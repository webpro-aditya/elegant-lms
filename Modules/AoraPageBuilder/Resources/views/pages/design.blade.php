@extends('aorapagebuilder::layouts.master')
@section('styles')
    <style>
        .aoraeditor-header .header_area {
            padding: 0 !important;
            position: relative !important;
            top: 0;
        }

        .aoraeditor-header {
            width: calc(100% - var(--editor-width));
            margin-left: var(--editor-width);
        }

        .aoraeditor-footer {
            width: calc(100% - var(--editor-width));
            margin-left: var(--editor-width);
        }
    </style>
    
    <style type="text/css" data-type="aoraeditor-style">
        /* Premium Spacing & Background Preset Stylesheet */
        [data-type="container"] {
            box-sizing: border-box;
            transition: all 0.25s ease-in-out;
        }

        /* Specific padding overrides matching the settings dropdowns */
        [data-padding-y="none"] {
            padding-top: 0px !important;
            padding-bottom: 0px !important;
        }
        [data-padding-y="small"] {
            padding-top: 15px !important;
            padding-bottom: 15px !important;
        }
        [data-padding-y="medium"] {
            padding-top: 30px !important;
            padding-bottom: 30px !important;
        }
        [data-padding-y="large"] {
            padding-top: 60px !important;
            padding-bottom: 60px !important;
        }
        [data-padding-y="huge"] {
            padding-top: 100px !important;
            padding-bottom: 100px !important;
        }

        [data-padding-x="none"] {
            padding-left: 0px !important;
            padding-right: 0px !important;
        }
        [data-padding-x="standard"] {
            padding-left: 15px !important;
            padding-right: 15px !important;
        }
        [data-padding-x="comfortable"] {
            padding-left: 48px !important;
            padding-right: 48px !important;
        }
        [data-padding-x="wide"] {
            padding-left: 80px !important;
            padding-right: 80px !important;
        }

        /* Background preset themes */
        [data-bg-style="light-gray"] {
            background-color: #f9fafb !important;
        }
        [data-bg-style="dark-slate"] {
            background-color: #111827 !important;
            color: #f9fafb !important;
        }
        [data-bg-style="dark-slate"] p {
            color: #d1d5db !important;
        }
        [data-bg-style="dark-slate"] h1,
        [data-bg-style="dark-slate"] h2,
        [data-bg-style="dark-slate"] h3,
        [data-bg-style="dark-slate"] h4,
        [data-bg-style="dark-slate"] h5,
        [data-bg-style="dark-slate"] h6 {
            color: #ffffff !important;
        }

        /* Dynamic customizable text colors */
        [data-text-color="light"] {
            color: #ffffff !important;
        }
        [data-text-color="light"] p,
        [data-text-color="light"] span,
        [data-text-color="light"] a,
        [data-text-color="light"] h1,
        [data-text-color="light"] h2,
        [data-text-color="light"] h3,
        [data-text-color="light"] h4,
        [data-text-color="light"] h5,
        [data-text-color="light"] h6 {
            color: #ffffff !important;
        }

        [data-text-color="dark"] {
            color: #1f2937 !important;
        }
        [data-text-color="dark"] p,
        [data-text-color="dark"] span,
        [data-text-color="dark"] a,
        [data-text-color="dark"] h1,
        [data-text-color="dark"] h2,
        [data-text-color="dark"] h3,
        [data-text-color="dark"] h4,
        [data-text-color="dark"] h5,
        [data-text-color="dark"] h6 {
            color: #1f2937 !important;
        }

        /* Automatically fix breadcrumb banner elements to white on dark images/banners */
        .breadcrumb_area .breadcam_wrap h3,
        .breadcrumb_area .breadcam_wrap span,
        .breadcrumb_area .breadcam_wrap a {
            color: #ffffff !important;
        }
        .breadcrumb_area .breadcam_wrap p {
            color: #e5e7eb !important;
        }

        /* Automatic Full-Bleed Banners Auto-Detection (forces zero padding/margins) */
        [data-type="container"]:has(.__banner),
        [data-type="container"]:has(.breadcrumb_area),
        [data-type="container"]:has(.bradcam_bg_1) {
            padding: 0px !important;
        }

        /* Ensure mobile-responsive padding for maximum readability */
        @media (max-width: 767px) {
            [data-type="container"]:not([data-padding-x="none"]):not([data-padding-x="standard"]) {
                padding-left: 20px !important;
                padding-right: 20px !important;
            }
            [data-type="container"]:not([data-padding-y="none"]):not([data-padding-y="small"]) {
                padding-top: 40px !important;
                padding-bottom: 40px !important;
            }
        }
    </style>
@endsection
@section('og_image'){{asset(Settings('logo'))}}@endsection
@section('content')
    {!! htmlspecialchars_decode($details)!!}
@endsection


@section('scripts')

    <script type="text/javascript" data-aoraeditor="script">
        $(function () {
            $('#content-area').aoraeditor({
                snippetsUrl: '{{route('page_builder.snippet')}}',
                title: '{{__('common.Design')}} {{$row->title}} {{__('frontendmanage.Page')}}',
                onSave: function (content) {
                    let isDisable = $('.aora-update-btn').hasClass('disable-btn')
                    if (isDisable) {
                        return false;
                    }
                    let jHtmlObject = jQuery(content);
                    let editor = jQuery("<p>").append(jHtmlObject);
                    editor.find(".aoraeditor-skip").remove();
                    let newHtml = editor.html();
                    newHtml = newHtml.replace(/\n\s+|\n/g, "");

                    var url = '{{ route("page_builder.pages.design.update",":id") }}';
                    url = url.replace(':id', {{$row->id}});


                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            'body': newHtml,
                            'lang': '{{$active}}',
                            _token: "{{csrf_token()}}"
                        },
                        success: function (data) {
                            location.reload();
                            toastr.success("{{__('frontendmanage.Page Designed Save Successfully')}}")
                        },error: function (error) {
                            location.reload();

                        }
                    });
                },
                onReady: function () {
                    console.log('ready');
                }, onSnippetsLoaded: function (t) {
                },
                onSnippetsError: function (t) {
                },
                onInitIframe: function (t, n, e) {
                },
                onContentChanged: function (t, n) {
                    changeElement();
                },
                onContainerDeleted: function (t, n, e) {
                    changeElement();
                },
                onContainerChanged: function (t, n, e) {
                    changeElement();
                },
                onContainerDuplicated: function (t, n, e, o) {
                    changeElement();
                },

                onComponentDeleted: function (t, n, e) {
                    changeElement();
                },
                onComponentChanged: function (t, n, e) {
                    changeElement();
                },
                onComponentDuplicated: function (t, n, e, o) {
                    changeElement();
                },

                containerSettingEnabled: true,
                containerSettingInitFunction: function (form, editor) {
                    form.html(`
                        <form class="form-horizontal" style="padding: 15px;">
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label style="font-weight: 600; margin-bottom: 8px; display: block; color: #374151; font-size: 13px;">Vertical Spacing (Top & Bottom)</label>
                                <select id="container-padding-y" class="form-control" style="width: 100%; height: 38px; border-radius: 6px; border: 1px solid #d1d5db; padding: 6px 12px; font-size: 14px;">
                                    <option value="none">Flush / None (0px)</option>
                                    <option value="small">Cozy (15px)</option>
                                    <option value="medium">Comfortable (30px)</option>
                                    <option value="large">Spacious (60px)</option>
                                    <option value="huge">Extra Spacious (100px)</option>
                                </select>
                                <span style="font-size: 11px; color: #6b7280; display: block; margin-top: 5px;">Adjust the space above and below this section's content.</span>
                            </div>
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label style="font-weight: 600; margin-bottom: 8px; display: block; color: #374151; font-size: 13px;">Horizontal Spacing (Left & Right)</label>
                                <select id="container-padding-x" class="form-control" style="width: 100%; height: 38px; border-radius: 6px; border: 1px solid #d1d5db; padding: 6px 12px; font-size: 14px;">
                                    <option value="none">Full-Bleed / None (0px)</option>
                                    <option value="standard">Standard Gutter (15px)</option>
                                    <option value="comfortable">Comfort Spacing (48px)</option>
                                    <option value="wide">Wide Spacing (80px)</option>
                                </select>
                                <span style="font-size: 11px; color: #6b7280; display: block; margin-top: 5px;">Choose how close content sits to the viewport boundaries.</span>
                            </div>
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label style="font-weight: 600; margin-bottom: 8px; display: block; color: #374151; font-size: 13px;">Content Text Color Preset</label>
                                <select id="container-color" class="form-control" style="width: 100%; height: 38px; border-radius: 6px; border: 1px solid #d1d5db; padding: 6px 12px; font-size: 14px;">
                                    <option value="default">Default / Automatic</option>
                                    <option value="light">Light (White Text for Dark Backgrounds)</option>
                                    <option value="dark">Dark (Dark Gray Text for Light Backgrounds)</option>
                                </select>
                                <span style="font-size: 11px; color: #6b7280; display: block; margin-top: 5px;">Force a text color style inside this entire container block.</span>
                            </div>
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label style="font-weight: 600; margin-bottom: 8px; display: block; color: #374151; font-size: 13px;">Background Preset Style</label>
                                <select id="container-bg" class="form-control" style="width: 100%; height: 38px; border-radius: 6px; border: 1px solid #d1d5db; padding: 6px 12px; font-size: 14px;">
                                    <option value="transparent">Transparent / Default</option>
                                    <option value="light-gray">Light Gray Accent</option>
                                    <option value="dark-slate">Dark Slate Block</option>
                                </select>
                                <span style="font-size: 11px; color: #6b7280; display: block; margin-top: 5px;">Change the background of this entire section block.</span>
                            </div>
                        </form>
                    `);

                    // Live handlers to apply style modifications dynamically inside the iframe
                    form.find('#container-padding-y').on('change', function () {
                        var container = editor.getSettingContainer();
                        if (container) {
                            var innerContainer = container.find('.aoraeditor-container-inner > div').first();
                            var val = $(this).val();
                            innerContainer.attr('data-padding-y', val);
                            editor.options.onContentChanged();
                        }
                    });

                    form.find('#container-padding-x').on('change', function () {
                        var container = editor.getSettingContainer();
                        if (container) {
                            var innerContainer = container.find('.aoraeditor-container-inner > div').first();
                            var val = $(this).val();
                            innerContainer.attr('data-padding-x', val);
                            editor.options.onContentChanged();
                        }
                    });

                    form.find('#container-color').on('change', function () {
                        var container = editor.getSettingContainer();
                        if (container) {
                            var innerContainer = container.find('.aoraeditor-container-inner > div').first();
                            var val = $(this).val();
                            innerContainer.attr('data-text-color', val);
                            editor.options.onContentChanged();
                        }
                    });

                    form.find('#container-bg').on('change', function () {
                        var container = editor.getSettingContainer();
                        if (container) {
                            var innerContainer = container.find('.aoraeditor-container-inner > div').first();
                            var val = $(this).val();
                            innerContainer.attr('data-bg-style', val);
                            editor.options.onContentChanged();
                        }
                    });
                },
                containerSettingShowFunction: function (form, container, editor) {
                    var innerContainer = container.find('.aoraeditor-container-inner > div').first();
                    
                    var padY = innerContainer.attr('data-padding-y') || 'large';
                    var padX = innerContainer.attr('data-padding-x') || 'comfortable';
                    var textColor = innerContainer.attr('data-text-color') || 'default';
                    var bgStyle = innerContainer.attr('data-bg-style') || 'transparent';

                    form.find('#container-padding-y').val(padY);
                    form.find('#container-padding-x').val(padX);
                    form.find('#container-color').val(textColor);
                    form.find('#container-bg').val(bgStyle);
                }

            });

            function changeElement() {
                $('.aora-update-btn').removeClass('disable-btn')
                $('.aora-update-btn').attr('disable', false)
            }

            $('.aoraeditor-topbar-right').prepend(
                '<a href="#" title="Responsive View" class="aoraeditor-ui aoraeditor-topbar-btn toggleResponsiveBar"><i class="fas fa-laptop"></i></a>'
            );
            $('.aoraeditor-topbar-right').prepend(
                '<a target="_blank" href="{{ $row->is_static!=1?url('pages/'.$row->slug):url($row->slug)}}" title="Frontend View" class="aoraeditor-ui aoraeditor-topbar-btn"><i class="fas fa-external-link-alt"></i></a>'
            );

            @if(isModuleActive('FrontendMultiLang'))
            @php
                $LanguageList = getLanguageList();
            @endphp
            $('.aoraeditor-topbar-right').prepend(
                '<select name="lang" id="languageChanger">' +
                @foreach ($LanguageList as $key => $language)
                    '<option value="{{ url()->current().'?lang='.$language->code}}" {{$active==$language->code?'selected':''}}>{{$language->native}}</option>' +
                @endforeach
                    '</select>'
            );

            $(document).on('change', '#languageChanger', function (e) {
                e.preventDefault();
                window.location.href = $(this).val();

            });
            @endif




            $('.aoraeditor-topbar').prependTo(".aoraeditor-header");
            $('.aoraeditor-topbar').appendTo(".aoraeditor-footer");

            // $(".aoraeditor-topbar-right").clone().appendTo(".aoraeditor-modal-footer");
            $(".aoraeditor-topbar-right").appendTo(".aoraeditor-modal-footer");


            $(document).on("click", ".toggleResponsiveBar", function () {
                $('.aoraeditor-topbar').toggleClass('hide-desktop')
            });


            function checkWindowSize() {
                if (window.matchMedia('(min-width: 992px)').matches) {
                    $('.aoraeditor-modal').addClass('show_modal');
                } else {
                    $('.aoraeditor-modal').removeClass('show_modal');

                }
                $(document).on("click", "[data-snippet]", function () {
                    $('.aoraeditor-modal').hide();
                });
            }

            checkWindowSize();
            $(window).on('resize', function () {
                checkWindowSize();
            });

        });


    </script>
@endsection
