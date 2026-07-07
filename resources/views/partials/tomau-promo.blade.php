{{-- Card giới thiệu site anh em tomau.tranhdali.vn (Tô Tranh Dali — tranh tô màu MIỄN PHÍ cho bé).
     Tự chứa style (không phụ thuộc CSS trang nhúng). Dùng lại ở nhiều trang qua @include.
     $place   : order|home|tracking|guide|blog|blog_list|cart_empty|product_footer  → map utm_medium + câu lead.
     $variant : full (thẻ ngang, mặc định) | mini (1 dòng mảnh — cho trang có ý-định-mua như product).
     Link kèm UTM để bên tomau đo nguồn từng vị trí. Nhấn "miễn phí", KHÔNG nêu giá. --}}
@php
  $tomauBase = rtrim(config('tomau.url', 'https://tomau.tranhdali.vn'), '/');
  $place   = $place ?? 'home';
  $variant = $variant ?? 'full';
  $mediumMap = [
    'order' => 'order_success', 'home' => 'homepage', 'tracking' => 'order_tracking',
    'guide' => 'guide_page', 'blog' => 'blog', 'blog_list' => 'blog_list',
    'cart_empty' => 'cart_empty', 'product_footer' => 'product_footer',
  ];
  $medium = $mediumMap[$place] ?? 'other';
  $tomauHref = $tomauBase . '?utm_source=tranhdali.vn&utm_medium=' . $medium . '&utm_campaign=cross_promo';
  $leadMap = [
    'order'          => 'Cho bé nhà bạn tô tranh nhé! 🎨',
    'home'           => 'Tô Tranh Dali — cho bé nhà bạn 🎨',
    'tracking'       => 'Trong lúc chờ tranh về, cho bé tô tranh nhé! 🎨',
    'guide'          => 'Đọc xong mẹo tô? Ghé kho tranh cho bé nữa nhé 🎨',
    'blog'           => 'Nhà có bé thích tô màu? 🎨',
    'blog_list'      => 'Nhà có bé thích tô màu? 🎨',
    'cart_empty'     => 'Chưa chọn được tranh? Ghé kho tranh cho bé nhé 🎨',
    'product_footer' => 'Có con nhỏ? Tranh tô màu miễn phí cho bé',
  ];
  $lead = $leadMap[$place] ?? 'Tô Tranh Dali — cho bé nhà bạn 🎨';
@endphp
@if($variant === 'mini')
<a href="{{ $tomauHref }}" target="_blank" rel="noopener" aria-label="Khám phá Tô Tranh Dali"
   style="display:flex;align-items:center;gap:9px;width:max-content;max-width:100%;margin:20px auto 0;padding:10px 16px;background:#FFF7ED;border:1px solid #FBD6BF;border-radius:12px;text-decoration:none">
  <span style="flex-shrink:0;font-size:19px">🖍️</span>
  <span style="font-size:13px;line-height:1.45;color:#6B4A3A">{{ $lead }} — <b style="color:#E15B2D;white-space:nowrap">Tô Tranh Dali →</b></span>
</a>
@else
<a href="{{ $tomauHref }}" target="_blank" rel="noopener" aria-label="Khám phá Tô Tranh Dali"
   style="display:flex;align-items:center;gap:13px;max-width:520px;margin:14px auto 0;padding:13px 15px;
          background:linear-gradient(135deg,#FFF7ED,#FEF1F0);border:1px solid #FBD6BF;border-radius:16px;
          text-decoration:none;text-align:left;box-shadow:0 4px 14px rgba(224,91,45,.13)"
   onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 9px 22px rgba(224,91,45,.22)'"
   onmouseout="this.style.transform='';this.style.boxShadow='0 4px 14px rgba(224,91,45,.13)'">
  <span style="flex-shrink:0;width:46px;height:46px;display:flex;align-items:center;justify-content:center;
               font-size:26px;background:#fff;border-radius:13px;box-shadow:0 2px 8px rgba(224,91,45,.15)">🖍️</span>
  <span style="flex:1;min-width:0">
    <span style="display:block;font-weight:800;font-size:14px;color:#B5451F;margin-bottom:2px">{{ $lead }}</span>
    <span style="display:block;font-size:12.5px;line-height:1.5;color:#6B4A3A">Kho <b>tranh tô màu miễn phí</b> cho bé — tải về in tại nhà, không giới hạn, không cần đăng ký.</span>
  </span>
  <span style="flex-shrink:0;font-weight:800;font-size:13px;color:#fff;white-space:nowrap;
               background:linear-gradient(135deg,#F5943F,#E15B2D);padding:9px 14px;border-radius:11px">Khám phá&nbsp;→</span>
</a>
@endif
