<?php

namespace Tests\Unit;

use App\DTOs\CourseDetailsDTO;
use App\Models\Course;
use App\Models\CoursePart;
use App\Models\Person;
use App\Models\Sanction;
use App\Models\SanctionType;
use App\Models\Student;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\SanctionRepositoryInterface;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Services\CourseService;
use App\Services\SanctionService;
use App\Services\StudentService;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class ReportingApisServiceTest extends TestCase
{
    public function test_course_service_maps_course_details_with_parts(): void
    {
        $course = new Course([
            'id' => 1,
            'code' => 'CS101',
            'credits' => 3,
            'college_id' => 1,
            'department_id' => 2,
        ]);
        $course->setRelation('courseParts', collect([
            new CoursePart(['id' => 1, 'name' => 'عملي', 'percentage' => 30]),
            new CoursePart(['id' => 2, 'name' => 'نظري', 'percentage' => 70]),
        ]));

        $repository = new class($course) implements CourseRepositoryInterface {
            public function __construct(private Course $course) {}
            public function all(): Collection
            {
                return new Collection();
            }
            public function create(array $data): Course
            {
                return new Course();
            }
            public function update(Course $course, array $data): bool
            {
                return true;
            }
            public function delete(string $id): bool
            {
                return true;
            }
            public function findById(string $id): ?Course
            {
                return null;
            }
            public function hasCourseAccess(\App\Models\User $user, int $courseId): array
            {
                return [];
            }
            public function getCourseWithParts(int $courseId, $perPage = 15)
            {
                return $this->course;
            }
        };

        $service = new CourseService($repository);
        $result = $service->getCourseDetails(['course_id' => 1]);

        $this->assertSame('تم جلب تفاصيل المقرر بنجاح', $result['message']);
        $this->assertSame(200, $result['code']);
        $this->assertSame('CS101', $result['data']['data'][0]['code']);
        $this->assertCount(2, $result['data']['data'][0]['parts']);
        $this->assertSame(1, $result['data']['meta']['total']);
    }

    public function test_student_service_maps_students_with_person_name(): void
    {
        $person = new Person(['id' => 7, 'full_name' => 'أحمد محمد العلي']);
        $student = new Student([
            'id' => 10,
            'student_id_number' => '2024001',
            'major' => 'هندسة برمجيات',
            'enrollment_year' => 2024,
            'current_gpa' => 3.5,
            'department_id' => 2,
            'college_id' => 1,
        ]);
        $student->setRelation('person', $person);

        $repository = new class($student) implements StudentRepositoryInterface {
            public function __construct(private Student $student) {}
            public function all(): Collection
            {
                return new Collection();
            }
            public function create(array $data): Student
            {
                return new Student();
            }
            public function update(Student $student, array $data): bool
            {
                return true;
            }
            public function delete(string $id): bool
            {
                return true;
            }
            public function findById(string $id): ?Student
            {
                return null;
            }
            public function findByStudentNumber(string $studentNumber)
            {
                return null;
            }
            public function getPendingStudents()
            {
                return new Collection();
            }
            public function getStudentsByCollege(int $collegeId, $perPage = 15, $page = 1)
            {
                return new \Illuminate\Pagination\LengthAwarePaginator([$this->student], 1, 15);
            }
        };

        $service = new StudentService($repository);
        $result = $service->getCollegeStudents(['college_id' => 1]);

        $this->assertSame('تم جلب الطلاب بنجاح', $result['message']);
        $this->assertSame(200, $result['code']);
        $this->assertSame('أحمد محمد العلي', $result['data']['data'][0]['full_name']);
        $this->assertSame(1, $result['data']['meta']['total']);
    }

    public function test_sanction_service_maps_sanctions_with_type_details(): void
    {
        $sanctionType = new SanctionType(['id' => 2, 'reason' => 'إزعاج الآخرين', 'name' => 'إنذار سلوكي']);
        $sanction = new Sanction([
            'id' => 2,
            'status' => 'expired',
            'issued_date' => '2026-04-10',
            'expiry_date' => '2026-05-10',
            'notes' => 'تسبب الطالب في إزعاج الآخرين داخل المحاضرة.',
            'student_response' => 'أعتذر عن سلوكي.',
            'staff_response' => 'تم توجيه تنبيه للطالب.',
            'course_id' => null,
            'staff_id' => 3,
        ]);
        $sanction->setRelation('sanctionType', $sanctionType);

        $repository = new class($sanction) implements SanctionRepositoryInterface {
            public function __construct(private Sanction $sanction) {}
            public function all(): Collection
            {
                return new Collection();
            }
            public function create(array $data): Sanction
            {
                return new Sanction();
            }
            public function update(Sanction $sanction, array $data): bool
            {
                return true;
            }
            public function delete(string $id): bool
            {
                return true;
            }
            public function findById(string $id): ?Sanction
            {
                return null;
            }
            public function getStudentSanctions(int $studentId)
            {
                return new \Illuminate\Pagination\LengthAwarePaginator([$this->sanction], 1, 15);
            }
            public function getStudentSanctionById(int $studentId, int $sanctionId)
            {
                return null;
            }
            public function updateResponse(int $sanctionId, array $data): bool
            {
                return true;
            }
            public function getSanctionsByStudent(int $studentId, $perPage = 15, $page = 1)
            {
                return new \Illuminate\Pagination\LengthAwarePaginator([$this->sanction], 1, 15);
            }
            public function getPaginatedWithFilters(array $filters, int $perPage = 15)
            {
                return new \Illuminate\Pagination\LengthAwarePaginator([], 0, $perPage, 1);
            }
            public function createSanctionType(array $data)
            {
                return new SanctionType();
            }
        };

        $service = new SanctionService($repository);
        $result = $service->getStudentSanctions(['student_id' => 10]);

        $this->assertSame('تم جلب العقوبات بنجاح', $result['message']);
        $this->assertSame(200, $result['code']);
        $this->assertSame('إنذار سلوكي', $result['data']['data'][0]['type_name']);
        $this->assertSame(1, $result['data']['meta']['total']);
    }
}
