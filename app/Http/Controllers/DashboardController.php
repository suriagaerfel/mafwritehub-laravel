<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;









class DashboardController extends Controller
{   
     

    public function get_profile (Request $request){
        if ($request->input('get_profile_submit')) {
            $conn= config('app.conn');

            $registrantId= session('user_id');

            $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
            $stmt->execute([$registrantId]);
            $profile= $stmt ->fetch();

        if ($profile){
            $profileDescription = $profile ['description'];
            $profileFirstName = $profile ['first_name'];
            $profileMiddleName = $profile ['middle_name'];
            $profileLastName = $profile ['last_name'];
            $profileEmailAddress = $profile ['email_address'];
            $profileUsername = $profile ['username'];
            $profileAccountType = $profile ['type'];

            $responses = [];
            $responses ['profile-description'] = $profileDescription;
            $responses ['profile-first-name'] = $profileFirstName;
            $responses ['profile-middle-name'] = $profileMiddleName;
            $responses ['profile-last-name'] = $profileLastName;
            $responses ['profile-email-address'] = $profileEmailAddress;
            $responses ['profile-username'] = $profileUsername;
            $responses ['profile-account-type'] = $profileAccountType;


            if ($responses) {
            header('Content-Type: application/json');
            $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
            echo  $jsonResponses;
            } else {
                echo '';
            }

        }
   
}
    }

        public function get_authors (Request $request){
        if ($request->input('get_authors_submit')){
        $conn= config('app.conn');

        echo "<option selected hidden>Select Author</option>";
        


        $stmt= $conn->prepare("SELECT * FROM users");
        $stmt->execute();
        $count=$stmt->rowCount();

        if ($count>0) {
            while ($users = $stmt->fetch()) {
                $name = $users ['name'];
                echo "<option>$name</option>";     
            }

        }

    }
}




public function get_articles (){
        if (isset($_POST['get_articles_submit'])) {

        $conn= config('app.conn');

        $userId = htmlspecialchars($_POST ['userid']);

        $author = htmlspecialchars($_POST['author']);


        if ($author) {
            $stmt= $conn->prepare("SELECT * FROM users WHERE name = ?");
            $stmt->execute([$author]);
            $authorDetails= $stmt->fetch();

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


        // $sqlcountResult = mysqli_query($conn, $sqlCount);
        // $rows = mysqli_fetch_assoc($sqlcountResult)['total'];
        // $pages = ceil($rows/$limit);

        $stmt= $conn->prepare($sqlCount);
        $stmt->execute();
        $rows=$stmt->fetch()['total'];
        $pages = ceil($rows/$limit);

        $sqlGet = '';
        $sqlGet = "SELECT * FROM articles WHERE writer_id = $userId ORDER BY drafted DESC LIMIT $offset,$limit";


        
        if ($query) {
            $sqlGet = "SELECT * FROM articles WHERE writer_id = $userId AND title LIKE '%$query%' ORDER BY drafted DESC LIMIT $limit";
        }


        // $sqlArticlesList = $sqlGet;
        // $sqlArticlesListResult= mysqli_query($conn,$sqlArticlesList);

        $stmt= $conn->prepare($sqlGet);
        $stmt->execute();
        $count= $stmt->rowCount();


        echo "<input id='article-rows' value=$rows hidden>";
        echo "<input id='article-pages' value=$pages hidden>";
        echo "<input id='article-current-page' value=$currentPage hidden>";

        if ($count>0) { 

            while($articles = $stmt->fetch()){ 
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

}



public function get_article (Request $request){
    if ($request->input('get_article_submit')){
        $conn= config('app.conn');

        $articleId = htmlspecialchars($_POST['article_id']);

        $stmt= $conn->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$articleId]);
        $article= $stmt->fetch();

        $responses = [];

        if ($article) {

            $articleTitle = $article ['title'];
            $articleCategory = $article ['category'];
            $articleTags = $article ['tags'];
            $articleVersion = $article ['version'];


            $stmt= $conn->prepare("SELECT * FROM article_versions WHERE article_id = ? AND version=?");
            $stmt->execute([$articleId,$articleVersion]);
            $versionRecords= $stmt->fetch();
           
            if ($versionRecords) {
                    $articleBody= $versionRecords ['version_body'];
            } else {
                $articleBody= '';
            }
           
            $articleStatus = $article ['status'];

            $responses ['article-id'] = $articleId;
            $responses ['article-title'] = $articleTitle;
            $responses ['article-category'] = $articleCategory;
            $responses ['article-tags'] = $articleTags;
            $responses ['article-version'] = $articleVersion;
            $responses ['article-body'] = $articleBody;
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
}


public function get_version_body (Request $request){
    if ($request->input('get_version_body_submit')){

        $conn= config('app.conn');

        $articleVersion = htmlspecialchars($_POST['article_version']);
        $articleId = htmlspecialchars($_POST['article_id']);



        $stmt= $conn->prepare("SELECT * FROM article_versions WHERE article_id = ? AND version = ?");
        $stmt->execute([$articleId,$articleVersion]);
        $articleVersion = $stmt->fetch();

        if ($articleVersion) {
            $versionBody = $articleVersion ['version_body'];
        } else {
            $versionBody = '';
        }

        echo $versionBody;

}
}


public function get_article_image (Request $request){
    if ($request->input('get_article_image_submit')) {
    $conn= config('app.conn');

   $articleId= htmlspecialchars(($_POST['article_id']));

   $stmt = $conn->prepare("SELECT * FROM articles WHERE id = ? LIMIT 1");
   $stmt->execute([$articleId]);
   $article = $stmt->fetch();

   if ($article) {
      $articleImage = $article ['image'];

      echo $articleImage;
   } else {
      echo '';
   }

   
}
}


public function get_article_categories (Request $request){
    if ($request->input('get_article_categories_submit')) {
        $conn= config('app.conn');

        $mode = htmlspecialchars ($_POST['mode']);
        $originalCategory = htmlspecialchars ($_POST['original_category']);
        $selectedCategory = htmlspecialchars ($_POST['selected_category']);

        if ($selectedCategory) {
                   
                $stmt= $conn->prepare("SELECT * FROM article_categories WHERE name=?");
                $stmt->execute([$selectedCategory]);
                $checkedCategory = $stmt->fetch();


            
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
        
      
        $stmt= $conn->prepare("SELECT * FROM article_categories");
        $stmt->execute();
        $count= $stmt->rowCount();

        if ($count>0) {
        
            while ($categories = $stmt->fetch()) {
            $category = $categories ['name'];
  
            echo "<option class='category-option' value=$category>$category</option>";
          
            }

        }
            
        
        

        }
}



public function get_article_tags (Request $request){
    if ($request->input('get_article_tags_submit')) {
        $conn= config('app.conn');

        $selectedTags= htmlspecialchars($_POST['selected_tags']);


        $stmt= $conn->prepare("SELECT * FROM article_tags");
        $stmt->execute();
        $count= $stmt->rowCount();

        if ($count>0) {
        
            while ($tag = $stmt->fetch()) {
            $tagName = $tag ['name'];

                if (!$selectedTags) {
                    echo "<span class='".'not-selected link-tag-button'."' value='".$tagName."' style='margin-bottom:3px;'>$tagName</span>";
                }
                 
                  if ($selectedTags){
                    if (str_contains($selectedTags,$tagName)) {
                         echo "<span class='".'selected link-tag-button'."' value='".$tagName."' style='margin-bottom:3px;'>$tagName</span>";
                    }

                     if (!str_contains($selectedTags,$tagName)) {
                         echo "<span class='".'not-selected link-tag-button'."' value='".$tagName."' style='margin-bottom:3px;'>$tagName</span>";
                    }
                  }


            }

        } else {
            echo "No tag";
        }
            
        
        

        }
}




public function get_article_categories_settings (Request $request){
    if ($request->input('get_article_categories_settings_submit')) {
        $conn= config('app.conn');

       
        $stmt= $conn->prepare("SELECT * FROM article_categories");
        $stmt->execute();
        $count= $stmt->rowCount();

        if ($count>0) {
        
            while ($categories = $stmt->fetch()) {
            $category = $categories ['name'];
  
            echo "<span class='link-tag-button' style='margin-bottom:3px;'>$category</span>";
          
            }
        } else {
            echo "<small>No category yet</small>";
        }    
        
        

        }
}



public function get_article_tags_settings (Request $request){
    if ($request->input('get_article_tags_settings_submit')) {
        $conn= config('app.conn');

       
        $stmt= $conn->prepare("SELECT * FROM article_tags");
        $stmt->execute();
        $count= $stmt->rowCount();

        if ($count>0) {
        
            while ($tags = $stmt->fetch()) {
            $tag = $tags ['name'];
  
            echo "<span class='link-tag-button' style='margin-bottom:3px;'>$tag</span>";
          
            }
        } else {
            echo "<small>No tag yet</small>";
        }    
        
        

        }
}




public function get_article_topics (Request $request){
    if ($request->input('get_article_topics_submit')) {

        $conn= config('app.conn');

        $mode = htmlspecialchars ($_POST['mode']);
        $originalTopic = htmlspecialchars ($_POST['original_topic']);
        $selectedTopic = htmlspecialchars ($_POST['selected_topic']);

        

        if ($selectedTopic) {
                   
                    $stmt= $conn->prepare("SELECT * FROM article_topics WHERE name=?");
                    $stmt->execute([$selectedTopic]);
                    $checkedTopic = $stmt->fetch();

            
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
        
       
        $stmt= $conn->prepare("SELECT * FROM article_topics");
        $stmt->execute();
        $count= $stmt->rowCount();

        if ($count>0) {
        
            while ($topics = $stmt->fetch()) {
            $topic = $topics ['name'];

            
            echo "<option class='topic-option' value=$topic>$topic</option>";
           
            }

        }
            
        
        

        }
}














public function add_category (Request $request){
    if ($request->input('add_category_submit')) {
        $conn= config('app.conn');

        $newCategory = htmlspecialchars($_POST['new_category']);

        $stmt= $conn->prepare("SELECT * FROM article_categories WHERE name = ?");
        $stmt->execute([$newCategory]);
        $category= $stmt->fetch();

        if (!$category) {
             $stmt= $conn->prepare("INSERT INTO article_categories (name) VALUES (?)");
            $stmt->execute([$newCategory]);
        }
       
        }
}



public function add_tag (Request $request){
    if ($request->input('add_tag_submit')) {
        $conn= config('app.conn');

        $newTag = htmlspecialchars($_POST['new_tag']);

        $stmt= $conn->prepare("SELECT * FROM article_tags WHERE name = ?");
        $stmt->execute([$newTag]);
        $tag= $stmt->fetch();
        
        if (!$tag) {
             $stmt= $conn->prepare("INSERT INTO article_tags (name) VALUES (?)");
            $stmt->execute([$newTag]);
        }

       
        }
}




public function delete_category (Request $request){
        if ($request->input('delete_category_submit')) {
            $conn= config('app.conn');

            $deleteCategory = htmlspecialchars($_POST['delete_category']);
            $stmt = $conn->prepare("delete from article_categories where name=?");
            $stmt->execute([$deleteCategory]);
      
    }

}

public function delete_tag (Request $request){
        if ($request->input('delete_tag_submit')) {
            $conn= config('app.conn');

            $deleteTag = htmlspecialchars($_POST['delete_tag']);
            $stmt = $conn->prepare("delete from article_tags where name=?");
            $stmt->execute([$deleteTag]);
    }

}




public function get_article_versions (Request $request){
    if ($request->input('get_article_versions_submit')) {
        $conn=  config('app.conn');

        $articleId = htmlspecialchars($_POST['article_id']);

//    if ($articleId) {
   
//       $stmt= $conn->prepare("SELECT * FROM article_versions WHERE article_id = ? ORDER BY id DESC");
//       $stmt->execute([$articleId]);
//       $count = $stmt->rowCount();
      
//       if ($count>0) {
//          while($articleVersions =$stmt->fetch()) {
//             $articleVersion = $articleVersions ['version'];
//             echo "<option value=$articleVersion>Content V$articleVersion</option>";
//          }
//       } 
  
//    } else {
//       echo "<option value=0 disabled selected>No Version</option>";
//    }


    if ($articleId) {
    
        $stmt= $conn->prepare("SELECT * FROM article_versions WHERE article_id = ? ORDER BY id DESC");
        $stmt->execute([$articleId]);
        $count = $stmt->rowCount();
        
        if ($count>0) {
            echo "<span>Versions: </span>";
            while($articleVersions =$stmt->fetch()) {
                $articleVersion = $articleVersions ['version'];
                echo "<span value=$articleVersion class='link-tag-button' style='margin-bottom:3px;'>$articleVersion</span>";
            }
        } 
    
    } else {
        echo "<span>Version: No Version</span>";
    }
  

}
}


public function update_article_status (Request $request){
    if ($request->input('update_article_status_submit')) {
            $conn=config('app.conn');
            $currentTime= config('app.currentTime');

            $action = htmlspecialchars($_POST ['action']);
            $articleId = htmlspecialchars($_POST['article_id']);

            $stmt= $conn->prepare("SELECT * FROM articles WHERE id=?");
            $stmt->execute([$articleId]);

            $articleRecords = $stmt->fetch();

            if ($articleRecords) {
                $articlePubDate = $articleRecords ['published'];

                if ($articlePubDate != null) {
                    $articlePubDate = $articlePubDate;
                }

                if ($articlePubDate == null) {
                    $articlePubDate = date("Y-m-d H:i:s", $currentTime);
                }
            }


            if ($action=='publish') {
                $status = 'Published';
            }

            if ($action=='unpublish') {
                $status = 'Unpublished';
            }


            $stmt= $conn->prepare("UPDATE articles
                    SET status = ?,
                    published = ?
                    WHERE id = ?");
            
            $stmt =$stmt->execute([$status,$articlePubDate,$articleId]);

             echo "Successful";


            }

}


public function save_article (Request $request){

    if ($request->input('save_article_submit')){
    
    $conn= config('app.conn');
    $userId = session('user_id');

   $storageType = htmlspecialchars($_POST['storage_type']);
   $articleId = htmlspecialchars($_POST['article_id']);

   $articleTitle = htmlspecialchars($_POST['article_title']);
   $slug = generateSlug($articleTitle);
   $articleCategory = htmlspecialchars($_POST['article_category']);
   $articleTags = htmlspecialchars($_POST['article_tags']);

   $articleVersion = htmlspecialchars($_POST['article_version']);
   
   $articleBody = $_POST['article_body'];

    $responses=[];
      $responses['error'] = [];

   if ($storageType=='session'){
      if($articleId){
         $_SESSION ["article-{$articleId}-title"] = $articleTitle;
         $_SESSION ["article-{$articleId}-category"] = $articleCategory;
         $_SESSION ["article-{$articleId}-tags"] = $articleTags;
         $_SESSION ["article-{$articleId}-version"] = $articleVersion;
         $_SESSION ["article-{$articleId}-body"] = $articleBody;
      }

       if(!$articleId){
         $_SESSION ["article-title"] = $articleTitle;
         $_SESSION ["article-category"] = $articleCategory;
         $_SESSION ["article-tags"] = $articleTags;
         $_SESSION ["article-version"] = $articleVersion;
         $_SESSION ["article-body"] = $articleBody;
      }
   }


    if ($storageType=='db'){
    
      if (!$articleTitle) {

         $error = 'Please enter the title.';

         array_push($responses['error'],$error); 
      
      }

      if (!$articleCategory) {
      $error = 'Please select a category.';
        array_push($responses['error'],$error); 
     
       
      }

      if (!$articleTags) {
         $error = 'Please add at least one tag.';
        array_push($responses['error'],$error); 
    
        
      }

       if (!$articleBody) {
         $error = 'Article body must not be empty.';
        array_push($responses['error'],$error); 
    
        
      }



      //Check if the title already exists
        
         $stmt= $conn->prepare("SELECT * FROM articles WHERE title=?");
         $stmt->execute([$articleTitle]);
         $articleRecord = $stmt->fetch();

      if ($articleRecord){
         $article_record_writer_id = $articleRecord ['writer_id'];

         if ($article_record_writer_id != $userId) {
            $error = 'An article with the same title already exists.';
            array_push($responses['error'],$error); 
 
         
         }
         
      }


      if (!$responses['error']) {
         
            if($articleId){

            $stmt= $conn->prepare("SELECT * FROM articles WHERE id=?");
            $stmt->execute([$articleId]);
            $articleRecord= $stmt->fetch();
          

            if ($articleRecord){
            $latestVersion = (int) $articleRecord['version'];
            $newVersion =  $latestVersion + 1;

            } 

               $stmt = $conn->prepare("UPDATE articles 
                                    SET title=?,
                                       category=?,
                                       tags=?,
                                       version=?
                                       WHERE id = ?");
                $stmt->execute([$articleTitle,$articleCategory,$articleTags,$newVersion,$articleId]);

                $update_articleId = $articleId;
             
            }

            if(!$articleId){
                  $newVersion = 1;

                  $stmt = $conn->prepare("INSERT INTO articles (title,slug,category,tags,writer_id,version) VALUES(?,?,?,?,?,?)");
                  $stmt->execute([$articleTitle,$slug,$articleCategory,$articleTags,$userId,$newVersion]);

                   $update_articleId =  $conn->lastInsertId();

            }


            $stmt = $conn->prepare("INSERT INTO article_versions (article_id,version,version_body) VALUES(?,?,?)");
            $stmt->execute([$update_articleId,$newVersion,$articleBody]);

         
            $responses ['status'] = 'Successful';
            $responses ['article-id'] = $update_articleId;

             array_push($responses,$responses['status']);
             array_push($responses,$responses ['article-id']);
           
      } else {
       
            $responses ['status'] = 'Unsuccessful';
      }


       
   }
      
      if ($responses) {
      header('Content-Type: application/json');
      $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
      echo  $jsonResponses;
   } 

}
}


public function delete_article (Request $request){
    if ($request->input('delete_submit')) {

    $conn= config('app.conn');

    $deleteId = htmlspecialchars($_POST['id']);
    $table = 'articles';
    $column = 'id';
    $imageCol = 'image';


    $stmt = $conn->prepare("SELECT $imageCol FROM $table WHERE $column=?");
    $stmt->execute([$deleteId]);
    $imageLink= $stmt->fetch();

    if ($imageLink) {
        $imageLinkDelete = public_path($imageLink [$imageCol]);
        $imageDeleted= unlink($imageLinkDelete);
    }

   $stmt=$conn->prepare("delete from articles where id =  ?");
   $stmt->execute([$deleteId]);

   $stmt = $conn->prepare("delete from article_content_versions where article_id = ?");
   $stmt->execute([$deleteId]);

   echo 'Successful';
 

}
}



public function update_profile (Request $request){
    if ($request->input('update_profile_submit')) {
    
    $conn= config('app.conn');
    $registrantId = session ('user_id');

   $profileFirstName = htmlspecialchars($_POST['profile_first_name']);
   $profileMiddleName = htmlspecialchars($_POST['profile_middle_name']);
   $profileLastName = htmlspecialchars($_POST['profile_last_name']);
   $profileEmailAddress = htmlspecialchars($_POST['profile_email_address']);
   $profileUsername= htmlspecialchars($_POST['profile_username']);
   $profileAccountType = htmlspecialchars($_POST['profile_account_type']);

   $letterOnlyPattern ='/^[a-zA-Z ]+$/';

   $responses = [];
   $responses['error'] = [];
   

   if (!$profileFirstName) {
      $error = 'Please enter first name.';
      array_push($responses['error'],$error);
   } else {
       if (!preg_match($letterOnlyPattern,$profileFirstName)) {
        $error = 'First name is not valid.';
         array_push($responses['error'],$error);
        }
   }


   if (!$profileLastName) {
      $error = 'Please enter last name.';
         array_push($responses['error'],$error);
   } else {
       if (!preg_match($letterOnlyPattern,$profileLastName)) {
         $error = 'Last name is not valid';
         array_push($responses['error'],$error);
        }
   }


   if (!$profileEmailAddress) {
      $error = 'Please enter email address.';
      array_push($responses['error'],$error);
   } else {
       if (!filter_var($profileEmailAddress, FILTER_VALIDATE_EMAIL)) { 
       $error = 'Email address is not valid.';
     array_push($responses['error'],$error);
      }else {
         
            $stmt =$conn->prepare( "SELECT * FROM users WHERE email_address = ?");
            $stmt->execute([$profileEmailAddress]);
            $userEmailAddress = $stmt->fetch();

            if ($userEmailAddress) { 
               $userEmailAddress_Id = $userEmailAddress ['id'];

               if ($registrantId !== $userEmailAddress_Id){
                  $error = 'Email address is already used.';
                  array_push($responses['error'],$error);
               }
            }
       
        
    }
   }


   if (!$profileUsername) {
      $error = 'Please enter username.';
      array_push($responses['error'],$error);
      } else {
       $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
       $stmt->execute([$profileUsername]);
      $userUsername = $stmt->fetch();

    

    if ($userUsername) {
         $userUsername_Id = $userUsername ['id'];

         if ($registrantId != $userUsername_Id) {
            $error = 'Username is already used.';
            array_push($responses['error'],$error);
         }
         }
       
       
   }



      if (!$profileAccountType) {
      $error = 'Please select type.';
      array_push($responses['error'],$error);
      } 


     if (!$responses['error']) {

    

      $stmt=$conn->prepare("UPDATE users
                            SET 
                            first_name=?,
                            middle_name=?,
                            last_name=?,
                            email_address=?,
                           username=?,
                            type=?
                            WHERE id = ?");
        $stmt->execute([$profileFirstName, $profileMiddleName,$profileLastName,$profileEmailAddress,$profileUsername,$profileAccountType,$registrantId]);
         $responses['status'] = 'Successful'; 

     } else {
         $responses['status'] = 'Unsuccessful'; 
      }
   
      if ($responses) {
            header('Content-Type: application/json');
            $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
            echo  $jsonResponses;
      }

  
}
}

public function get_users (Request $request){
  
    if ($request->input('get_users_submit')) {
    
    $conn= config('app.conn');
    $registrantId = session('user_id');

    $query = htmlspecialchars($_POST ['query']);  
    
    $limit = 5;
    $currentPage = isset($_POST ['page'])? (int) $_POST ['page'] : 1;

    if ($query) {
        $currentPage = 1;
    }
    $offset = ($currentPage - 1) * $limit;

    
    
    $sqlCount = "SELECT COUNT(*) as total FROM users WHERE id !=$registrantId";

    if ($query) {
        $sqlCount = "SELECT COUNT(*) as total FROM users WHERE name LIKE '%$query%' AND id !=$registrantId";
    }


    $stmt= $conn->prepare($sqlCount);
    $stmt->execute();

    $rows = $stmt->fetch()['total'];
    $pages = ceil($rows/$limit);



    $sqlGet = "SELECT * FROM users WHERE id !=$registrantId ORDER BY name ASC LIMIT  $offset,$limit";
    
    if ($query) {
        $sqlGet = "SELECT * FROM users WHERE name LIKE '%$query%' AND id !=$registrantId ORDER BY name ASC LIMIT $limit";
    }

    $stmt= $conn->prepare($sqlGet);
    $stmt->execute();
    $count= $stmt->rowCount();
   


    echo "<input id='user-rows' value=$rows hidden>";
    echo "<input id='user-pages' value=$pages hidden>";
    echo "<input id='user-current-page' value=$currentPage hidden>";

    if ($count>0) { 

    while($users= $stmt->fetch()){ 
            $userId = $users ['id'];
            $name = $users ['name'];
        
            $attributeId = 'user-'.$userId;
            $attributeClass = 'list-title';

            if ($userId != $registrantId) {
                    echo "<strong id='$attributeId' class='$attributeClass'>$name</strong>";
            echo '<hr>';
            }
            
            
    }  


    } else {
    echo '<small>No result</small>';
    
    }



            
    }



}


public function get_user(Request $request){
    if ($request->input('get_user_submit')){
        $conn= config('app.conn');
    $userId = htmlspecialchars($_POST['user_id']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    $responses = [];

    if ($user) {
        $responses ['user-first-name'] = $user['first_name'];
        $responses ['user-middle-name'] = $user['middle_name'];
        $responses ['user-last-name'] = $user['last_name'];
        $responses ['user-name'] = $user['name'];
        $responses ['user-email-address'] = $user['email_address'];
        $responses ['user-username'] = $user['username'];
        $responses ['user-type'] = $user['type'];
        $responses ['user-status'] = $user['status'];
    }


    if ($responses) {
        header('Content-Type: application/json');
        $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
        echo  $jsonResponses;
    } else {
        echo '';
    }
    }
}


public function save_user (Request $request){
    if($request->input('save_user_submit')){

        $conn= config('app.conn');

        $userId = htmlspecialchars($_POST['user_id']);
        $firstName = htmlspecialchars($_POST['user_first_name']);
        $lastName = htmlspecialchars($_POST['user_last_name']);

        $name=trim($firstName.' '.$lastName);
        $emailAddress = htmlspecialchars($_POST['user_email_address']);
        $username = htmlspecialchars($_POST['user_username']);
        $type = htmlspecialchars($_POST['user_type']);

        $generatedPassword = password_hash($username, PASSWORD_DEFAULT);

        $status = htmlspecialchars($_POST['user_status']);

        $action = htmlspecialchars($_POST['save_action']);

        $letterOnlyPattern ='/^[a-zA-Z ]+$/';
        $responses = [];
        $responses ['error'] = [];
        



        if (!$firstName) {
            $error = 'Please enter first name.';
            array_push($responses ['error'],$error);
        } else {
            if (!preg_match($letterOnlyPattern,$firstName)) {
                $error = 'First name is not valid.';
                array_push($responses ['error'],$error);
                }
        }


        if (!$lastName) {
            $error= 'Please enter last name.';
            array_push($responses ['error'],$error);
        } else {
            if (!preg_match($letterOnlyPattern,$lastName)) {
            $error = 'Last name is not valid.';
                array_push($responses ['error'],$error);
                }
        }


        if (!$emailAddress) {
            $error = 'Please enter email address.';
            array_push($responses ['error'],$error);
        } else {
            if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) { 
            $error = 'Email address is not valid.';
            array_push($responses ['error'],$error);
            }else {
                
            if ($action=='Add') {
                    $stmt = $conn->prepare("SELECT * FROM users WHERE email_address = ?");
                    $stmt->execute([$emailAddress]);
                    $rowCountEmail = $stmt->rowCount();

                    if ($rowCountEmail>0) { 
                    $error = 'Email address is already added.';
                    array_push($responses ['error'],$error);
                    }
            }
                
            }
        }


        if (!$username) {
            $error = 'Please enter username.';
            array_push($responses ['error'],$error);
            } else {
            if ($action=='Add') {
                $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->execute([$username]);
                $rowCountUsername = $stmt->rowCount();

                if ($rowCountUsername>0) {
                        $error = 'Username is already added.';
                    array_push($responses ['error'],$error);
                    
                }
            }
            
        }




            if (!$type) {
            $error = 'Please select type.';
            array_push($responses ['error'],$error);
            } 


            if (!$status) {
            $error = 'Please select status.';
            array_push($responses ['error'],$error);
            } 
        





        


            if (!$responses['error']) {

            if ($action == 'Add') {

                $stmt = $conn->prepare("INSERT INTO users (first_name,last_name,name,email_address,username,password,type,status) VALUES (?,?,?,?,?,?,?,?)");
                $stmt->execute([$firstName,$lastName,$name,$emailAddress,$username,$generatedPassword,$type,$status]);
               
                $newUserId = $conn->lastInsertId();;

                $responses ['status'] = 'Successful';
                $responses ['user-id'] = $newUserId;
                $responses ['user-email-address'] = $emailAddress;

            }


            if ($action == 'Update') {

                $stmt= $conn->prepare("UPDATE users
                                    SET 
                                    type=?,
                                    status = ?
                                    WHERE id = ?");

                $stmt->execute([$type,$status,$userId]);
      
                $responses ['status'] = 'Successful';
                
            }

            
        } else {
                $responses['status'] = 'Unsuccessful'; 
        }
        
        
        
        
            if ($responses) {
                    header('Content-Type: application/json');
                    $jsonResponses = json_encode($responses,JSON_PRETTY_PRINT);
                    echo  $jsonResponses;
            }
            


        }

}


public function delete (Request $request){
    if ($request->input('delete_submit')) {
        $conn=config('app.conn');

        $deleteId = htmlspecialchars($_POST['id']);
        $deleteType = htmlspecialchars($_POST['type']);


        if ($deleteType=='user') {
            $table = 'users';
            $idColumn = 'id';
            $imageCol = 'profile_picture_link';
        }

        if ($deleteType=='article') {
            $table = 'articles';
            $idColumn = 'id';
            $imageCol = 'image';
        }
        

        $stmt = $conn->prepare("SELECT $imageCol FROM $table WHERE $idColumn=?");
        $stmt->execute([$deleteId]);
        $imageLink= $stmt->fetchColumn();

        if ($imageLink) {
            if ($deleteType=='user'){
                unlink(public_path('uploads/profile-pictures/'.$imageLink));
            }

             if ($deleteType=='article'){
                unlink(public_path('uploads/featured-images/'.$imageLink));
            }
            
        }

    
        if($deleteType=='user') {
            $stmt= $conn->prepare("delete from users where id =  $deleteId");
            $stmt->execute();

            $stmt= $conn->prepare("delete from user_logs where user_id = '$deleteId'");
            $stmt->execute();
        }

        if($deleteType=='article') {
            $stmt= $conn->prepare("delete from articles where id =  $deleteId");
            $stmt->execute();

            $stmt= $conn->prepare("delete from article_versions where article_id = '$deleteId'");
            $stmt->execute();
        }
        

        echo 'Successful';
    

    }
}
        
}

