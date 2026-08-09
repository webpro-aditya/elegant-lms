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
                                        <td>
                                            @if($quiz->status == 1)
                                                <strong>{{ $quiz->percentage }}%</strong> ({{ $quiz->obtained_marks }}/{{ $quiz->total_marks }})
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($quiz->status == 1)
                                                @if($quiz->pass == 1)
                                                    <span class="badge badge-success px-2 py-1">PASSED</span>
                                                @else
                                                    <span class="badge badge-danger px-2 py-1">FAILED</span>
                                                @endif
                                            @else
                                                <span class="badge badge-warning px-2 py-1">IN PROGRESS</span>
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
@endsection
