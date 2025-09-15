<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineCourse extends Model
{
    use HasFactory;

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

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    // Relationships
    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'course_id');
    }


    public function rating()
    {
        return $this->belongsTo(Rating::class, 'rating_id');
    }
    // One course can have many shared experiences
    public function shareExperiences()
    {
        return $this->hasMany(ShareExperiance::class, 'online_course_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    // One course has many enrollments
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'course_id');
    }

    // Users enrolled in this course
    public function users()
    {
        return $this->belongsToMany(User::class, 'enrollments')
            ->withPivot('status', 'enrolled_at')
            ->withTimestamps();
    }
}
