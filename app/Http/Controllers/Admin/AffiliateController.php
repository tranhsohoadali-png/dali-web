<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\AgentPrice;
use App\Models\Order;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AffiliateController extends Controller
{
    public function index()
    {
        $affiliates = Affiliate::orderBy('total_earned', 'desc')->get();
        $totalPending = $affiliates->sum('balance');
        return view('admin.affiliates.index', compact('affiliates', 'totalPending'));
    }

    public function create()
    {
        return view('admin.affiliates.form', [
            'affiliate' => null,
            'sizes'     => Size::orderBy('sort_order')->get(),
            'agentMap'  => [],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:100',
            'phone'           => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:100',
            'code'            => 'nullable|string|max:30|unique:affiliates,code',
            'password'        => 'nullable|string|min:4|max:50',
            'type'            => 'nullable|in:ctv,agent',
            'commission_rate' => 'nullable|numeric|min:0|max:50',
            'deposit_percent' => 'nullable|integer|min:0|max:100',
            'bank_name'       => 'nullable|string|max:50',
            'bank_acc'        => 'nullable|string|max:30',
            'bank_owner'      => 'nullable|string|max:100',
            'is_active'       => 'nullable|boolean',
            'note'            => 'nullable|string|max:500',
        ]);
        $data['type'] = $data['type'] ?? 'ctv';
        // deposit_percent chỉ áp dụng cho đại lý; CTV luôn null
        if ($data['type'] !== 'agent') {
            $data['deposit_percent'] = null;
        }
        // Mật khẩu đăng nhập CTV
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        // Auto-generate code if empty
        if (empty($data['code'])) {
            $data['code'] = 'DALI_' . strtoupper(Str::slug($data['name'], '_'));
            // Ensure unique
            $base = $data['code'];
            $i = 1;
            while (Affiliate::where('code', $data['code'])->exists()) {
                $data['code'] = $base . '_' . $i++;
            }
        } else {
            $data['code'] = strtoupper($data['code']);
        }
        $data['is_active']       = $request->boolean('is_active', true);
        $data['commission_rate'] = $data['commission_rate'] ?? 5;
        $affiliate = Affiliate::create($data);
        $this->saveAgentPrices($affiliate, $request);
        return redirect()->route('admin.affiliates.index')->with('success', $affiliate->isAgent() ? 'Đã thêm Đại lý!' : 'Đã thêm CTV!');
    }

    public function show(Affiliate $affiliate)
    {
        $orders = Order::where('affiliate_code', $affiliate->code)
            ->latest()->paginate(20);
        return view('admin.affiliates.show', compact('affiliate', 'orders'));
    }

    public function edit(Affiliate $affiliate)
    {
        $sizes    = Size::orderBy('sort_order')->get();
        $agentMap = $affiliate->agentPrices()->pluck('price', 'size_id')->toArray();
        return view('admin.affiliates.form', compact('affiliate', 'sizes', 'agentMap'));
    }

    public function update(Request $request, Affiliate $affiliate)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:100',
            'phone'           => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:100',
            'password'        => 'nullable|string|min:4|max:50',
            'type'            => 'nullable|in:ctv,agent',
            'commission_rate' => 'nullable|numeric|min:0|max:50',
            'deposit_percent' => 'nullable|integer|min:0|max:100',
            'bank_name'       => 'nullable|string|max:50',
            'bank_acc'        => 'nullable|string|max:30',
            'bank_owner'      => 'nullable|string|max:100',
            'is_active'       => 'nullable|boolean',
            'note'            => 'nullable|string|max:500',
        ]);
        $data['type'] = $data['type'] ?? 'ctv';
        // deposit_percent chỉ áp dụng cho đại lý; CTV luôn null
        if ($data['type'] !== 'agent') {
            $data['deposit_percent'] = null;
        }
        // Chỉ đổi mật khẩu khi admin nhập mới
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $data['is_active']       = $request->boolean('is_active', true);
        $data['commission_rate'] = $data['commission_rate'] ?? 5;
        $affiliate->update($data);
        $this->saveAgentPrices($affiliate, $request);
        return redirect()->route('admin.affiliates.index')->with('success', $affiliate->isAgent() ? 'Đã cập nhật Đại lý!' : 'Đã cập nhật CTV!');
    }

    /** Lưu bảng giá sỉ riêng của đại lý (theo kích thước). */
    private function saveAgentPrices(Affiliate $affiliate, Request $request): void
    {
        if (!$affiliate->isAgent()) return;
        foreach ((array) $request->input('agent_prices', []) as $sizeId => $price) {
            $val = (int) preg_replace('/[^\d]/', '', (string) $price);
            if ($val <= 0) continue;
            AgentPrice::updateOrCreate(
                ['affiliate_id' => $affiliate->id, 'size_id' => (int) $sizeId],
                ['price' => $val]
            );
        }
    }

    public function markPaid(Affiliate $affiliate)
    {
        $balance = $affiliate->balance;
        if ($balance > 0) {
            $affiliate->increment('total_paid', $balance);
            return back()->with('success', 'Đã đánh dấu thanh toán ' . number_format($balance, 0, ',', '.') . 'đ cho ' . $affiliate->name);
        }
        return back()->with('error', 'Không có số dư để thanh toán!');
    }

    public function destroy(Affiliate $affiliate)
    {
        $affiliate->delete();
        return back()->with('success', 'Đã xoá CTV!');
    }
}
