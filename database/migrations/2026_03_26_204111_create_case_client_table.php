<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_client', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_id')->index();
            $table->unsignedBigInteger('client_id')->index();
            $table->timestamps();
            $table->unique(['case_id', 'client_id']);
            $table->foreign('case_id')->references('id')->on('cases')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('case_client');
    }
};
