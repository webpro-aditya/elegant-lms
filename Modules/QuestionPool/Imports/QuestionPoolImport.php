<?php

namespace Modules\QuestionPool\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\CourseSetting\Entities\Chapter;
use Modules\CourseSetting\Entities\Course;
use Modules\CourseSetting\Entities\Lesson;
use Modules\QuestionPool\Entities\QuestionPoolQuestion;
use Modules\QuestionPool\Entities\QuestionPoolOption;
use Illuminate\Support\Facades\Auth;

class QuestionPoolImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $user_id = Auth::id();

        foreach ($rows as $row) {
            // Validate required fields
            if (!isset($row['course_title']) || !isset($row['question']) || !isset($row['type'])) {
                continue;
            }

            // Find course
            $course = Course::where('title', 'like', '%' . $row['course_title'] . '%')->first();
            if (!$course) continue;

            // Find chapter if provided
            $chapter_id = null;
            if (!empty($row['chapter_name'])) {
                $chapter = Chapter::where('name', 'like', '%' . $row['chapter_name'] . '%')
                                  ->where('course_id', $course->id)
                                  ->first();
                if ($chapter) $chapter_id = $chapter->id;
            }

            // Find lesson if provided
            $lesson_id = null;
            if (!empty($row['lesson_name'])) {
                $lesson_query = Lesson::where('name', 'like', '%' . $row['lesson_name'] . '%')
                                      ->where('course_id', $course->id);
                if ($chapter_id) {
                    $lesson_query->where('chapter_id', $chapter_id);
                }
                $lesson = $lesson_query->first();
                if ($lesson) $lesson_id = $lesson->id;
            }

            $type = strtoupper($row['type']);
            if (!in_array($type, ['M', 'T', 'F'])) {
                $type = 'M'; // default to MCQ
            }

            $question = new QuestionPoolQuestion();
            $question->course_id = $course->id;
            $question->chapter_id = $chapter_id;
            $question->lesson_id = $lesson_id;
            $question->type = $type;
            $question->question = $row['question'];
            $question->marks = isset($row['marks']) ? (int)$row['marks'] : 1;
            $question->explanation = $row['explanation'] ?? null;
            $question->user_id = $user_id;
            $question->active_status = 1;

            if ($type == 'M') {
                $question->save();
                
                // Add options
                $options = [
                    $row['option_a'] ?? null,
                    $row['option_b'] ?? null,
                    $row['option_c'] ?? null,
                    $row['option_d'] ?? null,
                ];
                
                $correct_option = strtolower(trim($row['correct_option'] ?? 'a'));
                $correct_index = 0;
                if ($correct_option == 'b') $correct_index = 1;
                elseif ($correct_option == 'c') $correct_index = 2;
                elseif ($correct_option == 'd') $correct_index = 3;

                foreach ($options as $index => $optText) {
                    if (!empty($optText)) {
                        $option = new QuestionPoolOption();
                        $option->question_pool_question_id = $question->id;
                        $option->title = $optText;
                        $option->status = ($index == $correct_index) ? 1 : 0;
                        $option->save();
                    }
                }
            } elseif ($type == 'T') {
                $question->true_false = strtoupper(trim($row['correct_option'] ?? 'T')) == 'F' ? 'F' : 'T';
                $question->save();
            } elseif ($type == 'F') {
                $question->suitable_words = $row['correct_option'] ?? '';
                $question->save();
            }
        }
    }
}
