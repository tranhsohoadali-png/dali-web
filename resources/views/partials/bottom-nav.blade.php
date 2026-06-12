{{-- Thanh điều hướng cố định dưới màn hình (chỉ hiện trên điện thoại/tablet nhỏ) --}}
@php $rn = \Route::currentRouteName() ?? ''; @endphp
<div class="dali-bottombar" role="navigation" aria-label="Điều hướng nhanh">
  <a href="{{ route('home') }}" class="{{ $rn==='home' ? 'on' : '' }}">
    <i class="ri-home-5-line"></i><span>Trang chủ</span>
  </a>
  <a href="{{ route('products') }}" class="{{ in_array($rn,['products','product','category']) ? 'on' : '' }}">
    <i class="ri-palette-line"></i><span>Sản phẩm</span>
  </a>
  <a href="{{ route('thiet-ke') }}" class="{{ $rn==='thiet-ke' ? 'on' : '' }}">
    <i class="ri-magic-line"></i><span>Thiết kế</span>
  </a>
  <a href="{{ route('track-order') }}" class="{{ $rn==='track-order' ? 'on' : '' }}">
    <i class="ri-search-line"></i><span>Tra cứu</span>
  </a>
  <a href="{{ route('ctv.login') }}" class="bb-ctv {{ \Illuminate\Support\Str::startsWith($rn,'ctv') ? 'on' : '' }}">
    <i class="ri-team-line"></i><span>CTV</span>
  </a>
</div>
<style>
.dali-bottombar{display:none;position:fixed;bottom:0;left:0;right:0;z-index:950;height:60px;
  background:#fff;border-top:1px solid #E2EFC9;box-shadow:0 -3px 16px rgba(58,122,10,.10);
  justify-content:space-around;align-items:stretch;padding-bottom:env(safe-area-inset-bottom)}
.dali-bottombar a{position:relative;flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;
  gap:3px;text-decoration:none;color:#86AE66;font-size:10px;font-weight:600;letter-spacing:.2px;transition:color .2s}
.dali-bottombar a i{font-size:22px;line-height:1}
.dali-bottombar a span{line-height:1}
.dali-bottombar a.on{color:var(--g,#6BBF1F)}
.dali-bottombar a.on::before{content:'';position:absolute;top:0;left:24%;right:24%;height:3px;
  background:var(--g,#6BBF1F);border-radius:0 0 4px 4px}
.dali-bottombar a:active{transform:scale(.92)}
.dali-bottombar a.bb-ctv i{color:#3A9A12}
.dali-bottombar a.bb-ctv.on i{color:var(--g,#6BBF1F)}
@media(max-width:768px){
  .dali-bottombar{display:flex}
  body{padding-bottom:calc(60px + env(safe-area-inset-bottom))}
  /* Bỏ nút hamburger vì đã có thanh dưới */
  .nav-hamburger{display:none!important}
  /* Nâng cụm nút nổi (Zalo/gọi/giỏ) lên trên thanh tab */
  .dali-fab{bottom:calc(72px + env(safe-area-inset-bottom))!important}
}
</style>
