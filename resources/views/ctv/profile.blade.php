@extends('ctv.layout')
@section('title','Hồ sơ')
@section('content')

<div style="font-size:17px;font-weight:900;color:var(--gd);margin-bottom:14px">👤 Hồ sơ của tôi</div>

{{-- THÔNG TIN CƠ BẢN --}}
<div class="card">
  <div class="card-title"><span class="ic">🪪</span>Thông tin cá nhân</div>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:4px">
    <div style="background:var(--gll);border-radius:12px;padding:12px">
      <div class="muted" style="margin-bottom:3px">Họ tên</div>
      <div style="font-size:14px;font-weight:800;color:var(--tx)">{{ $ctv->name }}</div>
    </div>
    <div style="background:var(--gll);border-radius:12px;padding:12px">
      <div class="muted" style="margin-bottom:3px">Số điện thoại</div>
      <div style="font-size:14px;font-weight:800;color:var(--tx)">{{ $ctv->phone }}</div>
    </div>
    <div style="background:linear-gradient(135deg,var(--gl),var(--gll));border-radius:12px;padding:12px">
      <div class="muted" style="margin-bottom:3px">Mã giới thiệu</div>
      <div style="font-size:14px;font-weight:900;color:var(--gd)">{{ $ctv->code }}</div>
    </div>
    <div style="background:var(--gll);border-radius:12px;padding:12px">
      <div class="muted" style="margin-bottom:3px">{{ $ctv->isAgent() ? 'Loại tài khoản' : 'Hoa hồng' }}</div>
      <div style="font-size:18px;font-weight:900;color:var(--g)">@if($ctv->isAgent())Đại lý 🏷️@else{{ rtrim(rtrim(number_format($ctv->commission_rate,1),'0'),'.') }}%@endif</div>
    </div>
  </div>
  <p class="muted" style="margin-top:10px;font-size:11px">Liên hệ DALI để cập nhật tên hoặc số điện thoại.</p>
</div>

<form method="POST" action="{{ route('ctv.profile.update') }}">
  @csrf

  {{-- NGÂN HÀNG --}}
  <div class="card">
    <div class="card-title"><span class="ic">🏦</span>Thông tin ngân hàng</div>
    <p class="muted" style="margin-bottom:14px">Thông tin này dùng để chuyển tiền hoa hồng cho bạn. Nhập chính xác tránh sai sót.</p>
    <div class="field">
      <label>Tên ngân hàng</label>
      <input type="text" name="bank_name" value="{{ old('bank_name',$ctv->bank_name) }}"
        placeholder="VD: Vietcombank, BIDV, MB Bank...">
    </div>
    <div class="field">
      <label>Số tài khoản</label>
      <input type="text" name="bank_acc" value="{{ old('bank_acc',$ctv->bank_acc) }}"
        placeholder="Nhập số tài khoản" inputmode="numeric">
    </div>
    <div class="field" style="margin-bottom:0">
      <label>Chủ tài khoản</label>
      <input type="text" name="bank_owner" value="{{ old('bank_owner',$ctv->bank_owner) }}"
        placeholder="TÊN IN HOA ĐÚNG NHƯ TÀI KHOẢN" style="text-transform:uppercase">
      <div class="hint">⚠️ Nhập chính xác tên chủ tài khoản (chữ hoa, không dấu) để admin chuyển đúng.</div>
    </div>
  </div>

  {{-- ĐỔI MẬT KHẨU --}}
  <div class="card">
    <div class="card-title"><span class="ic">🔐</span>Đổi mật khẩu đăng nhập</div>
    <p class="muted" style="margin-bottom:14px">Để trống nếu không muốn thay đổi.</p>
    <div class="field">
      <label>Mật khẩu mới</label>
      <input type="password" name="new_password" autocomplete="new-password" placeholder="Tối thiểu 6 ký tự">
    </div>
    <div class="field" style="margin-bottom:0">
      <label>Xác nhận mật khẩu mới</label>
      <input type="password" name="new_password_confirmation" autocomplete="new-password" placeholder="Nhập lại mật khẩu mới">
    </div>
  </div>

  <button type="submit" class="btn full">💾 Lưu thay đổi</button>
</form>

@endsection
