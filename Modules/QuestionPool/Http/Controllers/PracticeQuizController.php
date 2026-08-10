<?php

namespace Modules\QuestionPool\Http\Controllers;

use App\LessonComplete;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\CourseSetting\Entities\Course;
use Modules\CourseSetting\Entities\CourseEnrolled;
use Modules\QuestionPool\Entities\PracticeQuiz;
use Modules\QuestionPool\Entities\PracticeQuizDetail;
use Modules\QuestionPool\Entities\QuestionPoolQuestion;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Support\Facades\DB;

class PracticeQuizController extends Controller
{
    public function setup($courseId)
    {
        try {
            $user = Auth::user();
            
            // Verify enrollment
            $isEnrolled = CourseEnrolled::where('user_id', $user->id)->where('course_id', $courseId)->exists();
            if (!$isEnrolled) {
                Toastr::error('You must be enrolled in this course to take a practice quiz', 'Error');
                return back();
            }

            $course = Course::with('chapters.lessons')->findOrFail($courseId);
            
            return view('questionpool::student.setup', compact('course'));
        } catch (Exception $e) {
            Toastr::error($e->getMessage(), 'Error');
            return back();
        }
    }

    public function availableCount(Request $request)
    {
        try {
            $query = QuestionPoolQuestion::active()->where('course_id', $request->course_id)->where('type', '!=', 'F');
            
            if ($request->scope == 'lesson' && $request->lesson_id) {
                $query->where('lesson_id', $request->lesson_id);
            } elseif ($request->scope == 'chapter' && $request->chapter_id) {
                $query->where('chapter_id', $request->chapter_id);
            }

            $count = $query->count();
            return response()->json([
                'count' => $count,
                'estimated_time' => $count // 1 minute per question
            ]);
        } catch (Exception $e) {
            return response()->json(['count' => 0, 'estimated_time' => 0]);
        }
    }

    public function start(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
            'scope' => 'required',
            'question_count' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            
            $query = QuestionPoolQuestion::active()->where('course_id', $request->course_id)->where('type', '!=', 'F');
            if ($request->scope == 'lesson') {
                $query->where('lesson_id', $request->lesson_id);
            } elseif ($request->scope == 'chapter') {
                $query->where('chapter_id', $request->chapter_id);
            }

            $available_count = $query->count();
            $request_count = min($request->question_count, $available_count);

            if ($request_count < 1) {
                Toastr::error('No questions available for this selection', 'Error');
                return back();
            }

            $questions = $query->inRandomOrder()->limit($request_count)->get();
            $total_marks = $questions->sum('marks');

            $quiz = new PracticeQuiz();
            $quiz->user_id = $user->id;
            $quiz->course_id = $request->course_id;
            $quiz->chapter_id = $request->scope == 'chapter' ? $request->chapter_id : ($request->scope == 'lesson' ? $request->chapter_id : null);
            $quiz->lesson_id = $request->scope == 'lesson' ? $request->lesson_id : null;
            $quiz->scope = $request->scope;
            $quiz->total_questions = $request_count;
            $quiz->total_marks = $total_marks;
            $quiz->estimated_time = $request_count; // 1 min per question
            $quiz->status = 0; // in-progress
            $quiz->started_at = now();
            $quiz->save();

            foreach ($questions as $question) {
                $detail = new PracticeQuizDetail();
                $detail->practice_quiz_id = $quiz->id;
                $detail->question_pool_question_id = $question->id;
                $detail->save();
            }

            DB::commit();
            return redirect()->route('practice-quiz.attempt', $quiz->id);
        } catch (Exception $e) {
            DB::rollBack();
            Toastr::error($e->getMessage(), 'Error');
            return back();
        }
    }

    public function attempt($id)
    {
        try {
            $user = Auth::user();
            $quiz = PracticeQuiz::with('details.question.options')->where('user_id', $user->id)->findOrFail($id);

            if ($quiz->status == 1) {
                return redirect()->route('practice-quiz.result', $quiz->id);
            }

            return view('questionpool::student.attempt', compact('quiz'));
        } catch (Exception $e) {
            Toastr::error($e->getMessage(), 'Error');
            return back();
        }
    }

    public function submit(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $quiz = PracticeQuiz::with('details.question.options')->where('user_id', $user->id)->findOrFail($request->quiz_id);

            if ($quiz->status == 1) {
                return redirect()->route('practice-quiz.result', $quiz->id);
            }

            $correct_answers = 0;
            $wrong_answers = 0;
            $obtained_marks = 0;

            foreach ($quiz->details as $detail) {
                $question = $detail->question;
                $given_answer = $request->input('answer_' . $detail->id);
                $is_correct = 0;
                $marks = 0;

                if ($given_answer !== null && $given_answer !== '') {
                    if ($question->type == 'M') {
                        // $given_answer is option_id
                        $option = $question->options->where('id', $given_answer)->first();
                        if ($option && $option->status == 1) {
                            $is_correct = 1;
                        }
                    } elseif ($question->type == 'T') {
                        if ($given_answer == $question->true_false) {
                            $is_correct = 1;
                        }
                    } elseif ($question->type == 'F') {
                        // Basic match, could be improved with case-insensitive / word matching
                        if (strtolower(trim($given_answer)) == strtolower(trim($question->suitable_words))) {
                            $is_correct = 1;
                        }
                    }

                    if ($is_correct) {
                        $correct_answers++;
                        $marks = $question->marks;
                        $obtained_marks += $marks;
                    } else {
                        $wrong_answers++;
                    }
                }

                $detail->given_answer = $given_answer;
                $detail->is_correct = $is_correct;
                $detail->marks_obtained = $marks;
                $detail->save();
            }

            $quiz->obtained_marks = $obtained_marks;
            $quiz->correct_answers = $correct_answers;
            $quiz->wrong_answers = $wrong_answers;
            $quiz->status = 1; // completed
            $quiz->completed_at = now();
            $quiz->time_taken = $quiz->completed_at->diffInSeconds($quiz->started_at);
            
            // Check pass condition (50%)
            $percentage = ($quiz->total_marks > 0) ? round(($obtained_marks / $quiz->total_marks) * 100, 2) : 0;
            $quiz->pass = $percentage >= 50 ? 1 : 0;
            $quiz->save();

            // Course progress integration if passed and scope is lesson
            if ($quiz->pass == 1 && $quiz->scope == 'lesson' && $quiz->lesson_id) {
                $lessonComplete = LessonComplete::where('user_id', $user->id)
                    ->where('course_id', $quiz->course_id)
                    ->where('lesson_id', $quiz->lesson_id)
                    ->first();
                if (!$lessonComplete) {
                    $lessonComplete = new LessonComplete();
                    $lessonComplete->user_id = $user->id;
                    $lessonComplete->course_id = $quiz->course_id;
                    $lessonComplete->lesson_id = $quiz->lesson_id;
                    $lessonComplete->status = 1;
                    $lessonComplete->save();
                }
            }

            DB::commit();
            return redirect()->route('practice-quiz.result', $quiz->id);
        } catch (Exception $e) {
            DB::rollBack();
            Toastr::error($e->getMessage(), 'Error');
            return back();
        }
    }

    public function result($id)
    {
        try {
            $user = Auth::user();
            $quiz = PracticeQuiz::with('details.question.options', 'course', 'chapter', 'lesson')
                ->where('user_id', $user->id)
                ->findOrFail($id);
            
            // Count attempts
            $query = PracticeQuiz::where('user_id', $user->id)
                                ->where('course_id', $quiz->course_id)
                                ->where('scope', $quiz->scope)
                                ->where('id', '<=', $quiz->id);
            
            if ($quiz->scope == 'lesson') {
                $query->where('lesson_id', $quiz->lesson_id);
            } elseif ($quiz->scope == 'chapter') {
                $query->where('chapter_id', $quiz->chapter_id);
            }
            
            $attempt_number = $query->count();

            return view('questionpool::student.result', compact('quiz', 'attempt_number'));
        } catch (Exception $e) {
            Toastr::error($e->getMessage(), 'Error');
            return back();
        }
    }

    public function history()
    {
        try {
            $user = Auth::user();
            $quizzes = PracticeQuiz::with('course', 'chapter', 'lesson')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('questionpool::student.history', compact('quizzes'));
        } catch (Exception $e) {
            Toastr::error($e->getMessage(), 'Error');
            return back();
        }
    }
}
