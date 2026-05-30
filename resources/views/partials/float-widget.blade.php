{{-- Nút nổi: Giỏ hàng + Zalo + Messenger + Gọi điện --}}
@php
  $fwPhone  = $settings['shop_phone'] ?? '0856911698';
  $fwDigits = preg_replace('/\D/', '', $fwPhone);
  $fwZalo   = preg_replace('/^0/', '84', $fwDigits); // 0856.. -> 84856..
@endphp
<div class="dali-fab">
  <a href="{{ route('cart') }}" class="fab-btn fab-cart" title="Giỏ hàng" aria-label="Giỏ hàng">
    🛒<span class="fab-badge" id="fabCartCount" style="display:none">0</span>
  </a>
  @if(!empty($settings['fb_page']))
  <a href="https://m.me/{{ $settings['fb_page'] }}" target="_blank" rel="noopener" class="fab-btn fab-mes" title="Chat Messenger" aria-label="Messenger">
    <svg viewBox="0 0 24 24" width="22" height="22" fill="#fff"><path d="M12 2C6.5 2 2 6.1 2 11.2c0 2.9 1.4 5.5 3.7 7.2V22l3.4-1.9c.9.300 1.9.4 2.9.4 5.5 0 10-4.1 10-9.2S17.5 2 12 2zm1 12.4l-2.6-2.8-5 2.8 5.5-5.8 2.6 2.8 4.9-2.8-5.4 5.8z"/></svg>
  </a>
  @endif
  <a href="https://zalo.me/{{ $fwZalo }}" target="_blank" rel="noopener" class="fab-btn fab-zalo" title="Chat Zalo" aria-label="Zalo">
    <span style="font-weight:900;font-size:12px;letter-spacing:-.5px">Zalo</span>
  </a>
  <a href="tel:{{ $fwPhone }}" class="fab-btn fab-phone" title="Gọi điện" aria-label="Gọi điện">📞</a>
</div>
<style>
.dali-fab{position:fixed;right:16px;bottom:18px;z-index:900;display:flex;flex-direction:column;gap:11px}
.fab-btn{width:50px;height:50px;border-radius:50%;display:flex;align-items:center;justify-content:center;text-decoration:none;font-size:21px;color:#fff;box-shadow:0 4px 14px rgba(0,0,0,.22);transition:transform .2s;position:relative;animation:fabIn .4s ease-out backwards}
.fab-btn:hover{transform:scale(1.1)}
.fab-cart{background:linear-gradient(135deg,#3A9A12,#6BBF1F)}
.fab-mes{background:linear-gradient(135deg,#00B2FF,#006AFF)}
.fab-zalo{background:#0068FF}
.fab-phone{background:#16A34A;animation:fabIn .4s ease-out backwards,fabRing 1.6s ease-in-out infinite}
.fab-badge{position:absolute;top:-3px;right:-3px;background:#FF8FB1;color:#fff;font-size:10px;font-weight:800;min-width:18px;height:18px;border-radius:9px;display:flex;align-items:center;justify-content:center;padding:0 4px}
@keyframes fabIn{from{opacity:0;transform:translateY(12px) scale(.8)}to{opacity:1;transform:none}}
@keyframes fabRing{0%,100%{box-shadow:0 4px 14px rgba(0,0,0,.22),0 0 0 0 rgba(22,163,74,.5)}50%{box-shadow:0 4px 14px rgba(0,0,0,.22),0 0 0 9px rgba(22,163,74,0)}}
@media(max-width:600px){.dali-fab{right:12px;bottom:14px;gap:9px}.fab-btn{width:46px;height:46px;font-size:19px}}
</style>
<script>
(function(){
  fetch('{{ route("cart.count") }}').then(r=>r.json()).then(d=>{
    var b=document.getElementById('fabCartCount');
    if(b && d.count>0){b.textContent=d.count;b.style.display='flex';}
  }).catch(()=>{});
})();
</script>
