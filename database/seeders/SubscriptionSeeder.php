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
            ['email' => 'rinab@example.com'],
            ['email' => 'nirob@example.com'],
            ['email' => 'shehab@example.com'],
            ['email' => 'fahim@example.com'],
            ['email' => 'raihan@example.com'],
        ];

        foreach ($subscriptions as $sub) {
            Subscription::create($sub);
        }
    }
}
