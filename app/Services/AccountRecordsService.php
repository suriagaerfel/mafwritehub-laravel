<?php 
namespace App\Services;

use Illuminate\Database\Eloquent\Attributes\Initialize;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;



class AccountRecordsService
{
  
       public function get_profile_records ($userId)
    {

        $conn = config('app.conn');
        $publicFolder=  config('app.publicFolder');

        $loggedIn=false;

        $firstName = '';
        $middleName = '';
        $lastName = '';
        $accountName = '';
        $registrantDescription = '';
        $type = '';

        $profilePictureLink = '';

        $username = '';
        $emailAddress = '';
        $mobileNumber = '';


    if ($userId) {
            $stmt=$conn->prepare("SELECT * FROM users where id= ?");
            $stmt->execute([$userId]);
            $myRecords=$stmt->fetch();


            if($myRecords) {

                $loggedIn= true;

                $firstName = $myRecords['first_name'];
                $middleName = $myRecords['middle_name'];
                $lastName = $myRecords['last_name'];
                $type = $myRecords['type'];
                $accountName = $myRecords['name'];
                $registrantDescription = $myRecords ['description'];
                $profilePictureLink = $myRecords ['profile_picture_link'] ? $publicFolder.$myRecords ['profile_picture_link']: $publicFolder.'/assets/images/user.svg';

                $username = $myRecords['username'];
                $emailAddress = $myRecords['email_address'];
                $mobileNumber = $myRecords['mobile_number'];

               

            } 
            
    } 



     $account_records= [
        'loggedIn'=>$loggedIn,
        'userId'=>$userId,
        'firstName'=>$firstName,
        'middleName'=> $middleName,
        'lastName'=> $lastName,
        'accountName'=>$accountName,
        'registrantDescription'=>$registrantDescription,
        'type'=>$type,
        'profilePictureLink'=>$profilePictureLink,
        'username'=>$username,
        'emailAddress'=>$emailAddress,
        'mobileNumber'=>$mobileNumber
    ];

    return $account_records;
   


    }
}



?>