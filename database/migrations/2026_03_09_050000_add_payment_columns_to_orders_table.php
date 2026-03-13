<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method', 20)->nullable()->after('total');
            $table->string('payment_provider', 20)->nullable()->after('payment_method');
            $table->string('payment_status', 32)->default('pending')->after('payment_provider');
            $table->string('payment_session_id', 120)->nullable()->after('payment_status');
            $table->string('payment_reference', 120)->nullable()->after('payment_session_id');
            $table->json('payment_meta')->nullable()->after('payment_reference');
            $table->timestamp('paid_at')->nullable()->after('payment_meta');

            $table->index(['payment_method', 'payment_status']);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_payment_method_payment_status_index');
            $table->dropColumn([
                'payment_method',
                'payment_provider',
                'payment_status',
                'payment_session_id',
                'payment_reference',
                'payment_meta',
                'paid_at',
            ]);
        });
    }
};
