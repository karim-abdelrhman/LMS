<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('case_number')->index();
            $table->unsignedBigInteger('category_id')->index();
            $table->unsignedBigInteger('court_id')->index();
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('case_categories')->onDelete('cascade');
            $table->foreign('court_id')->references('id')->on('courts')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('cases');
        Schema::dropIfExists('case_categories');
    }
};
