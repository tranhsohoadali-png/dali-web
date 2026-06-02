<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $product ? 'Sửa' : 'Thêm' }} Sản Phẩm | DALI Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--g:#6BBF1F;--gb:#8ED63A;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--gn:#C6F135;--pk:#FF8FB1;--bd:#C8E89A;--bd2:#A8D870;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:var(--tx)}
.sb{width:232px;flex-shrink:0;background:linear-gradient(175deg,#1C5200,#2D7A08,#3A9A12);display:flex;flex-direction:column}
.sb-logo{padding:20px 18px 14px;border-bottom:1px solid rgba(255,255,255,.12)}
.sb-logo img{height:38px;filter:brightness(0) invert(1)}
.sb-tag{font-size:9px;color:rgba(255,255,255,.4);letter-spacing:2.5px;margin-top:6px}
.sb-nav{flex:1;padding:12px 8px;display:flex;flex-direction:column;gap:1px}
.sb-sec{font-size:9px;letter-spacing:2.5px;color:rgba(255,255,255,.25);padding:8px 10px 5px}
.sb-a{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;color:rgba(255,255,255,.65);font-size:13px;font-weight:500;text-decoration:none;border:1px solid transparent;transition:all .2s}
.sb-a:hover{background:rgba(255,255,255,.12);color:#fff}
.sb-a.on{background:rgba(255,255,255,.18);color:#fff;font-weight:700;border-color:rgba(255,255,255,.28)}
.sb-foot{padding:14px;border-top:1px solid rgba(255,255,255,.12)}
.av{display:flex;align-items:center;gap:9px}
.av-r{width:34px;height:34px;border-radius:50%;background:rgba(255,255,255,.18);border:2px solid rgba(255,255,255,.35);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;color:#fff}
.av-n{font-size:12px;font-weight:700;color:#fff}
.av-s{font-size:10px;color:rgba(255,255,255,.4)}
.logout{display:block;margin-top:8px;font-size:10px;color:rgba(255,255,255,.3);text-decoration:none}
.topbar{background:#fff;border-bottom:2px solid var(--gl);height:64px;padding:0 24px;display:flex;align-items:center;justify-content:space-between}
.tb-bc{font-size:10px;color:var(--tx3)}
.tb-bc a{color:var(--g);text-decoration:none;font-weight:600}
.tb-title{font-size:18px;font-weight:900;background:linear-gradient(90deg,#2D7A08,var(--g));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-top:2px}
.sakura{background:linear-gradient(90deg,#fff8fa,#f6ffe8,#fff);border-bottom:1px solid #F0EBF8;padding:6px 24px;display:flex;align-items:center;gap:5px}
.p{font-size:14px;animation:drift 5s ease-in-out infinite;display:inline-block}
.p:nth-child(2){animation-delay:1s}.p:nth-child(3){animation-delay:2s}
@keyframes drift{0%,100%{transform:translateY(0)}50%{transform:translateY(-4px)}}
.sak-t{font-size:10px;color:#B8D8A0;letter-spacing:2px;font-weight:700;margin-left:8px}
.cnt{flex:1;overflow-y:auto;padding:22px 24px}
.form-card{background:#fff;border-radius:16px;border:1.5px solid var(--bd);overflow:hidden;box-shadow:0 3px 18px rgba(58,122,10,.07)}
.rainbow{height:4px;background:linear-gradient(90deg,#3A9A12,var(--g),var(--gn),#FF8FB1,#A78BFA)}
.form-head{padding:16px 22px;border-bottom:1px solid var(--gl);background:linear-gradient(135deg,var(--gll),#fff);display:flex;align-items:center;gap:12px}
.form-icon{width:42px;height:42px;background:linear-gradient(135deg,var(--gl),#CCEF90);border:1.5px solid var(--bd2);border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:20px}
.form-title{font-size:14px;font-weight:900;color:var(--char)}
.form-sub{font-size:11px;color:var(--tx3);margin-top:2px}
.fb{padding:22px}
.sec-title{font-size:10px;font-weight:800;letter-spacing:3px;color:var(--tx3);text-transform:uppercase;display:flex;align-items:center;gap:8px;margin-bottom:16px}
.sec-title::before,.sec-title::after{content:'';flex:1;height:1.5px;background:linear-gradient(90deg,transparent,var(--bd))}
.sec-title::before{background:linear-gradient(90deg,var(--bd),transparent)}
.g2{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px}
.g3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;margin-bottom:16px}
.g1{margin-bottom:16px}
.flabel{font-size:12px;font-weight:700;color:var(--tx);display:block;margin-bottom:7px}
.flabel .req{color:var(--pk)}
.dinput,.dselect,.dtextarea{width:100%;background:var(--gll);border:1.5px solid var(--bd);border-radius:10px;padding:11px 14px;font-size:13px;font-family:'Be Vietnam Pro',sans-serif;color:var(--tx);font-weight:500;outline:none;transition:all .2s}
.dinput:focus,.dselect:focus,.dtextarea:focus{border-color:var(--g);background:#fff;box-shadow:0 0 0 3px rgba(107,191,31,.1)}
.dtextarea{resize:vertical;min-height:90px}
.fnote{font-size:10px;color:var(--tx3);margin-top:5px}
.err-msg{font-size:11px;color:#EF4444;margin-top:4px;font-weight:600}
.upload-zone{border:2px dashed var(--bd);border-radius:11px;padding:22px 16px;text-align:center;cursor:pointer;background:var(--gll);transition:all .25s;display:block}
.upload-zone:hover{border-color:var(--g);background:#EAFFC8}
.uzone-icon{font-size:28px;color:var(--bd2);display:block;margin-bottom:8px;transition:all .2s}
.upload-zone:hover .uzone-icon{color:var(--g);transform:scale(1.1)}
.uzone-text{font-size:12px;color:var(--tx3);line-height:1.6}
.img-prev{margin-top:10px;border-radius:11px;overflow:hidden;border:1.5px solid var(--bd)}
.img-prev img{width:100%;height:200px;object-fit:cover;display:block}
.toggle-wrap{display:flex;align-items:center;gap:12px}
.toggle{position:relative;width:46px;height:26px}
.toggle input{opacity:0;width:0;height:0;position:absolute}
.toggle-slider{position:absolute;inset:0;background:#D1D5DB;border-radius:50px;cursor:pointer;transition:background .2s}
.toggle-slider::before{content:'';position:absolute;width:20px;height:20px;left:3px;top:3px;background:#fff;border-radius:50%;transition:transform .2s;box-shadow:0 1px 4px rgba(0,0,0,.15)}
.toggle input:checked + .toggle-slider{background:var(--g)}
.toggle input:checked + .toggle-slider::before{transform:translateX(20px)}
.divider{height:1.5px;background:linear-gradient(90deg,transparent,var(--bd) 25%,var(--bd) 75%,transparent);margin:8px 0 20px}
.btn-save{display:inline-flex;align-items:center;gap:7px;padding:11px 26px;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;font-size:13px;font-weight:800;border:none;border-radius:10px;cursor:pointer;box-shadow:0 4px 16px rgba(107,191,31,.3);transition:all .2s}
.btn-save:hover{background:linear-gradient(135deg,#2E7D08,#5AAF15);transform:translateY(-1px)}
.btn-cancel{padding:11px 22px;background:#fff;border:1.5px solid var(--bd);color:var(--tx3);font-size:13px;font-weight:600;border-radius:10px;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;transition:all .2s}
.btn-cancel:hover{border-color:var(--pk);color:var(--pk)}
.price-preview{background:var(--gll);border-radius:9px;padding:12px 16px;border:1px solid var(--bd);font-size:13px;color:var(--tx2)}
.price-big{font-size:22px;font-weight:900;color:var(--g)}
</style>
</head>
<body>
<div style="display:flex;min-height:100vh">
@include('admin.partials.sidebar')
<div style="flex:1;display:flex;flex-direction:column;overflow:hidden">
  <div class="topbar">
    <div>
      <div class="tb-bc"><a href="{{ route('admin.products.index') }}">Sản phẩm</a> › <b>{{ $product ? 'Chỉnh sửa' : 'Thêm mới' }}</b></div>
      <div class="tb-title">{{ $product ? 'Sửa: '.Str::limit($product->name,30) : 'Thêm Sản Phẩm Mới' }}</div>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn-cancel">← Quay lại</a>
  </div>
  <div class="sakura"><span class="p">🌸</span><span class="p">✿</span><span class="p">🍃</span><span class="sak-t">DALI · TÔ ĐIỂM CUỘC SỐNG</span></div>
  <div class="cnt">
    <div style="display:grid;grid-template-columns:1fr 340px;gap:22px;align-items:start">

      <!-- FORM LEFT -->
      <div class="form-card">
        <div class="rainbow"></div>
        <div class="form-head">
          <div class="form-icon">🎨</div>
          <div><div class="form-title">{{ $product ? 'Cập nhật sản phẩm' : 'Thêm sản phẩm mới' }}</div><div class="form-sub">Điền thông tin chi tiết sản phẩm DALI</div></div>
        </div>
        <div class="fb">
          <form method="POST" action="{{ $product ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data" id="prodForm">
            @csrf
            @if($product) @method('PUT') @endif

            <div class="sec-title">📋 Thông tin cơ bản</div>
            <div class="g1">
              <label class="flabel">Tên sản phẩm <span class="req">*</span></label>
              <input type="text" name="name" class="dinput" value="{{ old('name', $product->name ?? '') }}" placeholder="Ví dụ: Hoa Sen Bình Minh" required>
              @error('name')<div class="err-msg">{{ $message }}</div>@enderror
            </div>
            <div class="g2">
              <div>
                <label class="flabel">Danh mục <span class="req">*</span></label>
                <select name="category_id" class="dselect" required>
                  <option value="">— Chọn danh mục —</option>
                  @foreach($categories as $cat)
                  <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->icon }} {{ $cat->name }}</option>
                  @endforeach
                </select>
                @error('category_id')<div class="err-msg">{{ $message }}</div>@enderror
              </div>
              <div>
                <label class="flabel">Thứ tự hiển thị</label>
                <input type="number" name="sort_order" class="dinput" value="{{ old('sort_order', $product->sort_order ?? 0) }}" min="0">
                <div class="fnote">Số nhỏ → hiển thị trên cùng</div>
              </div>
            </div>
            <div class="g1">
              <label class="flabel">Mô tả sản phẩm</label>
              <textarea name="description" class="dtextarea" placeholder="Mô tả chi tiết về bức tranh...">{{ old('description', $product->description ?? '') }}</textarea>
            </div>

            <div class="divider"></div>
            <div class="sec-title">📐 Thông số kỹ thuật</div>
            <div class="g2">
              <div>
                <label class="flabel">Số màu <span style="font-size:10px;color:var(--tx3);font-weight:400">(nhập 0 = ẩn số màu với khách)</span></label>
                <input type="number" name="colors_count" class="dinput" value="{{ old('colors_count', $product->colors_count ?? 36) }}" min="0" max="200">
              </div>
              <div>
                <label class="flabel">Trạng thái</label>
                <div class="toggle-wrap" style="margin-top:10px">
                  <label class="toggle">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                  </label>
                  <span style="font-size:12px;font-weight:600;color:var(--tx2)">Hiển thị</span>
                </div>
              </div>
            </div>

            <div class="divider"></div>
            <div class="sec-title">📐 Kích thước &amp; Giá <span style="font-size:11px;font-weight:500;color:var(--tx3)">— tích chọn các khổ tranh này có</span></div>
            @php $selectedSizes = old('size_ids', $product->size_ids ?? $sizes->pluck('id')->all()); @endphp
            <div style="background:var(--gll);border:1px solid var(--bd);border-radius:10px;padding:10px 14px;font-size:12px;color:var(--gd);margin-bottom:12px">
              💡 Giá lấy từ <a href="{{ route('admin.settings') }}" target="_blank" style="color:var(--g);font-weight:700">Bảng giá theo kích thước</a> (chung cho mọi tranh). Sửa giá ở đó → áp dụng đồng loạt.
            </div>
            <div style="display:flex;gap:8px;margin-bottom:10px">
              <button type="button" onclick="toggleAllSizes(true)" style="font-size:11px;font-weight:700;padding:5px 12px;border-radius:7px;border:1px solid var(--bd2);background:var(--gl);color:var(--gd);cursor:pointer">✓ Chọn tất cả</button>
              <button type="button" onclick="toggleAllSizes(false)" style="font-size:11px;font-weight:700;padding:5px 12px;border-radius:7px;border:1px solid var(--bd2);background:#fff;color:var(--tx2);cursor:pointer">✕ Bỏ chọn hết</button>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(190px,1fr));gap:8px">
              @foreach($sizes as $sz)
              <label class="size-pick" style="display:flex;align-items:center;gap:9px;padding:10px 12px;border:1.5px solid var(--bd);border-radius:10px;cursor:pointer;transition:all .15s">
                <input type="checkbox" class="size-cb" name="size_ids[]" value="{{ $sz->id }}" data-price="{{ $sz->price }}"
                  {{ in_array($sz->id, (array)$selectedSizes) ? 'checked' : '' }}
                  onchange="updatePreview()" style="width:18px;height:18px;accent-color:var(--g);cursor:pointer">
                <span style="flex:1">
                  <span style="font-size:13px;font-weight:700;color:var(--char)">{{ $sz->name }}</span>
                  @if($sz->note)<span style="font-size:10px;color:var(--pk);font-weight:600;display:block">{{ $sz->note }}</span>@endif
                  <span style="font-size:12px;color:var(--g);font-weight:700">{{ $sz->display_price }}</span>
                </span>
              </label>
              @endforeach
            </div>
            @error('size_ids')<div class="err-msg">{{ $message }}</div>@enderror

            <div class="divider"></div>
            <div class="sec-title">🏷️ Badge & Nhãn</div>
            <div class="g2">
              <div>
                <label class="flabel">Nhãn hiển thị</label>
                <input type="text" name="badge" class="dinput" value="{{ old('badge', $product->badge ?? '') }}" placeholder="Mới / Hot / -20%">
                <div class="fnote">Nhãn nhỏ góc ảnh sản phẩm</div>
              </div>
              <div>
                <label class="flabel">Loại nhãn (màu)</label>
                <select name="badge_type" class="dselect">
                  <option value="default" {{ old('badge_type', $product->badge_type ?? 'default') == 'default' ? 'selected' : '' }}>🟢 Mặc định (xanh)</option>
                  <option value="new" {{ old('badge_type', $product->badge_type ?? '') == 'new' ? 'selected' : '' }}>🔵 Mới (xanh dương)</option>
                  <option value="hot" {{ old('badge_type', $product->badge_type ?? '') == 'hot' ? 'selected' : '' }}>🔴 Hot (hồng đỏ)</option>
                  <option value="sale" {{ old('badge_type', $product->badge_type ?? '') == 'sale' ? 'selected' : '' }}>🟡 Sale (vàng)</option>
                </select>
              </div>
            </div>

            <div class="divider"></div>
            <div class="sec-title">🖼️ Ảnh sản phẩm</div>
            <div>
              <label class="flabel">Ảnh chính</label>
              <input type="file" id="prod_img" name="main_image" accept="image/*" style="display:none" onchange="prevImg(this,'prev_prod')">
              <label for="prod_img" class="upload-zone">
                <span class="uzone-icon">☁️</span>
                <div class="uzone-text">Nhấn vào đây hoặc kéo thả file<br><span style="opacity:.5;font-size:10px">JPG, PNG · Tối đa 5MB · Nên dùng tỉ lệ 1:1</span></div>
              </label>
              @if($product && $product->main_image)
              <div class="img-prev" id="prev_prod"><img src="{{ asset('storage/'.$product->main_image) }}" alt=""></div>
              @else
              <div id="prev_prod"></div>
              @endif
            </div>

            <div class="divider" style="margin-top:22px"></div>
            <div style="display:flex;gap:10px">
              <button type="submit" class="btn-save">✅ {{ $product ? 'Lưu thay đổi' : 'Thêm sản phẩm' }}</button>
              <a href="{{ route('admin.products.index') }}" class="btn-cancel">Huỷ bỏ</a>
            </div>
          </form>
        </div>
      </div>

      <!-- SIDEBAR RIGHT: Preview -->
      <div style="display:flex;flex-direction:column;gap:16px;position:sticky;top:22px">
        <!-- Price preview card -->
        <div class="form-card">
          <div class="rainbow"></div>
          <div style="padding:16px 18px">
            <div style="font-size:12px;font-weight:800;color:var(--tx3);letter-spacing:2px;margin-bottom:12px">💰 GIÁ HIỂN THỊ VỚI KHÁCH</div>
            <div class="price-preview" id="pricePreview">
              <div style="font-size:12px;color:var(--tx3);font-weight:600" id="ppFromLabel">Từ</div>
              <div class="price-big" id="ppMainPrice">—</div>
              <div id="ppCount" style="font-size:12px;color:var(--tx2);margin-top:4px">Chưa chọn kích thước</div>
            </div>
          </div>
        </div>

        <!-- Quick links -->
        <div class="form-card">
          <div style="padding:16px 18px">
            <div style="font-size:12px;font-weight:800;color:var(--tx3);letter-spacing:2px;margin-bottom:12px">🔗 LIÊN KẾT NHANH</div>
            <div style="display:flex;flex-direction:column;gap:8px">
              <a href="{{ route('admin.categories.create') }}" style="font-size:13px;color:var(--g);text-decoration:none;font-weight:600;display:flex;align-items:center;gap:6px">+ Thêm danh mục mới</a>
              <a href="{{ route('admin.products.index') }}" style="font-size:13px;color:var(--tx2);text-decoration:none;font-weight:500;display:flex;align-items:center;gap:6px">← Danh sách sản phẩm</a>
              <a href="{{ route('home') }}" target="_blank" style="font-size:13px;color:var(--tx2);text-decoration:none;font-weight:500;display:flex;align-items:center;gap:6px">🌐 Xem trang chủ</a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
</div>
<script>
function prevImg(input,id){if(!input.files||!input.files[0])return;const r=new FileReader();r.onload=e=>{document.getElementById(id).innerHTML='<div class="img-prev"><img src="'+e.target.result+'" alt=""></div>';};r.readAsDataURL(input.files[0]);}
function fmt(n){return n.toLocaleString('vi-VN')+'đ';}
function toggleAllSizes(on){document.querySelectorAll('.size-cb').forEach(function(cb){cb.checked=on;});updatePreview();}
function updatePreview(){
  var checked=[].slice.call(document.querySelectorAll('.size-cb:checked'));
  var mp=document.getElementById('ppMainPrice');
  var cnt=document.getElementById('ppCount');
  var fromLabel=document.getElementById('ppFromLabel');
  // highlight selected rows
  document.querySelectorAll('.size-pick').forEach(function(l){
    var cb=l.querySelector('.size-cb');
    l.style.borderColor=cb.checked?'var(--g)':'var(--bd)';
    l.style.background=cb.checked?'var(--gll)':'#fff';
  });
  if(checked.length===0){
    mp.textContent='—'; cnt.textContent='Chưa chọn kích thước';
    fromLabel.style.display='none'; return;
  }
  var prices=checked.map(function(cb){return parseInt(cb.dataset.price)||0;});
  var min=Math.min.apply(null,prices);
  mp.textContent=fmt(min);
  fromLabel.style.display=checked.length>1?'block':'none';
  cnt.textContent=checked.length+' kích thước'+(checked.length>1?' (hiện "Từ "+giá nhỏ nhất)':'');
}
updatePreview();
</script>
</body>
</html>