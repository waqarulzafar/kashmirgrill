<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('menu_items')) {
            return;
        }

        $usedSlugs = DB::table('menu_items')
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->pluck('slug')
            ->all();

        DB::table('menu_items')
            ->select('id', 'name')
            ->where(function ($query): void {
                $query->whereNull('slug')
                    ->orWhere('slug', '');
            })
            ->orderBy('id')
            ->get()
            ->each(function (object $item) use (&$usedSlugs): void {
                $base = Str::slug($item->name) ?: 'menu-item';
                $slug = $base;
                $suffix = 2;

                while (in_array($slug, $usedSlugs, true)) {
                    $slug = $base.'-'.$suffix;
                    $suffix++;
                }

                $usedSlugs[] = $slug;

                DB::table('menu_items')
                    ->where('id', $item->id)
                    ->update(['slug' => $slug]);
            });
    }

    public function down(): void
    {
        //
    }
};
