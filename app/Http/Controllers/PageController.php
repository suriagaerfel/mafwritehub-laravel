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


}
