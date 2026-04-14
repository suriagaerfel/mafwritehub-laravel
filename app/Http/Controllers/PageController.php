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



    public function articles ($slug=null,$category=null,$date=null,$tag=null){
        $pageName = 'Articles';

        $user=null;

        $publicFolder= $this->publicFolder;
       

        return view ('articles', compact('pageName','user','slug','date'));
   
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
