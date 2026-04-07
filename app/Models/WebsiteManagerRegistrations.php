<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteManagerRegistrations extends Model
{
    protected $fillable = [
         'website_manager_accountSuperManager',
           'website_manager_accountSubscriptionManager', 
           'website_manager_accountRegistrationManager', 
          'website_manager_accountPromotionManager',
          'website_manager_accountMessageManager'
    
        ];


        // public function user (){
        //     return $this->belongsTo(User::class);
        // }
}
