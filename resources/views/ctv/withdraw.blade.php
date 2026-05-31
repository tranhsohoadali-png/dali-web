@extends('ctv.layout')
@section('title','Rút tiền')
@section('content')

<div style="font-size:17px;font-weight:900;color:var(--gd);margin-bottom:14px">💳 Rút tiền hoa hồng</div>

{{-- SỐ DƯ --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px">
  <div style="background:linear-gradient(135deg,var(--gd),var(--g));border-radius:18px;padding:16px;color:#fff;text-align:center">
    <div style="font-size:10px;font-weight:700;opacity:.8;margin-bottom:5px">💰 Có thể rút</div>
    <div style="font-size:22px;font-weight:900;line-height:1">{{ number_format($ctv->available,0,',','.') }}đ</div>
  </div>
  <div class="stat-box">
    <div class="sn" style="color:var(--gd);font-size:18px">{{ number_format($ctv->total_earned,0,',','.') }}đ</div>
    <div class="sl">📈 Tổng đã kiếm</div>
    <div style="font-size:10px;color:var(--tx3);margin-top:3px">Đã nhận: {{ number_format($ctv->total_paid,0,',','.') }}đ</div>
  </div>
</div>

{{-- TRẠNG THÁI --}}
@if(!$ctv->bank_acc)
<div class="card" style="border-color:#FCA5A5;background:#FEF2F2">
  <div class="card-title" style="color:#B91C1C"><span class="ic">⚠️</span>Chưa có thông tin ngân hàng</div>
  <p style="font-size:13px;color:#991B1B;margin-bottom:14px">Vui lòng cập nhật thông tin ngân hàng trong mục Hồ sơ trước khi rút tiền.</p>
  <a href="{{ route('ctv.profile') }}" class="btn danger sm" style="text-decoration:none;display:inline-flex">👤 Cập nhật hồ sơ</a>
</div>
@elseif($ctv->available < 50000)
<div class="card" style="border-color:#FDE68A;background:#FFFBEB">
  <div class="card-title" style="color:#92400E"><span class="ic">⏳</span>Chưa đủ số dư tối thiểu</div>
  <p style="font-size:13px;color:#B45309;margin-bottom:8px">Số dư khả dụng: <b>{{ number_format($ctv->available,0,',','.') }}đ</b> — cần tối thiểu <b>50.000đ</b>.</p>
  <a href="{{ route('ctv.order.create') }}" class="btn sm" style="text-decoration:none;display:inline-flex">➕ Lên đơn thêm</a>
</div>
@else
<div class="card">
  <div class="card-title"><span class="ic">🏦</span>Nhận tiền về</div>
  <div style="background:var(--gll);border:1.5px solid var(--bd);border-radius:12px;padding:14px;margin-bottom:16px">
    <div style="font-size:13px;font-weight:800;color:var(--gd)">{{ $ctv->bank_name }}</div>
    <div style="font-size:20px;font-weight:900;letter-spacing:1px;margin:4px 0">{{ $ctv->bank_acc }}</div>
    <div style="font-size:12px;color:var(--tx2);font-weight:700;text-transform:uppercase">{{ $ctv->bank_owner }}</div>
  </div>
  @if($ctv->pending_withdraw > 0)
  <div style="background:#FFFBEB;border:1px solid #FDE68A;border-radius:10px;padding:10px 12px;font-size:12.5px;color:#92400E;font-weight:700;margin-bottom:14px">
    ⏳ Đang có <b>{{ number_format($ctv->pending_withdraw,0,',','.') }}đ</b> chờ admin duyệt.
  </div>
  @endif
  <form method="POST" action="{{ route('ctv.withdraw') }}">
    @csrf
    <div class="field">
      <label>Số tiền muốn rút *</label>
      <input type="number" name="amount" min="50000" max="{{ $ctv->available }}" step="10000"
        placeholder="Tối thiểu 50.000đ" required inputmode="numeric"
        oninput="document.getElementById('amtPrev').textContent=this.value?'= '+parseInt(this.value||0).toLocaleString('vi-VN')+'đ':''">
      <div class="hint" id="amtPrev" style="font-weight:800;color:var(--gd);font-size:13px"></div>
      <div class="hint">Tối đa: {{ number_format($ctv->available,0,',','.') }}đ</div>
    </div>
    <div class="field">
      <label>Ghi chú (tuỳ chọn)</label>
      <textarea name="note" rows="2" placeholder="VD: Rút hoa hồng tháng 6..."></textarea>
    </div>
    <button type="submit" class="btn full" onclick="return confirm('Xác nhận gửi yêu cầu rút tiền?')">
      Gửi yêu cầu rút tiền
    </button>
  </form>
</div>
@endif

{{-- LỊCH SỬ --}}
<div class="card">
  <div class="card-title"><span class="ic">📜</span>Lịch sử rút tiền</div>
  @forelse($withdrawals as $w)
  <div style="display:flex;justify-content:space-between;align-items:flex-start;padding:12px 0;border-bottom:1px solid #EDF7DE">
    <div>
      <div style="font-size:16px;font-weight:900;color:var(--gd)">{{ number_format($w->amount,0,',','.') }}đ</div>
      <div class="muted">{{ $w->created_at->format('d/m/Y H:i') }}</div>
      @if($w->note)<div class="muted">📝 {{ $w->note }}</div>@endif
      @if($w->processed_at && $w->status=='approved')
      <div style="font-size:11px;color:var(--g);font-weight:700;margin-top:2px">✅ {{ $w->processed_at->format('d/m/Y') }}</div>
      @endif
    </div>
    <span class="badge {{ $w->status }}">{{ $w->status_label }}</span>
  </div>
  @empty
  <p class="muted" style="padding:8px 0">Chưa có lịch sử rút tiền.</p>
  @endforelse
  @if($withdrawals->hasPages())<div style="margin-top:10px">{{ $withdrawals->links() }}</div>@endif
</div>

@endsection
