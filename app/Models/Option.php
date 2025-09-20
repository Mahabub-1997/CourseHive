<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = ['question_id', 'option_text', 'is_correct'];

    // Automatically cast JSON and boolean fields
    protected $casts = [
        'option_text' => 'array',  // stored as JSON, retrieved as array
        'is_correct'  => 'boolean',
    ];

    // Relationships
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
