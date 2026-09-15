@php $duongApp = '/admin/3d/thay-ten/app/'; @endphp
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Thay tên mẫu in 3D | DALI Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--pk:#FF8FB1;--bd:#C8E89A;--bd2:#A8D870;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0;font-family:'Be Vietnam Pro',sans-serif}
html,body{height:100%}
/* Trang ngoài không cuộn: app trong iframe tự cuộn */
body{background:var(--bg);color:var(--tx);overflow:hidden}
.topbar{background:#fff;border-bottom:2px solid var(--gl);height:64px;padding:0 24px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-shrink:0}
.tb-bc{font-size:10px;color:var(--tx3)}.tb-bc b{color:var(--g)}
.tb-title{font-size:18px;font-weight:900;background:linear-gradient(90deg,#2D7A08,var(--g));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-top:2px}
.btn-sm{padding:6px 12px;font-size:11.5px;font-weight:700;border-radius:7px;border:1px solid var(--bd2);background:var(--gl);color:var(--gd);cursor:pointer;text-decoration:none;display:inline-block;white-space:nowrap}
.khung{display:flex;height:100vh;height:100dvh}
.cot{flex:1;display:flex;flex-direction:column;min-width:0;min-height:0;overflow:hidden}
.vung{flex:1;min-height:0;display:flex;padding:14px 18px 18px}
.vung iframe{flex:1;min-width:0;border:1.5px solid var(--bd);border-radius:14px;background:#fff;box-shadow:0 3px 18px rgba(58,122,10,.07);display:block}
/* Máy tính: sidebar cao đúng màn hình, menu dài thì cuộn trong sidebar */
@media(min-width:821px){#adminSidebar{height:100vh;height:100dvh;min-height:0!important}#adminSidebar nav{min-height:0}}
/* Điện thoại: sidebar là ngăn kéo (partials.sidebar), iframe gần tràn viền */
@media(max-width:820px){.vung{padding:8px}.vung iframe{border-radius:10px}.tb-title{font-size:16px}}
</style>
</head>
<body>
<div class="khung">
@include('admin.partials.sidebar')
<div class="cot">
  <div class="topbar">
    <div><div class="tb-bc">Admin › Xưởng in 3D › <b>Thay tên mẫu</b></div><div class="tb-title">Thay tên mẫu in 3D</div></div>
    <a class="btn-sm" href="{{ $duongApp }}" target="_blank" rel="noopener">Mở toàn màn hình ↗</a>
  </div>
  <div class="vung">
    <iframe src="{{ $duongApp }}" title="Ứng dụng thay tên mẫu in 3D"></iframe>
  </div>
</div>
</div>
</body>
</html>
