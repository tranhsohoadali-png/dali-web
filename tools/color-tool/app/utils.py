import os.path

import cv2
from datetime import datetime
import time

from PIL import Image
from django.conf import settings

from app.color_index_lib import index_color, get_draw_result
from app.dali_match import nearest_dali
from app.models import *


def split_list(pagination, imgColor):
    imgColorPagination = []
    list_tmp = []
    for i in imgColor:
        list_tmp.append(i)
        if len(list_tmp) == pagination:
            imgColorPagination.append(list_tmp)
            list_tmp = []
    imgColorPagination.append(list_tmp)
    return (imgColorPagination)


def getColor(input):
    result = convert_to_hex(input)
    imgColor = create_image_color(input, result)
    return imgColor


def convert_to_hex(input):
    resut = []
    for i in input:
        hex = '#%02x%02x%02x' % i[1]
        resut.append([i[0], hex.upper()])
    return resut


def save_img(edge_img, dpi=(72, 72)):
    print("Saving image with DPI:", dpi)
    now = str(datetime.fromtimestamp(time.time()).strftime("%Y-%m-%d_%H-%M-%S"))
    name_output = now + "_result.png"
    rgb_image = cv2.cvtColor(edge_img, cv2.COLOR_BGR2RGB)
    pil_image = Image.fromarray(rgb_image)
    # Dùng đường dẫn tuyệt đối (MEDIA_ROOT) để không phụ thuộc thư mục chạy (cwd).
    os.makedirs(settings.MEDIA_ROOT, exist_ok=True)
    out_path = os.path.join(settings.MEDIA_ROOT, name_output)
    pil_image.save(out_path, dpi=dpi)
    print("Image saved as:", out_path)
    return name_output, now


def create_image_color(input1, input2, percentages=None):
    # input1: [[index, (r, g, b)], ...]  (from index_color)
    # input2: [[index, "#RRGGBB"], ...]  (from convert_to_hex)
    # percentages: [float, ...] % diện tích mỗi màu (song song input1)
    # Each output row: [index, "#RRGGBB", dali_code, percent]
    # The DALI code is the merged-in feature from the django_dali project.
    result = []
    for i in range(len(input1)):
        rgb = input1[i][1]
        dali_code = nearest_dali(rgb)
        percent = percentages[i] if percentages and i < len(percentages) else 0
        result.append([input1[i][0], input2[i][1], dali_code, percent])

    return result


def save_result_to_db(BASE_DIR, name, user):
    # Tạo bản ghi ngay với trạng thái "processing" để frontend biết đang xử lý.
    obj = ImageModel.objects.create(
        name=name, image=name, user=user,
        name_output='', image_output='', colors=[],
        status=ImageModel.STATUS_PROCESSING, error_message=''
    )
    try:
        print("Start processing image:", name)
        edge_img, color_mapping, percentages = index_color(f"{BASE_DIR}/media/{name}", debug=False)
        dpi = Image.open(f"{BASE_DIR}/media/{name}").info.get('dpi', (72, 72))
        name_output, name_sufix = save_img(edge_img, dpi)
        result = convert_to_hex(color_mapping)
        imgColor = create_image_color(color_mapping, result, percentages)
        print("Image processing completed for:", name)
        obj.name_output = name_output
        obj.image_output = name_output
        obj.colors = imgColor
        obj.status = ImageModel.STATUS_DONE
        obj.save()
        print("ImageModel updated:", obj)
    except Exception as e:
        print(f"Error processing image {name}: {e}")
        obj.status = ImageModel.STATUS_ERROR
        obj.error_message = str(e)
        obj.save()
        return None

def get_paint_image(BASE_DIR, file_path, image_name: str, option: str, orientation='portrait'):
    image_result_full_path = f"{BASE_DIR}{file_path}"
    width, height = option.split('x')
    width = int(width)
    height = int(height)
    image_paint, image_a3 = get_draw_result(image_result_full_path, width, height, image_name, orientation=orientation)
    filename, ext = os.path.splitext(image_result_full_path)
    filename_paint = f'/media/{image_name}_painting.png'
    filename_a3 = f'/media/{image_name}_a3.png'
    cv2.imwrite(f'{BASE_DIR}{filename_paint}', image_paint)
    cv2.imwrite(f'{BASE_DIR}{filename_a3}', image_a3)
    return filename_paint, filename_a3