<!DOCTYPE html>
<html lang="vi" class="scroll-smooth">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Tranh Tô Màu Số Hóa Theo Ảnh — Thiết Kế Từ Ảnh Của Bạn | DALI</title>
<meta name="description" content="Biến ảnh của bạn thành tranh tô màu số hóa độc bản. Thiết kế từ ảnh thật, tặng bộ màu & cọ, giao toàn quốc. Tải ảnh xem trước miễn phí.">
<meta property="og:title" content="Tranh Tô Màu Số Hóa Theo Ảnh Cá Nhân | DALI">
<meta property="og:description" content="Tải ảnh — AI thiết kế thành tranh tô màu độc bản. Tặng màu & cọ, giao toàn quốc.">
<meta property="og:image" content="{{ asset('images/logo_dali.png') }}">
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
  <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
    <a href="{{ route('home') }}" class="text-2xl font-black tracking-wide text-primaryd">DAL<span class="text-primary">I</span></a>
    <nav class="hidden md:flex items-center gap-7 text-sm font-semibold text-gray-600">
      <a href="#mau-tranh" class="hover:text-primary">Mẫu tranh</a>
      <a href="#danh-gia" class="hover:text-primary">Đánh giá</a>
      <a href="#bang-gia" class="hover:text-primary">Bảng giá</a>
      <a href="#faq" class="hover:text-primary">FAQ</a>
    </nav>
    <a href="#upload" class="grad-btn text-white text-sm font-extrabold px-5 py-2.5 rounded-full">Tải ảnh ngay</a>
  </div>
</header>

{{-- ════════ SECTION 1 — HERO + TRUST ════════ --}}
<section class="relative overflow-hidden">
  <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary/10 rounded-full blur-3xl"></div>
  <div class="max-w-6xl mx-auto px-4 pt-12 pb-6 grid md:grid-cols-2 gap-10 items-center relative">
    <div class="reveal">
      <div class="inline-flex items-center gap-2 bg-white border border-green-200 rounded-full px-3 py-1 text-xs font-bold text-primaryd mb-5 shadow-sm">
        <i class="ri-fire-fill text-accent"></i> Hơn 10.000 khách hàng đã đặt tranh
      </div>
      <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black leading-tight">
        Biến <span class="text-primary">ảnh của bạn</span> thành bức tranh số hóa độc nhất
      </h1>
      <ul class="mt-6 space-y-2.5 text-[15px] font-semibold text-gray-700">
        <li class="flex items-center gap-2"><i class="ri-checkbox-circle-fill text-primary text-lg"></i> Thiết kế từ ảnh thật</li>
        <li class="flex items-center gap-2"><i class="ri-checkbox-circle-fill text-primary text-lg"></i> Tặng bộ màu &amp; cọ</li>
        <li class="flex items-center gap-2"><i class="ri-checkbox-circle-fill text-primary text-lg"></i> Giao toàn quốc</li>
      </ul>
      <div class="mt-8 flex flex-wrap gap-3">
        <a href="#upload" class="grad-btn text-white text-base font-extrabold px-7 py-4 rounded-2xl shadow-xl2 flex items-center gap-2"><i class="ri-upload-cloud-2-line"></i> Tải ảnh lên miễn phí</a>
        <a href="#mau-tranh" class="bg-white border-2 border-green-200 text-primaryd text-base font-bold px-6 py-4 rounded-2xl hover:border-primary transition flex items-center gap-2"><i class="ri-image-line"></i> Xem mẫu tranh</a>
      </div>
    </div>
    {{-- Mockup: Ảnh gốc -> Thành phẩm --}}
    <div class="reveal relative">
      <div class="grid grid-cols-[1fr_auto_1fr] gap-3 items-center">
        <figure class="rounded-2xl overflow-hidden shadow-xl2 bg-white border border-green-100 rotate-[-3deg]">
          <img src="{{ asset('images/thiet-ke/be-goc.jpg') }}" class="w-full h-56 object-cover" alt="Ảnh gốc">
          <figcaption class="text-[11px] font-bold text-gray-500 text-center py-1.5">📷 Ảnh gốc</figcaption>
        </figure>
        <div class="text-primary text-3xl font-black">→</div>
        <figure class="rounded-2xl overflow-hidden shadow-xl2 bg-white border-2 border-primary/40 rotate-[3deg] scale-105">
          <img src="{{ asset('images/thiet-ke/be-art.jpg') }}" class="w-full h-56 object-cover" alt="Thành phẩm">
          <figcaption class="text-[11px] font-extrabold text-primaryd text-center py-1.5">🖼️ Thành phẩm</figcaption>
        </figure>
      </div>
    </div>
  </div>
  {{-- TRUST BAR --}}
  <div class="max-w-6xl mx-auto px-4 pb-8">
    <div class="flex flex-wrap justify-center gap-3 text-sm font-bold">
      <span class="bg-white border border-green-200 rounded-full px-4 py-2 shadow-sm">⭐ 4.9/5 đánh giá</span>
      <span class="bg-white border border-green-200 rounded-full px-4 py-2 shadow-sm">🚚 Giao toàn quốc</span>
      <span class="bg-white border border-green-200 rounded-full px-4 py-2 shadow-sm">🛡️ Đổi trả nếu lỗi</span>
      <span class="bg-white border border-green-200 rounded-full px-4 py-2 shadow-sm">🎨 Thiết kế miễn phí</span>
    </div>
  </div>
</section>

{{-- ════════ SECTION 2 — UPLOAD TOOL (chức năng thật) ════════ --}}
<section id="upload" class="max-w-3xl mx-auto px-4 py-12 reveal">
  <div class="text-center mb-6">
    <h2 class="text-2xl sm:text-3xl font-black">Tải ảnh lên để xem bản xem trước <span class="text-primary">miễn phí</span></h2>
    <p class="text-gray-500 mt-2 text-sm">Mỗi máy có <b>{{ \App\Models\DesignQuota::FREE }} lượt thử miễn phí</b> — ưng ý rồi mới đặt.</p>
  </div>

  <div class="inline-flex items-center gap-2 bg-green-50 border border-green-200 rounded-full px-4 py-2 text-sm font-bold text-primaryd mb-4">
    <i class="ri-flashlight-fill text-accent"></i> Lượt tạo còn lại: <span id="remainBadge">…</span>
  </div>

  <div class="bg-white rounded-3xl shadow-xl2 border border-green-100 p-6 sm:p-8">
    <input type="file" id="fileInput" accept="image/png,image/jpeg,image/webp" class="hidden">
    <div id="dropZone" class="border-2 border-dashed border-green-300 rounded-2xl bg-green-50/60 px-6 py-12 text-center cursor-pointer hover:border-primary hover:bg-green-50 transition">
      <div class="w-16 h-16 mx-auto mb-3 rounded-full grad flex items-center justify-center text-white text-3xl"><i class="ri-image-add-line"></i></div>
      <div class="font-extrabold text-lg">Kéo thả hoặc nhấn để chọn ảnh</div>
      <div class="text-xs text-gray-400 mt-1">PNG · JPG · WEBP — ảnh càng rõ kết quả càng đẹp</div>
      <span class="inline-block mt-4 grad-btn text-white text-sm font-extrabold px-6 py-3 rounded-xl"><i class="ri-folder-image-line"></i> Chọn ảnh</span>
    </div>
    <img id="previewImg" class="hidden mt-5 mx-auto max-h-80 rounded-2xl border border-green-100" alt="">
    <div class="mt-5 flex flex-wrap items-center gap-3">
      <button id="genBtn" disabled class="grad-btn disabled:opacity-40 disabled:cursor-not-allowed text-white text-base font-extrabold px-7 py-4 rounded-2xl flex items-center gap-2"><i class="ri-sparkling-2-line"></i> Tạo bản thiết kế</button>
      <span class="text-xs text-gray-400">⏱️ Nhận bản xem trước ngay · bản in giao trong 24–72 giờ</span>
    </div>
  </div>

</section>

{{-- KẾT QUẢ (before/after ảnh thật của khách — khổ rộng, bấm để zoom) --}}
<section class="max-w-5xl mx-auto px-4">
  <div id="resultSection" class="hidden mt-2 bg-white rounded-3xl shadow-xl2 border-2 border-primary/60 p-6 sm:p-8">
    <div class="font-black text-xl mb-1 flex items-center gap-2"><i class="ri-checkbox-circle-fill text-primary"></i> Tác phẩm của bạn đã sẵn sàng!</div>
    <div id="restoreNote" class="hidden mb-2 bg-green-50 border border-green-200 rounded-xl px-4 py-2 text-xs font-bold text-primaryd">💾 Kết quả lần trước của bạn vẫn được giữ trên máy này — tải ảnh mới để tạo thêm bất cứ lúc nào!</div>
    <div class="text-xs text-gray-400 font-semibold mb-4">🔍 Bấm vào ảnh để phóng to, xem chi tiết từng nét</div>
    <div class="grid sm:grid-cols-[1fr_auto_1fr] gap-4 items-center">
      <figure class="text-center"><img id="rOriginal" onclick="openZoom(this.src)" class="w-full aspect-square object-contain rounded-2xl border border-green-100 bg-green-50 cursor-zoom-in" alt=""><figcaption class="text-xs font-bold text-gray-500 mt-2">📷 Ảnh gốc của bạn</figcaption></figure>
      <div class="text-primary text-3xl font-black text-center rotate-90 sm:rotate-0">→</div>
      <figure class="text-center"><img id="rEnhanced" onclick="openZoom(this.src)" class="w-full aspect-square object-contain rounded-2xl border-2 border-primary/40 bg-green-50 cursor-zoom-in shadow-lg" alt=""><figcaption class="text-sm font-extrabold text-primary mt-2">✨ Bản DALI tăng cường AI — bấm để phóng to</figcaption></figure>
    </div>
    <div class="mt-5 bg-amber-50 border border-amber-200 rounded-2xl p-4 text-center text-sm font-semibold text-amber-700">
      🌟 <b>Đẹp đúng không?</b> Đây là bức tranh <b>độc nhất vô nhị</b> — không ai có tấm thứ hai!
    </div>

    {{-- CHỌN KÍCH THƯỚC & SỐ MÀU --}}
    <div class="mt-5 bg-green-50/70 border-2 border-green-200 rounded-2xl p-5 sm:p-6">
      <div class="font-extrabold text-lg mb-1">🛒 Đặt bức tranh này về nhà</div>
      <div class="text-xs text-gray-500 mb-4">Chọn kích thước &amp; số màu — giá đã gồm canvas in sẵn, bộ màu, cọ &amp; giao hàng.</div>
      <div class="font-bold text-sm mb-2">🖼️ Kích thước <span class="text-xs text-gray-400 font-semibold">({{ $pricing['sizes'][0]['note'] ?? '' }})</span></div>
      <div class="flex flex-wrap gap-2" id="sizeChips">
        @foreach($pricing['sizes'] as $i => $s)
        <button type="button" data-i="{{ $i }}" class="pick border-2 rounded-xl px-4 py-2.5 text-sm font-bold bg-white">{{ $s['label'] }}<span class="block text-xs font-extrabold text-primaryd sizePrice" data-i="{{ $i }}"></span></button>
        @endforeach
      </div>
      <div class="font-bold text-sm mb-2 mt-4">🎨 Số màu <span class="text-xs text-gray-400 font-semibold">(nhiều màu hơn = chi tiết &amp; giống ảnh hơn)</span></div>
      <div class="flex flex-wrap gap-2" id="colorChips">
        @foreach($pricing['colors'] as $j => $c)
        <button type="button" data-j="{{ $j }}" class="pick border-2 rounded-xl px-4 py-2.5 text-sm font-bold bg-white">{{ $c }} màu</button>
        @endforeach
      </div>
      <div class="mt-5 flex flex-wrap items-center gap-3">
        <button id="resultOrderBtn" class="grad-btn text-white font-extrabold text-base px-8 py-4 rounded-2xl flex items-center gap-2"><i class="ri-shopping-bag-3-fill"></i> <span id="resultOrderLabel">Đặt tranh này</span></button>
        <span class="text-xs text-gray-500">Đặt xong được <b>+{{ \App\Models\DesignQuota::ORDER_BONUS }} lượt tạo</b> để thử thêm ảnh khác.</span>
      </div>
    </div>
  </div>
</section>

{{-- ════════ SECTION 3 — BEFORE / AFTER ════════ --}}
<section id="mau-tranh" class="max-w-6xl mx-auto px-4 py-12 reveal">
  <h2 class="text-2xl sm:text-3xl font-black text-center">Khách hàng đã biến ảnh thành <span class="text-primary">tác phẩm nghệ thuật</span> thế nào?</h2>
  <p class="text-center text-gray-500 mt-2 mb-8 text-sm">Từ một tấm ảnh đời thường → một bức tranh treo tường</p>
  <div class="grid md:grid-cols-3 gap-5">
    @foreach([
      ['be',  'Bé yêu nhà bạn',     'TK310 — thiết kế từ ảnh bé'],
      ['ba',  'Tặng ông bà, cha mẹ','TK305 — món quà chạm tim'],
      ['sen', 'Hoa sen nghệ thuật', 'TK320 — tranh treo phòng khách'],
    ] as [$k, $label, $sub])
    <div class="bg-white rounded-3xl border border-green-100 shadow-lg overflow-hidden hover:-translate-y-1.5 hover:shadow-xl2 transition">
      <div class="grid grid-cols-2">
        <img src="{{ asset('images/thiet-ke/'.$k.'-goc.jpg') }}" loading="lazy" class="w-full h-44 object-cover cursor-zoom-in" onclick="openZoom(this.src)" alt="Ảnh gốc {{ $label }}">
        <img src="{{ asset('images/thiet-ke/'.$k.'-art.jpg') }}" loading="lazy" class="w-full h-44 object-cover cursor-zoom-in" onclick="openZoom(this.src)" alt="Thành phẩm {{ $label }}">
      </div>
      <div class="flex text-[10px] font-bold text-gray-400 text-center"><span class="flex-1 py-1">Ảnh gốc</span><span class="flex-1 py-1 text-primaryd">Thành phẩm</span></div>
      <div class="px-5 py-3 text-center border-t border-green-50"><div class="font-extrabold">{{ $label }}</div><div class="text-[11px] text-gray-400 font-semibold">{{ $sub }}</div></div>
    </div>
    @endforeach
  </div>
</section>

{{-- ════════ SECTION 4 — VÌ SAO CHỌN CHÚNG TÔI ════════ --}}
<section class="max-w-6xl mx-auto px-4 py-12 reveal">
  <h2 class="text-2xl sm:text-3xl font-black text-center mb-8">Vì sao 10.000+ khách chọn <span class="text-primary">DALI</span>?</h2>
  <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
    @foreach([
      ['ri-brush-4-line','Thiết kế riêng theo ảnh','Mỗi bức là độc bản, thiết kế từ chính ảnh của bạn.'],
      ['ri-gallery-line','Canvas cao cấp','In trên canvas dày, màu lên chuẩn, treo tường sang trọng.'],
      ['ri-palette-line','Bộ màu đầy đủ','Tặng kèm đủ màu acrylic + cọ, mở hộp là tô được ngay.'],
      ['ri-emotion-happy-line','Dễ tô cho người mới','Bản đồ số rõ ràng, ai cũng tô đẹp dù chưa từng vẽ.'],
      ['ri-gift-2-line','Quà tặng ý nghĩa','Món quà chạm cảm xúc cho người thương, dịp đặc biệt.'],
      ['ri-customer-service-2-line','Hỗ trợ trọn đời','Tư vấn tô màu, đổi trả nếu lỗi, đồng hành cùng bạn.'],
    ] as $f)
    <div class="bg-white rounded-3xl border border-green-100 p-6 shadow-sm hover:shadow-xl2 hover:-translate-y-1 transition">
      <div class="w-12 h-12 rounded-2xl bg-green-50 text-primary text-2xl flex items-center justify-center mb-3"><i class="{{ $f[0] }}"></i></div>
      <h3 class="font-extrabold text-lg mb-1">{{ $f[1] }}</h3>
      <p class="text-sm text-gray-500 leading-relaxed">{{ $f[2] }}</p>
    </div>
    @endforeach
  </div>
</section>

{{-- ════════ SECTION 5 — QUY TRÌNH 3 BƯỚC ════════ --}}
<section class="max-w-5xl mx-auto px-4 py-12 reveal">
  <h2 class="text-2xl sm:text-3xl font-black text-center mb-10">Chỉ <span class="text-primary">3 bước</span> đơn giản</h2>
  <div class="grid md:grid-cols-3 gap-6 relative">
    @foreach([['1','ri-upload-cloud-2-line','Gửi ảnh','Tải lên tấm ảnh bạn yêu thích nhất.'],['2','ri-magic-line','Nhận bản thiết kế','AI tạo bản tô số + xem trước miễn phí.'],['3','ri-truck-line','Nhận tranh tận nhà','Shop in & giao bộ tranh kèm màu, cọ.']] as $st)
    <div class="text-center">
      <div class="w-16 h-16 mx-auto rounded-full grad text-white flex items-center justify-center text-3xl shadow-xl2"><i class="{{ $st[1] }}"></i></div>
      <div class="mt-3 inline-flex items-center justify-center w-7 h-7 rounded-full bg-accent text-white text-sm font-black">{{ $st[0] }}</div>
      <h3 class="font-extrabold text-lg mt-2">{{ $st[2] }}</h3>
      <p class="text-sm text-gray-500 mt-1">{{ $st[3] }}</p>
    </div>
    @endforeach
  </div>
</section>

{{-- ════════ SECTION 6 — VIDEO REVIEW ════════ --}}
<section id="danh-gia" class="max-w-6xl mx-auto px-4 py-12 reveal">
  <h2 class="text-2xl sm:text-3xl font-black text-center mb-8">Khách hàng nói gì về chúng tôi</h2>
  <div class="flex gap-5 overflow-x-auto no-scrollbar snap-x pb-3">
    @foreach([['Minh Tuấn','Hà Nội','phat-art'],['Lan Phương','TP.HCM','be-art'],['Hoàng Anh','Đà Nẵng','sen-art']] as $v)
    <div class="snap-start shrink-0 w-72 bg-white rounded-3xl border border-green-100 shadow-lg overflow-hidden">
      <div class="relative">
        <img src="{{ asset('images/thiet-ke/'.$v[2].'.jpg') }}" loading="lazy" class="w-full h-40 object-cover" alt="">
        <div class="absolute inset-0 flex items-center justify-center"><div class="w-14 h-14 rounded-full glass flex items-center justify-center text-primaryd text-2xl shadow-lg"><i class="ri-play-fill"></i></div></div>
      </div>
      <div class="p-4">
        <div class="text-accent text-sm">★★★★★</div>
        <div class="font-extrabold mt-1">{{ $v[0] }}</div>
        <div class="text-xs text-gray-400">{{ $v[1] }}</div>
      </div>
    </div>
    @endforeach
  </div>
</section>

{{-- ════════ SECTION 7 — SOCIAL PROOF (count up) ════════ --}}
<section class="bg-green-50 py-14 reveal">
  <div class="max-w-5xl mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
    @foreach([['10000','+','Tranh đã giao'],['4.9','/5','Đánh giá'],['95','%','Khách hài lòng'],['63','','Tỉnh thành']] as $s)
    <div>
      <div class="text-3xl sm:text-4xl font-black text-primaryd"><span class="count" data-to="{{ $s[0] }}">0</span>{{ $s[1] }}</div>
      <div class="text-sm font-semibold text-gray-500 mt-1">{{ $s[2] }}</div>
    </div>
    @endforeach
  </div>
</section>

{{-- ════════ SECTION 8 — SẢN PHẨM NHẬN ĐƯỢC ════════ --}}
<section class="max-w-6xl mx-auto px-4 py-12 reveal">
  <h2 class="text-2xl sm:text-3xl font-black text-center mb-8">Mở hộp bạn nhận được gì?</h2>
  <div class="grid md:grid-cols-2 gap-8 items-center">
    <figure class="relative">
      <img src="{{ asset('images/thiet-ke/mo-hop.jpg') }}" loading="lazy" class="rounded-3xl shadow-xl2 w-full aspect-square object-cover cursor-zoom-in" onclick="openZoom(this.src)" alt="Bộ tranh tô màu số DALI hoàn chỉnh: canvas in số, bộ màu, cọ vẽ">
      <figcaption class="absolute bottom-3 left-3 glass rounded-full px-4 py-1.5 text-xs font-extrabold text-primaryd shadow">🖼️ Thành phẩm thật từ khách DALI</figcaption>
    </figure>
    <div class="grid sm:grid-cols-2 gap-4">
      @foreach([['ri-image-2-line','Canvas in số','Tranh in sẵn ô số trên canvas cao cấp'],['ri-palette-line','Bộ màu','Đủ màu acrylic theo bản thiết kế'],['ri-brush-line','Cọ vẽ','Bộ cọ nhiều cỡ, tô chi tiết dễ dàng'],['ri-file-list-3-line','Ảnh hướng dẫn','Bảng mã màu rõ ràng, dễ làm theo'],['ri-links-line','Móc treo','Tặng kèm móc, treo tường ngay']] as $i)
      <div class="flex items-start gap-3 bg-white rounded-2xl border border-green-100 p-4 shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-green-50 text-primary text-xl flex items-center justify-center shrink-0"><i class="{{ $i[0] }}"></i></div>
        <div><div class="font-extrabold text-sm">{{ $i[1] }}</div><div class="text-xs text-gray-500">{{ $i[2] }}</div></div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ════════ SECTION 9 — BẢNG GIÁ (ma trận Kích thước × Số màu, quản trị trong admin) ════════ --}}
<section id="bang-gia" class="max-w-5xl mx-auto px-4 py-12 reveal">
  <h2 class="text-2xl sm:text-3xl font-black text-center mb-2">Bảng giá tranh thiết kế</h2>
  <p class="text-center text-gray-500 mb-8 text-sm">Giá đã gồm thiết kế từ ảnh + canvas in sẵn + bộ màu &amp; cọ + giao toàn quốc</p>
  <div class="bg-white rounded-3xl border border-green-100 shadow-xl2 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full" style="border-collapse:collapse;min-width:640px">
        <thead>
          <tr class="border-b border-gray-100">
            <th class="text-left px-5 py-4 font-black text-ink">Kích thước</th>
            @foreach($pricing['colors'] as $c)
            <th class="text-left px-5 py-4 font-black text-ink whitespace-nowrap">{{ $c }} màu</th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          @foreach($pricing['sizes'] as $s)
          <tr class="border-b border-gray-50 hover:bg-green-50/40 transition">
            <td class="px-5 py-4">
              <div class="font-black text-ink whitespace-nowrap">{{ $s['label'] }}</div>
              @if(!empty($s['note']))<div class="text-[11px] text-gray-400 font-semibold">{{ $s['note'] }}</div>@endif
            </td>
            @foreach($s['prices'] as $v)
            <td class="px-5 py-4 font-black text-ink whitespace-nowrap">{{ number_format($v, 0, ',', '.') }}đ</td>
            @endforeach
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <div class="text-center mt-6">
    <a href="#upload" class="grad-btn inline-flex items-center gap-2 text-white font-extrabold px-7 py-4 rounded-2xl"><i class="ri-upload-cloud-2-line"></i> Tải ảnh lên — xem trước miễn phí</a>
  </div>
</section>

{{-- ════════ SECTION 10 — FAQ ════════ --}}
<section id="faq" class="max-w-3xl mx-auto px-4 py-12 reveal">
  <h2 class="text-2xl sm:text-3xl font-black text-center mb-8">Câu hỏi thường gặp</h2>
  <div class="space-y-3">
    @foreach([
      ['Không biết vẽ có tô được không?','Hoàn toàn được! Tranh chia sẵn từng ô có số, bạn chỉ việc tô màu đúng số tương ứng. Người chưa từng vẽ vẫn ra thành phẩm đẹp.'],
      ['Bao lâu nhận hàng?','Bản thiết kế xem trước có ngay. Tranh in &amp; giao trong 24–72 giờ tùy khu vực (toàn quốc).'],
      ['Ảnh chụp điện thoại có làm được không?','Có. Chỉ cần ảnh rõ mặt/chủ thể. AI sẽ tăng cường nét trước khi thiết kế.'],
      ['Có được xem trước không?','Có — bạn tải ảnh lên và xem bản thiết kế MIỄN PHÍ trước khi quyết định đặt.'],
      ['Có bảo hành không?','Có. Đổi trả miễn phí nếu tranh in lỗi, sai thiết kế hoặc hư hỏng khi vận chuyển.'],
    ] as $q)
    <details class="bg-white rounded-2xl border border-green-100 p-5 shadow-sm">
      <summary class="flex items-center justify-between font-extrabold"><span>{{ $q[0] }}</span><i class="ri-add-line faq-ic text-primary text-xl transition-transform"></i></summary>
      <p class="mt-3 text-sm text-gray-600 leading-relaxed">{{ $q[1] }}</p>
    </details>
    @endforeach
  </div>
</section>

{{-- ════════ SECTION 11 — CTA CUỐI ════════ --}}
<section class="grad text-white text-center py-16 px-4 reveal">
  <h2 class="text-3xl sm:text-4xl font-black max-w-2xl mx-auto leading-tight">Biến kỷ niệm thành tác phẩm nghệ thuật ngay hôm nay</h2>
  <p class="text-white/85 mt-3 mb-7">Thử miễn phí — không ưng, không mất gì.</p>
  <a href="#upload" class="inline-flex items-center gap-2 bg-white text-primaryd font-extrabold text-lg px-8 py-4 rounded-2xl hover:scale-105 transition shadow-xl2"><i class="ri-upload-cloud-2-line"></i> Tải ảnh lên miễn phí</a>
</section>

<footer class="bg-ink text-white/50 text-center text-xs py-8 px-4">
  © 2026 DALI · Tranh Tô Màu Số Hóa Theo Ảnh · 🇻🇳 Giao toàn quốc
</footer>

{{-- ════════ FLOATING (mobile sticky CTA) ════════ --}}
<div class="md:hidden fixed bottom-0 inset-x-0 z-40 glass border-t border-green-200 grid grid-cols-2 gap-2 p-2">
  <a href="#upload" class="grad-btn text-white font-extrabold py-3 rounded-xl text-center text-sm">📸 Tải ảnh</a>
  <a href="https://zalo.me/0856911698" target="_blank" rel="noopener" class="bg-white border border-green-200 text-primaryd font-extrabold py-3 rounded-xl text-center text-sm">💬 Chat Zalo</a>
</div>
<div class="md:hidden h-16"></div>

{{-- ════════ MODALS ════════ --}}
<div id="confirmModal" class="fixed inset-0 z-50 bg-black/50 hidden items-center justify-center p-4">
  <div class="bg-white rounded-3xl max-w-sm w-full p-7 text-center">
    <div class="text-4xl mb-2">✨</div>
    <h3 class="text-xl font-black mb-2">Tạo bản thiết kế?</h3>
    <p class="text-sm text-gray-500 mb-5">Bạn còn <b id="confirmRemain">0</b> lượt. Mỗi lần tạo dùng <b>1 lượt</b> và mất ~20–60 giây.</p>
    <div class="flex gap-3 justify-center">
      <button onclick="closeM('confirmModal')" class="bg-gray-100 text-gray-600 font-bold px-5 py-3 rounded-xl">Huỷ</button>
      <button id="confirmGo" class="grad-btn text-white font-extrabold px-6 py-3 rounded-xl">Tạo ngay</button>
    </div>
  </div>
</div>
<div id="loadingModal" class="fixed inset-0 z-50 bg-black/50 hidden items-center justify-center p-4">
  <div class="bg-white rounded-3xl max-w-sm w-full p-8 text-center">
    <div class="w-12 h-12 mx-auto mb-4 border-4 border-green-100 border-t-primary rounded-full animate-spin"></div>
    <h3 class="text-lg font-black">Đang thiết kế bằng AI…</h3>
    <p class="text-sm text-gray-500 mt-1">Vui lòng đợi 20–60 giây, đừng tắt trang.</p>
  </div>
</div>
<div id="orderModal" class="fixed inset-0 z-50 bg-black/50 hidden items-center justify-center p-4">
  <div class="bg-white rounded-3xl max-w-sm w-full p-7">
    <div class="text-center"><div class="text-4xl mb-2">🛍️</div><h3 id="orderTitle" class="text-xl font-black mb-1">Đặt hàng tranh thiết kế</h3>
    <p id="orderDesc" class="text-sm text-gray-500 mb-4">Điền thông tin, shop sẽ liên hệ &amp; làm tranh cho bạn. Đặt xong được <b>+{{ \App\Models\DesignQuota::ORDER_BONUS }} lượt</b>.</p></div>
    <div class="space-y-3 text-left">
      <input id="oName" class="w-full border border-green-200 rounded-xl px-4 py-3 bg-green-50 focus:bg-white focus:border-primary outline-none text-sm" placeholder="Họ tên *">
      <input id="oPhone" class="w-full border border-green-200 rounded-xl px-4 py-3 bg-green-50 focus:bg-white focus:border-primary outline-none text-sm" placeholder="Số điện thoại *">
      <input id="oAddr" class="w-full border border-green-200 rounded-xl px-4 py-3 bg-green-50 focus:bg-white focus:border-primary outline-none text-sm" placeholder="Địa chỉ nhận hàng">
      <div id="oPkgRow" class="hidden text-xs font-bold text-primaryd bg-green-50 rounded-lg px-3 py-2">Gói: <span id="oPkgLabel"></span></div>
    </div>
    <div class="flex gap-3 justify-center mt-5">
      <button onclick="closeM('orderModal')" class="bg-gray-100 text-gray-600 font-bold px-5 py-3 rounded-xl">Để sau</button>
      <button id="orderSubmit" class="grad-btn text-white font-extrabold px-6 py-3 rounded-xl">Gửi đơn</button>
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
  <div class="fixed bottom-4 inset-x-0 text-center text-white/70 text-xs font-semibold pointer-events-none">Lăn chuột / chụm 2 ngón để phóng to · kéo để di chuyển</div>
</div>

<script>
const CSRF = document.querySelector('meta[name=csrf-token]').content;
const URLS = { quota:"{{ route('thiet-ke.quota') }}", gen:"{{ route('thiet-ke.generate') }}", order:"{{ route('thiet-ke.order') }}", status:"{{ route('thiet-ke.status') }}" };

// Hiệu ứng Fade Up
const io = new IntersectionObserver((es)=>es.forEach(e=>{ if(e.isIntersecting){ e.target.classList.add('in'); io.unobserve(e.target);} }),{threshold:.12});
document.querySelectorAll('.reveal').forEach(el=>io.observe(el));

// Count up
const cio = new IntersectionObserver((es)=>es.forEach(e=>{ if(!e.isIntersecting) return; cio.unobserve(e.target);
  const el=e.target, to=parseFloat(el.dataset.to), dec=(to%1!==0)?1:0; let cur=0; const step=to/40;
  const t=setInterval(()=>{ cur+=step; if(cur>=to){cur=to;clearInterval(t);} el.textContent=(dec?cur.toFixed(1):Math.floor(cur)).toLocaleString('vi-VN'); },28);
}),{threshold:.5});
document.querySelectorAll('.count').forEach(el=>cio.observe(el));

// Modal helpers
function openM(id){ const m=document.getElementById(id); m.classList.remove('hidden'); m.classList.add('flex'); }
function closeM(id){ const m=document.getElementById(id); m.classList.add('hidden'); m.classList.remove('flex'); }

// ───── Công cụ thiết kế (device id + quota + tạo + đặt hàng) ─────
function deviceId(){ let d=localStorage.getItem('dali_device'); if(!d){ d='d'+Date.now().toString(36)+Math.random().toString(36).slice(2,12); localStorage.setItem('dali_device',d);} return d; }
const DEVICE=deviceId(); let remaining=0, lastResultUrl='', lastEnhancedUrl='', selectedPackage='';

const fileInput=document.getElementById('fileInput'), dropZone=document.getElementById('dropZone'),
      previewImg=document.getElementById('previewImg'), genBtn=document.getElementById('genBtn');

function badgeText(d){ return d&&d.unlimited ? '∞ (máy test)' : (remaining+' lượt'); }
async function refreshQuota(){ try{ const r=await fetch(URLS.quota+'?device_id='+encodeURIComponent(DEVICE)); const d=await r.json(); remaining=d.remaining??0; document.getElementById('remainBadge').textContent=badgeText(d); }catch(e){ document.getElementById('remainBadge').textContent='—'; } }
refreshQuota();

dropZone.addEventListener('click',()=>fileInput.click());
dropZone.addEventListener('dragover',e=>{e.preventDefault();dropZone.classList.add('border-primary');});
dropZone.addEventListener('dragleave',()=>dropZone.classList.remove('border-primary'));
dropZone.addEventListener('drop',e=>{e.preventDefault();dropZone.classList.remove('border-primary'); if(e.dataTransfer.files[0]){ fileInput.files=e.dataTransfer.files; onFile(); }});
fileInput.addEventListener('change',onFile);
function onFile(){ const f=fileInput.files[0]; if(!f) return; previewImg.src=URL.createObjectURL(f); previewImg.classList.remove('hidden'); genBtn.disabled=false; }

genBtn.addEventListener('click',()=>{ if(!fileInput.files[0]){alert('Vui lòng chọn ảnh.');return;} if(remaining<=0){ outOfQuota(); return;} document.getElementById('confirmRemain').textContent=remaining; openM('confirmModal'); });

// Nén ảnh ngay trên máy khách trước khi gửi: ảnh điện thoại 4-10MB -> ~0.3-0.6MB
// (tránh giới hạn upload của máy chủ + gửi nhanh hơn nhiều trên 4G).
function compressImage(file, maxEdge, quality){
  maxEdge=maxEdge||1800; quality=quality||0.85;
  return new Promise(function(resolve){
    try{
      var img=new Image();
      img.onload=function(){
        var w=img.naturalWidth,h=img.naturalHeight,m=Math.max(w,h);
        if(m<=maxEdge && file.size<1200*1024){ URL.revokeObjectURL(img.src); resolve(file); return; }
        var k=Math.min(1,maxEdge/m); w=Math.round(w*k); h=Math.round(h*k);
        var cv=document.createElement('canvas'); cv.width=w; cv.height=h;
        var cx=cv.getContext('2d'); cx.fillStyle='#fff'; cx.fillRect(0,0,w,h); cx.drawImage(img,0,0,w,h);
        cv.toBlob(function(b){ URL.revokeObjectURL(img.src); resolve(b||file); },'image/jpeg',quality);
      };
      img.onerror=function(){ resolve(file); };
      img.src=URL.createObjectURL(file);
    }catch(e){ resolve(file); }
  });
}

document.getElementById('confirmGo').addEventListener('click', async ()=>{
  closeM('confirmModal'); openM('loadingModal');
  const f0=fileInput.files[0];
  const blob=await compressImage(f0);
  const fname=((f0.name||'anh').replace(/\.[^.]+$/,'')||'anh')+'.jpg';
  const fd=new FormData(); fd.append('image',blob,fname); fd.append('device_id',DEVICE); fd.append('enhance','1');
  try{
    const r=await fetch(URLS.gen,{method:'POST',headers:{'X-CSRF-TOKEN':CSRF},body:fd}); const d=await r.json();
    if(!d.ok){ closeM('loadingModal'); if(d.reason==='no_quota') outOfQuota(); else alert(d.msg||'Có lỗi, thử lại sau.'); return; }
    remaining=d.remaining??remaining; document.getElementById('remainBadge').textContent=badgeText(d);
    pollJob(d.job);   // job chạy nền bên hệ thống màu -> hỏi trạng thái mỗi 3s
  }catch(e){ closeM('loadingModal'); alert('Lỗi kết nối, thử lại sau.'); }
});

// Poll trạng thái job (an toàn với mọi timeout proxy — mỗi request chỉ ~1s)
function pollJob(job){
  var t0=Date.now(), MAX_MS=10*60*1000;   // job thuc te co the toi ~5 phut
  var note=document.querySelector('#loadingModal p');
  var timer=setInterval(async function(){
    var giay=Math.round((Date.now()-t0)/1000);
    var phut=Math.floor(giay/60);
    if(note) note.textContent='Đã chờ '+(phut>0?phut+' phút '+(giay%60)+' giây':giay+' giây')+'… AI thường mất 1–3 phút, đừng tắt trang.';
    if(Date.now()-t0>MAX_MS){ clearInterval(timer); closeM('loadingModal'); alert('Hệ thống đang bận, vui lòng thử lại sau ít phút.'); refreshQuota(); return; }
    try{
      var r=await fetch(URLS.status+'?job='+encodeURIComponent(job),{cache:'no-store'});
      var d=await r.json();
      if(d.status==='done'){ clearInterval(timer); closeM('loadingModal'); showResult(d.result); }
      else if(d.status==='error'){ clearInterval(timer); closeM('loadingModal');
        if(d.remaining!=null){ remaining=d.remaining; document.getElementById('remainBadge').textContent=remaining+' lượt'; }
        alert((d.msg||'Xử lý thất bại.')+' (Lượt của bạn đã được hoàn lại)'); }
      // processing -> chờ vòng sau
    }catch(e){ /* mạng chập chờn -> thử vòng sau */ }
  }, 3000);
}

function showResult(res, restored){
  const sec=document.getElementById('resultSection');
  // Khi khôi phục sau reload: dùng URL ảnh trên server (blob preview không còn)
  document.getElementById('rOriginal').src=(restored ? (res.original||res.enhanced) : (previewImg.src||res.original))||'';
  document.getElementById('rEnhanced').src=res.enhanced||res.original||'';
  lastResultUrl=res.img_output||'';            // bản đồ màu: chỉ gửi cho shop, không hiển thị
  lastEnhancedUrl=res.enhanced||'';
  document.getElementById('restoreNote').classList.toggle('hidden', !restored);
  sec.classList.remove('hidden');
  if(!restored){
    sec.scrollIntoView({behavior:'smooth'});
    // Lưu kết quả theo MÁY: tải lại trang vẫn còn (ảnh server giữ ~24h)
    try{ localStorage.setItem('dali_last_result', JSON.stringify({o:res.original||'',e:res.enhanced||'',m:res.img_output||'',at:Date.now()})); }catch(err){}
  }
}

// Khôi phục kết quả lần trước của máy này (trong vòng 24h)
(function(){
  try{
    var d=JSON.parse(localStorage.getItem('dali_last_result')||'null');
    if(!d||!d.e&&!d.m) return;
    if(Date.now()-(d.at||0) > 24*3600*1000){ localStorage.removeItem('dali_last_result'); return; }
    // Nếu ảnh đã bị dọn trên server -> ẩn khối + xoá bản lưu
    var probe=new Image();
    probe.onload=function(){ showResult({original:d.o,enhanced:d.e,img_output:d.m}, true); };
    probe.onerror=function(){ localStorage.removeItem('dali_last_result'); };
    probe.src=d.e||d.o;
  }catch(err){}
})();

// ───── Chọn kích thước & số màu (ma trận giá từ admin) ─────
const PRICING = @json($pricing);
var selI = Math.min(1, PRICING.sizes.length-1);
var selJ = (function(){ var k = PRICING.colors.indexOf(36); return k >= 0 ? k : 0; })();
function fmtVnd(n){ return n.toLocaleString('vi-VN')+'đ'; }
function curPrice(){ return PRICING.sizes[selI].prices[selJ] || 0; }
function paintChips(){
  document.querySelectorAll('#sizeChips .pick').forEach(b=>{
    var on = +b.dataset.i === selI;
    b.className='pick border-2 rounded-xl px-4 py-2.5 text-sm font-bold '+(on?'border-primary bg-green-50 text-primaryd':'border-green-200 bg-white');
  });
  document.querySelectorAll('.sizePrice').forEach(sp=>{
    sp.textContent = fmtVnd(PRICING.sizes[+sp.dataset.i].prices[selJ] || 0);
  });
  document.querySelectorAll('#colorChips .pick').forEach(b=>{
    var on = +b.dataset.j === selJ;
    b.className='pick border-2 rounded-xl px-4 py-2.5 text-sm font-bold '+(on?'border-primary bg-green-50 text-primaryd':'border-green-200 bg-white');
  });
  document.getElementById('resultOrderLabel').textContent='Đặt tranh này — '+fmtVnd(curPrice());
}
document.querySelectorAll('#sizeChips .pick').forEach(b=>b.addEventListener('click',()=>{ selI=+b.dataset.i; paintChips(); }));
document.querySelectorAll('#colorChips .pick').forEach(b=>b.addEventListener('click',()=>{ selJ=+b.dataset.j; paintChips(); }));
paintChips();
document.getElementById('resultOrderBtn').addEventListener('click',()=>openOrder(PRICING.sizes[selI].label+' — '+fmtVnd(curPrice())+' · '+PRICING.colors[selJ]+' màu'));

// ───── Lightbox zoom (lăn chuột / chụm 2 ngón / kéo) ─────
var zScale=1,zX=0,zY=0,zPointers=new Map(),zLastDist=0;
function zApply(){ var im=document.getElementById('zoomImg'); im.style.transform='translate('+zX+'px,'+zY+'px) scale('+zScale+')'; }
function openZoom(src){
  var m=document.getElementById('zoomModal'), im=document.getElementById('zoomImg');
  im.src=src; m.classList.remove('hidden');
  im.onload=function(){
    var s=Math.min(window.innerWidth/im.naturalWidth, window.innerHeight/im.naturalHeight)*0.95;
    zScale=s; zX=(window.innerWidth-im.naturalWidth*s)/2; zY=(window.innerHeight-im.naturalHeight*s)/2; zApply();
  };
  if(im.complete&&im.naturalWidth) im.onload();
}
function closeZoom(){ document.getElementById('zoomModal').classList.add('hidden'); zPointers.clear(); }
function zoomBy(f){ zoomAt(window.innerWidth/2, window.innerHeight/2, f); }
function zoomAt(cx,cy,f){ var ns=Math.min(Math.max(zScale*f,0.05),12); zX=cx-(cx-zX)*(ns/zScale); zY=cy-(cy-zY)*(ns/zScale); zScale=ns; zApply(); }
(function(){
  var m=document.getElementById('zoomModal');
  m.addEventListener('wheel',function(e){ e.preventDefault(); zoomAt(e.clientX,e.clientY, e.deltaY<0?1.15:1/1.15); },{passive:false});
  m.addEventListener('pointerdown',function(e){ zPointers.set(e.pointerId,{x:e.clientX,y:e.clientY}); m.setPointerCapture(e.pointerId); });
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
  ['pointerup','pointercancel'].forEach(ev=>m.addEventListener(ev,function(e){ zPointers.delete(e.pointerId); if(zPointers.size<2) zLastDist=0; }));
  m.addEventListener('dblclick',function(e){ zoomAt(e.clientX,e.clientY,2); });
  document.addEventListener('keydown',function(e){ if(e.key==='Escape') closeZoom(); });
})();

function outOfQuota(){ document.getElementById('orderTitle').textContent='Bạn đã hết lượt miễn phí';
  document.getElementById('orderDesc').innerHTML='Đặt hàng để shop làm tranh cho bạn — và nhận thêm <b>+{{ \App\Models\DesignQuota::ORDER_BONUS }} lượt</b> tạo.'; openOrder(''); }

function openOrder(pkg){ selectedPackage=pkg||''; const row=document.getElementById('oPkgRow');
  if(selectedPackage){ row.classList.remove('hidden'); document.getElementById('oPkgLabel').textContent=selectedPackage; } else { row.classList.add('hidden'); }
  openM('orderModal'); }
document.querySelectorAll('.orderOpen').forEach(b=>b.addEventListener('click',()=>openOrder(b.dataset.package||'')));

document.getElementById('orderSubmit').addEventListener('click', async ()=>{
  const name=document.getElementById('oName').value.trim(), phone=document.getElementById('oPhone').value.trim();
  if(!name||!phone){ alert('Vui lòng nhập họ tên và số điện thoại.'); return; }
  const fd=new FormData(); fd.append('device_id',DEVICE); fd.append('customer_name',name); fd.append('customer_phone',phone);
  fd.append('customer_address',document.getElementById('oAddr').value.trim()); fd.append('result_url',lastResultUrl); fd.append('enhanced_url',lastEnhancedUrl); fd.append('package',selectedPackage);
  try{ const r=await fetch(URLS.order,{method:'POST',headers:{'X-CSRF-TOKEN':CSRF},body:fd}); const d=await r.json();
    if(!d.ok){ alert('Gửi đơn thất bại, thử lại.'); return; } closeM('orderModal');
    remaining=d.remaining??remaining; document.getElementById('remainBadge').textContent=remaining+' lượt';
    alert('✅ Đã nhận đơn '+d.code+'! Shop sẽ liên hệ sớm. Bạn được +'+d.bonus+' lượt tạo.');
  }catch(e){ alert('Lỗi kết nối, thử lại sau.'); }
});
</script>
</body>
</html>
