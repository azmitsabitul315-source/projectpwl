<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        /* 1. Background sama dengan Dashboard */
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        /* 2. Navbar sama dengan Dashboard */
        .navbar {
            background: linear-gradient(90deg, #1a1a2e 0%, #16213e 100%) !important;
            border-bottom: 3px solid #667eea;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: 0.5px;
        }
        .logout-btn {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            border: none;
            padding: 0.4rem 1.2rem;
            border-radius: 25px;
            font-weight: 600;
            color: white;
        }
        .logout-btn:hover {
            background: linear-gradient(135deg, #ee5a5a 0%, #e34559 100%);
            color: white;
        }

        /* 3. Card Pembungkus Konten Utama */
        .content-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            background: white;
            overflow: hidden;
        }
        .card-header-custom {
            padding: 2rem 2.5rem;
            border-bottom: 1px solid #f1f5f9;
        }

        /* 4. Tombol Aksi */
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            background: linear-gradient(135deg, #5568d3 0%, #6a3f91 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        /* 5. Estetika Tabel & Gambar */
        .img-kuliner {
            width: 70px;
            height: 70px;
            object-fit: cover; 
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s ease-in-out;
        }
        .img-kuliner:hover {
            transform: scale(1.1);
        }
        .img-placeholder {
            width: 70px;
            height: 70px;
            border-radius: 8px;
            border: 1.5px dashed #cbd5e1;
            background-color: #f8fafc;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
        }
        .table-hover tbody tr:hover {
            background-color: #f8fafc;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('dashboard'); ?>">
                <i class="bi bi-shop-window me-2"></i>Aplikasi Kuliner
            </a>
            <div class="ms-auto">
                <span class="text-light me-3 d-none d-md-inline">
                    <i class="bi bi-person-circle"></i> <?= session()->get('nama'); ?>
                </span>
                <a href="<?= base_url('logout'); ?>" class="logout-btn btn btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="content-card">
            
            <div class="card-header-custom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h3 class="fw-bold text-dark mb-1"><i class="bi bi-card-list me-2"></i>Daftar Kuliner</h3>
                    <p class="text-muted mb-0">Kelola direktori tempat dan menu makanan sistem Anda.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?= base_url('dashboard'); ?>" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm d-flex align-items-center">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                    <?php if (session()->get('role') === 'admin'): ?>
                        <a href="<?= base_url('kuliner/create'); ?>" class="btn btn-gradient rounded-pill px-4 shadow-sm d-flex align-items-center">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Data
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (session()->getFlashdata('success')) : ?>
                <div class="px-4 pt-3">
                    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-0 rounded-3" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card-body p-0 mt-2">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless align-middle mb-0">
                        <thead class="border-bottom" style="background-color: #f8fafc;">
                            <tr>
                                <th class="py-3 px-4 text-center text-muted" width="5%">No</th>
                                <th class="py-3 px-4 text-muted" width="12%">Foto</th>
                                <th class="py-3 px-4 text-muted" width="23%">Nama Kuliner</th>
                                <th class="py-3 px-4 text-muted" width="40%">Alamat</th>
                                <?php if (session()->get('role') === 'admin'): ?>
                                    <th class="py-3 px-4 text-center text-muted" width="20%">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach ($kuliner as $k) : ?>
                            <tr class="border-bottom">
                                <td class="px-4 text-center fw-semibold text-secondary"><?= $i++; ?></td>
                                <td class="px-4 py-3">
                                    <?php if (! empty($k['gambar'])): ?>
                                        
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalFoto<?= $k['id']; ?>">
                                            <img src="<?= base_url('uploads/kuliner/' . $k['gambar']); ?>" class="img-kuliner" style="cursor: zoom-in;" alt="Foto <?= $k['nama']; ?>" title="Klik untuk memperbesar">
                                        </a>

                                        <div class="modal fade" id="modalFoto<?= $k['id']; ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content bg-transparent border-0">
                                                    <div class="modal-body text-center position-relative">
                                                        
                                                        <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-0" data-bs-dismiss="modal" aria-label="Close" style="background-color: rgba(0,0,0,0.6); padding: 10px; border-radius: 50%; z-index: 10;"></button>
                                                        
                                                        <img src="<?= base_url('uploads/kuliner/' . $k['gambar']); ?>" class="img-fluid rounded-4 shadow-lg" alt="Foto Besar <?= $k['nama']; ?>">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php else: ?>
                                        <div class="img-placeholder">
                                            <i class="bi bi-image fs-5"></i>
                                            <small style="font-size: 0.65rem;">Kosong</small>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 fw-bold text-dark"><?= $k['nama']; ?></td>
                                <td class="px-4 text-secondary" style="font-size: 0.95rem;"><?= $k['alamat']; ?></td>
                                <?php if (session()->get('role') === 'admin'): ?>
                                    <td class="px-4 text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="<?= base_url('kuliner/edit/' . $k['id']); ?>" class="btn btn-warning btn-sm text-dark px-3 rounded-pill shadow-sm" title="Edit Data">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="<?= base_url('kuliner/delete/' . $k['id']); ?>" class="btn btn-danger btn-sm px-3 rounded-pill shadow-sm" onclick="return confirm('Tindakan ini tidak dapat dibatalkan. Yakin ingin menghapus data ini?');" title="Hapus Data">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; ?>
                            
                            <?php if(empty($kuliner)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                    Belum ada data kuliner. Silakan tambah data baru.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>