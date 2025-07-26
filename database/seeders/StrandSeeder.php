<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Strand;

class StrandSeeder extends Seeder
{
    public function run(): void
    {
        $english = Subject::where('slug', 'english')->first();

        $strands = ['Grammar', 'Poetry', 'Oral Skills'];

        foreach ($strands as $strandName) {
            Strand::updateOrCreate(
                ['slug' => strtolower(str_replace(' ', '-', $strandName))],
                ['subject_id' => $english->id, 'name' => $strandName]
            );
        }
    }
}
