<?php

namespace Database\Seeders;

use App\Models\BusinessSetting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
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
