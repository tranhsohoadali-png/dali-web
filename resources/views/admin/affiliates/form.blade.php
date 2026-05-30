<!DOCTYPE html>
<html lang="vi">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $affiliate ? 'Sửa' : 'Thêm' }} CTV | DALI Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--gn:#C6F135;--pk:#FF8FB1;--bd:#C8E89A;--bd2:#A8D870;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0}body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:var(--tx)}
.layout{display:flex;min-height:100vh}.main{flex:1;display:flex;flex-direction:column;overflow:hidden}
.tb{background:#fff;border-bottom:2px solid var(--gl);height:64px;padding:0 24px;display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
.tb-bc{font-size:10px;color:var(--tx3)}.tb-bc a{color:var(--g);text-decoration:none;font-weight:600}
.tb-title{font-size:18px;font-weight:900;background:linear-gradient(90deg,#2D7A08,var(--g));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-top:2px}
.sak{background:linear-gradient(90deg,#fff8fa,#f6ffe8,#fff);border-bottom:1px solid #F0EBF8;padding:6px 24px;display:flex;align-items:center;gap:5px;flex-shrink:0}
.sak-t{font-size:10px;color:#B8D8A0;letter-spacing:2px;font-weight:700;margin-left:8px}
@keyframes drift{0%,100%{transform:translateY(0)}50%{transform:translateY(-4px)}}
.cnt{flex:1;overflow-y:auto;padding:22px 24px}
.form-card{background:#fff;border-radius:16px;border:0.5px solid var(--bd);overflow:hidden;max-width:680px}
.rainbow{height:4px;background:linear-gradient(90deg,#3A9A12,var(--g),var(--gn),#FF8FB1,#A78BFA)}
.card-head{padding:14px 22px;border-bottom:1px solid var(--gl);background:var(--gll);display:flex;align-items:center;gap:10px}
.card-icon{width:38px;height:38px;background:linear-gradient(135deg,var(--gl),#CCEF90);border:1px solid var(--bd2);border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:17px}
.card-title{font-size:14px;font-weight:900;color:var(--char)}
.fb{padding:18px 22px}
.sec-t{font-size:9px;font-weight:800;letter-spacing:3px;color:var(--tx3);text-transform:uppercase;display:flex;align-items:center;gap:6px;margin-bottom:14px}
.sec-t::before,.sec-t::after{content:'';flex:1;height:1px;background:var(--bd)}
.sec-t::before{background:linear-gradient(90deg,var(--bd),transparent)}
.g2{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px}
.g3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:14px}
.g1{margin-bottom:14px}
.flabel{font-size:12px;font-weight:700;color:var(--tx);display:block;margin-bottom:6px}
.req{color:var(--pk)}
.fnote{font-size:10px;color:var(--tx3);margin-top:4px}
.dinput,.dselect,.dtextarea{width:100%;background:var(--gll);border:1.5px solid var(--bd);border-radius:9px;padding:10px 13px;font-size:13px;color:var(--tx);outline:none;transition:all .2s;font-family:'Be Vietnam Pro',sans-serif}
.dinput:focus,.dselect:focus{border-color:var(--g);background:#fff}
.dtextarea{resize:vertical;min-height:70px}
.divider{height:1px;background:linear-gradient(90deg,transparent,var(--bd) 25%,var(--bd) 75%,transparent);margin:5px 0 18px}
.btn-save{display:inline-flex;align-items:center;gap:7px;padding:11px 24px;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;font-size:13px;font-weight:800;border:none;border-radius:9px;cursor:pointer}
.btn-cancel{padding:11px 18px;background:#fff;border:1.5px solid var(--bd);color:var(--tx3);font-size:12px;font-weight:600;border-radius:9px;text-decoration:none;display:inline-flex}
.tgl{position:relative;width:40px;height:22px;display:inline-block}
.tgl input{opacity:0;width:0;height:0;position:absolute}
.tgl-s{position:absolute;inset:0;background:#D1D5DB;border-radius:50px;cursor:pointer;transition:background .2s}
.tgl-s::before{content:'';position:absolute;width:16px;height:16px;left:3px;top:3px;background:#fff;border-radius:50%;transition:transform .2s}
.tgl input:checked + .tgl-s{background:var(--g)}
.tgl input:checked + .tgl-s::before{transform:translateX(18px)}
.ref-preview{background:var(--gll);border-radius:9px;padding:11px 14px;border:1px solid var(--bd);font-size:12px;color:var(--gd);font-weight:600;margin-top:8px}
</style>
</head>
<body>
<div class="layout">
  @include('admin.partials.sidebar')
  <div class="main">
    <div class="tb">
      <div>
        <div class="tb-bc"><a href="{{ route('admin.affiliates.index') }}">CTV</a> › <b>{{ $affiliate ? 'Chỉnh sửa' : 'Thêm mới' }}</b></div>
        <div class="tb-title">{{ $affiliate ? 'Sửa CTV: '.$affiliate->name : 'Thêm CTV Mới' }}</div>
      </div>
      <a href="{{ route('admin.affiliates.index') }}" class="btn-cancel">← Quay lại</a>
    </div>
    <div class="sak"><span style="animation:drift 5s ease-in-out infinite;display:inline-block">🌸</span><span class="sak-t">DALI · TÔ ĐIỂM CUỘC SỐNG</span></div>
    <div class="cnt">
      <div class="form-card">
        <div class="rainbow"></div>
        <div class="card-head"><div class="card-icon">👥</div><div><div class="card-title">{{ $affiliate ? 'Cập nhật thông tin CTV' : 'Đăng ký cộng tác viên mới' }}</div></div></div>
        <div class="fb">
          <form method="POST" action="{{ $affiliate ? route('admin.affiliates.update', $affiliate) : route('admin.affiliates.store') }}">
            @csrf @if($affiliate) @method('PUT') @endif

            <div class="sec-t">Thông tin cơ bản</div>
            <div class="g2">
              <div><label class="flabel">Tên CTV <span class="req">*</span></label><input type="text" name="name" class="dinput" value="{{ old('name',$affiliate->name??'') }}" placeholder="Nguyễn Văn A" required oninput="previewCode(this.value)"></div>
              <div><label class="flabel">Số điện thoại</label><input type="text" name="phone" class="dinput" value="{{ old('phone',$affiliate->phone??'') }}" placeholder="0912 345 678"></div>
            </div>
            <div class="g2">
              <div><label class="flabel">Email</label><input type="email" name="email" class="dinput" value="{{ old('email',$affiliate->email??'') }}" placeholder="ctv@email.com"></div>
              <div>
                <label class="flabel">Mã giới thiệu (Ref code)<span style="font-size:10px;color:var(--tx3);font-weight:400;margin-left:5px">(để trống = tự động)</span></label>
                <input type="text" name="code" id="codeInput" class="dinput" value="{{ old('code',$affiliate->code??'') }}" placeholder="DALI_TENA" style="text-transform:uppercase" @if($affiliate) readonly @endif>
                <div class="fnote">URL: <span id="codePreview" style="color:var(--g);font-weight:700">{{ url('/ref/'.($affiliate->code??'[MÃ_CTV]')) }}</span></div>
              </div>
            </div>
            <div class="g2">
              <div>
                <label class="flabel">Tỷ lệ hoa hồng (%)</label>
                <input type="number" name="commission_rate" class="dinput" value="{{ old('commission_rate',$affiliate->commission_rate??5) }}" min="0" max="50" step="0.5">
                <div class="fnote">Ví dụ: 5 = CTV nhận 5% giá trị đơn hàng</div>
              </div>
              <div>
                <label class="flabel">Trạng thái</label>
                <div style="display:flex;align-items:center;gap:8px;margin-top:10px">
                  <label class="tgl"><input type="checkbox" name="is_active" value="1" {{ old('is_active',$affiliate->is_active??true)?'checked':'' }}><span class="tgl-s"></span></label>
                  <span style="font-size:12px;font-weight:600;color:var(--tx2)">Đang hoạt động</span>
                </div>
              </div>
            </div>

            <div class="divider"></div>
            <div class="sec-t">Thông tin ngân hàng</div>
            <div class="g3">
              <div><label class="flabel">Ngân hàng</label><input type="text" name="bank_name" class="dinput" value="{{ old('bank_name',$affiliate->bank_name??'') }}" placeholder="Vietcombank"></div>
              <div><label class="flabel">Số tài khoản</label><input type="text" name="bank_acc" class="dinput" value="{{ old('bank_acc',$affiliate->bank_acc??'') }}" placeholder="1234567890"></div>
              <div><label class="flabel">Chủ tài khoản</label><input type="text" name="bank_owner" class="dinput" value="{{ old('bank_owner',$affiliate->bank_owner??'') }}" placeholder="NGUYEN VAN A" style="text-transform:uppercase"></div>
            </div>

            <div class="divider"></div>
            <div class="g1"><label class="flabel">Ghi chú nội bộ</label><textarea name="note" class="dinput dtextarea" placeholder="Ghi chú về CTV này...">{{ old('note',$affiliate->note??'') }}</textarea></div>

            <div style="display:flex;gap:10px">
              <button type="submit" class="btn-save">✅ {{ $affiliate ? 'Lưu thay đổi' : 'Thêm CTV' }}</button>
              <a href="{{ route('admin.affiliates.index') }}" class="btn-cancel">Huỷ bỏ</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
function previewCode(name){
  if(document.querySelector('input[name=code]').readOnly)return;
  var code='DALI_'+name.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g,'').replace(/[^A-Z0-9]/g,'_').replace(/_+/g,'_');
  document.getElementById('codePreview').textContent='{{ url("/ref/") }}/'+code;
}
</script>
</body>
</html>