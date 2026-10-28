<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>{{ $tieuDe }} | Thợ in DALI</title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--bd:#D5ECB4;--tx:#1C3A0A;--mu:#6B7280}
*{box-sizing:border-box;margin:0;padding:0;font-family:'Be Vietnam Pro',system-ui,sans-serif}
body{background:#F7FBF2;color:var(--tx);font-size:15px;line-height:1.45}
header{background:#fff;border-bottom:1px solid var(--bd);padding:10px 16px;display:flex;justify-content:space-between;align-items:center;gap:10px}
.logo{font-weight:800;color:var(--gd)}.logo small{color:var(--mu);font-weight:600;font-size:12px;margin-left:6px}
main{max-width:900px;margin:0 auto;padding:16px}
h2{font-size:18px;margin:6px 0 10px}
.box{background:#fff;border:1px solid var(--bd);border-radius:12px;padding:14px;margin-bottom:12px}
.btn{background:var(--g);color:#fff;border:none;border-radius:10px;padding:11px 18px;font-weight:700;font-size:15px;cursor:pointer;text-decoration:none;display:inline-block}
.btn2{background:#fff;color:var(--gd);border:1px solid var(--bd);border-radius:10px;padding:8px 14px;font-weight:600;cursor:pointer;text-decoration:none;display:inline-block}
.msg{border-radius:10px;padding:10px 14px;margin-bottom:12px;font-size:14px}
.ok{background:var(--gll);color:var(--gd);border:1px solid var(--bd)}.err{background:#FEF2F2;color:#B91C1C;border:1px solid #FECACA}
.st{font-size:12px;font-weight:700;border-radius:20px;padding:3px 10px;white-space:nowrap}
.st-cho_in{background:#E0F2FE;color:#0369A1}.st-dang_in{background:#EDE9FE;color:#6D28D9}.st-da_giao{background:var(--gl);color:var(--gd)}
.mu{color:var(--mu);font-size:13px}
input{border:1px solid var(--bd);border-radius:9px;padding:10px 12px;font-size:15px;width:100%}
label{display:flex;flex-direction:column;gap:5px;font-size:13px;font-weight:600;color:var(--mu)}
.dong{padding:8px 0;border-bottom:1px solid var(--gll)}
.sl{font-weight:800;font-size:17px}
</style>
</head>
<body>
<header>
  <div class="logo">DALI<small>Thợ in · đơn POD</small></div>
  @isset($tho)
  <form method="POST" action="{{ route('tho-in.dang-xuat') }}" style="display:flex;gap:10px;align-items:center">@csrf<span class="mu">{{ $tho->ten }}</span><button class="btn2">Đăng xuất</button></form>
  @endisset
</header>
<main>
@if(session('ok'))<div class="msg ok">{{ session('ok') }}</div>@endif
@if($errors->any())<div class="msg err">{{ $errors->first() }}</div>@endif
