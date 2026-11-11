@extends('frontend.infixlmstheme.layouts.dashboard_master')
@section('title')Practice Quiz Result Sheet @endsection
@section('mainContent')
<style>
    .pq-sheet-container {
        padding: 25px 0 40px;
    }
    .pq-header-back-btn {
        background: #1f2b40;
        color: #ffffff !important;
        padding: 8px 18px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .pq-header-back-btn:hover {
        opacity: 0.9;
    }
    .pq-filter-pill {
        border: 1px solid #d1d1d1;
        background: #ffffff;
        color: #1f2b40;
        padding: 5px 14px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }
    .pq-filter-pill.active {
        background: var(--system_primery_color);
        color: #ffffff;
        border-color: transparent;
    }
    .pq-tag-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 4px;
        margin-left: 8px;
        vertical-align: middle;
    }
    .pq-explanation-box {
        background: #f8f8fe;
        border-left: 4px solid #1f2b40;
        padding: 12px 16px;
        border-radius: 4px;
        margin-bottom: 16px;
        font-size: 14px;
        color: #374151;
    }
</style>

@php
    $unattempted = $quiz->unattempted_count;
@endphp

<div class="main_content_iner main_content_padding pq-sheet-container">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <div class="result_sheet_wrapper mb_30">
                    <div class="quiz_test_header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h3>{{ __('student.Result Sheet') }} — Practice Quiz</h3>
                        <a href="{{ route('practice-quiz.result', $quiz->id) }}" class="pq-header-back-btn">
                            <i class="ti-arrow-left"></i> {{ __('common.Back') }}
                        </a>
                    </div>

                    <div class="quiz_test_body">
                        <!-- Summary Table matching quiz-result-preview-page-section -->
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th>{{ __('student.Exam Score') }}</th>
                                        <td>{{ $quiz->obtained_marks ?? 0 }} Out of {{ $quiz->total_marks }}</td>
                                        <th>{{ __('frontend.Percentage') }}</th>
                                        <td><strong>{{ $quiz->percentage }}%</strong></td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('quiz.Total Questions') }}</th>
                                        <td>{{ $quiz->total_questions }}</td>
                                        <th>{{ __('frontend.Result') }}</th>
                                        <td>
                                            @if($quiz->pass == 1)
                                                <span class="text-success fw-bold">{{ __('frontend.Pass') }}</span>
                                            @else
                                                <span class="text-danger fw-bold">{{ __('frontend.Failed') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('student.Correct Answer') }}</th>
                                        <td><span class="text-success fw-bold">{{ $quiz->correct_answers }}</span></td>
                                        <th>{{ __('student.Wrong Answer') }}</th>
                                        <td><span class="text-danger fw-bold">{{ $quiz->wrong_answers }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('student.Unattempted') }}</th>
                                        <td><span class="text-warning fw-bold">{{ $unattempted }}</span></td>
                                        <th>Scope</th>
                                        <td>{{ $quiz->scope_label }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Filter Pills -->
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                            <h5 class="mb-0" style="font-weight: 700; color: #1f2b40;">Question Overview</h5>
                            <div class="d-flex gap-2">
                                <button type="button" class="pq-filter-pill active" data-filter="all">All ({{ $quiz->total_questions }})</button>
                                <button type="button" class="pq-filter-pill" data-filter="wrong">Wrong ({{ $quiz->wrong_answers }})</button>
                                <button type="button" class="pq-filter-pill" data-filter="unattempted">Unattempted ({{ $unattempted }})</button>
                            </div>
                        </div>

                        <!-- Question Overview Table matching quiz-result-preview-page-section -->
                        <div class="table-responsive mb-5" style="max-height: 360px; overflow-y: auto;">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="sticky-top bg-white">
                                    <tr>
                                        <th width="8%">#</th>
                                        <th width="22%">Chapter / Lesson</th>
                                        <th width="50%">{{ __('frontend.Question') }}</th>
                                        <th width="20%">{{ __('frontend.Result') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($quiz->details as $index => $detail)
                                        @php
                                            $q = $detail->question;
                                            $scopeInfo = $q->lesson->name ?? ($q->chapter->name ?? '-');
                                            if ($detail->given_answer === null || $detail->given_answer === '') {
                                                $qStatusText = __('student.Unattempted');
                                                $qClass = 'text-warning';
                                                $rowState = 'unattempted';
                                            } elseif ($detail->is_correct == 1) {
                                                $qStatusText = __('student.Correct Answer');
                                                $qClass = 'text-success';
                                                $rowState = 'correct';
                                            } else {
                                                $qStatusText = __('student.Wrong Answer');
                                                $qClass = 'text-danger';
                                                $rowState = 'wrong';
                                            }
                                        @endphp
                                        <tr class="pq-review-row" data-state="{{ $rowState }}">
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $scopeInfo }}</td>
                                            <td>{!! \Illuminate\Support\Str::limit(strip_tags($q->question ?? ''), 140) !!}</td>
                                            <td class="{{ $qClass }} fw-bold">{{ $qStatusText }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Detailed Result Sheet View matching quiz-result-preview-page-section -->
                        <div class="quiz_result_sheet_body">
                            <div class="result_sheet_view">
                                @foreach($quiz->details as $index => $detail)
                                    @php
                                        $q = $detail->question;
                                        $qType = $q->type ?? 'M';
                                        $scopeInfo = $q->lesson->name ?? ($q->chapter->name ?? $quiz->scope_label);
                                        $isUnattempted = ($detail->given_answer === null || $detail->given_answer === '');
                                        $isWrong = (!$isUnattempted && $detail->is_correct != 1);
                                        $cardState = $isUnattempted ? 'unattempted' : ($isWrong ? 'wrong' : 'correct');
                                    @endphp

                                    <div class="single_result_view {{ $isWrong || $isUnattempted ? 'wrong' : 'correct' }}" data-state="{{ $cardState }}">
                                        <h5 class="single_result_count d-flex align-items-center flex-wrap gap-2">
                                            <span>{{ __('frontend.Question') }} : {{ $index + 1 }}</span>
                                            @if($scopeInfo && $scopeInfo !== '-')
                                                <span class="badge bg-light text-dark border" style="font-size: 12px;">{{ $scopeInfo }}</span>
                                            @endif
                                            @if($isUnattempted)
                                                <small class="text-warning fw-bold">({{ __('quiz.Not Submitted') }})</small>
                                            @elseif($detail->is_correct == 1)
                                                <small class="text-success fw-bold">({{ __('student.Correct Answer') }} +{{ $detail->marks_obtained }})</small>
                                            @else
                                                <small class="text-danger fw-bold">({{ __('student.Wrong Answer') }} 0)</small>
                                            @endif
                                        </h5>

                                        <div class="single_result_view_inner">
                                            <h4 class="single_result_question">
                                                {!! $q->question ?? '' !!}
                                            </h4>

                                            @if(!empty($q->image))
                                                <div class="mb-3">
                                                    <img src="{{ asset($q->image) }}" class="img-fluid rounded" alt="Question Image" style="max-height: 260px;">
                                                </div>
                                            @endif

                                            @if(!empty(strip_tags($q->explanation ?? '')))
                                                <div class="pq-explanation-box">
                                                    <strong>{{ __('quiz.Explanation') }}:</strong>
                                                    {!! $q->explanation !!}
                                                </div>
                                            @endif

                                            <div class="row mt-3">
                                                <div class="col-lg-12">
                                                    @if($qType == 'M')
                                                        <ul>
                                                            @foreach($q->options ?? collect() as $option)
                                                                @php
                                                                    $isRightOption = ($option->status == 1);
                                                                    $isSelectedByStudent = ((string)$detail->given_answer === (string)$option->id);
                                                                @endphp

                                                                @if($isRightOption)
                                                                    <li>
                                                                        <label class="primary_checkbox2 d-flex">
                                                                            <input checked type="checkbox" disabled>
                                                                            <span class="checkmark mr_10"></span>
                                                                            <span class="label_name">
                                                                                {{ $option->title }}
                                                                                <span class="pq-tag-badge bg-success text-white">Correct Answer</span>
                                                                                @if($isSelectedByStudent)
                                                                                    <span class="pq-tag-badge bg-primary text-white">Your Answer</span>
                                                                                @endif
                                                                            </span>
                                                                        </label>
                                                                    </li>
                                                                @elseif($isSelectedByStudent)
                                                                    <li>
                                                                        <label class="primary_checkbox2 error_ans d-flex">
                                                                            <input checked type="checkbox" disabled>
                                                                            <span class="checkmark mr_10"></span>
                                                                            <span class="label_name">
                                                                                {{ $option->title }}
                                                                                <span class="pq-tag-badge bg-danger text-white">Your Selection</span>
                                                                            </span>
                                                                        </label>
                                                                    </li>
                                                                @else
                                                                    <li>
                                                                        <label class="primary_checkbox2 d-flex">
                                                                            <input type="checkbox" disabled>
                                                                            <span class="checkmark mr_10"></span>
                                                                            <span class="label_name text-muted">{{ $option->title }}</span>
                                                                        </label>
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    @elseif($qType == 'T')
                                                        <ul>
                                                            @foreach(['T' => 'True', 'F' => 'False'] as $tfVal => $tfLabel)
                                                                @php
                                                                    $isRight = strtoupper((string)$q->true_false) === $tfVal;
                                                                    $isChosen = strtoupper((string)$detail->given_answer) === $tfVal;
                                                                @endphp
                                                                <li>
                                                                    <label class="primary_checkbox2 {{ !$isRight && $isChosen ? 'error_ans' : '' }} d-flex">
                                                                        <input {{ ($isRight || $isChosen) ? 'checked' : '' }} type="checkbox" disabled>
                                                                        <span class="checkmark mr_10"></span>
                                                                        <span class="label_name">
                                                                            {{ $tfLabel }}
                                                                            @if($isRight)
                                                                                <span class="pq-tag-badge bg-success text-white">Correct Answer</span>
                                                                            @elseif($isChosen)
                                                                                <span class="pq-tag-badge bg-danger text-white">Your Selection</span>
                                                                            @endif
                                                                        </span>
                                                                    </label>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @elseif($qType == 'F')
                                                        <p class="mb-2"><strong>Your Answer:</strong>
                                                            <span class="{{ $detail->is_correct == 1 ? 'text-success' : 'text-danger' }}">
                                                                {{ $detail->given_answer ?: 'No answer provided' }}
                                                            </span>
                                                        </p>
                                                        <p class="mb-0"><strong>Correct Answer:</strong>
                                                            <span class="text-success">{{ $q->suitable_words }}</span>
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
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
        $('.pq-filter-pill').on('click', function () {
            var filter = $(this).data('filter');
            $('.pq-filter-pill').removeClass('active');
            $(this).addClass('active');

            if (filter === 'all') {
                $('.pq-review-row, .single_result_view').show();
            } else {
                $('.pq-review-row, .single_result_view').each(function () {
                    $(this).toggle($(this).data('state') === filter);
                });
            }
        });
    });
</script>
@endpush
