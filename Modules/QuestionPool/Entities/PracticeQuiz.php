<?php

namespace Modules\QuestionPool\Entities;

use App\Traits\Tenantable;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\CourseSetting\Entities\Chapter;
use Modules\CourseSetting\Entities\Course;
use Modules\CourseSetting\Entities\Lesson;

class PracticeQuiz extends Model
{
    use Tenantable;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

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

    public function details()
    {
        return $this->hasMany(PracticeQuizDetail::class, 'practice_quiz_id');
    }

    public function getPercentageAttribute()
    {
        if ($this->total_marks > 0) {
            return round(($this->obtained_marks / $this->total_marks) * 100, 2);
        }
        return 0;
    }
}
