<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

/**
 * Cập nhật HÀNG LOẠT mô tả sản phẩm từ một file JSON.
 *
 * Lý do có lệnh này: ~500 trang sản phẩm dùng chung một khuôn mô tả ~70 ký tự
 * ("Tranh tô màu số hóa DALI – {tên}. {N} màu sắc tinh tế.") nên Google xếp vào
 * nhóm trang mỏng/trùng lặp và KHÔNG lập chỉ mục (Search Console: 621 trang
 * "Đã phát hiện – hiện chưa được lập chỉ mục").
 *
 * An toàn: LUÔN sao lưu mô tả cũ ra file JSON trước khi ghi, và có --restore
 * để trả lại nguyên trạng nếu cần.
 */
class EnrichProductDescriptions extends Command
{
    protected $signature = 'dali:enrich-descriptions
                            {--file= : File JSON chứa mô tả mới, dạng [{"slug":"...","description":"..."}]}
                            {--dry-run : Chỉ xem trước, KHÔNG ghi vào database}
                            {--restore= : Khôi phục mô tả từ file sao lưu đã tạo trước đó}';

    protected $description = 'Cập nhật mô tả sản phẩm hàng loạt từ file JSON (tự sao lưu mô tả cũ)';

    public function handle(): int
    {
        if ($path = $this->option('restore')) {
            return $this->restore($path);
        }

        $file = $this->option('file') ?: storage_path('app/mo-ta-moi.json');
        if (!is_file($file)) {
            $this->error("Không thấy file: $file");
            return self::FAILURE;
        }

        $rows = json_decode((string) file_get_contents($file), true);
        if (!is_array($rows) || $rows === []) {
            $this->error('File JSON rỗng hoặc sai định dạng.');
            return self::FAILURE;
        }

        $dry = (bool) $this->option('dry-run');
        $this->info(($dry ? '[XEM TRƯỚC] ' : '') . 'Đọc ' . count($rows) . ' mục từ ' . basename($file));

        // Gom sẵn các thay đổi để (1) sao lưu trọn gói, (2) đếm chính xác trước khi ghi
        $changes = [];
        $missing = [];
        $same    = 0;

        foreach ($rows as $r) {
            $slug = trim((string) ($r['slug'] ?? ''));
            $new  = trim((string) ($r['description'] ?? ''));
            if ($slug === '' || $new === '') continue;

            $product = Product::where('slug', $slug)->first();
            if (!$product) { $missing[] = $slug; continue; }

            if (trim((string) $product->description) === $new) { $same++; continue; }

            $changes[] = ['id' => $product->id, 'slug' => $slug, 'old' => (string) $product->description, 'new' => $new];
        }

        $this->line('');
        $this->line('  Sẽ cập nhật     : ' . count($changes));
        $this->line('  Đã giống sẵn    : ' . $same);
        $this->line('  KHÔNG tìm thấy  : ' . count($missing));
        if ($missing) {
            foreach (array_slice($missing, 0, 10) as $m) $this->line('     - ' . $m);
            if (count($missing) > 10) $this->line('     ... và ' . (count($missing) - 10) . ' mục nữa');
        }

        if ($changes === []) {
            $this->info('Không có gì để cập nhật.');
            return self::SUCCESS;
        }

        // Xem thử 2 mục đầu cho chắc ăn
        $this->line('');
        $this->line('── Ví dụ thay đổi ──');
        foreach (array_slice($changes, 0, 2) as $c) {
            $this->line('  ' . $c['slug']);
            $this->line('    CŨ  (' . mb_strlen($c['old']) . '): ' . mb_substr($c['old'], 0, 90));
            $this->line('    MỚI (' . mb_strlen($c['new']) . '): ' . mb_substr($c['new'], 0, 90) . '...');
        }

        if ($dry) {
            $this->line('');
            $this->info('Đây là bản xem trước — KHÔNG có gì được ghi vào database.');
            return self::SUCCESS;
        }

        // ── SAO LƯU TRƯỚC KHI GHI ──
        $dir = storage_path('backups');
        if (!is_dir($dir)) @mkdir($dir, 0775, true);
        $backup = $dir . '/mo-ta-cu_' . date('Y-m-d_His') . '.json';
        $dump   = array_map(fn ($c) => ['slug' => $c['slug'], 'description' => $c['old']], $changes);
        file_put_contents($backup, json_encode($dump, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        $this->info('✓ Đã sao lưu mô tả cũ: ' . basename($backup));

        $bar = $this->output->createProgressBar(count($changes));
        $bar->start();
        foreach ($changes as $c) {
            Product::where('id', $c['id'])->update(['description' => $c['new']]);
            $bar->advance();
        }
        $bar->finish();

        $this->line('');
        $this->info('✓ Đã cập nhật ' . count($changes) . ' mô tả sản phẩm.');
        $this->line('  Muốn hoàn tác: php artisan dali:enrich-descriptions --restore=' . $backup);
        return self::SUCCESS;
    }

    /** Ghi ngược mô tả từ file sao lưu. */
    private function restore(string $path): int
    {
        if (!is_file($path)) {
            $this->error("Không thấy file sao lưu: $path");
            return self::FAILURE;
        }
        $rows = json_decode((string) file_get_contents($path), true);
        if (!is_array($rows)) {
            $this->error('File sao lưu sai định dạng.');
            return self::FAILURE;
        }

        $n = 0;
        foreach ($rows as $r) {
            $slug = trim((string) ($r['slug'] ?? ''));
            if ($slug === '') continue;
            $n += Product::where('slug', $slug)->update(['description' => (string) ($r['description'] ?? '')]);
        }
        $this->info("✓ Đã khôi phục $n mô tả từ " . basename($path));
        return self::SUCCESS;
    }
}
