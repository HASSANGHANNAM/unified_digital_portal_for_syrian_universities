<?php

namespace App\Imports;

use App\Models\Course;
use App\Models\Student;
use App\Models\StudentCourse;
use App\Models\StudentCoursePart;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;



class StudentMarksImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    private int $courseId;
    private string $academicYear;
    private int $semester;


    private int $importedCount = 0;
    private array $skippedRows = [];
    private array $errors = [];

    public function __construct(int $courseId, string $academicYear, int $semester)
    {
        $this->courseId = $courseId;
        $this->academicYear = $academicYear;
        $this->semester = $semester;
    }


    public function collection(Collection $rows): void
    {
        $course = Course::with('parts')->find($this->courseId);
        if (!$course) {
            $this->errors[] = ['error' => 'Course not found', 'course_id' => $this->courseId];
            return;
        }

        $parts = $course->parts->mapWithKeys(function ($part) {
            return [$this->normalizeHeader($part->name) => $part];
        });

        DB::transaction(function () use ($rows, $parts, $course) {
            $studentLookup = [];
            $studentNumbers = [];

            // 1. تجميع الأرقام الجامعية للتحضير للـ Eager Loading
            foreach ($rows as $row) {
                $rowMap = $this->normalizeRowKeys($row);
                $studentNumber = $this->findStudentNumber($rowMap);
                if ($studentNumber !== null) {
                    $studentNumbers[] = $studentNumber;
                }
            }

            $studentNumbers = array_filter(array_unique($studentNumbers));
            if (!empty($studentNumbers)) {
                $studentLookup = Student::whereIn('student_id_number', $studentNumbers)
                    ->get()
                    ->keyBy('student_id_number')
                    ->toArray();
            }

            // 2. معالجة الصفوف
            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2; // مراعاة السطر الأول كـ Header
                $rowMap = $this->normalizeRowKeys($row);

                try {
                    $studentNumber = $this->findStudentNumber($rowMap);
                    if (empty($studentNumber)) {
                        $this->skippedRows[] = ['row' => $rowNumber, 'reason' => 'missing_student_number'];
                        continue;
                    }

                    $student = isset($studentLookup[$studentNumber])
                        ? (object)$studentLookup[$studentNumber]
                        : Student::where('student_id_number', $studentNumber)->first();

                    if (!$student) {
                        $this->skippedRows[] = ['row' => $rowNumber, 'student_number' => $studentNumber, 'reason' => 'student_not_found'];
                        continue;
                    }

                    $partsData = [];
                    $matchedPartCount = 0;

                    // مطابقة واستخراج الأجزاء الموجودة في الشيت فقط
                    foreach ($parts as $normalizedHeader => $part) {
                        $value = $this->findPartValue($rowMap, $part);

                        // إذا كان العمود غير موجود مطلقاً في الشيت المرفوع، يتم تخطيه وعدم تصفير علامته
                        if ($value === null) {
                            continue;
                        }
                        $value = trim((string)$value);

                        // إذا كان العمود موجوداً لكن الخلية فارغة
                        if ($value === '') {
                            $mark = 0.0;
                        } else {
                            if (!is_numeric($value)) {
                                throw new \RuntimeException("Non-numeric mark for part '{$part->name}' in row {$rowNumber}");
                            }
                            $mark = (float)$value;
                        }

                        // التحقق من أن العلامة ضمن المجال المسموح للقسم
                        if (isset($part->percentage) && $part->percentage !== null) {
                            if ($mark < 0 || $mark > (float)$part->percentage) {
                                throw new \RuntimeException("Mark for '{$part->name}' out of allowed range (0-{$part->percentage}) in row {$rowNumber}");
                            }
                        }

                        $matchedPartCount++;
                        $partsData[] = ['part' => $part, 'mark' => $mark];
                    }

                    // إذا لم يحتوي الشيت على أي عمود يطابق أجزاء الكورس
                    if ($matchedPartCount === 0) {
                        $this->skippedRows[] = ['row' => $rowNumber, 'reason' => 'columns_not_matched_or_empty'];
                        continue;
                    }

                    // جلب أو إنشاء سجل كورس الطالب
                    $studentCourse = StudentCourse::firstOrCreate(
                        [
                            'student_id' => $student->id,
                            'course_id' => $course->id,
                            'academic_year' => $this->academicYear,
                            'semester'      => $this->semester,

                        ],
                        ['credits' => 0, 'status' => 'fail']
                    );

                    // حفظ/تحديث العلامات للأجزاء الموجودة في الملف الحالي فقط
                    foreach ($partsData as $p) {
                        StudentCoursePart::updateOrCreate(
                            ['student_course_id' => $studentCourse->id, 'course_part_id' => $p['part']->id],
                            ['credits' => $p['mark'], 'published' => false]
                        );
                    }

                    // إعادة حساب الإجمالي الكلي لجميع أجزاء المادة المسجلة للطالب من قاعدة البيانات
                    $totalScore = StudentCoursePart::where('student_course_id', $studentCourse->id)->sum('credits');
                    $status = $totalScore >= 60.0 ? 'pass' : 'fail';

                    // تحديث النتيجة النهائية للمادة
                    $studentCourse->update([
                        'credits' => $totalScore,
                        'status' => $status
                    ]);

                    $this->importedCount++;
                } catch (\Throwable $e) {
                    $this->errors[] = ['row' => $rowNumber, 'error' => $e->getMessage()];
                    continue;
                }
            }
        });
    }

    private function normalizeRowKeys(Collection $row): array
    {
        $mapped = [];
        foreach ($row->keys() as $key) {
            if (!is_string($key)) {
                continue;
            }
            $mapped[$this->normalizeHeader($key)] = $row[$key];
        }
        return $mapped;
    }


    private function findStudentNumber(array $rowMap)
    {
        $candidates = ['الرقم_الجامعي', 'الرقم الجامعي', 'student_id_number', 'student_number', 'student_number', 'رقم_الجامعي', 'رقم_جامعي'];
        if ($value = $this->findValue($rowMap, $candidates)) {
            return trim((string)$value);
        }

        foreach ($rowMap as $key => $value) {
            if (str_contains($key, 'رقم') || str_contains($key, 'student')) {
                return trim((string)$value);
            }
        }

        return null;
    }

    private function findPartValue(array $rowMap, $part)
        {
            $partName = trim($part->name);
            $normalizedPart = $this->normalizeHeader($partName);


            foreach ($rowMap as $key => $rowValue) {
                $cleanedKey = trim((string)$key);
                $normalizedKey = $this->normalizeHeader($cleanedKey);

                if ($cleanedKey === $partName || $normalizedKey === $normalizedPart) {
                    return $rowValue;
                }

                if (str_contains($cleanedKey, $partName) || str_contains($normalizedKey, $normalizedPart)) {
                    return $rowValue;
                }
            }

            return null;
        }

    private function findValue(array $rowMap, array $candidates)
    {
        foreach ($candidates as $candidate) {
            $key = $this->normalizeHeader($candidate);
            if (array_key_exists($key, $rowMap)) {
                return $rowMap[$key];
            }
        }

        foreach ($candidates as $candidate) {
            $normalizedCandidate = $this->normalizeHeader($candidate);
            foreach ($rowMap as $key => $value) {
                $normalizedKey = $this->normalizeHeader($key);
                if (str_contains($normalizedKey, $normalizedCandidate) || str_contains($normalizedCandidate, $normalizedKey)) {
                    return $value;
                }
            }
        }

        return null;
    }


    private function normalizeHeader(string $header): string
    {
        $h = trim($header);
        $h = preg_replace('/\s+/u', '_', $h);
        $h = preg_replace('/[^\p{L}\p{N}_]/u', '', $h);
        return mb_strtolower($h);
    }

    private function getCell(Collection $row, array $candidates)
    {
        foreach ($candidates as $candidate) {
            if ($row->offsetExists($candidate)) {
                return $row[$candidate];
            }

            $norm = $this->normalizeHeader($candidate);
            if ($row->offsetExists($norm)) {
                return $row[$norm];
            }
        }
        return null;
    }


    public function getReport(): array
    {
        return [
            'imported' => $this->importedCount,
            'skipped' => count($this->skippedRows),
            'skipped_rows' => $this->skippedRows,
            'errors' => $this->errors,
        ];
    }


    public function chunkSize(): int
    {
        return 500;
    }
}
