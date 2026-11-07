{{-- Ô "Phân loại · giá in" ở danh sách mẫu: tóm tắt + chip kiểu + bảng gộp phân loại × nhựa (khoảng giá giữa các kiểu)
     + <details> giá từng kiểu. Nhận $m, $bangGia từ view cha. Dữ liệu: PodSo::bangPhanLoai(). --}}
@php
  $bp = \App\Services\PodSo::bangPhanLoai($m, $bangGia);
  $nk = $bp['so_kieu'];
  $dd = fn ($x) => number_format($x, 0, ',', '.');
  $gg = fn ($x) => rtrim(rtrim(number_format($x, 1, ',', '.'), '0'), ',');
  $kh = fn ($a, $b, $f) => $f($a) === $f($b) ? $f($a) : $f($a) . '–' . $f($b);
  $tenK = fn ($t) => $t ?? 'Chưa gán kiểu';
  $gon = $bp['so'] > 0 && $nk <= 1 && count($bp['gop']) === 1 && count($bp['nhua']) === 1;
@endphp
@if($bp['so'] === 0)
  <span class="muted">Chưa có phân loại, seller chưa đặt được</span>
@else
  <div class="pl-tom">
    @if($nk > 1)<b>{{ $nk }} kiểu</b> × @endif<b>{{ count($bp['gop']) }} phân loại</b> × {{ implode(', ', array_keys($bp['nhua'])) }}
    <span class="muted">= {{ $bp['so'] }}</span> · <b>{{ $kh($bp['gia'][0], $bp['gia'][1], $dd) }}đ</b>
    @if($m->gia_gram)<span class="badge b-gia">Đơn giá riêng {{ $dd($m->gia_gram) }}đ/g</span>@endif
    @if($m->tang_gia)<span class="badge b-gia">+{{ $m->tang_gia }} %</span>@endif
    @if($m->phi_ca_nhan)<span class="badge b-gia">Cá nhân hoá +{{ $dd($m->phi_ca_nhan) }}đ/dòng</span>@endif
    @if($bp['chua_kieu'])<span class="badge b-loi">Có phân loại chưa gán kiểu</span>@endif
    @if($bp['trung'])<span class="badge b-loi">{{ $bp['trung'] }} phân loại trùng</span>@endif
  </div>
  @if($nk > 1)
  <div class="pl-kieu">@foreach($bp['chi_tiet'] as $ct)<span class="chip">{{ $tenK($ct['ten']) }}</span>@endforeach</div>
  @endif
  @if($gon)
    @foreach($bp['gop'] as $ten => $r)
    <div class="pl-mot">{{ $ten }} · {{ $gg($r['gram'][0]) }} g · {{ array_key_first($bp['nhua']) }} {{ $dd(reset($bp['nhua'])) }}đ/g</div>
    @endforeach
  @else
  <div class="pl-cuon"><table class="pl-bang">
    <tr><th>Phân loại</th><th class="num">Gram</th>@foreach($bp['nhua'] as $cl => $dg)<th class="num">{{ $cl }} · {{ $dd($dg) }}đ/g</th>@endforeach</tr>
    @foreach($bp['gop'] as $ten => $r)
    <tr><td>{{ $ten }}</td><td class="num muted">{{ $kh($r['gram'][0], $r['gram'][1], $gg) }} g</td>
      @foreach($bp['nhua'] as $cl => $dg)
      <td class="num">@isset($r['o'][$cl])<b>{{ $kh($r['o'][$cl]['min'], $r['o'][$cl]['max'], $dd) }}đ</b>@if($nk > 1 && $r['o'][$cl]['so_kieu'] < $nk) <span class="muted">{{ $r['o'][$cl]['so_kieu'] }}/{{ $nk }} kiểu</span>@endif @else<span class="muted">—</span>@endisset</td>
      @endforeach
    </tr>
    @endforeach
  </table></div>
  @endif
  @if($nk > 1)
  <details class="pl-ct"><summary>Giá từng kiểu ({{ $bp['so'] }})</summary><div class="pl-cuon"><table class="pl-bang">
    <tr><th>Kiểu</th><th>Phân loại</th><th class="num">Gram</th>@foreach($bp['nhua'] as $cl => $dg)<th class="num">{{ $cl }}</th>@endforeach</tr>
    @foreach($bp['chi_tiet'] as $ct)
      @foreach($ct['hang'] as $ten => $o)
      <tr @class(['dau-kieu' => $loop->first && !$loop->parent->first])>
        @if($loop->first)<td rowspan="{{ count($ct['hang']) }}"><b>{{ $tenK($ct['ten']) }}</b></td>@endif
        <td>{{ $ten }}</td><td class="num muted">{{ collect($o)->map(fn ($x) => $gg($x['gram']))->unique()->implode(' / ') }} g</td>
        @foreach($bp['nhua'] as $cl => $dg)<td class="num">{{ isset($o[$cl]) ? $dd($o[$cl]['gia']) . 'đ' : '—' }}</td>@endforeach
      </tr>
      @endforeach
    @endforeach
  </table></div></details>
  @endif
@endif
@if($bp['cu'])
  <div class="pl-cu"><span class="badge b-off">{{ count($bp['cu']) }} phân loại cũ</span> còn đơn tham chiếu:
    {{ collect($bp['cu'])->map(fn ($x) => $x['ten'] . ' · ' . $x['nhua'] . ' — ' . $gg($x['gram']) . ' g = ' . $dd($x['gia']) . 'đ')->implode('; ') }}</div>
@endif
