@include('tho-in._khung', ['tieuDe' => 'Đăng nhập'])
<div class="box" style="max-width:380px;margin:40px auto">
  <h2>Đăng nhập thợ in</h2>
  <p class="mu" style="margin-bottom:12px">Tài khoản do chủ xưởng cấp. Chỉ xem và cập nhật hàng đợi in.</p>
  <form method="POST" action="{{ route('tho-in.dang-nhap.post') }}">
    @csrf
    <label>Số điện thoại<input name="sdt" value="{{ old('sdt') }}" required autocomplete="username"></label><br>
    <label>Mật khẩu<input name="matkhau" type="password" required autocomplete="current-password"></label><br>
    <button class="btn" style="width:100%">Đăng nhập</button>
  </form>
</div>
@include('tho-in._het')
