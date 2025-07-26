<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesSeeder extends Seeder
{
    public function run()
    {
        DB::table('courses')->insert([
            [
                'id' => 1,
                'image' => '1742737648_Html.jpg',
                'short_video' => '1742737648_HTML - Introduction - W3Schools.com.mp4',
                'video_url' => null,
                'title' => 'HTML Basics',
                'long_title' => 'Introduction to HTML: Building the Foundation for Web Development',
                'description' => 'Learn the fundamentals of HTML, the markup language used to create and structure web pages. In this course, you\'ll explore essential tags, semantic elements, forms, and best practices to build accessible and responsive websites.',
                'duration' => 7,
                'price' => 200.00,
                'user_id' => 6,
                'category_id' => 2,
                'created_at' => '2025-02-28 10:30:11',
                'updated_at' => '2025-03-23 11:47:28',
                'rate' => 4.5,
                'why_choose_this_course' => 'why_choose_this_course : HTML is the backbone of every web page. This course offers clear, practical instruction with real-world examples, making it perfect for beginners looking to start their journey in web development.'
            ],
            [
                'id' => 2,
                'image' => '1742737668_photo.jpg',
                'short_video' => '1742737668_PHP in 100 Seconds.mp4',
                'video_url' => null,
                'title' => 'PHP',
                'long_title' => 'Introduction to PHP: Building the Foundation for Server-Side Web Development',
                'description' => 'Learn the fundamentals of PHP, the popular server-side scripting language used to develop dynamic websites and web applications. In this course, you\'ll explore PHP syntax, variables, control structures, functions, and how to interact with databases. You\'ll also learn best practices for writing secure and efficient code.',
                'duration' => 15,
                'price' => 500.00,
                'user_id' => 6,
                'category_id' => 2,
                'created_at' => '2025-02-28 11:14:42',
                'updated_at' => '2025-03-23 11:47:48',
                'rate' => 4.6,
                'why_choose_this_course' => 'PHP is a cornerstone of modern web development. This course offers clear, practical instruction with real-world examples, making it perfect for beginners looking to start their journey in server-side programming and dynamic web development.'
            ],
            [
                'id' => 3,
                'image' => '1742737852_Adobe.jpg',
                'short_video' => '1742737852_short video.mp4',
                'video_url' => null,
                'title' => 'Adobe Illustrator Mastery',
                'long_title' => 'Adobe Certified Program in Advanced Digital Illustration',
                'description' => 'This course provides a comprehensive overview of Adobe Illustrator, covering essential tools, advanced techniques, and creative projects that empower you to produce professional-grade vector artwork. From logo design to intricate illustrations, you\'ll build a robust foundation and expand your creative expertise.',
                'duration' => 2,
                'price' => 250.00,
                'user_id' => 6,
                'category_id' => 1,
                'created_at' => '2025-03-23 11:50:52',
                'updated_at' => '2025-03-23 11:50:52',
                'rate' => 4.0,
                'why_choose_this_course' => 'Gain industry-recognized skills and certification that accelerate your creative career in digital design.'
            ],
            [
                'id' => 4,
                'image' => '1742738067_IBM Certificate.jpg',
                'short_video' => '1742738067_Short Video.mp4',
                'video_url' => null,
                'title' => 'IBM Certificate in AI',
                'long_title' => 'IBM Certified Program in Advanced Artificial Intelligence',
                'description' => 'This course offers a comprehensive curriculum covering fundamental concepts, advanced techniques, and practical applications in artificial intelligence. It includes interactive modules, hands-on projects, and industry insights to equip you with the skills required for a successful career in AI.',
                'duration' => 3,
                'price' => 1200.00,
                'user_id' => 6,
                'category_id' => 2,
                'created_at' => '2025-03-23 11:54:27',
                'updated_at' => '2025-03-23 11:54:27',
                'rate' => 4.5,
                'why_choose_this_course' => 'Gain a globally recognized certification that accelerates your career in AI.'
            ],
        ]);
    }
}
