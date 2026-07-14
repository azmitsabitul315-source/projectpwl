<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Masuk ke Kuliner — temukan kuliner terbaik di sekitar UDINUS Semarang">
    <title><?= $title ?> — Kuliner</title>
    <link rel="stylesheet" href="<?= base_url('kuliner.css') ?>">
</head>
<body style="background: var(--kl-bg);">

    <div class="kl-auth-layout">
        <!-- Left Panel — Decorative -->
        <div class="kl-auth-left">
            <div class="kl-auth-left-emojis">
                <span>🍜</span>
                <span>☕</span>
                <span>🥣</span>
                <span>🧋</span>
                <span>🍳</span>
            </div>
            <div style="position: relative; z-index: 1;">
                <div class="kl-logo" style="margin-bottom: 32px; font-size: 2rem;">
                    <span> </span>Kuliner
                </div>
                <h1 style="font-size: 36px; font-weight: 700; color: var(--kl-dark); margin-bottom: 16px; font-family: var(--kl-font-display);">
                    Kuliner sekitar UDINUS,<br>semuanya di sini.
                </h1>
                <p style="font-size: 16px; color: var(--kl-muted); max-width: 400px; line-height: 1.7;">
                    Temukan warung makan, coffee shop, dan burjo favorit di sekitar kampus. Masuk untuk mulai berkontribusi.
                </p>
            </div>
        </div>

        <!-- Right Panel — Login Form -->
        <div class="kl-auth-right">
            <div class="kl-auth-card">
                <div class="kl-text-center" style="margin-bottom: 32px;">
                    <div class="kl-logo" style="justify-content: center; margin-bottom: 20px; font-size: 1.6rem;">
                         <span></span>Kuliner
                    </div>
                    <h2 style="font-family: var(--kl-font-display); font-size: 24px; font-weight: 700; margin-bottom: 8px;">
                        Selamat datang kembali
                    </h2>
                    <p style="color: var(--kl-muted); font-size: 14px;">
                        Masuk untuk mulai berkontribusi
                    </p>
                </div>

                <?php if (session()->getFlashdata('msg')): ?>
                    <div class="kl-alert kl-alert-danger">
                        <span>⚠️</span>
                        <div style="flex: 1;"><?= session()->getFlashdata('msg') ?></div>
                    </div>
                <?php endif; ?>

                <?= form_open(site_url('login-auth')) ?>
                    <div class="kl-form-group">
                        <label class="kl-form-label">Email</label>
                        <input type="email" name="email" class="kl-input" placeholder="nama@email.com" required minlength="6" value="<?= old('email'); ?>" id="login-email">
                    </div>
                    
                    <div class="kl-form-group">
                        <label class="kl-form-label">Password</label>
                        <div class="kl-password-wrap">
                            <input type="password" name="paswd" class="kl-input" placeholder="Masukkan password" required id="login-password">
                            <button type="button" class="kl-password-toggle" onclick="togglePassword()" id="toggle-password" aria-label="Toggle password visibility">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </button>
                        </div>
                    </div>
                    
                    <button type="submit" class="kl-btn kl-btn-primary kl-btn-full kl-btn-lg" style="margin-top: 8px;" id="login-submit">
                        Masuk
                    </button>
                <?= form_close() ?>

                <div class="kl-auth-divider">atau</div>

                <p class="kl-text-center" style="font-size: 14px; color: var(--kl-muted);">
                    Belum punya akun? 
                    <a href="#" style="font-weight: 600;">Daftar di sini →</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('login-password');
            const btn = document.getElementById('toggle-password');
            
            const eyeIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>`;
            const eyeOffIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye-off"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>`;
            
            if (input.type === 'password') {
                input.type = 'text';
                btn.innerHTML = eyeOffIcon;
            } else {
                input.type = 'password';
                btn.innerHTML = eyeIcon;
            }
        }
    </script>
</body>
</html>
