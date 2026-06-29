<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Tambah kuliner baru — Kuliner">
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
                    <h4 style="font-size: 14px; font-weight: 600; color: var(--kl-dark); margin: 0 0 2px 0;"><?= esc(session()->get('nama')) ?></h4>
                    <span class="kl-badge kl-badge-kategori" style="font-size: 10px;"><?= ucfirst(session()->get('role')) ?></span>
                </div>
            </div>
            <nav class="kl-sidebar-nav">
                <?php if (session()->get('role') === 'admin'): ?>
                    <a href="<?= base_url('admin/dashboard') ?>">📊 Dashboard</a>
                    <a href="<?= base_url('kuliner') ?>">🍽️ Kelola Kuliner</a>
                    <a href="<?= base_url('kategori') ?>">🏷️ Kategori</a>
                    <a href="<?= base_url('tag') ?>">🔖 Tag</a>
                    <a href="<?= base_url('review') ?>">⭐ Kelola Ulasan</a>
                <?php else: ?>
                    <a href="<?= base_url('dashboard') ?>">🏠 Dashboard</a>
                    <a href="<?= base_url('kuliner') ?>">🍽️ Kuliner Saya</a>
                    <a href="<?= base_url('kuliner/create') ?>" class="active">➕ Tambah Kuliner</a>
                    <a href="<?= base_url('review') ?>">⭐ Ulasan Saya</a>
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
            <div class="kl-main-content" style="max-width: 780px;">
                <!-- Breadcrumb -->
                <div class="kl-breadcrumb">
                    <a href="<?= base_url('dashboard') ?>">Dashboard</a>
                    <span class="kl-breadcrumb-sep">›</span>
                    <a href="<?= base_url('kuliner') ?>">Kuliner</a>
                    <span class="kl-breadcrumb-sep">›</span>
                    <span>Tambah Baru</span>
                </div>

                <!-- Page Header -->
                <div class="kl-page-header">
                    <h1>Tambah Tempat Kuliner Baru</h1>
                    <p>Lengkapi informasi di bawah untuk menambahkan tempat makan baru.</p>
                </div>

                <!-- Error Alert -->
                <?php if (session()->has('errors')): ?>
                    <div class="kl-alert kl-alert-danger">
                        <span>⚠️</span>
                        <div style="flex: 1;">
                            <strong>Silakan perbaiki kesalahan berikut:</strong>
                            <ul style="margin: 8px 0 0 16px; padding: 0;">
                                <?php foreach (session('errors') as $error): ?>
                                    <li style="margin-bottom: 4px;"><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Form Card -->
                <form action="<?= base_url('kuliner/store') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <!-- Section: Informasi Dasar -->
                    <div class="kl-card" style="margin-bottom: 20px;">
                        <div style="padding: 20px 24px; border-bottom: 1px solid var(--kl-border);">
                            <h3 style="font-size: 16px; font-weight: 600;">📝 Informasi Dasar</h3>
                        </div>
                        <div class="kl-card-body" style="padding: 24px;">
                            <div class="kl-form-group">
                                <label class="kl-form-label">Nama Tempat Kuliner <span class="kl-required">*</span></label>
                                <input type="text" name="nama" class="kl-input" value="<?= old('nama') ?>" placeholder="Contoh: Warung Nasi Kuning Bu Ani">
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                                <div class="kl-form-group">
                                    <label class="kl-form-label">Kategori <span class="kl-required">*</span></label>
                                    <select name="id_kategori" class="kl-select">
                                        <option value="">— Pilih Kategori —</option>
                                        <?php foreach ($kategori as $k): ?>
                                            <option value="<?= $k['id'] ?>" <?= old('id_kategori') == $k['id'] ? 'selected' : '' ?>>
                                                <?= esc($k['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="kl-form-group">
                                    <label class="kl-form-label">Alamat Lengkap <span class="kl-required">*</span></label>
                                    <input type="text" name="alamat" class="kl-input" value="<?= old('alamat') ?>" placeholder="Jl. Nakula No. 1, Semarang">
                                </div>
                            </div>

                            <div class="kl-form-group">
                                <label class="kl-form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="kl-textarea" rows="4" placeholder="Ceritakan suasana, menu andalan, harga kisaran..."><?= old('deskripsi') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Foto -->
                    <div class="kl-card" style="margin-bottom: 20px;">
                        <div style="padding: 20px 24px; border-bottom: 1px solid var(--kl-border);">
                            <h3 style="font-size: 16px; font-weight: 600;">📷 Foto</h3>
                        </div>
                        <div class="kl-card-body" style="padding: 24px;">
                            <div class="kl-form-group">
                                <label class="kl-form-label">Gambar Utama <span class="kl-required">*</span></label>
                                <img id="gambar-preview" style="max-width: 100%; max-height: 240px; border-radius: 8px; display: none; margin-bottom: 12px; box-shadow: var(--kl-shadow-card);">
                                <input type="file" id="gambar-input" name="gambar" class="kl-input" accept="image/jpg,image/jpeg,image/png" style="padding: 8px 12px;" onchange="previewImg(this, 'gambar-preview')">
                                <p class="kl-input-hint" style="margin-top: 4px; font-size: 12px; color: var(--kl-muted);">Wajib diisi. Max 2MB, format JPG/PNG</p>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                                <div class="kl-form-group">
                                    <label class="kl-form-label">Foto Tambahan 1 <span style="color: var(--kl-muted); font-weight: 400;">(Opsional)</span></label>
                                    <input type="file" name="foto2" class="kl-input" accept="image/jpg,image/jpeg,image/png" style="padding: 8px 12px;">
                                </div>
                                <div class="kl-form-group">
                                    <label class="kl-form-label">Foto Tambahan 2 <span style="color: var(--kl-muted); font-weight: 400;">(Opsional)</span></label>
                                    <input type="file" name="foto3" class="kl-input" accept="image/jpg,image/jpeg,image/png" style="padding: 8px 12px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Lokasi Koordinat (WEBSERVICE CLIENT) -->
                    <div class="kl-card" style="margin-bottom: 20px;">
                        <div style="padding: 20px 24px; border-bottom: 1px solid var(--kl-border);">
                            <h3 style="font-size: 16px; font-weight: 600;">📍 Lokasi Koordinat <span style="font-weight: 400; font-size: 13px; color: var(--kl-muted);">(Webservice Client — Nominatim API)</span></h3>
                        </div>
                        <div class="kl-card-body" style="padding: 24px;">
                            <p style="font-size: 13px; color: var(--kl-muted); margin-bottom: 16px;">Isi alamat di atas terlebih dahulu, lalu klik tombol di bawah untuk mencari koordinat secara otomatis.</p>
                            <button type="button" id="btn-cari-koordinat" class="kl-btn kl-btn-outline" style="margin-bottom: 16px;" onclick="cariKoordinat(this)">
                                🔍 Cari Koordinat dari Alamat
                            </button>
                            <div id="koordinat-status" style="padding: 12px 16px; border-radius: 8px; font-size: 14px; font-weight: 500; margin-bottom: 16px; display: none;"></div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                                <div class="kl-form-group">
                                    <label class="kl-form-label">Latitude</label>
                                    <input type="text" name="lat" id="lat" class="kl-input" placeholder="Otomatis terisi" value="<?= old('lat') ?>">
                                </div>
                                <div class="kl-form-group">
                                    <label class="kl-form-label">Longitude</label>
                                    <input type="text" name="lng" id="lng" class="kl-input" placeholder="Otomatis terisi" value="<?= old('lng') ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Section: Tag -->
                    <div class="kl-card" style="margin-bottom: 24px;">
                        <div style="padding: 20px 24px; border-bottom: 1px solid var(--kl-border);">
                            <h3 style="font-size: 16px; font-weight: 600;">🔖 Tag</h3>
                        </div>
                        <div class="kl-card-body" style="padding: 24px;">
                            <?php if (!empty($tags)): ?>
                                <div class="kl-checkbox-group">
                                    <?php foreach ($tags as $t): ?>
                                        <label class="kl-checkbox">
                                            <input type="checkbox" name="tags[]" value="<?= $t['id'] ?>" id="tag<?= $t['id'] ?>">
                                            <?= esc($t['nama']) ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="kl-text-muted" style="font-size: 14px;">Belum ada tag tersedia.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="kl-flex-between" style="margin-bottom: 20px;">
                        <a href="<?= base_url('kuliner') ?>" class="kl-btn kl-btn-outline">
                            ← Batalkan
                        </a>
                        <button type="submit" class="kl-btn kl-btn-primary kl-btn-lg">
                            Kirim untuk Direview →
                        </button>
                    </div>

                    <p class="kl-text-center kl-text-muted" style="font-size: 13px;">
                        ⏳ Pengajuan Anda akan ditinjau admin sebelum ditampilkan publik
                    </p>
                </form>
            </div>
        </main>
    </div>

    <script>
        function previewImg(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '';
                preview.style.display = 'none';
            }
        }

        // ══════════════════════════════════════════
        // WEBSERVICE CLIENT: AJAX ke Nominatim via Controller
        // ══════════════════════════════════════════
        function cariKoordinat(btn) {
            console.log("Mencari koordinat...");
            // 1. Ambil teks alamat yang diketik user
            var alamat = document.querySelector('input[name="alamat"]').value;
            var statusEl = document.getElementById('koordinat-status');

            if (!alamat) {
                statusEl.style.display = 'block';
                statusEl.style.color = '#e74c3c';
                statusEl.innerHTML = '⚠️ Silakan isi alamat terlebih dahulu.';
                return;
            }

            // Tampilkan loading
            btn.disabled = true;
            btn.textContent = '⏳ Mencari koordinat...';
            statusEl.style.display = 'block';
            statusEl.style.backgroundColor = '#f1f5f9';
            statusEl.style.color = 'var(--kl-muted)';
            statusEl.innerHTML = 'Menghubungi Nominatim API...';

            // 2. Kirim ke controller kita via AJAX (fetch)
            fetch('<?= base_url("kuliner/cariKoordinat") ?>?alamat=' + encodeURIComponent(alamat))
                .then(res => res.json())
                .then(data => {
                    if (data.length > 0) {
                        // 3. Isi input lat & lng dengan hasil dari Nominatim
                        document.getElementById('lat').value = data[0].lat;
                        document.getElementById('lng').value = data[0].lon;
                        statusEl.style.backgroundColor = '#ecfdf5';
                        statusEl.style.color = '#059669';
                        statusEl.innerHTML = '✅ Koordinat ditemukan: ' + data[0].display_name;
                    } else {
                        statusEl.style.backgroundColor = '#fef2f2';
                        statusEl.style.color = '#dc2626';
                        statusEl.innerHTML = '❌ Alamat tidak ditemukan di peta. Coba alamat yang lebih umum (misal: "Semarang") atau isi manual.';
                    }
                    btn.disabled = false;
                    btn.textContent = '🔍 Cari Koordinat dari Alamat';
                })
                .catch(err => {
                    console.error("Fetch error:", err);
                    statusEl.style.backgroundColor = '#fef2f2';
                    statusEl.style.color = '#dc2626';
                    statusEl.innerHTML = '❌ Gagal menghubungi server. Coba lagi atau isi manual.';
                    btn.disabled = false;
                    btn.textContent = '🔍 Cari Koordinat dari Alamat';
                });
        }
    </script>
</body>
</html>
