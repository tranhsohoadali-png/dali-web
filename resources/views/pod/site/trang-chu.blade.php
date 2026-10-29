@extends('pod.site._khung', ['tab' => 'chu'])
@section('tieu_de', 'Xưởng in 3D theo đơn cho seller POD')
@section('mo_ta', 'DALI in 3D theo đơn cho seller bán Etsy, Amazon, TikTok Shop: giá in PETG ' . number_format($gia['PETG'], 0, ',', '.') . 'đ/gram, PLA ' . number_format($gia['PLA'], 0, ',', '.') . 'đ/gram, ví nạp trước, giao tới kho gom trong nước, mẫu độc quyền giữ kín cho riêng từng seller.')
@section('css')
.hero{padding:64px 0 56px;background:radial-gradient(1200px 500px at 85% -10%,#E3F5CF 0%,transparent 60%),var(--bg)}
.hero .wrap{display:grid;gap:40px;align-items:center}
@media(min-width:900px){.hero .wrap{grid-template-columns:1.1fr .9fr}}
.hero .phu{margin:18px 0 28px}
.hero .nuts{display:flex;gap:12px;flex-wrap:wrap}
.hero-anh{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.hero-anh .o{aspect-ratio:1;border-radius:20px;overflow:hidden;background:#fff;border:1px solid var(--bd)}
.hero-anh .o img{width:100%;height:100%;object-fit:cover}
.hero-anh .o:nth-child(2){transform:translateY(24px)}.hero-anh .o:nth-child(4){transform:translateY(24px)}
.hero-gia{background:#fff;border:1px solid var(--bd);border-radius:24px;padding:32px;box-shadow:0 20px 50px rgba(20,50,10,.08)}
.hero-gia .so{font-size:56px;font-weight:800;color:var(--gd);line-height:1}
.dai{background:var(--gdd);color:#fff;padding:26px 0}
.dai .luoi{gap:14px}
.dai b{display:block;font-size:20px}
.dai small{color:#BFE0A4;font-size:14px}
.buoc{position:relative;padding-top:56px}
.buoc .so{position:absolute;top:22px;left:24px;width:38px;height:38px;border-radius:12px;background:var(--g);color:#fff;font-weight:800;display:flex;align-items:center;justify-content:center}
.buoc p{color:var(--mu);font-size:15px}
.kho h3{display:flex;align-items:center;gap:10px}
.kho ul{list-style:none;margin-top:12px}
.kho li{padding:7px 0 7px 26px;position:relative;font-size:15px;color:var(--ink)}
.kho li:before{content:"";position:absolute;left:4px;top:15px;width:10px;height:6px;border-left:2px solid var(--g);border-bottom:2px solid var(--g);transform:rotate(-45deg)}
.kho-rieng{background:linear-gradient(160deg,#FFF5F8 0%,#fff 55%);border-color:#F7D3DF}
.kho-rieng .nhan{background:#FDE6EE;color:#B4235A}
.cam{counter-reset:c;list-style:none;display:grid;gap:12px}
.cam li{counter-increment:c;display:flex;gap:14px;align-items:flex-start;background:#fff;border:1px solid var(--bd);border-radius:14px;padding:16px 18px;font-size:15px}
.cam li:before{content:counter(c);flex:none;width:30px;height:30px;border-radius:10px;background:var(--gl);color:var(--gd);font-weight:800;display:flex;align-items:center;justify-content:center}
.cta{background:linear-gradient(120deg,var(--gd),var(--g));color:#fff;border-radius:28px;padding:44px 32px;display:flex;flex-wrap:wrap;gap:20px;align-items:center;justify-content:space-between}
.cta h2{margin:0}.cta p{color:#E8F7D8}
.cta .nut-vien{border-color:#fff}
@endsection

@section('noi_dung')
<section class="hero">
  <div class="wrap">
    <div>
      <span class="nhan">Xưởng in 3D cho seller POD</span>
      <h1>Bạn bán trên Etsy, Amazon, TikTok Shop. Xưởng DALI in và giao hàng.</h1>
      <p class="phu">Chọn mẫu có sẵn hoặc đặt xưởng thiết kế mẫu độc quyền. Giá in tính theo gram, trừ thẳng vào ví nạp trước. Xưởng giao tới kho gom hoặc line bay của bạn trong nước.</p>
      <div class="nuts">
        <a href="/dang-ky" class="nut nut-chinh">Đăng ký làm seller</a>
        <a href="/kho-mau" class="nut nut-vien">Xem kho mẫu{{ $soMau ? ' (' . $soMau . ')' : '' }}</a>
      </div>
    </div>
    @if($noiBat->count() >= 4)
      <div class="hero-anh" aria-hidden="true">
        @foreach($noiBat->take(4) as $m)<div class="o"><img src="{{ $m->urlAnh() }}" alt="" loading="lazy"></div>@endforeach
      </div>
    @else
      <div class="hero-gia">
        <div class="mu" style="color:var(--mu);font-weight:600">Giá in nhựa PETG</div>
        <div class="so">{{ number_format($gia['PETG'], 0, ',', '.') }}đ</div>
        <div style="font-weight:700;margin-top:4px">mỗi gram · PLA {{ number_format($gia['PLA'], 0, ',', '.') }}đ</div>
        <p style="color:var(--mu);font-size:15px;margin-top:14px">Tính theo tổng gram trên phần mềm cắt lớp, gồm cả support và nhựa xả khi đổi màu. Không phí ẩn.</p>
        <a href="/bang-gia" class="nut nut-vien nut-nho" style="margin-top:18px">Xem bảng giá đầy đủ</a>
      </div>
    @endif
  </div>
</section>

<div class="dai">
  <div class="wrap luoi l3">
    <div><b>Từ {{ number_format(min($gia), 0, ',', '.') }}đ / gram</b><small>PETG {{ number_format($gia['PETG'], 0, ',', '.') }}đ, PLA {{ number_format($gia['PLA'], 0, ',', '.') }}đ, gồm support và nhựa xả</small></div>
    <div><b>Mẫu độc quyền</b><small>Seller khác không thấy, xưởng không in cho ai khác</small></div>
    <div><b>Ví nạp trước</b><small>Đủ tiền là đơn vào hàng đợi in ngay</small></div>
  </div>
</div>

<section id="quy-trinh">
  <div class="wrap">
    <span class="nhan">Quy trình</span>
    <h2>Bốn bước từ đăng ký tới hàng tới kho gom</h2>
    <p class="phu">Không cần gọi điện chốt đơn từng lần. Mọi thứ nằm trong cổng seller.</p>
    <div class="luoi l4" style="margin-top:28px">
      <div class="the buoc"><div class="so">1</div><h3>Đăng ký</h3><p>Điền thông tin shop. Xưởng duyệt và báo qua Zalo.</p></div>
      <div class="the buoc"><div class="so">2</div><h3>Chọn mẫu</h3><p>Lấy mẫu ở Kho chung, hoặc đặt xưởng thiết kế mẫu riêng cho shop bạn.</p></div>
      <div class="the buoc"><div class="so">3</div><h3>Nạp ví, đặt đơn</h3><p>Giá hiện ngay khi chọn. Đủ tiền trong ví thì đơn vào hàng đợi in.</p></div>
      <div class="the buoc"><div class="so">4</div><h3>Xưởng in và giao</h3><p>In xong giao tới kho gom hoặc line bay. Mã vận đơn hiện trên cổng.</p></div>
    </div>
  </div>
</section>

<section style="background:#fff;border-top:1px solid var(--bd);border-bottom:1px solid var(--bd)">
  <div class="wrap">
    <span class="nhan">Hai kho mẫu</span>
    <h2>Dùng mẫu chung, hoặc giữ mẫu độc quyền cho riêng bạn</h2>
    <div class="luoi l2 kho" style="margin-top:28px">
      <div class="the">
        <span class="nhan">Kho chung</span>
        <h3>Mẫu xưởng làm sẵn, mọi seller dùng được</h3>
        <ul><li>Ảnh mockup được dùng để đăng bán</li><li>Không mất phí thiết kế</li><li>Chọn phân loại, màu, chữ cá nhân hoá khi đặt</li></ul>
        <a href="/kho-mau" class="nut nut-vien nut-nho" style="margin-top:16px">Xem Kho chung</a>
      </div>
      <div class="the kho-rieng">
        <span class="nhan">Kho riêng · Độc quyền</span>
        <h3>Mẫu xưởng thiết kế riêng cho shop bạn</h3>
        <ul><li>Chỉ tài khoản của bạn thấy mẫu, seller khác không biết mẫu tồn tại</li><li>Xưởng không in, không bán mẫu này cho bất kỳ ai khác</li><li>Phí thiết kế được hoàn lại khi mẫu đạt 20 đơn in</li></ul>
        <a href="/bang-gia#thiet-ke" class="nut nut-vien nut-nho" style="margin-top:16px">Xem phí thiết kế</a>
      </div>
    </div>
  </div>
</section>

@if($noiBat->isNotEmpty())
<section>
  <div class="wrap">
    <div style="display:flex;justify-content:space-between;align-items:flex-end;gap:16px;flex-wrap:wrap">
      <div><span class="nhan">Kho chung</span><h2 style="margin:0">Mẫu mới trong kho</h2></div>
      <a href="/kho-mau" class="nut nut-vien nut-nho">Xem tất cả</a>
    </div>
    <div class="luoi l4" style="margin-top:24px">
      @foreach($noiBat as $m)
        <a class="mau-the" href="/kho-mau/{{ $m->ma }}"><div class="anh"><img src="{{ $m->urlAnh() }}" alt="{{ $m->ten }}" loading="lazy"></div><div class="chu"><b>{{ $m->ten }}</b><small>{{ $m->nhom }}</small>@php $gt = \App\Services\PodSo::giaTu($m); @endphp @if($gt)<span class="gia-tu">từ {{ number_format($gt, 0, ',', '.') }}đ</span>@endif</div></a>
      @endforeach
    </div>
  </div>
</section>
@endif

<section id="bao-mat" style="background:var(--gll)">
  <div class="wrap luoi l2" style="align-items:start">
    <div>
      <span class="nhan">Bảo mật thiết kế</span>
      <h2>Thiết kế của bạn chỉ là của bạn</h2>
      <p class="phu">Năm cam kết này nằm trong điều khoản bạn đồng ý khi đăng ký. Mẫu riêng được chặn ngay ở máy chủ: seller khác có cố mở đường link cũng chỉ nhận "không tìm thấy".</p>
    </div>
    <ol class="cam">
      <li>Không chia sẻ, bán lại hay đăng tải file thiết kế của bạn (STL, 3MF, file gốc) cho bất kỳ bên thứ ba nào.</li>
      <li>Không in và không bán sản phẩm từ thiết kế riêng của bạn cho người khác.</li>
      <li>Không dùng hình ảnh sản phẩm của bạn để quảng cáo khi chưa được đồng ý.</li>
      <li>Ảnh và thông tin bạn gửi chỉ dùng để sản xuất đúng đơn hàng đó.</li>
      <li>File chỉ được lưu để in lại cho chính bạn. Cam kết giữ nguyên sau khi ngừng hợp tác.</li>
    </ol>
  </div>
</section>

<section>
  <div class="wrap">
    <div class="cta">
      <div><h2>Sẵn sàng nhận đơn?</h2><p>Đăng ký trong một phút. Xưởng duyệt và báo qua Zalo 0856 911 698.</p></div>
      <div style="display:flex;gap:12px;flex-wrap:wrap"><a href="/dang-ky" class="nut nut-vien">Đăng ký làm seller</a><a href="https://zalo.me/0856911698" class="nut" style="color:#fff;border-color:rgba(255,255,255,.6)" rel="nofollow">Nhắn Zalo xưởng</a></div>
    </div>
  </div>
</section>
@endsection
