# Công cụ tách màu + mã DALI (color-tool)

App Django (gốc: `index_color_dali_merged`) đã được **gộp vào repo dali-v4**.
Upload 1 ảnh → tách màu chủ đạo, tạo bản đồ màu đánh số, khớp **mã DALI** gần
nhất, xuất CSV/Excel + ảnh chú giải. Website Laravel nhúng tool này trong admin
(**Tách màu & mã DALI**) qua proxy `ColorToolController`.

Tool lắng nghe `http://127.0.0.1:18001` (chỉ localhost; truy cập qua proxy admin).

## Chạy

| Môi trường | Cách chạy | Python dùng |
|-----------|-----------|-------------|
| Dev Windows | `run.bat` (hoặc `..\..\start-dev.bat` để chạy cùng Laravel) | `python_embed/` nhúng sẵn |
| Server Ubuntu | `bash setup_ubuntu.sh` (deploy.sh tự gọi) → systemd `dali-color-tool` | venv + `requirements.txt` |

## Ghi chú kỹ thuật

- `python_embed/` (Windows-only, ~280MB) **không** đưa vào git — xem `.gitignore`.
- `polylabelfast` (Windows-only) tự fallback sang **shapely** trên Linux —
  `app/color_index_lib.py`.
- Media (`/media/...`, ảnh kết quả) được phục vụ **không phụ thuộc DEBUG**
  (`ColorPickProject/urls.py`) để ảnh hiển thị qua proxy khi `COLOR_TOOL_DEBUG=false`.
- DB SQLite: `database/index-color.sqlite3` (tạo bằng `manage.py migrate`).
