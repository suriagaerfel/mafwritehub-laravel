<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use App\Models\Registration;
use App\Services\AccountRecordsService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Services\MailService;
use App\Services\FunctionsService;









class FeaturesController extends Controller
{   
    public function get_searched_articles (Request  $request){
        if($request->input('get_searched_articles_submit')) {
   
        $conn= config('app.conn');
        $publicFolder = config('app.publicFolder');

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

        $stmt= $conn->prepare($sqlSearchedArticles);
        $stmt->execute();
        $count= $stmt->rowCount();


            if ($count>0) {
                    
            while ($searcheArticles = $stmt->fetch()) {
            $searchedTitle = $searcheArticles ['title'];
            $searchedCategory = $searcheArticles ['category'];
            $searchedSlug = $searcheArticles ['slug'];
            
            if ($searchedCategory !='Administrative') {

                    echo "<a href='$publicFolder/articles/read/$searchedSlug' style='background-color: aliceblue;padding:5px;'><strong>$searchedTitle</strong></a><br><br>"; 
            }
                    
            } 
            
            } else {
            echo "<small>No result</small>";
            }

        }
        }
    }


    public function get_featured_articles (Request $request){
        if($request->input('get_featured_articles_submit')) {
            $conn= config('app.conn');
            $publicFolder = config('app.publicFolder');

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
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = '$articleWriter'");
            $stmt->execute();           
            $writer = $stmt->fetch();

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


            $stmt= $conn->prepare($sqlFeaturedArticles);
            $stmt->execute();
            $count= $stmt->rowCount();
         

            if ($count>0) {
                    
            while ($articles = $stmt->fetch()) {
            $title = $articles ['title'];
            $category = $articles ['category'];
            $slug = $articles ['slug'];
            $image = $articles ['image'] ? $publicFolder.'/uploads/featured-images/'.$articles ['image']: $publicFolder.'/assets/images/default-featured-image.jpg';

            if ($category !='Administrative') {
                echo "<div style='display:flex;flex-direction:column; width:400px; background-color:white;padding:20px;' class='featured-article-item'>";
                    echo "<img src='$image' style='width:100%;'> "; 
                    echo "<a class='' href='$publicFolder/articles/read/$slug'><strong>$title</strong></a>";      
                echo "</div>";

            }
            
            } 
            
            } 


        }
    }

    public function get_featured_categories (Request $request){
        if ($request->input('get_featured_categories_submit')) {
            $conn= config('app.conn');
            $publicFolder= config('app.publicFolder');
            
            $stmt = $conn->prepare("SELECT * FROM article_categories");
            $stmt->execute();
            $count= $stmt->rowCount();

            
        if ($count>0) {
                    
            while ($categories = $stmt->fetch()) {
            $category = $categories ['name'];
            if($category !='Administrative') {
                    echo "<a class='navigation-button' href='$publicFolder/articles/categories/$category'>$category</a>";     
            }
                

            } 
            
            } 
        }
    }
}




    




    

        

        

        
