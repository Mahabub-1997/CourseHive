<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = ['part_id', 'title'];

    // Relationships
    public function part()
    {
        return $this->belongsTo(Part::class, 'part_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'quiz_id');
    }
    public function results()
    {
        return $this->hasMany(QuizResult::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'quiz_results')
            ->withPivot(['score', 'percentage', 'is_passed', 'attempt_number'])
            ->withTimestamps();
    }
    public function course()
    {
        return $this->belongsTo(OnlineCourse::class, 'course_id');
    }

    public function getCourseTitleAttribute()
    {
        return $this->part
        && $this->part->lesson
        && $this->part->lesson->course
            ? $this->part->lesson->course->title
            : 'N/A';
    }
}
