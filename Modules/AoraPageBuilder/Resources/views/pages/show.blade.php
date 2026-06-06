@extends('aorapagebuilder::layouts.master')
@section('og_image'){{asset(Settings('logo'))}}@endsection

@section('content')
    <style>
        /* Premium Spacing & Background Preset Stylesheet */
        #content-area {
            padding-bottom: 60px; /* Elegant bottom padding before footer */
        }

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


        /* Banner-specific text color presets inside a container */
        [data-banner-color="light"] .__banner,
        [data-banner-color="light"] .breadcrumb_area,
        [data-banner-color="light"] .__banner p,
        [data-banner-color="light"] .__banner span,
        [data-banner-color="light"] .__banner a,
        [data-banner-color="light"] .__banner h1,
        [data-banner-color="light"] .__banner h2,
        [data-banner-color="light"] .__banner h3,
        [data-banner-color="light"] .__banner h4,
        [data-banner-color="light"] .__banner h5,
        [data-banner-color="light"] .__banner h6,
        [data-banner-color="light"] .breadcrumb_area p,
        [data-banner-color="light"] .breadcrumb_area span,
        [data-banner-color="light"] .breadcrumb_area a,
        [data-banner-color="light"] .breadcrumb_area h1,
        [data-banner-color="light"] .breadcrumb_area h2,
        [data-banner-color="light"] .breadcrumb_area h3,
        [data-banner-color="light"] .breadcrumb_area h4,
        [data-banner-color="light"] .breadcrumb_area h5,
        [data-banner-color="light"] .breadcrumb_area h6 {
            color: #ffffff !important;
        }

        [data-banner-color="dark"] .__banner,
        [data-banner-color="dark"] .breadcrumb_area,
        [data-banner-color="dark"] .__banner p,
        [data-banner-color="dark"] .__banner span,
        [data-banner-color="dark"] .__banner a,
        [data-banner-color="dark"] .__banner h1,
        [data-banner-color="dark"] .__banner h2,
        [data-banner-color="dark"] .__banner h3,
        [data-banner-color="dark"] .__banner h4,
        [data-banner-color="dark"] .__banner h5,
        [data-banner-color="dark"] .__banner h6,
        [data-banner-color="dark"] .breadcrumb_area p,
        [data-banner-color="dark"] .breadcrumb_area span,
        [data-banner-color="dark"] .breadcrumb_area a,
        [data-banner-color="dark"] .breadcrumb_area h1,
        [data-banner-color="dark"] .breadcrumb_area h2,
        [data-banner-color="dark"] .breadcrumb_area h3,
        [data-banner-color="dark"] .breadcrumb_area h4,
        [data-banner-color="dark"] .breadcrumb_area h5,
        [data-banner-color="dark"] .breadcrumb_area h6 {
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

        /* Make sure inner elements have nice line height and spacing for readability */
        #content-area p {
            line-height: 1.8 !important;
            margin-bottom: 20px !important;
            font-size: 15.5px !important;
            color: #4b5563; /* Preserved base color, overrides work transparently */
        }
        
        #content-area h1, 
        #content-area h2, 
        #content-area h3, 
        #content-area h4, 
        #content-area h5, 
        #content-area h6 {
            margin-top: 35px !important;
            margin-bottom: 18px !important;
            font-weight: 700 !important;
            color: #1f2937;
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
            #content-area {
                padding-bottom: 40px;
            }
        }

    </style>

    @php
    $url =config('app.short_url').'/public';
    if (!config('app.has_public_folder')){
        $details = str_replace($url,config('app.short_url'),$details);
    }
    @endphp
    {!! htmlspecialchars_decode($details)!!}
@endsection


@section('scripts')
    @php
        $route =request()->route()->getName();
    @endphp
    @if($route=='blogs')
        <script src="{{assetPath('frontend/infixlmstheme/js/blogs.js')}}"></script>
    @elseif($route=='contact')
{{--        <script src="https://maps.googleapis.com/maps/api/js?key={{Settings('gmap_key') }}"></script>--}}
{{--        <script src="{{ assetPath('frontend/infixlmstheme') }}/js/map.js"></script>--}}
    @else
        <script src="{{assetPath('frontend/infixlmstheme/js/courses.js'.assetVersion())}}"></script>
    @endif
    @if (isModuleActive("Store") && \Illuminate\Support\Facades\Route::currentRouteName()=="store.products")
        <script src="{{assetPath('frontend/infixlmstheme/js/store.js')}}"></script>
    @endif

    <script>
        $('.ui-resizable-resizer').remove()
        
        // Strip legacy text-white classes from counter items in saved HTML
        // to prevent Bootstrap !important rules from overriding inline/custom colors
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.counter-item.text-white').forEach(function(el) {
                el.classList.remove('text-white');
            });
        });
    </script>

@endsection
