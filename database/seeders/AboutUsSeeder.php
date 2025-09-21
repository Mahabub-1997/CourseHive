<?php

namespace Database\Seeders;

use App\Models\AboutUs;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class AboutUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $aboutUsRecords = [
            [
                'title'       => 'Our Mission',
                'description' => 'We aim to provide world-class training and resources to healthcare professionals worldwide.',
                'image'       => null, // You can put something like 'about/mission.jpg'
            ],
            [
                'title'       => 'Our Vision',
                'description' => 'To become the leading online platform for medical and healthcare training by empowering learners with modern education tools.',
                'image'       => null,
            ],
            [
                'title'       => 'Our Values',
                'description' => 'Integrity, dedication, and a passion for continuous learning drive everything we do.',
                'image'       => null,
            ],
        ];

        foreach ($aboutUsRecords as $record) {
            AboutUs::create($record);
        }
    }
}
