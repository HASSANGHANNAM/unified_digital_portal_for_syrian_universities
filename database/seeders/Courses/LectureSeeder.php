<?php

namespace Database\Seeders\Courses;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\Lecture;

class LectureSeeder extends Seeder
{
    public function run(): void
    {
        // $lecturesData = [
        //     // المادة 1: اللغة الانكليزية 1 - عملي
        //     ['course_parts_id' => 1, 'title' => 'مقدمة', 'file_url' => 'lectures/1/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 1: اللغة الانكليزية 1 - نظري
        //     ['course_parts_id' => 2, 'title' => 'مقدمة', 'file_url' => 'lectures/2/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 2: اللغة الانكليزية 2 - عملي
        //     ['course_parts_id' => 3, 'title' => 'مقدمة', 'file_url' => 'lectures/3/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 2: اللغة الانكليزية 2 - نظري
        //     ['course_parts_id' => 4, 'title' => 'مقدمة', 'file_url' => 'lectures/4/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 3: اللغة الانكليزية 3 - عملي
        //     ['course_parts_id' => 5, 'title' => 'مقدمة', 'file_url' => 'lectures/5/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 3: اللغة الانكليزية 3 - نظري
        //     ['course_parts_id' => 6, 'title' => 'مقدمة', 'file_url' => 'lectures/6/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 4: اللغة الانكليزية 4 - عملي
        //     ['course_parts_id' => 7, 'title' => 'مقدمة', 'file_url' => 'lectures/7/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 4: اللغة الانكليزية 4 - نظري
        //     ['course_parts_id' => 8, 'title' => 'مقدمة', 'file_url' => 'lectures/8/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 5: البرمجة 1 - عملي
        //     ['course_parts_id' => 9, 'title' => 'مقدمة', 'file_url' => 'lectures/9/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 5: البرمجة 1 - نظري
        //     ['course_parts_id' => 10, 'title' => 'مقدمة', 'file_url' => 'lectures/10/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 6: البرمجة 2 - عملي
        //     ['course_parts_id' => 11, 'title' => 'مقدمة', 'file_url' => 'lectures/11/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 6: البرمجة 2 - نظري
        //     ['course_parts_id' => 12, 'title' => 'مقدمة', 'file_url' => 'lectures/12/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 7: البرمجة 3 - عملي
        //     ['course_parts_id' => 13, 'title' => 'مقدمة', 'file_url' => 'lectures/13/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 7: البرمجة 3 - نظري
        //     ['course_parts_id' => 14, 'title' => 'مقدمة', 'file_url' => 'lectures/14/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 8: الجبر العام - عملي
        //     ['course_parts_id' => 15, 'title' => 'مقدمة', 'file_url' => 'lectures/15/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 8: الجبر العام - نظري
        //     ['course_parts_id' => 16, 'title' => 'مقدمة', 'file_url' => 'lectures/16/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 9: الجبر الخطي - عملي
        //     ['course_parts_id' => 17, 'title' => 'مقدمة', 'file_url' => 'lectures/17/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 9: الجبر الخطي - نظري
        //     ['course_parts_id' => 18, 'title' => 'مقدمة', 'file_url' => 'lectures/18/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 10: تحليل 1 - عملي
        //     ['course_parts_id' => 19, 'title' => 'مقدمة', 'file_url' => 'lectures/19/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 10: تحليل 1 - نظري
        //     ['course_parts_id' => 20, 'title' => 'مقدمة', 'file_url' => 'lectures/20/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 11: تحليل 2 - عملي
        //     ['course_parts_id' => 21, 'title' => 'مقدمة', 'file_url' => 'lectures/21/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 11: تحليل 2 - نظري
        //     ['course_parts_id' => 22, 'title' => 'مقدمة', 'file_url' => 'lectures/22/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 12: تحليل 3 - عملي
        //     ['course_parts_id' => 23, 'title' => 'مقدمة', 'file_url' => 'lectures/23/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 12: تحليل 3 - نظري
        //     ['course_parts_id' => 24, 'title' => 'مقدمة', 'file_url' => 'lectures/24/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 13: تحليل عددي - عملي
        //     ['course_parts_id' => 25, 'title' => 'مقدمة', 'file_url' => 'lectures/25/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 13: تحليل عددي - نظري
        //     ['course_parts_id' => 26, 'title' => 'مقدمة', 'file_url' => 'lectures/26/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 14: الاحتمالات و الإحصاء - عملي
        //     ['course_parts_id' => 27, 'title' => 'مقدمة', 'file_url' => 'lectures/27/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 14: الاحتمالات و الإحصاء - نظري
        //     ['course_parts_id' => 28, 'title' => 'مقدمة', 'file_url' => 'lectures/28/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 15: مبادئ عمل الحاسوب - عملي
        //     ['course_parts_id' => 29, 'title' => 'مقدمة', 'file_url' => 'lectures/29/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 15: مبادئ عمل الحاسوب - نظري
        //     ['course_parts_id' => 30, 'title' => 'مقدمة', 'file_url' => 'lectures/30/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 16: بنيان الحواسيب 1 - عملي
        //     ['course_parts_id' => 31, 'title' => 'مقدمة', 'file_url' => 'lectures/31/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 16: بنيان الحواسيب 1 - نظري
        //     ['course_parts_id' => 32, 'title' => 'مقدمة', 'file_url' => 'lectures/32/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 17: بنيان الحواسيب 2 - عملي
        //     ['course_parts_id' => 33, 'title' => 'مقدمة', 'file_url' => 'lectures/33/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 17: بنيان الحواسيب 2 - نظري
        //     ['course_parts_id' => 34, 'title' => 'مقدمة', 'file_url' => 'lectures/34/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 18: قواعد المعطيات 1 - عملي
        //     ['course_parts_id' => 35, 'title' => 'مقدمة', 'file_url' => 'lectures/35/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 18: قواعد المعطيات 1 - نظري
        //     ['course_parts_id' => 36, 'title' => 'مقدمة', 'file_url' => 'lectures/36/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 19: قواعد المعطيات 2 - عملي
        //     ['course_parts_id' => 37, 'title' => 'مقدمة', 'file_url' => 'lectures/37/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 19: قواعد المعطيات 2 - نظري
        //     ['course_parts_id' => 38, 'title' => 'مقدمة', 'file_url' => 'lectures/38/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 20: قواعد المعطيات المتقدمة - عملي
        //     ['course_parts_id' => 39, 'title' => 'مقدمة', 'file_url' => 'lectures/39/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 20: قواعد المعطيات المتقدمة - نظري
        //     ['course_parts_id' => 40, 'title' => 'مقدمة', 'file_url' => 'lectures/40/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 21: قواعد البيانات - عملي
        //     ['course_parts_id' => 41, 'title' => 'مقدمة', 'file_url' => 'lectures/41/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 21: قواعد البيانات - نظري
        //     ['course_parts_id' => 42, 'title' => 'مقدمة', 'file_url' => 'lectures/42/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 22: الفيزياء - عملي
        //     ['course_parts_id' => 43, 'title' => 'مقدمة', 'file_url' => 'lectures/43/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 22: الفيزياء - نظري
        //     ['course_parts_id' => 44, 'title' => 'مقدمة', 'file_url' => 'lectures/44/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 23: اللغة العربية - عملي
        //     ['course_parts_id' => 45, 'title' => 'مقدمة', 'file_url' => 'lectures/45/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 23: اللغة العربية - نظري
        //     ['course_parts_id' => 46, 'title' => 'مقدمة', 'file_url' => 'lectures/46/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 24: الدارات الكهربائية - عملي
        //     ['course_parts_id' => 47, 'title' => 'مقدمة', 'file_url' => 'lectures/47/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 24: الدارات الكهربائية - نظري
        //     ['course_parts_id' => 48, 'title' => 'مقدمة', 'file_url' => 'lectures/48/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 25: الدارات المطقية - عملي
        //     ['course_parts_id' => 49, 'title' => 'مقدمة', 'file_url' => 'lectures/49/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 25: الدارات المطقية - نظري
        //     ['course_parts_id' => 50, 'title' => 'مقدمة', 'file_url' => 'lectures/50/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 26: الخوارزميات وبنى المعطيات 1 - عملي
        //     ['course_parts_id' => 51, 'title' => 'مقدمة', 'file_url' => 'lectures/51/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 26: الخوارزميات وبنى المعطيات 1 - نظري
        //     ['course_parts_id' => 52, 'title' => 'مقدمة', 'file_url' => 'lectures/52/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 27: الخوارزميات وبنى المعطيات 2 - عملي
        //     ['course_parts_id' => 53, 'title' => 'مقدمة', 'file_url' => 'lectures/53/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 27: الخوارزميات وبنى المعطيات 2 - نظري
        //     ['course_parts_id' => 54, 'title' => 'مقدمة', 'file_url' => 'lectures/54/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 28: الاتصالات الرقمية - عملي
        //     ['course_parts_id' => 55, 'title' => 'مقدمة', 'file_url' => 'lectures/55/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 28: الاتصالات الرقمية - نظري
        //     ['course_parts_id' => 56, 'title' => 'مقدمة', 'file_url' => 'lectures/56/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 29: مهارات التواصل - عملي
        //     ['course_parts_id' => 57, 'title' => 'مقدمة', 'file_url' => 'lectures/57/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 29: مهارات التواصل - نظري
        //     ['course_parts_id' => 58, 'title' => 'مقدمة', 'file_url' => 'lectures/58/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 30: بحوث العمليات - عملي
        //     ['course_parts_id' => 59, 'title' => 'مقدمة', 'file_url' => 'lectures/59/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 30: بحوث العمليات - نظري
        //     ['course_parts_id' => 60, 'title' => 'مقدمة', 'file_url' => 'lectures/60/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 31: لغات البرمجة - عملي
        //     ['course_parts_id' => 61, 'title' => 'مقدمة', 'file_url' => 'lectures/61/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 31: لغات البرمجة - نظري
        //     ['course_parts_id' => 62, 'title' => 'مقدمة', 'file_url' => 'lectures/62/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 32: مبادئ الذكاء الصنعي - عملي
        //     ['course_parts_id' => 63, 'title' => 'مقدمة', 'file_url' => 'lectures/63/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 32: مبادئ الذكاء الصنعي - نظري
        //     ['course_parts_id' => 64, 'title' => 'مقدمة', 'file_url' => 'lectures/64/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 33: أساسيات الشبكات المعلوماتية - عملي
        //     ['course_parts_id' => 65, 'title' => 'مقدمة', 'file_url' => 'lectures/65/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 33: أساسيات الشبكات المعلوماتية - نظري
        //     ['course_parts_id' => 66, 'title' => 'مقدمة', 'file_url' => 'lectures/66/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 34: اللغات الصورية - عملي
        //     ['course_parts_id' => 67, 'title' => 'مقدمة', 'file_url' => 'lectures/67/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 34: اللغات الصورية - نظري
        //     ['course_parts_id' => 68, 'title' => 'مقدمة', 'file_url' => 'lectures/68/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 35: بيانيات حاسوبية - عملي
        //     ['course_parts_id' => 69, 'title' => 'مقدمة', 'file_url' => 'lectures/69/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 35: بيانيات حاسوبية - نظري
        //     ['course_parts_id' => 70, 'title' => 'مقدمة', 'file_url' => 'lectures/70/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 36: حسابات علمية - عملي
        //     ['course_parts_id' => 71, 'title' => 'مقدمة', 'file_url' => 'lectures/71/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 36: حسابات علمية - نظري
        //     ['course_parts_id' => 72, 'title' => 'مقدمة', 'file_url' => 'lectures/72/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 37: المشروع 1 - عملي
        //     ['course_parts_id' => 73, 'title' => 'مقدمة', 'file_url' => 'lectures/73/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 37: المشروع 1 - نظري
        //     ['course_parts_id' => 74, 'title' => 'مقدمة', 'file_url' => 'lectures/74/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 38: المشروع 2 - عملي
        //     ['course_parts_id' => 75, 'title' => 'مقدمة', 'file_url' => 'lectures/75/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 38: المشروع 2 - نظري
        //     ['course_parts_id' => 76, 'title' => 'مقدمة', 'file_url' => 'lectures/76/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 39: المشروع 3 - عملي
        //     ['course_parts_id' => 77, 'title' => 'مقدمة', 'file_url' => 'lectures/77/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 39: المشروع 3 - نظري
        //     ['course_parts_id' => 78, 'title' => 'مقدمة', 'file_url' => 'lectures/78/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 40: بروتوكولات الاتصال الحاسوبية - عملي
        //     ['course_parts_id' => 79, 'title' => 'مقدمة', 'file_url' => 'lectures/79/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 40: بروتوكولات الاتصال الحاسوبية - نظري
        //     ['course_parts_id' => 80, 'title' => 'مقدمة', 'file_url' => 'lectures/80/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 41: خوارزميات البحث الذكية - عملي
        //     ['course_parts_id' => 81, 'title' => 'مقدمة', 'file_url' => 'lectures/81/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 41: خوارزميات البحث الذكية - نظري
        //     ['course_parts_id' => 82, 'title' => 'مقدمة', 'file_url' => 'lectures/82/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 42: نظم تشغيل 1 - عملي
        //     ['course_parts_id' => 83, 'title' => 'مقدمة', 'file_url' => 'lectures/83/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 42: نظم تشغيل 1 - نظري
        //     ['course_parts_id' => 84, 'title' => 'مقدمة', 'file_url' => 'lectures/84/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 43: نظم تشغيل 2 - عملي
        //     ['course_parts_id' => 85, 'title' => 'مقدمة', 'file_url' => 'lectures/85/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 43: نظم تشغيل 2 - نظري
        //     ['course_parts_id' => 86, 'title' => 'مقدمة', 'file_url' => 'lectures/86/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 44: البرمجة التفرعية - عملي
        //     ['course_parts_id' => 87, 'title' => 'مقدمة', 'file_url' => 'lectures/87/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 44: البرمجة التفرعية - نظري
        //     ['course_parts_id' => 88, 'title' => 'مقدمة', 'file_url' => 'lectures/88/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 45: التسويق - عملي
        //     ['course_parts_id' => 89, 'title' => 'مقدمة', 'file_url' => 'lectures/89/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 45: التسويق - نظري
        //     ['course_parts_id' => 90, 'title' => 'مقدمة', 'file_url' => 'lectures/90/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 46: الاقتصاد والإدارة في المؤسسة - عملي
        //     ['course_parts_id' => 91, 'title' => 'مقدمة', 'file_url' => 'lectures/91/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 46: الاقتصاد والإدارة في المؤسسة - نظري
        //     ['course_parts_id' => 92, 'title' => 'مقدمة', 'file_url' => 'lectures/92/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 47: إدارة المشاريع - عملي
        //     ['course_parts_id' => 93, 'title' => 'مقدمة', 'file_url' => 'lectures/93/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 47: إدارة المشاريع - نظري
        //     ['course_parts_id' => 94, 'title' => 'مقدمة', 'file_url' => 'lectures/94/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 48: هندسة الرمجيات 1 - عملي
        //     ['course_parts_id' => 95, 'title' => 'مقدمة', 'file_url' => 'lectures/95/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 48: هندسة الرمجيات 1 - نظري
        //     ['course_parts_id' => 96, 'title' => 'مقدمة', 'file_url' => 'lectures/96/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 49: هندسة الرمجيات 2 - عملي
        //     ['course_parts_id' => 97, 'title' => 'مقدمة', 'file_url' => 'lectures/97/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 49: هندسة الرمجيات 2 - نظري
        //     ['course_parts_id' => 98, 'title' => 'مقدمة', 'file_url' => 'lectures/98/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 50: هندسة الرمجيات 3 - عملي
        //     ['course_parts_id' => 99, 'title' => 'مقدمة', 'file_url' => 'lectures/99/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 50: هندسة الرمجيات 3 - نظري
        //     ['course_parts_id' => 100, 'title' => 'مقدمة', 'file_url' => 'lectures/100/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 51: برمجة التطبيقات الشبكية - عملي
        //     ['course_parts_id' => 101, 'title' => 'مقدمة', 'file_url' => 'lectures/101/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 51: برمجة التطبيقات الشبكية - نظري
        //     ['course_parts_id' => 102, 'title' => 'مقدمة', 'file_url' => 'lectures/102/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 52: نظم وساءط متعددة وفائقة - عملي
        //     ['course_parts_id' => 103, 'title' => 'مقدمة', 'file_url' => 'lectures/103/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 52: نظم وساءط متعددة وفائقة - نظري
        //     ['course_parts_id' => 104, 'title' => 'مقدمة', 'file_url' => 'lectures/104/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 53: الحقائق الافتراضية - عملي
        //     ['course_parts_id' => 105, 'title' => 'مقدمة', 'file_url' => 'lectures/105/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 53: الحقائق الافتراضية - نظري
        //     ['course_parts_id' => 106, 'title' => 'مقدمة', 'file_url' => 'lectures/106/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 54: المترجمات 1 - عملي
        //     ['course_parts_id' => 107, 'title' => 'مقدمة', 'file_url' => 'lectures/107/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 54: المترجمات 1 - نظري
        //     ['course_parts_id' => 108, 'title' => 'مقدمة', 'file_url' => 'lectures/108/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 55: مشروع المترجمات - عملي
        //     ['course_parts_id' => 109, 'title' => 'مقدمة', 'file_url' => 'lectures/109/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 55: مشروع المترجمات - نظري
        //     ['course_parts_id' => 110, 'title' => 'مقدمة', 'file_url' => 'lectures/110/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 56: نظم قواعد المعرفة - عملي
        //     ['course_parts_id' => 111, 'title' => 'مقدمة', 'file_url' => 'lectures/111/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 56: نظم قواعد المعرفة - نظري
        //     ['course_parts_id' => 112, 'title' => 'مقدمة', 'file_url' => 'lectures/112/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 57: الشبكات العصبونية - عملي
        //     ['course_parts_id' => 113, 'title' => 'مقدمة', 'file_url' => 'lectures/113/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 57: الشبكات العصبونية - نظري
        //     ['course_parts_id' => 114, 'title' => 'مقدمة', 'file_url' => 'lectures/114/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 58: نمذجة ومحاكاة النظم الشبكية - عملي
        //     ['course_parts_id' => 115, 'title' => 'مقدمة', 'file_url' => 'lectures/115/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 58: نمذجة ومحاكاة النظم الشبكية - نظري
        //     ['course_parts_id' => 116, 'title' => 'مقدمة', 'file_url' => 'lectures/116/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 59: تصميم الشبكات الحاسوبية - عملي
        //     ['course_parts_id' => 117, 'title' => 'مقدمة', 'file_url' => 'lectures/117/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 59: تصميم الشبكات الحاسوبية - نظري
        //     ['course_parts_id' => 118, 'title' => 'مقدمة', 'file_url' => 'lectures/118/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 60: أمن نظم المعلومات - عملي
        //     ['course_parts_id' => 119, 'title' => 'مقدمة', 'file_url' => 'lectures/119/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 60: أمن نظم المعلومات - نظري
        //     ['course_parts_id' => 120, 'title' => 'مقدمة', 'file_url' => 'lectures/120/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 61: النظم والتطبيقات الموزعة - عملي
        //     ['course_parts_id' => 121, 'title' => 'مقدمة', 'file_url' => 'lectures/121/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 61: النظم والتطبيقات الموزعة - نظري
        //     ['course_parts_id' => 122, 'title' => 'مقدمة', 'file_url' => 'lectures/122/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 62: معالجة اللغات الطبيعية - عملي
        //     ['course_parts_id' => 123, 'title' => 'مقدمة', 'file_url' => 'lectures/123/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 62: معالجة اللغات الطبيعية - نظري
        //     ['course_parts_id' => 124, 'title' => 'مقدمة', 'file_url' => 'lectures/124/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 63: الروبوتية - عملي
        //     ['course_parts_id' => 125, 'title' => 'مقدمة', 'file_url' => 'lectures/125/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 63: الروبوتية - نظري
        //     ['course_parts_id' => 126, 'title' => 'مقدمة', 'file_url' => 'lectures/126/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 64: تطبيقات الانترنت - عملي
        //     ['course_parts_id' => 127, 'title' => 'مقدمة', 'file_url' => 'lectures/127/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 64: تطبيقات الانترنت - نظري
        //     ['course_parts_id' => 128, 'title' => 'مقدمة', 'file_url' => 'lectures/128/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 65: نظم الزمن الحقيقي - عملي
        //     ['course_parts_id' => 129, 'title' => 'مقدمة', 'file_url' => 'lectures/129/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 65: نظم الزمن الحقيقي - نظري
        //     ['course_parts_id' => 130, 'title' => 'مقدمة', 'file_url' => 'lectures/130/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 66: إدارة الشبكات الحاسوبية - عملي
        //     ['course_parts_id' => 131, 'title' => 'مقدمة', 'file_url' => 'lectures/131/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 66: إدارة الشبكات الحاسوبية - نظري
        //     ['course_parts_id' => 132, 'title' => 'مقدمة', 'file_url' => 'lectures/132/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 67: أمن الشبكات الحاسوبية - عملي
        //     ['course_parts_id' => 133, 'title' => 'مقدمة', 'file_url' => 'lectures/133/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 67: أمن الشبكات الحاسوبية - نظري
        //     ['course_parts_id' => 134, 'title' => 'مقدمة', 'file_url' => 'lectures/134/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 68: التعلم التلقائي - عملي
        //     ['course_parts_id' => 135, 'title' => 'مقدمة', 'file_url' => 'lectures/135/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 68: التعلم التلقائي - نظري
        //     ['course_parts_id' => 136, 'title' => 'مقدمة', 'file_url' => 'lectures/136/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 69: الرؤيا الحاسوبية - عملي
        //     ['course_parts_id' => 137, 'title' => 'مقدمة', 'file_url' => 'lectures/137/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 69: الرؤيا الحاسوبية - نظري
        //     ['course_parts_id' => 138, 'title' => 'مقدمة', 'file_url' => 'lectures/138/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 70: استكشاف المعرفة - عملي
        //     ['course_parts_id' => 139, 'title' => 'مقدمة', 'file_url' => 'lectures/139/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 70: استكشاف المعرفة - نظري
        //     ['course_parts_id' => 140, 'title' => 'مقدمة', 'file_url' => 'lectures/140/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 71: نظم البحث عن الملومات - عملي
        //     ['course_parts_id' => 141, 'title' => 'مقدمة', 'file_url' => 'lectures/141/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 71: نظم البحث عن الملومات - نظري
        //     ['course_parts_id' => 142, 'title' => 'مقدمة', 'file_url' => 'lectures/142/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 72: هندسة نظم المعلومات - عملي
        //     ['course_parts_id' => 143, 'title' => 'مقدمة', 'file_url' => 'lectures/143/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        //     // المادة 72: هندسة نظم المعلومات - نظري
        //     ['course_parts_id' => 144, 'title' => 'مقدمة', 'file_url' => 'lectures/144/uuid.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
        // ];

        // foreach ($lecturesData as $data) {
        //     Lecture::firstOrCreate([
        //         'course_parts_id' => $data['course_parts_id'],
        //         'title' => $data['title'],
        //         'type' => $data['type'],
        //     ], [
        //         'course_parts_id' => $data['course_parts_id'],
        //         'title' => $data['title'],
        //         'file_url' => $data['file_url'],
        //         'upload_date' => $data['upload_date'],
        //         'type' => $data['type'],
        //         'order_index' => $data['order_index'],
        //     ]);
        // }
    }
}
