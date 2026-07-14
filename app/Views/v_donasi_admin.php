<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Laporan Donasi — Kuliner Admin">
    <title><?= esc($title) ?> — Kuliner</title>
    <link rel="stylesheet" href="<?= base_url('kuliner.css') ?>">
    <style>
        .kl-table-wrapper {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-top: 20px;
        }
        .kl-table {
            width: 100%;
            border-collapse: collapse;
        }
        .kl-table th, .kl-table td {
            padding: 16px 20px;
            text-align: left;
            border-bottom: 1px solid var(--kl-border);
        }
        .kl-table th {
            background: #f8fafc;
            font-weight: 600;
            color: var(--kl-muted);
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .kl-table td {
            font-size: 14px;
            color: var(--kl-dark);
        }
        .kl-table tr:last-child td {
            border-bottom: none;
        }
        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        .status-settlement { background: var(--kl-success-light); color: var(--kl-success); }
        .status-pending { background: var(--kl-warning-light); color: var(--kl-warning); }
        .status-cancel, .status-expire, .status-deny { background: #fee2e2; color: var(--kl-danger); }
    </style>
</head>
<body>
    <div class="kl-layout">
        <!-- Sidebar Admin -->
        <aside class="kl-sidebar">
            <div class="kl-sidebar-header">
                <a href="<?= base_url('admin/dashboard') ?>" class="kl-logo" style="font-size: 1.35rem; text-decoration: none;">
                     <span>Kul</span>inerr
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
                <a href="<?= base_url('admin/dashboard') ?>"> Dashboard</a>
                <a href="<?= base_url('kuliner') ?>"> Kelola Kuliner</a>
                <a href="<?= base_url('kategori') ?>"> Kategori</a>
                <a href="<?= base_url('tag') ?>"> Tag</a>
                <a href="<?= base_url('review') ?>"> Kelola Ulasan</a>
                <a href="<?= base_url('admin/donasi') ?>" class="active"> Laporan Donasi</a>
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
                    <span>Laporan Donasi</span>
                </div>

                <!-- Page Header -->
                <div class="kl-page-header kl-flex-between">
                    <div>
                        <h1>Laporan Donasi</h1>
                        <p>Pantau semua donasi yang masuk untuk pengembangan sistem.</p>
                    </div>
                </div>

                <!-- Total Stats -->
                <div class="kl-stats-grid" style="grid-template-columns: repeat(1, 1fr); margin-bottom: 24px;">
                    <div class="kl-stat-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none;">
                        <div class="kl-stat-icon" style="background: rgba(255,255,255,0.2); color: white;">💰</div>
                        <div class="kl-stat-number" style="color: white;">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></div>
                        <div class="kl-stat-label" style="color: rgba(255,255,255,0.9);">Total Donasi Terkumpul</div>
                    </div>
                </div>

                <!-- Table -->
                <div class="kl-table-wrapper">
                    <table class="kl-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Order ID</th>
                                <th>Nama & Email</th>
                                <th>Nominal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($donasi)): ?>
                                <tr>
                                    <td colspan="5" class="text-center" style="text-align: center; padding: 40px; color: var(--kl-muted);">
                                        Belum ada riwayat donasi.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($donasi as $d): ?>
                                    <tr>
                                        <td><?= date('d M Y H:i', strtotime($d['created_at'])) ?></td>
                                        <td><span style="font-family: monospace; font-size: 13px; color: var(--kl-muted);"><?= esc($d['order_id']) ?></span></td>
                                        <td>
                                            <strong><?= esc($d['nama_donatur']) ?></strong><br>
                                            <span style="font-size: 12px; color: var(--kl-muted);"><?= esc($d['email'] ?? '-') ?></span>
                                        </td>
                                        <td>Rp <?= number_format($d['nominal'], 0, ',', '.') ?></td>
                                        <td>
                                            <?php
                                                $status = strtolower($d['status_pembayaran']);
                                                $badgeClass = 'status-pending';
                                                $statusText = 'Tertunda';
                                                
                                                if($status == 'settlement' || $status == 'capture') {
                                                    $badgeClass = 'status-settlement';
                                                    $statusText = 'Berhasil';
                                                } else if(in_array($status, ['cancel', 'expire', 'deny'])) {
                                                    $badgeClass = 'status-cancel';
                                                    $statusText = 'Dibatalkan';
                                                }
                                            ?>
                                            <span class="badge-status <?= $badgeClass ?>"><?= $statusText ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </main>
    </div>
</body>
</html>
