<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Tổng quan 3D | DALI Admin</title>
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
.tb-btn{font-size:13px;color:#fff;background:linear-gradient(135deg,#3A9A12,var(--g));padding:9px 16px;border-radius:9px;text-decoration:none;font-weight:800;box-shadow:0 3px 12px rgba(107,191,31,.3)}
.sak{background:linear-gradient(90deg,#fff8fa,#f6ffe8,#fff);border-bottom:1px solid #F0EBF8;padding:6px 24px;display:flex;align-items:center;gap:5px;flex-shrink:0}
.p{font-size:14px;animation:drift 5s ease-in-out infinite;display:inline-block}
.p:nth-child(2){animation-delay:1s}.p:nth-child(3){animation-delay:2s}
@keyframes drift{0%,100%{transform:translateY(0)}50%{transform:translateY(-4px)}}
.sak-t{font-size:10px;color:#B8D8A0;letter-spacing:2px;font-weight:700;margin-left:8px}
.cnt{flex:1;overflow-y:auto;padding:22px 24px}
.grid4{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:16px}
@media(max-width:960px){.grid4{grid-template-columns:repeat(2,1fr)}}
.stat{background:#fff;border-radius:14px;border:0.5px solid var(--bd);padding:17px 20px;position:relative;overflow:hidden}
.stat::before{content:'';position:absolute;top:0;left:0;right:0;height:3px}
.stat.green::before{background:linear-gradient(90deg,#3A9A12,var(--g))}
.stat.blue::before{background:linear-gradient(90deg,#1D4ED8,#60A5FA)}
.stat.orange::before{background:linear-gradient(90deg,#D97706,#FCD34D)}
.stat.purple::before{background:linear-gradient(90deg,#7C3AED,#C084FC)}
.stat-icon{font-size:23px;margin-bottom:9px;display:block}
.stat-num{font-size:26px;font-weight:900;color:var(--char);margin-bottom:3px;line-height:1.05}
.stat-label{font-size:12px;color:var(--tx3);font-weight:600}
.stat-sub{font-size:11px;color:var(--g);font-weight:600;margin-top:4px}
.card{background:#fff;border-radius:14px;border:0.5px solid var(--bd);overflow:hidden;margin-bottom:16px}
.rainbow{height:4px;background:linear-gradient(90deg,#3A9A12,var(--g),var(--gn),#FF8FB1,#A78BFA)}
.card-h{padding:14px 20px;border-bottom:1px solid var(--gl);background:var(--gll);display:flex;align-items:center;justify-content:space-between}
.card-t{font-size:14px;font-weight:900;color:var(--char)}
.card-a{font-size:12px;color:var(--g);text-decoration:none;font-weight:700}
.grid2{display:grid;grid-template-columns:2fr 1fr;gap:16px}
@media(max-width:960px){.grid2{grid-template-columns:1fr}}
table{width:100%;border-collapse:collapse}
th{font-size:10px;font-weight:800;letter-spacing:1px;color:var(--tx3);text-transform:uppercase;padding:10px 14px;background:var(--gll);border-bottom:1px solid var(--bd);text-align:left}
td{padding:11px 14px;border-bottom:0.5px solid var(--gl);font-size:12px;color:var(--tx);vertical-align:middle}
tr:hover td{background:var(--gll)}
.b{display:inline-flex;align-items:center;gap:5px;padding:2px 9px;border-radius:20px;font-size:11px;font-weight:800}
.b.g{background:#F0FDF4;color:var(--gd);border:1px solid #86efac}
.b.a{background:#FEF3C7;color:#B45309}.b.r{background:#FEE2E2;color:#B91C1C}
.b.n{background:#EEF2F0;color:#6B7A66}.b.bl{background:#DBEAFE;color:#1D4ED8}
.top-item{display:flex;align-items:center;gap:11px;padding:10px 18px;border-bottom:0.5px solid var(--gl)}
.rankn{width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;flex-shrink:0;color:var(--char)}
.top-img{width:38px;height:38px;border-radius:8px;object-fit:cover;border:1px solid var(--bd);flex:none}
.top-ph{width:38px;height:38px;border-radius:8px;background:var(--gl);border:1px solid var(--bd);display:flex;align-items:center;justify-content:center;font-size:18px;flex:none}
.top-n{font-weight:700;font-size:12px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:150px}
.top-s{font-size:11px;color:var(--tx3)}
.empty{text-align:center;padding:30px;color:var(--tx3);font-size:13px}
.live-dot{width:8px;height:8px;border-radius:50%;background:var(--g);display:inline-block;animation:blink 1.5s ease-in-out infinite;margin-right:6px}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.2}}
</style>
</head>
<body>
@php
  if(!function_exists('vnd3d')){ function vnd3d($n){ return number_format((int)$n,0,',','.').'đ'; } }
@endphp
<div class="layout">
  @include('admin.partials.sidebar')
  <div class="main">
    <div class="tb">
      <div><div class="tb-bc">Admin › Xưởng in 3D › <b>Tổng quan</b></div><div class="tb-title">Tổng quan Xưởng in 3D</div></div>
      <div style="display:flex;align-items:center;gap:14px">
        <div style="font-size:13px;color:var(--tx2)"><span class="live-dot"></span>{{ now()->format('d/m/Y H:i') }}</div>
        <a href="{{ route('admin.don3d.index') }}" class="tb-btn">📦 Đơn 3D</a>
      </div>
    </div>
    <div class="sak"><span class="p">📊</span><span class="p">⚛️</span><span class="p">🖨️</span><span class="sak-t">DALI · XƯỞNG IN 3D · RIÊNG KHỎI SỐ LIỆU TRANH</span></div>
    <div class="cnt">

      <!-- HÀNG 1: đơn + doanh thu -->
      <div class="grid4">
        <div class="stat green"><span class="stat-icon">🧾</span><div class="stat-num">{{ $stats['don_today'] }}</div><div class="stat-label">Đơn 3D hôm nay</div><div class="stat-sub">{{ $stats['don_month'] }} đơn tháng này</div></div>
        <div class="stat blue"><span class="stat-icon">💰</span><div class="stat-num">{{ vnd3d($stats['dt_today']) }}</div><div class="stat-label">Doanh thu hôm nay</div><div class="stat-sub">{{ vnd3d($stats['dt_month']) }} tháng này</div></div>
        <div class="stat orange"><span class="stat-icon">⏳</span><div class="stat-num">{{ $stats['don_cho_coc'] }}</div><div class="stat-label">Chờ cọc · cần xử lý</div><div class="stat-sub">{{ $stats['don_dang_giao'] }} đơn đang giao</div></div>
        <div class="stat purple"><span class="stat-icon">⚛️</span><div class="stat-num">{{ $stats['sp_dang_ban'] }}</div><div class="stat-label">Sản phẩm đang bán</div><div class="stat-sub">tổng {{ $stats['sp_tong'] }} · đã hoàn tất {{ $stats['don_hoan_tat'] }} đơn</div></div>
      </div>

      <!-- HÀNG 2: hoạt động khách trên web 3D -->
      <div class="grid4">
        <div class="stat green"><span class="stat-icon">👀</span><div class="stat-num">{{ number_format($stats['view_today'],0,',','.') }}</div><div class="stat-label">Lượt xem SP hôm nay</div><div class="stat-sub">{{ number_format($stats['view_total'],0,',','.') }} tổng lượt xem</div></div>
        <div class="stat blue"><span class="stat-icon">🛒</span><div class="stat-num">{{ number_format($stats['cart_month'],0,',','.') }}</div><div class="stat-label">Thêm giỏ (tháng)</div><div class="stat-sub">{{ number_format($stats['cart_total'],0,',','.') }} tổng lượt</div></div>
        <div class="stat orange"><span class="stat-icon">💳</span><div class="stat-num">{{ number_format($stats['checkout_month'],0,',','.') }}</div><div class="stat-label">Bắt đầu thanh toán (tháng)</div><div class="stat-sub">{{ number_format($stats['checkout_total'],0,',','.') }} tổng lượt</div></div>
        <div class="stat purple"><span class="stat-icon">📍</span><div class="stat-num" style="font-size:{{ ($province->first()->tinh ?? null) ? '17px' : '26px' }};line-height:1.3">{{ $province->first()->tinh ?? '—' }}</div><div class="stat-label">Tỉnh nhiều đơn nhất</div><div class="stat-sub">{{ $province->count() }} tỉnh · {{ $province_total }} đơn có địa chỉ</div></div>
      </div>

      <div class="grid2">
        <!-- Doanh thu 7 ngày -->
        <div class="card"><div class="rainbow"></div>
          <div class="card-h"><div class="card-t">📈 Doanh thu 7 ngày qua</div></div>
          <div style="padding:16px 20px"><canvas id="rev7" height="180"></canvas></div>
        </div>
        <!-- Top bán chạy -->
        <div class="card"><div class="rainbow"></div>
          <div class="card-h"><div class="card-t">🔥 Bán chạy nhất</div><a href="{{ route('admin.sp3d.index') }}" class="card-a">Tất cả →</a></div>
          <div style="padding:4px 0">
            @forelse($top as $i => $p)
              <div class="top-item">
                <div class="rankn" style="background:{{ $i==0?'var(--gn)':($i==1?'var(--bd2)':'var(--gl)') }}">{{ $i+1 }}</div>
                @if($p->anh_bia)<img src="{{ asset('storage/'.$p->anh_bia) }}" class="top-img" alt="">@else<div class="top-ph">{{ $p->art ?: '📦' }}</div>@endif
                <div style="flex:1;min-width:0"><div class="top-n">{{ $p->ten }}</div><div class="top-s">{{ $p->gia_ht }}</div></div>
                <div style="text-align:right;flex:none"><div style="font-size:14px;font-weight:900;color:var(--g)">{{ $p->da_ban }}</div><div class="top-s">đã bán</div></div>
              </div>
            @empty
              <div class="empty">Chưa có sản phẩm nào bán được.<br>Lượt bán tự cộng khi đơn chuyển sang <b>Hoàn tất</b>.</div>
            @endforelse
          </div>
        </div>
      </div>

      <!-- Doanh thu 12 tháng -->
      <div class="card"><div class="rainbow"></div>
        <div class="card-h"><div class="card-t">📅 Doanh thu 3D theo tháng (12 tháng gần nhất)</div></div>
        <div style="padding:16px 20px"><canvas id="rev12" height="120"></canvas></div>
      </div>

      <div class="grid2">
        <!-- Hoạt động web 3D 7 ngày -->
        <div class="card"><div class="rainbow"></div>
          <div class="card-h"><div class="card-t">👀 Hoạt động khách trên web 3D (7 ngày)</div></div>
          <div style="padding:16px 20px"><canvas id="act7" height="180"></canvas></div>
        </div>
        <!-- Khách theo tỉnh -->
        <div class="card"><div class="rainbow"></div>
          <div class="card-h"><div class="card-t">📍 Khách theo tỉnh/thành</div></div>
          <div style="padding:14px 18px">
            @forelse($province as $i => $pv)
              @php $pct = $province_total>0 ? round($pv->don/$province_total*100) : 0; @endphp
              <div style="margin-bottom:12px">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
                  <span style="font-size:13px;font-weight:700;color:var(--char)">{{ $i+1 }}. {{ $pv->tinh }}</span>
                  <span style="font-size:12px;color:var(--tx2)"><b style="color:var(--g)">{{ $pv->don }}</b> đơn · {{ $pct }}%</span>
                </div>
                <div style="height:8px;background:var(--gl);border-radius:6px;overflow:hidden"><div style="height:100%;width:{{ $pct }}%;background:linear-gradient(90deg,#3A9A12,var(--g));border-radius:6px"></div></div>
              </div>
            @empty
              <div class="empty">Chưa có đơn 3D nào.<br><span style="font-size:12px">Sẽ hiện khi có khách đặt — thống kê theo tỉnh/thành lúc đặt.</span></div>
            @endforelse
          </div>
        </div>
      </div>

      <!-- Đơn 3D gần đây -->
      <div class="card"><div class="rainbow"></div>
        <div class="card-h"><div class="card-t">🧾 Đơn 3D gần đây</div><a href="{{ route('admin.don3d.index') }}" class="card-a">Xem tất cả →</a></div>
        <div style="overflow-x:auto">
        <table>
          <thead><tr><th>Mã đơn</th><th>Khách</th><th>Sản phẩm</th><th>Tổng</th><th>Trạng thái</th><th>Thời gian</th></tr></thead>
          <tbody>
          @forelse($recent as $d)
            @php $cls = match($d->tt){ 'hoan_tat'=>'g','huy'=>'r','cho_coc'=>'a','dang_giao'=>'bl', default=>'n' }; @endphp
            <tr onclick="location.href='{{ route('admin.don3d.show',$d) }}'" style="cursor:pointer">
              <td style="font-weight:800;color:var(--g)">{{ $d->ma }}</td>
              <td style="font-weight:600">{{ $d->ten ?: '—' }}<div style="font-size:11px;color:var(--tx3)">{{ $d->sdt }}</div></td>
              <td style="max-width:210px;color:var(--tx2)">{{ \Illuminate\Support\Str::limit($d->sp, 46) }}</td>
              <td style="font-weight:800;color:var(--g)">{{ vnd3d($d->tong) }}</td>
              <td><span class="b {{ $cls }}">{{ $tt[$d->tt] ?? $d->tt }}</span></td>
              <td style="color:var(--tx3);font-size:11px">{{ $d->created_at?->format('d/m H:i') }}</td>
            </tr>
          @empty
            <tr><td colspan="6" class="empty">Chưa có đơn 3D nào.</td></tr>
          @endforelse
          </tbody>
        </table>
        </div>
      </div>

    </div>
  </div>
</div>
<script>
function mkBarLine(id, labels, bars, barLabel, line, lineLabel, money){
  var el=document.getElementById(id); if(!el||!window.Chart) return;
  new Chart(el.getContext('2d'),{type:'bar',data:{labels:labels,datasets:[
    {label:barLabel,data:bars,backgroundColor:'rgba(107,191,31,.35)',borderColor:'#6BBF1F',borderWidth:2,borderRadius:6,yAxisID:'y'},
    {label:lineLabel,data:line,type:'line',borderColor:'#FF8FB1',borderWidth:2.5,pointRadius:4,pointBackgroundColor:'#FF8FB1',fill:false,yAxisID:'y1'}
  ]},options:{responsive:true,maintainAspectRatio:false,
    plugins:{legend:{labels:{font:{size:11}}},tooltip:{callbacks:{label:function(c){return c.dataset.label+': '+(money&&c.datasetIndex===0?c.raw.toLocaleString('vi-VN')+'đ':c.raw);}}}},
    scales:{y:{beginAtZero:true,ticks:{callback:v=>money?(v>=1000000?Math.round(v/1000000)+'tr':(v>=1000?Math.round(v/1000)+'k':v)):v,font:{size:10}}},
            y1:{position:'right',beginAtZero:true,ticks:{stepSize:1,font:{size:10}},grid:{display:false}}}}});
}
mkBarLine('rev7',  {!! json_encode(array_column($chart_7day,'date')) !!},   {!! json_encode(array_column($chart_7day,'dt')) !!},   'Doanh thu', {!! json_encode(array_column($chart_7day,'don')) !!},   'Đơn', true);
mkBarLine('rev12', {!! json_encode(array_column($chart_12month,'label')) !!},{!! json_encode(array_column($chart_12month,'dt')) !!}, 'Doanh thu', {!! json_encode(array_column($chart_12month,'don')) !!}, 'Đơn', true);

// Hoạt động web 3D: 2 cột (xem SP + thêm giỏ)
(function(){
  var el=document.getElementById('act7'); if(!el||!window.Chart) return;
  new Chart(el.getContext('2d'),{type:'bar',data:{labels:{!! json_encode(array_column($act_7day,'date')) !!},datasets:[
    {label:'Lượt xem SP',data:{!! json_encode(array_column($act_7day,'views')) !!},backgroundColor:'rgba(59,130,246,.35)',borderColor:'#3B82F6',borderWidth:2,borderRadius:5},
    {label:'Thêm giỏ',data:{!! json_encode(array_column($act_7day,'carts')) !!},backgroundColor:'rgba(107,191,31,.35)',borderColor:'#6BBF1F',borderWidth:2,borderRadius:5}
  ]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{labels:{font:{size:11}}}},scales:{y:{beginAtZero:true,ticks:{stepSize:1,font:{size:10}}}}}});
})();
</script>
</body>
</html>
