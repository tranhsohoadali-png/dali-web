<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Xác nhận đơn hàng {{ $order->code }}</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Helvetica Neue',Arial,sans-serif;background:#F2FDE8;color:#1A4D00;padding:30px 20px}
.wrap{max-width:580px;margin:0 auto;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(58,122,10,.1)}
.header{background:linear-gradient(135deg,#1C5200,#3A9A12);padding:28px 32px;text-align:center}
.logo-text{font-size:32px;font-weight:900;letter-spacing:4px;color:#fff}
.tagline{font-size:11px;color:rgba(255,255,255,.6);letter-spacing:2px;margin-top:4px}
.hero-band{height:4px;background:linear-gradient(90deg,#3A9A12,#6BBF1F,#C6F135,#FF8FB1,#A78BFA)}
.body{padding:28px 32px}
.greeting{font-size:22px;font-weight:800;color:#1C3A0A;margin-bottom:8px}
.subtitle{font-size:15px;color:#4A8A1A;margin-bottom:24px;line-height:1.6}
.order-box{background:#F4FDE8;border-radius:12px;padding:20px;border:1.5px solid #C8E89A;margin-bottom:20px}
.order-code{font-size:24px;font-weight:900;color:#6BBF1F;margin-bottom:4px}
.order-date{font-size:12px;color:#8FC860}
.divider{height:1px;background:linear-gradient(90deg,transparent,#C8E89A 30%,#C8E89A 70%,transparent);margin:16px 0}
.info-row{display:flex;justify-content:space-between;padding:6px 0;font-size:14px}
.info-label{color:#8FC860;font-weight:500}
.info-value{color:#1C3A0A;font-weight:700;text-align:right;max-width:60%}
.items-title{font-size:13px;font-weight:800;letter-spacing:1.5px;color:#8FC860;text-transform:uppercase;margin:20px 0 12px}
.item-row{display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #E8F9D0}
.item-name{font-size:14px;font-weight:700;color:#1C3A0A}
.item-size{font-size:11px;color:#8FC860}
.item-price{font-size:14px;font-weight:900;color:#6BBF1F}
.total-box{background:#E8F9D0;border-radius:10px;padding:14px 18px;margin:16px 0;display:flex;justify-content:space-between;align-items:center}
.total-label{font-size:14px;font-weight:800;color:#1C3A0A}
.total-val{font-size:22px;font-weight:900;color:#6BBF1F}
.status-timeline{background:#fff;border:1.5px solid #C8E89A;border-radius:12px;padding:16px 18px;margin-top:20px}
.step{display:flex;gap:10px;align-items:flex-start;margin-bottom:10px}
.step:last-child{margin-bottom:0}
.step-dot{width:22px;height:22px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;flex-shrink:0;background:#6BBF1F;color:#fff}
.step-dot.inactive{background:#E8F9D0;color:#8FC860}
.step-label{font-size:13px;font-weight:600;color:#1C3A0A}
.step-sub{font-size:11px;color:#8FC860}
.cta{text-align:center;margin:28px 0 10px}
.btn{display:inline-block;background:linear-gradient(135deg,#3A9A12,#6BBF1F);color:#fff;text-decoration:none;padding:13px 32px;border-radius:50px;font-size:15px;font-weight:800}
.footer{background:#F4FDE8;padding:20px 32px;text-align:center;border-top:1px solid #E8F9D0}
.footer-text{font-size:12px;color:#8FC860;line-height:1.8}
.sakura{font-size:18px;margin-bottom:8px}
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <div class="logo-text">DALI</div>
    <div class="tagline">TÔ ĐIỂM CUỘC SỐNG</div>
  </div>
  <div class="hero-band"></div>
  <div class="body">
    <div class="greeting">🎉 Đặt hàng thành công!</div>
    <div class="subtitle">Xin chào <strong>{{ $order->customer_name }}</strong>,<br>Cảm ơn bạn đã tin tưởng DALI. Đơn hàng của bạn đã được tiếp nhận!</div>

    <div class="order-box">
      <div class="order-code">{{ $order->code }}</div>
      <div class="order-date">{{ $order->created_at->format('H:i · d/m/Y') }}</div>
      <div class="divider"></div>
      <div class="info-row"><span class="info-label">Giao đến</span><span class="info-value">{{ $order->customer_address }}, {{ $order->customer_city }}</span></div>
      <div class="info-row"><span class="info-label">Số điện thoại</span><span class="info-value">{{ $order->customer_phone }}</span></div>
      <div class="info-row"><span class="info-label">Thanh toán</span><span class="info-value">{{ $order->payment_label }}</span></div>
      @if($order->coupon_code)<div class="info-row"><span class="info-label">Mã giảm giá</span><span class="info-value" style="color:#6BBF1F">{{ $order->coupon_code }}</span></div>@endif
    </div>

    <div class="items-title">🎨 Sản phẩm đã đặt</div>
    @foreach($order->items as $item)
    <div class="item-row">
      <div><div class="item-name">{{ $item->product_name }}</div><div class="item-size">{{ $item->product_size }} · x{{ $item->quantity }}</div></div>
      <div class="item-price">{{ number_format($item->subtotal,0,',','.')}}đ</div>
    </div>
    @endforeach

    <div class="total-box">
      <div>
        <div class="total-label">Tổng thanh toán</div>
        @if($order->ship_fee == 0)<div style="font-size:12px;color:#6BBF1F">🚚 Miễn phí vận chuyển</div>@endif
        @if($order->discount > 0)<div style="font-size:12px;color:#6BBF1F">✓ Đã giảm {{ number_format($order->discount,0,',','.')}}đ</div>@endif
      </div>
      <div class="total-val">{{ number_format($order->total,0,',','.')}}đ</div>
    </div>

    <div class="status-timeline">
      <div style="font-size:12px;font-weight:800;letter-spacing:1.5px;color:#8FC860;text-transform:uppercase;margin-bottom:12px">Trạng thái đơn hàng</div>
      <div class="step"><div class="step-dot">✓</div><div><div class="step-label">Đơn hàng đã tiếp nhận</div><div class="step-sub">Ngay bây giờ</div></div></div>
      <div class="step"><div class="step-dot inactive">2</div><div><div class="step-label">Xác nhận & đóng gói</div><div class="step-sub">Trong 30 phút – 1 giờ</div></div></div>
      <div class="step"><div class="step-dot inactive">3</div><div><div class="step-label">Giao cho đơn vị vận chuyển</div><div class="step-sub">Trong ngày</div></div></div>
      <div class="step"><div class="step-dot inactive">4</div><div><div class="step-label">Giao đến tay bạn</div><div class="step-sub">2–3 ngày làm việc</div></div></div>
    </div>

    <div class="cta">
      <a href="{{ route('track-order') }}?code={{ $order->code }}" class="btn">🔍 Theo dõi đơn hàng</a>
    </div>
  </div>
  <div class="footer">
    <div class="sakura">🌸 ✿ 🍃</div>
    <div class="footer-text">
      Có thắc mắc? Liên hệ chúng tôi qua hotline hoặc Zalo<br>
      © 2024 DALI Tranh Tô Màu Số Hóa · Tô điểm cuộc sống
    </div>
  </div>
</div>
</body>
</html>
