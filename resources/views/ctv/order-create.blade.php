@extends('ctv.layout')
@section('title','Lên đơn')
@section('content')
<div class="card">
  <h2>➕ Lên đơn mới</h2>
  <form method="POST" action="{{ route('ctv.order.store') }}" id="orderForm">
    @csrf
    <div class="field">
      <label>Sản phẩm *</label>
      <select name="product_id" id="productSel" required>
        <option value="">— Chọn tranh —</option>
        @foreach($products as $p)
          <option value="{{ $p->id }}" data-sizes='@json($p->size_ids ?? [])' data-price="{{ $p->price }}">{{ $p->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="field">
      <label>Kích thước</label>
      <select name="size_id" id="sizeSel">
        <option value="">— Mặc định —</option>
      </select>
    </div>
    <div class="field">
      <label>Số lượng *</label>
      <input type="number" name="quantity" value="1" min="1" max="99" required>
    </div>
    <hr style="border:none;border-top:1px dashed var(--bd);margin:14px 0">
    <div class="field">
      <label>Tên khách hàng *</label>
      <input type="text" name="customer_name" value="{{ old('customer_name') }}" required>
    </div>
    <div class="field">
      <label>Số điện thoại khách *</label>
      <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" required>
    </div>
    <div class="field">
      <label>Tỉnh / Thành phố</label>
      <input type="text" name="customer_city" value="{{ old('customer_city') }}">
    </div>
    <div class="field">
      <label>Địa chỉ nhận hàng</label>
      <input type="text" name="customer_address" value="{{ old('customer_address') }}">
    </div>
    <div class="field">
      <label>Hình thức thanh toán</label>
      <select name="payment">
        <option value="COD">Thanh toán khi nhận (COD)</option>
        <option value="BANK">Chuyển khoản</option>
      </select>
    </div>
    <div class="field">
      <label>Ghi chú</label>
      <textarea name="note" rows="2">{{ old('note') }}</textarea>
    </div>
    <button type="submit" class="btn full">Tạo đơn & nhận hoa hồng</button>
  </form>
</div>

<script>
const SIZES = @json($sizes->map(fn($s)=>['id'=>$s->id,'name'=>$s->name,'price'=>$s->price])->values());
const sizeById = Object.fromEntries(SIZES.map(s=>[s.id, s]));
const prodSel = document.getElementById('productSel');
const sizeSel = document.getElementById('sizeSel');
prodSel.addEventListener('change', function(){
  const opt = this.selectedOptions[0];
  let ids = [];
  try { ids = JSON.parse(opt.getAttribute('data-sizes') || '[]'); } catch(e){}
  sizeSel.innerHTML = '<option value="">— Mặc định —</option>';
  ids.forEach(id=>{
    const s = sizeById[id];
    if(s){
      const o = document.createElement('option');
      o.value = s.id;
      o.textContent = s.name + ' — ' + Number(s.price).toLocaleString('vi-VN') + 'đ';
      sizeSel.appendChild(o);
    }
  });
});
</script>
@endsection
