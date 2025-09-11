<?php

use App\Http\Controllers\API\Auth\AuthenticatedSessionController;
use App\Http\Controllers\API\Auth\ForgotPasswordController;
use App\Http\Controllers\API\Auth\OtpVerificationController;
use App\Http\Controllers\API\Auth\RegisteredUserController;
use App\Http\Controllers\API\Auth\ResetPasswordController;

use App\Http\Controllers\API\CMS\AboutUsController;
use App\Http\Controllers\API\CMS\CategoryController;
use App\Http\Controllers\API\CMS\ContactUsController;
use App\Http\Controllers\API\CMS\HeroImageController;
use App\Http\Controllers\API\CMS\HeroSectionController;
use App\Http\Controllers\API\CMS\OnlineCourseController;
use App\Http\Controllers\API\CMS\RatingController;
use App\Http\Controllers\API\CMS\ShareExperianceController;
use App\Http\Controllers\API\CMS\SubscriptionController;
use App\Http\Controllers\API\CMS\TopCourseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// Public routes
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'login']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp']);
Route::post('/reset-password', [ResetPasswordController::class, 'verifyOtp']);
Route::post('/verify-otp', [OtpVerificationController::class, 'verify']);
Route::post('/reset-verify-otp', [ForgotPasswordController::class, 'verifyOtpRegister']);



// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Dashboard example
    Route::get('/dashboard', function (Request $request) {
        return response()->json([
            'message' => 'Welcome to dashboard',
            'user' => $request->user()
        ]);
    });

    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
});



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



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
Route::patch('update-hero-section/{id}', [HeroSectionController::class,'updateHeroSection']);

Route::get('hero-sections/search/courses', [HeroSectionController::class, 'searchCourses']);
