@extends('ctv.layout')
@section('title','Hồ sơ')

@section('content')
<div class="card">
  <div class="card-title"><span class="ic">👤</span> Hồ sơ cộng tác viên</div>

  {{-- Thông tin cơ bản (chỉ đọc) --}}
  <div class="field">
    <label>Họ tên</label>
    <input type="text" value="{{ $ctv->name }}" disabled>
  </div>
  <div class="field">
    <label>Số điện thoại <span class="muted">(liên hệ admin để thay đổi)</span></label>
    <input type="text" value="{{ $ctv->phone }}" disabled>
  </div>
  <div class="field">
    <label>Mã CTV</label>
    <input type="text" value="{{ $ctv->code }}" disabled>
  </div>
  <div class="field">
    <label>Tỉ lệ hoa hồng</label>
    <input type="text" value="{{ $ctv->commission_rate }}%" disabled>
  </div>
</div>

<form method="POST" action="{{ route('ctv.profile.update') }}">
  @csrf

  {{-- Thông tin ngân hàng --}}
  <div class="card">
    <div class="card-title"><span class="ic">🏦</span> Thông tin ngân hàng</div>

    <div class="field">
      <label>Tên ngân hàng</label>
      <input type="text" name="bank_name"
             value="{{ old('bank_name', $ctv->bank_name) }}"
             placeholder="VD: Vietcombank, BIDV, Techcombank...">
    </div>
    <div class="field">
      <label>Số tài khoản</label>
      <input type="text" name="bank_acc"
             value="{{ old('bank_acc', $ctv->bank_acc) }}"
             placeholder="Nhập số tài khoản">
    </div>
    <div class="field">
      <label>Tên chủ tài khoản</label>
      <input type="text" name="bank_owner"
             value="{{ old('bank_owner', $ctv->bank_owner) }}"
             placeholder="Tên in hoa đúng như trên tài khoản">
      <span class="hint">Nhập đúng tên chủ tài khoản để tránh sai sót khi chuyển tiền.</span>
    </div>
  </div>

  {{-- Đổi mật khẩu --}}
  <div class="card">
    <div class="card-title"><span class="ic">🔒</span> Đổi mật khẩu</div>
    <p class="muted" style="margin-bottom:14px;font-size:12px">Để trống nếu không muốn thay đổi mật khẩu.</p>

    <div class="field">
      <label>Mật khẩu mới</label>
      <input type="password" name="new_password"
             autocomplete="new-password"
             placeholder="Tối thiểu 6 ký tự">
    </div>
    <div class="field">
      <label>Xác nhận mật khẩu mới</label>
      <input type="password" name="new_password_confirmation"
             autocomplete="new-password"
             placeholder="Nhập lại mật khẩu mới">
    </div>
  </div>

  <button type="submit" class="btn full">💾 Lưu thay đổi</button>
</form>
@endsection
