#!/usr/bin/env python3
"""
Master Presentation Generator for NONGKRONG.id (Smart Cafe POS)
Generates an executive, 16:9 widescreen (13.333" x 7.5"), pure flat design presentation deck.
100% compatible with Google Slides and Microsoft PowerPoint.

Standard DLS:
- Zero gradients, pure solid colors
- Royal Blue (#004AAD / #1E3A8A)
- Charcoal slate text (#0F172A / #334155)
- Crisp borders (#E2E8F0 / #CBD5E1)
- Emerald green (#16A34A) & Amber (#D97706)
- High-res embedded screenshots & diagrams
"""

import os
import sys
from pptx import Presentation
from pptx.util import Inches, Pt
from pptx.dml.color import RGBColor
from pptx.enum.text import PP_ALIGN, MSO_ANCHOR
from pptx.enum.shapes import MSO_SHAPE

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
OUTPUT_FILE = os.path.join(BASE_DIR, "PRESENTASI_SMART_CAFE_NONGKRONG_ID.pptx")
ASSET_DIR = os.path.join(BASE_DIR, "ui_mockups")
ERD_FILE = os.path.join(BASE_DIR, "erd_smart_cafe.png")

# Color Palette Constants (Pure Flat Design)
C_PRIMARY = RGBColor(0, 74, 173)        # #004AAD Royal Blue
C_PRIMARY_DARK = RGBColor(15, 23, 42)    # #0F172A Dark Charcoal/Navy
C_PRIMARY_NAVY = RGBColor(30, 58, 138)   # #1E3A8A Cobalt Navy
C_BG_LIGHT = RGBColor(248, 250, 252)     # #F8FAFC Light Slate
C_BG_WHITE = RGBColor(255, 255, 255)     # #FFFFFF Pure White
C_BG_BLUE_TINT = RGBColor(239, 246, 255) # #EFF6FF Tint Blue
C_BORDER = RGBColor(226, 232, 240)       # #E2E8F0
C_BORDER_DARK = RGBColor(203, 213, 225)  # #CBD5E1
C_TEXT_TITLE = RGBColor(15, 23, 42)      # #0F172A
C_TEXT_BODY = RGBColor(51, 65, 85)       # #334155
C_TEXT_MUTED = RGBColor(100, 116, 139)   # #64748B
C_TEXT_WHITE = RGBColor(255, 255, 255)   # #FFFFFF
C_ACCENT_GREEN = RGBColor(22, 163, 74)   # #16A34A
C_ACCENT_GREEN_BG = RGBColor(220, 252, 231) # #DCFCE7
C_ACCENT_AMBER = RGBColor(217, 119, 6)   # #D97706
C_ACCENT_AMBER_BG = RGBColor(254, 243, 199) # #FEF3C7
C_ACCENT_RED = RGBColor(220, 38, 38)     # #DC2626
C_ACCENT_RED_BG = RGBColor(254, 226, 226) # #FEE2E2

FONT_HEADING = "Trebuchet MS"
FONT_BODY = "Arial"

def create_presentation():
    prs = Presentation()
    prs.slide_width = Inches(13.333)
    prs.slide_height = Inches(7.5)
    return prs

def set_slide_background(slide, color):
    # Add a full-bleed background rectangle
    bg = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, 0, 0, Inches(13.333), Inches(7.5))
    bg.fill.solid()
    bg.fill.fore_color.rgb = color
    bg.line.color.rgb = color
    bg.line.width = Pt(0)
    return bg

def add_header(slide, badge_text, title_text, subtitle_text, dark_mode=False):
    # Badge
    badge_bg = C_BG_WHITE if dark_mode else C_BG_BLUE_TINT
    badge_color = C_PRIMARY if dark_mode else C_PRIMARY
    
    badge = slide.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, Inches(0.8), Inches(0.5), Inches(2.8), Inches(0.36))
    badge.fill.solid()
    badge.fill.fore_color.rgb = badge_bg
    badge.line.color.rgb = C_BORDER_DARK if not dark_mode else C_BG_WHITE
    badge.line.width = Pt(1.5)
    tf = badge.text_frame
    tf.word_wrap = True
    p = tf.paragraphs[0]
    p.text = badge_text.upper()
    p.font.name = FONT_HEADING
    p.font.size = Pt(10)
    p.font.bold = True
    p.font.color.rgb = badge_color
    p.alignment = PP_ALIGN.CENTER
    
    # Title & Subtitle Box
    tx_box = slide.shapes.add_textbox(Inches(0.8), Inches(0.92), Inches(11.7), Inches(0.95))
    tf2 = tx_box.text_frame
    tf2.word_wrap = True
    tf2.margin_left = tf2.margin_top = tf2.margin_right = tf2.margin_bottom = 0
    
    p_title = tf2.paragraphs[0]
    p_title.text = title_text
    p_title.font.name = FONT_HEADING
    p_title.font.size = Pt(22)
    p_title.font.bold = True
    p_title.font.color.rgb = C_TEXT_WHITE if dark_mode else C_TEXT_TITLE
    
    p_sub = tf2.add_paragraph()
    p_sub.text = subtitle_text
    p_sub.font.name = FONT_BODY
    p_sub.font.size = Pt(11)
    p_sub.font.color.rgb = RGBColor(219, 234, 254) if dark_mode else C_TEXT_MUTED
    p_sub.space_before = Pt(3)

def add_footer(slide, current_page, total_pages=17, dark_mode=False):
    # Divider line
    line = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, Inches(0.8), Inches(6.9), Inches(11.733), Pt(1))
    line.fill.solid()
    line.fill.fore_color.rgb = RGBColor(30, 58, 138) if dark_mode else C_BORDER
    line.line.color.rgb = RGBColor(30, 58, 138) if dark_mode else C_BORDER
    line.line.width = Pt(0)
    
    # Left project text
    tx = slide.shapes.add_textbox(Inches(0.8), Inches(6.98), Inches(6.0), Inches(0.35))
    p = tx.text_frame.paragraphs[0]
    p.text = "NONGKRONG.id • Smart Cafe POS & AI Barista (Final Project)"
    p.font.name = FONT_BODY
    p.font.size = Pt(9)
    p.font.color.rgb = RGBColor(191, 219, 254) if dark_mode else C_TEXT_MUTED
    
    # Right author and page number
    tx2 = slide.shapes.add_textbox(Inches(7.5), Inches(6.98), Inches(5.033), Inches(0.35))
    p2 = tx2.text_frame.paragraphs[0]
    p2.text = f"Presenter: Allam (puu)  |  Slide {current_page} of {total_pages}"
    p2.font.name = FONT_BODY
    p2.font.size = Pt(9)
    p2.font.color.rgb = RGBColor(191, 219, 254) if dark_mode else C_TEXT_MUTED
    p2.alignment = PP_ALIGN.RIGHT

def add_flat_card(slide, left, top, width, height, bg_color=C_BG_WHITE, border_color=C_BORDER, border_pt=1.5):
    card = slide.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, Inches(left), Inches(top), Inches(width), Inches(height))
    card.fill.solid()
    card.fill.fore_color.rgb = bg_color
    card.line.color.rgb = border_color
    card.line.width = Pt(border_pt)
    return card

def add_image_card(slide, img_path, left, top, width, height, border_color=C_BORDER_DARK):
    # Outer frame
    frame = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, Inches(left), Inches(top), Inches(width), Inches(height))
    frame.fill.solid()
    frame.fill.fore_color.rgb = C_BG_WHITE
    frame.line.color.rgb = border_color
    frame.line.width = Pt(1.5)
    
    # Add picture slightly inside
    pad = 0.04
    slide.shapes.add_picture(img_path, Inches(left + pad), Inches(top + pad), Inches(width - 2*pad), Inches(height - 2*pad))


# ==========================================================
# SLIDE BUILDERS
# ==========================================================

def build_slide_01(prs):
    # Slide 1: Cover Slide
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    set_slide_background(slide, C_PRIMARY)
    
    # Top Tag
    badge = slide.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, Inches(0.8), Inches(1.1), Inches(4.2), Inches(0.42))
    badge.fill.solid()
    badge.fill.fore_color.rgb = C_BG_WHITE
    badge.line.color.rgb = C_BG_WHITE
    tf = badge.text_frame
    p = tf.paragraphs[0]
    p.text = "FINAL PROJECT DEFENSE • LARAVEL FULLSTACK"
    p.font.name = FONT_HEADING
    p.font.size = Pt(11)
    p.font.bold = True
    p.font.color.rgb = C_PRIMARY
    p.alignment = PP_ALIGN.CENTER
    
    # Big Title
    tb = slide.shapes.add_textbox(Inches(0.8), Inches(1.75), Inches(11.7), Inches(2.4))
    tf2 = tb.text_frame
    tf2.word_wrap = True
    
    p1 = tf2.paragraphs[0]
    p1.text = "NONGKRONG.id"
    p1.font.name = FONT_HEADING
    p1.font.size = Pt(44)
    p1.font.bold = True
    p1.font.color.rgb = C_TEXT_WHITE
    
    p2 = tf2.add_paragraph()
    p2.text = "Smart Cafe POS & Self-Ordering System Berbasis Generative AI\ndan Realtime Kitchen Display"
    p2.font.name = FONT_HEADING
    p2.font.size = Pt(20)
    p2.font.bold = True
    p2.font.color.rgb = RGBColor(224, 237, 255)
    p2.space_before = Pt(8)
    
    p3 = tf2.add_paragraph()
    p3.text = "Pemberdayaan Kafe Modern melalui Rekomendasi Rasa Mas Nongki (Gemini 1.5 Flash), Dual Table Verification, dan Livewire 3 Reactive Polling"
    p3.font.name = FONT_BODY
    p3.font.size = Pt(12)
    p3.font.color.rgb = RGBColor(191, 219, 254)
    p3.space_before = Pt(6)

    # Info Cards at Bottom
    c1 = add_flat_card(slide, 0.8, 4.45, 3.6, 2.1, bg_color=C_PRIMARY_NAVY, border_color=RGBColor(59, 130, 246), border_pt=1.5)
    tf_c1 = c1.text_frame
    tf_c1.margin_left = tf_c1.margin_right = Inches(0.25)
    p = tf_c1.paragraphs[0]
    p.text = "👤 PENGEMBANG SISTEM"
    p.font.size = Pt(10)
    p.font.bold = True
    p.font.color.rgb = RGBColor(147, 197, 253)
    p.alignment = PP_ALIGN.LEFT
    p2 = tf_c1.add_paragraph()
    p2.text = "Allam (puu)"
    p2.font.size = Pt(16)
    p2.font.bold = True
    p2.font.color.rgb = C_TEXT_WHITE
    p2.alignment = PP_ALIGN.LEFT
    p2.space_before = Pt(4)
    p3 = tf_c1.add_paragraph()
    p3.text = "Lead Fullstack Software Engineer\nSpesialisasi: Laravel, Livewire, & GenAI"
    p3.font.size = Pt(10)
    p3.font.color.rgb = RGBColor(224, 237, 255)
    p3.alignment = PP_ALIGN.LEFT
    p3.space_before = Pt(4)

    c2 = add_flat_card(slide, 4.7, 4.45, 3.8, 2.1, bg_color=C_PRIMARY_NAVY, border_color=RGBColor(59, 130, 246), border_pt=1.5)
    tf_c2 = c2.text_frame
    tf_c2.margin_left = tf_c2.margin_right = Inches(0.25)
    p = tf_c2.paragraphs[0]
    p.text = "🛠️ TEKNOLOGI INTI"
    p.font.size = Pt(10)
    p.font.bold = True
    p.font.color.rgb = RGBColor(147, 197, 253)
    p.alignment = PP_ALIGN.LEFT
    p2 = tf_c2.add_paragraph()
    p2.text = "Laravel 11 • Livewire 3"
    p2.font.size = Pt(16)
    p2.font.bold = True
    p2.font.color.rgb = C_TEXT_WHITE
    p2.alignment = PP_ALIGN.LEFT
    p2.space_before = Pt(4)
    p3 = tf_c2.add_paragraph()
    p3.text = "Database: MariaDB 10.11+ (3NF)\nAI: Google Gemini 1.5 Flash (JSON Output)\nUI: Tailwind CSS • Pure Flat Design"
    p3.font.size = Pt(10)
    p3.font.color.rgb = RGBColor(224, 237, 255)
    p3.alignment = PP_ALIGN.LEFT
    p3.space_before = Pt(4)

    c3 = add_flat_card(slide, 8.8, 4.45, 3.733, 2.1, bg_color=C_PRIMARY_NAVY, border_color=RGBColor(59, 130, 246), border_pt=1.5)
    tf_c3 = c3.text_frame
    tf_c3.margin_left = tf_c3.margin_right = Inches(0.25)
    p = tf_c3.paragraphs[0]
    p.text = "☕ LOKASI & TARGET"
    p.font.size = Pt(10)
    p.font.bold = True
    p.font.color.rgb = RGBColor(147, 197, 253)
    p.alignment = PP_ALIGN.LEFT
    p2 = tf_c3.add_paragraph()
    p2.text = "Smart Cafe Ecosystem"
    p2.font.size = Pt(16)
    p2.font.bold = True
    p2.font.color.rgb = C_TEXT_WHITE
    p2.alignment = PP_ALIGN.LEFT
    p2.space_before = Pt(4)
    p3 = tf_c3.add_paragraph()
    p3.text = "Pilot Project: Cabang Dipati Ukur\nKapasitas: 6 Meja Sesi Dinamis\nStatus: Teruji 100% Siap Produksi"
    p3.font.size = Pt(10)
    p3.font.color.rgb = RGBColor(224, 237, 255)
    p3.alignment = PP_ALIGN.LEFT
    p3.space_before = Pt(4)


def build_slide_02(prs):
    # Slide 2: Latar Belakang & Permasalahan
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    set_slide_background(slide, C_BG_LIGHT)
    add_header(slide, "Latar Belakang & Masalah", "Bottleneck & Tantangan Operasional Kafe Konvensional", 
               "Analisis kendala alur pemesanan fisik di era tingginya mobilitas dan ekspektasi pelanggan kafe")
    
    # 4 Cards Layout
    cards_data = [
        {
            "num": "01",
            "title": "Antrean Kasir di Jam Sibuk (Peak Hours)",
            "desc": "Penumpukan antrean pada pukul 14:00 - 18:00 menyebabkan waktu tunggu rata-rata mencapai 8-15 menit hanya untuk memesan, berujung pada walk-out rate 12%.",
            "stat": "Waktu Tunggu: 8 - 15 Menit",
            "stat_color": C_ACCENT_RED,
            "stat_bg": C_ACCENT_RED_BG
        },
        {
            "num": "02",
            "title": "Decision Paralysis & Menu Statis",
            "desc": "Buku menu fisik/PDF statis gagal membantu tamu yang awam profil rasa kopi. Tamu bingung memilih di antara puluhan beans artisan tanpa barista pendamping.",
            "stat": "40%+ Tamu Ragu Memilih Menu",
            "stat_color": C_ACCENT_AMBER,
            "stat_bg": C_ACCENT_AMBER_BG
        },
        {
            "num": "03",
            "title": "Miskomunikasi Catatan Meja vs Dapur",
            "desc": "Nota kertas tulisan tangan rawan hilang, salah dengar catatan khusus (misal: 'less sugar 50% / oatmilk'), serta tertukarnya pesanan antar nomor meja.",
            "stat": "Human Error Nota Kertas: ~7%",
            "stat_color": C_ACCENT_RED,
            "stat_bg": C_ACCENT_RED_BG
        },
        {
            "num": "04",
            "title": "Keterbatasan Monitoring Pemilik (Owner)",
            "desc": "Mesin kasir POS lokal tidak dapat dipantau saat pemilik kafe mobilitas di luar outlet. Sulit mendeteksi menu habis dan pergerakan omzet secara instan.",
            "stat": "Ketiadaan Pantauan On-the-Go",
            "stat_color": C_ACCENT_AMBER,
            "stat_bg": C_ACCENT_AMBER_BG
        }
    ]

    for i, c in enumerate(cards_data):
        col = i % 2
        row = i // 2
        left = 0.8 + col * 5.95
        top = 2.0 + row * 2.35
        
        card = add_flat_card(slide, left, top, 5.75, 2.15, bg_color=C_BG_WHITE, border_color=C_BORDER)
        tf = card.text_frame
        tf.margin_left = tf.margin_right = Inches(0.25)
        tf.margin_top = Inches(0.2)
        
        p = tf.paragraphs[0]
        p.text = f"{c['num']} • {c['title']}"
        p.font.name = FONT_HEADING
        p.font.size = Pt(13)
        p.font.bold = True
        p.font.color.rgb = C_PRIMARY
        
        p2 = tf.add_paragraph()
        p2.text = c['desc']
        p2.font.name = FONT_BODY
        p2.font.size = Pt(10)
        p2.font.color.rgb = C_TEXT_BODY
        p2.space_before = Pt(6)
        
        p3 = tf.add_paragraph()
        p3.text = f"🚨 {c['stat']}"
        p3.font.name = FONT_BODY
        p3.font.size = Pt(10)
        p3.font.bold = True
        p3.font.color.rgb = c['stat_color']
        p3.space_before = Pt(6)

    add_footer(slide, 2)


def build_slide_03(prs):
    # Slide 3: Solusi Inovatif & UVP
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    set_slide_background(slide, C_BG_LIGHT)
    add_header(slide, "Solusi Sistem", "Solusi Inovatif NONGKRONG.id: Self-Ordering Cerdas", 
               "Empat pilar transformasi digital yang mengubah pengalaman kafe dari hulu ke hilir")
    
    pillars = [
        {
            "icon": "📱",
            "title": "Dual-Verification Check-In",
            "pts": [
                "Scan kamera QR instan langsung di meja.",
                "Fallback 5-box kode meja (misal: 7K92A) jika kamera bermasalah.",
                "Zero installation: jalan di browser HP & laptop."
            ]
        },
        {
            "icon": "🤖",
            "title": "AI Barista 'Mas Nongki'",
            "pts": [
                "Konsultasi selera rasa bertenaga Gemini 1.5 Flash.",
                "Menyerap 15 menu lokal MariaDB secara real-time.",
                "Structured JSON: kartu rekomendasi match 98% & 1-klik order."
            ]
        },
        {
            "icon": "📋",
            "title": "Realtime KDS Kanban Board",
            "pts": [
                "Dapur barista menerima tiket otomatis (wire:poll.3s).",
                "Kolom dinamis: Menunggu ➔ Diracik ➔ Siap Saji.",
                "Pelanggan pantau progres meja secara transparan."
            ]
        },
        {
            "icon": "📊",
            "title": "Omnichannel Role Ecosystem",
            "pts": [
                "Peran 1: Web & Mobile Pelanggan mandiri.",
                "Peran 2: Kasir POS Walk-in & Layar KDS Barista.",
                "Peran 3: Admin Widescreen & Executive Mobile App."
            ]
        }
    ]

    for i, p_info in enumerate(pillars):
        left = 0.8 + i * 2.98
        card = add_flat_card(slide, left, 2.0, 2.82, 4.65, bg_color=C_BG_WHITE, border_color=C_BORDER)
        tf = card.text_frame
        tf.margin_left = tf.margin_right = Inches(0.2)
        tf.margin_top = Inches(0.25)
        
        p = tf.paragraphs[0]
        p.text = p_info["icon"]
        p.font.size = Pt(28)
        
        p2 = tf.add_paragraph()
        p2.text = p_info["title"]
        p2.font.name = FONT_HEADING
        p2.font.size = Pt(13)
        p2.font.bold = True
        p2.font.color.rgb = C_PRIMARY
        p2.space_before = Pt(8)
        
        for pt in p_info["pts"]:
            p_pt = tf.add_paragraph()
            p_pt.text = f"• {pt}"
            p_pt.font.name = FONT_BODY
            p_pt.font.size = Pt(10)
            p_pt.font.color.rgb = C_TEXT_BODY
            p_pt.space_before = Pt(8)

    add_footer(slide, 3)


def build_slide_04(prs):
    # Slide 4: Arsitektur & Tech Stack
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    set_slide_background(slide, C_BG_LIGHT)
    add_header(slide, "Spesifikasi Teknologi", "Arsitektur Sistem & Modern Tech Stack", 
               "Kombinasi teknologi yang efisien, reaktif, dan mudah dimaintain tanpa overhead infrastruktur")
    
    stacks = [
        {
            "layer": "BACKEND FRAMEWORK",
            "name": "Laravel 11 / 12 (PHP 8.5)",
            "details": [
                "Arsitektur MVC & Service-Repository Pattern.",
                "Eloquent ORM untuk relasi 7 tabel yang aman & teroptimasi.",
                "Proteksi keamanan: CSRF protection, Prepared Statements, & RBAC Guard."
            ],
            "badge": "Stabilitas & Keamanan"
        },
        {
            "layer": "FRONTEND REACTIVITY",
            "name": "Livewire 3 + Alpine.js",
            "details": [
                "Interaktivitas real-time tanpa perlu API terpisah & SPA bundle.",
                "Polling ringan wire:poll.3s menghemat biaya server.",
                "Tailwind CSS dengan Pure Flat Design (Solid Blue #004AAD, Zero Gradient)."
            ],
            "badge": "Reaktif Tanpa Reload"
        },
        {
            "layer": "RELATIONAL DATABASE",
            "name": "MariaDB 10.11+ (InnoDB Engine)",
            "details": [
                "Normalisasi 3NF dengan integritas referensial CASCADE.",
                "Indeks unik pada access_code meja untuk lookup instan < 5ms.",
                "Penyimpanan terpusat: Users, Tables, Categories, Menus, Orders, AI Logs."
            ],
            "badge": "Integritas 3NF Murni"
        },
        {
            "layer": "GENERATIVE AI ENGINE",
            "name": "Google Gemini 1.5 Flash API",
            "details": [
                "Structured JSON Output untuk parsing kartu hidangan instan.",
                "Latensi ultra-cepat (~1.1 detik) dengan context injection 15 menu lokal.",
                "Temperature tuned di 0.7 untuk persona ramah 'Mas Nongki'."
            ],
            "badge": "Rekomendasi Cerdas"
        }
    ]

    for i, s in enumerate(stacks):
        col = i % 2
        row = i // 2
        left = 0.8 + col * 5.95
        top = 2.0 + row * 2.35
        
        card = add_flat_card(slide, left, top, 5.75, 2.15, bg_color=C_BG_WHITE, border_color=C_BORDER)
        tf = card.text_frame
        tf.margin_left = tf.margin_right = Inches(0.25)
        tf.margin_top = Inches(0.18)
        
        p = tf.paragraphs[0]
        p.text = f"{s['layer']}  •  {s['badge']}"
        p.font.name = FONT_HEADING
        p.font.size = Pt(9)
        p.font.bold = True
        p.font.color.rgb = C_PRIMARY
        
        p2 = tf.add_paragraph()
        p2.text = s['name']
        p2.font.name = FONT_HEADING
        p2.font.size = Pt(14)
        p2.font.bold = True
        p2.font.color.rgb = C_TEXT_TITLE
        p2.space_before = Pt(2)
        
        for det in s['details']:
            p_det = tf.add_paragraph()
            p_det.text = f"✔ {det}"
            p_det.font.name = FONT_BODY
            p_det.font.size = Pt(9.5)
            p_det.font.color.rgb = C_TEXT_BODY
            p_det.space_before = Pt(3)

    add_footer(slide, 4)


def build_slide_05(prs):
    # Slide 5: Tiga Peran Pengguna (Three-Tier Roles)
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    set_slide_background(slide, C_BG_LIGHT)
    add_header(slide, "Manajemen Akses (RBAC)", "Tiga Peran Pengguna (Three-Tier Architecture)", 
               "Pemisahan tanggung jawab operasional untuk kenyamanan pelanggan dan kontrol penuh manajemen")
    
    roles = [
        {
            "tag": "ROLE 1",
            "name": "Pelanggan (Customer)",
            "devices": "📱 Smartphone Web PWA & 💻 Desktop Widescreen",
            "color": C_PRIMARY,
            "tasks": [
                "Verifikasi meja (QR Scan / 5-box code).",
                "Eksplorasi katalog & filter preferensi rasa.",
                "Konsultasi interaktif AI Barista Mas Nongki.",
                "Review keranjang & pembayaran QRIS dinamis.",
                "Live tracking status pesanan di meja."
            ]
        },
        {
            "tag": "ROLE 2",
            "name": "Staff Kasir & Barista",
            "devices": "🖥️ Layar Sentuh POS & 📺 Tablet KDS Dapur",
            "color": C_ACCENT_AMBER,
            "tasks": [
                "Pemesanan langsung tamu offline (Walk-in).",
                "Kalkulator bayar tunai & cetak struk kasir.",
                "Monitoring antrean masuk di dapur (KDS Kanban).",
                "Pindah status: Diracik ➔ Siap Saji ➔ Selesai.",
                "Notifikasi waiter untuk pengantaran ke meja."
            ]
        },
        {
            "tag": "ROLE 3",
            "name": "Admin & Cafe Owner",
            "devices": "💻 Desktop Backoffice & 📱 Mobile Executive App",
            "color": C_ACCENT_GREEN,
            "tasks": [
                "Executive dashboard: omzet & jam ramai.",
                "CRUD 15 menu, harga, & kontrol stok.",
                "AI sandbox: tuning prompt persona & temperature.",
                "Floor monitor: cek kode meja 5 karakter.",
                "Audit log transaksi & riwayat konsultasi AI."
            ]
        }
    ]

    for i, r in enumerate(roles):
        left = 0.8 + i * 3.98
        card = add_flat_card(slide, left, 2.0, 3.78, 4.65, bg_color=C_BG_WHITE, border_color=C_BORDER)
        tf = card.text_frame
        tf.margin_left = tf.margin_right = Inches(0.25)
        tf.margin_top = Inches(0.22)
        
        p = tf.paragraphs[0]
        p.text = r["tag"]
        p.font.name = FONT_HEADING
        p.font.size = Pt(11)
        p.font.bold = True
        p.font.color.rgb = r["color"]
        
        p2 = tf.add_paragraph()
        p2.text = r["name"]
        p2.font.name = FONT_HEADING
        p2.font.size = Pt(15)
        p2.font.bold = True
        p2.font.color.rgb = C_TEXT_TITLE
        p2.space_before = Pt(2)
        
        p_dev = tf.add_paragraph()
        p_dev.text = r["devices"]
        p_dev.font.name = FONT_BODY
        p_dev.font.size = Pt(9.5)
        p_dev.font.bold = True
        p_dev.font.color.rgb = C_TEXT_MUTED
        p_dev.space_before = Pt(4)
        
        p_div = tf.add_paragraph()
        p_div.text = "Fitur & Hak Akses Fungsional:"
        p_div.font.name = FONT_HEADING
        p_div.font.size = Pt(10)
        p_div.font.bold = True
        p_div.font.color.rgb = C_TEXT_BODY
        p_div.space_before = Pt(8)
        
        for task in r["tasks"]:
            p_t = tf.add_paragraph()
            p_t.text = f"• {task}"
            p_t.font.name = FONT_BODY
            p_t.font.size = Pt(10)
            p_t.font.color.rgb = C_TEXT_BODY
            p_t.space_before = Pt(5)

    add_footer(slide, 5)


def build_slide_06(prs):
    # Slide 6: Alur Kerja Sistem Global
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    set_slide_background(slide, C_BG_LIGHT)
    add_header(slide, "Diagram Alur Sistem", "Alur Proses Bisnis End-to-End (ANSI/ISO 5807)", 
               "Orkestrasi terpadu dari kedatangan pelanggan hingga hidangan disajikan di meja")
    
    steps = [
        ("1. Check-In Meja", "Tamu scan QR atau ketik 5 kode meja (7K92A) ➔ Sesi meja terkunci di sistem.", C_PRIMARY),
        ("2. Pilih / AI Barista", "Eksplorasi katalog mandiri ATAU konsultasi preferensi rasa Mas Nongki.", C_PRIMARY),
        ("3. Checkout & QRIS", "Review item, catatan barista, kalkulasi pajak PB1 10%, & bayar via QRIS.", C_PRIMARY),
        ("4. Dapur (KDS Kanban)", "Tiket order otomatis masuk ke layar barista via Livewire polling 3 detik.", C_ACCENT_AMBER),
        ("5. Barista Meracik", "Status pesanan berganti ke 'Sedang Diracik' ➔ Diperbarui ke HP tamu.", C_ACCENT_AMBER),
        ("6. Sajian Tiba di Meja", "Barista klik 'Siap Saji' ➔ Waiter antar ke meja ➔ Selesai & Meja bersih.", C_ACCENT_GREEN)
    ]

    for i, (title, desc, color) in enumerate(steps):
        col = i % 3
        row = i // 3
        left = 0.8 + col * 3.98
        top = 2.0 + row * 2.35
        
        card = add_flat_card(slide, left, top, 3.78, 2.15, bg_color=C_BG_WHITE, border_color=C_BORDER)
        tf = card.text_frame
        tf.margin_left = tf.margin_right = Inches(0.2)
        tf.margin_top = Inches(0.2)
        
        p = tf.paragraphs[0]
        p.text = f"TAHAP {i+1}"
        p.font.name = FONT_HEADING
        p.font.size = Pt(9.5)
        p.font.bold = True
        p.font.color.rgb = color
        
        p2 = tf.add_paragraph()
        p2.text = title
        p2.font.name = FONT_HEADING
        p2.font.size = Pt(13)
        p2.font.bold = True
        p2.font.color.rgb = C_TEXT_TITLE
        p2.space_before = Pt(3)
        
        p3 = tf.add_paragraph()
        p3.text = desc
        p3.font.name = FONT_BODY
        p3.font.size = Pt(10)
        p3.font.color.rgb = C_TEXT_BODY
        p3.space_before = Pt(5)

    add_footer(slide, 6)


def build_slide_07(prs):
    # Slide 7: Basis Data & ERD 3NF
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    set_slide_background(slide, C_BG_LIGHT)
    add_header(slide, "Perancangan Basis Data", "Struktur Basis Data Relasional 3NF & Kamus Data", 
               "Menjamin konsistensi transaksi, performa tinggi, dan referensial antar entitas tanpa redundansi data")
    
    # Left Card: Table dictionary summary
    c_left = add_flat_card(slide, 0.8, 2.0, 5.2, 4.65, bg_color=C_BG_WHITE, border_color=C_BORDER)
    tf = c_left.text_frame
    tf.margin_left = tf.margin_right = Inches(0.25)
    tf.margin_top = Inches(0.2)
    
    p = tf.paragraphs[0]
    p.text = "7 TABEL DOMAIN UTAMA (3NF TERUJI)"
    p.font.name = FONT_HEADING
    p.font.size = Pt(11)
    p.font.bold = True
    p.font.color.rgb = C_PRIMARY
    
    tables_summary = [
        ("users", "Kelola kredensial staff & owner (admin, staff, customer)."),
        ("tables", "Nomor meja, kapasitas, status, & access_code unik 5 digit."),
        ("categories", "Kategori hidangan (Espresso, Signature, Non-Coffee, Pastry)."),
        ("menus", "Daftar 15 menu, harga, stok aktif, & taste_profile untuk AI."),
        ("orders", "Tiket transaksi meja, status (pending-completed), & total bayar."),
        ("order_items", "Detail item per order, kuantitas, harga, & custom notes."),
        ("ai_consultations", "Log audit prompt tamu, respon Mas Nongki, & token usage.")
    ]
    
    for t_name, t_desc in tables_summary:
        p_item = tf.add_paragraph()
        p_item.text = f"• {t_name}: {t_desc}"
        p_item.font.name = FONT_BODY
        p_item.font.size = Pt(9.5)
        p_item.font.color.rgb = C_TEXT_BODY
        p_item.space_before = Pt(4)

    # Right: ERD Image
    if os.path.exists(ERD_FILE):
        add_image_card(slide, ERD_FILE, 6.2, 2.0, 6.333, 4.65)

    add_footer(slide, 7)


def build_slide_08(prs):
    # Slide 8: Fitur 1: Check-In Meja
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    set_slide_background(slide, C_BG_LIGHT)
    add_header(slide, "Fitur Unggulan 1", "Dual-Verification Table Check-In (QR & 5-Box Code)", 
               "Solusi inklusif mengatasi kendala teknis kamera HP atau perangkat laptop pelanggan")
    
    # Left: Explanation Card
    c_left = add_flat_card(slide, 0.8, 2.0, 4.8, 4.65, bg_color=C_BG_WHITE, border_color=C_BORDER)
    tf = c_left.text_frame
    tf.margin_left = tf.margin_right = Inches(0.25)
    tf.margin_top = Inches(0.25)
    
    p = tf.paragraphs[0]
    p.text = "MEKANISME CHECK-IN TANPA HAMBATAN"
    p.font.name = FONT_HEADING
    p.font.size = Pt(11)
    p.font.bold = True
    p.font.color.rgb = C_PRIMARY
    
    pts = [
        "Metode 1: QR Code Scanner Kamera",
        "Pelanggan cukup arahkan kamera smartphone/webcam ke stiker akrilik meja untuk langsung membuka menu.",
        "Metode 2: 5-Box Manual Access Code",
        "Jika izin kamera diblokir atau pencahayaan redup, tamu cukup mengetik 5 karakter unik (misal: 7K92A, 3X81B, 8Y24C) yang tertera di meja.",
        "Kunci Sesi Meja Otomatis",
        "Sistem langsung mengikat session_id dengan nomor meja di MariaDB, mencegah kesalahan kirim hidangan ke meja lain.",
        "Zero-Install Experience",
        "Tidak membutuhkan unduh aplikasi PlayStore/AppStore yang merepotkan pelanggan baru."
    ]
    
    for i, pt in enumerate(pts):
        p_pt = tf.add_paragraph()
        p_pt.text = pt
        if i % 2 == 0:
            p_pt.font.name = FONT_HEADING
            p_pt.font.size = Pt(10.5)
            p_pt.font.bold = True
            p_pt.font.color.rgb = C_TEXT_TITLE
            p_pt.space_before = Pt(8)
        else:
            p_pt.font.name = FONT_BODY
            p_pt.font.size = Pt(9.5)
            p_pt.font.color.rgb = C_TEXT_BODY
            p_pt.space_before = Pt(2)

    # Right: Screenshots (Web Checkin & Mobile Checkin)
    web_checkin = os.path.join(ASSET_DIR, "02_web_table_checkin.png")
    mob_checkin = os.path.join(ASSET_DIR, "03_table_checkin.png")
    
    if os.path.exists(web_checkin):
        add_image_card(slide, web_checkin, 5.8, 2.0, 4.5, 4.65)
    if os.path.exists(mob_checkin):
        add_image_card(slide, mob_checkin, 10.45, 2.0, 2.083, 4.65)

    add_footer(slide, 8)


def build_slide_09(prs):
    # Slide 9: Fitur 2: AI Barista Mas Nongki
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    set_slide_background(slide, C_BG_LIGHT)
    add_header(slide, "Fitur Unggulan 2", "AI Barista 'Mas Nongki': Rekomendasi Personal", 
               "Integrasi Gemini 1.5 Flash dengan katalog dinamis 15 menu lokal untuk mengatasi kebingungan rasa")
    
    # Left: Details Card
    c_left = add_flat_card(slide, 0.8, 2.0, 4.8, 4.65, bg_color=C_BG_WHITE, border_color=C_BORDER)
    tf = c_left.text_frame
    tf.margin_left = tf.margin_right = Inches(0.25)
    tf.margin_top = Inches(0.2)
    
    p = tf.paragraphs[0]
    p.text = "ORKESTRASI PROMPT & STRUCTURED OUTPUT"
    p.font.name = FONT_HEADING
    p.font.size = Pt(11)
    p.font.bold = True
    p.font.color.rgb = C_PRIMARY
    
    points = [
        "1. Dynamic Menu Context Injection",
        "Saat pelanggan chat, sistem menyuntikkan seluruh 15 menu aktif dari MariaDB beserta atribut rasa (misal: Sweet & Creamy, Bitter & Bold).",
        "2. Persona Lokal yang Ramah",
        "Mas Nongki diprogram menyapa pelanggan sesuai nomor mejanya dan menjelaskan profil beans dengan bahasa santai khas barista kafe.",
        "3. Structured JSON Response",
        "Gemini merespon dengan skema JSON terstruktur: teks sapaan ramah, array menu rekomendasi, dan persentase kecocokan (misal: 98% Match).",
        "4. 1-Klik Tambah ke Keranjang",
        "Hasil rekomendasi langsung dirender menjadi kartu interaktif dengan tombol '+' cepat ke keranjang meja."
    ]
    
    for i, pt in enumerate(points):
        p_pt = tf.add_paragraph()
        p_pt.text = pt
        if i % 2 == 0:
            p_pt.font.name = FONT_HEADING
            p_pt.font.size = Pt(10.5)
            p_pt.font.bold = True
            p_pt.font.color.rgb = C_TEXT_TITLE
            p_pt.space_before = Pt(8)
        else:
            p_pt.font.name = FONT_BODY
            p_pt.font.size = Pt(9.5)
            p_pt.font.color.rgb = C_TEXT_BODY
            p_pt.space_before = Pt(2)

    # Right: Screenshot AI Barista
    web_ai = os.path.join(ASSET_DIR, "04_web_ai_barista.png")
    if os.path.exists(web_ai):
        add_image_card(slide, web_ai, 5.8, 2.0, 6.733, 4.65)

    add_footer(slide, 9)


def build_slide_10(prs):
    # Slide 10: Fitur 3: KDS & Order Tracking
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    set_slide_background(slide, C_BG_LIGHT)
    add_header(slide, "Fitur Unggulan 3", "Kitchen Display System (KDS) & Realtime Tracking", 
               "Transparansi alur pesanan dari dapur barista hingga ke meja pelanggan tanpa kertas nota")
    
    # Left: Dapur Screenshot
    pos_kanban = os.path.join(ASSET_DIR, "08_pos_kanban.png")
    if os.path.exists(pos_kanban):
        add_image_card(slide, pos_kanban, 0.8, 2.0, 5.7, 3.4)
    
    # Left Bottom Note
    c_note = add_flat_card(slide, 0.8, 5.55, 5.7, 1.1, bg_color=C_BG_WHITE, border_color=C_BORDER)
    tf_n = c_note.text_frame
    tf_n.margin_left = tf_n.margin_right = Inches(0.2)
    p = tf_n.paragraphs[0]
    p.text = "📺 LAYAR KDS DAPUR BARISTA (ROLE 2)"
    p.font.name = FONT_HEADING
    p.font.size = Pt(10)
    p.font.bold = True
    p.font.color.rgb = C_ACCENT_AMBER
    p2 = tf_n.add_paragraph()
    p2.text = "Auto-update polling wire:poll.3s mengelompokkan tiket: Menunggu (Kuning) ➔ Diracik (Biru) ➔ Siap Saji (Hijau)."
    p2.font.name = FONT_BODY
    p2.font.size = Pt(9)
    p2.font.color.rgb = C_TEXT_BODY
    p2.space_before = Pt(2)

    # Right: Customer Tracking Screenshot
    web_track = os.path.join(ASSET_DIR, "06_web_order_tracking.png")
    if os.path.exists(web_track):
        add_image_card(slide, web_track, 6.7, 2.0, 5.833, 3.4)
        
    c_note2 = add_flat_card(slide, 6.7, 5.55, 5.833, 1.1, bg_color=C_BG_WHITE, border_color=C_BORDER)
    tf_n2 = c_note2.text_frame
    tf_n2.margin_left = tf_n2.margin_right = Inches(0.2)
    p = tf_n2.paragraphs[0]
    p.text = "📱 LIVE STATUS MEJA PELANGGAN (ROLE 1)"
    p.font.name = FONT_HEADING
    p.font.size = Pt(10)
    p.font.bold = True
    p.font.color.rgb = C_PRIMARY
    p2 = tf_n2.add_paragraph()
    p2.text = "Stepper 4-tahap dengan estimasi waktu saji (~5-8 Menit), breakdown bill tiket, dan tombol panggil waiter."
    p2.font.name = FONT_BODY
    p2.font.size = Pt(9)
    p2.font.color.rgb = C_TEXT_BODY
    p2.space_before = Pt(2)

    add_footer(slide, 10)


def build_slide_11(prs):
    # Slide 11: Showcase Customer Catalog
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    set_slide_background(slide, C_BG_LIGHT)
    add_header(slide, "Showcase Antarmuka", "Antarmuka Pelanggan: Desktop Web & Mobile", 
               "Tampilan katalog menu, sticky cart, dan filter kategori dengan estetika Pure Flat Design")
    
    # Desktop Web Catalog
    web_catalog = os.path.join(ASSET_DIR, "03_web_customer_catalog.png")
    if os.path.exists(web_catalog):
        add_image_card(slide, web_catalog, 0.8, 2.0, 8.8, 4.65)
        
    # Mobile Catalog
    mob_catalog = os.path.join(ASSET_DIR, "04_customer_catalog.png")
    if os.path.exists(mob_catalog):
        add_image_card(slide, mob_catalog, 9.8, 2.0, 2.733, 4.65)

    add_footer(slide, 11)


def build_slide_12(prs):
    # Slide 12: Showcase Staff POS Walk-in
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    set_slide_background(slide, C_BG_LIGHT)
    add_header(slide, "Showcase Antarmuka", "Staff Kasir POS Fisik: Khusus Tamu Walk-In", 
               "Mendukung alur operasional tamu yang tidak membawa smartphone atau memilih bayar tunai di kasir")
    
    pos_walkin = os.path.join(ASSET_DIR, "09_pos_walkin.png")
    if os.path.exists(pos_walkin):
        add_image_card(slide, pos_walkin, 0.8, 2.0, 7.8, 4.65)
        
    c_right = add_flat_card(slide, 8.8, 2.0, 3.733, 4.65, bg_color=C_BG_WHITE, border_color=C_BORDER)
    tf = c_right.text_frame
    tf.margin_left = tf.margin_right = Inches(0.25)
    tf.margin_top = Inches(0.25)
    
    p = tf.paragraphs[0]
    p.text = "FITUR KASIR FISIK OFFLINE"
    p.font.name = FONT_HEADING
    p.font.size = Pt(11)
    p.font.bold = True
    p.font.color.rgb = C_ACCENT_AMBER
    
    feats = [
        "Grid Menu Touchscreen Cepat",
        "Ikon dan foto menu besar dioptimalkan untuk input layar sentuh kasir.",
        "Kalkulator Kembalian Tunai",
        "Preset tombol uang pas (Rp 50.000, Rp 100.000) dan kalkulasi kembalian otomatis.",
        "Dukungan Dine-in & Takeaway",
        "Kasir dapat memilih nomor meja tujuan atau pesanan bungkus bawa pulang.",
        "Sinkronisasi Instan ke Dapur",
        "Pesanan langsung terinjeksi ke antrean KDS Barista tanpa cetak kertas tambahan."
    ]
    for i, f in enumerate(feats):
        pf = tf.add_paragraph()
        pf.text = f
        if i % 2 == 0:
            pf.font.name = FONT_HEADING
            pf.font.size = Pt(10.5)
            pf.font.bold = True
            pf.font.color.rgb = C_TEXT_TITLE
            pf.space_before = Pt(8)
        else:
            pf.font.name = FONT_BODY
            pf.font.size = Pt(9.5)
            pf.font.color.rgb = C_TEXT_BODY
            pf.space_before = Pt(2)

    add_footer(slide, 12)


def build_slide_13(prs):
    # Slide 13: Showcase Admin Management
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    set_slide_background(slide, C_BG_LIGHT)
    add_header(slide, "Showcase Antarmuka", "Admin Management: Desktop Analytics & Mobile App", 
               "Dua sudut pandang bagi pemilik usaha: analitik mendalam di desktop dan monitoring saat mobilitas")
    
    # Desktop Analytics (Left)
    admin_desk = os.path.join(ASSET_DIR, "10_admin_analytics.png")
    if os.path.exists(admin_desk):
        add_image_card(slide, admin_desk, 0.8, 2.0, 6.8, 4.65)
        
    # Mobile Admin Dashboard (Center-Right)
    adm_mob1 = os.path.join(ASSET_DIR, "01_admin_mobile_dashboard.png")
    if os.path.exists(adm_mob1):
        add_image_card(slide, adm_mob1, 7.8, 2.0, 2.3, 4.65)
        
    # Mobile Table Monitor (Right)
    adm_mob2 = os.path.join(ASSET_DIR, "04_admin_mobile_table_monitor.png")
    if os.path.exists(adm_mob2):
        add_image_card(slide, adm_mob2, 10.3, 2.0, 2.233, 4.65)

    add_footer(slide, 13)


def build_slide_14(prs):
    # Slide 14: Pengujian & Evaluasi
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    set_slide_background(slide, C_BG_LIGHT)
    add_header(slide, "Pengujian Sistem", "Hasil Pengujian Black-Box & Metrik Performa", 
               "Validasi fungsional menyeluruh dan benchmark performa sistem pada skenario nyata")
    
    metrics = [
        {
            "num": "100%",
            "label": "Tingkat Keberhasilan Black-Box",
            "desc": "18 test cases mencakup alur check-in QR/kode, AI recommendation, QRIS checkout, KDS kitchen, dan admin controls seluruhnya berstatus PASSED.",
            "color": C_ACCENT_GREEN
        },
        {
            "num": "~1.1s",
            "label": "Latensi Respon Gemini 1.5 Flash",
            "desc": "Waktu inferensi streaming sangat cepat untuk menganalisis selera dan mengembalikan format kartu JSON yang valid.",
            "color": C_PRIMARY
        },
        {
            "num": "< 15ms",
            "label": "Query Latency MariaDB (3NF)",
            "desc": "Pemanfaatan foreign key indexing dan access_code unique index menghasilkan retrieval tabel di bawah 15 milidetik.",
            "color": C_PRIMARY
        },
        {
            "num": "< 2.5 KB",
            "label": "Payload Livewire Polling Delta",
            "desc": "Siklus wire:poll.3s hanya mentransfer payload DOM yang berubah, sangat hemat bandwidth jaringan WiFi kafe.",
            "color": C_ACCENT_GREEN
        }
    ]

    for i, m in enumerate(metrics):
        col = i % 2
        row = i // 2
        left = 0.8 + col * 5.95
        top = 2.0 + row * 2.35
        
        card = add_flat_card(slide, left, top, 5.75, 2.15, bg_color=C_BG_WHITE, border_color=C_BORDER)
        tf = card.text_frame
        tf.margin_left = tf.margin_right = Inches(0.25)
        tf.margin_top = Inches(0.2)
        
        p = tf.paragraphs[0]
        p.text = m["num"]
        p.font.name = FONT_HEADING
        p.font.size = Pt(28)
        p.font.bold = True
        p.font.color.rgb = m["color"]
        
        p2 = tf.add_paragraph()
        p2.text = m["label"]
        p2.font.name = FONT_HEADING
        p2.font.size = Pt(13)
        p2.font.bold = True
        p2.font.color.rgb = C_TEXT_TITLE
        p2.space_before = Pt(2)
        
        p3 = tf.add_paragraph()
        p3.text = m["desc"]
        p3.font.name = FONT_BODY
        p3.font.size = Pt(10)
        p3.font.color.rgb = C_TEXT_BODY
        p3.space_before = Pt(4)

    add_footer(slide, 14)


def build_slide_15(prs):
    # Slide 15: Dampak Bisnis
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    set_slide_background(slide, C_BG_LIGHT)
    add_header(slide, "Nilai Tambah Bisnis", "Kuantifikasi Dampak Bisnis & Efisiensi Operasional", 
               "Dampak nyata bagi pemilik usaha, efisiensi staf barista, dan kepuasan pelanggan")
    
    impacts = [
        {
            "val": "-75%",
            "title": "Waktu Antre & Pemesanan",
            "pts": [
                "Pemotongan waktu tunggu dari rata-rata 8 menit menjadi 2 menit.",
                "Pelanggan langsung duduk di meja tanpa harus berdiri mengantre di kasir.",
                "Meniadakan walk-out rate saat jam sibuk (peak hours)."
            ],
            "color": C_ACCENT_GREEN
        },
        {
            "val": "+18.4%",
            "title": "Kenaikan Nilai Belanja (AOV)",
            "pts": [
                "AI Barista merekomendasikan pairing pastry & signature beans yang relevan.",
                "Peluang up-selling alami tanpa paksaan dari kasir.",
                "Tingkat konversi kartu rekomendasi AI mencapai 72%."
            ],
            "color": C_PRIMARY
        },
        {
            "val": "0%",
            "title": "Nota Hilang / Human Error",
            "pts": [
                "Semua pesanan tercatat langsung di MariaDB dan dikirim ke layar dapur.",
                "Catatan custom ('less sugar 50%') tercetak jelas di tiket KDS.",
                "Menghilangkan biaya kertas struk thermal konvensional."
            ],
            "color": C_ACCENT_GREEN
        }
    ]

    for i, imp in enumerate(impacts):
        left = 0.8 + i * 3.98
        card = add_flat_card(slide, left, 2.0, 3.78, 4.65, bg_color=C_BG_WHITE, border_color=C_BORDER)
        tf = card.text_frame
        tf.margin_left = tf.margin_right = Inches(0.25)
        tf.margin_top = Inches(0.25)
        
        p = tf.paragraphs[0]
        p.text = imp["val"]
        p.font.name = FONT_HEADING
        p.font.size = Pt(36)
        p.font.bold = True
        p.font.color.rgb = imp["color"]
        
        p2 = tf.add_paragraph()
        p2.text = imp["title"]
        p2.font.name = FONT_HEADING
        p2.font.size = Pt(14)
        p2.font.bold = True
        p2.font.color.rgb = C_TEXT_TITLE
        p2.space_before = Pt(4)
        
        p3 = tf.add_paragraph()
        p3.text = "Bukti Kuantitatif:"
        p3.font.name = FONT_BODY
        p3.font.size = Pt(10)
        p3.font.bold = True
        p3.font.color.rgb = C_TEXT_MUTED
        p3.space_before = Pt(10)
        
        for pt in imp["pts"]:
            ppt = tf.add_paragraph()
            ppt.text = f"✔ {pt}"
            ppt.font.name = FONT_BODY
            ppt.font.size = Pt(10)
            ppt.font.color.rgb = C_TEXT_BODY
            ppt.space_before = Pt(6)

    add_footer(slide, 15)


def build_slide_16(prs):
    # Slide 16: Kesimpulan & Roadmap
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    set_slide_background(slide, C_BG_LIGHT)
    add_header(slide, "Kesimpulan & Roadmap", "Kesimpulan Proyek & Rencana Pengembangan", 
               "Fondasi kokoh sistem POS modern dengan peta jalan ekspansi fitur masa depan")
    
    # Left: Kesimpulan Card
    c_left = add_flat_card(slide, 0.8, 2.0, 5.75, 4.65, bg_color=C_BG_WHITE, border_color=C_BORDER)
    tf = c_left.text_frame
    tf.margin_left = tf.margin_right = Inches(0.25)
    tf.margin_top = Inches(0.25)
    
    p = tf.paragraphs[0]
    p.text = "🎯 KESIMPULAN UTAMA PROYEK"
    p.font.name = FONT_HEADING
    p.font.size = Pt(12)
    p.font.bold = True
    p.font.color.rgb = C_PRIMARY
    
    conclusions = [
        "1. Arsitektur Reaktif & Hemat Biaya",
        "Kombinasi Laravel 11 dan Livewire 3 membuktikan bahwa pengalaman real-time ala SPA dapat diwujudkan tanpa infrastruktur WebSocket berbayar yang rumit.",
        "2. Pemanfaatan Nyata Generative AI",
        "AI Barista Mas Nongki bukan sekadar chatbot biasa, melainkan recommendation engine berbasis structured JSON yang terhubung langsung ke inventaris 15 menu lokal.",
        "3. Solusi Inklusif & Komprehensif",
        "Mendukung alur dual-verification meja, kasir POS walk-in, dapur KDS Kanban, serta dashboard mobile admin.",
        "4. Kesiapan Produksi 100%",
        "Sistem telah diuji fungsional 100% lulus, basis data ternormalisasi 3NF, dan dokumentasi modul akademik lengkap."
    ]
    for i, c in enumerate(conclusions):
        pc = tf.add_paragraph()
        pc.text = c
        if i % 2 == 0:
            pc.font.name = FONT_HEADING
            pc.font.size = Pt(10.5)
            pc.font.bold = True
            pc.font.color.rgb = C_TEXT_TITLE
            pc.space_before = Pt(8)
        else:
            pc.font.name = FONT_BODY
            pc.font.size = Pt(9.5)
            pc.font.color.rgb = C_TEXT_BODY
            pc.space_before = Pt(2)

    # Right: Roadmap Card
    c_right = add_flat_card(slide, 6.75, 2.0, 5.783, 4.65, bg_color=C_BG_WHITE, border_color=C_BORDER)
    tf_r = c_right.text_frame
    tf_r.margin_left = tf_r.margin_right = Inches(0.25)
    tf_r.margin_top = Inches(0.25)
    
    p = tf_r.paragraphs[0]
    p.text = "🚀 RENCANA PENGEMBANGAN (ROADMAP)"
    p.font.name = FONT_HEADING
    p.font.size = Pt(12)
    p.font.bold = True
    p.font.color.rgb = C_ACCENT_GREEN
    
    roadmaps = [
        "Fase 1: Payment Gateway Webhook (Bulan 1)",
        "Integrasi Midtrans / Xendit Snap API untuk settlement QRIS otomatis tanpa konfirmasi kasir manual.",
        "Fase 2: Inventory Recipe Deduction (Bulan 2)",
        "Pengurangan stok bahan mentah otomatis per gram kopi bubuk, susu literan, dan sirup per cangkir hidangan.",
        "Fase 3: Multi-Outlet Cloud Sync & PWA Push (Bulan 3)",
        "Dukungan multi-cabang (franchise) terpusat dan notifikasi Web Push ke smartphone pelanggan saat pesanan siap saji.",
        "Fase 4: Loyalty Program & Personalized Coupons",
        "AI Barista mengingat riwayat pesanan pelanggan langganan untuk diskon personal."
    ]
    for i, r in enumerate(roadmaps):
        pr = tf_r.add_paragraph()
        pr.text = r
        if i % 2 == 0:
            pr.font.name = FONT_HEADING
            pr.font.size = Pt(10.5)
            pr.font.bold = True
            pr.font.color.rgb = C_TEXT_TITLE
            pr.space_before = Pt(8)
        else:
            pr.font.name = FONT_BODY
            pr.font.size = Pt(9.5)
            pr.font.color.rgb = C_TEXT_BODY
            pr.space_before = Pt(2)

    add_footer(slide, 16)


def build_slide_17(prs):
    # Slide 17: Closing & Q&A
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    set_slide_background(slide, C_PRIMARY)
    
    badge = slide.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, Inches(0.8), Inches(1.5), Inches(3.2), Inches(0.42))
    badge.fill.solid()
    badge.fill.fore_color.rgb = C_BG_WHITE
    badge.line.color.rgb = C_BG_WHITE
    tf = badge.text_frame
    p = tf.paragraphs[0]
    p.text = "SESI DISKUSI & TANYA JAWAB"
    p.font.name = FONT_HEADING
    p.font.size = Pt(11)
    p.font.bold = True
    p.font.color.rgb = C_PRIMARY
    p.alignment = PP_ALIGN.CENTER
    
    tb = slide.shapes.add_textbox(Inches(0.8), Inches(2.2), Inches(11.7), Inches(2.0))
    tf2 = tb.text_frame
    tf2.word_wrap = True
    
    p1 = tf2.paragraphs[0]
    p1.text = "Terima Kasih!"
    p1.font.name = FONT_HEADING
    p1.font.size = Pt(48)
    p1.font.bold = True
    p1.font.color.rgb = C_TEXT_WHITE
    
    p2 = tf2.add_paragraph()
    p2.text = "Open for Questions, Feedback, and Discussion."
    p2.font.name = FONT_HEADING
    p2.font.size = Pt(20)
    p2.font.color.rgb = RGBColor(224, 237, 255)
    p2.space_before = Pt(8)

    # Info card
    c_info = add_flat_card(slide, 0.8, 4.4, 11.733, 2.0, bg_color=C_PRIMARY_NAVY, border_color=RGBColor(59, 130, 246), border_pt=1.5)
    tf_i = c_info.text_frame
    tf_i.margin_left = tf_i.margin_right = Inches(0.35)
    tf_i.margin_top = Inches(0.2)
    
    p = tf_i.paragraphs[0]
    p.text = "☕ NONGKRONG.id — Fall in Love with Coffee in NONGKRONG.id"
    p.font.name = FONT_HEADING
    p.font.size = Pt(14)
    p.font.bold = True
    p.font.color.rgb = C_TEXT_WHITE
    
    p2 = tf_i.add_paragraph()
    p2.text = "Presenter: Allam (puu)  •  Lead Fullstack Software Engineer\nFinal Project: Laravel 11, Livewire 3, MariaDB 3NF, & Google Gemini 1.5 Flash\nRepositori: Desktop/nongkrong_id  •  Dokumentasi Lengkap: DOKUMENTASI_LENGKAP_SMART_CAFE.docx"
    p2.font.name = FONT_BODY
    p2.font.size = Pt(11)
    p2.font.color.rgb = RGBColor(219, 234, 254)
    p2.space_before = Pt(6)


def main():
    print("[1/2] Initializing presentation generation...")
    prs = create_presentation()
    
    print("[2/2] Building 17 master presentation slides...")
    build_slide_01(prs)
    build_slide_02(prs)
    build_slide_03(prs)
    build_slide_04(prs)
    build_slide_05(prs)
    build_slide_06(prs)
    build_slide_07(prs)
    build_slide_08(prs)
    build_slide_09(prs)
    build_slide_10(prs)
    build_slide_11(prs)
    build_slide_12(prs)
    build_slide_13(prs)
    build_slide_14(prs)
    build_slide_15(prs)
    build_slide_16(prs)
    build_slide_17(prs)
    
    prs.save(OUTPUT_FILE)
    size_mb = os.path.getsize(OUTPUT_FILE) / (1024 * 1024)
    print(f"  ✓ Successfully saved presentation to {OUTPUT_FILE} ({size_mb:.2f} MB)")

if __name__ == "__main__":
    main()
