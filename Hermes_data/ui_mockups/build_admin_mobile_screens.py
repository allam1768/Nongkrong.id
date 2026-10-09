#!/usr/bin/env python3
"""
Role 3 Admin Mobile Screen Generator (Smartphone Viewport: 430px x 932px)
Generates high-fidelity, publication-standard mobile admin interfaces for cafe owner/superadmin.

Screens:
1. 01_admin_mobile_dashboard.html    - Executive Sales Dashboard & Hourly KPIs
2. 02_admin_mobile_menus.html        - Mobile Menu Catalog, Availability & Stock Control
3. 03_admin_mobile_ai_tuner.html     - AI Barista Gemini Config & System Prompt Tuner
4. 04_admin_mobile_table_monitor.html- Realtime Table Floor & Access Code Monitor
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

MOBILE_HEAD = """
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
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
    padding: 30px 20px;
  }
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
    flex-shrink: 0;
    background: #FFFFFF;
  }
  .phone-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    position: relative;
    background: #F8FAFC;
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
  /* Bottom Tab Bar */
  .admin-tabbar {
    height: 64px;
    background: #FFFFFF;
    border-top: 1.5px solid #E2E8F0;
    display: flex;
    align-items: center;
    justify-content: space-around;
    padding: 0 12px 10px 12px;
    flex-shrink: 0;
    z-index: 100;
  }
  .tab-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 3px;
    background: transparent;
    border: none;
    color: #64748B;
    font-size: 10px;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
  }
  .tab-btn.active {
    color: #004AAD;
  }
  .tab-icon {
    font-size: 18px;
  }
</style>
"""

# ==========================================
# 1. SCREEN 01: ADMIN MOBILE DASHBOARD
# ==========================================
def build_screen_01():
    mascot_blue = MASCOT_SVG.format(s=24, c="#FFFFFF", fc="#FFFFFF", bg="#004AAD")

    html = f"""<!DOCTYPE html>
<html>
<head>
  <title>NONGKRONG.id - Admin Mobile Dashboard</title>
  {MOBILE_HEAD}
  <style>
    .admin-header {{
      background: #FFFFFF;
      padding: 12px 20px 16px 20px;
      border-bottom: 1.5px solid #E2E8F0;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-shrink: 0;
    }}
    .owner-info {{
      display: flex;
      align-items: center;
      gap: 12px;
    }}
    .owner-avatar {{
      width: 44px;
      height: 44px;
      border-radius: 12px;
      background: #004AAD;
      border: 2px solid #003087;
      display: flex;
      align-items: center;
      justify-content: center;
    }}
    .scroll-body {{
      flex: 1;
      overflow-y: auto;
      padding: 16px;
      display: flex;
      flex-direction: column;
      gap: 16px;
    }}

    /* REVENUE HERO CARD */
    .hero-rev-card {{
      background: #004AAD;
      color: #FFFFFF;
      border-radius: 20px;
      padding: 20px;
      border: 2px solid #003087;
      position: relative;
    }}
    .rev-badge {{
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #FFFFFF;
      color: #004AAD;
      font-size: 11px;
      font-weight: 800;
      padding: 3px 10px;
      border-radius: 999px;
      margin-bottom: 10px;
    }}
    .rev-amount {{
      font-size: 30px;
      font-weight: 900;
      letter-spacing: -0.5px;
      margin-bottom: 6px;
    }}
    .rev-growth {{
      font-size: 12px;
      font-weight: 700;
      color: #86EFAC;
      display: flex;
      align-items: center;
      gap: 4px;
    }}

    /* 3 KPI ROW */
    .kpi-row-3 {{
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 10px;
    }}
    .kpi-box {{
      background: #FFFFFF;
      border: 1.5px solid #E2E8F0;
      border-radius: 14px;
      padding: 12px 10px;
      display: flex;
      flex-direction: column;
      gap: 4px;
    }}
    .kpi-val {{
      font-size: 17px;
      font-weight: 900;
      color: #0F172A;
    }}
    .kpi-lbl {{
      font-size: 11px;
      color: #64748B;
      font-weight: 600;
    }}

    /* HOURLY SALES BAR CHART */
    .chart-card {{
      background: #FFFFFF;
      border: 1.5px solid #E2E8F0;
      border-radius: 18px;
      padding: 16px;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }}
    .chart-header {{
      display: flex;
      justify-content: space-between;
      align-items: center;
    }}
    .bars-wrap {{
      display: flex;
      align-items: flex-end;
      justify-content: space-between;
      height: 100px;
      padding-top: 10px;
      border-bottom: 1.5px solid #E2E8F0;
    }}
    .bar-col {{
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 6px;
      width: 32px;
    }}
    .bar-pill {{
      width: 18px;
      background: #CBD5E1;
      border-radius: 4px 4px 0 0;
    }}
    .bar-pill.peak {{
      background: #004AAD;
    }}
    .bar-time {{
      font-size: 10px;
      font-weight: 700;
      color: #64748B;
    }}

    /* TOP MENUS LIST */
    .top-menu-card {{
      background: #FFFFFF;
      border: 1.5px solid #E2E8F0;
      border-radius: 18px;
      padding: 16px;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }}
    .menu-stat-item {{
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding-bottom: 10px;
      border-bottom: 1px solid #F1F5F9;
    }}
    .menu-rank {{
      width: 24px;
      height: 24px;
      border-radius: 6px;
      background: #EFF6FF;
      color: #004AAD;
      font-size: 12px;
      font-weight: 800;
      display: flex;
      align-items: center;
      justify-content: center;
    }}
  </style>
</head>
<body>
  <div class="phone-frame">
    <div class="dynamic-island"></div>

    <!-- iOS Status Bar -->
    <div class="status-bar">
      <span>09:41</span>
      <div style="display: flex; gap: 6px; align-items: center;">
        <span style="font-size: 12px;">5G</span>
        <span style="font-size: 14px;">100%</span>
      </div>
    </div>

    <!-- Admin Top Header -->
    <header class="admin-header">
      <div class="owner-info">
        <div class="owner-avatar">
          {mascot_blue}
        </div>
        <div>
          <div style="font-size: 15px; font-weight: 900; color: #0F172A;">Allam (puu)</div>
          <div style="font-size: 11px; font-weight: 700; color: #004AAD;">👑 Owner • Cabang Dipati Ukur</div>
        </div>
      </div>
      <div style="display: flex; gap: 8px;">
        <span style="font-size: 11px; font-weight: 800; background: #DCFCE7; color: #166534; padding: 4px 10px; border-radius: 999px;">
          ● Buka
        </span>
      </div>
    </header>

    <!-- Content -->
    <div class="phone-content">
      <div class="scroll-body">
        <!-- Revenue Card -->
        <div class="hero-rev-card">
          <div class="rev-badge">💰 OMZET PENJUALAN HARI INI</div>
          <div class="rev-amount">Rp 4.250.000</div>
          <div class="rev-growth">
            <span>▲ +18.4%</span> vs kemarin (142 Transaksi)
          </div>
        </div>

        <!-- 3 KPIs -->
        <div class="kpi-row-3">
          <div class="kpi-box">
            <span class="kpi-val">142</span>
            <span class="kpi-lbl">Tiket Pesanan</span>
          </div>
          <div class="kpi-box">
            <span class="kpi-val">83.3%</span>
            <span class="kpi-lbl">Okupansi Meja</span>
          </div>
          <div class="kpi-box">
            <span class="kpi-val">Rp 29.9k</span>
            <span class="kpi-lbl">Rata-rata/Order</span>
          </div>
        </div>

        <!-- Hourly Sales Peak Bar Chart -->
        <div class="chart-card">
          <div class="chart-header">
            <div>
              <div style="font-size: 14px; font-weight: 800; color: #0F172A;">Tren Penjualan Per Jam</div>
              <div style="font-size: 11px; color: #64748B;">Puncak keramaian (Peak Hours)</div>
            </div>
            <span style="font-size: 11px; font-weight: 700; color: #004AAD; background: #EFF6FF; padding: 2px 8px; border-radius: 6px;">Hari Ini</span>
          </div>

          <div class="bars-wrap">
            <div class="bar-col">
              <div class="bar-pill" style="height: 35px;"></div>
              <span class="bar-time">10:00</span>
            </div>
            <div class="bar-col">
              <div class="bar-pill" style="height: 55px;"></div>
              <span class="bar-time">12:00</span>
            </div>
            <div class="bar-col">
              <div class="bar-pill peak" style="height: 85px;"></div>
              <span class="bar-time">14:00</span>
            </div>
            <div class="bar-col">
              <div class="bar-pill peak" style="height: 95px;"></div>
              <span class="bar-time">16:00</span>
            </div>
            <div class="bar-col">
              <div class="bar-pill peak" style="height: 75px;"></div>
              <span class="bar-time">18:00</span>
            </div>
            <div class="bar-col">
              <div class="bar-pill" style="height: 45px;"></div>
              <span class="bar-time">20:00</span>
            </div>
          </div>
        </div>

        <!-- Top Selling Menu -->
        <div class="top-menu-card">
          <div style="font-size: 14px; font-weight: 800; color: #0F172A;">Menu Terlaris Hari Ini</div>

          <div class="menu-stat-item">
            <div style="display: flex; gap: 10px; align-items: center;">
              <span class="menu-rank">1</span>
              <div>
                <div style="font-size: 13px; font-weight: 800; color: #0F172A;">Kopi Susu Aren</div>
                <div style="font-size: 11px; color: #64748B;">48 Cups terjual</div>
              </div>
            </div>
            <div style="font-size: 13px; font-weight: 900; color: #004AAD;">Rp 1.056.000</div>
          </div>

          <div class="menu-stat-item">
            <div style="display: flex; gap: 10px; align-items: center;">
              <span class="menu-rank">2</span>
              <div>
                <div style="font-size: 13px; font-weight: 800; color: #0F172A;">Caramel Macchiato</div>
                <div style="font-size: 11px; color: #64748B;">36 Cups terjual</div>
              </div>
            </div>
            <div style="font-size: 13px; font-weight: 900; color: #004AAD;">Rp 1.008.000</div>
          </div>

          <div class="menu-stat-item" style="border-bottom: none; padding-bottom: 0;">
            <div style="display: flex; gap: 10px; align-items: center;">
              <span class="menu-rank">3</span>
              <div>
                <div style="font-size: 13px; font-weight: 800; color: #0F172A;">Butter Croissant</div>
                <div style="font-size: 11px; color: #64748B;">28 Pcs terjual</div>
              </div>
            </div>
            <div style="font-size: 13px; font-weight: 900; color: #004AAD;">Rp 504.000</div>
          </div>
        </div>
      </div>

      <!-- Bottom Tab Bar -->
      <nav class="admin-tabbar">
        <a href="#" class="tab-btn active">
          <span class="tab-icon">📊</span>
          <span>Ringkasan</span>
        </a>
        <a href="#" class="tab-btn">
          <span class="tab-icon">☕</span>
          <span>Menu & Stok</span>
        </a>
        <a href="#" class="tab-btn">
          <span class="tab-icon">🪑</span>
          <span>Meja Kafe</span>
        </a>
        <a href="#" class="tab-btn">
          <span class="tab-icon">🤖</span>
          <span>AI Barista</span>
        </a>
      </nav>
    </div>

    <div class="home-indicator"></div>
  </div>
</body>
</html>"""

    with open(os.path.join(OUTPUT_DIR, "01_admin_mobile_dashboard.html"), "w", encoding="utf-8") as f:
        f.write(html)
    print("  ✓ Built 01_admin_mobile_dashboard.html")


# ==========================================
# 2. SCREEN 02: ADMIN MOBILE MENUS & STOCK
# ==========================================
def build_screen_02():
    html = f"""<!DOCTYPE html>
<html>
<head>
  <title>NONGKRONG.id - Admin Mobile Menu & Stock</title>
  {MOBILE_HEAD}
  <style>
    .menu-mgmt-header {{
      background: #FFFFFF;
      padding: 12px 20px 14px 20px;
      border-bottom: 1.5px solid #E2E8F0;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-shrink: 0;
    }}
    .btn-add-menu-sm {{
      background: #004AAD;
      color: #FFFFFF;
      border: none;
      padding: 7px 14px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 800;
      cursor: pointer;
    }}
    .scroll-body {{
      flex: 1;
      overflow-y: auto;
      padding: 16px;
      display: flex;
      flex-direction: column;
      gap: 14px;
    }}
    .search-box-m {{
      background: #FFFFFF;
      border: 1.5px solid #CBD5E1;
      border-radius: 999px;
      padding: 8px 16px;
      display: flex;
      align-items: center;
      gap: 10px;
    }}
    .search-box-m input {{
      border: none;
      background: transparent;
      outline: none;
      font-size: 13px;
      width: 100%;
      font-family: inherit;
    }}
    .cat-chips-row {{
      display: flex;
      gap: 8px;
      overflow-x: auto;
      padding-bottom: 2px;
    }}
    .cat-chip-m {{
      padding: 6px 14px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 700;
      background: #FFFFFF;
      border: 1.5px solid #CBD5E1;
      color: #475569;
      white-space: nowrap;
    }}
    .cat-chip-m.active {{
      background: #004AAD;
      color: #FFFFFF;
      border-color: #004AAD;
    }}

    /* MENU CARD COMPONENT */
    .menu-card-m {{
      background: #FFFFFF;
      border: 1.5px solid #E2E8F0;
      border-radius: 16px;
      padding: 14px;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }}
    .card-top-row {{
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
    }}
    .menu-title-m {{
      font-size: 15px;
      font-weight: 800;
      color: #0F172A;
    }}
    .menu-cat-m {{
      font-size: 11px;
      color: #64748B;
      font-weight: 600;
    }}
    .toggle-switch {{
      width: 44px;
      height: 24px;
      background: #004AAD;
      border-radius: 999px;
      position: relative;
      cursor: pointer;
    }}
    .toggle-knob {{
      width: 18px;
      height: 18px;
      background: #FFFFFF;
      border-radius: 50%;
      position: absolute;
      top: 3px;
      right: 3px;
    }}
    .toggle-switch.off {{
      background: #CBD5E1;
    }}
    .toggle-switch.off .toggle-knob {{
      right: auto;
      left: 3px;
    }}

    .card-mid-row {{
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #F8FAFC;
      border: 1px solid #E2E8F0;
      border-radius: 10px;
      padding: 8px 12px;
    }}
    .tag-taste-ai {{
      background: #EFF6FF;
      color: #004AAD;
      font-size: 10px;
      font-weight: 800;
      padding: 3px 8px;
      border-radius: 6px;
      border: 1px solid #BFDBFE;
    }}
    .card-act-row {{
      display: flex;
      gap: 8px;
    }}
    .btn-act-m {{
      flex: 1;
      padding: 7px;
      border-radius: 8px;
      font-size: 11px;
      font-weight: 700;
      cursor: pointer;
      text-align: center;
      border: 1.5px solid #CBD5E1;
      background: #FFFFFF;
      color: #334155;
    }}
  </style>
</head>
<body>
  <div class="phone-frame">
    <div class="dynamic-island"></div>

    <div class="status-bar">
      <span>09:41</span>
      <div style="display: flex; gap: 6px; align-items: center;">
        <span style="font-size: 12px;">5G</span>
        <span style="font-size: 14px;">100%</span>
      </div>
    </div>

    <header class="menu-mgmt-header">
      <div>
        <div style="font-size: 16px; font-weight: 900; color: #0F172A;">Daftar Menu & Stok</div>
        <div style="font-size: 11px; color: #64748B;">Total 15 Menu Terdaftar</div>
      </div>
      <button class="btn-add-menu-sm">+ Menu Baru</button>
    </header>

    <div class="phone-content">
      <div class="scroll-body">
        <!-- Search -->
        <div class="search-box-m">
          <span style="font-size: 14px; color: #64748B;">🔍</span>
          <input type="text" placeholder="Cari kopi, pastry, cemilan..." value="">
        </div>

        <!-- Filter Chips -->
        <div class="cat-chips-row">
          <button class="cat-chip-m active">Semua (15)</button>
          <button class="cat-chip-m">Espresso</button>
          <button class="cat-chip-m">Signature</button>
          <button class="cat-chip-m">Pastry</button>
          <button class="cat-chip-m">Habis (1)</button>
        </div>

        <!-- Card 1 -->
        <div class="menu-card-m">
          <div class="card-top-row">
            <div>
              <div class="menu-title-m">Kopi Susu Aren</div>
              <div class="menu-cat-m">Signature Coffee • Rp 22.000</div>
            </div>
            <div class="toggle-switch">
              <div class="toggle-knob"></div>
            </div>
          </div>
          <div class="card-mid-row">
            <span class="tag-taste-ai">✨ AI Taste: Sweet & Creamy</span>
            <span style="font-size: 12px; font-weight: 800; color: #166534;">Stok: 42 Porsi</span>
          </div>
          <div class="card-act-row">
            <button class="btn-act-m">Edit Profil AI</button>
            <button class="btn-act-m">Ubah Stok</button>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="menu-card-m">
          <div class="card-top-row">
            <div>
              <div class="menu-title-m">Caramel Macchiato</div>
              <div class="menu-cat-m">Espresso Based • Rp 28.000</div>
            </div>
            <div class="toggle-switch">
              <div class="toggle-knob"></div>
            </div>
          </div>
          <div class="card-mid-row">
            <span class="tag-taste-ai">✨ AI Taste: Caramel & Nutty</span>
            <span style="font-size: 12px; font-weight: 800; color: #166534;">Stok: 18 Porsi</span>
          </div>
          <div class="card-act-row">
            <button class="btn-act-m">Edit Profil AI</button>
            <button class="btn-act-m">Ubah Stok</button>
          </div>
        </div>

        <!-- Card 3: Out of stock -->
        <div class="menu-card-m" style="border-color: #FECACA;">
          <div class="card-top-row">
            <div>
              <div class="menu-title-m" style="color: #991B1B;">Matcha Latte Iced (Habis)</div>
              <div class="menu-cat-m">Non-Coffee • Rp 25.000</div>
            </div>
            <div class="toggle-switch off">
              <div class="toggle-knob"></div>
            </div>
          </div>
          <div class="card-mid-row" style="background: #FEF2F2; border-color: #FECACA;">
            <span class="tag-taste-ai">✨ AI Taste: Earthy & Sweet</span>
            <span style="font-size: 12px; font-weight: 800; color: #EF4444;">Stok: 0 (Kosong)</span>
          </div>
          <div class="card-act-row">
            <button class="btn-act-m">Edit Profil AI</button>
            <button class="btn-act-m" style="background: #004AAD; color: #FFFFFF; border-color: #004AAD;">+ Restock</button>
          </div>
        </div>

        <!-- Card 4 -->
        <div class="menu-card-m">
          <div class="card-top-row">
            <div>
              <div class="menu-title-m">Butter Croissant</div>
              <div class="menu-cat-m">Pastry & Bakery • Rp 18.000</div>
            </div>
            <div class="toggle-switch">
              <div class="toggle-knob"></div>
            </div>
          </div>
          <div class="card-mid-row">
            <span class="tag-taste-ai">✨ AI Taste: Flaky & Butter</span>
            <span style="font-size: 12px; font-weight: 800; color: #166534;">Stok: 12 Porsi</span>
          </div>
          <div class="card-act-row">
            <button class="btn-act-m">Edit Profil AI</button>
            <button class="btn-act-m">Ubah Stok</button>
          </div>
        </div>
      </div>

      <!-- Bottom Tab Bar -->
      <nav class="admin-tabbar">
        <a href="#" class="tab-btn">
          <span class="tab-icon">📊</span>
          <span>Ringkasan</span>
        </a>
        <a href="#" class="tab-btn active">
          <span class="tab-icon">☕</span>
          <span>Menu & Stok</span>
        </a>
        <a href="#" class="tab-btn">
          <span class="tab-icon">🪑</span>
          <span>Meja Kafe</span>
        </a>
        <a href="#" class="tab-btn">
          <span class="tab-icon">🤖</span>
          <span>AI Barista</span>
        </a>
      </nav>
    </div>

    <div class="home-indicator"></div>
  </div>
</body>
</html>"""

    with open(os.path.join(OUTPUT_DIR, "02_admin_mobile_menus.html"), "w", encoding="utf-8") as f:
        f.write(html)
    print("  ✓ Built 02_admin_mobile_menus.html")


# ==========================================
# 3. SCREEN 03: ADMIN MOBILE AI BARISTA TUNER
# ==========================================
def build_screen_03():
    mascot_blue = MASCOT_SVG.format(s=36, c="#004AAD", fc="#004AAD", bg="#EFF6FF")

    html = f"""<!DOCTYPE html>
<html>
<head>
  <title>NONGKRONG.id - Admin Mobile AI Tuner</title>
  {MOBILE_HEAD}
  <style>
    .ai-tuner-header {{
      background: #FFFFFF;
      padding: 12px 20px 14px 20px;
      border-bottom: 1.5px solid #E2E8F0;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-shrink: 0;
    }}
    .scroll-body {{
      flex: 1;
      overflow-y: auto;
      padding: 16px;
      display: flex;
      flex-direction: column;
      gap: 14px;
    }}

    /* ENGINE HEALTH CARD */
    .engine-health-card {{
      background: #004AAD;
      color: #FFFFFF;
      border-radius: 18px;
      padding: 18px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border: 2px solid #003087;
    }}
    .health-stats {{
      display: flex;
      gap: 14px;
      margin-top: 8px;
    }}
    .h-stat {{
      font-size: 11px;
      color: #DBEAFE;
    }}
    .h-stat strong {{
      color: #FFFFFF;
    }}

    /* TUNING CONTROLS CARD */
    .ctrl-box-card {{
      background: #FFFFFF;
      border: 1.5px solid #E2E8F0;
      border-radius: 16px;
      padding: 16px;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }}
    .ctrl-row {{
      display: flex;
      justify-content: space-between;
      align-items: center;
    }}
    .ctrl-label {{
      font-size: 13px;
      font-weight: 700;
      color: #0F172A;
    }}
    .ctrl-val-badge {{
      font-size: 12px;
      font-weight: 800;
      color: #004AAD;
      background: #EFF6FF;
      padding: 3px 10px;
      border-radius: 6px;
    }}
    .slider-bar-mock {{
      height: 8px;
      background: #E2E8F0;
      border-radius: 999px;
      position: relative;
    }}
    .slider-fill {{
      width: 70%;
      height: 100%;
      background: #004AAD;
      border-radius: 999px;
    }}

    /* SYSTEM PROMPT TEXTAREA */
    .prompt-box-card {{
      background: #FFFFFF;
      border: 1.5px solid #E2E8F0;
      border-radius: 16px;
      padding: 16px;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }}
    .prompt-textarea-m {{
      width: 100%;
      height: 100px;
      background: #F8FAFC;
      border: 1.5px solid #CBD5E1;
      border-radius: 10px;
      padding: 10px;
      font-size: 12px;
      color: #334155;
      font-family: inherit;
      resize: none;
      line-height: 1.5;
    }}

    /* QUICK TEST SIMULATOR */
    .sim-card {{
      background: #EFF6FF;
      border: 1.5px solid #BFDBFE;
      border-radius: 16px;
      padding: 14px;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }}
    .sim-bubble {{
      background: #FFFFFF;
      border: 1px solid #CBD5E1;
      border-radius: 10px;
      padding: 10px;
      font-size: 12px;
      color: #0F172A;
      line-height: 1.4;
    }}

    .btn-save-ai-cfg {{
      width: 100%;
      height: 48px;
      background: #004AAD;
      color: #FFFFFF;
      border: none;
      border-radius: 999px;
      font-size: 14px;
      font-weight: 800;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      box-shadow: 0 4px 14px rgba(0, 74, 173, 0.3);
    }}
  </style>
</head>
<body>
  <div class="phone-frame">
    <div class="dynamic-island"></div>

    <div class="status-bar">
      <span>09:41</span>
      <div style="display: flex; gap: 6px; align-items: center;">
        <span style="font-size: 12px;">5G</span>
        <span style="font-size: 14px;">100%</span>
      </div>
    </div>

    <header class="ai-tuner-header">
      <div>
        <div style="font-size: 16px; font-weight: 900; color: #0F172A;">AI Barista Config</div>
        <div style="font-size: 11px; color: #64748B;">Gemini 1.5 Flash • Persona Mas Nongki</div>
      </div>
      <span style="font-size: 11px; font-weight: 800; background: #DCFCE7; color: #166534; padding: 4px 10px; border-radius: 999px;">
        ● Online
      </span>
    </header>

    <div class="phone-content">
      <div class="scroll-body">
        <!-- Engine Health -->
        <div class="engine-health-card">
          <div>
            <div style="font-size: 11px; font-weight: 800; background: #FFFFFF; color: #004AAD; padding: 3px 8px; border-radius: 6px; display: inline-block;">GEMINI 1.5 FLASH ENGINE</div>
            <div style="font-size: 16px; font-weight: 900; margin-top: 6px;">Status Mesin Sehat</div>
            <div class="health-stats">
              <span class="h-stat">Latensi: <strong>1.1s</strong></span>
              <span class="h-stat">Kuota: <strong>84% Aman</strong></span>
            </div>
          </div>
          <div style="width: 50px; height: 50px; background: #FFFFFF; border-radius: 14px; display: flex; align-items: center; justify-content: center;">
            {mascot_blue}
          </div>
        </div>

        <!-- Temperature Slider -->
        <div class="ctrl-box-card">
          <div class="ctrl-row">
            <span class="ctrl-label">Kreativitas (Temperature)</span>
            <span class="ctrl-val-badge">0.7 (Ramah)</span>
          </div>
          <div class="slider-bar-mock">
            <div class="slider-fill"></div>
          </div>
          <div style="display: flex; justify-content: space-between; font-size: 10px; color: #64748B;">
            <span>0.0 (Presisi/Kaku)</span>
            <span>1.0 (Kreatif Penuh)</span>
          </div>
        </div>

        <!-- Prompt Textarea -->
        <div class="prompt-box-card">
          <div style="font-size: 13px; font-weight: 800; color: #0F172A;">Instruksi Persona (System Prompt)</div>
          <textarea class="prompt-textarea-m">Kamu adalah Mas Nongki, barista virtual ramah di kafe NONGKRONG.id. Tugasmu menyapa pelanggan berdasarkan nomor mejanya, menganalisis selera rasa, dan merekomendasikan 2-3 menu aktif dalam format JSON terstruktur...</textarea>
          <span style="font-size: 10px; color: #64748B;">Sinkronisasi otomatis dengan 15 menu database lokal MariaDB.</span>
        </div>

        <!-- Simulator -->
        <div class="sim-card">
          <div style="font-size: 12px; font-weight: 800; color: #004AAD;">🧪 UJI CEPAT SIMULATOR RESPON</div>
          <div class="sim-bubble">
            <strong>Prompt Tes:</strong> "Mau kopi dingin manis tapi gak enek..."<br>
            <strong>Hasil AI:</strong> Merekomendasikan <em>Caramel Macchiato (98%)</em> &amp; <em>Kopi Susu Aren (94%)</em>.
          </div>
        </div>

        <button class="btn-save-ai-cfg">
          Simpan Konfigurasi AI ➔
        </button>
      </div>

      <!-- Bottom Tab Bar -->
      <nav class="admin-tabbar">
        <a href="#" class="tab-btn">
          <span class="tab-icon">📊</span>
          <span>Ringkasan</span>
        </a>
        <a href="#" class="tab-btn">
          <span class="tab-icon">☕</span>
          <span>Menu & Stok</span>
        </a>
        <a href="#" class="tab-btn">
          <span class="tab-icon">🪑</span>
          <span>Meja Kafe</span>
        </a>
        <a href="#" class="tab-btn active">
          <span class="tab-icon">🤖</span>
          <span>AI Barista</span>
        </a>
      </nav>
    </div>

    <div class="home-indicator"></div>
  </div>
</body>
</html>"""

    with open(os.path.join(OUTPUT_DIR, "03_admin_mobile_ai_tuner.html"), "w", encoding="utf-8") as f:
        f.write(html)
    print("  ✓ Built 03_admin_mobile_ai_tuner.html")


# ==========================================
# 4. SCREEN 04: ADMIN MOBILE TABLE & FLOOR MONITOR
# ==========================================
def build_screen_04():
    html = f"""<!DOCTYPE html>
<html>
<head>
  <title>NONGKRONG.id - Admin Mobile Table & Floor Monitor</title>
  {MOBILE_HEAD}
  <style>
    .floor-header {{
      background: #FFFFFF;
      padding: 12px 20px 14px 20px;
      border-bottom: 1.5px solid #E2E8F0;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-shrink: 0;
    }}
    .scroll-body {{
      flex: 1;
      overflow-y: auto;
      padding: 16px;
      display: flex;
      flex-direction: column;
      gap: 14px;
    }}

    /* OCCUPANCY SUMMARY */
    .occ-summary-card {{
      background: #FFFFFF;
      border: 1.5px solid #E2E8F0;
      border-radius: 16px;
      padding: 16px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }}

    /* 2-COL TABLE CARDS */
    .table-grid-2 {{
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }}
    .tbl-card-m {{
      background: #FFFFFF;
      border: 1.5px solid #E2E8F0;
      border-radius: 16px;
      padding: 12px;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }}
    .tbl-card-m.occupied {{
      border-color: #004AAD;
      background: #FFFFFF;
    }}
    .tbl-card-m.cooking {{
      border-color: #F59E0B;
      background: #FFFBEB;
    }}
    .tbl-card-m.ready {{
      border-color: #10B981;
      background: #F0FDF4;
    }}
    .tbl-card-m.empty {{
      border-color: #CBD5E1;
      background: #F8FAFC;
    }}

    .tbl-head-row {{
      display: flex;
      justify-content: space-between;
      align-items: center;
    }}
    .tbl-num {{
      font-size: 15px;
      font-weight: 900;
      color: #0F172A;
    }}
    .tbl-code-badge {{
      font-size: 11px;
      font-weight: 800;
      font-family: 'JetBrains Mono', monospace;
      background: #EFF6FF;
      color: #004AAD;
      padding: 2px 6px;
      border-radius: 4px;
    }}
    .tbl-status-pill {{
      font-size: 10px;
      font-weight: 800;
      padding: 2px 8px;
      border-radius: 999px;
      width: fit-content;
    }}
    .pill-active {{ background: #DBEAFE; color: #1E40AF; }}
    .pill-cook {{ background: #FEF3C7; color: #92400E; }}
    .pill-ready {{ background: #DCFCE7; color: #166534; }}
    .pill-empty {{ background: #E2E8F0; color: #475569; }}

    .tbl-meta {{
      font-size: 11px;
      color: #64748B;
      line-height: 1.3;
    }}
    .btn-tbl-action {{
      width: 100%;
      padding: 6px;
      border-radius: 6px;
      border: 1px solid #CBD5E1;
      background: #FFFFFF;
      font-size: 10px;
      font-weight: 700;
      color: #334155;
      cursor: pointer;
      margin-top: 4px;
    }}
  </style>
</head>
<body>
  <div class="phone-frame">
    <div class="dynamic-island"></div>

    <div class="status-bar">
      <span>09:41</span>
      <div style="display: flex; gap: 6px; align-items: center;">
        <span style="font-size: 12px;">5G</span>
        <span style="font-size: 14px;">100%</span>
      </div>
    </div>

    <header class="floor-header">
      <div>
        <div style="font-size: 16px; font-weight: 900; color: #0F172A;">Monitoring Meja Kafe</div>
        <div style="font-size: 11px; color: #64748B;">Cabang Dipati Ukur • Live Status</div>
      </div>
      <button style="background: #004AAD; color: #FFFFFF; border: none; padding: 6px 12px; border-radius: 999px; font-size: 11px; font-weight: 800; cursor: pointer;">
        + Reset Sesi
      </button>
    </header>

    <div class="phone-content">
      <div class="scroll-body">
        <!-- Occupancy Bar -->
        <div class="occ-summary-card">
          <div>
            <div style="font-size: 12px; font-weight: 700; color: #64748B;">STATUS OKUPANSI LANTAI:</div>
            <div style="font-size: 18px; font-weight: 900; color: #0F172A; margin-top: 2px;">5 dari 6 Meja Terisi</div>
          </div>
          <span style="font-size: 16px; font-weight: 900; color: #004AAD; background: #EFF6FF; padding: 6px 14px; border-radius: 10px;">
            83% Sibuk
          </span>
        </div>

        <!-- 2-Col Table Cards -->
        <div class="table-grid-2">
          <!-- Meja 01 -->
          <div class="tbl-card-m occupied">
            <div class="tbl-head-row">
              <span class="tbl-num">Meja 01</span>
              <span class="tbl-code-badge">7K92A</span>
            </div>
            <span class="tbl-status-pill pill-active">● Sedang Makan</span>
            <div class="tbl-meta">
              Order: #ORD-001<br>
              Durasi: 45 Menit (2 Pax)
            </div>
            <button class="btn-tbl-action">Lihat Bill Meja</button>
          </div>

          <!-- Meja 02 -->
          <div class="tbl-card-m cooking">
            <div class="tbl-head-row">
              <span class="tbl-num">Meja 02</span>
              <span class="tbl-code-badge">3X81B</span>
            </div>
            <span class="tbl-status-pill pill-cook">⏳ Sedang Diramu</span>
            <div class="tbl-meta">
              Order: #ORD-003<br>
              Dapur Barista (4 Pax)
            </div>
            <button class="btn-tbl-action">Pantau Dapur</button>
          </div>

          <!-- Meja 03 -->
          <div class="tbl-card-m ready">
            <div class="tbl-head-row">
              <span class="tbl-num">Meja 03</span>
              <span class="tbl-code-badge">8Y24C</span>
            </div>
            <span class="tbl-status-pill pill-ready">✓ Siap Saji Pick-up</span>
            <div class="tbl-meta">
              Order: #ORD-002<br>
              3 Item Siap (2 Pax)
            </div>
            <button class="btn-tbl-action">Panggil Waiter</button>
          </div>

          <!-- Meja 04 -->
          <div class="tbl-card-m occupied">
            <div class="tbl-head-row">
              <span class="tbl-num">Meja 04</span>
              <span class="tbl-code-badge">6W15D</span>
            </div>
            <span class="tbl-status-pill pill-active">● Sedang Makan</span>
            <div class="tbl-meta">
              Order: #ORD-004<br>
              Durasi: 20 Menit (3 Pax)
            </div>
            <button class="btn-tbl-action">Lihat Bill Meja</button>
          </div>

          <!-- Meja 05 -->
          <div class="tbl-card-m occupied">
            <div class="tbl-head-row">
              <span class="tbl-num">Meja 05</span>
              <span class="tbl-code-badge">5V39E</span>
            </div>
            <span class="tbl-status-pill pill-active">● Sedang Makan</span>
            <div class="tbl-meta">
              Order: #ORD-005<br>
              Durasi: 12 Menit (1 Pax)
            </div>
            <button class="btn-tbl-action">Lihat Bill Meja</button>
          </div>

          <!-- Meja VIP-1 (Empty) -->
          <div class="tbl-card-m empty">
            <div class="tbl-head-row">
              <span class="tbl-num">VIP-1</span>
              <span class="tbl-code-badge">4U72F</span>
            </div>
            <span class="tbl-status-pill pill-empty">○ Kosong / Siap</span>
            <div class="tbl-meta">
              Ruang AC VIP<br>
              Kapasitas: 6 Orang
            </div>
            <button class="btn-tbl-action" style="background: #004AAD; color: #FFFFFF; border-color: #004AAD;">Buka Meja</button>
          </div>
        </div>
      </div>

      <!-- Bottom Tab Bar -->
      <nav class="admin-tabbar">
        <a href="#" class="tab-btn">
          <span class="tab-icon">📊</span>
          <span>Ringkasan</span>
        </a>
        <a href="#" class="tab-btn">
          <span class="tab-icon">☕</span>
          <span>Menu & Stok</span>
        </a>
        <a href="#" class="tab-btn active">
          <span class="tab-icon">🪑</span>
          <span>Meja Kafe</span>
        </a>
        <a href="#" class="tab-btn">
          <span class="tab-icon">🤖</span>
          <span>AI Barista</span>
        </a>
      </nav>
    </div>

    <div class="home-indicator"></div>
  </div>
</body>
</html>"""

    with open(os.path.join(OUTPUT_DIR, "04_admin_mobile_table_monitor.html"), "w", encoding="utf-8") as f:
        f.write(html)
    print("  ✓ Built 04_admin_mobile_table_monitor.html")


if __name__ == "__main__":
    print("Building Admin Mobile screens (Role 3 Mobile Viewport)...")
    build_screen_01()
    build_screen_02()
    build_screen_03()
    build_screen_04()
    print("All Admin Mobile screens successfully generated.")
