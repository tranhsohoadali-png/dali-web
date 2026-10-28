<?php
namespace App\Http\Middleware;

use App\Models\PodThoIn;
use Closure;
use Illuminate\Http\Request;

/** Chỉ cho thợ in đã đăng nhập (session tho_in_id) và đang mở. Không liên quan đăng nhập admin. */
class ThoInAuth
{
    public function handle(Request $request, Closure $next)
    {
        $id = session('tho_in_id');
        $tho = $id ? PodThoIn::whereKey($id)->where('hien', true)->first() : null;
        if (!$tho) {
            session()->forget('tho_in_id');
            return redirect()->route('tho-in.dang-nhap');
        }
        $request->attributes->set('tho_in', $tho);
        return $next($request);
    }
}
