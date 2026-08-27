# Aplikasi POS

## Gambaran Proyek

Bangun aplikasi **Point of Sale (POS)** berbasis web.

Aplikasi dikembangkan langsung di server ini dan harus berjalan menggunakan **Docker dan Docker Compose**.

Direktori project:

```text
/opt/pos-app/
```

Jangan membuat project di luar direktori tersebut.

---

## UI/UX Guidelines

Aplikasi harus memiliki tampilan modern, profesional, bersih, dan nyaman digunakan.

### Prinsip desain
- Jangan membuat UI yang terlihat seperti template CRUD standar.
- Prioritaskan kemudahan penggunaan untuk kasir.
- Gunakan layout yang konsisten di seluruh aplikasi.
- Gunakan spacing, typography, warna, border-radius, dan shadow secara konsisten.
- Hindari terlalu banyak warna.
- Gunakan visual hierarchy yang jelas.
- Pastikan tombol utama mudah ditemukan.
- Gunakan icon yang konsisten.
- Semua halaman harus responsive.

### POS / Kasir
- Halaman kasir harus dioptimalkan untuk penggunaan cepat.
- Produk mudah dicari.
- Sediakan search produk.
- Gunakan kategori/filter produk.
- Keranjang belanja selalu mudah terlihat.
- Total pembayaran harus sangat menonjol.
- Tombol "Bayar" harus menjadi primary action.
- Minimalkan jumlah klik untuk menyelesaikan transaksi.
- Gunakan keyboard shortcut jika memungkinkan.

### Dashboard
Dashboard harus menampilkan:
- Total penjualan hari ini
- Jumlah transaksi
- Produk terlaris
- Pendapatan
- Grafik penjualan
- Transaksi terbaru

Gunakan card, chart, badge, dan visualisasi yang modern.

### UX
- Gunakan loading state.
- Gunakan skeleton loading jika diperlukan.
- Gunakan empty state yang informatif.
- Gunakan confirmation dialog untuk tindakan berbahaya.
- Gunakan toast notification untuk feedback.
- Validasi form harus jelas.
- Error message harus mudah dipahami user.

### Responsive
Aplikasi harus nyaman digunakan pada:
- Desktop
- Laptop
- Tablet
- Mobile

### Konsistensi
Sebelum membuat komponen baru, periksa apakah komponen serupa sudah tersedia.
Hindari membuat style yang berbeda-beda untuk fungsi yang sama.

### Kualitas UI
Setiap halaman yang selesai dibuat harus diperiksa secara visual.
Jika UI terlihat terlalu sederhana, kosong, generik, atau seperti CRUD admin template, lakukan improvement sebelum dianggap selesai.

# Teknologi

Gunakan:

* Laravel
* PHP
* MySQL
* Blade
* Tailwind CSS
* Vite
* Docker
* Docker Compose

Gunakan versi Laravel stabil terbaru yang kompatibel dengan versi PHP yang tersedia.

Jangan menambahkan framework atau package yang tidak diperlukan.

---

# Arsitektur Docker

Aplikasi harus berjalan menggunakan Docker Compose.

Minimal sediakan container untuk:

* Laravel/PHP
* Web server
* MySQL

Arsitektur harus mudah dikembangkan nantinya untuk:

* Redis
* Queue Worker
* Scheduler
* Mail Service

Source code aplikasi tetap berada di host:

```text
/opt/pos-app/
```

Gunakan Docker volume dengan benar.

Jangan menginstall PHP, MySQL, Node.js, atau dependency aplikasi langsung di host jika dependency tersebut dapat dijalankan di dalam Docker.

---

# Keamanan Server

Server ini kemungkinan menjalankan aplikasi lain.

Sebelum melakukan perubahan:

1. Periksa kondisi server.
2. Periksa Docker dan Docker Compose.
3. Periksa container yang sedang berjalan.
4. Periksa port yang sedang digunakan.
5. Periksa kapasitas disk.
6. Periksa penggunaan RAM.
7. Periksa apakah `/opt/pos-app/` sudah berisi file.

Jangan:

* Menghapus container aplikasi lain.
* Menghapus Docker volume aplikasi lain.
* Menghapus database aplikasi lain.
* Mengubah aplikasi lain.
* Mengubah Docker Compose project lain.
* Mengubah firewall tanpa alasan yang jelas.
* Menghentikan service lain tanpa alasan.
* Mengubah konfigurasi aplikasi lain.

Jika akan melakukan operasi yang berpotensi menghapus data atau mengganggu service lain, minta konfirmasi terlebih dahulu.

---

# Struktur Project

Pertahankan struktur Laravel yang rapi.

Struktur yang diharapkan:

```text
/opt/pos-app/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
├── docker/
├── docker-compose.yml
├── Dockerfile
├── .env
├── .env.example
└── ...
```

Struktur Docker dapat disesuaikan jika terdapat arsitektur yang lebih baik.

---

# Modul Aplikasi POS

Aplikasi nantinya memiliki modul berikut.

## 1. Authentication

* Login
* Logout
* Authentication user
* Role Admin
* Role Kasir

---

## 2. Dashboard

Tampilkan:

* Total penjualan hari ini
* Jumlah transaksi hari ini
* Jumlah produk
* Produk dengan stok menipis
* Transaksi terbaru

---

## 3. Manajemen Produk

Fitur:

* Daftar produk
* Tambah produk
* Edit produk
* Hapus produk
* Kode produk/SKU
* Barcode
* Nama produk
* Kategori
* Harga beli
* Harga jual
* Stok
* Satuan
* Minimum stok

---

## 4. Manajemen Kategori

Fitur:

* Daftar kategori
* Tambah kategori
* Edit kategori
* Hapus kategori

---

## 5. POS / Transaksi Penjualan

Kasir harus dapat:

* Mencari produk
* Mencari berdasarkan barcode
* Menambahkan produk ke keranjang
* Mengubah quantity
* Menghapus produk dari keranjang
* Menghitung subtotal
* Memberikan diskon
* Menghitung total
* Memasukkan pembayaran
* Menghitung kembalian
* Menyelesaikan transaksi

Ketika transaksi selesai:

1. Validasi transaksi.
2. Periksa ketersediaan stok.
3. Buat transaksi penjualan.
4. Buat detail transaksi.
5. Kurangi stok produk.
6. Commit database transaction.

Gunakan **database transaction**.

Jika salah satu proses gagal, seluruh transaksi harus di-rollback.

Stok tidak boleh menjadi negatif.

---

## 6. Riwayat Transaksi

Tampilkan:

* Nomor transaksi
* Tanggal transaksi
* Kasir
* Total
* Pembayaran
* Kembalian
* Status

Buat halaman detail transaksi.

---

## 7. Struk

Setelah transaksi berhasil:

* Tampilkan struk.
* Sediakan tombol print.
* Nomor transaksi.
* Tanggal/waktu.
* Kasir.
* Produk.
* Quantity.
* Harga.
* Subtotal.
* Diskon.
* Total.
* Pembayaran.
* Kembalian.

Desain struk harus memungkinkan penggunaan **thermal printer** di kemudian hari.

---

## 8. Laporan

Sediakan:

* Laporan penjualan harian
* Laporan penjualan bulanan
* Jumlah transaksi
* Total omzet
* Laporan penjualan produk

Sistem laporan harus mudah dikembangkan untuk laporan tambahan.

---

## 9. Manajemen User

Admin dapat:

* Melihat user
* Membuat user
* Mengedit user
* Menghapus user
* Menentukan role user

---

# Database

Minimal buat tabel:

```text
users
categories
products
sales
sale_items
```

Relasi:

```text
Category
  └── Products

User
  └── Sales

Sale
  └── SaleItems

Product
  └── SaleItems
```

Gunakan:

* Foreign key
* Index
* Data type yang sesuai
* Unique constraint jika diperlukan
* Timestamp

Database harus dirancang agar mudah dikembangkan untuk modul tambahan di masa depan.

---

# Arsitektur Laravel

Ikuti standar dan best practice Laravel.

Gunakan:

* Eloquent Model
* Controller
* Form Request
* Policy
* Middleware
* Service jika business logic mulai kompleks
* Migration
* Seeder
* Factory
* Feature Test
* Unit Test jika diperlukan

Jangan menaruh business logic yang kompleks di dalam Blade.

Hindari duplikasi logic.

Sebelum menambahkan package baru, pastikan package tersebut memang diperlukan.

---

# Keamanan

Implementasikan:

* Authentication
* Authorization
* CSRF protection
* Form validation
* Mass assignment protection
* Password hashing
* Database transaction
* Server-side validation

Jangan mempercayai data yang dikirim langsung dari browser.

Semua data penting harus divalidasi di server.

---

# Alur Pengembangan

Kerjakan aplikasi secara bertahap.

Jangan langsung membuat seluruh aplikasi dalam satu proses.

Urutan pengembangan:

## Tahap 1 — Pemeriksaan Environment

* Periksa server
* Periksa Docker
* Periksa Docker Compose
* Periksa resource server
* Periksa port yang digunakan

## Tahap 2 — Inisialisasi Project

* Buat Laravel project
* Buat Dockerfile
* Buat Docker Compose
* Konfigurasi MySQL
* Konfigurasi `.env`
* Jalankan container
* Pastikan Laravel dapat diakses

## Tahap 3 — Database

* Migration
* Model
* Relationship
* Factory
* Seeder

## Tahap 4 — Authentication

* Login
* Logout
* Role
* Authorization

## Tahap 5 — Produk

* Kategori
* Produk
* Stok

## Tahap 6 — POS

* Pencarian produk
* Keranjang
* Checkout
* Pembayaran
* Kembalian
* Transaksi
* Pengurangan stok

## Tahap 7 — Transaksi

* Riwayat transaksi
* Detail transaksi
* Struk

## Tahap 8 — Laporan

* Laporan harian
* Laporan bulanan
* Laporan produk

## Tahap 9 — Dashboard

* Statistik
* Transaksi terbaru
* Produk dengan stok rendah

## Tahap 10 — Testing

Lakukan testing terhadap seluruh workflow utama.

---

# Aturan Docker

Sebisa mungkin jalankan command aplikasi melalui container.

Contoh:

```bash
docker compose exec app php artisan migrate
docker compose exec app php artisan test
```

Jangan mengasumsikan PHP atau Composer tersedia langsung di host.

Sebelum menjalankan command, periksa nama service Docker menggunakan:

```bash
docker compose ps
```

Jangan menjalankan:

```bash
docker compose down -v
```

kecuali sudah mendapatkan izin secara eksplisit.

Perintah tersebut dapat menghapus volume database.

Jangan menghapus Docker volume selama development normal.

---

# Testing

Setelah membuat fitur, lakukan testing.

Minimal jalankan:

```bash
docker compose exec app php artisan test
```

Periksa juga:

```bash
docker compose ps
docker compose exec app php artisan migrate:status
docker compose exec app php artisan route:list
```

Jika terjadi error, periksa log Laravel:

```text
storage/logs/laravel.log
```

Periksa juga log container jika diperlukan:

```bash
docker compose logs
```

---

# Git

Jika project belum menggunakan Git, lakukan inisialisasi Git.

Buat `.gitignore` yang sesuai.

Jangan pernah commit:

```text
.env
password
API token
database credential
private key
secret
```

Pastikan `.env.example` selalu diperbarui ketika terdapat environment variable baru.

---

# Aturan Pengembangan

Sebelum mengimplementasikan fitur:

1. Periksa kode yang sudah ada.
2. Pahami arsitektur project.
3. Periksa migration yang sudah ada.
4. Periksa model yang sudah ada.
5. Periksa route yang sudah ada.
6. Gunakan kembali komponen yang sudah tersedia jika sesuai.
7. Hindari rewrite yang tidak diperlukan.

Setelah mengimplementasikan fitur:

1. Jalankan test.
2. Periksa log aplikasi.
3. Periksa status Docker.
4. Verifikasi fitur.
5. Perbaiki error sebelum melanjutkan.

Jangan meninggalkan error kritis yang sudah diketahui.

---

# Pelaporan

Setiap kali menyelesaikan sebuah task, laporkan:

* Apa yang telah dibuat.
* File yang dibuat.
* File yang diubah.
* Perubahan database.
* Perubahan Docker.
* Command yang dijalankan.
* Hasil testing.
* Error yang ditemukan.
* Solusi yang dilakukan.
* Langkah berikutnya.

Gunakan Bahasa Indonesia dalam seluruh laporan dan komunikasi.

---

# Tujuan Saat Ini

Bangun aplikasi POS secara bertahap di:

```text
/opt/pos-app/
```

Aplikasi harus berjalan menggunakan:

```text
Docker
Docker Compose
```

Jangan menganggap server dalam kondisi kosong.

Selalu periksa kondisi server dan project terlebih dahulu sebelum melakukan perubahan.

Mulai dengan pemeriksaan environment dan Docker, kemudian lanjutkan ke inisialisasi Laravel.
