<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dashboard Kuliner — Kelola kuliner sekitar UDINUS Semarang">
    <title><?= esc($title) ?> — Kuliner</title>
    <link rel="stylesheet" href="<?= base_url('kuliner.css') ?>">
</head>
<body>
    <div class="kl-layout">
        <!-- Sidebar -->
        <aside class="kl-sidebar">
            <div class="kl-sidebar-header">
                <a href="<?= base_url('dashboard') ?>" class="kl-logo" style="font-size: 1.35rem; text-decoration: none;">
                    🍽️ <span>Kul</span>ine
                </a>
                <?php if (session()->get('role') === 'admin'): ?>
                    <span class="kl-admin-badge" style="margin-left: 8px;">Admin</span>
                <?php endif; ?>
            </div>

            <div class="kl-sidebar-user">
                <div class="kl-avatar" style="width: 40px; height: 40px; font-size: 16px;">
                    <?= strtoupper(substr(session()->get('nama'), 0, 1)) ?>
                </div>
                <div class="kl-sidebar-user-info">
                    <h4 style="font-size: 14px; font-weight: 600; color: var(--kl-dark); margin: 0 0 2px 0;">
                        <?= esc(session()->get('nama')) ?>
                    </h4>
                    <span class="kl-badge kl-badge-kategori" style="font-size: 10px;">
                        <?= ucfirst(session()->get('role')) ?>
                    </span>
                </div>
            </div>

            <nav class="kl-sidebar-nav">
                <?php if (session()->get('role') === 'admin'): ?>
                    <a href="<?= base_url('admin/dashboard') ?>" class="active">📊 Dashboard</a>
                    <a href="<?= base_url('kuliner') ?>">🍽️ Kelola Kuliner</a>
                    <a href="<?= base_url('kategori') ?>">🏷️ Kategori</a>
                    <a href="<?= base_url('tag') ?>">🔖 Tag</a>
                    <a href="<?= base_url('review') ?>">⭐ Kelola Ulasan</a>
                <?php else: ?>
                    <a href="<?= base_url('dashboard') ?>" class="active">🏠 Dashboard</a>
                    <a href="<?= base_url('kuliner') ?>">🍽️ Kuliner Saya</a>
                    <a href="<?= base_url('kuliner/create') ?>">➕ Tambah Kuliner</a>
                    <a href="<?= base_url('review') ?>">⭐ Ulasan Saya</a>
                <?php endif; ?>
            </nav>

            <!-- Logout moved to topbar -->
        </aside>

        <!-- Main Content -->
        <main class="kl-main" style="background: var(--kl-bg); position: relative;">
            <div class="kl-topbar-actions">
                <a href="<?= base_url('logout') ?>" class="kl-logout-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    <span>Logout</span>
                </a>
            </div>
            <div class="kl-main-content">

                <!-- Page Header -->
                <div class="kl-page-header">
                    <h1 style="font-size: 26px;">
                        Halo, <?= esc(session()->get('nama')) ?>! 
                    </h1>
                    <p style="color: var(--kl-muted); font-size: 14px; margin-top: 4px;">
                        <?= date('l, d F Y') ?> — Selamat datang di dashboard <?= ucfirst(session()->get('role')) ?>.
                    </p>
                </div>

                <?php if (session()->get('role') === 'admin' && isset($totalKuliner)): ?>
                    <!-- Admin Stats -->
                    <div class="kl-stats-grid">
                        <div class="kl-stat-card">
                            <div class="kl-stat-icon" style="background: var(--kl-primary-light); color: var(--kl-primary);">🍽️</div>
                            <div class="kl-stat-number"><?= $totalKuliner ?></div>
                            <div class="kl-stat-label">Total Kuliner</div>
                        </div>
                        <div class="kl-stat-card" style="border-left: 3px solid var(--kl-warning);">
                            <div class="kl-stat-icon" style="background: var(--kl-warning-light); color: var(--kl-warning);">⏳</div>
                            <div class="kl-stat-number"><?= $pendingKuliner ?></div>
                            <div class="kl-stat-label">Pending Review</div>
                        </div>
                        <div class="kl-stat-card">
                            <div class="kl-stat-icon" style="background: var(--kl-info-light); color: var(--kl-info);">👥</div>
                            <div class="kl-stat-number"><?= $userCount ?></div>
                            <div class="kl-stat-label">Total User</div>
                        </div>
                        <div class="kl-stat-card">
                            <div class="kl-stat-icon" style="background: #FEF3C7; color: #D97706;">⭐</div>
                            <div class="kl-stat-number"><?= $reviewCount ?></div>
                            <div class="kl-stat-label">Total Ulasan</div>
                        </div>
                    </div>

                    <?php if (!empty($topRatedKuliner)): ?>
                        <!-- Top Rated -->
                        <div class="kl-card" style="padding: 20px; margin-bottom: 28px;">
                            <div class="kl-flex-between">
                                <div>
                                    <div class="kl-label" style="color: var(--kl-muted); margin-bottom: 8px;">🏆 Kuliner Terpopuler</div>
                                    <h3 style="font-size: 20px; margin-bottom: 4px;"><?= esc($topRatedKuliner['nama']) ?></h3>
                                    <p class="kl-text-muted" style="font-size: 14px;">
                                        Rata-rata ⭐ <?= number_format($topRatedKuliner['avg_rating'], 1) ?> dari <?= $topRatedKuliner['review_count'] ?> ulasan
                                    </p>
                                </div>
                                <div style="font-size: 48px; opacity: 0.3;">🏆</div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Quick Actions Admin -->
                    <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 16px; color: var(--kl-dark);">Menu Cepat</h3>
                    <div class="kl-grid-3" style="margin-bottom: 28px;">
                        <a href="<?= base_url('kuliner') ?>" class="kl-card kl-card-texture" style="padding: 24px; text-decoration: none; display: flex; align-items: center; gap: 16px;">
                            <div style="font-size: 28px;">🍽️</div>
                            <div>
                                <h4 style="font-size: 15px; font-weight: 600; color: var(--kl-dark); margin-bottom: 2px;">Kelola Kuliner</h4>
                                <p style="font-size: 13px; color: var(--kl-muted); margin: 0;">Lihat semua data kuliner</p>
                            </div>
                        </a>
                        <a href="<?= base_url('kategori') ?>" class="kl-card kl-card-texture" style="padding: 24px; text-decoration: none; display: flex; align-items: center; gap: 16px;">
                            <div style="font-size: 28px;">🏷️</div>
                            <div>
                                <h4 style="font-size: 15px; font-weight: 600; color: var(--kl-dark); margin-bottom: 2px;">Kelola Kategori</h4>
                                <p style="font-size: 13px; color: var(--kl-muted); margin: 0;">Atur kategori kuliner</p>
                            </div>
                        </a>
                        <a href="<?= base_url('tag') ?>" class="kl-card kl-card-texture" style="padding: 24px; text-decoration: none; display: flex; align-items: center; gap: 16px;">
                            <div style="font-size: 28px;">🔖</div>
                            <div>
                                <h4 style="font-size: 15px; font-weight: 600; color: var(--kl-dark); margin-bottom: 2px;">Kelola Tag</h4>
                                <p style="font-size: 13px; color: var(--kl-muted); margin: 0;">Atur tag & label</p>
                            </div>
                        </a>
                        <a href="<?= base_url('review') ?>" class="kl-card kl-card-texture" style="padding: 24px; text-decoration: none; display: flex; align-items: center; gap: 16px;">
                            <div style="font-size: 28px;">⭐</div>
                            <div>
                                <h4 style="font-size: 15px; font-weight: 600; color: var(--kl-dark); margin-bottom: 2px;">Moderasi Ulasan</h4>
                                <p style="font-size: 13px; color: var(--kl-muted); margin: 0;">Kelola ulasan pengguna</p>
                            </div>
                        </a>
                    </div>

                <?php else: ?>
                    <!-- Kontributor Dashboard -->
                    <div class="kl-grid-2" style="margin-bottom: 28px;">
                        <a href="<?= base_url('kuliner') ?>" class="kl-card kl-card-texture" style="padding: 32px; text-decoration: none; text-align: center; transition: all 0.3s ease;">
                            <div style="font-size: 48px; margin-bottom: 12px;">🍽️</div>
                            <h3 style="font-size: 18px; font-weight: 700; color: var(--kl-dark); margin-bottom: 6px;">Lihat Daftar Kuliner</h3>
                            <p style="font-size: 14px; color: var(--kl-muted); margin: 0;">Jelajahi semua kuliner aktif & pantau pengajuan Anda</p>
                        </a>
                        <a href="<?= base_url('review') ?>" class="kl-card kl-card-texture" style="padding: 32px; text-decoration: none; text-align: center; transition: all 0.3s ease;">
                            <div style="font-size: 48px; margin-bottom: 12px;">⭐</div>
                            <h3 style="font-size: 18px; font-weight: 700; color: var(--kl-dark); margin-bottom: 6px;">Rating & Ulasan</h3>
                            <p style="font-size: 14px; color: var(--kl-muted); margin: 0;">Tulis ulasan dan lihat review dari pengguna lain</p>
                        </a>
                    </div>

                    <div class="kl-card" style="padding: 32px; text-align: center; background: var(--kl-gradient-hero);">
                        <div style="font-size: 36px; margin-bottom: 12px;">➕</div>
                        <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 8px;">Tahu tempat makan enak?</h3>
                        <p style="color: var(--kl-muted); font-size: 14px; margin-bottom: 16px;">
                            Bantu teman-teman sekampus menemukan kuliner terbaik!
                        </p>
                        <a href="<?= base_url('kuliner/create') ?>" class="kl-btn kl-btn-primary">
                            Tambah Tempat Baru →
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Info Footer -->
                <div style="text-align: center; margin-top: 40px; padding: 20px; color: var(--kl-muted); font-size: 13px;">
                    <p>Jika mengalami kendala, hubungi administrator sistem.</p>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Avatar dropdown toggle
        document.querySelectorAll('.kl-avatar-dropdown').forEach(el => {
            el.querySelector('.kl-avatar')?.addEventListener('click', () => {
                el.classList.toggle('open');
            });
        });
        // Close dropdown on outside click
        document.addEventListener('click', (e) => {
            document.querySelectorAll('.kl-avatar-dropdown.open').forEach(el => {
                if (!el.contains(e.target)) el.classList.remove('open');
            });
        });
    </script>
</body>
</html>
