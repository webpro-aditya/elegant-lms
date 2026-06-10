@php use Illuminate\Support\Carbon;use Modules\BunnyStorage\Http\Controllers\BunnyStreamController; @endphp
@extends(theme('layouts.full_screen_master'))
@section('title')
    {{Settings('site_title')  ? Settings('site_title')  : 'Infix LMS'}} | {{ $course->title}}
@endsection
@section('css')
    <link href="{{assetPath('backend/css/jquery-ui.css')}}{{assetVersion()}}" rel="stylesheet">
    <link href="{{assetPath('frontend/infixlmstheme/css/full_screen.css')}}{{assetVersion()}}" rel="stylesheet"/>

    {{-- <link href="{{assetPath('frontend/infixlmstheme/css/class_details.css')}}{{assetVersion()}}" rel="stylesheet"/> --}}
    <link href="{{assetPath('backend/css/summernote-bs5.min.css')}}{{assetVersion()}}" rel="stylesheet">

    @if(isModuleActive("WhatsappSupport"))
        <link rel="stylesheet" href="{{assetPath('whatsapp-support/style.css')}}{{assetVersion()}}"/>
    @endif

    <style>
        .default-font {
            font-family: "Jost", sans-serif;
            font-weight: normal;
            font-style: normal;
            font-weight: 400;
        }

        .primary_checkbox {
            z-index: 99;
        }

        #fab-root {
            max-width: 50px;
        }

        @media (max-width: 767.98px) {
            .contact_btn {
                margin: 0 !important;
                justify-content: space-between;
            }

            #video-placeholder {
                height: 300px;
            }
        }

        .course__play_warp.courseListPlayer:before {
            background-color: transparent;
        }

        @media (max-width: 991.98px) {
            .mobile-min-height {
                height: 330px !important;
            }
        }

        #ExternalHeaderViewerChromeTopBars {
            display: none !important;
        }

        .quiz_questions_wrapper {
            height: 100%;
        }

        .question_number_lists {
            max-height: 320px;
            overflow: auto;
        }

        .logo_img {
            height: 50px !important;
        }

        @media (max-width: 991.98px) {
            .header_area .header__wrapper .header__left .logo_img img {
                padding: .5rem !important
            }
        }

        .inline-YTPlayer {
            height: auto !important;
        }

        .quiz_score_wrapper .quiz_test_body .score_view_wrapper {
            justify-content: space-around;
        }

        html[dir=rtl] .fa-angle-left,
        html[dir=rtl] .fa-angle-right {
            transform: scaleX(-1)
        }

        @media (max-width: 991px) {
            .course_fullview_wrapper .video_iframe {
                position: initial !important;
                height: 400px;
                width: 100%;
            }
        }

        @media (min-width: 576px) {
            .modal-dialog {
                max-width: 550px;
            }
        }

        @media (min-width: 1080px) {
            .modal-dialog {
                max-width: 800px;
            }
        }

        .conversition_box .single_comment_box .comment_box_inner .comment_box_info .comment_box_text span {
            font-size: 14px;
            font-weight: 400;
            margin-bottom: 10px;
            margin-top: 2px;
            display: block;
            color: #7b7887;
        }

        .header__common_btn {
            border: 0 !important;
        }

        .header__common_btn:hover {
            background-position: right !important;
            color: white !important;
        }

        /* Hover-triggered dropdown (desktop) */
        @media (min-width: 768px) {
            .header__common_btn.dropdown .dropdown-menu {
                display: block;
                opacity: 0;
                visibility: hidden;
                pointer-events: none;
                transition: opacity 0.2s ease, visibility 0.2s ease;
                margin-top: 0;
            }

            .header__common_btn.dropdown:hover .dropdown-menu {
                opacity: 1;
                visibility: visible;
                pointer-events: auto;
            }
        }

    </style>
    <style>
        .nice-select.quiz-select {
            padding-right: 50px;
            height: 30px;
        }

        html[dir="rtl"] .nice-select.quiz-select {
            padding-left: 50px;
            padding-right: 20px;
        }

        .select-wrapper .nice-select .current {
            line-height: 30px;
        }

        .question_title_quiz p {
            flex-wrap: wrap;
            vertical-align: middle;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        /* drawflow */

        .drawflow_content_node .image-preview {
            height: 60px;
            background: #f1f1f1;
            border-radius: 4px;
            overflow: hidden;
            border: 1px solid #D1D1D1;
        }

        .drawflow_content_node .image-preview img {
            height: 60px;
            width: 100px;
            object-fit: cover;
            min-width: 100px
        }

        .drawflow_content_node .option_title,
        .drawflow_content_node .ans_title {
            flex-grow: 1;
        }

        .drawflow_content_node .primary_label2 {
            gap: 10px;
        }

        /* .ansNode .drawflow_content_node .primary_label2{
            flex-direction: row-reverse;
        } */

        .drawflow_content_node .option_title,
        .drawflow_content_node .ans_title {
            height: 60px;
            font-size: 22px;
        }

        .drawflow {
            min-width: 900px;
            overflow: auto;
            max-width: 100%;
            width: 100%;
        }

        .drawflow .drawflow-node {
            width: calc(100% / 12 * 5) !important;
        }

        .parent-drawflow {
            overflow: auto !important;
        }

        @media (max-width: 767px) {
            .quiz_secondary_btn, .quiz_primary_btn {
                font-size: 16px;
                padding: 0px 20px;
                height: 50px;
                line-height: 50px;
            }

            .question_number_lists a {
                height: 40px;
                width: 40px;
                line-height: 40px;
                flex: 40px 0 0;
                font-size: 16px;
            }

            .question_number_lists {
                gap: 10px;
                margin-bottom: 10px;
            }

            .drawflow_content_node .option_title,
            .drawflow_content_node .ans_title {
                height: 50px;
                font-size: 22px;
            }

            .drawflow_content_node .image-preview img {
                height: 50px;
                width: 80px;
                min-width: 80px;
            }

            .drawflow_content_node .image-preview {
                height: 50px;
            }

            .drawflow {
                min-width: 600px;
            }
        }

        .sumit_skip_btns {
            margin-top: 20px;
        }

        .nice-select.quiz-select {
            padding-right: 30px;
        }

        .question_title_quiz p * {
            vertical-align: middle
        }

        html[dir='rtl'] .drawflow {
            direction: ltr;
        }

        html[dir='rtl'] .drawflow .connection {
            right: auto;
            left: 0;
        }

    </style>
    <style>
        .quiz_questions_wrapper .quiz_test_header .quiz_header_right p {
            color: #ffffff;
        }

        .course_fullview_wrapper:not(.video) {
            height: auto;
        }

        .multypol_qustion p {
            flex-wrap: wrap;
            vertical-align: middle;
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        html[dir="rtl"] .course_fullview_wrapper .course__play_warp {
            top: var(--top);
        }

        html[dir="rtl"] .header_area {
            position: fixed;
            padding-left: 0;
            padding-right: 0;
        }

        html[dir="rtl"] .floating-title {
            left: 0px;
            right: auto;
        }

        html[dir="rtl"] .lmsSwitch_toggle input:checked + .slider:before {
            transform: translateY(-50%) translateX(-18px);
        }

        .plyr.plyr--full-ui {
            height: 100%;
            width: 100%;
        }

        .plyr__controls__item.plyr__progress__container {
            flex-grow: 1;
        }

        .header_iner {
            display: none !important;
        }

        @media (max-width: 767px) {

            .header_iner {
                background: #fff !important;
                box-shadow: 0px 1px 10px rgb(0 0 0 / 10%);
                display: flex !important;
            }

            .notification_wrapper {
                background: #000000 !important;
            }

            .header__wrapper {
                padding: 10px 0 0 0 !important;
            }

            .header__left {
                display: none !important;
            }
        }
    /* PDF Toolbar Toggle */
    .pdftoolbar-toggle-wrapper {
        display: none;
        justify-content: center;
        width: 100%;
    }

    .pdftoolbar-toggle-btn {
        background: linear-gradient(90deg, #ff3c3c, #3c7cff);
        border: none;
        cursor: pointer;
        width: 44px;
        height: 20px;
        border-radius: 6px 6px 0 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 12px;
        padding: 0;
        transition: opacity 0.2s ease;
    }

    .pdftoolbar-toggle-btn:hover {
        opacity: 0.85;
    }

    .pdftoolbar-toggle-btn .pdftoolbar-arrow {
        display: inline-block;
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        transform: rotate(0deg);
    }

    /* Arrow flips UP when toolbar is hidden */
    .pdftoolbar-toggle-btn.collapsed .pdftoolbar-arrow {
        transform: rotate(180deg);
    }

    .pdftoolbar-slide-container {
        width: 100%;
        overflow: hidden;
        max-height: 120px;  /* ← was 80px, increase to fit both rows */
        transition: max-height 0.38s cubic-bezier(0.16, 1, 0.3, 1),
                    opacity 0.3s ease;
        opacity: 1;
    }

    .pdftoolbar-slide-container.pdftoolbar-hidden {
        /* max-height: 0 !important; */
        opacity: 0;
    }
    @media (max-width: 767px) {
        .pdftoolbar-toggle-wrapper {
            display: flex;
        }
        .pdftoolbar-slide-container.pdftoolbar-hidden {
            max-height: 0 !important;
        }
    }
    /* DESKTOP FIX */
    @media (min-width: 768px) {
        .course_fullview_wrapper {
            flex-direction: column;
        }

        .pdftoolbar-toggle-wrapper {
            display: none !important;
        }

        .pdftoolbar-slide-container {
            max-height: none !important;
            opacity: 1 !important;
            overflow: visible !important;
        }

        .pdftoolbar {
            display: block !important;
        }
    }

    /* Prevent text selection / copy protection on course view */
    body, html, .course_fullview_wrapper, .mainContent {
        -webkit-user-select: none !important;
        -moz-user-select: none !important;
        -ms-user-select: none !important;
        user-select: none !important;
    }

    /* Re-enable selection for inputs, textareas, and the PDF text layer (for highlighting) */
    input, textarea, [contenteditable="true"], .note-editable, #pdfOuterContainer, #pdfOuterContainer * {
        -webkit-user-select: text !important;
        -moz-user-select: text !important;
        -ms-user-select: text !important;
        user-select: text !important;
    }
    </style>
@endsection

@section('mainContent')
    @php
        $video_lesson_hosts=['Iframe','Image','PDF','Word','Excel','PowerPoint','Text','Zip','GoogleDrive','H5P','Editor'];
    @endphp
    @push('js')
        <script>
            $(document).on('click', '.showHistoryBtn', function (e) {
                e.preventDefault();
                $("#historyDiv").slideToggle('slow', function() {
                    if ($("#historyDiv").is(':visible')) {
                        $('.course_fullview_wrapper').animate({
                            scrollTop: $("#historyDiv").offset().top + $('.course_fullview_wrapper').scrollTop() - 100
                        }, 500);
                        
                        // Fallback to body scroll if wrapper isn't the scroll container
                        $('html, body').animate({
                            scrollTop: $("#historyDiv").offset().top - 100
                        }, 500);
                    }
                });
            });
        </script>
        <script>
            var completeRequest = false;
        </script>
        <script>
            // Prevent Right-Click / Context Menu
            document.addEventListener('contextmenu', function(e) {
                e.preventDefault();
            });

            // Prevent Copy and Cut events
            document.addEventListener('copy', function(e) {
                e.preventDefault();
            });
            document.addEventListener('cut', function(e) {
                e.preventDefault();
            });

            // Prevent Drag and Drop (prevents dragging images/videos to desktop/new tab to save)
            document.addEventListener('dragstart', function(e) {
                e.preventDefault();
            });

            // Prevent DevTools Keyboard Shortcuts
            document.addEventListener('keydown', function(e) {
                // F12 key
                if (e.keyCode === 123) {
                    e.preventDefault();
                    return false;
                }
                // Ctrl+Shift+I or Cmd+Opt+I (Chrome, Firefox, Safari DevTools)
                if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.keyCode === 73) {
                    e.preventDefault();
                    return false;
                }
                // Ctrl+Shift+J or Cmd+Opt+J (Console)
                if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.keyCode === 74) {
                    e.preventDefault();
                    return false;
                }
                // Ctrl+Shift+C or Cmd+Opt+C (Inspect Element)
                if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.keyCode === 67) {
                    e.preventDefault();
                    return false;
                }
                // Ctrl+U or Cmd+Opt+U (View Source)
                if ((e.ctrlKey || e.metaKey) && e.keyCode === 85) {
                    e.preventDefault();
                    return false;
                }
                // Ctrl+S or Cmd+S (Save Page - prevents page saving/downloading)
                if ((e.ctrlKey || e.metaKey) && e.keyCode === 83) {
                    e.preventDefault();
                    return false;
                }
                // Ctrl+P or Cmd+P (Print Page)
                if ((e.ctrlKey || e.metaKey) && e.keyCode === 80) {
                    e.preventDefault();
                    return false;
                }
            });
        </script>
    @endpush

    @php
        if ($lesson->lessonQuiz->random_question==1){
        $questions =$lesson->lessonQuiz->assignRand;
        }else{
        $questions =$lesson->lessonQuiz->assign;
       }
    @endphp

    <script>
        @if(auth()->check())
            window.full_name = "{{auth()->user()->name}}";
        window.course_name = "{{ $course->title}}";
        @if(isModuleActive('Org'))
            window.org_chart_name = "{{auth()->user()->branch->group}}";
        @endif
            @else
            window.full_name = "Guest";
        window.course_name = "{{ $course->title}}";
        @if(isModuleActive('Org'))
            window.org_chart_name = "";
        @endif
        @endif
    </script>
    <header>
        <div id="sticky-header" class="header_area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="header__wrapper flex-wrap">
                            <div class="header__left d-flex align-items-center">
                                <div class="">
                                    <a class="logo_img" href="{{url('/')}}">
                                        <img class="p-2" src="{{getLogoImage(Settings('logo') )}}" width="150"
                                             alt="{{ Settings('site_name')  }}">
                                    </a>
                                </div>
                                <div class="category_search d-none d-lg-flex category_box_iner">
                                    <div class="input-group-prepend2 ps-3 ">
                                        <a class="headerTitle"
                                           href="{{ courseDetailsUrl($course->id, $course->type, $course->slug) }}">
                                            <h4 class="headerTitle">{{ $course->title }}</h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="header__right">
                                <div class="contact_wrap d-flex align-items-center flex-wrap mx-0">
                                    <div class="contact_btn d-flex align-items-center flex-wrap">
                                        @if (in_array($lesson->host, $video_lesson_hosts))
                                            <button
                                                class="theme_btn small_btn2 p-2 me-2 mr-lg-4 fs-14 completeAndPlayNext">
                                                {{ __('frontend.Mark as Complete') }}</button>
                                        @endif
                                        @if (isset($lessons))
                                            <div class="d-flex aling-items-center">
                                                <label class="lmsSwitch_toggle pe-2" for="autoNext">
                                                    <input type="checkbox" id="autoNext" checked>
                                                    <div class="slider round"></div>
                                                </label>
                                                <span class="ps-2 text-nowrap">{{ __('frontend.Auto Next') }}</span>
                                            </div>
                                            <div class="pl-20 text-end ms-3 d-flex align-items-center flex-row">
                                                @php
                                                    $last_key = array_key_last($lesson_ids);
                                                    $last_previous_one = array_key_last($lesson_ids) - 1;
                                                    $current_page = (int) showPicName(Request::url());

                                                    $current_index = array_search(showPicName(Request::url()), $lesson_ids);
                                                @endphp
                                                @if (0 == array_search($current_page, $lesson_ids))
                                                    <a href="#" disabled="disabled"
                                                       class="header__common_btn theme_button_disabled disabled">
                                                        <i class="fa fa-angle-left"></i>
                                                    </a>
                                                @else
                                                    <a href="#"
                                                       onclick="goFullScreen({{ $course->id }},{{ $lesson_ids[$current_index - 1] }})"
                                                       class="header__common_btn"><i class="fa fa-angle-left"></i>
                                                    </a>
                                                @endif
                                                @if (array_search($current_page, $lesson_ids) < array_search(end($lesson_ids), $lesson_ids))
                                                    <a href="#" id="next_lesson_btn"
                                                       onclick="goFullScreen({{ $course->id }},{{ $lesson_ids[$current_index + 1] }})"
                                                       class="header__common_btn ms-2">
                                                        <i class="fa fa-angle-right"></i>
                                                    </a>
                                                @else
                                                    <a href="#" disabled
                                                       class="header__common_btn theme_button_disabled ms-2 disabled"
                                                       style="opacity: 1">
                                                        <i class="fa fa-angle-right"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                        <a href="javascript:void(0)" class="ms-2 mobile_progress">
                                            <div class="progress p-2" data-percentage="{{ $percentage }}">
                                                <span class="progress-left">
                                                    <span class="progress-bar"></span>
                                                </span>
                                                <span class="progress-right">
                                                    <span class="progress-bar"></span>
                                                </span>
                                                <div class="progress-value">
                                                    <div class="headerSubProcess">
                                                        {{ $percentage }}%
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="header__common_btn dropdown ms-2">
                                            <button
                                                class="d-block w-100 h-100 bg-transparent border-0 outline-none border-0 p-0 currentColor js-hover-dropdown-toggle"
                                                type="button">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                       data-bs-target="#ShareLink"><i
                                                            class="fa fa-share fs-12 me-2"></i>{{ __('frontend.Share') }}
                                                    </a></li>
                                                @if (Auth::check())
                                                    @if (Auth::user()->role_id == 3)
                                                        @if (!in_array(Auth::user()->id, $reviewer_user_ids))
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                   data-bs-target="#courseRating" class="dropdown-item">
                                                                    <i class="fa fa-star me-2 fs-12"></i>{{ __('frontend.Leave a rating') }}
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endif
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    @include(theme('snippets.floating-menu'))

    <div class="course_fullview_wrapper {{$lesson->is_quiz == 1 ? '' : 'video'}} {{$lesson->host == 'Editor' ? 'flex-column justify-content-start p-4' : ''}}">
        @if ($lesson->is_quiz == 1)
            @if (count($result) != 0)
                <div class="quiz_score_wrapper w-100 mt_70">
                    @if (!isset($_GET['done']))
                        <div class="quiz_test_header">
                            <h3>{{ __('student.Your Exam Score') }}</h3>
                        </div>
                        <div class="quiz_test_body">
                            {{-- <h3>{{ __('student.Congratulations! You’ve completed') }} {{ $course->quiz->title }}</h3> --}}



                            @if ($result['publish'] == 1)

                                @if ($result['status'] != 'Failed')
                                    <h3 class="success">{{__('student.Congratulations!')}}</h3>
                                @else
                                    <h3 class="failed">
                                        <span>{{__('frontend.You have failed')}}.</span> {{__('frontend.Wishing you luck the next time')}}
                                    </h3>
                                @endif
                                <p class="subtitle">{{__('frontend.You have completed')}} {{$lesson->lessonQuiz->title}}</p>

                                <div class="">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="score_view_wrapper">
                                                <div class="single_score_view">
                                                    {{-- <p>{{ __('student.Exam Score') }}:</p> --}}
                                                    <ul class="quiz_exam_score_details">
                                                        <li class="correct">
                                                            <label class="primary_checkbox2 d-flex">
                                                                <input checked="" type="checkbox" disabled>
                                                                <div class="icon">
                                                                    <svg width="15" height="15" viewBox="0 0 15 15"
                                                                         fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M1.61719 9.18847L5.78764 13.4892C7.75009 7.85194 9.38446 5.37824 13.0859 2.02051"
                                                                            stroke="currentColor" stroke-width="3"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"></path>
                                                                    </svg>
                                                                </div>
                                                                <span class="label_name">{{ $result['totalCorrect'] }}
                                                                    {{ __('student.Correct Answer') }}</span>
                                                            </label>
                                                        </li>
                                                        <li class="wrong">
                                                            <label class="primary_checkbox2 error_ans d-flex">
                                                                <input checked="" name="qus" type="checkbox"
                                                                       disabled>
                                                                <div class="icon">
                                                                    <svg width="12" height="12" viewBox="0 0 12 12"
                                                                         fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M1.75781 10.2427L10.2431 1.75739"
                                                                              stroke="currentColor" stroke-width="2"
                                                                              stroke-linecap="round"
                                                                              stroke-linejoin="round"></path>
                                                                        <path d="M10.2431 10.2426L1.75781 1.75732"
                                                                              stroke="currentColor" stroke-width="2"
                                                                              stroke-linecap="round"
                                                                              stroke-linejoin="round"></path>
                                                                    </svg>

                                                                </div>
                                                                <span class="label_name">{{ $result['totalWrong'] }}
                                                                    {{ __('student.Wrong Answer') }}</span>
                                                            </label>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div class="single_score_view d-flex">
                                                    <div>
                                                        <div>
                                                            <p>{{__('student.Exam Score')}}: {{ $result['score'] }}
                                                                Out of {{ $result['totalScore'] }}
                                                            </p>
                                                        </div>
                                                        <div>
                                                            <p>
                                                                {{__('frontend.Result')}}:
                                                                <span>
                                                                    @if ($result['status'] != 'Failed')
                                                                        {{ __('frontend.Passed') }}
                                                                    @else
                                                                        {{ __('frontend.Failed') }}
                                                                    @endif
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="sumit_skip_btns d-flex gap-3 align-items-center flex-wrap">
                                    <button type="button"
                                            onclick="javascript:history.back()"
                                            class="quiz_secondary_btn border-0 d-flex align-items-center gap-1">
                                        <i class="fa fa-arrow-left"></i> {{ __('common.Back') }}
                                    </button>
                                    @if (isset($result) && $result['status'] != 'Failed')
                                        <form action="{{ route('lesson.complete') }}" method="post">
                                            @csrf
                                            <input type="hidden" value="{{ $course->id }}" name="course_id">
                                            <input type="hidden" value="{{ $lesson->id }}" name="lesson_id">
                                            <input type="hidden" value="1" name="status">
                                            <button type="submit"
                                                    class="quiz_primary_btn border-0">{{ __('student.Done') }}</button>
                                        </form>
                                    @endif
                                    @if (count($preResult) != 0)
                                        <button type="button"
                                                class="theme_line_btn showHistoryBtn quiz_secondary_btn border-0">{{ __('frontend.View History') }}</button>
                                    @endif
                                    <a href="{{ $lesson->lessonQuiz->show_ans_sheet == 1 ? route('quizResultPreview', $_GET['quiz_result_id'] ?? 0) : '#' }}"
                                       data-quiz_test_id="{{ $_GET['quiz_result_id'] ?? 0 }}"
                                       title="{{ $lesson->lessonQuiz->show_ans_sheet != 1 ? __('quiz.Answer Sheet is currently locked by Teacher') : '' }}"
                                       class=" font_1 font_16 f_w_600 theme_text3 quiz_secondary_btn ">{{ __('student.See Answer Sheet') }}</a>
                                </div>
                            @else
                                <h3>{{ __('quiz.Please wait till completion marking process') }}</h3>
                            @endif


                            <div id="historyDiv" class="pt-5 " style="display:none;">
                                @if (count($preResult) != 0)
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>{{ __('common.Date') }}</th>
                                            <th>{{ __('quiz.Marks') }}</th>
                                            <th>{{ __('quiz.Percentage') }}</th>
                                            <th>{{ __('common.Rating') }}</th>
                                            <th>{{ __('common.Details') }}</th>
                                        </tr>
                                        @foreach ($preResult as $pre)
                                            <tr>
                                                <td>{{ $pre['date'] }}</td>
                                                <td>{{ $pre['score'] }}/{{ $pre['totalScore'] }}</td>
                                                <td>{{ $pre['mark'] }}%</td>
                                                <td class="{{ $pre['text_color'] }}">
                                                    @if ($pre['status'] != 'Failed')
                                                        {{ __('frontend.Passed') }}
                                                    @else
                                                        {{ __('frontend.Failed') }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ $lesson->lessonQuiz->show_ans_sheet == 1 ? route('quizResultPreview', $pre['quiz_test_id']) : '#' }}"
                                                       data-quiz_test_id="{{ $pre['quiz_test_id'] }}"
                                                       title="{{ $lesson->lessonQuiz->show_ans_sheet != 1 ? __('quiz.Answer Sheet is currently locked by Teacher') : '' }}"
                                                       class=" font_1 font_16 f_w_600 theme_text3    @if ($lesson->lessonQuiz->show_ans_with_explanation == 1)
                                       submit_q_btn
                                       @endif ">{{ __('student.See Answer Sheet') }}</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                @endif
                            </div>
                            @if ($lesson->lessonQuiz->show_ans_with_explanation == 1)
                                <div class="mt-3">
                                    <x-quiz-details-question-list :quiz="$lesson->lessonQuiz"/>
                                </div>
                            @endif
                        </div>
                    @else
                        <h3 class="text-center">{{ __('student.Congratulations! You’ve completed') }}
                            {{ $lesson->lessonQuiz->title }}</h3>

                    @endif
                </div>
            @else
                <div class="quiz_questions_wrapper w-100 ms-5 me-5">
                    <!-- quiz_test_header  -->

                    @if ($alreadyJoin != 0 && $lesson->lessonQuiz->multiple_attend == 0)
                        <div class="quiz_test_header d-flex justify-content-between align-items-center">
                            <div class="quiz_header_left text-center">
                                <h3>{{ __('frontend.Sorry! You already attempted this quiz') }}</h3>
                            </div>


                        </div>
                    @else
                        <div class="quiz_test_header d-flex justify-content-between align-items-center">
                            <div class="quiz_header_left">
                                <h3>{{ $lesson->lessonQuiz->title }}
                                </h3>
                            </div>

                            <div class="quiz_header_right">
                                <span class="question_time">
                                <span>{{__('frontend.Remaining')}}:</span>
                                <span>
                                    @php
                                        $timer = 0;

                                        if (!empty($lesson->lessonQuiz->question_time_type == 1)) {
                                            $timer = $lesson->lessonQuiz->question_time;
                                        } else {
                                            $timer = $lesson->lessonQuiz->question_time * count($questions);
                                        }

                                    @endphp

                                    <span id="timer">{{ $timer }}:00</span> {{ __('quiz.Min') }}</span>
                                </span>
                                {{-- <p>{{ __('student.Left of this Section') }}</p> --}}
                            </div>
                        </div>
                        <form action="{{ route('quizSubmit') }}" method="POST" id="quizForm">
                            <input type='hidden' name="from" value="course">
                            <input type="hidden" name="courseId" value="{{ $course->id }}">
                            <input type="hidden" name="quizType" value="1">
                            <input type="hidden" name="quizId" value="{{ $lesson->lessonQuiz->id }}">
                            <input type="hidden" name="question_review" id="question_review"
                                   value="{{ $lesson->lessonQuiz->question_review }}">
                            <input type="hidden" name="start_at" value="">
                            <input type="hidden" name="quiz_test_id" value="">
                            <input type="hidden" name="quiz_start_url" value="{{ route('quizTestStart') }}">
                            <input type="hidden" name="single_quiz_submit_url" value="{{ route('singleQuizSubmit') }}">
                            @csrf

                            <div class="quiz_test_body ">
                                <div class="tabControl">
                                    <div class="row">
                                        <div class="col-xl-12">

                                            @php
                                                $count2 = 1;
                                            @endphp

                                            {{--<div class="question_list_header">
                                                <div class="question_list_top">
                                                    <p>{{ __('quiz.Question') }} <span
                                                            id="currentNumber">{{ $count2 }}</span>
                                                        {{ __('common.out of') }} {{ count($questions) }}</p>
                                                </div>
                                            </div>
                                            <div class="nav question_number_lists" id="nav-tab" role="tablist">
                                                @if (isset($questions))
                                                    @foreach ($questions as $key2 => $assign)
                                                        <a class="nav-link questionLink link_{{ $assign->id }} {{ $key2 == 0 ? 'skip_qus' : 'pouse_qus' }}"
                                                           data-bs-toggle="tab" href="#pills-{{ $assign->id }}"
                                                           role="tab" aria-controls="nav-home"
                                                           data-qus="{{ $assign->id }}"
                                                           aria-selected="true">{{ $count2 }}</a>
                                                        @php
                                                            $count2++;
                                                        @endphp
                                                    @endforeach
                                                @endif
                                            </div>--}}

                                            @include(theme('partials._quiz_navigation'),compact('questions'))

                                        </div>

                                        <div class="col-md-12">
                                            <div class="tab-content" id="pills-tabContent">
                                                @php
                                                    $count = 1;
                                                @endphp
                                                @if (isset($questions))
                                                    @foreach ($questions as $key => $assign)
                                                        @php
                                                            $options = [];
    if (isset($assign->questionBank->questionMu)) {
if ($assign->questionBank->shuffle==1){
    $options = $assign->questionBank->questionMu;
}else{
    $options = $assign->questionBank->questionMuInSerial;
}
                                                                                                                          }
                                                        @endphp
                                                        <div
                                                            class="tab-pane fade  {{ $key == 0 ? 'active show' : '' }} singleQuestion"
                                                            data-qus-id="{{ $assign->id }}"
                                                            data-qus-type="{{ $assign->questionBank->type }}"
                                                            id="pills-{{ $assign->id }}" role="tabpanel"
                                                            aria-labelledby="pills-home-tab{{ $assign->id }}">
                                                            <div class="question_list_header">

                                                            </div>
                                                            <div class="multypol_qustion mb_30">
                                                                <h4 class="font_18 f_w_700 mb-0">
                                                                    @if(@$assign->questionBank->type=="C")
                                                                        {!! getClozeOptions(@$assign->questionBank) !!}
                                                                    @else
                                                                        {!! @$assign->questionBank->question !!}
                                                                    @endif
                                                                </h4>
                                                                @if($assign->questionBank->type=="M" && @$quiz->show_total_correct_answer == 1)
                                                                    <small>({{ __('quiz.Choose') }} <span
                                                                            class="questionAnsTotal text-danger fw-bold">
                                                                                        {{ count($options->where('status', 1)) }}</span>
                                                                        @if (count($options->where('status', 1)) <= 1)
                                                                            {{ __('quiz.answer') }})
                                                                        @else
                                                                            {{ __('quiz.answers') }})
                                                                        @endif
                                                                    </small>
                                                                @endif
                                                            </div>
                                                            <input type="hidden" class="question_type"
                                                                   name="type[{{ $assign->questionBank->id }}]"
                                                                   value="{{ @$assign->questionBank->type }}">
                                                            <input type="hidden" class="question_id"
                                                                   name="question[{{ $assign->questionBank->id }}]"
                                                                   value="{{ @$assign->questionBank->id }}">

                                                            {{--                                                            @if ($assign->questionBank->type == 'M')--}}
                                                            {{--                                                                <ul class="quiz_select">--}}
                                                            {{--                                                                    @if (isset($assign->questionBank->questionMu))--}}
                                                            {{--                                                                        @foreach (@$assign->questionBank->questionMu as $option)--}}
                                                            {{--                                                                            <li>--}}
                                                            {{--                                                                                <label--}}
                                                            {{--                                                                                    class="primary_bulet_checkbox d-flex">--}}
                                                            {{--                                                                                    <input class="quizAns"--}}
                                                            {{--                                                                                           name="ans[{{ $option->question_bank_id }}][]"--}}
                                                            {{--                                                                                           type="checkbox"--}}
                                                            {{--                                                                                           value="{{ $option->id }}">--}}

                                                            {{--                                                                                    <span--}}
                                                            {{--                                                                                        class="checkmark mr_10"></span>--}}
                                                            {{--                                                                                    <span--}}
                                                            {{--                                                                                        class="label_name">{{ $option->title }}--}}
                                                            {{--                                                                                    </span>--}}
                                                            {{--                                                                                </label>--}}
                                                            {{--                                                                            </li>--}}
                                                            {{--                                                                        @endforeach--}}
                                                            {{--                                                                    @endif--}}
                                                            {{--                                                                </ul>--}}
                                                            {{--                                                            @else--}}
                                                            {{--                                                                <div style="margin-bottom: 20px;">--}}
                                                            {{--                                                                    <textarea class="textArea lms_summernote quizAns"--}}
                                                            {{--                                                                              id="editor{{ $assign->id }}" cols="30"--}}
                                                            {{--                                                                              rows="10"--}}
                                                            {{--                                                                              name="ans[{{ $assign->questionBank->id }}]"></textarea>--}}
                                                            {{--                                                                </div>--}}
                                                            {{--                                                            @endif--}}
                                                            @php
                                                                $qusBank=$assign->questionBank;
                                                                   $already = null;
                                                            @endphp

                                                            @if($assign->questionBank->type=="M")
                                                                @include(theme('partials._quiz_multiple_type'),compact('qusBank','already'))
                                                            @elseif($assign->questionBank->type=="O")
                                                                @include(theme('partials._quiz_sorting_type'),compact('qusBank','already'))
                                                            @elseif($assign->questionBank->type=="X")
                                                                @include(theme('partials._quiz_matching_type'),compact('qusBank','already'))
                                                            @elseif($assign->questionBank->type=="P")
                                                                @php
                                                                    $puzzleQus = $options->where('type',1);
                                                                $puzzleAns = $options->where('type',0);
                                                                @endphp
                                                                @include(theme('partials._quiz_puzzle_type'),compact('qusBank','already'))
                                                            @elseif($assign->questionBank->type=="S" || $assign->questionBank->type=="L")
                                                                <div style="margin-bottom: 20px;">
                                                                                <textarea
                                                                                    class="textArea lms_summernote quizAns"
                                                                                    id="editor{{ $assign->id }}"
                                                                                    cols="30" rows="10"
                                                                                    name="ans[{{ $assign->questionBank->id }}]"></textarea>
                                                                </div>
                                                            @endif

                                                            @if (!empty($assign->questionBank->image))
                                                                <div class="ques_thumb mb_50 mt-4">
                                                                    <img src="{{assetPath($assign->questionBank->image) }}"
                                                                         class="img-fluid" alt="">
                                                                </div>
                                                            @endif
                                                            <div
                                                                class="sumit_skip_btns d-flex align-items-center mb_50">
                                                                @if (count($questions) != $count)
                                                                    <span class="quiz_primary_btn  mr_20 next"
                                                                          data-question_id="{{ $assign->questionBank->id }}"
                                                                          data-assign_id="{{ $assign->id }}"
                                                                          data-question_type="{{ $assign->questionBank->type }}"
                                                                          id="next">{{ __('student.Continue') }}</span>
                                                                    <span
                                                                        class=" font_1 font_16 f_w_600 theme_text3 submit_q_btn skip quiz_secondary_btn"
                                                                        id="skip">{{ __('student.Skip') }}
                                                                        {{ __('frontend.Question') }}</span>
                                                                @else
                                                                    <button type="button"
                                                                            data-question_id="{{ $assign->questionBank->id }}"
                                                                            data-assign_id="{{ $assign->id }}"
                                                                            data-question_type="{{ $assign->questionBank->type }}"
                                                                            class="submitBtn theme_btn small_btn  mr_20 quiz_primary_btn">
                                                                        {{ __('student.Submit') }}
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @php
                                                            $count++;
                                                        @endphp
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>



                @include(theme('partials._quiz_submit_confirm_modal'))
                @include(theme('partials._quiz_start_confirm_modal'))
            @endif

        @elseif($lesson->is_assignment == 1)
            @if (isModuleActive('Assignment'))

                @php

                    $assignment_info = $lesson->assignmentInfo;
                    if (Auth::check()) {
                        $submit_info = Modules\Assignment\Entities\InfixSubmitAssignment::assignmentLastSubmitted($assignment_info->id, Auth::user()->id);

                        if (Auth::user()->role_id == 1) {
                            $sty = '-150px';
                        } else {
                            if ($submit_info != null) {
                                $sty = '50px';
                            } else {
                                $sty = '280px';
                            }
                        }
                    } else {
                        $submit_info = null;
                        if ($submit_info != null) {
                            $sty = '50px';
                        } else {
                            $sty = '280px';
                        }
                    }
                @endphp
                <div class="col-lg-12 ps-5">

                    <style>
                        .assignment_info {
                            margin-top: 10px;
                        }
                    </style>
                    <div class="table-responsive-md table-responsive-sm assignment-info-table">
                        <table class="table">
                            <thead>
                            <h3 class="mb-0 ">{{ __('assignment.Assignment') }} {{ __('common.Details') }}</h3>
                            </thead>
                            <tr class="nowrap">
                                <td>
                                    {{ __('common.Title') }}
                                </td>
                                <td>
                                    : {{ @$assignment_info->title }}
                                </td>
                                <td>
                                    {{ __('courses.Course') }}
                                </td>
                                <td>
                                    @if ($assignment_info->course->title)
                                        : {{ @$assignment_info->course->title }}
                                    @else
                                        : {{ __('frontend.Not Assigned') }}
                                    @endif
                                </td>
                            </tr>
                            <tr class="nowrap">
                                <td>
                                    {{ __('assignment.Marks') }}
                                </td>
                                <td>
                                    : {{ @$assignment_info->marks }}
                                </td>
                                <td>
                                    {{ __('assignment.Min Percentage') }}
                                </td>
                                <td>
                                    : {{ @$assignment_info->min_parcentage }}%
                                </td>
                            </tr>
                            @if ($submit_info != null)
                                <tr class="nowrap">
                                    <td>
                                        {{ __('assignment.Obtain Marks') }}
                                    </td>
                                    <td>
                                        : {{ @$submit_info->marks }}
                                    </td>
                                    <td>
                                        {{ __('common.Status') }}
                                    </td>
                                    <td>
                                        :

                                        @if ($submit_info->assigned->pass_status == 1)
                                            {{ __('frontend.Pass') }}
                                        @elseif($submit_info->assigned->pass_status == 2)
                                            {{ __('frontend.Fail') }}
                                        @else
                                            {{ __('frontend.Not Marked') }}
                                        @endif
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <td>
                                    {{ __('assignment.Submit Date') }}
                                </td>
                                <td>
                                    : {{ showDate(@$assignment_info->last_date_submission) }}
                                </td>
                                <td>
                                    {{ __('assignment.Attachment') }}
                                </td>
                                <td>
                                    @if (fileExists($assignment_info->attachment))
                                        : <a href="{{assetPath(@$assignment_info->attachment) }}"
                                             download="{{ @$assignment_info->title }}_attachment">{{ __('common.Download') }}</a>
                                    @endif
                                </td>
                            </tr>

                        </table>
                    </div>


                    <div class="row assignment_info">
                        <div class="col-lg-2">
                            {{ __('assignment.Description') }}
                        </div>
                        <div class="col-lg-12">
                            {!! @$assignment_info->description !!}
                        </div>
                    </div>

                    @php
                        $todate = today()->format('Y-m-d');
                    @endphp
                    @if (empty($submit_info))
                        @if (isset($assignment_info->last_date_submission) && Auth::user()->role_id == 3)
                            @if (
                                $todate <= $assignment_info->last_date_submission ||
                                    (isset($submit_info) && $submit_info->assigned->pass_status == 0))
                                @include(theme('partials._assignment_submit_section'))
                            @endif
                        @else
                            @if (isset($submit_info) && $submit_info->assigned->pass_status == 0 && Auth::user()->role_id == 3)
                                @include(theme('partials._assignment_submit_section'))
                            @endif
                        @endif
                    @endif

                </div>
            @endif
        @else

            <script>
                const course_id = "{{ $lesson->course_id }}"
            </script>


            @push('js')
                <script>
                    $("#autoNext").change(function () {
                        if ($(this).is(':checked')) {
                            localStorage.setItem('autoNext', 1);
                        } else {
                            localStorage.setItem('autoNext', 0);

                        }

                    });
                    if (localStorage.getItem('autoNext') == 0) {
                        $("#autoNext").prop('checked', false);
                    }
                    $("#autoNext").trigger('change');

                    function lessonAutoComplete(course_id, lesson_id) {
                        let status = $('#single_lesson_' + lesson_id).find('[type=checkbox]');
                        if (status.is(":checked")) {
                            return true;
                        }
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });


                        $.ajax({
                            type: 'GET',
                            "_token": "{{ csrf_token() }}",
                            url: '{{ route('lesson.complete.ajax') }}',
                            data: {
                                course_id: course_id,
                                lesson_id: lesson_id
                            },
                            success: function (data) {
                                if ($('#autoNext').is(':checked')) {
                                    @if (isModuleActive('Org') && $lesson->host == 'SCORM')
                                    $('#single_lesson_' + lesson_id).find('[type=checkbox]').prop('checked', true);
                                    @else
                                    reaload();
                                    @endif

                                }

                            }
                        });

                        function reaload() {
                            if ($('#next_lesson_btn').length) {
                                jQuery('#next_lesson_btn').click();
                            } else {
                                location.reload();
                            }
                        }

                        if (window.outerWidth < 425) {
                            $('.courseListPlayer').toggleClass("active");
                            $('.course_fullview_wrapper').toggleClass("active");
                        }
                    }
                </script>
            @endpush



            @if ($lesson->host == 'Vimeo')
                @php
                    if (Str::contains($lesson->video_url, '&')) {
                        $video_id = explode('=', $lesson->video_url);
                        $video_id = youtubeVideo($video_id[1]);
                    } else {
                        $video_id = getVideoId(showPicName(@$lesson->video_url));
                    }
                @endphp

                <div id="video-id" data-plyr-provider="{{strtolower($lesson->host)}}"
                     data-plyr-embed-id="{{$video_id}}"></div>

            @endif


            @if ($lesson->host == 'VdoCipher')
                <div id="embedBox" class="video_iframe"></div>

                <script>
                    (function (v, i, d, e, o) {
                        v[o] = v[o] || {
                            add: function V(a) {
                                (v[o].d = v[o].d || []).push(a);
                            }
                        };
                        if (!v[o].l) {
                            v[o].l = 1 * new Date();
                            a = i.createElement(d);
                            m = i.getElementsByTagName(d)[0];
                            a.async = 1;
                            a.src = e;
                            m.parentNode.insertBefore(a, m);
                        }
                    })(
                        window,
                        document,
                        "script",
                        "https://cdn-gce.vdocipher.com/playerAssets/1.6.10/vdo.js",
                        "vdo"
                    );
                    vdo.add({
                        otp: "{{ $lesson->otp }}",
                        playbackInfo: "{{ $lesson->playbackInfo }}",
                        theme: "9ae8bbe8dd964ddc9bdb932cca1cb59a",
                        container: document.querySelector("#embedBox"),
                        autoplay: true
                    });
                </script>

                <script>
                    var isRedirect = false;

                    function onVdoCipherAPIReady() {


                        let video = vdo.getObjects()[0];


                        setInterval(function () {
                            if (video.ended) {
                                if (!isRedirect) {
                                    if (!completeRequest) {
                                        lessonAutoComplete(course_id, {{ showPicName(Request::url()) }});
                                        completeRequest = true;
                                    }
                                    isRedirect = true;
                                }
                            }
                        }, 1000);
                    }
                </script>
            @endif

            @if (isModuleActive('BunnyStorage') && $lesson->host == 'BunnyStorage')
                @php
                    $time = Carbon::now()
                        ->addDay(1)
                        ->unix();
                    if ($lesson->bunnyLesson && $lesson->bunnyLesson->service_type == 'stream') {
                        $url = 'https://iframe.mediadelivery.net/embed/' . $lesson->bunnyLesson->library_id . '/' . $lesson->bunnyLesson->video_id;
                        $sha256 = hash('sha256', $lesson->bunnyLesson->token_authentication_key . $lesson->bunnyLesson->video_id . $time);
                        $url .= '?token=' . $sha256 . '&expires=' . $time . '&autoplay=true&preload=true';
                        $lesson_src = $url;
                    } elseif ($lesson->bunnyLesson && $lesson->bunnyLesson->service_type == 'storage') {
                        $bunnyStreamController = new BunnyStreamController();
                        $path = 'https://' . $lesson->bunnyLesson->zone_name . '.b-cdn.net/' . $lesson->bunnyLesson->name;
                        $url = $bunnyStreamController->sign_bcdn_url($path, $lesson->bunnyLesson->token_authentication_key, $time);
                        $lesson_src = $url;
                    } else {
                        $lesson_src = $lesson->video_url;
                    }
                @endphp


                <iframe src="{{ $lesson_src }}" loading="lazy" style="border: none; height: 100%; width: 100%;"
                        frameborder="0" controls="1"
                        allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
                        allowfullscreen>
                </iframe>
            @endif

            @if ($lesson->host == 'Self' || $lesson->host == 'Storage')
                <video class="" id="video-id" controls autoplay controlsList="nodownload" oncontextmenu="return false;">
                    <source src="{{assetPath($lesson->video_url) }}" type="video/mp4"/>
                    <source src="{{assetPath($lesson->video_url) }}" type="video/ogg">
                </video>
            @endif

            @if ($lesson->host == 'Editor')
                <style>
                    .lesson_content_text ul{
                        padding-left: 2rem;
                        margin: 0;
                    }
                    html[dir='rtl'].lesson_content_text ul{
                        padding-left: 0;
                        padding-right: 2rem;
                        margin: 0;
                    }
                    .lesson_content_text ul li{
                        list-style-type: unset;
                    }
                </style>
                <div class="lesson_content_text w-100">
                    {!! $lesson->editor !!}
                </div>
            @endif
            @if ($lesson->host == 'm3u8')
                <video class="" id="video-id" controls autoplay controlsList="nodownload" oncontextmenu="return false;"
                       onended="lessonAutoComplete(course_id, {{ showPicName(Request::url()) }})">
                    <source src="{{ $lesson->video_url }}" type='application/x-mpegURL'/>
                </video>
            @endif



            @if ($lesson->host == 'URL')
                <video class="" id="video-id" controls autoplay controlsList="nodownload" oncontextmenu="return false;">
                    <source src="{{ $lesson->video_url }}" type="video/mp4">
                    <source src="{{ $lesson->video_url }}" type="video/ogg">
                    Your browser does not support the video.
                </video>
            @endif
            @if ($lesson->host == 'AmazonS3')
                <video class=" " id="video-id" controls controlsList="nodownload" oncontextmenu="return false;">
                    <source src="{{ $lesson->video_url }}" type="video/mp4"/>

                </video>
            @endif
            @if ($lesson->host == 'H5P' && isModuleActive('H5P'))
                @include('h5p::player', ['course' => $course, 'lesson' => $lesson])
            @endif
            @if ($lesson->host == 'XAPI' || $lesson->host == 'XAPI-AwsS3')
                <iframe id="video-id" class="video_iframe"
                        src="{{assetPath($lesson->video_url) }}?actor=%7B%22mbox%22%3A%22mailto%3A{{ Settings('email') }}%22%2C%22name%22%3A%22{{ Settings('site_title') }}%22%2C%22objectType%22%3A%22Agent%22%7D&amp;endpoint={{ url('xapi') }}&amp;course_id={{ $course->id }}&amp;lesson_id={{ $lesson->id }}&amp;next_lesson={{ $lesson_ids[$current_index + 1] ?? '' }}"></iframe>
            @endif
            @if ($lesson->host == 'SCORM' || $lesson->host == 'SCORM-AwsS3')
                @if (!empty($lesson->video_url))
                    <iframe class=" video_iframe" id="video-id" src=""
                            @if ($lesson->scorm_version == 'scorm_12') onbeforeunload="API.LMSFinish('');" width="100%"
                            height="100%" onunload="API.LMSFinish('');" @endif></iframe>
                @endif
            @endif

            @if ($lesson->host == 'Iframe' ||  $lesson->host =='Youtube')
                @if (!empty($lesson->video_url))
                    @php
                        $embedUrl = $lesson->video_url;

                        // Convert YouTube watch URL to embed URL
                        if ($lesson->host == 'Youtube') {
                            // Handle youtube.com/watch?v=VIDEO_ID format
                            if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $embedUrl, $matches)) {
                                $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                            }
                            // Handle youtu.be/VIDEO_ID format
                            elseif (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $embedUrl, $matches)) {
                                $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                            }
                            // Handle youtube.com/embed/VIDEO_ID format (already correct)
                            elseif (!str_contains($embedUrl, 'youtube.com/embed/')) {
                                // If it's just a video ID
                                $embedUrl = 'https://www.youtube.com/embed/' . $embedUrl;
                            }
                        }
                    @endphp

                    <div class="plyr__video-embed video_iframe" id="video-id">
                        <iframe height="500"
                                src="{{ $embedUrl }}?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1"
                                allowfullscreen allowtransparency allow="autoplay"></iframe>
                    </div>

                @endif
            @endif


            @if ($lesson->host == 'Image')
                <img src="{{assetPath($lesson->video_url) }}" alt="" class="w-100  h-100">
            @endif

            @if ($lesson->host == 'PDF')
    <script src="{{ assetPath('frontend/infixlmstheme/js/pdf.min.js') }}"></script>
    <script>
        var pdfjsLib = window['pdfjs-dist/build/pdf'];
        pdfjsLib.GlobalWorkerOptions.workerSrc = '{{ assetPath('frontend/infixlmstheme/js/pdf.worker.min.js') }}';
    </script>

    <style>
        #pdfOuterContainer {
            width: 100%;
            height: calc(100vh - 115px);
            overflow-y: auto;
            overflow-x: auto;
            background: #525659;
            padding: 16px 0;
            position: relative;
        }

        #pdfPagesContainer {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: fit-content;
        }

        .pdf-page-wrapper {
            position: relative;
            margin-bottom: 12px;
            background: #fff;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .35);
            line-height: 0;
            margin-left: auto !important;
            margin-right: auto !important;
        }

        .pdf-canvas {
            display: block;
            width: 100%;
            height: auto;
            image-rendering: auto;
        }

        .pdf-text-layer {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            overflow: hidden;
            line-height: 1;
            text-size-adjust: none;
            pointer-events: none;
            z-index: 2;
        }

        .pdf-text-layer span {
            color: transparent;
            position: absolute;
            white-space: pre;
            cursor: text;
            transform-origin: 0% 0%;
            pointer-events: all;
        }

        .pdf-text-layer ::selection {
            background: rgba(0, 0, 255, .2);
            color: transparent;
        }

        .pdf-page-wrapper.comment-mode .pdf-text-layer,
        .pdf-page-wrapper.comment-mode .pdf-text-layer span {
            pointer-events: none !important;
        }

        .pdf-page-wrapper.comment-mode {
            cursor: crosshair !important;
        }

        .pdf-page-wrapper.comment-mode canvas {
            cursor: crosshair !important;
        }

        .pdf-annot-layer {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 3;
        }

        .pdf-hl-rect {
            position: absolute;
            border-radius: 2px;
            mix-blend-mode: multiply;
            opacity: .55;
            pointer-events: all;
            cursor: pointer;
            z-index: 4;
        }

        .pdf-hl-rect:hover::after {
            content: '×';
            position: absolute;
            top: -9px;
            right: -7px;
            background: #e74c3c;
            color: #fff;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            font-size: 12px;
            line-height: 16px;
            text-align: center;
            pointer-events: none;
        }

        .pdf-comment-pin {
            position: absolute;
            width: 26px;
            height: 26px;
            pointer-events: all;
            cursor: pointer;
            z-index: 6;
            filter: drop-shadow(0 1px 3px rgba(0, 0, 0, .4));
            transition: transform .15s;
        }

        .pdf-comment-pin:hover {
            transform: scale(1.2);
        }

        #pdfCommentPopup {
            display: none;
            position: fixed;
            z-index: 99999;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 14px;
            min-width: 240px;
            max-width: 300px;
            box-shadow: 0 6px 24px rgba(0, 0, 0, .18);
        }

        #pdfCommentPopup textarea {
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 7px 9px;
            font-size: 13px;
            resize: vertical;
            font-family: inherit;
            color: #333;
        }

        #pdfCommentPopup textarea:focus {
            outline: none;
            border-color: #7aa8d4;
        }

        .pdf-popup-actions {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }

        .pdf-popup-actions .theme_btn,
        .pdf-popup-actions .theme_line_btn {
            padding: 5px 14px;
            font-size: 13px;
        }

        .pdf-search-wrap {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 5px;
            justify-content: center;
        }

        #pdfSearchInput {
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 4px 10px;
            font-size: 13px;
            height: 34px;
            width: 180px;
            font-family: inherit;
            color: #333;
        }

        #pdfSearchInput:focus {
            outline: none;
            border-color: #7aa8d4;
        }

        #pdfSearchInput.search-found {
            border-color: #27ae60;
        }

        #pdfSearchInput.search-not-found {
            border-color: #e74c3c;
        }

        #pdfSearchCount {
            font-size: 12px;
            color: #666;
            min-width: 60px;
            text-align: center;
        }

        .pdf-search-match {
            background: rgba(255, 200, 0, .6) !important;
            border-radius: 2px;
            color: #000 !important;
        }

        .pdf-search-match.active-match {
            background: rgba(255, 100, 0, .75) !important;
            outline: 2px solid #e67e22;
            color: #000 !important;
        }

        .pdf-hl-btn {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            border: 2px solid transparent;
            cursor: pointer;
            padding: 0;
            transition: border-color .15s, transform .15s;
        }

        .pdf-hl-btn:hover,
        .pdf-hl-btn.active-hl {
            border-color: #333;
            transform: scale(1.15);
        }

        .pdf-hl-btn[data-color="yellow"] { background: #ffe066; }
        .pdf-hl-btn[data-color="green"] { background: #90ee90; }
        .pdf-hl-btn[data-color="blue"] { background: #90c8f0; }
        .pdf-hl-btn[data-color="pink"] { background: #f0a8c8; }

        .pdf-toolbar-sep {
            width: 1px;
            height: 22px;
            background: #ddd;
            display: inline-block;
            margin: 0 4px;
            vertical-align: middle;
        }

        #pdfCommentModeBtn.comment-active {
            background: linear-gradient(90deg, #3c7cff, #1aafff) !important;
            color: #fff !important;
        }

        .small_btn_icon {
            padding: 10px;
        }

        .pdf-page-placeholder {
            background: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #888;
            font-size: 13px;
            font-family: sans-serif;
        }

        .pdftoolbar-slide-container {
            max-height: 180px !important;
        }

        @media (max-width:767px) {
            .pdftoolbar-slide-container:not(.pdftoolbar-hidden) {
                max-height: 230px !important;
            }

            #pdfOuterContainer {
                height: calc(100vh - 170px);
            }

            #pdfPagesContainer {
                display: flex;
                flex-direction: column;
                align-items: center;
            }
        }

        @media (min-width:768px) {
            .pdftoolbar-slide-container {
                max-height: none !important;
                opacity: 1 !important;
                overflow: visible !important;
            }

            .pdftoolbar-toggle-wrapper {
                display: none !important;
            }
        }

        .pdfviewer,
        .pdfjs-viewer {
            touch-action: pan-y pan-x;
            overscroll-behavior: contain;
        }

        @media (max-width: 767px) {
            .pdfviewer,
            .pdfjs-viewer {
                touch-action: none;
            }
        }

        .pdf-draw-layer {
            position: absolute;
            top: 0; left: 0;
            pointer-events: none;
            z-index: 10;
        }
        .pdf-page-wrapper.draw-mode .pdf-draw-layer {
            pointer-events: auto;
            cursor: crosshair;
        }
        .pdf-pen-btn {
            border: 2px solid transparent;
            margin: 0 2px;
        }
        .pdf-pen-btn.active-pen {
            border-color: #333;
            transform: scale(1.1);
        }
    </style>

    <div class="pdftoolbar-toggle-wrapper">
        <button class="pdftoolbar-toggle-btn collapsed" id="pdftoolbarToggleBtn" aria-label="Show PDF Toolbar">
            <i class="fa fa-chevron-down pdftoolbar-arrow"></i>
        </button>
    </div>

    <div class="pdftoolbar-slide-container pdftoolbar-hidden" id="pdftoolbarSlideContainer">
        <div class="pdftoolbar text-center row m-0 p-0">
            <div class="col-12 my-1">
                <button type="button" class="theme_btn small_btn_icon" id="pdfBtnFirst" title="First page"><i class="fa fa-step-backward"></i></button>
                <button type="button" class="theme_btn small_btn_icon" id="pdfBtnPrev" title="Previous"><i class="fa fa-angle-left"></i></button>
                <span class="pageno" id="pdfPageNo" style="margin:0 6px;">-/-</span>
                <button type="button" class="theme_btn small_btn_icon" id="pdfBtnNext" title="Next"><i class="fa fa-angle-right"></i></button>
                <button type="button" class="theme_btn small_btn_icon" id="pdfBtnLast" title="Last page"><i class="fa fa-step-forward"></i></button>

                <button type="button" class="theme_btn small_btn_icon" id="pdfBtnZoomOut" title="Zoom out"><i class="fa fa-search-minus"></i></button>
                <span class="zoomval" id="pdfZoomVal" style="margin:0 4px;">100%</span>
                <button type="button" class="theme_btn small_btn_icon" id="pdfBtnZoomIn" title="Zoom in"><i class="fa fa-search-plus"></i></button>

                <button type="button" class="theme_btn small_btn_icon ms-3" id="pdfBtnFitW" title="Fit width"><i class="fa fa-arrows-alt-h"></i></button>
                <button type="button" class="theme_btn small_btn_icon" id="pdfBtnFitH" title="Fit height"><i class="fa fa-arrows-alt-v"></i></button>
                <button type="button" class="theme_btn small_btn_icon" id="pdfBtnFit" title="Fit page"><i class="fa fa-expand"></i></button>
            </div>

            <div class="col-12 my-1 pdf-search-wrap" style="row-gap: 10px;">
                <div class="d-flex align-items-center flex-wrap" style="gap: 5px;">
                    <input type="text" id="pdfSearchInput" placeholder="Search in PDF…" autocomplete="off">
                    <button type="button" class="theme_btn small_btn_icon" id="pdfSearchBtn" title="Search"><i class="fa fa-search"></i></button>
                    <button type="button" class="theme_btn small_btn_icon" id="pdfSearchPrev" title="Prev match"><i class="fa fa-angle-up"></i></button>
                    <button type="button" class="theme_btn small_btn_icon" id="pdfSearchNext" title="Next match"><i class="fa fa-angle-down"></i></button>
                    <span id="pdfSearchCount"></span>
                </div>

                <span class="pdf-toolbar-sep d-none d-md-inline-block"></span>
                
                <div class="d-flex align-items-center flex-wrap" style="gap: 5px;">
                    <span style="font-size:12px;color:#555;vertical-align:middle;">Highlight:</span>
                    <button type="button" class="pdf-hl-btn active-hl" data-color="yellow" title="Yellow"></button>
                    <button type="button" class="pdf-hl-btn" data-color="green" title="Green"></button>
                    <button type="button" class="pdf-hl-btn" data-color="blue" title="Blue"></button>
                    <button type="button" class="pdf-hl-btn" data-color="pink" title="Pink"></button>
                </div>

                <span class="pdf-toolbar-sep d-none d-md-inline-block"></span>
                
                <div class="d-flex align-items-center flex-wrap" style="gap: 5px;">
                    <button type="button" class="theme_btn small_btn_icon" id="pdfCommentModeBtn" title="Add Comment">
                        <i class="fa fa-comment-alt"></i>
                    </button>
                </div>

                <span class="pdf-toolbar-sep d-none d-md-inline-block"></span>
                
                <div class="d-flex align-items-center flex-wrap" style="gap: 5px;">
                    <span style="font-size:12px;color:#555;vertical-align:middle;">Pen:</span>
                    <button type="button" class="theme_btn small_btn_icon" id="pdfPenModeBtn" title="Draw Mode">
                        <i class="fa fa-pen"></i>
                    </button>
                    <button type="button" class="pdf-pen-btn active-pen" data-pen-color="black" title="Black" style="background:black; width:20px;height:20px;border-radius:50%;cursor:pointer;vertical-align:middle;"></button>
                    <button type="button" class="pdf-pen-btn" data-pen-color="red" title="Red" style="background:red; width:20px;height:20px;border-radius:50%;cursor:pointer;vertical-align:middle;"></button>
                    <button type="button" class="pdf-pen-btn" data-pen-color="blue" title="Blue" style="background:blue; width:20px;height:20px;border-radius:50%;cursor:pointer;vertical-align:middle;"></button>
                    <button type="button" class="theme_btn small_btn_icon" id="pdfEraserModeBtn" title="Eraser Tool" style="margin-left:5px;">
                        <i class="fa fa-eraser"></i>
                    </button>
                    <button type="button" class="theme_btn small_btn_icon" id="pdfClearDrawingsBtn" title="Clear All Drawings" style="color:red;">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="pdfOuterContainer">
        <div id="pdfPagesContainer"></div>
    </div>

    <div id="pdfCommentPopup">
        <textarea id="pdfCommentText" rows="4" placeholder="Write a comment…"></textarea>
        <div class="pdf-popup-actions">
            <button id="pdfCommentSave" class="theme_btn" type="button">Save</button>
            <button id="pdfCommentDelete" class="theme_line_btn" style="display:none;" type="button">Delete</button>
            <button id="pdfCommentCancel" class="theme_line_btn" type="button">Cancel</button>
        </div>
    </div>

    <script>
        (function () {
            'use strict';

            const LESSON_ID = {{ $lesson->id }};
            const COURSE_ID = {{ $course->id }};
            const SAVE_URL = '{{ route("pdf.annotations.save") }}';
            const LOAD_URL = '{{ route("pdf.annotations.load") }}';
            const CSRF = document.querySelector('meta[name="csrf-token"]').content;
            const PDF_URL = '{{ assetPath($lesson->video_url) }}';
            const COLORS = { yellow: '#ffe066', green: '#90ee90', blue: '#90c8f0', pink: '#f0a8c8' };
            const ZOOMS = [0.5, 0.75, 1.0, 1.25, 1.5, 1.75, 2.0, 2.5, 3.0];

            let pdfDoc = null, scale = 1.5, totalPages = 0, rendered = {};
            let highlights = [], comments = [], drawings = [];
            let hlColor = 'yellow', commentMode = false, pendingCmt = null, editingCmt = null;
            let penMode = false, eraserMode = false, penColor = 'black', isDrawing = false, currentPath = [];
            let searchMatches = [], searchIdx = -1, lastSearchQuery = '';

            const outerCont = document.getElementById('pdfOuterContainer');
            const pagesCont = document.getElementById('pdfPagesContainer');
            const pageNoEl = document.getElementById('pdfPageNo');
            const zoomValEl = document.getElementById('pdfZoomVal');
            const srchInput = document.getElementById('pdfSearchInput');
            const srchCount = document.getElementById('pdfSearchCount');
            const popup = document.getElementById('pdfCommentPopup');
            const popupText = document.getElementById('pdfCommentText');
            const popupSave = document.getElementById('pdfCommentSave');
            const popupDel = document.getElementById('pdfCommentDelete');
            const popupCncl = document.getElementById('pdfCommentCancel');
            const cmBtn = document.getElementById('pdfCommentModeBtn');

            function makeAnnotId() {
                return Math.floor((Date.now() % 1000000000) + Math.floor(Math.random() * 10000));
            }

            function getAnnotId(item) {
                return item ? parseInt(item.annot_id || item.id || 0, 10) : 0;
            }

            function normalizeLoadedAnnotations() {
                highlights = (highlights || []).map(h => ({
                    ...h,
                    annot_id: parseInt(h.annot_id || h.id || 0, 10),
                    pageNum: parseInt(h.pageNum || h.page_num || 1, 10),
                    rects: Array.isArray(h.rects) ? h.rects : (typeof h.rects === 'string' ? safeJsonParse(h.rects, []) : [])
                })).filter(h => h.annot_id > 0);

                comments = (comments || []).map(c => ({
                    ...c,
                    annot_id: parseInt(c.annot_id || c.id || 0, 10),
                    pageNum: parseInt(c.pageNum || c.page_num || 1, 10),
                    x: typeof c.x !== 'undefined' ? parseFloat(c.x) : parseFloat(c.pos_x || 0),
                    y: typeof c.y !== 'undefined' ? parseFloat(c.y) : parseFloat(c.pos_y || 0)
                })).filter(c => c.annot_id > 0);

                drawings = (drawings || []).map(d => ({
                    ...d,
                    annot_id: parseInt(d.annot_id || d.id || 0, 10),
                    pageNum: parseInt(d.pageNum || d.page_num || 1, 10),
                    rects: Array.isArray(d.rects) ? d.rects : (typeof d.rects === 'string' ? safeJsonParse(d.rects, []) : [])
                })).filter(d => d.annot_id > 0);
            }

            function safeJsonParse(value, fallback) {
                try {
                    return JSON.parse(value);
                } catch (e) {
                    return fallback;
                }
            }

            function apiSave(payload) {
                const formData = new FormData();

                Object.keys(payload).forEach(key => {
                    const value = payload[key];

                    if (Array.isArray(value) || (value && typeof value === 'object')) {
                        formData.append(key, JSON.stringify(value));
                    } else if (value !== undefined && value !== null) {
                        formData.append(key, value);
                    }
                });

                return fetch(SAVE_URL, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                    body: formData
                }).then(async r => {
                    const text = await r.text();
                    try {
                        return text ? JSON.parse(text) : null;
                    } catch (e) {
                        console.error('Save API non-JSON response:', text);
                        return null;
                    }
                }).catch(err => {
                    console.error('apiSave error:', err);
                    return null;
                });
            }

            function loadAnnotations() {
                fetch(LOAD_URL + '?lesson_id=' + LESSON_ID, {
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.ok ? r.json() : null)
                .then(d => {
                    if (!d || !d.success) return;
                    highlights = d.highlights || [];
                    comments = d.comments || [];
                    drawings = d.drawings || [];
                    normalizeLoadedAnnotations();
                    redrawAll();
                })
                .catch(err => console.error('Load annotations error:', err));
            }

            async function fitPdfToWidthOnLoad() {
                if (!pdfDoc) return;

                const page = await pdfDoc.getPage(1);
                const vp = page.getViewport({ scale: 1 });

                await new Promise(resolve => requestAnimationFrame(resolve));
                await new Promise(resolve => setTimeout(resolve, 60));

                const containerWidth = outerCont.clientWidth || outerCont.getBoundingClientRect().width;
                if (!containerWidth) return;

                const horizontalPadding = window.innerWidth < 768 ? 20 : 32;
                const targetWidth = Math.max(100, containerWidth - horizontalPadding);
                const fitScale = targetWidth / vp.width;

                scale = Math.max(0.5, Math.min(3.0, Math.round(fitScale * 100) / 100));
                zoomValEl.textContent = Math.round(scale * 100) + '%';

                await rerenderAll();
            }

            pdfjsLib.getDocument(PDF_URL).promise.then(async doc => {
                pdfDoc = doc;
                totalPages = doc.numPages;
                buildWrappers();
                loadAnnotations();

                await fitPdfToWidthOnLoad();

                requestAnimationFrame(() => {
                    triggerVisible();
                });

                if (window.innerWidth < 768) {
                    setTimeout(async () => {
                        await fitPdfToWidthOnLoad();
                        triggerVisible();
                    }, 250);

                    setTimeout(async () => {
                        await fitPdfToWidthOnLoad();
                        triggerVisible();
                    }, 700);
                }
            }).catch(e => console.error('PDF load error:', e));

            function buildWrappers() {
                pagesCont.innerHTML = '';
                rendered = {};

                for (let i = 1; i <= totalPages; i++) {
                    const wrap = document.createElement('div');
                    wrap.className = 'pdf-page-wrapper';
                    wrap.dataset.page = i;

                    const ph = document.createElement('div');
                    ph.className = 'pdf-page-placeholder';
                    const mobileWidth = Math.max(220, Math.min((outerCont.clientWidth || 320) - 20, 612));
                    const mobileHeight = Math.round(mobileWidth * 1.294);
                    ph.style.cssText = `width:${mobileWidth}px;height:${mobileHeight}px;`;
                    ph.textContent = 'Page ' + i;

                    wrap.appendChild(ph);
                    pagesCont.appendChild(wrap);
                }

                updatePageNo(1);
                setupObserver();
            }

            let observer;

            function setupObserver() {
                if (observer) observer.disconnect();

                observer = new IntersectionObserver(entries => {
                    entries.forEach(e => {
                        if (e.isIntersecting) renderPage(+e.target.dataset.page);
                    });
                }, { root: outerCont, rootMargin: '400px' });

                pagesCont.querySelectorAll('.pdf-page-wrapper').forEach(w => observer.observe(w));
            }

            function triggerVisible() {
                const outerR = outerCont.getBoundingClientRect();

                pagesCont.querySelectorAll('.pdf-page-wrapper').forEach(w => {
                    const r = w.getBoundingClientRect();
                    if (r.bottom >= outerR.top - 400 && r.top <= outerR.bottom + 400) {
                        renderPage(+w.dataset.page);
                    }
                });
            }

            async function renderPage(pageNum) {
                if (rendered[pageNum] || !pdfDoc) return;
                rendered[pageNum] = true;

                const wrap = pagesCont.querySelector(`[data-page="${pageNum}"]`);
                if (!wrap) return;

                const page = await pdfDoc.getPage(pageNum);
                const viewport = page.getViewport({ scale });

                const outputScale = window.devicePixelRatio || 1;

                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d', { alpha: false });

                canvas.className = 'pdf-canvas';
                canvas.style.display = 'block';

                canvas.style.width = `${viewport.width}px`;
                canvas.style.height = `${viewport.height}px`;

                canvas.width = Math.floor(viewport.width * outputScale);
                canvas.height = Math.floor(viewport.height * outputScale);

                ctx.setTransform(outputScale, 0, 0, outputScale, 0, 0);

                const textDiv = document.createElement('div');
                textDiv.className = 'pdf-text-layer';
                textDiv.style.width = `${viewport.width}px`;
                textDiv.style.height = `${viewport.height}px`;

                const annotDiv = document.createElement('div');
                annotDiv.className = 'pdf-annot-layer';
                annotDiv.dataset.annotPage = pageNum;
                annotDiv.style.width = `${viewport.width}px`;
                annotDiv.style.height = `${viewport.height}px`;

                const drawCanvas = document.createElement('canvas');
                drawCanvas.className = 'pdf-draw-layer';
                drawCanvas.dataset.drawPage = pageNum;
                drawCanvas.style.width = `${viewport.width}px`;
                drawCanvas.style.height = `${viewport.height}px`;
                drawCanvas.width = Math.floor(viewport.width * outputScale);
                drawCanvas.height = Math.floor(viewport.height * outputScale);
                const drawCtx = drawCanvas.getContext('2d');
                drawCtx.scale(outputScale, outputScale);

                wrap.style.width = `${viewport.width}px`;
                wrap.style.height = `${viewport.height}px`;
                wrap.innerHTML = '';

                wrap.appendChild(canvas);
                wrap.appendChild(drawCanvas);
                wrap.appendChild(textDiv);
                wrap.appendChild(annotDiv);

                setupDrawCanvas(drawCanvas, pageNum, viewport.width, viewport.height);

                await page.render({
                    canvasContext: ctx,
                    viewport: viewport
                }).promise;

                const textContent = await page.getTextContent();
                buildTextLayer(textContent, textDiv, viewport);

                wrap.onmouseup = null;
                wrap.addEventListener('mouseup', e => onMouseUp(e, wrap, pageNum));

                if (commentMode) wrap.classList.add('comment-mode');
                if (penMode || eraserMode) wrap.classList.add('draw-mode');
                drawAnnotsForPage(pageNum);
            }
            
            function buildTextLayer(textContent, container, viewport) {
                if (typeof pdfjsLib.renderTextLayer === 'function') {
                    try {
                        pdfjsLib.renderTextLayer({
                            textContentSource: textContent,
                            container,
                            viewport,
                            textDivs: []
                        });
                        return;
                    } catch (e) {}

                    try {
                        pdfjsLib.renderTextLayer({
                            textContent,
                            container,
                            viewport,
                            textDivs: []
                        });
                        return;
                    } catch (e) {}
                }

                if (!textContent || !textContent.items) return;

                textContent.items.forEach(item => {
                    if (!item.str) return;

                    const tx = pdfjsLib.Util.transform(viewport.transform, item.transform);
                    const span = document.createElement('span');
                    span.textContent = item.str;

                    const fontH = Math.sqrt(tx[2] * tx[2] + tx[3] * tx[3]);
                    span.style.left = tx[4] + 'px';
                    span.style.top = (tx[5] - fontH) + 'px';
                    span.style.fontSize = fontH + 'px';

                    const angle = Math.atan2(tx[1], tx[0]);
                    const sx = item.width > 0 ? item.width / (fontH * item.str.length * 0.55 || 1) : 1;
                    span.style.transform = (angle ? `rotate(${-angle}rad) ` : '') + `scaleX(${sx})`;

                    container.appendChild(span);
                });
            }

            async function rerenderAll() {
                clearSearch(true);
                rendered = {};

                pagesCont.querySelectorAll('.pdf-page-wrapper').forEach(w => {
                    const ph = document.createElement('div');
                    ph.className = 'pdf-page-placeholder';
                    ph.style.cssText = `width:${w.offsetWidth || 612}px;height:${w.offsetHeight || 792}px;`;
                    ph.textContent = 'Page ' + w.dataset.page;
                    w.innerHTML = '';
                    w.appendChild(ph);
                });

                setupObserver();
                triggerVisible();
            }

            function drawAnnotsForPage(pageNum) {
                const annotDiv = pagesCont.querySelector(`.pdf-annot-layer[data-annot-page="${pageNum}"]`);
                if (!annotDiv) return;

                annotDiv.querySelectorAll('.pdf-hl-rect,.pdf-comment-pin').forEach(el => el.remove());

                highlights.filter(h => parseInt(h.pageNum, 10) === pageNum).forEach(hl => {
                    if (!hl.rects || !hl.rects.length) return;

                    hl.rects.forEach(r => {
                        const div = document.createElement('div');
                        div.className = 'pdf-hl-rect';
                        div.style.cssText = `background:${COLORS[hl.color] || COLORS.yellow};left:${r.x * 100}%;top:${r.y * 100}%;width:${r.w * 100}%;height:${r.h * 100}%;`;
                        div.title = ((hl.text || 'Highlight') + '').substring(0, 80);

                        div.addEventListener('click', async ev => {
                            ev.stopPropagation();

                            const annotId = getAnnotId(hl);
                            if (!annotId) return;

                            if (confirm('Delete this highlight?')) {
                                await apiSave({
                                    action: 'delete_highlight',
                                    lesson_id: LESSON_ID,
                                    course_id: COURSE_ID,
                                    annot_id: annotId
                                });

                                highlights = highlights.filter(h2 => getAnnotId(h2) !== annotId);
                                drawAnnotsForPage(pageNum);
                            }
                        });

                        annotDiv.appendChild(div);
                    });
                });

                comments.filter(c => parseInt(c.pageNum, 10) === pageNum).forEach(c => {
                    const pin = document.createElement('div');
                    pin.className = 'pdf-comment-pin';
                    pin.innerHTML = `<svg viewBox="0 0 24 24" fill="#3c7cff" xmlns="http://www.w3.org/2000/svg"><path d="M20 2H4a2 2 0 00-2 2v18l4-4h14a2 2 0 002-2V4a2 2 0 00-2-2z"/></svg>`;
                    pin.style.left = `calc(${c.x * 100}% - 13px)`;
                    pin.style.top = `calc(${c.y * 100}% - 26px)`;
                    pin.title = ((c.text || 'Comment') + '').substring(0, 60);

                    pin.addEventListener('click', ev => {
                        ev.stopPropagation();
                        openPopup(ev.clientX, ev.clientY, c);
                    });

                    annotDiv.appendChild(pin);
                });

                const drawCanvas = pagesCont.querySelector(`.pdf-draw-layer[data-draw-page="${pageNum}"]`);
                if (drawCanvas) {
                    const ctx = drawCanvas.getContext('2d');
                    const cw = parseFloat(drawCanvas.style.width);
                    const ch = parseFloat(drawCanvas.style.height);
                    const outputScale = window.devicePixelRatio || 1;
                    
                    ctx.save();
                    ctx.setTransform(1, 0, 0, 1, 0, 0);
                    ctx.clearRect(0, 0, drawCanvas.width, drawCanvas.height);
                    ctx.restore();
                    
                    drawings.filter(d => parseInt(d.pageNum, 10) === pageNum).forEach(d => {
                        if (!d.rects || !d.rects.length) return;
                        ctx.beginPath();
                        ctx.lineJoin = 'round';
                        ctx.lineCap = 'round';
                        ctx.lineWidth = 2;
                        ctx.strokeStyle = d.color || 'black';
                        d.rects.forEach((pt, i) => {
                            if (i === 0) ctx.moveTo(pt.x * cw, pt.y * ch);
                            else ctx.lineTo(pt.x * cw, pt.y * ch);
                        });
                        ctx.stroke();
                    });
                }
            }

            function setupDrawCanvas(canvas, pageNum, w, h) {
                const ctx = canvas.getContext('2d');
                
                function getPos(e) {
                    const rect = canvas.getBoundingClientRect();
                    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                    return {
                        x: (clientX - rect.left) / rect.width,
                        y: (clientY - rect.top) / rect.height
                    };
                }
                
                function dist2(v, w) { return Math.pow(v.x - w.x, 2) + Math.pow(v.y - w.y, 2); }
                function distToSegmentSquared(p, v, w) {
                    var l2 = dist2(v, w);
                    if (l2 == 0) return dist2(p, v);
                    var t = ((p.x - v.x) * (w.x - v.x) + (p.y - v.y) * (w.y - v.y)) / l2;
                    t = Math.max(0, Math.min(1, t));
                    return dist2(p, { x: v.x + t * (w.x - v.x), y: v.y + t * (w.y - v.y) });
                }
                
                function startDraw(e) {
                    if (!penMode && !eraserMode) return;
                    e.preventDefault();
                    isDrawing = true;
                    currentPath = [getPos(e)];
                    if (eraserMode) draw(e); // Trigger erase immediately on click
                }
                
                function draw(e) {
                    if (!isDrawing) return;
                    if (!penMode && !eraserMode) return;
                    e.preventDefault();
                    const pt = getPos(e);
                    
                    if (eraserMode) {
                        const thresholdSq = Math.pow(0.02, 2); // 2% distance
                        let deletedAny = false;
                        for (let i = drawings.length - 1; i >= 0; i--) {
                            const d = drawings[i];
                            if (parseInt(d.pageNum, 10) !== pageNum) continue;
                            if (!d.rects || !d.rects.length) continue;
                            
                            let hit = false;
                            if (d.rects.length === 1) {
                                if (dist2(pt, d.rects[0]) < thresholdSq) hit = true;
                            } else {
                                for (let j = 0; j < d.rects.length - 1; j++) {
                                    if (distToSegmentSquared(pt, d.rects[j], d.rects[j+1]) < thresholdSq) {
                                        hit = true; break;
                                    }
                                }
                            }
                            
                            if (hit) {
                                apiSave({
                                    action: 'delete_drawing',
                                    lesson_id: LESSON_ID,
                                    course_id: COURSE_ID,
                                    annot_id: d.annot_id
                                });
                                drawings.splice(i, 1);
                                deletedAny = true;
                            }
                        }
                        if (deletedAny) {
                            const outputScale = window.devicePixelRatio || 1;
                            ctx.save();
                            ctx.setTransform(1, 0, 0, 1, 0, 0);
                            ctx.clearRect(0, 0, canvas.width, canvas.height);
                            ctx.restore();
                            drawAnnotsForPage(pageNum);
                        }
                        return;
                    }
                    
                    currentPath.push(pt);
                    
                    const outputScale = window.devicePixelRatio || 1;
                    ctx.save();
                    ctx.setTransform(1, 0, 0, 1, 0, 0);
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    ctx.restore();
                    
                    drawAnnotsForPage(pageNum);
                    
                    ctx.beginPath();
                    ctx.lineJoin = 'round';
                    ctx.lineCap = 'round';
                    ctx.lineWidth = 2;
                    ctx.strokeStyle = penColor;
                    currentPath.forEach((p, i) => {
                        if (i === 0) ctx.moveTo(p.x * w, p.y * h);
                        else ctx.lineTo(p.x * w, p.y * h);
                    });
                    ctx.stroke();
                }
                
                async function endDraw(e) {
                    if (!isDrawing) return;
                    if (!penMode && !eraserMode) return;
                    isDrawing = false;
                    if (penMode && currentPath.length > 1) {
                        const annotId = makeAnnotId();
                        drawings.push({
                            annot_id: annotId,
                            pageNum: pageNum,
                            rects: currentPath,
                            color: penColor
                        });
                        drawAnnotsForPage(pageNum);
                        
                        await apiSave({
                            action: 'drawing',
                            lesson_id: LESSON_ID,
                            course_id: COURSE_ID,
                            annot_id: annotId,
                            pageNum: pageNum,
                            rects: currentPath,
                            color: penColor
                        });
                    }
                    currentPath = [];
                }
                
                canvas.addEventListener('mousedown', startDraw);
                canvas.addEventListener('mousemove', draw);
                canvas.addEventListener('mouseup', endDraw);
                canvas.addEventListener('mouseout', endDraw);
                
                canvas.addEventListener('touchstart', startDraw, {passive: false});
                canvas.addEventListener('touchmove', draw, {passive: false});
                canvas.addEventListener('touchend', endDraw);
                canvas.addEventListener('touchcancel', endDraw);
            }

            function redrawAll() {
                for (let i = 1; i <= totalPages; i++) {
                    if (rendered[i]) drawAnnotsForPage(i);
                }
            }

            async function onMouseUp(e, wrap, pageNum) {
                if (commentMode) return;

                const sel = window.getSelection();
                if (!sel || sel.isCollapsed || !sel.toString().trim()) return;

                const text = sel.toString().trim();
                const range = sel.getRangeAt(0);
                const rects = Array.from(range.getClientRects());
                if (!rects.length) return;

                const wRect = wrap.getBoundingClientRect();
                const norm = rects.map(r => ({
                    x: (r.left - wRect.left) / wRect.width,
                    y: (r.top - wRect.top) / wRect.height,
                    w: r.width / wRect.width,
                    h: r.height / wRect.height
                })).filter(r => r.w > 0.001 && r.h > 0.001);

                sel.removeAllRanges();
                if (!norm.length) return;

                let annot_id = makeAnnotId();

                while (
                    highlights.some(h => getAnnotId(h) === annot_id) ||
                    comments.some(c => getAnnotId(c) === annot_id)
                ) {
                    annot_id = makeAnnotId();
                }

                const localHighlight = {
                    annot_id,
                    pageNum,
                    rects: norm,
                    text,
                    color: hlColor
                };

                highlights.push(localHighlight);
                drawAnnotsForPage(pageNum);

                await apiSave({
                    action: 'highlight',
                    lesson_id: LESSON_ID,
                    course_id: COURSE_ID,
                    annot_id: annot_id,
                    pageNum,
                    rects: norm,
                    text,
                    color: hlColor
                });
            }

            function enterCommentMode() {
                commentMode = true;
                cmBtn.classList.add('comment-active');
                cmBtn.title = 'Click anywhere on PDF to place comment';
                pagesCont.querySelectorAll('.pdf-page-wrapper').forEach(w => w.classList.add('comment-mode'));
            }

            function exitCommentMode() {
                commentMode = false;
                cmBtn.classList.remove('comment-active');
                cmBtn.title = 'Add Comment';
                pagesCont.querySelectorAll('.pdf-page-wrapper').forEach(w => w.classList.remove('comment-mode'));
            }

            cmBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                if (penMode) document.getElementById('pdfPenModeBtn').click();
                if (eraserMode) document.getElementById('pdfEraserModeBtn').click();
                if (commentMode) exitCommentMode();
                else enterCommentMode();
            });

            document.getElementById('pdfPenModeBtn').addEventListener('click', function (e) {
                e.stopPropagation();
                if (commentMode) exitCommentMode();
                if (eraserMode) {
                    eraserMode = false;
                    document.getElementById('pdfEraserModeBtn').style.background = '';
                    document.getElementById('pdfEraserModeBtn').style.color = '';
                }
                penMode = !penMode;
                if (penMode) {
                    this.style.background = 'linear-gradient(90deg, #3c7cff, #1aafff)';
                    this.style.color = '#fff';
                    pagesCont.querySelectorAll('.pdf-page-wrapper').forEach(w => w.classList.add('draw-mode'));
                } else {
                    this.style.background = '';
                    this.style.color = '';
                    pagesCont.querySelectorAll('.pdf-page-wrapper').forEach(w => w.classList.remove('draw-mode'));
                }
            });

            document.getElementById('pdfEraserModeBtn').addEventListener('click', function (e) {
                e.stopPropagation();
                if (commentMode) exitCommentMode();
                if (penMode) {
                    penMode = false;
                    document.getElementById('pdfPenModeBtn').style.background = '';
                    document.getElementById('pdfPenModeBtn').style.color = '';
                }
                eraserMode = !eraserMode;
                if (eraserMode) {
                    this.style.background = 'linear-gradient(90deg, #3c7cff, #1aafff)';
                    this.style.color = '#fff';
                    pagesCont.querySelectorAll('.pdf-page-wrapper').forEach(w => w.classList.add('draw-mode'));
                } else {
                    this.style.background = '';
                    this.style.color = '';
                    pagesCont.querySelectorAll('.pdf-page-wrapper').forEach(w => w.classList.remove('draw-mode'));
                }
            });

            document.querySelectorAll('.pdf-pen-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    document.querySelectorAll('.pdf-pen-btn').forEach(b => b.classList.remove('active-pen'));
                    this.classList.add('active-pen');
                    penColor = this.dataset.penColor;
                    if (!penMode) document.getElementById('pdfPenModeBtn').click();
                });
            });

            document.getElementById('pdfClearDrawingsBtn').addEventListener('click', async function(e) {
                e.stopPropagation();
                if (confirm('Are you sure you want to clear all drawings?')) {
                    await apiSave({
                        action: 'clear_drawings',
                        lesson_id: LESSON_ID,
                        course_id: COURSE_ID
                    });
                    drawings = [];
                    redrawAll();
                }
            });

            outerCont.addEventListener('click', function (e) {
                if (!commentMode) return;

                let hitWrap = null;
                let node = e.target;

                while (node && node !== outerCont) {
                    if (node.classList && node.classList.contains('pdf-page-wrapper')) {
                        hitWrap = node;
                        break;
                    }
                    node = node.parentElement;
                }

                if (!hitWrap) return;

                const pageNum = parseInt(hitWrap.dataset.page, 10);
                const r = hitWrap.getBoundingClientRect();

                pendingCmt = {
                    pageNum,
                    x: (e.clientX - r.left) / r.width,
                    y: (e.clientY - r.top) / r.height
                };

                editingCmt = null;
                e.stopPropagation();
                openPopup(e.clientX, e.clientY, null);
            });

            function openPopup(cx, cy, existing) {
                exitCommentMode();
                popup.style.display = 'block';

                let left = cx + 12, top = cy + 12;
                if (left + 300 > window.innerWidth) left = cx - 312;
                if (top + 220 > window.innerHeight) top = cy - 220;

                popup.style.left = Math.max(4, left) + 'px';
                popup.style.top = Math.max(4, top) + 'px';

                if (existing) {
                    popupText.value = existing.text || '';
                    editingCmt = existing;
                    pendingCmt = null;
                    popupDel.style.display = 'inline-block';
                } else {
                    popupText.value = '';
                    popupDel.style.display = 'none';
                }

                setTimeout(() => popupText.focus(), 50);
            }

            popupSave.addEventListener('click', async function () {
                const txt = popupText.value.trim();

                if (!txt) {
                    popupText.focus();
                    return;
                }

                if (editingCmt) {
                    editingCmt.text = txt;

                    await apiSave({
                        action: 'update_comment',
                        lesson_id: LESSON_ID,
                        course_id: COURSE_ID,
                        annot_id: getAnnotId(editingCmt),
                        text: txt
                    });

                    redrawAll();
                } else if (pendingCmt) {
                    let annot_id = makeAnnotId();

                    while (
                        highlights.some(h => getAnnotId(h) === annot_id) ||
                        comments.some(c => getAnnotId(c) === annot_id)
                    ) {
                        annot_id = makeAnnotId();
                    }

                    const c = {
                        annot_id,
                        pageNum: pendingCmt.pageNum,
                        x: pendingCmt.x,
                        y: pendingCmt.y,
                        text: txt
                    };

                    comments.push(c);

                    await apiSave({
                        action: 'comment',
                        lesson_id: LESSON_ID,
                        course_id: COURSE_ID,
                        annot_id: annot_id,
                        pageNum: c.pageNum,
                        x: c.x,
                        y: c.y,
                        text: txt
                    });

                    drawAnnotsForPage(c.pageNum);
                }

                closePopup();
                pendingCmt = null;
                editingCmt = null;
            });

            popupDel.addEventListener('click', async function () {
                if (!editingCmt || !confirm('Delete this comment?')) return;

                const annotId = getAnnotId(editingCmt);
                if (!annotId) return;

                await apiSave({
                    action: 'delete_comment',
                    lesson_id: LESSON_ID,
                    course_id: COURSE_ID,
                    annot_id: annotId
                });

                comments = comments.filter(c => getAnnotId(c) !== annotId);
                redrawAll();
                closePopup();
                editingCmt = null;
            });

            popupCncl.addEventListener('click', () => {
                closePopup();
                pendingCmt = null;
                editingCmt = null;
            });

            function closePopup() {
                popup.style.display = 'none';
                exitCommentMode();
            }

            document.addEventListener('click', e => {
                if (!popup.contains(e.target) && e.target !== cmBtn && !cmBtn.contains(e.target)) {
                    if (popup.style.display === 'block') closePopup();
                }
            });

            document.querySelectorAll('.pdf-hl-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    document.querySelectorAll('.pdf-hl-btn').forEach(b => b.classList.remove('active-hl'));
                    this.classList.add('active-hl');
                    hlColor = this.dataset.color;

                    if (commentMode) exitCommentMode();
                });
            });

            function clearSearch(resetQuery = false) {
                document.querySelectorAll('.pdf-search-match').forEach(m => {
                    const parent = m.parentNode;
                    if (parent) {
                        parent.replaceChild(document.createTextNode(m.textContent), m);
                    }
                });

                pagesCont.querySelectorAll('.pdf-text-layer span').forEach(s => {
                    try { s.normalize(); } catch (e) {}
                });

                searchMatches = [];
                searchIdx = -1;
                srchCount.textContent = '';
                srchInput.classList.remove('search-found', 'search-not-found');

                if (resetQuery) lastSearchQuery = '';
            }

            async function doSearch() {
                clearSearch();

                const query = srchInput.value.trim();
                lastSearchQuery = query;

                if (!query) return;

                const re = new RegExp(query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'gi');

                for (let pn = 1; pn <= totalPages; pn++) {
                    if (!rendered[pn]) {
                        await renderPage(pn);
                        await new Promise(res => setTimeout(res, 80));
                    }

                    const wrap = pagesCont.querySelector(`[data-page="${pn}"]`);
                    if (!wrap) continue;

                    const tl = wrap.querySelector('.pdf-text-layer');
                    if (!tl) continue;

                    tl.querySelectorAll('span').forEach(span => {
                        const nodes = [];
                        span.childNodes.forEach(n => {
                            if (n.nodeType === 3 && n.nodeValue) nodes.push(n);
                        });

                        nodes.forEach(node => {
                            const val = node.nodeValue;
                            re.lastIndex = 0;
                            if (!re.test(val)) return;

                            re.lastIndex = 0;
                            const frag = document.createDocumentFragment();
                            let last = 0, m;

                            while ((m = re.exec(val)) !== null) {
                                if (m.index > last) {
                                    frag.appendChild(document.createTextNode(val.slice(last, m.index)));
                                }

                                const mark = document.createElement('mark');
                                mark.className = 'pdf-search-match';
                                mark.textContent = m[0];
                                frag.appendChild(mark);
                                searchMatches.push(mark);

                                last = m.index + m[0].length;
                            }

                            if (last < val.length) {
                                frag.appendChild(document.createTextNode(val.slice(last)));
                            }

                            if (node.parentNode) node.parentNode.replaceChild(frag, node);
                            re.lastIndex = 0;
                        });
                    });
                }

                if (searchMatches.length) {
                    srchInput.classList.add('search-found');
                    activateMatch(0);
                } else {
                    srchInput.classList.add('search-not-found');
                    srchCount.textContent = '0 results';
                }
            }

            function activateMatch(idx) {
                searchMatches.forEach(m => {
                    if (m) {
                        m.style.background = 'rgba(255,200,0,.6)';
                        m.style.outline = '';
                        m.classList.remove('active-match');
                    }
                });

                if (!searchMatches.length) return;

                idx = ((idx % searchMatches.length) + searchMatches.length) % searchMatches.length;
                searchIdx = idx;

                const mark = searchMatches[idx];
                if (mark) {
                    mark.classList.add('active-match');
                    mark.style.background = 'rgba(255,100,0,.75)';
                    mark.style.outline = '2px solid #e67e22';
                    mark.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }

                srchCount.textContent = (idx + 1) + ' / ' + searchMatches.length;
            }

            async function handleSearchEnter(isPrev = false) {
                const query = srchInput.value.trim();

                if (!query) {
                    clearSearch(true);
                    return;
                }

                const isSameQuery = query === lastSearchQuery;

                if (!searchMatches.length || !isSameQuery) {
                    await doSearch();
                    return;
                }

                activateMatch(isPrev ? searchIdx - 1 : searchIdx + 1);
            }

            document.getElementById('pdfSearchBtn').addEventListener('click', async function (e) {
                e.preventDefault();
                await handleSearchEnter(false);
            });

            document.getElementById('pdfSearchNext').addEventListener('click', function (e) {
                e.preventDefault();
                if (!searchMatches.length) return;
                activateMatch(searchIdx + 1);
            });

            document.getElementById('pdfSearchPrev').addEventListener('click', function (e) {
                e.preventDefault();
                if (!searchMatches.length) return;
                activateMatch(searchIdx - 1);
            });

            srchInput.addEventListener('keydown', async function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    await handleSearchEnter(e.shiftKey);
                }

                if (e.key === 'Escape') {
                    clearSearch(true);
                }
            });

            srchInput.addEventListener('input', function () {
                if (srchInput.value.trim() !== lastSearchQuery) {
                    searchMatches = [];
                    searchIdx = -1;
                    srchCount.textContent = '';
                    srchInput.classList.remove('search-found', 'search-not-found');
                }
            });

            function updatePageNo(n) {
                pageNoEl.textContent = n + '/' + totalPages;
            }

            function getCurrentPage() {
                const outerR = outerCont.getBoundingClientRect();
                let cur = 1;

                pagesCont.querySelectorAll('.pdf-page-wrapper').forEach(w => {
                    if (w.getBoundingClientRect().top <= outerR.top + outerR.height * 0.5) {
                        cur = +w.dataset.page;
                    }
                });

                return cur;
            }

            function scrollToPage(n) {
                const w = pagesCont.querySelector(`[data-page="${n}"]`);
                if (w) {
                    w.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    updatePageNo(n);
                }
            }

            outerCont.addEventListener('scroll', () => updatePageNo(getCurrentPage()));

            document.getElementById('pdfBtnFirst').addEventListener('click', () => scrollToPage(1));
            document.getElementById('pdfBtnLast').addEventListener('click', () => scrollToPage(totalPages));

            document.getElementById('pdfBtnPrev').addEventListener('click', () => {
                const c = getCurrentPage();
                if (c > 1) scrollToPage(c - 1);
            });

            document.getElementById('pdfBtnNext').addEventListener('click', () => {
                const c = getCurrentPage();
                if (c < totalPages) scrollToPage(c + 1);
            });

            function setScale(s) {
                scale = Math.round(Math.max(0.5, Math.min(3.0, s)) * 100) / 100;
                zoomValEl.textContent = Math.round(scale * 100) + '%';
                rerenderAll();
            }

            document.getElementById('pdfBtnZoomIn').addEventListener('click', () => {
                const n = ZOOMS.find(z => z > scale);
                if (n) setScale(n);
            });

            document.getElementById('pdfBtnZoomOut').addEventListener('click', () => {
                const p = [...ZOOMS].reverse().find(z => z < scale);
                if (p) setScale(p);
            });

            document.getElementById('pdfBtnFitW').addEventListener('click', async () => {
                const vp = (await pdfDoc.getPage(1)).getViewport({ scale: 1 });
                setScale((outerCont.clientWidth - 32) / vp.width);
            });

            document.getElementById('pdfBtnFitH').addEventListener('click', async () => {
                const vp = (await pdfDoc.getPage(1)).getViewport({ scale: 1 });
                setScale((outerCont.clientHeight - 32) / vp.height);
            });

            document.getElementById('pdfBtnFit').addEventListener('click', async () => {
                const vp = (await pdfDoc.getPage(1)).getViewport({ scale: 1 });
                setScale(Math.min(
                    (outerCont.clientWidth - 32) / vp.width,
                    (outerCont.clientHeight - 32) / vp.height
                ));
            });

            (function () {
                const tBtn = document.getElementById('pdftoolbarToggleBtn');
                const sEl = document.getElementById('pdftoolbarSlideContainer');

                if (!sEl) return;

                if (window.innerWidth < 768) {
                    sEl.style.maxHeight = '0';
                    sEl.style.opacity = '0';

                    if (tBtn) {
                        tBtn.addEventListener('click', function () {
                            const hidden = sEl.style.maxHeight === '0px' || sEl.style.maxHeight === '0';
                            sEl.style.maxHeight = hidden ? '180px' : '0';
                            sEl.style.opacity = hidden ? '1' : '0';
                            tBtn.querySelector('i').style.transform = hidden ? 'rotate(180deg)' : 'rotate(0deg)';
                            tBtn.setAttribute('aria-label', hidden ? 'Hide PDF Toolbar' : 'Show PDF Toolbar');
                        });
                    }
                } else {
                    sEl.style.maxHeight = 'none';
                    sEl.style.opacity = '1';
                }
            })();

            let resizeFitTimer = null;
            window.addEventListener('resize', () => {
                clearTimeout(resizeFitTimer);
                resizeFitTimer = setTimeout(async () => {
                    if (window.innerWidth < 768) {
                        await fitPdfToWidthOnLoad();
                        triggerVisible();
                    } else {
                        redrawAll();
                    }
                }, 180);
            });
        })();

        document.addEventListener('DOMContentLoaded', function () {
            const pdfArea = document.querySelector('.pdfviewer');
            if (!pdfArea || !window.pdfViewer) return;

            let lastDistance = null;

            function getDistance(touches) {
                const dx = touches[0].clientX - touches[1].clientX;
                const dy = touches[0].clientY - touches[1].clientY;
                return Math.sqrt(dx * dx + dy * dy);
            }

            pdfArea.addEventListener('touchmove', function (e) {
                if (e.touches.length === 2) {
                e.preventDefault();

                const distance = getDistance(e.touches);

                if (lastDistance) {
                    if (distance - lastDistance > 12) {
                    pdfViewer.setZoomIn();
                    lastDistance = distance;
                    } else if (lastDistance - distance > 12) {
                    pdfViewer.setZoomOut();
                    lastDistance = distance;
                    }
                } else {
                    lastDistance = distance;
                }
                }
            }, { passive: false });

            pdfArea.addEventListener('touchend', function () {
                lastDistance = null;
            });
        });
    </script>
@endif
            @if ($lesson->host == 'Word')
                <iframe class="w-100  h-100 mobile-min-height"
                        src="https://docs.google.com/gview?url={{assetPath($lesson->video_url) }}&embedded=true"></iframe>
            @endif
            @if ($lesson->host == 'Excel' || $lesson->host == 'PowerPoint')
                <iframe class="w-100  h-100 mobile-min-height"
                        src="https://view.officeapps.live.com/op/view.aspx?src={{assetPath($lesson->video_url) }}"></iframe>
            @endif

            @if ($lesson->host == 'GoogleDrive')
                {{--                <iframe class="w-100  h-100" controlsList="nodownload"--}}
                {{--                        src="https://drive.google.com/uc?id={{ $lesson->video_url }}&export=view"></iframe>--}}
                <iframe class="w-100  h-100" controlsList="nodownload"
                        src="https://drive.google.com/file/d/{{$lesson->video_url}}/preview"></iframe>
            @endif

            @if ($lesson->host == 'Text')
                <div class="w-100  h-100 textViewer">

                </div>
                <script>
                    $(".textViewer").load("{{assetPath($lesson->video_url) }}");
                </script>
            @endif


            {{-- Iframe video --}}
{{--            @push('js')--}}
{{--                @if ($lesson->host == 'Iframe')--}}
{{--                    <script>--}}
{{--                        $(document).ready(function (e) {--}}
{{--                            if ($('#video-id').length) {--}}
{{--                                var iframe = document.getElementById("video-id");--}}
{{--                                // console.log(iframe);--}}
{{--                                var video = iframe.contentDocument.body.getElementsByTagName("video")[0];--}}
{{--                                var supposedCurrentTime = 0;--}}
{{--                                video.addEventListener('timeupdate', function () {--}}
{{--                                    if (!video.seeking) {--}}
{{--                                        supposedCurrentTime = video.currentTime;--}}
{{--                                    }--}}
{{--                                });--}}
{{--                                // prevent user from seeking--}}
{{--                                video.addEventListener('seeking', function () {--}}
{{--                                    // guard agains infinite recursion:--}}
{{--                                    // user seeks, seeking is fired, currentTime is modified, seeking is fired, current time is modified, ....--}}
{{--                                    var delta = video.currentTime - supposedCurrentTime;--}}
{{--                                    if (Math.abs(delta) > 0.01) {--}}
{{--                                        console.log("Seeking is disabled");--}}
{{--                                        video.currentTime = supposedCurrentTime;--}}
{{--                                    }--}}
{{--                                });--}}
{{--                                // delete the following event handler if rewind is not required--}}
{{--                                video.addEventListener('ended', function () {--}}
{{--                                    if (!completeRequest) {--}}
{{--                                        lessonAutoComplete(course_id, {{ showPicName(Request::url()) }});--}}
{{--                                        completeRequest = true;--}}
{{--                                    }--}}

{{--                                    // reset state in order to allow for rewind--}}
{{--                                    console.log('video end');--}}
{{--                                    supposedCurrentTime = 0;--}}
{{--                                });--}}
{{--                            }--}}
{{--                        });--}}
{{--                    </script>--}}
{{--                @endif--}}
{{--            @endpush--}}
            @if ($lesson->host == 'Zip')
                <style>
                    .parent {
                        position: fixed;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }

                    .child {
                        position: relative;
                        font-size: 10vw;
                    }
                </style>
                <div class="w-100 parent  h-100 ">
                    <div class="">
                        <div class="row">
                            <div class="col  text-center">
                                <div class="child">
                                    <a class="theme_btn " href="{{assetPath($lesson->video_url) }}"
                                       download="">{{ __('frontend.Download File') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            @endif

        @endif
        {{-- </div> --}}


        <input type="hidden" id="url" value="{{ url('/') }}">
        <div class="floating-title position-fixed">
            <p class="font_16 d-flex align-items-center">
                <span class="header__common_btn me-2 play_toggle_btn"><i
                        class="ti-menu-alt"></i></span> {{ @$total }} {{ __('common.Lessons') }}
            </p>
        </div>
        <div class="course__play_warp courseListPlayer ">
            <div class="play_warp_header d-flex justify-content-between">
                <h3 class="font_16 mb-0 lesson_count default-font d-flex align-items-center">
                    <span class="play_toggle_btn header__common_btn me-2 d-none d-lg-flex">
                        <i class="fas fa-expand"></i>
                    </span>
                    {{-- <a href="{{ courseDetailsUrl(@$course->id, @$course->type, @$course->slug) }}"
                        class="theme_btn_mini">
                        <i class="fas fa-arrow-left"></i>
                    </a> --}}
                    <span>
                        <strong class="d-block d-lg-none">{{ $course->title }}</strong>
                        <span class="d-block">
                            {{ @$total }} {{ __('common.Lessons') }}
                        </span>
                    </span>
                </h3>
                @if($lesson->is_quiz!=1)
                    <button class="theme_btn p-2" type="button" data-bs-toggle="modal"
                            data-bs-target="#qnamodal">{{__('common.Q&A')}}</button>
                @endif


            </div>
            <div class="course__play_list">
                @php
                    $i = 1;
                @endphp
                <div class="theme_according mb_30 accordion" id="accordion1">
                    @foreach ($chapters as $k => $chapter)
                        <div class="accordion-item">
                            <div class="accordion-header" id="heading{{ $chapter->id }}">
                                <h5 class="mb-0">
                                    <button class="accordion-button collapsed" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $chapter->id }}" aria-expanded="false"
                                            aria-controls="collapse{{ $chapter->id }}">
                                        {{ $chapter->name }} <br>
                                        <span class="course_length nowrap">
                                            @if (!isModuleActive('Assignment'))
                                                {{ count($chapter->lessons->where('is_assignment', 0)) }}
                                            @else
                                                {{ count($chapter->lessons) }}
                                            @endif

                                            {{ __('frontend.Lectures') }}
                                        </span>
                                    </button>
                                </h5>
                            </div>
                            <div class="collapse" id="collapse{{ $chapter->id }}"
                                 aria-labelledby="heading{{ $chapter->id }}" data-bs-parent="#accordion1">
                                <div class="accordion-body">
                                    <div class="curriculam_list">
                                        @if (isset($lessons))

                                            @foreach ($lessons as $key => $singleLesson)
                                                @if ($singleLesson->chapter_id == $chapter->id)
                                                    @php
                                                        if ($singleLesson->is_quiz == 1 && $singleLesson->quiz->count() == 0) {
                                                            continue;
                                                        }
                                                        if ($singleLesson->is_assignment == 1 && !isModuleActive('Assignment')) {
                                                            continue;
                                                        }
                                                    @endphp
                                                    <div class="single_play_list"
                                                         id="single_lesson_{{ $singleLesson->id }}">
                                                        <a class="@if (showPicName(Request::url()) == $singleLesson->id) active @endif"
                                                           href="#">

                                                            @if ($singleLesson->is_quiz == 1)
                                                                <div class="course_play_name">

                                                                    <label class="primary_checkbox d-flex mb-0">
                                                                        <input type="checkbox"
                                                                               {{ $singleLesson->completed && $singleLesson->completed->status == 1 ? 'checked' : '' }}
                                                                               disabled>
                                                                        <span class="checkmark mr_15"
                                                                              style="cursor: not-allowed"></span>

                                                                        <i class="ti-check-box"></i>
                                                                    </label>
                                                                    @foreach ($singleLesson->quiz as $quiz)
                                                                        <span class="quizLink"
                                                                              onclick="goFullScreen({{ $course->id }},{{ $singleLesson->id }})">
                                                                            <span class="quiz_name">{{ $i }}.
                                                                                {{ @$quiz->title }}</span>
                                                                        </span>
                                                                </div>
                                                                @endforeach
                                                            @else
                                                                <div class="course_play_name">
                                                                    @if (request()->route('lesson_id') == $singleLesson->id)
                                                                        <div
                                                                            class="remember_forgot_pass d-flex justify-content-between">
                                                                            <label class="primary_checkbox d-flex mb-0">
                                                                                @if ($isEnrolled)
                                                                                    <input type="checkbox"
                                                                                           {{ $singleLesson->completed && $singleLesson->completed->status == 1 ? 'checked' : '' }}
                                                                                           disabled>
                                                                                    <span style="cursor: not-allowed"
                                                                                          class="checkmark mr_15"></span>
                                                                                    <i class="ti-control-play"></i>
                                                                                @else
                                                                                    <i class="ti-control-play"></i>
                                                                                @endif
                                                                            </label>
                                                                        </div>
                                                                    @else
                                                                        <label class="primary_checkbox d-flex mb-0">
                                                                            <input type="checkbox"
                                                                                {{ $singleLesson->completed && $singleLesson->completed->status == 1 ? 'checked' : '' }}>
                                                                            <span style="cursor: not-allowed"
                                                                                  class="checkmark mr_15"></span>

                                                                            <i class="ti-control-play"></i>
                                                                        </label>
                                                                    @endif

                                                                    <span
                                                                        onclick="goFullScreen({{ $course->id }},{{ $singleLesson->id }})">{{ $i }}.
                                                                    {{ $singleLesson->name }} </span>
                                                                </div>
                                                                <span
                                                                    class="course_play_duration nowrap">{{ MinuteFormat($singleLesson->duration) }}</span>
                                                            @endif
                                                        </a>
                                                    </div>
                                                    @php
                                                        $i++;
                                                    @endphp
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="row justify-content-center text-center">
                    @if ($certificate && $certificate->id > 0  && !Settings('manually_assign_certificate'))
                        @if ($quizPass)
                            @auth()
                                @if ($percentage >= 100)
                                    @if (isModuleActive('Survey') && $course->survey)
                                        @if (Settings('must_survey_before_certificate'))
                                            @if (auth()->user()->attendSurvey($course->survey))
                                                <a href="{{ route('getCertificate', [$course->id, $course->title]) }}"
                                                   class="theme_btn certificate_btn mt-5 mb-5">
                                                    {{ __('frontend.Get Certificate') }}
                                                </a>
                                                @if (isModuleActive('MyClass'))
                                                    <a href="{{ route('get-transcript', [$course->id, auth()->user()->id]) }}"
                                                       class="theme_btn certificate_btn mt-5 mb-5 ms-2"
                                                       target="__blank">{{ __('class.Get Transcript') }}</a>
                                                @endif
                                            @else
                                                <button type="button" data-bs-toggle="modal"
                                                        data-bs-target="#assignSubmit"
                                                        class="theme_btn certificate_btn mt-5 mb-5">
                                                    {{ __('frontend.Survey') }}
                                                </button>
                                                <small>
                                                    {{ __('frontend.You must attend survey before getting certificate') }}
                                                </small>
                                            @endif
                                        @else
                                            @if (!auth()->user()->attendSurvey($course->survey))
                                                <button type="button" data-bs-toggle="modal"
                                                        data-bs-target="#assignSubmit"
                                                        class="theme_btn certificate_btn mt-5 mb-5 me-1">
                                                    {{ __('frontend.Survey') }}
                                                </button>
                                            @endif
                                            <a href="{{ route('getCertificate', [$course->id, $course->title]) }}"
                                               class="theme_btn certificate_btn mt-5 mb-5 ms-1">
                                                {{ __('frontend.Get Certificate') }}
                                            </a>
                                            @if (isModuleActive('MyClass'))
                                                <a href="{{ route('get-transcript', [$course->id, auth()->user()->id]) }}"
                                                   class="theme_btn certificate_btn mt-5 mb-5 ms-2"
                                                   target="__blank">{{ __('class.Get Transcript') }}</a>
                                            @endif
                                        @endif
                                    @else
                                        <a href="{{ route('getCertificate', [$course->id, $course->title]) }}"
                                           class="theme_btn certificate_btn mt-5 mb-5">
                                            {{ __('frontend.Get Certificate') }}
                                        </a>
                                        @if (isModuleActive('MyClass'))
                                            <a href="{{ route('get-transcript', [$course->id, auth()->user()->id]) }}"
                                               class="theme_btn certificate_btn mt-5 mb-5 ms-2"
                                               target="__blank">{{ __('class.Get Transcript') }}</a>
                                        @endif
                                    @endif
                                @endif
                            @endauth
                        @endif
                    @endif

                </div>
                <div class="pb-5 mb-5 d-none">
                    <div>{{ __('frontend.Current Time') }}: <span id="currentTime">0</span></div>
                    <div>{{ __('frontend.Total Time') }} : <span id="totalTime">0</span></div>
                    <div>{{ __('frontend.Status') }} : <span class="status"></span></div>
                </div>
            </div>
        </div>

    </div>


    <div class="modal fade " id="ShareLink" tabindex="-1" role="dialog" aria-labelledby=" " aria-hidden="true">
        <div class="modal-dialog modal-lg " role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ __('frontend.Share this course') }}

                    </h5>
                </div>

                <div class="modal-body">


                    <div class="row mb-20">
                        <div class="col-md-12">
                            <input type="text" required class="primary_input mb_20" name=""
                                   value="{{ URL::current() }}">
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="social_btns ">
                                <a target="_blank"
                                   href="https://www.facebook.com/sharer/sharer.php?u={{ URL::current() }}"
                                   class="social_btn fb_bg"> <i class="fab fa-facebook-f"></i>
                                </a>
                                <a target="_blank"
                                   href="https://twitter.com/intent/tweet?text={{ $course->title }}&amp;url={{ URL::current() }}"
                                   class="social_btn Twitter_bg"> <i class="fab fa-twitter"></i> </a>
                                <a target="_blank"
                                   href="https://pinterest.com/pin/create/link/?url={{ URL::current() }}&amp;description={{ $course->title }}"
                                   class="social_btn Pinterest_bg"> <i class="fab fa-pinterest-p"></i> </a>
                                <a target="_blank"
                                   href="https://www.linkedin.com/shareArticle?mini=true&amp;url={{ URL::current() }}&amp;title={{ $course->title }}&amp;summary={{ $course->title }}"
                                   class="social_btn Linkedin_bg"> <i class="fab fa-linkedin-in"></i> </a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>


    <div class="modal fade " id="courseRating" tabindex="-1" role="dialog" aria-labelledby=" " aria-hidden="true">
        <div class="modal-dialog modal-lg " role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ __('frontend.Rate this course') }}

                    </h5>
                </div>
                <div class="modal-body">


                    <div class="row mb-20">
                        <div class="col-md-12">
                            <div class="rating_star text-end">

                                @php
                                    $PickId = $course->id;
                                @endphp
                                @if (Auth::check())
                                    @if (Auth::user()->role_id == 3)
                                        @if (!in_array(Auth::user()->id, $reviewer_user_ids))
                                            <div class="star_icon d-flex align-items-center justify-content-between">
                                                <a class="rating">
                                                    <input type="radio" id="star5" name="rating" value="5"
                                                           class="rating"/><label class="full" for="star5"
                                                                                  id="star5" title="Awesome - 5 stars"
                                                                                  onclick="Rates(5, {{ @$PickId }})"></label>

                                                    <input type="radio" id="star4" name="rating" value="4"
                                                           class="rating"/><label class="full" for="star4"
                                                                                  title="Pretty good - 4 stars"
                                                                                  onclick="Rates(4, {{ @$PickId }})"></label>

                                                    <input type="radio" id="star3" name="rating" value="3"
                                                           class="rating"/><label class="full" for="star3"
                                                                                  title="Meh - 3 stars"
                                                                                  onclick="Rates(3, {{ @$PickId }})"></label>
                                                    <input type="radio" id="star2" name="rating" value="2"
                                                           class="rating"/><label class="full" for="star2"
                                                                                  title="Kinda bad - 2 stars"
                                                                                  onclick="Rates(2, {{ @$PickId }})"></label>

                                                    <input type="radio" id="star1" name="rating" value="1"
                                                           class="rating"/><label class="full" for="star1"
                                                                                  title="Bad  - 1 star"
                                                                                  onclick="Rates(1,{{ @$PickId }})"></label>

                                                </a>
                                            </div>
                                        @endif
                                    @endif
                                @else
                                    <p class="font_14 f_w_400 mt-0"><a href="{{ url('login') }}"
                                                                       class="theme_color2">{{ __('frontend.Sign In') }}</a>
                                        {{ __('frontend.or') }} <a class="theme_color2"
                                                                   href="{{ url('register') }}">{{ __('frontend.Sign Up') }}</a>
                                        {{ __('frontend.as student to post a review') }}</p>
                                @endif

                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal cs_modal fade admin-query" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('frontend.Review') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="ti-close "></i></button>
                </div>

                <form action="{{ route('submitReview') }}" method="Post">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="course_id" id="rating_course_id" value="">
                        <input type="hidden" name="rating" id="rating_value" value="">

                        <div class="text-center">
                            <textarea class="form-control" name="review" name="" id=""
                                      placeholder="{{ __('frontend.Write your review') }}" cols="30"
                                      rows="10">{{ old('review') }}</textarea>
                            <span class="text-danger" role="alert">{{ $errors->first('review') }}</span>
                        </div>


                    </div>
                    <div class="modal-footer justify-content-center">
                        <div class="mt-40">
                            <button type="button" class="theme_line_btn me-2"
                                    data-bs-dismiss="modal">{{ __('common.Cancel') }}
                            </button>
                            <button class="theme_btn " type="submit">{{ __('common.Submit') }}</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    @include(theme('partials._qna_modal'))
    <div id="logDisplay">
    </div>
    @if (isModuleActive('Survey') && $course->survey)
        @include(theme('partials._survey_model'))
    @endif
    @if(isModuleActive("WhatsappSupport"))
        @include('whatsappsupport::partials._popup')
    @endif

@endsection
@push('js')
    @if(isModuleActive("WhatsappSupport"))
        <script src="{{assetPath('whatsapp-support/scripts.js')}}{{assetVersion()}}"></script>
    @endif

    <script>
        // =========================================
        // PDF TOOLBAR TOGGLE
        // =========================================
        (function () {
            var toggleBtn = document.getElementById('pdftoolbarToggleBtn');
            var slideContainer = document.getElementById('pdftoolbarSlideContainer');
            if (toggleBtn && slideContainer && window.innerWidth < 768) {
                toggleBtn.addEventListener('click', function () {
                    var isHidden = slideContainer.classList.toggle('pdftoolbar-hidden');
                    toggleBtn.classList.toggle('collapsed', isHidden);
                    toggleBtn.setAttribute(
                        'aria-label',
                        isHidden ? 'Show PDF Toolbar' : 'Hide PDF Toolbar'
                    );
                });
            }
        })();

        // =========================================
        // SCROLL HIDE/SHOW TOOLBAR (MOBILE ONLY)
        // =========================================
        let lastScrollTop = 0;
        const toolbar = document.querySelector('.pdftoolbar');

        window.addEventListener('scroll', function () {
            if (!toolbar) return; // safety — only runs on PDF lesson pages
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const slideContainer = document.getElementById('pdftoolbarSlideContainer');

            // Only apply scroll-hide on mobile AND only when toolbar is visible
            if (window.innerWidth < 768 && slideContainer && !slideContainer.classList.contains('pdftoolbar-hidden')) {
                if (scrollTop > lastScrollTop) {
                    toolbar.style.transform = 'translateY(100%)';
                } else {
                    toolbar.style.transform = 'translateY(0)';
                }
            }
            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
        });
    </script>

    <script>
        $(document).ready(function () {
            if ($('.active').length) {
                let active = $('.active');
                let parent = active.parents('.collapse').first();
                parent.addClass('show');
            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function () {
            let course = '{{ $course->id }}';
            let lesson = '{{ $lesson->id }}';

            /*       $("iframe").each(function () {
                       //Using closures to capture each one
                       var iframe = $(this);
                       iframe.on("load", function () { //Make sure it is fully loaded
                           iframe.contents().click(function (event) {
                               iframe.trigger("click");
                           });

                       });

                       iframe.click(function () {
                           $.ajax({
                               type: 'POST',
                               "_token": "{{ csrf_token() }}",
                            url: '{{ route('lesson.complete.ajax') }}',
                            data: {course_id: course, lesson_id: lesson},
                            success: function (data) {

                            }
                        });
                    });
                });*/

            if (window.outerWidth < 425) {
                $('.courseListPlayer').toggleClass("active");
                $('.course_fullview_wrapper').toggleClass("active");
            }


            $(".completeAndPlayNext").click(function () {
                $.ajax({
                    type: 'POST',
                    "_token": "{{ csrf_token() }}",
                    url: '{{ route('lesson.complete.ajax') }}',
                    data: {
                        course_id: course,
                        lesson_id: lesson
                    },
                    success: function (data) {
                        if ($('#next_lesson_btn').length) {
                            $('#next_lesson_btn').trigger('click');
                        } else {
                            location.reload();
                        }
                    }
                });
            });
        });

    </script>

    @if ($lesson->host == 'Self' || $lesson->host == 'AmazonS3' || $lesson->host == 'URL' || $lesson->host == 'Youtube' || $lesson->host == 'Iframe'|| $lesson->host == 'Vimeo')
        <script src="{{assetPath('plugins/plyr/plyr.js')}}" type="application/javascript"></script>
        <link rel="stylesheet" href="{{assetPath('plugins/plyr/plyr.css')}}">
        <script>

            const player = new Plyr('#video-id', {
                controls: [
                    'play-large', 'rewind', 'play', 'fast-forward', '{{Settings('show_seek_bar')?'progress':''}}', 'current-time',
                    'duration', 'mute', 'volume', 'captions', 'settings', 'pip', 'airplay', 'fullscreen'
                ],
                autoplay: true,
                muted: false,
                volume: 0.8,
                clickToPlay: true,
                seekTime: 5,
                speed: {selected: 1, options: [0.5, 1, 1.5, 2]},
                fullscreen: {enabled: true, fallback: true},
                keyboard: {focused: true, global: false},
                tooltips: {controls: true, seek: true},
                youtube: {
                     controls: 1,
                    modestBranding: false,
                    showinfo: 1,
                    rel: 1,
                    iv_load_policy: 3,
                    cc_load_policy: 1,
                    autoplay: false,
                    loop: false,
                    mute: false,
                    start: 0,
                    end: null
                }
            });

            player.on('ended', () => {
                lessonAutoComplete(course_id, {{ showPicName(Request::url()) }})
            });


        </script>

    @endif

    @if ($lesson->host == 'm3u8')
        <script>
            let myFP = fluidPlayer(
                'video-id', {
                    "layoutControls": {
                        "controlBar": {
                            "autoHideTimeout": 3,
                            "animated": true,
                            "autoHide": true
                        },
                        "htmlOnPauseBlock": {
                            "html": null,
                            "height": null,
                            "width": null
                        },
                        "autoPlay": true,
                        "mute": false,
                        "hideWithControls": true,
                        "allowTheatre": true,
                        "playPauseAnimation": true,
                        "playbackRateEnabled": true,
                        "allowDownload": false,
                        "playButtonShowing": true,
                        "fillToContainer": true,
                        "posterImage": "{{getCourseImage($course->image)}}",
                        "doubleClickFullscreen": true,
                        "keyboardControl": true,
                    },

                    "vastOptions": {
                        "adList": [],
                        "adCTAText": false,
                        "adCTATextPosition": ""
                    }
                });
        </script>
    @endif
    <script src="{{ assetPath('frontend/infixlmstheme/js/app.js') }}{{assetVersion()}}"></script>
    <script src="{{ assetPath('backend/js/jquery-ui.js') }}{{assetVersion()}}"></script>
    <script src="{{ assetPath('backend/js/jquery.ui.touch-punch.min.js') }}{{assetVersion()}}"></script>

    <script src="{{ assetPath('frontend/infixlmstheme/js/class_details.js') }}"></script>
    <script src="{{ assetPath('frontend/infixlmstheme/js/full_screen_video.js') }}"></script>
    @if ($lesson->is_quiz == 1)
        @if (!$result)
            <script src="{{ assetPath('frontend/infixlmstheme/js/quiz_start.js') }}"></script>
        @endif
        @include(theme('partials._quiz_exp_script'))
    @endif



    @include(theme('partials.fullscreen_video._summernote_script'))
    @include(theme('partials.fullscreen_video._scorm_script'))

    <script>
        /* Mobile fallback: click-to-toggle for ellipsis dropdown */
        (function () {
            var btn = document.querySelector('.js-hover-dropdown-toggle');
            if (!btn) return;
            var menu = btn.closest('.dropdown').querySelector('.dropdown-menu');
            if (!menu) return;

            btn.addEventListener('click', function (e) {
                if (window.innerWidth >= 768) return; // hover handles desktop
                e.stopPropagation();
                var isOpen = menu.classList.contains('show');
                menu.classList.toggle('show', !isOpen);
            });

            document.addEventListener('click', function () {
                if (window.innerWidth < 768) menu.classList.remove('show');
            });
        })();
    </script>
@endpush
