<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Kelola data kuliner — Kuliner Admin">
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
                <a href="<?= base_url('kuliner') ?>" class="active">🍽️ Kelola Kuliner</a>
                <a href="<?= base_url('kategori') ?>">🏷️ Kategori</a>
                <a href="<?= base_url('tag') ?>">🔖 Tag</a>
                <a href="<?= base_url('review') ?>">⭐ Kelola Ulasan</a>
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
                    <a href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
                    <span class="kl-breadcrumb-sep">›</span>
                    <span>Kelola Kuliner</span>
                </div>

                <!-- Page Header -->
                <div class="kl-page-header kl-flex-between">
                    <div>
                        <h1>Kelola Kuliner</h1>
                        <p>Semua data tempat kuliner dalam sistem.</p>
                    </div>
                    <a href="<?= base_url('kuliner/create') ?>" class="kl-btn kl-btn-primary">
                        ➕ Tambah Data
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

                <!-- Filter Tabs -->
                <div class="kl-flex-between kl-mb-md" style="flex-wrap: wrap; gap: 12px; margin-bottom: 24px;">
                    <div class="kl-filter-tabs" id="filter-tabs">
                        <button class="kl-filter-tab active" data-filter="all">Semua</button>
                        <button class="kl-filter-tab" data-filter="active">Aktif</button>
                        <button class="kl-filter-tab" data-filter="pending">Menunggu</button>
                        <button class="kl-filter-tab" data-filter="rejected">Ditolak</button>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <input type="text" class="kl-input" placeholder="🔍 Cari nama kuliner..." style="width: 260px; padding: 8px 14px; font-size: 13px;" id="search-input">
                    </div>
                </div>

                <!-- Cards Grid -->
                <div class="kuliner-grid" id="kuliner-grid">
                    <?php if (!empty($kuliner)): ?>
                        <?php foreach ($kuliner as $k): 
                            $userName = $k['user_name'] ?? 'User';
                            $userInitial = strtoupper(substr($userName, 0, 1));
                        ?>
                        <div class="card-kuliner status-<?= esc($k['status']) ?>" data-status="<?= esc($k['status']) ?>" data-name="<?= strtolower(esc($k['nama'])) ?>">
                            <!-- Header Strip -->
                            <div class="card-header-strip">
                                <div class="card-header-user">
                                    <div class="card-header-avatar"><?= $userInitial ?></div>
                                    <div class="card-header-name" title="<?= esc($userName) ?>"><?= esc($userName) ?></div>
                                </div>
                                <div class="card-header-actions">
                                    <?php if ($k['status'] === 'pending'): ?>
                                        <a href="<?= base_url('kuliner/approve/' . $k['id']) ?>" class="kl-btn kl-btn-sm" style="background: var(--kl-success); color: white; padding: 4px 10px; font-size: 11px;">Setujui</a>
                                        <a href="#" class="kl-btn kl-btn-outline kl-btn-sm" style="border-color: var(--kl-danger); color: var(--kl-danger); padding: 4px 10px; font-size: 11px;" onclick="openRejectModal('<?= $k['id'] ?>', '<?= esc(addslashes($k['nama'])) ?>'); return false;">Tolak</a>
                                    <?php elseif ($k['status'] === 'active'): ?>
                                        <a href="<?= base_url('kuliner/edit/' . $k['id']) ?>" class="kl-btn kl-btn-outline kl-btn-sm" style="border-color: #2563EB; color: #2563EB; padding: 4px 10px; font-size: 11px;">Edit</a>
                                        <a href="<?= base_url('kuliner/delete/' . $k['id']) ?>" class="kl-btn kl-btn-sm" style="color: var(--kl-danger); background: transparent; padding: 4px; font-size: 14px;" onclick="return confirm('Yakin hapus?');">🗑️</a>
                                    <?php elseif ($k['status'] === 'rejected'): ?>
                                        <a href="<?= base_url('kuliner/edit/' . $k['id']) ?>" class="kl-btn kl-btn-outline kl-btn-sm" style="color: var(--kl-muted); border-color: var(--kl-border); padding: 4px 10px; font-size: 11px;">Review Lagi</a>
                                        <a href="<?= base_url('kuliner/delete/' . $k['id']) ?>" class="kl-btn kl-btn-sm" style="color: var(--kl-danger); background: transparent; padding: 4px; font-size: 14px;" onclick="return confirm('Yakin hapus?');">🗑️</a>
                                    <?php endif; ?>
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

                <?php if (empty($kuliner)): ?>
                    <div class="kl-empty" id="empty-state">
                        <div class="kl-empty-icon">📭</div>
                        <h3>Belum ada data kuliner</h3>
                        <p>Silakan tambah data baru untuk memulai.</p>
                        <a href="<?= base_url('kuliner/create') ?>" class="kl-btn kl-btn-primary">➕ Tambah Data</a>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Reject Modal -->
    <div class="kl-modal-overlay" id="reject-modal">
        <div class="kl-modal">
            <div class="kl-modal-header">
                <h3 style="font-size: 18px; font-weight: 700; color: var(--kl-dark); margin: 0;">Tolak Pengajuan</h3>
            </div>
            <form id="reject-form" action="" method="get">
                <div class="kl-modal-body">
                    <p style="font-size: 14px; margin-bottom: 12px;">Anda akan menolak pengajuan: <br><strong id="reject-kuliner-name">Nama Kuliner</strong></p>
                    <div class="kl-form-group" style="margin-bottom: 0;">
                        <textarea name="alasan_penolakan" class="kl-textarea" placeholder="Alasan penolakan untuk kontributor..." required style="min-height: 80px;"></textarea>
                        <p style="font-size: 12px; color: var(--kl-muted); margin-top: 6px;">Alasan ini akan dikirim ke kontributor (simulasi UI).</p>
                    </div>
                </div>
                <div class="kl-modal-footer">
                    <button type="button" class="kl-btn kl-btn-outline" onclick="closeRejectModal()">Batalkan</button>
                    <button type="submit" class="kl-btn kl-btn-primary" style="background: var(--kl-danger); border-color: var(--kl-danger);">Tolak</button>
                </div>
            </form>
        </div>
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

        // Search
        document.getElementById('search-input')?.addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#kuliner-grid .card-kuliner').forEach(card => {
                card.style.display = card.dataset.name?.includes(q) ? 'flex' : 'none';
            });
        });

        // Auto-dismiss flash
        setTimeout(() => {
            const flash = document.getElementById('flash-alert');
            if (flash) flash.style.opacity = '0';
            setTimeout(() => flash?.remove(), 300);
        }, 4000);

        // Reject Modal
        function openRejectModal(id, name) {
            document.getElementById('reject-kuliner-name').textContent = name;
            // Gunakan method GET sesuai existing route, biarkan controller yg handle status
            document.getElementById('reject-form').action = '<?= base_url('kuliner/reject') ?>/' + id;
            document.getElementById('reject-modal').classList.add('active');
        }

        function closeRejectModal() {
            document.getElementById('reject-modal').classList.remove('active');
        }

        document.getElementById('reject-modal').addEventListener('click', function(e) {
            if (e.target === this) closeRejectModal();
        });
    </script>
</body>
</html>
