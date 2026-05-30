<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $post ? 'Sửa' : 'Thêm' }} Bài Viết | DALI Admin</title>
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
.form-card{background:#fff;border-radius:16px;border:0.5px solid var(--bd);overflow:hidden;max-width:900px}
.rainbow{height:4px;background:linear-gradient(90deg,#3A9A12,var(--g),var(--gn),#FF8FB1,#A78BFA)}
.card-head{padding:14px 22px;border-bottom:1px solid var(--gl);background:var(--gll);display:flex;align-items:center;gap:10px}
.card-icon{width:38px;height:38px;background:linear-gradient(135deg,var(--gl),#CCEF90);border:1px solid var(--bd2);border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:17px}
.card-title{font-size:14px;font-weight:900;color:var(--char)}
.fb{padding:18px 22px}
.sec-t{font-size:9px;font-weight:800;letter-spacing:3px;color:var(--tx3);text-transform:uppercase;display:flex;align-items:center;gap:6px;margin-bottom:14px}
.sec-t::before,.sec-t::after{content:'';flex:1;height:1px;background:var(--bd)}
.sec-t::before{background:linear-gradient(90deg,var(--bd),transparent)}
.g2{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px}
.g1{margin-bottom:14px}
.flabel{font-size:12px;font-weight:700;color:var(--tx);display:block;margin-bottom:6px}
.req{color:var(--pk)}
.dinput,.dselect,.dtextarea{width:100%;background:var(--gll);border:1.5px solid var(--bd);border-radius:9px;padding:10px 13px;font-size:13px;color:var(--tx);outline:none;transition:all .2s;font-family:'Be Vietnam Pro',sans-serif}
.dinput:focus,.dselect:focus,.dtextarea:focus{border-color:var(--g);background:#fff}
.content-area{min-height:320px;resize:vertical}
.divider{height:1px;background:linear-gradient(90deg,transparent,var(--bd) 25%,var(--bd) 75%,transparent);margin:5px 0 18px}
.btn-save{display:inline-flex;align-items:center;gap:7px;padding:11px 24px;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;font-size:13px;font-weight:800;border:none;border-radius:9px;cursor:pointer}
.btn-cancel{padding:11px 18px;background:#fff;border:1.5px solid var(--bd);color:var(--tx3);font-size:12px;font-weight:600;border-radius:9px;text-decoration:none;display:inline-flex}
.tgl{position:relative;width:40px;height:22px;display:inline-block}
.tgl input{opacity:0;width:0;height:0;position:absolute}
.tgl-s{position:absolute;inset:0;background:#D1D5DB;border-radius:50px;cursor:pointer;transition:background .2s}
.tgl-s::before{content:'';position:absolute;width:16px;height:16px;left:3px;top:3px;background:#fff;border-radius:50%;transition:transform .2s}
.tgl input:checked + .tgl-s{background:var(--g)}
.tgl input:checked + .tgl-s::before{transform:translateX(18px)}
.upload-zone{border:2px dashed var(--bd);border-radius:10px;padding:20px;text-align:center;background:var(--gll);cursor:pointer;transition:all .2s;display:block}
.upload-zone:hover{border-color:var(--g);background:#EAFFC8}
.img-prev{margin-top:10px;border-radius:10px;overflow:hidden;border:1.5px solid var(--bd)}
.img-prev img{width:100%;height:200px;object-fit:cover;display:block}
.fnote{font-size:10px;color:var(--tx3);margin-top:4px}
</style>
</head>
<body>
<div class="layout">
  @include('admin.partials.sidebar')
  <div class="main">
    <div class="tb">
      <div>
        <div class="tb-bc"><a href="{{ route('admin.posts.index') }}">Blog</a> › <b>{{ $post ? 'Chỉnh sửa' : 'Thêm mới' }}</b></div>
        <div class="tb-title">{{ $post ? 'Sửa: '.Str::limit($post->title,30) : 'Thêm Bài Viết Mới' }}</div>
      </div>
      <a href="{{ route('admin.posts.index') }}" class="btn-cancel">← Quay lại</a>
    </div>
    <div class="sak"><span style="animation:drift 5s ease-in-out infinite;display:inline-block">🌸</span><span class="sak-t">DALI · TÔ ĐIỂM CUỘC SỐNG</span></div>
    <div class="cnt">
      <div class="form-card">
        <div class="rainbow"></div>
        <div class="card-head"><div class="card-icon">📝</div><div><div class="card-title">{{ $post ? 'Cập nhật bài viết' : 'Thêm bài viết mới' }}</div></div></div>
        <div class="fb">
          <form method="POST" action="{{ $post ? route('admin.posts.update', $post) : route('admin.posts.store') }}" enctype="multipart/form-data">
            @csrf @if($post) @method('PUT') @endif

            <div class="sec-t">Nội dung bài viết</div>
            <div class="g1"><label class="flabel">Tiêu đề <span class="req">*</span></label><input type="text" name="title" class="dinput" value="{{ old('title',$post->title??'') }}" placeholder="Hướng dẫn tô màu số hóa cho người mới bắt đầu" required>@error('title')<div style="font-size:11px;color:#EF4444;margin-top:4px">{{ $message }}</div>@enderror</div>
            <div class="g2">
              <div>
                <label class="flabel">Danh mục <span class="req">*</span></label>
                <select name="category" class="dselect" required>
                  @foreach(['Hướng dẫn','Cảm hứng','Tin tức','Mẹo & Kỹ thuật'] as $cat)
                  <option value="{{ $cat }}" {{ old('category',$post->category??'Hướng dẫn')===$cat?'selected':'' }}>{{ $cat }}</option>
                  @endforeach
                </select>
              </div>
              <div>
                <label class="flabel">Thứ tự hiển thị</label>
                <input type="number" name="sort_order" class="dinput" value="{{ old('sort_order',$post->sort_order??0) }}" min="0">
              </div>
            </div>
            <div class="g1"><label class="flabel">Tóm tắt ngắn</label><textarea name="excerpt" class="dinput" rows="2" placeholder="Một đoạn ngắn mô tả nội dung bài viết...">{{ old('excerpt',$post->excerpt??'') }}</textarea></div>
            <div class="g1"><label class="flabel">Nội dung bài viết <span class="req">*</span></label><textarea name="content" class="dinput content-area" placeholder="Nhập nội dung bài viết...">{{ old('content',$post->content??'') }}</textarea><div class="fnote">Hỗ trợ xuống dòng, HTML cơ bản sẽ được hiển thị.</div></div>

            <div class="divider"></div>
            <div class="sec-t">Ảnh bìa</div>
            <div>
              <input type="file" id="cover_img" name="cover_image" accept="image/*" style="display:none" onchange="prevImg(this)">
              <label for="cover_img" class="upload-zone"><span style="font-size:24px;color:var(--bd2);display:block;margin-bottom:6px">☁️</span><div style="font-size:12px;color:var(--tx3)">Nhấn để chọn ảnh bìa<br><span style="opacity:.5;font-size:10px">JPG, PNG · Tối đa 5MB</span></div></label>
              @if($post && $post->cover_image)
              <div class="img-prev" id="imgPrev"><img src="{{ asset('storage/'.$post->cover_image) }}" alt=""></div>
              @else<div id="imgPrev"></div>@endif
            </div>

            <div class="divider" style="margin-top:18px"></div>
            <div class="sec-t">SEO</div>
            <div class="g2">
              <div><label class="flabel">Meta Title</label><input type="text" name="meta_title" class="dinput" value="{{ old('meta_title',$post->meta_title??'') }}" placeholder="Tiêu đề SEO (tối đa 60 ký tự)"></div>
              <div><label class="flabel">Meta Description</label><input type="text" name="meta_description" class="dinput" value="{{ old('meta_description',$post->meta_description??'') }}" placeholder="Mô tả SEO (tối đa 160 ký tự)"></div>
            </div>

            <div class="divider"></div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px">
              <div style="display:flex;align-items:center;gap:10px">
                <label class="tgl"><input type="checkbox" name="is_published" value="1" {{ old('is_published',$post->is_published??false)?'checked':'' }}><span class="tgl-s"></span></label>
                <span style="font-size:13px;font-weight:600;color:var(--tx2)">Đăng bài ngay lập tức</span>
              </div>
            </div>
            <div style="display:flex;gap:10px">
              <button type="submit" class="btn-save">✅ {{ $post ? 'Lưu thay đổi' : 'Đăng bài viết' }}</button>
              <a href="{{ route('admin.posts.index') }}" class="btn-cancel">Huỷ bỏ</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
function prevImg(input){if(!input.files||!input.files[0])return;const r=new FileReader();r.onload=e=>{document.getElementById('imgPrev').innerHTML='<div class="img-prev"><img src="'+e.target.result+'" alt=""></div>';};r.readAsDataURL(input.files[0]);}
</script>
</body>
</html>