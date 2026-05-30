<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="description" content="Xem tất cả {{ $products->total() }} mẫu tranh tô màu số hóa DALI – chất lượng cao, giao hàng toàn quốc.">
<meta property="og:title" content="Tất cả sản phẩm | DALI Tranh Tô Màu Số Hóa">
<meta property="og:image" content="{{ asset('images/logo_dali.png') }}">
@if(!empty($settings['ga_id']))<script async src="https://www.googletagmanager.com/gtag/js?id={{ $settings['ga_id'] }}"></script><script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ $settings["ga_id"] }}');</script> @endif
@if(!empty($settings['fb_pixel_id']))<script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init','{{ $settings["fb_pixel_id"] }}');fbq('track','PageView');</script> @endif
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Sản phẩm | DALI – Tô Điểm Cuộc Sống</title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
<style>

:root{--g:#6BBF1F;--gb:#8ED63A;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--gn:#C6F135;--pk:#FF8FB1;--pkl:#FFF0F5;--bl:#74C7FF;--bll:#EEF8FF;--yl:#FFE066;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--bd:#C8E89A;--bd2:#A8D870;--bg:#F2FDE8;--wh:#FFFFFF;--char:#1C3A0A;--shad:rgba(58,122,10,.10)}
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:var(--tx);line-height:1.6}
::-webkit-scrollbar{width:5px}
::-webkit-scrollbar-track{background:var(--bg)}
::-webkit-scrollbar-thumb{background:var(--bd2);border-radius:4px}
nav{position:sticky;top:0;z-index:100;background:linear-gradient(175deg,#1C5200,#2D7A08,#3A9A12);height:68px;padding:0 5%;display:flex;align-items:center;justify-content:space-between;transition:transform .3s}
nav.nav-hidden{transform:translateY(-100%)}
.nav-logo{height:38px;object-fit:contain;display:block;filter:brightness(0) invert(1)}
.nav-links{display:flex;gap:28px;list-style:none}
.nav-links a{text-decoration:none;color:rgba(255,255,255,.75);font-size:14px;font-weight:500;border-bottom:2px solid transparent;padding-bottom:2px;transition:all .2s}
.nav-links a:hover,.nav-links a.active{color:#fff;border-bottom-color:var(--gn)}
.nav-right{display:flex;align-items:center;gap:12px}
.nav-phone{font-size:13px;font-weight:600;color:rgba(255,255,255,.85);text-decoration:none}
.btn-order-nav{background:var(--gn);color:var(--char);border:none;border-radius:50px;padding:9px 20px;font-size:13px;font-weight:800;cursor:pointer;text-decoration:none;display:inline-block;transition:all .2s}
.btn-order-nav:hover{background:#fff;transform:translateY(-2px)}
.nav-hamburger{display:none;flex-direction:column;gap:5px;cursor:pointer;background:none;border:none}
.nav-hamburger span{display:block;width:22px;height:2px;background:#fff}
.mobile-nav{display:none;position:fixed;top:68px;left:0;right:0;bottom:0;background:linear-gradient(175deg,#1C5200,#2D7A08);z-index:99;padding:28px 5%;flex-direction:column;gap:4px}
.mobile-nav.open{display:flex}
.mobile-nav a{font-size:17px;font-weight:600;color:rgba(255,255,255,.8);text-decoration:none;padding:13px 16px;border-bottom:1px solid rgba(255,255,255,.1);border-radius:8px}
.sakura-strip{background:linear-gradient(90deg,#fff8fa,#f6ffe8,#fff,#f6ffe8,#fff8fa);border-bottom:1px solid #F0EBF8;padding:7px 5%;display:flex;align-items:center;gap:6px}
.petal{font-size:15px;animation:drift 5s ease-in-out infinite;display:inline-block}
.petal:nth-child(2){animation-delay:1s}.petal:nth-child(3){animation-delay:2s}.petal:nth-child(4){animation-delay:3s}
@keyframes drift{0%,100%{transform:translateY(0)}50%{transform:translateY(-4px)}}
.sak-text{font-size:10px;color:#B8D8A0;letter-spacing:2.5px;font-weight:700;margin-left:8px}
footer{background:linear-gradient(175deg,#0F2E00,#1C5200);color:rgba(255,255,255,.7);padding:52px 5% 26px}
.footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:36px;margin-bottom:40px}
.footer-logo{height:34px;width:auto;object-fit:contain;filter:brightness(0) invert(1);display:block;margin-bottom:13px}
.footer-brand p{font-size:13px;line-height:1.9;margin-bottom:16px}
.social-links{display:flex;gap:10px}
.social-btn{width:35px;height:35px;border-radius:50%;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;text-decoration:none;font-size:14px;transition:all .2s}
.social-btn:hover{background:var(--g)}
.footer-col h4{color:#fff;font-size:14px;font-weight:800;margin-bottom:16px}
.footer-col ul{list-style:none}
.footer-col li{margin-bottom:8px}
.footer-col a{color:rgba(255,255,255,.5);text-decoration:none;font-size:13px;transition:color .2s}
.footer-col a:hover{color:var(--gn)}
.footer-contact p{font-size:13px;margin-bottom:6px}
.footer-bottom{border-top:1px solid rgba(255,255,255,.08);padding-top:20px;display:flex;justify-content:space-between;flex-wrap:wrap;gap:10px;font-size:12px;color:rgba(255,255,255,.3)}
.btn-primary{background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:50px;padding:12px 28px;font-size:14px;font-weight:700;cursor:pointer;transition:all .25s;text-decoration:none;display:inline-block}
.btn-primary:hover{background:linear-gradient(135deg,#2E7D08,#5AAF15);transform:translateY(-2px)}
.btn-outline{background:transparent;color:var(--gd);border:2px solid var(--g);border-radius:50px;padding:10px 26px;font-size:14px;font-weight:700;cursor:pointer;transition:all .25s;text-decoration:none;display:inline-block}
.btn-outline:hover{background:var(--g);color:#fff}
.toast{position:fixed;bottom:22px;left:50%;transform:translateX(-50%) translateY(100px);background:var(--char);color:#fff;padding:11px 22px;border-radius:50px;font-size:13px;font-weight:600;z-index:9999;transition:transform .4s;white-space:nowrap;max-width:90vw;text-align:center}
.toast.show{transform:translateX(-50%) translateY(0)}
/* ORDER MODAL */
.modal-overlay{position:fixed;inset:0;background:rgba(28,82,0,.55);z-index:999;display:none;align-items:center;justify-content:center;padding:16px;backdrop-filter:blur(5px)}
.modal-overlay.open{display:flex}
.modal{background:#fff;border-radius:22px;width:100%;max-width:570px;max-height:94vh;overflow-y:auto;position:relative;border:1.5px solid var(--bd)}
.modal::before{content:'';display:block;height:4px;background:linear-gradient(90deg,#3A9A12,var(--g),var(--gn),var(--pk),#A78BFA);border-radius:22px 22px 0 0}
.modal-header{padding:20px 26px 16px;border-bottom:1px solid var(--gl);position:sticky;top:0;background:#fff;z-index:2}
.modal-header h2{font-size:19px;font-weight:900;color:var(--char);margin-bottom:2px}
.modal-header p{font-size:12px;color:var(--tx3)}
.modal-close{position:absolute;top:16px;right:20px;background:var(--gl);border:none;width:32px;height:32px;border-radius:50%;font-size:16px;cursor:pointer;color:var(--gd);display:flex;align-items:center;justify-content:center}
.modal-state{padding:20px 26px;display:none}
.modal-state.active{display:block}
.order-product{display:flex;gap:13px;background:var(--gll);border-radius:12px;padding:13px;margin-bottom:16px;align-items:center;border:1px solid var(--bd)}
.order-product img{width:58px;height:58px;object-fit:cover;border-radius:9px;flex-shrink:0}
.op-name{font-weight:800;font-size:13px;color:var(--char);margin-bottom:2px}
.op-size{font-size:11px;color:var(--tx3);margin-bottom:3px}
.op-price{font-size:15px;font-weight:900;color:var(--g)}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px}
.form-row.single{grid-template-columns:1fr}
.form-group{display:flex;flex-direction:column;gap:5px}
.form-group label{font-size:12px;font-weight:700;color:var(--tx)}
.req{color:var(--pk)}
.form-group input,.form-group select,.form-group textarea{border:1.5px solid var(--bd);border-radius:9px;padding:10px 13px;font-size:13px;font-family:'Be Vietnam Pro',sans-serif;color:var(--tx);background:var(--gll);transition:border-color .2s;outline:none}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{border-color:var(--g);background:#fff}
.form-group textarea{resize:vertical;min-height:66px}
.qty-control{display:flex;align-items:center;border:1.5px solid var(--bd);border-radius:9px;overflow:hidden;width:fit-content}
.qty-btn{background:var(--gl);border:none;width:36px;height:40px;font-size:18px;cursor:pointer;font-weight:700;color:var(--gd)}
.qty-input{border:none;border-left:1.5px solid var(--bd);border-right:1.5px solid var(--bd);width:52px;height:40px;text-align:center;font-size:15px;font-weight:800;color:var(--char);font-family:'Be Vietnam Pro',sans-serif;outline:none;background:#fff}
.payment-opts{display:grid;grid-template-columns:1fr 1fr;gap:11px;margin-bottom:13px}
.payment-opt{border:2px solid var(--bd);border-radius:12px;padding:12px;cursor:pointer;display:flex;align-items:center;gap:9px;transition:all .2s;position:relative}
.payment-opt:hover{border-color:var(--g)}
.payment-opt.active{border-color:var(--g);background:var(--gl)}
.payment-opt input[type=radio]{display:none}
.payment-opt-icon{font-size:20px}
.payment-opt-text{font-size:13px;font-weight:700;color:var(--char);line-height:1.3}
.payment-opt-sub{font-size:11px;color:var(--tx3)}
.discount-badge{position:absolute;top:-8px;right:9px;background:var(--g);color:#fff;font-size:10px;font-weight:800;padding:2px 8px;border-radius:20px}
.order-summary{background:var(--gll);border-radius:12px;padding:15px;margin:13px 0;border:1px solid var(--bd)}
.summary-row{display:flex;justify-content:space-between;align-items:center;padding:5px 0;font-size:13px}
.summary-row.discount{color:var(--gd);font-weight:700}
.summary-row.total{border-top:1.5px dashed var(--bd);padding-top:11px;margin-top:4px;font-weight:800;font-size:16px}
.summary-row.total .val{color:var(--g);font-size:18px}
.btn-submit{width:100%;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:12px;padding:14px;font-size:15px;font-weight:800;cursor:pointer;transition:all .2s;margin-top:5px;box-shadow:0 4px 16px rgba(107,191,31,.3)}
.btn-submit:hover{background:linear-gradient(135deg,#2E7D08,#5AAF15);transform:translateY(-1px)}
.btn-submit:disabled{background:#C8E89A;cursor:not-allowed;transform:none;box-shadow:none}
.qr-box{text-align:center;padding:6px 0}
.qr-top-info{background:var(--gl);border-radius:12px;padding:12px 15px;margin-bottom:16px;display:flex;align-items:center;gap:10px;border:1px solid var(--bd2)}
.qr-top-info .icon{font-size:20px;flex-shrink:0}
.qr-top-info p{font-size:12px;color:var(--gd);line-height:1.5;text-align:left}
.qr-top-info strong{font-size:13px;display:block;margin-bottom:2px}
.qr-frame{display:inline-block;background:#fff;border:3px solid var(--g);border-radius:16px;padding:12px;margin:0 auto 14px;box-shadow:0 4px 18px rgba(107,191,31,.18)}
.qr-frame img{width:190px;height:190px;display:block;border-radius:7px}
.qr-amount{font-size:28px;font-weight:900;color:var(--g);margin-bottom:3px}
.qr-amount-label{font-size:12px;color:var(--tx3);margin-bottom:13px}
.bank-info{background:var(--gll);border-radius:12px;padding:13px;margin-bottom:14px;text-align:left;border:1px solid var(--bd)}
.bank-row{display:flex;justify-content:space-between;align-items:center;padding:5px 0;font-size:13px;border-bottom:1px dashed var(--bd)}
.bank-row:last-child{border-bottom:none}
.bank-row .label{color:var(--tx3);font-weight:500}
.bank-row .val{font-weight:800;color:var(--char)}
.copy-btn{background:var(--gl);color:var(--gd);border:1px solid var(--bd2);border-radius:6px;padding:3px 9px;font-size:11px;cursor:pointer;font-weight:700}
.copy-btn:hover{background:var(--g);color:#fff;border-color:var(--g)}
.countdown-wrap{background:var(--char);border-radius:12px;padding:12px;text-align:center;margin-bottom:14px;color:#fff}
.countdown-title{font-size:11px;opacity:.6;margin-bottom:3px}
.countdown-timer{font-size:26px;font-weight:900;color:var(--gn);letter-spacing:2px}
.countdown-sub{font-size:10px;opacity:.5;margin-top:3px}
.countdown-wrap.urgent{background:#991111}
.countdown-wrap.urgent .countdown-timer{color:#ffcccc}
.btn-confirm-paid{width:100%;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:12px;padding:13px;font-size:14px;font-weight:800;cursor:pointer;transition:all .2s;box-shadow:0 4px 14px rgba(107,191,31,.3)}
.btn-confirm-paid:disabled{background:#C8E89A;cursor:not-allowed}
.btn-back-link{display:block;text-align:center;margin-top:10px;color:var(--tx3);font-size:12px;cursor:pointer;text-decoration:underline}
.waiting-box{text-align:center;padding:26px 13px}
.waiting-anim{font-size:50px;margin-bottom:13px;display:block;animation:pulse 1.5s ease-in-out infinite}
@keyframes pulse{0%,100%{transform:scale(1);opacity:1}50%{transform:scale(1.1);opacity:.7}}
.waiting-box h3{font-size:19px;font-weight:900;color:var(--char);margin-bottom:8px}
.waiting-box p{font-size:13px;color:var(--tx2);line-height:1.7;margin-bottom:5px}
.order-code-display{font-size:19px;font-weight:900;background:var(--bll);color:var(--bl);padding:8px 20px;border-radius:9px;display:inline-block;margin:11px 0;letter-spacing:1px}
.waiting-steps{background:var(--gll);border-radius:12px;padding:13px;margin:12px 0;text-align:left;border:1px solid var(--bd)}
.ws-item{display:flex;align-items:center;gap:8px;padding:6px 0;font-size:12px}
.success-box{text-align:center;padding:30px 16px}
.success-icon-big{font-size:58px;margin-bottom:13px;display:block}
.success-box h3{font-size:22px;font-weight:900;color:var(--g);margin-bottom:8px}
.success-box p{font-size:13px;color:var(--tx2);line-height:1.7;margin-bottom:5px}
.order-code-success{font-size:18px;font-weight:900;background:var(--gl);color:var(--gd);padding:8px 20px;border-radius:9px;display:inline-block;margin:11px 0;border:1.5px solid var(--bd2)}
.btn-close-modal{background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:50px;padding:11px 28px;font-size:14px;font-weight:800;cursor:pointer;margin-top:12px}
@media(max-width:900px){.footer-grid{grid-template-columns:1fr 1fr}.nav-links{display:none}.nav-hamburger{display:flex}}
@media(max-width:600px){.footer-grid{grid-template-columns:1fr}.footer-bottom{flex-direction:column;text-align:center}.form-row{grid-template-columns:1fr}.payment-opts{grid-template-columns:1fr}.nav-phone,.nav-tracuu{display:none}nav{padding:0 4%}}

.page-hero{background:linear-gradient(175deg,#1C5200,#2D7A08);padding:44px 5% 36px;color:#fff;text-align:center}
.page-hero h1{font-size:clamp(24px,3.5vw,38px);font-weight:900;margin-bottom:8px}
.page-hero p{font-size:15px;opacity:.75;max-width:500px;margin:0 auto}
.filter-bar{background:#fff;border-bottom:1.5px solid var(--bd);padding:14px 5%;display:flex;align-items:center;gap:10px;flex-wrap:wrap;position:sticky;top:68px;z-index:90}
.filter-select,.filter-input{background:var(--gll);border:1.5px solid var(--bd);border-radius:9px;padding:9px 13px;font-size:13px;color:var(--tx);outline:none;font-family:'Be Vietnam Pro',sans-serif;transition:all .2s}
.filter-select:focus,.filter-input:focus{border-color:var(--g)}
.btn-filter{padding:9px 18px;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:9px;font-size:13px;font-weight:700;cursor:pointer;transition:all .2s}
.btn-filter:hover{background:linear-gradient(135deg,#2E7D08,#5AAF15);transform:translateY(-1px)}
.btn-clear{font-size:12px;color:var(--pk);font-weight:700;background:none;border:none;cursor:pointer;padding:5px}
.products-section{padding:40px 5%}
.result-count{font-size:13px;color:var(--tx3);margin-bottom:18px;font-weight:500}
.result-count b{color:var(--g)}
.products-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:20px}
.product-card{background:#fff;border-radius:18px;overflow:hidden;border:1.5px solid var(--bd);transition:transform .3s,box-shadow .3s}
.product-card:hover{transform:translateY(-8px);box-shadow:0 16px 44px rgba(58,122,10,.12)}
.product-img{position:relative;height:220px;overflow:hidden;display:block;text-decoration:none}
.product-img img{width:100%;height:100%;object-fit:cover;transition:transform .4s}
.product-card:hover .product-img img{transform:scale(1.06)}
.product-badge{position:absolute;top:12px;left:12px;color:#fff;font-size:11px;font-weight:800;padding:4px 10px;border-radius:50px;z-index:2}
.product-info{padding:15px}
.product-name{font-size:14px;font-weight:700;color:var(--char);margin-bottom:4px;line-height:1.4}
.product-size{font-size:12px;color:var(--tx3);margin-bottom:8px}
.product-price{display:flex;align-items:center;gap:8px;margin-bottom:12px;flex-wrap:wrap}
.price-current{font-size:18px;font-weight:900;color:var(--g)}
.price-old{font-size:13px;color:var(--tx3);text-decoration:line-through}
.btn-buy{width:100%;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:9px;padding:11px;font-size:13px;font-weight:700;cursor:pointer;transition:all .2s}
.btn-buy:hover{background:linear-gradient(135deg,#2E7D08,#5AAF15);transform:translateY(-1px)}
.no-products{text-align:center;padding:60px 20px;color:var(--tx3)}
.no-products .icon{font-size:52px;margin-bottom:14px}
.no-products h3{font-size:18px;font-weight:700;margin-bottom:8px;color:var(--char)}
.pagination{display:flex;gap:6px;margin-top:28px;flex-wrap:wrap;justify-content:center}
.pagination a,.pagination span{padding:8px 14px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;border:1.5px solid var(--bd);color:var(--tx2);background:#fff;transition:all .2s}
.pagination a:hover{background:var(--g);color:#fff;border-color:var(--g)}
.pagination .active{background:var(--g);color:#fff;border-color:var(--g)}
.cat-tabs{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px}
.cat-tab{padding:7px 16px;border-radius:50px;border:1.5px solid var(--bd);background:#fff;font-size:12px;font-weight:700;color:var(--tx2);cursor:pointer;text-decoration:none;transition:all .2s}
.cat-tab:hover,.cat-tab.active{background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border-color:var(--g)}
@media(max-width:600px){.products-grid{grid-template-columns:repeat(2,1fr);gap:12px}.filter-bar{flex-direction:column;align-items:stretch}}
</style>
</head>
<body>

<nav id="mainNav">
  <a href="{{ route('home') }}"><img src="{{ asset('images/logo_dali.png') }}" alt="DALI" class="nav-logo"></a>
  <ul class="nav-links">
    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Trang chủ</a></li>
    <li><a href="{{ route('products') }}" class="{{ request()->routeIs('products') ? 'active' : '' }}">Sản phẩm</a></li>
    <li><a href="{{ route('home') }}#ve-chung-toi">Về chúng tôi</a></li>
    <li><a href="{{ route('home') }}#huong-dan">Hướng dẫn</a></li>
    <li><a href="{{ route('home') }}#lien-he">Liên hệ</a></li>
  </ul>
  <div class="nav-right">
    <a href="tel:{{ $settings['shop_phone'] ?? '0123456789' }}" class="nav-phone">📞 {{ $settings['shop_phone'] ?? '0123456789' }}</a>
    <a href="{{ route('track-order') }}" class="nav-tracuu" style="font-size:13px;color:rgba(255,255,255,.75);text-decoration:none;font-weight:500">🔍 Tra cứu đơn</a>
    <a href="#" class="btn-order-nav" onclick="document.querySelector('.products-grid')?.scrollIntoView({behavior:'smooth'});return false">Mua ngay</a>
    <button class="nav-hamburger" id="hamburger" onclick="toggleMenu()"><span></span><span></span><span></span></button>
  </div>
</nav>
<div class="mobile-nav" id="mobileNav">
  <a href="{{ route('home') }}">🏠 Trang chủ</a>
  <a href="{{ route('products') }}">🎨 Sản phẩm</a>
  <a href="{{ route('track-order') }}">🔍 Tra cứu đơn hàng</a>
  <a href="{{ route('home') }}#lien-he">📞 Liên hệ</a>
</div>

<div class="sakura-strip"><span class="petal">🌸</span><span class="petal">✿</span><span class="petal">🍃</span><span class="petal">🌸</span><span class="sak-text">DALI · TÔ ĐIỂM CUỘC SỐNG</span></div>

<div class="page-hero">
  <h1>🎨 Tất cả sản phẩm tranh DALI</h1>
  <p>{{ $products->total() }} mẫu tranh tô màu số hóa cao cấp</p>
</div>

{{-- Filter bar --}}
<div class="filter-bar">
  <form method="GET" action="{{ route('products') }}" style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;width:100%">
    <select name="category" class="filter-select" onchange="this.form.submit()">
      <option value="">🏷️ Tất cả danh mục</option>
      @foreach($categories as $cat)
      <option value="{{ $cat->slug }}" { request('category') == $cat->slug ? 'selected' : '' }>{{ $cat->icon }} {{ $cat->name }}</option>
      @endforeach
    </select>
    <select name="sort" class="filter-select" onchange="this.form.submit()">
      <option value="" { !request('sort') ? 'selected' : '' }>📊 Sắp xếp</option>
      <option value="price_asc" { request('sort')=='price_asc' ? 'selected' : '' }>💰 Giá tăng dần</option>
      <option value="price_desc" { request('sort')=='price_desc' ? 'selected' : '' }>💰 Giá giảm dần</option>
      <option value="new" { request('sort')=='new' ? 'selected' : '' }>🆕 Mới nhất</option>
    </select>
    <input type="text" name="search" class="filter-input" placeholder="🔍 Tìm tên tranh..." value="{{ request('search') }}" style="flex:1;min-width:180px">
    <button type="submit" class="btn-filter">Tìm kiếm</button>
    @if(request()->hasAny(['category','search','sort']))
    <a href="{{ route('products') }}" class="btn-clear">✕ Xoá lọc</a>
    @endif
  </form>
</div>

<div class="products-section">
  {{-- Tab danh mục --}}
  @if($categories->count())
  <div class="cat-tabs">
    <a href="{{ route('products') }}" class="cat-tab { !request('category') ? 'active' : '' }">🎨 Tất cả</a>
    @foreach($categories as $cat)
    <a href="{{ route('products') }}?category={{ $cat->slug }}" class="cat-tab { request('category')==$cat->slug ? 'active' : '' }">{{ $cat->icon }} {{ $cat->name }}</a>
    @endforeach
  </div>
  @endif

  <div class="result-count">Tìm thấy <b>{{ $products->total() }}</b> sản phẩm @if(request('category')) trong danh mục này @endif</div>

  @if($products->count())
  <div class="products-grid">
    @foreach($products as $p)
    <div class="product-card">
      <a href="{{ route('product', $p->slug) }}" class="product-img">
        @if($p->main_image)
          <img src="{{ asset('storage/'.$p->main_image) }}" alt="{{ $p->name }}" loading="lazy">
        @else
          <img src="https://images.unsplash.com/photo-1578926288207-a90a5366759d?w=400&q=60" alt="{{ $p->name }}" loading="lazy">
        @endif
        @if($p->badge)
          <div class="product-badge" style="background:{{ $p->badge_type=='new' ? '#2563EB' : ($p->badge_type=='hot' ? '#E11D48' : ($p->badge_type=='sale' ? '#D97706' : 'var(--g)')) }}">{{ $p->badge }}</div>
        @endif
      </a>
      <div class="product-info">
        <a href="{{ route('product', $p->slug) }}" class="product-name" style="text-decoration:none;color:inherit;display:block">{{ $p->name }}</a>
        <div class="product-size">{{ $p->colors_count ? $p->colors_count.' màu' : '' }}{{ $p->sizes()->count() ? ($p->colors_count ? ' · ' : '').$p->sizes()->count().' kích thước' : '' }}</div>
        <div class="product-price">
          @if($p->has_multiple_sizes)<span style="font-size:12px;color:var(--tx3);font-weight:600">Từ</span>@endif
          <span class="price-current">{{ $p->display_price }}</span>
        </div>
        <button class="btn-buy" onclick="window.location='{{ route('product', $p->slug) }}'">🛒 Đặt mua ngay</button>
      </div>
    </div>
    @endforeach
  </div>
  @if($products->hasPages())
  <div class="pagination">
    @if($products->onFirstPage())
      <span style="opacity:.35;cursor:default">‹ Trước</span>
    @else
      <a href="{{ $products->previousPageUrl() }}" rel="prev">‹ Trước</a>
    @endif
    @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
      @if($page == $products->currentPage())
        <span class="active">{{ $page }}</span>
      @else
        <a href="{{ $url }}">{{ $page }}</a>
      @endif
    @endforeach
    @if($products->hasMorePages())
      <a href="{{ $products->nextPageUrl() }}" rel="next">Sau ›</a>
    @else
      <span style="opacity:.35;cursor:default">Sau ›</span>
    @endif
  </div>
  @endif
  @else
  <div class="no-products">
    <div class="icon">🎨</div>
    <h3>Không tìm thấy sản phẩm phù hợp</h3>
    <p>Thử thay đổi bộ lọc hoặc tìm kiếm với từ khóa khác</p>
    <a href="{{ route('products') }}" class="btn-primary" style="margin-top:16px">Xem tất cả sản phẩm</a>
  </div>
  @endif
</div>


<footer id="lien-he">
  <div class="footer-grid">
    <div class="footer-brand">
      <img src="{{ asset('images/logo_dali.png') }}" alt="DALI" class="footer-logo">
      <p>DALI – Thương hiệu tranh tô màu số hóa hàng đầu Việt Nam.</p>
      <div class="social-links"><a href="#" class="social-btn">📘</a><a href="#" class="social-btn">📷</a><a href="#" class="social-btn">💬</a></div>
    </div>
    <div class="footer-col"><h4>Sản phẩm</h4><ul><li><a href="{{ route('products') }}?category=phong-canh">Phong cảnh</a></li><li><a href="{{ route('products') }}?category=hoa-thuc-vat">Hoa & Thực vật</a></li><li><a href="{{ route('products') }}?category=dong-vat">Động vật</a></li><li><a href="{{ route('products') }}">Xem tất cả</a></li></ul></div>
    <div class="footer-col"><h4>Hỗ trợ</h4><ul><li><a href="{{ route('track-order') }}">Tra cứu đơn hàng</a></li><li><a href="{{ route('home') }}#huong-dan">Hướng dẫn tô màu</a></li><li><a href="#">Chính sách đổi trả</a></li><li><a href="#">Câu hỏi thường gặp</a></li></ul></div>
    <div class="footer-col"><h4>Liên hệ</h4><p>📍 {{ $settings['shop_address'] ?? 'Số 1 Đường ABC, Hà Nội' }}</p><p>📞 <a href="tel:{{ $settings['shop_phone'] ?? '' }}" style="color:var(--gn);text-decoration:none">{{ $settings['shop_phone'] ?? '0123456789' }}</a></p><p>⏰ T2–T7: 8:00 – 20:00</p></div>
  </div>
  <div class="footer-bottom"><span>© 2024 DALI Tranh Tô Màu Số Hóa</span><span>Thiết kế tại Việt Nam 🇻🇳</span></div>
</footer>


{{{-- ORDER MODAL --}}}
<div class="modal-overlay" id="orderModal" onclick="if(event.target===this)closeOrder()">
  <div class="modal">
    <div class="modal-header">
      <h2 id="modalTitle">🛒 Đặt hàng DALI</h2>
      <p id="modalSub">Điền thông tin – xác nhận trong 30 phút</p>
      <button class="modal-close" onclick="closeOrder()">✕</button>
    </div>
    <div class="modal-state active" id="state-form">
      <div class="order-product">
        <img id="oImg" src="" alt="" onerror="this.style.display='none'">
        <div><div class="op-name" id="oName">–</div><div class="op-size" id="oSize">–</div><div class="op-price" id="oPrice">–</div></div>
      </div>
      <div style="margin-bottom:13px">
        <label style="font-size:12px;font-weight:700;color:var(--tx);display:block;margin-bottom:7px">Số lượng</label>
        <div class="qty-control"><button class="qty-btn" onclick="changeQty(-1)">−</button><input class="qty-input" id="qtyInput" type="number" value="1" min="1" max="99"><button class="qty-btn" onclick="changeQty(1)">+</button></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Họ và tên <span class="req">*</span></label><input type="text" id="custName" placeholder="Nguyễn Văn A"></div>
        <div class="form-group"><label>Số điện thoại <span class="req">*</span></label><input type="tel" id="custPhone" placeholder="0912 345 678"></div>
      </div>
      <div class="form-row single">
        <div class="form-group"><label>Tỉnh / Thành phố <span class="req">*</span></label>
          <select id="custCity"><option value="">— Chọn tỉnh/thành —</option><option>Hà Nội</option><option>TP. Hồ Chí Minh</option><option>Đà Nẵng</option><option>Hải Phòng</option><option>Cần Thơ</option><option>An Giang</option><option>Bắc Giang</option><option>Bắc Ninh</option><option>Bình Dương</option><option>Bình Thuận</option><option>Đắk Lắk</option><option>Đồng Nai</option><option>Hà Tĩnh</option><option>Hải Dương</option><option>Khánh Hòa</option><option>Kiên Giang</option><option>Lâm Đồng</option><option>Long An</option><option>Nam Định</option><option>Nghệ An</option><option>Ninh Bình</option><option>Phú Thọ</option><option>Quảng Bình</option><option>Quảng Nam</option><option>Quảng Ninh</option><option>Thanh Hóa</option><option>Thừa Thiên Huế</option><option>Tiền Giang</option><option>Vĩnh Long</option><option>Vĩnh Phúc</option><option>Yên Bái</option></select>
        </div>
      </div>
      <div class="form-row single"><div class="form-group"><label>Địa chỉ cụ thể <span class="req">*</span></label><input type="text" id="custAddr" placeholder="Số nhà, tên đường, phường/xã, quận/huyện"></div></div>
      <div class="form-row single"><div class="form-group"><label>Ghi chú</label><textarea id="custNote" placeholder="Gọi trước khi giao, đóng gói kỹ hơn..."></textarea></div></div>
      <div class="form-row single"><div class="form-group"><label>Email <span style="font-size:10px;color:var(--tx3);font-weight:400">(nhận xác nhận đơn hàng)</span></label><input type="email" id="custEmail" placeholder="email@gmail.com" style="width:100%;background:var(--gll);border:1.5px solid var(--bd);border-radius:9px;padding:10px 13px;font-size:13px;font-family:'Be Vietnam Pro',sans-serif;color:var(--tx);outline:none"></div></div>
<label style="font-size:12px;font-weight:700;color:var(--tx);display:block;margin-bottom:10px">Hình thức thanh toán <span class="req">*</span></label>
      <div class="payment-opts">
        <label class="payment-opt" id="pay-cod" onclick="selectPay('COD')"><input type="radio" name="payment" value="COD"><span class="payment-opt-icon">💵</span><div><div class="payment-opt-text">COD</div><div class="payment-opt-sub">Trả khi nhận hàng</div></div></label>
        <label class="payment-opt active" id="pay-bank" onclick="selectPay('BANK')"><input type="radio" name="payment" value="BANK" checked><span class="payment-opt-icon">📱</span><div><div class="payment-opt-text">QR Chuyển khoản</div><div class="payment-opt-sub">Giảm 5% ngay</div></div><div class="discount-badge">-5%</div></label>
      </div>
      <div class="order-summary">
        <div class="summary-row"><span>Giá sản phẩm</span><span id="sumPrice">–</span></div>
        <div class="summary-row"><span>Số lượng</span><span id="sumQty">1</span></div>
        <div class="summary-row discount" id="discountRow" style="display:none"><span>🎉 Giảm 5% chuyển khoản</span><span id="sumDiscount">–</span></div>
        <div class="summary-row"><span>Phí giao hàng</span><span id="sumShip" style="color:var(--g)">Miễn phí</span></div>
        <div class="summary-row total"><span>Tổng thanh toán</span><span class="val" id="sumTotal">–</span></div>
      </div>
      <button class="btn-submit" id="submitBtn" onclick="handleSubmit()"><span id="submitText">Tiếp theo →</span></button>
      <p style="text-align:center;font-size:11px;color:var(--tx3);margin-top:9px">🔒 Thông tin của bạn được bảo mật</p>
    </div>
    <div class="modal-state" id="state-qr">
      <div class="qr-box">
        <div class="qr-top-info"><span class="icon">💳</span><div><strong>Quét mã QR để thanh toán</strong>Dùng app ngân hàng quét mã. Nội dung CK <b>phải ghi đúng mã đơn</b>.</div></div>
        <div class="qr-frame"><img id="qrImg" src="" alt="QR"></div>
        <div class="qr-amount" id="qrAmount">–</div>
        <div class="qr-amount-label">Số tiền cần chuyển (đã giảm 5%)</div>
        <div class="bank-info">
          <div class="bank-row"><span class="label">Ngân hàng</span><span class="val" id="bi-bank">–</span></div>
          <div class="bank-row"><span class="label">Số tài khoản</span><span class="val" id="bi-acc">–</span><button class="copy-btn" onclick="copyText('bi-acc')">Sao chép</button></div>
          <div class="bank-row"><span class="label">Chủ tài khoản</span><span class="val" id="bi-name">–</span></div>
          <div class="bank-row"><span class="label">Số tiền</span><span class="val" id="bi-amount">–</span><button class="copy-btn" onclick="copyAmountRaw()">Sao chép</button></div>
          <div class="bank-row"><span class="label">Nội dung CK</span><span class="val" id="bi-note">–</span><button class="copy-btn" onclick="copyText('bi-note')">Sao chép</button></div>
        </div>
        <div class="countdown-wrap" id="countdownBox"><div class="countdown-title">⏱ Thời gian giữ đơn hàng</div><div class="countdown-timer" id="countdownDisplay">15:00</div><div class="countdown-sub">Đơn sẽ huỷ nếu quá thời gian</div></div>
        <button class="btn-confirm-paid" id="paidBtn" onclick="customerConfirmPaid()">✅ Tôi đã chuyển khoản xong</button>
        <span class="btn-back-link" onclick="showState('form')">← Quay lại chỉnh sửa đơn hàng</span>
      </div>
    </div>
    <div class="modal-state" id="state-waiting">
      <div class="waiting-box">
        <span class="waiting-anim">⏳</span>
        <h3>Đang chờ xác nhận thanh toán</h3>
        <p>Chúng tôi đã nhận đơn hàng của bạn.<br>Vui lòng chờ shop kiểm tra tài khoản ngân hàng.</p>
        <div class="order-code-display" id="waitOrderCode">DALI-000000</div>
        <div class="waiting-steps">
          <div class="ws-item">✅ Khách hàng xác nhận đã chuyển khoản</div>
          <div class="ws-item">🔍 Shop đang kiểm tra tài khoản ngân hàng</div>
          <div class="ws-item">📦 Xác nhận xong → chuẩn bị đóng gói & giao hàng</div>
          <div class="ws-item">📞 Shop sẽ liên hệ qua SĐT bạn đã cung cấp</div>
        </div>
        <p style="font-size:12px;color:var(--g);font-weight:700">Thường xác nhận trong 15–30 phút (giờ làm việc)</p>
        <button class="btn-close-modal" onclick="closeOrder()">Đóng & tiếp tục mua sắm</button>
      </div>
    </div>
    <div class="modal-state" id="state-success">
      <div class="success-box">
        <span class="success-icon-big">🎉</span>
        <h3>Đặt hàng thành công!</h3>
        <p>Cảm ơn bạn đã tin tưởng DALI.<br>Chúng tôi sẽ liên hệ xác nhận trong vòng <strong>30 phút</strong>.</p>
        <div class="order-code-success" id="successCode">DALI-000000</div>
        <p style="font-size:12px;color:var(--tx3)">Lưu mã này để tra cứu đơn hàng tại <a href="{{ route('track-order') }}" style="color:var(--g)">đây</a></p>
        <button class="btn-close-modal" onclick="closeOrder()">Tiếp tục mua sắm</button>
      </div>
    </div>
  </div>
</div>
<div class="toast" id="toast"></div>

<script>
var gOrder={},gCountdown=null,payMode='BANK';
var CFG_BANK_ID='{{ $settings["bank_id"] ?? "VCB" }}';
var CFG_BANK_ACC='{{ $settings["bank_acc"] ?? "" }}';
var CFG_BANK_NAME='{{ $settings["bank_name"] ?? "" }}';
var CFG_BANK_LABEL='{{ $settings["bank_label"] ?? "Ngân hàng" }}';
var CFG_TG_TOKEN='{{ $settings["tg_token"] ?? "" }}';
var CFG_TG_CHAT='{{ $settings["tg_chat_id"] ?? "" }}';
function fmtVnd(n){return Math.round(n).toLocaleString('vi-VN')+'đ';}
function parseVnd(s){return parseInt((s||'').replace(/[^\d]/g,''))||0;}
function genCode(){return 'DALI-'+Date.now().toString().slice(-6);}
function openOrder(name,size,price,img,productId){
  gOrder={name:name,size:size,price:parseVnd(price),priceStr:price,img:img||'',productId:productId||null};
  document.getElementById('oImg').src=img||'';
  document.getElementById('oName').textContent=name;
  document.getElementById('oSize').textContent=size;
  document.getElementById('oPrice').textContent=price;
  document.getElementById('qtyInput').value=1;
  selectPay('BANK');updateSummary();showState('form');
  document.getElementById('orderModal').classList.add('open');
  document.body.style.overflow='hidden';
  document.getElementById('modalTitle').textContent='🛒 Đặt hàng DALI';
  document.getElementById('modalSub').textContent='Điền thông tin – xác nhận trong 30 phút';
  return false;
}
function closeOrder(){document.getElementById('orderModal').classList.remove('open');document.body.style.overflow='';if(gCountdown){clearInterval(gCountdown);gCountdown=null;}}
function showState(s){document.querySelectorAll('.modal-state').forEach(e=>e.classList.remove('active'));document.getElementById('state-'+s).classList.add('active');}
function selectPay(mode){payMode=mode;document.getElementById('pay-cod').classList.toggle('active',mode==='COD');document.getElementById('pay-bank').classList.toggle('active',mode==='BANK');updateSummary();}
function updateSummary(){
  var qty=parseInt(document.getElementById('qtyInput').value)||1;
  var sub=gOrder.price*qty;
  var disc=payMode==='BANK'?Math.round(sub*.05):0;
  var after=sub-disc;var ship=after>=299000?0:30000;var total=after+ship;
  document.getElementById('sumPrice').textContent=fmtVnd(gOrder.price);
  document.getElementById('sumQty').textContent=qty;
  document.getElementById('sumDiscount').textContent='-'+fmtVnd(disc);
  document.getElementById('discountRow').style.display=disc>0?'flex':'none';
  document.getElementById('sumShip').textContent=ship===0?'Miễn phí':fmtVnd(ship);
  document.getElementById('sumTotal').textContent=fmtVnd(total);
  gOrder.qty=qty;gOrder.sub=sub;gOrder.disc=disc;gOrder.afterDisc=after;gOrder.ship=ship;gOrder.total=total;
}
function changeQty(d){var i=document.getElementById('qtyInput');i.value=Math.min(99,Math.max(1,parseInt(i.value)+d));updateSummary();}
function handleSubmit(){
  var name=document.getElementById('custName').value.trim();
  var phone=document.getElementById('custPhone').value.trim();
  var city=document.getElementById('custCity').value;
  var addr=document.getElementById('custAddr').value.trim();
  if(!name){showToast('⚠️ Vui lòng nhập họ tên');return;}
  if(!phone){showToast('⚠️ Vui lòng nhập số điện thoại');return;}
  if(!city){showToast('⚠️ Vui lòng chọn tỉnh/thành phố');return;}
  if(!addr){showToast('⚠️ Vui lòng nhập địa chỉ cụ thể');return;}
  gOrder.custName=name;gOrder.phone=phone;gOrder.city=city;gOrder.addr=addr;
  gOrder.note=document.getElementById('custNote').value.trim();
  gOrder.payMode=payMode;gOrder.time=new Date().toLocaleString('vi-VN');
  if(payMode==='BANK'){submitOrder();}else{submitOrder();}
}
async function submitOrder(){
  var btn=document.getElementById('submitBtn');btn.disabled=true;
  document.getElementById('submitText').textContent='⏳ Đang xử lý...';
  try{
    var res=await fetch('{{ route("place-order") }}',{
      method:'POST',
      headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
      body:JSON.stringify({
        product_id:gOrder.productId,product_name:gOrder.name,product_size:gOrder.size,
        price:gOrder.price,quantity:gOrder.qty,
        customer_name:gOrder.custName,customer_phone:gOrder.phone,
        customer_city:gOrder.city,customer_addr:gOrder.addr,note:gOrder.note,
        payment:gOrder.payMode
      })
    });
    var d=await res.json();
    if(d.success){
      gOrder.code=d.code;gOrder.total=d.total;
      if(gOrder.payMode==='BANK'){goToQR();}
      else{document.getElementById('successCode').textContent=d.code;showState('success');document.getElementById('modalTitle').textContent='✅ Đặt hàng thành công';document.getElementById('modalSub').textContent='';}
    }else{showToast('❌ '+(d.message||'Có lỗi xảy ra. Vui lòng thử lại.'));}
  }catch(e){showToast('❌ Lỗi kết nối. Vui lòng thử lại.');}
  btn.disabled=false;document.getElementById('submitText').textContent='Tiếp theo →';
}
function goToQR(){
  var amt=gOrder.total;var note=gOrder.code+' DALI';
  document.getElementById('qrImg').src='https://img.vietqr.io/image/'+CFG_BANK_ID+'-'+CFG_BANK_ACC+'-qr_only.png?amount='+Math.round(amt)+'&addInfo='+encodeURIComponent(note)+'&accountName='+encodeURIComponent(CFG_BANK_NAME);
  document.getElementById('qrAmount').textContent=fmtVnd(amt);
  document.getElementById('bi-bank').textContent=CFG_BANK_LABEL;
  document.getElementById('bi-acc').textContent=CFG_BANK_ACC;
  document.getElementById('bi-name').textContent=CFG_BANK_NAME;
  document.getElementById('bi-amount').textContent=fmtVnd(amt);
  document.getElementById('bi-note').textContent=note;
  gOrder.qrAmt=amt;gOrder.qrNote=note;
  showState('qr');startCountdown();
  document.getElementById('modalTitle').textContent='💳 Thanh toán QR';
  document.getElementById('modalSub').textContent='Quét mã và chuyển khoản đúng nội dung';
}
function startCountdown(){
  if(gCountdown)clearInterval(gCountdown);
  var secs=15*60;var box=document.getElementById('countdownBox');var disp=document.getElementById('countdownDisplay');
  function tick(){var m=Math.floor(secs/60);var s=secs%60;disp.textContent=(m<10?'0':'')+m+':'+(s<10?'0':'')+s;if(secs<=120)box.classList.add('urgent');else box.classList.remove('urgent');if(secs<=0){clearInterval(gCountdown);showToast('⚠️ Hết thời gian. Vui lòng đặt lại.');}secs--;}
  tick();gCountdown=setInterval(tick,1000);
}
async function customerConfirmPaid(){
  var btn=document.getElementById('paidBtn');btn.disabled=true;btn.textContent='⏳ Đang gửi...';
  if(gCountdown){clearInterval(gCountdown);gCountdown=null;}
  try{
    await fetch('{{ route("place-order") }}/confirm',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({code:gOrder.code})});
  }catch(e){}
  document.getElementById('waitOrderCode').textContent=gOrder.code;
  showState('waiting');document.getElementById('modalTitle').textContent='⏳ Chờ xác nhận';
  document.getElementById('modalSub').textContent='Shop đang kiểm tra thanh toán';
}
function copyText(id){navigator.clipboard.writeText(document.getElementById(id).textContent).then(()=>showToast('✅ Đã sao chép!'));}
function copyAmountRaw(){navigator.clipboard.writeText(Math.round(gOrder.qrAmt).toString()).then(()=>showToast('✅ Đã sao chép số tiền'));}
function showToast(msg){var t=document.getElementById('toast');t.textContent=msg;t.classList.add('show');setTimeout(()=>t.classList.remove('show'),3000);}
function toggleMenu(){document.getElementById('mobileNav').classList.toggle('open');}
var lastST=0,navVis=true;
window.addEventListener('scroll',function(){var nav=document.getElementById('mainNav');if(!nav)return;var st=window.scrollY;if(st>200){if(st>lastST&&navVis){nav.classList.add('nav-hidden');navVis=false;}else if(st<lastST&&!navVis){nav.classList.remove('nav-hidden');navVis=true;}}lastST=st<=0?0:st;});
document.getElementById('qtyInput').addEventListener('input',updateSummary);
</script>

<script>
document.querySelectorAll('.product-fav').forEach(b=>b.addEventListener('click',function(e){e.stopPropagation();this.textContent=this.textContent==='🤍'?'❤️':'🤍';}));
</script>
@if(!empty($settings['zalo_oa_id']))
<div class="zalo-chat-widget" data-oaid="{{ $settings['zalo_oa_id'] }}" data-welcome-message="Xin chào!" data-autopopup="0" data-width="350" data-height="420"></div>
<script src="https://sp.zalo.me/plugins/sdk.js"></script>
@endif
@include('partials.float-widget')
</body>
</html>
