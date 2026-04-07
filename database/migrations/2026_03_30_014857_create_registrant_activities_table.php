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
       Schema::create('registrant_activities', function (Blueprint $table) {
            $table->id('registrant_activityId');  // int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
            $table->timestamp('registrant_activityTimestamp')->useCurrent();  // timestamp NOT NULL DEFAULT current_timestamp()
            $table->unsignedInteger('registrant_activityUserId');  // int(11) NOT NULL
            $table->string('registrant_activityType', 100)->nullable();  // varchar(100) NOT NULL
            $table->string('registrant_activityContent', 150)->nullable();  // varchar(150) NOT NULL
            
            $table->timestamps();  // Laravel standard created_at/updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrant_activities');
    }
};
