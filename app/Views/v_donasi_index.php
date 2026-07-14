<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donasi Developer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .donasi-card {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            background-color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="donasi-card">
            <h3 class="text-center mb-4">Donasi Developer</h3>
            <p class="text-muted text-center mb-4">Dukung kami untuk terus mengembangkan fitur-fitur bermanfaat.</p>
            
            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('donasi/pay') ?>" method="POST">
                    <div class="mb-3">
                        <label for="nama_donatur" class="form-label">Nama Anda</label>
                        <input type="text" class="form-control" id="nama_donatur" name="nama_donatur" required placeholder="Contoh: Budi">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Alamat Email</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="Contoh: budi@gmail.com">
                        <div class="form-text">Kami akan mengirimkan ucapan terima kasih ke email ini.</div>
                    </div>
                <div class="mb-4">
                    <label for="nominal" class="form-label">Nominal Donasi</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="nominal" name="nominal" required min="10000" placeholder="10000">
                    </div>
                    <div class="form-text">Minimal donasi Rp 10.000</div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Donasi Sekarang</button>
                <a href="<?= base_url('/') ?>" class="btn btn-outline-secondary w-100 mt-2">Kembali ke Beranda</a>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
