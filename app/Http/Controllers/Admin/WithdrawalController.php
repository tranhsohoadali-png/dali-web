<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function index()
    {
        $withdrawals = Withdrawal::with('affiliate')->latest()->paginate(30);
        $pendingCount = Withdrawal::where('status', 'pending')->count();
        $pendingTotal = Withdrawal::where('status', 'pending')->sum('amount');
        return view('admin.withdrawals.index', compact('withdrawals', 'pendingCount', 'pendingTotal'));
    }

    // Duyệt: đánh dấu đã chuyển khoản -> cộng vào total_paid của CTV
    public function approve(Request $request, Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Yêu cầu này đã được xử lý.');
        }
        $affiliate = $withdrawal->affiliate;
        $affiliate->increment('total_paid', $withdrawal->amount);
        $withdrawal->update([
            'status'       => 'approved',
            'processed_at' => now(),
        ]);
        return back()->with('success',
            'Đã duyệt rút ' . number_format($withdrawal->amount, 0, ',', '.') . 'đ cho ' . $affiliate->name . '. Hãy chuyển khoản thủ công.');
    }

    public function reject(Request $request, Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Yêu cầu này đã được xử lý.');
        }
        $withdrawal->update([
            'status'       => 'rejected',
            'processed_at' => now(),
            'note'         => trim(($withdrawal->note ? $withdrawal->note . ' | ' : '') . 'Admin từ chối: ' . $request->input('reason', '')),
        ]);
        return back()->with('success', 'Đã từ chối yêu cầu rút tiền.');
    }
}
