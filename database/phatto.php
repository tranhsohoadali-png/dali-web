<?php
use App\Models\Product;
use App\Models\Category;
use App\Models\Size;
use Illuminate\Support\Str;
ini_set('memory_limit','1024M');
$dir = public_path('storage/products');
foreach (glob($dir.'/phatto-P*.{jpg,jpeg,png}', GLOB_BRACE) as $f) {
    $info=@getimagesize($f); if(!$info) continue; [$w,$h,$type]=$info;
    if($w<=1000 && $h<=1000 && filesize($f)<400000) continue;
    $src=$type===IMAGETYPE_PNG?@imagecreatefrompng($f):@imagecreatefromjpeg($f); if(!$src) continue;
    $s=min(1,1000/max($w,$h)); $nw=(int)round($w*$s); $nh=(int)round($h*$s);
    $dst=imagecreatetruecolor($nw,$nh); imagefill($dst,0,0,imagecolorallocate($dst,255,255,255));
    imagecopyresampled($dst,$src,0,0,0,0,$nw,$nh,$w,$h);
    $type===IMAGETYPE_PNG?imagepng($dst,$f,6):imagejpeg($dst,$f,85);
    imagedestroy($src); imagedestroy($dst);
}
echo "Resize xong\n";
$cat=Category::where('slug','phat-lon')->first();
$rect=[5,6,7,8,9]; $sq=[4];
$priceRect=(int)Size::whereIn('id',$rect)->min('price'); $priceSq=(int)Size::whereIn('id',$sq)->min('price');
$map=json_decode(file_get_contents('database/phatto_map.json'), true);
$added=0;
foreach($map as $code=>$row){
    [$desc,$colors,$shape]=$row;
    $found=glob($dir."/phatto-{$code}.*"); if(!$found){echo "? {$code}\n"; continue;}
    $rel='products/'.basename($found[0]); $name="Tranh {$desc} {$code}";
    if(Product::where('name',$name)->exists()) continue;
    $sizeIds=$shape==='s'?$sq:$rect; $price=$shape==='s'?$priceSq:$priceRect;
    Product::create(['category_id'=>$cat->id,'name'=>$name,'slug'=>Str::slug($name).'-'.strtolower($code).'-'.substr(md5('to'.$code),0,6),'description'=>"Tranh tô màu số Phật giáo khổ lớn - {$desc} (mã {$code}). {$colors} màu, khổ từ 40×50cm, trang nghiêm cho phòng thờ, phòng khách.",'main_image'=>$rel,'price'=>$price,'colors_count'=>$colors,'size_ids'=>$sizeIds,'is_active'=>true,'sort_order'=>0,'sold_count'=>0]);
    $added++;
}
echo "DONE: thêm {$added} / Tổng: ".Product::where('category_id',$cat->id)->count()."\n";
