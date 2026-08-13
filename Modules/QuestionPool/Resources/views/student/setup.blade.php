@extends('frontend.infixlmstheme.layouts.dashboard_master')
@section('title')Practice Quiz Setup @endsection

@section('mainContent')
<style>
    .setup-wrapper {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    .setup-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: 1px solid rgba(226, 232, 240, 0.8);
        padding: 30px;
        margin-bottom: 30px;
    }
    .setup-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .title-icon-wrapper {
        background: #f1f5f9;
        color: #FB1159;
        width: 36px; height: 36px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
    }
    .title-icon-wrapper svg { width: 20px; height: 20px; }
    
    @media (max-width: 768px) {
        .setup-card { padding: 20px; margin-bottom: 20px; }
        .setup-title { font-size: 1rem; margin-bottom: 20px; gap: 8px; }
        .title-icon-wrapper { width: 32px; height: 32px; }
        .title-icon-wrapper svg { width: 18px; height: 18px; }
        .section__title3 h3 { font-size: 1.5rem; }
    }
    
    /* Scope Cards */
    .scope-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }
    @media (max-width: 768px) {
        .scope-grid { grid-template-columns: 1fr; }
    }
    .scope-box {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px 15px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
        background: #fff;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }
    .scope-box:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
    }
    .scope-input:checked + .scope-box {
        border-color: #FB1159;
        background: #fff5f7;
        box-shadow: 0 4px 15px rgba(251, 17, 89, 0.1);
    }
    .scope-input:checked + .scope-box svg {
        color: #FB1159;
    }
    .scope-box svg {
        width: 32px; height: 32px;
        color: #64748b;
        transition: color 0.2s ease;
    }
    .scope-box span {
        font-weight: 600;
        color: #334155;
        font-size: 0.95rem;
    }
    
    /* Quiz Customizer */
    .slider-container {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 25px;
    }
    .stepper-control {
        display: flex;
        align-items: center;
        background: #fff;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
    }
    .stepper-control:focus-within {
        border-color: #FB1159;
    }
    .stepper-btn {
        background: #f8fafc;
        border: none;
        color: #64748b;
        font-size: 20px;
        font-weight: bold;
        width: 40px;
        height: 40px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    .stepper-btn:hover {
        background: #e2e8f0; color: #1e293b;
    }
    .stepper-input {
        width: 50px;
        text-align: center;
        border: none;
        background: transparent;
        font-size: 1.2rem;
        font-weight: 700;
        color: #FB1159;
        outline: none;
    }
    .stepper-input:focus {
        outline: none;
    }
    
    /* Estimated Time Box */
    .time-box {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        background: #fff;
        border: 1px solid #e2e8f0;
        padding: 20px;
        border-radius: 12px;
        height: 100%;
        margin-top: 15px;
    }
    @media (min-width: 768px) {
        .time-box { margin-top: 0; justify-content: flex-start; }
    }
    @media (max-width: 768px) {
        .slider-container { padding: 15px; }
        .time-box { padding: 15px; gap: 10px; margin-top: 20px; }
        .time-icon { width: 40px; height: 40px; }
        .time-icon svg { width: 24px; height: 24px; }
        .time-details h3 { font-size: 1.25rem; }
        .status-alert { padding: 12px 15px; margin-bottom: 20px; }
        .start-quiz-btn { padding: 14px; font-size: 1rem; }
    }
    .time-icon {
        width: 48px; height: 48px;
        background: #fff5f7;
        color: #FB1159;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .time-icon svg { width: 28px; height: 28px; }
    .time-details p { margin: 0; color: #64748b; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }
    .time-details h3 { margin: 0; color: #0f172a; font-size: 1.5rem; font-weight: 800; }
    
    /* Button */
    .start-quiz-btn {
        background: #FB1159;
        color: #fff;
        border: none;
        width: 100%;
        padding: 16px;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        display: flex; align-items: center; justify-content: center; gap: 10px;
        box-shadow: 0 4px 15px rgba(251, 17, 89, 0.2);
    }
    .start-quiz-btn:hover {
        background: #e00f4f;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(251, 17, 89, 0.3);
    }
    .start-quiz-btn:disabled {
        background: #cbd5e1;
        box-shadow: none;
        cursor: not-allowed;
        transform: none;
    }
    
    /* Instructions */
    .instruction-list {
        list-style: none; padding: 0; margin: 0;
    }
    .instruction-item {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        align-items: flex-start;
    }
    .instruction-item:last-child { margin-bottom: 0; }
    .instruction-bullet {
        width: 24px; height: 24px;
        background: #e0f2fe; color: #0284c7;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; margin-top: 2px;
    }
    .instruction-bullet svg { width: 14px; height: 14px; }
    .instruction-text h6 { margin: 0 0 4px 0; color: #334155; font-weight: 600; font-size: 0.95rem; }
    .instruction-text p { margin: 0; color: #64748b; font-size: 0.85rem; line-height: 1.5; }

    /* Alerts */
    .status-alert {
        padding: 16px 20px; border-radius: 12px; display: flex; align-items: center; gap: 15px; margin-bottom: 25px;
    }
    .status-alert.info { background: #eff6ff; border: 1px solid #bfdbfe; color: #1e3a8a; }
    .status-alert.success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
    .status-alert.danger { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
    .status-icon { width: 28px; height: 28px; flex-shrink: 0; }
    
    @keyframes spin { 100% { transform: rotate(360deg); } }
    .spin { animation: spin 1.5s linear infinite; }
    
    /* Custom Range */
    .custom-range {
        -webkit-appearance: none; width: 100%; height: 8px; border-radius: 4px; background: #cbd5e1; outline: none; margin: 20px 0;
    }
    .custom-range::-webkit-slider-thumb {
        -webkit-appearance: none; appearance: none; width: 22px; height: 22px; border-radius: 50%; background: #FB1159; cursor: pointer; box-shadow: 0 2px 6px rgba(251,17,89,0.3); border: 2px solid #fff;
    }
    
    /* Hide native radio button interference */
    .primary_radio { display: none !important; }
</style>
<div class="main_content_iner main_content_padding setup-wrapper">
    <div class="dashboard_lg_card">
        <div class="container-fluid no-gutters">
            <div class="row">
                <div class="col-12">
                    <div class="section__title3 margin_50 d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h3>Practice Quiz Setup</h3>
                            <p class="m-0">Customize your revision session for: <strong>{{ $course->title }}</strong></p>
                        </div>
                        <button onclick="window.close();" class="theme_btn text-white px-4 py-2" style="border-radius: 8px; border: none;">
                            <i class="fas fa-arrow-left mr-2"></i> {{ __('common.Back') }}
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-xl-8">
                    <form action="{{ route('practice-quiz.start') }}" method="POST" id="practice_setup_form">
                        @csrf
                        <input type="hidden" name="course_id" value="{{ $course->id }}" id="setup_course_id">
                        <input type="hidden" name="question_count" id="setup_question_count" value="10">
                        
                        <!-- Scope Section -->
                        <div class="setup-card">
                            <div class="setup-title">
                                <div class="title-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                </div>
                                1. Select Study Scope
                            </div>
                            
                            <div class="scope-grid mb-4">
                                <label class="m-0" style="position: relative;">
                                    <input type="radio" name="scope" value="course" class="scope-input" style="position: absolute; opacity: 0; width: 0; height: 0;" checked>
                                    <div class="scope-box">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                        <span>Entire Course</span>
                                    </div>
                                </label>
                                <label class="m-0" style="position: relative;">
                                    <input type="radio" name="scope" value="chapter" class="scope-input" style="position: absolute; opacity: 0; width: 0; height: 0;">
                                    <div class="scope-box">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></svg>
                                        <span>Specific Chapter</span>
                                    </div>
                                </label>
                                <label class="m-0" style="position: relative;">
                                    <input type="radio" name="scope" value="lesson" class="scope-input" style="position: absolute; opacity: 0; width: 0; height: 0;">
                                    <div class="scope-box">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        <span>Specific Lesson</span>
                                    </div>
                                </label>
                            </div>

                            <div id="chapter_selection" class="d-none">
                                <label class="font-weight-bold text-dark mb-2" style="font-size: 0.9rem;">Select Chapter</label>
                                <select class="primary_select w-100" name="chapter_id" id="setup_chapter_id">
                                    <option value="">Choose a chapter...</option>
                                    @foreach($course->chapters as $chapter)
                                        <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="lesson_selection" class="d-none">
                                <label class="font-weight-bold text-dark mb-2" style="font-size: 0.9rem;">Select Lesson</label>
                                <select class="primary_select w-100" name="lesson_id" id="setup_lesson_id">
                                    <option value="">Choose a lesson...</option>
                                    @foreach($course->chapters as $chapter)
                                        <optgroup label="{{ $chapter->name }}">
                                            @foreach($chapter->lessons as $lesson)
                                                <option value="{{ $lesson->id }}">{{ $lesson->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Quiz Length Section -->
                        <div class="setup-card">
                            <div class="setup-title">
                                <div class="title-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
                                </div>
                                2. Customize Quiz Length
                            </div>
                            
                            <div id="availability_info" class="status-alert info">
                                <svg class="status-icon spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                <div>
                                    <h6 class="mb-1 font-weight-bold" style="font-size: 0.95rem;">Scanning Question Pool...</h6>
                                    <p class="mb-0" style="font-size: 0.85rem; opacity: 0.8;">Checking available questions.</p>
                                </div>
                            </div>
                            
                            <div class="slider-container mb-4">
                                <div class="row align-items-center">
                                    <div class="col-md-7">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <h6 class="font-weight-bold mb-1 text-dark" style="font-size: 0.95rem;">Number of Questions</h6>
                                                <p class="mb-0 text-muted" style="font-size: 0.8rem;">Drag slider or type exact number</p>
                                            </div>
                                            <div class="stepper-control">
                                                <button type="button" class="stepper-btn" id="btn_minus">&minus;</button>
                                                <input type="text" class="stepper-input" id="question_count_display" value="10">
                                                <button type="button" class="stepper-btn" id="btn_plus">+</button>
                                            </div>
                                        </div>
                                        
                                        <div style="padding: 10px 0;">
                                            <input type="range" class="custom-range" id="question_range" min="1" max="20" value="10">
                                            <div class="d-flex justify-content-between" style="font-size: 0.8rem; color: #64748b; font-weight: 600;">
                                                <span>1 (Min)</span>
                                                <span id="range_max_label">20 (Max)</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-5">
                                        <div class="time-box">
                                            <div class="time-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            </div>
                                            <div class="time-details">
                                                <p>Est. Duration</p>
                                                <h3 id="setup_estimated_time">10 Min</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="start-quiz-btn" id="start_btn">
                                Start Practice Quiz
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="col-xl-4 mt-4 mt-xl-0">
                    <div class="setup-card" style="background: #f8fafc; border: 1px dashed #cbd5e1; box-shadow: none;">
                        <div class="setup-title mb-4" style="font-size: 1.2rem;">
                            How it works
                        </div>
                        
                        <ul class="instruction-list">
                            <li class="instruction-item">
                                <div class="instruction-bullet">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                </div>
                                <div class="instruction-text">
                                    <h6>Targeted Revision</h6>
                                    <p>Focus on exactly what you need to study by selecting specific chapters or lessons.</p>
                                </div>
                            </li>
                            <li class="instruction-item">
                                <div class="instruction-bullet">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                </div>
                                <div class="instruction-text">
                                    <h6>Time Managed</h6>
                                    <p>Adjust the number of questions to perfectly fit the free time you have available.</p>
                                </div>
                            </li>
                            <li class="instruction-item">
                                <div class="instruction-bullet">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                </div>
                                <div class="instruction-text">
                                    <h6>Unlimited Retakes</h6>
                                    <p>Practice makes perfect. You can retake these quizzes as many times as you like.</p>
                                </div>
                            </li>
                            <li class="instruction-item">
                                <div class="instruction-bullet">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                </div>
                                <div class="instruction-text">
                                    <h6>Track Progress</h6>
                                    <p>Passing the quiz (50% score) contributes to your overall course completion.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        var maxAvailable = 0;
        var currentCount = 10;

        function setCount(val) {
            val = parseInt(val);
            if (isNaN(val) || val < 1) val = 1;
            if (maxAvailable > 0 && val > maxAvailable) val = maxAvailable;
            currentCount = val;
            document.getElementById('setup_question_count').value = currentCount;
            document.getElementById('question_count_display').value = currentCount;
            document.getElementById('question_range').value = currentCount;
            document.getElementById('setup_estimated_time').innerText = currentCount + ' Min';
        }

        function updateAvailability() {
            var scope = $('input[name="scope"]:checked').val();
            var course_id = $('#setup_course_id').val();
            var chapter_id = $('#setup_chapter_id').val();
            var lesson_id = $('#setup_lesson_id').val();

            if (scope == 'chapter' && !chapter_id) return;
            if (scope == 'lesson' && !lesson_id) return;

            $('#availability_info').removeClass('danger success').addClass('info')
                .html('<svg class="status-icon spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg><div><h6 class="mb-1 font-weight-bold" style="font-size: 0.95rem;">Scanning Question Pool...</h6><p class="mb-0" style="font-size: 0.85rem; opacity: 0.8;">Checking available questions.</p></div>');
            $('#start_btn').prop('disabled', true);

            $.ajax({
                url: "{{ route('practice-quiz.available-count') }}",
                type: "GET",
                data: {
                    scope: scope,
                    course_id: course_id,
                    chapter_id: chapter_id,
                    lesson_id: lesson_id
                },
                success: function(res) {
                    maxAvailable = res.count;
                    if (maxAvailable > 0) {
                        $('#availability_info').removeClass('danger info').addClass('success')
                            .html('<svg class="status-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg><div><h6 class="mb-1 font-weight-bold" style="font-size: 0.95rem;">' + maxAvailable + ' Questions Available</h6><p class="mb-0" style="font-size: 0.85rem; opacity: 0.8;">Ready to generate your custom quiz.</p></div>');
                        
                        // Update slider max
                        document.getElementById('question_range').max = maxAvailable;
                        document.getElementById('range_max_label').innerText = maxAvailable + ' (Max)';

                        if (currentCount > maxAvailable) {
                            setCount(maxAvailable);
                        }
                        $('#start_btn').prop('disabled', false);
                    } else {
                        $('#availability_info').removeClass('info success').addClass('danger')
                            .html('<svg class="status-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg><div><h6 class="mb-1 font-weight-bold" style="font-size: 0.95rem;">No Questions Found</h6><p class="mb-0" style="font-size: 0.85rem; opacity: 0.8;">Please select another topic or chapter.</p></div>');
                        setCount(0);
                        $('#start_btn').prop('disabled', true);
                    }
                }
            });
        }

        // Minus button
        document.getElementById('btn_minus').addEventListener('click', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            if (currentCount > 1) {
                setCount(currentCount - 1);
            }
            return false;
        }, true);

        // Plus button
        document.getElementById('btn_plus').addEventListener('click', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            if (currentCount < maxAvailable) {
                setCount(currentCount + 1);
            }
            return false;
        }, true);

        // Range slider
        document.getElementById('question_range').addEventListener('input', function(e) {
            e.stopImmediatePropagation();
            setCount(this.value);
        }, true);

        // Manual text input bypass
        var displayInput = document.getElementById('question_count_display');
        var eventsToStop = ['mousedown', 'mouseup', 'click', 'selectstart', 'copy', 'paste', 'cut', 'contextmenu', 'keydown'];
        eventsToStop.forEach(function(ev) {
            displayInput.addEventListener(ev, function(e) {
                e.stopPropagation();
            }, true);
        });

        displayInput.addEventListener('input', function(e) {
            e.stopImmediatePropagation();
            var val = parseInt(this.value);
            if (!isNaN(val)) {
                if (maxAvailable > 0 && val > maxAvailable) val = maxAvailable;
                currentCount = val;
                document.getElementById('setup_question_count').value = currentCount;
                document.getElementById('question_range').value = currentCount;
                document.getElementById('setup_estimated_time').innerText = currentCount + ' Min';
            }
        }, true);

        displayInput.addEventListener('blur', function() {
            setCount(this.value);
        });

        // Scope radio buttons
        $('input[name="scope"]').on('change', function() {
            var val = $(this).val();
            $('#chapter_selection').addClass('d-none');
            $('#lesson_selection').addClass('d-none');

            if (val == 'chapter') {
                $('#chapter_selection').removeClass('d-none');
            } else if (val == 'lesson') {
                $('#lesson_selection').removeClass('d-none');
            }
            updateAvailability();
        });

        $('#setup_chapter_id, #setup_lesson_id').on('change', function() {
            updateAvailability();
        });

        // initial load
        updateAvailability();
    });
</script>
@endpush
