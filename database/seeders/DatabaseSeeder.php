<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create or update the test user to avoid duplicates
        User::updateOrCreate(
            ['email' => 'test@example.com'], // unique key
            [
                'name' => 'Test User',
                'password' => bcrypt('password123'), // set a password
            ]
        );

        // Call other seeders
        $this->call([
            OnlineCourseSeeder::class,
            CategorySeeder::class
        ]);
    }
}
