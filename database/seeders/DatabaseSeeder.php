<?php

namespace Database\Seeders;

use App\Models\BusinessSetting;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        if (!BusinessSetting::exists()) {
            BusinessSetting::create([
                'name' => 'Demo Company',
                'email' => 'hello@example.com',
                'currency_code' => 'USD',
                'timezone' => 'UTC',
                'footer_text' => 'Powered by Demo Company',
            ]);
        }
    }
}
