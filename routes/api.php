<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\StudentMarksImportController;
use App\Http\Controllers\Api\V1\AcademicController;
use App\Http\Controllers\Api\V1\AdminController;
use App\Http\Controllers\Api\V1\AdmissionController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DocumentController;
use App\Http\Controllers\Api\V1\GradeController;
use App\Http\Controllers\Api\V1\MaterialController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\RequestController;
use App\Http\Controllers\Api\V1\SanctionController;
use App\Http\Controllers\Api\V1\UserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('V1')->group(function () {
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
        Route::get('/courses', [AcademicController::class, 'getStudentCourses']);
        Route::get('/grades', [AcademicController::class, 'getGrades']);
        Route::get('/courses', [AcademicController::class, 'getInstructorCourses']);
        Route::get('/teaching-assistants', [AcademicController::class, 'getTeachingAssistants']);
        Route::get('/courses/{courseId}/students', [AcademicController::class, 'getCourseStudents']);
        Route::get('/courses/{courseId}/materials', [MaterialController::class, 'getMaterials']);
        Route::post('/courses/{courseId}/materials', [MaterialController::class, 'uploadMaterial']);
        Route::put('/materials/{materialId}', [MaterialController::class, 'updateMaterial']);
        Route::delete('/materials/{materialId}', [MaterialController::class, 'deleteMaterial']);
        Route::get('/materials/{materialId}/download', [MaterialController::class, 'downloadMaterial']);
        Route::get('/requestsInStudentCollege', [RequestController::class, 'requestsInStudentCollege']);
        Route::get('/requests', [RequestController::class, 'getStudentRequests']);
        Route::post('/requests', [RequestController::class, 'store']);
        Route::get('/requests/{requestId}', [RequestController::class, 'getRequestDetails']);
        Route::post('/requests/{requestId}/cancel', [RequestController::class, 'cancelRequest']);
        Route::get('/allRequests', [RequestController::class, 'getAllRequests']);
        Route::put('/requests/{requestId}/review', [RequestController::class, 'reviewRequest']);
        Route::get('/request-type-media/{request_type_id}', [RequestController::class, 'getMediaByRequestTypeId']);
        Route::get('/media/{id}', [\App\Http\Controllers\Api\V1\FileStorageController::class, 'viewMedia']);
        Route::get('/allGrades', [GradeController::class, 'getAllGrades']);
        Route::post('/addGrade', [GradeController::class, 'addGrade']);
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
        Route::post('/sanctions', [SanctionController::class, 'addSanction']);
        Route::put('/sanctions/{sanctionId}', [SanctionController::class, 'updateSanction']);
        Route::delete('/sanctions/{sanctionId}', [SanctionController::class, 'deleteSanction']);
        Route::post('/student/{studentId}/documents', [DocumentController::class, 'addDocument']);
        Route::get('/students/{studentId}/documents', [DocumentController::class, 'getDocuments']);
    });
});

Route::post('/addGrade', [GradeController::class, 'addGrade']);

// Route::post('/courses/{course}/marks/import', [StudentMarksImportController::class, 'import']);

Route::post('/login', [AuthController::class, 'login']);



Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/refreshToken', [AuthController::class, 'refreshToken']);
    Route::post('/resend-code', [AuthController::class, 'resendCode']);
    Route::post('/verify-code', [AuthController::class, 'verifyCode']);
    Route::post('/setup-account', [ProfileController::class, 'setupAccount']);
    Route::post('/upload-document', [ProfileController::class, 'uploadDocument']);
    Route::post('/complete-profile', [ProfileController::class, 'completeProfile']);
    Route::post('/submit', [ProfileController::class, 'submit']);
});


Route::middleware(['auth:sanctum', 'CheckStatus'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
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
