<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $coupon ? 'Sửa' : 'Thêm' }} Mã Giảm Giá | DALI Admin</title>
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
      <div>
        <div class="tb-bc"><a href="{{ route('admin.coupons.index') }}" style="color:var(--g);text-decoration:none;font-weight:600">Mã giảm giá</a> › <b>{{ $coupon ? 'Chỉnh sửa' : 'Thêm mới' }}</b></div>
        <div class="tb-title">{{ $coupon ? 'Sửa mã: '.$coupon->code : 'Thêm Mã Giảm Giá' }}</div>
      </div>
      <a href="{{ route('admin.coupons.index') }}" class="btn-cancel">← Quay lại</a>
    </div>
    <div class="sak"><span style="animation:drift 5s ease-in-out infinite;display:inline-block">🌸</span><span class="sak-t">DALI · TÔ ĐIỂM CUỘC SỐNG</span></div>
    <div class="cnt">
      <div class="card" style="max-width:640px">
        <div class="rainbow"></div>
        <div class="card-head">
          <div style="display:flex;align-items:center;gap:10px">
            <div style="width:38px;height:38px;background:var(--gl);border:1px solid var(--bd2);border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:18px">🏷️</div>
            <div><div class="card-title">{{ $coupon ? 'Cập nhật mã giảm giá' : 'Tạo mã giảm giá mới' }}</div></div>
          </div>
        </div>
        <div class="fb">
          <form method="POST" action="{{ $coupon ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}">
            @csrf @if($coupon) @method('PUT') @endif
            <div class="g2">
              <div>
                <label class="flabel">Mã giảm giá <span class="req">*</span></label>
                <input type="text" name="code" class="dinput" value="{{ old('code', $coupon->code ?? '') }}" placeholder="DALI10" style="text-transform:uppercase" required>
                @error('code')<div style="font-size:11px;color:#EF4444;margin-top:4px">{{ $message }}</div>@enderror
              </div>
              <div>
                <label class="flabel">Loại giảm giá <span class="req">*</span></label>
                <select name="type" class="dselect" id="typeSelect" onchange="updateValueLabel()">
                  <option value="percent" {{ old('type', $coupon->type ?? 'percent') === 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
                  <option value="fixed"   {{ old('type', $coupon->type ?? '') === 'fixed' ? 'selected' : '' }}>Số tiền cố định (đ)</option>
                </select>
              </div>
            </div>
            <div class="g2">
              <div>
                <label class="flabel" id="valueLabel">Giá trị giảm (%) <span class="req">*</span></label>
                <input type="number" name="value" class="dinput" value="{{ old('value', $coupon->value ?? '') }}" placeholder="10" min="1" required>
              </div>
              <div>
                <label class="flabel">Đơn hàng tối thiểu (đ)</label>
                <input type="number" name="min_order" class="dinput" value="{{ old('min_order', $coupon->min_order ?? 0) }}" placeholder="0">
              </div>
            </div>
            <div class="g2">
              <div>
                <label class="flabel">Số lần dùng tối đa</label>
                <input type="number" name="max_uses" class="dinput" value="{{ old('max_uses', $coupon->max_uses ?? '') }}" placeholder="Để trống = vô hạn">
              </div>
              <div>
                <label class="flabel">Ngày hết hạn</label>
                <input type="date" name="expires_at" class="dinput" value="{{ old('expires_at', $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '') }}">
              </div>
            </div>
            <div class="g1">
              <label class="flabel">Mô tả (dùng nội bộ)</label>
              <input type="text" name="description" class="dinput" value="{{ old('description', $coupon->description ?? '') }}" placeholder="Mã dành cho khách hàng mới">
            </div>
            <div class="g1">
              <label class="flabel">Trạng thái</label>
              <div class="toggle-row">
                <label class="tgl"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }}><span class="tgl-s"></span></label>
                <span style="font-size:12px;font-weight:600;color:var(--tx2)">Đang hoạt động</span>
              </div>
            </div>
            <div class="divider"></div>
            <div style="display:flex;gap:10px">
              <button type="submit" class="btn-save">✅ {{ $coupon ? 'Lưu thay đổi' : 'Tạo mã giảm giá' }}</button>
              <a href="{{ route('admin.coupons.index') }}" class="btn-cancel">Huỷ bỏ</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
function updateValueLabel(){
  var t=document.getElementById('typeSelect').value;
  document.getElementById('valueLabel').innerHTML=t==='percent'?'Giá trị giảm (%) <span style="color:var(--pk)">*</span>':'Số tiền giảm (đ) <span style="color:var(--pk)">*</span>';
}
updateValueLabel();
</script>
</body></html>