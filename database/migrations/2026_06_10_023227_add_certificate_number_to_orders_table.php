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
        Schema::table('orders', function (Blueprint $table) {
            // Добавляем поле для хранения номера сертификата (12 символов)
            // nullable(), так как у старых заказов его может не быть
            $table->string('certificate_number', 12)->nullable()->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Удаляем колонку, если потребуется откат миграции
            $table->dropColumn('certificate_number');
        });
    }
};