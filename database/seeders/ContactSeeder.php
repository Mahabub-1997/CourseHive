<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contacts = [
            [
                'name'        => 'Alice Johnson',
                'email'       => 'alice.johnson@example.com',
                'description' => 'I want to learn more about your healthcare courses.',
            ],
            [
                'name'        => 'Bob Smith',
                'email'       => 'bob.smith@example.com',
                'description' => 'Can I get a discount for group enrollments?',
            ],
            [
                'name'        => 'Charlie Brown',
                'email'       => 'charlie.brown@example.com',
                'description' => 'I need help accessing my account.',
            ],
            [
                'name'        => 'Diana Prince',
                'email'       => 'diana.prince@example.com',
                'description' => 'I am interested in corporate training programs.',
            ],
            [
                'name'        => 'Ethan Hunt',
                'email'       => 'ethan.hunt@example.com',
                'description' => 'Please provide more info about certification timelines.',
            ],
        ];

        foreach ($contacts as $contact) {
            Contact::create($contact);
        }
    }
}

