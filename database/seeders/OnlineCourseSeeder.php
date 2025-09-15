<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\OnlineCourse;

class OnlineCourseSeeder extends Seeder
{
    public function run(): void
    {
        // Step 1: Create unique categories
        $categoryData = [
            ['name' => 'Electronics', 'description' => 'All kinds of electronic items'],
            ['name' => 'Fashion', 'description' => 'Clothes, shoes, and accessories'],
            ['name' => 'Books', 'description' => 'Books of all kinds'],
            ['name' => 'Sports', 'description' => 'Sports equipment and goods'],
            ['name' => 'Music', 'description' => 'Musical instruments and accessories'],
        ];

        $categories = [];
        foreach ($categoryData as $cat) {
            // firstOrCreate avoids duplicates
            $categories[] = Category::firstOrCreate(
                ['name' => $cat['name']],
                ['description' => $cat['description']]
            );
        }

        // Step 2: Create courses and assign random categories
        for ($i = 1; $i <= 10; $i++) {
            $category = $categories[array_rand($categories)]; // pick random category

            OnlineCourse::create([
                'title' => 'Course ' . $i,
                'description' => 'This is the description for Course ' . $i,
                'price' => rand(0, 1000),
                'level' => ['Beginner', 'Intermediate', 'Advanced'][array_rand(['Beginner', 'Intermediate', 'Advanced'])],
                'duration' => rand(1, 12) . ' weeks',
                'language' => ['English', 'Bangla', 'Spanish'][array_rand(['English', 'Bangla', 'Spanish'])],
                'image' => 'courses/course_' . $i . '.png',
                'course_type' => ['free', 'paid'][array_rand(['free', 'paid'])],
                'user_id' => 1,
                'rating_id' => 1,
                'category_id' => $category->id,
                'created_by' => 1,
                'updated_by' => 1,
            ]);
        }
    }
}
