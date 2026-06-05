#!/usr/bin/env bash
# ==========================================================
#  Bat TU DONG DEPLOY: cai cron chay auto-deploy.sh moi 2 phut.
#  Chay bang root:  bash setup-autodeploy.sh
#  Chay lai nhieu lan duoc (khong tao trung dong).
# ==========================================================
set -e
cd "$(dirname "$0")"
SCRIPT="$(pwd)/auto-deploy.sh"

LINE="*/2 * * * * /bin/bash $SCRIPT >> /var/log/dali-deploy.log 2>&1"

# Xoa dong auto-deploy cu (neu co) roi them lai -> idempotent
( crontab -l 2>/dev/null | grep -v 'auto-deploy.sh' ; echo "$LINE" ) | crontab -

echo ""
echo "============================================================"
echo "  Da BAT tu dong deploy (kiem tra GitHub moi 2 phut)."
echo "  Tu gio: push len GitHub -> server tu cap nhat sau <=2 phut."
echo ""
echo "  Xem log:   tail -f /var/log/dali-deploy.log"
echo "  Tat di:    crontab -e   (xoa dong co auto-deploy.sh)"
echo "============================================================"
crontab -l | grep auto-deploy.sh || true
