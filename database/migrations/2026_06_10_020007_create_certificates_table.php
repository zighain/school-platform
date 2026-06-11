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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            // Внешний ключ на заказ (предполагаем, что таблица заказов называется 'orders')
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            // Номер сертификата из 12 символов, должен быть уникальным
            $table->string('certificate_number', 12)->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};