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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');                                             // Who paid
            $table->foreignId('course_id');                                            // Which course
            $table->decimal('amount', 10, 2);                              // Amount paid
            $table->string('currency', 10);
            $table->string('payment_method')->nullable();                               // e.g., card
            $table->string('stripe_payment_id')->nullable();                         // Stripe transaction ID
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->foreignId('promo_code_id')->nullable();                             // Optional applied promo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
