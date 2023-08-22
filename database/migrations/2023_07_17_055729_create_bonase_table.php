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
        Schema::create('bonase', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('office_id')->constrained('offices')->onDelete('cascade');
            $table->String('description');
            $table->integer('amount');
           $table->integer('point_id')->constrained('subscreptions')->onDelete('cascade');
       //   $table->integer('numberpoint');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonase');
    }
};
