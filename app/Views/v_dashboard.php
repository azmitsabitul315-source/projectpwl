<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Aplikasi Kuliner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

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

        .welcome-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            overflow: hidden;
            background: white;
        }

        .welcome-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .welcome-header h3 {
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .role-badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .menu-section {
            padding: 2.5rem;
        }

        .menu-section h5 {
            color: #1a1a2e;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
            opacity: 0.7;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }

        .menu-btn {
            border: none;
            border-radius: 12px;
            padding: 1.2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            color: white;
            font-size: 0.95rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .menu-btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            color: white;
            text-decoration: none;
        }

        .menu-btn i {
            font-size: 2rem;
        }

        .menu-btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .menu-btn-primary:hover {
            background: linear-gradient(135deg, #5568d3 0%, #6a3f91 100%);
        }

        .menu-btn-secondary {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .menu-btn-secondary:hover {
            background: linear-gradient(135deg, #e77fe0 0%, #f0355b 100%);
        }

        .menu-btn-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .menu-btn-info:hover {
            background: linear-gradient(135deg, #3d9bde 0%, #00d9e9 100%);
        }

        .logout-section {
            text-align: center;
            padding: 1.5rem;
            border-top: 1px solid #e0e0e0;
        }

        .logout-btn {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            border: none;
            padding: 0.7rem 2rem;
            border-radius: 25px;
            font-weight: 600;
            color: white;
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, #ee5a5a 0%, #e34559 100%);
            color: white;
            text-decoration: none;
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
                <span class="text-light me-3">
                    <i class="bi bi-person-circle"></i> <?= session()->get('nama'); ?>
                </span>
                <a href="<?= base_url('logout'); ?>" class="logout-btn btn btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="welcome-card">
                    
                    <div class="welcome-header">
                        <h3><i class="bi bi-hand-thumbs-up me-2"></i>Selamat Datang!</h3>
                        <p class="mb-0">Anda login sebagai</p>
                        <div class="role-badge mt-2">
                            <i class="bi bi-shield-check me-1"></i><?= ucfirst(session()->get('role')); ?>
                        </div>
                    </div>

                    <div class="menu-section">
                        <p class="text-muted mb-3">
                            Gunakan menu di bawah untuk mengelola data kuliner sesuai dengan hak akses Anda.
                        </p>

                        <?php if (session()->get('role') == 'admin'): ?>
                            
                            <h5><i class="bi bi-sliders me-2"></i>Menu Admin</h5>
                            <div class="menu-grid">
                                <a href="<?= base_url('kuliner'); ?>" class="menu-btn menu-btn-primary">
                                    <i class="bi bi-shop"></i>
                                    <span>Kelola Kuliner</span>
                                </a>
                                <a href="#" class="menu-btn menu-btn-secondary">
                                    <i class="bi bi-tag"></i>
                                    <span>Kategori</span>
                                </a>
                                <a href="#" class="menu-btn menu-btn-info">
                                    <i class="bi bi-check-circle"></i>
                                    <span>Validasi</span>
                                </a>
                            </div>

                        <?php else: ?>
                            
                            <h5><i class="bi bi-eye me-2"></i>Menu Kontributor</h5>
                            <div class="menu-grid">
                                <a href="<?= base_url('kuliner'); ?>" class="menu-btn menu-btn-primary">
                                    <i class="bi bi-list-check"></i>
                                    <span>Lihat Daftar</span>
                                </a>
                            </div>

                        <?php endif; ?>
                    </div>

                    <div class="logout-section">
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> Jika mengalami kendala, hubungi administrator sistem.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>