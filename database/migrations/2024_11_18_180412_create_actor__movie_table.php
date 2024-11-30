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
        Schema::create('actor_movie', function (Blueprint $table) {
            $table->bigIncrements('id');  // معرف الصف في الجدول
            $table->foreignId('actor_id')->constrained('actors')->onDelete('cascade');  // مفتاح خارجي للممثل
            $table->foreignId('movie_id')->constrained('movies')->onDelete('cascade');  // مفتاح خارجي للفيلم
            $table->timestamps(true);  // وقت الإنشاء والتحديث
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actor__movies');
    }
};