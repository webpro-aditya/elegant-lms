@extends('frontend.infixlmstheme.layouts.dashboard_master')
@section('title')Practice Quiz Attempt @endsection
@section('mainContent')
<style>
    .pq-attempt-container {
        padding: 25px 0 40px;
    }
    /* Match _quiz_navigation.blade.php styles */
    .quiz-progress-bar {
        height: 6px;
        background: #e5e5e5;
        border-radius: 10px;
        margin-top: 10px;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        width: 0%;
        background: linear-gradient(90deg, #ff3c3c, #3c7cff);
        transition: 0.3s ease;
    }
    .question-header-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .q-title {
        font-weight: 700;
        font-size: 16px;
        color: #1f2b40;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }
    .pq-scope-badge {
        background: #f1f5f9;
        color: #475569;
        font-size: 12px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 4px;
    }
    .pq-marks-badge {
        background: #eef2ff;
        color: #1f2b40;
        font-size: 12px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 4px;
    }
    #questionToggleBtn {
        background: linear-gradient(90deg, #ff3c3c, #3c7cff);
        color: #fff;
        border: none;
        padding: 8px 14px;
        border-radius: 8px;
        font-weight: bold;
        font-size: 13px;
        cursor: pointer;
    }
    .jump-wrapper {
        position: relative;
    }
    .question-popover {
        position: absolute;
        top: 115%;
        right: 0;
        width: 310px;
        max-width: 90vw;
        background: #fff;
        border-radius: 12px;
        padding: 14px;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        border: 1px solid #e5e7eb;
        z-index: 9999;
    }
    .question-popover::before {
        content: '';
        position: absolute;
        top: -6px;
        right: 20px;
        border-width: 6px;
        border-style: solid;
        border-color: transparent transparent #fff transparent;
    }
    .question-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 6px;
        max-height: 360px;
        overflow: auto;
    }
    .q-box {
        padding: 8px;
        text-align: center;
        border-radius: 6px;
        background: #eee;
        color: #1f2b40;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        transition: 0.2s;
    }
    .q-box:hover {
        background: #dbeafe;
    }
    .q-box.answered {
        background: #22c55e;
        color: #fff;
    }
    .q-box.active {
        background: #3c7cff;
        color: #fff;
    }
    .sumit_skip_btns {
        margin-top: 20px;
        gap: 14px;
    }
    @media (max-width: 767px) {
        .quiz_secondary_btn, .quiz_primary_btn {
            font-size: 16px;
            padding: 0 22px;
            height: 48px;
            line-height: 48px;
        }
        .question-popover {
            width: 260px;
        }
    }
</style>

<div class="main_content_iner main_content_padding pq-attempt-container">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <div class="quiz_questions_wrapper mb_30">
                    <!-- Header bar matching fullscreen_video & quizStart -->
                    <div class="quiz_test_header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="quiz_header_left">
                            <h3>Practice Quiz: {{ $quiz->course->title ?? 'Practice Quiz' }}</h3>
                        </div>
                        <div class="quiz_header_right">
                            <span class="question_time">
                                <span class="me-2">{{ __('frontend.Remaining') }}:</span>
                                <span id="timer">{{ sprintf('%02d:00', max(1, (int)$quiz->estimated_time)) }}</span>
                                {{ __('quiz.Min') }}
                            </span>
                        </div>
                    </div>

                    <form action="{{ route('practice-quiz.submit') }}" method="POST" id="quiz_submit_form">
                        @csrf
                        <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">

                        <div class="quiz_test_body">
                            <div class="tabControl">
                                <div class="row">
                                    <!-- Navigation Header + Jump Grid + Progress Bar -->
                                    <div class="col-xl-12">
                                        @php
                                            $totalQuestions = count($quiz->details);
                                        @endphp
                                        <div class="question_list_header">
                                            <div class="question-header-flex">
                                                <div class="q-title">
                                                    <span>
                                                        {{ __('quiz.Question') }}
                                                        <span id="currentNumber">1</span>
                                                        {{ __('common.out of') }} {{ $totalQuestions }}
                                                    </span>
                                                    <span class="pq-scope-badge" id="currentScopeBadge">{{ $quiz->scope_label }}</span>
                                                    <span class="pq-marks-badge" id="currentMarksBadge"></span>
                                                </div>

                                                <div class="jump-wrapper">
                                                    <button type="button" id="questionToggleBtn">
                                                        Jump &#9662;
                                                    </button>

                                                    <div id="questionPopover" class="question-popover d-none">
                                                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom" style="font-size: 11px; color: #64748b; font-weight: 600;">
                                                            <span class="d-inline-flex align-items-center gap-1"><span style="width: 10px; height: 10px; background: #3c7cff; border-radius: 3px; display: inline-block;"></span> Current</span>
                                                            <span class="d-inline-flex align-items-center gap-1"><span style="width: 10px; height: 10px; background: #22c55e; border-radius: 3px; display: inline-block;"></span> Answered</span>
                                                            <span class="d-inline-flex align-items-center gap-1"><span style="width: 10px; height: 10px; background: #eee; border-radius: 3px; display: inline-block; border: 1px solid #cbd5e1;"></span> Unseen</span>
                                                        </div>
                                                        <div class="question-grid">
                                                            @foreach ($quiz->details as $index => $detail)
                                                                <div class="q-box qbox_{{ $detail->id }} {{ $index == 0 ? 'active' : '' }}"
                                                                     data-id="{{ $detail->id }}"
                                                                     data-index="{{ $index }}">
                                                                    {{ $index + 1 }}
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <div class="mt-3 pt-2 border-top text-center">
                                                            <button type="button" class="btn btn-sm w-100 text-white fw-semibold trigger-submit-modal" style="background: #1f2b40;">
                                                                {{ __('frontend.Submit Quiz') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="quiz-progress-bar">
                                                <div class="progress-fill" id="progressFill" style="width: {{ $totalQuestions > 0 ? round(100 / $totalQuestions, 2) : 100 }}%;"></div>
                                            </div>
                                        </div>

                                        <!-- Hidden Bootstrap Tab Links -->
                                        <div class="nav d-none" role="tablist">
                                            @foreach ($quiz->details as $index => $detail)
                                                <a class="nav-link questionLink link_{{ $detail->id }} {{ $index == 0 ? 'active' : '' }}"
                                                   data-bs-toggle="tab"
                                                   href="#pills-{{ $detail->id }}"
                                                   role="tab"
                                                   data-qus="{{ $detail->id }}">
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Single-Question Tab Panes -->
                                    <div class="col-md-12">
                                        <div class="tab-content" id="pills-tabContent">
                                            @foreach($quiz->details as $key => $detail)
                                                @php
                                                    $q = $detail->question;
                                                    $qMarks = (int)($q->marks ?? 1);
                                                    $scopeInfo = $q->lesson->name ?? ($q->chapter->name ?? $quiz->scope_label);
                                                @endphp
                                                <div class="tab-pane fade {{ $key == 0 ? 'active show' : '' }} singleQuestion"
                                                     data-qus-id="{{ $detail->id }}"
                                                     data-qus-type="{{ $q->type }}"
                                                     data-scope-title="{{ $scopeInfo }}"
                                                     data-marks-label="{{ $qMarks }} {{ $qMarks == 1 ? 'Mark' : 'Marks' }}"
                                                     id="pills-{{ $detail->id }}"
                                                     role="tabpanel">

                                                    <div class="multypol_qustion mb_30">
                                                        <h4 class="font_18 f_w_700 mb-0">
                                                            {!! $q->question !!}
                                                        </h4>
                                                    </div>

                                                    @if(!empty($q->image))
                                                        <div class="ques_thumb mb_30 mt-3">
                                                            <img src="{{ asset($q->image) }}" class="img-fluid rounded" alt="Question Image" style="max-height: 320px;">
                                                        </div>
                                                    @endif

                                                    @if($q->type == 'M')
                                                        <ul class="quiz_select">
                                                            @foreach($q->questionOptionsInRandom as $option)
                                                                <li>
                                                                    <label class="primary_bulet_checkbox d-flex">
                                                                        <input class="quizAns"
                                                                               name="answer_{{ $detail->id }}"
                                                                               type="radio"
                                                                               value="{{ $option->id }}">
                                                                        <span class="checkmark mr_10"></span>
                                                                        <span class="label_name">{{ $option->title }}</span>
                                                                    </label>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @elseif($q->type == 'T')
                                                        <ul class="quiz_select">
                                                            <li>
                                                                <label class="primary_bulet_checkbox d-flex">
                                                                    <input class="quizAns" name="answer_{{ $detail->id }}" type="radio" value="T">
                                                                    <span class="checkmark mr_10"></span>
                                                                    <span class="label_name">{{ __('common.True') ?? 'True' }}</span>
                                                                </label>
                                                            </li>
                                                            <li>
                                                                <label class="primary_bulet_checkbox d-flex">
                                                                    <input class="quizAns" name="answer_{{ $detail->id }}" type="radio" value="F">
                                                                    <span class="checkmark mr_10"></span>
                                                                    <span class="label_name">{{ __('common.False') ?? 'False' }}</span>
                                                                </label>
                                                            </li>
                                                        </ul>
                                                    @elseif($q->type == 'F')
                                                        <div class="mb_30" style="position: relative; z-index: 2147483647;">
                                                            <input class="primary_input3 fill-in-the-blanks-input"
                                                                   type="text"
                                                                   name="answer_{{ $detail->id }}"
                                                                   placeholder="Type your answer here..."
                                                                   style="position: relative !important; z-index: 2147483647 !important; pointer-events: auto !important; cursor: text !important; user-select: auto !important; background-color: #fff !important;"
                                                                   onmousedown="event.stopPropagation(); this.focus();"
                                                                   onclick="event.stopPropagation(); this.focus();">
                                                        </div>
                                                    @endif

                                                    <!-- Action Buttons matching existing Quiz UI -->
                                                    <div class="sumit_skip_btns d-flex align-items-center flex-wrap mb_20">
                                                        @if ($key + 1 < $totalQuestions)
                                                            <span class="quiz_primary_btn mr_20 pq-next-btn" role="button">
                                                                {{ __('student.Continue') }}
                                                            </span>
                                                            <span class="font_1 font_16 f_w_600 theme_text3 submit_q_btn pq-skip-btn quiz_secondary_btn" role="button">
                                                                {{ __('student.Skip') }} {{ __('frontend.Question') }}
                                                            </span>
                                                        @else
                                                            <button type="button" class="quiz_primary_btn mr_20 trigger-submit-modal">
                                                                {{ __('student.Submit') }}
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include(theme('partials._quiz_submit_confirm_modal'))
@endsection

@push('js')
<script>
    $(document).ready(function () {
        var total = {{ count($quiz->details) }};
        var timeRemaining = {{ max(1, (int)$quiz->estimated_time) * 60 }};
        var toggleBtn = document.getElementById('questionToggleBtn');
        var popover = document.getElementById('questionPopover');

        function formatTime(sec) {
            var minutes = Math.floor(sec / 60);
            var seconds = sec % 60;
            return (minutes < 10 ? "0" : "") + minutes + ":" + (seconds < 10 ? "0" : "") + seconds;
        }

        $('#timer').text(formatTime(timeRemaining));
        var timerInterval = setInterval(function () {
            timeRemaining--;
            $('#timer').text(formatTime(Math.max(0, timeRemaining)));
            if (timeRemaining <= 0) {
                clearInterval(timerInterval);
                $('#quiz_submit_form')[0].submit();
            }
        }, 1000);

        if (toggleBtn && popover) {
            toggleBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                popover.classList.toggle('d-none');
            });

            document.addEventListener('click', function (e) {
                if (!popover.contains(e.target) && !toggleBtn.contains(e.target)) {
                    popover.classList.add('d-none');
                }
            });
        }

        function updateUI(detailId) {
            var links = Array.from(document.querySelectorAll('.questionLink'));
            var index = links.findIndex(function (l) {
                return l.getAttribute('data-qus') == detailId;
            });
            if (index < 0) index = 0;

            $('#currentNumber').text(index + 1);
            var percent = total > 0 ? ((index + 1) / total) * 100 : 100;
            $('#progressFill').css('width', percent + '%');

            document.querySelectorAll('.q-box').forEach(function (b) {
                b.classList.remove('active');
            });
            var activeBox = document.querySelector('.qbox_' + detailId);
            if (activeBox) activeBox.classList.add('active');

            var pane = document.getElementById('pills-' + detailId);
            if (pane) {
                $('#currentScopeBadge').text(pane.getAttribute('data-scope-title') || '');
                $('#currentMarksBadge').text(pane.getAttribute('data-marks-label') || '');
            }
        }

        function switchQuestion(detailId) {
            var tabEl = document.querySelector('.link_' + detailId);
            if (!tabEl) return;
            if (window.bootstrap && bootstrap.Tab) {
                new bootstrap.Tab(tabEl).show();
            } else {
                $(tabEl).tab('show');
            }
            updateUI(detailId);
        }

        document.querySelectorAll('.q-box').forEach(function (box) {
            box.addEventListener('click', function () {
                var id = this.getAttribute('data-id');
                switchQuestion(id);
                if (popover) popover.classList.add('d-none');
            });
        });

        document.querySelectorAll('.questionLink').forEach(function (link) {
            link.addEventListener('shown.bs.tab', function () {
                updateUI(this.getAttribute('data-qus'));
            });
        });

        function markAnswered(detailId) {
            var box = document.querySelector('.qbox_' + detailId);
            if (box) box.classList.add('answered');
        }

        function unmarkAnswered(detailId) {
            var box = document.querySelector('.qbox_' + detailId);
            if (box) box.classList.remove('answered');
        }

        $(document).on('change input', '.singleQuestion input', function () {
            var $pane = $(this).closest('.singleQuestion');
            var detailId = $pane.data('qus-id');
            var hasValue = false;

            if ($pane.find('input[type="radio"]:checked').length > 0) {
                hasValue = true;
            }
            $pane.find('input[type="text"]').each(function () {
                if ($(this).val().trim() !== '') hasValue = true;
            });

            if (hasValue) markAnswered(detailId);
            else unmarkAnswered(detailId);
        });

        $(document).on('click', '.pq-next-btn, .pq-skip-btn', function () {
            var $pane = $(this).closest('.singleQuestion');
            var currentId = String($pane.data('qus-id'));
            var links = Array.from(document.querySelectorAll('.questionLink'));
            var index = links.findIndex(function (l) {
                return l.getAttribute('data-qus') == currentId;
            });
            if (index >= 0 && links[index + 1]) {
                switchQuestion(links[index + 1].getAttribute('data-qus'));
            }
        });

        $(document).on('click', '.trigger-submit-modal', function (e) {
            e.preventDefault();
            if (popover) popover.classList.add('d-none');
            var modalEl = document.getElementById('submitConfirmModal');
            if (modalEl && window.bootstrap && bootstrap.Modal) {
                new bootstrap.Modal(modalEl).show();
            } else if ($('#submitConfirmModal').length && typeof $('#submitConfirmModal').modal === 'function') {
                $('#submitConfirmModal').modal('show');
            } else if (confirm('{{ __("frontend.Are you sure?") }}')) {
                clearInterval(timerInterval);
                $('#quiz_submit_form')[0].submit();
            }
        });

        $('#QuizSubmitBtn').on('click', function () {
            clearInterval(timerInterval);
            $('#quiz_submit_form')[0].submit();
        });

        // Anti-piracy script bypass for Fill in the Blanks inputs
        document.querySelectorAll('.fill-in-the-blanks-input').forEach(function (input) {
            ['selectstart', 'copy', 'paste', 'cut', 'contextmenu', 'keydown', 'keyup', 'keypress'].forEach(function (ev) {
                input.addEventListener(ev, function (e) {
                    e.stopPropagation();
                }, false);
            });
        });

        var firstPane = document.querySelector('.singleQuestion.active');
        if (firstPane) {
            updateUI(firstPane.getAttribute('data-qus-id'));
        }
    });
</script>
@endpush
