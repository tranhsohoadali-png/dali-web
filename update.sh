#!/usr/bin/env bash
#
# Cập nhật DALI sau khi có code mới (chạy trên server).
# Dùng cho các lần update THÔNG THƯỜNG (sửa code, thêm bảng).
# KHÔNG đụng tới file database.sqlite (giữ nguyên đơn hàng thật).
#
#   bash update.sh
#
set -e
cd "$(dirname "$0")"

echo "==> 1/4  Kéo code mới"
git pull

echo "==> 2/4  Áp dụng thay đổi database (nếu có bảng/cột mới)"
php artisan migrate --force        # an toàn: không có gì mới thì bỏ qua, KHÔNG xoá dữ liệu

echo "==> 3/4  Dọn + dựng lại cache"
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> 4/4  Quyền ghi cho web"
sudo chown -R www-data:www-data storage bootstrap/cache database
sudo chmod -R 775 storage bootstrap/cache database

echo ""
echo "✅ Cập nhật xong! Tải lại tranhdali.vn để kiểm tra."
echo "   (Nếu tôi báo 'lần này có đổi thư viện' thì cần cập nhật thêm vendor — xem README.)"

# Restart color tool nếu service đã được cài
if systemctl is-active --quiet dali-color-tool 2>/dev/null; then
    echo "==> Restart công cụ tách màu..."
    sudo systemctl restart dali-color-tool
    echo "   ✅ dali-color-tool restarted"
fi
