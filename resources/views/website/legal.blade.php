@php
  /* ============================================================
     TRANG PHÁP LÝ / TIN CẬY dùng chung cho 5 trang:
     Giới thiệu · Liên hệ · Chính sách bảo mật · Điều khoản ·
     Đổi trả & vận chuyển. Chọn nội dung theo $slug.
     Mấy trang này BẮT BUỘC cho AdSense (site phải có thông tin
     doanh nghiệp thật + chính sách bảo mật khai báo cookie QC).
     ============================================================ */
  $COMPANY = 'Công ty TNHH Sản xuất và Thương mại Dali';
  $MST     = '0601177989';
  $ADDR    = 'Thôn Bắc Cường, Xã Minh Thái, Tỉnh Ninh Bình, Việt Nam';
  $EMAIL   = 'tranhsohoadali@gmail.com';
  $FB      = 'https://www.facebook.com/tranhtomau.dali';
  $MSG     = 'https://m.me/tranhtomau.dali';

  // Chỉ hiện SĐT khi admin đã điền số THẬT (tránh hiện số mẫu 0123456789).
  $phoneRaw = trim((string)($settings['shop_phone'] ?? ''));
  $PHONE    = ($phoneRaw !== '' && $phoneRaw !== '0123456789' && $phoneRaw !== '0123 456 789') ? $phoneRaw : null;
  $phoneRow = $PHONE
      ? '<tr><th>Điện thoại</th><td><a href="tel:'.e($PHONE).'">'.e($PHONE).'</a></td></tr>'
      : '';

  $infoTable = <<<HTML
<table class="info">
  <tr><th>Tên công ty</th><td>{$COMPANY}</td></tr>
  <tr><th>Mã số thuế</th><td>{$MST}</td></tr>
  <tr><th>Địa chỉ</th><td>{$ADDR}</td></tr>
  <tr><th>Email</th><td><a href="mailto:{$EMAIL}">{$EMAIL}</a></td></tr>
  {$phoneRow}
  <tr><th>Facebook</th><td><a href="{$FB}" target="_blank" rel="noopener">facebook.com/tranhtomau.dali</a></td></tr>
</table>
HTML;

  $pages = [
    // ─────────────────────────── GIỚI THIỆU ───────────────────────────
    'about' => [
      'title' => 'Giới thiệu về DALI — Tranh tô màu số hóa theo ảnh',
      'desc'  => 'DALI là thương hiệu tranh tô màu số hóa (paint by numbers) theo ảnh của Công ty TNHH Sản xuất và Thương mại Dali. Tìm hiểu về chúng tôi, sản phẩm và cam kết chất lượng.',
      'kick'  => 'Về chúng tôi',
      'h1'    => 'Giới thiệu về DALI',
      'lead'  => 'Chúng tôi biến những tấm ảnh kỷ niệm thành bức tranh bạn tự tay tô — để mỗi bức tranh treo tường đều mang câu chuyện của chính gia đình bạn.',
      'body'  => <<<HTML
<h2>DALI là ai?</h2>
<p><strong>DALI</strong> là thương hiệu <strong>tranh tô màu số hóa</strong> (còn gọi là tranh sơn theo số, paint by numbers) trực thuộc <strong>{$COMPANY}</strong>. Chúng tôi bắt đầu từ một ý tưởng rất đơn giản: <em>ai cũng có thể vẽ được một bức tranh đẹp</em> — chỉ cần được chỉ đúng chỗ nào tô màu nào.</p>
<p>Thay vì phải biết vẽ, bạn nhận được một tấm canvas đã in sẵn các vùng đánh số, kèm bộ màu pha sẵn tương ứng và cọ. Việc của bạn chỉ là tô màu theo số. Sau vài buổi tối thư giãn, bạn có một bức tranh hoàn chỉnh do chính tay mình làm ra để treo lên tường.</p>

<h2>Chúng tôi làm gì?</h2>
<ul>
  <li><strong>Tranh có sẵn theo bộ sưu tập</strong> — phong cảnh, hoa lá, động vật, tranh phong thủy, tranh trang trí phòng khách, phòng ngủ, quán cà phê…</li>
  <li><strong>Thiết kế theo yêu cầu từ ảnh của bạn</strong> — gửi ảnh gia đình, ảnh cưới, ảnh thú cưng hoặc ảnh kỷ niệm, chúng tôi chuyển thành bản tranh số hóa độc bản rồi sản xuất và giao tận nhà.</li>
</ul>
<p>Mỗi bộ sản phẩm tiêu chuẩn gồm: canvas in sẵn số, bộ màu acrylic pha sẵn theo đúng bảng màu của tranh, cọ vẽ các cỡ và hướng dẫn sử dụng.</p>

<h2>Vì sao khách hàng chọn DALI?</h2>
<ul>
  <li><strong>Không cần biết vẽ</strong> — có hướng dẫn từng bước, phù hợp cả người lần đầu cầm cọ và trẻ em có người lớn kèm.</li>
  <li><strong>Tranh độc bản từ ảnh thật</strong> — món quà mang tính cá nhân cho sinh nhật, tân gia, kỷ niệm ngày cưới.</li>
  <li><strong>Thư giãn, giảm căng thẳng</strong> — tô tranh là cách nhiều người chọn để nghỉ ngơi sau giờ làm, rời mắt khỏi điện thoại.</li>
  <li><strong>Giao hàng toàn quốc</strong> — đóng gói kỹ, hỗ trợ kiểm tra khi nhận.</li>
</ul>

<h2>Cam kết của chúng tôi</h2>
<p>Chúng tôi cam kết mô tả sản phẩm đúng thực tế, tư vấn trung thực về kích thước và độ khó của tranh, đồng thời hỗ trợ khách hàng trong suốt quá trình tô. Nếu sản phẩm có lỗi từ khâu sản xuất hoặc hư hỏng do vận chuyển, chúng tôi chịu trách nhiệm xử lý — xem chi tiết tại <a href="/chinh-sach-doi-tra">Chính sách đổi trả &amp; vận chuyển</a>.</p>

<h2>Thông tin doanh nghiệp</h2>
{$infoTable}
<p>Bạn cần tư vấn chọn tranh hoặc đặt thiết kế theo ảnh? Xem <a href="/huong-dan-to-tranh">hướng dẫn tô tranh</a>, ghé <a href="/san-pham">cửa hàng</a> hoặc <a href="/lien-he">liên hệ với chúng tôi</a>.</p>
HTML,
    ],

    // ─────────────────────────── LIÊN HỆ ───────────────────────────
    'contact' => [
      'title' => 'Liên hệ DALI — Tranh tô màu số hóa',
      'desc'  => 'Thông tin liên hệ của Công ty TNHH Sản xuất và Thương mại Dali: địa chỉ, email, Facebook. Hỗ trợ tư vấn chọn tranh và đặt thiết kế theo ảnh.',
      'kick'  => 'Hỗ trợ khách hàng',
      'h1'    => 'Liên hệ với DALI',
      'lead'  => 'Cần tư vấn chọn tranh, hỏi về đơn hàng hay đặt thiết kế từ ảnh của bạn? Liên hệ với chúng tôi theo thông tin bên dưới.',
      'body'  => <<<HTML
<h2>Thông tin liên hệ</h2>
{$infoTable}

<h2>Kênh hỗ trợ nhanh nhất</h2>
<ul>
  <li><strong>Nhắn tin Facebook / Messenger</strong> — <a href="{$MSG}" target="_blank" rel="noopener">m.me/tranhtomau.dali</a>. Đây là kênh chúng tôi phản hồi nhanh nhất trong giờ làm việc.</li>
  <li><strong>Email</strong> — <a href="mailto:{$EMAIL}">{$EMAIL}</a>, phù hợp khi bạn cần gửi ảnh gốc để thiết kế hoặc gửi ảnh/video về tình trạng sản phẩm.</li>
</ul>
<p><em>Giờ làm việc: Thứ 2 – Thứ 7, 8:00 – 20:00. Ngoài giờ bạn vẫn có thể nhắn tin, chúng tôi phản hồi vào buổi làm việc kế tiếp.</em></p>

<h2>Một số việc bạn có thể tự làm ngay</h2>
<ul>
  <li><strong>Kiểm tra đơn hàng</strong> — dùng trang <a href="/tra-cuu-don-hang">Tra cứu đơn hàng</a> với mã đơn được gửi khi đặt.</li>
  <li><strong>Đặt tranh từ ảnh của bạn</strong> — gửi ảnh tại trang <a href="/thiet-ke">Thiết kế theo yêu cầu</a>.</li>
  <li><strong>Chưa biết tô thế nào</strong> — xem <a href="/huong-dan-to-tranh">hướng dẫn tô tranh từ A–Z</a> kèm video.</li>
</ul>

<h2>Góp ý &amp; khiếu nại</h2>
<p>Nếu bạn chưa hài lòng về sản phẩm hoặc dịch vụ, vui lòng gửi email kèm <strong>mã đơn hàng</strong> và ảnh/video minh họa tới <a href="mailto:{$EMAIL}">{$EMAIL}</a>. Chúng tôi sẽ xem xét và phản hồi theo <a href="/chinh-sach-doi-tra">Chính sách đổi trả &amp; vận chuyển</a>.</p>
HTML,
    ],

    // ─────────────────────── CHÍNH SÁCH BẢO MẬT ───────────────────────
    'privacy' => [
      'title' => 'Chính sách bảo mật | DALI',
      'desc'  => 'Chính sách bảo mật của DALI: chúng tôi thu thập thông tin gì, dùng để làm gì, cookie và quảng cáo của bên thứ ba (Google AdSense, Google Analytics), cùng quyền của bạn.',
      'kick'  => 'Quyền riêng tư',
      'h1'    => 'Chính sách bảo mật',
      'lead'  => 'Trang này giải thích rõ DALI thu thập những thông tin nào của bạn, dùng vào việc gì, chia sẻ với ai và bạn có những quyền gì.',
      'body'  => <<<HTML
<p class="upd">Áp dụng cho website tranhdali.vn và các trang con. Vận hành bởi {$COMPANY} (MST {$MST}).</p>

<h2>1. Chúng tôi thu thập thông tin gì?</h2>
<ul>
  <li><strong>Thông tin bạn chủ động cung cấp khi đặt hàng:</strong> họ tên, số điện thoại, địa chỉ nhận hàng, email và ghi chú đơn hàng.</li>
  <li><strong>Ảnh bạn tải lên</strong> khi sử dụng dịch vụ thiết kế tranh theo yêu cầu.</li>
  <li><strong>Thông tin kỹ thuật tự động:</strong> địa chỉ IP, loại trình duyệt, thiết bị, trang bạn đã xem và thời gian truy cập.</li>
</ul>
<p>Chúng tôi <strong>không</strong> thu thập và <strong>không</strong> lưu số thẻ ngân hàng của bạn trên website.</p>

<h2>2. Chúng tôi dùng thông tin để làm gì?</h2>
<ul>
  <li>Xử lý, sản xuất và giao đơn hàng của bạn.</li>
  <li>Liên hệ xác nhận đơn, thông báo tình trạng giao hàng, hỗ trợ sau bán.</li>
  <li>Thiết kế bản tranh số hóa từ ảnh bạn gửi (chỉ dùng cho đúng đơn hàng đó).</li>
  <li>Cải thiện website, sản phẩm và trải nghiệm mua sắm.</li>
</ul>

<h2>3. Cookie và quảng cáo của bên thứ ba</h2>
<p>Website sử dụng cookie để ghi nhớ giỏ hàng, phiên đăng nhập và đo lường truy cập. Ngoài ra chúng tôi sử dụng dịch vụ của bên thứ ba:</p>
<ul>
  <li><strong>Google AdSense</strong> — hiển thị quảng cáo. Google và các đối tác có thể dùng cookie để phân phát quảng cáo dựa trên lần truy cập trước đó của bạn tới website này hoặc các website khác.</li>
  <li><strong>Google Analytics</strong> — thống kê lượt truy cập ẩn danh để chúng tôi hiểu trang nào hữu ích.</li>
  <li><strong>Facebook Pixel</strong> — đo hiệu quả quảng cáo trên nền tảng Meta (nếu được bật).</li>
</ul>
<p>Bạn có thể <strong>từ chối quảng cáo cá nhân hóa</strong> bất cứ lúc nào:</p>
<ul>
  <li>Cài đặt quảng cáo của Google: <a href="https://www.google.com/settings/ads" target="_blank" rel="noopener nofollow">google.com/settings/ads</a></li>
  <li>Trang từ chối chung: <a href="https://www.aboutads.info/choices/" target="_blank" rel="noopener nofollow">aboutads.info/choices</a></li>
  <li>Hoặc tự xóa/chặn cookie trong phần cài đặt trình duyệt của bạn.</li>
</ul>

<h2>4. Chia sẻ thông tin</h2>
<p>Chúng tôi <strong>không bán, không trao đổi</strong> thông tin cá nhân của bạn. Thông tin chỉ được chia sẻ trong các trường hợp cần thiết:</p>
<ul>
  <li>Với <strong>đơn vị vận chuyển</strong> (tên, số điện thoại, địa chỉ) để giao hàng tới bạn.</li>
  <li>Với các <strong>nền tảng kỹ thuật</strong> nêu ở mục 3, theo chính sách riêng của họ.</li>
  <li>Khi có <strong>yêu cầu hợp pháp</strong> từ cơ quan nhà nước có thẩm quyền.</li>
</ul>

<h2>5. Lưu trữ và bảo mật</h2>
<p>Dữ liệu đơn hàng được lưu trên máy chủ có kiểm soát truy cập, chỉ nhân sự phụ trách mới xem được. Chúng tôi lưu thông tin đơn hàng trong thời gian cần thiết để phục vụ bảo hành, đổi trả và nghĩa vụ kế toán theo quy định.</p>

<h2>6. Quyền của bạn</h2>
<p>Bạn có quyền yêu cầu <strong>xem, sửa hoặc xóa</strong> thông tin cá nhân của mình, và yêu cầu chúng tôi ngừng liên hệ quảng cáo. Gửi yêu cầu tới <a href="mailto:{$EMAIL}">{$EMAIL}</a> kèm thông tin xác minh (mã đơn hàng hoặc số điện thoại đã đặt). Chúng tôi xử lý trong thời gian sớm nhất.</p>

<h2>7. Trẻ em</h2>
<p>Website hướng tới người mua từ đủ 18 tuổi. Chúng tôi không chủ đích thu thập thông tin của trẻ em. Nếu bạn cho rằng con bạn đã gửi thông tin cho chúng tôi, vui lòng liên hệ để được xóa.</p>

<h2>8. Thay đổi chính sách</h2>
<p>Chính sách có thể được cập nhật khi dịch vụ thay đổi. Bản mới sẽ được đăng ngay trên trang này. Mọi thắc mắc xin gửi về <a href="mailto:{$EMAIL}">{$EMAIL}</a>.</p>
HTML,
    ],

    // ─────────────────────────── ĐIỀU KHOẢN ───────────────────────────
    'terms' => [
      'title' => 'Điều khoản sử dụng | DALI',
      'desc'  => 'Điều khoản sử dụng website tranhdali.vn: quy định đặt hàng, giá và thanh toán, quyền sở hữu trí tuệ với ảnh khách gửi, và giới hạn trách nhiệm.',
      'kick'  => 'Quy định chung',
      'h1'    => 'Điều khoản sử dụng',
      'lead'  => 'Khi truy cập và đặt hàng tại tranhdali.vn, bạn đồng ý với các điều khoản dưới đây.',
      'body'  => <<<HTML
<p class="upd">Website tranhdali.vn được vận hành bởi {$COMPANY} (MST {$MST}).</p>

<h2>1. Chấp nhận điều khoản</h2>
<p>Bằng việc truy cập, duyệt hoặc đặt hàng trên website, bạn xác nhận đã đọc và đồng ý với các điều khoản này cùng <a href="/chinh-sach-bao-mat">Chính sách bảo mật</a>. Nếu không đồng ý, vui lòng ngừng sử dụng website.</p>

<h2>2. Đặt hàng</h2>
<ul>
  <li>Bạn có trách nhiệm cung cấp <strong>thông tin nhận hàng chính xác</strong> (tên, số điện thoại, địa chỉ). Chúng tôi không chịu trách nhiệm với đơn giao sai do thông tin bạn cung cấp sai.</li>
  <li>Đơn hàng chỉ được xem là xác lập khi chúng tôi <strong>xác nhận</strong> qua điện thoại, tin nhắn hoặc email.</li>
  <li>Chúng tôi có quyền từ chối hoặc hủy đơn nếu phát hiện thông tin giả mạo, có dấu hiệu gian lận, hoặc sản phẩm hết hàng — và sẽ thông báo cho bạn.</li>
</ul>

<h2>3. Giá và thanh toán</h2>
<p>Giá hiển thị trên website tính bằng <strong>đồng Việt Nam (VNĐ)</strong>. Giá và chương trình khuyến mãi có thể thay đổi mà không báo trước, nhưng <strong>không</strong> ảnh hưởng tới đơn hàng đã được xác nhận. Phí vận chuyển (nếu có) được hiển thị trước khi bạn hoàn tất đặt hàng.</p>

<h2>4. Ảnh bạn gửi và quyền sở hữu trí tuệ</h2>
<ul>
  <li><strong>Ảnh của bạn vẫn thuộc về bạn.</strong> Khi gửi ảnh để thiết kế tranh, bạn cho phép chúng tôi sử dụng ảnh đó <em>nhằm mục đích thiết kế và sản xuất đúng đơn hàng của bạn</em>.</li>
  <li>Bạn cam kết <strong>có quyền sử dụng hợp pháp</strong> đối với ảnh đã gửi và chịu trách nhiệm nếu ảnh vi phạm bản quyền hoặc quyền hình ảnh của người khác.</li>
  <li>Chúng tôi <strong>không</strong> dùng ảnh riêng tư của bạn để quảng cáo nếu chưa được bạn đồng ý.</li>
  <li>Toàn bộ nội dung do DALI tạo ra trên website (bài viết, hình ảnh sản phẩm, thiết kế, logo) thuộc quyền sở hữu của chúng tôi; không sao chép cho mục đích thương mại khi chưa có sự đồng ý bằng văn bản.</li>
</ul>

<h2>5. Hành vi không được phép</h2>
<p>Bạn không được dùng website để đăng tải nội dung vi phạm pháp luật, xâm phạm quyền của người khác, phát tán mã độc, hoặc can thiệp trái phép vào hệ thống.</p>

<h2>6. Giới hạn trách nhiệm</h2>
<p>Sản phẩm tranh tô màu số hóa là mặt hàng thủ công do <strong>chính khách hàng tự tô</strong>; kết quả cuối cùng phụ thuộc vào cách thực hiện của mỗi người. Chúng tôi chịu trách nhiệm về chất lượng vật tư và bản in số theo <a href="/chinh-sach-doi-tra">Chính sách đổi trả</a>, nhưng không chịu trách nhiệm với thành phẩm sau khi bạn đã tô.</p>

<h2>7. Luật áp dụng</h2>
<p>Các điều khoản này được điều chỉnh bởi pháp luật Việt Nam. Tranh chấp phát sinh sẽ ưu tiên giải quyết bằng thương lượng; nếu không đạt được, sẽ do cơ quan có thẩm quyền tại Việt Nam giải quyết.</p>

<h2>8. Liên hệ</h2>
<p>Mọi câu hỏi về điều khoản, vui lòng gửi tới <a href="mailto:{$EMAIL}">{$EMAIL}</a> hoặc xem trang <a href="/lien-he">Liên hệ</a>.</p>
HTML,
    ],

    // ─────────────────── ĐỔI TRẢ & VẬN CHUYỂN ───────────────────
    'return' => [
      'title' => 'Chính sách đổi trả & vận chuyển | DALI',
      'desc'  => 'Chính sách vận chuyển và đổi trả của DALI: phạm vi giao hàng toàn quốc, cách kiểm tra khi nhận, điều kiện đổi trả với sản phẩm lỗi và quy trình xử lý.',
      'kick'  => 'Mua hàng an tâm',
      'h1'    => 'Chính sách đổi trả &amp; vận chuyển',
      'lead'  => 'Chúng tôi muốn bạn nhận được bộ tranh nguyên vẹn và đúng mong đợi. Dưới đây là cách chúng tôi giao hàng và xử lý khi có sự cố.',
      'body'  => <<<HTML
<h2>1. Phạm vi và đơn vị vận chuyển</h2>
<p>DALI giao hàng <strong>toàn quốc</strong> thông qua các đơn vị vận chuyển đối tác (Viettel Post và các đơn vị tương đương). Sau khi đơn được xác nhận và đóng gói, bạn sẽ nhận được thông tin để theo dõi tại trang <a href="/tra-cuu-don-hang">Tra cứu đơn hàng</a>.</p>

<h2>2. Phí và thời gian giao hàng</h2>
<ul>
  <li><strong>Phí vận chuyển</strong> được tính theo khu vực và khối lượng kiện hàng, và luôn <strong>hiển thị rõ trước khi bạn hoàn tất đặt hàng</strong>.</li>
  <li>Chúng tôi có áp dụng <strong>miễn phí vận chuyển cho đơn đạt ngưỡng ưu đãi</strong>; mức cụ thể hiển thị tại trang thanh toán ở từng thời điểm.</li>
  <li><strong>Thời gian giao</strong> phụ thuộc khu vực và đơn vị vận chuyển. Với tranh <em>thiết kế theo yêu cầu</em>, cần thêm thời gian thiết kế và sản xuất trước khi gửi đi — chúng tôi sẽ thông báo khi xác nhận đơn.</li>
</ul>

<h2>3. Kiểm tra khi nhận hàng</h2>
<p>Bạn <strong>nên quay video lúc mở kiện hàng</strong> và kiểm tra ngay khi nhận. Đây là căn cứ nhanh nhất để chúng tôi xử lý nếu có thiếu sót hoặc hư hỏng. Cần kiểm tra: canvas có rách/gãy khung không, đủ bộ màu và cọ không, số in trên canvas có rõ không.</p>

<h2>4. Trường hợp được đổi / trả</h2>
<p>Chúng tôi <strong>chịu trách nhiệm đổi hoặc bù sản phẩm</strong> khi:</p>
<ul>
  <li>Sản phẩm bị <strong>hư hỏng do vận chuyển</strong> (canvas rách, khung gãy, hộp màu vỡ/đổ).</li>
  <li><strong>Lỗi từ khâu sản xuất</strong>: in số bị mờ/sai, thiếu màu, thiếu cọ, giao sai mẫu hoặc sai kích thước so với đơn đã xác nhận.</li>
</ul>
<p>Cách xử lý: liên hệ <strong>ngay khi phát hiện</strong> qua <a href="mailto:{$EMAIL}">{$EMAIL}</a> hoặc <a href="{$MSG}" target="_blank" rel="noopener">Messenger</a>, kèm <strong>mã đơn hàng</strong> và <strong>ảnh/video</strong> tình trạng sản phẩm. Chúng tôi sẽ đổi sản phẩm mới, gửi bù phần thiếu hoặc hoàn tiền tùy mức độ — <strong>chi phí do DALI chịu</strong> khi lỗi thuộc về chúng tôi.</p>

<h2>5. Trường hợp không áp dụng đổi trả</h2>
<ul>
  <li>Sản phẩm <strong>đã được tô</strong> (dù chỉ một phần) hoặc đã qua sử dụng, trừ khi lỗi thuộc mục 4.</li>
  <li>Hư hỏng do <strong>bảo quản/sử dụng sai cách</strong> sau khi nhận (làm đổ màu, để ẩm mốc, va đập…).</li>
  <li><strong>Tranh thiết kế theo yêu cầu</strong> đã được sản xuất <strong>đúng với bản thiết kế bạn duyệt</strong> — vì là hàng cá nhân hóa, không thể bán lại cho người khác. Vì vậy hãy kiểm tra kỹ bản thiết kế trước khi xác nhận sản xuất.</li>
  <li>Lý do <strong>thay đổi ý định</strong> sau khi hàng đã sản xuất/gửi đi, hoặc khác biệt nhỏ về sắc độ màu do hiển thị màn hình.</li>
</ul>

<h2>6. Hoàn tiền</h2>
<p>Với trường hợp được chấp nhận hoàn tiền, chúng tôi hoàn về <strong>đúng phương thức bạn đã thanh toán</strong> hoặc chuyển khoản theo tài khoản bạn cung cấp, sau khi hai bên thống nhất phương án xử lý.</p>

<h2>7. Hỗ trợ</h2>
<p>Mọi vướng mắc về đơn hàng, vui lòng liên hệ <a href="mailto:{$EMAIL}">{$EMAIL}</a> hoặc xem thêm tại trang <a href="/lien-he">Liên hệ</a>. Chúng tôi luôn ưu tiên xử lý để bạn có trải nghiệm tô tranh trọn vẹn.</p>
HTML,
    ],
  ];

  $slug = $slug ?? 'about';
  $p    = $pages[$slug] ?? $pages['about'];

  $navPhone = $PHONE;
@endphp
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $p['title'] }} | DALI</title>
<meta name="description" content="{{ $p['desc'] }}">
<link rel="canonical" href="{{ url()->current() }}">
<meta property="og:title" content="{{ $p['title'] }}">
<meta property="og:description" content="{{ $p['desc'] }}">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ asset('images/og-home.jpg') }}?v=1">
@if(!empty($settings['ga_id']))<script async src="https://www.googletagmanager.com/gtag/js?id={{ $settings['ga_id'] }}"></script><script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ $settings["ga_id"] }}');</script>@endif
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet">
<style>[class^="ri-"],[class*=" ri-"]{vertical-align:-.125em;font-style:normal;line-height:1}</style>
<style>
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--gn:#C6F135;--bd:#C8E89A;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0}html{scroll-behavior:smooth}
body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:var(--tx);line-height:1.6}
nav{background:linear-gradient(175deg,#1C5200,#2D7A08,#3A9A12);height:68px;padding:0 5%;display:flex;align-items:center;justify-content:space-between}
.nav-logo{height:38px;object-fit:contain;display:block;filter:brightness(0) invert(1)}
.nav-links{display:flex;gap:26px;list-style:none}
.nav-links a{text-decoration:none;color:rgba(255,255,255,.78);font-size:14px;font-weight:500}
.nav-links a:hover{color:#fff}
.nav-right{display:flex;align-items:center;gap:12px}
.nav-phone{font-size:13px;font-weight:600;color:rgba(255,255,255,.85);text-decoration:none}
.btn-nav{background:var(--gn);color:var(--char);border:none;border-radius:50px;padding:9px 20px;font-size:13px;font-weight:800;cursor:pointer;text-decoration:none}
.page-hero{background:linear-gradient(175deg,#1C5200,#2D7A08);padding:46px 5% 40px;color:#fff;text-align:center}
.page-hero .kick{display:inline-block;background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.25);color:#fff;font-size:12px;font-weight:700;padding:5px 14px;border-radius:50px;margin-bottom:14px}
.page-hero h1{font-size:clamp(23px,3.4vw,36px);font-weight:900;margin-bottom:10px;line-height:1.2}
.page-hero p{font-size:15px;opacity:.85;max-width:620px;margin:0 auto}
.wrap{max-width:820px;margin:0 auto;padding:0 5%}
.card{background:#fff;border:1.5px solid var(--bd);border-radius:18px;padding:30px 30px 34px;margin:-26px 0 46px;box-shadow:0 8px 30px rgba(58,122,10,.08);position:relative}
.card h2{font-size:clamp(17px,2.1vw,21px);font-weight:900;color:var(--char);margin:26px 0 10px;padding-top:4px}
.card h2:first-child{margin-top:4px}
.card p{font-size:15px;color:var(--tx2);margin-bottom:12px;line-height:1.78}
.card ul{margin:0 0 14px 20px}
.card li{font-size:15px;color:var(--tx2);margin-bottom:8px;line-height:1.75}
.card a{color:var(--gd);font-weight:600}
.card a:hover{color:var(--g)}
.card strong{color:var(--char)}
.card .upd{font-size:13.5px;color:var(--tx3);background:var(--gll);border:1px dashed var(--bd);border-radius:10px;padding:10px 14px;margin-bottom:6px}
table.info{width:100%;border-collapse:collapse;margin:6px 0 16px;font-size:15px}
table.info th,table.info td{text-align:left;padding:10px 12px;border-bottom:1px solid var(--gl);vertical-align:top}
table.info th{width:150px;color:var(--char);font-weight:800;background:var(--gll)}
table.info td{color:var(--tx2)}
.legal-nav{display:flex;flex-wrap:wrap;gap:8px;justify-content:center;margin:0 0 40px}
.legal-nav a{font-size:13px;font-weight:700;text-decoration:none;color:var(--tx2);background:#fff;border:1.5px solid var(--bd);border-radius:50px;padding:8px 16px}
.legal-nav a:hover{background:var(--gl);color:var(--gd)}
.legal-nav a.on{background:var(--gd);border-color:var(--gd);color:#fff}
footer{background:linear-gradient(175deg,#173F00,#0F2A00);color:rgba(255,255,255,.6);padding:36px 5% 26px;font-size:13.5px}
footer .f-in{max-width:900px;margin:0 auto;text-align:center}
footer strong{color:#fff}
footer a{color:rgba(255,255,255,.72);text-decoration:none}
footer a:hover{color:var(--gn)}
.f-links{display:flex;flex-wrap:wrap;gap:6px 18px;justify-content:center;margin:14px 0 16px}
.f-bot{border-top:1px solid rgba(255,255,255,.12);padding-top:16px;margin-top:16px;font-size:12.5px;color:rgba(255,255,255,.45)}
@media(max-width:820px){.nav-links{display:none}.card{padding:24px 20px 28px}table.info th{width:120px}}
</style>
</head>
<body>
<nav>
  <a href="{{ route('home') }}"><img src="{{ asset('images/logo_dali.png') }}" alt="DALI" class="nav-logo"></a>
  <ul class="nav-links">
    <li><a href="{{ route('home') }}">Trang chủ</a></li>
    <li><a href="{{ route('products') }}">Sản phẩm</a></li>
    <li><a href="{{ route('thiet-ke') }}">🎨 Thiết kế</a></li>
    <li><a href="{{ route('guide') }}">Hướng dẫn</a></li>
  </ul>
  <div class="nav-right">
    @if($navPhone)<a href="tel:{{ $navPhone }}" class="nav-phone"><i class="ri-phone-line" style="margin-right:5px"></i>{{ $navPhone }}</a>@endif
    <a href="{{ route('products') }}" class="btn-nav">Mua sắm</a>
  </div>
</nav>

<div class="page-hero">
  <span class="kick">{{ $p['kick'] }}</span>
  <h1>{!! $p['h1'] !!}</h1>
  <p>{{ $p['lead'] }}</p>
</div>

<div class="wrap">
  <article class="card">
    {!! $p['body'] !!}
  </article>

  {{-- Liên kết chéo giữa các trang pháp lý --}}
  <nav class="legal-nav" aria-label="Trang thông tin">
    <a href="{{ route('about') }}"        class="{{ $slug==='about'   ? 'on' : '' }}">Giới thiệu</a>
    <a href="{{ route('contact') }}"      class="{{ $slug==='contact' ? 'on' : '' }}">Liên hệ</a>
    <a href="{{ route('privacy') }}"      class="{{ $slug==='privacy' ? 'on' : '' }}">Chính sách bảo mật</a>
    <a href="{{ route('terms') }}"        class="{{ $slug==='terms'   ? 'on' : '' }}">Điều khoản</a>
    <a href="{{ route('return-policy') }}" class="{{ $slug==='return' ? 'on' : '' }}">Đổi trả &amp; vận chuyển</a>
  </nav>
</div>

<footer>
  <div class="f-in">
    <strong>{{ $COMPANY }}</strong><br>
    MST: {{ $MST }} · {{ $ADDR }}<br>
    Email: <a href="mailto:{{ $EMAIL }}">{{ $EMAIL }}</a>@if($navPhone) · ĐT: <a href="tel:{{ $navPhone }}">{{ $navPhone }}</a>@endif
    <div class="f-links">
      <a href="{{ route('home') }}">Trang chủ</a>
      <a href="{{ route('products') }}">Sản phẩm</a>
      <a href="{{ route('guide') }}">Hướng dẫn</a>
      <a href="{{ route('about') }}">Giới thiệu</a>
      <a href="{{ route('contact') }}">Liên hệ</a>
      <a href="{{ route('privacy') }}">Chính sách bảo mật</a>
      <a href="{{ route('terms') }}">Điều khoản</a>
      <a href="{{ route('return-policy') }}">Đổi trả &amp; vận chuyển</a>
    </div>
    <div class="f-bot">© {{ date('Y') }} DALI — Tranh tô màu số hóa. Thiết kế tại Việt Nam 🇻🇳</div>
  </div>
</footer>

@include('partials.float-widget')
@include('partials.bottom-nav')
</body>
</html>
