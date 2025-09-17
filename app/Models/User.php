<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'otp',
        'otp_created_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
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
     * Get the attributes that should be cast.
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
    // Relationship: User has many Ratings
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
    // Courses created by this user
    public function createdCourses()
    {
        return $this->hasMany(OnlineCourse::class, 'created_by');
    }

    // Courses updated by this user
    public function updatedCourses()
    {
        return $this->hasMany(OnlineCourse::class, 'updated_by');
    }

    // Courses owned by this user
    public function Courses()
    {
        return $this->hasMany(OnlineCourse::class, 'user_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }


    // Courses the user has **enrolled in** (many-to-many)
    public function enrolledCourses()
    {
        return $this->belongsToMany(OnlineCourse::class, 'enrollments')
            ->withPivot('status', 'enrolled_at')
            ->withTimestamps();
    }
    public function shareExperiences()
    {
        return $this->hasMany(ShareExperiance::class, 'user_id');
    }

    // 👇 Add relations
    public function quizResults()
    {
        return $this->hasMany(QuizResult::class);
    }

    // If you want to quickly access all quizzes a user has attempted:
    public function quizzesAttempted()
    {
        return $this->belongsToMany(Quiz::class, 'quiz_results')
            ->withPivot(['score', 'percentage', 'is_passed', 'attempt_number'])
            ->withTimestamps();
    }
}
