@extends('frontend.infixlmstheme.layouts.dashboard_master')
@section('title')Practice Quiz Result @endsection
@section('mainContent')
<style>
    .pq-result-container {
        padding: 25px 0 40px;
    }
    .quiz_score_wrapper .quiz_test_body h3.success {
        font-size: 40px;
        line-height: 1.25;
        margin-bottom: 12px;
    }
    .quiz_score_wrapper .quiz_test_body h3.failed {
        font-size: 36px;
        line-height: 1.25;
        margin-bottom: 12px;
    }
    .quiz_score_wrapper .quiz_test_body p.subtitle {
        font-size: 24px;
        line-height: 1.5;
        margin-bottom: 36px;
    }
    .quiz_score_wrapper .quiz_test_body .score_view_wrapper .single_score_view ul {
        margin-bottom: 36px;
    }
    .quiz_score_wrapper .quiz_test_body .sumit_skip_btns {
        margin-top: 36px;
    }
    .pq-group-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 24px;
    }
    .pq-group-tag {
        background: #f1f5f9;
        color: #1f2b40;
        font-size: 13px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
    }
    #historyDiv {
        scroll-margin-top: 90px;
    }
    @media (max-width: 767px) {
        .quiz_score_wrapper .quiz_test_body h3.success,
        .quiz_score_wrapper .quiz_test_body h3.failed {
            font-size: 26px;
        }
        .quiz_score_wrapper .quiz_test_body p.subtitle {
            font-size: 18px;
            margin-bottom: 24px;
        }
        .quiz_secondary_btn, .quiz_primary_btn {
            height: 48px;
            padding: 0 22px;
            font-size: 16px;
        }
    }
</style>

@php
    $unattempted = $quiz->unattempted_count;
@endphp

<div class="main_content_iner main_content_padding pq-result-container">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <div class="quiz_score_wrapper w-100 mb_30">
                    <div class="quiz_test_header d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h3>{{ __('student.Your Exam Score') }}</h3>
                        <span class="text-white" style="font-size: 15px; font-weight: 600;">
                            Practice Quiz &bull; Attempt #{{ $attempt_number }}
                        </span>
                    </div>

                    <div class="quiz_test_body">
                        @if ($quiz->pass == 1)
                            <h3 class="success">{{ __('student.Congratulations!') }}</h3>
                        @else
                            <h3 class="failed">
                                <span>{{ __('frontend.You have failed') }}.</span> {{ __('frontend.Wishing you luck the next time') }}
                            </h3>
                        @endif

                        <p class="subtitle">
                            {{ __('frontend.You have completed') }} {{ $quiz->course->title ?? 'Practice Quiz' }}
                        </p>

                        @if($quiz->scope_label)
                            <div class="pq-group-tags">
                                <span class="pq-group-tag">{{ $quiz->scope_label }}</span>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="score_view_wrapper">
                                    <div class="single_score_view">
                                        <ul class="quiz_exam_score_details">
                                            <li class="correct">
                                                <label class="primary_checkbox2 d-flex">
                                                    <input checked type="checkbox" disabled>
                                                    <div class="icon">
                                                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M1.61719 9.18847L5.78764 13.4892C7.75009 7.85194 9.38446 5.37824 13.0859 2.02051" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                    </div>
                                                    <span class="label_name">{{ $quiz->correct_answers }} {{ __('student.Correct Answer') }}</span>
                                                </label>
                                            </li>

                                            <li class="wrong">
                                                <label class="primary_checkbox2 error_ans d-flex">
                                                    <input checked type="checkbox" disabled>
                                                    <div class="icon">
                                                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M1.75781 10.2427L10.2431 1.75739" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M10.2431 10.2426L1.75781 1.75732" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                    </div>
                                                    <span class="label_name">{{ $quiz->wrong_answers }} {{ __('student.Wrong Answer') }}</span>
                                                </label>
                                            </li>

                                            <li class="unattempted">
                                                <label class="primary_checkbox2 warning_ans d-flex">
                                                    <input checked type="checkbox" disabled>
                                                    <div class="icon" style="background: #6c757d; border-color: #6c757d;">
                                                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M2 6H10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                        </svg>
                                                    </div>
                                                    <span class="label_name">{{ $unattempted }} {{ __('student.Unattempted') }}</span>
                                                </label>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="single_score_view d-flex">
                                        <div>
                                            <div>
                                                <p>
                                                    {{ __('student.Exam Score') }}: {{ $quiz->obtained_marks }} Out of {{ $quiz->total_marks }}
                                                    ({{ $quiz->percentage }}%)
                                                </p>
                                            </div>
                                            <div>
                                                <p>
                                                    {{ __('frontend.Result') }}:
                                                    <span class="{{ $quiz->pass == 1 ? 'text-success fw-bold' : 'text-danger fw-bold' }}">
                                                        {{ $quiz->pass == 1 ? __('frontend.Passed') : __('frontend.Failed') }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="sumit_skip_btns d-flex gap-3 align-items-center flex-wrap">
                            <a href="{{ $quiz->course ? courseDetailsUrl($quiz->course->id, $quiz->course->type, $quiz->course->slug) : route('practice-quiz.history') }}"
                               class="quiz_secondary_btn border-0 d-inline-flex align-items-center gap-2">
                                <i class="ti-arrow-left"></i> {{ __('common.Back') }}
                            </a>

                            <a href="{{ route('practice-quiz.setup', $quiz->course_id) }}"
                               class="quiz_primary_btn border-0 d-inline-flex align-items-center">
                                Take Another Quiz
                            </a>

                            @if(isset($preResult) && count($preResult) > 0)
                                <button type="button"
                                        class="theme_line_btn showHistoryBtn quiz_secondary_btn border-0">
                                    {{ __('frontend.View History') }}
                                </button>
                            @endif

                            <a href="{{ route('practice-quiz.answer-sheet', $quiz->id) }}"
                               class="font_1 font_16 f_w_600 theme_text3 quiz_secondary_btn d-inline-flex align-items-center">
                                {{ __('student.See Answer Sheet') }}
                            </a>
                        </div>

                        @if(isset($preResult) && count($preResult) > 0)
                            <div id="historyDiv" class="pt-5" style="display: none;">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>{{ __('common.Date') }}</th>
                                                <th>{{ __('quiz.Attempted') }}</th>
                                                <th>{{ __('quiz.Not Attempted') }}</th>
                                                <th>{{ __('quiz.Marks') }}</th>
                                                <th>{{ __('quiz.Percentage') }}</th>
                                                <th>{{ __('common.Rating') }}</th>
                                                <th>{{ __('common.Details') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($preResult as $pre)
                                                @php
                                                    $attemptedCount = (int)$pre->correct_answers + (int)$pre->wrong_answers;
                                                    $unattemptedRow = max(0, (int)$pre->total_questions - $attemptedCount);
                                                @endphp
                                                <tr>
                                                    <td>{{ $pre->created_at->format('d M Y, h:i A') }}</td>
                                                    <td>{{ $attemptedCount }}</td>
                                                    <td>{{ $unattemptedRow }}</td>
                                                    <td>{{ $pre->obtained_marks }}/{{ $pre->total_marks }}</td>
                                                    <td>{{ $pre->percentage }}%</td>
                                                    <td class="{{ $pre->pass == 1 ? 'text-success fw-bold' : 'text-danger fw-bold' }}">
                                                        {{ $pre->pass == 1 ? __('frontend.Passed') : __('frontend.Failed') }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('practice-quiz.answer-sheet', $pre->id) }}"
                                                           class="theme_btn small_btn2 text-white">
                                                            {{ __('student.See Answer Sheet') }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function () {
        $('.showHistoryBtn').on('click', function () {
            $('#historyDiv').slideToggle(250, function () {
                if ($(this).is(':visible')) {
                    this.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    });
</script>
@endpush
