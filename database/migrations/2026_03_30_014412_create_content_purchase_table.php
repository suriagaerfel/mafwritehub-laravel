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
        Schema::create('content_purchase', function (Blueprint $table) {
            $table->increments('content_purchaseId'); // INTEGER PRIMARY KEY AUTO_INCREMENT

            $table->timestamp('content_purchaseTimestamp')->useCurrent(); // DEFAULT CURRENT_TIMESTAMP
            $table->string('content_purchaseType', 100);
            $table->integer('content_purchaseContentId')->unsigned();
            $table->integer('content_purchaseSeller')->unsigned();
            $table->integer('content_purchaseAmount');
            $table->integer('content_purchaseRegistrantId')->unsigned();
            $table->string('content_purchasePaymentChannel', 64);
            $table->string('content_purchaseReferenceNumber', 64);
            $table->string('content_purchaseStatus', 64);
            $table->string('content_purchaseProofLink', 64);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_purchase');
    }
};
