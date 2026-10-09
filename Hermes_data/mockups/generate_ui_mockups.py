#!/usr/bin/env python3
import os
import subprocess

OUT_DIR = "/home/puutraa/Desktop/nongkrong_id/mockups"
ASSETS_DIR = "/home/puutraa/Desktop/nongkrong_id/public/assets/img"
os.makedirs(OUT_DIR, exist_ok=True)

# Helper function to read asset as inline svg or base64 data
def get_file_content(path):
    with open(path, "r", encoding="utf-8") as f:
        return f.read()

logo_blue_svg = get_file_content(os.path.join(ASSETS_DIR, "NongkrongId-blue.svg"))
star_svg = get_file_content(os.path.join(ASSETS_DIR, "Star-ic.svg"))
mascot_svg = get_file_content(os.path.join(ASSETS_DIR, "icont_noks.svg"))

print("Assets loaded successfully:")
print("Logo size:", len(logo_blue_svg))
print("Star size:", len(star_svg))
print("Mascot size:", len(mascot_svg))
