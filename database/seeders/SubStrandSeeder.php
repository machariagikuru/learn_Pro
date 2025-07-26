<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Strand;
use App\Models\SubStrand;

class SubStrandSeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            'grammar' => ['Nouns', 'Verbs', 'Adjectives'],
            'poetry' => ['Introduction to Poetry', 'Poetic Devices'],
            'oral-skills' => ['Listening Skills', 'Pronunciation']
        ];

        foreach ($map as $strandSlug => $subStrands) {
            $strand = Strand::where('slug', $strandSlug)->first();

            foreach ($subStrands as $sub) {
                SubStrand::updateOrCreate(
                    ['slug' => strtolower(str_replace(' ', '-', $sub))],
                    ['strand_id' => $strand->id, 'name' => $sub]
                );
            }
        }
    }
}
