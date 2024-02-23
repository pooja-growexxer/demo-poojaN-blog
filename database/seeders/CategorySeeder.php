<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Laravel',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Node JS',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Python',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Java',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'React',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Angular',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Flutter',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Sales Force',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        Category::insert($data);
    }
}
