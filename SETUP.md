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
