<?php

$firstName = '';
$middleName = '';
$lastName = '';
$accountName = '';
$registrantDescription = '';
$type = '';

$username = '';
$emailAddress = '';
$mobileNumber = '';

$birthdate = '';
$gender = '';
$civilStatus = '';

$education = '';
$school = '';
$occupation = '';

$street_subd_village = '';
$barangay = '';
$city_municipality = '';
$province_state = '';
$region='';
$country = '';
$zipcode = '';

$basicRegistration = '';
$teacherRegistration = '';
$writerRegistration = '';
$editorRegistration = '';
$websiteManagerRegistration = '';
$developerRegistration = '';
$researchesRegistration='';
$websiteManagerRegistration='';

$websiteManagerSuperManagerRegistration='';
$websiteManagerSubscriptionManagerRegistration='';
$websiteManagerRegistrationManagerRegistration='';
$websiteManagerPromotionManagerRegistration='';
$websiteManagerMessageManagerRegistration='';


$inSubscriptionSellerList='';
$inSubscriptionToolList='';
$inSubscriptionFileList='';
$inSubscriptionShelfList='';

$toolSubscribed=false; 
$fileSubscribed=false; 
$sellerSubscribed=false;
$shelfSubscribed=false; 

$pendingToolSubscription=false;
$pendingFileSubscription=false;
$pendingSellerSubscription=false;
$pendingShelfSubscription=false;



$haveOtherRegistration = false;
$haveAllRegistrations=false;

$subscriptionStatus="";
$subscriptionExpiry="";
$subscription="";

$filledOutSellingDetails="";
$credential=""; 



if ($loggedIn) {

            $sqlMyRecords = "SELECT * FROM registrations where registrantId = '$registrantId'";
            $sqlMyRecordsResults = mysqli_query($conn,$sqlMyRecords);
            $myRecords = $sqlMyRecordsResults->fetch_assoc();


            if($myRecords) {
                $registrantCode = $myRecords['registrantCode'];
                $firstName = $myRecords['registrantFirstName'];
                $middleName = $myRecords['registrantMiddleName'];
                $lastName = $myRecords['registrantLastName'];
                $type = $myRecords['registrantAccountType'];
                $accountName = $myRecords['registrantAccountName'];
                $registrantDescription = $myRecords ['registrantDescription'];

                $username = $myRecords['registrantUsername'];
                $emailAddress = $myRecords['registrantEmailAddress'];
                $mobileNumber = $myRecords['registrantMobileNumber'];

                $birthdate = $myRecords['registrantBirthdate'];
                $gender = $myRecords['registrantGender'];
                $civilStatus = $myRecords['registrantCivilStatus'];

                $education = $myRecords['registrantEducationalAttainment'];
                $school = $myRecords['registrantSchool'];
                $occupation = $myRecords['registrantOccupation'];

                $street_subd_village = $myRecords ['registrantAddressStreet'];
                $barangay = $myRecords ['registrantAddressBarangay'];
                $city_municipality = $myRecords ['registrantAddressCity'];
                $province_state = $myRecords ['registrantAddressProvince'];
                $region = $myRecords ['registrantAddressRegion'];
                $country = $myRecords ['registrantAddressCountry'];
                $zipcode = $myRecords ['registrantAddressZipCode'];

                $paymentChannel = $myRecords ['registrantPaymentChannel'];
                $bankAccountName = $myRecords ['registrantBankAccountName'];
                $bankAccountNumber = $myRecords ['registrantBankAccountNumber'];
                $reviewSchedules = $myRecords ['registrantReviewSchedules'];



                if ($paymentChannel && $bankAccountName && $bankAccountNumber && $reviewSchedules) {
                    $filledOutSellingDetails = "yes";
                } 
               

                $profilePictureLink = $myRecords['registrantProfilePictureLink'] ? $privateFolder.$myRecords['registrantProfilePictureLink'] : $website."/assets/images/user.svg";

                $coverPhotoLink = $myRecords['registrantCoverPhotoLink'] ? $privateFolder.$myRecords['registrantCoverPhotoLink'] : $website."/assets/images/cover-photo.jpeg";

                $basicRegistration = $myRecords['registrantBasicAccount'];
                $teacherRegistration = $myRecords['registrantTeacherAccount'];
                $writerRegistration = $myRecords['registrantWriterAccount'];
                $editorRegistration = $myRecords['registrantEditorAccount'];
                $websiteManagerRegistration = '';
                $developerRegistration = $myRecords['registrantDeveloperAccount'];
                $researchesRegistration = $myRecords['registrantResearchesAccount']; 

                
                    $sqlWebsiteManagerRegistrations = "SELECT * FROM website_manager_accounts WHERE website_manager_accountRegistrant = $registrantId";
                    $sqlWebsiteManagerRegistrationsResult =mysqli_query($conn,$sqlWebsiteManagerRegistrations);
                    $websiteManagerRegistrations = $sqlWebsiteManagerRegistrationsResult->fetch_assoc();


                     if ($websiteManagerRegistrations){
                            $websiteManagerSuperManagerRegistration = $websiteManagerRegistrations ['website_manager_accountSuperManager'];

                             $websiteManagerSubscriptionManagerRegistration = $websiteManagerRegistrations ['website_manager_accountSubscriptionManager'];

                              $websiteManagerRegistrationManagerRegistration = $websiteManagerRegistrations ['website_manager_accountRegistrationManager'];

                              $websiteManagerPromotionManagerRegistration = $websiteManagerRegistrations ['website_manager_accountPromotionManager'];

                              $websiteManagerMessageManagerRegistration = $websiteManagerRegistrations ['website_manager_accountMessageManager'];
                        
                              if ($websiteManagerSuperManagerRegistration ||            $websiteManagerSubscriptionManagerRegistration || $websiteManagerRegistrationManagerRegistration || $websiteManagerPromotionManagerRegistration || $websiteManagerMessageManagerRegistration){
                                $websiteManagerRegistration = 'Website Manager';
                            }


                            $websiteManagerRegistrationsComplete = [];

                            if ($websiteManagerSuperManagerRegistration){
                                array_push($websiteManagerRegistrationsComplete,$websiteManagerSuperManagerRegistration);
                            }

                            if ($websiteManagerSubscriptionManagerRegistration){
                                array_push($websiteManagerRegistrationsComplete,$websiteManagerSubscriptionManagerRegistration);
                            }

                             if ($websiteManagerRegistrationManagerRegistration){
                                array_push($websiteManagerRegistrationsComplete,$websiteManagerRegistrationManagerRegistration);
                            }

                             if ($websiteManagerPromotionManagerRegistration){
                                array_push($websiteManagerRegistrationsComplete,$websiteManagerPromotionManagerRegistration);
                            }

                             if ($websiteManagerMessageManagerRegistration){
                                array_push($websiteManagerRegistrationsComplete,$websiteManagerMessageManagerRegistration);
                            }

                            if ($websiteManagerRegistrationsComplete) {
                                $websiteManagerRegistrationsComplete = implode(', ', $websiteManagerRegistrationsComplete);
                            }


                            
                }

                $registrations = [];
              
                    if ($basicRegistration) {
                        array_push($registrations,$basicRegistration);
                    }

                    if ($teacherRegistration) {
                        array_push($registrations,$teacherRegistration);
                    }

                    if ($writerRegistration) {
                        array_push($registrations,$writerRegistration);
                    }
                    if ($editorRegistration) {
                        array_push($registrations,$editorRegistration);
                    }

                    if ($developerRegistration) {
                        array_push($registrations,$developerRegistration);
                    }

                    if ($websiteManagerRegistration) {
                        array_push($registrations,$websiteManagerRegistration);
                    }         

                


                    


                    if ($registrations) {
                        $registrations = implode(' | ', $registrations);
                    }

                if ($teacherRegistration || $writerRegistration || $editorRegistration || $websiteManagerRegistration ||  $developerRegistration || $researchesRegistration) {
                $haveOtherRegistration = true;
                }


                if ($teacherRegistration && $writerRegistration && $editorRegistration && $websiteManagerRegistration &&  $developerRegistration) {
                $haveAllRegistrations = true;
                } 
          
                $sqlSubscriptionTool = "SELECT * FROM registrant_subscriptions WHERE registrant_subscriptionUserId = $registrantId AND registrant_subscriptionType='Tools' ORDER BY registrant_subscriptionId DESC LIMIT 1";
                $sqlSubscriptionToolResults = mysqli_query($conn,$sqlSubscriptionTool);
                $inSubscriptionToolList = $sqlSubscriptionToolResults->fetch_assoc();


                if ($inSubscriptionToolList) {
                    $subscriptionStatusTool = $inSubscriptionToolList['registrant_subscriptionStatus'];
                    $subscriptionExpiryTool = dcomplete_format($inSubscriptionToolList['registrant_subscriptionExpiry']);

                    $subscriptionRemainingDaysTool = floor((strtotime($subscriptionExpiryTool) - $currentTime)/86400);
                    
                    $inSubscriptionToolList="yes";

                   
                    if ($subscriptionStatusTool == 'Approved' || $subscriptionStatusTool == 'Kept' || $subscriptionStatusTool == 'Revoked') {
                    if (strtotime($subscriptionExpiryTool)-$currentTime>0) {
                          $_SESSION ['tool-subscribed'] = "yes";
                        $toolSubscribed=true;    
                    }
                  
                    }

                    elseif ($subscriptionStatusTool == 'Pending') {
                     $_SESSION ['pending-tool-subscription'] = "yes";
                     $pendingToolSubscription=true;

                   }     
               
                }


                $sqlSubscriptionFile = "SELECT * FROM registrant_subscriptions WHERE registrant_subscriptionUserId = $registrantId AND registrant_subscriptionType='Files' ORDER BY registrant_subscriptionId DESC LIMIT 1";
                $sqlSubscriptionFileResults = mysqli_query($conn,$sqlSubscriptionFile);
                $inSubscriptionFileList = $sqlSubscriptionFileResults->fetch_assoc();


                if ($inSubscriptionFileList) {
                    $subscriptionStatusFile = $inSubscriptionFileList['registrant_subscriptionStatus'];
                    $subscriptionExpiryFile = dcomplete_format($inSubscriptionFileList['registrant_subscriptionExpiry']);

                     $subscriptionRemainingDaysFile = floor((strtotime($subscriptionExpiryFile) - $currentTime)/86400);

                     $inSubscriptionFileList="yes";

                   
                    if ($subscriptionStatusFile == 'Approved' || $subscriptionStatusFile == 'Kept' || $subscriptionStatusFile == 'Revoked') {
                        if (strtotime($subscriptionExpiryFile)-$currentTime>0) {
                            $_SESSION ['file-subscribed'] = "yes";
                            $fileSubscribed=true;    
                        }
                    }

                    elseif ($subscriptionStatusFile == 'Pending') {
                     $_SESSION ['pending-file-subscription'] = "yes";
                     $pendingFileSubscription=true;

                   }     
               
                }






                $sqlSubscriptionSeller = "SELECT * FROM registrant_subscriptions WHERE registrant_subscriptionUserId = $registrantId AND registrant_subscriptionType='Seller' ORDER BY registrant_subscriptionId DESC LIMIT 1";
                $sqlSubscriptionSellerResults = mysqli_query($conn,$sqlSubscriptionSeller);
                $inSubscriptionSellerList = $sqlSubscriptionSellerResults->fetch_assoc();


                if ($inSubscriptionSellerList) {
                    $subscriptionStatusSeller = $inSubscriptionSellerList ['registrant_subscriptionStatus'];
                    $subscriptionExpirySeller = dcomplete_format($inSubscriptionSellerList['registrant_subscriptionExpiry']);

                    $subscriptionRemainingDaysSeller = floor((strtotime($subscriptionExpirySeller) - $currentTime)/86400);

                    $inSubscriptionSellerList="yes";

                   
                    if ($subscriptionStatusSeller == 'Approved' || $subscriptionStatusSeller == 'Kept' || $subscriptionStatusSeller == 'Revoked') {

                        if (strtotime($subscriptionExpirySeller)-$currentTime>0) {
                                $_SESSION ['seller-subscribed'] = "yes";
                                $sellerSubscribed=true;    
                        }
                  
                    } elseif ($subscriptionStatusSeller == 'Pending') {
                     $_SESSION ['pending-seller-subscription'] = "yes";
                     $pendingSellerSubscription=true;

                   }     
               
                }


                $sqlSubscriptionShelf = "SELECT * FROM registrant_subscriptions WHERE registrant_subscriptionUserId = $registrantId AND registrant_subscriptionType='Shelf' ORDER BY registrant_subscriptionId DESC LIMIT 1";
                $sqlSubscriptionShelfResults = mysqli_query($conn,$sqlSubscriptionShelf);
                $inSubscriptionShelfList = $sqlSubscriptionShelfResults->fetch_assoc();


                if ($inSubscriptionShelfList) {
                    $subscriptionStatusShelf = $inSubscriptionShelfList ['registrant_subscriptionStatus'];

                    $subscriptionExpiryShelf = dcomplete_format($inSubscriptionShelfList['registrant_subscriptionExpiry']);

                    $subscriptionRemainingDaysShelf = floor((strtotime($subscriptionExpiryShelf) - $currentTime)/86400);

                    $inSubscriptionShelfList="yes";

                   
                    if ($subscriptionStatusShelf == 'Approved' || $subscriptionStatusShelf == 'Kept' || $subscriptionStatusShelf == 'Revoked') {
                        if (strtotime($subscriptionExpiryShelf)-$currentTime>0){
                            $_SESSION ['shelf-subscribed'] = "yes";
                            $shelfSubscribed=true;    
                        }
                    }

                    elseif ($subscriptionStatusShelf == 'Pending') {
                     $_SESSION ['pending-shelf-subscription'] = "yes";
                     $pendingShelfSubscription=true;

                   }     
               
                }

                if ($type=='Personal') {
                    if ($teacherRegistration) {
                        if ($inSubscriptionToolList && $inSubscriptionSellerList) {
                        $subscription = "disabled";
                        } 
                    }

                    if (!$teacherRegistration) {
                        if ($inSubscriptionToolList) {
                        $subscription = "disabled";
                        } 
                    }
                }
                
                if ($type=='School') {
                    if ($inSubscriptionShelfList) {
                    $subscription = "disabled";
                    }
                }
                


                //Check login status
                        
                        $sqlCheckLogin = "SELECT * FROM registrant_activities WHERE registrant_activityUserId= $registrantId ORDER BY registrant_activityId DESC LIMIT 1";
                        $sqlCheckLoginResult = mysqli_query($conn,$sqlCheckLogin);
                        $login=$sqlCheckLoginResult->fetch_assoc();

                        if ($login) {
                          $loginContent = $login['registrant_activityContent'];

                           if ($loginContent=='Logged out') {
                                session_destroy(); 
                                header('Location:'.$website.'/login/');
                            }

                        }

                       
            } else {
                header('Location:'.$website.'/login/');
            }

            
 } 
 


 




?>


