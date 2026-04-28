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
       
        $d_email = "user@example.com";
        $d_password = "Test-123";

       
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('paswd');
        
        if($d_email == $email && $d_password == $password)
        {
            $datauser = [
                'userid'    => 1,
                'email'     => $email,
                'nama'      => 'Fulan',
                'logged_in' => true 
            ];

            session()->set($datauser);
            session()->set('member','premium');
            
            return redirect()->to('/');
            return redirect()->to('/produk');
        }
        else
        {
           
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