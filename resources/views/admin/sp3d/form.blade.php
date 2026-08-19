<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $sp ? 'Sửa' : 'Thêm' }} sản phẩm 3D | DALI Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--pk:#FF8FB1;--bd:#C8E89A;--bd2:#A8D870;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:var(--tx)}
.topbar{background:#fff;border-bottom:2px solid var(--gl);height:64px;padding:0 24px;display:flex;align-items:center;justify-content:space-between}
.tb-bc{font-size:10px;color:var(--tx3)}.tb-bc b{color:var(--g)}
.tb-title{font-size:18px;font-weight:900;background:linear-gradient(90deg,#2D7A08,var(--g));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-top:2px}
.back{font-size:12px;color:var(--tx2);text-decoration:none;font-weight:700}
.cnt{flex:1;overflow-y:auto;padding:22px 24px}
.wrap{max-width:1000px;margin:0 auto}
.err{background:#FFF0F0;border-left:3px solid #EF4444;border-radius:9px;padding:12px 16px;margin-bottom:18px;font-size:13px;color:#B91C1C}
.err ul{margin:4px 0 0 16px}
.sec{background:#fff;border-radius:16px;border:1.5px solid var(--bd);box-shadow:0 3px 18px rgba(58,122,10,.07);padding:20px 22px;margin-bottom:18px}
.sec h2{font-size:15px;font-weight:900;color:var(--char);margin-bottom:4px}
.sec .hint{font-size:12px;color:var(--tx3);margin-bottom:16px}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:14px 16px;align-items:end;max-width:900px}
.f{display:flex;flex-direction:column;gap:5px}
.f.rong{grid-column:1/-1}
.f.hep{max-width:150px}
.f>span{font-size:11px;font-weight:700;color:var(--tx2);letter-spacing:.3px;line-height:1.4}
.f>span i{font-style:normal;font-weight:500;color:var(--tx3)}
.f input,.f select,.f textarea{width:100%;border:1.5px solid var(--bd);border-radius:9px;padding:10px 13px;font-size:13px;background:var(--gll);color:var(--tx);font-family:'Be Vietnam Pro',sans-serif;outline:none;transition:all .2s}
.f input:focus,.f select:focus,.f textarea:focus{border-color:var(--g);background:#fff}
.f textarea{resize:vertical;min-height:70px}
.tick{display:flex;gap:10px;align-items:flex-start;font-size:13px;background:var(--gll);border:1px solid var(--bd);border-radius:10px;padding:11px 13px;margin-bottom:9px;cursor:pointer}
.tick input{accent-color:var(--g);margin-top:2px;flex:none;width:16px;height:16px}
.tick b{font-weight:700}.tick small{color:var(--tx3);display:block;font-size:11.5px}
.imgs{display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:10px}
.imgo{position:relative;border:1.5px solid var(--bd);border-radius:12px;overflow:hidden;aspect-ratio:1/1;background:var(--gll)}
.imgo img{width:100%;height:100%;object-fit:cover;display:block}
.imgo .bia{position:absolute;top:6px;left:6px;background:var(--g);color:#fff;font-size:9px;font-weight:800;padding:2px 7px;border-radius:20px}
.imgo .ops{position:absolute;bottom:0;left:0;right:0;display:flex;justify-content:space-between;background:rgba(28,58,10,.72);padding:3px 5px}
.imgo .ops button{border:none;background:transparent;color:#fff;font-size:13px;cursor:pointer;padding:2px 5px;line-height:1}
.imgo .ops .del{color:#FFB3B3}
.them{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px;border:1.5px dashed var(--bd2);border-radius:12px;aspect-ratio:1/1;color:var(--tx3);cursor:pointer;font-size:22px;background:transparent}
.them span{font-size:11px;font-weight:700}
.them:hover{border-color:var(--g);color:var(--g);background:var(--gll)}
.bar{display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-top:6px}
.btn-save{padding:12px 26px;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;font-size:14px;font-weight:800;border:none;border-radius:10px;cursor:pointer;box-shadow:0 3px 12px rgba(107,191,31,.3)}
.btn-save:hover{transform:translateY(-1px)}
.btn-ghost{padding:12px 22px;background:#fff;color:var(--tx2);border:1.5px solid var(--bd);border-radius:10px;font-size:13px;font-weight:700;text-decoration:none}
.btn-xem{padding:8px 16px;background:var(--gl);color:var(--gd);border:1px solid var(--bd2);border-radius:9px;font-size:12px;font-weight:700;text-decoration:none}
/* Phân loại hàng kiểu Shopee */
.vg-group{border:1.5px solid var(--bd);border-radius:12px;padding:13px;margin-bottom:11px;background:var(--gll)}
.vg-ghd{display:flex;gap:8px;align-items:center;margin-bottom:9px}
.vg-ghd>label{font-size:11px;font-weight:700;color:var(--tx2);flex:none}
.vg-in{width:100%;border:1.5px solid var(--bd);border-radius:9px;padding:9px 12px;font-size:13px;background:#fff;color:var(--tx);font-family:'Be Vietnam Pro',sans-serif;outline:none;transition:border .2s}
.vg-in:focus{border-color:var(--g)}
.vg-opt{display:flex;gap:6px;align-items:center;margin:6px 0}
.vg-opt .vg-in{flex:1}
.vg-mini{border:1.5px solid var(--bd);background:#fff;color:var(--tx2);border-radius:8px;min-width:32px;height:36px;font-size:14px;cursor:pointer;flex:none;line-height:1;transition:all .15s}
.vg-mini:hover{border-color:var(--g);color:var(--gd)}
.vg-mini.del:hover{border-color:#EF4444;color:#EF4444}
.vg-btn{border:1.5px dashed var(--bd2);background:#fff;color:var(--gd);border-radius:9px;padding:9px 14px;font-size:13px;font-weight:700;cursor:pointer;transition:all .15s}
.vg-btn:hover{background:var(--gll);border-color:var(--g)}
.vg-addopt{margin-top:3px;padding:7px 12px;font-size:12.5px}
.vg-subhd{font-size:13px;font-weight:800;color:var(--char);margin:16px 0 8px}
.vg-table{width:100%;border-collapse:collapse;font-size:12.5px;min-width:420px}
.vg-table th,.vg-table td{border:1px solid var(--bd);padding:7px 10px;text-align:left;color:var(--tx)}
.vg-table th{background:var(--gll);color:var(--gd);font-weight:800;white-space:nowrap}
.vg-table td .vg-in{width:120px;padding:7px 9px}
.vg-note{font-size:11.5px;color:var(--tx3);margin-top:7px}
</style>
</head>
<body>
<div style="display:flex;min-height:100vh">
@include('admin.partials.sidebar')
<div style="flex:1;display:flex;flex-direction:column;overflow:hidden">
  <div class="topbar">
    <div><div class="tb-bc">Admin › Xưởng in 3D › <b>{{ $sp ? 'Sửa sản phẩm' : 'Thêm sản phẩm' }}</b></div>
      <div class="tb-title">{{ $sp ? $sp->ten : 'Thêm sản phẩm 3D' }}</div></div>
    <div style="display:flex;gap:10px;align-items:center">
      @if($sp)<a href="https://3d.tranhdali.vn/san-pham/{{ $sp->slug }}" target="_blank" rel="noopener" class="btn-xem">👁️ Xem trên web ↗</a>@endif
      <a href="{{ route('admin.sp3d.index') }}" class="back">← Tất cả sản phẩm 3D</a>
    </div>
  </div>
  <div class="cnt"><div class="wrap">

  @if($errors->any())
    <div class="err"><b>Chưa lưu được:</b><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  @php
    $anh = $sp ? ($sp->anh ?: []) : [];
    $motaText = $sp ? collect($sp->mota ?: [])->implode("\n") : '';
    // Seed trình sửa phân loại: ưu tiên old() khi lưu lỗi, rồi tới dữ liệu đã lưu.
    // Fallback: nếu sản phẩm có variants cũ mà chưa có variant_groups thì dựng 1 nhóm.
    if (old('variant_groups_json')) {
        $vgSeed = json_decode(old('variant_groups_json'), true) ?: ['groups'=>[],'rows'=>[]];
    } elseif ($sp && is_array($sp->variant_groups) && !empty($sp->variant_groups['groups'])) {
        $vgSeed = $sp->variant_groups;
    } elseif ($sp && ($vs = $sp->variants ?: []) && count($vs)) {
        $opts = []; $rows = [];
        foreach (array_values($vs) as $i => $v) {
            $opts[] = $v['ten'] ?? '';
            $rows[] = ['combo'=>[$i],'ten'=>$v['ten']??'','gia'=>(int)($v['gia']??0),'kho'=>null];
        }
        $vgSeed = ['groups'=>[['ten'=>'Chọn phiên bản','options'=>$opts]],'rows'=>$rows];
    } else {
        $vgSeed = ['groups'=>[],'rows'=>[]];
    }
  @endphp

  <form method="POST" action="{{ $sp ? route('admin.sp3d.update',$sp) : route('admin.sp3d.store') }}" enctype="multipart/form-data" id="spForm">
    @csrf
    @if($sp)@method('PUT')@endif
    <input type="hidden" name="anh_keep" id="anhKeep" value='@json($anh)'>

    <div class="sec">
      <h2>📷 Ảnh sản phẩm</h2>
      <div class="hint">Ảnh <b>đầu tiên là ảnh bìa</b> — hiện ngoài trang chủ và trong danh sách. Dùng ◀ ▶ để đổi thứ tự, ✕ để xoá. Nên chụp nền sáng, vuông.</div>
      <div class="imgs" id="imgGrid"></div>
      <input type="file" name="anh_moi[]" id="anhMoi" accept="image/*" multiple hidden>
    </div>

    <div class="sec">
      <h2>📝 Thông tin cơ bản</h2>
      <div class="grid">
        <label class="f rong"><span>Tên sản phẩm</span><input name="ten" value="{{ old('ten', $sp->ten ?? '') }}" placeholder="Bộ lắp ghép mô hình phân tử" required></label>
        <label class="f"><span>Đường dẫn trên web <i>— để trống sẽ tự tạo</i></span><input name="slug" value="{{ old('slug', $sp->slug ?? '') }}" placeholder="mo-hinh-phan-tu"></label>
        <label class="f"><span>Nhóm hàng</span><input name="cat" value="{{ old('cat', $sp->cat ?? '') }}" placeholder="Góc học tập"></label>
        <label class="f"><span>Nhãn góc ảnh <i>— để trống nếu không cần</i></span><input name="nhan" value="{{ old('nhan', $sp->nhan ?? '') }}" placeholder="BÁN CHẠY NHẤT"></label>
        <label class="f rong"><span>Mô tả ngắn <i>— một câu hiện dưới tên sản phẩm</i></span><input name="mo_ta_ngan" value="{{ old('mo_ta_ngan', $sp->mo_ta_ngan ?? '') }}" placeholder="Nền + 40 thẻ môn học cắm rời như LEGO. Bé tự đổi lịch mỗi tuần."></label>
      </div>
    </div>

    <div class="sec">
      <h2>💰 Giá bán</h2>
      <div class="grid">
        <label class="f"><span>Giá bán (đ)</span><input name="gia" inputmode="numeric" value="{{ old('gia', $sp->gia ?? 0) }}"></label>
        <label class="f"><span>Tồn kho <i>— 0 là không theo dõi</i></span><input name="kho" inputmode="numeric" value="{{ old('kho', $sp->kho ?? 0) }}"></label>
        <label class="f"><span>Thứ tự hiện trên web <i>— nhỏ đứng trước</i></span><input name="thu_tu" inputmode="numeric" value="{{ old('thu_tu', $sp->thu_tu ?? 0) }}"></label>
      </div>
    </div>

    <div class="sec">
      <h2>🏷️ Phân loại hàng <i style="font-weight:500;color:var(--tx3);font-size:12px">— để trống nếu bán một mức giá</i></h2>
      <div class="hint">Mỗi tùy chọn một ô. Ví dụ Bookmark: nhóm <b>Chọn phiên bản</b> gồm 4 màu lẻ + Set 4. Thêm nhóm thứ 2 (vd Kích thước) sẽ tự sinh bảng tổ hợp. Giá của mỗi lựa chọn nhập ở bảng bên dưới.</div>
      <div id="vgGroups"></div>
      <button type="button" class="vg-btn" id="vgAddGroup">＋ Thêm nhóm phân loại</button>

      <div id="vgTableWrap" hidden>
        <div class="vg-subhd">Danh sách phân loại hàng</div>
        <div style="overflow-x:auto"><table class="vg-table">
          <thead id="vgThead"></thead><tbody id="vgTbody"></tbody>
        </table></div>
        <div class="vg-note">Kho để trống = không theo dõi tồn (in theo đơn). Phí ship tính theo bậc, không theo cân nặng.</div>
      </div>

      <input type="hidden" name="variant_groups_json" id="vgJson">
    </div>

    <div class="sec">
      <h2>📄 Mô tả chi tiết</h2>
      <div class="hint">Mỗi dòng một ý — hiện thành danh sách gạch đầu dòng trên trang sản phẩm.</div>
      <textarea name="mota_text" rows="6" style="width:100%;border:1.5px solid var(--bd);border-radius:9px;padding:11px 13px;font-size:13px;background:var(--gll);font-family:'Be Vietnam Pro',sans-serif;resize:vertical" placeholder="185 chi tiết: 20 carbon, 32 hydro…&#10;Mô hình dạng bi và thanh nối, dựng đúng góc chuẩn VSEPR&#10;Nhựa an toàn cho bé từ 8 tuổi">{{ old('mota_text', $motaText) }}</textarea>
    </div>

    <div class="sec">
      <h2>⚙️ Cách bán</h2>
      <div class="grid" style="margin-bottom:12px">
        <label class="f"><span>Điểm sao <i>— ví dụ 4.9</i></span><input name="sao" inputmode="decimal" value="{{ old('sao', $sp ? rtrim(rtrim((string)$sp->sao,'0'),'.') : '') }}"></label>
        <label class="f"><span>Số lượt đã bán</span><input name="da_ban" inputmode="numeric" value="{{ old('da_ban', $sp->da_ban ?? 0) }}"></label>
        <label class="f"><span>Chính sách thanh toán</span>
          <select name="payment_policy">
            @php $pp = old('payment_policy', $sp->payment_policy ?? 'cod_or_prepaid_10'); @endphp
            <option value="cod_or_prepaid_10" {{ $pp=='cod_or_prepaid_10'?'selected':'' }}>Nhận hàng trả tiền (CK trước giảm 10%)</option>
            <option value="deposit_50" {{ $pp=='deposit_50'?'selected':'' }}>Đặt cọc 50% (hàng in riêng)</option>
          </select></label>
        <label class="f"><span>Cách gửi hàng</span>
          <select name="shipping_class">
            @php $sc = old('shipping_class', $sp->shipping_class ?? 'standard'); @endphp
            <option value="standard" {{ $sc=='standard'?'selected':'' }}>Tiêu chuẩn</option>
            <option value="phieu" {{ $sc=='phieu'?'selected':'' }}>Phong bì phiếu lẻ</option>
          </select></label>
      </div>
      <label class="tick"><input type="checkbox" name="khac_ten" value="1" {{ old('khac_ten', $sp->khac_ten ?? false) ? 'checked' : '' }}><span><b>Có khắc tên miễn phí</b><small>Hiện ô nhập tên khi khách đặt</small></span></label>
      <label class="tick"><input type="checkbox" name="dat_lam" value="1" {{ old('dat_lam', $sp->dat_lam ?? false) ? 'checked' : '' }}><span><b>Hàng đặt làm theo yêu cầu</b><small>Khách phải cọc 50% và duyệt mẫu trước khi in</small></span></label>
      <label class="tick"><input type="checkbox" name="hien" value="1" {{ old('hien', $sp->hien ?? true) ? 'checked' : '' }}><span><b>Đang bán</b><small>Bỏ tích để ẩn khỏi web</small></span></label>
    </div>

    <div class="sec"><div class="bar">
      <button type="submit" class="btn-save">{{ $sp ? 'Lưu sản phẩm' : 'Thêm sản phẩm' }}</button>
      <a href="{{ route('admin.sp3d.index') }}" class="btn-ghost">Huỷ</a>
    </div></div>
  </form>

  </div></div>
</div>
</div>

<script>
(function(){
  var STORE = @json(asset('storage')) + '/';
  var keepEl = document.getElementById('anhKeep');
  var grid   = document.getElementById('imgGrid');
  var fileEl = document.getElementById('anhMoi');
  var moiFiles = [];            // ảnh mới chọn nhưng chưa lưu (đối tượng File)

  function keep(){ try{ return JSON.parse(keepEl.value)||[]; }catch(e){ return []; } }
  function setKeep(a){ keepEl.value = JSON.stringify(a); }

  function ve(){
    var a = keep();
    grid.innerHTML = '';
    a.forEach(function(path, i){
      grid.appendChild(oCu(path, i, a.length));
    });
    moiFiles.forEach(function(f, i){ grid.appendChild(oMoi(f, i)); });
    grid.appendChild(nutThem());
    dongBoInput();
  }

  function oCu(path, i, n){
    var d = document.createElement('div'); d.className='imgo';
    d.innerHTML = '<img src="'+STORE+path+'">'
      + (i===0 ? '<i class="bia">Ảnh bìa</i>' : '')
      + '<div class="ops">'
      +   '<span>'+(i>0?'<button type="button" data-mv="'+i+'" data-dir="-1">◀</button>':'')
      +          (i<n-1?'<button type="button" data-mv="'+i+'" data-dir="1">▶</button>':'')+'</span>'
      +   '<button type="button" class="del" data-del="'+i+'">✕</button>'
      + '</div>';
    return d;
  }
  function oMoi(file, idx){
    var d = document.createElement('div'); d.className='imgo';
    var url = URL.createObjectURL(file);
    d.innerHTML = '<img src="'+url+'"><i class="bia" style="background:#F59E0B">Mới</i>'
      + '<div class="ops"><span></span><button type="button" class="del" data-delmoi="'+idx+'">✕</button></div>';
    return d;
  }
  function nutThem(){
    var d = document.createElement('div'); d.className='them';
    d.innerHTML = '＋<span>Thêm ảnh</span>';
    d.onclick = function(){ fileEl.click(); };
    return d;
  }

  // Đồng bF các file mới vào input để form gửi lên
  function dongBoInput(){
    var dt = new DataTransfer();
    moiFiles.forEach(function(f){ dt.items.add(f); });
    fileEl.files = dt.files;
  }

  grid.addEventListener('click', function(e){
    var t = e.target;
    if(t.dataset.del !== undefined){ var a=keep(); a.splice(+t.dataset.del,1); setKeep(a); ve(); }
    else if(t.dataset.delmoi !== undefined){ moiFiles.splice(+t.dataset.delmoi,1); ve(); }
    else if(t.dataset.mv !== undefined){
      var a=keep(), i=+t.dataset.mv, j=i+(+t.dataset.dir);
      if(j>=0 && j<a.length){ var tmp=a[i]; a[i]=a[j]; a[j]=tmp; setKeep(a); ve(); }
    }
  });
  fileEl.addEventListener('change', function(){
    Array.prototype.forEach.call(fileEl.files, function(f){ moiFiles.push(f); });
    ve();
  });

  ve();
})();
</script>

<script>
/* ===== Phân loại hàng kiểu Shopee =====
   Sinh tổ hợp deterministic (nhóm 0 vòng ngoài) — KHỚP hệt compileVariantGroups() ở PHP,
   nên bảng xem trước và mảng variants lưu ra luôn cùng thứ tự. */
(function(){
  var seed = @json($vgSeed);
  var st = (seed && Array.isArray(seed.groups)) ? seed : {groups:[],rows:[]};
  st.groups = st.groups || []; st.rows = st.rows || [];

  var elGroups = document.getElementById('vgGroups');
  var elAddG   = document.getElementById('vgAddGroup');
  var elTblW   = document.getElementById('vgTableWrap');
  var elThead  = document.getElementById('vgThead');
  var elTbody  = document.getElementById('vgTbody');
  var elJson   = document.getElementById('vgJson');
  var giaEl    = document.querySelector('[name="gia"]');

  function base(){ return giaEl ? (parseInt(giaEl.value||'0',10)||0) : 0; }
  function esc(s){ return String(s==null?'':s).replace(/[&<>"]/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c];}); }
  function opts(g){ return (g.options||[]).map(function(o){return String(o||'').trim();}); }

  function combos(){
    if(!st.groups.length) return [];
    if(st.groups.some(function(g){ return !opts(g).filter(Boolean).length; })) return [];
    var out=[[]];
    st.groups.forEach(function(g){
      var os=g.options||[], nx=[];
      out.forEach(function(c){ os.forEach(function(_,i){ nx.push(c.concat(i)); }); });
      out=nx;
    });
    return out;
  }

  function rebuildRows(){
    var old={}; (st.rows||[]).forEach(function(r){ old[(r.combo||[]).join('-')]=r; });
    st.rows = combos().map(function(combo){
      var label=combo.map(function(oi,gi){ return String(st.groups[gi].options[oi]||'').trim(); });
      var key=combo.join('-'), prev=old[key]||{};
      return {combo:combo, ten:label.join(' · '),
              gia:(prev.gia!=null?prev.gia:base()),
              kho:(prev.kho!=null?prev.kho:null)};
    });
  }

  function serialize(){ elJson.value = JSON.stringify(st); }

  function renderGroups(){
    elGroups.innerHTML = st.groups.map(function(g,gi){
      var rows=(g.options||[]).map(function(o,oi){
        return '<div class="vg-opt">'
          + '<input class="vg-in" data-g="'+gi+'" data-o="'+oi+'" placeholder="Tên lựa chọn (vd: Đỏ)" value="'+esc(o)+'">'
          + '<button type="button" class="vg-mini" data-up="'+gi+','+oi+'" title="Lên">▲</button>'
          + '<button type="button" class="vg-mini" data-dn="'+gi+','+oi+'" title="Xuống">▼</button>'
          + '<button type="button" class="vg-mini del" data-delo="'+gi+','+oi+'" title="Xoá lựa chọn">✕</button>'
          + '</div>';
      }).join('');
      return '<div class="vg-group">'
        + '<div class="vg-ghd"><label>Tên nhóm</label>'
        + '<input class="vg-in" data-gname="'+gi+'" placeholder="vd: Chọn phiên bản / Màu / Kích thước" value="'+esc(g.ten||'')+'">'
        + '<button type="button" class="vg-mini del" data-delg="'+gi+'" title="Xoá nhóm">🗑</button></div>'
        + rows
        + '<button type="button" class="vg-btn vg-addopt" data-addo="'+gi+'">＋ Thêm lựa chọn</button>'
        + '</div>';
    }).join('');
    elAddG.style.display = st.groups.length>=2 ? 'none' : '';
  }

  function renderTable(){
    var rows=st.rows||[];
    if(!rows.length){ elTblW.hidden=true; return; }
    elTblW.hidden=false;
    elThead.innerHTML='<tr>'
      + st.groups.map(function(g){ return '<th>'+esc(g.ten||'Phân loại')+'</th>'; }).join('')
      + '<th>Giá (₫)</th><th>Kho</th></tr>';
    elTbody.innerHTML=rows.map(function(r,ri){
      var cells=(r.combo||[]).map(function(oi,gi){ return '<td>'+esc(st.groups[gi].options[oi])+'</td>'; }).join('');
      return '<tr>'+cells
        + '<td><input class="vg-in" type="number" min="0" step="1000" data-price="'+ri+'" value="'+(r.gia!=null?r.gia:'')+'"></td>'
        + '<td><input class="vg-in" type="number" min="0" step="1" data-stock="'+ri+'" placeholder="—" value="'+(r.kho!=null?r.kho:'')+'"></td>'
        + '</tr>';
    }).join('');
  }

  function renderAll(){ renderGroups(); rebuildRows(); renderTable(); serialize(); }

  elAddG.addEventListener('click', function(){
    if(st.groups.length>=2) return;
    st.groups.push({ten:'', options:['']});
    renderAll();
  });

  elGroups.addEventListener('click', function(e){
    var t=e.target, d, a;
    if((d=t.getAttribute('data-addo'))!=null){ st.groups[+d].options.push(''); renderAll(); }
    else if((d=t.getAttribute('data-delg'))!=null){ st.groups.splice(+d,1); renderAll(); }
    else if((d=t.getAttribute('data-delo'))!=null){ a=d.split(','); st.groups[+a[0]].options.splice(+a[1],1); renderAll(); }
    else if((d=t.getAttribute('data-up'))!=null){ a=d.split(','); var ar=st.groups[+a[0]].options,o=+a[1]; if(o>0){ var x=ar[o];ar[o]=ar[o-1];ar[o-1]=x; renderAll(); } }
    else if((d=t.getAttribute('data-dn'))!=null){ a=d.split(','); var ar2=st.groups[+a[0]].options,o2=+a[1]; if(o2<ar2.length-1){ var y=ar2[o2];ar2[o2]=ar2[o2+1];ar2[o2+1]=y; renderAll(); } }
  });

  elGroups.addEventListener('input', function(e){
    var t=e.target, d;
    if((d=t.getAttribute('data-gname'))!=null){ st.groups[+d].ten=t.value; serialize(); renderTable(); }
    else if(t.hasAttribute('data-g')){ st.groups[+t.getAttribute('data-g')].options[+t.getAttribute('data-o')]=t.value; rebuildRows(); renderTable(); serialize(); }
  });

  elTbody.addEventListener('input', function(e){
    var t=e.target, d;
    if((d=t.getAttribute('data-price'))!=null){ st.rows[+d].gia = t.value===''?null:(parseInt(t.value,10)||0); serialize(); }
    else if((d=t.getAttribute('data-stock'))!=null){ st.rows[+d].kho = t.value===''?null:(parseInt(t.value,10)||0); serialize(); }
  });

  if(giaEl) giaEl.addEventListener('input', function(){ (st.rows||[]).forEach(function(r){ if(r.gia==null) r.gia=base(); }); renderTable(); serialize(); });

  var form=document.getElementById('spForm');
  if(form) form.addEventListener('submit', serialize); // chốt lần cuối trước khi gửi

  renderAll();
})();
</script>
</body>
</html>
