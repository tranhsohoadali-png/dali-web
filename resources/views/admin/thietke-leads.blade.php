<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Khách thiết kế (SĐT) | DALI Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--bd:#C8E89A;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0}body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:var(--tx)}
.layout{display:flex;min-height:100vh}.main{flex:1;display:flex;flex-direction:column;min-width:0}
.tb{background:#fff;border-bottom:2px solid var(--gl);min-height:64px;padding:10px 24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px}
.tb-bc{font-size:10px;color:var(--tx3)}.tb-bc a{color:var(--g);text-decoration:none;font-weight:600}
.tb-title{font-size:18px;font-weight:900;background:linear-gradient(90deg,#2D7A08,var(--g));-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.cnt{flex:1;overflow-y:auto;padding:22px 24px}
.card{background:#fff;border-radius:16px;border:1px solid var(--bd);max-width:1100px;overflow:hidden}
.card-head{padding:14px 22px;border-bottom:1px solid var(--gl);background:var(--gll);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px}
.card-title{font-size:14px;font-weight:900;color:var(--char)}
.hint{font-size:11px;color:var(--tx3)}
.btn{display:inline-flex;align-items:center;gap:6px;background:#fff;border:1.5px solid var(--bd);color:var(--gd);font-size:12px;font-weight:800;padding:8px 14px;border-radius:9px;cursor:pointer;text-decoration:none}
.btn:hover{background:var(--gll)}
.btn-green{background:var(--g);border-color:var(--g);color:#fff}
table{width:100%;border-collapse:collapse}
th,td{padding:12px 14px;text-align:left;border-bottom:1px solid #EEF5E5;font-size:13px}
thead th{font-size:12px;font-weight:900;color:#0F172A;background:#fff;white-space:nowrap}
.phone{font-size:15px;font-weight:900;color:#0F172A;white-space:nowrap}
.time{font-size:11px;color:#94A3B8;font-weight:600;white-space:nowrap}
.lnk{display:inline-block;font-size:11px;font-weight:800;color:var(--gd);background:var(--gll);border:1px solid var(--bd);border-radius:7px;padding:3px 8px;text-decoration:none;margin:1px}
.lnk:hover{background:var(--gl)}
.search{display:flex;gap:6px}
.search input{border:1.5px solid var(--bd);border-radius:9px;padding:8px 12px;font-size:13px;font-family:inherit;outline:none;width:170px}
.pagi{padding:14px 22px}.pagi a,.pagi span{margin-right:6px;font-size:13px}
.empty{padding:40px;text-align:center;color:var(--tx3);font-weight:700}
.wrap-scroll{overflow-x:auto}
@media(max-width:768px){.cnt{padding:12px}}
</style>
</head>
<body>
<div class="layout">
  @include('admin.partials.sidebar')
  <div class="main">
    <div class="tb">
      <div>
        <div class="tb-bc"><a href="{{ route('admin.dashboard') }}">Admin</a> › <b>Khách thiết kế</b></div>
        <div class="tb-title">Khách lưu bản thiết kế theo SĐT</div>
      </div>
      <div style="display:flex;gap:8px;flex-wrap:wrap">
        <form class="search" method="get">
          <input name="q" value="{{ $q }}" placeholder="Tìm theo SĐT…">
          <button class="btn" type="submit">🔍 Tìm</button>
        </form>
        <a class="btn btn-green" href="{{ route('admin.thietke.leads', array_filter(['q' => $q, 'xuat' => 'csv'])) }}">⬇️ Xuất file CSV</a>
      </div>
    </div>
    <div class="cnt">
      <div class="card">
        <div class="card-head">
          <div class="card-title">📱 Danh sách SĐT khách ({{ $leads->total() }})</div>
          <div class="hint">Khách nhập SĐT trước khi nhận bản thiết kế trên trang /thiet-ke — mỗi dòng kèm link 3 ảnh.</div>
        </div>
        <div class="wrap-scroll">
        <table>
          <thead><tr><th>#</th><th>SĐT</th><th>Thời gian</th><th>Ảnh của khách</th><th>Mã máy</th></tr></thead>
          <tbody>
            @forelse($leads as $l)
            <tr>
              <td>{{ $l->id }}</td>
              <td class="phone">{{ $l->phone }}</td>
              <td class="time">{{ $l->created_at->format('d/m/Y H:i') }}</td>
              <td>
                @if($l->original_url)<a class="lnk" href="{{ $l->original_url }}" target="_blank">📷 Gốc</a>@endif
                @if($l->enhanced_url)<a class="lnk" href="{{ $l->enhanced_url }}" target="_blank">✨ Ảnh AI</a>@endif
                @if($l->result_url)<a class="lnk" href="{{ $l->result_url }}" target="_blank">🎨 Bản đồ màu</a>@endif
              </td>
              <td class="time">{{ \Illuminate\Support\Str::limit($l->device_id, 14) }}</td>
            </tr>
            @empty
            <tr><td colspan="5"><div class="empty">Chưa có khách nào lưu SĐT.</div></td></tr>
            @endforelse
          </tbody>
        </table>
        </div>
        <div class="pagi">{{ $leads->links() }}</div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
