@include('admin.pod._dau', ['tieuDe' => $mau->exists ? 'Sửa mẫu: ' . $mau->ten : 'Thêm mẫu POD'])
@php
  $rows = old('bt', $bienThe->map(fn ($b) => ['id' => $b->id, 'ten' => $b->ten, 'gram' => $b->gram, 'chat_lieu' => $b->chat_lieu, 'mau_sac' => $b->mau_sac, 'file_in' => $b->file_in])->all());
  for ($i = 0; $i < 3; $i++) $rows[] = ['id' => '', 'ten' => '', 'gram' => '', 'chat_lieu' => 'PETG', 'mau_sac' => '', 'file_in' => ''];
  $bangGia = \App\Services\PodSo::bangGia();
  $chuHienTai = old('chu_seller_id', $mau->chu_seller_id);
@endphp
<form method="POST" enctype="multipart/form-data" action="{{ $mau->exists ? route('admin.pod.mau.update', $mau) : route('admin.pod.mau.store') }}">
  @csrf @if($mau->exists) @method('PUT') @endif
  <div class="card">
    <div class="card-h"><div class="card-t">Thông tin mẫu</div>@if($mau->exists)<div class="card-hint">Mã {{ $mau->ma }}</div>@endif</div>
    <div class="pad grid">
      <label class="f"><span>Tên mẫu</span><input name="ten" value="{{ old('ten', $mau->ten) }}" required maxlength="160"></label>
      <label class="f"><span>Nhóm</span><input name="nhom" value="{{ old('nhom', $mau->nhom) }}" maxlength="60" placeholder="Khuôn bánh, Móc khoá…"></label>
      <label class="f"><span>Thuộc kho</span>
        <select name="chu_seller_id">
          <option value="">Kho chung (mọi seller thấy)</option>
          @foreach($sellers as $s)<option value="{{ $s->id }}" @selected((string) $chuHienTai === (string) $s->id)>Kho riêng: {{ $s->ten }} ({{ $s->ma }})</option>@endforeach
        </select>
      </label>
      <label class="f"><span>Phí cá nhân hoá (đ/dòng có chữ riêng, 0–10.000)</span><input name="phi_ca_nhan" type="number" min="0" max="10000" step="500" value="{{ old('phi_ca_nhan', (int) $mau->phi_ca_nhan) }}"></label>
      <label class="f"><span>Tăng giá riêng mẫu này (%, 0 = theo đơn giá chung)</span><input name="tang_gia" type="number" min="0" max="300" step="1" value="{{ old('tang_gia', (int) $mau->tang_gia) }}"></label>
      <label class="f"><span>Phí thiết kế seller đã trả (đ)</span><input name="phi_thiet_ke" type="number" min="0" step="1000" value="{{ old('phi_thiet_ke', (int) $mau->phi_thiet_ke) }}"></label>
      <label class="f" style="flex-direction:row;gap:8px;align-items:center"><input type="checkbox" name="hien" value="1" style="width:auto" @checked(old('hien', $mau->hien))> <span>Hiện cho seller</span></label>
      <label class="f rong"><span>Mô tả cho seller</span><textarea name="mo_ta" rows="3" maxlength="5000">{{ old('mo_ta', $mau->mo_ta) }}</textarea></label>
    </div>
    @if($mau->exists && $mau->chu_seller_id)
    <div class="pad" style="padding-top:0">
      <div class="canh">
        <b>Đây là mẫu riêng của {{ optional($mau->chu)->ten }}.</b> Chỉ chuyển sang Kho chung khi seller đã đồng ý bằng văn bản.
        <label style="display:flex;gap:8px;align-items:center;margin-top:8px"><input type="checkbox" name="xac_nhan_chung" value="1"> Tôi xác nhận seller đã đồng ý chuyển mẫu này sang Kho chung</label>
        <input name="dong_y_chung" value="{{ old('dong_y_chung') }}" maxlength="300" placeholder="Ghi lời đồng ý: kênh, ngày (vd: Zalo 28/10, seller nhắn 'đồng ý')" style="width:100%;margin-top:8px;border:1px solid #F1D084;border-radius:8px;padding:8px 10px;font-size:13px">
      </div>
    </div>
    @endif
  </div>

  <div class="card">
    <div class="card-h"><div class="card-t">Phân loại</div><div class="card-hint">Gram lấy từ slicer, gồm support và nhựa xả. Giá in = gram × đơn giá. Bỏ trống tên một dòng cũ để xoá dòng đó.</div></div>
    <table>
      <tr><th>Tên phân loại</th><th style="width:110px">Gram</th><th style="width:150px">Chất liệu</th><th>Màu cho phép (phẩy ngăn cách)</th><th>File in trên máy xưởng (chỉ admin thấy)</th></tr>
      @foreach($rows as $i => $b)
      <tr>
        <td><input type="hidden" name="bt[{{ $i }}][id]" value="{{ $b['id'] }}"><input name="bt[{{ $i }}][ten]" value="{{ $b['ten'] }}" maxlength="120" placeholder="vd: Tem + khuôn cắt · 3 inch"></td>
        <td><input name="bt[{{ $i }}][gram]" value="{{ $b['gram'] }}" type="number" step="0.1" min="0.1"></td>
        <td><select name="bt[{{ $i }}][chat_lieu]">@foreach($bangGia as $cl => $g)<option value="{{ $cl }}" @selected(\App\Services\PodSo::chatLieu($b['chat_lieu'] ?? null) === $cl)>{{ $cl }} · {{ number_format($g, 0, ',', '.') }}đ/g</option>@endforeach</select></td>
        <td><input name="bt[{{ $i }}][mau_sac]" value="{{ $b['mau_sac'] }}" maxlength="300" placeholder="Trắng, Hồng, Xanh"></td>
        <td><input name="bt[{{ $i }}][file_in]" value="{{ $b['file_in'] }}" maxlength="300" placeholder="E:\POD\..."></td>
      </tr>
      @endforeach
    </table>
  </div>

  <div class="card">
    <div class="card-h"><div class="card-t">Ảnh mockup</div><div class="card-hint">Lưu ở đĩa riêng tư; seller chỉ xem qua link có chữ ký 10 phút. Ảnh đầu tiên là ảnh đại diện.</div></div>
    <div class="pad">
      @if($mau->exists && !empty($mau->anh))
      <div class="acts" style="margin-bottom:12px">
        @foreach(array_values($mau->anh) as $i => $rel)
        <label style="text-align:center;font-size:11px"><img class="thumb" style="width:90px;height:90px" src="{{ route('admin.pod.mau.anh', [$mau, $i]) }}" alt=""><br><input type="checkbox" name="xoa_anh[]" value="{{ $i }}"> xoá</label>
        @endforeach
      </div>
      @endif
      <input type="file" name="anh_moi[]" multiple accept="image/jpeg,image/png,image/webp">
    </div>
  </div>
  <div class="acts"><button class="btn">{{ $mau->exists ? 'Lưu mẫu' : 'Tạo mẫu' }}</button><a class="btn-sm" href="{{ route('admin.pod.mau.index') }}">Về danh sách</a></div>
</form>
@include('admin.pod._cuoi')
