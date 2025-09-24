<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ==========================
// Auth Controllers
// ==========================
use App\Http\Controllers\API\Auth\AuthenticatedSessionController;
use App\Http\Controllers\API\Auth\ForgotPasswordController;
use App\Http\Controllers\API\Auth\OtpVerificationController;
use App\Http\Controllers\API\Auth\RegisteredUserController;
use App\Http\Controllers\API\Auth\ResetPasswordController;

// ==========================
// CMS Controllers
// ==========================
use App\Http\Controllers\API\CMS\AboutUsController;
use App\Http\Controllers\API\CMS\CategoryController;
use App\Http\Controllers\API\CMS\ContactController;
use App\Http\Controllers\API\CMS\ContactUsController;
use App\Http\Controllers\API\CMS\HeroImageController;
use App\Http\Controllers\API\CMS\HeroSectionController;
use App\Http\Controllers\API\CMS\OnlineCourseController;
use App\Http\Controllers\API\CMS\RatingController;
use App\Http\Controllers\API\CMS\ShareExperianceController;
use App\Http\Controllers\API\CMS\SubscriptionController;
use App\Http\Controllers\API\CMS\TopCourseController;

// ==========================
// Other Feature Controllers
// ==========================
use App\Http\Controllers\API\Certificate\CertificateController;
use App\Http\Controllers\API\Dashboard\DashboardController;
use App\Http\Controllers\API\Enroll\EnrollmentController;
use App\Http\Controllers\API\MyCourse\CourseController;
use App\Http\Controllers\API\QuizOverview\QuizController;


// ==========================
// Public Authentication Routes
// ==========================
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'login']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp']);
Route::post('/reset-password', [ResetPasswordController::class, 'verifyOtp']);
Route::post('/verify-otp', [OtpVerificationController::class, 'verify']);
Route::post('/reset-verify-otp', [ForgotPasswordController::class, 'verifyOtpRegister']);


// ==========================
// Protected Routes (Require Sanctum Auth)
// ==========================
Route::middleware('auth:sanctum')->group(function () {
    // Example Dashboard
    Route::get('/dashboard', function (Request $request) {
        return response()->json([
            'message' => 'Welcome to dashboard',
            'user'    => $request->user()
        ]);
    });

    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);

    // My Courses
    Route::get('/my-courses', [CourseController::class, 'index']);

    // Enrollment
    Route::post('courses/{id}/enroll', [EnrollmentController::class, 'enroll']);
    Route::post('createPayment/{id}', [EnrollmentController::class, 'createPayment']);
    Route::post('/enroll/payment-success', [EnrollmentController::class, 'handlePaymentSuccess']);




    // Courses
    Route::get('courses', [CourseController::class, 'courseindex']);
    Route::get('/courses/{id}', [CourseController::class, 'courseShow']);
    Route::get('/courses/{id}', [CourseController::class, 'Contentshow']); // ⚠️ DUPLICATE — may need fixing

    // Quiz
    Route::get('courses/{course}/quiz', [CourseController::class, 'getCourseQuiz']);
    Route::get('quiz/{quizId}/result', [CourseController::class, 'getResult']);
    Route::get('/quiz-performance', [QuizController::class, 'performance']);
    Route::middleware('auth:sanctum')->post('/quizzes/{quizId}/submit', [QuizController::class, 'submit']);

    Route::middleware('auth:sanctum')->post('/quizzes/{quiz}/review', [QuizController::class, 'review']);  //addd
//    Route::middleware('auth:sanctum')->get('/quizzes/{quiz}/result', [QuizController::class, 'resultApi']);

    // Certificates
    Route::get('/certificates', [CertificateController::class, 'index']);
    Route::get('/certificates/{id}', [CertificateController::class, 'show']);

    // Dashboard (custom endpoints)
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/get-profile-info', [DashboardController::class, 'userprofileinfo']);
    Route::post('/profile-update', [DashboardController::class, 'userprofileupdate']);
//    new-----
    Route::middleware('auth:sanctum')->post('/user/password/update', [DashboardController::class, 'updatePassword']);
});


// ==========================
// User Info (Sanctum Protected)
// ==========================
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// ==========================
// CMS Resources
// ==========================
Route::apiResource('top-courses', TopCourseController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('ratings', RatingController::class);
Route::apiResource('online-courses', OnlineCourseController::class);
Route::apiResource('about-us', AboutUsController::class);
Route::apiResource('subscriptions', SubscriptionController::class);
Route::apiResource('contact-us', ContactUsController::class);
Route::apiResource('hero-images', HeroImageController::class);
Route::apiResource('hero-sections', HeroSectionController::class);
Route::apiResource('share-experiance', ShareExperianceController::class);

// Special Hero Section Route
Route::patch('update-hero-section/{id}', [HeroSectionController::class, 'updateHeroSection']);
Route::get('hero-sections/search/courses', [HeroSectionController::class, 'searchCourses']);


// ==========================
// Contact (Custom)
// ==========================
Route::get('contacts', [ContactController::class, 'index']);
Route::post('contacts', [ContactController::class, 'store']);
Route::delete('contacts/{id}', [ContactController::class, 'destroy']);


// ==========================
// Extra Public Routes
// ==========================
Route::get('/top-courses', [TopCourseController::class, 'index']); // duplicate with apiResource
Route::get('/about-us', [AboutUsController::class, 'show']); // duplicate with apiResource
Route::get('/online-courses/{id}', [CourseController::class, 'show']); // note: might conflict with OnlineCourseController




// Free + Paid course enroll API
Route::middleware('auth:sanctum')->post('/enroll/{course_id}', [EnrollmentController::class, 'enroll']);


