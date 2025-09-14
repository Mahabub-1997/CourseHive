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
}
