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
        Schema::create('registrant_subscriptions', function (Blueprint $table) {
            $table->id('rs_id');  // int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
            
            $table->unsignedInteger('rs_userId');  // int(11) NOT NULL
            $table->longText('rs_registrantAccountName');  // longtext NOT NULL
            $table->string('rs_type', 64);  // varchar(64) NOT NULL
            $table->string('rs_duration', 2)->default('1');  // varchar(2) NOT NULL DEFAULT '1'
            $table->unsignedInteger('rs_total');  // int(11) NOT NULL
            $table->string('rs_paymentOption', 64);  // varchar(64) NOT NULL
            $table->string('rs_senderName', 250);  // varchar(250) NOT NULL
            $table->string('rs_senderAccountNumber', 64);  // varchar(64) NOT NULL
            $table->string('rs_refNumber', 64);  // varchar(64) NOT NULL
            $table->string('rs_proofOfPayment', 100);  // varchar(100) NOT NULL
            $table->dateTime('rs_timestamp')->useCurrent();  // datetime NOT NULL DEFAULT current_timestamp()
            $table->string('rs_status', 64)->default('Pending');  // varchar(64) NOT NULL DEFAULT 'Pending'
            $table->dateTime('rs_date')->nullable();  // datetime DEFAULT NULL
            $table->dateTime('rs_expiry')->nullable();  // datetime DEFAULT NULL
                    
            $table->index(['rs_userId', 'rs_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrant_subscriptions');
    }
};
