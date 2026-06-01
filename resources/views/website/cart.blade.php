<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Giỏ hàng | DALI</title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet">
<style>[class^="ri-"],[class*=" ri-"]{vertical-align:-.125em;font-style:normal;line-height:1}</style>
<style>
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--gn:#C6F135;--pk:#FF8FB1;--bd:#C8E89A;--bd2:#A8D870;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0}html{scroll-behavior:smooth}
body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:var(--tx);line-height:1.6}
nav{background:linear-gradient(175deg,#1C5200,#2D7A08,#3A9A12);height:68px;padding:0 5%;display:flex;align-items:center;justify-content:space-between}
.nav-logo-t{font-size:26px;font-weight:900;letter-spacing:3px;color:#fff;text-decoration:none}
.nav-logo-t span{color:#C6F135}
.nav-links{display:flex;gap:26px;list-style:none}
.nav-links a{text-decoration:none;color:rgba(255,255,255,.75);font-size:14px;font-weight:500;transition:color .2s}
.nav-links a:hover{color:#fff}
.btn-nav{background:#C6F135;color:#1C3A0A;border:none;border-radius:50px;padding:9px 20px;font-size:13px;font-weight:800;cursor:pointer;text-decoration:none}
.sak-strip{background:linear-gradient(90deg,#fff8fa,#f6ffe8,#fff);border-bottom:1px solid #F0EBF8;padding:7px 5%;display:flex;align-items:center;gap:6px;font-size:14px}
.sak-t{font-size:10px;color:#B8D8A0;letter-spacing:2.5px;font-weight:700;margin-left:8px}
.page-hero{background:linear-gradient(175deg,#1C5200,#2D7A08);padding:40px 5% 32px;color:#fff;text-align:center}
.page-hero h1{font-size:clamp(22px,3vw,32px);font-weight:900;margin-bottom:6px}
.page-hero p{font-size:14px;opacity:.75}
.wrap{padding:32px 5%;max-width:1100px;margin:0 auto;display:grid;grid-template-columns:1fr 380px;gap:28px;align-items:start}
.cart-card{background:#fff;border-radius:18px;border:1.5px solid var(--bd);overflow:hidden}
.cart-top{height:4px;background:linear-gradient(90deg,#3A9A12,var(--g),#C6F135,#FF8FB1,#A78BFA)}
.cart-head{padding:16px 22px;border-bottom:1px solid var(--gl);background:var(--gll);display:flex;align-items:center;justify-content:space-between}
.cart-title{font-size:15px;font-weight:900;color:var(--char)}
.btn-clear{padding:6px 14px;background:#FFF0F0;color:#EF4444;border:1px solid #FECACA;border-radius:7px;font-size:12px;font-weight:700;cursor:pointer}
.cart-item{display:flex;align-items:center;gap:16px;padding:16px 22px;border-bottom:1px solid var(--gl);transition:background .2s}
.cart-item:hover{background:var(--gll)}
.cart-item:last-child{border-bottom:none}
.ci-img{width:72px;height:72px;border-radius:11px;object-fit:cover;border:1.5px solid var(--bd);flex-shrink:0;background:var(--gl);display:flex;align-items:center;justify-content:center;font-size:28px}
.ci-name{font-size:14px;font-weight:700;color:var(--char);margin-bottom:3px;line-height:1.4}
.ci-size{font-size:12px;color:var(--tx3);margin-bottom:6px}
.ci-price{font-size:15px;font-weight:900;color:var(--g)}
.qty-ctrl{display:flex;align-items:center;border:1.5px solid var(--bd);border-radius:8px;overflow:hidden;width:fit-content}
.qty-b{background:var(--gl);border:none;width:32px;height:36px;font-size:16px;cursor:pointer;font-weight:700;color:var(--gd)}
.qty-i{border:none;border-left:1.5px solid var(--bd);border-right:1.5px solid var(--bd);width:46px;height:36px;text-align:center;font-size:14px;font-weight:800;color:var(--char);outline:none;font-family:'Be Vietnam Pro',sans-serif;background:#fff}
.ci-del{width:32px;height:32px;border-radius:50%;background:#FFF0F0;border:1px solid #FECACA;color:#EF4444;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:14px;margin-left:auto;transition:all .2s}
.ci-del:hover{background:#EF4444;color:#fff}
.empty-cart{text-align:center;padding:52px 20px}
.empty-icon{font-size:56px;margin-bottom:14px}
.empty-title{font-size:18px;font-weight:800;color:var(--char);margin-bottom:8px}
.empty-sub{font-size:14px;color:var(--tx3);margin-bottom:20px}
/* SUMMARY CARD */
.summary-card{background:#fff;border-radius:18px;border:1.5px solid var(--bd);overflow:hidden;position:sticky;top:80px}
.summary-head{padding:16px 22px;border-bottom:1px solid var(--gl);background:var(--gll)}
.summary-title{font-size:15px;font-weight:900;color:var(--char)}
.summary-body{padding:18px 22px}
.coupon-row{display:flex;gap:8px;margin-bottom:16px}
.coupon-input{flex:1;background:var(--gll);border:1.5px solid var(--bd);border-radius:9px;padding:10px 13px;font-size:13px;font-family:'Be Vietnam Pro',sans-serif;color:var(--tx);outline:none;text-transform:uppercase;transition:all .2s}
.coupon-input:focus{border-color:var(--g);background:#fff}
.coupon-btn{padding:10px 16px;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:9px;font-size:13px;font-weight:700;cursor:pointer;white-space:nowrap;transition:all .2s}
.coupon-btn:hover{background:linear-gradient(135deg,#2E7D08,#5AAF15)}
.coupon-ok{font-size:12px;color:var(--g);font-weight:700;margin-bottom:12px;display:none}
.pay-opts{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px}
.pay-opt{border:2px solid var(--bd);border-radius:10px;padding:11px;cursor:pointer;display:flex;align-items:center;gap:8px;transition:all .2s;position:relative}
.pay-opt.active{border-color:var(--g);background:var(--gl)}
.pay-opt-txt{font-size:12px;font-weight:700;color:var(--char);line-height:1.3}
.pay-sub{font-size:10px;color:var(--tx3)}
.disc-tag{position:absolute;top:-7px;right:8px;background:var(--g);color:#fff;font-size:9px;font-weight:800;padding:2px 7px;border-radius:20px}
.s-row{display:flex;justify-content:space-between;padding:5px 0;font-size:13px}
.s-row.disc{color:var(--gd);font-weight:700}
.s-row.total{border-top:1.5px dashed var(--bd);padding-top:12px;margin-top:4px;font-weight:800;font-size:16px}
.s-row.total .v{color:var(--g);font-size:18px}
.btn-checkout{width:100%;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:12px;padding:14px;font-size:15px;font-weight:800;cursor:pointer;transition:all .2s;margin-top:6px;box-shadow:0 4px 16px rgba(107,191,31,.3)}
.btn-checkout:hover{background:linear-gradient(135deg,#2E7D08,#5AAF15);transform:translateY(-1px)}
.btn-checkout:disabled{background:#C8E89A;cursor:not-allowed;transform:none;box-shadow:none}
/* CHECKOUT MODAL */
.modal-overlay{position:fixed;inset:0;background:rgba(28,82,0,.55);z-index:999;display:none;align-items:center;justify-content:center;padding:16px;backdrop-filter:blur(5px)}
.modal-overlay.open{display:flex}
.modal{background:#fff;border-radius:22px;width:100%;max-width:540px;max-height:90vh;overflow-y:auto;border:1.5px solid var(--bd)}
.modal::before{content:'';display:block;height:4px;background:linear-gradient(90deg,#3A9A12,var(--g),#C6F135,#FF8FB1,#A78BFA);border-radius:22px 22px 0 0}
.modal-header{padding:20px 24px 15px;border-bottom:1px solid var(--gl);position:sticky;top:0;background:#fff;z-index:2;display:flex;align-items:flex-start;justify-content:space-between}
.modal-close{background:var(--gl);border:none;width:30px;height:30px;border-radius:50%;cursor:pointer;color:var(--gd);font-size:15px;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px}
.modal-body{padding:18px 24px}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px}
.form-row.single{grid-template-columns:1fr}
.form-group{display:flex;flex-direction:column;gap:5px}
.form-group label{font-size:12px;font-weight:700;color:var(--tx)}
.req{color:var(--pk)}
.form-group input,.form-group select,.form-group textarea{border:1.5px solid var(--bd);border-radius:9px;padding:10px 13px;font-size:13px;font-family:'Be Vietnam Pro',sans-serif;color:var(--tx);background:var(--gll);outline:none;transition:all .2s}
.form-group input:focus,.form-group select:focus{border-color:var(--g);background:#fff}
.form-group textarea{resize:vertical;min-height:60px}
.btn-submit{width:100%;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:12px;padding:14px;font-size:15px;font-weight:800;cursor:pointer;transition:all .2s;margin-top:5px}
.btn-submit:disabled{background:#C8E89A;cursor:not-allowed}
.success-box{text-align:center;padding:28px 16px}
.success-icon{font-size:56px;margin-bottom:12px;display:block}
.success-box h3{font-size:21px;font-weight:900;color:var(--g);margin-bottom:8px}
.order-code{font-size:18px;font-weight:900;background:var(--gl);color:var(--gd);padding:8px 20px;border-radius:9px;display:inline-block;margin:11px 0;border:1.5px solid var(--bd2)}
.toast{position:fixed;bottom:22px;left:50%;transform:translateX(-50%) translateY(100px);background:#1C3A0A;color:#fff;padding:11px 22px;border-radius:50px;font-size:13px;font-weight:600;z-index:9999;transition:transform .4s;white-space:nowrap;max-width:90vw;text-align:center}
.toast.show{transform:translateX(-50%) translateY(0)}
.btn-primary{background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:50px;padding:12px 28px;font-size:14px;font-weight:700;cursor:pointer;transition:all .2s;text-decoration:none;display:inline-block}
footer{background:linear-gradient(175deg,#0F2E00,#1C5200);color:rgba(255,255,255,.7);padding:30px 5%;margin-top:32px}
.footer-bottom{border-top:1px solid rgba(255,255,255,.08);padding-top:18px;display:flex;justify-content:space-between;font-size:12px;color:rgba(255,255,255,.3)}
@media(max-width:900px){.wrap{grid-template-columns:1fr}.summary-card{position:static}}
</style>
</head>
<body>
<nav>
  <a href="{{ route('home') }}" class="nav-logo-t">DAL<span>I</span></a>
  <ul class="nav-links">
    <li><a href="{{ route('home') }}">Trang chủ</a></li>
    <li><a href="{{ route('products') }}">Sản phẩm</a></li>
    <li><a href="{{ route('blog') }}">Blog</a></li>
    <li><a href="{{ route('track-order') }}">Tra cứu đơn</a></li>
  </ul>
  <a href="{{ route('products') }}" class="btn-nav">Tiếp tục mua sắm</a>
</nav>
<div class="sak-strip"><span><i class="ri-flower-line"></i></span><span><i class="ri-flower-line"></i></span><span><i class="ri-leaf-line"></i></span><span class="sak-t">DALI · TÔ ĐIỂM CUỘC SỐNG</span></div>

<div class="page-hero">
  <h1><i class="ri-shopping-cart-2-line"></i> Giỏ hàng của bạn</h1>
  <p>{{ count($cart) }} sản phẩm · {{ array_sum(array_column($cart,'quantity')) }} món</p>
</div>

<div class="wrap">
  {{-- CART ITEMS --}}
  <div class="cart-card">
    <div class="cart-top"></div>
    <div class="cart-head">
      <div class="cart-title">Sản phẩm ({{ count($cart) }})</div>
      @if(count($cart) > 0)
      <button class="btn-clear" onclick="clearCart()"><i class="ri-delete-bin-line"></i> Xoá tất cả</button>
      @endif
    </div>

    @if(count($cart) > 0)
    <div id="cartItems">
      @foreach($cart as $key => $item)
      <div class="cart-item" id="item-{{ $key }}">
        <div class="ci-img">
          @if($item['main_image'])
          <img src="{{ asset('storage/'.$item['main_image']) }}" alt="{{ $item['name'] }}" style="width:100%;height:100%;object-fit:cover;border-radius:9px">
          @else
          <i class="ri-palette-line"></i>
          @endif
        </div>
        <div style="flex:1">
          <div class="ci-name">{{ $item['name'] }}</div>
          <div class="ci-size">{{ $item['size'] }}</div>
          <div class="ci-price">{{ number_format($item['price'],0,',','.')}}đ</div>
        </div>
        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px">
          <div class="qty-ctrl">
            <button class="qty-b" onclick="changeQty('{{ $key }}',-1)">−</button>
            <input class="qty-i" type="number" value="{{ $item['quantity'] }}" min="1" max="99" onchange="setQty('{{ $key }}',this.value)">
            <button class="qty-b" onclick="changeQty('{{ $key }}',1)">+</button>
          </div>
          <div style="font-size:14px;font-weight:900;color:var(--g)" id="sub-{{ $key }}">{{ number_format($item['price']*$item['quantity'],0,',','.')}}đ</div>
          <button class="ci-del" onclick="removeItem('{{ $key }}')">✕</button>
        </div>
      </div>
      @endforeach
    </div>
    @else
    <div class="empty-cart">
      <div class="empty-icon"><i class="ri-shopping-cart-2-line"></i></div>
      <div class="empty-title">Giỏ hàng trống</div>
      <div class="empty-sub">Hãy thêm sản phẩm vào giỏ hàng để tiến hành đặt mua</div>
      <a href="{{ route('products') }}" class="btn-primary"><i class="ri-palette-line"></i> Xem tất cả sản phẩm</a>
    </div>
    @endif
  </div>

  {{-- ORDER SUMMARY --}}
  @if(count($cart) > 0)
  <div class="summary-card">
    <div class="cart-top"></div>
    <div class="summary-head"><div class="summary-title">Tổng đơn hàng</div></div>
    <div class="summary-body">
      {{-- Coupon --}}
      <div class="coupon-row">
        <input type="text" class="coupon-input" id="couponInput" placeholder="Nhập mã giảm giá..." oninput="this.value=this.value.toUpperCase()">
        <button class="coupon-btn" onclick="applyCoupon()">Áp dụng</button>
      </div>
      <div class="coupon-ok" id="couponMsg"></div>

      {{-- Payment --}}
      <div class="pay-opts">
        <div class="pay-opt" id="pay-cod" onclick="selectPay('COD')">
          <span style="font-size:19px"><i class="ri-money-dollar-circle-line"></i></span>
          <div><div class="pay-opt-txt">COD</div><div class="pay-sub">Trả khi nhận</div></div>
        </div>
        <div class="pay-opt active" id="pay-bank" onclick="selectPay('BANK')">
          <span style="font-size:19px"><i class="ri-smartphone-line"></i></span>
          <div><div class="pay-opt-txt">QR Chuyển khoản</div><div class="pay-sub">Giảm {{ (int)($settings['discount_bank'] ?? 5) }}%</div></div>
          <div class="disc-tag">-{{ (int)($settings['discount_bank'] ?? 5) }}%</div>
        </div>
      </div>

      {{-- Summary rows --}}
      @php
        $subtotal = array_sum(array_map(fn($i)=>$i['price']*$i['quantity'], $cart));
        $freeShip = (int)($settings['free_ship_from'] ?? 299000);
        $shipFee  = (int)($settings['ship_fee'] ?? 30000);
      @endphp
      <div id="summaryRows">
        <div class="s-row"><span>Tạm tính ({{ array_sum(array_column($cart,'quantity')) }} món)</span><span id="sumSubtotal">{{ number_format($subtotal,0,',','.')}}đ</span></div>
        <div class="s-row disc" id="discRow" style="display:none"><span>🎉 Giảm {{ (int)($settings['discount_bank'] ?? 5) }}% chuyển khoản</span><span id="sumDisc">–</span></div>
        <div class="s-row disc" id="couponRow" style="display:none"><span><i class="ri-price-tag-3-line"></i> Mã giảm giá</span><span id="sumCoupon">–</span></div>
        <div class="s-row"><span>Phí giao hàng</span><span id="sumShip" style="color:var(--g)">{{ $subtotal >= $freeShip ? 'Miễn phí' : number_format($shipFee,0,',','.').'đ' }}</span></div>
        <div class="s-row total"><span>Tổng thanh toán</span><span class="v" id="sumTotal">{{ number_format($subtotal,0,',','.')}}đ</span></div>
      </div>
      <button class="btn-checkout" id="checkoutBtn" onclick="openCheckout()">
        <i class="ri-shopping-cart-2-line"></i> Đặt hàng ngay
      </button>
      <p style="text-align:center;font-size:11px;color:var(--tx3);margin-top:8px"><i class="ri-lock-line"></i> Thanh toán an toàn · Miễn phí ship từ {{ number_format($freeShip,0,',','.')}}đ</p>
    </div>
  </div>
  @endif
</div>

<footer><div class="footer-bottom"><span>© 2024 DALI Tranh Tô Màu Số Hóa</span><span>🇻🇳 Việt Nam</span></div></footer>

{{-- CHECKOUT MODAL --}}
<div class="modal-overlay" id="checkoutModal" onclick="if(event.target===this)closeModal()">
  <div class="modal">
    <div class="modal-header">
      <div>
        <div style="font-size:18px;font-weight:900;color:var(--char)" id="modalTitle"><i class="ri-box-3-line"></i> Thông tin giao hàng</div>
        <div style="font-size:12px;color:var(--tx3)">Điền thông tin để chúng tôi giao hàng đến bạn</div>
      </div>
      <button class="modal-close" onclick="closeModal()">✕</button>
    </div>
    <div id="checkoutForm" class="modal-body">
      <div class="form-row">
        <div class="form-group"><label>Họ và tên <span class="req">*</span></label><input type="text" id="cName" placeholder="Nguyễn Văn A"></div>
        <div class="form-group"><label>Số điện thoại <span class="req">*</span></label><input type="tel" id="cPhone" placeholder="0912 345 678"></div>
      </div>
      <div class="form-row single">
        <div class="form-group"><label>Tỉnh / Thành phố <span class="req">*</span></label>
          <select id="cCity"><option value="">— Chọn tỉnh/thành —</option><option>Hà Nội</option><option>TP. Hồ Chí Minh</option><option>Đà Nẵng</option><option>Hải Phòng</option><option>Cần Thơ</option><option>An Giang</option><option>Bắc Giang</option><option>Bắc Ninh</option><option>Bình Dương</option><option>Đồng Nai</option><option>Khánh Hòa</option><option>Kiên Giang</option><option>Long An</option><option>Nghệ An</option><option>Quảng Ninh</option><option>Thanh Hóa</option><option>Thừa Thiên Huế</option><option>Vĩnh Phúc</option></select>
        </div>
      </div>
      <div class="form-row single"><div class="form-group"><label>Địa chỉ cụ thể <span class="req">*</span></label><input type="text" id="cAddr" placeholder="Số nhà, tên đường, phường/xã, quận/huyện"></div></div>
      <div class="form-row single"><div class="form-group"><label>Email <span style="font-size:10px;color:var(--tx3);font-weight:400">(nhận xác nhận đơn)</span></label><input type="email" id="cEmail" placeholder="email@gmail.com"></div></div>
      <div class="form-row single"><div class="form-group"><label>Ghi chú</label><textarea id="cNote" placeholder="Gọi trước khi giao..."></textarea></div></div>
      <div style="background:var(--gll);border-radius:10px;padding:12px 14px;border:1px solid var(--bd);margin-bottom:14px;font-size:13px;color:var(--tx2)">
        <b>Hình thức thanh toán:</b> <span id="payModeDisplay">QR Chuyển khoản (Giảm {{ (int)($settings['discount_bank'] ?? 5) }}%)</span><br>
        <b>Tổng thanh toán:</b> <span id="modalTotal" style="color:var(--g);font-weight:900;font-size:15px">–</span>
      </div>
      <button class="btn-submit" id="submitBtn" onclick="doCheckout()"><span id="submitText"><i class="ri-checkbox-circle-line"></i> Xác nhận đặt hàng</span></button>
      <p style="text-align:center;font-size:11px;color:var(--tx3);margin-top:8px"><i class="ri-lock-line"></i> Thông tin được bảo mật</p>
    </div>
    <div id="checkoutSuccess" class="modal-body" style="display:none">
      <div class="success-box">
        <span class="success-icon">🎉</span>
        <h3>Đặt hàng thành công!</h3>
        <p>Cảm ơn bạn đã tin tưởng DALI.<br>Chúng tôi sẽ liên hệ xác nhận trong <strong>30 phút</strong>.</p>
        <div class="order-code" id="successCode">DALI-000000</div>
        <p style="font-size:12px;color:var(--tx3)">Dùng mã này để <a href="{{ route('track-order') }}" style="color:var(--g);font-weight:700">tra cứu đơn hàng</a></p>
        <a href="{{ route('products') }}" class="btn-primary" style="margin-top:14px">Tiếp tục mua sắm →</a>
      </div>
    </div>
  </div>
</div>
<div class="toast" id="toast"></div>

<script>
var payMode='BANK',gCoupon=null;
var CART_DATA = @json($cart);
var FREE_SHIP = {{ (int)($settings['free_ship_from'] ?? 299000) }};
var SHIP_FEE  = {{ (int)($settings['ship_fee'] ?? 30000) }};
var liveShip  = null; // phí ViettelPost tính theo địa chỉ (null = chưa có)
var DISCOUNT_PCT = {{ (int)($settings['discount_bank'] ?? 5) }};

function fmtVnd(n){return Math.round(n).toLocaleString('vi-VN')+'đ';}
function showToast(m){var t=document.getElementById('toast');t.textContent=m;t.classList.add('show');setTimeout(()=>t.classList.remove('show'),3200);}

// Cart operations
async function changeQty(key,delta){
  var input=document.querySelector('#item-'+key+' .qty-i');
  var newQty=Math.max(1,parseInt(input.value)+delta);
  input.value=newQty; await setQty(key,newQty);
}
async function setQty(key,qty){
  qty=Math.max(1,parseInt(qty)||1);
  var res=await fetch('{{ route("cart.update") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({key:key,quantity:qty})});
  var d=await res.json();
  if(d.success){updateCartDisplay(key,qty);updateSummary();}
}
async function removeItem(key){
  if(!confirm('Xoá sản phẩm khỏi giỏ hàng?'))return;
  var res=await fetch('{{ route("cart.remove") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({key:key})});
  var d=await res.json();
  if(d.success){document.getElementById('item-'+key).remove();delete CART_DATA[key];updateSummary();showToast('🗑️ Đã xoá khỏi giỏ hàng');if(Object.keys(CART_DATA).length===0)location.reload();}
}
async function clearCart(){
  if(!confirm('Xoá tất cả sản phẩm khỏi giỏ hàng?'))return;
  for(var key in CART_DATA){await fetch('{{ route("cart.remove") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({key:key})});}
  location.reload();
}
function updateCartDisplay(key,qty){
  if(CART_DATA[key]){CART_DATA[key].quantity=qty;var price=CART_DATA[key].price;document.getElementById('sub-'+key).textContent=fmtVnd(price*qty);}
}

// Summary
function selectPay(mode){
  payMode=mode;
  document.getElementById('pay-cod').classList.toggle('active',mode==='COD');
  document.getElementById('pay-bank').classList.toggle('active',mode==='BANK');
  document.getElementById('payModeDisplay').textContent=mode==='BANK'?'QR Chuyển khoản (Giảm {{ (int)($settings['discount_bank'] ?? 5) }}%)':'COD – Thanh toán khi nhận hàng';
  updateSummary();refreshShip();
}
function updateSummary(){
  var sub=0;
  Object.values(CART_DATA).forEach(i=>{if(i.quantity>0)sub+=i.price*i.quantity;});
  var disc=payMode==='BANK'?Math.round(sub*DISCOUNT_PCT/100):0;
  var couponDisc=(gCoupon&&gCoupon.discount)?Math.min(gCoupon.discount,sub):0;
  var after=sub-disc-couponDisc;
  var ship=after>=FREE_SHIP?0:(liveShip!=null?liveShip:SHIP_FEE);var total=after+ship;
  document.getElementById('sumSubtotal').textContent=fmtVnd(sub);
  document.getElementById('sumDisc').textContent='-'+fmtVnd(disc);
  document.getElementById('discRow').style.display=disc>0?'flex':'none';
  document.getElementById('sumCoupon').textContent='-'+fmtVnd(couponDisc);
  document.getElementById('couponRow').style.display=couponDisc>0?'flex':'none';
  document.getElementById('sumShip').textContent=ship===0?'Miễn phí':fmtVnd(ship);
  document.getElementById('sumTotal').textContent=fmtVnd(total);
  document.getElementById('modalTotal').textContent=fmtVnd(total);
}

// Tính cước thật theo địa chỉ (ViettelPost). Gọi khi nhập tỉnh/địa chỉ.
async function refreshShip(){
  var city=(document.getElementById('cCity')||{}).value||'';
  var addr=((document.getElementById('cAddr')||{}).value||'').trim();
  var sub=Object.values(CART_DATA).reduce((s,i)=>s+(i.quantity>0?i.price*i.quantity:0),0);
  var disc=payMode==='BANK'?Math.round(sub*DISCOUNT_PCT/100):0;
  var couponDisc=(gCoupon&&gCoupon.discount)?Math.min(gCoupon.discount,sub):0;
  var after=sub-disc-couponDisc;
  var qty=Object.values(CART_DATA).reduce((s,i)=>s+(i.quantity>0?i.quantity:0),0);
  if(after>=FREE_SHIP||!city||!addr){liveShip=null;updateSummary();return;}
  var el=document.getElementById('sumShip'); if(el)el.textContent='Đang tính...';
  try{
    var res=await fetch('{{ route("calc-ship") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({amount:after,qty:qty,city:city,address:addr,payment:payMode})});
    var d=await res.json();
    liveShip=(d&&typeof d.fee==='number')?d.fee:null;
  }catch(e){liveShip=null;}
  updateSummary();
}
document.addEventListener('DOMContentLoaded',function(){
  var c=document.getElementById('cCity'), a=document.getElementById('cAddr');
  if(c)c.addEventListener('change',refreshShip);
  if(a)a.addEventListener('blur',refreshShip);
});

// Coupon
async function applyCoupon(){
  var code=document.getElementById('couponInput').value.trim().toUpperCase();
  if(!code){showToast('⚠️ Vui lòng nhập mã giảm giá');return;}
  var sub=Object.values(CART_DATA).reduce((s,i)=>s+i.price*i.quantity,0);
  try{
    var res=await fetch('{{ route("check-coupon") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({code,amount:sub})});
    var d=await res.json();
    var msg=document.getElementById('couponMsg');
    if(d.valid){gCoupon=d;msg.textContent='✅ '+d.message;msg.style.color='var(--g)';msg.style.display='block';document.getElementById('couponInput').style.borderColor='var(--g)';showToast('🏷️ '+d.message);}
    else{gCoupon=null;msg.textContent='❌ '+d.message;msg.style.color='#EF4444';msg.style.display='block';}
    updateSummary();refreshShip();
  }catch(e){showToast('❌ Lỗi kết nối');}
}

// Checkout
function openCheckout(){document.getElementById('checkoutModal').classList.add('open');document.body.style.overflow='hidden';updateSummary();}
function closeModal(){document.getElementById('checkoutModal').classList.remove('open');document.body.style.overflow='';}

async function doCheckout(){
  var name=document.getElementById('cName').value.trim();
  var phone=document.getElementById('cPhone').value.trim();
  var city=document.getElementById('cCity').value;
  var addr=document.getElementById('cAddr').value.trim();
  if(!name){showToast('⚠️ Vui lòng nhập họ tên');return;}
  if(!phone){showToast('⚠️ Vui lòng nhập số điện thoại');return;}
  if(!city){showToast('⚠️ Vui lòng chọn tỉnh/thành');return;}
  if(!addr){showToast('⚠️ Vui lòng nhập địa chỉ');return;}
  var btn=document.getElementById('submitBtn');btn.disabled=true;
  document.getElementById('submitText').textContent='⏳ Đang xử lý...';
  try{
    var res=await fetch('{{ route("cart.checkout") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({customer_name:name,customer_phone:phone,customer_city:city,customer_addr:addr,customer_email:document.getElementById('cEmail').value.trim(),note:document.getElementById('cNote').value.trim(),payment:payMode,coupon_code:gCoupon?gCoupon.code:''})});
    var d=await res.json();
    if(d.success){
      document.getElementById('successCode').textContent=d.code;
      document.getElementById('checkoutForm').style.display='none';
      document.getElementById('checkoutSuccess').style.display='block';
      document.getElementById('modalTitle').textContent='✅ Đặt hàng thành công';
      CART_DATA={};
    }else showToast('❌ '+(d.message||'Có lỗi xảy ra'));
  }catch(e){showToast('❌ Lỗi kết nối');}
  btn.disabled=false;document.getElementById('submitText').textContent='✅ Xác nhận đặt hàng';
}

selectPay('BANK');
</script>
@include('partials.float-widget')
@include('partials.bottom-nav')
</body>
</html>