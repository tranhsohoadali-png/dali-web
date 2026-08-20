<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Danh mục 3D | DALI Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--bd:#C8E89A;--bd2:#A8D870;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:var(--tx)}
.topbar{background:#fff;border-bottom:2px solid var(--gl);height:64px;padding:0 24px;display:flex;align-items:center;justify-content:space-between}
.tb-bc{font-size:10px;color:var(--tx3)}.tb-bc b{color:var(--g)}
.tb-title{font-size:18px;font-weight:900;background:linear-gradient(90deg,#2D7A08,var(--g));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-top:2px}
.cnt{flex:1;overflow-y:auto;padding:22px 24px}
.wrap{max-width:960px;margin:0 auto}
.ok{background:var(--gll);border:1px solid var(--bd);border-left:3px solid var(--g);border-radius:9px;padding:11px 15px;margin-bottom:16px;font-size:13px;color:var(--gd);font-weight:600}
.err{background:#FFF0F0;border-left:3px solid #EF4444;border-radius:9px;padding:12px 16px;margin-bottom:16px;font-size:13px;color:#B91C1C}
.hint{font-size:12.5px;color:var(--tx3);margin-bottom:18px}
.nhom{background:#fff;border-radius:16px;border:1.5px solid var(--bd);box-shadow:0 3px 18px rgba(58,122,10,.07);padding:16px 18px;margin-bottom:16px}
.nhom-hd{display:flex;gap:8px;align-items:center;flex-wrap:wrap;padding-bottom:12px;border-bottom:1px dashed var(--bd)}
.nhom-hd .stt{font-family:'Be Vietnam Pro';font-size:22px;font-weight:900;color:var(--bd2);flex:none;width:30px;text-align:center}
.muc-list{margin:12px 0 4px;display:flex;flex-direction:column;gap:8px}
.row{display:flex;gap:7px;align-items:center;flex-wrap:wrap}
.row.muc{background:var(--gll);border:1px solid var(--bd);border-radius:11px;padding:8px 10px}
.in{border:1.5px solid var(--bd);border-radius:9px;padding:8px 11px;font-size:13px;background:#fff;color:var(--tx);font-family:inherit;outline:none;transition:border .2s}
.in:focus{border-color:var(--g)}
.in.ten{flex:1;min-width:120px;font-weight:600}
.in.stt{width:58px;text-align:center}
.in.icon{width:46px;text-align:center;font-size:16px}
.in.mota{flex:1;min-width:150px}
.badge{font-size:11px;font-weight:700;color:var(--tx2);background:#fff;border:1px solid var(--bd);border-radius:20px;padding:3px 9px;flex:none}
.sel{border:1.5px solid var(--bd);border-radius:9px;padding:8px 10px;font-size:12.5px;background:#fff;color:var(--tx);outline:none}
.tick{display:inline-flex;gap:5px;align-items:center;font-size:12px;color:var(--tx2);font-weight:600}
.tick input{accent-color:var(--g);width:15px;height:15px}
.bt{border:none;border-radius:9px;padding:8px 13px;font-size:12.5px;font-weight:700;cursor:pointer;white-space:nowrap}
.bt-luu{background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff}
.bt-xoa{background:#fff;color:#C0392B;border:1px solid #F0C0C0}
.bt-them{background:var(--gl);color:var(--gd);border:1px dashed var(--bd2)}
.addmuc{border-top:1px dashed var(--bd);margin-top:10px;padding-top:11px}
.lbl{font-size:10px;font-weight:700;color:var(--tx3);letter-spacing:.3px;text-transform:uppercase}
form{display:contents}
.nhom-add{background:var(--gll);border:1.5px dashed var(--bd2);border-radius:16px;padding:16px 18px}
.nhom-add h3{font-size:14px;font-weight:800;color:var(--gd);margin-bottom:10px}
</style>
</head>
<body>
<div style="display:flex;min-height:100vh">
@include('admin.partials.sidebar')
<div style="flex:1;display:flex;flex-direction:column;overflow:hidden">
  <div class="topbar">
    <div><div class="tb-bc">Admin › Xưởng in 3D › <b>Danh mục</b></div>
      <div class="tb-title">Danh mục 3D</div></div>
    <a href="{{ route('admin.sp3d.index') }}" style="font-size:12px;color:var(--tx2);font-weight:700;text-decoration:none">← Sản phẩm 3D</a>
  </div>
  <div class="cnt"><div class="wrap">

  @if(session('ok'))<div class="ok">✅ {{ session('ok') }}</div>@endif
  @if($errors->any())<div class="err"><b>Lỗi:</b> {{ $errors->first() }}</div>@endif
  <div class="hint">Cây danh mục: <b>Nhóm</b> (cấp lớn) → <b>Danh mục</b> (cấp con). Mỗi sản phẩm gắn vào 1 danh mục. Số nhỏ đứng trước. Sửa xong bấm <b>Lưu</b> từng dòng.</div>

  @php $nhomOpts = $nhoms->pluck('ten','id'); @endphp

  @foreach($nhoms as $nhom)
    <div class="nhom">
      <div class="nhom-hd">
        <span class="stt">{{ $loop->iteration }}</span>
        <form method="POST" action="{{ route('admin.danhmuc3d.nhom.update', $nhom) }}">@csrf @method('PUT')
          <input class="in ten" name="ten" value="{{ $nhom->ten }}" placeholder="Tên nhóm" required>
          <input class="in mota" name="mo_ta" value="{{ $nhom->mo_ta }}" placeholder="Mô tả ngắn (không bắt buộc)">
          <input class="in stt" name="thu_tu" type="number" value="{{ $nhom->thu_tu }}" title="Thứ tự">
          <label class="tick"><input type="checkbox" name="hien" value="1" {{ $nhom->hien?'checked':'' }}> Hiện</label>
          <button class="bt bt-luu" type="submit">Lưu nhóm</button>
        </form>
        <form method="POST" action="{{ route('admin.danhmuc3d.nhom.destroy', $nhom) }}" onsubmit="return confirm('Xoá nhóm “{{ $nhom->ten }}” và toàn bộ danh mục con? Sản phẩm sẽ về “chưa phân loại”.')">@csrf @method('DELETE')
          <button class="bt bt-xoa" type="submit">Xoá</button>
        </form>
      </div>

      <div class="muc-list">
        @forelse($nhom->danhMuc as $muc)
          <div class="row muc">
            <form method="POST" action="{{ route('admin.danhmuc3d.muc.update', $muc) }}">@csrf @method('PUT')
              <input class="in icon" name="icon" value="{{ $muc->icon }}" placeholder="🏷️" title="Icon (emoji)">
              <input class="in ten" name="ten" value="{{ $muc->ten }}" placeholder="Tên danh mục" required>
              <span class="badge">{{ $counts[$muc->id] ?? 0 }} SP</span>
              <select class="sel" name="nhom_id" title="Chuyển sang nhóm">
                @foreach($nhomOpts as $id=>$ten)<option value="{{ $id }}" {{ $muc->nhom_id==$id?'selected':'' }}>{{ $ten }}</option>@endforeach
              </select>
              <input class="in stt" name="thu_tu" type="number" value="{{ $muc->thu_tu }}" title="Thứ tự">
              <label class="tick"><input type="checkbox" name="hien" value="1" {{ $muc->hien?'checked':'' }}> Hiện</label>
              <button class="bt bt-luu" type="submit">Lưu</button>
            </form>
            <form method="POST" action="{{ route('admin.danhmuc3d.muc.destroy', $muc) }}" onsubmit="return confirm('Xoá danh mục “{{ $muc->ten }}”? Sản phẩm trong đó về “chưa phân loại”.')">@csrf @method('DELETE')
              <button class="bt bt-xoa" type="submit">✕</button>
            </form>
          </div>
        @empty
          <div style="font-size:12.5px;color:var(--tx3);padding:4px 2px">Chưa có danh mục nào — thêm bên dưới.</div>
        @endforelse
      </div>

      <div class="addmuc row">
        <form method="POST" action="{{ route('admin.danhmuc3d.muc.store') }}">@csrf
          <input type="hidden" name="nhom_id" value="{{ $nhom->id }}">
          <span class="lbl">Thêm vào “{{ $nhom->ten }}”:</span>
          <input class="in icon" name="icon" placeholder="🏷️">
          <input class="in ten" name="ten" placeholder="Tên danh mục mới" required>
          <input class="in stt" name="thu_tu" type="number" value="0" title="Thứ tự">
          <button class="bt bt-them" type="submit">＋ Thêm danh mục</button>
        </form>
      </div>
    </div>
  @endforeach

  <div class="nhom-add">
    <h3>＋ Thêm nhóm mới</h3>
    <div class="row">
      <form method="POST" action="{{ route('admin.danhmuc3d.nhom.store') }}">@csrf
        <input class="in ten" name="ten" placeholder="Tên nhóm (vd: Trang trí nhà)" required>
        <input class="in mota" name="mo_ta" placeholder="Mô tả ngắn (không bắt buộc)">
        <input class="in stt" name="thu_tu" type="number" value="0" title="Thứ tự">
        <button class="bt bt-them" type="submit">＋ Thêm nhóm</button>
      </form>
    </div>
  </div>

  </div></div>
</div>
</div>
</body>
</html>
