@extends('ctv.layout')
@section('title','Đơn hàng')
@section('content')

<div style="font-size:17px;font-weight:900;color:var(--gd);margin-bottom:14px">📦 Đơn hàng của bạn</div>

@if($orders->isEmpty())
  <div class="card">
    <div class="empty-state">
      <div class="ei">📋</div>
      <div class="et">Chưa có đơn nào</div>
      <div class="es">Lên đơn hoặc chia sẻ link để nhận hoa hồng</div>
      <a href="{{ route('ctv.order.create') }}" class="btn sm" style="margin-top:16px;text-decoration:none;display:inline-flex">➕ Lên đơn ngay</a>
    </div>
  </div>
@else
<div class="card">
  @foreach($orders as $o)
  <div class="order-row">
    <div class="top">
      <div>
        <div class="code">{{ $o->code }}</div>
        <div class="meta">{{ $o->customer_name }} · {{ $o->customer_phone }}</div>
        @if($o->customer_city)<div class="meta">📍 {{ $o->customer_city }}</div>@endif
        <div class="meta">🕒 {{ $o->created_at->format('d/m/Y H:i') }}</div>
      </div>
      <div class="amount">
        <div class="total">{{ number_format($o->total,0,',','.') }}đ</div>
        <div class="comm">+{{ number_format($o->affiliate_commission,0,',','.') }}đ HH</div>
        <span class="badge" style="background:{{ $o->status_color }}22;color:{{ $o->status_color }};margin-top:4px">{{ $o->status_label }}</span>
      </div>
    </div>
    {{-- Items --}}
    @foreach($o->items as $item)
    <div style="margin-top:8px;background:var(--gll);border-radius:10px;padding:8px 12px;font-size:12px;color:var(--tx2)">
      {{ $item->product_name }} @if($item->product_size)<span style="color:var(--tx3)">· {{ $item->product_size }}</span>@endif
      × {{ $item->quantity }} — <b>{{ number_format($item->subtotal,0,',','.') }}đ</b>
    </div>
    @endforeach
  </div>
  @endforeach
</div>
<div style="margin-top:8px">{{ $orders->links() }}</div>
@endif

@endsection
