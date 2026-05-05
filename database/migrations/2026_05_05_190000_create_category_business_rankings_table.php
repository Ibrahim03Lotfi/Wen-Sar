<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_business_rankings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade');
            $table->unsignedTinyInteger('rank'); // 1-10
            $table->timestamps();

            $table->unique(['category_id', 'business_id']);
            $table->unique(['category_id', 'rank']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_business_rankings');
    }
};
