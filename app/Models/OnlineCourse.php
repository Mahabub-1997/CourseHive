<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineCourse extends Model
{
    use HasFactory;

    // =========================
    // Mass assignable fields
    // =========================
    protected $fillable = [
        'title',
        'description',
        'price',
        'level',
        'duration',
        'language',
        'image',
        'user_id',
        'course_type',
        'rating_id',
        'category_id',
        'created_by',
        'updated_by'
    ];

//    protected $appends = ['image_url'];
    // =========================
    // Relationships
    // =========================

    // The user who owns the course
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Course creator & updater references
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Course category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Average rating
    public function rating()
    {
        return $this->belongsTo(Rating::class, 'rating_id');
    }

    // Lessons under this course
    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'course_id');
    }

    // Quizzes under this course
    public function quiz()
    {
        return $this->hasMany(Quiz::class, 'course_id');
    }

    // Course instructors
    public function instructors()
    {
        return $this->hasOne(Instructor::class, 'course_id');
    }

    // Users enrolled in this course (Many-to-Many via enrollments)
    public function users()
    {
        return $this->belongsToMany(User::class, 'enrollments')
            ->withPivot('status', 'enrolled_at')
            ->withTimestamps();
    }

    // All enrollments for this course
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'course_id');
    }

    // Reviews for this course
    public function reviews()
    {
        return $this->hasMany(Reviews::class, 'course_id');
    }

    // Learn materials associated with course
    public function learns()
    {
        return $this->hasMany(Learn::class, 'course_id');
    }

    // User share experiences for this course
    public function shareExperiances()
    {
        return $this->hasMany(ShareExperiance::class, 'online_course_id');
    }

    // Payments for this course
    public function payments()
    {
        return $this->hasMany(Payment::class, 'course_id');
    }

    // Promo codes applied via payments
    public function promoCodesApplied()
    {
        return $this->hasManyThrough(
            PromoCode::class,
            Payment::class,
            'course_id',
            'id',
            'id',
            'promo_code_id'
        );
    }
    public function ratings()
    {
        return $this->hasOne(Rating::class, 'course_id');
    }
    // Accessor to get full image URL
//    public function getImageUrlAttribute()
//    {
//        if ($this->image && !str_starts_with($this->image, 'http')) {
//            return url('storage/online_course/' . $this->image);
//        }
//        return $this->image;
//    }
}
