<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->index(['user_id', 'date'], 'bookings_user_date_index');
        });

        $usersByEmail = DB::table('users')->pluck('id', 'email');

        DB::table('bookings')
            ->select('id', 'email')
            ->whereNull('user_id')
            ->orderBy('id')
            ->chunkById(100, function ($bookings) use ($usersByEmail): void {
                foreach ($bookings as $booking) {
                    $userId = $usersByEmail[$booking->email] ?? null;

                    if ($userId === null) {
                        continue;
                    }

                    DB::table('bookings')
                        ->where('id', $booking->id)
                        ->update(['user_id' => $userId]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('bookings_user_date_index');
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
