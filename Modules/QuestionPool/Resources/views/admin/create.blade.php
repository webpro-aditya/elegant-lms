@extends('backend.master')
@section('mainContent')
    {!! generateBreadcrumb() !!}
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="box_header">
                        <div class="main-title d-flex justify-content-between w-100">
                            <h3 class="mb-0">Add Question</h3>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="white_box_50px box_shadow_white">
                        <form action="{{ route('question-pool.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <div class="primary_input">
                                        <label class="primary_input_label" for="course_id">Course *</label>
                                        <select class="primary_select" name="course_id" id="course_id" required>
                                            <option data-display="Select Course" value="">Select Course</option>
                                            @foreach($courses as $course)
                                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <div class="primary_input">
                                        <label class="primary_input_label" for="chapter_id">Chapter</label>
                                        <select class="primary_select" name="chapter_id" id="chapter_id">
                                            <option data-display="Select Chapter" value="">Select Chapter</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <div class="primary_input">
                                        <label class="primary_input_label" for="lesson_id">Lesson</label>
                                        <select class="primary_select" name="lesson_id" id="lesson_id">
                                            <option data-display="Select Lesson" value="">Select Lesson</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-lg-4 mb-3">
                                    <div class="primary_input">
                                        <label class="primary_input_label" for="marks">Marks *</label>
                                        <input class="primary_input_field" type="number" name="marks" value="1" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <div class="primary_input">
                                        <label class="primary_input_label" for="type">Question Type *</label>
                                        <select class="primary_select" name="type" id="question_type" required>
                                            <option data-display="Select Type" value="">Select Type</option>
                                            <option value="M">Multiple Choice</option>
                                            <option value="T">True/False</option>
                                            <option value="F">Fill in the blanks</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-12 mb-3">
                                    <div class="primary_input">
                                        <label class="primary_input_label" for="question">Question *</label>
                                        <textarea class="primary_textarea summernote" name="question" required></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Multiple Choice Section -->
                            <div class="row mt-3 d-none" id="multiple_choice_section">
                                <div class="col-lg-12 mb-3">
                                    <label class="primary_input_label">Options (Check the correct answer)</label>
                                    <div id="options_area">
                                        <div class="row mb-2">
                                            <div class="col-lg-1 d-flex align-items-center">
                                                <input type="radio" name="correct" value="0" checked>
                                            </div>
                                            <div class="col-lg-9">
                                                <input class="primary_input_field" type="text" name="option[]" placeholder="Option A">
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-lg-1 d-flex align-items-center">
                                                <input type="radio" name="correct" value="1">
                                            </div>
                                            <div class="col-lg-9">
                                                <input class="primary_input_field" type="text" name="option[]" placeholder="Option B">
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-lg-1 d-flex align-items-center">
                                                <input type="radio" name="correct" value="2">
                                            </div>
                                            <div class="col-lg-9">
                                                <input class="primary_input_field" type="text" name="option[]" placeholder="Option C">
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-lg-1 d-flex align-items-center">
                                                <input type="radio" name="correct" value="3">
                                            </div>
                                            <div class="col-lg-9">
                                                <input class="primary_input_field" type="text" name="option[]" placeholder="Option D">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- True/False Section -->
                            <div class="row mt-3 d-none" id="true_false_section">
                                <div class="col-lg-12 mb-3">
                                    <div class="primary_input">
                                        <label class="primary_input_label">Select Correct Answer</label>
                                        <div class="d-flex">
                                            <label class="primary_checkbox d-flex mr-12">
                                                <input type="radio" name="true_false" value="T">
                                                <span class="checkmark"></span>
                                                <span class="ml-2">True</span>
                                            </label>
                                            <label class="primary_checkbox d-flex mr-12">
                                                <input type="radio" name="true_false" value="F">
                                                <span class="checkmark"></span>
                                                <span class="ml-2">False</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Fill in the blanks Section -->
                            <div class="row mt-3 d-none" id="fill_blanks_section">
                                <div class="col-lg-12 mb-3">
                                    <div class="primary_input">
                                        <label class="primary_input_label">Suitable Words (Comma separated)</label>
                                        <textarea class="primary_textarea" name="suitable_words" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-lg-12 mb-3">
                                    <div class="primary_input">
                                        <label class="primary_input_label" for="explanation">Explanation</label>
                                        <textarea class="primary_textarea summernote" name="explanation"></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-lg-6 mb-3">
                                    <div class="primary_input mb-25">
                                        <label class="primary_input_label" for="">Image</label>
                                        <div class="primary_file_uploader">
                                            <input class="primary-input" type="text" id="placeholderFileOneName" placeholder="Browse file" readonly="">
                                            <button class="" type="button">
                                                <label class="primary-btn small fix-gr-bg" for="document_file_1">Browse</label>
                                                <input type="file" class="d-none" name="image" id="document_file_1">
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-lg-12 text-center">
                                    <button class="primary-btn fix-gr-bg" type="submit"><i class="ti-check"></i> Save Question</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#course_id').on('change', function () {
                var course_id = $(this).val();
                if (course_id) {
                    $.ajax({
                        url: "{{ url('question-pool/get-chapters') }}/" + course_id,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('#chapter_id').empty().append('<option value="">Select Chapter</option>');
                            $('#lesson_id').empty().append('<option value="">Select Lesson</option>');
                            $.each(data, function (key, value) {
                                $('#chapter_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                            $('#chapter_id').niceSelect('update');
                            $('#lesson_id').niceSelect('update');
                        }
                    });
                }
            });

            $('#chapter_id').on('change', function () {
                var chapter_id = $(this).val();
                if (chapter_id) {
                    $.ajax({
                        url: "{{ url('question-pool/get-lessons') }}/" + chapter_id,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('#lesson_id').empty().append('<option value="">Select Lesson</option>');
                            $.each(data, function (key, value) {
                                $('#lesson_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                            $('#lesson_id').niceSelect('update');
                        }
                    });
                }
            });

            $('#question_type').on('change', function () {
                var type = $(this).val();
                $('#multiple_choice_section').addClass('d-none');
                $('#true_false_section').addClass('d-none');
                $('#fill_blanks_section').addClass('d-none');

                if (type == 'M') {
                    $('#multiple_choice_section').removeClass('d-none');
                } else if (type == 'T') {
                    $('#true_false_section').removeClass('d-none');
                } else if (type == 'F') {
                    $('#fill_blanks_section').removeClass('d-none');
                }
            });
        });
    </script>
@endpush
