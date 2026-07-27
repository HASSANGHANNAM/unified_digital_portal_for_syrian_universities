<?php


namespace Database\Seeders\SeedersV2\UsersSeeders;

use App\Repositories\Contracts\PersonAttachmentRepositoryInterface;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class PersonAttachmentsSeederV2 extends Seeder
{
    public function __construct(
        private PersonAttachmentRepositoryInterface $personAttachmentsRepo,
    ) {}

    public function run(): void
    {
        $attachments = [

            // person_id = 7 (student.omar)
            ['person_id' => 7, 'name' => 'صورة عن الهوية', 'path' => '/attachments/id_7_identity.pdf'],
            ['person_id' => 7, 'name' => 'صورة شخصية', 'path' => '/attachments/id_7_photo.pdf'],
            ['person_id' => 7, 'name' => 'شهادة البكالوريا', 'path' => '/attachments/id_7_certificate.pdf'],

            // person_id = 8 (student.layla)
            ['person_id' => 8, 'name' => 'صورة عن الهوية', 'path' => '/attachments/id_8_identity.pdf'],
            ['person_id' => 8, 'name' => 'صورة شخصية', 'path' => '/attachments/id_8_photo.pdf'],
            ['person_id' => 8, 'name' => 'شهادة البكالوريا', 'path' => '/attachments/id_8_certificate.pdf'],

            // person_id = 9 (ahmad.ali)
            ['person_id' => 9, 'name' => 'صورة عن الهوية', 'path' => '/attachments/id_9_identity.pdf'],
            ['person_id' => 9, 'name' => 'صورة شخصية', 'path' => '/attachments/id_9_photo.pdf'],
            ['person_id' => 9, 'name' => 'شهادة البكالوريا', 'path' => '/attachments/id_9_certificate.pdf'],

            // person_id = 10 (fatima.hussein)
            ['person_id' => 10, 'name' => 'صورة عن الهوية', 'path' => '/attachments/id_10_identity.pdf'],
            ['person_id' => 10, 'name' => 'صورة شخصية', 'path' => '/attachments/id_10_photo.pdf'],
            ['person_id' => 10, 'name' => 'شهادة البكالوريا', 'path' => '/attachments/id_10_certificate.pdf'],

            // person_id = 11 (youssef.hamwi)
            ['person_id' => 11, 'name' => 'صورة عن الهوية', 'path' => '/attachments/id_11_identity.pdf'],
            ['person_id' => 11, 'name' => 'صورة شخصية', 'path' => '/attachments/id_11_photo.pdf'],
            ['person_id' => 11, 'name' => 'شهادة البكالوريا', 'path' => '/attachments/id_11_certificate.pdf'],

            // person_id = 12 (noura.hussein)
            ['person_id' => 12, 'name' => 'صورة عن الهوية', 'path' => '/attachments/id_12_identity.pdf'],
            ['person_id' => 12, 'name' => 'صورة شخصية', 'path' => '/attachments/id_12_photo.pdf'],
            ['person_id' => 12, 'name' => 'شهادة البكالوريا', 'path' => '/attachments/id_12_certificate.pdf'],

            // person_id = 18 (lina.azzam)
            ['person_id' => 18, 'name' => 'صورة عن الهوية', 'path' => '/attachments/id_18_identity.pdf'],
            ['person_id' => 18, 'name' => 'صورة شخصية', 'path' => '/attachments/id_18_photo.pdf'],
            ['person_id' => 18, 'name' => 'شهادة البكالوريا', 'path' => '/attachments/id_18_certificate.pdf'],

            // person_id = 19 (rami.khatib)
            ['person_id' => 19, 'name' => 'صورة عن الهوية', 'path' => '/attachments/id_19_identity.pdf'],
            ['person_id' => 19, 'name' => 'صورة شخصية', 'path' => '/attachments/id_19_photo.pdf'],
            ['person_id' => 19, 'name' => 'شهادة البكالوريا', 'path' => '/attachments/id_19_certificate.pdf'],

            // person_id = 22 (duaa.sheikh)
            ['person_id' => 22, 'name' => 'صورة عن الهوية', 'path' => '/attachments/id_22_identity.pdf'],
            ['person_id' => 22, 'name' => 'صورة شخصية', 'path' => '/attachments/id_22_photo.pdf'],
            ['person_id' => 22, 'name' => 'شهادة البكالوريا', 'path' => '/attachments/id_22_certificate.pdf'],

            // person_id = 24 (maisa.hammoud)
            ['person_id' => 24, 'name' => 'صورة عن الهوية', 'path' => '/attachments/id_24_identity.pdf'],
            ['person_id' => 24, 'name' => 'صورة شخصية', 'path' => '/attachments/id_24_photo.pdf'],
            ['person_id' => 24, 'name' => 'شهادة البكالوريا', 'path' => '/attachments/id_24_certificate.pdf'],

            // person_id = 25 (basel.nouri)
            ['person_id' => 25, 'name' => 'صورة عن الهوية', 'path' => '/attachments/id_25_identity.pdf'],
            ['person_id' => 25, 'name' => 'صورة شخصية', 'path' => '/attachments/id_25_photo.pdf'],
            ['person_id' => 25, 'name' => 'شهادة البكالوريا', 'path' => '/attachments/id_25_certificate.pdf'],

        ];
        foreach ($attachments as $attachment) {

            DB::transaction(function () use ($attachment) {

                $this->personAttachmentsRepo->create($attachment);
            });
        }
    }
}
