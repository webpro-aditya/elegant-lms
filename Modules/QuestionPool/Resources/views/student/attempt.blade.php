@extends('frontend.infixlmstheme.layouts.dashboard_master')
@section('title')Practice Quiz Attempt @endsection
@section('mainContent')
<div class="main_content_iner main_content_padding">
    <div class="dashboard_lg_card">
        <div class="container-fluid no-gutters">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded shadow-sm">
                        <h4 class="mb-0 text-primary">Practice Quiz In Progress</h4>
                        <div class="d-flex align-items-center">
                            <i class="ti-timer text-danger mr-2" style="font-size: 24px;"></i>
                            <h3 class="mb-0 text-danger font-weight-bold timer_display" id="timer_display">
                                {{ sprintf("%02d:00", $quiz->estimated_time) }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
            
            <form action="{{ route('practice-quiz.submit') }}" method="POST" id="quiz_submit_form">
                @csrf
                <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">
                
                <div class="row">
                    <!-- Questions List -->
                    <div class="col-xl-9">
                        @foreach($quiz->details as $index => $detail)
                        <div class="dashboard_white_box p-4 mb-4 question_block" id="question_{{ $index + 1 }}">
                            <div class="d-flex justify-content-between mb-3">
                                <h5 class="text-secondary">Question {{ $index + 1 }} of {{ $quiz->total_questions }}</h5>
                                <span class="badge badge-info">{{ $detail->question->marks }} Marks</span>
                            </div>
                            
                            <div class="question_body text-dark h5 mb-4" style="line-height: 1.6;">
                                {!! $detail->question->question !!}
                            </div>
                            
                            @if($detail->question->image)
                                <img src="{{ asset($detail->question->image) }}" class="img-fluid rounded mb-4 max-w-100" alt="Question Image">
                            @endif

                            <div class="answer_section">
                                @if($detail->question->type == 'M')
                                    @foreach($detail->question->questionOptionsInRandom as $option)
                                        <label class="primary_radio d-block mb-3 bg-light p-3 rounded border">
                                            <input type="radio" name="answer_{{ $detail->id }}" value="{{ $option->id }}">
                                            <span class="checkmark" style="top: 15px;"></span>
                                            <span class="ml-2 h6">{{ $option->title }}</span>
                                        </label>
                                    @endforeach
                                @elseif($detail->question->type == 'T')
                                    <label class="primary_radio d-block mb-3 bg-light p-3 rounded border">
                                        <input type="radio" name="answer_{{ $detail->id }}" value="T">
                                        <span class="checkmark" style="top: 15px;"></span>
                                        <span class="ml-2 h6">True</span>
                                    </label>
                                    <label class="primary_radio d-block mb-3 bg-light p-3 rounded border">
                                        <input type="radio" name="answer_{{ $detail->id }}" value="F">
                                        <span class="checkmark" style="top: 15px;"></span>
                                        <span class="ml-2 h6">False</span>
                                    </label>
                                @elseif($detail->question->type == 'F')
                                    <div class="primary_input">
                                        <input class="primary_input3" type="text" name="answer_{{ $detail->id }}" placeholder="Type your answer here...">
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                        
                        <div class="text-center mt-4 mb-5">
                            <button type="button" class="theme_btn w-100" id="submit_quiz_btn">Submit Practice Quiz</button>
                        </div>
                    </div>
                    
                    <!-- Navigation sidebar -->
                    <div class="col-xl-3 d-none d-xl-block">
                        <div class="dashboard_white_box p-4 sticky-top" style="top: 20px;">
                            <h5 class="mb-3">Quiz Navigation</h5>
                            <div class="d-flex flex-wrap">
                                @for($i = 1; $i <= $quiz->total_questions; $i++)
                                    <a href="#question_{{ $i }}" class="btn btn-outline-secondary m-1 rounded-circle" style="width: 40px; height: 40px; line-height: 26px;">{{ $i }}</a>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        // Timer Logic
        let timeRemaining = {{ $quiz->estimated_time * 60 }};
        let timerInterval = setInterval(function() {
            timeRemaining--;
            
            let minutes = Math.floor(timeRemaining / 60);
            let seconds = timeRemaining % 60;
            
            let display = (minutes < 10 ? "0" : "") + minutes + ":" + (seconds < 10 ? "0" : "") + seconds;
            $('#timer_display').text(display);
            
            if (timeRemaining <= 60) {
                $('#timer_display').removeClass('text-primary').addClass('text-danger');
            }
            
            if (timeRemaining <= 0) {
                clearInterval(timerInterval);
                $('#quiz_submit_form').submit(); // Auto submit
            }
        }, 1000);

        $('#submit_quiz_btn').on('click', function(e) {
            e.preventDefault();
            if(confirm("Are you sure you want to submit the quiz? You won't be able to change your answers.")) {
                clearInterval(timerInterval);
                $('#quiz_submit_form').submit();
            }
        });
    });
</script>
@endpush
