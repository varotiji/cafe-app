<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('price');
            $table->string('image')->nullable();
            $table->string('category');
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            $table->softDeletes(); // ✅ soft delete
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
