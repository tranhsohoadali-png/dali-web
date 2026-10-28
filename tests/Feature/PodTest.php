<?php

namespace Tests\Feature;

use App\Models\PodBienThe;
use App\Models\PodDon;
use App\Models\PodMau;
use App\Models\PodNhatKy;
use App\Models\PodNoiGiao;
use App\Models\PodPhien;
use App\Models\PodSeller;
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

    private function taoMau(string $ten, ?int $chu, float $gram): PodMau
    {
        $ma = PodMau::taoMa();
        $rel = "pod/anh/$ma-1.png";
        Storage::disk('local')->put($rel, 'png');
        $m = PodMau::create(['ma' => $ma, 'ten' => $ten, 'chu_seller_id' => $chu, 'hien' => true, 'anh' => [$rel], 'phi_ca_nhan' => 5000]);
        PodBienThe::create(['mau_id' => $m->id, 'ten' => 'Cỡ gốc', 'gram' => $gram]);
        return $m;
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

    public function test_trang_cong_gan_noindex(): void
    {
        $this->get('/pod')->assertOk()->assertHeader('X-Robots-Tag', 'noindex, nofollow');
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
}
