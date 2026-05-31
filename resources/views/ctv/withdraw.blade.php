@extends('ctv.layout')
@section('title','Rút tiền')
@section('content')

<div style="font-size:17px;font-weight:900;color:var(--gd);margin-bottom:14px">💳 Rút tiền hoa hồng</div>

{{-- Số dư tổng quan --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px">
  <div style="background:linear-gradient(135deg,var(--gd),var(--g));border-radius:18px;padding:18px;color:#fff;text-align:center">
    <div style="font-size:10px;font-weight:700;opacity:.8;margin-bottom:4px">💰 Khả dụng</div>
    <div style="font-size:24px;font-weight:900">{{ number_format($ctv->available,0,',','.') }}đ</div>
    <div style="font-size:10px;opacity:.7;margin-top:3px">Có thể rút ngay</div>
  </div>
  <div style="background:#fff;border:1.5px solid var(--bd);border-radius:18px;padding:18px;text-align:center">
    <div style="font-size:10px;font-weight:700;color:var(--tx3);margin-bottom:4px">📈 Tổng kiếm</div>
    <div style="font-size:20px;font-weight:900;color:var(--gd)">{{ number_format($ctv->total_earned,0,',','.') }}đ</div>
    <div style="font-size:10px;color:var(--tx3);margin-top:3px">Đã nhận: {{ number_format($ctv->total_paid,0,',','.') }}đ</div>
  </div>
</div>

{{-- Form rút tiền --}}
@if(!$ctv->bank_acc)
  <div class="card" style="border-color:#FCA5A5;background:#FEF2F2">
    <div class="card-title" style="color:#B91C1C"><span class="ic">⚠️</span>Chưa có thông tin ngân hàng</div>
    <p style="font-size:13px;color:#991B1B">Bạn cần có thông tin ngân hàng để rút tiền. Vui lòng liên hệ quản trị viên DALI để cập nhật số tài khoản của bạn.</p>
    <a href="tel:0856911698" class="btn" style="margin-top:14px;text-decoration:none;background:linear-gradient(135deg,#EF4444,#B91C1C)">📞 Gọi cho DALI ngay</a>
  </div>
@elseif($ctv->available < 50000)
  <div class="card" style="border-color:#FDE68A;background:#FFFBEB">
    <div class="card-title" style="color:#92400E"><span class="ic">⏳</span>Chưa đủ số dư tối thiểu</div>
    <p style="font-size:13px;color:#B45309">Số dư khả dụng của bạn là <b>{{ number_format($ctv->available,0,',','.') }}đ</b>. Cần tối thiểu <b>50.000đ</b> để rút tiền.</p>
    <p style="font-size:12px;color:var(--tx3);margin-top:8px">Chia sẻ link giới thiệu hoặc lên đơn mới để tích luỹ thêm hoa hồng.</p>
    <a href="{{ route('ctv.order.create') }}" class="btn sm" style="margin-top:14px;text-decoration:none;display:inline-flex">➕ Lên đơn mới</a>
  </div>
@else
  <div class="card">
    <div class="card-title"><span class="ic">🏦</span>Thông tin nhận tiền</div>
    <div style="background:var(--gll);border:1.5px solid var(--bd);border-radius:12px;padding:14px;margin-bottom:6px">
      <div style="font-size:13px;font-weight:800;color:var(--gd)">{{ $ctv->bank_name }}</div>
      <div style="font-size:18px;font-weight:900;color:var(--tx);letter-spacing:1px;margin:4px 0">{{ $ctv->bank_acc }}</div>
      <div style="font-size:12px;color:var(--tx2);font-weight:700;text-transform:uppercase">{{ $ctv->bank_owner }}</div>
    </div>
    <p class="muted" style="margin-bottom:16px">Thông tin sai? Liên hệ admin để cập nhật.</p>

    <form method="POST" action="{{ route('ctv.withdraw') }}">
      @csrf
      <div class="field">
        <label>Số tiền muốn rút *</label>
        <input type="number" name="amount" min="50000" max="{{ $ctv->available }}" step="10000"
          placeholder="Tối thiểu 50.000đ" required
          oninput="document.getElementById('amtPreview').textContent=this.value?'= '+parseInt(this.value).toLocaleString('vi-VN')+'đ':''">
        <div class="hint" id="amtPreview" style="font-weight:700;color:var(--gd)"></div>
        <div class="hint">Tối thiểu: 50.000đ &nbsp;·&nbsp; Tối đa: {{ number_format($ctv->available,0,',','.') }}đ</div>
      </div>
      <div class="field">
        <label>Ghi chú (tuỳ chọn)</label>
        <textarea name="note" rows="2" placeholder="VD: Rút hoa hồng tháng 6..."></textarea>
      </div>
      <button type="submit" class="btn full" onclick="return confirm('Xác nhận gửi yêu cầu rút tiền?')">
        Gửi yêu cầu rút tiền →
      </button>
    </form>
  </div>

  {{-- Pending withdrawal warning --}}
  @if($ctv->pending_withdraw > 0)
  <div class="card" style="border-color:#FDE68A;background:#FFFBEB">
    <div style="font-size:12.5px;color:#92400E;font-weight:700">⏳ Bạn đang có <b>{{ number_format($ctv->pending_withdraw,0,',','.') }}đ</b> chờ duyệt. Vui lòng đợi admin xử lý trước khi gửi thêm yêu cầu.</div>
  </div>
  @endif
@endif

{{-- Lịch sử rút tiền --}}
<div class="card">
  <div class="card-title"><span class="ic">📜</span>Lịch sử rút tiền</div>
  @forelse($withdrawals as $w)
    <div class="withdraw-row">
      <div style="display:flex;justify-content:space-between;align-items:flex-start">
        <div>
          <div style="font-size:16px;font-weight:900;color:var(--gd)">{{ number_format($w->amount,0,',','.') }}đ</div>
          <div class="muted">{{ $w->created_at->format('d/m/Y H:i') }}</div>
          @if($w->note)<div class="muted">📝 {{ $w->note }}</div>@endif
          @if($w->processed_at && $w->status=='approved')<div class="muted" style="color:var(--g);font-weight:700">✅ Đã nhận {{ $w->processed_at->format('d/m/Y') }}</div>@endif
        </div>
        <span class="badge {{ $w->status }}">{{ $w->status_label }}</span>
      </div>
    </div>
  @empty
    <div class="empty-state" style="padding:24px">
      <div class="es">Chưa có lịch sử rút tiền nào.</div>
    </div>
  @endforelse
  @if($withdrawals->hasPages())
  <div style="margin-top:10px">{{ $withdrawals->links() }}</div>
  @endif
</div>

@endsection
