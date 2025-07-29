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
        Schema::create('category_recipe', function (Blueprint $table) {
            $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');
            $table->foreignId('categorie_id')->constrained('categories')->onDelete('cascade');
            $table->primary(['recipe_id', 'categorie_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_recipe');
    }
};
