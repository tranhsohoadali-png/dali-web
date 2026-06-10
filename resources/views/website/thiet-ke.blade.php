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
    {{-- Mockup: Ảnh gốc -> Tranh số hóa -> Hoàn thiện --}}
    <div class="reveal relative">
      <div class="grid grid-cols-3 gap-3 items-center">
        <figure class="rounded-2xl overflow-hidden shadow-xl2 bg-white border border-green-100 rotate-[-4deg]">
          <img src="https://picsum.photos/seed/dali-photo/400/500" class="w-full h-40 object-cover" alt="Ảnh gốc">
          <figcaption class="text-[11px] font-bold text-gray-500 text-center py-1.5">📷 Ảnh gốc</figcaption>
        </figure>
        <figure class="rounded-2xl overflow-hidden shadow-xl2 bg-white border border-green-100 z-10 scale-110">
          <img src="https://picsum.photos/seed/dali-map/400/500?grayscale" class="w-full h-44 object-cover" alt="Tranh số hóa">
          <figcaption class="text-[11px] font-bold text-primaryd text-center py-1.5">🎨 Bản tô số</figcaption>
        </figure>
        <figure class="rounded-2xl overflow-hidden shadow-xl2 bg-white border border-green-100 rotate-[4deg]">
          <img src="https://picsum.photos/seed/dali-art/400/500" class="w-full h-40 object-cover" alt="Hoàn thiện">
          <figcaption class="text-[11px] font-bold text-gray-500 text-center py-1.5">🖼️ Hoàn thiện</figcaption>
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

  {{-- KẾT QUẢ (before/after ảnh thật của khách) --}}
  <div id="resultSection" class="hidden mt-6 bg-white rounded-3xl shadow-xl2 border-2 border-primary/60 p-6 sm:p-8">
    <div class="font-black text-lg mb-4 flex items-center gap-2"><i class="ri-checkbox-circle-fill text-primary"></i> Tác phẩm của bạn đã sẵn sàng!</div>
    <div class="grid sm:grid-cols-[1fr_auto_1fr] gap-4 items-center">
      <figure class="text-center"><img id="rOriginal" class="w-full aspect-square object-contain rounded-2xl border border-green-100 bg-green-50" alt=""><figcaption class="text-xs font-bold text-gray-500 mt-2">📷 Ảnh gốc của bạn</figcaption></figure>
      <div class="text-primary text-3xl font-black text-center rotate-90 sm:rotate-0">→</div>
      <figure class="text-center"><img id="rEnhanced" class="w-full aspect-square object-contain rounded-2xl border border-green-100 bg-green-50" alt=""><figcaption class="text-xs font-bold text-primary mt-2">✨ Bản DALI tăng cường AI</figcaption></figure>
    </div>
    <div class="mt-4 text-center">
      <figure class="inline-block w-3/5"><img id="rOutput" class="w-full rounded-2xl border border-green-100" alt=""><figcaption class="text-xs font-bold text-gray-500 mt-2">🎨 Bản đồ màu tô số (kèm theo bộ tranh)</figcaption></figure>
    </div>
    <div id="rSwatches" class="flex flex-wrap gap-1.5 justify-center mt-4"></div>
    <div class="mt-5 bg-amber-50 border border-amber-200 rounded-2xl p-4 text-center text-sm font-semibold text-amber-700">
      🌟 <b>Đẹp đúng không?</b> Đây là bức tranh <b>độc nhất vô nhị</b> — không ai có tấm thứ hai!
    </div>
    <div class="mt-4 grad rounded-2xl p-5 text-center text-white">
      <div class="font-black text-lg">🎁 Sở hữu bức tranh này ngay hôm nay</div>
      <div class="text-white/85 text-sm mt-1 mb-4">Đặt hàng còn được <b>+{{ \App\Models\DesignQuota::ORDER_BONUS }} lượt tạo</b> để thử thêm ảnh khác.</div>
      <div class="flex flex-wrap gap-3 justify-center">
        <button class="orderOpen bg-white text-primaryd font-extrabold px-7 py-3.5 rounded-xl hover:scale-105 transition flex items-center gap-2"><i class="ri-shopping-bag-3-fill"></i> Đặt hàng ngay</button>
        <a id="dlOutput" href="#" download target="_blank" class="bg-white/15 border border-white/40 text-white font-bold px-6 py-3.5 rounded-xl hover:bg-white/25 transition flex items-center gap-2"><i class="ri-download-line"></i> Tải về xem</a>
      </div>
    </div>
  </div>
</section>

{{-- ════════ SECTION 3 — BEFORE / AFTER ════════ --}}
<section id="mau-tranh" class="max-w-6xl mx-auto px-4 py-12 reveal">
  <h2 class="text-2xl sm:text-3xl font-black text-center">Khách hàng đã biến ảnh thành <span class="text-primary">tác phẩm nghệ thuật</span> thế nào?</h2>
  <p class="text-center text-gray-500 mt-2 mb-8 text-sm">Từ một tấm ảnh đời thường → một bức tranh treo tường</p>
  <div class="grid md:grid-cols-3 gap-5">
    @foreach(['co-gai'=>'Chân dung','gia-dinh'=>'Gia đình','thu-cung'=>'Thú cưng'] as $seed=>$label)
    <div class="bg-white rounded-3xl border border-green-100 shadow-lg overflow-hidden hover:-translate-y-1.5 hover:shadow-xl2 transition">
      <div class="grid grid-cols-3">
        <img src="https://picsum.photos/seed/{{ $seed }}o/300/300" class="w-full h-28 object-cover" alt="">
        <img src="https://picsum.photos/seed/{{ $seed }}m/300/300?grayscale" class="w-full h-28 object-cover" alt="">
        <img src="https://picsum.photos/seed/{{ $seed }}f/300/300" class="w-full h-28 object-cover" alt="">
      </div>
      <div class="flex text-[10px] font-bold text-gray-400 text-center"><span class="flex-1 py-1">Ảnh gốc</span><span class="flex-1 py-1 text-primaryd">Bản tô số</span><span class="flex-1 py-1">Thành phẩm</span></div>
      <div class="px-5 py-4 font-extrabold text-center border-t border-green-50">{{ $label }}</div>
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
    @foreach([['Minh Tuấn','Hà Nội','rv1'],['Lan Phương','TP.HCM','rv2'],['Hoàng Anh','Đà Nẵng','rv3']] as $v)
    <div class="snap-start shrink-0 w-72 bg-white rounded-3xl border border-green-100 shadow-lg overflow-hidden">
      <div class="relative">
        <img src="https://picsum.photos/seed/{{ $v[2] }}/400/240" class="w-full h-40 object-cover" alt="">
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
    <img src="https://picsum.photos/seed/dali-box/700/520" class="rounded-3xl shadow-xl2 border border-green-100 w-full" alt="Bộ tranh tô màu DALI">
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

{{-- ════════ SECTION 9 — BẢNG GIÁ ════════ --}}
<section id="bang-gia" class="max-w-5xl mx-auto px-4 py-12 reveal">
  <h2 class="text-2xl sm:text-3xl font-black text-center mb-2">Chọn gói phù hợp với bạn</h2>
  <p class="text-center text-gray-500 mb-8 text-sm">Giá đã gồm thiết kế từ ảnh + bộ màu &amp; cọ + giao hàng</p>
  <div class="grid md:grid-cols-3 gap-5 items-stretch">
    @foreach([
      ['Starter','199.000đ','30×40 cm',['Thiết kế từ ảnh','Canvas + khung','Bộ màu cơ bản','Giao toàn quốc'],false],
      ['Popular','299.000đ','40×50 cm',['Tất cả gói Starter','Canvas cao cấp hơn','Bộ màu đầy đủ + cọ','Ảnh hướng dẫn chi tiết','Ưu tiên thiết kế'],true],
      ['Premium','499.000đ','50×70 cm',['Tất cả gói Popular','Khổ lớn, sắc nét','Khung gỗ cao cấp','Hộp quà sang trọng','Hỗ trợ tô 1-1'],false],
    ] as $p)
    <div class="relative bg-white rounded-3xl border-2 {{ $p[4] ? 'border-primary shadow-xl2 md:-translate-y-3' : 'border-green-100 shadow-sm' }} p-6 flex flex-col">
      @if($p[4])<div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-accent text-white text-xs font-black px-4 py-1.5 rounded-full shadow">🔥 BÁN CHẠY NHẤT</div>@endif
      <div class="font-black text-xl {{ $p[4] ? 'text-primaryd' : '' }}">{{ $p[0] }}</div>
      <div class="text-xs text-gray-400 mb-3">{{ $p[2] }}</div>
      <div class="text-3xl font-black mb-4">{{ $p[1] }}</div>
      <ul class="space-y-2 text-sm text-gray-600 flex-1 mb-5">
        @foreach($p[3] as $feat)<li class="flex items-start gap-2"><i class="ri-check-line text-primary mt-0.5"></i> {{ $feat }}</li>@endforeach
      </ul>
      <button class="orderOpen {{ $p[4] ? 'grad-btn text-white' : 'bg-green-50 text-primaryd hover:bg-green-100' }} font-extrabold py-3.5 rounded-xl transition" data-package="{{ $p[0] }} ({{ $p[2] }}) - {{ $p[1] }}">Đặt mẫu này</button>
    </div>
    @endforeach
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

<script>
const CSRF = document.querySelector('meta[name=csrf-token]').content;
const URLS = { quota:"{{ route('thiet-ke.quota') }}", gen:"{{ route('thiet-ke.generate') }}", order:"{{ route('thiet-ke.order') }}" };

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
const DEVICE=deviceId(); let remaining=0, lastResultUrl='', selectedPackage='';

const fileInput=document.getElementById('fileInput'), dropZone=document.getElementById('dropZone'),
      previewImg=document.getElementById('previewImg'), genBtn=document.getElementById('genBtn');

async function refreshQuota(){ try{ const r=await fetch(URLS.quota+'?device_id='+encodeURIComponent(DEVICE)); const d=await r.json(); remaining=d.remaining??0; document.getElementById('remainBadge').textContent=remaining+' lượt'; }catch(e){ document.getElementById('remainBadge').textContent='—'; } }
refreshQuota();

dropZone.addEventListener('click',()=>fileInput.click());
dropZone.addEventListener('dragover',e=>{e.preventDefault();dropZone.classList.add('border-primary');});
dropZone.addEventListener('dragleave',()=>dropZone.classList.remove('border-primary'));
dropZone.addEventListener('drop',e=>{e.preventDefault();dropZone.classList.remove('border-primary'); if(e.dataTransfer.files[0]){ fileInput.files=e.dataTransfer.files; onFile(); }});
fileInput.addEventListener('change',onFile);
function onFile(){ const f=fileInput.files[0]; if(!f) return; previewImg.src=URL.createObjectURL(f); previewImg.classList.remove('hidden'); genBtn.disabled=false; }

genBtn.addEventListener('click',()=>{ if(!fileInput.files[0]){alert('Vui lòng chọn ảnh.');return;} if(remaining<=0){ outOfQuota(); return;} document.getElementById('confirmRemain').textContent=remaining; openM('confirmModal'); });

document.getElementById('confirmGo').addEventListener('click', async ()=>{
  closeM('confirmModal'); openM('loadingModal');
  const fd=new FormData(); fd.append('image',fileInput.files[0]); fd.append('device_id',DEVICE); fd.append('enhance','1');
  try{ const r=await fetch(URLS.gen,{method:'POST',headers:{'X-CSRF-TOKEN':CSRF},body:fd}); const d=await r.json(); closeM('loadingModal');
    if(!d.ok){ if(d.reason==='no_quota') outOfQuota(); else alert(d.msg||'Có lỗi, thử lại sau.'); return; }
    remaining=d.remaining??remaining; document.getElementById('remainBadge').textContent=remaining+' lượt'; showResult(d.result);
  }catch(e){ closeM('loadingModal'); alert('Lỗi kết nối, thử lại sau.'); }
});

function showResult(res){
  const sec=document.getElementById('resultSection');
  document.getElementById('rOriginal').src=previewImg.src||res.original||'';
  document.getElementById('rEnhanced').src=res.enhanced||res.original||'';
  document.getElementById('rOutput').src=res.img_output||'';
  lastResultUrl=res.img_output||''; document.getElementById('dlOutput').href=res.img_output||'#';
  const sw=document.getElementById('rSwatches'); sw.innerHTML=''; let flat=[]; (res.colors||[]).forEach(p=>Array.isArray(p)&&p.forEach(c=>flat.push(c)));
  flat.slice(0,30).forEach(c=>{ const d=document.createElement('div'); d.className='w-7 h-7 rounded-md border border-green-100'; d.style.background=(c[1]||'#fff'); d.title=(c[2]||''); sw.appendChild(d); });
  sec.classList.remove('hidden'); sec.scrollIntoView({behavior:'smooth'});
}

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
  fd.append('customer_address',document.getElementById('oAddr').value.trim()); fd.append('result_url',lastResultUrl); fd.append('package',selectedPackage);
  try{ const r=await fetch(URLS.order,{method:'POST',headers:{'X-CSRF-TOKEN':CSRF},body:fd}); const d=await r.json();
    if(!d.ok){ alert('Gửi đơn thất bại, thử lại.'); return; } closeM('orderModal');
    remaining=d.remaining??remaining; document.getElementById('remainBadge').textContent=remaining+' lượt';
    alert('✅ Đã nhận đơn '+d.code+'! Shop sẽ liên hệ sớm. Bạn được +'+d.bonus+' lượt tạo.');
  }catch(e){ alert('Lỗi kết nối, thử lại sau.'); }
});
</script>
</body>
</html>
