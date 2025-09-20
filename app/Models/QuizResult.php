<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    protected $fillable = [
        'quiz_id',
        'user_id',
        'score',
        'total_questions',
        'percentage',
        'is_passed',
        'answers',
        'attempt_number',
    ];

    protected $casts = [
        'answers' => 'array',
        'percentage' => 'float',
        'is_passed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id'); // usually correct
    }

    public  function course(){
        return $this->belongsTo(OnlineCourse::class, 'course_id');
    }

}
