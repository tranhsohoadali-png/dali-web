<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="description" content="{{ $settings['meta_description'] ?? 'Bộ tranh tô màu số hóa DALI – ai cũng có thể tạo ra kiệt tác hội họa của riêng mình.' }}">
<meta property="og:title" content="{{ $settings['meta_title'] ?? 'DALI – Tô Điểm Cuộc Sống' }}">
<meta property="og:description" content="{{ $settings['meta_description'] ?? '' }}">
<meta property="og:image" content="{{ asset('images/logo_dali.png') }}">
<meta property="og:url" content="{{ url('/') }}">
<meta property="og:type" content="website">
<meta property="og:locale" content="vi_VN">
{{-- Google Analytics --}}
@if(!empty($settings['ga_id']))
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $settings['ga_id'] }}"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ $settings["ga_id"] }}');</script>
@endif
{{-- Facebook Pixel --}}
@if(!empty($settings['fb_pixel_id']))
<script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init','{{ $settings["fb_pixel_id"] }}');fbq('track','PageView');</script>
@endif
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DALI – Tô Điểm Cuộc Sống</title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet">
<style>[class^="ri-"],[class*=" ri-"]{vertical-align:-.125em;font-style:normal;line-height:1}</style>
<style>
/* ══════════════════════════════════════════
   DALI ANIME THEME  –  Trang chủ khách hàng
   Màu xanh lá DALI + phong cách anime tươi
══════════════════════════════════════════ */
:root{
  --g:    #6BBF1F;
  --gb:   #8ED63A;
  --gd:   #3E7A0A;
  --gl:   #E8F9D0;
  --gll:  #F4FDE8;
  --gn:   #C6F135;
  --pk:   #FF8FB1;
  --pkl:  #FFF0F5;
  --yl:   #FFE066;
  --bl:   #74C7FF;
  --bll:  #EEF8FF;
  --tx:   #1A4D00;
  --tx2:  #4A8A1A;
  --tx3:  #8FC860;
  --bd:   #C8E89A;
  --bd2:  #A8D870;
  --bg:   #F2FDE8;
  --wh:   #FFFFFF;
  --char: #1C3A0A;
  --shad: rgba(58,122,10,.10);
}
*{margin:0;padding:0;box-sizing:border-box}
html{scroll-behavior:smooth}
body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:var(--tx);line-height:1.6}
::-webkit-scrollbar{width:5px}
::-webkit-scrollbar-track{background:var(--bg)}
::-webkit-scrollbar-thumb{background:var(--bd2);border-radius:4px}

/* ─── ANIMATIONS ─── */
@keyframes fadeUp{from{opacity:0;transform:translateY(28px)}to{opacity:1;transform:translateY(0)}}
@keyframes slideL{from{opacity:0;transform:translateX(-36px)}to{opacity:1;transform:translateX(0)}}
@keyframes slideR{from{opacity:0;transform:translateX(36px)}to{opacity:1;transform:translateX(0)}}
@keyframes floatY{0%,100%{transform:translateY(0)}50%{transform:translateY(-18px)}}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.2}}
@keyframes drift{0%,100%{transform:translateY(0) rotate(0)}50%{transform:translateY(-5px) rotate(10deg)}}
@keyframes shimmer{0%{background-position:-600px 0}100%{background-position:600px 0}}
@keyframes heartBeat{0%,100%{transform:scale(1)}30%{transform:scale(1.35)}60%{transform:scale(1)}}
@keyframes scaleIn{from{opacity:0;transform:scale(.94)}to{opacity:1;transform:scale(1)}}
@keyframes pulse{0%,100%{transform:scale(1);opacity:1}50%{transform:scale(1.1);opacity:.7}}

.anim-up{animation:fadeUp .6s ease-out forwards}
.anim-l{animation:slideL .7s ease-out forwards}
.anim-r{animation:slideR .7s ease-out forwards}
.anim-float{animation:floatY 4s ease-in-out infinite}

/* ─── NAVBAR ─── */
nav{
  position:sticky;top:0;z-index:100;
  background:linear-gradient(175deg,#1C5200 0%,#2D7A08 55%,#3A9A12 100%);
  height:68px;padding:0 5%;
  display:flex;align-items:center;justify-content:space-between;
  transition:transform .3s,box-shadow .3s;
}
nav.nav-hidden{transform:translateY(-100%)}
nav.nav-visible{box-shadow:0 4px 20px rgba(58,122,10,.3)}

/* Logo */
.nav-logo{height:38px;width:auto;object-fit:contain;display:block;filter:brightness(0) invert(1)}
.nav-links{display:flex;gap:28px;list-style:none}
.nav-links a{
  text-decoration:none;color:rgba(255,255,255,.75);
  font-size:14px;font-weight:500;
  border-bottom:2px solid transparent;padding-bottom:2px;
  transition:all .2s;
}
.nav-links a:hover{color:#fff;border-bottom-color:var(--gn)}
.nav-ctv-link{background:rgba(198,241,53,.12);border:1.5px solid rgba(198,241,53,.35)!important;border-radius:50px!important;padding:4px 12px!important;font-size:12.5px!important;font-weight:700!important;color:var(--gn)!important;border-bottom:none!important;transition:all .2s}
.nav-ctv-link:hover{background:rgba(198,241,53,.25)!important;color:#fff!important;transform:translateY(-1px)}
.nav-right{display:flex;align-items:center;gap:12px}
.nav-phone{
  font-size:13px;font-weight:600;color:rgba(255,255,255,.85);
  text-decoration:none;transition:color .2s;
}
.nav-phone:hover{color:#fff}

/* Search */
.nav-search{position:relative;width:170px}
.nav-search input{
  width:100%;background:rgba(255,255,255,.12);
  border:1.5px solid rgba(255,255,255,.2);
  border-radius:50px;padding:7px 15px;
  font-size:13px;color:#fff;outline:none;transition:all .2s;
  font-family:'Be Vietnam Pro',sans-serif;
}
.nav-search input::placeholder{color:rgba(255,255,255,.5)}
.nav-search input:focus{background:rgba(255,255,255,.18);border-color:var(--gn)}
.nav-search-icon{position:absolute;right:13px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,.5);pointer-events:none;font-size:13px}
.search-dropdown{
  position:absolute;top:calc(100% + 8px);right:0;
  background:#fff;border-radius:14px;min-width:260px;
  max-height:320px;overflow-y:auto;
  box-shadow:0 8px 32px rgba(58,122,10,.15);
  display:none;z-index:200;border:1.5px solid var(--bd);
}
.search-dropdown.open{display:block;animation:scaleIn .2s ease-out}
.search-item{padding:11px 16px;border-bottom:1px solid var(--gl);cursor:pointer;transition:background .15s}
.search-item:hover{background:var(--gll)}
.search-item:last-child{border-bottom:none}
.si-name{font-size:13px;font-weight:600;color:var(--tx)}
.si-cat{font-size:11px;color:var(--tx3)}

/* Cart */
.nav-cart{
  position:relative;width:38px;height:38px;
  background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.25);
  border-radius:50%;color:#fff;cursor:pointer;
  display:flex;align-items:center;justify-content:center;
  font-size:17px;transition:all .2s;
}
.nav-cart:hover{background:rgba(255,255,255,.25);transform:scale(1.08)}
.cart-counter{
  position:absolute;top:-5px;right:-5px;
  background:var(--pk);color:#fff;
  border-radius:50%;width:20px;height:20px;
  display:flex;align-items:center;justify-content:center;
  font-size:10px;font-weight:800;
}
.btn-order-nav{
  background:var(--gn);color:var(--char);
  border:none;border-radius:50px;padding:9px 20px;
  font-size:13px;font-weight:800;cursor:pointer;
  text-decoration:none;display:inline-block;
  transition:all .2s;
}
.btn-order-nav:hover{background:#fff;transform:translateY(-2px);box-shadow:0 6px 18px rgba(0,0,0,.15)}
.nav-hamburger{display:none;flex-direction:column;gap:5px;cursor:pointer;background:none;border:none}
.nav-hamburger span{display:block;width:22px;height:2px;background:#fff;transition:all .3s}
.nav-hamburger.open span:nth-child(1){transform:rotate(45deg) translate(8px,8px)}
.nav-hamburger.open span:nth-child(2){opacity:0}
.nav-hamburger.open span:nth-child(3){transform:rotate(-45deg) translate(7px,-7px)}

/* Mobile nav */
.mobile-nav{
  display:none;position:fixed;top:68px;left:0;right:0;bottom:0;
  background:linear-gradient(175deg,#1C5200,#2D7A08);
  z-index:99;padding:28px 5%;flex-direction:column;gap:6px;
}
.mobile-nav.open{display:flex}
.mobile-nav a{
  font-size:17px;font-weight:600;color:rgba(255,255,255,.8);
  text-decoration:none;padding:13px 16px;
  border-bottom:1px solid rgba(255,255,255,.1);
  border-radius:8px;transition:all .2s;
}
.mobile-nav a:hover{background:rgba(255,255,255,.1);color:#fff}

/* ─── SAKURA STRIP ─── */
.sakura-strip{
  background:linear-gradient(90deg,#fff8fa,#f6ffe8,#fff,#f6ffe8,#fff8fa);
  border-bottom:1px solid #F0EBF8;
  padding:7px 5%;display:flex;align-items:center;gap:6px;
}
.petal{font-size:15px;animation:drift 5s ease-in-out infinite;display:inline-block}
.petal:nth-child(2){animation-delay:1s}
.petal:nth-child(3){animation-delay:2s}
.petal:nth-child(4){animation-delay:3s}
.petal:nth-child(5){animation-delay:4s}
.sakura-text{font-size:10px;color:#B8D8A0;letter-spacing:2.5px;font-weight:700;margin-left:8px}

/* ─── HERO ─── */
.hero{
  min-height:88vh;
  display:grid;grid-template-columns:1fr 1fr;
  align-items:center;padding:70px 5% 50px;gap:56px;
  position:relative;overflow:hidden;
  background:linear-gradient(135deg,var(--gll) 0%,#fff 60%,var(--pkl) 100%);
}
.hero::before{
  content:'';position:absolute;top:-80px;right:-80px;
  width:450px;height:450px;
  background:radial-gradient(circle,rgba(198,241,53,.12) 0%,transparent 70%);
  border-radius:50%;pointer-events:none;animation:floatY 8s ease-in-out infinite;
}
.hero::after{
  content:'';position:absolute;bottom:-60px;left:-60px;
  width:300px;height:300px;
  background:radial-gradient(circle,rgba(255,143,177,.08) 0%,transparent 70%);
  border-radius:50%;pointer-events:none;
}
.hero-badge{
  display:inline-flex;align-items:center;gap:8px;
  background:linear-gradient(135deg,var(--gl),#DAFAB0);
  color:var(--gd);padding:6px 16px;border-radius:50px;
  font-size:13px;font-weight:700;margin-bottom:20px;
  border:1px solid var(--bd2);
}
.hero h1{
  font-size:clamp(36px,4.2vw,54px);line-height:1.15;
  font-weight:900;color:var(--char);margin-bottom:20px;
}
.hero h1 em{
  background:linear-gradient(90deg,#2D7A08,var(--g),#9ED65A);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;
  font-style:normal;
}
.hero p{font-size:16px;color:var(--tx2);max-width:450px;margin-bottom:32px;line-height:1.8}
.hero-btns{display:flex;gap:14px;flex-wrap:wrap;margin-bottom:36px}
.btn-primary{
  background:linear-gradient(135deg,#3A9A12,var(--g));
  color:#fff;border:none;border-radius:50px;
  padding:13px 30px;font-size:15px;font-weight:700;
  cursor:pointer;transition:all .25s;text-decoration:none;
  display:inline-block;position:relative;overflow:hidden;
  box-shadow:0 4px 16px rgba(107,191,31,.3);
}
.btn-primary::after{
  content:'';position:absolute;top:0;left:-100%;
  width:100%;height:100%;
  background:linear-gradient(90deg,transparent,rgba(255,255,255,.2),transparent);
  transition:left .4s;
}
.btn-primary:hover{background:linear-gradient(135deg,#2E7D08,#5AAF15);transform:translateY(-2px);box-shadow:0 8px 24px rgba(107,191,31,.4)}
.btn-primary:hover::after{left:100%}
.btn-outline{
  background:transparent;color:var(--gd);
  border:2px solid var(--g);border-radius:50px;
  padding:11px 28px;font-size:15px;font-weight:700;
  cursor:pointer;transition:all .25s;
  text-decoration:none;display:inline-block;
}
.btn-outline:hover{background:var(--g);color:#fff;transform:translateY(-2px)}
.hero-stats{
  display:flex;gap:0;
  padding-top:26px;border-top:1.5px solid var(--bd);
}
.hero-stats .stat{flex:1;text-align:center;padding:2px 6px;position:relative;transition:transform .25s}
/* vạch ngăn mảnh giữa các chỉ số */
.hero-stats .stat + .stat::before{content:'';position:absolute;left:0;top:50%;transform:translateY(-50%);height:40px;width:1px;background:linear-gradient(transparent,var(--bd2),transparent)}
.hero-stats .stat:hover{transform:translateY(-4px)}
.stat-num{font-size:clamp(21px,2.5vw,28px);font-weight:900;color:var(--g);line-height:1.1;letter-spacing:-.5px;font-variant-numeric:tabular-nums;display:inline-block}
.hero-stats .stat:hover .stat-num{filter:brightness(1.08)}
.stat-label{font-size:12px;color:var(--tx3);font-weight:600;margin-top:5px;line-height:1.3}
/* xuất hiện mượt khi cuộn tới */
.hero-stats .stat{opacity:0;transform:translateY(14px)}
.hero-stats.in .stat{opacity:1;transform:translateY(0);transition:opacity .5s ease,transform .5s ease}
.hero-stats.in .stat:nth-child(2){transition-delay:.12s}
.hero-stats.in .stat:nth-child(3){transition-delay:.24s}
.hero-stats.in .stat:hover{transform:translateY(-4px)}
.hero-image{position:relative}
.hero-img-main{
  width:100%;aspect-ratio:5/4;max-height:520px;object-fit:cover;
  border-radius:22px;
  border:2px solid var(--bd);
  box-shadow:0 18px 50px rgba(58,122,10,.16);
  display:block;
}
.hero-tag{
  position:absolute;top:22px;right:-14px;
  background:#fff;border-radius:12px;
  padding:12px 18px;
  box-shadow:0 4px 20px var(--shad);
  font-size:12px;font-weight:700;color:var(--tx);
  border:1.5px solid var(--bd);
}
.hero-tag span{color:var(--g);display:block;font-size:19px;font-weight:900}

/* ─── TRUST BAR ─── */
.trust-bar{
  background:linear-gradient(90deg,#1C5200,#2D7A08,#1C5200);
  display:flex;justify-content:center;gap:50px;
  padding:16px 5%;flex-wrap:wrap;
}
.trust-item{
  display:flex;align-items:center;gap:9px;
  color:rgba(255,255,255,.85);font-size:13px;font-weight:500;
  transition:color .2s;
}
.trust-item:hover{color:#fff}

/* ─── SECTIONS ─── */
section{padding:72px 5%}
.section-header{text-align:center;margin-bottom:48px}
.section-label{
  display:inline-block;font-size:11px;font-weight:800;
  letter-spacing:2.5px;color:var(--g);text-transform:uppercase;
  margin-bottom:10px;
}
.section-header h2{
  font-size:clamp(26px,3.2vw,38px);font-weight:900;
  color:var(--char);margin-bottom:12px;
}
.section-header p{font-size:15px;color:var(--tx2);max-width:520px;margin:0 auto}

/* ─── CATEGORIES ─── */
.categories{background:var(--wh);overflow:hidden}
/* ── Marquee danh mục: tự trượt + nút sang trang ── */
.cat-marquee-wrap{position:relative;max-width:1340px;margin:0 auto;padding:0 8px}
.cat-marquee{overflow:hidden;-webkit-mask-image:linear-gradient(90deg,transparent,#000 4%,#000 96%,transparent);mask-image:linear-gradient(90deg,transparent,#000 4%,#000 96%,transparent)}
.cat-track{display:flex;gap:clamp(12px,1.4vw,20px);width:max-content;will-change:transform}
/* Nút mũi tên */
.cat-nav{position:absolute;top:50%;transform:translateY(-50%);z-index:6;width:48px;height:48px;border-radius:50%;
  background:#fff;border:1.5px solid var(--bd);color:var(--gd);font-size:26px;font-weight:700;line-height:1;
  cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 5px 18px rgba(58,122,10,.18);transition:all .2s}
.cat-nav:hover{background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border-color:var(--g);transform:translateY(-50%) scale(1.08)}
.cat-nav:active{transform:translateY(-50%) scale(.95)}
.cat-prev{left:-2px}
.cat-next{right:-2px}
@media(max-width:600px){.cat-nav{width:40px;height:40px;font-size:22px}}
.cat-card{
  position:relative;overflow:hidden;border-radius:18px;
  /* Co giãn mượt theo bề ngang màn hình: nhỏ nhất 200px, lớn nhất 300px (không nhảy giật) */
  flex:0 0 clamp(200px,30vw,300px);aspect-ratio:1/1;
  cursor:pointer;transition:transform .3s,box-shadow .3s;
  border:1.5px solid var(--bd);display:block;background:var(--gll);
}
.cat-card:hover{transform:translateY(-6px);box-shadow:0 16px 40px rgba(58,122,10,.18);border-color:var(--g)}
.cat-card img{width:100%;height:100%;object-fit:cover;transition:transform .4s;display:block}
.cat-card:hover img{transform:scale(1.05)}
/* Nhãn "Xem chủ đề" hiện khi hover (cho banner đã có chữ sẵn) */
.cat-hover{position:absolute;left:0;right:0;bottom:0;padding:14px;display:flex;justify-content:center;
  background:linear-gradient(to top,rgba(28,82,0,.85),transparent);opacity:0;transition:opacity .3s}
.cat-card:hover .cat-hover{opacity:1}
.cat-hover span{background:var(--gn);color:var(--char);font-size:13px;font-weight:800;padding:8px 18px;border-radius:50px}
/* Fallback (danh mục chưa có ảnh bìa) */
.cat-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(28,82,0,.75) 0%,transparent 55%)}
.cat-info{position:absolute;bottom:0;left:0;right:0;padding:18px;color:#fff}
.cat-info h3{font-size:17px;font-weight:800;margin-bottom:3px}
.cat-info span{font-size:12px;opacity:.8}

/* ─── PRODUCTS ─── */
.products-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(245px,1fr));gap:22px}
.product-card{
  background:#fff;border-radius:18px;overflow:hidden;
  border:1.5px solid var(--bd);
  transition:transform .3s,box-shadow .3s;
}
.product-card:hover{transform:translateY(-10px);box-shadow:0 20px 52px rgba(58,122,10,.13)}
.product-img{position:relative;aspect-ratio:1/1;overflow:hidden;background:linear-gradient(135deg,var(--gll),#fff)}
.product-img img{width:100%;height:100%;object-fit:cover;object-position:center;transition:transform .4s}
.product-card:hover .product-img img{transform:scale(1.07)}
.product-badge{
  position:absolute;top:12px;left:12px;
  background:var(--g);color:#fff;
  font-size:11px;font-weight:800;padding:5px 11px;border-radius:50px;z-index:2;
}
.product-badge.new{background:var(--bl);color:var(--char)}
.product-badge.hot{background:var(--pk);color:#fff}
.product-badge.sale{background:#FF6B6B;color:#fff}
.product-fav{
  position:absolute;top:10px;right:10px;
  width:38px;height:38px;border-radius:50%;
  background:rgba(255,255,255,.92);border:none;cursor:pointer;
  display:flex;align-items:center;justify-content:center;
  font-size:17px;box-shadow:0 3px 10px var(--shad);
  transition:all .3s;z-index:2;
}
.product-fav:hover{transform:scale(1.15)}
.product-fav.liked{background:var(--pkl)}
.product-info{padding:16px}
.product-name{font-size:14px;font-weight:700;color:var(--char);margin-bottom:5px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:39px}
.product-size{font-size:12px;color:var(--tx3);margin-bottom:9px}
.product-price{display:flex;align-items:center;gap:9px;margin-bottom:13px;flex-wrap:wrap}
.price-current{font-size:19px;font-weight:900;color:var(--g)}
.price-old{font-size:13px;color:var(--tx3);text-decoration:line-through}
.btn-buy{
  width:100%;background:linear-gradient(135deg,#3A9A12,var(--g));
  color:#fff;border:none;border-radius:10px;
  padding:11px;font-size:13px;font-weight:700;cursor:pointer;
  transition:all .2s;position:relative;overflow:hidden;
}
.btn-buy:hover{background:linear-gradient(135deg,#2E7D08,#5AAF15);transform:translateY(-1px);box-shadow:0 6px 16px rgba(107,191,31,.3)}

/* ─── STEPS ─── */
.how-it-works{background:var(--wh)}
.steps-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:28px}
.step-card{
  text-align:center;padding:28px 22px;
  background:var(--gll);border-radius:16px;
  border:1.5px solid var(--bd);
  transition:transform .3s,box-shadow .3s;
}
.step-card:hover{transform:translateY(-8px);box-shadow:0 12px 32px rgba(58,122,10,.1)}
.step-num{
  display:inline-flex;align-items:center;justify-content:center;
  width:48px;height:48px;
  background:linear-gradient(135deg,#3A9A12,var(--g));
  color:#fff;border-radius:50%;font-size:22px;font-weight:900;
  margin-bottom:14px;box-shadow:0 4px 14px rgba(107,191,31,.3);
}
.step-icon{font-size:30px;margin-bottom:10px;display:block}
.step-card h3{font-size:15px;font-weight:800;color:var(--char);margin-bottom:7px}
.step-card p{font-size:13px;color:var(--tx2);line-height:1.7}

/* ─── WHY DALI ─── */
.why-dali{background:linear-gradient(175deg,#1C5200 0%,#2D7A08 55%,#3A9A12 100%);color:#fff;position:relative;overflow:hidden}
.why-dali::before{content:'';position:absolute;top:-100px;right:-100px;width:400px;height:400px;background:radial-gradient(circle,rgba(198,241,53,.08) 0%,transparent 70%);border-radius:50%;pointer-events:none}
.why-dali .section-header h2{color:#fff}
.why-dali .section-header p{color:rgba(255,255,255,.65)}
.why-dali .section-label{color:var(--gn)}
.features-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(235px,1fr));gap:24px}
.feature-card{
  background:rgba(255,255,255,.07);
  border:1px solid rgba(255,255,255,.12);
  border-radius:18px;padding:28px;transition:background .3s,transform .3s;
}
.feature-card:hover{background:rgba(255,255,255,.12);transform:translateY(-4px)}
.feature-icon{font-size:34px;margin-bottom:14px;display:block}
.feature-card h3{font-size:16px;font-weight:800;color:#fff;margin-bottom:9px}
.feature-card p{font-size:13px;color:rgba(255,255,255,.6);line-height:1.7}

/* ─── TESTIMONIALS ─── */
.testi-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(290px,1fr));gap:22px}
.testi-card{
  background:#fff;border-radius:18px;padding:26px;
  border:1.5px solid var(--bd);
  box-shadow:0 3px 18px rgba(58,122,10,.07);
  transition:transform .3s,box-shadow .3s;
}
.testi-card:hover{transform:translateY(-6px);box-shadow:0 12px 32px rgba(58,122,10,.12)}
.stars{color:var(--yl);font-size:15px;margin-bottom:12px}
.testi-text{font-size:14px;color:var(--tx);line-height:1.8;margin-bottom:18px;font-style:italic}
.testi-author{display:flex;align-items:center;gap:11px}
.avatar-placeholder{
  width:42px;height:42px;border-radius:50%;
  background:linear-gradient(135deg,var(--gl),#CCEF90);
  color:var(--gd);display:flex;align-items:center;justify-content:center;
  font-weight:900;font-size:15px;flex-shrink:0;border:2px solid var(--bd2);
}
.author-name{font-weight:700;font-size:14px;color:var(--char)}
.author-sub{font-size:12px;color:var(--tx3)}

/* ─── CTA BANNER ─── */
.cta-banner{
  background:linear-gradient(135deg,#4CAE18 0%,#3A9A12 40%,#2D7A08 100%);
  padding:72px 5%;text-align:center;color:#fff;
  position:relative;overflow:hidden;
}
.cta-banner::before{content:'';position:absolute;top:-60px;left:50%;transform:translateX(-50%);width:400px;height:200px;background:rgba(198,241,53,.08);border-radius:50%;pointer-events:none}
.cta-banner h2{font-size:clamp(26px,3.8vw,42px);margin-bottom:14px;font-weight:900}
.cta-banner p{font-size:16px;opacity:.85;max-width:480px;margin:0 auto 32px;line-height:1.7}
.btn-white{
  background:#fff;color:var(--gd);border:none;
  border-radius:50px;padding:14px 34px;
  font-size:15px;font-weight:800;cursor:pointer;
  transition:all .2s;text-decoration:none;display:inline-block;
  box-shadow:0 4px 18px rgba(0,0,0,.15);
}
.btn-white:hover{background:var(--gll);transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,0,0,.2)}

/* ─── FOOTER ─── */
footer{
  background:linear-gradient(175deg,#0F2E00,#1C5200);
  color:rgba(255,255,255,.7);padding:60px 5% 26px;
}
.footer-grid{display:grid;grid-template-columns:1.9fr 1fr 1.3fr;gap:48px;max-width:1180px;margin:0 auto 40px;align-items:start}
.footer-logo{height:38px;width:auto;object-fit:contain;filter:brightness(0) invert(1);display:block;margin-bottom:16px}
.footer-brand p{font-size:13.5px;line-height:1.85;margin-bottom:20px;max-width:360px;color:rgba(255,255,255,.6)}
.social-links{display:flex;gap:10px}
.social-btn{
  width:42px;height:42px;border-radius:13px;
  background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.14);
  display:flex;align-items:center;justify-content:center;
  color:#fff;text-decoration:none;font-size:19px;transition:transform .2s,background .2s,border-color .2s;
}
.social-btn:hover{transform:translateY(-3px)}
.social-btn.fb:hover{background:#1877F2;border-color:#1877F2}
.social-btn.mb:hover{background:#A033FF;border-color:#A033FF}
.footer-col h4{color:#fff;font-size:14px;font-weight:800;margin-bottom:18px;letter-spacing:.3px;position:relative;padding-bottom:10px}
.footer-col h4::after{content:'';position:absolute;left:0;bottom:0;width:28px;height:2.5px;border-radius:2px;background:var(--gn)}
.footer-col ul{list-style:none}
.footer-col li{margin-bottom:11px}
.footer-col a{color:rgba(255,255,255,.55);text-decoration:none;font-size:13.5px;transition:color .2s,padding-left .2s;display:inline-block}
.footer-col a:hover{color:var(--gn);padding-left:4px}
.footer-contact p{font-size:13.5px;margin-bottom:13px;display:flex;align-items:flex-start;gap:9px;color:rgba(255,255,255,.62);line-height:1.55}
.footer-contact p i{color:var(--gn);font-size:16px;margin-top:1px;flex-shrink:0}
.footer-phone{display:inline-flex;align-items:center;gap:8px;background:rgba(123,201,42,.14);border:1px solid rgba(123,201,42,.32);
  color:var(--gn)!important;font-weight:800;font-size:15px;padding:9px 17px;border-radius:50px;text-decoration:none;margin-top:2px;transition:all .2s}
.footer-phone:hover{background:var(--g);color:#fff!important;border-color:var(--g);transform:translateY(-2px)}
.footer-bottom{
  border-top:1px solid rgba(255,255,255,.1);padding-top:22px;max-width:1180px;margin:0 auto;
  display:flex;justify-content:space-between;align-items:center;
  flex-wrap:wrap;gap:10px;font-size:12px;color:rgba(255,255,255,.35);
}
.view-more{text-align:center;margin-top:38px}

/* ─── ORDER MODAL ─── */
.modal-overlay{
  position:fixed;inset:0;background:rgba(28,82,0,.55);
  z-index:999;display:none;align-items:center;justify-content:center;
  padding:16px;backdrop-filter:blur(5px);
}
.modal-overlay.open{display:flex}
.modal{
  background:#fff;border-radius:22px;
  width:100%;max-width:570px;max-height:94vh;overflow-y:auto;
  position:relative;border:1.5px solid var(--bd);
}
/* Rainbow top */
.modal::before{
  content:'';display:block;height:4px;
  background:linear-gradient(90deg,#3A9A12 0%,var(--g) 28%,var(--gn) 52%,var(--pk) 76%,#A78BFA 100%);
  border-radius:22px 22px 0 0;
}
.modal-header{
  padding:22px 26px 16px;border-bottom:1px solid var(--gl);
  position:sticky;top:0;background:#fff;z-index:2;
}
.modal-header h2{font-size:20px;font-weight:900;color:var(--char);margin-bottom:2px}
.modal-header p{font-size:12px;color:var(--tx3)}
.modal-close{
  position:absolute;top:16px;right:20px;
  background:var(--gl);border:none;width:32px;height:32px;
  border-radius:50%;font-size:16px;cursor:pointer;
  color:var(--gd);display:flex;align-items:center;justify-content:center;
  transition:background .2s;
}
.modal-close:hover{background:var(--bd2)}
.modal-state{padding:22px 26px;display:none}
.modal-state.active{display:block}

/* Order product preview */
.order-product{
  display:flex;gap:14px;background:var(--gll);
  border-radius:12px;padding:14px;margin-bottom:18px;
  align-items:center;border:1px solid var(--bd);
}
.order-product img{width:60px;height:60px;object-fit:cover;border-radius:9px;flex-shrink:0}
.op-name{font-weight:800;font-size:14px;color:var(--char);margin-bottom:3px}
.op-size{font-size:11px;color:var(--tx3);margin-bottom:3px}
.op-price{font-size:16px;font-weight:900;color:var(--g)}

/* Form */
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px}
.form-row.single{grid-template-columns:1fr}
.form-group{display:flex;flex-direction:column;gap:5px}
.form-group label{font-size:12px;font-weight:700;color:var(--tx)}
.req{color:var(--pk)}
.form-group input,.form-group select,.form-group textarea{
  border:1.5px solid var(--bd);border-radius:9px;
  padding:10px 13px;font-size:13px;
  font-family:'Be Vietnam Pro',sans-serif;
  color:var(--tx);background:var(--gll);
  transition:border-color .2s;outline:none;
}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{
  border-color:var(--g);background:#fff;
}
.form-group textarea{resize:vertical;min-height:68px}

/* Qty */
.qty-control{display:flex;align-items:center;border:1.5px solid var(--bd);border-radius:9px;overflow:hidden;width:fit-content}
.qty-btn{background:var(--gl);border:none;width:38px;height:40px;font-size:18px;cursor:pointer;font-weight:700;color:var(--gd)}
.qty-btn:hover{background:var(--bd)}
.qty-input{
  border:none;border-left:1.5px solid var(--bd);border-right:1.5px solid var(--bd);
  width:54px;height:40px;text-align:center;font-size:15px;font-weight:800;
  color:var(--char);font-family:'Be Vietnam Pro',sans-serif;outline:none;background:#fff;
}

/* Payment opts */
.payment-opts{display:grid;grid-template-columns:1fr 1fr;gap:11px;margin-bottom:14px}
.payment-opt{
  border:2px solid var(--bd);border-radius:12px;padding:13px;
  cursor:pointer;display:flex;align-items:center;gap:9px;
  transition:all .2s;position:relative;
}
.payment-opt:hover{border-color:var(--g)}
.payment-opt.active{border-color:var(--g);background:var(--gl)}
.payment-opt input[type=radio]{display:none}
.payment-opt-icon{font-size:21px}
.payment-opt-text{font-size:13px;font-weight:700;color:var(--char);line-height:1.3}
.payment-opt-sub{font-size:11px;color:var(--tx3)}
.discount-badge{position:absolute;top:-8px;right:9px;background:var(--g);color:#fff;font-size:10px;font-weight:800;padding:2px 8px;border-radius:20px}

/* Summary */
.order-summary{background:var(--gll);border-radius:12px;padding:16px;margin:14px 0;border:1px solid var(--bd)}
.summary-row{display:flex;justify-content:space-between;align-items:center;padding:5px 0;font-size:13px}
.summary-row.discount{color:var(--gd);font-weight:700}
.summary-row.total{border-top:1.5px dashed var(--bd);padding-top:11px;margin-top:4px;font-weight:800;font-size:16px}
.summary-row.total .val{color:var(--g);font-size:18px}

/* Submit */
.btn-submit{
  width:100%;background:linear-gradient(135deg,#3A9A12,var(--g));
  color:#fff;border:none;border-radius:12px;padding:14px;
  font-size:15px;font-weight:800;cursor:pointer;
  transition:all .2s;margin-top:6px;
  display:flex;align-items:center;justify-content:center;gap:8px;
  box-shadow:0 4px 16px rgba(107,191,31,.3);
}
.btn-submit:hover{background:linear-gradient(135deg,#2E7D08,#5AAF15);transform:translateY(-1px);box-shadow:0 6px 20px rgba(107,191,31,.4)}
.btn-submit:disabled{background:#C8E89A;cursor:not-allowed;transform:none;box-shadow:none}

/* QR state */
.qr-box{text-align:center;padding:6px 0}
.qr-top-info{background:var(--gl);border-radius:12px;padding:13px 16px;margin-bottom:18px;display:flex;align-items:center;gap:11px;border:1px solid var(--bd2)}
.qr-top-info .icon{font-size:22px;flex-shrink:0}
.qr-top-info p{font-size:12px;color:var(--gd);line-height:1.5;text-align:left}
.qr-top-info strong{font-size:14px;display:block;margin-bottom:2px}
.qr-frame{display:inline-block;background:#fff;border:3px solid var(--g);border-radius:18px;padding:12px;margin:0 auto 16px;box-shadow:0 4px 20px rgba(107,191,31,.2)}
.qr-frame img{width:195px;height:195px;display:block;border-radius:7px}
.qr-amount{font-size:30px;font-weight:900;color:var(--g);margin-bottom:3px}
.qr-amount-label{font-size:12px;color:var(--tx3);margin-bottom:14px}
.bank-info{background:var(--gll);border-radius:12px;padding:14px;margin-bottom:16px;text-align:left;border:1px solid var(--bd)}
.bank-row{display:flex;justify-content:space-between;align-items:center;padding:5px 0;font-size:13px;border-bottom:1px dashed var(--bd)}
.bank-row:last-child{border-bottom:none;padding-top:9px}
.bank-row .label{color:var(--tx3);font-weight:500}
.bank-row .val{font-weight:800;color:var(--char)}
.copy-btn{background:var(--gl);color:var(--gd);border:1px solid var(--bd2);border-radius:6px;padding:3px 9px;font-size:11px;cursor:pointer;font-weight:700}
.copy-btn:hover{background:var(--g);color:#fff;border-color:var(--g)}
.countdown-wrap{background:var(--char);border-radius:12px;padding:13px;text-align:center;margin-bottom:16px;color:#fff}
.countdown-title{font-size:11px;opacity:.6;margin-bottom:3px}
.countdown-timer{font-size:26px;font-weight:900;color:var(--gn);letter-spacing:2px}
.countdown-sub{font-size:10px;opacity:.5;margin-top:3px}
.countdown-wrap.urgent{background:#991111}
.countdown-wrap.urgent .countdown-timer{color:#ffcccc}
.btn-confirm-paid{width:100%;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:12px;padding:14px;font-size:14px;font-weight:800;cursor:pointer;transition:all .2s;display:flex;align-items:center;justify-content:center;gap:7px;box-shadow:0 4px 14px rgba(107,191,31,.3)}
.btn-confirm-paid:hover{background:linear-gradient(135deg,#2E7D08,#5AAF15);transform:translateY(-1px)}
.btn-confirm-paid:disabled{background:#C8E89A;cursor:not-allowed;transform:none;box-shadow:none}
.btn-back-link{display:block;text-align:center;margin-top:11px;color:var(--tx3);font-size:12px;cursor:pointer;text-decoration:underline}
.btn-back-link:hover{color:var(--gd)}

/* Waiting & success */
.waiting-box{text-align:center;padding:28px 14px}
.waiting-anim{font-size:52px;margin-bottom:14px;display:block;animation:pulse 1.5s ease-in-out infinite}
.waiting-box h3{font-size:20px;font-weight:900;color:var(--char);margin-bottom:8px}
.waiting-box p{font-size:13px;color:var(--tx2);line-height:1.7;margin-bottom:5px}
.order-code-display{font-size:20px;font-weight:900;background:var(--bll);color:var(--bl);padding:9px 22px;border-radius:9px;display:inline-block;margin:12px 0;letter-spacing:1px}
.waiting-steps{background:var(--gll);border-radius:12px;padding:14px;margin:14px 0;text-align:left;border:1px solid var(--bd)}
.ws-item{display:flex;align-items:center;gap:9px;padding:7px 0;font-size:13px}
.ws-icon{font-size:17px;flex-shrink:0;width:22px;text-align:center}
.success-box{text-align:center;padding:32px 18px}
.success-icon-big{font-size:60px;margin-bottom:14px;display:block}
.success-box h3{font-size:24px;font-weight:900;color:var(--g);margin-bottom:8px}
.success-box p{font-size:13px;color:var(--tx2);line-height:1.7;margin-bottom:5px}
.order-code-success{font-size:18px;font-weight:900;background:var(--gl);color:var(--gd);padding:9px 22px;border-radius:9px;display:inline-block;margin:12px 0;border:1.5px solid var(--bd2)}
.btn-close-modal{background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:50px;padding:12px 30px;font-size:14px;font-weight:800;cursor:pointer;margin-top:14px;transition:all .2s;box-shadow:0 4px 14px rgba(107,191,31,.3)}
.btn-close-modal:hover{background:linear-gradient(135deg,#2E7D08,#5AAF15);transform:translateY(-1px)}

/* Toast */
.toast{position:fixed;bottom:22px;left:50%;transform:translateX(-50%) translateY(100px);background:var(--char);color:#fff;padding:11px 22px;border-radius:50px;font-size:13px;font-weight:600;z-index:9999;transition:transform .4s ease;white-space:nowrap;max-width:90vw;text-align:center}
.toast.show{transform:translateX(-50%) translateY(0)}

/* Responsive */
@media(max-width:900px){
  body{overflow-x:clip}
  .hero{grid-template-columns:1fr;padding-top:44px;gap:36px;min-height:auto}
  .hero-image{order:-1}.hero-img-main{aspect-ratio:4/3;max-height:380px}
  .footer-grid{grid-template-columns:1fr 1fr;gap:34px}
  .footer-brand{grid-column:1/-1}
  .nav-links{display:none}.nav-hamburger{display:flex}
  .trust-bar{gap:24px}
  .form-row{grid-template-columns:1fr}
  .modal-state{padding:16px 18px}
  .modal-header{padding:16px 18px}
}
@media(max-width:600px){
  .footer-grid{grid-template-columns:1fr 1fr;gap:28px 18px}
  .footer-bottom{flex-direction:column;text-align:center}
  footer{padding:48px 6% 24px}
  .footer-brand p{font-size:13px;max-width:none}
  /* ── HERO tối ưu cho mọi cỡ điện thoại (320–430px) ── */
  .hero{padding:30px 6% 36px;gap:24px}
  .hero-badge{font-size:12px;padding:6px 14px;margin-bottom:15px}
  .hero h1{font-size:clamp(23px,6.3vw,30px);line-height:1.25;margin-bottom:13px;letter-spacing:-.3px}
  .hero p{font-size:14.5px;line-height:1.7;margin-bottom:22px;max-width:none}
  .hero-btns{flex-direction:column;gap:11px;margin-bottom:26px}
  .hero-btns .btn-primary,.hero-btns .btn-outline{width:100%;text-align:center;padding:14px 20px}
  .hero-stats{padding-top:20px}
  .hero-stats .stat{padding:2px 4px}
  .stat-num{font-size:clamp(20px,6vw,25px)}
  .stat-label{font-size:clamp(10px,3vw,12px);margin-top:4px}
  .hero-stats .stat + .stat::before{height:34px}
  .hero-tag{display:none}
  .payment-opts{grid-template-columns:1fr}
  .nav-search,.nav-phone{display:none}
  nav{padding:0 4%}
  .products-grid{grid-template-columns:repeat(2,1fr);gap:12px}
  /* Nhịp section gọn hơn trên điện thoại */
  section{padding:46px 5%}
  .section-header{margin-bottom:26px}
  .section-header h2{font-size:23px;margin-bottom:8px;line-height:1.25}
  .section-header p{font-size:13.5px}
  /* Danh mục: dùng chung công thức co giãn mượt ở trên, chỉ bo góc nhẹ hơn */
  .cat-card{border-radius:16px}
  .cat-marquee-wrap{padding:0 4px}
  .cat-prev{left:-4px}.cat-next{right:-4px}
  /* Cách hoạt động: nén thành lưới 2×2 gọn */
  .steps-grid{grid-template-columns:1fr 1fr;gap:12px}
  .step-card{padding:18px 12px}
  .step-num{width:36px;height:36px;font-size:17px;margin-bottom:8px}
  .step-icon{font-size:23px;margin-bottom:5px}
  .step-card h3{font-size:13.5px;margin-bottom:5px}
  .step-card p{font-size:11.5px;line-height:1.55}
  /* Tại sao chọn DALI: 6 thẻ → lưới 2×3 gọn */
  .features-grid{grid-template-columns:1fr 1fr;gap:12px}
  .feature-card{padding:18px 14px;border-radius:14px}
  .feature-icon{font-size:27px;margin-bottom:9px}
  .feature-card h3{font-size:14px;margin-bottom:6px}
  .feature-card p{font-size:11.5px;line-height:1.55}
}
.hidden{display:none!important}
</style>
</head>
<body>

<!-- NAV -->
<nav id="mainNav">
  <a href="{{ route('home') }}"><img src="{{ asset('images/logo_dali.png') }}" alt="DALI" class="nav-logo"></a>
  <ul class="nav-links">
    <li><a href="{{ route('home') }}" class="active">Trang chủ</a></li>
    <li><a href="{{ route('products') }}">Sản phẩm</a></li>
    <li><a href="{{ route('blog') }}">Blog</a></li>
    <li><a href="#ve-chung-toi">Về chúng tôi</a></li>
    <li><a href="#lien-he">Liên hệ</a></li>
    <li><a href="{{ route('ctv.login') }}" class="nav-ctv-link"><i class="ri-team-line" style="margin-right:4px"></i>Cộng Tác Viên</a></li>
  </ul>
  <div class="nav-right">
    <a href="tel:{{ $settings['shop_phone'] ?? '0123456789' }}" class="nav-phone"><i class="ri-phone-line" style="margin-right:5px"></i>{{ $settings['shop_phone'] ?? '0123 456 789' }}</a>
    <div class="nav-search">
      <input type="text" id="navSearch" placeholder="Tìm tranh..." oninput="timKiem(event)">
      <span class="nav-search-icon"><i class="ri-search-line"></i></span>
      <div class="search-dropdown" id="searchDropdown"></div>
    </div>
    <button class="nav-cart" onclick="window.location='{{ route('cart') }}'" title="Giỏ hàng">
      <i class="ri-shopping-cart-2-line" style="font-size:19px"></i><span class="cart-counter" id="cartCount">0</span>
    </button>
    <a href="{{ route('products') }}" class="btn-order-nav">Mua ngay</a>
    <button class="nav-hamburger" id="hamburger" type="button" onclick="document.getElementById('mobileNav').classList.toggle('open');this.classList.toggle('open')"><span></span><span></span><span></span></button>
  </div>
</nav>

<div class="mobile-nav" id="mobileNav" onclick="if(event.target.closest('a')){this.classList.remove('open');document.getElementById('hamburger').classList.remove('open')}">
  <a href="{{ route('home') }}"><i class="ri-home-5-line"></i> Trang chủ</a>
  <a href="{{ route('products') }}"><i class="ri-palette-line"></i> Sản phẩm</a>
  <a href="{{ route('blog') }}"><i class="ri-quill-pen-line"></i> Blog</a>
  <a href="{{ route('cart') }}"><i class="ri-shopping-cart-2-line"></i> Giỏ hàng</a>
  <a href="{{ route('track-order') }}"><i class="ri-search-line"></i> Tra cứu đơn hàng</a>
  <a href="#lien-he"><i class="ri-phone-line"></i> Liên hệ</a>
  <a href="{{ route('ctv.login') }}" style="background:rgba(198,241,53,.15);border:1px solid rgba(198,241,53,.4);color:var(--gn)!important;border-radius:8px"><i class="ri-team-line"></i> Đăng nhập CTV</a>
</div>

<!-- SAKURA -->
<div class="sakura-strip">
  <span class="petal"><i class="ri-flower-line"></i></span><span class="petal"><i class="ri-flower-line"></i></span>
  <span class="petal"><i class="ri-leaf-line"></i></span><span class="petal"><i class="ri-flower-line"></i></span><span class="petal"><i class="ri-sparkling-line"></i></span>
  <span class="sakura-text">DALI · TÔ ĐIỂM CUỘC SỐNG</span>
</div>

<!-- HERO -->
<section class="hero">
  <div class="anim-l">
    <div class="hero-badge"><i class="ri-palette-line"></i> Thương hiệu Việt Nam – Est. 2020</div>
    <h1>Biến tranh nghệ thuật thành <em>niềm vui</em> mỗi ngày</h1>
    <p>Bộ tranh tô màu số hóa DALI – ai cũng có thể tạo ra kiệt tác hội họa của riêng mình, dù chưa từng học vẽ.</p>
    <div class="hero-btns">
      <a href="#san-pham" class="btn-primary">Khám phá tranh ngay</a>
      <a href="#huong-dan" class="btn-outline">Xem cách hoạt động</a>
    </div>
    <div class="hero-stats" id="heroStats">
      <div class="stat"><div class="stat-num" data-to="500" data-suffix="+">0</div><div class="stat-label">Mẫu tranh độc quyền</div></div>
      <div class="stat"><div class="stat-num" data-to="15" data-suffix="K+">0</div><div class="stat-label">Khách hàng hài lòng</div></div>
      <div class="stat"><div class="stat-num" data-to="4.9" data-suffix="★" data-dec="1">0</div><div class="stat-label">Đánh giá trung bình</div></div>
    </div>
  </div>
  <div class="hero-image anim-r">
    @if($hero)
    <img class="hero-img-main" src="{{ asset('storage/'.$hero->main_image) }}" alt="DALI">
    <div class="hero-tag">{{ $hero->tag_text }}<span>{{ $hero->tag_subtext }}</span></div>
    @else
    <img class="hero-img-main" src="https://images.unsplash.com/photo-1578926288207-a90a5366759d?w=700&q=80" alt="DALI">
    <div class="hero-tag"><i class="ri-ruler-2-line"></i> BỘ TRANH<span>48 Màu</span></div>
    @endif
  </div>
</section>

<!-- TRUST BAR -->
<div class="trust-bar">
  <div class="trust-item"><i class="ri-truck-line"></i> Miễn phí ship từ 299K</div>
  <div class="trust-item"><i class="ri-palette-line"></i> Màu cao cấp không độc hại</div>
  <div class="trust-item"><i class="ri-bank-card-line"></i> Giảm {{ (int)($settings['discount_bank'] ?? 5) }}% khi chuyển khoản</div>
</div>

<!-- CATEGORIES -->
{{-- ────────────────────────────────────────
   SECTION: CATEGORIES – lấy từ DB
   Thay thế toàn bộ phần .categories cũ
──────────────────────────────────────── --}}
<section class="categories" id="san-pham">
  <div class="section-header">
    <span class="section-label">Danh mục</span>
    <h2>Tìm tranh theo chủ đề yêu thích</h2>
    <p>Từ phong cảnh thiên nhiên đến chân dung, từ hoa lá đến thành phố.</p>
  </div>
  @if($categories->count())
  <div class="cat-marquee-wrap">
    <button class="cat-nav cat-prev" type="button" aria-label="Danh mục trước" onclick="catJump(1)">‹</button>
    <button class="cat-nav cat-next" type="button" aria-label="Danh mục tiếp" onclick="catJump(-1)">›</button>
    <div class="cat-marquee">
    {{-- Nhân đôi danh sách (concat) để trượt lặp liền mạch --}}
    <div class="cat-track" id="catTrack">
      @foreach($categories->concat($categories) as $cat)
      <a class="cat-card" href="{{ route('category', $cat->slug) }}" style="text-decoration:none" aria-hidden="{{ $loop->index >= $categories->count() ? 'true' : 'false' }}">
        @if($cat->image)
          <img src="{{ asset('storage/'.$cat->image) }}" alt="{{ $cat->name }}" loading="lazy">
          <div class="cat-hover"><span><i class="ri-arrow-right-line"></i> Xem chủ đề {{ $cat->name }}</span></div>
        @else
          <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&q=80" alt="" loading="lazy">
          <div class="cat-overlay"></div>
          <div class="cat-info">
            <h3>{{ $cat->icon }} {{ $cat->name }}</h3>
            <span>{{ $cat->products_count }} mẫu tranh</span>
          </div>
        @endif
      </a>
      @endforeach
    </div>
    </div>
  </div>
  @else
  <div style="text-align:center;padding:40px;color:var(--tx3)">
    Chưa có danh mục. <a href="/admin/categories/create" style="color:var(--g);font-weight:700">Thêm ngay →</a>
  </div>
  @endif
</section>

<script>
(function(){
  var track=document.getElementById('catTrack'); if(!track) return;
  var wrap=track.parentNode;            // .cat-marquee
  var pos=0, setW=0, speed=0, paused=false;
  function measure(){ setW=track.scrollWidth/2; speed=setW/(90*60); }  // 1 vòng ~90s
  function wrapPos(){ if(pos<=-setW) pos+=setW; if(pos>0) pos-=setW; }
  function apply(){ track.style.transform='translateX('+pos+'px)'; }
  measure(); window.addEventListener('resize', function(){ measure(); });
  var reduce=window.matchMedia&&window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  function frame(){ if(!paused&&!reduce){ pos-=speed; wrapPos(); apply(); } requestAnimationFrame(frame); }
  requestAnimationFrame(frame);
  var wrapEl=wrap.parentNode;           // .cat-marquee-wrap
  wrapEl.addEventListener('mouseenter', function(){ paused=true; });
  wrapEl.addEventListener('mouseleave', function(){ paused=false; });
  // Nút sang trang: nhảy ~2 thẻ mỗi lần bấm (tức thì, không bị khoảng trắng)
  window.catJump=function(dir){
    var card=track.querySelector('.cat-card');
    var gap=parseFloat(getComputedStyle(track).columnGap||getComputedStyle(track).gap)||16;
    var step=card?(card.offsetWidth+gap)*2:560;
    pos+=dir*step; wrapPos(); apply();
  };
})();
// ── Hiệu ứng đếm số cho 3 chỉ số hero (chạy 1 lần khi cuộn tới) ──
(function(){
  var box=document.getElementById('heroStats'); if(!box) return;
  function fmt(v,dec){ return dec>0 ? v.toFixed(dec) : Math.round(v).toLocaleString('vi-VN'); }
  function run(){
    box.classList.add('in');
    box.querySelectorAll('.stat-num').forEach(function(el){
      var to=parseFloat(el.dataset.to)||0, dec=parseInt(el.dataset.dec||0), suf=el.dataset.suffix||'';
      var dur=1200, t0=null;
      function step(ts){
        if(!t0)t0=ts; var p=Math.min(1,(ts-t0)/dur);
        var e=1-Math.pow(1-p,3);            // easeOutCubic
        el.textContent=fmt(to*e,dec)+suf;
        if(p<1) requestAnimationFrame(step); else el.textContent=fmt(to,dec)+suf;
      }
      requestAnimationFrame(step);
    });
  }
  if('IntersectionObserver' in window){
    var io=new IntersectionObserver(function(es){ es.forEach(function(en){ if(en.isIntersecting){ run(); io.disconnect(); } }); },{threshold:.4});
    io.observe(box);
  } else { run(); }
})();
</script>

{{-- ────────────────────────────────────────
   SECTION: PRODUCTS – lấy từ DB
──────────────────────────────────────── --}}
<section style="background:var(--wh)">
  <div class="section-header">
    <span class="section-label"><i class="ri-fire-line"></i> Bán chạy nhất</span>
    <h2>Tranh được yêu thích nhất tháng này</h2>
    <p>Được hàng nghìn khách hàng lựa chọn và đánh giá cao nhất.</p>
  </div>

  <div class="products-grid" id="productsGrid">
    @forelse($bestSellers as $p)
    <div class="product-card" data-cat="{{ $p->category_id }}">
      <a href="{{ route('product', $p->slug) }}" class="product-img" style="display:block;text-decoration:none">
        @if($p->main_image)
          <img src="{{ asset('storage/'.$p->main_image) }}" alt="{{ $p->name }}" loading="lazy">
        @else
          <img src="https://images.unsplash.com/photo-1578926288207-a90a5366759d?w=400&q=80" alt="{{ $p->name }}" loading="lazy">
        @endif
        @if($p->badge)
          <div class="product-badge {{ $p->badge_type == 'new' ? 'new' : ($p->badge_type == 'hot' ? 'hot' : ($p->badge_type == 'sale' ? 'sale' : '')) }}">{{ $p->badge }}</div>
        @endif
      </a>
      <div class="product-info">
        <a href="{{ route('product', $p->slug) }}" class="product-name" style="text-decoration:none;color:inherit">{{ $p->name }}</a>
        <div class="product-size">{{ $p->colors_count ? $p->colors_count.' màu' : '' }}{{ $p->sizes()->count() ? ($p->colors_count ? ' · ' : '').$p->sizes()->count().' kích thước' : '' }}</div>
        <div class="product-price">
          @if($p->has_multiple_sizes)<span style="font-size:12px;color:var(--tx3);font-weight:600">Từ</span>@endif
          <span class="price-current">{{ $p->display_price }}</span>
        </div>
        <button class="btn-buy" onclick="window.location='{{ route('product', $p->slug) }}'">
          <i class="ri-shopping-cart-2-line"></i> Đặt mua ngay
        </button>
      </div>
    </div>
    @empty
    <div style="grid-column:1/-1;text-align:center;padding:60px 20px;color:var(--tx3)">
      <div style="font-size:48px;margin-bottom:16px"><i class="ri-palette-line"></i></div>
      <div style="font-size:16px;font-weight:700;margin-bottom:8px">Chưa có sản phẩm nào</div>
      <div style="font-size:14px">Quản trị viên có thể thêm sản phẩm tại <a href="/admin/products/create" style="color:var(--g);font-weight:700">trang admin →</a></div>
    </div>
    @endforelse
  </div>

  @if($products->count() > 8)
  <div class="view-more"><a href="{{ route('products') }}" class="btn-primary">Xem tất cả sản phẩm →</a></div>
  @endif
</section>

{{-- ── THÊM CSS + JS LỌC DANH MỤC vào phần <style> hoặc cuối file --}}
<style>
.cat-tab{
  padding:9px 18px;border-radius:50px;
  border:1.5px solid var(--bd);background:#fff;
  font-size:13px;font-weight:700;color:var(--tx2);
  cursor:pointer;transition:all .2s;
  font-family:'Be Vietnam Pro',sans-serif;
}
.cat-tab:hover{border-color:var(--g);color:var(--g)}
.cat-tab.active{background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border-color:var(--g);box-shadow:0 3px 12px rgba(107,191,31,.3)}
.product-card[style*="none"]{display:none}
.hidden{display:none!important}
</style>
<script>
function filterByCategory(catId){
  // Cập nhật tab active
  document.querySelectorAll('.cat-tab').forEach(t=>{
    t.classList.toggle('active', t.dataset.cat == catId);
  });
  // Lọc product cards
  document.querySelectorAll('#productsGrid .product-card').forEach(card=>{
    if(catId === 'all' || card.dataset.cat == catId){
      card.style.display='';
    } else {
      card.style.display='none';
    }
  });
  // Scroll mượt về grid
  document.getElementById('productsGrid').scrollIntoView({behavior:'smooth',block:'start'});
}
</script>

<!-- STEPS -->
<section class="how-it-works" id="huong-dan">
  <div class="section-header"><span class="section-label">Cách hoạt động</span><h2>Chỉ 4 bước để có tác phẩm của riêng bạn</h2></div>
  <div class="steps-grid">
    <div class="step-card"><span class="step-icon"><i class="ri-shopping-bag-3-line"></i></span><div class="step-num">1</div><h3>Chọn tranh yêu thích</h3><p>Duyệt qua hơn 500 mẫu tranh và đặt hàng trực tiếp trên website.</p></div>
    <div class="step-card"><span class="step-icon"><i class="ri-box-3-line"></i></span><div class="step-num">2</div><h3>Nhận bộ tranh tại nhà</h3><p>Bộ tranh đầy đủ: canvas, màu đánh số, cọ vẽ và sổ hướng dẫn.</p></div>
    <div class="step-card"><span class="step-icon"><i class="ri-brush-line"></i></span><div class="step-num">3</div><h3>Tô màu theo số</h3><p>Mỗi ô có số tương ứng với màu sơn. Tô theo hướng dẫn – không thể sai!</p></div>
    <div class="step-card"><span class="step-icon"><i class="ri-image-line"></i></span><div class="step-num">4</div><h3>Treo tranh &amp; tự hào</h3><p>Đóng khung và trưng bày như một họa sĩ thực thụ.</p></div>
  </div>
</section>

<!-- WHY DALI -->
<section class="why-dali" id="ve-chung-toi">
  <div class="section-header"><span class="section-label">Tại sao chọn DALI?</span><h2>Chất lượng tạo nên sự khác biệt</h2><p>DALI không chỉ bán tranh – chúng tôi mang đến trải nghiệm sáng tạo đích thực.</p></div>
  <div class="features-grid">
    <div class="feature-card"><span class="feature-icon"><i class="ri-palette-line"></i></span><h3>Màu sơn cao cấp</h3><p>Màu acrylic cao cấp, không độc hại, bền màu 10+ năm.</p></div>
    <div class="feature-card"><span class="feature-icon"><i class="ri-image-line"></i></span><h3>Canvas chất lượng cao</h3><p>Canvas 100% cotton dày 280g, đã căng khung sẵn.</p></div>
    <div class="feature-card"><span class="feature-icon"><i class="ri-list-ordered-2"></i></span><h3>Số màu rõ nét</h3><p>Công nghệ in UV sắc nét, số màu không bị che khuất khi tô.</p></div>
    <div class="feature-card"><span class="feature-icon"><i class="ri-gift-line"></i></span><h3>Quà tặng ý nghĩa</h3><p>Hộp quà cao cấp – món quà độc đáo cho mọi dịp đặc biệt.</p></div>
    <div class="feature-card"><span class="feature-icon"><i class="ri-phone-line"></i></span><h3>Hỗ trợ tận tâm</h3><p>Đội ngũ hỗ trợ 7 ngày/tuần qua Zalo, Facebook và hotline.</p></div>
    <div class="feature-card"><span class="feature-icon"><i class="ri-bank-card-line"></i></span><h3>Giảm {{ (int)($settings['discount_bank'] ?? 5) }}% chuyển khoản</h3><p>Thanh toán trước qua QR ngân hàng – được giảm {{ (int)($settings['discount_bank'] ?? 5) }}% toàn bộ đơn hàng.</p></div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section style="background:var(--gll)">
  <div class="section-header"><span class="section-label">Khách hàng nói gì</span><h2>Hàng nghìn người đã trải nghiệm DALI</h2></div>
  <div class="testi-grid">
    <div class="testi-card"><div class="stars">★★★★★</div><p class="testi-text">"Tôi không ngờ mình lại vẽ được đẹp đến vậy! Sau 1 tuần kiên trì, bức tranh hoa sen treo phòng khách ai nhìn cũng khen."</p><div class="testi-author"><div class="avatar-placeholder">LN</div><div><div class="author-name">Lê Thị Ngọc</div><div class="author-sub">Hà Nội · Hoa Sen 40×50</div></div></div></div>
    <div class="testi-card"><div class="stars">★★★★★</div><p class="testi-text">"Mua làm quà sinh nhật cho mẹ, mẹ thích lắm! Chất lượng hộp quà rất sang, màu sơn mịn và cọ vẽ tốt hơn tôi tưởng."</p><div class="testi-author"><div class="avatar-placeholder">TA</div><div><div class="author-name">Trần Minh Anh</div><div class="author-sub">TP.HCM · Hộp quà tặng</div></div></div></div>
    <div class="testi-card"><div class="stars">★★★★★</div><p class="testi-text">"Chuyển khoản QR rất tiện, được giảm 5% luôn. Hàng giao đúng 2 ngày, đóng gói cẩn thận. Rất hài lòng!"</p><div class="testi-author"><div class="avatar-placeholder">PH</div><div><div class="author-name">Phạm Thị Hà</div><div class="author-sub">Đà Nẵng · Paris Về Đêm</div></div></div></div>
  </div>
</section>

<!-- CTA -->
<div class="cta-banner">
  <h2><i class="ri-gift-line"></i> Đặt hàng ngay hôm nay</h2>
  <p>Chuyển khoản QR – giảm thêm {{ (int)($settings['discount_bank'] ?? 5) }}%. Giao toàn quốc trong 2–3 ngày.</p>
  <a href="{{ route('products') }}" class="btn-white">Xem tất cả sản phẩm →</a>
</div>

<!-- FOOTER -->
<footer id="lien-he">
  <div class="footer-grid">
    <div class="footer-brand">
      <img src="{{ asset('images/logo_dali.png') }}" alt="DALI" class="footer-logo">
      <p>DALI – Thương hiệu tranh tô màu số hóa hàng đầu Việt Nam. Chúng tôi tin rằng mọi người đều có tâm hồn nghệ sĩ.</p>
      <div class="social-links">
        <a href="https://www.facebook.com/tranhtomau.dali" target="_blank" rel="noopener" class="social-btn fb" aria-label="Facebook"><i class="ri-facebook-fill"></i></a>
        <a href="https://m.me/tranhtomau.dali" target="_blank" rel="noopener" class="social-btn mb" aria-label="Messenger"><i class="ri-messenger-fill"></i></a>
      </div>
    </div>
    <div class="footer-col"><h4>Sản phẩm</h4><ul>
      <li><a href="{{ route('products') }}">Tất cả sản phẩm</a></li>
      <li><a href="{{ route('products') }}">Tranh phong cảnh</a></li>
      <li><a href="{{ route('products') }}">Tranh hoa &amp; thực vật</a></li>
      <li><a href="{{ route('products') }}">Tranh động vật</a></li>
      <li><a href="{{ route('track-order') }}">Tra cứu đơn hàng</a></li>
    </ul></div>
    <div class="footer-col footer-contact"><h4>Liên hệ</h4>
      <p><i class="ri-map-pin-line"></i> {{ $settings['shop_address'] ?? 'Số 1 Đường ABC, Hà Nội' }}</p>
      <p><i class="ri-time-line"></i> T2–T7: 8:00 – 20:00</p>
      <a href="tel:{{ $settings['shop_phone'] ?? '0123456789' }}" class="footer-phone"><i class="ri-phone-fill"></i> {{ $settings['shop_phone'] ?? '0123 456 789' }}</a>
    </div>
  </div>
  <div class="footer-bottom"><span>© 2024 DALI Tranh Tô Màu Số Hóa</span><span>Thiết kế tại Việt Nam 🇻🇳</span></div>
</footer>

<!-- ORDER MODAL -->
<div class="modal-overlay" id="orderModal" onclick="if(event.target===this)closeOrder()">
  <div class="modal">
    <div class="modal-header">
      <h2 id="modalTitle"><i class="ri-shopping-cart-2-line"></i> Đặt hàng DALI</h2>
      <p id="modalSub">Điền thông tin – xác nhận trong 30 phút</p>
      <button class="modal-close" onclick="closeOrder()">✕</button>
    </div>
    <div class="modal-state active" id="state-form">
      <div class="order-product">
        <img id="oImg" src="" alt="">
        <div><div class="op-name" id="oName">–</div><div class="op-size" id="oSize">–</div><div class="op-price" id="oPrice">–</div></div>
      </div>
      <div style="margin-bottom:14px">
        <label style="font-size:12px;font-weight:700;color:var(--tx);display:block;margin-bottom:7px">Số lượng</label>
        <div class="qty-control"><button class="qty-btn" onclick="changeQty(-1)">−</button><input class="qty-input" id="qtyInput" type="number" value="1" min="1" max="99"><button class="qty-btn" onclick="changeQty(1)">+</button></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Họ và tên <span class="req">*</span></label><input type="text" id="custName" placeholder="Nguyễn Văn A"></div>
        <div class="form-group"><label>Số điện thoại <span class="req">*</span></label><input type="tel" id="custPhone" placeholder="0912 345 678"></div>
      </div>
      <div class="form-row single">
        <div class="form-group"><label>Tỉnh / Thành phố <span class="req">*</span></label>
          <select id="custCity"><option value="">— Chọn tỉnh/thành —</option><option>Hà Nội</option><option>TP. Hồ Chí Minh</option><option>Đà Nẵng</option><option>Hải Phòng</option><option>Cần Thơ</option><option>An Giang</option><option>Bà Rịa – Vũng Tàu</option><option>Bắc Giang</option><option>Bắc Ninh</option><option>Bến Tre</option><option>Bình Định</option><option>Bình Dương</option><option>Bình Phước</option><option>Bình Thuận</option><option>Cà Mau</option><option>Cao Bằng</option><option>Đắk Lắk</option><option>Đắk Nông</option><option>Điện Biên</option><option>Đồng Nai</option><option>Đồng Tháp</option><option>Gia Lai</option><option>Hà Giang</option><option>Hà Nam</option><option>Hà Tĩnh</option><option>Hải Dương</option><option>Hậu Giang</option><option>Hòa Bình</option><option>Hưng Yên</option><option>Khánh Hòa</option><option>Kiên Giang</option><option>Kon Tum</option><option>Lai Châu</option><option>Lâm Đồng</option><option>Lạng Sơn</option><option>Lào Cai</option><option>Long An</option><option>Nam Định</option><option>Nghệ An</option><option>Ninh Bình</option><option>Ninh Thuận</option><option>Phú Thọ</option><option>Phú Yên</option><option>Quảng Bình</option><option>Quảng Nam</option><option>Quảng Ngãi</option><option>Quảng Ninh</option><option>Quảng Trị</option><option>Sóc Trăng</option><option>Sơn La</option><option>Tây Ninh</option><option>Thái Bình</option><option>Thái Nguyên</option><option>Thanh Hóa</option><option>Thừa Thiên Huế</option><option>Tiền Giang</option><option>Trà Vinh</option><option>Tuyên Quang</option><option>Vĩnh Long</option><option>Vĩnh Phúc</option><option>Yên Bái</option></select>
        </div>
      </div>
      <div class="form-row single"><div class="form-group"><label>Địa chỉ cụ thể <span class="req">*</span></label><input type="text" id="custAddr" placeholder="Số nhà, tên đường, phường/xã, quận/huyện"></div></div>
      <div class="form-row single"><div class="form-group"><label>Ghi chú</label><textarea id="custNote" placeholder="Gọi trước khi giao, đóng gói kỹ hơn..."></textarea></div></div>
      
      {{-- COUPON --}}
      <div class="form-row single" id="couponRow">
        <div class="form-group">
          <label>Mã giảm giá</label>
          <div style="display:flex;gap:8px">
            <input type="text" id="couponInput" placeholder="Nhập mã nếu có..." style="flex:1;border:1.5px solid var(--bd);border-radius:9px;padding:10px 13px;font-size:13px;font-family:'Be Vietnam Pro',sans-serif;color:var(--tx);background:var(--gll);outline:none;text-transform:uppercase">
            <button type="button" onclick="applyCoupon()" style="padding:10px 16px;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:9px;font-size:13px;font-weight:700;cursor:pointer;white-space:nowrap">Áp dụng</button>
          </div>
          <div id="couponMsg" style="display:none;margin-top:5px;font-size:12px;font-weight:600"></div>
        </div>
      </div>

      
      <div class="form-row single">
        <div class="form-group">
          <label>Email <span style="font-size:10px;color:var(--tx3);font-weight:400">(nhận xác nhận đơn hàng)</span></label>
          <input type="email" id="custEmail" placeholder="email@gmail.com">
        </div>
      </div>
<label style="font-size:12px;font-weight:700;color:var(--tx);display:block;margin-bottom:10px">Hình thức thanh toán <span class="req">*</span></label>
      <div class="payment-opts">
        <label class="payment-opt" id="pay-cod" onclick="selectPay('COD')"><input type="radio" name="payment" value="COD"><span class="payment-opt-icon"><i class="ri-money-dollar-circle-line"></i></span><div><div class="payment-opt-text">COD</div><div class="payment-opt-sub">Trả khi nhận hàng</div></div></label>
        <label class="payment-opt active" id="pay-bank" onclick="selectPay('BANK')"><input type="radio" name="payment" value="BANK" checked><span class="payment-opt-icon"><i class="ri-smartphone-line"></i></span><div><div class="payment-opt-text">QR Chuyển khoản</div><div class="payment-opt-sub">Giảm {{ (int)($settings['discount_bank'] ?? 5) }}% ngay</div></div><div class="discount-badge">-{{ (int)($settings['discount_bank'] ?? 5) }}%</div></label>
      </div>
      <div class="order-summary">
        <div class="summary-row"><span>Giá sản phẩm</span><span id="sumPrice">–</span></div>
        <div class="summary-row"><span>Số lượng</span><span id="sumQty">1</span></div>
        <div class="summary-row discount" id="discountRow" style="display:none"><span>🎉 Giảm {{ (int)($settings['discount_bank'] ?? 5) }}% chuyển khoản</span><span id="sumDiscount">–</span></div>
        <div class="summary-row discount" id="couponDiscRow" style="display:none"><span><i class="ri-price-tag-3-line"></i> Mã giảm giá</span><span id="sumCouponDiscount">–</span></div>
        <div class="summary-row"><span>Phí giao hàng</span><span id="sumShip" style="color:var(--g)">Miễn phí</span></div>
        <div class="summary-row total"><span>Tổng thanh toán</span><span class="val" id="sumTotal">–</span></div>
      </div>
      <button class="btn-submit" id="submitBtn" onclick="handleSubmit()"><span id="submitText">Tiếp theo →</span></button>
      <p style="text-align:center;font-size:11px;color:var(--tx3);margin-top:10px"><i class="ri-lock-line"></i> Thông tin của bạn được bảo mật hoàn toàn</p>
    </div>
    <div class="modal-state" id="state-qr">
      <div class="qr-box">
        <div class="qr-top-info"><span class="icon"><i class="ri-bank-card-line"></i></span><div><strong>Quét mã QR để thanh toán</strong>Dùng app ngân hàng quét mã hoặc chuyển khoản thủ công. Nội dung CK <b>phải ghi đúng mã đơn</b>.</div></div>
        <div class="qr-frame"><img id="qrImg" src="" alt="QR"></div>
        <div class="qr-amount" id="qrAmount">–</div>
        <div class="qr-amount-label">Số tiền cần chuyển (đã giảm {{ (int)($settings['discount_bank'] ?? 5) }}%)</div>
        <div class="bank-info">
          <div class="bank-row"><span class="label">Ngân hàng</span><span class="val" id="bi-bank">–</span></div>
          <div class="bank-row"><span class="label">Số tài khoản</span><span class="val" id="bi-acc">–</span><button class="copy-btn" onclick="copyText('bi-acc')">Sao chép</button></div>
          <div class="bank-row"><span class="label">Chủ tài khoản</span><span class="val" id="bi-name">–</span></div>
          <div class="bank-row"><span class="label">Số tiền</span><span class="val" id="bi-amount">–</span><button class="copy-btn" onclick="copyAmountRaw()">Sao chép</button></div>
          <div class="bank-row"><span class="label">Nội dung CK</span><span class="val" id="bi-note">–</span><button class="copy-btn" onclick="copyText('bi-note')">Sao chép</button></div>
        </div>
        <div class="countdown-wrap" id="countdownBox"><div class="countdown-title"><i class="ri-timer-line"></i> Thời gian giữ đơn hàng</div><div class="countdown-timer" id="countdownDisplay">15:00</div><div class="countdown-sub">Đơn sẽ huỷ nếu quá thời gian trên</div></div>
        <button class="btn-confirm-paid" id="paidBtn" onclick="customerConfirmPaid()"><i class="ri-checkbox-circle-line"></i> Tôi đã chuyển khoản xong</button>
        <span class="btn-back-link" onclick="showState('form')">← Quay lại chỉnh sửa đơn hàng</span>
      </div>
    </div>
    <div class="modal-state" id="state-waiting">
      <div class="waiting-box">
        <span class="waiting-anim">⏳</span>
        <h3>Đang chờ xác nhận thanh toán</h3>
        <p>Chúng tôi đã nhận được thông tin đơn hàng của bạn.<br>Vui lòng chờ shop kiểm tra tài khoản ngân hàng.</p>
        <div class="order-code-display" id="waitOrderCode">DALI-000000</div>
        <div class="waiting-steps">
          <div class="ws-item"><span class="ws-icon"><i class="ri-checkbox-circle-line"></i></span> Khách hàng xác nhận đã chuyển khoản</div>
          <div class="ws-item"><span class="ws-icon"><i class="ri-search-line"></i></span> Shop đang kiểm tra tài khoản ngân hàng</div>
          <div class="ws-item"><span class="ws-icon"><i class="ri-box-3-line"></i></span> Xác nhận xong → chuẩn bị đóng gói & giao hàng</div>
          <div class="ws-item"><span class="ws-icon"><i class="ri-phone-line"></i></span> Shop sẽ liên hệ xác nhận qua SĐT của bạn</div>
        </div>
        <p style="font-size:12px;color:var(--g);font-weight:700">Thường xác nhận trong vòng 15–30 phút (giờ làm việc)</p>
        <button class="btn-close-modal" onclick="closeOrder()">Đóng & tiếp tục mua sắm</button>
      </div>
    </div>
    <div class="modal-state" id="state-success">
      <div class="success-box">
        <span class="success-icon-big">🎉</span>
        <h3>Đặt hàng thành công!</h3>
        <p>Cảm ơn bạn đã tin tưởng DALI.<br>Chúng tôi sẽ liên hệ xác nhận trong vòng <strong>30 phút</strong>.</p>
        <div class="order-code-success" id="successCode">DALI-000000</div>
        <p style="font-size:12px;color:var(--tx3)">Lưu mã này để tra cứu đơn hàng</p>
        <button class="btn-close-modal" onclick="closeOrder()">Tiếp tục mua sắm</button>
      </div>
    </div>
  </div>
</div>
<div class="toast" id="toast"></div>

<script>
// ================================================================
//  === CẤU HÌNH SHOP – ĐIỀN THÔNG TIN CỦA BẠN VÀO ĐÂY ===
// ================================================================
var gOrder={},gCountdown=null,payMode='BANK';
var CFG_BANK_ID='{{ $settings["bank_id"] ?? "VCB" }}';
var CFG_BANK_ACC='{{ $settings["bank_acc"] ?? "" }}';
var CFG_BANK_NAME='{{ $settings["bank_name"] ?? "" }}';
var CFG_BANK_LABEL='{{ $settings["bank_label"] ?? "Ngân hàng" }}';
// ================================================================

var gOrder={},gCountdown=null,payMode='BANK';
function fmtVnd(n){return Math.round(n).toLocaleString('vi-VN')+'đ';}
function parseVnd(s){return parseInt((s||'').replace(/[^\d]/g,''))||0;}
function genCode(){return 'DALI-'+Date.now().toString().slice(-6);}

function openOrder(name,size,price,img){
  gOrder={name:name,size:size,price:parseVnd(price),priceStr:price,img:img};
  document.getElementById('oImg').src=img;
  document.getElementById('oName').textContent=name;
  document.getElementById('oSize').textContent=size;
  document.getElementById('oPrice').textContent=price;
  document.getElementById('qtyInput').value=1;
  gCoupon=null;
  if(document.getElementById('couponInput'))document.getElementById('couponInput').value='';
  if(document.getElementById('couponMsg'))document.getElementById('couponMsg').style.display='none';
  selectPay('BANK');updateSummary();showState('form');
  document.getElementById('orderModal').classList.add('open');
  document.body.style.overflow='hidden';
  document.getElementById('modalTitle').textContent='🛒 Đặt hàng DALI';
  document.getElementById('modalSub').textContent='Điền thông tin – xác nhận trong 30 phút';
  return false;
}
function closeOrder(){
  document.getElementById('orderModal').classList.remove('open');
  document.body.style.overflow='';
  if(gCountdown){clearInterval(gCountdown);gCountdown=null;}
}
function showState(s){
  document.querySelectorAll('.modal-state').forEach(function(el){el.classList.remove('active');});
  document.getElementById('state-'+s).classList.add('active');
}
function selectPay(mode){
  payMode=mode;
  document.getElementById('pay-cod').classList.toggle('active',mode==='COD');
  document.getElementById('pay-bank').classList.toggle('active',mode==='BANK');
  updateSummary();
}
function updateSummary(){
  var qty=parseInt(document.getElementById('qtyInput').value)||1;
  var sub=gOrder.price*qty;
  var disc=payMode==='BANK'?Math.round(sub*CFG.discountPct/100):0;
  var afterDisc=sub-disc;
  var ship=afterDisc>=CFG.freeShipFrom?0:CFG.shipFee;
  var total=afterDisc+ship;
  document.getElementById('sumPrice').textContent=fmtVnd(gOrder.price);
  document.getElementById('sumQty').textContent=qty;
  document.getElementById('sumDiscount').textContent='-'+fmtVnd(disc);
  document.getElementById('discountRow').style.display=disc>0?'flex':'none';
  document.getElementById('sumShip').textContent=ship===0?'Miễn phí':fmtVnd(ship);
  document.getElementById('sumTotal').textContent=fmtVnd(total);
  gOrder.qty=qty;gOrder.sub=sub;gOrder.disc=disc;gOrder.afterDisc=afterDisc;gOrder.ship=ship;gOrder.total=total;
}
function changeQty(d){
  var i=document.getElementById('qtyInput');
  i.value=Math.min(99,Math.max(1,parseInt(i.value)+d));updateSummary();
}
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
  gOrder.code=genCode();gOrder.payMode=payMode;gOrder.time=new Date().toLocaleString('vi-VN');
  submitOrder();
}

async function submitOrder(){
  var name=document.getElementById('custName').value.trim();
  var phone=document.getElementById('custPhone').value.trim();
  var city=document.getElementById('custCity').value;
  var addr=document.getElementById('custAddr').value.trim();
  if(!name){showToast('⚠️ Vui lòng nhập họ tên');return;}
  if(!phone){showToast('⚠️ Vui lòng nhập số điện thoại');return;}
  if(!city){showToast('⚠️ Vui lòng chọn tỉnh/thành phố');return;}
  if(!addr){showToast('⚠️ Vui lòng nhập địa chỉ cụ thể');return;}
  gOrder.custName=name;gOrder.phone=phone;gOrder.city=city;gOrder.addr=addr;
  gOrder.note=document.getElementById('custNote').value.trim();gOrder.payMode=payMode;
  var btn=document.getElementById('submitBtn');btn.disabled=true;
  document.getElementById('submitText').textContent='⏳ Đang xử lý...';
  try{
    var res=await fetch('{{ route("place-order") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({product_id:gOrder.productId,product_name:gOrder.name,product_size:gOrder.size,price:gOrder.price,quantity:gOrder.qty,customer_name:gOrder.custName,customer_phone:gOrder.phone,customer_city:gOrder.city,customer_addr:gOrder.addr,note:gOrder.note,payment:gOrder.payMode,coupon_code:gCoupon?gCoupon.code:'',customer_email:document.getElementById('custEmail').value.trim()})});
    var d=await res.json();
    if(d.success){gOrder.code=d.code;gOrder.total=d.total;if(gOrder.payMode==='BANK'){goToQR();}else{document.getElementById('successCode').textContent=d.code;showState('success');document.getElementById('modalTitle').textContent='✅ Đặt hàng thành công';document.getElementById('modalSub').textContent='';}}
    else showToast('❌ '+(d.message||'Có lỗi xảy ra'));
  }catch(e){showToast('❌ Lỗi kết nối. Vui lòng thử lại.');}
  btn.disabled=false;document.getElementById('submitText').textContent='Tiếp theo →';
}
</script>
@include('partials.float-widget')
@include('partials.bottom-nav')
</body>
</html>