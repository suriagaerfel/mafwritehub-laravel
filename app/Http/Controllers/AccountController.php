<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use App\Models\Registration;
use App\Services\AccountRecordsService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Services\MailService;
use App\Services\FunctionsService;









class AccountController extends Controller
{   
    
     public function create_account(Request $request)
    {
        $currentTime = config('app.currentTime');
        $conn = config('app.conn');

       

        //for creating account
        if ($request->input('create_account_submit')) {
        
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
                $birthdate =null;
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

            $acceptRegistration = false;

            $responses = [];
            $responses ['error'] = [];


            if ($acceptRegistration){

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
                
                    $existingEmail = Registration::where('registrantEmailAddress',$emailAddress)->first();

                    if ($existingEmail) { 
                    $error= 'Email address is already used.';
                    array_push($responses ['error'],$error);
                    }
                }

            }



            if (!$username) {
                $error= 'Username is required.';
                array_push($responses ['error'],$error);
            } else {
            
                $existingUsername = Registration::where('registrantUsername',$username)->first();

                if ($existingUsername) {
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


        } else {
            $error= 'The app is currently not accepting new registration. Please come back once the deployment is 100% complete.';
            array_push($responses['error'],$error);
        }
            if (!$responses ['error']) {

            $verificationCode = bin2hex(random_bytes(32));
                
            $sql = "INSERT INTO registrations (registrantFirstName, registrantLastName, registrantAccountName,registrantAccountType,registrantBirthdate,registrantGender,registrantEmailAddress,registrantUsername,registrantPassword,registrantBasicAccount,registrantCreatedAt, registrantVerificationCode) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $conn->prepare($sql);

            $stmt->execute([
                    $firstName,
                    $lastName,
                    $accountName,
                    $type,
                    $birthdate,
                    $gender,
                    $emailAddress,
                    $username,
                    $pwdHash,
                    $basicAccount,
                    $userCreatedAt,
                    $verificationCode
            ]);

            $userId = $conn->lastInsertId();
            
           
                //Add registration code
                $registrantCode = "2026".sprintf("%012d",  4271997+$userId);
             

                $sql = "UPDATE registrations SET registrantCode = ? WHERE registrantId = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$registrantCode, $userId]);
                     

               

                $responses ['registrantFirstName']=$firstName;
                $responses ['registrantLastName']=$lastName;
                $responses ['registrantAccountName']=$accountName;
                $responses ['registrantAccountType']=$type;
                $responses ['registrantBirthdate']=$birthdate;
                $responses ['registrantGender']=$gender;
                $responses ['registrantEmailAddress']=$emailAddress;
                $responses ['registrantUsername']=$username;
                $responses ['registrantPassword']=$pwdHash;
                $responses ['registrantBasicAccount']=$basicAccount;
                $responses ['registrantCreatedAt']=$userCreatedAt;
               

                $responses ['user-id']=$userId;
                $responses ['email-address']=$emailAddress;

                $responses['status'] = 'Successful';
                $responses['success-message'] = 'Your account has been created. Verify it now by the link sent to your email address.';
                
            } else  {
                $responses['status'] = 'Unsuccessful';
            }  

            if ($responses) {
                header('Content-Type: application/json');
                $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
                echo $jsonResponses;
            }
            
            
        }

    
        
    }

    public function send_verification_link (Request $request){

            $publicFolder= config('app.publicFolder');
            $conn = config('app.conn');

            //Send verification link
            if ($request->input('send_verification_link_submit')) {
            

            $verifyingId = $request->input('user_id');
            $verifyingEmail = $request->input('email_address');
            $accountAge = $request->input('account_age');
           

            $stmt = $conn->prepare("SELECT * FROM registrations WHERE registrantId = ?");
            $stmt->execute([$verifyingId]);
            $registration = $stmt->fetch();

            $registrantAccountName = $registration['registrantAccountName'] ?? 'User';
            $registrantCode= $registration['registrantCode'] ?? '';
            $verificationCode= $registration['registrantVerificationCode'] ?? '';

            $mailerSubject = 'Account Verification';
            $mailerBody = <<<END
            <p>Thank you for registering and welcome to EskQuip, $registrantAccountName!</p>

            <p>An independent web application developed by Erfel Suriaga, a licensed teacher with a depth passion in learning and innovation, EskQuip serves as a venue for individuals who aspire to help learners, fellow colleagues and even schools in their educational journey by sharing articles, ready-made files, researches and online tools. </p>
            
            <p><strong> So, if you are a teacher, writer, editor, school or developer, you are very much welcome here!</strong> </p>
                        

            <p>Are you excited to use your account? Verify it now <a href="$publicFolder/verify/$registrantCode/$verificationCode">here</a>.</p>

             <p>You can also copy the link below and paste it on your browser's url bar if the previous method does not work:</p>

            <p>$publicFolder/verify/$registrantCode/$verificationCode</p>

            <br>
            <p>Best Regards,</p>
            <p>EskQuip Team</p>
            END;

            // 🔥 ACTUALLY SEND EMAIL
            $mailService = new MailService();
            $mailService->send($verifyingEmail, $mailerSubject, $mailerBody);
        }



        

    }



        public function verify ($registrantCode, $verificationCode){
            
        //Verify the account
            $conn = config('app.conn');
            $publicFolder = config('app.publicFolder');


            $stmt = $conn->prepare("SELECT * FROM registrations WHERE registrantCode = ?");
            $stmt->execute([$registrantCode]);
            $registration = $stmt->fetch();

            $verificationCodeDatabase= $registration['registrantVerificationCode'] ?? '';

            if ($verificationCode ==  $verificationCodeDatabase) {
                $verificationStatus = "Verified";

                $sql = "UPDATE registrations SET registrantVerificationStatus = ? WHERE registrantCode = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$verificationStatus,$registrantCode]);

                return redirect($publicFolder.'/login');
            } else {
                 return redirect($publicFolder.'/create-account');
            }

         
        
    }


    public function login (Request $request){

        session_start();
       
        //Login
         $conn = config('app.conn');

        if ($request->input('login_submit')) {
            $credential = htmlspecialchars($_POST["login_email_username"]);
            $pwd = htmlspecialchars($_POST["login_password"]);

           
            $responses = [];
            $responses ['error'] = [];


            if ($credential) {

            if ($pwd) {

                        $stmt = $conn->prepare("SELECT * FROM registrations WHERE registrantEmailAddress = ? or registrantUsername=?");
                        $stmt->execute([$credential,$credential]);
                        $registrant = $stmt->fetch();


                        if ($registrant) {
                            $registrantId = $registrant ['registrantId'];
                            $registrantCode = $registrant ['registrantCode'];
                            $registrantEmailAddress =  $registrant ['registrantEmailAddress'];
                            $registrantVerificationStatus =  $registrant ['registrantVerificationStatus'];
                            $registrantPassword = $registrant["registrantPassword"];

                            $responses ['user-code'] = $registrantCode;
                            $responses ['user-id'] = $registrantId;
                            $responses ['email-address'] = $registrantEmailAddress;

                            if (password_verify($pwd,$registrantPassword)) {

                                    if ($registrantVerificationStatus=="Verified") {
                                    
                                        //Check login status
                                        $stmt = $conn->prepare("SELECT * FROM registrant_activities WHERE registrant_activityUserId=? ORDER BY registrant_activityId DESC LIMIT 1");
                                        $stmt->execute([$registrantId]);
                                        $login = $stmt->fetch();

                                     


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
            
                                                $sql = "INSERT INTO registrant_activities (registrant_activityUserId,registrant_activityContent) VALUES (?,?)";
                                                $stmt = $conn->prepare($sql);

                                                $stmt->execute([
                                                        $registrantId,$activityContent
                                                ]);

                                                
                                                    
                                                    session(['registrant_code' => $registrantCode]);
                                                     
                                                    $responses ['login-status'] = 'Successful';
                                                    $responses ['error'] = 'No error';
                                
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
    }




     public function logout_ajax (Request $request){
            $conn = config('app.conn');
            $registrantId= $request->input('registrant_id');
            $activityContent='Logged out';

            $sql = "INSERT INTO registrant_activities (registrant_activityUserId,registrant_activityContent) VALUES (?,?)";
            $stmt = $conn->prepare($sql);

            $stmt->execute([
                    $registrantId,$activityContent
            ]);

            session()->flush();
          
            $responses = [];
            $responses ['status'] = 'Successful';
            $responses ['success-message'] = 'You have been logged out successfully!';

            if ($responses) {
                header('Content-Type: application/json');
                $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
                echo  $jsonResponses;
            }    
     
    }

    public function logout_email ($user_code,$token){
           
           $conn = config('app.conn');
           $publicFolder = config('app.publicFolder');

    
            $stmt = $conn->prepare("SELECT * FROM registrations WHERE registrantCode = ?");
            $stmt->execute([$user_code]);
            $user_records = $stmt->fetch();

            $realToken = $user_records ['registrantLogoutLinkToken'];
            $user_id = $user_records ['registrantId'];

            if ($realToken === $token){
                $activityContent='Logged out';

                $sql = "INSERT INTO registrant_activities (registrant_activityUserId,registrant_activityContent) VALUES (?,?)";
                $stmt = $conn->prepare($sql);

                $stmt->execute([
                        $user_id,$activityContent
                ]);


                session()->flush();
                return redirect($publicFolder.'/login');

            } else {
                return redirect($publicFolder.'/create-account');
            }

            
          

       
    }

    public function send_logout_link (Request $request){
        //Send logout link
        if ($request->input('send_logout_link_submit')) {
            $conn = config('app.conn');

            $logoutId = htmlspecialchars($_POST ['user_id']);
            $logoutEmailAddress = htmlspecialchars($_POST['email_address']);
            $publicFolder = config('app.publicFolder');

            $token = bin2hex(random_bytes(32));

            $sql = "UPDATE registrations SET registrantLogoutLinkToken = ? WHERE registrantId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$token, $logoutId]);


            $stmt = $conn->prepare("SELECT * FROM registrations WHERE registrantId = ?");
            $stmt->execute([$logoutId]);
            $user_info = $stmt->fetch();

            $user_accountName = $user_info ['registrantAccountName'];
            $user_code = $user_info ['registrantCode'];


            $mailerSubject = 'Logout Account';

            $mailerBody = <<<END
                
                <p>Hi, $user_accountName!</p 
                <p>Someone is attempting to login to your account.</p>
            
                <p>If it's you, kindly click <a href='$publicFolder/logout/$user_code/$token'> here</a> to logout so you can login.</p>

                <p>You can also copy the link below and paste it on your browser's url bar if the previous method does not work:</p>

                <p>$publicFolder/logout/$user_code/$token</p>

                <br><br>
                <p>Best Wishes,</p>
                <p>EskQuip Team</p>


                
                        
            END;

            $mailService = new MailService();
            $mailService->send($logoutEmailAddress, $mailerSubject,$mailerBody);

        }
    }



    public function get_password_reset_link (Request $request){
        //Get reset password link
        if ($request->input('get_password_reset_link_submit')) {

             $conn= config('app.conn');
              $publicFolder = config('app.publicFolder');
            $credential = htmlspecialchars($_POST["credential"]);

            $responses = [];
            $responses ['error'] = [];

            if (empty($credential)) {
                $error = "Please provide your email address or username.";
                array_push($responses ['error'], $error);

            } else {

                $stmt = $conn->prepare("SELECT * FROM registrations WHERE registrantEmailAddress = ? or registrantUsername = ?");
                $stmt->execute([$credential,$credential]);
                $registrant = $stmt->fetch();

                if (!$registrant) {
                    $error = "We could not find the record.";
                    array_push($responses ['error'], $error);
                }
            }

            if (!$responses ['error']){

                $receivingEmail = $registrant ['registrantEmailAddress'];
                $userCode = $registrant ['registrantCode'];

                $token = bin2hex(random_bytes(16));

                $tokenHash = hash("sha256",$token);

                $tokenHashExpiration = date("Y-m-d H:i:s",time()+ 60 * 30);

                $stmt = $conn->prepare("UPDATE registrations 
                        SET resetTokenHash = ?,
                            resetTokenHashExpiration = ?
                            WHERE registrantUsername=? or registrantEmailAddress = ?");

                $stmt->execute([$tokenHash,$tokenHashExpiration,$credential,$credential]);


                $mailerSubject = 'Password Reset Link';

                $mailerBody = <<<END
                
                <p>Click <a href='$publicFolder/reset-password/$userCode/$tokenHash'> here </a> to reset your password.</p>
                
                <p>The link will expire after 30 minutes.</p>
           
            END;

                $mailService = new MailService();
                $mailService->send($receivingEmail, $mailerSubject,$mailerBody);


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
    }

    //Reset password
    public function reset_password (Request $request){

        $conn=config('app.conn');
        
        if($request->input('reset_password_submit')) {
        
            $userCode = htmlspecialchars($_POST['user_code']);
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
                   
                    $stmt = $conn->prepare("UPDATE registrations 
                                    SET registrantPassword = ?
                                        WHERE registrantCode = ?");
                    $stmt->execute([$pwdHash,$userCode]);

                    $responses ['status'] = 'Successful';
                    $responses ['success-message'] = 'Password has been reset successfully. You will be redirected to the login page shortly...';
                } 
                    
                else {
                    $responses ['status'] = 'Unsuccessful';
                }


                if ($responses) {
                header('Content-Type: application/json');
                $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
                echo  $jsonResponses;
                } 
        

        }
   
    }



    //update profile details
    public function update_profile_details (Request $request){


        if ($request->update_profile_details_submit) {
    
        $conn= config('app.conn');
        
        $update_accountType = htmlspecialchars($_POST["profile_account_type"]);
        $update_registrantId = htmlspecialchars($_POST["profile_registrant_id"]);
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

    
        if ($update_accountType=='Personal') {
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

        if ($update_accountType=='School') {
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

            $stmt = $conn->prepare("SELECT * FROM registrations WHERE registrantUsername = ?");
            $stmt->execute([$update_username]);
            $username = $stmt->fetch();

            if($username) {
                if($username['registrantId']!=$update_registrantId) {
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

                $stmt = $conn->prepare("SELECT * FROM registrations WHERE registrantEmailAddress = ?");
                $stmt->execute([$update_emailAddress]);
                $emailAddress = $stmt->fetch();

                if($emailAddress) {
                    if($emailAddress['registrantId']!=$update_registrantId) {
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

                 $stmt = $conn->prepare("UPDATE registrations 
                            SET 
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
                            registrantEducationalAttainment=?,
                            registrantSchool=?,
                            registrantOccupation =?
                            WHERE registrantId = ?");
                    $stmt->execute([$update_firstName,$update_middleName,$update_lastName,$update_accountName,$update_basicRegistration,$update_registrantDescription,$update_username,$update_emailAddress,$update_mobileNumber,$update_birthdate,$update_gender, $update_civilStatus,$update_addressStreet,$update_addressBarangay,$update_addressCity,$update_addressProvince,$update_addressRegion,$update_addressCountry,$update_educationalAttainment,$update_school,$update_occupation,$update_registrantId]);

                $stmt = $conn->prepare("UPDATE other_registrations 
                            SET otherRegistrantAccountName =?
                            WHERE otherUserId = ?");
                $stmt->execute([$update_accountName,$update_registrantId]);


                $stmt = $conn->prepare( "UPDATE registrant_subscriptions 
                            SET rs_registrantAccountName =?
                            WHERE rs_userId = ?");
                $stmt->execute([$update_accountName,$update_registrantId]);


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
    }





    public function upload_for_profile(Request $request) {
        //Update profile picture or cover photo
        if ($request->upload_image_submit)  {

            $conn=config('app.conn');
            $publicFolder = config('app.publicFolder');

            
            $uploadType = htmlspecialchars($_POST['upload_type']);
            $userId = htmlspecialchars($_POST['registrant_id']);
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
                $imageFolder = '/profile-pictures/';
                $imageLinkColumn = 'registrantProfilePictureLink';
                $maxResolution = 500;
                
            }

            if ($uploadType=='Cover Photo') {
                $imageFolder = '/cover-photos/';
                $imageLinkColumn = 'registrantCoverPhotoLink';
                $maxResolution = 4000;
            
            }


            $stmt = $conn->prepare("SELECT * FROM registrations WHERE registrantId = ?");
            $stmt->execute([$userId]);
            $registrantData = $stmt->fetch();

            $registrantImageLink = $registrantData [$imageLinkColumn];
            

            if ($registrantImageLink) {
                $registrantImageLinkDelete = $publicFolder.$registrantImageLink;
                $registrantImageLinkDeleted = unlink($registrantImageLinkDelete);
            } else {
                $registrantImageLinkDelete='';
                $registrantImageLinkDeleted='';
            }

            // Create folders if they don't exist
            // if (!is_dir($imageFolder)) {
            //     mkdir($imageFolder, 0777, true);
            // }

            $imageFile = $imageFolder .str_replace(" ","_",$accountName)."-".date("YmdHis",time()).".".$imageFileNameActualExt;

            $uploadOk = 1;

            // if (move_uploaded_file($image["tmp_name"], $publicFolder.'/storage/app/public'.$imageFile)) {
            //     $uploadOk = 1;
            // } 

            // $uploaded= $image->storeAs('profile-pictures', $imageFile, 'public');

            // if ($uploaded){
            //     $uploadOk = 1;
            // }

            // $request->move(public_path('storage/profile-pictures'), $imageFile);
            $file=$request->file('profile-picture');
            $path = $file->storeAs('profile-pictures', $imageFile, 'public');
            
              if ($path){
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


                $uploadedImageFile= $imageFile;
                $imageStatus = 0;

                $stmt = $conn->prepare("UPDATE registrations
                                SET 
                                $imageLinkColumn=?
                                WHERE registrantId =?");

                $stmt->execute([$uploadedImageFile,$userId]);

                                        
                $responses ['status'] = 'Successful';
                $responses['success-message'] = 'You updated your '.$uploadType.' successfully!';

            } else {
                $responses ['status'] = 'Unsuccessful';
            }


            if ($responses) {
            header('Content-Type: application/json');
            $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
            echo  $jsonResponses;
        } 
            
            
        }

    }

//     public function uploadForProfile(Request $request)
// {
//     $responses = ['error' => []];

//     // ✅ Validate input
//     $request->validate([
//         'upload_type' => 'required|string',
//         'registrant_id' => 'required|integer',
//         'account_name' => 'nullable|string',
//         'upload_image' => 'required|image|mimes:jpeg,jpg|max:10240' // 10MB
//     ]);

//     $uploadType = $request->upload_type;
//     $userId = $request->registrant_id;
//     $accountName = $request->account_name;

//     // ✅ Determine settings
//     if ($uploadType == 'Profile Picture') {
//         $imageFolder = 'profile-pictures';
//         $imageColumn = 'registrantProfilePictureLink';
//         $maxResolution = 500;
//     } elseif ($uploadType == 'Cover Photo') {
//         $imageFolder = 'cover-photos';
//         $imageColumn = 'registrantCoverPhotoLink';
//         $maxResolution = 4000;
//     } else {
//         return response()->json([
//             'status' => 'Unsuccessful',
//             'error' => ['Invalid upload type']
//         ]);
//     }

//     // ✅ Get user
//     $user = DB::table('registrations')
//         ->where('registrantId', $userId)
//         ->first();

//     if (!$user) {
//         return response()->json([
//             'status' => 'Unsuccessful',
//             'error' => ['User not found']
//         ]);
//     }

//     // ✅ Delete old image if exists
//     if (!empty($user->$imageColumn)) {
//         Storage::disk('public')->delete($user->$imageColumn);
//     }

//     // ✅ Prepare file
//     $file = $request->file('upload_image');
//     $filename = Str::slug($accountName) . '-' . now()->format('YmdHis') . '.jpg';
//     $path = $imageFolder . '/' . $filename;

//     // ✅ Resize + Crop (using Intervention Image)
//     $image = Image::make($file);

//     if ($uploadType == 'Profile Picture') {
//         // square crop
//         $image->fit($maxResolution, $maxResolution);
//     } else {
//         // cover photo (wide)
//         $image->fit($maxResolution, $maxResolution / 3);
//     }

//     // ✅ Save image
//     Storage::disk('public')->put($path, (string) $image->encode('jpg', 90));

//     // ✅ Update database
//     DB::table('registrations')
//         ->where('registrantId', $userId)
//         ->update([
//             $imageColumn => $path
//         ]);

//     return response()->json([
//         'status' => 'Successful',
//         'path' => $path,
//         'success-message' => "You updated your $uploadType successfully!"
//     ]);
// }


// public function upload_for_profile(Request $request)
// {
//     $responses = ['error' => []];

//     // ✅ Validate inputs
//     $request->validate([
//         'upload_type' => 'required|string|in:Profile Picture,Cover Photo',
//         'registrant_id' => 'required|integer|exists:registrations,registrantId',
//         'account_name' => 'nullable|string',
//         'upload_image' => 'required|image|mimes:jpeg,jpg|max:10240', // 10 MB
//     ]);

//     $uploadType = $request->upload_type;
//     $userId = $request->registrant_id;
//     $accountName = $request->account_name ?? 'user';

//     // ✅ Set folder and max resolution
//     if ($uploadType == 'Profile Picture') {
//         $imageFolder = 'profile-pictures';
//         $imageColumn = 'registrantProfilePictureLink';
//         $maxResolution = 500;
//     } else { // Cover Photo
//         $imageFolder = 'cover-photos';
//         $imageColumn = 'registrantCoverPhotoLink';
//         $maxResolution = 4000;
//     }

//     // ✅ Get user
//     $user = DB::table('registrations')->where('registrantId', $userId)->first();

//     // ✅ Delete old image if exists
//     if (!empty($user->$imageColumn)) {
//         Storage::disk('public')->delete($user->$imageColumn);
//     }

//     // ✅ Prepare new filename
//     $filename = Str::slug($accountName) . '-' . now()->format('YmdHis') . '.jpg';
//     $path = $imageFolder . '/' . $filename;

//     // Initialize ImageManager (GD is default, you can skip driver)
//     // $manager = new ImageManager(); // ✅ you must pass an array
  
//     $manager = new ImageManager('driver','gd');
//     // Read uploaded file
//     $image = $manager->read($request->file('upload_image')); // ✅ read() instead of make()

//     // Resize and crop
//     if ($uploadType === 'Profile Picture') {
//         $image->cover(500, 500); // square
//     } else {
//         $image->cover(4000, 4000 / 3); // wide for cover photo
//     }

//     // Save to storage
//     Storage::disk('public')->put($path, (string) $image->toJpeg(90)); // encode to jpg

//     // ✅ Update database
//     DB::table('registrations')
//         ->where('registrantId', $userId)
//         ->update([$imageColumn => $path]);

//     // ✅ Return JSON response
//     return response()->json([
//         'status' => 'Successful',
//         'path' => $path,
//         'success-message' => "You updated your $uploadType successfully!"
//     ]);
// }


    public function check_other_registration (Request $request){
        //Check other registration
        if ($request->check_other_registration_submit) {

            $conn=config('app.conn');

            $regType = htmlspecialchars($_POST['regtype']);
            $registrantId = htmlspecialchars($_POST['registrant_id']);

            $responses = [];
            $responses ['have-registration'] = false; 

            $stmt =$conn->prepare("SELECT * FROM other_registrations WHERE otherType = ? AND otherUserId = ?");
            $stmt->execute([$regType,$registrantId]);
            $checkedRegistration= $stmt->fetch();

        if ($checkedRegistration) {
        $responses ['have-registration'] = true;   
        }

            if ($responses) {
            header('Content-Type: application/json');
            $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
            echo  $jsonResponses;
        } 

        }
    }


    public function other_registration_submit (Request $request){
        //Submit other registration
        if (isset($_POST['other_registration_submit'])) {
        
        $conn= config('app.conn');
        
        $regType = htmlspecialchars($_POST['regtype']);
        $registrantId = htmlspecialchars($_POST['registrant_id']);
        $accountName= htmlspecialchars($_POST['account_name']);

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
    }




    public function get_submissions (Request $request){
        //Get submissions
        if (isset($_POST['get_submissions_submit'])) {
        
        $conn=config('app.conn');
        $privateFolder=config('app.privateFolder');

        $regType = htmlspecialchars($_POST['regtype']);
        $registrantId = htmlspecialchars($_POST['registrant_id']);


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
    }


    public function get_users (){
        if (isset($_POST['get_users_submit'])){

            $conn= config('app.conn');
            $registrantCode= htmlspecialchars($_POST['registrant_code']);

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
    }



    public function get_user_info(){
        if(isset($_POST['get_user_info_submit'])){
        
        $conn=config('app.conn');

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
    }


    public function get_my_seller_details (){
        if(isset($_POST['get_my_seller_details_submit'])){

        $conn=config('app.conn');
        $registrantId = htmlspecialchars($_POST['registrant_id']);

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
    }


    public function update_my_seller_details (){
        if(isset($_POST['update_my_seller_details_submit'])){
        
        $conn=config('app.conn');

        $registrantId = htmlspecialchars($_POST['registrant_id']);

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
    }


    public function check_password_super_manager_registration (){
        if (isset($_POST['check_password_for_super_manager_registration_update_submit'])){
        
        $conn=config('app.conn');

        $registrantId = htmlspecialchars($_POST['registrant_id']);
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
    }

    public function get_thread_messages (){
        if (isset($_POST['get_thread_messages_submit'])){

        $conn=config('app.conn');

        $registrantId=htmlspecialchars($_POST['registrant_id']);
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

    }


    public function send_message (){
        if (isset($_POST['send_message_submit'])){

        $conn= config('app.conn');
        $currentTime = config('app.currentTime');
        
        $messageMode = htmlspecialchars($_POST['message_mode']);
        $adminMessageThreadCode = htmlspecialchars($_POST['admin_message_thread_code']);
        $recipientCode = htmlspecialchars($_POST['recipient_code']);
        $senderCode = htmlspecialchars($_POST['sender_code']);
        $registrantId = htmlspecialchars($_POST['registrant_id']);

    
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
    }


    public function get_recipient_records (){
        if (isset($_POST['get_recipient_records_submit'])) {
        
        $conn=config('app.conn');
        $publicFolder = config('app.publicFolder');
        $privateFolder= config('app.privateFolder');

        $recipientCode = htmlspecialchars($_POST['recipient_code']);
        $registrantId = htmlspecialchars($_POST['registrant_id']);

        $responses = [];

        if ($recipientCode) {

                $sqlRecipientRecords = "SELECT * FROM registrations where registrantCode = $recipientCode";
                $sqlRecipientRecordsResults = mysqli_query($conn,$sqlRecipientRecords);
                $recipientRecords =  $sqlRecipientRecordsResults->fetch_assoc();


                if($recipientRecords) {
                
                    $recipientType =  $recipientRecords['registrantAccountType'];
                    $recipientAccountName =  $recipientRecords['registrantAccountName'];
                    $recipientRegistrantDescription = $recipientRecords ['registrantDescription'];

                
                    $recipientProfilePictureLink =  $recipientRecords['registrantProfilePictureLink'] ? $privateFolder. $recipientRecords['registrantProfilePictureLink'] : $publicFolder."/images/user.svg";

                
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
    }


    public function get_unread_message (){
        if (isset($_POST['get_unread_messages_submit'])){

        $conn =config('app.conn');

        $registrantId= htmlspecialchars($_POST['registrant_id']);
        $registrantCode= htmlspecialchars($_POST['registrant_code']);
        $websiteManagerMessageManagerRegistration = htmlspecialchars($_POST['message_manager_registration']);
        $websiteManagerSuperManagerRegistration=htmlspecialchars($_POST['super_manager_registration']);

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
    }
}




    




    

        

        

        
