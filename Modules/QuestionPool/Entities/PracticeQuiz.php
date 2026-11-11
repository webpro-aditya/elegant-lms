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

    public function getScopeLabelAttribute()
    {
        if ($this->scope == 'lesson') {
            $ids = $this->lesson_ids ? array_filter(explode(',', $this->lesson_ids)) : [];
            if (count($ids) > 1) {
                $names = Lesson::whereIn('id', $ids)->pluck('name')->implode(', ');
                return 'Lessons: ' . ($names ?: '-');
            }
            return 'Lesson: ' . ($this->lesson->name ?? '-');
        }
        if ($this->scope == 'chapter') {
            $ids = $this->chapter_ids ? array_filter(explode(',', $this->chapter_ids)) : [];
            if (count($ids) > 1) {
                $names = Chapter::whereIn('id', $ids)->pluck('name')->implode(', ');
                return 'Chapters: ' . ($names ?: '-');
            }
            return 'Chapter: ' . ($this->chapter->name ?? '-');
        }
        return 'Entire Course';
    }

    public function getUnattemptedCountAttribute()
    {
        $attempted = (int)$this->correct_answers + (int)$this->wrong_answers;
        return max(0, (int)$this->total_questions - $attempted);
    }
}
