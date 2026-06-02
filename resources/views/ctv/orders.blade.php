@extends('ctv.layout')
@section('title','Đơn hàng')
@section('content')

<div style="font-size:17px;font-weight:900;color:var(--gd);margin-bottom:14px">📦 Đơn hàng của bạn</div>

@if($orders->isEmpty())
<div class="card">
  <div class="empty">
    <div class="ei">📋</div>
    <div class="et">Chưa có đơn nào</div>
    <div class="es">Lên đơn hoặc chia sẻ link để nhận hoa hồng</div>
    <a href="{{ route('ctv.order.create') }}" class="btn sm" style="margin-top:16px;text-decoration:none;display:inline-flex">➕ Lên đơn ngay</a>
  </div>
</div>
@else
<div class="card">
  @foreach($orders as $o)
  <div class="orow">
    <div class="orow-top">
      <div style="flex:1;min-width:0">
        <div class="orow-code">{{ $o->code }}</div>
        <div class="orow-meta">{{ $o->customer_name }} · {{ $o->customer_phone }}</div>
        @if($o->customer_city)<div class="orow-meta">📍 {{ $o->customer_city }}</div>@endif
        <div class="orow-meta">🕒 {{ $o->created_at->format('d/m/Y H:i') }}</div>
      </div>
      <div class="orow-right">
        <div class="orow-total">{{ number_format($o->total,0,',','.') }}đ</div>
        @if($ctv->isAgent())
          @if(($o->deposit ?? 0) > 0)<div class="orow-comm" style="color:#6D28D9">Cọc {{ number_format($o->deposit,0,',','.') }}đ{{ $o->deposit_paid ? ' ✓' : '' }}</div>@endif
        @else
          <div class="orow-comm">+{{ number_format($o->affiliate_commission,0,',','.') }}đ</div>
        @endif
        <span class="badge" style="margin-top:4px;background:{{ $o->status_color }}22;color:{{ $o->status_color }}">{{ $o->status_label }}</span>
      </div>
    </div>
    {{-- Items --}}
    @foreach($o->items as $item)
    <div style="margin-top:8px;background:var(--gll);border-radius:10px;padding:8px 11px;font-size:12px;color:var(--tx2)">
      · {{ $item->product_name }}
      @if($item->product_size)<span style="color:var(--tx3)">· {{ $item->product_size }}</span>@endif
      × {{ $item->quantity }} — <b>{{ number_format($item->subtotal,0,',','.') }}đ</b>
    </div>
    @endforeach
  </div>
  @endforeach
</div>
<div style="margin-top:4px">{{ $orders->links() }}</div>
@endif

@endsection
