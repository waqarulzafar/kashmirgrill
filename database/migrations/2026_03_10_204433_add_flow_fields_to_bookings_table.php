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
            $table->string('booking_type', 20)->default('table')->after('phone');
            $table->string('status', 20)->default('pending')->after('booking_type');
            $table->string('phone_country_iso2', 5)->nullable()->after('phone');
            $table->foreignId('dine_in_slot_id')->nullable()->after('time')->constrained('dine_in_slots')->nullOnDelete();
            $table->string('special_occasion', 120)->nullable()->after('selected_menu');
            $table->string('payment_method', 40)->nullable()->after('special_occasion');
            $table->string('payment_status', 32)->default('pending')->after('payment_method');
            $table->boolean('marketing_opt_in')->default(false)->after('payment_status');

            $table->index(['booking_type', 'date', 'dine_in_slot_id'], 'bookings_type_date_slot_index');
            $table->index(['status', 'date'], 'bookings_status_date_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('bookings_type_date_slot_index');
            $table->dropIndex('bookings_status_date_index');
            $table->dropConstrainedForeignId('dine_in_slot_id');
            $table->dropColumn([
                'booking_type',
                'status',
                'phone_country_iso2',
                'special_occasion',
                'payment_method',
                'payment_status',
                'marketing_opt_in',
            ]);
        });
    }
};
