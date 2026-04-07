<?php

$user= isset($_GET['user']) ? htmlspecialchars($_GET['user']) : "";

$u_userId='';
$u_username = '';
$u_firstName = '';
$u_middleName = '';
$u_lastName = '';
$u_accountName = '';
$u_registrantDescription = '';


$u_type = '';
$u_accountName = '';

$u_emailAddress = '';
$u_mobileNumber = '';

$u_birthdate = '';
$u_gender = '';
$u_civilStatus = '';

$u_education ='';
$u_school = '';
$u_occupation ='';

$u_street_subd_village ='';
$u_barangay = '';
$u_city_municipality ='';
$u_province_state = '';
$u_region ='';
$u_country = '';
$u_zipcode = '';



$u_profilePictureLink ='';
$u_coverPhotoLink = '';

$u_basicRegistration = '';
$u_teacherRegistration = '';
$u_writerRegistration = '';
$u_editorRegistration = '';
$u_siteManagerRegistration = '';
$u_developerRegistration = '';


$uInfo="";

 if ($user) {
   
        $sqlGetUInfo = "SELECT * FROM registrations WHERE registrantUsername = '$user'";
        $sqlGetUInfoResult = mysqli_query ($conn,$sqlGetUInfo);
        $uInfo = $sqlGetUInfoResult->fetch_assoc();

        if($uInfo) {
            $u_userId=$uInfo ['registrantId'];
            $u_username = $uInfo ['registrantUsername'];
            $u_firstName = $uInfo ['registrantFirstName'];
            $u_middleName = $uInfo ['registrantMiddleName'];
            $u_lastName = $uInfo ['registrantLastName'];
            $u_accountName = $uInfo ['registrantAccountName'];
            $u_registrantDescription = $uInfo ['registrantDescription'];


            $u_type = $uInfo['registrantAccountType'];
            $u_accountName = $uInfo['registrantAccountName'];

            $u_emailAddress = $uInfo['registrantEmailAddress'];
            $u_mobileNumber = $uInfo['registrantMobileNumber'];

            // $birthdate = date ("M j, Y",strtotime($myRecords['registrantBirthdate']));
            $u_birthdate = $uInfo['registrantBirthdate'];
            $u_gender = $uInfo['registrantGender'];
            $u_civilStatus = $uInfo['registrantCivilStatus'];

            $u_education = $uInfo['registrantEducationalAttainment'];
            $u_school = $uInfo['registrantSchool'];
            $u_occupation = $uInfo['registrantOccupation'];

            $u_street_subd_village = $uInfo ['registrantAddressStreet'];
            $u_barangay = $uInfo ['registrantAddressBarangay'];
            $u_city_municipality = $uInfo ['registrantAddressCity'];
            $u_province_state = $uInfo ['registrantAddressProvince'];
            $u_region = $uInfo ['registrantAddressRegion'];
            $u_country = $uInfo ['registrantAddressCountry'];
            $u_zipcode = $uInfo ['registrantAddressZipCode'];



             $u_profilePictureLink = $uInfo['registrantProfilePictureLink'] ? $privateFolder.$uInfo['registrantProfilePictureLink'] : $website."/assets/images/user.svg";
            $u_coverPhotoLink = $uInfo['registrantCoverPhotoLink'] ? $privateFolder.$uInfo['registrantCoverPhotoLink'] : $website."/assets/images/cover-photo.jpeg";

            $u_basicRegistration = $uInfo['registrantBasicAccount'];
            $u_teacherRegistration = $uInfo['registrantTeacherAccount'];
            $u_writerRegistration = $uInfo['registrantWriterAccount'];
            $u_editorRegistration = $uInfo['registrantEditorAccount'];
            $u_websiteManagerRegistration = '';
            $u_developerRegistration = $uInfo['registrantDeveloperAccount'];



             $u_sqlWebsiteManagerRegistrations = "SELECT * FROM website_manager_accounts WHERE website_manager_accountRegistrant = $u_userId";
            $u_sqlWebsiteManagerRegistrationsResult =mysqli_query($conn,$u_sqlWebsiteManagerRegistrations);
            $u_websiteManagerRegistrations = $u_sqlWebsiteManagerRegistrationsResult->fetch_assoc();


                if ($u_websiteManagerRegistrations){
                    $u_websiteManagerSuperManagerRegistration = $u_websiteManagerRegistrations ['website_manager_accountSuperManager'] ? $u_websiteManagerRegistrations ['website_manager_accountSuperManager'] : "";

                        $u_websiteManagerSubscriptionManagerRegistration = $u_websiteManagerRegistrations ['website_manager_accountSubscriptionManager'] ? $u_websiteManagerRegistrations ['website_manager_accountSubscriptionManager'] : "";

                        $u_websiteManagerRegistrationManagerRegistration = $u_websiteManagerRegistrations ['website_manager_accountRegistrationManager'] ? $u_websiteManagerRegistrations ['website_manager_accountRegistrationManager'] : "";

                        $u_websiteManagerPromotionManagerRegistration = $u_websiteManagerRegistrations ['website_manager_accountPromotionManager'] ? $u_websiteManagerRegistrations['website_manager_accountPromotionManager'] : "";

                        $u_websiteManagerMessageManagerRegistration = $u_websiteManagerRegistrations ['website_manager_accountMessageManager'] ? $u_websiteManagerRegistrations['website_manager_accountMessageManager'] : "";
                
                        if ($u_websiteManagerSuperManagerRegistration ||            $u_websiteManagerSubscriptionManagerRegistration || $u_websiteManagerRegistrationManagerRegistration || $u_websiteManagerPromotionManagerRegistration || $u_websiteManagerMessageManagerRegistration){
                        $u_websiteManagerRegistration = 'Website Manager';
                    }
                }











         

            $u_accounts = [];
            
            if ($u_basicRegistration) {
                array_push($u_accounts,$u_basicRegistration);
            }

            if ($u_teacherRegistration) {
                array_push($u_accounts,$u_teacherRegistration);
            }

            if ($u_writerRegistration) {
                array_push($u_accounts,$u_writerRegistration);
            }
            if ($u_editorRegistration) {
                array_push($u_accounts,$u_editorRegistration);
            }

            if ($u_developerRegistration) {
                array_push($u_accounts,$u_developerRegistration);
            }

            if ($u_websiteManagerRegistration) {
                array_push($u_accounts,$u_websiteManagerRegistration);
            }

            if ($u_accounts){
                $u_accounts =implode(' | ',$u_accounts);
            }

            if ($u_userId===$registrantId) {
                header('Location:'.$website.'/account/');
            } 
        } else {
            header('Location:'.$website);
        }

        $pageName = $u_accountName;

 }

 


?>


