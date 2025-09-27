<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'rating_point'
    ];

    /* =====================
     *   RELATIONSHIPS
     * ===================== */

    /**
     * Rating belongs to a user (who gave the rating).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A rating is associated with one course.
     */
    public function course()
    {
        return $this->hasOne(OnlineCourse::class, 'rating_id');
    }

    /**
     * A rating can belong to many share experiences (reviews/feedback).
     */
    public function shareExperiences()
    {
        return $this->hasMany(ShareExperiance::class, 'rating_id');
    }

    /*
    // Alternative relation (if you store course_id in ratings table)
    public function courses()
    {
        return $this->belongsTo(OnlineCourse::class, 'online_course_id');
        // Replace 'online_course_id' with the actual column name in your ratings table
    }
    */


    // Rating may be linked to a review
    public function review()
    {
        return $this->hasOne(Reviews::class);
    }
    public function instructors()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }

}
