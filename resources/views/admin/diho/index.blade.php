<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Đơn đi hộ | DALI Admin</title>
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
.b-moi{background:#FEF3C7;color:#B45309}.b-da_in{background:#DBEAFE;color:#1D4ED8}
.b-da_gui{background:var(--gl);color:var(--gd)}.b-huy{background:#F3F4F6;color:#9CA3AF}
.money{font-weight:900;color:var(--g)}
.btn-edit{display:inline-flex;align-items:center;padding:5px 11px;background:var(--gl);color:var(--gd);border:1px solid var(--bd2);border-radius:7px;font-size:11px;font-weight:700;text-decoration:none}
.btn-edit:hover{background:var(--g);color:#fff}
.btn-dl{display:inline-flex;align-items:center;padding:5px 11px;background:#EEF2FF;color:#3730A3;border:1px solid #C7D2FE;border-radius:7px;font-size:11px;font-weight:700;text-decoration:none;margin-left:6px}
.pagination{display:flex;gap:6px;margin-top:18px;flex-wrap:wrap;justify-content:center}
.pagination a,.pagination span{padding:7px 13px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;border:1.5px solid var(--bd);color:var(--tx2);background:#fff}
</style>
</head>
<body>
<div style="display:flex;min-height:100vh">
@include('admin.partials.sidebar')
<div style="flex:1;display:flex;flex-direction:column;overflow:hidden">
  <div class="topbar">
    <div><div class="tb-bc">Admin › Xưởng in 3D › <b>Đơn đi hộ</b></div><div class="tb-title">Đơn đi hộ (đại lý)</div></div>
  </div>
  <div class="sakura"><span class="p">🚚</span><span class="p">🏷️</span><span class="sak-t">DALI · ĐI ĐƠN HỘ TMĐT</span></div>
  <div class="cnt">
    @if(session('ok'))<div class="alert-ok">✅ {{ session('ok') }}</div>@endif

    <form method="GET" action="{{ route('admin.diho.index') }}">
      <div class="top-row">
        <select name="tt" class="filter-select" onchange="this.form.submit()">
          <option value="">🏷️ Tất cả trạng thái</option>
          @foreach($tt as $k => $v)<option value="{{ $k }}" {{ request('tt')==$k?'selected':'' }}>{{ $v }}</option>@endforeach
        </select>
        <select name="tt_tt" class="filter-select" onchange="this.form.submit()">
          <option value="">💰 Tất cả thanh toán</option>
          <option value="chua" {{ request('tt_tt')=='chua'?'selected':'' }}>Chưa thu tiền</option>
          <option value="da" {{ request('tt_tt')=='da'?'selected':'' }}>Đã thu tiền</option>
        </select>
        <input type="text" name="search" class="filter-input" placeholder="🔍 Mã đơn / tên đại lý / SĐT" value="{{ request('search') }}" style="width:250px">
        <button type="submit" class="btn-filter">Lọc</button>
        @if(request()->hasAny(['tt','search']))<a href="{{ route('admin.diho.index') }}" style="font-size:12px;color:var(--pk);text-decoration:none;font-weight:700">✕ Xoá lọc</a>@endif
      </div>
    </form>

    <div class="card">
      <div class="card-top"></div>
      <div class="card-head">{{ $orders->total() }} đơn @if($moi>0) · <span style="color:#B45309">{{ $moi }} mới</span>@endif</div>
      <table>
        <thead><tr><th>Mã đơn</th><th>Đại lý</th><th>Sản phẩm</th><th>SL</th><th>Tổng sỉ</th><th>Thu tiền</th><th>Nhãn VC</th><th>Trạng thái</th><th>Ngày</th><th></th></tr></thead>
        <tbody>
        @forelse($orders as $o)
        <tr>
          <td style="font-weight:800">{{ $o->ma }}</td>
          <td><div style="font-weight:600">{{ $o->dai_ly_ten }}</div><div style="font-size:11px;color:var(--tx3)">{{ $o->dai_ly_sdt }}</div></td>
          <td style="max-width:260px;font-size:12px">{{ \Illuminate\Support\Str::limit(collect($o->chi_tiet ?: [])->map(fn($l)=>($l['ten']??'').' ×'.($l['qty']??0))->implode('; '), 70) }}</td>
          <td style="font-weight:700">{{ (int)$o->so_luong }}</td>
          <td class="money">{{ number_format((int)$o->tong_si,0,',','.') }}đ</td>
          <td>@if($o->da_thanh_toan)<span class="badge" style="background:#E8F9D0;color:#3E7A0A">✓ Đã thu</span>@else<span class="badge" style="background:#FEE2E2;color:#B91C1C">Chưa thu</span>@endif</td>
          <td>@if($o->nhan_vc_path)<a href="{{ route('admin.diho.nhan', $o) }}" class="btn-dl">⬇ Tải nhãn</a>@else<span style="font-size:11px;color:var(--tx3)">—</span>@endif</td>
          <td><span class="badge b-{{ $o->tt }}">{{ $tt[$o->tt] ?? $o->tt }}</span>@if($o->dai_ly_xac_nhan)<div style="font-size:10px;color:#3E7A0A;font-weight:800;margin-top:3px">✓ ĐL xác nhận VC</div>@endif</td>
          <td style="font-size:11px;color:var(--tx3)">{{ optional($o->created_at)->format('d/m H:i') }}</td>
          <td><a href="{{ route('admin.diho.show', $o) }}" class="btn-edit">Xem →</a></td>
        </tr>
        @empty
        <tr><td colspan="10" style="text-align:center;padding:44px;color:var(--tx3)">
          Chưa có đơn đi hộ nào. Đại lý gửi đơn từ web 3d.tranhdali.vn (mục 🚚 Đi đơn hộ) sẽ về đây.
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
