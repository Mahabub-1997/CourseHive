<?php

namespace Database\Seeders;

use App\Models\Subscription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subscriptions = [
            ['email' => 'john.doe@example.com'],
            ['email' => 'jane.smith@example.com'],
            ['email' => 'michael.brown@example.com'],
            ['email' => 'susan.williams@example.com'],
            ['email' => 'david.johnson@example.com'],
        ];

        foreach ($subscriptions as $sub) {
            Subscription::create($sub);
        }
    }
}
