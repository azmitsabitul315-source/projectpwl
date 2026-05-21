<?php

namespace App\Controllers;

use App\Models\KulinerModel;

class Kuliner extends BaseController
{
    protected $kulinerModel;

    public function __construct()
    {
        $this->kulinerModel = new KulinerModel();
    }

    public function index()
    {
        $data = [
            'title'   => 'Data Kuliner',
            'kuliner' => $this->kulinerModel->findAll()
        ];
        return view('kuliner/v_index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah Data Kuliner'];
        return view('kuliner/v_create', $data);
    }

    public function store()
    {
        // 1. VALIDASI
        $aturan = [
            'nama'   => 'required|min_length[3]',
            'gambar' => 'uploaded[gambar]|max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($aturan)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 2. UPLOAD FILE
        $fileGambar = $this->request->getFile('gambar');
        $namaGambar = $fileGambar->getRandomName();
        $uploadPath = FCPATH . 'uploads/kuliner';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $fileGambar->move($uploadPath, $namaGambar);

        // 3. SIMPAN KE DATABASE
        $this->kulinerModel->save([
            'user_id'     => 1,
            'nama'        => $this->request->getPost('nama'),
            'alamat'      => $this->request->getPost('alamat'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'gambar'      => $namaGambar,
            'status'      => 'pending'
        ]);

        // 4. FLASH MESSAGE
        session()->setFlashdata('success', 'Data kuliner berhasil ditambahkan!');
        return redirect()->to('/kuliner');
    }

    public function delete($id)
    {
        $kuliner = $this->kulinerModel->find($id);

        // Hapus file gambar dari folder uploads
        if ($kuliner['gambar'] && file_exists(FCPATH . 'uploads/kuliner/' . $kuliner['gambar'])) {
            unlink(FCPATH . 'uploads/kuliner/' . $kuliner['gambar']);
        }

        $this->kulinerModel->delete($id);
        session()->setFlashdata('success', 'Data kuliner berhasil dihapus!');
        return redirect()->to('/kuliner');
    }

    public function edit($id)
    {
        $data = [
            'title'   => 'Edit Data Kuliner',
            'kuliner' => $this->kulinerModel->find($id)
        ];

        if (empty($data['kuliner'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data kuliner tidak ditemukan.');
        }

        return view('kuliner/v_edit', $data);
    }

    public function update($id)
    {
        // 1. VALIDASI (Gambar bersifat opsional saat edit)
        $aturan = [
            'nama'   => 'required|min_length[3]',
            'gambar' => 'max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($aturan)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $kulinerLama = $this->kulinerModel->find($id);
        $fileGambar  = $this->request->getFile('gambar');

        // 2. LOGIKA CEK GAMBAR BARU
        if ($fileGambar->isValid() && !$fileGambar->hasMoved()) {
            // User mengunggah gambar baru
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move(FCPATH . 'uploads/kuliner', $namaGambar);

            // Hapus file gambar lama dari server
            if ($kulinerLama['gambar'] && file_exists(FCPATH . 'uploads/kuliner/' . $kulinerLama['gambar'])) {
                unlink(FCPATH . 'uploads/kuliner/' . $kulinerLama['gambar']);
            }
        } else {
            // User TIDAK mengunggah gambar baru, pakai nama gambar lama
            $namaGambar = $kulinerLama['gambar'];
        }

        // 3. UPDATE KE DATABASE
        $this->kulinerModel->update($id, [
            'nama'      => $this->request->getPost('nama'),
            'alamat'    => $this->request->getPost('alamat'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'gambar'    => $namaGambar,
        ]);

        // 4. FLASH MESSAGE SUKSES
        session()->setFlashdata('success', 'Data kuliner berhasil diperbarui!');
        return redirect()->to('/kuliner');
    }
}