<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Cài đặt 3D | DALI Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--pk:#FF8FB1;--bd:#C8E89A;--bd2:#A8D870;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:var(--tx)}
.topbar{background:#fff;border-bottom:2px solid var(--gl);height:64px;padding:0 24px;display:flex;align-items:center;justify-content:space-between}
.tb-bc{font-size:10px;color:var(--tx3)}.tb-bc b{color:var(--g)}
.tb-title{font-size:18px;font-weight:900;background:linear-gradient(90deg,#2D7A08,var(--g));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-top:2px}
.cnt{flex:1;overflow-y:auto;padding:22px 24px}
.wrap{max-width:920px;margin:0 auto}
.alert-ok{background:var(--gll);border-left:3px solid var(--g);border-radius:9px;padding:12px 16px;margin-bottom:18px;font-size:13px;font-weight:600;color:var(--gd)}
.sec{background:#fff;border-radius:16px;border:1.5px solid var(--bd);box-shadow:0 3px 18px rgba(58,122,10,.07);padding:20px 22px;margin-bottom:18px}
.sec h2{font-size:15px;font-weight:900;color:var(--char);margin-bottom:4px}
.sec .hint{font-size:12px;color:var(--tx3);margin-bottom:16px}
.gia-dong{display:flex;align-items:center;gap:14px;padding:10px 13px;background:var(--gll);border:1px solid var(--bd);border-radius:11px;margin-bottom:7px}
.gia-ten{flex:1;font-size:13px;font-weight:600}
.gia-o{display:flex;align-items:center;gap:5px;background:#fff;border:1px solid var(--bd);border-radius:9px;padding:6px 11px}
.gia-o input{width:110px;border:0;outline:0;text-align:right;font-size:14px;font-weight:800;font-variant-numeric:tabular-nums;color:var(--tx);background:transparent}
.gia-o b{font-size:12.5px;color:var(--tx3)}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px;max-width:760px}
.f{display:flex;flex-direction:column;gap:5px}
.f>span{font-size:11px;font-weight:700;color:var(--tx2);letter-spacing:.3px}
.f input{border:1.5px solid var(--bd);border-radius:9px;padding:10px 13px;font-size:13px;background:var(--gll);outline:none;font-family:'Be Vietnam Pro',sans-serif}
.f input:focus{border-color:var(--g);background:#fff}
.mon-luoi{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:8px;margin-top:6px}
.mon-o{position:relative;display:flex;align-items:center;gap:9px;padding:8px 9px;background:#fff;border:1px solid var(--bd);border-left:5px solid var(--mm);border-radius:10px}
.mon-mau{width:26px;height:26px;border-radius:7px;flex:none;cursor:pointer;overflow:hidden;position:relative;box-shadow:inset 0 0 0 1px rgba(0,0,0,.15)}
.mon-mau input{position:absolute;inset:0;opacity:0;cursor:pointer;border:0;padding:0}
.mon-ten{flex:1;min-width:0;border:0;outline:0;background:transparent;font-size:13px;font-weight:600;color:var(--tx)}
.mon-bo{flex:none;width:22px;height:22px;border:0;border-radius:6px;cursor:pointer;background:#FEE2E2;color:#B91C1C;font-size:12px}
.mon-them{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:2px;min-height:46px;border:1.5px dashed var(--bd2);border-radius:10px;background:transparent;color:var(--tx3);font-size:17px;cursor:pointer;font-family:inherit}
.mon-them span{font-size:11.5px;font-weight:600}
.mon-them:hover{border-color:var(--g);color:var(--g);background:var(--gll)}
.dem-nho{font-size:11.5px;font-weight:700;color:var(--tx3);background:var(--gll);border:1px solid var(--bd);border-radius:20px;padding:2px 9px;margin-left:6px}
.warn{background:#FEF3C7;border-left:3px solid #F59E0B;border-radius:9px;padding:11px 14px;font-size:12.5px;color:#92400E;margin-bottom:16px}
.btn-save{padding:12px 26px;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;font-size:14px;font-weight:800;border:none;border-radius:10px;cursor:pointer;box-shadow:0 3px 12px rgba(107,191,31,.3)}
.btn-save:hover{transform:translateY(-1px)}
</style>
</head>
<body>
<div style="display:flex;min-height:100vh">
@include('admin.partials.sidebar')
<div style="flex:1;display:flex;flex-direction:column;overflow:hidden">
  <div class="topbar">
    <div><div class="tb-bc">Admin › Xưởng in 3D › <b>Cài đặt</b></div><div class="tb-title">Cài đặt Xưởng in 3D</div></div>
    <button form="chForm" type="submit" class="btn-save">Lưu cài đặt</button>
  </div>
  <div class="cnt"><div class="wrap">
    @if(session('ok'))<div class="alert-ok">✅ {{ session('ok') }}</div>@endif

    <form method="POST" action="{{ route('admin.cauhinh3d.update') }}" id="chForm">
      @csrf @method('PUT')

      <div class="sec">
        <h2>🧾 Bảng giá</h2>
        <div class="hint">Giá của bảng thời khoá biểu và phiếu môn. Sửa ở đây là web khách tính theo ngay.</div>
        @foreach($giaNhan as $k => $nhan)
        <label class="gia-dong">
          <span class="gia-ten">{{ $nhan }}</span>
          <span class="gia-o"><input name="gia[{{ $k }}]" inputmode="numeric" value="{{ (int)($gia[$k] ?? 0) }}"><b>₫</b></span>
        </label>
        @endforeach
      </div>

      <div class="sec">
        <h2>🏦 Tài khoản nhận tiền</h2>
        <div class="warn">⚠️ In lên mã QR khi khách chuyển khoản. Sai một chữ số là tiền đi lạc — kiểm tra kỹ.</div>
        <div class="grid">
          <label class="f"><span>Mã ngân hàng <i style="font-weight:400;color:var(--tx3)">(MB, VCB, MSB…)</i></span><input name="bank[bin]" value="{{ $bank['bin'] ?? '' }}"></label>
          <label class="f"><span>Số tài khoản</span><input name="bank[stk]" inputmode="numeric" value="{{ $bank['stk'] ?? '' }}"></label>
          <label class="f"><span>Chủ tài khoản (IN HOA không dấu)</span><input name="bank[ten]" value="{{ $bank['ten'] ?? '' }}"></label>
        </div>
      </div>

      <div class="sec">
        <h2>🎨 Danh sách môn học <span class="dem-nho" id="monDem">{{ count($mon) }} môn</span></h2>
        <div class="hint">Các môn khách chọn khi mua phiếu lẻ. Bấm ô màu để đổi màu in. Bấm ✕ để bỏ môn.</div>
        <div class="mon-luoi" id="monLuoi">
          @foreach($mon as $m)
          @php $ten = is_array($m) ? ($m[0] ?? '') : $m; $mau = is_array($m) ? ($m[1] ?? '#8CC63E') : '#8CC63E'; @endphp
          <div class="mon-o" style="--mm:{{ $mau }}">
            <label class="mon-mau" style="background:{{ $mau }}"><input type="color" value="{{ $mau }}" oninput="this.closest('.mon-o').style.setProperty('--mm',this.value);this.parentElement.style.background=this.value;this.nextElementSibling.value=this.value"><input type="hidden" name="mon_mau[]" value="{{ $mau }}"></label>
            <input class="mon-ten" name="mon_ten[]" value="{{ $ten }}" placeholder="Tên môn">
            <button type="button" class="mon-bo" onclick="this.closest('.mon-o').remove();demMon()">✕</button>
          </div>
          @endforeach
          <button type="button" class="mon-them" onclick="themMon()">＋<span>Thêm môn</span></button>
        </div>
      </div>

      <div class="sec"><button type="submit" class="btn-save">Lưu cài đặt</button></div>
    </form>
  </div></div>
</div>
</div>
<script>
function demMon(){ document.getElementById('monDem').textContent = document.querySelectorAll('#monLuoi .mon-o').length + ' môn'; }
function themMon(){
  var them = document.querySelector('.mon-them');
  var d = document.createElement('div'); d.className='mon-o'; d.style.setProperty('--mm','#8CC63E');
  d.innerHTML = '<label class="mon-mau" style="background:#8CC63E"><input type="color" value="#8CC63E" oninput="this.closest(\'.mon-o\').style.setProperty(\'--mm\',this.value);this.parentElement.style.background=this.value;this.nextElementSibling.value=this.value"><input type="hidden" name="mon_mau[]" value="#8CC63E"></label>'
    + '<input class="mon-ten" name="mon_ten[]" value="" placeholder="Tên môn">'
    + '<button type="button" class="mon-bo" onclick="this.closest(\'.mon-o\').remove();demMon()">✕</button>';
  them.parentNode.insertBefore(d, them);
  d.querySelector('.mon-ten').focus(); demMon();
}
</script>
</body>
</html>
