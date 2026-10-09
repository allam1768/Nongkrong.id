#!/usr/bin/env python3
"""
Generator UI Mockup Lengkap untuk NONGKRONG.id:
1. Customer Menu Catalog & AI Barista (Mobile Web)
2. Customer Live Order Tracking (Mobile Web)
3. Kasir & Barista Live POS Kanban Dashboard (Desktop)
4. Admin / Owner Analytics & Menu Management (Desktop)

Aesthetic Rules:
- Brand: NONGKRONG.id
- Mascot: Smiling coffee cup with cute face, handle, 3 steam lines, and blush marks.
- Palette: Solid Royal Blue (#0049af), Pure White (#ffffff), Solid Black (#000000), Dark Slate (#0f172a), Light Border (#e2e8f0).
- ZERO GRADIENTS. All colors are flat & solid.
- Decorative solid royal blue 5-pointed stars peeking in from edges.
"""

import os
import subprocess

OUTPUT_DIR = "/home/puutraa/Desktop/nongkrong_id/mockups"
os.makedirs(OUTPUT_DIR, exist_ok=True)

# Common SVG Mascot
def get_mascot_svg(size=44, color="#0049af", bg="#ffffff"):
    return f"""
    <svg width="{size}" height="{size}" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
      <!-- Steam -->
      <path d="M40 18 L36 8" stroke="{color}" stroke-width="4.5" stroke-linecap="round"/>
      <path d="M50 16 L50 6" stroke="{color}" stroke-width="4.5" stroke-linecap="round"/>
      <path d="M60 18 L64 8" stroke="{color}" stroke-width="4.5" stroke-linecap="round"/>
      <!-- Cup Body -->
      <path d="M26 28 H74 V58 C74 71 63 80 50 80 C37 80 26 71 26 58 Z" fill="{bg}" stroke="{color}" stroke-width="4.5" stroke-linejoin="round"/>
      <!-- Handle -->
      <path d="M74 36 C85 36 88 52 74 55" stroke="{color}" stroke-width="4.5" stroke-linecap="round" fill="none"/>
      <!-- Eyes (curved smiling eyes) -->
      <path d="M38 46 C40 43 44 43 46 46" stroke="{color}" stroke-width="3.5" stroke-linecap="round" fill="none"/>
      <path d="M54 46 C56 43 60 43 62 46" stroke="{color}" stroke-width="3.5" stroke-linecap="round" fill="none"/>
      <!-- Blush marks -->
      <circle cx="36" cy="51" r="2.5" fill="{color}" opacity="0.4"/>
      <circle cx="64" cy="51" r="2.5" fill="{color}" opacity="0.4"/>
      <!-- Smile -->
      <path d="M44 54 Q50 62 56 54" stroke="{color}" stroke-width="3.5" stroke-linecap="round" fill="none"/>
    </svg>
    """

# Star Decoration SVG
def get_star_svg(size=40, color="#0049af", rotate=0):
    return f"""
    <svg width="{size}" height="{size}" viewBox="0 0 50 50" fill="none" style="transform: rotate({rotate}deg);" xmlns="http://www.w3.org/2000/svg">
      <polygon points="25,2 32,17 48,19 36,31 40,47 25,38 10,47 14,31 2,19 18,17" fill="{color}"/>
    </svg>
    """

# Common Brand Header
def get_brand_header_html(extra_classes=""):
    return f"""
    <div style="display: flex; align-items: center; justify-content: center; position: relative; padding: 10px 0;">
      <div style="display: inline-flex; align-items: flex-end; position: relative;">
        <!-- Smiling Cup perched right atop 'NG' -->
        <div style="position: absolute; top: -30px; left: 110px; z-index: 2;">
          {get_mascot_svg(size=36, color="#0049af", bg="#ffffff")}
        </div>
        <span style="font-size: 26px; font-weight: 900; color: #0049af; letter-spacing: -0.5px; font-family: 'Plus Jakarta Sans', Arial, sans-serif;">NONGKRONG</span>
        <span style="font-size: 26px; font-weight: 900; color: #0049af; font-family: 'Plus Jakarta Sans', Arial, sans-serif;">.id</span>
      </div>
    </div>
    """

print("Base assets helper defined.")
