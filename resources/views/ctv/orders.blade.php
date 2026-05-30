@extends('ctv.layout')
@section('title','Đơn hàng')
@section('content')
<div class="card">
  <h2>📦 Đơn hàng của bạn</h2>
  @forelse($orders as $o)
    <div style="padding:11px 0;border-bottom:1px solid #EAF6D8">
      <div style="display:flex;justify-content:space-between;align-items:start">
        <div>
          <div style="font-weight:800">{{ $o->code }}</div>
          <div class="muted">{{ $o->customer_name }} · {{ $o->customer_phone }}</div>
          <div class="muted">{{ $o->items_count }} sản phẩm · {{ $o->created_at->format('d/m/Y H:i') }}</div>
        </div>
        <div style="text-align:right">
          <div style="font-weight:800">{{ number_format($o->total,0,',','.') }}đ</div>
          <div style="color:var(--gd);font-weight:800;font-size:13px">+{{ number_format($o->affiliate_commission,0,',','.') }}đ</div>
          <span class="badge" style="background:{{ $o->status_color }}22;color:{{ $o->status_color }}">{{ $o->status_label }}</span>
        </div>
      </div>
    </div>
  @empty
    <p class="muted">Chưa có đơn nào. <a href="{{ route('ctv.order.create') }}" style="color:var(--g);font-weight:700">Lên đơn ngay →</a></p>
  @endforelse
  <div style="margin-top:14px">{{ $orders->links() }}</div>
</div>
@endsection
