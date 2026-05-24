<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\News;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Смартфоны',  'slug' => 'smartphones'],
            ['name' => 'Ноутбуки',   'slug' => 'laptops'],
            ['name' => 'Планшеты',   'slug' => 'tablets'],
            ['name' => 'Наушники',   'slug' => 'headphones'],
            ['name' => 'Аксессуары', 'slug' => 'accessories'],
        ];
        foreach ($categories as $cat) Category::create($cat);

        $products = [
            ['name'=>'iPhone 15 Pro', 'price'=>129990, 'category_id'=>1, 'brand'=>'Apple', 'sku'=>'IPH15PRO', 'stock'=>15, 'description'=>'Titanium design, A17 Pro chip, 48MP camera system'],
            ['name'=>'Samsung Galaxy S24', 'price'=>89990, 'category_id'=>1, 'brand'=>'Samsung', 'sku'=>'SGS24', 'stock'=>20, 'description'=>'Snapdragon 8 Gen 3, 50MP camera, 6.2" display'],
            ['name'=>'Xiaomi 14', 'price'=>69990, 'category_id'=>1, 'brand'=>'Xiaomi', 'sku'=>'XMI14', 'stock'=>18, 'description'=>'Leica camera, Snapdragon 8 Gen 3, 4610 mAh'],
            ['name'=>'MacBook Pro 14"', 'price'=>199990, 'category_id'=>2, 'brand'=>'Apple', 'sku'=>'MBP14', 'stock'=>8, 'description'=>'M3 Pro chip, 18GB RAM, 512GB SSD'],
            ['name'=>'ASUS ROG Zephyrus', 'price'=>149990, 'category_id'=>2, 'brand'=>'ASUS', 'sku'=>'ROG-ZEPH', 'stock'=>5, 'description'=>'RTX 4070, Ryzen 9, 16GB RAM, 165Hz display'],
            ['name'=>'iPad Pro 12.9"', 'price'=>109990, 'category_id'=>3, 'brand'=>'Apple', 'sku'=>'IPADPRO', 'stock'=>12, 'description'=>'M2 chip, Liquid Retina XDR, Apple Pencil support'],
            ['name'=>'Samsung Galaxy Tab S9', 'price'=>79990, 'category_id'=>3, 'brand'=>'Samsung', 'sku'=>'TABS9', 'stock'=>10, 'description'=>'Snapdragon 8 Gen 2, 12GB RAM, AMOLED display'],
            ['name'=>'AirPods Pro 2', 'price'=>24990, 'category_id'=>4, 'brand'=>'Apple', 'sku'=>'APP2', 'stock'=>30, 'description'=>'Active Noise Cancellation, Adaptive Audio'],
            ['name'=>'Sony WH-1000XM5', 'price'=>29990, 'category_id'=>4, 'brand'=>'Sony', 'sku'=>'SONYWH5', 'stock'=>25, 'description'=>'Industry-leading noise cancelling, 30hr battery'],
            ['name'=>'MagSafe Charger', 'price'=>3990, 'category_id'=>5, 'brand'=>'Apple', 'sku'=>'MAGSAFE', 'stock'=>50, 'description'=>'15W fast wireless charging for iPhone'],
        ];
        foreach ($products as $p) Product::create($p);

        News::insert([
            ['title'=>'Новинки Apple 2024 уже в наличии', 'body'=>'iPhone 15 Pro и MacBook Pro на чипе M3 теперь доступны в нашем магазине. Успейте заказать!', 'published_at'=>now()],
            ['title'=>'Скидки на Samsung до 30%', 'body'=>'Только до конца месяца — грандиозная распродажа техники Samsung. Galaxy S24, Tab S9 и многое другое.', 'published_at'=>now()],
            ['title'=>'Бесплатная доставка от 10 000 ₽', 'body'=>'Мы запускаем бесплатную доставку на все заказы от 10 000 рублей по всей России.', 'published_at'=>now()],
        ]);
    }
}
