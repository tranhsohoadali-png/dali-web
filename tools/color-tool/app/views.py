import csv
import os
import time
from concurrent.futures import ThreadPoolExecutor

from PIL import Image
from django.contrib import messages
from django.contrib.auth import login, authenticate, logout  # add this
from django.core.files.storage import FileSystemStorage
from django.http import Http404, HttpResponseNotFound
from django.http import HttpResponse
from django.http import JsonResponse
from django.shortcuts import render, redirect
from django.views.decorators.csrf import csrf_exempt

from ColorPickProject.settings import BASE_DIR
from app import dali_match
from app.exports import build_xlsx, build_legend_image
from app.form import LoginForm
from app.utils import *

executor = ThreadPoolExecutor(max_workers=5)
NUM_ROWS = 10
MAX_LEN_FILENAME = 28


def format_filename(filename: str) -> str:
    first_dot = filename.find('.')
    pre_time = filename[:first_dot]
    first_underscore = filename.find('_')
    post_fix_filename = filename[first_underscore + 1:]
    post_fix_filenames = post_fix_filename.split(' ')

    post_fix_filenames = [f[:MAX_LEN_FILENAME] + '...' if len(f) > MAX_LEN_FILENAME else f for f in post_fix_filenames]
    post_fix_filename = ' '.join(post_fix_filenames)
    new_filename = f'{pre_time} {post_fix_filename}'
    return new_filename


@csrf_exempt
def homepage(request):
    # Bỏ qua đăng nhập: công cụ được nhúng trong admin DALI (đã có bảo vệ riêng)
    last_query = ImageModel.objects.all().order_by('-created_time')[:50]
    last_query = [{'name': format_filename(q.name), 'url': q.name} for q in last_query]

    if request.method == 'POST':
        upload = request.FILES['image']
        fss = FileSystemStorage()
        name = f'{datetime.now().strftime("%Y-%m-%d_%H-%M-%S")}_{upload.name}'
        user = str(request.user)

        file = fss.save(name, upload)
        file_url = fss.url(file)

        # call hàm tính toán - hàm này chạy ngầm - lưu vào db
        executor.submit(save_result_to_db, BASE_DIR, name, user)
        return render(request, 'homepage.html', {'file_url': file_url, 'last_query': last_query})

    return render(request, 'homepage.html', {'last_query': last_query})


@csrf_exempt
def get_result(request):
    if request.method == 'POST':
        data = request.POST.get('file_url')
        if data is not None:
            res = ImageModel.objects.filter(name=data.replace("/media/", "")).order_by('-created_time').first()
            if res:
                if res.status == ImageModel.STATUS_PROCESSING:
                    return JsonResponse({'status': 'processing'})
                if res.status == ImageModel.STATUS_ERROR:
                    return JsonResponse({'status': 'error', 'error': res.error_message})
                colors = split_list(NUM_ROWS, res.colors)
                return JsonResponse({'status': 'done', 'img_output': res.image_output.url, "colors": colors})
            return JsonResponse({'status': 'processing'})


@csrf_exempt
def export_colors(request):
    """
    Export the color table (number, HEX, R, G, B, DALI code) of a processed
    image as a CSV file. This is the merged-in DALI export feature from the
    django_dali project, but kept dependency-free (stdlib csv -> opens in Excel).
    """
    data = request.GET.get('file_url') or request.POST.get('file_url')
    if not data:
        return HttpResponseNotFound("Missing file_url")
    res = ImageModel.objects.filter(name=data.replace("/media/", "")).first()
    if not res:
        return HttpResponseNotFound("Do not have result")

    response = HttpResponse(content_type='text/csv; charset=utf-8-sig')
    response['Content-Disposition'] = 'attachment; filename="color_dali_table.csv"'
    # BOM so Vietnamese characters / Excel open correctly.
    response.write('﻿')
    writer = csv.writer(response)
    writer.writerow(['STT', 'HEX', 'R', 'G', 'B', 'Ma_DALI', 'Phan_tram_dien_tich'])
    for row in res.colors:
        # row: [index, "#RRGGBB", dali_code, percent]
        index = row[0]
        hex_value = row[1]
        dali_code = row[2] if len(row) > 2 else ''
        percent = row[3] if len(row) > 3 else ''
        h = hex_value.lstrip('#')
        try:
            r = int(h[0:2], 16)
            g = int(h[2:4], 16)
            b = int(h[4:6], 16)
        except (ValueError, IndexError):
            r = g = b = ''
        writer.writerow([index, hex_value, r, g, b, dali_code, percent])
    return response


def _get_record(request):
    data = request.GET.get('file_url') or request.POST.get('file_url')
    if not data:
        return None
    return ImageModel.objects.filter(name=data.replace("/media/", "")) \
        .order_by('-created_time').first()


@csrf_exempt
def export_xlsx(request):
    """Xuất bảng màu ra Excel (.xlsx) có tô màu nền theo HEX."""
    res = _get_record(request)
    if not res:
        return HttpResponseNotFound("Do not have result")
    out_path = os.path.join(BASE_DIR, 'media', 'bang_mau_dali.xlsx')
    build_xlsx(res.colors, out_path)
    with open(out_path, 'rb') as f:
        content = f.read()
    response = HttpResponse(
        content,
        content_type='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
    response['Content-Disposition'] = 'attachment; filename="bang_mau_dali.xlsx"'
    return response


@csrf_exempt
def download_legend(request):
    """Tải ảnh (màu) kèm bảng chú giải số thứ tự -> mã DALI, bố cục như mẫu K496."""
    res = _get_record(request)
    if not res:
        return HttpResponseNotFound("Do not have result")
    # Ảnh bên trái: ưu tiên ảnh gốc (có màu); nếu thiếu thì dùng ảnh kết quả.
    left_name = res.name if os.path.exists(os.path.join(BASE_DIR, 'media', res.name)) else res.name_output
    if not left_name:
        return HttpResponseNotFound("Do not have image")
    left_path = os.path.join(BASE_DIR, 'media', left_name)
    title = (request.GET.get('title') or request.POST.get('title') or '').strip()
    out_path = os.path.join(BASE_DIR, 'media', f'{res.name_output or res.name}_legend.png')
    build_legend_image(left_path, res.colors, out_path, title=title)
    with open(out_path, 'rb') as f:
        content = f.read()
    response = HttpResponse(content, content_type='image/png')
    response['Content-Disposition'] = 'attachment; filename="bang_mau_DALI.png"'
    return response


@csrf_exempt
def dali_colors(request):
    """Trang quản lý bảng màu DALI: xem / tìm kiếm / thêm / xoá / nạp lại."""
    # Bỏ qua đăng nhập (xem chú thích ở homepage)
    if request.method == 'POST':
        action = request.POST.get('action')
        if action == 'add':
            ok, msg = dali_match.add_entry(request.POST.get('hex', ''), request.POST.get('dali', ''))
            messages.info(request, msg)
        elif action == 'delete':
            removed = dali_match.delete_entry(request.POST.get('hex', ''), request.POST.get('dali') or None)
            messages.info(request, f"Đã xoá {removed} mục." if removed else "Không tìm thấy mục để xoá.")
        elif action == 'reload':
            n = dali_match.reload_reference()
            messages.info(request, f"Đã nạp lại {n} màu từ file.")
        return redirect('dali_colors')

    query = (request.GET.get('q') or '').strip().lower()
    items = dali_match.get_all()
    if query:
        items = [it for it in items
                 if query in it['hex'].lower() or query in it['dali'].lower()]
    return render(request, 'dali_colors.html', {
        'items': items,
        'total': dali_match.reference_size(),
        'query': request.GET.get('q') or '',
    })


@csrf_exempt
def get_download_result(request):
    if request.method == 'GET':
        result_url = request.GET.get('result_url')
        image_name = request.GET.get('image_name')
        scale_option = request.GET.get('scale_option')
        orientation = request.GET.get('orientation_option')
        file_paint, file_a3 = get_paint_image(BASE_DIR, result_url, image_name=image_name, option=scale_option,
                                              orientation=orientation)
        return JsonResponse({'file_paint': file_paint, "file_a3": file_a3, 'origin_result': result_url})

    return HttpResponseNotFound("Do not have result")


def logout_request(request):
    # Bỏ đăng xuất: chuyển thẳng về trang chính
    return redirect('homepage')


@csrf_exempt
def login_request(request):
    # Bỏ đăng nhập: vào /login cũng chuyển thẳng về trang chính
    return redirect('homepage')
