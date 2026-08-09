@extends('frontend.infixlmstheme.layouts.dashboard_master')
@section('title')Practice Quiz Setup @endsection
@section('mainContent')
<div class="main_content_iner main_content_padding">
    <div class="dashboard_lg_card">
        <div class="container-fluid no-gutters">
            <div class="row">
                <div class="col-12">
                    <div class="section__title3 margin_50">
                        <h3>Practice Quiz Setup</h3>
                        <p>Generate a customized practice quiz for: <strong>{{ $course->title }}</strong></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-8">
                    <form action="{{ route('practice-quiz.start') }}" method="POST" id="practice_setup_form">
                        @csrf
                        <input type="hidden" name="course_id" value="{{ $course->id }}" id="setup_course_id">
                        
                        <div class="dashboard_white_box p-4">
                            <h4 class="mb-4">1. Select Scope</h4>
                            <div class="d-flex mb-4">
                                <label class="primary_radio d-flex mr-4">
                                    <input type="radio" name="scope" value="course" checked>
                                    <span class="checkmark"></span>
                                    <span class="ml-2">Entire Course</span>
                                </label>
                                <label class="primary_radio d-flex mr-4">
                                    <input type="radio" name="scope" value="chapter">
                                    <span class="checkmark"></span>
                                    <span class="ml-2">Specific Chapter</span>
                                </label>
                                <label class="primary_radio d-flex mr-4">
                                    <input type="radio" name="scope" value="lesson">
                                    <span class="checkmark"></span>
                                    <span class="ml-2">Specific Lesson</span>
                                </label>
                            </div>

                            <div id="chapter_selection" class="d-none mb-4">
                                <label class="primary_label2">Select Chapter</label>
                                <select class="primary_select" name="chapter_id" id="setup_chapter_id">
                                    <option value="">Select Chapter</option>
                                    @foreach($course->chapters as $chapter)
                                        <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="lesson_selection" class="d-none mb-4">
                                <label class="primary_label2">Select Lesson</label>
                                <select class="primary_select" name="lesson_id" id="setup_lesson_id">
                                    <option value="">Select Lesson</option>
                                    @foreach($course->chapters as $chapter)
                                        <optgroup label="{{ $chapter->name }}">
                                            @foreach($chapter->lessons as $lesson)
                                                <option value="{{ $lesson->id }}">{{ $lesson->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>

                            <h4 class="mb-3 mt-5">2. Customize Quiz</h4>
                            <div id="availability_info" class="alert alert-info mb-4">
                                Loading available questions...
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="primary_label2">Number of Questions</label>
                                    <input type="number" class="primary_input" name="question_count" id="setup_question_count" value="10" min="1">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="primary_label2">Estimated Time</label>
                                    <div class="d-flex align-items-center h-100">
                                        <span id="setup_estimated_time" class="h4 text-primary font-weight-bold">10 Minutes</span>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="theme_btn w-100" id="start_btn">Start Practice Quiz</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-xl-4 mt-4 mt-xl-0">
                    <div class="dashboard_white_box p-4 bg-light">
                        <h4>Instructions</h4>
                        <ul class="list-unstyled mt-3">
                            <li class="mb-2"><i class="ti-check text-success mr-2"></i> Select the specific topic you want to revise.</li>
                            <li class="mb-2"><i class="ti-check text-success mr-2"></i> Adjust the number of questions based on your available free time.</li>
                            <li class="mb-2"><i class="ti-check text-success mr-2"></i> Passing the quiz (50%) contributes to your course completion progress.</li>
                            <li class="mb-2"><i class="ti-check text-success mr-2"></i> You can retake practice quizzes as many times as you like.</li>
                        </ul>
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
        let maxAvailable = 0;

        function updateAvailability() {
            let scope = $('input[name="scope"]:checked').val();
            let course_id = $('#setup_course_id').val();
            let chapter_id = $('#setup_chapter_id').val();
            let lesson_id = $('#setup_lesson_id').val();

            if (scope == 'chapter' && !chapter_id) return;
            if (scope == 'lesson' && !lesson_id) return;

            $('#availability_info').text('Checking availability...');
            $('#start_btn').prop('disabled', true);

            $.ajax({
                url: "{{ route('practice-quiz.available-count') }}",
                type: "GET",
                data: {
                    scope: scope,
                    course_id: course_id,
                    chapter_id: chapter_id,
                    lesson_id: lesson_id
                },
                success: function(res) {
                    maxAvailable = res.count;
                    if (maxAvailable > 0) {
                        $('#availability_info').removeClass('alert-danger').addClass('alert-info')
                            .html(`<strong>${maxAvailable}</strong> questions available in this scope.`);
                        
                        let currentVal = parseInt($('#setup_question_count').val());
                        if (currentVal > maxAvailable || isNaN(currentVal)) {
                            $('#setup_question_count').val(maxAvailable);
                        }
                        $('#setup_question_count').attr('max', maxAvailable);
                        updateTime();
                        $('#start_btn').prop('disabled', false);
                    } else {
                        $('#availability_info').removeClass('alert-info').addClass('alert-danger')
                            .html('<strong>0</strong> questions available. Please select another topic.');
                        $('#setup_question_count').val(0);
                        updateTime();
                        $('#start_btn').prop('disabled', true);
                    }
                }
            });
        }

        function updateTime() {
            let count = parseInt($('#setup_question_count').val());
            if(isNaN(count)) count = 0;
            if(count > maxAvailable && maxAvailable > 0) {
                count = maxAvailable;
                $('#setup_question_count').val(count);
            }
            $('#setup_estimated_time').text(`${count} Minutes`);
        }

        $('input[name="scope"]').on('change', function() {
            let val = $(this).val();
            $('#chapter_selection').addClass('d-none');
            $('#lesson_selection').addClass('d-none');

            if (val == 'chapter') {
                $('#chapter_selection').removeClass('d-none');
            } else if (val == 'lesson') {
                $('#lesson_selection').removeClass('d-none');
            }
            updateAvailability();
        });

        $('#setup_chapter_id, #setup_lesson_id').on('change', function() {
            updateAvailability();
        });

        $('#setup_question_count').on('mousedown mouseup click selectstart copy paste cut contextmenu keydown', function(e) {
            e.stopPropagation();
        });

        $('#setup_question_count').on('input change', function() {
            updateTime();
        });

        // initial load
        updateAvailability();
    });
</script>
@endpush
