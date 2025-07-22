<?php
namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailSender
{
    private $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);

        // Cấu hình SMTP Gmail
        $this->mailer->isSMTP();
        $this->mailer->Host = 'smtp.gmail.com';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = 'minhvannghuyen@gmail.com';       // ✅ Thay bằng Gmail của bạn
        $this->mailer->Password = 'ckzyreceuffwdwuf';           // ✅ App Password 16 ký tự
        $this->mailer->SMTPSecure = 'tls';
        $this->mailer->Port = 587;

        $this->mailer->setFrom('minhvannghuyen@gmail.com', 'MusicofMinh');
        $this->mailer->isHTML(true);
    }

    public function sendTokenToEmail($toEmail, $token)
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($toEmail);
            
            $this->mailer->Subject = 'Mã xác nhận khôi phục mật khẩu';
            $this->mailer->Body    = "Mã xác nhận của bạn là: <strong>$token</strong>";

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            error_log("Lỗi gửi mail: " . $this->mailer->ErrorInfo);
            return false;
        }
    }
}
