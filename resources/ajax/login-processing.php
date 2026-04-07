<?php

require '../../initialize.php';
require '../../database.php';


        if (isset($_POST["login_submit"])) {

         

           $credential = htmlspecialchars($_POST["login_email_username"]);
           $pwd = htmlspecialchars($_POST["login_password"]);

           $loginErrors = [];
           $responses = [];


           if ($credential) {

            if ($pwd) {

                     $sqlRegistration = "SELECT * FROM users WHERE email_address = '$credential' or username = '$credential'";

                    $result = mysqli_query($conn, $sqlRegistration);
                    $registrant= $result->fetch_assoc();

                    if ($registrant) {
                            $registrantId = $registrant ['id'];
                            $registrantEmailAddress =  $registrant ['email_address'];
                            $registrantVerificationStatus =  $registrant ['verification'];
                            $registrantPassword = $registrant["password"];

                           

                            if (password_verify($pwd,$registrantPassword)) {

                                $_SESSION ['temporary-session-userid'] = $registrantId;
                                $_SESSION ['temporary-session-email-address'] = $registrantEmailAddress;

                                $userid_email ['temporary-userid'] = $registrantId;
                                $userid_email ['temporary-email-address'] = $registrantEmailAddress;

                                

                                  if ($registrantVerificationStatus=="Verified") {
                        
                                  
                                   
                                      $sqlCheckLog = "SELECT * FROM user_logs WHERE user_id = '$registrantId' ORDER BY id DESC LIMIT 1";
                                      $sqlCheckLogResult = mysqli_query($conn,$sqlCheckLog);
                                      $logged=$sqlCheckLogResult->fetch_assoc();

                                      if ($logged) {
                                        $activity = $logged['activity'];

                                          if ($activity=='Logged in') {

                                          

                                          $error = 'You are logged in in the other device. Open the email sent to log out.';
                                          array_push($loginErrors,$error);
                                          array_push($responses,$error);

                                        
                                            $loginErrors ['error'] = $error;
                                            $responses ['error'] = $error;
                                            $responses ['temporary-session-userid'] = $registrantId;
                                            $responses ['temporary-session-email-address'] = $registrantEmailAddress;
  
                                          } 
                                      } 
                                      
                                      if (!$logged || $activity != 'Logged in') {
                                          
                                          $activity='Logged in';
                                          
                                          $sqlInsertActivity = "INSERT INTO user_logs (user_id,activity) VALUES (?, ?)";
                                          $stmt = mysqli_stmt_init($conn);

                                          $prepareStmt = mysqli_stmt_prepare($stmt,$sqlInsertActivity);

                                            if ($prepareStmt) {
                                                mysqli_stmt_bind_param($stmt,"ss", $registrantId,$activity);
                                                mysqli_stmt_execute($stmt);

                                                $_SESSION['id'] = $registrantId;
                                                
                                                $error = 'No error';

                                                array_push($loginErrors,$error);
                                                array_push($responses,$error); 

                                                $loginErrors ['error'] = $error;
                                                $responses ['error'] = 'No error';
                                    
                                            }
                                      }

                                       
                                          
                                            



                                    } else {
                                            $error = 'Your account is not yet verified. Check your email to verify.';
                                         
                                            array_push($loginErrors,$error);
                                            array_push($responses,$error); 
                                            // array_push($responses,json_encode($userid_email));   
                                            $loginErrors ['error'] = $error;
                                            $responses ['error'] = $error;
                                            $responses ['temporary-session-userid'] = $registrantId;
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

           


           


          //  if ($responses) {
          //     foreach ($responses as $key => $value) {
          //       echo "$key : $value <br>";         
          //     }  
          //  } 


          // // $array = ['key' => 'value'];
          // $json = json_encode($responses,JSON_PRETTY_PRINT);

          // echo $jason;


          //  if ($responses) {
          //     foreach ($responses as $key => $value) {
          //       echo "$key : $value <br>";         
          //     }  
          //  } 


          if ($responses) {
              // foreach ($responses as $key => $value) {
              //   echo "$key : $value <br>";         
              // }  

              // echo json_encode($responses);


              // foreach ($responses as $response) {
              //   echo $response."<br>";         
              // } 
            header('Content-Type: application/json');
            $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
            
            echo  $jsonResponses;
           } 

        }        