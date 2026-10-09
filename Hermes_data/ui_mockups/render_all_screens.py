#!/usr/bin/env python3
"""
Master Renderer for NONGKRONG.id UI Mockups
Renders all 12 HTML templates into publication-standard PNG images using headless Chrome.
"""

import os
import subprocess
import time

OUTPUT_DIR = "/home/puutraa/Desktop/nongkrong_id/ui_mockups"

# 1. Run generators
print("[1/3] Generating HTML templates...")
subprocess.run(["python3", "/home/puutraa/Desktop/nongkrong_id/ui_mockups/build_role1_screens.py"], check=True)
subprocess.run(["python3", "/home/puutraa/Desktop/nongkrong_id/ui_mockups/build_role2_screens.py"], check=True)
subprocess.run(["python3", "/home/puutraa/Desktop/nongkrong_id/ui_mockups/build_role3_screens.py"], check=True)

# List of screens to render
SCREENS = [
    # Mobile Screens (Role 1)
    {"file": "01_splash_screen.html", "png": "01_splash_screen.png", "w": 500, "h": 1040},
    {"file": "02_portal_welcome.html", "png": "02_portal_welcome.png", "w": 500, "h": 1040},
    {"file": "03_table_checkin.html", "png": "03_table_checkin.png", "w": 500, "h": 1040},
    {"file": "04_customer_catalog.html", "png": "04_customer_catalog.png", "w": 500, "h": 1040},
    {"file": "05_ai_barista_modal.html", "png": "05_ai_barista_modal.png", "w": 500, "h": 1040},
    {"file": "06_cart_checkout.html", "png": "06_cart_checkout.png", "w": 500, "h": 1040},
    {"file": "07_order_tracking.html", "png": "07_order_tracking.png", "w": 500, "h": 1040},
    # Desktop Screens (Role 2)
    {"file": "08_pos_kanban.html", "png": "08_pos_kanban.png", "w": 1520, "h": 980},
    {"file": "09_pos_walkin.html", "png": "09_pos_walkin.png", "w": 1520, "h": 980},
    # Desktop Screens (Role 3)
    {"file": "10_admin_analytics.html", "png": "10_admin_analytics.png", "w": 1520, "h": 980},
    {"file": "11_admin_menu.html", "png": "11_admin_menu.png", "w": 1520, "h": 980},
    {"file": "12_admin_ai_config.html", "png": "12_admin_ai_config.png", "w": 1520, "h": 980},
]

print("[2/3] Rendering PNG screenshots with headless Google Chrome...")
success_count = 0
for sc in SCREENS:
    html_path = os.path.join(OUTPUT_DIR, sc["file"])
    png_path = os.path.join(OUTPUT_DIR, sc["png"])
    file_url = f"file://{html_path}"
    
    cmd = [
        "google-chrome",
        "--headless",
        "--disable-gpu",
        "--hide-scrollbars",
        f"--window-size={sc['w']},{sc['h']}",
        f"--screenshot={png_path}",
        file_url
    ]
    res = subprocess.run(cmd, capture_output=True, text=True)
    if os.path.exists(png_path) and os.path.getsize(png_path) > 1000:
        size_kb = os.path.getsize(png_path) // 1024
        print(f"  ✓ Rendered {sc['png']} ({size_kb} KB)")
        success_count += 1
    else:
        print(f"  ✗ Failed to render {sc['png']}: {res.stderr}")

print(f"[3/3] Successfully rendered {success_count}/{len(SCREENS)} screens!")
