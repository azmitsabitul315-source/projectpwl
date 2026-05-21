<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container">
        <div class="card p-4 mx-auto" style="max-width: 600px;">
            <h3>Tambah Kuliner</h3>
            
            <?php if(session()->has('errors')): ?>
                <div class="alert alert-danger">
                    <ul>
                    <?php foreach(session('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('kuliner/store'); ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label>Nama Kuliner</label>
                    <input type="text" name="nama" class="form-control" value="<?= old('nama'); ?>">
                </div>
                <div class="mb-3">
                    <label>Alamat</label>
                    <input type="text" name="alamat" class="form-control" value="<?= old('alamat'); ?>">
                </div>
                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control"><?= old('deskripsi'); ?></textarea>
                </div>
                <div class="mb-3">
                    <label>Gambar (Max 2MB)</label>
                    <input type="file" name="gambar" class="form-control">
                </div>
                <button type="submit" class="btn btn-success w-100">Simpan Data & Upload Gambar</button>
            </form>
        </div>
    </div>
</body>
</html>