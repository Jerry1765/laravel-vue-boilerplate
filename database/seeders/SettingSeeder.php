<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate(
            ['key' => 'auth.registrations.enabled'],
            ['value' => '1']
        );

        // Media Settings
        Setting::updateOrCreate(
            ['key' => 'media.upload.max_size_kb'],
            ['value' => '5120']
        );
    }
}
