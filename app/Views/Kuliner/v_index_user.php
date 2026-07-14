<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Jelajahi dan daftar kuliner — Kuliner">
    <title><?= esc($title) ?> — Kuliner</title>
    <link rel="stylesheet" href="<?= base_url('kuliner.css') ?>">
</head>
<body>
    <div class="kl-layout">
        <!-- Sidebar Kontributor -->
        <aside class="kl-sidebar">
            <div class="kl-sidebar-header">
                <a href="<?= base_url('dashboard') ?>" class="kl-logo" style="font-size: 1.35rem; text-decoration: none;">
                     <span>Kul</span>inerr
                </a>
            </div>
            <div class="kl-sidebar-user">
                <div class="kl-avatar" style="width: 40px; height: 40px; font-size: 16px;">
                    <?php if ($isLoggedIn): ?>
                        <?= strtoupper(substr(session()->get('nama'), 0, 1)) ?>
                    <?php else: ?>
                        G
                    <?php endif; ?>
                </div>
                <div class="kl-sidebar-user-info">
                    <h4 style="font-size: 14px; font-weight: 600; color: var(--kl-dark); margin: 0 0 2px 0;">
                        <?php if ($isLoggedIn): ?>
                            <?= esc(session()->get('nama')) ?>
                        <?php else: ?>
                            Guest
                        <?php endif; ?>
                    </h4>
                    <span class="kl-badge kl-badge-kategori" style="font-size: 10px;">
                        <?php if ($isLoggedIn): ?>
                            Kontributor
                        <?php else: ?>
                            Pengunjung
                        <?php endif; ?>
                    </span>
                </div>
            </div>
            <nav class="kl-sidebar-nav">
                <?php if ($isLoggedIn): ?>
                    <a href="<?= base_url('dashboard') ?>"> Dashboard</a>
                    <a href="<?= base_url('kuliner') ?>" class="active"> Kuliner Saya</a>
                    <a href="<?= base_url('kuliner/create') ?>">+ Tambah Kuliner</a>
                    <a href="<?= base_url('review') ?>"> Ulasan Saya</a>
                <?php else: ?>
                    <a href="<?= base_url('kuliner') ?>" class="active"> Daftar Kuliner</a>
                    <a href="<?= base_url('login') ?>"> Login</a>
                <?php endif; ?>
            </nav>
        </aside>

        <!-- Main -->
        <main class="kl-main" style="background: var(--kl-bg); position: relative;">
            <div class="kl-topbar-actions">
                <?php if ($isLoggedIn): ?>
                    <a href="<?= base_url('logout') ?>" class="kl-logout-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                        <span>Logout</span>
                    </a>
                <?php else: ?>
                    <a href="<?= base_url('login') ?>" class="kl-logout-btn" style="color: var(--kl-primary); border-color: rgba(232, 149, 109, 0.3); background: #FFF7ED;">
                        Login
                    </a>
                <?php endif; ?>
            </div>
            <div class="kl-main-content">
                <!-- Breadcrumb -->
                <div class="kl-breadcrumb">
                    <a href="<?= $isLoggedIn ? base_url('dashboard') : base_url() ?>"><?= $isLoggedIn ? 'Dashboard' : 'Beranda' ?></a>
                    <span class="kl-breadcrumb-sep">›</span>
                    <span>Daftar Kuliner</span>
                </div>

                <div class="kl-page-header kl-flex-between">
                    <div>
                        <h1>Halo, <?= $isLoggedIn ? esc(session()->get('nama')) : 'Pengunjung' ?>!</h1>
                        <p>Jelajahi tempat makan <?= $isLoggedIn ? 'atau pantau pengajuan Anda.' : 'di sekitar area kampus.' ?></p>
                    </div>
                    <a href="<?= $isLoggedIn ? base_url('kuliner/create') : base_url('login') ?>" class="kl-btn kl-btn-primary">
                        ➕ Tambah Tempat
                    </a>
                </div>

                <!-- Flash Message -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="kl-alert kl-alert-success" id="flash-alert">
                        <span>✅</span>
                        <div style="flex: 1;"><?= session()->getFlashdata('success') ?></div>
                        <button class="kl-alert-close" onclick="this.parentElement.remove()">×</button>
                    </div>
                <?php endif; ?>

                <!-- Daftar Kuliner Aktif (Utama) -->
                <div style="margin-bottom: 40px;">
                    <!-- Search Only -->
                    <div class="kl-flex-between kl-mb-md" style="margin-bottom: 24px; justify-content: flex-end;">
                        <input type="text" class="kl-input" placeholder="🔍 Cari nama kuliner..." style="width: 260px; padding: 8px 14px; font-size: 13px;" id="search-input">
                    </div>

                    <h2 style="font-size: 1.15rem; font-weight: 600; color: var(--kl-dark); border-bottom: 2px solid var(--kl-border); padding-bottom: 8px; margin-bottom: 16px;">Daftar Rekomendasi Kuliner</h2>
                    <?php if (!empty($activeKuliner)): ?>
                        <div class="kuliner-grid" id="kuliner-grid">
                            <?php foreach ($activeKuliner as $k): 
                                $userName = $k['user_name'] ?? 'Kontributor';
                                $userInitial = strtoupper(substr($userName, 0, 1));
                            ?>
                            <div class="card-kuliner status-<?= esc($k['status']) ?>" data-name="<?= strtolower(esc($k['nama'])) ?>">
                                <!-- Header Strip -->
                                <div class="card-header-strip">
                                    <div class="card-header-user">
                                        <div class="card-header-avatar"><?= $userInitial ?></div>
                                        <div class="card-header-name" title="<?= esc($userName) ?>"><?= esc($userName) ?></div>
                                    </div>
                                    <div class="card-header-actions"></div>
                                </div>
                                <a href="<?= base_url('kuliner/detail/' . $k['id']) ?>" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; flex: 1;">
                                    <div class="card-photo-grid <?= empty($k['foto2']) ? 'card-photo-single' : '' ?>">
                                        <?php if (!empty($k['gambar'])): ?>
                                            <img src="<?= base_url('uploads/kuliner/' . $k['gambar']) ?>" alt="<?= esc($k['nama']) ?>" loading="lazy">
                                            <?php if (!empty($k['foto2'])): ?>
                                                <img src="<?= base_url('uploads/kuliner/' . $k['foto2']) ?>" alt="<?= esc($k['nama']) ?> 2" loading="lazy">
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <div class="card-photo-placeholder">Foto belum tersedia</div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-name-strip"><?= esc($k['nama']) ?></div>
                                </a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="kl-empty" style="padding: 30px 20px; min-height: auto;">
                            <p style="margin:0; font-size: 14px;">Belum ada tempat makan yang tersedia.</p>
                            <a href="<?= $isLoggedIn ? base_url('kuliner/create') : base_url('login') ?>" class="kl-btn kl-btn-primary" style="margin-top: 15px;">➕ Tambah Tempat</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pengajuan Saya (Khusus Kontributor yg Sedang Login) -->
                <?php if ($isLoggedIn && !empty($mySubmissions)): ?>
                <div style="margin-bottom: 40px;">
                    <h2 style="font-size: 1.15rem; font-weight: 600; color: var(--kl-primary); border-bottom: 2px solid var(--kl-primary); padding-bottom: 8px; margin-bottom: 16px;">Pengajuan Saya (Belum Disetujui)</h2>
                    <div class="kuliner-grid">
                        <?php foreach ($mySubmissions as $k): 
                            $userName = $k['user_name'] ?? 'Kontributor';
                            $userInitial = strtoupper(substr($userName, 0, 1));
                        ?>
                        <div class="card-kuliner status-<?= esc($k['status']) ?>" data-name="<?= strtolower(esc($k['nama'])) ?>">
                            <!-- Header Strip -->
                            <div class="card-header-strip">
                                <div class="card-header-user">
                                    <div class="card-header-avatar"><?= $userInitial ?></div>
                                    <div class="card-header-name" title="<?= esc($userName) ?>"><?= esc($userName) ?></div>
                                </div>
                                <div class="card-header-actions">
                                    <span class="kl-badge" style="font-size: 10px; background: <?= $k['status'] === 'rejected' ? 'var(--kl-danger)' : 'var(--kl-primary)' ?>; color: white; padding: 4px 8px;">
                                        <?= $k['status'] === 'rejected' ? 'Ditolak' : 'Menunggu' ?>
                                    </span>
                                </div>
                            </div>
                            <div style="display: flex; flex-direction: column; flex: 1; opacity: 0.8; cursor: not-allowed;" title="Menunggu review admin, belum dipublikasikan.">
                                <div class="card-photo-grid <?= empty($k['foto2']) ? 'card-photo-single' : '' ?>">
                                    <?php if (!empty($k['gambar'])): ?>
                                        <img src="<?= base_url('uploads/kuliner/' . $k['gambar']) ?>" alt="<?= esc($k['nama']) ?>" loading="lazy">
                                    <?php else: ?>
                                        <div class="card-photo-placeholder">Foto belum tersedia</div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-name-strip"><?= esc($k['nama']) ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </main>
    </div>

    <script>
        // Filter tabs
        document.querySelectorAll('#filter-tabs .kl-filter-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('#filter-tabs .kl-filter-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                const filter = this.dataset.filter;
                document.querySelectorAll('#kuliner-grid .card-kuliner').forEach(card => {
                    if (filter === 'all' || card.dataset.status === filter) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Auto-dismiss flash
        setTimeout(() => {
            const flash = document.getElementById('flash-alert');
            if (flash) flash.style.opacity = '0';
            setTimeout(() => flash?.remove(), 300);
        }, 4000);
    </script>
</body>
</html>
