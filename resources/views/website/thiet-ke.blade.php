<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Thiết kế tranh theo yêu cầu | DALI</title>
<meta name="description" content="Tải ảnh của bạn lên — AI DALI tăng cường & tạo bản đồ màu tô số. Mỗi máy 3 lượt miễn phí, đặt hàng nhận thêm 5 lượt.">
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet">
<style>[class^="ri-"],[class*=" ri-"]{vertical-align:-.125em;font-style:normal;line-height:1}
*{box-sizing:border-box;margin:0;padding:0;font-family:'Be Vietnam Pro',sans-serif}
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--gn:#C6F135;--bd:#C8E89A;--bd2:#A8D870;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
body{background:var(--bg);color:var(--tx);line-height:1.6}
nav{background:linear-gradient(175deg,#1C5200,#2D7A08,#3A9A12);height:64px;padding:0 5%;display:flex;align-items:center;justify-content:space-between}
.logo{font-size:24px;font-weight:900;letter-spacing:2px;color:#fff;text-decoration:none}.logo span{color:#C6F135}
nav a.back{color:rgba(255,255,255,.85);text-decoration:none;font-size:13px;font-weight:600}
.hero{background:linear-gradient(175deg,#1C5200,#2D7A08);color:#fff;text-align:center;padding:34px 5% 28px}
.hero h1{font-size:clamp(22px,3.4vw,32px);font-weight:900;margin-bottom:8px}
.hero p{font-size:14px;opacity:.85;max-width:560px;margin:0 auto}
.wrap{max-width:920px;margin:0 auto;padding:22px 5% 60px}
.card{background:#fff;border:1.5px solid var(--bd);border-radius:18px;padding:22px;margin-bottom:18px;box-shadow:0 8px 28px rgba(58,122,10,.06)}
.quota{display:flex;align-items:center;gap:10px;background:var(--gll);border:1.5px solid var(--bd);border-radius:50px;padding:9px 18px;font-size:13px;font-weight:700;color:var(--gd);margin-bottom:16px;width:fit-content}
.quota b{font-size:16px;color:var(--g)}
.drop{border:2px dashed var(--bd2);border-radius:14px;background:var(--gll);padding:30px;text-align:center;cursor:pointer;transition:all .2s}
.drop:hover{border-color:var(--g);background:#EAFFC8}
.drop .ic{font-size:40px;color:var(--bd2);margin-bottom:8px}
.drop .t{font-weight:700;color:var(--tx)}.drop .s{font-size:12px;color:var(--tx3);margin-top:4px}
.preview-img{max-width:100%;max-height:340px;border-radius:12px;border:1.5px solid var(--bd);margin-top:14px;display:block}
.btn{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:12px;padding:13px 26px;font-size:15px;font-weight:800;cursor:pointer;transition:all .2s;text-decoration:none}
.btn:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(107,191,31,.3)}
.btn:disabled{opacity:.5;cursor:not-allowed;transform:none;box-shadow:none}
.btn-out{background:#fff;color:var(--gd);border:1.5px solid var(--bd)}
.muted{font-size:12px;color:var(--tx3)}
.result-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.result-grid figure{margin:0}
.result-grid img{width:100%;border-radius:12px;border:1.5px solid var(--bd);background:var(--gll)}
.result-grid figcaption{font-size:12px;color:var(--tx2);font-weight:600;text-align:center;margin-top:6px}
.swatches{display:flex;flex-wrap:wrap;gap:6px;margin-top:12px}
.sw{width:30px;height:30px;border-radius:7px;border:1px solid var(--bd)}
.hidden{display:none}
/* Popup */
.modal{position:fixed;inset:0;background:rgba(0,0,0,.5);display:none;align-items:center;justify-content:center;z-index:1000;padding:18px}
.modal.show{display:flex}
.modal-box{background:#fff;border-radius:18px;max-width:420px;width:100%;padding:26px;text-align:center}
.modal-box .mi{font-size:42px;margin-bottom:10px}
.modal-box h3{font-size:19px;font-weight:900;color:var(--char);margin-bottom:8px}
.modal-box p{font-size:14px;color:var(--tx2);margin-bottom:18px;line-height:1.7}
.modal-actions{display:flex;gap:10px;justify-content:center}
.spinner{width:42px;height:42px;border:4px solid var(--gl);border-top-color:var(--g);border-radius:50%;animation:spin 1s linear infinite;margin:0 auto 14px}
@keyframes spin{to{transform:rotate(360deg)}}
.field{margin-bottom:12px;text-align:left}
.field label{display:block;font-size:13px;font-weight:700;margin-bottom:5px;color:var(--tx)}
.field input{width:100%;border:1.5px solid var(--bd);border-radius:10px;padding:11px 13px;font-size:14px;background:var(--gll);outline:none}
.field input:focus{border-color:var(--g);background:#fff}
@media(max-width:600px){.result-grid{grid-template-columns:1fr}}
/* So sánh Trước → Sau */
.ba{display:grid;grid-template-columns:1fr auto 1fr;gap:14px;align-items:center}
.ba .arrow{font-size:26px;color:var(--g);font-weight:900}
.ba figure{margin:0;text-align:center}
.ba img{width:100%;border-radius:12px;border:1.5px solid var(--bd);background:var(--gll);aspect-ratio:1/1;object-fit:contain}
.ba figcaption{font-size:12px;font-weight:700;margin-top:6px;color:var(--tx2)}
.ba .tag-before{color:var(--tx3)} .ba .tag-after{color:var(--g)}
@media(max-width:640px){.ba{grid-template-columns:1fr}.ba .arrow{transform:rotate(90deg)}}
/* Hook banner */
.hook{background:linear-gradient(135deg,#FFF7E0,#FFFBEF);border:1.5px solid #F0D98A;border-radius:14px;padding:14px 18px;margin:16px 0;font-size:14px;color:#8A6D1A;font-weight:600;text-align:center}
.hook b{color:#B8860B}
.order-cta{background:linear-gradient(135deg,var(--gll),#fff);border:2px solid var(--g);border-radius:16px;padding:20px;text-align:center;margin-top:8px}
.order-cta .big{font-size:17px;font-weight:900;color:var(--char);margin-bottom:6px}
.order-cta .sub{font-size:13px;color:var(--tx2);margin-bottom:14px}
.btn-lg{font-size:16px;padding:15px 34px}
/* Hướng dẫn 3 bước */
.section-t{text-align:center;font-size:22px;font-weight:900;color:var(--char);margin:6px 0 4px}
.section-s{text-align:center;font-size:13px;color:var(--tx3);margin-bottom:20px}
.steps{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
.step{background:#fff;border:1.5px solid var(--bd);border-radius:16px;padding:20px 16px;text-align:center}
.step .n{width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;font-weight:900;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;font-size:16px}
.step .ic{font-size:30px;margin-bottom:6px}
.step h4{font-size:15px;font-weight:800;color:var(--char);margin-bottom:5px}
.step p{font-size:12.5px;color:var(--tx2);line-height:1.6}
/* Trust + reviews */
.trust{display:flex;gap:10px;justify-content:center;flex-wrap:wrap;margin:18px 0}
.trust .b{background:#fff;border:1.5px solid var(--bd);border-radius:50px;padding:8px 16px;font-size:13px;font-weight:700;color:var(--gd)}
.trust .b b{color:var(--g);font-size:15px}
.reviews{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
.rv{background:#fff;border:1.5px solid var(--bd);border-radius:16px;padding:18px}
.rv .stars{color:#FFB400;font-size:14px;letter-spacing:1px;margin-bottom:8px}
.rv .txt{font-size:13px;color:var(--tx);line-height:1.7;margin-bottom:12px}
.rv .who{display:flex;align-items:center;gap:9px}
.rv .av{width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--gl),#CCEF90);display:flex;align-items:center;justify-content:center;font-weight:800;color:var(--gd);font-size:14px}
.rv .who .nm{font-size:13px;font-weight:800;color:var(--char)}.rv .who .ro{font-size:11px;color:var(--tx3)}
@media(max-width:760px){.steps,.reviews{grid-template-columns:1fr}}
</style>
</head>
<body>
<nav>
  <a href="{{ route('home') }}" class="logo">DAL<span>I</span></a>
  <a href="{{ route('home') }}" class="back"><i class="ri-arrow-left-line"></i> Trang chủ</a>
</nav>

<div class="hero">
  <h1><i class="ri-magic-line"></i> Biến ảnh của bạn thành tranh treo tường</h1>
  <p>Tải 1 tấm ảnh kỷ niệm lên — AI DALI biến nó thành <b>tranh tô màu số hóa độc bản</b> chỉ trong 1 phút. <b>Thử miễn phí {{ \App\Models\DesignQuota::FREE }} lần</b>, ưng ý rồi mới đặt. Món quà không đụng hàng cho người bạn thương ❤️</p>
</div>

<div class="wrap">
  <div class="quota">
    <i class="ri-flashlight-line"></i> Lượt tạo còn lại: <b id="remainBadge">…</b>
  </div>

  <div class="card">
    <input type="file" id="fileInput" accept="image/*" class="hidden">
    <div class="drop" id="dropZone">
      <div class="ic"><i class="ri-image-add-line"></i></div>
      <div class="t">Nhấn để chọn ảnh của bạn</div>
      <div class="s">JPG, PNG · ảnh càng rõ kết quả càng đẹp</div>
    </div>
    <img id="previewImg" class="preview-img hidden" alt="">
    <div style="margin-top:16px;display:flex;gap:10px;flex-wrap:wrap;align-items:center">
      <button class="btn" id="genBtn" disabled><i class="ri-sparkling-2-line"></i> Tạo kết quả AI</button>
      <span class="muted">Mỗi lần tạo dùng 1 lượt · AI mất ~20–60 giây.</span>
    </div>
  </div>

  <div class="card hidden" id="resultCard">
    <div style="font-weight:800;color:var(--char);margin-bottom:14px;font-size:16px"><i class="ri-checkbox-circle-line" style="color:var(--g)"></i> Tác phẩm của bạn đã sẵn sàng!</div>
    <div class="ba">
      <figure><img id="rOriginal" alt="Ảnh gốc"><figcaption class="tag-before">📷 Ảnh gốc của bạn</figcaption></figure>
      <div class="arrow">→</div>
      <figure><img id="rEnhanced" alt="Bản AI"><figcaption class="tag-after">✨ Bản DALI tăng cường AI</figcaption></figure>
    </div>
    <div style="margin-top:14px;text-align:center">
      <figure style="display:inline-block;max-width:62%;margin:0">
        <img id="rOutput" alt="Bản đồ màu" style="width:100%;border-radius:12px;border:1.5px solid var(--bd)">
        <figcaption style="font-size:12px;font-weight:700;color:var(--tx2);margin-top:6px">🎨 Bản đồ màu tô số (kèm theo bộ tranh)</figcaption>
      </figure>
    </div>
    <div class="swatches" id="rSwatches"></div>
    <div class="hook">🌟 <b>Đẹp đúng không?</b> Đây là bức tranh <b>độc nhất vô nhị</b> — không ai có tấm thứ hai. Đặt ngay để shop in &amp; giao bộ tranh tô màu tận nhà!</div>
    <div class="order-cta">
      <div class="big">🎁 Sở hữu bức tranh này ngay hôm nay</div>
      <div class="sub">Đặt hàng còn được <b>+{{ \App\Models\DesignQuota::ORDER_BONUS }} lượt tạo</b> để thử thêm những tấm ảnh khác.</div>
      <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap">
        <button class="btn btn-lg" id="orderBtn"><i class="ri-shopping-bag-line"></i> Đặt hàng ngay</button>
        <a class="btn btn-out" id="dlOutput" href="#" download target="_blank"><i class="ri-download-line"></i> Tải về xem</a>
      </div>
    </div>
  </div>
</div>

{{-- ───────── Hướng dẫn + niềm tin + review (chốt đơn) ───────── --}}
<div class="wrap" style="padding-top:6px">
  <div class="section-t">Chỉ 3 bước có tranh độc bản</div>
  <div class="section-s">Đơn giản đến mức ai cũng làm được — không cần biết vẽ</div>
  <div class="steps">
    <div class="step"><div class="n">1</div><div class="ic">📤</div><h4>Tải ảnh lên</h4><p>Ảnh người thân, thú cưng, phong cảnh… tấm nào bạn yêu thích nhất.</p></div>
    <div class="step"><div class="n">2</div><div class="ic">✨</div><h4>AI tạo bản thiết kế</h4><p>DALI tăng cường ảnh &amp; chia thành bản đồ màu tô số — xem trước miễn phí.</p></div>
    <div class="step"><div class="n">3</div><div class="ic">🚚</div><h4>Đặt &amp; nhận tranh</h4><p>Shop in lên canvas, kèm màu &amp; cọ, giao tận nhà. Bạn chỉ việc tô!</p></div>
  </div>

  <div class="trust">
    <div class="b">⭐ <b>4.9/5</b> · 2.000+ đánh giá</div>
    <div class="b">🎨 <b>10.000+</b> bức đã giao</div>
    <div class="b">🔁 Đổi trả <b>7 ngày</b></div>
    <div class="b">🚚 Giao toàn quốc</div>
  </div>

  <div class="section-t" style="margin-top:22px">Khách hàng nói gì về DALI?</div>
  <div class="section-s">Hàng nghìn món quà ý nghĩa đã được trao đi</div>
  <div class="reviews">
    <div class="rv">
      <div class="stars">★★★★★</div>
      <div class="txt">“Mình làm tranh từ ảnh cưới tặng vợ, bả khóc luôn 🥹. Bản AI nét hơn ảnh gốc, tô xong treo phòng khách ai tới cũng khen.”</div>
      <div class="who"><div class="av">M</div><div><div class="nm">Minh Tuấn</div><div class="ro">Hà Nội</div></div></div>
    </div>
    <div class="rv">
      <div class="stars">★★★★★</div>
      <div class="txt">“Tô màu cùng con cuối tuần, vui mà ý nghĩa. Bản đồ màu rõ ràng dễ tô, màu lên chuẩn. Sẽ đặt thêm tranh thú cưng.”</div>
      <div class="who"><div class="av">L</div><div><div class="nm">Lan Phương</div><div class="ro">TP.HCM</div></div></div>
    </div>
    <div class="rv">
      <div class="stars">★★★★★</div>
      <div class="txt">“Thử miễn phí thấy đẹp quá nên chốt luôn. Giao nhanh 3 ngày, đóng gói kỹ, có sẵn màu &amp; cọ. Quá đáng tiền!”</div>
      <div class="who"><div class="av">H</div><div><div class="nm">Hoàng Anh</div><div class="ro">Đà Nẵng</div></div></div>
    </div>
  </div>

  <div class="order-cta" style="margin-top:20px">
    <div class="big">Sẵn sàng tạo món quà độc bản của riêng bạn?</div>
    <div class="sub">Thử miễn phí {{ \App\Models\DesignQuota::FREE }} lần — không ưng, không mất gì.</div>
    <button class="btn btn-lg" onclick="document.getElementById('dropZone').scrollIntoView({behavior:'smooth'}); document.getElementById('fileInput').click();"><i class="ri-image-add-line"></i> Tải ảnh &amp; thử ngay</button>
  </div>
</div>

{{-- Popup xác nhận tạo --}}
<div class="modal" id="confirmModal">
  <div class="modal-box">
    <div class="mi">✨</div>
    <h3>Tạo kết quả bằng AI?</h3>
    <p>Bạn còn <b id="confirmRemain">0</b> lượt. Mỗi lần tạo sẽ <b>dùng 1 lượt</b> và mất khoảng 20–60 giây.<br>Tiếp tục nhé?</p>
    <div class="modal-actions">
      <button class="btn btn-out" onclick="closeModal('confirmModal')">Huỷ</button>
      <button class="btn" id="confirmGo"><i class="ri-sparkling-2-line"></i> Tạo ngay</button>
    </div>
  </div>
</div>

{{-- Popup đang xử lý --}}
<div class="modal" id="loadingModal">
  <div class="modal-box">
    <div class="spinner"></div>
    <h3>Đang xử lý bằng AI…</h3>
    <p>Vui lòng đợi 20–60 giây, đừng tắt trang.</p>
  </div>
</div>

{{-- Popup hết lượt / đặt hàng --}}
<div class="modal" id="orderModal">
  <div class="modal-box">
    <div class="mi">🛍️</div>
    <h3 id="orderTitle">Đặt hàng tranh thiết kế</h3>
    <p id="orderDesc">Để shop làm tranh cho bạn, điền thông tin dưới đây. Đặt hàng xong bạn còn được <b>+{{ \App\Models\DesignQuota::ORDER_BONUS }} lượt</b> tạo kết quả.</p>
    <div class="field"><label>Họ tên *</label><input type="text" id="oName" placeholder="Nguyễn Văn A"></div>
    <div class="field"><label>Số điện thoại *</label><input type="text" id="oPhone" placeholder="09xxxxxxxx"></div>
    <div class="field"><label>Địa chỉ</label><input type="text" id="oAddr" placeholder="Số nhà, đường, phường/xã, tỉnh"></div>
    <div class="modal-actions">
      <button class="btn btn-out" onclick="closeModal('orderModal')">Để sau</button>
      <button class="btn" id="orderSubmit"><i class="ri-check-line"></i> Gửi đơn</button>
    </div>
  </div>
</div>

<script>
const CSRF = document.querySelector('meta[name=csrf-token]').content;
const URLS = {
  quota: "{{ route('thiet-ke.quota') }}",
  gen:   "{{ route('thiet-ke.generate') }}",
  order: "{{ route('thiet-ke.order') }}",
};
// 1) Mã thiết bị (device id) — lưu localStorage
function deviceId(){
  let d = localStorage.getItem('dali_device');
  if(!d){ d = 'd' + Date.now().toString(36) + Math.random().toString(36).slice(2,12); localStorage.setItem('dali_device', d); }
  return d;
}
const DEVICE = deviceId();
let remaining = 0, lastResultUrl = '';

function openModal(id){ document.getElementById(id).classList.add('show'); }
function closeModal(id){ document.getElementById(id).classList.remove('show'); }

async function refreshQuota(){
  try{
    const r = await fetch(URLS.quota + '?device_id=' + encodeURIComponent(DEVICE));
    const d = await r.json();
    remaining = d.remaining ?? 0;
    document.getElementById('remainBadge').textContent = remaining + ' lượt';
  }catch(e){ document.getElementById('remainBadge').textContent = '—'; }
}
refreshQuota();

// 2) Chọn ảnh + xem trước
const fileInput = document.getElementById('fileInput');
const dropZone  = document.getElementById('dropZone');
const previewImg= document.getElementById('previewImg');
const genBtn    = document.getElementById('genBtn');
dropZone.addEventListener('click', ()=>fileInput.click());
fileInput.addEventListener('change', ()=>{
  const f = fileInput.files[0]; if(!f) return;
  previewImg.src = URL.createObjectURL(f); previewImg.classList.remove('hidden');
  genBtn.disabled = false;
});

// 3) Tạo kết quả -> popup xác nhận
genBtn.addEventListener('click', ()=>{
  if(!fileInput.files[0]){ alert('Vui lòng chọn ảnh.'); return; }
  if(remaining <= 0){ showOutOfQuota(); return; }
  document.getElementById('confirmRemain').textContent = remaining;
  openModal('confirmModal');
});

document.getElementById('confirmGo').addEventListener('click', async ()=>{
  closeModal('confirmModal');
  openModal('loadingModal');
  const fd = new FormData();
  fd.append('image', fileInput.files[0]);
  fd.append('device_id', DEVICE);
  fd.append('enhance', '1');
  try{
    const r = await fetch(URLS.gen, {method:'POST', headers:{'X-CSRF-TOKEN':CSRF}, body:fd});
    const d = await r.json();
    closeModal('loadingModal');
    if(!d.ok){
      if(d.reason === 'no_quota'){ showOutOfQuota(); }
      else alert(d.msg || 'Có lỗi xảy ra, thử lại sau.');
      return;
    }
    remaining = d.remaining ?? remaining;
    document.getElementById('remainBadge').textContent = remaining + ' lượt';
    showResult(d.result);
  }catch(e){ closeModal('loadingModal'); alert('Lỗi kết nối, thử lại sau.'); }
});

function showResult(res){
  const card = document.getElementById('resultCard');
  document.getElementById('rOriginal').src = previewImg.src || res.original || '';
  document.getElementById('rEnhanced').src = res.enhanced || res.original || '';
  document.getElementById('rOutput').src   = res.img_output || '';
  lastResultUrl = res.img_output || '';
  document.getElementById('dlOutput').href = res.img_output || '#';
  // swatches từ colors (mảng trang [[stt,hex,dali,%],...])
  const sw = document.getElementById('rSwatches'); sw.innerHTML='';
  let colors = res.colors || [];
  let flat = []; colors.forEach(p => Array.isArray(p) && p.forEach(c => flat.push(c)));
  flat.slice(0,30).forEach(c=>{ const d=document.createElement('div'); d.className='sw'; d.style.background=(c[1]||'#fff'); d.title=(c[2]||''); sw.appendChild(d); });
  card.classList.remove('hidden');
  card.scrollIntoView({behavior:'smooth'});
}

function showOutOfQuota(){
  document.getElementById('orderTitle').textContent = 'Bạn đã hết lượt miễn phí';
  document.getElementById('orderDesc').innerHTML = 'Đặt hàng tranh thiết kế để shop làm cho bạn — và nhận thêm <b>+{{ \App\Models\DesignQuota::ORDER_BONUS }} lượt</b> tạo kết quả.';
  openModal('orderModal');
}
document.getElementById('orderBtn').addEventListener('click', ()=>{
  document.getElementById('orderTitle').textContent = 'Đặt hàng tranh thiết kế';
  openModal('orderModal');
});

// 4) Gửi đơn -> +5 lượt
document.getElementById('orderSubmit').addEventListener('click', async ()=>{
  const name = document.getElementById('oName').value.trim();
  const phone= document.getElementById('oPhone').value.trim();
  if(!name || !phone){ alert('Vui lòng nhập họ tên và số điện thoại.'); return; }
  const fd = new FormData();
  fd.append('device_id', DEVICE);
  fd.append('customer_name', name);
  fd.append('customer_phone', phone);
  fd.append('customer_address', document.getElementById('oAddr').value.trim());
  fd.append('result_url', lastResultUrl);
  try{
    const r = await fetch(URLS.order, {method:'POST', headers:{'X-CSRF-TOKEN':CSRF}, body:fd});
    const d = await r.json();
    if(!d.ok){ alert('Gửi đơn thất bại, thử lại.'); return; }
    closeModal('orderModal');
    remaining = d.remaining ?? remaining;
    document.getElementById('remainBadge').textContent = remaining + ' lượt';
    alert('✅ Đã nhận đơn ' + d.code + '! Shop sẽ liên hệ sớm. Bạn được +' + d.bonus + ' lượt tạo kết quả.');
  }catch(e){ alert('Lỗi kết nối, thử lại sau.'); }
});
</script>
</body>
</html>
