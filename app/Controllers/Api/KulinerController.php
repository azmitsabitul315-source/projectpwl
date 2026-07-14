<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\KulinerModel;

class KulinerController extends BaseController
{
    protected $model;

    // Daftar field yang diizinkan untuk insert/update
    protected array $allowedFields = [
        'user_id', 'id_kategori', 'nama', 'alamat',
        'deskripsi', 'gambar', 'foto2', 'foto3', 'lat', 'lng', 'status'
    ];

    public function __construct()
    {
        $this->model = new KulinerModel();
    }

    // GET /api/kuliner (list semua kuliner: page, per_page, dan filter lat, lng, radius untuk pencarian radius)
    // Mendukung filter radius jika param lat,lng,radius diberikan
    public function index()
    {
        $lat    = $this->request->getVar('lat');
        $lng    = $this->request->getVar('lng');
        $radius = $this->request->getVar('radius'); // km

        $page    = (int) ($this->request->getVar('page') ?? 1);
        $perPage = (int) ($this->request->getVar('per_page') ?? 10);

        $query = $this->model->where('status', 'active');

        if ($lat && $lng && $radius) {
            // Haversine formula
            $query->select('kuliner.*, ( 6371 * acos( cos( radians(' . $lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians( lat ) ) ) ) AS distance');
            $query->having('distance <=', $radius);
            $query->orderBy('distance', 'ASC');
        } else {
            $query->orderBy('created_at', 'DESC');
        }

        $data = $query->paginate($perPage, 'default', $page);
        $pager = $this->model->pager;

        return $this->response->setJSON([
            'status' => true,
            'filter' => [ 'lat' => $lat, 'lng' => $lng, 'radius' => $radius ],
            'data' => $data,
            'pagination' => [
                'current_page' => $page,
                'per_page'     => $perPage,
                'total'        => $pager ? $pager->getTotal() : count($data),
            ],
        ]);
    }

    // GET /api/kuliner/{id} (detail satu kuliner berdasarkan id)
    public function show($id = null)
    {
        $row = $this->model->find($id);
        if (! $row) {
            return $this->response->setStatusCode(404)->setJSON(['status' => false, 'message' => 'Not found']);
        }

        return $this->response->setJSON(['status' => true, 'data' => $row]);
    }

    // POST /api/kuliner (membuat data kuliner)
    public function create()
    {
        $input = $this->request->getJSON(true) ?? $this->request->getPost();
        if (! $input) {
            return $this->response->setStatusCode(400)->setJSON(['status' => false, 'message' => 'No input provided']);
        }

        $filtered = array_intersect_key($input, array_flip($this->allowedFields));

        $id = $this->model->insert($filtered);
        if ($id === false) {
            return $this->response->setStatusCode(500)->setJSON(['status' => false, 'message' => 'Insert failed']);
        }

        return $this->response->setStatusCode(201)->setJSON(['status' => true, 'id' => $id]);
    }

    // PUT /api/kuliner/{id} (update data kuliner)
    public function update($id = null)
    {
        if (! $id) {
            return $this->response->setStatusCode(400)->setJSON(['status' => false, 'message' => 'ID required']);
        }

        $raw = $this->request->getRawInput();
        $input = $this->request->getJSON(true) ?? $raw;

        if (! $input) {
            return $this->response->setStatusCode(400)->setJSON(['status' => false, 'message' => 'No input provided']);
        }

        $row = $this->model->find($id);
        if (! $row) {
            return $this->response->setStatusCode(404)->setJSON(['status' => false, 'message' => 'Not found']);
        }

        $filtered = array_intersect_key($input, array_flip($this->allowedFields));
        $ok = $this->model->update($id, $filtered);

        if ($ok === false) {
            return $this->response->setStatusCode(500)->setJSON(['status' => false, 'message' => 'Update failed']);
        }

        return $this->response->setJSON(['status' => true]);
    }

    // DELETE /api/kuliner/{id} (hapus data kuliner)
    public function delete($id = null)
    {
        if (! $id) {
            return $this->response->setStatusCode(400)->setJSON(['status' => false, 'message' => 'ID required']);
        }

        $row = $this->model->find($id);
        if (! $row) {
            return $this->response->setStatusCode(404)->setJSON(['status' => false, 'message' => 'Not found']);
        }

        $this->model->delete($id);
        return $this->response->setJSON(['status' => true]);
    }
}
