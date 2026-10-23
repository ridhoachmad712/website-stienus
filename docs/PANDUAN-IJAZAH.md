# Panduan Fitur Ijazah (CMS STIE Nusantara Makassar)

Fitur ijazah kini menyatu di dalam CMS Laravel/Filament (`/admin`) — bukan lagi
aplikasi HTML statis terpisah. Data, login, riwayat, QR verifikasi, dan impor
tersimpan di **database server**, sehingga dapat diakses banyak petugas dari
komputer berbeda (berbeda dengan editor statis lama yang hanya menyimpan di
localStorage satu browser).

## Menu

- **Akademik → Ijazah** (`/admin/ijazah`)
  Daftar & dashboard ijazah: kartu ringkasan (berlaku, per-prodi Akuntansi/
  Manajemen, draf menunggu terbit, dicabut), pencarian, filter status, tambah,
  edit, terbitkan QR, cabut, hapus, **Cetak ijazah**, dan **Impor Excel/CSV**.
- **Pengaturan → Template Ijazah** (`/admin/pengaturan-ijazah`)
  Teks tetap ijazah: template umum + per-prodi Akuntansi & Manajemen, dan
  tombol tampilkan QR. Akreditasi Manajemen sengaja kosong — isi dari dokumen
  resmi sebelum mencetak, jangan dikarang.
- **Pengaturan → Tata Letak Ijazah** (`/admin/tata-letak-ijazah`)
  Editor posisi: geser tiap blok teks (atau blok QR) pada pratinjau A4,
  atur X/Y (mm), ukuran (pt), lebar (mm), dan titik acuan. Garis tengah
  148,5 mm dengan tempel otomatis + tombol Tengahkan. Klik **Simpan tata letak**.

## Alur kerja satu ijazah

1. **Akademik → Ijazah → Tambah ijazah.** Isi data (termasuk **Tempat lahir**
   dan **Tanggal lahir** untuk badan ijazah). Simpan sebagai draf.
2. Periksa data dengan arsip, lalu **Terbitkan QR** (data terkunci setelah ini).
3. **Cetak ijazah** → tab baru menampilkan pratinjau A4 lanskap latar putih.
   Klik **Cetak / Simpan PDF**, pilih **A4 lanskap, skala 100%, margin tanpa
   batas**. Cetak di atas blanko fisik. Kode QR verifikasi ikut tercetak
   (dapat dimatikan di Template Ijazah).

> Sebelum bisa dicetak, tempat lahir, tanggal lahir, dan akreditasi program
> studi terkait harus terisi; jika belum, halaman cetak menampilkan daftar yang
> perlu dilengkapi.

## Impor banyak mahasiswa (Excel .xlsx)

1. **Unduh template → Template Excel (.xlsx).**
2. Isi satu baris per mahasiswa. Kolom: `student_name, student_number,
   birth_place, birth_date, diploma_number, institution_name, program_name,
   degree, graduation_date, issue_date, source_reference`.
   - Tanggal: format `YYYY-MM-DD` atau tanggal Excel asli.
   - Agar nol di awal pada nomor/NIM tidak hilang, **format kolom sebagai Teks**.
   - Maksimal 500 baris. Jika ada satu baris salah, **seluruh impor dibatalkan**.
3. **Impor Excel** → semua baris masuk sebagai **draf**; periksa lalu terbitkan
   QR satu per satu. (Impor CSV lama tetap tersedia dengan kolom yang sama.)

## Catatan hosting (jujur)

Ini aplikasi **Laravel**, jadi perlu hosting yang mendukung PHP, bukan hosting
statis biasa:

- PHP 8.3 + Composer + database (MySQL/MariaDB untuk produksi; lokal memakai SQLite).
- Deploy: `composer install --no-dev`, atur `.env` (termasuk
  `DIPLOMA_VERIFICATION_URL` ke alamat **HTTPS** resmi kampus), `php artisan key:generate`
  bila perlu, `php artisan migrate --force`, `npm ci && npm run build`,
  `php artisan storage:link`, lalu arahkan document root web ke folder `public/`.
- QR hanya bisa diterbitkan jika `DIPLOMA_VERIFICATION_URL` berupa HTTPS resmi
  (bukan localhost) saat environment produksi.

## Penyimpanan

Semua data ijazah, template, dan tata letak tersimpan di **database + tabel
settings** server — dipakai bersama seluruh petugas. Tidak ada lagi
ekspor/impor JSON per-browser seperti editor statis lama.
