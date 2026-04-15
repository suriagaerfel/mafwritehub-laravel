<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Services\AccountRecordsService;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{

    protected $account_records;

    protected $publicFolder;

    public function __construct(AccountRecordsService $service)
    {

        $userId = session('user_id');

        $this->account_records = $service->get_profile_records($userId);
        $records= $this->account_records;

        View::share($records);

        $this->publicFolder = config('app.publicFolder');

    }








    public function home(){

        $pageName = 'Home';

        return view ('home', compact('pageName'));
        
    }



    public function articles (){
        $pageName = 'Articles';

        $user=null;
        $slug=null;
        $category=null;
        $tag=null;
        $writer=null;
        $date=null;


        $publicFolder= $this->publicFolder;
       

        return view ('articles', compact('pageName','user','slug','category','tag','writer','date'));
   
    }

    public function article_slug ($slug){
        $conn= config('app.conn');
         $publicFolder= config('app.publicFolder');

        // $pageName = 'Articles';

        $user=null;
        $slug=$slug;
        $category=null;
        $tag=null;
        $writer=null;
        $date=null;

        $publicFolder= $this->publicFolder;


        if ($slug) {

        $stmt = $conn->prepare("SELECT * FROM articles WHERE slug='$slug'");
        $stmt->execute();
        $info = $stmt->fetch();


        if ($info) {
            $articleInfo= true;

            $articleId = $info ['id'];
            $articleWriterId = $info ['writer_id'];

            $stmt = $conn->prepare("SELECT * FROM users WHERE id = $articleWriterId");
            $stmt->execute();
            $writerInfo = $stmt->fetch();

            if($writerInfo) {
                $articleWriterName = $writerInfo ['name'];
                $articleWriterDescription = $writerInfo ['description'];
                $articleWriterProfilePicture = $writerInfo ['profile_picture_link'] ? $publicFolder.$writerInfo ['profile_picture_link'] : $publicFolder."/assets/images/user.svg";
                $articleWriterUsername = $writerInfo ['username'];
            } 

            $articleTitle = $info ['title'];
            $articleImage = $info ['image'] ? $publicFolder.$articleInfo ['image'] : "";
            $articleCategory = $info ['category'];
            $articleTopic = $info ['topic'];
            $articleVersion = $info ['version'];

            $stmt = $conn->prepare("SELECT * FROM article_versions WHERE article_id= $articleId AND version = $articleVersion");
            $stmt->execute();
            $version= $stmt->fetch();

            if ($version){
                $articleBody = $version ['version_body'];
            }
         
            $articlePubDate = $info ['published'];
            $articleUpdateDate = $info ['updated'];
            $articleStatus = $info ['status'];

        
            if ($articleStatus!="Published") {
                $unpublishedNotice = true;
            }

            $pageName = $articleTitle; 

            if ($pageName == 'Terms of Use') {
            return redirect ($publicFolder.'/terms-of-use');
            }

            if ($pageName == 'About Us') {
            return redirect($publicFolder.'/about-us');
            }

            if ($pageName == 'Data Privacy') {
             return redirect ($publicFolder.'/data-privacy');
            }
            
        } else {
            $articleStatus='';
            $articleWriterUsername='';
            $articleWriterProfilePicture='';
            $articleWriterName='';
            $articleCategory='';
            $articlePubDate='';
            $articleUpdateDate='';
            $articleImage='';
            $articleTitle='';
            $articleBody='';
            $articleWriterDescription='';
            $articleInfo= false;
        }

        

        
      }



        return view ('articles', compact('pageName','user','slug','category','tag','writer','date','articleStatus','articleWriterUsername','articleWriterProfilePicture','articleWriterName','articleCategory','articlePubDate','articleUpdateDate','articleImage','articleTitle','articleBody','articleWriterDescription','articleInfo'));
   
    }



     public function article_category ($category){
        $conn= config('app.conn');
         $publicFolder= config('app.publicFolder');

        $pageName = $category;

        $user=null;
        $slug=null;
        $category=$category;
        $tag=null;
        $writer=null;
        $date=null;

        $publicFolder= $this->publicFolder;

        return view ('articles', compact('pageName','user','slug','category','tag','writer','date'));
   
    }


     public function article_tag ($tag){
        $conn= config('app.conn');
         $publicFolder= config('app.publicFolder');

        $pageName = $tag;

        $user=null;
        $slug=null;
        $category=null;
        $tag=$tag;
        $writer=null;
        $date=null;

        $publicFolder= $this->publicFolder;

        return view ('articles', compact('pageName','user','slug','category','tag','writer','date'));
   
    }

     public function article_writer ($writer){
        $conn= config('app.conn');
         $publicFolder= config('app.publicFolder');

        $pageName = $writer;

        $user=null;
        $slug=null;
        $category=null;
        $tag=null;
        $writer=$writer;
        $date=null;

        $publicFolder= $this->publicFolder;

        return view ('articles', compact('pageName','user','slug','category','tag','writer','date'));
   
    }


     public function article_date ($date){
        $conn= config('app.conn');
         $publicFolder= config('app.publicFolder');

        $pageName = $date;

        $user=null;
        $slug=null;
        $category=null;
        $tag=null;
        $writer=null;
        $date=$date;

        $publicFolder= $this->publicFolder;

        return view ('articles', compact('pageName','user','slug','category','tag','writer','date'));
   
    }





    
    

      


     public function about_us (AccountRecordsService $service){
        
        $pageName = 'About Us';


        // return view ('about-us', compact('pageName'));

        $publicFolder=config('app.publicFoldr');
        return redirect($publicFolder.'/articles');
        
    }

     

    public function data_privacy (AccountRecordsService $service){

        $user=null;
        
        $pageName = 'Data Privacy';


        // return view ('data-privacy', compact('pageName','user'));

        $publicFolder=config('app.publicFoldr');
        return redirect($publicFolder.'/articles');
        
    }

     public function terms_of_use (AccountRecordsService $service){

        $user=null;
        
        $pageName = 'Terms of Use';


        // return view ('terms-of-use', compact('pageName','user'));

        $publicFolder=config('app.publicFoldr');
        return redirect($publicFolder.'/articles');
        
    }


    





    public function dashboard (){
         $pageName = 'Dashboard';

        $publicFolder= $this->publicFolder;

        $loggedIn= $this->account_records['loggedIn'];
        $publicFolder= $this->publicFolder;

        if (!$loggedIn){
            return redirect($publicFolder);
        }

        return view ('dashboard', compact('pageName'));
    }


    public function reset_password ($token,$user_id){

        $pageName = 'Reset Password';

        $conn=config('app.conn');
        
        
        $stmt = $conn->prepare ("SELECT * FROM users WHERE id= ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        if ($user) {
        
            $real_password_reset_token = $user['password_reset_token'];
            $real_password_reset_token_expiration = strtotime($user['password_reset_token_expiration']);

            if ($token== $real_password_reset_token) {
                                
                if ($real_password_reset_token_expiration-time()>0) {
                
                   
                    return view('reset-password',compact('pageName','token','user_id'))->with('reset-now',true);

                } else {
              
                    return redirect(route('home'))->with('link-expired',true);
                }
                
            } else {
             
                return redirect(route('home'))->with('not-you',true);    
            }

        } else {
           return redirect(route('home'))->with('account-not-found',true);   
            
        }


    }


    public function add_article (){
        $pageName = 'Add Article';
        
        return view('article-edit', compact('pageName'));
    }


}
