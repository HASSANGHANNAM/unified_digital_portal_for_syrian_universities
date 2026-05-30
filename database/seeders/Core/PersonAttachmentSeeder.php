<?php

namespace Database\Seeders\Core;

use Illuminate\Database\Seeder;
use App\Models\Person;
use App\Models\PersonAttachment;

class PersonAttachmentSeeder extends Seeder
{
    public function run(): void
    {
        // المرفقات مرتبطة بأشخاص محددين (اخترت أول شخصين مثلاً)
        $persons = Person::take(2)->get();
        if ($persons->count() >= 2) {
            $attachments = [
                ['person_id' => $persons[0]->id, 'name' => 'صورة شخصية', 'path' => '/attachments/photo_1.jpg'],
                ['person_id' => $persons[1]->id, 'name' => 'نسخة عن الهوية', 'path' => '/attachments/id_2.pdf'],
            ];
            foreach ($attachments as $att) {
                PersonAttachment::firstOrCreate([
                    'person_id' => $att['person_id'],
                    'name' => $att['name'],
                ], [
                    'path' => $att['path'],
                ]);
            }
        }
    }
}