<!DOCTYPE html>
<html lang="vi" class="scroll-smooth">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<title>DALI — Tranh Tô Màu Số Hóa Theo Ảnh | Biến ảnh kỷ niệm thành kiệt tác</title>
<meta name="description" content="DALI Việt Nam — biến ảnh kỷ niệm thành tranh tô màu số hóa độc bản. AI thiết kế trong 1 phút, tặng bộ màu &amp; cọ, giao toàn quốc. Giá từ 370.000đ.">
<meta property="og:image" content="{{ asset('images/home/hero-banner.jpg') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config={theme:{extend:{colors:{primary:'#4CAF50',primaryd:'#2E7D32',cream:'#FBF7EF',ink:'#1A1A1A',accent:'#FFB74D'},fontFamily:{sans:['Be Vietnam Pro','sans-serif']},boxShadow:{xl2:'0 24px 60px -12px rgba(46,125,50,.18)'}}}}
</script>
<style>
body{font-family:'Be Vietnam Pro',sans-serif;background:#fff;color:#1A1A1A}
[class^="ri-"],[class*=" ri-"]{vertical-align:-.125em}
.reveal{opacity:0;transform:translateY(26px);transition:opacity .6s ease,transform .6s ease}.reveal.in{opacity:1;transform:none}
.glass{background:rgba(255,255,255,.72);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px)}
.grad-btn{background:linear-gradient(135deg,#2E7D32,#4CAF50);transition:transform .2s,box-shadow .2s}
.grad-btn:hover{transform:translateY(-2px);box-shadow:0 14px 30px rgba(76,175,80,.35)}
.no-scrollbar::-webkit-scrollbar{display:none}.no-scrollbar{scrollbar-width:none}
</style>
</head>
<body class="text-ink">
@php $zalo = $settings['zalo'] ?? $settings['hotline'] ?? '0856911698'; $minPrice = '370.000'; @endphp

{{-- ════════ HEADER ════════ --}}
<header class="sticky top-0 z-40 glass border-b border-green-100">
  <div class="max-w-6xl mx-auto px-4 md:px-6 lg:px-8 h-16 flex items-center justify-between">
    <a href="{{ route('home.v2') }}" class="flex items-center"><img src="{{ asset('images/logo_dali.png') }}" alt="DALI" class="h-9 w-auto"></a>
    <nav class="hidden md:flex items-center gap-7 text-sm font-semibold text-gray-600">
      <a href="{{ route('products') }}" class="hover:text-primary">Sản phẩm</a>
      <a href="{{ route('thiet-ke') }}" class="hover:text-primary">Thiết kế từ ảnh</a>
      <a href="#qua-tang" class="hover:text-primary">Quà tặng</a>
      <a href="{{ route('track-order') }}" class="hover:text-primary">Tra cứu đơn</a>
    </nav>
    <a href="{{ route('thiet-ke') }}" class="grad-btn text-white text-xs md:text-sm font-extrabold px-4 md:px-5 py-2 md:py-2.5 rounded-full">Thiết kế ngay</a>
  </div>
</header>

{{-- ════════ HERO ════════ --}}
<section class="bg-cream overflow-hidden">
  <div class="max-w-6xl mx-auto px-4 md:px-6 lg:px-8 grid lg:grid-cols-2 gap-8 lg:gap-10 items-center py-12 md:py-16 lg:py-20">
    <div class="reveal order-2 lg:order-1 text-center lg:text-left">
      <span class="inline-flex items-center gap-1.5 bg-primary/10 text-primaryd font-bold text-xs px-3 py-1.5 rounded-full"><i class="ri-sparkling-2-fill text-accent"></i> AI thiết kế trong 1 phút</span>
      <h1 class="mt-4 text-3xl sm:text-4xl lg:text-5xl 2xl:text-[3.4rem] font-black leading-[1.12] tracking-tight">Biến ảnh kỷ niệm thành <span class="text-primaryd">tranh tô màu độc bản</span></h1>
      <p class="mt-4 text-gray-600 text-[15px] lg:text-lg max-w-lg mx-auto lg:mx-0">Gửi một tấm ảnh — nhận bộ kit canvas in số + màu pha sẵn + cọ, tự tay tô thành bức tranh treo tường của riêng bạn.</p>
      <div class="mt-7 flex flex-wrap items-center justify-center lg:justify-start gap-x-5 gap-y-3">
        <a href="{{ route('thiet-ke') }}" class="grad-btn text-white text-base font-extrabold px-7 py-4 rounded-xl shadow-xl2 inline-flex items-center gap-2"><i class="ri-image-add-line"></i> Thiết kế từ ảnh của bạn</a>
        <a href="{{ route('products') }}" class="text-primaryd font-bold text-sm underline underline-offset-4 hover:text-primary inline-flex items-center gap-1">Xem tranh có sẵn <i class="ri-arrow-right-line"></i></a>
      </div>
      <div class="mt-6 flex flex-wrap items-center justify-center lg:justify-start gap-x-6 gap-y-2 text-sm font-semibold text-gray-600">
        <span class="text-accent">★★★★★ <b class="text-ink">4.9</b></span>
        <span>🚚 Freeship toàn quốc</span>
        <span>💚 Giá từ <b class="text-primaryd">{{ $minPrice }}đ</b></span>
      </div>
    </div>
    <div class="reveal order-1 lg:order-2">
      <div class="relative">
        <img src="{{ asset('images/home/girl-paint.jpg') }}" alt="Cô gái vẽ tranh DALI" class="w-full rounded-3xl shadow-xl2 object-cover aspect-square">
        <div class="absolute -bottom-4 -left-2 sm:left-4 glass rounded-2xl px-4 py-2.5 shadow-xl2 flex items-center gap-2">
          <span class="text-2xl">🎨</span><div class="text-left"><div class="text-xs font-black text-primaryd leading-tight">Tranh từ ảnh thật</div><div class="text-[11px] text-gray-500">độc nhất vô nhị</div></div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ════════ TRUST STRIP ════════ --}}
<section class="border-y border-green-100/70 bg-white">
  <div class="max-w-6xl mx-auto px-4 md:px-6 lg:px-8 grid grid-cols-2 md:grid-cols-4 gap-4 py-6">
    @foreach([['ri-magic-line','AI thiết kế 1 phút'],['ri-palette-line','Màu pha sẵn theo số'],['ri-truck-line','Giao 24–72h toàn quốc'],['ri-refresh-line','Đổi mới nếu in lỗi']] as $t)
    <div class="flex items-center gap-2.5 justify-center md:justify-start"><div class="w-9 h-9 rounded-xl bg-green-50 text-primary flex items-center justify-center shrink-0"><i class="{{ $t[0] }}"></i></div><span class="text-xs sm:text-sm font-bold text-gray-700">{{ $t[1] }}</span></div>
    @endforeach
  </div>
</section>

{{-- ════════ BIẾN ẢNH THÀNH TRANH (→ /thiet-ke) ════════ --}}
<section class="max-w-6xl mx-auto px-4 md:px-6 lg:px-8 py-14 md:py-24 grid lg:grid-cols-2 gap-10 lg:gap-14 items-center reveal">
  <div class="order-2 lg:order-1">
    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight">Thiết kế tranh từ chính ảnh của bạn</h2>
    <p class="mt-3 text-gray-600 text-[15px] md:text-base">Ảnh gia đình, em bé, thú cưng hay chân dung — AI của DALI biến thành bản tranh tô màu số hóa, giữ đúng người thật. Xem trước miễn phí, ưng mới đặt.</p>
    <div class="mt-7 space-y-4">
      @foreach([['1','Tải ảnh lên','Chọn ảnh kỷ niệm bạn yêu thích.'],['2','Xem bản thiết kế','AI thiết kế trong ~1 phút — miễn phí.'],['3','Nhận kit &amp; tự tô','Canvas in số + màu + cọ, giao tận nhà.']] as $s)
      <div class="flex items-start gap-3">
        <div class="w-9 h-9 rounded-full bg-primary text-white font-black flex items-center justify-center shrink-0">{{ $s[0] }}</div>
        <div><div class="font-bold">{!! $s[1] !!}</div><div class="text-sm text-gray-500">{!! $s[2] !!}</div></div>
      </div>
      @endforeach
    </div>
    <a href="{{ route('thiet-ke') }}" class="mt-8 grad-btn text-white text-base font-extrabold px-7 py-4 rounded-xl shadow-xl2 inline-flex items-center gap-2"><i class="ri-upload-cloud-2-line"></i> Tải ảnh — xem trước miễn phí</a>
  </div>
  <div class="order-1 lg:order-2 relative">
    <img src="{{ asset('images/home/girl-finished.jpg') }}" alt="Khách DALI khoe tranh hoàn thiện" class="w-full rounded-3xl shadow-xl2 object-cover aspect-[4/5]">
    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur rounded-full px-3 py-1.5 text-xs font-extrabold text-primaryd shadow">✨ Bản thật từ khách</div>
  </div>
</section>

{{-- ════════ DANH MỤC ════════ --}}
@if($categories->count())
<section class="bg-cream border-y border-green-100/60">
<div class="max-w-6xl mx-auto px-4 md:px-6 lg:px-8 py-14 md:py-20 reveal">
  <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight text-center mb-3">Khám phá theo chủ đề</h2>
  <p class="text-center text-gray-500 text-sm md:text-base mb-10">Hàng trăm mẫu tranh tô màu số hóa — chọn chủ đề bạn yêu thích.</p>
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
    @foreach($categories->where('combo_only',false)->take(8) as $cat)
    <a href="{{ route('category', $cat->slug) }}" class="group bg-white rounded-2xl border border-green-100 shadow-sm p-5 text-center hover:-translate-y-1 hover:shadow-xl2 transition">
      <div class="text-3xl mb-2">{{ $cat->icon ?: '🎨' }}</div>
      <div class="font-extrabold text-sm group-hover:text-primaryd">{{ $cat->name }}</div>
      <div class="text-[11px] text-gray-500 mt-0.5">{{ $cat->products_count }} mẫu</div>
    </a>
    @endforeach
  </div>
</div>
</section>
@endif

{{-- ════════ BÁN CHẠY ════════ --}}
@if($bestSellers->count())
<section class="max-w-6xl mx-auto px-4 md:px-6 lg:px-8 py-14 md:py-24 reveal">
  <div class="flex items-end justify-between mb-8 md:mb-10">
    <div><h2 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight">Tranh bán chạy</h2><p class="text-gray-500 text-sm md:text-base mt-1">Được khách DALI yêu thích nhất.</p></div>
    <a href="{{ route('products') }}" class="hidden sm:inline-flex text-primaryd font-bold text-sm underline underline-offset-4 hover:text-primary items-center gap-1">Tất cả <i class="ri-arrow-right-line"></i></a>
  </div>
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5">
    @foreach($bestSellers as $p)
    <div class="bg-white rounded-2xl border border-green-100 shadow-sm overflow-hidden hover:-translate-y-1 hover:shadow-xl2 transition flex flex-col">
      <a href="{{ route('product', $p->slug) }}" class="block relative">
        <img src="{{ $p->main_image ? asset('storage/'.$p->main_image) : asset('images/home/wall-art.jpg') }}" alt="{{ $p->name }}" loading="lazy" class="w-full aspect-square object-cover">
        @if($p->badge)<span class="absolute top-2 left-2 bg-accent text-white text-[10px] font-black px-2 py-1 rounded-full">{{ $p->badge }}</span>@endif
      </a>
      <div class="p-3 flex flex-col flex-1">
        <a href="{{ route('product', $p->slug) }}" class="font-bold text-sm leading-snug line-clamp-2 hover:text-primaryd">{{ $p->name }}</a>
        <div class="text-[11px] text-gray-500 mt-1">{{ $p->colors_count ? $p->colors_count.' màu' : '' }}</div>
        <div class="mt-2 mb-3 font-black text-primaryd">@if($p->has_multiple_sizes)<span class="text-[11px] text-gray-400 font-semibold">Từ </span>@endif{{ $p->display_price }}</div>
        <a href="{{ route('product', $p->slug) }}" class="mt-auto grad-btn text-white text-xs font-extrabold py-2.5 rounded-xl text-center"><i class="ri-shopping-bag-3-line"></i> Đặt mua</a>
      </div>
    </div>
    @endforeach
  </div>
  <div class="text-center mt-10"><a href="{{ route('products') }}" class="inline-flex items-center gap-2 border-2 border-primary text-primaryd font-extrabold px-7 py-3.5 rounded-xl hover:bg-green-50 transition">Xem tất cả sản phẩm <i class="ri-arrow-right-line"></i></a></div>
</section>
@endif

{{-- ════════ TRANH TREO NHÀ BẠN ════════ --}}
<section class="bg-cream border-y border-green-100/60">
<div class="max-w-6xl mx-auto px-4 md:px-6 lg:px-8 py-14 md:py-24 grid lg:grid-cols-2 gap-10 lg:gap-14 items-center reveal">
  <img src="{{ asset('images/home/wall-art.jpg') }}" alt="Tranh DALI treo tường" class="w-full rounded-3xl shadow-xl2 object-cover aspect-[4/3]">
  <div>
    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight">Một bức tranh — cả không gian đổi khác</h2>
    <p class="mt-3 text-gray-600 text-[15px] md:text-base">Canvas in số cao cấp, màu acrylic bền đẹp. Tự tay tô xong là có ngay tác phẩm treo phòng khách, phòng ngủ hay làm quà — đậm dấu ấn cá nhân.</p>
    <div class="mt-6 flex flex-wrap gap-x-6 gap-y-2 text-sm font-semibold text-gray-700">
      <span><i class="ri-checkbox-circle-fill text-primary"></i> Canvas dày, in số sắc nét</span>
      <span><i class="ri-checkbox-circle-fill text-primary"></i> Móc treo tặng kèm</span>
      <span><i class="ri-checkbox-circle-fill text-primary"></i> Bền màu nhiều năm</span>
    </div>
    <a href="{{ route('products') }}" class="mt-7 inline-flex items-center gap-2 border-2 border-primary text-primaryd font-extrabold px-7 py-3.5 rounded-xl hover:bg-white transition">Chọn tranh treo nhà <i class="ri-arrow-right-line"></i></a>
  </div>
</div>
</section>

{{-- ════════ QUÀ TẶNG ════════ --}}
<section id="qua-tang" class="max-w-6xl mx-auto px-4 md:px-6 lg:px-8 py-14 md:py-24 grid lg:grid-cols-2 gap-10 lg:gap-14 items-center reveal">
  <div class="order-2 lg:order-1">
    <span class="inline-flex items-center gap-1.5 bg-accent/15 text-amber-700 font-bold text-xs px-3 py-1.5 rounded-full"><i class="ri-gift-line"></i> Món quà ý nghĩa</span>
    <h2 class="mt-4 text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight">Tặng người thương một kỷ niệm</h2>
    <p class="mt-3 text-gray-600 text-[15px] md:text-base">Sinh nhật, kỷ niệm, tân gia — gói quà DALI sang trọng kèm thiệp viết tay. Biến tấm ảnh chung thành món quà không đụng hàng, người nhận giữ mãi.</p>
    <a href="{{ route('thiet-ke') }}" class="mt-7 grad-btn text-white text-base font-extrabold px-7 py-4 rounded-xl shadow-xl2 inline-flex items-center gap-2"><i class="ri-gift-2-line"></i> Tạo quà từ ảnh kỷ niệm</a>
  </div>
  <img src="{{ asset('images/home/gift-kit.jpg') }}" alt="Gói quà tranh tô màu DALI" class="order-1 lg:order-2 w-full rounded-3xl shadow-xl2 object-cover aspect-square">
</section>

{{-- ════════ ĐÁNH GIÁ ════════ --}}
<section class="bg-cream border-y border-green-100/60">
<div class="max-w-6xl mx-auto px-4 md:px-6 lg:px-8 py-14 md:py-20 text-center reveal">
  <div class="flex items-center justify-center gap-2 mb-2"><span class="text-4xl font-black">4.9</span><span class="text-accent text-2xl">★★★★★</span></div>
  <p class="text-gray-500 text-sm md:text-base mb-10">Hàng nghìn khách đã tô xong và khoe thành phẩm.</p>
  <div class="grid sm:grid-cols-3 gap-5 text-left">
    @foreach([['Mai Anh','Tô xong đẹp hơn mình tưởng, treo phòng khách ai cũng khen!'],['Tuấn','Tặng vợ ảnh cưới thành tranh, vợ thích mê.'],['Hà','Bé nhà mình tô cả buổi chiều, vui mà tranh lại đẹp.']] as $r)
    <div class="bg-white rounded-2xl border border-green-100 shadow-sm p-5">
      <div class="text-accent text-sm">★★★★★</div>
      <p class="text-sm text-gray-700 mt-2 leading-relaxed">“{{ $r[1] }}”</p>
      <div class="text-xs font-extrabold text-primaryd mt-3">— {{ $r[0] }}</div>
    </div>
    @endforeach
  </div>
</div>
</section>

{{-- ════════ CTA CUỐI ════════ --}}
<section class="relative overflow-hidden bg-gradient-to-r from-[#1E7A33] to-[#37A24A] text-white py-14 md:py-20 px-4 md:px-6">
  <div class="max-w-5xl mx-auto grid md:grid-cols-[320px_1fr] gap-8 lg:gap-14 items-center">
    <img src="{{ asset('images/home/process.jpg') }}" alt="Quá trình tô tranh DALI" class="w-full max-w-[320px] mx-auto rounded-2xl border-4 border-white/80 shadow-xl2 rotate-[-3deg] aspect-square object-cover">
    <div class="text-center md:text-left">
      <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight leading-tight">Bắt đầu bức tranh của riêng bạn hôm nay</h2>
      <p class="mt-3 text-white/85 text-[15px] md:text-base">Tải một tấm ảnh — xem bản thiết kế miễn phí trong 1 phút. Ưng rồi mới đặt.</p>
      <a href="{{ route('thiet-ke') }}" class="mt-6 inline-flex items-center gap-2 bg-white text-primaryd font-extrabold text-base px-8 py-4 rounded-2xl hover:scale-105 transition shadow-xl2">📸 Thiết kế từ ảnh của bạn</a>
    </div>
  </div>
</section>

{{-- ════════ FOOTER ════════ --}}
<footer class="bg-ink text-white/60 text-[13px] py-10 md:py-12 px-4">
  <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
    <div class="flex items-center gap-3"><img src="{{ asset('images/logo_dali.png') }}" alt="DALI" class="h-8 w-auto"><span class="font-bold text-white/80">Tranh Tô Màu Số Hóa Theo Ảnh</span></div>
    <nav class="flex flex-wrap justify-center gap-x-6 gap-y-2">
      <a href="{{ route('products') }}" class="hover:text-white">Sản phẩm</a>
      <a href="{{ route('thiet-ke') }}" class="hover:text-white">Thiết kế từ ảnh</a>
      <a href="{{ route('track-order') }}" class="hover:text-white">Tra cứu đơn</a>
      <a href="https://zalo.me/{{ preg_replace('/\D/','',$zalo) }}" target="_blank" rel="noopener" class="hover:text-white">Zalo {{ $zalo }}</a>
    </nav>
    <div>© {{ date('Y') }} DALI · 🇻🇳 Giao toàn quốc</div>
  </div>
</footer>

{{-- Thanh đáy mobile + nút nổi (tái dùng) --}}
@include('partials.float-widget')

<script>
const io=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting){e.target.classList.add('in');io.unobserve(e.target);}}),{threshold:.12});
document.querySelectorAll('.reveal').forEach(el=>io.observe(el));
</script>
</body>
</html>
