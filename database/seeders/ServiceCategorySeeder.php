<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Electrician', 'Plumber', 'House Cleaning', 'AC Repair & Service',
            'Carpenter', 'Painter', 'Home Tutor', 'Pest Control',
            'Appliance Repair', 'Gardening & Landscaping',
        ];

        foreach ($categories as $name) {
            ServiceCategory::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name'   => $name,
                    'status' => 'active',
                ]
            );
        }
    }
}
