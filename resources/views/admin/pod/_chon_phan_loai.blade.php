{{-- <option> phân loại theo mẫu seller thấy. Biến: $mau (collection PodMau kèm bienThe), $bt (giá từng phân loại), $chon (id đang chọn). --}}
<option value="">—</option>
@foreach($mau as $m)
<optgroup label="{{ $m->ten }}{{ $m->laRieng() ? ' (mẫu riêng)' : '' }}">
  @foreach($m->bienThe as $b)
    @continue($b->laCu())
    <option value="{{ $b->id }}" @selected((string) $chon === (string) $b->id)>{{ $m->ten }} · {{ trim(($m->tenKieu($b->kieu) ? $m->tenKieu($b->kieu) . ' · ' : '') . $b->ten) }} · {{ \App\Services\PodSo::chatLieu($b->chat_lieu) }} — {{ number_format($bt[$b->id]['gia'] ?? 0, 0, ',', '.') }}đ</option>
  @endforeach
</optgroup>
@endforeach
