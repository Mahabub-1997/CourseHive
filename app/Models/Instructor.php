<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'rating_id',
        'name',
        'image',
        'rating',
        'description',
        'total_lesson',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(OnlineCourse::class, 'course_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'instructor_id');
    }
//    public function ratings()
//    {
//        return $this->hasManyThrough(
//            Rating::class,        // Final model
//            OnlineCourse::class,  // Intermediate model
//            'id',                 // Local key on OnlineCourse
//            'course_id',          // Foreign key on Rating
//            'course_id',          // Local key on Instructor
//            'id'                  // Local key on OnlineCourse
//        );
//    }

    // Dynamic lesson count
    public function getTotalLessonAttribute($value)
    {
        if ($value) {
            return $value;
        }
        return $this->course ? $this->course->lessons()->count() : 0;
    }
}
