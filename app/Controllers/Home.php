<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $data = [
            'title' => 'Beranda',
            'content' => view('default')
        ];
        return view('v_home',$data);
    }

    function produk()
    {
        return view('v_produk');
    }

    

    
}
