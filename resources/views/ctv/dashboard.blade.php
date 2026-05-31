@extends('ctv.layout')
@section('title','Tổng quan')
@section('content')

{{-- HERO --}}
<div class="hero-banner">
  <div class="hero-sub">Xin chào,</div>
  <div class="hero-name">{{ $ctv->name }}</div>
  <div class="hero-meta">Mã: <b>{{ $ctv->code }}</b> &nbsp;·&nbsp; Hoa hồng <b>{{ rtrim(rtrim(number_format($ctv->commission_rate,1),'0'),'.') }}%</b>/đơn</div>
  <div class="ref-row">
    <div class="ref-url">{{ url('/ref/'.$ctv->code) }}</div>
    <button class="copy-btn" onclick="copyText('{{ url('/ref/'.$ctv->code) }}',this)">Sao chép</button>
  </div>
</div>

{{-- STATS --}}
<div class="stat-grid">
  <div class="stat-box">
    <div class="sn" style="color:var(--gd)">{{ number_format($ctv->available,0,',','.') }}đ</div>
    <div class="sl">💰 Khả dụng</div>
  </div>
  <div class="stat-box">
    <div class="sn" style="color:var(--yl)">{{ number_format($ctv->pending_withdraw,0,',','.') }}đ</div>
    <div class="sl">⏳ Chờ rút</div>
  </div>
  <div class="stat-box">
    <div class="sn" style="color:var(--g)">{{ number_format($ctv->total_earned,0,',','.') }}đ</div>
    <div class="sl">📈 Tổng kiếm</div>
  </div>
  <div class="stat-box">
    <div class="sn" style="color:var(--tx2)">{{ $ctv->total_orders }}</div>
    <div class="sl">📦 Đơn hàng</div>
  </div>
</div>

{{-- QUICK ACTIONS --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px">
  <a href="{{ route('ctv.order.create') }}" class="btn" style="flex-direction:column;gap:4px;border-radius:16px;padding:16px 10px;text-decoration:none">
    <span style="font-size:26px">➕</span><span style="font-size:13px">Lên đơn mới</span>
  </a>
  <a href="{{ route('ctv.withdraw.page') }}" class="btn ghost" style="flex-direction:column;gap:4px;border-radius:16px;padding:16px 10px;text-decoration:none">
    <span style="font-size:26px">💳</span><span style="font-size:13px">Rút tiền</span>
  </a>
</div>

{{-- ĐƠN GẦN ĐÂY --}}
<div class="card">
  <div class="card-title"><span class="ic">📦</span>Đơn gần đây</div>
  @forelse($orders as $o)
  <div class="orow">
    <div class="orow-top">
      <div>
        <div class="orow-code">{{ $o->code }}</div>
        <div class="orow-meta">{{ $o->customer_name }} · {{ $o->created_at->format('d/m/Y') }}</div>
      </div>
      <div class="orow-right">
        <div class="orow-total">{{ number_format($o->total,0,',','.') }}đ</div>
        <div class="orow-comm">+{{ number_format($o->affiliate_commission,0,',','.') }}đ HH</div>
        <span class="badge" style="margin-top:4px;background:{{ $o->status_color }}22;color:{{ $o->status_color }}">{{ $o->status_label }}</span>
      </div>
    </div>
  </div>
  @empty
  <div class="empty" style="padding:24px">
    <div class="ei" style="font-size:36px">📋</div>
    <div class="es">Chưa có đơn nào. <a href="{{ route('ctv.order.create') }}" style="color:var(--g);font-weight:700">Lên đơn ngay →</a></div>
  </div>
  @endforelse
  @if(count($orders) >= 5)
  <a href="{{ route('ctv.orders') }}" style="display:block;text-align:center;padding:12px 0 2px;font-size:13px;font-weight:700;color:var(--g);text-decoration:none">Xem tất cả →</a>
  @endif
</div>

{{-- HƯỚNG DẪN --}}
<div class="card" style="background:linear-gradient(135deg,var(--gll),#fff)">
  <div class="card-title"><span class="ic">💡</span>Cách nhận hoa hồng</div>
  @foreach([
    ['Chia sẻ link giới thiệu cho khách (sao chép ở trên).','1'],
    ['Hoặc lên đơn trực tiếp thay mặt khách tại tab Lên đơn.','2'],
    ['Hoa hồng tự ghi nhận ngay khi đơn được tạo.','3'],
    ['Đủ 50.000đ → Rút tiền → Admin duyệt → Chuyển khoản.','4'],
  ] as [$t,$n])
  <div style="display:flex;gap:10px;align-items:flex-start;margin-bottom:10px">
    <span style="background:linear-gradient(135deg,var(--g),var(--gn));color:#1A4D00;font-weight:900;font-size:11px;width:22px;height:22px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px">{{ $n }}</span>
    <span style="font-size:13px;color:var(--tx2);padding-top:2px">{{ $t }}</span>
  </div>
  @endforeach
</div>

@endsection
