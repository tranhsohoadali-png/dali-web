<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $category ? 'Sửa' : 'Thêm' }} Danh Mục | DALI Admin</title>
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
.form-card{background:#fff;border-radius:16px;border:1.5px solid var(--bd);overflow:hidden;max-width:680px;box-shadow:0 3px 18px rgba(58,122,10,.07)}
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
.img-prev img{width:100%;height:180px;object-fit:cover;display:block}
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
</style>
</head>
<body>
<div style="display:flex;min-height:100vh">
@include('admin.partials.sidebar')
<div style="flex:1;display:flex;flex-direction:column;overflow:hidden">
  <div class="topbar">
    <div>
      <div class="tb-bc"><a href="{{ route('admin.categories.index') }}">Danh mục</a> › <b>{{ $category ? 'Chỉnh sửa' : 'Thêm mới' }}</b></div>
      <div class="tb-title">{{ $category ? 'Sửa: '.$category->name : 'Thêm Danh Mục Mới' }}</div>
    </div>
    <a href="{{ route('admin.categories.index') }}" class="btn-cancel">← Quay lại</a>
  </div>
  <div class="sakura"><span class="p">🌸</span><span class="p">✿</span><span class="p">🍃</span><span class="sak-t">DALI · TÔ ĐIỂM CUỘC SỐNG</span></div>
  <div class="cnt">
    <div class="form-card">
      <div class="rainbow"></div>
      <div class="form-head">
        <div class="form-icon">🏷️</div>
        <div><div class="form-title">{{ $category ? 'Cập nhật danh mục' : 'Thêm danh mục mới' }}</div><div class="form-sub">Phân loại sản phẩm theo chủ đề</div></div>
      </div>
      <div class="fb">
        <form method="POST" action="{{ $category ? route('admin.categories.update', $category) : route('admin.categories.store') }}" enctype="multipart/form-data">
          @csrf
          @if($category) @method('PUT') @endif

          <div class="sec-title">🏷️ Thông tin danh mục</div>
          <div class="g2">
            <div>
              <label class="flabel">Tên danh mục <span class="req">*</span></label>
              <input type="text" name="name" class="dinput" value="{{ old('name', $category->name ?? '') }}" placeholder="Ví dụ: Phong cảnh" required>
              @error('name')<div class="err-msg">{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="flabel">Icon (emoji)</label>
              <input type="text" name="icon" class="dinput" value="{{ old('icon', $category->icon ?? '🎨') }}" placeholder="🌄">
              <div class="fnote">Một emoji đại diện cho danh mục</div>
            </div>
          </div>
          <div class="g2">
            <div>
              <label class="flabel">Thứ tự hiển thị</label>
              <input type="number" name="sort_order" class="dinput" value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0">
              <div class="fnote">Số nhỏ hiển thị trước</div>
            </div>
            <div>
              <label class="flabel">Trạng thái</label>
              <div class="toggle-wrap" style="margin-top:10px">
                <label class="toggle">
                  <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
                  <span class="toggle-slider"></span>
                </label>
                <span style="font-size:13px;font-weight:600;color:var(--tx2)">Hiển thị trên website</span>
              </div>
            </div>
          </div>
          <div class="g1">
            <label class="flabel">Mô tả ngắn</label>
            <textarea name="description" class="dtextarea" placeholder="Mô tả ngắn về danh mục...">{{ old('description', $category->description ?? '') }}</textarea>
          </div>

          <div class="divider"></div>
          <div class="sec-title">🖼️ Ảnh đại diện</div>
          <div>
            <label class="flabel">Ảnh danh mục</label>
            <input type="file" id="cat_img" name="image" accept="image/*" style="display:none" onchange="prevImg(this,'prev_cat')">
            <label for="cat_img" class="upload-zone">
              <span class="uzone-icon">☁️</span>
              <div class="uzone-text">Nhấn vào đây hoặc kéo thả file<br><span style="opacity:.5;font-size:10px">JPG, PNG · Tối đa 5MB</span></div>
            </label>
            @if($category && $category->image)
            <div class="img-prev" id="prev_cat"><img src="{{ asset('storage/'.$category->image) }}" alt=""></div>
            @else
            <div id="prev_cat"></div>
            @endif
          </div>

          <div class="divider" style="margin-top:22px"></div>
          <div style="display:flex;gap:10px">
            <button type="submit" class="btn-save">✅ {{ $category ? 'Lưu thay đổi' : 'Thêm danh mục' }}</button>
            <a href="{{ route('admin.categories.index') }}" class="btn-cancel">Huỷ bỏ</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
<script>
function prevImg(input,id){if(!input.files||!input.files[0])return;const r=new FileReader();r.onload=e=>{document.getElementById(id).innerHTML='<div class="img-prev"><img src="'+e.target.result+'" alt=""></div>';};r.readAsDataURL(input.files[0]);}
</script>
</body>
</html>