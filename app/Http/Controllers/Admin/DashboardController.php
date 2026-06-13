<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today      = now()->toDateString();
        $thisMonth  = now()->format('Y-m');

        $stats = [
            'orders_today'    => Order::whereDate('created_at', $today)->count(),
            'orders_month'    => Order::where('created_at','like',$thisMonth.'%')->count(),
            'revenue_today'   => Order::whereDate('created_at',$today)->whereIn('status',['confirmed','packing','shipping','delivered'])->sum('total'),
            'revenue_month'   => Order::where('created_at','like',$thisMonth.'%')->whereIn('status',['confirmed','packing','shipping','delivered'])->sum('total'),
            'orders_new'      => Order::where('status','new')->count(),
            'orders_shipping' => Order::where('status','shipping')->count(),
            'total_products'  => Product::where('is_active',true)->count(),
            'total_categories'=> Category::where('is_active',true)->count(),
            // ── Lượt truy cập website ──
            'visits_total'    => DB::table('visits')->count(),
            'visits_today'    => DB::table('visits')->where('date',$today)->count(),
            'visitors_today'  => DB::table('visits')->where('date',$today)->distinct('ip')->count('ip'),
            'visitors_month'  => DB::table('visits')->where('date','like',$thisMonth.'%')->distinct('ip')->count('ip'),
        ];

        // ── Lượt truy cập 7 ngày qua ──
        $visit_chart = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = now()->subDays($i)->toDateString();
            $visit_chart[] = [
                'date'     => now()->subDays($i)->format('d/m'),
                'views'    => DB::table('visits')->where('date',$d)->count(),
                'visitors' => DB::table('visits')->where('date',$d)->distinct('ip')->count('ip'),
            ];
        }

        // ── Khách hàng theo tỉnh/thành (từ đơn hàng) ──
        $province_stats = Order::select('customer_city', DB::raw('COUNT(*) as orders'), DB::raw('SUM(total) as revenue'))
            ->whereNotNull('customer_city')->where('customer_city','!=','')
            ->groupBy('customer_city')
            ->orderByDesc('orders')
            ->take(10)
            ->get();
        $province_total = (int) Order::whereNotNull('customer_city')->where('customer_city','!=','')->count();

        // ── Doanh thu 12 tháng qua ──
        $monthly_chart = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = now()->subMonths($i);
            $ym = $m->format('Y-m');
            $monthly_chart[] = [
                'label'   => $m->format('m/Y'),
                'orders'  => Order::where('created_at','like',$ym.'%')->count(),
                'revenue' => (int) Order::where('created_at','like',$ym.'%')
                    ->whereIn('status',['confirmed','packing','shipping','delivered'])->sum('total'),
            ];
        }

        $recent_orders = Order::latest()->take(8)->get();

        $top_products = Order::join('order_items','orders.id','=','order_items.order_id')
            ->select('order_items.product_name',\DB::raw('SUM(order_items.quantity) as sold'),\DB::raw('SUM(order_items.subtotal) as revenue'))
            ->groupBy('order_items.product_name')
            ->orderByDesc('sold')
            ->take(5)
            ->get();

        $chart_data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $chart_data[] = [
                'date'    => now()->subDays($i)->format('d/m'),
                'orders'  => Order::whereDate('created_at',$date)->count(),
                'revenue' => Order::whereDate('created_at',$date)->whereIn('status',['confirmed','packing','shipping','delivered'])->sum('total'),
            ];
        }

        // Cảnh báo AI: % bản thiết kế gần đây KHÔNG có ảnh AI (dấu hiệu hết credit Google).
        $aiWarn = null;
        if (\Illuminate\Support\Facades\Schema::hasTable('design_leads')) {
            $recentLeads = \App\Models\DesignLead::where('created_at', '>=', now()->subHours(48));
            $totalLeads  = (clone $recentLeads)->count();
            $noAi        = (clone $recentLeads)->where(fn($q) => $q->whereNull('enhanced_url')->orWhere('enhanced_url', ''))->count();
            if ($totalLeads >= 2 && $noAi >= ceil($totalLeads * 0.5)) {
                $aiWarn = ['no_ai' => $noAi, 'total' => $totalLeads];
            }
        }

        return view('admin.dashboard', compact('stats','recent_orders','top_products','chart_data','visit_chart','province_stats','province_total','monthly_chart','aiWarn'));
    }
}
