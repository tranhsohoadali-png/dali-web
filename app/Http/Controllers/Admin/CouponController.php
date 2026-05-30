<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.form', ['coupon' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'        => 'required|string|max:30|unique:coupons,code',
            'type'        => 'required|in:percent,fixed',
            'value'       => 'required|integer|min:1',
            'min_order'   => 'nullable|integer|min:0',
            'max_uses'    => 'nullable|integer|min:1',
            'expires_at'  => 'nullable|date|after:today',
            'is_active'   => 'nullable|boolean',
            'description' => 'nullable|string|max:255',
        ]);
        $data['code']      = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['min_order'] = $data['min_order'] ?? 0;
        Coupon::create($data);
        return redirect()->route('admin.coupons.index')->with('success', 'Đã thêm mã giảm giá!');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.form', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $request->validate([
            'code'        => 'required|string|max:30|unique:coupons,code,'.$coupon->id,
            'type'        => 'required|in:percent,fixed',
            'value'       => 'required|integer|min:1',
            'min_order'   => 'nullable|integer|min:0',
            'max_uses'    => 'nullable|integer|min:1',
            'expires_at'  => 'nullable|date',
            'is_active'   => 'nullable|boolean',
            'description' => 'nullable|string|max:255',
        ]);
        $data['code']      = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['min_order'] = $data['min_order'] ?? 0;
        $coupon->update($data);
        return redirect()->route('admin.coupons.index')->with('success', 'Đã cập nhật mã giảm giá!');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'Đã xoá mã giảm giá!');
    }
}
