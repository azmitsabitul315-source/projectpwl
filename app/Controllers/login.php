<?php

namespace App\Controllers;

use App\Models\UserModel;

class Login extends BaseController
{
    public function index()
    {
        helper('form');

        if (session()->get('logged_in')) {
            if (session()->get('role') == 'admin') {
                return redirect()->to('/admin/dashboard');
            } else {
                return redirect()->to('/dashboard');
            }
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
        
        $rules = [
            'email' => 'required|valid_email',
            'paswd' => 'required|min_length[7]',
        ];

        $messages = [
            'email' => [
                'required'    => 'Email harus diisi.',
                'valid_email' => 'Masukkan alamat email yang valid.',
            ],
            'paswd' => [
                'required'   => 'Password harus diisi.',
                'min_length' => 'Password minimal 7 karakter.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            $session->setFlashdata('msg', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }

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