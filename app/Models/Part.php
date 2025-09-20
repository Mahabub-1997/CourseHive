<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $fillable = ['lesson_id', 'title', 'content','video'];

    public function lesson() {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    public function quiz() {
        return $this->hasOne(Quiz::class, 'part_id');
    }
}
