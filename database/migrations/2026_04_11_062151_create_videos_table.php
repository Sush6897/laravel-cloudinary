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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('filename')->nullable();
            $table->string('original_filename');
            $table->bigInteger('size')->nullable();
            $table->string('status')->default('pending'); // pending, processing, completed, failed
            $table->string('cloudinary_public_id')->nullable();
            $table->text('cloudinary_url')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
