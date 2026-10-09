#!/usr/bin/env python3
"""
Renderer for Customer Web Screens
Generates PNG images (1440x900 viewport, 1500x960 capture) using Google Chrome headless.
"""

import os
import subprocess

OUTPUT_DIR = "/home/puutraa/Desktop/nongkrong_id/ui_mockups"

SCREENS = [
    ("01_web_portal_welcome.html", "01_web_portal_welcome.png"),
    ("02_web_table_checkin.html", "02_web_table_checkin.png"),
    ("03_web_customer_catalog.html", "03_web_customer_catalog.png"),
    ("04_web_ai_barista.html", "04_web_ai_barista.png"),
    ("05_web_cart_checkout.html", "05_web_cart_checkout.png"),
    ("06_web_order_tracking.html", "06_web_order_tracking.png"),
]

def main():
    print("[1/2] Generating Customer Web HTML screens...")
    subprocess.run(["python3", os.path.join(OUTPUT_DIR, "build_customer_web_screens.py")], check=True)

    print("[2/2] Rendering PNG screenshots with headless Google Chrome...")
    for html_file, png_file in SCREENS:
        html_path = os.path.join(OUTPUT_DIR, html_file)
        png_path = os.path.join(OUTPUT_DIR, png_file)
        cmd = [
            "google-chrome",
            "--headless=new",
            "--disable-gpu",
            "--hide-scrollbars",
            "--force-device-scale-factor=1",
            "--window-size=1500,960",
            f"--screenshot={png_path}",
            f"file://{html_path}"
        ]
        res = subprocess.run(cmd, capture_output=True, text=True)
        if res.returncode == 0 and os.path.exists(png_path):
            size_kb = os.path.getsize(png_path) // 1024
            print(f"  ✓ Rendered {png_file} ({size_kb} KB)")
        else:
            print(f"  ✗ Failed to render {png_file}: {res.stderr}")

    print("Customer Web mockup generation complete!")

if __name__ == "__main__":
    main()
