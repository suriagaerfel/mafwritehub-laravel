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
       Schema::create('content_performance', function (Blueprint $table) {
            $table->increments('content_viewId'); // INTEGER PRIMARY KEY AUTO_INCREMENT

            $table->timestamp('content_viewTimestamp')->useCurrent(); // DEFAULT CURRENT_TIMESTAMP
            $table->string('content_viewTable', 100);
            $table->integer('content_viewForeignId')->unsigned();
            $table->integer('content_viewUserId')->unsigned();
            $table->integer('content_viewTime'); // can add ->unsigned() if non‑negative only
            $table->timestamp('content_viewLastUpdate')
                ->useCurrent()
                ->useCurrentOnUpdate(); // DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_performance');
    }
};
