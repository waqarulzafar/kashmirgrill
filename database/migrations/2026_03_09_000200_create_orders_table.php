<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 40)->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status', 32)->default('pending');
            $table->string('fulfillment_type', 20);

            $table->string('customer_name', 120);
            $table->string('customer_email', 150);
            $table->string('customer_phone', 40);

            $table->text('delivery_address')->nullable();
            $table->text('notes')->nullable();

            $table->foreignId('dine_in_slot_id')->nullable()->constrained('dine_in_slots')->nullOnDelete();
            $table->date('reservation_date')->nullable();
            $table->time('reservation_time')->nullable();
            $table->unsignedTinyInteger('guest_count')->nullable();

            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->timestamp('placed_at')->nullable();
            $table->timestamps();

            $table->index(['fulfillment_type', 'reservation_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
