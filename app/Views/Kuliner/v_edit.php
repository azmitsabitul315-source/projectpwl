<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container">
        <div class="card p-4 mx-auto" style="max-width: 600px;">
            <h3>Edit Kuliner</h3>
            <hr>
            
            <?php if(session()->has('errors')): ?>
                <div class="alert alert-danger">
                    <ul>
                    <?php foreach(session('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('kuliner/update/' . $kuliner['id']); ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label>Nama Kuliner</label>
                    <input type="text" name="nama" class="form-control" value="<?= old('nama', $kuliner['nama']); ?>">
                </div>
                <div class="mb-3">
                    <label>Alamat</label>
                    <input type="text" name="alamat" class="form-control" value="<?= old('alamat', $kuliner['alamat']); ?>">
                </div>
                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control"><?= old('deskripsi', $kuliner['deskripsi']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="d-block">Gambar Saat Ini</label>
                    <img src="<?= base_url('uploads/kuliner/' . $kuliner['gambar']); ?>" width="120" class="img-thumbnail mb-2" alt="Gambar Lama">
                    <input type="file" name="gambar" class="form-control">
                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                </div>
                <button type="submit" class="btn btn-warning w-100">Perbarui Data</button>
                <a href="<?= base_url('kuliner'); ?>" class="btn btn-light w-100 mt-2">Batal</a>
            </form>
        </div>
    </div>
</body>
</html>