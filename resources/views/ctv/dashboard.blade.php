@extends('ctv.layout')
@section('title','Tổng quan')
@section('content')

{{-- HERO GREETING --}}
<div style="background:linear-gradient(135deg,#1A4D00 0%,#2D7A08 60%,#3A9A12 100%);border-radius:20px;padding:20px 18px 18px;color:#fff;margin-bottom:14px;position:relative;overflow:hidden">
  <div style="position:absolute;right:16px;top:50%;transform:translateY(-50%);font-size:56px;opacity:.15">🎨</div>
  <div style="font-size:11px;color:rgba(255,255,255,.7);font-weight:600;letter-spacing:.5px">Xin chào,</div>
  <div style="font-size:20px;font-weight:900;margin-bottom:1px">{{ $ctv->name }}</div>
  <div style="font-size:11.5px;color:rgba(255,255,255,.75);margin-bottom:14px">
    Mã giới thiệu: <b style="color:#C6F135">{{ $ctv->code }}</b> &nbsp;·&nbsp; Hoa hồng <b style="color:#C6F135">{{ rtrim(rtrim(number_format($ctv->commission_rate,1),'0'),'.') }}%</b>/đơn
  </div>
  {{-- ref link --}}
  <div style="background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.25);border-radius:12px;padding:10px 13px;display:flex;align-items:center;gap:8px">
    <div style="flex:1;font-size:11.5px;color:rgba(255,255,255,.9);word-break:break-all;font-weight:600">{{ url('/ref/'.$ctv->code) }}</div>
    <button onclick="copyText('{{ url('/ref/'.$ctv->code) }}')" style="flex-shrink:0;background:#C6F135;color:#1A4D00;border:none;border-radius:50px;padding:5px 12px;font-size:11px;font-weight:800;cursor:pointer;font-family:inherit">Sao chép</button>
  </div>
</div>

{{-- STATS --}}
<div class="stat-row">
  <div class="stat-box">
    <div class="num" style="color:var(--gd)">{{ number_format($ctv->available,0,',','.') }}đ</div>
    <div class="lbl">💰 Khả dụng</div>
  </div>
  <div class="stat-box">
    <div class="num" style="color:var(--yl)">{{ number_format($ctv->pending_withdraw,0,',','.') }}đ</div>
    <div class="lbl">⏳ Đang rút</div>
  </div>
  <div class="stat-box">
    <div class="num" style="color:#6BBF1F">{{ number_format($ctv->total_earned,0,',','.') }}đ</div>
    <div class="lbl">📈 Tổng đã kiếm</div>
  </div>
  <div class="stat-box">
    <div class="num" style="color:var(--tx2)">{{ $ctv->total_orders }}</div>
    <div class="lbl">📦 Đơn hàng</div>
  </div>
</div>

{{-- QUICK ACTIONS --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px">
  <a href="{{ route('ctv.order.create') }}" class="btn" style="border-radius:14px;padding:14px 10px;flex-direction:column;gap:4px;text-decoration:none">
    <span style="font-size:24px">➕</span>
    <span style="font-size:13px">Lên đơn mới</span>
  </a>
  <a href="{{ route('ctv.withdraw.page') }}" class="btn ghost" style="border-radius:14px;padding:14px 10px;flex-direction:column;gap:4px;text-decoration:none">
    <span style="font-size:24px">💳</span>
    <span style="font-size:13px">Rút tiền</span>
  </a>
</div>

{{-- ĐƠN GẦN ĐÂY --}}
<div class="card">
  <div class="card-title"><span class="ic">📦</span>Đơn hàng gần đây</div>
  @forelse($orders as $o)
    <div class="order-row">
      <div class="top">
        <div>
          <div class="code">{{ $o->code }}</div>
          <div class="meta">{{ $o->customer_name }} · {{ $o->customer_phone }}</div>
          <div class="meta">{{ $o->created_at->format('d/m/Y H:i') }}</div>
        </div>
        <div class="amount">
          <div class="total">{{ number_format($o->total,0,',','.') }}đ</div>
          <div class="comm">+{{ number_format($o->affiliate_commission,0,',','.') }}đ</div>
          <span class="badge" style="background:{{ $o->status_color }}22;color:{{ $o->status_color }};margin-top:4px;display:inline-block">{{ $o->status_label }}</span>
        </div>
      </div>
    </div>
  @empty
    <div class="empty-state">
      <div class="ei">📋</div>
      <div class="et">Chưa có đơn hàng</div>
      <div class="es">Bắt đầu lên đơn để nhận hoa hồng!</div>
      <a href="{{ route('ctv.order.create') }}" class="btn sm" style="margin-top:14px;text-decoration:none;display:inline-flex">Lên đơn ngay →</a>
    </div>
  @endforelse
  @if(count($orders) >= 5)
  <a href="{{ route('ctv.orders') }}" style="display:block;text-align:center;padding:10px;font-size:13px;font-weight:700;color:var(--g);text-decoration:none;margin-top:6px">Xem tất cả đơn →</a>
  @endif
</div>

{{-- HƯỚNG DẪN --}}
<div class="card" style="background:linear-gradient(135deg,var(--gll),#fff)">
  <div class="card-title"><span class="ic">💡</span>Cách nhận hoa hồng</div>
  <div style="display:flex;flex-direction:column;gap:10px">
    @foreach([
      ['1','Chia sẻ link giới thiệu của bạn (sao chép ở trên) cho khách hàng.'],
      ['2','Hoặc lên đơn trực tiếp thay mặt khách tại mục "Lên đơn".'],
      ['3','Hoa hồng tự động được ghi nhận khi đơn được tạo.'],
      ['4','Khi đủ 50.000đ, yêu cầu rút tiền → admin duyệt → chuyển khoản.'],
    ] as [$n,$t])
    <div style="display:flex;gap:10px;align-items:flex-start">
      <span style="background:linear-gradient(135deg,var(--g),var(--gn));color:#1A4D00;font-weight:900;font-size:12px;width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0">{{ $n }}</span>
      <span style="font-size:13px;color:var(--tx2);padding-top:3px">{{ $t }}</span>
    </div>
    @endforeach
  </div>
</div>

@endsection
