<?php
namespace App\Models;
use App\Models\Attachment;

use Illuminate\Database\Eloquent\Model;

class CourseRequest extends Model
{

    protected $fillable = [
        'user_id',
        'course_id',
        'custom_course_title',
        'custom_course_provider',
        'reason',
        'status',
        'comment',
        'link',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function attachments()
{
    return $this->morphMany(\App\Models\Attachment::class, 'attachable');
}


  //  public function approvals()
   // {
  //      return $this->morphMany(Approval::class, 'approvable');
  //  }
}
