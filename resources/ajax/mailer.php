<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require "../../vendor/autoload.php";




$mail = new PHPMailer(true);
// $mail->SMTPDebug=SMTP::DEBUG_SERVER;
$mail->isSMTP();
$mail->SMTPAuth = true;

$mail->Host="smtp.gmail.com";
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port=587;
$mail->Username = "mafwritehub@gmail.com";
$mail->Password = "dwyg oxcp dglv vwur";
$mail->setFrom('mafwritehub@gmail.com', 'Maf Write Hub');

$mail->isHTML(true);

return $mail;
