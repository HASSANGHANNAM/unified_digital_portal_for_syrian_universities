<?php

namespace Database\Seeders\Courses;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Doctor;
use App\Models\TeachingAssistant;
use App\Models\CourseStaff;

class CourseStaffSeeder extends Seeder
{
    public function run(): void
    {
        // $data = [
        //     // =====================================================================
        //     // قسم هندسة البرمجيات ونظم المعلومات (department_id = 1)
        //     // المواد: 1-7, 18-21, 26-27, 31, 34, 37-39, 48-50, 72
        //     // =====================================================================
        //     // المادة 1
        //     ['course_id' => 1, 'doctor_id' => 1,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: نور الدين أحمد
        //     ['course_id' => 1, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true], // م: سارة حسن
        //     // المادة 2
        //     ['course_id' => 2, 'doctor_id' => 4,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: هود محمد
        //     ['course_id' => 2, 'doctor_id' => null, 'ta_id' => 2,  'staff_id' => null, 'is_advisor' => true], // م: رنا باسم العقاد
        //     // المادة 3
        //     ['course_id' => 3, 'doctor_id' => 5,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: محمد نور الدين
        //     ['course_id' => 3, 'doctor_id' => null, 'ta_id' => 3,  'staff_id' => null, 'is_advisor' => true], // م: عمار حسام الخطيب
        //     // المادة 4
        //     ['course_id' => 4, 'doctor_id' => 6, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: سامر فؤاد العبد
        //     ['course_id' => 4, 'doctor_id' => null, 'ta_id' => 4,  'staff_id' => null, 'is_advisor' => true], // م: ريم جورج الخوري
        //     // المادة 5
        //     ['course_id' => 5, 'doctor_id' => 7, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: دعاء إبراهيم الشيخ
        //     ['course_id' => 5, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true], // م: سارة حسن
        //     // المادة 6
        //     ['course_id' => 6, 'doctor_id' => 8, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: حسام تيسير الحلبي
        //     ['course_id' => 6, 'doctor_id' => null, 'ta_id' => 2,  'staff_id' => null, 'is_advisor' => true], // م: رنا باسم العقاد
        //     // المادة 7
        //     ['course_id' => 7, 'doctor_id' => 2, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: غسان نبيل الحافظ
        //     ['course_id' => 7, 'doctor_id' => null, 'ta_id' => 3,  'staff_id' => null, 'is_advisor' => true], // م: عمار حسام الخطيب
        //     // المادة 18
        //     ['course_id' => 18, 'doctor_id' => 3, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: نانسي رائف صالح
        //     ['course_id' => 18, 'doctor_id' => null, 'ta_id' => 4,  'staff_id' => null, 'is_advisor' => true], // م: ريم جورج الخوري
        //     // المادة 19
        //     ['course_id' => 19, 'doctor_id' => 9, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: خالد يوسف
        //     ['course_id' => 19, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true], // م: سارة حسن
        //     // المادة 20
        //     ['course_id' => 20, 'doctor_id' => 1,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: نور الدين أحمد
        //     ['course_id' => 20, 'doctor_id' => null, 'ta_id' => 2,  'staff_id' => null, 'is_advisor' => true], // م: رنا باسم العقاد
        //     // المادة 21
        //     ['course_id' => 21, 'doctor_id' => 4,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: هود محمد
        //     ['course_id' => 21, 'doctor_id' => null, 'ta_id' => 3,  'staff_id' => null, 'is_advisor' => true], // م: عمار حسام الخطيب
        //     // المادة 26
        //     ['course_id' => 26, 'doctor_id' => 5,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: محمد نور الدين
        //     ['course_id' => 26, 'doctor_id' => null, 'ta_id' => 4,  'staff_id' => null, 'is_advisor' => true], // م: ريم جورج الخوري
        //     // المادة 27
        //     ['course_id' => 27, 'doctor_id' => 6, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: سامر فؤاد العبد
        //     ['course_id' => 27, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true], // م: سارة حسن
        //     // المادة 31
        //     ['course_id' => 31, 'doctor_id' => 7, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: دعاء إبراهيم الشيخ
        //     ['course_id' => 31, 'doctor_id' => null, 'ta_id' => 2,  'staff_id' => null, 'is_advisor' => true], // م: رنا باسم العقاد
        //     // المادة 34
        //     ['course_id' => 34, 'doctor_id' => 8, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: حسام تيسير الحلبي
        //     ['course_id' => 34, 'doctor_id' => null, 'ta_id' => 3,  'staff_id' => null, 'is_advisor' => true], // م: عمار حسام الخطيب
        //     // المادة 37
        //     ['course_id' => 37, 'doctor_id' => 2, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: غسان نبيل الحافظ
        //     ['course_id' => 37, 'doctor_id' => null, 'ta_id' => 4,  'staff_id' => null, 'is_advisor' => true], // م: ريم جورج الخوري
        //     // المادة 38
        //     ['course_id' => 38, 'doctor_id' => 3, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: نانسي رائف صالح
        //     ['course_id' => 38, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true], // م: سارة حسن
        //     // المادة 39
        //     ['course_id' => 39, 'doctor_id' => 9, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: خالد يوسف
        //     ['course_id' => 39, 'doctor_id' => null, 'ta_id' => 2,  'staff_id' => null, 'is_advisor' => true], // م: رنا باسم العقاد
        //     // المادة 48
        //     ['course_id' => 48, 'doctor_id' => 1,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: نور الدين أحمد
        //     ['course_id' => 48, 'doctor_id' => null, 'ta_id' => 3,  'staff_id' => null, 'is_advisor' => true], // م: عمار حسام الخطيب
        //     // المادة 49
        //     ['course_id' => 49, 'doctor_id' => 4,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: هود محمد
        //     ['course_id' => 49, 'doctor_id' => null, 'ta_id' => 4,  'staff_id' => null, 'is_advisor' => true], // م: ريم جورج الخوري
        //     // المادة 50
        //     ['course_id' => 50, 'doctor_id' => 5,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: محمد نور الدين
        //     ['course_id' => 50, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true], // م: سارة حسن
        //     // المادة 72
        //     ['course_id' => 72, 'doctor_id' => 6, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: سامر فؤاد العبد
        //     ['course_id' => 72, 'doctor_id' => null, 'ta_id' => 2,  'staff_id' => null, 'is_advisor' => true], // م: رنا باسم العقاد

        //     // =====================================================================
        //     // قسم الذكاء الاصطناعي (department_id = 2)
        //     // المواد: 8-14, 32, 41, 57, 62-63, 68-71
        //     // =====================================================================
        //     // المادة 8
        //     ['course_id' => 8, 'doctor_id' => 7, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: دعاء إبراهيم الشيخ
        //     ['course_id' => 8, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true], // م: سارة حسن
        //     // المادة 9
        //     ['course_id' => 9, 'doctor_id' => 8, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: حسام تيسير الحلبي
        //     ['course_id' => 9, 'doctor_id' => null, 'ta_id' => 2,  'staff_id' => null, 'is_advisor' => true], // م: رنا باسم العقاد
        //     // المادة 10
        //     ['course_id' => 10, 'doctor_id' => 2, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: غسان نبيل الحافظ
        //     ['course_id' => 10, 'doctor_id' => null, 'ta_id' => 3,  'staff_id' => null, 'is_advisor' => true], // م: عمار حسام الخطيب
        //     // المادة 11
        //     ['course_id' => 11, 'doctor_id' => 3, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: نانسي رائف صالح
        //     ['course_id' => 11, 'doctor_id' => null, 'ta_id' => 4,  'staff_id' => null, 'is_advisor' => true], // م: ريم جورج الخوري
        //     // المادة 12
        //     ['course_id' => 12, 'doctor_id' => 9, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: خالد يوسف
        //     ['course_id' => 12, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true], // م: سارة حسن
        //     // المادة 13
        //     ['course_id' => 13, 'doctor_id' => 1,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: نور الدين أحمد
        //     ['course_id' => 13, 'doctor_id' => null, 'ta_id' => 2,  'staff_id' => null, 'is_advisor' => true], // م: رنا باسم العقاد
        //     // المادة 14
        //     ['course_id' => 14, 'doctor_id' => 4,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: هود محمد
        //     ['course_id' => 14, 'doctor_id' => null, 'ta_id' => 3,  'staff_id' => null, 'is_advisor' => true], // م: عمار حسام الخطيب
        //     // المادة 32
        //     ['course_id' => 32, 'doctor_id' => 5,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: محمد نور الدين
        //     ['course_id' => 32, 'doctor_id' => null, 'ta_id' => 4,  'staff_id' => null, 'is_advisor' => true], // م: ريم جورج الخوري
        //     // المادة 41
        //     ['course_id' => 41, 'doctor_id' => 6, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: سامر فؤاد العبد
        //     ['course_id' => 41, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true], // م: سارة حسن
        //     // المادة 57
        //     ['course_id' => 57, 'doctor_id' => 7, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: دعاء إبراهيم الشيخ
        //     ['course_id' => 57, 'doctor_id' => null, 'ta_id' => 2,  'staff_id' => null, 'is_advisor' => true], // م: رنا باسم العقاد
        //     // المادة 62
        //     ['course_id' => 62, 'doctor_id' => 8, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: حسام تيسير الحلبي
        //     ['course_id' => 62, 'doctor_id' => null, 'ta_id' => 3,  'staff_id' => null, 'is_advisor' => true], // م: عمار حسام الخطيب
        //     // المادة 63
        //     ['course_id' => 63, 'doctor_id' => 2, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: غسان نبيل الحافظ
        //     ['course_id' => 63, 'doctor_id' => null, 'ta_id' => 4,  'staff_id' => null, 'is_advisor' => true], // م: ريم جورج الخوري
        //     // المادة 68
        //     ['course_id' => 68, 'doctor_id' => 3, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: نانسي رائف صالح
        //     ['course_id' => 68, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true], // م: سارة حسن
        //     // المادة 69
        //     ['course_id' => 69, 'doctor_id' => 9, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: خالد يوسف
        //     ['course_id' => 69, 'doctor_id' => null, 'ta_id' => 2,  'staff_id' => null, 'is_advisor' => true], // م: رنا باسم العقاد
        //     // المادة 70
        //     ['course_id' => 70, 'doctor_id' => 1,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: نور الدين أحمد
        //     ['course_id' => 70, 'doctor_id' => null, 'ta_id' => 3,  'staff_id' => null, 'is_advisor' => true], // م: عمار حسام الخطيب
        //     // المادة 71
        //     ['course_id' => 71, 'doctor_id' => 4,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: هود محمد
        //     ['course_id' => 71, 'doctor_id' => null, 'ta_id' => 4,  'staff_id' => null, 'is_advisor' => true], // م: ريم جورج الخوري

        //     // =====================================================================
        //     // قسم النظم والشبكات الحاسوبية (department_id = 3)
        //     // المواد: 15-17, 28, 33, 40, 42-44, 51-53, 58-61, 64-67
        //     // =====================================================================
        //     // المادة 15
        //     ['course_id' => 15, 'doctor_id' => 5,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: محمد نور الدين
        //     ['course_id' => 15, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true], // م: سارة حسن
        //     // المادة 16
        //     ['course_id' => 16, 'doctor_id' => 6, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: سامر فؤاد العبد
        //     ['course_id' => 16, 'doctor_id' => null, 'ta_id' => 2,  'staff_id' => null, 'is_advisor' => true], // م: رنا باسم العقاد
        //     // المادة 17
        //     ['course_id' => 17, 'doctor_id' => 7, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: دعاء إبراهيم الشيخ
        //     ['course_id' => 17, 'doctor_id' => null, 'ta_id' => 3,  'staff_id' => null, 'is_advisor' => true], // م: عمار حسام الخطيب
        //     // المادة 28
        //     ['course_id' => 28, 'doctor_id' => 8, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: حسام تيسير الحلبي
        //     ['course_id' => 28, 'doctor_id' => null, 'ta_id' => 4,  'staff_id' => null, 'is_advisor' => true], // م: ريم جورج الخوري
        //     // المادة 33
        //     ['course_id' => 33, 'doctor_id' => 2, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: غسان نبيل الحافظ
        //     ['course_id' => 33, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true], // م: سارة حسن
        //     // المادة 40
        //     ['course_id' => 40, 'doctor_id' => 3, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: نانسي رائف صالح
        //     ['course_id' => 40, 'doctor_id' => null, 'ta_id' => 2,  'staff_id' => null, 'is_advisor' => true], // م: رنا باسم العقاد
        //     // المادة 42
        //     ['course_id' => 42, 'doctor_id' => 9, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: خالد يوسف
        //     ['course_id' => 42, 'doctor_id' => null, 'ta_id' => 3,  'staff_id' => null, 'is_advisor' => true], // م: عمار حسام الخطيب
        //     // المادة 43
        //     ['course_id' => 43, 'doctor_id' => 1,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: نور الدين أحمد
        //     ['course_id' => 43, 'doctor_id' => null, 'ta_id' => 4,  'staff_id' => null, 'is_advisor' => true], // م: ريم جورج الخوري
        //     // المادة 44
        //     ['course_id' => 44, 'doctor_id' => 4,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: هود محمد
        //     ['course_id' => 44, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true], // م: سارة حسن
        //     // المادة 51
        //     ['course_id' => 51, 'doctor_id' => 5,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: محمد نور الدين
        //     ['course_id' => 51, 'doctor_id' => null, 'ta_id' => 2,  'staff_id' => null, 'is_advisor' => true], // م: رنا باسم العقاد
        //     // المادة 52
        //     ['course_id' => 52, 'doctor_id' => 6, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: سامر فؤاد العبد
        //     ['course_id' => 52, 'doctor_id' => null, 'ta_id' => 3,  'staff_id' => null, 'is_advisor' => true], // م: عمار حسام الخطيب
        //     // المادة 53
        //     ['course_id' => 53, 'doctor_id' => 7, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: دعاء إبراهيم الشيخ
        //     ['course_id' => 53, 'doctor_id' => null, 'ta_id' => 4,  'staff_id' => null, 'is_advisor' => true], // م: ريم جورج الخوري
        //     // المادة 58
        //     ['course_id' => 58, 'doctor_id' => 8, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: حسام تيسير الحلبي
        //     ['course_id' => 58, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true], // م: سارة حسن
        //     // المادة 59
        //     ['course_id' => 59, 'doctor_id' => 2, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: غسان نبيل الحافظ
        //     ['course_id' => 59, 'doctor_id' => null, 'ta_id' => 2,  'staff_id' => null, 'is_advisor' => true], // م: رنا باسم العقاد
        //     // المادة 60
        //     ['course_id' => 60, 'doctor_id' => 3, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: نانسي رائف صالح
        //     ['course_id' => 60, 'doctor_id' => null, 'ta_id' => 3,  'staff_id' => null, 'is_advisor' => true], // م: عمار حسام الخطيب
        //     // المادة 61
        //     ['course_id' => 61, 'doctor_id' => 9, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: خالد يوسف
        //     ['course_id' => 61, 'doctor_id' => null, 'ta_id' => 4,  'staff_id' => null, 'is_advisor' => true], // م: ريم جورج الخوري
        //     // المادة 64
        //     ['course_id' => 64, 'doctor_id' => 1,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: نور الدين أحمد
        //     ['course_id' => 64, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true], // م: سارة حسن
        //     // المادة 65
        //     ['course_id' => 65, 'doctor_id' => 4,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: هود محمد
        //     ['course_id' => 65, 'doctor_id' => null, 'ta_id' => 2,  'staff_id' => null, 'is_advisor' => true], // م: رنا باسم العقاد
        //     // المادة 66
        //     ['course_id' => 66, 'doctor_id' => 5,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: محمد نور الدين
        //     ['course_id' => 66, 'doctor_id' => null, 'ta_id' => 3,  'staff_id' => null, 'is_advisor' => true], // م: عمار حسام الخطيب
        //     // المادة 67
        //     ['course_id' => 67, 'doctor_id' => 6, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: سامر فؤاد العبد
        //     ['course_id' => 67, 'doctor_id' => null, 'ta_id' => 4,  'staff_id' => null, 'is_advisor' => true], // م: ريم جورج الخوري

        //     // =====================================================================
        //     // قسم العلوم الأساسية (department_id = 4)
        //     // المواد: 22-25, 29-30, 35-36, 45-47
        //     // =====================================================================
        //     // المادة 22
        //     ['course_id' => 22, 'doctor_id' => 7, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: دعاء إبراهيم الشيخ
        //     ['course_id' => 22, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true], // م: سارة حسن
        //     // المادة 23
        //     ['course_id' => 23, 'doctor_id' => 8, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: حسام تيسير الحلبي
        //     ['course_id' => 23, 'doctor_id' => null, 'ta_id' => 2,  'staff_id' => null, 'is_advisor' => true], // م: رنا باسم العقاد
        //     // المادة 24
        //     ['course_id' => 24, 'doctor_id' => 2, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: غسان نبيل الحافظ
        //     ['course_id' => 24, 'doctor_id' => null, 'ta_id' => 3,  'staff_id' => null, 'is_advisor' => true], // م: عمار حسام الخطيب
        //     // المادة 25
        //     ['course_id' => 25, 'doctor_id' => 3, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: نانسي رائف صالح
        //     ['course_id' => 25, 'doctor_id' => null, 'ta_id' => 4,  'staff_id' => null, 'is_advisor' => true], // م: ريم جورج الخوري
        //     // المادة 29
        //     ['course_id' => 29, 'doctor_id' => 9, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: خالد يوسف
        //     ['course_id' => 29, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true], // م: سارة حسن
        //     // المادة 30
        //     ['course_id' => 30, 'doctor_id' => 1,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: نور الدين أحمد
        //     ['course_id' => 30, 'doctor_id' => null, 'ta_id' => 2,  'staff_id' => null, 'is_advisor' => true], // م: رنا باسم العقاد
        //     // المادة 35
        //     ['course_id' => 35, 'doctor_id' => 4,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: هود محمد
        //     ['course_id' => 35, 'doctor_id' => null, 'ta_id' => 3,  'staff_id' => null, 'is_advisor' => true], // م: عمار حسام الخطيب
        //     // المادة 36
        //     ['course_id' => 36, 'doctor_id' => 5,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: محمد نور الدين
        //     ['course_id' => 36, 'doctor_id' => null, 'ta_id' => 4,  'staff_id' => null, 'is_advisor' => true], // م: ريم جورج الخوري
        //     // المادة 45
        //     ['course_id' => 45, 'doctor_id' => 6, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: سامر فؤاد العبد
        //     ['course_id' => 45, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true], // م: سارة حسن
        //     // المادة 46
        //     ['course_id' => 46, 'doctor_id' => 7, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: دعاء إبراهيم الشيخ
        //     ['course_id' => 46, 'doctor_id' => null, 'ta_id' => 2,  'staff_id' => null, 'is_advisor' => true], // م: رنا باسم العقاد
        //     // المادة 47
        //     ['course_id' => 47, 'doctor_id' => 8, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true], // د: حسام تيسير الحلبي
        //     ['course_id' => 47, 'doctor_id' => null, 'ta_id' => 3,  'staff_id' => null, 'is_advisor' => true], // م: عمار حسام الخطيب
        // ];
        $data = [
            // =====================================================================
            // قسم هندسة البرمجيات ونظم المعلومات (department_id = 1)
            // المواد: 1-7, 18-21, 26-27, 31, 34, 37-39, 48-50, 72
            // =====================================================================
            // المادة 1
            ['course_id' => 1, 'doctor_id' => 1,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 1, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true],
            // المادة 2
            ['course_id' => 2, 'doctor_id' => 2,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 2, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 3
            ['course_id' => 3, 'doctor_id' => 3,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 3, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 4
            ['course_id' => 4, 'doctor_id' => 1, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 4, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 5
            ['course_id' => 5, 'doctor_id' => 2, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 5, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true],
            // المادة 6
            ['course_id' => 6, 'doctor_id' => 3, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 6, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 7
            ['course_id' => 7, 'doctor_id' => 1, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 7, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 18
            ['course_id' => 18, 'doctor_id' => 2, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 18, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 19
            ['course_id' => 19, 'doctor_id' => 3, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 19, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true],
            // المادة 20
            ['course_id' => 20, 'doctor_id' => 1,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 20, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 21
            ['course_id' => 21, 'doctor_id' => 2,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 21, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 26
            ['course_id' => 26, 'doctor_id' => 3,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 26, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 27
            ['course_id' => 27, 'doctor_id' => 1, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 27, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true],
            // المادة 31
            ['course_id' => 31, 'doctor_id' => 2, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 31, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 34
            ['course_id' => 34, 'doctor_id' => 3, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 34, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 37
            ['course_id' => 37, 'doctor_id' => 1, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 37, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 38
            ['course_id' => 38, 'doctor_id' => 2, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 38, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true],
            // المادة 39
            ['course_id' => 39, 'doctor_id' => 3, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 39, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 48
            ['course_id' => 48, 'doctor_id' => 1,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 48, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 49
            ['course_id' => 49, 'doctor_id' => 2,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 49, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 50
            ['course_id' => 50, 'doctor_id' => 3,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 50, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true],
            // المادة 72
            ['course_id' => 72, 'doctor_id' => 1, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 72, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],

            // =====================================================================
            // قسم الذكاء الاصطناعي (department_id = 2)
            // المواد: 8-14, 32, 41, 57, 62-63, 68-71
            // =====================================================================
            // المادة 8
            ['course_id' => 8, 'doctor_id' => 2, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 8, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true],
            // المادة 9
            ['course_id' => 9, 'doctor_id' => 3, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 9, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 10
            ['course_id' => 10, 'doctor_id' => 1, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 10, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 11
            ['course_id' => 11, 'doctor_id' => 2, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 11, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 12
            ['course_id' => 12, 'doctor_id' => 3, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 12, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true],
            // المادة 13
            ['course_id' => 13, 'doctor_id' => 1,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 13, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 14
            ['course_id' => 14, 'doctor_id' => 2,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 14, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 32
            ['course_id' => 32, 'doctor_id' => 3,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 32, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 41
            ['course_id' => 41, 'doctor_id' => 1, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 41, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true],
            // المادة 57
            ['course_id' => 57, 'doctor_id' => 2, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 57, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 62
            ['course_id' => 62, 'doctor_id' => 3, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 62, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 63
            ['course_id' => 63, 'doctor_id' => 1, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 63, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 68
            ['course_id' => 68, 'doctor_id' => 2, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 68, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true],
            // المادة 69
            ['course_id' => 69, 'doctor_id' => 3, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 69, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 70
            ['course_id' => 70, 'doctor_id' => 1,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 70, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 71
            ['course_id' => 71, 'doctor_id' => 2,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 71, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],

            // =====================================================================
            // قسم النظم والشبكات الحاسوبية (department_id = 3)
            // المواد: 15-17, 28, 33, 40, 42-44, 51-53, 58-61, 64-67
            // =====================================================================
            // المادة 15
            ['course_id' => 15, 'doctor_id' => 3,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 15, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true],
            // المادة 16
            ['course_id' => 16, 'doctor_id' => 1, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 16, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 17
            ['course_id' => 17, 'doctor_id' => 2, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 17, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 28
            ['course_id' => 28, 'doctor_id' => 3, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 28, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 33
            ['course_id' => 33, 'doctor_id' => 1, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 33, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true],
            // المادة 40
            ['course_id' => 40, 'doctor_id' => 2, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 40, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 42
            ['course_id' => 42, 'doctor_id' => 3, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 42, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 43
            ['course_id' => 43, 'doctor_id' => 1,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 43, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 44
            ['course_id' => 44, 'doctor_id' => 2,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 44, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true],
            // المادة 51
            ['course_id' => 51, 'doctor_id' => 3,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 51, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 52
            ['course_id' => 52, 'doctor_id' => 1, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 52, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 53
            ['course_id' => 53, 'doctor_id' => 2, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 53, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 58
            ['course_id' => 58, 'doctor_id' => 3, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 58, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true],
            // المادة 59
            ['course_id' => 59, 'doctor_id' => 1, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 59, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 60
            ['course_id' => 60, 'doctor_id' => 2, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 60, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 61
            ['course_id' => 61, 'doctor_id' => 3, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 61, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 64
            ['course_id' => 64, 'doctor_id' => 1,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 64, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true],
            // المادة 65
            ['course_id' => 65, 'doctor_id' => 2,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 65, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 66
            ['course_id' => 66, 'doctor_id' => 3,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 66, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 67
            ['course_id' => 67, 'doctor_id' => 1, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 67, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],

            // =====================================================================
            // قسم العلوم الأساسية (department_id = 4)
            // المواد: 22-25, 29-30, 35-36, 45-47
            // =====================================================================
            // المادة 22
            ['course_id' => 22, 'doctor_id' => 2, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 22, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true],
            // المادة 23
            ['course_id' => 23, 'doctor_id' => 3, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 23, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 24
            ['course_id' => 24, 'doctor_id' => 1, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 24, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 25
            ['course_id' => 25, 'doctor_id' => 2, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 25, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 29
            ['course_id' => 29, 'doctor_id' => 3, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 29, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true],
            // المادة 30
            ['course_id' => 30, 'doctor_id' => 1,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 30, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 35
            ['course_id' => 35, 'doctor_id' => 2,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 35, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 36
            ['course_id' => 36, 'doctor_id' => 3,  'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 36, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 45
            ['course_id' => 45, 'doctor_id' => 1, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 45, 'doctor_id' => null, 'ta_id' => 1,   'staff_id' => null, 'is_advisor' => true],
            // المادة 46
            ['course_id' => 46, 'doctor_id' => 2, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 46, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
            // المادة 47
            ['course_id' => 47, 'doctor_id' => 3, 'ta_id' => null, 'staff_id' => null, 'is_advisor' => true],
            ['course_id' => 47, 'doctor_id' => null, 'ta_id' => 1,  'staff_id' => null, 'is_advisor' => true],
        ];
        foreach ($data as $item) {
            CourseStaff::create([
                'course_id' => $item['course_id'],
                'doctor_id' => $item['doctor_id'],
                'ta_id' => $item['ta_id'],
                'staff_id' => $item['staff_id'],
                'is_advisor' => $item['is_advisor'],
            ]);
        }
    }
}
