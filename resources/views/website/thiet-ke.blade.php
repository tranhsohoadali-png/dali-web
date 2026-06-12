<!DOCTYPE html>
<html lang="vi" class="scroll-smooth">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
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
    <a href="{{ route('home') }}" class="flex items-center"><img src="{{ asset('images/logo_dali.png') }}" alt="DALI" class="h-9 w-auto"></a>
    <nav class="hidden md:flex items-center gap-7 text-sm font-semibold text-gray-600">
      <a href="#mau-tranh" class="hover:text-primary">Mẫu tranh</a>
      <a href="#danh-gia" class="hover:text-primary">Đánh giá</a>
      <a href="#bang-gia" class="hover:text-primary">Bảng giá</a>
      <a href="#faq" class="hover:text-primary">FAQ</a>
    </nav>
    <a href="#upload" class="hidden md:inline-flex grad-btn text-white text-sm font-extrabold px-5 py-2.5 rounded-full">Tải ảnh ngay</a>
  </div>
</header>

{{-- ════════ SECTION 1 — HERO (thiết kế Stitch V2) ════════ --}}
<section class="relative overflow-hidden bg-[#3E2F23]">
  {{-- Mobile: ảnh phủ nền, tiêu điểm vào khuôn mặt (không cắt mất đầu) --}}
  <img src="{{ asset('images/thiet-ke/hero-studio.jpg') }}" alt="Studio vẽ tranh DALI" class="md:hidden absolute inset-0 w-full h-full object-cover object-[62%_18%]">
  {{-- Desktop: hiển thị TRỌN VẸN ảnh (đúng tỷ lệ, neo phải), mép trái hoà vào nền nâu --}}
  <div class="hidden md:block absolute inset-y-0 right-0">
    <img src="{{ asset('images/thiet-ke/hero-studio.jpg') }}" alt="Studio vẽ tranh DALI" class="h-full w-auto max-w-none">
    <div class="absolute inset-y-0 left-0 w-48 bg-gradient-to-r from-[#3E2F23] to-transparent"></div>
  </div>
  <div class="absolute inset-0 bg-gradient-to-r from-[#3E2F23] via-[#3E2F23]/85 to-[#3E2F23]/55 md:via-[#3E2F23]/60 md:to-transparent"></div>
  <div class="relative max-w-6xl mx-auto px-4 pt-14 pb-24 md:pt-20 md:pb-28">
    <div class="max-w-xl reveal">
      <h1 class="text-3xl sm:text-4xl lg:text-[2.7rem] font-black leading-tight text-white">
        Biến khoảnh khắc của bạn thành kiệt tác!
        <span class="block mt-1 text-[#B7F0A8]">Trải nghiệm niềm vui vẽ tranh số hóa.</span>
      </h1>
      <p class="mt-4 text-white/85 text-[15px] max-w-md">Gửi 1 tấm ảnh kỷ niệm — nhận bộ kit <b class="text-white">canvas in số + màu pha sẵn + cọ</b>, tự tay tô thành tranh treo tường.</p>
      <div class="mt-7 flex flex-wrap gap-3">
        <a href="#upload" class="grad-btn w-full sm:w-auto justify-center text-white text-base font-extrabold px-7 py-4 rounded-xl shadow-xl2 flex items-center gap-2"><i class="ri-upload-cloud-2-line"></i> Tải ảnh lên &amp; Xem trước miễn phí</a>
        <a href="#bang-gia" class="w-full sm:w-auto justify-center bg-white/10 backdrop-blur border-2 border-white/60 text-white text-base font-bold px-6 py-4 rounded-xl hover:bg-white/20 transition flex items-center gap-2">Xem bảng giá</a>
      </div>
    </div>
  </div>
</section>
{{-- ════════ SECTION 2 — TRIAL: UPLOAD TOOL (chức năng thật) ════════ --}}
<section id="upload" class="max-w-6xl mx-auto px-4 -mt-12 relative z-10 pb-12">
  <div class="grid lg:grid-cols-[1fr_310px] gap-5 items-start">
    <div class="bg-white rounded-3xl shadow-xl2 border-2 border-primary/40 p-6 sm:p-8 reveal">
      <h2 class="text-xl sm:text-2xl font-black"><span class="text-primary">Thử miễn phí:</span> Xem bản thiết kế từ ảnh của bạn trong ~1 phút!</h2>
      <div class="mt-2 mb-4 inline-flex flex-wrap max-w-full items-center gap-2 bg-green-50 border border-green-200 rounded-full px-4 py-1.5 text-sm font-bold text-primaryd">
        <i class="ri-flashlight-fill text-accent"></i> Lượt tạo còn lại: <span id="remainBadge">…</span>
        <span class="text-gray-400 font-semibold hidden md:inline">· {{ \App\Models\DesignQuota::FREE }} lượt miễn phí/máy</span>
      </div>
      <input type="file" id="fileInput" accept="image/png,image/jpeg,image/webp" class="hidden">
      <div class="grid sm:grid-cols-[1fr_auto] gap-4 items-stretch">
        <div id="dropZone" class="border-2 border-dashed border-green-300 rounded-2xl bg-green-50/60 px-6 py-10 text-center cursor-pointer hover:border-primary hover:bg-green-50 transition">
          <div class="w-14 h-14 mx-auto mb-3 rounded-full grad flex items-center justify-center text-white text-2xl"><i class="ri-image-add-line"></i></div>
          <div class="font-extrabold text-lg">Kéo thả hoặc chạm để chọn ảnh</div>
          <div class="text-xs text-gray-400 mt-1">PNG · JPG · WEBP — ảnh càng rõ kết quả càng đẹp</div>
          <span class="inline-block mt-4 grad-btn text-white text-sm font-extrabold px-6 py-3 rounded-xl"><i class="ri-folder-image-line"></i> Chọn ảnh</span>
        </div>
        {{-- Demo trước→sau (như mockup Stitch): ảnh thật của khách --}}
        <div class="flex items-center justify-center gap-2 self-center">
          <img src="{{ asset('images/thiet-ke/be-goc.jpg') }}" onclick="openZoom(this.src)" class="w-24 h-32 sm:w-28 sm:h-36 object-cover rounded-xl border border-green-100 cursor-zoom-in" alt="Ảnh gốc">
          <span class="text-primary text-2xl font-black">→</span>
          <img src="{{ asset('images/thiet-ke/be-art.jpg') }}" onclick="openZoom(this.src)" class="w-24 h-32 sm:w-28 sm:h-36 object-cover rounded-xl border-2 border-primary/40 cursor-zoom-in" alt="Bản tăng cường AI">
        </div>
      </div>
      <img id="previewImg" class="hidden mt-5 mx-auto max-h-72 rounded-2xl border border-green-100" alt="">
      <div class="mt-5 flex flex-wrap items-center gap-3">
        <button id="genBtn" disabled class="grad-btn w-full sm:w-auto justify-center disabled:opacity-40 disabled:cursor-not-allowed text-white text-base font-extrabold px-7 py-4 rounded-2xl flex items-center gap-2"><i class="ri-sparkling-2-line"></i> Tạo bản thiết kế</button>
        <span class="text-xs text-gray-400">⏱️ Bộ kit giao trong 24–72 giờ · <span class="font-extrabold text-primaryd">Trọn bộ chỉ từ {{ number_format(min(array_map(fn($s) => min($s['prices']), $pricing['sizes'])), 0, ',', '.') }}đ · COD toàn quốc</span></span>
      </div>
      <div class="mt-4 pt-4 border-t border-green-100 text-center text-sm text-gray-500">Ngại tự làm? <a href="https://zalo.me/0856911698" target="_blank" rel="noopener" class="font-extrabold text-primaryd underline">💬 Gửi ảnh qua Zalo</a> — shop thiết kế giúp miễn phí, hoặc <button type="button" class="orderOpen font-extrabold text-primaryd underline" data-package="Đặt trước — shop gọi tư vấn ảnh &amp; kích thước">để lại SĐT, shop gọi lại</button></div>
    </div>
    <div class="space-y-4 reveal">
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
{{-- KẾT QUẢ (before/after ảnh thật của khách — khổ rộng, bấm để zoom) --}}
<section class="max-w-5xl mx-auto px-4">
  <div id="resultSection" class="hidden scroll-mt-20 mt-2 bg-white rounded-3xl shadow-xl2 border-2 border-primary/60 p-6 sm:p-8">
    <div class="font-black text-xl mb-1 flex items-center gap-2"><i class="ri-checkbox-circle-fill text-primary"></i> Tác phẩm của bạn đã sẵn sàng!</div>
    <div id="restoreNote" class="hidden mb-2 bg-green-50 border border-green-200 rounded-xl px-4 py-2 text-xs font-bold text-primaryd">💾 Kết quả lần trước của bạn vẫn được giữ trên máy này — tải ảnh mới để tạo thêm bất cứ lúc nào!</div>
    <div class="text-xs text-gray-400 font-semibold mb-4">🔍 Bấm vào ảnh để phóng to, xem chi tiết từng nét</div>
    <div class="grid grid-cols-2 sm:grid-cols-[1fr_auto_1fr] gap-3 sm:gap-4 items-center">
      <figure class="text-center"><img id="rOriginal" onclick="openZoom(this.src)" class="w-full aspect-square object-contain rounded-2xl border border-green-100 bg-green-50 cursor-zoom-in" alt=""><figcaption class="text-xs font-bold text-gray-500 mt-2">📷 Ảnh gốc của bạn</figcaption></figure>
      <div class="text-primary text-3xl font-black text-center hidden sm:block">→</div>
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
        <button type="button" data-i="{{ $i }}" class="pick border-2 rounded-xl px-4 py-3 text-sm font-bold bg-white">{{ $s['label'] }}<span class="block text-xs font-extrabold text-primaryd sizePrice" data-i="{{ $i }}"></span></button>
        @endforeach
      </div>
      <div class="font-bold text-sm mb-2 mt-4">🎨 Số màu <span class="text-xs text-gray-400 font-semibold">(nhiều màu hơn = chi tiết &amp; giống ảnh hơn)</span></div>
      <div class="flex flex-wrap gap-2" id="colorChips">
        @foreach($pricing['colors'] as $j => $c)
        <button type="button" data-j="{{ $j }}" class="pick border-2 rounded-xl px-4 py-3 text-sm font-bold bg-white">{{ $c }} màu</button>
        @endforeach
      </div>
      <div class="mt-5 flex flex-wrap items-center gap-3">
        <button id="resultOrderBtn" class="grad-btn w-full sm:w-auto justify-center text-white font-extrabold text-base px-5 sm:px-8 py-4 rounded-2xl flex items-center gap-2"><i class="ri-shopping-bag-3-fill"></i> <span id="resultOrderLabel">Đặt tranh này</span></button>
        <span class="text-xs text-gray-500">Đặt xong được <b>+{{ \App\Models\DesignQuota::ORDER_BONUS }} lượt tạo</b> để thử thêm ảnh khác.</span>
      </div>
      <div class="mt-3 text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2 inline-block">⏳ Bản thiết kế chỉ được lưu <b>24 giờ</b> trên hệ thống — đặt ngay để giữ bức tranh độc bản của bạn.</div>
    </div>
  </div>
</section>

{{-- ════════ SECTION 3 — WHAT'S IN THE BOX ════════ --}}
<section id="mo-hop" class="max-w-5xl mx-auto px-4 py-14 reveal">
  <h2 class="text-2xl sm:text-3xl font-black text-center mb-10">Bộ kit DALI — trong hộp có gì?</h2>
  <div class="grid md:grid-cols-[230px_1fr_230px] gap-5 items-center">
    <div class="space-y-3 order-2 md:order-1">
      @foreach([['ri-image-2-line','Canvas in số','Tranh của bạn in sẵn ô số trên canvas cao cấp'],['ri-palette-line','Bộ màu acrylic','Pha sẵn đúng từng mã màu của bản thiết kế']] as $i)
      <div class="bg-white rounded-xl border border-green-100 shadow-sm p-4 flex gap-3 items-start"><div class="w-9 h-9 rounded-lg bg-green-50 text-primary flex items-center justify-center shrink-0"><i class="{{ $i[0] }}"></i></div><div><div class="font-extrabold text-sm">{{ $i[1] }}</div><div class="text-[11px] text-gray-500">{{ $i[2] }}</div></div></div>
      @endforeach
    </div>
    <figure class="relative order-1 md:order-2">
      <img src="{{ asset('images/thiet-ke/mo-hop.jpg') }}" loading="lazy" class="rounded-3xl shadow-xl2 w-full aspect-square object-cover cursor-zoom-in" onclick="openZoom(this.src)" alt="Bộ kit tranh tô màu số DALI">
      <figcaption class="absolute bottom-3 left-1/2 -translate-x-1/2 glass rounded-full px-4 py-1.5 text-xs font-extrabold text-primaryd shadow whitespace-nowrap">🖼️ Thành phẩm thật từ khách DALI</figcaption>
    </figure>
    <div class="space-y-3 order-3">
      @foreach([['ri-brush-line','Cọ vẽ 3 cỡ','Tô nền lớn và chi tiết nhỏ đều dễ dàng'],['ri-file-list-3-line','Bảng mã màu','Hướng dẫn rõ ràng, nhìn là làm theo được'],['ri-links-line','Móc treo tặng kèm','Tô xong treo tường ngay không cần mua thêm']] as $i)
      <div class="bg-white rounded-xl border border-green-100 shadow-sm p-4 flex gap-3 items-start"><div class="w-9 h-9 rounded-lg bg-green-50 text-primary flex items-center justify-center shrink-0"><i class="{{ $i[0] }}"></i></div><div><div class="font-extrabold text-sm">{{ $i[1] }}</div><div class="text-[11px] text-gray-500">{{ $i[2] }}</div></div></div>
      @endforeach
    </div>
  </div>
</section>
{{-- ════════ SECTION 4 — KHÁCH KHOE TRANH + BẢNG GIÁ ════════ --}}
<section id="mau-tranh" class="max-w-6xl mx-auto px-4 py-12 reveal">
  <h2 class="text-2xl sm:text-3xl font-black text-center mb-2">Khách DALI khoe thành phẩm</h2>
  <p class="text-center text-gray-500 mb-8 text-sm">Tranh thật từ ảnh thật — và bảng giá rõ ràng ngay bên cạnh</p>
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
        <img src="{{ asset('images/thiet-ke/'.$g[0].'.jpg') }}" loading="lazy" class="w-full h-36 object-cover cursor-zoom-in" onclick="openZoom(this.src)" alt="">
        <div class="px-3 py-2"><div class="text-xs font-extrabold text-primaryd"><i class="ri-instagram-line"></i> {{ '@'.$g[1] }}</div><div class="text-[11px] text-gray-500 leading-snug">“{{ $g[2] }}”</div></div>
      </div>
      @endforeach
    </div>
    <div id="bang-gia" class="bg-white rounded-3xl border border-green-100 shadow-xl2 overflow-hidden">
      <div class="px-5 pt-5 pb-3"><div class="font-black text-lg">💰 Bảng giá bộ kit</div><div class="text-[11px] text-gray-400">Đã gồm thiết kế từ ảnh + canvas + bộ màu &amp; cọ + giao toàn quốc</div>
        <div class="text-[11px] font-bold text-primaryd mt-1">🎁 Trọn gói: canvas in số + màu pha sẵn + cọ + móc treo + freeship — không phát sinh thêm chi phí.</div></div>
      <div class="sm:hidden px-5 pb-1 text-[10px] font-bold text-gray-400">↔ Vuốt ngang để xem đủ mức giá</div>
      <div class="relative">
      <div class="overflow-x-auto">
        <table class="w-full" style="border-collapse:collapse;min-width:430px">
          <thead><tr class="border-y border-gray-100 bg-bgsoft">
            <th class="text-left px-3 py-2.5 font-black text-[11px] sticky left-0 z-10 bg-bgsoft">Kích thước</th>
            @foreach($pricing['colors'] as $c)<th class="text-right px-2 py-2.5 font-black text-[11px] whitespace-nowrap">{{ $c }} màu</th>@endforeach
          </tr></thead>
          <tbody>
            @foreach($pricing['sizes'] as $si => $s)
            @if($si === 1)<tr><td colspan="{{ count($pricing['colors']) + 1 }}" class="bg-primary text-white text-center text-[11px] font-black py-1.5">⭐ Lựa chọn phổ biến</td></tr>@endif
            <tr class="border-b border-gray-50 {{ $si === 1 ? 'bg-primary' : 'hover:bg-green-50/40' }} transition">
              <td class="px-3 py-2.5 sticky left-0 z-10 {{ $si === 1 ? 'bg-primary' : 'bg-white' }}"><div class="font-black text-xs whitespace-nowrap {{ $si === 1 ? 'text-white' : '' }}">{{ $s['label'] }}</div>@if(!empty($s['note']))<div class="text-[9px] font-semibold {{ $si === 1 ? 'text-white/80' : 'text-gray-400' }}">{{ $s['note'] }}</div>@endif</td>
              @foreach($s['prices'] as $v)<td class="px-2 py-2.5 text-right text-[11px] whitespace-nowrap {{ $si === 1 ? 'text-white font-black' : 'text-ink font-bold' }}">{{ number_format($v, 0, ',', '.') }}đ</td>@endforeach
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="absolute inset-y-0 right-0 w-8 bg-gradient-to-l from-white pointer-events-none sm:hidden"></div>
      </div>
      <div class="p-4"><a href="#upload" class="grad-btn w-full text-white text-sm font-extrabold py-3.5 rounded-xl flex items-center justify-center gap-2"><i class="ri-upload-cloud-2-line"></i> Tải ảnh lên — xem trước miễn phí</a></div>
    </div>
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
      ['Tôi không rành thao tác, nhờ shop làm giúp được không?','Được! Bạn chỉ cần gửi ảnh qua Zalo 0856.911.698, shop thiết kế và gửi bản xem trước miễn phí — ưng mới đặt.'],
    ] as $q)
    <details class="bg-white rounded-2xl border border-green-100 px-5 shadow-sm">
      <summary class="flex items-center justify-between font-extrabold py-4 min-h-[44px]"><span>{{ $q[0] }}</span><i class="ri-add-line faq-ic text-primary text-xl transition-transform"></i></summary>
      <p class="pb-4 text-sm text-gray-600 leading-relaxed">{{ $q[1] }}</p>
    </details>
    @endforeach
  </div>
</section>

{{-- ════════ SECTION CTA CUỐI (Stitch V2) ════════ --}}
<section class="relative overflow-hidden bg-gradient-to-r from-[#1E7A33] to-[#37A24A] text-white py-14 px-4">
  <span class="absolute top-6 left-[8%] text-2xl rotate-12 opacity-70">🎉</span>
  <span class="absolute top-10 right-[12%] text-3xl -rotate-12 opacity-70">🎊</span>
  <span class="absolute bottom-8 left-[20%] text-xl opacity-60">✨</span>
  <span class="absolute bottom-12 right-[28%] text-2xl rotate-6 opacity-60">🎈</span>
  <div class="max-w-5xl mx-auto grid md:grid-cols-[280px_1fr] gap-8 items-center relative">
    <img src="{{ asset('images/thiet-ke/mo-hop.jpg') }}" loading="lazy" class="w-full max-w-[280px] mx-auto rounded-2xl border-4 border-white/80 shadow-xl2 rotate-[-4deg]" alt="Thành phẩm DALI">
    <div class="text-center md:text-left">
      <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black leading-tight">Sẵn sàng để sáng tạo?<br>Bắt đầu hành trình nghệ thuật của bạn ngay hôm nay!</h2>
      <a href="#upload" class="mt-6 inline-flex items-center gap-2 bg-white text-primaryd font-extrabold text-lg px-8 py-4 rounded-2xl hover:scale-105 transition shadow-xl2">📸 Tải ảnh — xem bản thiết kế miễn phí, ưng mới đặt</a>
    </div>
  </div>
</section>

<footer class="bg-ink text-white/50 text-center text-xs py-8 px-4">
  © 2026 DALI · Tranh Tô Màu Số Hóa Theo Ảnh · 🇻🇳 Giao toàn quốc
</footer>

{{-- ════════ FLOATING (mobile sticky CTA) ════════ --}}
<div id="stickyBar" class="md:hidden fixed bottom-0 inset-x-0 z-40 glass border-t border-green-200 grid grid-cols-2 gap-2 p-2 pb-[max(0.5rem,env(safe-area-inset-bottom))] translate-y-full transition-transform duration-300">
  <a id="stickyCta" href="#upload" class="grad-btn text-white font-extrabold py-3 rounded-xl text-center text-sm">📸 Tải ảnh</a>
  <a href="https://zalo.me/0856911698" target="_blank" rel="noopener" class="bg-white border border-green-200 text-primaryd font-extrabold py-3 rounded-xl text-center text-sm">💬 Chat Zalo</a>
</div>
<div class="md:hidden h-20"></div>
<a href="https://zalo.me/0856911698" target="_blank" rel="noopener" class="hidden md:flex fixed bottom-6 right-6 z-40 items-center gap-2 bg-[#0068FF] text-white font-extrabold px-5 py-3.5 rounded-full shadow-xl2 hover:scale-105 transition"><i class="ri-message-3-fill"></i> Chat Zalo</a>

{{-- Viên nổi tiến trình: hiện khi ẩn cửa sổ chờ / quay lại trang lúc đang xử lý --}}
<button id="jobPill" onclick="openM('loadingModal')" class="hidden fixed bottom-24 md:bottom-6 left-4 z-40 items-center gap-2 bg-white border-2 border-primary/50 shadow-xl2 rounded-full px-4 py-2.5 text-xs font-extrabold text-primaryd">
  <span class="w-4 h-4 border-2 border-green-100 border-t-primary rounded-full animate-spin inline-block"></span>
  <span id="jobPillText">Đang thiết kế…</span>
</button>
{{-- Thông báo khi xong --}}
<div id="doneToast" class="hidden fixed top-20 inset-x-0 z-[60] justify-center pointer-events-none px-4">
  <div class="bg-primaryd text-white font-extrabold text-sm px-6 py-3.5 rounded-full shadow-xl2">🔔 Bản thiết kế của bạn đã xong — xem ngay bên dưới!</div>
</div>

{{-- ════════ MODALS ════════ --}}
<div id="confirmModal" class="fixed inset-0 z-50 bg-black/50 hidden items-center justify-center p-4">
  <div class="bg-white rounded-3xl max-w-sm w-full p-7 text-center max-h-[85vh] overflow-y-auto">
    <div class="text-4xl mb-2">✨</div>
    <h3 class="text-xl font-black mb-2">Tạo bản thiết kế?</h3>
    <p class="text-sm text-gray-500 mb-5">Bạn còn <b id="confirmRemain">0</b> lượt. Mỗi lần tạo dùng <b>1 lượt</b> và mất khoảng <b>1–3 phút</b>.</p>
    <div class="flex gap-3 justify-center">
      <button onclick="closeM('confirmModal')" class="bg-gray-100 text-gray-600 font-bold px-5 py-3 rounded-xl">Huỷ</button>
      <button id="confirmGo" class="grad-btn text-white font-extrabold px-6 py-3 rounded-xl">Tạo ngay</button>
    </div>
  </div>
</div>
<div id="loadingModal" class="fixed inset-0 z-50 bg-black/50 hidden items-center justify-center p-4">
  <div class="bg-white rounded-3xl max-w-sm w-full p-8 text-center max-h-[85vh] overflow-y-auto">
    <div class="w-12 h-12 mx-auto mb-4 border-4 border-green-100 border-t-primary rounded-full animate-spin"></div>
    <h3 class="text-lg font-black">Đang thiết kế bằng AI…</h3>
    <p class="text-sm text-gray-500 mt-1">Thường mất 1–3 phút — bạn có thể chờ, đừng tắt trang.</p>
    <button onclick="hideLoading()" class="mt-4 inline-flex items-center gap-1.5 text-xs font-extrabold text-primaryd bg-green-50 border border-green-200 rounded-full px-4 py-2.5">🔔 Ẩn cửa sổ — cứ xem trang, xong sẽ có chuông báo</button>
  </div>
</div>
<div id="orderModal" class="fixed inset-0 z-50 bg-black/50 hidden items-center justify-center p-4">
  <div class="bg-white rounded-3xl max-w-sm w-full p-7 max-h-[85vh] overflow-y-auto">
    <div class="text-center"><div class="text-4xl mb-2">🛍️</div><h3 id="orderTitle" class="text-xl font-black mb-1">Đặt hàng tranh thiết kế</h3>
    <p id="orderDesc" class="text-sm text-gray-500 mb-4">Điền thông tin, shop sẽ liên hệ &amp; làm tranh cho bạn. Đặt xong được <b>+{{ \App\Models\DesignQuota::ORDER_BONUS }} lượt</b>.</p></div>
    <form id="orderForm" class="space-y-3 text-left" novalidate>
      <input id="oName" name="name" type="text" autocomplete="name" autocapitalize="words" enterkeyhint="next" class="w-full border border-green-200 rounded-xl px-4 py-3 bg-green-50 focus:bg-white focus:border-primary outline-none text-base" placeholder="Họ tên *">
      <input id="oPhone" name="phone" type="tel" inputmode="tel" autocomplete="tel" maxlength="13" enterkeyhint="next" class="w-full border border-green-200 rounded-xl px-4 py-3 bg-green-50 focus:bg-white focus:border-primary outline-none text-base" placeholder="Số điện thoại *">
      <div id="oPhoneErr" class="hidden text-xs text-red-500 font-bold px-1">Số điện thoại chưa đúng (VD: 0912 345 678)</div>
      <input id="oAddr" name="address" type="text" autocomplete="street-address" enterkeyhint="done" class="w-full border border-green-200 rounded-xl px-4 py-3 bg-green-50 focus:bg-white focus:border-primary outline-none text-base" placeholder="Địa chỉ nhận hàng (có thể bổ sung khi shop gọi)">
      <div id="oPkgRow" class="hidden text-xs font-bold text-primaryd bg-green-50 rounded-lg px-3 py-2">Gói: <span id="oPkgLabel"></span></div>
    </form>
    <div class="mt-4 grid grid-cols-3 gap-1 text-center text-[10px] font-bold text-gray-500"><div>💵 Thanh toán<br>khi nhận hàng</div><div>🚚 Freeship<br>toàn quốc</div><div>🔄 Đổi trả nếu<br>in lỗi/hư hỏng</div></div>
    <div class="flex gap-3 justify-center mt-5">
      <button onclick="closeM('orderModal')" class="bg-gray-100 text-gray-600 font-bold px-5 py-3 rounded-xl">Để sau</button>
      <button id="orderSubmit" class="grad-btn disabled:opacity-50 disabled:cursor-not-allowed text-white font-extrabold px-6 py-3 rounded-xl">Gửi đơn</button>
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
function openM(id){ const m=document.getElementById(id); m.classList.remove('hidden'); m.classList.add('flex'); document.body.style.overflow='hidden'; }
function closeM(id){ const m=document.getElementById(id); m.classList.add('hidden'); m.classList.remove('flex'); document.body.style.overflow=''; }

// ───── Công cụ thiết kế (device id + quota + tạo + đặt hàng) ─────
function deviceId(){ let d=localStorage.getItem('dali_device'); if(!d){ d='d'+Date.now().toString(36)+Math.random().toString(36).slice(2,12); localStorage.setItem('dali_device',d);} return d; }
const DEVICE=deviceId(); let remaining=null, lastResultUrl='', lastEnhancedUrl='', selectedPackage='', orderDone=false;

const fileInput=document.getElementById('fileInput'), dropZone=document.getElementById('dropZone'),
      previewImg=document.getElementById('previewImg'), genBtn=document.getElementById('genBtn');

function badgeText(d){ return d&&d.unlimited ? '∞ (máy test)' : (remaining==null ? '—' : remaining+' lượt'); }
async function refreshQuota(){ try{ const r=await fetch(URLS.quota+'?device_id='+encodeURIComponent(DEVICE)); const d=await r.json(); remaining=d.remaining??0; document.getElementById('remainBadge').textContent=badgeText(d); }catch(e){ document.getElementById('remainBadge').textContent='—'; } }
refreshQuota();

dropZone.addEventListener('click',()=>fileInput.click());
dropZone.addEventListener('dragover',e=>{e.preventDefault();dropZone.classList.add('border-primary');});
dropZone.addEventListener('dragleave',()=>dropZone.classList.remove('border-primary'));
dropZone.addEventListener('drop',e=>{e.preventDefault();dropZone.classList.remove('border-primary'); if(e.dataTransfer.files[0]){ fileInput.files=e.dataTransfer.files; onFile(); }});
fileInput.addEventListener('change',onFile);
function onFile(){ const f=fileInput.files[0]; if(!f) return; previewImg.src=URL.createObjectURL(f); previewImg.classList.remove('hidden'); genBtn.disabled=false; }

genBtn.addEventListener('click', async ()=>{ if(!fileInput.files[0]){alert('Vui lòng chọn ảnh.');return;}
  if(remaining===null){ await refreshQuota(); }
  if(remaining===null){ alert('Không kiểm tra được lượt tạo — vui lòng kiểm tra mạng và thử lại.'); return; }
  if(remaining<=0){ outOfQuota(); return;} document.getElementById('confirmRemain').textContent=remaining; openM('confirmModal'); });

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

// ───── Chuông + thông báo khi xong (khách có thể thoát ra trong lúc chờ) ─────
let _ac=null;
function initAudio(){ try{ _ac=_ac||new (window.AudioContext||window.webkitAudioContext)(); if(_ac.state==='suspended') _ac.resume(); }catch(e){} }
function playChime(){ try{ if(!_ac) return; if(_ac.state==='suspended') _ac.resume(); const t=_ac.currentTime;
  [[880,0],[1175,0.18]].forEach(function(p){ const o=_ac.createOscillator(), g=_ac.createGain();
    o.type='sine'; o.frequency.value=p[0];
    g.gain.setValueAtTime(0.0001,t+p[1]); g.gain.exponentialRampToValueAtTime(0.25,t+p[1]+0.02); g.gain.exponentialRampToValueAtTime(0.0001,t+p[1]+0.6);
    o.connect(g); g.connect(_ac.destination); o.start(t+p[1]); o.stop(t+p[1]+0.65); });
}catch(e){} }
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

function showPill(){ const p=document.getElementById('jobPill'); p.classList.remove('hidden'); p.classList.add('flex'); }
function hidePill(){ const p=document.getElementById('jobPill'); p.classList.add('hidden'); p.classList.remove('flex'); }
function hideLoading(){ closeM('loadingModal'); showPill(); }
function clearPendingJob(){ try{ localStorage.removeItem('dali_pending_job'); }catch(e){} hidePill(); }
function notifyDone(){
  playChime();
  const t=document.getElementById('doneToast'); t.classList.remove('hidden'); t.classList.add('flex');
  setTimeout(function(){ t.classList.add('hidden'); t.classList.remove('flex'); }, 7000);
  if(document.hidden){ const old=document.title; document.title='🔔 Tranh của bạn đã xong! — DALI';
    document.addEventListener('visibilitychange', function h(){ if(!document.hidden){ document.title=old; document.removeEventListener('visibilitychange',h); } }); }
}

document.getElementById('confirmGo').addEventListener('click', async ()=>{
  initAudio();   // tạo audio ngay lúc khách bấm -> sau này được phép kêu chuông
  closeM('confirmModal'); openM('loadingModal');
  const f0=fileInput.files[0];
  const blob=await compressImage(f0);
  const fname=((f0.name||'anh').replace(/\.[^.]+$/,'')||'anh')+'.jpg';
  const fd=new FormData(); fd.append('image',blob,fname); fd.append('device_id',DEVICE); fd.append('enhance','1');
  try{
    const r=await fetch(URLS.gen,{method:'POST',headers:{'X-CSRF-TOKEN':CSRF},body:fd}); const d=await r.json();
    if(!d.ok){ closeM('loadingModal'); if(d.reason==='no_quota') outOfQuota(); else alert(d.msg||'Có lỗi, thử lại sau.'); return; }
    remaining=d.remaining??remaining; document.getElementById('remainBadge').textContent=badgeText(d);
    // Ghi nhớ job theo MÁY: khách thoát trang giữa chừng, quay lại vẫn tự nối tiếp
    try{ localStorage.setItem('dali_pending_job', JSON.stringify({job:d.job, at:Date.now()})); }catch(e){}
    pollJob(d.job);   // job chạy nền bên hệ thống màu -> hỏi trạng thái mỗi 3s
  }catch(e){ closeM('loadingModal'); alert('Lỗi kết nối, thử lại sau.'); }
});

// Poll trạng thái job (an toàn với mọi timeout proxy — mỗi request chỉ ~1s)
function pollJob(job, startedAt){
  var t0=startedAt||Date.now(), MAX_MS=10*60*1000;   // job thuc te co the toi ~5 phut
  var note=document.querySelector('#loadingModal p');
  var pillText=document.getElementById('jobPillText');
  var timer=setInterval(async function(){
    var giay=Math.round((Date.now()-t0)/1000);
    var phut=Math.floor(giay/60);
    var tgian=(phut>0?phut+' phút '+(giay%60)+' giây':giay+' giây');
    if(note) note.textContent='Đã chờ '+tgian+'… AI thường mất 1–3 phút.'+(giay>180?' Sắp xong rồi — ảnh phức tạp cần thêm chút thời gian.':'');
    if(pillText) pillText.textContent='Đang thiết kế… '+tgian;
    if(Date.now()-t0>MAX_MS){ clearInterval(timer); clearPendingJob(); closeM('loadingModal'); alert('Hệ thống đang bận, vui lòng thử lại sau ít phút.'); refreshQuota(); return; }
    try{
      var r=await fetch(URLS.status+'?job='+encodeURIComponent(job),{cache:'no-store'});
      var d=await r.json();
      if(d.status==='done'){ clearInterval(timer); clearPendingJob(); closeM('loadingModal'); showResult(d.result); notifyDone(); }
      else if(d.status==='error'){ clearInterval(timer); clearPendingJob(); closeM('loadingModal');
        if(d.remaining!=null){ remaining=d.remaining; document.getElementById('remainBadge').textContent=badgeText(d); }
        alert((d.msg||'Xử lý thất bại.')+' (Lượt của bạn đã được hoàn lại)'); }
      // processing -> chờ vòng sau
    }catch(e){ /* mạng chập chờn -> thử vòng sau */ }
  }, 3000);
}

// Quay lại trang khi job còn đang chạy -> tự nối tiếp, không mất lượt
(function(){
  try{
    var p=JSON.parse(localStorage.getItem('dali_pending_job')||'null');
    if(!p||!p.job) return;
    if(Date.now()-(p.at||0) > 15*60*1000){ localStorage.removeItem('dali_pending_job'); return; }
    showPill(); pollJob(p.job, p.at);
  }catch(e){}
})();

function showResult(res, restored){
  const sec=document.getElementById('resultSection');
  // Khi khôi phục sau reload: dùng URL ảnh trên server (blob preview không còn)
  document.getElementById('rOriginal').src=(restored ? (res.original||res.enhanced) : (previewImg.src||res.original))||'';
  document.getElementById('rEnhanced').src=res.enhanced||res.original||'';
  lastResultUrl=res.img_output||'';            // bản đồ màu: chỉ gửi cho shop, không hiển thị
  lastEnhancedUrl=res.enhanced||'';
  document.getElementById('restoreNote').classList.toggle('hidden', !restored);
  sec.classList.remove('hidden');
  updateStickyCta();
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
var selJ = (function(){ var k = PRICING.colors.findIndex(function(c){ return +c === 36; }); return k >= 0 ? k : 0; })();
function fmtVnd(n){ return n.toLocaleString('vi-VN')+'đ'; }
function curPrice(){ return PRICING.sizes[selI].prices[selJ] || 0; }
// Thanh nổi mobile: sau khi có kết quả -> đổi thành nút đặt hàng kèm giá hiện tại
function updateStickyCta(){
  var sc=document.getElementById('stickyCta');
  if(!sc || document.getElementById('resultSection').classList.contains('hidden')) return;
  sc.setAttribute('href','#resultSection');
  sc.textContent='🛒 Đặt tranh này — '+fmtVnd(curPrice());
}
function paintChips(){
  document.querySelectorAll('#sizeChips .pick').forEach(b=>{
    var on = +b.dataset.i === selI;
    b.className='pick border-2 rounded-xl px-4 py-3 text-sm font-bold '+(on?'border-primary bg-green-50 text-primaryd':'border-green-200 bg-white');
  });
  document.querySelectorAll('.sizePrice').forEach(sp=>{
    sp.textContent = fmtVnd(PRICING.sizes[+sp.dataset.i].prices[selJ] || 0);
  });
  document.querySelectorAll('#colorChips .pick').forEach(b=>{
    var on = +b.dataset.j === selJ;
    b.className='pick border-2 rounded-xl px-4 py-3 text-sm font-bold '+(on?'border-primary bg-green-50 text-primaryd':'border-green-200 bg-white');
  });
  document.getElementById('resultOrderLabel').textContent='Đặt tranh này — '+fmtVnd(curPrice());
  updateStickyCta();
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

function outOfQuota(){ document.getElementById('orderTitle').textContent='Hết lượt miễn phí — nhận thêm +{{ \App\Models\DesignQuota::ORDER_BONUS }} lượt ngay';
  document.getElementById('orderDesc').innerHTML='Đặt hàng để shop làm tranh cho bạn — và nhận thêm <b>+{{ \App\Models\DesignQuota::ORDER_BONUS }} lượt</b> tạo.'
    +'<br><a href="https://zalo.me/0856911698" target="_blank" class="inline-flex items-center gap-1 mt-2 text-[#0068FF] font-extrabold underline">💬 Hoặc gửi ảnh qua Zalo, shop làm xem trước giúp bạn</a>';
  openOrder(document.getElementById('resultSection').classList.contains('hidden') ? '' : (PRICING.sizes[selI].label+' — '+fmtVnd(curPrice())+' · '+PRICING.colors[selJ]+' màu')); }

function openOrder(pkg){
  // Khôi phục form nếu modal đang ở trạng thái "đặt thành công" của đơn trước
  if(orderDone){ orderDone=false;
    document.getElementById('orderForm').classList.remove('hidden');
    document.getElementById('orderSubmit').classList.remove('hidden');
    document.getElementById('orderTitle').textContent='Đặt hàng tranh thiết kế';
    document.getElementById('orderDesc').innerHTML='Điền thông tin, shop sẽ liên hệ &amp; làm tranh cho bạn. Đặt xong được <b>+{{ \App\Models\DesignQuota::ORDER_BONUS }} lượt</b>.';
  }
  selectedPackage=pkg||''; const row=document.getElementById('oPkgRow');
  if(selectedPackage){ row.classList.remove('hidden'); document.getElementById('oPkgLabel').textContent=selectedPackage; } else { row.classList.add('hidden'); }
  document.getElementById('orderSubmit').textContent = (selectedPackage && selectedPackage.indexOf('đ')>-1) ? ('Đặt hàng COD — '+fmtVnd(curPrice())) : 'Gửi đơn — shop gọi lại ngay';
  openM('orderModal'); }
document.querySelectorAll('.orderOpen').forEach(b=>b.addEventListener('click',()=>openOrder(b.dataset.package||'')));

function validVnPhone(p){ p=p.replace(/[\s.\-]/g,''); return /^(0|\+?84)(3|5|7|8|9)\d{8}$/.test(p); }
document.getElementById('oPhone').addEventListener('input',()=>{ document.getElementById('oPhoneErr').classList.add('hidden'); document.getElementById('oPhone').classList.remove('border-red-400'); });
document.getElementById('orderForm').addEventListener('submit',function(e){ e.preventDefault(); document.getElementById('orderSubmit').click(); });

document.getElementById('orderSubmit').addEventListener('click', async ()=>{
  const btn=document.getElementById('orderSubmit');
  if(btn.disabled) return;
  const name=document.getElementById('oName').value.trim(), phone=document.getElementById('oPhone').value.trim();
  if(!name||!phone){ alert('Vui lòng nhập họ tên và số điện thoại.'); return; }
  if(!validVnPhone(phone)){ document.getElementById('oPhoneErr').classList.remove('hidden'); document.getElementById('oPhone').classList.add('border-red-400'); document.getElementById('oPhone').focus(); return; }
  const fd=new FormData(); fd.append('device_id',DEVICE); fd.append('customer_name',name); fd.append('customer_phone',phone);
  fd.append('customer_address',document.getElementById('oAddr').value.trim()); fd.append('result_url',lastResultUrl); fd.append('enhanced_url',lastEnhancedUrl); fd.append('package',selectedPackage);
  btn.disabled=true; const oldLabel=btn.innerHTML; btn.innerHTML='<i class="ri-loader-4-line animate-spin"></i> Đang gửi…';
  try{ const r=await fetch(URLS.order,{method:'POST',headers:{'X-CSRF-TOKEN':CSRF},body:fd}); const d=await r.json();
    if(!d.ok){ alert('Gửi đơn thất bại, thử lại.'); return; }
    remaining=d.remaining??remaining; document.getElementById('remainBadge').textContent=badgeText(d);
    // Trạng thái thành công NGAY TRONG modal: giữ mã đơn + bước tiếp theo qua Zalo
    document.getElementById('orderForm').classList.add('hidden');
    document.getElementById('orderSubmit').classList.add('hidden');
    document.getElementById('orderTitle').textContent='✅ Đã nhận đơn '+d.code;
    document.getElementById('orderDesc').innerHTML='Shop sẽ gọi xác nhận trong hôm nay. Bạn được <b>+'+d.bonus+' lượt tạo</b>. Thanh toán khi nhận hàng (COD).'
      +'<br><a href="https://zalo.me/0856911698" target="_blank" class="inline-block mt-3 bg-[#0068FF] text-white font-extrabold px-5 py-2.5 rounded-xl">💬 Nhắn Zalo kèm mã đơn '+d.code+'</a>';
    orderDone=true;
  }catch(e){ alert('Lỗi kết nối, thử lại sau.'); }
  finally{ btn.disabled=false; btn.innerHTML=oldLabel; }
});
</script>
</body>
</html>
