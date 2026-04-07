<?php require resource_path('processing/initialize.php'); ?>
<?php require resource_path('processing/registrant-records.php'); ?>
<?php require resource_path('processing/u_registrant-records.php'); ?>



<?php 
//Create account
if (isset($_POST["create_account_submit"])) {

$type = htmlspecialchars($_POST["create_type"]);

if ($type=="Personal") {
$firstName = htmlentities($_POST["create_personal_first_name"]);
$lastName = htmlspecialchars($_POST["create_personal_last_name"]);
$accountName = $firstName." ".$lastName;
$birthdate = htmlspecialchars($_POST["create_personal_birthdate"]);
$gender = htmlspecialchars($_POST["create_personal_gender"]);
$basicAccount = 'Basic User';
}

if ($type=="School") {
$firstName = "na";
$lastName = "na";
$accountName = htmlspecialchars($_POST ['create_school_name']);
$birthdate = $currentTime;
$gender = "na";
$basicAccount = htmlspecialchars($_POST["create_school_basic_account"]);
}





$emailAddress = htmlspecialchars($_POST["create_email_address"]);
$username = htmlspecialchars($_POST["create_username"]);
$pwd = htmlspecialchars($_POST["create_password"]);
$pwdRetype = htmlspecialchars($_POST["create_password_retype"]);



$userCreatedAt = date("Y-m-d H:i:s", $currentTime);
$pwdHash = password_hash($pwd, PASSWORD_DEFAULT);
$letterOnlyPattern ='/^[a-zA-Z ]+$/';


$responses = [];
$responses ['error'] = [];

if ($type =='Personal') {
    if (!$firstName) {
        $error= 'First name is required.';
        array_push($responses ['error'],$error);
    } else {
        if (!preg_match($letterOnlyPattern,$firstName)) {
        $error= 'First name is not valid.';
        array_push($responses ['error'],$error);
        }
    }

    if (!$lastName) {
        $error= 'Last name is required.';
        array_push($responses ['error'],$error);

    } else {
         if (!preg_match($letterOnlyPattern,$lastName)) {
        $error= 'Last name is not valid.';
        array_push($responses ['error'],$error);

        }
    }

     if (!$birthdate) {
        $error= 'Birthdate is required.';
        array_push($responses ['error'],$error);
    }

    if (!$gender) {
        $error= 'Gender is required.';
        array_push($responses ['error'],$error);
    }
}




if ($type=='School') {
    if (!$accountName) {
        $error= 'School name is required.';
        array_push($responses ['error'],$error);
    } else {
        if (!preg_match($letterOnlyPattern,$accountName)) {
        $error= 'School name is not valid.';
        array_push($responses ['error'],$error);
        }
    }

    if (!$basicAccount) {
        $error= 'School type is required.';
        array_push($responses ['error'],$error);
    } 
}


if (!$emailAddress) {
    $error= 'Email address is required.';
    array_push($responses ['error'],$error);
} else {
    if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) { 
    $error= 'Email address is not valid.';
    array_push($responses ['error'],$error);
    } else {
        $sqlEmail = "SELECT * FROM registrations WHERE registrantEmailAddress = '$emailAddress'";
        $resultEmail = mysqli_query($conn, $sqlEmail);
        $rowCountEmail = mysqli_num_rows($resultEmail);

         if ($rowCountEmail>0) { 
         $error= 'Email address is already used.';
        array_push($responses ['error'],$error);
        }
    }

}



if (!$username) {
    $error= 'Username is required.';
    array_push($responses ['error'],$error);
} else {
   
    $sqlUsername = "SELECT * FROM registrations WHERE registrantUsername = '$username'";
    $resultUsername = mysqli_query($conn, $sqlUsername);
    $rowCountUsername = mysqli_num_rows($resultUsername);

    if ($rowCountUsername>0) {
    $error= 'Username is already used.';
    array_push($responses ['error'],$error);
    }
   

}



if (!$pwd) {
    $error= 'Password is required.';
    array_push($responses ['error'],$error);
} else {
    if (strlen($pwd)<8) { 
    $error= 'Password must be at least 8 characters long.';
    array_push($responses ['error'],$error);
    }  

    if (!$pwdRetype) {
    $error= 'Please retype your password.';
    array_push($responses ['error'],$error);
}
}

 


if ($pwd != $pwdRetype) {
    $error= 'Passwords do not match.';
    array_push($responses ['error'],$error);
}


    if (!$responses ['error']) {
        $sql = "INSERT INTO registrations (registrantFirstName,registrantLastName,registrantAccountName,registrantAccountType,registrantBirthdate,registrantGender,registrantEmailAddress,registrantUsername,registrantPassword,registrantBasicAccount,registrantCreatedAt) VALUES (?, ?, ?, ?, ?, ?, ?,?,?,?,?)";
        $stmt = mysqli_stmt_init($conn);
        $prepareStmt = mysqli_stmt_prepare($stmt,$sql);

        if ($prepareStmt) {

            mysqli_stmt_bind_param($stmt,"sssssssssss",$firstName,$lastName,$accountName,$type,$birthdate,$gender,$emailAddress,$username,$pwdHash,$basicAccount,$userCreatedAt);
            mysqli_stmt_execute($stmt);

            $userId = mysqli_insert_id($conn);

            $registrantCode = "2026".sprintf("%012d",  4271997+$userId);

            $sqlUpdateDetails = "UPDATE registrations 
                        SET registrantCode = ?
                        WHERE registrantId = '$userId'";

            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt, $sqlUpdateDetails);
            
            if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt,"s",$registrantCode);
                mysqli_stmt_execute($stmt);
            }

            $responses['status'] = 'Successful';
            $responses ['user-id'] = $userId;
            $responses ['email-address'] = $emailAddress;       

        }
        $responses['status'] = 'Successful';
        $responses['success-message'] = 'Your account has been created. Verify it now by the link sent to your email address.';
        
    } else  {
        $responses['status'] = 'Unsuccessful';
    }  

    if ($responses) {
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        echo  $jsonResponses;
    } 
}





//Login
if (isset($_POST["login_submit"])) {

    $credential = htmlspecialchars($_POST["login_email_username"]);
    $pwd = htmlspecialchars($_POST["login_password"]);

    $loginErrors = [];
    $responses = [];
    $responses ['error'] = [];


    if ($credential) {

    if ($pwd) {

                $sqlRegistration = "SELECT * FROM registrations WHERE registrantEmailAddress = '$credential' or registrantUsername = '$credential'";

            $result = mysqli_query($conn, $sqlRegistration);
            $registrant= $result->fetch_assoc();

            if ($registrant) {
                    $registrantId = $registrant ['registrantId'];
                    $registrantEmailAddress =  $registrant ['registrantEmailAddress'];
                    $registrantVerificationStatus =  $registrant ['registrantVerificationStatus'];
                    $registrantPassword = $registrant["registrantPassword"];

                    $responses ['user-id'] = $registrantId;
                    $responses ['email-address'] = $registrantEmailAddress;

                    if (password_verify($pwd,$registrantPassword)) {

                            if ($registrantVerificationStatus=="Verified") {
                            
                                //Check login status
                                $sqlCheckLogin = "SELECT * FROM registrant_activities WHERE registrant_activityUserId=$registrantId ORDER BY registrant_activityId DESC LIMIT 1";
                                $sqlCheckLoginResult = mysqli_query($conn,$sqlCheckLogin);
                                $login=$sqlCheckLoginResult->fetch_assoc();

                                if ($login) {
                                    $loginContent = $login['registrant_activityContent'];

                                    if ($loginContent=='Logged in') {

                                    $error = 'You are logged in in the other device. Log it out now with the link sent to your email address.';
                                    array_push($responses['error'],$error);
                                    $responses ['login-status'] = 'Unsuccessful';
                                    $responses ['logged-in'] = true;
                                  
                                    
                                    } else {

                                         $responses ['logged-in'] = false;
                                        
                                    }


                                } else {
                                     $responses ['logged-in'] = false;
                                }

                                    if (!$responses ['logged-in']) {
                                        $activityContent='Logged in';
    
                                        $sqlInsertActivity = "INSERT INTO registrant_activities (registrant_activityUserId,registrant_activityContent) VALUES (?, ?)";
                                        $stmt = mysqli_stmt_init($conn);

                                        $prepareStmt = mysqli_stmt_prepare($stmt,$sqlInsertActivity);

                                        if ($prepareStmt) {
                                            mysqli_stmt_bind_param($stmt,"ss", $registrantId,$activityContent);
                                            mysqli_stmt_execute($stmt);

                                            $_SESSION['id'] = $registrantId;
                                            $responses ['login-status'] = 'Successful';
                                            $responses ['error'] = 'No error';
                                        }
                                    }



                            } else {
                                    $error = 'Your account is not yet verified. Verify it now with the link sent to your email address.';
                                    array_push($responses['error'],$error);
                                    $responses ['login-status'] = 'Unsuccessful';
                                    $responses ['unverified'] = true;
                                           
                            }

        
                            } else {
                                $error = 'Your password is not correct.';
                                array_push($responses['error'],$error);
                                $responses ['login-status'] = 'Unsuccessful';
                                
                            }
                

            } else{
                $error = 'We could not find a record.';
                array_push($responses['error'],$error);
                $responses ['login-status'] = 'Unsuccessful';
                
            }

        } elseif (!$pwd) {
            $error = 'Please provide your password.';
            array_push($responses['error'],$error);

            $responses ['login-status'] = 'Unsuccessful';
          
        }

    } else {
    $error = 'Please provide your email address or username.';
    array_push($responses['error'],$error);

    $responses ['login-status'] = 'Unsuccessful';

    }

    

    if ($responses) {
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        echo  $jsonResponses;
    } 
}   


//Send logout link
if (isset($_POST ['send_logout_link_submit'])) {
    $logoutId = htmlspecialchars($_POST ['user_id']);
    $logoutEmailAddress = htmlspecialchars($_POST['email_address']);;

    $mail->addAddress($logoutEmailAddress);
    $mail->Subject = "Logout Account";
    $mail->Body = <<<END
        
        <p>Click <a href='$privateFolder/includes/processing/account-processing.php?logout=yes&userid=$logoutId'> here </a> to logout your account from other device.</p>
                
    END;

        try {
            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
            
        }

}




$toLogout = false;
//Logout from button
if (isset($_POST['logout_submit'])) {
$registrantId = $_POST['registrant_id'];
$toLogout = true;
}


//Logout from email
if (isset($_GET['logout'])) {
$registrantId = htmlspecialchars($_GET['userid']);
$goToURL='Location:'.$website.'/login/';
$toLogout = true;
}



if ($toLogout) {
    $activityContent='Logged out';

    $sqlInsertActivity = "INSERT INTO registrant_activities (registrant_activityUserId,registrant_activityContent) VALUES (?, ?)";
        $stmt = mysqli_stmt_init($conn);
        $prepareStmt = mysqli_stmt_prepare($stmt,$sqlInsertActivity);

    if ($prepareStmt) {
        mysqli_stmt_bind_param($stmt,"ss", $registrantId,$activityContent);
        mysqli_stmt_execute($stmt);

        session_destroy();
        session_start();

        if (isset($_POST['logout_submit'])) {
            $responses = [];
            $responses ['status'] = 'Successful';
            $responses ['success-message'] = 'You have been logged out successfully!';

            if ($responses) {
                header('Content-Type: application/json');
                $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
                echo  $jsonResponses;
            }
        
        }

            if (isset($_GET['userid'])) { 
            header($goToURL);

        }

    } 
}


//Send verification link
if(isset($_POST['send_verification_link_submit'])) {
    $verifyingId= htmlspecialchars($_POST ['user_id']);
    $verifyingEmail=htmlspecialchars($_POST ['email_address']);
    $accountAge=htmlspecialchars($_POST ['account_age']);



    $sqlRegistration = "SELECT * FROM registrations WHERE registrantId = '$verifyingId'";
    $sqlRegistrationResult = mysqli_query($conn,$sqlRegistration);
    $registration = $sqlRegistrationResult->fetch_assoc();

    if($registration) {
        $registrantAccountName = $registration ['registrantAccountName'];
    }


    $mail->addAddress($verifyingEmail);
    $mail->Subject = "Account Verification";
   
    $mail->Body = 
         <<<END
            <p>Welcome to EskQuip, $registrantAccountName!</p>
            
            <p>EskQuip is an online platform dedicated to help improve the Philippine's educational system.</p>

            <p>This independent web application developed by Erfel Suriaga, a licensed teacher with a depth passion in learning and innovation, serves as a venue for individuals who aspire to help learners,fellow colleagues and even schools in their educational journey by providing sharing their articles, ready-made files, researches and online tools. <strong> So, if you are a teacher, writer, editor, school or developer, you are very much welcome!</strong> </p>
            
            <p>Click <a href="$publicFolder/about-us"> here </a> to read more about EskQuip.</p>

            <p>Excited to use EskQuip app? Click <a href="$privateFolder/includes/processing/account-processing.php?verify=yes&userid=$verifyingId"> here </a> to verify your account.</p> 

            <br>
            <p>Best Regards,</p> 
            <p>EskQuip Team</p>  
        END;
  

   





        try {
            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}"; 
        }

}


//Verify the account
if (isset($_GET['verify'])) {
    $verifyingId = htmlspecialchars($_GET['userid']);
    $verificationStatus = "Verified";

    $sqlVerify = "UPDATE registrations 
                    SET registrantVerificationStatus = ?
                        WHERE registrantId = $verifyingId";

     $stmt = mysqli_stmt_init($conn);
    $prepareStmt = mysqli_stmt_prepare($stmt, $sqlVerify);
    
    if ($prepareStmt) {
    mysqli_stmt_bind_param($stmt,"s", $verificationStatus);

    mysqli_stmt_execute($stmt);

            session_start();
            header('Location:'.$website.'/login/');
        }
}

//Get reset password link
if (isset($_POST["get_password_reset_link_submit"])) {

    $credential = htmlspecialchars($_POST["credential"]);


    $responses = [];
    $responses ['error'] = [];

    if (empty($credential)) {
        $error = "Please provide your email address or username.";
        array_push($responses ['error'], $error);

    } else {
        $sql = "SELECT * FROM registrations WHERE registrantEmailAddress = '$credential' or registrantUsername = '$credential'";
        $result = mysqli_query($conn, $sql);
        $registrant = mysqli_fetch_array($result, MYSQLI_ASSOC);

        if (!$registrant) {
            $error = "We could not find the record.";
            array_push($responses ['error'], $error);
        }
    }

    if (!$responses ['error']){

        $receivingEmail = $registrant ['registrantEmailAddress'];
        $userID = $registrant ['registrantId'];

        $token = bin2hex(random_bytes(16));

        $tokenHash = hash("sha256",$token);

        $tokenHashExpiration = date("Y-m-d H:i:s",time()+ 60 * 30);

        $sql = "UPDATE registrations 
                SET resetTokenHash = ?,
                    resetTokenHashExpiration = ?
                    WHERE registrantUsername=? or registrantEmailAddress = ?";


        $stmt =$conn->prepare($sql);

        $stmt ->bind_param("ssss",$tokenHash,$tokenHashExpiration,$credential,$credential);

        $stmt-> execute();


        if ($conn->affected_rows) {

            $mail->addAddress($receivingEmail);
            $mail->Subject = "Password Reset Link";
            $mail->Body = <<<END


            <p>Click <a href='$publicFolder/reset-password?userid=$userID&token=$tokenHash'> here </a> to reset your password.\nThe link will expire after 30 minutes.</p>
            
            END;

            try {
                $mail->send();


            } catch (Exception $e) {
                echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
            }

        }

        $responses ['status'] = 'Successful';
        $responses ['success-message'] = 'Password reset link is sent successfully.';

    }  else {
        $responses ['status'] = 'Unsuccessful';
    }

     if ($responses) {
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        echo  $jsonResponses;
    } 

}

//Reset password
if(isset($_POST['reset_password_submit'])) {
   
    $userId = htmlspecialchars($_POST['userid']);
    $newPassword = htmlspecialchars($_POST['new_password']);
    $newPasswordRetype = htmlspecialchars($_POST['new_password_retype']);

    $responses = [];
    $responses ['error'] = [];



    if (!$newPassword){
        $error = 'Please provide your new password.';
        array_push($responses ['error'], $error);
    } else {
         if (!$newPasswordRetype){
        $error = 'Please retype your new password.';
        array_push($responses ['error'], $error);
        } else {
            if ($newPassword!==$newPasswordRetype) {
            $error = 'Passwords do not match.';
            array_push($responses ['error'], $error); 
            }
        }
    }


        if (!$responses ['error']) {
            $pwdHash = password_hash($newPassword, PASSWORD_DEFAULT);   
            $sqlUpdatePassword = "UPDATE registrations 
                            SET registrantPassword = ?
                                WHERE registrantId = '$userId'";

            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt, $sqlUpdatePassword);
            
            if ($prepareStmt) {
            mysqli_stmt_bind_param($stmt,"s", $pwdHash);

            mysqli_stmt_execute($stmt);

                    $responses ['status'] = 'Successful';
                    $responses ['success-message'] = 'Password has been reset successfully. You will be redirected to the login page shortly...';
            } 
            
        } else {
            $responses ['status'] = 'Unsuccessful';
        }


        if ($responses) {
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        echo  $jsonResponses;
        } 
  

}



if (isset($_POST['get_profile_submit'])){
    $homeSearchedUser = htmlspecialchars($_POST['home_searched_user']);

    $responses = [];

  
     if (!$homeSearchedUser){

        if ($loggedIn) {
            $responses ['account-name'] = $accountName;
            $responses ['type'] = $type;
            $responses ['registrations'] = $registrations;
            $responses ['profile-picture-link'] = $profilePictureLink;
            $responses ['cover-photo-link'] = $coverPhotoLink;
            $responses ['description'] = $registrantDescription;

        
            $responses ['first-name'] = $firstName;
            $responses ['middle-name'] = $middleName;
            $responses ['last-name'] = $lastName;
            $responses ['birthdate'] = $birthdate;
        
            $responses ['registrant-code'] = $registrantCode;
        
            $responses ['registrantId']= $registrantId;
            $responses ['username']= $username;
            $responses ['email-address']=$emailAddress;
            $responses ['mobile-number']=$mobileNumber;

            $responses ['birthdate']=dMDY_format($birthdate);
            $responses ['birthdate-raw']=$birthdate;
            $responses ['gender']=$gender;
            $responses ['civil-status']=$civilStatus;

            $responses ['education']=$education;
            $responses ['school']=$school;
            $responses ['occupation']=$occupation;

            $responses ['street-subd-village']=$street_subd_village;
            $responses ['barangay']=$barangay;
            $responses ['city-municipality']=$city_municipality;
            $responses ['province-state']=$province_state;
            $responses ['region']=$region;
            $responses ['country']=$country;
            $responses ['zip-code']=$zipcode;


            $responses ['basic-registration'] = $basicRegistration;
            $responses ['teacher-registration']= $teacherRegistration;
            $responses ['writer-registration'] = $writerRegistration;
            $responses ['editor-registration'] = $editorRegistration;
            $responses ['website-manager-registration'] = $websiteManagerRegistration;
            $responses ['developer-registration'] = $developerRegistration;
            $responses ['researches-registration'] = $researchesRegistration;
           

            $responses ['wm-super-manager-registration'] = $websiteManagerSuperManagerRegistration;
            $responses ['wm-subscription-manager-registration']= $websiteManagerSubscriptionManagerRegistration;
            $responses ['wm-registration-manager-registration']= $websiteManagerRegistrationManagerRegistration;
            $responses ['wm-promotion-manager-registration'] = $websiteManagerPromotionManagerRegistration;
            $responses ['wm-promotion-manager-registration']= $websiteManagerMessageManagerRegistration;


            $responses ['in-subscription-seller-list'] = $inSubscriptionSellerList;
            $responses ['in-subscription-tool-list'] = $inSubscriptionToolList;
            $responses ['in-subscription-file-list']=$inSubscriptionFileList;
            $responses ['in-subscription-shelf-list']= $inSubscriptionShelfList;

            $responses ['tool-subscribed'] = $toolSubscribed; 
           $responses ['file-subscribed'] = $fileSubscribed; 
            $responses ['seller-subscribed']= $sellerSubscribed;
           $responses ['shelf-subscribed']= $shelfSubscribed; 

            $responses ['pending-tool-subscription'] = $pendingToolSubscription;
            $responses ['pending-file-subscription']= $pendingFileSubscription;
            $responses ['pending-seller-subscription']= $pendingSellerSubscription;
            $responses ['pending-shelf-subscription']= $pendingShelfSubscription;



            $responses ['have-other-registration'] = $haveOtherRegistration;
            $responses ['have-all-registrations'] = $haveAllRegistrations;

            $responses ['filled-out-selling-details'] = $filledOutSellingDetails;

            $responses ['logged-in']=true;
            
        } else {
             $responses ['logged-in']=false;
        }
        




    }

    if ($homeSearchedUser){

        $sqlHomeSearchedUserInfo = "SELECT * FROM registrations WHERE registrantUsername = '$homeSearchedUser'";
        $sqlHomeSearchedUserInfoResult = mysqli_query ($conn,$sqlHomeSearchedUserInfo);
        $homeSearchedUserInfo = $sqlHomeSearchedUserInfoResult->fetch_assoc();

        if($homeSearchedUserInfo) {
            $homeSearchedUserId=$homeSearchedUserInfo ['registrantId'];
            $homeSearchedUserAccountName = $homeSearchedUserInfo ['registrantAccountName'];
            $homeSearchedUserDescription = $homeSearchedUserInfo ['registrantDescription'];


            $homeSearchedUserType = $homeSearchedUserInfo['registrantAccountType'];
            
             $homeSearchedUserProfilePictureLink = $homeSearchedUserInfo['registrantProfilePictureLink'] ? $privateFolder.$homeSearchedUserInfo['registrantProfilePictureLink'] : $website."/assets/images/user.svg";
            $homeSearchedUserCoverPhotoLink = $homeSearchedUserInfo['registrantCoverPhotoLink'] ? $privateFolder.$homeSearchedUserInfo['registrantCoverPhotoLink'] : $website."/assets/images/cover-photo.jpeg";

            $homeSearchedUserBasicRegistration = $homeSearchedUserInfo['registrantBasicAccount'];
            $homeSearchedUserTeacherRegistration = $homeSearchedUserInfo['registrantTeacherAccount'];
            $homeSearchedUserWriterRegistration = $homeSearchedUserInfo['registrantWriterAccount'];
            $homeSearchedUserEditorRegistration = $homeSearchedUserInfo['registrantEditorAccount'];
            $homeSearchedUserWebsiteManagerRegistration = '';
            $homeSearchedUserDeveloperRegistration = $homeSearchedUserInfo['registrantDeveloperAccount'];



             $sqlHomeSearchedUserWebsiteManagerRegistrations = "SELECT * FROM website_manager_accounts WHERE website_manager_accountRegistrant = $homeSearchedUserId";
             $sqlHomeSearchedUserWebsiteManagerRegistrationsResult =mysqli_query($conn, $sqlHomeSearchedUserWebsiteManagerRegistrations);
            $homeSearchedUserWebsiteManagerRegistrations = $sqlHomeSearchedUserWebsiteManagerRegistrationsResult->fetch_assoc();


                if ($homeSearchedUserWebsiteManagerRegistrations){
                        $homeSearchedUserWebsiteManagerSuperManagerRegistration = $homeSearchedUserWebsiteManagerRegistrations ['website_manager_accountSuperManager'];

                        $homeSearchedUserWebsiteManagerSubscriptionManagerRegistration = $homeSearchedUserWebsiteManagerRegistrations ['website_manager_accountSubscriptionManager'];

                        $homeSearchedUserWebsiteManagerRegistrationManagerRegistration = $homeSearchedUserWebsiteManagerRegistrations ['website_manager_accountRegistrationManager'];

                        $homeSearchedUserWebsiteManagerPromotionManagerRegistration = $homeSearchedUserWebsiteManagerRegistrations ['website_manager_accountPromotionManager'];

                        $homeSearchedUserWebsiteManagerMessageManagerRegistration = $homeSearchedUserWebsiteManagerRegistrations ['website_manager_accountMessageManager'];
                
                        if ($homeSearchedUserWebsiteManagerSuperManagerRegistration ||            $homeSearchedUserWebsiteManagerSubscriptionManagerRegistration || $homeSearchedUserWebsiteManagerRegistrationManagerRegistration || $homeSearchedUserWebsiteManagerPromotionManagerRegistration || $homeSearchedUserWebsiteManagerMessageManagerRegistration){
                        $homeSearchedUserWebsiteManagerRegistration = 'Website Manager';
                    }
                }



            $homeSearchedUserRegistrations = [];
            
            if ($homeSearchedUserBasicRegistration) {
                array_push($homeSearchedUserRegistrations,$homeSearchedUserBasicRegistration);
            }

            if ($homeSearchedUserTeacherRegistration) {
                array_push($homeSearchedUserRegistrations,$homeSearchedUserTeacherRegistration);
            }

            if ($homeSearchedUserWriterRegistration) {
                array_push($homeSearchedUserRegistrations,$homeSearchedUserWriterRegistration);
            }
            if ($homeSearchedUserEditorRegistration) {
                array_push($homeSearchedUserRegistrations,$homeSearchedUserEditorRegistration);
            }

            if ($homeSearchedUserDeveloperRegistration) {
                array_push($homeSearchedUserRegistrations,$homeSearchedUserDeveloperRegistration);
            }

            if ($homeSearchedUserWebsiteManagerRegistration) {
                array_push($homeSearchedUserRegistrations,$homeSearchedUserWebsiteManagerRegistration);
            }

            if ($homeSearchedUserRegistrations){
                $homeSearchedUserRegistrations =implode(' | ',$homeSearchedUserRegistrations);
            }

            if ($homeSearchedUserId==$registrantId) {
                $responses ['current-account'] = true;
            } 

            $_SESSION ['home-searched-user-id'] = $homeSearchedUserId;
            $responses ['id'] = $homeSearchedUserId;
            $responses ['type'] = $homeSearchedUserType;
            $responses ['account-name'] = $homeSearchedUserAccountName;
            $responses ['registrations'] = $homeSearchedUserRegistrations;
            $responses ['profile-picture-link'] = $homeSearchedUserProfilePictureLink;
            $responses ['cover-photo-link'] = $homeSearchedUserCoverPhotoLink;
            $responses ['description'] = $homeSearchedUserDescription;

           
        } else {
            $responses ['no-account'] = true;
        }

     
    }



      

     if ($responses) {
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        echo  $jsonResponses;
    } 
}


if (isset($_POST['update_profile_details_submit'])) {

    $profile = $registrantId;
    $accountType = $type;
    $update_registrantDescription = htmlspecialchars($_POST["profile_description"]);
    
    $update_username = htmlspecialchars($_POST["profile_username"]);
    $update_emailAddress = htmlspecialchars($_POST["profile_email_address"]);
    $update_mobileNumber = htmlspecialchars($_POST["profile_mobile_number"]);

    $update_addressStreet = htmlspecialchars($_POST["profile_street_subd_village"]);
    $update_addressBarangay = htmlspecialchars($_POST["profile_barangay"]);
    $update_addressCity = htmlspecialchars($_POST["profile_city_municipality"]);

    $update_addressCountry = htmlspecialchars($_POST["profile_country"]);
    $update_addressRegion = htmlspecialchars($_POST["profile_region"]);
    $update_addressProvince = htmlspecialchars($_POST["profile_province_state"]);


    


    
    $responses = [];
    $responses ['error'] = [];

  
    if ($accountType=='Personal') {

        $update_firstName = htmlspecialchars($_POST["profile_first_name"]);
        $update_middleName = htmlspecialchars($_POST["profile_middle_name"]);
        $update_lastName = htmlspecialchars($_POST["profile_last_name"]);
    
        $accountName = [];
        
        if(empty($update_firstName)) {
        $error='First name is required.';
        array_push ($responses ['error'],$error);
       
        } else {
            array_push ($accountName,$update_firstName);
        }

        if ($update_middleName) {
            array_push ($accountName,$update_middleName);
        }

        if(empty($update_lastName)) {
        $error='Last name is required.';
            array_push ($responses ['error'],$error); 
        } else {
            array_push ($accountName,$update_lastName);
        }

        $update_accountName = implode(' ',$accountName);

        $update_birthdate = htmlspecialchars($_POST["profile_birthdate"]);
        $update_gender = htmlspecialchars($_POST["profile_gender"]);
        $update_civilStatus = htmlspecialchars($_POST["profile_civil_status"]);

        $update_educationalAttainment = htmlspecialchars($_POST["profile_educational_attainment"]);
        $update_school = htmlspecialchars($_POST["profile_school"]);
        $update_occupation = htmlspecialchars($_POST["profile_occupation"]);

        $update_basicRegistration = 'Basic User';

    }

     if ($accountType=='School') {
         $update_schoolCategory =htmlspecialchars($_POST["profile_school_category"]);

        if(empty($update_schoolCategory)) {
        $error='School type is required.';
        array_push ($responses ['error'],$error); 
        } 
        
       
        $update_accountName =htmlspecialchars($_POST["profile_account_name"]);

        if(empty($update_accountName)) {
        $error='School name is required.';
        array_push ($responses ['error'],$error);  
        } 

         $update_basicRegistration = $update_schoolCategory;
        
    }
    


    
    if(empty($update_username)) {
    $error='Username is required.';
    array_push ($responses ['error'],$error);  
    } else {
        $sqlUsername = "SELECT * FROM registrations WHERE registrantUsername = '$update_username'";
        $sqlUsernameResult = mysqli_query($conn,$sqlUsername);
        $username = $sqlUsernameResult->fetch_assoc();

        if($username) {
            if($username['registrantId']!=$profile) {
            $error='Username is already taken.';
                array_push ($responses ['error'],$error); 
            } 
        }
    }

    
    if(empty($update_emailAddress)) {
           $error='Email address is required.';
            array_push ($responses ['error'],$error);   
    } else {
        if (!filter_var($update_emailAddress, FILTER_VALIDATE_EMAIL)) {
        $error='Email address is invalid.';
        array_push ($responses ['error'],$error);   
        } else {

            $sqlEmailAddress = "SELECT * FROM registrations WHERE registrantEmailAddress = '$update_emailAddress'";
            $sqlEmailAddressResult = mysqli_query($conn,$sqlEmailAddress);
            $emailAddress = $sqlEmailAddressResult->fetch_assoc();

            if($emailAddress) {
                if($emailAddress['registrantId']!=$profile) {
                   $error='Email address is already taken.';
                    array_push ($responses ['error'],$error);
            }

        }
    }

    }
    
    if($update_mobileNumber) {
        if( !is_numeric($update_mobileNumber)) {
        $error='Mobile number is not valid.';
        array_push ($responses ['error'],$error); 
        } 
    } 


    
    if(!$responses ['error']) {

        $registrantCode = "2026".sprintf("%012d",  4271997+$profile);

        $sqlUpdateDetails = "UPDATE registrations 
                        SET registrantCode = ?,
                        registrantFirstName =?,
                        registrantMiddleName = ?,
                        registrantLastName = ?,
                        registrantAccountName = ?,
                        registrantBasicAccount = ?,
                        registrantDescription=?,
                        registrantUsername = ?,
                        registrantEmailAddress = ?,
                        registrantMobileNumber = ?,
                        registrantBirthdate = ?,
                        registrantGender = ?,
                        registrantCivilStatus = ?,
                        registrantAddressStreet = ?,
                        registrantAddressBarangay = ?,
                        registrantAddressCity = ?,
                        registrantAddressProvince = ?,
                        registrantAddressRegion = ?,
                        registrantAddressCountry = ?,
                        registrantAddressZipCode = ?,
                        registrantEducationalAttainment=?,
                        registrantSchool=?,
                        registrantOccupation =?
                        WHERE registrantId = '$profile'";

    
   

            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt, $sqlUpdateDetails);
            
            if ($prepareStmt) {
            mysqli_stmt_bind_param($stmt,"sssssssssssssssssssssss",$registrantCode, $update_firstName,$update_middleName,$update_lastName,$update_accountName,$update_basicRegistration,$update_registrantDescription,$update_username,$update_emailAddress,$update_mobileNumber,$update_birthdate,$update_gender, $update_civilStatus,$update_addressStreet,$update_addressBarangay,$update_addressCity,$update_addressProvince,$update_addressRegion,$update_addressCountry,$update_addressZipCode,$update_educationalAttainment,$update_school,$update_occupation);
            mysqli_stmt_execute($stmt);
                         
            }   

            $sqlUpdateOtherRegistrationRegistrantAccountName = "UPDATE other_registrations 
                        SET otherRegistrantAccountName =?
                        WHERE otherUserId = '$profile'";

            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt, $sqlUpdateOtherRegistrationRegistrantAccountName);
            
            if ($prepareStmt) {
            mysqli_stmt_bind_param($stmt,"s", $update_accountName);
            mysqli_stmt_execute($stmt);
                       
            }   



             $sqlUpdateSubscriptionRegistrantAccountName = "UPDATE registrant_subscriptions 
                        SET registrant_subscriptionRegistrantAccountName =?
                        WHERE registrant_subscriptionUserId = '$profile'";

            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt, $sqlUpdateSubscriptionRegistrantAccountName);
            
            if ($prepareStmt) {
            mysqli_stmt_bind_param($stmt,"s", $update_accountName);
            mysqli_stmt_execute($stmt);
                       
            }   


        $responses ['status'] = 'Successful';
        $responses ['success-message'] = 'You updated your profile successfully!';


    } else {
       $responses ['status'] = 'Unsuccessful';
    }


     if ($responses) {
      header('Content-Type: application/json');
      $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
      echo  $jsonResponses;
    } 


}   



//Update profile picture or cover photo
if (isset($_POST['upload_image_submit']))  {
    
    $uploadType = htmlspecialchars($_POST['upload_type']);
    $userId = $registrantId;
    $accountName = htmlspecialchars($_POST['account_name']);;


    $imageFileName = '';
    $imageFileSize = '';
    $imageFileNameExt = '';
    $imageFileNameActualExt = '';

    $allowedImage = ['jpeg','jpg'];
    $maxSize = 10 * 1024 * 1024;

    
    $responses = [];
    $responses ['error'] = [];

   if (isset($_FILES ['upload_image'])) {
    $image = $_FILES ['upload_image'];
    $imageFileName = $image ['name'];
    $imageFileSize = $image ['size'];
    $imageFileNameExt = explode ('.',$imageFileName);
    $imageFileNameActualExt = strtolower(end($imageFileNameExt));

        if ($imageFileNameActualExt=='jpg') {
            $imageFileNameActualExt='jpeg';
        }
    


        if((!in_array($imageFileNameActualExt,$allowedImage))) {
            $error= 'Please select an image in JPEG or JPG format only.';
        array_push ($responses ['error'],$error);
       

        }

        if($imageFileSize>$maxSize) {
            $error= 'Your image is too big in size.';
        array_push ($responses ['error'],$error);

        }



   } else {
        $error= 'You did not select an image.';
        array_push ($responses ['error'],$error);
   }
    
    

    if (!$responses ['error']) {

    if($uploadType=='Profile Picture') {
        $imageFolder = '../../uploads/profile-pictures/';
        $imageLinkColumn = 'registrantProfilePictureLink';
        $maxResolution = 500;
        
    }

    if ($uploadType=='Cover Photo') {
        $imageFolder = '../../uploads/cover-photos/';
        $imageLinkColumn = 'registrantCoverPhotoLink';
        $maxResolution = 4000;
    
    }

    $sqlRegistrantData = "SELECT * FROM registrations WHERE registrantId = '$userId'";
    $sqlRegistrantDataResult = mysqli_query($conn,$sqlRegistrantData);
    $registrantData= $sqlRegistrantDataResult->fetch_assoc();

    $registrantImageLink = $registrantData [$imageLinkColumn];
    

    if ($registrantImageLink) {
        $registrantImageLinkDelete = '../..'.$registrantImageLink;
        $registrantImageLinkDeleted = unlink($registrantImageLinkDelete);
    } else {
         $registrantImageLinkDelete='';
          $registrantImageLinkDeleted='';
    }

    // Create folders if they don't exist
    if (!is_dir($imageFolder)) {
        mkdir($imageFolder, 0777, true);
    }

    $imageFile = $imageFolder .str_replace(" ","_",$accountName)."-".date("YmdHis",time()).".".$imageFileNameActualExt;

    $uploadOk = 1;

    if (move_uploaded_file($image["tmp_name"], $imageFile)) {
        $uploadOk = 1;
    } 


    //Resize and crop image
    
    if ($imageFileNameActualExt=='jpeg') {
    $originalImage = imagecreatefromjpeg($imageFile);
    }

    if ($imageFileNameActualExt=='png') {
    $originalImage = imagecreatefrompng($imageFile);
    }


    
    $originalWidth = imagesx($originalImage);
    $originalHeight = imagesy($originalImage);

    if ($originalHeight > $originalWidth) {
    $ratio = $maxResolution / $originalWidth;
    $newWidth = $maxResolution;
    $newHeight = $originalHeight * $ratio;

    $difference= $newHeight - $newWidth;

    $x=0;
    $y = round($difference/2);

    } 
    
    elseif($originalHeight < $originalWidth) {

      $ratio = $maxResolution / $originalHeight;
      $newHeight = $maxResolution;
      $newWidth = $originalWidth * $ratio;

      $difference= $newWidth - $newHeight;

      $x = round($difference/2);
      $y=0;
    } 
    
    elseif ($originalHeight == $originalWidth) {

    
      $newWidth = $maxResolution;
      $newHeight = $maxResolution;

        $x=0;
        $y=0;

    
       
    }


    if ($originalImage) {
    $newImage = imagecreatetruecolor($newWidth,$newHeight);
    imagecopyresampled($newImage,$originalImage,0,0,0,0,$newWidth,$newHeight,$originalWidth,$originalHeight); 

    if ($uploadType=='Profile Picture') {
    $newCropImage = imagecreatetruecolor($maxResolution,$maxResolution);
    imagecopyresampled($newCropImage,$newImage,0,0,$x,$y,$maxResolution,$maxResolution,$maxResolution,$maxResolution); 
    }

    if ($uploadType=='Cover Photo') {

   $newCropImage = imagecreatetruecolor($maxResolution,$maxResolution/3);
    imagecopyresampled($newCropImage,$newImage,0,0,$x,$y,$maxResolution,$maxResolution,$maxResolution,$maxResolution); 
    }

    imagejpeg($newCropImage,$imageFile,90);
    }


  

    $uploadedImageFile= substr($imageFile,5);
    $imageStatus = 0;

    $sqlUpdateImage = "UPDATE registrations
                        SET 
                        $imageLinkColumn=?
                        WHERE registrantId = $userId";


    $stmt = mysqli_stmt_init($conn);
    $prepareStmt = mysqli_stmt_prepare($stmt, $sqlUpdateImage);
    
    if ($prepareStmt) {
    mysqli_stmt_bind_param($stmt,"s", $uploadedImageFile);

    mysqli_stmt_execute($stmt);

                                
        $responses ['status'] = 'Successful';
        $responses['success-message'] = 'You updated your '.$uploadType.' successfully!';

     }

     
    } else {
        $responses ['status'] = 'Unsuccessful';
    }


     if ($responses) {
      header('Content-Type: application/json');
      $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
      echo  $jsonResponses;
   } 
    
    
}




//Check other registration
if (isset($_POST['check_other_registration_submit'])) {

    $regType = htmlspecialchars($_POST['regtype']);

    $responses = [];
    $responses ['have-registration'] = false; 

    $sqlCheckRegistration = "SELECT * FROM other_registrations WHERE otherType = '$regType' AND otherUserId = $registrantId";
$sqlCheckRegistrationResult = mysqli_query($conn,$sqlCheckRegistration);
$checkedRegistration= $sqlCheckRegistrationResult->fetch_assoc();

if ($checkedRegistration) {
   $responses ['have-registration'] = true;   
}

    if ($responses) {
      header('Content-Type: application/json');
      $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
      echo  $jsonResponses;
   } 

}





//Submit other registration
if (isset($_POST['other_registration_submit'])) {

$regType = htmlspecialchars($_POST['regtype']);

$responses = [];
$responses ['error'] = [];

$licenseCertification = ''; 
$licenseCertificationFileName = ''; 
$licenseCertificationFileNameExt = ''; 
$licenseCertificationFileNameActualExt = ''; 


if (isset($_FILES ['other_registration_license_certification'])) {
$licenseCertification = $_FILES['other_registration_license_certification']; 
$licenseCertificationFileName = $licenseCertification ['name'];
$licenseCertificationFileNameExt = explode ('.',$licenseCertificationFileName);
$licenseCertificationFileNameActualExt = strtolower(end($licenseCertificationFileNameExt));        

}

$sample =htmlspecialchars($_POST['other_registration_sample']);


$agreement = ''; 
$agreementFileName = ''; 
$agreementFileNameExt = ''; 
$agreementFileNameActualExt = ''; 

if (isset($_FILES ['other_registration_agreement'])) {
$agreement = $_FILES['other_registration_agreement']; 
$agreementFileName = $agreement ['name'];
$agreementFileNameExt = explode ('.',$agreementFileName);
$agreementFileNameActualExt = strtolower(end($agreementFileNameExt));
}



$allowedExtLicenseCertification = ['pdf'];
$allowedExtAgreement = ['pdf'];


$sqlCheckRegistration = "SELECT * FROM other_registrations WHERE otherType = '$regType' AND otherUserId = $registrantId";
$sqlCheckRegistrationResult = mysqli_query($conn,$sqlCheckRegistration);
$checkedRegistration= $sqlCheckRegistrationResult->fetch_assoc();

if ($checkedRegistration) {
    $error = 'You already sent your registration as '.$regType;
    array_push($responses['error'],$error);   
}
 

 if ($regType =='Teacher'){
    if (empty($licenseCertificationFileName)) {
      $error = "Please provide  your license or certification.";
     array_push($responses['error'],$error); 
    } else{
      if (!in_array($licenseCertificationFileNameActualExt,$allowedExtLicenseCertification)) {
        $error = "Invalid format for license or cetification.";
        array_push($responses['error'],$error); 
        }
    }
  }

if ($regType == 'Writer' || $regType=='Editor' || $regType == 'Developer'){
  if (empty($sample)) {
    $error = "Please provide a sample";
    array_push($responses['error'],$error); 
  } else {
    if (!str_contains($sample,'https://')) {
        $sample = 'https://'.str_replace('http://','',$sample);
    }
  }
          
}


 if (empty($agreementFileName)) {
    $error = "Please attach an agreement.";
    array_push($responses['error'],$error); 
} else{
    if (!in_array($agreementFileNameActualExt,$allowedExtAgreement)) {
      $error = "Invalid format for agreement.";
        array_push($responses['error'],$error); 
      }
}





if (!$responses['error']) {
  $checkRegistrant = "SELECT * FROM other_registrations WHERE otherUserId = $registrantId AND otherType='$regType'";
  $checkRegistrantResult = mysqli_query($conn,$checkRegistrant);
  $recordedRegistration = $checkRegistrantResult->fetch_assoc();

    $licenseCertificationFileLink = '';
    if ($regType == 'Teacher') {
        if ($licenseCertificationFileName) {
        $licenseCertificationFolder = '../../uploads/registration/'.$regType.'/license-certification/';

        if (!is_dir($licenseCertificationFolder)) {
            mkdir($licenseCertificationFolder, 0777, true);
        }

        $licenseCertificationFile = $licenseCertificationFolder.str_replace(' ','-',$accountName)."-".date("YmdHis",time()).".".$licenseCertificationFileNameActualExt;

        $uploadOk = 1;

        if (move_uploaded_file($licenseCertification["tmp_name"], $licenseCertificationFile)) {
            $uploadOk = 1;
        } 

        $licenseCertificationFileLink= substr($licenseCertificationFile,5);
        }  

    }
         
        
        
    if ($regType ==  'Writer' || $regType ==  'Editor' || $regType ==  'Developer'){
    if ($sample) {
        $sample = $sample;
        }      
    }
       


    $agreementFolder = '../../uploads/registration/'.$regType.'/agreement/';

    if (!is_dir($agreementFolder)) {
        mkdir($agreementFolder, 0777, true);
    }

    $agreementFile = $agreementFolder.str_replace(' ','-',$accountName)."-".date("YmdHis",time()).".".$agreementFileNameActualExt;

    $uploadOk = 1;

    if (move_uploaded_file($agreement["tmp_name"], $agreementFile)) {
        $uploadOk = 1;
    }

    $agreementFileLink= substr($agreementFile,5);

    $sqlRegister = "INSERT INTO other_registrations(otherUserId,otherType,otherRegistrantAccountName,otherLicenseCertification,otherSample,otherAgreement) VALUES ( ?, ?, ?,?, ?,?)";

    $stmt =$conn->prepare( $sqlRegister);

    $stmt ->bind_param("ssssss", $registrantId,$regType,$accountName,$licenseCertificationFileLink,$sample,$agreementFileLink);

    $stmt-> execute();      


    $successMessage = '';

    if ($regType != 'Researches'){
        $successMessage = 'You submitted your registration as '.$regType.' successfully!';
    }

    if ($regType == 'Researches'){
        $successMessage = 'You submitted your registration for '.$regType.' successfully!';
    }

    $responses ['status'] ='Successful';
  
    $responses ['success-message'] = $successMessage ;
   
    
} else {

     $responses ['status'] = 'Unsuccessful';
    
}

if ($responses) {
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        echo  $jsonResponses;
    }

        

}




//Get submissions
if (isset($_POST['get_submissions_submit'])) {

$regType = $_POST['regtype'];

$responses = [];
           
$sqlSubmissions = "SELECT * FROM other_registrations WHERE otherUserId = $registrantId AND otherType='$regType'";
$sqlSubmissionsResult = mysqli_query($conn,$sqlSubmissions);
$submitted= $sqlSubmissionsResult->fetch_assoc();

if($submitted) {
   $responses ['status']= $submitted['otherStatus'];
    $responses ['sample']= $submitted['otherSample'];
    $responses ['license-certification']= $submitted['otherLicenseCertification'] ? $privateFolder.$submitted['otherLicenseCertification']:'';
    $responses ['agreement']= $submitted['otherAgreement'] ? $privateFolder.$submitted['otherAgreement'] :'';

    $responses ['submitted']= true;

} else {
    $responses ['submitted']= false;
}


if ($responses) {
      header('Content-Type: application/json');
      $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
      echo  $jsonResponses;
   } 

}




 if (isset($_POST['get_users_submit'])){
    $selectedUsers = htmlspecialchars($_POST['selected_users']);
    $searchedUser = htmlspecialchars($_POST['searched_user']);
    $responses = [];

    $sqlUsers = "SELECT * FROM registrations";
    $sqlUsersResult = mysqli_query($conn,$sqlUsers);

    if ($searchedUser){
        $sqlUsers = "SELECT * FROM registrations WHERE registrantAccountName LIKE '%$searchedUser%'";
        $sqlUsersResult = mysqli_query($conn,$sqlUsers);
    }

    if ($sqlUsersResult->num_rows > 0){ 
        while($users = $sqlUsersResult->fetch_assoc()){
            $userAccountName = $users ['registrantAccountName'];
            $userUserId = $users ['registrantId'];
            $userUserCode = $users ['registrantCode'];
            $selected = false;
            if ($selectedUsers){
                if (str_contains($selectedUsers,$userUserCode)) {
                    $selected= true;
                } else {
                     $selected= false;
                }
            }
           
            if (!$selected){
                if($registrantCode !=$userUserCode){
                         echo "<span class='link-tag-button' id='".$userUserCode."'>$userAccountName</span>";
                }
               
            }
            
        }
    }
}





if(isset($_POST['get_user_info_submit'])){
    $selectedUsers = htmlspecialchars($_POST['selected_users']);
  
$selectedUsers = explode(', ',$selectedUsers);

foreach ($selectedUsers as $userCode){
    $sqlUser = "SELECT * FROM registrations WHERE registrantCode = '$userCode'";
    $sqlUserResult = mysqli_query($conn,$sqlUser);
    $user = $sqlUserResult->fetch_assoc();

     if ($user) {
        $userAccountName = $user['registrantAccountName'];
        echo "<span class='link-tag-button' id=$userCode>$userAccountName</span>";
    } 

    
}


}






if(isset($_POST['get_my_seller_details_submit'])){
    $sqlRegistration = "SELECT * FROM registrations WHERE registrantId = $registrantId LIMIT 1";
    $sqlRegistrationResult = mysqli_query($conn,$sqlRegistration);
    $registration = $sqlRegistrationResult->fetch_assoc();

    $responses = [];

    if($registration){
        $responses ['payment-channel'] = $registration['registrantPaymentChannel'];
        $responses ['account-name'] = $registration['registrantBankAccountName'];
        $responses ['account-number'] = $registration['registrantBankAccountNumber'];
        $responses ['review-schedules'] = $registration['registrantReviewSchedules'];
        $responses ['registration'] = true;
        
    } else {
        $responses ['registration'] = false;
    }


     if ($responses) {
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        echo  $jsonResponses;
    } 

}







if(isset($_POST['update_my_seller_details_submit'])){
    $paymentChannel = htmlspecialchars($_POST['payment_channel']);
    $accountName = htmlspecialchars($_POST['account_name']);
    $accountNumber = htmlspecialchars($_POST['account_number']);
    $reviewSchedules = htmlspecialchars($_POST['review_schedules']);

    $responses = [];
    $responses ['error'] = [];

    if (!$paymentChannel) {
        $error = 'Please provide your payment channel.';
        array_push($responses ['error'],$error);
    }

    if (!$accountName) {
        $error = 'Please provide your account name.';
        array_push($responses ['error'],$error);
    }

    if (!$accountNumber) {
        $error = 'Please provide your account number.';
        array_push($responses ['error'],$error);
    }

    if (!$reviewSchedules) {
        $error = 'Please provide your review schedules.';
        array_push($responses ['error'],$error);
    }

   if (!$responses ['error']){

            $sqlUpdateMySellerDetails = "UPDATE registrations 
                            SET registrantPaymentChannel = ?,
                            registrantBankAccountName = ?,
                            registrantBankAccountNumber = ?,
                            registrantReviewSchedules = ?
                                WHERE registrantId = $registrantId";

            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt, $sqlUpdateMySellerDetails);
            
            if ($prepareStmt) {
            mysqli_stmt_bind_param($stmt,"ssss", $paymentChannel,$accountName,$accountNumber,$reviewSchedules);

            mysqli_stmt_execute($stmt);

            $responses ['status'] = 'Successful';
            
        } 

   } else {
    $responses ['status'] = 'Unsuccessful';
   }

     if ($responses) {
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        echo  $jsonResponses;
    } 

}




if (isset($_POST['check_password_for_super_manager_registration_update_submit'])){
    $password = htmlspecialchars($_POST['password']);

    $responses = [];
    $responses ['error'] = [];

    if ($password){
        $sqlCheckRegistration = "SELECT * FROM registrations WHERE registrantId = '$registrantId'";
            $sqlCheckRegistrationResult = mysqli_query($conn, $sqlCheckRegistration);
            $checkedRegistration= $sqlCheckRegistrationResult->fetch_assoc();

            if ($checkedRegistration) {
                $registrationPassword =$checkedRegistration["registrantPassword"];

                if (password_verify($password, $registrationPassword)){
                    $responses ['status']= 'Successful';
                } else {
                     $error = 'Your password is not correct.';
                array_push( $responses ['error'],$error);
                }
            }   else {
                $error = 'We could not find a record.';
                array_push( $responses ['error'],$error);
            }    
    } else {
         $error = 'Please provide your password.';
        array_push( $responses ['error'],$error);
    }


    if ($responses) {
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        echo  $jsonResponses;
    } 





}



if (isset($_POST['get_thread_messages_submit'])){
    $messageMode = htmlspecialchars($_POST['message_mode']);
    $adminMessageThreadCode = htmlspecialchars($_POST['admin_message_thread_code']);
    $senderCode = htmlspecialchars($_POST['sender_code']);
    $recipientCode = htmlspecialchars($_POST['recipient_code']);

    if ($messageMode == 'Personal'){
        $thread1 = $senderCode.$recipientCode;
        $thread2 = $recipientCode.$senderCode;
    }

    if ($messageMode == 'Message Manager'){
            $thread1= $adminMessageThreadCode;
    }
  
        if ($messageMode == 'Personal'){
        $sqlGetThreadMessages = "SELECT * FROM thread_messages WHERE thread_messageThreadCode = '$thread1' OR thread_messageThreadCode = '$thread2'  ORDER BY thread_messageId DESC";

        }

         if ($messageMode == 'Message Manager'){
            $sqlGetThreadMessages = "SELECT * FROM thread_messages WHERE thread_messageThreadCode = '$thread1'  ORDER BY thread_messageId DESC";
        }

        $sqlGetThreadMessagesResult = mysqli_query($conn,$sqlGetThreadMessages);
    

        if ($sqlGetThreadMessagesResult->num_rows>0) {
            while ($threadMessages = $sqlGetThreadMessagesResult->fetch_assoc()) {
                $threadMessageContent = nl2br($threadMessages ['thread_messageContent']);
                $threadMessageRegistrantId = $threadMessages ['thread_messageRegistrantId'];
                 $threadMessageThreadCode = $threadMessages ['thread_messageThreadCode'];
                $threadMessageTimestamp = dcomplete_format($threadMessages ['thread_messageTimestamp']);

                 if ($threadMessageRegistrantId==$registrantId){
                $divClass = 'thread-message-sender';
                $contentClass = 'thread-message-content-sender';
                 }

                if ($threadMessageRegistrantId!=$registrantId){
                    $divClass = 'thread-message-recipient';
                    $contentClass = 'thread-message-content-recipient';
                }


                 echo "
                <div class='$divClass'>
                    <p class='$contentClass'>$threadMessageContent</p>
                    <small style='".'margin-top:-10px;'."'>✓ $threadMessageTimestamp</small>
                </div>
            
            ";
            }
           
        } else {
            echo "
                <div>
                <small> No message...</small>
                </div>
            ";
        }


       
            if ($messageMode=='Personal'){
                $getMessageThread = "SELECT * FROM message_threads WHERE message_threadCode = '$thread1'  OR message_threadCode= '$thread2'";
            }

             if ($messageMode=='Message Manager'){
                $getMessageThread = "SELECT * FROM message_threads WHERE message_threadCode = '$thread1'";
            }
             
            //update message status
            $threadMessageStatus = 'Read';

            $getMessageThreadResult = mysqli_query($conn,$getMessageThread);
            $messageThread = $getMessageThreadResult->fetch_assoc();
            
            if ($messageThread){ 
                
            $updateStatus= false;
                if ($messageMode=='Personal'){
                    if ($recipientCode != $senderCode) {
                    $messageThreadCode = $messageThread ['message_threadCode'];
                     $updateStatus =true;
                    }

                }

                  if ($messageMode=='Message Manager'){
                    $messageThreadCode = $adminMessageThreadCode;
                    $updateStatus =true;
                }
                 

                if ($updateStatus){
                    $updateThreadMessageStatus = "UPDATE thread_messages 
                                SET thread_messageStatusRecipient = ?
                                WHERE thread_messageThreadCode = '$messageThreadCode'
                                AND thread_messageRegistrantId !=$registrantId";

                    $stmt = mysqli_stmt_init($conn);
                    $prepareStmt = mysqli_stmt_prepare($stmt, $updateThreadMessageStatus);
                    
                    if ($prepareStmt) {
                        mysqli_stmt_bind_param($stmt,"s", $threadMessageStatus);
                        mysqli_stmt_execute($stmt);
                
                    }  

                }
                
            

        }
        


       
    
}


if (isset($_POST['send_message_submit'])){
    $messageMode = htmlspecialchars($_POST['message_mode']);
    $adminMessageThreadCode = htmlspecialchars($_POST['admin_message_thread_code']);
    $recipientCode = htmlspecialchars($_POST['recipient_code']);
    $senderCode = htmlspecialchars($_POST['sender_code']);

 
    $messageContent = htmlspecialchars($_POST['message_content']);

    if ($messageMode=='Personal'){
          //thread checker for personal
        $thread1= $senderCode.$recipientCode;
        $thread2= $recipientCode.$senderCode;
    }

      if ($messageMode=='Message Manager'){
         //thread checker for message manager
            $thread1= $adminMessageThreadCode;
    }
  

    $responses = [];
    $responses ['error'] = [];

    if (!$messageContent) {
        $error = 'Please type your message.';
        array_push($responses ['error'],$error);
        } 

   

    if (!$responses['error']){ 
        if ($messageMode=='Personal') {
            $sqlCheckMessageThread = "SELECT * FROM message_threads WHERE message_threadCode = '$thread1' OR message_threadCode = '$thread2'";
        }

        if ($messageMode=='Message Manager') {
            $sqlCheckMessageThread = "SELECT * FROM message_threads WHERE message_threadCode = '$thread1'";
        }
        

        $sqlCheckMessageThreadResult = mysqli_query($conn,$sqlCheckMessageThread);
        $checkedMessageThread = $sqlCheckMessageThreadResult->fetch_assoc();

        if ($checkedMessageThread) {
            $messageThreadId = $checkedMessageThread ['message_threadId'];
            $messageThreadCode = $checkedMessageThread ['message_threadCode'];
        }

        if (!$checkedMessageThread) {
        //Insert threads
        $messageThreadCode = $thread1;

        $sqlInsertThread = "INSERT INTO message_threads (message_threadType,message_threadCode) VALUES (?, ?)";

        $stmt =$conn->prepare($sqlInsertThread);
        $stmt ->bind_param("ss", $messageMode,$messageThreadCode);
        $stmt-> execute(); 

        $messageThreadId =mysqli_insert_id($conn);

        }

         //Insert to messages
         $sqlInsertMessage = "INSERT INTO thread_messages (thread_messageThreadCode,thread_messageRegistrantId,thread_messageContent) VALUES ( ?, ?,?)";

        $stmt =$conn->prepare($sqlInsertMessage);
        $stmt ->bind_param("sss", $messageThreadCode,$registrantId,$messageContent);
        $stmt-> execute(); 

        $threadMessageId = mysqli_insert_id($conn);


        if ($messageMode == 'Personal') {
            if ($senderCode == $recipientCode) {
            $messageThreadUpdateStatusRecipient = 'Read';
            $sqlUpdateMessageStatusRecipient = "UPDATE thread_messages 
                            SET thread_messageStatusRecipient = ?
                            WHERE thread_messageId = '$threadMessageId'";

                $stmt = mysqli_stmt_init($conn);
                $prepareStmt = mysqli_stmt_prepare($stmt, $sqlUpdateMessageStatusRecipient);
                
                if ($prepareStmt) {
                    mysqli_stmt_bind_param($stmt,"s",$messageThreadUpdateStatusRecipient);
                    mysqli_stmt_execute($stmt);
            
                }

            }

        }
        

       //change thread update date
        $messageThreadUpdateDate = date("Y-m-d H:i:s", $currentTime);
         $sqlUpdateMessageThreadDetails = "UPDATE message_threads 
                        SET message_threadUpdateDate = ?
                        WHERE message_threadId = '$messageThreadId'";

            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt, $sqlUpdateMessageThreadDetails);
            
            if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt,"s",$messageThreadUpdateDate);
                mysqli_stmt_execute($stmt);
        
            }
        
        $responses ['status']= 'Successful';
        
  
    } else {
        $responses ['status']= 'Unsuccessful';
    }



     if ($responses) {
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        echo  $jsonResponses;
    } 




}


if (isset($_POST['get_recipient_records_submit'])) {
    $recipientCode = htmlspecialchars($_POST['recipient_code']);

    $responses = [];

    if ($recipientCode) {

            $sqlRecipientRecords = "SELECT * FROM registrations where registrantCode = $recipientCode";
            $sqlRecipientRecordsResults = mysqli_query($conn,$sqlRecipientRecords);
            $recipientRecords =  $sqlRecipientRecordsResults->fetch_assoc();


            if($recipientRecords) {
               
                $recipientType =  $recipientRecords['registrantAccountType'];
                $recipientAccountName =  $recipientRecords['registrantAccountName'];
                $recipientRegistrantDescription = $recipientRecords ['registrantDescription'];

               
                $recipientProfilePictureLink =  $recipientRecords['registrantProfilePictureLink'] ? $privateFolder. $recipientRecords['registrantProfilePictureLink'] : $website."/assets/images/user.svg";

               
                $recipientBasicRegistration =  $recipientRecords['registrantBasicAccount'];
                $recipientTeacherRegistration =  $recipientRecords['registrantTeacherAccount'];
                $recipientWriterRegistration =  $recipientRecords['registrantWriterAccount'];
                $recipientEditorRegistration =  $recipientRecords['registrantEditorAccount'];
                $recipientWebsiteManagerRegistration = '';
                $recipientDeveloperRegistration =  $recipientRecords['registrantDeveloperAccount'];
                $recipientResearchesRegistration =  $recipientRecords['registrantResearchesAccount'];
                
                

                 $sqlRecipientWebsiteManagerRegistrations = "SELECT * FROM website_manager_accounts WHERE website_manager_accountRegistrant = $registrantId";
                    $sqlRecipientWebsiteManagerRegistrationsResult =mysqli_query($conn,$sqlRecipientWebsiteManagerRegistrations);
                    $recipientWebsiteManagerRegistrations = $sqlRecipientWebsiteManagerRegistrationsResult->fetch_assoc();

                    if ($recipientWebsiteManagerRegistrations){
                        $recipientWebsiteManagerRegistration = 'Website Manager';
                    }



                    $recipientAccounts = [];
              
                    if ($recipientBasicRegistration) {
                        array_push($recipientAccounts,$recipientBasicRegistration);
                    }

                    if ($recipientTeacherRegistration) {
                        array_push($recipientAccounts,$recipientTeacherRegistration);
                    }

                    if ($recipientWriterRegistration) {
                        array_push($recipientAccounts,$recipientWriterRegistration);
                    }
                    if ($recipientEditorRegistration) {
                        array_push($recipientAccounts,$recipientEditorRegistration);
                    }

                    if ($recipientDeveloperRegistration) {
                        array_push($recipientAccounts,$recipientDeveloperRegistration);
                    }

                    if ($recipientWebsiteManagerRegistration) {
                        array_push($recipientAccounts,$recipientWebsiteManagerRegistration);
                    }         

                    if ($recipientAccounts) {
                        $recipientAccounts = implode(' | ', $recipientAccounts);
                    }

                    $responses ['type'] = $recipientType;
                    $responses ['account-name'] = $recipientAccountName;
                    $responses ['profile-picture'] = $recipientProfilePictureLink;
                    $responses ['accounts'] = $recipientAccounts;


            }


    }


    if ($responses) {
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        echo  $jsonResponses;
    }
}




if (isset($_POST['get_unread_messages_submit'])){

$responses = [];

    //General
    $sqlUnreadMessages = "SELECT * FROM thread_messages WHERE thread_messageThreadCode LIKE '%$registrantCode%' AND thread_messageStatusRecipient = 'Unread' AND thread_messageRegistrantId !=$registrantId";
    $sqlUnreadMessagesResult = mysqli_query($conn, $sqlUnreadMessages);

    $unreadMessages= $sqlUnreadMessagesResult->num_rows;

    $responses ['unread-messages'] = $unreadMessages;


    //Personal

    $sqlUnreadMessagesPersonal = "SELECT * FROM thread_messages WHERE thread_messageThreadCode LIKE '%$registrantCode%' AND thread_messageThreadCode NOT LIKE '%TOADMIN%' AND thread_messageStatusRecipient = 'Unread' AND thread_messageRegistrantId !=$registrantId";
    $sqlUnreadMessagesPersonalResult = mysqli_query($conn, $sqlUnreadMessagesPersonal);

    $unreadMessagesPersonal= $sqlUnreadMessagesPersonalResult->num_rows;

    $responses ['unread-messages-personal'] = $unreadMessagesPersonal;

    
    //Message Manager-Admin

    $sqlUnreadMessagesMessageManagerAdmin = "SELECT * FROM thread_messages WHERE thread_messageThreadCode NOT LIKE '%$registrantCode%' AND thread_messageThreadCode LIKE '%TOADMIN%' AND thread_messageStatusRecipient = 'Unread' AND thread_messageRegistrantId !=$registrantId";
    $sqlUnreadMessagesMessageManagerAdminResult = mysqli_query($conn, $sqlUnreadMessagesMessageManagerAdmin);

    $unreadMessagesMessageManagerAdmin= $sqlUnreadMessagesMessageManagerAdminResult->num_rows;

    $responses ['unread-messages-message-manager-admin'] = $unreadMessagesMessageManagerAdmin;




     //Message Manager-Non Admin
    $sqlUnreadMessagesMessageManagerNonAdmin = "SELECT * FROM thread_messages WHERE thread_messageThreadCode LIKE '%$registrantCode%' AND thread_messageThreadCode LIKE '%TOADMIN%' AND thread_messageStatusRecipient = 'Unread' AND thread_messageRegistrantId !=$registrantId";
    $sqlUnreadMessagesMessageManagerNonAdminResult = mysqli_query($conn, $sqlUnreadMessagesMessageManagerNonAdmin);

    $unreadMessagesMessageManagerNonAdmin= $sqlUnreadMessagesMessageManagerNonAdminResult->num_rows;

    $responses ['unread-messages-message-manager-non-admin'] = $unreadMessagesMessageManagerNonAdmin;


    if ($websiteManagerMessageManagerRegistration || $websiteManagerSuperManagerRegistration){
        $responses ['message-manager'] = true;
    } else {
         $responses ['message-manager'] = false;
    }

    if ($responses) {
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        echo  $jsonResponses;
    } 

}



if (isset($_POST['get_unread_updates_submit'])){

$responses = [];



 $sqlUnreadUpdates = "SELECT * FROM updates WHERE updateStatus='Published'";

 if ($loggedIn) {
    $sqlUnreadUpdates = "SELECT * FROM updates WHERE updateStatus='Published' AND updateRegistrantId != $registrantId AND updateViewers NOT LIKE '%$registrantCode%'";
}



    $sqlUnreadUpdatesResult = mysqli_query($conn, $sqlUnreadUpdates);

    $unreadUpdates= $sqlUnreadUpdatesResult->num_rows;

    $responses ['unread-updates'] = $unreadUpdates;

    if ($responses) {
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        echo  $jsonResponses;
    } 

}