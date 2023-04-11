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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title_course');
            $table->string('image_course');
            $table->string('certificate_course');
            $table->text('description');
            $table->foreignId('category_id')->constrained('categories');
            $table->enum('is_paid', [0, 1])->nullable();
            $table->integer('price')->nullable();
            $table->integer('discount')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
