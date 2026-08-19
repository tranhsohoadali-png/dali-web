<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Đơn 3D | DALI Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--gn:#C6F135;--pk:#FF8FB1;--bd:#C8E89A;--bd2:#A8D870;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:var(--tx)}
.topbar{background:#fff;border-bottom:2px solid var(--gl);height:64px;padding:0 24px;display:flex;align-items:center;justify-content:space-between}
.tb-bc{font-size:10px;color:var(--tx3)}.tb-bc b{color:var(--g)}
.tb-title{font-size:18px;font-weight:900;background:linear-gradient(90deg,#2D7A08,var(--g));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-top:2px}
.sakura{background:linear-gradient(90deg,#fff8fa,#f6ffe8,#fff);border-bottom:1px solid #F0EBF8;padding:6px 24px;display:flex;align-items:center;gap:5px}
.p{font-size:14px}.sak-t{font-size:10px;color:#B8D8A0;letter-spacing:2px;font-weight:700;margin-left:8px}
.cnt{flex:1;overflow-y:auto;padding:22px 24px}
.top-row{display:flex;align-items:center;gap:12px;margin-bottom:18px;flex-wrap:wrap}
.filter-select,.filter-input{background:#fff;border:1.5px solid var(--bd);border-radius:9px;padding:9px 14px;font-size:13px;color:var(--tx);outline:none;font-family:'Be Vietnam Pro',sans-serif}
.filter-select:focus,.filter-input:focus{border-color:var(--g)}
.btn-filter{padding:9px 18px;background:var(--gl);color:var(--gd);border:1px solid var(--bd2);border-radius:9px;font-size:13px;font-weight:700;cursor:pointer}
.alert-ok{background:var(--gll);border-left:3px solid var(--g);border-radius:9px;padding:12px 16px;margin-bottom:18px;font-size:13px;font-weight:600;color:var(--gd)}
.card{background:#fff;border-radius:16px;border:1.5px solid var(--bd);overflow:hidden;box-shadow:0 3px 18px rgba(58,122,10,.07)}
.card-top{height:4px;background:linear-gradient(90deg,#3A9A12,var(--g),var(--gn),#FF8FB1,#A78BFA)}
.card-head{padding:14px 22px;border-bottom:1px solid var(--gl);background:linear-gradient(135deg,var(--gll),#fff);font-size:14px;font-weight:900;color:var(--char)}
table{width:100%;border-collapse:collapse}
th{font-size:10px;font-weight:800;letter-spacing:1px;color:var(--tx3);text-transform:uppercase;padding:11px 14px;background:var(--gll);border-bottom:1.5px solid var(--bd);text-align:left}
td{padding:12px 14px;border-bottom:1px solid var(--gl);font-size:13px;color:var(--tx);vertical-align:middle}
tr:hover td{background:var(--gll)}
.badge{display:inline-block;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:800}
.b-cho_coc{background:#FEF3C7;color:#B45309}.b-dang_in{background:#DBEAFE;color:#1D4ED8}
.b-dong_goi{background:#E9D8FD;color:#6B21A8}.b-dang_giao{background:#CFFAFE;color:#0E7490}
.b-hoan_tat{background:var(--gl);color:var(--gd)}.b-huy{background:#F3F4F6;color:#9CA3AF}
.money{font-weight:900;color:var(--g)}
.btn-edit{display:inline-flex;align-items:center;padding:5px 11px;background:var(--gl);color:var(--gd);border:1px solid var(--bd2);border-radius:7px;font-size:11px;font-weight:700;text-decoration:none}
.btn-edit:hover{background:var(--g);color:#fff}
.pagination{display:flex;gap:6px;margin-top:18px;flex-wrap:wrap;justify-content:center}
.pagination a,.pagination span{padding:7px 13px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;border:1.5px solid var(--bd);color:var(--tx2);background:#fff}
</style>
</head>
<body>
<div style="display:flex;min-height:100vh">
@include('admin.partials.sidebar')
<div style="flex:1;display:flex;flex-direction:column;overflow:hidden">
  <div class="topbar">
    <div><div class="tb-bc">Admin › Xưởng in 3D › <b>Đơn hàng</b></div><div class="tb-title">Đơn hàng 3D</div></div>
  </div>
  <div class="sakura"><span class="p">📦</span><span class="p">🖨️</span><span class="sak-t">DALI · XƯỞNG IN 3D</span></div>
  <div class="cnt">
    @if(session('ok'))<div class="alert-ok">✅ {{ session('ok') }}</div>@endif

    <form method="GET" action="{{ route('admin.don3d.index') }}">
      <div class="top-row">
        <select name="tt" class="filter-select" onchange="this.form.submit()">
          <option value="">🏷️ Tất cả trạng thái</option>
          @foreach($tt as $k => $v)<option value="{{ $k }}" {{ request('tt')==$k?'selected':'' }}>{{ $v }}</option>@endforeach
        </select>
        <input type="text" name="search" class="filter-input" placeholder="🔍 Mã đơn / tên / SĐT" value="{{ request('search') }}" style="width:240px">
        <button type="submit" class="btn-filter">Lọc</button>
        @if(request()->hasAny(['tt','search']))<a href="{{ route('admin.don3d.index') }}" style="font-size:12px;color:var(--pk);text-decoration:none;font-weight:700">✕ Xoá lọc</a>@endif
      </div>
    </form>

    <div class="card">
      <div class="card-top"></div>
      <div class="card-head">{{ $orders->total() }} đơn @if($choCoc>0) · <span style="color:#B45309">{{ $choCoc }} chờ cọc</span>@endif</div>
      <table>
        <thead><tr><th>Mã đơn</th><th>Khách</th><th>Sản phẩm</th><th>Tổng</th><th>Trả ngay</th><th>Trạng thái</th><th>Ngày</th><th></th></tr></thead>
        <tbody>
        @forelse($orders as $o)
        <tr>
          <td style="font-weight:800">{{ $o->ma }}</td>
          <td><div style="font-weight:600">{{ $o->ten }}</div><div style="font-size:11px;color:var(--tx3)">{{ $o->sdt }} · {{ $o->tinh }}</div></td>
          <td style="max-width:260px;font-size:12px">{{ \Illuminate\Support\Str::limit($o->sp, 70) }}</td>
          <td class="money">{{ number_format((int)$o->tong,0,',','.') }}đ</td>
          <td>@if($o->tra_ngay>0)<span style="font-weight:700;color:#B45309">{{ number_format((int)$o->tra_ngay,0,',','.') }}đ</span>@else<span style="font-size:11px;color:var(--tx3)">COD</span>@endif</td>
          <td><span class="badge b-{{ $o->tt }}">{{ $tt[$o->tt] ?? $o->tt }}</span></td>
          <td style="font-size:11px;color:var(--tx3)">{{ optional($o->created_at)->format('d/m H:i') }}</td>
          <td><a href="{{ route('admin.don3d.show', $o) }}" class="btn-edit">Xem →</a></td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;padding:44px;color:var(--tx3)">
          Chưa có đơn 3D nào. Đơn từ web 3d.tranhdali.vn sẽ tự về đây sau khi chuyển sang Laravel.
        </td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
    <div class="pagination">{{ $orders->links() }}</div>
  </div>
</div>
</div>
</body>
</html>
