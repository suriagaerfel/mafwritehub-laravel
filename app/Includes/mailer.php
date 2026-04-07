<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require "../../vendor/autoload.php";




$mailer = new PHPMailer(true);
// $mail->SMTPDebug=SMTP::DEBUG_SERVER;
$mailer->isSMTP();
$mailer->SMTPAuth = true;

$mailer->Host="smtp.gmail.com";
$mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mailer->Port=587;
$mailer->Username = "eskquip@gmail.com";
$mailer->Password = "aefe osht kypq tyuv";
$mailer->setFrom('eskquip@gmail.com', 'EskQuip');

$mailer->isHTML(true);

return $mailer;
