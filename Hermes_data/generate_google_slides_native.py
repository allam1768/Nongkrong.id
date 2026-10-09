#!/usr/bin/env python3
"""
Native Google Slides Presentation Builder for NONGKRONG.id
Uses Google Slides API v1 with credentials from ~/.hermes/google_token.json.
Generates a complete 17-slide deck directly in Allam's Google Slides account!
"""

import os
import sys
import json
import uuid
from google.oauth2.credentials import Credentials
from google.auth.transport.requests import Request
from googleapiclient.discovery import build

TOKEN_PATH = "/home/puutraa/.hermes/google_token.json"

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

# Colors - Flat Solid Aesthetics
C_WHITE         = {"red": 1.0, "green": 1.0, "blue": 1.0}
C_BG_PAGE       = {"red": 0.973, "green": 0.980, "blue": 0.988}  # #F8FAFC
C_BG_CARD       = {"red": 1.0, "green": 1.0, "blue": 1.0}        # #FFFFFF
C_PRIMARY_BLUE  = {"red": 0.0, "green": 0.290, "blue": 0.678}   # #004AAD Royal Blue
C_PRIMARY_NAVY  = {"red": 0.059, "green": 0.090, "blue": 0.165}  # #0F172A Dark Slate
C_ACCENT_BLUE   = {"red": 0.118, "green": 0.227, "blue": 0.541}  # #1E3A8A Dark Navy
C_BORDER_LIGHT  = {"red": 0.886, "green": 0.910, "blue": 0.941}  # #E2E8F0
C_BORDER_STRONG = {"red": 0.796, "green": 0.835, "blue": 0.882}  # #CBD5E1
C_TEXT_DARK     = {"red": 0.059, "green": 0.090, "blue": 0.165}  # #0F172A
C_TEXT_BODY     = {"red": 0.200, "green": 0.255, "blue": 0.333}  # #334155
C_TEXT_MUTED    = {"red": 0.392, "green": 0.455, "blue": 0.545}  # #64748B
C_GREEN         = {"red": 0.086, "green": 0.643, "blue": 0.290}  # #16A34A
C_AMBER         = {"red": 0.851, "green": 0.467, "blue": 0.024}  # #D97706
C_BLUE_SOFT_BG  = {"red": 0.937, "green": 0.965, "blue": 1.0}    # #EFF6FF
C_GREEN_SOFT_BG = {"red": 0.941, "green": 0.992, "blue": 0.957}  # #F0FDF4
C_AMBER_SOFT_BG = {"red": 1.0, "green": 0.984, "blue": 0.929}    # #FFFBEB

class GoogleSlideBuilder:
    def __init__(self, sid):
        self.sid = sid
        self.reqs = []
        self._count = 0

    def _id(self, prefix="el"):
        self._count += 1
        return f"{self.sid}_{prefix}_{self._count}"

    def add_shape(self, shape_type, x, y, w, h, bg_color=None, border_color=None, border_width=1):
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

        self.reqs.append({
            "updateShapeProperties": {
                "objectId": oid,
                "shapeProperties": props,
                "fields": ",".join(fields)
            }
        })
        return oid

    def add_card(self, x, y, w, h, bg_color=C_BG_CARD, border_color=C_BORDER_LIGHT, border_width=1):
        return self.add_shape("RECTANGLE", x, y, w, h, bg_color, border_color, border_width)

    def add_textbox(self, x, y, w, h, text, font_size=11, bold=False, text_color=C_TEXT_BODY, bg_color=None, border_color=None, border_width=1, font_name="Arial", align=None):
        oid = self.add_shape("RECTANGLE", x, y, w, h, bg_color, border_color, border_width)
        if text:
            self.reqs.append({
                "insertText": {
                    "objectId": oid,
                    "text": text,
                    "insertionIndex": 0
                }
            })
            style = {
                "fontSize": {"magnitude": font_size, "unit": "PT"},
                "bold": bold,
                "fontFamily": font_name,
                "foregroundColor": {"opaqueColor": {"rgbColor": text_color}}
            }
            self.reqs.append({
                "updateTextStyle": {
                    "objectId": oid,
                    "style": style,
                    "fields": "fontSize,bold,fontFamily,foregroundColor"
                }
            })
            if align:
                self.reqs.append({
                    "updateParagraphStyle": {
                        "objectId": oid,
                        "style": {"alignment": align},
                        "fields": "alignment"
                    }
                })
        return oid

    def setup_header(self, tag, title, subtitle):
        self.add_shape("RECTANGLE", 0, 0, 960, 540, bg_color=C_BG_PAGE)
        self.add_textbox(40, 22, 170, 22, f"●  {tag.upper()}", font_size=8, bold=True, text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, border_color=C_BORDER_LIGHT, border_width=1)
        self.add_textbox(40, 48, 880, 32, title, font_size=17, bold=True, text_color=C_TEXT_DARK)
        self.add_textbox(40, 80, 880, 22, subtitle, font_size=9.5, bold=False, text_color=C_TEXT_MUTED)
        self.add_shape("RECTANGLE", 40, 106, 880, 1.5, bg_color=C_BORDER_LIGHT)
        self.add_shape("RECTANGLE", 40, 508, 880, 1, bg_color=C_BORDER_LIGHT)
        self.add_textbox(40, 513, 400, 18, "NONGKRONG.id • Smart Cafe POS & Self-Ordering System", font_size=8, text_color=C_TEXT_MUTED)
        self.add_textbox(680, 513, 240, 18, "Pengembang: Allam (puu)", font_size=8, text_color=C_TEXT_MUTED, align="END")


def build_presentation():
    creds = get_creds()
    slides_service = build("slides", "v1", credentials=creds)

    title = "NONGKRONG.id - Smart Cafe POS Presentation Deck"
    pres = slides_service.presentations().create(body={"title": title}).execute()
    pres_id = pres.get("presentationId")
    print(f"Created presentation with ID: {pres_id}")

    default_slide_id = pres.get("slides", [{}])[0].get("objectId")
    slide_ids = [f"slide_{i}_{uuid.uuid4().hex[:6]}" for i in range(1, 18)]

    # 1. Create all 17 blank slides & delete default
    reqs_create = []
    for sid in slide_ids:
        reqs_create.append({
            "createSlide": {
                "objectId": sid,
                "slideLayoutReference": {"predefinedLayout": "BLANK"}
            }
        })
    if default_slide_id:
        reqs_create.append({"deleteObject": {"objectId": default_slide_id}})

    slides_service.presentations().batchUpdate(presentationId=pres_id, body={"requests": reqs_create}).execute()
    print("17 blank slides initialized.")

    all_builders = []

    # ==========================================================
    # SLIDE 1: COVER
    # ==========================================================
    s1 = GoogleSlideBuilder(slide_ids[0])
    s1.add_shape("RECTANGLE", 0, 0, 960, 540, bg_color=C_PRIMARY_BLUE)
    s1.add_textbox(60, 50, 360, 26, "★  FINAL PROJECT LARAVEL FULLSTACK • 2026", font_size=9, bold=True, text_color=C_PRIMARY_BLUE, bg_color=C_WHITE)
    s1.add_textbox(60, 90, 840, 70, "NONGKRONG.id", font_size=42, bold=True, text_color=C_WHITE)
    s1.add_textbox(60, 165, 840, 60, "Smart Cafe POS & Self-Ordering System dengan Integrasi AI Barista Mas Nongki (Gemini 1.5 Flash), Kitchen Display System, dan Multi-Platform Responsive Portal", font_size=13, bold=False, text_color={"red": 0.86, "green": 0.92, "blue": 1.0})
    s1.add_shape("RECTANGLE", 60, 245, 840, 2, bg_color={"red": 0.23, "green": 0.45, "blue": 0.82})

    # 3 Cards on Cover
    cards_meta = [
        ("👤 PENGEMBANG SISTEM", "Allam (puu)\nLead Fullstack Engineer\nJurusan Software Engineering"),
        ("⚡ ARSITEKTUR UTAMA", "Laravel 11 • Livewire 3\nGoogle Gemini 1.5 Flash\nMariaDB 10.11+ • Tailwind CSS"),
        ("🎯 FOKUS SOLUSI", "Dual Check-in (QR + 5-Box)\nRealtime KDS Kanban\nOwner Executive Mobility")
    ]
    card_w = 265
    for i, (head, body) in enumerate(cards_meta):
        cx = 60 + i * (card_w + 22)
        s1.add_card(cx, 270, card_w, 140, bg_color={"red": 0.05, "green": 0.22, "blue": 0.55}, border_color={"red": 0.25, "green": 0.45, "blue": 0.85}, border_width=1.5)
        s1.add_textbox(cx + 15, 282, card_w - 30, 22, head, font_size=9, bold=True, text_color={"red": 0.58, "green": 0.77, "blue": 1.0})
        s1.add_textbox(cx + 15, 308, card_w - 30, 85, body, font_size=11, bold=False, text_color=C_WHITE)

    s1.add_textbox(60, 460, 840, 25, "Dokumentasi & Presentasi Resmi Sidang Tugas Akhir Pengembangan Perangkat Lunak", font_size=9.5, text_color={"red": 0.75, "green": 0.85, "blue": 1.0})
    all_builders.append(s1)

    # ==========================================================
    # SLIDE 2: PROBLEM STATEMENT
    # ==========================================================
    s2 = GoogleSlideBuilder(slide_ids[1])
    s2.setup_header("Latar Belakang & Permasalahan", "Tantangan & Bottleneck Operasional Kafe Modern", "Analisis permasalahan faktual pada alur pemesanan konvensional di industri Food & Beverage")
    problems = [
        ("01", "Antrean Kasir di Jam Sibuk (Peak Hours)", "Pelanggan harus berdiri mengantre 8-15 menit di kasir pada jam 14:00-18:00. Mengakibatkan hilangnya potensi omzet (walk-out rate mencapai 12%)."),
        ("02", "Decision Paralysis & Menu Statis", "Buku menu fisik konvensional tidak memberikan interaktivitas. Pelanggan bingung memilih profil rasa kopi tanpa panduan rekomendasi yang personal."),
        ("03", "Miskomunikasi Pesanan & Tiket Kertas", "Nota manual tulisan tangan rentan hilang, salah catat request khusus ('less sugar 50%'), dan tertukar nomor meja. Tingkat kesalahan saji mencapai 7%."),
        ("04", "Keterbatasan Mobilitas Pemantauan Pemilik", "Sistem kasir konvensional terisolasi di PC desktop kasir. Cafe owner tidak bisa memantau perputaran omzet dan stok kritis secara real-time via smartphone.")
    ]
    p_w, p_h = 425, 175
    positions = [(40, 125), (495, 125), (40, 315), (495, 315)]
    for (num, title_p, desc_p), (px, py) in zip(problems, positions):
        s2.add_card(px, py, p_w, p_h, bg_color=C_BG_CARD, border_color=C_BORDER_LIGHT, border_width=1.5)
        s2.add_textbox(px + 16, py + 14, 38, 26, num, font_size=12, bold=True, text_color=C_WHITE, bg_color=C_PRIMARY_NAVY, align="CENTER")
        s2.add_textbox(px + 62, py + 14, p_w - 78, 26, title_p, font_size=11, bold=True, text_color=C_TEXT_DARK)
        s2.add_shape("RECTANGLE", px + 16, py + 48, p_w - 32, 1, bg_color=C_BORDER_LIGHT)
        s2.add_textbox(px + 16, py + 56, p_w - 32, 105, desc_p, font_size=9.5, bold=False, text_color=C_TEXT_BODY)
    all_builders.append(s2)

    # ==========================================================
    # SLIDE 3: SOLUSI SISTEM
    # ==========================================================
    s3 = GoogleSlideBuilder(slide_ids[2])
    s3.setup_header("Solusi Sistem & UVP", "Ekosistem Self-Ordering Cerdas & Omnichannel POS", "Transformasi digital menyeluruh yang mengintegrasikan tamu, barista, kasir, dan pemilik")
    solutions = [
        ("MEJA CHECK-IN", "Dual-Verification Table", "Scan kamera QR atau input kode alfanumerik 5-karakter meja (misal: 7K92A) tanpa unduh aplikasi.", C_BLUE_SOFT_BG, C_PRIMARY_BLUE),
        ("AI BARISTA", "Mas Nongki Gemini 1.5", "Konsultasi preferensi rasa dua arah berbasis Natural Language Processing terhubung 15 menu lokal.", C_BLUE_SOFT_BG, C_PRIMARY_BLUE),
        ("KITCHEN DISPLAY", "Realtime KDS Kanban", "Sinkronisasi antrean dapur instan bertenaga Livewire 3 wire:poll.3s tanpa websocket berbayar.", C_GREEN_SOFT_BG, C_GREEN),
        ("OMNICHANNEL UI", "Multi-Platform Experience", "Antarmuka terpisah & responsif: Mobile PWA, Widescreen Desktop, Touch POS, & Executive Admin.", C_AMBER_SOFT_BG, C_AMBER)
    ]
    c_w = 205
    for i, (badge, title_s, desc_s, bg_b, col_b) in enumerate(solutions):
        sx = 40 + i * (c_w + 20)
        s3.add_card(sx, 125, c_w, 355, bg_color=C_BG_CARD, border_color=C_BORDER_LIGHT, border_width=1.5)
        s3.add_textbox(sx + 14, 142, c_w - 28, 24, badge, font_size=8, bold=True, text_color=col_b, bg_color=bg_b, align="CENTER")
        s3.add_textbox(sx + 14, 175, c_w - 28, 45, title_s, font_size=12, bold=True, text_color=C_TEXT_DARK, align="CENTER")
        s3.add_shape("RECTANGLE", sx + 14, 228, c_w - 28, 1, bg_color=C_BORDER_LIGHT)
        s3.add_textbox(sx + 14, 242, c_w - 28, 220, desc_s, font_size=9.5, bold=False, text_color=C_TEXT_BODY)
    all_builders.append(s3)

    # ==========================================================
    # SLIDE 4: ARSITEKTUR TEKNOLOGI
    # ==========================================================
    s4 = GoogleSlideBuilder(slide_ids[3])
    s4.setup_header("Arsitektur Teknologi", "Modern Fullstack Stack: Cepat, Reaktif, & Skalabel", "Kombinasi teknologi mutakhir untuk performa tinggi dengan arsitektur elegan")
    tech_stacks = [
        ("BACKEND ENGINE", "Laravel 11 / 12 & PHP 8.5", "• RESTful Routing & Model-View-Controller\n• Eloquent ORM dengan Relasi Kompleks 3NF\n• Form Request Validation & Database Seeder\n• Session & File Cache Driver Terisolasi"),
        ("REACTIVE FRONTEND", "Livewire 3 + Alpine.js + Tailwind", "• DOM Morphing Reaktif tanpa Build JS Berat\n• wire:poll.3s Sinkronisasi Data Realtime\n• Pure Flat Design Palette (Solid Royal Blue)\n• Full Responsive: Mobile 414px & Desktop 1440px"),
        ("DATABASE ENGINE", "MariaDB 10.11+ Relational Engine", "• 7 Tabel Relasional Normalisasi Penuh 3NF\n• Foreign Key Cascading & Data Integrity\n• Indexing Cepat access_code & qr_token\n• Optimized Query Execution Latency < 15ms"),
        ("GENERATIVE AI", "Google Gemini 1.5 Flash API", "• Model Gemini 1.5 Flash Latensi Ultra Cepat\n• System Prompt: Persona Mas Nongki Barista\n• JSON Structured Output (Match Rate & Menu ID)\n• Context Injection 15 Menu Lokal MariaDB")
    ]
    for i, (cat, name, points, ) in enumerate(tech_stacks):
        tx = 40 + (i % 2) * 455
        ty = 125 + (i // 2) * 185
        s4.add_card(tx, ty, 425, 170, bg_color=C_BG_CARD, border_color=C_BORDER_LIGHT, border_width=1.5)
        s4.add_textbox(tx + 16, ty + 12, 160, 20, cat, font_size=8, bold=True, text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG)
        s4.add_textbox(tx + 16, ty + 36, 393, 24, name, font_size=11, bold=True, text_color=C_TEXT_DARK)
        s4.add_shape("RECTANGLE", tx + 16, ty + 64, 393, 1, bg_color=C_BORDER_LIGHT)
        s4.add_textbox(tx + 16, ty + 70, 393, 90, points, font_size=9, bold=False, text_color=C_TEXT_BODY)
    all_builders.append(s4)

    # ==========================================================
    # SLIDE 5: 3 PERAN PENGGUNA (RBAC)
    # ==========================================================
    s5 = GoogleSlideBuilder(slide_ids[4])
    s5.setup_header("Desain Pengguna", "Struktur Hak Akses & Tiga Peran Pengguna (RBAC)", "Pemisahan tanggung jawab fungsional yang tegas demi keamanan operasional kafe")
    roles = [
        ("ROLE 1", "PELANGGAN (CUSTOMER)", "Platform: Mobile Web & Desktop Portal", [
            "Check-in meja via QR scan kamera atau kode 5-box",
            "Jelajahi 15 menu dengan filter kategori sticky",
            "Konsultasi rasa interaktif via Mas Nongki AI Barista",
            "Kelola keranjang & perhitungan pajak PB1 10%",
            "Checkout QRIS dinamis & live order status tracking"
        ], C_BLUE_SOFT_BG, C_PRIMARY_BLUE),
        ("ROLE 2", "STAFF KASIR & BARISTA", "Platform: Desktop Touch POS & KDS Tablet", [
            "Input transaksi walk-in order langsung di kasir fisik",
            "Keypad tunai cepat, preset nominal, hitung kembalian",
            "Kanban Board Dapur: Pesanan Baru -> Diracik -> Siap",
            "Livewire auto-shift kartu pesanan saat status diupdate",
            "Pencetakan struk transaksi nota kasir instan"
        ], C_GREEN_SOFT_BG, C_GREEN),
        ("ROLE 3", "ADMIN & CAFE OWNER", "Platform: Executive Analytics & Mobile App", [
            "Ringkasan omzet harian, transaksi, dan AOV kafe",
            "Grafik tren jam sibuk (heatmap hourly traffic)",
            "CRUD 15 Menu: Harga, deskripsi, rasa, & switch stok",
            "Tuning System Prompt Mas Nongki & Sandbox AI",
            "Mobile floor table monitor & toggle ketersediaan meja"
        ], C_AMBER_SOFT_BG, C_AMBER)
    ]
    r_w = 280
    for i, (badge_r, name_r, plat_r, duties, bg_rb, col_rb) in enumerate(roles):
        rx = 40 + i * (r_w + 20)
        s5.add_card(rx, 125, r_w, 355, bg_color=C_BG_CARD, border_color=C_BORDER_LIGHT, border_width=1.5)
        s5.add_textbox(rx + 16, 140, 70, 22, badge_r, font_size=8, bold=True, text_color=col_rb, bg_color=bg_rb, align="CENTER")
        s5.add_textbox(rx + 16, 168, r_w - 32, 24, name_r, font_size=11, bold=True, text_color=C_TEXT_DARK)
        s5.add_textbox(rx + 16, 194, r_w - 32, 18, plat_r, font_size=8.5, bold=False, text_color=C_TEXT_MUTED)
        s5.add_shape("RECTANGLE", rx + 16, 218, r_w - 32, 1, bg_color=C_BORDER_LIGHT)
        duties_text = "\n\n".join([f"• {d}" for d in duties])
        s5.add_textbox(rx + 16, 226, r_w - 32, 240, duties_text, font_size=9, bold=False, text_color=C_TEXT_BODY)
    all_builders.append(s5)

    # ==========================================================
    # SLIDE 6: ALUR SISTEM GLOBAL (FLOWCHART)
    # ==========================================================
    s6 = GoogleSlideBuilder(slide_ids[5])
    s6.setup_header("Diagram Alir Sistem", "Alur Proses Bisnis End-to-End (ANSI/ISO 5807)", "Sinkronisasi otomatis antara alur pelanggan (Customer), barista (Kitchen), dan database")
    steps_flow = [
        ("TAHAP 01", "Verifikasi Meja", "Tamu datang ke meja fisik kafe, scan QR code atau input kode alfanumerik 5-karakter meja (misal: 7K92A) untuk membuka sesi."),
        ("TAHAP 02", "Eksplorasi Menu", "Tamu dapat memilih menu secara mandiri lewat katalog visual atau berkonsultasi rasa dengan Mas Nongki AI Barista."),
        ("TAHAP 03", "Order & QRIS", "Tamu meninjau keranjang Livewire, sistem menghitung PB1 10%, memilih metode QRIS, dan menekan Bayar Sekarang."),
        ("TAHAP 04", "Dispatch Dapur", "Database MariaDB mencatat order_number (#ORD-XXX) dan Livewire me-refresh kolom 'Pesanan Baru' pada layar KDS Dapur."),
        ("TAHAP 05", "Peracikan Menu", "Barista menekan 'Mulai Racik' (status dipindah ke 'Sedang Diramu'). Pelanggan melihat progress live tracking bergerak."),
        ("TAHAP 06", "Sajian Tiba", "Barista menekan 'Siap Saji'. Waiter mengantar ke nomor meja. Status pesanan selesai dan sesi meja ditutup.")
    ]
    for i, (stg, tit, dsc) in enumerate(steps_flow):
        fx = 40 + (i % 3) * 300
        fy = 125 + (i // 3) * 185
        s6.add_card(fx, fy, 280, 170, bg_color=C_BG_CARD, border_color=C_BORDER_LIGHT, border_width=1.5)
        s6.add_textbox(fx + 14, fy + 12, 85, 20, stg, font_size=8, bold=True, text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, align="CENTER")
        s6.add_textbox(fx + 14, fy + 38, 252, 22, tit, font_size=11, bold=True, text_color=C_TEXT_DARK)
        s6.add_shape("RECTANGLE", fx + 14, fy + 64, 252, 1, bg_color=C_BORDER_LIGHT)
        s6.add_textbox(fx + 14, fy + 72, 252, 85, dsc, font_size=9, bold=False, text_color=C_TEXT_BODY)
    all_builders.append(s6)

    # ==========================================================
    # SLIDE 7: BASIS DATA & ERD 3NF
    # ==========================================================
    s7 = GoogleSlideBuilder(slide_ids[6])
    s7.setup_header("Basis Data & Skema Relasional", "Perancangan Basis Data 3NF & Kamus Data Relasional", "Normalisasi penuh 3NF memastikan data transaksi akurat, cepat, dan bebas anomali")
    tables_spec = [
        ("users", "RBAC Auth (admin, staff, customer), email unik, hashed password."),
        ("tables", "table_number, access_code unik (5-karakter), status, capacity."),
        ("categories", "Pengelompokan menu: Coffee, Non-Coffee, Meals, Snacks."),
        ("menus", "Relasi category_id, name, price, stock, is_available, taste_profile."),
        ("orders", "Relasi user_id, table_id, order_number unik, total_amount, status."),
        ("order_items", "Relasi order_id, menu_id, quantity, unit_price, subtotal, notes."),
        ("ai_consultations", "Log riwayat session_id, user_prompt, ai_recommendations JSON.")
    ]
    s7.add_card(40, 125, 430, 355, bg_color=C_BG_CARD, border_color=C_BORDER_LIGHT, border_width=1.5)
    s7.add_textbox(56, 140, 398, 24, "KAMUS DATA 7 TABEL UTAMA (3NF)", font_size=11, bold=True, text_color=C_PRIMARY_BLUE)
    s7.add_shape("RECTANGLE", 56, 168, 398, 1, bg_color=C_BORDER_LIGHT)
    tab_text = ""
    for t_name, t_desc in tables_spec:
        tab_text += f"• {t_name.upper()}: {t_desc}\n"
    s7.add_textbox(56, 176, 398, 290, tab_text, font_size=9, bold=False, text_color=C_TEXT_BODY)

    s7.add_card(490, 125, 430, 355, bg_color=C_BG_CARD, border_color=C_BORDER_LIGHT, border_width=1.5)
    s7.add_textbox(506, 140, 398, 24, "KARAKTERISTIK INTEGRITAS RELASI", font_size=11, bold=True, text_color=C_TEXT_DARK)
    s7.add_shape("RECTANGLE", 506, 168, 398, 1, bg_color=C_BORDER_LIGHT)
    db_points = (
        "1. Pembuktian Normalisasi Ketiga (3NF):\n"
        "   Tidak ada ketergantungan transitif. Kolom non-kunci hanya bergantung secara penuh pada Primary Key masing-masing tabel.\n\n"
        "2. Foreign Key Cascading & Restrict:\n"
        "   order_items berelasi erat ke orders (cascade delete) dan menus (restrict delete untuk menjaga riwayat transaksi historis).\n\n"
        "3. Snapshot Immutability Transaksi:\n"
        "   Kolom unit_price pada order_items mencatat harga saat transaksi berlangsung, mencegah perubahan omzet jika harga menu diupdate di masa depan.\n\n"
        "4. Diagram Visual ERD:\n"
        "   Tersedia berkas diagram ERD Crow's Foot beresolusi tinggi di direktori proyek: erd_smart_cafe.png."
    )
    s7.add_textbox(506, 176, 398, 290, db_points, font_size=9, bold=False, text_color=C_TEXT_BODY)
    all_builders.append(s7)

    # ==========================================================
    # SLIDE 8: FITUR 1 - CHECK IN MEJA
    # ==========================================================
    s8 = GoogleSlideBuilder(slide_ids[7])
    s8.setup_header("Fitur Unggulan 1", "Dual-Verification Table Check-In (QR & 5-Box Code)", "Solusi inklusif mengatasi kendala kamera rusak atau pemesanan lewat laptop")
    f1_cards = [
        ("MEKANISME VERIFIKASI", "Metode Ganda Check-in Meja", [
            "Jalur 1: QR Code Scanner via kamera HP langsung membuka sesi meja.",
            "Jalur 2: Input 5-Box kode unik alfanumerik (7K92A, 3X81B, 8Y24C, 6W15D, 5V39E, 4U72F).",
            "Auto-binding: Mengunci nomor meja ke sesi pemesanan tanpa risiko order salah antar.",
            "Zero Installation: Tanpa perlu install aplikasi Play Store / App Store."
        ]),
        ("PENCEGAHAN HUMAN ERROR", "Keamanan Sesi & Validasi", [
            "Mencegah pelanggan memesan dari luar radius kafe tanpa kode meja fisik.",
            "Validasi database instan: Meja dengan status 'occupied' menolak sesi baru yang bentrok.",
            "Visual feedback jelas: status meja, kapasitas kursi, dan akses menu langsung aktif.",
            "Mendukung 22 layar responsif (Mobile 414px & Desktop 1440px)."
        ])
    ]
    for i, (tag_f, tit_f, pts) in enumerate(f1_cards):
        cx = 40 + i * 455
        s8.add_card(cx, 125, 425, 355, bg_color=C_BG_CARD, border_color=C_BORDER_LIGHT, border_width=1.5)
        s8.add_textbox(cx + 20, 142, 170, 22, tag_f, font_size=8, bold=True, text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG)
        s8.add_textbox(cx + 20, 170, 385, 24, tit_f, font_size=12, bold=True, text_color=C_TEXT_DARK)
        s8.add_shape("RECTANGLE", cx + 20, 198, 385, 1, bg_color=C_BORDER_LIGHT)
        pts_text = "\n\n".join([f"• {p}" for p in pts])
        s8.add_textbox(cx + 20, 210, 385, 250, pts_text, font_size=9.5, bold=False, text_color=C_TEXT_BODY)
    all_builders.append(s8)

    # ==========================================================
    # SLIDE 9: FITUR 2 - AI BARISTA MAS NONGKI
    # ==========================================================
    s9 = GoogleSlideBuilder(slide_ids[8])
    s9.setup_header("Fitur Unggulan 2", "AI Barista Mas Nongki: Rekomendasi Rasa Personal", "Integrasi Gemini 1.5 Flash untuk konsultasi preferensi selera kopi pelanggan")
    f2_cards = [
        ("SISTEM PROMPTING & NLP", "Persona Barista Lokal & Context Injection", [
            "Persona: Ramah, santai khas barista lokal, memahami profil rasa kopi.",
            "Context Injection: Menyuntikkan seluruh 15 menu aktif MariaDB ke system prompt Gemini.",
            "Analisis Rasa: Memetakan acidity, sweetness, bitterness, dan tingkat ketahanan kafein.",
            "Latensi Sangat Cepat: Rata-rata respon ~1.1 detik dengan Google Gemini 1.5 Flash."
        ]),
        ("STRUCTURED OUTPUT", "Konversi Rekomendasi Menjadi Penjualan", [
            "JSON Schema Terstruktur: Output dikembalikan dalam format data siap-render.",
            "Kartu Interaktif: Menampilkan persentase kecocokan rasa (Match Rate: 98%).",
            "1-Klik Order: Tombol 'Tambah ke Pesanan' langsung menginjeksi menu_id ke Livewire cart.",
            "Logging Riwayat: Disimpan di tabel ai_consultations untuk audit preferensi rasa kafe."
        ])
    ]
    for i, (tag_f, tit_f, pts) in enumerate(f2_cards):
        cx = 40 + i * 455
        s9.add_card(cx, 125, 425, 355, bg_color=C_BG_CARD, border_color=C_BORDER_LIGHT, border_width=1.5)
        s9.add_textbox(cx + 20, 142, 170, 22, tag_f, font_size=8, bold=True, text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG)
        s9.add_textbox(cx + 20, 170, 385, 24, tit_f, font_size=12, bold=True, text_color=C_TEXT_DARK)
        s9.add_shape("RECTANGLE", cx + 20, 198, 385, 1, bg_color=C_BORDER_LIGHT)
        pts_text = "\n\n".join([f"• {p}" for p in pts])
        s9.add_textbox(cx + 20, 210, 385, 250, pts_text, font_size=9.5, bold=False, text_color=C_TEXT_BODY)
    all_builders.append(s9)

    # ==========================================================
    # SLIDE 10: FITUR 3 - KDS & REALTIME TRACKING
    # ==========================================================
    s10 = GoogleSlideBuilder(slide_ids[9])
    s10.setup_header("Fitur Unggulan 3", "Kitchen Display System (KDS) & Realtime Tracking", "Eliminasi nota kertas dan transparansi status sajian langsung di meja tamu")
    f3_cards = [
        ("OPERASIONAL DAPUR", "Kanban Board Dapur Terotomasi", [
            "3 Kolom Kanban: Pesanan Baru Masuk -> Sedang Diramu -> Siap Disajikan.",
            "Sinkronisasi wire:poll.3s: Kartu tiket otomatis muncul begitu transaksi selesai dibayar.",
            "Detail Tiket Lengkap: Nomor Meja, Kode Order (#ORD-XXX), list item, dan request catatan khusus.",
            "Zero Paper Waste: Menghemat ribuan lembar kertas struk thermal dapur per bulan."
        ]),
        ("TRANSPARANSI PELANGGAN", "Live Order Status Tracking di Meja", [
            "Stepper Progres 4 Tahap: Diterima -> Diracik -> Siap Antar -> Selesai.",
            "Estimasi Waktu Penyajian: Indikator waktu real-time (~5-8 menit per racikan).",
            "Bebas Rasa Cemas: Pelanggan tidak perlu berulang kali bertanya ke barista.",
            "Tombol Panggil Waiter: Fitur bantuan sekali klik jika tamu memerlukan sesuatu."
        ])
    ]
    for i, (tag_f, tit_f, pts) in enumerate(f3_cards):
        cx = 40 + i * 455
        s10.add_card(cx, 125, 425, 355, bg_color=C_BG_CARD, border_color=C_BORDER_LIGHT, border_width=1.5)
        s10.add_textbox(cx + 20, 142, 170, 22, tag_f, font_size=8, bold=True, text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG)
        s10.add_textbox(cx + 20, 170, 385, 24, tit_f, font_size=12, bold=True, text_color=C_TEXT_DARK)
        s10.add_shape("RECTANGLE", cx + 20, 198, 385, 1, bg_color=C_BORDER_LIGHT)
        pts_text = "\n\n".join([f"• {p}" for p in pts])
        s10.add_textbox(cx + 20, 210, 385, 250, pts_text, font_size=9.5, bold=False, text_color=C_TEXT_BODY)
    all_builders.append(s10)

    # ==========================================================
    # SLIDE 11: REKAPITULASI 22 LAYAR UI
    # ==========================================================
    s11 = GoogleSlideBuilder(slide_ids[10])
    s11.setup_header("Arsitektur Antarmuka", "Koleksi 22 Layar UI Mockup: Komprehensif & Responsif", "Seluruh antarmuka dirancang dengan Pure Flat Design tanpa gradien")
    ui_groups = [
        ("ROLE 1 • MOBILE PELANGGAN (7 LAYAR)", [
            "01_splash_screen.png — Splash screen & identitas kafe",
            "02_onboarding.png — 3 pilar value onboarding",
            "03_table_checkin.png — Check-in QR & 5-box code",
            "04_customer_catalog.png — Katalog menu mobile & kategori",
            "05_ai_consultation.png — Obrolan interaktif Mas Nongki",
            "06_checkout_qris.png — QRIS settlement & rincian PB1 10%",
            "07_order_tracking.png — Stepper tracking pesanan meja"
        ]),
        ("ROLE 1 • WEB DESKTOP PELANGGAN (6 LAYAR)", [
            "01_web_portal_welcome.png — Hero welcome portal laptop",
            "02_web_table_checkin.png — Split view QR & 5-box code",
            "03_web_customer_catalog.png — Widescreen 3-kolom catalog",
            "04_web_ai_barista.png — Drawer interaktif Mas Nongki AI",
            "05_web_checkout_qris.png — QRIS modal & ringkasan pesanan",
            "06_web_order_tracking.png — Live horizontal tracking bar"
        ]),
        ("ROLE 2 & 3 • POS KASIR, KDS & ADMIN (9 LAYAR)", [
            "08_pos_kanban.png — Kitchen Display Kanban 3 Kolom",
            "09_pos_walkin.png — Touchscreen POS kasir fisik & keypad",
            "10_admin_analytics.png — Executive dashboard omzet & jam ramai",
            "11_admin_menu_crud.png — Manajemen 15 menu & saklar stok",
            "12_admin_ai_config.png — Prompt tuning Mas Nongki Gemini",
            "01-04_admin_mobile_*.png — 4 Layar Mobile Executive Admin"
        ])
    ]
    u_w = 280
    for i, (grp_t, screen_list) in enumerate(ui_groups):
        ux = 40 + i * (u_w + 20)
        s11.add_card(ux, 125, u_w, 355, bg_color=C_BG_CARD, border_color=C_BORDER_LIGHT, border_width=1.5)
        s11.add_textbox(ux + 14, 140, u_w - 28, 26, grp_t, font_size=8.5, bold=True, text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, align="CENTER")
        s11.add_shape("RECTANGLE", ux + 14, 172, u_w - 28, 1, bg_color=C_BORDER_LIGHT)
        scr_text = "\n\n".join([f"• {s}" for s in screen_list])
        s11.add_textbox(ux + 14, 180, u_w - 28, 285, scr_text, font_size=8.5, bold=False, text_color=C_TEXT_BODY)
    all_builders.append(s11)

    # ==========================================================
    # SLIDE 12: KASIR POS WALKIN
    # ==========================================================
    s12 = GoogleSlideBuilder(slide_ids[11])
    s12.setup_header("Antarmuka Pengguna: Kasir POS", "Kasir POS Fisik: Penanganan Cepat Pesanan Walk-In", "Mendukung pelanggan yang memesan langsung tanpa smartphone dengan input touch-screen cepat")
    pos_cards = [
        ("INPUT CEPAT TOUCH-SCREEN", "Keypad Nominal & Hitung Kembalian Otomatis", [
            "Mendukung pesanan Dine-In maupun Takeaway di meja kasir.",
            "Touch-screen catalog grid dengan filter kategori instan.",
            "NumPad nominal uang tunai cepat (Rp 50.000, Rp 100.000, atau Uang Pas).",
            "Kalkulasi kembalian otomatis bebas risiko salah hitung kasir."
        ]),
        ("INTEGRASI TIKET LANGSUNG", "Injeksi Tiket Instan ke Kitchen Display System", [
            "Seketika transaksi walk-in selesai, tiket pesanan langsung terbit.",
            "Livewire event mentransmisikan data ke layar KDS Barista secara real-time.",
            "Pencetakan struk pembayaran resmi dengan detail pajak restoran PB1 10%.",
            "Mendukung multi-tender pembayaran: Tunai, Debit, Kartu Kredit, atau QRIS Kasir."
        ])
    ]
    for i, (tag_f, tit_f, pts) in enumerate(pos_cards):
        cx = 40 + i * 455
        s12.add_card(cx, 125, 425, 355, bg_color=C_BG_CARD, border_color=C_BORDER_LIGHT, border_width=1.5)
        s12.add_textbox(cx + 20, 142, 180, 22, tag_f, font_size=8, bold=True, text_color=C_GREEN, bg_color=C_GREEN_SOFT_BG)
        s12.add_textbox(cx + 20, 170, 385, 24, tit_f, font_size=12, bold=True, text_color=C_TEXT_DARK)
        s12.add_shape("RECTANGLE", cx + 20, 198, 385, 1, bg_color=C_BORDER_LIGHT)
        pts_text = "\n\n".join([f"• {p}" for p in pts])
        s12.add_textbox(cx + 20, 210, 385, 250, pts_text, font_size=9.5, bold=False, text_color=C_TEXT_BODY)
    all_builders.append(s12)

    # ==========================================================
    # SLIDE 13: EXECUTIVE ADMIN & OWNER
    # ==========================================================
    s13 = GoogleSlideBuilder(slide_ids[12])
    s13.setup_header("Antarmuka Pengguna: Admin & Owner", "Executive Dashboard: Pantau Bisnis Kapan Saja", "Fleksibilitas ganda: Analisis mendalam di PC Desktop dan kontrol lantai via Mobile")
    adm_cards = [
        ("DESKTOP ANALYTICS", "Superadmin Web Portal", [
            "Ringkasan KPI: Omzet kotor, total transaksi, rata-rata order, dan menu terlaris.",
            "Heatmap Jam Sibuk: Distribusi jam pemesanan tertinggi untuk alokasi shift barista.",
            "CRUD 15 Menu: Pembaruan harga, deskripsi, profil rasa, dan toggle ketersediaan stok.",
            "Prompt Tuning Sandbox: Uji coba dan penyempurnaan instruksi sistem Mas Nongki Gemini."
        ]),
        ("MOBILE EXECUTIVE APP", "Owner Mobility On-The-Go", [
            "Hero Omzet Card: Total penjualan harian dan target omzet kafe dalam genggaman.",
            "Quick Stock Switch: Matikan menu yang habis dalam 1 ketukan jari saat berkeliling kafe.",
            "Live Floor Table Monitor: Status real-time tiap meja (Available, Occupied, Reserved).",
            "Notifikasi Kritis: Peringatan jika stok bahan atau menu berada di bawah batas minimum."
        ])
    ]
    for i, (tag_f, tit_f, pts) in enumerate(adm_cards):
        cx = 40 + i * 455
        s13.add_card(cx, 125, 425, 355, bg_color=C_BG_CARD, border_color=C_BORDER_LIGHT, border_width=1.5)
        s13.add_textbox(cx + 20, 142, 170, 22, tag_f, font_size=8, bold=True, text_color=C_AMBER, bg_color=C_AMBER_SOFT_BG)
        s13.add_textbox(cx + 20, 170, 385, 24, tit_f, font_size=12, bold=True, text_color=C_TEXT_DARK)
        s13.add_shape("RECTANGLE", cx + 20, 198, 385, 1, bg_color=C_BORDER_LIGHT)
        pts_text = "\n\n".join([f"• {p}" for p in pts])
        s13.add_textbox(cx + 20, 210, 385, 250, pts_text, font_size=9.5, bold=False, text_color=C_TEXT_BODY)
    all_builders.append(s13)

    # ==========================================================
    # SLIDE 14: PENGUJIAN SISTEM & PERFORMA
    # ==========================================================
    s14 = GoogleSlideBuilder(slide_ids[13])
    s14.setup_header("Pengujian & Evaluasi", "Hasil Pengujian Black-Box & Metrik Performa Sistem", "Verifikasi stabilitas seluruh skenario fungsional dan kecepatan respon aplikasi")
    metrics = [
        ("100% LULUS", "Pengujian Fungsional Black-Box", "18 dari 18 test cases lolos verifikasi sempurna (alur check-in meja, katalog Livewire, prompt AI Gemini, order QRIS, KDS status, dan admin CRUD)."),
        ("~1.1 DETIK", "Latensi Respon AI Barista", "Google Gemini 1.5 Flash mengembalikan rekomendasi JSON structured dalam durasi sangat responsif dengan fallback aman jika kuota limit."),
        ("< 15 MS", "Waktu Respon Database MariaDB", "Eksekusi query Eloquent sangat ringan berkat indeks kunci primer, indeks access_code, dan normalisasi struktur tabel 3NF."),
        ("< 2.5 KB", "Beban Bandwidth Polling Livewire", "Payload delta wire:poll.3s sangat hemat kuota data dan tidak membebani jaringan router WiFi kafe.")
    ]
    for i, (val, tit_m, desc_m) in enumerate(metrics):
        mx = 40 + (i % 2) * 455
        my = 125 + (i // 2) * 185
        s14.add_card(mx, my, 425, 170, bg_color=C_BG_CARD, border_color=C_BORDER_LIGHT, border_width=1.5)
        s14.add_textbox(mx + 16, my + 14, 130, 30, val, font_size=13, bold=True, text_color=C_PRIMARY_BLUE, bg_color=C_BLUE_SOFT_BG, align="CENTER")
        s14.add_textbox(mx + 155, my + 14, 255, 30, tit_m, font_size=11, bold=True, text_color=C_TEXT_DARK)
        s14.add_shape("RECTANGLE", mx + 16, my + 52, 393, 1, bg_color=C_BORDER_LIGHT)
        s14.add_textbox(mx + 16, my + 60, 393, 98, desc_m, font_size=9.5, bold=False, text_color=C_TEXT_BODY)
    all_builders.append(s14)

    # ==========================================================
    # SLIDE 15: DAMPAK BISNIS & ROI
    # ==========================================================
    s15 = GoogleSlideBuilder(slide_ids[14])
    s15.setup_header("Dampak Bisnis & Nilai Tambah", "Kuantifikasi Dampak Bisnis & Efisiensi Operasional", "Manfaat nyata bagi pemilik usaha, efisiensi staf, dan pengalaman pengunjung kafe")
    impacts = [
        ("-75% WAKTU", "Waktu Antrean Terpangkas", "Waktu dari tamu duduk hingga pesanan masuk ke dapur terpangkas dari 8 menit menjadi hanya 2 menit.", C_BLUE_SOFT_BG, C_PRIMARY_BLUE),
        ("+18.4% OMZET", "Peningkatan Rata-rata Order (AOV)", "Rekomendasi AI Barista secara natural menyarankan pairing pastry & snack yang relevan, menaikkan basket size.", C_GREEN_SOFT_BG, C_GREEN),
        ("0% KESALAHAN", "Eliminasi Tiket Hilang & Salah Catat", "Pesanan langsung tercatat di MariaDB dan diteruskan ke KDS Dapur, menghapus human error salah dengar.", C_AMBER_SOFT_BG, C_AMBER)
    ]
    imp_w = 280
    for i, (stat, tit_i, desc_i, bg_ib, col_ib) in enumerate(impacts):
        ix = 40 + i * (imp_w + 20)
        s15.add_card(ix, 125, imp_w, 355, bg_color=C_BG_CARD, border_color=C_BORDER_LIGHT, border_width=1.5)
        s15.add_textbox(ix + 16, 142, imp_w - 32, 36, stat, font_size=15, bold=True, text_color=col_ib, bg_color=bg_ib, align="CENTER")
        s15.add_textbox(ix + 16, 190, imp_w - 32, 45, tit_i, font_size=12, bold=True, text_color=C_TEXT_DARK, align="CENTER")
        s15.add_shape("RECTANGLE", ix + 16, 242, imp_w - 32, 1, bg_color=C_BORDER_LIGHT)
        s15.add_textbox(ix + 16, 255, imp_w - 32, 210, desc_i, font_size=10, bold=False, text_color=C_TEXT_BODY)
    all_builders.append(s15)

    # ==========================================================
    # SLIDE 16: KESIMPULAN & ROADMAP
    # ==========================================================
    s16 = GoogleSlideBuilder(slide_ids[15])
    s16.setup_header("Kesimpulan & Roadmap", "Kesimpulan Proyek & Rencana Pengembangan Lanjutan", "Fondasi kokoh sistem POS modern dengan peluang ekspansi multi-cabang masa depan")
    s16.add_card(40, 125, 425, 355, bg_color=C_BG_CARD, border_color=C_BORDER_LIGHT, border_width=1.5)
    s16.add_textbox(58, 142, 389, 24, "KESIMPULAN PENGEMBANGAN SISTEM", font_size=11, bold=True, text_color=C_PRIMARY_BLUE)
    s16.add_shape("RECTANGLE", 58, 170, 389, 1, bg_color=C_BORDER_LIGHT)
    c_points = (
        "1. Arsitektur Reaktif Tanpa Kompleksitas Berlebih:\n"
        "   Kombinasi Laravel 11 dan Livewire 3 terbukti andal menghadirkan interaksi real-time tanpa memerlukan setup microservices yang rumit.\n\n"
        "2. Diferensiasi Nyata Melalui AI Generatif:\n"
        "   Integrasi Google Gemini 1.5 Flash sebagai Mas Nongki AI Barista mengubah POS statis menjadi asisten belanja interaktif yang disukai pelanggan.\n\n"
        "3. Standar Kualitas Akademik & Industri:\n"
        "   Perancangan basis data 3NF, flowchart standar ANSI/ISO 5807, dan 22 layar UI responsif siap diuji pada evaluasi sidang tugas akhir."
    )
    s16.add_textbox(58, 180, 389, 285, c_points, font_size=9.5, bold=False, text_color=C_TEXT_BODY)

    s16.add_card(495, 125, 425, 355, bg_color=C_BG_CARD, border_color=C_BORDER_LIGHT, border_width=1.5)
    s16.add_textbox(513, 142, 389, 24, "ROADMAP PENGEMBANGAN LANJUTAN", font_size=11, bold=True, text_color=C_TEXT_DARK)
    s16.add_shape("RECTANGLE", 513, 170, 389, 1, bg_color=C_BORDER_LIGHT)
    r_points = (
        "• FASE 1: Payment Gateway Webhook Terpadu\n"
        "  Implementasi Midtrans / Xendit Webhook callback untuk verifikasi pembayaran QRIS otomatis instan tanpa konfirmasi kasir manual.\n\n"
        "• FASE 2: Inventory Stock Recipe Deduction\n"
        "  Pengurangan stok bahan baku tingkat gramatur (biji kopi arabika, sirup karamel, susu fresh milk) secara otomatis tiap pesanan selesai diracik.\n\n"
        "• FASE 3: Multi-Outlet Cloud Sync & PWA Push\n"
        "  Sinkronisasi data antar cabang kafe terpusat dan notifikasi push browser saat pesanan siap dijemput di bar."
    )
    s16.add_textbox(513, 180, 389, 285, r_points, font_size=9.5, bold=False, text_color=C_TEXT_BODY)
    all_builders.append(s16)

    # ==========================================================
    # SLIDE 17: PENUTUP & Q&A
    # ==========================================================
    s17 = GoogleSlideBuilder(slide_ids[16])
    s17.add_shape("RECTANGLE", 0, 0, 960, 540, bg_color=C_PRIMARY_BLUE)
    s17.add_textbox(80, 70, 800, 32, "★  NONGKRONG.id • SMART CAFE POS SYSTEM", font_size=11, bold=True, text_color=C_WHITE, align="CENTER")
    s17.add_textbox(80, 115, 800, 75, "TERIMA KASIH", font_size=46, bold=True, text_color=C_WHITE, align="CENTER")
    s17.add_textbox(80, 195, 800, 30, "Sesi Tanya Jawab & Diskusi Terbuka (Q&A)", font_size=16, bold=False, text_color={"red": 0.86, "green": 0.92, "blue": 1.0}, align="CENTER")
    s17.add_shape("RECTANGLE", 280, 240, 400, 2, bg_color={"red": 0.23, "green": 0.45, "blue": 0.82})

    q_box = (
        "Pengembang Proyek : Allam (puu)\n"
        "Repositori Sistem : nongkrong_id (Laravel 11, Livewire 3, Gemini AI)\n"
        "Status Validasi   : Basis Data 3NF, 18 Test Cases Passed, 22 UI Screens\n"
        "\n"
        "\"Fall in Love with Coffee in NONGKRONG.id\""
    )
    s17.add_card(200, 265, 560, 160, bg_color={"red": 0.05, "green": 0.22, "blue": 0.55}, border_color={"red": 0.25, "green": 0.45, "blue": 0.85}, border_width=1.5)
    s17.add_textbox(220, 280, 520, 130, q_box, font_size=11, bold=False, text_color=C_WHITE, align="CENTER")
    s17.add_textbox(80, 465, 800, 25, "Presentasi Tugas Akhir Rekayasa Perangkat Lunak • 2026", font_size=9.5, text_color={"red": 0.75, "green": 0.85, "blue": 1.0}, align="CENTER")
    all_builders.append(s17)

    # 2. Batch update requests per slide to avoid huge payload timeouts
    print("Sending design requests to Google Slides API...")
    for idx, bld in enumerate(all_builders, start=1):
        if bld.reqs:
            slides_service.presentations().batchUpdate(presentationId=pres_id, body={"requests": bld.reqs}).execute()
            print(f"Slide {idx:02d} rendered ({len(bld.reqs)} requests).")

    url = f"https://docs.google.com/presentation/d/{pres_id}/edit"
    print("=" * 60)
    print("SUCCESS! GOOGLE SLIDES CREATED DIRECTLY ON USER ACCOUNT:")
    print(url)
    print("=" * 60)
    return {"presentationId": pres_id, "url": url}

if __name__ == "__main__":
    res = build_presentation()
    print(json.dumps(res, indent=2))
