<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DbBackup extends Command
{
    protected $signature = 'dali:backup {--keep=14 : Số bản backup giữ lại (xoá bản cũ hơn)}';
    protected $description = 'Sao lưu database ra file .sql.gz (hỗ trợ SQLite + MySQL)';

    public function handle(): int
    {
        $dir = storage_path('backups');
        if (!is_dir($dir)) @mkdir($dir, 0775, true);

        $conn   = config('database.default');
        $driver = config("database.connections.$conn.driver");
        $stamp  = date('Y-m-d_His');
        $file   = "$dir/dali_{$stamp}.sql.gz";

        $this->info("Driver: $driver → $file");

        try {
            if ($driver === 'sqlite') {
                $this->dumpSqlite($file);
            } elseif ($driver === 'mysql') {
                $this->dumpMysql($conn, $file);
            } else {
                $this->error("Chưa hỗ trợ driver: $driver");
                return self::FAILURE;
            }
        } catch (\Throwable $e) {
            $this->error('Lỗi backup: ' . $e->getMessage());
            return self::FAILURE;
        }

        $size = is_file($file) ? round(filesize($file) / 1024, 1) . ' KB' : '?';
        $this->info("✓ Đã tạo backup: " . basename($file) . " ($size)");

        $this->rotate($dir, (int) $this->option('keep'));
        return self::SUCCESS;
    }

    /** Dump SQLite sang SQL bằng PDO (không cần lệnh sqlite3). */
    private function dumpSqlite(string $file): void
    {
        $pdo = DB::connection()->getPdo();
        $gz  = gzopen($file, 'wb9');
        if (!$gz) throw new \RuntimeException('Không mở được file gzip.');

        gzwrite($gz, "-- DALI backup (SQLite) " . date('c') . "\nPRAGMA foreign_keys=OFF;\nBEGIN TRANSACTION;\n");

        // Bảng (schema + dữ liệu)
        $tables = $pdo->query("SELECT name, sql FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name")
            ->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($tables as $t) {
            $name = $t['name'];
            gzwrite($gz, "\nDROP TABLE IF EXISTS \"$name\";\n" . $t['sql'] . ";\n");
            $rows = $pdo->query("SELECT * FROM \"$name\"")->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $cols = implode(',', array_map(fn ($c) => "\"$c\"", array_keys($row)));
                $vals = implode(',', array_map(fn ($v) => $v === null ? 'NULL' : $pdo->quote((string) $v), array_values($row)));
                gzwrite($gz, "INSERT INTO \"$name\" ($cols) VALUES ($vals);\n");
            }
        }
        // Index + trigger
        $extra = $pdo->query("SELECT sql FROM sqlite_master WHERE type IN ('index','trigger') AND sql IS NOT NULL")
            ->fetchAll(\PDO::FETCH_COLUMN);
        foreach ($extra as $sql) gzwrite($gz, $sql . ";\n");

        gzwrite($gz, "COMMIT;\nPRAGMA foreign_keys=ON;\n");
        gzclose($gz);
    }

    /** Dump MySQL bằng mysqldump | gzip (chạy trên server). */
    private function dumpMysql(string $conn, string $file): void
    {
        $c = config("database.connections.$conn");
        // File defaults tạm để không lộ mật khẩu trên process list
        $cnf = tempnam(sys_get_temp_dir(), 'mycnf');
        file_put_contents($cnf, "[client]\nuser=\"{$c['username']}\"\npassword=\"{$c['password']}\"\nhost=\"{$c['host']}\"\nport=\"{$c['port']}\"\n");

        $cmd = sprintf(
            'mysqldump --defaults-extra-file=%s --no-tablespaces --single-transaction --quick --skip-lock-tables %s | gzip > %s',
            escapeshellarg($cnf),
            escapeshellarg($c['database']),
            escapeshellarg($file)
        );
        exec($cmd . ' 2>&1', $out, $code);
        @unlink($cnf);
        if ($code !== 0) {
            throw new \RuntimeException("mysqldump lỗi (code $code): " . implode("\n", $out));
        }
    }

    /** Giữ N bản mới nhất, xoá bản cũ. */
    private function rotate(string $dir, int $keep): void
    {
        if ($keep <= 0) return;
        $files = glob("$dir/dali_*.sql.gz");
        usort($files, fn ($a, $b) => filemtime($b) <=> filemtime($a));
        foreach (array_slice($files, $keep) as $old) {
            @unlink($old);
            $this->line('  Xoá bản cũ: ' . basename($old));
        }
    }
}
