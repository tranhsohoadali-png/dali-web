<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Đại lý | DALI Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--pk:#FF8FB1;--bd:#C8E89A;--bd2:#A8D870;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0;font-family:'Be Vietnam Pro',sans-serif}
body{background:var(--bg);color:var(--tx)}
.topbar{background:#fff;border-bottom:2px solid var(--gl);height:64px;padding:0 24px;display:flex;align-items:center;justify-content:space-between}
.tb-bc{font-size:10px;color:var(--tx3)}.tb-bc b{color:var(--g)}
.tb-title{font-size:18px;font-weight:900;background:linear-gradient(90deg,#2D7A08,var(--g));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-top:2px}
.cnt{flex:1;overflow-y:auto;padding:22px 24px;max-width:1000px}
.alert-ok{background:var(--gll);border-left:3px solid var(--g);border-radius:9px;padding:12px 16px;margin-bottom:16px;font-size:13px;font-weight:600;color:var(--gd)}
.err{background:#FFF0F0;border-left:3px solid #EF4444;border-radius:9px;padding:12px 16px;margin-bottom:16px;font-size:13px;color:#B91C1C}
.card{background:#fff;border-radius:16px;border:1.5px solid var(--bd);box-shadow:0 3px 18px rgba(58,122,10,.07);margin-bottom:18px;overflow:hidden}
.card-top{height:4px;background:linear-gradient(90deg,#3A9A12,var(--g),#C6F135,#FF8FB1,#A78BFA)}
.card-h{padding:14px 20px;border-bottom:1px solid var(--gl);background:linear-gradient(135deg,var(--gll),#fff)}
.card-t{font-size:14px;font-weight:900;color:var(--char)}
.card-hint{font-size:12px;color:var(--tx3);margin-top:2px}
.pad{padding:16px 20px}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px 14px;align-items:end}
.f{display:flex;flex-direction:column;gap:5px}.f.rong{grid-column:1/-1}
.f>span{font-size:11px;font-weight:700;color:var(--tx2)}
.f input{width:100%;border:1.5px solid var(--bd);border-radius:9px;padding:10px 13px;font-size:13px;background:var(--gll);outline:none}
.f input:focus{border-color:var(--g);background:#fff}
.btn{padding:10px 20px;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;font-size:13px;font-weight:800;border:none;border-radius:9px;cursor:pointer;box-shadow:0 3px 12px rgba(107,191,31,.3)}
.btn-sm{padding:6px 12px;font-size:11.5px;font-weight:700;border-radius:7px;border:1px solid var(--bd2);background:var(--gl);color:var(--gd);cursor:pointer;text-decoration:none;display:inline-block}
.btn-warn{background:#FEF3C7;border-color:#FCD34D;color:#B45309}
.btn-del{background:#FFF0F0;border-color:#FECACA;color:#EF4444}
table{width:100%;border-collapse:collapse}
th{font-size:10px;font-weight:800;letter-spacing:1px;color:var(--tx3);text-transform:uppercase;padding:11px 16px;background:var(--gll);border-bottom:1.5px solid var(--bd);text-align:left}
td{padding:12px 16px;border-bottom:1px solid var(--gl);font-size:13px;vertical-align:middle}
.badge-on{background:var(--gl);color:var(--gd);font-size:11px;font-weight:800;padding:3px 9px;border-radius:20px}
.badge-off{background:#F3F4F6;color:#9CA3AF;font-size:11px;font-weight:800;padding:3px 9px;border-radius:20px}
.badge-sll{background:#FFF7E6;color:#B45309;border:1px solid #F1D084;font-size:11px;font-weight:800;padding:2px 8px;border-radius:20px;white-space:nowrap}
.tick-si{display:flex;gap:9px;align-items:flex-start;background:#FFF7E6;border:1px solid #F1D084;border-radius:10px;padding:11px 13px;margin-top:12px;cursor:pointer;font-size:13px}
.tick-si input{accent-color:#B45309;margin-top:2px;width:16px;height:16px;flex:none}
.tick-si b{font-weight:800;color:#B45309}
.tick-si small{display:block;color:var(--tx3);font-size:11.5px;margin-top:2px}
details.edit summary{list-style:none;cursor:pointer}details.edit summary::-webkit-details-marker{display:none}
.edit-box{margin-top:10px;padding:12px;background:var(--gll);border:1px solid var(--bd);border-radius:10px}
.acts{display:flex;gap:6px;flex-wrap:wrap;align-items:center}
.empty{text-align:center;padding:34px;color:var(--tx3);font-size:13px}
</style>
</head>
<body>
<div style="display:flex;min-height:100vh">
@include('admin.partials.sidebar')
<div style="flex:1;display:flex;flex-direction:column;overflow:hidden">
  <div class="topbar">
    <div><div class="tb-bc">Admin › Xưởng in 3D › <b>Đại lý</b></div><div class="tb-title">Tài khoản đại lý</div></div>
  </div>
  <div class="cnt">
    @if(session('ok'))<div class="alert-ok">✅ {{ session('ok') }}</div>@endif
    @if($errors->any())<div class="err"><b>Lỗi:</b><ul style="margin:4px 0 0 16px">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

    <div class="card">
      <div class="card-top"></div>
      <div class="card-h"><div class="card-t">➕ Thêm đại lý</div><div class="card-hint">Đại lý dùng SĐT + mật khẩu này để đăng nhập trên web 3D và xem giá sỉ.</div></div>
      <form method="POST" action="{{ route('admin.daily.store') }}" class="pad">
        @csrf
        <div class="grid">
          <label class="f"><span>Tên đại lý / cửa hàng</span><input name="ten" value="{{ old('ten') }}" placeholder="Cửa hàng Minh Anh" required></label>
          <label class="f"><span>Số điện thoại (đăng nhập)</span><input name="sdt" value="{{ old('sdt') }}" placeholder="0912345678" required></label>
          <label class="f"><span>Mật khẩu</span><input name="matkhau" value="{{ old('matkhau') }}" placeholder="tối thiểu 4 ký tự" required></label>
          <label class="f rong"><span>Ghi chú <i style="font-weight:500;color:var(--tx3)">— không bắt buộc</i></span><input name="ghi_chu" value="{{ old('ghi_chu') }}" placeholder="Khu vực, chiết khấu thoả thuận…"></label>
        </div>
        <label class="tick-si"><input type="checkbox" name="sll_luon" value="1"><span><b>Luôn nhận giá SLL</b><small>Đại lý này hưởng giá số-lượng-lớn cho MỌI đơn, không cần mua đủ số lượng.</small></span></label>
        <div style="margin-top:14px"><button class="btn" type="submit">Thêm đại lý</button></div>
      </form>
    </div>

    <div class="card">
      <div class="card-top"></div>
      <div class="card-h"><div class="card-t">🤝 Danh sách đại lý ({{ $items->count() }})</div></div>
      <div style="overflow-x:auto">
      <table>
        <thead><tr><th>Tên</th><th>SĐT</th><th>Ghi chú</th><th>Trạng thái</th><th>Đăng nhập</th><th>Thao tác</th></tr></thead>
        <tbody>
        @forelse($items as $d)
          <tr>
            <td style="font-weight:700">{{ $d->ten }}</td>
            <td>{{ $d->sdt }}</td>
            <td style="font-size:12px;color:var(--tx3);max-width:200px">{{ $d->ghi_chu ?: '—' }}</td>
            <td>@if($d->hien)<span class="badge-on">Hoạt động</span>@else<span class="badge-off">Đã khoá</span>@endif @if($d->sll_luon)<span class="badge-sll">⚡ Giá SLL</span>@endif</td>
            <td style="font-size:12px;color:var(--tx3)">{{ $d->dang_nhap_luc ? $d->dang_nhap_luc->format('d/m H:i') : 'chưa' }}</td>
            <td>
              <div class="acts">
                <details class="edit"><summary class="btn-sm">✏️ Sửa</summary></details>
                <form method="POST" action="{{ route('admin.daily.toggle',$d) }}" style="display:inline">@csrf
                  <button class="btn-sm btn-warn" type="submit">{{ $d->hien ? '🔒 Khoá' : '🔓 Mở' }}</button>
                </form>
                <form method="POST" action="{{ route('admin.daily.destroy',$d) }}" style="display:inline" onsubmit="return confirm('Xoá đại lý {{ $d->ten }}?')">@csrf @method('DELETE')
                  <button class="btn-sm btn-del" type="submit">🗑 Xoá</button>
                </form>
              </div>
              <details class="edit"><summary style="display:none"></summary>
                <div class="edit-box">
                  <form method="POST" action="{{ route('admin.daily.update',$d) }}">@csrf @method('PUT')
                    <div class="grid">
                      <label class="f"><span>Tên</span><input name="ten" value="{{ $d->ten }}" required></label>
                      <label class="f"><span>SĐT</span><input name="sdt" value="{{ $d->sdt }}" required></label>
                      <label class="f"><span>Mật khẩu mới <i style="font-weight:500;color:var(--tx3)">— để trống nếu giữ nguyên</i></span><input name="matkhau" placeholder="••••"></label>
                      <label class="f rong"><span>Ghi chú</span><input name="ghi_chu" value="{{ $d->ghi_chu }}"></label>
                    </div>
                    <label class="tick-si"><input type="checkbox" name="sll_luon" value="1" {{ $d->sll_luon ? 'checked' : '' }}><span><b>Luôn nhận giá SLL</b><small>Hưởng giá số-lượng-lớn cho MỌI đơn, không cần đủ số lượng.</small></span></label>
                    <div style="margin-top:12px"><button class="btn" type="submit">Lưu</button></div>
                  </form>
                </div>
              </details>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="empty">Chưa có đại lý nào. Thêm ở khung trên.</td></tr>
        @endforelse
        </tbody>
      </table>
      </div>
    </div>
  </div>
</div>
</div>
<script>
/* Nút "✏️ Sửa" mở khung sửa (details thứ 2 cùng hàng) */
document.querySelectorAll('td .acts details.edit > summary.btn-sm').forEach(function(sm){
  sm.addEventListener('click', function(e){
    e.preventDefault();
    var box = this.closest('td').querySelector('details.edit:nth-of-type(2)');
    if(box){ box.open = !box.open; }
  });
});
</script>
</body>
</html>
