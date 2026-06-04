#!/usr/bin/env bash
# ==========================================================
#  DALI - Cai dat server tu DAU tren Ubuntu 24.04 (VPS trong)
#  Chay BANG ROOT:   bash provision_ubuntu.sh
#
#  Lo tron goi: PHP 8.3 + Apache + Composer + Python venv,
#  tao vhost Apache, roi goi deploy.sh (Laravel + cong cu mau).
#  Chay lai nhieu lan duoc (idempotent).
# ==========================================================
set -e

# ----- Cau hinh (sua neu can) -----
REPO_URL="https://github.com/tranhsohoadali-png/dali-web.git"
APP_DIR="/var/www/dali-web"
SERVER_NAME="72.62.76.78"          # IP hoac ten mien tro ve VPS
# ----------------------------------

if [ "$(id -u)" -ne 0 ]; then
  echo "Hay chay bang root:  sudo bash provision_ubuntu.sh"; exit 1
fi

echo "==> 1/6  Cai goi he thong (PHP 8.3, Apache, Composer, git, Python)"
export DEBIAN_FRONTEND=noninteractive
apt-get update -y
apt-get install -y \
  apache2 libapache2-mod-php8.3 \
  php8.3 php8.3-cli php8.3-common php8.3-sqlite3 php8.3-mbstring \
  php8.3-xml php8.3-curl php8.3-gd php8.3-zip php8.3-bcmath php8.3-intl \
  composer git unzip curl \
  python3 python3-venv python3-pip

echo "==> 2/6  Lay code tu GitHub vao $APP_DIR"
if [ -d "$APP_DIR/.git" ]; then
  echo "    Da co repo -> git pull"
  git config --global --add safe.directory "$APP_DIR" || true
  git -C "$APP_DIR" pull --ff-only
else
  mkdir -p "$(dirname "$APP_DIR")"
  git clone "$REPO_URL" "$APP_DIR"
  git config --global --add safe.directory "$APP_DIR" || true
fi
cd "$APP_DIR"

echo "==> 3/6  Cau hinh Apache (DocumentRoot -> $APP_DIR/public)"
a2enmod rewrite >/dev/null 2>&1 || true
cat > /etc/apache2/sites-available/dali-web.conf <<EOF
<VirtualHost *:80>
    ServerName $SERVER_NAME
    DocumentRoot $APP_DIR/public

    <Directory $APP_DIR/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/dali-web-error.log
    CustomLog \${APACHE_LOG_DIR}/dali-web-access.log combined
</VirtualHost>
EOF
a2dissite 000-default.conf >/dev/null 2>&1 || true
a2ensite dali-web.conf >/dev/null 2>&1 || true

echo "==> 4/6  Trien khai ung dung (goi deploy.sh)"
# deploy.sh: composer install, .env + key, migrate, seed, cache,
#            cai service cong cu mau (dali-color-tool), phan quyen.
bash deploy.sh

echo "==> 5/6  Chuyen .env sang che do PRODUCTION"
sed -i 's|^APP_ENV=.*|APP_ENV=production|' .env
sed -i 's|^APP_DEBUG=.*|APP_DEBUG=false|' .env
sed -i "s|^APP_URL=.*|APP_URL=http://$SERVER_NAME|" .env
php artisan config:cache
php artisan route:cache
php artisan view:cache
chown -R www-data:www-data storage bootstrap/cache database
chmod -R 775 storage bootstrap/cache database

echo "==> 6/6  Khoi dong lai Apache"
systemctl restart apache2

echo ""
echo "============================================================"
echo "  XONG! Mo trinh duyet: http://$SERVER_NAME"
echo "  Admin:               http://$SERVER_NAME/admin/login"
echo "  Cong cu mau (service): systemctl status dali-color-tool"
echo "============================================================"
