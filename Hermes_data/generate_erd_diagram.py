import os
import subprocess

def generate_erd_svg():
    width = 1860
    height = 1260
    
    # SVG Definition
    svg = []
    svg.append(f'''<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {width} {height}" width="{width}" height="{height}" style="background-color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <defs>
        <!-- Marker Crow Foot Many -->
        <marker id="crow_many" markerWidth="16" markerHeight="16" refX="14" refY="8" orient="auto">
            <line x1="2" y1="2" x2="14" y2="8" stroke="#1e293b" stroke-width="2"/>
            <line x1="2" y1="14" x2="14" y2="8" stroke="#1e293b" stroke-width="2"/>
            <line x1="2" y1="8" x2="14" y2="8" stroke="#1e293b" stroke-width="2"/>
            <line x1="14" y1="1" x2="14" y2="15" stroke="#1e293b" stroke-width="2"/>
        </marker>
        <!-- Marker One Bar -->
        <marker id="one_bar" markerWidth="14" markerHeight="14" refX="2" refY="7" orient="auto">
            <line x1="4" y1="1" x2="4" y2="13" stroke="#1e293b" stroke-width="2"/>
            <line x1="9" y1="1" x2="9" y2="13" stroke="#1e293b" stroke-width="2"/>
        </marker>
    </defs>

    <!-- Canvas Background -->
    <rect width="{width}" height="{height}" fill="#ffffff"/>
    <rect x="20" y="20" width="{width-40}" height="{height-40}" fill="none" stroke="#0f172a" stroke-width="2"/>

    <!-- ================= HEADER SECTION ================= -->
    <rect x="40" y="38" width="850" height="75" fill="#f8fafc" stroke="#334155" stroke-width="1.5" rx="0"/>
    <rect x="40" y="38" width="10" height="75" fill="#1e3a8a"/>
    <text x="65" y="68" font-size="22" font-weight="800" fill="#0f172a" letter-spacing="0.5">ENTITY RELATIONSHIP DIAGRAM (ERD)</text>
    <text x="65" y="93" font-size="14" font-weight="600" fill="#475569">Sistem Smart Cafe POS &amp; AI Recommender • Database: <tspan fill="#1e3a8a" font-weight="700">nongkrong_id</tspan> (MariaDB / 3NF)</text>

    <!-- ================= LEGEND BOX ================= -->
    <g transform="translate(1220, 38)">
        <rect width="580" height="75" fill="#f8fafc" stroke="#334155" stroke-width="1.5"/>
        <text x="15" y="22" font-size="12" font-weight="800" fill="#0f172a" text-transform="uppercase" letter-spacing="0.5">LEGENDA RELASI &amp; ATRIBUT</text>
        
        <!-- Legend Items -->
        <rect x="15" y="35" width="28" height="15" fill="#fef08a" stroke="#ca8a04" stroke-width="1"/>
        <text x="29" y="46" font-size="9" font-weight="800" fill="#854d0e" text-anchor="middle">PK</text>
        <text x="50" y="47" font-size="11" font-weight="600" fill="#334155">Primary Key</text>

        <rect x="140" y="35" width="28" height="15" fill="#dbeafe" stroke="#2563eb" stroke-width="1"/>
        <text x="154" y="46" font-size="9" font-weight="800" fill="#1e40af" text-anchor="middle">FK</text>
        <text x="175" y="47" font-size="11" font-weight="600" fill="#334155">Foreign Key</text>

        <line x1="270" y1="42" x2="310" y2="42" stroke="#1e293b" stroke-width="2"/>
        <text x="290" y="35" font-size="10" font-weight="700" fill="#1e3a8a" text-anchor="middle">1 : N</text>
        <text x="320" y="47" font-size="11" font-weight="600" fill="#334155">Relasi One-to-Many</text>

        <!-- New Badge -->
        <rect x="450" y="35" width="38" height="15" fill="#dcfce7" stroke="#16a34a" stroke-width="1"/>
        <text x="469" y="46" font-size="9" font-weight="800" fill="#15803d" text-anchor="middle">NEW</text>
        <text x="495" y="47" font-size="11" font-weight="600" fill="#334155">Manual PIN (5 Digit)</text>
    </g>
''')

    # Helper function to render a table card
    def render_table_card(x, y, w, title, rows, badge_text=""):
        res = []
        row_h = 24
        header_h = 36
        sub_h = 20
        total_h = header_h + sub_h + len(rows) * row_h
        
        # Outer Card Border & Fill
        res.append(f'<g id="table_{title.lower()}">')
        res.append(f'<rect x="{x}" y="{y}" width="{w}" height="{total_h}" fill="#ffffff" stroke="#1e293b" stroke-width="2"/>')
        
        # Header Rectangle (Solid Flat Dark Slate/Navy)
        res.append(f'<rect x="{x}" y="{y}" width="{w}" height="{header_h}" fill="#0f172a" stroke="#1e293b" stroke-width="1"/>')
        res.append(f'<text x="{x+14}" y="{y+23}" font-size="14" font-weight="800" fill="#ffffff" letter-spacing="0.5">{title}</text>')
        if badge_text:
            res.append(f'<rect x="{x+w-120}" y="{y+8}" width="108" height="19" fill="#334155" stroke="#475569" stroke-width="1"/>')
            res.append(f'<text x="{x+w-66}" y="{y+21}" font-size="10" font-weight="700" fill="#cbd5e1" text-anchor="middle">{badge_text}</text>')
        
        # Subheader Column Titles
        sub_y = y + header_h
        res.append(f'<rect x="{x}" y="{sub_y}" width="{w}" height="{sub_h}" fill="#f1f5f9" stroke="#cbd5e1" stroke-width="1"/>')
        res.append(f'<text x="{x+12}" y="{sub_y+14}" font-size="10" font-weight="700" fill="#475569">KEY</text>')
        res.append(f'<text x="{x+58}" y="{sub_y+14}" font-size="10" font-weight="700" fill="#475569">FIELD NAME</text>')
        res.append(f'<text x="{x+220}" y="{sub_y+14}" font-size="10" font-weight="700" fill="#475569">TYPE</text>')
        res.append(f'<text x="{x+w-12}" y="{sub_y+14}" font-size="10" font-weight="700" fill="#475569" text-anchor="end">CONSTRAINT</text>')
        
        # Rows
        for i, row in enumerate(rows):
            curr_y = sub_y + sub_h + i * row_h
            bg = "#ffffff" if i % 2 == 0 else "#f8fafc"
            res.append(f'<rect x="{x}" y="{curr_y}" width="{w}" height="{row_h}" fill="{bg}" stroke="#e2e8f0" stroke-width="0.8"/>')
            
            # Key badge
            ktype = row.get("key", "")
            if ktype == "PK":
                res.append(f'<rect x="{x+8}" y="{curr_y+4}" width="26" height="15" fill="#fef08a" stroke="#ca8a04" stroke-width="1"/>')
                res.append(f'<text x="{x+21}" y="{curr_y+15}" font-size="9" font-weight="800" fill="#854d0e" text-anchor="middle">PK</text>')
            elif ktype == "FK":
                res.append(f'<rect x="{x+8}" y="{curr_y+4}" width="26" height="15" fill="#dbeafe" stroke="#2563eb" stroke-width="1"/>')
                res.append(f'<text x="{x+21}" y="{curr_y+15}" font-size="9" font-weight="800" fill="#1e40af" text-anchor="middle">FK</text>')
            elif ktype == "NEW":
                res.append(f'<rect x="{x+6}" y="{curr_y+4}" width="30" height="15" fill="#dcfce7" stroke="#16a34a" stroke-width="1"/>')
                res.append(f'<text x="{x+21}" y="{curr_y+15}" font-size="8" font-weight="800" fill="#15803d" text-anchor="middle">NEW</text>')
            else:
                res.append(f'<circle cx="{x+21}" cy="{curr_y+12}" r="2" fill="#94a3b8"/>')
                
            # Field Name
            fname = row.get("name", "")
            fcolor = "#0f172a" if ktype in ["PK", "FK", "NEW"] else "#334155"
            fweight = "700" if ktype in ["PK", "FK", "NEW"] else "500"
            res.append(f'<text x="{x+58}" y="{curr_y+16}" font-size="11" font-weight="{fweight}" fill="{fcolor}" font-family="monospace">{fname}</text>')
            
            # Field Type
            ftype = row.get("type", "")
            res.append(f'<text x="{x+220}" y="{curr_y+16}" font-size="10" font-weight="500" fill="#64748b">{ftype}</text>')
            
            # Constraint
            fconst = row.get("const", "")
            res.append(f'<text x="{x+w-12}" y="{curr_y+16}" font-size="9.5" font-weight="600" fill="#475569" text-anchor="end">{fconst}</text>')
            
        res.append('</g>')
        return "\n".join(res), total_h

    # Definition of tables
    # Col 1 (Left): CATEGORIES, MENUS, AI_CONSULTATIONS
    # Col 2 (Center): ORDER_ITEMS
    # Col 3 (Right): USERS, ORDERS, TABLES

    cards_svg = []
    
    # 1. CATEGORIES (Col 1, Top)
    cat_rows = [
        {"key": "PK", "name": "id", "type": "BIGINT UNSIGNED", "const": "AUTO_INCREMENT"},
        {"key": "", "name": "name", "type": "VARCHAR(100)", "const": "NOT NULL"},
        {"key": "", "name": "slug", "type": "VARCHAR(100)", "const": "UNIQUE"},
        {"key": "", "name": "icon", "type": "VARCHAR(50)", "const": "NULL"},
        {"key": "", "name": "is_active", "type": "BOOLEAN", "const": "DEFAULT TRUE"},
        {"key": "", "name": "created_at", "type": "TIMESTAMP", "const": "NULL"},
        {"key": "", "name": "updated_at", "type": "TIMESTAMP", "const": "NULL"},
    ]
    svg_cat, h_cat = render_table_card(60, 160, 440, "categories", cat_rows, "Katalog Kategori")
    cards_svg.append(svg_cat)

    # 2. MENUS (Col 1, Middle)
    menu_rows = [
        {"key": "PK", "name": "id", "type": "BIGINT UNSIGNED", "const": "AUTO_INCREMENT"},
        {"key": "FK", "name": "category_id", "type": "BIGINT UNSIGNED", "const": "CASCADE"},
        {"key": "", "name": "name", "type": "VARCHAR(150)", "const": "NOT NULL"},
        {"key": "", "name": "price", "type": "DECIMAL(10,2)", "const": "NOT NULL"},
        {"key": "", "name": "description", "type": "TEXT", "const": "NULL"},
        {"key": "", "name": "image_path", "type": "VARCHAR(255)", "const": "NULL"},
        {"key": "", "name": "is_available", "type": "BOOLEAN", "const": "DEFAULT TRUE"},
        {"key": "", "name": "taste_profile", "type": "JSON", "const": "AI PROMPT TAGS"},
        {"key": "", "name": "created_at", "type": "TIMESTAMP", "const": "NULL"},
        {"key": "", "name": "updated_at", "type": "TIMESTAMP", "const": "NULL"},
    ]
    svg_menu, h_menu = render_table_card(60, 450, 440, "menus", menu_rows, "Katalog Produk")
    cards_svg.append(svg_menu)

    # 3. AI_CONSULTATIONS (Col 1, Bottom)
    ai_rows = [
        {"key": "PK", "name": "id", "type": "BIGINT UNSIGNED", "const": "AUTO_INCREMENT"},
        {"key": "FK", "name": "table_id", "type": "BIGINT UNSIGNED", "const": "NULL / CASCADE"},
        {"key": "", "name": "customer_preference", "type": "TEXT", "const": "USER QUERY"},
        {"key": "", "name": "ai_response", "type": "TEXT", "const": "BARISTA TEXT"},
        {"key": "", "name": "recommended_ids", "type": "JSON", "const": "MENU ID ARRAY"},
        {"key": "", "name": "is_converted", "type": "BOOLEAN", "const": "DEFAULT FALSE"},
        {"key": "", "name": "created_at", "type": "TIMESTAMP", "const": "NULL"},
        {"key": "", "name": "updated_at", "type": "TIMESTAMP", "const": "NULL"},
    ]
    svg_ai, h_ai = render_table_card(60, 840, 440, "ai_consultations", ai_rows, "Log Audit AI")
    cards_svg.append(svg_ai)

    # 4. ORDER_ITEMS (Col 2, Middle Center)
    item_rows = [
        {"key": "PK", "name": "id", "type": "BIGINT UNSIGNED", "const": "AUTO_INCREMENT"},
        {"key": "FK", "name": "order_id", "type": "BIGINT UNSIGNED", "const": "CASCADE"},
        {"key": "FK", "name": "menu_id", "type": "BIGINT UNSIGNED", "const": "RESTRICT"},
        {"key": "", "name": "price", "type": "DECIMAL(10,2)", "const": "SNAPSHOT UNIT"},
        {"key": "", "name": "quantity", "type": "INT UNSIGNED", "const": "DEFAULT 1"},
        {"key": "", "name": "subtotal", "type": "DECIMAL(10,2)", "const": "UNIT x QTY"},
        {"key": "", "name": "notes", "type": "VARCHAR(255)", "const": "CUSTOM ORDER"},
        {"key": "", "name": "created_at", "type": "TIMESTAMP", "const": "NULL"},
        {"key": "", "name": "updated_at", "type": "TIMESTAMP", "const": "NULL"},
    ]
    svg_item, h_item = render_table_card(680, 460, 440, "order_items", item_rows, "Pivot Line Items")
    cards_svg.append(svg_item)

    # 5. USERS (Col 3, Top)
    user_rows = [
        {"key": "PK", "name": "id", "type": "BIGINT UNSIGNED", "const": "AUTO_INCREMENT"},
        {"key": "", "name": "name", "type": "VARCHAR(100)", "const": "NOT NULL"},
        {"key": "", "name": "email", "type": "VARCHAR(150)", "const": "UNIQUE"},
        {"key": "", "name": "password", "type": "VARCHAR(255)", "const": "HASHED"},
        {"key": "", "name": "role", "type": "ENUM('admin','staff')", "const": "DEFAULT 'staff'"},
        {"key": "", "name": "created_at", "type": "TIMESTAMP", "const": "NULL"},
        {"key": "", "name": "updated_at", "type": "TIMESTAMP", "const": "NULL"},
    ]
    svg_user, h_user = render_table_card(1320, 160, 480, "users", user_rows, "Akun Karyawan")
    cards_svg.append(svg_user)

    # 6. ORDERS (Col 3, Middle)
    order_rows = [
        {"key": "PK", "name": "id", "type": "BIGINT UNSIGNED", "const": "AUTO_INCREMENT"},
        {"key": "", "name": "order_code", "type": "VARCHAR(32)", "const": "UNIQUE INDEX"},
        {"key": "FK", "name": "table_id", "type": "BIGINT UNSIGNED", "const": "NULL (WALK-IN)"},
        {"key": "FK", "name": "user_id", "type": "BIGINT UNSIGNED", "const": "NULL (KASIR)"},
        {"key": "", "name": "customer_name", "type": "VARCHAR(100)", "const": "NOT NULL"},
        {"key": "", "name": "order_status", "type": "ENUM(5 STATES)", "const": "INDEX"},
        {"key": "", "name": "payment_status", "type": "ENUM('unpaid','paid')", "const": "INDEX"},
        {"key": "", "name": "payment_method", "type": "VARCHAR(30)", "const": "QRIS/CASH"},
        {"key": "", "name": "total_amount", "type": "DECIMAL(10,2)", "const": "GRAND TOTAL"},
        {"key": "", "name": "notes", "type": "TEXT", "const": "NULL"},
        {"key": "", "name": "created_at", "type": "TIMESTAMP", "const": "NULL"},
        {"key": "", "name": "updated_at", "type": "TIMESTAMP", "const": "NULL"},
    ]
    svg_order, h_order = render_table_card(1320, 460, 480, "orders", order_rows, "Header Transaksi")
    cards_svg.append(svg_order)

    # 7. TABLES (Col 3, Bottom)
    table_rows = [
        {"key": "PK", "name": "id", "type": "BIGINT UNSIGNED", "const": "AUTO_INCREMENT"},
        {"key": "", "name": "table_number", "type": "VARCHAR(20)", "const": "UNIQUE"},
        {"key": "", "name": "capacity", "type": "INT UNSIGNED", "const": "DEFAULT 4"},
        {"key": "", "name": "status", "type": "ENUM(3 STATES)", "const": "AVAILABLE/OCC"},
        {"key": "", "name": "qr_code_token", "type": "VARCHAR(64)", "const": "UNIQUE"},
        {"key": "NEW", "name": "access_code", "type": "VARCHAR(10)", "const": "5-CHAR MANUAL PIN"},
        {"key": "", "name": "created_at", "type": "TIMESTAMP", "const": "NULL"},
        {"key": "", "name": "updated_at", "type": "TIMESTAMP", "const": "NULL"},
    ]
    svg_table, h_table = render_table_card(1320, 890, 480, "tables", table_rows, "Master Meja Kafe")
    cards_svg.append(svg_table)

    # Append all table cards
    svg.extend(cards_svg)

    # ================= RELATIONSHIP CONNECTOR LINES =================
    # Clean orthogonal lines with crow-foot and labels
    connectors = []

    # 1. categories (1) -> menus (N)
    # categories bottom (X: 280, Y: 160 + h_cat) to menus top (X: 280, Y: 450)
    cat_bottom_y = 160 + h_cat
    connectors.append(f'''
    <!-- Relasi categories -> menus -->
    <g id="rel_cat_menus">
        <line x1="280" y1="{cat_bottom_y}" x2="280" y2="450" stroke="#1e293b" stroke-width="2.5" marker-start="url(#one_bar)" marker-end="url(#crow_many)"/>
        <rect x="235" y="{cat_bottom_y + 18}" width="90" height="20" fill="#ffffff" stroke="#334155" stroke-width="1.2"/>
        <text x="280" y="{cat_bottom_y + 32}" font-size="10" font-weight="800" fill="#1e3a8a" text-anchor="middle">1 : N (FK)</text>
    </g>
    ''')

    # 2. menus (1) -> order_items (N)
    # menus right at id/name (X: 500, Y: 490) to order_items left at menu_id (X: 680, Y: 535)
    connectors.append(f'''
    <!-- Relasi menus -> order_items -->
    <g id="rel_menus_items">
        <path d="M 500 490 L 590 490 L 590 535 L 680 535" fill="none" stroke="#1e293b" stroke-width="2.5" marker-start="url(#one_bar)" marker-end="url(#crow_many)"/>
        <rect x="545" y="478" width="90" height="20" fill="#ffffff" stroke="#334155" stroke-width="1.2"/>
        <text x="590" y="492" font-size="10" font-weight="800" fill="#1e3a8a" text-anchor="middle">1 : N (FK)</text>
    </g>
    ''')

    # 3. orders (1) -> order_items (N)
    # orders left at id row (X: 1320, Y: 490) to order_items right at order_id row (X: 1120, Y: 510)
    connectors.append(f'''
    <!-- Relasi orders -> order_items -->
    <g id="rel_orders_items">
        <path d="M 1320 490 L 1220 490 L 1220 510 L 1120 510" fill="none" stroke="#1e293b" stroke-width="2.5" marker-start="url(#one_bar)" marker-end="url(#crow_many)"/>
        <rect x="1175" y="478" width="90" height="20" fill="#ffffff" stroke="#334155" stroke-width="1.2"/>
        <text x="1220" y="492" font-size="10" font-weight="800" fill="#1e3a8a" text-anchor="middle">1 : N (FK)</text>
    </g>
    ''')

    # 4. users (1) -> orders (N)
    # users bottom (X: 1560, Y: 160 + h_user) to orders top (X: 1560, Y: 460)
    user_bottom_y = 160 + h_user
    connectors.append(f'''
    <!-- Relasi users -> orders -->
    <g id="rel_users_orders">
        <line x1="1560" y1="{user_bottom_y}" x2="1560" y2="460" stroke="#1e293b" stroke-width="2.5" marker-start="url(#one_bar)" marker-end="url(#crow_many)"/>
        <rect x="1515" y="{user_bottom_y + 14}" width="90" height="20" fill="#ffffff" stroke="#334155" stroke-width="1.2"/>
        <text x="1560" y="{user_bottom_y + 28}" font-size="10" font-weight="800" fill="#1e3a8a" text-anchor="middle">1 : N (Kasir)</text>
    </g>
    ''')

    # 5. tables (1) -> orders (N)
    # tables top (X: 1440, Y: 890) to orders bottom (X: 1440, Y: 460 + h_order)
    order_bottom_y = 460 + h_order
    connectors.append(f'''
    <!-- Relasi tables -> orders -->
    <g id="rel_tables_orders">
        <line x1="1440" y1="890" x2="1440" y2="{order_bottom_y}" stroke="#1e293b" stroke-width="2.5" marker-start="url(#one_bar)" marker-end="url(#crow_many)"/>
        <rect x="1395" y="{order_bottom_y + 28}" width="90" height="20" fill="#ffffff" stroke="#334155" stroke-width="1.2"/>
        <text x="1440" y="{order_bottom_y + 42}" font-size="10" font-weight="800" fill="#1e3a8a" text-anchor="middle">1 : N (Meja)</text>
    </g>
    ''')

    # 6. tables (1) -> ai_consultations (N)
    # tables left (X: 1320, Y: 960) to ai_consultations right (X: 500, Y: 910)
    # Path routes through center gutter safely:
    connectors.append(f'''
    <!-- Relasi tables -> ai_consultations -->
    <g id="rel_tables_ai">
        <path d="M 1320 960 L 910 960 L 910 910 L 500 910" fill="none" stroke="#1e293b" stroke-width="2.5" marker-start="url(#one_bar)" marker-end="url(#crow_many)"/>
        <rect x="865" y="948" width="90" height="20" fill="#ffffff" stroke="#334155" stroke-width="1.2"/>
        <text x="910" y="962" font-size="10" font-weight="800" fill="#1e3a8a" text-anchor="middle">1 : N (Sesi AI)</text>
    </g>
    ''')

    svg.extend(connectors)

    # ================= FOOTER METADATA =================
    svg.append(f'''
    <!-- Footer Note -->
    <rect x="40" y="{height-50}" width="{width-80}" height="32" fill="#f8fafc" stroke="#cbd5e1" stroke-width="1"/>
    <text x="55" y="{height-30}" font-size="11" font-weight="700" fill="#334155">Integritas Relasional:</text>
    <text x="180" y="{height-30}" font-size="11" font-weight="500" fill="#64748b">• ON DELETE CASCADE: order_items (via orders), menus (via categories) • ON DELETE RESTRICT: order_items (via menus) • ON DELETE SET NULL: orders (table_id / user_id)</text>
    <text x="{width-55}" y="{height-30}" font-size="11" font-weight="600" fill="#0f172a" text-anchor="end">Smart Cafe POS v1.0 • Standar Akademik Skripsi</text>
    ''')

    svg.append('</svg>')
    return "\n".join(svg)

if __name__ == "__main__":
    out_dir = os.path.dirname(os.path.abspath(__file__))
    svg_path = os.path.join(out_dir, "erd_smart_cafe.svg")
    png_path = os.path.join(out_dir, "erd_smart_cafe.png")

    svg_content = generate_erd_svg()
    with open(svg_path, "w", encoding="utf-8") as f:
        f.write(svg_content)
    print(f"SVG written to {svg_path}")

    # Convert SVG to PNG using Google Chrome Headless for perfect typography & SVG marker rendering
    # or using ImageMagick
    res = subprocess.run([
        "google-chrome",
        "--headless",
        "--disable-gpu",
        "--window-size=1860,1260",
        "--screenshot=" + png_path,
        svg_path
    ], capture_output=True, text=True)

    if res.returncode == 0:
        print(f"PNG generated successfully via chrome: {png_path}")
    else:
        print("Chrome error, fallback to magick:", res.stderr)
        res2 = subprocess.run(["magick", "-density", "150", svg_path, png_path], capture_output=True, text=True)
        print(f"Magick status: {res2.returncode}")
