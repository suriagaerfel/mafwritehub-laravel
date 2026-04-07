<?php require resource_path('processing/initialize.php'); ?>
<?php require resource_path('processing/registrant-records.php'); ?>
<?php require resource_path('processing/u_registrant-records.php'); ?>


<?php

if (isset($_POST['get_content_info_submit'])) {
$contentId = htmlspecialchars($_POST['content_id']);
$queryIn = htmlspecialchars($_POST['query_in']);

if ($queryIn == 'teacher_files') {
    $idColumn = 'teacher_fileId';
    $titleColumn = 'teacher_fileTitle';
    $descriptionColumn = 'teacher_fileDescription';
    $categoryColumn = 'teacher_fileCategory';
    $versionColumn = 'teacher_fileContentVersion';
    $accessTypeColumn = 'teacher_fileAccessType';
    $statusColumn = 'teacher_fileStatus';
    $slugColumn = 'teacher_fileSlug';
    $imageColumn = 'teacher_fileImage';
    $defaultImage=$website.'/assets/images/default-file-image.jpg';
    $tagsColumn = 'teacher_fileTags';
    $contentColumn= 'teacher_fileContent';
    $amountColumn = 'teacher_fileAmount';
    $sharedWithColumn = 'teacher_fileSharedWith';
     $pageLink=$website.'/teacher-files/';
     $pubDateColumn = 'teacher_filePubDate';
     $updateDateColumn = 'teacher_fileUpdateDate';

     $access = 'view';
   
}

if ($queryIn == 'writer_articles') {
    $idColumn = 'writer_articleId';
    $titleColumn = 'writer_articleTitle';
    $descriptionColumn = 'writer_articleContent';
    $categoryColumn = 'writer_articleCategory';
    $versionColumn = 'writer_articleContentVersion';
    $accessTypeColumn = 'writer_articleAccessType';
    $statusColumn = 'writer_articleStatus';
    $slugColumn = 'writer_articleSlug';
    $imageColumn = 'writer_articleImage';
    $defaultImage=$website.'/assets/images/default-featured-image.jpg';
    $tagsColumn = 'writer_articleTags';
    $contentColumn= 'writer_articleContent';
    $sharedWithColumn = 'writer_articleSharedWith';
     $pageLink=$website.'/articles/';

     $pubDateColumn = 'writer_articlePubDate';
     $updateDateColumn = 'writer_articleUpdateDate';

     $access = 'read';
}

if ($queryIn == 'school_researches') {
    $idColumn = 'school_researchId';
    $titleColumn = 'school_researchTitle';
    $descriptionColumn = 'school_researchAbstract';
    $categoryColumn = 'school_researchCategory';
    $versionColumn = 'school_researchContentVersion';
    $accessTypeColumn = 'school_researchAccessType';
    $statusColumn = 'school_researchStatus';
    $slugColumn = 'school_researchSlug';
    $imageColumn = 'school_researchImage';
    $defaultImage=$website.'/assets/images/default-research-image.jpg';
    $tagsColumn = 'school_researchTags';
    $contentColumn= 'school_researchContent';

    $sharedWithColumn = 'school_researchSharedWith';
    $pageLink=$website.'/researches/';

    $pubDateColumn = 'school_researchLiveDate';
     $updateDateColumn = 'school_researchUpdateDate';

    $access = 'view';
}

if ($queryIn == 'developer_tools') {
    $idColumn = 'developer_toolId';
    $titleColumn = 'developer_toolTitle';
    $descriptionColumn = 'developer_toolDescription';
    $categoryColumn = 'developer_toolCategory';
    $versionColumn = 'developer_toolContentVersion';
    $accessTypeColumn = 'developer_toolAccessType';
    $statusColumn = 'developer_toolStatus';
    $slugColumn = 'developer_toolSlug';
    $imageColumn = 'developer_toolImage';
    $defaultImage=$website.'/assets/images/default-tool-image.jpg';

    $tagsColumn = 'developer_toolTags';
    $contentColumn = $slugColumn;

    $sharedWithColumn = 'developer_toolSharedWith';
    $pageLink=$website.'/tools/';

    $pubDateColumn = 'developer_toolPubDate';
     $updateDateColumn = 'developer_toolUpdateDate';
}



$sqlContentInfo = "SELECT * FROM $queryIn WHERE $idColumn = '$contentId'";
$sqlContentInfoResult = mysqli_query($conn,$sqlContentInfo);
$contentInfo = $sqlContentInfoResult->fetch_assoc();


if ($contentInfo) {
    $contentTitle = $contentInfo [$titleColumn];
    $contentCategory = $contentInfo [$categoryColumn];
    $contentDescription = $contentInfo [$descriptionColumn];
    $contentVersion = $contentInfo [$versionColumn];
    $contentAccessType = $contentInfo [$accessTypeColumn];
    $contentStatus = $contentInfo [$statusColumn];
    $contentPubDate = dcomplete_format($contentInfo [$pubDateColumn]);
    $contentUpdateDate = dcomplete_format($contentInfo [$updateDateColumn]);
     
   


    if ($queryIn != 'developer_tools'){
        $contentSlug = $pageLink.$access.'/'.$contentInfo [$slugColumn];
    }

    

    $contentImage = $contentInfo [$imageColumn] ? $privateFolder.$contentInfo [$imageColumn] : $defaultImage;
    $contentImageStatus = $contentInfo [$imageColumn] ? 1 : 0;

    $contentTags = $contentInfo [$tagsColumn];
    $contentContent = $contentInfo [$contentColumn];

    $contentSharedWith = $contentInfo [$sharedWithColumn];

    $contentVersions = [];

    
    if ($queryIn == 'teacher_files') {
        $contentAmount = $contentInfo [$amountColumn];
        $contentForSale = $contentInfo ['teacher_fileForSale'];
    }

    if ($queryIn == 'writer_articles') {
        $contentEditors = $contentInfo ['writer_articleEditors'];
    }

    if ($queryIn == 'school_researches') {
        $contentProponents = $contentInfo ['school_researchProponents'];
        $contentDate = $contentInfo ['school_researchDate'];
    }

    if ($queryIn == 'developer_tools') {
        $contentLink = $contentInfo[$slugColumn];
        $contentSlug = $domain.$contentInfo[$slugColumn];
    }


}




if ($queryIn == 'writer_articles'){
    
    $sqlContentVersions = "SELECT * FROM content_versions WHERE content_versionTable = '$queryIn' AND content_versionContentId= $contentId ORDER BY content_versionNumber DESC LIMIT 7 ";
    $sqlContentVersionsResult = mysqli_query($conn,$sqlContentVersions);

    if ( $sqlContentVersionsResult->num_rows>0) {
        $contentVersions = [];
        
        while ($version = $sqlContentVersionsResult->fetch_assoc()) {
        $contentVersionNumber = $version['content_versionNumber'];    
        array_push ($contentVersions,$contentVersionNumber);
        }
    }

        
    
}


$responses = [];

$responses ['title'] = $contentTitle;
$responses ['category'] = $contentCategory;
$responses ['tags'] = $contentTags;
$responses ['description'] = $contentDescription;
$responses ['version'] = $contentVersion;
$responses ['access-type'] = $contentAccessType;
$responses ['status'] = $contentStatus;
$responses ['slug'] = $contentSlug;
$responses ['image'] = $contentImage;
$responses ['image-status'] = $contentImageStatus;
$responses ['content'] = $contentContent;
$responses ['shared-with'] = $contentSharedWith;


if ($queryIn == 'teacher_files') {
    $responses ['amount'] = $contentAmount;
    $responses ['for-sale'] = $contentForSale;
}

if ($queryIn == 'writer_articles') {
    $responses ['editors'] = $contentEditors;
}

if ($queryIn == 'school_researches') {
    $responses ['proponents'] = $contentProponents;
    $responses ['date'] = $contentDate;
}

if ($queryIn == 'developer_tools') {
    $responses ['link'] = $contentLink;
}

$responses ['publish-date'] = $contentPubDate;
$responses ['update-date'] = $contentUpdateDate;


$responses ['versions'] = $contentVersions;

 


 if ($responses) {
      header('Content-Type: application/json');
      $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
      echo  $jsonResponses;
   } 



}




if (isset($_POST['get_tool_files_submit'])) {
    $toolId= htmlspecialchars($_POST['tool_id']);
    $status = htmlspecialchars($_POST['status']);

    $sqlToolFiles = "SELECT * FROM developer_tool_files WHERE developer_tool_fileToolId = '$toolId'";
    $sqlToolFilesResult = mysqli_query($conn,$sqlToolFiles);

    echo "
            <input type='text' id='tool-files' value=$sqlToolFilesResult->num_rows hidden>
        ";

    if ( $sqlToolFilesResult->num_rows > 0) {
        while($toolFiles = $sqlToolFilesResult->fetch_assoc()){
            $toolFileId = $toolFiles ['developer_tool_fileId'];
            $toolFileTimestamp = dcomplete_format($toolFiles ['developer_tool_fileTimestamp']);
            $toolFileLink = basename($toolFiles['developer_tool_fileLink']);

            echo "
            
            <tr>
                <td><small>$toolFileTimestamp</small></td>
                <td><small>$toolFileLink</small></td>
                <td id='tool-file-actions'>
            ";

                if ($status != 'Published') { 
                echo "     
                    <a class='link-tag-button' id='".'edit-tool-file-'.$toolFileId."'>Edit</a>
                    <a class='link-tag-button' id='".'delete-tool-file-'.$toolFileId."'>Delete</a>
                ";
                }

                if ($status == 'Published') {
                    echo "
                    <small>Please unpublish to enable buttons.</small>
                    ";
                }
                   
            
            echo "
                </td>

            </tr>
          
            ";
        }
       
        
    } else {
        echo "<small>No file was uploaded. You can't publish this tool.</small>
        ";
        
    }

    
    

}



if (isset($_POST['get_used_tags_submit'])){
    $selectedTags = htmlspecialchars($_POST['selected_tags']);

    if ($selectedTags){
        $selectedTags = explode(', ',$selectedTags);

        foreach ($selectedTags as $usedTag){
            $sqlUsedTagInfo = "SELECT * FROM content_tags WHERE content_tagName = '$usedTag'";
            $sqlUsedTagInfoResult = mysqli_query($conn,$sqlUsedTagInfo);
            $usedTagInfo = $sqlUsedTagInfoResult->fetch_assoc();

            if ($usedTagInfo) {
                $usedTagCreator =$usedTagInfo['content_tagCreator'];
                $usedTagClass = '';
                if ($usedTagCreator == $registrantId){
                    $usedTagClass = 'link-tag-button active';
                }

                if ($usedTagCreator != $registrantId){
                    $usedTagClass = 'link-tag-button';
                }

                echo "<span class='".$usedTagClass."'>$usedTag</span>";
            } 

            
        }
  
    }






}

if (isset($_POST['get_tags_submit'])){
    $selectedTags = htmlspecialchars($_POST['selected_tags']);
    $searchedTag = htmlspecialchars($_POST['searched_tag']);
    $responses = [];

    $sqlTags = "SELECT * FROM content_tags";
    $sqlTagsResult = mysqli_query($conn,$sqlTags);

    if ($searchedTag){
        $sqlTags = "SELECT * FROM content_tags WHERE content_tagName LIKE '%$searchedTag%'";
        $sqlTagsResult = mysqli_query($conn,$sqlTags);
    }




    if ($sqlTagsResult->num_rows > 0){ 
        while($tags = $sqlTagsResult->fetch_assoc()){
            $tagId = $tags ['content_tagId'];
            $tagName = $tags ['content_tagName'];
            $tagCreator = $tags ['content_tagCreator'];
            $used = false;
            if ($selectedTags){
                if (str_contains($selectedTags,$tagName)) {
                    $used= true;
                } else {
                     $used= false;
                }
            }
           
            if (!$used){
                echo "<span class='link-tag-button' id='".$tagName."'>$tagName</span>";
                if ($tagCreator==$registrantId){
                    echo "<img src='$publicFolder/assets/images/delete.png' style='width:15px;margin-top:-15px;cursor:pointer;'title='".'You created this tag. You can delete it.'."' id='".'delete-tag-'.$tagId."'>";
                }
            }
            
        }
    }
}


if (isset($_POST['new_tag_submit'])){
    $newTag = htmlspecialchars($_POST['new_tag']);

    $responses = [];
    $responses ['error']= [];

    if ($newTag) {
        $sqlCheckTag = "SELECT * FROM content_tags WHERE content_tagName = '$newTag'";
        $sqlCheckTagResult = mysqli_query($conn,$sqlCheckTag);
        $checkedTag = $sqlCheckTagResult->fetch_assoc();

        if ($checkedTag) {
            $error= $newTag.' has already been added to the tags list.';
            array_push ($responses['error'],$error);
        } 
    } else{
        $error= 'What is your new tag?';
        array_push ($responses['error'],$error);
    }

   
    
    if (!$responses['error']) {
        $sqlInsertTag = "INSERT INTO content_tags (content_tagName,content_tagCreator) VALUES (?,?)";

        $stmt=$conn->prepare($sqlInsertTag);
        $stmt ->bind_param("ss",$newTag,$registrantId);
        $stmt-> execute(); 

        $responses['status'] = 'Successful';
        $responses ['success-message'] = $newTag.' has been added to the tags list.';
    } else {
        $responses['status'] = 'Unsuccessful';
    }
    
  
    
    if ($responses) {
      header('Content-Type: application/json');
      $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
      echo  $jsonResponses;
   }

}




if (isset($_POST['delete_tag_submit'])){
   
    $tagId = htmlspecialchars($_POST['tag_id']);

    $sqlTagInfo = "SELECT * FROM content_tags WHERE content_tagId=$tagId";
    $sqlTagInfoResult = mysqli_query($conn,$sqlTagInfo);
    $tagInfo = $sqlTagInfoResult->fetch_assoc();

    if($tagInfo){
        $tagName = $tagInfo['content_tagName'];
    }
   
    
    $responses = [];

    //delete the tag
    $sqlDeleteFromTags = mysqli_query($conn,"delete from content_tags where content_tagId = '$tagId' LIMIT 1");



    $responses ['status'] = 'Successful';
    $responses ['success-message'] = $tagName.' has been removed from tags list successfully.';

    if ($responses) {
            header('Content-Type: application/json');
            $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
            echo  $jsonResponses;
    } 


}


if (isset($_POST['get_categories_submit'])) {

   $mode = htmlspecialchars ($_POST['mode']);
   $originalCategory = htmlspecialchars ($_POST['original_category']);
   $selectedCategory = htmlspecialchars ($_POST['selected_category']);
   $categoryType = htmlspecialchars ($_POST['category_type']);

   
   $sqlContentCategories = "SELECT * FROM content_categories WHERE content_categoryType = '$categoryType'";
   $sqlContentCategoriesResult = mysqli_query($conn,$sqlContentCategories);

      

if ($selectedCategory == 'Select Category') {
    echo "<option class='category-option' value='".$selectedCategory."' selected hidden>Select Category</option>";
}

if ($selectedCategory != 'Select Category') {
    echo "<option class='category-option' value='".$selectedCategory."' selected hidden>$selectedCategory</option>"; 
}
 
   if ($sqlContentCategoriesResult->num_rows>0) {
   
      while ($contentCategory = $sqlContentCategoriesResult->fetch_assoc()) {
      $contentCategoryName = $contentCategory ['content_categoryName'];
      $contentCategoryId = $contentCategory ['content_categoryId'];

        if ($contentCategoryName != $selectedCategory) {
            echo "<option class='category-option' value='".$contentCategoryName."' id='$contentCategoryId'>
            $contentCategoryName 
            </option>";
    
        }
        

     
      
    

      }

   }



  
}



if (isset($_POST['get_all_categories_submit'])){
    $contentType = htmlspecialchars($_POST['content_type']);

    $sqlAllCategories = "SELECT * FROM content_categories WHERE content_categoryType = '$contentType' ORDER BY content_categoryName ASC";
    $sqlAllCategoriesResult = mysqli_query($conn,$sqlAllCategories);


    if ($sqlAllCategoriesResult->num_rows>0){
        echo "
            <div>
                <small>Click to remove</small>
            </div>
            <br>

             <div id='all-categories-list'>
        ";
        while($allCategories = $sqlAllCategoriesResult->fetch_assoc()){
            $allCategoryId = $allCategories ['content_categoryId'];
            $allCategoryName = $allCategories ['content_categoryName'];


            echo "
               
            <span class='link-tag-button' id='".'delete-category-'.$allCategoryId."' style='margin: 5px;'>$allCategoryName</span>
               
               
            ";
        }

        echo "
            </div>
            ";
    } else {
         echo "
                <div>
                    <small>No category</small>
                </div>
            ";
    }




}



if (isset($_POST['delete_category_submit'])){
    //variables
    $categoryId = htmlspecialchars($_POST['category_id']);
    $categoryName = htmlspecialchars($_POST['category_name']);
    $contentType = htmlspecialchars($_POST['content_type']);
    
    $responses = [];

    if ($contentType == 'Teacher File'){
        $table = 'teacher_files';
        $categoryColumn = 'teacher_fileCategory';
        $idColumn = 'teacher_fileId';
    }

    if ($contentType == 'Article'){
        $table = 'writer_articles';
        $categoryColumn = 'writer_articleCategory';
        $idColumn = 'writer_articleId';
    }

    if ($contentType == 'Research'){
        $table = 'school_researches';
        $categoryColumn = 'school_researchCategory';
        $idColumn = 'school_researchId';
    }

    if ($contentType == 'Tool'){
        $table = 'developer_tools';
        $categoryColumn = 'developer_toolCategory';
        $idColumn = 'developer_toolId';
    }



   
    //delete the category
    $sqlDeleteFromCategories = mysqli_query($conn,"delete from content_categories where content_categoryId = '$categoryId' LIMIT 1");


    $newCategory = 'Uncategorized';
    //update the category of the content with the deleted category to 'Uncategorized'
    $sqlUpdateCategoryTable = "UPDATE $table 
                        SET $categoryColumn = ?
                        WHERE $categoryColumn = '$categoryName'";
    $stmt = mysqli_stmt_init($conn);
    $prepareStmt = mysqli_stmt_prepare($stmt,$sqlUpdateCategoryTable);

    if ($prepareStmt) {
        mysqli_stmt_bind_param($stmt,"s", $newCategory);
        mysqli_stmt_execute($stmt);
    }


    $responses ['status'] = 'Successful';
    $responses ['success-message'] = $categoryName.' has been removed from '.$contentType.' categories successfully.';

      if ($responses) {
            header('Content-Type: application/json');
            $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
            echo  $jsonResponses;
        } 


}



if (isset($_POST['get_access_types_submit'])) {
   $mode = htmlspecialchars ($_POST['mode']);
   $originalAccessType = htmlspecialchars ($_POST['original_access_type']);
   $selectedAccessType = htmlspecialchars ($_POST['selected_access_type']);
   $accessTypeType = htmlspecialchars ($_POST['access_type_type']);

    if (!$selectedAccessType) {
        echo "<option class='access-type-option' value='".'Free Access'."' selected hidden>Free Access</option>";
    }

     if ($selectedAccessType) {
        echo "<option class='access-type-option' value='".$selectedAccessType."' selected hidden>$selectedAccessType</option>";
    }

    if ($selectedAccessType != 'Free Access') {
        echo "<option class='access-type-option' value='".'Free Access'."'>Free Access</option>";
    }

    if ($accessTypeType=='Teacher File') {
        if ($sellerSubscribed) {
             if ($selectedAccessType!== 'Purchased') {
            echo "<option class='access-type-option' value='".'Purchased'."'>Purchased</option>";
             }
        }
            
    }

   
    
    if ($accessTypeType=='Tool') {
            if ($selectedAccessType != 'Subscription') {
            echo "<option class='access-type-option' value='".'Subscription'."'>Subscription</option>";
    }
    }

    


}





if (isset($_POST['add_category_submit'])){
  
    $categoryType = htmlspecialchars($_POST['category_type']);
    $categoryName = htmlspecialchars($_POST['category_name']);

    $responses= [];
    $responses ['error'] =[];

    if (!$categoryName){
         $error = 'Please enter a category.';
        array_push($responses['error'],$error);
    } else {
        $sqlCheckCategory = "SELECT * FROM content_categories WHERE content_categoryType = '$categoryType' AND content_categoryName = '$categoryName'";
        $sqlCheckCategoryResult = mysqli_query($conn,$sqlCheckCategory);
        $checkedCategory = $sqlCheckCategoryResult->fetch_assoc();

        
        if ($checkedCategory) {
            $error = $categoryName.' has already been added to the '.$categoryType.' categories.';
            array_push($responses['error'],$error);
        }

    }

     
    
    if (!$responses['error']) {
           $sqlInsertToCategories = "INSERT INTO content_categories (content_categoryType,content_categoryName) VALUES (?,?)";
                $stmt= $conn->prepare($sqlInsertToCategories);
                $stmt ->bind_param("ss",$categoryType,$categoryName);
                $stmt-> execute(); 

         $responses ['status'] = 'Successful';
         $responses ['success-message'] = $categoryName.' has been added to the '.$categoryType.' categories.';
    } else {
        $responses ['status'] = 'Unsuccessful';
    }


  if ($responses) {
    header('Content-Type: application/json');
    $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
    echo  $jsonResponses;
} 
    

    
}



   

if (isset($_POST['get_version_content_submit'])){
        $contentId = htmlspecialchars($_POST['content_id']);
        $versionNumber = htmlspecialchars($_POST['version_number']);
        $contentType = htmlspecialchars($_POST['content_type']);
        $contentTable = '';

        if ($contentType == 'Article'){
            $contentTable = 'writer_articles';
        }

        $responses = [];
        $sqlVersion = "SELECT * FROM content_versions WHERE content_versionTable = '$contentTable' AND content_versionContentId = $contentId AND content_versionNumber = $versionNumber";
        $sqlVersionResult = mysqli_query($conn,$sqlVersion);
        $version = $sqlVersionResult->fetch_assoc();

        if ($version) {
            $versionContent = $version ['content_versionContent'];
            $responses ['version-content'] = $versionContent;
        }

    
    
    
        if ($responses) {
      header('Content-Type: application/json');
      $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
      echo  $jsonResponses;
   } 


}


if (isset($_POST['save_content_submit'])) {
    $contentId = htmlspecialchars($_POST['content_id']);
    $version = htmlspecialchars($_POST['version']);
    $mode = htmlspecialchars($_POST['mode']);
    $status = htmlspecialchars($_POST['status']);
    $saveIn = htmlspecialchars($_POST['save_in']);

    $title = htmlspecialchars($_POST['title']);
    $category = htmlspecialchars($_POST['category']);
    $tags = htmlspecialchars($_POST['tags']);
    $accessType = htmlspecialchars($_POST['access_type']);
    $description = htmlspecialchars($_POST['description']);
    
    $content = '';

  
    $sharedWith = htmlspecialchars($_POST['shared_with']);


    if ($saveIn == 'teacher_files'){
          $amount = htmlspecialchars($_POST['amount']);
    }

    if ($saveIn == 'school_researches'){
          $date = htmlspecialchars($_POST['date']);
          $proponents = htmlspecialchars($_POST['proponents']);
    }






    if ($saveIn=='teacher_files') {
        $titleColumn = 'teacher_fileTitle';
        $registrantIdColumn = 'teacher_fileTeacher';
        $categoryColumn = 'teacher_fileCategory';
        $tagsColumn = 'teacher_fileTags';
        $accessTypeColumn = 'teacher_fileAccessType';
        $descriptionColumn = 'teacher_fileDescription';
        $contentColumn = 'teacher_fileContent';
        $sharedWithColumn = 'teacher_fileSharedWith';
        $amountColumn ='teacher_fileAmount';
        $versionColumn = 'teacher_fileContentVersion';
        $idColumn = 'teacher_fileId';
        $slugColumn = 'teacher_fileSlug';
        $statusColumn = 'teacher_fileStatus';
     
    }

    if ($saveIn=='writer_articles') {
        $titleColumn = 'writer_articleTitle';
        $registrantIdColumn = 'writer_articleWriter';
        $categoryColumn = 'writer_articleCategory';
        $tagsColumn = 'writer_articleTags';
        $accessTypeColumn = 'writer_articleAccessType';
        $descriptionColumn = 'writer_articleDescription';
        $contentColumn = 'writer_articleContent';
        $sharedWithColumn = 'writer_articleSharedWith';
        $versionColumn = 'writer_articleContentVersion';
        $idColumn = 'writer_articleId';
        $slugColumn = 'writer_articleSlug';
        $statusColumn = 'writer_articleStatus';
    }

    if ($saveIn=='school_researches') {
        $titleColumn = 'school_researchTitle';
        $registrantIdColumn = 'school_researchSchool';
         $categoryColumn = 'school_researchCategory';
         $tagsColumn = 'school_researchTags';
        $accessTypeColumn = 'school_researchAccessType';
        $descriptionColumn = 'school_researchAbstract';
        $contentColumn = 'school_researchContent';
        $sharedWithColumn = 'school_researchSharedWith';
        $versionColumn = 'school_researchContentVersion';
        $idColumn = 'school_researchId';
        $slugColumn = 'school_researchSlug';
        $statusColumn = 'school_researchStatus';
        $dateColumn = 'school_researchDate';
        $proponentsColumn = 'school_researchProponents';
    }

    if ($saveIn=='developer_tools') {
        $titleColumn = 'developer_toolTitle';
        $registrantIdColumn = 'developer_toolDeveloper';
         $categoryColumn = 'developer_toolCategory';
         $tagsColumn = 'developer_toolTags';
        $accessTypeColumn = 'developer_toolAccessType';
        $descriptionColumn = 'developer_toolDescription';
        $contentColumn = 'developer_toolContent';
        $sharedWithColumn = 'developer_toolSharedWith';
        $versionColumn = 'developer_toolContentVersion';
        $idColumn = 'developer_toolId';
        $slugColumn = 'developer_toolSlug';
        $statusColumn = 'developer_toolStatus';
    }
    
    $responses= [];
    $responses['error'] = [];
  
    if ($saveIn == 'writer_articles') {
        if (isset($_POST['content'])) {
            $content = $_POST['content'];
        }   
    }

    if ($saveIn == 'teacher_files' || $saveIn == 'school_researches') {
        if ($mode=='new') {
             if (isset($_FILES['content'])) {
            $content = $_FILES['content'];  
            } 
        }

        if ($mode=='edit') {
            $content = htmlspecialchars($_POST['content']);  
        }
         
    }



    if (!$title) {
        $error = 'Please provide a title.';
        array_push($responses['error'], $error); 
    }else {

        if ($saveIn != 'developer_tools') {

            $sqlContentTitle = "SELECT * FROM $saveIn WHERE $titleColumn='$title'";
            $sqlContentTitleResult = mysqli_query($conn,$sqlContentTitle);
            $contentTitle =  $sqlContentTitleResult-> fetch_assoc();

            if ($contentTitle) {
                $contentRegistrantId = $contentTitle [$registrantIdColumn];

                if ($contentRegistrantId != $registrantId){
                    $error= 'Title already exists.';
                    array_push($responses['error'], $error); 
                }
            }
        } 


        if ($saveIn == 'developer_tools') {
            $toolName = str_replace(" ","-",$title);
            $toolFolder = strtolower($toolName);
            $createdDate = date("Y-m-d H:i:s", $currentTime);
            $folderPath = '../../../public/tools/'.$toolFolder.'/';

            // Create folders if they don't exist
                    if (!is_dir($folderPath)) {
                        mkdir($folderPath, 0777, true);
                    }
            
            $folderPathLink= substr($folderPath,8);
            $slug = $folderPathLink;

            $sqlCheckFolder = "SELECT * FROM developer_tools WHERE developer_toolSlug = '$folderPathLink'";
            $sqlCheckFolderResult = mysqli_query($conn,$sqlCheckFolder);
            $folder=$sqlCheckFolderResult->fetch_assoc();

            if ($folder) {
                $developerId = $folder [$registrantIdColumn];

                if ($developerId != $registrantId){
                 $error= 'The tool already exists.';
                array_push($responses['error'], $error);
                }
                 
            } 
        }
    }






    if ($category) {
         if ($category =='Select Category') {
             $error = 'Please select category.';
            array_push($responses['error'], $error);
        }
    }

  

    if (!$accessType) {
        $error = 'Please select access type.';
        array_push($responses['error'], $error);
    }


    if (!$description) {
        $error = 'Please provide description.';
        array_push($responses['error'], $error);
    }


    if ($saveIn == 'teacher_files') {
            if ($accessType == 'Purchased') {
                if (!$amount) {
                $error = 'Amount is required to sell your file.';
                array_push($responses['error'], $error);
                }
            }
    }



    if ($saveIn == 'school_researches') {
        
        if (!$date) {
        $error = 'Please provide the research date.';
        array_push($responses['error'], $error);
        }

        if (!$proponents) {
        $error = 'Please provide the research proponents.';
        array_push($responses['error'], $error);
        }
           
    }


    if ($saveIn != 'developer_tools') {

        if ($mode=='new') {
             if (!$content) {
            $error = 'Please provide the content.';
            array_push($responses['error'], $error);

            } else {

                if ($saveIn == 'teacher_files' || $saveIn == 'school_researches') {
                    $contentFileName = '';
                    $contentFileSize = '';
                    $contentFileNameExt = '';
                    $contentFileNameActualExt = '';

                    $allowedFormats = ['pdf'];
                

                    $contentFileName = $content ['name'];
                    $contentFileSize = $content ['size'];
                    $contentFileNameExt = explode ('.',$contentFileName);
                    $contentFileNameActualExt = strtolower(end($contentFileNameExt));


                    if (!in_array($contentFileNameActualExt,$allowedFormats)) {
                            $error = 'Please attach a file in this format: '.implode(', ',$allowedFormats).'.';
                            array_push($responses ['error'],$error);
                    }
        
                    
                }



            }

        }
       
    } 
    

    if ($saveIn != 'developer_tools') {
        $slug = generateSlug($title);
    }

    
    

    if ($mode=='new') {
        $status = 'Draft';
    }


    if ($mode == 'new'){
        $version = 1;
    }

    if ($mode == 'edit'){
         if ($saveIn == 'writer_articles') {
        $version = (int) $version + 1;
        }
        
        if ($saveIn != 'writer_articles') {
            $version = $version;
        }
    }
   



    if (!$responses['error']) {

        if ($mode == 'new') {
            if ($saveIn == 'teacher_files' || $saveIn =='school_researches') {
   
                if ($saveIn == 'teacher_files') {
                   $contentFolder = '../../uploads/documents/teacher/files/';
                   $formatColumn = 'teacher_fileFormat';
                }

                 if ($saveIn == 'school_researches') {
                   $contentFolder = '../../uploads/documents/school/researches';
                    $formatColumn = 'school_researchFormat';
                }

                // Create folders if they don't exist
                if (!is_dir($contentFolder)) {
                    mkdir($contentFolder, 0777, true);
                }
                
                $name=$registrantId.'-'.bin2hex($title);
                $actualContent= $name.'.'.$contentFileNameActualExt;
                $contentPath =$contentFolder.$actualContent;

                $uploadOk = 1;

                // Move uploaded files
                        if (move_uploaded_file($_FILES['content']["tmp_name"], $contentPath)) {
                            $uploadOk = 1;
                        } 
                $content= substr($contentPath,5);


        }

            $sqlInsertToTable = "INSERT INTO $saveIn 
                                ($titleColumn,
                                $categoryColumn,
                                $tagsColumn,
                                $descriptionColumn,
                                $accessTypeColumn,
                                $sharedWithColumn,
                                $contentColumn,
                                $versionColumn,
                                $slugColumn,
                                $registrantIdColumn,
                                $statusColumn)
                                VALUES (? ,?,?,?,?,?,?,?,?,?,?)";
            

            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt,$sqlInsertToTable);

            if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt,"sssssssssss",
                    $title,
                    $category,
                    $tags,
                    $description,
                    $accessType,
                    $sharedWith,
                    $content,
                    $version,
                    $slug,
                    $registrantId,
                    $status
                );
                mysqli_stmt_execute($stmt);

                $contentId = mysqli_insert_id($conn);

                $sqlInsertToContents = "INSERT INTO contents (contentTable,contentForeignId,contentRegistrantId,contentSharedWith,contentStatus) VALUES (?,?,?,?,?)";
                $stmt=$conn->prepare($sqlInsertToContents);
                $stmt ->bind_param("sssss",$saveIn,$contentId,$registrantId,$sharedWith,$status);
                $stmt-> execute(); 


                if($saveIn == 'teacher_files' || $saveIn == 'school_researches') {
                    $sqlInsertFormat = "UPDATE $saveIn 
                                SET $formatColumn =?
                                WHERE $idColumn = $contentId";

                    $stmt = mysqli_stmt_init($conn);
                    $prepareStmt = mysqli_stmt_prepare($stmt,$sqlInsertFormat);

                    if ($prepareStmt) {
                        mysqli_stmt_bind_param($stmt,"s",$contentFileNameActualExt);
                        mysqli_stmt_execute($stmt);

                    }


                    if ($saveIn == 'teacher_files') {
                        $sqlUpdateTeacherFile = "UPDATE $saveIn 
                                SET $amountColumn =?
                                WHERE $idColumn = $contentId";

                        $stmt = mysqli_stmt_init($conn);
                        $prepareStmt = mysqli_stmt_prepare($stmt,$sqlUpdateTeacherFile);

                        if ($prepareStmt) {
                            mysqli_stmt_bind_param($stmt,"s",$amount);
                            mysqli_stmt_execute($stmt);

                        }

                    }


                    if ($saveIn == 'school_researches') {
                        $sqlUpdateResearch = "UPDATE $saveIn 
                                SET $dateColumn =?,
                                    $proponentsColumn =?
                                WHERE $idColumn = $contentId";

                        $stmt = mysqli_stmt_init($conn);
                        $prepareStmt = mysqli_stmt_prepare($stmt,$sqlUpdateResearch);

                        if ($prepareStmt) {
                            mysqli_stmt_bind_param($stmt,"ss",$date,$proponents);
                            mysqli_stmt_execute($stmt);

                        }

                    }

                }
                 
            }
        }



        if ($mode == 'edit') {
            $sqlUpdateTable = "UPDATE $saveIn 
                                SET $titleColumn =?,
                                $categoryColumn= ?,
                                $tagsColumn = ?,
                                $descriptionColumn = ?,
                                $accessTypeColumn = ?,
                                $sharedWithColumn = ?,
                                $contentColumn = ?,
                                $versionColumn = ?,
                                $slugColumn= ?,
                                $registrantIdColumn= ?,
                                $statusColumn= ?
                                WHERE $idColumn = $contentId";


            

            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt,$sqlUpdateTable);
            if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt,"sssssssssss",
                    $title,
                    $category,
                    $tags,
                    $description,
                    $accessType,
                    $sharedWith,
                    $content,
                    $version,
                    $slug,
                    $registrantId,
                    $status
                );
                mysqli_stmt_execute($stmt);


                $sqlUpdateContents = "UPDATE contents 
                                SET                       
                                    contentTable=?,
                                    contentRegistrantId = ?,
                                    contentSharedWith = ?,
                                    contentStatus=?
                                WHERE contentForeignId = $contentId";

                $stmt=$conn->prepare($sqlUpdateContents);
                $stmt ->bind_param("ssss",$saveIn,$registrantId,$sharedWith,$status);
                $stmt-> execute(); 





                 if ($saveIn == 'teacher_files') {
                        $sqlUpdateTeacherFile = "UPDATE $saveIn 
                                SET $amountColumn =?
                                WHERE $idColumn = $contentId";

                        $stmt = mysqli_stmt_init($conn);
                        $prepareStmt = mysqli_stmt_prepare($stmt,$sqlUpdateTeacherFile);

                        if ($prepareStmt) {
                            mysqli_stmt_bind_param($stmt,"s",$amount);
                            mysqli_stmt_execute($stmt);

                        }

                    }


                    if ($saveIn == 'school_researches') {
                        $sqlUpdateResearch = "UPDATE $saveIn 
                                SET $dateColumn =?,
                                    $proponentsColumn =?
                                WHERE $idColumn = $contentId";

                        $stmt = mysqli_stmt_init($conn);
                        $prepareStmt = mysqli_stmt_prepare($stmt,$sqlUpdateResearch);

                        if ($prepareStmt) {
                            mysqli_stmt_bind_param($stmt,"ss",$date,$proponents);
                            mysqli_stmt_execute($stmt);

                        }

                    }

            }

        }


        
        if ($saveIn == 'writer_articles') {
                $sqlInsertToVersions = "INSERT INTO content_versions (content_versionTable,content_versionContentId,content_versionNumber,content_versionContent) VALUES (?,?,?,?)";
                $stmt= $conn->prepare($sqlInsertToVersions);
                $stmt ->bind_param("ssss",$saveIn,$contentId,$version,$content);
                $stmt-> execute(); 
        }

        $successMessage= '';

        if ($mode=='new'){
            $successMessage = 'The content has been added successfully!';
        }

         if ($mode=='edit'){
            $successMessage = 'The content has been updated successfully!';
        }
        $responses ['status'] = 'Successful';
        $responses ['success-message'] = $successMessage;
        $responses ['content-id'] = $contentId;

          

    } else {
        $responses ['status'] = 'Unsuccessful';
    }



 if ($responses) {
      header('Content-Type: application/json');
      $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
      echo  $jsonResponses;
   } 



}




if (isset($_POST['upload_tool_file_submit'])) {

    $toolLink= str_replace($domain,'',htmlspecialchars($_POST ['tool_link']));
    $toolId= htmlspecialchars($_POST ['tool_id']);


    $fileFileName = '';
    $fileFileNameExt = '';
    $fileFileNameActualExt = '';


    if (isset($_FILES['tool_file'])) {
        $file = $_FILES['tool_file'];

        $fileFileName = $file ['name'];
        $fileFileNameExt = explode ('.',$fileFileName);
        $fileFileNameActualExt = strtolower(end($fileFileNameExt));
    }
    

    $allowedFile = ['php','htm','html','css','js','java','jsx','cpp','py'];      
    $uploadDate = date("Y-m-d H:i:s", $currentTime);

   
   
    $responses = [];
     $responses['error'] = [];

    if (!$fileFileName){
        $error = 'Please attach a file';
        array_push($responses['error'], $error);

    } else {
        if(!in_array( $fileFileNameActualExt,$allowedFile)) {
            $error = 'Please attach a file in any of these formats:'.implode(', ',$allowedFile).'.';
            array_push($responses['error'], $error);

        } else {
            $filePath = '../../..'.$toolLink.$fileFileName;

            $fileLink = substr($filePath,8);

            $sqlCheckIfFileExists = "SELECT * FROM developer_tool_files WHERE developer_tool_fileLink='$fileLink'";
            $sqlCheckIfFileExistsResult = mysqli_query($conn,$sqlCheckIfFileExists);
            $fileExists = $sqlCheckIfFileExistsResult->fetch_assoc();

            if ($fileExists) {
               $error = 'The file with the same name exists. Delete it and reupload.';
                array_push($responses['error'], $error); 
            } 

        }

    } 

    if (!$responses['error']) {

        $uploadOk = 1;

      // Move uploaded files
      if (move_uploaded_file($_FILES['tool_file']["tmp_name"],  $filePath)) {
        $uploadOk = 1;    
      } 


      $sqlInsertToFiles = "INSERT INTO developer_tool_files (developer_tool_fileToolId,developer_tool_fileLink) VALUES (? ,?)";
        

       $stmt = mysqli_stmt_init($conn);
        $prepareStmt = mysqli_stmt_prepare($stmt,$sqlInsertToFiles);

        if ($prepareStmt) {
            mysqli_stmt_bind_param($stmt,"ss",$toolId,$fileLink);
            mysqli_stmt_execute($stmt);

            
             $responses ['status'] = 'Successful';
             $responses ['success-message'] = 'The tool file has been uploaded successfully!';
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






if (isset($_POST['replace_content_submit'])){
    $contentType = htmlspecialchars($_POST['content_type']);
    $contentId = htmlspecialchars($_POST['content_id']);
    $contentTitle = htmlspecialchars($_POST['content_title']);
    $contentFile = htmlspecialchars($_POST['content_file']);
    $contentStatus= htmlspecialchars($_POST['content_status']);

    $newContent= isset($_FILES['new_content']) ? ($_FILES['new_content']) : '';
    

    $contentTable = '';
    $contentIdColumn = ''; 
    $contentColumn = '';

     if ($contentType =='Teacher File'){
        $contentTable = 'teacher_files';
        $contentIdColumn = 'teacher_fileId'; 
        $contentColumn = 'teacher_fileContent';
    }

     if ($contentType =='Research'){
        $contentTable = 'school_researches';
        $contentIdColumn = 'school_researchId'; 
        $contentColumn = 'school_researchContent'; 
    }

 
    $responses = [];
    $responses ['error'] = [];

    if ($contentStatus == 'Draft'){


        if($newContent) {
            $contentFileName = '';
            $contentFileSize = '';
            $contentFileNameExt = '';
            $contentFileNameActualExt = '';

            $allowedFormats = ['pdf'];
        
            $contentFileName = $newContent ['name'];
            $contentFileSize = $newContent ['size'];
            $contentFileNameExt = explode ('.',$contentFileName);
            $contentFileNameActualExt = strtolower(end($contentFileNameExt));

             if (!in_array($contentFileNameActualExt,$allowedFormats)) {
                $error = 'You attached an invalid format.';
                array_push($responses ['error'],$error);
            } else {
                // if ($contentFile) {
                //   $contentFileUnlinked= unlink('../../'.$contentFile);  
                // }

                        if ($contentTable == 'teacher_files') {
                        $contentFolder = '../../uploads/documents/teacher/files/';
                        $formatColumn = 'teacher_fileFormat';
                        }

                        if ($contentTable == 'school_researches') {
                        $contentFolder = '../../uploads/documents/school/researches';
                        $formatColumn = 'school_researchFormat';
                        }

                        // Create folders if they don't exist
                        if (!is_dir($contentFolder)) {
                            mkdir($contentFolder, 0777, true);
                        }
                        
                        $name=$registrantId.'-'.bin2hex($contentTitle);
                        $actualContent= $name.'.'.$contentFileNameActualExt;
                        $contentPath =$contentFolder.$actualContent;

                        $uploadOk = 1;

                        // Move uploaded files
                                if (move_uploaded_file($_FILES['new_content']["tmp_name"], $contentPath)) {
                                    $uploadOk = 1;
                                } 
                        $content= substr($contentPath,5);
                            
                        $sqlUpdateContent = "UPDATE $contentTable 
                                SET $contentColumn =?
                                WHERE $contentIdColumn = $contentId";

                        $stmt = mysqli_stmt_init($conn);
                        $prepareStmt = mysqli_stmt_prepare($stmt,$sqlUpdateContent);

                        if ($prepareStmt) {
                            mysqli_stmt_bind_param($stmt,"s",$content);
                            mysqli_stmt_execute($stmt);

                            $responses ['status'] = "Successful";
                             $responses ['new-file'] = $content;

                        }

       
            }

        } else {
            $error = 'Please attach a new content.';
            array_push($responses ['error'],$error);
        }
        

       

    } else {
        $error = "You can't replace you content once published";
        array_push($responses['error'],$error);
    }


    if(!$responses ['error']){
                    
    } else {
        $responses['status'] = 'Unsuccessful';
    }


    if ($responses) {
      header('Content-Type: application/json');
      $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
      echo  $jsonResponses;
   } 
}





















if (isset($_POST['delete_content_submit'])) {
    $contentType = htmlspecialchars($_POST['content_type']);
    $contentId = htmlspecialchars($_POST['delete_content_id']);
   

       
    if ($contentType == 'Teacher File') {
    $table = 'teacher_files';
    $idColumn = 'teacher_fileId';
    $linkColumn = 'teacher_fileSlug';
    $imageColumn = 'teacher_fileImage';
    }

    if ($contentType == 'Article') {
    $table = 'writer_articles';
    $idColumn = 'writer_articleId';
    $linkColumn = 'writer_articleSlug';
    $imageColumn = 'writer_articleImage';
    }

    if ($contentType == 'Research') {
    $table = 'school_researches';
    $idColumn = 'school_researchId';
    $linkColumn = 'school_researchSlug';
    $imageColumn = 'school_researchImage';
    }

    if($contentType == 'Tool') {
    $table = 'developer_tools';
    $idColumn = 'developer_toolId';
    $linkColumn = 'developer_toolSlug';
    $imageColumn = 'developer_toolImage';
    }
    


    if ($table == 'developer_tools') {
        $getLink = "SELECT $linkColumn FROM $table WHERE $idColumn='$contentId'";
        $getLinkResult = mysqli_query($conn,$getLink);
        $link= $getLinkResult->fetch_assoc();

        if ($link) {
            $linkDelete = PROJECT_PATH.$link[$linkColumn];
            $deleted = rmdir($linkDelete );
        }

    }
    

    $toProceed = true;


    if ($table == 'developer_tools') {
        if ($deleted) {
            $toProceed = true;
        }else {
            $toProceed = false;
        }
    } else {
        $toProceed = true;
    }


    if ($toProceed) {
        $getImageLink = "SELECT $imageColumn FROM $table WHERE $idColumn='$contentId'";
        $getImageLinkResult = mysqli_query($conn,$getImageLink);
        $imageLink= $getImageLinkResult->fetch_assoc();

        if ($imageLink) {
            $imageLinkDelete = '../../'.$imageLink [$imageColumn];
            $imageDeleted= unlink($imageLinkDelete);
        }

        $sqlDelete = mysqli_query($conn,"delete from $table where $idColumn =   '$contentId'");

        $sqlDeleteFromContents = mysqli_query($conn,"delete from contents where contentTable = '$table' and contentForeignId='$contentId'");

        $sqlDeleteFromContentVersions = mysqli_query($conn,"delete from content_versions where content_versionTable = '$table' and content_versionContentId='$contentId'");
        
        echo 'Deleted Successfully';

    }
    


}




if (isset($_POST['delete_sub_content_submit'])) {
    
    $toolId= htmlspecialchars($_POST['delete_content_id']);
    $toolFileToDelete = htmlspecialchars($_POST['delete_sub_content_id']);


    $getToolFileLink = "SELECT * FROM developer_tool_files WHERE developer_tool_fileId='$toolFileToDelete'";
    $getToolFileLinkResult = mysqli_query($conn,$getToolFileLink);
    $toolFileLink= $getToolFileLinkResult->fetch_assoc();

    if ($toolFileLink) {
        $toolFileLinkDelete = PROJECT_PATH.$toolFileLink ['developer_tool_fileLink'];
        $toolFileDeleted= unlink($toolFileLinkDelete);
    }

   

    if ($toolFileDeleted) {   
    $sqlDeleteToolFile = mysqli_query($conn,"delete from developer_tool_files where developer_tool_fileId =   '$toolFileToDelete'");

    } 



}






//Pin or unpin article
if(isset($_POST['pin_unpin_article_submit'])) {

  
        $action = htmlspecialchars($_POST['action']);
        $articleId = htmlspecialchars($_POST['article_id']);
        $articleStatus = htmlspecialchars($_GET['article_status']);

        $articleEditor = '';

        if ($action =='pin') {
            $articleEditor = $registrantId;
        }

        if ($action =='unpin') {
            $articleEditor = '';
        }

    
        $responses = [];
    
    $sqlUpdateArticleEditorandStatus = "UPDATE writer_articles 
                        SET writer_articleStatus = ?,
                        writer_articleEditors = ?
                        WHERE writer_articleId =  $articleId";

        
    $stmt = mysqli_stmt_init($conn);
        $prepareStmt = mysqli_stmt_prepare($stmt, $sqlUpdateArticleEditorandStatus);
        
        if ($prepareStmt) {
        mysqli_stmt_bind_param($stmt,"ss", $articleStatus,$articleEditor);
        mysqli_stmt_execute($stmt);

       $responses ['status'] = 'Successful';

    }


    if ($responses) {
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        echo  $jsonResponses;
    } 
}












if (isset($_POST['update_content_status_submit'])) {

$contentType = htmlspecialchars($_POST['content_type']);
$contentId = htmlspecialchars($_POST['content_id']);
$action = htmlspecialchars($_POST['action']);
$idColumn = '';
$pubDateColumn = '';
$statusColumn ='';



if ($action=='publish') {
    $status ="Published";
}

if ($action=='unpublish') {
    $status ="Unpublished";
}

if ($action=='review') {
    $status ="Waiting for Update";
}


if ($action=='approve') {
    $status ="Approved";
}


if ($action=='take') {
    $status ="Waiting for Update";
}

if ($action=='leave') {
    $status ="Waiting for Update";
}









if ($contentType =='Teacher File') {
    $table='teacher_files';
    $idColumn = 'teacher_fileId';
    $pubDateColumn = 'teacher_filePubDate';
    $statusColumn = 'teacher_fileStatus';
}

if ($contentType =='Article') {
    $table='writer_articles';
    $idColumn = 'writer_articleId';
    $pubDateColumn = 'writer_articlePubDate';
    $statusColumn = 'writer_articleStatus';
}

if ($contentType =='Research') {
    $table='school_researches';
    $idColumn = 'school_researchId';
    $pubDateColumn = 'school_researchLiveDate';
    $statusColumn = 'school_researchStatus';
}

if ($contentType =='Tool') {
    $table='developer_tools';
    $idColumn = 'developer_toolId';
    $pubDateColumn = 'developer_toolPubDate';
    $statusColumn = 'developer_toolStatus';
}

 $sqlContentInfo = "SELECT * FROM $table WHERE $idColumn = '$contentId'";

    $sqlContentInfoResult = mysqli_query($conn,$sqlContentInfo);
    $contentInfo = $sqlContentInfoResult->fetch_assoc();

    $contentPubDate = $contentInfo [$pubDateColumn] !='0000-00-00 00:00:00' ? $contentInfo [$pubDateColumn] : date("Y-m-d H:i:s", $currentTime);


    $sqlUpdateContentStatus = "UPDATE $table 
                        SET $statusColumn = ?,
                        $pubDateColumn = ?
                        WHERE $idColumn =  $contentId";

    
   $stmt = mysqli_stmt_init($conn);
    $prepareStmt = mysqli_stmt_prepare($stmt, $sqlUpdateContentStatus);
    
    if ($prepareStmt) {
    mysqli_stmt_bind_param($stmt,"ss", $status,$contentPubDate);
    mysqli_stmt_execute($stmt);

    
    $sqlUpdateContent = "UPDATE contents 
                        SET contentStatus = ?,
                        contentPubDate = ?
                        WHERE contentTable = '$table' AND
                        contentForeignId = '$contentId'";

    
   $stmt = mysqli_stmt_init($conn);
    $prepareStmt = mysqli_stmt_prepare($stmt, $sqlUpdateContent );
    
    if ($prepareStmt) {
    mysqli_stmt_bind_param($stmt,"ss", $status,$contentPubDate);
    mysqli_stmt_execute($stmt);


        if($table == 'writer_articles'){
            if($action=='take' || $action=='leave'){
                $editor = '';

                if ($action == 'take'){
                    $editor = $registrantId;
                }

                if ($action == 'leave'){
                    $editor = '';
                }

                $sqlUpdateArticleEditors = "UPDATE writer_articles 
                                SET writer_articleEditors = ?
                                WHERE writer_articleId = '$contentId'";

            
                $stmt = mysqli_stmt_init($conn);
                $prepareStmt = mysqli_stmt_prepare($stmt, $sqlUpdateArticleEditors);
                
                if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt,"s", $editor);
                mysqli_stmt_execute($stmt);
                
                }

            }
        }
  


        echo 'Successful';
    }



    

    
                             
    }



}







if (isset($_POST['upload_image_submit'])) {

    $contentId = htmlspecialchars($_POST['content_id']);
    $uploadType = htmlspecialchars($_POST['upload_type']);

    $table = '';
    $idColumn = '';
    $imageColumn = '';
    $imageFolder = '';
    

     if ($uploadType == 'File Thumbnail') {
        $table = 'teacher_files';
        $idColumn = 'teacher_fileId';
        $imageFolder = '../../uploads/file-thumbnails/';
        $imageLinkColumn = 'teacher_fileImage';
    }

    if ($uploadType == 'Featured Image') {
        $table = 'writer_articles';
        $idColumn = 'writer_articleId';
        $imageFolder = '../../uploads/article-images/';
        $imageLinkColumn = 'writer_articleImage';
    }

     if ($uploadType == 'Research Thumbnail') {
        $table = 'school_researches';
        $idColumn = 'school_researchId';
        $imageFolder = '../../uploads/research-thumbnails/';
        $imageLinkColumn = 'school_researchImage';
    }

    if ($uploadType == 'Tool Icon') {
        $table = 'developer_tools';
        $idColumn = 'developer_toolId';
        $imageFolder = '../../uploads/tool-icons/';
        $imageLinkColumn = 'developer_toolImage';

    }

    $maxSize = 10 * 1024 * 1024;

    $allowedImage = ['jpeg','jpg'];
 

    $imageFileName = '';
    $imageFileSize = '';
    $imageFileNameExt = '';
    $imageFileNameActualExt = '';



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
        $error='Please choose an image in JPEG or  JPG format only.';
         array_push($responses['error'],$error);
        
        }

        if($imageFileSize>$maxSize) {
        $error='Your image is too big in size.';
          array_push($responses['error'],$error);
        }
        } else {
            $error='You did not select an image.';
            array_push($responses['error'],$error);
        }



    if (!$responses['error']) {

    $sqlData = "SELECT * FROM $table WHERE $idColumn = '$contentId'";
    $sqlDataResult = mysqli_query($conn,$sqlData);
    $data= $sqlDataResult->fetch_assoc();

    $imageLink = $data [$imageLinkColumn];
    

    if ($imageLink) {
        $imageLinkDelete = '../..'.$imageLink;
        $imageLinkDeleted = unlink($imageLinkDelete);
    } 
    

    // Create folders if they don't exist
    if (!is_dir($imageFolder)) {
        mkdir($imageFolder, 0777, true);
    }

    $imageFile = $imageFolder .$contentId."-".date("YmdHis",time()).".".$imageFileNameActualExt;

    $uploadOk = 1;

    if (move_uploaded_file($image["tmp_name"], $imageFile)) {
        $uploadOk = 1;
    } 


    if ($uploadType == 'Featured' || $uploadType == 'Tool Icon') {
            //Resize and crop image
        $maxResolution = 500;

        
        if ($imageFileNameActualExt=='jpeg') {
        $originalImage = imagecreatefromjpeg($imageFile);
        }

        if ($imageFileNameActualExt=='png') {
        $originalImage = imagecreatefrompng($imageFile);
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

            if ($uploadType == 'Featured Image'){
                $newCropImage = imagecreatetruecolor($maxResolution,$maxResolution/1.5);
            }

            if ($uploadType == 'Tool Icon'){
                    $newCropImage = imagecreatetruecolor($maxResolution,$maxResolution);
            }
        

            imagecopyresampled($newCropImage,$newImage,0,0,$x,$y,$maxResolution,$maxResolution,$maxResolution,$maxResolution); 
        

            imagejpeg($newCropImage,$imageFile,90);
        }

    }
    






    $uploadedImageFile= substr($imageFile,5);
    $imageStatus = 0;

    $sqlUpdateImage = "UPDATE $table
                        SET 
                        $imageLinkColumn=?
                        WHERE $idColumn = $contentId";


    $stmt = mysqli_stmt_init($conn);
    $prepareStmt = mysqli_stmt_prepare($stmt, $sqlUpdateImage);
    
    if ($prepareStmt) {
    mysqli_stmt_bind_param($stmt,"s", $uploadedImageFile);

    mysqli_stmt_execute($stmt);




     $responses ['status'] = 'Successful';
    
    
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


//submit comment

if (isset($_POST['comment_submit'])){
    $contentId = htmlspecialchars($_POST['content_id']);
    $contentComment = htmlspecialchars($_POST['content_comment']);
    $contentType = htmlspecialchars($_POST['content_type']);
    $role = htmlspecialchars($_POST['role']);


    $responses = [];
    $responses ['error'] = [];

    if (!$contentComment) {
        $error = 'Please provide your comment.';
        array_push($responses['error'],$error);

    } 

    if (!$responses['error']){
         $sqlInsertIntoComments= "INSERT INTO content_comments
                                (content_commentContentType,content_commentContentId,content_commentContent,content_commentRegistrant,content_commentRole) VALUES (?,?,?,?,?)";
        $stmt=$conn->prepare($sqlInsertIntoComments);
        $stmt ->bind_param("sssss",$contentType,$contentId,$contentComment,$registrantId,$role);
        $stmt-> execute();   


        if ($contentType=='Teacher File') {
            $table = 'teacher_files';
            $idColumn = 'teacher_fileId';
            $statusColumn = 'teacher_fileStatus';
        }

        if ($contentType=='Article') {
            $table = 'writer_articles';
            $idColumn = 'writer_articleId';
            $statusColumn = 'writer_articleStatus';
        }

        if ($contentType=='Research') {
            $table = 'school_researches';
            $idColumn = 'school_researchId';
            $statusColumn = 'school_researchStatus';
        }

        if ($contentType=='Tool') {
            $table = 'developer_tools';
            $idColumn = 'developer_toolId';
            $statusColumn = 'developer_toolStatus';
        }

            if ($role == 'Editor'){

                $status ='To Revise';

            
                $sqlUpdateContentStatus = "UPDATE $table 
                            SET $statusColumn = ?
                            WHERE $idColumn =  '$contentId'";

        
                $stmt = mysqli_stmt_init($conn);
                $prepareStmt = mysqli_stmt_prepare($stmt, $sqlUpdateContentStatus);
                
                if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt,"s", $status);
                mysqli_stmt_execute($stmt);

                }


                $sqlUpdateContent = "UPDATE contents 
                                SET contentStatus = ?
                                WHERE contentTable = '$table' AND
                                contentForeignId = '$contentId'";

                
                $stmt = mysqli_stmt_init($conn);
                $prepareStmt = mysqli_stmt_prepare($stmt, $sqlUpdateContent );
                
                if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt,"s", $status);
                mysqli_stmt_execute($stmt);
                }
            }
            

            
            
        

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

//get content comments

if (isset($_POST['get_content_comments_submit'])){
    $contentType =htmlspecialchars($_POST['content_type']);
    $contentId= htmlspecialchars($_POST['content_id']);
    $role = htmlspecialchars($_POST['role']);

    $sqlContentComments = "SELECT * FROM content_comments WHERE content_commentContentType = '$contentType' AND content_commentContentId=$contentId ORDER BY content_commentTimestamp DESC";
    $sqlContentCommentsResult = mysqli_query($conn,$sqlContentComments);

    if ($sqlContentCommentsResult ->num_rows >0) {
        while($contentComments = $sqlContentCommentsResult->fetch_assoc()){
            $contentCommentId = $contentComments ['content_commentId'];
            $contentCommentRegistrant = $contentComments ['content_commentRegistrant'];

                $sqlCommentRegistrantInfo = "SELECT * FROM registrations WHERE registrantId=  $contentCommentRegistrant";
                $sqlCommentRegistrantInfoResult = mysqli_query($conn,$sqlCommentRegistrantInfo);
                $commentRegistrantInfo =$sqlCommentRegistrantInfoResult->fetch_assoc();

                if ( $commentRegistrantInfo){
                     $commentRegistrantAccountName =  $commentRegistrantInfo ['registrantAccountName'];
                     $commentRegistrantProfilePicture =  $commentRegistrantInfo ['registrantProfilePictureLink'] ? $privateFolder.$commentRegistrantInfo ['registrantProfilePictureLink'] : $publicFolder.'/assets/images/user.svg';
                }

            $contentCommentRole = $contentComments ['content_commentRole'];
            $contentCommentContent = $contentComments ['content_commentContent'];
            $contentCommentTimestamp = dcomplete_format($contentComments ['content_commentTimestamp']);


            $divStyle = '';
            $contentStyle = '';

            if ($contentCommentRegistrant==$registrantId){
                 $divClass = 'thread-message-sender';
                $contentClass = 'thread-message-content-sender comment-sender';
            }

            if ($contentCommentRegistrant!=$registrantId){
                $divClass = 'thread-message-recipient';
                $contentClass = 'thread-message-content-recipient comment-recipient';
            }

            echo "
                
                <div class='".$divClass."'>
                
                
             
                  <p class='".$contentClass."'>$contentCommentContent</p>
                 
                   
                <img src='$commentRegistrantProfilePicture' style='width:20px;border-radius:50%;' title='$commentRegistrantAccountName'>
                <strong style='margin-top:-3px;font-size:10pt;'>$commentRegistrantAccountName</strong>
                <small style='margin-top:-3px;font-size:8pt;'>$contentCommentTimestamp</small>
            
                
                  
                 
                  
                  
                </div>
            ";

        }

    } else {
        echo "<small>No comment yet. </small>";
    }


}


    //Track content performance
    if (isset($_POST['track_content_performance_submit'])) {

    $contentTable = htmlspecialchars($_POST['content_table']);;
    $contentId = htmlspecialchars($_POST['content_id']);;
    $contentstatus = htmlspecialchars($_POST['content_status']);
    $isRefreshed = htmlspecialchars($_POST['is_refreshed']);
 
    
    $viewerId = 0;

    if ($registrantId) {
        $viewerId = $registrantId;
    }

    if ($contentStatus=='Published') {

        if (!$isRefreshed) {
            $sqlViewingRecords = "SELECT * FROM content_performance WHERE content_viewUserId = '$viewerId' AND content_viewTable='$contentTable' AND content_viewForeignId = '$contentId'";
            $sqlViewingRecordsResults = mysqli_query ($conn,$sqlViewingRecords);
            $viewingRecords = $sqlViewingRecordsResults-> fetch_assoc();

            if ($viewingRecords) {
                $viewingId = $viewingRecords ['content_viewId'];
                $contentViewTimeOld = $viewingRecords ['content_viewTime'];
                $contentViewTimeUpdated = $contentViewTimeNew + $contentViewTimeOld;
                $contentLastView = strtotime($viewingRecords ['content_viewLastUpdate']);     
                            
                $sqlUpdateViewingRecords= "UPDATE content_performance
                                    SET 
                                    content_viewTime=?
                                    WHERE content_viewId = $viewingId";


                $stmt = mysqli_stmt_init($conn);
                $prepareStmt = mysqli_stmt_prepare($stmt, $sqlUpdateViewingRecords);
                
                if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt,"s", $contentViewTimeUpdated);

                mysqli_stmt_execute($stmt);

                }
        
            }    
        } else {
            $sqlInsertViewingRecords= "INSERT INTO content_performance
                                (content_viewTime, content_viewTable,content_viewForeignId,content_viewUserId) VALUES (?,?,?,?)";
                $stmt=$conn->prepare($sqlInsertViewingRecords);
                $stmt ->bind_param("ssss",$contentViewTimeNew ,$contentTable,$contentId,$viewerId);
                $stmt-> execute();         
        }

    }
    
}




if (isset($_POST['get_purchase_content_info_submit'])){
    $contentType = htmlspecialchars($_POST['content_type']);
    $contentId = htmlspecialchars($_POST['content_id']);

    if ($contentType=='Teacher File') {
        $contentTable = 'teacher_files';
        $idColumn = 'teacher_fileId';
        $titleColumn = 'teacher_fileTitle';
        $sellerColumn = 'teacher_fileTeacher';
        $amountColumn = 'teacher_fileAmount';
        $slugColumn = 'teacher_fileSlug';
        $pageLink = $website.'/teacher-files/view/';
    }


    $responses = [];

    $sqlContentInfo = "SELECT * FROM $contentTable WHERE $idColumn = $contentId";
    $sqlContentInfoResult = mysqli_query($conn,$sqlContentInfo);
    $contentInfo = $sqlContentInfoResult->fetch_assoc();

    if ($contentInfo){
        $contentTitle = $contentInfo [$titleColumn];
        $contentSeller = $contentInfo [$sellerColumn];
        $contentSlug = $contentInfo [$slugColumn];      

        $sqlSellerInfo = "SELECT * FROM registrations WHERE registrantId = $contentSeller";
        $sqlSellerInfoResult = mysqli_query($conn,$sqlSellerInfo);
        $sellerInfo = $sqlSellerInfoResult->fetch_assoc();

        if ($sellerInfo) {
            $contentSellerName = $sellerInfo ['registrantAccountName'];
            $contentSellerCode = $sellerInfo ['registrantCode'];
            $contentSellerPaymentChannel = $sellerInfo ['registrantPaymentChannel'];
            $contentSellerBankAccountName = $sellerInfo ['registrantBankAccountName'];
            $contentSellerBankAccountNumber = $sellerInfo ['registrantBankAccountNumber'];
            $contentSellerPurchaseReview = $sellerInfo ['registrantReviewSchedules'];

            $responses ['payment-channel']=$contentSellerPaymentChannel;
            $responses ['account-name']=$contentSellerBankAccountName;
            $responses ['account-number']=$contentSellerBankAccountNumber;
            $responses ['purchase-review']=$contentSellerPurchaseReview;

            
        } else {
            $contentSellerName = 'Owner not found';
        }


        $contentAmount = $contentInfo ['teacher_fileAmount'];

        $responses ['amount'] =  $contentAmount;


        $sqlSellerSubscriptionInfo = "SELECT * FROM registrant_subscriptions WHERE registrant_subscriptionUserId = $contentSeller AND registrant_subscriptionType= 'Seller' ORDER BY registrant_subscriptionId DESC LIMIT 1";
        $sqlSellerSubscriptionInfoResult = mysqli_query($conn,$sqlSellerSubscriptionInfo);
        $sellerSubscriptionInfo =  $sqlSellerSubscriptionInfoResult->fetch_assoc();

        if($sellerSubscriptionInfo){
            $sellerSubscriptionStatus = $sellerSubscriptionInfo ['registrant_subscriptionStatus'];

            if ($sellerSubscriptionStatus == 'Approved' || $sellerSubscriptionStatus == 'Kept' || $sellerSubscriptionStatus == 'Revoked'){
                    $sellerSubscriptionStart = strtotime($sellerSubscriptionInfo['registrant_subscriptionDate']);
                $sellerSubscriptionExpiry = strtotime($sellerSubscriptionInfo['registrant_subscriptionExpiry']);

                if ($sellerSubscriptionExpiry-$currentTime > 0) {
                    $sellerSubscriptionActive = 'Active';
                }

                if ($sellerSubscriptionExpiry-$currentTime <= 0) {
                    $sellerSubscriptionActive = 'Expired';
                }

                $sellerSubscriptionStartRaw = $sellerSubscriptionInfo['registrant_subscriptionDate'];
                $sellerSubscriptionExpiryRaw = $sellerSubscriptionInfo['registrant_subscriptionExpiry'];

                $sellerSubscriptionRemainingDays = $sellerSubscriptionExpiry-$sellerSubscriptionStart;
                
            }


            if ($sellerSubscriptionStatus=='Pending'){
                $sellerSubscriptionStart = '-';
                $sellerSubscriptionExpiry = '-';
                $sellerSubscriptionRemainingDays = '-';
                $sellerSubscriptionActive = '-';
            }

            $hasSellerSubscriptionInfo = 'With record';
            $responses ['seller-subscription-status'] = $sellerSubscriptionStatus;

            $responses ['seller-subscription-start'] = $sellerSubscriptionStart;
                $responses ['seller-subscription-expiry'] = $sellerSubscriptionExpiry;

            if ($sellerSubscriptionStatus != 'Pending') {
                $responses ['seller-subscription-start'] = dcomplete_format($sellerSubscriptionStartRaw);
                $responses ['seller-subscription-expiry'] = dcomplete_format($sellerSubscriptionExpiryRaw);
            }
            

            $responses ['seller-subscription-remaining-days'] =  $sellerSubscriptionRemainingDays;
            $responses ['seller-subscription-active'] = $sellerSubscriptionActive;
            
        } else {
                $hasSellerSubscriptionInfo = 'No record';
        }



        $sqlContentPurchased = "SELECT * FROM content_purchase WHERE content_purchaseType= '$contentType' AND content_purchaseContentId = $contentId AND content_purchaseRegistrantId = '$registrantId'";
        $sqlContentPurchasedResult = mysqli_query($conn,$sqlContentPurchased);
        $contentPurchased = $sqlContentPurchasedResult->fetch_assoc();

        if ($contentPurchased){  
            $is_contentPurchased = 'Purchased';
            $contentPurchaseStatus =  $contentPurchased ['content_purchaseStatus'];
        } else {
             $is_contentPurchased = 'Not Purchased';
             $contentPurchaseStatus =  '';
        }







        $responses ['seller-id'] = $contentSeller;
        $responses ['purchased'] = $is_contentPurchased;
        $responses ['purchase-status'] = $contentPurchaseStatus;
        $responses ['seller-subscription-info'] = $hasSellerSubscriptionInfo;
        $responses ['title'] = $contentTitle;
        $responses ['href'] = $pageLink.$contentSlug;
        $responses ['seller-name'] = $contentSellerName;
        $responses ['seller-code'] =  $contentSellerCode;
        $responses ['content-info'] = 'Content found';
    } else {
        $responses ['info'] = ' Content not found';
    }

     if ($responses) {
      header('Content-Type: application/json');
      $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
      echo  $jsonResponses;
   } 


}






if (isset($_POST['purchase_content_submit'])) {
$contentType = htmlspecialchars($_POST ['purchase_content_type']);
$contentId = htmlspecialchars($_POST ['purchase_content_id']);
  $registrant = $registrantId;
  $seller = htmlspecialchars($_POST['seller_id']);
  $amount = htmlspecialchars($_POST ['amount']);
  $paymentChannel = htmlspecialchars($_POST ['payment_channel']);
  $referenceNumber= htmlspecialchars($_POST['reference_number']);
  $purchaseStatus = 'Pending';

$proofOfPayment = isset($_FILES ['payment_proof']) ? $_FILES ['payment_proof'] : '';
$proofOfPaymentFileName = $proofOfPayment ? $proofOfPayment ['name'] : '';
$proofOfPaymentFileNameFileNameExt = $proofOfPaymentFileName ? explode ('.',$proofOfPaymentFileName) : '';
$proofOfPaymentFileNameActualExt = $proofOfPaymentFileNameFileNameExt ? strtolower(end($proofOfPaymentFileNameFileNameExt)) : '';
 


$allowedExtsProofOfPayment = ['jpeg', 'jpg'];

 
$responses = [];
$responses ['error'] = [];


  if (empty($paymentChannel)) {
    $error = 'Please select payment channel.';
    array_push($responses ['error'],$error);
  } 

  if (empty($referenceNumber)) {
    $error = 'Please provide reference number.';
    array_push($responses ['error'],$error);
  } 

  if (empty($proofOfPaymentFileName)) {
    $error = 'Please provide proof of payment.';
    array_push($responses ['error'],$error);
  } else {
    if (!in_array($proofOfPaymentFileNameActualExt,$allowedExtsProofOfPayment)) {
      $error = 'Please attach a file in any of these formats: '.implode(', ',$allowedExtsProofOfPayment).'.';
        array_push($responses ['error'],$error);
    } 

  }

  
  


  if (!$responses ['error']) {
    $proofOfPaymentFolder = '../../uploads/content-purchase/proof/';
            
                if (!is_dir($proofOfPaymentFolder)) { 
                mkdir($proofOfPaymentFolder, 0777, true);
                }

                $proofOfPaymentFile = $proofOfPaymentFolder ."userid-".$registrant."-".date("YmdHis",time()).".".$proofOfPaymentFileNameActualExt;

                $uploadOk = 1;

              
              if (move_uploaded_file($proofOfPayment["tmp_name"], $proofOfPaymentFile)) {
                  $uploadOk = 1;
              } 

                $proofOfPaymentLink= substr($proofOfPaymentFile,5);

                
                $sql = "INSERT INTO content_purchase (content_purchaseType,content_purchaseContentId,content_purchaseAmount,content_purchaseRegistrantId,content_purchaseSeller,content_purchasePaymentChannel,content_purchaseReferenceNumber,content_purchaseStatus,content_purchaseProofLink) VALUES (?,?, ?, ?, ?,?,?,?,?)";

                $stmt =$conn->prepare($sql);

                $stmt ->bind_param("ssissssss", $contentType,$contentId,$amount,$registrant,$seller,$paymentChannel,$referenceNumber,$purchaseStatus,$proofOfPaymentLink);

                $stmt-> execute();

                $responses ['status'] = 'Successful';
                $responses ['success-message'] = 'Your purchase has been sent successfully. It will be reviewed by the owner.';
                
  } else{
    $responses ['status'] = 'Unsuccessful';
  }


   if ($responses) {
      header('Content-Type: application/json');
      $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
      echo  $jsonResponses;
   } 



}





if(isset($_POST['get_content_purchases_submit'])){
$filteredStatus = htmlspecialchars($_POST['filtered_status']);
$contentType = htmlspecialchars($_POST['content_type']);
$responses = [];

$sqlContentPurchases = "SELECT * FROM content_purchase WHERE content_purchaseType = '$contentType' AND content_purchaseSeller = $registrantId ORDER BY content_purchaseId DESC";

if ($filteredStatus){
    $sqlContentPurchases = "SELECT * FROM content_purchase WHERE content_purchaseType = '$contentType' AND content_purchaseSeller = $registrantId AND content_purchaseStatus = '$filteredStatus' ORDER BY content_purchaseId DESC";
}

$sqlContentPurchasesResult = mysqli_query($conn,$sqlContentPurchases);

$contentPurchaseCount = $sqlContentPurchasesResult-> num_rows;


if ($sqlContentPurchasesResult->num_rows > 0) {
    echo "
            <div id='purchase-count'> $contentPurchaseCount results</div>
        ";

    while($contentPurchases = $sqlContentPurchasesResult->fetch_assoc()){
        $contentPurchaseTimestamp = dcomplete_format($contentPurchases ['content_purchaseTimestamp']);
        $contentPurchaseId = $contentPurchases ['content_purchaseId'];
        $contentPurchaseRegistrant = $contentPurchases ['content_purchaseRegistrantId'];
      
            $sqlRegistration = "SELECT * FROM registrations WHERE registrantId = $contentPurchaseRegistrant";
            $sqlRegistrationResult = mysqli_query($conn,$sqlRegistration);
            $registration = $sqlRegistrationResult->fetch_assoc();

            if ($registration){
                $contentPurchaseRegistrantAccountName = $registration ['registrantAccountName'];
            }
        $contentPurchaseContentId = $contentPurchases ['content_purchaseContentId'];

            if ($contentType == 'Teacher File'){
                $contentTable = 'teacher_files';
                $idColumn = 'teacher_fileId';
                $titleColumn = 'teacher_fileTitle';
            }

            if ($contentType == 'Article'){
                $contentTable = 'writer_articles';
                $idColumn = 'writer_articleId';
                $titleColumn = 'writer_articleTitle';
            }

            if ($contentType == 'Research'){
                $contentTable = 'school_researches';
                $idColumn = 'school_researchId';
                $titleColumn = 'school_researchTitle';

            }

             if ($contentType == 'Tool'){
                $contentTable = 'developer_tools';
                $idColumn = 'developer_toolId';
                 $titleColumn = 'developer_toolTitle';
            }


            $sqlContentPurchaseInfo = "SELECT * FROM $contentTable WHERE $idColumn = $contentPurchaseContentId";
            $sqlContentPurchaseInfoResult = mysqli_query($conn,$sqlContentPurchaseInfo);
            $contentPurchaseInfo = $sqlContentPurchaseInfoResult->fetch_assoc();

            if ($contentPurchaseInfo) {
                $contentPurchaseTitle = $contentPurchaseInfo [$titleColumn];
            } else {
                 $contentPurchaseTitle = 'Not found';
            }

         $contentPurchaseAmount = $contentPurchases ['content_purchaseAmount'];
         $contentPurchasePaymentChannel = $contentPurchases ['content_purchasePaymentChannel'];
         $contentPurchaseReferenceNumber = $contentPurchases ['content_purchaseReferenceNumber'];
         $contentPurchaseStatus = $contentPurchases ['content_purchaseStatus'];
         $contentPurchasePaymentProof = $privateFolder.$contentPurchases ['content_purchaseProofLink'];
         $contentPurchasePaymentProofExt = pathinfo($contentPurchasePaymentProof, PATHINFO_EXTENSION);

         

         echo "
        <div id='".'details-for-purchase-'.$contentPurchaseId."' class='list-purchase-details' style=display: flex; flex-direction:column;'>
            <strong>
                $contentPurchaseTitle
            </strong>
           
            <div>
                <small>$contentPurchaseTimestamp</small>
                <small> | </small>
                <small>$contentPurchaseRegistrantAccountName</small>
                <small> | </small>
                <small>₱$contentPurchaseAmount</small>
                <small> | </small>
                <small>$contentPurchaseStatus</small>
            </div>
            <br>
             <div class='purchase-action-buttons'>
                <small class='link-tag-button' id='".'view-proof-purchase-'.$contentPurchaseId."'>View Proof</small>";

                if ($contentPurchaseStatus == 'Pending' || $contentPurchaseStatus == 'Rejected'){
                echo "
                 <small class='link-tag-button' id='".'approve-purchase-'.$contentPurchaseId."'>Approve</small>
                <small class='link-tag-button' id='".'reject-purchase-'.$contentPurchaseId."'>Reject</small>";
                }

                if ($contentPurchaseStatus == 'Approved' || $contentPurchaseStatus == 'Kept'){
                echo "
                <small class='link-tag-button' id='".'revoke-purchase-'.$contentPurchaseId."'>Revoke</small>";
                }

                 if ($contentPurchaseStatus == 'Revoked'){
                echo "
                <small class='link-tag-button' id='".'keep-purchase-'.$contentPurchaseId."'>Keep</small>";
                }
               
            
            echo "
                <small class='".'link-tag-button message'."' id='".'message-registrant-'.$contentPurchaseRegistrant."'>Message</small>
            </div>
         
         
           <hr>
  
        </div>
    
        <div id='".'payment-proof-for-purchase-'.$contentPurchaseId."' style='display: none;' class='payment-proof'>
            <img src='".$website.'/assets/images/return.png'."' class='icons' id='hide-purchase-proof'>
            <br>
            <img src='".$contentPurchasePaymentProof."' style='width: 400px;' id='purchase-proof'>
      
         </div>


       
         ";



    }

} else {
    echo "
            <div>No result</div>
        ";
}

  
}



if (isset($_POST['update_purchase_status_submit'])){
    $purchaseId = htmlspecialchars($_POST['purchase_id']);
    $action = htmlspecialchars($_POST['action']);

    if ($action=='approve'){
        $status = 'Approved';
    }

    if ($action=='reject'){
        $status = 'Rejected';
    }

    if ($action=='revoke'){
        $status = 'Revoked';
    }

    if ($action=='keep'){
        $status = 'Kept';
    }




    $sqlUpdatePurchaseStatus = "UPDATE content_purchase 
                SET content_purchaseStatus =?
                WHERE content_purchaseId = '$purchaseId'";

    $stmt = mysqli_stmt_init($conn);
    $prepareStmt = mysqli_stmt_prepare($stmt,$sqlUpdatePurchaseStatus);

    if ($prepareStmt) {
        mysqli_stmt_bind_param($stmt,"s",$status);
        mysqli_stmt_execute($stmt);

    }

}


if (isset($_POST['read_update_submit'])){
    $updateId = htmlspecialchars($_POST['update_id']);

    $responses = [];

    $responses['error'] = [];

    $sqlUpdateDetails = "SELECT * FROM updates WHERE updateId = $updateId";
    $sqlUpdateDetailsResult = mysqli_query($conn,$sqlUpdateDetails);
    $updateDetails = $sqlUpdateDetailsResult -> fetch_assoc();


    if ($updateDetails){
        $updateTitle = $updateDetails ['updateTitle'];
        $updateTimestamp = dcomplete_format($updateDetails ['updateTimestamp']);
        $updateContent = $updateDetails ['updateContent'];
        $updateStatus = $updateDetails ['updateStatus'];

        $updateViewers = $updateDetails ['updateViewers'];

        $responses ['title'] = $updateTitle;
        $responses ['timestamp'] = $updateTimestamp;
        $responses ['content'] = nl2br($updateContent);
        $responses ['status'] = $updateStatus;
        $responses ['viewers'] = $updateViewers;


        $responses ['details'] = true;

        if ($updateViewers){

            if (!str_contains($updateViewers,$registrantCode)){
                $updateUpdateViewers = explode(', ',$updateViewers);
                array_push($updateUpdateViewers,$registrantCode);
                $updateUpdateViewers = implode(', ',$updateUpdateViewers);
            }

            if (str_contains($updateViewers,$registrantCode)){
                $updateUpdateViewers = $updateViewers;
            }
           
        } else {
             $updateUpdateViewers = $registrantCode;
        }
       


        if ($updateStatus == 'Published'){
            $sqlUpdateUpdateViewers = "UPDATE updates 
                SET updateViewers =?
                WHERE updateId = '$updateId'";

            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt,$sqlUpdateUpdateViewers);

            if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt,"s",$updateUpdateViewers);
                mysqli_stmt_execute($stmt);

            }

        }


         







    } else {
        $error = 'The update you look for is not found!';
        array_push($responses ['error'], $error);
         $responses ['details'] = false;
    }


    
 if ($responses) {
      header('Content-Type: application/json');
      $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
      echo  $jsonResponses;
   }

    
}