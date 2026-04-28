<?php

namespace App\Controllers;

class Login extends BaseController
{
    public function index()
    {
        // Panggil helper form agar form_open() bekerja
        helper('form');

        // Jika sudah login, jangan kasih akses ke halaman login lagi
        if (session()->get('logged_in')) {
            return redirect()->to('/produk');
        }

        $data = [
            'title' => 'Halaman Login'
        ];

        return view('login_page', $data);
    }

    public function auth()
    {   
        // Data testing (Hardcoded)
        $d_email = "user@example.com";
        $d_password = "Test-123";

        // Ambil input dari form
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('paswd');
        
        if($d_email == $email && $d_password == $password)
        {
            $datauser = [
                'userid'    => 1,
                'email'     => $email,
                'nama'      => 'Fulan',
                'logged_in' => true // HARUS 'logged_in' agar sesuai dengan Filter Auth.php
            ];

            session()->set($datauser);
            session()->set('member','premium');
            
            return redirect()->to('/');
            // Redirect ke halaman produk setelah sukses
            return redirect()->to('/produk');
        }
        else
        {
            // Kirim pesan error jika gagal
            session()->setFlashdata('msg', 'User tidak ditemukan atau Password salah');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}