<?php

use App\Http\Controllers\Web\Backend\CMS\AboutUs\AboutUsController;
use App\Http\Controllers\Web\Backend\CMS\Category\CategoryController;
use App\Http\Controllers\Web\Backend\CMS\ContactUs\ContactUsController;
use App\Http\Controllers\Web\Backend\CMS\HeroImage\HeroImageController;
use App\Http\Controllers\Web\Backend\CMS\HeroSection\HeroSectionController;
use App\Http\Controllers\Web\Backend\CMS\OnlineCourses\OnlineCoursesController;
use App\Http\Controllers\Web\Backend\CMS\Subscription\SubscriptionController;
use App\Http\Controllers\Web\Backend\Dashboard\ProfileController;
use App\Http\Controllers\Web\Backend\Enrollment\EnrollmentController;
use App\Http\Controllers\Web\Backend\StudentReview\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('backend.layouts.dashboard');

})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profile & Dashboard routes
    Route::get('/dashboard', [ProfileController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Enrollment & Course routes
    Route::post('courses/{id}/enroll', [EnrollmentController::class, 'enroll'])->name('courses.enroll');
    Route::get('courses/{id}/pay', [EnrollmentController::class, 'pay'])->name('courses.pay');
    Route::get('courses/{id}/payment-success', [EnrollmentController::class, 'paymentSuccess'])->name('courses.payment.success');
    Route::get('my-courses', [EnrollmentController::class, 'myCourses'])->name('courses.my');

    // Enrolled users route
    Route::get('courses/{id}/enrolled-users', [EnrollmentController::class, 'courseEnrolledUsers'])->name('courses.enrolled-users');
    Route::get('admin/enrollments', [EnrollmentController::class, 'indexEnrollments'])->name('enrollments.index');
    Route::patch('enrollments/{id}/update-status', [EnrollmentController::class, 'updateStatus'])->name('enrollments.update-status');
});

Route::resource('about-us', AboutUsController::class);
Route::resource('categories', CategoryController::class);
Route::resource('online-courses', OnlineCoursesController::class);
Route::resource('subscriptions', SubscriptionController::class);
Route::resource('contactus', ContactUsController::class);
Route::resource('hero-images', HeroImageController::class);
Route::resource('hero-sections', HeroSectionController::class);

Route::get('/share-experiance', [ApiController::class, 'index'])->name('share.experiance.index');

Route::get('/top-courses', [EnrollmentController::class, 'topCourses'])->name('top.courses');




require __DIR__.'/auth.php';
