<?php
require '../../initialize.php';
require '../../database.php';

//Sanitized already
if(isset($_POST['changePasswordBtn'])) {
    

    $token=htmlspecialchars($_POST ['hiddenToken']);

    $userIdReset = htmlspecialchars($_POST ['hiddenRegistrantId']);

    $newPassword = htmlspecialchars($_POST['newPassword']);
    $newPasswordRetype = htmlspecialchars($_POST['newPasswordRetype']);

    $goBackURL='Location: ' . $website.'/change-password/?userid='.$userIdReset.'&token='.$token;

    $_SESSION ['newPassword'] =  $newPassword;
    $_SESSION ['newPasswordRetype'] = $newPasswordRetype;

    if (!empty($newPassword) && !empty($newPasswordRetype)) {
        if ($newPassword!==$newPasswordRetype) {

        $_SESSION ['passwords-dont-match'] = "yes";

        header ($goBackURL);


    } else {

    $pwdHash = password_hash($newPassword, PASSWORD_DEFAULT);
       
    $sqlUpdatePassword = "UPDATE users 
                    SET password = ?
                        WHERE id = $userIdReset";

     $stmt = mysqli_stmt_init($conn);
    $prepareStmt = mysqli_stmt_prepare($stmt, $sqlUpdatePassword);
    
    if ($prepareStmt) {
    mysqli_stmt_bind_param($stmt,"s", $pwdHash);

    mysqli_stmt_execute($stmt);

            $_SESSION ['password-changed'] = "yes";

            header('Location:'.$website);

        }


        }


    } else {

        $_SESSION ['empty-passwords'] = "yes";

        header ($goBackURL);
    }
    
    
    



    

}
