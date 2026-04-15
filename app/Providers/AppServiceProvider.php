<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Extensions\SessionHandler;
use Illuminate\Http\Request;
use App\Helpers\Helpers;
use PSpell\Config;

use PDO;
use Illuminate\Foundation\AliasLoader;
use App\Services\AccountRecordsService;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
        $this->app->bind('appConfig', function () {
        return [
            'site_name' => 'My App',
            'admin_email' => 'admin@example.com'
        ];
        });

      

    
    }

    /**
     * Bootstrap any application services.
     */
   public function boot(Request $request, AccountRecordsService $service)
    {

        require_once app_path('Helpers/helpers.php');


        Session::extend('eskquip_laravel', function ($app) {
        $conn= DB::connection();
        $table = 'sessions';
        $lifetime = $app['config']['session.lifetime'];

        return new SessionHandler($conn, $table, $lifetime, $app);
    });

        View::composer('*', function ($view) {
            if (!$view->offsetExists('title')) {
                $view->with('title', 'Global Title');
            }
        });

        $conn = DB::connection('mysql')->getPdo();
        
        config(['app.conn' => $conn]);
        View::share('conn', $conn);


        $domain = $request->schemeAndHttpHost();
        if ($domain) {

            if ($domain)
            $publicFolder= $domain; 
            $privateFolder=$domain.'/private';

            // if(str_contains($domain,'localhost')){
            //     $projectName = '/mafwritehub-laravel';
            //     $domain = $domain.$projectName;

            //     $publicFolder= $domain.'/public'; 
            //     $privateFolder=$domain.'/private';

            // }

        }

        if (!str_contains($publicFolder,'localhost')){
            $publicFolder = str_replace('http://','https://',$publicFolder);
        }

        
        config(['app.publicFolder'=>$publicFolder]);
        config(['app.privateFolder'=>$privateFolder]);

        View::share('publicFolder',$publicFolder);
        View::share('privateFolder',$privateFolder);

        

 

        date_default_timezone_set('Asia/Manila');
        $currentTimeZone = date_default_timezone_get();
        $currentTime = time(); 
        $currentTimeConverted = date("m/d/Y g:i A",  $currentTime); 


        config(['app.currentTimeZone' => $currentTimeZone]);
        config(['app.currentTime'=> $currentTime]);
        config(['app.currentTimeConverted'=>$currentTimeConverted]);
        View::share('currentTimeZone',$currentTimeZone);
        View::share('currentTime',$currentTime);
        View::share('currentTimeConverted',$currentTimeConverted);




        $currentURL = request()->url();
        config(['app.currentURL' => $currentURL]);
        View::share('currentURL',$currentURL);





}




}