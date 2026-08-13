@extends('frontend.infixlmstheme.layouts.dashboard_master')
@section('title')Practice Quiz Attempt @endsection
@section('mainContent')
<style>
    html {
        scroll-behavior: smooth;
    }
    .question_block {
        scroll-margin-top: 120px;
    }
    .nav-not-attempted { background-color: #dc3545 !important; border-color: #dc3545 !important; color: #fff !important; }
    .nav-skipped { background-color: #ffc107 !important; border-color: #ffc107 !important; color: #fff !important; }
    .nav-answered { background-color: #28a745 !important; border-color: #28a745 !important; color: #fff !important; }
    
    @media (max-width: 768px) {
        .dashboard_white_box.p-4 { padding: 15px !important; }
        .question_body { font-size: 1.1rem !important; }
        .answer_section .primary_radio { padding: 15px !important; }
        #timer_display { font-size: 1.25rem !important; }
        .section__title3 h4 { font-size: 1.2rem; }
        .timer-icon-svg { width: 20px; height: 20px; }
    }
    @media (min-width: 769px) {
        .timer-icon-svg { width: 28px; height: 28px; }
    }
</style>
<div class="main_content_iner main_content_padding">
    <div class="dashboard_lg_card">
        <div class="container-fluid no-gutters">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded shadow-sm">
                        <h4 class="mb-0 text-primary" style="font-weight: 600;">Practice Quiz In Progress</h4>
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center mr-3">
                                <svg class="timer-icon-svg text-danger mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="mb-0 text-danger font-weight-bold timer_display" id="timer_display">
                                    {{ sprintf("%02d:00", $quiz->estimated_time) }}
                                </h3>
                            </div>
                            <button onclick="window.close();" class="theme_btn text-white px-4 py-2" style="border-radius: 8px; border: none;">
                                <i class="fas fa-arrow-left mr-2"></i> {{ __('common.Back') }}
                            </button>
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
                                    <div class="primary_input" style="position: relative; z-index: 2147483647;">
                                        <input class="primary_input3 fill-in-the-blanks-input" type="text" name="answer_{{ $detail->id }}" placeholder="Type your answer here..." style="position: relative !important; z-index: 2147483647 !important; pointer-events: auto !important; cursor: text !important; user-select: auto !important; background-color: #fff !important;" onmousedown="event.stopPropagation(); this.focus();" onclick="event.stopPropagation(); this.focus();">
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
                            
                            <div class="d-flex justify-content-between mb-3" style="font-size: 12px; font-weight: 600;">
                                <div class="d-flex align-items-center"><span style="width: 12px; height: 12px; background: #28a745; border-radius: 50%; display: inline-block; margin-right: 5px;"></span> Answered</div>
                                <div class="d-flex align-items-center"><span style="width: 12px; height: 12px; background: #ffc107; border-radius: 50%; display: inline-block; margin-right: 5px;"></span> Skipped</div>
                                <div class="d-flex align-items-center"><span style="width: 12px; height: 12px; background: #dc3545; border-radius: 50%; display: inline-block; margin-right: 5px;"></span> Unseen</div>
                            </div>
                            
                            <div class="d-flex flex-wrap">
                                @for($i = 1; $i <= $quiz->total_questions; $i++)
                                    <a href="#question_{{ $i }}" id="nav_link_{{ $i }}" class="btn m-1 rounded-circle nav-not-attempted" style="width: 40px; height: 40px; line-height: 26px; font-weight: bold; padding: 6px 0; text-align: center;">{{ $i }}</a>
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

        // Question Navigation Status Tracking
        const answeredQuestions = new Set();
        
        // Listen to radio changes (Multiple Choice / True-False)
        $('.question_block input[type="radio"]').on('change', function() {
            let questionId = $(this).closest('.question_block').attr('id').split('_')[1];
            $('#nav_link_' + questionId).removeClass('nav-not-attempted nav-skipped').addClass('nav-answered');
            answeredQuestions.add(questionId);
        });

        // Listen to text inputs (Fill in the blanks)
        $('.question_block input[type="text"]').on('input', function() {
            let questionId = $(this).closest('.question_block').attr('id').split('_')[1];
            if($(this).val().trim() !== '') {
                $('#nav_link_' + questionId).removeClass('nav-not-attempted nav-skipped').addClass('nav-answered');
                answeredQuestions.add(questionId);
            } else {
                answeredQuestions.delete(questionId);
                // If it's deleted, it goes back to skipped (yellow) because they already visited it!
                $('#nav_link_' + questionId).removeClass('nav-answered nav-not-attempted').addClass('nav-skipped');
            }
        });

        // Intersection observer to track skipped (visited but not answered)
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        let questionId = entry.target.id.split('_')[1];
                        // If it hasn't been answered, mark it as skipped/visited
                        if (!answeredQuestions.has(questionId)) {
                            $('#nav_link_' + questionId).removeClass('nav-not-attempted').addClass('nav-skipped');
                        }
                    }
                });
            }, { threshold: 0.5 }); // Trigger when 50% of the question block is visible

            // Observe all question blocks
            document.querySelectorAll('.question_block').forEach(block => {
                observer.observe(block);
            });
        }

        // Anti-piracy script bypass for Fill in the Blanks inputs
        var textInputs = document.querySelectorAll('.fill-in-the-blanks-input');
        var eventsToStop = ['selectstart', 'copy', 'paste', 'cut', 'contextmenu', 'keydown', 'keyup', 'keypress'];
        textInputs.forEach(function(input) {
            eventsToStop.forEach(function(ev) {
                input.addEventListener(ev, function(e) {
                    e.stopPropagation();
                }, false);
            });
        });
    });
</script>
@endpush
