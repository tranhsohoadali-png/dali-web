<!DOCTYPE html>
<html lang="vi">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Chi tiết CTV {{ $affiliate->name }} | DALI Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--gn:#C6F135;--pk:#FF8FB1;--bd:#C8E89A;--bd2:#A8D870;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0}body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:var(--tx)}
.layout{display:flex;min-height:100vh}.main{flex:1;display:flex;flex-direction:column;overflow:hidden}
.tb{background:#fff;border-bottom:2px solid var(--gl);height:64px;padding:0 24px;display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
.tb-bc{font-size:10px;color:var(--tx3)}.tb-bc a{color:var(--g);text-decoration:none;font-weight:600}
.tb-title{font-size:18px;font-weight:900;background:linear-gradient(90deg,#2D7A08,var(--g));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-top:2px}
.sak{background:linear-gradient(90deg,#fff8fa,#f6ffe8,#fff);border-bottom:1px solid #F0EBF8;padding:6px 24px;display:flex;align-items:center;gap:5px;flex-shrink:0}
.sak-t{font-size:10px;color:#B8D8A0;letter-spacing:2px;font-weight:700;margin-left:8px}
@keyframes drift{0%,100%{transform:translateY(0)}50%{transform:translateY(-4px)}}
.cnt{flex:1;overflow-y:auto;padding:22px 24px}
.alert-ok{background:var(--gll);border-left:3px solid var(--g);border-radius:9px;padding:12px 16px;margin-bottom:16px;font-size:13px;font-weight:600;color:var(--gd)}
.btn-cancel{padding:10px 18px;background:#fff;border:1.5px solid var(--bd);color:var(--tx3);font-size:12px;font-weight:600;border-radius:9px;text-decoration:none}
.stat-boxes{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px}
.stat-box{background:#fff;border-radius:14px;border:1.5px solid var(--bd);padding:16px;text-align:center}
.stat-num{font-size:20px;font-weight:900;color:var(--g);margin-bottom:3px}
.stat-label{font-size:11px;color:var(--tx3)}
.card{background:#fff;border-radius:16px;border:0.5px solid var(--bd);overflow:hidden;margin-bottom:20px}
.rainbow{height:4px;background:linear-gradient(90deg,#3A9A12,var(--g),var(--gn),#FF8FB1,#A78BFA)}
.card-head{padding:14px 22px;border-bottom:1px solid var(--gl);background:var(--gll)}
.card-title{font-size:14px;font-weight:900;color:var(--char)}
table{width:100%;border-collapse:collapse}
th{font-size:10px;font-weight:800;letter-spacing:1px;color:var(--tx3);text-transform:uppercase;padding:10px 14px;background:var(--gll);border-bottom:1.5px solid var(--bd);text-align:left}
td{padding:11px 14px;border-bottom:1px solid var(--gl);font-size:12px;color:var(--tx);vertical-align:middle}
tr:hover td{background:var(--gll)}
.aff-info{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:20px}
.info-item{background:#fff;border-radius:12px;border:1.5px solid var(--bd);padding:14px}
.info-label{font-size:10px;font-weight:800;letter-spacing:1.5px;color:var(--tx3);text-transform:uppercase;margin-bottom:5px}
.info-value{font-size:14px;font-weight:700;color:var(--char)}
</style>
</head>
<body>
<div class="layout">
  @include('admin.partials.sidebar')
  <div class="main">
    <div class="tb">
      <div>
        <div class="tb-bc"><a href="{{ route('admin.affiliates.index') }}">CTV</a> › <b>{{ $affiliate->name }}</b></div>
        <div class="tb-title">Chi tiết CTV: {{ $affiliate->name }}</div>
      </div>
      <div style="display:flex;gap:8px">
        <a href="{{ route('admin.affiliates.edit', $affiliate) }}" style="padding:9px 18px;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;font-size:12px;font-weight:800;border-radius:9px;text-decoration:none">✏️ Sửa</a>
        <a href="{{ route('admin.affiliates.index') }}" class="btn-cancel">← Quay lại</a>
      </div>
    </div>
    <div class="sak"><span style="animation:drift 5s ease-in-out infinite;display:inline-block">🌸</span><span class="sak-t">DALI · TÔ ĐIỂM CUỘC SỐNG</span></div>
    <div class="cnt">
      @if(session('success'))<div class="alert-ok">✅ {{ session('success') }}</div>@endif

      <div class="stat-boxes">
        <div class="stat-box"><div class="stat-num">{{ $affiliate->total_orders }}</div><div class="stat-label">Tổng đơn hàng</div></div>
        <div class="stat-box"><div class="stat-num" style="font-size:14px">{{ number_format($affiliate->total_earned,0,',','.') }}đ</div><div class="stat-label">Tổng hoa hồng</div></div>
        <div class="stat-box"><div class="stat-num" style="font-size:14px">{{ number_format($affiliate->total_paid,0,',','.') }}đ</div><div class="stat-label">Đã thanh toán</div></div>
        <div class="stat-box">
          <div class="stat-num" style="font-size:14px;color:{{ $affiliate->balance > 0 ? 'var(--pk)' : 'var(--tx3)' }}">{{ number_format($affiliate->balance,0,',','.') }}đ</div>
          <div class="stat-label">Chờ thanh toán</div>
          @if($affiliate->balance > 0)
          <form method="POST" action="{{ route('admin.affiliates.paid', $affiliate) }}" style="margin-top:8px">
            @csrf
            <button type="submit" style="padding:5px 12px;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:7px;font-size:11px;font-weight:700;cursor:pointer">💸 Đánh dấu đã TT</button>
          </form>
          @endif
        </div>
      </div>

      <div class="aff-info">
        <div class="info-item"><div class="info-label">Mã ref</div><div class="info-value" style="font-family:monospace;color:var(--g)">{{ $affiliate->code }}</div></div>
        <div class="info-item"><div class="info-label">Hoa hồng</div><div class="info-value">{{ $affiliate->commission_rate }}%</div></div>
        <div class="info-item"><div class="info-label">Link giới thiệu</div><a href="{{ route('affiliate.track', $affiliate->code) }}" target="_blank" style="font-size:11px;color:var(--g);word-break:break-all">{{ url('/ref/'.$affiliate->code) }}</a></div>
        @if($affiliate->phone)<div class="info-item"><div class="info-label">Điện thoại</div><div class="info-value">{{ $affiliate->phone }}</div></div>@endif
        @if($affiliate->bank_name)<div class="info-item"><div class="info-label">Ngân hàng</div><div class="info-value">{{ $affiliate->bank_name }} · {{ $affiliate->bank_acc }}</div></div>@endif
        @if($affiliate->bank_owner)<div class="info-item"><div class="info-label">Chủ TK</div><div class="info-value">{{ $affiliate->bank_owner }}</div></div>@endif
      </div>

      <div class="card">
        <div class="rainbow"></div>
        <div class="card-head"><div class="card-title">Lịch sử đơn hàng ({{ $orders->total() }})</div></div>
        <table>
          <thead><tr><th>Mã đơn</th><th>Khách hàng</th><th>Tổng đơn</th><th>Hoa hồng</th><th>Trạng thái</th><th>Ngày đặt</th></tr></thead>
          <tbody>
          @forelse($orders as $o)
          <tr>
            <td style="font-family:monospace;font-weight:700;color:var(--g)">{{ $o->code }}</td>
            <td><div style="font-weight:600">{{ $o->customer_name }}</div><div style="font-size:10px;color:var(--tx3)">{{ $o->customer_phone }}</div></td>
            <td style="font-weight:800">{{ number_format($o->total,0,',','.') }}đ</td>
            <td style="font-weight:800;color:var(--pk)">{{ number_format($o->affiliate_commission,0,',','.') }}đ</td>
            <td>{{ $o->status_label }}</td>
            <td style="font-size:11px;color:var(--tx3)">{{ $o->created_at->format('d/m/Y H:i') }}</td>
          </tr>
          @empty
          <tr><td colspan="6" style="text-align:center;padding:28px;color:var(--tx3)">Chưa có đơn hàng nào từ CTV này</td></tr>
          @endforelse
          </tbody>
        </table>
      </div>
      @if($orders->hasPages())
      <div>{{ $orders->links() }}</div>
      @endif
    </div>
  </div>
</div>
</body>
</html>