<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('other_registrations', function (Blueprint $table) {
            $table->increments('otherId'); // INTEGER PRIMARY KEY AUTO_INCREMENT

            $table->timestamp('otherTimestamp')->useCurrent(); // DEFAULT CURRENT_TIMESTAMP
            $table->string('otherType', 64)->nullable();
            $table->integer('otherUserId')->unsigned();
            $table->longText('otherRegistrantAccountName');
            $table->string('otherResume', 150);
            $table->string('otherLicenseCertification', 150);
            $table->string('otherSample', 150);
            $table->string('otherAgreement', 150);
            $table->longText('otherNotes');
            $table->string('otherStatus', 64)->default('Pending');
            $table->dateTime('otherApprovalDate')->nullable(); // DEFAULT NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('other_registrations');
    }
};
