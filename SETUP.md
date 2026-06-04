# DALI – Hướng dẫn cài đặt

## Yêu cầu
- PHP 8.1+
- Composer
- MySQL / SQLite

## Cài đặt (5 bước)

### 1. Cài thư viện
```bash
composer install
```

### 2. Tạo file .env
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Cấu hình database trong .env
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tranh_dali
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Chạy migration
```bash
php artisan migrate
php artisan storage:link
```

### 5. Khởi động
```bash
php artisan serve
```

## Truy cập
| Trang | URL |
|-------|-----|
| Trang chủ | http://localhost:8000 |
| Quản lý Hero | http://localhost:8000/admin/hero |
| Quản lý Danh mục | http://localhost:8000/admin/categories |
| Quản lý Sản phẩm | http://localhost:8000/admin/products |

## Quy trình thêm sản phẩm
1. Vào `/admin/categories` → thêm danh mục
2. Vào `/admin/products` → thêm sản phẩm, chọn danh mục
3. Trang chủ tự hiển thị sản phẩm từ DB

## Ghi chú
- Logo nằm tại `public/images/logo_dali.png`
- Ảnh upload lưu vào `storage/app/public/`
- Chạy `php artisan storage:link` để ảnh hiển thị được

## Công cụ tách màu + mã DALI (admin)

Công cụ **index_color** (tách màu, bản đồ đánh số, khớp mã DALI) đã được gộp vào
repo này tại `tools/color-tool/` (Django). Admin nhúng nó qua proxy tại
**Admin → Tách màu & mã DALI** (`/admin/cong-cu-tach-mau`). Tool lắng nghe ở
`http://127.0.0.1:18001`; có thể đổi URL trong **Cài đặt → URL công cụ tách màu**.

### Dev (Windows) — tự khởi động cùng Laravel
```
start-dev.bat
```
Chạy đồng thời web Laravel (cổng 8000) và công cụ tách màu (cổng 18001). Công cụ
dùng `python_embed` nhúng sẵn nên **không cần cài Python**. (Chạy riêng công cụ:
`tools\color-tool\run.bat`.)

> Nếu trước đây bạn chạy bản cũ ở `E:\index_color_dali_merged` (run.bat), hãy
> **tắt** nó để khỏi trùng cổng 18001 — bản trong repo này đã thay thế.

### Server (Ubuntu) — auto-start bằng systemd
`deploy.sh` tự cài service `dali-color-tool` ở lần deploy đầu (gọi
`tools/color-tool/setup_ubuntu.sh`: tạo venv + cài deps + systemd enable). Các
lần `update.sh` sau sẽ tự restart service. Kiểm tra:
```
systemctl status dali-color-tool
journalctl -u dali-color-tool -f
```
