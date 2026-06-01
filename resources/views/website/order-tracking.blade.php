<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Tra cứu đơn hàng | DALI</title>
<meta name="description" content="Tra cứu trạng thái đơn hàng DALI. Nhập mã đơn để xem chi tiết.">
<meta property="og:title" content="Tra cứu đơn hàng | DALI">
@if(!empty($settings['ga_id']))<script async src="https://www.googletagmanager.com/gtag/js?id={{ $settings['ga_id'] }}"></script><script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ $settings["ga_id"] }}');</script>@endif
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet">
<style>[class^="ri-"],[class*=" ri-"]{vertical-align:-.125em;font-style:normal;line-height:1}</style>
<style>
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--gn:#C6F135;--pk:#FF8FB1;--bd:#C8E89A;--bd2:#A8D870;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0}html{scroll-behavior:smooth}
body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:var(--tx);line-height:1.6}
nav{background:linear-gradient(175deg,#1C5200,#2D7A08,#3A9A12);height:68px;padding:0 5%;display:flex;align-items:center;justify-content:space-between}
.nav-logo{height:38px;object-fit:contain;display:block;filter:brightness(0) invert(1)}
.nav-links{display:flex;gap:28px;list-style:none}
.nav-links a{text-decoration:none;color:rgba(255,255,255,.75);font-size:14px;font-weight:500}
.nav-links a:hover{color:#fff}
.nav-right{display:flex;align-items:center;gap:12px}
.nav-phone{font-size:13px;font-weight:600;color:rgba(255,255,255,.85);text-decoration:none}
.btn-nav{background:var(--gn);color:var(--char);border:none;border-radius:50px;padding:9px 20px;font-size:13px;font-weight:800;cursor:pointer;text-decoration:none}
.nav-hamburger{display:none;flex-direction:column;gap:5px;cursor:pointer;background:none;border:none;padding:4px}
.nav-hamburger span{display:block;width:22px;height:2px;background:#fff;border-radius:2px}
.mobile-nav{display:none;position:fixed;top:68px;left:0;right:0;bottom:0;background:linear-gradient(175deg,#1C5200,#2D7A08);z-index:99;padding:28px 5%;flex-direction:column;gap:4px}
.mobile-nav.open{display:flex}
.mobile-nav a{font-size:17px;font-weight:600;color:rgba(255,255,255,.85);text-decoration:none;padding:13px 16px;border-bottom:1px solid rgba(255,255,255,.1);border-radius:8px}
.sakura-strip{background:linear-gradient(90deg,#fff8fa,#f6ffe8,#fff,#f6ffe8,#fff8fa);border-bottom:1px solid #F0EBF8;padding:7px 5%;display:flex;align-items:center;gap:6px}
.petal{font-size:15px;animation:drift 5s ease-in-out infinite;display:inline-block}
.petal:nth-child(2){animation-delay:1s}.petal:nth-child(3){animation-delay:2s}
@keyframes drift{0%,100%{transform:translateY(0)}50%{transform:translateY(-4px)}}
.sak-text{font-size:10px;color:#B8D8A0;letter-spacing:2.5px;font-weight:700;margin-left:8px}
.page-hero{background:linear-gradient(175deg,#1C5200,#2D7A08);padding:44px 5% 36px;color:#fff;text-align:center}
.page-hero h1{font-size:clamp(22px,3vw,34px);font-weight:900;margin-bottom:8px}
.page-hero p{font-size:14px;opacity:.75}
.search-section{padding:32px 5%;max-width:600px;margin:0 auto}
.search-card{background:#fff;border-radius:18px;border:1.5px solid var(--bd);padding:28px;box-shadow:0 4px 24px rgba(58,122,10,.08)}
.search-title{font-size:18px;font-weight:900;color:var(--char);margin-bottom:6px}
.search-sub{font-size:13px;color:var(--tx3);margin-bottom:20px}
.search-form{display:flex;gap:10px}
.search-input{flex:1;background:var(--gll);border:1.5px solid var(--bd);border-radius:10px;padding:12px 16px;font-size:14px;font-family:'Be Vietnam Pro',sans-serif;color:var(--tx);outline:none;transition:all .2s}
.search-input:focus{border-color:var(--g);background:#fff}
.search-btn{background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:10px;padding:12px 22px;font-size:14px;font-weight:800;cursor:pointer;white-space:nowrap;transition:all .2s}
.search-btn:hover{background:linear-gradient(135deg,#2E7D08,#5AAF15);transform:translateY(-1px)}
.result-section{max-width:700px;margin:0 auto;padding:0 5% 60px}
.order-card{background:#fff;border-radius:18px;border:1.5px solid var(--bd);overflow:hidden;box-shadow:0 4px 24px rgba(58,122,10,.08)}
.order-card-top{height:4px;background:linear-gradient(90deg,#3A9A12,var(--g),var(--gn),#FF8FB1,#A78BFA)}
.order-head{padding:20px 24px;border-bottom:1px solid var(--gl);display:flex;align-items:flex-start;justify-content:space-between;background:var(--gll)}
.order-code{font-size:22px;font-weight:900;color:var(--char)}
.order-date{font-size:12px;color:var(--tx3);margin-top:3px}
.status-badge{display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:20px;font-size:13px;font-weight:800;border:1.5px solid currentColor}
.order-body{padding:20px 24px}
.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:20px}
.info-item{background:var(--gll);border-radius:10px;padding:12px 14px;border:1px solid var(--bd)}
.info-label{font-size:10px;font-weight:800;letter-spacing:1.5px;color:var(--tx3);text-transform:uppercase;margin-bottom:4px}
.info-value{font-size:14px;font-weight:700;color:var(--char)}
.items-title{font-size:14px;font-weight:800;color:var(--char);margin-bottom:12px}
.item-row{display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--gl)}
.item-row:last-child{border-bottom:none}
.item-img{width:48px;height:48px;object-fit:cover;border-radius:8px;border:1px solid var(--bd);background:var(--gl)}
.item-name{font-size:13px;font-weight:700;color:var(--char);flex:1}
.item-size{font-size:11px;color:var(--tx3)}
.item-price{font-size:14px;font-weight:900;color:var(--g)}
.total-row{display:flex;justify-content:space-between;align-items:center;padding:14px 0;border-top:1.5px solid var(--bd);margin-top:4px}
.total-label{font-size:15px;font-weight:800;color:var(--char)}
.total-val{font-size:22px;font-weight:900;color:var(--g)}
/* Status timeline */
.timeline{margin:20px 0;padding:18px;background:var(--gll);border-radius:12px;border:1px solid var(--bd)}
.timeline-title{font-size:12px;font-weight:800;letter-spacing:1.5px;color:var(--tx3);text-transform:uppercase;margin-bottom:14px}
.timeline-step{display:flex;gap:12px;align-items:flex-start;margin-bottom:10px}
.timeline-step:last-child{margin-bottom:0}
.t-dot{width:20px;height:20px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:10px;margin-top:1px}
.t-dot.done{background:var(--g);color:#fff}
.t-dot.active{background:var(--gn);color:var(--char)}
.t-dot.pending{background:var(--bd);color:var(--tx3)}
.t-label{font-size:13px;font-weight:600}
.t-sub{font-size:11px;color:var(--tx3);margin-top:1px}
.not-found{text-align:center;padding:40px 20px}
.not-found .icon{font-size:52px;margin-bottom:14px}
.not-found h3{font-size:18px;font-weight:800;color:var(--char);margin-bottom:8px}
.not-found p{font-size:14px;color:var(--tx3)}
.btn-primary{background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:50px;padding:12px 26px;font-size:14px;font-weight:700;cursor:pointer;text-decoration:none;display:inline-block;margin-top:14px;transition:all .2s}
footer{background:linear-gradient(175deg,#0F2E00,#1C5200);color:rgba(255,255,255,.7);padding:40px 5% 24px;margin-top:0}
.footer-bottom{border-top:1px solid rgba(255,255,255,.08);padding-top:18px;display:flex;justify-content:space-between;flex-wrap:wrap;gap:10px;font-size:12px;color:rgba(255,255,255,.3)}
.footer-links{display:flex;gap:20px}
.footer-links a{color:rgba(255,255,255,.5);text-decoration:none;font-size:13px}
.footer-links a:hover{color:var(--gn)}
@media(max-width:600px){.info-grid{grid-template-columns:1fr}.search-form{flex-direction:column}.footer-bottom{flex-direction:column;text-align:center}.nav-phone{display:none}.nav-links{display:none}.nav-hamburger{display:flex}nav{padding:0 4%}}
</style>
</head>
<body>
<nav>
  <a href="{{ route('home') }}"><img src="{{ asset('images/logo_dali.png') }}" alt="DALI" class="nav-logo"></a>
  <ul class="nav-links">
    <li><a href="{{ route('home') }}">Trang chủ</a></li>
    <li><a href="{{ route('products') }}">Sản phẩm</a></li>
  </ul>
  <div class="nav-right">
    <a href="tel:{{ $settings['shop_phone'] ?? '0123456789' }}" class="nav-phone"><i class="ri-phone-line" style="margin-right:5px"></i>{{ $settings['shop_phone'] ?? '0123456789' }}</a>
    <a href="{{ route('products') }}" class="btn-nav">Mua sắm</a>
    <button class="nav-hamburger" onclick="document.getElementById('mnav').classList.toggle('open')"><span></span><span></span><span></span></button>
  </div>
</nav>
<div class="mobile-nav" id="mnav">
  <a href="{{ route('home') }}"><i class="ri-home-5-line"></i> Trang chủ</a>
  <a href="{{ route('products') }}"><i class="ri-palette-line"></i> Sản phẩm</a>
  <a href="{{ route('blog') }}"><i class="ri-quill-pen-line"></i> Blog</a>
  <a href="{{ route('cart') }}"><i class="ri-shopping-cart-2-line"></i> Giỏ hàng</a>
  <a href="{{ route('track-order') }}"><i class="ri-search-line"></i> Tra cứu đơn</a>
</div>
<div class="sakura-strip"><span class="petal"><i class="ri-flower-line"></i></span><span class="petal"><i class="ri-flower-line"></i></span><span class="petal"><i class="ri-leaf-line"></i></span><span class="sak-text">DALI · TÔ ĐIỂM CUỘC SỐNG</span></div>

<div class="page-hero">
  <h1><i class="ri-search-line"></i> Tra cứu đơn hàng</h1>
  <p>Nhập mã đơn hoặc số điện thoại để xem trạng thái giao hàng</p>
</div>

<div class="search-section">
  <div class="search-card">
    <div class="search-title">Tra cứu đơn hàng</div>
    <div class="search-sub">Nhập <b>mã đơn</b> (DALI-XXXXXX) hoặc <b>số điện thoại</b> đặt hàng</div>
    <form method="GET" action="{{ route('track-order') }}" class="search-form">
      <input type="text" name="code" class="search-input" placeholder="DALI-847291 hoặc 0901234567" value="{{ request('code') }}" autocomplete="off">
      <button type="submit" class="search-btn">Tra cứu</button>
    </form>
    @if(request('code') && !$order && !isset($orders))
    <div style="margin-top:14px;background:#FFF0F0;border-left:3px solid #EF4444;border-radius:8px;padding:11px 14px;font-size:13px;color:#991B1B;font-weight:600">
      <i class="ri-close-circle-line"></i> Không tìm thấy đơn hàng với <b>{{ request('code') }}</b>. Vui lòng kiểm tra lại mã hoặc SĐT.
    </div>
    @endif
  </div>
</div>

{{-- DANH SÁCH ĐƠN KHI TÌM BẰNG SĐT (nhiều đơn) --}}
@isset($orders)
@if($orders && $orders->count() > 0)
<div class="result-section">
  <div style="font-size:14px;font-weight:700;color:var(--tx2);margin-bottom:14px">
    <i class="ri-clipboard-line"></i> Tìm thấy <b>{{ $orders->count() }}</b> đơn hàng với số điện thoại <b>{{ request('code') }}</b>:
  </div>
  @foreach($orders as $o)
  <div class="order-card" style="margin-bottom:16px;cursor:pointer" onclick="window.location='{{ route('track-order') }}?code={{ $o->code }}'">
    <div class="order-card-top"></div>
    <div class="order-head" style="display:flex;align-items:center;justify-content:space-between">
      <div>
        <div class="order-code">{{ $o->code }}</div>
        <div class="order-date">{{ $o->created_at->format('d/m/Y H:i') }} · {{ $o->items->count() }} sản phẩm</div>
      </div>
      <div style="text-align:right">
        <div style="font-size:18px;font-weight:900;color:var(--g)">{{ number_format($o->total,0,',','.') }}đ</div>
        <span class="status-badge" style="color:{{ $o->status_color }};border-color:{{ $o->status_color }}">{{ $o->status_label }}</span>
      </div>
    </div>
    <div style="padding:10px 20px 14px;font-size:13px;color:var(--tx2)">
      @foreach($o->items as $item)
        <div style="padding:4px 0">· {{ $item->product_name }} @if($item->product_size)<span style="color:var(--tx3)">({{ $item->product_size }})</span>@endif × {{ $item->quantity }}</div>
      @endforeach
      <div style="margin-top:6px;font-size:12px;color:var(--g);font-weight:700">Bấm để xem chi tiết →</div>
    </div>
  </div>
  @endforeach
</div>
@endif
@endisset

@if($order)
<div class="result-section">
  <div class="order-card">
    <div class="order-card-top"></div>
    <div class="order-head">
      <div>
        <div class="order-code">{{ $order->code }}</div>
        <div class="order-date">Đặt lúc: {{ $order->created_at->format('H:i · d/m/Y') }}</div>
      </div>
      <div>
        <div class="status-badge" style="color:{{ $order->status_color }};border-color:{{ $order->status_color }};background:{{ $order->status_color }}15">
          {{ $order->status_label }}
        </div>
        @if($order->payment_status === 'paid')
        <div style="margin-top:6px;font-size:11px;font-weight:700;color:var(--g);text-align:right"><i class="ri-checkbox-circle-line"></i> Đã thanh toán</div>
        @elseif($order->payment_method === 'BANK')
        <div style="margin-top:6px;font-size:11px;font-weight:700;color:#D97706;text-align:right"><i class="ri-bank-card-line"></i> Chờ xác nhận TT</div>
        @endif
      </div>
    </div>

    <div class="order-body">
      {{-- Timeline --}}
      <div class="timeline">
        <div class="timeline-title">Trạng thái đơn hàng</div>
        @php
          $steps = [
            'new'       => ['Đơn mới', 'Đơn hàng đã được tiếp nhận'],
            'confirmed' => ['Đã xác nhận', 'Shop đã xác nhận đơn hàng'],
            'packing'   => ['Đang đóng gói', 'Đang chuẩn bị sản phẩm'],
            'shipping'  => ['Đang giao hàng', 'Sản phẩm đang trên đường đến bạn'],
            'delivered' => ['Đã giao hàng', 'Bạn đã nhận được hàng'],
          ];
          $statusOrder = ['new','confirmed','packing','shipping','delivered'];
          $currentIdx  = array_search($order->status, $statusOrder);
        @endphp
        @foreach($steps as $key => [$label, $sub])
          @php $idx = array_search($key, $statusOrder); @endphp
          <div class="timeline-step">
            <div class="t-dot {{ $idx < $currentIdx ? 'done' : ($idx == $currentIdx ? 'active' : 'pending') }}">
              {{ $idx < $currentIdx ? '✓' : ($idx == $currentIdx ? '●' : '○') }}
            </div>
            <div>
              <div class="t-label" style="color:{{ $idx <= $currentIdx ? 'var(--char)' : 'var(--tx3)' }}">{{ $label }}</div>
              <div class="t-sub">{{ $sub }}</div>
            </div>
          </div>
        @endforeach
        @if($order->status === 'cancelled')
        <div class="timeline-step">
          <div class="t-dot" style="background:#EF4444;color:#fff">✕</div>
          <div><div class="t-label" style="color:#EF4444">Đã huỷ</div><div class="t-sub">Đơn hàng đã bị huỷ</div></div>
        </div>
        @endif
      </div>

      {{-- Info grid --}}
      <div class="info-grid">
        <div class="info-item">
          <div class="info-label">Khách hàng</div>
          <div class="info-value">{{ $order->customer_name }}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Số điện thoại</div>
          <div class="info-value">{{ $order->customer_phone }}</div>
        </div>
        <div class="info-item" style="grid-column:1/-1">
          <div class="info-label">Địa chỉ giao hàng</div>
          <div class="info-value">{{ $order->customer_address }}, {{ $order->customer_city }}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Thanh toán</div>
          <div class="info-value">{{ $order->payment_label }}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Trạng thái TT</div>
          <div class="info-value">{{ $order->payment_status_label }}</div>
        </div>
        @if($order->vtp_order_number)
        <div class="info-item">
          <div class="info-label">Mã vận đơn (VTP)</div>
          <div class="info-value" style="color:var(--g);user-select:all">{{ $order->vtp_order_number }}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Hành trình ViettelPost</div>
          <div class="info-value">{{ $order->vtp_status_name ?? 'Đã tạo vận đơn' }}</div>
        </div>
        @endif
      </div>

      {{-- Order items --}}
      <div class="items-title"><i class="ri-palette-line"></i> Sản phẩm đã đặt</div>
      @foreach($order->items as $item)
      <div class="item-row">
        <div class="item-img">
          @if($item->product && $item->product->main_image)
            <img src="{{ asset('storage/'.$item->product->main_image) }}" alt="{{ $item->product_name }}" style="width:100%;height:100%;object-fit:cover;border-radius:8px">
          @else
            <div style="width:48px;height:48px;display:flex;align-items:center;justify-content:center;font-size:20px"><i class="ri-palette-line"></i></div>
          @endif
        </div>
        <div style="flex:1">
          <div class="item-name">{{ $item->product_name }}</div>
          @if($item->product_size)<div class="item-size">{{ $item->product_size }}</div>@endif
        </div>
        <div style="text-align:right">
          <div class="item-price">{{ number_format($item->price,0,',','.') }}đ</div>
          <div style="font-size:11px;color:var(--tx3)">x{{ $item->quantity }}</div>
        </div>
      </div>
      @endforeach

      {{-- Total --}}
      <div class="total-row">
        <div>
          <div class="total-label">Tổng thanh toán</div>
          @if($order->discount > 0)
          <div style="font-size:12px;color:var(--g);font-weight:600">✓ Đã giảm {{ number_format($order->discount,0,',','.') }}đ (5% CK)</div>
          @endif
          @if($order->ship_fee > 0)
          <div style="font-size:12px;color:var(--tx3)">Phí ship: {{ number_format($order->ship_fee,0,',','.') }}đ</div>
          @else
          <div style="font-size:12px;color:var(--g)"><i class="ri-truck-line"></i> Miễn phí vận chuyển</div>
          @endif
        </div>
        <div class="total-val">{{ number_format($order->total,0,',','.') }}đ</div>
      </div>

      @if($order->note)
      <div style="background:var(--gll);border-radius:9px;padding:11px 14px;border:1px solid var(--bd);font-size:13px;color:var(--tx2);margin-top:12px">
        <b>Ghi chú:</b> {{ $order->note }}
      </div>
      @endif

      <div style="text-align:center;margin-top:20px">
        <a href="{{ route('products') }}" class="btn-primary">Tiếp tục mua sắm →</a>
      </div>
    </div>
  </div>
</div>
@endif

<footer>
  <div class="footer-bottom">
    <span>© 2024 DALI Tranh Tô Màu Số Hóa</span>
    <div class="footer-links">
      <a href="{{ route('home') }}">Trang chủ</a>
      <a href="{{ route('products') }}">Sản phẩm</a>
      <a href="{{ route('track-order') }}">Tra cứu đơn</a>
    </div>
  </div>
</footer>
@include('partials.float-widget')
@include('partials.bottom-nav')
</body>
</html>