<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds for 25 standardized cafe menus.
     */
    public function run(): void
    {
        $categoriesData = [
            ['name' => 'Signature Coffee', 'slug' => 'signature-coffee', 'icon' => 'coffee', 'is_active' => true],
            ['name' => 'Espresso Based', 'slug' => 'espresso-based', 'icon' => 'cup-hot', 'is_active' => true],
            ['name' => 'Non-Coffee & Milk', 'slug' => 'non-coffee-milk', 'icon' => 'cup-straw', 'is_active' => true],
            ['name' => 'Artisan Tea & Refreshers', 'slug' => 'tea-refreshers', 'icon' => 'glass-water', 'is_active' => true],
            ['name' => 'Pastry & Bakery', 'slug' => 'pastry-bakery', 'icon' => 'cookie', 'is_active' => true],
            ['name' => 'Meals & Snacks', 'slug' => 'meals-snacks', 'icon' => 'utensils', 'is_active' => true],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[$c['slug']] = Category::updateOrCreate(
                ['slug' => $c['slug']],
                $c
            );
        }

        $menusData = [
            // 1. Signature Coffee
            [
                'category_slug' => 'signature-coffee',
                'name' => 'Kopi Susu Gula Aren Senja',
                'slug' => 'kopi-susu-gula-aren-senja',
                'description' => 'Espresso blend lokal dipadu susu segar pilihan dan sirup gula aren organik premium.',
                'price' => 22000,
                'image' => 'images/menus/kopi-susu-gula-aren-senja.jpg',
                'is_available' => true,
                'taste_profile' => ['sweet', 'creamy', 'coffee', 'traditional', 'cold'],
            ],
            [
                'category_slug' => 'signature-coffee',
                'name' => 'Caramel Cloud Macchiato',
                'slug' => 'caramel-cloud-macchiato',
                'description' => 'Layered vanilla milk foam dengan shot espresso pekat dan lelehan saus karamel gurih.',
                'price' => 28000,
                'image' => 'images/menus/caramel-cloud-macchiato.jpg',
                'is_available' => true,
                'taste_profile' => ['sweet', 'caramel', 'creamy', 'mild coffee', 'hot_or_cold'],
            ],
            [
                'category_slug' => 'signature-coffee',
                'name' => 'Hazelnut Dolce Latte',
                'slug' => 'hazelnut-dolce-latte',
                'description' => 'Sentuhan kacang hazelnut panggang berpadu espresso lembut dan susu kental manis.',
                'price' => 26000,
                'image' => 'images/menus/hazelnut-dolce-latte.jpg',
                'is_available' => true,
                'taste_profile' => ['nutty', 'sweet', 'creamy', 'coffee', 'smooth'],
            ],
            [
                'category_slug' => 'signature-coffee',
                'name' => 'Avocado Espresso Float',
                'slug' => 'avocado-espresso-float',
                'description' => 'Jus alpukat kental berpadu shot espresso pekat dan topping es krim vanila.',
                'price' => 32000,
                'image' => 'images/menus/avocado-espresso-float.jpg',
                'is_available' => true,
                'taste_profile' => ['creamy', 'avocado', 'sweet', 'coffee', 'cold'],
            ],

            // 2. Espresso Based
            [
                'category_slug' => 'espresso-based',
                'name' => 'Caffe Americano Double Shot',
                'slug' => 'caffe-americano-double-shot',
                'description' => 'Dua shot espresso Arabika murni dengan air mineral panas/dingin. Bold tanpa ampas.',
                'price' => 18000,
                'image' => 'images/menus/caffe-americano-double-shot.jpg',
                'is_available' => true,
                'taste_profile' => ['strong', 'bitter', 'bold', 'low acid', 'no sugar'],
            ],
            [
                'category_slug' => 'espresso-based',
                'name' => 'Classic Cappuccino',
                'slug' => 'classic-cappuccino',
                'description' => 'Keseimbangan sepertiga espresso, steamed milk, dan foam tebal bertabur bubuk cokelat.',
                'price' => 24000,
                'image' => 'images/menus/classic-cappuccino.jpg',
                'is_available' => true,
                'taste_profile' => ['bold', 'foamy', 'mild coffee', 'warm', 'classic'],
            ],
            [
                'category_slug' => 'espresso-based',
                'name' => 'Vanilla Oat Flat White',
                'slug' => 'vanilla-oat-flat-white',
                'description' => 'Double ristretto dengan microfoam susu oat nabati dan ekstrak vanila murni Madagaskar.',
                'price' => 29000,
                'image' => 'images/menus/vanilla-oat-flat-white.jpg',
                'is_available' => true,
                'taste_profile' => ['smooth', 'nutty', 'creamy', 'plant-based', 'mild coffee'],
            ],
            [
                'category_slug' => 'espresso-based',
                'name' => 'Spanish Latte Creamy',
                'slug' => 'spanish-latte-creamy',
                'description' => 'Latte khas Spanyol dengan kombinasi susu segar dan kental manis yang memikat.',
                'price' => 25000,
                'image' => 'images/menus/spanish-latte-creamy.jpg',
                'is_available' => true,
                'taste_profile' => ['sweet', 'creamy', 'milky', 'smooth', 'coffee'],
            ],

            // 3. Non-Coffee & Milk
            [
                'category_slug' => 'non-coffee-milk',
                'name' => 'Uji Matcha Cream Frappe',
                'slug' => 'uji-matcha-cream-frappe',
                'description' => 'Bubuk matcha murni asli Kyoto Jepang berpadu susu dan whipped cream lembut.',
                'price' => 28000,
                'image' => 'images/menus/uji-matcha-cream-frappe.jpg',
                'is_available' => true,
                'taste_profile' => ['earthy', 'sweet', 'creamy', 'non-coffee', 'cold'],
            ],
            [
                'category_slug' => 'non-coffee-milk',
                'name' => 'Dark Belgian Chocolate Ice',
                'slug' => 'dark-belgian-chocolate',
                'description' => 'Cokelat hitam 70% Belgia yang pekat, legit, dan menghangatkan suasana.',
                'price' => 27000,
                'image' => 'images/menus/dark-belgian-chocolate-ice.jpg',
                'is_available' => true,
                'taste_profile' => ['rich chocolate', 'sweet', 'thick', 'creamy', 'non-coffee'],
            ],
            [
                'category_slug' => 'non-coffee-milk',
                'name' => 'Red Velvet Marshmallow',
                'slug' => 'red-velvet-marshmallow',
                'description' => 'Rasa kue red velvet lembut dengan taburan toasted marshmallow meleleh di atasnya.',
                'price' => 26000,
                'image' => 'images/menus/red-velvet-marshmallow.jpg',
                'is_available' => true,
                'taste_profile' => ['sweet', 'vanilla', 'creamy', 'dessert', 'non-coffee'],
            ],
            [
                'category_slug' => 'non-coffee-milk',
                'name' => 'Taro Coconut Velvet',
                'slug' => 'taro-coconut-velvet',
                'description' => 'Rasa talas ungu manis dipadu gurihnya krim santan lembut yang menyegarkan.',
                'price' => 25000,
                'image' => 'images/menus/taro-coconut-velvet.jpg',
                'is_available' => true,
                'taste_profile' => ['taro', 'sweet', 'creamy', 'coconut', 'non-coffee'],
            ],

            // 4. Artisan Tea & Refreshers
            [
                'category_slug' => 'tea-refreshers',
                'name' => 'Berry Citrus Sparkler Soda',
                'slug' => 'berry-citrus-sparkler',
                'description' => 'Kombinasi stroberi, blueberry liar, perasan jeruk lemon, dan air soda segar.',
                'price' => 23000,
                'image' => 'images/menus/berry-citrus-sparkler-soda.jpg',
                'is_available' => true,
                'taste_profile' => ['fresh', 'fruity', 'sour', 'sparkling', 'cold', 'non-coffee'],
            ],
            [
                'category_slug' => 'tea-refreshers',
                'name' => 'Royal Earl Grey Lavender Tea',
                'slug' => 'royal-earl-grey-lavender-tea',
                'description' => 'Seduhan teh hitam bergamot aromatik dengan kelopak lavender yang merilekskan.',
                'price' => 20000,
                'image' => 'images/menus/royal-earl-grey-lavender-tea.jpg',
                'is_available' => true,
                'taste_profile' => ['floral', 'aromatic', 'calming', 'tea', 'hot', 'refreshing'],
            ],
            [
                'category_slug' => 'tea-refreshers',
                'name' => 'Peach Mint Iced Tea',
                'slug' => 'peach-mint-iced-tea',
                'description' => 'Teh melati dingin dengan sirup buah persik asli dan remasan daun mint segar.',
                'price' => 22000,
                'image' => 'images/menus/peach-mint-iced-tea.jpg',
                'is_available' => true,
                'taste_profile' => ['fruity', 'peach', 'mint', 'refreshing', 'cold', 'tea'],
            ],
            [
                'category_slug' => 'tea-refreshers',
                'name' => 'Lychee Yakult Blossom',
                'slug' => 'lychee-yakult-blossom',
                'description' => 'Kombinasi probiotik Yakult segar, sirup leci premium, dan buah leci utuh.',
                'price' => 24000,
                'image' => 'images/menus/lychee-yakult-blossom.jpg',
                'is_available' => true,
                'taste_profile' => ['probiotic', 'sweet', 'sour', 'lychee', 'cold', 'refreshing'],
            ],

            // 5. Pastry & Bakery
            [
                'category_slug' => 'pastry-bakery',
                'name' => 'French Butter Croissant',
                'slug' => 'french-butter-croissant',
                'description' => 'Croissant renyah berlapis dengan mentega Prancis harum dan tekstur flaky sempurna.',
                'price' => 22000,
                'image' => 'images/menus/french-butter-croissant.jpg',
                'is_available' => true,
                'taste_profile' => ['buttery', 'savory', 'crispy', 'flaky', 'snack'],
            ],
            [
                'category_slug' => 'pastry-bakery',
                'name' => 'Molten Chocolate Lava Cake',
                'slug' => 'molten-chocolate-lava-cake',
                'description' => 'Kue cokelat hangat dengan lelehan cokelat pekat di bagian dalam.',
                'price' => 28000,
                'image' => 'images/menus/molten-chocolate-lava-cake.jpg',
                'is_available' => true,
                'taste_profile' => ['sweet', 'rich chocolate', 'warm', 'dessert'],
            ],
            [
                'category_slug' => 'pastry-bakery',
                'name' => 'New York Baked Cheesecake',
                'slug' => 'new-york-baked-cheesecake',
                'description' => 'Kue keju panggang padat gaya New York dengan lapisan biskuit renyah di bawahnya.',
                'price' => 30000,
                'image' => 'images/menus/new-york-baked-cheesecake.jpg',
                'is_available' => true,
                'taste_profile' => ['cheesy', 'sweet', 'rich', 'creamy', 'dessert'],
            ],
            [
                'category_slug' => 'pastry-bakery',
                'name' => 'Cinnamon Roll Cream Glaze',
                'slug' => 'cinnamon-roll-cream-glaze',
                'description' => 'Roti gulung kayu manis harum yang baru dipanggang dengan siraman saus keju manis.',
                'price' => 24000,
                'image' => 'images/menus/cinnamon-roll-cream-glaze.jpg',
                'is_available' => true,
                'taste_profile' => ['cinnamon', 'sweet', 'warm', 'glaze', 'bakery'],
            ],

            // 6. Meals & Snacks
            [
                'category_slug' => 'meals-snacks',
                'name' => 'Creamy Beef Carbonara',
                'slug' => 'creamy-beef-carbonara',
                'description' => 'Spageti saus krim gurih dengan potongan smoked beef renyah dan kuning telur segar.',
                'price' => 38000,
                'image' => 'images/menus/creamy-beef-carbonara.jpg',
                'is_available' => true,
                'taste_profile' => ['creamy', 'savory', 'heavy meal', 'pasta', 'warm'],
            ],
            [
                'category_slug' => 'meals-snacks',
                'name' => 'Nasi Goreng Spesial Nongkrong',
                'slug' => 'nasi-goreng-spesial-nongkrong',
                'description' => 'Nasi goreng bumbu rempah nusantara dengan ayam suwir, telur mata sapi, dan kerupuk renyah.',
                'price' => 32000,
                'image' => 'images/menus/nasi-goreng-spesial-nongkrong.jpg',
                'is_available' => true,
                'taste_profile' => ['savory', 'spicy', 'traditional', 'heavy meal', 'egg'],
            ],
            [
                'category_slug' => 'meals-snacks',
                'name' => 'Chicken Katsu Curry Bowl',
                'slug' => 'chicken-katsu-curry-bowl',
                'description' => 'Fillet ayam goreng katsu renyah disajikan dengan kuah kari Jepang kental dan nasi pulen.',
                'price' => 36000,
                'image' => 'images/menus/chicken-katsu-curry-bowl.jpg',
                'is_available' => true,
                'taste_profile' => ['crispy', 'curry', 'savory', 'heavy meal', 'warm'],
            ],
            [
                'category_slug' => 'meals-snacks',
                'name' => 'Truffle Parmesan Fries',
                'slug' => 'truffle-parmesan-fries',
                'description' => 'Kentang goreng renyah dengan aroma minyak truffle putih dan parutan keju parmesan.',
                'price' => 25000,
                'image' => 'images/menus/truffle-parmesan-fries.jpg',
                'is_available' => true,
                'taste_profile' => ['savory', 'salty', 'crispy', 'snack', 'truffle'],
            ],
            [
                'category_slug' => 'meals-snacks',
                'name' => 'Crispy Chicken Popcorn BBQ',
                'slug' => 'crispy-chicken-popcorn-bbq',
                'description' => 'Daging ayam potong dadu berbalut tepung bumbu krispi disajikan dengan saus celup BBQ.',
                'price' => 26000,
                'image' => 'images/menus/crispy-chicken-popcorn-bbq.jpg',
                'is_available' => true,
                'taste_profile' => ['crispy', 'bbq', 'savory', 'snack', 'chicken'],
            ],
        ];

        foreach ($menusData as $m) {
            $cat = $categories[$m['category_slug']];
            Menu::updateOrCreate(
                ['slug' => $m['slug']],
                [
                    'category_id' => $cat->id,
                    'name' => $m['name'],
                    'slug' => $m['slug'],
                    'description' => $m['description'],
                    'price' => $m['price'],
                    'image' => $m['image'],
                    'is_available' => $m['is_available'],
                    'taste_profile' => $m['taste_profile'],
                ]
            );
        }
    }
}
