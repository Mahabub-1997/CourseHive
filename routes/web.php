<?php

use App\Http\Controllers\Web\Backend\Certificate\CertificateController;
use App\Http\Controllers\Web\Backend\CMS\CourseOverview\OverviewController;
use App\Http\Controllers\Web\Backend\CMS\Instructor\InstructorController;
use App\Http\Controllers\Web\Backend\Quiz\Quiz\QuizController;
use App\Http\Controllers\Web\Backend\QuizResult\QuizResultController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Backend\CMS\AboutUs\AboutUsController;
use App\Http\Controllers\Web\Backend\CMS\Category\CategoryController;
use App\Http\Controllers\Web\Backend\CMS\ContactUs\ContactUsController;
use App\Http\Controllers\Web\Backend\CMS\HeroImage\HeroImageController;
use App\Http\Controllers\Web\Backend\CMS\HeroSection\HeroSectionController;
use App\Http\Controllers\Web\Backend\CMS\OnlineCourses\OnlineCoursesController;
use App\Http\Controllers\Web\Backend\CMS\Subscription\SubscriptionController;
use App\Http\Controllers\Web\Backend\Dashboard\ProfileController;
use App\Http\Controllers\Web\Backend\Enrollment\EnrollmentController;
use App\Http\Controllers\Web\Backend\Mycourse\CourseController;
use App\Http\Controllers\Web\Backend\Quiz\Lesson\LessonController;
use App\Http\Controllers\Web\Backend\Quiz\Option\OptionController;
use App\Http\Controllers\Web\Backend\Quiz\Part\PartController;
use App\Http\Controllers\Web\Backend\Quiz\Question\QuestionController;
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

    /** -------- Enrollment & Course Enrollment -------- */
    Route::post('courses/{id}/enroll', [EnrollmentController::class, 'enroll'])->name('courses.enroll');

    Route::get('courses/{id}/pay', [EnrollmentController::class, 'pay'])->name('courses.pay');
    Route::get('courses/{id}/payment-success', [EnrollmentController::class, 'paymentSuccess'])->name('courses.payment.success');

    Route::get('my-courses', [EnrollmentController::class, 'myCourses'])->name('courses.my');

    /** -------- Enrolled Users Management -------- */
    Route::get('courses/{id}/enrolled-users', [EnrollmentController::class, 'courseEnrolledUsers'])->name('courses.enrolled-users');
    Route::get('admin/enrollments', [EnrollmentController::class, 'indexEnrollments'])->name('enrollments.index');
    Route::patch('enrollments/{id}/update-status', [EnrollmentController::class, 'updateStatus'])->name('enrollments.update-status');
    Route::get('/certificate/{enrollment}/download', [CertificateController::class, 'download'])->name('certificate.download');
    Route::get('/certificates', [CertificateController::class, 'index'])->name('certificate.index');
});

/* ==============================
   CMS Management Routes
   ============================== */
Route::resource('about-us', AboutUsController::class);
Route::resource('categories', CategoryController::class);
Route::resource('online-courses', OnlineCoursesController::class);
Route::resource('subscriptions', SubscriptionController::class);
Route::resource('contactus', ContactUsController::class);
Route::resource('hero-images', HeroImageController::class);
Route::resource('hero-sections', HeroSectionController::class);

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
Route::resource('lessons', LessonController::class);
Route::resource('parts', PartController::class);
Route::resource('quizzes', QuizController::class);
Route::resource('questions', QuestionController::class);
Route::resource('options', OptionController::class);
Route::resource('overview', OverviewController::class);
Route::resource('instructors', InstructorController::class);


/* ==============================
   My Courses
   ============================== */
Route::get('/my-courses/in-progress', [CourseController::class, 'inProgress'])->name('courses.in-progress');
Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/course/{id}/content', [CourseController::class, 'content'])->name('course.content');
Route::get('/course/{id}/content/{partId?}', [CourseController::class, 'content'])->name('course.content');
Route::get('/course/{id}/quiz', [CourseController::class, 'quiz'])->name('course.quiz');



Route::post('/quiz/{quiz}/submit', [QuizResultController::class, 'submit'])
    ->name('quiz.submit');

//Route::get('/quiz/{course}/start', [QuizResultController::class, 'start'])->name('quiz.start');
//Route::post('/quiz/{courseId}/start', [QuizResultController::class, 'start'])->name('quiz.start');

// Show the quiz start page (GET)
Route::post('/quiz/{course}/start', [QuizResultController::class, 'start'])->name('quiz.start');

// Submit quiz answers (POST)
Route::post('/quiz/{quiz}/submit', [QuizResultController::class, 'quizsubmit'])->name('quiz.submit');



/* ==============================
   Quiz Results
   ============================== */

// Show quiz (GET)
//Route::get('/quiz/{quiz}/start', [QuizResultController::class, 'start'])->name('quiz.start');

// Review answers before final submit (POST)
Route::post('/quiz/{quiz}/review', [QuizResultController::class, 'review'])->name('quiz.review');

// Submit final answers (POST)
Route::post('/quiz/{courseId}/submit', [QuizResultController::class, 'submit'])->name('quiz.submit');

Route::post('/quiz/{quiz}/result', [QuizResultController::class, 'result'])->name('quiz.result');



/* ==============================
   Course Reviews (Requires Auth)
   ============================== */
Route::middleware('auth')->post('/courses/{id}/reviews', [CourseController::class, 'store'])
    ->name('courses.reviews.store');

/* ==============================
   Auth Routes
   ============================== */
require __DIR__.'/auth.php';
