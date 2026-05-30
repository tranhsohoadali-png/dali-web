@extends('ctv.layout')
@section('title','Đăng nhập')
@section('content')
<div style="max-width:400px;margin:40px auto 0">
  <div style="text-align:center;margin-bottom:22px">
    <div style="font-size:34px;font-weight:900;letter-spacing:3px;color:var(--gd)">DAL<span style="color:var(--g)">I</span></div>
    <div style="font-size:13px;color:var(--tx2);font-weight:600">Cổng Cộng Tác Viên</div>
  </div>
  <div class="card">
    <h2>🔐 Đăng nhập CTV</h2>
    <form method="POST" action="{{ route('ctv.login.post') }}">
      @csrf
      <div class="field">
        <label>Số điện thoại</label>
        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="0901 234 567" required autofocus>
      </div>
      <div class="field">
        <label>Mật khẩu</label>
        <input type="password" name="password" placeholder="••••••" required>
      </div>
      <button type="submit" class="btn full" style="margin-top:6px">Đăng nhập</button>
    </form>
    <p class="muted" style="text-align:center;margin-top:14px">Chưa có tài khoản? Liên hệ quản trị viên DALI để được cấp.</p>
  </div>
</div>
@endsection
