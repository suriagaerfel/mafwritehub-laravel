<?php 
 require '../../initialize.php';
require '../../database.php';





if (isset($_POST['get_authors_submit'])){

   echo "<option selected hidden>Select Author</option>";
   
   $sqlUsers = "SELECT * FROM users";
   $sqlUsersResult = mysqli_query($conn,$sqlUsers);

   if ($sqlUsersResult->num_rows>0) {

      while ($users = $sqlUsersResult->fetch_assoc()) {
         $name = $users ['name'];

         echo "<option>$name</option>";
         
      }

   }

}




if (isset($_POST['get_articles_submit'])) {

   $userId = htmlspecialchars($_POST ['userid']);

   $author = htmlspecialchars($_POST['author']);



   if ($author) {
      $sqlAuthorDetails = "SELECT * FROM users WHERE name = '$author'";
      $sqlAuthorDetailsResult = mysqli_query($conn,$sqlAuthorDetails );
      $authorDetails = $sqlAuthorDetailsResult->fetch_assoc();

      if ($authorDetails) {
         $userId = $authorDetails ['id'];
      }
   }

   $query = htmlspecialchars($_POST ['query']);

   $sqlCount = '';
   

   $limit = 5;
  $currentPage = isset($_POST ['page'])? (int) $_POST ['page'] : 1;

  if ($query) {
   $currentPage = 1;
  }
  $offset = ($currentPage - 1) * $limit;


  
   $sqlCount = "SELECT COUNT(*) as total FROM articles WHERE writer_id = $userId";

   if ($query) {
      $sqlCount = "SELECT COUNT(*) as total FROM articles WHERE writer_id = $userId AND title LIKE '%$query%'";
   }


   $sqlcountResult = mysqli_query($conn, $sqlCount);
   $rows = mysqli_fetch_assoc($sqlcountResult)['total'];
   $pages = ceil($rows/$limit);



   $sqlGet = '';
   $sqlGet = "SELECT * FROM articles WHERE writer_id = $userId ORDER BY drafted DESC LIMIT $offset,$limit";


   
   if ($query) {
      $sqlGet = "SELECT * FROM articles WHERE writer_id = $userId AND title LIKE '%$query%' ORDER BY drafted DESC LIMIT $limit";
   }


$sqlArticlesList = $sqlGet;
$sqlArticlesListResult= mysqli_query($conn,$sqlArticlesList);


echo "<input id='article-rows' value=$rows hidden>";
echo "<input id='article-pages' value=$pages hidden>";
echo "<input id='article-current-page' value=$currentPage hidden>";

if ($sqlArticlesListResult->num_rows>0) { 

      while($articles = $sqlArticlesListResult->fetch_assoc()){ 
         $articleId = $articles ['id'];
         $articleTitle = $articles ['title'];
         $articleSlug = $articles ['slug'];

         $attributeId = 'article-'.$articleId;
         $attributeClass = 'list-title';


         echo "<strong id='$attributeId' class='$attributeClass'>$articleTitle</strong>";
         echo '<hr>';
          
   }
   
   


} else {
   echo '<small>No result</small>';
   
}



           
}




if (isset($_POST['get_article_submit'])){
   $articleId = htmlspecialchars($_POST['article_id']);

   $sqlArticle = "SELECT * FROM articles WHERE id = $articleId";
   $sqlArticleResult = mysqli_query($conn,$sqlArticle);
   $article = $sqlArticleResult->fetch_assoc();

   $responses = [];

   if ($article) {

      $articleTitle = $article ['title'];
      $articleCategory = $article ['category'];
      $articleTopic = $article ['topic'];
      $articleContentVersion = $article ['content_version'];

      $sqlGetVersionRecords = "SELECT * FROM article_content_versions WHERE article_id = $articleId AND content_version=$articleContentVersion";
      $sqlGetVersionRecordsResult = mysqli_query($conn,$sqlGetVersionRecords);
      $versionRecords = $sqlGetVersionRecordsResult->fetch_assoc();
      
      
      $articleContent = $versionRecords ['version_content'];
     
      $articleStatus = $article ['status'];

      $responses ['article-id'] = $articleId;
      $responses ['article-title'] = $articleTitle;
      $responses ['article-category'] = $articleCategory;
      $responses ['article-topic'] = $articleTopic;
      $responses ['article-content-version'] = $articleContentVersion;
      $responses ['article-content'] = $articleContent;
      $responses ['article-status'] = $articleStatus;
      
   } else {
     $responses ['error'] = 'Article not found';
   }


   if ($responses) {
      header('Content-Type: application/json');
      $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
      echo  $jsonResponses;
   } 
}



if (isset($_POST['get_version_content_submit'])){
   $articleContentVersion = htmlspecialchars($_POST['article_content_version']);
   $articleId = htmlspecialchars($_POST['article_id']);

   $sqlArticleVersion = "SELECT * FROM article_content_versions WHERE article_id = $articleId AND content_version = $articleContentVersion";
   $sqlArticleVersionResult = mysqli_query($conn,$sqlArticleVersion);
   $articleVersion = $sqlArticleVersionResult->fetch_assoc();

   if ($articleVersion) {
      $versionContent = $articleVersion ['version_content'];
   } else {
       $versionContent = '';
   }

   echo $versionContent;


}





if (isset($_POST['get_article_categories_submit'])) {

   $mode = htmlspecialchars ($_POST['mode']);
   $originalCategory = htmlspecialchars ($_POST['original_category']);
   $selectedCategory = htmlspecialchars ($_POST['selected_category']);

   

   if ($selectedCategory) {
            $sqlCheckCategory = "SELECT * FROM article_categories WHERE name='$selectedCategory'";
            $sqlCheckCategoryResult = mysqli_query($conn,$sqlCheckCategory);
            $checkedCategory = $sqlCheckCategoryResult->fetch_assoc();
      
      if ($selectedCategory !='Select') {

         if ($originalCategory) {
            if ($originalCategory == $selectedCategory) {
            
            

            if($checkedCategory) {
               echo "<option id='article-originally-selected-category' selected hidden class='category-option' value=$selectedCategory>$selectedCategory</option>";
            }
         
            if(!$checkedCategory) {
               echo "<option id='article-originally-selected-category' selected hidden class='category-option' value=$selectedCategory>$selectedCategory [Deleted]</option>";
            }

            }

            if ($originalCategory != $selectedCategory) {

                  if ($selectedCategory =='Add') {
                         if($checkedCategory) {
                              echo "<option id='article-originally-selected-category' selected hidden class='category-option' value=$originalCategory>$originalCategory</option>";
                           }
                        
                           if(!$checkedCategory) {
                              echo "<option id='article-originally-selected-category' selected hidden class='category-option' value=$originalCategory>$originalCategory [Deleted]</option>";
                           }
                        
                  }

                  if ($selectedCategory !='Add') {
                        echo "<option id='article-originally-selected-category' selected hidden class='category-option' value=$selectedCategory>$selectedCategory</option>";
                  }
                  
               
            }
         
         }

         if (!$originalCategory) {
            echo "<option id='article-originally-selected-category' selected hidden class='category-option' value=$selectedCategory>$selectedCategory</option>";
         }

      }

   }

   if(!$selectedCategory) {
      echo "<option id='article-originally-selected-category' selected hidden class='category-option' value=''>Select Category</option>";
   }
   
   
   $sqlCategories = "SELECT * FROM article_categories";
   $sqlCategoriesResult = mysqli_query($conn,$sqlCategories);

   if ($sqlCategoriesResult->num_rows>0) {
   
      while ($categories = $sqlCategoriesResult->fetch_assoc()) {
      $category = $categories ['name'];

     
      echo "<option class='category-option' value=$category>$category</option>";
    
      


      }

   }
      
  
   echo "<option class='category-option' value='Add'>Add Category</option>";

}







if (isset($_POST['get_article_topics_submit'])) {

   $mode = htmlspecialchars ($_POST['mode']);
   $originalTopic = htmlspecialchars ($_POST['original_topic']);
   $selectedTopic = htmlspecialchars ($_POST['selected_topic']);

   

   if ($selectedTopic) {
            $sqlCheckTopic = "SELECT * FROM article_topics WHERE name='$selectedTopic'";
            $sqlCheckTopicResult = mysqli_query($conn,$sqlCheckTopic);
            $checkedTopic = $sqlCheckTopicResult->fetch_assoc();
      
      if ($selectedTopic !='Select') {

         if ($originalTopic) {
            if ($originalTopic == $selectedTopic) {
         
               if($checkedTopic) {
                  echo "<option id='article-originally-selected-topic' selected hidden class='topic-option' value=$selectedTopic>$selectedTopic</option>";
               }
            
               if(!$checkedTopic) {
                  echo "<option id='article-originally-selected-topic' selected hidden class='topic-option' value=$selectedTopic>$selectedTopic [Deleted]</option>";
               }

            }

            if ($originalTopic != $selectedTopic) {

                  if ($selectedTopic =='Add') {
                         if($checkedTopic) {
                              echo "<option id='article-originally-selected-topic' selected hidden class='topic-option' value=$originalTopic>$originalTopic</option>";
                           }
                        
                           if(!$checkedTopic) {
                              echo "<option id='article-originally-selected-topic' selected hidden class='topic-option' value=$originalTopic>$originalTopic [Deleted]</option>";
                           }
                        
                  }

                  if ($selectedTopic !='Add') {
                        echo "<option id='article-originally-selected-topic' selected hidden class='topic-option' value=$selectedTopic>$selectedTopic</option>";
                  }
                  
               
            }
         
         }

         if (!$originalTopic) {
            echo "<option id='article-originally-selected-topic' selected hidden class='topic-option' value=$selectedTopic>$selectedTopic</option>";
         }

      }

   }

   if(!$selectedTopic) {
      echo "<option id='article-originally-selected-topic' selected hidden class='topic-option' value=''>Select Topic</option>";
   }
   
   
   $sqlTopics = "SELECT * FROM article_topics";
   $sqlTopicsResult = mysqli_query($conn,$sqlTopics);

   if ($sqlTopicsResult->num_rows>0) {
   
      while ($topics = $sqlTopicsResult->fetch_assoc()) {
      $topic = $topics ['name'];

     
      echo "<option class='topic-option' value=$topic>$topic</option>";
    
      


      }

   }
      
  
   echo "<option class='topic-option' value='Add'>Add Topic</option>";

}




if (isset($_POST['add_category_submit'])) {
   $newCategory = htmlspecialchars($_POST['new_category']);

   $sqlInsertCategory = "INSERT INTO article_categories (name) VALUES (?)";
   $stmt = mysqli_stmt_init($conn);

   $prepareStmt = mysqli_stmt_prepare($stmt,$sqlInsertCategory);

      if ($prepareStmt) {
         mysqli_stmt_bind_param($stmt,"s", $newCategory);
         mysqli_stmt_execute($stmt);

         echo 'Category added';
      }
}



if (isset($_POST['add_topic_submit'])) {
   $newTopic = htmlspecialchars($_POST['new_topic']);

   $sqlInsertTopic = "INSERT INTO article_topics(name) VALUES (?)";
   $stmt = mysqli_stmt_init($conn);

   $prepareStmt = mysqli_stmt_prepare($stmt,$sqlInsertTopic);

      if ($prepareStmt) {
         mysqli_stmt_bind_param($stmt,"s", $newTopic);
         mysqli_stmt_execute($stmt);

         echo 'Topic added';
      }
}



if (isset($_POST['delete_category_submit'])) {

   $deleteCategory = htmlspecialchars($_POST['delete_category']);
   $sqlDeleteCategory = mysqli_query($conn,"delete from article_categories where name='$deleteCategory'");
      
}


if (isset($_POST['delete_topic_submit'])) {

   $deleteTopic = htmlspecialchars($_POST['delete_topic']);
   $sqlDeleteTopic = mysqli_query($conn,"delete from article_topics where name='$deleteTopic'");
      
}





if (isset($_POST['get_article_content_versions_submit'])) {

   $articleId = htmlspecialchars($_POST['article_id']);
   if ($articleId) {
      $sqlArticleVersions = "SELECT * FROM article_content_versions WHERE article_id = $articleId ORDER BY id DESC";
      $sqlArticleVersionsResult = mysqli_query($conn,$sqlArticleVersions);
      
      if ($sqlArticleVersionsResult->num_rows>0) {
         while($articleVersions =$sqlArticleVersionsResult->fetch_assoc()) {
            $articleVersion = $articleVersions ['content_version'];
            echo "<option value=$articleVersion>Content V$articleVersion</option>";
         }
      } 
  
   } else {
      echo "<option value=0 disabled selected>No Version</option>";
   }
  

}


if (isset($_POST['update_article_status_submit'])) {
   $action = htmlspecialchars($_POST ['action']);
   $articleId = htmlspecialchars($_POST['article_id']);

   $sqlArticleRecords = "SELECT * FROM articles WHERE id= $articleId";
   $sqlArticleRecordsResult = mysqli_query($conn,$sqlArticleRecords);
   $articleRecords = $sqlArticleRecordsResult->fetch_assoc();

   if ($articleRecords) {
      $articlePubDate = $articleRecords ['published'];

      if ($articlePubDate != '0000-00-00 00:00:00') {
         $articlePubDate = $articlePubDate;
      }

      if ($articlePubDate == '0000-00-00 00:00:00') {
         $articlePubDate = date("Y-m-d H:i:s", $currentTime);
      }
   }


   if ($action=='publish') {
      $status = 'Published';
   }

   if ($action=='unpublish') {
      $status = 'Unpublished';
   }


   $sqlUpdateArticleStatus = "UPDATE articles
                              SET status = ?,
                              published = ?
                              WHERE id = $articleId";
   
   $stmt = mysqli_stmt_init($conn);

   $prepareStmt = mysqli_stmt_prepare($stmt,$sqlUpdateArticleStatus);

   if ($prepareStmt) {
      mysqli_stmt_bind_param($stmt,"ss", $status,$articlePubDate);
      mysqli_stmt_execute($stmt);

      echo "Successful";
   }  

}




if (isset($_POST['article_submit'])){
   $storageType = htmlspecialchars($_POST['storage_type']);
   $articleMode = htmlspecialchars($_POST['article_mode']);
   $articleId = htmlspecialchars($_POST['article_id']);

   $articleTitle = htmlspecialchars($_POST['article_title']);
   $slug = generateSlug($articleTitle);
   $articleCategory = htmlspecialchars($_POST['article_category']);
   $articleTopic = htmlspecialchars($_POST['article_topic']);

   $articleContentVersion = htmlspecialchars($_POST['article_content_version']);
   
  

   $articleContent = $_POST['article_content'];



   if ($storageType=='session'){
      if($articleId){
         $_SESSION ["article-{$articleId}-title"] = $articleTitle;
         $_SESSION ["article-{$articleId}-category"] = $articleCategory;
         $_SESSION ["article-{$articleId}-topic"] = $articleTopic;
         $_SESSION ["article-{$articleId}-content-version"] = $articleContentVersion;
         $_SESSION ["article-{$articleId}-content"] = $articleContent;
      }

       if(!$articleId){
         $_SESSION ["article-title"] = $articleTitle;
         $_SESSION ["article-category"] = $articleCategory;
         $_SESSION ["article--topic"] = $articleTopic;
         $_SESSION ["article-content-version"] = $articleContentVersion;
         $_SESSION ["article-content"] = $articleContent;
      }
   }


    if ($storageType=='database'){
    
      $responses=[];
      $articleErrors = [];

      if (!$articleTitle) {

         $error = 'Please enter the title.';

         array_push($articleErrors,$error); 
         array_push($responses,$articleErrors); 
      


      }

      if (!$articleCategory) {
      $error = 'Please select a category.';
        array_push($articleErrors,$error); 
         array_push($responses,$articleErrors); 
       
      }

      if (!$articleTopic) {
         $error = 'Please select a topic';
        array_push($articleErrors,$error); 
         array_push($responses,$articleErrors);  
        
      }


      //Check if the title already exists
      $sqlCheckArticleRecord = "SELECT * FROM articles WHERE title='$articleTitle'";
         $sqlCheckArticleRecordResult = mysqli_query($conn,$sqlCheckArticleRecord);
         $articleRecord = $sqlCheckArticleRecordResult->fetch_assoc();

      if ($articleRecord){
         $article_record_writer_id = $articleRecord ['writer_id'];

         if ($article_record_writer_id != $registrantId) {
            $error = 'An article with the same title already exists.';
           array_push($articleErrors,$error); 
         array_push($responses,$articleErrors);  
         
         }
         
      }


      if (!$articleErrors) {
         
            if($articleId){

            $sqlCheckArticleRecord = "SELECT * FROM articles WHERE id=$articleId";
            $sqlCheckArticleRecordResult = mysqli_query($conn,$sqlCheckArticleRecord);
            $articleRecord = $sqlCheckArticleRecordResult->fetch_assoc();

            if ($articleRecord){
            $latestVersion = (int) $articleRecord['content_version'];
            $newVersion =  $latestVersion + 1;

            } 

               $sqlUpdateArticle = "UPDATE articles 
                                    SET title=?,
                                       category=?,
                                       topic=?,
                                       content_version=?
                                       WHERE id = $articleId";

               $stmt = mysqli_stmt_init($conn);

               $prepareStmt = mysqli_stmt_prepare($stmt,$sqlUpdateArticle);

               if ($prepareStmt) {
                  mysqli_stmt_bind_param($stmt,"ssss", $articleTitle,$articleCategory,$articleTopic,$newVersion);
                  mysqli_stmt_execute($stmt);

                  $update_articleId = $articleId;

               }     

               
         
            }

            if(!$articleId){
                  $newVersion = 1;

                  $sqlAddArticle = "INSERT INTO articles (title,slug,category,topic,writer_id,content_version) VALUES(?,?,?,?,?,?)";

                  $stmt = mysqli_stmt_init($conn);

                  $prepareStmt = mysqli_stmt_prepare($stmt,$sqlAddArticle);

                  if ($prepareStmt) {
                     mysqli_stmt_bind_param($stmt,"ssssss", $articleTitle,$slug,$articleCategory,$articleTopic,$registrantId,$newVersion);
                     mysqli_stmt_execute($stmt);

                     $update_articleId = mysqli_insert_id($conn);


                  }

                  
         
            }


          
               $sqlInsertVersionContent =  "INSERT INTO article_content_versions (article_id,content_version,version_content) VALUES(?,?,?)";

               $stmt = mysqli_stmt_init($conn);

               $prepareStmt = mysqli_stmt_prepare($stmt,$sqlInsertVersionContent);

               if ($prepareStmt) {
                  mysqli_stmt_bind_param($stmt,"sss", $update_articleId,$newVersion,$articleContent);
                  mysqli_stmt_execute($stmt);
                  
               }

         
               $responses ['status'] = 'Successful';
               $responses ['article-id'] = $update_articleId;

             array_push($responses,$responses['status']);
             array_push($responses,$responses ['article-id']);
           
      } else {
            $responses ['errors'] = $articleErrors;
            $responses ['status'] = 'Unsuccessful';
            array_push($responses,$responses['status']);
            array_push($responses,$responses['errors']);

      }


       
   }
      
      if ($responses) {
      header('Content-Type: application/json');
      $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
      echo  $jsonResponses;
   } else {
      echo '';
   }

}


if (isset($_POST['delete_submit'])) {
    $deleteId = htmlspecialchars($_POST['id']);

    $table = 'articles';
    $column = 'id';
    $imageCol = 'image';

    $getImageLink = "SELECT $imageCol FROM $table WHERE $column='$deleteId'";
    $getImageLinkResult = mysqli_query($conn,$getImageLink);
    $imageLink= $getImageLinkResult->fetch_assoc();

    if ($imageLink) {
        $imageLinkDelete = '../../'.$imageLink [$imageCol];
        $imageDeleted= unlink($imageLinkDelete);
    }

   
   $sqlDeleteArticle = mysqli_query($conn,"delete from articles where id =  $deleteId");

   $sqlDeleteFromVersions = mysqli_query($conn,"delete from article_content_versions where article_id = '$deleteId'");

   echo 'Successful';
 

}





if (isset($_POST['get_article_image_submit'])) {
   $articleId= htmlspecialchars(($_POST['article_id']));

   $sqlArticle = "SELECT * FROM articles WHERE id = $articleId LIMIT 1";
   $sqlArticleResult = mysqli_query($conn,$sqlArticle);
   $article = $sqlArticleResult->fetch_assoc();

   if ($article) {
      $articleImage = $article ['image'];

      echo $articleImage;
   } else {
      echo '';
   }

   
}




if (isset($_POST['upload_image_submit'])) {
    
    $articleId = htmlspecialchars($_POST['content_hidden_id']);

    $imageFolder = '../../uploads/featured-images/';
    $imageLinkColumn = 'image';

    $allowedImage = ['jpeg','jpg'];
    $maxSize = 10 * 1024 * 1024;

    $imageFileName = '';
    $imageFileSize = '';
    $imageFileNameExt = '';
    $imageFileNameActualExt = '';

    $imageErrors = [];

    $responses = [];

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
         array_push($imageErrors,$error);
         array_push($responses,$error);
        }

        if($imageFileSize>$maxSize) {
        $error='Your image is too big in size.';
         array_push($imageErrors,$error);
         array_push($responses,$error);
        }
    } else {
        $error='You did not select an image.';
         array_push($imageErrors,$error);
         array_push($responses,$error);
    }
    


    if (!$imageErrors) {

        $sqlArticleData = "SELECT * FROM articles WHERE id = '$articleId'";
        $sqlArticleDataResult = mysqli_query($conn,$sqlArticleData);
        $articleData= $sqlArticleDataResult->fetch_assoc();

        $articleImageLink = $articleData [$imageLinkColumn];
    

        if ($articleImageLink) {
            $articleImageLinkDelete = '../..'.$articleImageLink;
            $articleImageLinkDeleted = unlink($articleImageLinkDelete);
        } 

    
        // Create folders if they don't exist
        if (!is_dir($imageFolder)) {
            mkdir($imageFolder, 0777, true);
        }

        $imageFile = $imageFolder .$articleId."-".date("YmdHis",time()).".".$imageFileNameActualExt;

        $uploadOk = 1;

        if (move_uploaded_file($image["tmp_name"], $imageFile)) {
            $uploadOk = 1;
        } 


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

    
        $newCropImage = imagecreatetruecolor($maxResolution,$maxResolution/1.5);
        imagecopyresampled($newCropImage,$newImage,0,0,$x,$y,$maxResolution,$maxResolution,$maxResolution,$maxResolution); 
    

        imagejpeg($newCropImage,$imageFile,90);
        }





        $uploadedImageFile= substr($imageFile,5);
        $imageStatus = 0;

        $sqlUpdateImage = "UPDATE articles
                            SET 
                            $imageLinkColumn=?
                            WHERE id = '$articleId'";


        $stmt = mysqli_stmt_init($conn);
        $prepareStmt = mysqli_stmt_prepare($stmt, $sqlUpdateImage);
        
        if ($prepareStmt) {
        mysqli_stmt_bind_param($stmt,"s", $uploadedImageFile);

        mysqli_stmt_execute($stmt);

    

        echo 'Upload Successful';
        

        }

        
        }  else {

            foreach ($responses as $response) {
            echo $response."<br>";
            }

        }





    }








if (isset($_POST['get_featured_categories_submit'])) {
    
    
    $sqlCategories = "SELECT * FROM article_categories";
    $sqlCategoriesResult = mysqli_query($conn,$sqlCategories);

    
   if ($sqlCategoriesResult->num_rows>0) {
             
    while ($categories = $sqlCategoriesResult->fetch_assoc()) {
    $category = $categories ['name'];
      if($category !='Administrative') {
            echo "<a class='navigation-button' href='$website/articles/categories/$category'>$category</a>";     
      }
        

    } 
    
    } 
}


if(isset($_POST['get_featured_articles_submit'])) {
   
   $articleCategory = htmlspecialchars($_POST['article_category']);
   $articleTag = htmlspecialchars($_POST['article_tag']);
   $articleDate = htmlspecialchars($_POST['article_date']);
   $articleWriter = htmlspecialchars($_POST['article_writer']);
   $articleSearch = htmlspecialchars($_POST['article_search']);
 
   $sqlFeaturedArticles = "SELECT * FROM articles WHERE status='Published'";

   if ($articleSearch) {
      $sqlFeaturedArticles = "SELECT * FROM articles WHERE status='Published' AND title LIKE '%$articleSearch%'";
   }


   if ($articleCategory) {
      $sqlFeaturedArticles = "SELECT * FROM articles WHERE status='Published' AND category ='$articleCategory'"; 

      if ($articleSearch) {
      $sqlFeaturedArticles = "SELECT * FROM articles WHERE status='Published' AND category ='$articleCategory' AND title LIKE '%$articleSearch%'";
      }
   }

   if ($articleWriter) {
      $sqlWriter = "SELECT * FROM users WHERE username = '$articleWriter'";
      $sqlWriterResult = mysqli_query($conn,$sqlWriter);
      $writer = $sqlWriterResult->fetch_assoc();

      if ($writer){
         $writerUserId = $writer ['id'];
      }
      $sqlFeaturedArticles = "SELECT * FROM articles WHERE status='Published' and  writer_id='$writerUserId'"; 

      if ($articleSearch) {
      $sqlFeaturedArticles = "SELECT * FROM articles WHERE status='Published' and  writer_id='$writerUserId' AND title LIKE '%$articleSearch%'";
      }
   }


   if ($articleTag) {
       $sqlFeaturedArticles = "SELECT * FROM articles WHERE status='Published' and tag ='$articleTag'"; 

       if ($articleSearch) {
      $sqlFeaturedArticles = "SELECT * FROM articles WHERE status='Published' and tag ='$articleTag' AND title LIKE '%$articleSearch%'";
      }
   }

   if ($articleDate) {
       $sqlFeaturedArticles = "SELECT * FROM articles WHERE status='Published' and published LIKE '%$articleDate%'"; 

        if ($articleSearch) {
      $sqlFeaturedArticles = "SELECT * FROM articles WHERE status='Published' and published LIKE '%$articleDate%' AND title LIKE '%$articleSearch%'";
      }
   }

   $sqlFeaturedArticlesResult = mysqli_query($conn,$sqlFeaturedArticles);

    if ($sqlFeaturedArticlesResult->num_rows>0) {
             
    while ($articles = $sqlFeaturedArticlesResult->fetch_assoc()) {
    $title = $articles ['title'];
    $category = $articles ['category'];
    $slug = $articles ['slug'];
    $image = $articles ['image'] ? $privateFolder.$articles ['image']: $website.'/assets/images/default-featured-image.jpg';
      if ($category !='Administrative') {
      echo "<div style='display:flex;flex-direction:column; width:400px; background-color:white;padding:20px;' class='featured-article-item'>";
         echo "<img src='$image' style='width:100%;'> "; 
         echo "<a class='' href='$website/articles/read/$slug'><strong>$title</strong></a>";      
      echo "</div>";

      }
      
    } 
    
    } 


}



if(isset($_POST['get_searched_articles_submit'])) {
   
   $articleCategory = htmlspecialchars($_POST['article_category']);
   $articleTag = htmlspecialchars($_POST['article_tag']);
   $articleWriter = htmlspecialchars($_POST['article_writer']);
   $articleDate = htmlspecialchars($_POST['article_date']);
   $query = htmlspecialchars($_POST['query']);
 
   if ($query){

   $sqlSearchedArticles = "SELECT * FROM articles WHERE status='Published' and category !='Administrative' AND title LIKE '%$query%' ORDER BY title ASC";

   if ($articleCategory) {
       $sqlSearchedArticles = "SELECT * FROM articles WHERE status='Published' and category ='$articleCategory' AND title LIKE '%$query%' ORDER BY title ASC";
   }


   if ($articleTag) {
       $sqlSearchedArticles = "SELECT * FROM articles WHERE status='Published' and tag ='$articleTag' AND title LIKE '%$query%' ORDER BY title ASC";
   }



   if ($articleWriter) {
      $sqlWriter = "SELECT * FROM users WHERE username = '$articleWriter'";
      $sqlWriterResult = mysqli_query($conn,$sqlWriter);
      $writer = $sqlWriterResult->fetch_assoc();

      if ($writer){
         $writerUserId = $writer ['id'];
      }

       $sqlSearchedArticles = "SELECT * FROM articles WHERE status='Published' and writer_id ='$writerUserId' AND title LIKE '%$query%' ORDER BY title ASC";
   }

   $sqlSearchedArticlesResult = mysqli_query($conn,$sqlSearchedArticles);

      if ($sqlSearchedArticlesResult->num_rows>0) {
               
      while ($searcheArticles = $sqlSearchedArticlesResult->fetch_assoc()) {
      $searchedTitle = $searcheArticles ['title'];
      $searchedCategory = $searcheArticles ['category'];
      $searchedSlug = $searcheArticles ['slug'];
      
      if ($searchedCategory !='Administrative') {
             echo "<a href='$website/articles/read/$searchedSlug'><strong>$searchedTitle</strong></a>"; 
      }
            
      } 
    
    } else {
      echo "<small>No result</small>";
    }

   }
}