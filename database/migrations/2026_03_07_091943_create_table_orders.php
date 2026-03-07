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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('order_number',20)->unique();
            $table->string('razorpay_order_id')->nullable()->index();
            $table->string('status',20);
            $table->decimal('total_amount',10,2);
            $table->decimal('paid_amount',10,2);
            $table->string('payment_method',20)->default('cash_on_delivery');
            $table->string('payment_status',10)->default('pending');
            $table->string('shipping_address');
            $table->timestamp('order_date')->useCurrent();
            $table->timestamps();
            $table->index(['user_id','status','created_at']);
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
