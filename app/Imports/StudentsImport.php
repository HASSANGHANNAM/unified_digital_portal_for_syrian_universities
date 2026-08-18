<?php

namespace App\Imports;

use App\Exceptions\ExcelImportValidationException;
use App\Models\College;
use App\Models\Course;
use App\Models\Department;
use App\Models\Person;
use App\Models\Student;
use App\Models\StudentCourse;
use App\Models\StudyPlanCourse;
use App\Models\University;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentsImport implements
    ToCollection,
    WithHeadingRow,
    WithValidation,
    SkipsEmptyRows
{
    private array $report = [
        'imported' => 0,
        'students' => [],
    ];

    private array $errors = [];

    public function __construct(
        private readonly int $allowedUniversityId,
        private readonly int $allowedCollegeId
    ) {
    }

    /**
     * Import Excel rows.
     */
    public function collection(Collection $rows)
    {
        /*
        |--------------------------------------------------------------------------
        | Phase 1: Validate the complete Excel file
        |--------------------------------------------------------------------------
        */

        $this->validateDuplicateRows($rows);

        $this->validateExistingNationalNumbers($rows);

        $this->validateRows($rows);

        /*
        |--------------------------------------------------------------------------
        | If there are any errors, stop the import completely.
        |--------------------------------------------------------------------------
        */

        if (!empty($this->errors)) {
            throw new ExcelImportValidationException(
                $this->errors
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Phase 2: Create students
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($rows) {

            foreach ($rows as $index => $row) {

                $rowNumber = $index + 2;


                $nationalNumber = trim(
                    (string) ($row['national_number'] ?? '')
                );

                $fullName = trim(
                    (string) ($row['full_name'] ?? '')
                );

                $birthDate = $this->parseBirthDate(
                    $row['birth_date'] ?? null,
                    $rowNumber
                );

                $universityName = trim(
                    (string) ($row['university_name'] ?? '')
                );

                $collegeName = trim(
                    (string) ($row['college_name'] ?? '')
                );

                $departmentName = trim(
                    (string) ($row['department_name'] ?? '')
                );

                /*
                |--------------------------------------------------------------------------
                | Get University
                |--------------------------------------------------------------------------
                */

                $university = University::where(
                    'name',
                    $universityName
                )->first();


                $college = College::where(
                    'name',
                    $collegeName
                )
                    ->where(
                        'university_id',
                        $university->id
                    )
                    ->first();

                $department = Department::where(
                    'name',
                    $departmentName
                )
                    ->where(
                        'college_id',
                        $college->id
                    )
                    ->first();



                $username = $fullName . '-' . $nationalNumber;

                $enrollmentYear = now()->year;

                $serialNumber = $this->getNextSerialNumber(
                    $enrollmentYear,
                    $university->id,
                    $college->id
                );

                /*
                |--------------------------------------------------------------------------
                | Generate student ID number
                |--------------------------------------------------------------------------
                |
                | Example:
                |
                | 2024 + 1 + 14 + 00105
                |
                | = 202411400105
                |
                */

                $studentIdNumber =
                    $enrollmentYear .
                    $university->id .
                    $college->id .
                    str_pad(
                        $serialNumber,
                        5,
                        '0',
                        STR_PAD_LEFT
                    );


                $person = Person::create([
                    'national_id' => $nationalNumber,
                    'full_name' => $fullName,
                    'phone' => null,
                    'birth_date' => $birthDate,
                    'national_number' => $nationalNumber,
                    'address' => null,
                ]);

                User::create([
                    'username' => $username,
                    'password' => Hash::make($nationalNumber),
                    'status' => 'inactive',
                    'email' => null,
                    'last_login' => null,
                    'person_id' => $person->id,
                    'email_verified_at' => null,
                    'new_password' => null,
                ]);

                $student = Student::create([
                    'student_id_number' => $studentIdNumber,
                    'academic_status' => 'مستمر',
                    'major' => 'العلوم الأساسية',
                    'enrollment_year' => $enrollmentYear,
                    'current_gpa' => 0.0,
                    'advisor_id' => null,
                    'person_id' => $person->id,
                    'college_id' => $college->id,
                    'department_id' => $department->id,
                ]);

                $this->assignFirstSemesterCourses(
                    $student,
                    $department->id,
                    $enrollmentYear
                );

                $this->report['imported']++;

                $this->report['students'][] = [
                    'row' => $rowNumber,
                    'student_id_number' => $student->student_id_number,
                    'username' => $username,
                    'full_name' => $fullName,
                ];
            }
        });
    }

    /**
     * Assign first-year / first-semester courses to the student.
     */
    private function assignFirstSemesterCourses(
        Student $student,
        int $departmentId,
        int $enrollmentYear
    ): void {

        $studyPlanCourses = StudyPlanCourse::where(
            'department_id',
            $departmentId
        )
            ->where('year', 1)
            ->where('semester', 1)
            ->get();

        foreach ($studyPlanCourses as $studyPlanCourse) {

            $course = Course::find(
                $studyPlanCourse->course_id
            );

            if (!$course) {
                throw new \Exception(
                    "Course with ID {$studyPlanCourse->course_id} " .
                    "was not found."
                );
            }

            StudentCourse::create([
                'course_id' => $course->id,
                'credits' => $course->credits,
                'status'        => 'pass',

                'academic_year' => $enrollmentYear,
                'semester' => 1,
                'student_id' => $student->id,
            ]);
        }
    }

    /**
     * Validate all rows.
     */
    private function validateRows(Collection $rows): void
    {
        foreach ($rows as $index => $row) {

            $rowNumber = $index + 2;

            $universityName = trim(
                (string) ($row['university_name'] ?? '')
            );

            $collegeName = trim(
                (string) ($row['college_name'] ?? '')
            );

            $departmentName = trim(
                (string) ($row['department_name'] ?? '')
            );

            /*
            |--------------------------------------------------------------------------
            | University
            |--------------------------------------------------------------------------
            */

            $university = University::where(
                'name',
                $universityName
            )->first();

            if (!$university) {

                $this->addError(
                    $rowNumber,
                    'university_name',
                    'university_not_found',
                    "University '{$universityName}' was not found."
                );

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Check staff university permission
            |--------------------------------------------------------------------------
            */

            if ($university->id !== $this->allowedUniversityId) {

                $this->addError(
                    $rowNumber,
                    'university_name',
                    'unauthorized_university',
                    "You are not authorized to import students from university '{$universityName}'."
                );

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | College
            |--------------------------------------------------------------------------
            */

            $college = College::where(
                'name',
                $collegeName
            )
                ->where(
                    'university_id',
                    $university->id
                )
                ->first();

            if (!$college) {

                $this->addError(
                    $rowNumber,
                    'college_name',
                    'college_not_found',
                    "College '{$collegeName}' was not found in university '{$universityName}'."
                );

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Check staff college permission
            |--------------------------------------------------------------------------
            */

            if ($college->id !== $this->allowedCollegeId) {

                $this->addError(
                    $rowNumber,
                    'college_name',
                    'unauthorized_college',
                    "You are not authorized to import students from college '{$collegeName}'."
                );

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Department
            |--------------------------------------------------------------------------
            */

            $department = Department::where(
                'name',
                $departmentName
            )
                ->where(
                    'college_id',
                    $college->id
                )
                ->first();

            if (!$department) {

                $existingDepartment = Department::where(
                    'name',
                    $departmentName
                )->first();

                if ($existingDepartment) {

                    $this->addError(
                        $rowNumber,
                        'department_name',
                        'department_not_in_college',
                        "Department '{$departmentName}' does not belong to college '{$collegeName}'."
                    );

                } else {

                    $this->addError(
                        $rowNumber,
                        'department_name',
                        'department_not_found',
                        "Department '{$departmentName}' was not found."
                    );
                }
            }
        }
    }

    /**
     * Check duplicate national numbers inside Excel.
     */
    private function validateDuplicateRows(Collection $rows): void
    {
        $nationalNumbers = [];

        foreach ($rows as $index => $row) {

            $rowNumber = $index + 2;

            $nationalNumber = trim(
                (string) ($row['national_number'] ?? '')
            );

            if ($nationalNumber === '') {
                continue;
            }

            if (isset($nationalNumbers[$nationalNumber])) {

                $firstRow = $nationalNumbers[$nationalNumber];

                $this->addError(
                    $rowNumber,
                    'national_number',
                    'duplicate_national_number',
                    "National number '{$nationalNumber}' is duplicated in the Excel file. It already appears in row {$firstRow}."
                );

                continue;
            }

            $nationalNumbers[$nationalNumber] = $rowNumber;
        }
    }

    /**
     * Check national numbers already existing in database.
     */
    private function validateExistingNationalNumbers(
        Collection $rows
    ): void {

        $nationalNumbers = $rows
            ->map(function ($row) {

                return trim(
                    (string) ($row['national_number'] ?? '')
                );
            })
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        if (empty($nationalNumbers)) {
            return;
        }

        $existingNumbers = Person::whereIn(
            'national_number',
            $nationalNumbers
        )
            ->pluck('national_number')
            ->toArray();

        foreach ($existingNumbers as $nationalNumber) {

            $row = $rows->search(function ($item) use ($nationalNumber) {

                return trim(
                    (string) ($item['national_number'] ?? '')
                ) === $nationalNumber;
            });

            $rowNumber = $row !== false
                ? $row + 2
                : null;

            $this->addError(
                $rowNumber,
                'national_number',
                'national_number_exists',
                "National number '{$nationalNumber}' already exists in the system."
            );
        }
    }

    /**
     * Add validation error.
     */
    private function addError(
        ?int $row,
        string $field,
        string $error,
        string $message
    ): void {

        $this->errors[] = [
            'row' => $row,
            'field' => $field,
            'error' => $error,
            'message' => $message,
        ];
    }

    /**
     * Parse birth date.
     */
    private function parseBirthDate(
        $value,
        int $rowNumber
    ): ?string {

        if (empty($value)) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {

            return Carbon::instance($value)
                ->format('Y-m-d');
        }

        try {

            return Carbon::parse($value)
                ->format('Y-m-d');

        } catch (\Throwable $exception) {

            throw new \Exception(
                "Invalid birth date '{$value}' in row {$rowNumber}."
            );
        }
    }

    /**
     * Generate next serial number.
     */
    private function getNextSerialNumber(
        int $enrollmentYear,
        int $universityId,
        int $collegeId
    ): int {

        $prefix =
            $enrollmentYear .
            $universityId .
            $collegeId;

        $lastStudent = Student::where(
            'student_id_number',
            'like',
            $prefix . '%'
        )
            ->orderByDesc('student_id_number')
            ->lockForUpdate()
            ->first();

        if (!$lastStudent) {
            return 1;
        }

        $lastSerial = (int) substr(
            $lastStudent->student_id_number,
            strlen($prefix)
        );

        $nextSerial = $lastSerial + 1;

        if ($nextSerial > 99999) {

            throw new \Exception(
                "Serial number exceeded 99999 for " .
                "year {$enrollmentYear}, " .
                "university {$universityId}, " .
                "college {$collegeId}."
            );
        }

        return $nextSerial;
    }

    /**
     * Excel validation rules.
     */
    public function rules(): array
    {
        return [
            'national_number' => [
                'required',
            ],

            'full_name' => [
                'required',
            ],

            'birth_date' => [
                'required',
            ],

            'university_name' => [
                'required',
            ],

            'college_name' => [
                'required',
            ],

            'department_name' => [
                'required',
            ],
        ];
    }

    /**
     * Excel validation messages.
     */
    public function customValidationMessages(): array
    {
        return [
            'national_number.required' =>
                'national_number is required.',

            'full_name.required' =>
                'full_name is required.',

            'birth_date.required' =>
                'birth_date is required.',

            'university_name.required' =>
                'university_name is required.',

            'college_name.required' =>
                'college_name is required.',

            'department_name.required' =>
                'department_name is required.',
        ];
    }

    /**
     * Get validation errors.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get import report.
     */
    public function getReport(): array
    {
        return $this->report;
    }
}
