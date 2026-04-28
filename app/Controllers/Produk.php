<?php

namespace App\Controllers;

class Produk extends BaseController
{
    public function produk()
    {
        // Ini untuk halaman produk
        $data = [
            'title'   => 'Daftar Produk',
            'content' => view('v_produk') // Jika Anda menggunakan sistem inject content
        ];
        
        // Sesuaikan dengan nama template/layout Anda
        return view('v_produk', $data); 
    }

    public function edit($id)
{
    echo "Halaman Edit untuk ID: " . $id;
    // Nanti di sini kita panggil view form edit
}

public function delete($id)
{
    echo "Proses Hapus untuk ID: " . $id;
    // Nanti di sini kita panggil model untuk hapus data
}
    
}