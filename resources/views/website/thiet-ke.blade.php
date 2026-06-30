<!DOCTYPE html>
<html lang="vi" class="scroll-smooth">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Tranh Tô Màu Số Hóa Theo Ảnh — Thiết Kế Từ Ảnh Của Bạn | DALI</title>
<meta name="description" content="Biến ảnh của bạn thành tranh tô màu số hóa độc bản. Gửi ảnh — shop thiết kế thủ công, gửi bản xem trước qua Zalo. Tặng bộ màu & cọ, giao toàn quốc.">
<link rel="canonical" href="{{ url('/thiet-ke') }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="DALI — Tranh Tô Màu Số Hóa">
<meta property="og:url" content="{{ url('/thiet-ke') }}">
<meta property="og:title" content="Biến ảnh của bạn thành tranh tô màu độc bản | DALI">
<meta property="og:description" content="Gửi 1 tấm ảnh — shop thiết kế thủ công thành tranh tô màu số hóa giữ đúng người thật, gửi bản xem trước qua Zalo. Tặng bộ màu & cọ, giao toàn quốc.">
<meta property="og:image" content="{{ asset('images/og-thiet-ke.jpg') }}?v=1">
<meta property="og:image:secure_url" content="{{ asset('images/og-thiet-ke.jpg') }}?v=1">
<meta property="og:image:type" content="image/jpeg">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="DALI — Biến ảnh thành tranh tô màu độc bản">
<meta property="og:locale" content="vi_VN">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Biến ảnh của bạn thành tranh tô màu độc bản | DALI">
<meta name="twitter:description" content="Gửi 1 tấm ảnh — shop thiết kế thủ công thành tranh tô màu số hóa, gửi bản xem trước qua Zalo. Tặng màu & cọ, giao toàn quốc.">
<meta name="twitter:image" content="{{ asset('images/og-thiet-ke.jpg') }}?v=1">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
  theme: {
    extend: {
      colors: {
        primary:   '#4CAF50',
        primaryd:  '#2E7D32',
        bgsoft:    '#F8FAF5',
        ink:       '#1A1A1A',
        accent:    '#FFB74D',
      },
      fontFamily: { sans: ['Be Vietnam Pro','sans-serif'] },
      boxShadow: { xl2: '0 24px 60px -12px rgba(46,125,50,.22)' },
    }
  }
}
</script>
<style>
  body{font-family:'Be Vietnam Pro',sans-serif;background:#F8FAF5;color:#1A1A1A}
  [class^="ri-"],[class*=" ri-"]{vertical-align:-.125em}
  .reveal{opacity:0;transform:translateY(26px);transition:opacity .6s ease,transform .6s ease}
  .reveal.in{opacity:1;transform:none}
  .glass{background:rgba(255,255,255,.7);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px)}
  .grad{background:linear-gradient(135deg,#2E7D32,#4CAF50)}
  .grad-btn{background:linear-gradient(135deg,#2E7D32,#4CAF50);transition:transform .2s,box-shadow .2s}
  .grad-btn:hover{transform:translateY(-2px);box-shadow:0 14px 30px rgba(76,175,80,.35)}
  .no-scrollbar::-webkit-scrollbar{display:none}.no-scrollbar{scrollbar-width:none}
  details>summary{list-style:none;cursor:pointer}details>summary::-webkit-details-marker{display:none}
  details[open] .faq-ic{transform:rotate(45deg)}
</style>
</head>
<body class="text-ink">

{{-- ════════ HEADER (sticky) ════════ --}}
<header class="sticky top-0 z-40 glass border-b border-green-100">
  <div class="max-w-6xl mx-auto px-4 md:px-6 lg:px-8 h-16 flex items-center justify-between">
    <a href="{{ route('home') }}" class="flex items-center"><img src="{{ asset('images/logo_dali.png') }}" alt="DALI" class="h-9 w-auto"></a>
    <nav class="hidden md:flex items-center gap-7 text-sm font-semibold text-gray-600">
      <a href="#mau-tranh" class="hover:text-primary">Mẫu tranh</a>
      <a href="#danh-gia" class="hover:text-primary">Đánh giá</a>
      <a href="#bang-gia" class="hover:text-primary">Bảng giá</a>
      <a href="#faq" class="hover:text-primary">FAQ</a>
    </nav>
    <a href="#upload" class="hidden md:inline-flex grad-btn text-white text-sm font-extrabold px-5 py-2.5 rounded-full">Gửi ảnh ngay</a>
  </div>
</header>

{{-- ════════ SECTION 1 — HERO (thiết kế Stitch V2) ════════ --}}
<section class="relative overflow-hidden bg-[#3E2F23]">
  {{-- Mobile: ảnh dọc HIỆN RÕ, chỉ tối nhẹ phía trên cho chữ trắng (không che hết ảnh) --}}
  <img src="{{ asset('images/home/girl-night.jpg') }}" alt="Cô gái vẽ tranh DALI" class="md:hidden absolute inset-0 w-full h-full object-cover object-[center_35%]">
  <div class="md:hidden absolute inset-0" style="background:linear-gradient(to bottom,rgba(46,34,25,.88) 0%,rgba(46,34,25,.5) 26%,rgba(46,34,25,0) 50%)"></div>
  {{-- Desktop: ảnh cô gái váy xanh phủ panel phải, mép trái hoà vào nền nâu --}}
  <div class="hidden md:block absolute inset-y-0 right-0 w-[50%] lg:w-[46%] xl:w-[44%]">
    <img src="{{ asset('images/home/girl-night.jpg') }}" alt="Cô gái vẽ tranh DALI" class="w-full h-full object-cover object-[center_38%]">
    <div class="absolute inset-y-0 left-0 w-40 lg:w-56 bg-gradient-to-r from-[#3E2F23] to-transparent"></div>
  </div>
  <div class="hidden md:block absolute inset-0 bg-gradient-to-r from-[#3E2F23] via-[#3E2F23]/55 to-transparent"></div>
  <div class="relative max-w-6xl mx-auto px-4 md:px-6 lg:px-8 pt-14 pb-44 sm:pb-52 md:pt-0 md:pb-0 md:min-h-[560px] xl:min-h-[640px] md:flex md:items-center">
    <div class="max-w-xl xl:max-w-2xl reveal md:pb-16 text-center md:text-left">
      {{-- Mobile: CHỈ 1 câu hook căn giữa · Desktop: đầy đủ 2 dòng + mô tả --}}
      <h1 class="text-3xl sm:text-4xl lg:text-[2.7rem] xl:text-5xl 2xl:text-[3.4rem] font-black leading-[1.12] tracking-tight text-white">
        Biến khoảnh khắc của bạn thành <span class="text-[#B7F0A8]">kiệt tác!</span>
        <span class="hidden md:block mt-1 text-[#B7F0A8]">Trải nghiệm niềm vui vẽ tranh số hóa.</span>
      </h1>
      <p class="hidden md:block mt-4 text-white/85 text-[15px] lg:text-base xl:text-lg max-w-md xl:max-w-lg">Gửi 1 tấm ảnh kỷ niệm — nhận bộ kit <b class="text-white">canvas in số + màu pha sẵn + cọ</b>, tự tay tô thành tranh treo tường.</p>
      <div class="mt-6 md:mt-7 flex flex-wrap items-center justify-center md:justify-start gap-x-6 gap-y-3">
        <a href="#upload" class="grad-btn w-full sm:w-auto justify-center text-white text-base font-extrabold px-7 py-4 rounded-xl shadow-xl2 flex items-center gap-2"><i class="ri-upload-cloud-2-line"></i> Gửi ảnh — shop thiết kế cho bạn</a>
        <a href="#bang-gia" class="hidden md:inline-flex text-white/80 hover:text-white underline underline-offset-4 font-bold text-sm items-center gap-1">Xem bảng giá <i class="ri-arrow-down-line"></i></a>
      </div>
      <div class="mt-4 md:mt-5 inline-flex flex-wrap justify-center items-center gap-x-2.5 gap-y-1 bg-white/10 backdrop-blur rounded-full px-4 py-2 text-sm text-white/90">
        <span class="font-black text-[#B7F0A8]">Từ {{ number_format(min(array_map(fn($s) => min($s['prices']), $pricing['sizes'])), 0, ',', '.') }}đ</span>
        <span class="text-white/50">·</span><span class="font-semibold">⭐ 4.9/5<span class="hidden md:inline"> đánh giá</span></span>
      </div>
    </div>
  </div>
</section>
{{-- ════════ SECTION 2 — UPLOAD TOOL (gửi ảnh + đặt cọc) ════════ --}}
<section id="upload" class="max-w-6xl mx-auto px-4 md:px-6 lg:px-8 -mt-12 relative z-10 pb-12 md:pb-16">
  <div class="grid lg:grid-cols-[1fr_310px] xl:grid-cols-[1fr_340px] gap-5 xl:gap-7 items-start">
    <div class="bg-white rounded-3xl shadow-xl2 border-2 border-primary/40 p-6 sm:p-8 reveal">
      <h2 class="text-xl sm:text-2xl font-black"><span class="text-primary">Gửi ảnh của bạn</span> — shop thiết kế tranh tô màu số hoá thủ công cho bạn</h2>
      <div class="mt-3 mb-4 flex flex-wrap items-center gap-x-2 gap-y-1.5 text-sm font-bold text-primaryd">
        <span class="inline-flex items-center gap-1.5 bg-green-50 border border-green-200 rounded-full px-3 py-1.5"><span class="w-5 h-5 rounded-full bg-primary text-white text-xs flex items-center justify-center">1</span> Tải ảnh</span>
        <i class="ri-arrow-right-line text-gray-300"></i>
        <span class="inline-flex items-center gap-1.5 bg-green-50 border border-green-200 rounded-full px-3 py-1.5"><span class="w-5 h-5 rounded-full bg-primary text-white text-xs flex items-center justify-center">2</span> Chọn cỡ &amp; số màu</span>
        <i class="ri-arrow-right-line text-gray-300"></i>
        <span class="inline-flex items-center gap-1.5 bg-amber-50 border border-amber-200 text-amber-700 rounded-full px-3 py-1.5"><span class="w-5 h-5 rounded-full bg-amber-500 text-white text-xs flex items-center justify-center">3</span> Cọc 20%</span>
        <i class="ri-arrow-right-line text-gray-300"></i>
        <span class="inline-flex items-center gap-1.5 bg-blue-50 border border-blue-200 text-[#0068FF] rounded-full px-3 py-1.5"><i class="ri-message-3-fill"></i> Shop nhắn Zalo tư vấn</span>
      </div>
      <input type="file" id="fileInput" accept="image/png,image/jpeg,image/webp" class="hidden">
      <div class="grid sm:grid-cols-[1fr_auto] gap-4 items-stretch">
        <div id="dropZone" class="border-2 border-dashed border-green-300 rounded-2xl bg-green-50/60 px-6 py-10 text-center cursor-pointer hover:border-primary hover:bg-green-50 transition">
          <div class="w-14 h-14 mx-auto mb-3 rounded-full grad flex items-center justify-center text-white text-2xl"><i class="ri-image-add-line"></i></div>
          <div class="font-extrabold text-lg">Kéo thả hoặc chạm để chọn ảnh</div>
          <div class="text-xs text-gray-500 mt-1">PNG · JPG · WEBP — ảnh càng rõ kết quả càng đẹp</div>
          <span class="inline-block mt-4 grad-btn text-white text-sm font-extrabold px-6 py-3 rounded-xl"><i class="ri-folder-image-line"></i> Chọn ảnh</span>
        </div>
        {{-- Demo trước→sau: ảnh thật của khách --}}
        <div class="flex items-center justify-center gap-2 lg:gap-3 self-center">
          <img src="{{ asset('images/thiet-ke/be-goc.jpg') }}" onclick="openZoom(this.src)" class="w-24 h-32 sm:w-28 sm:h-36 lg:w-36 lg:h-48 object-cover rounded-xl border border-green-100 cursor-zoom-in" alt="Ảnh gốc">
          <span class="text-primary text-2xl lg:text-3xl font-black">→</span>
          <img src="{{ asset('images/thiet-ke/be-art.jpg') }}" onclick="openZoom(this.src)" class="w-24 h-32 sm:w-28 sm:h-36 lg:w-36 lg:h-48 object-cover rounded-xl border-2 border-primary/40 cursor-zoom-in" alt="Tranh DALI từ ảnh">
        </div>
      </div>
      {{-- Checklist ảnh đẹp / ảnh nên tránh --}}
      <div class="grid grid-cols-2 gap-x-4 gap-y-1.5 text-xs mt-4 text-gray-600">
        <div class="space-y-1.5">
          <div><i class="ri-checkbox-circle-fill text-primary"></i> Ảnh rõ mặt, đủ sáng</div>
          <div><i class="ri-checkbox-circle-fill text-primary"></i> Nền càng đơn giản càng đẹp</div>
          <div><i class="ri-checkbox-circle-fill text-primary"></i> Tối đa 4–5 người trong ảnh</div>
        </div>
        <div class="space-y-1.5">
          <div><i class="ri-close-circle-fill text-red-400"></i> Ảnh mờ, nhòe, rung tay</div>
          <div><i class="ri-close-circle-fill text-red-400"></i> Ngược sáng, quá tối</div>
          <div><i class="ri-close-circle-fill text-red-400"></i> Chụp quá xa, mặt quá nhỏ</div>
        </div>
      </div>
      <img id="previewImg" class="hidden mt-5 mx-auto max-h-72 rounded-2xl border border-green-100" alt="">
      <div class="mt-5 flex flex-wrap items-center gap-3">
        <button id="genBtn" disabled class="grad-btn w-full sm:w-auto justify-center disabled:opacity-40 disabled:cursor-not-allowed text-white text-base font-extrabold px-7 py-4 rounded-2xl flex items-center gap-2"><i class="ri-arrow-right-circle-line"></i> Tiếp tục: chọn cỡ &amp; đặt cọc</button>
        <span class="inline-flex items-center gap-1.5 bg-green-50 border border-green-200 text-primaryd font-bold text-xs sm:text-sm px-4 py-2 rounded-full"><i class="ri-truck-line"></i> Giao 24–72h toàn quốc · từ {{ number_format(min(array_map(fn($s) => min($s['prices']), $pricing['sizes'])), 0, ',', '.') }}đ</span>
      </div>
      {{-- 4 cam kết --}}
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-5 text-center">
        @foreach([
          ['ri-user-star-line','Designer thiết kế thủ công từng ảnh'],
          ['ri-message-3-line','Gửi bản xem trước qua Zalo'],
          ['ri-hand-coin-line','Cọc 20% — còn lại khi nhận hàng'],
          ['ri-refresh-line','Không ưng bản thiết kế — hoàn cọc'],
        ] as $t)
        <div>
          <div class="w-10 h-10 mx-auto rounded-full bg-green-50 text-primary text-lg flex items-center justify-center mb-1.5"><i class="{{ $t[0] }}"></i></div>
          <div class="text-[11px] font-bold leading-tight text-gray-600">{{ $t[1] }}</div>
        </div>
        @endforeach
      </div>
      <div class="mt-4 pt-4 border-t border-green-100 text-center text-sm text-gray-500">Muốn tư vấn trước? <a href="https://zalo.me/0856911698" target="_blank" rel="noopener" class="font-extrabold text-primaryd underline">💬 Gửi ảnh qua Zalo</a> — shop tư vấn cỡ &amp; số màu, hoặc <button type="button" class="orderOpen font-extrabold text-primaryd underline" data-package="Đặt trước — shop gọi tư vấn ảnh &amp; kích thước">để lại SĐT, shop gọi lại</button></div>
    </div>
    <div class="grid gap-4 md:grid-cols-3 lg:grid-cols-1 reveal">
      @foreach([
        ['ri-artboard-2-line','Vải Canvas Cao Cấp','Mịn, dày dặn, in số sắc nét — tô chính xác tới từng chi tiết nhỏ.'],
        ['ri-palette-line','Bộ Màu Acrylic Đầy Đủ','Từng màu pha sẵn theo đúng bản thiết kế, mở nắp là tô được ngay.'],
        ['ri-brush-line','Cọ Vẽ Chuyên Dụng','Trọn bộ cọ nhiều cỡ — nét to, chi tiết bé đều dễ dàng xử lý.'],
      ] as $b)
      <div class="bg-white rounded-2xl border border-green-100 shadow-sm p-5 flex gap-3 items-start hover:shadow-xl2 transition">
        <div class="w-11 h-11 rounded-xl bg-green-50 text-primary text-xl flex items-center justify-center shrink-0"><i class="{{ $b[0] }}"></i></div>
        <div><div class="font-extrabold text-[15px]">{{ $b[1] }}</div><div class="text-xs text-gray-500 leading-relaxed mt-0.5">{{ $b[2] }}</div></div>
      </div>
      @endforeach
    </div>
  </div>
</section>
{{-- CHỌN CỠ & SỐ MÀU + ĐẶT CỌC (hiện sau khi tải ảnh) --}}
<section class="max-w-6xl mx-auto px-4 md:px-6 lg:px-8">
  <div id="resultSection" class="hidden scroll-mt-20 mt-2 bg-white rounded-3xl shadow-xl2 border-2 border-primary/60 p-6 sm:p-8">
    <div class="bg-green-50 border-2 border-green-200 rounded-2xl p-5 text-center mb-5">
      <div class="text-3xl mb-1">✅🎨</div>
      <div class="font-black text-lg text-primaryd mb-1">Đã nhận ảnh của bạn!</div>
      <div class="text-sm text-gray-600 leading-relaxed max-w-md mx-auto">Chọn <b>kích thước &amp; số màu</b> bên dưới rồi <b>đặt cọc 20%</b>. Shop sẽ <b>tự tay thiết kế bức tranh từ ảnh của bạn</b> và <b>nhắn Zalo gửi bản xem trước</b> sớm nhất — ưng mới làm tiếp, không ưng hoàn cọc.</div>
      <img id="orderPreviewImg" onclick="openZoom(this.src)" class="hidden mt-3 mx-auto max-h-44 rounded-xl border border-green-200 cursor-zoom-in" alt="Ảnh bạn gửi">
    </div>
    {{-- CHỌN KÍCH THƯỚC & SỐ MÀU --}}
    <div class="bg-green-50/70 border-2 border-green-200 rounded-2xl p-5 sm:p-6">
      <div class="font-extrabold text-lg mb-1">🛒 Đặt bức tranh này về nhà</div>
      <div class="text-xs text-gray-500 mb-4">Chọn kích thước &amp; số màu — giá đã gồm canvas in sẵn, bộ màu, cọ &amp; giao hàng.</div>
      <div class="font-bold text-sm mb-2">🖼️ Kích thước <span class="text-xs text-gray-500 font-semibold">({{ $pricing['sizes'][0]['note'] ?? '' }})</span></div>
      <div class="flex flex-wrap gap-2" id="sizeChips">
        @foreach($pricing['sizes'] as $i => $s)
        <button type="button" data-i="{{ $i }}" class="pick border-2 rounded-xl px-4 py-3 text-sm font-bold bg-white">{{ $s['label'] }}<span class="block text-xs font-extrabold text-primaryd sizePrice" data-i="{{ $i }}"></span></button>
        @endforeach
      </div>
      <div class="font-bold text-sm mb-2 mt-4">🎨 Số màu <span class="text-xs text-gray-500 font-semibold">(nhiều màu hơn = chi tiết &amp; giống ảnh hơn)</span></div>
      <div class="flex flex-wrap gap-2" id="colorChips">
        @foreach($pricing['colors'] as $j => $c)
        <button type="button" data-j="{{ $j }}" class="pick border-2 rounded-xl px-4 py-3 text-sm font-bold bg-white">{{ $c }} màu</button>
        @endforeach
      </div>
      <div class="mt-5">
        <button id="resultOrderBtn" class="grad-btn w-full sm:w-auto justify-center text-white font-extrabold text-base px-5 sm:px-8 py-4 rounded-2xl flex items-center gap-2"><i class="ri-hand-coin-fill"></i> <span id="resultOrderLabel">Đặt cọc 20%</span></button>
      </div>
      <div class="mt-3 text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2 inline-block">💬 Sau khi đặt cọc, nhân viên sẽ nhắn Zalo tư vấn &amp; gửi bản thiết kế xem trước cho bạn.</div>
    </div>
  </div>
</section>

{{-- ════════ CÁCH HOẠT ĐỘNG — 4 bước ════════ --}}
<section class="max-w-6xl mx-auto px-4 md:px-6 lg:px-8 py-12 md:py-20 reveal">
  <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight text-center mb-3">Cách hoạt động</h2>
  <p class="text-center text-gray-500 text-sm md:text-base mb-10 md:mb-12 max-w-2xl mx-auto">Từ tấm ảnh trong điện thoại đến bức tranh treo tường — chỉ 4 bước.</p>
  <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
    @foreach([
      ['ri-image-add-line','Tải ảnh lên','Chọn ảnh kỷ niệm bạn yêu thích ngay trên điện thoại.'],
      ['ri-hand-coin-line','Chọn cỡ &amp; đặt cọc','Chọn kích thước &amp; số màu, cọc 20% để giữ chỗ — còn lại trả khi nhận.'],
      ['ri-message-3-line','Shop thiết kế &amp; gửi Zalo','Designer thiết kế thủ công, nhắn Zalo gửi bản xem trước — ưng mới làm tiếp.'],
      ['ri-brush-line','Nhận kit &amp; tô','Giao 24–72h: canvas in số + màu pha sẵn + cọ.'],
    ] as $i => $s)
    <div class="text-center">
      <div class="w-10 h-10 mx-auto rounded-full bg-primary text-white font-black flex items-center justify-center mb-3">{{ $i + 1 }}</div>
      <div class="w-12 h-12 mx-auto rounded-2xl bg-green-50 text-primary text-2xl flex items-center justify-center mb-3"><i class="{{ $s[0] }}"></i></div>
      <div class="font-bold text-base mb-1">{!! $s[1] !!}</div>
      <div class="text-sm text-gray-500 leading-relaxed">{!! $s[2] !!}</div>
    </div>
    @endforeach
  </div>
</section>

{{-- ════════ SECTION 3 — WHAT'S IN THE BOX ════════ --}}
<section id="mo-hop" class="bg-white border-y border-green-100/60">
<div class="max-w-6xl mx-auto px-4 md:px-6 lg:px-8 py-12 md:py-20 lg:py-24 reveal">
  <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight text-center mb-10 md:mb-12">Bộ kit DALI — trong hộp có gì?</h2>
  <div class="grid gap-5 lg:grid-cols-[230px_1fr_230px] xl:grid-cols-[260px_1fr_260px] xl:gap-8 items-center">
    <div class="order-2 lg:order-1 grid gap-3 md:grid-cols-2 lg:grid-cols-1">
      @foreach([['ri-image-2-line','Canvas in số','Tranh của bạn in sẵn ô số trên canvas cao cấp'],['ri-palette-line','Bộ màu acrylic','Pha sẵn đúng từng mã màu của bản thiết kế']] as $i)
      <div class="bg-white rounded-xl border border-green-100 shadow-sm p-4 flex gap-3 items-start"><div class="w-9 h-9 rounded-lg bg-green-50 text-primary flex items-center justify-center shrink-0"><i class="{{ $i[0] }}"></i></div><div><div class="font-extrabold text-sm">{{ $i[1] }}</div><div class="text-[11px] text-gray-500">{{ $i[2] }}</div></div></div>
      @endforeach
    </div>
    <figure class="relative order-1 lg:order-2">
      <img src="{{ asset('images/thiet-ke/mo-hop.jpg') }}" loading="lazy" class="rounded-3xl shadow-xl2 w-full aspect-square object-cover cursor-zoom-in" onclick="openZoom(this.src)" alt="Bộ kit tranh tô màu số DALI">
      <figcaption class="absolute bottom-3 left-1/2 -translate-x-1/2 glass rounded-full px-4 py-1.5 text-xs font-extrabold text-primaryd shadow whitespace-nowrap">🖼️ Thành phẩm thật từ khách DALI</figcaption>
    </figure>
    <div class="order-3 grid gap-3 md:grid-cols-2 lg:grid-cols-1">
      @foreach([['ri-brush-line','Cọ vẽ 3 cỡ','Tô nền lớn và chi tiết nhỏ đều dễ dàng'],['ri-file-list-3-line','Bảng mã màu','Hướng dẫn rõ ràng, nhìn là làm theo được'],['ri-links-line','Móc treo tặng kèm','Tô xong treo tường ngay không cần mua thêm']] as $i)
      <div class="bg-white rounded-xl border border-green-100 shadow-sm p-4 flex gap-3 items-start"><div class="w-9 h-9 rounded-lg bg-green-50 text-primary flex items-center justify-center shrink-0"><i class="{{ $i[0] }}"></i></div><div><div class="font-extrabold text-sm">{{ $i[1] }}</div><div class="text-[11px] text-gray-500">{{ $i[2] }}</div></div></div>
      @endforeach
    </div>
  </div>
</div>
</section>

{{-- ════════ ẢNH THẬT → TRANH DALI (3 cặp minh chứng lớn) ════════ --}}
<section class="max-w-6xl mx-auto px-4 md:px-6 lg:px-8 py-12 md:py-20 reveal">
  <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight text-center mb-3">Ảnh thật → Tranh DALI</h2>
  <p class="text-center text-gray-500 text-sm md:text-base mb-10 max-w-2xl mx-auto">Mỗi tấm ảnh là một bản thiết kế riêng — đây là kết quả thật từ ảnh khách gửi.</p>
  <div class="grid sm:grid-cols-3 gap-6 md:gap-8">
    @foreach([['be-goc','be-art'],['ba-goc','ba-art'],['sen-goc','sen-art']] as $p)
    <div class="grid grid-cols-[1fr_auto_1fr] items-center gap-2">
      <img src="{{ asset('images/thiet-ke/'.$p[0].'.jpg') }}" loading="lazy" onclick="openZoom(this.src)" class="w-full aspect-[3/4] object-cover rounded-2xl border border-green-100 cursor-zoom-in" alt="Ảnh gốc">
      <i class="ri-arrow-right-line text-primary text-2xl"></i>
      <img src="{{ asset('images/thiet-ke/'.$p[1].'.jpg') }}" loading="lazy" onclick="openZoom(this.src)" class="w-full aspect-[3/4] object-cover rounded-2xl border-2 border-primary/40 shadow-lg cursor-zoom-in" alt="Tranh DALI">
    </div>
    @endforeach
  </div>
</section>

{{-- ════════ SECTION 4 — KHÁCH KHOE TRANH + BẢNG GIÁ ════════ --}}
<section id="mau-tranh" class="max-w-6xl mx-auto px-4 md:px-6 lg:px-8 py-12 md:py-20 reveal">
  <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight text-center mb-2">Khách DALI khoe thành phẩm</h2>
  <div class="flex items-center justify-center gap-2 mb-2"><span class="text-2xl font-black">4.9</span><span class="text-accent text-lg">★★★★★</span></div>
  <p class="text-center text-gray-500 mb-8 md:mb-10 text-sm md:text-base">Tranh thật từ ảnh thật — và bảng giá rõ ràng ngay bên cạnh</p>
  <div class="grid lg:grid-cols-[1fr_460px] gap-8 items-start">
    <div id="danh-gia" class="grid grid-cols-2 sm:grid-cols-3 gap-3">
      @foreach([
        ['be-art','Mai_Art','Món quà tuyệt vời cho mẹ!'],
        ['ba-art','Tuan_Draws','Giống bà ngoại lắm luôn!'],
        ['sen-art','HoaSen_Home','Treo phòng khách quá sang!'],
        ['phat-art','AnNhien_99','Tô xong thấy bình yên hẳn.'],
        ['mo-hop','DaliFan','Bộ kit đầy đủ, đóng gói xịn.'],
        ['sen-goc','Thu_Art','Đẹp hơn cả mong đợi!'],
      ] as $g)
      <div class="bg-white rounded-2xl border border-green-100 shadow-sm overflow-hidden hover:-translate-y-1 hover:shadow-xl2 transition">
        <img src="{{ asset('images/thiet-ke/'.$g[0].'.jpg') }}" loading="lazy" class="w-full h-36 lg:h-auto lg:aspect-[4/5] object-cover cursor-zoom-in" onclick="openZoom(this.src)" alt="Tranh tô màu số hóa DALI của khách {{ $g[1] }}">
        <div class="px-3 py-2"><div class="text-accent text-[10px] leading-none">★★★★★</div><div class="text-xs lg:text-[13px] font-bold text-primaryd mt-0.5"><i class="ri-instagram-line"></i> {{ '@'.$g[1] }}</div><div class="text-[11px] lg:text-xs text-gray-600 leading-snug">“{{ $g[2] }}”</div></div>
      </div>
      @endforeach
    </div>
    <div id="bang-gia" class="bg-white rounded-3xl border border-green-100 shadow-xl2 overflow-hidden">
      <div class="px-5 pt-5 pb-3"><div class="font-black text-lg">💰 Bảng giá bộ kit</div><div class="text-[11px] text-gray-500">Đã gồm thiết kế từ ảnh + canvas + bộ màu &amp; cọ + giao toàn quốc</div>
        <div class="text-[11px] font-bold text-primaryd mt-1">🎁 Trọn gói: canvas in số + màu pha sẵn + cọ + móc treo + freeship — không phát sinh thêm chi phí.</div></div>
      <div class="sm:hidden px-5 pb-1 text-[10px] font-bold text-gray-500">↔ Vuốt ngang để xem đủ mức giá</div>
      <div class="relative">
      <div class="overflow-x-auto">
        <table class="w-full" style="border-collapse:collapse;min-width:430px">
          <thead><tr class="border-y border-gray-100 bg-bgsoft">
            <th class="text-left px-3 py-2.5 font-black text-[11px] lg:text-xs sticky left-0 z-10 bg-bgsoft">Kích thước</th>
            @foreach($pricing['colors'] as $c)<th class="text-right px-2 py-2.5 font-black text-[11px] lg:text-xs whitespace-nowrap">{{ $c }} màu</th>@endforeach
          </tr></thead>
          <tbody>
            @foreach($pricing['sizes'] as $si => $s)
            @if($si === 1)<tr><td colspan="{{ count($pricing['colors']) + 1 }}" class="bg-primary text-white text-center text-[11px] font-black py-1.5">⭐ Lựa chọn phổ biến</td></tr>@endif
            <tr class="border-b border-gray-50 {{ $si === 1 ? 'bg-primary' : 'hover:bg-green-50/40' }} transition">
              <td class="px-3 py-2.5 sticky left-0 z-10 {{ $si === 1 ? 'bg-primary' : 'bg-white' }}"><div class="font-black text-xs whitespace-nowrap {{ $si === 1 ? 'text-white' : '' }}">{{ $s['label'] }}</div>@if(!empty($s['note']))<div class="text-[10px] lg:text-[11px] font-semibold {{ $si === 1 ? 'text-white/80' : 'text-gray-500' }}">{{ $s['note'] }}</div>@endif</td>
              @foreach($s['prices'] as $v)<td class="px-2 py-2.5 text-right text-[11px] lg:text-xs whitespace-nowrap {{ $si === 1 ? 'text-white font-black' : 'text-ink font-bold' }}">{{ number_format($v, 0, ',', '.') }}đ</td>@endforeach
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="absolute inset-y-0 right-0 w-8 bg-gradient-to-l from-white pointer-events-none sm:hidden"></div>
      </div>
      <div class="p-4"><a href="#upload" class="grad-btn w-full text-white text-sm font-extrabold py-3.5 rounded-xl flex items-center justify-center gap-2"><i class="ri-upload-cloud-2-line"></i> Gửi ảnh — shop thiết kế cho bạn</a></div>
    </div>
  </div>
</section>
{{-- ════════ SECTION 10 — FAQ ════════ --}}
@php
$faqs = [
  ['Không biết vẽ có tô được không?','Hoàn toàn được! Tranh chia sẵn từng ô có số, bạn chỉ việc tô màu đúng số tương ứng. Người chưa từng vẽ vẫn ra thành phẩm đẹp.'],
  ['Bao lâu nhận hàng?','Sau khi đặt cọc, shop thiết kế & gửi bản xem trước qua Zalo (thường trong ngày). Ưng rồi tranh in & giao trong 24–72 giờ tùy khu vực (toàn quốc).'],
  ['Ảnh chụp điện thoại có làm được không?','Có. Chỉ cần ảnh rõ mặt/chủ thể. Shop sẽ chỉnh nét & thiết kế thủ công cho đẹp.'],
  ['Có được xem trước không?','Có — sau khi đặt cọc, shop tự thiết kế và gửi bản xem trước qua Zalo. Ưng mới in; không ưng, shop hoàn cọc.'],
  ['Vì sao phải đặt cọc trước?','Cọc 20% để shop bắt đầu thiết kế thủ công cho riêng bạn. Phần còn lại trả khi nhận hàng. Nếu bạn không ưng bản thiết kế, shop hoàn lại cọc.'],
  ['Có bảo hành không?','Có. Đổi trả miễn phí nếu tranh in lỗi, sai thiết kế hoặc hư hỏng khi vận chuyển.'],
  ['Khác nhau giữa 24 / 36 / 48 màu là gì?','Nhiều màu hơn = tranh chi tiết và giống ảnh hơn (đặc biệt với chân dung). 24 màu hợp tranh đơn giản; 36 màu cân bằng đẹp – dễ tô; 48–60 màu cho chân dung cần độ giống cao.'],
  ['Tôi không rành thao tác, nhờ shop làm giúp được không?','Được! Bạn chỉ cần gửi ảnh qua Zalo 0856.911.698, shop tư vấn cỡ & số màu và thiết kế giúp bạn.'],
];
@endphp
<section id="faq" class="bg-white border-y border-green-100/60"><div class="max-w-3xl mx-auto px-4 md:px-6 py-12 md:py-20 lg:py-24 reveal">
  <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight text-center mb-8 md:mb-10">Câu hỏi thường gặp</h2>
  <div class="space-y-3">
    @foreach($faqs as $q)
    <details class="bg-white rounded-2xl border border-green-100 px-5 shadow-sm">
      <summary class="flex items-center justify-between font-extrabold py-4 min-h-[44px]"><span class="text-[15px] lg:text-base">{{ $q[0] }}</span><i class="ri-add-line faq-ic text-primary text-xl transition-transform"></i></summary>
      <p class="pb-4 text-sm lg:text-[15px] text-gray-600 leading-relaxed">{{ $q[1] }}</p>
    </details>
    @endforeach
  </div>
</div>
</section>
<script type="application/ld+json">
{!! json_encode([
  '@context' => 'https://schema.org',
  '@type'    => 'FAQPage',
  'mainEntity' => array_map(fn($q) => [
    '@type' => 'Question',
    'name'  => $q[0],
    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $q[1]],
  ], $faqs),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>

{{-- ════════ SECTION CTA CUỐI (Stitch V2) ════════ --}}
<section class="relative overflow-hidden bg-gradient-to-r from-[#1E7A33] to-[#37A24A] text-white py-14 md:py-20 px-4 md:px-6">
  <div class="max-w-5xl mx-auto grid md:grid-cols-[280px_1fr] lg:grid-cols-[340px_1fr] gap-8 lg:gap-14 items-center relative">
    <img src="{{ asset('images/home/portrait-finished.jpg') }}" loading="lazy" class="w-full max-w-[280px] lg:max-w-[340px] mx-auto rounded-2xl border-4 border-white/80 shadow-xl2 rotate-[-4deg] aspect-square object-cover" alt="Khách DALI ngắm tranh chân dung tự tô hoàn thiện">
    <div class="text-center md:text-left">
      <h2 class="text-2xl sm:text-3xl lg:text-4xl xl:text-[2.6rem] font-black tracking-tight leading-tight">Sẵn sàng để sáng tạo?<br>Bắt đầu hành trình nghệ thuật của bạn ngay hôm nay!</h2>
      <a href="#upload" class="mt-6 inline-flex items-center gap-2 bg-white text-primaryd font-extrabold text-lg px-8 py-4 rounded-2xl hover:scale-105 transition shadow-xl2">📸 Gửi ảnh — shop thiết kế, gửi bản xem trước qua Zalo</a>
    </div>
  </div>
</section>

<footer class="bg-ink text-white/60 text-[13px] py-10 md:py-12 px-4">
  <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
    <div class="font-bold text-white/80">DALI — Tranh Tô Màu Số Hóa Theo Ảnh</div>
    <nav class="flex flex-wrap justify-center gap-x-6 gap-y-2">
      <a href="#mau-tranh" class="hover:text-white">Mẫu tranh</a>
      <a href="#bang-gia" class="hover:text-white">Bảng giá</a>
      <a href="#faq" class="hover:text-white">FAQ</a>
      <a href="https://zalo.me/0856911698" target="_blank" rel="noopener" class="hover:text-white">Zalo 0856.911.698</a>
    </nav>
    <div>© 2026 DALI · 🇻🇳 Giao toàn quốc</div>
  </div>
</footer>

{{-- ════════ FLOATING (mobile sticky CTA) ════════ --}}
<div id="stickyBar" class="md:hidden fixed bottom-0 inset-x-0 z-40 glass border-t border-green-200 grid grid-cols-2 gap-2 p-2 pb-[max(0.5rem,env(safe-area-inset-bottom))] translate-y-full transition-transform duration-300">
  <a id="stickyCta" href="#upload" class="grad-btn text-white font-extrabold py-2 rounded-xl text-center text-sm leading-tight"><span class="block">📸 Gửi ảnh — shop thiết kế</span><span class="block text-[10px] font-bold text-white/85">cọc 20% · gửi xem trước Zalo</span></a>
  <a href="https://zalo.me/0856911698" target="_blank" rel="noopener" class="bg-white border border-green-200 text-primaryd font-extrabold py-3 rounded-xl text-center text-sm">💬 Chat Zalo</a>
</div>
<div class="md:hidden h-20"></div>
<a href="https://zalo.me/0856911698" target="_blank" rel="noopener" class="hidden md:flex fixed bottom-6 right-6 z-40 items-center gap-2 bg-[#0068FF] text-white font-extrabold px-5 py-3.5 rounded-full shadow-xl2 hover:scale-105 transition"><i class="ri-message-3-fill"></i> Chat Zalo</a>

{{-- ════════ MODALS ════════ --}}
<div id="orderModal" class="fixed inset-0 z-50 bg-black/50 hidden items-center justify-center p-4">
  <div class="bg-white rounded-3xl max-w-sm w-full p-7 max-h-[85vh] overflow-y-auto">
    <div class="text-center"><div class="text-4xl mb-2">🛍️</div><h3 id="orderTitle" class="text-xl font-black mb-1">Đặt cọc — shop thiết kế cho bạn</h3>
    <p id="orderDesc" class="text-sm text-gray-500 mb-4">Điền thông tin, đặt cọc 20%. Nhân viên sẽ nhắn Zalo tư vấn &amp; gửi bản thiết kế xem trước.</p></div>
    <form id="orderForm" class="space-y-3 text-left" novalidate>
      <input id="oName" name="name" type="text" autocomplete="name" autocapitalize="words" enterkeyhint="next" class="w-full border border-green-200 rounded-xl px-4 py-3 bg-green-50 focus:bg-white focus:border-primary outline-none text-base" placeholder="Họ tên *">
      <input id="oPhone" name="phone" type="tel" inputmode="tel" autocomplete="tel" maxlength="13" enterkeyhint="next" class="w-full border border-green-200 rounded-xl px-4 py-3 bg-green-50 focus:bg-white focus:border-primary outline-none text-base" placeholder="Số điện thoại *">
      <div id="oPhoneErr" class="hidden text-xs text-red-500 font-bold px-1">Số điện thoại chưa đúng (VD: 0912 345 678)</div>
      <input id="oAddr" name="address" type="text" autocomplete="street-address" enterkeyhint="done" class="w-full border border-green-200 rounded-xl px-4 py-3 bg-green-50 focus:bg-white focus:border-primary outline-none text-base" placeholder="Địa chỉ nhận hàng (có thể bổ sung khi shop gọi)">
      <div id="oPkgRow" class="hidden text-xs font-bold text-primaryd bg-green-50 rounded-lg px-3 py-2">Gói: <span id="oPkgLabel"></span></div>
      <div id="oDepositRow" class="hidden text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2"></div>
    </form>
    <div class="mt-4 grid grid-cols-3 gap-1 text-center text-[10px] font-bold text-gray-500"><div>💵 Cọc 20%<br>còn lại khi nhận</div><div>🚚 Freeship<br>toàn quốc</div><div>🔄 Đổi trả nếu<br>in lỗi/hư hỏng</div></div>
    <div class="flex gap-3 justify-center mt-5">
      <button onclick="closeM('orderModal')" class="bg-gray-100 text-gray-600 font-bold px-5 py-3 rounded-xl">Để sau</button>
      <button id="orderSubmit" class="grad-btn disabled:opacity-50 disabled:cursor-not-allowed text-white font-extrabold px-6 py-3 rounded-xl">Đặt cọc</button>
    </div>
  </div>
</div>

{{-- Lightbox phóng to ảnh (lăn chuột / chụm 2 ngón để zoom, kéo để di) --}}
<div id="zoomModal" class="fixed inset-0 z-[70] bg-black/90 hidden overflow-hidden" style="touch-action:none">
  <img id="zoomImg" class="absolute select-none max-w-none" draggable="false" style="transform-origin:0 0" alt="">
  <div class="fixed top-3 right-3 flex gap-2 z-10">
    <button onclick="zoomBy(1.4)" class="w-11 h-11 rounded-full bg-white/90 text-xl font-black">＋</button>
    <button onclick="zoomBy(1/1.4)" class="w-11 h-11 rounded-full bg-white/90 text-xl font-black">－</button>
    <button onclick="closeZoom()" class="w-11 h-11 rounded-full bg-white/90 text-xl font-black">✕</button>
  </div>
  <button onclick="closeZoom()" class="fixed bottom-12 left-1/2 -translate-x-1/2 z-10 bg-white text-ink font-extrabold text-sm px-8 py-3.5 rounded-full shadow-xl2">✕ Đóng</button>
  <div class="fixed bottom-4 inset-x-0 text-center text-white/70 text-xs font-semibold pointer-events-none">Lăn chuột / chụm 2 ngón để phóng to · kéo để di chuyển · chạm nền để đóng</div>
</div>

<script>
const CSRF = document.querySelector('meta[name=csrf-token]').content;
const URLS = { order:"{{ route('thiet-ke.order') }}", saveImg:"{{ route('thiet-ke.save-image') }}" };

// Hiệu ứng Fade Up
const io = new IntersectionObserver((es)=>es.forEach(e=>{ if(e.isIntersecting){ e.target.classList.add('in'); io.unobserve(e.target);} }),{threshold:.12});
document.querySelectorAll('.reveal').forEach(el=>io.observe(el));

// Modal helpers
function openM(id){ const m=document.getElementById(id); m.classList.remove('hidden'); m.classList.add('flex'); document.body.style.overflow='hidden'; }
function closeM(id){ const m=document.getElementById(id); m.classList.add('hidden'); m.classList.remove('flex'); document.body.style.overflow=''; }

// ───── Mã thiết bị (gắn ảnh đã lưu + chống đúp đơn phía server) ─────
function deviceId(){ let d=localStorage.getItem('dali_device'); if(!d){ d='d'+Date.now().toString(36)+Math.random().toString(36).slice(2,12); localStorage.setItem('dali_device',d);} return d; }
const DEVICE=deviceId();
let lastUploadUrl='', selectedPackage='', orderDone=false;
// Thông tin ngân hàng để tạo mã QR đặt cọc (lấy từ Cài đặt admin)
const BANK={ id:"{{ $settings['bank_id'] ?? '' }}", acc:"{{ $settings['bank_acc'] ?? '' }}", name:"{{ $settings['bank_name'] ?? '' }}", label:"{{ $settings['bank_label'] ?? '' }}" };

const fileInput=document.getElementById('fileInput'), dropZone=document.getElementById('dropZone'),
      previewImg=document.getElementById('previewImg'), genBtn=document.getElementById('genBtn');

dropZone.addEventListener('click',()=>fileInput.click());
dropZone.addEventListener('dragover',e=>{e.preventDefault();dropZone.classList.add('border-primary');});
dropZone.addEventListener('dragleave',()=>dropZone.classList.remove('border-primary'));
dropZone.addEventListener('drop',e=>{e.preventDefault();dropZone.classList.remove('border-primary'); if(e.dataTransfer.files[0]){ fileInput.files=e.dataTransfer.files; onFile(); }});
fileInput.addEventListener('change',onFile);
function onFile(){ const f=fileInput.files[0]; if(!f) return; if(previewImg.src && previewImg.src.indexOf('blob:')===0){ try{URL.revokeObjectURL(previewImg.src);}catch(e){} } previewImg.src=URL.createObjectURL(f); previewImg.classList.remove('hidden'); genBtn.disabled=false; lastUploadUrl=''; }

// Nén ảnh ngay trên máy khách trước khi gửi: ảnh điện thoại 4-10MB -> ~0.3-0.6MB
// (tránh giới hạn upload của máy chủ + gửi nhanh hơn nhiều trên 4G).
function compressImage(file, maxEdge, quality, loadedImg){
  maxEdge=maxEdge||1600; quality=quality||0.82;
  function toBlobFrom(srcW,srcH,drawer){
    var m=Math.max(srcW,srcH), k=Math.min(1,maxEdge/m);
    var w=Math.round(srcW*k), h=Math.round(srcH*k);
    var cv=document.createElement('canvas'); cv.width=w; cv.height=h;
    var cx=cv.getContext('2d'); cx.fillStyle='#fff'; cx.fillRect(0,0,w,h); drawer(cx,w,h);
    return new Promise(function(res){ cv.toBlob(function(b){ res(b||file); },'image/jpeg',quality); });
  }
  return new Promise(function(resolve){
    // Tái dùng ảnh ĐÃ giải mã cho phần xem trước (trình duyệt đã tự xoay đúng EXIF):
    // không giải mã lần 2 -> bớt ~1 nửa RAM, tránh treo/tràn RAM trên điện thoại yếu.
    if(loadedImg && loadedImg.complete && loadedImg.naturalWidth){
      var w=loadedImg.naturalWidth,h=loadedImg.naturalHeight;
      if(Math.max(w,h)<=maxEdge && file.size<1200*1024){ resolve(file); return; }
      toBlobFrom(w,h,function(cx,tw,th){ cx.drawImage(loadedImg,0,0,tw,th); }).then(resolve);
      return;
    }
    // Dự phòng: tự giải mã (vẫn dùng <img> để giữ đúng hướng EXIF)
    try{
      var img=new Image();
      img.onload=function(){
        var w=img.naturalWidth,h=img.naturalHeight;
        if(Math.max(w,h)<=maxEdge && file.size<1200*1024){ URL.revokeObjectURL(img.src); resolve(file); return; }
        toBlobFrom(w,h,function(cx,tw,th){ cx.drawImage(img,0,0,tw,th); }).then(function(b){ URL.revokeObjectURL(img.src); resolve(b); });
      };
      img.onerror=function(){ try{URL.revokeObjectURL(img.src);}catch(_){} resolve(file); };
      img.src=URL.createObjectURL(file);
    }catch(e){ resolve(file); }
  });
}

// ───── Tiếp tục: lưu ảnh lên server -> hiện khu chọn cỡ & đặt cọc ─────
async function startOrderFlow(){
  if(!fileInput.files[0]){ alert('Bạn chọn ảnh muốn thiết kế trước nhé 🙂'); try{document.getElementById('upload').scrollIntoView({behavior:'smooth'});}catch(e){} return; }
  // Lưu ảnh gốc lên server 1 lần -> shop luôn có ảnh để thiết kế
  if(!lastUploadUrl){
    var old=genBtn.innerHTML; genBtn.disabled=true; genBtn.innerHTML='<i class="ri-loader-4-line animate-spin"></i> Đang tải ảnh lên…';
    try{
      var blob=await compressImage(fileInput.files[0],1600,0.82,previewImg);
      var fd=new FormData(); fd.append('image',blob,'anh.jpg'); fd.append('device_id',DEVICE);
      var r=await fetch(URLS.saveImg,{method:'POST',headers:{'X-CSRF-TOKEN':CSRF},body:fd}); var d=await r.json();
      if(d&&d.ok&&d.url) lastUploadUrl=d.url;
    }catch(e){}
    genBtn.disabled=false; genBtn.innerHTML=old;
  }
  var sec=document.getElementById('resultSection'); sec.classList.remove('hidden');
  var op=document.getElementById('orderPreviewImg'); if(op && previewImg.src){ op.src=previewImg.src; op.classList.remove('hidden'); }
  paintChips();
  try{ sec.scrollIntoView({behavior:'smooth'}); }catch(e){}
}
genBtn.addEventListener('click', startOrderFlow);

// ───── Chọn kích thước & số màu (ma trận giá từ admin) ─────
const PRICING = @json($pricing);
var selI = Math.min(1, PRICING.sizes.length-1);
var selJ = (function(){ var k = PRICING.colors.findIndex(function(c){ return +c === 36; }); return k >= 0 ? k : 0; })();
function fmtVnd(n){ return n.toLocaleString('vi-VN')+'đ'; }
function curPrice(){ return PRICING.sizes[selI].prices[selJ] || 0; }
function curDeposit(){ return Math.round(curPrice()*0.2/1000)*1000; }
// Số màu hợp lệ theo cỡ: giá 0 = cỡ đó KHÔNG bán mức màu này (vd 120 màu chỉ có ở 1.2×2m).
function colorOK(i,j){ return (PRICING.sizes[i].prices[j]||0) > 0; }
function maxColorIdx(i){ var p=PRICING.sizes[i].prices, m=0; for(var j=0;j<p.length;j++){ if((p[j]||0)>0) m=j; } return m; }
// Thanh nổi mobile: sau khi hiện khu đặt hàng -> nút đặt cọc kèm số tiền cọc
function updateStickyCta(){
  var sc=document.getElementById('stickyCta');
  if(!sc || document.getElementById('resultSection').classList.contains('hidden')) return;
  sc.setAttribute('href','#resultSection');
  sc.innerHTML='<span class="block">🛒 Đặt cọc — '+fmtVnd(curDeposit())+'</span><span class="block text-[10px] font-bold text-white/85">shop nhắn Zalo tư vấn</span>';
}
function paintChips(){
  document.querySelectorAll('#sizeChips .pick').forEach(b=>{
    var on = +b.dataset.i === selI;
    b.className='pick border-2 rounded-xl px-4 py-3 text-sm font-bold '+(on?'border-primary bg-green-50 text-primaryd':'border-green-200 bg-white');
  });
  document.querySelectorAll('.sizePrice').forEach(sp=>{
    var i=+sp.dataset.i, pj=colorOK(i,selJ)?selJ:maxColorIdx(i);   // cỡ không có mức màu đang chọn -> hiện giá ở mức màu cao nhất của cỡ đó
    sp.textContent = fmtVnd(PRICING.sizes[i].prices[pj] || 0);
  });
  document.querySelectorAll('#colorChips .pick').forEach(b=>{
    var j=+b.dataset.j, ok=colorOK(selI,j);
    b.style.display = ok ? '' : 'none';   // ẩn mức màu cỡ này không bán (vd 120 màu ở cỡ nhỏ)
    var on = j === selJ;
    b.className='pick border-2 rounded-xl px-4 py-3 text-sm font-bold '+(on?'border-primary bg-green-50 text-primaryd':'border-green-200 bg-white');
  });
  document.getElementById('resultOrderLabel').textContent = 'Đặt cọc 20% — '+fmtVnd(curDeposit());
  updateStickyCta();
}
document.querySelectorAll('#sizeChips .pick').forEach(b=>b.addEventListener('click',()=>{ selI=+b.dataset.i; if(!colorOK(selI,selJ)) selJ=maxColorIdx(selI); paintChips(); }));
document.querySelectorAll('#colorChips .pick').forEach(b=>b.addEventListener('click',()=>{ selJ=+b.dataset.j; paintChips(); }));
paintChips();
document.getElementById('resultOrderBtn').addEventListener('click',()=>openOrder(PRICING.sizes[selI].label+' — '+fmtVnd(curPrice())+' · '+PRICING.colors[selJ]+' màu · Cọc 20%: '+fmtVnd(curDeposit())));

// ───── Lightbox zoom (lăn chuột / chụm 2 ngón / kéo) ─────
var zScale=1,zX=0,zY=0,zPointers=new Map(),zLastDist=0,zDownX=0,zDownY=0,zDownOnBack=false;
function zApply(){ var im=document.getElementById('zoomImg'); im.style.transform='translate('+zX+'px,'+zY+'px) scale('+zScale+')'; }
function openZoom(src){
  var m=document.getElementById('zoomModal'), im=document.getElementById('zoomImg');
  im.src=src; m.classList.remove('hidden'); document.body.style.overflow='hidden';
  im.onload=function(){
    var s=Math.min(window.innerWidth/im.naturalWidth, window.innerHeight/im.naturalHeight)*0.95;
    zScale=s; zX=(window.innerWidth-im.naturalWidth*s)/2; zY=(window.innerHeight-im.naturalHeight*s)/2; zApply();
  };
  if(im.complete&&im.naturalWidth) im.onload();
}
function closeZoom(){ document.getElementById('zoomModal').classList.add('hidden'); zPointers.clear(); document.body.style.overflow=''; }
function zoomBy(f){ zoomAt(window.innerWidth/2, window.innerHeight/2, f); }
function zoomAt(cx,cy,f){ var ns=Math.min(Math.max(zScale*f,0.05),12); zX=cx-(cx-zX)*(ns/zScale); zY=cy-(cy-zY)*(ns/zScale); zScale=ns; zApply(); }
(function(){
  var m=document.getElementById('zoomModal');
  m.addEventListener('wheel',function(e){ e.preventDefault(); zoomAt(e.clientX,e.clientY, e.deltaY<0?1.15:1/1.15); },{passive:false});
  m.addEventListener('pointerdown',function(e){
    // KHÔNG capture khi bấm nút (✕/＋/－): capture sẽ NUỐT click làm nút chết
    if(e.target && e.target.closest && e.target.closest('button')) return;
    zDownX=e.clientX; zDownY=e.clientY; zDownOnBack=(e.target===m);
    zPointers.set(e.pointerId,{x:e.clientX,y:e.clientY}); m.setPointerCapture(e.pointerId);
  });
  m.addEventListener('pointermove',function(e){
    if(!zPointers.has(e.pointerId)) return;
    var prev=zPointers.get(e.pointerId); zPointers.set(e.pointerId,{x:e.clientX,y:e.clientY});
    if(zPointers.size===1){ zX+=e.clientX-prev.x; zY+=e.clientY-prev.y; zApply(); }
    else if(zPointers.size===2){
      var pts=[...zPointers.values()];
      var d=Math.hypot(pts[0].x-pts[1].x, pts[0].y-pts[1].y);
      if(zLastDist>0){ zoomAt((pts[0].x+pts[1].x)/2,(pts[0].y+pts[1].y)/2, d/zLastDist); }
      zLastDist=d;
    }
  });
  ['pointerup','pointercancel'].forEach(ev=>m.addEventListener(ev,function(e){
    zPointers.delete(e.pointerId); if(zPointers.size<2) zLastDist=0;
    // Chạm NỀN ĐEN (không phải ảnh/nút) mà không kéo -> đóng
    if(ev==='pointerup' && zDownOnBack && zPointers.size===0
       && Math.hypot(e.clientX-zDownX, e.clientY-zDownY) < 8){ zDownOnBack=false; closeZoom(); }
  }));
  m.addEventListener('dblclick',function(e){ zoomAt(e.clientX,e.clientY,2); });
  document.addEventListener('keydown',function(e){ if(e.key==='Escape') closeZoom(); });
})();

// Thanh nổi mobile: CHỈ hiện khi đã cuộn QUA khu hero + tải ảnh (tránh 1 màn hình 3 nút)
(function(){
  const bar=document.getElementById('stickyBar');
  const hero=document.querySelector('section');
  const up=document.getElementById('upload');
  if(!bar||!up) return;
  const seen=new Map();
  const io2=new IntersectionObserver(function(es){
    es.forEach(function(e){ seen.set(e.target, e.isIntersecting); });
    const dangXem=[...seen.values()].some(Boolean);   // hero hoặc khu tải ảnh đang trên màn hình
    bar.classList.toggle('translate-y-full', dangXem);
  },{threshold:0.05});
  if(hero) io2.observe(hero);
  io2.observe(up);
})();

// ───── Đặt cọc: modal nhập thông tin + gửi đơn (đơn thiết kế thủ công) ─────
function openOrder(pkg){
  // Khôi phục form nếu modal đang ở trạng thái "đặt thành công" của đơn trước
  if(orderDone){ orderDone=false;
    document.getElementById('orderForm').classList.remove('hidden');
    document.getElementById('orderSubmit').classList.remove('hidden');
    document.getElementById('orderTitle').textContent='Đặt cọc — shop thiết kế cho bạn';
    document.getElementById('orderDesc').innerHTML='Điền thông tin, đặt cọc 20%. Nhân viên sẽ nhắn Zalo tư vấn &amp; gửi bản thiết kế xem trước.';
  }
  selectedPackage=pkg||''; const row=document.getElementById('oPkgRow');
  if(selectedPackage){ row.classList.remove('hidden'); document.getElementById('oPkgLabel').textContent=selectedPackage; } else { row.classList.add('hidden'); }
  // CỌC 20%: hiện số tiền cọc + còn lại; nút ghi rõ tiền cọc
  const dep=document.getElementById('oDepositRow');
  if(selectedPackage && selectedPackage.indexOf('đ')>-1){
    const total=curPrice(), coc=curDeposit();
    dep.innerHTML='Cọc trước 20%: <b>'+fmtVnd(coc)+'</b> — còn lại '+fmtVnd(total-coc)+' thanh toán khi nhận hàng. Nhân viên sẽ gửi hướng dẫn chuyển khoản khi nhắn Zalo.';
    dep.classList.remove('hidden');
    document.getElementById('orderSubmit').textContent='Đặt cọc 20% — '+fmtVnd(coc);
  } else {
    dep.classList.add('hidden');
    document.getElementById('orderSubmit').textContent='Gửi đơn — shop gọi lại ngay';
  }
  openM('orderModal'); }
document.querySelectorAll('.orderOpen').forEach(b=>b.addEventListener('click',()=>openOrder(b.dataset.package||'')));

function validVnPhone(p){ p=p.replace(/[\s.\-]/g,''); return /^(0|\+?84)(3|5|7|8|9)\d{8}$/.test(p); }
document.getElementById('oPhone').addEventListener('input',()=>{ document.getElementById('oPhoneErr').classList.add('hidden'); document.getElementById('oPhone').classList.remove('border-red-400'); });
document.getElementById('orderForm').addEventListener('submit',function(e){ e.preventDefault(); document.getElementById('orderSubmit').click(); });

document.getElementById('orderSubmit').addEventListener('click', async ()=>{
  const btn=document.getElementById('orderSubmit');
  if(btn.disabled) return;
  const name=document.getElementById('oName').value.trim(), phone=document.getElementById('oPhone').value.trim();
  const addr=document.getElementById('oAddr').value.trim();
  if(!name||!phone){ alert('Vui lòng nhập họ tên và số điện thoại.'); return; }
  if(!validVnPhone(phone)){ document.getElementById('oPhoneErr').classList.remove('hidden'); document.getElementById('oPhone').classList.add('border-red-400'); document.getElementById('oPhone').focus(); return; }
  const fd=new FormData(); fd.append('device_id',DEVICE); fd.append('customer_name',name); fd.append('customer_phone',phone); fd.append('customer_address',addr);
  // Đơn thiết kế THỦ CÔNG: gửi ảnh GỐC khách tải lên; shop tự thiết kế & gửi Zalo (không kèm ảnh AI/bản đồ màu)
  fd.append('await_design','1');
  fd.append('original_url', lastUploadUrl||''); fd.append('result_url',''); fd.append('enhanced_url','');
  fd.append('package',selectedPackage);
  // Giá tranh khách chọn + cọc 20% (server tự tính lại theo cỡ+màu để chống giả mạo)
  const _price=curPrice(); fd.append('price',_price); fd.append('deposit',curDeposit());
  fd.append('size_index',selI); fd.append('color_index',selJ);
  const oldLabel=btn.innerHTML; btn.disabled=true; btn.innerHTML='<i class="ri-loader-4-line animate-spin"></i> Đang gửi…';
  try{ const r=await fetch(URLS.order,{method:'POST',headers:{'X-CSRF-TOKEN':CSRF},body:fd}); const d=await r.json();
    if(!d.ok){ alert(d.msg||'Gửi đơn thất bại, thử lại.'); return; }
    // Trạng thái thành công NGAY TRONG modal: giữ mã đơn + QR đặt cọc + Zalo
    document.getElementById('orderForm').classList.add('hidden');
    document.getElementById('orderSubmit').classList.add('hidden');
    document.getElementById('orderTitle').textContent='✅ Đã nhận đơn '+d.code;
    var coc=curDeposit();
    var html='Đặt cọc <b>'+fmtVnd(coc)+'</b> (20%) để shop bắt đầu thiết kế — còn lại '+fmtVnd(curPrice()-coc)+' trả khi nhận hàng. Nhân viên sẽ nhắn Zalo tư vấn &amp; gửi bản xem trước cho bạn.';
    // QR chuyển khoản đặt cọc (VietQR) — nội dung CK = mã đơn để shop đối soát
    if(BANK.acc && coc>0){
      var qr='https://img.vietqr.io/image/'+BANK.id+'-'+BANK.acc+'-qr_only.png?amount='+coc+'&addInfo='+encodeURIComponent(d.code)+'&accountName='+encodeURIComponent(BANK.name);
      html+='<div class="mt-3 bg-green-50 border-2 border-green-200 rounded-2xl p-3 text-center">'
        +'<div class="text-xs font-bold text-primaryd mb-2">Quét mã để đặt cọc '+fmtVnd(coc)+'</div>'
        +'<img src="'+qr+'" alt="QR đặt cọc" class="w-44 h-44 mx-auto rounded-xl border border-green-200 bg-white">'
        +'<div class="text-xs text-gray-600 mt-2 leading-relaxed">'+BANK.label+' · <b>'+BANK.acc+'</b><br>'+BANK.name+'<br>Nội dung CK: <b>'+d.code+'</b></div>'
        +'</div>';
    }
    html+='<a href="https://zalo.me/0856911698" target="_blank" class="inline-block mt-3 bg-[#0068FF] text-white font-extrabold px-5 py-2.5 rounded-xl">💬 Gửi ảnh CK qua Zalo (mã '+d.code+')</a>';
    document.getElementById('orderDesc').innerHTML=html;
    orderDone=true;
  }catch(e){ alert('Lỗi kết nối, thử lại sau.'); }
  finally{ btn.disabled=false; btn.innerHTML=oldLabel; }
});
</script>
</body>
</html>
