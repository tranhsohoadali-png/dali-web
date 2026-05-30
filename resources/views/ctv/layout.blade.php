<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>@yield('title','CTV') | DALI Cộng Tác Viên</title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--card:#fff;--bd:#D8EFB8}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:var(--tx);line-height:1.6;padding-bottom:74px}
.topbar{background:linear-gradient(175deg,#1C5200,#2D7A08,#3A9A12);color:#fff;padding:14px 18px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:40}
.topbar .logo{font-size:20px;font-weight:900;letter-spacing:2px;color:#fff;text-decoration:none}
.topbar .logo span{color:#C6F135}
.topbar .out{color:rgba(255,255,255,.85);font-size:12px;text-decoration:none;border:1px solid rgba(255,255,255,.35);border-radius:50px;padding:5px 12px}
.wrap{max-width:760px;margin:0 auto;padding:18px}
.flash{padding:12px 16px;border-radius:12px;font-size:13.5px;font-weight:600;margin-bottom:14px}
.flash.ok{background:#DCFCE7;color:#166534;border:1px solid #86EFAC}
.flash.err{background:#FEE2E2;color:#991B1B;border:1px solid #FCA5A5}
.card{background:var(--card);border:1.5px solid var(--bd);border-radius:16px;padding:18px;margin-bottom:16px}
.card h2{font-size:15px;font-weight:800;color:var(--gd);margin-bottom:12px}
.btn{display:inline-block;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:50px;padding:11px 22px;font-size:14px;font-weight:800;cursor:pointer;text-decoration:none;text-align:center}
.btn:hover{opacity:.92}
.btn.full{display:block;width:100%}
.btn.ghost{background:#fff;color:var(--gd);border:1.5px solid var(--g)}
.field{margin-bottom:13px}
.field label{display:block;font-size:12.5px;font-weight:700;color:var(--tx2);margin-bottom:5px}
.field input,.field select,.field textarea{width:100%;border:1.5px solid var(--bd);border-radius:10px;padding:11px 13px;font-size:14px;font-family:inherit;background:#FCFFF7}
.field input:focus,.field select:focus,.field textarea:focus{outline:none;border-color:var(--g)}
.tabbar{position:fixed;bottom:0;left:0;right:0;background:#fff;border-top:1.5px solid var(--bd);display:flex;z-index:50}
.tabbar a{flex:1;text-align:center;padding:9px 4px;font-size:10.5px;font-weight:700;color:#8FB860;text-decoration:none}
.tabbar a .ic{display:block;font-size:20px;margin-bottom:2px}
.tabbar a.act{color:var(--gd)}
table{width:100%;border-collapse:collapse;font-size:13px}
th,td{padding:9px 8px;text-align:left;border-bottom:1px solid #EAF6D8}
th{color:var(--tx2);font-weight:700;font-size:11.5px;text-transform:uppercase;letter-spacing:.3px}
.badge{display:inline-block;padding:2px 9px;border-radius:20px;font-size:11px;font-weight:700}
.muted{color:#9CC470;font-size:12px}
</style>
</head>
<body>
<div class="topbar">
  <a href="{{ route('ctv.dashboard') }}" class="logo">DAL<span>I</span> · CTV</a>
  @if(session('ctv_id'))<a href="{{ route('ctv.logout') }}" class="out">Đăng xuất</a>@endif
</div>
<div class="wrap">
  @if(session('success'))<div class="flash ok">✓ {{ session('success') }}</div>@endif
  @if(session('error'))<div class="flash err">⚠ {{ session('error') }}</div>@endif
  @if($errors->any())<div class="flash err">⚠ {{ $errors->first() }}</div>@endif
  @yield('content')
</div>
@if(session('ctv_id'))
<nav class="tabbar">
  <a href="{{ route('ctv.dashboard') }}" class="{{ request()->routeIs('ctv.dashboard')?'act':'' }}"><span class="ic">📊</span>Tổng quan</a>
  <a href="{{ route('ctv.order.create') }}" class="{{ request()->routeIs('ctv.order.create')?'act':'' }}"><span class="ic">➕</span>Lên đơn</a>
  <a href="{{ route('ctv.orders') }}" class="{{ request()->routeIs('ctv.orders')?'act':'' }}"><span class="ic">📦</span>Đơn hàng</a>
</nav>
@endif
</body>
</html>
