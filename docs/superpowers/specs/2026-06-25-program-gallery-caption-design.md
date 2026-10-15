# Program Gallery Caption Design

Tanggal: 2026-06-25
Status: Draft tervalidasi bersama user
Topik: Caption per gambar untuk `Galeri Kegiatan` pada program keahlian

## Latar Belakang

Saat ini `Galeri Kegiatan` pada form admin program hanya mendukung upload banyak gambar melalui field `activity_images`. Data disimpan sebagai array URL gambar di kolom JSON `programs.activity_images`.

Kebutuhan baru:

- setiap gambar galeri memiliki teks informasi/caption sendiri
- caption bisa diisi saat menambahkan gambar di admin
- caption bisa diedit lagi saat mengubah program
- caption tampil pada halaman publik `keahlian/<slug>`

## Tujuan

- Menambahkan caption opsional untuk setiap gambar galeri kegiatan.
- Menjaga data lama tetap kompatibel tanpa migrasi data yang berisiko.
- Menampilkan caption di halaman publik program keahlian tanpa merusak layout existing.

## Keputusan Desain

Pendekatan yang dipilih adalah mengubah struktur `activity_images` dari array string menjadi mendukung array object:

- data lama:
  - `["/storage/uploads/programs/activities/a.webp"]`
- data baru:
  - `[{ "url": "/storage/uploads/programs/activities/a.webp", "caption": "Praktik tune up mesin" }]`

Alasan pemilihan:

- tidak perlu tabel baru
- gambar dan caption tetap menyatu dalam satu struktur data
- paling sederhana untuk kebutuhan saat ini
- masih bisa membaca data lama secara aman

## Cakupan Perubahan

Perubahan akan menyentuh:

- `app/Http/Controllers/Admin/ProgramController.php`
- `app/Models/Program.php`
- `resources/views/admin/programs.blade.php`
- `resources/js/Pages/Programs/Show.vue`

## Desain Data

### Bentuk Data Target

Setiap item galeri diperlakukan sebagai object dengan kunci:

- `url`: string, wajib
- `caption`: string, opsional

### Kompatibilitas Data Lama

Jika item galeri masih berupa string lama, sistem harus menormalkannya menjadi:

- `{ url: "<string-lama>", caption: "" }`

Normalisasi ini harus dipakai baik di admin maupun di frontend publik.

## Desain Admin

### Tampilan Form

Pada bagian `Galeri Kegiatan (Multiple)`:

- preview gambar lama ditampilkan bersama input caption
- saat user memilih file baru, setiap preview file baru juga mendapatkan input caption sendiri
- caption bersifat opsional

### Interaksi

Untuk gambar yang sudah ada:

- admin dapat mengubah caption
- gambar tetap bisa dipertahankan tanpa mengunggah ulang

Untuk gambar baru:

- admin memilih banyak file
- setiap file muncul di preview grid
- setiap preview memiliki field caption teks

### Pengiriman Data

Form akan mengirim dua jenis data:

- file baru: `activity_images[]`
- caption dan metadata galeri: field JSON/array yang mencerminkan urutan item

Implementasi akhir boleh menggunakan hidden input JSON selama tetap mudah dirender dan diproses server.

## Desain Controller

### Store

Saat membuat program baru:

- upload file galeri baru
- padankan setiap file dengan caption yang diinput
- simpan sebagai array object `[{ url, caption }]`

### Update

Saat mengedit program:

- baca item galeri lama
- pertahankan item lama yang tidak dihapus
- izinkan caption item lama diperbarui
- tambahkan item baru beserta caption-nya
- simpan kembali seluruh galeri dalam format object yang seragam

### Delete File

Jika ada mekanisme hapus gambar galeri:

- penghapusan tetap berdasarkan `url`
- file fisik di storage tetap dihapus seperti sekarang

## Desain Model

`Program` tetap memakai cast `activity_images => array`, tetapi perlu ditambah helper/accessor normalisasi agar data lama dan data baru bisa dibaca secara konsisten.

Tujuan helper:

- output admin dan frontend selalu berbentuk array object
- view tidak perlu banyak percabangan untuk string vs object

## Desain Frontend Publik

Pada halaman `resources/js/Pages/Programs/Show.vue`:

- galeri tetap menampilkan gambar seperti sekarang
- caption ditampilkan di atas overlay bawah atau di bawah gambar, tergantung layout paling aman saat implementasi
- jika caption kosong, hanya gambar yang ditampilkan

Prinsip visual:

- caption tidak boleh menutupi gambar secara berlebihan
- jika ada caption panjang, tetap harus terbaca tanpa merusak grid
- layout tetap menjaga tampilan premium yang sudah ada

## Non-Tujuan

- Tidak membuat tabel database baru
- Tidak mengubah hero/content utama halaman program
- Tidak mewajibkan semua gambar punya caption
- Tidak melakukan migrasi massal data lama di database

## Risiko

### Risiko 1

Data lama string dan data baru object bercampur, lalu view menjadi rumit.

Mitigasi:

- lakukan normalisasi di satu tempat terpusat
- view cukup membaca format hasil normalisasi

### Risiko 2

Caption baru tidak sinkron dengan urutan file upload.

Mitigasi:

- gunakan struktur metadata yang eksplisit
- pertahankan urutan berdasarkan index saat build payload

### Risiko 3

Caption terlalu panjang dan merusak kartu galeri di frontend.

Mitigasi:

- gunakan styling ringkas
- batasi secara wajar di UI bila perlu

## Rencana Uji

1. Edit program di `/admin/programs/{id}/edit`
2. Tambahkan beberapa gambar baru
3. Isi caption berbeda untuk tiap gambar
4. Simpan perubahan
5. Buka ulang form edit
   - caption tersimpan dan bisa diedit lagi
6. Buka halaman publik `keahlian/<slug>`
   - setiap gambar tampil
   - caption tampil pada gambar yang memilikinya
7. Pastikan data lama tanpa caption tetap tampil normal

## Catatan

Perubahan ini sengaja memprioritaskan kompatibilitas data lama. Jika di masa depan galeri program perlu berkembang menjadi fitur yang lebih kompleks seperti reordering, alt text, atau link per gambar, barulah masuk akal dipindahkan ke tabel relasional khusus.
