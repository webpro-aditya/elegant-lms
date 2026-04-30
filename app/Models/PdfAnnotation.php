<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdfAnnotation extends Model
{
    protected $table = 'pdf_annotations';

    protected $fillable = [
        'user_id',
        'lesson_id',
        'course_id',
        'type',
        'annot_id',
        'page_num',
        'rects',
        'text',
        'color',
        'pos_x',
        'pos_y',
    ];
}
