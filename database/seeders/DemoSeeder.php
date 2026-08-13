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
    /**
     * Editorial fashion palette — mix of moody + warm neutrals for
     * placeholder images that pop against the page background.
     * Each entry: [top color, bottom color, text color].
     */
    protected array $palette = [
        ['#2a1f18', '#7d6045', '#f5efe6'], // deep espresso → mocha
        ['#c9a892', '#7d6045', '#faf7f3'], // clay → cocoa
        ['#4a5c48', '#8fa08a', '#faf7f3'], // sage deep → sage light
        ['#8b6f4e', '#e6d9c8', '#2a1f18'], // walnut → sand
        ['#6b7580', '#c0c9d0', '#1a1a1a'], // dusty blue
        ['#c07a4a', '#e8dcc9', '#2a1f18'], // terra → wheat
        ['#3a3a3a', '#8b6f4e', '#faf7f3'], // charcoal → walnut
        ['#a08466', '#f5efe6', '#2a1f18'], // hazel → ecru
        ['#5c4a3a', '#b89a78', '#faf7f3'], // umber → mocha
    ];

    public function run(): void
    {
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

            'banner_enabled' => '1',
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

        $collection = Collection::create([
            'name' => 'Ljeto ' . date('Y'),
            'slug' => 'ljeto-' . date('Y'),
            'description' => 'Laganije tkanine, prozračni kroj, jedinstveni detalji. Kolekcija za tople dane.',
            'is_featured' => true,
            'published_at' => now(),
        ]);

        $this->attachPlaceholder($collection, 'cover', 'Ljeto ' . date('Y'), 1600, 900, 0);

        $products = [
            ['name' => 'Lanena haljina Ada', 'category' => 'haljine', 'price' => 189, 'sale' => 149, 'promoted' => true],
            ['name' => 'Košulja Nera', 'category' => 'bluze', 'price' => 89, 'promoted' => true],
            ['name' => 'Midi suknja Iva', 'category' => 'suknje', 'price' => 129, 'promoted' => true],
            ['name' => 'Bluza Lira', 'category' => 'bluze', 'price' => 79, 'promoted' => true],
            ['name' => 'Haljina Zora', 'category' => 'haljine', 'price' => 219, 'made_to_order' => true, 'promoted' => true],
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

        foreach ($products as $i => $data) {
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

            $this->attachPlaceholder($product, 'gallery', $data['name'], 800, 1000, $i);
            $this->attachPlaceholder($product, 'gallery', $data['name'], 800, 1000, $i + 1);

            if (in_array($data['category'], ['haljine', 'suknje', 'bluze'])) {
                $collection->products()->attach($product->id);
            }

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

        $this->command->info('Demo data seeded: ' . count($catData) . ' categories, 1 collection, ' . count($products) . ' products with placeholder photos.');
    }

    /**
     * Generate an in-memory JPG placeholder (via GD) with a warm gradient background
     * and the item name as an elegant serif overlay. Attach to media library.
     * Deterministic — same $paletteIndex → same colors.
     */
    protected function attachPlaceholder($model, string $collectionName, string $name, int $width, int $height, int $paletteIndex): void
    {
        if (! extension_loaded('gd')) {
            return;
        }

        [$startHex, $endHex, $textHex] = $this->palette[$paletteIndex % count($this->palette)];
        [$sr, $sg, $sb] = $this->hexToRgb($startHex);
        [$er, $eg, $eb] = $this->hexToRgb($endHex);
        [$tr, $tg, $tb] = $this->hexToRgb($textHex);

        $img = imagecreatetruecolor($width, $height);

        // Diagonal gradient for more visual interest
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $t = (($x / $width) * 0.4) + (($y / $height) * 0.6);
                $t = max(0, min(1, $t));
                $r = (int) round($sr + ($er - $sr) * $t);
                $g = (int) round($sg + ($eg - $sg) * $t);
                $b = (int) round($sb + ($eb - $sb) * $t);
                $color = imagecolorallocate($img, $r, $g, $b);
                imagesetpixel($img, $x, $y, $color);
            }
        }

        // Grain effect
        $grain = imagecolorallocatealpha($img, 255, 255, 255, 110);
        for ($i = 0; $i < ($width * $height) / 300; $i++) {
            imagesetpixel($img, rand(0, $width - 1), rand(0, $height - 1), $grain);
        }
        $grainDark = imagecolorallocatealpha($img, 0, 0, 0, 115);
        for ($i = 0; $i < ($width * $height) / 400; $i++) {
            imagesetpixel($img, rand(0, $width - 1), rand(0, $height - 1), $grainDark);
        }

        // Thin decorative frame
        $frameColor = imagecolorallocatealpha($img, $tr, $tg, $tb, 100);
        $inset = (int) ($width * 0.04);
        imagerectangle($img, $inset, $inset, $width - $inset - 1, $height - $inset - 1, $frameColor);

        // Product / brand label using built-in font (no TTF dependency)
        $textColor = imagecolorallocate($img, $tr, $tg, $tb);
        $eyebrowColor = imagecolorallocatealpha($img, $tr, $tg, $tb, 60);

        // Eyebrow: SINONIMDESIGN — small at top
        $eyebrow = 'SINONIMDESIGN';
        $eyebrowFont = 3;
        $ew = imagefontwidth($eyebrowFont) * strlen($eyebrow);
        imagestring($img, $eyebrowFont, (int) (($width - $ew) / 2), $inset + 20, $eyebrow, $eyebrowColor);

        // Main name (uppercase, larger built-in font)
        $label = mb_strtoupper($name, 'UTF-8');
        $font = 5;
        $lw = imagefontwidth($font) * mb_strlen($label);
        // If name too wide, split across two rows
        if ($lw > $width - $inset * 4) {
            $words = explode(' ', $label);
            $line1 = array_slice($words, 0, (int) ceil(count($words) / 2));
            $line2 = array_slice($words, count($line1));
            $t1 = implode(' ', $line1);
            $t2 = implode(' ', $line2);
            $w1 = imagefontwidth($font) * mb_strlen($t1);
            $w2 = imagefontwidth($font) * mb_strlen($t2);
            imagestring($img, $font, (int) (($width - $w1) / 2), (int) ($height / 2 - 20), $t1, $textColor);
            imagestring($img, $font, (int) (($width - $w2) / 2), (int) ($height / 2 + 5), $t2, $textColor);
        } else {
            imagestring($img, $font, (int) (($width - $lw) / 2), (int) ($height / 2 - 5), $label, $textColor);
        }

        // Bottom marker line + label
        $lineY = $height - $inset - 25;
        imageline($img, $inset + 40, $lineY, $width - $inset - 40, $lineY, $eyebrowColor);
        $footer = 'HANDMADE · SARAJEVO';
        $fw = imagefontwidth(2) * strlen($footer);
        imagestring($img, 2, (int) (($width - $fw) / 2), $lineY + 8, $footer, $eyebrowColor);

        ob_start();
        imagejpeg($img, null, 88);
        $binary = ob_get_clean();
        imagedestroy($img);

        try {
            $model
                ->addMediaFromString($binary)
                ->usingName($name)
                ->usingFileName(Str::slug($name) . '-' . uniqid() . '.jpg')
                ->toMediaCollection($collectionName);
        } catch (\Throwable $e) {
            $this->command?->warn('  (skipped placeholder for ' . $name . ': ' . $e->getMessage() . ')');
        }
    }

    protected function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }
}
