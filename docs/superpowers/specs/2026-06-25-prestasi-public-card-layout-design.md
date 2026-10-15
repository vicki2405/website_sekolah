# Prestasi Public Card Layout Design

Tanggal: 2026-06-25
Status: Draft tervalidasi bersama user
Topik: Perapihan layout card halaman publik `prestasi`

## Latar Belakang

Card pada halaman publik `prestasi` saat ini menempatkan `value` di kanan atas dengan ukuran sangat dominan, sementara `title` diletakkan di bawahnya. Komposisi ini membuat hirarki visual kurang rapi dan fokus kartu terasa pecah.

User menginginkan tampilan yang lebih rapi, dengan `judul` sebagai fokus utama dan `nilai` dipindahkan ke bawah judul.

## Tujuan

- Menjadikan `title` sebagai fokus utama card.
- Memindahkan `value` menjadi elemen sekunder yang tetap terlihat jelas.
- Menjaga desain card tetap selaras dengan gaya visual halaman `prestasi`.

## Keputusan Desain

Pendekatan yang dipilih:

- ikon tetap berada di bagian atas card
- blok `value` besar di kanan atas dihapus
- `title` tampil lebih dulu sebagai elemen utama
- `value` diubah menjadi badge/chip kecil di bawah judul
- deskripsi tetap di bawah badge

Pendekatan ini dipilih karena memberi alur baca yang lebih natural:

1. ikon
2. judul
3. nilai
4. deskripsi

## Struktur Implementasi

Perubahan utama hanya pada:

- `resources/js/Pages/Prestasi.vue`

Rencana perubahan:

1. Hapus layout dua kolom pada header card yang saat ini memisahkan ikon dan `value`.
2. Pertahankan ikon di area atas sebagai identitas visual card.
3. Letakkan `title` setelah ikon dengan ukuran dan leading yang stabil.
4. Ubah `value` menjadi badge/chip kecil di bawah judul.
5. Hapus label `Pencapaian` besar yang saat ini berdiri sendiri.

## Perilaku Visual

- Card tetap mempertahankan gambar, efek glow, gradient, dan gaya gelap yang sudah ada.
- Judul menjadi anchor visual utama.
- Badge `value` tampil menonjol secukupnya tanpa mengganggu keseimbangan layout.
- Deskripsi tetap berada di area bawah dengan ritme spacing yang lebih rapi.

## Non-Tujuan

- Tidak mengubah hero section halaman `prestasi`
- Tidak mengubah data backend
- Tidak mengubah isi teks `title`, `value`, atau `description`
- Tidak mengubah keseluruhan style system halaman selain area card item

## Rencana Uji

1. Buka `/prestasi`
2. Pastikan pada setiap card:
   - judul tampil lebih dominan
   - nilai tampil di bawah judul
   - area kanan atas tidak lagi berat
3. Pastikan card tetap rapi baik untuk judul pendek maupun judul panjang
4. Pastikan deskripsi tetap terbaca dan tidak bertabrakan dengan badge nilai

## Risiko

### Risiko 1

Badge `value` terlalu kecil sehingga kurang terlihat.

Mitigasi:

- gunakan gaya chip yang cukup kontras
- pertahankan weight font yang tegas

### Risiko 2

Judul panjang tetap membuat card terasa padat.

Mitigasi:

- atur ukuran font dan `leading` agar lebih stabil
- jaga urutan konten tetap sederhana

## Catatan

Perubahan ini fokus pada kualitas hierarki visual, bukan redesign total halaman `prestasi`. Jika hasilnya cocok, pola ini bisa menjadi acuan untuk card berbasis konten lain di frontend publik.
