<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> — Kuliner</title>
    <link rel="stylesheet" href="<?= base_url('kuliner.css') ?>">
</head>
<body>
    <div class="kl-layout">
        <!-- Sidebar Admin -->
        <aside class="kl-sidebar">
            <div class="kl-sidebar-header">
                <a href="<?= base_url('admin/dashboard') ?>" class="kl-logo" style="font-size: 1.35rem; text-decoration: none;">
                    🍽️ <span>Kul</span>ine
                </a>
                <span class="kl-admin-badge" style="margin-left: 8px;">Admin</span>
            </div>
            <div class="kl-sidebar-user">
                <div class="kl-avatar" style="width: 40px; height: 40px; font-size: 16px;">
                    <?= strtoupper(substr(session()->get('nama'), 0, 1)) ?>
                </div>
                <div class="kl-sidebar-user-info">
                    <h4 style="font-size: 14px; font-weight: 600; color: var(--kl-dark); margin: 0 0 2px 0;"><?= esc(session()->get('nama')) ?></h4>
                    <span class="kl-badge kl-badge-kategori" style="font-size: 10px;">Admin</span>
                </div>
            </div>
            <nav class="kl-sidebar-nav">
                <a href="<?= base_url('admin/dashboard') ?>">📊 Dashboard</a>
                <a href="<?= base_url('kuliner') ?>">🍽️ Kelola Kuliner</a>
                <a href="<?= base_url('kategori') ?>">🏷️ Kategori</a>
                <a href="<?= base_url('tag') ?>" class="active">🔖 Tag</a>
                <a href="<?= base_url('review') ?>">⭐ Kelola Ulasan</a>
            </nav>
            <!-- Logout moved to topbar -->
        </aside>

        <!-- Main -->
        <main class="kl-main" style="background: var(--kl-bg); position: relative;">
            <div class="kl-topbar-actions">
                <a href="<?= base_url('logout') ?>" class="kl-logout-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    <span>Logout</span>
                </a>
            </div>
            <div class="kl-main-content" style="max-width: 600px;">
                <!-- Breadcrumb -->
                <div class="kl-breadcrumb">
                    <a href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
                    <span class="kl-breadcrumb-sep">›</span>
                    <a href="<?= base_url('tag') ?>">Tag</a>
                    <span class="kl-breadcrumb-sep">›</span>
                    <span>Tambah Baru</span>
                </div>

                <div class="kl-page-header">
                    <h1>Tambah Tag</h1>
                    <p>Buat tag baru untuk label kuliner.</p>
                </div>

                <?php if (session()->has('errors')): ?>
                    <div class="kl-alert kl-alert-danger">
                        <span>⚠️</span>
                        <div style="flex: 1;">
                            <?php foreach (session('errors') as $error): ?>
                                <p style="margin: 0;"><?= $error ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="kl-card" style="padding: 32px;">
                    <form action="<?= base_url('tag/store') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="kl-form-group">
                            <label class="kl-form-label">Nama Tag <span class="kl-required">*</span></label>
                            <input type="text" name="nama" class="kl-input" placeholder="Contoh: Wifi, Halal, AC" required value="<?= old('nama') ?>">
                        </div>
                        <div class="kl-flex kl-gap-sm" style="margin-top: 24px;">
                            <button type="submit" class="kl-btn kl-btn-primary">💾 Simpan</button>
                            <a href="<?= base_url('tag') ?>" class="kl-btn kl-btn-outline">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
