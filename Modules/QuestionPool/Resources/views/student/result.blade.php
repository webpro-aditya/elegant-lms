@extends('frontend.infixlmstheme.layouts.dashboard_master')
@section('title')Practice Quiz Result @endsection
@section('mainContent')
<style>
    @media (max-width: 768px) {
        .dashboard_white_box.p-4 { padding: 15px !important; }
        .section__title3 h3 { font-size: 1.5rem !important; }
        .score-grid .col-6 { padding-left: 10px; padding-right: 10px; }
        .score-grid .p-3 { padding: 10px !important; }
        .score-grid h4 { font-size: 1.1rem; }
        .score-grid h2 { font-size: 1.5rem; }
    }
    .svg-icon-sm { width: 18px; height: 18px; display: inline-block; vertical-align: text-bottom; margin-right: 4px; }
</style>
<div class="main_content_iner main_content_padding">
    <div class="dashboard_lg_card">
        <div class="container-fluid no-gutters">
            <div class="row">
                <div class="col-12">
                    <div class="section__title3 margin_50 text-center">
                        <h3>Practice Quiz Result</h3>
                        <p>Attempt #{{ $attempt_number }} for 
                            @if($quiz->scope == 'lesson') Lesson: <strong>{{ $quiz->lesson->name ?? '' }}</strong>
                            @elseif($quiz->scope == 'chapter') Chapter: <strong>{{ $quiz->chapter->name ?? '' }}</strong>
                            @else Course: <strong>{{ $quiz->course->title ?? '' }}</strong>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8">
                    <div class="dashboard_white_box p-4 text-center {{ $quiz->pass == 1 ? 'border-success' : 'border-danger' }}" style="border: 2px solid;">
                        <h2 class="{{ $quiz->pass == 1 ? 'text-success' : 'text-danger' }} mb-3">
                            {{ $quiz->pass == 1 ? 'PASSED!' : 'FAILED' }}
                        </h2>
                        
                        <div class="row score-grid">
                            <div class="col-6 col-md-3 mb-3 mb-md-0">
                                <div class="p-3 bg-light rounded h-100">
                                    <h4 class="text-secondary">Score</h4>
                                    <h2 class="text-primary">{{ $quiz->percentage }}%</h2>
                                </div>
                            </div>
                            <div class="col-6 col-md-3 mb-3 mb-md-0">
                                <div class="p-3 bg-light rounded h-100">
                                    <h4 class="text-secondary">Marks</h4>
                                    <h2 class="text-info">{{ $quiz->obtained_marks }} / {{ $quiz->total_marks }}</h2>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-3 bg-light rounded h-100">
                                    <h4 class="text-secondary">Correct</h4>
                                    <h2 class="text-success">{{ $quiz->correct_answers }}</h2>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-3 bg-light rounded h-100">
                                    <h4 class="text-secondary">Wrong</h4>
                                    <h2 class="text-danger">{{ $quiz->wrong_answers }}</h2>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('practice-quiz.setup', $quiz->course_id) }}" class="theme_btn mr-2 mb-2">Take Another Quiz</a>
                            <a href="{{ url('my-practice-quizzes') }}" class="theme_btn_outline mb-2">View History</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <h4 class="mb-4 border-bottom pb-2">Detailed Review</h4>
                    
                    @foreach($quiz->details as $index => $detail)
                    <div class="dashboard_white_box p-4 mb-4 {{ $detail->is_correct ? 'border-left-success' : 'border-left-danger' }}" style="border-left: 5px solid {{ $detail->is_correct ? '#28a745' : '#dc3545' }};">
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="text-secondary">Question {{ $index + 1 }}</h5>
                            @if($detail->is_correct)
                                <span class="badge badge-success px-3 py-2"><svg class="svg-icon-sm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg> Correct (+{{ $detail->marks_obtained }})</span>
                            @else
                                <span class="badge badge-danger px-3 py-2"><svg class="svg-icon-sm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg> Wrong (0)</span>
                            @endif
                        </div>
                        
                        <div class="question_body text-dark h5 mb-4">
                            {!! $detail->question->question !!}
                        </div>
                        
                        @if($detail->question->image)
                            <img src="{{ asset($detail->question->image) }}" class="img-fluid rounded mb-4 max-w-100" alt="Question Image">
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded h-100">
                                    <strong class="d-block mb-2 text-secondary">Your Answer:</strong>
                                    @if($detail->question->type == 'M')
                                        @php
                                            $given_option = $detail->question->options->where('id', $detail->given_answer)->first();
                                        @endphp
                                        <p class="mb-0 {{ $detail->is_correct ? 'text-success' : 'text-danger' }}">
                                            {{ $given_option ? $given_option->title : 'No answer provided' }}
                                        </p>
                                    @else
                                        <p class="mb-0 {{ $detail->is_correct ? 'text-success' : 'text-danger' }}">
                                            {{ $detail->given_answer ?: 'No answer provided' }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-md-6 mt-3 mt-md-0">
                                <div class="p-3 bg-success-light rounded h-100" style="background-color: #e8f5e9;">
                                    <strong class="d-block mb-2 text-success">Correct Answer:</strong>
                                    @if($detail->question->type == 'M')
                                        @php
                                            $correct_option = $detail->question->options->where('status', 1)->first();
                                        @endphp
                                        <p class="mb-0 text-success">
                                            {{ $correct_option ? $correct_option->title : '' }}
                                        </p>
                                    @elseif($detail->question->type == 'T')
                                        <p class="mb-0 text-success">{{ $detail->question->true_false == 'T' ? 'True' : 'False' }}</p>
                                    @elseif($detail->question->type == 'F')
                                        <p class="mb-0 text-success">{{ $detail->question->suitable_words }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        @if(!empty(strip_tags($detail->question->explanation)))
                        <div class="mt-4 p-3 bg-info-light rounded" style="background-color: #e3f2fd;">
                            <strong class="d-block mb-2 text-info"><svg class="svg-icon-sm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> Explanation:</strong>
                            <div class="text-dark">
                                {!! $detail->question->explanation !!}
                            </div>
                        </div>
                        @endif
                        
                    </div>
                    @endforeach
                    
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
