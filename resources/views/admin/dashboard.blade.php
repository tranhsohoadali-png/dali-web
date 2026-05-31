<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard | DALI Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--gn:#C6F135;--pk:#FF8FB1;--bd:#C8E89A;--bd2:#A8D870;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0;font-family:'Be Vietnam Pro','Segoe UI',sans-serif}
body{background:var(--bg);color:var(--tx)}
.layout{display:flex;min-height:100vh}
.main{flex:1;display:flex;flex-direction:column;overflow:hidden}
.tb{background:#fff;border-bottom:2px solid var(--gl);height:64px;padding:0 24px;display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
.tb-bc{font-size:10px;color:var(--tx3)}.tb-bc b{color:var(--g)}
.tb-title{font-size:18px;font-weight:900;background:linear-gradient(90deg,#2D7A08,var(--g));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-top:2px}
.sak{background:linear-gradient(90deg,#fff8fa,#f6ffe8,#fff);border-bottom:1px solid #F0EBF8;padding:6px 24px;display:flex;align-items:center;gap:5px;flex-shrink:0}
.p{font-size:14px;animation:drift 5s ease-in-out infinite;display:inline-block}
.p:nth-child(2){animation-delay:1s}.p:nth-child(3){animation-delay:2s}
@keyframes drift{0%,100%{transform:translateY(0)}50%{transform:translateY(-4px)}}
.sak-t{font-size:10px;color:#B8D8A0;letter-spacing:2px;font-weight:700;margin-left:8px}
.cnt{flex:1;overflow-y:auto;padding:22px 24px}
.grid4{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px}
.stat{background:#fff;border-radius:14px;border:0.5px solid var(--bd);padding:18px 20px;position:relative;overflow:hidden}
.stat::before{content:'';position:absolute;top:0;left:0;right:0;height:3px}
.stat.green::before{background:linear-gradient(90deg,#3A9A12,var(--g))}
.stat.blue::before{background:linear-gradient(90deg,#1D4ED8,#60A5FA)}
.stat.orange::before{background:linear-gradient(90deg,#D97706,#FCD34D)}
.stat.purple::before{background:linear-gradient(90deg,#7C3AED,#C084FC)}
.stat-icon{font-size:24px;margin-bottom:10px;display:block}
.stat-num{font-size:28px;font-weight:900;color:var(--char);margin-bottom:3px;line-height:1}
.stat-label{font-size:12px;color:var(--tx3);font-weight:500}
.stat-sub{font-size:11px;color:var(--g);font-weight:600;margin-top:4px}
.card{background:#fff;border-radius:14px;border:0.5px solid var(--bd);overflow:hidden;margin-bottom:16px}
.rainbow{height:4px;background:linear-gradient(90deg,#3A9A12,var(--g),var(--gn),#FF8FB1,#A78BFA)}
.card-h{padding:14px 20px;border-bottom:1px solid var(--gl);background:var(--gll);display:flex;align-items:center;justify-content:space-between}
.card-t{font-size:14px;font-weight:900;color:var(--char)}
.grid2{display:grid;grid-template-columns:2fr 1fr;gap:16px}
table{width:100%;border-collapse:collapse}
th{font-size:10px;font-weight:800;letter-spacing:1px;color:var(--tx3);text-transform:uppercase;padding:10px 14px;background:var(--gll);border-bottom:1px solid var(--bd);text-align:left}
td{padding:11px 14px;border-bottom:0.5px solid var(--gl);font-size:12px;color:var(--tx);vertical-align:middle}
tr:hover td{background:var(--gll)}
.order-code{font-weight:800;color:var(--g);font-size:12px}
.btn-view{padding:4px 10px;background:var(--gl);color:var(--gd);border:1px solid var(--bd2);border-radius:6px;font-size:11px;font-weight:700;text-decoration:none;transition:all .2s}
.btn-view:hover{background:var(--g);color:#fff;border-color:var(--g)}
.live-dot{width:8px;height:8px;border-radius:50%;background:var(--g);display:inline-block;animation:blink 1.5s ease-in-out infinite;margin-right:6px}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.2}}
</style>
</head>
<body>
<div class="layout">
  @include('admin.partials.sidebar')
  <div class="main">
    <div class="tb">
      <div><div class="tb-bc">Admin › <b>Dashboard</b></div><div class="tb-title">Tổng quan hôm nay</div></div>
      <div style="font-size:13px;color:var(--tx2)"><span class="live-dot"></span>{{ now()->format('d/m/Y H:i') }}</div>
    </div>
    <div class="sak"><span class="p">🌸</span><span class="p">✿</span><span class="p">🍃</span><span class="sak-t">DALI · TÔ ĐIỂM CUỘC SỐNG</span></div>
    <div class="cnt">

      <!-- STATS -->
      <div class="grid4">
        <div class="stat green">
          <span class="stat-icon">📦</span>
          <div class="stat-num">{{ $stats['orders_today'] }}</div>
          <div class="stat-label">Đơn hàng hôm nay</div>
          <div class="stat-sub">{{ $stats['orders_month'] }} đơn tháng này</div>
        </div>
        <div class="stat blue">
          <span class="stat-icon">💰</span>
          <div class="stat-num">{{ number_format($stats['revenue_today'],0,',','.') }}đ</div>
          <div class="stat-label">Doanh thu hôm nay</div>
          <div class="stat-sub">{{ number_format($stats['revenue_month'],0,',','.') }}đ tháng này</div>
        </div>
        <div class="stat orange">
          <span class="stat-icon">🆕</span>
          <div class="stat-num">{{ $stats['orders_new'] }}</div>
          <div class="stat-label">Đơn chờ xử lý</div>
          <div class="stat-sub">{{ $stats['orders_shipping'] }} đơn đang giao</div>
        </div>
        <div class="stat purple">
          <span class="stat-icon">🎨</span>
          <div class="stat-num">{{ $stats['total_products'] }}</div>
          <div class="stat-label">Sản phẩm active</div>
          <div class="stat-sub">{{ $stats['total_categories'] }} danh mục</div>
        </div>
      </div>

      <!-- TRUY CẬP WEBSITE -->
      <div class="grid4" style="margin-top:16px">
        <div class="stat green">
          <span class="stat-icon">👀</span>
          <div class="stat-num">{{ number_format($stats['visits_today'],0,',','.') }}</div>
          <div class="stat-label">Lượt xem hôm nay</div>
          <div class="stat-sub">{{ number_format($stats['visits_total'],0,',','.') }} tổng lượt xem</div>
        </div>
        <div class="stat blue">
          <span class="stat-icon">🧑‍🤝‍🧑</span>
          <div class="stat-num">{{ number_format($stats['visitors_today'],0,',','.') }}</div>
          <div class="stat-label">Khách truy cập hôm nay</div>
          <div class="stat-sub">{{ number_format($stats['visitors_month'],0,',','.') }} khách tháng này</div>
        </div>
        <div class="stat orange">
          <span class="stat-icon">📍</span>
          <div class="stat-num">{{ $province_stats->count() }}</div>
          <div class="stat-label">Tỉnh/thành có khách đặt</div>
          <div class="stat-sub">{{ $province_total }} đơn có địa chỉ</div>
        </div>
        <div class="stat purple">
          <span class="stat-icon">🏆</span>
          <div class="stat-num" style="font-size:18px;line-height:1.3">{{ $province_stats->first()->customer_city ?? '—' }}</div>
          <div class="stat-label">Tỉnh nhiều đơn nhất</div>
          <div class="stat-sub">{{ $province_stats->first()->orders ?? 0 }} đơn</div>
        </div>
      </div>

      <div class="grid2">
        <!-- Chart doanh thu 7 ngày -->
        <div class="card">
          <div class="rainbow"></div>
          <div class="card-h"><div class="card-t">📈 Doanh thu 7 ngày qua</div></div>
          <div style="padding:16px 20px">
            <canvas id="revenueChart" height="180"></canvas>
          </div>
        </div>

        <!-- Top sản phẩm -->
        <div class="card">
          <div class="rainbow"></div>
          <div class="card-h"><div class="card-t">🔥 Sản phẩm bán chạy</div></div>
          <div style="padding:4px 0">
            @forelse($top_products as $i => $p)
            <div style="display:flex;align-items:center;gap:12px;padding:11px 18px;border-bottom:0.5px solid var(--gl)">
              <div style="width:24px;height:24px;border-radius:50%;background:{{ $i==0 ? 'var(--gn)' : ($i==1 ? 'var(--bd2)' : 'var(--gl)') }};color:var(--char);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;flex-shrink:0">{{ $i+1 }}</div>
              <div style="flex:1;min-width:0"><div style="font-size:12px;font-weight:700;color:var(--char);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $p->product_name }}</div></div>
              <div style="text-align:right;flex-shrink:0"><div style="font-size:12px;font-weight:800;color:var(--g)">{{ $p->sold }} bán</div></div>
            </div>
            @empty
            <div style="text-align:center;padding:30px;color:var(--tx3);font-size:13px">Chưa có đơn hàng nào</div>
            @endforelse
          </div>
        </div>
      </div>

      <!-- DOANH THU 12 THÁNG -->
      <div class="card" style="margin-bottom:20px">
        <div class="rainbow"></div>
        <div class="card-h"><div class="card-t">📅 Doanh thu theo tháng (12 tháng gần nhất)</div></div>
        <div style="padding:16px 20px"><canvas id="monthlyChart" height="120"></canvas></div>
      </div>

      <!-- TRUY CẬP + TỈNH/THÀNH -->
      <div class="grid2">
        <!-- Biểu đồ lượt truy cập 7 ngày -->
        <div class="card">
          <div class="rainbow"></div>
          <div class="card-h"><div class="card-t">👀 Lượt truy cập 7 ngày qua</div></div>
          <div style="padding:16px 20px">
            <canvas id="visitChart" height="180"></canvas>
          </div>
        </div>

        <!-- Khách hàng theo tỉnh/thành -->
        <div class="card">
          <div class="rainbow"></div>
          <div class="card-h"><div class="card-t">📍 Khách hàng theo tỉnh/thành</div></div>
          <div style="padding:14px 18px">
            @forelse($province_stats as $i => $pv)
            @php $pct = $province_total > 0 ? round($pv->orders / $province_total * 100) : 0; @endphp
            <div style="margin-bottom:12px">
              <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
                <span style="font-size:13px;font-weight:700;color:var(--char)">{{ $i+1 }}. {{ $pv->customer_city }}</span>
                <span style="font-size:12px;color:var(--tx2)"><b style="color:var(--g)">{{ $pv->orders }}</b> đơn · {{ $pct }}%</span>
              </div>
              <div style="height:8px;background:var(--gl);border-radius:6px;overflow:hidden">
                <div style="height:100%;width:{{ $pct }}%;background:linear-gradient(90deg,#3A9A12,var(--g));border-radius:6px"></div>
              </div>
            </div>
            @empty
            <div style="text-align:center;padding:30px;color:var(--tx3);font-size:13px">
              Chưa có đơn hàng nào.<br><span style="font-size:12px">Biểu đồ sẽ hiện khi có khách đặt hàng — thống kê theo tỉnh/thành khách chọn lúc đặt.</span>
            </div>
            @endforelse
          </div>
        </div>
      </div>

      <!-- Đơn hàng gần đây -->
      <div class="card">
        <div class="rainbow"></div>
        <div class="card-h">
          <div class="card-t">🛒 Đơn hàng gần đây</div>
          <a href="{{ route('admin.orders.index') }}" style="font-size:12px;color:var(--g);text-decoration:none;font-weight:700">Xem tất cả →</a>
        </div>
        <table>
          <thead><tr><th>Mã đơn</th><th>Khách hàng</th><th>SĐT</th><th>Tổng tiền</th><th>Thanh toán</th><th>Trạng thái</th><th>Thời gian</th><th></th></tr></thead>
          <tbody>
          @forelse($recent_orders as $order)
          <tr>
            <td><span class="order-code">{{ $order->code }}</span></td>
            <td style="font-weight:600">{{ $order->customer_name }}</td>
            <td>{{ $order->customer_phone }}</td>
            <td style="font-weight:800;color:var(--g)">{{ number_format($order->total,0,',','.') }}đ</td>
            <td><span style="font-size:11px;background:{{ $order->payment_method=='BANK' ? '#DBEAFE' : '#F3F4F6' }};color:{{ $order->payment_method=='BANK' ? '#1D4ED8' : '#6B7280' }};padding:2px 8px;border-radius:20px;font-weight:700">{{ $order->payment_label }}</span></td>
            <td><span style="font-size:11px;background:#F0FDF4;color:{{ $order->status_color }};padding:2px 8px;border-radius:20px;font-weight:700;border:1px solid {{ $order->status_color }}30">{{ $order->status_label }}</span></td>
            <td style="color:var(--tx3);font-size:11px">{{ $order->created_at->format('d/m H:i') }}</td>
            <td><a href="{{ route('admin.orders.show', $order) }}" class="btn-view">Chi tiết</a></td>
          </tr>
          @empty
          <tr><td colspan="8" style="text-align:center;padding:30px;color:var(--tx3)">Chưa có đơn hàng nào</td></tr>
          @endforelse
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>
<script>
var ctx = document.getElementById('revenueChart').getContext('2d');
var labels = {!! json_encode(array_column($chart_data,'date')) !!};
var revenues = {!! json_encode(array_column($chart_data,'revenue')) !!};
var orders = {!! json_encode(array_column($chart_data,'orders')) !!};
new Chart(ctx, {
  type:'bar',
  data:{
    labels:labels,
    datasets:[
      {label:'Doanh thu (đ)',data:revenues,backgroundColor:'rgba(107,191,31,.3)',borderColor:'#6BBF1F',borderWidth:2,yAxisID:'y'},
      {label:'Đơn hàng',data:orders,type:'line',backgroundColor:'rgba(255,143,177,.2)',borderColor:'#FF8FB1',borderWidth:2,pointRadius:4,yAxisID:'y1'},
    ]
  },
  options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{labels:{font:{size:11}}}},scales:{y:{beginAtZero:true,ticks:{callback:v=>v>=1000?Math.round(v/1000)+'K':v,font:{size:10}}},y1:{position:'right',beginAtZero:true,ticks:{stepSize:1,font:{size:10}},grid:{display:false}}}}
});

// ── Biểu đồ doanh thu 12 tháng ──
var mctx = document.getElementById('monthlyChart').getContext('2d');
var mlabels   = {!! json_encode(array_column($monthly_chart,'label')) !!};
var mrevenues = {!! json_encode(array_column($monthly_chart,'revenue')) !!};
var morders   = {!! json_encode(array_column($monthly_chart,'orders')) !!};
new Chart(mctx, {
  type:'bar',
  data:{
    labels:mlabels,
    datasets:[
      {label:'Doanh thu (đ)',data:mrevenues,backgroundColor:'rgba(107,191,31,.35)',borderColor:'#6BBF1F',borderWidth:2,borderRadius:6,yAxisID:'y'},
      {label:'Đơn hàng',data:morders,type:'line',borderColor:'#FF8FB1',borderWidth:2.5,pointRadius:5,pointBackgroundColor:'#FF8FB1',fill:false,yAxisID:'y1'},
    ]
  },
  options:{
    responsive:true,maintainAspectRatio:false,
    plugins:{legend:{labels:{font:{size:11}}},tooltip:{callbacks:{label:function(c){return c.dataset.label+': '+(c.datasetIndex===0?c.raw.toLocaleString('vi-VN')+'đ':c.raw+' đơn');}}}},
    scales:{
      y:{beginAtZero:true,ticks:{callback:v=>v>=1000000?Math.round(v/1000000)+'tr':(v>=1000?Math.round(v/1000)+'k':v),font:{size:10}}},
      y1:{position:'right',beginAtZero:true,ticks:{stepSize:1,font:{size:10}},grid:{display:false}}
    }
  }
});

// ── Biểu đồ lượt truy cập 7 ngày ──
var vctxEl = document.getElementById('visitChart');
if (vctxEl) {
  var vlabels = {!! json_encode(array_column($visit_chart,'date')) !!};
  var vviews = {!! json_encode(array_column($visit_chart,'views')) !!};
  var vvisitors = {!! json_encode(array_column($visit_chart,'visitors')) !!};
  new Chart(vctxEl.getContext('2d'), {
    type:'bar',
    data:{
      labels:vlabels,
      datasets:[
        {label:'Lượt xem',data:vviews,backgroundColor:'rgba(116,199,255,.35)',borderColor:'#3B82F6',borderWidth:2},
        {label:'Khách (IP)',data:vvisitors,type:'line',backgroundColor:'rgba(107,191,31,.2)',borderColor:'#6BBF1F',borderWidth:2,pointRadius:4},
      ]
    },
    options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{labels:{font:{size:11}}}},scales:{y:{beginAtZero:true,ticks:{stepSize:1,font:{size:10}}}}}
  });
}
</script>
</body>
</html>