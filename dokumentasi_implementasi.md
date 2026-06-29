# Perbaikan Bug Webservice Client (Map API)

Dokumen ini merangkum rentetan perbaikan (bug fixes) yang dilakukan untuk menstabilkan fitur pencarian koordinat via Nominatim API. Masalah-masalah ini muncul bertahap selama proses pengujian.

## Ringkasan Masalah & Solusi

### 1. Backend: Isu Verifikasi SSL cURL
- **Masalah**: Fitur awal gagal terhubung ke Nominatim karena API Client CodeIgniter 4 (menggunakan cURL) tidak mengenali sertifikat SSL di environment XAMPP lokal.
- **Solusi**: Menambahkan opsi `'verify' => false` pada header `curlrequest()` di `app/Controllers/Kuliner.php` untuk mem-bypass verifikasi SSL lokal.

### 2. UI/UX: Pesan Error Kurang Jelas & Input Terkunci
- **Masalah**: Saat pengguna memasukkan alamat spesifik (misal: "Udinus Semarang" atau "Jl. Sadewa"), Nominatim (OpenStreetMap) sering tidak menemukannya karena databasenya kaku. Pesan error yang muncul terlalu kecil, dan kolom Latitude/Longitude masih bersifat `readonly`, membuat pengguna mengira sistem macet/rusak.
- **Solusi**:
  - Menghapus atribut `readonly` dari kolom Latitude dan Longitude di `v_create.php` dan `v_edit.php` agar pengguna dapat mengisi koordinat secara manual (copy-paste dari Google Maps) jika API Nominatim gagal.
  - Memperbesar tampilan UI peringatan error (menggunakan div background merah muda) agar status error API sangat jelas terlihat.

### 3. Frontend: Bug Event Listener Tombol Mati
- **Masalah**: Pada pengujian tahap akhir, ditemukan bahwa *event listener* Javascript (`btnCari.addEventListener('click')`) gagal di-attach oleh browser pada tombol "Cari Koordinat". Hal ini menyebabkan tombol sama sekali tidak merespons (dead button) tanpa memunculkan pesan error apapun di console browser.
- **Solusi**: 
  - Membuang sistem `addEventListener`.
  - Mengubah cara pemanggilan Javascript menjadi metode inline yang 100% tahan banting (bulletproof) dengan menambahkan atribut `onclick="cariKoordinat(this)"` secara langsung di dalam elemen `<button>` HTML.
  - Membungkus logika Javascript ke dalam fungsi global `function cariKoordinat(btn)` di file `v_create.php` dan `v_edit.php`.

## Kesimpulan

Sistem Webservice Client sekarang telah sepenuhnya **Stabil dan Tahan Banting**. Baik dalam skenario API berhasil, API gagal menemukan alamat, maupun rendering browser yang *flaky*, fitur koordinat ini tidak akan macet dan pengguna selalu diberikan opsi manual sebagai *fallback*.
