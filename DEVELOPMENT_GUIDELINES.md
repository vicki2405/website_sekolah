# PANDUAN PENGEMBANGAN, KEAMANAN, DAN UI/UX SISTEM WEBSITE STEMDA
**SMKS Muhammadiyah 2 Genteng**

> **ATURAN MUTLAK / GOLDEN RULE UNTUK ASSISTANT / AGENT:**
> 1. **Dilarang langsung mengeksekusi perubahan kode sebelum berdiskusi.**
> 2. **Setiap analisis, perbaikan, atau pembenahan wajib dipaparkan dan didiskusikan terlebih dahulu kepada USER untuk mendapatkan persetujuan.**
> 3. **Seluruh analisis dan solusi wajib merujuk pada standar dan kapabilitas *Agency Skills* yang telah terpasang.**

---

## 1. Protokol Kerja (Standard Operating Procedure)

Setiap sesi pengembangan, perbaikan bug, penambahan fitur, atau audit sistem wajib mengikuti 5 tahap berikut:

```
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│ 1. Identifikasi │ ──> │   2. Analisis   │ ──> │ 3. DISKUSIKAN   │
│   Permintaan    │     │  Agency Skills  │     │  DENGAN USER    │
└─────────────────┘     └─────────────────┘     └────────┬────────┘
                                                         │ (Tunggu Approval)
                                                         ▼
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│ 5. Laporan &    │ <── │ 4. Verifikasi & │ <── │ 3b. Eksekusi    │
│    Konfirmasi   │     │    Testing      │     │     Perbaikan   │
└─────────────────┘     └─────────────────┘     └─────────────────┘
```

1. **Analisis Berbasis Skill**: Gunakan modul Agency Skills yang relevan untuk mendiagnosis masalah secara akurat.
2. **Pemaparan Rencana (Proposal)**: Jelaskan kepada pengguna:
   * Masalah/akar penyebab (*root cause*).
   * Opsi solusi dan perbandingannya.
   * File-file yang akan dimodifikasi.
   * Potensi risiko atau dampak ke modul lain.
3. **Persetujuan Pengguna**: **Tunggu instruksi / persetujuan eksplisit dari pengguna sebelum menulis atau mengedit file.**
4. **Implementasi Rapi (*Minimal Viable Diff*)**: Tulis kode bersih tanpa *scope creep*, pertahankan komentar yang ada.
5. **Verifikasi Wajib**:
   * Jalankan automated test: `DB_HOST=127.0.0.1 php artisan test`
   * Kompilasi asset: `npm run build`
   * Bersihkan cache: `php artisan optimize:clear`

---

## 2. Pemetaan Agency Skills Berdasarkan Kategori Pekerjaan

Gunakan panduan skill berikut saat menangani tugas terkait:

### A. Keamanan Sistem & DevSecOps
* **`agency-application-security-engineer`**:
  * Pencegahan OWASP Top 10 (SQL Injection, Stored/Reflected XSS, CSRF, IDOR).
  * Sanitasi input teks kaya (Quill) wajib menggunakan trait [`app/Traits/HtmlSanitizer.php`](app/Traits/HtmlSanitizer.php).
  * Validasi otorisasi ganda pada aksi sensitif (penghapusan, upload berkas, ubah peran).
* **`agency-penetration-tester`**:
  * Pengujian celah eskalasi hak akses (*Privilege Escalation*).
  * Pengguna non-Superadmin **dilarang keras** dapat memodifikasi, menonaktifkan, atau menghapus akun Superadmin atau menetapkan peran Superadmin ke dirinya sendiri.
* **`agency-secrets-credential-hygiene-engineer`**:
  * File `.sql`, `.csv`, `.bak`, `.env*`, `.log` dilarang berada di direktori publik atau diakses lewat HTTP.
  * Proteksi berkas sensitif dikunci rapat di root [`.htaccess`](.htaccess).

### B. Frontend, UI/UX & Aksesibilitas
* **`agency-frontend-developer` & `agency-ux-architect`**:
  * Membangun UI berbasis **Vue 3 (Composition API)**, **Inertia.js**, dan **Tailwind CSS**.
  * Hindari rendering API mandiri jika data bisa disuplai langsung melalui prop Inertia dari controller.
* **`agency-accessibility-auditor` & `design-system`**:
  * Target sentuh tombol dan tautan minimal **44x44px s/d 48x48px** (`min-w-[44px] min-h-[44px]`).
  * Kontras warna teks terhadap latar belakang minimal 4.5:1 (WCAG AA).
  * Navigasi keyboard dan fokus outline yang jelas (`focus-visible:ring-2`).

### C. Basis Data & Backend Engineering
* **`agency-database-optimizer` & `agency-database-reliability-engineer`**:
  * Optimasi kueri MySQL 8: hindari masalah *N+1 query* dengan eager loading `with(...)`.
  * Multi-layer caching via Redis/Cache Laravel: setiap penambahan/pengubahan data di admin wajib memanggil fungsi pembersihan cache publik (`forgetPublicCaches`).
  * Integritas backup SQL: impor backup harus divalidasi ketat terhadap subquery injeksi atau manipulasi tabel sistem.

---

## 3. Standar Arsitektur Kunci Proyek STEMDA

### A. Kebijakan Penyimpanan Non-Symlink (Shared Hosting Friendly)
* **Konteks**: Lingkungan hosting produksi sekolah **tidak menyediakan symlink** dan sering menghapus tautan simbolis otomatis.
* **Aturan Implementasi**:
  1. Root disk `public` pada [`config/filesystems.php`](config/filesystems.php) diarahkan ke `public_path('storage')`.
  2. Seluruh proses unggah berkas wajib menghasilkan file fisik langsung di folder:
     ```
     public/storage/
     ├── achievements/
     ├── images/
     ├── partners/
     ├── profile/
     ├── sliders/
     └── uploads/ (facilities, gallery, news, ppdb, programs, teachers, tefa, waka)
     ```
  3. Gunakan trait [`app/Traits/ImageCompression.php`](app/Traits/ImageCompression.php) untuk mengompresi gambar otomatis ke format **WebP** guna menghemat bandwidth dan kapasitas hosting.
  4. Rute fallback `Route::get('/storage/{path}')` di [`routes/web.php`](routes/web.php) wajib dipertahankan untuk mengamankan pengiriman media jika sewaktu-waktu file berada di `storage/app/public/`.

### B. Standar Tampilan Lintas Perangkat (Cross-Device Responsiveness)
1. **Smartphone (Mobile 320px – 480px)**:
   * **Wajib Zero Horizontal Scroll** (`overflow-x-clip` pada layout utama).
   * Logo sekolah: slogan panjang disembunyikan pada layar `< sm` (`hidden sm:block`) agar tidak mendorong tombol hamburger keluar layar.
   * Sediakan tombol menu hamburger di header atas dan bilah pintasan bawah (*quick-bottom bar*).
   * Tumpukan vertikal hero slider dan kartu statistik wajib berjarak aman (`-mt-10 sm:-mt-16 lg:-mt-24`) agar tombol aksi tidak tertutup.
2. **Tablet & Laptop Ringkas (768px – 1279px)**:
   * Mengingat terdapat 12 item navigasi utama, navbar desktop beralih ke mode drawer/sheet pada resolusi `< 1280px` (`hidden xl:flex`) agar menu tidak berhimpitan atau patah dua baris.
3. **Papan Tulis Interaktif Digital (*Smart Board* / TV Layar Sentuh 4K)**:
   * **Dilarang bergantung pada hover kursor mouse**: tombol navigasi slider (panah kiri/kanan) wajib terlihat jelas (`opacity-80 hover:opacity-100`).
   * Aktifkan `:grab-cursor="true"` dan navigasi sentuh gestur swipe pada Swiper.
   * Matikan delay sentuhan 300ms dengan `touch-action: manipulation;` di [`resources/css/app.pcss`](resources/css/app.pcss).

### C. Penanganan Error Tampilan Gambar (Graceful Image Fallback)
* Seluruh tag gambar yang bersumber dari database dinamis wajib menyertakan error handler `@error`:
  ```vue
  <img
      v-if="item.photo"
      :src="item.photo"
      :alt="item.name"
      class="w-full h-full object-cover"
      @error="(e) => { e.target.classList.add('hidden'); if (e.target.nextElementSibling) e.target.nextElementSibling.classList.remove('hidden'); }"
  />
  <div :class="{ 'hidden': item.photo }" class="w-full h-full flex items-center justify-center ...">
      <!-- Fallback Avatar / Inisial / Icon SVG -->
  </div>
  ```
* Ini menjamin jika database berisi path gambar yang file fisiknya belum ada di hosting, website **tidak akan pernah menampilkan kotak gambar rusak/silang merah**.

---

## 4. Checklist Pra-Rilis / Sebelum Deploy ke Hosting

Sebelum melakukan push atau pembaruan ke server hosting, pastikan:
- [ ] Pengujian otomatis lulus: `DB_HOST=127.0.0.1 php artisan test`
- [ ] Asset frontend terkompilasi: `npm run build`
- [ ] Cache dibersihkan: `php artisan optimize:clear`
- [ ] Izin folder fisik `public/storage/` dan `storage/` berstatus *writable* (755 atau 775).
- [ ] File `.env` produksi memiliki `APP_ENV=production` dan `APP_DEBUG=false`.
- [ ] Tidak ada berkas SQL dump, arsip ZIP backup, atau CSV data siswa yang tertinggal di folder `public/`.

---

*Dokumen ini menjadi rujukan wajib bagi setiap developer dan AI coding assistant yang bekerja pada repositori ini.*

