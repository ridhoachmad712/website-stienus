# Panduan Fitur Transkrip Nilai (CMS STIE Nusantara Makassar)

Pembuat transkrip akademik terintegrasi di CMS Laravel/Filament (`/admin`),
berbagi login & database dengan fitur ijazah. Transkrip dicetak **penuh di
atas kertas polos A4 potret** (kop + logo ikut tercetak).

## Menu

- **Akademik → Transkrip Nilai** (`/admin/transkrip`): daftar transkrip
  (NIM, nama, prodi, SKS, IPK, status), cari/filter, tambah, edit, **Cetak**, hapus.
- **Pengaturan → Template Transkrip** (`/admin/pengaturan-transkrip`): kop
  (nama institusi, alamat, akreditasi), default identitas (fakultas/jurusan/
  prodi/program/konsentrasi), penandatangan, **bobot nilai** (A–E), **ambang
  predikat**, pilihan kategori mata kuliah, dan saklar area foto. Default
  mengikuti STIE Nusantara Makassar; logo diambil dari Pengaturan Umum.

## Alur kerja

1. **Tambah transkrip** → isi identitas mahasiswa (prefill dari Pengaturan),
   simpan. Otomatis masuk ke halaman edit.
2. Di tab **Mata Kuliah & Nilai**:
   - **Tambah mata kuliah** manual (bisa ambil dari katalog mata kuliah untuk
     mengisi kode/nama/SKS otomatis), pilih kelompok & nilai. Baris bisa
     di-urутkan (drag).
   - atau **Impor Excel**: unduh **Template Excel**, isi, lalu unggah.
3. **Cetak transkrip** → tab baru menampilkan A4 potret. Klik **Cetak / Simpan
   PDF**, pilih **A4 potret, skala 100%, margin tanpa batas**.

> IPK, total SKS, nilai mutu, dan predikat dihitung otomatis. Predikat
> mengikuti ambang di Pengaturan, atau bisa ditimpa manual per transkrip.

## Format Excel impor mata kuliah

Satu baris per mata kuliah. Kolom (baris pertama = judul kolom):

| category | kode | nama | sks | grade |
|----------|------|------|-----|-------|
| Matakuliah Keahlian dan Keterampilan | 111013 | Pengantar Manajemen | 3 | A |

- `grade` sesuai bobot di Pengaturan (default A/B/C/D/E).
- `category` menentukan pengelompokan; cocokkan dengan pilihan kategori agar
  urutan grup rapi saat cetak.
- Maksimal 500 baris; jika ada baris salah, **seluruh impor dibatalkan**.
- Agar nol di awal (kode) tidak hilang, format kolom sebagai Teks.

## Perhitungan

- **KxN** = SKS × bobot nilai.
- **Nilai Mutu** = Σ KxN. **IPK** = Nilai Mutu ÷ total SKS (2 desimal).
- **Predikat** = label pertama yang IPK-nya ≥ ambang minimum di Pengaturan
  (atau nilai override per transkrip). Ambang default bersifat umum —
  **sesuaikan dengan peraturan akademik resmi**; jangan dianggap baku.

## Hosting

Sama seperti fitur lain (aplikasi Laravel): saat deploy jalankan
`php artisan migrate` (termasuk migrasi settings), `npm run build`,
`php artisan storage:link` (agar logo & pas foto tampil). Lihat juga
[PANDUAN-IJAZAH.md](PANDUAN-IJAZAH.md).
