<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
       
        $data = [
            'title' => 'Dashboard Kontributor'
        ];
        return view('v_dashboard', $data);
    }

    public function admin()
    {
      
        $data = [
            'title' => 'Dashboard Admin'
        ];
        return view('v_dashboard', $data);
    }
}