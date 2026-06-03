<?php

namespace Modules\CourseSetting\Http\Controllers;

use App\Exports\CourseStatisticsReport;
use App\Exports\QuizStatisticsReport;
use App\Notifications\EmailNotification;
use App\Notifications\GeneralNotification;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Modules\CourseSetting\Entities\Category;
use Modules\CourseSetting\Entities\Course;
use Modules\CourseSetting\Entities\CourseEnrolled;
use Modules\CourseSetting\Entities\CourseEnrollmentLog;
use Modules\Org\Entities\OrgBranch;
use Modules\Org\Entities\OrgPosition;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class CourseInvitationController extends Controller
{

    //    public function courseInvitation($course_id)
    //    {
    //
    //        $course = Course::findOrFail($course_id);
    //        try {
    //            $enrollUsers = [];
    //            foreach ($course->enrollUsers as $key => $user) {
    //                $enrollUsers[] = $user->id;
    //            }
    //            $other_students = User::whereIn('role_id', [2, 3])->whereNotIn('id', $enrollUsers)->where('status', 1)->get();
    //
    //            foreach ($other_students as $key => $student) {
    //                SendInvitation::dispatch($course, $student);
    //            }
    //            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
    //            return redirect()->back();
    //        } catch (\Throwable $th) {
    //            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
    //            return redirect()->back();
    //        }
    //
    //    }

    public function courseStatistics()
    {
        try {
            $categories = Category::with('childs')->where('status', 1)->orderBy('position_order', 'ASC')->get();

            $courses = [];
            $data = [];
            $data['overviewStatus']['not_start'] = 0;
            $data['overviewStatus']['in_process'] = 0;
            $data['overviewStatus']['finished'] = 0;
            $data['overviewStatus']['total_enrolled'] = 0;

            $data['quizStatistics']['not_start'] = 0;
            $data['quizStatistics']['fail'] = 0;
            $data['quizStatistics']['pass'] = 0;

            if (empty(request('type'))) {
                \request()->merge([
                    'type' => 1
                ]);
            }
            if (empty(request('mode_of_delivery'))) {
                \request()->merge([
                    'mode_of_delivery' => 1
                ]);
            }

            if (request('type') == 1) {
                $statistics = $this->courseStatisticFilterQuery()->get();
                foreach ($statistics as $statistic) {
                    $status = $statistic->totalStatistic([
                        'status' => request('student_status')
                    ]);
                    $data['overviewStatus']['not_start'] = $data['overviewStatus']['not_start'] + $status['not_start'];
                    $data['overviewStatus']['in_process'] = $data['overviewStatus']['in_process'] + $status['in_process'];
                    $data['overviewStatus']['finished'] = $data['overviewStatus']['finished'] + $status['finished'];
                    $data['overviewStatus']['total_enrolled'] = $data['overviewStatus']['total_enrolled'] + $status['total_enroll'];
                }
            } elseif (request('type') == 3) {
                $statistics = $this->courseStatisticFilterQuery()->get();
                foreach ($statistics as $statistic) {
                    $status = $statistic->totalClassStatistic([
                        'status' => request('student_status')
                    ]);
                    $data['overviewStatus']['not_start'] = $data['overviewStatus']['not_start'] + $status['not_start'];
                    $data['overviewStatus']['in_process'] = $data['overviewStatus']['in_process'] + $status['in_process'];
                    $data['overviewStatus']['finished'] = $data['overviewStatus']['finished'] + $status['finished'];
                    $data['overviewStatus']['total_enrolled'] = $data['overviewStatus']['total_enrolled'] + $status['total_enroll'];
                }
            } else {
                $quizStatistics = $this->courseStatisticFilterQuery()->get();
                foreach ($quizStatistics as $statistic) {
                    $status = $statistic->totalQuizStatistic([
                        'status' => request('student_status')
                    ]);
                    $data['quizStatistics']['not_start'] = $data['quizStatistics']['not_start'] + $status['not_start'];
                    $data['quizStatistics']['fail'] = $data['quizStatistics']['fail'] + $status['fail'];
                    $data['quizStatistics']['pass'] = $data['quizStatistics']['pass'] + $status['pass'];
                }
            }
            if (isModuleActive('Org')) {
                $data['positions'] = OrgPosition::orderBy('order', 'asc')->get();
                $data['branches'] = OrgBranch::where('parent_id', 0)->orderBy('order', 'asc')->get();
            }
            return view('coursesetting::statistics', $data, compact('courses', 'categories'));
        } catch (Exception $e) {
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }
    }

    public function courseStatisticFilterQuery()
    {
        $query = Course::with('category', 'user', 'enrolls');
        if (\request('type')) {
            $query->where('type', \request('type'));
        }
        if (\request('category')) {
            $category = Category::find(request('category'));
            if ($category) {

                $ids = $category->getAllChildIds($category, [$category->id]);

                if (request('type') == 1) {
                    $query->where(function ($q) use ($ids) {
                        $q->whereIn('category_id', $ids);
                        $q->orWhereIn('subcategory_id', $ids);
                    });
                } elseif (request('type') == 2) {
                    $query->whereHas('quiz', function ($q) use ($ids) {
                        $q->where(function ($q2) use ($ids) {
                            $q2->whereIn('category_id', $ids);
                            $q2->orWhereIn('subcategory_id', $ids);
                        });
                    });
                } elseif (\request('type') == 3) {
                    $query->whereHas('class', function ($q) use ($ids) {
                        $q->where(function ($q2) use ($ids) {
                            $q2->whereIn('category_id', $ids);
                            $q2->orWhereIn('sub_category_id', $ids);
                        });
                    });
                }
            }
        }
        if (isModuleActive('Org')) {
            if (\request('required_type') == '0') {
                $query->where('required_type', '=', '0');
            }
            if (\request('required_type') == '1') {
                $query->where('required_type', '=', '1');
            }
            if (\request('mode_of_delivery')) {
                $query->where('mode_of_delivery', \request('mode_of_delivery'));
            }

            $query->whereHas('enrolls', function ($q) {
                $q->whereHas('user', function ($query) {
                    if (request('org_branch_code_search')) {
                        $query->where('org_chart_code', request('org_branch_code_search'));
                    }
                    if (request('job_position')) {
                        $query->where('org_position_code', request('job_position'));
                    }
                });
            });
        }

        $query->whereHas('enrolls', function ($q) {
            $q->whereHas('user', function ($q2) {
                if (request('student_status', 0)) {
                    $q2->where('status', (int)request('student_status', 0) == 1 ? 1 : 0);
                }
            });
        });

        if (isInstructor()) {
            $query->where('user_id', '=', Auth::id());
            $query->orWhere('assistant_instructors', 'like', '%"{' . Auth::id() . '}"%');
        }
        return $query;
    }

    public function enrolled_students($course_id)
    {
        try {
            $course = Course::find($course_id);
            $students = [];
            return view('coursesetting::student_list', compact('students', 'course'));
        } catch (Exception $e) {
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }
    }

    public function getAllStudentData(Request $request, $course_id)
    {
        $course = Course::find($course_id);

        $query = User::join('course_enrolleds', function ($join) use ($course_id) {
                $join->on('course_enrolleds.user_id', '=', 'users.id')
                    ->where('course_enrolleds.course_id', '=', $course_id);
            })
            ->select(
                'users.*',
                'course_enrolleds.id as enroll_id',
                'course_enrolleds.start_date as enroll_date',
                'course_enrolleds.end_date as expiry_date',
                'course_enrolleds.status as enroll_status'
            );

        return Datatables::of($query)
            ->addIndexColumn()

            ->addColumn('image', function ($row) {
                return "<div class=\"profile_info\"><img src='"
                    . getProfileImage($row->image, $row->name)
                    . "' alt='" . e($row->name) . " image'></div>";
            })

            ->addColumn('student_name', function ($row) {
                return '<a class="dropdown-item" target="_blank" href="'
                    . route('student.courses', $row->id)
                    . '" data-id="' . $row->id . '" type="button">'
                    . e($row->name) . '</a>';
            })

            ->editColumn('email', function ($row) {
                return $row->email;
            })

            ->editColumn('phone', function ($row) {
                return translatedNumber($row->phone);
            })

            ->addColumn('progressbar', function ($row) use ($course) {
                $pct = round($course->userTotalPercentage($row->id, $course->id));

                return "<div class='progress_percent flex-fill text-end'>
                            <div class='progress theme_progressBar'>
                                <div class='progress-bar' role='progressbar'
                                    style='width:{$pct}%'
                                    aria-valuenow='{$pct}' aria-valuemin='0' aria-valuemax='100'></div>
                            </div>
                            <p class='font_14 f_w_400'>"
                    . translatedNumber($pct) . "% " . trans('courses.Completed') .
                    "</p>
                        </div>";
            })

            ->addColumn('enroll_start_date', function ($row) {
                $startDate   = $row->enroll_date;
                $endDate     = $row->expiry_date;
                $enrollId    = $row->enroll_id;
                $studentName = e($row->name);

                $badge = $startDate
                    ? '<span class="em-date-badge em-date-start"><i class="ti-calendar"></i>'
                        . \Carbon\Carbon::parse($startDate)->format('d M Y, h:i A')
                        . '</span>'
                    : '<span class="em-date-dash">—</span>';

                return '<div class="edit-date-cell"'
                    . ' data-user-id="' . $row->id . '"'
                    . ' data-enroll-id="' . e($enrollId) . '"'
                    . ' data-student-name="' . $studentName . '"'
                    . ' data-start-date="' . e($startDate ?? '') . '"'
                    . ' data-end-date="' . e($endDate ?? '') . '"'
                    . ' title="Click to edit enrollment dates">'
                    . $badge
                    . '<span class="edit-pencil"><i class="ti-pencil"></i></span>'
                    . '</div>';
            })

            ->addColumn('enroll_end_date', function ($row) {
                $endDate = $row->expiry_date;

                if (!$endDate) {
                    return '<span class="em-date-badge em-date-lifetime"><i class="ti-infinite"></i> Lifetime</span>';
                }

                $end = \Carbon\Carbon::parse($endDate);
                $now = \Carbon\Carbon::now();

                if ($end->isPast()) {
                    $badgeClass = 'em-date-expired';
                    $icon = 'ti-alert';
                } elseif ($end->diffInDays($now) <= 7) {
                    $badgeClass = 'em-date-soon';
                    $icon = 'ti-timer';
                } else {
                    $badgeClass = 'em-date-active';
                    $icon = 'ti-check';
                }

                return '<span class="em-date-badge ' . $badgeClass . '">'
                    . '<i class="' . $icon . '"></i>'
                    . $end->format('d M Y, h:i A')
                    . '</span>';
            })

            ->editColumn('dob', function ($row) {
                return showDate($row->dob);
            })

            ->addColumn('start_working_date', function ($row) {
                return isModuleActive('Org') ? showDate($row->start_working_date) : '';
            })

            ->editColumn('country', function ($row) {
                return $row->userCountry->name ?? '';
            })

            ->addColumn('status', function ($row) use ($course) {
                $checked = $row->enroll_status == 1 ? 'checked' : '';

                return '<label class="switch_toggle">
                            <input type="checkbox" class="enrollment_status_toggle" data-user-id="' . $row->id . '" data-course-id="' . $course->id . '" ' . $checked . '>
                            <i class="slider round"></i>
                        </label>';
            })

            ->addColumn('notify_user', function ($row) use ($course) {
                if (round($course->userTotalPercentage($row->id, $course->id)) < 100) {
                    return '<a href="' . route('course.courseStudentNotify', [$course->id, $row->id]) . '" data-id="' . $row->id . '" type="button">'
                        . trans('courses.Notify') . '</a>';
                }

                return '';
            })

            ->addColumn('remove_student', function ($row) {
                return '<button class="btn btn-sm unenroll-btn" data-user-id="' . $row->id . '" data-student-name="' . e($row->name) . '" title="' . trans('courses.Remove Student') . '">'
                    . '<i class="ti-trash"></i>'
                    . '</button>';
            })

            ->rawColumns([
                'status',
                'progressbar',
                'image',
                'notify_user',
                'action',
                'student_name',
                'enroll_start_date',
                'enroll_end_date',
                'remove_student',
            ])
            ->make(true);
    }

    public function courseStudentNotify($course_id, $student_id)
    {
        try {
            $course = Course::find($course_id);
            $user = User::find($student_id);
            $percentage = round($course->userTotalPercentage($student_id, $course_id));
            $message = trans('courses.You have complete') . " " . $percentage . "% " . trans('courses.of') . ' ' . $course->title . ". " . trans('courses.Please complete as soon as possible');
            $details = [
                'title' => trans('courses.Incomplete course reminder'),
                'body' => $message,
                'actionText' => trans('courses.Visit'),
                'actionURL' => route('courseDetailsView', $course->slug),
            ];
            Notification::send($user, new GeneralNotification($details));
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();
        } catch (Throwable $th) {
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }
    }

    public function courseStatisticsCourseData()
    {

        $query = $this->courseStatisticFilterQuery();
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('required_type', function ($query) {
                return $query->required_type == 1 ? trans('courses.Compulsory') : trans('courses.Open');
            })->editColumn('mode_of_delivery', function ($query) {
                if ($query->mode_of_delivery == 1) {
                    $title = trans('courses.Online');
                } elseif ($query->mode_of_delivery == 2) {
                    $title = trans('courses.Distance Learning');
                } else {
                    if (isModuleActive('Org')) {
                        $title = trans('courses.Offline');
                    } else {
                        $title = trans('courses.Face-to-Face');
                    }
                }
                return $title;
            })
            ->addColumn('type', function ($query) {
                return $query->type == 1 ? trans('courses.Course') : trans('quiz.Quiz');
            })
            ->editColumn('total_enrolled', function ($query) {
                return translatedNumber($query->totalStatistic([
                    'status' => request('student_status')
                ])['total_enroll']);
            })->editColumn('title', function ($query) {
                return $query->title;
            })
            ->addColumn('not_start', function ($query) {
                return translatedNumber($query->totalStatistic([
                    'status' => request('student_status')
                ])['not_start']);
            })
            ->addColumn('in_process', function ($query) {
                return translatedNumber($query->totalStatistic([
                    'status' => request('student_status')
                ])['in_process']);
            })
            ->addColumn('finished', function ($query) {
                return translatedNumber($query->totalStatistic([
                    'status' => request('student_status')
                ])['finished']);
            })
            ->addColumn('finished_rate', function ($query) {
                $finished = $query->totalStatistic([
                    'status' => request('student_status')
                ])['finished'];
                $total = $query->total_enrolled;
                $percentage = 0;
                if ($total != 0) {
                    $percentage = ($finished / $total) * 100;
                    if ($percentage > 100) {
                        $percentage = 100;
                    }
                }
                return translatedNumber(round($percentage)) . '%';
            })
            ->make(true);
    }

    public function courseStatisticsQuizData()
    {
        $query = $this->courseStatisticFilterQuery();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('title', function ($query) {
                return $query->title;
            })
            ->editColumn('required_type', function ($query) {
                return $query->required_type == 1 ? trans('courses.Compulsory') : trans('courses.Open');
            })->editColumn('mode_of_delivery', function ($query) {
                if ($query->mode_of_delivery == 1) {
                    $title = trans('courses.Online');
                } elseif ($query->mode_of_delivery == 2) {
                    $title = trans('courses.Distance Learning');
                } else {
                    if (isModuleActive('Org')) {
                        $title = trans('courses.Offline');
                    } else {
                        $title = trans('courses.Face-to-Face');
                    }
                }
                return $title;
            })
            ->addColumn('type', function ($query) {
                return $query->type == 1 ? trans('courses.Course') : trans('quiz.Quiz');
            })
            ->editColumn('total_enrolled', function ($query) {
                return translatedNumber($query->totalQuizStatistic([
                    'status' => request('student_status')
                ])['total_enroll']);
            })
            ->addColumn('not_start', function ($query) {
                return translatedNumber($query->totalQuizStatistic([
                    'status' => request('student_status')
                ])['not_start']);
            })
            ->addColumn('fail', function ($query) {
                return translatedNumber($query->totalQuizStatistic([
                    'status' => request('student_status')
                ])['fail']);
            })
            ->addColumn('pass', function ($query) {
                return translatedNumber($query->totalQuizStatistic([
                    'status' => request('student_status')
                ])['pass']);
            })
            ->addColumn('pass_rate', function ($query) {
                $pass = $query->totalQuizStatistic([
                    'status' => request('student_status')
                ])['pass'];
                $total = $query->total_enrolled;
                $percentage = 0;
                if ($total != 0) {
                    $percentage = ($pass / $total) * 100;
                    if ($percentage > 100) {
                        $percentage = 100;
                    }
                }
                return translatedNumber($percentage) . '%';
            })
            ->make(true);
    }
    public function courseStatisticsClassData()
    {
        $query = $this->courseStatisticFilterQuery();
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('required_type', function ($query) {
                return $query->required_type == 1 ? trans('courses.Compulsory') : trans('courses.Open');
            })->editColumn('mode_of_delivery', function ($query) {
                if ($query->mode_of_delivery == 1) {
                    $title = trans('courses.Online');
                } elseif ($query->mode_of_delivery == 2) {
                    $title = trans('courses.Distance Learning');
                } else {
                    if (isModuleActive('Org')) {
                        $title = trans('courses.Offline');
                    } else {
                        $title = trans('courses.Face-to-Face');
                    }
                }
                return $title;
            })
            ->addColumn('type', function ($query) {
                return $query->type == 1 ? trans('courses.Course') : trans('quiz.Quiz');
            })
            ->editColumn('total_enrolled', function ($query) {
                return translatedNumber($query->totalClassStatistic([
                    'status' => request('student_status')
                ])['total_enroll']);
            })->editColumn('title', function ($query) {
                return $query->title;
            })
            ->addColumn('not_start', function ($query) {
                return translatedNumber($query->totalClassStatistic([
                    'status' => request('student_status')
                ])['not_start']);
            })
            ->addColumn('in_process', function ($query) {
                return translatedNumber($query->totalClassStatistic([
                    'status' => request('student_status')
                ])['in_process']);
            })
            ->addColumn('finished', function ($query) {
                return translatedNumber($query->totalClassStatistic([
                    'status' => request('student_status')
                ])['finished']);
            })
            ->addColumn('finished_rate', function ($query) {
                $finished = $query->totalClassStatistic([
                    'status' => request('student_status')
                ])['finished'];
                $total = $query->total_enrolled;
                $percentage = 0;
                if ($total != 0) {
                    $percentage = ($finished / $total) * 100;
                    if ($percentage > 100) {
                        $percentage = 100;
                    }
                }
                return translatedNumber(round($percentage)) . '%';
            })
            ->make(true);
    }


    public function courseStatisticsCourseReport()
    {
        return Excel::download(new CourseStatisticsReport(), 'course-statistic-report.xlsx');
    }

    public function courseStatisticsQuizReport()
    {
        return Excel::download(new QuizStatisticsReport(), 'quiz-statistic-report.xlsx');
    }

    // -----------------------------------------------------------
    // 1. SEARCH: Return users NOT yet enrolled in the course
    // -----------------------------------------------------------
    public function searchUsersForEnroll(Request $request, $course_id)
    {
        $search = $request->get('q', '');

        $enrolledIds = CourseEnrolled::where('course_id', $course_id)
            ->pluck('user_id')
            ->toArray();

        $users = User::where(function ($query) use ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%");
        })
            ->whereNotIn('id', $enrolledIds)
            ->where('status', 1)
            ->limit(20)
            ->get(['id', 'name', 'email']);

        return response()->json($users);
    }


    // -----------------------------------------------------------
    // 2. ENROLL: Insert a user into course_enrolleds
    // -----------------------------------------------------------
    public function enrollStudent(Request $request, $course_id)
    {
        $request->validate([
            'user_id'    => 'required|integer|exists:users,id',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'duration'   => 'nullable|integer|min:1',
        ]);

        $course = Course::findOrFail($course_id);

        $alreadyEnrolled = CourseEnrolled::where('course_id', $course_id)
            ->where('user_id', $request->user_id)
            ->exists();

        if ($alreadyEnrolled) {
            return response()->json([
                'success' => false,
                'message' => trans('courses.Already enrolled'),
            ], 409);
        }

        $startDate = null;
        $endDate   = null;

        if ($request->filled('start_date')) {
            $startDate = \Carbon\Carbon::parse($request->start_date)->toDateTimeString();

            if ($request->filled('end_date')) {
                $endDate = \Carbon\Carbon::parse($request->end_date)->toDateTimeString();
            } elseif ($request->filled('duration')) {
                $endDate = \Carbon\Carbon::parse($request->start_date)
                    ->addDays((int) $request->duration)
                    ->toDateTimeString();
            }
        } elseif ($request->filled('duration')) {
            $startDate = now()->toDateTimeString();
            $endDate   = now()->addDays((int) $request->duration)->toDateTimeString();
        }

        $purchasePrice  = (float) ($course->price ?? 0);
        $discountAmount = (float) ($course->discount_price ?? 0);
        $revenue        = max(0, $purchasePrice - $discountAmount);

        do {
            $tracking = strtoupper(Str::random(12));
        } while (CourseEnrolled::where('tracking', $tracking)->exists());

        $subscriptionValidityDate = null;
        if (!empty($course->subscription) && (int) $course->subscription > 0) {
            $subscriptionValidityDate = now()
                ->addDays((int) $course->subscription)
                ->toDateTimeString();
        }

        CourseEnrolled::insert([
            'tracking'                   => $tracking,
            'user_id'                    => $request->user_id,
            'course_id'                  => (int) $course_id,
            'purchase_price'             => $purchasePrice,
            'coupon'                     => null,
            'discount_amount'            => $discountAmount,
            'status'                     => 1,
            'reveune'                    => $revenue,
            'reason'                     => null,
            'created_at'                 => now(),
            'updated_at'                 => now(),
            'subscription'               => $course->subscription ?? 0,
            'subscription_validity_date' => $subscriptionValidityDate,
            'last_view_at'               => null,
            'lms_id'                     => $course->lms_id ?? 1,
            'send_expire_notification'   => 0,
            'start_date'                 => $startDate,
            'end_date'                   => $endDate,
        ]);

        // Log enrollment action
        CourseEnrollmentLog::create([
            'course_id'    => (int) $course_id,
            'user_id'      => $request->user_id,
            'performed_by' => Auth::id(),
            'action'       => 'enrolled',
            'details'      => json_encode([
                'start_date' => $startDate,
                'end_date'   => $endDate,
            ]),
        ]);

        return response()->json([
            'success' => true,
            'message' => trans('courses.Student enrolled successfully'),
        ]);
    }


    // -----------------------------------------------------------
    // 3. UNENROLL: Remove a user from course_enrolleds
    // -----------------------------------------------------------
    public function unenrollStudent(Request $request, $course_id)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $deleted = CourseEnrolled::where('course_id', $course_id)
            ->where('user_id', $request->user_id)
            ->delete();

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => trans('courses.Enrollment not found'),
            ], 404);
        }

        // Log removal action
        CourseEnrollmentLog::create([
            'course_id'    => (int) $course_id,
            'user_id'      => $request->user_id,
            'performed_by' => Auth::id(),
            'action'       => 'removed',
            'details'      => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => trans('courses.Student unenrolled successfully'),
        ]);
    }

    // -----------------------------------------------------------
    // 4. TOGGLE STATUS: Change student's active/inactive status
    // -----------------------------------------------------------
    public function toggleEnrollmentStatus(Request $request, $course_id)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'status'  => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
                'received_data' => $request->all()
            ]);
        }

        $enrollment = CourseEnrolled::where('course_id', $course_id)
            ->where('user_id', $request->user_id)
            ->first();

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => trans('courses.Enrollment not found'),
            ], 404);
        }

        $enrollment->status = $request->status;
        $enrollment->save();

        $actionStr = $request->status == 1 ? 'activated' : 'deactivated';

        // Log status change action
        CourseEnrollmentLog::create([
            'course_id'    => (int) $course_id,
            'user_id'      => $request->user_id,
            'performed_by' => Auth::id(),
            'action'       => 'updated',
            'details'      => ['note' => "Enrollment status $actionStr."],
        ]);

        return response()->json([
            'success' => true,
            'message' => trans('common.Status has been changed'),
        ]);
    }

    public function updateEnrollment(Request $request, $course_id)
    {
        $request->validate([
            'user_id'    => 'required|integer|exists:users,id',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $enrollment = CourseEnrolled::where('course_id', $course_id)
            ->where('user_id', $request->user_id)
            ->first();

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => trans('courses.Enrollment not found'),
            ], 404);
        }

        $startDate = $request->filled('start_date')
            ? \Carbon\Carbon::parse($request->start_date)->toDateTimeString()
            : null;

        $endDate = $request->filled('end_date')
            ? \Carbon\Carbon::parse($request->end_date)->toDateTimeString()
            : null;

        // Capture old values before update
        $oldStartDate = $enrollment->start_date;
        $oldEndDate   = $enrollment->end_date;

        $enrollment->update([
            'start_date' => $startDate,
            'end_date'   => $endDate,
            'updated_at' => now(),
        ]);

        // Log update action with old and new values
        CourseEnrollmentLog::create([
            'course_id'    => (int) $course_id,
            'user_id'      => $request->user_id,
            'performed_by' => Auth::id(),
            'action'       => 'updated',
            'details'      => json_encode([
                'old_start_date' => $oldStartDate,
                'old_end_date'   => $oldEndDate,
                'new_start_date' => $startDate,
                'new_end_date'   => $endDate,
            ]),
        ]);

        return response()->json([
            'success' => true,
            'message' => trans('courses.Enrollment updated successfully'),
        ]);
    }

    // -----------------------------------------------------------
    // ENROLLMENT ACTIVITY LOG
    // -----------------------------------------------------------

    /**
     * Show the enrollment activity log page for a course.
     */
    public function enrollmentActivityLog($course_id)
    {
        try {
            $course = Course::findOrFail($course_id);
            return view('coursesetting::enrollment_log', compact('course'));
        } catch (Exception $e) {
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }
    }

    /**
     * Return server-side DataTable data for the enrollment activity log.
     */
    public function getEnrollmentLogData(Request $request, $course_id)
    {
        $query = CourseEnrollmentLog::with(['user', 'performer'])
            ->where('course_id', $course_id)
            ->orderBy('created_at', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()

            ->addColumn('student_info', function ($row) {
                $avatar = '<div class="profile_info"><img src="'
                    . getProfileImage($row->user->image ?? null, $row->user->name ?? 'Unknown')
                    . '" alt="' . e($row->user->name ?? '') . '"></div>';
                $name = '<span class="log-student-name">' . e($row->user->name ?? 'Deleted User') . '</span>';
                $email = '<span class="log-student-email">' . e($row->user->email ?? '') . '</span>';
                return '<div class="log-student-cell">' . $avatar . '<div>' . $name . $email . '</div></div>';
            })

            ->addColumn('action_badge', function ($row) {
                $badges = [
                    'enrolled' => '<span class="log-badge log-badge-enrolled"><i class="ti-plus"></i> Enrolled</span>',
                    'updated'  => '<span class="log-badge log-badge-updated"><i class="ti-pencil"></i> Updated</span>',
                    'removed'  => '<span class="log-badge log-badge-removed"><i class="ti-trash"></i> Removed</span>',
                ];
                return $badges[$row->action] ?? '<span class="log-badge">' . e($row->action) . '</span>';
            })

            ->addColumn('details_html', function ($row) {
                $details = $row->decoded_details;
                if (empty($details)) return '<span class="text-muted">—</span>';

                $html = '';
                if ($row->action === 'enrolled') {
                    $start = $details['start_date'] ?? null;
                    $end   = $details['end_date'] ?? null;
                    if ($start) {
                        $html .= '<div class="log-detail-row"><span class="log-detail-label">Start:</span> '
                            . \Carbon\Carbon::parse($start)->format('d M Y, h:i A') . '</div>';
                    }
                    if ($end) {
                        $html .= '<div class="log-detail-row"><span class="log-detail-label">End:</span> '
                            . \Carbon\Carbon::parse($end)->format('d M Y, h:i A') . '</div>';
                    }
                    if (!$start && !$end) {
                        $html = '<span class="text-muted">Lifetime access</span>';
                    }
                } elseif ($row->action === 'updated') {
                    $oldStart = $details['old_start_date'] ?? null;
                    $newStart = $details['new_start_date'] ?? null;
                    $oldEnd   = $details['old_end_date'] ?? null;
                    $newEnd   = $details['new_end_date'] ?? null;

                    $fmtDate = function ($d) {
                        return $d ? \Carbon\Carbon::parse($d)->format('d M Y, h:i A') : 'None';
                    };

                    if ($oldStart !== $newStart) {
                        $html .= '<div class="log-detail-row"><span class="log-detail-label">Start:</span> '
                            . $fmtDate($oldStart) . ' → ' . $fmtDate($newStart) . '</div>';
                    }
                    if ($oldEnd !== $newEnd) {
                        $html .= '<div class="log-detail-row"><span class="log-detail-label">End:</span> '
                            . $fmtDate($oldEnd) . ' → ' . $fmtDate($newEnd) . '</div>';
                    }
                    if (empty($html)) {
                        $html = '<span class="text-muted">No date changes</span>';
                    }
                }

                return $html ?: '<span class="text-muted">—</span>';
            })

            ->addColumn('performed_by_name', function ($row) {
                return e($row->performer->name ?? 'System');
            })

            ->addColumn('log_date', function ($row) {
                return '<span class="log-date">' . $row->created_at->format('d M Y, h:i A') . '</span>';
            })

            ->rawColumns(['student_info', 'action_badge', 'details_html', 'log_date'])
            ->make(true);
    }
}
