<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background-color: #f4f4f4; }
        .login-container { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 300px; }
        h1 { text-align: center; color: #333; }
        .error-msg { color: white; background: #e74c3c; padding: 10px; margin-bottom: 15px; border-radius: 4px; font-size: 14px; text-align: center; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background-color: #062336; border: none; color: white; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background-color: #012035; }
    </style>
</head>
<body>

    <div class="login-container">
        <h1>Login</h1>

        <?php if(session()->getFlashdata('msg')):?>
            <div class="error-msg">
                <?= session()->getFlashdata('msg') ?>
            </div>
        <?php endif;?>

        <?= form_open(site_url('login-auth')) ?>
            <label>Email</label>
            <input type="email" name="email" placeholder="user@example.com" required>
            
            <label>Password</label>
            <input type="password" name="paswd" placeholder="Masukkan Password" required>
            
            <button type="submit">Login</button>
        <?= form_close() ?>
    </div>

</body>
</html>