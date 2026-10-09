#!/usr/bin/env python3
"""
Role 3 Screen Generator (Admin & Pemilik)
Desktop Viewport (1440px x 900px)
Screens:
1. 10_admin_analytics.html (Executive Dashboard & Business Analytics)
2. 11_admin_menu.html (Menu Management & AI Taste Profile Tagging)
3. 12_admin_ai_config.html (AI Barista Config & Live Sandbox Simulator)
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
  }
  .window-titlebar {
    height: 42px;
    background: #F8FAFC;
    border-bottom: 1.5px solid #E2E8F0;
    display: flex;
    align-items: center;
    padding: 0 16px;
    justify-content: space-between;
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
  .url-bar {
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    border-radius: 8px;
    padding: 4px 18px;
    font-size: 12px;
    color: #475569;
    font-family: 'JetBrains Mono', monospace;
    width: 440px;
    text-align: center;
  }
  .admin-layout {
    display: flex;
    flex: 1;
    height: calc(100% - 42px);
    overflow: hidden;
  }
  .admin-sidebar {
    width: 250px;
    background: #0F172A;
    color: #FFFFFF;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 20px 16px;
  }
  .sidebar-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 11px 14px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 700;
    color: #94A3B8;
    text-decoration: none;
    margin-bottom: 4px;
    cursor: pointer;
  }
  .sidebar-link.active {
    background: #004AAD;
    color: #FFFFFF;
  }
  .admin-content {
    flex: 1;
    background: #F8FAFC;
    overflow-y: auto;
    padding: 24px 30px;
    display: flex;
    flex-direction: column;
    gap: 20px;
  }
  .kpi-card {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 18px;
    padding: 18px 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
  }
</style>
"""

def admin_sidebar_html(active_module="analytics"):
    return f"""
    <div class="admin-sidebar">
      <div>
        <!-- Logo -->
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 28px; padding-left: 8px;">
          {MASCOT_SVG.format(s=34, c="#FFFFFF", bg="#004AAD", fc="#FFFFFF")}
          <div>
            <div style="font-size: 16px; font-weight: 900; color: #FFFFFF; letter-spacing: -0.3px;">NONGKRONG<span style="color: #60A5FA;">.id</span></div>
            <div style="font-size: 10px; color: #94A3B8; font-weight: 600;">Admin Management Portal</div>
          </div>
        </div>

        <!-- Menu links -->
        <div class="sidebar-link {'active' if active_module=='analytics' else ''}">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
          Analitik Bisnis
        </div>

        <div class="sidebar-link {'active' if active_module=='menu' else ''}">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
          Manajemen Menu & Stok
        </div>

        <div class="sidebar-link {'active' if active_module=='ai' else ''}">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
          Konfigurasi AI Barista
        </div>

        <div class="sidebar-link">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
          Laporan Shift & Kasir
        </div>

        <div class="sidebar-link">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
          Pengaturan Sistem
        </div>
      </div>

      <!-- User footer -->
      <div style="border-top: 1px solid #1E293B; padding-top: 14px; display: flex; align-items: center; gap: 10px;">
        <div style="width: 38px; height: 38px; border-radius: 50%; background: #004AAD; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px;">AL</div>
        <div>
          <div style="font-size: 13px; font-weight: 800; color: #FFFFFF;">Allam (Admin)</div>
          <div style="font-size: 11px; color: #64748B;">Super Administrator</div>
        </div>
      </div>
    </div>
    """

# ==========================================
# 10. EXECUTIVE ANALYTICS DASHBOARD (10_admin_analytics.html)
# ==========================================
def build_screen_10():
    html = f"""<!DOCTYPE html>
<html>
<head>
<title>10 - Admin Executive Analytics</title>
{DESKTOP_HEAD}
</head>
<body>
  <div class="desktop-window">
    <div class="window-titlebar">
      <div class="window-dots">
        <div class="window-dot dot-red"></div>
        <div class="window-dot dot-yellow"></div>
        <div class="window-dot dot-green"></div>
      </div>
      <div class="url-bar">https://nongkrong.id/admin/dashboard/analytics</div>
      <div style="font-size: 11px; font-weight: 700; color: #64748B;">Executive Analytics</div>
    </div>

    <div class="admin-layout">
      {admin_sidebar_html(active_module="analytics")}

      <!-- Content -->
      <div class="admin-content">
        <!-- Top bar -->
        <div style="display: flex; justify-content: space-between; align-items: center;">
          <div>
            <h1 style="font-size: 22px; font-weight: 900; color: #0F172A;">Analitik & Performa Bisnis Kafe</h1>
            <p style="font-size: 12px; color: #64748B;">Ringkasan metrik penjualan, tren jam sibuk, dan konversi AI Barista</p>
          </div>
          <div style="display: flex; gap: 10px;">
            <button style="background: #FFFFFF; border: 1.5px solid #004AAD; color: #004AAD; font-size: 12px; font-weight: 800; padding: 10px 16px; border-radius: 12px; cursor: pointer;">
              📄 Ekspor PDF Laporan
            </button>
            <button style="background: #004AAD; border: none; color: #FFFFFF; font-size: 12px; font-weight: 800; padding: 10px 16px; border-radius: 12px; cursor: pointer;">
              📊 Ekspor Microsoft Excel
            </button>
          </div>
        </div>

        <!-- 4 KPI Cards (ANSI Flowchart: Query Agregasi Penjualan) -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
          <div class="kpi-card" style="border-left: 5px solid #004AAD;">
            <div style="font-size: 12px; color: #64748B; font-weight: 700; margin-bottom: 6px;">Total Omzet Hari Ini</div>
            <div style="font-size: 24px; font-weight: 900; color: #0F172A;">Rp 4.850.000</div>
            <div style="display: flex; align-items: center; gap: 4px; font-size: 11px; color: #10B981; font-weight: 700; margin-top: 6px;">
              <span>▲ +18.4%</span>
              <span style="color: #94A3B8; font-weight: 500;">vs rata-rata harian</span>
            </div>
          </div>

          <div class="kpi-card" style="border-left: 5px solid #10B981;">
            <div style="font-size: 12px; color: #64748B; font-weight: 700; margin-bottom: 6px;">Jumlah Transaksi</div>
            <div style="font-size: 24px; font-weight: 900; color: #0F172A;">142 Order</div>
            <div style="font-size: 11px; color: #64748B; margin-top: 6px;">
              QR: <b style="color: #004AAD;">108</b> • Walk-In: <b style="color: #0F172A;">34</b>
            </div>
          </div>

          <div class="kpi-card" style="border-left: 5px solid #F59E0B;">
            <div style="font-size: 12px; color: #64748B; font-weight: 700; margin-bottom: 6px;">Total Item Terjual</div>
            <div style="font-size: 24px; font-weight: 900; color: #0F172A;">318 Item</div>
            <div style="font-size: 11px; color: #64748B; margin-top: 6px;">
              Rata-rata 2.24 item / meja
            </div>
          </div>

          <div class="kpi-card" style="border-left: 5px solid #8B5CF6;">
            <div style="font-size: 12px; color: #64748B; font-weight: 700; margin-bottom: 6px;">Konversi AI Barista</div>
            <div style="font-size: 24px; font-weight: 900; color: #004AAD;">34.8%</div>
            <div style="font-size: 11px; color: #64748B; margin-top: 6px;">
              49 Checkout dari rekomendasi
            </div>
          </div>
        </div>

        <!-- Charts Section (ApexCharts visual representation) -->
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 18px;">
          <!-- Left: Sales per hour -->
          <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 18px; padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
              <div>
                <h3 style="font-size: 15px; font-weight: 800; color: #0F172A;">Tren Omzet & Jam Sibuk (Peak Hours)</h3>
                <p style="font-size: 11px; color: #64748B;">Pantauan transaksi jam 08:00 s/d 22:00 WIB</p>
              </div>
              <span style="font-size: 11px; font-weight: 700; color: #004AAD; background: #EFF6FF; padding: 4px 10px; border-radius: 8px;">Hari Ini</span>
            </div>

            <!-- Stylized Flat Bar Graph -->
            <div style="height: 190px; display: flex; align-items: flex-end; gap: 14px; padding-top: 10px; border-bottom: 1.5px solid #E2E8F0; padding-bottom: 6px;">
              <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px;">
                <div style="background: #93C5FD; width: 100%; height: 45px; border-radius: 6px 6px 0 0;"></div>
                <span style="font-size: 10px; color: #64748B; font-weight: 600;">08:00</span>
              </div>
              <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px;">
                <div style="background: #93C5FD; width: 100%; height: 75px; border-radius: 6px 6px 0 0;"></div>
                <span style="font-size: 10px; color: #64748B; font-weight: 600;">10:00</span>
              </div>
              <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px;">
                <div style="background: #004AAD; width: 100%; height: 160px; border-radius: 6px 6px 0 0;"></div>
                <span style="font-size: 10px; color: #004AAD; font-weight: 800;">12:00</span>
              </div>
              <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px;">
                <div style="background: #93C5FD; width: 100%; height: 110px; border-radius: 6px 6px 0 0;"></div>
                <span style="font-size: 10px; color: #64748B; font-weight: 600;">14:00</span>
              </div>
              <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px;">
                <div style="background: #93C5FD; width: 100%; height: 85px; border-radius: 6px 6px 0 0;"></div>
                <span style="font-size: 10px; color: #64748B; font-weight: 600;">16:00</span>
              </div>
              <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px;">
                <div style="background: #004AAD; width: 100%; height: 175px; border-radius: 6px 6px 0 0;"></div>
                <span style="font-size: 10px; color: #004AAD; font-weight: 800;">19:00</span>
              </div>
              <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px;">
                <div style="background: #93C5FD; width: 100%; height: 130px; border-radius: 6px 6px 0 0;"></div>
                <span style="font-size: 10px; color: #64748B; font-weight: 600;">21:00</span>
              </div>
            </div>
            <div style="font-size: 11px; color: #64748B; margin-top: 10px;">
              Puncak order terjadi pada <b>Jam Makan Siang (12:00 - 13:30)</b> dan <b>Nongkrong Malam (19:00 - 21:00)</b>.
            </div>
          </div>

          <!-- Right: Top 5 Best Sellers -->
          <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 18px; padding: 20px;">
            <h3 style="font-size: 15px; font-weight: 800; color: #0F172A; margin-bottom: 14px;">5 Menu Terlaris (Ranking)</h3>
            <div style="display: flex; flex-direction: column; gap: 10px;">
              <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                  <span style="width: 20px; height: 20px; background: #004AAD; color: #FFFFFF; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 10px;">1</span>
                  <b>Kopi Susu Aren</b>
                </div>
                <span style="color: #004AAD; font-weight: 800;">78 Porsi</span>
              </div>
              <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                  <span style="width: 20px; height: 20px; background: #004AAD; color: #FFFFFF; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 10px;">2</span>
                  <b>Caramel Macchiato</b>
                </div>
                <span style="color: #004AAD; font-weight: 800;">52 Porsi</span>
              </div>
              <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                  <span style="width: 20px; height: 20px; background: #CBD5E1; color: #0F172A; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 10px;">3</span>
                  <b>Butter Croissant</b>
                </div>
                <span style="color: #004AAD; font-weight: 800;">41 Porsi</span>
              </div>
              <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                  <span style="width: 20px; height: 20px; background: #CBD5E1; color: #0F172A; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 10px;">4</span>
                  <b>Matcha Latte Iced</b>
                </div>
                <span style="color: #004AAD; font-weight: 800;">34 Porsi</span>
              </div>
              <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                  <span style="width: 20px; height: 20px; background: #CBD5E1; color: #0F172A; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 10px;">5</span>
                  <b>Americano Cold Brew</b>
                </div>
                <span style="color: #004AAD; font-weight: 800;">29 Porsi</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>"""
    with open(os.path.join(OUTPUT_DIR, "10_admin_analytics.html"), "w") as f:
        f.write(html)


# ==========================================
# 11. MENU MANAGEMENT & TASTE PROFILE TAGS (11_admin_menu.html)
# ==========================================
def build_screen_11():
    html = f"""<!DOCTYPE html>
<html>
<head>
<title>11 - Menu Management & Taste Tags</title>
{DESKTOP_HEAD}
<style>
  .menu-table {{
    width: 100%;
    border-collapse: collapse;
    background: #FFFFFF;
    border-radius: 16px;
    overflow: hidden;
    border: 1.5px solid #E2E8F0;
  }}
  .menu-table th {{
    background: #F8FAFC;
    padding: 14px 16px;
    font-size: 12px;
    font-weight: 800;
    color: #475569;
    text-align: left;
    border-bottom: 2px solid #E2E8F0;
  }}
  .menu-table td {{
    padding: 14px 16px;
    font-size: 13px;
    color: #0F172A;
    border-bottom: 1px solid #E2E8F0;
  }}
  .tag-chip {{
    display: inline-block;
    font-size: 10px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 6px;
    margin-right: 4px;
  }}
  .tag-sweet {{ background: #EFF6FF; color: #004AAD; border: 1px solid #BFDBFE; }}
  .tag-nutty {{ background: #FEF3C7; color: #92400E; border: 1px solid #FDE68A; }}
  .tag-creamy {{ background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; }}
  .tag-bitter {{ background: #F1F5F9; color: #334155; border: 1px solid #CBD5E1; }}
  .toggle-switch {{
    width: 38px;
    height: 20px;
    background: #004AAD;
    border-radius: 20px;
    position: relative;
    cursor: pointer;
  }}
  .toggle-knob {{
    width: 16px;
    height: 16px;
    background: #FFFFFF;
    border-radius: 50%;
    position: absolute;
    top: 2px;
    right: 2px;
  }}
  .modal-form-card {{
    background: #FFFFFF;
    border: 2px solid #004AAD;
    border-radius: 18px;
    padding: 20px;
    box-shadow: 0 10px 25px rgba(0, 74, 173, 0.12);
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
      <div class="url-bar">https://nongkrong.id/admin/menu-management</div>
      <div style="font-size: 11px; font-weight: 700; color: #64748B;">Manajemen Menu & AI Tagging</div>
    </div>

    <div class="admin-layout">
      {admin_sidebar_html(active_module="menu")}

      <!-- Content -->
      <div class="admin-content">
        <!-- Top bar -->
        <div style="display: flex; justify-content: space-between; align-items: center;">
          <div>
            <h1 style="font-size: 22px; font-weight: 900; color: #0F172A;">Katalog Menu & AI Taste Profile Tagging</h1>
            <p style="font-size: 12px; color: #64748B;">Kelola harga, status ketersediaan, serta tag profil rasa untuk algoritma AI Barista</p>
          </div>
          <button style="background: #004AAD; color: #FFFFFF; border: none; font-size: 13px; font-weight: 800; padding: 10px 18px; border-radius: 12px; cursor: pointer;">
            + Tambah Menu Baru
          </button>
        </div>

        <!-- Split View: Table list on left (60%), Edit Form Drawer on right (40%) -->
        <div style="display: grid; grid-template-columns: 1.6fr 1.1fr; gap: 20px;">
          <!-- Left Table -->
          <div>
            <table class="menu-table">
              <thead>
                <tr>
                  <th>Nama Menu</th>
                  <th>Kategori</th>
                  <th>Harga</th>
                  <th>Taste Tags (AI)</th>
                  <th>Stok</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr style="background: #EFF6FF;">
                  <td>
                    <b>Caramel Macchiato</b>
                    <div style="font-size: 10px; color: #64748B;">ID: #MENU-002</div>
                  </td>
                  <td>Espresso</td>
                  <td><b>Rp 28.000</b></td>
                  <td>
                    <span class="tag-chip tag-sweet">Sweet</span>
                    <span class="tag-chip tag-nutty">Nutty</span>
                    <span class="tag-chip tag-creamy">Creamy</span>
                  </td>
                  <td>
                    <div class="toggle-switch"><div class="toggle-knob"></div></div>
                  </td>
                  <td>
                    <span style="color: #004AAD; font-weight: 800; cursor: pointer;">Edit</span>
                  </td>
                </tr>

                <tr>
                  <td>
                    <b>Kopi Susu Aren</b>
                    <div style="font-size: 10px; color: #64748B;">ID: #MENU-001</div>
                  </td>
                  <td>Signature</td>
                  <td><b>Rp 22.000</b></td>
                  <td>
                    <span class="tag-chip tag-sweet">Sweet</span>
                    <span class="tag-chip tag-creamy">Creamy</span>
                  </td>
                  <td>
                    <div class="toggle-switch"><div class="toggle-knob"></div></div>
                  </td>
                  <td>
                    <span style="color: #004AAD; font-weight: 800; cursor: pointer;">Edit</span>
                  </td>
                </tr>

                <tr>
                  <td>
                    <b>Americano Cold Brew</b>
                    <div style="font-size: 10px; color: #64748B;">ID: #MENU-003</div>
                  </td>
                  <td>Espresso</td>
                  <td><b>Rp 20.000</b></td>
                  <td>
                    <span class="tag-chip tag-bitter">Bitter</span>
                    <span class="tag-chip tag-bitter">Fresh</span>
                  </td>
                  <td>
                    <div class="toggle-switch"><div class="toggle-knob"></div></div>
                  </td>
                  <td>
                    <span style="color: #004AAD; font-weight: 800; cursor: pointer;">Edit</span>
                  </td>
                </tr>

                <tr>
                  <td>
                    <b>Butter Croissant</b>
                    <div style="font-size: 10px; color: #64748B;">ID: #MENU-004</div>
                  </td>
                  <td>Pastry</td>
                  <td><b>Rp 18.000</b></td>
                  <td>
                    <span class="tag-chip tag-nutty">Buttery</span>
                    <span class="tag-chip tag-nutty">Flaky</span>
                  </td>
                  <td>
                    <div class="toggle-switch"><div class="toggle-knob"></div></div>
                  </td>
                  <td>
                    <span style="color: #004AAD; font-weight: 800; cursor: pointer;">Edit</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Right: Edit / Add Form (Branch 1 Flowchart) -->
          <div class="modal-form-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; padding-bottom: 8px; border-bottom: 1.5px solid #E2E8F0;">
              <div>
                <h3 style="font-size: 15px; font-weight: 800; color: #0F172A;">Edit Menu: Caramel Macchiato</h3>
                <span style="font-size: 11px; color: #64748B;">Sinkron otomatis ke tabel MariaDB 'menus'</span>
              </div>
              <span style="background: #DCFCE7; color: #166534; font-size: 11px; font-weight: 800; padding: 3px 8px; border-radius: 8px;">Tersedia ✓</span>
            </div>

            <!-- Form inputs -->
            <div style="display: flex; flex-direction: column; gap: 12px;">
              <div>
                <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Nama Produk</label>
                <input type="text" value="Caramel Macchiato" style="width: 100%; border: 1.5px solid #CBD5E1; border-radius: 10px; padding: 8px 12px; font-size: 13px;">
              </div>

              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <div>
                  <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Kategori</label>
                  <select style="width: 100%; border: 1.5px solid #CBD5E1; border-radius: 10px; padding: 8px 12px; font-size: 13px;">
                    <option>Espresso Based</option>
                    <option>Signature Coffee</option>
                  </select>
                </div>
                <div>
                  <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Harga (IDR)</label>
                  <input type="text" value="28000" style="width: 100%; border: 1.5px solid #CBD5E1; border-radius: 10px; padding: 8px 12px; font-size: 13px;">
                </div>
              </div>

              <!-- AI Taste Profile Tags Field -->
              <div style="background: #F8FAFC; border: 1.5px dashed #004AAD; border-radius: 12px; padding: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                  <label style="font-size: 12px; font-weight: 800; color: #004AAD;">Taste Profile Tag (JSON Attribute)</label>
                  <span style="font-size: 10px; font-weight: 700; background: #004AAD; color: #FFFFFF; padding: 2px 6px; border-radius: 6px;">Gemini Ready</span>
                </div>
                <p style="font-size: 11px; color: #64748B; margin-bottom: 8px;">
                  Tag ini dicocokkan oleh prompt Gemini API saat pelanggan bertanya ke Barista AI.
                </p>
                <!-- Multi-tag selector -->
                <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                  <span class="tag-chip tag-sweet" style="font-size: 11px; padding: 4px 10px;">Sweet ✕</span>
                  <span class="tag-chip tag-nutty" style="font-size: 11px; padding: 4px 10px;">Nutty ✕</span>
                  <span class="tag-chip tag-creamy" style="font-size: 11px; padding: 4px 10px;">Creamy ✕</span>
                  <button style="background: #FFFFFF; border: 1px dashed #CBD5E1; padding: 4px 8px; border-radius: 6px; font-size: 11px; cursor: pointer;">+ Tambah Tag</button>
                </div>
              </div>

              <!-- Action button -->
              <button style="background: #004AAD; color: #FFFFFF; font-size: 14px; font-weight: 800; padding: 12px; border-radius: 12px; border: none; cursor: pointer; margin-top: 4px;">
                Simpan & Sinkronkan ke Katalog
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>"""
    with open(os.path.join(OUTPUT_DIR, "11_admin_menu.html"), "w") as f:
        f.write(html)


# ==========================================
# 12. AI BARISTA CONFIG & SANDBOX SIMULATOR (12_admin_ai_config.html)
# ==========================================
def build_screen_12():
    html = f"""<!DOCTYPE html>
<html>
<head>
<title>12 - AI Barista Config & Simulator</title>
{DESKTOP_HEAD}
<style>
  .prompt-box {{
    width: 100%;
    height: 140px;
    background: #F8FAFC;
    border: 1.5px solid #CBD5E1;
    border-radius: 12px;
    padding: 12px;
    font-size: 12px;
    font-family: 'JetBrains Mono', monospace;
    color: #0F172A;
    line-height: 1.5;
    resize: none;
  }}
  .json-panel {{
    background: #0F172A;
    border-radius: 12px;
    padding: 14px;
    color: #38BDF8;
    font-family: 'JetBrains Mono', monospace;
    font-size: 11px;
    line-height: 1.5;
    overflow-x: auto;
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
      <div class="url-bar">https://nongkrong.id/admin/ai-barista-settings</div>
      <div style="font-size: 11px; font-weight: 700; color: #64748B;">Konfigurasi Gemini AI Barista</div>
    </div>

    <div class="admin-layout">
      {admin_sidebar_html(active_module="ai")}

      <!-- Content -->
      <div class="admin-content">
        <!-- Top bar -->
        <div style="display: flex; justify-content: space-between; align-items: center;">
          <div>
            <h1 style="font-size: 22px; font-weight: 900; color: #0F172A;">Konfigurasi AI Barista & Sandbox Simulator</h1>
            <p style="font-size: 12px; color: #64748B;">Pengaturan parameter Google Gemini API, Persona Barista, dan pengujian prompt realtime</p>
          </div>
          <span style="background: #EFF6FF; border: 1.5px solid #BFDBFE; color: #004AAD; font-size: 12px; font-weight: 800; padding: 6px 14px; border-radius: 20px;">
            Gemini 1.5 Flash Connected ✓
          </span>
        </div>

        <!-- 2 Column Layout (Left: Settings, Right: Sandbox Simulator) -->
        <div style="display: grid; grid-template-columns: 1.1fr 1fr; gap: 20px;">
          <!-- Left: API & Persona Settings -->
          <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 18px; padding: 20px; display: flex; flex-direction: column; gap: 14px;">
            <h3 style="font-size: 15px; font-weight: 800; color: #0F172A; border-bottom: 1.5px solid #E2E8F0; padding-bottom: 8px;">
              Parameter Model & System Persona
            </h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
              <div>
                <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">AI Model Endpoint</label>
                <input type="text" value="gemini-1.5-flash-latest" style="width: 100%; border: 1.5px solid #CBD5E1; border-radius: 10px; padding: 8px 12px; font-size: 12px; font-family: 'JetBrains Mono', monospace;">
              </div>
              <div>
                <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Temperature / Kreativitas</label>
                <input type="text" value="0.7 (Rekomendasi Seimbang)" style="width: 100%; border: 1.5px solid #CBD5E1; border-radius: 10px; padding: 8px 12px; font-size: 12px;">
              </div>
            </div>

            <div>
              <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">System Prompt Persona Barista</label>
              <textarea class="prompt-box">Kamu adalah Barista Virtual ramah dari NONGKRONG.id bernama "Mas Nongki". 
Tugasmu adalah menganalisis mood dan preferensi rasa pelanggan, lalu merekomendasikan 2-3 menu yang paling cocok berdasarkan tag rasa menu database.
Format balasan WAJIB Structured JSON: {{ "recommended_menu_ids": [id], "message": "...", "reason": "..." }}</textarea>
            </div>

            <!-- Token & Conversion Stats -->
            <div style="background: #F8FAFC; border: 1px solid #CBD5E1; border-radius: 12px; padding: 12px; display: flex; justify-content: space-between;">
              <div>
                <div style="font-size: 10px; color: #64748B; font-weight: 700;">Rata-rata Respon API</div>
                <div style="font-size: 16px; font-weight: 900; color: #004AAD;">1.14 Detik</div>
              </div>
              <div>
                <div style="font-size: 10px; color: #64748B; font-weight: 700;">Token per Request</div>
                <div style="font-size: 16px; font-weight: 900; color: #0F172A;">~280 Tokens</div>
              </div>
              <div>
                <div style="font-size: 10px; color: #64748B; font-weight: 700;">Success Rate</div>
                <div style="font-size: 16px; font-weight: 900; color: #10B981;">99.8%</div>
              </div>
            </div>

            <button style="background: #004AAD; color: #FFFFFF; font-size: 14px; font-weight: 800; padding: 12px; border-radius: 12px; border: none; cursor: pointer;">
              Simpan Konfigurasi AI Barista
            </button>
          </div>

          <!-- Right: Live Simulation Sandbox (Branch 2 Flowchart) -->
          <div style="background: #FFFFFF; border: 2px solid #004AAD; border-radius: 18px; padding: 20px; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1.5px solid #E2E8F0;">
                <div style="display: flex; align-items: center; gap: 8px;">
                  {MASCOT_SVG.format(s=28, c="#004AAD", bg="#FFFFFF", fc="#004AAD")}
                  <h3 style="font-size: 15px; font-weight: 800; color: #004AAD;">Sandbox Test Prompt</h3>
                </div>
                <span style="font-size: 11px; background: #DCFCE7; color: #166534; font-weight: 800; padding: 2px 8px; border-radius: 8px;">Live Debug</span>
              </div>

              <!-- Test input -->
              <div style="margin-bottom: 10px;">
                <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Uji Pesan Pengguna (User Input)</label>
                <div style="display: flex; gap: 6px;">
                  <input type="text" value="Mau kopi dingin yang manis gurih dan nggak terlalu pait" style="flex: 1; border: 1.5px solid #CBD5E1; border-radius: 8px; padding: 8px 12px; font-size: 12px;">
                  <button style="background: #004AAD; color: #FFFFFF; border: none; font-size: 12px; font-weight: 800; padding: 8px 14px; border-radius: 8px; cursor: pointer;">Run Test</button>
                </div>
              </div>

              <!-- Output JSON Structured Result -->
              <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Hasil Response Gemini (Structured JSON)</label>
              <div class="json-panel">
&#123;
  <span style="color: #F472B6;">"recommended_menu_ids"</span>: [2, 1],
  <span style="color: #F472B6;">"message"</span>: <span style="color: #FDE047;">"Cobain Caramel Macchiato Iced atau Kopi Susu Aren kami! Manis karamelnya berpadu gurih pas buat harimu."</span>,
  <span style="color: #F472B6;">"match_tags"</span>: [<span style="color: #A7F3D0;">"Sweet"</span>, <span style="color: #A7F3D0;">"Caramel"</span>, <span style="color: #A7F3D0;">"Creamy"</span>],
  <span style="color: #F472B6;">"confidence_score"</span>: 0.98
&#125;
              </div>
            </div>

            <!-- Bottom Live preview UI card -->
            <div style="background: #EFF6FF; border: 1.5px solid #BFDBFE; border-radius: 12px; padding: 12px; margin-top: 10px;">
              <div style="font-size: 11px; font-weight: 800; color: #004AAD; margin-bottom: 2px;">Preview Tampilan di Mobile Pelanggan:</div>
              <div style="font-size: 12px; color: #1E293B;">
                "Cobain Caramel Macchiato Iced atau Kopi Susu Aren kami! Manis karamelnya berpadu gurih pas buat harimu."
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>"""
    with open(os.path.join(OUTPUT_DIR, "12_admin_ai_config.html"), "w") as f:
        f.write(html)

if __name__ == "__main__":
    print("Building Role 3 HTML screens...")
    build_screen_10()
    build_screen_11()
    build_screen_12()
    print("Role 3 HTML screens complete.")
