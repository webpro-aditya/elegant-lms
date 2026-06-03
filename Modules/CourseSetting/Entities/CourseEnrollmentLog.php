<?php

namespace Modules\CourseSetting\Entities;

use App\User;
use App\Traits\Tenantable;
use Illuminate\Database\Eloquent\Model;

class CourseEnrollmentLog extends Model
{
    use Tenantable;

    protected $guarded = [];

    protected $table = 'course_enrollment_logs';

    /**
     * The course this log entry belongs to.
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id')->withDefault();
    }

    /**
     * The student being enrolled/updated/removed.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault();
    }

    /**
     * The admin/user who performed the action.
     */
    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by', 'id')->withDefault();
    }

    /**
     * Decode the JSON details column.
     */
    public function getDecodedDetailsAttribute()
    {
        return $this->details ? json_decode($this->details, true) : [];
    }
}
