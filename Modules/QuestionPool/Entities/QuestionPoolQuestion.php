<?php

namespace Modules\QuestionPool\Entities;

use App\Traits\Tenantable;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\CourseSetting\Entities\Chapter;
use Modules\CourseSetting\Entities\Course;
use Modules\CourseSetting\Entities\Lesson;

class QuestionPoolQuestion extends Model
{
    use Tenantable;

    protected $guarded = ['id'];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id')->withDefault();
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class, 'chapter_id')->withDefault();
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id')->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function options()
    {
        return $this->hasMany(QuestionPoolOption::class, 'question_pool_question_id');
    }

    public function questionOptionsInRandom()
    {
        return $this->hasMany(QuestionPoolOption::class, 'question_pool_question_id')->inRandomOrder();
    }

    public function scopeActive($query)
    {
        return $query->where('active_status', 1);
    }
}
