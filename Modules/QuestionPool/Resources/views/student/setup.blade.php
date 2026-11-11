@extends('frontend.infixlmstheme.layouts.dashboard_master')
@section('title')Practice Quiz Setup @endsection

@section('mainContent')
<style>
    .pq-setup-container {
        padding: 25px 0 40px;
    }
    .pq-scope-list {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }
    @media (max-width: 767px) {
        .pq-scope-list {
            grid-template-columns: 1fr;
        }
    }
    .pq-scope-card {
        border: 1px solid #d1d1d1;
        border-radius: 4px;
        padding: 14px 16px;
        background: #ffffff;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.2s ease;
        margin: 0;
    }
    .pq-scope-card:hover {
        border-color: var(--system_primery_gredient2, #18518C);
        background: #f8fafc;
    }
    .scope-input:checked + .pq-scope-card {
        border-color: var(--system_primery_gredient1, #D12027);
        background: #fff8f8;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }
    .pq-scope-dot {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 2px solid #cbd5e1;
        display: inline-block;
        flex-shrink: 0;
        position: relative;
    }
    .scope-input:checked + .pq-scope-card .pq-scope-dot {
        border-color: var(--system_primery_gredient1, #D12027);
    }
    .scope-input:checked + .pq-scope-card .pq-scope-dot::after {
        content: '';
        position: absolute;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--system_primery_gredient1, #D12027);
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    .ms-panel {
        border: 1px solid #d1d1d1;
        border-radius: 4px;
        background: #ffffff;
        max-height: 230px;
        overflow-y: auto;
        margin-top: 6px;
    }
    .ms-panel label {
        display: flex;
        align-items: center;
        padding: 10px 14px;
        cursor: pointer;
        margin: 0;
        transition: background 0.15s;
        gap: 10px;
        font-size: 14px;
        color: #202e3b;
        border-bottom: 1px solid #f1f5f9;
    }
    .ms-panel label:last-child { border-bottom: none; }
    .ms-panel label:hover { background: #f8fafc; }
    .ms-panel input[type="checkbox"] {
        width: 17px;
        height: 17px;
        accent-color: var(--system_primery_gredient1, #D12027);
        flex-shrink: 0;
    }
    .ms-panel .ms-group-header {
        padding: 8px 14px;
        font-weight: 700;
        font-size: 12px;
        color: #475569;
        background: #f8f8fe;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        position: sticky;
        top: 0;
        z-index: 1;
        border-bottom: 1px solid #e2e8f0;
    }
    .ms-selected-count {
        font-size: 12px;
        color: #1f2b40;
        font-weight: 600;
        margin-top: 6px;
    }
    .pq-summary-card {
        background: #f8f8fe;
        border: 1px solid #e9e7f7;
        border-radius: 4px;
        padding: 24px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .pq-stepper {
        display: inline-flex;
        align-items: center;
        border: 1px solid #d1d1d1;
        background: #ffffff;
        border-radius: 4px;
        overflow: hidden;
    }
    .pq-stepper button {
        width: 34px;
        height: 34px;
        border: none;
        background: #f8f9fa;
        color: #1f2b40;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
    }
    .pq-stepper button:hover {
        background: #e2e8f0;
    }
    .pq-stepper input {
        width: 48px;
        height: 34px;
        border: none;
        text-align: center;
        font-weight: 700;
        font-size: 15px;
        color: #1f2b40;
        background: #fff;
        outline: none;
    }
    .pq-range-slider {
        width: 100%;
        accent-color: var(--system_primery_gredient1, #D12027);
        cursor: pointer;
    }
    .pq-action-bar {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-top: 28px;
        padding-top: 24px;
        border-top: 1px solid #e9e7f7;
    }
    .pq-header-back-btn {
        background: rgba(255, 255, 255, 0.16);
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.35);
        padding: 8px 18px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }
    .pq-header-back-btn:hover {
        background: rgba(255, 255, 255, 0.28);
    }
    @media (max-width: 767px) {
        .pq-action-bar .quiz_primary_btn,
        .pq-action-bar .quiz_secondary_btn {
            width: 100%;
            height: 48px;
            font-size: 16px;
        }
    }

    #historyDiv {
        scroll-margin-top: 90px;
    }
</style>

<div class="main_content_iner main_content_padding pq-setup-container">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <div class="quiz_test_wrapper mb_30">
                    <div class="quiz_test_header d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h3>Practice Quiz Setup — {{ $course->title }}</h3>
                        </div>
                        <a href="{{ courseDetailsUrl($course->id, $course->type, $course->slug) }}" class="pq-header-back-btn">
                            <i class="ti-arrow-left"></i> {{ __('common.Back') }}
                        </a>
                    </div>

                    <div class="quiz_test_body">
                        <form action="{{ route('practice-quiz.start') }}" method="POST" id="practice_setup_form">
                            @csrf
                            <input type="hidden" name="course_id" value="{{ $course->id }}" id="setup_course_id">
                            <input type="hidden" name="question_count" id="setup_question_count" value="{{ $preselectQuestionCount ?? 10 }}">

                            <div class="row row-gap-4">
                                <!-- Left: Study Scope & Chapter / Lesson Selection -->
                                <div class="col-lg-7">
                                    <h4 class="mb-3" style="font-size: 18px; font-weight: 700; color: #1f2b40;">
                                        1. Select Study Scope
                                    </h4>

                                    <div class="pq-scope-list">
                                        <label style="position: relative; margin: 0;">
                                            <input type="radio" name="scope" value="course" class="scope-input" style="position: absolute; opacity: 0; width: 0; height: 0;" checked>
                                            <div class="pq-scope-card">
                                                <span class="pq-scope-dot"></span>
                                                <span class="fw-semibold" style="font-size: 14px; color: #1f2b40;">Entire Course</span>
                                            </div>
                                        </label>

                                        <label style="position: relative; margin: 0;">
                                            <input type="radio" name="scope" value="chapter" class="scope-input" style="position: absolute; opacity: 0; width: 0; height: 0;">
                                            <div class="pq-scope-card">
                                                <span class="pq-scope-dot"></span>
                                                <span class="fw-semibold" style="font-size: 14px; color: #1f2b40;">Specific Chapters</span>
                                            </div>
                                        </label>

                                        <label style="position: relative; margin: 0;">
                                            <input type="radio" name="scope" value="lesson" class="scope-input" style="position: absolute; opacity: 0; width: 0; height: 0;">
                                            <div class="pq-scope-card">
                                                <span class="pq-scope-dot"></span>
                                                <span class="fw-semibold" style="font-size: 14px; color: #1f2b40;">Specific Lessons</span>
                                            </div>
                                        </label>
                                    </div>

                                    {{-- Chapter multi-select --}}
                                    <div id="chapter_selection" class="d-none mb-3">
                                        <label class="fw-bold text-dark mb-1" style="font-size: 14px;">
                                            Select Chapters <small class="text-muted">(select one or more)</small>
                                        </label>
                                        <div class="ms-panel" id="chapter_panel">
                                            @foreach($course->chapters as $chapter)
                                                <label>
                                                    <input type="checkbox" name="chapter_ids[]" value="{{ $chapter->id }}" class="chapter-checkbox">
                                                    {{ $chapter->name }}
                                                </label>
                                            @endforeach
                                        </div>
                                        <div class="ms-selected-count" id="chapter_selected_count"></div>
                                    </div>

                                    {{-- Lesson multi-select --}}
                                    <div id="lesson_selection" class="d-none">
                                        <label class="fw-bold text-dark mb-1" style="font-size: 14px;">
                                            Select Lessons <small class="text-muted">(select one or more)</small>
                                        </label>
                                        <div class="ms-panel" id="lesson_panel">
                                            @foreach($course->chapters as $chapter)
                                                <div class="ms-group-header" data-chapter-id="{{ $chapter->id }}">{{ $chapter->name }}</div>
                                                @foreach($chapter->lessons as $lesson)
                                                    <label data-chapter-id="{{ $chapter->id }}">
                                                        <input type="checkbox" name="lesson_ids[]" value="{{ $lesson->id }}" class="lesson-checkbox">
                                                        {{ $lesson->name }}
                                                    </label>
                                                @endforeach
                                            @endforeach
                                        </div>
                                        <div class="ms-selected-count" id="lesson_selected_count"></div>
                                    </div>
                                </div>

                                <!-- Right: Quiz Info & Length Controls matching .quiz_test_info -->
                                <div class="col-lg-5">
                                    <div class="pq-summary-card">
                                        <div>
                                            <h4 class="mb-3" style="font-size: 18px; font-weight: 700; color: #1f2b40;">
                                                2. Quiz Summary
                                            </h4>
                                            <ul class="quiz_test_info mb-4" style="margin-bottom: 20px;">
                                                <li style="grid-template-columns: 140px auto;">
                                                    <span>Available Pool <span>:</span></span>
                                                    <strong id="available_pool_label" class="text-primary">Scanning...</strong>
                                                </li>
                                                <li style="grid-template-columns: 140px auto; align-items: center;">
                                                    <span>{{ __('frontend.Questions') }} <span>:</span></span>
                                                    <div class="pq-stepper">
                                                        <button type="button" id="btn_minus">&minus;</button>
                                                        <input type="text" id="question_count_display" value="{{ $preselectQuestionCount ?? 10 }}">
                                                        <button type="button" id="btn_plus">+</button>
                                                    </div>
                                                </li>
                                                <li style="grid-template-columns: 140px auto;">
                                                    <span>{{ __('frontend.Duration') }} <span>:</span></span>
                                                    <strong id="setup_estimated_time">{{ $preselectQuestionCount ?? 10 }} {{ __('quiz.Min') }}</strong>
                                                </li>
                                            </ul>

                                            <div class="mb-2">
                                                <input type="range" class="pq-range-slider" id="question_range" min="1" max="20" value="{{ $preselectQuestionCount ?? 10 }}">
                                                <div class="d-flex justify-content-between text-muted" style="font-size: 12px; font-weight: 600;">
                                                    <span>1 (Min)</span>
                                                    <span id="range_max_label">20 (Max)</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pt-3 mt-3 border-top" style="font-size: 13px; color: #687083;">
                                            <div id="availability_status_note">Checking available questions...</div>
                                            <div class="mt-1">Pass score: <strong>50%</strong> &bull; Unlimited retakes enabled.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="pq-action-bar">
                                <button type="submit" class="quiz_primary_btn" id="start_btn">
                                    {{ __('frontend.Start Quiz') }}
                                </button>
                                @if(isset($history) && $history->count() > 0)
                                    <button type="button" class="quiz_secondary_btn" id="toggle_setup_history_btn">
                                        {{ __('frontend.View History') }}
                                    </button>
                                @else
                                    <a href="{{ route('practice-quiz.history') }}" class="quiz_secondary_btn">
                                        {{ __('frontend.View History') }}
                                    </a>
                                @endif
                            </div>
                        </form>

                        @if(isset($history) && $history->count() > 0)
                            <div id="historyDiv" class="pt-5" style="display: none;">
                                <h4 class="mb-3" style="font-size: 18px; font-weight: 700; color: #1f2b40;">Recent Attempts</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>{{ __('common.Date') }}</th>
                                                <th>Scope</th>
                                                <th>{{ __('quiz.Marks') }}</th>
                                                <th>{{ __('quiz.Percentage') }}</th>
                                                <th>{{ __('common.Rating') }}</th>
                                                <th>{{ __('common.Details') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($history as $item)
                                                <tr>
                                                    <td>{{ $item->created_at->format('d M Y, h:i A') }}</td>
                                                    <td>{{ $item->scope_label }}</td>
                                                    <td>{{ $item->obtained_marks }}/{{ $item->total_marks }}</td>
                                                    <td>{{ $item->percentage }}%</td>
                                                    <td class="{{ $item->pass == 1 ? 'text-success fw-bold' : 'text-danger fw-bold' }}">
                                                        {{ $item->pass == 1 ? __('frontend.Passed') : __('frontend.Failed') }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('practice-quiz.answer-sheet', $item->id) }}"
                                                           class="font_1 font_16 f_w_600 theme_text3 submit_q_btn">
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
    $(document).ready(function() {
        var maxAvailable = 0;
        var preselectQuestionCount = @json($preselectQuestionCount ?? 10);
        var currentCount = parseInt(preselectQuestionCount, 10);
        if (isNaN(currentCount) || currentCount < 1) currentCount = 10;

        var preselectScope = @json($preselectScope ?? 'course');
        var preselectChapterIds = @json($preselectChapterIds ?? []);
        var preselectLessonIds = @json($preselectLessonIds ?? []);

        function setCount(val) {
            val = parseInt(val, 10);
            if (isNaN(val) || val < 1) val = 1;
            if (maxAvailable > 0 && val > maxAvailable) val = maxAvailable;
            currentCount = val;
            document.getElementById('setup_question_count').value = currentCount;
            document.getElementById('question_count_display').value = currentCount;
            document.getElementById('question_range').value = currentCount;
            document.getElementById('setup_estimated_time').innerText = currentCount + ' {{ __("quiz.Min") }}';
        }

        function getSelectedChapterIds() {
            var ids = [];
            $('.chapter-checkbox:checked').each(function() {
                ids.push($(this).val());
            });
            return ids;
        }

        function getSelectedLessonIds() {
            var ids = [];
            $('.lesson-checkbox:checked').each(function() {
                ids.push($(this).val());
            });
            return ids;
        }

        function updateSelectionCounts() {
            var chCount = getSelectedChapterIds().length;
            var lsCount = getSelectedLessonIds().length;
            $('#chapter_selected_count').text(chCount > 0 ? chCount + ' chapter(s) selected' : '');
            $('#lesson_selected_count').text(lsCount > 0 ? lsCount + ' lesson(s) selected' : '');
        }

        function updateAvailability() {
            var scope = $('input[name="scope"]:checked').val();
            var course_id = $('#setup_course_id').val();
            var chapter_ids = getSelectedChapterIds();
            var lesson_ids = getSelectedLessonIds();

            if (scope == 'chapter' && chapter_ids.length === 0) {
                $('#available_pool_label').text('Select chapter(s)').removeClass('text-success text-danger').addClass('text-primary');
                $('#availability_status_note').text('Please select one or more chapters to check available questions.');
                $('#start_btn').prop('disabled', true);
                return;
            }
            if (scope == 'lesson' && lesson_ids.length === 0) {
                $('#available_pool_label').text('Select lesson(s)').removeClass('text-success text-danger').addClass('text-primary');
                $('#availability_status_note').text('Please select one or more lessons to check available questions.');
                $('#start_btn').prop('disabled', true);
                return;
            }

            $('#available_pool_label').text('Scanning...').removeClass('text-success text-danger').addClass('text-primary');
            $('#start_btn').prop('disabled', true);

            var ajaxData = {
                scope: scope,
                course_id: course_id
            };
            if (scope == 'chapter') {
                ajaxData.chapter_ids = chapter_ids;
            } else if (scope == 'lesson') {
                ajaxData.lesson_ids = lesson_ids;
            }

            $.ajax({
                url: "{{ route('practice-quiz.available-count') }}",
                type: "GET",
                data: ajaxData,
                success: function(res) {
                    maxAvailable = res.count;
                    if (maxAvailable > 0) {
                        $('#available_pool_label').text(maxAvailable + ' Questions').removeClass('text-primary text-danger').addClass('text-success');
                        $('#availability_status_note').text('Ready to generate your practice quiz.');
                        document.getElementById('question_range').max = maxAvailable;
                        document.getElementById('range_max_label').innerText = maxAvailable + ' (Max)';

                        if (currentCount > maxAvailable) {
                            setCount(maxAvailable);
                        } else {
                            setCount(currentCount);
                        }
                        $('#start_btn').prop('disabled', false);
                    } else {
                        $('#available_pool_label').text('0 Questions').removeClass('text-primary text-success').addClass('text-danger');
                        $('#availability_status_note').text('No questions available for the selected scope.');
                        document.getElementById('question_range').max = 1;
                        document.getElementById('range_max_label').innerText = '0 (Max)';
                        $('#start_btn').prop('disabled', true);
                    }
                },
                error: function() {
                    $('#available_pool_label').text('Error').removeClass('text-primary text-success').addClass('text-danger');
                    $('#start_btn').prop('disabled', true);
                }
            });
        }

        // Anti-piracy script bypasses for interactive setup controls
        document.getElementById('btn_minus').addEventListener('click', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            if (currentCount > 1) {
                setCount(currentCount - 1);
            }
            return false;
        }, true);

        document.getElementById('btn_plus').addEventListener('click', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            if (currentCount < maxAvailable) {
                setCount(currentCount + 1);
            }
            return false;
        }, true);

        document.getElementById('question_range').addEventListener('input', function(e) {
            e.stopImmediatePropagation();
            setCount(this.value);
        }, true);

        var displayInput = document.getElementById('question_count_display');
        ['mousedown', 'mouseup', 'click', 'selectstart', 'copy', 'paste', 'cut', 'contextmenu', 'keydown'].forEach(function(ev) {
            displayInput.addEventListener(ev, function(e) {
                e.stopPropagation();
            }, true);
        });

        displayInput.addEventListener('input', function(e) {
            e.stopImmediatePropagation();
            var val = parseInt(this.value, 10);
            if (!isNaN(val)) {
                if (maxAvailable > 0 && val > maxAvailable) val = maxAvailable;
                currentCount = val;
                document.getElementById('setup_question_count').value = currentCount;
                document.getElementById('question_range').value = currentCount;
                document.getElementById('setup_estimated_time').innerText = currentCount + ' {{ __("quiz.Min") }}';
            }
        }, true);

        displayInput.addEventListener('blur', function() {
            setCount(this.value);
        });

        $('input[name="scope"]').on('change', function() {
            var val = $(this).val();
            $('#chapter_selection').addClass('d-none');
            $('#lesson_selection').addClass('d-none');

            if (val == 'chapter') {
                $('#chapter_selection').removeClass('d-none');
            } else if (val == 'lesson') {
                $('#chapter_selection').removeClass('d-none');
                $('#lesson_selection').removeClass('d-none');
                filterLessonsByChapters();
            }
            updateAvailability();
        });

        $(document).on('change', '.chapter-checkbox', function() {
            updateSelectionCounts();
            if ($('input[name="scope"]:checked').val() == 'lesson') {
                filterLessonsByChapters();
            }
            updateAvailability();
        });

        $(document).on('change', '.lesson-checkbox', function() {
            updateSelectionCounts();
            updateAvailability();
        });

        function filterLessonsByChapters() {
            var selectedChapterIds = getSelectedChapterIds();
            $('#lesson_panel label, #lesson_panel .ms-group-header').each(function() {
                var chId = String($(this).data('chapter-id'));
                if (selectedChapterIds.length === 0 || selectedChapterIds.indexOf(chId) !== -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                    $(this).find('input').prop('checked', false);
                }
            });
            updateSelectionCounts();
        }

        if (preselectScope && preselectScope !== 'course') {
            $('input[name="scope"][value="' + preselectScope + '"]').prop('checked', true).trigger('change');

            if (preselectChapterIds && preselectChapterIds.length > 0) {
                preselectChapterIds.forEach(function(id) {
                    $('.chapter-checkbox[value="' + id + '"]').prop('checked', true);
                });
            }

            if (preselectLessonIds && preselectLessonIds.length > 0) {
                preselectLessonIds.forEach(function(id) {
                    $('.lesson-checkbox[value="' + id + '"]').prop('checked', true);
                });
            }

            if (preselectScope === 'lesson') {
                filterLessonsByChapters();
            }

            updateSelectionCounts();
        }

        $('#toggle_setup_history_btn').on('click', function () {
            $('#historyDiv').slideToggle(250, function () {
                if ($(this).is(':visible')) {
                    this.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        updateAvailability();
    });
</script>
@endpush
