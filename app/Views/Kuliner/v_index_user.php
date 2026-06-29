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
                    🍽️ <span>Kul</span>ine
                </a>
            </div>
            <div class="kl-sidebar-user">
                <div class="kl-avatar" style="width: 40px; height: 40px; font-size: 16px;">
                    <?= strtoupper(substr(session()->get('nama'), 0, 1)) ?>
                </div>
                <div class="kl-sidebar-user-info">
                    <h4 style="font-size: 14px; font-weight: 600; color: var(--kl-dark); margin: 0 0 2px 0;"><?= esc(session()->get('nama')) ?></h4>
                    <span class="kl-badge kl-badge-kategori" style="font-size: 10px;">Kontributor</span>
                </div>
            </div>
            <nav class="kl-sidebar-nav">
                <a href="<?= base_url('dashboard') ?>">🏠 Dashboard</a>
                <a href="<?= base_url('kuliner') ?>" class="active">🍽️ Kuliner Saya</a>
                <a href="<?= base_url('kuliner/create') ?>">➕ Tambah Kuliner</a>
                <a href="<?= base_url('review') ?>">⭐ Ulasan Saya</a>
            </nav>
        </aside>

        <!-- Main -->
        <main class="kl-main" style="background: var(--kl-bg); position: relative;">
            <div class="kl-topbar-actions">
                <a href="<?= base_url('logout') ?>" class="kl-logout-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    <span>Logout</span>
                </a>
            </div>
            <div class="kl-main-content">
                <!-- Breadcrumb -->
                <div class="kl-breadcrumb">
                    <a href="<?= base_url('dashboard') ?>">Dashboard</a>
                    <span class="kl-breadcrumb-sep">›</span>
                    <span>Daftar Kuliner</span>
                </div>

                <div class="kl-page-header kl-flex-between">
                    <div>
                        <h1>Daftar Kuliner</h1>
                        <p>Jelajahi tempat makan atau pantau pengajuan Anda.</p>
                    </div>
                    <a href="<?= base_url('kuliner/create') ?>" class="kl-btn kl-btn-primary">
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

                <?php 
                    // Gabungkan active kuliner dan submission milik user
                    $activeKuliner = $activeKuliner ?? [];
                    $mySubmissions = $mySubmissions ?? [];
                    $allKuliner = array_merge($activeKuliner, $mySubmissions);
                    $currentUserId = session()->get('userid');
                ?>

                <!-- Filter Tabs -->
                <div class="kl-flex-between kl-mb-md" style="flex-wrap: wrap; gap: 12px; margin-bottom: 24px;">
                    <div class="kl-filter-tabs" id="filter-tabs">
                        <button class="kl-filter-tab active" data-filter="all">Semua</button>
                        <button class="kl-filter-tab" data-filter="active">Aktif</button>
                        <button class="kl-filter-tab" data-filter="pending">Menunggu</button>
                        <button class="kl-filter-tab" data-filter="rejected">Ditolak</button>
                    </div>
                </div>

                <!-- Cards Grid -->
                <div class="kuliner-grid" id="kuliner-grid">
                    <?php if (!empty($allKuliner)): ?>
                        <?php foreach ($allKuliner as $k): 
                            $userName = $k['user_name'] ?? 'Kontributor';
                            $userInitial = strtoupper(substr($userName, 0, 1));
                            $isMine = ($k['user_id'] == $currentUserId);
                        ?>
                        <div class="card-kuliner status-<?= esc($k['status']) ?>" data-status="<?= esc($k['status']) ?>" data-name="<?= strtolower(esc($k['nama'])) ?>">
                            <!-- Header Strip -->
                            <div class="card-header-strip">
                                <div class="card-header-user">
                                    <div class="card-header-avatar"><?= $userInitial ?></div>
                                    <div class="card-header-name" title="<?= esc($userName) ?>"><?= esc($userName) ?></div>
                                </div>
                                <div class="card-header-actions">
                                    <!-- Tombol Edit & Lihat disembunyikan sesuai permintaan -->
                                </div>
                            </div>

                            <a href="<?= base_url('kuliner/detail/' . $k['id']) ?>" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; flex: 1;">
                                <!-- Photo Grid -->
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

                            <!-- Name Strip -->
                            <div class="card-name-strip">
                                <?= esc($k['nama']) ?>
                            </div>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <?php if (empty($allKuliner)): ?>
                    <div class="kl-empty">
                        <div class="kl-empty-icon">🍽️</div>
                        <h3>Belum ada data kuliner</h3>
                        <p>Jadilah yang pertama merekomendasikan tempat makan favorit Anda!</p>
                        <a href="<?= base_url('kuliner/create') ?>" class="kl-btn kl-btn-primary">➕ Tambah Tempat</a>
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
