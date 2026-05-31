@extends('ctv.layout')
@section('title','Lên đơn')
@section('content')

<style>
/* ── PRODUCT PICKER ── */
.picker-search{position:relative;margin-bottom:10px}
.picker-search input{width:100%;border:1.5px solid var(--bd);border-radius:12px;padding:11px 14px 11px 38px;font-size:14px;font-family:inherit;background:#fff;color:var(--tx)}
.picker-search input:focus{outline:none;border-color:var(--g)}
.picker-search .si{position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:16px;pointer-events:none}
.cat-tabs{display:flex;gap:6px;overflow-x:auto;padding-bottom:4px;margin-bottom:12px;scrollbar-width:none}
.cat-tabs::-webkit-scrollbar{display:none}
.cat-tab{flex-shrink:0;padding:5px 13px;border-radius:50px;border:1.5px solid var(--bd);background:#fff;font-size:12px;font-weight:700;color:var(--tx2);cursor:pointer;white-space:nowrap;transition:all .2s}
.cat-tab.act{background:var(--g);color:#fff;border-color:var(--g)}
.prod-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;max-height:320px;overflow-y:auto;padding:2px}
.prod-card{border:2px solid var(--bd);border-radius:12px;overflow:hidden;cursor:pointer;transition:all .2s;background:#fff;position:relative}
.prod-card:hover{border-color:var(--g);box-shadow:0 4px 12px rgba(107,191,31,.2);transform:translateY(-1px)}
.prod-card.selected{border-color:var(--g);box-shadow:0 0 0 3px rgba(107,191,31,.25)}
.prod-card.hidden{display:none}
.prod-card .tick{display:none;position:absolute;top:5px;right:5px;background:var(--g);color:#fff;border-radius:50%;width:20px;height:20px;align-items:center;justify-content:center;font-size:12px;font-weight:900;z-index:2}
.prod-card.selected .tick{display:flex}
.prod-img{aspect-ratio:1/1;overflow:hidden;background:var(--gll)}
.prod-img img{width:100%;height:100%;object-fit:cover;transition:transform .3s}
.prod-card:hover .prod-img img{transform:scale(1.06)}
.prod-info{padding:6px 7px}
.prod-name{font-size:10px;font-weight:700;color:var(--tx);line-height:1.35;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.prod-price{font-size:11px;font-weight:800;color:var(--gd);margin-top:2px}
.no-result{grid-column:1/-1;text-align:center;padding:28px;color:var(--tx3);font-size:13px}
/* Selected product display */
.selected-box{display:none;background:linear-gradient(135deg,var(--gll),#fff);border:2px solid var(--g);border-radius:14px;padding:12px;margin-bottom:12px;align-items:center;gap:10px}
.selected-box.show{display:flex}
.selected-box img{width:52px;height:52px;object-fit:cover;border-radius:9px;flex-shrink:0}
.selected-box .info{flex:1;min-width:0}
.selected-box .name{font-size:13px;font-weight:800;color:var(--gd);line-height:1.3}
.selected-box .price{font-size:12px;color:var(--tx2);margin-top:2px}
.selected-box .clear{background:none;border:none;font-size:18px;cursor:pointer;color:var(--tx3);flex-shrink:0}
</style>

<div style="font-size:17px;font-weight:900;color:var(--gd);margin-bottom:16px">➕ Lên đơn mới</div>

<form method="POST" action="{{ route('ctv.order.store') }}" id="orderForm">
  @csrf
  <input type="hidden" name="product_id" id="productId">

  {{-- CHỌN TRANH --}}
  <div class="card" style="margin-bottom:14px">
    <div class="card-title"><span class="ic">🎨</span>Chọn sản phẩm</div>

    {{-- Sản phẩm đã chọn --}}
    <div class="selected-box" id="selectedBox">
      <img id="selectedImg" src="" alt="">
      <div class="info">
        <div class="name" id="selectedName"></div>
        <div class="price" id="selectedPrice"></div>
      </div>
      <button type="button" class="clear" onclick="clearProduct()" title="Đổi tranh">✕</button>
    </div>

    {{-- Picker (ẩn khi đã chọn) --}}
    <div id="pickerWrap">
      {{-- Tìm kiếm --}}
      <div class="picker-search">
        <span class="si">🔍</span>
        <input type="text" id="searchInput" placeholder="Tìm tên tranh hoặc mã..." oninput="filterProducts()">
      </div>

      {{-- Tab danh mục --}}
      <div class="cat-tabs" id="catTabs">
        <button type="button" class="cat-tab act" data-cat="all" onclick="filterCat(this,'all')">Tất cả</button>
        @foreach($categories as $catId => $catName)
          <button type="button" class="cat-tab" data-cat="{{ $catId }}" onclick="filterCat(this,'{{ $catId }}')">{{ $catName }}</button>
        @endforeach
      </div>

      {{-- Lưới tranh --}}
      <div class="prod-grid" id="prodGrid">
        @foreach($products as $p)
          <div class="prod-card"
               data-id="{{ $p->id }}"
               data-cat="{{ $p->category_id }}"
               data-name="{{ strtolower($p->name) }}"
               data-sizes='@json($p->size_ids ?? [])'
               data-price="{{ $p->price }}"
               data-display="{{ $p->name }}"
               data-img="{{ $p->main_image ? asset('storage/'.$p->main_image) : '' }}"
               onclick="selectProduct(this)">
            <div class="tick">✓</div>
            <div class="prod-img">
              @if($p->main_image)
                <img src="{{ asset('storage/'.$p->main_image) }}" alt="{{ $p->name }}" loading="lazy">
              @else
                <div style="height:100%;display:flex;align-items:center;justify-content:center;font-size:28px">🎨</div>
              @endif
            </div>
            <div class="prod-info">
              <div class="prod-name">{{ $p->name }}</div>
              <div class="prod-price">{{ number_format($p->price,0,',','.') }}đ</div>
            </div>
          </div>
        @endforeach
        <div class="no-result" id="noResult" style="display:none">Không tìm thấy tranh nào 🔍</div>
      </div>
    </div>
  </div>

  {{-- KÍCH THƯỚC + SỐ LƯỢNG + GIÁ --}}
  <div class="card" id="sizeCard" style="display:none;margin-bottom:14px">
    <div class="card-title"><span class="ic">📐</span>Kích thước & số lượng</div>
    <div class="field" id="sizeWrap" style="display:none">
      <label>Kích thước</label>
      <select name="size_id" id="sizeSel" onchange="updatePrice()">
        <option value="">— Không chọn kích thước —</option>
      </select>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
      <div class="field" style="margin:0">
        <label>Số lượng *</label>
        <input type="number" name="quantity" id="qty" value="1" min="1" max="99" required oninput="updatePrice()">
      </div>
      <div style="background:var(--gll);border:1.5px solid var(--bd);border-radius:12px;padding:12px;display:flex;flex-direction:column;justify-content:center">
        <div style="font-size:10px;color:var(--tx3);font-weight:700;margin-bottom:2px">Tổng tiền</div>
        <div id="priceDisplay" style="font-size:18px;font-weight:900;color:var(--gd)">—</div>
        <div id="commDisplay" style="font-size:11px;color:var(--g);font-weight:700"></div>
      </div>
    </div>
  </div>

  {{-- THÔNG TIN KHÁCH --}}
  <div class="card" id="customerCard" style="display:none;margin-bottom:14px">
    <div class="card-title"><span class="ic">👤</span>Thông tin khách hàng</div>
    <div class="field">
      <label>Tên khách hàng *</label>
      <input type="text" name="customer_name" value="{{ old('customer_name') }}" placeholder="Nguyễn Văn A" required>
    </div>
    <div class="field">
      <label>Số điện thoại *</label>
      <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}" placeholder="0901 234 567" required inputmode="tel">
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
      <div class="field" style="margin:0">
        <label>Tỉnh / Thành phố</label>
        <input type="text" name="customer_city" value="{{ old('customer_city') }}" placeholder="Hà Nội">
      </div>
      <div class="field" style="margin:0">
        <label>Thanh toán</label>
        <select name="payment">
          <option value="COD">COD (nhận hàng)</option>
          <option value="BANK">Chuyển khoản</option>
        </select>
      </div>
    </div>
    <div class="field">
      <label>Địa chỉ giao hàng</label>
      <input type="text" name="customer_address" value="{{ old('customer_address') }}" placeholder="Số nhà, đường, phường/xã...">
    </div>
    <div class="field" style="margin:0">
      <label>Ghi chú đơn hàng</label>
      <textarea name="note" rows="2" placeholder="Yêu cầu đặc biệt...">{{ old('note') }}</textarea>
    </div>
  </div>

  <button type="submit" class="btn full" id="submitBtn" style="display:none">
    Tạo đơn & nhận hoa hồng →
  </button>
</form>

<script>
const SIZES_DATA = @json($sizes->map(fn($s)=>['id'=>$s->id,'name'=>$s->name,'price'=>(int)$s->price])->values());
const sizeById = Object.fromEntries(SIZES_DATA.map(s=>[String(s.id), s]));
const COMM_RATE = {{ $ctv->commission_rate }};
let activeCat = 'all';
let selectedCard = null;

function filterProducts() {
  const q = document.getElementById('searchInput').value.toLowerCase().trim();
  const cards = document.querySelectorAll('.prod-card');
  let visible = 0;
  cards.forEach(c => {
    const name = c.dataset.name || '';
    const cat = c.dataset.cat;
    const matchQ = !q || name.includes(q);
    const matchCat = activeCat === 'all' || cat === activeCat;
    const show = matchQ && matchCat;
    c.classList.toggle('hidden', !show);
    if (show) visible++;
  });
  document.getElementById('noResult').style.display = visible === 0 ? 'block' : 'none';
}

function filterCat(btn, cat) {
  activeCat = cat;
  document.querySelectorAll('.cat-tab').forEach(b => b.classList.remove('act'));
  btn.classList.add('act');
  filterProducts();
}

function selectProduct(card) {
  if (selectedCard) selectedCard.classList.remove('selected');
  selectedCard = card;
  card.classList.add('selected');

  const id = card.dataset.id;
  const name = card.dataset.display;
  const img = card.dataset.img;
  const price = parseInt(card.dataset.price) || 0;
  let sizeIds = [];
  try { sizeIds = JSON.parse(card.dataset.sizes || '[]'); } catch(e){}

  // update hidden input
  document.getElementById('productId').value = id;

  // show selected box
  const box = document.getElementById('selectedBox');
  document.getElementById('selectedImg').src = img || '';
  document.getElementById('selectedImg').style.display = img ? 'block' : 'none';
  document.getElementById('selectedName').textContent = name;
  document.getElementById('selectedPrice').textContent = price.toLocaleString('vi-VN') + 'đ';
  box.classList.add('show');

  // hide picker
  document.getElementById('pickerWrap').style.display = 'none';

  // show size/qty + customer cards
  document.getElementById('sizeCard').style.display = 'block';
  document.getElementById('customerCard').style.display = 'block';
  document.getElementById('submitBtn').style.display = 'flex';

  // populate sizes
  const sizeSel = document.getElementById('sizeSel');
  const sizeWrap = document.getElementById('sizeWrap');
  sizeSel.innerHTML = '<option value="">— Không chọn kích thước —</option>';
  if (sizeIds.length) {
    sizeIds.forEach(sid => {
      const s = sizeById[String(sid)];
      if (s) {
        const o = document.createElement('option');
        o.value = s.id;
        o.textContent = s.name + ' — ' + s.price.toLocaleString('vi-VN') + 'đ';
        sizeSel.appendChild(o);
      }
    });
    if (sizeIds.length > 0) sizeSel.value = sizeIds[0];
    sizeWrap.style.display = 'block';
  } else {
    sizeWrap.style.display = 'none';
  }

  // store base price on select element for updatePrice
  sizeSel.dataset.baseprice = price;
  updatePrice();

  // scroll to size/qty section
  setTimeout(()=>{ document.getElementById('sizeCard').scrollIntoView({behavior:'smooth',block:'nearest'}); }, 100);
}

function clearProduct() {
  if (selectedCard) selectedCard.classList.remove('selected');
  selectedCard = null;
  document.getElementById('productId').value = '';
  document.getElementById('selectedBox').classList.remove('show');
  document.getElementById('pickerWrap').style.display = 'block';
  document.getElementById('sizeCard').style.display = 'none';
  document.getElementById('customerCard').style.display = 'none';
  document.getElementById('submitBtn').style.display = 'none';
  document.getElementById('priceDisplay').textContent = '—';
  document.getElementById('commDisplay').textContent = '';
}

function updatePrice() {
  const sizeOpt = document.getElementById('sizeSel').selectedOptions[0];
  const baseprice = parseInt(document.getElementById('sizeSel').dataset.baseprice) || 0;
  const qty = parseInt(document.getElementById('qty').value) || 1;
  let price = baseprice;
  if (sizeOpt && sizeOpt.value) {
    const s = sizeById[sizeOpt.value];
    if (s) price = s.price;
  }
  const total = price * qty;
  const comm = Math.round(total * COMM_RATE / 100);
  document.getElementById('priceDisplay').textContent = total.toLocaleString('vi-VN') + 'đ';
  document.getElementById('commDisplay').textContent = '+' + comm.toLocaleString('vi-VN') + 'đ hoa hồng';
  document.getElementById('selectedPrice').textContent = price.toLocaleString('vi-VN') + 'đ';
}
</script>
@endsection
