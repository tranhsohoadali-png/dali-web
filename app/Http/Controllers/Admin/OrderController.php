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
}
