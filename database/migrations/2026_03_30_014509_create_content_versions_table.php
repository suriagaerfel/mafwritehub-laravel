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
        Schema::create('content_versions', function (Blueprint $table) {
            $table->increments('content_versionId'); // INTEGER PRIMARY KEY AUTO_INCREMENT

            $table->string('content_versionTable', 100);
            $table->integer('content_versionContentId')->unsigned();
            $table->integer('content_versionNumber');
            $table->longText('content_versionContent');
            $table->timestamp('content_versionTimestamp')->useCurrent(); // DEFAULT CURRENT_TIMESTAMP
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_versions');
    }
};
