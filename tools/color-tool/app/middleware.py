"""
Middleware cho phép công cụ gộp được NHÚNG (iframe) bởi website DALI.

Mặc định Django gửi header X-Frame-Options khiến trình duyệt chặn nhúng iframe
từ origin khác (website DALI chạy cổng 8000, công cụ này chạy cổng 18001 ->
khác origin). Middleware này gỡ X-Frame-Options và đặt Content-Security-Policy
'frame-ancestors' để chỉ cho phép các origin tin cậy (localhost) nhúng.

Không ảnh hưởng khi chạy độc lập — chỉ thêm header cho phép nhúng.
"""

# Các origin được phép nhúng công cụ này (website DALI + chính nó).
ALLOWED_FRAME_ANCESTORS = (
    "'self' "
    "http://localhost:8000 http://127.0.0.1:8000 "
    "http://localhost:18001 http://127.0.0.1:18001"
)


class FrameEmbedMiddleware:
    def __init__(self, get_response):
        self.get_response = get_response

    def __call__(self, request):
        response = self.get_response(request)
        # Gỡ X-Frame-Options (chặn nhúng cross-origin) và dùng CSP thay thế.
        if 'X-Frame-Options' in response:
            del response['X-Frame-Options']
        response['Content-Security-Policy'] = f"frame-ancestors {ALLOWED_FRAME_ANCESTORS}"
        return response
