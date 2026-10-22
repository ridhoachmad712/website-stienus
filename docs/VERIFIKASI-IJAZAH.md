# Verifikasi ijazah melalui QR

## Lokasi fitur

- Publik: `/verifikasi-ijazah` dan `/verifikasi-ijazah/{kode}`.
- Admin: **Akademik → Verifikasi Ijazah**, `/admin/ijazah`.
- QR dibuat oleh server sendiri, tanpa layanan QR pihak ketiga, berupa SVG yang dapat dicetak.
- Data dimasukkan manual atau diimpor dengan CSV. Belum ada sinkronisasi SIAKAD atau API PISN. Tautan PISN hanya membuka layanan nasional untuk pemeriksaan terpisah.

## Instalasi / deployment

1. Cadangkan database dan berkas aplikasi.
2. Jalankan `composer install --no-dev --optimize-autoloader` pada produksi. PHP minimum 8.3.
3. Atur `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` dan `DIPLOMA_VERIFICATION_URL` ke alamat **HTTPS resmi kampus**. Contoh: `DIPLOMA_VERIFICATION_URL=https://domain-resmi-kampus.ac.id`. Jangan gunakan domain contoh ini untuk penerbitan.
4. Jalankan `php artisan migrate --force`, `php artisan optimize:clear`, `php artisan config:cache` dan `php artisan filament:upgrade`. Jangan jalankan DatabaseSeeder pada produksi; berisi data contoh dan sekarang menolak lingkungan produksi.
5. Berikan izin petugas melalui **Pengguna & Akses → Roles**. Super admin memiliki akses melalui konfigurasi Shield yang sudah ada. Ganti sandi admin bawaan jika sebelumnya pernah dipakai; perubahan seeder tidak mengganti sandi akun yang sudah ada.
6. Uji penerbitan dengan lingkungan uji terpisah, pembacaan QR dari berkas SVG serta hasil cetak, dan status pencabutan. QR lokal tidak boleh ditempelkan pada ijazah asli.

Database baru terdiri atas `diplomas` dan `diploma_events`; migrasi tidak mengubah isi data kampus yang sudah ada. Migrasi membuat tujuh permission dan tidak otomatis memberi akses pada role biasa.

## Izin

| Izin | Fungsi |
|---|---|
| `view_any_diploma` | Daftar ijazah |
| `view_diploma` | Detail dan riwayat |
| `create_diploma` | Membuat draf dan impor CSV |
| `update_diploma` | Memperbarui draf |
| `issue_diploma` | Menyetujui data dan menerbitkan QR |
| `revoke_diploma` | Mencabut status; juga diperlukan untuk menerbitkan pengganti |
| `download_diploma` | Mengunduh QR dan mencetak label yang masih berlaku |

Rekomendasi: operator hanya mendapat view/view_any/create/update. Pemeriksa mendapat view/view_any/issue/revoke/download. Super admin tetap dapat menangani seluruh alur; sistem tidak memaksa pemeriksa dan pembuat draf menjadi dua orang berbeda.

## Alur penerbitan

1. Tambahkan data sesuai arsip: nama, NIM, nomor ijazah, nama kampus, prodi, jenjang/gelar, tanggal kelulusan, tanggal penerbitan, serta referensi SK/arsip.
2. Data tersimpan sebagai **Draf**, belum memiliki kode publik.
3. Pemeriksa mencocokkan dengan arsip resmi, memilih **Terbitkan QR**, memeriksa domain tujuan, lalu menyatakan persetujuan.
4. Sistem mengunci data, membuat token acak 256 bit dan URL permanen, serta mencatat petugas dan waktu tindakan.
5. Unduh SVG atau buka **Cetak label QR**. Label memuat nama dan nomor untuk mengurangi kesalahan penempelan. Pertahankan ukuran QR 40 mm atau uji ulang bila ukuran diubah; jangan potong ruang putih di sekeliling QR.

Persetujuan di sini adalah pemeriksaan internal kampus, bukan tanda tangan elektronik tersertifikasi. Kampus menetapkan kewenangan petugas, dasar publikasi data, serta kecocokan data dengan PISN/arsip akademik.

## Koreksi dan pencabutan

- Draf dapat diperbarui. Setiap perubahan dicatat beserta nilai sebelum/sesudah.
- Data yang telah terbit tidak dapat diedit atau dihapus melalui modul.
- Untuk perbaikan, buat draf baru dengan nomor ijazah baru sesuai arsip, pilih catatan yang digantikan, dan isi alasan. NIM harus sama. Saat draf baru diterbitkan, perubahan status lama dan penerbitan baru dilakukan dalam satu transaksi.
- Untuk pencabutan, isi dasar/alasan minimal 10 karakter. Alasan hanya terlihat oleh petugas.
- QR lama tetap membuka status **Dicabut** atau **Diganti / diperbaiki**; tidak dialihkan otomatis ke status berlaku.
- Nomor ijazah bersifat unik. Alur penerbitan ulang menggunakan nomor yang sama akibat hilang/rusak perlu penanganan administrasi tersendiri; modul saat ini menangani pendaftaran awal, perbaikan dengan nomor baru, dan pencabutan.
- Penggantian NIM tidak disediakan melalui alur otomatis. Koreksi identitas inti perlu diperiksa dan ditangani secara khusus oleh kampus.

## Impor CSV

Unduh template pada daftar ijazah. Header dan urutannya harus tepat:

```csv
student_name,student_number,diploma_number,institution_name,program_name,degree,graduation_date,issue_date,source_reference
```

- UTF-8, pemisah koma, maksimum 500 baris dan ukuran unggahan 1 MB.
- Tanggal `YYYY-MM-DD`; tanggal penerbitan tidak mendahului kelulusan atau melampaui hari ini.
- Atur kolom NIM/nomor sebagai teks di aplikasi spreadsheet agar nol awal tidak hilang.
- Seluruh impor ditolak jika satu baris salah atau nomor ijazah duplikat. Pesan kesalahan menunjuk nomor baris.
- Impor tidak memperbarui data yang sudah ada dan tidak otomatis menerbitkan QR.

## Data publik dan keberlangsungan layanan

Halaman menampilkan nama, nomor ijazah, NIM tersamarkan, kampus, prodi, jenjang/gelar, tanggal kelulusan dan penerbitan. Referensi arsip, catatan internal, alasan pencabutan, serta identitas petugas tidak ditampilkan. Modul tidak mengumpulkan NIK, tanggal lahir, alamat, transkrip, atau berkas pindai.

Siapa pun yang memiliki tautan QR dapat melihat data minimum ini. Tidak tersedia pencarian alumni berdasarkan nama/NIM. Halaman diberi `noindex`, `no-store`, dan `no-referrer`, serta pembatasan permintaan. `noindex` bukan kontrol otorisasi. Hindari pencatatan URL lengkap beserta token pada analitik atau log eksternal; atur retensi log server sesuai kebijakan kampus.

URL dicatat saat penerbitan agar perubahan konfigurasi tidak diam-diam mengubah QR yang sudah dicetak. Jika domain berganti, pertahankan domain lama dan rute pengalihannya. Token tidak kedaluwarsa dan tidak bergantung pada APP_KEY. Cadangkan database, uji pemulihan, dan pertahankan HTTPS serta domain jangka panjang.

Riwayat bersifat tidak dapat diedit/dihapus melalui model dan UI aplikasi. Akses langsung administrator database tetap dapat mengubahnya; gunakan pembatasan akses database dan cadangan di luar server untuk audit yang lebih kuat.

## Pengujian pengembang

`composer install` kemudian `php artisan test`. PHPUnit 11 digunakan agar sesuai PHP 8.3. Formatter keluaran tes opsional Laravel Pao dihapus karena memerlukan PHPUnit 12 yang membutuhkan PHP 8.4.

Pengujian fitur mencakup data publik dan escaping, draf tersembunyi, izin, QR/label, penerbitan berulang, pencabutan, perbaikan, penguncian data, audit, impor atomik, form/action Filament, dan seeder yang tidak menimpa sandi admin.
