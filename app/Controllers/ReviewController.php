<?php

namespace App\Controllers;

use App\Models\KulinerModel;
use App\Models\ReviewModel;

class ReviewController extends BaseController
{
    protected $reviewModel;

    public function __construct()
    {
        $this->reviewModel = new ReviewModel();
    }

    public function store()
    {
        // Fitur ini hanya bisa diakses oleh user yang login (diatur di Routes.php filter)
        $rules = [
            'kuliner_id' => 'required|numeric',
            'rating'     => 'required|numeric|greater_than[0]|less_than[6]',
            'komentar'   => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $kulinerModel = new KulinerModel();
        $kuliner = $kulinerModel->find($this->request->getPost('kuliner_id'));

        if (empty($kuliner) || $kuliner['status'] !== 'active') {
            return redirect()->back()->withInput()->with('errors', ['kuliner_id' => 'Pilihan kuliner tidak valid.']);
        }

        $this->reviewModel->save([
            'user_id'    => session()->get('userid'),
            'kuliner_id' => $this->request->getPost('kuliner_id'),
            'rating'     => $this->request->getPost('rating'),
            'komentar'   => $this->request->getPost('komentar')
        ]);

        session()->setFlashdata('success', 'Review berhasil dikirim!');
        return redirect()->back();
    }

    public function delete($id)
    {
        $review = $this->reviewModel->find($id);
        if (empty($review)) {
            return redirect()->back()->with('error', 'Review tidak ditemukan.');
        }

        $currentUserId = session()->get('userid');
        $currentRole = session()->get('role');

        if ($currentRole !== 'admin' && $currentUserId !== $review['user_id']) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus review ini.');
        }

        $this->reviewModel->delete($id);
        session()->setFlashdata('success', 'Review berhasil dihapus!');
        return redirect()->back();
    }
}
