<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    // تعيين الخصائص المسموح بها للتعديل
    protected $fillable = [
        'name', 'description', 'start_date', 'end_date', 'location', 'instructor', 'available'
    ];
    public function courseRequests()
    {
        return $this->hasMany(CourseRequest::class);
    }
    public function approvals()
    {
        return $this->morphMany(Approval::class, 'approvable');
    }
}
