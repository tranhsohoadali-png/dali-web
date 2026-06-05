#!/usr/bin/env bash
# ==========================================================
#  Tu dong deploy khi GitHub co commit moi (chay dinh ky qua cron).
#  - Chi deploy khi remote main khac local (khoi ton tai nguyen).
#  - Tu cai lai vendor neu composer.lock doi.
#  - migrate + dung lai cache + restart cong cu mau.
#  KHONG dung toi database.sqlite (giu nguyen don hang that).
#
#  Cai cron (chay bang root):
#    (crontab -l 2>/dev/null; echo '*/2 * * * * cd /var/www/dali-web && bash auto-deploy.sh >> /var/log/dali-deploy.log 2>&1') | crontab -
# ==========================================================
set -e
cd "$(dirname "$0")"

git fetch origin main --quiet
LOCAL=$(git rev-parse HEAD)
REMOTE=$(git rev-parse origin/main)

# Khong co gi moi -> thoat im lang (cron chay lien tuc nen khong in rac)
[ "$LOCAL" = "$REMOTE" ] && exit 0

echo "=== $(date '+%F %T')  Co commit moi ($REMOTE) -> trien khai ==="

git reset --hard origin/main    # khop dung remote (chi code, khong dung DB)

# Neu doi thu vien PHP -> cai lai vendor
if ! git diff --quiet "$LOCAL" HEAD -- composer.lock; then
  echo "    composer.lock doi -> composer install"
  export COMPOSER_ALLOW_SUPERUSER=1
  composer install --no-dev --optimize-autoloader --no-interaction
fi

php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

chown -R www-data:www-data storage bootstrap/cache database
chmod -R 775 storage bootstrap/cache database

# Restart cong cu mau neu co thay doi trong tools/color-tool
if systemctl is-active --quiet dali-color-tool 2>/dev/null; then
  systemctl restart dali-color-tool || true
fi

echo "=== $(date '+%F %T')  Deploy xong ==="
