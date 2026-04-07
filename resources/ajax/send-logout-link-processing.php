<?php

require '../../initialize.php';
require '../../database.php';

require "mailer.php";
 


if (isset($_POST['logout_submit'])) {


$logoutUserId = htmlspecialchars($_POST ['logout_userid']);
$logoutEmailAddress = htmlspecialchars($_POST ['logout_email_address']);

$mail->addAddress($logoutEmailAddress);
$mail->Subject = "Logout Account";
$mail->Body = <<<END
    
    <p>Click <a href='$domain$privateFolder/includes/processing/logout-processing.php?userid=$logoutUserId'> here </a> to logout your account from other device.</p>
               
END;

    try {
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
        
    }


}