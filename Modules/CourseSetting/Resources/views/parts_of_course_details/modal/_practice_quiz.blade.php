<div class="modal-dialog modal-dialog-centered modal-lg student-details">
    <div class="modal-content">

        <div class="modal-header">
            <h4 class="modal-title">
                @if($edit)
                    {{__('common.Edit')}}
                @else
                    {{__('common.Add')}}
                @endif
                Practice Quiz
            </h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <form id="practiceQuizForm"
                @if($edit)
                    class="form-horizontal" method="POST" action="{{ route('updateChapter') }}"
                enctype="multipart/form-data">
                @method('PUT')
                @else
                    class="form-horizontal" method="POST" action="{{ route('saveChapter') }}"
                    enctype="multipart/form-data">
                @endif
                @csrf
                <input type="hidden" name="course_id" value="{{@$course_id}}">
                <input type="hidden" name="chapter_id" value="{{@$chapter_id}}">
                <input type="hidden" name="input_type" value="0">
                <input type="hidden" name="is_practice_quiz" value="1">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="practice_quiz_div">
                            <div class="row">

                                {{-- Title --}}
                                <div class="col-lg-12">
                                    <div class="input-effect mt-2 pt-1">
                                        <label class="primary_input_label mt-1">Practice Quiz Title
                                            <span class="required_mark">*</span></label>
                                        <input
                                            class="primary_input_field name{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                            type="text" name="name"
                                            placeholder="Enter Practice Quiz Title"
                                            autocomplete="off"
                                            value="{{$edit->name??""}}">
                                        <input type="hidden" name="lesson_id"
                                               value="{{$edit->id??""}}">
                                        <span class="focus-border"></span>
                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Course (Read-only) --}}
                                <div class="col-lg-12">
                                    <div class="input-effect mt-2 pt-1">
                                        <label class="primary_input_label mt-1">{{__('courses.Course')}}</label>
                                        <input
                                            class="primary_input_field"
                                            type="text"
                                            value="{{@$course->title}}"
                                            readonly
                                            disabled>
                                        <span class="focus-border"></span>
                                    </div>
                                </div>

                                {{-- Chapters (Multi-Select) --}}
                                <div class="col-lg-12">
                                    <div class="input-effect mt-2 pt-1">
                                        <label class="primary_input_label mt-1">{{__('quiz.Chapter')}} <small class="text-muted">(Select one or more)</small></label>
                                        @php
                                            $selectedChapterIds = [];
                                            if ($edit && $edit->practice_quiz_chapter_ids) {
                                                $selectedChapterIds = array_map('intval', explode(',', $edit->practice_quiz_chapter_ids));
                                            } elseif ($chapter_id) {
                                                $selectedChapterIds = [(int)$chapter_id];
                                            }
                                        @endphp
                                        <select class="primary_select w-100" name="practice_quiz_chapter_ids[]" id="practice_quiz_chapter_select" multiple="multiple">
                                            @foreach($allChapters as $ch)
                                                <option value="{{ $ch->id }}"
                                                    @if(in_array($ch->id, $selectedChapterIds)) selected @endif
                                                >{{ $ch->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Lessons (Multi-Select, dynamic based on chapters) --}}
                                <div class="col-lg-12">
                                    <div class="input-effect mt-2 pt-1">
                                        <label class="primary_input_label mt-1">{{__('courses.Lesson')}} ({{__('common.Optional')}}) <small class="text-muted">(Select one or more, or leave empty for all from selected chapters)</small></label>
                                        @php
                                            $selectedLessonIds = [];
                                            if ($edit && $edit->practice_quiz_lesson_ids) {
                                                $selectedLessonIds = array_map('intval', explode(',', $edit->practice_quiz_lesson_ids));
                                            } elseif ($edit && $edit->practice_quiz_lesson_id) {
                                                $selectedLessonIds = [(int)$edit->practice_quiz_lesson_id];
                                            }
                                        @endphp
                                        <select class="primary_select w-100" name="practice_quiz_lesson_ids[]" id="practice_quiz_lesson_select" multiple="multiple">
                                            @foreach($allChapters as $ch)
                                                @if(isset($ch->filteredLessons))
                                                    @foreach($ch->filteredLessons as $lessonItem)
                                                        <option value="{{ $lessonItem->id }}" data-chapter-id="{{ $ch->id }}"
                                                            @if(in_array($lessonItem->id, $selectedLessonIds)) selected @endif
                                                        >{{ $ch->name }} → {{ $lessonItem->name }}</option>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- No. of Questions --}}
                                <div class="col-lg-12">
                                    <div class="input-effect mt-2 pt-1">
                                        <label class="primary_input_label mt-1">No. of Questions
                                            <span class="required_mark">*</span></label>
                                        <input
                                            class="primary_input_field{{ $errors->has('practice_quiz_question_count') ? ' is-invalid' : '' }}"
                                            type="number" name="practice_quiz_question_count"
                                            placeholder="Enter number of questions"
                                            autocomplete="off"
                                            min="1"
                                            value="{{$edit->practice_quiz_question_count ?? 10}}">
                                        <span class="focus-border"></span>
                                        <small class="text-primary mt-2 d-inline-block" id="availableQuestionsText">
                                            Available Questions: <i class="fa fa-spinner fa-spin" id="availableQuestionsSpinner"></i> <strong id="availableQuestionsCount">...</strong>
                                        </small>
                                        @if ($errors->has('practice_quiz_question_count'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('practice_quiz_question_count') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Privacy --}}
                                <div class="col-lg-12">
                                    <div class="input-effect mt-2 pt-1">
                                        <label class="primary_input_label" for="">{{__('courses.Privacy')}}
                                            <span class="required_mark">*</span></label>
                                        <select class="primary_select" name="is_lock">
                                            <option data-display="{{__('common.Select')}} {{__('courses.Privacy')}}"
                                                    value="">{{__('common.Select')}} {{__('courses.Privacy')}}</option>
                                            <option value="0"
                                                    @if ($edit && @$edit->is_lock==0) selected @endif>{{__('courses.Unlock')}}</option>
                                            <option value="1"
                                                    @if (!$edit || @$edit->is_lock==1) selected @endif>{{__('courses.Locked')}}</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <button type="button" class="primary-btn tr-bg"
                            data-bs-dismiss="modal">@lang('common.Cancel')</button>
                    <button class="primary-btn fix-gr-bg"
                            type="submit">
                        <i class="ti-check"></i>
                        @lang('common.Submit')</button>
                </div>
            </form>

        </div>
    </div>
</div>

<style>
    /* Multi-select checkbox styles for niceSelect replacement */
    .pq-multiselect-wrap { position: relative; }
    .pq-multiselect-btn {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        padding: 8px 12px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        min-height: 40px;
        font-size: 14px;
        color: #334155;
    }
    .pq-multiselect-btn .pq-selected-text {
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .pq-multiselect-btn .pq-arrow { margin-left: 10px; font-size: 12px; color: #94a3b8; }
    .pq-multiselect-dropdown {
        position: absolute;
        top: 100%;
        left: 0; right: 0;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-top: none;
        border-radius: 0 0 6px 6px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        z-index: 9999;
        max-height: 250px;
        overflow-y: auto;
        display: none;
    }
    .pq-multiselect-dropdown.open { display: block; }
    .pq-multiselect-dropdown label {
        display: flex;
        align-items: center;
        padding: 8px 14px;
        cursor: pointer;
        transition: background 0.15s;
        font-size: 13px;
        color: #334155;
        margin: 0;
        gap: 8px;
    }
    .pq-multiselect-dropdown label:hover { background: #f8fafc; }
    .pq-multiselect-dropdown input[type="checkbox"] {
        width: 16px; height: 16px;
        accent-color: #FB1159;
        flex-shrink: 0;
    }
    .pq-multiselect-dropdown .pq-group-header {
        padding: 6px 14px;
        font-weight: 700;
        font-size: 12px;
        color: #64748b;
        background: #f1f5f9;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>

<script>
    $(document).ready(function () {
        // Hide the native selects and build custom multi-select UI
        var $chapterSelect = $('#practice_quiz_chapter_select');
        var $lessonSelect = $('#practice_quiz_lesson_select');

        // Destroy niceSelect on these elements if applied
        if ($chapterSelect.next('.nice-select').length) {
            $chapterSelect.next('.nice-select').remove();
        }
        if ($lessonSelect.next('.nice-select').length) {
            $lessonSelect.next('.nice-select').remove();
        }
        $chapterSelect.hide();
        $lessonSelect.hide();

        // Apply niceSelect only to the privacy select
        $('select[name="is_lock"]').niceSelect();

        // Build Chapter multi-select
        function buildMultiSelect($nativeSelect, label) {
            var $wrap = $('<div class="pq-multiselect-wrap"></div>');
            var $btn = $('<div class="pq-multiselect-btn"><span class="pq-selected-text">' + label + '</span><span class="pq-arrow">▾</span></div>');
            var $dropdown = $('<div class="pq-multiselect-dropdown"></div>');

            $nativeSelect.find('option').each(function () {
                var $opt = $(this);
                var isChecked = $opt.prop('selected') ? 'checked' : '';
                var chapterId = $opt.data('chapter-id') || '';
                var $label = $('<label data-chapter-id="' + chapterId + '"><input type="checkbox" value="' + $opt.val() + '" ' + isChecked + '> ' + $opt.text() + '</label>');
                $dropdown.append($label);
            });

            $wrap.append($btn).append($dropdown);
            $nativeSelect.after($wrap);

            // Toggle dropdown
            $btn.on('click', function (e) {
                e.stopPropagation();
                // Close other dropdowns
                $('.pq-multiselect-dropdown').not($dropdown).removeClass('open');
                $dropdown.toggleClass('open');
            });

            // Sync back to native select
            $dropdown.on('change', 'input[type="checkbox"]', function () {
                var selected = [];
                $dropdown.find('input:checked').each(function () {
                    selected.push($(this).val());
                });
                $nativeSelect.val(selected).trigger('change');
                updateBtnText($btn, $dropdown, label);
            });

            function updateBtnText($b, $dd, defaultLabel) {
                var checked = $dd.find('input:checked');
                if (checked.length === 0) {
                    $b.find('.pq-selected-text').text(defaultLabel);
                } else if (checked.length <= 2) {
                    var names = [];
                    checked.each(function () { names.push($(this).parent().text().trim()); });
                    $b.find('.pq-selected-text').text(names.join(', '));
                } else {
                    $b.find('.pq-selected-text').text(checked.length + ' selected');
                }
            }

            // Initial text
            updateBtnText($btn, $dropdown, label);

            return { $wrap: $wrap, $btn: $btn, $dropdown: $dropdown, updateText: function() { updateBtnText($btn, $dropdown, label); } };
        }

        var chapterMS = buildMultiSelect($chapterSelect, 'Select chapters...');
        var lessonMS = buildMultiSelect($lessonSelect, 'Select lessons (optional)...');

        // Close dropdowns on outside click
        $(document).on('click', function () {
            $('.pq-multiselect-dropdown').removeClass('open');
        });
        $('.pq-multiselect-dropdown').on('click', function (e) {
            e.stopPropagation();
        });

        // When chapters change, filter lesson options
        $chapterSelect.on('change', function () {
            var selectedChapterIds = $(this).val() || [];
            lessonMS.$dropdown.find('label').each(function () {
                var chId = $(this).data('chapter-id');
                if (chId && selectedChapterIds.length > 0) {
                    if (selectedChapterIds.indexOf(String(chId)) !== -1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                        $(this).find('input').prop('checked', false);
                    }
                } else {
                    $(this).show();
                }
            });
            // Sync lesson select
            var selectedLessons = [];
            lessonMS.$dropdown.find('input:checked').each(function () {
                selectedLessons.push($(this).val());
            });
            $lessonSelect.val(selectedLessons).trigger('change');
            lessonMS.updateText();
            updateAvailableQuestions();
        });

        // When lessons change, update count
        $lessonSelect.on('change', function () {
            updateAvailableQuestions();
        });

        function updateAvailableQuestions() {
            let form = $('#practiceQuizForm');
            let course_id = form.find('input[name="course_id"]').val();
            let chapter_ids = $chapterSelect.val() || [];
            let lesson_ids = $lessonSelect.val() || [];

            let scope = 'chapter';
            if (lesson_ids.length > 0) {
                scope = 'lesson';
            }

            $('#availableQuestionsSpinner').show();
            $('#availableQuestionsCount').hide();

            $.ajax({
                url: '{{route("practice-quiz.available-count")}}',
                type: 'GET',
                data: {
                    course_id: course_id,
                    chapter_ids: chapter_ids,
                    lesson_ids: lesson_ids,
                    scope: scope
                },
                success: function(response) {
                    $('#availableQuestionsSpinner').hide();
                    $('#availableQuestionsCount').text(response.count).show();
                    form.find('input[name="practice_quiz_question_count"]').attr('max', response.count);
                },
                error: function() {
                    $('#availableQuestionsSpinner').hide();
                    $('#availableQuestionsCount').text('Error').show();
                }
            });
        }

        updateAvailableQuestions();

        // Trigger initial chapter filter for lessons
        $chapterSelect.trigger('change');

        $('#practiceQuizForm').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let submitBtn = form.find('button[type="submit"]');

            let course_id = form.find('input[name="course_id"]').val();
            let chapter_ids = $chapterSelect.val() || [];
            let lesson_ids = $lessonSelect.val() || [];
            let requested_count = parseInt(form.find('input[name="practice_quiz_question_count"]').val()) || 0;

            let scope = lesson_ids.length > 0 ? 'lesson' : 'chapter';

            submitBtn.prop('disabled', true);
            let originalText = submitBtn.html();
            submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Validating...');

            $.ajax({
                url: '{{route("practice-quiz.available-count")}}',
                type: 'GET',
                data: {
                    course_id: course_id,
                    chapter_ids: chapter_ids,
                    lesson_ids: lesson_ids,
                    scope: scope
                },
                success: function(response) {
                    if (requested_count > response.count) {
                        toastr.error('Available questions in pool (' + response.count + ') is less than requested (' + requested_count + ').', 'Error');
                        submitBtn.prop('disabled', false);
                        submitBtn.html(originalText);
                    } else {
                        form[0].submit(); 
                    }
                },
                error: function() {
                    submitBtn.prop('disabled', false);
                    submitBtn.html(originalText);
                    form[0].submit(); 
                }
            });
        });
    });
</script>
