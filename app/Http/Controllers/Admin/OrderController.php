<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items')->latest();
        if ($request->filled('status'))  $query->where('status', $request->status);
        if ($request->filled('search'))  $query->where(function($q) use ($request) {
            $q->where('code','like','%'.$request->search.'%')
              ->orWhere('customer_phone','like','%'.$request->search.'%')
              ->orWhere('customer_name','like','%'.$request->search.'%');
        });
        if ($request->filled('date'))    $query->whereDate('created_at', $request->date);
        $orders = $query->paginate(20)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:new,confirmed,packing,shipping,delivered,cancelled']);
        $order->update(['status' => $request->status]);
        if ($request->status === 'confirmed' && $order->payment_method === 'BANK') {
            $order->update(['payment_status' => 'paid']);
        }
        return back()->with('success', 'Đã cập nhật trạng thái đơn hàng!');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return back()->with('success', 'Đã xoá đơn hàng!');
    }

    // ── XUẤT CSV ──────────────────────────────────
    public function export(Request $request)
    {
        $query = Order::with('items')->latest();
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('date_from')) $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->filled('date_to'))   $query->whereDate('created_at', '<=', $request->date_to);
        $orders = $query->get();

        $filename = 'don-hang-dali-' . now()->format('Y-m-d') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($orders) {
            $fh = fopen('php://output', 'w');
            // BOM UTF-8 để Excel đọc tiếng Việt đúng
            fputs($fh, "\xEF\xBB\xBF");
            fputcsv($fh, ['Mã đơn','Ngày đặt','Khách hàng','SĐT','Tỉnh/TP','Sản phẩm','Kích thước','Số lượng','Đơn giá','Tiền hàng','Phí ship','Tổng','Thanh toán','Trạng thái','Mã CTV','Hoa hồng CTV','Ghi chú']);
            foreach ($orders as $o) {
                foreach ($o->items as $item) {
                    fputcsv($fh, [
                        $o->code,
                        $o->created_at->format('d/m/Y H:i'),
                        $o->customer_name,
                        $o->customer_phone,
                        $o->customer_city,
                        $item->product_name,
                        $item->product_size,
                        $item->quantity,
                        $item->price,
                        $item->subtotal,
                        $o->ship_fee,
                        $o->total,
                        $o->payment_label,
                        $o->status_label,
                        $o->affiliate_code ?? '',
                        $o->affiliate_commission ?? 0,
                        $o->note,
                    ]);
                }
                if ($o->items->isEmpty()) {
                    fputcsv($fh, [
                        $o->code, $o->created_at->format('d/m/Y H:i'),
                        $o->customer_name, $o->customer_phone, $o->customer_city,
                        '', '', '', '', '', $o->ship_fee, $o->total,
                        $o->payment_label, $o->status_label,
                        $o->affiliate_code ?? '', $o->affiliate_commission ?? 0, $o->note,
                    ]);
                }
            }
            fclose($fh);
        };

        return response()->stream($callback, 200, $headers);
    }
}
