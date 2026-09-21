<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Soạn TKB {{ $don->ma }} | DALI Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--bd:#C8E89A;--bd2:#A8D870;--bg:#EDF1F4;--tx:#1A4D00;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:var(--tx);padding:16px}
.bar{max-width:210mm;margin:0 auto 12px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px}
.back{font-size:13px;color:var(--gd);text-decoration:none;font-weight:700}
.btn{padding:9px 16px;border:none;border-radius:9px;font-size:13px;font-weight:800;cursor:pointer;font-family:inherit}
.btn-g{background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff}
/* Tờ A4 */
#sheet{width:210mm;height:297mm;margin:0 auto;background:#fff;box-shadow:0 6px 30px rgba(0,0,0,.14);padding:10mm;overflow:hidden}
#sheetInner{transform-origin:top left}
.card{border:1.5px solid var(--bd);border-radius:12px;padding:14px 16px;margin-bottom:12px}
.hd{display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:10px;border-bottom:1.5px solid var(--gl);padding-bottom:10px;margin-bottom:10px}
.ma{font-size:18px;font-weight:900;color:var(--char)}
.sub{font-size:12px;color:var(--tx3)}
.tenin{background:linear-gradient(135deg,var(--gll),#fff);border:1.5px solid var(--bd);border-radius:10px;padding:8px 16px;text-align:center;margin-bottom:12px}
.tenin .l{font-size:10.5px;color:var(--tx3);font-weight:700;text-transform:uppercase;letter-spacing:1px}
.tenin .v{font-size:22px;font-weight:900;color:var(--gd)}
.row{display:flex;justify-content:space-between;font-size:13px;padding:4px 0;border-bottom:1px solid var(--gll)}
.row span{color:var(--tx3)}.row b{font-weight:800}
h2{font-size:13.5px;font-weight:900;color:var(--char);margin:2px 0 9px}
.prog{font-size:12.5px;font-weight:800;color:var(--gd);margin-bottom:9px}
.mon-list{column-count:2;column-gap:10px}
.mon{display:flex;align-items:center;gap:10px;padding:8px 11px;border:1.5px solid var(--bd);border-radius:10px;margin-bottom:7px;background:#FCFFF7;cursor:pointer;user-select:none;break-inside:avoid;-webkit-column-break-inside:avoid}
.mon input{width:20px;height:20px;accent-color:var(--g);flex:0 0 auto}
.mon .ten{font-size:14px;font-weight:700;flex:1;line-height:1.15}
.mon .sl{font-size:14px;font-weight:900;color:var(--gd);background:var(--gl);border-radius:20px;padding:1px 11px;flex:0 0 auto}
.mon.done{background:var(--gll);opacity:.6}
.mon.done .ten{text-decoration:line-through}
.empty{text-align:center;color:var(--tx3);padding:30px;font-size:13px}
#ruler{position:absolute;width:100mm;height:0;visibility:hidden;pointer-events:none}
@media print{
  @page{size:A4 portrait;margin:0}
  body{background:#fff;padding:0}
  .bar{display:none!important}
  #sheet{width:210mm;height:297mm;box-shadow:none;margin:0}
  .mon{cursor:default}
}
</style>
</head>
<body>
<div id="ruler"></div>
<div class="bar">
  <a href="{{ route('admin.diho.show', $don) }}" class="back">← Về đơn {{ $don->ma }}</a>
  <button class="btn btn-g" id="btnIn">🖨️ In bảng soạn (vừa 1 trang A4)</button>
</div>

<div id="sheet"><div id="sheetInner">
  <div class="card">
    <div class="hd">
      <div>
        <div class="ma">📋 Soạn TKB · {{ $don->ma }}</div>
        <div class="sub">Đại lý: {{ $don->dai_ly_ten }} @if($don->dai_ly_sdt)· {{ $don->dai_ly_sdt }}@endif</div>
      </div>
      <div class="sub" style="text-align:right">Ngày đặt<br><b style="color:var(--tx)">{{ optional($don->created_at)->format('d/m/Y H:i') }}</b></div>
    </div>

    @php $tenIn = collect($don->chi_tiet ?: [])->pluck('ten_in')->filter()->unique()->implode(', '); @endphp
    @if($tenIn !== '')
      <div class="tenin"><div class="l">✍️ Tên in riêng</div><div class="v">{{ $tenIn }}</div></div>
    @endif

    <h2>🧾 Sản phẩm cần làm</h2>
    @foreach(($don->chi_tiet ?: []) as $l)
      <div class="row"><span>{{ $l['ten'] ?? '' }} ×{{ $l['qty'] ?? 1 }}</span><b>{{ $l['bien_the'] ?? '' }}@if(!empty($l['cap_hoc'])) · {{ $l['cap_hoc'] }}@endif</b></div>
    @endforeach
  </div>

  <div class="card">
    @php $ds = $don->mon_soan ?: []; $tong = collect($ds)->sum('sl'); $xong = collect($ds)->where('xong',true)->sum('sl'); @endphp
    <h2>✅ Danh sách thẻ môn cần làm (tick khi xong)</h2>
    @if(count($ds))
      <div class="prog" id="prog">Đã xong {{ collect($ds)->where('xong',true)->count() }}/{{ count($ds) }} môn · {{ $xong }}/{{ $tong }} thẻ</div>
      <div class="mon-list">
      @foreach($ds as $i => $m)
        <label class="mon {{ !empty($m['xong']) ? 'done' : '' }}" data-tick="{{ route('admin.diho.ticksoan', $don) }}" data-idx="{{ $i }}">
          <input type="checkbox" {{ !empty($m['xong']) ? 'checked' : '' }}>
          <span class="ten">{{ $m['mon'] }}</span>
          <span class="sl">×{{ $m['sl'] }}</span>
        </label>
      @endforeach
      </div>
    @else
      <div class="empty">Chưa có danh sách môn. Về đơn, bấm <b>“🤖 Đọc số môn (AI)”</b> rồi <b>“📋 Chuyển sang soạn TKB”</b>.</div>
    @endif
  </div>
</div></div>

<script>
var CSRF='{{ csrf_token() }}';

// Tự co nội dung để LUÔN vừa 1 trang A4 (mọi số lượng môn)
function pxMm(){ return document.getElementById('ruler').getBoundingClientRect().width/100; }
function fitPage(){
  var inner=document.getElementById('sheetInner');
  inner.style.transform='none';
  var mm=pxMm();
  var availH=(297-20)*mm;          // A4 cao 297 trừ padding 10+10
  var availW=(210-20)*mm;          // A4 rộng 210 trừ padding 10+10
  var h=inner.scrollHeight, w=inner.scrollWidth;
  var s=Math.min(1, availH/h, availW/w);
  inner.style.transform = s<1 ? ('scale('+s+')') : 'none';
}
window.addEventListener('load', fitPage);
window.addEventListener('resize', fitPage);
document.getElementById('btnIn').addEventListener('click', function(){ fitPage(); setTimeout(function(){ window.print(); }, 60); });
window.addEventListener('beforeprint', fitPage);

// Tick môn đã soạn xong (lưu ngay)
document.addEventListener('change',function(e){
  var cb=e.target; if(cb.type!=='checkbox') return;
  var lab=cb.closest('.mon'); if(!lab) return;
  cb.disabled=true;
  fetch(lab.dataset.tick,{method:'POST',headers:{'X-CSRF-TOKEN':CSRF,'Content-Type':'application/json'},body:JSON.stringify({idx:lab.dataset.idx})})
    .then(function(r){return r.json();})
    .then(function(d){ if(d.ok){ lab.classList.toggle('done', d.xong); capNhatTienDo(); } else { cb.checked=!cb.checked; } })
    .catch(function(){ cb.checked=!cb.checked; })
    .finally(function(){ cb.disabled=false; });
});
function capNhatTienDo(){
  var labs=[].slice.call(document.querySelectorAll('.mon'));
  var xongMon=0,tongThe=0,xongThe=0;
  labs.forEach(function(l){
    var sl=parseInt((l.querySelector('.sl').textContent||'0').replace(/\D/g,''),10)||0; tongThe+=sl;
    if(l.classList.contains('done')){ xongMon++; xongThe+=sl; }
  });
  var p=document.getElementById('prog'); if(p) p.textContent='Đã xong '+xongMon+'/'+labs.length+' môn · '+xongThe+'/'+tongThe+' thẻ';
}
</script>
</body>
</html>
