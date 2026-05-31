<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Size;
use App\Models\Product;
use App\Models\Affiliate;
use App\Models\Post;

class GenerateSeed extends Command
{
    protected $signature   = 'dali:generate-seed {--out=database/seeders/ProductionSeeder.php}';
    protected $description = 'Xuất toàn bộ dữ liệu hiện tại thành file Seeder để deploy lên hosting';

    public function handle(): void
    {
        $out = base_path($this->option('out'));

        $cats     = Category::all();
        $sizes    = Size::orderBy('sort_order')->get();
        $products = Product::with('category')->orderBy('id')->get();
        $settings = DB::table('admin_settings')->get();
        $affs     = Affiliate::all();
        $posts    = Post::orderBy('id')->get();

        $php  = "<?php\n";
        $php .= "namespace Database\\Seeders;\n\n";
        $php .= "use Illuminate\\Database\\Seeder;\n";
        $php .= "use Illuminate\\Support\\Facades\\DB;\n";
        $php .= "use Illuminate\\Support\\Facades\\Hash;\n\n";
        $php .= "class ProductionSeeder extends Seeder\n{\n";
        $php .= "    public function run(): void\n    {\n";
        $php .= "        \$now = now();\n\n";

        // ── SETTINGS ──────────────────────────────
        $php .= "        // ── Cài đặt ──\n";
        $php .= "        DB::table('admin_settings')->truncate();\n";
        $php .= "        DB::table('admin_settings')->insert([\n";
        foreach ($settings as $s) {
            $v = addslashes($s->value ?? '');
            $php .= "            ['key'=>'{$s->key}','value'=>'{$v}','created_at'=>\$now,'updated_at'=>\$now],\n";
        }
        $php .= "        ]);\n\n";

        // ── CATEGORIES ────────────────────────────
        $php .= "        // ── Danh mục ──\n";
        $php .= "        DB::table('categories')->truncate();\n";
        $php .= "        DB::table('categories')->insert([\n";
        foreach ($cats as $c) {
            $name = addslashes($c->name);
            $slug = addslashes($c->slug);
            $desc = addslashes($c->description ?? '');
            $img  = addslashes($c->image ?? '');
            $act  = $c->is_active ? 'true' : 'false';
            $php .= "            ['id'=>{$c->id},'name'=>'{$name}','slug'=>'{$slug}','description'=>'{$desc}','image'=>'{$img}','is_active'=>{$act},'created_at'=>\$now,'updated_at'=>\$now],\n";
        }
        $php .= "        ]);\n\n";

        // ── SIZES ─────────────────────────────────
        $php .= "        // ── Kích thước & giá ──\n";
        $php .= "        DB::table('sizes')->truncate();\n";
        $php .= "        DB::table('sizes')->insert([\n";
        foreach ($sizes as $s) {
            $name = addslashes($s->name);
            $note = addslashes($s->note ?? '');
            $act  = $s->is_active ? 'true' : 'false';
            $php .= "            ['id'=>{$s->id},'name'=>'{$name}','note'=>'{$note}','price'=>{$s->price},'sort_order'=>{$s->sort_order},'is_active'=>{$act},'created_at'=>\$now,'updated_at'=>\$now],\n";
        }
        $php .= "        ]);\n\n";

        // ── PRODUCTS ──────────────────────────────
        $php .= "        // ── Sản phẩm ({$products->count()} tranh) ──\n";
        $php .= "        DB::table('products')->truncate();\n";
        $php .= "        DB::table('products')->insert([\n";
        foreach ($products as $p) {
            $name  = addslashes($p->name);
            $slug  = addslashes($p->slug);
            $desc  = addslashes($p->description ?? '');
            $img   = addslashes($p->main_image ?? '');
            $sizes = json_encode($p->size_ids ?? []);
            $act   = $p->is_active ? 'true' : 'false';

            $php .= "            ['id'=>{$p->id},'category_id'=>{$p->category_id},'name'=>'{$name}','slug'=>'{$slug}',"
                 .  "'description'=>'{$desc}','main_image'=>'{$img}','price'=>{$p->price},'colors_count'=>{$p->colors_count},"
                 .  "'size_ids'=>'{$sizes}','is_active'=>{$act},'sort_order'=>{$p->sort_order},"
                 .  "'sold_count'=>{$p->sold_count},'created_at'=>\$now,'updated_at'=>\$now],\n";
        }
        $php .= "        ]);\n\n";

        // ── AFFILIATES ────────────────────────────
        $php .= "        // ── Cộng tác viên ──\n";
        $php .= "        DB::table('affiliates')->truncate();\n";
        if ($affs->count()) {
            $php .= "        DB::table('affiliates')->insert([\n";
            foreach ($affs as $a) {
                $name  = addslashes($a->name);
                $phone = addslashes($a->phone ?? '');
                $email = addslashes($a->email ?? '');
                $code  = addslashes($a->code);
                $bname = addslashes($a->bank_name ?? '');
                $bacc  = addslashes($a->bank_acc ?? '');
                $bown  = addslashes($a->bank_owner ?? '');
                $note  = addslashes($a->note ?? '');
                $act   = $a->is_active ? 'true' : 'false';
                // Password giữ nguyên hash
                $pass  = addslashes($a->password ?? '');
                $php .= "            ['name'=>'{$name}','phone'=>'{$phone}','email'=>'{$email}','code'=>'{$code}',"
                     .  "'password'=>'{$pass}','commission_rate'=>{$a->commission_rate},"
                     .  "'total_earned'=>0,'total_paid'=>0,'total_orders'=>0,"
                     .  "'bank_name'=>'{$bname}','bank_acc'=>'{$bacc}','bank_owner'=>'{$bown}',"
                     .  "'is_active'=>{$act},'note'=>'{$note}','created_at'=>\$now,'updated_at'=>\$now],\n";
            }
            $php .= "        ]);\n";
        }

        // ── POSTS (Blog) ──────────────────────────
        $php .= "\n        // ── Blog ({$posts->count()} bài) ──\n";
        $php .= "        DB::table('posts')->truncate();\n";
        if ($posts->count()) {
            $php .= "        DB::table('posts')->insert([\n";
            foreach ($posts as $p) {
                // base64 cho các trường HTML/text dài để tránh lỗi escape
                $title   = base64_encode($p->title ?? '');
                $excerpt = base64_encode($p->excerpt ?? '');
                $content = base64_encode($p->content ?? '');
                $mtitle  = base64_encode($p->meta_title ?? '');
                $mdesc   = base64_encode($p->meta_description ?? '');
                $slug    = addslashes($p->slug ?? '');
                $cover   = addslashes($p->cover_image ?? '');
                $cat     = addslashes($p->category ?? '');
                $pub     = $p->is_published ? 'true' : 'false';
                $views   = (int) ($p->view_count ?? 0);
                $sort    = (int) ($p->sort_order ?? 0);
                $pubAt   = $p->published_at ? "'".$p->published_at->format('Y-m-d H:i:s')."'" : 'null';
                $php .= "            ['title'=>base64_decode('{$title}'),'slug'=>'{$slug}',"
                     .  "'excerpt'=>base64_decode('{$excerpt}'),'content'=>base64_decode('{$content}'),"
                     .  "'cover_image'=>'{$cover}','category'=>'{$cat}',"
                     .  "'meta_title'=>base64_decode('{$mtitle}'),'meta_description'=>base64_decode('{$mdesc}'),"
                     .  "'is_published'=>{$pub},'view_count'=>{$views},'sort_order'=>{$sort},"
                     .  "'published_at'=>{$pubAt},'created_at'=>\$now,'updated_at'=>\$now],\n";
            }
            $php .= "        ]);\n";
        }

        $php .= "    }\n}\n";

        file_put_contents($out, $php);
        $this->info("✅ Seeder xuất ra: {$out}");
        $this->info("   Chạy trên hosting: php artisan db:seed --class=ProductionSeeder");
    }
}
