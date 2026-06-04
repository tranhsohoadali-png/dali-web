#!/bin/bash
# ==========================================================
# Cài đặt công cụ tách màu + mã DALI trên Ubuntu/Linux server
# Chạy: bash tools/color-tool/setup_ubuntu.sh
# ==========================================================
set -e

TOOL_DIR="$(cd "$(dirname "$0")" && pwd)"
VENV_DIR="$TOOL_DIR/venv"
SERVICE_FILE="/etc/systemd/system/dali-color-tool.service"
TOOL_USER="www-data"

echo "=== DALI Color Tool Setup ==="
echo "Tool dir: $TOOL_DIR"

# 1. Kiểm tra Python
if ! command -v python3 &>/dev/null; then
    echo "Cài Python3..."
    apt-get install -y python3 python3-pip python3-venv
fi

# 2. Cài thư viện system cần thiết (opencv-headless cần libGL)
echo "Cài system libs..."
apt-get install -y libglib2.0-0 libsm6 libxrender1 libxext6 2>/dev/null || true

# 3. Tạo virtualenv
echo "Tạo virtualenv tại $VENV_DIR..."
python3 -m venv "$VENV_DIR"
source "$VENV_DIR/bin/activate"

# 4. Cài Python packages
echo "Cài Python packages..."
pip install --upgrade pip
pip install -r "$TOOL_DIR/requirements.txt"

# 5. Tạo thư mục cần thiết
mkdir -p "$TOOL_DIR/media" "$TOOL_DIR/database" "$TOOL_DIR/static"
chown -R $TOOL_USER:$TOOL_USER "$TOOL_DIR/media" "$TOOL_DIR/database" 2>/dev/null || true

# 6. Migrate database Django
echo "Migrate Django database..."
python "$TOOL_DIR/manage.py" migrate --run-syncdb 2>/dev/null || true

# File sqlite vừa tạo do root sở hữu -> trả lại cho service user (ghi được)
chown -R $TOOL_USER:$TOOL_USER "$TOOL_DIR/database" "$TOOL_DIR/media" 2>/dev/null || true

# 7. Tạo systemd service
echo "Tạo systemd service..."
cat > "$SERVICE_FILE" <<EOF
[Unit]
Description=DALI Color Tool (Django - port 18001)
After=network.target

[Service]
Type=simple
User=$TOOL_USER
WorkingDirectory=$TOOL_DIR
ExecStart=$VENV_DIR/bin/python $TOOL_DIR/manage.py runserver 127.0.0.1:18001
Restart=on-failure
RestartSec=5
StandardOutput=journal
StandardError=journal
Environment=COLOR_TOOL_DEBUG=false

[Install]
WantedBy=multi-user.target
EOF

# 8. Enable và start service
systemctl daemon-reload
systemctl enable dali-color-tool
systemctl restart dali-color-tool

echo ""
echo "=== Hoàn tất! ==="
echo "Tool đang chạy tại: http://127.0.0.1:18001"
echo "Kiểm tra: systemctl status dali-color-tool"
echo "Log: journalctl -u dali-color-tool -f"
