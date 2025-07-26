<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Note;
use App\Models\SubStrand;

class NoteSeeder extends Seeder
{
    public function run(): void
    {
        // 🌟 Grade 10: Grammar > Nouns
        $nouns = SubStrand::where('slug', 'nouns')->first();

        Note::create([
            'sub_strand_id' => $nouns->id,
            'title' => 'Understanding Nouns (Grade 10)',
            'grade' => '10',
            'content' => <<<EOT
### What is a Noun?

A **noun** is a word that names a person, place, thing, idea, or quality. Nouns can be categorized into:

1. **Proper Nouns** – Names of specific people or places (e.g. Nairobi, Jane).
2. **Common Nouns** – General names (e.g. city, girl).
3. **Abstract Nouns** – Ideas or qualities (e.g. freedom, courage).
4. **Concrete Nouns** – Things you can touch or see (e.g. chair, dog).
5. **Collective Nouns** – Groups of things/people (e.g. team, herd).

### Noun Functions in a Sentence

Nouns can be:
- Subjects: _The **dog** barked._
- Objects: _I saw a **cat**._
- Subject Complements: _He is a **teacher**._

### Examples in Context:

- The **jury** made its decision.
- She admires **kindness**.
- **Tom** bought a new **laptop**.

### Practice Tip:

Underline all nouns in this sentence:  
_The excited children ran across the wide field to the old farmhouse._

EOT
        ]);

        // 🌟 Grade 11: Poetry > Poetic Devices
        $poeticDevices = SubStrand::where('slug', 'poetic-devices')->first();

        Note::create([
            'sub_strand_id' => $poeticDevices->id,
            'title' => 'Poetic Devices (Grade 11)',
            'grade' => '11',
            'content' => <<<EOT
### What Are Poetic Devices?

**Poetic devices** are tools poets use to enhance the meaning, sound, and impact of their poems. Some common ones include:

1. **Alliteration** – Repetition of initial consonant sounds:  
   _"She sells sea shells by the seashore."_

2. **Simile** – Comparison using 'like' or 'as':  
   _"Her eyes were like stars."_

3. **Metaphor** – Direct comparison without 'like' or 'as':  
   _"Time is a thief."_

4. **Personification** – Giving human traits to non-human things:  
   _"The wind whispered through the trees."_

5. **Imagery** – Use of vivid descriptions to appeal to senses:  
   _"The golden sun poured through the dusty window."_

### Why They Matter

Poetic devices help:
- Create rhythm and musicality
- Enhance imagery and mood
- Convey deeper meanings

**Activity:**  
Find a poem of your choice and identify at least 3 poetic devices used in it.

EOT
        ]);

        // 🌟 Grade 12: Oral Skills > Pronunciation
        $pronunciation = SubStrand::where('slug', 'pronunciation')->first();

        Note::create([
            'sub_strand_id' => $pronunciation->id,
            'title' => 'Mastering Pronunciation (Grade 12)',
            'grade' => '12',
            'content' => <<<EOT
### Why Pronunciation Matters

Pronunciation affects how clearly you communicate. Proper pronunciation improves comprehension, credibility, and fluency in speech.

### Key Concepts:

1. **Syllables**: Words are broken into syllables.  
   E.g., _com/pu/ter_ has 3 syllables.

2. **Stress**: One syllable is more prominent than others.  
   E.g., _PHOtograph_, phoTOgrapher, photoGRAphic

3. **Intonation**: The rise and fall of voice tone.  
   - Rising for yes/no questions: _"Are you okay?"_  
   - Falling for statements: _"I’m fine."_

4. **Consonant & Vowel Sounds**:  
   Master individual sounds to avoid confusion (e.g., /l/ vs /r/).

### Exercises:

- Record yourself reading aloud.
- Listen to BBC Learning English or TED Talks.
- Practice tongue twisters:  
  _"Red lorry, yellow lorry."_  

EOT
        ]);
    }
}
