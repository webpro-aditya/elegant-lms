@extends('backend.master')

@push('styles')
    <style>
        /* ════════════════════════════════
           ENROLLMENT ACTIVITY LOG STYLES
        ════════════════════════════════ */

        /* ── Header section ── */
        .log-header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .log-back-btn {
            display: inline-flex; align-items: center; gap: 6px;
            background: none; border: 1.5px solid #e0d4ff; color: #7b2ff7;
            border-radius: 10px; padding: 8px 18px; font-size: 13px; font-weight: 600;
            text-decoration: none; transition: all .2s; white-space: nowrap;
        }
        .log-back-btn:hover {
            background: #f5f0ff; border-color: #c4a8ff; color: #6d22e8;
            text-decoration: none;
        }

        /* ── Badge styles for action column ── */
        .log-badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 5px 14px; border-radius: 20px;
            font-size: 12px; font-weight: 700; white-space: nowrap;
            letter-spacing: .3px;
        }
        .log-badge i { font-size: 10px; }

        .log-badge-enrolled {
            background: linear-gradient(135deg, #edfaf3, #d4f5e2);
            color: #1a7a4a;
        }
        .log-badge-updated {
            background: linear-gradient(135deg, #eef0ff, #dde3ff);
            color: #3d5afe;
        }
        .log-badge-removed {
            background: linear-gradient(135deg, #fff0f0, #ffe0e0);
            color: #e74c3c;
        }

        /* ── Student cell (avatar + name/email) ── */
        .log-student-cell {
            display: flex; align-items: center; gap: 10px;
        }
        .log-student-cell .profile_info {
            flex-shrink: 0;
        }
        .log-student-cell .profile_info img {
            width: 36px; height: 36px; border-radius: 50%;
            object-fit: cover; border: 2px solid #f0ebff;
        }
        .log-student-name {
            display: block; font-weight: 600; font-size: 13px; color: #222;
            line-height: 1.3;
        }
        .log-student-email {
            display: block; font-size: 11px; color: #999;
        }

        /* ── Details column ── */
        .log-detail-row {
            font-size: 12px; color: #555; line-height: 1.7;
        }
        .log-detail-label {
            font-weight: 700; color: #7b2ff7; margin-right: 4px;
        }

        /* ── Date column ── */
        .log-date {
            font-size: 12px; color: #666; white-space: nowrap;
        }

        /* ── Table tweaks ── */
        .QA_section .QA_table td, .QA_section .QA_table th {
            padding: 12px 15px;
            vertical-align: middle;
        }

        /* ── Summary cards ── */
        .log-summary-cards {
            display: flex; gap: 16px; margin-bottom: 24px; flex-wrap: wrap;
        }
        .log-summary-card {
            flex: 1; min-width: 160px;
            background: #fff; border: 1.5px solid #f0ebff;
            border-radius: 14px; padding: 18px 20px;
            display: flex; align-items: center; gap: 14px;
            transition: box-shadow .2s, transform .2s;
        }
        .log-summary-card:hover {
            box-shadow: 0 8px 24px rgba(123,47,247,.1);
            transform: translateY(-2px);
        }
        .log-summary-icon {
            width: 44px; height: 44px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; flex-shrink: 0;
        }
        .log-summary-icon.enrolled { background: #edfaf3; color: #1a7a4a; }
        .log-summary-icon.updated  { background: #eef0ff; color: #3d5afe; }
        .log-summary-icon.removed  { background: #fff0f0; color: #e74c3c; }
        .log-summary-icon.total    { background: #f0ebff; color: #7b2ff7; }
        .log-summary-info {}
        .log-summary-count {
            font-size: 22px; font-weight: 800; color: #222; line-height: 1.2;
        }
        .log-summary-label {
            font-size: 11px; font-weight: 600; color: #999;
            text-transform: uppercase; letter-spacing: .5px;
        }
    </style>
@endpush

@php $table_name = 'course_enrollment_logs'; @endphp
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
                                Enrollment Activity Log - {{$course->title}}
                            </h3>
                            <div class="log-header-actions">
                                <a href="{{route('course.enrolled_students', $course->id)}}" class="log-back-btn">
                                    <i class="ti-arrow-left"></i> Back to Students
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Summary cards --}}
                <div class="col-12 mt-20">
                    <div class="log-summary-cards">
                        <div class="log-summary-card">
                            <div class="log-summary-icon total"><i class="ti-layout-list-post"></i></div>
                            <div class="log-summary-info">
                                <div class="log-summary-count" id="total-count">—</div>
                                <div class="log-summary-label">Total Actions</div>
                            </div>
                        </div>
                        <div class="log-summary-card">
                            <div class="log-summary-icon enrolled"><i class="ti-plus"></i></div>
                            <div class="log-summary-info">
                                <div class="log-summary-count" id="enrolled-count">—</div>
                                <div class="log-summary-label">Enrolled</div>
                            </div>
                        </div>
                        <div class="log-summary-card">
                            <div class="log-summary-icon updated"><i class="ti-pencil"></i></div>
                            <div class="log-summary-info">
                                <div class="log-summary-count" id="updated-count">—</div>
                                <div class="log-summary-label">Updated</div>
                            </div>
                        </div>
                        <div class="log-summary-card">
                            <div class="log-summary-icon removed"><i class="ti-trash"></i></div>
                            <div class="log-summary-info">
                                <div class="log-summary-count" id="removed-count">—</div>
                                <div class="log-summary-label">Removed</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="QA_section QA_section_heading_custom check_box_table">
                        <div class="QA_table">
                            <table id="lms_table" class="table Crm_table_active3">
                                <thead>
                                <tr>
                                    <th>{{__('common.SL')}}</th>
                                    <th>Student</th>
                                    <th>{{__('common.Action')}}</th>
                                    <th>Details</th>
                                    <th>Performed By</th>
                                    <th>Date</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('scripts')
    @php
        $logDataUrl = route('course.enrollment.logData', $course->id);
    @endphp

    <script>
    /* ── DataTable ── */
    dataTableOptions.serverSide  = true;
    dataTableOptions.processing  = true;
    dataTableOptions.ajax        = {
        url: '{!! $logDataUrl !!}',
        dataSrc: function(json) {
            // Update summary cards from the full dataset count
            // We'll compute counts client-side from the drawn data
            return json.data;
        }
    };
    dataTableOptions.columns     = [
        {data:'DT_RowIndex',       name:'id',                orderable:false, searchable:false},
        {data:'student_info',      name:'student_info',      orderable:false},
        {data:'action_badge',      name:'action',            orderable:true},
        {data:'details_html',      name:'details_html',      orderable:false, searchable:false},
        {data:'performed_by_name', name:'performed_by_name', orderable:true},
        {data:'log_date',          name:'created_at',        orderable:true},
    ];
    dataTableOptions.order = [[5, 'desc']];

    var table = $('#lms_table').DataTable(dataTableOptions);

    /* Update summary cards after each draw */
    table.on('xhr.dt', function(e, settings, json, xhr) {
        if (!json || !json.data) return;
        var enrolled = 0, updated = 0, removed = 0;
        // Count from the action_badge column HTML
        json.data.forEach(function(row) {
            var badge = row.action_badge || '';
            if (badge.indexOf('log-badge-enrolled') !== -1) enrolled++;
            else if (badge.indexOf('log-badge-updated') !== -1) updated++;
            else if (badge.indexOf('log-badge-removed') !== -1) removed++;
        });
        document.getElementById('total-count').textContent    = json.recordsTotal || (enrolled + updated + removed);
        document.getElementById('enrolled-count').textContent = enrolled;
        document.getElementById('updated-count').textContent  = updated;
        document.getElementById('removed-count').textContent  = removed;
    });
    </script>
@endpush
