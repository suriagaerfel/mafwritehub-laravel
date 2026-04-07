<?php 

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    public function send($email, $subject, $body)
    {
        $mailer = new PHPMailer(true);

        try {
            $mailer->isSMTP();
            $mailer->Host = "smtp.gmail.com";
            $mailer->SMTPAuth = true;
            $mailer->Username = "eskquip@gmail.com";
            $mailer->Password = "aefe osht kypq tyuv";
            $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mailer->Port = 587;

            $mailer->setFrom('eskquip@gmail.com', 'EskQuip');
            $mailer->addAddress($email);

            $mailer->isHTML(true);
            $mailer->Subject = $subject;
            $mailer->Body = $body;

            $mailer->send();

            return true;

        } catch (Exception $e) {
            return $mailer->ErrorInfo;
        }
    }
}


?>