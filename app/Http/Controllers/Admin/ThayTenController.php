<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Xưởng in 3D › Thay tên mẫu: khung iframe tới app FastAPI "thay tên mẫu"
 * (chạy Docker trên VPS, cổng 127.0.0.1:8021; nginx proxy /admin/3d/thay-ten/app/).
 *
 * kiem() là cổng cho nginx auth_request: 204 nếu admin đã đăng nhập, 401 nếu chưa.
 *   - CHỈ nhận subrequest nội bộ: location /_thayten_kiem của nginx đặt fastcgi_param
 *     THAYTEN_NOIBO=1. Tham số FastCGI không có tiền tố HTTP_ nên trình duyệt không giả được
 *     bằng header. Gọi thẳng https://tranhdali.vn/admin/3d/thay-ten/kiem (kể cả dạng
 *     /index.php/admin/3d/thay-ten/kiem, lọt qua mọi location nginx) -> 404, để không ai dùng
 *     cổng này dò xem một cookie phiên còn là admin hay không.
 *   - Route kiem được gỡ StartSession (xem routes/web.php) nên ở đây tự nạp phiên CHỈ ĐỌC,
 *     không save(): app hỏi trạng thái mỗi 0,7-5 giây mà
 *       + không ghi bảng sessions (SQLite) mỗi lần,
 *       + không làm "già" flash (thông báo / lỗi form ở tab admin khác không bị mất),
 *       + không đè url.previous (back() ở trang khác không nhảy về /kiem).
 *
 * Gia hạn phiên: KHÔNG làm ở kiem(). Cookie laravel-session mang Expires = 120 phút kể từ
 * response Laravel gần nhất (expire_on_close=false), nên chỉ cập nhật last_activity trong DB
 * là không đủ. Việc gia hạn dựa vào script hỏi đơn mới mỗi 25 giây của sidebar
 * (orders.new-count, có StartSession). Vì vậy tab "Mở toàn màn hình" cũng là trang Laravel này
 * (?toan=1: ẩn sidebar + topbar nhưng VẪN nạp sidebar), không mở thẳng app.
 */
class ThayTenController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.thayten.index', ['toan' => $request->boolean('toan')]);
    }

    public function kiem(Request $request)
    {
        if ($request->server('THAYTEN_NOIBO') !== '1') {    // không đến từ location nội bộ của nginx
            return response('', 404)->header('Cache-Control', 'no-store');
        }

        $phien = app('session')->driver();
        $phien->setId($request->cookies->get($phien->getName()));
        $phien->start();                                  // chỉ đọc; KHÔNG gọi save()

        $ma = $phien->get('admin_logged_in') ? 204 : 401; // cùng khoá AdminAuth kiểm
        return response('', $ma)->header('Cache-Control', 'no-store');
    }
}
