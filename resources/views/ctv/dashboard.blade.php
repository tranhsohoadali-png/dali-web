@extends('ctv.layout')
@section('title','Tổng quan')
@section('content')

<div class="card" style="background:linear-gradient(135deg,#2D7A08,#3A9A12);border:none;color:#fff">
  <div style="font-size:13px;opacity:.85;font-weight:600">Xin chào,</div>
  <div style="font-size:20px;font-weight:900;margin-bottom:2px">{{ $ctv->name }}</div>
  <div style="font-size:12px;opacity:.8">Mã giới thiệu: <b>{{ $ctv->code }}</b> · Hoa hồng {{ rtrim(rtrim(number_format($ctv->commission_rate,1),'0'),'.') }}%</div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px">
  <div class="card" style="margin:0;text-align:center">
    <div class="muted">💰 Số dư khả dụng</div>
    <div style="font-size:22px;font-weight:900;color:var(--gd)">{{ number_format($ctv->available,0,',','.') }}đ</div>
  </div>
  <div class="card" style="margin:0;text-align:center">
    <div class="muted">⏳ Đang chờ rút</div>
    <div style="font-size:22px;font-weight:900;color:#D97706">{{ number_format($ctv->pending_withdraw,0,',','.') }}đ</div>
  </div>
  <div class="card" style="margin:0;text-align:center">
    <div class="muted">📈 Tổng hoa hồng</div>
    <div style="font-size:17px;font-weight:800">{{ number_format($ctv->total_earned,0,',','.') }}đ</div>
  </div>
  <div class="card" style="margin:0;text-align:center">
    <div class="muted">✅ Đã nhận</div>
    <div style="font-size:17px;font-weight:800">{{ number_format($ctv->total_paid,0,',','.') }}đ</div>
  </div>
</div>

{{-- RÚT TIỀN --}}
<div class="card">
  <h2>🏧 Rút tiền hoa hồng</h2>
  @if(!$ctv->bank_acc)
    <p class="muted">Bạn chưa có thông tin ngân hàng. Liên hệ quản trị viên để cập nhật trước khi rút.</p>
  @else
    <p class="muted" style="margin-bottom:10px">Chuyển về: <b>{{ $ctv->bank_name }}</b> – {{ $ctv->bank_acc }} ({{ $ctv->bank_owner }})</p>
    <form method="POST" action="{{ route('ctv.withdraw') }}">
      @csrf
      <div class="field">
        <label>Số tiền muốn rút (tối thiểu 50.000đ — tối đa {{ number_format($ctv->available,0,',','.') }}đ)</label>
        <input type="number" name="amount" min="50000" max="{{ $ctv->available }}" step="1000" placeholder="VD: 200000" {{ $ctv->available < 50000 ? 'disabled' : '' }} required>
      </div>
      <div class="field">
        <label>Ghi chú (tuỳ chọn)</label>
        <input type="text" name="note" placeholder="VD: rút lần 1 tháng 6">
      </div>
      <button type="submit" class="btn full" {{ $ctv->available < 50000 ? 'disabled style=opacity:.5;cursor:not-allowed' : '' }}>
        @if($ctv->available < 50000) Số dư chưa đủ 50.000đ @else Gửi yêu cầu rút tiền @endif
      </button>
    </form>
  @endif
</div>

{{-- LỊCH SỬ RÚT TIỀN --}}
<div class="card">
  <h2>📜 Lịch sử rút tiền</h2>
  @forelse($withdrawals as $w)
    <div style="display:flex;justify-content:space-between;align-items:center;padding:9px 0;border-bottom:1px solid #EAF6D8">
      <div>
        <div style="font-weight:800">{{ number_format($w->amount,0,',','.') }}đ</div>
        <div class="muted">{{ $w->created_at->format('d/m/Y H:i') }}</div>
      </div>
      <span class="badge" style="background:{{ $w->status=='approved'?'#DCFCE7':($w->status=='rejected'?'#FEE2E2':'#FEF3C7') }};color:{{ $w->status=='approved'?'#166534':($w->status=='rejected'?'#991B1B':'#92400E') }}">{{ $w->status_label }}</span>
    </div>
  @empty
    <p class="muted">Chưa có yêu cầu rút tiền nào.</p>
  @endforelse
</div>

{{-- ĐƠN GẦN ĐÂY --}}
<div class="card">
  <h2>🧾 Đơn gần đây</h2>
  @forelse($orders as $o)
    <div style="display:flex;justify-content:space-between;align-items:center;padding:9px 0;border-bottom:1px solid #EAF6D8">
      <div>
        <div style="font-weight:800">{{ $o->code }} · {{ $o->customer_name }}</div>
        <div class="muted">{{ $o->created_at->format('d/m/Y') }} · {{ number_format($o->total,0,',','.') }}đ</div>
      </div>
      <div style="text-align:right">
        <div style="font-weight:800;color:var(--gd)">+{{ number_format($o->affiliate_commission,0,',','.') }}đ</div>
        <span class="badge" style="background:{{ $o->status_color }}22;color:{{ $o->status_color }}">{{ $o->status_label }}</span>
      </div>
    </div>
  @empty
    <p class="muted">Chưa có đơn nào. <a href="{{ route('ctv.order.create') }}" style="color:var(--g);font-weight:700">Lên đơn ngay →</a></p>
  @endforelse
</div>

@endsection
