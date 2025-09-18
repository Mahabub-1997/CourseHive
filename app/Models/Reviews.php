<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Reviews extends Model
{
    protected $fillable = ['course_id', 'user_id', 'rating_id', 'description'];

    //  Review belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //  Review has one rating
    public function rating()
    {
        return $this->belongsTo(Rating::class);
    }

    //  Review belongs to a course
    public function course()
    {
        return $this->belongsTo(OnlineCourse::class); // Or OnlineCourse
    }
}

