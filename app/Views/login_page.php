<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="<?= base_url('login_style.css') ?>">

</head>
<body>

    <div class="left-panel">
        <h2>Selamat Datang</h2>
        <p>Silakan gunakan Email Anda untuk masuk ke sistem. Pastikan data yang dimasukkan sudah benar.</p>
    </div>

    <div class="right-panel">
        <div class="login-card">
            <h1>Login</h1>
            <p class="subtitle">Masuk untuk melanjutkan ke dashboard.</p>

            <?php if(session()->getFlashdata('msg')): ?>
                <div class="error-msg">
                    <?= session()->getFlashdata('msg') ?>
                </div>
            <?php endif; ?>

            <?= form_open(site_url('login-auth')) ?>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="user@example.com" required>
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="paswd" placeholder="Masukkan Password" required>
                </div>
                
                <button type="submit">Log In</button>
            <?= form_close() ?>
        </div>
    </div>

</body>
</html>