@extends('backend.master')

@push('styles')
    <link rel="stylesheet" href="{{assetPath('backend/css/student_list.css')}}"/>
    <style>
        .progress-bar { background-color: #9734f2; }

        /* ════════════════════════════════
           ENROLL MODAL — redesigned
        ════════════════════════════════ */
        #enrollModal .modal-dialog   { max-width: 580px; }
        #enrollModal .modal-content  { border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(123,47,247,.18); overflow: hidden; }

        /* ── Header ── */
        #enrollModal .modal-header {
            background: linear-gradient(135deg, #6d22e8 0%, #9b4dff 100%);
            padding: 20px 24px;
            border: none;
            position: relative;
        }
        #enrollModal .modal-header::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 1px;
            background: rgba(255,255,255,.15);
        }
        #enrollModal .modal-title {
            font-size: 17px;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -.2px;
        }
        #enrollModal .modal-title .title-icon {
            width: 32px; height: 32px;
            background: rgba(255,255,255,.2);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px;
        }
        #enrollModal .btn-close {
            background: rgba(255,255,255,.15);
            border-radius: 50%;
            width: 32px; height: 32px;
            display: flex; align-items: center; justify-content: center;
            border: none;
            color: #fff;
            font-size: 13px;
            transition: background .2s;
            padding: 0;
        }
        #enrollModal .btn-close:hover { background: rgba(255,255,255,.3); }
        #enrollModal .btn-close i { color: #fff; }

        /* ── Body ── */
        #enrollModal .modal-body { padding: 0; background: #fff; }

        /* ── Search section ── */
        .em-search-section {
            padding: 20px 24px 0;
        }
        .em-search-label {
            font-size: 12px;
            font-weight: 700;
            color: #888;
            text-transform: uppercase;
            letter-spacing: .6px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .em-search-wrap {
            position: relative;
        }
        .em-search-wrap .search-icon {
            position: absolute;
            left: 12px; top: 50%;
            transform: translateY(-50%);
            color: #b0b0b0;
            font-size: 14px;
            pointer-events: none;
        }
        #enroll-search-input {
            width: 100%;
            height: 44px;
            padding: 0 44px 0 38px;
            border: 1.5px solid #e8e0ff;
            border-radius: 10px;
            font-size: 14px;
            color: #333;
            background: #f9f7ff;
            outline: none;
            box-sizing: border-box;
            transition: border-color .2s, box-shadow .2s;
        }
        #enroll-search-input:focus {
            border-color: #9b4dff;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(155,77,255,.1);
        }
        #enroll-search-input::placeholder { color: #bbb; }
        .em-search-wrap .search-spinner-wrap {
            position: absolute;
            right: 12px; top: 50%;
            transform: translateY(-50%);
            display: none;
        }
        .em-search-wrap .search-spinner-wrap .spinner-border { width: 16px; height: 16px; border-width: 2px; color: #9b4dff; }

        /* ── Results area ── */
        .em-results-area {
            margin: 10px 24px 0;
            border: 1.5px solid #f0ebff;
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
        }
        .em-results-area.hidden { display: none; }

        .student-result-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            border-bottom: 1px solid #f5f3ff;
            transition: background .15s;
        }
        .student-result-item:last-child { border-bottom: none; }
        .student-result-item:hover { background: #faf8ff; }
        .student-result-item .stu-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e0d4ff, #c4a8ff);
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700; color: #7b2ff7;
            flex-shrink: 0;
            margin-right: 12px;
        }
        .student-result-item .stu-info { flex: 1; }
        .student-result-item .s-name  { font-weight: 600; font-size: 14px; color: #222; line-height: 1.3; }
        .student-result-item .s-email { font-size: 12px; color: #999; margin-top: 1px; }

        .em-no-result {
            padding: 24px 16px;
            text-align: center;
            color: #bbb;
            font-size: 13px;
            display: none;
        }
        .em-no-result i { font-size: 24px; display: block; margin-bottom: 8px; color: #e0d4ff; }

        .enroll-btn-item {
            background: linear-gradient(135deg, #7b2ff7, #9b4dff);
            color: #fff; border: none;
            padding: 7px 16px;
            border-radius: 20px;
            font-size: 12px; font-weight: 600;
            cursor: pointer;
            transition: opacity .2s, transform .1s;
            white-space: nowrap;
            display: flex; align-items: center; gap: 5px;
            flex-shrink: 0;
        }
        .enroll-btn-item:hover    { opacity: .88; transform: scale(1.02); }
        .enroll-btn-item:disabled { background: #ddd; cursor: not-allowed; transform: none; opacity: 1; }

        /* ── Divider with label ── */
        .em-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 24px;
        }
        .em-divider::before, .em-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #f0ebff;
        }
        .em-divider span {
            font-size: 11px;
            font-weight: 700;
            color: #c4a8ff;
            text-transform: uppercase;
            letter-spacing: .5px;
            white-space: nowrap;
        }

        /* ── Duration section ── */
        .em-duration-section { padding: 0 24px 24px; }

        .em-section-title {
            font-size: 13px;
            font-weight: 700;
            color: #6d22e8;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 14px;
        }
        .em-section-title .title-pill {
            font-size: 10px;
            font-weight: 500;
            color: #b0b0b0;
            background: #f5f5f5;
            border-radius: 20px;
            padding: 2px 8px;
        }

        /* ── Mode toggle ── */
        .em-mode-toggle {
            display: flex;
            background: #f5f2ff;
            border-radius: 10px;
            padding: 4px;
            gap: 4px;
            margin-bottom: 18px;
        }
        .em-mode-btn {
            flex: 1;
            border: none;
            background: transparent;
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 12px;
            font-weight: 600;
            color: #9b4dff;
            cursor: pointer;
            transition: all .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .em-mode-btn.active {
            background: #fff;
            color: #6d22e8;
            box-shadow: 0 2px 8px rgba(123,47,247,.15);
        }

        /* ── Date fields ── */
        .em-field-label {
            font-size: 11px;
            font-weight: 700;
            color: #999;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 6px;
            display: block;
        }
        .date-input-wrap { position: relative; }
        .date-display-input {
            width: 100%;
            height: 42px;
            padding: 0 38px 0 14px;
            border: 1.5px solid #e8e0ff;
            border-radius: 10px;
            background: #f9f7ff;
            font-size: 13px;
            color: #333;
            cursor: pointer;
            outline: none;
            caret-color: transparent;
            box-sizing: border-box;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }
        .date-display-input:hover   { border-color: #c4a8ff; }
        .date-display-input.has-value,
        .date-display-input:focus   { border-color: #9b4dff; background: #fff; box-shadow: 0 0 0 3px rgba(155,77,255,.1); }
        .date-input-wrap .cal-icon  { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #c4a8ff; pointer-events: none; font-size: 14px; }
        .date-display-input.has-value ~ .cal-icon { color: #9b4dff; }

        /* ── Inline calendar ── */
        .cal-widget {
            display: none;
            border: 1.5px solid #ede8ff;
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
            margin-top: 6px;
            box-shadow: 0 8px 24px rgba(123,47,247,.13);
        }
        .cal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, #6d22e8, #9b4dff);
            padding: 10px 14px;
        }
        .cal-month-label { font-size: 13px; font-weight: 700; color: #fff; }
        .cal-nav {
            background: rgba(255,255,255,.15);
            border: none;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            width: 28px; height: 28px;
            border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            transition: background .15s;
            padding: 0; line-height: 1;
        }
        .cal-nav:hover { background: rgba(255,255,255,.3); }
        .cal-grid { display: grid; grid-template-columns: repeat(7,1fr); padding: 8px; gap: 2px; }
        .cal-dow  { text-align: center; font-size: 10px; font-weight: 700; color: #9b4dff; padding: 4px 0 8px; }
        .cal-day  { text-align: center; font-size: 12px; padding: 7px 3px; cursor: pointer; border-radius: 8px; transition: background .12s, color .12s; line-height: 1.2; color: #444; }
        .cal-day:hover:not(.cal-empty):not(.cal-disabled) { background: #f0ebff; color: #7b2ff7; }
        .cal-day.cal-today    { font-weight: 700; color: #7b2ff7; position: relative; }
        .cal-day.cal-today::after { content: ''; position: absolute; bottom: 2px; left: 50%; transform: translateX(-50%); width: 4px; height: 4px; background: #9b4dff; border-radius: 50%; }
        .cal-day.cal-selected { background: linear-gradient(135deg,#7b2ff7,#9b4dff) !important; color: #fff !important; border-radius: 8px; }
        .cal-day.cal-disabled { color: #ddd; cursor: default; }
        .cal-day.cal-empty    { cursor: default; }
        .cal-footer { text-align: right; padding: 6px 10px; border-top: 1px solid #f5f3ff; }
        .cal-clear-btn { font-size: 11px; color: #c4a8ff; background: none; border: none; cursor: pointer; padding: 3px 8px; border-radius: 6px; font-weight: 600; }
        .cal-clear-btn:hover { color: #7b2ff7; background: #f5f3ff; }

        /* ── Duration number ── */
        .duration-number-wrap       { position: relative; }
        .duration-number-wrap input {
            padding: 0 48px 0 14px;
            border-radius: 10px;
            border: 1.5px solid #e8e0ff;
            height: 42px;
            font-size: 13px;
            width: 100%;
            outline: none;
            background: #f9f7ff;
            box-sizing: border-box;
            transition: border-color .2s, box-shadow .2s;
        }
        .duration-number-wrap input:focus { border-color: #9b4dff; background: #fff; box-shadow: 0 0 0 3px rgba(155,77,255,.1); }
        .duration-number-wrap .unit { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); font-size: 11px; color: #bbb; pointer-events: none; font-weight: 600; }

        /* ── Computed end date ── */
        .em-computed-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #f5f0ff, #ede8ff);
            border: 1.5px dashed #c4a8ff;
            border-radius: 10px;
            padding: 10px 14px;
            margin-top: 2px;
        }
        .em-computed-badge .cb-icon { color: #9b4dff; font-size: 16px; }
        .em-computed-badge .cb-content { display: flex; flex-direction: column; }
        .em-computed-badge .cb-label { font-size: 10px; font-weight: 700; color: #bbb; text-transform: uppercase; letter-spacing: .4px; }
        .em-computed-badge .cb-value { font-size: 14px; font-weight: 700; color: #6d22e8; }

        /* ── Input hint ── */
        .em-hint { font-size: 11px; color: #bbb; margin-top: 6px; display: flex; align-items: center; gap: 4px; }

        /* ── Feedback ── */
        #enroll-feedback { padding: 0 24px 20px; }
        #enroll-feedback .alert { border-radius: 10px; font-size: 13px; border: none; }
        #enroll-feedback .alert-success { background: #edfaf3; color: #1a7a4a; }
        #enroll-feedback .alert-danger  { background: #fff0f0; color: #c0392b; }

        /* ── Enrollment date badges (table) ── */
        .em-date-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }
        .em-date-badge i { font-size: 10px; }
        .em-date-start    { background: #f0ebff; color: #7b2ff7; }
        .em-date-active   { background: #edfaf3; color: #1a7a4a; }
        .em-date-lifetime { background: #f0ebff; color: #9b4dff; }
        .em-date-soon     { background: #fff8e1; color: #b8860b; }
        .em-date-expired  { background: #fff0f0; color: #c0392b; }
        .em-date-dash     { color: #ccc; font-size: 13px; }
        .em-date-sub {
            display: block;
            font-size: 10px;
            font-weight: 500;
            margin-top: 2px;
            opacity: .8;
        }
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
                            <button type="button" class="primary-btn fix-gr-bg"
                                    data-bs-toggle="modal" data-bs-target="#enrollModal">
                                <i class="ti-plus mr-1"></i> {{__('courses.Enroll Student')}}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 mt-40">
                    <div class="QA_section QA_section_heading_custom check_box_table">
                        <div class="QA_table">
                            <table id="lms_table" class="table Crm_table_active3">
                                <thead>
                                <tr>
                                    <th>{{__('common.SL')}}</th>
                                    <th>{{__('common.Image')}}</th>
                                    <th>{{__('common.Name')}}</th>
                                    <th>{{__('common.Email')}}</th>
                                    <th>{{__('common.Progress')}}</th>
                                    <th>{{__('courses.Enrolled On')}}</th>
                                    <th>{{__('courses.Access Until')}}</th>
                                    <th>{{__('common.Action')}}</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ══ ENROLL MODAL ══ --}}
                <div class="modal fade" id="enrollModal" tabindex="-1"
                     aria-labelledby="enrollModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            {{-- Header --}}
                            <div class="modal-header">
                                <h4 class="modal-title" id="enrollModalLabel">
                                    <span class="title-icon"><i class="ti-user"></i></span>
                                    {{__('courses.Enroll Student')}}
                                </h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="ti-close"></i>
                                </button>
                            </div>

                            {{-- Body --}}
                            <div class="modal-body">

                                {{-- Search --}}
                                <div class="em-search-section">
                                    <div class="em-search-label">
                                        <i class="ti-search"></i> {{__('courses.Search Student')}}
                                    </div>
                                    <div class="em-search-wrap">
                                        <i class="ti-search search-icon"></i>
                                        <input type="text" id="enroll-search-input"
                                               placeholder="{{__('courses.Search by name or email...')}}"
                                               autocomplete="off">
                                        <div class="search-spinner-wrap" id="search-spinner">
                                            <span class="spinner-border" role="status"></span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Results --}}
                                <div class="em-results-area hidden" id="em-results-area">
                                    <div id="student-search-results">
                                        <div id="no-result-msg" class="em-no-result">
                                            <i class="ti-face-sad"></i>
                                            {{__('courses.No students found')}}
                                        </div>
                                    </div>
                                </div>

                                {{-- Divider --}}
                                <div class="em-divider">
                                    <span>Enrollment Duration</span>
                                </div>

                                {{-- Duration section --}}
                                <div class="em-duration-section">

                                    <div class="em-section-title">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15">
                                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                                            <line x1="16" y1="2" x2="16" y2="6"/>
                                            <line x1="8" y1="2" x2="8" y2="6"/>
                                            <line x1="3" y1="10" x2="21" y2="10"/>
                                        </svg>
                                        Set Access Period
                                        <span class="title-pill">Optional</span>
                                    </div>

                                    <div class="em-mode-toggle" id="durationModeToggle">
                                        <button class="em-mode-btn active" data-mode="daterange" type="button">
                                            <i class="ti-calendar"></i> Date Range
                                        </button>
                                        <button class="em-mode-btn" data-mode="duration" type="button">
                                            <i class="ti-timer"></i> Duration (Days)
                                        </button>
                                    </div>

                                    {{-- Date Range panel --}}
                                    <div id="panel-daterange">
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <label class="em-field-label">Start Date</label>
                                                <div class="date-input-wrap">
                                                    <input type="text" id="enroll-start-date" class="date-display-input" placeholder="Pick a date" readonly />
                                                    <i class="ti-calendar cal-icon"></i>
                                                </div>
                                                <div id="cal-start" class="cal-widget"></div>
                                            </div>
                                            <div class="col-6">
                                                <label class="em-field-label">End Date</label>
                                                <div class="date-input-wrap">
                                                    <input type="text" id="enroll-end-date" class="date-display-input" placeholder="Pick a date" readonly />
                                                    <i class="ti-calendar cal-icon"></i>
                                                </div>
                                                <div id="cal-end" class="cal-widget"></div>
                                            </div>
                                        </div>
                                        <p class="em-hint"><i class="ti-info-alt"></i> Leave blank to enroll without a fixed end date.</p>
                                    </div>

                                    {{-- Duration panel --}}
                                    <div id="panel-duration" style="display:none;">
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <label class="em-field-label">Start Date</label>
                                                <div class="date-input-wrap">
                                                    <input type="text" id="enroll-duration-start" class="date-display-input" placeholder="Today by default" readonly />
                                                    <i class="ti-calendar cal-icon"></i>
                                                </div>
                                                <div id="cal-duration-start" class="cal-widget"></div>
                                            </div>
                                            <div class="col-6">
                                                <label class="em-field-label">Number of Days</label>
                                                <div class="duration-number-wrap">
                                                    <input type="number" id="enroll-duration-days" min="1" placeholder="e.g. 30" />
                                                    <span class="unit">days</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <label class="em-field-label">Calculated End Date</label>
                                            <div class="em-computed-badge">
                                                <i class="ti-calendar cb-icon"></i>
                                                <div class="cb-content">
                                                    <span class="cb-label">Access expires on</span>
                                                    <span class="cb-value" id="computed-end-text">—</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                {{-- Feedback --}}
                                <div id="enroll-feedback" style="display:none;"></div>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- Delete modal --}}
                <div class="modal fade admin-query" id="deleteStudent">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">{{__('common.Delete')}} {{__('student.Student')}}</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="ti-close"></i></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('student.delete')}}" method="post">
                                    @csrf
                                    <div class="text-center"><h4>{{__('common.Are you sure to delete ?')}}</h4></div>
                                    <input type="hidden" name="id" value="" id="studentDeleteId">
                                    <div class="mt-40 d-flex justify-content-between">
                                        <button type="button" class="primary-btn tr-bg" data-bs-dismiss="modal">{{__('common.Cancel')}}</button>
                                        <button class="primary-btn fix-gr-bg" type="submit">{{__('common.Delete')}}</button>
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
                @if(Session::get('type') == "store") $('#add_student').modal('show');
                @else $('#editStudent').modal('show');
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
    /* ── DataTable ── */
    dataTableOptions.serverSide = true;
    dataTableOptions.processing = true;
    dataTableOptions.ajax       = '{!! $listUrl !!}';
    dataTableOptions.columns    = [
        {data:'DT_RowIndex', name:'id',           orderable:true},
        {data:'image',       name:'image',         orderable:false},
        {data:'student_name',name:'student_name'},
        {data:'email',       name:'email'},
        {data:'progressbar',       name:'progressbar',       orderable:false},
        {data:'enroll_start_date', name:'enroll_start_date', orderable:false},
        {data:'enroll_end_date',   name:'enroll_end_date',   orderable:false},
        {data:'notify_user',       name:'notify_user',       orderable:false},
    ];
    dataTableOptions = updateColumnExportOption(dataTableOptions,[0,2,3,4,5,6]);
    var table = $('#lms_table').DataTable(dataTableOptions);

    /* ════════════════════════════════════════════════
       INLINE CALENDAR
    ════════════════════════════════════════════════ */
    var CAL_MONTHS = ['January','February','March','April','May','June',
                      'July','August','September','October','November','December'];
    var CAL_DAYS   = ['Su','Mo','Tu','We','Th','Fr','Sa'];

    function pad2(n) { return n < 10 ? '0' + n : '' + n; }

    function calFmt(d) {
        return d.getFullYear() + '-' + pad2(d.getMonth()+1) + '-' + pad2(d.getDate());
    }

    function calFmtDisplay(d) {
        return pad2(d.getDate()) + ' ' + CAL_MONTHS[d.getMonth()].slice(0,3) + ' ' + d.getFullYear();
    }

    function todayMidnight() {
        var d = new Date(); d.setHours(0,0,0,0); return d;
    }

    function getInitials(name) {
        var parts = (name || '').trim().split(' ');
        if (parts.length >= 2) return (parts[0][0] + parts[1][0]).toUpperCase();
        return (parts[0] || '?')[0].toUpperCase();
    }

    function InlineCalendar(opts) {
        var self       = this;
        self.container = document.getElementById(opts.containerId);
        self.input     = document.getElementById(opts.inputId);
        self.minDateFn = opts.minDateFn || function(){ return null; };
        self.onChange  = opts.onChange  || function(){};
        self.selected  = null;
        var now = todayMidnight();
        self.viewYear  = now.getFullYear();
        self.viewMonth = now.getMonth();

        self.input.addEventListener('click', function(e){
            e.stopPropagation();
            var isOpen = self.container.style.display === 'block';
            document.querySelectorAll('.cal-widget').forEach(function(c){ c.style.display='none'; });
            if (!isOpen) { self.container.style.display='block'; self.render(); }
        });
        self.container.addEventListener('click', function(e){ e.stopPropagation(); });
    }

    InlineCalendar.prototype.render = function(){
        var self    = this;
        var minDate = self.minDateFn();
        var today   = todayMidnight();
        var firstDay= new Date(self.viewYear, self.viewMonth, 1).getDay();
        var numDays = new Date(self.viewYear, self.viewMonth+1, 0).getDate();
        var cid     = self.container.id;

        var html = '<div class="cal-header">'
            + '<button type="button" class="cal-nav" id="' + cid + '-prev">&#8249;</button>'
            + '<span class="cal-month-label">' + CAL_MONTHS[self.viewMonth] + ' ' + self.viewYear + '</span>'
            + '<button type="button" class="cal-nav" id="' + cid + '-next">&#8250;</button>'
            + '</div><div class="cal-grid">';

        CAL_DAYS.forEach(function(d){ html += '<div class="cal-dow">' + d + '</div>'; });

        for (var i = 0; i < firstDay; i++) html += '<div class="cal-day cal-empty"></div>';

        for (var d = 1; d <= numDays; d++) {
            var td = new Date(self.viewYear, self.viewMonth, d);
            var cls = 'cal-day';
            if (td.getTime() === today.getTime())                           cls += ' cal-today';
            if (self.selected && td.getTime() === self.selected.getTime()) cls += ' cal-selected';
            if (minDate && td < minDate)                                    cls += ' cal-disabled';
            html += '<div class="' + cls + '" data-ts="' + td.getTime() + '">' + d + '</div>';
        }

        html += '</div><div class="cal-footer">'
            + '<button type="button" class="cal-clear-btn" id="' + cid + '-clear">&#10005; Clear</button>'
            + '</div>';

        self.container.innerHTML = html;

        document.getElementById(cid+'-prev').addEventListener('click', function(e){
            e.stopPropagation();
            self.viewMonth--; if(self.viewMonth<0){self.viewMonth=11;self.viewYear--;} self.render();
        });
        document.getElementById(cid+'-next').addEventListener('click', function(e){
            e.stopPropagation();
            self.viewMonth++; if(self.viewMonth>11){self.viewMonth=0;self.viewYear++;} self.render();
        });
        document.getElementById(cid+'-clear').addEventListener('click', function(e){
            e.stopPropagation();
            self.selected=null; self.input.value=''; self.input.classList.remove('has-value');
            self.container.style.display='none'; self.onChange(null);
        });
        self.container.querySelectorAll('.cal-day:not(.cal-empty):not(.cal-disabled)').forEach(function(el){
            el.addEventListener('click', function(e){
                e.stopPropagation();
                var ts = parseInt(this.getAttribute('data-ts'), 10);
                self.selected = new Date(ts);
                self.input.value = calFmtDisplay(self.selected);
                self.input.setAttribute('data-value', calFmt(self.selected));
                self.input.classList.add('has-value');
                self.container.style.display='none';
                self.onChange(self.selected);
            });
        });
    };

    InlineCalendar.prototype.getDate   = function(){ return this.selected; };
    InlineCalendar.prototype.getValue  = function(){ return this.selected ? calFmt(this.selected) : null; };
    InlineCalendar.prototype.rerender  = function(){ if(this.container.style.display==='block') this.render(); };
    InlineCalendar.prototype.clear     = function(){
        this.selected=null; this.input.value=''; this.input.removeAttribute('data-value');
        this.input.classList.remove('has-value'); this.container.style.display='none';
        var now=todayMidnight(); this.viewYear=now.getFullYear(); this.viewMonth=now.getMonth();
    };

    /* ── Instantiate ── */
    var calStart = new InlineCalendar({
        containerId: 'cal-start',
        inputId:     'enroll-start-date',
        onChange: function(date){
            calEnd.rerender();
            var ed = calEnd.getDate();
            if (date && ed && ed < date) calEnd.clear();
        }
    });

    var calEnd = new InlineCalendar({
        containerId: 'cal-end',
        inputId:     'enroll-end-date',
        minDateFn:   function(){ return calStart.getDate(); }
    });

    var calDurationStart = new InlineCalendar({
        containerId: 'cal-duration-start',
        inputId:     'enroll-duration-start',
        onChange:    function(){ recomputeEndDate(); }
    });

    document.addEventListener('click', function(){
        document.querySelectorAll('.cal-widget').forEach(function(c){ c.style.display='none'; });
    });

    /* ── Duration calc ── */
    function recomputeEndDate(){
        var days = parseInt(document.getElementById('enroll-duration-days').value, 10);
        var out  = document.getElementById('computed-end-text');
        if (!days || days < 1){ out.textContent='—'; return; }
        var sd   = calDurationStart.getDate();
        var base = sd ? new Date(sd.getTime()) : new Date();
        base.setDate(base.getDate()+days);
        out.textContent = calFmtDisplay(base);
    }
    document.getElementById('enroll-duration-days').addEventListener('input', recomputeEndDate);

    /* ── Mode toggle ── */
    var currentMode = 'daterange';
    document.querySelectorAll('#durationModeToggle .em-mode-btn').forEach(function(btn){
        btn.addEventListener('click', function(){
            document.querySelectorAll('#durationModeToggle .em-mode-btn').forEach(function(b){ b.classList.remove('active'); });
            this.classList.add('active');
            currentMode = this.dataset.mode;
            document.getElementById('panel-daterange').style.display = currentMode==='daterange' ? '' : 'none';
            document.getElementById('panel-duration').style.display  = currentMode==='duration'  ? '' : 'none';
        });
    });

    /* ── Modal reset ── */
    document.getElementById('enrollModal').addEventListener('hidden.bs.modal', function(){
        searchInput.value='';
        document.getElementById('em-results-area').classList.add('hidden');
        document.getElementById('student-search-results').innerHTML='';
        document.getElementById('student-search-results').appendChild(noResultMsg);
        noResultMsg.style.display='none';
        feedback.style.display='none'; feedback.innerHTML='';
        document.querySelectorAll('.cal-widget').forEach(function(c){ c.style.display='none'; });
        calStart.clear(); calEnd.clear(); calDurationStart.clear();
        document.getElementById('enroll-duration-days').value='';
        document.getElementById('computed-end-text').textContent='—';
        document.querySelectorAll('#durationModeToggle .em-mode-btn').forEach(function(b){ b.classList.remove('active'); });
        document.querySelector('[data-mode="daterange"]').classList.add('active');
        currentMode='daterange';
        document.getElementById('panel-daterange').style.display='';
        document.getElementById('panel-duration').style.display='none';
    });

    /* ── Live search ── */
    var searchInput = document.getElementById('enroll-search-input');
    var resultsList = document.getElementById('student-search-results');
    var noResultMsg = document.getElementById('no-result-msg');
    var spinner     = document.getElementById('search-spinner');
    var feedback    = document.getElementById('enroll-feedback');
    var resultsArea = document.getElementById('em-results-area');
    var searchTimeout = null;

    searchInput.addEventListener('keyup', function(){
        clearTimeout(searchTimeout);
        var q = this.value.trim();
        if (q.length < 2){
            resultsArea.classList.add('hidden');
            resultsList.innerHTML=''; resultsList.appendChild(noResultMsg);
            noResultMsg.style.display='none';
            return;
        }
        searchTimeout = setTimeout(function(){ doSearch(q); }, 350);
    });

    function doSearch(q){
        spinner.style.display='flex';
        resultsList.innerHTML='';
        resultsArea.classList.remove('hidden');
        fetch('{!! $searchUrl !!}?q='+encodeURIComponent(q),{headers:{'X-Requested-With':'XMLHttpRequest'}})
        .then(function(r){ return r.json(); })
        .then(function(users){
            spinner.style.display='none';
            if(!users.length){
                resultsList.appendChild(noResultMsg);
                noResultMsg.style.display='block';
                return;
            }
            users.forEach(function(u){
                var item=document.createElement('div');
                item.className='student-result-item';
                item.innerHTML='<div class="stu-avatar">'+escHtml(getInitials(u.name))+'</div>'
                    +'<div class="stu-info"><div class="s-name">'+escHtml(u.name)+'</div><div class="s-email">'+escHtml(u.email)+'</div></div>'
                    +'<button class="enroll-btn-item" data-id="'+u.id+'" data-name="'+escHtml(u.name)+'"><i class="ti-plus"></i> Enroll</button>';
                resultsList.appendChild(item);
            });
        })
        .catch(function(){ spinner.style.display='none'; });
    }

    /* ── Payload ── */
    function getDurationPayload(){
        if(currentMode==='daterange'){
            return { start_date: calStart.getValue(), end_date: calEnd.getValue() };
        }
        var sd   = calDurationStart.getValue();
        var days = parseInt(document.getElementById('enroll-duration-days').value, 10);
        return { start_date: sd, duration: days||null };
    }

    /* ── Enroll click ── */
    resultsList.addEventListener('click', function(e){
        var btn=e.target.closest('.enroll-btn-item');
        if(!btn) return;
        btn.disabled=true;
        btn.innerHTML='<span class="spinner-border spinner-border-sm" style="width:14px;height:14px;border-width:2px;"></span>';
        var payload=Object.assign({user_id:btn.dataset.id},getDurationPayload());
        fetch('{!! $enrollUrl !!}',{
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{!! $csrfToken !!}','X-Requested-With':'XMLHttpRequest'},
            body:JSON.stringify(payload)
        })
        .then(function(r){ return r.json(); })
        .then(function(res){
            if(res.success){
                btn.closest('.student-result-item').remove();
                if(!resultsList.querySelector('.student-result-item')){
                    resultsList.appendChild(noResultMsg);
                    noResultMsg.style.display='block';
                }
                table.ajax.reload(null,false);
                showFeedback(res.message,'success');
            } else {
                btn.disabled=false;
                btn.innerHTML='<i class="ti-plus"></i> Enroll';
                showFeedback(res.message,'danger');
            }
        })
        .catch(function(){
            btn.disabled=false;
            btn.innerHTML='<i class="ti-plus"></i> Enroll';
            showFeedback('Something went wrong.','danger');
        });
    });

    function showFeedback(msg,type){
        feedback.style.display='block';
        feedback.innerHTML='<div class="alert alert-'+type+'"><i class="'+(type==='success'?'ti-check-box':'ti-alert')+' mr-2"></i>'+escHtml(msg)+'</div>';
        setTimeout(function(){ feedback.style.display='none'; feedback.innerHTML=''; }, 3500);
    }
    function escHtml(str){
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
    </script>

    <script src="{{assetPath('backend/js/student_list.js')}}"></script>
@endpush