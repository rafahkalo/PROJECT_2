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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('role')->default(2);
            $table->string('image_driver');
            $table->string('image_agency');
            $table->String('date_of_birth');
            $table->string('status')->default(false);
            $table->string('address');
            $table->foreignId('office_id')->constrained('offices')->onDelete('cascade');
            $table->string('phoneOne');
            $table->string('phoneTwo');
            $table->string('device_key')->nullable();
            $table->index(['phoneOne', 'phoneTwo']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};