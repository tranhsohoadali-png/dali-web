#!/usr/bin/env bash
# ==========================================================
#  Tu dong deploy khi GitHub co commit moi (chay dinh ky qua cron).
#  - Chi deploy khi remote main khac local (khoi ton tai nguyen).
#  - CONG KIEM: truoc khi dung vao web that, chay tests/Feature/PodTest.php
#    (cam ket giu kin mau rieng cua seller POD) tren ban sao rieng $CI_DIR.
#    Phep thu DO -> KHONG deploy, web giu ban cu, ghi ly do vao log.
#  - Tu cai lai vendor neu composer.lock doi.
#  - migrate + dung lai cache + restart cong cu mau.
#  KHONG dung toi database.sqlite (giu nguyen don hang that).
#
#  Cai cron (chay bang root):
#    (crontab -l 2>/dev/null; echo '*/2 * * * * cd /var/www/dali-web && bash auto-deploy.sh >> /var/log/dali-deploy.log 2>&1') | crontab -
#
#  Khi bi chan (log co "DUNG DEPLOY"):
#    - Sua code, push commit moi -> tu kiem lai.
#    - Thu lai cung commit (vd loi mang luc composer):  rm /var/lib/dali-deploy/chan-deploy
#    - KHAN CAP, deploy khong qua kiem:  echo <hash commit> > /var/lib/dali-deploy/bo-qua-kiem
#  Chi chay kiem, khong deploy:  bash auto-deploy.sh --chi-kiem <commit>
# ==========================================================
set -e
cd "$(dirname "$0")"

CI_DIR=/opt/dali-web-ci          # ban sao rieng co thu vien dev de chay phep thu; KHONG phai web that
TT=/var/lib/dali-deploy          # file trang thai, NGOAI $CI_DIR (git clean trong ban sao se xoa file la)
CHAN="$TT/chan-deploy"           # commit da bi chan -> khong chay lai moi 2 phut
BO_QUA="$TT/bo-qua-kiem"         # commit duoc deploy khong qua kiem (khan cap)
mkdir -p "$TT"

# Chay PodTest cho commit $1 tren $CI_DIR. Tra 0 = xanh. Moi buoc tu kiem ma tra ve
# vi set -e KHONG co tac dung ben trong ham duoc goi o vi tri dieu kien (if/!).
kiem_thu() {
  local rev="$1"
  export COMPOSER_ALLOW_SUPERUSER=1
  if [ ! -e "$CI_DIR/.git" ]; then
    echo "    Tao ban sao kiem thu $CI_DIR"
    git worktree prune
    git worktree add --detach "$CI_DIR" "$rev" >/dev/null 2>&1 || { echo "    Khong tao duoc $CI_DIR"; return 1; }
  fi
  (
    cd "$CI_DIR" || exit 1
    git checkout --detach --force --quiet "$rev" || exit 1
    git clean -fd --quiet || exit 1            # giu file bi gitignore: vendor/, .env
    if [ ! -f .env ]; then cp .env.example .env || exit 1; fi
    mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/framework/testing storage/logs bootstrap/cache
    h=$(sha1sum composer.lock | cut -d' ' -f1)
    if [ ! -d vendor ] || [ "$(cat "$TT/lock-da-cai" 2>/dev/null)" != "$h" ]; then
      echo "    composer install (co dev) cho ban kiem thu"
      composer install --no-interaction --no-progress --prefer-dist >/dev/null 2>&1 || { echo "    composer install loi"; exit 1; }
      echo "$h" > "$TT/lock-da-cai"
    fi
    grep -q '^APP_KEY=base64' .env || php artisan key:generate --force >/dev/null || exit 1
    php artisan config:clear >/dev/null 2>&1 || true
    if timeout 600 php artisan test --filter=PodTest > "$TT/ket-qua-kiem.log" 2>&1; then
      echo "    PodTest xanh: $(sed 's/\x1b\[[0-9;]*m//g' "$TT/ket-qua-kiem.log" | grep -E 'Tests:' | tr -s ' ')"
    else
      echo "    PodTest DO. Chi tiet: $TT/ket-qua-kiem.log"
      sed 's/\x1b\[[0-9;]*m//g' "$TT/ket-qua-kiem.log" | grep -E '⨯|FAILED|Failed asserting|Tests:' | head -15 | sed 's/^/      /'
      exit 1
    fi
  )
}

if [ "${1:-}" = "--chi-kiem" ]; then
  git fetch origin main --quiet
  if kiem_thu "${2:-origin/main}"; then echo "KET QUA: XANH"; exit 0; else echo "KET QUA: DO"; exit 1; fi
fi

git fetch origin main --quiet
LOCAL=$(git rev-parse HEAD)
REMOTE=$(git rev-parse origin/main)

# Khong co gi moi -> thoat im lang (cron chay lien tuc nen khong in rac)
[ "$LOCAL" = "$REMOTE" ] && exit 0

# Commit nay da bi chan -> im lang, doi commit moi (hoac xoa $CHAN de kiem lai)
[ -f "$CHAN" ] && [ "$(cat "$CHAN")" = "$REMOTE" ] && exit 0

echo "=== $(date '+%F %T')  Co commit moi ($REMOTE) -> kiem thu ==="

if [ -f "$BO_QUA" ] && [ "$(cat "$BO_QUA")" = "$REMOTE" ]; then
  echo "    BO QUA phep thu (khan cap, theo $BO_QUA)"
elif ! kiem_thu "$REMOTE"; then
  echo "$REMOTE" > "$CHAN"
  echo "!!! $(date '+%F %T')  DUNG DEPLOY: PodTest do o commit $REMOTE. Web giu ban cu $LOCAL."
  echo "    Sua roi push commit moi; thu lai: rm $CHAN ; khan cap: echo $REMOTE > $BO_QUA"
  exit 0
fi

echo "    -> trien khai"
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

echo "=== $(date '+%F %T')  Deploy xong ==="
