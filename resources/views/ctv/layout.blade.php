<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<meta name="theme-color" content="#1A4D00">
<title>@yield('title','CTV') | DALI</title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{
  --g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--gn:#C6F135;
  --bg:#F2FDE8;--card:#fff;--bd:#D0EAA8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8EC860;
  --pk:#FF6B9D;--yl:#F59E0B;--red:#EF4444;
  --tab-h:64px;--top-h:56px;
  --safe-b:env(safe-area-inset-bottom,0px);
}
*{box-sizing:border-box;margin:0;padding:0;-webkit-tap-highlight-color:transparent}
html{height:100%;scroll-behavior:smooth}
body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:var(--tx);
  min-height:100vh;min-height:100dvh;display:flex;flex-direction:column;line-height:1.5}
img{max-width:100%;display:block}

/* ═══ TOPBAR ═══ */
.topbar{
  height:var(--top-h);background:linear-gradient(135deg,#1A4D00,#2D7A08,#3A9A12);
  display:flex;align-items:center;justify-content:space-between;padding:0 14px;
  position:sticky;top:0;z-index:200;flex-shrink:0;
  box-shadow:0 2px 12px rgba(0,0,0,.22);
}
.tb-left{display:flex;align-items:center;gap:8px}
.tb-logo{font-size:20px;font-weight:900;letter-spacing:2px;color:#fff;text-decoration:none;line-height:1}
.tb-logo span{color:var(--gn)}
.tb-badge{background:rgba(198,241,53,.2);border:1px solid rgba(198,241,53,.4);
  color:var(--gn);font-size:9px;font-weight:800;padding:2px 7px;border-radius:20px;letter-spacing:.5px}
.tb-nav{display:none;align-items:center;gap:2px;flex:1;justify-content:center;padding:0 8px}
.tb-nav a{color:rgba(255,255,255,.72);font-size:12px;font-weight:600;text-decoration:none;
  padding:6px 10px;border-radius:8px;transition:all .18s;white-space:nowrap}
.tb-nav a:hover,.tb-nav a.act{background:rgba(255,255,255,.15);color:#fff;font-weight:800}
.tb-right{display:flex;align-items:center;gap:8px}
.tb-name{font-size:12px;font-weight:700;color:rgba(255,255,255,.85);
  max-width:100px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;display:none}
.btn-out{color:rgba(255,255,255,.8);font-size:11.5px;text-decoration:none;
  border:1px solid rgba(255,255,255,.3);border-radius:50px;padding:5px 12px;
  transition:all .18s;white-space:nowrap}
.btn-out:hover{background:rgba(255,255,255,.15);color:#fff}

/* ═══ PAGE BODY ═══ */
.page-body{flex:1;padding:14px 14px calc(var(--tab-h) + var(--safe-b) + 10px);min-height:0}

/* ═══ FLASH ═══ */
.flash{padding:12px 14px;border-radius:14px;font-size:13px;font-weight:600;
  margin-bottom:14px;display:flex;align-items:flex-start;gap:8px;line-height:1.45}
.flash.ok{background:#DCFCE7;color:#166534;border:1px solid #86EFAC}
.flash.err{background:#FEE2E2;color:#991B1B;border:1px solid #FCA5A5}

/* ═══ CARD ═══ */
.card{background:var(--card);border:1.5px solid var(--bd);border-radius:18px;
  padding:16px;margin-bottom:14px;box-shadow:0 1px 6px rgba(0,0,0,.05)}
.card-title{font-size:13px;font-weight:800;color:var(--gd);margin-bottom:14px;
  display:flex;align-items:center;gap:7px}
.card-title .ic{font-size:17px}

/* ═══ BUTTONS ═══ */
.btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;
  background:linear-gradient(135deg,#2D7A08,var(--g));color:#fff;border:none;
  border-radius:14px;padding:13px 22px;font-size:14px;font-weight:800;cursor:pointer;
  text-decoration:none;transition:all .18s;font-family:inherit;line-height:1;
  min-height:48px}
.btn:active{transform:scale(.97);opacity:.9}
.btn.full{display:flex;width:100%}
.btn.ghost{background:var(--gll);color:var(--gd);border:2px solid var(--g)}
.btn.ghost:active{background:var(--gl)}
.btn.sm{padding:9px 16px;font-size:13px;min-height:40px;border-radius:10px}
.btn.danger{background:linear-gradient(135deg,#EF4444,#B91C1C)}

/* ═══ FORM ═══ */
.field{margin-bottom:14px}
.field label{display:block;font-size:12px;font-weight:700;color:var(--tx2);margin-bottom:6px}
.field .hint{font-size:11px;color:var(--tx3);margin-top:5px;line-height:1.4}
.field input,.field select,.field textarea{
  width:100%;border:1.5px solid var(--bd);border-radius:12px;
  padding:13px 14px;font-size:15px;font-family:inherit;background:var(--gll);
  color:var(--tx);transition:border .18s,background .18s;appearance:none;
  -webkit-appearance:none;min-height:48px}
.field select{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%238EC860' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
  background-repeat:no-repeat;background-position:right 14px center;padding-right:36px}
.field input:focus,.field select:focus,.field textarea:focus{
  outline:none;border-color:var(--g);background:#fff;box-shadow:0 0 0 3px rgba(107,191,31,.12)}
.field input:disabled{opacity:.6;cursor:default;background:#f5f5f5}
.field textarea{resize:none;min-height:80px;line-height:1.5}

/* ═══ TABBAR ═══ */
.tabbar{
  position:fixed;bottom:0;left:0;right:0;z-index:200;
  background:#fff;border-top:1px solid #E8F4D4;
  display:flex;height:calc(var(--tab-h) + var(--safe-b));
  padding-bottom:var(--safe-b);
  box-shadow:0 -2px 16px rgba(0,0,0,.08);
}
.tabbar a{flex:1;display:flex;flex-direction:column;align-items:center;
  justify-content:center;gap:3px;text-decoration:none;color:var(--tx3);
  font-size:9.5px;font-weight:700;letter-spacing:.2px;transition:color .15s;
  position:relative;padding:6px 0}
.tabbar a .ic{font-size:21px;line-height:1;transition:transform .15s}
.tabbar a.act{color:var(--gd)}
.tabbar a.act .ic{transform:scale(1.12)}
.tabbar a.act::after{content:'';position:absolute;top:0;left:50%;transform:translateX(-50%);
  width:36px;height:3px;background:linear-gradient(90deg,var(--g),var(--gn));
  border-radius:0 0 4px 4px}
.tabbar a .bdot{position:absolute;top:7px;right:calc(50% - 13px);
  width:8px;height:8px;background:var(--pk);border-radius:50%;border:2px solid #fff}

/* ═══ MISC ═══ */
.muted{color:var(--tx3);font-size:12px;line-height:1.5}
.divider{height:1px;background:var(--bd);margin:16px 0}
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700}
.badge.pending{background:#FEF3C7;color:#92400E}
.badge.approved{background:#DCFCE7;color:#166534}
.badge.rejected{background:#FEE2E2;color:#991B1B}

/* ═══ STAT GRID ═══ */
.stat-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px}
.stat-box{background:var(--card);border:1.5px solid var(--bd);border-radius:16px;
  padding:14px 12px;text-align:center}
.stat-box .sn{font-size:20px;font-weight:900;line-height:1.1;margin-bottom:3px}
.stat-box .sl{font-size:10px;color:var(--tx3);font-weight:700;letter-spacing:.2px}

/* ═══ ORDER LIST ROWS ═══ */
.orow{padding:12px 0;border-bottom:1px solid #EDF7DE}
.orow:last-child{border:none}
.orow-top{display:flex;justify-content:space-between;align-items:flex-start;gap:8px}
.orow-code{font-size:13.5px;font-weight:800;color:var(--tx)}
.orow-meta{font-size:11px;color:var(--tx3);margin-top:2px}
.orow-right{text-align:right;flex-shrink:0}
.orow-total{font-size:14px;font-weight:900;color:var(--tx)}
.orow-comm{font-size:12px;font-weight:800;color:var(--g)}

/* ═══ EMPTY STATE ═══ */
.empty{text-align:center;padding:40px 20px}
.empty .ei{font-size:52px;margin-bottom:12px}
.empty .et{font-size:16px;font-weight:800;color:var(--tx);margin-bottom:6px}
.empty .es{font-size:13px;color:var(--tx3)}

/* ═══ HERO BANNER ═══ */
.hero-banner{
  background:linear-gradient(135deg,#1A4D00,#2D7A08,#3A9A12);
  border-radius:20px;padding:18px 16px;color:#fff;margin-bottom:14px;
  position:relative;overflow:hidden}
.hero-banner::after{content:'🎨';position:absolute;right:14px;top:50%;
  transform:translateY(-50%);font-size:52px;opacity:.12}
.hero-sub{font-size:11px;color:rgba(255,255,255,.7);font-weight:600;margin-bottom:2px}
.hero-name{font-size:22px;font-weight:900;margin-bottom:10px}
.hero-meta{font-size:11.5px;color:rgba(255,255,255,.8);margin-bottom:14px}
.hero-meta b{color:var(--gn)}
.ref-row{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);
  border-radius:12px;padding:10px 12px;display:flex;align-items:center;gap:8px}
.ref-url{flex:1;font-size:11.5px;color:rgba(255,255,255,.9);font-weight:600;
  word-break:break-all;line-height:1.4}
.copy-btn{flex-shrink:0;background:var(--gn);color:#1A4D00;border:none;
  border-radius:50px;padding:7px 13px;font-size:11.5px;font-weight:900;
  cursor:pointer;font-family:inherit;white-space:nowrap}
.copy-btn:active{transform:scale(.96)}

/* ═══ TOTAL BAR ═══ */
.total-bar{background:linear-gradient(135deg,#1A4D00,var(--g));color:#fff;
  border-radius:16px;padding:14px 16px;margin-bottom:14px}
.tbar-row{display:flex;justify-content:space-between;align-items:center;
  font-size:13px;margin-bottom:5px}
.tbar-row:last-child{margin-bottom:0;padding-top:8px;margin-top:4px;
  border-top:1px solid rgba(255,255,255,.2)}
.tbar-row .k{font-weight:600;opacity:.9}
.tbar-row .v{font-weight:800}
.tbar-row.big .v{font-size:20px;font-weight:900}
.tbar-row.green .v{color:var(--gn);font-size:15px}

/* ═══ DESKTOP ═══ */
@media(min-width:800px){
  .tabbar{display:none}
  .tb-nav{display:flex}
  .tb-name{display:block}
  .page-body{padding:22px 28px 32px;max-width:860px;margin:0 auto;width:100%}
  .stat-grid{grid-template-columns:repeat(4,1fr)}
  .hero-banner{padding:22px 20px}
  .hero-name{font-size:24px}
}
</style>
</head>
<body>

{{-- TOPBAR --}}
<header class="topbar">
  <div class="tb-left">
    <a href="{{ route('home') }}" class="tb-logo">DAL<span>I</span></a>
    <span class="tb-badge">CTV</span>
  </div>
  @if(session('ctv_id'))
  <nav class="tb-nav">
    <a href="{{ route('ctv.dashboard') }}" class="{{ request()->routeIs('ctv.dashboard')?'act':'' }}">📊 Tổng quan</a>
    <a href="{{ route('ctv.order.create') }}" class="{{ request()->routeIs('ctv.order.create')?'act':'' }}">➕ Lên đơn</a>
    <a href="{{ route('ctv.orders') }}" class="{{ request()->routeIs('ctv.orders')?'act':'' }}">📦 Đơn hàng</a>
    <a href="{{ route('ctv.products') }}" class="{{ request()->routeIs('ctv.products')?'act':'' }}">🔗 Tìm tranh</a>
    @unless(optional($ctv)->isAgent())<a href="{{ route('ctv.withdraw.page') }}" class="{{ request()->routeIs('ctv.withdraw.page')?'act':'' }}">💳 Rút tiền</a>@endunless
    <a href="{{ route('ctv.profile') }}" class="{{ request()->routeIs('ctv.profile')?'act':'' }}">👤 Hồ sơ</a>
  </nav>
  <div class="tb-right">
    <span class="tb-name">{{ $ctv->name ?? '' }}</span>
    <a href="{{ route('ctv.logout') }}" class="btn-out">Đăng xuất</a>
  </div>
  @endif
</header>

{{-- CONTENT --}}
<main class="page-body">
  @if(session('success'))<div class="flash ok">✅ {{ session('success') }}</div>@endif
  @if(session('error'))<div class="flash err">⚠️ {{ session('error') }}</div>@endif
  @if($errors->any())<div class="flash err">⚠️ {{ $errors->first() }}</div>@endif
  @yield('content')
</main>

{{-- TABBAR (mobile) --}}
@if(session('ctv_id'))
<nav class="tabbar">
  <a href="{{ route('ctv.dashboard') }}" class="{{ request()->routeIs('ctv.dashboard')?'act':'' }}">
    <span class="ic">📊</span>Tổng quan
  </a>
  <a href="{{ route('ctv.order.create') }}" class="{{ request()->routeIs('ctv.order.create')?'act':'' }}">
    <span class="ic">➕</span>Lên đơn
  </a>
  <a href="{{ route('ctv.orders') }}" class="{{ request()->routeIs('ctv.orders')?'act':'' }}">
    <span class="ic">📦</span>Đơn hàng
  </a>
  <a href="{{ route('ctv.products') }}" class="{{ request()->routeIs('ctv.products')?'act':'' }}">
    <span class="ic">🔗</span>Tìm tranh
  </a>
  @unless(optional($ctv)->isAgent())
  <a href="{{ route('ctv.withdraw.page') }}" class="{{ request()->routeIs('ctv.withdraw.page')?'act':'' }}">
    <span class="ic">💳</span>Rút tiền
  </a>
  @endunless
  <a href="{{ route('ctv.profile') }}" class="{{ request()->routeIs('ctv.profile')?'act':'' }}">
    <span class="ic">👤</span>Hồ sơ
  </a>
</nav>
@endif

<script>
function copyText(t,btn){
  navigator.clipboard?.writeText(t).then(()=>{
    var old=btn.textContent;btn.textContent='✓ Đã sao chép!';
    setTimeout(()=>{btn.textContent=old},2000);
  });
}
</script>
</body>
</html>
