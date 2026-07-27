<?php

namespace Database\Seeders\Requests;

use Illuminate\Database\Seeder;
use App\Models\RequestType;
use App\Models\RequestTypeAvailability;
use App\Models\RequestTypeMedia;

class RequestTypeAvailabilitySeeder extends Seeder
{
    public function run(): void
    {
        $data =
            [
                // ========== جامعة دمشق (college_id 1-7) ==========
                // 1. كلية الهندسة المعلوماتية (عملية)
                ['is_available' => true, 'request_type_id' => 1, 'college_id' => 1],
                ['is_available' => true, 'request_type_id' => 2, 'college_id' => 1],
                ['is_available' => true, 'request_type_id' => 3, 'college_id' => 1],
                ['is_available' => true, 'request_type_id' => 4, 'college_id' => 1],
                ['is_available' => true, 'request_type_id' => 5, 'college_id' => 1],
                ['is_available' => true, 'request_type_id' => 6, 'college_id' => 1],
                ['is_available' => true, 'request_type_id' => 7, 'college_id' => 1],
                ['is_available' => true, 'request_type_id' => 8, 'college_id' => 1],
                ['is_available' => true, 'request_type_id' => 9, 'college_id' => 1], // إعادة عملي
                ['is_available' => true, 'request_type_id' => 10, 'college_id' => 1],
                ['is_available' => true, 'request_type_id' => 11, 'college_id' => 1],

                // 2. كلية الطب البشري (عملية)
                ['is_available' => true, 'request_type_id' => 1, 'college_id' => 2],
                ['is_available' => true, 'request_type_id' => 2, 'college_id' => 2],
                ['is_available' => true, 'request_type_id' => 3, 'college_id' => 2],
                ['is_available' => true, 'request_type_id' => 4, 'college_id' => 2],
                ['is_available' => true, 'request_type_id' => 5, 'college_id' => 2],
                ['is_available' => true, 'request_type_id' => 6, 'college_id' => 2],
                ['is_available' => true, 'request_type_id' => 7, 'college_id' => 2],
                ['is_available' => true, 'request_type_id' => 8, 'college_id' => 2],
                ['is_available' => true, 'request_type_id' => 9, 'college_id' => 2],
                ['is_available' => true, 'request_type_id' => 10, 'college_id' => 2],
                ['is_available' => true, 'request_type_id' => 11, 'college_id' => 2],

                // 3. كلية طب الأسنان (عملية)
                ['is_available' => true, 'request_type_id' => 1, 'college_id' => 3],
                ['is_available' => true, 'request_type_id' => 2, 'college_id' => 3],
                ['is_available' => true, 'request_type_id' => 3, 'college_id' => 3],
                ['is_available' => true, 'request_type_id' => 4, 'college_id' => 3],
                ['is_available' => true, 'request_type_id' => 5, 'college_id' => 3],
                ['is_available' => true, 'request_type_id' => 6, 'college_id' => 3],
                ['is_available' => true, 'request_type_id' => 7, 'college_id' => 3],
                ['is_available' => true, 'request_type_id' => 8, 'college_id' => 3],
                ['is_available' => true, 'request_type_id' => 9, 'college_id' => 3],
                ['is_available' => true, 'request_type_id' => 10, 'college_id' => 3],
                ['is_available' => true, 'request_type_id' => 11, 'college_id' => 3],

                // 4. كلية الصيدلة (عملية)
                ['is_available' => true, 'request_type_id' => 1, 'college_id' => 4],
                ['is_available' => true, 'request_type_id' => 2, 'college_id' => 4],
                ['is_available' => true, 'request_type_id' => 3, 'college_id' => 4],
                ['is_available' => true, 'request_type_id' => 4, 'college_id' => 4],
                ['is_available' => true, 'request_type_id' => 5, 'college_id' => 4],
                ['is_available' => true, 'request_type_id' => 6, 'college_id' => 4],
                ['is_available' => true, 'request_type_id' => 7, 'college_id' => 4],
                ['is_available' => true, 'request_type_id' => 8, 'college_id' => 4],
                ['is_available' => true, 'request_type_id' => 9, 'college_id' => 4],
                ['is_available' => true, 'request_type_id' => 10, 'college_id' => 4],
                ['is_available' => true, 'request_type_id' => 11, 'college_id' => 4],

                // 5. كلية الآداب (نظرية)
                ['is_available' => true, 'request_type_id' => 1, 'college_id' => 5],
                ['is_available' => true, 'request_type_id' => 2, 'college_id' => 5],
                ['is_available' => true, 'request_type_id' => 3, 'college_id' => 5],
                ['is_available' => true, 'request_type_id' => 4, 'college_id' => 5],
                ['is_available' => true, 'request_type_id' => 5, 'college_id' => 5],
                ['is_available' => true, 'request_type_id' => 6, 'college_id' => 5],
                ['is_available' => true, 'request_type_id' => 7, 'college_id' => 5],
                ['is_available' => true, 'request_type_id' => 8, 'college_id' => 5],
                ['is_available' => false, 'request_type_id' => 9, 'college_id' => 5], // لا يوجد عملي
                ['is_available' => true, 'request_type_id' => 10, 'college_id' => 5],
                ['is_available' => true, 'request_type_id' => 11, 'college_id' => 5],

                // 6. كلية الاقتصاد (نظرية)
                ['is_available' => true, 'request_type_id' => 1, 'college_id' => 6],
                ['is_available' => true, 'request_type_id' => 2, 'college_id' => 6],
                ['is_available' => true, 'request_type_id' => 3, 'college_id' => 6],
                ['is_available' => true, 'request_type_id' => 4, 'college_id' => 6],
                ['is_available' => true, 'request_type_id' => 5, 'college_id' => 6],
                ['is_available' => true, 'request_type_id' => 6, 'college_id' => 6],
                ['is_available' => true, 'request_type_id' => 7, 'college_id' => 6],
                ['is_available' => true, 'request_type_id' => 8, 'college_id' => 6],
                ['is_available' => false, 'request_type_id' => 9, 'college_id' => 6],
                ['is_available' => true, 'request_type_id' => 10, 'college_id' => 6],
                ['is_available' => true, 'request_type_id' => 11, 'college_id' => 6],

                // 7. كلية العلوم (عملية - بها معامل)
                ['is_available' => true, 'request_type_id' => 1, 'college_id' => 7],
                ['is_available' => true, 'request_type_id' => 2, 'college_id' => 7],
                ['is_available' => true, 'request_type_id' => 3, 'college_id' => 7],
                ['is_available' => true, 'request_type_id' => 4, 'college_id' => 7],
                ['is_available' => true, 'request_type_id' => 5, 'college_id' => 7],
                ['is_available' => true, 'request_type_id' => 6, 'college_id' => 7],
                ['is_available' => true, 'request_type_id' => 7, 'college_id' => 7],
                ['is_available' => true, 'request_type_id' => 8, 'college_id' => 7],
                ['is_available' => true, 'request_type_id' => 9, 'college_id' => 7],
                ['is_available' => true, 'request_type_id' => 10, 'college_id' => 7],
                ['is_available' => true, 'request_type_id' => 11, 'college_id' => 7],

                // ========== جامعة حمص (college_id 8-13) ==========
                // 8. كلية الهندسة المعلوماتية (عملية)
                ['is_available' => true, 'request_type_id' => 1, 'college_id' => 8],
                ['is_available' => true, 'request_type_id' => 2, 'college_id' => 8],
                ['is_available' => true, 'request_type_id' => 3, 'college_id' => 8],
                ['is_available' => true, 'request_type_id' => 4, 'college_id' => 8],
                ['is_available' => true, 'request_type_id' => 5, 'college_id' => 8],
                ['is_available' => true, 'request_type_id' => 6, 'college_id' => 8],
                ['is_available' => true, 'request_type_id' => 7, 'college_id' => 8],
                ['is_available' => true, 'request_type_id' => 8, 'college_id' => 8],
                ['is_available' => true, 'request_type_id' => 9, 'college_id' => 8],
                ['is_available' => true, 'request_type_id' => 10, 'college_id' => 8],
                ['is_available' => true, 'request_type_id' => 11, 'college_id' => 8],

                // 9. كلية الطب البشري (عملية)
                ['is_available' => true, 'request_type_id' => 1, 'college_id' => 9],
                ['is_available' => true, 'request_type_id' => 2, 'college_id' => 9],
                ['is_available' => true, 'request_type_id' => 3, 'college_id' => 9],
                ['is_available' => true, 'request_type_id' => 4, 'college_id' => 9],
                ['is_available' => true, 'request_type_id' => 5, 'college_id' => 9],
                ['is_available' => true, 'request_type_id' => 6, 'college_id' => 9],
                ['is_available' => true, 'request_type_id' => 7, 'college_id' => 9],
                ['is_available' => true, 'request_type_id' => 8, 'college_id' => 9],
                ['is_available' => true, 'request_type_id' => 9, 'college_id' => 9],
                ['is_available' => true, 'request_type_id' => 10, 'college_id' => 9],
                ['is_available' => true, 'request_type_id' => 11, 'college_id' => 9],

                // 10. كلية طب الأسنان (عملية)
                ['is_available' => true, 'request_type_id' => 1, 'college_id' => 10],
                ['is_available' => true, 'request_type_id' => 2, 'college_id' => 10],
                ['is_available' => true, 'request_type_id' => 3, 'college_id' => 10],
                ['is_available' => true, 'request_type_id' => 4, 'college_id' => 10],
                ['is_available' => true, 'request_type_id' => 5, 'college_id' => 10],
                ['is_available' => true, 'request_type_id' => 6, 'college_id' => 10],
                ['is_available' => true, 'request_type_id' => 7, 'college_id' => 10],
                ['is_available' => true, 'request_type_id' => 8, 'college_id' => 10],
                ['is_available' => true, 'request_type_id' => 9, 'college_id' => 10],
                ['is_available' => true, 'request_type_id' => 10, 'college_id' => 10],
                ['is_available' => true, 'request_type_id' => 11, 'college_id' => 10],

                // 11. كلية الصيدلة (عملية)
                ['is_available' => true, 'request_type_id' => 1, 'college_id' => 11],
                ['is_available' => true, 'request_type_id' => 2, 'college_id' => 11],
                ['is_available' => true, 'request_type_id' => 3, 'college_id' => 11],
                ['is_available' => true, 'request_type_id' => 4, 'college_id' => 11],
                ['is_available' => true, 'request_type_id' => 5, 'college_id' => 11],
                ['is_available' => true, 'request_type_id' => 6, 'college_id' => 11],
                ['is_available' => true, 'request_type_id' => 7, 'college_id' => 11],
                ['is_available' => true, 'request_type_id' => 8, 'college_id' => 11],
                ['is_available' => true, 'request_type_id' => 9, 'college_id' => 11],
                ['is_available' => true, 'request_type_id' => 10, 'college_id' => 11],
                ['is_available' => true, 'request_type_id' => 11, 'college_id' => 11],

                // 12. كلية الآداب (نظرية)
                ['is_available' => true, 'request_type_id' => 1, 'college_id' => 12],
                ['is_available' => true, 'request_type_id' => 2, 'college_id' => 12],
                ['is_available' => true, 'request_type_id' => 3, 'college_id' => 12],
                ['is_available' => true, 'request_type_id' => 4, 'college_id' => 12],
                ['is_available' => true, 'request_type_id' => 5, 'college_id' => 12],
                ['is_available' => true, 'request_type_id' => 6, 'college_id' => 12],
                ['is_available' => true, 'request_type_id' => 7, 'college_id' => 12],
                ['is_available' => true, 'request_type_id' => 8, 'college_id' => 12],
                ['is_available' => false, 'request_type_id' => 9, 'college_id' => 12],
                ['is_available' => true, 'request_type_id' => 10, 'college_id' => 12],
                ['is_available' => true, 'request_type_id' => 11, 'college_id' => 12],

                // 13. كلية الاقتصاد (نظرية)
                ['is_available' => true, 'request_type_id' => 1, 'college_id' => 13],
                ['is_available' => true, 'request_type_id' => 2, 'college_id' => 13],
                ['is_available' => true, 'request_type_id' => 3, 'college_id' => 13],
                ['is_available' => true, 'request_type_id' => 4, 'college_id' => 13],
                ['is_available' => true, 'request_type_id' => 5, 'college_id' => 13],
                ['is_available' => true, 'request_type_id' => 6, 'college_id' => 13],
                ['is_available' => true, 'request_type_id' => 7, 'college_id' => 13],
                ['is_available' => true, 'request_type_id' => 8, 'college_id' => 13],
                ['is_available' => false, 'request_type_id' => 9, 'college_id' => 13],
                ['is_available' => true, 'request_type_id' => 10, 'college_id' => 13],
                ['is_available' => true, 'request_type_id' => 11, 'college_id' => 13],

                // ========== جامعة حلب (college_id 14-15) ==========
                // 14. كلية الآداب (نظرية)
                ['is_available' => true, 'request_type_id' => 1, 'college_id' => 14],
                ['is_available' => true, 'request_type_id' => 2, 'college_id' => 14],
                ['is_available' => true, 'request_type_id' => 3, 'college_id' => 14],
                ['is_available' => true, 'request_type_id' => 4, 'college_id' => 14],
                ['is_available' => true, 'request_type_id' => 5, 'college_id' => 14],
                ['is_available' => true, 'request_type_id' => 6, 'college_id' => 14],
                ['is_available' => true, 'request_type_id' => 7, 'college_id' => 14],
                ['is_available' => true, 'request_type_id' => 8, 'college_id' => 14],
                ['is_available' => false, 'request_type_id' => 9, 'college_id' => 14],
                ['is_available' => true, 'request_type_id' => 10, 'college_id' => 14],
                ['is_available' => true, 'request_type_id' => 11, 'college_id' => 14],

                // 15. كلية الاقتصاد (نظرية)
                ['is_available' => true, 'request_type_id' => 1, 'college_id' => 15],
                ['is_available' => true, 'request_type_id' => 2, 'college_id' => 15],
                ['is_available' => true, 'request_type_id' => 3, 'college_id' => 15],
                ['is_available' => true, 'request_type_id' => 4, 'college_id' => 15],
                ['is_available' => true, 'request_type_id' => 5, 'college_id' => 15],
                ['is_available' => true, 'request_type_id' => 6, 'college_id' => 15],
                ['is_available' => true, 'request_type_id' => 7, 'college_id' => 15],
                ['is_available' => true, 'request_type_id' => 8, 'college_id' => 15],
                ['is_available' => false, 'request_type_id' => 9, 'college_id' => 15],
                ['is_available' => true, 'request_type_id' => 10, 'college_id' => 15],
                ['is_available' => true, 'request_type_id' => 11, 'college_id' => 15],

                // ========== جامعة طرطوس (college_id 16-18) ==========
                // 16. كلية الطب البشري (عملية)
                ['is_available' => true, 'request_type_id' => 1, 'college_id' => 16],
                ['is_available' => true, 'request_type_id' => 2, 'college_id' => 16],
                ['is_available' => true, 'request_type_id' => 3, 'college_id' => 16],
                ['is_available' => true, 'request_type_id' => 4, 'college_id' => 16],
                ['is_available' => true, 'request_type_id' => 5, 'college_id' => 16],
                ['is_available' => true, 'request_type_id' => 6, 'college_id' => 16],
                ['is_available' => true, 'request_type_id' => 7, 'college_id' => 16],
                ['is_available' => true, 'request_type_id' => 8, 'college_id' => 16],
                ['is_available' => true, 'request_type_id' => 9, 'college_id' => 16],
                ['is_available' => true, 'request_type_id' => 10, 'college_id' => 16],
                ['is_available' => true, 'request_type_id' => 11, 'college_id' => 16],

                // 17. كلية طب الأسنان (عملية)
                ['is_available' => true, 'request_type_id' => 1, 'college_id' => 17],
                ['is_available' => true, 'request_type_id' => 2, 'college_id' => 17],
                ['is_available' => true, 'request_type_id' => 3, 'college_id' => 17],
                ['is_available' => true, 'request_type_id' => 4, 'college_id' => 17],
                ['is_available' => true, 'request_type_id' => 5, 'college_id' => 17],
                ['is_available' => true, 'request_type_id' => 6, 'college_id' => 17],
                ['is_available' => true, 'request_type_id' => 7, 'college_id' => 17],
                ['is_available' => true, 'request_type_id' => 8, 'college_id' => 17],
                ['is_available' => true, 'request_type_id' => 9, 'college_id' => 17],
                ['is_available' => true, 'request_type_id' => 10, 'college_id' => 17],
                ['is_available' => true, 'request_type_id' => 11, 'college_id' => 17],

                // 18. كلية الصيدلة (عملية)
                ['is_available' => true, 'request_type_id' => 1, 'college_id' => 18],
                ['is_available' => true, 'request_type_id' => 2, 'college_id' => 18],
                ['is_available' => true, 'request_type_id' => 3, 'college_id' => 18],
                ['is_available' => true, 'request_type_id' => 4, 'college_id' => 18],
                ['is_available' => true, 'request_type_id' => 5, 'college_id' => 18],
                ['is_available' => true, 'request_type_id' => 6, 'college_id' => 18],
                ['is_available' => true, 'request_type_id' => 7, 'college_id' => 18],
                ['is_available' => true, 'request_type_id' => 8, 'college_id' => 18],
                ['is_available' => true, 'request_type_id' => 9, 'college_id' => 18],
                ['is_available' => true, 'request_type_id' => 10, 'college_id' => 18],
                ['is_available' => true, 'request_type_id' => 11, 'college_id' => 18],

                // ========== جامعة اللاذقية (college_id 19-21) ==========
                // 19. كلية الهندسة المعلوماتية (عملية)
                ['is_available' => true, 'request_type_id' => 1, 'college_id' => 19],
                ['is_available' => true, 'request_type_id' => 2, 'college_id' => 19],
                ['is_available' => true, 'request_type_id' => 3, 'college_id' => 19],
                ['is_available' => true, 'request_type_id' => 4, 'college_id' => 19],
                ['is_available' => true, 'request_type_id' => 5, 'college_id' => 19],
                ['is_available' => true, 'request_type_id' => 6, 'college_id' => 19],
                ['is_available' => true, 'request_type_id' => 7, 'college_id' => 19],
                ['is_available' => true, 'request_type_id' => 8, 'college_id' => 19],
                ['is_available' => true, 'request_type_id' => 9, 'college_id' => 19],
                ['is_available' => true, 'request_type_id' => 10, 'college_id' => 19],
                ['is_available' => true, 'request_type_id' => 11, 'college_id' => 19],

                // 20. كلية الآداب (نظرية)
                ['is_available' => true, 'request_type_id' => 1, 'college_id' => 20],
                ['is_available' => true, 'request_type_id' => 2, 'college_id' => 20],
                ['is_available' => true, 'request_type_id' => 3, 'college_id' => 20],
                ['is_available' => true, 'request_type_id' => 4, 'college_id' => 20],
                ['is_available' => true, 'request_type_id' => 5, 'college_id' => 20],
                ['is_available' => true, 'request_type_id' => 6, 'college_id' => 20],
                ['is_available' => true, 'request_type_id' => 7, 'college_id' => 20],
                ['is_available' => true, 'request_type_id' => 8, 'college_id' => 20],
                ['is_available' => false, 'request_type_id' => 9, 'college_id' => 20],
                ['is_available' => true, 'request_type_id' => 10, 'college_id' => 20],
                ['is_available' => true, 'request_type_id' => 11, 'college_id' => 20],

                // 21. كلية الاقتصاد (نظرية)
                ['is_available' => true, 'request_type_id' => 1, 'college_id' => 21],
                ['is_available' => true, 'request_type_id' => 2, 'college_id' => 21],
                ['is_available' => true, 'request_type_id' => 3, 'college_id' => 21],
                ['is_available' => true, 'request_type_id' => 4, 'college_id' => 21],
                ['is_available' => true, 'request_type_id' => 5, 'college_id' => 21],
                ['is_available' => true, 'request_type_id' => 6, 'college_id' => 21],
                ['is_available' => true, 'request_type_id' => 7, 'college_id' => 21],
                ['is_available' => true, 'request_type_id' => 8, 'college_id' => 21],
                ['is_available' => false, 'request_type_id' => 9, 'college_id' => 21],
                ['is_available' => true, 'request_type_id' => 10, 'college_id' => 21],
                ['is_available' => true, 'request_type_id' => 11, 'college_id' => 21],

                // ========== جامعة حماة (college_id 22-24) ==========
                // 22. كلية الطب البشري (عملية)
                ['is_available' => true, 'request_type_id' => 1, 'college_id' => 22],
                ['is_available' => true, 'request_type_id' => 2, 'college_id' => 22],
                ['is_available' => true, 'request_type_id' => 3, 'college_id' => 22],
                ['is_available' => true, 'request_type_id' => 4, 'college_id' => 22],
                ['is_available' => true, 'request_type_id' => 5, 'college_id' => 22],
                ['is_available' => true, 'request_type_id' => 6, 'college_id' => 22],
                ['is_available' => true, 'request_type_id' => 7, 'college_id' => 22],
                ['is_available' => true, 'request_type_id' => 8, 'college_id' => 22],
                ['is_available' => true, 'request_type_id' => 9, 'college_id' => 22],
                ['is_available' => true, 'request_type_id' => 10, 'college_id' => 22],
                ['is_available' => true, 'request_type_id' => 11, 'college_id' => 22],

                // 23. كلية الصيدلة (عملية)
                ['is_available' => true, 'request_type_id' => 1, 'college_id' => 23],
                ['is_available' => true, 'request_type_id' => 2, 'college_id' => 23],
                ['is_available' => true, 'request_type_id' => 3, 'college_id' => 23],
                ['is_available' => true, 'request_type_id' => 4, 'college_id' => 23],
                ['is_available' => true, 'request_type_id' => 5, 'college_id' => 23],
                ['is_available' => true, 'request_type_id' => 6, 'college_id' => 23],
                ['is_available' => true, 'request_type_id' => 7, 'college_id' => 23],
                ['is_available' => true, 'request_type_id' => 8, 'college_id' => 23],
                ['is_available' => true, 'request_type_id' => 9, 'college_id' => 23],
                ['is_available' => true, 'request_type_id' => 10, 'college_id' => 23],
                ['is_available' => true, 'request_type_id' => 11, 'college_id' => 23],

                // 24. كلية الآداب (نظرية)
                ['is_available' => true, 'request_type_id' => 1, 'college_id' => 24],
                ['is_available' => true, 'request_type_id' => 2, 'college_id' => 24],
                ['is_available' => true, 'request_type_id' => 3, 'college_id' => 24],
                ['is_available' => true, 'request_type_id' => 4, 'college_id' => 24],
                ['is_available' => true, 'request_type_id' => 5, 'college_id' => 24],
                ['is_available' => true, 'request_type_id' => 6, 'college_id' => 24],
                ['is_available' => true, 'request_type_id' => 7, 'college_id' => 24],
                ['is_available' => true, 'request_type_id' => 8, 'college_id' => 24],
                ['is_available' => false, 'request_type_id' => 9, 'college_id' => 24],
                ['is_available' => true, 'request_type_id' => 10, 'college_id' => 24],
                ['is_available' => true, 'request_type_id' => 11, 'college_id' => 24],

                // ========== جامعة الفرات (college_id 25) ==========
                // 25. كلية الطب البيطري (عملية)
                ['is_available' => true, 'request_type_id' => 1, 'college_id' => 25],
                ['is_available' => true, 'request_type_id' => 2, 'college_id' => 25],
                ['is_available' => true, 'request_type_id' => 3, 'college_id' => 25],
                ['is_available' => true, 'request_type_id' => 4, 'college_id' => 25],
                ['is_available' => true, 'request_type_id' => 5, 'college_id' => 25],
                ['is_available' => true, 'request_type_id' => 6, 'college_id' => 25],
                ['is_available' => true, 'request_type_id' => 7, 'college_id' => 25],
                ['is_available' => true, 'request_type_id' => 8, 'college_id' => 25],
                ['is_available' => true, 'request_type_id' => 9, 'college_id' => 25],
                ['is_available' => true, 'request_type_id' => 10, 'college_id' => 25],
                ['is_available' => true, 'request_type_id' => 11, 'college_id' => 25],
            ];

        foreach ($data as $item) {
            RequestTypeAvailability::firstOrCreate([
                'is_available' => $item['is_available'],
                'request_type_id' =>  $item['request_type_id'],
                'college_id' => $item['college_id'],
            ]);
        }
    }
}
