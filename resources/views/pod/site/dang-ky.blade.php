@extends('pod.site._khung', ['tab' => 'dk'])
@section('tieu_de', 'Đăng ký làm seller POD')
@section('mo_ta', 'Đăng ký tài khoản seller POD tại xưởng in 3D DALI. Xưởng duyệt và báo qua Zalo, sau đó bạn đặt in ngay trên cổng seller.')
@section('css')
.dk{display:grid;gap:32px;padding:52px 0 24px;align-items:start}
@media(min-width:900px){.dk{grid-template-columns:1.15fr .85fr}}
.form{background:#fff;border:1px solid var(--bd);border-radius:22px;padding:28px}
.o{display:flex;flex-direction:column;gap:6px;margin-bottom:16px}
.o span{font-size:14px;font-weight:700}
.o small{color:var(--mu);font-size:13px;font-weight:400}
.o input,.o textarea,.o select{font:inherit;font-size:16px;border:1.5px solid var(--bd);border-radius:12px;padding:12px 14px;background:var(--gll);color:var(--ink);outline:none}
.o input:focus,.o textarea:focus{border-color:var(--g);background:#fff}
.hai{display:grid;gap:0 16px}@media(min-width:600px){.hai{grid-template-columns:1fr 1fr}}
.bay{position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden}
.tiep ol{list-style:none;counter-reset:b;display:grid;gap:14px;margin-top:14px}
.tiep li{counter-increment:b;display:flex;gap:12px;font-size:15px}
.tiep li:before{content:counter(b);flex:none;width:28px;height:28px;border-radius:9px;background:var(--g);color:#fff;font-weight:800;display:flex;align-items:center;justify-content:center}
@endsection
@section('noi_dung')
<div class="wrap dk">
  <div>
    <span class="nhan">Đăng ký seller</span>
    <h1 style="font-size:clamp(30px,4.2vw,42px);margin-bottom:22px">Mở tài khoản đặt in</h1>
    @if(session('xong'))
      <div class="tb-ok"><b>Đã gửi đăng ký.</b> Xưởng sẽ xem và báo qua Zalo số bạn vừa nhập. Khi được duyệt, bạn <a href="/cong">đăng nhập cổng seller</a> bằng số điện thoại và mật khẩu đã đặt.</div>
    @else
      @if($errors->any())<div class="tb-loi">{{ $errors->first() }}</div>@endif
      <form class="form" method="POST" action="/dang-ky">
        @csrf
        <div class="bay" aria-hidden="true"><label>Website<input name="website" tabindex="-1" autocomplete="off"></label></div>
        <label class="o"><span>Tên shop hoặc tên bạn</span><input name="ten" value="{{ old('ten') }}" required maxlength="120" autocomplete="organization"></label>
        <label class="o"><span>Số điện thoại (Zalo) <small>dùng để đăng nhập và nhận báo duyệt</small></span><input name="sdt" value="{{ old('sdt') }}" required maxlength="20" inputmode="tel" autocomplete="tel"></label>
        <div class="hai">
          <label class="o"><span>Mật khẩu <small>ít nhất 6 ký tự</small></span><input name="matkhau" type="password" required minlength="6" maxlength="100" autocomplete="new-password"></label>
          <label class="o"><span>Nhập lại mật khẩu</span><input name="matkhau_confirmation" type="password" required minlength="6" maxlength="100" autocomplete="new-password"></label>
        </div>
        <div class="hai">
          <label class="o"><span>Bạn đang bán ở đâu</span><input name="san_ban" value="{{ old('san_ban') }}" maxlength="120" placeholder="Etsy, Amazon, TikTok Shop…" list="dsSan"></label>
          <label class="o"><span>Link shop <small>không bắt buộc</small></span><input name="link_shop" value="{{ old('link_shop') }}" maxlength="300" placeholder="https://"></label>
        </div>
        <datalist id="dsSan"><option value="Etsy"><option value="Amazon"><option value="TikTok Shop"><option value="Shopify"><option value="eBay"></datalist>
        <label class="o"><span>Bạn định bán sản phẩm gì <small>không bắt buộc</small></span><textarea name="loi_nhan" rows="3" maxlength="1000" placeholder="Vd: khuôn cắt bánh quy theo mùa lễ, khoảng 30 đơn mỗi tuần">{{ old('loi_nhan') }}</textarea></label>
        <label style="display:flex;gap:10px;align-items:flex-start;font-size:15px;margin:4px 0 18px"><input type="checkbox" name="dong_y" value="1" required style="margin-top:5px;width:18px;height:18px;accent-color:var(--g)" @checked(old('dong_y'))> <span>Tôi đồng ý <a href="/#bao-mat">điều khoản bảo mật thiết kế</a>, không cho người khác dùng chung tài khoản, và giá in tính theo gram tại lúc đặt đơn.</span></label>
        <button class="nut nut-chinh" style="width:100%">Gửi đăng ký</button>
        <p style="color:var(--mu);font-size:14px;margin-top:12px;text-align:center">Đã có tài khoản? <a href="/cong">Đăng nhập</a></p>
      </form>
    @endif
  </div>
  <aside class="the tiep">
    <h3>Sau khi gửi đăng ký</h3>
    <ol>
      <li>Xưởng xem thông tin shop và duyệt tài khoản.</li>
      <li>Xưởng báo qua Zalo khi đã duyệt.</li>
      <li>Bạn đăng nhập, nạp ví và đặt đơn ngay trên cổng seller.</li>
      <li>Muốn mẫu riêng: gửi yêu cầu cho xưởng, mẫu xong nằm trong Kho riêng của bạn.</li>
    </ol>
    <p style="color:var(--mu);font-size:14px;margin-top:18px">Cần gấp? Nhắn Zalo <a href="https://zalo.me/0856911698" rel="nofollow">0856 911 698</a>, 8h–20h thứ 2 đến thứ 7.</p>
  </aside>
</div>
@endsection
