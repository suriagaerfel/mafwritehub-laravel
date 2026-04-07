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
}




    




    

        

        

        
