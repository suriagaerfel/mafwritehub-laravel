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
        Schema::create('promotions', function (Blueprint $table) {
            $table->increments('promotionId'); // INTEGER PRIMARY KEY AUTO_INCREMENT

            $table->string('promotionCode', 100);
            $table->timestamp('promotionTimestamp')->useCurrent(); // DEFAULT CURRENT_TIMESTAMP
            $table->longText('promotionNameCompany');
            $table->longText('promotionTitle');
            $table->longText('promotionTopics');
            $table->longText('promotionDescription');
            $table->string('promotionType', 64);
            $table->longText('promotionImage');
            $table->longText('promotionLink');
            $table->string('promotionDuration', 64);
            $table->integer('promotionAmount');
            $table->longText('promotionAgreement');
            $table->dateTime('promotionDate');
            $table->dateTime('promotionExpiry');
            $table->string('promotionStatus', 64);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
