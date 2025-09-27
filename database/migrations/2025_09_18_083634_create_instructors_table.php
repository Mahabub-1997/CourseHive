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
        Schema::create('instructors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('course_id');
            $table->unsignedBigInteger('rating_id')->nullable();
            $table->string('name');
            $table->string('image')->nullable();
            $table->decimal('rating', 3, 2)->default(0);
            $table->text('description')->nullable();
            $table->integer('total_lesson')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructors');
    }
};
