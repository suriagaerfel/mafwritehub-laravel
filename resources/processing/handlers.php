<?php 


// if ($pageName=="My Account") { 
//     // if(!$loggedIn){
//     // header('Location:'.$publicFolder.'/login');
//     // }
// }

// if ($pageName=="File Purchase") { 
//     if($loggedIn){
//     header('Location:'.$publicFolder.'/login');
//     }
// }



// if ($pageName=="Messages") { 
//     if(!$loggedIn){
//     header('Location:'.$publicFolder.'/login');
//     } 
// }

// if ($pageName=="Notifications") { 
//     if(!$loggedIn){
//     header('Location:'.$publicFolder.'/login');
//     } else {
//         if(!$developerRegistration){
//     header('Location:'.$publicFolder.'/account');
//     }
//     } 
// }



// if ($pageName=="Login") { 
//     if ($loggedIn) {
//     header('Location:'.$publicFolder.'/account');
//     }
// }

// if ($pageName=="Create Account") { 
//      if ($loggedIn) {
//     header('Location:'.$publicFolder.'/account');
//     }
// }

// if ($pageName=="Get Password Reset Link") { 
//      if ($loggedIn) {
//     header('Location:'.$publicFolder.'/account');
//     }
// }

// if ($pageName=="Workspace - Teacher") {
//     if (!$teacherRegistration) {
//     header('Location:'.$publicFolder.'/account');
//     }   
// }

// if ($pageName=="Workspace - Writer") {
//     if (!$writerRegistration) {
//     header('Location:'.$publicFolder.'/account');
//     }  

// }

// if ($pageName=="Workspace - Editor") {
//     if (!$editorRegistration) { 
//     header('Location:'.$publicFolder.'/account');
//     }  

// }

// if ($pageName=="Workspace - Website Manager") {
//     if (!$websiteManagerRegistration) {
//     header('Location:'.$publicFolder.'/account');
//     }   
// }

// if ($pageName=="Workspace - Data Analyst") {
//     if (!$dataAnalystRegistration) { 
//     header('Location:'.$publicFolder.'/account');
//     }
// }


// if ($pageName=="Workspace - Developer") {
//     if (!$developerRegistration) {
//     header('Location:'.$publicFolder.'/account');
//     }   

// }


// if ($pageName=="Dashboard - Funder") {
//     if (!$funderRegistration) {
//     header('Location:'.$publicFolder.'/account');
//     }
    
// }

// if ($pageName=="School Workspace - Researches") {
//     if ($type!='School') {
//     header('Location:'.$publicFolder.'/account');
//     }  
// }




// if ($pageName =="Reset Password") {
//     $userIdReset = isset($_GET['userid']) ? htmlspecialchars($_GET['userid']) : "" ;
//     $tokenReset = isset($_GET['token']) ? htmlspecialchars($_GET['token']) : "" ;
    
//     $sqlValidate = "SELECT * FROM registrations WHERE registrantId= '$userIdReset'";
//     $sqlValidateResult = mysqli_query($conn,$sqlValidate);
//     $validated = $sqlValidateResult->fetch_assoc();

//     if ($validated) {
//         $expiration = strtotime($validated['resetTokenHashExpiration']);
//         $tokenHash = $validated['resetTokenHash'];

//         if ($tokenReset==$tokenHash) {
                         
//             if ($expiration-time()>0) {
//                 $_SESSION ['reset-now'] = "yes";

//             } else {
//                 $_SESSION ['link-expired'] = "yes";
//                 header('Location:'.$publicFolder.'/get-password-reset-link');

//             }
            
//         } else {
//             $_SESSION['its-not-you'] = "yes";
//             header('Location:'.$publicFolder.'/get-password-reset-link');     
//         }

//     } else {
//         $_SESSION['account-not-found'] = "yes";
//         header('Location:'.$publicFolder.'/create-account');
        
//     }

// }







?>






<?php 
    //THIS AREA IF FOR HANDLING ERRORS.
    // error_reporting(E_ALL);
    // ini_set("display_errors", 1);

    // function customErrorHandler ($errno,$errstr,$errfile,$errline) {
    //     $message = "Error : [$errno] $errstr - $errfile : $errline";
    //     error_log($message .PHP_EOL,3, PUBLIC_PATH.'/error_log.txt');
    // }

    // set_error_handler("customErrorHandler");
?>