@php
    use Illuminate\Support\Carbon;
    use Modules\BunnyStorage\Http\Controllers\BunnyStreamController;
@endphp
@extends(theme('layouts.full_screen_master'))

@section('title')
    {{ Settings('site_title') ? Settings('site_title') : 'Infix LMS' }} | {{ $course->title }}
@endsection

@section('css')
    <link href="{{ assetPath('backend/css/jquery-ui.css') }}{{ assetVersion() }}" rel="stylesheet">
    <link href="{{ assetPath('frontend/infixlmstheme/css/full_screen.css') }}{{ assetVersion() }}" rel="stylesheet"/>
    <link href="{{ assetPath('backend/css/summernote-bs5.min.css') }}{{ assetVersion() }}" rel="stylesheet">

    @if(isModuleActive("WhatsappSupport"))
        <link rel="stylesheet" href="{{ assetPath('whatsapp-support/style.css') }}{{ assetVersion() }}"/>
    @endif

    <style>
        .default-font {
            font-family: "Jost", sans-serif;
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
                padding: 0 20px;
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
            left: 0;
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
                box-shadow: 0 1px 10px rgb(0 0 0 / 10%);
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

        .pdftoolbar-toggle-btn.collapsed .pdftoolbar-arrow {
            transform: rotate(180deg);
        }

        .pdftoolbar-slide-container {
            width: 100%;
            overflow: hidden;
            max-height: 120px;
            transition: max-height 0.38s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.3s ease;
            opacity: 1;
        }

        .pdftoolbar-slide-container.pdftoolbar-hidden {
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
    </style>
@endsection

@section('mainContent')
    @php
        $video_lesson_hosts = ['Iframe', 'Image', 'PDF', 'Word', 'Excel', 'PowerPoint', 'Text', 'Zip', 'GoogleDrive', 'H5P', 'Editor'];
    @endphp

    @push('js')
        <script>
            var completeRequest = false;
        </script>
    @endpush

    @php
        if ($lesson->lessonQuiz->random_question == 1) {
            $questions = $lesson->lessonQuiz->assignRand;
        } else {
            $questions = $lesson->lessonQuiz->assign;
        }
    @endphp

    <script>
        @if(auth()->check())
            window.full_name = "{{ auth()->user()->name }}";
            window.course_name = "{{ $course->title }}";
            @if(isModuleActive('Org'))
                window.org_chart_name = "{{ auth()->user()->branch->group }}";
            @endif
        @else
            window.full_name = "Guest";
            window.course_name = "{{ $course->title }}";
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
                                    <a class="logo_img" href="{{ url('/') }}">
                                        <img class="p-2" src="{{ getLogoImage(Settings('logo')) }}" width="150"
                                             alt="{{ Settings('site_name') }}">
                                    </a>
                                </div>
                                <div class="category_search d-none d-lg-flex category_box_iner">
                                    <div class="input-group-prepend2 ps-3">
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
                                            <button class="theme_btn small_btn2 p-2 me-2 mr-lg-4 fs-14 completeAndPlayNext">
                                                {{ __('frontend.Mark as Complete') }}
                                            </button>
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
                                                    $current_page = (int) showPicName(Request::url());
                                                    $current_index = array_search(showPicName(Request::url()), $lesson_ids);
                                                @endphp

                                                @if (0 == array_search($current_page, $lesson_ids))
                                                    <a href="#" disabled="disabled" class="header__common_btn theme_button_disabled disabled">
                                                        <i class="fa fa-angle-left"></i>
                                                    </a>
                                                @else
                                                    <a href="#"
                                                       onclick="goFullScreen({{ $course->id }},{{ $lesson_ids[$current_index - 1] }})"
                                                       class="header__common_btn">
                                                        <i class="fa fa-angle-left"></i>
                                                    </a>
                                                @endif

                                                @if (array_search($current_page, $lesson_ids) < array_search(end($lesson_ids), $lesson_ids))
                                                    <a href="#" id="next_lesson_btn"
                                                       onclick="goFullScreen({{ $course->id }},{{ $lesson_ids[$current_index + 1] }})"
                                                       class="header__common_btn ms-2">
                                                        <i class="fa fa-angle-right"></i>
                                                    </a>
                                                @else
                                                    <a href="#" disabled class="header__common_btn theme_button_disabled ms-2 disabled" style="opacity:1">
                                                        <i class="fa fa-angle-right"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        @endif

                                        <a href="javascript:void(0)" class="ms-2 mobile_progress">
                                            <div class="progress p-2" data-percentage="{{ $percentage }}">
                                                <span class="progress-left"><span class="progress-bar"></span></span>
                                                <span class="progress-right"><span class="progress-bar"></span></span>
                                                <div class="progress-value">
                                                    <div class="headerSubProcess">{{ $percentage }}%</div>
                                                </div>
                                            </div>
                                        </a>

                                        <div class="header__common_btn dropdown ms-2">
                                            <button class="d-block w-100 h-100 bg-transparent border-0 outline-none p-0 currentColor js-hover-dropdown-toggle" type="button">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#ShareLink">
                                                        <i class="fa fa-share fs-12 me-2"></i>{{ __('frontend.Share') }}
                                                    </a>
                                                </li>
                                                @if (Auth::check() && Auth::user()->role_id == 3 && !in_array(Auth::user()->id, $reviewer_user_ids))
                                                    <li>
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#courseRating" class="dropdown-item">
                                                            <i class="fa fa-star me-2 fs-12"></i>{{ __('frontend.Leave a rating') }}
                                                        </a>
                                                    </li>
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

    <div class="course_fullview_wrapper {{ $lesson->is_quiz == 1 ? '' : 'video' }} {{ $lesson->host == 'Editor' ? 'flex-column justify-content-start p-4' : '' }}">
        @if ($lesson->is_quiz == 1)
            {{-- Quiz section unchanged --}}
        @elseif($lesson->is_assignment == 1)
            {{-- Assignment section unchanged --}}
        @else

            <script>
                const course_id = "{{ $lesson->course_id }}";
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
                            success: function () {
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

            @if ($lesson->host == 'Image')
                <img src="{{ assetPath($lesson->video_url) }}" alt="" class="w-100 h-100">
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
                        box-sizing: border-box;
                        overscroll-behavior: contain;
                        -webkit-overflow-scrolling: touch;
                    }

                    #pdfPagesContainer {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        width: 100%;
                        box-sizing: border-box;
                    }

                    .pdf-page-wrapper {
                        position: relative;
                        overflow: hidden;
                        max-width: 100%;
                        box-sizing: border-box;
                        margin: 0 auto 12px auto;
                    }

                    .pdf-canvas {
                        display: block;
                        max-width: 100%;
                        height: auto !important;
                    }

                    .pdf-text-layer,
                    .pdf-annot-layer {
                        position: absolute;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
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
                        transform-origin: 0 0;
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

                    @media (max-width: 767px) {
                        .pdftoolbar-slide-container:not(.pdftoolbar-hidden) {
                            max-height: 230px !important;
                        }

                        #pdfOuterContainer {
                            height: calc(100vh - 170px);
                            padding: 8px 0;
                            touch-action: none;
                        }

                        #pdfPagesContainer,
                        .pdf-page-wrapper,
                        .pdf-canvas,
                        .pdf-text-layer,
                        .pdf-annot-layer {
                            touch-action: none;
                        }
                    }

                    @media (min-width: 768px) {
                        .pdftoolbar-slide-container {
                            max-height: none !important;
                            opacity: 1 !important;
                            overflow: visible !important;
                        }

                        .pdftoolbar-toggle-wrapper {
                            display: none !important;
                        }
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

                        <div class="col-12 my-1 pdf-search-wrap">
                            <input type="text" id="pdfSearchInput" placeholder="Search in PDF…" autocomplete="off">
                            <button type="button" class="theme_btn small_btn_icon" id="pdfSearchBtn" title="Search"><i class="fa fa-search"></i></button>
                            <button type="button" class="theme_btn small_btn_icon" id="pdfSearchPrev" title="Prev match"><i class="fa fa-angle-up"></i></button>
                            <button type="button" class="theme_btn small_btn_icon" id="pdfSearchNext" title="Next match"><i class="fa fa-angle-down"></i></button>
                            <span id="pdfSearchCount"></span>

                            <span class="pdf-toolbar-sep"></span>
                            <span style="font-size:12px;color:#555;vertical-align:middle;">Highlight:</span>
                            <button type="button" class="pdf-hl-btn active-hl" data-color="yellow" title="Yellow"></button>
                            <button type="button" class="pdf-hl-btn" data-color="green" title="Green"></button>
                            <button type="button" class="pdf-hl-btn" data-color="blue" title="Blue"></button>
                            <button type="button" class="pdf-hl-btn" data-color="pink" title="Pink"></button>

                            <span class="pdf-toolbar-sep"></span>
                            <button type="button" class="theme_btn small_btn_icon" id="pdfCommentModeBtn" title="Add Comment">
                                <i class="fa fa-comment-alt"></i>
                            </button>
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
                        let highlights = [], comments = [];
                        let hlColor = 'yellow', commentMode = false, pendingCmt = null, editingCmt = null;
                        let searchMatches = [], searchIdx = -1, lastSearchQuery = '';
                        let observer;
                        let resizeFitTimer = null;

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

                        function safeJsonParse(value, fallback) {
                            try {
                                return JSON.parse(value);
                            } catch (e) {
                                return fallback;
                            }
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

                            const horizontalPadding = window.innerWidth < 768 ? 16 : 32;
                            const targetWidth = Math.max(100, containerWidth - horizontalPadding);
                            const fitScale = targetWidth / vp.width;

                            scale = Math.max(0.5, Math.min(3.0, Math.round(fitScale * 100) / 100));
                            zoomValEl.textContent = Math.round(scale * 100) + '%';

                            await rerenderAll();
                        }

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

                            const maxW = outerCont.clientWidth - (window.innerWidth < 768 ? 16 : 32);
                            const clampedWidth = Math.min(viewport.width, maxW);
                            const clampedHeight = Math.round(clampedWidth * (viewport.height / viewport.width));

                            wrap.style.width = `${clampedWidth}px`;
                            wrap.style.height = `${clampedHeight}px`;
                            wrap.innerHTML = '';
                            wrap.appendChild(canvas);
                            wrap.appendChild(textDiv);
                            wrap.appendChild(annotDiv);

                            await page.render({
                                canvasContext: ctx,
                                viewport: viewport
                            }).promise;

                            const textContent = await page.getTextContent();
                            buildTextLayer(textContent, textDiv, viewport);

                            wrap.onmouseup = null;
                            wrap.addEventListener('mouseup', e => onMouseUp(e, wrap, pageNum));

                            if (commentMode) wrap.classList.add('comment-mode');
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
                            if (commentMode) exitCommentMode();
                            else enterCommentMode();
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

                        function setScale(s) {
                            scale = Math.round(Math.max(0.5, Math.min(3.0, s)) * 100) / 100;
                            zoomValEl.textContent = Math.round(scale * 100) + '%';
                            rerenderAll();
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

                        function bindMobilePinchToViewerZoom() {
                            if (window.innerWidth >= 768) return;

                            let pinchStartDistance = 0;
                            let pinchBaseScale = scale;
                            let isPinching = false;

                            function getDistance(touches) {
                                const dx = touches[0].clientX - touches[1].clientX;
                                const dy = touches[0].clientY - touches[1].clientY;
                                return Math.sqrt(dx * dx + dy * dy);
                            }

                            outerCont.addEventListener('touchstart', function (e) {
                                if (e.touches.length === 2) {
                                    isPinching = true;
                                    pinchStartDistance = getDistance(e.touches);
                                    pinchBaseScale = scale;
                                    e.preventDefault();
                                }
                            }, { passive: false });

                            outerCont.addEventListener('touchmove', function (e) {
                                if (e.touches.length === 2) {
                                    e.preventDefault();

                                    if (!isPinching) {
                                        isPinching = true;
                                        pinchStartDistance = getDistance(e.touches);
                                        pinchBaseScale = scale;
                                    }

                                    const currentDistance = getDistance(e.touches);
                                    if (!pinchStartDistance) return;

                                    const ratio = currentDistance / pinchStartDistance;
                                    const nextScale = pinchBaseScale * ratio;
                                    const roundedScale = Math.round(nextScale * 20) / 20;

                                    if (Math.abs(roundedScale - scale) >= 0.08) {
                                        setScale(roundedScale);
                                    }
                                }
                            }, { passive: false });

                            outerCont.addEventListener('touchend', function () {
                                if (isPinching) {
                                    pinchBaseScale = scale;
                                }
                                if (event.touches && event.touches.length < 2) {
                                    isPinching = false;
                                    pinchStartDistance = 0;
                                }
                            }, { passive: true });

                            outerCont.addEventListener('gesturestart', function (e) {
                                e.preventDefault();
                            }, { passive: false });

                            outerCont.addEventListener('gesturechange', function (e) {
                                e.preventDefault();
                            }, { passive: false });

                            outerCont.addEventListener('gestureend', function (e) {
                                e.preventDefault();
                            }, { passive: false });
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

                            bindMobilePinchToViewerZoom();

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
                </script>
            @endif

            @if ($lesson->host == 'Word')
                <iframe class="w-100 h-100 mobile-min-height"
                        src="https://docs.google.com/gview?url={{ assetPath($lesson->video_url) }}&embedded=true"></iframe>
            @endif

            @if ($lesson->host == 'Excel' || $lesson->host == 'PowerPoint')
                <iframe class="w-100 h-100 mobile-min-height"
                        src="https://view.officeapps.live.com/op/view.aspx?src={{ assetPath($lesson->video_url) }}"></iframe>
            @endif

            @if ($lesson->host == 'GoogleDrive')
                <iframe class="w-100 h-100" controlsList="nodownload"
                        src="https://drive.google.com/file/d/{{ $lesson->video_url }}/preview"></iframe>
            @endif

            @if ($lesson->host == 'Text')
                <div class="w-100 h-100 textViewer"></div>
                <script>
                    $(".textViewer").load("{{ assetPath($lesson->video_url) }}");
                </script>
            @endif

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
                <div class="w-100 parent h-100">
                    <div class="">
                        <div class="row">
                            <div class="col text-center">
                                <div class="child">
                                    <a class="theme_btn" href="{{ assetPath($lesson->video_url) }}" download="">
                                        {{ __('frontend.Download File') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        @endif

        <input type="hidden" id="url" value="{{ url('/') }}">

        <div class="floating-title position-fixed">
            <p class="font_16 d-flex align-items-center">
                <span class="header__common_btn me-2 play_toggle_btn"><i class="ti-menu-alt"></i></span>
                {{ @$total }} {{ __('common.Lessons') }}
            </p>
        </div>

        <div class="course__play_warp courseListPlayer">
            <div class="play_warp_header d-flex justify-content-between">
                <h3 class="font_16 mb-0 lesson_count default-font d-flex align-items-center">
                    <span class="play_toggle_btn header__common_btn me-2 d-none d-lg-flex">
                        <i class="fas fa-expand"></i>
                    </span>
                    <span>
                        <strong class="d-block d-lg-none">{{ $course->title }}</strong>
                        <span class="d-block">{{ @$total }} {{ __('common.Lessons') }}</span>
                    </span>
                </h3>

                @if($lesson->is_quiz != 1)
                    <button class="theme_btn p-2" type="button" data-bs-toggle="modal" data-bs-target="#qnamodal">
                        {{ __('common.Q&A') }}
                    </button>
                @endif
            </div>
        </div>
    </div>
@endsection