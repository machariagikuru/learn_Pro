<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            [
                'id'            => 1,
                'category_name' => 'Design',
                'created_at'    => null,
                'updated_at'    => '2025-02-18 11:59:00',
            ],
            [
                'id'            => 2,
                'category_name' => 'Software Development',
                'created_at'    => null,
                'updated_at'    => null,
            ],
            [
                'id'            => 3,
                'category_name' => 'Marketing',
                'created_at'    => null,
                'updated_at'    => null,
            ],
            [
                'id'            => 4,
                'category_name' => 'Networking',
                'created_at'    => null,
                'updated_at'    => null,
            ],
            [
                'id'            => 5,
                'category_name' => 'Security',
                'created_at'    => null,
                'updated_at'    => null,
            ],
            [
                'id'            => 6,
                'category_name' => 'Business',
                'created_at'    => null,
                'updated_at'    => null,
            ],
        ]);
    }
}
