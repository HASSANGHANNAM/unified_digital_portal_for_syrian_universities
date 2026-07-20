<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\StudentMarksImportController;
use App\Http\Controllers\Api\V1\AcademicController;
use App\Http\Controllers\Api\V1\AdminController;
use App\Http\Controllers\Api\V1\AdmissionController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CollegeController;
use App\Http\Controllers\Api\V1\DocumentController;
use App\Http\Controllers\Api\V1\GradeController;
use App\Http\Controllers\Api\V1\MaterialController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\RequestController;
use App\Http\Controllers\Api\V1\SanctionController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\StudyPlanController;
use App\Http\Controllers\Api\V1\StudentAttachment;
use App\Http\Controllers\Api\V1\AffairController; // من فرع student_profile

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Routes المشتركة خارج مجموعة auth (من HEAD)
Route::post('/addGrade', [GradeController::class, 'addGrade']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/refreshToken', [AuthController::class, 'refreshToken']);

// ========== Routes داخل الـ prefix V1 (من كلا الفرعين) ==========
Route::prefix('V1')->group(function () {

    // مجموعة auth:sanctum من HEAD (تحتوي على /my-grades)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('notifications', [NotificationController::class, 'index']);
        Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount']);
        Route::patch('notifications/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead']);

        Route::get('/users', [UserController::class, 'getUsers']);
        Route::post('/user', [UserController::class, 'addUser']);
        Route::put('/user/{id}/role', [UserController::class, 'updateUserRole']);
        Route::delete('/user/{id}', [UserController::class, 'deleteUser']);
        Route::get('/user/{id}/permissions', [UserController::class, 'getUserPermissions']);
        Route::put('/user/{id}/activate', [UserController::class, 'toggleUserActivation']);
        Route::get('/schedule', [AcademicController::class, 'getSchedule']);
        Route::get('/courses', [AcademicController::class, 'getStudentCourses']);  // الأولى
        Route::get('/grades', [AcademicController::class, 'getGrades']);
        Route::get('/courses', [AcademicController::class, 'getInstructorCourses']); // الثانية (مكررة)
        Route::get('/teaching-assistants', [AcademicController::class, 'getTeachingAssistants']);
        Route::get('/courses/{courseId}/students', [AcademicController::class, 'getCourseStudents']);
        Route::get('/courses/{courseId}/materials', [MaterialController::class, 'getMaterials']);
        Route::post('/courses/{courseId}/materials', [MaterialController::class, 'uploadMaterial']);
        Route::put('/materials/{materialId}', [MaterialController::class, 'updateMaterial']);
        Route::delete('/materials/{materialId}', [MaterialController::class, 'deleteMaterial']);
        Route::get('/materials/{materialId}/download', [MaterialController::class, 'downloadMaterial']);
        Route::get('/requestsInStudentCollege', [RequestController::class, 'requestsInStudentCollege']);
        Route::get('/requests', [RequestController::class, 'getStudentRequests']);
        Route::get('/staff_requests', [RequestController::class, 'staffRequests']);
        Route::post('/requests', [RequestController::class, 'store']);
        Route::get('/requests/{requestId}', [RequestController::class, 'getRequestDetails']);
        Route::post('/requests/{requestId}/assign', [RequestController::class, 'assignRequest']);
        Route::get('/staff_requests/{requestId}', [RequestController::class, 'getStaffRequestDetails']);
        Route::post('/requests/{requestId}/cancel', [RequestController::class, 'cancelRequest']);
        Route::get('/allRequests', [RequestController::class, 'getAllRequests']);
        Route::put('/requests/{requestId}/review', [RequestController::class, 'reviewRequest']);
        Route::get('/request-type-media/{request_type_id}', [RequestController::class, 'getMediaByRequestTypeId']);
        Route::post('/request-user/{requestUserId}/approve', [RequestController::class, 'approveRequest']);
        Route::get('/media/{id}', [\App\Http\Controllers\Api\V1\FileStorageController::class, 'viewMedia']);
        Route::get('/pdf/{request}', [\App\Http\Controllers\Api\V1\FileStorageController::class, 'viewPdf']);
        Route::get('/allGrades', [GradeController::class, 'getAllGrades']);
        Route::post('/addGrade', [GradeController::class, 'addGrade']);
        Route::get('/my-grades/{courseId}', [GradeController::class, 'getgrade']); // موجودة هنا في HEAD
        Route::get('/grades/appeals', [GradeController::class, 'getGradeAppeals']);
        Route::put('/grades/appeals/{appealId}', [GradeController::class, 'processAppeal']);
        Route::get('/invoices', [PaymentController::class, 'getInvoices']);
        Route::post('/invoices/{invoiceId}/pay', [PaymentController::class, 'payInvoice']);
        Route::post('/payments/callback', [PaymentController::class, 'paymentCallback']);
        Route::get('/payments/status/{paymentId}', [PaymentController::class, 'paymentStatus']);
        Route::get('/dashboard/stats', [AdminController::class, 'dashboardStats']);
        Route::get('/audit-logs', [AdminController::class, 'getAuditLogs']);
        Route::post('/apply', [AdmissionController::class, 'applyForAdmission']);
        Route::get('/my-application', [AdmissionController::class, 'getMyApplication']);
        Route::get('/applications', [AdmissionController::class, 'getApplications']);
        Route::put('/applications/{id}/review', [AdmissionController::class, 'reviewApplication']);
        Route::get('/sanctions', [SanctionController::class, 'getSanctions']);
        Route::post('/sanction', [SanctionController::class, 'addSanction']);
        Route::put('/sanctions/{sanctionId}', [SanctionController::class, 'updateSanction']);
        Route::delete('/sanctions/{sanctionId}', [SanctionController::class, 'deleteSanction']);
        Route::get('/sanction-types', [SanctionController::class, 'index']);
        Route::post('/sanction-type', [SanctionController::class, 'store']);
        Route::post('/student/{studentId}/documents', [DocumentController::class, 'addDocument']);
        Route::get('/students/{studentId}/documents', [DocumentController::class, 'getDocuments']);
        Route::post('/signatures', [UserController::class, 'uploadSignature']);
        Route::post('/colleges/{collegeId}/logo', [CollegeController::class, 'uploadLogo']);
    });

    // ملاحظة: لم نضع هنا مجموعة auth:sanctum من student_profile لأنها خارج V1 في الأصل
});

// ========== Routes خارج الـ prefix V1 (من كلا الفرعين) ==========

// مجموعة auth:sanctum من HEAD (الخاصة بالطلاب)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/resend-code', [AuthController::class, 'resendCode']);
    Route::post('/verify-code', [AuthController::class, 'verifyCode']);
    Route::post('/setup-account', [ProfileController::class, 'setupAccount']);
    Route::post('/upload-document', [ProfileController::class, 'uploadDocument']);
    Route::post('/complete-profile', [ProfileController::class, 'completeProfile']);
    Route::post('/submit', [ProfileController::class, 'submit']);
    Route::get('/profile', [AuthController::class, 'getProfile']);
    Route::post('/edit-profile', [AuthController::class, 'editProfile']);
    Route::get('/profile-image', [AuthController::class, 'getProfileImage']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/verify-reset-code', [AuthController::class, 'verifyResetCode']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::get('/my-grades/{courseId}', [GradeController::class, 'getgrade']); // مكررة مع التي داخل V1
    Route::get('/all-my-grades', [GradeController::class, 'getAllMyGrades']);
    Route::get('/all-my-sanctions', [SanctionController::class, 'getAllSanctions']);
    Route::get('/sanctions-details/{sanctionId}', [SanctionController::class, 'getSanctionDetails']);
    Route::post('/sanctions-respond/{sanctionId}', [SanctionController::class, 'respondToSanction']);
    Route::get('/study-plan', [StudyPlanController::class, 'getStudyPlan']);
    Route::get('/study-plan/year/{year}', [StudyPlanController::class, 'getYearCourses']);
    Route::get('/study-plan/course/{courseId}', [StudyPlanController::class, 'getCourseDetails']);
    Route::get('/study-plan/search', [StudyPlanController::class, 'searchCourses']);
    Route::get('/current-semester', [StudyPlanController::class, 'getCurrentSemesterCourses']);
    Route::get('/current-year', [StudyPlanController::class, 'getCurrentYearCourses']);
    Route::get('/completed-courses', [StudyPlanController::class, 'getCompletedCourses']);
    Route::get('/remaining-courses', [StudyPlanController::class, 'getRemainingCourses']);
    Route::get('/academic-progress', [StudyPlanController::class, 'getAcademicProgress']);
    Route::get('/student-affairs/students/{personId}', [StudentAttachment::class, 'getStudentAttachments']);
    Route::post('/student-affairs/{personId}', [StudentAttachment::class, 'reviewStudent']);
    // من student_profile
    Route::get('/student-affairs/pending-students', [StudentAttachment::class, 'getPendingStudents']);

    Route::get('/request-types', [RequestController::class, 'getRequestTypes']);
    Route::get('/requests-list', [RequestController::class, 'getRequestsList']);
    Route::get('/request-details/{requestId}', [RequestController::class, 'RequestDetails']);

    Route::get('/course-grades/{courseId}/{academicYear}/{semester}', [GradeController::class, 'getCourseGrades']);
    Route::put('/grades/{studentCoursePartId}', [GradeController::class, 'updateGrade']);
    Route::post('/grades-for-one-student/{courseId}/{academicYear}/{semester}', [GradeController::class, 'addGradesforonestudent']);

    // من student_profile (إضافات)
    Route::get('/student-home', [ProfileController::class, 'getHomePage']);
    Route::get('/student-academic-profile', [ProfileController::class, 'getAcademicProfile']);
});

// Route logout مع CheckStatus من HEAD (خارج V1)
Route::middleware(['auth:sanctum', 'CheckStatus'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Route الخاصة بـ student_profile (خارج V1)
Route::get('/student-affairs/courses', [AffairController::class, 'getCollegeCourses']);

// Route /user من كلا الفرعين (مرة واحدة)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ========== Routes الخاصة بـ throttle (من كلا الملفين) ==========
Route::prefix('v1')->middleware('throttle:api')->group(function () {});

Route::prefix('v1')->group(function () {
    Route::middleware('throttle:login')->group(function () {});
});

Route::prefix('v1')->middleware(['auth:sanctum', 'throttle:premium-api'])->group(function () {
    Route::middleware('throttle:heavy')->group(function () {});
});
