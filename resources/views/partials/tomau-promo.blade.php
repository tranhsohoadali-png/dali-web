{{-- Card giới thiệu site anh em tomau.tranhdali.vn (Tô Tranh Dali — tranh tô màu cho bé).
     Tự chứa style (không phụ thuộc CSS của trang nhúng) để dùng lại ở mọi trang.
     $place: 'order' (sau khi đặt hàng) | 'home' (trang chủ). Link kèm UTM để tomau đo nguồn. --}}
@php
  $tomauBase = rtrim(config('tomau.url', 'https://tomau.tranhdali.vn'), '/');
  $place = $place ?? 'home';
  $tomauHref = $tomauBase . '?utm_source=tranhdali.vn&utm_medium=' . ($place === 'order' ? 'order_success' : 'homepage') . '&utm_campaign=cross_promo';
  $lead = $place === 'order' ? 'Cho bé nhà bạn tô tranh nhé! 🎨' : 'Tô Tranh Dali — cho bé nhà bạn 🎨';
@endphp
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
