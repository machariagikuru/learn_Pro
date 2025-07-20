<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QuestionsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('questions')->insert([
            [
                'id'            => 1,
                'quiz_id'       => 1,
                'question_text' => 'What does HTML stand for?',
                'option_a'      => 'Hyper Text Markup Language',
                'option_b'      => 'High Text Machine Learning',
                'option_c'      => 'Hyperlink and Text Management Language',
                'option_d'      => 'Home Tool Markup Language',
                'correct_option'=> 'A',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'id'            => 2,
                'quiz_id'       => 1,
                'question_text' => 'Which HTML tag is used to define an unordered list?',
                'option_a'      => '<ul>',
                'option_b'      => '<ol>',
                'option_c'      => '<li>',
                'option_d'      => '<list>',
                'correct_option'=> 'A',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'id'            => 3,
                'quiz_id'       => 1,
                'question_text' => 'What is the correct HTML element for inserting a line break?',
                'option_a'      => '<br>',
                'option_b'      => '<lb>',
                'option_c'      => '<break>',
                'option_d'      => '<newline>',
                'correct_option'=> 'A',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'id'            => 4,
                'quiz_id'       => 2,
                'question_text' => 'Which symbol is used to start a variable in PHP?',
                'option_a'      => '$',
                'option_b'      => '@',
                'option_c'      => '#',
                'option_d'      => '&',
                'correct_option'=> 'A',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'id'            => 5,
                'quiz_id'       => 2,
                'question_text' => 'Which function is used to output text in PHP?',
                'option_a'      => 'echo',
                'option_b'      => 'print',
                'option_c'      => 'display',
                'option_d'      => 'show',
                'correct_option'=> 'A',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'id'            => 6,
                'quiz_id'       => 2,
                'question_text' => 'Which superglobal variable holds information about GET requests?',
                'option_a'      => '$_POST',
                'option_b'      => '$_GET',
                'option_c'      => '$_REQUEST',
                'option_d'      => '$_SESSION',
                'correct_option'=> 'B',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
        ]);
    }
}
