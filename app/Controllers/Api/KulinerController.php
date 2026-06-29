<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\KulinerModel;

class KulinerController extends BaseController
{
    protected $model;
    private $token;

    // ══════════════════════════════════════════
    // CONSTRUCTOR — sama seperti materi dosen
    // ══════════════════════════════════════════
    function __construct()
    {
        $this->model = new KulinerModel();
        $this->token = env('MY_API_KEY');
    }

    // ══════════════════════════════════════════
    // CEK API KEY — copy dari materi dosen
    // ══════════════════════════════════════════
    private function authenticate()
    {
        $header = $this->request->getHeaderLine('Authorization');

        if (empty($header)) {
            return false;
        }

        if (!preg_match('/Bearer\s+(.*)$/i', $header, $matches)) {
            return false;
        }

        return $matches[1] === $this->token;
    }

    private function unauthorized()
    {
        return $this->response
                    ->setStatusCode(401)
                    ->setJSON([
                        'status'  => false,
                        'message' => 'Unauthorized'
        ]);
    }

    // ══════════════════════════════════════════
    // INDEX — endpoint utama dengan filter radius
    // ══════════════════════════════════════════
    public function index()
    {
        // Cek API Key dulu
        if (! $this->authenticate()) {
            return $this->unauthorized();
        }

        // Ambil parameter dari URL
        $lat    = $this->request->getGet('lat');
        $lng    = $this->request->getGet('lng');
        $radius = $this->request->getGet('radius'); // dalam km

        // Pagination (sama seperti materi dosen)
        $page    = (int) ($this->request->getGet('page') ?? 1);
        $perPage = (int) ($this->request->getGet('per_page') ?? 10);

        // Query database
        $query = $this->model->where('status', 'active');

        if ($lat && $lng && $radius) {
            // Rumus Haversine: menghitung jarak antar 2 titik di permukaan bumi
            $query->select('kuliner.*, ( 6371 * acos( cos( radians('.$lat.') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('.$lng.') ) + sin( radians('.$lat.') ) * sin( radians( lat ) ) ) ) AS distance');
            $query->having('distance <=', $radius);
            $query->orderBy('distance', 'ASC');
        } else {
            $query->orderBy('created_at', 'DESC');
        }

        // Pagination
        $kuliners = $query->paginate($perPage, 'default', $page);
        $pager = $this->model->pager;

        // Response JSON dengan pagination (sama seperti materi dosen)
        return $this->response->setJSON([
            'status' => true,
            'filter' => [
                'lat'    => $lat,
                'lng'    => $lng,
                'radius' => $radius
            ],
            'data'   => $kuliners,
            'pagination' => [
                'current_page' => $page,
                'per_page'     => $perPage,
                'total_data'   => $pager->getTotal(),
                'has_next'     => $page < $pager->getPageCount(),
                'has_prev'     => $page > 1,
            ]
        ]);
    }
}
