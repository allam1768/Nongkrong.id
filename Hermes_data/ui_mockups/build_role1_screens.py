#!/usr/bin/env python3
"""
Role 1 Screen Generator (Pembeli / Customer)
Screens:
1. 01_splash_screen.html
2. 02_portal_welcome.html
3. 03_table_checkin.html
4. 04_customer_catalog.html
5. 05_ai_barista_modal.html
6. 06_cart_checkout.html
7. 07_order_tracking.html
"""

import os
import subprocess

OUTPUT_DIR = "/home/puutraa/Desktop/nongkrong_id/ui_mockups"
os.makedirs(OUTPUT_DIR, exist_ok=True)

STAR_SVG = """<svg width="{w}" height="{h}" viewBox="0 0 100 95" fill="none" style="transform: rotate({deg}deg); position: absolute; {pos}; z-index: {z}; pointer-events: none;" xmlns="http://www.w3.org/2000/svg">
<path d="M49.9297 0L61.7167 35.9311H99.8602L69.0014 58.1378L80.7884 94.0689L49.9297 71.8622L19.071 94.0689L30.8579 58.1378L-0.000778198 35.9311H38.1427L49.9297 0Z" fill="{color}"/>
</svg>"""

MASCOT_SVG = """<svg width="{s}" height="{s}" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="M40 18 L36 8" stroke="{c}" stroke-width="4.5" stroke-linecap="round"/>
  <path d="M50 16 L50 6" stroke="{c}" stroke-width="4.5" stroke-linecap="round"/>
  <path d="M60 18 L64 8" stroke="{c}" stroke-width="4.5" stroke-linecap="round"/>
  <path d="M26 28 H74 V58 C74 71 63 80 50 80 C37 80 26 71 26 58 Z" fill="{bg}" stroke="{c}" stroke-width="4.5" stroke-linejoin="round"/>
  <path d="M74 36 C86 36 88 52 74 55" stroke="{c}" stroke-width="4.5" stroke-linecap="round" fill="none"/>
  <path d="M38 46 C40 43 44 43 46 46" stroke="{fc}" stroke-width="3.5" stroke-linecap="round" fill="none"/>
  <path d="M54 46 C56 43 60 43 62 46" stroke="{fc}" stroke-width="3.5" stroke-linecap="round" fill="none"/>
  <circle cx="35" cy="51" r="2.5" fill="{c}" opacity="0.35"/>
  <circle cx="65" cy="51" r="2.5" fill="{c}" opacity="0.35"/>
  <path d="M44 54 Q50 62 56 54" stroke="{fc}" stroke-width="3.5" stroke-linecap="round" fill="none"/>
</svg>"""

COMMON_HEAD = """
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; }
  body {
    background-color: #F1F5F9;
    font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
    color: #0F172A;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 30px 20px;
  }
  /* Phone Shell Container */
  .phone-frame {
    width: 430px;
    height: 932px;
    background: #FFFFFF;
    border-radius: 54px;
    border: 12px solid #0F172A;
    box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25);
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
  }
  /* Dynamic Island */
  .dynamic-island {
    position: absolute;
    top: 14px;
    left: 50%;
    transform: translateX(-50%);
    width: 124px;
    height: 35px;
    background: #0F172A;
    border-radius: 20px;
    z-index: 999;
  }
  /* Status Bar */
  .status-bar {
    height: 52px;
    padding: 14px 28px 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 14px;
    font-weight: 700;
    z-index: 998;
    position: relative;
  }
  .home-indicator {
    position: absolute;
    bottom: 8px;
    left: 50%;
    transform: translateX(-50%);
    width: 140px;
    height: 5px;
    background: #0F172A;
    border-radius: 4px;
    z-index: 999;
  }
</style>
"""

# ==========================================
# 1. SPLASH SCREEN (01_splash_screen.html)
# ==========================================
def build_screen_01():
    html = f"""<!DOCTYPE html>
<html>
<head>
<title>01 - Splash Screen</title>
{COMMON_HEAD}
<style>
  .splash-body {{
    background-color: #004AAD;
    color: #FFFFFF;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
    padding: 70px 24px 40px;
    position: relative;
    overflow: hidden;
  }}
</style>
</head>
<body>
  <div class="phone-frame">
    <div class="dynamic-island"></div>
    <div class="splash-body">
      {STAR_SVG.format(w=110, h=104, deg=-15, pos="top: -20px; left: -25px", z=1, color="#FFFFFF")}
      {STAR_SVG.format(w=140, h=133, deg=20, pos="top: 40px; right: -45px", z=1, color="#1D4ED8")}
      {STAR_SVG.format(w=90, h=85, deg=-35, pos="bottom: 120px; left: -25px", z=1, color="#1D4ED8")}
      {STAR_SVG.format(w=130, h=123, deg=15, pos="bottom: -20px; right: -30px", z=1, color="#FFFFFF")}

      <div style="width: 100%; display: flex; justify-content: flex-end; z-index: 10;">
        <span style="font-size: 11px; font-weight: 700; background: #FFFFFF; color: #004AAD; padding: 4px 12px; border-radius: 20px; text-transform: uppercase; letter-spacing: 1px;">Smart Cafe POS</span>
      </div>

      <div style="display: flex; flex-direction: column; align-items: center; text-align: center; z-index: 10; margin-top: -30px;">
        <!-- Brand Logo with Mascot on top of NG -->
        <div style="position: relative; display: inline-flex; align-items: flex-end; margin-bottom: 24px;">
          <div style="position: absolute; top: -48px; left: 142px;">
            {MASCOT_SVG.format(s=54, c="#FFFFFF", bg="#004AAD", fc="#FFFFFF")}
          </div>
          <span style="font-size: 38px; font-weight: 900; color: #FFFFFF; letter-spacing: -0.5px;">NONGKRONG</span>
          <span style="font-size: 38px; font-weight: 900; color: #FFFFFF;">.id</span>
        </div>
        
        <p style="font-size: 15px; font-weight: 500; color: #E0E7FF; max-width: 260px; line-height: 1.5;">
          Tempat Nongkrong Asik, Pesan Praktis Tanpa Antri.
        </p>
      </div>

      <div style="display: flex; flex-direction: column; align-items: center; gap: 14px; z-index: 10; width: 100%;">
        <div style="width: 48px; height: 48px; border-radius: 50%; border: 3px solid #FFFFFF; border-top-color: transparent; animation: spin 1s linear infinite;"></div>
        <span style="font-size: 12px; font-weight: 600; color: #CBD5E1; letter-spacing: 0.5px;">Memuat Pengalaman Kafe...</span>
        <span style="font-size: 10px; color: #93C5FD; font-family: 'JetBrains Mono', monospace;">v2.4.0 • MariaDB 3NF Active</span>
      </div>
    </div>
    <div class="home-indicator" style="background: #FFFFFF;"></div>
  </div>
</body>
</html>"""
    with open(os.path.join(OUTPUT_DIR, "01_splash_screen.html"), "w") as f:
        f.write(html)


# ==========================================
# 2. PORTAL WELCOME / LANDING (02_portal_welcome.html)
# ==========================================
def build_screen_02():
    html = f"""<!DOCTYPE html>
<html>
<head>
<title>02 - Portal Welcome</title>
{COMMON_HEAD}
<style>
  .portal-body {{
    background-color: #FFFFFF;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 55px 24px 30px;
    position: relative;
    overflow: hidden;
  }}
  .brand-header {{
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 10;
  }}
  .barista-container {{
    position: relative;
    width: 100%;
    height: 380px;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    margin-top: 10px;
    z-index: 5;
  }}
  .action-block {{
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    z-index: 10;
    margin-bottom: 10px;
  }}
  .btn-primary {{
    width: 100%;
    background-color: #004AAD;
    color: #FFFFFF;
    font-size: 16px;
    font-weight: 700;
    padding: 15px;
    border-radius: 30px;
    border: none;
    cursor: pointer;
    margin-bottom: 12px;
    box-shadow: 0 4px 14px rgba(0, 74, 173, 0.25);
  }}
  .btn-outline {{
    width: 100%;
    background-color: #FFFFFF;
    color: #0F172A;
    font-size: 16px;
    font-weight: 700;
    padding: 14px;
    border-radius: 30px;
    border: 2px solid #E2E8F0;
    cursor: pointer;
  }}
</style>
</head>
<body>
  <div class="phone-frame">
    <div class="dynamic-island"></div>
    <div class="status-bar" style="color: #0F172A;">
      <span>09:41</span>
      <div style="display: flex; gap: 6px; align-items: center;">
        <svg width="18" height="12" viewBox="0 0 18 12" fill="#0F172A"><path d="M1 8h2v3H1zM5 6h2v5H5zM9 4h2v7H9zM13 1h2v10h-2z"/></svg>
        <span style="font-size: 12px;">5G</span>
      </div>
    </div>

    <div class="portal-body">
      <!-- Header Brand -->
      <div class="brand-header">
        <div style="position: relative; display: inline-flex; align-items: flex-end; margin-top: 4px;">
          <div style="position: absolute; top: -34px; left: 102px;">
            {MASCOT_SVG.format(s=40, c="#004AAD", bg="#FFFFFF", fc="#004AAD")}
          </div>
          <span style="font-size: 28px; font-weight: 900; color: #004AAD; letter-spacing: -0.5px;">NONGKRONG</span>
          <span style="font-size: 28px; font-weight: 900; color: #004AAD;">.id</span>
        </div>
      </div>

      <!-- Barista Duo Center Vector/Image -->
      <div class="barista-container">
        <img src="file:///home/puutraa/Desktop/nongkrong_id/public/assets/img/baristas-portal.png" 
             style="max-width: 105%; max-height: 100%; object-fit: contain; object-position: bottom; pointer-events: none;" alt="Baristas">
      </div>

      <!-- Action & Typography Section -->
      <div class="action-block">
        <h1 style="font-size: 26px; font-weight: 800; color: #000000; line-height: 1.25; margin-bottom: 8px;">
          Fall in Love with<br>
          <span style="color: #000000;">Coffee in </span>
          <span style="color: #004AAD;">NONGKRONG.id</span>
        </h1>
        <p style="font-size: 12px; color: #64748B; line-height: 1.5; margin-bottom: 24px; max-width: 290px;">
          Welcome to our cozy coffee corner, where every cup is a delightful for you.
        </p>

        <button class="btn-primary">Login Karyawan</button>
        <button class="btn-outline">Pesan Cepat (Meja)</button>
      </div>
    </div>
    <div class="home-indicator"></div>
  </div>
</body>
</html>"""
    with open(os.path.join(OUTPUT_DIR, "02_portal_welcome.html"), "w") as f:
        f.write(html)


# ==========================================
# 3. TABLE CHECK-IN (03_table_checkin.html)
# ==========================================
def build_screen_03():
    html = f"""<!DOCTYPE html>
<html>
<head>
<title>03 - Table Check-in</title>
{COMMON_HEAD}
<style>
  .checkin-body {{
    background-color: #FFFFFF;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 55px 22px 25px;
    position: relative;
    overflow: hidden;
  }}
  .qr-box {{
    background: #FFFFFF;
    border: 2px solid #004AAD;
    border-radius: 24px;
    padding: 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0, 74, 173, 0.08);
  }}
  .divider-or {{
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 14px 0;
    position: relative;
  }}
  .divider-or::before, .divider-or::after {{
    content: "";
    flex: 1;
    height: 1.5px;
    background: #E2E8F0;
  }}
  .divider-or span {{
    padding: 0 14px;
    font-size: 14px;
    font-weight: 800;
    color: #0F172A;
  }}
  .code-inputs {{
    display: flex;
    justify-content: space-between;
    gap: 8px;
    margin: 14px 0 18px;
  }}
  .code-box {{
    width: 58px;
    height: 64px;
    border: 2px solid #004AAD;
    border-radius: 14px;
    background: #FFFFFF;
    font-size: 26px;
    font-weight: 800;
    color: #004AAD;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'JetBrains Mono', monospace;
  }}
  .btn-confirm {{
    width: 100%;
    background-color: #004AAD;
    color: #FFFFFF;
    font-size: 16px;
    font-weight: 700;
    padding: 15px;
    border-radius: 30px;
    border: none;
    cursor: pointer;
    box-shadow: 0 6px 16px rgba(0, 74, 173, 0.28);
  }}
</style>
</head>
<body>
  <div class="phone-frame">
    <div class="dynamic-island"></div>
    <div class="status-bar">
      <span>09:41</span>
      <div style="display: flex; gap: 6px; align-items: center;">
        <svg width="18" height="12" viewBox="0 0 18 12" fill="#0F172A"><path d="M1 8h2v3H1zM5 6h2v5H5zM9 4h2v7H9zM13 1h2v10h-2z"/></svg>
      </div>
    </div>

    <div class="checkin-body">
      {STAR_SVG.format(w=80, h=76, deg=-15, pos="top: 10px; left: -25px", z=1, color="#004AAD")}
      {STAR_SVG.format(w=95, h=90, deg=20, pos="top: 25px; right: -30px", z=1, color="#004AAD")}
      {STAR_SVG.format(w=75, h=71, deg=-30, pos="bottom: 120px; left: -25px", z=1, color="#004AAD")}
      {STAR_SVG.format(w=85, h=80, deg=15, pos="bottom: -15px; right: -25px", z=1, color="#004AAD")}

      <!-- Brand Logo -->
      <div style="display: flex; justify-content: center; position: relative; z-index: 10; margin-top: 5px;">
        <div style="position: relative; display: inline-flex; align-items: flex-end;">
          <div style="position: absolute; top: -34px; left: 98px;">
            {MASCOT_SVG.format(s=38, c="#004AAD", bg="#FFFFFF", fc="#004AAD")}
          </div>
          <span style="font-size: 26px; font-weight: 900; color: #004AAD; letter-spacing: -0.5px;">NONGKRONG</span>
          <span style="font-size: 26px; font-weight: 900; color: #004AAD;">.id</span>
        </div>
      </div>

      <!-- Main Options Container -->
      <div style="position: relative; z-index: 10; margin: auto 0;">
        <!-- Option 1: Scan QR Code -->
        <div class="qr-box">
          <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#004AAD" stroke-width="2.2"><path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/></svg>
            <span style="font-size: 14px; font-weight: 700; color: #004AAD;">Arahkan Kamera ke QR Meja</span>
          </div>

          <!-- Stylized QR Code SVG -->
          <div style="background: #F8FAFC; padding: 14px; border-radius: 16px; border: 1.5px dashed #CBD5E1;">
            <svg width="150" height="150" viewBox="0 0 100 100" fill="#0F172A">
              <!-- Top Left Corner Box -->
              <rect x="5" y="5" width="30" height="30" rx="4" fill="none" stroke="#004AAD" stroke-width="6"/>
              <rect x="13" y="13" width="14" height="14" fill="#004AAD"/>
              <!-- Top Right Corner Box -->
              <rect x="65" y="5" width="30" height="30" rx="4" fill="none" stroke="#004AAD" stroke-width="6"/>
              <rect x="73" y="13" width="14" height="14" fill="#004AAD"/>
              <!-- Bottom Left Corner Box -->
              <rect x="5" y="65" width="30" height="30" rx="4" fill="none" stroke="#004AAD" stroke-width="6"/>
              <rect x="13" y="73" width="14" height="14" fill="#004AAD"/>
              <!-- Random Data Dots -->
              <rect x="42" y="8" width="6" height="6" fill="#0F172A"/>
              <rect x="52" y="16" width="6" height="12" fill="#0F172A"/>
              <rect x="42" y="24" width="8" height="6" fill="#0F172A"/>
              <rect x="12" y="44" width="14" height="6" fill="#0F172A"/>
              <rect x="32" y="40" width="8" height="8" fill="#004AAD"/>
              <rect x="46" y="46" width="10" height="10" fill="#0F172A"/>
              <rect x="62" y="42" width="12" height="6" fill="#0F172A"/>
              <rect x="80" y="44" width="8" height="12" fill="#0F172A"/>
              <rect x="42" y="66" width="8" height="8" fill="#0F172A"/>
              <rect x="56" y="62" width="6" height="14" fill="#0F172A"/>
              <rect x="70" y="72" width="14" height="6" fill="#004AAD"/>
              <rect x="44" y="82" width="12" height="8" fill="#0F172A"/>
              <rect x="68" y="84" width="8" height="8" fill="#0F172A"/>
              <rect x="84" y="80" width="8" height="12" fill="#0F172A"/>
            </svg>
          </div>
          <span style="font-size: 11px; color: #64748B; margin-top: 10px; font-weight: 500;">Otomatis redirect ke katalog meja Anda</span>
        </div>

        <!-- Divider OR -->
        <div class="divider-or">
          <span>OR</span>
        </div>

        <!-- Option 2: Enter Table Code -->
        <div style="background: #F8FAFC; border: 2px solid #E2E8F0; border-radius: 24px; padding: 20px 18px; text-align: center;">
          <h2 style="font-size: 18px; font-weight: 800; color: #000000; margin-bottom: 4px;">
            Enter your table code !
          </h2>
          <p style="font-size: 12px; color: #64748B; margin-bottom: 12px;">
            Ketik 5 digit kode yang tertera pada stiker meja Anda
          </p>

          <!-- 5 Input Boxes -->
          <div class="code-inputs">
            <div class="code-box">7</div>
            <div class="code-box">K</div>
            <div class="code-box">9</div>
            <div class="code-box">2</div>
            <div class="code-box" style="border-color: #004AAD; background: #EFF6FF;">A</div>
          </div>

          <button class="btn-confirm">Confirm Meja</button>
        </div>
      </div>

      <div style="text-align: center; font-size: 11px; color: #94A3B8; z-index: 10;">
        Butuh bantuan? Silakan panggil waiter atau kasir kami
      </div>
    </div>
    <div class="home-indicator"></div>
  </div>
</body>
</html>"""
    with open(os.path.join(OUTPUT_DIR, "03_table_checkin.html"), "w") as f:
        f.write(html)


# ==========================================
# 4. DIGITAL MENU CATALOG & AI BANNER (04_customer_catalog.html)
# ==========================================
def build_screen_04():
    html = f"""<!DOCTYPE html>
<html>
<head>
<title>04 - Menu Catalog & AI Banner</title>
{COMMON_HEAD}
<style>
  .catalog-body {{
    background-color: #F8FAFC;
    height: 100%;
    display: flex;
    flex-direction: column;
    padding: 52px 18px 20px;
    overflow-y: auto;
    position: relative;
  }}
  .app-header {{
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
  }}
  .table-badge {{
    background: #004AAD;
    color: #FFFFFF;
    font-size: 12px;
    font-weight: 800;
    padding: 6px 14px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    gap: 6px;
  }}
  .search-bar {{
    background: #FFFFFF;
    border: 2px solid #E2E8F0;
    border-radius: 16px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 14px;
  }}
  .ai-banner {{
    background: #004AAD;
    border-radius: 22px;
    padding: 18px 20px;
    color: #FFFFFF;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    overflow: hidden;
    margin-bottom: 18px;
    border: 2px solid #003080;
  }}
  .category-scroll {{
    display: flex;
    gap: 8px;
    overflow-x: auto;
    padding-bottom: 4px;
    margin-bottom: 16px;
  }}
  .cat-pill {{
    white-space: nowrap;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 700;
    border: 1.5px solid #E2E8F0;
    background: #FFFFFF;
    color: #64748B;
    cursor: pointer;
  }}
  .cat-pill.active {{
    background: #004AAD;
    color: #FFFFFF;
    border-color: #004AAD;
  }}
  .menu-grid {{
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    padding-bottom: 80px;
  }}
  .menu-card {{
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 18px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
  }}
  .menu-img {{
    height: 110px;
    background: #EEF2F6;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
  }}
  .flavor-tag {{
    font-size: 9px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 6px;
    background: #EFF6FF;
    color: #004AAD;
    border: 1px solid #BFDBFE;
  }}
  .bottom-nav {{
    position: absolute;
    bottom: 20px;
    left: 18px;
    right: 18px;
    background: #0F172A;
    border-radius: 30px;
    padding: 12px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #FFFFFF;
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.35);
    z-index: 50;
  }}
</style>
</head>
<body>
  <div class="phone-frame">
    <div class="dynamic-island"></div>
    <div class="catalog-body">
      <!-- Top App Bar -->
      <div class="app-header">
        <div style="display: flex; align-items: center; gap: 8px;">
          {MASCOT_SVG.format(s=32, c="#004AAD", bg="#FFFFFF", fc="#004AAD")}
          <span style="font-size: 18px; font-weight: 900; color: #004AAD;">NONGKRONG.id</span>
        </div>
        <div class="table-badge">
          <span style="width: 8px; height: 8px; border-radius: 50%; background: #10B981;"></span>
          Meja 03
        </div>
      </div>

      <!-- Search Bar -->
      <div class="search-bar">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <span style="font-size: 13px; color: #94A3B8; font-weight: 500;">Cari kopi, pastry, makanan...</span>
      </div>

      <!-- AI Barista Banner (Step 4 & Branch 1 Flowchart) -->
      <div class="ai-banner">
        {STAR_SVG.format(w=85, h=80, deg=25, pos="top: -20px; right: -20px", z=1, color="#1D4ED8")}
        <div style="position: relative; z-index: 5; max-width: 230px;">
          <div style="display: inline-flex; align-items: center; gap: 6px; background: #FFFFFF; color: #004AAD; font-size: 10px; font-weight: 800; padding: 3px 8px; border-radius: 12px; margin-bottom: 6px; text-transform: uppercase;">
            <span>✨ Gemini 1.5 Flash</span>
          </div>
          <h3 style="font-size: 16px; font-weight: 800; line-height: 1.25; margin-bottom: 4px;">
            Bingung Mau Minum Apa?
          </h3>
          <p style="font-size: 11px; color: #E0E7FF; margin-bottom: 10px;">
            Ceritakan mood & rasa favoritmu, Barista AI racikkan rekomendasinya!
          </p>
          <button style="background: #FFFFFF; color: #004AAD; font-size: 12px; font-weight: 800; padding: 8px 16px; border-radius: 20px; border: none; cursor: pointer;">
            Tanya AI Barista ➔
          </button>
        </div>
        <div style="position: relative; z-index: 5;">
          {MASCOT_SVG.format(s=60, c="#FFFFFF", bg="#004AAD", fc="#FFFFFF")}
        </div>
      </div>

      <!-- Categories Pills -->
      <div class="category-scroll">
        <div class="cat-pill active">Semua (15)</div>
        <div class="cat-pill">Espresso Based</div>
        <div class="cat-pill">Signature Coffee</div>
        <div class="cat-pill">Non-Coffee</div>
        <div class="cat-pill">Pastry & Toast</div>
      </div>

      <!-- Menu Grid -->
      <div class="menu-grid">
        <!-- Item 1 -->
        <div class="menu-card">
          <div class="menu-img">
            <svg width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="#004AAD" stroke-width="1.8"><path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><path d="M6 1v3M10 1v3M14 1v3"/></svg>
            <div style="position: absolute; top: 8px; left: 8px; display: flex; gap: 4px;">
              <span class="flavor-tag">Sweet</span>
              <span class="flavor-tag">Creamy</span>
            </div>
          </div>
          <div style="padding: 10px; display: flex; flex-direction: column; justify-content: space-between; flex: 1;">
            <div>
              <h4 style="font-size: 13px; font-weight: 800; color: #0F172A; margin-bottom: 2px;">Kopi Susu Aren</h4>
              <p style="font-size: 10px; color: #64748B; margin-bottom: 6px;">Espresso, susu segar, gula aren organik</p>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <span style="font-size: 13px; font-weight: 800; color: #004AAD;">Rp 22.000</span>
              <button style="background: #004AAD; color: #FFFFFF; border: none; width: 26px; height: 26px; border-radius: 8px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center;">+</button>
            </div>
          </div>
        </div>

        <!-- Item 2 -->
        <div class="menu-card">
          <div class="menu-img">
            <svg width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="#004AAD" stroke-width="1.8"><path d="M17 8h1a4 4 0 1 1 0 8h-1M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V8z"/></svg>
            <div style="position: absolute; top: 8px; left: 8px; display: flex; gap: 4px;">
              <span class="flavor-tag">Caramel</span>
              <span class="flavor-tag">Nutty</span>
            </div>
          </div>
          <div style="padding: 10px; display: flex; flex-direction: column; justify-content: space-between; flex: 1;">
            <div>
              <h4 style="font-size: 13px; font-weight: 800; color: #0F172A; margin-bottom: 2px;">Caramel Macchiato</h4>
              <p style="font-size: 10px; color: #64748B; margin-bottom: 6px;">Vanilla syrup, steamed milk, caramel drizzle</p>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <span style="font-size: 13px; font-weight: 800; color: #004AAD;">Rp 28.000</span>
              <button style="background: #004AAD; color: #FFFFFF; border: none; width: 26px; height: 26px; border-radius: 8px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center;">+</button>
            </div>
          </div>
        </div>

        <!-- Item 3 -->
        <div class="menu-card">
          <div class="menu-img">
            <svg width="46" height="46" viewBox="0 0 24 24" fill="none" stroke="#004AAD" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="4"/><path d="M7 12h10M7 8h10M7 16h6"/></svg>
            <div style="position: absolute; top: 8px; left: 8px; display: flex; gap: 4px;">
              <span class="flavor-tag">Bitter</span>
              <span class="flavor-tag">Bold</span>
            </div>
          </div>
          <div style="padding: 10px; display: flex; flex-direction: column; justify-content: space-between; flex: 1;">
            <div>
              <h4 style="font-size: 13px; font-weight: 800; color: #0F172A; margin-bottom: 2px;">Americano Cold Brew</h4>
              <p style="font-size: 10px; color: #64748B; margin-bottom: 6px;">Ekstraksi dingin 12 jam, rasa clean</p>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <span style="font-size: 13px; font-weight: 800; color: #004AAD;">Rp 20.000</span>
              <button style="background: #004AAD; color: #FFFFFF; border: none; width: 26px; height: 26px; border-radius: 8px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center;">+</button>
            </div>
          </div>
        </div>

        <!-- Item 4 -->
        <div class="menu-card">
          <div class="menu-img">
            <svg width="46" height="46" viewBox="0 0 24 24" fill="none" stroke="#004AAD" stroke-width="1.8"><path d="M12 2a10 10 0 0 0-7.07 17.07l14.14-14.14A9.96 9.96 0 0 0 12 2z"/></svg>
            <div style="position: absolute; top: 8px; left: 8px; display: flex; gap: 4px;">
              <span class="flavor-tag">Flaky</span>
              <span class="flavor-tag">Butter</span>
            </div>
          </div>
          <div style="padding: 10px; display: flex; flex-direction: column; justify-content: space-between; flex: 1;">
            <div>
              <h4 style="font-size: 13px; font-weight: 800; color: #0F172A; margin-bottom: 2px;">Butter Croissant</h4>
              <p style="font-size: 10px; color: #64748B; margin-bottom: 6px;">Pastry renyah dengan butter Prancis</p>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <span style="font-size: 13px; font-weight: 800; color: #004AAD;">Rp 18.000</span>
              <button style="background: #004AAD; color: #FFFFFF; border: none; width: 26px; height: 26px; border-radius: 8px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center;">+</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Bottom Floating Cart Bar -->
      <div class="bottom-nav">
        <div style="display: flex; align-items: center; gap: 10px;">
          <div style="position: relative;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2.2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            <span style="position: absolute; -top: 6px; -right: 8px; background: #004AAD; color: #FFFFFF; font-size: 10px; font-weight: 800; width: 16px; height: 16px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">3</span>
          </div>
          <div>
            <div style="font-size: 11px; color: #94A3B8;">Subtotal Pesanan</div>
            <div style="font-size: 15px; font-weight: 800; color: #FFFFFF;">Rp 68.000</div>
          </div>
        </div>
        <button style="background: #004AAD; color: #FFFFFF; font-size: 13px; font-weight: 800; padding: 10px 18px; border-radius: 20px; border: none; cursor: pointer;">
          Checkout ➔
        </button>
      </div>
    </div>
    <div class="home-indicator"></div>
  </div>
</body>
</html>"""
    with open(os.path.join(OUTPUT_DIR, "04_customer_catalog.html"), "w") as f:
        f.write(html)


# ==========================================
# 5. AI BARISTA CONSULTATION MODAL (05_ai_barista_modal.html)
# ==========================================
def build_screen_05():
    html = f"""<!DOCTYPE html>
<html>
<head>
<title>05 - AI Barista Consultation</title>
{COMMON_HEAD}
<style>
  .modal-backdrop {{
    background: rgba(15, 23, 42, 0.6);
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    position: relative;
    overflow: hidden;
  }}
  .sheet-container {{
    background: #FFFFFF;
    border-top-left-radius: 36px;
    border-top-right-radius: 36px;
    height: 85%;
    display: flex;
    flex-direction: column;
    padding: 24px 20px 24px;
    position: relative;
    border: 2px solid #E2E8F0;
    border-bottom: none;
  }}
  .chat-bubble-ai {{
    background: #EFF6FF;
    border: 1.5px solid #BFDBFE;
    border-radius: 20px;
    border-top-left-radius: 4px;
    padding: 14px 16px;
    margin-bottom: 12px;
    font-size: 13px;
    color: #1E293B;
    line-height: 1.5;
  }}
  .chat-bubble-user {{
    background: #004AAD;
    color: #FFFFFF;
    border-radius: 20px;
    border-top-right-radius: 4px;
    padding: 14px 16px;
    margin-bottom: 16px;
    font-size: 13px;
    align-self: flex-end;
    max-width: 85%;
    line-height: 1.5;
  }}
  .recom-card {{
    background: #FFFFFF;
    border: 2px solid #004AAD;
    border-radius: 18px;
    padding: 14px;
    margin-bottom: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 12px rgba(0, 74, 173, 0.08);
  }}
</style>
</head>
<body>
  <div class="phone-frame">
    <div class="dynamic-island"></div>
    <div class="modal-backdrop">
      <div class="sheet-container">
        <!-- Sheet Handle -->
        <div style="width: 44px; height: 5px; background: #CBD5E1; border-radius: 4px; align-self: center; margin-bottom: 16px;"></div>

        <!-- AI Barista Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1.5px solid #E2E8F0;">
          <div style="display: flex; align-items: center; gap: 10px;">
            <div style="background: #004AAD; width: 44px; height: 44px; border-radius: 14px; display: flex; align-items: center; justify-content: center;">
              {MASCOT_SVG.format(s=30, c="#FFFFFF", bg="#004AAD", fc="#FFFFFF")}
            </div>
            <div>
              <div style="display: flex; align-items: center; gap: 6px;">
                <h3 style="font-size: 16px; font-weight: 800; color: #0F172A;">AI Barista Virtual</h3>
                <span style="background: #10B981; width: 8px; height: 8px; border-radius: 50%;"></span>
              </div>
              <p style="font-size: 11px; color: #64748B;">Powered by Gemini API • Smart Match</p>
            </div>
          </div>
          <button style="background: #F1F5F9; border: none; width: 32px; height: 32px; border-radius: 50%; font-weight: 800; color: #64748B; cursor: pointer;">✕</button>
        </div>

        <!-- Conversation Scroll -->
        <div style="flex: 1; overflow-y: auto; padding-right: 4px;">
          <!-- AI Prompt Question -->
          <div class="chat-bubble-ai">
            <span style="font-weight: 700; color: #004AAD; display: block; margin-bottom: 2px;">Barista Mas Nongki:</span>
            Hai Allam! Lagi pengen yang manis segar buat nemenin santai siang, atau butuh kopi bold pekat buat ngerjain tugas di Meja 03?
          </div>

          <!-- Quick Preference Chips -->
          <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 14px;">
            <span style="background: #F1F5F9; border: 1.5px solid #CBD5E1; color: #004AAD; font-size: 11px; font-weight: 700; padding: 6px 12px; border-radius: 16px;">Manis & Dingin</span>
            <span style="background: #F1F5F9; border: 1.5px solid #CBD5E1; color: #004AAD; font-size: 11px; font-weight: 700; padding: 6px 12px; border-radius: 16px;">Creamy Hazelnut</span>
            <span style="background: #F1F5F9; border: 1.5px solid #CBD5E1; color: #004AAD; font-size: 11px; font-weight: 700; padding: 6px 12px; border-radius: 16px;">Bold & Strong</span>
          </div>

          <!-- User Preference Message -->
          <div class="chat-bubble-user">
            Lagi butuh kopi dingin yang creamy, nggak terlalu pait, ada aroma caramel gurih dan manisnya pas.
          </div>

          <!-- AI Gemini Structured Recommendation Response -->
          <div class="chat-bubble-ai" style="background: #FFFFFF; border-color: #004AAD;">
            <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
              <span style="font-size: 13px;">🎯</span>
              <span style="font-weight: 800; color: #004AAD;">Rekomendasi Terbaik Buat Kamu:</span>
            </div>
            <p style="font-size: 12px; color: #475569; margin-bottom: 10px;">
              Berdasarkan taste profile database, ini 2 racikan paling pas dengan selera manismu:
            </p>

            <!-- Card 1 -->
            <div class="recom-card">
              <div>
                <h4 style="font-size: 13px; font-weight: 800; color: #0F172A;">Caramel Macchiato Iced</h4>
                <div style="display: flex; gap: 4px; margin: 3px 0;">
                  <span style="font-size: 9px; font-weight: 700; background: #EFF6FF; color: #004AAD; padding: 2px 6px; border-radius: 6px;">98% Match</span>
                  <span style="font-size: 9px; font-weight: 700; background: #FEF3C7; color: #B45309; padding: 2px 6px; border-radius: 6px;">Sweet & Creamy</span>
                </div>
                <div style="font-size: 12px; font-weight: 800; color: #004AAD;">Rp 28.000</div>
              </div>
              <button style="background: #004AAD; color: #FFFFFF; border: none; font-size: 12px; font-weight: 800; padding: 8px 14px; border-radius: 14px; cursor: pointer;">
                + Tambah
              </button>
            </div>

            <!-- Card 2 -->
            <div class="recom-card">
              <div>
                <h4 style="font-size: 13px; font-weight: 800; color: #0F172A;">Kopi Susu Aren Gula Jawa</h4>
                <div style="display: flex; gap: 4px; margin: 3px 0;">
                  <span style="font-size: 9px; font-weight: 700; background: #EFF6FF; color: #004AAD; padding: 2px 6px; border-radius: 6px;">94% Match</span>
                  <span style="font-size: 9px; font-weight: 700; background: #FEF3C7; color: #B45309; padding: 2px 6px; border-radius: 6px;">Classic Creamy</span>
                </div>
                <div style="font-size: 12px; font-weight: 800; color: #004AAD;">Rp 22.000</div>
              </div>
              <button style="background: #004AAD; color: #FFFFFF; border: none; font-size: 12px; font-weight: 800; padding: 8px 14px; border-radius: 14px; cursor: pointer;">
                + Tambah
              </button>
            </div>
          </div>
        </div>

        <!-- Chat Input Field -->
        <div style="display: flex; gap: 8px; align-items: center; padding-top: 12px;">
          <input type="text" placeholder="Ketik preferensi rasa lain..." style="flex: 1; border: 2px solid #E2E8F0; border-radius: 24px; padding: 12px 18px; font-size: 13px; outline: none;">
          <button style="background: #004AAD; color: #FFFFFF; border: none; width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2.5"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
          </button>
        </div>
      </div>
    </div>
    <div class="home-indicator"></div>
  </div>
</body>
</html>"""
    with open(os.path.join(OUTPUT_DIR, "05_ai_barista_modal.html"), "w") as f:
        f.write(html)


# ==========================================
# 6. CART REVIEW & CHECKOUT (06_cart_checkout.html)
# ==========================================
def build_screen_06():
    html = f"""<!DOCTYPE html>
<html>
<head>
<title>06 - Review Keranjang & Checkout</title>
{COMMON_HEAD}
<style>
  .cart-body {{
    background-color: #F8FAFC;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 55px 18px 24px;
    overflow-y: auto;
  }}
  .cart-card {{
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 18px;
    padding: 14px;
    margin-bottom: 10px;
    display: flex;
    flex-direction: column;
    gap: 8px;
  }}
  .payment-option {{
    background: #FFFFFF;
    border: 2px solid #E2E8F0;
    border-radius: 16px;
    padding: 12px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
    cursor: pointer;
  }}
  .payment-option.selected {{
    border-color: #004AAD;
    background: #EFF6FF;
  }}
  .btn-pay {{
    background: #004AAD;
    color: #FFFFFF;
    font-size: 16px;
    font-weight: 800;
    padding: 16px;
    border-radius: 30px;
    border: none;
    width: 100%;
    cursor: pointer;
    box-shadow: 0 6px 18px rgba(0, 74, 173, 0.28);
  }}
</style>
</head>
<body>
  <div class="phone-frame">
    <div class="dynamic-island"></div>
    <div class="cart-body">
      <!-- Header -->
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <div style="display: flex; align-items: center; gap: 8px;">
          <button style="background: #FFFFFF; border: 1.5px solid #E2E8F0; width: 36px; height: 36px; border-radius: 50%; font-weight: 800; cursor: pointer;">←</button>
          <div>
            <h2 style="font-size: 18px; font-weight: 800; color: #0F172A;">Rincian Pesanan</h2>
            <p style="font-size: 11px; color: #64748B;">Meja 03 • 3 Item Terpilih</p>
          </div>
        </div>
        <span style="background: #E0E7FF; color: #004AAD; font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 12px;">Self-Order</span>
      </div>

      <!-- Item List -->
      <div style="flex: 1; overflow-y: auto;">
        <!-- Item 1 -->
        <div class="cart-card">
          <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
              <h4 style="font-size: 14px; font-weight: 800; color: #0F172A;">Caramel Macchiato Iced</h4>
              <span style="font-size: 12px; font-weight: 700; color: #004AAD;">Rp 28.000</span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
              <button style="background: #F1F5F9; border: none; width: 26px; height: 26px; border-radius: 8px; font-weight: 800;">-</button>
              <span style="font-size: 13px; font-weight: 800;">1</span>
              <button style="background: #004AAD; color: #FFFFFF; border: none; width: 26px; height: 26px; border-radius: 8px; font-weight: 800;">+</button>
            </div>
          </div>
          <!-- Note -->
          <div style="background: #F8FAFC; border: 1px dashed #CBD5E1; border-radius: 10px; padding: 6px 10px; font-size: 11px; color: #64748B;">
            📝 Catatan: <span style="color: #0F172A; font-weight: 600;">Less sugar (50%), es sedang</span>
          </div>
        </div>

        <!-- Item 2 -->
        <div class="cart-card">
          <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
              <h4 style="font-size: 14px; font-weight: 800; color: #0F172A;">Kopi Susu Aren</h4>
              <span style="font-size: 12px; font-weight: 700; color: #004AAD;">Rp 22.000</span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
              <button style="background: #F1F5F9; border: none; width: 26px; height: 26px; border-radius: 8px; font-weight: 800;">-</button>
              <span style="font-size: 13px; font-weight: 800;">1</span>
              <button style="background: #004AAD; color: #FFFFFF; border: none; width: 26px; height: 26px; border-radius: 8px; font-weight: 800;">+</button>
            </div>
          </div>
          <div style="background: #F8FAFC; border: 1px dashed #CBD5E1; border-radius: 10px; padding: 6px 10px; font-size: 11px; color: #64748B;">
            📝 Catatan: <span style="color: #0F172A; font-weight: 600;">Normal sugar & es</span>
          </div>
        </div>

        <!-- Item 3 -->
        <div class="cart-card">
          <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
              <h4 style="font-size: 14px; font-weight: 800; color: #0F172A;">Butter Croissant</h4>
              <span style="font-size: 12px; font-weight: 700; color: #004AAD;">Rp 18.000</span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
              <button style="background: #F1F5F9; border: none; width: 26px; height: 26px; border-radius: 8px; font-weight: 800;">-</button>
              <span style="font-size: 13px; font-weight: 800;">1</span>
              <button style="background: #004AAD; color: #FFFFFF; border: none; width: 26px; height: 26px; border-radius: 8px; font-weight: 800;">+</button>
            </div>
          </div>
          <div style="background: #F8FAFC; border: 1px dashed #CBD5E1; border-radius: 10px; padding: 6px 10px; font-size: 11px; color: #64748B;">
            📝 Catatan: <span style="color: #0F172A; font-weight: 600;">Hangatkan sebelum disajikan</span>
          </div>
        </div>

        <!-- Payment Method Selection (Branch Flowchart: QRIS vs Tunai Kasir) -->
        <div style="margin: 16px 0 10px;">
          <h4 style="font-size: 13px; font-weight: 800; color: #0F172A; margin-bottom: 8px;">Metode Pembayaran</h4>
          <div class="payment-option selected">
            <div style="display: flex; align-items: center; gap: 10px;">
              <span style="background: #004AAD; color: #FFFFFF; font-size: 11px; font-weight: 900; padding: 4px 8px; border-radius: 6px;">QRIS</span>
              <div>
                <div style="font-size: 13px; font-weight: 800; color: #0F172A;">QRIS Digital (Instan)</div>
                <div style="font-size: 10px; color: #64748B;">Gopay, OVO, Dana, BCA, Mandiri</div>
              </div>
            </div>
            <div style="width: 18px; height: 18px; border-radius: 50%; border: 5px solid #004AAD; background: #FFFFFF;"></div>
          </div>

          <div class="payment-option">
            <div style="display: flex; align-items: center; gap: 10px;">
              <span style="background: #64748B; color: #FFFFFF; font-size: 11px; font-weight: 900; padding: 4px 8px; border-radius: 6px;">KASIR</span>
              <div>
                <div style="font-size: 13px; font-weight: 800; color: #0F172A;">Bayar Tunai di Kasir</div>
                <div style="font-size: 10px; color: #64748B;">Konfirmasi pembayaran ke staf kasir</div>
              </div>
            </div>
            <div style="width: 18px; height: 18px; border-radius: 50%; border: 2px solid #CBD5E1;"></div>
          </div>
        </div>

        <!-- Cost Breakdown -->
        <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 14px; margin-bottom: 14px;">
          <div style="display: flex; justify-content: space-between; font-size: 12px; color: #64748B; margin-bottom: 6px;">
            <span>Subtotal (3 item)</span>
            <span>Rp 68.000</span>
          </div>
          <div style="display: flex; justify-content: space-between; font-size: 12px; color: #64748B; margin-bottom: 8px;">
            <span>Pajak Restoran (PB1 10%)</span>
            <span>Rp 6.800</span>
          </div>
          <div style="border-top: 1.5px dashed #E2E8F0; padding-top: 8px; display: flex; justify-content: space-between; font-size: 15px; font-weight: 800; color: #004AAD;">
            <span>Total Pembayaran</span>
            <span>Rp 74.800</span>
          </div>
        </div>
      </div>

      <!-- Action Button -->
      <button class="btn-pay">Kirim & Bayar Pesanan (Rp 74.800)</button>
    </div>
    <div class="home-indicator"></div>
  </div>
</body>
</html>"""
    with open(os.path.join(OUTPUT_DIR, "06_cart_checkout.html"), "w") as f:
        f.write(html)


# ==========================================
# 7. LIVE ORDER TRACKING (07_order_tracking.html)
# ==========================================
def build_screen_07():
    html = f"""<!DOCTYPE html>
<html>
<head>
<title>07 - Live Order Tracking</title>
{COMMON_HEAD}
<style>
  .track-body {{
    background-color: #F8FAFC;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 55px 18px 24px;
    overflow-y: auto;
  }}
  .step-node {{
    display: flex;
    align-items: flex-start;
    gap: 14px;
    position: relative;
    padding-bottom: 22px;
  }}
  .step-node:not(:last-child)::before {{
    content: "";
    position: absolute;
    left: 17px;
    top: 36px;
    bottom: 0;
    width: 2.5px;
    background: #CBD5E1;
  }}
  .step-node.completed::before {{
    background: #004AAD;
  }}
  .icon-circle {{
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 800;
    z-index: 5;
  }}
  .icon-circle.done {{
    background: #004AAD;
    color: #FFFFFF;
  }}
  .icon-circle.active {{
    background: #FFFFFF;
    color: #004AAD;
    border: 3px solid #004AAD;
    animation: pulse 1.5s infinite;
  }}
  .icon-circle.pending {{
    background: #F1F5F9;
    color: #94A3B8;
    border: 2px solid #CBD5E1;
  }}
</style>
</head>
<body>
  <div class="phone-frame">
    <div class="dynamic-island"></div>
    <div class="track-body">
      <!-- Header -->
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
        <div style="display: flex; align-items: center; gap: 8px;">
          {MASCOT_SVG.format(s=34, c="#004AAD", bg="#FFFFFF", fc="#004AAD")}
          <div>
            <h2 style="font-size: 17px; font-weight: 800; color: #0F172A;">Status Pesanan Anda</h2>
            <div style="display: flex; align-items: center; gap: 5px;">
              <span style="width: 7px; height: 7px; border-radius: 50%; background: #10B981; animation: blink 1s infinite;"></span>
              <span style="font-size: 11px; color: #004AAD; font-weight: 700; font-family: 'JetBrains Mono', monospace;">wire:poll.3s Realtime</span>
            </div>
          </div>
        </div>
        <div style="background: #004AAD; color: #FFFFFF; font-size: 12px; font-weight: 800; padding: 6px 12px; border-radius: 16px;">
          Meja 03
        </div>
      </div>

      <!-- Order Ticket Banner -->
      <div style="background: #FFFFFF; border: 2px solid #004AAD; border-radius: 20px; padding: 18px; margin-bottom: 20px; box-shadow: 0 4px 14px rgba(0, 74, 173, 0.08);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1.5px dashed #E2E8F0;">
          <div>
            <span style="font-size: 11px; color: #64748B; font-weight: 600;">Nomor Pesanan</span>
            <div style="font-size: 16px; font-weight: 900; color: #004AAD; font-family: 'JetBrains Mono', monospace;">#ORD-20261008-002</div>
          </div>
          <span style="background: #DCFCE7; color: #166534; font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 10px;">QRIS: Lunas ✓</span>
        </div>

        <div style="display: flex; align-items: center; gap: 12px;">
          <div style="background: #EFF6FF; border: 2px solid #004AAD; width: 50px; height: 50px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
            ☕
          </div>
          <div>
            <div style="font-size: 14px; font-weight: 800; color: #0F172A;">Sedang Diramu di Dapur</div>
            <div style="font-size: 12px; color: #64748B;">Estimasi penyajian: <b style="color: #004AAD;">~5 - 8 Menit</b></div>
          </div>
        </div>
      </div>

      <!-- Pipeline Steps (ANSI/ISO Flowchart Sequence) -->
      <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 20px; padding: 20px 18px; margin-bottom: 16px;">
        <h4 style="font-size: 13px; font-weight: 800; color: #0F172A; margin-bottom: 16px;">Alur Penyajian Pesanan:</h4>

        <!-- Step 1: Diterima -->
        <div class="step-node completed">
          <div class="icon-circle done">✓</div>
          <div>
            <h5 style="font-size: 13px; font-weight: 800; color: #0F172A;">Pesanan Diterima Sistem</h5>
            <p style="font-size: 11px; color: #64748B;">Tiket pesanan masuk ke antrean kasir & dapur (11:42)</p>
          </div>
        </div>

        <!-- Step 2: Sedang Diramu (Current Active) -->
        <div class="step-node completed">
          <div class="icon-circle active">●</div>
          <div>
            <h5 style="font-size: 13px; font-weight: 800; color: #004AAD;">Sedang Diramu Barista</h5>
            <p style="font-size: 11px; color: #475569;">Barista sedang menyeduh espresso & meracik pesanan</p>
          </div>
        </div>

        <!-- Step 3: Siap Disajikan -->
        <div class="step-node">
          <div class="icon-circle pending">3</div>
          <div>
            <h5 style="font-size: 13px; font-weight: 700; color: #64748B;">Siap Disajikan (Pick-up Desk)</h5>
            <p style="font-size: 11px; color: #94A3B8;">Makanan & minuman selesai diracik</p>
          </div>
        </div>

        <!-- Step 4: Selesai -->
        <div class="step-node" style="padding-bottom: 0;">
          <div class="icon-circle pending">4</div>
          <div>
            <h5 style="font-size: 13px; font-weight: 700; color: #64748B;">Hidangan Tiba di Meja</h5>
            <p style="font-size: 11px; color: #94A3B8;">Waiter menyajikan ke Meja 03 & status selesai</p>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div style="display: flex; gap: 8px;">
        <button style="flex: 1; background: #FFFFFF; border: 2px solid #004AAD; color: #004AAD; font-size: 13px; font-weight: 800; padding: 13px; border-radius: 20px; cursor: pointer;">
          + Pesan Tambahan
        </button>
        <button style="flex: 1; background: #004AAD; border: none; color: #FFFFFF; font-size: 13px; font-weight: 800; padding: 13px; border-radius: 20px; cursor: pointer;">
          Panggil Waiter
        </button>
      </div>
    </div>
    <div class="home-indicator"></div>
  </div>
</body>
</html>"""
    with open(os.path.join(OUTPUT_DIR, "07_order_tracking.html"), "w") as f:
        f.write(html)

if __name__ == "__main__":
    print("Building Role 1 HTML screens...")
    build_screen_01()
    build_screen_02()
    build_screen_03()
    build_screen_04()
    build_screen_05()
    build_screen_06()
    build_screen_07()
    print("Role 1 HTML screens complete.")
