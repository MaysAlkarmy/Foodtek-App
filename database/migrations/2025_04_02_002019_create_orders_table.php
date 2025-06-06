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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // مفتاح أجنبي لجدول المستخدمين
            $table->decimal('total_amount', 10, 2); // المبلغ الإجمالي
            $table->enum('status', ['pending', 'preparing', 'out_for_delivery', 'completed', 'canceled']);
            $table->enum('payment_status', ['pending', 'paid', 'failed']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
