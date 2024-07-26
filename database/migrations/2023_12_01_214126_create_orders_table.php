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
            $table->enum('status_user', ['factory','pharmacy'])->default('factory');

            $table->enum('status_order', ['قيد التحضير','تم الارسال','مستلمة'])->default('قيد التحضير ');
            $table->enum('status_paid', ['مدفوع','غير مدفوع'])->default('غير مدفوع');
            $table->string('trade_name');
            $table->integer('amount');
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
