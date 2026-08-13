<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Site settings
        $settings = [
            'brand_name' => 'SinonimDesign',
            'tagline' => 'Ručno rađena kolekcija odjeće',
            'hero_mode' => 'gradient',
            'hero_headline' => 'Ručno rađeno. Za tebe.',
            'hero_subheadline' => 'Svaki komad je jedinstven — sašiven pažljivo, u malim serijama.',
            'hero_cta_label' => 'Pogledaj kolekciju',
            'hero_cta_url' => '/kolekcije',
            'hero_gradient_from' => '#efe7de',
            'hero_gradient_to' => '#c9a892',

            'banner_enabled' => '0',
            'banner_text' => 'Besplatna dostava iznad 100 KM',
            'banner_bg' => '#1a1a1a',
            'banner_fg' => '#ffffff',

            'contact_email' => 'sinonimdesign@gmail.com',
            'contact_phone' => '+387 61 000 000',
            'whatsapp_number' => '38761000000',
            'viber_number' => '38761000000',
            'instagram_handle' => 'sinonim_design',

            'shipping_flat_rate' => '5',
            'shipping_free_over' => '100',
            'shipping_note' => 'Dostava putem BH Pošte, rok isporuke 2–5 radnih dana.',

            'about_text' => 'SinonimDesign je ručno rađena kolekcija odjeće nastala iz ljubavi prema detaljima i kvalitetnim materijalima. Svaki komad je jedinstven — od odabira tkanine do finalnog šava.',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }

        // Categories
        $catData = [
            ['name' => 'Haljine', 'slug' => 'haljine', 'sort_order' => 1],
            ['name' => 'Bluze i košulje', 'slug' => 'bluze', 'sort_order' => 2],
            ['name' => 'Suknje', 'slug' => 'suknje', 'sort_order' => 3],
            ['name' => 'Dodaci', 'slug' => 'dodaci', 'sort_order' => 4],
        ];

        $categories = [];
        foreach ($catData as $c) {
            $categories[$c['slug']] = Category::create($c + ['is_published' => true]);
        }

        // Collection
        $collection = Collection::create([
            'name' => 'Ljeto ' . date('Y'),
            'slug' => 'ljeto-' . date('Y'),
            'description' => 'Laganije tkanine, prozračni kroj, jedinstveni detalji. Kolekcija za tople dane.',
            'is_featured' => true,
            'published_at' => now(),
        ]);

        // Products
        $products = [
            ['name' => 'Lanena haljina Ada', 'category' => 'haljine', 'price' => 189, 'sale' => 149, 'promoted' => true],
            ['name' => 'Košulja Nera', 'category' => 'bluze', 'price' => 89, 'promoted' => true],
            ['name' => 'Midi suknja Iva', 'category' => 'suknje', 'price' => 129, 'promoted' => true],
            ['name' => 'Bluza Lira', 'category' => 'bluze', 'price' => 79, 'promoted' => true],
            ['name' => 'Haljina Zora', 'category' => 'haljine', 'price' => 219, 'made_to_order' => true],
            ['name' => 'Torba Ela', 'category' => 'dodaci', 'price' => 65],
            ['name' => 'Suknja Mira', 'category' => 'suknje', 'price' => 109],
            ['name' => 'Bluza Neva', 'category' => 'bluze', 'price' => 95],
        ];

        $sizes = ['XS', 'S', 'M', 'L', 'XL'];
        $colors = [
            ['name' => 'crna', 'hex' => '#111111'],
            ['name' => 'krem', 'hex' => '#f0e4d3'],
            ['name' => 'terra', 'hex' => '#c07a4a'],
        ];

        foreach ($products as $data) {
            $product = Product::create([
                'category_id' => $categories[$data['category']]->id,
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'description' => "Ručno šivena {$data['name']} od kvalitetnih materijala. Pažljivo osmišljen kroj, ugodan za nošenje.\n\nDostupno u više veličina i boja — svaki komad je izrađen u maloj seriji.",
                'care_instructions' => "Pranje na 30°C, bez izbjeljivača.\nSušenje u hladu.\nGlačanje na srednjoj temperaturi.",
                'base_price' => $data['price'],
                'sale_price' => $data['sale'] ?? null,
                'is_promoted' => $data['promoted'] ?? false,
                'is_made_to_order' => $data['made_to_order'] ?? false,
                'published_at' => now(),
            ]);

            // Add to collection
            if (in_array($data['category'], ['haljine', 'suknje', 'bluze'])) {
                $collection->products()->attach($product->id);
            }

            // Variants
            foreach ($sizes as $size) {
                foreach ($colors as $color) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'size' => $size,
                        'color' => $color['name'],
                        'color_hex' => $color['hex'],
                        'stock' => rand(0, 5),
                    ]);
                }
            }
        }

        $this->command->info('Demo data seeded: ' . count($catData) . ' categories, 1 collection, ' . count($products) . ' products.');
    }
}
