<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::query()->updateOrCreate(
            ['key' => SiteSetting::KEY_DEFAULT_LOCALE],
            ['value' => config('app.locale')]
        );
    }
}
