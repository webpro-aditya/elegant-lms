@extends('backend.master')
@section('mainContent')
    {!! generateBreadcrumb() !!}

    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="box_header common_table_header">
                        <div class="main-title d-md-flex">
                            <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">Question Pool</h3>
                            @if(permissionCheck('question-pool.create'))
                                <ul class="d-flex">
                                    <li><a class="primary-btn radius_30px mr-10 fix-gr-bg"
                                           href="{{ route('question-pool.create') }}"><i
                                                class="ti-plus"></i>Add Question</a>
                                    </li>
                                </ul>
                            @endif
                        </div>
                    </div>

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
    <script>
        $(document).ready(function () {
            $('#lms_table').DataTable({
                bLengthChange: false,
                "bDestroy": true,
                processing: true,
                serverSide: true,
                order: [[0, "desc"]],
                "ajax": $.fn.dataTable.pipeline({
                    url: '{{ route('question-pool.index') }}',
                    pages: 5 // number of pages to cache
                }),
                columns: [
                    {data: 'DT_RowIndex', name: 'id'},
                    {data: 'question', name: 'question'},
                    {data: 'course', name: 'course'},
                    {data: 'chapter', name: 'chapter'},
                    {data: 'lesson', name: 'lesson'},
                    {data: 'type', name: 'type'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                language: {
                    emptyTable: "No data available in the table",
                    search: "<i class='ti-search'></i>",
                    searchPlaceholder: 'Quick Search',
                    paginate: {
                        next: "<i class='ti-arrow-right'></i>",
                        previous: "<i class='ti-arrow-left'></i>"
                    }
                },
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copyHtml5',
                        text: '<i class="far fa-copy"></i>',
                        title: $("#logo_title").val(),
                        titleAttr: 'Copy',
                        exportOptions: {
                            columns: ':visible',
                            columns: ':not(:last-child)',
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="far fa-file-excel"></i>',
                        titleAttr: 'Excel',
                        title: $("#logo_title").val(),
                        margin: [10, 10, 10, 0],
                        exportOptions: {
                            columns: ':visible',
                            columns: ':not(:last-child)',
                        },
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="far fa-file-alt"></i>',
                        titleAttr: 'CSV',
                        exportOptions: {
                            columns: ':visible',
                            columns: ':not(:last-child)',
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="far fa-file-pdf"></i>',
                        title: $("#logo_title").val(),
                        titleAttr: 'PDF',
                        exportOptions: {
                            columns: ':visible',
                            columns: ':not(:last-child)',
                        },
                        orientation: 'landscape',
                        pageSize: 'A4',
                        margin: [0, 0, 0, 12],
                        alignment: 'center',
                        header: true,
                        customize: function (doc) {
                            doc.content[1].table.widths =
                                Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i>',
                        titleAttr: 'Print',
                        title: $("#logo_title").val(),
                        exportOptions: {
                            columns: ':not(:last-child)',
                        }
                    },
                    {
                        extend: 'colvis',
                        text: '<i class="fa fa-columns"></i>',
                        postfixButtons: ['colvisRestore']
                    }
                ],
                columnDefs: [{
                    visible: false
                }],
                responsive: true,
            });

            $(document).on('click', '.deleteQuestion', function () {
                $('#delete_id').val($(this).data('id'));
                $('#deleteQuestionModal').modal('show');
            });
        });
    </script>
@endpush
