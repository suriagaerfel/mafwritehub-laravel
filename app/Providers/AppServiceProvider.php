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

            if(str_contains($domain,'localhost')){
                $projectName = '/eskquip-laravel';
                $domain = $domain.$projectName;

                $publicFolder= $domain.'/public'; 
                $privateFolder=$domain.'/private';

            }

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





        // $registrantCode = session ('registrant-code');
        // $myRecords = $service->get_profile_records($registrantCode);

        // View::share('loggedIn', $myRecords ['loggedIn']);
        // View::share('registrantId', $myRecords ['registrantId']);
        // View::share('registrantCode', $myRecords ['registrantCode']);
        // View::share('firstName',  $myRecords ['firstName']);
        // View::share('middleName',  $myRecords ['middleName']);
        // View::share('lastName',  $myRecords ['lastName']);
        // View::share('accountName',  $myRecords ['accountName']);
        // View::share('registrantDescription', $myRecords ['registrantDescription']);
        // View::share('type', $myRecords ['type']);
        // View::share('username', $myRecords ['username']);
        // View::share('emailAddress', $myRecords ['emailAddress']);
        // View::share('mobileNumber', $myRecords ['mobileNumber']);
        // View::share('birthdate', $myRecords ['birthdate']);
        // View::share('gender', $myRecords ['gender']);
        // View::share('civilStatus', $myRecords ['civilStatus']);

        // View::share('profilePictureLink',$myRecords ['profilePictureLink']);
        // View::share('coverPhotoLink',$myRecords ['coverPhotoLink']);

        // View::share('education', $myRecords ['education']);
        // View::share('school',$myRecords ['school']);
        // View::share('occupation', $myRecords ['occupation']);
        // View::share('street_subd_village', $myRecords ['street_subd_village']);
        // View::share('barangay', $myRecords ['barangay']);
        // View::share('city_municipality', $myRecords ['city_municipality']);
        // View::share('province_state', $myRecords ['province_state']);
        // View::share('region', $myRecords ['region']);
        // View::share('country', $myRecords ['country']);
        // View::share('zipcode', $myRecords ['zipcode']);
        // View::share('basicRegistration', $myRecords ['basicRegistration']);
        // View::share('teacherRegistration', $myRecords ['teacherRegistration']);
        // View::share('writerRegistration', $myRecords ['writerRegistration']);
        // View::share('editorRegistration', $myRecords ['editorRegistration']);
        // View::share('developerRegistration', $myRecords ['developerRegistration']);
        // View::share('researchesRegistration', $myRecords ['researchesRegistration']);
        // View::share('websiteManagerRegistration', $myRecords ['websiteManagerRegistration']);
        // View::share('websiteManagerSuperManagerRegistration', $myRecords ['websiteManagerSuperManagerRegistration']);
        // View::share('websiteManagerSubscriptionManagerRegistration', $myRecords ['websiteManagerSubscriptionManagerRegistration']);
        // View::share('websiteManagerRegistrationManagerRegistration', $myRecords ['websiteManagerRegistrationManagerRegistration']);
        // View::share('websiteManagerPromotionManagerRegistration', $myRecords ['websiteManagerPromotionManagerRegistration']);
        // View::share('websiteManagerMessageManagerRegistration', $myRecords ['websiteManagerMessageManagerRegistration']);

        // View::share('inSubscriptionSellerList', $myRecords ['inSubscriptionSellerList']);
        // View::share('inSubscriptionToolList', $myRecords ['inSubscriptionToolList']);
        // View::share('inSubscriptionFileList', $myRecords ['inSubscriptionFileList']);
        // View::share('inSubscriptionShelfList', $myRecords ['inSubscriptionShelfList']);

        // View::share('toolSubscribed', $myRecords ['toolSubscribed']);
        // View::share('fileSubscribed', $myRecords ['fileSubscribed']); 
        // View::share('sellerSubscribed', $myRecords ['sellerSubscribed']);  
        // View::share('shelfSubscribed', $myRecords ['shelfSubscribed']); 

        // View::share('pendingToolSubscription', $myRecords ['pendingToolSubscription']); 
        // View::share('pendingFileSubscription', $myRecords ['pendingFileSubscription']);
        // View::share('pendingSellerSubscription', $myRecords ['pendingSellerSubscription']);
        // View::share('pendingShelfSubscription', $myRecords ['pendingShelfSubscription']);

        // View::share('subscriptionRemainingDaysTool', $myRecords ['subscriptionRemainingDaysTool']);
        // View::share('subscriptionRemainingDaysFile', $myRecords ['subscriptionRemainingDaysFile']);
        // View::share('subscriptionRemainingDaysSeller', $myRecords ['subscriptionRemainingDaysSeller']);
        // View::share('subscriptionRemainingDaysShelf', $myRecords ['subscriptionRemainingDaysShelf']);
        // View::share('subscription', $myRecords ['subscription']);

        // View::share('haveOtherRegistration', $myRecords ['haveOtherRegistration']);
        // View::share('haveAllRegistrations', $myRecords ['haveAllRegistrations']);

        // View::share('filledOutSellingDetails', $myRecords ['filledOutSellingDetails']);

}




}