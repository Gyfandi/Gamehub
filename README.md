# 🎮 GameHub

GameHub adalah aplikasi web **marketplace jual-beli game digital** (terinspirasi dari Steam) yang dibangun menggunakan **Laravel 13**. Aplikasi ini memungkinkan pengguna untuk menjelajahi katalog game, membeli game, mengelola wishlist, memberikan review, serta menyediakan panel admin untuk mengelola seluruh data toko.

---

## 📋 Daftar Isi

- [Fitur](#-fitur)
- [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
- [Struktur Database](#-struktur-database)
- [Instalasi & Setup](#-instalasi--setup)
- [Akun Demo](#-akun-demo)
- [Struktur Project](#-struktur-project)
- [Role & Hak Akses](#-role--hak-akses)
- [Catatan Tambahan](#-catatan-tambahan)

---

## ✨ Fitur

### 👤 Sisi Pengguna (Buyer)
- **Landing Page** — Menampilkan game unggulan, trending games, special offers (diskon aktif), top sellers, dan new releases
- **Katalog Game** — Pencarian (judul, developer, publisher, kategori), filter berdasarkan kategori, dan sorting (harga, rating, terbaru)
- **Detail Game** — Informasi lengkap game, system requirements, review pengguna lain, dan game terkait (related games)
- **Keranjang Belanja (Cart)** — Tambah/hapus game dari keranjang sebelum checkout
- **Checkout** — Proses pembelian dengan beberapa metode pembayaran, otomatis mengurangi stok dan menambahkan game ke Library
- **Library** — Daftar game yang sudah dimiliki, lengkap dengan playtime dan tanggal terakhir dimainkan
- **Wishlist** — Simpan game yang ingin dibeli nanti
- **Review & Rating** — Beri ulasan dan rekomendasi (like/dislike) untuk game yang sudah dimiliki
- **Profil Pengguna** — Kelola data akun dan lihat riwayat transaksi

### 🛠️ Sisi Admin
- **Dashboard** — Statistik total pengguna, total game, total transaksi, total pendapatan, grafik penjualan bulanan, top selling games, dan log aktivitas
- **Manajemen Game** — CRUD (Create, Read, Update, Delete) data game
- **Manajemen Kategori** — CRUD kategori game
- **Manajemen Publisher** — CRUD data publisher/penerbit game
- **Manajemen Diskon** — Buat dan kelola promo diskon per game dengan periode waktu tertentu
- **Manajemen Pengguna** — Lihat daftar pengguna dan ubah role (admin/buyer)
- **Log Transaksi** — Lihat seluruh riwayat transaksi yang terjadi di platform

---

## 🧰 Teknologi yang Digunakan

| Komponen | Teknologi |
|---|---|
| Backend Framework | Laravel 13 (PHP ^8.3) |
| Templating | Blade |
| Styling | Tailwind CSS 4 |
| Build Tool | Vite |
| Database | Oracle Database (via `yajra/laravel-oci8`) |
| Autentikasi | Laravel Auth bawaan (session-based) |

---

## 🗄️ Struktur Database

Entity utama dalam aplikasi ini:

```
users ──┬── carts ── cart_items ── games ──┬── categories
        │                                   ├── publishers
        ├── libraries ─────────────────────┤
        ├── wishlists ─────────────────────┤
        ├── reviews ───────────────────────┤
        ├── transactions ── transaction_details
        └── activity_logs

games ── discounts (diskon dengan periode start_date - end_date)
```

**Tabel-tabel utama:**
- `users` — data pengguna (role: `admin` / `buyer`)
- `games` — data game (judul, harga, stok, rating, developer, dll)
- `categories`, `publishers` — kategori dan penerbit game
- `discounts` — diskon game berdasarkan periode waktu
- `carts`, `cart_items` — keranjang belanja
- `transactions`, `transaction_details` — riwayat & rincian transaksi
- `libraries` — game yang dimiliki user setelah pembelian
- `wishlists` — daftar keinginan user
- `reviews` — ulasan & rating dari user terhadap game
- `activity_logs` — log aktivitas admin (tambah/ubah/hapus data)

---

## ⚙️ Instalasi & Setup

### Prasyarat
Pastikan sudah terinstall di komputer kamu:
- PHP >= 8.3
- Composer
- Node.js & npm
- Oracle Database (XE/Express Edition direkomendasikan untuk lokal) yang sudah berjalan dan punya service `XEPDB1`
- Ekstensi PHP `oci8` aktif (dibutuhkan oleh driver `yajra/laravel-oci8`)

### Langkah-langkah

1. **Clone repository**
   ```bash
   git clone <url-repository-ini>
   cd gamehub
   ```

2. **Install dependency PHP**
   ```bash
   composer install
   ```

3. **Install dependency JavaScript**
   ```bash
   npm install
   ```

4. **Konfigurasi environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Lalu sesuaikan kredensial Oracle di `.env` sesuai instalasi Oracle Database kamu:
   ```dotenv
   DB_CONNECTION=oracle
   DB_HOST=127.0.0.1
   DB_PORT=1521
   DB_DATABASE=XEPDB1
   DB_SERVICE_NAME=XEPDB1
   DB_USERNAME=system
   DB_PASSWORD=password_oracle_kamu
   ```

5. **Pastikan Oracle Database sudah running** dan service/PDB (`XEPDB1` atau sesuai konfigurasi kamu) sudah aktif dan bisa diakses.

6. **Jalankan migrasi & seeder**
   ```bash
   php artisan migrate --seed
   ```
   Perintah ini akan membuat seluruh tabel di Oracle sekaligus mengisi data awal (kategori, publisher, user demo, daftar game, dan diskon).

7. **Build asset frontend**
   ```bash
   npm run build
   ```
   atau untuk mode development dengan hot-reload:
   ```bash
   npm run dev
   ```

8. **Jalankan server lokal**
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses di `http://localhost:8000`

> 💡 **Tips:** Kamu juga bisa menjalankan semua proses (server, queue, log, vite) sekaligus dengan satu perintah:
> ```bash
> composer run dev
> ```

> ⚠️ **Catatan:** Aplikasi ini menggunakan **Oracle Database** sebagai satu-satunya database yang didukung penuh (lewat package `yajra/laravel-oci8`). Pastikan ekstensi PHP `oci8` sudah aktif dan Oracle Instant Client sudah terpasang di sistem kamu sebelum menjalankan `composer install` / `php artisan migrate`.

---

## 🔑 Akun Demo

Setelah menjalankan `php artisan migrate --seed`, kamu bisa login menggunakan akun berikut:

| Role | Email | Password |
|---|---|---|
| Admin | `admin@gamehub.com` | `admin123` |
| Buyer | `demo@gamehub.com` | `demo123` |
| Buyer | `gamer@gamehub.com` | `gamer123` |

---

## 📁 Struktur Project

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          → Controller khusus panel admin (CRUD game, kategori, dll)
│   │   ├── AuthController.php
│   │   ├── StoreController.php     → Landing, katalog, detail game
│   │   ├── CartController.php
│   │   ├── CheckoutController.php
│   │   ├── LibraryController.php
│   │   ├── ProfileController.php
│   │   ├── ReviewController.php
│   │   └── WishlistController.php
│   └── Middleware/
│       ├── AdminMiddleware.php     → Membatasi akses hanya untuk role admin
│       └── BuyerMiddleware.php     → Membatasi akses hanya untuk role buyer
├── Models/                  → Eloquent model (Game, User, Transaction, dll)
database/
├── migrations/               → Skema database
├── seeders/                  → Data awal/dummy
resources/
├── views/                    → Tampilan Blade
│   ├── admin/                → Halaman panel admin
│   ├── auth/                 → Halaman login & register
│   └── layouts/              → Layout utama
routes/
└── web.php                   → Definisi seluruh route aplikasi
```

---

## 🔐 Role & Hak Akses

Aplikasi ini memiliki dua jenis role pengguna:

| Role | Akses |
|---|---|
| **buyer** | Berbelanja, mengelola cart/wishlist/library, memberi review, mengubah profil |
| **admin** | Seluruh akses buyer + akses penuh ke panel admin (`/admin/*`) untuk mengelola game, kategori, publisher, diskon, pengguna, dan transaksi |

Route admin dilindungi oleh middleware `auth` dan `admin`, sehingga hanya user dengan role `admin` yang bisa mengaksesnya.

---

## 📝 Catatan Tambahan

- **Playtime & Last Played** pada halaman Library bersifat **simulasi/mock** (dihasilkan secara deterministik dari ID game), bukan tracking waktu bermain yang sesungguhnya — fitur ini ditambahkan untuk memperkaya tampilan demo.
- Review hanya dapat diberikan oleh user yang **sudah memiliki game tersebut** di Library (divalidasi di `ReviewController`).
- Harga final game (`final_price`) dihitung otomatis berdasarkan diskon yang sedang aktif (jika ada), melalui accessor pada model `Game`.
- Proses checkout dibungkus dalam database transaction (`DB::transaction`) untuk memastikan konsistensi data antara pengurangan stok, pencatatan transaksi, dan penambahan ke library.

---

## 📄 Lisensi

Project ini dibuat untuk keperluan tugas Basis Data.
