<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('court_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });
        Schema::create('courts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('category_id')->index();
            $table->foreign('category_id')->references('id')->on('court_categories')->onDelete('cascade');  
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('courts');
        Schema::dropIfExists('court_categories');
    }
};
