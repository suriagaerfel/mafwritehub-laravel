
<?php 
//----------------------------------------- DATABASE CONNECTION ---------------------------------//
// $database_path = database_path('database.sqlite'); // Helper to get absolute path
// $conn = null;
// try {
//     $conn = new PDO("sqlite:" . $database_path);
//     // Set error mode to exception for robust error handling
//     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
//     // echo "Connected successfully";
// } catch (PDOException $e) {
//     echo "Connection failed: " . $e->getMessage();
// }
//----------------------------------------- PAGE NAME ---------------------------------//
// $pageName = '';


//----------------------------------------- SEARCHED USER ---------------------------------//
// $user= isset($_GET['user']) ? htmlspecialchars(isset($_GET['user'])) : '';

//----------------------------------------- SLUG ---------------------------------//
// $slug = isset($_GET['slug']) ? $_GET['slug'] : '';

//----------------------------------------- PUBLIC FOLDER ---------------------------------//
// $publicFolder = '';


// //------------------------------------------DYNAMIC HOST-----------------------------------//

// // Get the scheme (http or https)
// $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

// // Get the host (domain name, with optional port number)
// $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'];

// // Combine them to form the base URL
// $domain = $scheme . '://' . $host;

// if ($domain) {
//     $publicFolder= $domain; 
//     $privateFolder=$domain.'/private';

//     if(str_contains($domain,'localhost')){
//         $projectName = '/eskquip-laravel';
//         $domain = $domain.$projectName;

//         $publicFolder= $domain.'/public'; 
//         $privateFolder=$domain.'/private';
//     }

// }




// //----------------------------------------TIME ZONE and CURRENT TIME----------------------------------//

// date_default_timezone_set('Asia/Manila');

// $currentTimeZone = date_default_timezone_get();
// $currentTime = time(); 

// $currentTimeConverted = date("m/d/Y g:i A",  $currentTime); 


// // ------------------------------------------- CURRENT URL ------------------------------------------//
// $currentURL = $_SERVER['REQUEST_URI']; 
  
  
 
// //---------------------------------------- SESSION ID ---------------------------------//

 
// $registrantId= isset($_SESSION['id']) ? $_SESSION['id'] : ''; 
// $loggedIn =  $registrantId ? true : false; 






?>



