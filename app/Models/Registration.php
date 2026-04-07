<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'registrantCode',
        'registrantFirstName',
        'registrantMiddleName',
        'registrantLastName', 
        'registrantAccountName',
        'registrantDescription',
        'registrantAccountType', 
        'registrantBirthdate',
        'registrantGender',
        'registrantCivilStatus',
        'registrantAddressStreet',
        'registrantAddressBarangay', 
        'registrantAddressCity', 
        'registrantAddressProvince', 
        'registrantAddressRegion', 
        'registrantAddressCountry', 
        'registrantAddressZipCode',
        'registrantEducationalAttainment',
        'registrantSchool',
        'registrantOccupation',
        'registrantEmailAddress',
        'registrantMobileNumber',
        'registrantUsername',
        'registrantPassword',
        'registrantBasicAccount'
       
        ];

    protected $guarded = [];

}
