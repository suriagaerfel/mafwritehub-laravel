<?php 
require '../../initialize.php';
require '../../database.php';


$registrantId="";
$goToURL="";

$byEmail = '';

if (isset($_POST['logout_submit'])) {
$registrantId = $_POST ['session_userid'];
$byEmail='';
}



if (isset($_GET['userid'])) {
$registrantId = htmlspecialchars($_GET['userid']);
$byEmail ='yes';
}


$activity='Logged out';

$sqlInsertActivity = "INSERT INTO user_logs (user_id,activity) VALUES (?, ?)";
    $stmt = mysqli_stmt_init($conn);
    $prepareStmt = mysqli_stmt_prepare($stmt,$sqlInsertActivity);

    if ($prepareStmt) {
        mysqli_stmt_bind_param($stmt,"ss", $registrantId,$activity);
        mysqli_stmt_execute($stmt);

        session_destroy();
        session_start();

        if (!$byEmail) {
            echo 'Logout successful';
        }

        if ($byEmail) {
            header('Location:'.$website);
        }

    }



