<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\View;
use App\Services\AccountRecordsService;
use Session;

class AccountRecordsMiddleware
{

    protected $service;

   public function __construct(AccountRecordsService $service)
    {
        $this->service = $service;
    }


    public function handle(Request $request, Closure $next): Response 
    {

        $registrantCode = session ('registrant-code');
        
        $myRecords = $this->service->get_profile_records($registrantCode);

        // $records = $this->service->get_profile_records($registrantCode);
        // View::share($records);

        View::share('loggedIn', $myRecords ['loggedIn']);
        View::share('registrantId', $myRecords ['registrantId']);
        View::share('registrantCode', $myRecords ['registrantCode']);
        View::share('firstName',  $myRecords ['firstName']);
        View::share('middleName',  $myRecords ['middleName']);
        View::share('lastName',  $myRecords ['lastName']);
        View::share('accountName',  $myRecords ['accountName']);
        View::share('registrantDescription', $myRecords ['registrantDescription']);
        View::share('type', $myRecords ['type']);
        View::share('username', $myRecords ['username']);
        View::share('emailAddress', $myRecords ['emailAddress']);
        View::share('mobileNumber', $myRecords ['mobileNumber']);
        View::share('birthdate', $myRecords ['birthdate']);
        View::share('gender', $myRecords ['gender']);
        View::share('civilStatus', $myRecords ['civilStatus']);

        View::share('profilePictureLink',$myRecords ['profilePictureLink']);
        View::share('coverPhotoLink',$myRecords ['coverPhotoLink']);

        View::share('education', $myRecords ['education']);
        View::share('school',$myRecords ['school']);
        View::share('occupation', $myRecords ['occupation']);
        View::share('street_subd_village', $myRecords ['street_subd_village']);
        View::share('barangay', $myRecords ['barangay']);
        View::share('city_municipality', $myRecords ['city_municipality']);
        View::share('province_state', $myRecords ['province_state']);
        View::share('region', $myRecords ['region']);
        View::share('country', $myRecords ['country']);
        View::share('zipcode', $myRecords ['zipcode']);
        View::share('basicRegistration', $myRecords ['basicRegistration']);
        View::share('teacherRegistration', $myRecords ['teacherRegistration']);
        View::share('writerRegistration', $myRecords ['writerRegistration']);
        View::share('editorRegistration', $myRecords ['editorRegistration']);
        View::share('developerRegistration', $myRecords ['developerRegistration']);
        View::share('researchesRegistration', $myRecords ['researchesRegistration']);
        View::share('websiteManagerRegistration', $myRecords ['websiteManagerRegistration']);
        View::share('websiteManagerSuperManagerRegistration', $myRecords ['websiteManagerSuperManagerRegistration']);
        View::share('websiteManagerSubscriptionManagerRegistration', $myRecords ['websiteManagerSubscriptionManagerRegistration']);
        View::share('websiteManagerRegistrationManagerRegistration', $myRecords ['websiteManagerRegistrationManagerRegistration']);
        View::share('websiteManagerPromotionManagerRegistration', $myRecords ['websiteManagerPromotionManagerRegistration']);
        View::share('websiteManagerMessageManagerRegistration', $myRecords ['websiteManagerMessageManagerRegistration']);

        View::share('inSubscriptionSellerList', $myRecords ['inSubscriptionSellerList']);
        View::share('inSubscriptionToolList', $myRecords ['inSubscriptionToolList']);
        View::share('inSubscriptionFileList', $myRecords ['inSubscriptionFileList']);
        View::share('inSubscriptionShelfList', $myRecords ['inSubscriptionShelfList']);

        View::share('toolSubscribed', $myRecords ['toolSubscribed']);
        View::share('fileSubscribed', $myRecords ['fileSubscribed']); 
        View::share('sellerSubscribed', $myRecords ['sellerSubscribed']);  
        View::share('shelfSubscribed', $myRecords ['shelfSubscribed']); 

        View::share('pendingToolSubscription', $myRecords ['pendingToolSubscription']); 
        View::share('pendingFileSubscription', $myRecords ['pendingFileSubscription']);
        View::share('pendingSellerSubscription', $myRecords ['pendingSellerSubscription']);
        View::share('pendingShelfSubscription', $myRecords ['pendingShelfSubscription']);

        View::share('subscriptionRemainingDaysTool', $myRecords ['subscriptionRemainingDaysTool']);
        View::share('subscriptionRemainingDaysFile', $myRecords ['subscriptionRemainingDaysFile']);
        View::share('subscriptionRemainingDaysSeller', $myRecords ['subscriptionRemainingDaysSeller']);
        View::share('subscriptionRemainingDaysShelf', $myRecords ['subscriptionRemainingDaysShelf']);
        View::share('subscription', $myRecords ['subscription']);

        View::share('haveOtherRegistration', $myRecords ['haveOtherRegistration']);
        View::share('haveAllRegistrations', $myRecords ['haveAllRegistrations']);

        View::share('filledOutSellingDetails', $myRecords ['filledOutSellingDetails']);

        return $next($request);
    }
}


