<?php

namespace Modules\QuestionPool\Entities;

use Illuminate\Database\Eloquent\Model;

class PracticeQuizDetail extends Model
{
    protected $guarded = ['id'];

    public function practiceQuiz()
    {
        return $this->belongsTo(PracticeQuiz::class, 'practice_quiz_id')->withDefault();
    }

    public function question()
    {
        return $this->belongsTo(QuestionPoolQuestion::class, 'question_pool_question_id')->withDefault();
    }
}
