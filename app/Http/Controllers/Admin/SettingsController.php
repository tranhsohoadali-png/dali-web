<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = DB::table('admin_settings')->pluck('value','key');
        $sizes    = Size::orderBy('sort_order')->get();
        return view('admin.settings', compact('settings', 'sizes'));
    }

    /**
     * Lưu bảng giá theo kích thước (áp dụng đồng loạt cho tất cả tranh).
     */
    public function updateSizes(Request $request)
    {
        $rows = $request->input('sizes', []);
        foreach ($rows as $id => $row) {
            $size = Size::find($id);
            if (!$size) continue;
            $size->update([
                'price'     => (int) preg_replace('/[^\d]/', '', $row['price'] ?? '0'),
                'is_active' => !empty($row['is_active']),
            ]);
        }
        return back()->with('success', 'Đã lưu bảng giá theo kích thước! Giá đã cập nhật cho tất cả tranh.');
    }

    public function update(Request $request)
    {
        $fields = ['tg_token','tg_chat_id','bank_id','bank_acc','bank_name','bank_label','shop_phone','shop_address','ga_id','fb_pixel_id','zalo_link','zalo_oa_id','meta_title','meta_description','meta_keywords','free_ship_from','ship_fee','discount_bank',
            'vtp_token','vtp_env','vtp_sender_name','vtp_sender_phone','vtp_sender_address','vtp_service','vtp_webhook_token','default_weight','color_tool_url'];
        foreach ($fields as $key) {
            if ($request->has($key)) {
                DB::table('admin_settings')->where('key',$key)->update(['value' => $request->$key]);
            }
        }
        // Checkbox bật/tắt VTP (chỉ xử lý khi form VTP được gửi)
        if ($request->has('vtp_form')) {
            DB::table('admin_settings')->where('key','vtp_enabled')
                ->update(['value' => $request->boolean('vtp_enabled') ? '1' : '0']);
        }
        if ($request->filled('new_password')) {
            // Bảo vệ: chỉ đổi khi 2 ô khớp nhau (tránh trình duyệt autofill làm đổi mật khẩu ngoài ý muốn)
            if ($request->input('new_password') !== $request->input('new_password_confirm')) {
                return back()->with('error', 'Mật khẩu xác nhận không khớp — KHÔNG đổi mật khẩu. Các cài đặt khác đã lưu.')->withInput();
            }
            DB::table('admin_settings')->where('key','admin_password')->update(['value' => Hash::make($request->new_password)]);
        }
        return back()->with('success', 'Đã lưu cài đặt!');
    }
}
