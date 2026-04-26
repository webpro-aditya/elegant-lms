{{-- ============================================================
     FULL REPLACEMENT for your enrolled_students.blade.php
     Adds "Enroll Student" button + modal with live-search
     ============================================================ --}}

@extends('backend.master')
@push('styles')
    <link rel="stylesheet" href="{{assetPath('backend/css/student_list.css')}}"/>
    <style>
        .progress-bar { background-color: #9734f2; }

        /* ── Enroll Modal ── */
        #enrollModal .modal-content { border-radius: 10px; }
        #student-search-results { max-height: 280px; overflow-y: auto; }
        .student-result-item {
            display: flex; align-items: center; justify-content: space-between;
            padding: 10px 14px; border-bottom: 1px solid #f0f0f0; cursor: default;
        }
        .student-result-item:last-child { border-bottom: none; }
        .student-result-item .info { display: flex; flex-direction: column; }
        .student-result-item .s-name { font-weight: 600; font-size: 14px; }
        .student-result-item .s-email { font-size: 12px; color: #888; }
        .enroll-btn-item {
            background: #9734f2; color: #fff; border: none;
            padding: 5px 12px; border-radius: 5px; font-size: 13px; cursor: pointer;
            transition: background .2s;
        }
        .enroll-btn-item:hover { background: #7a24d9; }
        .enroll-btn-item:disabled { background: #ccc; cursor: not-allowed; }
        #search-spinner { display:none; }
        #no-result-msg  { display:none; padding:14px; text-align:center; color:#888; }
        .modal-content .modal-header{ border-radius: 0 !important; }
    </style>
@endpush

@php $table_name = 'users'; @endphp
@section('table') {{$table_name}} @endsection

@section('mainContent')

    {!! generateBreadcrumb() !!}

    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="box_header common_table_header">
                        <div class="main-title d-md-flex align-items-center justify-content-between">
                            <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px" id="page_title">
                                {{__('student.Students List')}} - {{$course->title}}
                            </h3>
                            {{-- ★ ENROLL BUTTON ★ --}}
                            <button type="button"
                                    class="primary-btn fix-gr-bg"
                                    data-bs-toggle="modal"
                                    data-bs-target="#enrollModal">
                                <i class="ti-plus mr-1"></i> {{__('courses.Enroll Student')}}
                            </button>
                        </div>
                    </div>
                </div>

                {{-- DataTable --}}
                <div class="col-lg-12 mt-40">
                    <div class="QA_section QA_section_heading_custom check_box_table">
                        <div class="QA_table">
                            <div class="">
                                <table id="lms_table" class="table Crm_table_active3">
                                    <thead>
                                    <tr>
                                        <th scope="col">{{__('common.SL')}}</th>
                                        <th scope="col">{{__('common.Image')}}</th>
                                        <th scope="col">{{__('common.Name')}}</th>
                                        <th scope="col">{{__('common.Email')}}</th>
                                        <th scope="col">{{__('common.Progress')}}</th>
                                        <th scope="col">{{__('common.Action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ════════════════════════════════════════════
                     ENROLL STUDENT MODAL
                ════════════════════════════════════════════ --}}
                <div class="modal fade" id="enrollModal" tabindex="-1"
                     aria-labelledby="enrollModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h4 class="modal-title" id="enrollModalLabel">
                                    {{__('courses.Enroll Student')}}
                                </h4>
                                <button type="button" class="btn-close"
                                        data-bs-dismiss="modal"><i class="ti-close"></i></button>
                            </div>

                            <div class="modal-body">
                                {{-- Live-search input --}}
                                <div class="input-group mb-3">
                                    <input type="text"
                                           id="enroll-search-input"
                                           class="form-control"
                                           placeholder="{{__('courses.Search by name or email...')}}"
                                           autocomplete="off">
                                    <span class="input-group-text" id="search-spinner">
                                        <span class="spinner-border spinner-border-sm" role="status"></span>
                                    </span>
                                </div>

                                {{-- Results list --}}
                                <div id="student-search-results"
                                     class="border rounded bg-white">
                                    <div id="no-result-msg">{{__('courses.No students found')}}</div>
                                </div>

                                {{-- Toast feedback --}}
                                <div id="enroll-feedback" class="mt-3" style="display:none;"></div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Delete modal (unchanged) --}}
                <div class="modal fade admin-query" id="deleteStudent">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">{{__('common.Delete')}} {{__('student.Student')}}</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    <i class="ti-close"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('student.delete')}}" method="post">
                                    @csrf
                                    <div class="text-center">
                                        <h4>{{__('common.Are you sure to delete ?')}}</h4>
                                    </div>
                                    <input type="hidden" name="id" value="" id="studentDeleteId">
                                    <div class="mt-40 d-flex justify-content-between">
                                        <button type="button" class="primary-btn tr-bg"
                                                data-bs-dismiss="modal">{{__('common.Cancel')}}</button>
                                        <button class="primary-btn fix-gr-bg"
                                                type="submit">{{__('common.Delete')}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection

@push('scripts')
    @if ($errors->any())
        <script>
            @if(Session::has('type'))
                @if(Session::get('type') == "store")
                    $('#add_student').modal('show');
                @else
                    $('#editStudent').modal('show');
                @endif
            @endif
        </script>
    @endif

    @php
        $listUrl   = route('course.getAllStudentData', $course->id);
        $searchUrl = route('course.enroll.search',    $course->id);
        $enrollUrl = route('course.enroll.store',     $course->id);
        $csrfToken = csrf_token();
    @endphp

    <script>
        /* ── DataTable (unchanged) ── */
        dataTableOptions.serverSide  = true;
        dataTableOptions.processing  = true;
        dataTableOptions.ajax        = '{!! $listUrl !!}';
        dataTableOptions.columns     = [
            {data: 'DT_RowIndex',  name: 'id',          orderable: true},
            {data: 'image',        name: 'image',        orderable: false},
            {data: 'student_name', name: 'student_name'},
            {data: 'email',        name: 'email'},
            {data: 'progressbar',  name: 'progressbar',  orderable: false},
            {data: 'notify_user',  name: 'notify_user',  orderable: false},
        ];
        dataTableOptions = updateColumnExportOption(dataTableOptions, [0, 2, 3, 4]);
        let table = $('#lms_table').DataTable(dataTableOptions);

        /* ══════════════════════════════════════════════════
           ENROLL MODAL — live-search + one-click enroll
        ══════════════════════════════════════════════════ */
        const searchInput   = document.getElementById('enroll-search-input');
        const resultsList   = document.getElementById('student-search-results');
        const noResultMsg   = document.getElementById('no-result-msg');
        const spinner       = document.getElementById('search-spinner');
        const feedback      = document.getElementById('enroll-feedback');
        let   searchTimeout = null;

        // Reset modal when closed
        document.getElementById('enrollModal')
            .addEventListener('hidden.bs.modal', function () {
                searchInput.value   = '';
                resultsList.innerHTML = '';
                resultsList.appendChild(noResultMsg);
                noResultMsg.style.display = 'none';
                feedback.style.display    = 'none';
                feedback.innerHTML        = '';
            });

        // Debounced search on keyup
        searchInput.addEventListener('keyup', function () {
            clearTimeout(searchTimeout);
            const q = this.value.trim();
            if (q.length < 2) {
                resultsList.innerHTML = '';
                resultsList.appendChild(noResultMsg);
                noResultMsg.style.display = 'none';
                return;
            }
            searchTimeout = setTimeout(() => doSearch(q), 350);
        });

        function doSearch(q) {
            spinner.style.display = 'inline-flex';
            resultsList.innerHTML = '';

            fetch(`{!! $searchUrl !!}?q=${encodeURIComponent(q)}`, {
                headers: {'X-Requested-With': 'XMLHttpRequest'}
            })
            .then(r => r.json())
            .then(users => {
                spinner.style.display = 'none';
                if (!users.length) {
                    resultsList.appendChild(noResultMsg);
                    noResultMsg.style.display = 'block';
                    return;
                }
                users.forEach(u => {
                    const item = document.createElement('div');
                    item.className = 'student-result-item';
                    item.innerHTML = `
                        <div class="info">
                            <span class="s-name">${escHtml(u.name)}</span>
                            <span class="s-email">${escHtml(u.email)}</span>
                        </div>
                        <button class="enroll-btn-item"
                                data-id="${u.id}"
                                data-name="${escHtml(u.name)}">
                            Enroll
                        </button>`;
                    resultsList.appendChild(item);
                });
            })
            .catch(() => { spinner.style.display = 'none'; });
        }

        // Delegate click on Enroll buttons
        resultsList.addEventListener('click', function (e) {
            const btn = e.target.closest('.enroll-btn-item');
            if (!btn) return;

            btn.disabled   = true;
            btn.textContent = '...';

            fetch('{!! $enrollUrl !!}', {
                method:  'POST',
                headers: {
                    'Content-Type':     'application/json',
                    'X-CSRF-TOKEN':     '{!! $csrfToken !!}',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({user_id: btn.dataset.id})
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    // Remove from search results
                    btn.closest('.student-result-item').remove();
                    // Reload DataTable to reflect new enrollment
                    table.ajax.reload(null, false);
                    showFeedback(res.message, 'success');
                } else {
                    btn.disabled    = false;
                    btn.textContent = 'Enroll';
                    showFeedback(res.message, 'danger');
                }
            })
            .catch(() => {
                btn.disabled    = false;
                btn.textContent = 'Enroll';
                showFeedback('Something went wrong.', 'danger');
            });
        });

        function showFeedback(msg, type) {
            feedback.style.display = 'block';
            feedback.innerHTML = `<div class="alert alert-${type} py-2 mb-0">${escHtml(msg)}</div>`;
            setTimeout(() => { feedback.style.display = 'none'; feedback.innerHTML = ''; }, 3500);
        }

        function escHtml(str) {
            return String(str)
                .replace(/&/g,'&amp;').replace(/</g,'&lt;')
                .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }
    </script>

    <script src="{{assetPath('backend/js/student_list.js')}}"></script>
@endpush