<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $category->name }} | DALI – Chọn mã tranh</title>
<meta name="description" content="Bộ sưu tập tranh tô màu số hóa chủ đề {{ $category->name }} – chọn mã tranh yêu thích, nhiều kích thước.">
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet">
<style>[class^="ri-"],[class*=" ri-"]{vertical-align:-.125em;font-style:normal;line-height:1}</style>
<style>
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--gn:#C6F135;--pk:#FF8FB1;--bd:#C8E89A;--bd2:#A8D870;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0}html{scroll-behavior:smooth}
body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:var(--tx);line-height:1.6}
nav{position:sticky;top:0;z-index:100;background:linear-gradient(175deg,#1C5200,#2D7A08,#3A9A12);height:68px;padding:0 5%;display:flex;align-items:center;justify-content:space-between}
.nav-logo{height:38px;object-fit:contain;display:block;filter:brightness(0) invert(1)}
.nav-links{display:flex;gap:28px;list-style:none}
.nav-links a{text-decoration:none;color:rgba(255,255,255,.78);font-size:14px;font-weight:500}
.nav-links a:hover{color:#fff}
.nav-right{display:flex;align-items:center;gap:12px}
.nav-phone{font-size:13px;font-weight:600;color:rgba(255,255,255,.85);text-decoration:none}
.btn-nav{background:var(--gn);color:var(--char);border:none;border-radius:50px;padding:9px 20px;font-size:13px;font-weight:800;text-decoration:none}
.nav-hamburger{display:none;flex-direction:column;gap:5px;cursor:pointer;background:none;border:none}
.nav-hamburger span{display:block;width:22px;height:2px;background:#fff}
.mobile-nav{display:none;position:fixed;top:68px;left:0;right:0;bottom:0;background:linear-gradient(175deg,#1C5200,#2D7A08);z-index:99;padding:28px 5%;flex-direction:column;gap:4px}
.mobile-nav.open{display:flex}
.mobile-nav a{font-size:17px;font-weight:600;color:rgba(255,255,255,.85);text-decoration:none;padding:13px 16px;border-bottom:1px solid rgba(255,255,255,.1);border-radius:8px}
.breadcrumb{padding:13px 5%;font-size:13px;color:var(--tx3);display:flex;gap:7px;flex-wrap:wrap}
.breadcrumb a{color:var(--tx2);text-decoration:none}
.wrap{max-width:1120px;margin:0 auto;padding:8px 5% 50px;display:grid;grid-template-columns:440px 1fr;gap:30px;align-items:start}
/* LEFT preview */
.preview-box{position:sticky;top:84px}
.preview-main{width:100%;aspect-ratio:1/1;border-radius:16px;overflow:hidden;border:1.5px solid var(--bd);background:linear-gradient(135deg,var(--gll),#fff);display:flex;align-items:center;justify-content:center}
.preview-main img{width:100%;height:100%;object-fit:contain}
.preview-cap{margin-top:10px;text-align:center;font-size:13px;color:var(--tx2);font-weight:600}
.share-row{display:flex;align-items:center;gap:10px;margin-top:14px;justify-content:center;font-size:13px;color:var(--tx3)}
/* RIGHT */
.c-title{font-size:22px;font-weight:900;color:var(--char);line-height:1.3;margin-bottom:10px}
.c-meta{display:flex;align-items:center;gap:14px;font-size:13px;color:var(--tx2);margin-bottom:16px;flex-wrap:wrap}
.c-meta b{color:var(--g)}
.c-stars{color:#FFC107}
.price-box{background:var(--gll);border:1.5px solid var(--bd);border-radius:12px;padding:14px 18px;margin-bottom:18px}
.price-main{font-size:30px;font-weight:900;color:var(--g)}
.price-note{font-size:12px;color:var(--gd);margin-top:4px}
.sec-label{font-size:13px;font-weight:700;color:var(--tx);margin-bottom:9px;display:flex;align-items:center;justify-content:space-between}
.sec-label .hint{font-size:11px;color:var(--tx3);font-weight:500}
/* Mã tranh grid */
.code-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(132px,1fr));gap:9px;max-height:340px;overflow-y:auto;padding:3px;margin-bottom:20px;border:1px solid var(--gl);border-radius:12px;padding:10px}
.code-card{display:flex;align-items:center;gap:8px;border:1.5px solid var(--bd);border-radius:10px;padding:6px;cursor:pointer;background:#fff;transition:all .15s;text-align:left}
.code-card:hover{border-color:var(--g)}
.code-card.active{border-color:var(--g);background:var(--gl);box-shadow:0 0 0 1px var(--g) inset}
.code-card img{width:42px;height:42px;border-radius:7px;object-fit:cover;flex-shrink:0;background:var(--gl)}
.code-card .cc-code{font-size:12px;font-weight:800;color:var(--char);line-height:1.2}
.code-card .cc-sub{font-size:10px;color:var(--tx3)}
/* sizes */
.size-row{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:20px}
.size-opt{padding:8px 13px;border:1.5px solid var(--bd);border-radius:9px;background:#fff;cursor:pointer;font-family:inherit;text-align:left;transition:all .15s}
.size-opt.active{border-color:var(--g);background:var(--gll)}
.size-opt .so-n{font-size:13px;font-weight:700;color:var(--char)}
.size-opt .so-p{font-size:12px;color:var(--g);font-weight:800}
.size-opt .so-note{font-size:9px;color:var(--pk);display:block}
/* qty + buttons */
.qty-row{display:flex;align-items:center;gap:14px;margin-bottom:20px}
.qty-ctrl{display:flex;align-items:center;border:1.5px solid var(--bd);border-radius:9px;overflow:hidden}
.qty-b{background:var(--gl);border:none;width:38px;height:42px;font-size:18px;cursor:pointer;font-weight:700;color:var(--gd)}
.qty-i{border:none;border-left:1.5px solid var(--bd);border-right:1.5px solid var(--bd);width:54px;height:42px;text-align:center;font-size:15px;font-weight:800;color:var(--char);outline:none;background:#fff;font-family:inherit}
.btn-row{display:flex;gap:12px}
.btn-cart{flex:1;background:#fff;color:var(--gd);border:1.5px solid var(--g);border-radius:12px;padding:14px;font-size:15px;font-weight:800;cursor:pointer;transition:all .2s}
.btn-cart:hover{background:var(--gl)}
.btn-buy{flex:1;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:12px;padding:14px;font-size:15px;font-weight:900;cursor:pointer;transition:all .2s;box-shadow:0 4px 16px rgba(107,191,31,.3)}
.btn-buy:hover{background:linear-gradient(135deg,#2E7D08,#5AAF15)}
.gift-banner{background:linear-gradient(90deg,#FFF7ED,#FEF3C7);border:1px dashed #F59E0B;border-radius:10px;padding:10px 14px;font-size:13px;color:#B45309;font-weight:600;margin-bottom:18px}
.toast{position:fixed;bottom:22px;left:50%;transform:translateX(-50%) translateY(100px);background:var(--char);color:#fff;padding:11px 22px;border-radius:50px;font-size:13px;font-weight:600;z-index:9999;transition:transform .4s;white-space:nowrap;max-width:90vw;text-align:center}
.toast.show{transform:translateX(-50%) translateY(0)}
.combo-bar{display:none}
@media(max-width:880px){
  .wrap{grid-template-columns:1fr;gap:14px;padding:8px 4% 40px}
  .preview-box{position:static}
  .nav-links{display:none}.nav-hamburger{display:flex}.nav-phone{display:none}nav{padding:0 4%}.breadcrumb{padding:11px 4%}
  /* Bố cục kiểu Shopee: ảnh → tên → chọn tranh → giá → chọn cỡ */
  .combo-content{display:flex;flex-direction:column}
  .c-title{order:-4;font-size:18px;line-height:1.3}
  .pick-block{order:-3}
  .price-box{order:-2}
  .size-block{order:-1}
  /* Hàng thumbnail mã tranh: cuộn ngang */
  .code-grid{display:flex;flex-wrap:nowrap;overflow-x:auto;max-height:none;gap:10px;-webkit-overflow-scrolling:touch}
  .code-card{flex:0 0 auto;min-width:128px}
  /* Thanh mua cố định đáy + ẩn thanh tab nav (gọn như trang SP Shopee) */
  .dali-bottombar{display:none!important}
  .btn-row{display:none}
  body{padding-bottom:80px!important}
  .dali-fab{bottom:calc(88px + env(safe-area-inset-bottom))!important}
  .combo-bar{display:flex;position:fixed;left:0;right:0;bottom:0;z-index:950;background:#fff;border-top:1px solid var(--bd);box-shadow:0 -3px 16px rgba(58,122,10,.12);padding:9px 12px;gap:9px;align-items:center;padding-bottom:calc(9px + env(safe-area-inset-bottom))}
  .cb-price{display:flex;flex-direction:column;line-height:1.1}
  .cb-price span{font-size:10px;color:var(--tx3)}
  .cb-price b{font-size:16px;color:var(--g);font-weight:900;white-space:nowrap}
  .cb-cart{flex:0 0 auto;background:#fff;color:var(--gd);border:1.5px solid var(--g);border-radius:10px;padding:11px 13px;font-size:13px;font-weight:800;white-space:nowrap;cursor:pointer}
  .cb-buy{flex:1;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:10px;padding:12px;font-size:15px;font-weight:900;box-shadow:0 4px 14px rgba(107,191,31,.3);cursor:pointer}
}
/* ── ĐÁNH GIÁ ── */
.reviews-wrap{max-width:1120px;margin:0 auto;padding:0 5% 50px}
.rv-head{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;background:#fff;border:1.5px solid var(--bd);border-radius:16px;padding:18px 22px;margin-bottom:16px}
.rv-title{font-size:18px;font-weight:900;color:var(--char)}
.rv-avg{display:flex;align-items:center;gap:12px}
.rv-avg-num{font-size:38px;font-weight:900;color:var(--g);line-height:1}
.rv-avg-stars{color:#FFC107;font-size:17px}
.rv-avg-count{font-size:12px;color:var(--tx3);margin-top:2px}
.rv-list{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:20px}
.rv-item{background:#fff;border:1.5px solid var(--bd);border-radius:14px;padding:16px}
.rv-item-top{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:8px}
.rv-author{display:flex;align-items:center;gap:10px}
.rv-avatar{width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--gl),#CCEF90);border:2px solid var(--bd2);display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:900;color:var(--gd);flex-shrink:0}
.rv-name{font-size:14px;font-weight:700;color:var(--char)}
.rv-date{font-size:11px;color:var(--tx3)}
.rv-prodcode{background:var(--gl);color:var(--gd);font-weight:700;padding:1px 7px;border-radius:20px;font-size:10px}
.rv-stars{color:#FFC107;font-size:15px}
.rv-badge{font-size:10px;font-weight:700;background:var(--gl);color:var(--gd);padding:2px 8px;border-radius:20px;margin-top:3px;display:inline-block}
.rv-content{font-size:13px;color:var(--tx2);line-height:1.7}
.rv-form{background:var(--gll);border-radius:14px;padding:22px;border:1.5px solid var(--bd)}
.rv-form-title{font-size:16px;font-weight:800;color:var(--char);margin-bottom:16px}
.rv-lbl{font-size:12px;font-weight:700;color:var(--tx);display:block;margin-bottom:6px}
.rv-inp{width:100%;background:#fff;border:1.5px solid var(--bd);border-radius:9px;padding:10px 13px;font-size:13px;font-family:'Be Vietnam Pro',sans-serif;color:var(--tx);outline:none}
.rv-inp:focus{border-color:var(--g)}
.star-picker{display:flex;gap:6px}
.star-btn{background:none;border:none;font-size:30px;color:#E0E0E0;cursor:pointer;padding:0;line-height:1;transition:color .15s}
.star-btn.active{color:#FFC107}
.rv-submit{width:100%;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:10px;padding:13px;font-size:14px;font-weight:800;cursor:pointer;margin-top:6px}
.rv-submit:hover{background:linear-gradient(135deg,#2E7D08,#5AAF15)}
.rv-submit:disabled{background:#C8E89A;cursor:not-allowed}
.rv-success{background:var(--gl);border-radius:10px;padding:14px;text-align:center;font-size:14px;font-weight:600;color:var(--gd);display:none;margin-bottom:14px}
@media(max-width:700px){.rv-list{grid-template-columns:1fr}.rv-avg-num{font-size:30px}}
</style>
</head>
<body>
<nav>
  <a href="{{ route('home') }}"><img src="{{ asset('images/logo_dali.png') }}" alt="DALI" class="nav-logo"></a>
  <ul class="nav-links">
    <li><a href="{{ route('home') }}">Trang chủ</a></li>
    <li><a href="{{ route('products') }}">Sản phẩm</a></li>
    <li><a href="{{ route('blog') }}">Blog</a></li>
  </ul>
  <div class="nav-right">
    <a href="tel:{{ $settings['shop_phone'] ?? '0856911698' }}" class="nav-phone"><i class="ri-phone-line" style="margin-right:5px"></i>{{ $settings['shop_phone'] ?? '0856.911.698' }}</a>
    <a href="{{ route('products') }}" class="btn-nav">Tất cả tranh</a>
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

<div class="breadcrumb">
  <a href="{{ route('home') }}">Trang chủ</a> › <a href="{{ route('products') }}">Sản phẩm</a> › <span style="color:var(--tx)">{{ $category->name }}</span>
</div>

@php
  // chuẩn bị dữ liệu sản phẩm cho JS
  $jsProducts = $products->map(function($p){
      $code = preg_match('/([A-Za-z]+\d+)\s*$/u', $p->name, $m) ? $m[1] : '';
      return [
          'id'    => $p->id,
          'name'  => $p->name,
          'code'  => $code,
          'image' => $p->main_image ? asset('storage/'.$p->main_image) : '',
          'sizeIds' => $p->size_ids ?: [],
      ];
  })->values();
  $jsSizes = $sizes->map(fn($s)=>['id'=>$s->id,'name'=>$s->name,'note'=>$s->note,'price'=>$s->price])->values();
  $minPrice = $sizes->min('price'); $maxPrice = $sizes->max('price');
@endphp

<div class="wrap">
  <!-- LEFT: preview -->
  <div class="preview-box">
    <div class="preview-main">
      <img id="previewImg" src="" alt="{{ $category->name }}">
    </div>
    <div class="preview-cap" id="previewCap">—</div>
    <div class="share-row"><i class="ri-palette-line"></i> {{ $products->count() }} mẫu tranh · <i class="ri-box-3-line"></i> Đã căng khung sẵn</div>
  </div>

  <!-- RIGHT: chọn -->
  <div class="combo-content">
    <h1 class="c-title">{{ $category->icon }} Tổng hợp tranh tô màu số hóa chủ đề {{ $category->name }}</h1>
    <div class="c-meta">
      @php
        $stars = '';
        $full = floor($avgRating);
        for($s=1;$s<=5;$s++) $stars .= $s<=$full ? '★' : ($avgRating-$s>-0.5?'★':'☆');
      @endphp
      <span class="c-stars">{{ $stars }} {{ number_format($avgRating,1) }}</span>
      @if($reviewCount > 0)
        <span style="color:var(--tx3);font-size:13px">({{ $reviewCount }} đánh giá)</span>
      @endif
      <span>{{ $products->count() }} mẫu tranh</span>
      <span>Khổ chuẩn 40×50cm · đã căng khung</span>
    </div>

    <div class="price-box">
      <div class="price-main" id="priceMain">{{ number_format($minPrice,0,',','.') }}đ</div>
      <div class="price-note"><i class="ri-bank-card-line"></i> Chuyển khoản QR giảm thêm {{ (int)($settings['discount_bank'] ?? 5) }}% · <i class="ri-truck-line"></i> Miễn phí ship từ 299K</div>
    </div>

    <div class="gift-banner"><i class="ri-gift-line"></i> MUA 3 TRANH TẶNG 1 TRANH cùng khổ — áp dụng mọi mẫu trong chủ đề!</div>

    <div class="pick-block">
    <div class="sec-label"><i class="ri-image-line"></i> Chọn mã tranh <span class="hint">bấm để xem & chọn — {{ $products->count() }} mẫu</span></div>
    <div class="code-grid" id="codeGrid">
      @foreach($products as $i => $p)
      @php $code = preg_match('/([A-Za-z]+\d+)\s*$/u', $p->name, $m) ? $m[1] : ''; @endphp
      <div class="code-card" data-id="{{ $p->id }}" onclick="selectPainting({{ $p->id }})">
        <img src="{{ $p->main_image ? asset('storage/'.$p->main_image) : '' }}" alt="{{ $code }}" loading="lazy">
        <div>
          <div class="cc-code">{{ $code ?: 'Mẫu '.($i+1) }}</div>
          <div class="cc-sub">sẵn khung</div>
        </div>
      </div>
      @endforeach
    </div>
    </div>

    <div class="size-block">
    <div class="sec-label"><i class="ri-ruler-2-line"></i> Chọn kích thước</div>
    <div class="size-row" id="sizeRow">
      @foreach($sizes as $s)
      <button type="button" class="size-opt" data-id="{{ $s->id }}" data-price="{{ $s->price }}" onclick="selectSize({{ $s->id }})">
        <span class="so-n">{{ $s->name }}</span>
        @if($s->note)<span class="so-note">{{ $s->note }}</span>@endif
        <span class="so-p">{{ number_format($s->price,0,',','.') }}đ</span>
      </button>
      @endforeach
    </div>
    </div>

    <div class="qty-row">
      <span class="sec-label" style="margin:0">Số lượng</span>
      <div class="qty-ctrl">
        <button class="qty-b" onclick="chgQty(-1)">−</button>
        <input class="qty-i" id="qty" type="number" value="1" min="1" max="99">
        <button class="qty-b" onclick="chgQty(1)">+</button>
      </div>
    </div>

    <div class="btn-row">
      <button class="btn-cart" onclick="addCombo(false)"><i class="ri-add-line"></i> Thêm vào giỏ</button>
      <button class="btn-buy" onclick="addCombo(true)"><i class="ri-shopping-cart-2-line"></i> Mua ngay</button>
    </div>
  </div>
</div>

<!-- Thanh mua cố định (mobile - giống Shopee) -->
<div class="combo-bar">
  <div class="cb-price"><span>Giá</span><b id="barPrice">{{ number_format($minPrice,0,',','.') }}đ</b></div>
  <button class="cb-cart" onclick="addCombo(false)"><i class="ri-add-line"></i> Thêm giỏ</button>
  <button class="cb-buy" onclick="addCombo(true)"><i class="ri-shopping-cart-2-line"></i> Mua ngay</button>
</div>

{{-- ════════ ĐÁNH GIÁ ════════ --}}
<div class="reviews-wrap">
  <div class="rv-head">
    <div class="rv-title">⭐ Đánh giá tranh {{ $category->name }}</div>
    <div class="rv-avg">
      <span class="rv-avg-num">{{ number_format($avgRating,1) }}</span>
      <div>
        <div class="rv-avg-stars">{{ str_repeat('★', round($avgRating)) }}{{ str_repeat('☆', 5-round($avgRating)) }}</div>
        <div class="rv-avg-count">{{ $reviewCount }} đánh giá</div>
      </div>
    </div>
  </div>

  {{-- Danh sách đánh giá --}}
  <div class="rv-list">
    @forelse($reviews as $rv)
    @php $rvCode = preg_match('/([A-Za-z]+\d+)\s*$/u', $rv->product->name ?? '', $m) ? $m[1] : ''; @endphp
    <div class="rv-item">
      <div class="rv-item-top">
        <div class="rv-author">
          <div class="rv-avatar">{{ mb_substr($rv->customer_name,0,1) }}</div>
          <div>
            <div class="rv-name">{{ $rv->customer_name }}</div>
            <div class="rv-date">{{ $rv->created_at->diffForHumans() }}@if($rvCode) · <span class="rv-prodcode">Mã {{ $rvCode }}</span>@endif</div>
          </div>
        </div>
        <div style="text-align:right">
          <div class="rv-stars">{{ $rv->stars }}</div>
          @if($rv->order_code)<div class="rv-badge">✓ Đã mua</div>@endif
        </div>
      </div>
      @if($rv->content)<div class="rv-content">{{ $rv->content }}</div>@endif
      @if($rv->image)
      <div style="margin-top:10px">
        <img src="{{ asset('storage/'.$rv->image) }}" alt="Thành quả của {{ $rv->customer_name }}" loading="lazy"
             style="max-width:160px;max-height:160px;border-radius:10px;border:1.5px solid var(--bd);cursor:zoom-in;object-fit:cover"
             onclick="window.open(this.src,'_blank')">
      </div>
      @endif
    </div>
    @empty
    <div style="text-align:center;padding:30px;color:var(--tx3);font-size:14px">
      Chưa có đánh giá nào. Hãy là người đầu tiên đánh giá! 🌸
    </div>
    @endforelse
  </div>

  {{-- Form viết đánh giá --}}
  <div class="rv-form" id="reviewForm">
    <div class="rv-form-title"><i class="ri-edit-line"></i> Đánh giá &amp; khoe thành quả của bạn</div>
    <div class="rv-success" id="reviewSuccess">🎉 Cảm ơn! Đánh giá của bạn đang chờ admin duyệt.</div>
    <div id="reviewFormContent">
      <div style="font-size:12px;color:var(--tx3);margin-bottom:12px">Đang đánh giá cho: <b id="rvForProduct" style="color:var(--gd)">—</b></div>
      <div style="margin-bottom:12px">
        <label class="rv-lbl">Đánh giá <span style="color:var(--pk)">*</span></label>
        <div class="star-picker" id="starPicker">
          @for($i=1;$i<=5;$i++)
          <button type="button" class="star-btn" data-val="{{ $i }}" onclick="setStar({{ $i }})">★</button>
          @endfor
        </div>
        <input type="hidden" id="ratingVal" value="5">
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
        <div>
          <label class="rv-lbl">Họ tên <span style="color:var(--pk)">*</span></label>
          <input type="text" id="rvName" class="rv-inp" placeholder="Nguyễn Văn A">
        </div>
        <div>
          <label class="rv-lbl">Số điện thoại</label>
          <input type="tel" id="rvPhone" class="rv-inp" placeholder="0912 345 678">
        </div>
      </div>
      <div style="margin-bottom:12px">
        <label class="rv-lbl">Mã đơn hàng <span style="font-size:10px;color:var(--tx3);font-weight:400">(không bắt buộc)</span></label>
        <input type="text" id="rvOrder" class="rv-inp" placeholder="DALI-XXXXXX" style="text-transform:uppercase">
      </div>
      <div style="margin-bottom:12px">
        <label class="rv-lbl">Nội dung đánh giá</label>
        <textarea id="rvContent" class="rv-inp" placeholder="Chia sẻ trải nghiệm của bạn..." rows="3" style="resize:vertical"></textarea>
      </div>
      <div style="margin-bottom:12px">
        <label class="rv-lbl"><i class="ri-camera-line"></i> Khoe thành quả <span style="font-size:10px;color:var(--tx3);font-weight:400">(ảnh tranh bạn đã tô)</span></label>
        <input type="file" id="rvImage" accept="image/*" style="width:100%;font-size:12px;color:var(--tx2)">
        <div id="rvImgPreview" style="margin-top:8px"></div>
      </div>
      <button class="rv-submit" id="rvSubmitBtn" onclick="submitReview()">Gửi đánh giá →</button>
    </div>
  </div>
</div>

<div class="toast" id="toast"></div>
@include('partials.float-widget')
@include('partials.bottom-nav')
<script>
var PRODUCTS = @json($jsProducts);
var SIZES = @json($jsSizes);
var SELECTED = null;     // product
var SELECTED_SIZE = null;
function fmt(n){return Math.round(n).toLocaleString('vi-VN')+'đ';}
function showToast(m){var t=document.getElementById('toast');t.textContent=m;t.classList.add('show');setTimeout(()=>t.classList.remove('show'),3000);}

function selectPainting(id){
  SELECTED = PRODUCTS.find(p=>p.id===id);
  if(!SELECTED) return;
  document.getElementById('previewImg').src = SELECTED.image;
  document.getElementById('previewCap').textContent = SELECTED.code ? ('Mã: '+SELECTED.code+' · '+SELECTED.name) : SELECTED.name;
  var rvFor=document.getElementById('rvForProduct'); if(rvFor) rvFor.textContent = SELECTED.code ? (SELECTED.code+' · '+SELECTED.name) : SELECTED.name;
  document.querySelectorAll('.code-card').forEach(c=>c.classList.toggle('active', parseInt(c.dataset.id)===id));
  // lọc size theo sản phẩm (nếu có giới hạn)
  document.querySelectorAll('.size-opt').forEach(b=>{
    var sid=parseInt(b.dataset.id);
    var ok = !SELECTED.sizeIds.length || SELECTED.sizeIds.map(Number).indexOf(sid)>=0;
    b.style.display = ok ? '' : 'none';
  });
  // nếu size đang chọn không hợp lệ -> chọn size hiển thị đầu tiên
  var visible=[...document.querySelectorAll('.size-opt')].filter(b=>b.style.display!=='none');
  if(!SELECTED_SIZE || !visible.find(b=>parseInt(b.dataset.id)===SELECTED_SIZE.id)){
    if(visible[0]) selectSize(parseInt(visible[0].dataset.id));
  }
  // Mobile: cuộn lên cho khách thấy ngay ảnh vừa chọn
  if(window.innerWidth<=880){
    var box=document.querySelector('.preview-box');
    if(box){var r=box.getBoundingClientRect();window.scrollTo({top:Math.max(0,window.scrollY+r.top-76),behavior:'smooth'});}
  }
}
function selectSize(id){
  SELECTED_SIZE = SIZES.find(s=>s.id===id);
  document.querySelectorAll('.size-opt').forEach(b=>b.classList.toggle('active', parseInt(b.dataset.id)===id));
  if(SELECTED_SIZE){
    document.getElementById('priceMain').textContent = fmt(SELECTED_SIZE.price);
    var bp=document.getElementById('barPrice'); if(bp) bp.textContent = fmt(SELECTED_SIZE.price);
  }
}
function chgQty(d){var i=document.getElementById('qty');i.value=Math.min(99,Math.max(1,parseInt(i.value)+d));}

async function addCombo(buyNow){
  if(!SELECTED){showToast('⚠️ Vui lòng chọn mã tranh');document.getElementById('codeGrid').scrollIntoView({behavior:'smooth'});return;}
  if(!SELECTED_SIZE){showToast('⚠️ Vui lòng chọn kích thước');return;}
  var qty=parseInt(document.getElementById('qty').value)||1;
  try{
    var res=await fetch('{{ route("cart.add") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({product_id:SELECTED.id,size_id:SELECTED_SIZE.id,quantity:qty})});
    var d=await res.json();
    if(d.success){
      var b=document.getElementById('fabCartCount'); if(b){b.textContent=d.count;b.style.display='flex';}
      if(buyNow){ window.location='{{ route("cart") }}'; }
      else showToast('✅ Đã thêm '+SELECTED.code+' ('+SELECTED_SIZE.name+') vào giỏ!');
    } else showToast('❌ '+(d.message||'Có lỗi'));
  }catch(e){showToast('❌ Lỗi kết nối');}
}

// khởi tạo: chọn mẫu đầu + size nhỏ nhất
document.addEventListener('DOMContentLoaded',function(){
  if(PRODUCTS.length) selectPainting(PRODUCTS[0].id);
  if(SIZES.length && !SELECTED_SIZE) selectSize(SIZES[0].id);
});

// ── ĐÁNH GIÁ ──
function setStar(val){
  document.getElementById('ratingVal').value=val;
  document.querySelectorAll('.star-btn').forEach(function(b){
    b.classList.toggle('active', parseInt(b.dataset.val)<=val);
  });
}
setStar(5);
(function(){var el=document.getElementById('rvImage');if(!el)return;el.addEventListener('change',function(){var p=document.getElementById('rvImgPreview');if(this.files&&this.files[0]){var r=new FileReader();r.onload=function(e){p.innerHTML='<img src="'+e.target.result+'" style="max-width:120px;max-height:120px;border-radius:8px;border:1.5px solid var(--bd);object-fit:cover">';};r.readAsDataURL(this.files[0]);}else{p.innerHTML='';}});})();

async function submitReview(){
  if(!SELECTED){showToast('⚠️ Vui lòng chọn mã tranh trước');return;}
  var name=document.getElementById('rvName').value.trim();
  var rating=parseInt(document.getElementById('ratingVal').value)||5;
  if(!name){showToast('⚠️ Vui lòng nhập họ tên');return;}
  var btn=document.getElementById('rvSubmitBtn');
  btn.disabled=true; btn.textContent='⏳ Đang gửi...';
  try{
    var fd=new FormData();
    fd.append('product_id',SELECTED.id);
    fd.append('customer_name',name);
    fd.append('customer_phone',document.getElementById('rvPhone').value.trim());
    fd.append('rating',rating);
    fd.append('content',document.getElementById('rvContent').value.trim());
    fd.append('order_code',document.getElementById('rvOrder').value.trim().toUpperCase());
    var imgEl=document.getElementById('rvImage');
    if(imgEl && imgEl.files && imgEl.files[0]) fd.append('image',imgEl.files[0]);
    var res=await fetch('{{ route("submit-review") }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},body:fd});
    var d=await res.json();
    if(d.success){
      document.getElementById('reviewFormContent').style.display='none';
      document.getElementById('reviewSuccess').style.display='block';
    } else { showToast('❌ '+(d.message||'Có lỗi xảy ra')); btn.disabled=false; btn.textContent='Gửi đánh giá →'; }
  }catch(e){ showToast('❌ Lỗi kết nối'); btn.disabled=false; btn.textContent='Gửi đánh giá →'; }
}
</script>
</body>
</html>
