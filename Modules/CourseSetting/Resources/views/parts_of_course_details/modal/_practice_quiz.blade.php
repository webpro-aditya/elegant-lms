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

                                {{-- Chapter (Read-only) --}}
                                <div class="col-lg-12">
                                    <div class="input-effect mt-2 pt-1">
                                        <label class="primary_input_label mt-1">{{__('quiz.Chapter')}}</label>
                                        @php
                                            $chapterName = '';
                                            if ($chapter_id && $course) {
                                                $chapterObj = $course->chapters->where('id', $chapter_id)->first();
                                                $chapterName = $chapterObj ? $chapterObj->name : '';
                                            }
                                        @endphp
                                        <input
                                            class="primary_input_field"
                                            type="text"
                                            value="{{$chapterName}}"
                                            readonly
                                            disabled>
                                        <span class="focus-border"></span>
                                    </div>
                                </div>

                                {{-- Lesson (Dropdown - optional scope) --}}
                                <div class="col-lg-12">
                                    <div class="input-effect mt-2 pt-1">
                                        <label class="primary_input_label mt-1">{{__('courses.Lesson')}} ({{__('common.Optional')}})</label>
                                        <select class="primary_select" name="practice_quiz_lesson_id" id="practice_quiz_lesson_select">
                                            <option data-display="{{__('common.Select')}} {{__('courses.Lesson')}} (All Questions from Chapter)"
                                                    value="">{{__('common.Select')}} {{__('courses.Lesson')}} (All Questions from Chapter)</option>
                                            @if(isset($lessons) && count($lessons) > 0)
                                                @foreach ($lessons as $lessonItem)
                                                    <option value="{{$lessonItem->id}}"
                                                        @if($edit && $edit->practice_quiz_lesson_id == $lessonItem->id) selected @endif
                                                    >{{$lessonItem->name}}</option>
                                                @endforeach
                                            @endif
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

<script>
    $(document).ready(function () {
        $('select').niceSelect();
        
        $('#practiceQuizForm').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let submitBtn = form.find('button[type="submit"]');
            
            let course_id = form.find('input[name="course_id"]').val();
            let chapter_id = form.find('input[name="chapter_id"]').val();
            let lesson_id = form.find('select[name="practice_quiz_lesson_id"]').val();
            let requested_count = parseInt(form.find('input[name="practice_quiz_question_count"]').val()) || 0;
            
            let scope = lesson_id ? 'lesson' : 'chapter';
            
            submitBtn.prop('disabled', true);
            let originalText = submitBtn.html();
            submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Validating...');
            
            $.ajax({
                url: '{{route("practice-quiz.available-count")}}',
                type: 'GET',
                data: {
                    course_id: course_id,
                    chapter_id: chapter_id,
                    lesson_id: lesson_id,
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
