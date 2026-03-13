<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dine_in_slots', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedSmallInteger('max_guests')->default(20);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dine_in_slots');
    }
};
