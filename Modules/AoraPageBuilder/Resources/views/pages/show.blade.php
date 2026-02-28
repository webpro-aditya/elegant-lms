@extends('aorapagebuilder::layouts.master')
@section('og_image'){{asset(Settings('logo'))}}@endsection

@section('content')
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
