<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>@yield('title','CTV') | DALI Cộng Tác Viên</title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{
  --g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--gn:#C6F135;
  --bg:#F0FBE4;--card:#fff;--bd:#D0EAA8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8EC860;
  --pk:#FF6B9D;--yl:#F59E0B;
}
*{box-sizing:border-box;margin:0;padding:0}
html,body{height:100%}
body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:var(--tx);line-height:1.55;min-height:100vh;display:flex;flex-direction:column}

/* ── TOPBAR ── */
.topbar{
  background:linear-gradient(135deg,#1A4D00 0%,#2D7A08 60%,#3A9A12 100%);
  color:#fff;padding:0 16px;display:flex;align-items:center;justify-content:space-between;
  height:58px;flex-shrink:0;box-shadow:0 2px 12px rgba(0,0,0,.25);
  position:sticky;top:0;z-index:100;
}
.topbar-left{display:flex;align-items:center;gap:10px}
.logo{font-size:22px;font-weight:900;letter-spacing:2px;color:#fff;text-decoration:none}
.logo span{color:var(--gn)}
.ctv-badge{background:rgba(198,241,53,.18);border:1px solid rgba(198,241,53,.4);color:var(--gn);font-size:10px;font-weight:800;letter-spacing:1px;padding:2px 8px;border-radius:20px}
.topbar-right{display:flex;align-items:center;gap:8px}
.topbar-name{font-size:12px;font-weight:600;color:rgba(255,255,255,.8);display:none}
.btn-out{color:rgba(255,255,255,.75);font-size:12px;text-decoration:none;border:1px solid rgba(255,255,255,.3);border-radius:50px;padding:5px 12px;transition:all .2s}
.btn-out:hover{background:rgba(255,255,255,.12);color:#fff}

/* ── BOTTOM TABBAR (mobile-first) ── */
.tabbar{
  position:fixed;bottom:0;left:0;right:0;z-index:90;
  background:#fff;border-top:1.5px solid var(--bd);
  display:flex;padding-bottom:env(safe-area-inset-bottom);
}
.tabbar a{
  flex:1;text-align:center;padding:10px 4px 8px;
  font-size:10px;font-weight:700;color:var(--tx3);text-decoration:none;
  transition:color .2s;position:relative;
}
.tabbar a .ic{display:block;font-size:22px;margin-bottom:2px;line-height:1}
.tabbar a.act{color:var(--gd)}
.tabbar a.act::before{
  content:'';position:absolute;top:0;left:20%;right:20%;height:3px;
  background:linear-gradient(90deg,var(--g),var(--gn));border-radius:0 0 6px 6px;
}
.tabbar a .badge-dot{
  position:absolute;top:8px;right:calc(50% - 14px);
  width:7px;height:7px;background:var(--pk);border-radius:50%;border:1.5px solid #fff;
}

/* ── CONTENT WRAP ── */
.page-body{flex:1;overflow-y:auto;padding:16px 14px 80px}

/* ── FLASH ── */
.flash{padding:12px 16px;border-radius:12px;font-size:13px;font-weight:600;margin-bottom:14px;display:flex;align-items:flex-start;gap:8px}
.flash.ok{background:#DCFCE7;color:#166534;border:1px solid #A7F3D0}
.flash.err{background:#FEE2E2;color:#991B1B;border:1px solid #FECACA}

/* ── CARDS ── */
.card{background:var(--card);border:1.5px solid var(--bd);border-radius:18px;padding:18px;margin-bottom:14px;box-shadow:0 2px 8px rgba(0,0,0,.04)}
.card-title{font-size:13px;font-weight:800;color:var(--gd);margin-bottom:14px;display:flex;align-items:center;gap:6px}
.card-title .ic{font-size:18px}

/* ── BUTTONS ── */
.btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:50px;padding:12px 22px;font-size:14px;font-weight:800;cursor:pointer;text-decoration:none;transition:all .2s;font-family:inherit}
.btn:hover{opacity:.9;transform:translateY(-1px)}
.btn.full{display:flex;width:100%}
.btn.ghost{background:none;color:var(--gd);border:2px solid var(--g)}
.btn.danger{background:linear-gradient(135deg,#EF4444,#B91C1C)}
.btn.sm{padding:8px 16px;font-size:12.5px}

/* ── FORM ── */
.field{margin-bottom:14px}
.field label{display:block;font-size:12px;font-weight:700;color:var(--tx2);margin-bottom:5px;letter-spacing:.2px}
.field .hint{font-size:11px;color:var(--tx3);margin-top:4px}
.field input,.field select,.field textarea{
  width:100%;border:1.5px solid var(--bd);border-radius:12px;
  padding:12px 14px;font-size:14px;font-family:inherit;
  background:var(--gll);color:var(--tx);transition:border .2s;
}
.field input:focus,.field select:focus,.field textarea:focus{outline:none;border-color:var(--g);background:#fff}
.field input:disabled{opacity:.55;cursor:not-allowed}
.field textarea{resize:none}

/* ── TABLES ── */
table{width:100%;border-collapse:collapse;font-size:13px}
th{padding:9px 10px;text-align:left;font-size:10.5px;font-weight:800;text-transform:uppercase;letter-spacing:.5px;color:var(--tx3);border-bottom:1.5px solid var(--bd)}
td{padding:11px 10px;border-bottom:1px solid #EAF6D8;vertical-align:middle;color:var(--tx)}

/* ── BADGES ── */
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700}
.badge.pending{background:#FEF3C7;color:#92400E}
.badge.approved{background:#DCFCE7;color:#166534}
.badge.rejected{background:#FEE2E2;color:#991B1B}

/* ── STAT GRID ── */
.stat-row{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px}
.stat-box{background:var(--card);border:1.5px solid var(--bd);border-radius:16px;padding:14px 12px;text-align:center}
.stat-box .num{font-size:21px;font-weight:900;line-height:1.1;margin-bottom:3px}
.stat-box .lbl{font-size:10.5px;color:var(--tx3);font-weight:600}

/* ── MISC ── */
.muted{color:var(--tx3);font-size:12px}
.bold{font-weight:800}
.divider{height:1px;background:var(--bd);margin:16px 0}
.empty-state{text-align:center;padding:40px 20px}
.empty-state .ei{font-size:48px;margin-bottom:12px}
.empty-state .et{font-size:16px;font-weight:800;color:var(--tx);margin-bottom:6px}
.empty-state .es{font-size:13px;color:var(--tx3)}
.ref-link-box{background:linear-gradient(135deg,var(--gll),#fff);border:1.5px solid var(--bd);border-radius:14px;padding:14px;display:flex;align-items:center;gap:10px;margin-bottom:14px}
.ref-link-box .url{flex:1;font-size:12px;color:var(--g);font-weight:700;word-break:break-all}
.copy-btn{flex-shrink:0;background:var(--g);color:#fff;border:none;border-radius:50px;padding:6px 14px;font-size:11px;font-weight:800;cursor:pointer;font-family:inherit}
.order-row{padding:12px 0;border-bottom:1px solid #EAF6D8}
.order-row:last-child{border:none}
.order-row .top{display:flex;justify-content:space-between;align-items:flex-start}
.order-row .code{font-size:13px;font-weight:800}
.order-row .meta{font-size:11.5px;color:var(--tx3);margin-top:2px}
.order-row .amount{text-align:right}
.order-row .amount .total{font-size:14px;font-weight:800;color:var(--tx)}
.order-row .amount .comm{font-size:12px;font-weight:800;color:var(--g)}
.withdraw-row{padding:12px 0;border-bottom:1px solid #EAF6D8}
.withdraw-row:last-child{border:none}

/* ── DESKTOP sidebar layout ── */
@media(min-width:860px){
  .tabbar{display:none}
  .page-body{padding:24px 28px 32px;max-width:860px;margin:0 auto;width:100%}
  .topbar-name{display:block}
  .body-wrap{display:flex;flex-direction:column;flex:1}
  .stat-row{grid-template-columns:repeat(4,1fr)}
}
@media(max-width:400px){
  .stat-row{grid-template-columns:1fr 1fr}
}
</style>
</head>
<body>

<div class="topbar">
  <div class="topbar-left">
    <a href="{{ route('home') }}" class="logo">DAL<span>I</span></a>
    <span class="ctv-badge">CTV</span>
  </div>
  <div class="topbar-right">
    @if(session('ctv_id'))
      <span class="topbar-name">{{ $ctv->name ?? '' }}</span>
      <a href="{{ route('ctv.logout') }}" class="btn-out">Đăng xuất</a>
    @endif
  </div>
</div>

<div class="page-body">
  @if(session('success'))<div class="flash ok">✓ {{ session('success') }}</div>@endif
  @if(session('error'))<div class="flash err">⚠ {{ session('error') }}</div>@endif
  @if($errors->any())<div class="flash err">⚠ {{ $errors->first() }}</div>@endif
  @yield('content')
</div>

@if(session('ctv_id'))
<nav class="tabbar">
  <a href="{{ route('ctv.dashboard') }}" class="{{ request()->routeIs('ctv.dashboard') ? 'act' : '' }}">
    <span class="ic">📊</span>Tổng quan
  </a>
  <a href="{{ route('ctv.order.create') }}" class="{{ request()->routeIs('ctv.order.create') ? 'act' : '' }}">
    <span class="ic">➕</span>Lên đơn
  </a>
  <a href="{{ route('ctv.orders') }}" class="{{ request()->routeIs('ctv.orders') ? 'act' : '' }}">
    <span class="ic">📦</span>Đơn hàng
  </a>
  <a href="{{ route('ctv.withdraw.page') }}" class="{{ request()->routeIs('ctv.withdraw.page') ? 'act' : '' }}">
    <span class="ic">💳</span>Rút tiền
  </a>
  <a href="{{ route('ctv.profile') }}" class="{{ request()->routeIs('ctv.profile') ? 'act' : '' }}">
    <span class="ic">👤</span>Hồ sơ
  </a>
</nav>
@endif

<script>
function copyText(t){ navigator.clipboard?.writeText(t).then(()=>{var el=event.target;var old=el.textContent;el.textContent='✓ Đã sao chép!';setTimeout(()=>{el.textContent=old},1800);}); }
</script>
</body>
</html>
