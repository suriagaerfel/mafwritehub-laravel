<?php require '../../private/initialize.php'; ?>


<?php $pageName = "Change Password"; ?>

<?php  $userIdReset = isset($_GET['userid']) ? htmlspecialchars($_GET['userid']) : "" ; ?>
<?php  $tokenReset = isset($_GET['token']) ? htmlspecialchars($_GET['token']) : "" ; ?>

<?php 

$sqlValidate = "SELECT * FROM users WHERE id= '$userIdReset'";
$sqlValidateResult = mysqli_query($conn,$sqlValidate);
$validated = $sqlValidateResult->fetch_assoc();

if ($validated) {
    $expiration = strtotime($validated['reset_token_expiration']);
    $tokenHash = $validated['reset_token_hash'];

    if ($tokenReset==$tokenHash) {
                        
        if ($expiration-time()>0) {
            $_SESSION ['reset-now'] = "yes";

        } else {
            $_SESSION ['link-expired'] = "yes";
            header('Location:'.$website);
        }
        
    } else {
        $_SESSION['its-not-you'] = "yes";
        header('Location:'.$website);     
    }

} else {
    $_SESSION['account-not-found'] = "yes";
    header('Location:'.$website);
    
}

?>

<?php $newPwd = isset($_SESSION ['newPassword'] ) ? $_SESSION ['newPassword'] : ""; ?>
<?php $newPwdRetype = isset($_SESSION ['newPasswordRetype'] ) ? $_SESSION ['newPasswordRetype'] : ""; ?>

<?php require (SECTION_PATH.'/head.php'); ?>

<?php require (SECTION_PATH.'/header.php'); ?>



<div id="change-password-page" class="page form-page" style="display: flex; flex-direction:column;padding:20px; margin:0px;  background-image: url(<?php echo $website.'/assets/images/home-image.jpg'?>);">
    
    <div class="form-page-content-container" >

            <?php if (isset ($_SESSION['reset-now'])) { ?>

            <form id="change-password-form" class="form" method="post" action="../../private/includes/processing/change-password-processing.php">
            
                <?php  //Notify if new passwords don't match.
                if (isset($_SESSION['passwords-dont-match'])) {
                    echo "<div class='alert alert-danger'>New passwords don't match.</div>";
                    unset ($_SESSION ['passwords-dont-match']);
                } ?>

                <?php  //Notify if new passwords don't match.
                if (isset($_SESSION['empty-passwords'])) {
                    echo "<div class='alert alert-danger'>Please provide new passwords.</div>";
                    unset ($_SESSION ['empty-passwords']);
                } ?>
             
                <h5 class="form-title">Change Password</h5>
                <input type="text" name="hiddenRegistrantId" value="<?php echo $userIdReset;?>" hidden>
                <input type="text" name="hiddenToken" value="<?php echo $token; ?>" hidden>
                <input class="registrantInputs" type="password" name="newPassword" placeholder="New Password" value="<?php echo $newPwd?>">
                <input class="registrantInputs" type="password" name="newPasswordRetype" placeholder="Retype New Password" value="<?php echo $newPwdRetype?>">
            
                <button class="registrantSubmitButtons" type="submit" name="changePasswordBtn">Change Password</button> 

            </form>

            <?php } ?>

        
            
    </div>

    <?php require (SECTION_PATH.'/footer-links.php');?>


</div>

<?php require (SECTION_PATH.'/footer-scripts.php');?>
</body>

</html>