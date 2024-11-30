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
        Schema::create('categoryables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->morphs('categoryable'); // هيدا بيسمح للعلاقة تكون مع الأفلام أو المسلسلات
            $table->timestamps(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categoryable');
    }
};