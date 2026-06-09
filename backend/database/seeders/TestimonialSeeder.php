<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'author_name'  => 'Priya Daimary',
                'author_email' => 'priya@example.com',
                'content'      => 'Aakhikh Church has been a place of healing and restoration for me and my family. The Word of God preached here changed our lives completely.',
                'status'       => 'approved',
                'reviewed_at'  => now(),
            ],
            [
                'author_name'  => 'Raju Baglary',
                'author_email' => null,
                'content'      => 'I came to this church broken and hopeless. Through the prayers of the congregation and the love of Christ shown here, I found new purpose and joy.',
                'status'       => 'approved',
                'reviewed_at'  => now(),
            ],
            [
                'author_name'  => 'Sunita Mushahary',
                'author_email' => 'sunita@example.com',
                'content'      => 'The youth ministry here truly impacted my children. They are now walking with the Lord and are full of purpose. Thank God for this church.',
                'status'       => 'approved',
                'reviewed_at'  => now(),
            ],
            [
                'author_name'  => 'David Narzary',
                'author_email' => 'david@example.com',
                'content'      => 'I was healed through prayer at Aakhikh Church. After years of illness, God touched me and I am well today. All glory to Jesus.',
                'status'       => 'approved',
                'reviewed_at'  => now(),
            ],
            [
                'author_name'  => 'Anita Brahma',
                'author_email' => null,
                'content'      => 'This church helped me reconnect with my faith after many difficult years. The love and warmth of the members here is truly Christ-like.',
                'status'       => 'pending',
                'reviewed_at'  => null,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            DB::table('testimonials')->insertOrIgnore([
                ...$testimonial,
                'id'         => (string) Str::uuid(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}