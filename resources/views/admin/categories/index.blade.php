<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Danh Mục | DALI Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--g:#6BBF1F;--gb:#8ED63A;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--gn:#C6F135;--pk:#FF8FB1;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--bd:#C8E89A;--bg:#F2FDE8;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:var(--tx)}
.sb{width:232px;flex-shrink:0;background:linear-gradient(175deg,#1C5200,#2D7A08,#3A9A12);display:flex;flex-direction:column}
.sb-logo{padding:20px 18px 14px;border-bottom:1px solid rgba(255,255,255,.12)}
.sb-logo img{height:38px;filter:brightness(0) invert(1)}
.sb-tag{font-size:9px;color:rgba(255,255,255,.4);letter-spacing:2.5px;margin-top:6px}
.sb-nav{flex:1;padding:12px 8px;display:flex;flex-direction:column;gap:1px}
.sb-sec{font-size:9px;letter-spacing:2.5px;color:rgba(255,255,255,.25);padding:8px 10px 5px}
.sb-a{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;color:rgba(255,255,255,.65);font-size:13px;font-weight:500;text-decoration:none;border:1px solid transparent;transition:all .2s}
.sb-a:hover{background:rgba(255,255,255,.12);color:#fff}
.sb-a.on{background:rgba(255,255,255,.18);color:#fff;font-weight:700;border-color:rgba(255,255,255,.28)}
.sb-foot{padding:14px;border-top:1px solid rgba(255,255,255,.12)}
.av{display:flex;align-items:center;gap:9px}
.av-r{width:34px;height:34px;border-radius:50%;background:rgba(255,255,255,.18);border:2px solid rgba(255,255,255,.35);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;color:#fff}
.av-n{font-size:12px;font-weight:700;color:#fff}
.av-s{font-size:10px;color:rgba(255,255,255,.4)}
.logout{display:block;margin-top:8px;font-size:10px;color:rgba(255,255,255,.3);text-decoration:none;letter-spacing:1px}
.topbar{background:#fff;border-bottom:2px solid var(--gl);height:64px;padding:0 24px;display:flex;align-items:center;justify-content:space-between}
.tb-bc{font-size:10px;color:var(--tx3)}
.tb-bc b{color:var(--g)}
.tb-title{font-size:18px;font-weight:900;background:linear-gradient(90deg,#2D7A08,var(--g));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-top:2px}
.sakura{background:linear-gradient(90deg,#fff8fa,#f6ffe8,#fff);border-bottom:1px solid #F0EBF8;padding:6px 24px;display:flex;align-items:center;gap:5px}
.p{font-size:14px;animation:drift 5s ease-in-out infinite;display:inline-block}
.p:nth-child(2){animation-delay:1s}.p:nth-child(3){animation-delay:2s}
@keyframes drift{0%,100%{transform:translateY(0)}50%{transform:translateY(-4px)}}
.sak-t{font-size:10px;color:#B8D8A0;letter-spacing:2px;font-weight:700;margin-left:8px}
.cnt{flex:1;overflow-y:auto;padding:22px 24px}
.card{background:#fff;border-radius:16px;border:1.5px solid var(--bd);overflow:hidden;box-shadow:0 3px 18px rgba(58,122,10,.07)}
.card-top{height:4px;background:linear-gradient(90deg,#3A9A12,var(--g),var(--gn),#FF8FB1,#A78BFA)}
.card-head{padding:16px 22px;border-bottom:1px solid var(--gl);display:flex;align-items:center;justify-content:space-between;background:linear-gradient(135deg,var(--gll),#fff)}
.card-title{font-size:15px;font-weight:900;color:var(--char)}
.btn-add{display:inline-flex;align-items:center;gap:6px;padding:9px 20px;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;font-size:13px;font-weight:800;border:none;border-radius:9px;cursor:pointer;text-decoration:none;box-shadow:0 3px 12px rgba(107,191,31,.3);transition:all .2s}
.btn-add:hover{background:linear-gradient(135deg,#2E7D08,#5AAF15);transform:translateY(-1px)}
.alert-ok{background:var(--gll);border-left:3px solid var(--g);border-radius:9px;padding:12px 16px;margin-bottom:18px;font-size:13px;font-weight:600;color:var(--gd)}
.alert-err{background:#FFF0F0;border-left:3px solid #EF4444;border-radius:9px;padding:12px 16px;margin-bottom:18px;font-size:13px;font-weight:600;color:#991B1B}
table{width:100%;border-collapse:collapse}
th{font-size:11px;font-weight:800;letter-spacing:1px;color:var(--tx3);text-transform:uppercase;padding:12px 16px;background:var(--gll);border-bottom:1.5px solid var(--bd);text-align:left}
td{padding:13px 16px;border-bottom:1px solid var(--gl);font-size:13px;color:var(--tx);vertical-align:middle}
tr:hover td{background:var(--gll)}
.badge-act{display:inline-flex;align-items:center;gap:4px;background:var(--gl);color:var(--gd);font-size:11px;font-weight:700;padding:3px 9px;border-radius:20px}
.badge-act::before{content:'';width:6px;height:6px;border-radius:50%;background:var(--g)}
.badge-off{display:inline-flex;align-items:center;gap:4px;background:#F3F4F6;color:#9CA3AF;font-size:11px;font-weight:700;padding:3px 9px;border-radius:20px}
.btn-edit{display:inline-flex;align-items:center;padding:5px 12px;background:var(--gl);color:var(--gd);border:1px solid var(--bd2);border-radius:7px;font-size:12px;font-weight:600;text-decoration:none;transition:all .2s}
.btn-edit:hover{background:var(--g);color:#fff;border-color:var(--g)}
.btn-del{display:inline-flex;align-items:center;padding:5px 12px;background:#FFF0F0;color:#EF4444;border:1px solid #FECACA;border-radius:7px;font-size:12px;font-weight:600;border:none;cursor:pointer;transition:all .2s}
.btn-del:hover{background:#EF4444;color:#fff}
.cat-img{width:44px;height:44px;object-fit:cover;border-radius:8px;border:1px solid var(--bd)}
.cat-img-ph{width:44px;height:44px;border-radius:8px;background:var(--gl);border:1px solid var(--bd);display:flex;align-items:center;justify-content:center;font-size:20px}
</style>
</head>
<body>
<div style="display:flex;min-height:100vh">
@include('admin.partials.sidebar')
<div style="flex:1;display:flex;flex-direction:column;overflow:hidden">
  <div class="topbar">
    <div><div class="tb-bc">Admin › <b>Danh mục</b></div><div class="tb-title">Quản lý Danh Mục</div></div>
    <a href="{{ route('admin.categories.create') }}" class="btn-add">+ Thêm danh mục</a>
  </div>
  <div class="sakura"><span class="p">🌸</span><span class="p">✿</span><span class="p">🍃</span><span class="sak-t">DALI · TÔ ĐIỂM CUỘC SỐNG</span></div>
  <div class="cnt">
    @if(session('success'))<div class="alert-ok">✅ {{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert-err">⚠️ {{ session('error') }}</div>@endif
    <div class="card">
      <div class="card-top"></div>
      <div class="card-head">
        <div class="card-title">Danh sách danh mục ({{ $categories->count() }})</div>
      </div>
      <table>
        <thead><tr>
          <th>#</th><th>Ảnh</th><th>Tên danh mục</th><th>Slug</th>
          <th>Sản phẩm</th><th>Trạng thái</th><th>Thứ tự</th><th>Thao tác</th>
        </tr></thead>
        <tbody>
        @forelse($categories as $cat)
        <tr>
          <td style="color:var(--tx3);font-weight:600">{{ $cat->id }}</td>
          <td>
            @if($cat->image)
              <img src="{{ asset('storage/'.$cat->image) }}" class="cat-img" alt="">
            @else
              <div class="cat-img-ph">{{ $cat->icon ?? '🎨' }}</div>
            @endif
          </td>
          <td><div style="font-weight:700">{{ $cat->icon }} {{ $cat->name }}</div><div style="font-size:11px;color:var(--tx3)">{{ Str::limit($cat->description,50) }}</div></td>
          <td style="font-family:monospace;font-size:12px;color:var(--tx3)">{{ $cat->slug }}</td>
          <td><span style="font-weight:800;color:var(--g)">{{ $cat->all_products_count }}</span> sản phẩm</td>
          <td>@if($cat->is_active)<span class="badge-act">Hiện</span>@else<span class="badge-off">Ẩn</span>@endif</td>
          <td style="font-weight:700;color:var(--tx3)">{{ $cat->sort_order }}</td>
          <td>
            <div style="display:flex;gap:7px;flex-wrap:wrap">
              <a href="{{ route('admin.categories.edit', $cat) }}" class="btn-edit">✏️ Sửa</a>
              <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('Xoá danh mục này?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-del">🗑️ Xoá</button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--tx3)">Chưa có danh mục nào. <a href="{{ route('admin.categories.create') }}" style="color:var(--g);font-weight:700">Thêm ngay →</a></td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
</body>
</html>