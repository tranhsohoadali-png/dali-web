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
/* ── Thanh công cụ soạn thảo ── */
.ed-toolbar{display:flex;flex-wrap:wrap;gap:4px;background:#fff;border:1.5px solid var(--bd);border-bottom:none;border-radius:9px 9px 0 0;padding:7px 8px;position:sticky;top:0;z-index:5}
.ed-toolbar button{background:var(--gll);border:1px solid var(--bd);border-radius:7px;padding:6px 10px;font-size:12px;font-weight:700;color:var(--gd);cursor:pointer;font-family:'Be Vietnam Pro',sans-serif;transition:all .15s;line-height:1.2}
.ed-toolbar button:hover{background:var(--g);color:#fff;border-color:var(--g)}
.ed-toolbar button.on{background:var(--gd);color:#fff;border-color:var(--gd)}
.ed-sep{width:1px;background:var(--bd);margin:2px 3px}
.content-area{border-radius:0 0 9px 9px!important;min-height:340px;resize:vertical;line-height:1.7}
/* ── Khung xem trước (giống trang blog) ── */
.content-preview{border:1.5px solid var(--bd);border-top:none;border-radius:0 0 9px 9px;background:#fff;padding:18px 22px;min-height:340px;max-height:620px;overflow:auto;color:var(--tx2);line-height:1.85;font-size:14px}
.content-preview h2{font-size:20px;font-weight:800;color:var(--char);margin:20px 0 10px;padding-left:13px;border-left:5px solid var(--g)}
.content-preview h3{font-size:17px;font-weight:700;color:var(--char);margin:16px 0 8px}
.content-preview p{margin-bottom:13px}
.content-preview ul,.content-preview ol{margin:0 0 13px 22px}
.content-preview li{margin-bottom:5px}
.content-preview a{color:var(--g);font-weight:600}
.content-preview img{max-width:100%;border-radius:10px;border:1.5px solid var(--bd)}
.content-preview figure{margin:16px 0}
.content-preview figcaption{font-size:12px;color:var(--tx3);text-align:center;font-style:italic;margin-top:6px}
.content-preview blockquote{background:var(--gll);border-left:4px solid var(--g);border-radius:0 10px 10px 0;padding:13px 18px;margin:16px 0;font-style:italic;color:var(--gd)}
.content-preview .lead{font-size:15px;color:var(--tx);font-weight:500;margin-bottom:14px}
.content-preview .tip-box{background:linear-gradient(135deg,var(--gll),#fff);border:1.5px solid var(--bd);border-radius:12px;padding:13px 16px;margin:14px 0}
.content-preview .faq-q{font-size:15px;font-weight:800;color:var(--char);margin:14px 0 5px}
.content-preview .faq-q::before{content:'❓ '}
.char-count{font-size:10px;color:var(--tx3);text-align:right;margin-top:3px}
.char-count.over{color:#EF4444;font-weight:700}
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
            <div class="g1">
              <label class="flabel">Nội dung bài viết <span class="req">*</span></label>
              <div class="ed-toolbar" id="edToolbar">
                <button type="button" onclick="tbWrap('<strong>','</strong>')" title="In đậm"><b>B</b></button>
                <button type="button" onclick="tbWrap('<em>','</em>')" title="In nghiêng"><i>I</i></button>
                <span class="ed-sep"></span>
                <button type="button" onclick="tbBlock('h2')" title="Tiêu đề mục lớn">H2</button>
                <button type="button" onclick="tbBlock('h3')" title="Tiêu đề phụ">H3</button>
                <button type="button" onclick="tbList('ul')" title="Danh sách gạch đầu dòng (mỗi dòng 1 mục)">• Danh sách</button>
                <button type="button" onclick="tbList('ol')" title="Danh sách đánh số">1. Đánh số</button>
                <span class="ed-sep"></span>
                <button type="button" onclick="tbLead()" title="Đoạn mở đầu nổi bật">Mở đầu</button>
                <button type="button" onclick="tbTip()" title="Hộp mẹo / lưu ý">💡 Mẹo</button>
                <button type="button" onclick="tbFaq()" title="Câu hỏi thường gặp">❓ FAQ</button>
                <button type="button" onclick="tbQuote()" title="Trích dẫn">❝ Trích</button>
                <button type="button" onclick="tbLink()" title="Chèn liên kết">🔗 Link</button>
                <button type="button" onclick="tbImage()" title="Chèn ảnh bằng URL">🖼️ Ảnh</button>
                <span class="ed-sep"></span>
                <button type="button" onclick="tbPreview()" id="edPrevBtn" title="Xem trước / Soạn tiếp">👁️ Xem trước</button>
              </div>
              <textarea name="content" id="contentArea" class="dinput content-area" placeholder="Nhập nội dung... Mẹo: bôi đen chữ rồi bấm nút (B, H2, 💡 Mẹo...) ở thanh trên — không cần biết HTML.">{{ old('content',$post->content??'') }}</textarea>
              <div class="content-preview" id="contentPreview" style="display:none"></div>
              <div class="fnote">Bấm các nút phía trên để chèn định dạng — không cần biết HTML. Nút <b>👁️ Xem trước</b> để xem bài hiển thị thật.</div>
            </div>

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
              <div><label class="flabel">Meta Title</label><input type="text" name="meta_title" id="metaTitle" maxlength="75" class="dinput" value="{{ old('meta_title',$post->meta_title??'') }}" placeholder="Tiêu đề SEO (nên ≤ 60 ký tự)" oninput="cc('metaTitle','ccTitle',60)"><div class="char-count" id="ccTitle"></div></div>
              <div><label class="flabel">Meta Description</label><input type="text" name="meta_description" id="metaDesc" maxlength="200" class="dinput" value="{{ old('meta_description',$post->meta_description??'') }}" placeholder="Mô tả SEO (nên ≤ 160 ký tự)" oninput="cc('metaDesc','ccDesc',160)"><div class="char-count" id="ccDesc"></div></div>
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

/* ── Trình soạn thảo nội dung ── */
var ED=document.getElementById('contentArea');
function _put(s,e,text,selA,selB){
  ED.value=ED.value.slice(0,s)+text+ED.value.slice(e);
  ED.focus();
  var a=(selA!=null)?selA:s+text.length, b=(selB!=null)?selB:a;
  try{ED.setSelectionRange(a,b);}catch(_){}
}
function _r(){return [ED.selectionStart,ED.selectionEnd];}
function _nl(p){return (p>0 && ED.value[p-1]!=='\n')?'\n':'';}
function tbWrap(b,a){var r=_r();var t=ED.value.slice(r[0],r[1]);_put(r[0],r[1],b+t+a,r[0]+b.length,r[0]+b.length+t.length);}
function tbBlock(tag){var r=_r();var t=ED.value.slice(r[0],r[1])||'Tiêu đề';_put(r[0],r[1],_nl(r[0])+'<'+tag+'>'+t+'</'+tag+'>\n');}
function _ins(html){var r=_r();_put(r[0],r[1],_nl(r[0])+html+'\n');}
function tbList(type){var r=_r();var t=ED.value.slice(r[0],r[1]);
  var items=t?t.split('\n').filter(function(x){return x.trim();}):['Mục thứ nhất','Mục thứ hai','Mục thứ ba'];
  _ins('<'+type+'>\n'+items.map(function(x){return '  <li>'+x.trim()+'</li>';}).join('\n')+'\n</'+type+'>');}
function tbLead(){var r=_r();var t=ED.value.slice(r[0],r[1])||'Đoạn mở đầu nổi bật của bài viết...';_ins('<p class="lead">'+t+'</p>');}
function tbTip(){var r=_r();var t=ED.value.slice(r[0],r[1])||'Nội dung mẹo / lưu ý...';_ins('<div class="tip-box"><strong>💡 Mẹo:</strong> '+t+'</div>');}
function tbFaq(){var r=_r();var t=ED.value.slice(r[0],r[1])||'Câu hỏi của khách?';_ins('<div class="faq-q">'+t+'</div>\n<p>Câu trả lời...</p>');}
function tbQuote(){var r=_r();var t=ED.value.slice(r[0],r[1])||'Câu trích dẫn...';_ins('<blockquote>'+t+'</blockquote>');}
function tbLink(){var r=_r();var t=ED.value.slice(r[0],r[1]);var u=prompt('Dán đường link (URL):','https://');if(!u)return;tbWrap('<a href="'+u+'">', '</a>');if(!t){/* không có chữ chọn -> chèn nhãn */}}
function tbImage(){var u=prompt('Dán URL ảnh:','https://');if(!u)return;var c=prompt('Chú thích ảnh (để trống nếu không cần):','')||'';_ins('<figure><img src="'+u+'" alt="">'+(c?'<figcaption>'+c+'</figcaption>':'')+'</figure>');}
function tbPreview(){
  var p=document.getElementById('contentPreview'), b=document.getElementById('edPrevBtn');
  if(p.style.display==='none'){
    p.innerHTML=ED.value.trim()?ED.value:'<p style="color:#aaa">(Chưa có nội dung)</p>';
    p.style.display='block';ED.style.display='none';b.classList.add('on');b.innerHTML='✏️ Soạn tiếp';
  }else{
    p.style.display='none';ED.style.display='block';b.classList.remove('on');b.innerHTML='👁️ Xem trước';
  }
}
/* ── Đếm ký tự SEO ── */
function cc(id,ccId,max){var el=document.getElementById(id),out=document.getElementById(ccId);if(!el||!out)return;var n=el.value.length;out.textContent=n+'/'+max+(n>max?' (hơi dài)':'');out.classList.toggle('over',n>max);}
document.addEventListener('DOMContentLoaded',function(){cc('metaTitle','ccTitle',60);cc('metaDesc','ccDesc',160);});
</script>
</body>
</html>