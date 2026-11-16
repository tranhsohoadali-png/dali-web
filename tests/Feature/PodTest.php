<?php

namespace Tests\Feature;

use App\Models\PodBienThe;
use App\Models\PodDon;
use App\Models\PodMau;
use App\Models\PodNhanVien;
use App\Models\PodNhatKy;
use App\Models\PodNoiGiao;
use App\Models\PodPhien;
use App\Models\PodSeller;
use App\Models\PodThoIn;
use App\Services\PodSo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Cổng seller POD. Nhóm "giu_kin" là cam kết với seller: seller B không bao giờ
 * thấy/mở/đặt được mẫu riêng của seller A — ở danh sách, chi tiết, link ảnh, đặt đơn.
 * Chạy trước mỗi lần push: php artisan test --filter=PodTest
 */
class PodTest extends TestCase
{
    use RefreshDatabase;

    private PodSeller $a;
    private PodSeller $b;
    private PodMau $chung;
    private PodMau $riengA;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        $this->a = $this->taoSeller('Seller A', '0900000001');
        $this->b = $this->taoSeller('Seller B', '0900000002');
        $this->chung  = $this->taoMau('Khuôn bánh Eid', null, 18.8);
        $this->riengA = $this->taoMau('Mẫu độc quyền của A', $this->a->id, 12.0);
    }

    private function taoSeller(string $ten, string $sdt): PodSeller
    {
        return PodSeller::create([
            'ma' => PodSeller::taoMa(), 'ten' => $ten, 'sdt' => $sdt,
            'matkhau' => Hash::make('matkhau1'), 'hien' => true, 'dong_y_luc' => now(),
        ]);
    }

    /** Mặc định PLA (1.300đ/g) để các con số tiền trong phép thử cũ giữ nguyên. */
    private function taoMau(string $ten, ?int $chu, float $gram, string $chatLieu = 'PLA'): PodMau
    {
        $ma = PodMau::taoMa();
        $rel = "pod/anh/$ma-1.png";
        Storage::disk('local')->put($rel, 'png');
        $m = PodMau::create(['ma' => $ma, 'ten' => $ten, 'chu_seller_id' => $chu, 'hien' => true, 'anh' => [$rel], 'phi_ca_nhan' => 5000]);
        PodBienThe::create(['mau_id' => $m->id, 'ten' => 'Cỡ gốc', 'gram' => $gram, 'chat_lieu' => $chatLieu]);
        return $m;
    }

    public function test_mau_khac_ten_bat_buoc_ten_va_co_xem_truoc(): void
    {
        $orn = $this->taoMau('Ornament khắc tên', null, 9.0, 'PETG');
        $orn->update(['xem_truoc' => ['loai' => 'ten', 'font' => 'Pacifico', 'toi_da' => 8, 'w' => 90, 'h' => 104,
            'khung' => 'M0 0 L90 0 L90 104 L0 104Z', 'o_ten' => ['cx' => 45, 'cy' => 55, 'w' => 70, 'h' => 20, 'goc' => -10]]]);
        $bt = $this->bt($orn);
        foreach ([['', 'nhập tên'], ['Alexandria', 'tối đa 8']] as [$chu, $loi]) {
            try {
                PodSo::datDon($this->a, [['bien_the_id' => $bt, 'so_luong' => 1, 'chu' => $chu]], null, null, null, null);
                $this->fail('Phải báo lỗi tên: ' . $loi);
            } catch (\Illuminate\Validation\ValidationException $e) {
                $this->assertStringContainsString($loi, collect($e->errors())->flatten()->first());
            }
        }
        $don = PodSo::datDon($this->a, [['bien_the_id' => $bt, 'so_luong' => 1, 'chu' => 'Emma']], null, null, null, null);
        $this->assertSame('Emma', $don->dong()->first()->chu_ca_nhan);
        $h = $this->token($this->a);
        $ds = collect($this->getJson('/api/pod/mau', $h)->json('mau'))->firstWhere('ma', $orn->ma);
        $this->assertTrue($ds['co_xem_truoc']);
        $this->assertNull($ds['xem_truoc'], 'Danh sách không kèm khung SVG (nặng)');
        $this->assertSame(8, $ds['ten_toi_da']);
        $this->assertSame('M0 0 L90 0 L90 104 L0 104Z', $this->getJson('/api/pod/mau/' . $orn->ma, $h)->json('mau.xem_truoc.khung'));
        // Trang công khai: có ô thử tên + canvas + giá in từng cỡ (9 g × 900 = 8.100đ)
        $this->get('http://' . config('pod.domain') . '/kho-mau/' . $orn->ma)->assertOk()
            ->assertSee('cvXt')->assertSee('Thử tên của bạn')->assertSee('8.100đ');
    }

    public function test_mot_san_pham_nhieu_kieu_chon_font(): void
    {
        $khung = fn ($ten, $toiDa) => ['ten' => $ten, 'toi_da' => $toiDa, 'w' => 90, 'h' => 104, 'khung' => 'M0 0 L90 0 L90 104 L0 104Z',
            'o_ten' => ['cx' => 45, 'cy' => 55, 'w' => 70, 'h' => 20, 'goc' => 0]];
        $m = PodMau::create(['ma' => 'ornkieu001', 'ten' => 'Ornament gộp', 'chu_seller_id' => null, 'hien' => true, 'anh' => [],
            'xem_truoc' => ['loai' => 'ten', 'font' => 'Pacifico', 'fonts' => ['Pacifico', 'Lobster'], 'toi_da' => 12,
                'kieu' => ['la' => $khung('Lá + chuông', 12), 'chan' => $khung('Chân thú cưng', 4)]]]);
        $la = PodBienThe::create(['mau_id' => $m->id, 'kieu' => 'la', 'ten' => '3 inch', 'gram' => 5, 'chat_lieu' => 'PETG'])->id;
        $chan = PodBienThe::create(['mau_id' => $m->id, 'kieu' => 'chan', 'ten' => '3 inch', 'gram' => 6, 'chat_lieu' => 'PETG'])->id;
        foreach ([[$chan, 'Alexa', null, 'tối đa 4'], [$la, 'Emma', 'Comic Sans', 'font có sẵn']] as [$bt, $chu, $font, $loi]) {
            try {
                PodSo::datDon($this->a, [['bien_the_id' => $bt, 'so_luong' => 1, 'chu' => $chu, 'font' => $font]], null, null, null, null);
                $this->fail('Phải báo lỗi: ' . $loi);
            } catch (\Illuminate\Validation\ValidationException $e) {
                $this->assertStringContainsString($loi, collect($e->errors())->flatten()->first());
            }
        }
        $don = PodSo::datDon($this->a, [
            ['bien_the_id' => $la, 'so_luong' => 1, 'chu' => 'Alexandra', 'font' => 'Lobster'],
            ['bien_the_id' => $chan, 'so_luong' => 1, 'chu' => 'Max'],                      // bỏ trống font -> font mặc định
        ], null, null, null, null);
        $dong = $don->dong()->orderBy('id')->get();
        $this->assertSame(['Lá + chuông · 3 inch', 'Chân thú cưng · 3 inch'], $dong->pluck('ten_bien_the')->all());
        $this->assertSame(['Lobster', 'Pacifico'], $dong->pluck('font')->all());
        $h = $this->token($this->a);
        $this->assertSame(['la', 'chan'], collect($this->getJson('/api/pod/mau/' . $m->ma, $h)->json('mau.bien_the'))->pluck('kieu')->all());
        $this->assertSame('Lobster', collect($this->getJson('/api/pod/don', $h)->json('don.0.dong'))->firstWhere('chu', 'Alexandra')['font'] ?? null);
        // Trang công khai: chip kiểu + chip font, bảng giá từng kiểu (kiểu sau ẩn chờ chọn)
        $html = $this->get('http://' . config('pod.domain') . '/kho-mau/' . $m->ma)->assertOk()
            ->assertSee('id="kieuXt"', false)->assertSee('Chân thú cưng')->assertSee('data-font="Lobster"', false)->getContent();
        $this->assertMatchesRegularExpression('/<table class="bang gia-co" data-kieu="chan"\s+hidden/', $html);
        $this->assertDoesNotMatchRegularExpression('/<table class="bang gia-co" data-kieu="la"\s+hidden/', $html);
    }

    public function test_moc_khoa_ten_khong_khung_co_dinh_phoi_mau_hai_mau(): void
    {
        $m = PodMau::create(['ma' => 'mockhoa001', 'ten' => 'Móc khoá tên', 'chu_seller_id' => null, 'hien' => true, 'anh' => [],
            'xem_truoc' => ['loai' => 'ten', 've' => 'moc_khoa', 'font' => 'Pacifico', 'fonts' => ['Pacifico', 'Lobster'], 'toi_da' => 10,
                'cao_hoa' => 16, 'vien' => 2.45, 'lo_r' => 2.25, 'vanh' => 2.4, 'khe_lo' => 2,
                'mu' => ['ti_le' => 0.9448, 'than' => 'M0 0 L5 0 L5 5Z', 'vien' => 'M0 5 L5 5 L5 6Z', 'khung' => [0, 0, 5, 6]]]]);
        $bt = PodBienThe::create(['mau_id' => $m->id, 'ten' => 'Thường', 'gram' => 6.6, 'chat_lieu' => 'PETG',
            'mau_sac' => 'Đỏ + chữ trắng, Trắng + chữ đỏ'])->id;
        $this->assertSame(10, $m->tenToiDa());
        try {
            PodSo::datDon($this->a, [['bien_the_id' => $bt, 'so_luong' => 1, 'chu' => 'Wilhelmina1', 'mau_sac' => 'Đỏ + chữ trắng']], null, null, null, null);
            $this->fail('Tên 11 ký tự phải bị chặn');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertStringContainsString('tối đa 10', collect($e->errors())->flatten()->first());
        }
        $dong = PodSo::datDon($this->a, [['bien_the_id' => $bt, 'so_luong' => 2, 'chu' => 'Sofia', 'mau_sac' => 'Trắng + chữ đỏ']],
            null, null, null, null)->dong()->first();
        $this->assertSame(['Trắng + chữ đỏ', 'Pacifico', 11880], [$dong->mau_sac, $dong->font, (int) $dong->thanh_tien]); // 6,6 g × 2 × 900
        // Trang công khai: không có khung w/h -> canvas cao mặc định, móc khoá tự đặt chiều cao khi vẽ
        $this->get('http://' . config('pod.domain') . '/kho-mau/' . $m->ma)->assertOk()
            ->assertSee('height="520"', false)->assertSee('data-mau="Trắng + chữ đỏ"', false)->assertSee('5.940đ');
    }

    public function test_chu_duoi_dai_chuan_hoa_va_chan_chu_khong_in_duoc(): void
    {
        $g = fn (float $w) => ['d' => 'M0 0Z', 'w' => $w];
        $glyph = array_fill_keys(array_merge(range('A', 'Z'), range(0, 9), ['-']), $g(2.4));
        $glyph['W'] = $g(3.6);
        $m = $this->taoMau('Móc khóa Karate', null, 19.8);
        $m->update(['xem_truoc' => ['loai' => 'ten', 've' => 'dai', 'toi_da' => 10, 'w' => 900, 'h' => 1200, 'glyph' => $glyph,
            'so' => ['h' => 3.55, 'no_x' => .05, 'no_y' => .14, 'khe' => .45, 'khoang' => 1.1, 'l_chuan' => 13.2, 'l_max' => 14.5, 'lech_u' => .4, 'nen_min' => .38]]]);
        $m = $m->fresh();
        $this->assertTrue($m->laDai());
        $this->assertSame('SOPHIA / 2026', $m->chuanDai('sophia / 2026'));
        $this->assertSame('JOSE DUC', $m->chuanDai(' josé   đức '));
        $this->assertSame('ALEXANDER / SENSEI', $m->chuanDai('Alexander/Sensei'));
        foreach (['', 'AN@', 'ABCDEFGHIJK', 'WWWWWWWWWW', 'AN / 20@6'] as $sai) {
            try {
                $m->chuanDai($sai);
                $this->fail("Phải từ chối: $sai");
            } catch (\InvalidArgumentException $e) {
                $this->assertNotSame('', $e->getMessage());
            }
        }
        // chữ vẽ tay: chữ M co không quá wmin 1,4 -> 8 chữ M lọt công thức nén (sx 0,55) nhưng tổng tối thiểu dài quá đuôi
        $xt = $m->xem_truoc;
        $xt['glyph']['M']['wmin'] = 1.4;
        $m->update(['xem_truoc' => $xt]);
        $m = $m->fresh();
        $this->assertSame('MMMMMM', $m->chuanDai('mmmmmm'));
        try {
            $m->chuanDai('MMMMMMMM');
            $this->fail('Phải từ chối 8 chữ M');
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString('dài quá', $e->getMessage());
        }
        // đặt đơn: máy chủ lưu chữ đã chuẩn hoá, chữ không in được bị chặn dù trình duyệt lọt
        $bt = $this->bt($m);
        try {
            PodSo::datDon($this->a, [['bien_the_id' => $bt, 'so_luong' => 1, 'chu' => 'AN@']], null, null, null, null);
            $this->fail('Phải chặn chữ không in được');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertStringContainsString('chỉ in được', collect($e->errors())->flatten()->first());
        }
        $don = PodSo::datDon($this->a, [['bien_the_id' => $bt, 'so_luong' => 1, 'chu' => 'sophia / 2026']], null, null, null, null);
        $this->assertSame('SOPHIA / 2026', $don->dong()->first()->chu_ca_nhan);
        // trang công khai có 2 ô chữ; mẫu khắc tên thường không có ô thứ hai
        $this->get('http://' . config('pod.domain') . '/kho-mau/' . $m->ma)->assertOk()->assertSee('ten2Xt');
    }

    public function test_dau_banh_ten_in_hoa_giu_dau_kiem_cung_va_file_in(): void
    {
        // bảng bề rộng đều 0,62 em (thật: chép từ dau_banh.scad); số còn lại = xem_truoc mẫu dấu bánh thật
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZĐĂÂÊÔƠƯ0123456789 &'.,-!ÀÁẢÃẠẰẮẲẴẶẦẤẨẪẬÈÉẺẼẸỀẾỂỄỆÌÍỈĨỊÒÓỎÕỌỒỐỔỖỘỜỚỞỠỢÙÚỦŨỤỪỨỬỮỰỲÝỶỸỴ";
        $m = $this->taoMau('Dấu bánh quy Giáng sinh khắc tên', null, 35.56, 'PETG');
        $bt = $m->bienThe()->first();
        $bt->update(['ten' => 'Cả bộ · dấu Ø60 + khuôn']);
        $m->update(['xem_truoc' => ['loai' => 'ten', 've' => 'dau_banh', 'toi_da' => 24, 'w' => 150, 'h' => 100, 'hoa' => true,
            'chars' => $chars, 'adv' => array_fill(0, mb_strlen($chars), 0.62),
            'so' => ['r_ten' => 26.2, 'co' => 5.0, 'co_min' => 4.2, 'cung_max' => 150, 'cap' => 0.617, 'he_so_dan' => 0.98],
            'thay_ten' => ['mau' => 'dau_banh', 'khoa' => 'in_gi', 'co' => ['Cả bộ · dấu Ø60 + khuôn' => 'bo', 'Con dấu Ø60' => 'dau']]]]);
        $m = $m->fresh();
        $this->assertTrue($m->laDauBanh());
        $this->assertFalse($m->laDai());
        $this->assertSame('THE SMITHS', $m->chuanDauBanh('  the   smiths '));
        $this->assertSame('NGUYỄN HOÀNG YẾN', $m->chuanDauBanh('Nguyễn Hoàng Yến'), 'giữ dấu tiếng Việt');
        $this->assertEqualsWithDelta(100.0, $m->cungDauBanh('THE SMITHS'), 1.0);
        foreach (['', '...', 'AN@', 'XIN😀', 'NGUYỄN THỊ PHƯƠNG THẢO', str_repeat('A', 25)] as $sai) {
            try {
                $m->chuanDauBanh($sai);
                $this->fail("Phải từ chối: $sai");
            } catch (\InvalidArgumentException $e) {
                $this->assertNotSame('', $e->getMessage());
            }
        }
        // đặt đơn: lưu tên đã in hoa, tên dài quá cung bị chặn dù trình duyệt lọt
        try {
            PodSo::datDon($this->a, [['bien_the_id' => $bt->id, 'so_luong' => 1, 'chu' => 'Nguyễn Thị Phương Thảo']], null, null, null, null);
            $this->fail('Phải chặn tên dài quá cung');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertStringContainsString('cung dưới', collect($e->errors())->flatten()->first());
        }
        $dong = PodSo::datDon($this->a, [['bien_the_id' => $bt->id, 'so_luong' => 1, 'chu' => 'the smiths']], null, null, null, null)->dong()->first();
        $this->assertSame('THE SMITHS', $dong->chu_ca_nhan);
        // file in: tuỳ chọn in_gi theo phân loại, không kèm "nam" của mẫu đuôi đai
        $this->assertSame(['mau' => 'dau_banh', 'ten' => 'THE SMITHS', 'tuy_chon' => ['in_gi' => 'bo']],
            $m->thamSoFileIn($dong->ten_bien_the, $dong->chu_ca_nhan));
        $this->assertSame(['in_gi' => 'dau'], $m->thamSoFileIn('Con dấu Ø60', 'AN')['tuy_chon']);
        $this->assertNull($m->thamSoFileIn('Chỉ khuôn', 'AN'), 'Phân loại lạ: để thợ làm tay');
        // nhiều kiểu: phân loại "Kiểu · cỡ" -> bộ tuỳ chọn {kieu, in_gi}
        $xt = $m->xem_truoc;
        $xt['thay_ten']['co']['Happy Birthday · Con dấu Ø60'] = ['kieu' => 'sinh_nhat', 'in_gi' => 'dau'];
        $m->update(['xem_truoc' => $xt]);
        $this->assertSame(['mau' => 'dau_banh', 'ten' => 'EMMA', 'tuy_chon' => ['kieu' => 'sinh_nhat', 'in_gi' => 'dau']],
            $m->fresh()->thamSoFileIn('Happy Birthday · Con dấu Ø60', 'EMMA'));
        // trang công khai: ô tên tự in hoa + chỗ báo lỗi + font Baloo 2
        $this->get('http://' . config('pod.domain') . '/kho-mau/' . $m->ma)->assertOk()
            ->assertSee('id="loiXt"', false)->assertSee('text-transform:uppercase', false)->assertSee('Baloo+2', false);
    }

    public function test_thanh_ten_in_hoa_tieng_anh_dem_khong_tinh_cach_va_file_in(): void
    {
        // ornament cô gái Pilates: tên chữ thẳng trên thanh khung, app Thay tên mau_ornament_pilates (không tuỳ chọn)
        $m = $this->taoMau('Ornament cô gái Pilates khắc tên', null, 56.5, 'PLA');
        $bt = $m->bienThe()->first();
        $bt->update(['ten' => '4.7 in · trọn bộ']);
        $m->update(['xem_truoc' => ['loai' => 'ten', 've' => 'thanh', 'toi_da' => 14, 'toi_ky_tu' => 10, 'hoa' => true,
            'w' => 1049, 'h' => 910, 'nen' => 'data:image/jpeg;base64,AA==', 'mau_chu' => 'EMILIA',
            'o_ten' => ['cx' => 515, 'cy' => 758, 'cap' => 52.2, 'rong_max' => 850, 'vien' => 7.65, 'ty_doc' => 0.99],
            'thay_ten' => ['mau' => 'ornament_pilates', 'co' => ['4.7 in · trọn bộ' => []]]]]);
        $m = $m->fresh();
        $this->assertTrue($m->laThanh());
        $this->assertFalse($m->laDauBanh());
        $this->assertSame('MAI ANH', $m->chuanThanh('  mai   anh '));
        $this->assertSame("O'NEIL & CO", $m->chuanThanh("o'neil & co"));
        $this->assertSame('ANNE MARIE X', $m->chuanThanh('Anne Marie X'), '10 chữ + 2 dấu cách vẫn nhận');
        foreach (['', '...', 'NGỌC', 'EMMA@', 'ABCDEFGHIJK', 'XIN😀'] as $sai) {
            try {
                $m->chuanThanh($sai);
                $this->fail("Phải từ chối: $sai");
            } catch (\InvalidArgumentException $e) {
                $this->assertNotSame('', $e->getMessage());
            }
        }
        // đặt đơn: chặn tên có dấu dù trình duyệt lọt, lưu tên đã in hoa
        try {
            PodSo::datDon($this->a, [['bien_the_id' => $bt->id, 'so_luong' => 1, 'chu' => 'Ngọc']], null, null, null, null);
            $this->fail('Phải chặn tên có dấu');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertStringContainsString('chưa in được', collect($e->errors())->flatten()->first());
        }
        $donTh = PodSo::datDon($this->a, [['bien_the_id' => $bt->id, 'so_luong' => 1, 'chu' => 'emilia']], null, null, null, null);
        $dong = $donTh->dong()->first();
        $this->assertSame('EMILIA', $dong->chu_ca_nhan);
        // nút "Tạo + tải file in": mẫu KHÔNG tuỳ chọn phải gửi tuy_chon là object {} — list [] bị app từ chối (dict_type, lỗi 11-11)
        $this->withSession(['admin_logged_in' => true])->get(route('admin.pod.don.show', $donTh))->assertOk()
            ->assertSee('&quot;tuy_chon&quot;:{}', false)->assertDontSee('&quot;tuy_chon&quot;:[]', false);
        $this->assertSame(['mau' => 'ornament_pilates', 'ten' => 'EMILIA', 'tuy_chon' => []],
            $m->thamSoFileIn($dong->ten_bien_the, $dong->chu_ca_nhan));
        // trang công khai: ô tên tự in hoa + chỗ báo lỗi + câu luật tên
        $this->get('http://' . config('pod.domain') . '/kho-mau/' . $m->ma)->assertOk()
            ->assertSee('id="loiXt"', false)->assertSee('text-transform:uppercase', false)->assertSee('chỉ chữ tiếng Anh', false);
    }

    public function test_lot_ly_dung_bo_ten_cung_duoi_cua_dau_banh_voi_ten_mat_rieng(): void
    {
        // lót ly Giáng sinh: cùng bộ tên cung dưới (ve=dau_banh) nhưng câu báo gọi "lót ly", một phân loại không tuỳ chọn
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 &'.,-!ĐẤ";
        $m = $this->taoMau('Lót ly Giáng sinh khắc tên', null, 24.5, 'PETG');
        $bt = $m->bienThe()->first();
        $bt->update(['ten' => 'Ø90 · 4 màu']);
        $m->update(['xem_truoc' => ['loai' => 'ten', 've' => 'dau_banh', 'ten_mat' => 'lót ly', 'toi_da' => 24, 'w' => 100, 'h' => 100,
            'chars' => $chars, 'adv' => array_fill(0, mb_strlen($chars), 0.6), 'lop_ten' => [['#6e1010', 0.22], ['#f7f5f0', 0]],
            'so' => ['r_ten' => 37.4, 'co' => 5.76, 'co_min' => 4.61, 'cung_max' => 140, 'cap' => 0.625, 'he_so_dan' => 1.04,
                     'r_gach_max' => 0, 'r_gach_min' => 999],
            'thay_ten' => ['mau' => 'lot_ly', 'co' => ['Ø90 · 4 màu' => []]]]]);
        $m = $m->fresh();
        $this->assertSame('THE SMITHS', $m->chuanDauBanh('the smiths'));
        try {
            $m->chuanDauBanh('AN@');
            $this->fail('Phải từ chối ký tự lạ');
        } catch (\InvalidArgumentException $e) {
            $this->assertStringStartsWith('lót ly chưa in được ký tự @', $e->getMessage());
        }
        try {
            $m->chuanDauBanh(str_repeat('W', 24));
            $this->fail('Phải từ chối tên dài quá cung');
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString('cung dưới lót ly', $e->getMessage());
        }
        $dong = PodSo::datDon($this->a, [['bien_the_id' => $bt->id, 'so_luong' => 2, 'chu' => 'the smiths']], null, null, null, null)->dong()->first();
        $this->assertSame(['mau' => 'lot_ly', 'ten' => 'THE SMITHS', 'tuy_chon' => []], $m->thamSoFileIn($dong->ten_bien_the, $dong->chu_ca_nhan));
        $this->get('http://' . config('pod.domain') . '/kho-mau/' . $m->ma)->assertOk()->assertSee('lop_ten', false);
        // nhiều kiểu: Santa tên nằm trên băng rôn ngắn — số xếp tên riêng ở xem_truoc.kieu.santa, máy chủ kiểm theo kiểu phân loại
        $xt = $m->xem_truoc;
        $xt['kieu'] = ['santa' => ['ten' => 'Santa và tuần lộc',
            'so' => array_merge($xt['so'], ['r_ten' => 41.6, 'co' => 5.07, 'co_min' => 3.92, 'cung_max' => 86])]];
        $xt['thay_ten']['co']['Santa và tuần lộc · Ø90 mm'] = ['kieu' => 'santa'];
        $m->update(['xem_truoc' => $xt]);
        $m = $m->fresh();
        $dai = str_repeat('A', 18);   // 10,8 em: vừa đúng cung 140° của cây thông, dài quá băng Santa (86°)
        $this->assertSame($dai, $m->chuanDauBanh($dai));
        try {
            $m->chuanDauBanh($dai, 'santa');
            $this->fail('Phải từ chối tên dài quá băng Santa');
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString('cung dưới lót ly', $e->getMessage());
        }
        $this->assertSame('AN', $m->chuanDauBanh('an', 'khong_co'), 'kiểu lạ: dùng số cấp ngoài');
        $bs = PodBienThe::create(['mau_id' => $m->id, 'kieu' => 'santa', 'ten' => 'Santa và tuần lộc · Ø90 mm', 'chat_lieu' => 'PETG', 'gram' => 26.5,
            'mau_sac' => 'Đen + Santa đỏ trắng']);
        try {
            PodSo::datDon($this->a, [['bien_the_id' => $bs->id, 'so_luong' => 1, 'mau_sac' => 'Đen + Santa đỏ trắng', 'chu' => $dai]], null, null, null, null);
            $this->fail('Phải chặn tên dài quá băng Santa khi đặt đơn');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertStringContainsString('cung dưới lót ly', collect($e->errors())->flatten()->first());
        }
        PodSo::datDon($this->a, [['bien_the_id' => $bt->id, 'so_luong' => 1, 'chu' => $dai]], null, null, null, null);
        $this->assertSame(['mau' => 'lot_ly', 'ten' => 'AN', 'tuy_chon' => ['kieu' => 'santa']], $m->thamSoFileIn('Santa và tuần lộc · Ø90 mm', 'AN'));
        // trang công khai: màu nhựa gắn kiểu (đổi kiểu chỉ hiện màu của kiểu), ô báo lỗi kiểm theo số của kiểu đang chọn
        $this->get('http://' . config('pod.domain') . '/kho-mau/' . $m->ma)->assertOk()
            ->assertSee('data-mau="Đen + Santa đỏ trắng" data-kieu="santa"', false)->assertSee('PodVeTen.kiem(k, ten, co)', false);
    }

    public function test_trang_don_admin_co_nut_tao_file_in_dung_chu_va_co(): void
    {
        $m = $this->taoMau('Móc khóa Karate', null, 20.5);
        $bt = $m->bienThe()->first();
        $bt->update(['ten' => 'Dài 8 cm (3.1 inch)']);
        $glyph = array_fill_keys(array_merge(range('A', 'Z'), range(0, 9)), ['d' => 'M0 0Z', 'w' => 2.0]);
        $m->update(['xem_truoc' => ['loai' => 'ten', 've' => 'dai', 'toi_da' => 10, 'w' => 900, 'h' => 1200, 'glyph' => $glyph,
            'so' => ['h' => 3.55, 'no_x' => .05, 'no_y' => .14, 'khe' => .45, 'khoang' => 1.1, 'l_chuan' => 13.2, 'l_max' => 14.5, 'lech_u' => .4, 'nen_min' => .38],
            'thay_ten' => ['mau' => 'moc_khoa_karate', 'co' => ['Dài 7 cm (2.8 inch)' => '7', 'Dài 8 cm (3.1 inch)' => '8']]]]);
        $m = $m->fresh();
        $this->assertSame(['mau' => 'moc_khoa_karate', 'ten' => 'SOPHIA', 'tuy_chon' => ['kich_thuoc' => '8', 'nam' => '2026']],
            $m->thamSoFileIn('Dài 8 cm (3.1 inch)', 'SOPHIA / 2026'));
        $this->assertSame('', $m->thamSoFileIn('Dài 7 cm (2.8 inch)', 'MIA')['tuy_chon']['nam'], 'Bỏ trống đuôi trái');
        $this->assertNull($m->thamSoFileIn('Cỡ đã đổi tên', 'SOPHIA / 2026'), 'Cỡ lạ: không đoán, để thợ làm tay');
        $this->assertNull($this->chung->thamSoFileIn('Cỡ gốc', 'EMMA'), 'Mẫu chưa nối app');
        // màu đai: dòng đơn chọn màu nào thì file in đúng màu đó, màu lạ không đoán
        $xt = $m->xem_truoc;
        $xt['thay_ten']['mau_dai'] = ['Áo trắng đai đỏ' => 'do', 'Áo trắng đai đen' => 'den'];
        $m->update(['xem_truoc' => $xt]);
        $bt->update(['mau_sac' => 'Áo trắng đai đỏ, Áo trắng đai đen']);
        $m = $m->fresh();
        $this->assertSame(['kich_thuoc' => '8', 'nam' => '2026', 'mau_dai' => 'den'],
            $m->thamSoFileIn('Dài 8 cm (3.1 inch)', 'SOPHIA / 2026', 'Áo trắng đai đen')['tuy_chon']);
        $this->assertSame('do', $m->thamSoFileIn('Dài 8 cm (3.1 inch)', 'SOPHIA / 2026', 'Áo trắng đai đỏ')['tuy_chon']['mau_dai']);
        $this->assertNull($m->thamSoFileIn('Dài 8 cm (3.1 inch)', 'SOPHIA / 2026', 'Áo trắng đai xanh'), 'Màu lạ: để thợ làm tay');
        $this->assertNull($m->thamSoFileIn('Dài 8 cm (3.1 inch)', 'SOPHIA / 2026'), 'Thiếu màu: để thợ làm tay');
        $donDen = PodSo::datDon($this->a, [['bien_the_id' => $bt->id, 'so_luong' => 1, 'chu' => 'mia / 2026', 'mau_sac' => 'Áo trắng đai đen']], null, null, null, null);
        $this->withSession(['admin_logged_in' => true])->get(route('admin.pod.don.show', $donDen))->assertOk()->assertSee('&quot;mau_dai&quot;:&quot;den&quot;', false);
        $don = PodSo::datDon($this->a, [
            ['bien_the_id' => $bt->id, 'so_luong' => 3, 'chu' => 'rabful / 2026', 'mau_sac' => 'Áo trắng đai đỏ'],
            ['bien_the_id' => $this->bt($this->chung), 'so_luong' => 1],
        ], null, null, null, null);
        $trang = $this->withSession(['admin_logged_in' => true])->get(route('admin.pod.don.show', $don))->assertOk();
        $trang->assertSee('Tạo + tải file in')->assertSee('File 1 chiếc, đơn cần 3 chiếc.')->assertSee('RABFUL', false);
        $this->assertSame(1, substr_count($trang->getContent(), 'class="tao-file"'), 'Dòng mẫu chưa nối app không có nút');
        // đơn không có dòng nào nối app: không chèn script gọi app
        $don2 = PodSo::datDon($this->a, [['bien_the_id' => $this->bt($this->chung), 'so_luong' => 1]], null, null, null, null);
        $this->withSession(['admin_logged_in' => true])->get(route('admin.pod.don.show', $don2))->assertOk()->assertDontSee('thay-ten/app', false);
    }

    public function test_anh_kich_thuoc_theo_co_va_hinh_chieu_giu_kin(): void
    {
        $dom = 'http://' . config('pod.domain');
        // mẫu khối Kho chung: số đo từng cỡ + 2 hình chiếu
        $m = $this->taoMau('Hộp kẹo Noel', null, 150.0, 'PETG');
        $m->bienThe()->first()->update(['ten' => '6 inch', 'kich_thuoc' => ['rong' => 98.2, 'cao' => 152.4, 'day' => 144.1]]);
        foreach (['truoc', 'ben'] as $mat) Storage::disk('local')->put("pod/anh/{$m->ma}-kt-$mat.png", 'png');
        $m->update(['kich_thuoc' => ['truoc' => "pod/anh/{$m->ma}-kt-truoc.png", 'ben' => "pod/anh/{$m->ma}-kt-ben.png"]]);
        $this->assertTrue($m->fresh()->coKichThuoc());
        $this->assertFalse($this->chung->fresh()->coKichThuoc());                  // chưa có số đo -> không vẽ

        $h = $this->token($this->a);
        $ds = collect($this->getJson('/api/pod/mau', $h)->json('mau'))->firstWhere('ma', $m->ma);
        $this->assertTrue($ds['co_kich_thuoc']);
        $this->assertNull($ds['hinh_chieu']);                                     // danh sách không kèm link ảnh
        $ct = $this->getJson('/api/pod/mau/' . $m->ma, $h)->json('mau');
        $this->assertSame(152.4, $ct['bien_the'][0]['kich_thuoc']['cao']);
        $this->get($ct['hinh_chieu']['ben'])->assertOk();
        $this->get($dom . '/anh-kt/' . $m->ma . '/truoc')->assertOk();
        $this->get($dom . '/kho-mau/' . $m->ma)->assertOk()->assertSee('id="cvKt"', false)->assertSee('data-co="6 inch"', false)
            ->assertDontSee('Số có dấu ~');
        // link ảnh kèm ?v= thời điểm sửa mẫu: đổi ảnh bìa thì trình duyệt tải ảnh mới, không giữ ảnh cũ 24 giờ
        $this->get($dom . '/kho-mau')->assertOk()->assertSee($m->fresh()->urlAnh(), false);
        $this->assertStringStartsWith('/anh-mau/' . $m->ma . '/0?v=', $m->urlAnh());

        // mẫu riêng của A: seller B và khách vãng lai không lấy được hình chiếu, kể cả cầm link có chữ ký của A
        $this->riengA->bienThe()->first()->update(['kich_thuoc' => ['rong' => 50, 'cao' => 60, 'day' => 40]]);
        Storage::disk('local')->put('pod/anh/rieng-kt.png', 'png');
        $this->riengA->update(['kich_thuoc' => ['truoc' => 'pod/anh/rieng-kt.png']]);
        $link = $this->getJson('/api/pod/mau/' . $this->riengA->ma, $h)->json('mau.hinh_chieu.truoc');
        $this->get($link)->assertOk();
        $this->get(str_replace('s=' . $this->a->id, 's=' . $this->b->id, $link))->assertForbidden();   // sửa link -> sai chữ ký
        $this->get($dom . '/anh-kt/' . $this->riengA->ma . '/truoc')->assertNotFound();
        $this->get($dom . '/anh-kt/' . $m->ma . '/hong')->assertNotFound();

        // admin nhập "R × C × D" mm; sai dạng bị chặn; ô trống giữ kiểu "đo theo tên" của móc khoá
        $admin = $this->withSession(['admin_logged_in' => true]);
        $bt = $this->bt($m);
        $form = ['ten' => $m->ten, 'chu_seller_id' => '', 'hien' => '1', 'bt' => [['id' => $bt, 'ten' => '6 inch', 'gram' => 150, 'kt' => '98,2 × 152,4 × 144,1']]];
        $admin->put('/admin/3d/pod/mau/' . $m->id, $form)->assertSessionHasNoErrors();
        $this->assertSame(['rong' => 98.2, 'cao' => 152.4, 'day' => 144.1], PodBienThe::find($bt)->kich_thuoc);
        $form['bt'][0]['kt'] = '98 x 152';
        $admin->put('/admin/3d/pod/mau/' . $m->id, $form)->assertSessionHasErrors('bt.0.kt');
        PodBienThe::whereKey($bt)->update(['kich_thuoc' => json_encode(['ti_le' => 1.3, 'day' => 3.6])]);
        $form['bt'][0]['kt'] = '';
        $admin->put('/admin/3d/pod/mau/' . $m->id, $form)->assertSessionHasNoErrors();
        $this->assertSame(['ti_le' => 1.3, 'day' => 3.6], PodBienThe::find($bt)->kich_thuoc);
    }

    public function test_mau_khoi_nhieu_kieu_moi_kieu_anh_rieng(): void
    {
        $dom = 'http://' . config('pod.domain');
        $m = PodMau::create(['ma' => 'stuffer001', 'ten' => 'Stuffer Buddy', 'chu_seller_id' => null, 'hien' => true, 'anh' => []]);
        $kt = ['kieu' => []];
        foreach (['yeti' => 'Yeti', 'elf' => 'Elf'] as $k => $ten) {
            foreach (['truoc', 'ben', 'bia'] as $mat) Storage::disk('local')->put("pod/anh/stuffer001-$mat-$k.png", 'png');
            $kt['kieu'][$k] = ['ten' => $ten, 'truoc' => "pod/anh/stuffer001-truoc-$k.png", 'ben' => "pod/anh/stuffer001-ben-$k.png",
                               'bia' => "pod/anh/stuffer001-bia-$k.png"];
            PodBienThe::create(['mau_id' => $m->id, 'kieu' => $k, 'ten' => '6 inch', 'gram' => 200, 'chat_lieu' => 'PETG',
                                'kich_thuoc' => ['rong' => 100, 'cao' => 152.4, 'day' => 140]]);
        }
        $m->update(['kich_thuoc' => $kt]);
        $m = $m->fresh();
        $this->assertSame(['yeti', 'elf'], $m->kieuCoAnh());
        $this->assertSame('Elf', $m->tenKieu('elf'));
        $this->assertTrue($m->coKichThuoc());

        $h = $this->token($this->a);
        $ct = $this->getJson('/api/pod/mau/' . $m->ma, $h)->json('mau');
        $this->assertSame(['yeti' => 'Yeti', 'elf' => 'Elf'], $ct['ten_kieu']);
        $this->get($ct['hinh_chieu']['kieu']['elf']['bia'])->assertOk();
        $this->get($ct['hinh_chieu']['kieu']['yeti']['truoc'])->assertOk();
        $this->get($dom . '/anh-kt/' . $m->ma . '/bia/elf')->assertOk();
        $this->get($dom . '/anh-kt/' . $m->ma . '/bia/khong-co')->assertNotFound();
        // khoá kiểu có gạch dưới (lót ly: cay_thong, nguoi_tuyet) — route từng chỉ nhận [a-z0-9-] nên ảnh 404
        Storage::disk('local')->put('pod/anh/stuffer001-truoc-nguoi_tuyet.png', 'png');
        $kt2 = $m->kich_thuoc; $kt2['kieu']['nguoi_tuyet'] = ['truoc' => 'pod/anh/stuffer001-truoc-nguoi_tuyet.png'];
        $m->update(['kich_thuoc' => $kt2]);
        $this->get($dom . '/anh-kt/' . $m->ma . '/truoc/nguoi_tuyet')->assertOk();
        $this->get($dom . '/anh-kt/' . $m->ma . '/ben/nguoi_tuyet')->assertNotFound();   // kiểu không có 'ben', cấp ngoài cũng không
        $m->update(['kich_thuoc' => $kt]);
        $this->get($dom . '/kho-mau/' . $m->ma)->assertOk()->assertSee('id="kieuXt"', false)->assertSee('/anh-kt/stuffer001/bia/yeti', false);
        // dòng đơn chụp tên kiểu lấy từ kich_thuoc
        $dong = PodSo::datDon($this->a, [['bien_the_id' => PodBienThe::where('mau_id', $m->id)->where('kieu', 'elf')->value('id'), 'so_luong' => 1]],
            null, null, null, null)->dong()->first();
        $this->assertSame('Elf · 6 inch', $dong->ten_bien_the);
    }

    public function test_tang_gia_rieng_mot_mau(): void
    {
        $m = $this->taoMau('Móc khoá tăng giá', null, 10.0, 'PETG');
        $m->update(['tang_gia' => 20]);
        PodBienThe::create(['mau_id' => $m->id, 'ten' => 'Mặc định', 'gram' => 10.0, 'chat_lieu' => 'PLA']);
        $m->load('bienThe');
        $this->assertSame([1080, 1560], [PodSo::donGia($m, 'PETG'), PodSo::donGia($m, 'PLA')]);
        $this->assertSame(10800, PodSo::giaTu($m));                                   // 10 g × 900 × 1,2
        $dong = PodSo::datDon($this->a, [['bien_the_id' => $this->bt($m), 'so_luong' => 2]], null, null, null, null)->dong()->first();
        $this->assertSame([1080, 21600], [(int) $dong->don_gia_gram, (int) $dong->thanh_tien]); // dòng đơn khớp gram × đơn giá mẫu
        $gia = collect($this->getJson('/api/pod/mau/' . $m->ma, $this->token($this->a))->json('mau.bien_the'))->pluck('gia')->all();
        $this->assertSame([10800, 15600], $gia);
        $this->get('http://' . config('pod.domain') . '/kho-mau/' . $m->ma)->assertOk()->assertSee('10.800đ')->assertSee('15.600đ');
        // mẫu khác không bị ảnh hưởng
        $this->assertSame(1300, PodSo::donGia($this->chung, 'PLA'));
    }

    public function test_don_gia_rieng_mot_mau_500d_gram(): void
    {
        $m = $this->taoMau('Book nook Gothic', null, 100.0, 'PETG');
        $m->update(['gia_gram' => 500]);
        PodBienThe::create(['mau_id' => $m->id, 'ten' => 'PLA', 'gram' => 100.0, 'chat_lieu' => 'PLA']);
        $m->load('bienThe');
        $this->assertSame([500, 500], [PodSo::donGia($m, 'PETG'), PodSo::donGia($m, 'PLA')]);   // thay giá nhựa, mọi nhựa
        $dong = PodSo::datDon($this->a, [['bien_the_id' => $this->bt($m), 'so_luong' => 3]], null, null, null, null)->dong()->first();
        $this->assertSame([500, 150000], [(int) $dong->don_gia_gram, (int) $dong->thanh_tien]);
        $this->assertSame([50000, 50000], collect($this->getJson('/api/pod/mau/' . $m->ma, $this->token($this->a))->json('mau.bien_the'))->pluck('gia')->all());
        $m->update(['tang_gia' => 20]);                                                          // tăng giá cộng trên đơn giá riêng
        $this->assertSame(600, PodSo::donGia($m->fresh(), 'PLA'));
        // admin nhập / xoá đơn giá riêng
        $admin = $this->withSession(['admin_logged_in' => true]);
        $form = ['ten' => $m->ten, 'chu_seller_id' => '', 'hien' => '1', 'gia_gram' => '450', 'bt' => [['id' => $this->bt($m), 'ten' => 'Cỡ gốc', 'gram' => 100]]];
        $admin->put('/admin/3d/pod/mau/' . $m->id, $form)->assertSessionHasNoErrors();
        $this->assertSame(450, (int) $m->fresh()->gia_gram);
        $admin->put('/admin/3d/pod/mau/' . $m->id, ['gia_gram' => ''] + $form)->assertSessionHasNoErrors();
        $this->assertNull($m->fresh()->gia_gram);
        $this->assertSame(900, PodSo::donGia($m->fresh(), 'PETG'));
        // ô "File in" dài (script đăng mẫu ghi hướng dẫn ~1.000 ký tự, vd ornament Pilates) vẫn lưu được form
        $dai = str_repeat('MỖI ĐƠN: bấm "Tạo file in" rồi in A1 PETG. ', 25);           // ~1.050 ký tự
        $form['bt'][0]['file_in'] = $dai;
        $admin->put('/admin/3d/pod/mau/' . $m->id, $form)->assertSessionHasNoErrors();
        $this->assertSame(trim($dai), PodBienThe::find($this->bt($m))->file_in);
        $form['bt'][0]['file_in'] = str_repeat('x', 2001);
        $admin->put('/admin/3d/pod/mau/' . $m->id, $form)->assertSessionHasErrors(['bt.0.file_in' => 'Ô "File in" của một phân loại dài quá 2.000 ký tự.']);
    }

    public function test_admin_danh_sach_mau_gop_kieu_phan_loai_nhua(): void
    {
        $ma = PodMau::taoMa();
        $m = PodMau::create(['ma' => $ma, 'ten' => 'Dấu nhiều kiểu', 'hien' => true, 'anh' => [], 'tang_gia' => 20,
            'kich_thuoc' => ['kieu' => ['a' => ['ten' => 'Kiểu Một'], 'b' => ['ten' => 'Kiểu Hai'], 'c' => ['ten' => 'Kiểu Ba']]]]);
        $tt = 0;
        foreach (['a' => 35.4, 'b' => 35.6, 'c' => 35.5] as $k => $g) {
            foreach (['Cả bộ' => $g, 'Con dấu' => $g - 9] as $ten => $gram) {
                foreach (['PLA', 'PETG'] as $cl) { // PLA tạo trước: cột vẫn phải PETG trước
                    PodBienThe::create(['mau_id' => $m->id, 'kieu' => $k, 'ten' => $ten, 'gram' => $gram, 'chat_lieu' => $cl, 'thu_tu' => $tt++]);
                }
            }
        }
        PodBienThe::create(['mau_id' => $m->id, 'ten' => 'Cỡ cũ', 'gram' => 500, 'chat_lieu' => 'PETG', 'thu_tu' => PodBienThe::THU_TU_CU]);
        PodMau::create(['ma' => PodMau::taoMa(), 'ten' => 'Mẫu trống', 'hien' => false, 'anh' => []]);

        $bp = PodSo::bangPhanLoai($m->fresh('bienThe'));
        $this->assertSame([12, 3, false, 0], [$bp['so'], $bp['so_kieu'], $bp['chua_kieu'], $bp['trung']]);
        $this->assertSame(['PETG' => 1080, 'PLA' => 1560], $bp['nhua']);
        $this->assertSame(['Cả bộ', 'Con dấu'], array_keys($bp['gop']));
        $this->assertSame([38232, 38448, 3], [$bp['gop']['Cả bộ']['o']['PETG']['min'], $bp['gop']['Cả bộ']['o']['PETG']['max'], $bp['gop']['Cả bộ']['o']['PETG']['so_kieu']]);
        $this->assertSame(['Kiểu Một', 'Kiểu Hai', 'Kiểu Ba'], array_column($bp['chi_tiet'], 'ten'));
        $this->assertSame([28512, 55536], $bp['gia']);                // phân loại cũ 500 g không lọt vào khoảng giá
        $this->assertCount(1, $bp['cu']);
        $this->assertSame(540000, $bp['cu'][0]['gia']);

        $html = $this->withSession(['admin_logged_in' => true])->get('/admin/3d/pod/mau')->assertOk()
            ->assertSeeInOrder(['3 kiểu', '2 phân loại', 'PETG, PLA', '28.512–55.536đ', '+20 %', 'Kiểu Một', 'Kiểu Hai', 'Kiểu Ba',
                'PETG · 1.080đ/g', 'PLA · 1.560đ/g', '38.232–38.448đ', 'Giá từng kiểu (12)', '1 phân loại cũ', 'Cỡ cũ'])
            ->assertSee('rowspan="2"', false)->assertSee('Chưa có phân loại')
            ->assertSee('Cỡ gốc · 18,8 g · PLA 1.300đ/g')                 // mẫu 1 phân loại: một dòng gọn
            ->assertDontSee('@endif', false)->assertDontSee('@else', false)->assertDontSee('@isset', false)
            ->getContent();
        $this->assertSame(4, substr_count($html, '<td>Cả bộ</td>'));      // 1 dòng gộp + 3 dòng từng kiểu, không lặp theo nhựa
        $this->assertSame(1, substr_count($html, 'Giá từng kiểu'));      // mẫu một kiểu không có phần mở rộng
    }

    public function test_admin_tao_don_ho_seller_tru_vi_va_giu_kin_mau_rieng(): void
    {
        $admin = $this->withSession(['admin_logged_in' => true]);
        $ngA = $this->noiGiao($this->a);
        // seller nào chỉ thấy mẫu của seller đó: B không thấy mẫu riêng của A
        $admin->get('/admin/3d/pod/don/tao')->assertOk()->assertSee('Chọn seller');
        $admin->get('/admin/3d/pod/don/tao?seller=' . $this->a->id)->assertOk()->assertSee('Khuôn bánh Eid')->assertSee('Mẫu độc quyền của A')->assertSee('Kho gom HN');
        $admin->get('/admin/3d/pod/don/tao?seller=' . $this->b->id)->assertOk()->assertSee('Khuôn bánh Eid')->assertDontSee('Mẫu độc quyền của A')->assertDontSee('Kho gom HN');
        $admin->get('/admin/3d/pod/seller')->assertSee('Tạo đơn hộ');
        $admin->get('/admin/3d/pod/don')->assertSee('+ Tạo đơn hộ seller');

        // đủ tiền: Chờ in, trừ ví A; dòng trống bỏ qua; SL trống = 1
        PodSo::napTien($this->a, 100000, 'nap', null);
        $r = $admin->post('/admin/3d/pod/don/tao', ['seller_id' => $this->a->id, 'noi_giao_id' => (string) $ngA, 'ma_don_san' => 'ETSY-9',
            'dong' => [['bien_the_id' => '', 'so_luong' => 1], ['bien_the_id' => $this->bt($this->chung), 'so_luong' => '']]]);
        $don = PodDon::latest('id')->first();
        $r->assertRedirect(route('admin.pod.don.show', $don))->assertSessionHas('ok', fn ($m) => str_contains($m, 'Đã trừ ví'));
        $this->assertTrue($don->xuong_tao);
        $this->assertSame(['cho_in', 24440, 'ETSY-9', 'Kho gom HN', 1], [$don->trang_thai, (int) $don->tong_tien, $don->ma_don_san, $don->noi_giao['ten'], (int) $don->dong()->first()->so_luong]);
        $this->assertSame(100000 - 24440, $this->a->soDu());
        $j = collect($this->getJson('/api/pod/don', $this->token($this->a))->json('don'))->firstWhere('ma', $don->ma);
        $this->assertSame([true, 'Xưởng tạo hộ'], [$j['xuong_tao'], $j['nguoi_dat']]);   // seller thấy đơn do xưởng tạo
        $admin->get("/admin/3d/pod/don/{$don->id}")->assertSee('Xưởng tạo hộ');

        // thiếu tiền + nơi giao nhập tay: Chờ nạp tiền, ví không đổi
        $admin->post('/admin/3d/pod/don/tao', ['seller_id' => $this->a->id, 'noi_giao_id' => 'moi', 'ng_ten' => 'Line bay Mỹ', 'ng_dia_chi' => 'Q. Tân Bình',
            'dong' => [['bien_the_id' => $this->bt($this->chung), 'so_luong' => 5]]])->assertSessionHas('ok', fn ($m) => str_contains($m, 'Chờ nạp tiền'));
        $d2 = PodDon::latest('id')->first();
        $this->assertSame(['cho_nap', 'Line bay Mỹ', false], [$d2->trang_thai, $d2->noi_giao['ten'], $d2->da_tru]);
        $this->assertSame(100000 - 24440, $this->a->soDu());

        // từ chối: mẫu riêng A cho B, nơi giao của A cho B, thiếu địa chỉ, không dòng nào, seller bị khoá
        $n = PodDon::count();
        $hop = ['seller_id' => $this->b->id, 'noi_giao_id' => 'moi', 'ng_ten' => 'X', 'ng_dia_chi' => 'Y', 'dong' => [['bien_the_id' => $this->bt($this->chung), 'so_luong' => 1]]];
        $admin->post('/admin/3d/pod/don/tao', ['dong' => [['bien_the_id' => $this->bt($this->riengA), 'so_luong' => 1]]] + $hop)->assertSessionHasErrors('dong.0');
        $admin->post('/admin/3d/pod/don/tao', ['noi_giao_id' => (string) $ngA] + $hop)->assertSessionHasErrors('noi_giao_id');
        $admin->post('/admin/3d/pod/don/tao', ['ng_dia_chi' => ''] + $hop)->assertSessionHasErrors('noi_giao_id');
        $admin->post('/admin/3d/pod/don/tao', ['dong' => [['bien_the_id' => '']]] + $hop)->assertSessionHasErrors('dong');
        $this->b->update(['hien' => false]);
        $admin->post('/admin/3d/pod/don/tao', $hop)->assertSessionHasErrors('seller_id');
        $this->assertSame($n, PodDon::count());

        $this->flushSession();
        $this->get('/admin/3d/pod/don/tao')->assertRedirect();
        $this->b->update(['hien' => true]);
        $this->post('/admin/3d/pod/don/tao', $hop)->assertRedirect();
        $this->assertSame($n, PodDon::count());
    }

    public function test_admin_tai_file_in_tu_khop_phan_loai_mot_file_hoac_zip(): void
    {
        $m = $this->taoMau('Khay vỏ sò', null, 48.2, 'PETG');
        $b5 = $m->bienThe()->first();
        $b5->update(['ten' => '5 inch', 'file_in' => 'A1 · E:\\POD\\Khay · file "Khay - A1 5 in.3mf" · ~2.5 giờ']);
        $b8 = PodBienThe::create(['mau_id' => $m->id, 'ten' => '8 inch', 'gram' => 135.6, 'chat_lieu' => 'PETG', 'file_in' => 'A1 · file "Khay - A1 8 in.3mf"', 'thu_tu' => 1]);
        $admin = $this->withSession(['admin_logged_in' => true]);
        $form = ['ten' => $m->ten, 'chu_seller_id' => '', 'hien' => '1', 'bt' => [
            ['id' => $b5->id, 'ten' => '5 inch', 'gram' => 48.2, 'chat_lieu' => 'PETG', 'file_in' => $b5->file_in],
            ['id' => $b8->id, 'ten' => '8 inch', 'gram' => 135.6, 'chat_lieu' => 'PETG', 'file_in' => $b8->file_in]]];

        $admin->put("/admin/3d/pod/mau/{$m->id}", $form + ['file_in_moi' => [UploadedFile::fake()->createWithContent('virus.exe', 'x')]])
            ->assertSessionHasErrors('file_in_moi');
        $admin->put("/admin/3d/pod/mau/{$m->id}", $form + ['file_in_moi' => [
            UploadedFile::fake()->createWithContent('Khay - A1 5 in.3mf', 'NAM'),
            UploadedFile::fake()->createWithContent('Khay - A1 8 in.3mf', 'TAM'),
            UploadedFile::fake()->createWithContent('De chung (phu kien).3mf', 'DE'),
        ]])->assertSessionHasNoErrors();

        $m = $m->fresh('bienThe');
        $this->assertSame(['De chung (phu kien).3mf', 'Khay - A1 5 in.3mf', 'Khay - A1 8 in.3mf'], array_column($m->tepIn(), 'ten'));
        $this->assertSame(['De chung (phu kien).3mf', 'Khay - A1 5 in.3mf'], array_column($m->tepInCho($b5->file_in), 'ten')); // đúng cỡ + file chung
        $this->assertSame(['De chung (phu kien).3mf', 'Khay - A1 8 in.3mf'], array_column($m->tepInCho($b8->file_in), 'ten'));
        $admin->get("/admin/3d/pod/mau/{$m->id}/sua")->assertOk()->assertSee('File in tải về (3)')->assertSee('File chung — mọi phân loại');
        $admin->get('/admin/3d/pod/mau')->assertOk()->assertSee('3 file in tải được');
        $admin->get(route('admin.pod.mau.filein', [$m, 'Khay - A1 8 in.3mf']))->assertOk()->assertDownload('Khay - A1 8 in.3mf');
        $admin->get(route('admin.pod.mau.filein', [$m, 'khong-co.3mf']))->assertNotFound();
        $admin->get("/admin/3d/pod/mau/{$m->id}/file-in/..")->assertNotFound();

        // trang đơn: dòng 5 inch có 2 file -> một nút tải .zip đủ cả hai
        $d = PodSo::datDon($this->a, [['bien_the_id' => $b5->id, 'so_luong' => 2]], null, null, null, null);
        $dong = $d->dong()->first();
        $admin->get("/admin/3d/pod/don/{$d->id}")->assertOk()->assertSee('Tải 2 file in (.zip)')->assertSee('De chung (phu kien).3mf · Khay - A1 5 in.3mf');
        $r = $admin->get(route('admin.pod.don.filein', [$d, $dong->id]))->assertOk();
        $this->assertStringContainsString('.zip', $r->headers->get('content-disposition'));
        $zip = new \ZipArchive();
        $this->assertTrue($zip->open($r->baseResponse->getFile()->getPathname()));
        $this->assertSame(['DE', 'NAM'], [$zip->getFromName('De chung (phu kien).3mf'), $zip->getFromName('Khay - A1 5 in.3mf')]);
        $this->assertSame(2, $zip->numFiles);
        $zip->close();

        // bỏ file chung -> còn 1 file: tải thẳng, không zip
        $admin->put("/admin/3d/pod/mau/{$m->id}", $form + ['xoa_file_in' => ['De chung (phu kien).3mf']])->assertSessionHasNoErrors();
        $admin->get(route('admin.pod.don.filein', [$d, $dong->id]))->assertOk()->assertDownload('Khay - A1 5 in.3mf');
        $admin->get("/admin/3d/pod/don/{$d->id}")->assertSee('Tải file in');

        // mẫu không có file: không nút, link 404; chưa đăng nhập admin thì không tải được
        $d2 = PodSo::datDon($this->a, [['bien_the_id' => $this->bt($this->chung), 'so_luong' => 1]], null, null, null, null);
        $this->withSession(['admin_logged_in' => true])->get("/admin/3d/pod/don/{$d2->id}")->assertOk()->assertDontSee('Tải file in');
        $this->withSession(['admin_logged_in' => true])->get(route('admin.pod.don.filein', [$d2, $d2->dong()->first()->id]))->assertNotFound();
        $this->withSession(['admin_logged_in' => true])->get(route('admin.pod.don.filein', [$d2, $dong->id]))->assertNotFound(); // dòng của đơn khác
        $this->flushSession();
        $this->get(route('admin.pod.don.filein', [$d, $dong->id]))->assertRedirect();
        $this->get(route('admin.pod.mau.filein', [$m, 'Khay - A1 8 in.3mf']))->assertRedirect();

        // mẫu riêng: tải file in ghi nhật ký
        Storage::disk('local')->put("pod/file_in/{$this->riengA->ma}/Rieng.3mf", 'R');
        $this->withSession(['admin_logged_in' => true])->get(route('admin.pod.mau.filein', [$this->riengA, 'Rieng.3mf']))->assertDownload('Rieng.3mf');
        $this->assertTrue(PodNhatKy::where('mau_id', $this->riengA->id)->where('hanh_dong', 'tai_file_in')->exists());
    }

    public function test_gia_theo_chat_lieu_petg_900_pla_1300(): void
    {
        $petg = $this->taoMau('Móc khoá PETG', null, 10.0, 'PETG');
        $don = PodSo::datDon($this->a, [
            ['bien_the_id' => $this->bt($petg), 'so_luong' => 2],          // 10 g × 2 × 900 = 18.000
            ['bien_the_id' => $this->bt($this->chung), 'so_luong' => 1],   // 18,8 g × 1.300 = 24.440
        ], null, null, null, null);
        $this->assertSame(18000 + 24440, (int) $don->tong_tien);
        $dong = $don->dong()->get();
        $this->assertSame(['PETG', 'PLA'], $dong->pluck('chat_lieu')->all());
        $this->assertSame([900, 1300], $dong->pluck('don_gia_gram')->map(fn ($x) => (int) $x)->all());
        $bt = collect($this->getJson('/api/pod/mau/' . $petg->ma, $this->token($this->a))->json('mau.bien_the'))->first();
        $this->assertSame(['PETG', 9000], [$bt['chat_lieu'], $bt['gia']]);
        // Phân loại mới không ghi chất liệu -> PETG (nhựa chính của xưởng)
        $this->assertSame('PETG', PodBienThe::create(['mau_id' => $petg->id, 'ten' => 'Cỡ to', 'gram' => 20])->fresh()->chat_lieu);
        // Admin đổi giá PETG: đơn mới theo giá mới, đơn cũ giữ nguyên
        $this->withSession(['admin_logged_in' => true])->post('/admin/3d/pod/gia-gram', ['gia_petg' => 950, 'gia_pla' => 1300])->assertSessionHasNoErrors();
        $this->assertSame(950, PodSo::giaGram('PETG'));
        $this->assertSame(42440, (int) $don->fresh()->tong_tien);
        $this->get('http://' . config('pod.domain') . '/bang-gia')->assertOk()->assertSee('950đ')->assertSee('1.300đ');
    }

    private function token(PodSeller $s): array
    {
        $t = Str::random(48);
        PodPhien::create(['seller_id' => $s->id, 'token_hash' => PodPhien::bam($t), 'dung_luc' => now()]);
        return ['X-Pod-Token' => $t, 'Accept' => 'application/json'];
    }

    private function noiGiao(PodSeller $s): int
    {
        return PodNoiGiao::create(['seller_id' => $s->id, 'ten' => 'Kho gom HN', 'dia_chi' => 'Hà Nội'])->id;
    }

    private function bt(PodMau $m): int
    {
        return (int) $m->bienThe()->value('id');
    }

    /* ---------------- Giữ kín mẫu độc quyền ---------------- */

    public function test_giu_kin_danh_sach_khong_co_mau_rieng_nguoi_khac(): void
    {
        $maB = collect($this->getJson('/api/pod/mau', $this->token($this->b))->assertOk()->json('mau'))->pluck('ma');
        $this->assertTrue($maB->contains($this->chung->ma));
        $this->assertFalse($maB->contains($this->riengA->ma));

        $maA = collect($this->getJson('/api/pod/mau', $this->token($this->a))->json('mau'))->pluck('ma');
        $this->assertTrue($maA->contains($this->riengA->ma));
        $this->assertTrue($maA->contains($this->chung->ma));
    }

    public function test_giu_kin_cap_quyen_chi_seller_duoc_chon_thay_va_dat_duoc(): void
    {
        $c = $this->taoSeller('Seller C', '0900000003');
        $this->riengA->duocCap()->attach($this->b->id);
        $hb = $this->token($this->b);
        $hc = $this->token($c);
        // được cấp: thấy ở tab riêng, mở chi tiết + ảnh, đặt được đơn; cổng biết là mẫu dùng chung, không lộ tên ai
        $mau = collect($this->getJson('/api/pod/mau?kho=rieng', $hb)->assertOk()->json('mau'));
        $this->assertSame([$this->riengA->ma], $mau->pluck('ma')->all());
        $this->assertTrue($mau->first()['chia_se']);
        $this->assertStringNotContainsString('Seller A', json_encode($mau->first(), JSON_UNESCAPED_UNICODE));
        $this->assertFalse(collect($this->getJson('/api/pod/mau?kho=chung', $hb)->json('mau'))->pluck('ma')->contains($this->riengA->ma), 'Không lọt sang tab Kho chung');
        $this->getJson('/api/pod/mau/' . $this->riengA->ma, $hb)->assertOk();
        $link = $this->getJson('/api/pod/mau/' . $this->riengA->ma, $hb)->json('mau.anh.0');
        $this->get($link)->assertOk();
        $don = PodSo::datDon($this->b, [['bien_the_id' => $this->bt($this->riengA), 'so_luong' => 1]], null, null, null, null);
        $this->assertSame('Mẫu độc quyền của A', $don->dong()->first()->ten_mau);
        // không được cấp: như mẫu không tồn tại ở mọi đường
        $this->assertSame([], $this->getJson('/api/pod/mau?kho=rieng', $hc)->assertOk()->json('mau'));
        $this->getJson('/api/pod/mau/' . $this->riengA->ma, $hc)->assertNotFound();
        $this->get(URL::temporarySignedRoute('pod.anh', now()->addMinutes(5), ['ma' => $this->riengA->ma, 'i' => 0, 's' => $c->id], absolute: false))->assertNotFound();
        $this->assertDatLoi($c, $this->riengA);
        // chủ mẫu không bị ảnh hưởng; trang công khai vẫn không có
        $this->getJson('/api/pod/mau/' . $this->riengA->ma, $this->token($this->a))->assertOk();
        $this->get('http://' . config('pod.domain') . '/kho-mau/' . $this->riengA->ma)->assertNotFound();
        $this->assertTrue($this->getJson('/api/pod/mau/' . $this->riengA->ma, $this->token($this->a))->json('mau.chia_se'), 'Chủ mẫu cũng phải biết mẫu đã chia sẻ');
        // gỡ quyền là mất ngay, mẫu về độc quyền; mẫu ẩn thì người được cấp cũng không thấy
        $this->riengA->duocCap()->detach($this->b->id);
        $this->getJson('/api/pod/mau/' . $this->riengA->ma, $hb)->assertNotFound();
        $this->assertDatLoi($this->b, $this->riengA);
        $this->assertFalse($this->getJson('/api/pod/mau/' . $this->riengA->ma, $this->token($this->a))->json('mau.chia_se'));
        $this->riengA->duocCap()->attach($this->b->id);
        $this->riengA->update(['hien' => false]);
        $this->getJson('/api/pod/mau/' . $this->riengA->ma, $hb)->assertNotFound();
    }

    private function assertDatLoi(PodSeller $s, PodMau $m): void
    {
        try {
            PodSo::datDon($s, [['bien_the_id' => $this->bt($m), 'so_luong' => 1]], null, null, null, null);
            $this->fail('Seller không có quyền mà đặt được mẫu riêng');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertStringContainsString('không tìm thấy mẫu', collect($e->errors())->flatten()->first());
        }
    }

    public function test_admin_cap_quyen_ghi_nhat_ky_va_ve_kho_chung_thi_xoa(): void
    {
        $admin = $this->withSession(['admin_logged_in' => true]);
        $url = '/admin/3d/pod/mau/' . $this->riengA->id;
        $form = ['ten' => $this->riengA->ten, 'chu_seller_id' => $this->a->id, 'hien' => '1',
                 'bt' => [['id' => $this->bt($this->riengA), 'ten' => 'Cỡ gốc', 'gram' => 12]]];
        // form có khối cấp quyền: tích ai thì người đó; tích trúng chủ mẫu thì bỏ qua
        $admin->put($url, $form + ['cap_form' => '1', 'cap_seller' => [$this->b->id, $this->a->id]])->assertSessionHasNoErrors();
        $this->assertSame([$this->b->id], $this->riengA->duocCap()->pluck('pod_seller.id')->all());
        $this->assertStringContainsString('Thêm: Seller B', PodNhatKy::where('mau_id', $this->riengA->id)->where('hanh_dong', 'cap_quyen')->latest('id')->value('chi_tiet'));
        // form cũ không có khối này thì không đụng tới danh sách
        $admin->put($url, $form)->assertSessionHasNoErrors();
        $this->assertSame(1, $this->riengA->duocCap()->count());
        // nick không tồn tại bị từ chối
        $admin->put($url, $form + ['cap_form' => '1', 'cap_seller' => [999999]])->assertSessionHasErrors('cap_seller.0');
        // bỏ tích là gỡ, có nhật ký
        $this->riengA->duocCap()->sync([$this->b->id]);
        $admin->put($url, $form + ['cap_form' => '1'])->assertSessionHasNoErrors();
        $this->assertSame(0, $this->riengA->duocCap()->count());
        $this->assertStringContainsString('Gỡ: Seller B', PodNhatKy::where('mau_id', $this->riengA->id)->where('hanh_dong', 'cap_quyen')->latest('id')->value('chi_tiet'));
        // về Kho chung thì danh sách cấp bị xoá, kẻo sau này về lại Kho riêng người cũ tự có quyền
        $this->riengA->duocCap()->sync([$this->b->id]);
        $admin->put($url, ['chu_seller_id' => '', 'xac_nhan_chung' => '1', 'dong_y_chung' => 'Seller A đồng ý'] + $form)->assertSessionHasNoErrors();
        $this->assertNull($this->riengA->fresh()->chu_seller_id);
        $this->assertSame(0, $this->riengA->duocCap()->count());
        // trang admin hiện đúng ô tích + danh sách
        $this->riengA->refresh()->update(['chu_seller_id' => $this->a->id]);
        $this->riengA->duocCap()->sync([$this->b->id]);
        $admin->get(route('admin.pod.mau.edit', $this->riengA))->assertOk()->assertSee('Seller được xem và đặt mẫu này')->assertSee('cap_seller[]', false);
        $admin->get(route('admin.pod.mau.index'))->assertOk()->assertSee('+1')->assertDontSee('@else', false);
    }

    public function test_giu_kin_chi_tiet_tra_404_nhu_ma_khong_ton_tai(): void
    {
        $h = $this->token($this->b);
        $that = $this->getJson('/api/pod/mau/' . $this->riengA->ma, $h)->assertNotFound();
        $bia  = $this->getJson('/api/pod/mau/khongtontai', $h)->assertNotFound();
        $this->assertSame($bia->json(), $that->json(), 'Mẫu riêng người khác phải trả y hệt mã không tồn tại.');
        $this->getJson('/api/pod/mau/' . $this->riengA->ma, $this->token($this->a))->assertOk();
    }

    public function test_giu_kin_link_anh(): void
    {
        // A lấy được ảnh của mình qua link server cấp
        $url = $this->getJson('/api/pod/mau/' . $this->riengA->ma, $this->token($this->a))->json('mau.anh.0');
        $this->get($url)->assertOk();
        // Sửa s=A thành s=B trên link của A -> chữ ký sai
        $this->get(str_replace('s=' . $this->a->id, 's=' . $this->b->id, $url))->assertForbidden();
        // Link chữ ký ĐÚNG nhưng cho seller B (server không bao giờ cấp) -> vẫn 404
        $lenB = URL::temporarySignedRoute('pod.anh', now()->addMinutes(10), ['ma' => $this->riengA->ma, 'i' => 0, 's' => $this->b->id], absolute: false);
        $this->get($lenB)->assertNotFound();
        // Không chữ ký
        $this->get('/api/pod/anh/' . $this->riengA->ma . '/0?s=' . $this->a->id)->assertForbidden();
    }

    public function test_giu_kin_khong_dat_duoc_mau_rieng_nguoi_khac(): void
    {
        $this->postJson('/api/pod/don', [
            'dong' => [['bien_the_id' => $this->bt($this->riengA), 'so_luong' => 1]],
            'noi_giao_id' => $this->noiGiao($this->b),
        ], $this->token($this->b))->assertStatus(422)->assertJsonFragment(['Dòng 1: không tìm thấy mẫu.']);
        $this->assertSame(0, PodDon::count());
    }

    public function test_giu_kin_mau_an_khong_ai_thay(): void
    {
        $this->riengA->update(['hien' => false]);
        $this->getJson('/api/pod/mau/' . $this->riengA->ma, $this->token($this->a))->assertNotFound();
    }

    public function test_giu_kin_catalog_ban_le_khong_co_mau_pod(): void
    {
        $body = $this->getJson('/api/3d/catalog')->getContent();
        $this->assertStringNotContainsString($this->riengA->ma, $body);
        $this->assertStringNotContainsString('Mẫu độc quyền của A', $body);
    }

    public function test_giu_kin_web_cong_khai_khong_lo_mau_rieng(): void
    {
        $goc = 'http://' . config('pod.domain');
        foreach (['/', '/kho-mau', '/sitemap.xml'] as $u) {
            $body = $this->get($goc . $u)->assertOk()->getContent();
            $this->assertStringNotContainsString($this->riengA->ma, $body, $u);
            $this->assertStringNotContainsString('Mẫu độc quyền của A', $body, $u);
        }
        $this->assertStringContainsString($this->chung->ma, $this->get($goc . '/sitemap.xml')->getContent());
        $this->get($goc . '/kho-mau/' . $this->riengA->ma)->assertNotFound();
        $this->get($goc . '/anh-mau/' . $this->riengA->ma . '/0')->assertNotFound();
        $this->get($goc . '/anh-mau/' . $this->chung->ma . '/0')->assertOk();
        // Mẫu Kho chung hiện giá in 1 chiếc theo cỡ: 18,8 g × 1.300 = 24.440đ (trang chi tiết + thẻ "từ …đ")
        $this->get($goc . '/kho-mau/' . $this->chung->ma)->assertOk()->assertSee('Khuôn bánh Eid')->assertSee('24.440đ');
        $this->get($goc . '/kho-mau')->assertOk()->assertSee('từ 24.440đ');
    }

    public function test_dang_ky_seller_cho_duyet_roi_moi_dang_nhap(): void
    {
        $goc = 'http://' . config('pod.domain');
        $this->post($goc . '/dang-ky', ['ten' => 'Shop Mới', 'sdt' => '0933 444 555', 'matkhau' => 'matkhau7', 'matkhau_confirmation' => 'matkhau7', 'san_ban' => 'Etsy', 'dong_y' => '1'])
            ->assertRedirect()->assertSessionHas('xong');
        $s = PodSeller::where('sdt', '0933444555')->firstOrFail();
        $this->assertTrue($s->cho_duyet);
        $this->assertFalse($s->hien);
        $this->postJson('/api/pod/dang-nhap', ['sdt' => '0933444555', 'matkhau' => 'matkhau7'])->assertStatus(403)
            ->assertJsonFragment(['error' => 'Tài khoản đang chờ xưởng duyệt. Xưởng sẽ báo qua Zalo khi duyệt xong.']);
        $this->withSession(['admin_logged_in' => true])->post('/admin/3d/pod/seller/' . $s->id . '/duyet')->assertSessionHasNoErrors();
        $this->postJson('/api/pod/dang-nhap', ['sdt' => '0933444555', 'matkhau' => 'matkhau7'])->assertOk();
        // Ô bẫy máy spam: không tạo tài khoản
        $this->post($goc . '/dang-ky', ['website' => 'http://spam.example', 'ten' => 'Bot', 'sdt' => '0900999888', 'matkhau' => 'matkhau7', 'matkhau_confirmation' => 'matkhau7', 'dong_y' => '1'])->assertRedirect();
        $this->assertFalse(PodSeller::where('sdt', '0900999888')->exists());
    }

    public function test_ten_mien_pod_trang_cong_khai_cho_google_cong_thi_an(): void
    {
        $goc = 'http://' . config('pod.domain');
        foreach (['/', '/bang-gia', '/kho-mau', '/dang-ky', '/kho-mau/' . $this->chung->ma] as $u) {
            $r = $this->get($goc . $u)->assertOk();
            $this->assertNull($r->headers->get('X-Robots-Tag'), "$u phải cho Google lập chỉ mục");
            $this->assertMatchesRegularExpression('/<meta property="og:image" content="https?:\/\/[^"]+"/', $r->getContent(), "$u: gửi link phải có ảnh");
        }
        // trang chủ: ảnh chung 1200×630 (file có thật trong public/images); trang mẫu: ảnh của mẫu
        $this->get($goc . '/')->assertSee('https://tranhdali.vn/images/og-pod.jpg', false)->assertSee('<meta property="og:image:width" content="1200">', false)
            ->assertSee('<meta name="twitter:card" content="summary_large_image">', false);
        $this->assertFileExists(public_path('images/og-pod.jpg'));
        $this->get($goc . '/kho-mau/' . $this->chung->ma)->assertSee('/anh-mau/' . $this->chung->ma . '/0?v=', false)
            ->assertDontSee('og-pod.jpg', false);
        $this->get($goc . '/cong')->assertOk()->assertSee('Cổng seller')->assertHeader('X-Robots-Tag', 'noindex, nofollow');
        $this->get($goc . '/robots.txt')->assertOk()->assertSee('Disallow: /cong');
        $this->get('/pod')->assertStatus(301)->assertRedirect(config('pod.url'));
        $this->get($goc . '/tho-in')->assertRedirectContains('/tho-in/dang-nhap');
        $this->getJson($goc . '/api/pod/mau', $this->token($this->a))->assertOk();
        $khop = fn ($u) => app('router')->getRoutes()->match(\Illuminate\Http\Request::create($u))->getName();
        $this->assertSame('home', $khop('http://tranhdali.vn/'));
        $this->assertSame('pod.trang-chu', $khop($goc . '/'));
    }

    /* ---------------- Đăng nhập + điều khoản ---------------- */

    public function test_dang_nhap_va_bat_dong_y_dieu_khoan(): void
    {
        $this->a->update(['dong_y_luc' => null]);
        $tok = $this->postJson('/api/pod/dang-nhap', ['sdt' => '0900000001', 'matkhau' => 'matkhau1'])->assertOk()->json('token');
        $h = ['X-Pod-Token' => $tok, 'Accept' => 'application/json'];
        $this->getJson('/api/pod/mau', $h)->assertStatus(403)->assertJson(['can_dong_y' => true]);
        $this->postJson('/api/pod/dong-y', [], $h)->assertOk();
        $this->getJson('/api/pod/mau', $h)->assertOk();
        $this->postJson('/api/pod/dang-nhap', ['sdt' => '0900000001', 'matkhau' => 'sai'])->assertStatus(401);
    }

    public function test_seller_bi_khoa_mat_phien(): void
    {
        $h = $this->token($this->a);
        $this->a->update(['hien' => false]);
        $this->getJson('/api/pod/mau', $h)->assertStatus(401);
    }

    /* ---------------- Đơn + ví ---------------- */

    public function test_gia_tinh_o_server_theo_gram(): void
    {
        $don = PodSo::datDon($this->a, [['bien_the_id' => $this->bt($this->chung), 'so_luong' => 2, 'gia' => 1, 'chu' => 'Ahmed']], null, null, null, null);
        // 18.8 g × 2 × 1.300 = 48.880 + phí cá nhân hoá 5.000 một lần
        $this->assertSame(53880, (int) $don->tong_tien);
        $this->assertSame(1300, (int) $don->dong()->first()->don_gia_gram);
    }

    public function test_vi_du_thi_cho_in_thieu_thi_cho_nap_roi_tu_vao_khi_nap(): void
    {
        $h = $this->token($this->a);
        $ng = $this->noiGiao($this->a);
        $r1 = $this->postJson('/api/pod/don', ['dong' => [['bien_the_id' => $this->bt($this->chung), 'so_luong' => 1]], 'noi_giao_id' => $ng], $h)->assertOk();
        $this->assertSame('cho_nap', $r1->json('don.trang_thai'));

        PodSo::napTien($this->a, 30000, 'nap', 'CK test');
        $don = PodDon::where('ma', $r1->json('don.ma'))->first();
        $this->assertSame('cho_in', $don->trang_thai);
        $this->assertSame(30000 - 24440, $this->a->soDu());

        $r2 = $this->postJson('/api/pod/don', ['dong' => [['bien_the_id' => $this->bt($this->riengA), 'so_luong' => 1]], 'noi_giao_id' => $ng], $h)->assertOk();
        $this->assertSame('cho_nap', $r2->json('don.trang_thai')); // 12 g = 15.600đ > số dư 5.560đ
    }

    public function test_khong_bao_gio_tieu_qua_so_du(): void
    {
        PodSo::napTien($this->a, 30000, 'nap', null);
        $d1 = PodSo::datDon($this->a, [['bien_the_id' => $this->bt($this->chung), 'so_luong' => 1]], null, null, null, null); // 24.440
        $d2 = PodSo::datDon($this->a, [['bien_the_id' => $this->bt($this->chung), 'so_luong' => 1]], null, null, null, null);
        $this->assertSame('cho_in', $d1->trang_thai);
        $this->assertSame('cho_nap', $d2->trang_thai);
        $this->assertSame(5560, $this->a->soDu());
        $this->assertGreaterThanOrEqual(0, $this->a->soDu());
    }

    public function test_vi_tra_sau_khong_gioi_han_tat_thi_cho_nap_tra_no_thi_chay_tiep(): void
    {
        $don = fn (int $sl = 1) => PodSo::datDon($this->a, [['bien_the_id' => $this->bt($this->chung), 'so_luong' => $sl]], null, null, null, null); // 24.440 / chiếc
        $admin = $this->withSession(['admin_logged_in' => true]);
        $d0 = $don();
        $this->assertSame('cho_nap', $d0->trang_thai); // chưa bật trả sau: như cũ

        // bật trả sau -> đơn đang chờ chạy ngay, ví âm thành nợ
        $admin->post("/admin/3d/pod/seller/{$this->a->id}/tra-sau", ['tra_sau' => '1'])
            ->assertSessionHasNoErrors()->assertSessionHas('ok', fn ($m) => str_contains($m, 'không giới hạn') && str_contains($m, '1 đơn chờ nạp tiền'));
        $this->assertTrue($this->a->fresh()->traSau());
        $this->assertSame('cho_in', $d0->fresh()->trang_thai);

        // không giới hạn: đơn lớn vẫn vào máy in
        $lon = $don(400);
        $this->assertSame('cho_in', $lon->trang_thai);
        $this->assertSame(-24440 * 401, $this->a->soDu());
        $v = $this->getJson('/api/pod/vi', $this->token($this->a))->assertOk();
        $this->assertSame([true, 0], [$v->json('tra_sau'), $v->json('can_them')]);
        $this->assertTrue($this->getJson('/api/pod/toi', $this->token($this->a))->json('tra_sau'));
        $admin->get('/admin/3d/pod/seller')->assertOk()->assertSee('Đang nợ 9.800.440đ')->assertSee('Tắt trả sau');
        $admin->get("/admin/3d/pod/seller/{$this->a->id}/vi")->assertSee('trả sau (không giới hạn)');

        // trả hết nợ rồi lại nợ một đơn; tắt trả sau khi đang nợ: đơn mới chờ nạp, huỷ đơn chưa in vẫn hoàn tiền
        PodSo::napTien($this->a, 24440 * 401, 'nap', 'CK trả nợ');
        $this->assertSame(0, $this->a->soDu());
        $d2 = $don();
        $admin->post("/admin/3d/pod/seller/{$this->a->id}/tra-sau", ['tra_sau' => '0'])
            ->assertSessionHas('ok', fn ($m) => str_contains($m, 'Đã tắt trả sau') && str_contains($m, 'đang nợ 24.440đ'));
        $this->assertFalse($this->a->fresh()->traSau());
        $this->assertSame('cho_nap', $don()->trang_thai);
        $this->assertSame(48880, $this->getJson('/api/pod/vi', $this->token($this->a))->json('can_them')); // trả nợ 24.440 + đơn chờ 24.440
        $this->postJson("/api/pod/don/{$d2->ma}/huy", [], $this->token($this->a))->assertOk();
        $this->assertSame(0, $this->a->soDu());
        $admin->post("/admin/3d/pod/seller/{$this->a->id}/tra-sau", ['tra_sau' => 'x'])->assertSessionHasErrors('tra_sau');

        // seller khác không trả sau
        $this->assertSame('cho_nap', PodSo::datDon($this->b, [['bien_the_id' => $this->bt($this->chung), 'so_luong' => 1]], null, null, null, null)->trang_thai);

        // seller đang có hạn mức cũ (> 0) được chuyển thành trả sau khi chạy migration
        $this->b->update(['han_muc' => 1000000, 'tra_sau' => false]);
        (require base_path('database/migrations/2026_11_16_000002_pod_tra_sau_khong_gioi_han.php'))->up();
        $this->assertTrue($this->b->fresh()->traSau());

        // ô tiền gõ có dấu chấm nghìn
        $this->b->update(['tra_sau' => false]);
        $admin->post("/admin/3d/pod/seller/{$this->b->id}/nap", ['so_tien' => '1.250.000', 'loai' => 'nap'])->assertSessionHasNoErrors();
        $admin->post("/admin/3d/pod/seller/{$this->b->id}/nap", ['so_tien' => '-50.000', 'loai' => 'dieu_chinh', 'ghi_chu' => 'thử'])->assertSessionHasNoErrors();
        $this->assertSame(1250000 - 50000 - 24440, $this->b->soDu()); // đơn chờ của B chạy khi nạp
    }

    public function test_huy_don_hoan_tien_va_khong_huy_khi_dang_in(): void
    {
        PodSo::napTien($this->a, 100000, 'nap', null);
        $h = $this->token($this->a);
        $d = PodSo::datDon($this->a, [['bien_the_id' => $this->bt($this->chung), 'so_luong' => 1]], null, null, null, null);
        $this->postJson("/api/pod/don/{$d->ma}/huy", [], $h)->assertOk();
        $this->assertSame(100000, $this->a->soDu());

        $d2 = PodSo::datDon($this->a, [['bien_the_id' => $this->bt($this->chung), 'so_luong' => 1]], null, null, null, null);
        $d2->update(['trang_thai' => 'dang_in']);
        $this->postJson("/api/pod/don/{$d2->ma}/huy", [], $h)->assertStatus(422);
        // Seller B không huỷ được đơn của A
        $this->postJson("/api/pod/don/{$d2->ma}/huy", [], $this->token($this->b))->assertNotFound();
    }

    public function test_don_cua_ai_nguoi_nay_thay(): void
    {
        PodSo::datDon($this->a, [['bien_the_id' => $this->bt($this->chung), 'so_luong' => 1]], null, null, null, null);
        $this->assertCount(0, $this->getJson('/api/pod/don', $this->token($this->b))->json('don'));
        $this->assertCount(1, $this->getJson('/api/pod/don', $this->token($this->a))->json('don'));
    }

    public function test_nhan_luu_dia_private(): void
    {
        $h = $this->token($this->a);
        $r = $this->post('/api/pod/don', [
            'dong' => json_encode([['bien_the_id' => $this->bt($this->chung), 'so_luong' => 1]]),
            'noi_giao_id' => $this->noiGiao($this->a),
            'nhan' => UploadedFile::fake()->create('nhan.pdf', 50, 'application/pdf'),
        ], $h)->assertOk();
        $don = PodDon::where('ma', $r->json('don.ma'))->first();
        Storage::disk('local')->assertExists($don->getRawOriginal('nhan'));
        $this->assertStringStartsWith('pod/nhan/', $don->getRawOriginal('nhan'));
    }

    /* ---------------- Thợ in: chỉ hàng đợi in ---------------- */

    private function taoTho(bool $hien = true): PodThoIn
    {
        return PodThoIn::create(['ten' => 'Thợ Minh', 'sdt' => '0911000001', 'matkhau' => Hash::make('thoin123'), 'hien' => $hien]);
    }

    /** Đơn đã trả của A: 12 g × 3 × 1.300 + 5.000 phí chữ = 51.800đ. */
    private function donChoIn(): PodDon
    {
        PodSo::napTien($this->a, 100000, 'nap', null);
        return PodSo::datDon($this->a, [['bien_the_id' => $this->bt($this->riengA), 'so_luong' => 3, 'chu' => 'Layla']], ['ten' => 'Line bay HCM'], null, null, null);
    }

    public function test_tho_in_thay_hang_doi_khong_thay_tien_khong_thay_don_chua_tra(): void
    {
        $don = $this->donChoIn();
        $choNap = PodSo::datDon($this->b, [['bien_the_id' => $this->bt($this->chung), 'so_luong' => 1]], null, null, null, null);
        $this->assertSame('cho_nap', $choNap->trang_thai);
        $this->taoTho();
        $this->post('/tho-in/dang-nhap', ['sdt' => '0911000001', 'matkhau' => 'thoin123'])->assertRedirect(route('tho-in.hang-doi'));
        $this->get('/tho-in')->assertOk()->assertSee($don->ma)->assertSee('Layla')->assertDontSee($choNap->ma)->assertDontSee('51.800');
        $this->get('/tho-in/don/' . $don->id)->assertOk()->assertSee('Layla')->assertDontSee('51.800');
        $this->get('/tho-in/don/' . $choNap->id)->assertNotFound();
    }

    public function test_tho_in_cap_nhat_trang_thai_ghi_ten_khong_dung_tien(): void
    {
        $don = $this->donChoIn();
        $s = $this->withSession(['tho_in_id' => $this->taoTho()->id]);
        $s->post('/tho-in/don/' . $don->id . '/trang-thai', ['trang_thai' => 'dang_in'])->assertRedirect(route('tho-in.hang-doi'));
        $this->assertSame('dang_in', $don->fresh()->trang_thai);
        $this->assertSame('Thợ Minh', $don->fresh()->nguoi_in);
        $s->post('/tho-in/don/' . $don->id . '/trang-thai', ['trang_thai' => 'da_giao', 'ma_van_don' => 'GHN123']);
        $this->assertSame('da_giao', $don->fresh()->trang_thai);
        $this->assertSame('GHN123', $don->fresh()->ma_van_don);
        $this->assertSame('Thợ Minh', $don->fresh()->nguoi_giao);
        $this->assertSame(100000 - 51800, $this->a->soDu());
    }

    public function test_tho_in_khong_vao_duoc_admin_khong_huy_duoc_don(): void
    {
        $don = $this->donChoIn();
        $s = $this->withSession(['tho_in_id' => $this->taoTho()->id]);
        foreach (['/admin', '/admin/3d/pod/mau', '/admin/3d/pod/seller', '/admin/3d/pod/don/' . $don->id, '/admin/3d/pod/nhat-ky', '/admin/3d/pod/tho-in'] as $u) {
            $s->get($u)->assertRedirect(route('admin.login'));
        }
        $s->post('/admin/3d/pod/don/' . $don->id . '/huy')->assertRedirect(route('admin.login'));
        $this->assertSame('cho_in', $don->fresh()->trang_thai);
        $s->getJson('/api/pod/mau')->assertStatus(401);
    }

    public function test_tho_in_bi_khoa_hoac_chua_dang_nhap_ve_trang_dang_nhap(): void
    {
        $this->get('/tho-in')->assertRedirect(route('tho-in.dang-nhap'));
        $tho = $this->taoTho(false);
        $this->withSession(['tho_in_id' => $tho->id])->get('/tho-in')->assertRedirect(route('tho-in.dang-nhap'));
        $this->post('/tho-in/dang-nhap', ['sdt' => '0911000001', 'matkhau' => 'thoin123'])->assertSessionHasErrors('sdt');
    }

    public function test_admin_cap_va_khoa_tho_in(): void
    {
        $admin = $this->withSession(['admin_logged_in' => true]);
        $admin->get('/admin/3d/pod/tho-in')->assertOk();
        $admin->post('/admin/3d/pod/tho-in', ['ten' => 'Thợ Lan', 'sdt' => '0911 000 002', 'matkhau' => 'matkhau9'])->assertSessionHasNoErrors();
        $t = PodThoIn::where('sdt', '0911000002')->firstOrFail();
        $admin->post('/admin/3d/pod/tho-in/' . $t->id . '/khoa');
        $this->assertFalse($t->fresh()->hien);
    }

    /* ---------------- Admin ---------------- */

    public function test_admin_phai_dang_nhap(): void
    {
        $this->get('/admin/3d/pod/mau')->assertRedirect(route('admin.login'));
        $this->get('/admin/3d/pod/don')->assertRedirect(route('admin.login'));
    }

    public function test_admin_chuyen_mau_rieng_sang_chung_can_xac_nhan_va_ghi_nhat_ky(): void
    {
        $admin = $this->withSession(['admin_logged_in' => true]);
        $form = ['ten' => $this->riengA->ten, 'chu_seller_id' => '', 'hien' => '1',
                 'bt' => [['id' => $this->bt($this->riengA), 'ten' => 'Cỡ gốc', 'gram' => 12]]];
        $admin->put('/admin/3d/pod/mau/' . $this->riengA->id, $form)->assertSessionHasErrors('chu_seller_id');
        $this->assertSame($this->a->id, $this->riengA->fresh()->chu_seller_id);

        $admin->put('/admin/3d/pod/mau/' . $this->riengA->id, $form + ['xac_nhan_chung' => '1', 'dong_y_chung' => 'Seller A đồng ý qua Zalo 28/10'])->assertSessionHasNoErrors();
        $this->assertNull($this->riengA->fresh()->chu_seller_id);
        $this->assertTrue(PodNhatKy::where('mau_id', $this->riengA->id)->where('hanh_dong', 'chuyen_kho_chung')->exists());
    }

    public function test_admin_cac_trang_mo_duoc(): void
    {
        PodSo::napTien($this->a, 50000, 'nap', null);
        $d = PodSo::datDon($this->a, [['bien_the_id' => $this->bt($this->riengA), 'so_luong' => 1, 'chu' => 'Sara']], ['ten' => 'Kho gom'], 'ETSY-1', null, null);
        $admin = $this->withSession(['admin_logged_in' => true]);
        foreach (['/admin/3d/pod/seller', '/admin/3d/pod/mau', '/admin/3d/pod/mau?kho=rieng', '/admin/3d/pod/mau/them',
                  '/admin/3d/pod/mau/' . $this->riengA->id . '/sua', '/admin/3d/pod/don', '/admin/3d/pod/don?tt=tat_ca',
                  '/admin/3d/pod/don/' . $d->id, '/admin/3d/pod/seller/' . $this->a->id . '/vi', '/admin/3d/pod/nhat-ky'] as $u) {
            $admin->get($u)->assertOk();
        }
        $admin->get('/admin/3d/pod/mau/' . $this->riengA->id . '/anh/0')->assertOk();
        $admin->post('/admin/3d/pod/don/' . $d->id . '/trang-thai', ['trang_thai' => 'da_giao', 'ma_van_don' => 'VN123'])->assertSessionHasNoErrors();
        $this->assertSame('da_giao', $d->fresh()->trang_thai);
        $this->assertTrue(PodNhatKy::where('mau_id', $this->riengA->id)->where('hanh_dong', 'xem')->exists());
    }

    /* ---------------- Tài khoản: hồ sơ, mật khẩu, thiết bị, nhân viên, nơi giao, sổ ví ---------------- */

    private function taoNv(PodSeller $s, string $sdt = '0911222333', string $mk = 'nhanvien1'): PodNhanVien
    {
        return PodNhanVien::create(['seller_id' => $s->id, 'ten' => 'NV Lan', 'sdt' => $sdt, 'matkhau' => Hash::make($mk), 'hien' => true, 'dong_y_luc' => now()]);
    }

    private function dangNhap(string $sdt, string $mk): array
    {
        $tok = $this->postJson('/api/pod/dang-nhap', ['sdt' => $sdt, 'matkhau' => $mk])->assertOk()->json('token');
        return ['X-Pod-Token' => $tok, 'Accept' => 'application/json'];
    }

    public function test_ho_so_chu_shop_sua_duoc_nhan_vien_chi_xem(): void
    {
        $h = $this->token($this->a);
        $this->getJson('/api/pod/ho-so', $h)->assertOk()->assertJson(['ma' => $this->a->ma, 'vai_tro' => 'chu', 'sdt' => '0900000001']);
        $this->postJson('/api/pod/ho-so', ['ten' => 'Shop A mới', 'san_ban' => 'Etsy', 'link_shop' => 'https://etsy.com/shop/a'], $h)->assertOk();
        $this->assertSame('Shop A mới', $this->a->fresh()->ten);
        $this->postJson('/api/pod/ho-so', ['ten' => 'X', 'link_shop' => 'javascript:alert(1)'], $h)->assertStatus(422);
        $this->assertSame('https://etsy.com/shop/a', $this->a->fresh()->link_shop);
        $this->taoNv($this->a);
        $hn = $this->dangNhap('0911222333', 'nhanvien1');
        $this->getJson('/api/pod/ho-so', $hn)->assertOk()->assertJson(['vai_tro' => 'nhan_vien', 'ten' => 'Shop A mới', 'nhan_vien' => ['ten' => 'NV Lan']]);
        $this->postJson('/api/pod/ho-so', ['ten' => 'Bị đổi'], $hn)->assertStatus(403);
        $this->assertSame('Shop A mới', $this->a->fresh()->ten);
    }

    public function test_doi_mat_khau_sai_bao_loi_dung_thi_dang_xuat_may_khac_giu_may_nay(): void
    {
        $h1 = $this->dangNhap('0900000001', 'matkhau1');
        $h2 = $this->dangNhap('0900000001', 'matkhau1');
        $hB = $this->token($this->b);
        $this->postJson('/api/pod/doi-mat-khau', ['cu' => 'sai', 'moi' => 'moi12345', 'moi_confirmation' => 'moi12345'], $h1)->assertStatus(422);
        $this->postJson('/api/pod/doi-mat-khau', ['cu' => 'matkhau1', 'moi' => 'moi12345', 'moi_confirmation' => 'khac'], $h1)->assertStatus(422);
        $this->postJson('/api/pod/doi-mat-khau', ['cu' => 'matkhau1', 'moi' => 'moi12345', 'moi_confirmation' => 'moi12345'], $h1)->assertOk()->assertJson(['dang_xuat' => 1]);
        $this->getJson('/api/pod/toi', $h1)->assertOk();
        $this->getJson('/api/pod/toi', $h2)->assertStatus(401);
        $this->getJson('/api/pod/toi', $hB)->assertOk();
        $this->assertNotNull($this->a->fresh()->doi_mk_luc);
        $this->postJson('/api/pod/dang-nhap', ['sdt' => '0900000001', 'matkhau' => 'matkhau1'])->assertStatus(401);
        $this->postJson('/api/pod/dang-nhap', ['sdt' => '0900000001', 'matkhau' => 'moi12345'])->assertOk();
    }

    public function test_thiet_bi_chi_thay_va_dang_xuat_may_cua_chinh_minh(): void
    {
        $h1 = $this->dangNhap('0900000001', 'matkhau1');
        $h2 = $this->dangNhap('0900000001', 'matkhau1');
        $this->taoNv($this->a);
        $hn = $this->dangNhap('0911222333', 'nhanvien1');
        $hB = $this->token($this->b);
        $ds = $this->getJson('/api/pod/phien', $h1)->assertOk()->json('phien');
        $this->assertCount(2, $ds);                                   // không thấy máy của nhân viên
        $this->assertSame(1, collect($ds)->where('hien_tai', true)->count());
        $this->assertCount(1, $this->getJson('/api/pod/phien', $hn)->json('phien'));
        $idNv = PodPhien::whereNotNull('nhan_vien_id')->value('id');
        $idB = PodPhien::where('seller_id', $this->b->id)->value('id');
        $this->deleteJson('/api/pod/phien/' . $idNv, [], $h1)->assertOk();
        $this->deleteJson('/api/pod/phien/' . $idB, [], $h1)->assertOk();
        $this->getJson('/api/pod/toi', $hn)->assertOk();              // không đăng xuất được máy người khác
        $this->getJson('/api/pod/toi', $hB)->assertOk();
        $this->postJson('/api/pod/phien/dang-xuat-khac', [], $h1)->assertOk()->assertJson(['dang_xuat' => 1]);
        $this->getJson('/api/pod/toi', $h2)->assertStatus(401);
        $this->getJson('/api/pod/toi', $h1)->assertOk();
        $this->getJson('/api/pod/toi', $hn)->assertOk();              // nhân viên vẫn đăng nhập
        $this->assertSame('Chrome · Windows', PodPhien::nhanThietBi('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0 Safari/537.36'));
        $this->assertSame('Safari · iPhone', PodPhien::nhanThietBi('Mozilla/5.0 (iPhone; CPU iPhone OS 18_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.0 Mobile/15E148 Safari/604.1'));
    }

    public function test_nhan_vien_dat_don_tru_vi_shop_khong_xem_vi_va_khong_quan_ly(): void
    {
        PodSo::napTien($this->a, 100000, 'nap', null);
        $h = $this->token($this->a);
        $this->postJson('/api/pod/nhan-vien', ['ten' => 'Lan', 'sdt' => '0911 222 333', 'matkhau' => 'nhanvien1', 'mk_chu' => 'matkhau1'], $h)->assertOk();
        $hn = $this->dangNhap('0911222333', 'nhanvien1');
        $this->postJson('/api/pod/dong-y', [], $hn)->assertOk();
        $toi = $this->getJson('/api/pod/toi', $hn)->assertOk()->assertJson(['vai_tro' => 'nhan_vien', 'ma' => $this->a->ma, 'nhan_vien' => ['ten' => 'Lan']]);
        $this->assertNull($toi->json('so_du'));
        $this->assertNull($toi->json('tra_sau'));
        $r = $this->postJson('/api/pod/don', ['dong' => [['bien_the_id' => $this->bt($this->chung), 'so_luong' => 1]], 'noi_giao_id' => $this->noiGiao($this->a)], $hn)->assertOk();
        $this->assertNull($r->json('so_du'));
        $this->assertSame('cho_in', $r->json('don.trang_thai'));
        $this->assertSame(100000 - 24440, $this->a->soDu());        // trừ ví của shop
        $don = PodDon::where('ma', $r->json('don.ma'))->first();
        $this->assertNotNull($don->nhan_vien_id);
        $dsChu = $this->getJson('/api/pod/don', $h)->assertOk();
        $this->assertTrue($dsChu->json('co_nhan_vien'));
        $this->assertSame('Lan', collect($dsChu->json('don'))->firstWhere('ma', $don->ma)['nguoi_dat']);
        $this->assertNull($this->postJson("/api/pod/don/{$don->ma}/huy", [], $hn)->assertOk()->json('so_du'));
        $this->assertSame(100000, $this->a->soDu());
        // mẫu độc quyền của shop: nhân viên thấy; nhân viên không vào ví / nhân viên / sửa hồ sơ / xoá nơi giao
        $this->getJson('/api/pod/mau/' . $this->riengA->ma, $hn)->assertOk();
        foreach ([['GET', '/api/pod/vi'], ['GET', '/api/pod/nhan-vien'], ['POST', '/api/pod/nhan-vien'], ['POST', '/api/pod/ho-so']] as [$m, $u]) {
            $this->json($m, $u, ['ten' => 'x', 'sdt' => '0911999888', 'matkhau' => 'abcdef'], $hn)->assertStatus(403);
        }
        $this->assertFalse(PodNhanVien::where('sdt', '0911999888')->exists());
        $ngA = PodNoiGiao::where('seller_id', $this->a->id)->value('id');
        $this->deleteJson('/api/pod/noi-giao/' . $ngA, [], $hn)->assertStatus(403);
        $this->postJson('/api/pod/noi-giao/' . $ngA, ['ten' => 'Nhà NV', 'dia_chi' => 'Nhà riêng'], $hn)->assertStatus(403);
        $this->postJson('/api/pod/noi-giao/' . $ngA . '/mac-dinh', [], $hn)->assertStatus(403);
        $this->assertSame('Hà Nội', PodNoiGiao::find($ngA)->dia_chi);
        $this->postJson('/api/pod/noi-giao', ['ten' => 'Line NV thêm', 'dia_chi' => 'HCM'], $hn)->assertOk();   // thêm thì được
        $donChu = PodSo::datDon($this->a, [['bien_the_id' => $this->bt($this->chung), 'so_luong' => 1]], null, null, null, null);
        $this->postJson("/api/pod/don/{$donChu->ma}/huy", [], $hn)->assertStatus(403);
        $this->assertNotSame('da_huy', $donChu->fresh()->trang_thai);
        $this->assertFalse(collect($this->getJson('/api/pod/don', $hn)->json('don'))->firstWhere('ma', $donChu->ma)['cua_toi']);
        // nhân viên của B: không thấy mẫu riêng, đơn, nơi giao của A
        $hb = $this->token($this->b);
        $this->postJson('/api/pod/nhan-vien', ['ten' => 'Minh', 'sdt' => '0911444555', 'matkhau' => 'nhanvien2', 'mk_chu' => 'matkhau1'], $hb)->assertOk();
        $hbn = $this->dangNhap('0911444555', 'nhanvien2');
        $this->postJson('/api/pod/dong-y', [], $hbn)->assertOk();
        $this->getJson('/api/pod/mau/' . $this->riengA->ma, $hbn)->assertNotFound();
        $this->assertCount(0, $this->getJson('/api/pod/don', $hbn)->json('don'));
        $this->assertCount(0, $this->getJson('/api/pod/noi-giao', $hbn)->json('noi_giao'));
        // admin thấy nhân viên và người đặt
        $admin = $this->withSession(['admin_logged_in' => true]);
        $admin->get('/admin/3d/pod/seller')->assertOk()->assertSee('Lan')->assertSee('0911222333');
        $admin->get('/admin/3d/pod/don/' . $don->id)->assertOk()->assertSee('Người đặt: nhân viên Lan');
    }

    public function test_nhan_vien_sdt_khong_trung_khoa_thi_mat_phien_xoa_khi_co_don_thi_khoa(): void
    {
        $h = $this->token($this->a);
        $this->postJson('/api/pod/nhan-vien', ['ten' => 'X', 'sdt' => '0900000002', 'matkhau' => 'nhanvien1', 'mk_chu' => 'matkhau1'], $h)->assertStatus(422);   // số của seller B
        $id = $this->postJson('/api/pod/nhan-vien', ['ten' => 'Lan', 'sdt' => '0911222333', 'matkhau' => 'nhanvien1', 'mk_chu' => 'matkhau1'], $h)->assertOk()->json('id');
        $this->postJson('/api/pod/nhan-vien', ['ten' => 'Y', 'sdt' => '0911222333', 'matkhau' => 'nhanvien1', 'mk_chu' => 'matkhau1'], $this->token($this->b))->assertStatus(422);
        $goc = 'http://' . config('pod.domain');
        $this->post($goc . '/dang-ky', ['ten' => 'Shop', 'sdt' => '0911222333', 'matkhau' => 'matkhau7', 'matkhau_confirmation' => 'matkhau7', 'dong_y' => '1'])->assertSessionHasErrors('sdt');
        $this->assertSame(0, PodSeller::where('sdt', '0911222333')->count());
        $hn = $this->dangNhap('0911222333', 'nhanvien1');
        $this->postJson('/api/pod/nhan-vien/' . $id, ['hien' => false], $this->token($this->b))->assertNotFound();
        $this->deleteJson('/api/pod/nhan-vien/' . $id, [], $this->token($this->b))->assertNotFound();
        $this->getJson('/api/pod/toi', $hn)->assertOk();
        // chủ shop khoá -> mất phiên, đăng nhập báo bị khoá
        $this->postJson('/api/pod/nhan-vien/' . $id, ['hien' => false], $h)->assertOk();
        $this->getJson('/api/pod/toi', $hn)->assertStatus(401);
        $this->postJson('/api/pod/dang-nhap', ['sdt' => '0911222333', 'matkhau' => 'nhanvien1'])->assertStatus(403);
        // mở + đặt lại mật khẩu
        $this->postJson('/api/pod/nhan-vien/' . $id, ['hien' => true, 'matkhau' => 'moimoi1', 'mk_chu' => 'matkhau1'], $h)->assertOk();
        $hn = $this->dangNhap('0911222333', 'moimoi1');
        // xưởng khoá shop -> nhân viên cũng mất phiên
        $this->a->update(['hien' => false]);
        $this->getJson('/api/pod/toi', $hn)->assertStatus(401);
        $this->postJson('/api/pod/dang-nhap', ['sdt' => '0911222333', 'matkhau' => 'moimoi1'])->assertStatus(403);
        $this->a->update(['hien' => true]);
        $hn = $this->dangNhap('0911222333', 'moimoi1');
        // xưởng đổi mật khẩu chủ shop: chỉ máy của chủ shop bị đăng xuất
        $this->withSession(['admin_logged_in' => true])->put('/admin/3d/pod/seller/' . $this->a->id, ['ten' => $this->a->ten, 'sdt' => $this->a->sdt, 'matkhau' => 'xuongdat1'])->assertSessionHasNoErrors();
        $this->getJson('/api/pod/toi', $h)->assertStatus(401);
        $this->getJson('/api/pod/toi', $hn)->assertOk();
        // đã có đơn -> xoá thành khoá, giữ tên
        PodSo::datDon($this->a, [['bien_the_id' => $this->bt($this->chung), 'so_luong' => 1]], null, null, null, null)->update(['nhan_vien_id' => $id]);
        $h = $this->token($this->a);
        $this->deleteJson('/api/pod/nhan-vien/' . $id, [], $h)->assertOk()->assertJson(['da_xoa' => true, 'giu_ten' => true]);
        $this->assertFalse(PodNhanVien::find($id)->hien);
        $this->assertSame('#' . $id, PodNhanVien::find($id)->sdt);           // số được giải phóng
        $this->getJson('/api/pod/toi', $hn)->assertStatus(401);
        $this->postJson('/api/pod/dang-nhap', ['sdt' => '0911222333', 'matkhau' => 'moimoi1'])->assertStatus(401);
        $this->assertSame('Lan', collect($this->getJson('/api/pod/don', $h)->json('don'))->first()['nguoi_dat']);   // tên vẫn trên đơn
        $this->assertCount(0, $this->getJson('/api/pod/nhan-vien', $h)->json('nhan_vien'));                         // không còn trong danh sách
        $this->deleteJson('/api/pod/nhan-vien/' . $id, [], $h)->assertNotFound();
        // shop khác dùng lại được số đó
        $this->postJson('/api/pod/nhan-vien', ['ten' => 'Lan B', 'sdt' => '0911222333', 'matkhau' => 'nhanvien1', 'mk_chu' => 'matkhau1'], $this->token($this->b))->assertOk();
        // chưa có đơn -> xoá hẳn
        $id2 = $this->postJson('/api/pod/nhan-vien', ['ten' => 'Tạm', 'sdt' => '0911777888', 'matkhau' => 'nhanvien3', 'mk_chu' => 'xuongdat1'], $h)->assertOk()->json('id');
        $this->deleteJson('/api/pod/nhan-vien/' . $id2, [], $h)->assertOk()->assertJson(['da_xoa' => true]);
        $this->assertNull(PodNhanVien::find($id2));
    }

    public function test_nhan_vien_dong_y_dieu_khoan_rieng(): void
    {
        $nv = PodNhanVien::create(['seller_id' => $this->a->id, 'ten' => 'Lan', 'sdt' => '0911222333', 'matkhau' => Hash::make('nhanvien1'), 'hien' => true]);
        $hn = $this->dangNhap('0911222333', 'nhanvien1');
        $this->getJson('/api/pod/toi', $hn)->assertOk()->assertJson(['dong_y' => false]);
        $this->getJson('/api/pod/mau', $hn)->assertStatus(403)->assertJson(['can_dong_y' => true]);
        $this->postJson('/api/pod/dong-y', [], $hn)->assertOk();
        $this->getJson('/api/pod/mau', $hn)->assertOk();
        $this->assertNotNull($nv->fresh()->dong_y_luc);
    }

    public function test_noi_giao_sua_mac_dinh_va_giu_kin(): void
    {
        $h = $this->token($this->a);
        $id1 = $this->postJson('/api/pod/noi-giao', ['ten' => 'Line 1', 'dia_chi' => 'HCM'], $h)->assertOk()->json('id');
        $id2 = $this->postJson('/api/pod/noi-giao', ['ten' => 'Line 2', 'dia_chi' => 'HN'], $h)->assertOk()->json('id');
        $this->assertSame($id1, $this->getJson('/api/pod/noi-giao', $h)->json('mac_dinh'));   // nơi đầu tiên thành mặc định
        $this->postJson("/api/pod/noi-giao/$id2/mac-dinh", [], $h)->assertOk();
        $this->assertSame($id2, $this->getJson('/api/pod/noi-giao', $h)->json('mac_dinh'));
        $this->postJson("/api/pod/noi-giao/$id1", ['ten' => 'Line 1 mới', 'dia_chi' => 'Q.Tân Bình'], $h)->assertOk();
        $this->assertSame('Line 1 mới', PodNoiGiao::find($id1)->ten);
        $hb = $this->token($this->b);
        $this->postJson("/api/pod/noi-giao/$id1", ['ten' => 'Hack', 'dia_chi' => 'x'], $hb)->assertNotFound();
        $this->postJson("/api/pod/noi-giao/$id1/mac-dinh", [], $hb)->assertNotFound();
        $this->deleteJson("/api/pod/noi-giao/$id1", [], $hb)->assertOk();             // không xoá được của người khác
        $this->assertSame('Line 1 mới', PodNoiGiao::find($id1)->ten);
        $this->assertNull($this->getJson('/api/pod/noi-giao', $hb)->json('mac_dinh'));
        $this->deleteJson("/api/pod/noi-giao/$id2", [], $h)->assertOk();
        $this->assertNull($this->getJson('/api/pod/noi-giao', $h)->json('mac_dinh'));
    }

    public function test_vi_loc_theo_thang_tong_vao_ra_va_so_du_sau(): void
    {
        $this->travelTo(\Illuminate\Support\Carbon::create(2026, 10, 15, 10));
        PodSo::napTien($this->a, 100000, 'nap', 'T10');
        PodSo::datDon($this->a, [['bien_the_id' => $this->bt($this->chung), 'so_luong' => 1]], null, null, null, null);   // −24.440
        $this->travelTo(\Illuminate\Support\Carbon::create(2026, 11, 3, 9));
        PodSo::napTien($this->a, 50000, 'nap', 'T11');
        $h = $this->token($this->a);
        $all = $this->getJson('/api/pod/vi', $h)->assertOk();
        $this->assertSame(125560, $all->json('so_du'));
        $this->assertSame(125560, $all->json('lich_su.0.so_du_sau'));   // dòng mới nhất = số dư hiện tại
        $this->assertSame(100000, $all->json('lich_su.2.so_du_sau'));
        $this->assertSame(['2026-11', '2026-10'], $all->json('cac_thang'));
        $t10 = $this->getJson('/api/pod/vi?thang=2026-10', $h)->assertOk();
        $this->assertSame(['thang' => '2026-10', 'dau_ky' => 0, 'cuoi_ky' => 75560, 'tong_vao' => 100000, 'tong_ra' => 24440, 'so_dong' => 2], $t10->json('thong_ke'));
        $this->assertSame(75560, $t10->json('lich_su.0.so_du_sau'));
        $t11 = $this->getJson('/api/pod/vi?thang=2026-11', $h)->assertOk();
        $this->assertSame(75560, $t11->json('thong_ke.dau_ky'));
        $this->assertSame(125560, $t11->json('thong_ke.cuoi_ky'));
        $this->assertNull($this->getJson('/api/pod/vi?thang=2026-13', $h)->assertOk()->json('thong_ke'));   // tháng sai -> như không lọc
        $this->getJson('/api/pod/vi?thang[]=2026-10', $h)->assertOk();                                            // trước đây lỗi 500
        $this->travelBack();
    }

    public function test_gui_don_khong_bi_chan_vi_da_goi_nhieu_api_khac(): void
    {
        // throttle:30,1 thiếu tham số bộ đếm riêng thì đếm CHUNG với throttle:120 của nhóm -> lần gọi thứ 31 bất kỳ chặn gửi đơn
        $h = $this->token($this->a);
        for ($i = 0; $i < 35; $i++) $this->getJson('/api/pod/toi', $h)->assertOk();
        $this->postJson('/api/pod/don', ['dong' => [['bien_the_id' => $this->bt($this->chung), 'so_luong' => 1]], 'noi_giao_id' => $this->noiGiao($this->a)], $h)->assertOk();
    }

    public function test_tao_va_dat_lai_mat_khau_nhan_vien_phai_nhap_mat_khau_chu_shop(): void
    {
        $h = $this->token($this->a);   // chỉ cầm phiên (máy dùng chung), không biết mật khẩu chủ
        $this->postJson('/api/pod/nhan-vien', ['ten' => 'Cửa hậu', 'sdt' => '0911222333', 'matkhau' => 'nhanvien1'], $h)->assertStatus(422);
        $this->postJson('/api/pod/nhan-vien', ['ten' => 'Cửa hậu', 'sdt' => '0911222333', 'matkhau' => 'nhanvien1', 'mk_chu' => 'doan-sai'], $h)->assertStatus(422);
        $this->assertSame(0, PodNhanVien::count());
        $id = $this->postJson('/api/pod/nhan-vien', ['ten' => 'Lan', 'sdt' => '0911222333', 'matkhau' => 'nhanvien1', 'mk_chu' => 'matkhau1'], $h)->assertOk()->json('id');
        $this->postJson('/api/pod/nhan-vien/' . $id, ['matkhau' => 'chiemquyen'], $h)->assertStatus(422);
        $this->postJson('/api/pod/dang-nhap', ['sdt' => '0911222333', 'matkhau' => 'nhanvien1'])->assertOk();
        $this->postJson('/api/pod/nhan-vien/' . $id, ['hien' => false], $h)->assertOk();   // khoá thì không cần
        // đã xoá không tính vào giới hạn
        for ($i = 0; $i < PodNhanVien::TOI_DA; $i++) {
            PodNhanVien::create(['seller_id' => $this->a->id, 'ten' => 'Cũ ' . $i, 'sdt' => '#x' . $i, 'matkhau' => 'x', 'hien' => false]);
        }
        $this->postJson('/api/pod/nhan-vien', ['ten' => 'Mới', 'sdt' => '0911333444', 'matkhau' => 'nhanvien1', 'mk_chu' => 'matkhau1'], $h)->assertOk();
    }

    public function test_dang_nhap_dung_tai_khoan_khac_khong_xoa_bo_dem_sai_cua_so_bi_do(): void
    {
        $this->taoNv($this->a);
        for ($i = 0; $i < 8; $i++) $this->postJson('/api/pod/dang-nhap', ['sdt' => '0900000002', 'matkhau' => 'doan' . $i])->assertStatus(401);
        $this->postJson('/api/pod/dang-nhap', ['sdt' => '0911222333', 'matkhau' => 'nhanvien1'])->assertOk();   // đăng nhập đúng tài khoản của mình
        $this->postJson('/api/pod/dang-nhap', ['sdt' => '0900000002', 'matkhau' => 'matkhau1'])->assertStatus(429);   // số bị dò vẫn bị chặn
        $this->postJson('/api/pod/dang-nhap', ['sdt' => '0900000001', 'matkhau' => 'matkhau1'])->assertOk();       // người khác không bị vạ lây
    }

    public function test_trang_cong_co_muc_tai_khoan_moi(): void
    {
        $this->get('http://' . config('pod.domain') . '/cong')->assertOk()
            ->assertSee('data-t="hoso"', false)->assertSee('data-t="nhanvien"', false)->assertSee('Quên mật khẩu? Nhắn xưởng qua Zalo');
    }

    public function test_gia_dinh_nhieu_o_kiem_be_ngang_theo_co_va_file_in(): void
    {
        // ornament gia đình tuần lộc: 7 ô "Bố / Mẹ / Con / Con / Con / Họ / Năm", tên dài ở cỡ nhỏ bị từ chối như app Thay tên
        $m = $this->taoMau('Ornament gia đình tuần lộc', null, 29.9, 'PLA');
        $btS = $m->bienThe()->first();
        $btS->update(['ten' => 'S · 3.9 in']);
        $btL = PodBienThe::create(['mau_id' => $m->id, 'ten' => 'L · 5.0 in', 'gram' => 47.8, 'chat_lieu' => 'PETG']);
        $g = [];
        foreach (str_split("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'-.&") as $c) $g[$c] = ['a' => 5, 'l' => 0.3, 'r' => 4.7];
        $g[' '] = ['a' => 2.5];
        $o = fn ($rong) => ['rong' => $rong, 'cap_max' => 4.0, 'min' => 3.4];
        $coS = ['bo' => $o(16), 'me' => $o(16), 'con1' => $o(14), 'con2' => $o(14), 'con3' => $o(14), 'nam' => ['rong' => 40, 'cap_max' => 5.6, 'min' => 3.6]];
        $coL = array_map(fn ($s) => ['rong' => $s['rong'] * 1.6] + $s, $coS);
        $onhap = [];
        foreach (['bo' => 'Tên bố', 'me' => 'Tên mẹ', 'con1' => 'Tên con 1', 'con2' => 'Tên con 2', 'con3' => 'Tên con 3', 'ho' => 'Dòng họ', 'nam' => 'Năm'] as $k => $n)
            $onhap[] = ['k' => $k, 'nhan' => $n, 'goi_y' => 'X'];
        $m->update(['xem_truoc' => ['loai' => 'ten', 've' => 'gia_dinh', 'toi_da' => 120, 'w' => 800, 'h' => 820, 'nen' => 'data:image/jpeg;base64,AA==',
            'o_nhap' => $onhap, 'o' => [], 'bang' => ['cap10' => 7.0, 'g' => $g, 'kern' => ['AV' => -0.8], 'co' => ['S · 3.9 in' => $coS, 'L · 5.0 in' => $coL],
                'toi_ky_tu' => ['bo' => 9, 'me' => 9, 'con1' => 9, 'con2' => 9, 'con3' => 9, 'ho' => 22, 'nam' => 6]],
            'thay_ten' => ['mau' => 'ornament_tuan_loc', 'o_ten' => 'ho', 'theo_nhua' => true,
                'co' => ['S · 3.9 in' => ['co' => 'S'], 'L · 5.0 in' => ['co' => 'L']]]]]);
        $m = $m->fresh();
        $this->assertTrue($m->laGiaDinh());
        $this->assertFalse($m->laThanh());
        $chuan = 'Dad / Mom / Liam / Emma / Noah / The Smith Family / 2026';
        $this->assertSame($chuan, $m->chuanGiaDinh(' Dad /Mom/ Liam  / Emma/Noah / The   Smith Family /2026', 'S · 3.9 in'));
        $this->assertSame('Grandpa / Mom / Liam / Emma / Noah / The Smith Family / 2026',
            $m->chuanGiaDinh('Grandpa / Mom / Liam / Emma / Noah / The Smith Family / 2026', 'L · 5.0 in'), 'Cỡ L đủ chỗ cho 7 chữ');
        foreach ([
            ['Grandpa / Mom / Liam / Emma / Noah / The Smith Family / 2026', 'dài quá ở cỡ S'],     // 7 chữ: chữ hoa 16·7/34,4 = 3,26 < 3,4
            ['Dad / Mom / Liam / Emma / / The Smith Family / 2026', 'chưa nhập Tên con 3'],
            ['Dad / Mom / Liam / Emma / Noah / The Smith Family', 'chưa nhập Năm'],
            ['Dad / Mom / Ngọc / Emma / Noah / The Smith Family / 2026', 'chỉ nhận chữ tiếng Anh'],
            ['Dad / Mom / Bartholomew / Emma / Noah / The Smith Family / 2026', 'tối đa 9'],
            ['Dad / Mom / Liam / Emma / Noah / The Smith Family / 2026 / Thêm', 'dấu /'],
        ] as [$sai, $loi]) {
            try {
                $m->chuanGiaDinh($sai, 'S · 3.9 in');
                $this->fail("Phải từ chối: $sai");
            } catch (\InvalidArgumentException $e) {
                $this->assertStringContainsString($loi, $e->getMessage(), $sai);
            }
        }
        // đặt đơn: chặn theo CỠ của phân loại (trình duyệt lọt vẫn chặn), lưu chuỗi đã chuẩn
        try {
            PodSo::datDon($this->a, [['bien_the_id' => $btS->id, 'so_luong' => 1, 'chu' => 'Grandpa / Mom / Liam / Emma / Noah / The Smith Family / 2026']], null, null, null, null);
            $this->fail('Phải chặn tên dài ở cỡ S');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertStringContainsString('dài quá ở cỡ S', collect($e->errors())->flatten()->first());
        }
        $don = PodSo::datDon($this->a, [['bien_the_id' => $btL->id, 'so_luong' => 1, 'chu' => 'Grandpa/Mom/Liam/Emma/Noah/The Smith Family/2026']], null, null, null, null);
        $dong = $don->dong()->first();
        $this->assertSame('Grandpa / Mom / Liam / Emma / Noah / The Smith Family / 2026', $dong->chu_ca_nhan);
        // tham số app: họ -> ten, các ô còn lại -> tuỳ chọn, nhựa theo chất liệu của dòng
        $this->assertSame(['mau' => 'ornament_tuan_loc', 'ten' => 'The Smith Family',
            'tuy_chon' => ['co' => 'L', 'bo' => 'Grandpa', 'me' => 'Mom', 'con1' => 'Liam', 'con2' => 'Emma', 'con3' => 'Noah', 'nam' => '2026', 'nhua' => 'PETG']],
            $m->thamSoFileIn($dong->ten_bien_the, $dong->chu_ca_nhan, null, $dong->chat_lieu));
        $this->assertNull($m->thamSoFileIn($dong->ten_bien_the, $dong->chu_ca_nhan), 'Thiếu chất liệu: không đoán nhựa');
        $this->withSession(['admin_logged_in' => true])->get(route('admin.pod.don.show', $don))->assertOk()
            ->assertSee('&quot;nhua&quot;:&quot;PETG&quot;', false)->assertSee('&quot;con3&quot;:&quot;Noah&quot;', false);
        // trang công khai: 7 ô nhập + chỗ báo lỗi
        $this->get('http://' . config('pod.domain') . '/kho-mau/' . $m->ma)->assertOk()
            ->assertSee('data-gd="con3"', false)->assertSee('id="loiXt"', false)->assertDontSee('id="tenXt"', false);
    }
}
