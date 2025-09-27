<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable (can be updated).
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'profile_image',
        'password',
        'otp',
        'otp_created_at'
    ];

    /**
     * The attributes that should be hidden when serialized (JSON/API).
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp',
        'otp_created_at'
    ];

    /**
     * The attributes that should be type casted.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'otp_created_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    //  User can give multiple ratings
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    //  Courses created by this user
    public function createdCourses()
    {
        return $this->hasMany(OnlineCourse::class, 'created_by');
    }

    //  Courses updated by this user
    public function updatedCourses()
    {
        return $this->hasMany(OnlineCourse::class, 'updated_by');
    }

    //  Courses owned by this user
    public function courses()
    {
        return $this->hasMany(OnlineCourse::class, 'user_id');
    }

    //  Enrollments of this user
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    //  Courses the user has enrolled in (many-to-many)
    public function enrolledCourses()
    {
        return $this->belongsToMany(OnlineCourse::class, 'enrollments')
            ->withPivot('status', 'enrolled_at')
            ->withTimestamps();
    }

    //  User's shared experiences
    public function shareExperiences()
    {
        return $this->hasMany(ShareExperiance::class, 'user_id');
    }

    //  User quiz results
    public function quizResults()
    {
        return $this->hasMany(QuizResult::class);
    }

    //  All quizzes attempted by the user (many-to-many through quiz_results)
    public function quizzesAttempted()
    {
        return $this->belongsToMany(Quiz::class, 'quiz_results')
            ->withPivot(['score', 'percentage', 'is_passed', 'attempt_number'])
            ->withTimestamps();
    }

    //  User reviews
    public function reviews()
    {
        return $this->hasMany(Reviews::class);
    }

    //  Learn records of the user
    public function learns()
    {
        return $this->hasMany(Learn::class, 'user_id');
    }

    //  Instructors linked to this user
    public function instructors()
    {
        return $this->hasMany(Instructor::class);
    }

    //  Payments made by this user
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    //  Promo codes used by this user (via payments)
    public function promoCodesUsed()
    {
        return $this->hasManyThrough(
            PromoCode::class,
            Payment::class,
            'user_id',
            'id',
            'id',
            'promo_code_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    */

    /**
     * Accessor for profile image.
     * Returns full URL for API requests,
     * or relative path for web requests.
     */
    public function getProfileImageAttribute($value): ?string
    {
        if (!$value) {
            return null; // No image uploaded
        }

        // If already a full URL, return as is
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // For API requests, return full URL
        if (request()->is('api/*')) {
            return url('storage/' . $value);
        }

        // For web requests, return relative path
        return 'storage/' . $value;
    }
}
