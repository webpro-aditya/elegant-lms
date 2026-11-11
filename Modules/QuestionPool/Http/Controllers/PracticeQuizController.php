<?php

namespace Modules\QuestionPool\Http\Controllers;

use App\LessonComplete;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\CourseSetting\Entities\Course;
use Modules\CourseSetting\Entities\CourseEnrolled;
use Modules\CourseSetting\Entities\Lesson;
use Modules\QuestionPool\Entities\PracticeQuiz;
use Modules\QuestionPool\Entities\PracticeQuizDetail;
use Modules\QuestionPool\Entities\QuestionPoolQuestion;

class PracticeQuizController extends Controller
{
    public function startLesson($lessonId)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $lesson = Lesson::findOrFail($lessonId);

            // Verify enrollment (allow admins as well)
            $isEnrolled = (int)$user->role_id === 1
                || CourseEnrolled::where('user_id', $user->id)->where('course_id', $lesson->course_id)->exists();
            if (!$isEnrolled) {
                Toastr::error('You must be enrolled in this course to take a practice quiz', 'Error');
                return back();
            }

            if ($lesson->is_practice_quiz != 1) {
                Toastr::error('Invalid practice quiz lesson', 'Error');
                return back();
            }

            $query = QuestionPoolQuestion::active()->where('course_id', $lesson->course_id)->where('type', '!=', 'F');

            // Multi-select support: read comma-separated IDs from lesson
            $chapterIds = $lesson->practice_quiz_chapter_ids ? array_filter(explode(',', $lesson->practice_quiz_chapter_ids)) : [];
            $lessonIds = $lesson->practice_quiz_lesson_ids ? array_filter(explode(',', $lesson->practice_quiz_lesson_ids)) : [];

            // Backward compat: fall back to old single-value fields
            if (empty($lessonIds) && $lesson->practice_quiz_lesson_id) {
                $lessonIds = [$lesson->practice_quiz_lesson_id];
            }
            if (empty($chapterIds) && $lesson->chapter_id) {
                $chapterIds = [$lesson->chapter_id];
            }

            if (!empty($lessonIds)) {
                $query->whereIn('lesson_id', $lessonIds);
                $scope = 'lesson';
            } elseif (!empty($chapterIds)) {
                $query->whereIn('chapter_id', $chapterIds);
                $scope = 'chapter';
            } else {
                $scope = 'course';
            }

            $available_count = $query->count();
            $request_count = min($lesson->practice_quiz_question_count ?? 10, $available_count);

            if ($request_count < 1) {
                DB::rollBack();
                Toastr::error('No questions available for this practice quiz', 'Error');
                return back();
            }

            $questions = $query->inRandomOrder()->limit($request_count)->get();
            $total_marks = $questions->sum('marks');

            $quiz = new PracticeQuiz();
            $quiz->user_id = $user->id;
            $quiz->course_id = $lesson->course_id;
            $quiz->chapter_id = !empty($chapterIds) ? $chapterIds[0] : null;
            $quiz->lesson_id = !empty($lessonIds) ? $lessonIds[0] : null;
            $quiz->chapter_ids = !empty($chapterIds) ? implode(',', $chapterIds) : null;
            $quiz->lesson_ids = !empty($lessonIds) ? implode(',', $lessonIds) : null;
            $quiz->scope = $scope;
            $quiz->total_questions = $request_count;
            $quiz->total_marks = $total_marks;
            $quiz->estimated_time = $request_count; // 1 min per question
            $quiz->status = 0; // in-progress
            $quiz->started_at = now();
            $quiz->lms_id = $lesson->id;
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

    public function setup(Request $request, $courseId)
    {
        try {
            $user = Auth::user();

            // Verify enrollment (allow admins as well)
            $isEnrolled = (int)$user->role_id === 1
                || CourseEnrolled::where('user_id', $user->id)->where('course_id', $courseId)->exists();
            if (!$isEnrolled) {
                Toastr::error('You must be enrolled in this course to take a practice quiz', 'Error');
                return back();
            }

            $course = Course::with('chapters.lessons')->findOrFail($courseId);

            $preselectScope = 'course';
            $preselectChapterIds = [];
            $preselectLessonIds = [];
            $preselectQuestionCount = 10;

            if ($request->has('quiz_lesson_id')) {
                $lesson = Lesson::find($request->quiz_lesson_id);
                if ($lesson) {
                    $preselectChapterIds = $lesson->practice_quiz_chapter_ids ? array_filter(explode(',', $lesson->practice_quiz_chapter_ids)) : [];
                    $preselectLessonIds = $lesson->practice_quiz_lesson_ids ? array_filter(explode(',', $lesson->practice_quiz_lesson_ids)) : [];

                    if (empty($preselectLessonIds) && $lesson->practice_quiz_lesson_id) {
                        $preselectLessonIds = [$lesson->practice_quiz_lesson_id];
                    }
                    if (empty($preselectChapterIds) && $lesson->chapter_id) {
                        $preselectChapterIds = [$lesson->chapter_id];
                    }

                    if (!empty($preselectLessonIds)) {
                        $preselectScope = 'lesson';
                    } elseif (!empty($preselectChapterIds)) {
                        $preselectScope = 'chapter';
                    }

                    if ($lesson->practice_quiz_question_count) {
                        $preselectQuestionCount = $lesson->practice_quiz_question_count;
                    }
                }
            }

            $history = PracticeQuiz::with('chapter', 'lesson')
                ->where('user_id', $user->id)
                ->where('course_id', $courseId)
                ->where('status', 1)
                ->latest()
                ->take(10)
                ->get();

            return view('questionpool::student.setup', compact(
                'course',
                'preselectScope',
                'preselectChapterIds',
                'preselectLessonIds',
                'preselectQuestionCount',
                'history'
            ));
        } catch (Exception $e) {
            Toastr::error($e->getMessage(), 'Error');
            return back();
        }
    }

    public function availableCount(Request $request)
    {
        try {
            $query = QuestionPoolQuestion::active()->where('course_id', $request->course_id)->where('type', '!=', 'F');

            // Multi-select support
            $lessonIds = $request->lesson_ids ?? ($request->lesson_id ? [$request->lesson_id] : []);
            $chapterIds = $request->chapter_ids ?? ($request->chapter_id ? [$request->chapter_id] : []);

            if (!empty($lessonIds)) {
                $query->whereIn('lesson_id', $lessonIds);
            } elseif (!empty($chapterIds)) {
                $query->whereIn('chapter_id', $chapterIds);
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

            // Multi-select support
            $chapterIds = $request->chapter_ids ?? ($request->chapter_id ? [$request->chapter_id] : []);
            $lessonIds = $request->lesson_ids ?? ($request->lesson_id ? [$request->lesson_id] : []);

            if ($request->scope == 'lesson' && !empty($lessonIds)) {
                $query->whereIn('lesson_id', $lessonIds);
            } elseif ($request->scope == 'chapter' && !empty($chapterIds)) {
                $query->whereIn('chapter_id', $chapterIds);
            }

            $available_count = $query->count();
            $request_count = min($request->question_count, $available_count);

            if ($request_count < 1) {
                DB::rollBack();
                Toastr::error('No questions available for this selection', 'Error');
                return back();
            }

            $questions = $query->inRandomOrder()->limit($request_count)->get();
            $total_marks = $questions->sum('marks');

            $quiz = new PracticeQuiz();
            $quiz->user_id = $user->id;
            $quiz->course_id = $request->course_id;
            $quiz->chapter_id = !empty($chapterIds) ? $chapterIds[0] : null;
            $quiz->lesson_id = !empty($lessonIds) ? $lessonIds[0] : null;
            $quiz->chapter_ids = !empty($chapterIds) ? implode(',', $chapterIds) : null;
            $quiz->lesson_ids = !empty($lessonIds) ? implode(',', $lessonIds) : null;
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
            $quiz = PracticeQuiz::with('details.question.options', 'course', 'chapter', 'lesson')
                ->where('user_id', $user->id)
                ->findOrFail($id);

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
                if (is_array($given_answer)) {
                    $given_answer = $given_answer[0] ?? null;
                }
                $is_correct = null;
                $marks = 0;

                if ($given_answer !== null && $given_answer !== '') {
                    $is_correct = 0;
                    if ($question->type == 'M') {
                        $option = $question->options->where('id', $given_answer)->first();
                        if ($option && $option->status == 1) {
                            $is_correct = 1;
                        }
                    } elseif ($question->type == 'T') {
                        if ($given_answer == $question->true_false) {
                            $is_correct = 1;
                        }
                    } elseif ($question->type == 'F') {
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

                $detail->given_answer = ($given_answer !== null && $given_answer !== '') ? $given_answer : null;
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

            $preResult = PracticeQuiz::with('chapter', 'lesson')
                ->where('user_id', $user->id)
                ->where('course_id', $quiz->course_id)
                ->where('status', 1)
                ->latest()
                ->take(10)
                ->get();

            return view('questionpool::student.result', compact('quiz', 'attempt_number', 'preResult'));
        } catch (Exception $e) {
            Toastr::error($e->getMessage(), 'Error');
            return back();
        }
    }

    public function answerSheet($id)
    {
        try {
            $user = Auth::user();
            $quiz = PracticeQuiz::with('details.question.options', 'course', 'chapter', 'lesson')
                ->where('user_id', $user->id)
                ->findOrFail($id);

            return view('questionpool::student.answer_sheet', compact('quiz'));
        } catch (Exception $e) {
            Toastr::error($e->getMessage(), 'Error');
            return back();
        }
    }

    public function history(Request $request)
    {
        try {
            $user = Auth::user();

            $query = PracticeQuiz::with('course', 'chapter', 'lesson')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc');

            if ($request->filled('course_id')) {
                $query->where('course_id', $request->course_id);
            }
            if ($request->filled('chapter_id')) {
                $query->where('chapter_id', $request->chapter_id);
            }
            if ($request->filled('lesson_id')) {
                $query->where('lesson_id', $request->lesson_id);
            }
            if ($request->filled('status')) {
                if ($request->status === 'passed') {
                    $query->where('status', 1)->where('pass', 1);
                } elseif ($request->status === 'failed') {
                    $query->where('status', 1)->where('pass', '!=', 1);
                } elseif ($request->status === 'in_progress') {
                    $query->where('status', '!=', 1);
                }
            }

            $quizzes = $query->get();

            $allHistory = PracticeQuiz::with('course', 'chapter', 'lesson')
                ->where('user_id', $user->id)
                ->get();

            $courses = $allHistory->pluck('course')->filter()->unique('id');
            $chapters = $allHistory->pluck('chapter')->filter()->unique('id');
            $lessons = $allHistory->pluck('lesson')->filter()->unique('id');

            $course_id = $request->course_id ?? '';
            $chapter_id = $request->chapter_id ?? '';
            $lesson_id = $request->lesson_id ?? '';
            $filter_status = $request->status ?? '';

            return view('questionpool::student.history', compact(
                'quizzes',
                'courses',
                'chapters',
                'lessons',
                'course_id',
                'chapter_id',
                'lesson_id',
                'filter_status'
            ));
        } catch (Exception $e) {
            Toastr::error($e->getMessage(), 'Error');
            return back();
        }
    }
}
