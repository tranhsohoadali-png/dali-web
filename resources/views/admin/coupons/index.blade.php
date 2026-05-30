<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Mã giảm giá | DALI Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--gn:#C6F135;--pk:#FF8FB1;--bd:#C8E89A;--bd2:#A8D870;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0}body{font-family:'Be Vietnam Pro','Segoe UI',sans-serif;background:var(--bg);color:var(--tx)}
.layout{display:flex;min-height:100vh}.main{flex:1;display:flex;flex-direction:column;overflow:hidden}
.tb{background:#fff;border-bottom:2px solid var(--gl);height:64px;padding:0 24px;display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
.tb-bc{font-size:10px;color:var(--tx3)}.tb-bc b{color:var(--g)}
.tb-title{font-size:18px;font-weight:900;background:linear-gradient(90deg,#2D7A08,var(--g));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-top:2px}
.sak{background:linear-gradient(90deg,#fff8fa,#f6ffe8,#fff);border-bottom:1px solid #F0EBF8;padding:6px 24px;display:flex;align-items:center;gap:5px;flex-shrink:0;font-size:14px}
.sak-t{font-size:10px;color:#B8D8A0;letter-spacing:2px;font-weight:700;margin-left:8px}
@keyframes drift{0%,100%{transform:translateY(0)}50%{transform:translateY(-4px)}}
.cnt{flex:1;overflow-y:auto;padding:22px 24px}
.alert-ok{background:var(--gll);border-left:3px solid var(--g);border-radius:9px;padding:12px 16px;margin-bottom:16px;font-size:13px;font-weight:600;color:var(--gd)}
.card{background:#fff;border-radius:16px;border:0.5px solid var(--bd);overflow:hidden;margin-bottom:20px}
.rainbow{height:4px;background:linear-gradient(90deg,#3A9A12,var(--g),var(--gn),#FF8FB1,#A78BFA)}
.card-head{padding:14px 22px;border-bottom:1px solid var(--gl);background:var(--gll);display:flex;align-items:center;justify-content:space-between}
.card-title{font-size:14px;font-weight:900;color:var(--char)}
.btn-add{display:inline-flex;align-items:center;gap:6px;padding:9px 18px;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;font-size:12px;font-weight:800;border:none;border-radius:9px;cursor:pointer;text-decoration:none}
table{width:100%;border-collapse:collapse}
th{font-size:10px;font-weight:800;letter-spacing:1px;color:var(--tx3);text-transform:uppercase;padding:10px 14px;background:var(--gll);border-bottom:1.5px solid var(--bd);text-align:left}
td{padding:11px 14px;border-bottom:1px solid var(--gl);font-size:12px;color:var(--tx);vertical-align:middle}
tr:hover td{background:var(--gll)}
.badge-act{display:inline-flex;align-items:center;gap:3px;background:var(--gl);color:var(--gd);font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px}
.badge-act::before{content:'';width:5px;height:5px;border-radius:50%;background:var(--g)}
.badge-off{background:#F3F4F6;color:#9CA3AF;font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px;display:inline-block}
.btn-e{padding:4px 10px;background:var(--gl);color:var(--gd);border:1px solid var(--bd2);border-radius:6px;font-size:10px;font-weight:700;text-decoration:none}
.btn-d{padding:4px 10px;background:#FFF0F0;color:#EF4444;border:1px solid #FECACA;border-radius:6px;font-size:10px;font-weight:700;cursor:pointer}
.btn-ap{padding:4px 10px;background:var(--gl);color:var(--gd);border:1px solid var(--bd2);border-radius:6px;font-size:10px;font-weight:700;cursor:pointer;transition:all .2s}
.btn-ap:hover{background:var(--g);color:#fff;border-color:var(--g)}
.fb{padding:20px 22px}
.g2{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px}
.g1{margin-bottom:14px}
.flabel{font-size:12px;font-weight:700;color:var(--tx);display:block;margin-bottom:6px}
.req{color:var(--pk)}
.dinput,.dselect{width:100%;background:var(--gll);border:1.5px solid var(--bd);border-radius:9px;padding:10px 13px;font-size:13px;color:var(--tx);outline:none;transition:all .2s;font-family:'Be Vietnam Pro',sans-serif}
.dinput:focus,.dselect:focus{border-color:var(--g);background:#fff}
.divider{height:1.5px;background:linear-gradient(90deg,transparent,var(--bd) 25%,var(--bd) 75%,transparent);margin:6px 0 18px}
.btn-save{display:inline-flex;align-items:center;gap:7px;padding:11px 24px;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;font-size:13px;font-weight:800;border:none;border-radius:9px;cursor:pointer}
.btn-cancel{padding:11px 20px;background:#fff;border:1.5px solid var(--bd);color:var(--tx3);font-size:12px;font-weight:600;border-radius:9px;cursor:pointer;text-decoration:none;display:inline-flex}
.toggle-row{display:flex;align-items:center;gap:8px;margin-top:9px}
.tgl{position:relative;width:40px;height:22px}
.tgl input{opacity:0;width:0;height:0;position:absolute}
.tgl-s{position:absolute;inset:0;background:#D1D5DB;border-radius:50px;cursor:pointer;transition:background .2s}
.tgl-s::before{content:'';position:absolute;width:16px;height:16px;left:3px;top:3px;background:#fff;border-radius:50%;transition:transform .2s}
.tgl input:checked + .tgl-s{background:var(--g)}
.tgl input:checked + .tgl-s::before{transform:translateX(18px)}
.flt-row{display:flex;gap:8px;margin-bottom:14px;flex-wrap:wrap}
.pagination{display:flex;gap:5px;margin-top:16px;flex-wrap:wrap;justify-content:center}
.pagination a,.pagination span{padding:6px 12px;border-radius:7px;font-size:12px;font-weight:600;text-decoration:none;border:1px solid var(--bd);color:var(--tx2);background:#fff}
.pagination a:hover{background:var(--g);color:#fff;border-color:var(--g)}
.pagination .active span{background:var(--g);color:#fff;border-color:var(--g)}</style>
</head>
<body>
<div class="layout">
  @include('admin.partials.sidebar')
  <div class="main">
    <div class="tb">
      <div><div class="tb-bc">Admin › <b>Mã giảm giá</b></div><div class="tb-title">Quản lý Mã Giảm Giá</div></div>
      <a href="{{ route('admin.coupons.create') }}" class="btn-add">+ Thêm mã mới</a>
    </div>
    <div class="sak"><span style="animation:drift 5s ease-in-out infinite;display:inline-block">🌸</span><span style="animation:drift 5s 1s ease-in-out infinite;display:inline-block">✿</span><span class="sak-t">DALI · TÔ ĐIỂM CUỘC SỐNG</span></div>
    <div class="cnt">
      @if(session('success'))<div class="alert-ok">✅ {{ session('success') }}</div>@endif
      <div class="card">
        <div class="rainbow"></div>
        <div class="card-head"><div class="card-title">{{ $coupons->count() }} mã giảm giá</div></div>
        <table>
          <thead><tr>
            <th>Mã</th><th>Loại</th><th>Giá trị</th><th>Đơn tối thiểu</th>
            <th>Đã dùng</th><th>Hết hạn</th><th>Trạng thái</th><th>Thao tác</th>
          </tr></thead>
          <tbody>
          @forelse($coupons as $cp)
          <tr>
            <td><span style="font-family:monospace;font-size:14px;font-weight:800;color:var(--g);background:var(--gl);padding:3px 10px;border-radius:6px">{{ $cp->code }}</span></td>
            <td>{{ $cp->type === 'percent' ? 'Phần trăm' : 'Số tiền cố định' }}</td>
            <td style="font-weight:800;color:var(--g)">{{ $cp->label }}</td>
            <td>{{ $cp->min_order > 0 ? number_format($cp->min_order,0,',','.').'đ' : '—' }}</td>
            <td>
              {{ $cp->used_count }}
              @if($cp->max_uses) / {{ $cp->max_uses }}@endif
            </td>
            <td>{{ $cp->expires_at ? $cp->expires_at->format('d/m/Y') : '∞ Vô hạn' }}</td>
            <td>@if($cp->is_active)<span class="badge-act">Hoạt động</span>@else<span class="badge-off">Tắt</span>@endif</td>
            <td>
              <div style="display:flex;gap:5px">
                <a href="{{ route('admin.coupons.edit', $cp) }}" class="btn-e">✏️ Sửa</a>
                <form method="POST" action="{{ route('admin.coupons.destroy', $cp) }}" onsubmit="return confirm('Xoá mã {{ $cp->code }}?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn-d">🗑️</button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="8" style="text-align:center;padding:36px;color:var(--tx3)">
            Chưa có mã giảm giá. <a href="{{ route('admin.coupons.create') }}" style="color:var(--g);font-weight:700">Thêm ngay →</a>
          </td></tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</body></html>