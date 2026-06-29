<?php

namespace App\Controllers;

use App\Models\TagModel;

class TagController extends BaseController
{
    protected $tagModel;

    public function __construct()
    {
        $this->tagModel = new TagModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Tag',
            'tags'  => $this->tagModel->findAll()
        ];
        return view('tag/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah Tag'];
        return view('tag/create', $data);
    }

    public function store()
    {
        $rules = ['nama' => 'required|min_length[2]'];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->tagModel->save([
            'nama' => $this->request->getPost('nama')
        ]);
        session()->setFlashdata('success', 'Data tag berhasil ditambahkan!');
        return redirect()->to('/tag');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Tag',
            'tag'   => $this->tagModel->find($id)
        ];
        if (empty($data['tag'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tag tidak ditemukan.');
        }
        return view('tag/edit', $data);
    }

    public function update($id)
    {
        $rules = ['nama' => 'required|min_length[2]'];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->tagModel->update($id, [
            'nama' => $this->request->getPost('nama')
        ]);
        session()->setFlashdata('success', 'Data tag berhasil diupdate!');
        return redirect()->to('/tag');
    }

    public function delete($id)
    {
        $this->tagModel->delete($id);
        session()->setFlashdata('success', 'Data tag berhasil dihapus!');
        return redirect()->to('/tag');
    }
}
