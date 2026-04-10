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
        Schema::create('legal_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->cascadeOnDelete();
            $table->dateTime('scheduled_at');
            $table->string('location')->nullable();
            $table->string('judge')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('scheduled'); // scheduled, completed, postponed, cancelled
            $table->timestamps();

            $table->index('case_id');
            $table->index('scheduled_at');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legal_sessions');
    }
};
