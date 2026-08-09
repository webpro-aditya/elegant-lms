<?php

namespace Modules\QuestionPool\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\CourseSetting\Entities\Chapter;
use Modules\CourseSetting\Entities\Course;
use Modules\CourseSetting\Entities\Lesson;
use Modules\QuestionPool\Entities\QuestionPoolQuestion;
use Modules\QuestionPool\Entities\QuestionPoolOption;
use Modules\QuestionPool\Imports\QuestionPoolImport;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\UploadMedia;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Support\Facades\DB;

class QuestionPoolController extends Controller
{
    use UploadMedia;

    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            
            if ($request->ajax()) {
                $query = QuestionPoolQuestion::with('course', 'chapter', 'lesson')->select('question_pool_questions.*');
                
                if ($user->role_id == 2) { // Instructor
                    $query->where('user_id', $user->id);
                }

                if ($request->course_id) {
                    $query->where('course_id', $request->course_id);
                }
                if ($request->chapter_id) {
                    $query->where('chapter_id', $request->chapter_id);
                }
                if ($request->lesson_id) {
                    $query->where('lesson_id', $request->lesson_id);
                }
                if ($request->type) {
                    $query->where('type', $request->type);
                }
                if ($request->status != "") {
                    $query->where('active_status', $request->status);
                }

                return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('question', function ($row) {
                        return strip_tags(substr($row->question, 0, 50)) . '...';
                    })
                    ->addColumn('course', function ($row) {
                        return $row->course->title ?? '-';
                    })
                    ->addColumn('chapter', function ($row) {
                        return $row->chapter->name ?? '-';
                    })
                    ->addColumn('lesson', function ($row) {
                        return $row->lesson->name ?? '-';
                    })
                    ->addColumn('type', function ($row) {
                        if ($row->type == 'M') return 'Multiple Choice';
                        if ($row->type == 'T') return 'True/False';
                        if ($row->type == 'F') return 'Fill in the blanks';
                        return '-';
                    })
                    ->addColumn('status', function ($row) {
                        return $row->active_status == 1 ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
                    })
                    ->addColumn('action', function ($row) {
                        $editUrl = route('question-pool.edit', $row->id);
                        $btn = '<div class="dropdown CRM_dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu2">
                                        <a href="' . $editUrl . '" class="dropdown-item">Edit</a>
                                        <a href="#" data-id="' . $row->id . '" class="dropdown-item deleteQuestion">Delete</a>
                                    </div>
                                </div>';
                        return $btn;
                    })
                    ->rawColumns(['status', 'action'])
                    ->make(true);
            }

            $courses = Course::where('type', 1)->get();
            if ($user->role_id == 2) {
                $courses = Course::where('type', 1)->where('user_id', $user->id)->get();
            }

            return view('questionpool::admin.index', compact('courses'));
        } catch (Exception $e) {
            Toastr::error($e->getMessage(), 'Error');
            return back();
        }
    }

    public function create()
    {
        try {
            $user = Auth::user();
            $courses = Course::where('type', 1)->get();
            if ($user->role_id == 2) {
                $courses = Course::where('type', 1)->where('user_id', $user->id)->get();
            }
            return view('questionpool::admin.create', compact('courses'));
        } catch (Exception $e) {
            Toastr::error($e->getMessage(), 'Error');
            return back();
        }
    }

    public function getChapters($courseId)
    {
        $chapters = Chapter::where('course_id', $courseId)->get();
        return response()->json($chapters);
    }

    public function getLessons($chapterId)
    {
        $lessons = Lesson::where('chapter_id', $chapterId)->get();
        return response()->json($lessons);
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
            'type' => 'required',
            'question' => 'required',
            'marks' => 'required|numeric'
        ]);

        DB::beginTransaction();
        try {
            $question = new QuestionPoolQuestion();
            $question->course_id = $request->course_id;
            $question->chapter_id = $request->chapter_id;
            $question->lesson_id = $request->lesson_id;
            $question->type = $request->type;
            $question->question = $request->question;
            $question->marks = $request->marks;
            $question->explanation = $request->explanation;
            $question->active_status = $request->active_status ?? 1;
            $question->user_id = Auth::id();

            if ($request->type == 'M') {
                $question->save();
                if ($request->option) {
                    foreach ($request->option as $key => $option) {
                        $newOption = new QuestionPoolOption();
                        $newOption->question_pool_question_id = $question->id;
                        $newOption->title = $option;
                        $newOption->status = $request->correct == $key ? 1 : 0;
                        $newOption->save();
                    }
                }
            } elseif ($request->type == 'T') {
                $question->true_false = $request->true_false;
                $question->save();
            } elseif ($request->type == 'F') {
                $question->suitable_words = $request->suitable_words;
                $question->save();
            }

            if ($request->hasFile('image')) {
                $question->image = $this->generateDefaultData($request->image);
                $question->save();
            }

            DB::commit();
            Toastr::success('Question saved successfully', 'Success');
            return redirect()->route('question-pool.index');
        } catch (Exception $e) {
            DB::rollBack();
            Toastr::error($e->getMessage(), 'Error');
            return back();
        }
    }

    public function edit($id)
    {
        try {
            $user = Auth::user();
            $question = QuestionPoolQuestion::with('options')->findOrFail($id);
            
            if ($user->role_id == 2 && $question->user_id != $user->id) {
                Toastr::error('Unauthorized access', 'Error');
                return redirect()->route('question-pool.index');
            }

            $courses = Course::where('type', 1)->get();
            if ($user->role_id == 2) {
                $courses = Course::where('type', 1)->where('user_id', $user->id)->get();
            }
            $chapters = Chapter::where('course_id', $question->course_id)->get();
            $lessons = Lesson::where('chapter_id', $question->chapter_id)->get();

            return view('questionpool::admin.edit', compact('question', 'courses', 'chapters', 'lessons'));
        } catch (Exception $e) {
            Toastr::error($e->getMessage(), 'Error');
            return back();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'course_id' => 'required',
            'type' => 'required',
            'question' => 'required',
            'marks' => 'required|numeric'
        ]);

        DB::beginTransaction();
        try {
            $question = QuestionPoolQuestion::findOrFail($id);
            
            $user = Auth::user();
            if ($user->role_id == 2 && $question->user_id != $user->id) {
                Toastr::error('Unauthorized access', 'Error');
                return redirect()->route('question-pool.index');
            }

            $question->course_id = $request->course_id;
            $question->chapter_id = $request->chapter_id;
            $question->lesson_id = $request->lesson_id;
            $question->type = $request->type;
            $question->question = $request->question;
            $question->marks = $request->marks;
            $question->explanation = $request->explanation;
            $question->active_status = $request->active_status ?? 1;

            if ($request->hasFile('image')) {
                $question->image = $this->generateDefaultData($request->image);
            }

            if ($request->type == 'M') {
                $question->save();
                QuestionPoolOption::where('question_pool_question_id', $question->id)->delete();
                if ($request->option) {
                    foreach ($request->option as $key => $option) {
                        $newOption = new QuestionPoolOption();
                        $newOption->question_pool_question_id = $question->id;
                        $newOption->title = $option;
                        $newOption->status = $request->correct == $key ? 1 : 0;
                        $newOption->save();
                    }
                }
            } elseif ($request->type == 'T') {
                $question->true_false = $request->true_false;
                $question->save();
            } elseif ($request->type == 'F') {
                $question->suitable_words = $request->suitable_words;
                $question->save();
            }

            DB::commit();
            Toastr::success('Question updated successfully', 'Success');
            return redirect()->route('question-pool.index');
        } catch (Exception $e) {
            DB::rollBack();
            Toastr::error($e->getMessage(), 'Error');
            return back();
        }
    }

    public function destroy(Request $request)
    {
        try {
            $question = QuestionPoolQuestion::findOrFail($request->id);
            $user = Auth::user();
            if ($user->role_id == 2 && $question->user_id != $user->id) {
                Toastr::error('Unauthorized access', 'Error');
                return redirect()->route('question-pool.index');
            }
            $question->delete();
            Toastr::success('Question deleted successfully', 'Success');
            return redirect()->back();
        } catch (Exception $e) {
            Toastr::error($e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    public function bulkImport()
    {
        return view('questionpool::admin.bulk_import');
    }

    public function bulkImportSubmit(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        try {
            Excel::import(new QuestionPoolImport, $request->file('file'));
            Toastr::success('Questions imported successfully', 'Success');
            return redirect()->route('question-pool.index');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $error = '';
            foreach ($failures as $failure) {
                $error .= 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors()) . '<br>';
            }
            Toastr::error($error, 'Error');
            return back();
        } catch (Exception $e) {
            Toastr::error($e->getMessage(), 'Error');
            return back();
        }
    }

    public function downloadSample()
    {
        // For simplicity, we can redirect to a static file or generate one.
        // Let's create a static file in public/samples/question_pool_sample.csv later.
        $path = public_path('samples/question_pool_sample.csv');
        if (file_exists($path)) {
            return response()->download($path);
        }
        Toastr::error('Sample file not found', 'Error');
        return back();
    }
}
