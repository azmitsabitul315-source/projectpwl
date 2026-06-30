# Dokumentasi API — Webservice Server

Base URL: `http://<host-anda>/projectpwl-main/public/index.php/api` (sesuaikan host/path)

Autentikasi
- Sertakan API key pada salah satu header berikut:
  - `Authorization: Bearer <API_KEY>`
  - `X-API-KEY: <API_KEY>`

Secara default terdapat API key demo di `app/Config/Api.php`. Ganti nilai tersebut sebelum digunakan di lingkungan produksi.

Endpoint

1) Daftar kuliner
   - Method: GET
   - URL: `/kuliner`
   - Query params (opsional): `page`, `per_page`
   - Response: JSON { status, data: [ ... ], pager: { currentPage, perPage, total } }
   - Contoh:
     curl -H "X-API-KEY: CHANGE_ME_API_KEY_ABC1234567890" "http://localhost/projectpwl-main/public/index.php/api/kuliner"

2) Detail satu kuliner
   - Method: GET
   - URL: `/kuliner/{id}`
   - Response: JSON { status, data }
   - Contoh:
     curl -H "Authorization: Bearer CHANGE_ME_API_KEY_ABC1234567890" "http://localhost/projectpwl-main/public/index.php/api/kuliner/1"

3) Tambah (create) kuliner
   - Method: POST
   - URL: `/kuliner`
   - Content-Type: `application/json` atau form-data
   - Field yang dapat dikirim: `user_id`, `id_kategori`, `nama`, `alamat`, `deskripsi`, `gambar`, `foto2`, `foto3`, `lat`, `lng`, `status`
   - Response: JSON { status, id }
   - Contoh:
     curl -X POST -H "Content-Type: application/json" -H "X-API-KEY: CHANGE_ME_API_KEY_ABC1234567890" \
       -d '{"nama":"Warung Sate","alamat":"Jl. Merdeka","lat":"-6.2","lng":"106.8","id_kategori":1}' \
       "http://localhost/projectpwl-main/public/index.php/api/kuliner"

4) Perbarui (update) kuliner
   - Method: PUT
   - URL: `/kuliner/{id}`
   - Body: JSON berisi field yang ingin diubah
   - Response: JSON { status }
   - Contoh:
     curl -X PUT -H "Content-Type: application/json" -H "X-API-KEY: CHANGE_ME_API_KEY_ABC1234567890" \
       -d '{"nama":"Warung Sate Updated"}' \
       "http://localhost/projectpwl-main/public/index.php/api/kuliner/1"

5) Hapus (delete) kuliner
   - Method: DELETE
   - URL: `/kuliner/{id}`
   - Response: JSON { status }
   - Contoh:
     curl -X DELETE -H "X-API-KEY: CHANGE_ME_API_KEY_ABC1234567890" "http://localhost/projectpwl-main/public/index.php/api/kuliner/1"

Catatan & Rekomendasi
- Ganti API key demo di `app/Config/Api.php` menjadi nilai acak yang aman.
- Pertimbangkan menyimpan API key di database dan membuat endpoint manajemen (create/revoke).
- Untuk upload file (gambar), gunakan endpoint upload yang sudah ada atau perluas API ini agar menerima `multipart/form-data`.
- Lindungi endpoint yang berisiko (create/update/delete) dengan API key yang lebih kuat atau mekanisme otorisasi tambahan.
