@extends('backend.master')
@section('mainContent')
    {!! generateBreadcrumb() !!}

    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="white_box mb_30">
                        <div class="white_box_tittle list_header main-title mb-0">
                            <h3 class="mb-0">Advanced Filter</h3>
                        </div>
                        <form action="#" method="GET" id="filter_form">
                            <div class="row">
                                <div class="col-lg-3 mt-20">
                                    <label class="primary_input_label" for="course_id">Course</label>
                                    <select class="primary_select" name="course_id" id="course_id">
                                        <option data-display="Select Course" value="">Select Course</option>
                                        @foreach($courses as $course)
                                            <option value="{{$course->id}}">{{$course->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 mt-20">
                                    <label class="primary_input_label" for="chapter_id">Chapter</label>
                                    <select class="primary_select" name="chapter_id" id="chapter_id">
                                        <option data-display="Select Chapter" value="">Select Chapter</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 mt-20">
                                    <label class="primary_input_label" for="lesson_id">Lesson</label>
                                    <select class="primary_select" name="lesson_id" id="lesson_id">
                                        <option data-display="Select Lesson" value="">Select Lesson</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 mt-20">
                                    <label class="primary_input_label" for="type">Question Type</label>
                                    <select class="primary_select" name="type" id="type">
                                        <option data-display="Select Type" value="">Select Type</option>
                                        <option value="M">Multiple Choice</option>
                                        <option value="T">True/False</option>
                                        <option value="F">Fill in the blanks</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 mt-20">
                                    <label class="primary_input_label" for="status">Status</label>
                                    <select class="primary_select" name="status" id="status">
                                        <option data-display="Select Status" value="">Select Status</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-12 mt-20">
                                    <div class="search_course_btn text-end">
                                        <button type="button" id="filter_btn" class="primary-btn radius_30px fix-gr-bg">
                                            <span class="ti-search pe-2"></span> Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="white-box">
                <div class="row">
                    <div class="col-12">
                        <div class="box_header common_table_header">
                            <div class="main-title d-md-flex">
                                <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px" id="page_title">Question Pool</h3>
                                @if(permissionCheck('question-pool.create'))
                                    <ul class="d-flex">
                                        <li>
                                            <a class="primary-btn radius_30px fix-gr-bg"
                                               href="{{ route('question-pool.create') }}">
                                                <i class="ti-plus"></i>Add Question
                                            </a>
                                        </li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="QA_section QA_section_heading_custom check_box_table">
                        <div class="QA_table">
                            <table id="lms_table" class="table Crm_table_active3">
                                <thead>
                                <tr>
                                    <th scope="col">Sl</th>
                                    <th scope="col">Question</th>
                                    <th scope="col">Course</th>
                                    <th scope="col">Chapter</th>
                                    <th scope="col">Lesson</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade admin-query" id="deleteQuestionModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Question</h4>
                    <button type="button" class="close" data-dismiss="modal"><i class="ti-close "></i></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <h4>Are you sure to delete?</h4>
                    </div>
                    <div class="mt-40 d-flex justify-content-between">
                        <button type="button" class="primary-btn tr-bg" data-dismiss="modal">Cancel</button>
                        <form action="{{ route('question-pool.delete') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="delete_id">
                            <button type="submit" class="primary-btn fix-gr-bg">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <style>
        .QA_section .QA_table .dataTables_wrapper {
            padding-top: 60px;
        }
        .QA_section .QA_table .dataTables_filter > label,
        .QA_section .QA_table div.dt-buttons {
            top: 0px !important;
        }
    </style>
    <script>
        $(document).ready(function () {
        dataTableOptions.serverSide = true;
        dataTableOptions.processing = true;
        dataTableOptions.bLengthChange = false;
        dataTableOptions.bDestroy = true;
        dataTableOptions.order = [[0, "desc"]];
        
        dataTableOptions.ajax = $.fn.dataTable.pipeline({
            url: '{{ route('question-pool.index') }}',
            data: function (d) {
                d.course_id = $('#course_id').val();
                d.chapter_id = $('#chapter_id').val();
                d.lesson_id = $('#lesson_id').val();
                d.type = $('#type').val();
                d.status = $('#status').val();
            },
            pages: 5
        });

        dataTableOptions.columns = [
            {data: 'DT_RowIndex', name: 'id'},
            {data: 'question', name: 'question'},
            {data: 'course', name: 'course'},
            {data: 'chapter', name: 'chapter'},
            {data: 'lesson', name: 'lesson'},
            {data: 'type', name: 'type'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];

        if (typeof updateColumnExportOption === "function") {
            dataTableOptions = updateColumnExportOption(dataTableOptions, [0, 1, 2, 3, 4, 5, 6]);
        }

        $('#lms_table').DataTable(dataTableOptions);

            $(document).on('click', '.deleteQuestion', function () {
                $('#delete_id').val($(this).data('id'));
                $('#deleteQuestionModal').modal('show');
            });

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
                } else {
                    $('#chapter_id').empty().append('<option value="">Select Chapter</option>');
                    $('#lesson_id').empty().append('<option value="">Select Lesson</option>');
                    $('#chapter_id').niceSelect('update');
                    $('#lesson_id').niceSelect('update');
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
                } else {
                    $('#lesson_id').empty().append('<option value="">Select Lesson</option>');
                    $('#lesson_id').niceSelect('update');
                }
            });

            $('#filter_btn').on('click', function() {
                var table = $('#lms_table').DataTable();
                table.clearPipeline();
                table.draw();
            });
        });
    </script>
@endpush
