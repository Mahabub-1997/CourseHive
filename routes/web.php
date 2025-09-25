<?php

use App\Http\Controllers\Web\Backend\Earning\EarningController;
use Illuminate\Support\Facades\Route;

// ==============================
// Auth Controllers
// ==============================
use App\Http\Controllers\Web\Backend\Dashboard\ProfileController;

// Enrollment / Payment
use App\Http\Controllers\Web\Backend\Enrollment\EnrollmentController;
use App\Http\Controllers\Web\Backend\Payment\PaymentController;

// CMS
use App\Http\Controllers\Web\Backend\CMS\AboutUs\AboutUsController;
use App\Http\Controllers\Web\Backend\CMS\Category\CategoryController;
use App\Http\Controllers\Web\Backend\CMS\ContactUs\ContactUsController;
use App\Http\Controllers\Web\Backend\CMS\HeroImage\HeroImageController;
use App\Http\Controllers\Web\Backend\CMS\HeroSection\HeroSectionController;
use App\Http\Controllers\Web\Backend\CMS\OnlineCourses\OnlineCoursesController;
use App\Http\Controllers\Web\Backend\CMS\Subscription\SubscriptionController;

// Certificate
use App\Http\Controllers\Web\Backend\Certificate\CertificateController;

// Quiz Module
use App\Http\Controllers\Web\Backend\Quiz\Quiz\QuizController;
use App\Http\Controllers\Web\Backend\QuizResult\QuizResultController;
use App\Http\Controllers\Web\Backend\Quiz\Lesson\LessonController;
use App\Http\Controllers\Web\Backend\Quiz\Part\PartController;
use App\Http\Controllers\Web\Backend\Quiz\Question\QuestionController;
use App\Http\Controllers\Web\Backend\Quiz\Option\OptionController;
use App\Http\Controllers\Web\Backend\CMS\CourseOverview\OverviewController;
use App\Http\Controllers\Web\Backend\CMS\Instructor\InstructorController;

// My Courses
use App\Http\Controllers\Web\Backend\MyCourse\CourseController;

// Student Reviews
use App\Http\Controllers\Web\Backend\StudentReview\ApiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you register web routes for your application.
| These routes are loaded by the RouteServiceProvider within a group
| which contains the "web" middleware group.
|
*/

/* ==============================
   Public Routes
   ============================== */
Route::get('/', function () {
    return view('welcome');
});

/* ==============================
   Dashboard (Requires Auth & Verification)
   ============================== */
Route::get('/dashboard', function () {
    return view('backend.layouts.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/* ==============================
   Protected Routes (Auth Required)
   ============================== */
Route::middleware('auth')->group(function () {

    /** -------- Profile -------- */
    Route::get('/dashboard', [ProfileController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /** -------- Enrollment & Payments -------- */
    Route::post('courses/{id}/enroll', [EnrollmentController::class, 'enroll'])->name('courses.enroll');
    Route::post('payment/{course}/process', [EnrollmentController::class, 'processPayment'])->name('payment.process');
    Route::get('courses/{id}/checkout', [EnrollmentController::class, 'checkout'])->name('payment.checkout');
    Route::get('courses/{id}/success', [EnrollmentController::class, 'success'])->name('payment.success');
    Route::get('courses/{id}/cancel', [EnrollmentController::class, 'cancel'])->name('payment.cancel');
    Route::get('courses/{id}/pay', [PaymentController::class, 'pay'])->name('courses.pay');

    /** -------- My Courses -------- */
    Route::get('my-courses', [EnrollmentController::class, 'myCourses'])->name('courses.my');

    /** -------- Enrolled Users -------- */
    Route::get('courses/{id}/enrolled-users', [EnrollmentController::class, 'courseEnrolledUsers'])->name('courses.enrolled-users');
    Route::get('admin/enrollments', [EnrollmentController::class, 'indexEnrollments'])->name('enrollments.index');
    Route::patch('enrollments/{id}/update-status', [EnrollmentController::class, 'updateStatus'])->name('enrollments.update-status');

    /** -------- Certificates -------- */
    Route::get('/certificate/{enrollment}/download', [CertificateController::class, 'download'])->name('certificate.download');
    Route::get('/certificates', [CertificateController::class, 'index'])->name('certificate.index');
});

/* ==============================
   CMS Management
   ============================== */
Route::resource('web-about-us', AboutUsController::class);
Route::resource('web-categories', CategoryController::class);
Route::resource('web-online-courses', OnlineCoursesController::class);
Route::resource('web-subscriptions', SubscriptionController::class);
Route::resource('web-contactus', ContactUsController::class);
Route::resource('web-hero-images', HeroImageController::class);
Route::resource('web-hero-sections', HeroSectionController::class);

/* ==============================
   Student Reviews
   ============================== */
Route::get('/share-experiance', [ApiController::class, 'index'])->name('share.experiance.index');

/* ==============================
   Top Courses
   ============================== */
Route::get('/top-courses', [EnrollmentController::class, 'topCourses'])->name('top.courses');

/* ==============================
   Quiz Module
   ============================== */
Route::resource('web-lessons', LessonController::class);
Route::resource('web-parts', PartController::class);
Route::resource('web-quizzes', QuizController::class);
Route::resource('web-questions', QuestionController::class);
Route::resource('web-options', OptionController::class);
Route::resource('web-overview', OverviewController::class);
Route::resource('web-instructors', InstructorController::class);

/* ==============================
   My Courses
   ============================== */
Route::get('/my-courses/in-progress', [CourseController::class, 'inProgress'])->name('courses.in-progress');
Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/course/{id}/content/{partId?}', [CourseController::class, 'content'])->name('course.content');
Route::get('/course/{id}/quiz', [CourseController::class, 'quiz'])->name('course.quiz');

/* ==============================
   Quiz Flow
   ============================== */
// Start Quiz
Route::match(['get', 'post'], '/quiz/{course}/start', [QuizResultController::class, 'start'])->name('quiz.start');

// Submit Quiz
Route::post('/quiz/{quiz}/submit', [QuizResultController::class, 'quizsubmit'])->name('quiz.submit');

// Review Answers
Route::post('/quiz/{quiz}/review', [QuizResultController::class, 'review'])->name('quiz.review');

// Final Submit
Route::post('/quiz/{courseId}/submit', [QuizResultController::class, 'submit'])->name('quiz.submit');

// Quiz Result
Route::post('/quiz/{quiz}/result', [QuizResultController::class, 'result'])->name('quiz.result');

// My Quiz
Route::get('courses/{course}/my-quiz', [QuizResultController::class, 'showCourseQuiz'])->name('course.my-quiz');


//Earning

Route::get('/earning', [EarningController::class, 'index'])->name('earning.index'); //added

/* ==============================
   Course Reviews
   ============================== */
Route::middleware('auth')->post('/courses/{id}/reviews', [CourseController::class, 'store'])->name('courses.reviews.store');

/* ==============================
   Auth Routes
   ============================== */
require __DIR__ . '/auth.php';
