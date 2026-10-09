#!/usr/bin/env python3
"""
Master UI Mockup Generator for NONGKRONG.id Smart Cafe POS
Generates 12 high-fidelity, publication-standard screens adhering to ANSI/ISO 5807 flowcharts.

Aesthetic Guidelines (Strict User Preferences):
- Theme: NONGKRONG.id Official Brand
- Palette: Solid Royal Blue (#004AAD), Pure White (#FFFFFF), Clean Slate (#0F172A, #1E293B), Muted (#64748B, #94A3B8)
- ZERO GRADIENTS. All colors are flat and solid.
- Decorative solid blue 5-pointed stars peeking in from edges.
- Mascot: Whimsical smiling coffee cup with steam lines and blush.
- Mobile screens framed in modern smartphone viewport.
- Desktop screens framed in modern SaaS app window viewport.
"""

import os
import subprocess
import time

OUTPUT_DIR = "/home/puutraa/Desktop/nongkrong_id/ui_mockups"
ASSETS_DIR = "/home/puutraa/Desktop/nongkrong_id/public/assets"
os.makedirs(OUTPUT_DIR, exist_ok=True)

# Common SVGs & Components
STAR_PATH = "M49.9297 0L61.7167 35.9311H99.8602L69.0014 58.1378L80.7884 94.0689L49.9297 71.8622L19.071 94.0689L30.8579 58.1378L-0.000778198 35.9311H38.1427L49.9297 0Z"

def star_svg(size=60, color="#004AAD", rotate=0):
    return f"""<svg width="{size}" height="{int(size*0.95)}" viewBox="0 0 100 95" fill="none" style="transform: rotate({rotate}deg);" xmlns="http://www.w3.org/2000/svg">
<path d="{STAR_PATH}" fill="{color}"/>
</svg>"""

def mascot_svg(size=44, color="#004AAD", bg="#FFFFFF", fill_face="#004AAD"):
    return f"""<svg width="{size}" height="{size}" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
  <!-- Steam lines -->
  <path d="M40 18 L36 8" stroke="{color}" stroke-width="4.5" stroke-linecap="round"/>
  <path d="M50 16 L50 6" stroke="{color}" stroke-width="4.5" stroke-linecap="round"/>
  <path d="M60 18 L64 8" stroke="{color}" stroke-width="4.5" stroke-linecap="round"/>
  <!-- Cup body -->
  <path d="M26 28 H74 V58 C74 71 63 80 50 80 C37 80 26 71 26 58 Z" fill="{bg}" stroke="{color}" stroke-width="4.5" stroke-linejoin="round"/>
  <!-- Cup handle -->
  <path d="M74 36 C86 36 88 52 74 55" stroke="{color}" stroke-width="4.5" stroke-linecap="round" fill="none"/>
  <!-- Eyes -->
  <path d="M38 46 C40 43 44 43 46 46" stroke="{fill_face}" stroke-width="3.5" stroke-linecap="round" fill="none"/>
  <path d="M54 46 C56 43 60 43 62 46" stroke="{fill_face}" stroke-width="3.5" stroke-linecap="round" fill="none"/>
  <!-- Blush -->
  <circle cx="35" cy="51" r="2.5" fill="{color}" opacity="0.35"/>
  <circle cx="65" cy="51" r="2.5" fill="{color}" opacity="0.35"/>
  <!-- Smile -->
  <path d="M44 54 Q50 62 56 54" stroke="{fill_face}" stroke-width="3.5" stroke-linecap="round" fill="none"/>
</svg>"""

def brand_logo_html(color="#004AAD", mascot_color="#004AAD", font_size=28):
    return f"""<div style="display: inline-flex; align-items: flex-end; position: relative; font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;">
  <div style="position: absolute; top: -{int(font_size*1.05)}px; left: {int(font_size*4.1)}px; z-index: 5;">
    {mascot_svg(size=int(font_size*1.3), color=mascot_color, bg="#FFFFFF" if color=="#004AAD" else "#004AAD", fill_face=mascot_color)}
  </div>
  <span style="font-size: {font_size}px; font-weight: 900; color: {color}; letter-spacing: -0.5px; line-height: 1;">NONGKRONG</span>
  <span style="font-size: {font_size}px; font-weight: 900; color: {color}; line-height: 1;">.id</span>
</div>"""

COMMON_FONTS = """
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
"""

print("Assets definitions ready.")
