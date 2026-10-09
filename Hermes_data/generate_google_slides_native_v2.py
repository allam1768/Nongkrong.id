#!/usr/bin/env python3
"""
Revised High-Polish Google Slides Presentation Builder for NONGKRONG.id (v3)
Presentation ID: 1fXlEZ83gWbV-RqITc_XM6NAWMlIf4qihpE2Gjtit-aY

Perfections in v3:
- Symmetrical card footers (bottom badges) on all multi-column & grid slides
- Balanced vertical negative space on Slide 01 & Slide 17
- Slide 17 redesigned into 2 balanced modular cards with strict left-aligned key-value pairs
- Slide 07 balanced with paired validation badges at y=338
- Slide 11 balanced with group total badges
- Consistent, rock-solid left-aligned typography, zero clip/overflow, 100% flat design (no gradients)
"""

import os
import sys
import json
import uuid
from google.oauth2.credentials import Credentials
from google.auth.transport.requests import Request
from googleapiclient.discovery import build

TOKEN_PATH = "/home/puutraa/.hermes/google_token.json"
PRES_ID = "1fXlEZ83gWbV-RqITc_XM6NAWMlIf4qihpE2Gjtit-aY"

def get_creds():
    with open(TOKEN_PATH, "r") as f:
        data = json.load(f)
    creds = Credentials(
        token=data.get("token"),
        refresh_token=data.get("refresh_token"),
        token_uri=data.get("token_uri", "https://oauth2.googleapis.com/token"),
        client_id=data.get("client_id"),
        client_secret=data.get("client_secret"),
        scopes=data.get("scopes")
    )
    if creds.expired and creds.refresh_token:
        creds.refresh(Request())
        data["token"] = creds.token
        with open(TOKEN_PATH, "w") as f:
            json.dump(data, f, indent=2)
    return creds

def pt(p):
    return int(p * 12700)

# Color Palette - Pure Flat Aesthetics (No Gradients)
C_WHITE         = {"red": 1.0, "green": 1.0, "blue": 1.0}
C_BG_PAGE       = {"red": 0.973, "green": 0.980, "blue": 0.988}  # #F8FAFC Slate-50
C_BG_CARD       = {"red": 1.0, "green": 1.0, "blue": 1.0}        # #FFFFFF
C_PRIMARY_BLUE  = {"red": 0.0, "green": 0.290, "blue": 0.678}   # #004AAD Royal Blue
C_PRIMARY_NAVY  = {"red": 0.059, "green": 0.090, "blue": 0.165}  # #0F172A Dark Slate
C_ACCENT_BLUE   = {"red": 0.118, "green": 0.227, "blue": 0.541}  # #1E3A8A Dark Navy
C_BORDER_LIGHT  = {"red": 0.886, "green": 0.910, "blue": 0.941}  # #E2E8F0 Slate-200
C_BORDER_CARD   = {"red": 0.796, "green": 0.835, "blue": 0.882}  # #CBD5E1 Slate-300
C_TEXT_DARK     = {"red": 0.059, "green": 0.090, "blue": 0.165}  # #0F172A Slate-900
C_TEXT_BODY     = {"red": 0.200, "green": 0.255, "blue": 0.333}  # #334155 Slate-700
C_TEXT_MUTED    = {"red": 0.392, "green": 0.455, "blue": 0.545}  # #64748B Slate-500

C_GREEN         = {"red": 0.086, "green": 0.643, "blue": 0.290}  # #16A34A
C_AMBER         = {"red": 0.851, "green": 0.467, "blue": 0.024}  # #D97706
C_RED           = {"red": 0.863, "green": 0.149, "blue": 0.149}  # #DC2626

C_BLUE_SOFT_BG  = {"red": 0.937, "green": 0.965, "blue": 1.0}    # #EFF6FF
C_GREEN_SOFT_BG = {"red": 0.941, "green": 0.992, "blue": 0.957}  # #F0FDF4
C_AMBER_SOFT_BG = {"red": 1.0, "green": 0.984, "blue": 0.929}    # #FFFBEB
C_RED_SOFT_BG   = {"red": 1.0, "green": 0.949, "blue": 0.949}    # #FEF2F2

class PolishedSlideBuilder:
    def __init__(self, sid):
        self.sid = sid
        self.reqs = []
        self._count = 0

    def _id(self, prefix="el"):
        self._count += 1
        return f"{self.sid}_{prefix}_{self._count}"

    def add_shape(self, shape_type, x, y, w, h, bg_color=None, border_color=None, border_width=1, v_align="TOP"):
        oid = self._id("shp")
        self.reqs.append({
            "createShape": {
                "objectId": oid,
                "shapeType": shape_type,
                "elementProperties": {
                    "pageObjectId": self.sid,
                    "size": {"width": {"magnitude": pt(w), "unit": "EMU"}, "height": {"magnitude": pt(h), "unit": "EMU"}},
                    "transform": {"scaleX": 1, "scaleY": 1, "translateX": pt(x), "translateY": pt(y), "unit": "EMU"}
                }
            }
        })
        props = {}
        fields = []
        if bg_color:
            props["shapeBackgroundFill"] = {"solidFill": {"color": {"rgbColor": bg_color}}}
            fields.append("shapeBackgroundFill")
        else:
            props["shapeBackgroundFill"] = {"propertyState": "NOT_RENDERED"}
            fields.append("shapeBackgroundFill")

        if border_color:
            props["outline"] = {
                "outlineFill": {"solidFill": {"color": {"rgbColor": border_color}}},
                "weight": {"magnitude": pt(border_width), "unit": "EMU"},
                "dashStyle": "SOLID"
            }
            fields.append("outline")
        else:
            props["outline"] = {"propertyState": "NOT_RENDERED"}
            fields.append("outline")

        props["contentAlignment"] = v_align
        fields.append("contentAlignment")

        self.reqs.append({
            "updateShapeProperties": {
                "objectId": oid,
                "shapeProperties": props,
                "fields": ",".join(fields)
            }
        })
        return oid

    def add_card(self, x, y, w, h, bg_color=C_BG_CARD, border_color=C_BORDER_CARD, border_width=1):
        return self.add_shape("RECTANGLE", x, y, w, h, bg_color, border_color, border_width, v_align="TOP")

    def add_badge(self, x, y, w, h, text, text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=7):
        oid = self.add_shape("RECTANGLE", x, y, w, h, bg_color, border_color, border_width=1, v_align="MIDDLE")
        self.reqs.append({
            "insertText": {"objectId": oid, "text": text, "insertionIndex": 0}
        })
        self.reqs.append({
            "updateTextStyle": {
                "objectId": oid,
                "style": {"fontSize": {"magnitude": font_size, "unit": "PT"}, "bold": True, "fontFamily": "Roboto", "foregroundColor": {"opaqueColor": {"rgbColor": text_color}}},
                "fields": "fontSize,bold,fontFamily,foregroundColor"
            }
        })
        self.reqs.append({
            "updateParagraphStyle": {
                "objectId": oid,
                "style": {"alignment": "CENTER"},
                "fields": "alignment"
            }
        })
        return oid

    def add_textbox(self, x, y, w, h, text, font_size=9, bold=False, text_color=C_TEXT_BODY, bg_color=None, border_color=None, border_width=1, font_name="Roboto", align="START", v_align="TOP"):
        oid = self.add_shape("RECTANGLE", x, y, w, h, bg_color, border_color, border_width, v_align=v_align)
        if text:
            self.reqs.append({
                "insertText": {"objectId": oid, "text": text, "insertionIndex": 0}
            })
            self.reqs.append({
                "updateTextStyle": {
                    "objectId": oid,
                    "style": {
                        "fontSize": {"magnitude": font_size, "unit": "PT"},
                        "bold": bold,
                        "fontFamily": font_name,
                        "foregroundColor": {"opaqueColor": {"rgbColor": text_color}}
                    },
                    "fields": "fontSize,bold,fontFamily,foregroundColor"
                }
            })
            self.reqs.append({
                "updateParagraphStyle": {
                    "objectId": oid,
                    "style": {"alignment": align},
                    "fields": "alignment"
                }
            })
        return oid

    def setup_header(self, tag, title, subtitle):
        # Canvas 720 x 405 pt
        self.add_shape("RECTANGLE", 0, 0, 720, 405, bg_color=C_BG_PAGE, v_align="TOP")
        # Category Badge
        self.add_badge(36, 18, 175, 18, f"●  {tag.upper()}", text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=7)
        # Title (Strictly Left-aligned)
        self.add_textbox(36, 40, 648, 24, title, font_size=15, bold=True, text_color=C_TEXT_DARK, align="START", v_align="TOP")
        # Subtitle (Strictly Left-aligned)
        self.add_textbox(36, 65, 648, 16, subtitle, font_size=8.5, bold=False, text_color=C_TEXT_MUTED, align="START", v_align="TOP")
        # Footer
        self.add_textbox(36, 382, 350, 15, "NONGKRONG.id • Smart Cafe POS & Self-Ordering System", font_size=7.5, text_color=C_TEXT_MUTED, align="START", v_align="TOP")
        self.add_textbox(470, 382, 214, 15, "Pengembang: Allam (puu)", font_size=7.5, text_color=C_TEXT_MUTED, align="END", v_align="TOP")


def build_and_push_deck():
    creds = get_creds()
    slides_service = build("slides", "v1", credentials=creds)

    print(f"Connecting to presentation {PRES_ID}...")
    pres = slides_service.presentations().get(presentationId=PRES_ID).execute()
    old_slides = pres.get("slides", [])

    # Create 17 new polished slides
    slide_ids = [f"rev3_slide_{i}_{uuid.uuid4().hex[:6]}" for i in range(1, 18)]
    create_reqs = []
    for sid in slide_ids:
        create_reqs.append({
            "createSlide": {
                "objectId": sid,
                "slideLayoutReference": {"predefinedLayout": "BLANK"}
            }
        })
    slides_service.presentations().batchUpdate(presentationId=PRES_ID, body={"requests": create_reqs}).execute()
    print("17 new blank slides created.")

    # Delete old slides
    delete_reqs = [{"deleteObject": {"objectId": s["objectId"]}} for s in old_slides]
    if delete_reqs:
        slides_service.presentations().batchUpdate(presentationId=PRES_ID, body={"requests": delete_reqs}).execute()
        print(f"{len(delete_reqs)} old slides removed.")

    builders = []

    # ==========================================================
    # SLIDE 1: COVER (Refined & Balanced)
    # ==========================================================
    s1 = PolishedSlideBuilder(slide_ids[0])
    s1.add_shape("RECTANGLE", 0, 0, 720, 405, bg_color=C_PRIMARY_BLUE, v_align="TOP")
    s1.add_badge(36, 28, 260, 20, "★  FINAL PROJECT LARAVEL FULLSTACK • 2026", text_color=C_PRIMARY_BLUE, bg_color=C_WHITE, border_color=C_WHITE, font_size=7.5)
    s1.add_textbox(36, 54, 648, 48, "NONGKRONG.id", font_size=36, bold=True, text_color=C_WHITE, align="START", v_align="TOP")
    s1.add_textbox(36, 106, 648, 30, "Smart Cafe POS & Self-Ordering System dengan Integrasi AI Barista Mas Nongki (Gemini 1.5 Flash), Kitchen Display System, dan Multi-Platform Responsive Portal", font_size=9.5, bold=False, text_color={"red": 0.88, "green": 0.93, "blue": 1.0}, align="START", v_align="TOP")

    cards_meta = [
        ("👤 PENGEMBANG SISTEM", "Allam (puu)", "Lead Fullstack Engineer\nJurusan Software Engineering\nFokus: System Architecture & DB 3NF\nTanggung Jawab: Full Lifecycle Dev", "✓ Sidang Tugas Akhir 2026"),
        ("⚡ ARSITEKTUR UTAMA", "Modern Reactive Stack", "• Laravel 11 / 12 & Livewire 3.0\n• Google Gemini 1.5 Flash API\n• MariaDB 10.11+ Relational Engine\n• Tailwind CSS & Alpine.js Flat", "✓ Production-Ready Engine"),
        ("🎯 FOKUS SOLUSI", "Ekosistem Terintegrasi", "• Dual Check-in Meja (QR + 5-Box)\n• Realtime KDS Kanban Dapur\n• Executive Mobile Owner Monitor\n• 22 Layar UI Responsif Flat", "✓ Zero Paper Waste POS")
    ]
    card_w = 205
    c_h = 195
    for i, (badge_c, title_c, desc_c, foot_c) in enumerate(cards_meta):
        cx = 36 + i * (card_w + 16)
        s1.add_card(cx, 146, card_w, c_h, bg_color=C_WHITE, border_color=C_BORDER_CARD, border_width=1)
        s1.add_badge(cx + 10, 156, card_w - 20, 18, badge_c, text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=7)
        s1.add_textbox(cx + 10, 178, card_w - 20, 18, title_c, font_size=10.5, bold=True, text_color=C_TEXT_DARK, align="START", v_align="TOP")
        s1.add_textbox(cx + 10, 198, card_w - 20, 105, desc_c, font_size=8, bold=False, text_color=C_TEXT_BODY, align="START", v_align="TOP")
        s1.add_badge(cx + 10, 312, card_w - 20, 18, foot_c, text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=6.5)

    s1.add_textbox(36, 362, 648, 18, "Dokumentasi & Presentasi Resmi Sidang Tugas Akhir Pengembangan Perangkat Lunak", font_size=8, text_color={"red": 0.82, "green": 0.89, "blue": 1.0}, align="START", v_align="TOP")
    builders.append(s1)

    # ==========================================================
    # SLIDE 2: PROBLEM STATEMENT (2x2 Grid)
    # ==========================================================
    s2 = PolishedSlideBuilder(slide_ids[1])
    s2.setup_header("Latar Belakang & Permasalahan", "Tantangan & Bottleneck Operasional Kafe Modern", "Analisis permasalahan faktual pada alur pemesanan konvensional di industri Food & Beverage")
    problems = [
        ("MASALAH 01", "Antrean Kasir di Jam Sibuk (Peak Hours)", "Pelanggan harus berdiri mengantre 8-15 menit di kasir pada jam 14:00-18:00 saat pesanan menumpuk.", "⚠️ Dampak: Walk-out rate mencapai 12%"),
        ("MASALAH 02", "Decision Paralysis & Menu Statis", "Buku menu fisik konvensional tidak memberikan interaktivitas. Pelanggan bingung memilih profil rasa kopi tanpa panduan rekomendasi yang personal.", "⚠️ Dampak: Waktu order lama & konversi rendah"),
        ("MASALAH 03", "Miskomunikasi Pesanan & Tiket Kertas", "Nota manual tulisan tangan rentan hilang, salah catat request khusus (misal 'less sugar 50%'), dan tertukar nomor meja.", "⚠️ Dampak: Kesalahan saji mencapai 7%"),
        ("MASALAH 04", "Keterbatasan Mobilitas Pemilik Kafe", "Sistem kasir konvensional terisolasi di PC desktop kasir. Cafe owner tidak bisa memantau perputaran omzet dan stok kritis secara real-time via smartphone.", "⚠️ Dampak: Kontrol operasional lambat")
    ]
    p_w, p_h = 314, 130
    positions_2x2 = [(36, 92), (370, 92), (36, 236), (370, 236)]
    for (badge_p, tit_p, desc_p, imp_p), (px, py) in zip(problems, positions_2x2):
        s2.add_card(px, py, p_w, p_h, bg_color=C_BG_CARD, border_color=C_BORDER_CARD, border_width=1)
        s2.add_badge(px + 12, py + 10, 80, 16, badge_p, text_color=C_RED, bg_color=C_RED_SOFT_BG, border_color={"red": 0.99, "green": 0.8, "blue": 0.8}, font_size=6.5)
        s2.add_textbox(px + 12, py + 30, p_w - 24, 20, tit_p, font_size=10, bold=True, text_color=C_TEXT_DARK, align="START", v_align="TOP")
        s2.add_textbox(px + 12, py + 52, p_w - 24, 48, desc_p, font_size=8, bold=False, text_color=C_TEXT_BODY, align="START", v_align="TOP")
        s2.add_badge(px + 12, py + 102, p_w - 24, 18, imp_p, text_color=C_RED, bg_color=C_RED_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=7)
    builders.append(s2)

    # ==========================================================
    # SLIDE 3: SOLUSI SISTEM (4 Kolom)
    # ==========================================================
    s3 = PolishedSlideBuilder(slide_ids[2])
    s3.setup_header("Solusi Sistem & UVP", "Ekosistem Self-Ordering Cerdas & Omnichannel POS", "Transformasi digital menyeluruh yang mengintegrasikan tamu, barista, kasir, dan pemilik")
    solutions = [
        ("SOLUSI 01", "Dual-Verification Table Check-in", "Scan QR kamera atau input kode alfanumerik 5-karakter meja (misal: 7K92A) tanpa unduh aplikasi.", "✓ Cepat & Inklusif", C_BLUE_SOFT_BG, C_PRIMARY_BLUE),
        ("SOLUSI 02", "AI Barista Mas Nongki", "Konsultasi preferensi rasa dua arah berbasis Natural Language Processing terhubung 15 menu lokal.", "✓ Rekomendasi Akurat", C_BLUE_SOFT_BG, C_PRIMARY_BLUE),
        ("SOLUSI 03", "Realtime KDS Kanban", "Sinkronisasi antrean dapur instan bertenaga Livewire 3 wire:poll.3s tanpa websocket berbayar.", "✓ Zero Paper Waste", C_GREEN_SOFT_BG, C_GREEN),
        ("SOLUSI 04", "Omnichannel Multi-UI", "Antarmuka terpisah & responsif: Mobile PWA, Widescreen Desktop, Touch POS, & Executive Admin.", "✓ 22 Layar Terintegrasi", C_AMBER_SOFT_BG, C_AMBER)
    ]
    c_w = 153
    for i, (badge_s, tit_s, desc_s, tag_s, bg_sb, col_sb) in enumerate(solutions):
        sx = 36 + i * (c_w + 12)
        s3.add_card(sx, 92, c_w, 275, bg_color=C_BG_CARD, border_color=C_BORDER_CARD, border_width=1)
        s3.add_badge(sx + 10, 104, c_w - 20, 18, badge_s, text_color=col_sb, bg_color=bg_sb, border_color=C_BORDER_LIGHT, font_size=7)
        s3.add_textbox(sx + 10, 128, c_w - 20, 36, tit_s, font_size=10, bold=True, text_color=C_TEXT_DARK, align="START", v_align="TOP")
        s3.add_textbox(sx + 10, 168, c_w - 20, 155, desc_s, font_size=8, bold=False, text_color=C_TEXT_BODY, align="START", v_align="TOP")
        s3.add_badge(sx + 10, 338, c_w - 20, 20, tag_s, text_color=col_sb, bg_color=bg_sb, border_color=C_BORDER_LIGHT, font_size=7)
    builders.append(s3)

    # ==========================================================
    # SLIDE 4: ARSITEKTUR TEKNOLOGI (2x2 Grid)
    # ==========================================================
    s4 = PolishedSlideBuilder(slide_ids[3])
    s4.setup_header("Arsitektur Teknologi", "Modern Fullstack Stack: Cepat, Reaktif, & Skalabel", "Kombinasi teknologi mutakhir untuk performa tinggi dengan arsitektur elegan")
    tech_stacks = [
        ("BACKEND ENGINE", "Laravel 11 / 12 & PHP 8.5", "• RESTful Routing & Model-View-Controller\n• Eloquent ORM dengan Relasi Kompleks 3NF\n• Form Request Validation & Database Seeder\n• Session & File Cache Driver Terisolasi", "✓ MVC & RESTful Engine"),
        ("REACTIVE FRONTEND", "Livewire 3 + Alpine.js + Tailwind", "• DOM Morphing Reaktif tanpa Build JS Berat\n• wire:poll.3s Sinkronisasi Data Realtime\n• Pure Flat Design Palette (Solid Royal Blue)\n• Full Responsive: Mobile 414px & Desktop 1440px", "✓ Reaktif Tanpa Build JS"),
        ("DATABASE ENGINE", "MariaDB 10.11+ Relational Engine", "• 7 Tabel Relasional Normalisasi Penuh 3NF\n• Foreign Key Cascading & Data Integrity\n• Indexing Cepat access_code & qr_token\n• Optimized Query Execution Latency < 15ms", "✓ Normalisasi Penuh 3NF"),
        ("GENERATIVE AI", "Google Gemini 1.5 Flash API", "• Model Gemini 1.5 Flash Latensi Ultra Cepat\n• System Prompt: Persona Mas Nongki Barista\n• JSON Structured Output (Match Rate & Menu ID)\n• Context Injection 15 Menu Lokal MariaDB", "✓ Latensi Cepat ~1.1s")
    ]
    for (badge_t, tit_t, pts_t, foot_t), (tx, ty) in zip(tech_stacks, positions_2x2):
        s4.add_card(tx, ty, p_w, p_h, bg_color=C_BG_CARD, border_color=C_BORDER_CARD, border_width=1)
        s4.add_badge(tx + 12, ty + 10, 130, 16, badge_t, text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=6.5)
        s4.add_textbox(tx + 12, ty + 28, p_w - 24, 18, tit_t, font_size=9.5, bold=True, text_color=C_TEXT_DARK, align="START", v_align="TOP")
        s4.add_textbox(tx + 12, ty + 46, p_w - 24, 56, pts_t, font_size=7.5, bold=False, text_color=C_TEXT_BODY, align="START", v_align="TOP")
        s4.add_badge(tx + 12, ty + 104, p_w - 24, 16, foot_t, text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=6.5)
    builders.append(s4)

    # ==========================================================
    # SLIDE 5: 3 PERAN PENGGUNA (RBAC) (3 Kolom)
    # ==========================================================
    s5 = PolishedSlideBuilder(slide_ids[4])
    s5.setup_header("Desain Pengguna", "Struktur Hak Akses & Tiga Peran Pengguna (RBAC)", "Pemisahan tanggung jawab fungsional yang tegas demi keamanan operasional kafe")
    roles = [
        ("ROLE 1", "PELANGGAN (CUSTOMER)", "Platform: Mobile Web & Desktop Portal", [
            "Check-in meja via QR scan kamera atau kode 5-box",
            "Jelajahi 15 menu dengan filter kategori sticky",
            "Konsultasi rasa interaktif via Mas Nongki AI Barista",
            "Kelola keranjang & perhitungan pajak PB1 10%",
            "Checkout QRIS dinamis & live order status tracking"
        ], "Hak Akses: Sesi Meja Publik", C_BLUE_SOFT_BG, C_PRIMARY_BLUE),
        ("ROLE 2", "STAFF KASIR & BARISTA", "Platform: Desktop Touch POS & KDS Tablet", [
            "Input transaksi walk-in order di kasir fisik",
            "Keypad tunai cepat, preset nominal, hitung kembalian",
            "Kanban Board Dapur: Pesanan Baru -> Diracik -> Siap",
            "Livewire auto-shift kartu pesanan saat status update",
            "Pencetakan struk transaksi nota kasir instan"
        ], "Hak Akses: Operasional Lantai & Dapur", C_GREEN_SOFT_BG, C_GREEN),
        ("ROLE 3", "ADMIN & CAFE OWNER", "Platform: Executive Analytics & Mobile App", [
            "Ringkasan omzet harian, transaksi, dan AOV kafe",
            "Grafik tren jam sibuk (heatmap hourly traffic)",
            "CRUD 15 Menu: Harga, deskripsi, rasa, & stok switch",
            "Tuning System Prompt Mas Nongki & Sandbox AI",
            "Mobile floor table monitor & toggle ketersediaan meja"
        ], "Hak Akses: Full Privileges & Master Data", C_AMBER_SOFT_BG, C_AMBER)
    ]
    r_w = 205
    for i, (badge_r, tit_r, plat_r, duties_r, acc_r, bg_rb, col_rb) in enumerate(roles):
        rx = 36 + i * (r_w + 16)
        s5.add_card(rx, 92, r_w, 275, bg_color=C_BG_CARD, border_color=C_BORDER_CARD, border_width=1)
        s5.add_badge(rx + 10, 102, 65, 16, badge_r, text_color=col_rb, bg_color=bg_rb, border_color=C_BORDER_LIGHT, font_size=7)
        s5.add_textbox(rx + 10, 122, r_w - 20, 18, tit_r, font_size=9.5, bold=True, text_color=C_TEXT_DARK, align="START", v_align="TOP")
        s5.add_textbox(rx + 10, 140, r_w - 20, 14, plat_r, font_size=7, bold=False, text_color=C_TEXT_MUTED, align="START", v_align="TOP")
        d_text = "\n\n".join([f"• {d}" for d in duties_r])
        s5.add_textbox(rx + 10, 158, r_w - 20, 170, d_text, font_size=7.5, bold=False, text_color=C_TEXT_BODY, align="START", v_align="TOP")
        s5.add_badge(rx + 10, 338, r_w - 20, 18, acc_r, text_color=col_rb, bg_color=bg_rb, border_color=C_BORDER_LIGHT, font_size=6.5)
    builders.append(s5)

    # ==========================================================
    # SLIDE 6: FLOWCHART ANSI/ISO 5807 (6 Tahapan / 2x3 Grid)
    # ==========================================================
    s6 = PolishedSlideBuilder(slide_ids[5])
    s6.setup_header("Diagram Alir Sistem", "Alur Proses Bisnis End-to-End (ANSI/ISO 5807)", "Sinkronisasi otomatis antara alur pelanggan (Customer), barista (Kitchen), dan database")
    steps_flow = [
        ("TAHAP 01", "Verifikasi Meja", "Tamu datang ke meja fisik kafe, scan QR code atau input kode alfanumerik 5-karakter meja (misal: 7K92A) untuk membuka sesi.", "Swimlane: Customer"),
        ("TAHAP 02", "Eksplorasi Menu", "Tamu dapat memilih menu secara mandiri lewat katalog visual atau berkonsultasi rasa dengan Mas Nongki AI Barista.", "Swimlane: AI Barista"),
        ("TAHAP 03", "Order & QRIS", "Tamu meninjau keranjang Livewire, sistem menghitung PB1 10%, memilih metode QRIS, dan menekan Bayar Sekarang.", "Swimlane: Customer"),
        ("TAHAP 04", "Dispatch Dapur", "Database MariaDB mencatat order_number (#ORD-XXX) dan Livewire me-refresh kolom 'Pesanan Baru' pada layar KDS Dapur.", "Swimlane: System / KDS"),
        ("TAHAP 05", "Peracikan Menu", "Barista menekan 'Mulai Racik' (status dipindah ke 'Sedang Diramu'). Pelanggan melihat progress live tracking bergerak.", "Swimlane: Kitchen Staff"),
        ("TAHAP 06", "Sajian Tiba", "Barista menekan 'Siap Saji'. Waiter mengantar ke nomor meja. Status pesanan selesai dan sesi meja ditutup.", "Swimlane: Service / Order Done")
    ]
    st_w, st_h = 205, 130
    positions_2x3 = [
        (36, 92), (257, 92), (478, 92),
        (36, 236), (257, 236), (478, 236)
    ]
    for (badge_st, tit_st, desc_st, swm_st), (sx, sy) in zip(steps_flow, positions_2x3):
        s6.add_card(sx, sy, st_w, st_h, bg_color=C_BG_CARD, border_color=C_BORDER_CARD, border_width=1)
        s6.add_badge(sx + 10, sy + 10, 70, 16, badge_st, text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=6.5)
        s6.add_textbox(sx + 10, sy + 28, st_w - 20, 18, tit_st, font_size=9.5, bold=True, text_color=C_TEXT_DARK, align="START", v_align="TOP")
        s6.add_textbox(sx + 10, sy + 48, st_w - 20, 52, desc_st, font_size=7.5, bold=False, text_color=C_TEXT_BODY, align="START", v_align="TOP")
        s6.add_badge(sx + 10, sy + 104, st_w - 20, 16, swm_st, text_color=C_TEXT_MUTED, bg_color=C_BG_PAGE, border_color=C_BORDER_LIGHT, font_size=6.5)
    builders.append(s6)

    # ==========================================================
    # SLIDE 7: BASIS DATA RELASIONAL 3NF (2 Kolom - Perfectly Balanced)
    # ==========================================================
    s7 = PolishedSlideBuilder(slide_ids[6])
    s7.setup_header("Basis Data & Skema Relasional", "Perancangan Basis Data 3NF & Kamus Data Relasional", "Normalisasi penuh 3NF memastikan data transaksi akurat, cepat, dan bebas anomali")
    col_w = 314
    s7.add_card(36, 92, col_w, 275, bg_color=C_BG_CARD, border_color=C_BORDER_CARD, border_width=1)
    s7.add_badge(48, 104, 180, 18, "KAMUS DATA 7 TABEL UTAMA (3NF)", text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=7)
    s7.add_textbox(48, 126, col_w - 24, 18, "Spesifikasi Entitas Basis Data", font_size=10, bold=True, text_color=C_TEXT_DARK, align="START", v_align="TOP")
    tables_text = (
        "• USERS: RBAC Auth (admin, staff, customer), email unik.\n\n"
        "• TABLES: table_number, access_code unik (5-karakter), status.\n\n"
        "• CATEGORIES: Pengelompokan Coffee, Non-Coffee, Meals, Snacks.\n\n"
        "• MENUS: Relasi category_id, name, price, stock, taste_profile.\n\n"
        "• ORDERS: Relasi user_id, table_id, order_number unik, total.\n\n"
        "• ORDER_ITEMS: Relasi order_id, menu_id, quantity, unit_price, subtotal.\n\n"
        "• AI_CONSULTATIONS: Log session_id, user_prompt, recommendations JSON."
    )
    s7.add_textbox(48, 148, col_w - 24, 180, tables_text, font_size=7.5, bold=False, text_color=C_TEXT_BODY, align="START", v_align="TOP")
    s7.add_badge(48, 338, col_w - 24, 18, "✓ 7 Entitas Normalisasi Penuh Bebas Anomali", text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=7)

    s7.add_card(370, 92, col_w, 275, bg_color=C_BG_CARD, border_color=C_BORDER_CARD, border_width=1)
    s7.add_badge(382, 104, 200, 18, "KARAKTERISTIK INTEGRITAS RELASI", text_color=C_TEXT_DARK, bg_color=C_BG_PAGE, border_color=C_BORDER_LIGHT, font_size=7)
    s7.add_textbox(382, 126, col_w - 24, 18, "Jaminan Integritas & Bebas Anomali", font_size=10, bold=True, text_color=C_TEXT_DARK, align="START", v_align="TOP")
    db_points = (
        "1. Pembuktian Normalisasi Ketiga (3NF):\n"
        "Tidak ada ketergantungan transitif. Kolom non-kunci hanya bergantung secara penuh pada Primary Key masing-masing tabel.\n\n"
        "2. Foreign Key Cascading & Restrict:\n"
        "order_items berelasi erat ke orders (cascade delete) dan menus (restrict delete untuk menjaga riwayat transaksi historis).\n\n"
        "3. Snapshot Immutability Transaksi:\n"
        "Kolom unit_price pada order_items mencatat harga saat transaksi berlangsung, mencegah perubahan omzet jika harga menu diupdate di masa depan.\n\n"
        "4. Diagram Visual ERD:\n"
        "Tersedia berkas diagram ERD Crow's Foot resmi di direktori proyek: erd_smart_cafe.png."
    )
    s7.add_textbox(382, 148, col_w - 24, 180, db_points, font_size=7.5, bold=False, text_color=C_TEXT_BODY, align="START", v_align="TOP")
    s7.add_badge(382, 338, col_w - 24, 18, "✓ Integritas Relasi & Immutability Terjamin", text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=7)
    builders.append(s7)

    # ==========================================================
    # SLIDE 8: FITUR 1 - CHECK IN MEJA (2 Kolom)
    # ==========================================================
    s8 = PolishedSlideBuilder(slide_ids[7])
    s8.setup_header("Fitur Unggulan 1", "Dual-Verification Table Check-In (QR & 5-Box Code)", "Solusi inklusif mengatasi kendala kamera rusak atau pemesanan lewat laptop")
    f1_cards = [
        ("MEKANISME VERIFIKASI", "Metode Ganda Check-in Meja", [
            "Jalur 1: QR Code Scanner via kamera HP langsung membuka sesi meja.",
            "Jalur 2: Input 5-Box kode unik alfanumerik (7K92A, 3X81B, 8Y24C, 6W15D, 5V39E, 4U72F).",
            "Auto-binding: Mengunci nomor meja ke sesi pemesanan tanpa risiko order salah antar.",
            "Zero Installation: Tanpa perlu install aplikasi Play Store / App Store."
        ], "✓ Dual Verification QR & 5-Box Code"),
        ("PENCEGAHAN HUMAN ERROR", "Keamanan Sesi & Validasi", [
            "Mencegah pelanggan memesan dari luar radius kafe tanpa kode meja fisik.",
            "Validasi database instan: Meja berstatus 'occupied' menolak sesi baru yang bentrok.",
            "Visual feedback jelas: status meja, kapasitas kursi, dan akses menu langsung aktif.",
            "Mendukung 22 layar responsif (Mobile 414px & Desktop 1440px)."
        ], "✓ Sesi Meja Terisolasi & Aman")
    ]
    for i, (tag_f, tit_f, pts_f, foot_f) in enumerate(f1_cards):
        cx = 36 + i * (col_w + 20)
        s8.add_card(cx, 92, col_w, 275, bg_color=C_BG_CARD, border_color=C_BORDER_CARD, border_width=1)
        s8.add_badge(cx + 12, 104, 150, 18, tag_f, text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=7)
        s8.add_textbox(cx + 12, 126, col_w - 24, 18, tit_f, font_size=10, bold=True, text_color=C_TEXT_DARK, align="START", v_align="TOP")
        pts_text = "\n\n".join([f"• {p}" for p in pts_f])
        s8.add_textbox(cx + 12, 148, col_w - 24, 180, pts_text, font_size=8, bold=False, text_color=C_TEXT_BODY, align="START", v_align="TOP")
        s8.add_badge(cx + 12, 338, col_w - 24, 18, foot_f, text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=7)
    builders.append(s8)

    # ==========================================================
    # SLIDE 9: FITUR 2 - AI BARISTA MAS NONGKI (2 Kolom)
    # ==========================================================
    s9 = PolishedSlideBuilder(slide_ids[8])
    s9.setup_header("Fitur Unggulan 2", "AI Barista Mas Nongki: Rekomendasi Rasa Personal", "Integrasi Gemini 1.5 Flash untuk konsultasi preferensi selera kopi pelanggan")
    f2_cards = [
        ("SISTEM PROMPTING & NLP", "Persona Barista Lokal & Context Injection", [
            "Persona: Ramah, santai khas barista lokal, memahami profil rasa kopi.",
            "Context Injection: Menyuntikkan seluruh 15 menu aktif MariaDB ke system prompt Gemini.",
            "Analisis Rasa: Memetakan acidity, sweetness, bitterness, dan tingkat ketahanan kafein.",
            "Latensi Sangat Cepat: Rata-rata respon ~1.1 detik dengan Google Gemini 1.5 Flash."
        ], "✓ Konteks 15 Menu Lokal Terinjeksi"),
        ("STRUCTURED OUTPUT", "Konversi Rekomendasi Menjadi Penjualan", [
            "JSON Schema Terstruktur: Output dikembalikan dalam format data siap-render.",
            "Kartu Interaktif: Menampilkan persentase kecocokan rasa (Match Rate: 98%).",
            "1-Klik Order: Tombol 'Tambah ke Pesanan' langsung menginjeksi menu_id ke Livewire cart.",
            "Logging Riwayat: Disimpan di tabel ai_consultations untuk audit preferensi rasa kafe."
        ], "✓ Konversi Pesanan 1-Klik Instan")
    ]
    for i, (tag_f, tit_f, pts_f, foot_f) in enumerate(f2_cards):
        cx = 36 + i * (col_w + 20)
        s9.add_card(cx, 92, col_w, 275, bg_color=C_BG_CARD, border_color=C_BORDER_CARD, border_width=1)
        s9.add_badge(cx + 12, 104, 160, 18, tag_f, text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=7)
        s9.add_textbox(cx + 12, 126, col_w - 24, 18, tit_f, font_size=10, bold=True, text_color=C_TEXT_DARK, align="START", v_align="TOP")
        pts_text = "\n\n".join([f"• {p}" for p in pts_f])
        s9.add_textbox(cx + 12, 148, col_w - 24, 180, pts_text, font_size=8, bold=False, text_color=C_TEXT_BODY, align="START", v_align="TOP")
        s9.add_badge(cx + 12, 338, col_w - 24, 18, foot_f, text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=7)
    builders.append(s9)

    # ==========================================================
    # SLIDE 10: FITUR 3 - KDS & REALTIME TRACKING (2 Kolom)
    # ==========================================================
    s10 = PolishedSlideBuilder(slide_ids[9])
    s10.setup_header("Fitur Unggulan 3", "Kitchen Display System (KDS) & Realtime Tracking", "Eliminasi nota kertas dan transparansi status sajian langsung di meja tamu")
    f3_cards = [
        ("OPERASIONAL DAPUR", "Kanban Board Dapur Terotomasi", [
            "3 Kolom Kanban: Pesanan Baru Masuk -> Sedang Diramu -> Siap Disajikan.",
            "Sinkronisasi wire:poll.3s: Kartu tiket otomatis muncul begitu transaksi selesai dibayar.",
            "Detail Tiket Lengkap: Nomor Meja, Kode Order (#ORD-XXX), list item, dan request catatan khusus.",
            "Zero Paper Waste: Menghemat ribuan lembar kertas struk thermal dapur per bulan."
        ], "✓ Dapur Bebas Kertas & Terorganisasi"),
        ("TRANSPARANSI PELANGGAN", "Live Order Status Tracking di Meja", [
            "Stepper Progres 4 Tahap: Diterima -> Diracik -> Siap Antar -> Selesai.",
            "Estimasi Waktu Penyajian: Indikator waktu real-time (~5-8 menit per racikan).",
            "Bebas Rasa Cemas: Pelanggan tidak perlu berulang kali bertanya ke barista.",
            "Tombol Panggil Waiter: Fitur bantuan sekali klik jika tamu memerlukan sesuatu."
        ], "✓ Kepuasan Tamu Meningkat Signifikan")
    ]
    for i, (tag_f, tit_f, pts_f, foot_f) in enumerate(f3_cards):
        cx = 36 + i * (col_w + 20)
        s10.add_card(cx, 92, col_w, 275, bg_color=C_BG_CARD, border_color=C_BORDER_CARD, border_width=1)
        s10.add_badge(cx + 12, 104, 150, 18, tag_f, text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=7)
        s10.add_textbox(cx + 12, 126, col_w - 24, 18, tit_f, font_size=10, bold=True, text_color=C_TEXT_DARK, align="START", v_align="TOP")
        pts_text = "\n\n".join([f"• {p}" for p in pts_f])
        s10.add_textbox(cx + 12, 148, col_w - 24, 180, pts_text, font_size=8, bold=False, text_color=C_TEXT_BODY, align="START", v_align="TOP")
        s10.add_badge(cx + 12, 338, col_w - 24, 18, foot_f, text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=7)
    builders.append(s10)

    # ==========================================================
    # SLIDE 11: REKAPITULASI 22 LAYAR UI (3 Kolom - Balanced)
    # ==========================================================
    s11 = PolishedSlideBuilder(slide_ids[10])
    s11.setup_header("Arsitektur Antarmuka", "Koleksi 22 Layar UI Mockup: Komprehensif & Responsif", "Seluruh antarmuka dirancang dengan Pure Flat Design tanpa gradien")
    ui_groups = [
        ("ROLE 1 • MOBILE PELANGGAN", "7 Layar Antarmuka", [
            "01_splash_screen.png — Splash identitas kafe",
            "02_onboarding.png — 3 pilar value kafe",
            "03_table_checkin.png — Check-in QR & 5-box code",
            "04_customer_catalog.png — Katalog menu mobile",
            "05_ai_consultation.png — Chat Mas Nongki AI",
            "06_checkout_qris.png — QRIS settlement & PB1 10%",
            "07_order_tracking.png — Live stepper tracking"
        ], "Total: 7 Layar Mobile"),
        ("ROLE 1 • WEB DESKTOP PELANGGAN", "6 Layar Antarmuka", [
            "01_web_portal_welcome.png — Hero welcome",
            "02_web_table_checkin.png — Split view QR & 5-box",
            "03_web_customer_catalog.png — Widescreen 3-kolom",
            "04_web_ai_barista.png — Drawer Mas Nongki AI",
            "05_web_checkout_qris.png — QRIS modal payment",
            "06_web_order_tracking.png — Horizontal tracking"
        ], "Total: 6 Layar Desktop"),
        ("ROLE 2 & 3 • POS, KDS & ADMIN", "9 Layar Antarmuka", [
            "08_pos_kanban.png — Kitchen Kanban 3 Kolom",
            "09_pos_walkin.png — Touchscreen POS kasir fisik",
            "10_admin_analytics.png — Dashboard omzet & jam",
            "11_admin_menu_crud.png — Manajemen 15 menu",
            "12_admin_ai_config.png — Prompt tuning AI",
            "01-04_admin_mobile_*.png — 4 Layar Mobile Admin"
        ], "Total: 9 Layar Operasional")
    ]
    for i, (grp_b, grp_t, scrs, tot_b) in enumerate(ui_groups):
        ux = 36 + i * (r_w + 16)
        s11.add_card(ux, 92, r_w, 275, bg_color=C_BG_CARD, border_color=C_BORDER_CARD, border_width=1)
        s11.add_badge(ux + 10, 104, r_w - 20, 18, grp_b, text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=7)
        s11.add_textbox(ux + 10, 126, r_w - 20, 18, grp_t, font_size=9.5, bold=True, text_color=C_TEXT_DARK, align="START", v_align="TOP")
        scr_text = "\n\n".join([f"• {s}" for s in scrs])
        s11.add_textbox(ux + 10, 148, r_w - 20, 180, scr_text, font_size=7, bold=False, text_color=C_TEXT_BODY, align="START", v_align="TOP")
        s11.add_badge(ux + 10, 338, r_w - 20, 18, tot_b, text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=7)
    builders.append(s11)

    # ==========================================================
    # SLIDE 12: KASIR POS WALKIN (2 Kolom)
    # ==========================================================
    s12 = PolishedSlideBuilder(slide_ids[11])
    s12.setup_header("Antarmuka Pengguna: Kasir POS", "Kasir POS Fisik: Penanganan Cepat Pesanan Walk-In", "Mendukung pelanggan yang memesan langsung tanpa smartphone dengan input touch-screen cepat")
    pos_cards = [
        ("INPUT CEPAT TOUCH-SCREEN", "Keypad Nominal & Hitung Kembalian Otomatis", [
            "Mendukung pesanan Dine-In maupun Takeaway di meja kasir.",
            "Touch-screen catalog grid dengan filter kategori instan.",
            "NumPad nominal uang tunai cepat (Rp 50.000, Rp 100.000, atau Uang Pas).",
            "Kalkulasi kembalian otomatis bebas risiko salah hitung kasir."
        ], "✓ Input Cepat & Kalkulasi Presisi"),
        ("INTEGRASI TIKET LANGSUNG", "Injeksi Tiket Instan ke Kitchen Display System", [
            "Seketika transaksi walk-in selesai, tiket pesanan langsung terbit.",
            "Livewire event mentransmisikan data ke layar KDS Barista secara real-time.",
            "Pencetakan struk pembayaran resmi dengan detail pajak restoran PB1 10%.",
            "Mendukung multi-tender pembayaran: Tunai, Debit, Kartu Kredit, atau QRIS Kasir."
        ], "✓ Sinkronisasi Dapur Tanpa Delay")
    ]
    for i, (tag_f, tit_f, pts_f, foot_f) in enumerate(pos_cards):
        cx = 36 + i * (col_w + 20)
        s12.add_card(cx, 92, col_w, 275, bg_color=C_BG_CARD, border_color=C_BORDER_CARD, border_width=1)
        s12.add_badge(cx + 12, 104, 160, 18, tag_f, text_color=C_GREEN, bg_color=C_GREEN_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=7)
        s12.add_textbox(cx + 12, 126, col_w - 24, 18, tit_f, font_size=10, bold=True, text_color=C_TEXT_DARK, align="START", v_align="TOP")
        pts_text = "\n\n".join([f"• {p}" for p in pts_f])
        s12.add_textbox(cx + 12, 148, col_w - 24, 180, pts_text, font_size=8, bold=False, text_color=C_TEXT_BODY, align="START", v_align="TOP")
        s12.add_badge(cx + 12, 338, col_w - 24, 18, foot_f, text_color=C_GREEN, bg_color=C_GREEN_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=7)
    builders.append(s12)

    # ==========================================================
    # SLIDE 13: EXECUTIVE ADMIN & OWNER (2 Kolom)
    # ==========================================================
    s13 = PolishedSlideBuilder(slide_ids[12])
    s13.setup_header("Antarmuka Pengguna: Admin & Owner", "Executive Dashboard: Pantau Bisnis Kapan Saja", "Fleksibilitas ganda: Analisis mendalam di PC Desktop dan kontrol lantai via Mobile")
    adm_cards = [
        ("DESKTOP ANALYTICS", "Superadmin Web Portal", [
            "Ringkasan KPI: Omzet kotor, total transaksi, rata-rata order, dan menu terlaris.",
            "Heatmap Jam Sibuk: Distribusi jam pemesanan tertinggi untuk alokasi shift barista.",
            "CRUD 15 Menu: Pembaruan harga, deskripsi, profil rasa, dan toggle ketersediaan stok.",
            "Prompt Tuning Sandbox: Uji coba dan penyempurnaan instruksi sistem Mas Nongki Gemini."
        ], "✓ Analitik Komprehensif di PC Desktop"),
        ("MOBILE EXECUTIVE APP", "Owner Mobility On-The-Go", [
            "Hero Omzet Card: Total penjualan harian dan target omzet kafe dalam genggaman.",
            "Quick Stock Switch: Matikan menu yang habis dalam 1 ketukan jari saat berkeliling kafe.",
            "Live Floor Table Monitor: Status real-time tiap meja (Available, Occupied, Reserved).",
            "Notifikasi Kritis: Peringatan jika stok bahan atau menu berada di bawah batas minimum."
        ], "✓ Kontrol Kafe Penuh dari Genggaman")
    ]
    for i, (tag_f, tit_f, pts_f, foot_f) in enumerate(adm_cards):
        cx = 36 + i * (col_w + 20)
        s13.add_card(cx, 92, col_w, 275, bg_color=C_BG_CARD, border_color=C_BORDER_CARD, border_width=1)
        s13.add_badge(cx + 12, 104, 150, 18, tag_f, text_color=C_AMBER, bg_color=C_AMBER_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=7)
        s13.add_textbox(cx + 12, 126, col_w - 24, 18, tit_f, font_size=10, bold=True, text_color=C_TEXT_DARK, align="START", v_align="TOP")
        pts_text = "\n\n".join([f"• {p}" for p in pts_f])
        s13.add_textbox(cx + 12, 148, col_w - 24, 180, pts_text, font_size=8, bold=False, text_color=C_TEXT_BODY, align="START", v_align="TOP")
        s13.add_badge(cx + 12, 338, col_w - 24, 18, foot_f, text_color=C_AMBER, bg_color=C_AMBER_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=7)
    builders.append(s13)

    # ==========================================================
    # SLIDE 14: PENGUJIAN SISTEM & PERFORMA (2x2 Grid)
    # ==========================================================
    s14 = PolishedSlideBuilder(slide_ids[13])
    s14.setup_header("Pengujian & Evaluasi", "Hasil Pengujian Black-Box & Metrik Performa Sistem", "Verifikasi stabilitas seluruh skenario fungsional dan kecepatan respon aplikasi")
    metrics = [
        ("100% LULUS", "Pengujian Fungsional Black-Box", "18 dari 18 test cases lolos verifikasi sempurna (alur check-in meja, katalog Livewire, prompt AI Gemini, order QRIS, KDS status, dan admin CRUD).", "✓ Semua Skenario Sukses"),
        ("~1.1 DETIK", "Latensi Respon AI Barista", "Google Gemini 1.5 Flash mengembalikan rekomendasi JSON structured dalam durasi sangat responsif dengan fallback aman jika kuota limit.", "✓ Respon Cepat & Responsif"),
        ("< 15 MS", "Waktu Respon Database MariaDB", "Eksekusi query Eloquent sangat ringan berkat indeks kunci primer, indeks access_code, dan normalisasi struktur tabel 3NF.", "✓ Query Terindeks Optimal"),
        ("< 2.5 KB", "Beban Bandwidth Polling Livewire", "Payload delta wire:poll.3s sangat hemat kuota data dan tidak membebani jaringan router WiFi kafe.", "✓ Ringan & Hemat Bandwidth")
    ]
    for (badge_m, tit_m, desc_m, foot_m), (mx, my) in zip(metrics, positions_2x2):
        s14.add_card(mx, my, p_w, p_h, bg_color=C_BG_CARD, border_color=C_BORDER_CARD, border_width=1)
        s14.add_badge(mx + 12, my + 10, 100, 18, badge_m, text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=7.5)
        s14.add_textbox(mx + 12, my + 32, p_w - 24, 18, tit_m, font_size=10, bold=True, text_color=C_TEXT_DARK, align="START", v_align="TOP")
        s14.add_textbox(mx + 12, my + 52, p_w - 24, 48, desc_m, font_size=8, bold=False, text_color=C_TEXT_BODY, align="START", v_align="TOP")
        s14.add_badge(mx + 12, my + 104, p_w - 24, 16, foot_m, text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=6.5)
    builders.append(s14)

    # ==========================================================
    # SLIDE 15: DAMPAK BISNIS & ROI (3 Kolom)
    # ==========================================================
    s15 = PolishedSlideBuilder(slide_ids[14])
    s15.setup_header("Dampak Bisnis & Nilai Tambah", "Kuantifikasi Dampak Bisnis & Efisiensi Operasional", "Manfaat nyata bagi pemilik usaha, efisiensi staf, dan pengalaman pengunjung kafe")
    impacts = [
        ("-75% WAKTU", "Waktu Antrean Terpangkas", "Waktu dari tamu duduk hingga pesanan masuk ke dapur terpangkas dari 8 menit menjadi hanya 2 menit.", "Kecepatan Layanan Meningkat", C_BLUE_SOFT_BG, C_PRIMARY_BLUE),
        ("+18.4% OMZET", "Peningkatan Rata-rata Order (AOV)", "Rekomendasi AI Barista secara natural menyarankan pairing pastry & snack yang relevan, menaikkan basket size.", "Pertumbuhan Penjualan Signifikan", C_GREEN_SOFT_BG, C_GREEN),
        ("0% KESALAHAN", "Eliminasi Tiket Hilang & Salah Catat", "Pesanan langsung tercatat di MariaDB dan diteruskan ke KDS Dapur, menghapus human error salah dengar.", "Akurasi Pesanan Maksimal", C_AMBER_SOFT_BG, C_AMBER)
    ]
    for i, (stat_i, tit_i, desc_i, sub_i, bg_ib, col_ib) in enumerate(impacts):
        ix = 36 + i * (r_w + 16)
        s15.add_card(ix, 92, r_w, 275, bg_color=C_BG_CARD, border_color=C_BORDER_CARD, border_width=1)
        s15.add_badge(ix + 12, 108, r_w - 24, 24, stat_i, text_color=col_ib, bg_color=bg_ib, border_color=C_BORDER_LIGHT, font_size=10)
        s15.add_textbox(ix + 12, 140, r_w - 24, 30, tit_i, font_size=10.5, bold=True, text_color=C_TEXT_DARK, align="START", v_align="TOP")
        s15.add_textbox(ix + 12, 176, r_w - 24, 150, desc_i, font_size=8, bold=False, text_color=C_TEXT_BODY, align="START", v_align="TOP")
        s15.add_badge(ix + 12, 338, r_w - 24, 18, sub_i, text_color=col_ib, bg_color=bg_ib, border_color=C_BORDER_LIGHT, font_size=6.5)
    builders.append(s15)

    # ==========================================================
    # SLIDE 16: KESIMPULAN & ROADMAP (2 Kolom)
    # ==========================================================
    s16 = PolishedSlideBuilder(slide_ids[15])
    s16.setup_header("Kesimpulan & Roadmap", "Kesimpulan Proyek & Rencana Pengembangan Lanjutan", "Fondasi kokoh sistem POS modern dengan peluang ekspansi multi-cabang masa depan")
    s16.add_card(36, 92, col_w, 275, bg_color=C_BG_CARD, border_color=C_BORDER_CARD, border_width=1)
    s16.add_badge(48, 104, 190, 18, "KESIMPULAN PENGEMBANGAN SISTEM", text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=7)
    s16.add_textbox(48, 126, col_w - 24, 18, "Ringkasan Pencapaian Proyek", font_size=10, bold=True, text_color=C_TEXT_DARK, align="START", v_align="TOP")
    c_points = (
        "1. Arsitektur Reaktif Tanpa Kompleksitas Berlebih:\n"
        "Kombinasi Laravel 11 dan Livewire 3 terbukti andal menghadirkan interaksi real-time tanpa memerlukan setup microservices yang rumit.\n\n"
        "2. Diferensiasi Nyata Melalui AI Generatif:\n"
        "Integrasi Google Gemini 1.5 Flash sebagai Mas Nongki AI Barista mengubah POS statis menjadi asisten belanja interaktif yang disukai pelanggan.\n\n"
        "3. Standar Kualitas Akademik & Industri:\n"
        "Perancangan basis data 3NF, flowchart standar ANSI/ISO 5807, dan 22 layar UI responsif siap diuji pada evaluasi sidang tugas akhir."
    )
    s16.add_textbox(48, 148, col_w - 24, 180, c_points, font_size=7.5, bold=False, text_color=C_TEXT_BODY, align="START", v_align="TOP")
    s16.add_badge(48, 338, col_w - 24, 18, "✓ Siap Diuji Pada Sidang Tugas Akhir", text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=7)

    s16.add_card(370, 92, col_w, 275, bg_color=C_BG_CARD, border_color=C_BORDER_CARD, border_width=1)
    s16.add_badge(382, 104, 190, 18, "ROADMAP PENGEMBANGAN LANJUTAN", text_color=C_TEXT_DARK, bg_color=C_BG_PAGE, border_color=C_BORDER_LIGHT, font_size=7)
    s16.add_textbox(382, 126, col_w - 24, 18, "Rencana Fase Pengembangan Mendatang", font_size=10, bold=True, text_color=C_TEXT_DARK, align="START", v_align="TOP")
    r_points = (
        "• FASE 1: Payment Gateway Webhook Terpadu\n"
        "Implementasi Midtrans / Xendit Webhook callback untuk verifikasi pembayaran QRIS otomatis instan tanpa konfirmasi kasir manual.\n\n"
        "• FASE 2: Inventory Stock Recipe Deduction\n"
        "Pengurangan stok bahan baku tingkat gramatur (biji kopi arabika, sirup karamel, susu fresh milk) secara otomatis tiap pesanan selesai diracik.\n\n"
        "• FASE 3: Multi-Outlet Cloud Sync & PWA Push\n"
        "Sinkronisasi data antar cabang kafe terpusat dan notifikasi push browser saat pesanan siap dijemput di bar."
    )
    s16.add_textbox(382, 148, col_w - 24, 180, r_points, font_size=7.5, bold=False, text_color=C_TEXT_BODY, align="START", v_align="TOP")
    s16.add_badge(382, 338, col_w - 24, 18, "✓ Skalabilitas Menuju Multi-Cabang", text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=7)
    builders.append(s16)

    # ==========================================================
    # SLIDE 17: PENUTUP & Q&A (Refined 2-Card Balanced Layout)
    # ==========================================================
    s17 = PolishedSlideBuilder(slide_ids[16])
    s17.add_shape("RECTANGLE", 0, 0, 720, 405, bg_color=C_PRIMARY_BLUE, v_align="TOP")
    s17.add_badge(235, 26, 250, 20, "★  NONGKRONG.id • SMART CAFE POS SYSTEM", text_color=C_PRIMARY_BLUE, bg_color=C_WHITE, border_color=C_WHITE, font_size=7.5)
    s17.add_textbox(36, 50, 648, 42, "TERIMA KASIH", font_size=34, bold=True, text_color=C_WHITE, align="CENTER", v_align="TOP")
    s17.add_textbox(36, 96, 648, 18, "Sesi Tanya Jawab & Diskusi Terbuka (Q&A)", font_size=11, bold=False, text_color={"red": 0.88, "green": 0.93, "blue": 1.0}, align="CENTER", v_align="TOP")

    # 2 Modular balanced cards side by side
    c17_h = 168
    s17.add_card(36, 126, col_w, c17_h, bg_color=C_WHITE, border_color=C_BORDER_CARD, border_width=1)
    s17.add_badge(48, 136, 160, 18, "📋 IDENTITAS PENGEMBANGAN", text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=7)
    s17.add_textbox(48, 158, col_w - 24, 16, "Informasi Proyek Sistem", font_size=10, bold=True, text_color=C_TEXT_DARK, align="START", v_align="TOP")
    
    # Left column: Labels (strict vertical alignment)
    dev_labels = "• Pengembang\n\n• Peran\n\n• Proyek\n\n• Arsitektur"
    s17.add_textbox(48, 178, 85, 80, dev_labels, font_size=7.5, bold=True, text_color=C_TEXT_DARK, align="START", v_align="TOP")
    
    # Right column: Values (colons aligned)
    dev_values = ":  Allam (puu)\n\n:  Lead Fullstack Engineer\n\n:  Final Project Laravel Fullstack\n\n:  Monolith Reactive + Gemini AI"
    s17.add_textbox(133, 178, col_w - 145, 80, dev_values, font_size=7.5, bold=False, text_color=C_TEXT_BODY, align="START", v_align="TOP")
    
    s17.add_badge(48, 266, col_w - 24, 18, "✓ Fullstack Lifecycle Architecture", text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=7)

    # Right Card
    s17.add_card(370, 126, col_w, c17_h, bg_color=C_WHITE, border_color=C_BORDER_CARD, border_width=1)
    s17.add_badge(382, 136, 180, 18, "🎯 STATUS VALIDASI SISTEM", text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=7)
    s17.add_textbox(382, 158, col_w - 24, 16, "Kesiapan Uji Sidang Akademik", font_size=10, bold=True, text_color=C_TEXT_DARK, align="START", v_align="TOP")

    # Left column: Labels (strict vertical alignment)
    val_labels = "• Basis Data\n\n• Pengujian\n\n• Antarmuka\n\n• Alur Proses"
    s17.add_textbox(382, 178, 85, 80, val_labels, font_size=7.5, bold=True, text_color=C_TEXT_DARK, align="START", v_align="TOP")

    # Right column: Values (colons aligned)
    val_values = ":  Normalisasi Penuh 3NF (7 Entitas)\n\n:  18 / 18 Test Cases Lulus (100%)\n\n:  22 Layar UI Mockup (Pure Flat Design)\n\n:  Standar Internasional ANSI/ISO 5807"
    s17.add_textbox(467, 178, col_w - 145, 80, val_values, font_size=7.5, bold=False, text_color=C_TEXT_BODY, align="START", v_align="TOP")

    s17.add_badge(382, 266, col_w - 24, 18, "✓ Siap Uji Sidang Akademik 2026", text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, border_color=C_BORDER_LIGHT, font_size=7)

    # Center callout badge
    s17.add_badge(180, 312, 360, 22, "✦ Sesi Diskusi Terbuka: Pertanyaan, Saran & Masukan Dipersilakan ✦", text_color=C_PRIMARY_BLUE, bg_color=C_WHITE, border_color=C_WHITE, font_size=7.5)
    s17.add_textbox(36, 348, 648, 16, "Presentasi Tugas Akhir Rekayasa Perangkat Lunak • 2026", font_size=8, text_color={"red": 0.82, "green": 0.89, "blue": 1.0}, align="CENTER", v_align="TOP")
    builders.append(s17)

    # Execute batchUpdate per slide
    print("Executing batchUpdate for each polished slide...")
    for idx, bld in enumerate(builders, start=1):
        if bld.reqs:
            slides_service.presentations().batchUpdate(presentationId=PRES_ID, body={"requests": bld.reqs}).execute()
            print(f"Slide {idx:02d} rendered ({len(bld.reqs)} requests).")

    print("ALL 17 SLIDES POLISHED & UPDATED SUCCESSFULLY!")
    return f"https://docs.google.com/presentation/d/{PRES_ID}/edit"

if __name__ == "__main__":
    url = build_and_push_deck()
    print("Live URL:", url)
