@extends('ctv.layout')
@section('title','Lên đơn')
@section('content')

<div style="font-size:17px;font-weight:900;color:var(--gd);margin-bottom:16px">➕ Lên đơn mới</div>

<form method="POST" action="{{ route('ctv.order.store') }}" id="orderForm">
  @csrf

  {{-- Thông tin tranh --}}
  <div class="card">
    <div class="card-title"><span class="ic">🎨</span>Chọn sản phẩm</div>
    <div class="field">
      <label>Tranh tô màu *</label>
      <select name="product_id" id="productSel" required onchange="loadSizes()">
        <option value="">— Chọn tranh —</option>
        @foreach($products as $p)
          <option value="{{ $p->id }}"
            data-sizes='@json($p->size_ids ?? [])'
            data-price="{{ $p->price }}">{{ $p->name }}</option>
        @endforeach
      </select>
    </div>

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
        <div style="font-size:10.5px;color:var(--tx3);font-weight:700;margin-bottom:2px">Tổng tiền</div>
        <div id="priceDisplay" style="font-size:18px;font-weight:900;color:var(--gd)">—</div>
        <div id="commDisplay" style="font-size:11px;color:var(--g);font-weight:700"></div>
      </div>
    </div>
  </div>

  {{-- Thông tin khách --}}
  <div class="card">
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
      <textarea name="note" rows="2" placeholder="Yêu cầu đặc biệt, màu sắc...">{{ old('note') }}</textarea>
    </div>
  </div>

  <button type="submit" class="btn full" id="submitBtn" disabled>Tạo đơn & nhận hoa hồng →</button>
  <p class="muted" style="text-align:center;margin-top:10px">Hoa hồng tự động được ghi nhận sau khi tạo đơn thành công.</p>
</form>

<script>
const SIZES_DATA = @json($sizes->map(fn($s)=>['id'=>$s->id,'name'=>$s->name,'price'=>(int)$s->price])->values());
const sizeById = Object.fromEntries(SIZES_DATA.map(s=>[String(s.id), s]));
const COMM_RATE = {{ $ctv->commission_rate }};

function loadSizes(){
  const opt = document.getElementById('productSel').selectedOptions[0];
  const wrap = document.getElementById('sizeWrap');
  const sel = document.getElementById('sizeSel');
  sel.innerHTML = '<option value="">— Không chọn kích thước —</option>';
  if(!opt.value){ wrap.style.display='none'; updatePrice(); return; }
  let ids = [];
  try{ ids = JSON.parse(opt.getAttribute('data-sizes') || '[]'); }catch(e){}
  if(ids.length){
    wrap.style.display='block';
    ids.forEach(id=>{
      const s = sizeById[String(id)];
      if(s){ const o=document.createElement('option');o.value=s.id;o.textContent=s.name+' — '+s.price.toLocaleString('vi-VN')+'đ';sel.appendChild(o); }
    });
    if(ids.length>0) sel.value = ids[0]; // pre-select first
  } else {
    wrap.style.display='none';
  }
  updatePrice();
}

function updatePrice(){
  const prodOpt = document.getElementById('productSel').selectedOptions[0];
  const sizeOpt = document.getElementById('sizeSel').selectedOptions[0];
  const qty = parseInt(document.getElementById('qty').value)||1;
  const btn = document.getElementById('submitBtn');
  if(!prodOpt.value){ document.getElementById('priceDisplay').textContent='—'; document.getElementById('commDisplay').textContent=''; btn.disabled=true; return; }
  let price = parseInt(prodOpt.getAttribute('data-price'))||0;
  if(sizeOpt && sizeOpt.value){ const s=sizeById[sizeOpt.value]; if(s) price=s.price; }
  const total = price * qty;
  const comm = Math.round(total * COMM_RATE / 100);
  document.getElementById('priceDisplay').textContent = total.toLocaleString('vi-VN')+'đ';
  document.getElementById('commDisplay').textContent = '+'+comm.toLocaleString('vi-VN')+'đ hoa hồng';
  btn.disabled = false;
}
</script>
@endsection
