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
        Schema::create('paymentfatora', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_payment')->default(false);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
         
            $table->foreignId('payment_id')->constrained('payment_types')->onDelete('cascade');
            $table->double('paymentAmount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paymentfatora');
    }
};
