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
/* Ảnh cho từng lựa chọn (chỉ nhóm 1) */
.vg-img{flex:none;width:44px;height:44px;border-radius:9px;display:inline-flex;align-items:center;justify-content:center;position:relative;overflow:hidden;padding:0}
.vg-img.add{border:1.5px dashed var(--bd2);background:#fff;color:var(--tx3);font-size:16px;flex-direction:column;line-height:1;cursor:pointer;gap:1px}
.vg-img.add i{font-style:normal;font-size:8px;font-weight:800;letter-spacing:.2px}
.vg-img.add:hover{border-color:var(--g);color:var(--g);background:var(--gll)}
.vg-img.has{border:1.5px solid var(--bd);cursor:pointer}
.vg-img.has img{width:100%;height:100%;object-fit:cover;display:block}
.vg-imgdel{position:absolute;top:1px;right:1px;width:16px;height:16px;border:none;border-radius:50%;background:rgba(28,58,10,.72);color:#fff;font-size:11px;line-height:1;cursor:pointer;padding:0;display:flex;align-items:center;justify-content:center}
.vg-imgdel:hover{background:#EF4444}
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
      <button type="button" id="btnPrev" class="btn-xem" style="cursor:pointer;border:1px solid var(--bd2)">👁️ Xem trước</button>
      @if($sp)<a href="https://3d.tranhdali.vn/san-pham/{{ $sp->slug }}" target="_blank" rel="noopener" class="btn-xem">🌐 Xem trên web ↗</a>@endif
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
    <input type="hidden" name="anh_order" id="anhOrder">

    <div class="sec">
      <h2>📷 Ảnh sản phẩm</h2>
      <div class="hint">Ảnh <b>đầu tiên là ảnh bìa</b> — hiện ngoài trang chủ và trong danh sách. Dùng ◀ ▶ để đổi thứ tự (áp dụng cho cả ảnh mới), <b>★ để đặt làm bìa</b>, ✕ để xoá. Nên chụp nền sáng, vuông.</div>
      <div class="imgs" id="imgGrid"></div>
      <input type="file" name="anh_moi[]" id="anhMoi" accept="image/*" multiple hidden>
    </div>

    <div class="sec">
      <h2>📝 Thông tin cơ bản</h2>
      <div class="grid">
        <label class="f rong"><span>Tên sản phẩm</span><input name="ten" value="{{ old('ten', $sp->ten ?? '') }}" placeholder="Bộ lắp ghép mô hình phân tử" required></label>
        <label class="f"><span>Đường dẫn trên web <i>— để trống sẽ tự tạo</i></span><input name="slug" value="{{ old('slug', $sp->slug ?? '') }}" placeholder="mo-hinh-phan-tu"></label>
        <label class="f"><span>Danh mục <i>— <a href="{{ route('admin.danhmuc3d.index') }}" style="color:var(--g);text-decoration:underline">quản lý nhóm/danh mục</a></i></span>
          <select name="danh_muc_id">
            <option value="">— Chưa phân loại —</option>
            @foreach($nhoms as $nhom)
              <optgroup label="{{ $nhom->ten }}">
                @foreach($nhom->danhMuc as $muc)
                  <option value="{{ $muc->id }}" {{ (string) old('danh_muc_id', $sp->danh_muc_id ?? '') === (string) $muc->id ? 'selected' : '' }}>{{ $muc->icon }} {{ $muc->ten }}</option>
                @endforeach
              </optgroup>
            @endforeach
          </select></label>
        <label class="f"><span>Nhãn góc ảnh <i>— để trống nếu không cần</i></span><input name="nhan" value="{{ old('nhan', $sp->nhan ?? '') }}" placeholder="BÁN CHẠY NHẤT"></label>
        <label class="f rong"><span>Mô tả ngắn <i>— một câu hiện dưới tên sản phẩm</i></span><input name="mo_ta_ngan" value="{{ old('mo_ta_ngan', $sp->mo_ta_ngan ?? '') }}" placeholder="Nền + 40 thẻ môn học cắm rời như LEGO. Bé tự đổi lịch mỗi tuần."></label>
      </div>
    </div>

    <div class="sec">
      <h2>💰 Giá bán</h2>
      <div class="grid">
        <label class="f"><span>Giá bán (đ) <i>— nếu có phân loại, hệ tự lấy giá RẺ NHẤT của phân loại</i></span><input name="gia" inputmode="numeric" value="{{ old('gia', $sp->gia ?? 0) }}"></label>
        <label class="f"><span>Tồn kho <i>— 0 là không theo dõi</i></span><input name="kho" inputmode="numeric" value="{{ old('kho', $sp->kho ?? 0) }}"></label>
        <label class="f"><span>Thứ tự hiện trên web <i>— nhỏ đứng trước</i></span><input name="thu_tu" inputmode="numeric" value="{{ old('thu_tu', $sp->thu_tu ?? 0) }}"></label>
      </div>
    </div>

    <div class="sec">
      <h2>🏷️ Phân loại hàng <i style="font-weight:500;color:var(--tx3);font-size:12px">— để trống nếu bán một mức giá</i></h2>
      <div class="hint">Mỗi tùy chọn một ô. Ví dụ Bookmark: nhóm <b>Chọn phiên bản</b> gồm 4 màu lẻ + Set 4. Thêm nhóm thứ 2 (vd Kích thước) sẽ tự sinh bảng tổ hợp. Giá nhập ở bảng bên dưới. <b>Nhóm thứ nhất</b> thêm được <b>ảnh riêng cho từng lựa chọn</b> (hiện cho khách khi chọn màu trên trang sản phẩm).</div>
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
      <input type="file" id="vgOptFile" accept="image/*" hidden>
      <input type="file" name="variant_img_new[]" id="vgFileInput" multiple hidden>
    </div>

    <div class="sec">
      <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:2px">
        <h2 style="margin:0">📄 Mô tả sản phẩm</h2>
        <button type="button" id="btnMotaAi" class="vg-btn" style="border-style:solid" title="Nhờ AI viết mô tả từ tên + ảnh + ý chính">✨ Gợi ý mô tả</button>
      </div>
      <div id="motaAiMsg" class="hint" style="display:none;margin:6px 0 10px"></div>
      <div class="hint"><b>Mô tả đầy đủ</b>: đoạn văn giới thiệu (mỗi dòng trống ngăn một đoạn) — hiện thành bài mô tả trên trang sản phẩm.</div>
      <textarea name="mo_ta_dai" rows="8" style="width:100%;border:1.5px solid var(--bd);border-radius:9px;padding:11px 13px;font-size:13px;background:#fff;font-family:'Be Vietnam Pro',sans-serif;resize:vertical" placeholder="Đoạn 1: giới thiệu sản phẩm, cảm giác/trải nghiệm.&#10;&#10;Đoạn 2: chi tiết kỹ thuật, chất liệu, an toàn.&#10;&#10;Đoạn 3: gợi ý sử dụng / cam kết.">{{ old('mo_ta_dai', $sp->mo_ta_dai ?? '') }}</textarea>
      <div class="hint" style="margin:14px 0 6px"><b>Ý chính (gạch đầu dòng)</b>: mỗi dòng một ý — hiện thành danh sách ✓ bên dưới bài mô tả.</div>
      <textarea name="mota_text" rows="5" style="width:100%;border:1.5px solid var(--bd);border-radius:9px;padding:11px 13px;font-size:13px;background:var(--gll);font-family:'Be Vietnam Pro',sans-serif;resize:vertical" placeholder="Bảng nền + đủ 79 phiếu 23 môn&#10;Lắp ghép như LEGO, thay đổi dễ dàng&#10;Nhựa PLA an toàn cho bé&#10;Khắc tên bé miễn phí">{{ old('mota_text', $motaText) }}</textarea>
    </div>

    <div class="sec">
      <h2>⚙️ Cách bán</h2>
      <div class="hint" style="margin:-6px 0 14px">⭐ Điểm sao mặc định <b>5.0</b> (như tranhdali.vn khi chưa có đánh giá) · 🛒 Lượt bán <b>tự cộng</b> mỗi khi một Đơn 3D chuyển sang <b>Hoàn tất</b> — không cần nhập tay.</div>
      <div class="grid" style="margin-bottom:12px">
        <label class="f"><span>Chính sách thanh toán</span>
          <select name="payment_policy">
            @php $pp = old('payment_policy', $sp->payment_policy ?? 'deposit_50'); @endphp
            <option value="deposit_50" {{ $pp=='deposit_50'?'selected':'' }}>Cọc 50% khi đặt, 50% khi nhận (mặc định)</option>
            <option value="cod_or_prepaid_10" {{ $pp=='cod_or_prepaid_10'?'selected':'' }}>Nhận hàng trả tiền (CK trước giảm 10%)</option>
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
/* Bộ ảnh chính: danh sách HỢP NHẤT ảnh cũ + ảnh mới, đổi thứ tự tự do + đặt bìa.
   items[i] = {old:"sp3d/.."} (ảnh đã lưu) | {_nf:<idx>} (file mới, trỏ vào newFiles).
   Ảnh đầu tiên là bìa. Gửi lên: anh_order (thứ tự cuối) + anh_moi[] (file mới theo k). */
(function(){
  var STORE  = @json(asset('storage')) + '/';
  var keepEl = document.getElementById('anhKeep');   // seed ảnh cũ
  var orderEl= document.getElementById('anhOrder');  // thứ tự cuối gửi lên
  var grid   = document.getElementById('imgGrid');
  var fileEl = document.getElementById('anhMoi');     // ô chọn + mang file mới

  var newFiles = [];   // File mới (KHÔNG nén chỉ số)
  var newUrl   = {};   // _nf -> objectURL
  var items = (function(){
    try{ return (JSON.parse(keepEl.value)||[]).map(function(p){ return {old:p}; }); }catch(e){ return []; }
  })();

  function urlOf(it){
    if(it.old!=null) return STORE+it.old;
    if(newUrl[it._nf]==null) newUrl[it._nf]=URL.createObjectURL(newFiles[it._nf]);
    return newUrl[it._nf];
  }

  function cell(it,i,n){
    var isNew = it.old==null;
    var d=document.createElement('div'); d.className='imgo';
    d.innerHTML = '<img src="'+urlOf(it)+'">'
      + (i===0 ? '<i class="bia">Ảnh bìa</i>' : (isNew?'<i class="bia" style="background:#F59E0B">Mới</i>':''))
      + '<div class="ops"><span>'
      +   (i>0   ? '<button type="button" data-mv="'+i+'" data-dir="-1" title="Sang trái">◀</button>' : '')
      +   (i<n-1 ? '<button type="button" data-mv="'+i+'" data-dir="1" title="Sang phải">▶</button>' : '')
      +   (i>0   ? '<button type="button" data-bia="'+i+'" title="Đặt làm ảnh bìa">★</button>' : '')
      + '</span><button type="button" class="del" data-del="'+i+'" title="Xoá">✕</button></div>';
    return d;
  }
  function nutThem(){
    var d=document.createElement('div'); d.className='them';
    d.innerHTML='＋<span>Thêm ảnh</span>';
    d.onclick=function(){ fileEl.click(); };
    return d;
  }

  // Nạp file mới đang dùng vào input theo đúng thứ tự k khớp anh_order.
  function sync(){
    var dt=new DataTransfer();
    var order=items.map(function(it){
      if(it.old!=null) return {old:it.old};
      var k=dt.items.length; dt.items.add(newFiles[it._nf]); return {"new":k};
    });
    fileEl.files=dt.files;
    orderEl.value=JSON.stringify(order);
  }

  function ve(){
    grid.innerHTML='';
    items.forEach(function(it,i){ grid.appendChild(cell(it,i,items.length)); });
    grid.appendChild(nutThem());
    sync();
  }

  grid.addEventListener('click', function(e){
    var t=e.target.closest('[data-del],[data-mv],[data-bia]'); if(!t) return;
    var d;
    if((d=t.getAttribute('data-del'))!=null){ items.splice(+d,1); ve(); }
    else if((d=t.getAttribute('data-bia'))!=null){ var it=items.splice(+d,1)[0]; items.unshift(it); ve(); }
    else if((d=t.getAttribute('data-mv'))!=null){ var i=+d,j=i+(+t.getAttribute('data-dir')); if(j>=0&&j<items.length){ var x=items[i];items[i]=items[j];items[j]=x; ve(); } }
  });
  fileEl.addEventListener('change', function(){
    Array.prototype.forEach.call(fileEl.files, function(f){ newFiles.push(f); items.push({_nf:newFiles.length-1}); });
    ve();
  });

  ve();
})();
</script>

<script>
/* ===== Phân loại hàng kiểu Shopee (kèm ảnh cho từng lựa chọn của nhóm 1) =====
   Sinh tổ hợp deterministic (nhóm 0 vòng ngoài) — KHỚP hệt buildVariants() ở PHP.
   Ảnh: mỗi lựa chọn của NHÓM 1 có 1 ảnh. Trạng thái ảnh = {path:"sp3d/.."} (đã lưu)
   | {tmp:<id>} (file mới chọn, chưa gửi) | null. Khi gửi, prepareUpload() đổi {tmp}
   thành {new:k} và nạp file vào input variant_img_new[] đúng thứ tự k. */
(function(){
  var STORE = @json(asset('storage')) + '/';
  var seed = @json($vgSeed);
  var st = (seed && Array.isArray(seed.groups)) ? seed : {groups:[],rows:[]};
  st.groups = st.groups || []; st.rows = st.rows || [];

  var elGroups = document.getElementById('vgGroups');
  var elAddG   = document.getElementById('vgAddGroup');
  var elTblW   = document.getElementById('vgTableWrap');
  var elThead  = document.getElementById('vgThead');
  var elTbody  = document.getElementById('vgTbody');
  var elJson   = document.getElementById('vgJson');
  var optFile  = document.getElementById('vgOptFile');   // ô chọn ảnh (không gửi)
  var fileIn   = document.getElementById('vgFileInput');  // ô gửi file mới
  var giaEl    = document.querySelector('[name="gia"]');

  var tmpFiles = {};   // id -> File
  var tmpUrl   = {};   // id -> objectURL
  var tmpSeq   = 0;
  var pendingOi = -1;  // lựa chọn nhóm 1 đang chờ gán ảnh

  function base(){ return giaEl ? (parseInt(giaEl.value||'0',10)||0) : 0; }
  function esc(s){ return String(s==null?'':s).replace(/[&<>"]/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c];}); }
  function opts(g){ return (g.options||[]).map(function(o){return String(o||'').trim();}); }

  // Chuẩn hoá mảng ảnh nhóm 0: dài đúng bằng số lựa chọn, seed dạng chuỗi -> {path}.
  function normImgs(){
    var g0=st.groups[0]; if(!g0) return;
    var src=g0.imgs||[];
    g0.imgs=(g0.options||[]).map(function(_,i){
      var v=src[i];
      if(v==null) return null;
      if(typeof v==='string') return v?{path:v}:null;
      if(v.path) return {path:v.path};
      if(v.tmp!=null) return {tmp:v.tmp};
      return null;
    });
  }

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

  function imgSrc(im){
    if(!im) return '';
    if(im.path) return STORE+im.path;
    if(im.tmp!=null) return tmpUrl[im.tmp]||'';
    return '';
  }
  function imgSlot(oi){
    var im=(st.groups[0].imgs||[])[oi];
    var src=imgSrc(im);
    if(src) return '<span class="vg-img has" data-imgpick="'+oi+'" title="Đổi ảnh"><img src="'+src+'">'
      +'<button type="button" class="vg-imgdel" data-imgdel="'+oi+'" title="Xoá ảnh">×</button></span>';
    return '<button type="button" class="vg-img add" data-imgpick="'+oi+'" title="Thêm ảnh">＋<i>ảnh</i></button>';
  }

  // Chuẩn bị gửi: đổi {tmp}->{new:k}, nạp file vào variant_img_new[], viết #vgJson.
  function serialize(){
    var dt=new DataTransfer();
    var out={groups:st.groups.map(function(g,gi){
      var ng={ten:g.ten||'', options:(g.options||[]).slice()};
      if(gi===0){
        ng.imgs=(g.imgs||[]).map(function(im){
          if(!im) return null;
          if(im.path) return {path:im.path};
          if(im.tmp!=null && tmpFiles[im.tmp]){ var k=dt.items.length; dt.items.add(tmpFiles[im.tmp]); return {"new":k}; }
          return null;
        });
      }
      return ng;
    }), rows:st.rows};
    fileIn.files=dt.files;
    elJson.value=JSON.stringify(out);
  }

  function renderGroups(){
    normImgs();
    elGroups.innerHTML = st.groups.map(function(g,gi){
      var rows=(g.options||[]).map(function(o,oi){
        return '<div class="vg-opt">'
          + (gi===0 ? imgSlot(oi) : '')
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
        + (gi===0 ? '<span class="vg-note" style="margin-left:8px">Ô ảnh bên trái mỗi lựa chọn — tải ảnh riêng cho màu đó.</span>' : '')
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

  // Bảo đảm imgs nhóm 0 đồng bộ khi thêm/xoá/đổi thứ tự lựa chọn.
  function g0imgs(){ var g0=st.groups[0]; if(!g0.imgs) g0.imgs=[]; return g0.imgs; }

  elAddG.addEventListener('click', function(){
    if(st.groups.length>=2) return;
    st.groups.push({ten:'', options:['']});
    renderAll();
  });

  elGroups.addEventListener('click', function(e){
    var t=e.target.closest('[data-imgpick],[data-imgdel],[data-addo],[data-delg],[data-delo],[data-up],[data-dn]');
    if(!t) return; var d, a;
    if((d=t.getAttribute('data-imgpick'))!=null){ pendingOi=+d; optFile.value=''; optFile.click(); }
    else if((d=t.getAttribute('data-imgdel'))!=null){ g0imgs()[+d]=null; renderAll(); }
    else if((d=t.getAttribute('data-addo'))!=null){ st.groups[+d].options.push(''); if(+d===0) g0imgs().push(null); renderAll(); }
    else if((d=t.getAttribute('data-delg'))!=null){ st.groups.splice(+d,1); renderAll(); }
    else if((d=t.getAttribute('data-delo'))!=null){ a=d.split(','); st.groups[+a[0]].options.splice(+a[1],1); if(+a[0]===0) g0imgs().splice(+a[1],1); renderAll(); }
    else if((d=t.getAttribute('data-up'))!=null){ a=d.split(','); var g=+a[0],o=+a[1],ar=st.groups[g].options; if(o>0){ var x=ar[o];ar[o]=ar[o-1];ar[o-1]=x; if(g===0){var im=g0imgs();var y=im[o];im[o]=im[o-1];im[o-1]=y;} renderAll(); } }
    else if((d=t.getAttribute('data-dn'))!=null){ a=d.split(','); var g2=+a[0],o2=+a[1],ar2=st.groups[g2].options; if(o2<ar2.length-1){ var z=ar2[o2];ar2[o2]=ar2[o2+1];ar2[o2+1]=z; if(g2===0){var im2=g0imgs();var w=im2[o2];im2[o2]=im2[o2+1];im2[o2+1]=w;} renderAll(); } }
  });

  optFile.addEventListener('change', function(){
    var f=optFile.files&&optFile.files[0];
    if(!f||pendingOi<0) return;
    if(!/^image\//.test(f.type)){ alert('Vui lòng chọn tệp ảnh.'); return; }
    if(f.size>6*1024*1024){ alert('Ảnh nên dưới 6MB.'); return; }
    var id=tmpSeq++; tmpFiles[id]=f; tmpUrl[id]=URL.createObjectURL(f);
    g0imgs()[pendingOi]={tmp:id};
    pendingOi=-1;
    renderAll();
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

<script>
/* Trợ lý viết mô tả: gom tên + danh mục + giá + ý chính + ảnh (đã hosted) gửi lên
   server; server gọi Claude (nếu có key) hoặc trả bản mẫu. Điền vào 3 ô mô tả. */
(function(){
  var btn=document.getElementById('btnMotaAi'), msg=document.getElementById('motaAiMsg');
  if(!btn) return;
  var URL_AI=@json(route('admin.sp3d.motaAi'));
  function tok(){var e=document.querySelector('[name=_token]');return e?e.value:'';}
  function val(n){var e=document.querySelector('[name="'+n+'"]');return e?String(e.value||'').trim():'';}
  function setVal(n,v){var e=document.querySelector('[name="'+n+'"]');if(e){e.value=v;e.dispatchEvent(new Event('input',{bubbles:true}));}}
  function dmText(){var s=document.querySelector('[name=danh_muc_id]');if(!s||!s.value)return '';var o=s.selectedOptions[0];return o?String(o.textContent||'').trim():'';}
  function khac(){var e=document.querySelector('[name=khac_ten]');return e&&e.checked?1:0;}
  function hostedAnh(){return [].slice.call(document.querySelectorAll('#imgGrid .imgo img'))
    .map(function(i){return i.getAttribute('src')||'';})
    .filter(function(u){return u.indexOf('/storage/sp3d/')>=0;});}
  function show(t,err){msg.style.display='block';msg.style.color=err?'#B91C1C':'var(--gd)';msg.textContent=t;}

  btn.addEventListener('click', function(){
    var ten=val('ten'), tuKhoa=val('mota_text');
    if(!ten && !tuKhoa){ show('Nhập TÊN sản phẩm hoặc vài ý chính (ô gạch đầu dòng) trước đã.',true); return; }
    var old=btn.textContent; btn.disabled=true; btn.textContent='✨ Đang viết…'; show('Đang nhờ AI viết mô tả…');
    fetch(URL_AI,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':tok(),'Accept':'application/json'},
      body:JSON.stringify({ten:ten,danh_muc:dmText(),gia:parseInt(val('gia')||'0',10)||0,tu_khoa:tuKhoa,khac_ten:khac(),anh:hostedAnh()})})
    .then(function(r){return r.json().then(function(d){return {ok:r.ok,d:d};});})
    .then(function(res){
      var d=res.d||{};
      if(!res.ok||!d.ok){ show(d.error||('Không tạo được mô tả ('+(d.message||'lỗi')+').'),true); return; }
      if(d.mo_ta_ngan) setVal('mo_ta_ngan',d.mo_ta_ngan);
      if(d.mota&&d.mota.length) setVal('mota_text',d.mota.join('\n'));
      if(d.mo_ta_dai) setVal('mo_ta_dai',d.mo_ta_dai);
      if(d.source==='ai') show('✓ AI đã viết xong — xem lại, sửa nếu cần rồi Lưu.');
      else show('✓ Đã điền bản mẫu. '+(d.warn?('⚠ '+d.warn+' '):'')+'Thêm ANTHROPIC_API_KEY vào .env để AI tự viết theo ảnh.');
    })
    .catch(function(e){ show('Lỗi mạng: '+e.message,true); })
    .finally(function(){ btn.disabled=false; btn.textContent=old; });
  });
})();
</script>

<!-- ===== Xem trước trực tiếp: thẻ ngoài trang + trang sản phẩm, cập nhật realtime ===== -->
<style>
#prevBack{position:fixed;inset:0;background:rgba(20,40,8,.32);opacity:0;visibility:hidden;transition:opacity .25s;z-index:60}
#prevBack.on{opacity:1;visibility:visible}
#prevDraw{position:fixed;top:0;right:0;bottom:0;width:420px;max-width:92vw;background:#EEF6FF;box-shadow:-8px 0 34px rgba(20,40,8,.18);
  transform:translateX(100%);transition:transform .28s cubic-bezier(.16,1,.3,1);z-index:61;display:flex;flex-direction:column}
#prevDraw.on{transform:none}
.pv-hd{flex:none;height:52px;padding:0 16px;background:#fff;border-bottom:1px solid #DCEBD0;display:flex;align-items:center;justify-content:space-between}
.pv-hd b{font-size:14px;font-weight:900;color:#2D6A08}
.pv-x{border:none;background:#F0F6E8;color:#3E7A0A;width:32px;height:32px;border-radius:9px;font-size:16px;cursor:pointer}
.pv-body{flex:1;overflow-y:auto;padding:16px;font-family:'Be Vietnam Pro',sans-serif}
.pv-lb{font-size:10px;font-weight:800;letter-spacing:.6px;color:#7FA968;text-transform:uppercase;margin:2px 2px 8px}
.pv-lb2{margin-top:22px}
/* Thẻ ngoài trang */
.pv-card{width:210px;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 8px 22px -12px rgba(20,60,20,.4);border:1px solid #EAF2E0}
.pv-card .im{aspect-ratio:1/1;background:#F1F6EA center/cover no-repeat;position:relative}
.pv-card .nhan{position:absolute;top:8px;left:8px;background:#FF5A3C;color:#fff;font-size:9px;font-weight:800;padding:3px 8px;border-radius:20px;letter-spacing:.3px}
.pv-card .bd{padding:11px 12px 13px}
.pv-card .bd h4{font-size:13.5px;font-weight:800;color:#173A12;line-height:1.3;margin-bottom:4px}
.pv-card .bd .sh{font-size:11px;color:#7C9A6E;line-height:1.35;margin-bottom:7px;min-height:15px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.pv-card .bd .st{font-size:10.5px;color:#E8A200;margin-bottom:6px}
.pv-card .bd .pr{font-size:16px;font-weight:900;color:#FF5A3C}
/* Trang sản phẩm */
.pv-pg{background:#fff;border-radius:16px;overflow:hidden;border:1px solid #EAF2E0;box-shadow:0 8px 22px -14px rgba(20,60,20,.4)}
.pv-pg .big{aspect-ratio:1/1;background:#F1F6EA center/cover no-repeat}
.pv-pg .thumbs{display:flex;gap:6px;padding:8px 10px;overflow-x:auto;background:#FAFDF6}
.pv-pg .thumbs img{width:40px;height:40px;border-radius:8px;object-fit:cover;flex:none;border:1px solid #E3EED6}
.pv-pg .pad{padding:13px 15px 16px}
.pv-crumb{font-size:10.5px;color:#9DB98C;margin-bottom:7px}
.pv-pg h3{font-size:18px;font-weight:900;color:#143A0F;line-height:1.25;margin-bottom:5px}
.pv-star{font-size:11px;color:#E8A200;margin-bottom:10px}
.pv-sh{font-size:12.5px;color:#5B7A4E;line-height:1.5;margin-bottom:12px}
.pv-price{font-size:24px;font-weight:900;color:#FF5A3C;margin-bottom:4px}
.pv-cocbox{background:#F3F9EC;border:1px solid #DCEBD0;border-radius:11px;padding:10px 12px;margin:10px 0;font-size:12px}
.pv-cocbox .r{display:flex;justify-content:space-between;padding:3px 0;color:#33590F}
.pv-cocbox .r b{font-weight:800}
.pv-cocbox .r.tot{border-top:1px dashed #CADFB6;margin-top:4px;padding-top:6px}
.pv-khac{background:#FFF6F9;border:1px dashed #FBC7D8;border-radius:10px;padding:9px 12px;margin:10px 0;font-size:11.5px;color:#B0466B}
.pv-ul{list-style:none;margin:10px 0 0}
.pv-ul li{font-size:12px;color:#33500F;line-height:1.5;padding-left:20px;position:relative;margin-bottom:5px}
.pv-ul li:before{content:"✓";position:absolute;left:0;color:#6BBF1F;font-weight:900}
.pv-para{margin-top:12px}
.pv-para p{font-size:12.5px;color:#4A6A3E;line-height:1.6;margin-bottom:9px}
.pv-empty{font-size:12px;color:#A9C199;font-style:italic;padding:8px 2px}
</style>
<div id="prevBack"></div>
<aside id="prevDraw" aria-hidden="true">
  <div class="pv-hd"><b>👁️ Xem trước</b><button type="button" class="pv-x" id="prevClose">✕</button></div>
  <div class="pv-body" id="prevBody"></div>
</aside>
<script>
/* Xem trước trực tiếp — đọc thẳng từ form, vẽ lại thẻ + trang y như web thật.
   Không gọi mạng: ảnh lấy từ lưới ảnh (#imgGrid), giá lấy min phân loại (như syncGiaTheoBienThe). */
(function(){
  var draw=document.getElementById('prevDraw'), back=document.getElementById('prevBack'),
      body=document.getElementById('prevBody'), btn=document.getElementById('btnPrev'),
      xBtn=document.getElementById('prevClose'), form=document.getElementById('spForm');
  if(!draw||!btn) return;
  var mo=false; // đang mở?

  function val(n){var e=document.querySelector('[name="'+n+'"]');return e?String(e.value||'').trim():'';}
  function chk(n){var e=document.querySelector('[name="'+n+'"]');return !!(e&&e.checked);}
  function esc(s){return String(s==null?'':s).replace(/[&<>"]/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c];});}
  function vnd(n){return (Number(n)||0).toLocaleString('vi-VN')+'₫';}
  function anhs(){return [].slice.call(document.querySelectorAll('#imgGrid .imgo img')).map(function(i){return i.getAttribute('src');});}
  function gia(){
    var base=parseInt(val('gia')||'0',10)||0;
    try{var vg=JSON.parse((document.getElementById('vgJson')||{}).value||'{}');
      var ps=(vg.rows||[]).map(function(r){return parseInt(r.gia,10)||0;}).filter(function(x){return x>0;});
      if(ps.length) return Math.min.apply(null,ps);
    }catch(e){}
    return base;
  }
  function dm(){
    var s=document.querySelector('[name="danh_muc_id"]');
    if(!s||!s.value) return {nhom:'',dm:''};
    var o=s.selectedOptions[0]; if(!o) return {nhom:'',dm:''};
    return {nhom:(o.parentElement&&o.parentElement.label)||'', dm:(o.textContent||'').trim()};
  }
  function lines(n){return val(n).split(/\r?\n/).map(function(x){return x.trim();}).filter(Boolean);}
  function paras(){return val('mo_ta_dai').split(/\r?\n\s*\r?\n/).map(function(x){return x.trim();}).filter(Boolean);}

  function render(){
    if(!mo) return;
    var ten=val('ten')||'(chưa có tên)', sh=val('mo_ta_ngan'), nhan=val('nhan'),
        g=gia(), imgs=anhs(), cover=imgs[0]||'', c=dm(),
        bullets=lines('mota_text'), pr=paras(), khac=chk('khac_ten'), pp=val('payment_policy');
    var crumb=[c.nhom,c.dm].filter(Boolean).join(' / ')||'Sản phẩm';

    // Thẻ ngoài trang
    var card='<div class="pv-card"><div class="im" style="'+(cover?'background-image:url(\''+esc(cover)+'\')':'')+'">'
      + (nhan?'<span class="nhan">'+esc(nhan)+'</span>':'')
      + (cover?'':'<div style="display:grid;place-items:center;height:100%;color:#B7CDA6;font-size:11px">chưa có ảnh</div>')
      + '</div><div class="bd"><h4>'+esc(ten)+'</h4>'
      + '<div class="sh">'+esc(sh)+'</div>'
      + '<div class="st">★★★★★ 5.0</div>'
      + '<div class="pr">'+vnd(g)+'</div></div></div>';

    // Trang sản phẩm
    var thumbs=imgs.slice(0,6).map(function(u){return '<img src="'+esc(u)+'">';}).join('');
    var coc='';
    if(pp==='deposit_50'){
      var c1=Math.ceil(g*0.5/1000)*1000, c2=g-c1;
      coc='<div class="pv-cocbox"><div class="r"><span>Tạm tính</span><b>'+vnd(g)+'</b></div>'
        + '<div class="r"><span>Cọc trước (50%)</span><b>'+vnd(c1)+'</b></div>'
        + '<div class="r tot"><span>Trả khi nhận hàng</span><b>'+vnd(c2)+'</b></div></div>';
    } else {
      coc='<div class="pv-cocbox"><div class="r"><span>Nhận hàng trả tiền</span><b>'+vnd(g)+'</b></div>'
        + '<div class="r"><span>Chuyển khoản trước</span><b>giảm 10%</b></div></div>';
    }
    var page='<div class="pv-pg">'
      + '<div class="big" style="'+(cover?'background-image:url(\''+esc(cover)+'\')':'')+'">'+(cover?'':'<div style="display:grid;place-items:center;height:100%;color:#B7CDA6;font-size:12px">chưa có ảnh</div>')+'</div>'
      + (thumbs?'<div class="thumbs">'+thumbs+'</div>':'')
      + '<div class="pad"><div class="pv-crumb">'+esc(crumb)+'</div>'
      + '<h3>'+esc(ten)+'</h3><div class="pv-star">★★★★★ 5.0 · Kiểm tra hàng khi nhận</div>'
      + (sh?'<div class="pv-sh">'+esc(sh)+'</div>':'')
      + '<div class="pv-price">'+vnd(g)+'</div>'
      + coc
      + (khac?'<div class="pv-khac">✏️ Khắc tên bé (miễn phí) — hiện ô nhập khi khách đặt</div>':'')
      + (bullets.length?'<ul class="pv-ul">'+bullets.map(function(b){return '<li>'+esc(b)+'</li>';}).join('')+'</ul>':'')
      + (pr.length?'<div class="pv-para">'+pr.map(function(p){return '<p>'+esc(p)+'</p>';}).join('')+'</div>':'')
      + '</div></div>';

    body.innerHTML='<div class="pv-lb">Thẻ ngoài trang chủ / danh sách</div>'+card
      + '<div class="pv-lb pv-lb2">Trang sản phẩm</div>'+page;
  }

  function open(){ mo=true; draw.classList.add('on'); back.classList.add('on'); draw.setAttribute('aria-hidden','false'); render(); }
  function close(){ mo=false; draw.classList.remove('on'); back.classList.remove('on'); draw.setAttribute('aria-hidden','true'); }

  btn.addEventListener('click', open);
  xBtn.addEventListener('click', close);
  back.addEventListener('click', close);
  document.addEventListener('keydown', function(e){ if(e.key==='Escape'&&mo) close(); });

  // Cập nhật realtime: mọi thay đổi trên form + khi lưới ảnh đổi.
  if(form){ form.addEventListener('input', render); form.addEventListener('change', render); }
  var grid=document.getElementById('imgGrid');
  if(grid && window.MutationObserver){ new MutationObserver(render).observe(grid,{childList:true,subtree:true,attributes:true}); }
})();
</script>
</body>
</html>
