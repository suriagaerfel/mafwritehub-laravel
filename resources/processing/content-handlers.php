

<?php 

$contentTitle = '';


if(str_contains($currentURL,'/teacher-files/')) {
    $contentType = 'Teacher File';
    $idColumn = 'teacher_fileId';
    $table = 'teacher_files';
    $slugColumn = 'teacher_fileSlug';
    $titleColumn = 'teacher_fileTitle';
    $categoryColumn = 'teacher_fileCategory';
    $tagsColumn = 'teacher_fileTags';
    $accessTypeColumn = 'teacher_fileAccessType';
    $sharedWithColumn = 'teacher_fileSharedWith';
    $descriptionColumn = 'teacher_fileDescription';
    $pubDateColumn = 'teacher_filePubDate';
    $updateDateColumn = 'teacher_fileUpdateDate';
    $registrantIdColumn = 'teacher_fileTeacher';
    $contentColumn = 'teacher_fileContent';
    $statusColumn = 'teacher_fileStatus';
    $versionColumn ='teacher_fileContentVersion';

    $imageColumn = 'teacher_fileImage';
    $defaultImage = asset('images/default-file-image.jpg');

    $formatColumn = 'teacher_fileFormat';


    $pageLink = $publicFolder.'/teacher-files/';
    $ownerLabel = 'Teacher';

    $access = 'view';

    $content_type = 'teacher-file';



}

if(str_contains($currentURL,'/articles/')) {
     $contentType = 'Article';
    $idColumn = 'writer_articleId';
    $table = 'writer_articles';
    $slugColumn = 'writer_articleSlug';
    $titleColumn = 'writer_articleTitle';
    $categoryColumn = 'writer_articleCategory';
    $tagsColumn = 'writer_articleTags';
    $accessTypeColumn = 'writer_articleAccessType';
    $sharedWithColumn = 'writer_articleSharedWith';
    $descriptionColumn = 'writer_articleDescription';
    $pubDateColumn = 'writer_articlePubDate';
    $updateDateColumn = 'writer_articleUpdateDate';
    $registrantIdColumn = 'writer_articleWriter';
    $contentColumn = 'writer_articleContent';
    $statusColumn = 'writer_articleStatus';
        $versionColumn ='writer_articleContentVersion';

        $imageColumn = 'writer_articleImage';
        $defaultImage = asset('images/default-article-image.jpg');

        $pageLink = $publicFolder.'/articles/';

        $ownerLabel = 'Writer';

        $access = 'read';

    $content_type = 'article';



}

if(str_contains($currentURL,'/researches/')) {
     $contentType = 'Research';
    $idColumn = 'school_researchId';
    $table = 'school_researches';
    $slugColumn = 'school_researchSlug';
    $titleColumn = 'school_researchTitle';
    $categoryColumn = 'school_researchCategory';
    $tagsColumn = 'school_researchTags';
        $accessTypeColumn = 'school_researchAccessType';
        $sharedWithColumn = 'school_researchSharedWith';
    $descriptionColumn = 'school_researchAbstract';
    $pubDateColumn = 'school_researchLiveDate';
    $updateDateColumn = 'school_researchUpdateDate';
    $registrantIdColumn = 'school_researchSchool';
    $contentColumn = 'school_researchContent';
    $statusColumn = 'school_researchStatus';
    $versionColumn ='school_researchContentVersion';

    $formatColumn = 'school_researchFormat';

    $imageColumn = 'school_researchImage';
    $defaultImage = asset('/images/default-research-image.jpg');

    $pageLink = $publicFolder.'/researches/';

    $ownerLabel = 'School';

    $access = 'view';

    $content_type = 'research';

}

// $slug = isset($_GET['slug']) ? $_GET['slug'] : '';

 if ($slug) {
         
        $sqlContentInfo = "SELECT * FROM $table WHERE $slugColumn = '$slug'";
        $sqlContentInfoResult = mysqli_query($conn,$sqlContentInfo);
        $contentInfo = $sqlContentInfoResult->fetch_assoc();

        if ($contentInfo){
            $id = $contentInfo [$idColumn];
            $contentTitle = $contentInfo [$titleColumn];
            $contentCategory = $contentInfo [$categoryColumn];
            $contentTags = $contentInfo [$tagsColumn];
            $contentAccessType = $contentInfo [$accessTypeColumn];
            $contentSharedWith = $contentInfo [$sharedWithColumn];
            $contentDescription = $contentInfo [$descriptionColumn];
            $contentOriginalPubDate =$contentInfo [$pubDateColumn];
            $contentPubDate = dcomplete_format($contentOriginalPubDate);
            $contentOriginalUpdateDate = $contentInfo [$updateDateColumn];
            $contentUpdateDate = dcomplete_format($contentOriginalUpdateDate);

            $contentContent = $contentInfo [$contentColumn];
            $contentRegistrantId = $contentInfo [$registrantIdColumn];
            $contentStatus = $contentInfo [$statusColumn];
            $contentVersion = $contentInfo [$versionColumn];

            $contentImage = $contentInfo [$imageColumn] ? $privateFolder.$contentInfo [$imageColumn] : $defaultImage;
            $isContentImageUpload = $contentInfo [$imageColumn] ? true :false;



            if ($table == 'teacher_files' || $table == 'school_researches') {
                $contentFormat = $contentInfo [$formatColumn];
            }

            $pageName = $contentTitle;

            if ($contentCategory == 'Administrative'){
                header('Location:'.$publicFolder.'/'.$slug);
            }

            $hasAccess = false;


            if ($contentAccessType == 'Free Access') {
                $hasAccess = true;
            }

            if ($contentAccessType != 'Free Access') {

                if ($contentAccessType == 'Purchased') {

                    
                    $sqlContentPurchase = "SELECT * FROM content_purchase WHERE content_purchaseRegistrantId = '$registrantId' AND content_purchaseContentId = '$id'";
                    $sqlContentPurchaseResult = mysqli_query($conn,$sqlContentPurchase);
                    $contentPurchase = $sqlContentPurchaseResult->fetch_assoc();

                    if ($contentPurchase){
                        $contentPurchaseStatus =  $contentPurchase['content_purchaseStatus'];
                        if ($contentPurchaseStatus == 'Approved' || $contentPurchaseStatus == 'Kept'){
                            $hasAccess = true;
                        } else {
                            $hasAccess = false;
                        }
                        
                        $contentPurchaseAmount = $contentPurchase ['content_purchaseAmount'];
                    }

                    if ($contentRegistrantId == $registrantId){
                        $hasAccess = true;
                    }

                   
                }


                if ($contentAccessType == 'Subscription') {


                    
                }

               
               
            }


            if ($contentStatus  != 'Published'){
                if ($contentRegistrantId==$registrantId){
                $hasAccess = true;
                }

                if ($contentRegistrantId !=$registrantId){
                $hasAccess = false;
                }
            }


            if ($loggedIn){
                 if (str_contains($contentSharedWith,$registrantCode)){
                $hasAccess = true;
                }
            }
           

         
           



            


            $sqlRegistration = "SELECT * FROM registrations WHERE registrantId = $contentRegistrantId";
            $sqlRegistrationResult = mysqli_query($conn,$sqlRegistration);
            $registration = $sqlRegistrationResult->fetch_assoc();

            if($registration){
                $contentRegistrant = $registration ['registrantAccountName'];
                $contentRegistrantUsername = $registration ['registrantUsername'];
            }



            $convertedPubDate = strtotime($contentOriginalPubDate);

            $sqlPreviousContent = "SELECT * FROM $table WHERE $statusColumn = 'Published' AND $categoryColumn ='$contentCategory' AND $pubDateColumn < $convertedPubDate  ORDER BY $pubDateColumn DESC LIMIT 1";
            $sqlPreviousContentResult = mysqli_query($conn,$sqlPreviousContent);
            $previousContent = $sqlPreviousContentResult->fetch_assoc();

            if ($previousContent){
                $previousContentTitle = $previousContent [$titleColumn];
                $previousContentSlug = $previousContent [$slugColumn];
                $previousContentHref = $pageLink.$access.'/'.$previousContentSlug;
            }



            $sqlNextContent = "SELECT * FROM $table WHERE $statusColumn = 'Published' AND $categoryColumn ='$contentCategory'  AND $pubDateColumn < $convertedPubDate ORDER BY $pubDateColumn ASC LIMIT 1";
            $sqlNextContentResult = mysqli_query($conn,$sqlNextContent);
            $nextContent = $sqlNextContentResult->fetch_assoc();

            if ($nextContent){
                $nextContentTitle = $nextContent [$titleColumn];
                $nextContentSlug = $nextContent [$slugColumn];
                $nextContentHref = $pageLink.$access.'/'.$nextContentSlug;
            }







       
        }
  
    } else {

        $accessedCategory = isset($_GET['category']) ? htmlspecialchars($_GET['category']) : '';
        $accessedTag = isset($_GET['tag']) ? htmlspecialchars($_GET['tag']) : '';
        $accessedDate = isset($_GET['date']) ? htmlspecialchars($_GET['date']) : '';
        $accessedOwner = isset($_GET['owner']) ? htmlspecialchars($_GET['owner']) : '';

        if ($accessedOwner) {
            $sqlRegistration = "SELECT * FROM registrations WHERE registrantUsername = '$accessedOwner'";
            $sqlRegistrationResult = mysqli_query($conn,$sqlRegistration);
            $registration = $sqlRegistrationResult->fetch_assoc();

            if($registration){
                $accessedOwner = $registration ['registrantId'];
            }
        }


        if (str_contains($currentURL,'/tag/')){
            if(!$accessedTag) {
                header('Location:'.$pageLink);
            }
        }



    }








?>



