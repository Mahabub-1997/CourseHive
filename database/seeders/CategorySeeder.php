<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Electronics',
                'description' => 'All kinds of electronic items',
            ],
            [
                'name' => 'Fashion',
                'description' => 'Clothing, shoes, accessories',
            ],
            [
                'name' => 'Books',
                'description' => 'Educational and leisure books',
            ],
            [
                'name' => 'Sports',
                'description' => 'Sports equipment and apparel',
            ],
        ];

        foreach ($categories as $cat) {
            // Prevent duplicate insertion
            Category::firstOrCreate(
                ['name' => $cat['name']],
                ['description' => $cat['description']]
            );
        }
    }
}
