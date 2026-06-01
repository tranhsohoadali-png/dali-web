<div style="width:220px;flex-shrink:0;background:linear-gradient(175deg,#1C5200,#2D7A08,#3A9A12);display:flex;flex-direction:column;min-height:100vh">
  <div style="padding:20px 18px 14px;border-bottom:1px solid rgba(255,255,255,.12)">
    <a href="{{ route('admin.dashboard') }}" style="display:block;text-decoration:none" title="Về trang Tổng quan">
      <img src="{{ asset('images/logo_dali.png') }}" alt="DALI" style="height:38px;filter:brightness(0) invert(1);display:block">
    </a>
    <div style="font-size:9px;color:rgba(255,255,255,.4);letter-spacing:2.5px;margin-top:6px;text-transform:uppercase">Tô điểm cuộc sống</div>
  </div>
  <nav style="flex:1;padding:12px 8px;overflow-y:auto">
    <div style="font-size:9px;letter-spacing:2.5px;color:rgba(255,255,255,.25);padding:8px 10px 5px;text-transform:uppercase">Tổng quan</div>
    <a href="{{ route('admin.dashboard') }}" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;color:{{ request()->routeIs('admin.dashboard') ? '#fff' : 'rgba(255,255,255,.65)' }};font-size:13px;font-weight:{{ request()->routeIs('admin.dashboard') ? '700' : '500' }};text-decoration:none;background:{{ request()->routeIs('admin.dashboard') ? 'rgba(255,255,255,.18)' : 'transparent' }};border:1px solid {{ request()->routeIs('admin.dashboard') ? 'rgba(255,255,255,.28)' : 'transparent' }};margin-bottom:2px;transition:all .2s">
      <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
      Dashboard
    </a>
    <div style="font-size:9px;letter-spacing:2.5px;color:rgba(255,255,255,.25);padding:8px 10px 5px;margin-top:4px;text-transform:uppercase">Quản lý</div>
    <a href="{{ route('admin.hero.edit') }}" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;color:{{ request()->routeIs('admin.hero*') ? '#fff' : 'rgba(255,255,255,.65)' }};font-size:13px;font-weight:{{ request()->routeIs('admin.hero*') ? '700' : '500' }};text-decoration:none;background:{{ request()->routeIs('admin.hero*') ? 'rgba(255,255,255,.18)' : 'transparent' }};border:1px solid {{ request()->routeIs('admin.hero*') ? 'rgba(255,255,255,.28)' : 'transparent' }};margin-bottom:2px">
      <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
      Màn hình chính
    </a>
    <a href="{{ route('admin.categories.index') }}" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;color:{{ request()->routeIs('admin.categories*') ? '#fff' : 'rgba(255,255,255,.65)' }};font-size:13px;font-weight:{{ request()->routeIs('admin.categories*') ? '700' : '500' }};text-decoration:none;background:{{ request()->routeIs('admin.categories*') ? 'rgba(255,255,255,.18)' : 'transparent' }};border:1px solid {{ request()->routeIs('admin.categories*') ? 'rgba(255,255,255,.28)' : 'transparent' }};margin-bottom:2px">
      <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
      Danh mục
    </a>
    <a href="{{ route('admin.products.index') }}" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;color:{{ request()->routeIs('admin.products*') ? '#fff' : 'rgba(255,255,255,.65)' }};font-size:13px;font-weight:{{ request()->routeIs('admin.products*') ? '700' : '500' }};text-decoration:none;background:{{ request()->routeIs('admin.products*') ? 'rgba(255,255,255,.18)' : 'transparent' }};border:1px solid {{ request()->routeIs('admin.products*') ? 'rgba(255,255,255,.28)' : 'transparent' }};margin-bottom:2px">
      <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
      Sản phẩm
    </a>
    <div style="font-size:9px;letter-spacing:2.5px;color:rgba(255,255,255,.25);padding:8px 10px 5px;margin-top:4px;text-transform:uppercase">Kinh doanh</div>
    <a href="{{ route('admin.posts.index') }}" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;color:{{ request()->routeIs('admin.posts*') ? '#fff' : 'rgba(255,255,255,.65)' }};font-size:13px;font-weight:{{ request()->routeIs('admin.posts*') ? '700' : '500' }};text-decoration:none;background:{{ request()->routeIs('admin.posts*') ? 'rgba(255,255,255,.18)' : 'transparent' }};border:1px solid {{ request()->routeIs('admin.posts*') ? 'rgba(255,255,255,.28)' : 'transparent' }};margin-bottom:2px">
      <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
      Blog
    </a>
    <a href="{{ route('admin.affiliates.index') }}" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;color:{{ request()->routeIs('admin.affiliates*') ? '#fff' : 'rgba(255,255,255,.65)' }};font-size:13px;font-weight:{{ request()->routeIs('admin.affiliates*') ? '700' : '500' }};text-decoration:none;background:{{ request()->routeIs('admin.affiliates*') ? 'rgba(255,255,255,.18)' : 'transparent' }};border:1px solid {{ request()->routeIs('admin.affiliates*') ? 'rgba(255,255,255,.28)' : 'transparent' }};margin-bottom:2px">
      <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
      CTV Affiliate
    </a>
    <a href="{{ route('admin.withdrawals.index') }}" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;color:{{ request()->routeIs('admin.withdrawals*') ? '#fff' : 'rgba(255,255,255,.65)' }};font-size:13px;font-weight:{{ request()->routeIs('admin.withdrawals*') ? '700' : '500' }};text-decoration:none;background:{{ request()->routeIs('admin.withdrawals*') ? 'rgba(255,255,255,.18)' : 'transparent' }};border:1px solid {{ request()->routeIs('admin.withdrawals*') ? 'rgba(255,255,255,.28)' : 'transparent' }};margin-bottom:2px">
      <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
      Rút tiền CTV
    </a>
        <a href="{{ route('admin.coupons.index') }}" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;color:{{ request()->routeIs('admin.coupons*') ? '#fff' : 'rgba(255,255,255,.65)' }};font-size:13px;font-weight:{{ request()->routeIs('admin.coupons*') ? '700' : '500' }};text-decoration:none;background:{{ request()->routeIs('admin.coupons*') ? 'rgba(255,255,255,.18)' : 'transparent' }};border:1px solid {{ request()->routeIs('admin.coupons*') ? 'rgba(255,255,255,.28)' : 'transparent' }};margin-bottom:2px">
      <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
      Mã giảm giá
    </a>
    <a href="{{ route('admin.reviews.index') }}" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;color:{{ request()->routeIs('admin.reviews*') ? '#fff' : 'rgba(255,255,255,.65)' }};font-size:13px;font-weight:{{ request()->routeIs('admin.reviews*') ? '700' : '500' }};text-decoration:none;background:{{ request()->routeIs('admin.reviews*') ? 'rgba(255,255,255,.18)' : 'transparent' }};border:1px solid {{ request()->routeIs('admin.reviews*') ? 'rgba(255,255,255,.28)' : 'transparent' }};margin-bottom:2px">
      <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
      Đánh giá
    </a>
    <a href="{{ route('admin.orders.index') }}" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;color:{{ request()->routeIs('admin.orders*') ? '#fff' : 'rgba(255,255,255,.65)' }};font-size:13px;font-weight:{{ request()->routeIs('admin.orders*') ? '700' : '500' }};text-decoration:none;background:{{ request()->routeIs('admin.orders*') ? 'rgba(255,255,255,.18)' : 'transparent' }};border:1px solid {{ request()->routeIs('admin.orders*') ? 'rgba(255,255,255,.28)' : 'transparent' }};margin-bottom:2px;position:relative">
      <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
      Đơn hàng
      @php $newOrders = \App\Models\Order::where('status','new')->count(); @endphp
      @if($newOrders > 0)
      <span style="position:absolute;right:10px;background:#EF4444;color:#fff;font-size:10px;font-weight:800;padding:1px 7px;border-radius:20px">{{ $newOrders }}</span>
      @endif
    </a>
    <div style="font-size:9px;letter-spacing:2.5px;color:rgba(255,255,255,.25);padding:8px 10px 5px;margin-top:4px;text-transform:uppercase">Công cụ</div>
    <a href="{{ route('admin.colortool') }}" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;color:{{ request()->routeIs('admin.colortool') ? '#fff' : 'rgba(255,255,255,.65)' }};font-size:13px;font-weight:{{ request()->routeIs('admin.colortool') ? '700' : '500' }};text-decoration:none;background:{{ request()->routeIs('admin.colortool') ? 'rgba(255,255,255,.18)' : 'transparent' }};border:1px solid {{ request()->routeIs('admin.colortool') ? 'rgba(255,255,255,.28)' : 'transparent' }};margin-bottom:2px">
      <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3a9 9 0 100 18c.83 0 1.5-.67 1.5-1.5 0-.39-.15-.74-.39-1.01-.23-.26-.39-.62-.39-1 0-.83.67-1.5 1.5-1.5H16a5 5 0 005-5c0-4.42-4.03-8-9-8z"/><circle cx="7.5" cy="10.5" r="1.1" fill="currentColor" stroke="none"/><circle cx="12" cy="7.5" r="1.1" fill="currentColor" stroke="none"/><circle cx="16.5" cy="10.5" r="1.1" fill="currentColor" stroke="none"/></svg>
      Tách màu &amp; mã DALI
    </a>
    <div style="font-size:9px;letter-spacing:2.5px;color:rgba(255,255,255,.25);padding:8px 10px 5px;margin-top:4px;text-transform:uppercase">Hệ thống</div>
    <a href="{{ route('admin.settings') }}" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;color:{{ request()->routeIs('admin.settings*') ? '#fff' : 'rgba(255,255,255,.65)' }};font-size:13px;font-weight:{{ request()->routeIs('admin.settings*') ? '700' : '500' }};text-decoration:none;background:{{ request()->routeIs('admin.settings*') ? 'rgba(255,255,255,.18)' : 'transparent' }};border:1px solid {{ request()->routeIs('admin.settings*') ? 'rgba(255,255,255,.28)' : 'transparent' }};margin-bottom:2px">
      <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
      Cài đặt
    </a>
    <a href="{{ route('home') }}" target="_blank" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;color:rgba(255,255,255,.65);font-size:13px;font-weight:500;text-decoration:none;margin-bottom:2px">
      <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
      Xem trang chủ ↗
    </a>
  </nav>
  <div style="padding:14px;border-top:1px solid rgba(255,255,255,.12)">
    <div style="display:flex;align-items:center;gap:9px;margin-bottom:8px">
      <div style="width:34px;height:34px;border-radius:50%;background:rgba(255,255,255,.18);border:2px solid rgba(255,255,255,.35);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;color:#fff">A</div>
      <div><div style="font-size:12px;font-weight:700;color:#fff">Admin</div><div style="font-size:10px;color:rgba(255,255,255,.4)">Quản trị viên</div></div>
    </div>
    <a href="{{ route('admin.logout') }}" style="font-size:10px;color:rgba(255,255,255,.3);text-decoration:none;letter-spacing:1px">Đăng xuất →</a>
  </div>
</div>
