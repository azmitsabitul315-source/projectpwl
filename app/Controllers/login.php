<?php

namespace App\Controllers;

use App\Models\UserModel;

class Login extends BaseController
{
    public function index()
    {
        helper('form');

        if (session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'Halaman Login'
        ];

        return view('login_page', $data);
    }

    public function auth()
    {
        $session = session();
        $model = new UserModel(); 
        
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('paswd');

       
        $user = $model->where('email', $email)->first();

        if ($user) {
            $pass = $user['password'];
           
            if (password_verify($password, $pass)) {
                $ses_data = [
                    'userid'    => $user['id'],
                    'nama'      => $user['nama'],
                    'email'     => $user['email'],
                    'role'      => $user['role'],
                    'logged_in' => TRUE
                ];
                $session->set($ses_data);
                
                if ($user['role'] == 'admin') {
                    return redirect()->to('/admin/dashboard');
                } else {
                    return redirect()->to('/dashboard');
                }
            } else {
                $session->setFlashdata('msg', 'Password salah.');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('msg', 'Email tidak ditemukan.');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}