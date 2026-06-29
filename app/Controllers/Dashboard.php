<?php

namespace App\Controllers;

use App\Models\KulinerModel;
use App\Models\ReviewModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    public function index()
    {
        if (session()->get('role') === 'admin') {
            return redirect()->to('/admin/dashboard');
        }

        $data = [
            'title' => 'Dashboard Kontributor'
        ];
        return view('v_dashboard', $data);
    }

    public function admin()
    {
        $kulinerModel = new KulinerModel();
        $reviewModel = new ReviewModel();
        $userModel = new UserModel();

        $topRatedKuliner = $reviewModel
            ->select('kuliner.id, kuliner.nama, AVG(reviews.rating) AS avg_rating, COUNT(reviews.id) AS review_count')
            ->join('kuliner', 'kuliner.id = reviews.kuliner_id')
            ->where('kuliner.status', 'active')
            ->groupBy('kuliner.id')
            ->orderBy('avg_rating', 'DESC')
            ->orderBy('review_count', 'DESC')
            ->first();

        $data = [
            'title'            => 'Dashboard Admin',
            'totalKuliner'     => $kulinerModel->countAllResults(),
            'pendingKuliner'   => $kulinerModel->where('status', 'pending')->countAllResults(),
            'activeKuliner'    => $kulinerModel->where('status', 'active')->countAllResults(),
            'reviewCount'      => $reviewModel->countAllResults(),
            'userCount'        => $userModel->countAllResults(),
            'topRatedKuliner'  => $topRatedKuliner,
        ];

        return view('v_dashboard', $data);
    }

    public function review()
    {
        $reviewModel = new ReviewModel();
        $kulinerModel = new KulinerModel();

        $reviews = $reviewModel
            ->select('reviews.*, users.nama as user_name, kuliner.nama as kuliner_nama, kuliner.id_kategori, kategori.nama as kategori_nama')
            ->join('users', 'users.id = reviews.user_id', 'left')
            ->join('kuliner', 'kuliner.id = reviews.kuliner_id', 'left')
            ->join('kategori', 'kategori.id = kuliner.id_kategori', 'left')
            ->orderBy('reviews.created_at', 'DESC')
            ->findAll();

        $ratingAverage = null;
        if (!empty($reviews)) {
            $ratingAverage = round(array_sum(array_column($reviews, 'rating')) / count($reviews), 1);
        }

        $data = [
            'title'           => 'Rating dan Review',
            'reviews'         => $reviews,
            'ratingAverage'   => $ratingAverage,
            'reviewCount'     => count($reviews),
            'kulinerList'     => $kulinerModel->select('id, nama')->where('status', 'active')->findAll(),
            'currentUserId'   => session()->get('userid'),
            'currentUserRole' => session()->get('role'),
        ];

        return view('review_page', $data);
    }
}