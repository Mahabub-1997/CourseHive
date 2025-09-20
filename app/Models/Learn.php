<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Learn extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'user_id',
        'title',
        'description',
    ];

    protected $casts = [
        'description' => 'array', // JSON কে array এ কাস্ট করবে
    ];

    /**
     * এই Learn কোন course এর সাথে যুক্ত
     */
    public function course()
    {
        return $this->belongsTo(OnlineCourse::class, 'course_id');
    }

    /**
     * এই Learn কোন user (creator) দ্বারা তৈরি হয়েছে
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
