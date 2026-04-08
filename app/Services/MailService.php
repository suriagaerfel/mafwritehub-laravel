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
            $mailer->Username = "mafwritehub@gmail.com";
            $mailer->Password = "dwyg oxcp dglv vwur";
            $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mailer->Port = 587;

            $mailer->setFrom('mafwritehub@gmail.com', 'Maf Write Hub');
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