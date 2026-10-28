<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $tieuDe }} | Cổng seller POD | DALI Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--bd:#C8E89A;--bd2:#A8D870;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0;font-family:'Be Vietnam Pro',sans-serif}
body{background:var(--bg);color:var(--tx)}
.topbar{background:#fff;border-bottom:2px solid var(--gl);min-height:64px;padding:8px 24px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
.tb-bc{font-size:10px;color:var(--tx3)}.tb-bc b{color:var(--g)}
.tb-title{font-size:18px;font-weight:900;color:var(--gd);margin-top:2px}
.podnav{display:flex;gap:6px;flex-wrap:wrap}
.podnav a{padding:7px 12px;border-radius:8px;font-size:12px;font-weight:700;text-decoration:none;color:var(--gd);border:1px solid var(--bd);background:var(--gll)}
.podnav a.on{background:var(--g);color:#fff;border-color:var(--g)}
.cnt{flex:1;overflow-y:auto;padding:22px 24px;max-width:1150px}
.alert-ok{background:var(--gll);border-left:3px solid var(--g);border-radius:9px;padding:12px 16px;margin-bottom:16px;font-size:13px;font-weight:600;color:var(--gd)}
.err{background:#FFF0F0;border-left:3px solid #EF4444;border-radius:9px;padding:12px 16px;margin-bottom:16px;font-size:13px;color:#B91C1C}
.card{background:#fff;border-radius:16px;border:1.5px solid var(--bd);box-shadow:0 3px 18px rgba(58,122,10,.07);margin-bottom:18px;overflow:hidden}
.card-h{padding:14px 20px;border-bottom:1px solid var(--gl);background:linear-gradient(135deg,var(--gll),#fff)}
.card-t{font-size:14px;font-weight:900;color:var(--char)}
.card-hint{font-size:12px;color:var(--tx3);margin-top:2px}
.pad{padding:16px 20px}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px 14px;align-items:end}
.f{display:flex;flex-direction:column;gap:5px}.f.rong{grid-column:1/-1}
.f>span{font-size:11px;font-weight:700;color:var(--tx2)}
.f input,.f select,.f textarea,td input,td select{width:100%;border:1.5px solid var(--bd);border-radius:9px;padding:9px 12px;font-size:13px;background:var(--gll);outline:none}
.f input:focus,.f select:focus,.f textarea:focus{border-color:var(--g);background:#fff}
.btn{padding:10px 20px;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;font-size:13px;font-weight:800;border:none;border-radius:9px;cursor:pointer;text-decoration:none;display:inline-block}
.btn-sm{padding:6px 12px;font-size:11.5px;font-weight:700;border-radius:7px;border:1px solid var(--bd2);background:var(--gl);color:var(--gd);cursor:pointer;text-decoration:none;display:inline-block}
.btn-warn{background:#FEF3C7;border-color:#FCD34D;color:#B45309}
.btn-del{background:#FFF0F0;border-color:#FECACA;color:#EF4444}
table{width:100%;border-collapse:collapse}
th{font-size:10px;font-weight:800;letter-spacing:1px;color:var(--tx3);text-transform:uppercase;padding:11px 14px;background:var(--gll);border-bottom:1.5px solid var(--bd);text-align:left}
td{padding:11px 14px;border-bottom:1px solid var(--gl);font-size:13px;vertical-align:top}
.badge{font-size:11px;font-weight:800;padding:3px 9px;border-radius:20px;white-space:nowrap;display:inline-block}
.b-chung{background:#E0F2FE;color:#0369A1}.b-rieng{background:#FCE7F3;color:#BE185D}
.b-on{background:var(--gl);color:var(--gd)}.b-off{background:#F3F4F6;color:#6B7280}
.b-cho_nap{background:#FEF3C7;color:#B45309}.b-cho_in{background:#E0F2FE;color:#0369A1}.b-dang_in{background:#EDE9FE;color:#6D28D9}.b-da_giao{background:var(--gl);color:var(--gd)}.b-da_huy{background:#F3F4F6;color:#6B7280}
.muted{color:#6B7280;font-size:12px}
.num{text-align:right;white-space:nowrap}
.acts{display:flex;gap:6px;flex-wrap:wrap;align-items:center}
details.edit summary{list-style:none;cursor:pointer}details.edit summary::-webkit-details-marker{display:none}
.edit-box{margin-top:10px;padding:12px;background:var(--gll);border:1px solid var(--bd);border-radius:10px;min-width:260px}
.empty{text-align:center;padding:34px;color:var(--tx3);font-size:13px}
.canh{background:#FFF7E6;border:1px solid #F1D084;border-radius:10px;padding:12px 14px;font-size:13px;color:#92400E}
.thumb{width:56px;height:56px;object-fit:cover;border-radius:8px;border:1px solid var(--bd);background:#fff}
</style>
</head>
<body>
<div style="display:flex;min-height:100vh">
@include('admin.partials.sidebar')
<div style="flex:1;display:flex;flex-direction:column;overflow:hidden">
  <div class="topbar">
    <div><div class="tb-bc">Admin › Cổng seller POD › <b>{{ $tieuDe }}</b></div><div class="tb-title">{{ $tieuDe }}</div></div>
    <nav class="podnav">
      <a href="{{ route('admin.pod.don.index') }}" class="{{ request()->routeIs('admin.pod.don*') ? 'on' : '' }}">Đơn POD</a>
      <a href="{{ route('admin.pod.mau.index') }}" class="{{ request()->routeIs('admin.pod.mau*') ? 'on' : '' }}">Mẫu POD</a>
      <a href="{{ route('admin.pod.seller.index') }}" class="{{ request()->routeIs('admin.pod.seller*') ? 'on' : '' }}">Seller & ví</a>
      <a href="{{ route('admin.pod.thoin.index') }}" class="{{ request()->routeIs('admin.pod.thoin*') ? 'on' : '' }}">Thợ in</a>
      <a href="{{ route('admin.pod.nhatky') }}" class="{{ request()->routeIs('admin.pod.nhatky') ? 'on' : '' }}">Nhật ký mẫu riêng</a>
      <a href="{{ route('pod.cong') }}" target="_blank">Cổng seller ↗</a>
    </nav>
  </div>
  <div class="cnt">
    @if(session('ok'))<div class="alert-ok">{{ session('ok') }}</div>@endif
    @if($errors->any())<div class="err"><b>Lỗi:</b><ul style="margin:4px 0 0 16px">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
