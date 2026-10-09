<?php

namespace Database\Seeders;

use App\Models\AiConsultation;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. DATA PENGGUNA (Users - Admin & Staff)
        $admin = User::updateOrCreate(
            ['email' => 'admin@smartcafe.id'],
            [
                'name' => 'Allam (Owner & Admin)',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        $kasir = User::updateOrCreate(
            ['email' => 'kasir@smartcafe.id'],
            [
                'name' => 'Siti Kasir POS',
                'password' => Hash::make('password123'),
                'role' => 'staff',
            ]
        );

        $barista = User::updateOrCreate(
            ['email' => 'barista@smartcafe.id'],
            [
                'name' => 'Dimas Barista',
                'password' => Hash::make('password123'),
                'role' => 'staff',
            ]
        );

        // 2. DATA MEJA KAFE (Tables)
        $tablesData = [
            ['table_number' => '01', 'capacity' => 2, 'status' => 'available', 'qr_code_token' => 'QR-TBL-01-A9X1', 'access_code' => '7K92A'],
            ['table_number' => '02', 'capacity' => 4, 'status' => 'occupied',  'qr_code_token' => 'QR-TBL-02-B8Y2', 'access_code' => '3X81B'],
            ['table_number' => '03', 'capacity' => 4, 'status' => 'occupied',  'qr_code_token' => 'QR-TBL-03-C7Z3', 'access_code' => '8Y24C'],
            ['table_number' => '04', 'capacity' => 6, 'status' => 'available', 'qr_code_token' => 'QR-TBL-04-D6W4', 'access_code' => '6W15D'],
            ['table_number' => '05', 'capacity' => 2, 'status' => 'available', 'qr_code_token' => 'QR-TBL-05-E5V5', 'access_code' => '5V39E'],
            ['table_number' => 'VIP-1', 'capacity' => 8, 'status' => 'reserved', 'qr_code_token' => 'QR-TBL-VIP1-F4U6', 'access_code' => '4U72F'],
        ];

        $tables = [];
        foreach ($tablesData as $t) {
            $tables[$t['table_number']] = Table::updateOrCreate(
                ['table_number' => $t['table_number']],
                $t
            );
        }

        // 3. DATA KATEGORI (Categories)
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

        // 4. DATA MENU & TASTE PROFILES (Menus)
        $menusData = [
            // Signature Coffee
            [
                'category_slug' => 'signature-coffee',
                'name' => 'Kopi Susu Gula Aren Senja',
                'slug' => 'kopi-susu-gula-aren-senja',
                'description' => 'Espresso house blend dipadu susu segar pilihan dan sirup gula aren organik premium.',
                'price' => 22000,
                'image' => 'menus/aren-senja.jpg',
                'is_available' => true,
                'taste_profile' => ['sweet', 'creamy', 'coffee', 'traditional', 'cold'],
            ],
            [
                'category_slug' => 'signature-coffee',
                'name' => 'Caramel Cloud Macchiato',
                'slug' => 'caramel-cloud-macchiato',
                'description' => 'Layered vanilla milk foam dengan shot espresso pekat dan lelehan saus karamel gurih.',
                'price' => 28000,
                'image' => 'menus/caramel-macchiato.jpg',
                'is_available' => true,
                'taste_profile' => ['sweet', 'caramel', 'creamy', 'mild coffee', 'hot_or_cold'],
            ],
            [
                'category_slug' => 'signature-coffee',
                'name' => 'Hazelnut Dolce Latte',
                'slug' => 'hazelnut-dolce-latte',
                'description' => 'Sentuhan kacang hazelnut panggang berpadu espresso lembut dan susu kental manis.',
                'price' => 27000,
                'image' => 'menus/hazelnut-latte.jpg',
                'is_available' => true,
                'taste_profile' => ['nutty', 'sweet', 'creamy', 'coffee', 'smooth'],
            ],

            // Espresso Based
            [
                'category_slug' => 'espresso-based',
                'name' => 'Caffe Americano Double Shot',
                'slug' => 'caffe-americano-double-shot',
                'description' => 'Dua shot espresso Arabika murni dengan air mineral panas/dingin. Bold tanpa ampas.',
                'price' => 20000,
                'image' => 'menus/americano.jpg',
                'is_available' => true,
                'taste_profile' => ['strong', 'bitter', 'bold', 'low acid', 'no sugar'],
            ],
            [
                'category_slug' => 'espresso-based',
                'name' => 'Classic Cappuccino',
                'slug' => 'classic-cappuccino',
                'description' => 'Keseimbangan sepertiga espresso, steamed milk, dan foam tebal bertabur bubuk cokelat.',
                'price' => 25000,
                'image' => 'menus/cappuccino.jpg',
                'is_available' => true,
                'taste_profile' => ['bold', 'foamy', 'mild coffee', 'warm', 'classic'],
            ],
            [
                'category_slug' => 'espresso-based',
                'name' => 'Vanilla Oat Flat White',
                'slug' => 'vanilla-oat-flat-white',
                'description' => 'Double ristretto dengan microfoam susu oat nabati dan ekstrak vanila murni Madagaskar.',
                'price' => 30000,
                'image' => 'menus/oat-flat-white.jpg',
                'is_available' => true,
                'taste_profile' => ['smooth', 'nutty', 'creamy', 'plant-based', 'mild coffee'],
            ],

            // Non-Coffee & Milk
            [
                'category_slug' => 'non-coffee-milk',
                'name' => 'Uji Matcha Cream Frappe',
                'slug' => 'uji-matcha-cream-frappe',
                'description' => 'Bubuk matcha murni asli Kyoto Jepang berpadu susu dan whipped cream lembut.',
                'price' => 28000,
                'image' => 'menus/matcha-frappe.jpg',
                'is_available' => true,
                'taste_profile' => ['earthy', 'sweet', 'creamy', 'non-coffee', 'cold'],
            ],
            [
                'category_slug' => 'non-coffee-milk',
                'name' => 'Dark Belgian Chocolate',
                'slug' => 'dark-belgian-chocolate',
                'description' => 'Cokelat hitam 70% Belgia yang pekat, legit, dan menghangatkan suasana.',
                'price' => 26000,
                'image' => 'menus/dark-choco.jpg',
                'is_available' => true,
                'taste_profile' => ['rich chocolate', 'sweet', 'thick', 'creamy', 'non-coffee'],
            ],
            [
                'category_slug' => 'non-coffee-milk',
                'name' => 'Red Velvet Marshmallow',
                'slug' => 'red-velvet-marshmallow',
                'description' => 'Rasa kue red velvet lembut dengan taburan toasted marshmallow meleleh di atasnya.',
                'price' => 27000,
                'image' => 'menus/red-velvet.jpg',
                'is_available' => false, // contoh out of stock
                'taste_profile' => ['sweet', 'vanilla', 'creamy', 'dessert', 'non-coffee'],
            ],

            // Artisan Tea & Refreshers
            [
                'category_slug' => 'tea-refreshers',
                'name' => 'Berry Citrus Sparkler',
                'slug' => 'berry-citrus-sparkler',
                'description' => 'Kombinasi stroberi, blueberry liar, perasan jeruk lemon, dan air soda segar.',
                'price' => 25000,
                'image' => 'menus/berry-citrus.jpg',
                'is_available' => true,
                'taste_profile' => ['fresh', 'fruity', 'sour', 'sparkling', 'cold', 'non-coffee'],
            ],
            [
                'category_slug' => 'tea-refreshers',
                'name' => 'Royal Earl Grey Lavender Tea',
                'slug' => 'royal-earl-grey-lavender-tea',
                'description' => 'Seduhan teh hitam bergamot aromatik dengan kelopak lavender yang merilekskan.',
                'price' => 22000,
                'image' => 'menus/earl-grey.jpg',
                'is_available' => true,
                'taste_profile' => ['floral', 'aromatic', 'calming', 'tea', 'hot', 'refreshing'],
            ],

            // Pastry & Bakery
            [
                'category_slug' => 'pastry-bakery',
                'name' => 'French Butter Croissant',
                'slug' => 'french-butter-croissant',
                'description' => 'Croissant renyah berlapis dengan mentega Prancis harum dan tekstur flaky sempurna.',
                'price' => 24000,
                'image' => 'menus/croissant.jpg',
                'is_available' => true,
                'taste_profile' => ['buttery', 'savory', 'crispy', 'flaky', 'snack'],
            ],
            [
                'category_slug' => 'pastry-bakery',
                'name' => 'Molten Chocolate Lava Cake',
                'slug' => 'molten-chocolate-lava-cake',
                'description' => 'Kue cokelat hangat dengan lelehan cokelat pekat di bagian dalam.',
                'price' => 29000,
                'image' => 'menus/lava-cake.jpg',
                'is_available' => true,
                'taste_profile' => ['sweet', 'rich chocolate', 'warm', 'dessert'],
            ],

            // Meals & Snacks
            [
                'category_slug' => 'meals-snacks',
                'name' => 'Truffle Parmesan Fries',
                'slug' => 'truffle-parmesan-fries',
                'description' => 'Kentang goreng renyah dengan aroma minyak truffle putih dan parutan keju parmesan.',
                'price' => 26000,
                'image' => 'menus/truffle-fries.jpg',
                'is_available' => true,
                'taste_profile' => ['savory', 'salty', 'crispy', 'snack', 'truffle'],
            ],
            [
                'category_slug' => 'meals-snacks',
                'name' => 'Creamy Beef Carbonara',
                'slug' => 'creamy-beef-carbonara',
                'description' => 'Spageti saus krim gurih dengan potongan smoked beef renyah dan kuning telur segar.',
                'price' => 38000,
                'image' => 'menus/carbonara.jpg',
                'is_available' => true,
                'taste_profile' => ['creamy', 'savory', 'heavy meal', 'pasta', 'warm'],
            ],
        ];

        $menus = [];
        foreach ($menusData as $m) {
            $cat = $categories[$m['category_slug']];
            $menus[$m['slug']] = Menu::updateOrCreate(
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

        // 5. DATA SAMPLE PESANAN (Orders & Order Items)
        // Pesanan 1: Status 'pending' (baru di-submit pembeli meja 01, belum diproses kasir)
        $order1 = Order::updateOrCreate(
            ['order_code' => 'ORD-202610-001'],
            [
                'table_id' => $tables['01']->id,
                'customer_name' => 'Rian Pratama',
                'handled_by' => null,
                'order_status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_method' => 'qris',
                'total_amount' => 50000,
                'notes' => 'Tolong sediakan sedotan kertas.',
                'created_at' => now()->subMinutes(8),
            ]
        );
        OrderItem::firstOrCreate([
            'order_id' => $order1->id,
            'menu_id' => $menus['kopi-susu-gula-aren-senja']->id,
        ], [
            'quantity' => 1,
            'unit_price' => 22000,
            'subtotal' => 22000,
            'item_notes' => 'Less ice, normal sugar',
        ]);
        OrderItem::firstOrCreate([
            'order_id' => $order1->id,
            'menu_id' => $menus['caramel-cloud-macchiato']->id,
        ], [
            'quantity' => 1,
            'unit_price' => 28000,
            'subtotal' => 28000,
            'item_notes' => 'Extra caramel sauce',
        ]);

        // Pesanan 2: Status 'processing' (kasir sudah terima & sedang diracik barista)
        $order2 = Order::updateOrCreate(
            ['order_code' => 'ORD-202610-002'],
            [
                'table_id' => $tables['02']->id,
                'customer_name' => 'Siti Nurhaliza',
                'handled_by' => $kasir->id,
                'order_status' => 'processing',
                'payment_status' => 'paid',
                'payment_method' => 'cash',
                'total_amount' => 54000,
                'notes' => null,
                'created_at' => now()->subMinutes(15),
            ]
        );
        OrderItem::firstOrCreate([
            'order_id' => $order2->id,
            'menu_id' => $menus['uji-matcha-cream-frappe']->id,
        ], [
            'quantity' => 1,
            'unit_price' => 28000,
            'subtotal' => 28000,
            'item_notes' => 'Less sugar',
        ]);
        OrderItem::firstOrCreate([
            'order_id' => $order2->id,
            'menu_id' => $menus['truffle-parmesan-fries']->id,
        ], [
            'quantity' => 1,
            'unit_price' => 26000,
            'subtotal' => 26000,
            'item_notes' => 'Saus dipisah',
        ]);

        // Pesanan 3: Status 'ready' (barista selesai meracik, siap diantar ke meja 03)
        $order3 = Order::updateOrCreate(
            ['order_code' => 'ORD-202610-003'],
            [
                'table_id' => $tables['03']->id,
                'customer_name' => 'Budi Santoso',
                'handled_by' => $kasir->id,
                'order_status' => 'ready',
                'payment_status' => 'paid',
                'payment_method' => 'qris',
                'total_amount' => 49000,
                'notes' => null,
                'created_at' => now()->subMinutes(25),
            ]
        );
        OrderItem::firstOrCreate([
            'order_id' => $order3->id,
            'menu_id' => $menus['berry-citrus-sparkler']->id,
        ], [
            'quantity' => 1,
            'unit_price' => 25000,
            'subtotal' => 25000,
            'item_notes' => null,
        ]);
        OrderItem::firstOrCreate([
            'order_id' => $order3->id,
            'menu_id' => $menus['french-butter-croissant']->id,
        ], [
            'quantity' => 1,
            'unit_price' => 24000,
            'subtotal' => 24000,
            'item_notes' => 'Dihangatkan sebentar',
        ]);

        // Pesanan 4: Status 'completed' (transaksi sukses selesai meja 04)
        $order4 = Order::updateOrCreate(
            ['order_code' => 'ORD-202610-004'],
            [
                'table_id' => $tables['04']->id,
                'customer_name' => 'Allam',
                'handled_by' => $kasir->id,
                'order_status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => 'qris',
                'total_amount' => 58000,
                'notes' => 'Pesanan dine-in',
                'created_at' => now()->subHours(2),
            ]
        );
        OrderItem::firstOrCreate([
            'order_id' => $order4->id,
            'menu_id' => $menus['caffe-americano-double-shot']->id,
        ], [
            'quantity' => 1,
            'unit_price' => 20000,
            'subtotal' => 20000,
            'item_notes' => 'Iced Americano',
        ]);
        OrderItem::firstOrCreate([
            'order_id' => $order4->id,
            'menu_id' => $menus['creamy-beef-carbonara']->id,
        ], [
            'quantity' => 1,
            'unit_price' => 38000,
            'subtotal' => 38000,
            'item_notes' => 'Extra pepper',
        ]);

        // Pesanan 5: Walk-in / Takeaway kasir (table_id: null, completed)
        $order5 = Order::updateOrCreate(
            ['order_code' => 'ORD-202610-005'],
            [
                'table_id' => null,
                'customer_name' => 'Dewi Anggraini (Takeaway)',
                'handled_by' => $kasir->id,
                'order_status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => 'cash',
                'total_amount' => 54000,
                'notes' => 'Takeaway cup & paper bag',
                'created_at' => now()->subHours(3),
            ]
        );
        OrderItem::firstOrCreate([
            'order_id' => $order5->id,
            'menu_id' => $menus['hazelnut-dolce-latte']->id,
        ], [
            'quantity' => 2,
            'unit_price' => 27000,
            'subtotal' => 54000,
            'item_notes' => null,
        ]);

        // 6. DATA LOG KONSULTASI AI BARISTA (Sample)
        AiConsultation::create([
            'table_id' => $tables['01']->id,
            'user_prompt' => 'Lagi agak ngantuk pengen kopi manis creamy yang gak terlalu asam di lambung',
            'ai_response' => 'Halo! Untuk nemenin kamu yang butuh kafein tapi tetap santai dan manis creamy, aku sangat sarankan **Kopi Susu Gula Aren Senja** atau **Caramel Cloud Macchiato**. Paduan espresso lembut dan saus karamelnya bikin mood langsung naik!',
            'recommended_ids' => [
                $menus['kopi-susu-gula-aren-senja']->id,
                $menus['caramel-cloud-macchiato']->id,
            ],
            'is_converted' => true,
            'created_at' => now()->subMinutes(10),
        ]);
    }
}
