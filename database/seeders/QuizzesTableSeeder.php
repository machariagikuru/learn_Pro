<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QuizzesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('quizzes')->insert([
            [
                'id'           => 1,
                'title'        => 'Html Quiz 1',
                'chapter_id'   => 1,
                'time_limit'   => 30,
                'passing_score'=> 70,
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'id'           => 2,
                'title'        => 'Php Quiz 1',
                'chapter_id'   => 4,
                'time_limit'   => 40,
                'passing_score'=> 75,
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
        ]);
    }
}
