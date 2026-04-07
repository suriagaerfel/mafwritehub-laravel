<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->increments('registrantId'); // INTEGER PRIMARY KEY AUTOINCREMENT in SQLite[web:13][web:16]
            $table->string('registrantCode', 64)->nullable();
            $table->string('registrantFirstName', 256);
            $table->string('registrantMiddleName', 256)->nullable();;
            $table->string('registrantLastName', 256);
            $table->string('registrantAccountName', 100);
            $table->longText('registrantDescription')->nullable();;
            $table->string('registrantAccountType', 64);
            $table->integer('registrantProfilePictureStatus')->default(1);
            $table->string('registrantProfilePictureLink', 100)->nullable();
            $table->string('registrantCoverPhotoLink', 100)->nullable();
            $table->date('registrantBirthdate','64')->nullable();
            $table->string('registrantGender', 64)->nullable();
            $table->string('registrantCivilStatus', 64)->nullable();
            $table->string('registrantAddressStreet', 100)->nullable();
            $table->string('registrantAddressBarangay', 100)->nullable();
            $table->string('registrantAddressCity', 100)->nullable();
            $table->string('registrantAddressProvince', 100)->nullable();
            $table->string('registrantAddressRegion', 100)->nullable();
            $table->string('registrantAddressCountry', 100)->nullable();
            $table->string('registrantAddressZipCode', 64)->nullable();
            $table->string('registrantEducationalAttainment', 64)->nullable();
            $table->string('registrantSchool', 100)->nullable();
            $table->string('registrantOccupation', 100)->nullable();
            $table->string('registrantEmailAddress', 64);
            $table->string('registrantMobileNumber', 64)->nullable();
            $table->string('registrantUsername', 100);
            $table->longText('registrantPassword');
            $table->string('registrantVerificationCode', 64);
            $table->string('registrantBasicAccount', 64)->nullable();
            $table->string('registrantTeacherAccount', 64)->nullable();
            $table->string('registrantWriterAccount', 64)->nullable();
            $table->string('registrantEditorAccount', 64)->nullable();
            $table->string('registrantWebsiteManagerAccount', 64)->nullable();
            $table->string('registrantDeveloperAccount', 64)->nullable();
            $table->string('registrantResearchesAccount', 64)->nullable();
            $table->string('registrantVerificationStatus', 64)->default('Unverified');
            $table->string('registrantStatus', 64)->default('Good');
            $table->dateTime('registrantCreatedAt');
            $table->string('resetTokenHash', 64)->nullable();
            $table->dateTime('resetTokenHashExpiration')->nullable();
            $table->string('registrantPaymentChannel', 64)->nullable();
            $table->string('registrantBankAccountName', 150)->nullable();
            $table->string('registrantBankAccountNumber', 64)->nullable();
            $table->string('registrantReviewSchedules', 150)->nullable();
            $table->longText('registrantConnectedUsers')->nullable();
            $table->longText('registrantLogoutLinkToken')->nullable();


            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
