# Naskah Presentasi: Implementasi Webservice Client (Nominatim API)

Gunakan naskah ini sebagai panduan saat mempresentasikan fitur pencarian koordinat peta otomatis (Webservice Client) di hadapan penguji.

---

## 1. Pembukaan
> "Selamat [pagi/siang/sore] Bapak/Ibu Dosen Penguji. Pada aplikasi Kuliner Radius ini, saya telah mengimplementasikan konsep **Webservice Client** untuk mempermudah pengguna. Saat pengguna menambahkan atau mengedit tempat kuliner, mereka tidak perlu mencari angka *Latitude* dan *Longitude* secara manual. Cukup ketikkan nama jalan atau nama tempat, lalu klik tombol **'Cari Koordinat dari Alamat'**, maka sistem akan secara otomatis menghubungi layanan peta dunia *OpenStreetMap (Nominatim API)* untuk mencarikan dan mengisi koordinatnya secara presisi."

## 2. Penjelasan Alur Logika (Logical Flow)
> "Alur logika dari fitur ini dibagi menjadi dua bagian utama, yaitu proses di antarmuka pengguna (Frontend) dan proses di server kita (Backend Proxy).
> 
> 1. Pertama, pengguna mengetik alamat lengkap di form yang disediakan.
> 2. Saat tombol diklik, sebuah fungsi **Javascript (AJAX)** di Frontend akan mengambil teks alamat tersebut.
> 3. Alih-alih Javascript langsung menembak ke server OpenStreetMap, Javascript akan menembak ke **Server Lokal (Controller Kuliner)** kita terlebih dahulu.
> 4. Server kita kemudian bertindak sebagai "Agen perantara". Menggunakan *library cURL*, server kita yang akan meminta data koordinat ke Nominatim API.
> 5. Setelah Nominatim membalas dengan data format JSON, server kita meneruskannya kembali ke Javascript.
> 6. Terakhir, Javascript membaca JSON tersebut dan otomatis mengisi kotak Latitude dan Longitude di layar pengguna."

## 3. Menampilkan Cuplikan Kode

### A. Kode Frontend (Tombol & Javascript)
*(Tampilkan file `v_create.php` atau `v_edit.php`)*

> "Berikut adalah kode di bagian antarmuka (Frontend). Tombol pencarian memiliki atribut `onclick` yang memanggil fungsi `cariKoordinat()`."

```javascript
function cariKoordinat(btn) {
    // 1. Ambil input alamat dari pengguna
    var alamat = document.querySelector('input[name="alamat"]').value;
    
    // 2. Minta data ke Server Lokal kita menggunakan Fetch API (AJAX)
    fetch('<?= base_url("kuliner/cariKoordinat") ?>?alamat=' + encodeURIComponent(alamat))
        .then(res => res.json())
        .then(data => {
            if (data.length > 0) {
                // 3. Jika ketemu, isikan otomatis ke kotak Latitude & Longitude
                document.getElementById('lat').value = data[0].lat;
                document.getElementById('lng').value = data[0].lon;
                // Tampilkan pesan sukses
            }
        })
}
```
> "Penggunaan AJAX ini membuat halaman tidak perlu melakukan *refresh* (memuat ulang) sama sekali saat data dicari, memberikan pengalaman (UX) yang sangat mulus."

### B. Kode Backend (Proxy Controller)
*(Tampilkan file `Kuliner.php` bagian fungsi `cariKoordinat`)*

> "Dan ini adalah kode di bagian Server (Controller). Kita sengaja menggunakan Server sebagai perantara (Proxy) untuk menghindari masalah keamanan blokir *Cross-Origin Resource Sharing (CORS)* dari browser, serta menjaga performa."

```php
public function cariKoordinat()
{
    $alamat = $this->request->getGet('alamat');

    // 1. Inisialisasi HTTP Client bawaan CodeIgniter 4 (Berbasis cURL)
    $client = \Config\Services::curlrequest();

    try {
        // 2. Melakukan GET Request ke server Nominatim API
        $response = $client->get('https://nominatim.openstreetmap.org/search', [
            'query' => [
                'q'      => $alamat,
                'format' => 'json',
                'limit'  => 5, // Batasi hasil pencarian
            ],
            'headers' => [
                'User-Agent' => 'KulinerApp/1.0' // Wajib menyertakan User-Agent
            ],
            'verify' => false // Memastikan lolos SSL di environment lokal
        ]);

        // 3. Mengubah balasan Nominatim menjadi array dan mengembalikannya sbg JSON
        $data = json_decode($response->getBody(), true);
        return $this->response->setJSON($data);

    } catch (\Exception $e) {
        return $this->response->setStatusCode(500)->setJSON(['error' => 'Gagal menghubungi server Nominatim']);
    }
}
```

## 4. Penutup & Nilai Tambah (Nilai Plus)
> "Sebagai fitur keselamatan pengguna (Fallback UX), saya mendesain form Latitude dan Longitude agar **tetap dapat diketik secara manual**. Jika sewaktu-waktu pengguna mencari nama jalanan kecil yang kebetulan belum terdaftar di OpenStreetMap, sistem akan memberitahu pengguna secara elegan, dan mereka tetap bisa melakukan *copy-paste* koordinat secara manual dari Google Maps tanpa harus merasa terjebak.
> 
> Demikian implementasi Webservice Client yang telah saya buat."
