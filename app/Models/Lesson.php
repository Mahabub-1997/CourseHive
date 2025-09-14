<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = ['course_id', 'title', 'description'];

    // Relationships
    public function course()
    {
        return $this->belongsTo(OnlineCourse::class, 'course_id');
    }

    public function parts()
    {
        return $this->hasMany(Part::class, 'lesson_id');
    }
}
