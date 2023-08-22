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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->integer('cost');
            $table->string('image');
            $table->string('agency_image');
            $table->integer('numberOfSeating');
            $table->integer('number');
            $table->foreignId('color_id')->constrained('colors')->onDelete('cascade');
            $table->foreignId('type_id')->constrained('car_types')->onDelete('cascade');
           
            $table->foreignId('office_id')->nullable()->constrained('offices')->onDelete('cascade')->default(0);
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->onDelete('cascade')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};