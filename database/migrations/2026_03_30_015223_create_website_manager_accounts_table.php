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
       Schema::create('website_manager_accounts', function (Blueprint $table) {
            $table->id('wma_id');  // int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
            
            $table->unsignedInteger('wma_registrantId');  // int(11) NOT NULL (user ID)
            
            // Role flags (varchar(100), empty='' means no access)
            $table->string('wma_superManager', 100);  // 'Super Manager'
            $table->string('wma_subscriptionManager', 100)->nullable();  
            $table->string('wma_registrationManager', 100)->nullable();  
            $table->string('wma_promotionManager', 100)->nullable();  
            $table->string('wma_messageManager', 100)->nullable();  
            
            $table->timestamps();  // Laravel standard created_at/updated_at
            
            $table->index('wma_registrantId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_manager_accounts');
    }
};
