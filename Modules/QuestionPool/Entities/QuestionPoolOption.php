<?php

namespace Modules\QuestionPool\Entities;

use Illuminate\Database\Eloquent\Model;

class QuestionPoolOption extends Model
{
    protected $guarded = ['id'];

    public function question()
    {
        return $this->belongsTo(QuestionPoolQuestion::class, 'question_pool_question_id')->withDefault();
    }
}
