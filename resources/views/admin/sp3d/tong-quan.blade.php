<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Tổng quan 3D | DALI Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--g:#6BBF1F;--gb:#8ED63A;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--gn:#C6F135;--pk:#FF8FB1;--bd:#C8E89A;--bd2:#A8D870;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:var(--tx)}
.topbar{background:#fff;border-bottom:2px solid var(--gl);height:64px;padding:0 24px;display:flex;align-items:center;justify-content:space-between}
.tb-bc{font-size:10px;color:var(--tx3)}.tb-bc b{color:var(--g)}
.tb-title{font-size:18px;font-weight:900;background:linear-gradient(90deg,#2D7A08,var(--g));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-top:2px}
.sakura{background:linear-gradient(90deg,#fff8fa,#f6ffe8,#fff);border-bottom:1px solid #F0EBF8;padding:6px 24px;display:flex;align-items:center;gap:5px}
.p{font-size:14px;animation:drift 5s ease-in-out infinite;display:inline-block}
.p:nth-child(2){animation-delay:1s}.p:nth-child(3){animation-delay:2s}
@keyframes drift{0%,100%{transform:translateY(0)}50%{transform:translateY(-4px)}}
.sak-t{font-size:10px;color:#B8D8A0;letter-spacing:2px;font-weight:700;margin-left:8px}
.cnt{flex:1;overflow-y:auto;padding:22px 24px}
.stat-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:14px;margin-bottom:16px}
.stat{background:#fff;border-radius:16px;border:1.5px solid var(--bd);box-shadow:0 3px 18px rgba(58,122,10,.07);padding:15px 18px}
.stat .ic{font-size:19px}
.stat .lb{font-size:10.5px;font-weight:800;color:var(--tx3);text-transform:uppercase;letter-spacing:.5px;margin-top:7px}
.stat .vl{font-size:23px;font-weight:900;color:var(--char);margin-top:1px;line-height:1.15}
.stat .sub{font-size:11px;color:var(--tx3);margin-top:2px}
.stat.hot{border-color:#FBBF24;background:linear-gradient(135deg,#FFFBEB,#fff)}
.stat.hot .vl{color:#D97706}
.stat.rev{border-color:var(--bd2);background:linear-gradient(135deg,var(--gll),#fff)}
.stat.rev .vl{color:var(--g)}
.grid2{display:grid;grid-template-columns:1.4fr 1fr;gap:16px;margin-bottom:16px}
@media(max-width:900px){.grid2{grid-template-columns:1fr}}
.card{background:#fff;border-radius:16px;border:1.5px solid var(--bd);overflow:hidden;box-shadow:0 3px 18px rgba(58,122,10,.07)}
.card-top{height:4px;background:linear-gradient(90deg,#3A9A12,var(--g),var(--gn),#FF8FB1,#A78BFA)}
.card-head{padding:13px 20px;border-bottom:1px solid var(--gl);background:linear-gradient(135deg,var(--gll),#fff);display:flex;align-items:center;justify-content:space-between}
.card-title{font-size:14px;font-weight:900;color:var(--char)}
.card-link{font-size:12px;color:var(--gd);text-decoration:none;font-weight:700}
.chart{display:flex;align-items:flex-end;gap:10px;height:190px;padding:18px 12px 6px}
.chart .col{flex:1;display:flex;flex-direction:column;align-items:center;gap:6px;height:100%;justify-content:flex-end}
.chart .bwrap{flex:1;display:flex;align-items:flex-end;width:100%;justify-content:center}
.chart .bar{width:66%;max-width:46px;background:linear-gradient(180deg,var(--gb),var(--g));border-radius:6px 6px 0 0;min-height:3px}
.chart .bval{font-size:10px;font-weight:800;color:var(--gd);white-space:nowrap}
.chart .blab{font-size:10.5px;color:var(--tx3);font-weight:600}
.chart .bsub{font-size:9.5px;color:var(--tx3)}
table{width:100%;border-collapse:collapse}
th{font-size:10px;font-weight:800;letter-spacing:1px;color:var(--tx3);text-transform:uppercase;padding:10px 16px;background:var(--gll);border-bottom:1.5px solid var(--bd);text-align:left}
td{padding:11px 16px;border-bottom:1px solid var(--gl);font-size:13px;color:var(--tx);vertical-align:middle}
tr:hover td{background:var(--gll)}
.b{display:inline-flex;align-items:center;gap:5px;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:800}
.b.g{background:var(--gl);color:var(--gd)}
.b.a{background:#FEF3C7;color:#B45309}
.b.r{background:#FEE2E2;color:#B91C1C}
.b.n{background:#EEF2F0;color:#6B7A66}
.b.bl{background:#DBEAFE;color:#1D4ED8}
.top-item{display:flex;align-items:center;gap:11px;padding:11px 18px;border-bottom:1px solid var(--gl)}
.top-item:last-child{border-bottom:none}
.top-img{width:44px;height:44px;border-radius:9px;object-fit:cover;border:1px solid var(--bd);flex:none}
.top-ph{width:44px;height:44px;border-radius:9px;background:var(--gl);border:1px solid var(--bd);display:flex;align-items:center;justify-content:center;font-size:20px;flex:none}
.top-n{font-weight:700;font-size:13px;max-width:190px}
.top-s{font-size:11px;color:var(--tx3);margin-top:1px}
.top-sold{margin-left:auto;text-align:right;flex:none}
.top-sold b{font-size:16px;font-weight:900;color:var(--g)}
.top-sold span{display:block;font-size:10px;color:var(--tx3)}
.empty{padding:34px;text-align:center;color:var(--tx3);font-size:13px}
</style>
</head>
<body>
<div style="display:flex;min-height:100vh">
@include('admin.partials.sidebar')
<div style="flex:1;display:flex;flex-direction:column;overflow:hidden">
  <div class="topbar">
    <div><div class="tb-bc">Admin › Xưởng in 3D › <b>Tổng quan</b></div><div class="tb-title">Tổng quan Xưởng in 3D</div></div>
    <a href="{{ route('admin.don3d.index') }}" class="btn-add" style="display:inline-flex;align-items:center;gap:6px;padding:9px 18px;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;font-size:13px;font-weight:800;border-radius:9px;text-decoration:none;box-shadow:0 3px 12px rgba(107,191,31,.3)">📦 Xem đơn 3D</a>
  </div>
  <div class="sakura"><span class="p">📊</span><span class="p">⚛️</span><span class="p">🖨️</span><span class="sak-t">DALI · XƯỞNG IN 3D · RIÊNG KHỎI SỐ LIỆU TRANH</span></div>
  <div class="cnt">

    @php
      if(!function_exists('vnd3d')){ function vnd3d($n){ return number_format((int)$n,0,',','.').'₫'; } }
      $maxDt = max(1, max(array_map(fn($c)=>$c['dt'], $chart)));
    @endphp

    {{-- KPI chính --}}
    <div class="stat-grid">
      <div class="stat"><div class="ic">🧾</div><div class="lb">Đơn hôm nay</div><div class="vl">{{ $stats['don_today'] }}</div><div class="sub">Tháng này: {{ $stats['don_month'] }} đơn</div></div>
      <div class="stat rev"><div class="ic">💰</div><div class="lb">Doanh thu hôm nay</div><div class="vl">{{ vnd3d($stats['dt_today']) }}</div><div class="sub">đơn đã vào in trở đi</div></div>
      <div class="stat rev"><div class="ic">📈</div><div class="lb">Doanh thu tháng này</div><div class="vl">{{ vnd3d($stats['dt_month']) }}</div><div class="sub">{{ now()->format('m/Y') }}</div></div>
      <div class="stat {{ $stats['don_cho_coc']>0 ? 'hot' : '' }}"><div class="ic">⏳</div><div class="lb">Chờ cọc · cần xử lý</div><div class="vl">{{ $stats['don_cho_coc'] }}</div><div class="sub">đơn mới chờ xác nhận</div></div>
    </div>

    {{-- KPI phụ --}}
    <div class="stat-grid">
      <div class="stat"><div class="ic">🚚</div><div class="lb">Đang giao</div><div class="vl">{{ $stats['don_dang_giao'] }}</div></div>
      <div class="stat"><div class="ic">✅</div><div class="lb">Đã hoàn tất</div><div class="vl">{{ $stats['don_hoan_tat'] }}</div></div>
      <div class="stat"><div class="ic">⚛️</div><div class="lb">Sản phẩm đang bán</div><div class="vl">{{ $stats['sp_dang_ban'] }}</div><div class="sub">tổng cộng {{ $stats['sp_tong'] }} sản phẩm</div></div>
    </div>

    <div class="grid2">
      {{-- Biểu đồ doanh thu 6 tháng --}}
      <div class="card">
        <div class="card-top"></div>
        <div class="card-head"><span class="card-title">📊 Doanh thu 6 tháng gần nhất</span></div>
        <div class="chart">
          @foreach($chart as $c)
            <div class="col" title="{{ $c['label'] }} · {{ $c['don'] }} đơn · {{ vnd3d($c['dt']) }}">
              <div class="bval">{{ $c['dt'] >= 1000000 ? round($c['dt']/1000000,1).'tr' : ($c['dt']>=1000 ? round($c['dt']/1000).'k' : $c['dt']) }}</div>
              <div class="bwrap"><div class="bar" style="height:{{ max(2, round($c['dt']/$maxDt*100)) }}%"></div></div>
              <div class="blab">{{ $c['label'] }}</div>
              <div class="bsub">{{ $c['don'] }} đơn</div>
            </div>
          @endforeach
        </div>
      </div>

      {{-- Top sản phẩm bán chạy --}}
      <div class="card">
        <div class="card-top"></div>
        <div class="card-head"><span class="card-title">🏆 Bán chạy nhất</span><a href="{{ route('admin.sp3d.index') }}" class="card-link">Tất cả →</a></div>
        @forelse($top as $p)
          <div class="top-item">
            @if($p->anh_bia)<img src="{{ asset('storage/'.$p->anh_bia) }}" class="top-img" alt="">@else<div class="top-ph">{{ $p->art ?: '📦' }}</div>@endif
            <div><div class="top-n">{{ $p->ten }}</div><div class="top-s">{{ $p->gia_ht }}</div></div>
            <div class="top-sold"><b>{{ $p->da_ban }}</b><span>đã bán</span></div>
          </div>
        @empty
          <div class="empty">Chưa có sản phẩm nào bán được.<br>Lượt bán tự cộng khi đơn chuyển sang <b>Hoàn tất</b>.</div>
        @endforelse
      </div>
    </div>

    {{-- Đơn 3D gần đây --}}
    <div class="card">
      <div class="card-top"></div>
      <div class="card-head"><span class="card-title">🧾 Đơn 3D gần đây</span><a href="{{ route('admin.don3d.index') }}" class="card-link">Tất cả đơn →</a></div>
      <div style="overflow-x:auto">
      <table>
        <thead><tr><th>Mã đơn</th><th>Khách</th><th>Sản phẩm</th><th>Tổng</th><th>Trạng thái</th><th>Ngày</th></tr></thead>
        <tbody>
        @forelse($recent as $d)
          @php
            $cls = match($d->tt){ 'hoan_tat'=>'g','huy'=>'r','cho_coc'=>'a','dang_giao'=>'bl', default=>'n' };
          @endphp
          <tr onclick="location.href='{{ route('admin.don3d.show',$d) }}'" style="cursor:pointer">
            <td style="font-weight:800;color:var(--gd)">{{ $d->ma }}</td>
            <td>{{ $d->ten ?: '—' }}<div style="font-size:11px;color:var(--tx3)">{{ $d->sdt }}</div></td>
            <td style="max-width:220px;font-size:12px;color:var(--tx2)">{{ \Illuminate\Support\Str::limit($d->sp, 46) }}</td>
            <td style="font-weight:800">{{ vnd3d($d->tong) }}</td>
            <td><span class="b {{ $cls }}">{{ $tt[$d->tt] ?? $d->tt }}</span></td>
            <td style="font-size:12px;color:var(--tx3)">{{ $d->created_at?->format('d/m H:i') }}</td>
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
</body>
</html>
