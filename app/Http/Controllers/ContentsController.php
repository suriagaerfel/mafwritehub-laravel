<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use App\Models\Registration;
use App\Services\AccountRecordsService;
use Illuminate\Support\Facades\Route;

use App\Services\MailService;
use App\Services\FunctionsService;








class ContentsController extends Controller
{   
     

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
            $articleTopic = $article ['topic'];
            $articleContentVersion = $article ['content_version'];


            $stmt= $conn->prepare("SELECT * FROM article_content_versions WHERE article_id = ? AND content_version=?");
            $stmt->execute([$articleId,$articleContentVersion]);
            $versionRecords= $stmt->fetch();
           
            if ($versionRecords) {
                    $articleContent = $versionRecords ['version_content'];
            }
           
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
}


public function get_version_content (Request $request){
    if ($request->input('get_version_content_submit')){

        $conn= config('app.conn');

        $articleContentVersion = htmlspecialchars($_POST['article_content_version']);
        $articleId = htmlspecialchars($_POST['article_id']);

        $sqlArticleVersion = "SELECT * FROM article_content_versions WHERE article_id = $articleId AND content_version = $articleContentVersion";
        $sqlArticleVersionResult = mysqli_query($conn,$sqlArticleVersion);
        $articleVersion = $sqlArticleVersionResult->fetch_assoc();


        $stmt= $conn->prepare("SELECT * FROM article_content_versions WHERE article_id = ? AND content_version = ?");
        $stmt->execute([$articleId,$articleContentVersion]);
        $articleVersion = $stmt->fetch();

        if ($articleVersion) {
            $versionContent = $articleVersion ['version_content'];
        } else {
            $versionContent = '';
        }

        echo $versionContent;

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
            
        
        echo "<option class='category-option' value='Add'>Add Category</option>";

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
            
        
        echo "<option class='topic-option' value='Add'>Add Topic</option>";

        }
}


public function add_category (Request $request){
    if ($request->input('add_category_submit')) {
        $conn= config('app.conn');

        $newCategory = htmlspecialchars($_POST['new_category']);

        $stmt= $conn->prepare("INSERT INTO article_categories (name) VALUES (?)");
        $stmt->execute([$newCategory]);

            echo 'Category added';
        }
}


public function add_topic (){
    if (isset($_POST['add_topic_submit'])) {
        $conn= config('app.conn');
        $newTopic = htmlspecialchars($_POST['new_topic']);

        $stmt= $conn->prepare("INSERT INTO article_topics(name) VALUES (?)");
        $stmt->execute([$newTopic]);

        echo 'Topic added';
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


public function delete_topic (Request $request){
    if ($request->input('delete_topic_submit')) {
    $conn= config('app.conn');
    $deleteTopic = htmlspecialchars($_POST['delete_topic']);
    $stmt= $conn->prepare("delete from article_topics where name=?");
    $stmt->execute([$deleteTopic]);
      
    }
}


public function get_article_content_versions (Request $request){
    if ($request->input('get_article_content_versions_submit')) {
        $conn=  config('app.conn');

        $articleId = htmlspecialchars($_POST['article_id']);

   if ($articleId) {
   
      $stmt= $conn->prepare("SELECT * FROM article_content_versions WHERE article_id = ? ORDER BY id DESC");
      $stmt->execute([$articleId]);
      $count = $stmt->rowCount();
      
      if ($count>0) {
         while($articleVersions =$stmt->fetch()) {
            $articleVersion = $articleVersions ['content_version'];
            echo "<option value=$articleVersion>Content V$articleVersion</option>";
         }
      } 
  
   } else {
      echo "<option value=0 disabled selected>No Version</option>";
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


            $stmt= $conn->prepare("UPDATE articles
                    SET status = ?,
                    published = ?
                    WHERE id = ?");
            
            $stmt =$stmt->execute([$status,$articlePubDate,$articleId]);

             echo "Successful";


            }

}


public function add_article (Request $request){

    if ($request->input('article_submit')){
    
    $conn= config('app.conn');
    $userId = session('user_id');

   $storageType = htmlspecialchars($_POST['storage_type']);
   $articleMode = htmlspecialchars($_POST['article_mode']);
   $articleId = htmlspecialchars($_POST['article_id']);

   $articleTitle = htmlspecialchars($_POST['article_title']);
   $slug = generateSlug($articleTitle);
   $articleCategory = htmlspecialchars($_POST['article_category']);
   $articleTopic = htmlspecialchars($_POST['article_topic']);

   $articleContentVersion = htmlspecialchars($_POST['article_content_version']);
   
   $articleContent = $_POST['article_content'];

    $responses=[];
      $responses['error'] = [];

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


    if ($storageType=='db'){
    
      if (!$articleTitle) {

         $error = 'Please enter the title.';

         array_push($responses['error'],$error); 
      
      }

      if (!$articleCategory) {
      $error = 'Please select a category.';
        array_push($responses['error'],$error); 
     
       
      }

      if (!$articleTopic) {
         $error = 'Please select a topic';
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
            $latestVersion = (int) $articleRecord['content_version'];
            $newVersion =  $latestVersion + 1;

            } 

               $stmt = $conn->prepare("UPDATE articles 
                                    SET title=?,
                                       category=?,
                                       topic=?,
                                       content_version=?
                                       WHERE id = ?");
                $stmt->execute([$articleTitle,$articleCategory,$articleTopic,$articleId]);

                $update_articleId = $articleId;
             
            }

            if(!$articleId){
                  $newVersion = 1;

                  $stmt = $conn->prepare("INSERT INTO articles (title,slug,category,topic,writer_id,content_version) VALUES(?,?,?,?,?,?)");
                  $stmt->execute([$articleTitle,$slug,$articleCategory,$articleTopic,$userId,$newVersion]);

                   $update_articleId =  $conn->lastInsertId();

            }


            $stmt = $conn->prepare("INSERT INTO article_content_versions (article_id,content_version,version_content) VALUES(?,?,?)");
            $stmt->execute([$update_articleId,$newVersion,$articleContent]);

         
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
        
}

