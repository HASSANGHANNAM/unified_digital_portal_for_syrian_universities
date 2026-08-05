<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AcademicController;
use App\Http\Controllers\Api\V1\AdminController;
use App\Http\Controllers\Api\V1\AdmissionController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CollegeController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\DocumentController;
use App\Http\Controllers\Api\V1\DoctorController;
use App\Http\Controllers\Api\V1\TeachingAssistantController;
use App\Http\Controllers\Api\V1\GradeController;
use App\Http\Controllers\Api\V1\MaterialController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\RequestController;
use App\Http\Controllers\Api\V1\SanctionController;
use App\Http\Controllers\Api\V1\StudentController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\StudyPlanController;
use App\Http\Controllers\Api\V1\StudentAttachment;
use App\Http\Controllers\Api\V1\AffairController;
use App\Http\Controllers\Api\V1\FileStorageController;

// Login and RefreshToken
Route::post('/login', [AuthController::class, 'login']);
Route::post('/refreshToken', [AuthController::class, 'refreshToken']);

// add grade 
Route::post('/addGrade', [GradeController::class, 'addGrade']);

//  V1 
Route::prefix('V1')->group(function () {
    Route::middleware(['auth:sanctum',])->group(function () {
        Route::post('/setup-account', [ProfileController::class, 'setupAccount'])->middleware(['permission:setup account']);
        Route::post('/complete-profile', [ProfileController::class, 'completeProfile'])->middleware(['permission:complete profile']);
        Route::post('/upload-document', [ProfileController::class, 'uploadDocument'])->middleware(['permission:upload document']);
        Route::post('/submit', [ProfileController::class, 'submit'])->middleware(['permission:submit profile']);
        Route::get('/profile', [AuthController::class, 'getProfile'])->middleware(['permission:get profile']);
    });
});
Route::prefix('V1')->group(function () {
    Route::middleware(['auth:sanctum', 'CheckStatus'])->group(function () {
        // Notifications
        Route::get('notifications', [NotificationController::class, 'index'])->middleware(['permission:get my notifications']);
        Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount'])->middleware(['permission:get my count notifications unread']);
        Route::patch('notifications/{id}/read', [NotificationController::class, 'markAsRead'])->middleware(['permission:read notification']);
        Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->middleware(['permission:read all my notifications']);
        Route::post('notifications/broadcast', [NotificationController::class, 'broadcast']); //->middleware(['permission:send broadcast notifications']);

        // Auth and verify code and profile
        Route::post('/resend-code', [AuthController::class, 'resendCode'])->middleware(['permission:resend verification code']);
        Route::post('/verify-code', [AuthController::class, 'verifyCode'])->middleware(['permission:verify verification code']);
        Route::post('/edit-profile', [AuthController::class, 'editProfile'])->middleware(['permission:edit profile']);
        Route::get('/profile-image', [AuthController::class, 'getProfileImage'])->middleware(['permission:get profile image']);
        Route::post('/change-password', [AuthController::class, 'changePassword'])->middleware(['permission:change password']);
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware(['permission:forgot password']);
        Route::post('/verify-reset-code', [AuthController::class, 'verifyResetCode'])->middleware(['permission:verify reset code']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware(['permission:reset password']);
        Route::get('/student-home', [ProfileController::class, 'getHomePage'])->middleware(['permission:get student home']);
        Route::get('/student-academic-profile', [ProfileController::class, 'getAcademicProfile'])->middleware(['permission:get academic profile']);

        // User Managment
        Route::get('/users', [UserController::class, 'getUsers'])->middleware(['permission:get all users in our system']);
        Route::post('/user', [UserController::class, 'addUser'])->middleware(['permission:add user']);
        Route::put('/user/{id}/role', [UserController::class, 'updateUserRole'])->middleware(['permission:update user role']);
        Route::delete('/user/{id}', [UserController::class, 'deleteUser'])->middleware(['permission:delete user']);
        Route::get('/user/{id}/permissions', [UserController::class, 'getUserPermissions'])->middleware(['permission:get user permissions']);
        Route::put('/user/{id}/activate', [UserController::class, 'toggleUserActivation'])->middleware(['permission:toggle user activation']);
        Route::get('/teaching-assistants', [AcademicController::class, 'getTeachingAssistants'])->middleware(['permission:get teaching assistants']);

        // Schedule
        Route::get('/schedule', [AcademicController::class, 'getSchedule'])->middleware(['permission:get schedule']);

        // Courses
        Route::get('/courses', [AcademicController::class, 'getStudentCourses'])->middleware(['permission:get student courses']);
        Route::get('/courses', [AcademicController::class, 'getInstructorCourses'])->middleware(['permission:get instructor courses']);
        Route::get('/courses/{courseId}/students', [AcademicController::class, 'getCourseStudents'])->middleware(['permission:get course students']);
        Route::get('/courses/{courseId}/materials', [MaterialController::class, 'getMaterials'])->middleware(['permission:get course materials']);
        Route::post('/courses/{courseId}/materials', [MaterialController::class, 'uploadMaterial'])->middleware(['permission:upload course material']);
        Route::get('/materials/{materialId}/download', [MaterialController::class, 'downloadMaterial'])->middleware(['permission:download course material']);
        Route::put('/materials/{materialId}', [MaterialController::class, 'updateMaterial'])->middleware(['permission:update course material']);
        Route::delete('/materials/{materialId}', [MaterialController::class, 'deleteMaterial'])->middleware(['permission:delete course material']);
        Route::get('/current-semester', [StudyPlanController::class, 'getCurrentSemesterCourses'])->middleware(['permission:get current semester courses']);
        Route::get('/current-year', [StudyPlanController::class, 'getCurrentYearCourses'])->middleware(['permission:get current year courses']);
        Route::get('/completed-courses', [StudyPlanController::class, 'getCompletedCourses'])->middleware(['permission:get completed courses']);
        Route::get('/remaining-courses', [StudyPlanController::class, 'getRemainingCourses'])->middleware(['permission:get remaining courses']);
        Route::get('/academic-progress', [StudyPlanController::class, 'getAcademicProgress'])->middleware(['permission:get academic progress']);
        Route::get('/study-plan', [StudyPlanController::class, 'getStudyPlan']); //->middleware(['permission:get academic progress']);

        // Grades
        Route::get('/grades', [AcademicController::class, 'getGrades'])->middleware(['permission:get my grades']);
        Route::get('/allGrades', [GradeController::class, 'getAllGrades'])->middleware(['permission:get all grades']);
        Route::post('/addGrade', [GradeController::class, 'addGrade'])->middleware(['permission:add grade']);
        Route::get('/my-grades/{courseId}', [GradeController::class, 'getgrade'])->middleware(['permission:get my grades']);
        Route::get('/grades/appeals', [GradeController::class, 'getGradeAppeals'])->middleware(['permission:get grade appeals']);
        Route::put('/grades/appeals/{appealId}', [GradeController::class, 'processAppeal'])->middleware(['permission:process grade appeal']);
        Route::get('/my-grades/{courseId}', [GradeController::class, 'getgrade'])->middleware(['permission:get my grades']);
        Route::get('/all-my-grades', [GradeController::class, 'getAllMyGrades'])->middleware(['permission:get all my grades']);
        Route::get('/course-grades/{courseId}/{academicYear}/{semester}', [GradeController::class, 'getCourseGrades'])->middleware(['permission:get course grades']);
        Route::put('/grades/{studentCoursePartId}', [GradeController::class, 'updateGrade'])->middleware(['permission:update grade']);
        Route::post('/grades-for-one-student/{courseId}/{academicYear}/{semester}', [GradeController::class, 'addGradesforonestudent'])->middleware(['permission:add grades for one student']);
        Route::post('add-grade/{courseId}/{academicYear}/{semester}', [GradeController::class, 'addGrade']);
        Route::get('/unpublished-marks/{courseId}', [GradeController::class, 'getUnpublishedMarks']);
        Route::post('/publish-marks/{courseId}', [GradeController::class, 'publishMarks']);

        // Requests
        Route::get('/requestsInStudentCollege', [RequestController::class, 'requestsInStudentCollege'])->middleware(['permission:get requests In student college']);
        Route::get('/requests', [RequestController::class, 'getStudentRequests'])->middleware(['permission:get student requests list']);
        Route::get('/staff_requests', [RequestController::class, 'staffRequests'])->middleware(['permission:get staff requests list']);
        Route::post('/requests', [RequestController::class, 'store'])->middleware(['permission:store request']);
        Route::get('/requests/{requestId}', [RequestController::class, 'getRequestDetails'])->middleware(['permission:get student request details']);
        Route::get('/staff_requests/{requestId}', [RequestController::class, 'getStaffRequestDetails'])->middleware(['permission:get staff request details']);
        Route::post('/requests/{requestId}/assign', [RequestController::class, 'assignRequest'])->middleware(['permission:assign request']);
        Route::post('/requests/{requestId}/cancel', [RequestController::class, 'cancelRequest'])->middleware(['permission:cancel request']);
        Route::get('/allRequests', [RequestController::class, 'getAllRequests'])->middleware(['permission:get all requests']);
        Route::put('/requests/{requestId}/review', [RequestController::class, 'reviewRequest'])->middleware(['permission:review request']);
        Route::get('/request-type-media/{request_type_id}', [RequestController::class, 'getMediaByRequestTypeId'])->middleware(['permission:get media by request type']);
        Route::post('/request-user/{requestUserId}/approve', [RequestController::class, 'approveRequest'])->middleware(['permission:approve request']);
        Route::get('/request-types', [RequestController::class, 'getRequestTypes'])->middleware(['permission:get request types']);
        Route::get('/requests-list', [RequestController::class, 'getRequestsList'])->middleware(['permission:get requests list']);
        Route::get('/request-details/{requestId}', [RequestController::class, 'RequestDetails'])->middleware(['permission:get request details']);


        // View filse
        Route::get('/media/{id}', [\App\Http\Controllers\Api\V1\FileStorageController::class, 'viewMedia'])->middleware(['permission:view media']);
        Route::get('/pdf/{request}', [\App\Http\Controllers\Api\V1\FileStorageController::class, 'viewPdf'])->middleware(['permission:view pdf']);

        // payment and invoices
        Route::get('/invoices', [PaymentController::class, 'getInvoices'])->middleware(['permission:get invoices']);
        Route::post('/invoices/{invoiceId}/pay', [PaymentController::class, 'payInvoice'])->middleware(['permission:pay invoice']);
        Route::post('/payments/callback', [PaymentController::class, 'paymentCallback'])->middleware(['permission:handle payment callback']);
        Route::get('/payments/status/{paymentId}', [PaymentController::class, 'paymentStatus'])->middleware(['permission:get payment status']);

        // dashboard and audit-logs
        Route::get('/dashboard/stats', [AdminController::class, 'dashboardStats'])->middleware(['permission:get dashboard stats']);
        Route::get('/audit-logs', [AdminController::class, 'getAuditLogs'])->middleware(['permission:get audit logs']);
        Route::post('/apply', [AdmissionController::class, 'applyForAdmission'])->middleware(['permission:apply for admission']);
        Route::get('/my-application', [AdmissionController::class, 'getMyApplication'])->middleware(['permission:get my application']);
        Route::get('/applications', [AdmissionController::class, 'getApplications'])->middleware(['permission:get applications']);
        Route::put('/applications/{id}/review', [AdmissionController::class, 'reviewApplication'])->middleware(['permission:review application']);

        // Sanctions
        Route::get('/sanctions', [SanctionController::class, 'getSanctions'])->middleware(['permission:get sanctions']);
        Route::post('/sanction', [SanctionController::class, 'addSanction'])->middleware(['permission:add sanction']);
        Route::put('/sanctions/{sanctionId}', [SanctionController::class, 'updateSanction'])->middleware(['permission:update sanction']);
        Route::delete('/sanctions/{sanctionId}', [SanctionController::class, 'deleteSanction'])->middleware(['permission:delete sanction']);
        Route::get('/sanction-types', [SanctionController::class, 'index'])->middleware(['permission:get sanction types']);
        Route::post('/sanction-type', [SanctionController::class, 'store'])->middleware(['permission:store sanction type']);
        Route::get('/all-my-sanctions', [SanctionController::class, 'getAllSanctions'])->middleware(['permission:get all my sanctions']);
        Route::get('/sanctions-details/{sanctionId}', [SanctionController::class, 'getSanctionDetails'])->middleware(['permission:get sanction details']);
        Route::post('/sanctions-respond/{sanctionId}', [SanctionController::class, 'respondToSanction'])->middleware(['permission:respond to sanction']);

        // Student Documents
        Route::post('/student/{studentId}/documents', [DocumentController::class, 'addDocument'])->middleware(['permission:add document']);
        Route::get('/students/{studentId}/documents', [DocumentController::class, 'getDocuments'])->middleware(['permission:get student documents']);

        // Signatures
        Route::post('/signatures', [UserController::class, 'uploadSignature'])->middleware(['permission:upload signature']);

        // Logo
        Route::post('/colleges/{collegeId}/logo', [CollegeController::class, 'uploadLogo'])->middleware(['permission:upload college logo']);

        // Lectures
        Route::post('/uploadLecture', [DocumentController::class, 'uploadLecture'])->middleware(['permission:upload lecture']);
        Route::get('/lectures/{coursePartsId}/{filename}', [FileStorageController::class, 'showLecture'])->middleware(['permission:get lecture']);
        Route::get('/course-parts/{coursePartsId}/lectures', [DocumentController::class, 'lecturesByCoursePart'])->middleware(['permission:get lectures by course part']);

        // students managment
        Route::get('/student-affairs/students/{personId}', [StudentAttachment::class, 'getStudentAttachments'])->middleware(['permission:get student attachments']);
        Route::post('/student-affairs/{personId}', [StudentAttachment::class, 'reviewStudent'])->middleware(['permission:review student']);
        Route::get('/student-affairs/pending-students', [StudentAttachment::class, 'getPendingStudents'])->middleware(['permission:get pending students']);


        Route::get('/course-details', [CourseController::class, 'getCourseDetails']);
        Route::get('/college-students', [StudentController::class, 'getCollegeStudents']);
        Route::get('/student-sanctions', [SanctionController::class, 'getStudentSanctions']);
        Route::get('/doctor/universities', [DoctorController::class, 'getUniversities']);
        Route::get('/doctor/courses', [DoctorController::class, 'getCourses']);
        Route::get('/universities/{universityId}/logo', [FileStorageController::class, 'showUniversityLogo']);
        Route::get('/teaching-assistant/universities', [TeachingAssistantController::class, 'getUniversities']);
        Route::get('/teaching-assistant/courses', [TeachingAssistantController::class, 'getCourses']);

        // student affairs courses
        Route::get('/student-affairs/courses', [AffairController::class, 'getCollegeCourses'])->middleware(['permission:get college courses']);
    });
});
Route::middleware(['auth:sanctum'])->group(function () {});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->middleware(['permission:logout']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('v1')->middleware('throttle:api')->group(function () {});

Route::prefix('v1')->group(function () {
    Route::middleware('throttle:login')->group(function () {});
});

Route::prefix('v1')->middleware(['auth:sanctum', 'throttle:premium-api'])->group(function () {
    Route::middleware('throttle:heavy')->group(function () {});
});
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});
