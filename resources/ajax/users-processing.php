<?php 
 require '../../initialize.php';
require '../../database.php';


// if (isset($_POST['get_profile_submit'])) {
//    $sqlProfile = "SELECT * FROM users WHERE id=$registrantId";
//    $sqlProfileResult = mysqli_query($conn,$sqlProfile);
//    $profile= $sqlProfileResult ->fetch_assoc();

//    if ($profile){
//       $profileDescription = $profile ['description'];
//       $profileFirstName = $profile ['first_name'];
//       $profileMiddleName = $profile ['middle_name'];
//       $profileLastName = $profile ['last_name'];
//       $profileEmailAddress = $profile ['email_address'];
//       $profileUsername = $profile ['username'];
//       $profileAccountType = $profile ['type'];

//       $responses = [];
//       $responses ['profile-description'] = $profileDescription;
//       $responses ['profile-first-name'] = $profileFirstName;
//       $responses ['profile-middle-name'] = $profileMiddleName;
//       $responses ['profile-last-name'] = $profileLastName;
//       $responses ['profile-email-address'] = $profileEmailAddress;
//       $responses ['profile-username'] = $profileUsername;
//       $responses ['profile-account-type'] = $profileAccountType;


//       if ($responses) {
//       header('Content-Type: application/json');
//       $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
//       echo  $jsonResponses;
//       } else {
//          echo '';
//       }

//    }
   
// }

// if (isset($_POST['profile_submit'])) {
//    $profileFirstName = htmlspecialchars($_POST['profile_first_name']);
//    $profileMiddleName = htmlspecialchars($_POST['profile_middle_name']);
//    $profileLastName = htmlspecialchars($_POST['profile_last_name']);
//    $profileEmailAddress = htmlspecialchars($_POST['profile_email_address']);
//    $profileUsername= htmlspecialchars($_POST['profile_username']);
//    $profileAccountType = htmlspecialchars($_POST['profile_account_type']);

//    $letterOnlyPattern ='/^[a-zA-Z ]+$/';

//    $responses = [];
//    $responses['error'] = [];
   

//    if (!$profileFirstName) {
//       $error = 'Please enter first name.';
//       array_push($responses['error'],$error);
//    } else {
//        if (!preg_match($letterOnlyPattern,$profileFirstName)) {
//         $error = 'First name is not valid.';
//          array_push($responses['error'],$error);
//         }
//    }


//    if (!$profileLastName) {
//       $error = 'Please enter last name.';
//          array_push($responses['error'],$error);
//    } else {
//        if (!preg_match($letterOnlyPattern,$profileLastName)) {
//          $error = 'Last name is not valid';
//          array_push($responses['error'],$error);
//         }
//    }


//    if (!$profileEmailAddress) {
//       $error = 'Please enter email address.';
//       array_push($responses['error'],$error);
//    } else {
//        if (!filter_var($profileEmailAddress, FILTER_VALIDATE_EMAIL)) { 
//        $error = 'Email address is not valid.';
//      array_push($responses['error'],$error);
//       }else {
         
//             $sqlUserEmailAddress = "SELECT * FROM users WHERE email_address = '$profileEmailAddress'";
//             $sqlUserEmailAddressResult = mysqli_query($conn, $sqlUserEmailAddress);
//             $userEmailAddress = $sqlUserEmailAddressResult->fetch_assoc();

//             if ($userEmailAddress) { 
//                $userEmailAddress_Id = $userEmailAddress ['id'];

//                if ($registrantId !== $userEmailAddress_Id){
//                   $error = 'Email address is already used.';
//                   array_push($responses['error'],$error);
//                }
//             }
       
        
//     }
//    }


//    if (!$profileUsername) {
//       $error = 'Please enter username.';
//       array_push($responses['error'],$error);
//       } else {
//        $sqlUserUsername = "SELECT * FROM users WHERE username = '$profileUsername'";
//       $sqlUserUsernameResult = mysqli_query($conn, $sqlUserUsername);
//       $userUsername = $sqlUserUsernameResult->fetch_assoc();

    

//     if ($userUsername) {
//          $userUsername_Id = $userUsername ['id'];

//          if ($registrantId != $userUsername_Id) {
//             $error = 'Username is already used.';
//             array_push($responses['error'],$error);
//          }
//          }
       
       
//    }



//       if (!$profileAccountType) {
//       $error = 'Please select type.';
//       array_push($responses['error'],$error);
//       } 


//      if (!$responses['error']) {

//       $sqlUpdateProfile = "UPDATE users
//                             SET 
//                             first_name=?,
//                             middle_name=?,
//                             last_name=?,
//                             email_address=?,
//                            username=?,
//                             type=?
//                             WHERE id = '$registrantId'";

//       $stmt = mysqli_stmt_init($conn);

//       $prepareStmt = mysqli_stmt_prepare($stmt,$sqlUpdateProfile);

//       if ($prepareStmt) {
//          mysqli_stmt_bind_param($stmt,"ssssss", $profileFirstName,$profileMiddleName,$profileLastName,$profileEmailAddress,$profileUsername,$profileAccountType);
//          mysqli_stmt_execute($stmt);

//          $responses['status'] = 'Successful'; 
//       } 


//      } else {
//          $responses['status'] = 'Unsuccessful'; 
//       }
   
   
   
   
//       if ($responses) {
//             header('Content-Type: application/json');
//             $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
//             echo  $jsonResponses;
//       }

  
// }

//Update profile picture or cover photo
if (isset($_POST['upload_image_submit']))  {
    
    $uploadType = htmlspecialchars($_POST['upload_type']);
    $userId = $registrantId;
  
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
            $error= 'Please select an image in any of these formats: '. implode(', ',$allowedImage);
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
        $imageLinkColumn = 'profile_picture_link';
        $maxResolution = 500;
        
    }

  

    $sqlRegistrantData = "SELECT * FROM users WHERE id = '$userId'";
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


    imagejpeg($newCropImage,$imageFile,90);
    }


  

    $uploadedImageFile= substr($imageFile,5);
    $imageStatus = 0;

    $sqlUpdateImage = "UPDATE users
                        SET 
                        $imageLinkColumn=?
                        WHERE id = $userId";


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












// if ($type == 'Owner') {
// if (isset($_POST['get_users_submit'])) {

  
// $query = htmlspecialchars($_POST ['query']);  
 
// $limit = 5;
//   $currentPage = isset($_POST ['page'])? (int) $_POST ['page'] : 1;

//   if ($query) {
//       $currentPage = 1;
//   }
//   $offset = ($currentPage - 1) * $limit;

  
  
//    $sqlCount = "SELECT COUNT(*) as total FROM users WHERE id !=$registrantId";

//    if ($query) {
//       $sqlCount = "SELECT COUNT(*) as total FROM users WHERE name LIKE '%$query%' AND id !=$registrantId";
//    }



//    $sqlcountResult = mysqli_query($conn, $sqlCount);
//    $rows = mysqli_fetch_assoc($sqlcountResult)['total'];
//    $pages = ceil($rows/$limit);



//    $sqlGet = "SELECT * FROM users WHERE id !=$registrantId ORDER BY name ASC LIMIT  $offset,$limit";
   
//    if ($query) {
//       $sqlGet = "SELECT * FROM users WHERE name LIKE '%$query%' AND id !=$registrantId ORDER BY name ASC LIMIT $limit";
//    }


// $sqlUsersList = $sqlGet;
// $sqlUsersListResult= mysqli_query($conn,$sqlUsersList);


// echo "<input id='user-rows' value=$rows hidden>";
// echo "<input id='user-pages' value=$pages hidden>";
// echo "<input id='user-current-page' value=$currentPage hidden>";

// if ($sqlUsersListResult->num_rows>0) { 

//    while($users= $sqlUsersListResult->fetch_assoc()){ 
//          $userId = $users ['id'];
//          $name = $users ['name'];
      
//          $attributeId = 'user-'.$userId;
//          $attributeClass = 'list-title';

//          if ($userId != $registrantId) {
//                 echo "<strong id='$attributeId' class='$attributeClass'>$name</strong>";
//          echo '<hr>';
//          }
        
          
//    }  


// } else {
//    echo '<small>No result</small>';
   
// }



           
// }


//  }




// if (isset($_POST['get_user_submit'])){
//    $userId = htmlspecialchars($_POST['user_id']);

//    $sqlUser = "SELECT * FROM users WHERE id = $userId";
//    $sqlUserResult = mysqli_query($conn,$sqlUser);
//    $user = $sqlUserResult->fetch_assoc();

//    $responses = [];

//    if ($user) {
//       $responses ['user-first-name'] = $user['first_name'];
//       $responses ['user-middle-name'] = $user['middle_name'];
//       $responses ['user-last-name'] = $user['last_name'];
//       $responses ['user-name'] = $user['name'];
//       $responses ['user-email-address'] = $user['email_address'];
//       $responses ['user-username'] = $user['username'];
//       $responses ['user-type'] = $user['type'];
//       $responses ['user-status'] = $user['status'];
//    }


//    if ($responses) {
//       header('Content-Type: application/json');
//       $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
//       echo  $jsonResponses;
//    } else {
//       echo '';
//    }
// }





// if(isset($_POST['add_update_user_submit'])){

//    $userId = htmlspecialchars($_POST['user_id']);
//    $firstName = htmlspecialchars($_POST['user_first_name']);
//    $lastName = htmlspecialchars($_POST['user_last_name']);

//    $name=trim($firstName.' '.$lastName);
//    $emailAddress = htmlspecialchars($_POST['user_email_address']);
//    $username = htmlspecialchars($_POST['user_username']);
//    $type = htmlspecialchars($_POST['user_type']);

//    $generatedPassword = password_hash($username, PASSWORD_DEFAULT);

//    $status = htmlspecialchars($_POST['user_status']);

//    $action = htmlspecialchars($_POST['add_update_action']);

//    $letterOnlyPattern ='/^[a-zA-Z ]+$/';
//    $responses = [];
//    $responses ['error'] = [];
   



//    if (!$firstName) {
//       $error = 'Please enter first name.';
//       array_push($responses ['error'],$error);
//    } else {
//        if (!preg_match($letterOnlyPattern,$firstName)) {
//         $error = 'First name is not valid.';
//         array_push($responses ['error'],$error);
//         }
//    }


//    if (!$lastName) {
//       $error= 'Please enter last name.';
//      array_push($responses ['error'],$error);
//    } else {
//        if (!preg_match($letterOnlyPattern,$lastName)) {
//        $error = 'Last name is not valid.';
//         array_push($responses ['error'],$error);
//         }
//    }


//    if (!$emailAddress) {
//       $error = 'Please enter email address.';
//     array_push($responses ['error'],$error);
//    } else {
//        if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) { 
//        $error = 'Email address is not valid.';
//      array_push($responses ['error'],$error);
//       }else {
         
//       if ($action=='Add') {
//             $sqlEmail = "SELECT * FROM users WHERE email_address = '$emailAddress'";
//             $resultEmail = mysqli_query($conn, $sqlEmail);
//             $rowCountEmail = mysqli_num_rows($resultEmail);

//             if ($rowCountEmail>0) { 
//             $error = 'Email address is already added.';
//            array_push($responses ['error'],$error);
//             }
//       }
        
//     }
//    }


//    if (!$username) {
//       $error = 'Please enter username.';
//      array_push($responses ['error'],$error);
//       } else {
//        if ($action=='Add') {
//        $sqlUsername = "SELECT * FROM users WHERE username = '$username'";
//          $resultUsername = mysqli_query($conn, $sqlUsername);
//          $rowCountUsername = mysqli_num_rows($resultUsername);

//          if ($rowCountUsername>0) {
//                   $error = 'Username is already added.';
//                array_push($responses ['error'],$error);
            
//          }
//        }
      
//    }




//       if (!$type) {
//       $error = 'Please select type.';
//       array_push($responses ['error'],$error);
//       } 


//       if (!$status) {
//       $error = 'Please select status.';
//      array_push($responses ['error'],$error);
//       } 
 





   


//     if (!$responses['error']) {

//       if ($action == 'Add') {

//          $sqlAddUser = "INSERT INTO users (first_name,last_name,name,email_address,username,password,type,status) VALUES (?,?,?,?,?,?,?,?)";
//          $stmt = mysqli_stmt_init($conn);

//          $prepareStmt = mysqli_stmt_prepare($stmt,$sqlAddUser);

//             if ($prepareStmt) {
//                mysqli_stmt_bind_param($stmt,"ssssssss", $firstName,$lastName,$name,$emailAddress,$username,$generatedPassword,$type,$status);
//                mysqli_stmt_execute($stmt);

//                $newUserId = mysqli_insert_id($conn);

//                $responses ['status'] = 'Successful';
//                $responses ['user-id'] = $newUserId;
//                $responses ['user-email-address'] = $emailAddress;

//             }

//       }


//       if ($action == 'Update') {

//          $sqlUpdateUser = "UPDATE users
//                             SET 
//                             type=?,
//                             status = ?
//                             WHERE id = '$userId'";

//          $stmt = mysqli_stmt_init($conn);

//          $prepareStmt = mysqli_stmt_prepare($stmt,$sqlUpdateUser);

//             if ($prepareStmt) {
//                mysqli_stmt_bind_param($stmt,"ss", $type,$status);
//                mysqli_stmt_execute($stmt);

//                $responses ['status'] = 'Successful';
//             }       
         
//       }

    
//    } else {
//          $responses['status'] = 'Unsuccessful'; 
//    }
   
   
   
   
//       if ($responses) {
//             header('Content-Type: application/json');
//             $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
//             echo  $jsonResponses;
//       }
      


// }






if (isset($_POST['delete_submit'])) {
    $deleteId = htmlspecialchars($_POST['id']);

    $table = 'users';
    $column = 'id';
    $imageCol = 'profile_picture_link';

    $getImageLink = "SELECT $imageCol FROM $table WHERE $column='$deleteId'";
    $getImageLinkResult = mysqli_query($conn,$getImageLink);
    $imageLink= $getImageLinkResult->fetch_assoc();

    if ($imageLink) {
        $imageLinkDelete = '../../'.$imageLink [$imageCol];
        $imageDeleted= unlink($imageLinkDelete);
    }

   
   $sqlDeleteArticle = mysqli_query($conn,"delete from users where id =  $deleteId");

   $sqlDeleteFromUserLogs = mysqli_query($conn,"delete from user_logs where user_id = '$deleteId'");

   echo 'Successful';
 

}



if(isset($_POST['changePasswordBtn'])) {
    session_start();

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

            header('Location:'.$website.'/login/');

        }


        }


    } else {

        $_SESSION ['empty-passwords'] = "yes";

        header ($goBackURL);
    }
    
    
    



    

}


