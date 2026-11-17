{{-- Chọn nơi giao: sổ nơi giao của seller hoặc "Nhập nơi giao khác".
     Biến: $noiGiao (collection PodNoiGiao), $chonSan (id | 'moi'), $ngCu (mảng ten/dia_chi/sdt/ghi_chu cho ô nhập tay). --}}
@php $chonNg = (string) old('noi_giao_id', $chonSan ?? ''); $ngCu = $ngCu ?? []; @endphp
<label class="f rong"><span>Nơi giao</span>
  <select name="noi_giao_id" class="ng-chon">
    @foreach($noiGiao as $ng)<option value="{{ $ng->id }}" @selected($chonNg === (string) $ng->id)>{{ $ng->ten }} — {{ $ng->dia_chi }}</option>@endforeach
    <option value="moi" @selected($chonNg === 'moi' || $noiGiao->isEmpty())>+ Nhập nơi giao khác</option>
  </select>
</label>
<div class="f rong ng-moi">
  <div class="grid">
    <label class="f"><span>Tên nơi giao / người nhận</span><input name="ng_ten" value="{{ old('ng_ten', $ngCu['ten'] ?? '') }}" maxlength="120"></label>
    <label class="f"><span>Số điện thoại</span><input name="ng_sdt" value="{{ old('ng_sdt', $ngCu['sdt'] ?? '') }}" maxlength="30"></label>
    <label class="f rong"><span>Địa chỉ</span><input name="ng_dia_chi" value="{{ old('ng_dia_chi', $ngCu['dia_chi'] ?? '') }}" maxlength="500"></label>
    <label class="f rong"><span>Ghi chú giao</span><input name="ng_ghi_chu" value="{{ old('ng_ghi_chu', $ngCu['ghi_chu'] ?? '') }}" maxlength="300"></label>
  </div>
</div>
<script>
/* Hiện ô nhập tay khi chọn "Nhập nơi giao khác". */
document.querySelectorAll('select.ng-chon:not([data-gan])').forEach(function (s) {
  s.dataset.gan = '1';
  var o = s.closest('form').querySelector('.ng-moi');
  function hien() { o.style.display = s.value === 'moi' ? '' : 'none'; }
  s.addEventListener('change', hien); hien();
});
</script>
