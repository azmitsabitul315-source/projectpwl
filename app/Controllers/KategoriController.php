<?php

namespace App\Controllers;

use App\Models\KategoriModel;
use App\Models\KulinerModel;

class KategoriController extends BaseController
{
    protected $kategoriModel;
    protected $kulinerModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriModel();
        $this->kulinerModel = new KulinerModel();
    }

    public function index()
    {
        $data = [
            'title'    => 'Data Kategori',
            'kategori' => $this->kategoriModel->findAll()
        ];
        return view('kategori/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah Kategori'];
        return view('kategori/create', $data);
    }

    public function store()
    {
        $rules = ['nama' => 'required|min_length[2]'];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->kategoriModel->save([
            'nama' => $this->request->getPost('nama')
        ]);
        session()->setFlashdata('success', 'Data kategori berhasil ditambahkan!');
        return redirect()->to('/kategori');
    }

    public function edit($id)
    {
        $data = [
            'title'    => 'Edit Kategori',
            'kategori' => $this->kategoriModel->find($id)
        ];
        if (empty($data['kategori'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data kategori tidak ditemukan.');
        }
        return view('kategori/edit', $data);
    }

    public function update($id)
    {
        $rules = ['nama' => 'required|min_length[2]'];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->kategoriModel->update($id, [
            'nama' => $this->request->getPost('nama')
        ]);
        session()->setFlashdata('success', 'Data kategori berhasil diupdate!');
        return redirect()->to('/kategori');
    }

    public function delete($id)
    {
        $relatedCount = $this->kulinerModel->where('id_kategori', $id)->countAllResults();
        if ($relatedCount > 0) {
            session()->setFlashdata('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh data kuliner.');
            return redirect()->to('/kategori');
        }

        $this->kategoriModel->delete($id);
        session()->setFlashdata('success', 'Data kategori berhasil dihapus!');
        return redirect()->to('/kategori');
    }
}
