<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Affiliate;

class CtvAuth
{
    public function handle(Request $request, Closure $next)
    {
        $id = session('ctv_id');
        if (!$id) {
            return redirect()->route('ctv.login');
        }
        $ctv = Affiliate::find($id);
        if (!$ctv || !$ctv->is_active) {
            session()->forget('ctv_id');
            return redirect()->route('ctv.login')->with('error', 'Tài khoản không khả dụng. Vui lòng đăng nhập lại.');
        }
        // chia sẻ cho controller / view
        $request->attributes->set('ctv', $ctv);
        view()->share('ctv', $ctv);
        return $next($request);
    }
}
