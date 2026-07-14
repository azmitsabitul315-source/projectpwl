<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> — Kuliner</title>
    <link rel="stylesheet" href="<?= base_url('kuliner.css') ?>">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
    <style>
        /* Detail specific styles not in main css */
        .kl-review-card {
            border: 1px solid var(--kl-border);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
            background: #fff;
        }
        .kl-review-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
        }
        .kl-review-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #E5E5E5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--kl-dark);
        }
        .kl-rating-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 4px;
            font-size: 13px;
        }
        .kl-rating-bar-inner {
            flex: 1;
            height: 8px;
            background: #E5E5E5;
            border-radius: 4px;
            overflow: hidden;
        }
        .kl-rating-bar-fill {
            height: 100%;
            background: var(--kl-warning);
        }
        .kl-star-input {
            display: flex;
            gap: 4px;
            font-size: 28px;
            color: #D4D4D4;
            cursor: pointer;
            margin-bottom: 16px;
        }
        .kl-star-input span:hover,
        .kl-star-input span.active {
            color: var(--kl-warning);
        }
    </style>
</head>
<body>
    <div class="kl-layout">
        <!-- Main Content (No Sidebar on Public Detail Page, or we can use kl-main fullwidth) -->
        <main class="kl-main" style="margin-left: 0; width: 100%; background: var(--kl-bg); min-height: 100vh;">
            <!-- Top Navbar -->
            <div style="background: var(--kl-surface); border-bottom: 1px solid var(--kl-border); padding: 16px 32px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100;">
                <a href="<?= base_url() ?>" class="kl-logo" style="font-size: 1.35rem; text-decoration: none;">
                     <span>Kul</span>inerr
                </a>
                <div>
                    <?php if (session()->get('logged_in')): ?>
                        <a href="<?= session()->get('role') === 'admin' ? base_url('admin/dashboard') : base_url('dashboard') ?>" class="kl-btn kl-btn-outline" style="margin-right: 8px;">Dashboard</a>
                        <a href="<?= base_url('logout') ?>" class="kl-logout-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    <span>Logout</span>
                </a>
                    <?php else: ?>
                        <a href="<?= base_url('login') ?>" class="kl-btn kl-btn-primary">Masuk / Daftar</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="kl-main-content" style="max-width: 1200px; margin: 0 auto; padding: 32px;">
                <!-- Breadcrumb -->
                <div class="kl-breadcrumb">
                    <a href="<?= session()->get('logged_in') ? base_url('dashboard') : base_url() ?>">Beranda</a>
                    <span class="kl-breadcrumb-sep">›</span>
                    <a href="<?= base_url('kuliner') ?>">Jelajahi Kuliner</a>
                    <span class="kl-breadcrumb-sep">›</span>
                    <span><?= esc($kuliner['nama']) ?></span>
                </div>

                <div class="detail-layout">
                    <!-- Kolom Kiri -->
                    <div class="detail-content">
                        <!-- Galeri -->
                        <div class="detail-gallery">
                            <?php if (!empty($kuliner['gambar'])): ?>
                                <img src="<?= base_url('uploads/kuliner/' . $kuliner['gambar']) ?>" class="detail-main-img" id="main-gallery-img" alt="Foto Utama">
                            <?php else: ?>
                                <div class="detail-main-img" style="background: #E5E5E5; display: flex; align-items: center; justify-content: center; color: var(--kl-muted);">Foto belum tersedia</div>
                            <?php endif; ?>

                            <?php if (!empty($kuliner['foto2']) || !empty($kuliner['foto3'])): ?>
                            <div class="detail-thumbnails">
                                <?php if (!empty($kuliner['gambar'])): ?>
                                    <img src="<?= base_url('uploads/kuliner/' . $kuliner['gambar']) ?>" onclick="setMainImage(this.src)">
                                <?php endif; ?>
                                <?php if (!empty($kuliner['foto2'])): ?>
                                    <img src="<?= base_url('uploads/kuliner/' . $kuliner['foto2']) ?>" onclick="setMainImage(this.src)">
                                <?php endif; ?>
                                <?php if (!empty($kuliner['foto3'])): ?>
                                    <img src="<?= base_url('uploads/kuliner/' . $kuliner['foto3']) ?>" onclick="setMainImage(this.src)">
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Info Title -->
                        <h1 style="font-size: 28px; font-weight: 800; color: var(--kl-dark); margin-bottom: 12px;"><?= esc($kuliner['nama']) ?></h1>
                        
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px; flex-wrap: wrap;">
                            <span class="kl-badge kl-badge-kategori" style="font-size: 13px; padding: 6px 12px;"><?= esc($kuliner['kategori_nama']) ?></span>
                            
                            <?php 
                                $avg = floatval($rating['avg_rating'] ?? 0);
                                $total = intval($rating['total_reviews'] ?? 0);
                            ?>
                            <div style="display: flex; align-items: center; gap: 4px; font-weight: 600; color: var(--kl-dark);">
                                <span style="color: var(--kl-warning);">⭐</span>
                                <?= number_format($avg, 1) ?>
                                <span style="color: var(--kl-muted); font-weight: 400; margin-left: 4px;">(<?= $total ?> ulasan)</span>
                            </div>

                            <!-- Tags -->
                            <?php if (!empty($kuliner['tag_list'])): ?>
                                <?php foreach (explode(', ', $kuliner['tag_list']) as $tag): ?>
                                    <span class="kl-badge kl-badge-tag">#<?= esc($tag) ?></span>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <!-- Address -->
                        <div style="display: flex; gap: 8px; margin-bottom: 24px; color: var(--kl-muted); font-size: 15px; line-height: 1.5;">
                            <span>📍</span>
                            <span><?= esc($kuliner['alamat']) ?></span>
                        </div>

                        <!-- Desc -->
                        <div style="font-size: 15px; color: var(--kl-dark); line-height: 1.7; margin-bottom: 40px; white-space: pre-line;">
                            <?= esc($kuliner['deskripsi']) ?>
                        </div>

                        <hr style="border: none; border-top: 1px solid var(--kl-border); margin-bottom: 32px;">

                        <!-- Reviews Section -->
                        <div id="reviews-section">
                            <button id="btn-load-reviews" class="kl-btn kl-btn-outline" style="width: 100%; justify-content: center; font-size: 15px; padding: 14px;">
                                Lihat Rating & Ulasan
                            </button>

                            <!-- Review Content (Hidden Initially) -->
                            <div id="reviews-content" style="display: none; margin-top: 32px;">
                                <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 4px;">Rating & Ulasan — <?= esc($kuliner['nama']) ?></h3>
                                <p style="font-size: 13px; color: var(--kl-muted); margin-bottom: 24px;">Menampilkan ulasan khusus untuk tempat ini.</p>

                                <?php if ($total > 0): ?>
                                <!-- Review Summary Bar -->
                                <div style="display: flex; gap: 32px; align-items: center; margin-bottom: 32px; background: #FAFAF8; padding: 24px; border-radius: 12px;">
                                    <div style="text-align: center;">
                                        <div style="font-size: 48px; font-weight: 800; line-height: 1;"><?= number_format($avg, 1) ?></div>
                                        <div style="color: var(--kl-warning); font-size: 18px; margin: 4px 0;">⭐⭐⭐⭐⭐</div>
                                        <div style="font-size: 13px; color: var(--kl-muted);"><?= $total ?> ulasan</div>
                                    </div>
                                    <div style="flex: 1;" id="rating-bars">
                                        <!-- Will be injected by JS if needed, but we don't have the exact distribution in SQL right now, so we just show mock distribution or skip it. -->
                                        <div class="kl-rating-bar">
                                            <span style="width: 20px;">5★</span>
                                            <div class="kl-rating-bar-inner"><div class="kl-rating-bar-fill" style="width: 70%;"></div></div>
                                        </div>
                                        <div class="kl-rating-bar">
                                            <span style="width: 20px;">4★</span>
                                            <div class="kl-rating-bar-inner"><div class="kl-rating-bar-fill" style="width: 20%;"></div></div>
                                        </div>
                                        <div class="kl-rating-bar">
                                            <span style="width: 20px;">3★</span>
                                            <div class="kl-rating-bar-inner"><div class="kl-rating-bar-fill" style="width: 10%;"></div></div>
                                        </div>
                                        <div class="kl-rating-bar">
                                            <span style="width: 20px;">2★</span>
                                            <div class="kl-rating-bar-inner"><div class="kl-rating-bar-fill" style="width: 0%;"></div></div>
                                        </div>
                                        <div class="kl-rating-bar">
                                            <span style="width: 20px;">1★</span>
                                            <div class="kl-rating-bar-inner"><div class="kl-rating-bar-fill" style="width: 0%;"></div></div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Review List Container -->
                                <div id="review-list" style="margin-bottom: 32px;">
                                    <!-- AJAX renders here -->
                                </div>

                                <!-- Form Tulis Ulasan -->
                                <div id="form-ulasan-section" style="scroll-margin-top: 100px;">
                                    <h4 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;">Tulis Ulasan Anda</h4>
                                    
                                    <?php if (session()->get('logged_in')): ?>
                                        <form action="<?= base_url('review/store') ?>" method="post">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="kuliner_id" value="<?= $kuliner['id'] ?>">
                                            <input type="hidden" name="rating" id="rating-val" value="5" required>

                                            <div class="kl-star-input" id="star-selector">
                                                <span data-val="1" class="active">★</span>
                                                <span data-val="2" class="active">★</span>
                                                <span data-val="3" class="active">★</span>
                                                <span data-val="4" class="active">★</span>
                                                <span data-val="5" class="active">★</span>
                                            </div>

                                            <div class="kl-form-group">
                                                <textarea name="komentar" class="kl-textarea" placeholder="Bagaimana pengalaman Anda di sini? Ceritakan selengkapnya..." required></textarea>
                                            </div>
                                            
                                            <button type="submit" class="kl-btn kl-btn-primary">Kirim Ulasan</button>
                                        </form>
                                    <?php else: ?>
                                        <div style="background: var(--kl-primary-light); border: 1px solid rgba(232, 149, 109, 0.3); padding: 20px; border-radius: 12px; text-align: center;">
                                            <p style="margin-bottom: 12px; color: var(--kl-dark); font-weight: 500;">Login untuk menulis ulasan</p>
                                            <a href="<?= base_url('login') ?>" class="kl-btn kl-btn-primary kl-btn-sm">Login Sekarang</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Kolom Kanan (Sticky Sidebar) -->
                    <div class="detail-sidebar">
                        <div class="kl-card" style="padding: 24px;">
                            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;">Informasi</h3>
                            
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid var(--kl-border);">
                                <span style="color: var(--kl-muted); font-size: 14px;">Status Tempat</span>
                                <?php if ($kuliner['status'] === 'active'): ?>
                                    <span class="kl-status kl-status-active" style="padding: 4px 10px; font-size: 11px;"><span class="kl-status-dot"></span> Aktif</span>
                                <?php else: ?>
                                    <span class="kl-status kl-status-pending" style="padding: 4px 10px; font-size: 11px;"><span class="kl-status-dot"></span> <?= ucfirst($kuliner['status']) ?></span>
                                <?php endif; ?>
                            </div>

                            <!-- Peta Lokasi (Leaflet.js) -->
                            <div style="margin-bottom: 16px;">
                                <?php if (!empty($kuliner['lat']) && !empty($kuliner['lng'])): ?>
                                <div id="map" style="height: 250px; border-radius: 12px; overflow: hidden; border: 1px solid var(--kl-border);"></div>
                                <p style="font-size: 11px; color: var(--kl-muted); margin-top: 6px;">📍 <?= esc($kuliner['lat']) ?>, <?= esc($kuliner['lng']) ?></p>
                                <?php else: ?>
                                <div id="map-container">
                                    <div style="display:flex; height:100%; align-items:center; justify-content:center; color: var(--kl-muted); font-size: 13px;">
                                        Peta belum tersedia (Tidak ada koordinat)
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>

                            <button onclick="document.getElementById('form-ulasan-section').scrollIntoView({behavior: 'smooth'})" class="kl-btn kl-btn-primary" style="width: 100%; justify-content: center; margin-top: 8px;">
                                ✍️ Tulis Ulasan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

    <!-- Inisialisasi Peta Leaflet -->
    <?php if (!empty($kuliner['lat']) && !empty($kuliner['lng'])): ?>
    <script>
        var lat = <?= $kuliner['lat'] ?>;
        var lng = <?= $kuliner['lng'] ?>;

        // Buat peta, pusatkan di koordinat kuliner
        var map = L.map('map').setView([lat, lng], 16);

        // Tambahkan layer peta dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        // Tambahkan marker (pin) di lokasi kuliner
        L.marker([lat, lng]).addTo(map)
            .bindPopup('<?= esc($kuliner["nama"]) ?>')
            .openPopup();
    </script>
    <?php endif; ?>
    <script>
        function setMainImage(src) {
            document.getElementById('main-gallery-img').src = src;
        }

        // Star Rating Selection Logic
        const stars = document.querySelectorAll('#star-selector span');
        const ratingInput = document.getElementById('rating-val');
        
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const val = this.dataset.val;
                ratingInput.value = val;
                stars.forEach(s => {
                    if (s.dataset.val <= val) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
            });
        });

        // Load Reviews AJAX
        document.getElementById('btn-load-reviews').addEventListener('click', function() {
            this.textContent = 'Memuat ulasan...';
            this.style.opacity = '0.7';

            fetch('<?= base_url("kuliner/{$kuliner['id']}/reviews") ?>')
                .then(res => res.json())
                .then(data => {
                    this.style.display = 'none';
                    document.getElementById('reviews-content').style.display = 'block';
                    
                    const listContainer = document.getElementById('review-list');
                    if (data.length === 0) {
                        listContainer.innerHTML = `
                            <div class="kl-empty" style="padding: 40px 20px; background: #FAFAF8; border-radius: 12px;">
                                <div class="kl-empty-icon" style="font-size: 32px; margin-bottom: 8px;">💭</div>
                                <h3>Belum ada ulasan</h3>
                                <p>Jadilah yang pertama menulis ulasan untuk tempat ini!</p>
                            </div>
                        `;
                        return;
                    }

                    let html = '';
                    data.forEach(rev => {
                        let starsHtml = '';
                        for(let i=0; i<5; i++) {
                            starsHtml += `<span style="color: ${i < rev.rating ? 'var(--kl-warning)' : '#E5E5E5'}">★</span>`;
                        }

                        // Format date simple
                        let dateObj = new Date(rev.created_at);
                        let dateStr = dateObj.toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric' });
                        let initial = rev.user_name ? rev.user_name.charAt(0).toUpperCase() : 'U';

                        html += `
                            <div class="kl-review-card">
                                <div class="kl-review-header">
                                    <div class="kl-review-avatar">${initial}</div>
                                    <div>
                                        <div style="font-weight: 600; font-size: 14px;">${rev.user_name || 'User'}</div>
                                        <div style="font-size: 12px; color: var(--kl-muted);">${dateStr}</div>
                                    </div>
                                    <div style="margin-left: auto; letter-spacing: 2px;">
                                        ${starsHtml}
                                    </div>
                                </div>
                                <div style="font-size: 14px; line-height: 1.6; color: var(--kl-dark);">
                                    ${rev.komentar}
                                </div>
                            </div>
                        `;
                    });
                    listContainer.innerHTML = html;

                    // scroll to it
                    document.getElementById('reviews-content').scrollIntoView({behavior: 'smooth', block: 'start'});
                })
                .catch(err => {
                    console.error(err);
                    this.textContent = 'Gagal memuat ulasan. Coba lagi.';
                    this.style.opacity = '1';
                });
        });
    </script>
</body>
</html>
