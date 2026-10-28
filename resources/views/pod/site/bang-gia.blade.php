@extends('pod.site._khung', ['tab' => 'gia'])
@section('tieu_de', 'Bảng giá in 3D cho seller POD')
@section('mo_ta', 'Giá in 3D nhựa PLA ' . number_format($gia, 0, ',', '.') . 'đ/gram, tính cả support và nhựa xả. Phí thiết kế mẫu riêng cho seller, hoàn phí khi mẫu đạt 20 đơn in.')
@section('css')
.dau-trang{padding:56px 0 24px}
.gia-to{background:linear-gradient(140deg,var(--gd),var(--g));color:#fff;border-radius:24px;padding:32px}
.gia-to .so{font-size:64px;font-weight:800;line-height:1}
.gia-to p{color:#E3F5CF;font-size:15px;margin-top:14px}
.hop{background:#FFF7E6;border:1px solid #F1D084;border-radius:14px;padding:16px 18px;color:#7A4B00;font-size:15px}
@endsection

@section('noi_dung')
<div class="wrap dau-trang">
  <span class="nhan">Bảng giá</span>
  <h1 style="font-size:clamp(30px,4.4vw,44px)">Giá in tính theo gram, không phí ẩn</h1>
  <p class="phu" style="margin-top:12px">Cập nhật 10/2026. Giá chưa gồm phí vận chuyển.</p>
</div>

<section style="padding-top:16px">
  <div class="wrap luoi l2" style="align-items:start">
    <div class="gia-to">
      <div style="font-weight:600;opacity:.9">Giá in nhựa PLA</div>
      <div class="so">{{ number_format($gia, 0, ',', '.') }}đ</div>
      <div style="font-weight:700;font-size:18px;margin-top:4px">mỗi gram</div>
      <p>Tính theo tổng số gram trên phần mềm cắt lớp, gồm cả support và nhựa xả khi đổi màu. Giá từng mẫu hiện ngay trong cổng seller khi bạn chọn phân loại.</p>
    </div>
    <div class="the" style="padding:8px 4px">
      <table class="bang">
        <thead><tr><th>Ví dụ tham khảo</th><th class="so">Khối lượng</th><th class="so">Giá in</th></tr></thead>
        <tbody>
          @foreach($viDu as $r)
          <tr><td>{{ $r['ten'] }}</td><td class="so" style="font-weight:500">~{{ $r['gram'] }} g</td><td class="so">{{ number_format($r['gia'], 0, ',', '.') }}đ</td></tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</section>

<section id="thiet-ke" style="background:#fff;border-top:1px solid var(--bd);border-bottom:1px solid var(--bd)">
  <div class="wrap">
    <span class="nhan">Phí thiết kế</span>
    <h2>Khi bạn chưa có file</h2>
    <p class="phu">Phí thiết kế được báo chính xác sau khi xưởng xem yêu cầu và ảnh mẫu.</p>
    <div class="luoi l2" style="margin-top:28px;align-items:start">
      <div class="the" style="padding:8px 4px">
        <div style="padding:14px 14px 4px"><h3>A. Cá nhân hoá theo từng đơn</h3><p style="color:var(--mu);font-size:14px">Mỗi đơn một nội dung riêng: tên, ảnh của khách cuối.</p></div>
        <table class="bang">
          <thead><tr><th>Hạng mục</th><th class="so">Phí / đơn</th></tr></thead>
          <tbody>
            <tr><td>Đổi tên, chữ, ngày trên mẫu có sẵn</td><td class="so">0 – 10.000đ</td></tr>
            <tr><td>Logo hoặc ảnh 2D thành 3D (biển, lithophane, cookie cutter, huy hiệu)</td><td class="so">25.000 – 75.000đ</td></tr>
            <tr><td>Tượng thú cưng / chibi từ ảnh</td><td class="so">150.000 – 300.000đ</td></tr>
          </tbody>
        </table>
      </div>
      <div class="the" style="padding:8px 4px">
        <div style="padding:14px 14px 4px"><h3>B. Thiết kế mẫu mới cho seller</h3><p style="color:var(--mu);font-size:14px">Phí một lần, dùng cho nhiều đơn, gồm 2 lần chỉnh sửa. Mẫu vào Kho riêng của bạn.</p></div>
        <table class="bang">
          <thead><tr><th>Hạng mục</th><th class="so">Phí trọn gói</th></tr></thead>
          <tbody>
            <tr><td>Đồ đơn giản (hộp, giá đỡ, phụ kiện)</td><td class="so">150.000 – 400.000đ</td></tr>
            <tr><td>Chi tiết lắp ghép theo kích thước, có in thử</td><td class="so">400.000 – 1.000.000đ</td></tr>
            <tr><td>Nhân vật / tượng chi tiết</td><td class="so">500.000 – 1.500.000đ</td></tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="hop" style="margin-top:20px"><b>Ưu đãi:</b> hoàn lại phí thiết kế khi mẫu đó đạt 20 đơn in. Tiền hoàn vào ví của bạn.</div>
  </div>
</section>

<section>
  <div class="wrap luoi l3">
    <div class="the"><h3>Thanh toán</h3><p style="color:var(--mu);font-size:15px">Nạp ví bằng chuyển khoản (mã QR trong cổng). Mỗi đơn trừ thẳng vào ví, đơn chưa đủ tiền chờ tới khi bạn nạp thêm.</p></div>
    <div class="the"><h3>Giao hàng</h3><p style="color:var(--mu);font-size:15px">Xưởng giao trong nước tới kho gom hoặc line bay bạn chọn. Có thể gửi kèm nhãn để dán từng kiện.</p></div>
    <div class="the"><h3>Huỷ đơn</h3><p style="color:var(--mu);font-size:15px">Huỷ được khi đơn chưa lên máy in, tiền hoàn ngay vào ví. Đơn đã lên máy in thì không huỷ được.</p></div>
  </div>
  <div class="wrap" style="margin-top:28px;display:flex;gap:12px;flex-wrap:wrap"><a href="/dang-ky" class="nut nut-chinh">Đăng ký làm seller</a><a href="/kho-mau" class="nut nut-vien">Xem kho mẫu</a></div>
</section>
@endsection
