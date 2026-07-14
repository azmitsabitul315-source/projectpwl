<?php

namespace App\Controllers;

use App\Models\DonasiModel;
use CodeIgniter\Controller;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class Donasi extends BaseController
{
    protected $donasiModel;

    public function __construct()
    {
        $this->donasiModel = new DonasiModel();
        
        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }

    public function index()
    {
        return view('v_donasi_index');
    }

    public function pay()
    {
        $nama_donatur = $this->request->getPost('nama_donatur');
        $email = $this->request->getPost('email');
        $nominal = $this->request->getPost('nominal');

        if ($nominal < 10000) {
            return redirect()->back()->with('error', 'Nominal donasi minimal Rp 10.000');
        }

        $order_id = 'DONASI-' . time();

        $dataDonasi = [
            'order_id' => $order_id,
            'nama_donatur' => $nama_donatur,
            'email' => $email,
            'nominal' => $nominal,
            'status_pembayaran' => 'pending'
        ];

        $this->donasiModel->insert($dataDonasi);

        $transaction_details = [
            'order_id' => $order_id,
            'gross_amount' => $nominal, 
        ];

        $customer_details = [
            'first_name' => $nama_donatur,
        ];

        $transaction = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($transaction);
            
            $data = [
                'snapToken' => $snapToken,
                'donasi' => $dataDonasi
            ];
            
            return view('v_donasi_pay', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function webhook()
    {
        try {
            $notif = new \Midtrans\Notification();

            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $order_id = $notif->order_id;
            $fraud = $notif->fraud_status;

            $donasi = $this->donasiModel->where('order_id', $order_id)->first();
            
            if ($donasi) {
                if ($transaction == 'capture') {
                    if ($type == 'credit_card') {
                        if ($fraud == 'challenge') {
                            $status = 'pending';
                        } else {
                            $status = 'settlement';
                        }
                    }
                } else if ($transaction == 'settlement') {
                    $status = 'settlement';
                } else if ($transaction == 'pending') {
                    $status = 'pending';
                } else if ($transaction == 'deny') {
                    $status = 'deny';
                } else if ($transaction == 'expire') {
                    $status = 'expire';
                } else if ($transaction == 'cancel') {
                    $status = 'cancel';
                }

                if (isset($status)) {
                    $this->donasiModel->update($donasi['id'], ['status_pembayaran' => $status]);
                    
                    // Send Email if status is settlement
                    if ($status === 'settlement' && !empty($donasi['email'])) {
                        $this->sendThankYouEmail($donasi['email'], $donasi['nama_donatur'], $donasi['nominal']);
                    }
                }
            }

            return $this->response->setStatusCode(200);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => $e->getMessage()]);
        }
    }

    public function finish()
    {
        $json = $this->request->getJSON();
        if (!$json || !isset($json->order_id)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }

        $order_id = $json->order_id;
        $donasi = $this->donasiModel->where('order_id', $order_id)->first();

        if ($donasi && $donasi['status_pembayaran'] !== 'settlement') {
            $this->donasiModel->update($donasi['id'], ['status_pembayaran' => 'settlement']);
            
            if (!empty($donasi['email'])) {
                $this->sendThankYouEmail($donasi['email'], $donasi['nama_donatur'], $donasi['nominal']);
            }
            return $this->response->setJSON(['status' => 'success']);
        }
        
        return $this->response->setJSON(['status' => 'ignored']);
    }

    public function admin_index()
    {
        $donasi = $this->donasiModel->orderBy('created_at', 'DESC')->findAll();
        
        $totalPendapatan = $this->donasiModel
                                ->where('status_pembayaran', 'settlement')
                                ->selectSum('nominal')
                                ->first();

        $data = [
            'title' => 'Laporan Donasi',
            'donasi' => $donasi,
            'total_pendapatan' => $totalPendapatan['nominal'] ?? 0
        ];

        return view('v_donasi_admin', $data);
    }

    private function sendThankYouEmail($to, $name, $nominal)
    {
        $mail = new PHPMailer(true);
        try {
            
            $mail->isSMTP();
            $mail->Host       = env('SMTP_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = env('SMTP_USER');
            $mail->Password   = env('SMTP_PASS');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = env('SMTP_PORT');

            //penerima
            $mail->setFrom(env('SMTP_USER'), 'Kuliner Admin');
            $mail->addAddress($to, $name);

            
            $mail->isHTML(true);
            $mail->Subject = 'Terima Kasih Atas Donasi Anda!';
            $mail->Body    = "
                <h3>Halo, $name!</h3>
                <p>Kami telah menerima donasi Anda sebesar <b>Rp " . number_format($nominal, 0, ',', '.') . "</b>.</p>
                <p>Terima kasih banyak atas dukungan Anda yang sangat berarti bagi pengembangan platform Kuliner kami.</p>
                <br>
                <p>Salam hangat,</p>
                <p><b>Tim Kuliner</b></p>
            ";

            $mail->send();
        } catch (PHPMailerException $e) {
            log_message('error', 'Mail could not be sent. Mailer Error: ' . $mail->ErrorInfo);
        }
    }
}
