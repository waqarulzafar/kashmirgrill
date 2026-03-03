<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        $usedSlugs = [];

        DB::table('menu_items')
            ->select('id', 'name')
            ->orderBy('id')
            ->get()
            ->each(function (object $item) use (&$usedSlugs): void {
                $base = Str::slug($item->name) ?: 'menu-item';
                $slug = $base;
                $suffix = 2;

                while (in_array($slug, $usedSlugs, true)) {
                    $slug = $base . '-' . $suffix;
                    $suffix++;
                }

                $usedSlugs[] = $slug;

                DB::table('menu_items')
                    ->where('id', $item->id)
                    ->update(['slug' => $slug]);
            });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
