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
    
    public function login (Request $request){
         if ($request->input('login_submit')) {

         session_start();

          $conn= config('app.conn');

           $credential = htmlspecialchars($_POST["login_email_username"]);
           $pwd = htmlspecialchars($_POST["login_password"]);

           $loginErrors = [];
           $responses = [];


           if ($credential) {

            if ($pwd) {

                    $stmt = $conn->prepare(
                     $stmt = "SELECT * FROM users WHERE email_address = ? or username = ?");
                     $stmt->execute([$credential,$credential]);
                     $user=$stmt->fetch();


                    if ($user) {
                            $userId = $user ['id'];
                            $registrantEmailAddress =  $user ['email_address'];
                            $registrantVerificationStatus =  $user ['verification'];
                            $registrantPassword = $user["password"];

                           

                            if (password_verify($pwd,$registrantPassword)) {

                                $_SESSION ['temporary-session-userid'] = $userId;
                                $_SESSION ['temporary-session-email-address'] = $registrantEmailAddress;

                                $userid_email ['temporary-userid'] = $userId;
                                $userid_email ['temporary-email-address'] = $registrantEmailAddress;

                                

                                  if ($registrantVerificationStatus=="Verified") {
                        
                                      $stmt = $conn->prepare("SELECT * FROM user_logs WHERE user_id = ? ORDER BY id DESC LIMIT 1");
                                      $stmt->execute([$userId]);

                                      $logged= $stmt->fetch();



                                      if ($logged) {
                                        $activity = $logged['activity'];

                                          if ($activity=='Logged in') {

                                          

                                          $error = 'You are logged in in the other device. Open the email sent to log out.';
                                          array_push($loginErrors,$error);
                                          array_push($responses,$error);

                                        
                                            $loginErrors ['error'] = $error;
                                            $responses ['error'] = $error;
                                            $responses ['temporary-session-userid'] = $userId;
                                            $responses ['temporary-session-email-address'] = $registrantEmailAddress;
  
                                          } 
                                      } 
                                      
                                      if (!$logged || $activity != 'Logged in') {
                                          
                                          $activity='Logged in';
                                          
                                            $stmt= $conn->prepare("INSERT INTO user_logs (user_id,activity) VALUES (?, ?)");
                                            $stmt->execute([ $userId,$activity]);
                                          
                      
                                            session(['user_id' => $userId]);
                                        
                                            $error = 'No error';

                                            array_push($loginErrors,$error);
                                            array_push($responses,$error); 

                                            $loginErrors ['error'] = $error;
                                            $responses ['error'] = 'No error';
                                  
                                           
                                      }

                                       
                                          
                                            



                                    } else {
                                            $error = 'Your account is not yet verified. Check your email to verify.';
                                         
                                            array_push($loginErrors,$error);
                                            array_push($responses,$error); 
                                            // array_push($responses,json_encode($userid_email));   
                                            $loginErrors ['error'] = $error;
                                            $responses ['error'] = $error;
                                            $responses ['temporary-session-userid'] = $userId;
                                            $responses ['temporary-session-email-address'] = $registrantEmailAddress;
                                            
                                    }

              
                                  } else {
                                      $error = 'The password is not correct.';
                                      array_push($loginErrors,$error);
                                      array_push($responses,$error);
                                      $loginErrors ['error'] = $error;
                                      $responses ['error'] = $error;
                                  }
                        

                    } else{

                        $error = 'Credential not found.';
                        array_push($loginErrors,$error);
                        array_push($responses,$error);
                        $loginErrors ['error'] = $error;
                        $responses ['error'] = $error;
                    }

                } else {
                  $error = 'Please provide your password.';
                  array_push($loginErrors,$error);
                  array_push($responses,$error);
                  $loginErrors ['error'] = $error;
                  $responses ['error'] = $error;  
                }

           } else {
                
            $error = 'Please provide your credential.';
            
            array_push($loginErrors,$error);
            array_push($responses,$error);
            $loginErrors ['error'] = $error;
            $responses ['error'] = $error;
           }

         

          if ($responses) {
             
            header('Content-Type: application/json');
            $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
            
            echo  $jsonResponses;
           } 

        }        
    }



    public function send_logout_link (Request $request){
      if ($request->input('send_logout_link_submit')) {

             $conn = config('app.conn');   

            $logoutUserId = htmlspecialchars($_POST ['logout_userid']);
            $logoutEmailAddress = htmlspecialchars($_POST ['logout_email_address']);

            $publicFolder = config('app.publicFolder');

            $token = bin2hex(random_bytes(32));
            $tokenExpiration = date("Y-m-d H:i:s",time()+ 60 * 30);

            $stmt = $conn->prepare("UPDATE users SET logout_token = ?, logout_token_expiration=? WHERE id = ?") ;
            $stmt->execute([$token, $tokenExpiration,$logoutUserId]);


            $stmt = $conn->prepare("SELECT name FROM users WHERE id= ?");
            $stmt->execute([$logoutUserId]);
            $user_name=$stmt->fetchColumn();

            $mailerSubject = 'Logout Account';

            $mailerBody = <<<END
                
                <p>Hi, $user_name!</p 
                <p>Someone is attempting to login to your account.</p>
            
                <p>If it's you, kindly click <a href='$publicFolder/logout/$token+$logoutUserId'> here</a> to logout so you can login.</p>

                <p>You can also copy the link below and paste it on your browser's url bar if the previous method does not work:</p>

                <p>$publicFolder/logout/$token+$logoutUserId</p>

                <br><br>
                <p>Best Wishes,</p>
                <p>Maf Write Hub Team</p>


                
                        
            END;

            $mailService = new MailService();
            $mailService->send($logoutEmailAddress, $mailerSubject,$mailerBody);


      }
    }



    public function send_verification_link (Request $request){
      if($request->input('send_verification_link_submit')){
        $conn= config('app.conn');

        $verifyingId = $_POST ['verifying_userid'];
        $verifyingEmail = $_POST ['verifying_email_address'];

       
            $publicFolder = config('app.publicFolder');

            $token = bin2hex(random_bytes(32));

            $tokenExpiration = date("Y-m-d H:i:s",time()+ 60 * 30);

            $stmt = $conn->prepare("UPDATE users SET verification_token = ?,verification_token_expiration = ? WHERE id = ?") ;
            $stmt->execute([$token, $tokenExpiration, $verifyingId]);


            $stmt = $conn->prepare("SELECT name FROM users WHERE id= ?");
            $stmt->execute([$verifyingId]);
            $user_name=$stmt->fetchColumn();

            $mailerSubject = 'Verify Your Account';

            $mailerBody = <<<END
                
                <p>Hi, $user_name!</p 
                <p>Someone is attempting to login to your account.</p>
            
                <p>If it's you, kindly click <a href='$publicFolder/verify/$token+$verifyingId'> here</a> to logout so you can login.</p>

                <p>You can also copy the link below and paste it on your browser's url bar if the previous method does not work:</p>

                <p>$publicFolder/verify/$token+$verifyingId</p>

                <br><br>
                <p>Best Wishes,</p>
                <p>Maf Write Hub Team</p>
           
            END;

            $mailService = new MailService();
            $mailService->send($verifyingEmail, $mailerSubject,$mailerBody);
      
    }


   
}

    public function logout_email($token,$user_id){

        $conn= config('app.conn');

        $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
        $stmt->execute([$user_id]);

        $user_info = $stmt->fetch();

        $user_real_logout_token = $user_info['logout_token'];
        $user_real_logout_token_expiration = strtotime($user_info['logout_token_expiration']);


        if ($token==$user_real_logout_token) {

            if ($user_real_logout_token_expiration-time()>0) {

                session()->flush();

                $activity='Logged out';

                $stmt= $conn->prepare("INSERT INTO user_logs (user_id,activity) VALUES (?, ?)");
                $stmt->execute([$user_id,$activity]);
                return redirect(route('home'))->with('logout_successful',true);

            } else {
              return redirect(route('home'))->with('log_out_token_expired',true);
            }

        } else {
          return redirect(route('home'))->with('log_out_token_invalid',true);
        }

   
    }


    public function logout_ajax(Request $request){

    if ($request->input('logout_submit')) {
          $conn= config('app.conn');

          $user_id = session('user_id');

          if ($user_id){

                session()->flush();
                $activity='Logged out';
                $stmt= $conn->prepare("INSERT INTO user_logs (user_id,activity) VALUES (?, ?)");
                $stmt->execute([$user_id,$activity]);
                
                session()->flush();
          
                $responses = [];
                $responses ['status'] = 'Successful';
                
                if ($responses) {
                    header('Content-Type: application/json');
                    $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
                    echo  $jsonResponses;
                } 

          } else {
              return redirect(route('home'))->with('no_user_id',true);
          }

    }
        

        
    }


    
    public function verify ($token,$user_id){
      $conn= config('app.conn');

        $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
        $stmt->execute([$user_id]);

        $user_info = $stmt->fetch();

        $user_real_verification_token = $user_info['verification_token'];
        $user_real_verification_token_expiration = strtotime($user_info['verification_token_expiration']);


        if ($token==$user_real_verification_token) {

            if ($user_real_verification_token_expiration-time()>0) {

                $status='Verified';

                $stmt= $conn->prepare("UPDATE users SET verification=? WHERE id=?");
                $stmt->execute([$status,$user_id]);
                return redirect(route('home'))->with('verification_successful',true);

            } else {
              return redirect(route('home'))->with('verification_token_expired',true);
            }

        } else {
          return redirect(route('home'))->with('verification_token_invalid',true);
        }
    }


    public function get_password_reset_otp (Request $request){
        if ($request->input('get_password_link_submit')) {

          $conn= config('app.conn');
          $publicFolder =config('app.publicFolder');

          $credential = htmlspecialchars($_POST['password_reset_email_username']);

          $responses = [];
          $responses ['error']=[];

         if ($credential) {

              $stmt = $conn->prepare("SELECT * FROM users WHERE email_address = ? or username = ?");
              $stmt->execute([$credential,$credential]);
              $user = $stmt->fetch();

              if ($user) {

              $user_email_address = $user ['email_address'];
            
              $user_name = $user ['name'];

              $otp = mt_rand(100000, 999999);

            
          
              $otpExpiration = date("Y-m-d H:i:s",time()+ 60 * 30);

              $stmt = $conn->prepare( "UPDATE users 
                      SET password_reset_otp = ?,
                          password_reset_otp_expiration = ?
                          WHERE username=? or email_address = ?");
              $stmt->execute([$otp,$otpExpiration,$credential,$credential]);


              $mailerSubject='Password Reset OTP';
              $mailerBody= <<<END

                <p>Hello, $user_name.</p>

                <p>Here is the OTP to reset your password:</p>

                <h2>$otp</h2>

                END;

                $mailService = new MailService();
                $mailService->send( $user_email_address, $mailerSubject,$mailerBody);

                $error = 'No error';
                array_push($responses['error'],$error) ; 

              } else {
                      
                      $error = 'Credential not found.';
                      array_push($responses['error'],$error) ;
                  
              }

          } else {
               $error = 'Please provide your credential.';
              array_push($responses['error'],$error) ;
          }

          
          if ($responses) {
              
          header('Content-Type: application/json');
          $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
          
          echo  $jsonResponses;
          } 

  }
}

public function check_password_reset_otp (Request $request){

  if ($request->input('check_password_reset_otp_submit')) {
    $conn=config('app.conn');


    $otp = htmlspecialchars($_POST['otp']);
    $credential = htmlspecialchars($_POST['credential']);

    $responses = [];
    $responses ['error']=[];

    if ($otp) {
      $stmt = $conn->prepare("SELECT * FROM users WHERE username=? OR email_address=?");
      $stmt->execute([$credential,$credential]);

      $user = $stmt->fetch();

      if ($user){
        $real_user_otp = $user['password_reset_otp'];
        $real_user_otp_expiration = strtotime($user['password_reset_otp_expiration']);

        if ($real_user_otp==$otp){
            if ($real_user_otp_expiration-time()>0){
                 $error= 'No error';
              array_push($responses['error'],$error);
            } else {
               $error= 'The OTP is expired.';
              array_push($responses['error'],$error);
            }

        } else{
           $error= 'The OTP is not correct.';
          array_push($responses['error'],$error);
        }

      } else {
         $error= 'Credential not found';
        array_push($responses['error'],$error);
      }


    } else {
      $error= 'Please enter the OTP.';
      array_push($responses['error'],$error);
    }

    


    if ($responses) {
             
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        
        echo  $jsonResponses;
      } 




  }
  
}

 public function reset_password(Request $request){
    if ($request->input('reset_password_submit')) {
      $conn= config('app.conn');

      $newPassword= htmlspecialchars($_POST['new_password']);
      $newPasswordRetyped= htmlspecialchars($_POST['new_password_retyped']);
      $credential= htmlspecialchars($_POST['credential']);



      $responses = [];
      $responses['error']=[];

      $stmt = $conn->prepare("SELECT * FROM users WHERE username=? OR email_address=?");
      $stmt->execute([$credential,$credential]);

      $user = $stmt->fetch();

      if ($user) {

        if ($newPassword) {

            if ($newPasswordRetyped){
                  if ($newPassword==$newPasswordRetyped) {
                   
                    $pwdHash = password_hash($newPassword, PASSWORD_DEFAULT);  

                     $stmt = $conn->prepare("UPDATE users 
                                    SET password = ?
                                        WHERE email_address = ?
                                        or username=?");
                    $stmt->execute([$pwdHash,$credential,$credential]);

                    $error = 'No error';
                    array_push($responses['error'],$error);

                  } else {
                     $error = 'Passwords do not match.';
                    array_push($responses ['error'], $error);
                  }
             
            } else {
               $error = 'Please retype your new password.';
              array_push($responses['error'],$error);
            }


        } else {
          $error = 'Please type your new password.';
          array_push($responses['error'],$error);
        }


      }


        if ($responses) {
             
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        
        echo  $jsonResponses;
      } 








    }





 }




      

}