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
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('payment_amount', 8, 2)->nullable()->after('payment_status');
            $table->string('payment_session_id', 120)->nullable()->after('payment_amount');
            $table->string('payment_reference', 120)->nullable()->after('payment_session_id');
            $table->json('payment_meta')->nullable()->after('payment_reference');
            $table->timestamp('paid_at')->nullable()->after('payment_meta');
            $table->string('payment_token', 64)->nullable()->after('paid_at');

            $table->index(['payment_method', 'payment_status'], 'bookings_payment_method_payment_status_index');
            $table->unique('payment_token', 'bookings_payment_token_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropUnique('bookings_payment_token_unique');
            $table->dropIndex('bookings_payment_method_payment_status_index');
            $table->dropColumn([
                'payment_amount',
                'payment_session_id',
                'payment_reference',
                'payment_meta',
                'paid_at',
                'payment_token',
            ]);
        });
    }
};
