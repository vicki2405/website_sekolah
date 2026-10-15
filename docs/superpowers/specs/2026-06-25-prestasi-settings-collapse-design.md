# Prestasi Settings Collapse Design

Tanggal: 2026-06-25
Status: Draft tervalidasi bersama user
Topik: Box `Pengaturan Halaman Prestasi` bisa dilipat/hide

## Latar Belakang

Halaman admin `prestasi` memiliki dua card utama:

- `Pengaturan Halaman Prestasi`
- `Daftar Prestasi`

User ingin card pengaturan bisa disembunyikan agar halaman lebih ringkas, terutama saat fokus mengelola daftar item prestasi. Perubahan hanya berlaku pada card pengaturan, bukan seluruh card admin.

## Tujuan

- Membuat box `Pengaturan Halaman Prestasi` dapat dilipat dan dibuka kembali.
- Menjadikan kondisi awal card pengaturan dalam keadaan tertutup.
- Menjaga `Daftar Prestasi` tetap tampil seperti sekarang.

## Keputusan Desain

Pendekatan yang dipilih adalah toggle sederhana inline di Blade:

- hanya untuk box `Pengaturan Halaman Prestasi`
- default tertutup saat halaman dibuka
- dibuka/ditutup melalui klik pada header card
- tidak menyimpan status buka/tutup ke `localStorage`

Pendekatan ini dipilih karena paling ringan, cepat, dan sesuai kebutuhan yang fokus hanya pada satu card.

## Perilaku UI

### Saat Halaman Dimuat

- Card `Pengaturan Halaman Prestasi` tetap terlihat header-nya.
- Isi form pengaturan tersembunyi.
- Ikon indikator menampilkan kondisi tertutup.

### Saat Header Diklik

- Isi card akan tampil atau tersembunyi.
- Ikon indikator berputar atau berubah arah untuk menunjukkan status.

### Saat Form Disubmit

- Perilaku tetap sama seperti sekarang.
- Setelah reload pasca submit, card kembali ke kondisi default tertutup.

## Struktur Implementasi

Perubahan hanya pada file view:

- `resources/views/admin/achievements.blade.php`

Rencana perubahan:

1. Ubah header card pengaturan menjadi area klik yang jelas.
2. Tambahkan tombol/ikon `chevron` sebagai indikator expand/collapse.
3. Bungkus isi form dalam container yang bisa diberi class `hidden`.
4. Tambahkan skrip kecil inline untuk toggle class `hidden` dan rotasi ikon.

## Aksesibilitas

- Header card sebaiknya menggunakan elemen `button`, bukan `div` biasa.
- Tambahkan `aria-expanded` untuk mencerminkan status terbuka/tertutup.
- Tambahkan `aria-controls` yang mengarah ke container isi card.

## Non-Tujuan

- Tidak mengubah `Daftar Prestasi`
- Tidak membuat komponen accordion reusable untuk semua halaman admin
- Tidak menyimpan preferensi buka/tutup per user

## Rencana Uji

1. Buka halaman `/admin/prestasi`
   - card pengaturan tertutup secara default
2. Klik header `Pengaturan Halaman Prestasi`
   - isi form muncul
   - ikon status berubah
3. Klik lagi header yang sama
   - isi form tersembunyi kembali
4. Simpan pengaturan
   - submit tetap berhasil
   - setelah reload, card kembali tertutup

## Risiko

### Risiko 1

Area klik terlalu kecil atau tidak terasa sebagai kontrol.

Mitigasi:

- buat seluruh baris header terasa interaktif
- tampilkan ikon chevron yang jelas

### Risiko 2

User tidak sadar card dapat dibuka.

Mitigasi:

- gunakan affordance visual pada header
- tampilkan ikon expand/collapse yang konsisten

## Catatan

Perubahan ini sengaja dibuat sempit cakupannya agar tetap fokus pada kebutuhan saat ini. Jika pola ini nantinya ingin dipakai di halaman admin lain, implementasi dapat diekstrak menjadi pola accordion reusable setelah perilaku dasar ini terbukti cocok.
