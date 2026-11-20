-- ==========================================================
-- NONGKRONG.ID / SMART CAFE - SUPABASE POSTGRESQL SCHEMA & SEED
-- Generated for Supabase SQL Editor execution
-- ==========================================================

BEGIN;

-- 1. DROP EXISTING TABLES
DROP TABLE IF EXISTS order_items CASCADE;
DROP TABLE IF EXISTS orders CASCADE;
DROP TABLE IF EXISTS ai_consultations CASCADE;
DROP TABLE IF EXISTS menus CASCADE;
DROP TABLE IF EXISTS categories CASCADE;
DROP TABLE IF EXISTS tables CASCADE;
DROP TABLE IF EXISTS settings CASCADE;
DROP TABLE IF EXISTS sessions CASCADE;
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS migrations CASCADE;

-- 2. CREATE SCHEMAS

CREATE TABLE migrations (
    id SERIAL PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INT NOT NULL
);

CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'staff' CHECK (role IN ('admin','cashier','kitchen','barista','cleaner','staff')),
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tables (
    id BIGSERIAL PRIMARY KEY,
    table_number VARCHAR(20) NOT NULL UNIQUE,
    capacity INT NOT NULL DEFAULT 4,
    status VARCHAR(20) NOT NULL DEFAULT 'available' CHECK (status IN ('available','occupied','reserved','dirty')),
    qr_code_token VARCHAR(64) NULL UNIQUE,
    access_code VARCHAR(10) NULL UNIQUE,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    icon VARCHAR(100) NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE menus (
    id BIGSERIAL PRIMARY KEY,
    category_id BIGINT NOT NULL REFERENCES categories(id) ON DELETE CASCADE,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(180) NOT NULL UNIQUE,
    description TEXT NULL,
    price DECIMAL(12,2) NOT NULL,
    image VARCHAR(255) NULL,
    is_available BOOLEAN NOT NULL DEFAULT TRUE,
    taste_profile JSONB NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_menus_available ON menus(is_available);
CREATE INDEX idx_menus_category ON menus(category_id);

CREATE TABLE orders (
    id BIGSERIAL PRIMARY KEY,
    order_code VARCHAR(30) NOT NULL UNIQUE,
    table_id BIGINT NULL REFERENCES tables(id) ON DELETE SET NULL,
    customer_name VARCHAR(100) NOT NULL,
    handled_by BIGINT NULL REFERENCES users(id) ON DELETE SET NULL,
    order_status VARCHAR(20) NOT NULL DEFAULT 'pending' CHECK (order_status IN ('pending','processing','ready','completed','cancelled')),
    payment_status VARCHAR(20) NOT NULL DEFAULT 'unpaid' CHECK (payment_status IN ('unpaid','paid')),
    payment_method VARCHAR(20) NOT NULL DEFAULT 'cash' CHECK (payment_method IN ('cash','qris','debit')),
    snap_token VARCHAR(255) NULL,
    total_amount DECIMAL(14,2) NOT NULL DEFAULT 0.00,
    notes TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_orders_status ON orders(order_status);
CREATE INDEX idx_orders_payment ON orders(payment_status);

CREATE TABLE order_items (
    id BIGSERIAL PRIMARY KEY,
    order_id BIGINT NOT NULL REFERENCES orders(id) ON DELETE CASCADE,
    menu_id BIGINT NOT NULL REFERENCES menus(id),
    quantity INT NOT NULL DEFAULT 1,
    unit_price DECIMAL(12,2) NOT NULL,
    subtotal DECIMAL(14,2) NOT NULL,
    item_notes VARCHAR(255) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_order_items_order ON order_items(order_id);

CREATE TABLE ai_consultations (
    id BIGSERIAL PRIMARY KEY,
    table_id BIGINT NULL REFERENCES tables(id) ON DELETE SET NULL,
    user_prompt TEXT NOT NULL,
    ai_response TEXT NOT NULL,
    recommended_ids JSONB NULL,
    is_converted BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE settings (
    id BIGSERIAL PRIMARY KEY,
    key VARCHAR(255) NOT NULL UNIQUE,
    value TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload TEXT NOT NULL,
    last_activity INT NOT NULL
);
CREATE INDEX idx_sessions_user_id ON sessions(user_id);
CREATE INDEX idx_sessions_last_activity ON sessions(last_activity);

-- 3. INSERT MIGRATIONS RECORD
INSERT INTO migrations (id, migration, batch) VALUES (1, '0001_01_01_000000_create_users_table', 1);
INSERT INTO migrations (id, migration, batch) VALUES (4, '2026_10_08_100101_add_role_to_users_table', 2);
INSERT INTO migrations (id, migration, batch) VALUES (5, '2026_10_08_100102_create_tables_table', 2);
INSERT INTO migrations (id, migration, batch) VALUES (6, '2026_10_08_100103_create_categories_table', 2);
INSERT INTO migrations (id, migration, batch) VALUES (7, '2026_10_08_100104_create_menus_table', 2);
INSERT INTO migrations (id, migration, batch) VALUES (8, '2026_10_08_100105_create_orders_table', 2);
INSERT INTO migrations (id, migration, batch) VALUES (9, '2026_10_08_100106_create_order_items_table', 2);
INSERT INTO migrations (id, migration, batch) VALUES (10, '2026_10_08_100107_create_ai_consultations_table', 2);
INSERT INTO migrations (id, migration, batch) VALUES (11, '2026_10_11_153345_add_snap_token_to_orders_table', 3);
INSERT INTO migrations (id, migration, batch) VALUES (12, '2026_11_04_025209_add_dirty_status_to_tables_table', 4);
INSERT INTO migrations (id, migration, batch) VALUES (13, '2026_11_04_025346_update_role_enum_on_users_table', 5);
INSERT INTO migrations (id, migration, batch) VALUES (14, '2026_11_09_012118_create_settings_table', 6);

-- 4. INSERT USERS
INSERT INTO users (id, name, email, email_verified_at, password, role, remember_token, created_at, updated_at) VALUES (1, 'agus', 'admin1@smartcafe.id', NULL, '$2y$12$ljjSbqTDzv6Lc8zR38BdPOz2CorqQERzLgMbBV0zRwRTqjK6T2aie', 'admin', NULL, '2026-10-08 03:06:25', '2026-11-09 00:57:28');
INSERT INTO users (id, name, email, email_verified_at, password, role, remember_token, created_at, updated_at) VALUES (2, 'Siti Kasir POS', 'kasir@smartcafe.id', NULL, '$2y$12$tQfdpOAQtL5GQVt11pxqsOWuftc0lE1SxUj9Axg0tbskUqk4LxQZK', 'cashier', NULL, '2026-10-08 03:06:25', '2026-11-04 02:54:23');
INSERT INTO users (id, name, email, email_verified_at, password, role, remember_token, created_at, updated_at) VALUES (3, 'Dimas Barista', 'barista@smartcafe.id', NULL, '$2y$12$64Qed0G48.lv28wbN2BbfeIIxgE1TnzheaT2BhVWaDuzKzK5dPdBW', 'barista', NULL, '2026-10-08 03:06:25', '2026-11-04 02:54:23');
INSERT INTO users (id, name, email, email_verified_at, password, role, remember_token, created_at, updated_at) VALUES (4, 'Budi Tim Dapur', 'dapur@smartcafe.id', NULL, '$2y$12$jrPaT0ttfwQpRXml6D2FAejqwdQY24Gvu0SwdG1ode9oMCX.HGwK6', 'kitchen', NULL, '2026-11-04 02:54:08', '2026-11-04 02:54:23');
INSERT INTO users (id, name, email, email_verified_at, password, role, remember_token, created_at, updated_at) VALUES (5, 'Ahmad Tim Pembersih', 'cleaner@smartcafe.id', NULL, '$2y$12$p9bc2oxx5.SJ8HrkAaOX3u1wO0KTlBM44A7oLVNkf3cRlgslKDcj2', 'cleaner', NULL, '2026-11-04 02:54:09', '2026-11-04 02:54:23');
INSERT INTO users (id, name, email, email_verified_at, password, role, remember_token, created_at, updated_at) VALUES (7, 'Allam satu dia', 'allam@gmail.com', NULL, '$2y$12$61jmWI8HLIbo8ZECVqKfY.WZ3cYYJ.3BhKfN2INVQ3aBlq3jt1f82', 'admin', NULL, '2026-11-09 04:34:39', '2026-11-09 04:34:39');

-- 5. INSERT TABLES
INSERT INTO tables (id, table_number, capacity, status, qr_code_token, access_code, created_at, updated_at) VALUES (1, '01', 2, 'available', 'QR-TBL-01-A9X1', '7K92A', '2026-10-08 03:06:25', '2026-11-20 01:03:15');
INSERT INTO tables (id, table_number, capacity, status, qr_code_token, access_code, created_at, updated_at) VALUES (2, '02', 4, 'available', 'QR-TBL-02-B8Y2', '3X81B', '2026-10-08 03:06:25', '2026-11-20 00:41:49');
INSERT INTO tables (id, table_number, capacity, status, qr_code_token, access_code, created_at, updated_at) VALUES (3, '03', 4, 'available', 'QR-TBL-03-C7Z3', '8Y24C', '2026-10-08 03:06:25', '2026-11-20 00:41:50');
INSERT INTO tables (id, table_number, capacity, status, qr_code_token, access_code, created_at, updated_at) VALUES (4, '04', 6, 'available', 'QR-TBL-04-D6W4', '6W15D', '2026-10-08 03:06:25', '2026-11-17 02:11:13');
INSERT INTO tables (id, table_number, capacity, status, qr_code_token, access_code, created_at, updated_at) VALUES (5, '05', 2, 'available', 'QR-TBL-05-E5V5', '5V39E', '2026-10-08 03:06:25', '2026-11-17 02:11:12');
INSERT INTO tables (id, table_number, capacity, status, qr_code_token, access_code, created_at, updated_at) VALUES (6, 'VIP-1', 8, 'available', 'QR-TBL-VIP1-F4U6', '4U72F', '2026-10-08 03:06:25', '2026-11-20 00:41:51');

-- 6. INSERT CATEGORIES
INSERT INTO categories (id, name, slug, icon, is_active, created_at, updated_at) VALUES (1, 'Signature Coffee', 'signature-coffee', 'coffee', TRUE, '2026-10-08 03:06:25', '2026-10-08 03:06:25');
INSERT INTO categories (id, name, slug, icon, is_active, created_at, updated_at) VALUES (2, 'Espresso Based', 'espresso-based', 'cup-hot', TRUE, '2026-10-08 03:06:25', '2026-10-08 03:06:25');
INSERT INTO categories (id, name, slug, icon, is_active, created_at, updated_at) VALUES (3, 'Non-Coffee & Milk', 'non-coffee-milk', 'cup-straw', TRUE, '2026-10-08 03:06:25', '2026-10-08 03:06:25');
INSERT INTO categories (id, name, slug, icon, is_active, created_at, updated_at) VALUES (4, 'Artisan Tea & Refreshers', 'tea-refreshers', 'glass-water', TRUE, '2026-10-08 03:06:25', '2026-10-08 03:06:25');
INSERT INTO categories (id, name, slug, icon, is_active, created_at, updated_at) VALUES (5, 'Pastry & Bakery', 'pastry-bakery', 'cookie', TRUE, '2026-10-08 03:06:25', '2026-10-08 03:06:25');
INSERT INTO categories (id, name, slug, icon, is_active, created_at, updated_at) VALUES (6, 'Meals & Snacks', 'meals-snacks', 'utensils', TRUE, '2026-10-08 03:06:25', '2026-10-08 03:06:25');

-- 7. INSERT MENUS
INSERT INTO menus (id, category_id, name, slug, description, price, image, is_available, taste_profile, created_at, updated_at) VALUES (1, 1, 'Kopi Susu Gula Aren Senja', 'kopi-susu-gula-aren-senja', 'Espresso blend lokal dipadu susu segar pilihan dan sirup gula aren organik premium.', 22000.00, 'images/menus/kopi-susu-gula-aren-senja.jpg', TRUE, '["sweet","creamy","coffee","traditional","cold"]'::jsonb, '2026-10-08 03:06:25', '2026-10-13 02:31:38');
INSERT INTO menus (id, category_id, name, slug, description, price, image, is_available, taste_profile, created_at, updated_at) VALUES (2, 1, 'Caramel Cloud Macchiato', 'caramel-cloud-macchiato', 'Layered vanilla milk foam dengan shot espresso pekat dan lelehan saus karamel gurih.', 28000.00, 'images/menus/caramel-cloud-macchiato.jpg', TRUE, '["sweet","caramel","creamy","mild coffee","hot_or_cold"]'::jsonb, '2026-10-08 03:06:25', '2026-10-13 02:31:38');
INSERT INTO menus (id, category_id, name, slug, description, price, image, is_available, taste_profile, created_at, updated_at) VALUES (3, 1, 'Hazelnut Dolce Latte', 'hazelnut-dolce-latte', 'Sentuhan kacang hazelnut panggang berpadu espresso lembut dan susu kental manis.', 26000.00, 'images/menus/hazelnut-dolce-latte.jpg', TRUE, '["nutty","sweet","creamy","coffee","smooth"]'::jsonb, '2026-10-08 03:06:25', '2026-10-13 02:31:38');
INSERT INTO menus (id, category_id, name, slug, description, price, image, is_available, taste_profile, created_at, updated_at) VALUES (4, 2, 'Caffe Americano Double Shot', 'caffe-americano-double-shot', 'Dua shot espresso Arabika murni dengan air mineral panas/dingin. Bold tanpa ampas.', 18000.00, 'images/menus/caffe-americano-double-shot.jpg', TRUE, '["strong","bitter","bold","low acid","no sugar"]'::jsonb, '2026-10-08 03:06:25', '2026-10-13 02:31:38');
INSERT INTO menus (id, category_id, name, slug, description, price, image, is_available, taste_profile, created_at, updated_at) VALUES (5, 2, 'Classic Cappuccino', 'classic-cappuccino', 'Keseimbangan sepertiga espresso, steamed milk, dan foam tebal bertabur bubuk cokelat.', 24000.00, 'images/menus/classic-cappuccino.jpg', TRUE, '["bold","foamy","mild coffee","warm","classic"]'::jsonb, '2026-10-08 03:06:25', '2026-10-13 02:31:38');
INSERT INTO menus (id, category_id, name, slug, description, price, image, is_available, taste_profile, created_at, updated_at) VALUES (6, 2, 'Vanilla Oat Flat White', 'vanilla-oat-flat-white', 'Double ristretto dengan microfoam susu oat nabati dan ekstrak vanila murni Madagaskar.', 29000.00, 'images/menus/vanilla-oat-flat-white.jpg', TRUE, '["smooth","nutty","creamy","plant-based","mild coffee"]'::jsonb, '2026-10-08 03:06:25', '2026-10-13 02:31:38');
INSERT INTO menus (id, category_id, name, slug, description, price, image, is_available, taste_profile, created_at, updated_at) VALUES (7, 3, 'Uji Matcha Cream Frappe', 'uji-matcha-cream-frappe', 'Bubuk matcha murni asli Kyoto Jepang berpadu susu dan whipped cream lembut.', 28000.00, 'images/menus/uji-matcha-cream-frappe.jpg', TRUE, '["earthy","sweet","creamy","non-coffee","cold"]'::jsonb, '2026-10-08 03:06:25', '2026-10-13 02:31:38');
INSERT INTO menus (id, category_id, name, slug, description, price, image, is_available, taste_profile, created_at, updated_at) VALUES (8, 3, 'Dark Belgian Chocolate Ice', 'dark-belgian-chocolate', 'Cokelat hitam 70% Belgia yang pekat, legit, dan menghangatkan suasana.', 27000.00, 'images/menus/dark-belgian-chocolate-ice.jpg', TRUE, '["rich chocolate","sweet","thick","creamy","non-coffee"]'::jsonb, '2026-10-08 03:06:25', '2026-10-13 02:31:38');
INSERT INTO menus (id, category_id, name, slug, description, price, image, is_available, taste_profile, created_at, updated_at) VALUES (9, 3, 'Red Velvet Marshmallow', 'red-velvet-marshmallow', 'Rasa kue red velvet lembut dengan taburan toasted marshmallow meleleh di atasnya.', 26000.00, 'images/menus/red-velvet-marshmallow.jpg', TRUE, '["sweet","vanilla","creamy","dessert","non-coffee"]'::jsonb, '2026-10-08 03:06:25', '2026-10-13 02:31:38');
INSERT INTO menus (id, category_id, name, slug, description, price, image, is_available, taste_profile, created_at, updated_at) VALUES (10, 4, 'Berry Citrus Sparkler Soda', 'berry-citrus-sparkler', 'Kombinasi stroberi, blueberry liar, perasan jeruk lemon, dan air soda segar.', 23000.00, 'images/menus/berry-citrus-sparkler-soda.jpg', TRUE, '["fresh","fruity","sour","sparkling","cold","non-coffee"]'::jsonb, '2026-10-08 03:06:25', '2026-10-13 02:31:38');
INSERT INTO menus (id, category_id, name, slug, description, price, image, is_available, taste_profile, created_at, updated_at) VALUES (11, 4, 'Royal Earl Grey Lavender Tea', 'royal-earl-grey-lavender-tea', 'Seduhan teh hitam bergamot aromatik dengan kelopak lavender yang merilekskan.', 20000.00, 'images/menus/royal-earl-grey-lavender-tea.jpg', TRUE, '["floral","aromatic","calming","tea","hot","refreshing"]'::jsonb, '2026-10-08 03:06:25', '2026-10-13 02:31:38');
INSERT INTO menus (id, category_id, name, slug, description, price, image, is_available, taste_profile, created_at, updated_at) VALUES (12, 5, 'French Butter Croissant', 'french-butter-croissant', 'Croissant renyah berlapis dengan mentega Prancis harum dan tekstur flaky sempurna.', 22000.00, 'images/menus/french-butter-croissant.jpg', TRUE, '["buttery","savory","crispy","flaky","snack"]'::jsonb, '2026-10-08 03:06:25', '2026-10-13 02:31:39');
INSERT INTO menus (id, category_id, name, slug, description, price, image, is_available, taste_profile, created_at, updated_at) VALUES (13, 5, 'Molten Chocolate Lava Cake', 'molten-chocolate-lava-cake', 'Kue cokelat hangat dengan lelehan cokelat pekat di bagian dalam.', 28000.00, 'images/menus/molten-chocolate-lava-cake.jpg', TRUE, '["sweet","rich chocolate","warm","dessert"]'::jsonb, '2026-10-08 03:06:25', '2026-10-13 02:31:39');
INSERT INTO menus (id, category_id, name, slug, description, price, image, is_available, taste_profile, created_at, updated_at) VALUES (14, 6, 'Truffle Parmesan Fries', 'truffle-parmesan-fries', 'Kentang goreng renyah dengan aroma minyak truffle putih dan parutan keju parmesan.', 25000.00, 'images/menus/truffle-parmesan-fries.jpg', TRUE, '["savory","salty","crispy","snack","truffle"]'::jsonb, '2026-10-08 03:06:25', '2026-10-13 02:31:39');
INSERT INTO menus (id, category_id, name, slug, description, price, image, is_available, taste_profile, created_at, updated_at) VALUES (15, 6, 'Creamy Beef Carbonara', 'creamy-beef-carbonara', 'Spageti saus krim gurih dengan potongan smoked beef renyah dan kuning telur segar.', 38000.00, 'images/menus/creamy-beef-carbonara.jpg', TRUE, '["creamy","savory","heavy meal","pasta","warm"]'::jsonb, '2026-10-08 03:06:25', '2026-10-13 02:31:39');
INSERT INTO menus (id, category_id, name, slug, description, price, image, is_available, taste_profile, created_at, updated_at) VALUES (16, 1, 'Avocado Espresso Float', 'avocado-espresso-float', 'Jus alpukat kental berpadu shot espresso pekat dan topping es krim vanila.', 32000.00, 'images/menus/avocado-espresso-float.jpg', TRUE, '["creamy","avocado","sweet","coffee","cold"]'::jsonb, '2026-10-13 02:31:38', '2026-10-13 02:31:38');
INSERT INTO menus (id, category_id, name, slug, description, price, image, is_available, taste_profile, created_at, updated_at) VALUES (17, 2, 'Spanish Latte Creamy', 'spanish-latte-creamy', 'Latte khas Spanyol dengan kombinasi susu segar dan kental manis yang memikat.', 25000.00, 'images/menus/spanish-latte-creamy.jpg', TRUE, '["sweet","creamy","milky","smooth","coffee"]'::jsonb, '2026-10-13 02:31:38', '2026-10-13 02:31:38');
INSERT INTO menus (id, category_id, name, slug, description, price, image, is_available, taste_profile, created_at, updated_at) VALUES (18, 3, 'Taro Coconut Velvet', 'taro-coconut-velvet', 'Rasa talas ungu manis dipadu gurihnya krim santan lembut yang menyegarkan.', 25000.00, 'images/menus/taro-coconut-velvet.jpg', TRUE, '["taro","sweet","creamy","coconut","non-coffee"]'::jsonb, '2026-10-13 02:31:38', '2026-10-13 02:31:38');
INSERT INTO menus (id, category_id, name, slug, description, price, image, is_available, taste_profile, created_at, updated_at) VALUES (19, 4, 'Peach Mint Iced Tea', 'peach-mint-iced-tea', 'Teh melati dingin dengan sirup buah persik asli dan remasan daun mint segar.', 22000.00, 'images/menus/peach-mint-iced-tea.jpg', TRUE, '["fruity","peach","mint","refreshing","cold","tea"]'::jsonb, '2026-10-13 02:31:39', '2026-11-10 00:28:53');
INSERT INTO menus (id, category_id, name, slug, description, price, image, is_available, taste_profile, created_at, updated_at) VALUES (20, 4, 'Lychee Yakult Blossom', 'lychee-yakult-blossom', 'Kombinasi probiotik Yakult segar, sirup leci premium, dan buah leci utuh.', 24000.00, 'images/menus/lychee-yakult-blossom.jpg', TRUE, '["probiotic","sweet","sour","lychee","cold","refreshing"]'::jsonb, '2026-10-13 02:31:39', '2026-10-13 02:31:39');
INSERT INTO menus (id, category_id, name, slug, description, price, image, is_available, taste_profile, created_at, updated_at) VALUES (21, 5, 'New York Baked Cheesecake', 'new-york-baked-cheesecake', 'Kue keju panggang padat gaya New York dengan lapisan biskuit renyah di bawahnya.', 30000.00, 'images/menus/new-york-baked-cheesecake.jpg', TRUE, '["cheesy","sweet","rich","creamy","dessert"]'::jsonb, '2026-10-13 02:31:39', '2026-10-13 02:31:39');
INSERT INTO menus (id, category_id, name, slug, description, price, image, is_available, taste_profile, created_at, updated_at) VALUES (22, 5, 'Cinnamon Roll Cream Glaze', 'cinnamon-roll-cream-glaze', 'Roti gulung kayu manis harum yang baru dipanggang dengan siraman saus keju manis.', 24000.00, 'images/menus/cinnamon-roll-cream-glaze.jpg', TRUE, '["cinnamon","sweet","warm","glaze","bakery"]'::jsonb, '2026-10-13 02:31:39', '2026-10-13 02:31:39');
INSERT INTO menus (id, category_id, name, slug, description, price, image, is_available, taste_profile, created_at, updated_at) VALUES (23, 6, 'Nasi Goreng Spesial Nongkrong', 'nasi-goreng-spesial-nongkrong', 'Nasi goreng bumbu rempah nusantara dengan ayam suwir, telur mata sapi, dan kerupuk renyah.', 32000.00, 'images/menus/nasi-goreng-spesial-nongkrong.jpg', TRUE, '["savory","spicy","traditional","heavy meal","egg"]'::jsonb, '2026-10-13 02:31:39', '2026-10-13 02:31:39');
INSERT INTO menus (id, category_id, name, slug, description, price, image, is_available, taste_profile, created_at, updated_at) VALUES (24, 6, 'Chicken Katsu Curry Bowl', 'chicken-katsu-curry-bowl', 'Fillet ayam goreng katsu renyah disajikan dengan kuah kari Jepang kental dan nasi pulen.', 36000.00, 'images/menus/chicken-katsu-curry-bowl.jpg', TRUE, '["crispy","curry","savory","heavy meal","warm"]'::jsonb, '2026-10-13 02:31:39', '2026-10-13 02:31:39');
INSERT INTO menus (id, category_id, name, slug, description, price, image, is_available, taste_profile, created_at, updated_at) VALUES (25, 6, 'Crispy Chicken Popcorn BBQ', 'crispy-chicken-popcorn-bbq', 'Daging ayam potong dadu berbalut tepung bumbu krispi disajikan dengan saus celup BBQ.', 26000.00, 'images/menus/crispy-chicken-popcorn-bbq.jpg', TRUE, '["crispy","bbq","savory","snack","chicken"]'::jsonb, '2026-10-13 02:31:39', '2026-10-13 02:31:39');

-- 8. INSERT SETTINGS
INSERT INTO settings (id, key, value, created_at, updated_at) VALUES (2, 'ai_config', '{"model":"gemini-1.5-flash","tonePersona":"friendly","temperature":0.2,"upsellStrategy":"soft","systemPrompt":"Kamu adalah Gemini Barista pintar di kafe NONGKRONG.id. Karaktermu ramah, asyik, dan paham cita rasa kopi. Tugasmu membantu pelanggan memilih minuman atau camilan terbaik sesuai mood, rasa manis, kafein, dan preferensi mereka. Rekomendasikan menu yang ada di katalog dengan menyebutkan harganya."}', '2026-11-09 04:39:52', '2026-11-10 00:54:20');

-- 9. RE-SYNC SERIAL SEQUENCES
SELECT setval('migrations_id_seq', COALESCE((SELECT MAX(id) FROM migrations), 1));
SELECT setval('users_id_seq', COALESCE((SELECT MAX(id) FROM users), 1));
SELECT setval('tables_id_seq', COALESCE((SELECT MAX(id) FROM tables), 1));
SELECT setval('categories_id_seq', COALESCE((SELECT MAX(id) FROM categories), 1));
SELECT setval('menus_id_seq', COALESCE((SELECT MAX(id) FROM menus), 1));
SELECT setval('orders_id_seq', COALESCE((SELECT MAX(id) FROM orders), 1));
SELECT setval('order_items_id_seq', COALESCE((SELECT MAX(id) FROM order_items), 1));
SELECT setval('ai_consultations_id_seq', COALESCE((SELECT MAX(id) FROM ai_consultations), 1));
SELECT setval('settings_id_seq', COALESCE((SELECT MAX(id) FROM settings), 1));

COMMIT;
