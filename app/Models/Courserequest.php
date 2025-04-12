<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseRequest extends Model
{
    protected $fillable = ['user_id',  'course_id', 'status' , 'manager_approved'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function approvals()
    {
        return $this->morphMany(Approval::class, 'approvable');
    }
}
