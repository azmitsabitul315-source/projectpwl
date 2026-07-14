<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Rating dan Review — Kuliner">
    <title><?= esc($title) ?> — Kuliner</title>
    <link rel="stylesheet" href="<?= base_url('kuliner.css') ?>">
</head>
<body>
    <div class="kl-layout">
        <!-- Sidebar -->
        <aside class="kl-sidebar">
            <div class="kl-sidebar-header">
                <a href="<?= base_url('dashboard') ?>" class="kl-logo" style="font-size: 1.35rem; text-decoration: none;">
                     <span>Kul</span>inerr
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
                    <h4 style="font-size: 14px; font-weight: 600; color: var(--kl-dark); margin: 0 0 2px 0;"><?= esc(session()->get('nama')) ?></h4>
                    <span class="kl-badge kl-badge-kategori" style="font-size: 10px;"><?= ucfirst(session()->get('role')) ?></span>
                </div>
            </div>
            <nav class="kl-sidebar-nav">
                <?php if (session()->get('role') === 'admin'): ?>
                    <a href="<?= base_url('admin/dashboard') ?>"> Dashboard</a>
                    <a href="<?= base_url('kuliner') ?>"> Kelola Kuliner</a>
                    <a href="<?= base_url('kategori') ?>"> Kategori</a>
                    <a href="<?= base_url('tag') ?>"> Tag</a>
                    <a href="<?= base_url('review') ?>" class="active"> Kelola Ulasan</a>
                <?php else: ?>
                    <a href="<?= base_url('dashboard') ?>"> Dashboard</a>
                    <a href="<?= base_url('kuliner') ?>"> Kuliner Saya</a>
                    <a href="<?= base_url('kuliner/create') ?>">+ Tambah Kuliner</a>
                    <a href="<?= base_url('review') ?>" class="active"> Ulasan Saya</a>
                <?php endif; ?>
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
            <div class="kl-main-content" style="max-width: 1100px;">
                <!-- Breadcrumb -->
                <div class="kl-breadcrumb">
                    <a href="<?= base_url('dashboard') ?>">Dashboard</a>
                    <span class="kl-breadcrumb-sep">›</span>
                    <span>Rating & Ulasan</span>
                </div>

                <!-- Page Header -->
                <div class="kl-page-header">
                    <h1>⭐ Rating dan Ulasan</h1>
                    <p>Kelola ulasan dan kirim review baru untuk kuliner aktif.</p>
                </div>

                <!-- Flash Messages -->
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
                <?php if ($errors = session()->getFlashdata('errors')): ?>
                    <div class="kl-alert kl-alert-danger">
                        <span>⚠️</span>
                        <div style="flex: 1;">
                            <strong>Silakan perbaiki kesalahan berikut:</strong>
                            <ul style="margin: 8px 0 0 16px; padding: 0;">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Content Grid -->
                <div style="display: grid; grid-template-columns: 340px 1fr; gap: 24px; align-items: start;">

                    <!-- Left Column: Stats + Form -->
                    <div>
                        <!-- Stats Card -->
                        <div class="kl-card" style="padding: 24px; margin-bottom: 20px;">
                            <div class="kl-flex-between kl-mb-md">
                                <div>
                                    <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 4px;">Rata-rata Rating</h3>
                                    <p class="kl-text-muted" style="font-size: 13px;">Berdasarkan semua ulasan.</p>
                                </div>
                                <span class="kl-badge kl-badge-kategori">
                                    <?= $reviewCount ?> ulasan
                                </span>
                            </div>
                            <div style="font-family: var(--kl-font-display); font-size: 48px; font-weight: 800; color: var(--kl-dark); margin-bottom: 12px;">
                                <?= $ratingAverage ?? '—' ?>
                            </div>
                            <!-- Star bar -->
                            <div style="background: #F5F5F4; border-radius: 100px; height: 8px; overflow: hidden;">
                                <div style="background: linear-gradient(90deg, #FBBF24, var(--kl-primary)); height: 100%; border-radius: 100px; width: <?= $ratingAverage ? min(100, $ratingAverage * 20) : 0 ?>%; transition: width 0.5s ease;"></div>
                            </div>
                            <p class="kl-text-muted kl-mt-sm" style="font-size: 12px;">
                                <?= $reviewCount ? 'dari skala 1-5 bintang' : 'Belum ada ulasan untuk ditampilkan.' ?>
                            </p>
                        </div>

                        <!-- Submit Review Form -->
                        <div class="kl-card" style="padding: 24px;">
                            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 16px;">✍️ Kirim Ulasan</h3>
                            <form action="<?= base_url('review/store') ?>" method="post">
                                <?= csrf_field() ?>
                                <div class="kl-form-group">
                                    <label class="kl-form-label">Kuliner</label>
                                    <select name="kuliner_id" class="kl-select" required>
                                        <option value="">Pilih kuliner...</option>
                                        <?php foreach ($kulinerList as $kuliner): ?>
                                            <option value="<?= esc($kuliner['id']) ?>" <?= old('kuliner_id') == $kuliner['id'] ? 'selected' : '' ?>>
                                                <?= esc($kuliner['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="kl-form-group">
                                    <label class="kl-form-label">Rating</label>
                                    <div class="kl-star-input" id="star-input">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span data-value="<?= $i ?>" class="<?= old('rating') >= $i ? 'active' : '' ?>" onclick="setRating(<?= $i ?>)">★</span>
                                        <?php endfor; ?>
                                    </div>
                                    <input type="hidden" name="rating" id="rating-value" value="<?= old('rating', '') ?>" required>
                                </div>

                                <div class="kl-form-group">
                                    <label class="kl-form-label">Komentar</label>
                                    <textarea name="komentar" class="kl-textarea" rows="4" placeholder="Tulis ulasan Anda..." required><?= old('komentar') ?></textarea>
                                </div>

                                <button type="submit" class="kl-btn kl-btn-primary kl-btn-full">
                                    Kirim Ulasan →
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Right Column: Review List -->
                    <div>
                        <div class="kl-card">
                            <div style="padding: 20px 24px; border-bottom: 1px solid var(--kl-border);">
                                <div class="kl-flex-between">
                                    <div>
                                        <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 2px;">Daftar Ulasan</h3>
                                        <p class="kl-text-muted" style="font-size: 13px;">Semua ulasan terbaru ditampilkan di sini.</p>
                                    </div>
                                </div>
                            </div>

                            <div style="padding: 0;">
                                <?php if (!empty($reviews)): ?>
                                    <?php foreach ($reviews as $review): ?>
                                        <div style="padding: 20px 24px; border-bottom: 1px solid #F5F5F4; transition: background 0.15s ease;" onmouseenter="this.style.background='rgba(232,149,109,0.03)'" onmouseleave="this.style.background='transparent'">
                                            <div class="kl-flex-between" style="margin-bottom: 8px;">
                                                <div class="kl-flex-center kl-gap-sm">
                                                    <!-- Avatar -->
                                                    <div class="kl-avatar" style="width: 34px; height: 34px; font-size: 13px; background: var(--kl-secondary);">
                                                        <?= strtoupper(substr($review['user_name'] ?? 'U', 0, 1)) ?>
                                                    </div>
                                                    <div>
                                                        <h4 style="font-size: 14px; font-weight: 600; margin: 0;"><?= esc($review['user_name'] ?? 'Pengguna') ?></h4>
                                                        <span class="kl-text-muted" style="font-size: 12px;">
                                                            <?= esc($review['kuliner_nama'] ?? '-') ?>
                                                            <?php if (!empty($review['kategori_nama'])): ?>
                                                                · <span class="kl-text-primary"><?= esc($review['kategori_nama']) ?></span>
                                                            <?php endif; ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <!-- Rating Badge -->
                                                <span style="background: #FEF3C7; color: #B45309; font-family: var(--kl-font-mono); font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 100px;">
                                                    ⭐ <?= esc($review['rating']) ?>
                                                </span>
                                            </div>

                                            <!-- Comment -->
                                            <p style="font-size: 14px; color: var(--kl-dark); line-height: 1.6; margin: 8px 0 10px; padding-left: 46px;">
                                                <?= esc($review['komentar']) ?>
                                            </p>

                                            <!-- Footer -->
                                            <div class="kl-flex-between" style="padding-left: 46px;">
                                                <span class="kl-text-muted" style="font-size: 12px;">
                                                    <?= date('d M Y, H:i', strtotime($review['created_at'])) ?>
                                                </span>
                                                <?php if ($currentUserRole === 'admin' || $currentUserId === $review['user_id']): ?>
                                                    <a href="<?= base_url('review/delete/' . $review['id']) ?>" 
                                                       class="kl-btn kl-btn-ghost kl-btn-sm" 
                                                       style="color: var(--kl-danger); font-size: 12px;"
                                                       onclick="return confirm('Yakin ingin menghapus ulasan ini?')">
                                                        🗑️ Hapus
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="kl-empty" style="padding: 60px 24px;">
                                        <div class="kl-empty-icon">💬</div>
                                        <h3>Belum ada ulasan</h3>
                                        <p>Cari kuliner dan beri ulasanmu!</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
        // Star rating interaction
        function setRating(value) {
            document.getElementById('rating-value').value = value;
            document.querySelectorAll('#star-input span').forEach(star => {
                star.classList.toggle('active', parseInt(star.dataset.value) <= value);
            });
        }

        // Hover effect for stars
        document.querySelectorAll('#star-input span').forEach(star => {
            star.addEventListener('mouseenter', function() {
                const val = parseInt(this.dataset.value);
                document.querySelectorAll('#star-input span').forEach(s => {
                    s.style.color = parseInt(s.dataset.value) <= val ? '#FBBF24' : '#D4D4D4';
                });
            });
        });
        document.getElementById('star-input')?.addEventListener('mouseleave', function() {
            const current = parseInt(document.getElementById('rating-value').value) || 0;
            document.querySelectorAll('#star-input span').forEach(s => {
                s.style.color = parseInt(s.dataset.value) <= current ? '#FBBF24' : '#D4D4D4';
            });
        });

        // Auto-dismiss flash
        setTimeout(() => {
            const flash = document.getElementById('flash-alert');
            if (flash) { flash.style.opacity = '0'; setTimeout(() => flash?.remove(), 300); }
        }, 4000);
    </script>
</body>
</html>
