<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\ViettelPostService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /** API nhẹ cho app quản lý: số đơn "mới" + thông tin đơn mới nhất (để báo). */
    public function newCount()
    {
        $count  = Order::where('status', 'new')->count();
        $latest = Order::where('status', 'new')->latest()->first();
        return response()->json([
            'count'  => $count,
            'latest' => $latest ? [
                'code'     => $latest->code,
                'customer' => $latest->customer_name,
                'total'    => (int) $latest->total,
                'at'       => optional($latest->created_at)->toIso8601String(),
            ] : null,
        ]);
    }

    /** Trích mã tranh ở đuôi tên sản phẩm (vd "Tranh ... D003" -> "D003"). */
    private function itemCode(string $name): string
    {
        return preg_match('/([A-Za-z]{1,5}\d{1,4})\s*$/u', trim($name), $m) ? strtoupper($m[1]) : '';
    }

    /** Xuất CSV (mở bằng Excel) các mã tranh khách đặt + thông tin khách. */
    public function exportCsv(Order $order)
    {
        $order->load('items');
        $filename = 'don-' . $order->code . '.csv';

        return response()->streamDownload(function () use ($order) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM UTF-8 để Excel đọc tiếng Việt
            // Truyền đủ tham số (kể cả escape) để tránh cảnh báo deprecated PHP 8.4+
            $put = fn(array $row) => fputcsv($out, $row, ',', '"', '');
            $put(['ĐƠN HÀNG', $order->code]);
            $put(['Khách hàng', $order->customer_name]);
            $put(['Số điện thoại', $order->customer_phone]);
            $put(['Địa chỉ', trim(($order->customer_address ?? '') . ', ' . ($order->customer_city ?? ''), ', ')]);
            $put(['Ngày đặt', optional($order->created_at)->format('d/m/Y H:i')]);
            $put([]);
            $put(['STT', 'Mã tranh', 'Tên tranh', 'Kích thước', 'Số lượng', 'Đơn giá', 'Thành tiền']);
            $i = 1;
            foreach ($order->items as $it) {
                $put([$i++, $this->itemCode($it->product_name), $it->product_name, $it->product_size, $it->quantity, (int) $it->price, (int) $it->subtotal]);
            }
            $put([]);
            $put(['', '', '', 'Tổng số lượng', (int) $order->items->sum('quantity'), '', '']);
            $put(['', '', '', 'Tiền hàng', '', '', (int) $order->subtotal]);
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /** Trang in gọn (A4) các mã tranh khách đặt + thông tin khách -> lưu PDF qua Ctrl+P. */
    public function printOrder(Order $order)
    {
        $order->load('items');
        $items = $order->items->map(function ($it) {
            return [
                'code' => $this->itemCode($it->product_name),
                'name' => $it->product_name,
                'size' => $it->product_size,
                'qty'  => (int) $it->quantity,
                'price' => (int) $it->price,
                'subtotal' => (int) $it->subtotal,
            ];
        });
        return view('admin.orders.print', compact('order', 'items'));
    }

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

    /** Xác nhận đã nhận tiền cọc của đại lý. */
    public function markDepositPaid(Order $order)
    {
        $order->update(['deposit_paid' => true]);
        return back()->with('success', 'Đã xác nhận nhận cọc ' . number_format($order->deposit, 0, ',', '.') . 'đ cho đơn ' . $order->code);
    }

    /** Hàng chờ "Đơn thiết kế chưa xem trước" — khách đã đặt cọc, shop tự thiết kế & gửi Zalo. */
    public function designQueue(Request $request)
    {
        $query = Order::where('design_status', 'pending')->latest();
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('code', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%');
            });
        }
        $orders    = $query->paginate(20)->withQueryString();
        $doneCount = Order::where('design_status', 'delivered')->count();
        return view('admin.thietke-queue', compact('orders', 'doneCount'));
    }

    /** Đánh dấu ĐÃ GỬI bản thiết kế cho khách (qua Zalo). */
    public function markDesignDelivered(Order $order)
    {
        $order->update(['design_status' => 'delivered']);
        return back()->with('success', 'Đã đánh dấu GỬI KHÁCH bản thiết kế cho đơn ' . $order->code);
    }

    // ── VIETTEL POST ──────────────────────────────
    /** Tạo vận đơn Viettel Post cho đơn hàng. */
    public function vtpCreate(Request $request, Order $order, ViettelPostService $vtp)
    {
        if ($order->vtp_order_number) {
            return back()->with('error', 'Đơn này đã có vận đơn VTP: ' . $order->vtp_order_number);
        }
        try {
            $data = $vtp->createOrder($order, $request->input('service') ?: null);
            $order->update([
                'vtp_order_number' => $data['ORDER_NUMBER'] ?? null,
                'vtp_service'      => $request->input('service') ?: ($order->vtp_service),
                'vtp_status'       => 100,
                'vtp_status_name'  => 'Đã tạo vận đơn',
                'vtp_status_at'    => now(),
                'ship_fee'         => (int) ($data['MONEY_TOTAL'] ?? $order->ship_fee),
                'status'           => $order->status === 'new' ? 'confirmed' : $order->status,
            ]);
            return back()->with('success', 'Đã tạo vận đơn VTP: ' . ($data['ORDER_NUMBER'] ?? ''));
        } catch (\Throwable $e) {
            return back()->with('error', 'Tạo vận đơn thất bại: ' . $e->getMessage());
        }
    }

    /** Hủy vận đơn Viettel Post. */
    public function vtpCancel(Order $order, ViettelPostService $vtp)
    {
        if (!$order->vtp_order_number) return back()->with('error', 'Đơn chưa có vận đơn VTP.');
        try {
            $vtp->cancelOrder($order->vtp_order_number, 'Shop hủy đơn');
            $order->update(['vtp_status' => 107, 'vtp_status_name' => 'Đã hủy vận đơn', 'vtp_status_at' => now(), 'status' => 'cancelled']);
            return back()->with('success', 'Đã hủy vận đơn VTP.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Hủy vận đơn thất bại: ' . $e->getMessage());
        }
    }

    /** Mở link in nhãn vận đơn. */
    public function vtpPrint(Order $order, ViettelPostService $vtp)
    {
        if (!$order->vtp_order_number) return back()->with('error', 'Đơn chưa có vận đơn VTP.');
        try {
            return redirect()->away($vtp->printLink($order->vtp_order_number));
        } catch (\Throwable $e) {
            return back()->with('error', 'Không lấy được link in: ' . $e->getMessage());
        }
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
