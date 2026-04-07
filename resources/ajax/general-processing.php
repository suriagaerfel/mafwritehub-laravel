<?php require resource_path('processing/initialize.php'); ?>
<?php require resource_path('processing/registrant-records.php'); ?>
<?php require resource_path('processing/u_registrant-records.php'); ?>

<?php



if (isset($_POST['get_general_records_submit'])) {

$query = htmlspecialchars($_POST['query']);
$queryIn = htmlspecialchars($_POST['query_in']);
$contentsAvailability = htmlspecialchars($_POST['contents_availability']);
$isWorkspace = htmlspecialchars($_POST['is_workspace']);
$fromPage = htmlspecialchars($_POST['page_name']);

$homeSearchedUser = htmlspecialchars($_POST['home_searched_user']);
$showShared = $_POST['show_shared'] ? htmlspecialchars($_POST['show_shared']) : '';

$category = htmlspecialchars($_POST['category']);
$tag = htmlspecialchars($_POST['tag']);
$date = htmlspecialchars($_POST['date']);
$owner = htmlspecialchars($_POST['owner']);




if ($queryIn=='teacher-files') {
    $table='teacher_files';
    $titleColumn='teacher_fileTitle';
    $slugColumn='teacher_fileSlug';
    $descriptionColumn='teacher_fileDescription';
    $descriptionLabel = 'Description';
    $categoryColumn = 'teacher_fileCategory';
    $tagsColumn = 'teacher_fileTags';
    $accessTypeColumn = 'teacher_fileAccessType';
    $imageColumn='teacher_fileImage';
    $defaultImage=$website.'/assets/images/default-file-image.jpg';
    $statusColumn = 'teacher_fileStatus';
    $statusValue = 'Published';
    $sortColumn = 'teacher_filePubDate';
    $sortColumnWorkspace = 'teacher_fileUpdateDate';
    $pageLink=$website.'/teacher-files/';
    $registrantIdColumn = 'teacher_fileTeacher';
    $workspace = '/workspace/teacher.php';
    $type='Teacher File';
    $idColumn='teacher_fileId';
    $workspaceType = 'file';
    $access = 'view';

    $ownerType = 'teacher';
}


if ($queryIn=='articles') {
    $table='writer_articles';
    $titleColumn='writer_articleTitle';
    $slugColumn='writer_articleSlug';
    $descriptionColumn='writer_articleContent';
    $descriptionLabel = 'Description';
    $categoryColumn = 'writer_articleCategory';
     $tagsColumn = 'writer_articleTags';
     $accessTypeColumn = 'writer_articleAccessType';
    $imageColumn='writer_articleImage';
    $defaultImage=$website.'/assets/images/default-article-image.jpg';
    $statusColumn = 'writer_articleStatus';
    $statusValue = 'Published';
    $sortColumn = 'writer_articlePubDate';
    $sortColumnWorkspace = 'writer_articleUpdateDate';
    $pageLink=$website.'/articles/';
    $registrantIdColumn = 'writer_articleWriter';
    $workspace = '/workspace/writer.php';
    $type='Article';
    $idColumn='writer_articleId';
    $workspaceType = 'article';

    if ($fromPage == 'Workspace - Editor') {
        $registrantIdColumn = 'writer_articleEditors';
        $workspace = '/workspace/editor.php';
        $statusValue = 'Waiting for Update';
    }

    $access = 'read';

    $ownerType = 'writer';
}



if ($queryIn=='researches') {
    $table='school_researches';
    $titleColumn='school_researchTitle';
    $slugColumn='school_researchSlug';
    $descriptionColumn='school_researchAbstract';
    $descriptionLabel = 'Abstract';
    $categoryColumn = 'school_researchCategory';
    $tagsColumn = 'school_researchTags';
    $accessTypeColumn = 'school_researchAccessType';
    $imageColumn='school_researchImage';
    $defaultImage=$website.'/assets/images/default-research-image.jpg';
    $statusColumn = 'school_researchStatus';
    $statusValue = 'Published';
    $sortColumn = 'school_researchLiveDate';
     $sortColumnWorkspace = 'school_researchUpdateDate';
    $pageLink=$website.'/researches/';
    $registrantIdColumn = 'school_researchSchool';
    $workspace = '/workspace/researches.php';
    $type='Research';
    $idColumn='school_researchId';
    $workspaceType = 'research';

    $access = 'view';

    $ownerType = 'school';
}

if ($queryIn=='tools') {
    $table='developer_tools';
    $titleColumn='developer_toolTitle';
    $descriptionColumn='developer_toolDescription';
    $descriptionLabel = 'Description';
    $categoryColumn = 'developer_toolCategory';
     $tagsColumn = 'developer_toolTags';
     $accessTypeColumn = 'developer_toolAccessType';
    $imageColumn='developer_toolImage';
    $defaultImage=$website.'/assets/images/default-tool-image.jpg';
    $slugColumn='developer_toolSlug';
    $statusColumn = 'developer_toolStatus';
    $statusValue = 'Published';
    $sortColumn = 'developer_toolPubDate';
    $sortColumnWorkspace = 'developer_toolUpdateDate';
    $pageLink=$website.'/tools/';
    $registrantIdColumn = 'developer_toolDeveloper';
    $workspace = '/workspace/developer.php';
    $type='Tool';
    $idColumn='developer_toolId';
    $workspaceType = 'tool';

    $access = 'use';

    $ownerType = 'developer';

}



if ($queryIn=='general-contents' || $homeSearchedUser) {
    $table='contents';
    $foreignIdColumn = 'contentForeignId';
    $tableColumn = 'contentTable';
    $statusColumn = 'contentStatus';
    $statusValue = 'Published';
    $sortColumn = 'contentPubDate';
    $registrantIdColumn = 'contentRegistrantId';
}


if ($queryIn=='accounts') {
    $table='registrations';
    $titleColumn='registrantAccountName';
    $usernameColumn='registrantUsername';
    $descriptionColumn='registrantDescription';
    $imageColumn='registrantProfilePictureLink';
    $defaultImage=$website.'/assets/images/user.svg';
    $statusColumn = 'registrantVerificationStatus';
    $statusValue = 'Verified';
    $sortColumn = 'registrantCreatedAt';
    $pageLink=$website.'/';
    $searchedTable="";
    $categoryColumn = 'registrantAccountType';
    $type = 'Account';
    $idColumn='registrantId';
    $registrantIdColumn=$idColumn;

    $descriptionLabel = 'Description';
    $access = 'visit';
}


   
   if (!$isWorkspace) {
    if (!$query) {


        $sqlCreteria = $table. " WHERE ".$statusColumn."='".$statusValue."'"." ORDER BY ".$sortColumn." DESC";
       
        if ($homeSearchedUser) {
            $sqlHomeSearchedUserInfo = "SELECT * FROM registrations WHERE registrantUsername='$homeSearchedUser' LIMIT 1";
            $sqlHomeSearchedUserInfoResult = mysqli_query($conn,$sqlHomeSearchedUserInfo);
            $homeSearchedUserInfo = $sqlHomeSearchedUserInfoResult->fetch_assoc();

            if ($homeSearchedUserInfo) {
                $homeSearchedUserId = $homeSearchedUserInfo ['registrantId'];
                $homeSearchedUserCode = $homeSearchedUserInfo ['registrantCode'];
            }


            $sqlCreteria = $table." WHERE ".$registrantIdColumn."=".$homeSearchedUserId." AND ". $statusColumn."='".$statusValue."'"." ORDER BY ".$sortColumn." DESC";
           

            if ($showShared){
                 $sqlCreteria= $table." WHERE contentSharedWith LIKE '%$homeSearchedUserCode%'"." AND ".$statusColumn."='".$statusValue."'"." ORDER BY ".$sortColumn." DESC";
            }
        } else {
            if ($category) {

             $sqlCreteria=  $table." WHERE ".$statusColumn."='".$statusValue."'"." AND ".$categoryColumn."='".$category."'"." ORDER BY ".$sortColumn." DESC";
            }


            if ($tag) {
                $sqlCreteria= $table." WHERE ".$statusColumn."='".$statusValue."'"." AND ".$tagsColumn." LIKE  '%$tag%'"."  ORDER BY ".$sortColumn." DESC";
            }


            if ($date) {
                $sqlCreteria = $table." WHERE ".$statusColumn."='".$statusValue."'"." AND ".$sortColumn." LIKE '%$date%'"." ORDER BY ".$sortColumn." DESC";
            
            }


            if ($owner) {
                $sqlCreteria = $table." WHERE ".$statusColumn."='".$statusValue."'"." AND ".$registrantIdColumn."='".$owner."'"." ORDER BY ".$sortColumn." DESC";
            }

        }

        



    }
    

    if ($query) {

        if ($fromPage != 'Home') {

         $sqlCreteria = $table." WHERE ".$titleColumn." LIKE '%$query%'"." AND ".$statusColumn."='".$statusValue."'"." OR ".$descriptionColumn." LIKE '%$query%'"." AND ".$statusColumn."='".$statusValue."'"." ORDER BY ". $sortColumn." DESC";
           
        }
        
    }

    

   

   }

    if ($isWorkspace) {
        $sqlCreteria = $table." WHERE ".$registrantIdColumn."='".$registrantId."'"." ORDER BY ".$sortColumnWorkspace." DESC";
      

        if ($query) {

         $sqlCreteria = $table." WHERE ".$registrantIdColumn."='".$registrantId."'"." AND ".$titleColumn." LIKE '%$query%'"." ORDER BY ".$sortColumnWorkspace." DESC";
       
        }

        if ($fromPage == 'Workspace - Editor') {
        
        $sqlCreteria = $table." WHERE ".$statusColumn."='".$statusValue."'"." AND "."writer_articleEditors ='' ORDER BY ".$sortColumnWorkspace." DESC";

      


            if ($contentsAvailability) {
                if ($contentsAvailability == 'taken') {
                    $sqlCreteria = $table." WHERE writer_articleEditors=".$registrantId." ORDER BY ".$sortColumnWorkspace." DESC";
                }
            }
           
        }
    }
    
 

    $sqlCount = 
    $sqlRecords = "SELECT * FROM ".$sqlCreteria;
    $sqlRecordsResult = mysqli_query($conn,$sqlRecords);

   if ($sqlRecordsResult->num_rows>0) { 

      while($result = $sqlRecordsResult->fetch_assoc()){ 

      if (!$isWorkspace) {

      if ($queryIn != 'general-contents') {
        $resultId = $result [$idColumn];
         $resultTitle = $result [$titleColumn];
         $resultImage = $result [$imageColumn] ? $privateFolder.$result[$imageColumn]: $defaultImage;
         $resultCategory = $result [$categoryColumn];
         $resultRegistrantId = $result [$registrantIdColumn];
         

       
        $resultDescription = limit_words($result [$descriptionColumn], 50);
        $resultPubDate = dcomplete_format($result [$sortColumn]);

        $href = '';


        $sqlRegistration = "SELECT * FROM registrations WHERE registrantId = $resultRegistrantId";
        $sqlRegistrationResult = mysqli_query($conn,$sqlRegistration);
        $registration = $sqlRegistrationResult->fetch_assoc();

        if ($registration){
            $resultRegistrantAccountName = $registration ['registrantAccountName'];
            $resultRegistrantUsername = $registration ['registrantUsername'];
        }
       

         if ($queryIn !='accounts') {
            $resultViewMode = $result [$accessTypeColumn];
             $resultTags = $result [$tagsColumn];
            $resultSlug = $result [$slugColumn];
            $href=$pageLink.$access.'/'.$resultSlug;

             if ($queryIn == 'tools') {
                $href = $publicFolder.str_replace('/public','',$resultSlug);
            }
         }

          if ($queryIn =='accounts') {
            $resultUsername = $result [$usernameColumn];
            $href=$pageLink.$resultUsername;

            $resultViewMode = 'Public';
         }

        

      }


      if ($queryIn == 'general-contents') {
        $resultTable = $result [$tableColumn];
        $resultForeignId = $result [$foreignIdColumn];
        
        if ($resultTable == 'teacher_files') {
            $idColumn = 'teacher_fileId';
            $titleColumn = 'teacher_fileTitle';
            $slugColumn = 'teacher_fileSlug';
            $categoryColumn = 'teacher_fileCategory';
            $tagsColumn = 'teacher_fileTags';
            $accessTypeColumn = 'teacher_fileAccessType';
            $pubDateColumn = 'teacher_filePubDate';
            $descriptionColumn = 'teacher_fileDescription';
            $descriptionLabel = 'Description';
            $registrantIdColumn= 'teacher_fileTeacher';
            $imageColumn = 'teacher_fileImage';
            $defaultImage=$website.'/assets/images/default-file-image.jpg';
            $pageLink=$website.'/teacher-files/';
            $type = 'Teacher File';
           

            $access = 'view';

            $ownerType = 'teacher';
            
        }

        if ($resultTable == 'writer_articles') {
            $idColumn = 'writer_articleId';
            $titleColumn = 'writer_articleTitle';
            $slugColumn = 'writer_articleSlug';
            $categoryColumn = 'writer_articleCategory';
            $tagsColumn = 'writer_articleTags';
            $accessTypeColumn = 'writer_articleAccessType';
            $pubDateColumn = 'writer_articlePubDate';
            $descriptionColumn = 'writer_articleDescription';
            $descriptionLabel = 'Description';
            $registrantIdColumn= 'writer_articleWriter';
            $imageColumn = 'writer_articleImage';
            $defaultImage=$website.'/assets/images/default-article-image.jpg';
            $pageLink=$website.'/articles/';
            $type = 'Article';
          

            $access = 'read';

            $ownerType = 'writer';
        }

        if ($resultTable == 'school_researches') {
            $idColumn = 'school_researchId';
            $titleColumn = 'school_researchTitle';
            $slugColumn = 'school_researchSlug';
            $categoryColumn = 'school_researchCategory';
            $tagsColumn = 'school_researchTags';
            $accessTypeColumn = 'school_researchAccessType';
            $pubDateColumn = 'school_researchLiveDate';
            $descriptionColumn = 'school_researchAbstract';
            $descriptionLabel = 'Abstract';
            $registrantIdColumn= 'school_researchSchool';
            $imageColumn = 'school_researchImage';
            $defaultImage=$website.'/assets/images/research.jpg';
            $pageLink=$website.'/researches/';
            $type = 'Research';
      

            $access = 'view';
            $ownerType = 'school';
        }

        if ($resultTable == 'developer_tools') {
            $idColumn = 'developer_toolId';
            $titleColumn = 'developer_toolTitle';
             $slugColumn='developer_toolSlug';
             $categoryColumn = 'developer_toolCategory';
            $tagsColumn = 'developer_toolTags';
            $accessTypeColumn = 'developer_toolAccessType';
            $pubDateColumn = 'developer_toolPubDate';
            $descriptionColumn = 'developer_toolDescription';
            $descriptionLabel = 'Description';
            $registrantIdColumn= 'developer_toolDeveloper';
            $imageColumn = 'developer_toolImage';
           $defaultImage=$website.'/assets/images/default-tool-image.jpg';
            $pageLink=$website.'/tools/';
            $type = 'Tool';
            $categoryColumn = 'developer_toolCategory';

            $access = 'use';
            $ownerType = 'developer';
        }

        $sqlGeneralRecords = "SELECT * FROM $resultTable WHERE $idColumn = $resultForeignId";
        $sqlGeneralRecordsResult = mysqli_query($conn,$sqlGeneralRecords);
        $generalResult = $sqlGeneralRecordsResult->fetch_assoc();
         $resultId = $generalResult [$idColumn];
        $resultTitle = $generalResult [$titleColumn];
        $resultImage = $generalResult [$imageColumn] ? $privateFolder.$generalResult[$imageColumn]: $defaultImage;
        
       $resultDescription = limit_words($generalResult [$descriptionColumn], 50);
        $resultCategory = $generalResult [$categoryColumn];
        $resultSlug = $generalResult [$slugColumn];

        $resultRegistrantId = $generalResult [$registrantIdColumn];

        $resultViewMode = $generalResult [$accessTypeColumn];
        $resultTags = $generalResult [$tagsColumn];
        $resultPubDate = dcomplete_format($generalResult [$pubDateColumn]);

        $href=$pageLink.$access.'/'.$resultSlug;   

        if ($resultTable=='developer_tools') {
            $href = $publicFolder.str_replace('/public','',$resultSlug);
        }

        $sqlRegistration = "SELECT * FROM registrations WHERE registrantId = $resultRegistrantId";
        $sqlRegistrationResult = mysqli_query($conn,$sqlRegistration);
        $registration = $sqlRegistrationResult->fetch_assoc();

        if ($registration){
            $resultRegistrantAccountName = $registration ['registrantAccountName'];
            $resultRegistrantUsername = $registration ['registrantUsername'];
        }

      }
}





 if ($isWorkspace) {

      if ($queryIn != 'general-contents') {
         $resultTitle = $result [$titleColumn];
         $resultImage = $result [$imageColumn] ? $privateFolder.$result[$imageColumn]: $defaultImage;
         $resultId = $result [$idColumn];
         $resultSlug = $result [$slugColumn];
         $resultStatus = $result [$statusColumn];
         $resultCategory = $result [$categoryColumn];

      }

     
}


$accessLabel = ucwords($access);
$lowerType = str_replace(' ','-',strtolower($type));


if (!$isWorkspace) {
    $hrefPubDate = date('Y-m',strtotime($resultPubDate));

    if ($resultCategory != 'Administrative') {

    echo "
       
          <div style='display:flex; gap:10px;'>

            <div>  
                <img src=$resultImage style='width:200px;'>
            </div>

           <div>

                <a><h2 id='result-title'>$resultTitle</h2></a>                
                <div>";
                    

                if($queryIn == 'accounts') {
                echo "
                    <a class='".'link-tag-button contents-list-label'."'>$type</a>
                   
                    <a class='".'link-tag-button contents-list-label'."'>$resultCategory</a>
                  
                    <span class='".'link-tag-button contents-list-label'."'>$resultViewMode</span>
                ";
                }

                if($queryIn != 'accounts'){
                echo "
                    <a href='$pageLink'class='".'link-tag-button contents-list-label'."'>$type</a>
                   
                    <a href='".$pageLink."category/".$resultCategory."' class='".'link-tag-button contents-list-label'."'>$resultCategory</a>

                    <a href='".$pageLink.$ownerType."/".$resultRegistrantUsername."'class='".'link-tag-button contents-list-label'."'>$resultRegistrantAccountName</a>

                    
                    <a href='".$pageLink."date/".$hrefPubDate."'class='".'link-tag-button contents-list-label'."'>$resultPubDate</a>
                  
                    <span class='".'link-tag-button contents-list-label'."'>$resultViewMode</span>
                ";
                }



            echo "
          
                </div>
                ";






            if ($queryIn != 'accounts'){


                echo "
                <br>
                <div>
                    <small>Tag (s): </small>";


                if ($resultTags) {
                    $resultTags = explode(', ',$resultTags);
                    foreach ($resultTags as $resultTag){
                    echo "
                    
                        <a class='".'link-tag-button contents-list-label'."' href='".$pageLink.'tag/'.urldecode($resultTag)."'>$resultTag</a>
                    
                        ";  
                    
                    }

                } else {
                    echo '<small>None</small>';
                }
               


                echo "
                </div>";





                

            }

            




            echo "
                <br>
                <div>
                ";


            if ($resultDescription) {
                 echo "
                    <small style='font-weight:bold;'>$descriptionLabel:</small>
                ";
            }
           


            echo "
                    <small>$resultDescription</small>





                </div>
                <br>
                <div>
                    <a class='link-tag-button' href='".$href."' target='_blank'>$accessLabel</a>";


                if($resultViewMode == 'Purchased') {
                    
                

                if (!$registrantId){
                    echo "
                    
                    <a class='".'link-tag-button purchase'."' id='".'purchase-'.$lowerType.'-'.$resultId."'>Purchase</a>
                    
                    "; 
                }

                if ($registrantId){

                    if ($registrantId != $resultRegistrantId) {

                    $sqlPurchased = "SELECT * FROM content_purchase WHERE content_purchaseType = '$type' AND content_purchaseContentId = '$resultId'  AND content_purchaseRegistrantId = '$registrantId'";
                    $sqlPurchasedResult = mysqli_query($conn,$sqlPurchased);
                    $purchased = $sqlPurchasedResult->fetch_assoc();

                    if($purchased){
                        $purchasedStatus = $purchased ['content_purchaseStatus'];
                    }

                        if(!$purchased) {
                        echo "
                        
                        <a class='".'link-tag-button purchase'."' id='".'purchase-'.$lowerType.'-'.$resultId."'>Purchase</a>
                        
                        "; 
                        }

                        if($purchased) {
                            echo "
                            <a class='".'link-tag-button purchase active'."' id='".'purchase-'.$lowerType.'-'.$resultId."'>$purchasedStatus</a>
 
                            ";
                        }
                    }
                    
                }
                      
                    
                }


echo "
                </div>
          
            </div>

            
         </div>

         <hr>";
         
    }
         

}





if ($isWorkspace) {

    
    $editHref = $website.$workspace.'?edit=yes&'.$workspaceType.'='.$resultId;
    $editId = 'edit-'.$workspaceType.'-'.$resultId;
    $viewHref = $pageLink.$access.'/'.$resultSlug;
    
    if ($queryIn == 'tools') {
        $viewHref = $publicFolder.str_replace('/public','',$resultSlug);
    }
    

    echo "
        
        <div style='display:flex; gap:10px;'>

            <div>
                <img src=$resultImage style='width:100px;'>
            </div>

           <div style='display:flex; flex-direction:column; gap:10px;'>
                <div>
                    <strong>$resultTitle</strong>
                </div>
            
                <div>
                    <small class='".'link-tag-button contents-list-label'."'>$resultCategory</small>
              
                    <small class='".'link-tag-button contents-list-label'."'>$resultStatus</small>
                </div>
            
            
                <div id='sidebar-actions'>
                ";

                    if ($fromPage != 'Workspace - Editor') {
                    echo "
                        <a class='link-tag-button' id='$editId'>Edit</a>
                        ";
                    }

                    if ($fromPage == 'Workspace - Editor') {
                    echo "
                        <a class='link-tag-button' id='$editId'>Open</a>
                        ";
                    }
                    
            echo 
                "
                    <a class='link-tag-button' href='".$viewHref."' target='_blank'>$accessLabel</a>
                </div>
            </div>


             
            
           
          
           
            

            
         </div>

         <hr>
         
         
         ";



}

        

         

        
        
            
   } 
 

   }  else {
    if ($query) {
        echo '<small>No result</small>';
    }
   }
}



if (isset($_POST['get_site_manager_records_submit'])){
    $recordCategory = htmlspecialchars($_POST['record_category']);
    $recordSubCategory = htmlspecialchars($_POST['record_sub_category']);
    $recordStatus = htmlspecialchars($_POST['record_status']);
    $recordDate = $_POST['record_date'] ? date('Y-m',strtotime(htmlspecialchars($_POST['record_date']))) : '';
    $recordSearch = htmlspecialchars($_POST['record_search']);

    if ($recordCategory=='General Registration'){
        $table = 'registrations';
        $statusColumn = 'registrantStatus';

        if ($recordStatus){
            if ($recordStatus == 'Verified' || $recordStatus == 'Unverified') {
              $statusColumn = 'registrantVerificationStatus';  
            }
        }
        $dateColumn = 'registrantCreatedAt';
        $subColumn = 'registrantAccountType';
        $searchColumn = 'registrantAccountName';
        $idColumn = 'registrantId';
        $registrantIdColumn = $idColumn;

        $recordType = 'general-registration';
    }

    if ($recordCategory=='Other Registration'){
        $table = 'other_registrations';
        $statusColumn = 'otherStatus';
        $dateColumn = 'otherTimestamp';
        $subColumn = 'otherType';

        $idColumn = 'otherId';
        $registrantIdColumn = 'otherUserId';
        $recordType = 'other-registration';
        $searchColumn = 'otherRegistrantAccountName';
    }

    if ($recordCategory=='Subscription'){
        $table = 'registrant_subscriptions';
        $statusColumn = 'registrant_subscriptionStatus';
        $dateColumn = 'registrant_subscriptionTimestamp';
        $subColumn = 'registrant_subscriptionType';
        $idColumn = 'registrant_subscriptionId';
        $registrantIdColumn = 'registrant_subscriptionUserId';

        $recordType = 'subscription';
        $searchColumn = 'registrant_subscriptionRegistrantAccountName';
    }

    if ($recordCategory=='Promotion'){
        $table = 'promotions';
        $statusColumn = 'promotionStatus';
        $dateColumn = 'promotionTimestamp';
        $subColumn = 'promotionType';
        $idColumn = 'promotionId';

        $recordType = 'promotion';
        $searchColumn = 'promotionNameCompany';
    }


     if ($recordCategory=='Messages'){
        $table = 'admin_messages';
        $statusColumn = 'admin_messageStatus';
        $dateColumn = 'admin_messageTimestamp';
        $subColumn = 'admin_messageType';
        $idColumn = 'admin_messageId';
        $registrantIdColumn = 'admin_messageRegistrantId';
        $recordType = 'message';
        $searchColumn = 'admin_messageRegistrantAccountName';
    }



   
    
   
    if (!$recordSubCategory && !$recordStatus && !$recordDate && !$recordSearch) {
        $sqlCreteria = ' FROM '.$table;
    }
  

    if ($recordSubCategory || $recordStatus || $recordDate || $recordSearch) {

        

        $sqlCreteria = ' FROM '.$table.' WHERE ';
        $additionalCreteria = [];


        if ($recordSubCategory) {
            array_push($additionalCreteria, "$subColumn = '$recordSubCategory'");
        }

        if ($recordStatus) {
            array_push($additionalCreteria, "$statusColumn = '$recordStatus'");
        }

        if ($recordDate) {
            array_push($additionalCreteria, "$dateColumn LIKE '%$recordDate%'");
        }

        if ($recordSearch) {
            array_push($additionalCreteria, "$searchColumn LIKE '%$recordSearch%'");
        }

        $sqlCreteria .= implode(' AND ', $additionalCreteria);

         
    }



  



    // $sqlCount = 'SELECT *'. $sqlCreteria;
    $sqlRecords = 'SELECT *'. $sqlCreteria.' ORDER BY '.$dateColumn.' DESC';



    $sqlRecordsResult= mysqli_query($conn,$sqlRecords);
    $recordsCount = $sqlRecordsResult->num_rows;

     echo "
        <div>
            <p>$recordsCount results</p>
        </div>";
    
        if ($recordCategory == 'Promotion') {
        echo 
        "<div>
            <a id='new-promotion' class='link-tag-button'>Add Promotion</a>
        </div>";
        }
        
     echo "     
        <hr>
    ";

    if ($sqlRecordsResult->num_rows>0){
       
        echo "
        <div id='sm-records-list' style='display:flex;flex-wrap: wrap;  gap:5px;justify-content:left;padding:0px;!important;'>
        ";
        while($listRecords = $sqlRecordsResult->fetch_assoc()){
            $listRecordId = $listRecords  [$idColumn];
            
            $listRecordSubCategory = $listRecords  [$subColumn];
            $listRecordStatus = $listRecords  [$statusColumn];
            $listRecordDate = dcomplete_format($listRecords  [$dateColumn]);

            if ($recordCategory != 'Promotion'){
                $listRecordRegistrantId = $listRecords  [$registrantIdColumn];

                $sqlListRegistrant = "SELECT * FROM registrations WHERE registrantId = '$listRecordRegistrantId'";
                $sqlListRegistrantResult = mysqli_query($conn,$sqlListRegistrant);
                $listRegistrant = $sqlListRegistrantResult->fetch_assoc();
                
                if($listRegistrant){
                    $listRecordRegistrant =  $listRegistrant ['registrantAccountName'];
                }

            }


            if ($recordCategory == 'Promotion'){
                $listRecordRegistrant = $listRecords ['promotionNameCompany'];
            }


            if ($recordCategory == 'General Registration') {
                $listRecordVerificationStatus = $listRecords['registrantVerificationStatus'];
                $listRecordBirthdate = dMDY_format($listRecords['registrantBirthdate']);
                if ($listRecordSubCategory != 'Personal'){
                     $listRecordBirthdate = '-';
                }
                $listRecordGender = $listRecords['registrantGender'];
                $listRecordCivilStatus = $listRecords['registrantCivilStatus'];
                $listRecordEducationalAttainment = $listRecords['registrantEducationalAttainment'];
                $listRecordSchool = $listRecords['registrantSchool'];
                $listRecordOccupation = $listRecords['registrantOccupation'];
                $listRecordEmailAddress = $listRecords['registrantEmailAddress'];
                $listRecordMobileNumber = $listRecords['registrantMobileNumber'];
                $listRecordUsername = $listRecords['registrantUsername'];
                
                
                $listRecordBasicRegistration = $listRecords['registrantBasicAccount'];
                $listRecordTeacherRegistration = $listRecords['registrantTeacherAccount'];
                $listRecordWriterRegistration = $listRecords['registrantWriterAccount'];
                $listRecordEditorRegistration = $listRecords['registrantEditorAccount'];
                $listRecordResearchesRegistration = $listRecords['registrantResearchesAccount'];
                $listRecordDeveloperRegistration = $listRecords['registrantDeveloperAccount'];
                $listRecordWebsiteManagerRegistration = '';

               



                //get website manager registrations

                $sqlListRecordWebsiteManagerRegistrations = "SELECT * FROM website_manager_accounts WHERE website_manager_accountRegistrant = $listRecordId";
                $sqlListRecordWebsiteManagerRegistrationsResult =mysqli_query($conn,$sqlListRecordWebsiteManagerRegistrations);
                $listRecordWebsiteManagerRegistrations = $sqlListRecordWebsiteManagerRegistrationsResult->fetch_assoc();

                $listRecordWebsiteManagerSuperManagerRegistration = '';
                $listRecordWebsiteManagerSubscriptionManagerRegistration = '';
                $listRecordWebsiteManagerRegistrationManagerRegistration = '';
                $listRecordWebsiteManagerPromotionManagerRegistration = '';
                $listRecordWebsiteManagerMessageManagerRegistration = '';

                    if ($listRecordWebsiteManagerRegistrations){
                        $listRecordWebsiteManagerSuperManagerRegistration = $listRecordWebsiteManagerRegistrations ['website_manager_accountSuperManager'];

                         $listRecordWebsiteManagerSubscriptionManagerRegistration = $listRecordWebsiteManagerRegistrations ['website_manager_accountSubscriptionManager'];

                          $listRecordWebsiteManagerRegistrationManagerRegistration = $listRecordWebsiteManagerRegistrations ['website_manager_accountRegistrationManager'];

                          $listRecordWebsiteManagerPromotionManagerRegistration = $listRecordWebsiteManagerRegistrations ['website_manager_accountPromotionManager'];

                          $listRecordWebsiteManagerMessageManagerRegistration = $listRecordWebsiteManagerRegistrations ['website_manager_accountMessageManager'];

                          if ($listRecordWebsiteManagerSuperManagerRegistration || $listRecordWebsiteManagerSubscriptionManagerRegistration || $listRecordWebsiteManagerRegistrationManagerRegistration || $listRecordWebsiteManagerPromotionManagerRegistration || $listRecordWebsiteManagerMessageManagerRegistration) {
                                 $listRecordWebsiteManagerRegistration = 'Website Manager';
                          }


                          $listRecordWebsiteManagerRegistrationsComplete = [];


                            if ($listRecordWebsiteManagerSuperManagerRegistration){
                                array_push($listRecordWebsiteManagerRegistrationsComplete,$listRecordWebsiteManagerSuperManagerRegistration);
                            }

                            if ($listRecordWebsiteManagerSubscriptionManagerRegistration){
                                array_push($listRecordWebsiteManagerRegistrationsComplete,$listRecordWebsiteManagerSubscriptionManagerRegistration);
                            }

                             if ($listRecordWebsiteManagerRegistrationManagerRegistration){
                                array_push($listRecordWebsiteManagerRegistrationsComplete,$listRecordWebsiteManagerRegistrationManagerRegistration);
                            }

                             if ($listRecordWebsiteManagerPromotionManagerRegistration){
                                array_push($listRecordWebsiteManagerRegistrationsComplete,$listRecordWebsiteManagerPromotionManagerRegistration);
                            }

                             if ($listRecordWebsiteManagerMessageManagerRegistration){
                                array_push($listRecordWebsiteManagerRegistrationsComplete,$listRecordWebsiteManagerMessageManagerRegistration);
                            }

                            if ($listRecordWebsiteManagerRegistrationsComplete) {
                                $listRecordWebsiteManagerRegistrationsComplete = implode(', ', $listRecordWebsiteManagerRegistrationsComplete);
                            }
                           
                    }

                    

               

                $listRecordRegistrations = [];

                if ($listRecordBasicRegistration) {
                    array_push($listRecordRegistrations,$listRecordBasicRegistration);
                }

                if ($listRecordTeacherRegistration) {
                    array_push($listRecordRegistrations,$listRecordTeacherRegistration);
                }

                if ($listRecordWriterRegistration) {
                    array_push($listRecordRegistrations,$listRecordWriterRegistration);
                }

                if ($listRecordEditorRegistration) {
                    array_push($listRecordRegistrations,$listRecordEditorRegistration);
                }

                if ($listRecordResearchesRegistration) {
                    array_push($listRecordRegistrations,$listRecordResearchesRegistration);
                }

                if ($listRecordDeveloperRegistration) {
                    array_push($listRecordRegistrations,$listRecordDeveloperRegistration);
                }

                if ($listRecordWebsiteManagerRegistration) {
                    array_push($listRecordRegistrations,$listRecordWebsiteManagerRegistration);
                }


                
                if ($listRecordRegistrations) {
                    $listRecordRegistrationsConverted = implode(', ',$listRecordRegistrations);
                } else {
                        $listRecordRegistrationsConverted = 'No registration';
                }
                  


                $listRecordAddressStreet= $listRecords ['registrantAddressStreet'];
                $listRecordAddressBarangay= $listRecords ['registrantAddressBarangay'];
                $listRecordAddressCity= $listRecords ['registrantAddressCity'];
                $listRecordAddressProvince= $listRecords ['registrantAddressProvince'];
                $listRecordAddressRegion= $listRecords ['registrantAddressRegion'];
                $listRecordAddressCountry= $listRecords ['registrantAddressCountry'];
                $listRecordAddressZipCode= $listRecords ['registrantAddressZipCode'];

                $listRecordCompleteAddress = [];
                
                if ($listRecordAddressStreet){
                    array_push( $listRecordCompleteAddress,$listRecordAddressStreet);
                }

                if ($listRecordAddressBarangay){
                    array_push( $listRecordCompleteAddress,$listRecordAddressBarangay);
                }

                if ($listRecordAddressCity){
                    array_push( $listRecordCompleteAddress,$listRecordAddressCity);
                }

                    if ($listRecordAddressProvince){
                    array_push( $listRecordCompleteAddress,$listRecordAddressProvince);
                }

                    if ($listRecordAddressRegion){
                    array_push( $listRecordCompleteAddress,$listRecordAddressRegion);
                }

                    if ($listRecordAddressCountry){
                    array_push( $listRecordCompleteAddress,$listRecordAddressCountry);
                }

                    if ($listRecordAddressZipCode){
                    array_push( $listRecordCompleteAddress,$listRecordAddressZipCode);
                }


                if ($listRecordCompleteAddress) {
                    $listRecordCompleteAddressConverted = implode(',',$listRecordCompleteAddress);
                } else {
                        $listRecordCompleteAddressConverted = 'No address';
                }
                
            }

            if ($recordCategory == 'Subscription') {
                $listRecordType = $listRecords['registrant_subscriptionType'];
                $listRecordDuration = $listRecords['registrant_subscriptionDuration'];

                if ($listRecordType !='Shelf') {
                    $listRecordDuration= $listRecordDuration.' Month(s)';
                }

                if ($listRecordType =='Shelf') {
                    $listRecordDuration= $listRecordDuration.' Year(s)';
                }

                
                $listRecordTotal = $listRecords['registrant_subscriptionTotal'];
                $listRecordPaymentOption = $listRecords['registrant_subscriptionPaymentOption'];
                $listRecordAccountNumber = $listRecords['registrant_subscriptionSenderAccountNumber'];
                $listRecordReferenceNumber = $listRecords['registrant_subscriptionRefNumber'];
                $listRecordProofOfPayment = $privateFolder.$listRecords['registrant_subscriptionProofOfPayment'];
                $listRecordApprovalDate = $listRecords['registrant_subscriptionDate'] ? dcomplete_format($listRecords['registrant_subscriptionDate'])  : '-';
                $listRecordExpiryDate = $listRecords['registrant_subscriptionExpiry'] ? dcomplete_format($listRecords['registrant_subscriptionExpiry']) : '-';
            
            }



            echo "
                <div id='".'details-for-record-'.$listRecordId."' class='sm-list-record-details'>
                    
                    <h5>$listRecordRegistrant</h5>";      
            echo "
                    <div>
                        <strong>Type:</strong>
                        <span >$listRecordSubCategory</span>
                    </div>

                    <div>
                        <strong>Status:</strong>
                        <span >$listRecordStatus</span>
                    </div>

                    <div>
                        <strong>Date/Time:</strong>
                        <span >$listRecordDate</span>
                    </div>
                ";



                if ($recordCategory == 'General Registration') {

                if ($recordStatus != 'Verified' && $recordStatus != 'Unverified'){
                    echo "
                        <div>
                        <strong>Verification: </strong>
                        <span>$listRecordVerificationStatus</span>
                        </div>
                    ";
                    
                }

                echo "
                        <div>
                                <strong>Email Address:</strong>
                                <span >$listRecordEmailAddress</span>
                        </div>
                         <div>
                                <strong>Mobile Number:</strong>
                                <span >$listRecordMobileNumber</span>
                        </div>
                        <div>
                                <strong>Username:</strong>
                                <span >$listRecordUsername</span>
                        </div>
                ";
                   
                    if ($listRecordSubCategory == 'Personal'){
                        
                        echo "
                            <div>
                                <strong>Birthdate:</strong>
                                <span >$listRecordBirthdate</span>
                            </div>

                            <div>
                                <strong>Gender:</strong>
                                <span >$listRecordGender</span>
                            </div>

                             <div>
                                <strong>Civil Status:</strong>
                                <span > $listRecordCivilStatus</span>
                            </div>

                            <div>
                                <strong>Educational Attainment:</strong>
                                <span >  $listRecordEducationalAttainment</span>
                            </div>
                            <div>
                                <strong>School:</strong>
                                <span > $listRecordSchool</span>
                            </div>
                            <div>
                                <strong>Occupation:</strong>
                                <span > $listRecordOccupation</span>
                            </div>
  
                            ";
                    }


                    if ($listRecordSubCategory == 'School'){
                        
                        echo "
                           

                           
  
                            ";
                    }


                    echo "
                        <div>
                            <strong>Registrations: </strong>
                            <span>$listRecordRegistrationsConverted</span>
                        </div>
                        <div>
                            <strong>Address: </strong>
                            <span>$listRecordCompleteAddressConverted</span>
                        </div>
                    
                    
                    
                    ";
                }


                if ($recordCategory == 'Subscription') {
                    echo "
                    <div>
                        <strong>Duration:</strong>
                        <span >$listRecordDuration</span>
                    </div>

                    <div>
                        <strong>Total:</strong>
                        <span >₱$listRecordTotal</span>
                    </div>

                    <div>
                        <strong>Payment Option:</strong>
                        <span >$listRecordPaymentOption</span>
                    </div>

                  
                    <div>
                        <strong>Reference Number:</strong>
                        <span >$listRecordReferenceNumber</span>
                    </div>

                    <div class='proof-of-payment'>
                        <strong>Proof of Payment:</strong>
                        <span id='$listRecordProofOfPayment'>🔗</span>
                    </div>

                    <div>
                        <strong>Approved:</strong>
                        <span >$listRecordApprovalDate</span>
                    </div>

                    <div>
                        <strong>Expired:</strong>
                        <span>$listRecordExpiryDate</span>
                    </div>
                    ";

                }



            

                
              


                if ($recordCategory == 'Other Registration') {
                    $listRecordSample = $listRecords['otherSample'];
                    $listRecordLicenseCertification = $privateFolder.$listRecords['otherLicenseCertification'];
                    $listRecordAgreement = $privateFolder.$listRecords['otherAgreement'];
               

                    if ($listRecordSubCategory != 'Teacher') {
                        echo "
                        <div>
                            <strong>Sample(s):</strong>
                             <a href='".$listRecordSample."' target='_blank'>🔗</a>
                        </div>
                        ";
                    }

                    if ($listRecordSubCategory== 'Teacher') {
                        echo "
                        <div class='other-registration-license-certification'>
                            <strong>License or Certification : </strong>
                             <span id='".$listRecordLicenseCertification."'>🔗</span>
                        </div>
                        ";
                    }
    
                    
                        echo "
                        <div class='other-registration-agreement'>
                            <strong>Agreement : </strong>
                            <span  id='".$listRecordAgreement."'>🔗</span>
                        </div>
                          
                        ";
                }

              


                if ($recordCategory == 'Promotion') {
                    $listRecordTitle = $listRecords ['promotionTitle'];
                    $listRecordTopics = $listRecords ['promotionTopics'];
                    $listRecordDescription = $listRecords ['promotionDescription'];
                    $listRecordDuration = $listRecords ['promotionDuration'];
                    $listRecordAmount = $listRecords ['promotionAmount'];
                    $listRecordLink = $listRecords ['promotionLink'];
                    $listRecordImageLink = $listRecords ['promotionImage'];
                    $listRecordAgreementLink = $listRecords ['promotionAgreement'];
                    echo "
                    <div style='display:flex;'>
                        <strong>Title: </strong>
                        <span>$listRecordTitle</span>
                    </div>
                    <div>
                        <input id='".'update-promotion-name-company-'.$listRecordId."' value='".$listRecordRegistrant."' hidden>
                        <input id='".'update-promotion-title-'.$listRecordId."' value='".$listRecordTitle."' hidden>
                         <input id='".'update-promotion-type-'.$listRecordId."' value='".$listRecordSubCategory."' hidden>
                        <input id='".'update-promotion-topics-'.$listRecordId."' value='".$listRecordTopics."' hidden>
                        <input id='".'update-promotion-description-'.$listRecordId."' value='".$listRecordDescription."' hidden>
                         <input id='".'update-promotion-duration-'.$listRecordId."' value='".$listRecordDuration."' hidden>
                          <input id='".'update-promotion-amount-'.$listRecordId."' value='".$listRecordAmount."' hidden>
                          <input id='".'update-promotion-link-'.$listRecordId."' value='".$listRecordLink."'hidden>
                          <input id='".'update-promotion-image-link-'.$listRecordId."' value='".$listRecordImageLink."' hidden>
                          <input id='".'update-promotion-agreement-link-'.$listRecordId."' value='".$listRecordAgreementLink."' hidden>
                    </div>
                          
                    ";
                }




                    
            echo "
                    <div id='sm-record-actions' style='margin-top:10px;'>

                    ";


                       if ($recordCategory != 'Promotion') {

                      
                        if ($listRecordStatus =='Pending') {
                    
                        echo "
                            <span class='link-tag-button' id='".'approve-'.$recordType.'-'.$listRecordSubCategory.'-'.$listRecordId."'>Approve</span>
                            
                            <span class='link-tag-button' id='".'reject-'.$recordType.'-'.$listRecordSubCategory.'-'.$listRecordId."'>Reject</span>
                        ";
                        }


                         if ($listRecordStatus =='Rejected') {
                            echo "
                                <span class='link-tag-button' id='".'approve-'.$recordType.'-'.$listRecordSubCategory.'-'.$listRecordId."'>Approve</span>
                            ";
                        }

                        
                        if ($listRecordStatus =='Approved' || $listRecordStatus =='Kept' || $listRecordStatus =='No Ban') {
                        echo "
                            <span class='link-tag-button' id='".'revoke-'.$recordType.'-'.$listRecordSubCategory.'-'.$listRecordId."'>Revoke</span>
                        ";
                        }


                         if ($listRecordStatus =='Revoked') {
                        echo "
                            <span class='link-tag-button' id='".'keep-'.$recordType.'-'.$listRecordSubCategory.'-'.$listRecordId."'>Keep</span>
                        ";
                        }

                        if ($recordCategory == 'General Registration'){
                             echo "
                            <a class='link-tag-button' href='".$publicFolder.'/'.$listRecordUsername."' target='_blank'>Visit Profile</a>
                            ";
                        }

                        }


                        if ($recordCategory == 'Promotion') {
                        echo "
                        <span class='link-tag-button' id='".'show-'.$recordType.'-'.$listRecordId."'>Show</span>
                        ";

                        if ($listRecordStatus !='Published') {
                        echo "
                        <span class='link-tag-button' id='".'status-to-publish-'.$recordType.'-'.$listRecordId."'>Publish</span>";
                        }

                        if ($listRecordStatus =='Published') {
                        echo "
                       <span class='link-tag-button' id='".'status-to-unpublish-'.$recordType.'-'.$listRecordId."'>Unpublish</span>";
                        }
                            

                        }

                    if ($recordCategory == 'Promotion') {
                        if ($websiteManagerSuperManagerRegistration) {
                            echo "
                                <span class='link-tag-button active' id='".'delete-'.$recordType.'-'.$listRecordId."'>Delete</span>
                            ";
                        }
                    }


                echo "
                        
                    </div>";

                
                if ($recordCategory == 'General Registration') {
                    if ($websiteManagerSuperManagerRegistration) {
                            echo "
                             <div id='website-manager-registration-buttons'>
                            ";

                            if(!$listRecordWebsiteManagerSuperManagerRegistration) {
                            echo "
                                <span class='link-tag-button' id='".'to-register-sm-super-manager-'.$listRecordId."'>✚ Super Manager</span>
                            ";
                            }

                            if($listRecordWebsiteManagerSuperManagerRegistration) {
                            echo "
                                <span class='link-tag-button active' id='".'to-unregister-sm-super-manager-'.$listRecordId."'>▬ Super Manager</span>
                            ";
                            }


                            if(!$listRecordWebsiteManagerRegistrationManagerRegistration) {
                            echo "
                                <span class='link-tag-button' id='".'to-register-sm-registration-manager-'.$listRecordId."'>✚ Registration Manager</span>
                            ";
                            }

                            if($listRecordWebsiteManagerRegistrationManagerRegistration) {
                            echo "
                                <span class='link-tag-button active' id='".'to-unregister-sm-registration-manager-'.$listRecordId."'>▬ Registration Manager</span>
                            ";
                            }

                            if(!$listRecordWebsiteManagerSubscriptionManagerRegistration) {
                            echo "
                                <span class='link-tag-button' id='".'to-register-sm-subscription-manager-'.$listRecordId."'>✚ Subscription Manager</span>
                            ";
                            } 

                            if($listRecordWebsiteManagerSubscriptionManagerRegistration) {
                            echo "
                                <span class='link-tag-button active' id='".'to-unregister-sm-subscription-manager-'.$listRecordId."'>▬ Subscription Manager</span>
                            ";
                            } 

                            if(!$listRecordWebsiteManagerPromotionManagerRegistration) {
                            echo "
                                <span class='link-tag-button' id='".'to-register-sm-promotion-manager-'.$listRecordId."'>✚ Promotion Manager</span>
                            ";
                            }

                            if($listRecordWebsiteManagerPromotionManagerRegistration) {
                            echo "
                                <span class='link-tag-button active' id='".'to-unregister-sm-promotion-manager-'.$listRecordId."'>▬ Promotion Manager</span>
                            ";
                            }
                            

                             if(!$listRecordWebsiteManagerMessageManagerRegistration) {
                            echo "
                                <span class='link-tag-button' id='".'to-register-sm-message-manager-'.$listRecordId."'>✚ Message Manager</span>
                            ";
                            }

                             if($listRecordWebsiteManagerMessageManagerRegistration) {
                            echo "
                                <span class='link-tag-button active' id='".'to-unregister-sm-message-manager-'.$listRecordId."'>▬ Message Manager</span>
                            ";
                            }

                            echo "
                             </div>
                            ";
                        }

                }
                    
                    
            echo"
                </div>
                <hr>
               
                ";



        }

        echo "
        </div>
        ";


    } 

    

}






if (isset($_POST['update_website_manager_registrations_submit'])){
    $recordId = htmlspecialchars($_POST['record_id']);
    $websiteManagerType = htmlspecialchars($_POST['website_manager_type']);
    $action = htmlspecialchars($_POST['action']);

    $websiteManagerValue = $websiteManagerType;
   
   
    if ($action =='unregister'){
        $websiteManagerValue = '';
    }

    $websiteManagerType = str_replace(' ','',$websiteManagerType);
    $websiteManagerColumn = 'website_manager_account'.$websiteManagerType;



    $responses = [];

    $sqlCheckWebsiteManagerRegistrations = "SELECT * FROM website_manager_accounts WHERE website_manager_accountRegistrant='$recordId'";
    $sqlCheckWebsiteManagerRegistrationsResult = mysqli_query($conn,$sqlCheckWebsiteManagerRegistrations);
    $checkedWebsiteManagerRegistrations = $sqlCheckWebsiteManagerRegistrationsResult->fetch_assoc();

    if ($checkedWebsiteManagerRegistrations) {

     if ($action =='unregister'){
        $websiteManagerValue = '';
    }
        
    $sqlUpdateWebsiteManagerRegistration = "UPDATE website_manager_accounts 
                        SET
                       $websiteManagerColumn =? 
                       WHERE website_manager_accountRegistrant='$recordId'";

    $stmt =$conn->prepare($sqlUpdateWebsiteManagerRegistration );
    $stmt ->bind_param("s", $websiteManagerValue);
    $stmt-> execute();

     $responses ['status']='Successful';
    }

    if (!$checkedWebsiteManagerRegistrations){
        $sqlInsertWebsiteManagerRegistration = "INSERT INTO website_manager_accounts ($websiteManagerColumn,website_manager_accountRegistrant) VALUES ( ?, ?)";

        $stmt =$conn->prepare($sqlInsertWebsiteManagerRegistration);

        $stmt ->bind_param("ss", $websiteManagerValue,$recordId);

        $stmt-> execute();

         $responses ['status']='Successful';
    }



   




    if ($responses) {
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        echo  $jsonResponses;
    } 

   

}



if (isset($_POST['promotion_submit'])) {

$promotionId = htmlspecialchars($_POST['promotion_id']);

$promotionNameCompany = htmlspecialchars($_POST['promotion_name_company']);
$promotionTitle = htmlspecialchars($_POST['promotion_title']);
$promotionType = htmlspecialchars($_POST['promotion_type']);
$promotionTopics = htmlspecialchars($_POST['promotion_topics']);
$promotionDescription = htmlspecialchars($_POST['promotion_description']);
$promotionLink = htmlspecialchars($_POST['promotion_link']);
$promotionDuration = htmlspecialchars($_POST['promotion_duration']);
$promotionAmount = htmlspecialchars($_POST['promotion_amount']);

$promotionImageLink = htmlspecialchars($_POST['promotion_image_link']);
$promotionAgreementLink = htmlspecialchars($_POST['promotion_agreement_link']);



$promotionImage = '';
$promotionImageFileName= '';
$promotionImageFileNameExt = '';
$promotionImageFileNameActualExt = '';

if (isset($_FILES['promotion_image'])) {
  $promotionImage = $_FILES ['promotion_image'];
  $promotionImageFileName= $promotionImage ['name'];
  $promotionImageFileNameExt = explode ('.',$promotionImageFileName);
  $promotionImageFileNameActualExt = strtolower(end($promotionImageFileNameExt));
}

$promotionAgreement = '';
$promotionAgreementFileName= '';
$promotionAgreementFileNameExt = '';
$promotionAgreementFileNameActualExt = '';

if (isset($_FILES['promotion_agreement'])) {
  $promotionAgreement = $_FILES ['promotion_agreement'];
  $promotionAgreementFileName= $promotionAgreement ['name'];
  $promotionAgreementFileNameExt = explode ('.',$promotionAgreementFileName);
  $promotionAgreementFileNameActualExt = strtolower(end($promotionAgreementFileNameExt));
}

$allowedExtPromotionImage = ['jpeg','jpg'];
$allowedExtPromotionAgreement = ['pdf'];

;
$responses = [];
$responses ['error'] = [];


if (!$promotionNameCompany) {
  $error= "Please provide a person's or company's name.";
  array_push($responses ['error'],$error);
  
}

if (!$promotionTitle) {
  $error= "Please provide a title.";
   array_push($responses ['error'],$error);
}

if (!$promotionType) {
  $error= "Please select a type.";
  array_push($responses ['error'],$error);
}

if (!$promotionTopics) {
  $error= "Please provide at least one topic.";
  array_push($responses ['error'],$error);
}

if (!$promotionDescription) {
  $error= "Please provide a description.";
  array_push($responses ['error'],$error);
}

if (!$promotionLink) {
  $error= "Please provide a link.";
  array_push($responses ['error'],$error);
}

if (!$promotionDuration) {
  $error= "Please provide a duration.";
   array_push($responses ['error'],$error);
}

if (!$promotionAmount) {
  $error= "Please provide an amount.";
  array_push($responses ['error'],$error);
}

if (!$promotionId) {

  if (!$promotionImageFileName) {
    $error= "Please upload the image.";
   array_push($responses ['error'],$error);
  }

  if (!$promotionAgreement) {
    $error= "Please upload the agreement.";
   array_push($responses ['error'],$error);

}
}


if (!$responses['error']) {

  $promotionDate = dcomplete_format ($currentTime);
  $promotionExpiry = dcomplete_format($currentTime + (86400 * $promotionDuration));

  $slugNameCompany = str_replace(" ","_",strtolower($promotionNameCompany));
 
  $promotionImageFileLink = $promotionImageLink;

  if ($promotionImageFileName)  {
      $promotionImageFolder = '../../uploads/promotion/image/';

      if (!is_dir($promotionImageFolder)) {
        mkdir($promotionImageFolder, 0777, true);
    }

    $promotionImageFile = $promotionImageFolder.$slugNameCompany."-".date("YmdHis",time()).".".$promotionImageFileNameActualExt;

    if (move_uploaded_file($promotionImage["tmp_name"], $promotionImageFile)) {
      $uploadOk = 1;
    }

    $promotionImageFileLink= substr($promotionImageFile,5);
  } 

  

  $promotionAgreementFileLink= $promotionAgreementLink;

  if ($promotionAgreementFileName) {
    $promotionAgreementFolder = '../../uploads/promotion/agreement/'; 

    if (!is_dir($promotionAgreementFolder)) {
        mkdir($promotionAgreementFolder, 0777, true);
    }

    $promotionAgreementFile = $promotionAgreementFolder.$slugNameCompany."-".date("YmdHis",time()).".".$promotionAgreementFileNameActualExt;

    $uploadOk = 1;

    if (move_uploaded_file($promotionAgreement["tmp_name"], $promotionAgreementFile)) {
      $uploadOk = 1;
    }
       
    $promotionAgreementFileLink= substr($promotionAgreementFile,5);

  } 
    


  if ($promotionId){
    $promotion_Id = $promotionId;
  }
  
  if (!$promotionId) {

     $promotionStatus = "Draft";

     $sqlAddPromotion = "INSERT INTO promotions (promotionNameCompany,promotionTitle,promotionType, promotionTopics,promotionDescription,promotionImage,promotionLink,promotionDuration,promotionAmount,promotionStatus,promotionAgreement) VALUES ( ?, ?, ?, ?,?,?,?,?,?,?,?)";

    $stmt =$conn->prepare($sqlAddPromotion);

    $stmt ->bind_param("sssssssssss", $promotionNameCompany,$promotionTitle,$promotionType,$promotionTopics,$promotionDescription,$promotionImageFileLink,$promotionLink,$promotionDuration,$promotionAmount,$promotionStatus,$promotionAgreementFileLink);

    $stmt-> execute();

    echo 'Promotion Sent';

    $promotion_Id =  mysqli_insert_id($conn);

  } 



  if ($promotion_Id) {

     $sqlUpdatePromotion = "UPDATE promotions 
                        SET
                        promotionNameCompany =?,
                        promotionTitle =?,
                        promotionType=?, 
                        promotionTopics=?,
                        promotionDescription=?,
                        promotionImage=?,
                        promotionLink=?,
                        promotionDuration=?,
                        promotionAmount=?,
                        promotionAgreement=?
                       WHERE promotionId=$promotion_Id";

    $stmt =$conn->prepare($sqlUpdatePromotion);

    $stmt ->bind_param("ssssssssss", $promotionNameCompany,$promotionTitle,$promotionType,$promotionTopics,$promotionDescription,$promotionImageFileLink,$promotionLink,$promotionDuration,$promotionAmount,$promotionAgreementFileLink);

    $stmt-> execute();

    $promotion_Id= (int) $promotion_Id;

    $promotionCode = "ESKQUIPPROMOTE" . sprintf("%09d",  4271997/$promotionId);

    $sqlUpdatePromotionCode = "UPDATE promotions 
                                SET promotionCode = ?
                                WHERE promotionId = '$promotion_Id'";
    $stmt =$conn->prepare($sqlUpdatePromotionCode);
    $stmt ->bind_param("s", $promotionCode);
    $stmt-> execute();

    $responses ['status'] = 'Successful';
    $responses ['success-message'] = 'This promotion has been saved successfully.';
  }
  
  
  

   
   
} else {
  $responses ['status'] ='Unsuccessful';
}

if ($responses) {
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        echo  $jsonResponses;
    } 
  


}



if (isset($_POST['initialize_subscription_submit'])){
    $subscriptionType = htmlspecialchars($_POST['subscription_type']);

    $responses = [];
    $responses ['subscribed'] = false;
    $responses ['pending'] = false;

    if ($subscriptionType=='Tools'){
        if ($pendingToolSubscription){
            $responses ['pending'] = true;
        }

         if ($toolSubscribed){
            $responses ['subscribed'] = true;
        }
    }


     if ($subscriptionType=='Seller'){
        if ($pendingSellerSubscription){
            $responses ['pending'] = true;
        }

         if ($sellerSubscribed){
            $responses ['subscribed'] = true;
        }
    }

      if ($subscriptionType=='Shelf'){
        if ($pendingShelfSubscription){
            $responses ['pending'] = true;
        }

         if ($shelfSubscribed){
            $responses ['subscribed'] = true;
        }
    }

      if ($subscriptionType=='Files'){
        if ($pendingFileSubscription){
            $responses ['pending'] = true;
        }

         if ($shelfSubscribed){
            $responses ['subscribed'] = true;
        }
    }


    if ($responses) {
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        echo  $jsonResponses;
    } 
}

if(isset($_POST['subscription_submit'])) {
        
            $subscriptionType = htmlspecialchars($_POST ['subscription_type']);        
            $duration = htmlspecialchars($_POST ['subscription_duration']);
            $total = htmlspecialchars($_POST ['subscription_total']);
            $paymentOption = htmlspecialchars($_POST ['subscription_payment_option']);
            $referenceNumber = htmlspecialchars($_POST ['subscription_reference_number']);

            $responses = [];
           
            $responses ['error'] = [];

            if (!$subscriptionType) {
                $error = "Please select subscription type.";
                array_push($responses ['error'],$error);
            } else {
                if ($subscriptionType =='Tools'){
                    if ($pendingToolSubscription){
                        $error = "Please wait for the approval.";
                        array_push($responses ['error'],$error);
                    }
                     if ($toolSubscribed){
                        $error = "You are currently subscribed.";
                        array_push($responses ['error'],$error);
                    }  
                }

                if ($subscriptionType =='Seller'){
                    if (!$teacherRegistration) {
                         $error = "Please register as Teacher to avail this subscription.";
                        array_push($responses ['error'],$error);

                    } else {
                        if ($pendingSellerSubscription){
                        $error = "Please wait for the approval.";
                            array_push($responses ['error'],$error);
                        }
                        if ($sellerSubscribed){
                            $error = "You are currently subscribed.";
                            array_push($responses ['error'],$error);
                        }  

                    }
                   
                }

                  if ($subscriptionType =='Shelf'){
                    if ($pendingShelfSubscription){
                        $error = "Please wait for the approval.";
                        array_push($responses ['error'],$error);
                    }
                     if ($shelfSubscribed){
                        $error = "You are currently subscribed.";
                        array_push($responses ['error'],$error);
                    }  
                }

                  if ($subscriptionType =='Files'){
                    if ($pendingFileSubscription){
                        $error = "Please wait for the approval.";
                        array_push($responses ['error'],$error);
                    }
                     if ($fileSubscribed){
                        $error = "You are currently subscribed.";
                        array_push($responses ['error'],$error);
                    }  
                }
            }

            if (!$duration) {
                 $error = "Please provide the duration.";
                array_push($responses ['error'],$error);
            }

            if (!$paymentOption) {
                 $error = "Please select a payment option.";
                array_push($responses ['error'],$error);
            }

            if (!$referenceNumber) {
                 $error = "Please provide the reference number.";
                array_push($responses ['error'],$error);
            }
        
         


            $proofOfPayment = '';
            $proofOfPaymentFileName ='';
            $proofOfPaymentFileNameFileNameExt = '';
            $proofOfPaymentFileNameActualExt = '';

            $allowedExtsProofOfPayment = ['jpeg', 'jpg'];
             

            if (isset($_FILES ['subscription_proof_of_payment'])) {

            $proofOfPayment = $_FILES ['subscription_proof_of_payment'];
            $proofOfPaymentFileName = $proofOfPayment ['name'];
            $proofOfPaymentFileNameFileNameExt = explode ('.',$proofOfPaymentFileName);
            $proofOfPaymentFileNameActualExt = strtolower(end($proofOfPaymentFileNameFileNameExt));
             
            } 
            
            
           if (!$proofOfPaymentFileName) {
                $error = 'Please provide a proof of payment.';
                array_push($responses ['error'],$error);
            } else {
                if (!in_array($proofOfPaymentFileNameActualExt,$allowedExtsProofOfPayment)) {
                $error = 'Please attach a file in any of these formats: '.implode(', ',$allowedExtsProofOfPayment).'.';
                array_push($responses ['error'],$error);
                } 
            }


            if (!empty($duration)) {
                
                if (!is_numeric($duration)) {
                        $error = "Please enter a number only for duration.";
                        array_push($responses ['error'],$error);
        
                } else {
                        if ($duration < 1) {
                        $error = "Duration must be greater than 0.";
                        array_push($responses ['error'],$error);
                        }

                } 

            }
        
        
        if (!$responses ['error'] ) {
                $proofOfPaymentFolder = '../../uploads/subscription/proof/';
            
                if (!is_dir($proofOfPaymentFolder)) {
                mkdir($proofOfPaymentFolder, 0777, true);
                }

                $proofOfPaymentFile = $proofOfPaymentFolder ."userid-".$registrantId."-".date("YmdHis",time()).".".$proofOfPaymentFileNameActualExt;

                $uploadOk = 1;

              
              if (move_uploaded_file($proofOfPayment["tmp_name"], $proofOfPaymentFile)) {
                  $uploadOk = 1;
              } 

                $proofOfPaymentLink= substr($proofOfPaymentFile,5);

                
                $sql = "INSERT INTO registrant_subscriptions (registrant_subscriptionUserId,registrant_subscriptionType,registrant_subscriptionDuration,registrant_subscriptionTotal,registrant_subscriptionPaymentOption,registrant_subscriptionRefNumber,registrant_subscriptionProofOfPayment) VALUES (?, ?,?, ?, ?,?,?)";

                $stmt =$conn->prepare($sql);

                $stmt ->bind_param("sssssss", $registrantId, $subscriptionType,$duration,$total,$paymentOption, $referenceNumber,$proofOfPaymentLink);

                $stmt-> execute();

                $responses ['status'] = 'Successful';

                $responses ['success-message'] = 'Your subscription for '.$subscriptionType.' has been submitted successfully!';
        
        } else {   
                $responses ['status'] = 'Unsuccessful';
        }



        if ($responses) {
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        echo  $jsonResponses;
    } 

} 



if (isset($_POST['get_subscriptions_submit'])){
    $subscriptionType = htmlspecialchars($_POST['subscription_type']);

    if ($subscriptionType){

        $sqlSubscriptions = "SELECT * FROM registrant_subscriptions WHERE registrant_subscriptionType = '$subscriptionType' AND registrant_subscriptionUserId= $registrantId ORDER BY registrant_subscriptionId DESC";
        $sqlSubscriptionsResult = mysqli_query($conn,$sqlSubscriptions);

        if ($sqlSubscriptionsResult->num_rows > 0){
            echo"
                <table>
                    <tr>
                        <th style='width:150px;'>Timestamp</th>
                        <th style='width:70px;'>Status</th>
                        <th style='width:70px;'>Actions</th>
                    </tr>
                

                ";
            while ($subscriptions = $sqlSubscriptionsResult ->fetch_assoc()) {
                $subscriptionTimestamp = dcomplete_format($subscriptions ['registrant_subscriptionTimestamp']);
                $subscriptionActivation = dcomplete_format($subscriptions ['registrant_subscriptionDate']);
                $subscriptionExpiry = dcomplete_format($subscriptions ['registrant_subscriptionExpiry']);
                $subscriptionStatus = $subscriptions ['registrant_subscriptionStatus'];
                $subscriptionId = $subscriptions ['registrant_subscriptionId'];
                $subscriptionProof = $subscriptions ['registrant_subscriptionProofOfPayment'] ? $privateFolder.$subscriptions ['registrant_subscriptionProofOfPayment'] : '';

                echo"
                    <tr class='subscription-row'>
                        <td style='width:150px;'>$subscriptionTimestamp</td>
                        <td style='width:70px;'>$subscriptionStatus</td>
                        <td style='margin-top:-8px;' class='subscription-actions' style='width:70px;'>
                            <span class='link-tag-button' id='".'show-details-for-subscription-'.$subscriptionId."'>Details</span>
                        </td>
                        
                    </tr>
                    ";
            }

            echo "
                </table>
                ";
        } else {
            echo "
                <small>No subscription yet.</small>
            ";
        }
        
    }
}



if (isset($_POST['get_subscription_details_submit'])){
    $subscriptionId = htmlspecialchars($_POST['subscription_id']);

    $responses = [];

    $sqlSubscriptionDetails = "SELECT * FROM registrant_subscriptions WHERE registrant_subscriptionId =$subscriptionId LIMIT 1";
    $sqlSubscriptionDetailsResult = mysqli_query($conn,$sqlSubscriptionDetails);
    $subscriptionDetails = $sqlSubscriptionDetailsResult->fetch_assoc();

    if ($subscriptionDetails){

        $subscriptionType = $subscriptionDetails ['registrant_subscriptionType'];
        $durationType = 'Month(s)';

        if ($subscriptionType == 'Shelf'){
             $durationType = 'Year(s)';
        }

       
        $responses ['timestamp'] = dcomplete_format($subscriptionDetails ['registrant_subscriptionTimestamp']);
        $responses ['type'] = $subscriptionType;
        $responses ['duration'] = $subscriptionDetails ['registrant_subscriptionDuration'].' '.$durationType;
        $responses ['total'] = $subscriptionDetails ['registrant_subscriptionTotal'];
        $responses ['payment-option'] = $subscriptionDetails ['registrant_subscriptionPaymentOption'];
        $responses ['payment-account-name'] = $subscriptionDetails ['registrant_subscriptionSenderName'];
        $responses ['payment-account-number'] = $subscriptionDetails ['registrant_subscriptionSenderAccountNumber'];
        $responses ['reference-code'] = $subscriptionDetails ['registrant_subscriptionRefNumber'];
        $responses ['payment-proof'] = $subscriptionDetails ['registrant_subscriptionProofOfPayment'] ? $privateFolder.$subscriptionDetails ['registrant_subscriptionProofOfPayment'] : '';
        $responses ['status'] = $subscriptionDetails ['registrant_subscriptionStatus'];
        $responses ['activated'] = $subscriptionDetails ['registrant_subscriptionDate'] ? dcomplete_format($subscriptionDetails ['registrant_subscriptionDate']) : '-';
        $responses ['expired'] = $subscriptionDetails ['registrant_subscriptionExpiry'] ? dcomplete_format($subscriptionDetails ['registrant_subscriptionExpiry']) : '-';

        $responses ['details'] =true;
    } else {
        $responses ['details'] =false;
    }



    if ($responses) {
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        echo  $jsonResponses;
    } 
}



//UPDATE THE PROMOTION STATUS
if (isset($_POST['status_promotion_submit'])) {

  $promotionId = htmlspecialchars($_POST['promotion_id']);
  $toDo = htmlspecialchars($_POST['to_do']);
  $duration = (int) htmlspecialchars($_POST['promotion_duration']);
  $status = '';

  $responses = [];
  $responses ['error'] =[];

  if ($toDo == 'publish') {
    $status ="Published";

  }

  if ($toDo == 'unpublish') {
    $status ="Unpublished";
  }



  $sqlPromotionInfo = "SELECT * FROM promotions WHERE promotionId = '$promotionId'";

  $sqlPromotionInfoResult = mysqli_query($conn,$sqlPromotionInfo);
  $promotionInfo = $sqlPromotionInfoResult->fetch_assoc();

  $promotionDate = $promotionInfo ['promotionDate'] !='0000-00-00 00:00:00' ? $promotionInfo ['promotionDate'] : date("Y-m-d H:i:s", $currentTime);

  $promotionExpiry = $promotionInfo ['promotionExpiry'] !='0000-00-00 00:00:00' ? $promotionInfo ['promotionExpiry'] : date ("Y-m-d H:i:s", strtotime($promotionDate) + ($duration * 86400));


  $sqlUpdatePromotionStatus = "UPDATE promotions 
                      SET promotionStatus = ?,
                      promotionDate = ?,
                      promotionExpiry = ?
                      WHERE promotionId =  $promotionId";

  
  $stmt = mysqli_stmt_init($conn);
  $prepareStmt = mysqli_stmt_prepare($stmt, $sqlUpdatePromotionStatus);
  
  if ($prepareStmt) {
  mysqli_stmt_bind_param($stmt,"sss", $status,$promotionDate,$promotionExpiry);
  mysqli_stmt_execute($stmt);

  $responses ['status']= 'Successful';

  }

  
if ($responses) {
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        echo  $jsonResponses;
    } 

                       
}






//UPDATE THE SUBSCRIPTION
if (isset($_POST['subscription_update'])) {

$subscriptionId=htmlspecialchars($_POST['subscription_id']);
$subscriptionType=htmlspecialchars($_POST['subscription_type']);
$toDo = htmlspecialchars($_POST['to_do']);

$responses = [];
$responses ['error'] = [];

$sqlSubscription = "SELECT * FROM registrant_subscriptions WHERE registrant_subscriptionId = $subscriptionId";
$sqlSubscriptionResult = mysqli_query($conn,$sqlSubscription);
$subscription = $sqlSubscriptionResult->fetch_assoc();

if ($subscription){
    $subscriptionDuration = (int) $subscription ['registrant_subscriptionDuration'];
    $subscriptionDate = $subscription ['registrant_subscriptionDate'];
    $subscriptionExpiry = $subscription ['registrant_subscriptionExpiry'];
}


if($toDo=='approve') {
$status="Approved";
}

if($toDo=='reject') {
$status="Rejected";
}

if($toDo=='revoke') {
$status="Revoked";
}

if($toDo=='keep') {
$status="Kept";
}



$activation = $subscriptionDate !='0000-00-00 00:00:00' ? $subscriptionDate : date("Y-m-d H:i:s", $currentTime);


if ($subscriptionType=='Shelf') {
    $expiry = date("Y-m-d H:i:s", strtotime($activation) + 365.25 * 86400 * $subscriptionDuration);
} else {
    $expiry = date("Y-m-d H:i:s", strtotime($activation) + 60 * 60 *24 *30*$subscriptionDuration); 
}




$sqlUpdateStatus =  "UPDATE registrant_subscriptions
                        SET registrant_subscriptionStatus = ?,
                        registrant_subscriptionDate=?,
                        registrant_subscriptionExpiry=?
                        WHERE registrant_subscriptionId =$subscriptionId";

    $stmt = mysqli_stmt_init($conn);
    $prepareStmt = mysqli_stmt_prepare($stmt, $sqlUpdateStatus);
    
    if ($prepareStmt) {
    mysqli_stmt_bind_param($stmt,"sss",$status,$activation,$expiry);
    mysqli_stmt_execute($stmt);

$responses ['status'] = 'Successful';

}


if ($responses) {
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        echo  $jsonResponses;
} 


}






//UPDATE THE REGISTRATION
if (isset($_POST['registration_update_submit'])) {

$registrationId= htmlspecialchars($_POST['registration_id']);
$regType =htmlspecialchars($_POST['regtype']);
$toDo = htmlspecialchars($_POST['to_do']);

$responses = [];

if ($toDo=="approve") {
    $profileStatus="Approved";
}

if ($toDo=="reject") {
    $profileStatus="Rejected";
}


if ($toDo=="revoke") {
    $profileStatus="Revoked";
}

if ($toDo=="keep") {
    $profileStatus="Kept";
}



if ($regType) {

    $approvalDate = date("Y-m-d H:i:s", $currentTime);

    $sqlUpdateStatus =  "UPDATE other_registrations
                            SET otherStatus = ?,
                            otherApprovalDate = ?
                            WHERE otherId = '$registrationId'";

    $stmt = mysqli_stmt_init($conn);
    $prepareStmt = mysqli_stmt_prepare($stmt, $sqlUpdateStatus);
    
    if ($prepareStmt) {
    mysqli_stmt_bind_param($stmt,"ss",$profileStatus,$approvalDate);
    mysqli_stmt_execute($stmt);

    
    //Update the general registrations
    $regTypeCap = ucfirst($regType);

    if ($profileStatus =="Approved" || $profileStatus =="Kept" ) {
        $regRoleStatus=$regTypeCap;
    } 

    if ($profileStatus !="Approved" && $profileStatus !="Kept" ) {
        $regRoleStatus='';
    }


    $regRoleAccountCol = 'registrant'.$regTypeCap.'Account';

    $sqlOtherRegistrant = "SELECT * FROM other_registrations WHERE otherId = $registrationId";
    $sqlOtherRegistrantResult = mysqli_query($conn,$sqlOtherRegistrant);
    $otherRegistrant = $sqlOtherRegistrantResult->fetch_assoc();

    if ($otherRegistrant) {
        $otherRegistrantMainId = $otherRegistrant['otherUserId'];
    }


    $sqlUpdateRegistrantRoleAccount = "UPDATE registrations SET $regRoleAccountCol = ?
                        WHERE registrantId = '$otherRegistrantMainId'";
    $stmt = mysqli_stmt_init($conn);
    $prepareStmt = mysqli_stmt_prepare($stmt, $sqlUpdateRegistrantRoleAccount);

    if ($prepareStmt) {
    mysqli_stmt_bind_param($stmt,"s",$regRoleStatus);
    mysqli_stmt_execute($stmt);  

    }

}
}

if (!$regType) {

    $sqlUpdateRegistrantStatus = "UPDATE registrations SET registrantStatus = ?
                            WHERE registrantId = '$registrationId'";
    $stmt = mysqli_stmt_init($conn);
    $prepareStmt = mysqli_stmt_prepare($stmt, $sqlUpdateRegistrantStatus);

    if ($prepareStmt) {
    mysqli_stmt_bind_param($stmt,"s",$profileStatus);
    mysqli_stmt_execute($stmt); 
    }


}

$responses ['status'] = 'Successful';

if ($responses) {
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        echo  $jsonResponses;
    } 


    
}


//submit note
if (isset($_POST['note_submit'])) {

    $notes= htmlspecialchars($_POST['note_notes']);
    $userIdHidden = htmlspecialchars($_POST['note_userid']);
    $regTypeCap = htmlspecialchars($_POST['note_regtype']);
    $regType = htmlspecialchars($_POST['note_regtype_cap']);

    $sqlUpdateNotes = "UPDATE other_registrations 
                            SET otherNotes =?
                            WHERE otherUserId = $userIdHidden AND otherType='$regTypeCap'";

                            $stmt = mysqli_stmt_init($conn);
    $prepareStmt = mysqli_stmt_prepare($stmt, $sqlUpdateNotes);
        
    if ($prepareStmt) {
        mysqli_stmt_bind_param($stmt,"s", $notes);
        mysqli_stmt_execute($stmt);

    echo 'Notes updated successfully!';
    }


}



//delete the website manager record
if (isset($_POST['delete_record_submit'])){
    $recordCategory = htmlspecialchars($_POST['record_category']);
    $recordId = htmlspecialchars($_POST['record_id']);


    $responses = [];
    $responses ['error']  = [];
   

    if ($recordCategory == 'Promotion'){

        $sqlPromotionInfo = "SELECT * FROM promotions WHERE promotionId = $recordId";
        $sqlPromotionInfoResult = mysqli_query($conn,$sqlPromotionInfo);
        $promotionInfo = $sqlPromotionInfoResult->fetch_assoc();

        if ($promotionInfo){
            $promotionImage = $promotionInfo['promotionImage'];
            $promotionAgreement = $promotionInfo['promotionAgreement'];

             if ($promotionImage) {
                  $promotionImageUnlinked= unlink($privateFolder.$promotionImage);  
            } else {
                $error = 'The image is not found.';
                array_push($responses ['error'],$error);
            }

            if ($promotionAgreement) {
                  $promotionAgreementUnlinked= unlink($privateFolder.$promotionAgreement);  
            } else {
                 $error = 'The agreement is not found.';
                array_push($responses ['error'],$error);
            }


        } else {
            $error = 'The promotion is not found.';
            array_push($responses ['error'],$error);
        }    
    } 

    if (!$responses['error']){
        if ($recordCategory == 'Promotion'){
            $sqlDeleteFromPromotions = mysqli_query($conn,"delete from promotions where promotionId = '$recordId' LIMIT 1");
            
        }

        $responses ['success-message']= 'Promotion has been deleted successfully.';
        $responses ['status'] = 'Successful';
    } else {
         $responses ['status'] = 'Unsuccessful';
    }


   if ($responses) {
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        echo  $jsonResponses;
    } 

}





if (isset($_POST['get_promotions_submit'])) {
    $contentType = htmlspecialchars($_POST['content_type']);
    $searchedPromotion = htmlspecialchars($_POST['searched_promotion']);

    $sqlPromotionsCreteria = "promotions WHERE promotionStatus = 'Published' ORDER BY promotionId DESC LIMIT 1";
    if ($searchedPromotion) {
         $sqlPromotionsCreteria = "promotions WHERE promotionStatus = 'Published' AND promotionCode="."'".$searchedPromotion."'"." ORDER BY promotionId DESC LIMIT 1";
    }
   
    // $sqlCount = 
    $sqlPromotionsRecords = "SELECT * FROM ".$sqlPromotionsCreteria;
    $sqlPromotionsResult = mysqli_query($conn, $sqlPromotionsRecords);


    if ($sqlPromotionsResult->num_rows>0){
        while($promotions = $sqlPromotionsResult->fetch_assoc()) {
            $promotionId = $promotions ['promotionId'];
            $promotionTitle = $promotions ['promotionTitle'];
            $promotionImage= $privateFolder.$promotions ['promotionImage'];
            $promotionLink = $promotions ['promotionLink'];



            echo "
            <a href='".$promotionLink."' id='".'promotion-'.$promotionId."'>
                <small>Advertisement</smal>
                <img src='$promotionImage' style='width:100%;'>
                <p><strong>$promotionTitle</strong></p>
            </a>
             ";
             if ($searchedPromotion) {
                echo "
                     <div>
                        <small>Clicks: 4</small>
                    </div>
                ";
             }     
           
        }
    } else {
        echo "
            <small>Not found!</small>
        ";
    }
}




if (isset($_POST['get_message_users_final_submit'])){
    $messageMode = htmlspecialchars($_POST['message_mode']);
    $messageSearchedUser = htmlspecialchars($_POST['message_searched_user']);

    $userProfilePictureLink = '';
    $userAccountname = '';
    $userId ='';
    $userCode = '';
    
   
  
    if (!$messageSearchedUser) {
    
        $sqlGetList = "SELECT * FROM message_threads WHERE message_threadType = '$messageMode' ORDER BY message_threadUpdateDate DESC";
    
     
    }
   

    if ($messageSearchedUser){
   
       $sqlGetList= "SELECT * FROM registrations WHERE registrantVerificationStatus= 'Verified' AND registrantAccountName LIKE '%$messageSearchedUser%' ORDER BY registrantAccountName";
       
    }

    $sqlGetListResult = mysqli_query($conn, $sqlGetList);
  
    

    if ($sqlGetListResult  ->num_rows>0){
        while ($list  = $sqlGetListResult->fetch_assoc()){

            if (!$messageSearchedUser) {
                $connected = false;

                $messageThreadCode = $list ['message_threadCode'];

                if ($messageMode=='Personal') {
                     if (str_contains($messageThreadCode,$registrantCode)) {
                    $connected = true;
                    }  
                }

                if ($messageMode=='Message Manager') {
                     if (str_contains($messageThreadCode,'TOADMIN')) {
                    $connected = true;
                    }  
                }
                

            }


            if ($messageSearchedUser){
                 $connected = false;
                $userAccountname = $list ['registrantAccountName'];
                $userProfilePictureLink = $list ['registrantProfilePictureLink'] ? $privateFolder.$list ['registrantProfilePictureLink'] : $publicFolder.'/assets/images/user.svg';

                $userId = $list ['registrantId'];
                $userCode = $list ['registrantCode'];
                
                if ($messageMode=='Personal'){
                    $thread1 = $registrantCode.$userCode;
                    $thread2 = $userCode.$registrantCode;
                }

                 if ($messageMode=='Message Manager'){
                    $thread1 = 'TOADMIN'.$userCode;
                }
                
                
                if ($messageMode=='Personal') {
                     $sqlGetThread = "SELECT * FROM message_threads WHERE message_threadCode = '$thread1' OR  message_threadCode = '$thread2' LIMIT 1";
                }

                 if ($messageMode=='Message Manager') {
                     $sqlGetThread = "SELECT * FROM message_threads WHERE message_threadCode = '$thread1' LIMIT 1";
                }
               


                $sqlGetThreadResult = mysqli_query($conn,$sqlGetThread);
                $thread = $sqlGetThreadResult->fetch_assoc();

                if ($thread) {
                    $connected =true;
                        $messageThreadCode = $thread ['message_threadCode'];
                }
                

            }



            if ($connected) {

                    if ($messageMode=='Personal') {
                            $messageThreadCodeOther = trim(str_replace($registrantCode,'',$messageThreadCode));
                            if ($messageThreadCode==$registrantCode.$registrantCode){
                                $messageThreadCodeOther = $registrantCode;
                            }
                    }

                     if ($messageMode=='Message Manager') {
                            $messageThreadCodeOther = trim(str_replace('TOADMIN','',$messageThreadCode));
                    }
                    
                  
                        $sqlmessageThreadCodeOtherInfo = "SELECT * FROM registrations WHERE registrantCode = '$messageThreadCodeOther' LIMIT 1";
                        $sqlmessageThreadCodeOtherInfoResult = mysqli_query($conn,$sqlmessageThreadCodeOtherInfo);
                        $messageThreadCodeOtherInfo = $sqlmessageThreadCodeOtherInfoResult->fetch_assoc();

                        if ($messageThreadCodeOtherInfo) {
                            $userAccountname = $messageThreadCodeOtherInfo ['registrantAccountName'];
                            $userId = $messageThreadCodeOtherInfo ['registrantId'];
                            $userCode = $messageThreadCodeOtherInfo ['registrantCode'];
                            $userProfilePictureLink = $messageThreadCodeOtherInfo ['registrantProfilePictureLink'] ? $privateFolder.$messageThreadCodeOtherInfo ['registrantProfilePictureLink'] : $publicFolder.'/assets/images/user.svg';
                        }
                    

              
                    $sqlGetLatestThreadMessage = "SELECT * FROM thread_messages WHERE thread_messageThreadCode = '$messageThreadCode' ORDER BY thread_messageTimestamp DESC LIMIT 1";
                    $sqlGetLatestThreadMessageResult = mysqli_query($conn,$sqlGetLatestThreadMessage);
                    $latestThreadMessage = $sqlGetLatestThreadMessageResult->fetch_assoc();

                    if ($latestThreadMessage) {
                        $latestThreadMessageTimestamp = dcomplete_format($latestThreadMessage ['thread_messageTimestamp']); 
                        $latestThreadMessageContent = nl2br($latestThreadMessage ['thread_messageContent']);
                        
                        if (str_word_count($latestThreadMessageContent) > 10) {
                            $latestThreadMessageContent =  limit_words($latestThreadMessageContent,10).'...';
                        }
                        $latestThreadMessageRegistrantId = $latestThreadMessage ['thread_messageRegistrantId'];
                        $latestThreadMessageStatusRecipient = $latestThreadMessage ['thread_messageStatusRecipient'];
                       
                    } else {
                         $latestThreadMessageRegistrantId = '';
                        $latestThreadMessageStatusRecipient = '';
                        $latestThreadMessageTimestamp ='';
                        $latestThreadMessageContent='';
                    }

                    $sqlUnreadMessages = "SELECT * FROM thread_messages WHERE thread_messageThreadCode = '$messageThreadCode' AND thread_messageStatusRecipient = 'Unread' AND thread_messageRegistrantId != $registrantId";
                    $sqlUnreadMessagesResult = mysqli_query($conn,$sqlUnreadMessages);
                    $unreadMessages = $sqlUnreadMessagesResult->num_rows;


                    
                }

           

                if ($connected || $messageSearchedUser){
                    
                    echo "
                    <div style='display:flex; gap:5px;'>
                        <div>
                            <img src='$userProfilePictureLink' style='width:100px;'>
                        </div>
                        <div style='display:flex;flex-direction:column;'>
                            <strong >$userAccountname
                            ";

                            if ($connected){
                                if ($unreadMessages){
                                    echo "
                                        ($unreadMessages)
                                    ";
                                }
                            }

                        echo " 
                        </strong>
                            <input id='".'code-for-message-user-'.$userId."' value='$userCode'hidden>
                            
                        ";
                            if ($connected) {
                                echo "
                                <div style='display:flex;flex-direction:column;'>
                                    <small style='margin-top:0px;'>$latestThreadMessageTimestamp</small>
                                    ";


                                    if ($latestThreadMessageRegistrantId!= $registrantId) {
                                             if ($latestThreadMessageStatusRecipient=='Unread') {
                                                echo"
                                                <strong style='margin-top:0px;font-size:9pt;'>Recipient: $latestThreadMessageContent</strong>
                                                ";
                                            }

                                            if ($latestThreadMessageStatusRecipient=='Read') {
                                                echo"
                                                <small style='margin-top:0px;'>Recipient: $latestThreadMessageContent</small>
                                                ";
                                            }
                                    }


                                     if ($latestThreadMessageRegistrantId == $registrantId) { 
                                                if ($latestThreadMessageStatusRecipient=='Unread') {
                                                    echo"
                                                    <small style='margin-top:0px;'>You: $latestThreadMessageContent (Sent)</small>
                                                    ";
                                                }  
                                                
                                                 if ($latestThreadMessageStatusRecipient=='Read') {
                                                    echo"
                                                    <small style='margin-top:0px;'>You: $latestThreadMessageContent (Read)</small>
                                                    ";
                                                }    
                                    }
                                   
                                   
                                echo "
                                </div>
                                ";
                            }

                        echo"
                            <div id='message-user-action' style='margin-top:3px;margin-left:-3px;'>
                            <span class='link-tag-button' id='".'to-message-'.$userId."' style='font-size:9pt;'>Message</span>
                        ";

                        
                            
                    echo"
                            </div>
                        </div>
                    </div>
                    <hr>
                
                    ";
                
            }
           
        }
    } else {
            echo "
                <small>No result.</small>
            ";
    }
}





if (isset($_POST['get_updates_submit'])){
    $searchedUpdate = htmlspecialchars($_POST['searched_update']);

    $sqlUpdates = "SELECT * FROM updates WHERE updateStatus='Published' ORDER BY updatePubDate DESC";

    if ($registrantId){
        if ($websiteManagerMessageManagerRegistration || $websiteManagerSuperManagerRegistration) {
        $sqlUpdates = "SELECT * FROM updates ORDER BY updateId DESC";
        }   
    }
    
    
    if ($searchedUpdate){
        $sqlUpdates = "SELECT * FROM updates WHERE updateTitle LIKE '%$searchedUpdate%' AND updateStatus='Published' ORDER BY updatePubDate DESC";

        if ($registrantId){
             if ($websiteManagerMessageManagerRegistration || $websiteManagerSuperManagerRegistration) {
            $sqlUpdates = "SELECT * FROM updates WHERE updateTitle  LIKE '%$searchedUpdate%' ORDER BY updateId DESC";
            }
        }
       
    }


    $sqlUpdatesResult = mysqli_query($conn,$sqlUpdates);


    if ($sqlUpdatesResult->num_rows>0) {
        while($updates = $sqlUpdatesResult->fetch_assoc()) {
            $updateId = $updates ['updateId'];
            $updateRegistrantId = $updates ['updateRegistrantId'];
            $updateTitle = $updates ['updateTitle'];
            $updatePubDate = dcomplete_format($updates ['updatePubDate']);
            $updateContent =$updates ['updateContent'];

            if (str_word_count($updateContent)>30) {
                $updateContent = limit_words($updateContent,50).'...';
            }
            $updateSlug = $updates ['updateSlug'];
            $updateStatus = $updates ['updateStatus'];
            $updateViewers = $updates ['updateViewers'];

            echo "
            <div style='display:flex;flex-direction:column;'>
                <strong>$updateTitle</strong>
               
                ";
            if (str_contains($updateViewers,$registrantCode) || $updateRegistrantId==$registrantId){
                echo "
                 <small>$updateContent 
                        <small id='".'reread-update-'.$updateId."' class='read-update'>Read>>></small>
                </small>";
            }

             if (!str_contains($updateViewers,$registrantCode) && $updateRegistrantId !=$registrantId){
                echo "
                 <strong style='font-size:10pt;'>$updateContent
                        <strong style='font-size:10pt;' id='".'read-update-'.$updateId."' class='read-update'>Read>>></strong>
                </strong>";
            }
               


            echo"
                <div style='display:flex; gap:5px;'>
                ";

                if ($updateStatus != 'Draft') {
                echo "
                <small>$updatePubDate</small>
                ";
                }

                if ($websiteManagerMessageManagerRegistration || $websiteManagerSuperManagerRegistration) {
                echo "
                <small>|</small>
                <small>$updateStatus</small>
                ";
                }
          
                echo "
                </div>
                ";
           
                if ($websiteManagerMessageManagerRegistration || $websiteManagerSuperManagerRegistration) {
                echo "
                
                <div id='update-actions' style='margin-top:5px;margin-left:-5px;'>
                    <span id='".'edit-update-'.$updateId."' class='link-tag-button'>Edit</span>

                </div>
                    ";
                }
                
            echo"
            </div>
            <hr>
        ";

        }

    } else {
        echo "
            <div>
                <small>No update</small>
            </div>
        ";
    }

}


if (isset($_POST ['submit_update_submit'])){
    $updateMode= htmlspecialchars($_POST['update_mode']);
    $updateId = htmlspecialchars($_POST['update_id']);
    $updateType = htmlspecialchars($_POST['update_type']);
    $updateTitle = htmlspecialchars($_POST['update_title']);
    $updateContent = htmlspecialchars($_POST['update_content']);

    $responses = [];
    $responses ['error'] = [];

     if (!$updateType){
        $error= 'Type is required.';
        array_push($responses ['error'], $error);
    }

    if (!$updateTitle){
        $error= 'Name is required.';
        array_push($responses ['error'], $error);
    } else {

            $sqlCheckUpdate = "SELECT * FROM updates WHERE updateTitle='$updateTitle'";
            $sqlCheckUpdateResult = mysqli_query($conn,$sqlCheckUpdate);
            $checkedUpdate = $sqlCheckUpdateResult->fetch_assoc();
            if ($checkedUpdate) {
                

                if ($updateMode == 'new') {
                      $error = 'An update with the same title already exists.';
                    array_push($responses ['error'], $error);
                }

                if ($updateMode == 'edit') {
                    $checkedUpdateId = $checkedUpdate ['updateId'];
                    if ($checkedUpdateId != $updateId) {
                        $error = 'You are purposely editing the other update.';
                        array_push($responses ['error'], $error);
                    }
                    
                }
              
            }    
        

    }

    if (!$updateContent){
        $error= 'Content is required.';
        array_push($responses ['error'], $error);
    }





    if (!$responses ['error']){
        $updateSlug = generateSlug($updateTitle);

        if ($updateMode == 'new'){
            //insert
            $sqlInsertToUpdates = "INSERT INTO updates (updateType, updateTitle, updateSlug, updateContent, updateRegistrantId)  VALUES (?,?,?,?,?)";
            $stmt =$conn->prepare($sqlInsertToUpdates);
            $stmt ->bind_param("sssss", $updateType,$updateTitle,$updateSlug,$updateContent,$registrantId);
            $stmt-> execute();

            $updateId = mysqli_insert_id($conn);
        }


        if ($updateMode == 'edit'){
            //update
            $sqlUpdateUpdate = "UPDATE updates 
                                SET updateType = ?, 
                                updateTitle =?, 
                                updateSlug = ?, 
                                updateContent =?
                                 WHERE updateId= '$updateId'";
            $stmt =$conn->prepare($sqlUpdateUpdate );
            $stmt ->bind_param("ssss", $updateType,$updateTitle,$updateSlug,$updateContent);
            $stmt-> execute();
        }


        $successMessage = '';

        if ($updateMode == 'new'){
            $successMessage = $updateTitle.' has been added successfully!';
        }

        if ($updateMode == 'edit'){
            $successMessage = $updateTitle.' has been updated successfully!';
        }


        $responses ['status'] ='Successful';
        $responses ['update-id'] = $updateId;
        $responses ['success-message'] = $successMessage;
    }else {
        $responses ['status'] ='Unsuccessful';
    }
    


    if ($responses) {
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        echo  $jsonResponses;
    } 


}


if (isset($_POST['get_update_details_submit'])) {
    $updateId = htmlspecialchars($_POST['update_id']);

    $sqlUpdateDetails = "SELECT * FROM updates WHERE updateId = $updateId";
    $sqlUpdateDetailsResult= mysqli_query($conn,$sqlUpdateDetails);
    $updateDetails = $sqlUpdateDetailsResult->fetch_assoc();

    $responses = [];

    if ($updateDetails) {
        $responses['update-type'] = $updateDetails ['updateType'];
        $responses['update-title'] = $updateDetails ['updateTitle'];
        $responses['update-content'] = $updateDetails ['updateContent'];
        $responses['update-status'] = $updateDetails ['updateStatus'];

    }

     if ($responses) {
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        echo  $jsonResponses;
    } 

}




if (isset($_POST['update_update_status_submit'])) {
    $action = htmlspecialchars($_POST['action']);
    $updateId = htmlspecialchars($_POST['update_id']);

    if ($action=='publish') {
        $updateStatus = 'Published';
    }

    if ($action=='unpublish') {
        $updateStatus = 'Unpublished';
    }


    $getUpdateDetails = "SELECT * FROM updates WHERE updateId =$updateId LIMIT 1";
    $getUpdateDetailsResult = mysqli_query($conn,$getUpdateDetails);
    $updateDetails = $getUpdateDetailsResult->fetch_assoc();

    if ($updateDetails) {
        $updatePubDate = $updateDetails ['updatePubDate'];
    }

     $pubDate = $updatePubDate !='0000-00-00 00:00:00' ? $updatePubDate : date("Y-m-d H:i:s", $currentTime);


     $sqlUpdateUpdate = "UPDATE updates 
                                SET updateStatus = ?, 
                                updatePubDate =?
                                 WHERE updateId= '$updateId'";
    $stmt =$conn->prepare($sqlUpdateUpdate );
    $stmt ->bind_param("ss", $updateStatus,$pubDate);
    $stmt-> execute();
     
    
}



if (isset($_POST['delete_update_submit'])) {
    $updateId = htmlspecialchars($_POST['update_id']);

    //delete the category
    $sqlDeleteFromUpdates = mysqli_query($conn,"delete from updates where updateId = '$updateId' LIMIT 1");
    echo "Deleted Successfully";
}













