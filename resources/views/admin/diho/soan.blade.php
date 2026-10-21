<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Soạn TKB {{ $don->ma }} | DALI Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
/* Phiếu soạn: MỖI BẢNG TKB (mỗi dòng đơn) MỘT TỜ, CHỌN CỠ GIẤY (A6 mặc định, A5, A4, nhãn nhiệt, tự nhập) — chủ xưởng 2026-10-21.
   Mọi kích thước trong tờ tính theo em, cỡ chữ gốc tỉ lệ bề rộng giấy (--k = rộng/105mm) -> đổi khổ là phóng/thu cả tờ.
   Chữ đen nền trắng, nét đủ dày, không nền màu: in được cả máy in nhiệt (máy in nhãn vận chuyển). */
:root{--gd:#3E7A0A;--bg:#E9EDF1;--pw:105mm;--ph:147.5mm;--k:1}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:#000;padding:14px 12px 40px}
.bar{max-width:760px;margin:0 auto 12px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px}
.back{font-size:13px;color:var(--gd);text-decoration:none;font-weight:700}
.btn{padding:9px 16px;border:none;border-radius:9px;font-size:13px;font-weight:800;cursor:pointer;font-family:inherit;background:linear-gradient(135deg,#3A9A12,#6BBF1F);color:#fff}
.prog{font-size:13px;font-weight:800;color:var(--gd)}
.kho{display:flex;align-items:center;gap:6px;font-size:12.5px;font-weight:700;color:#334155;flex-wrap:wrap}
.kho select,.kho input{font-family:inherit;font-size:12.5px;font-weight:700;border:1.5px solid #94A3B8;border-radius:8px;padding:6px 8px;background:#fff;color:#0F172A}
.kho input{width:64px}
.canh{max-width:760px;margin:0 auto 12px;background:#FEF3C7;border:1px solid #F59E0B;border-radius:9px;padding:9px 12px;font-size:12.5px;color:#92400E;font-weight:600}
.empty{max-width:520px;margin:30px auto;background:#fff;border-radius:12px;text-align:center;color:#475569;padding:30px;font-size:14px}
#khung{overflow:hidden}
.pages{display:flex;flex-direction:column;align-items:center;gap:14px;transform-origin:top center}
.ptool{display:flex;justify-content:space-between;align-items:center;width:var(--pw);margin-bottom:3px;font-size:9px;color:#475569;font-weight:700}
.ptool button{font-size:9px;padding:3px 8px;border:1px solid #94A3B8;background:#fff;border-radius:6px;cursor:pointer;font-family:inherit;font-weight:700;color:#0F172A}
.page{width:var(--pw);height:var(--ph);font-size:calc(var(--k) * 10pt);background:#fff;box-shadow:0 4px 18px rgba(0,0,0,.15);padding:1.1em 1.1em .85em;display:flex;flex-direction:column;overflow:hidden}
.ph{border-bottom:max(.5mm,.17em) solid #000;padding-bottom:.43em}
.ph1{display:flex;justify-content:space-between;align-items:center;gap:.6em}
.ma{font-size:1.25em;font-weight:900;letter-spacing:.02em}
.bang{font-size:1.1em;font-weight:900;background:#000;color:#fff;border-radius:.33em;padding:.08em .55em;white-space:nowrap}
.sp{font-size:.85em;font-weight:600;margin-top:.27em}
.bt{font-size:1.2em;font-weight:900;line-height:1.15}
.tenin{margin-top:.34em;border:max(.4mm,.14em) solid #000;border-radius:.42em;text-align:center;font-size:1.3em;font-weight:900;padding:.12em .35em;overflow-wrap:anywhere}
.tong{font-size:1em;font-weight:700;margin-top:.28em}
.tong .dp{font-size:.8em;font-weight:600;white-space:nowrap}
.list{position:relative;flex:1;min-height:0;overflow:hidden;display:grid;grid-template-columns:1fr 1fr;gap:.37em .51em;align-content:start;margin-top:.57em;font-size:calc(var(--fs,1) * 1.1em)}
.list.c3{grid-template-columns:1fr 1fr 1fr}
.tran-canh{margin-top:.3em;background:#000;color:#fff;font-weight:900;font-size:.95em;text-align:center;border-radius:.3em;padding:.15em .4em}
.mon{display:flex;align-items:center;gap:.35em;border:max(.3mm,.08em) solid #000;border-radius:.35em;padding:.28em .4em;cursor:pointer;user-select:none;min-width:0}
.mon input{appearance:none;-webkit-appearance:none;width:.95em;height:.95em;border:max(.35mm,.1em) solid #000;border-radius:.15em;flex:none;margin:0;cursor:pointer;background:#fff}
.mon input:checked{background:#000;box-shadow:inset 0 0 0 .12em #fff}
.ten{flex:1;min-width:0;font-weight:800;line-height:1.1;overflow-wrap:anywhere}
.sl{flex:none;font-weight:900;font-size:1.1em}
.mon.done .ten{text-decoration:line-through}
.pf{font-size:.7em;margin-top:.48em;display:flex;justify-content:space-between;gap:.6em}
#ruler{position:absolute;width:var(--pw);height:0;visibility:hidden;pointer-events:none}
@media screen{.mon.done{opacity:.5}}
@media print{
  body{background:#fff;padding:0}
  .bar,.canh,.ptool,.empty{display:none!important}
  #khung{height:auto!important;overflow:visible}
  .pages{display:block;transform:none!important}
  .pwrap{break-after:page;page-break-after:always}
  .pwrap:last-child,body.in-mot .pwrap.chon{break-after:auto;page-break-after:auto}
  body.in-mot .pwrap:not(.chon){display:none}
  .page{box-shadow:none}
  .mon{cursor:default}
}
</style>
<style id="khoIn">@page{size:105mm 148mm;margin:0}</style>
</head>
<body>
<div id="ruler"></div>
@php
  $ds = $don->mon_soan ?: [];
  $ct = $don->chi_tiet ?: [];
  // Nhóm theo DÒNG đơn (mỗi dòng = 1 bảng TKB = 1 tờ); giữ chỉ số gốc _i để tick đúng mục; danh sách cũ (không dòng) xếp cuối
  $nhom = collect($ds)->map(fn ($m, $i) => $m + ['_i' => $i])
      ->groupBy(fn ($m) => isset($m['dong']) ? (string) $m['dong'] : 'chung')
      ->sortBy(fn ($v, $k) => $k === 'chung' ? PHP_INT_MAX : (int) $k);
  $soTo = $nhom->count();
  $tong = collect($ds)->sum('sl'); $xongThe = collect($ds)->where('xong', true)->sum('sl');
  // Dòng có ảnh TKB mà chưa chuyển sang soạn -> nhắc (đơn 2 bảng dễ quên bảng thứ hai)
  $thieu = [];
  foreach ($ct as $i => $l) {
      if (!empty($l['anh_ghi_chu']) && !$nhom->has((string) $i)) $thieu[] = $i + 1;
  }
  // Đánh số "Bảng N/M" chỉ trên các dòng là BẢNG TKB (có ảnh TKB hoặc đã soạn), không đếm chân đế / thẻ lẻ cùng đơn
  $bang = collect(array_keys($ct))->filter(fn ($i) => !empty($ct[$i]['anh_ghi_chu']) || $nhom->has((string) $i))->values();
@endphp
<div class="bar">
  <a href="{{ route('admin.diho.show', $don) }}" class="back">← Về đơn {{ $don->ma }}</a>
  @if($soTo)
    <span class="prog" id="prog">Đã xong {{ collect($ds)->where('xong', true)->count() }}/{{ count($ds) }} môn · {{ $xongThe }}/{{ $tong }} thẻ</span>
    <span class="kho">Cỡ giấy
      <select id="khoChon">
        <option value="A6">A6 · 105×148 mm</option>
        <option value="A5">A5 · 148×210 mm</option>
        <option value="A4">A4 · 210×297 mm</option>
        <option value="N150">Nhãn nhiệt 100×150 mm</option>
        <option value="N100">Nhãn nhiệt 100×100 mm</option>
        <option value="tuy">Tự nhập (mm)…</option>
      </select>
      <span id="khoTuy" style="display:none">
        <input type="number" id="khoW" min="40" max="420" step="1" value="80" aria-label="Rộng (mm)"> ×
        <input type="number" id="khoH" min="40" max="600" step="1" value="120" aria-label="Cao (mm)">
      </span>
    </span>
    <button class="btn" id="btnIn" data-so-to="{{ $soTo }}">🖨️ In</button>
  @endif
</div>
@if($thieu && $soTo)
  <div class="canh">⚠️ Dòng {{ implode(', ', $thieu) }} có ảnh TKB nhưng CHƯA chuyển sang soạn — về đơn, bấm “🔎 Đọc số môn” rồi “📋 Chuyển dòng này sang soạn TKB”.</div>
@endif

@if($soTo)
<div id="khung"><div class="pages">
  @foreach($nhom as $dong => $ms)
    @php $l = $dong === 'chung' ? null : ($ct[(int) $dong] ?? null); @endphp
    <div class="pwrap">
      <div class="ptool"><span>Tờ {{ $loop->iteration }}/{{ $soTo }}</span><button type="button" class="in1">🖨️ In tờ này</button></div>
      <section class="page">
        <header class="ph">
          <div class="ph1">
            <span class="ma">{{ $don->ma }}</span>
            @if($dong === 'chung')
              <span class="bang">DS cũ</span>
            @elseif($bang->count() > 1 && $bang->search((int) $dong) !== false)
              <span class="bang">Bảng {{ $bang->search((int) $dong) + 1 }}/{{ $bang->count() }}</span>
            @endif
          </div>
          @if($l)
            <div class="sp">{{ $l['ten'] ?? '' }} @if(((int) ($l['qty'] ?? 1)) > 1) ×{{ (int) $l['qty'] }} @endif</div>
            <div class="bt">{{ $l['bien_the'] ?? '' }} @if(!empty($l['cap_hoc'])) · {{ $l['cap_hoc'] }} @endif</div>
            @if(!empty($l['ten_in']))<div class="tenin">✍ {{ $l['ten_in'] }}</div>@endif
          @else
            <div class="sp">Danh sách cũ — chưa rõ thuộc bảng nào (soạn lại theo từng dòng để tách)</div>
          @endif
          @php $theoTkb = $ms->sum(fn ($m) => $m['sl_tkb'] ?? $m['sl']); $duPhong = $ms->sum('sl') - $theoTkb; @endphp
          <div class="tong">{{ $ms->count() }} môn · <b>{{ $ms->sum('sl') }} thẻ cần in</b>
            @if($duPhong > 0) <span class="dp">({{ $theoTkb }} theo TKB + {{ $duPhong }} dự phòng)</span> @endif
          </div>
          <div class="tran-canh" style="display:none"></div>
        </header>
        <div class="list">
          @foreach($ms as $m)
            <label class="mon {{ !empty($m['xong']) ? 'done' : '' }}" data-tick="{{ route('admin.diho.ticksoan', $don) }}" data-idx="{{ $m['_i'] }}">
              <input type="checkbox" {{ !empty($m['xong']) ? 'checked' : '' }}>
              <span class="ten">{{ $m['mon'] }}</span>
              <span class="sl">×{{ $m['sl'] }}</span>
            </label>
          @endforeach
        </div>
        <footer class="pf"><span>{{ $don->dai_ly_ten }}</span><span>Đặt {{ optional($don->created_at)->format('d/m/Y') }}</span></footer>
      </section>
    </div>
  @endforeach
</div></div>
@else
  <div class="empty">Chưa có danh sách môn. Về đơn, bấm <b>“🔎 Đọc số môn”</b> rồi <b>“📋 Chuyển dòng này sang soạn TKB”</b>.</div>
@endif

<script>
var CSRF='{{ csrf_token() }}';

// ===== Cỡ giấy =====
var KHO={ A6:{w:105,h:148,ten:'A6'}, A5:{w:148,h:210,ten:'A5'}, A4:{w:210,h:297,ten:'A4'},
          N150:{w:100,h:150,ten:'100×150'}, N100:{w:100,h:100,ten:'100×100'} };
var KHO_KEY='dali_soan_kho';
function docKho(){ try{ return JSON.parse(localStorage.getItem(KHO_KEY)||'null'); }catch(e){ return null; } }
function luuKho(v){ try{ localStorage.setItem(KHO_KEY, JSON.stringify(v)); }catch(e){} }
function khoHienTai(){
  var sel=document.getElementById('khoChon'); if(!sel) return {w:105,h:148,ten:'A6'};
  if(sel.value==='tuy'){
    var w=Math.max(40,Math.min(420,parseFloat(document.getElementById('khoW').value)||80));
    var h=Math.max(40,Math.min(600,parseFloat(document.getElementById('khoH').value)||120));
    return {w:w,h:h,ten:w+'×'+h};
  }
  return KHO[sel.value]||KHO.A6;
}
function apKho(){
  var k=khoHienTai(), r=document.documentElement.style;
  r.setProperty('--pw', k.w+'mm');
  r.setProperty('--ph', (k.h-0.5)+'mm');          // hụt 0,5 mm: tránh làm tràn sang tờ trắng khi in
  // chữ gốc 10pt ở khổ A6; khổ khác phóng/thu theo cạnh "chật" hơn (giấy ngang/thấp không bắt đầu với chữ quá to)
  r.setProperty('--k', Math.min(k.w/105, k.h/148).toFixed(4));
  document.getElementById('khoIn').textContent='@page{size:'+k.w+'mm '+k.h+'mm;margin:0}';
  var b=document.getElementById('btnIn');
  if(b){ var n=+b.dataset.soTo||1; b.textContent='🖨️ In '+(n>1?'cả '+n+' tờ ':'phiếu ')+k.ten; }
  khoiDong();
}

// Cỡ chữ danh sách: bắt đầu TO (1,7×), nhỏ dần tới khi vừa tờ; còn chữ to thì không để tên môn xuống dòng.
// Đo đúng kích thước mm/pt như lúc in (phóng màn hình bằng transform không đổi bố cục).
function vuaTo(pg){
  var list=pg.querySelector('.list'); if(!list) return;
  var fs=1.7;
  function dat(v){ list.style.setProperty('--fs', v); }
  function tran(){ return list.scrollHeight > list.clientHeight + 1; }
  function xuongDong(){
    var ts=list.querySelectorAll('.ten');
    for(var i=0;i<ts.length;i++){ var c=parseFloat(getComputedStyle(ts[i]).fontSize)||0; if(c && ts[i].scrollHeight > c*1.1*1.6) return true; }
    return false;
  }
  var w=pg.querySelector('.tran-canh'); if(w){ w.style.display='none'; w.textContent=''; }
  list.classList.remove('c3');
  dat(fs);
  while(fs>0.5 && (tran() || (fs>1.0 && xuongDong()))){ fs=Math.round((fs-0.05)*100)/100; dat(fs); }
  if(tran()){                                     // 2 cột chữ nhỏ nhất vẫn tràn -> thử 3 cột
    list.classList.add('c3'); fs=1.2; dat(fs);
    while(fs>0.5 && tran()){ fs=Math.round((fs-0.05)*100)/100; dat(fs); }
  }
  // Vẫn không đủ chỗ: BÁO RÕ (cả trên giấy in) số môn bị khuất — không để thợ soạn thiếu thẻ mà không biết
  function demKhuat(){ var an=0, h=list.clientHeight; list.querySelectorAll('.mon').forEach(function(m){ if(m.offsetTop+m.offsetHeight>h+1) an++; }); return an; }
  if(w && demKhuat()){
    w.style.display=''; w.textContent='⚠';           // hiện dòng báo trước rồi mới đếm (dòng báo chiếm chỗ của danh sách)
    w.textContent='⚠ CÒN '+demKhuat()+' MÔN BỊ KHUẤT — chọn khổ giấy lớn hơn';
  }
}
// Phóng trên màn hình bằng transform (KHÔNG dùng CSS zoom: zoom làm chữ xuống dòng khác lúc đo -> khuất hàng cuối)
function datZoom(){
  var ps=document.querySelector('.pages'), kh=document.getElementById('khung'); if(!ps||!kh) return;
  var pw=document.getElementById('ruler').getBoundingClientRect().width||397;   // bề rộng tờ theo px
  var z=Math.max(0.4, Math.min(1.8, (window.innerWidth-28)/pw));
  ps.style.transform='scale('+z+')';
  kh.style.height=Math.ceil(ps.offsetHeight*z)+'px';
}
function khoiDong(){
  document.querySelectorAll('.page').forEach(vuaTo);
  datZoom();
}
(function(){
  var sel=document.getElementById('khoChon'); if(!sel) return;
  var v=docKho();
  if(v && (KHO[v.kho] || v.kho==='tuy')){
    sel.value=v.kho;
    if(v.kho==='tuy'){ document.getElementById('khoW').value=v.w||80; document.getElementById('khoH').value=v.h||120; }
  }
  document.getElementById('khoTuy').style.display=sel.value==='tuy'?'':'none';
  function doi(){
    document.getElementById('khoTuy').style.display=sel.value==='tuy'?'':'none';
    var k=khoHienTai(); luuKho({kho:sel.value,w:k.w,h:k.h}); apKho();
  }
  sel.addEventListener('change', doi);
  document.getElementById('khoW').addEventListener('change', doi);
  document.getElementById('khoH').addEventListener('change', doi);
})();
(document.fonts && document.fonts.ready ? document.fonts.ready : Promise.resolve()).then(apKho);
window.addEventListener('resize', datZoom);

// In: cả xấp (mỗi bảng 1 tờ) hoặc chỉ 1 tờ
function boChon(){ document.body.classList.remove('in-mot'); document.querySelectorAll('.pwrap.chon').forEach(function(x){ x.classList.remove('chon'); }); }
var bIn=document.getElementById('btnIn'); if(bIn) bIn.addEventListener('click', function(){ boChon(); window.print(); });
document.addEventListener('click', function(e){
  var b=e.target.closest('.in1'); if(!b) return;
  boChon(); b.closest('.pwrap').classList.add('chon'); document.body.classList.add('in-mot'); window.print();
});
window.addEventListener('afterprint', boChon);

// Tick môn đã soạn xong (lưu ngay)
document.addEventListener('change',function(e){
  var cb=e.target; if(cb.type!=='checkbox') return;
  var lab=cb.closest('.mon'); if(!lab) return;
  cb.disabled=true;
  fetch(lab.dataset.tick,{method:'POST',headers:{'X-CSRF-TOKEN':CSRF,'Content-Type':'application/json'},body:JSON.stringify({idx:lab.dataset.idx})})
    .then(function(r){return r.json();})
    .then(function(d){ if(d.ok){ lab.classList.toggle('done', d.xong); cb.checked=!!d.xong; capNhatTienDo(); } else { cb.checked=!cb.checked; } })
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
