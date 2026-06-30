# Naskah Presentasi: Implementasi Webservice Client (Pencarian Koordinat via Nominatim)

Selamat pagi/siang Bapak/Ibu Dosen dan teman-teman,

Pada kesempatan kali ini, saya akan mendemonstrasikan implementasi **Webservice Client** yang telah saya bangun dalam project ini. Fitur utamanya adalah **Pencarian Koordinat Otomatis berdasarkan Alamat** menggunakan API publik dari Nominatim (OpenStreetMap).

Alur kerja (Data Flow) dari fitur ini melibatkan *Routing*, *Front-End* (Client), *Back-End* (Server), hingga berujung ke *Database*. Berikut adalah rincian teknis dari awal hingga akhir:

---

## 1. Mendaftarkan Route (Sebagai Jembatan)
Langkah pertama yang saya lakukan adalah mendaftarkan *endpoint* API lokal di dalam file `app/Config/Routes.php`.
```php
$routes->get('kuliner/cariKoordinat', 'Kuliner::cariKoordinat');
```
Route ini berfungsi untuk mendaftarkan URL `kuliner/cariKoordinat` agar CodeIgniter tahu bahwa setiap permintaan (request) ke URL tersebut harus diarahkan ke Controller `Kuliner` pada fungsi (method) `cariKoordinat`.

## 2. Pemanggilan dari Front-End (Javascript `v_create` / `v_edit`)
Proses selanjutnya dimulai dari interaksi user di tampilan web (*Front-End*), tepatnya di file `v_create.php` dan `v_edit.php`:
- **Menangkap Input:** Saat user mengetikkan alamat dan menekan tombol pencarian, fungsi Javascript akan menangkap teks tersebut.
- **Validasi:** Terdapat pengecekan kondisi pertama. Jika input alamat kosong, proses dihentikan dan user akan mendapat notifikasi error (diminta untuk mengisi alamat).
- **Mengirim Request (Trigger):** Jika lolos validasi, Javascript bertindak sebagai pemanggil (Client) yang memanggil *endpoint* yang sudah kita daftarkan di *routes* tadi menggunakan AJAX/Fetch. 
- Alamat yang diketik akan disisipkan di URL sebagai parameter GET secara aman. 
  Contoh: `fetch('.../kuliner/cariKoordinat?alamat=' + encodeURIComponent(alamat))`

## 3. Pemrosesan di Back-End (Controller `Kuliner.php`)
Setelah dipanggil oleh *Front-End*, request tersebut diterima dan ditangani oleh Controller `Kuliner` di dalam fungsi `cariKoordinat()`:
- **Menangkap Data:** Controller menangkap parameter `alamat` dari URL.
- **Inisialisasi HTTP Client:** Di tahap ini, saya membuat objek *client* dengan menginisialisasi **HTTP Client berbasis cURL Request** bawaan dari *service* CodeIgniter 4 (`\Config\Services::curlrequest()`).
- **Eksekusi ke API Nominatim:** Di dalam blok `try...catch`, *client* tersebut digunakan untuk menembak server eksternal Nominatim OpenStreetMap dengan metode GET, untuk mencari titik koordinat dari alamat yang di-*request*.
- **Parsing Data:** Balasan (*response*) dari Nominatim berupa *raw JSON text* akan ditangkap dan diubah menjadi PHP Array menggunakan fungsi `json_decode()`.
- **Response ke Client:** Setelah rapi, Controller membungkus array tersebut kembali menjadi format JSON standar dan mengembalikannya ke *Front-End* (Javascript).

## 4. Manipulasi DOM / UI (Kembali ke Javascript)
- Data JSON yang dikirim dari Controller diterima oleh baris kode `.then(res => res.json())` lalu masuk ke pengkondisian.
- Jika data berhasil ditemukan (`data.length > 0`), Javascript akan mengambil objek pertama (pencarian paling relevan), lalu menarik nilai Latitude (`lat`) dan Longitude (`lon`).
- Nilai koordinat tersebut secara otomatis diisikan ke dalam elemen form input `lat` dan `lng` di layar user, dan memberikan pesan sukses berwarna hijau.

## 5. Penyimpanan ke Database (Fungsi `store` / `update`)
Tahap paling akhir terjadi saat user menekan tombol "Simpan" pada form:
- Form akan di-*submit* via metode POST.
- Nilai koordinat otomatis (yang tadi sudah masuk ke input form) akan ditangkap oleh Controller `Kuliner` di dalam fungsi `store()` (saat *create*) atau `update()` (saat *edit*).
- Data ditarik menggunakan `$this->request->getPost('lat')` dan `getPost('lng')`, lalu resmi disimpan secara permanen ke dalam tabel MySQL melalui perantara `KulinerModel`.

---
**Kesimpulan:**
Melalui fitur ini, saya telah mengimplementasikan integrasi *Full-Stack* yang memanfaatkan arsitektur Client-Server, pengelolaan Routing CI4, request asinkron AJAX (Fetch), serta konsumsi Third-Party API eksternal (Nominatim).

Demikian penjelasan alur kerja Webservice Client dari saya. Terima kasih.
