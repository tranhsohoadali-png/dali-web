#!/usr/bin/env bash
#
# Triển khai DALI bằng SQLite (không cần MySQL) trên Ubuntu/Apache.
# Cách dùng:  bash deploy.sh
# Chạy lại nhiều lần được — sẽ KHÔNG seed đè dữ liệu nếu đã seed trước đó.
#
set -e
cd "$(dirname "$0")"

echo "==> 0/7  Tạo thư mục runtime (clone từ git thường thiếu các thư mục này)"
mkdir -p bootstrap/cache \
         storage/framework/cache/data \
         storage/framework/sessions \
         storage/framework/views \
         storage/logs \
         storage/app/public

echo "==> 1/7  Cài thư viện PHP (vendor)"
export COMPOSER_ALLOW_SUPERUSER=1   # chay bang root -> khoi hoi xac nhan
composer install --no-dev --optimize-autoloader --no-interaction

echo "==> 2/7  Tạo file .env + APP_KEY (nếu chưa có)"
if [ ! -f .env ]; then
  cp .env.example .env
  php artisan key:generate
  echo "    Đã tạo .env mới. Nhớ sửa APP_URL, APP_ENV=production, APP_DEBUG=false."
else
  echo "    .env đã có — giữ nguyên."
fi

echo "==> 3/7  Tạo file database SQLite (nếu chưa có)"
mkdir -p database
[ -f database/database.sqlite ] || touch database/database.sqlite

echo "==> 4/7  Tạo bảng (migrate)"
php artisan migrate --force

echo "==> 5/7  Đổ dữ liệu mẫu (CHỈ lần đầu — chống đè đơn thật)"
if [ ! -f storage/.seeded ]; then
  php artisan db:seed --class=ProductionSeeder --force
  touch storage/.seeded
  echo "    Đã đổ 8 sản phẩm + cài đặt."
else
  echo "    Đã seed trước đó -> BỎ QUA (giữ an toàn dữ liệu hiện có)."
fi

echo "==> 6/7  Liên kết ảnh + dọn cache"
php artisan storage:link 2>/dev/null || true
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> 7/7  Phân quyền cho Apache (www-data)"
# Thư mục database PHẢI ghi được (SQLite tạo file -journal/-wal cùng chỗ)
sudo chown -R www-data:www-data storage bootstrap/cache database
sudo chmod -R 775 storage bootstrap/cache database

echo ""
echo "✅ XONG! Kiểm tra:"
echo "   - Apache DocumentRoot phải trỏ vào: $(pwd)/public"
echo "   - Mở trang web + /admin/login để kiểm tra."
