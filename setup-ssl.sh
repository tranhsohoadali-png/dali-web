#!/usr/bin/env bash
# ==========================================================
#  Bat ten mien + HTTPS (SSL mien phi Let's Encrypt) cho DALI.
#  CHAY SAU KHI da tro DNS ten mien ve IP server (72.62.76.78).
#
#  Cach dung (chay bang root):
#    bash setup-ssl.sh tranhdali.vn
# ==========================================================
set -e
cd "$(dirname "$0")"

DOMAIN="${1:?Hay truyen ten mien. Vi du:  bash setup-ssl.sh tranhdali.vn}"
APP_DIR="$(pwd)"

echo "==> 1/4  Cap nhat vhost Apache cho $DOMAIN"
cat > /etc/apache2/sites-available/dali-web.conf <<EOF
<VirtualHost *:80>
    ServerName $DOMAIN
    ServerAlias www.$DOMAIN
    DocumentRoot $APP_DIR/public

    <Directory $APP_DIR/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/dali-web-error.log
    CustomLog \${APACHE_LOG_DIR}/dali-web-access.log combined
</VirtualHost>
EOF
a2ensite dali-web.conf >/dev/null 2>&1 || true
systemctl reload apache2

echo "==> 2/4  Cai certbot (Let's Encrypt cho Apache)"
apt-get update -y
apt-get install -y certbot python3-certbot-apache

echo "==> 3/4  Lay chung chi SSL + bat https (tu redirect http->https)"
certbot --apache -d "$DOMAIN" -d "www.$DOMAIN" \
    --non-interactive --agree-tos --register-unsafely-without-email --redirect

echo "==> 4/4  Cap nhat APP_URL -> https://$DOMAIN"
sed -i "s|^APP_URL=.*|APP_URL=https://$DOMAIN|" .env
php artisan config:cache

echo ""
echo "============================================================"
echo "  XONG! Mo: https://$DOMAIN"
echo "  Chung chi tu gia han (certbot.timer). Kiem tra:"
echo "    certbot certificates"
echo "============================================================"
