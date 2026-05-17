@extends('aorapagebuilder::layouts.master')
@section('og_image'){{asset(Settings('logo'))}}@endsection

@section('content')
    <style>
        /* Premium Spacing & Padding for Pagebuilder Content */
        #content-area {
            padding-bottom: 60px; /* Elegant bottom padding before footer */
        }
        
        #content-area .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
        
        #content-area [class*="col-"] {
            padding-left: 48px !important;
            padding-right: 48px !important;
        }
        
        /* Make sure inner elements have nice line height and spacing for readability */
        #content-area p {
            line-height: 1.8 !important;
            margin-bottom: 20px !important;
            font-size: 15.5px !important;
            color: #4b5563 !important; /* Premium slate-grey color */
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
            color: #1f2937 !important;
        }

        @media (max-width: 767px) {
            #content-area [class*="col-"] {
                padding-left: 20px !important;
                padding-right: 20px !important;
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
    </script>

@endsection
