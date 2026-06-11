<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Bảng giá Thiết kế | DALI Admin</title>
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
.status{font-size:12px;font-weight:800;color:var(--g)}
table{width:100%;border-collapse:collapse}
th,td{padding:18px 16px;text-align:left;border-bottom:1px solid #EEF5E5}
thead th{font-size:15px;font-weight:900;color:#0F172A;background:#fff}
.size-label{font-size:16px;font-weight:900;color:#0F172A}
.size-note{font-size:11px;color:#94A3B8;font-weight:600;margin-top:2px}
.price{font-size:16px;font-weight:900;color:#0F172A;white-space:nowrap}
[contenteditable]{outline:none;border-radius:6px;padding:2px 6px;transition:background .15s, box-shadow .15s;cursor:text;min-width:40px;display:inline-block}
[contenteditable]:hover{background:var(--gll);box-shadow:0 0 0 1.5px var(--bd)}
[contenteditable]:focus{background:#fff;box-shadow:0 0 0 2px var(--g)}
.saved-flash{animation:flash 1s}
@keyframes flash{0%{background:#D8F7C0}100%{background:transparent}}
.btn{display:inline-flex;align-items:center;gap:6px;background:#fff;border:1.5px solid var(--bd);color:var(--gd);font-size:12px;font-weight:800;padding:8px 14px;border-radius:9px;cursor:pointer}
.btn:hover{background:var(--gll)}
.del{opacity:0;border:none;background:#FEE2E2;color:#DC2626;font-weight:900;border-radius:6px;width:20px;height:20px;cursor:pointer;font-size:11px;line-height:1;margin-left:6px;vertical-align:middle}
th:hover .del, tr:hover .del{opacity:1}
.wrap-scroll{overflow-x:auto}
</style>
</head>
<body>
<div class="layout">
  @include('admin.partials.sidebar')
  <div class="main">
    <div class="tb">
      <div>
        <div class="tb-bc"><a href="{{ route('admin.dashboard') }}">Admin</a> › <b>Bảng giá Thiết kế</b></div>
        <div class="tb-title">Bảng giá trang Thiết kế theo yêu cầu</div>
      </div>
      <a href="{{ route('thiet-ke') }}" target="_blank" class="btn">👁️ Xem trang khách</a>
    </div>
    <div class="cnt">
      <div class="card">
        <div class="card-head">
          <div>
            <div class="card-title">💰 Kích thước × Số màu</div>
            <div class="hint">Bấm vào <b>bất kỳ ô nào</b> (giá, kích thước, ghi chú, số màu) để sửa — tự lưu khi bấm ra ngoài.</div>
          </div>
          <div class="status" id="status">Sẵn sàng</div>
        </div>
        <div class="wrap-scroll">
          <table id="priceTable"></table>
        </div>
        <div style="padding:14px 22px;display:flex;gap:10px;flex-wrap:wrap;border-top:1px solid var(--gl)">
          <button class="btn" onclick="addRow()">➕ Thêm kích thước</button>
          <button class="btn" onclick="addCol()">➕ Thêm cột màu</button>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
const CSRF = document.querySelector('meta[name=csrf-token]').content;
let P = @json($pricing);

function fmt(n){ return n.toLocaleString('vi-VN') + 'đ'; }
function digits(s){ const d = (s||'').replace(/[^0-9]/g,''); return d ? parseInt(d,10) : 0; }

function render(){
  const t = document.getElementById('priceTable');
  let h = '<thead><tr><th style="width:180px">Kích thước</th>';
  P.colors.forEach((c,j)=>{
    h += '<th><span contenteditable data-type="color" data-j="'+j+'">'+c+'</span> màu'
       + (P.colors.length>1 ? '<button class="del" title="Xoá cột" onclick="delCol('+j+')">✕</button>' : '') + '</th>';
  });
  h += '</tr></thead><tbody>';
  P.sizes.forEach((s,i)=>{
    h += '<tr><td><div class="size-label"><span contenteditable data-type="label" data-i="'+i+'">'+s.label+'</span>'
       + (P.sizes.length>1 ? '<button class="del" title="Xoá dòng" onclick="delRow('+i+')">✕</button>' : '') + '</div>'
       + '<div class="size-note"><span contenteditable data-type="note" data-i="'+i+'">'+(s.note||'…')+'</span></div></td>';
    s.prices.forEach((v,j)=>{
      h += '<td><span class="price" contenteditable data-type="price" data-i="'+i+'" data-j="'+j+'">'+fmt(v)+'</span></td>';
    });
    h += '</tr>';
  });
  h += '</tbody>';
  t.innerHTML = h;
  t.querySelectorAll('[contenteditable]').forEach(el=>{
    el.addEventListener('blur', ()=>onEdit(el));
    el.addEventListener('keydown', e=>{ if(e.key==='Enter'){ e.preventDefault(); el.blur(); } });
  });
}

function onEdit(el){
  const ty = el.dataset.type, i = +el.dataset.i, j = +el.dataset.j;
  if(ty==='price'){ P.sizes[i].prices[j] = digits(el.textContent); el.textContent = fmt(P.sizes[i].prices[j]); }
  else if(ty==='color'){ P.colors[j] = Math.max(1, digits(el.textContent)); el.textContent = P.colors[j]; }
  else if(ty==='label'){ P.sizes[i].label = el.textContent.trim() || 'Kích thước'; el.textContent = P.sizes[i].label; }
  else if(ty==='note'){ P.sizes[i].note = el.textContent.trim().replace('…',''); }
  el.classList.remove('saved-flash'); void el.offsetWidth; el.classList.add('saved-flash');
  save();
}

let saveTimer=null;
function save(){
  clearTimeout(saveTimer);
  saveTimer = setTimeout(async ()=>{
    const st = document.getElementById('status'); st.textContent = 'Đang lưu…';
    try{
      const fd = new FormData(); fd.append('pricing', JSON.stringify(P));
      const r = await fetch("{{ route('admin.thietke.pricing.save') }}", {method:'POST', headers:{'X-CSRF-TOKEN':CSRF}, body:fd});
      const d = await r.json();
      st.textContent = d.ok ? ('✅ Đã lưu lúc ' + new Date().toLocaleTimeString('vi-VN')) : ('⚠️ ' + (d.msg||'Lỗi lưu'));
    }catch(e){ st.textContent = '⚠️ Mất kết nối — thử lại'; }
  }, 350);
}

function addRow(){
  const last = P.sizes[P.sizes.length-1];
  P.sizes.push({label:'Khổ mới', note:last.note||'Đã gồm Cấp 1', prices: last.prices.map(v=>v+50000)});
  render(); save();
}
function addCol(){
  P.colors.push(P.colors[P.colors.length-1]+6);
  P.sizes.forEach(s=>s.prices.push(s.prices[s.prices.length-1]+45000));
  render(); save();
}
function delRow(i){ if(!confirm('Xoá dòng "'+P.sizes[i].label+'"?'))return; P.sizes.splice(i,1); render(); save(); }
function delCol(j){ if(!confirm('Xoá cột '+P.colors[j]+' màu?'))return; P.colors.splice(j,1); P.sizes.forEach(s=>s.prices.splice(j,1)); render(); save(); }

render();
</script>
</body>
</html>
