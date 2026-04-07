<?php


require '../../initialize.php';
require '../../database.php';

require "mailer.php";

$verifyingId = $_POST ['verifying_userid'];
$verifyingEmail = $_POST ['verifying_email_address'];

$sql = "SELECT * FROM users WHERE id = '$verifyingId'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_array($result, MYSQLI_ASSOC);

if ($user) {

$name = $user ['name'];
$type = $user ['type'];
$username = $user ['username'];

$token = bin2hex(random_bytes(16));

$tokenHash = hash("sha256",$token);

$tokenHashExpiration = date("Y-m-d H:i:s",time()+ 60 * 30);

$sql = "UPDATE users 
        SET reset_token_hash = ?,
            reset_token_expiration = ?
            WHERE email_address = ?";


$stmt =$conn->prepare($sql);

$stmt ->bind_param("sss",$tokenHash,$tokenHashExpiration,$verifyingEmail);

$stmt-> execute();

                
}




//Email Head
$mail->addAddress($verifyingEmail);







//Body if user is already added
if (isset($_POST['old_verify_submit'])) {   
    $mail->Subject = "Account Verification";
    $mail->Body = 
        
    <<<END
        <p>Click <a href="$domain$privateFolder/includes/processing/account-verification-processing.php?userid=$verifyingId"> here </a> to to verify your account.</p>             
    END;

}


//Body if user is newly added
    if (isset($_POST['new_verify_submit'])) {   

        $mail->Subject = $type." Registration";
        $mail->Body = 
            
        <<<END
            <p>Congratulations, $name!</p> 
            
            <p>You have been registered as $type to $websiteName.</p> 

            <p><a href="$domain$privateFolder/includes/processing/account-verification-processing.php?userid=$verifyingId">Verify</a> your account now to login with these details:</p>   
            
            <p>Email Address:\n$verifyingEmail</p>
            <p>Username:$username</p>
            <p>Password:$username</p>
            
            <p>However, if you wish to change your password, you can do so by clicking <a href='$domain$publicFolder/change-password?userid=$verifyingId&token=$tokenHash'> here </a>.\nThe link will expire after 30 minutes.</p>
        END;

    }



//Send the email
    try {
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
        
    }

    





