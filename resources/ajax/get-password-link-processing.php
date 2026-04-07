<?php 


require '../../initialize.php';
require '../../database.php';

require "mailer.php";

if (isset($_POST["get_password_link_submit"])) {
      
        $credential = htmlspecialchars($_POST['password_reset_email_username']);

        if (empty($credential)) {
        
            $error = 'Please provide your credential.';
            $responses ['error'] = $error;

        } else {

            $sql = "SELECT * FROM users WHERE email_address = '$credential' or username = '$credential'";
            $result = mysqli_query($conn, $sql);
            $registrant = mysqli_fetch_array($result, MYSQLI_ASSOC);

            if ($registrant) {

            $receivingEmail = $registrant ['email_address'];
            $userID = $registrant ['id'];

            $token = bin2hex(random_bytes(16));

            $tokenHash = hash("sha256",$token);
        
            $tokenHashExpiration = date("Y-m-d H:i:s",time()+ 60 * 30);

            $sql = "UPDATE users 
                    SET reset_token_hash = ?,
                        reset_token_expiration = ?
                        WHERE username=? or email_address = ?";


            $stmt =$conn->prepare($sql);

            $stmt ->bind_param("ssss",$tokenHash,$tokenHashExpiration,$credential,$credential);

            $stmt-> execute();


            if ($conn->affected_rows) {

                $mail->addAddress($receivingEmail);
                $mail->Subject = "Password Reset";
                $mail->Body = <<<END


                <p>Click <a href='$domain$publicFolder/change-password?userid=$userID&token=$tokenHash'> here </a> to reset your password.\nThe link will expire after 30 minutes.</p>
               
                END;

                try {
                    $mail->send();


                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
                    header('Location:'.$website.'/get-password-reset-link/');
                }

            }
 
            $error = 'No error';
            $responses ['error'] = $error; 

            } else {
                    
                    $error = 'Credential not found.';
                    $responses ['error'] = $error; 
                
            }

        } 

        
        if ($responses) {
            
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        
        echo  $jsonResponses;
        } 

}
        
       
        
   


