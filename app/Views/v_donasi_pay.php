<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Donasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .pay-card {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            background-color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="pay-card text-center">
            <h3 class="mb-4">Menyiapkan Pembayaran...</h3>
            
            <div class="card bg-light mb-4 text-start">
                <div class="card-body">
                    <h5 class="card-title">Detail Donasi</h5>
                    <p class="card-text mb-1"><strong>Order ID:</strong> <?= esc($donasi['order_id']) ?></p>
                    <p class="card-text mb-1"><strong>Nama:</strong> <?= esc($donasi['nama_donatur']) ?></p>
                    <p class="card-text mb-1"><strong>Nominal:</strong> Rp <?= number_format($donasi['nominal'], 0, ',', '.') ?></p>
                </div>
            </div>

            <p class="text-muted mb-4">Jika pop-up pembayaran tidak muncul, silakan klik tombol di bawah ini.</p>
            <button id="pay-button" class="btn btn-primary btn-lg w-100">Lanjutkan Pembayaran</button>
            <a href="<?= base_url('donasi') ?>" class="btn btn-outline-secondary w-100 mt-3">Batalkan</a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Midtrans Snap.js -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= env('MIDTRANS_CLIENT_KEY') ?>"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            // SnapToken acquired from previous step
            snap.pay('<?= $snapToken ?>', {
                // Optional
                onSuccess: function(result){
                    // Tell backend that payment is successful
                    fetch("<?= base_url('donasi/finish') ?>", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-Requested-With": "XMLHttpRequest"
                        },
                        body: JSON.stringify({
                            order_id: result.order_id
                        })
                    }).then(res => res.json()).then(data => {
                        alert("Terima kasih! Donasi Anda berhasil.");
                        window.location.href = "<?= base_url('dashboard') ?>";
                    }).catch(err => {
                        alert("Terima kasih! Donasi Anda berhasil dicatat.");
                        window.location.href = "<?= base_url('dashboard') ?>";
                    });
                },
                // Optional
                onPending: function(result){
                    alert("Menunggu pembayaran donasi Anda.");
                    window.location.href = "<?= base_url('dashboard') ?>";
                },
                // Optional
                onError: function(result){
                    alert("Maaf, terjadi kesalahan pada pembayaran.");
                    window.location.href = "<?= base_url('donasi') ?>";
                }
            });
        };

        // Otomatis memicu klik pada tombol pay-button saat halaman selesai dimuat
        window.onload = function() {
            document.getElementById('pay-button').click();
        };
    </script>
</body>
</html>
