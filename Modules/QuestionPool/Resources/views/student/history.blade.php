@extends('frontend.infixlmstheme.layouts.dashboard_master')
@section('title')My Practice Quizzes @endsection
@section('mainContent')
<div class="main_content_iner main_content_padding">
    <div class="dashboard_lg_card">
        <div class="container-fluid no-gutters">
            <div class="row">
                <div class="col-12">
                    <div class="section__title3 margin_50">
                        <h3>My Practice Quizzes History</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="dashboard_white_box p-4">
                        
                        <div class="mb-4">
                            <form action="{{ route('practice-quiz.history') }}" method="GET" id="quizFilterForm">
                                <div class="row align-items-center">
                                    <div class="col-lg col-md-6 col-sm-12 mb-2">
                                        <select class="select2 w-100" name="course_id" id="course_id">
                                            <option value="">Select Course</option>
                                            @foreach($courses as $course)
                                                <option value="{{ $course->id }}" {{ $course_id == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg col-md-6 col-sm-12 mb-2">
                                        <select class="select2 w-100" name="chapter_id" id="chapter_id">
                                            <option value="">Select Chapter</option>
                                            @foreach($chapters as $chapter)
                                                <option value="{{ $chapter->id }}" {{ $chapter_id == $chapter->id ? 'selected' : '' }}>{{ $chapter->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg col-md-6 col-sm-12 mb-2">
                                        <select class="select2 w-100" name="lesson_id" id="lesson_id">
                                            <option value="">Select Lesson</option>
                                            @foreach($lessons as $lesson)
                                                <option value="{{ $lesson->id }}" {{ $lesson_id == $lesson->id ? 'selected' : '' }}>{{ $lesson->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg col-md-6 col-sm-12 mb-2">
                                        <select class="select2 w-100" name="status" id="status">
                                            <option value="">Select Status</option>
                                            <option value="passed" {{ $filter_status == 'passed' ? 'selected' : '' }}>Passed</option>
                                            <option value="failed" {{ $filter_status == 'failed' ? 'selected' : '' }}>Failed</option>
                                            <option value="in_progress" {{ $filter_status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-auto col-md-12 col-sm-12 mb-2 d-flex align-items-center" style="gap: 15px;">
                                        <button class="theme_btn" type="submit" id="filterBtn" style="border-radius: 5px; padding: 10px 28px; white-space: nowrap;">Filter</button>
                                        <a href="#" id="resetBtn" style="color: #222; font-weight: 600; text-decoration: none; white-space: nowrap;">Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Course</th>
                                        <th>Scope</th>
                                        <th>Score</th>
                                        <th>Result</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($quizzes as $quiz)
                                    <tr>
                                        <td>{{ $quiz->created_at->format('d M Y, h:i A') }}</td>
                                        <td>{{ $quiz->course->title ?? '-' }}</td>
                                        <td>
                                            @if($quiz->scope == 'lesson')
                                                Lesson: {{ $quiz->lesson->name ?? '-' }}
                                            @elseif($quiz->scope == 'chapter')
                                                Chapter: {{ $quiz->chapter->name ?? '-' }}
                                            @else
                                                Entire Course
                                            @endif
                                        </td>
                                        <td style="color: #333;">
                                            @if($quiz->status == 1)
                                                <strong style="color: #333;">{{ $quiz->percentage }}%</strong> ({{ $quiz->obtained_marks }}/{{ $quiz->total_marks }})
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($quiz->status == 1)
                                                @if($quiz->pass == 1)
                                                    <span class="badge px-2 py-1" style="background-color: #28a745; color: #fff;">PASSED</span>
                                                @else
                                                    <span class="badge px-2 py-1" style="background-color: #dc3545; color: #fff;">FAILED</span>
                                                @endif
                                            @else
                                                <span class="badge px-2 py-1" style="background-color: #ffc107; color: #333;">IN PROGRESS</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($quiz->status == 1)
                                                <a href="{{ route('practice-quiz.result', $quiz->id) }}" class="btn btn-sm btn-info text-white">View Result</a>
                                            @else
                                                <a href="{{ route('practice-quiz.attempt', $quiz->id) }}" class="btn btn-sm btn-primary text-white">Resume</a>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <p class="text-muted mb-0">No practice quizzes taken yet.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    #quizFilterForm .select2-container .select2-selection--single {
        height: 40px;
        display: flex;
        align-items: center;
        padding: 0 8px;
    }
    #quizFilterForm .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px;
    }
    #quizFilterForm .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 40px;
    }
</style>
<link href="{{assetPath('frontend/infixlmstheme/css/select2.min.css')}}{{assetVersion()}}" rel="stylesheet"/>
<script src="{{assetPath('frontend/infixlmstheme/js/select2.min.js')}}{{assetVersion()}}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if($.fn.select2) {
            $('#course_id').select2({ width: '100%', placeholder: 'Select Course', allowClear: true });
            $('#chapter_id').select2({ width: '100%', placeholder: 'Select Chapter', allowClear: true });
            $('#lesson_id').select2({ width: '100%', placeholder: 'Select Lesson', allowClear: true });
            $('#status').select2({ width: '100%', placeholder: 'Select Status', allowClear: true });
        }

        $('#quizFilterForm').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var btn = $('#filterBtn');
            var originalText = btn.text();
            btn.text('Filtering...');
            btn.prop('disabled', true);

            $.ajax({
                url: form.attr('action'),
                type: 'GET',
                data: form.serialize(),
                success: function(response) {
                    var tableHtml = $(response).find('.table-responsive').html();
                    $('.table-responsive').html(tableHtml);
                    btn.text(originalText);
                    btn.prop('disabled', false);
                },
                error: function() {
                    btn.text(originalText);
                    btn.prop('disabled', false);
                }
            });
        });
        
        $('#resetBtn').on('click', function(e) {
            e.preventDefault();
            $('#course_id').val('').trigger('change');
            $('#chapter_id').val('').trigger('change');
            $('#lesson_id').val('').trigger('change');
            $('#status').val('').trigger('change');
            $('#quizFilterForm').submit();
        });
    });
</script>
@endsection
