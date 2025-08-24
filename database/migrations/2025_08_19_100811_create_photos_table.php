<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); // public identifier for sharing URLs
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // photo belongs to user
            $table->string('image_path'); // store the file path
            $table->string('caption')->nullable(); // optional caption
            $table->unsignedBigInteger('likes_count')->default(0); // scalable likes counter
            $table->timestamps();

            $table->index('user_id'); // for performance on user photos
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
