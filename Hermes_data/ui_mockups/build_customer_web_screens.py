#!/usr/bin/env python3
"""
Customer Desktop Web Screen Generator (Role 1 - Web Version)
Desktop Viewport (1440px x 900px)
Generates high-fidelity, publication-standard customer web interfaces.

Screens:
1. 01_web_portal_welcome.html  - Web Landing & Ordering Portal
2. 02_web_table_checkin.html   - Widescreen Table Check-In (QR & 5-box code)
3. 03_web_customer_catalog.html- Web Digital Catalog, AI Banner & Sticky Cart
4. 04_web_ai_barista.html      - Web AI Barista Consultation Canvas
5. 05_web_cart_checkout.html   - Web Order Review & QRIS Checkout
6. 06_web_order_tracking.html  - Web Live Order Tracking & Realtime Stepper
"""

import os

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

DESKTOP_HEAD = """
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; }
  body {
    background-color: #E2E8F0;
    font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
    color: #0F172A;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 30px;
  }
  .desktop-window {
    width: 1440px;
    height: 900px;
    background: #FFFFFF;
    border-radius: 20px;
    border: 2px solid #CBD5E1;
    box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    position: relative;
  }
  .window-titlebar {
    height: 42px;
    background: #F8FAFC;
    border-bottom: 1.5px solid #E2E8F0;
    display: flex;
    align-items: center;
    padding: 0 16px;
    justify-content: space-between;
    flex-shrink: 0;
  }
  .window-dots {
    display: flex;
    gap: 8px;
  }
  .window-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
  }
  .dot-red { background: #EF4444; }
  .dot-yellow { background: #F59E0B; }
  .dot-green { background: #10B981; }
  .window-urlbar {
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    border-radius: 8px;
    padding: 4px 16px;
    font-size: 12px;
    font-family: 'JetBrains Mono', monospace;
    color: #475569;
    width: 480px;
    text-align: center;
  }
  .window-badge {
    font-size: 11px;
    font-weight: 700;
    background: #DBEAFE;
    color: #1E40AF;
    padding: 3px 10px;
    border-radius: 6px;
    letter-spacing: 0.3px;
  }
  .window-body {
    flex: 1;
    background: #FFFFFF;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    position: relative;
  }
</style>
"""

# ==========================================
# 1. SCREEN 01: WEB PORTAL WELCOME / LANDING
# ==========================================
def build_screen_01():
    mascot_blue = MASCOT_SVG.format(s=36, c="#004AAD", fc="#004AAD", bg="#FFFFFF")
    star_top_left = STAR_SVG.format(w=110, h=105, deg=15, pos="top: -20px; left: -25px;", z=1, color="#004AAD")
    star_bottom_right = STAR_SVG.format(w=140, h=135, deg=-25, pos="bottom: -35px; right: -30px;", z=1, color="#004AAD")
    star_mid = STAR_SVG.format(w=45, h=42, deg=45, pos="top: 140px; right: 80px;", z=1, color="#3B82F6")

    html = f"""<!DOCTYPE html>
<html>
<head>
  <title>NONGKRONG.id - Customer Web Portal</title>
  {DESKTOP_HEAD}
  <style>
    .portal-nav {{
      height: 70px;
      border-bottom: 1.5px solid #E2E8F0;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 48px;
      background: #FFFFFF;
      z-index: 10;
    }}
    .brand-wrap {{
      display: flex;
      align-items: center;
      gap: 12px;
    }}
    .brand-title {{
      font-size: 24px;
      font-weight: 900;
      color: #004AAD;
      letter-spacing: -0.5px;
    }}
    .nav-links {{
      display: flex;
      gap: 28px;
      align-items: center;
    }}
    .nav-link {{
      font-size: 14px;
      font-weight: 600;
      color: #475569;
      text-decoration: none;
      transition: color 0.2s;
    }}
    .nav-link.active {{
      color: #004AAD;
      font-weight: 700;
    }}
    .nav-actions {{
      display: flex;
      gap: 14px;
      align-items: center;
    }}
    .btn-login-nav {{
      padding: 9px 20px;
      border: 1.5px solid #004AAD;
      background: #FFFFFF;
      color: #004AAD;
      font-size: 13px;
      font-weight: 700;
      border-radius: 999px;
      cursor: pointer;
    }}
    .btn-order-nav {{
      padding: 9px 24px;
      background: #004AAD;
      color: #FFFFFF;
      font-size: 13px;
      font-weight: 700;
      border-radius: 999px;
      border: none;
      cursor: pointer;
    }}

    .hero-section {{
      flex: 1;
      display: flex;
      padding: 40px 64px 30px 64px;
      gap: 48px;
      align-items: center;
      z-index: 5;
    }}
    .hero-left {{
      flex: 1.1;
      display: flex;
      flex-direction: column;
      gap: 20px;
    }}
    .hero-badge {{
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: #EFF6FF;
      border: 1.5px solid #BFDBFE;
      padding: 6px 14px;
      border-radius: 999px;
      width: fit-content;
      font-size: 12px;
      font-weight: 700;
      color: #1D4ED8;
    }}
    .hero-title {{
      font-size: 46px;
      font-weight: 900;
      line-height: 1.15;
      color: #004AAD;
      letter-spacing: -1px;
    }}
    .hero-title span {{
      color: #0F172A;
    }}
    .hero-desc {{
      font-size: 16px;
      line-height: 1.6;
      color: #475569;
      max-width: 540px;
    }}
    .hero-cta-group {{
      display: flex;
      gap: 16px;
      align-items: center;
      margin-top: 8px;
    }}
    .btn-hero-primary {{
      background: #004AAD;
      color: #FFFFFF;
      border: none;
      padding: 16px 36px;
      border-radius: 999px;
      font-size: 15px;
      font-weight: 800;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 10px;
    }}
    .btn-hero-secondary {{
      background: #FFFFFF;
      color: #004AAD;
      border: 2px solid #004AAD;
      padding: 15px 30px;
      border-radius: 999px;
      font-size: 15px;
      font-weight: 700;
      cursor: pointer;
    }}
    .hero-stats {{
      display: flex;
      gap: 32px;
      margin-top: 14px;
      padding-top: 20px;
      border-top: 1.5px solid #F1F5F9;
    }}
    .stat-item {{
      display: flex;
      flex-direction: column;
    }}
    .stat-num {{
      font-size: 22px;
      font-weight: 900;
      color: #004AAD;
    }}
    .stat-label {{
      font-size: 12px;
      color: #64748B;
      font-weight: 600;
    }}

    .hero-right {{
      flex: 0.9;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
    }}
    .coffee-hero-card {{
      width: 440px;
      height: 480px;
      background: #F8FAFC;
      border: 2px solid #CBD5E1;
      border-radius: 28px;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      position: relative;
    }}
    .card-illus-box {{
      flex: 1;
      background: #004AAD;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: #FFFFFF;
      position: relative;
      padding: 24px;
    }}
    .barista-duo {{
      display: flex;
      gap: 36px;
      align-items: center;
      justify-content: center;
    }}
    .illus-cup {{
      width: 110px;
      height: 110px;
      background: #FFFFFF;
      border-radius: 24px;
      border: 3px solid #0F172A;
      display: flex;
      align-items: center;
      justify-content: center;
    }}
    .floating-card-tag {{
      position: absolute;
      background: #FFFFFF;
      border: 2px solid #004AAD;
      border-radius: 14px;
      padding: 10px 18px;
      display: flex;
      align-items: center;
      gap: 10px;
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }}
    .tag-ai {{
      bottom: 24px;
      left: -20px;
    }}
    .tag-speed {{
      top: 30px;
      right: -20px;
    }}
    .hero-features-bar {{
      height: 90px;
      background: #F8FAFC;
      border-top: 1.5px solid #E2E8F0;
      display: flex;
      align-items: center;
      justify-content: space-around;
      padding: 0 48px;
      z-index: 10;
    }}
    .feat-item {{
      display: flex;
      align-items: center;
      gap: 14px;
    }}
    .feat-icon {{
      width: 42px;
      height: 42px;
      border-radius: 12px;
      background: #DBEAFE;
      border: 1.5px solid #93C5FD;
      color: #004AAD;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 800;
      font-size: 18px;
    }}
    .feat-info h4 {{
      font-size: 14px;
      font-weight: 800;
      color: #0F172A;
    }}
    .feat-info p {{
      font-size: 12px;
      color: #64748B;
    }}
  </style>
</head>
<body>
  <div class="desktop-window">
    <div class="window-titlebar">
      <div class="window-dots">
        <div class="window-dot dot-red"></div>
        <div class="window-dot dot-yellow"></div>
        <div class="window-dot dot-green"></div>
      </div>
      <div class="window-urlbar">https://nongkrong.id/</div>
      <div class="window-badge">Customer Web Portal</div>
    </div>

    <div class="window-body">
      {star_top_left}
      {star_bottom_right}
      {star_mid}

      <!-- Top Navigation -->
      <nav class="portal-nav">
        <div class="brand-wrap">
          {mascot_blue}
          <div class="brand-title">NONGKRONG.id</div>
        </div>
        <div class="nav-links">
          <a href="#" class="nav-link active">Beranda</a>
          <a href="#" class="nav-link">Menu Kopi</a>
          <a href="#" class="nav-link">AI Barista</a>
          <a href="#" class="nav-link">Tentang Kafe</a>
          <a href="#" class="nav-link">Cek Pesanan</a>
        </div>
        <div class="nav-actions">
          <button class="btn-login-nav">Login Staff</button>
          <button class="btn-order-nav">Pesan Cepat di Meja ➔</button>
        </div>
      </nav>

      <!-- Hero Section -->
      <div class="hero-section">
        <div class="hero-left">
          <div class="hero-badge">
            <span>✨</span> SMART CAFE SELF-ORDERING SYSTEM
          </div>
          <h1 class="hero-title">
            Fall in Love with Coffee in <span>NONGKRONG.id</span>
          </h1>
          <p class="hero-desc">
            Nikmati kemudahan memesan kopi racikan barista langsung dari mejamu. Konsultasikan selera rasamu dengan Barista AI kami dan pantau pesanan secara realtime tanpa perlu mengantre di kasir.
          </p>
          <div class="hero-cta-group">
            <button class="btn-hero-primary">
              Mulai Pesan di Meja
              <span style="font-size: 18px;">➔</span>
            </button>
            <button class="btn-hero-secondary">
              Lihat Menu Unggulan
            </button>
          </div>
          <div class="hero-stats">
            <div class="stat-item">
              <div class="stat-num">4.9 / 5.0</div>
              <div class="stat-label">Rating 1,200+ Pelanggan</div>
            </div>
            <div class="stat-item">
              <div class="stat-num">&lt; 3 Detik</div>
              <div class="stat-label">Siklus Sinkronisasi Realtime</div>
            </div>
            <div class="stat-item">
              <div class="stat-num">98% Match</div>
              <div class="stat-label">Akurasi Rekomendasi AI Barista</div>
            </div>
          </div>
        </div>

        <div class="hero-right">
          <div class="coffee-hero-card">
            <div class="card-illus-box">
              <div style="font-size: 11px; font-weight: 800; background: #FFFFFF; color: #004AAD; padding: 4px 12px; border-radius: 999px; margin-bottom: 24px;">PREMIUM ARTISAN COFFEE</div>
              <div class="barista-duo">
                <div class="illus-cup">
                  {MASCOT_SVG.format(s=64, c="#004AAD", fc="#004AAD", bg="#EFF6FF")}
                </div>
                <div style="text-align: left;">
                  <div style="font-size: 20px; font-weight: 900; line-height: 1.2;">Artisan Beans & Fresh Milk</div>
                  <div style="font-size: 13px; color: #DBEAFE; margin-top: 6px;">Disangrai segar tiap minggu</div>
                </div>
              </div>
              <div style="margin-top: 36px; font-size: 13px; color: #BFDBFE;">Teknologi Self-Order Berbasis Meja Terkoneksi</div>
            </div>

            <div class="floating-card-tag tag-ai">
              <div style="width: 32px; height: 32px; background: #DBEAFE; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 16px;">🤖</div>
              <div>
                <div style="font-size: 12px; font-weight: 800; color: #004AAD;">Gemini AI Barista</div>
                <div style="font-size: 10px; color: #64748B;">Rekomendasi rasa personal</div>
              </div>
            </div>

            <div class="floating-card-tag tag-speed">
              <div style="width: 32px; height: 32px; background: #DCFCE7; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 16px;">⚡</div>
              <div>
                <div style="font-size: 12px; font-weight: 800; color: #166534;">Scan & Order Cepat</div>
                <div style="font-size: 10px; color: #64748B;">Tanpa instal aplikasi</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Bottom Features Bar -->
      <div class="hero-features-bar">
        <div class="feat-item">
          <div class="feat-icon">01</div>
          <div class="feat-info">
            <h4>Check-in Meja Instan</h4>
            <p>Scan QR atau masukkan 5-digit kode meja</p>
          </div>
        </div>
        <div class="feat-item">
          <div class="feat-icon">02</div>
          <div class="feat-info">
            <h4>Konsultasi Mood & Rasa</h4>
            <p>AI racikkan 2-3 menu yang pas dengan seleramu</p>
          </div>
        </div>
        <div class="feat-item">
          <div class="feat-icon">03</div>
          <div class="feat-info">
            <h4>Live KDS Tracking</h4>
            <p>Pantau tahapan pembuatan dari dapur hingga saji</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>"""

    with open(os.path.join(OUTPUT_DIR, "01_web_portal_welcome.html"), "w", encoding="utf-8") as f:
        f.write(html)
    print("  ✓ Built 01_web_portal_welcome.html")


# ==========================================
# 2. SCREEN 02: WEB TABLE CHECK-IN
# ==========================================
def build_screen_02():
    mascot_blue = MASCOT_SVG.format(s=36, c="#004AAD", fc="#004AAD", bg="#FFFFFF")
    star_top_left = STAR_SVG.format(w=110, h=105, deg=15, pos="top: -20px; left: -25px;", z=1, color="#004AAD")
    star_bottom_right = STAR_SVG.format(w=140, h=135, deg=-25, pos="bottom: -35px; right: -30px;", z=1, color="#004AAD")
    star_top_right = STAR_SVG.format(w=60, h=56, deg=45, pos="top: 20px; right: 40px;", z=1, color="#004AAD")

    html = f"""<!DOCTYPE html>
<html>
<head>
  <title>NONGKRONG.id - Table Check-in (Web Version)</title>
  {DESKTOP_HEAD}
  <style>
    .checkin-header {{
      height: 70px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 48px;
      border-bottom: 1.5px solid #E2E8F0;
      background: #FFFFFF;
      z-index: 10;
    }}
    .checkin-body {{
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 30px;
      position: relative;
      background: #FFFFFF;
      z-index: 5;
    }}
    .split-checkin-card {{
      width: 1080px;
      height: 600px;
      background: #FFFFFF;
      border: 2px solid #004AAD;
      border-radius: 28px;
      display: flex;
      box-shadow: 0 15px 35px -5px rgba(0, 74, 173, 0.12);
      overflow: hidden;
      position: relative;
      z-index: 10;
    }}
    .checkin-left {{
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 40px;
      background: #F8FAFC;
      border-right: 2px dashed #CBD5E1;
      position: relative;
    }}
    .qr-frame-box {{
      width: 250px;
      height: 250px;
      background: #FFFFFF;
      border: 2.5px solid #004AAD;
      border-radius: 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 16px;
      position: relative;
      box-shadow: 0 10px 25px -5px rgba(0, 74, 173, 0.15);
    }}
    .qr-badge-top {{
      background: #004AAD;
      color: #FFFFFF;
      font-size: 11px;
      font-weight: 800;
      padding: 4px 14px;
      border-radius: 999px;
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      gap: 6px;
    }}
    .qr-code-svg {{
      width: 160px;
      height: 160px;
    }}
    .qr-left-subtext {{
      margin-top: 24px;
      text-align: center;
    }}
    .qr-left-title {{
      font-size: 18px;
      font-weight: 800;
      color: #004AAD;
      margin-bottom: 6px;
    }}
    .qr-left-desc {{
      font-size: 13px;
      color: #64748B;
      max-width: 280px;
    }}

    .or-divider-circle {{
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 48px;
      height: 48px;
      background: #004AAD;
      color: #FFFFFF;
      border: 3px solid #FFFFFF;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 900;
      font-size: 14px;
      z-index: 20;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }}

    .checkin-right {{
      flex: 1.1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 40px 50px;
      background: #FFFFFF;
      text-align: center;
    }}
    .right-headline {{
      font-size: 32px;
      font-weight: 900;
      color: #0F172A;
      letter-spacing: -0.5px;
      margin-bottom: 8px;
    }}
    .right-headline span {{
      color: #004AAD;
    }}
    .right-subhead {{
      font-size: 14px;
      color: #64748B;
      margin-bottom: 32px;
      max-width: 360px;
      line-height: 1.5;
    }}
    .code-boxes-group {{
      display: flex;
      gap: 14px;
      margin-bottom: 32px;
    }}
    .code-box {{
      width: 60px;
      height: 68px;
      border: 2.5px solid #004AAD;
      border-radius: 14px;
      background: #FFFFFF;
      font-size: 28px;
      font-weight: 900;
      color: #004AAD;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 10px rgba(0, 74, 173, 0.08);
      font-family: 'JetBrains Mono', monospace;
    }}
    .btn-confirm-pill {{
      width: 320px;
      height: 56px;
      background: #004AAD;
      color: #FFFFFF;
      border: none;
      border-radius: 999px;
      font-size: 16px;
      font-weight: 800;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      box-shadow: 0 10px 20px -3px rgba(0, 74, 173, 0.35);
      transition: transform 0.15s;
    }}
    .quick-tables-picker {{
      margin-top: 28px;
      display: flex;
      align-items: center;
      gap: 10px;
    }}
    .quick-lbl {{
      font-size: 12px;
      font-weight: 700;
      color: #64748B;
    }}
    .tbl-chip {{
      padding: 6px 14px;
      border-radius: 8px;
      background: #F1F5F9;
      border: 1px solid #CBD5E1;
      font-size: 12px;
      font-weight: 700;
      color: #334155;
      cursor: pointer;
    }}
    .tbl-chip.active {{
      background: #EFF6FF;
      border-color: #004AAD;
      color: #004AAD;
    }}
  </style>
</head>
<body>
  <div class="desktop-window">
    <div class="window-titlebar">
      <div class="window-dots">
        <div class="window-dot dot-red"></div>
        <div class="window-dot dot-yellow"></div>
        <div class="window-dot dot-green"></div>
      </div>
      <div class="window-urlbar">https://nongkrong.id/checkin</div>
      <div class="window-badge">Table Check-in Web</div>
    </div>

    <div class="window-body">
      {star_top_left}
      {star_bottom_right}
      {star_top_right}

      <div class="checkin-header">
        <div style="display: flex; align-items: center; gap: 12px;">
          {mascot_blue}
          <div style="font-size: 22px; font-weight: 900; color: #004AAD;">NONGKRONG.id</div>
        </div>
        <div style="font-size: 13px; font-weight: 700; color: #64748B;">
          📍 Lokasi: <strong>Cabang Utama Dipati Ukur</strong> • Meja Self-Ordering
        </div>
        <div>
          <button style="padding: 8px 18px; border: 1.5px solid #CBD5E1; background: #FFFFFF; font-size: 12px; font-weight: 700; border-radius: 999px; cursor: pointer;">Panggil Waiter</button>
        </div>
      </div>

      <div class="checkin-body">
        <div class="split-checkin-card">
          <!-- OR CIRCLE IN CENTER -->
          <div class="or-divider-circle">OR</div>

          <!-- LEFT: SCAN QR -->
          <div class="checkin-left">
            <div class="qr-frame-box">
              <div class="qr-badge-top">📷 SCAN DENGAN KAMERA</div>
              <!-- Crisp SVG QR -->
              <svg class="qr-code-svg" viewBox="0 0 100 100" fill="#004AAD">
                <rect x="10" y="10" width="30" height="30" rx="4" fill="none" stroke="#004AAD" stroke-width="6"/>
                <rect x="20" y="20" width="10" height="10" fill="#004AAD"/>
                <rect x="60" y="10" width="30" height="30" rx="4" fill="none" stroke="#004AAD" stroke-width="6"/>
                <rect x="70" y="20" width="10" height="10" fill="#004AAD"/>
                <rect x="10" y="60" width="30" height="30" rx="4" fill="none" stroke="#004AAD" stroke-width="6"/>
                <rect x="20" y="70" width="10" height="10" fill="#004AAD"/>
                <rect x="48" y="12" width="6" height="18" rx="2"/>
                <rect x="12" y="48" width="18" height="6" rx="2"/>
                <rect x="48" y="48" width="14" height="14" rx="3"/>
                <rect x="70" y="48" width="18" height="8" rx="2"/>
                <rect x="48" y="72" width="8" height="16" rx="2"/>
                <rect x="66" y="66" width="10" height="10" rx="2"/>
                <rect x="80" y="80" width="10" height="10" rx="2"/>
              </svg>
            </div>
            <div class="qr-left-subtext">
              <div class="qr-left-title">Arahkan Kamera ke QR Meja</div>
              <div class="qr-left-desc">Sistem otomatis mendeteksi nomor mejamu dan membuka menu pemesanan secara instan.</div>
            </div>
          </div>

          <!-- RIGHT: 5-BOX ACCESS CODE -->
          <div class="checkin-right">
            <div style="font-size: 11px; font-weight: 800; background: #DBEAFE; color: #004AAD; padding: 4px 12px; border-radius: 999px; margin-bottom: 12px;">MANUAL VERIFICATION</div>
            <h2 class="right-headline">Enter your table code <span>!</span></h2>
            <p class="right-subhead">Ketik 5 karakter unik yang tertera pada stiker akrilik di atas mejamu.</p>

            <div class="code-boxes-group">
              <div class="code-box">7</div>
              <div class="code-box">K</div>
              <div class="code-box">9</div>
              <div class="code-box">2</div>
              <div class="code-box">A</div>
            </div>

            <button class="btn-confirm-pill">
              Confirm Meja & Buka Menu
              <span style="font-size: 18px;">➔</span>
            </button>

            <div class="quick-tables-picker">
              <span class="quick-lbl">Contoh Meja Terdeteksi:</span>
              <span class="tbl-chip">Meja 01</span>
              <span class="tbl-chip">Meja 02</span>
              <span class="tbl-chip active">Meja 03 (7K92A)</span>
              <span class="tbl-chip">Meja VIP-1</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>"""

    with open(os.path.join(OUTPUT_DIR, "02_web_table_checkin.html"), "w", encoding="utf-8") as f:
        f.write(html)
    print("  ✓ Built 02_web_table_checkin.html")


# ==========================================
# 3. SCREEN 03: WEB DIGITAL CATALOG & STICKY CART
# ==========================================
def build_screen_03():
    mascot_blue = MASCOT_SVG.format(s=32, c="#004AAD", fc="#004AAD", bg="#FFFFFF")

    html = f"""<!DOCTYPE html>
<html>
<head>
  <title>NONGKRONG.id - Customer Web Catalog & Order</title>
  {DESKTOP_HEAD}
  <style>
    .cat-navbar {{
      height: 64px;
      border-bottom: 1.5px solid #E2E8F0;
      background: #FFFFFF;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 32px;
      z-index: 10;
    }}
    .nav-search {{
      width: 440px;
      height: 40px;
      background: #F8FAFC;
      border: 1.5px solid #CBD5E1;
      border-radius: 999px;
      display: flex;
      align-items: center;
      padding: 0 16px;
      gap: 10px;
    }}
    .nav-search input {{
      border: none;
      background: transparent;
      outline: none;
      font-size: 13px;
      font-family: inherit;
      width: 100%;
    }}
    .table-tag {{
      display: flex;
      align-items: center;
      gap: 8px;
      background: #004AAD;
      color: #FFFFFF;
      font-size: 13px;
      font-weight: 800;
      padding: 8px 18px;
      border-radius: 999px;
    }}

    .cat-content-split {{
      flex: 1;
      display: flex;
      overflow: hidden;
      background: #F8FAFC;
    }}

    /* LEFT: MENU SECTION */
    .cat-main-scroll {{
      flex: 1;
      overflow-y: auto;
      padding: 24px 32px;
      display: flex;
      flex-direction: column;
      gap: 20px;
    }}

    /* AI HERO BANNER */
    .ai-banner-card {{
      background: #004AAD;
      border-radius: 20px;
      padding: 22px 28px;
      color: #FFFFFF;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border: 2px solid #003087;
      position: relative;
      overflow: hidden;
    }}
    .ai-badge {{
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #FFFFFF;
      color: #004AAD;
      font-size: 11px;
      font-weight: 800;
      padding: 3px 10px;
      border-radius: 999px;
      margin-bottom: 8px;
    }}
    .ai-banner-title {{
      font-size: 22px;
      font-weight: 900;
      margin-bottom: 4px;
    }}
    .ai-banner-desc {{
      font-size: 13px;
      color: #DBEAFE;
      max-width: 480px;
    }}
    .btn-ai-cta {{
      background: #FFFFFF;
      color: #004AAD;
      border: none;
      padding: 12px 24px;
      border-radius: 999px;
      font-size: 13px;
      font-weight: 800;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      flex-shrink: 0;
    }}

    /* CATEGORIES BAR */
    .cat-tabs-bar {{
      display: flex;
      gap: 10px;
      overflow-x: auto;
      padding-bottom: 4px;
    }}
    .cat-tab {{
      padding: 8px 18px;
      border-radius: 999px;
      font-size: 13px;
      font-weight: 700;
      background: #FFFFFF;
      border: 1.5px solid #CBD5E1;
      color: #475569;
      cursor: pointer;
      white-space: nowrap;
    }}
    .cat-tab.active {{
      background: #004AAD;
      border-color: #004AAD;
      color: #FFFFFF;
    }}

    /* MENU GRID (3 COLS) */
    .menu-grid-3 {{
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 16px;
    }}
    .menu-card {{
      background: #FFFFFF;
      border: 1.5px solid #E2E8F0;
      border-radius: 16px;
      padding: 14px;
      display: flex;
      flex-direction: column;
      gap: 10px;
      transition: border-color 0.15s;
    }}
    .menu-card:hover {{
      border-color: #004AAD;
    }}
    .card-img-placeholder {{
      height: 120px;
      background: #EFF6FF;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      border: 1px solid #DBEAFE;
    }}
    .flavor-tag {{
      position: absolute;
      top: 8px;
      left: 8px;
      background: #FFFFFF;
      color: #004AAD;
      font-size: 10px;
      font-weight: 800;
      padding: 3px 8px;
      border-radius: 6px;
      border: 1px solid #BFDBFE;
    }}
    .menu-info h4 {{
      font-size: 15px;
      font-weight: 800;
      color: #0F172A;
      margin-bottom: 4px;
    }}
    .menu-info p {{
      font-size: 12px;
      color: #64748B;
      line-height: 1.4;
      height: 34px;
      overflow: hidden;
    }}
    .menu-bottom {{
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: 4px;
    }}
    .menu-price {{
      font-size: 15px;
      font-weight: 900;
      color: #004AAD;
    }}
    .btn-add-item {{
      width: 34px;
      height: 34px;
      background: #004AAD;
      color: #FFFFFF;
      border: none;
      border-radius: 50%;
      font-size: 18px;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
    }}

    /* RIGHT: STICKY CART (360px) */
    .cat-cart-panel {{
      width: 360px;
      background: #FFFFFF;
      border-left: 1.5px solid #E2E8F0;
      display: flex;
      flex-direction: column;
    }}
    .cart-header {{
      padding: 20px 24px;
      border-bottom: 1.5px solid #F1F5F9;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }}
    .cart-title {{
      font-size: 16px;
      font-weight: 800;
      color: #0F172A;
    }}
    .cart-items-scroll {{
      flex: 1;
      overflow-y: auto;
      padding: 16px 24px;
      display: flex;
      flex-direction: column;
      gap: 14px;
    }}
    .cart-item-row {{
      display: flex;
      gap: 12px;
      align-items: flex-start;
      padding-bottom: 12px;
      border-bottom: 1px solid #F1F5F9;
    }}
    .cart-item-info {{
      flex: 1;
    }}
    .cart-item-name {{
      font-size: 13px;
      font-weight: 800;
      color: #0F172A;
    }}
    .cart-item-note {{
      font-size: 11px;
      color: #64748B;
      margin-top: 2px;
    }}
    .cart-item-price {{
      font-size: 13px;
      font-weight: 800;
      color: #004AAD;
      margin-top: 4px;
    }}
    .cart-qty-ctrl {{
      display: flex;
      align-items: center;
      gap: 8px;
      background: #F8FAFC;
      border: 1px solid #CBD5E1;
      border-radius: 8px;
      padding: 2px 6px;
    }}
    .qty-btn {{
      border: none;
      background: transparent;
      font-weight: 800;
      font-size: 14px;
      color: #004AAD;
      cursor: pointer;
    }}
    .qty-num {{
      font-size: 12px;
      font-weight: 800;
      width: 16px;
      text-align: center;
    }}

    .cart-footer {{
      padding: 20px 24px;
      border-top: 1.5px solid #E2E8F0;
      background: #F8FAFC;
    }}
    .bill-row {{
      display: flex;
      justify-content: space-between;
      font-size: 13px;
      color: #64748B;
      margin-bottom: 8px;
    }}
    .bill-row.total {{
      font-size: 16px;
      font-weight: 900;
      color: #0F172A;
      margin-top: 10px;
      padding-top: 10px;
      border-top: 1px dashed #CBD5E1;
    }}
    .btn-checkout-cart {{
      width: 100%;
      height: 48px;
      background: #004AAD;
      color: #FFFFFF;
      border: none;
      border-radius: 999px;
      font-size: 14px;
      font-weight: 800;
      cursor: pointer;
      margin-top: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }}
  </style>
</head>
<body>
  <div class="desktop-window">
    <div class="window-titlebar">
      <div class="window-dots">
        <div class="window-dot dot-red"></div>
        <div class="window-dot dot-yellow"></div>
        <div class="window-dot dot-green"></div>
      </div>
      <div class="window-urlbar">https://nongkrong.id/order?table=03</div>
      <div class="window-badge">Catalog & Order Web</div>
    </div>

    <div class="window-body">
      <!-- Navbar -->
      <header class="cat-navbar">
        <div style="display: flex; align-items: center; gap: 12px;">
          {mascot_blue}
          <div style="font-size: 20px; font-weight: 900; color: #004AAD;">NONGKRONG.id</div>
        </div>

        <div class="nav-search">
          <span style="font-size: 14px; color: #64748B;">🔍</span>
          <input type="text" placeholder="Cari kopi, tea, makanan, atau snack..." value="">
        </div>

        <div style="display: flex; align-items: center; gap: 16px;">
          <div class="table-tag">
            <span style="width: 8px; height: 8px; background: #22C55E; border-radius: 50%;"></span>
            Meja 03 (Indoor)
          </div>
        </div>
      </header>

      <!-- Main Split -->
      <div class="cat-content-split">
        <!-- Menu Catalog (Scrollable) -->
        <div class="cat-main-scroll">
          <!-- AI Hero Banner -->
          <div class="ai-banner-card">
            <div>
              <div class="ai-badge">✨ GEMINI 1.5 FLASH COFFEE ASSISTANT</div>
              <h2 class="ai-banner-title">Bingung Mau Minum Apa Hari Ini?</h2>
              <p class="ai-banner-desc">Ceritakan rasa & mood favoritmu, Barista AI kami akan meracikkan rekomendasi menu yang paling pas dengan seleramu!</p>
            </div>
            <button class="btn-ai-cta">
              Tanya Barista AI ➔
            </button>
          </div>

          <!-- Category Pills -->
          <div class="cat-tabs-bar">
            <button class="cat-tab active">Semua Menu (15)</button>
            <button class="cat-tab">Espresso Based</button>
            <button class="cat-tab">Signature Coffee</button>
            <button class="cat-tab">Tea & Mocktail</button>
            <button class="cat-tab">Pastry & Snacks</button>
            <button class="cat-tab">Main Course</button>
          </div>

          <!-- Menu Grid -->
          <div class="menu-grid-3">
            <!-- 1 -->
            <div class="menu-card">
              <div class="card-img-placeholder">
                <span class="flavor-tag">Sweet & Creamy</span>
                <span style="font-size: 38px;">☕</span>
              </div>
              <div class="menu-info">
                <h4>Kopi Susu Aren</h4>
                <p>Espresso double-shot, susu sapi segar, dan pemanis gula aren Jawa.</p>
              </div>
              <div class="menu-bottom">
                <span class="menu-price">Rp 22.000</span>
                <button class="btn-add-item">+</button>
              </div>
            </div>

            <!-- 2 -->
            <div class="menu-card">
              <div class="card-img-placeholder">
                <span class="flavor-tag">Caramel & Nutty</span>
                <span style="font-size: 38px;">🥛</span>
              </div>
              <div class="menu-info">
                <h4>Caramel Macchiato</h4>
                <p>Vanilla syrup, steamed fresh milk, espresso & saus karamel drizzle.</p>
              </div>
              <div class="menu-bottom">
                <span class="menu-price">Rp 28.000</span>
                <button class="btn-add-item">+</button>
              </div>
            </div>

            <!-- 3 -->
            <div class="menu-card">
              <div class="card-img-placeholder">
                <span class="flavor-tag">Bitter & Bold</span>
                <span style="font-size: 38px;">🧊</span>
              </div>
              <div class="menu-info">
                <h4>Americano Cold Brew</h4>
                <p>Ekstraksi dingin 12 jam biji Arabika pilihan dengan sensasi fruity clean.</p>
              </div>
              <div class="menu-bottom">
                <span class="menu-price">Rp 20.000</span>
                <button class="btn-add-item">+</button>
              </div>
            </div>

            <!-- 4 -->
            <div class="menu-card">
              <div class="card-img-placeholder">
                <span class="flavor-tag">Earthy & Sweet</span>
                <span style="font-size: 38px;">🍵</span>
              </div>
              <div class="menu-info">
                <h4>Matcha Latte Iced</h4>
                <p>Pure Uji matcha Jepang dengan susu segar dingin kental.</p>
              </div>
              <div class="menu-bottom">
                <span class="menu-price">Rp 25.000</span>
                <button class="btn-add-item">+</button>
              </div>
            </div>

            <!-- 5 -->
            <div class="menu-card">
              <div class="card-img-placeholder">
                <span class="flavor-tag">Flaky & Butter</span>
                <span style="font-size: 38px;">🥐</span>
              </div>
              <div class="menu-info">
                <h4>Butter Croissant</h4>
                <p>Pastry berlapis renyah dengan butter asli Prancis dipanggang hangat.</p>
              </div>
              <div class="menu-bottom">
                <span class="menu-price">Rp 18.000</span>
                <button class="btn-add-item">+</button>
              </div>
            </div>

            <!-- 6 -->
            <div class="menu-card">
              <div class="card-img-placeholder">
                <span class="flavor-tag">Savory & Crispy</span>
                <span style="font-size: 38px;">🥪</span>
              </div>
              <div class="menu-info">
                <h4>Beef Panini Toast</h4>
                <p>Roti gandum panggang isi daging asap, keju mozarella meleleh.</p>
              </div>
              <div class="menu-bottom">
                <span class="menu-price">Rp 32.000</span>
                <button class="btn-add-item">+</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Sticky Cart Sidebar -->
        <div class="cat-cart-panel">
          <div class="cart-header">
            <div class="cart-title">🛒 Pesanan Meja 03</div>
            <span style="font-size: 12px; font-weight: 700; color: #004AAD; background: #DBEAFE; padding: 2px 8px; border-radius: 6px;">3 Item</span>
          </div>

          <div class="cart-items-scroll">
            <div class="cart-item-row">
              <div class="cart-item-info">
                <div class="cart-item-name">Caramel Macchiato</div>
                <div class="cart-item-note">Catatan: Less sugar 50%</div>
                <div class="cart-item-price">Rp 28.000</div>
              </div>
              <div class="cart-qty-ctrl">
                <button class="qty-btn">-</button>
                <span class="qty-num">1</span>
                <button class="qty-btn">+</button>
              </div>
            </div>

            <div class="cart-item-row">
              <div class="cart-item-info">
                <div class="cart-item-name">Kopi Susu Aren</div>
                <div class="cart-item-note">Catatan: Normal ice</div>
                <div class="cart-item-price">Rp 22.000</div>
              </div>
              <div class="cart-qty-ctrl">
                <button class="qty-btn">-</button>
                <span class="qty-num">1</span>
                <button class="qty-btn">+</button>
              </div>
            </div>

            <div class="cart-item-row">
              <div class="cart-item-info">
                <div class="cart-item-name">Butter Croissant</div>
                <div class="cart-item-note">Catatan: Dihangatkan</div>
                <div class="cart-item-price">Rp 18.000</div>
              </div>
              <div class="cart-qty-ctrl">
                <button class="qty-btn">-</button>
                <span class="qty-num">1</span>
                <button class="qty-btn">+</button>
              </div>
            </div>
          </div>

          <div class="cart-footer">
            <div class="bill-row">
              <span>Subtotal</span>
              <span>Rp 68.000</span>
            </div>
            <div class="bill-row">
              <span>PB1 (Pajak Resto 10%)</span>
              <span>Rp 6.800</span>
            </div>
            <div class="bill-row total">
              <span>Total Tagihan</span>
              <span style="color: #004AAD;">Rp 74.800</span>
            </div>
            <button class="btn-checkout-cart">
              Lanjut ke Checkout ➔
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>"""

    with open(os.path.join(OUTPUT_DIR, "03_web_customer_catalog.html"), "w", encoding="utf-8") as f:
        f.write(html)
    print("  ✓ Built 03_web_customer_catalog.html")


# ==========================================
# 4. SCREEN 04: WEB AI BARISTA CONSULTATION CANVAS
# ==========================================
def build_screen_04():
    mascot_blue = MASCOT_SVG.format(s=32, c="#004AAD", fc="#004AAD", bg="#FFFFFF")

    html = f"""<!DOCTYPE html>
<html>
<head>
  <title>NONGKRONG.id - Web AI Barista Consultation</title>
  {DESKTOP_HEAD}
  <style>
    .ai-web-navbar {{
      height: 64px;
      border-bottom: 1.5px solid #E2E8F0;
      background: #FFFFFF;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 32px;
      z-index: 10;
    }}
    .ai-web-split {{
      flex: 1;
      display: flex;
      overflow: hidden;
      background: #F8FAFC;
    }}

    /* LEFT SIDEBAR: BARISTA PERSONA & FILTER */
    .barista-sidebar {{
      width: 380px;
      background: #FFFFFF;
      border-right: 1.5px solid #E2E8F0;
      display: flex;
      flex-direction: column;
      padding: 28px 24px;
      gap: 20px;
    }}
    .persona-card {{
      background: #EFF6FF;
      border: 1.5px solid #BFDBFE;
      border-radius: 18px;
      padding: 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      gap: 10px;
    }}
    .persona-avatar {{
      width: 76px;
      height: 76px;
      background: #004AAD;
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
    }}
    .persona-name {{
      font-size: 18px;
      font-weight: 900;
      color: #004AAD;
    }}
    .persona-role {{
      font-size: 12px;
      font-weight: 700;
      color: #15803D;
      display: flex;
      align-items: center;
      gap: 6px;
    }}
    .persona-desc {{
      font-size: 12px;
      color: #64748B;
      line-height: 1.5;
    }}

    .quick-flavors-box {{
      display: flex;
      flex-direction: column;
      gap: 10px;
    }}
    .flavor-chips-grid {{
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }}
    .f-chip {{
      padding: 7px 14px;
      background: #F8FAFC;
      border: 1px solid #CBD5E1;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 700;
      color: #334155;
      cursor: pointer;
    }}
    .f-chip.active {{
      background: #004AAD;
      color: #FFFFFF;
      border-color: #004AAD;
    }}

    /* RIGHT: CHAT CANVAS & RECOMMENDATIONS */
    .chat-canvas {{
      flex: 1;
      display: flex;
      flex-direction: column;
      background: #FFFFFF;
    }}
    .chat-messages-area {{
      flex: 1;
      overflow-y: auto;
      padding: 32px 48px;
      display: flex;
      flex-direction: column;
      gap: 24px;
    }}

    /* BUBBLE BOT */
    .msg-bot-row {{
      display: flex;
      gap: 16px;
      align-items: flex-start;
      max-width: 800px;
    }}
    .msg-bot-bubble {{
      background: #F8FAFC;
      border: 1.5px solid #CBD5E1;
      border-radius: 4px 20px 20px 20px;
      padding: 16px 20px;
      font-size: 14px;
      line-height: 1.6;
      color: #0F172A;
    }}

    /* BUBBLE USER */
    .msg-user-row {{
      display: flex;
      justify-content: flex-end;
      width: 100%;
    }}
    .msg-user-bubble {{
      background: #004AAD;
      color: #FFFFFF;
      border-radius: 20px 20px 4px 20px;
      padding: 16px 22px;
      font-size: 14px;
      line-height: 1.6;
      max-width: 600px;
    }}

    /* RECOMMENDATION CARDS WRAP */
    .recom-container {{
      background: #EFF6FF;
      border: 2px solid #004AAD;
      border-radius: 20px;
      padding: 20px 24px;
      margin-top: 10px;
    }}
    .recom-header {{
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 14px;
    }}
    .recom-cards-row {{
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }}
    .recom-card {{
      background: #FFFFFF;
      border: 1.5px solid #BFDBFE;
      border-radius: 14px;
      padding: 16px;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }}
    .recom-badge-match {{
      background: #DBEAFE;
      color: #004AAD;
      font-size: 11px;
      font-weight: 800;
      padding: 3px 8px;
      border-radius: 6px;
      width: fit-content;
    }}
    .btn-add-recom {{
      background: #004AAD;
      color: #FFFFFF;
      border: none;
      padding: 8px 16px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 800;
      cursor: pointer;
      margin-top: 6px;
      width: fit-content;
    }}

    /* CHAT INPUT BAR */
    .chat-input-bar {{
      height: 76px;
      border-top: 1.5px solid #E2E8F0;
      background: #FFFFFF;
      display: flex;
      align-items: center;
      padding: 0 48px;
      gap: 16px;
    }}
    .chat-input-box {{
      flex: 1;
      height: 48px;
      background: #F8FAFC;
      border: 1.5px solid #CBD5E1;
      border-radius: 999px;
      display: flex;
      align-items: center;
      padding: 0 20px;
    }}
    .chat-input-box input {{
      width: 100%;
      border: none;
      background: transparent;
      outline: none;
      font-size: 14px;
      font-family: inherit;
    }}
    .btn-chat-send {{
      width: 48px;
      height: 48px;
      background: #004AAD;
      color: #FFFFFF;
      border: none;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      cursor: pointer;
    }}
  </style>
</head>
<body>
  <div class="desktop-window">
    <div class="window-titlebar">
      <div class="window-dots">
        <div class="window-dot dot-red"></div>
        <div class="window-dot dot-yellow"></div>
        <div class="window-dot dot-green"></div>
      </div>
      <div class="window-urlbar">https://nongkrong.id/order/ai-barista?table=03</div>
      <div class="window-badge">AI Barista Consultation Web</div>
    </div>

    <div class="window-body">
      <!-- Top Navbar -->
      <header class="ai-web-navbar">
        <div style="display: flex; align-items: center; gap: 14px;">
          <button style="border: 1.5px solid #CBD5E1; background: #FFFFFF; padding: 6px 14px; border-radius: 999px; font-size: 12px; font-weight: 700; cursor: pointer;">
            ← Kembali ke Menu
          </button>
          <div style="font-size: 18px; font-weight: 900; color: #004AAD;">Konsultasi Barista AI Virtual</div>
        </div>

        <div style="display: flex; align-items: center; gap: 12px;">
          <span style="font-size: 12px; font-weight: 700; color: #166534; background: #DCFCE7; padding: 4px 12px; border-radius: 999px;">
            ● Gemini 1.5 Flash Connected
          </span>
          <span style="font-size: 13px; font-weight: 800; background: #004AAD; color: #FFFFFF; padding: 6px 16px; border-radius: 999px;">
            Meja 03
          </span>
        </div>
      </header>

      <!-- Main Split -->
      <div class="ai-web-split">
        <!-- Left: Persona & Quick Chips -->
        <div class="barista-sidebar">
          <div class="persona-card">
            <div class="persona-avatar">
              {MASCOT_SVG.format(s=50, c="#FFFFFF", fc="#FFFFFF", bg="#004AAD")}
            </div>
            <div>
              <div class="persona-name">Mas Nongki</div>
              <div class="persona-role">● Barista Virtual Aktif</div>
            </div>
            <p class="persona-desc">
              AI barista yang siap menganalisis preferensi rasa, tingkat kemanisan, toleransi asam kopi, hingga mood santaimu di Meja 03.
            </p>
          </div>

          <div class="quick-flavors-box">
            <div style="font-size: 12px; font-weight: 800; color: #475569;">PILIHAN RASA CEPAT:</div>
            <div class="flavor-chips-grid">
              <span class="f-chip active">Manis & Creamy</span>
              <span class="f-chip">Kopi Pahit Bold</span>
              <span class="f-chip">Fruity & Segar</span>
              <span class="f-chip">Non-Coffee / Matcha</span>
              <span class="f-chip">Rendah Kalori</span>
              <span class="f-chip">Cemilan Renyah</span>
            </div>
          </div>

          <div style="margin-top: auto; padding: 14px; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px;">
            <div style="font-size: 11px; font-weight: 700; color: #64748B;">SINKRONISASI DATABASE:</div>
            <div style="font-size: 12px; font-weight: 800; color: #0F172A; margin-top: 2px;">15 Menu Aktif Terbaca</div>
          </div>
        </div>

        <!-- Right: Chat Canvas -->
        <div class="chat-canvas">
          <div class="chat-messages-area">
            <!-- Bot Welcome -->
            <div class="msg-bot-row">
              <div style="width: 38px; height: 38px; background: #004AAD; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                {MASCOT_SVG.format(s=24, c="#FFFFFF", fc="#FFFFFF", bg="#004AAD")}
              </div>
              <div class="msg-bot-bubble">
                Halo Allam di <strong>Meja 03</strong>! Lagi pengen racikan kopi yang manis creamy buat santai, atau butuh kafein pekat buat fokus ngerjain tugas hari ini? Ceritain selera rasamu ya!
              </div>
            </div>

            <!-- User Query -->
            <div class="msg-user-row">
              <div class="msg-user-bubble">
                Lagi butuh kopi dingin yang creamy, nggak terlalu pait, ada aroma caramel gurih dan manisnya pas buat nemenin ngetik tugas.
              </div>
            </div>

            <!-- Bot Structured Recommendation -->
            <div class="msg-bot-row">
              <div style="width: 38px; height: 38px; background: #004AAD; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                {MASCOT_SVG.format(s=24, c="#FFFFFF", fc="#FFFFFF", bg="#004AAD")}
              </div>
              <div style="flex: 1;">
                <div class="msg-bot-bubble" style="margin-bottom: 12px;">
                  Pilihan pas banget! Berdasarkan database menu NONGKRONG.id, ini 2 racikan paling cocok yang aromanya gurih karamel dan tingkat kepahitannya sangat ramah:
                </div>

                <div class="recom-container">
                  <div class="recom-header">
                    <span style="font-size: 13px; font-weight: 800; color: #004AAD;">🎯 REKOMENDASI TERBAIK BERDASARKAN TASTE PROFILE:</span>
                    <span style="font-size: 11px; font-weight: 700; color: #64748B;">Structured JSON Parsed</span>
                  </div>

                  <div class="recom-cards-row">
                    <div class="recom-card">
                      <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="recom-badge-match">98% Match</span>
                        <span style="font-size: 13px; font-weight: 900; color: #004AAD;">Rp 28.000</span>
                      </div>
                      <div style="font-size: 15px; font-weight: 800; color: #0F172A;">Caramel Macchiato Iced</div>
                      <div style="font-size: 12px; color: #64748B;">Espresso lembut dipadu saus karamel leleh dan fresh milk dingin.</div>
                      <button class="btn-add-recom">+ Tambah ke Keranjang Meja</button>
                    </div>

                    <div class="recom-card">
                      <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="recom-badge-match">94% Match</span>
                        <span style="font-size: 13px; font-weight: 900; color: #004AAD;">Rp 22.000</span>
                      </div>
                      <div style="font-size: 15px; font-weight: 800; color: #0F172A;">Kopi Susu Aren Spesial</div>
                      <div style="font-size: 12px; color: #64748B;">Kombinasi klasik gula aren organik gurih dengan susu segar lembut.</div>
                      <button class="btn-add-recom">+ Tambah ke Keranjang Meja</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Input Field -->
          <div class="chat-input-bar">
            <div class="chat-input-box">
              <input type="text" placeholder="Ketik preferensi rasa lain (contoh: mau yang tanpa susu atau camilan pendamping)...">
            </div>
            <button class="btn-chat-send">➤</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>"""

    with open(os.path.join(OUTPUT_DIR, "04_web_ai_barista.html"), "w", encoding="utf-8") as f:
        f.write(html)
    print("  ✓ Built 04_web_ai_barista.html")


# ==========================================
# 5. SCREEN 05: WEB CART REVIEW & QRIS CHECKOUT
# ==========================================
def build_screen_05():
    mascot_blue = MASCOT_SVG.format(s=32, c="#004AAD", fc="#004AAD", bg="#FFFFFF")

    html = f"""<!DOCTYPE html>
<html>
<head>
  <title>NONGKRONG.id - Web Order Review & Checkout</title>
  {DESKTOP_HEAD}
  <style>
    .co-navbar {{
      height: 64px;
      border-bottom: 1.5px solid #E2E8F0;
      background: #FFFFFF;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 32px;
      z-index: 10;
    }}
    .co-content-split {{
      flex: 1;
      display: flex;
      background: #F8FAFC;
      overflow: hidden;
    }}

    /* LEFT: ORDER ITEMS LIST (60%) */
    .co-items-col {{
      flex: 1.2;
      overflow-y: auto;
      padding: 32px 48px;
      display: flex;
      flex-direction: column;
      gap: 20px;
    }}
    .co-box-card {{
      background: #FFFFFF;
      border: 1.5px solid #E2E8F0;
      border-radius: 18px;
      padding: 24px;
      display: flex;
      flex-direction: column;
      gap: 16px;
    }}
    .box-card-title {{
      font-size: 16px;
      font-weight: 800;
      color: #0F172A;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }}
    .item-table-row {{
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 14px 0;
      border-bottom: 1px solid #F1F5F9;
    }}
    .item-main-info {{
      display: flex;
      gap: 14px;
      align-items: center;
    }}
    .item-icon-box {{
      width: 44px;
      height: 44px;
      background: #EFF6FF;
      border: 1px solid #BFDBFE;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
    }}
    .note-input-inline {{
      margin-top: 4px;
      font-size: 12px;
      color: #475569;
      background: #F8FAFC;
      border: 1px solid #CBD5E1;
      border-radius: 6px;
      padding: 4px 10px;
      width: 280px;
      outline: none;
    }}

    /* RIGHT: PAYMENT METHOD & SUMMARY (40%) */
    .co-pay-col {{
      flex: 0.9;
      background: #FFFFFF;
      border-left: 1.5px solid #E2E8F0;
      overflow-y: auto;
      padding: 32px 36px;
      display: flex;
      flex-direction: column;
      gap: 24px;
    }}
    .pay-methods-wrap {{
      display: flex;
      flex-direction: column;
      gap: 12px;
    }}
    .pay-card-opt {{
      border: 2px solid #CBD5E1;
      border-radius: 14px;
      padding: 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      cursor: pointer;
      background: #FFFFFF;
    }}
    .pay-card-opt.active {{
      border-color: #004AAD;
      background: #EFF6FF;
    }}
    .qris-display-box {{
      background: #FFFFFF;
      border: 2px solid #004AAD;
      border-radius: 16px;
      padding: 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      gap: 12px;
    }}
    .qris-code-img {{
      width: 170px;
      height: 170px;
      background: #F8FAFC;
      border: 1.5px solid #CBD5E1;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
    }}

    .btn-pay-submit {{
      width: 100%;
      height: 52px;
      background: #004AAD;
      color: #FFFFFF;
      border: none;
      border-radius: 999px;
      font-size: 15px;
      font-weight: 800;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      box-shadow: 0 10px 20px -3px rgba(0, 74, 173, 0.35);
    }}
  </style>
</head>
<body>
  <div class="desktop-window">
    <div class="window-titlebar">
      <div class="window-dots">
        <div class="window-dot dot-red"></div>
        <div class="window-dot dot-yellow"></div>
        <div class="window-dot dot-green"></div>
      </div>
      <div class="window-urlbar">https://nongkrong.id/checkout?table=03</div>
      <div class="window-badge">Review & Checkout Web</div>
    </div>

    <div class="window-body">
      <!-- Navbar -->
      <header class="co-navbar">
        <div style="display: flex; align-items: center; gap: 14px;">
          <button style="border: 1.5px solid #CBD5E1; background: #FFFFFF; padding: 6px 14px; border-radius: 999px; font-size: 12px; font-weight: 700; cursor: pointer;">
            ← Tambah Menu Lain
          </button>
          <div style="font-size: 18px; font-weight: 900; color: #004AAD;">Ringkasan Pesanan & Pembayaran</div>
        </div>

        <div style="display: flex; align-items: center; gap: 12px;">
          <span style="font-size: 13px; font-weight: 800; background: #004AAD; color: #FFFFFF; padding: 6px 16px; border-radius: 999px;">
            Meja 03 (Kode: 7K92A)
          </span>
        </div>
      </header>

      <!-- Main Split -->
      <div class="co-content-split">
        <!-- Left: Item List -->
        <div class="co-items-col">
          <div class="co-box-card">
            <div class="box-card-title">
              <span>📋 Daftar Hidangan yang Dipesan</span>
              <span style="font-size: 13px; color: #004AAD; font-weight: 700;">3 Item Terpilih</span>
            </div>

            <!-- Item 1 -->
            <div class="item-table-row">
              <div class="item-main-info">
                <div class="item-icon-box">☕</div>
                <div>
                  <div style="font-size: 14px; font-weight: 800; color: #0F172A;">Caramel Macchiato Iced (1x)</div>
                  <input type="text" class="note-input-inline" value="Catatan: Less sugar 50%, extra caramel">
                </div>
              </div>
              <div style="font-size: 14px; font-weight: 900; color: #004AAD;">Rp 28.000</div>
            </div>

            <!-- Item 2 -->
            <div class="item-table-row">
              <div class="item-main-info">
                <div class="item-icon-box">🥛</div>
                <div>
                  <div style="font-size: 14px; font-weight: 800; color: #0F172A;">Kopi Susu Aren Jawa (1x)</div>
                  <input type="text" class="note-input-inline" value="Catatan: Normal ice, manis pas">
                </div>
              </div>
              <div style="font-size: 14px; font-weight: 900; color: #004AAD;">Rp 22.000</div>
            </div>

            <!-- Item 3 -->
            <div class="item-table-row" style="border-bottom: none;">
              <div class="item-main-info">
                <div class="item-icon-box">🥐</div>
                <div>
                  <div style="font-size: 14px; font-weight: 800; color: #0F172A;">Butter Croissant Renyah (1x)</div>
                  <input type="text" class="note-input-inline" value="Catatan: Dihangatkan sebelum saji">
                </div>
              </div>
              <div style="font-size: 14px; font-weight: 900; color: #004AAD;">Rp 18.000</div>
            </div>
          </div>

          <div class="co-box-card">
            <div class="box-card-title">
              <span>✍️ Catatan Tambahan untuk Waiter & Dapur</span>
            </div>
            <textarea style="width: 100%; height: 60px; border: 1.5px solid #CBD5E1; border-radius: 10px; padding: 10px; font-family: inherit; font-size: 13px; resize: none;" placeholder="Contoh: Tolong disajikan bersamaan ya..."></textarea>
          </div>
        </div>

        <!-- Right: Payment -->
        <div class="co-pay-col">
          <div>
            <div style="font-size: 16px; font-weight: 800; color: #0F172A; margin-bottom: 12px;">Metode Pembayaran</div>
            <div class="pay-methods-wrap">
              <div class="pay-card-opt active">
                <div style="display: flex; align-items: center; gap: 12px;">
                  <span style="font-size: 20px;">📱</span>
                  <div>
                    <div style="font-size: 14px; font-weight: 800; color: #004AAD;">QRIS Dinamis (Instant)</div>
                    <div style="font-size: 11px; color: #64748B;">BCA, Mandiri, GoPay, OVO, ShopeePay</div>
                  </div>
                </div>
                <span style="color: #004AAD; font-weight: 800;">✓</span>
              </div>

              <div class="pay-card-opt">
                <div style="display: flex; align-items: center; gap: 12px;">
                  <span style="font-size: 20px;">💵</span>
                  <div>
                    <div style="font-size: 14px; font-weight: 800; color: #0F172A;">Bayar Tunai di Kasir</div>
                    <div style="font-size: 11px; color: #64748B;">Bayar langsung saat pesanan diantar/kasir</div>
                  </div>
                </div>
                <span style="color: #CBD5E1; font-weight: 800;">○</span>
              </div>
            </div>
          </div>

          <!-- QRIS Preview -->
          <div class="qris-display-box">
            <div style="font-size: 11px; font-weight: 800; background: #004AAD; color: #FFFFFF; padding: 4px 12px; border-radius: 999px;">SCAN UNTUK MEMBAYAR</div>
            <div class="qris-code-img">
              <svg width="130" height="130" viewBox="0 0 100 100" fill="#004AAD">
                <rect x="10" y="10" width="30" height="30" rx="4" fill="none" stroke="#004AAD" stroke-width="6"/>
                <rect x="20" y="20" width="10" height="10" fill="#004AAD"/>
                <rect x="60" y="10" width="30" height="30" rx="4" fill="none" stroke="#004AAD" stroke-width="6"/>
                <rect x="70" y="20" width="10" height="10" fill="#004AAD"/>
                <rect x="10" y="60" width="30" height="30" rx="4" fill="none" stroke="#004AAD" stroke-width="6"/>
                <rect x="20" y="70" width="10" height="10" fill="#004AAD"/>
                <rect x="48" y="12" width="6" height="18" rx="2"/>
                <rect x="12" y="48" width="18" height="6" rx="2"/>
                <rect x="48" y="48" width="14" height="14" rx="3"/>
                <rect x="70" y="48" width="18" height="8" rx="2"/>
                <rect x="48" y="72" width="8" height="16" rx="2"/>
              </svg>
            </div>
            <div style="font-size: 12px; font-weight: 700; color: #004AAD;">NONGKRONG.ID • PT NONGKI NUSANTARA</div>
          </div>

          <!-- Total Calculation -->
          <div>
            <div style="display: flex; justify-content: space-between; font-size: 13px; color: #64748B; margin-bottom: 8px;">
              <span>Subtotal Item (3)</span>
              <span>Rp 68.000</span>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 13px; color: #64748B; margin-bottom: 8px;">
              <span>PB1 Pajak Resto 10%</span>
              <span>Rp 6.800</span>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: 900; color: #0F172A; margin-top: 12px; padding-top: 12px; border-top: 2px dashed #E2E8F0;">
              <span>Total Bayar</span>
              <span style="color: #004AAD;">Rp 74.800</span>
            </div>
          </div>

          <button class="btn-pay-submit">
            Konfirmasi & Kirim Pesanan ke Dapur ➔
          </button>
        </div>
      </div>
    </div>
  </div>
</body>
</html>"""

    with open(os.path.join(OUTPUT_DIR, "05_web_cart_checkout.html"), "w", encoding="utf-8") as f:
        f.write(html)
    print("  ✓ Built 05_web_cart_checkout.html")


# ==========================================
# 6. SCREEN 06: WEB LIVE ORDER TRACKING
# ==========================================
def build_screen_06():
    mascot_blue = MASCOT_SVG.format(s=32, c="#004AAD", fc="#004AAD", bg="#FFFFFF")

    html = f"""<!DOCTYPE html>
<html>
<head>
  <title>NONGKRONG.id - Web Live Order Tracking</title>
  {DESKTOP_HEAD}
  <style>
    .track-navbar {{
      height: 64px;
      border-bottom: 1.5px solid #E2E8F0;
      background: #FFFFFF;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 32px;
      z-index: 10;
    }}
    .track-body {{
      flex: 1;
      overflow-y: auto;
      padding: 36px 64px;
      display: flex;
      flex-direction: column;
      gap: 28px;
      background: #F8FAFC;
    }}

    /* HEADER BANNER CARD */
    .status-hero-card {{
      background: #FFFFFF;
      border: 2px solid #004AAD;
      border-radius: 20px;
      padding: 24px 32px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 10px 25px -5px rgba(0, 74, 173, 0.08);
    }}
    .live-pulse {{
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: #DCFCE7;
      color: #166534;
      font-size: 12px;
      font-weight: 800;
      padding: 5px 14px;
      border-radius: 999px;
      margin-bottom: 6px;
    }}
    .pulse-dot {{
      width: 8px;
      height: 8px;
      background: #22C55E;
      border-radius: 50%;
    }}

    /* HORIZONTAL 4-STEPPER (DESKTOP) */
    .stepper-card {{
      background: #FFFFFF;
      border: 1.5px solid #CBD5E1;
      border-radius: 20px;
      padding: 32px 40px;
      display: flex;
      flex-direction: column;
      gap: 20px;
    }}
    .stepper-track-row {{
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: relative;
    }}
    .track-line-bg {{
      position: absolute;
      top: 24px;
      left: 60px;
      right: 60px;
      height: 4px;
      background: #E2E8F0;
      z-index: 1;
    }}
    .track-line-progress {{
      position: absolute;
      top: 24px;
      left: 60px;
      width: 40%;
      height: 4px;
      background: #004AAD;
      z-index: 2;
    }}
    .step-item {{
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      position: relative;
      z-index: 5;
      width: 180px;
    }}
    .step-circle {{
      width: 48px;
      height: 48px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 800;
      font-size: 16px;
      margin-bottom: 12px;
      border: 3px solid #FFFFFF;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }}
    .circle-done {{
      background: #004AAD;
      color: #FFFFFF;
    }}
    .circle-active {{
      background: #FFFFFF;
      border-color: #004AAD;
      color: #004AAD;
      border: 3px solid #004AAD;
    }}
    .circle-pending {{
      background: #F1F5F9;
      color: #94A3B8;
      border: 2px solid #CBD5E1;
    }}
    .step-title {{
      font-size: 14px;
      font-weight: 800;
      color: #0F172A;
      margin-bottom: 4px;
    }}
    .step-desc {{
      font-size: 11px;
      color: #64748B;
      line-height: 1.4;
    }}

    /* BOTTOM 2 COLUMNS */
    .track-details-split {{
      display: grid;
      grid-template-columns: 1.3fr 0.9fr;
      gap: 24px;
    }}
    .detail-card {{
      background: #FFFFFF;
      border: 1.5px solid #E2E8F0;
      border-radius: 18px;
      padding: 24px;
      display: flex;
      flex-direction: column;
      gap: 16px;
    }}
    .action-row-btn {{
      display: flex;
      gap: 14px;
    }}
    .btn-act-outline {{
      flex: 1;
      height: 46px;
      border: 2px solid #004AAD;
      background: #FFFFFF;
      color: #004AAD;
      border-radius: 999px;
      font-size: 13px;
      font-weight: 800;
      cursor: pointer;
    }}
    .btn-act-solid {{
      flex: 1;
      height: 46px;
      background: #004AAD;
      border: none;
      color: #FFFFFF;
      border-radius: 999px;
      font-size: 13px;
      font-weight: 800;
      cursor: pointer;
    }}
  </style>
</head>
<body>
  <div class="desktop-window">
    <div class="window-titlebar">
      <div class="window-dots">
        <div class="window-dot dot-red"></div>
        <div class="window-dot dot-yellow"></div>
        <div class="window-dot dot-green"></div>
      </div>
      <div class="window-urlbar">https://nongkrong.id/order/status/ORD-20261008-002</div>
      <div class="window-badge">Live Order Tracking Web</div>
    </div>

    <div class="window-body">
      <!-- Navbar -->
      <header class="track-navbar">
        <div style="display: flex; align-items: center; gap: 12px;">
          {mascot_blue}
          <div style="font-size: 20px; font-weight: 900; color: #004AAD;">NONGKRONG.id</div>
        </div>

        <div style="display: flex; align-items: center; gap: 14px;">
          <span style="font-size: 13px; font-weight: 700; color: #64748B;">Sesi Meja Aktif:</span>
          <span style="font-size: 13px; font-weight: 800; background: #004AAD; color: #FFFFFF; padding: 6px 16px; border-radius: 999px;">
            Meja 03
          </span>
        </div>
      </header>

      <div class="track-body">
        <!-- Status Hero -->
        <div class="status-hero-card">
          <div>
            <div class="live-pulse">
              <span class="pulse-dot"></span>
              REALTIME LIVEWIRE TRACKING (wire:poll.3s)
            </div>
            <h1 style="font-size: 24px; font-weight: 900; color: #004AAD; margin-top: 4px;">
              Pesanan #ORD-20261008-002 Sedang Diramu!
            </h1>
            <p style="font-size: 13px; color: #64748B; margin-top: 4px;">
              Barista di bar dapur sedang menyeduh minuman dan memanggang pastry pesananmu.
            </p>
          </div>

          <div style="text-align: right;">
            <div style="font-size: 12px; font-weight: 700; color: #64748B;">ESTIMASI PENYAJIAN:</div>
            <div style="font-size: 28px; font-weight: 900; color: #004AAD;">~ 5 – 8 Menit</div>
            <div style="font-size: 12px; font-weight: 800; color: #166534; background: #DCFCE7; padding: 3px 10px; border-radius: 6px; display: inline-block; margin-top: 4px;">QRIS: Lunas ✓</div>
          </div>
        </div>

        <!-- 4-Stepper Card -->
        <div class="stepper-card">
          <div style="font-size: 15px; font-weight: 800; color: #0F172A;">Tahapan Proses Pesanan Dapur:</div>

          <div class="stepper-track-row">
            <div class="track-line-bg"></div>
            <div class="track-line-progress"></div>

            <!-- Step 1: Done -->
            <div class="step-item">
              <div class="step-circle circle-done">✓</div>
              <div class="step-title">Pesanan Diterima</div>
              <div class="step-desc">Tiket masuk antrean sistem (11:42 WIB)</div>
            </div>

            <!-- Step 2: Active -->
            <div class="step-item">
              <div class="step-circle circle-active">☕</div>
              <div class="step-title" style="color: #004AAD;">Sedang Diramu</div>
              <div class="step-desc">Sedang diseduh & dipanggang di dapur</div>
            </div>

            <!-- Step 3: Pending -->
            <div class="step-item">
              <div class="step-circle circle-pending">3</div>
              <div class="step-title">Siap Disajikan</div>
              <div class="step-desc">Hidangan siap di Pick-up Desk</div>
            </div>

            <!-- Step 4: Pending -->
            <div class="step-item">
              <div class="step-circle circle-pending">4</div>
              <div class="step-title">Tiba di Meja</div>
              <div class="step-desc">Waiter menyajikan ke Meja 03</div>
            </div>
          </div>
        </div>

        <!-- 2 Columns Bottom -->
        <div class="track-details-split">
          <!-- Detail Menu -->
          <div class="detail-card">
            <div style="font-size: 15px; font-weight: 800; color: #0F172A; display: flex; justify-content: space-between;">
              <span>Rincian Tiket Meja 03</span>
              <span style="font-size: 13px; color: #004AAD; font-weight: 800;">Total: Rp 74.800</span>
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px; font-size: 13px;">
              <div style="display: flex; justify-content: space-between; padding-bottom: 8px; border-bottom: 1px solid #F1F5F9;">
                <span>1x Caramel Macchiato Iced (Less sugar 50%)</span>
                <strong>Rp 28.000</strong>
              </div>
              <div style="display: flex; justify-content: space-between; padding-bottom: 8px; border-bottom: 1px solid #F1F5F9;">
                <span>1x Kopi Susu Aren Spesial (Normal ice)</span>
                <strong>Rp 22.000</strong>
              </div>
              <div style="display: flex; justify-content: space-between; padding-bottom: 8px; border-bottom: 1px solid #F1F5F9;">
                <span>1x Butter Croissant Renyah (Hangat)</span>
                <strong>Rp 18.000</strong>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="detail-card">
            <div style="font-size: 15px; font-weight: 800; color: #0F172A;">Layanan Pelanggan Meja</div>
            <p style="font-size: 12px; color: #64748B;">Ada permintaan khusus atau butuh bantuan saat menunggu pesananmu?</p>
            <div class="action-row-btn">
              <button class="btn-act-outline">🔔 Panggil Waiter</button>
              <button class="btn-act-solid">+ Pesan Tambahan</button>
            </div>
            <div style="font-size: 11px; text-align: center; color: #94A3B8; margin-top: 6px;">
              Struk digital otomatis tersimpan di sistem kasir NONGKRONG.id
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>"""

    with open(os.path.join(OUTPUT_DIR, "06_web_order_tracking.html"), "w", encoding="utf-8") as f:
        f.write(html)
    print("  ✓ Built 06_web_order_tracking.html")


if __name__ == "__main__":
    print("Building Customer Web screens (Role 1 Desktop Web Version)...")
    build_screen_01()
    build_screen_02()
    build_screen_03()
    build_screen_04()
    build_screen_05()
    build_screen_06()
    print("All Customer Web screens successfully generated.")
