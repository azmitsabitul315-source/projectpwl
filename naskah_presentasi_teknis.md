# Naskah Presentasi Teknis: Daftar Perubahan Kode

Gunakan naskah kedua ini jika dosen penguji meminta Anda untuk menunjukkan file apa saja yang diubah, kode mana yang baru ditambahkan, serta fungsinya masing-masing.

---

## BAGIAN 1: WEBSERVICE CLIENT (Fitur Pencarian Koordinat Peta)

**"Untuk merealisasikan fitur Webservice Client yang mengambil data dari Nominatim, saya melakukan modifikasi pada 4 file utama:"**

### 1. File: `app/Config/Routes.php`
- **Yang Ditambahkan:** Route baru khusus untuk menampung permintaan pencarian alamat.
- **Potongan Kode:** `$routes->get('kuliner/cariKoordinat', 'Kuliner::cariKoordinat');`
- **Penjelasan:** Route ini memanggil method `cariKoordinat` di controller saat antarmuka membutuhkan data peta.

### 2. File: `app/Controllers/Kuliner.php`
- **Method Baru yang Ditambahkan:** `public function cariKoordinat()`
  - **Penjelasan:** Di dalam method ini terdapat fungsi bawaan CodeIgniter `\Config\Services::curlrequest()` yang bertugas menembak ke server eksternal `https://nominatim.openstreetmap.org/search`. Method ini menangkap balasan JSON dari Nominatim dan meneruskannya ke Frontend.
- **Perubahan Tambahan:** Menambahkan field `lat` dan `lng` di dalam fungsi `store()` dan `update()` agar koordinat yang didapat dari API ikut tersimpan ke dalam database MySQL.

### 3. File: `app/Views/kuliner/v_create.php` & `v_edit.php` (Antarmuka Form)
- **Yang Ditambahkan (HTML):** Menambahkan blok form baru untuk `Latitude` dan `Longitude`, serta satu tombol pemicu bernama "Cari Koordinat dari Alamat".
- **Yang Ditambahkan (Javascript):** Menambahkan fungsi global `function cariKoordinat(btn)` di bagian bawah halaman.
  - **Penjelasan:** Fungsi JS inilah yang mengambil teks alamat dari form input, lalu mengirim permintaan secara asinkron (AJAX menggunakan fungsi `fetch()`) ke controller kita, lalu memproses balasan JSON untuk mengisi kotak Latitude & Longitude secara otomatis.

### 4. File: `app/Views/kuliner/v_detail.php` (Halaman Tampilan Peta)
- **Yang Ditambahkan:** Script dari *Leaflet.js* untuk merender koordinat ke dalam bentuk peta visual.
- **Penjelasan:** Saya mengganti tulisan placeholder "Peta belum tersedia" dengan logika `if (!empty($kuliner['lat']))`. Jika koordinat tersedia, sistem akan langsung menggambar peta *Leaflet* menggunakan *tile layer* dari OpenStreetMap yang berpusat pada koordinat yang tersimpan di database.

---

## BAGIAN 2: WEBSERVICE SERVER (Menyediakan API Radius untuk Pihak Ketiga)

**"Selain membuat Client, aplikasi saya juga bertindak sebagai Webservice Server yang menyediakan API untuk aplikasi lain. Untuk ini, saya membuat beberapa file baru:"**

### 1. File Baru: `app/Controllers/Api/KulinerController.php`
- **Yang Ditambahkan:** Ini adalah satu file Controller baru yang khusus menangani permintaan API.
- **Method yang Ada:**
  - `authenticate()`: Method keamanan. Ini akan mengecek apakah pihak yang meminta data menyertakan `Authorization: Bearer <token>` yang valid di *header* permintaan.
  - `index()`: Method operasional utama. Method ini bertugas merespons dengan format data JSON berisi daftar kuliner.
- **Penjelasan Logika Radius (Haversine):** Di dalam method `index()`, saya menanamkan rumus matematika *Haversine Formula*. Jika pengguna mengirimkan parameter `lat` dan `lng`, rumus SQL murni akan menghitung jarak titik kuliner di database ke titik pengguna, dan memfilter hanya data kuliner yang berada dalam radius 5 kilometer.

### 2. File Baru: `tests/api/kuliner.rest`
- **Yang Ditambahkan:** File teks khusus dengan ekstensi `.rest` untuk menguji API dari dalam *Visual Studio Code*.
- **Penjelasan:** Berisi simulasi *Request* HTTP dengan menyertakan *Bearer Token*, parameter halaman (*pagination*), dan koordinat uji coba. Ini mendemonstrasikan bahwa server API sudah siap dikonsumsi.

### 3. File: `.env`
- **Yang Ditambahkan:** Satu baris token statis: `MY_API_KEY = my-secret-token`
- **Penjelasan:** Sebagai bentuk pengamanan, aplikasi luar yang ingin meminta data dari API kita wajib menyertakan kunci ini. Tanpa kunci ini, server API akan mengembalikan respons `401 Unauthorized`.
