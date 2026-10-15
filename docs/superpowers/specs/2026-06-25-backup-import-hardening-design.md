# Backup Import Hardening Design

Tanggal: 2026-06-25
Status: Draft tervalidasi bersama user
Topik: Pengamanan export/import backup agar tidak menimpa akun admin, role, dan permission

## Latar Belakang

Sistem backup saat ini mengekspor dan mengimpor data SQL berbasis dump data-only. File backup lama seperti `db/backup-admin-20260624_082258-NJC9L7.sql` tidak hanya membawa data konten, tetapi juga tabel akses sensitif:

- `users`
- `roles`
- `permissions`
- `model_has_roles`
- `model_has_permissions`
- `role_has_permissions`

Saat file seperti ini diimpor, sistem dapat menimpa akun admin aktif, menghapus role/permission saat runtime, dan menyebabkan akses `/admin` atau `/admin/backup` berubah menjadi `403`.

Masalah menjadi lebih berbahaya karena backup lama memakai statement `TRUNCATE`, sedangkan pada MySQL `TRUNCATE` melakukan implicit commit dan tidak ikut rollback normal dalam transaksi. Artinya, jika import gagal di tengah jalan, data akses sensitif bisa tetap hilang.

## Tujuan

- Mencegah import backup menimpa akun admin, role, dan permission.
- Menjaga kompatibilitas dengan file backup lama yang masih mengandung tabel sensitif.
- Menghasilkan backup baru yang hanya berisi data CMS/konten yang aman untuk dipulihkan.
- Mengurangi risiko `403` pasca restore tanpa mengubah alur kerja user di menu backup.

## Non-Tujuan

- Tidak membuat sistem backup schema penuh.
- Tidak memigrasikan akun pengguna antar lingkungan.
- Tidak menambahkan UI/halaman admin baru untuk wizard restore.
- Tidak mengubah model otorisasi inti Laravel/Spatie.

## Keputusan Desain

Pendekatan yang dipilih adalah gabungan:

1. Importer memfilter tabel sensitif secara permanen.
2. Exporter tidak lagi menulis tabel sensitif ke file backup baru.
3. File backup lama tetap dapat dipakai, tetapi statement untuk tabel sensitif akan diabaikan.

Pendekatan ini dipilih karena memberi perlindungan dua arah:

- restore lama tidak lagi merusak akses admin
- backup baru sejak perubahan ini tidak lagi membawa data akses sensitif

## Tabel Sensitif

Daftar tabel yang harus dikecualikan dari export dan import:

- `users`
- `roles`
- `permissions`
- `model_has_roles`
- `model_has_permissions`
- `role_has_permissions`

Daftar ini diperlakukan sebagai bagian dari keamanan sistem, bukan preferensi per-file.

## Arsitektur Perubahan

Lokasi perubahan utama: `app/Http/Controllers/Admin/BackupController.php`

Perubahan akan dibagi menjadi tiga unit logika:

### 1. Daftar Tabel Ekspor Aman

Tambahkan konsep `sensitiveTables()` atau daftar tabel sensitif terpusat.

`exportableTables()` akan:

- mengambil seluruh base table dari database
- membuang tabel teknis yang memang sudah dikecualikan saat ini
- membuang semua tabel sensitif
- mengembalikan hanya tabel konten/CMS yang aman

Hasilnya, file backup baru tidak lagi berisi user/role/permission.

### 2. Parser dan Filter Import

Importer akan tetap membaca file SQL lama, lalu:

- memvalidasi signature backup (`SMK_BACKUP_SQL_V1`)
- memecah statement SQL
- mengenali jenis statement yang diizinkan
- mengekstrak nama tabel
- mengabaikan statement yang menyasar tabel sensitif

Perilaku per statement:

- `SET NAMES utf8mb4`: boleh
- `SET FOREIGN_KEY_CHECKS`: diabaikan oleh importer karena dikelola server-side
- `TRUNCATE` pada tabel aman: diubah menjadi daftar tabel target yang akan dikosongkan secara aman
- `TRUNCATE` pada tabel sensitif: diabaikan
- `INSERT` pada tabel aman: dieksekusi
- `INSERT` pada tabel sensitif: diabaikan
- selain `SET`, `TRUNCATE`, `INSERT`: ditolak

### 3. Eksekusi Restore Aman

Setelah parsing selesai:

- importer menyusun daftar tabel aman yang perlu diganti isinya
- importer menghapus isi tabel aman dengan `DELETE` di dalam transaksi
- importer menjalankan `INSERT` untuk tabel aman saja

Konsekuensi desain:

- tabel akses sensitif tetap utuh
- kegagalan restore tidak merusak akun admin
- backup lama tetap kompatibel walau membawa statement berbahaya

## Perilaku User-Facing

### Export Baru

File export baru:

- tetap memakai format SQL data-only
- tetap memuat header `SMK_BACKUP_SQL_V1`
- tidak lagi memuat tabel akses sensitif

User tidak perlu mengubah alur penggunaan.

### Import File Lama

Jika file lama berisi tabel sensitif:

- importer tidak gagal hanya karena tabel itu ada
- importer melewati statement sensitif dan tetap memproses tabel aman

Jika setelah filtering tidak ada statement aman yang tersisa:

- tampilkan pesan jelas bahwa file tidak berisi tabel yang diizinkan untuk diimport

### Import File Baru

File hasil export baru akan lebih stabil karena dari sumbernya sudah tidak mengandung data akses sensitif.

## Error Handling

Pesan error/hasil perlu dibuat lebih eksplisit:

- file tidak valid: tetap seperti sekarang
- statement tidak diizinkan: tampilkan verb yang ditolak
- tabel tidak diizinkan: tampilkan nama tabel
- file hanya berisi tabel sensitif: tampilkan pesan khusus, misalnya:
  - `File backup tidak berisi tabel konten yang diizinkan untuk diimport`

Pesan sukses dapat tetap sederhana, tetapi idealnya menjelaskan bahwa restore hanya memulihkan data konten.

## Kompatibilitas

### Kompatibel

- backup lama yang memuat `TRUNCATE` dan `INSERT` untuk data konten
- backup lama yang juga memuat tabel sensitif, karena akan difilter
- backup baru hasil export setelah perubahan

### Tidak Didukung

- file SQL yang mengandung `DROP`, `ALTER`, `CREATE`, `UPDATE`, `DELETE`, `USE`, `LOCK`, `UNLOCK`
- file yang tidak punya signature backup sistem ini

## Risiko dan Mitigasi

### Risiko 1

Ada user yang berharap akun admin dari backup lama ikut dipulihkan.

Mitigasi:

- dokumentasikan bahwa backup/import hanya untuk data konten
- pertahankan akun admin sebagai bagian dari konfigurasi lingkungan aktif

### Risiko 2

File backup lama berisi hanya tabel sensitif sehingga user merasa import “gagal”.

Mitigasi:

- beri pesan hasil yang eksplisit
- jika perlu, tampilkan informasi bahwa tabel sensitif sengaja diabaikan

### Risiko 3

Ada tabel baru di masa depan yang juga sensitif tetapi lupa dimasukkan daftar blokir.

Mitigasi:

- sentralisasi daftar tabel sensitif di satu method
- gunakan daftar ini di export dan import sekaligus

## Rencana Uji

### Uji Fungsional

1. Export baru tidak memuat:
   - `users`
   - `roles`
   - `permissions`
   - `model_has_roles`
   - `model_has_permissions`
   - `role_has_permissions`

2. Import file lama yang memuat tabel sensitif:
   - tidak merusak akun admin aktif
   - tidak membuat `/admin` menjadi `403`

3. Import file lama tetap memulihkan data konten seperti:
   - `news`
   - `programs`
   - `sliders`
   - `gallery`
   - `teachers`

4. Setelah import:
   - `admin` tetap bisa akses `/admin`
   - `superadmin` tetap bisa akses `/admin/backup`
   - permission `admin.backup.import` tetap ada pada role yang ditetapkan sistem aktif

### Uji Regresi

- Export masih dapat diunduh sebagai file `.sql`
- Import tetap menolak file non-backup
- Import tidak mengeksekusi verb SQL di luar whitelist

## Dampak Implementasi

- Perubahan terfokus pada `BackupController`
- Tidak memerlukan perubahan schema database
- Tidak memerlukan perubahan route
- Tidak memerlukan perubahan UI wajib

## Pertanyaan Yang Sudah Diputuskan

- Apakah `users/roles/permissions` boleh ikut diimport? Tidak.
- Apakah file backup lama tetap harus didukung? Ya.
- Apakah export baru harus mengecualikan tabel sensitif? Ya.

## Catatan Operasional

Folder proyek saat ini bukan repository git aktif, sehingga dokumen ini tidak dapat di-commit dari workspace sekarang. Dokumen tetap disimpan di jalur spesifikasi proyek agar bisa ditinjau dan dipakai untuk implementasi berikutnya.
