#!/usr/bin/env python3
"""
Renderer for Role 3 Admin Mobile Screens
Generates high-resolution PNG images (smartphone viewport) using headless Google Chrome.
"""

import os
import subprocess

OUTPUT_DIR = "/home/puutraa/Desktop/nongkrong_id/ui_mockups"

SCREENS = [
    ("01_admin_mobile_dashboard.html", "01_admin_mobile_dashboard.png"),
    ("02_admin_mobile_menus.html", "02_admin_mobile_menus.png"),
    ("03_admin_mobile_ai_tuner.html", "03_admin_mobile_ai_tuner.png"),
    ("04_admin_mobile_table_monitor.html", "04_admin_mobile_table_monitor.png"),
]

def main():
    print("[1/2] Building Admin Mobile HTML screens...")
    subprocess.run(["python3", os.path.join(OUTPUT_DIR, "build_admin_mobile_screens.py")], check=True)

    print("[2/2] Rendering PNG screenshots with headless Google Chrome...")
    for html_file, png_file in SCREENS:
        html_path = os.path.join(OUTPUT_DIR, html_file)
        png_path = os.path.join(OUTPUT_DIR, png_file)
        cmd = [
            "google-chrome",
            "--headless=new",
            "--disable-gpu",
            "--hide-scrollbars",
            "--window-size=500,1040",
            f"--screenshot={png_path}",
            f"file://{html_path}"
        ]
        res = subprocess.run(cmd, capture_output=True, text=True)
        if res.returncode == 0 and os.path.exists(png_path):
            size_kb = os.path.getsize(png_path) // 1024
            print(f"  ✓ Rendered {png_file} ({size_kb} KB)")
        else:
            print(f"  ✗ Failed to render {png_file}: {res.stderr}")

    print("Admin Mobile mockup rendering complete!")

if __name__ == "__main__":
    main()
