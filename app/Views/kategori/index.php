<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Kelola Kategori — Kuliner Admin">
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
                <a href="<?= base_url('kategori') ?>" class="active">🏷️ Kategori</a>
                <a href="<?= base_url('tag') ?>">🔖 Tag</a>
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
            <div class="kl-main-content" style="max-width: 800px;">
                <!-- Breadcrumb -->
                <div class="kl-breadcrumb">
                    <a href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
                    <span class="kl-breadcrumb-sep">›</span>
                    <span>Kelola Kategori</span>
                </div>

                <!-- Page Header -->
                <div class="kl-page-header kl-flex-between">
                    <div>
                        <h1>🏷️ Kelola Kategori</h1>
                        <p>Atur kategori kuliner di sistem.</p>
                    </div>
                    <a href="<?= base_url('kategori/create') ?>" class="kl-btn kl-btn-primary">
                        ➕ Tambah Kategori
                    </a>
                </div>

                <!-- Flash -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="kl-alert kl-alert-success" id="flash-alert">
                        <span>✅</span>
                        <div style="flex: 1;"><?= session()->getFlashdata('success') ?></div>
                        <button class="kl-alert-close" onclick="this.parentElement.remove()">×</button>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="kl-alert kl-alert-danger">
                        <span>❌</span>
                        <div style="flex: 1;"><?= session()->getFlashdata('error') ?></div>
                        <button class="kl-alert-close" onclick="this.parentElement.remove()">×</button>
                    </div>
                <?php endif; ?>

                <!-- Table -->
                <?php $items = $kategoris ?? $kategori ?? []; ?>
                <?php if (!empty($items)): ?>
                    <div class="kl-table-wrap">
                        <table class="kl-table">
                            <thead>
                                <tr>
                                    <th style="width: 6%; text-align: center;">#</th>
                                    <th>Nama Kategori</th>
                                    <th style="width: 20%; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; foreach ($items as $item): ?>
                                <tr>
                                    <td style="text-align: center; color: var(--kl-muted);"><?= $i++ ?></td>
                                    <td>
                                        <span style="font-weight: 600;"><?= esc($item['nama']) ?></span>
                                    </td>
                                    <td>
                                        <div class="kl-table-actions" style="justify-content: center;">
                                            <a href="<?= base_url('kategori/edit/' . $item['id']) ?>" class="kl-btn kl-btn-outline kl-btn-sm" title="Edit">
                                                ✏️ Edit
                                            </a>
                                            <a href="<?= base_url('kategori/delete/' . $item['id']) ?>" class="kl-btn kl-btn-danger-outline kl-btn-sm" 
                                               onclick="return confirm('Yakin hapus kategori ini? Kuliner yang terhubung tidak akan terhapus.')" title="Hapus">
                                                🗑️ Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="kl-card">
                        <div class="kl-empty">
                            <div class="kl-empty-icon">🏷️</div>
                            <h3>Belum ada kategori</h3>
                            <p>Tambah kategori pertama untuk mulai mengelola kuliner.</p>
                            <a href="<?= base_url('kategori/create') ?>" class="kl-btn kl-btn-primary">➕ Tambah Sekarang</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        setTimeout(() => {
            const flash = document.getElementById('flash-alert');
            if (flash) { flash.style.opacity = '0'; setTimeout(() => flash?.remove(), 300); }
        }, 4000);
    </script>
</body>
</html>
