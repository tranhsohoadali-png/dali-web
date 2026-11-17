@include('admin.pod._dau', ['tieuDe' => 'Đơn ' . $don->ma])
<div class="card">
  <div class="card-h"><div class="card-t">{{ $don->ma }} · <span class="badge b-{{ $don->trang_thai }}">{{ $don->tenTrangThai() }}</span>@if($don->xuong_tao) <span class="badge b-chung">Xưởng tạo hộ</span>@endif</div>
    <div class="card-hint">Đặt {{ optional($don->created_at)->format('d/m/Y H:i') }} · {{ $don->da_tru ? 'Đã trừ ví' : 'Chưa trừ ví' }}@if($don->huy_luc) · Huỷ {{ $don->huy_luc->format('d/m/Y H:i') }}@endif @if($don->giao_luc) · Giao {{ $don->giao_luc->format('d/m/Y H:i') }}@endif @if($don->nguoi_in) · In: {{ $don->nguoi_in }}@endif @if($don->nguoi_giao) · Giao bởi: {{ $don->nguoi_giao }}@endif</div></div>
  <div class="pad grid" style="align-items:start">
    <div><div class="muted">Seller</div><b>{{ optional($don->seller)->ten }}</b> ({{ optional($don->seller)->ma }})<div class="muted">{{ optional($don->seller)->sdt }}</div>@if($don->nhan_vien_id)<div class="muted">Người đặt: nhân viên {{ optional(\App\Models\PodNhanVien::find($don->nhan_vien_id))->ten ?? '#' . $don->nhan_vien_id }}</div>@endif</div>
    <div><div class="muted">Giao tới</div>@if($don->noi_giao)<b>{{ $don->noi_giao['ten'] ?? '' }}</b><div>{{ $don->noi_giao['dia_chi'] ?? '' }}</div><div class="muted">{{ $don->noi_giao['sdt'] ?? '' }} {{ $don->noi_giao['ghi_chu'] ?? '' }}</div>@else<span class="badge b-cho_nap">Chưa có địa chỉ</span><div class="muted">Seller bổ sung ở Đơn của tôi, hoặc sửa ở mục bên dưới.</div>@endif</div>
    <div><div class="muted">Mã đơn sàn</div><b>{{ $don->ma_don_san ?: '—' }}</b>@if($don->ma_van_don)<div class="muted" style="margin-top:6px">Mã vận đơn</div><b>{{ $don->ma_van_don }}</b>@endif</div>
    <div><div class="muted">Nhãn line bay</div>@if($coNhan)<a class="btn-sm" href="{{ route('admin.pod.don.nhan', $don) }}">Tải nhãn</a>@else<span class="muted">Không có</span>@endif</div>
    @if($don->ghi_chu)<div class="f rong"><div class="muted">Ghi chú của seller</div>{{ $don->ghi_chu }}</div>@endif
  </div>
</div>
<div class="card">
  <table>
    <tr><th>Mẫu · phân loại</th><th>Màu</th><th class="num">SL</th><th>Chữ cá nhân hoá</th><th>File in</th><th class="num">Thành tiền</th></tr>
    @foreach($don->dong as $x)
    <tr>
      <td><b>{{ $x->ten_mau }}</b><div class="muted">{{ $x->ten_bien_the }}@if($x->chat_lieu) · <b>{{ $x->chat_lieu }}</b>@endif · {{ rtrim(rtrim(number_format($x->gram, 1, ',', '.'), '0'), ',') }} g × {{ number_format($x->don_gia_gram, 0, ',', '.') }}đ</div></td>
      <td>{{ $x->mau_sac ?: '—' }}</td>
      <td class="num"><b>{{ $x->so_luong }}</b></td>
      <td>{{ $x->chu_ca_nhan ?: '—' }}@if($x->font)<div class="muted">font {{ $x->font }}</div>@endif @if($x->phi_ca_nhan)<div class="muted">+{{ number_format($x->phi_ca_nhan, 0, ',', '.') }}đ</div>@endif</td>
      <td class="muted" style="word-break:break-word">
        @if(!empty($taoFile[$x->id]))
        {{-- JSON_FORCE_OBJECT: mẫu không tuỳ chọn có tuy_chon = [] -> PHP mã hoá thành list "[]", app Thay tên từ chối
             (dict_type, lỗi thật 11-11 ở ornament Pilates). Gói tham số chỉ có khoá chữ nên ép object không hại gì. --}}
        <div class="tao-file" data-tao="{{ json_encode($taoFile[$x->id], JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT) }}" style="margin-bottom:6px">
          <button type="button" class="btn-sm" style="cursor:pointer">Tạo + tải file in</button>
          <span class="tao-tt" style="margin-left:6px"></span>
          @if($x->so_luong > 1)<div style="margin-top:4px">File 1 chiếc, đơn cần {{ $x->so_luong }} chiếc.</div>@endif
        </div>
        @endif
        @if(!empty($tepIn[$x->id]))
        @php $ds = $tepIn[$x->id]; $kb = array_sum(array_column($ds, 'kb')); @endphp
        <div class="tai-file" style="margin-bottom:6px">
          <a class="btn-sm" href="{{ route('admin.pod.don.filein', [$don, $x->id]) }}" download>{{ count($ds) > 1 ? 'Tải ' . count($ds) . ' file in (.zip)' : 'Tải file in' }} · {{ $kb >= 1024 ? number_format($kb / 1024, 1, ',', '.') . ' MB' : $kb . ' KB' }}</a>
          <div style="margin-top:4px">{{ implode(' · ', array_column($ds, 'ten')) }}@if($x->so_luong > 1) — file 1 chiếc, đơn cần {{ $x->so_luong }} chiếc @endif</div>
        </div>
        @endif
        {{ $fileIn[$x->bien_the_id] ?? '—' }}
      </td>
      <td class="num">{{ number_format($x->thanh_tien, 0, ',', '.') }}đ</td>
    </tr>
    @endforeach
    <tr><td colspan="5" class="num"><b>Tổng</b></td><td class="num"><b>{{ number_format($don->tong_tien, 0, ',', '.') }}đ</b></td></tr>
  </table>
</div>
@if(in_array($don->trang_thai, ['cho_nap', 'cho_in', 'dang_in'], true))
<div class="card"><div class="pad acts" style="align-items:flex-end">
  @if($don->trang_thai === 'cho_in')
    <form method="POST" action="{{ route('admin.pod.don.status', $don) }}">@csrf<input type="hidden" name="trang_thai" value="dang_in"><button class="btn">Bắt đầu in</button></form>
  @endif
  @if(in_array($don->trang_thai, ['cho_in', 'dang_in'], true))
    <form method="POST" action="{{ route('admin.pod.don.status', $don) }}" class="acts" style="align-items:flex-end">@csrf
      <input type="hidden" name="trang_thai" value="da_giao">
      <label class="f" style="width:200px"><span>Mã vận đơn trong nước</span><input name="ma_van_don" value="{{ $don->ma_van_don }}" maxlength="60"></label>
      <button class="btn">Đã giao</button>
    </form>
  @endif
  <form method="POST" action="{{ route('admin.pod.don.huy', $don) }}" class="acts" style="margin-left:auto">@csrf
    <label style="font-size:12px;display:flex;gap:6px;align-items:center"><input type="checkbox" required> Chắc chắn huỷ{{ $don->da_tru ? ', hoàn tiền vào ví' : '' }}</label>
    <button class="btn-sm btn-del">Huỷ đơn</button>
  </form>
</div></div>
<div class="card">
  <details @if($errors->has('thong_tin')) open @endif><summary class="pad" style="cursor:pointer;font-weight:800;color:var(--gd)">Sửa nơi giao · mã đơn sàn · ghi chú</summary>
  <form method="POST" action="{{ route('admin.pod.don.thongtin', $don) }}" class="pad grid" style="align-items:start;padding-top:0">
    @csrf
    @include('admin.pod._noi_giao', ['noiGiao' => $noiGiaoSeller, 'chonSan' => 'moi', 'ngCu' => (array) $don->noi_giao])
    <label class="f"><span>Mã đơn sàn</span><input name="ma_don_san" value="{{ old('ma_don_san', $don->ma_don_san) }}" maxlength="60"></label>
    <label class="f rong"><span>Ghi chú</span><textarea name="ghi_chu" rows="2" maxlength="1000">{{ old('ghi_chu', $don->ghi_chu) }}</textarea></label>
    <div class="f rong">
      @if($soCung > 0)<label style="display:flex;gap:6px;align-items:center;font-size:13px"><input type="checkbox" name="ap_cung" value="1" style="width:auto" checked> Áp nơi giao mới cho cả {{ $soCung }} đơn chưa giao khác của seller {{ $don->noi_giao ? 'đang ghi cùng nơi giao "' . ($don->noi_giao['ten'] ?? '') . '"' : 'cũng chưa có địa chỉ' }}</label>@endif
      <label style="display:flex;gap:6px;align-items:center;font-size:13px;margin-top:6px"><input type="checkbox" name="luu_so" value="1" style="width:auto"> Lưu vào sổ nơi giao của seller (seller chọn được ở cổng)</label>
    </div>
    <div><button class="btn">Lưu thông tin giao</button></div>
  </form>
  </details>
</div>
@endif
<a class="btn-sm" href="{{ route('admin.pod.don.index') }}">Về hàng đợi</a>
@if($taoFile)
<script>
/* Dựng file in theo đúng chữ + cỡ của dòng đơn: gọi app Thay tên cùng tên miền (nginx kiểm phiên admin, gắn khoá).
   Chữ lấy từ đơn nên chỉ ghi bằng textContent. */
document.querySelectorAll('.tao-file').forEach(function (hop) {
  var APP = '/admin/3d/thay-ten/app/', nut = hop.querySelector('button'), tt = hop.querySelector('.tao-tt');
  function ngu(ms) { return new Promise(function (ok) { setTimeout(ok, ms); }); }
  async function json(r) {
    var d = null;
    try { d = await r.json(); } catch (e) {}
    if (!r.ok || !d) throw new Error((d && (d.detail || d.loi)) || (r.status === 401 ? 'Phiên admin đã hết, đăng nhập lại rồi bấm lại.' : 'App Thay tên chưa phản hồi (mã ' + r.status + ').'));
    return d;
  }
  function tai(id) {
    var a = document.createElement('a');
    a.href = APP + 'api/viec/' + encodeURIComponent(id) + '/tai';
    a.download = ''; document.body.appendChild(a); a.click(); a.remove();
  }
  nut.addEventListener('click', async function () {
    nut.disabled = true; nut.textContent = 'Đang tạo…'; tt.textContent = ''; tt.style.color = '';
    try {
      var r = await fetch(APP + 'api/dung', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: hop.dataset.tao });
      var id = (await json(r)).id, v = null;
      for (var i = 0; i < 300; i++) { // tối đa 5 phút, mỗi lần 1 giây
        v = await json(await fetch(APP + 'api/viec/' + encodeURIComponent(id), { cache: 'no-store' }));
        if (v.trang_thai === 'xong' || v.trang_thai === 'loi') break;
        tt.textContent = (v.phan_tram || 0) + '% ' + (v.loi_nhan || '');
        await ngu(1000);
      }
      if (!v || v.trang_thai !== 'xong') throw new Error((v && (v.loi || v.loi_nhan)) || 'Tạo file quá lâu, bấm lại sau.');
      tai(id);
      tt.textContent = 'Đã tạo. Nếu trình duyệt chưa tải, bấm lại nút.';
      tt.style.color = '';
    } catch (e) {
      tt.textContent = e.message; tt.style.color = '#B91C1C';
    }
    nut.disabled = false; nut.textContent = 'Tạo + tải file in';
  });
});
</script>
@endif
@include('admin.pod._cuoi')
