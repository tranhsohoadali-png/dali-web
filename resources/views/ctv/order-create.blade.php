@extends('ctv.layout')
@section('title','Lên đơn')
@section('content')

<style>
/* ── PICKER ── */
.picker-search{position:relative;margin-bottom:10px}
.picker-search input{width:100%;border:1.5px solid var(--bd);border-radius:12px;padding:11px 14px 11px 38px;font-size:14px;font-family:inherit;background:#fff;color:var(--tx)}
.picker-search input:focus{outline:none;border-color:var(--g)}
.picker-search .si{position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:16px;pointer-events:none}
.cat-tabs{display:flex;gap:6px;overflow-x:auto;padding-bottom:4px;margin-bottom:12px;scrollbar-width:none}
.cat-tabs::-webkit-scrollbar{display:none}
.cat-tab{flex-shrink:0;padding:5px 13px;border-radius:50px;border:1.5px solid var(--bd);background:#fff;font-size:12px;font-weight:700;color:var(--tx2);cursor:pointer;white-space:nowrap;transition:all .2s}
.cat-tab.act{background:var(--g);color:#fff;border-color:var(--g)}
.prod-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;max-height:300px;overflow-y:auto;padding:2px 1px}
.prod-card{border:2px solid var(--bd);border-radius:12px;overflow:hidden;cursor:pointer;transition:all .2s;background:#fff;position:relative}
.prod-card:hover{border-color:var(--g);transform:translateY(-1px);box-shadow:0 4px 12px rgba(107,191,31,.2)}
.prod-card.in-cart{border-color:var(--g);background:var(--gll)}
.prod-card.hidden{display:none}
.prod-card .tick{display:none;position:absolute;top:5px;right:5px;background:var(--g);color:#fff;border-radius:50%;width:20px;height:20px;align-items:center;justify-content:center;font-size:11px;font-weight:900;z-index:2}
.prod-card.in-cart .tick{display:flex}
.prod-img{aspect-ratio:1/1;overflow:hidden;background:var(--gll)}
.prod-img img{width:100%;height:100%;object-fit:cover;transition:transform .3s}
.prod-card:hover .prod-img img{transform:scale(1.06)}
.prod-info{padding:6px 7px}
.prod-name{font-size:10px;font-weight:700;color:var(--tx);line-height:1.35;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.prod-price{font-size:11px;font-weight:800;color:var(--gd);margin-top:2px}
.no-result{grid-column:1/-1;text-align:center;padding:28px;color:var(--tx3);font-size:13px}
/* ── CART ITEMS ── */
.cart-item{background:#fff;border:1.5px solid var(--bd);border-radius:14px;padding:12px;margin-bottom:10px;display:flex;gap:10px;align-items:flex-start}
.cart-item img{width:52px;height:52px;object-fit:cover;border-radius:9px;flex-shrink:0;border:1.5px solid var(--bd)}
.cart-item .ci-body{flex:1;min-width:0}
.cart-item .ci-name{font-size:12.5px;font-weight:800;color:var(--gd);margin-bottom:6px;line-height:1.3}
.cart-item select,.cart-item .qty-row input{border:1.5px solid var(--bd);border-radius:9px;padding:7px 10px;font-size:13px;font-family:inherit;background:var(--gll);width:100%}
.cart-item select:focus,.cart-item .qty-row input:focus{outline:none;border-color:var(--g)}
.cart-item .qty-row{display:flex;align-items:center;gap:8px;margin-top:7px}
.cart-item .qty-row label{font-size:11.5px;font-weight:700;color:var(--tx2);white-space:nowrap}
.cart-item .qty-row input{width:80px;text-align:center}
.cart-item .ci-sub{font-size:12px;font-weight:800;color:var(--g);margin-top:5px}
.cart-item .rm-btn{flex-shrink:0;background:none;border:none;font-size:20px;cursor:pointer;color:#FCA5A5;padding:2px;line-height:1;align-self:flex-start}
.cart-item .rm-btn:hover{color:#EF4444}
/* ── TOTAL BAR ── */
.total-bar{background:linear-gradient(135deg,var(--gd),var(--g));color:#fff;border-radius:14px;padding:14px 16px;margin-bottom:14px}
.total-bar .row{display:flex;justify-content:space-between;align-items:center;font-size:13px;margin-bottom:4px}
.total-bar .row:last-child{margin-bottom:0;padding-top:8px;border-top:1px solid rgba(255,255,255,.25);margin-top:4px}
.total-bar .row .k{font-weight:600;opacity:.9}
.total-bar .row .v{font-weight:800}
.total-bar .row.big .k{font-size:12px;font-weight:700;opacity:.85}
.total-bar .row.big .v{font-size:19px;font-weight:900}
.total-bar .row.green-row .v{color:#C6F135}
/* ── DESKTOP: rộng hơn, nhiều tranh hơn, danh mục tự xuống hàng (hết kẹt trượt) ── */
@media(min-width:800px){
  .page-body{max-width:1100px}
  .cat-tabs{flex-wrap:wrap;overflow-x:visible;padding-bottom:0}
  .cat-tab{font-size:13px;padding:6px 15px}
  .prod-grid{grid-template-columns:repeat(6,1fr);gap:10px;max-height:720px}
  .prod-info{padding:8px 9px}
  .prod-name{font-size:12px}
  .prod-price{font-size:13px}
}
</style>

<div style="font-size:17px;font-weight:900;color:var(--gd);margin-bottom:14px">➕ Lên đơn mới</div>

<form method="POST" action="{{ route('ctv.order.store') }}" id="orderForm">
  @csrf

  {{-- ① CHỌN TRANH --}}
  <div class="card" style="margin-bottom:12px">
    <div class="card-title"><span class="ic">🎨</span>Chọn tranh <span id="cartBadge" style="display:none;background:var(--g);color:#fff;font-size:11px;padding:2px 8px;border-radius:20px;font-weight:700"></span></div>

    <div class="picker-search">
      <span class="si">🔍</span>
      <input type="text" id="searchInput" placeholder="Tìm tên tranh hoặc mã..." oninput="filterProducts()">
    </div>
    <div class="cat-tabs">
      <button type="button" class="cat-tab act" data-cat="all" onclick="filterCat(this,'all')">Tất cả</button>
      @foreach($categories as $catId => $catName)
        <button type="button" class="cat-tab" data-cat="{{ $catId }}" onclick="filterCat(this,'{{ $catId }}')">{{ $catName }}</button>
      @endforeach
    </div>
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
             onclick="toggleProduct(this)">
          <div class="tick">✓</div>
          <div class="prod-img">
            @if($p->main_image)
              <img src="{{ asset('storage/'.$p->main_image) }}" alt="{{ $p->name }}" loading="lazy">
            @else
              <div style="height:100%;display:flex;align-items:center;justify-content:center;font-size:28px;background:var(--gll)">🎨</div>
            @endif
          </div>
          <div class="prod-info">
            <div class="prod-name">{{ $p->name }}</div>
            <div class="prod-price">{{ number_format($p->price,0,',','.') }}đ</div>
          </div>
        </div>
      @endforeach
      <div class="no-result" id="noResult" style="display:none">Không tìm thấy tranh 🔍</div>
    </div>
    <p class="muted" style="text-align:center;margin-top:8px;font-size:11.5px">Bấm vào tranh để thêm / bỏ chọn</p>
  </div>

  {{-- ② GIỎ TRANH ĐÃ CHỌN --}}
  <div id="cartSection" style="display:none">
    <div style="font-size:13px;font-weight:800;color:var(--gd);margin-bottom:10px;display:flex;align-items:center;gap:6px">🛒 Tranh đã chọn <span id="cartCount2" style="background:var(--g);color:#fff;font-size:11px;padding:1px 8px;border-radius:20px"></span></div>
    <div id="cartItems"></div>

    {{-- Tổng tiền --}}
    <div class="total-bar">
      <div class="row"><span class="k">Tiền hàng</span><span class="v" id="subtotalDisplay">0đ</span></div>
      <div class="row" id="shipRow"><span class="k" id="shipLabel">Phí ship</span><span class="v" id="shipDisplay">—</span></div>
      <div class="row big"><span class="k">Tổng đơn</span><span class="v" id="totalDisplay">0đ</span></div>
      <div class="row green-row"><span class="k">@if($ctv->isAgent())💰 Đặt cọc ({{ $ctv->effectiveDepositPercent((int)($settings['agent_deposit_percent'] ?? 20)) }}%)@else 🌿 Hoa hồng của bạn @endif</span><span class="v" id="commSummary">0đ</span></div>
      <div style="font-size:11px;opacity:.9;margin-top:8px;padding-top:8px;border-top:1px solid rgba(255,255,255,.25);line-height:1.5">🚚 Đơn sỉ chưa tính phí ship — <b>shop sẽ báo phí ship sau</b> qua tin nhắn / gọi điện.</div>
    </div>
  </div>

  {{-- ③ THÔNG TIN KHÁCH --}}
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
    @if($ctv->isAgent())Tạo đơn & đặt cọc →@else Tạo đơn & nhận hoa hồng →@endif
  </button>
  <p class="muted" style="text-align:center;margin-top:10px;display:none" id="submitNote">@if($ctv->isAgent())Sau khi tạo đơn, bạn chuyển khoản đặt cọc theo hướng dẫn.@else Hoa hồng tự động ghi nhận sau khi tạo đơn.@endif</p>
</form>

<script>
const SIZES_DATA = @json($sizes->map(fn($s)=>['id'=>$s->id,'name'=>$s->name,'price'=>(int)$s->price])->values());
const sizeById = Object.fromEntries(SIZES_DATA.map(s=>[String(s.id), s]));
const COMM_RATE = {{ $ctv->commission_rate }};
const IS_AGENT = {{ $ctv->isAgent() ? 'true' : 'false' }};
const DEPOSIT_PCT = {{ $ctv->effectiveDepositPercent((int)($settings['agent_deposit_percent'] ?? 20)) }};
const FREE_SHIP_FROM = {{ (int)($settings['free_ship_from'] ?? 299000) }};
const SHIP_FEE = {{ (int)($settings['ship_fee'] ?? 30000) }};
let activeCat = 'all';

// cart = { productId: { name, img, price, sizeIds, selectedSizeId, qty } }
const cart = {};

/* ─── FILTER ─── */
function filterProducts() {
  const q = document.getElementById('searchInput').value.toLowerCase().trim();
  let vis = 0;
  document.querySelectorAll('.prod-card').forEach(c => {
    const matchQ = !q || (c.dataset.name||'').includes(q);
    const matchC = activeCat === 'all' || c.dataset.cat === activeCat;
    const show = matchQ && matchC;
    c.classList.toggle('hidden', !show);
    if (show) vis++;
  });
  document.getElementById('noResult').style.display = vis === 0 ? 'block' : 'none';
}
function filterCat(btn, cat) {
  activeCat = cat;
  document.querySelectorAll('.cat-tab').forEach(b => b.classList.remove('act'));
  btn.classList.add('act');
  filterProducts();
}

/* ─── TOGGLE sản phẩm ─── */
function toggleProduct(card) {
  const id = card.dataset.id;
  if (cart[id]) {
    // bỏ chọn
    delete cart[id];
    card.classList.remove('in-cart');
  } else {
    // thêm vào cart
    let sizeIds = [];
    try { sizeIds = JSON.parse(card.dataset.sizes || '[]'); } catch(e){}
    cart[id] = {
      name: card.dataset.display,
      img:  card.dataset.img || '',
      price: parseInt(card.dataset.price) || 0,
      sizeIds: sizeIds,
      selectedSizeId: sizeIds.length ? String(sizeIds[0]) : '',
      qty: 1,
    };
    card.classList.add('in-cart');
  }
  renderCart();
}

/* ─── RENDER giỏ ─── */
function renderCart() {
  const ids = Object.keys(cart);
  const cartSection = document.getElementById('cartSection');
  const customerCard = document.getElementById('customerCard');
  const submitBtn = document.getElementById('submitBtn');
  const submitNote = document.getElementById('submitNote');

  // badge
  const badge = document.getElementById('cartBadge');
  const cnt2 = document.getElementById('cartCount2');
  if (ids.length) {
    badge.style.display = 'inline';
    badge.textContent = ids.length + ' tranh';
    cnt2.textContent = ids.length;
  } else {
    badge.style.display = 'none';
  }

  if (ids.length === 0) {
    cartSection.style.display = 'none';
    customerCard.style.display = 'none';
    submitBtn.style.display = 'none';
    submitNote.style.display = 'none';
    return;
  }

  cartSection.style.display = 'block';
  customerCard.style.display = 'block';
  submitBtn.style.display = 'flex';
  submitNote.style.display = 'block';

  // render items
  const wrap = document.getElementById('cartItems');
  wrap.innerHTML = '';
  ids.forEach(id => {
    const item = cart[id];
    const sizeOptions = item.sizeIds.map(sid => {
      const s = sizeById[String(sid)];
      if (!s) return '';
      const sel = String(sid) === item.selectedSizeId ? 'selected' : '';
      return `<option value="${s.id}" ${sel}>${s.name} — ${s.price.toLocaleString('vi-VN')}đ</option>`;
    }).join('');
    const showSize = item.sizeIds.length > 0;
    const sub = calcSub(id);
    wrap.innerHTML += `
    <div class="cart-item" id="ci-${id}">
      ${item.img ? `<img src="${item.img}" alt="">` : '<div style="width:52px;height:52px;background:var(--gll);border-radius:9px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:22px">🎨</div>'}
      <div class="ci-body">
        <div class="ci-name">${item.name}</div>
        ${showSize ? `<select onchange="onSizeChange('${id}',this.value)">${sizeOptions}</select>` : ''}
        <div class="qty-row">
          <label>Số lượng:</label>
          <input type="number" value="${item.qty}" min="1" max="99" onchange="onQtyChange('${id}',this.value)">
          <span style="font-size:12px;font-weight:800;color:var(--gd)" id="sub-${id}">${sub.toLocaleString('vi-VN')}đ</span>
        </div>
        <div class="ci-sub" id="comm-${id}">${IS_AGENT ? '' : '+'+Math.round(sub*COMM_RATE/100).toLocaleString('vi-VN')+'đ hoa hồng'}</div>
        ${buildHiddenInputs(id, item)}
      </div>
      <button type="button" class="rm-btn" onclick="removeItem('${id}')">✕</button>
    </div>`;
  });

  updateTotal();
}

function buildHiddenInputs(id, item) {
  const sizeId = item.selectedSizeId || '';
  return `<input type="hidden" name="items[${id}][product_id]" value="${id}">
          <input type="hidden" name="items[${id}][size_id]" value="${sizeId}" id="hi-size-${id}">
          <input type="hidden" name="items[${id}][qty]" value="${item.qty}" id="hi-qty-${id}">`;
}

function onSizeChange(id, sizeId) {
  cart[id].selectedSizeId = sizeId;
  document.getElementById(`hi-size-${id}`).value = sizeId;
  const sub = calcSub(id);
  document.getElementById(`sub-${id}`).textContent = sub.toLocaleString('vi-VN') + 'đ';
  document.getElementById(`comm-${id}`).textContent = IS_AGENT ? '' : ('+' + Math.round(sub*COMM_RATE/100).toLocaleString('vi-VN') + 'đ hoa hồng');
  updateTotal();
}

function onQtyChange(id, qty) {
  cart[id].qty = parseInt(qty) || 1;
  document.getElementById(`hi-qty-${id}`).value = cart[id].qty;
  const sub = calcSub(id);
  document.getElementById(`sub-${id}`).textContent = sub.toLocaleString('vi-VN') + 'đ';
  document.getElementById(`comm-${id}`).textContent = IS_AGENT ? '' : ('+' + Math.round(sub*COMM_RATE/100).toLocaleString('vi-VN') + 'đ hoa hồng');
  updateTotal();
}

function removeItem(id) {
  delete cart[id];
  const card = document.querySelector(`.prod-card[data-id="${id}"]`);
  if (card) card.classList.remove('in-cart');
  renderCart();
}

function calcSub(id) {
  const item = cart[id];
  let price = item.price;
  if (item.selectedSizeId && sizeById[item.selectedSizeId]) {
    price = sizeById[item.selectedSizeId].price;
  }
  return price * item.qty;
}

function updateTotal() {
  const subtotal = Object.keys(cart).reduce((s, id) => s + calcSub(id), 0);
  // Đơn sỉ/CTV: KHÔNG áp miễn phí ship, KHÔNG cộng phí ship vào đơn.
  // Phí ship sẽ báo sau qua tin nhắn / gọi điện.
  const total = subtotal;
  const comm = IS_AGENT ? Math.round(total * DEPOSIT_PCT / 100) : Math.round(total * COMM_RATE / 100);

  document.getElementById('subtotalDisplay').textContent = subtotal.toLocaleString('vi-VN') + 'đ';
  document.getElementById('shipLabel').textContent = 'Phí ship';
  document.getElementById('shipDisplay').textContent = subtotal === 0 ? '—' : 'Báo sau';

  document.getElementById('totalDisplay').textContent = total.toLocaleString('vi-VN') + 'đ';
  document.getElementById('commSummary').textContent = (IS_AGENT ? '' : '+') + comm.toLocaleString('vi-VN') + 'đ';
}
</script>
@endsection
