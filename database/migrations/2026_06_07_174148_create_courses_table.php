<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30); 
            $table->string('description', 100)->nullable();
            $table->integer('hours');
            $table->decimal('price', 8, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('img');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};