@php
    $courseQuizHistory = \Modules\Quiz\Entities\QuizTest::where('course_id', $course->id)
        ->where('user_id', Auth::id())
        ->with('quiz')
        ->orderBy('id', 'desc')
        ->get();
@endphp

<div class="modal cs_modala fade" id="courseQuizHistoryModal" tabindex="-1" aria-labelledby="courseQuizHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="courseQuizHistoryModalLabel">{{__('frontend.Quiz History')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="ti-close"></i></button>
            </div>
            <div class="modal-body p-4">
                @if($courseQuizHistory->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>{{__('quiz.Quiz')}}</th>
                                    <th>{{__('common.Date')}}</th>
                                    <th>{{__('quiz.Marks')}}</th>
                                    <th>{{__('quiz.Percentage')}}</th>
                                    <th>{{__('common.Rating')}}</th>
                                    <th>{{__('common.Details')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courseQuizHistory as $history)
                                    @php
                                        $date = showDate($history->created_at) . ' ' . $history->created_at->format('h:i A');
                                        $totalQus = totalQuizQus($history->quiz_id);
                                        $totalAns = count($history->details);
                                        $totalCorrect = 0;
                                        $totalScore = totalQuizMarks($history->quiz_id);
                                        $score = 0;
                                        if ($totalAns != 0) {
                                            foreach ($history->details as $test) {
                                                if ($test->status == 1) {
                                                    $score += $test->mark ?? 1;
                                                    $totalCorrect++;
                                                }
                                            }
                                        }
                                        $passMark = $history->quiz->percentage ?? 0;
                                        $mark = $score > 0 && $totalScore > 0 ? round($score / $totalScore * 100, 2) : 0;
                                        $status = $mark >= $passMark ? "Passed" : "Failed";
                                        $text_color = $mark >= $passMark ? "success_text" : "error_text";
                                    @endphp
                                    <tr>
                                        <td>{{ $history->quiz->title }}</td>
                                        <td>{{ $date }}</td>
                                        <td>{{ $score }}/{{ $totalScore }}</td>
                                        <td>{{ $mark }}%</td>
                                        <td class="{{ $text_color }}">
                                            @if ($status == 'Passed')
                                                <span class="text-success">{{ __('frontend.Passed') }}</span>
                                            @else
                                                <span class="text-danger">{{ __('frontend.Failed') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('quizResultPreview', $history->id) }}" class="theme_btn small_btn2 text-white">{{ __('student.See Answer Sheet') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center">{{__('frontend.No Quiz History Found')}}</p>
                @endif
            </div>
        </div>
    </div>
</div>
