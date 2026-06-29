<?php

namespace App\Controllers;

use App\Models\KulinerModel;
use App\Models\KategoriModel;
use App\Models\TagModel;
use App\Models\KulinerTagModel;

class Kuliner extends BaseController
{
    protected $kulinerModel;
    protected $kategoriModel;
    protected $tagModel;
    protected $kulinerTagModel;

    public function __construct()
    {
        $this->kulinerModel = new KulinerModel();
        $this->kategoriModel = new KategoriModel();
        $this->tagModel = new TagModel();
        $this->kulinerTagModel = new KulinerTagModel();
    }

    private function buildKulinerMetaQuery()
    {
        $builder = $this->kulinerModel->builder();

        return $builder
            ->select('kuliner.*, kategori.nama as kategori_nama, GROUP_CONCAT(DISTINCT tag.nama ORDER BY tag.nama SEPARATOR ", ") as tag_list')
            ->join('kategori', 'kategori.id = kuliner.id_kategori', 'left')
            ->join('kuliner_tag', 'kuliner_tag.kuliner_id = kuliner.id', 'left')
            ->join('tag', 'tag.id = kuliner_tag.tag_id', 'left')
            ->groupBy('kuliner.id');
    }

    public function index()
    {
        $role = session()->get('role');
        $userId = session()->get('userid');

        if ($role === 'admin') {
            $kuliner = $this->buildKulinerMetaQuery()->get()->getResultArray();

            $data = [
                'title'   => 'Data Kuliner',
                'kuliner' => $kuliner,
            ];
            return view('kuliner/v_index', $data);
        }

        $activeKuliner = $this->buildKulinerMetaQuery()
            ->where('kuliner.status', 'active')
            ->get()
            ->getResultArray();

        $mySubmissions = $this->buildKulinerMetaQuery()
            ->where('kuliner.user_id', $userId)
            ->whereIn('kuliner.status', ['pending', 'rejected'])
            ->get()
            ->getResultArray();

        $data = [
            'title'           => 'Daftar Kuliner',
            'activeKuliner'   => $activeKuliner,
            'mySubmissions'   => $mySubmissions,
        ];

        return view('kuliner/v_index_user', $data);
    }

    public function create()
    {
        $data = [
            'title'    => 'Tambah Data Kuliner',
            'kategori' => $this->kategoriModel->findAll(),
            'tags'     => $this->tagModel->findAll()
        ];
        return view('kuliner/v_create', $data);
    }

    public function store()
    {
        $aturan = [
            'nama'        => 'required|min_length[3]',
            'id_kategori' => 'required',
            'gambar'      => 'uploaded[gambar]|max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]',
            'foto2'       => 'max_size[foto2,2048]|is_image[foto2]|mime_in[foto2,image/jpg,image/jpeg,image/png]',
            'foto3'       => 'max_size[foto3,2048]|is_image[foto3]|mime_in[foto3,image/jpg,image/jpeg,image/png]',
        ];

        if (!$this->validate($aturan)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $uploadPath = FCPATH . 'uploads/kuliner';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Gambar Utama
        $fileGambar = $this->request->getFile('gambar');
        $namaGambar = $fileGambar->getRandomName();
        $fileGambar->move($uploadPath, $namaGambar);    

        // Foto 2 (Opsional)
        $namaFoto2 = null;
        $fileFoto2 = $this->request->getFile('foto2');
        if ($fileFoto2 && $fileFoto2->isValid() && !$fileFoto2->hasMoved()) {
            $namaFoto2 = $fileFoto2->getRandomName();
            $fileFoto2->move($uploadPath, $namaFoto2);
        }

        // Foto 3 (Opsional)
        $namaFoto3 = null;
        $fileFoto3 = $this->request->getFile('foto3');
        if ($fileFoto3 && $fileFoto3->isValid() && !$fileFoto3->hasMoved()) {
            $namaFoto3 = $fileFoto3->getRandomName();
            $fileFoto3->move($uploadPath, $namaFoto3);
        }

        $status = session()->get('role') === 'admin' ? 'active' : 'pending';

        $this->kulinerModel->save([
            'user_id'     => session()->get('userid'),
            'id_kategori' => $this->request->getPost('id_kategori'),
            'nama'        => $this->request->getPost('nama'),
            'alamat'      => $this->request->getPost('alamat'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'gambar'      => $namaGambar,
            'foto2'       => $namaFoto2,
            'foto3'       => $namaFoto3,
            'lat'         => $this->request->getPost('lat'),
            'lng'         => $this->request->getPost('lng'),
            'status'      => $status
        ]);

        $kulinerId = $this->kulinerModel->getInsertID();

        // Simpan tags
        $tags = $this->request->getPost('tags');
        if (!empty($tags)) {
            foreach ($tags as $tagId) {
                $this->kulinerTagModel->insert([
                    'kuliner_id' => $kulinerId,
                    'tag_id'     => $tagId
                ]);
            }
        }

        session()->setFlashdata('success', 'Data kuliner berhasil ditambahkan dan menunggu persetujuan Admin!');
        return redirect()->to('/kuliner');
    }

    public function delete($id)
    {
        $kuliner = $this->kulinerModel->find($id);

        if ($kuliner['gambar'] && file_exists(FCPATH . 'uploads/kuliner/' . $kuliner['gambar'])) {
            unlink(FCPATH . 'uploads/kuliner/' . $kuliner['gambar']);
        }
        if ($kuliner['foto2'] && file_exists(FCPATH . 'uploads/kuliner/' . $kuliner['foto2'])) {
            unlink(FCPATH . 'uploads/kuliner/' . $kuliner['foto2']);
        }
        if ($kuliner['foto3'] && file_exists(FCPATH . 'uploads/kuliner/' . $kuliner['foto3'])) {
            unlink(FCPATH . 'uploads/kuliner/' . $kuliner['foto3']);
        }

        $this->kulinerModel->delete($id);
        $this->kulinerTagModel->where('kuliner_id', $id)->delete();
        
        session()->setFlashdata('success', 'Data kuliner berhasil dihapus!');
        return redirect()->to('/kuliner');
    }

    public function edit($id)
    {
        $data = [
            'title'    => 'Edit Data Kuliner',
            'kuliner'  => $this->kulinerModel->find($id),
            'kategori' => $this->kategoriModel->findAll(),
            'tags'     => $this->tagModel->findAll(),
            'kuliner_tags' => array_column($this->kulinerTagModel->where('kuliner_id', $id)->findAll(), 'tag_id')
        ];

        if (empty($data['kuliner'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data kuliner tidak ditemukan.');
        }

        return view('kuliner/v_edit', $data);
    }

    public function update($id)
    {
        $aturan = [
            'nama'        => 'required|min_length[3]',
            'id_kategori' => 'required',
            'gambar'      => 'max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]',
            'foto2'       => 'max_size[foto2,2048]|is_image[foto2]|mime_in[foto2,image/jpg,image/jpeg,image/png]',
            'foto3'       => 'max_size[foto3,2048]|is_image[foto3]|mime_in[foto3,image/jpg,image/jpeg,image/png]',
        ];

        if (!$this->validate($aturan)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $kulinerLama = $this->kulinerModel->find($id);
        $uploadPath = FCPATH . 'uploads/kuliner';

        // Gambar Utama
        $fileGambar = $this->request->getFile('gambar');
        if ($fileGambar && $fileGambar->isValid() && !$fileGambar->hasMoved()) {
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move($uploadPath, $namaGambar);
            if ($kulinerLama['gambar'] && file_exists($uploadPath . '/' . $kulinerLama['gambar'])) {
                unlink($uploadPath . '/' . $kulinerLama['gambar']);
            }
        } else {
            $namaGambar = $kulinerLama['gambar'];
        }

        // Foto 2
        $fileFoto2 = $this->request->getFile('foto2');
        if ($fileFoto2 && $fileFoto2->isValid() && !$fileFoto2->hasMoved()) {
            $namaFoto2 = $fileFoto2->getRandomName();
            $fileFoto2->move($uploadPath, $namaFoto2);
            if ($kulinerLama['foto2'] && file_exists($uploadPath . '/' . $kulinerLama['foto2'])) {
                unlink($uploadPath . '/' . $kulinerLama['foto2']);
            }
        } else {
            $namaFoto2 = $kulinerLama['foto2'];
        }

        // Foto 3
        $fileFoto3 = $this->request->getFile('foto3');
        if ($fileFoto3 && $fileFoto3->isValid() && !$fileFoto3->hasMoved()) {
            $namaFoto3 = $fileFoto3->getRandomName();
            $fileFoto3->move($uploadPath, $namaFoto3);
            if ($kulinerLama['foto3'] && file_exists($uploadPath . '/' . $kulinerLama['foto3'])) {
                unlink($uploadPath . '/' . $kulinerLama['foto3']);
            }
        } else {
            $namaFoto3 = $kulinerLama['foto3'];
        }

        $this->kulinerModel->update($id, [
            'id_kategori' => $this->request->getPost('id_kategori'),
            'nama'        => $this->request->getPost('nama'),
            'alamat'      => $this->request->getPost('alamat'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'gambar'      => $namaGambar,
            'foto2'       => $namaFoto2,
            'foto3'       => $namaFoto3,
            'lat'         => $this->request->getPost('lat'),
            'lng'         => $this->request->getPost('lng'),
        ]);

        // Update Tags
        $this->kulinerTagModel->where('kuliner_id', $id)->delete();
        $tags = $this->request->getPost('tags');
        if (!empty($tags)) {
            foreach ($tags as $tagId) {
                $this->kulinerTagModel->insert([
                    'kuliner_id' => $id,
                    'tag_id'     => $tagId
                ]);
            }
        }

        session()->setFlashdata('success', 'Data kuliner berhasil diperbarui!');
        return redirect()->to('/kuliner');
    }

    public function approve($id)
    {
        $this->kulinerModel->update($id, ['status' => 'active']);
        session()->setFlashdata('success', 'Data kuliner berhasil di-approve!');
        return redirect()->back();
    }

    public function reject($id)
    {
        $this->kulinerModel->update($id, ['status' => 'rejected']);
        session()->setFlashdata('success', 'Data kuliner berhasil di-reject!');
        return redirect()->back();
    }

    // --- Public Views Added for Detail UI ---
    public function detail($id)
    {
        $kuliner = $this->buildKulinerMetaQuery()
            ->where("kuliner.id", $id)
            ->get()->getRowArray();

        if (empty($kuliner)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Kuliner tidak ditemukan.");
        }

        // Get average rating
        $db = \Config\Database::connect();
        $query = $db->query("SELECT AVG(rating) as avg_rating, COUNT(id) as total_reviews FROM reviews WHERE kuliner_id = ?", [$id]);
        $ratingInfo = $query->getRowArray();

        $data = [
            "title" => $kuliner["nama"],
            "kuliner" => $kuliner,
            "rating" => $ratingInfo
        ];

        return view("kuliner/v_detail", $data);
    }

    // ══════════════════════════════════════════
    // WEBSERVICE CLIENT: Cari Koordinat via Nominatim API
    // ══════════════════════════════════════════
    public function cariKoordinat()
    {
        $alamat = $this->request->getGet('alamat');

        if (empty($alamat)) {
            return $this->response->setJSON([]);
        }

        $client = \Config\Services::curlrequest();

        try {
            $response = $client->get('https://nominatim.openstreetmap.org/search', [
                'query' => [
                    'q'      => $alamat,
                    'format' => 'json',
                    'limit'  => 5,
                ],
                'headers' => [
                    'User-Agent' => 'KulinerApp/1.0'
                ],
                'verify' => false
            ]);

            $data = json_decode($response->getBody(), true);
            return $this->response->setJSON($data);
        } catch (\Exception $e) {
            log_message('error', 'Nominatim Error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Gagal menghubungi server Nominatim: ' . $e->getMessage()]);
        }
    }

    public function reviews($id)
    {
        $db = \Config\Database::connect();
        $query = $db->query("SELECT r.*, u.nama as user_name FROM reviews r LEFT JOIN users u ON u.id = r.user_id WHERE r.kuliner_id = ? ORDER BY r.created_at DESC", [$id]);
        $reviews = $query->getResultArray();

        return $this->response->setJSON($reviews);
    }
}

