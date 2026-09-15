<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Xưởng in 3D › Thay tên mẫu: khung iframe tới app FastAPI "thay tên mẫu"
 * (chạy Docker trên VPS, cổng 127.0.0.1:8021; nginx proxy /admin/3d/thay-ten/app/).
 *
 * kiem() là cổng cho nginx auth_request: 204 nếu admin đã đăng nhập, 401 nếu chưa.
 * Route kiem được gỡ StartSession (xem routes/web.php) nên ở đây tự nạp phiên
 * CHỈ ĐỌC, không save(): app hỏi trạng thái mỗi 0,7-5 giây mà
 *   - không ghi bảng sessions (SQLite) mỗi lần,
 *   - không làm "già" flash (thông báo / lỗi form ở tab admin khác không bị mất),
 *   - không đè url.previous (back() ở trang khác không nhảy về /kiem).
 * Phiên vẫn được gia hạn nhờ trang admin.thayten (sidebar hỏi đơn mới mỗi 25 giây).
 */
class ThayTenController extends Controller
{
    public function index()
    {
        return view('admin.thayten.index');
    }

    public function kiem(Request $request)
    {
        $phien = app('session')->driver();
        $phien->setId($request->cookies->get($phien->getName()));
        $phien->start();                                  // chỉ đọc; KHÔNG gọi save()

        $ma = $phien->get('admin_logged_in') ? 204 : 401; // cùng khoá AdminAuth kiểm
        return response('', $ma)->header('Cache-Control', 'no-store');
    }
}
