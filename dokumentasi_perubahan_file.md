# Laporan Perubahan — Webservice Client & Server

Berikut adalah **semua file yang diubah dan ditambah** selama eksekusi implementasi Webservice Client (Nominatim API) dan Webservice Server (API Kuliner Radius).

---

## Ringkasan Cepat

| No | File | Aksi | Bagian |
|----|------|------|--------|
| 1 | `app/Config/Routes.php` | ✏️ EDIT | Client + Server |
| 2 | `app/Controllers/Kuliner.php` | ✏️ EDIT | Client |
| 3 | `app/Views/kuliner/v_create.php` | ✏️ EDIT | Client |
| 4 | `app/Views/kuliner/v_edit.php` | ✏️ EDIT | Client |
| 5 | `app/Views/kuliner/v_detail.php` | ✏️ EDIT | Client (Peta) |
| 6 | `.env` | ✏️ EDIT | Server |
| 7 | `app/Controllers/Api/KulinerController.php` | 🆕 BARU | Server |
| 8 | `tests/api/kuliner.rest` | 🆕 BARU | Server |

---

## BAGIAN A — Webservice Client (Perubahan Detail)

### 1. [Routes.php](file:///c:/xampp/htdocs/projectpwl22/app/Config/Routes.php)
**Yang ditambahkan:** 2 route baru (baris 21-25)
```diff
+// Webservice Client: Cari koordinat via Nominatim
+$routes->get('kuliner/cariKoordinat', 'Kuliner::cariKoordinat');
+
+// Webservice Server: API Kuliner Radius
+$routes->get('api/kuliner', 'Api\KulinerController::index');
```

---

### 2. [Kuliner.php](file:///c:/xampp/htdocs/projectpwl22/app/Controllers/Kuliner.php)
**Yang ditambahkan/diubah:**

#### a) Fungsi `store()` — 2 baris baru (lat & lng)
```diff
  'foto3'       => $namaFoto3,
+ 'lat'         => $this->request->getPost('lat'),
+ 'lng'         => $this->request->getPost('lng'),
  'status'      => $status
```

#### b) Fungsi `update()` — 2 baris baru (lat & lng)
```diff
  'foto3'       => $namaFoto3,
+ 'lat'         => $this->request->getPost('lat'),
+ 'lng'         => $this->request->getPost('lng'),
```

#### c) Fungsi baru `cariKoordinat()` — 30 baris baru
Fungsi ini adalah **inti Webservice Client**:
- Mengambil parameter `alamat` dari URL
- Membuat HTTP Client via `\Config\Services::curlrequest()`
- Mengirim GET request ke `https://nominatim.openstreetmap.org/search`
- Menerima response JSON berisi koordinat
- Meneruskan data JSON ke browser
- **[BUGFIX]**: Terdapat konfigurasi `'verify' => false` pada konfigurasi cURL untuk mengatasi kendala keamanan SSL di server lokal (XAMPP).

---

### 3. [v_create.php](file:///c:/xampp/htdocs/projectpwl22/app/Views/kuliner/v_create.php)
**Yang ditambahkan:**

#### a) Section "Lokasi Koordinat" (HTML) — 23 baris baru
Ditambahkan **setelah** section Foto, **sebelum** section Tag:
- Tombol "🔍 Cari Koordinat dari Alamat"
- Status feedback (loading/sukses/gagal)
- 2 input readonly: Latitude dan Longitude

#### b) JavaScript AJAX — 48 baris baru
Ditambahkan di dalam `<script>`:
- Event listener pada tombol klik
- `fetch()` ke `/kuliner/cariKoordinat?alamat=...`
- Isi otomatis field lat & lng dari response
- Handling error dan loading state (UI peringatan error diperbesar agar jelas terlihat).
- **[BUGFIX]**: Tombol dipanggil menggunakan metode **inline `onclick="cariKoordinat(this)"`** dan logika JavaScript dibungkus dalam `function cariKoordinat(btn)` agar 100% responsif di semua browser, mengatasi isu tombol mati yang tidak bereaksi terhadap event listener standar.
- **[UX FIX]**: Input field Latitude dan Longitude sengaja *dibuka kuncinya* (dibuang atribut `readonly`-nya) agar pengguna tetap dapat menyalin-tempel koordinat secara manual dari Google Maps jika Nominatim gagal menemukan alamat.

---

### 4. [v_edit.php](file:///c:/xampp/htdocs/projectpwl22/app/Views/kuliner/v_edit.php)
**Yang ditambahkan:** Sama persis seperti `v_create.php`, tapi:
- Input lat/lng sudah diisi `value` dari database: `$kuliner['lat']` dan `$kuliner['lng']`
- Teks instruksi sedikit berbeda: "Klik tombol di bawah untuk mencari **ulang** koordinat"

---

### 5. [v_detail.php](file:///c:/xampp/htdocs/projectpwl22/app/Views/kuliner/v_detail.php)
**Yang diubah:**

#### a) Placeholder peta → Peta Leaflet (HTML)
```diff
-<!-- Map Placeholder -->
-Peta belum tersedia (Tidak ada kordinat)
+<!-- Peta Lokasi (Leaflet.js) -->
+<?php if (!empty($kuliner['lat']) && !empty($kuliner['lng'])): ?>
+<div id="map" style="height: 250px; ..."></div>
+<?php else: ?>
+Peta belum tersedia (Tidak ada koordinat)
+<?php endif; ?>
```

#### b) Script inisialisasi Leaflet — 21 baris baru
```javascript
var map = L.map('map').setView([lat, lng], 16);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
L.marker([lat, lng]).addTo(map).bindPopup('Nama Kuliner').openPopup();
```

---

## BAGIAN B — Webservice Server (Perubahan Detail)

### 6. [.env](file:///c:/xampp/htdocs/projectpwl22/.env)
**Yang ditambahkan:** 5 baris baru di akhir file
```diff
+#--------------------------------------------------------------------
+# WEBSERVICE SERVER API KEY
+#--------------------------------------------------------------------
+
+MY_API_KEY = my-secret-token
```

---

### 7. [Api/KulinerController.php](file:///c:/xampp/htdocs/projectpwl22/app/Controllers/Api/KulinerController.php) 🆕
**File baru** — 105 baris. Berisi:

| Fungsi | Baris | Penjelasan |
|--------|-------|------------|
| `__construct()` | 16-20 | Load model + ambil API Key dari .env |
| `authenticate()` | 25-38 | Cek header Authorization: Bearer token |
| `unauthorized()` | 40-48 | Response 401 jika token tidak cocok |
| `index()` | 53-103 | Endpoint utama: filter radius (Haversine) + pagination |

---

### 8. [tests/api/kuliner.rest](file:///c:/xampp/htdocs/projectpwl22/tests/api/kuliner.rest) 🆕
**File baru** — 5 test case:
1. Ambil semua kuliner aktif (tanpa filter)
2. Ambil kuliner dalam radius 5km dari UDINUS
3. Test pagination (halaman 2, 5 per halaman)
4. Test tanpa API Key → harus 401
5. Test dengan API Key salah → harus 401

---

## Cara Testing

### Testing Webservice Client (Nominatim):
1. Jalankan `php spark serve`
2. Buka form "Tambah Kuliner" atau "Edit Kuliner"
3. Ketik alamat (misal: "Jl. Nakula Semarang")
4. Klik "🔍 Cari Koordinat dari Alamat"
5. Lat/Lng akan terisi otomatis
6. Submit → data tersimpan → buka halaman detail → peta muncul

### Testing Webservice Server (API Radius):
1. Install extension **REST Client** di VS Code
2. Buka file `tests/api/kuliner.rest`
3. Klik "Send Request" di atas setiap test case
4. Perhatikan response JSON dan status code

> [!IMPORTANT]
> **Tidak ada perubahan database (migration).** Kolom `lat` dan `lng` sudah ada di tabel `kuliner` dari migrasi sebelumnya. Model `KulinerModel.php` juga sudah include kedua field tersebut di `allowedFields`.
