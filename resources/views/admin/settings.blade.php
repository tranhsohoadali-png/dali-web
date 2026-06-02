<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Cài đặt | DALI Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--gn:#C6F135;--pk:#FF8FB1;--bd:#C8E89A;--bd2:#A8D870;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:var(--tx)}
.layout{display:flex;min-height:100vh}.main{flex:1;display:flex;flex-direction:column;overflow:hidden}
.tb{background:#fff;border-bottom:2px solid var(--gl);height:64px;padding:0 24px;display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
.tb-bc{font-size:10px;color:var(--tx3)}.tb-bc b{color:var(--g)}
.tb-title{font-size:18px;font-weight:900;background:linear-gradient(90deg,#2D7A08,var(--g));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-top:2px}
.sak{background:linear-gradient(90deg,#fff8fa,#f6ffe8,#fff);border-bottom:1px solid #F0EBF8;padding:6px 24px;display:flex;align-items:center;gap:5px;flex-shrink:0;font-size:14px}
@keyframes drift{0%,100%{transform:translateY(0)}50%{transform:translateY(-4px)}}
.sak-t{font-size:10px;color:#B8D8A0;letter-spacing:2px;font-weight:700;margin-left:8px}
.cnt{flex:1;overflow-y:auto;padding:22px 24px}
.alert-ok{background:var(--gll);border-left:3px solid var(--g);border-radius:9px;padding:12px 16px;margin-bottom:18px;font-size:13px;font-weight:600;color:var(--gd)}
.card{background:#fff;border-radius:16px;border:0.5px solid var(--bd);overflow:hidden;margin-bottom:20px;max-width:780px}
.rainbow{height:4px;background:linear-gradient(90deg,#3A9A12,var(--g),var(--gn),#FF8FB1,#A78BFA)}
.card-head{padding:14px 22px;border-bottom:1px solid var(--gl);background:var(--gll);display:flex;align-items:center;gap:10px}
.card-icon{width:38px;height:38px;background:linear-gradient(135deg,var(--gl),#CCEF90);border:1px solid var(--bd2);border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:17px}
.card-title{font-size:14px;font-weight:900;color:var(--char)}
.card-sub{font-size:11px;color:var(--tx3);margin-top:2px}
.fb{padding:18px 22px}
.g2{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px}
.g1{margin-bottom:14px}
.flabel{font-size:12px;font-weight:700;color:var(--tx);display:block;margin-bottom:6px}
.hint{font-size:10px;color:var(--tx3);font-weight:400;margin-left:5px}
.dinput,.dselect{width:100%;background:var(--gll);border:1.5px solid var(--bd);border-radius:9px;padding:10px 13px;font-size:13px;font-family:'Be Vietnam Pro',sans-serif;color:var(--tx);font-weight:500;outline:none;transition:all .2s}
.dinput:focus,.dselect:focus{border-color:var(--g);background:#fff}
.fnote{font-size:10px;color:var(--tx3);margin-top:4px}
.info-box{background:var(--gll);border-radius:9px;padding:11px 14px;border:1px solid var(--bd);margin-bottom:14px;font-size:13px;color:var(--gd);line-height:1.7}
.divider{height:1px;background:linear-gradient(90deg,transparent,var(--bd) 25%,var(--bd) 75%,transparent);margin:5px 0 18px}
.btn-save{display:inline-flex;align-items:center;gap:7px;padding:11px 26px;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;font-size:13px;font-weight:800;border:none;border-radius:10px;cursor:pointer;box-shadow:0 4px 14px rgba(107,191,31,.3);transition:all .2s}
.btn-save:hover{background:linear-gradient(135deg,#2E7D08,#5AAF15);transform:translateY(-1px)}
</style>
</head>
<body>
<div class="layout">
  @include('admin.partials.sidebar')
  <div class="main">
    <div class="tb">
      <div><div class="tb-bc">Admin › <b>Cài đặt</b></div><div class="tb-title">Cài Đặt Shop</div></div>
    </div>
    <div class="sak"><span style="animation:drift 5s ease-in-out infinite;display:inline-block">🌸</span><span class="sak-t">DALI · TÔ ĐIỂM CUỘC SỐNG</span></div>
    <div class="cnt">
      @if(session('success'))<div class="alert-ok">✅ {{ session('success') }}</div>@endif

      {{-- ════════ BẢNG GIÁ THEO KÍCH THƯỚC (áp dụng cho TẤT CẢ tranh) ════════ --}}
      <div class="card">
        <div class="rainbow"></div>
        <div class="card-head"><div class="card-icon">📐</div><div><div class="card-title">Bảng giá theo kích thước & phụ kiện</div><div class="card-sub">Mỗi dòng = 1 lựa chọn khi đặt tranh (khổ tranh hoặc bộ màu / phụ kiện bán lẻ). Thêm / sửa / xoá tuỳ ý.</div></div></div>
        <div class="fb">
          <div class="info-box">💡 Mỗi dòng là 1 lựa chọn khách thấy khi đặt tranh — có thể là <b>kích thước</b> (40×50cm…) hoặc <b>phụ kiện</b> (vd: "Bộ màu riêng" 90.000đ). Ở trang sửa sản phẩm, tích chọn tranh đó áp dụng những dòng nào.</div>

          {{-- Sửa + xoá các dòng hiện có --}}
          <form method="POST" action="{{ route('admin.settings.sizes') }}">
            @csrf
            <table style="width:100%;border-collapse:collapse;font-size:13px">
              <thead>
                <tr style="text-align:left;color:var(--tx3);font-size:11px;text-transform:uppercase;letter-spacing:1px">
                  <th style="padding:8px 6px">Tên (kích thước / phụ kiện)</th>
                  <th style="padding:8px 6px;width:150px">Ghi chú</th>
                  <th style="padding:8px 6px;width:140px">Giá (đ)</th>
                  <th style="padding:8px 6px;width:120px">Cân nặng (g)</th>
                  <th style="padding:8px 6px;width:60px;text-align:center">Hiện</th>
                  <th style="padding:8px 6px;width:44px"></th>
                </tr>
              </thead>
              <tbody>
                @foreach($sizes as $sz)
                <tr id="szrow-{{ $sz->id }}" style="border-top:1px solid var(--bd)">
                  <td style="padding:7px 6px"><input type="text" name="sizes[{{ $sz->id }}][name]" class="dinput" value="{{ $sz->name }}" style="margin:0;font-weight:700"></td>
                  <td style="padding:7px 6px"><input type="text" name="sizes[{{ $sz->id }}][note]" class="dinput" value="{{ $sz->note }}" placeholder="(tuỳ chọn)" style="margin:0"></td>
                  <td style="padding:7px 6px"><input type="number" name="sizes[{{ $sz->id }}][price]" class="dinput" value="{{ $sz->price }}" min="0" step="1000" style="margin:0"></td>
                  <td style="padding:7px 6px"><input type="number" name="sizes[{{ $sz->id }}][weight]" class="dinput" value="{{ $sz->weight }}" min="1" step="50" placeholder="500" style="margin:0"></td>
                  <td style="padding:7px 6px;text-align:center"><input type="checkbox" name="sizes[{{ $sz->id }}][is_active]" value="1" {{ $sz->is_active ? 'checked' : '' }} style="width:20px;height:20px;accent-color:var(--g);cursor:pointer"></td>
                  <td style="padding:7px 6px;text-align:center"><button type="button" title="Xoá dòng" onclick="delSize({{ $sz->id }})" style="background:#FFF0F0;color:#EF4444;border:1px solid #FECACA;border-radius:7px;width:30px;height:30px;cursor:pointer;font-size:14px">🗑</button></td>
                </tr>
                @endforeach
              </tbody>
            </table>
            <button type="submit" class="btn-save" style="margin-top:16px">💾 Lưu bảng giá</button>
          </form>

          {{-- Thêm dòng mới --}}
          <form method="POST" action="{{ route('admin.settings.sizes.add') }}" style="margin-top:18px;padding-top:16px;border-top:1.5px dashed var(--bd)">
            @csrf
            <div style="font-size:12px;font-weight:800;color:var(--char);margin-bottom:8px">➕ Thêm dòng mới (kích thước hoặc phụ kiện)</div>
            <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
              <input type="text" name="name" class="dinput" placeholder="Tên — vd: Bộ màu riêng" required style="margin:0;flex:1;min-width:170px">
              <input type="text" name="note" class="dinput" placeholder="Ghi chú (tuỳ chọn)" style="margin:0;width:150px">
              <input type="number" name="price" class="dinput" placeholder="Giá (đ) — vd: 90000" min="0" step="1000" required style="margin:0;width:160px">
              <input type="number" name="weight" class="dinput" placeholder="Cân nặng (g) — vd: 500" min="1" step="50" style="margin:0;width:170px">
              <button type="submit" class="btn-save" style="margin:0">➕ Thêm</button>
            </div>
          </form>
        </div>
      </div>
      <script>
      function delSize(id){
        var inp=document.querySelector('#szrow-'+id+' input[type=text]');
        var name=inp?inp.value:'dòng này';
        if(!confirm('Xoá "'+name+'" khỏi bảng giá?\n(Các tranh đang dùng dòng này sẽ không còn lựa chọn đó.)')) return;
        fetch('{{ url("admin/settings/sizes") }}/'+id,{method:'DELETE',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}})
          .then(r=>r.json()).then(d=>{ if(d.success){var row=document.getElementById('szrow-'+id); if(row) row.remove();} else alert('Lỗi khi xoá'); })
          .catch(()=>alert('Lỗi kết nối'));
      }
      </script>

      <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf

        {{-- TELEGRAM --}}
        <div class="card">
          <div class="rainbow"></div>
          <div class="card-head"><div class="card-icon">📱</div><div><div class="card-title">Thông báo Telegram</div><div class="card-sub">Nhận đơn hàng mới qua Telegram</div></div></div>
          <div class="fb">
            <div class="info-box">1) Vào Telegram → <b>@BotFather</b> → /newbot → lấy Token &nbsp;&nbsp; 2) Tìm <b>@userinfobot</b> → /start → lấy Chat ID</div>
            <div class="g2">
              <div><label class="flabel">Bot Token</label><input type="text" name="tg_token" class="dinput" value="{{ $settings['tg_token'] ?? '' }}" placeholder="7123456789:AAHxxxxxxxx"></div>
              <div><label class="flabel">Chat ID</label><input type="text" name="tg_chat_id" class="dinput" value="{{ $settings['tg_chat_id'] ?? '' }}" placeholder="123456789"></div>
            </div>
          </div>
        </div>

        {{-- NGÂN HÀNG --}}
        <div class="card">
          <div class="rainbow"></div>
          <div class="card-head"><div class="card-icon">🏦</div><div><div class="card-title">Ngân hàng QR thanh toán</div><div class="card-sub">Tạo mã QR VietQR tự động</div></div></div>
          <div class="fb">
            <div class="g2">
              <div>
                <label class="flabel">Ngân hàng</label>
                <select name="bank_id" class="dselect">
                  @foreach(['VCB'=>'Vietcombank','TCB'=>'Techcombank','MB'=>'MB Bank','VPB'=>'VPBank','ACB'=>'ACB','BIDV'=>'BIDV','VTB'=>'Vietinbank','STB'=>'Sacombank','TPB'=>'TPBank','MSB'=>'MSB'] as $code=>$name)
                  <option value="{{ $code }}" {{ ($settings['bank_id']??'VCB')==$code ? 'selected' : '' }}>{{ $code }} – {{ $name }}</option>
                  @endforeach
                </select>
              </div>
              <div><label class="flabel">Số tài khoản</label><input type="text" name="bank_acc" class="dinput" value="{{ $settings['bank_acc'] ?? '' }}" placeholder="1234567890"></div>
            </div>
            <div class="g2">
              <div><label class="flabel">Tên chủ TK <span class="hint">(IN HOA, không dấu)</span></label><input type="text" name="bank_name" class="dinput" value="{{ $settings['bank_name'] ?? '' }}" placeholder="NGUYEN VAN A" style="text-transform:uppercase"></div>
              <div><label class="flabel">Tên hiển thị</label><input type="text" name="bank_label" class="dinput" value="{{ $settings['bank_label'] ?? '' }}" placeholder="Vietcombank"></div>
            </div>
          </div>
        </div>

        {{-- THƯƠNG MẠI --}}
        <div class="card">
          <div class="rainbow"></div>
          <div class="card-head"><div class="card-icon">🛒</div><div><div class="card-title">Cấu hình thương mại</div><div class="card-sub">Phí ship, giảm giá, chính sách</div></div></div>
          <div class="fb">
            <div class="g2">
              <div><label class="flabel">Miễn phí ship từ (đ)</label><input type="number" name="free_ship_from" class="dinput" value="{{ $settings['free_ship_from'] ?? 299000 }}" min="0"><div class="fnote">Đơn ≥ số này → miễn phí ship</div></div>
              <div><label class="flabel">Phí ship mặc định (đ)</label><input type="number" name="ship_fee" class="dinput" value="{{ $settings['ship_fee'] ?? 30000 }}" min="0"></div>
            </div>
            <div class="g2">
              <div><label class="flabel">Giảm giá chuyển khoản (%)</label><input type="number" name="discount_bank" class="dinput" value="{{ $settings['discount_bank'] ?? 5 }}" min="0" max="50"><div class="fnote">Khách chọn QR được giảm % này</div></div>
              <div><label class="flabel">Đại lý đặt cọc trước (%)</label><input type="number" name="agent_deposit_percent" class="dinput" value="{{ $settings['agent_deposit_percent'] ?? 20 }}" min="0" max="100"><div class="fnote">Đại lý phải cọc % này khi lên đơn (0 = không bắt cọc)</div></div>
            </div>
          </div>
        </div>

        {{-- CÔNG CỤ TÁCH MÀU --}}
        <div class="card">
          <div class="rainbow"></div>
          <div class="card-head"><div class="card-icon">🎨</div><div><div class="card-title">Công cụ tách màu</div><div class="card-sub">Địa chỉ công cụ tạo bản đồ màu + mã DALI (nhúng vào tab "Tách màu")</div></div></div>
          <div class="fb">
            <div><label class="flabel">URL công cụ tách màu</label><input type="text" name="color_tool_url" class="dinput" value="{{ $settings['color_tool_url'] ?? 'http://127.0.0.1:18001' }}" placeholder="http://127.0.0.1:18001"><div class="fnote">Mặc định <b>http://127.0.0.1:18001</b> (chạy run.bat trên máy này). Khi lên server, đổi thành địa chỉ công cụ trên server (vd http://localhost:18001 hoặc http://ten-mien:18001).</div></div>
          </div>
        </div>

        {{-- SEO --}}
        <div class="card">
          <div class="rainbow"></div>
          <div class="card-head"><div class="card-icon">🔍</div><div><div class="card-title">SEO & Meta Tags</div><div class="card-sub">Tối ưu tìm kiếm Google</div></div></div>
          <div class="fb">
            <div class="g1"><label class="flabel">Tiêu đề trang (Meta Title)</label><input type="text" name="meta_title" class="dinput" value="{{ $settings['meta_title'] ?? 'DALI – Tranh Tô Màu Số Hóa' }}" placeholder="DALI – Tranh Tô Màu Số Hóa"></div>
            <div class="g1"><label class="flabel">Mô tả trang (Meta Description)</label><input type="text" name="meta_description" class="dinput" value="{{ $settings['meta_description'] ?? '' }}" placeholder="Bộ tranh tô màu số hóa DALI – ai cũng có thể tạo ra kiệt tác..."></div>
            <div class="g1"><label class="flabel">Từ khoá (Keywords)</label><input type="text" name="meta_keywords" class="dinput" value="{{ $settings['meta_keywords'] ?? '' }}" placeholder="tranh tô màu số hóa, paint by numbers"></div>
          </div>
        </div>

        {{-- ANALYTICS --}}
        <div class="card">
          <div class="rainbow"></div>
          <div class="card-head"><div class="card-icon">📊</div><div><div class="card-title">Google Analytics & Facebook Pixel</div><div class="card-sub">Theo dõi traffic và chuyển đổi</div></div></div>
          <div class="fb">
            <div class="g2">
              <div><label class="flabel">Google Analytics ID <span class="hint">(GA4)</span></label><input type="text" name="ga_id" class="dinput" value="{{ $settings['ga_id'] ?? '' }}" placeholder="G-XXXXXXXXXX"><div class="fnote">Lấy từ Google Analytics → Admin → Property → Data Streams</div></div>
              <div><label class="flabel">Facebook Pixel ID</label><input type="text" name="fb_pixel_id" class="dinput" value="{{ $settings['fb_pixel_id'] ?? '' }}" placeholder="123456789012345"><div class="fnote">Lấy từ Meta Business → Events Manager</div></div>
            </div>
          </div>
        </div>

        {{-- ZALO --}}
        <div class="card">
          <div class="rainbow"></div>
          <div class="card-head"><div class="card-icon">💬</div><div><div class="card-title">Zalo liên hệ</div><div class="card-sub">Nút Zalo nổi góc phải màn hình</div></div></div>
          <div class="fb">
            <div class="g2">
              <div>
                <label class="flabel">🔗 Link Zalo cá nhân</label>
                <input type="text" name="zalo_link" class="dinput" value="{{ $settings['zalo_link'] ?? '' }}" placeholder="https://zalo.me/0856911698">
                <div class="fnote">
                  Cách lấy link: Mở Zalo → Trang cá nhân → Chia sẻ → Sao chép link.<br>
                  Hoặc nhập thẳng: <b>https://zalo.me/0856911698</b> (thay bằng SĐT Zalo của bạn).
                  <br>Trạng thái: <b style="color:{{ !empty($settings['zalo_link']) ? 'var(--g)' : 'var(--tx3)' }}">
                    {{ !empty($settings['zalo_link']) ? '✅ '.$settings['zalo_link'] : 'Chưa đặt (tự dùng SĐT shop)' }}
                  </b>
                </div>
              </div>
              <div></div>
            </div>
          </div>
        </div>

        {{-- SHOP INFO --}}
        <div class="card">
          <div class="rainbow"></div>
          <div class="card-head"><div class="card-icon">🏪</div><div><div class="card-title">Thông tin Shop</div><div class="card-sub">Hiển thị trên website và email</div></div></div>
          <div class="fb">
            <div class="g2">
              <div><label class="flabel">Số điện thoại</label><input type="text" name="shop_phone" class="dinput" value="{{ $settings['shop_phone'] ?? '' }}" placeholder="0123456789"></div>
              <div><label class="flabel">Địa chỉ</label><input type="text" name="shop_address" class="dinput" value="{{ $settings['shop_address'] ?? '' }}" placeholder="Số 1 Đường ABC, Hà Nội"></div>
            </div>
          </div>
        </div>


        {{-- VẬN CHUYỂN --}}
        <div class="card">
          <div class="rainbow"></div>
          <div class="card-head"><div class="card-icon">🚚</div><div><div class="card-title">Tích hợp Giao Hàng</div><div class="card-sub">Kết nối GHTK hoặc Viettel Post để tạo vận đơn tự động</div></div></div>
          <div class="fb">
            <div style="background:var(--gll);border-radius:10px;padding:12px 16px;border:1px solid var(--bd);margin-bottom:14px;font-size:13px;color:var(--gd)">
              💡 <b>Giao Hàng Tiết Kiệm (GHTK):</b> Đăng ký tại <a href="https://khachhang.giaohangtietkiem.vn" target="_blank" style="color:var(--g)">khachhang.giaohangtietkiem.vn</a> → API → Lấy Token API<br>
              💡 <b>Viettel Post:</b> Đăng ký tại <a href="https://viettelpost.com.vn" target="_blank" style="color:var(--g)">viettelpost.com.vn</a> → API Management → Token
            </div>
            <div class="sec-title" style="font-size:9px;font-weight:800;letter-spacing:2px;color:var(--tx3);text-transform:uppercase;margin-bottom:12px">GHTK (Giao hàng tiết kiệm)</div>
            <div class="g2">
              <div><label class="flabel">GHTK Token</label><input type="text" name="ghtk_token" class="dinput" value="{{ $settings['ghtk_token'] ?? '' }}" placeholder="Token API GHTK"></div>
              <div><label class="flabel">GHTK Shop ID</label><input type="text" name="ghtk_shop_id" class="dinput" value="{{ $settings['ghtk_shop_id'] ?? '' }}" placeholder="Shop ID GHTK"></div>
            </div>
            <div class="g2">
              <div><label class="flabel">Địa chỉ lấy hàng</label><input type="text" name="ghtk_pick_address" class="dinput" value="{{ $settings['ghtk_pick_address'] ?? '' }}" placeholder="Số nhà, đường, phường/xã"></div>
              <div>
                <label class="flabel">Tỉnh/Thành phố lấy hàng</label>
                <select name="ghtk_pick_province" class="dselect">
                  @foreach(['Hà Nội','TP. Hồ Chí Minh','Đà Nẵng','Hải Phòng','Cần Thơ'] as $p)
                  <option {{ ($settings['ghtk_pick_province']??'Hà Nội')===$p?'selected':'' }}>{{ $p }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="divider"></div>
            <div class="sec-title" style="font-size:9px;font-weight:800;letter-spacing:2px;color:var(--tx3);text-transform:uppercase;margin-bottom:12px">Viettel Post <span style="color:var(--g)">(khuyên dùng)</span></div>
            <input type="hidden" name="vtp_form" value="1">
            <label style="display:flex;align-items:center;gap:9px;font-size:14px;font-weight:700;color:var(--char);margin-bottom:14px;cursor:pointer">
              <input type="checkbox" name="vtp_enabled" value="1" {{ ($settings['vtp_enabled'] ?? '0')==='1' ? 'checked' : '' }} style="width:18px;height:18px">
              Bật tích hợp Viettel Post
            </label>
            <div class="g2">
              <div><label class="flabel">VTP Token (bí mật)</label><input type="text" name="vtp_token" class="dinput" value="{{ $settings['vtp_token'] ?? '' }}" placeholder="Token từ viettelpost.vn → Cấu hình tài khoản"><div class="fnote">Lấy tại viettelpost.vn → Cấu hình tài khoản → Thêm mới token</div></div>
              <div><label class="flabel">Môi trường</label>
                <select name="vtp_env" class="dselect">
                  <option value="prod" {{ ($settings['vtp_env']??'prod')==='prod'?'selected':'' }}>Production (thật)</option>
                  <option value="dev" {{ ($settings['vtp_env']??'prod')==='dev'?'selected':'' }}>Development (thử nghiệm)</option>
                </select>
              </div>
            </div>
            <div class="g2">
              <div><label class="flabel">Tên người gửi</label><input type="text" name="vtp_sender_name" class="dinput" value="{{ $settings['vtp_sender_name'] ?? 'DALI' }}"></div>
              <div><label class="flabel">SĐT người gửi</label><input type="text" name="vtp_sender_phone" class="dinput" value="{{ $settings['vtp_sender_phone'] ?? '' }}" placeholder="0856911698"></div>
            </div>
            <div><label class="flabel">Địa chỉ lấy hàng (người gửi)</label><input type="text" name="vtp_sender_address" class="dinput" value="{{ $settings['vtp_sender_address'] ?? '' }}" placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/TP"></div>
            <div class="g2">
              <div><label class="flabel">Mã dịch vụ</label><input type="text" name="vtp_service" class="dinput" value="{{ $settings['vtp_service'] ?? 'VCN' }}" placeholder="VCN"><div class="fnote">VD: VCN (Chuyển phát nhanh), VTK (Tiết kiệm)</div></div>
              <div><label class="flabel">Webhook Secret</label><input type="text" name="vtp_webhook_token" class="dinput" value="{{ $settings['vtp_webhook_token'] ?? '' }}" placeholder="Chuỗi bí mật tự đặt"><div class="fnote">Đặt 1 chuỗi → khai báo cùng webhook trên VTP để xác thực</div></div>
            </div>
            <div style="background:var(--gll);border-radius:10px;padding:12px 16px;border:1px solid var(--bd);margin-top:6px;font-size:13px;color:var(--gd)">
              🔗 <b>URL Webhook</b> (dán vào Bảng điều khiển Partner VTP): <br>
              <code style="user-select:all;font-size:12px;color:var(--char)">{{ url('/webhook/viettelpost') }}</code>
            </div>
            <div class="divider"></div>
            <div class="sec-title" style="font-size:9px;font-weight:800;letter-spacing:2px;color:var(--tx3);text-transform:uppercase;margin-bottom:12px">Cấu hình mặc định</div>
            <div class="g2">
              <div><label class="flabel">Cân nặng mặc định (gram)</label><input type="number" name="default_weight" class="dinput" value="{{ $settings['default_weight'] ?? 500 }}" min="1" placeholder="500"><div class="fnote">Dùng dự phòng khi khổ tranh chưa đặt cân nặng riêng. Cân nặng theo từng kích thước cài ở mục <b>Bảng giá theo kích thước</b> bên trên.</div></div>
              <div></div>
            </div>
          </div>
        </div>

        {{-- BẢO MẬT --}}
        <div class="card">
          <div class="rainbow"></div>
          <div class="card-head"><div class="card-icon">🔒</div><div><div class="card-title">Bảo mật Admin</div><div class="card-sub">Đổi mật khẩu đăng nhập</div></div></div>
          <div class="fb">
            <div class="info-box">⚠️ Chỉ điền 2 ô dưới khi bạn THỰC SỰ muốn đổi mật khẩu. Để trống thì mật khẩu giữ nguyên.</div>
            <div class="g2">
              <div><label class="flabel">Mật khẩu mới</label><input type="password" name="new_password" class="dinput" placeholder="Để trống nếu không muốn đổi" autocomplete="new-password" autocapitalize="off" data-lpignore="true" data-form-type="other"></div>
              <div><label class="flabel">Xác nhận mật khẩu</label><input type="password" name="new_password_confirm" class="dinput" placeholder="Nhập lại mật khẩu mới" autocomplete="new-password" autocapitalize="off" data-lpignore="true" data-form-type="other"></div>
            </div>
          </div>
        </div>

        <button type="submit" class="btn-save">✅ Lưu tất cả cài đặt</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>