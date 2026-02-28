@extends(theme('layouts.master'))
@section('title')
    {{Settings('site_title')  ? Settings('site_title')  : 'Infix LMS'}} | {{_trans('store.Products')}}
@endsection
@section('css')

    <link rel="stylesheet" href="{{ assetPath('modules/store/css/front_style.css') }}">
    <link rel="stylesheet" href="{{ assetPath('modules/store/css/select2.min.css')}}">
@endsection

@section('js')
    <script src="{{assetPath('frontend/infixlmstheme/js/classes.js')}}"></script>
    <script src="{{assetPath('modules/store/js/front_script.js')}}"></script>
    <script src="{{assetPath('modules/store/js/select2.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            // select js
            $(".search-hide").select2({
                minimumResultsForSearch: Infinity,
            });
        });


    </script>

@endsection
@section('mainContent')
    <x-store-page-section :request="$request" :categories="$product_categories"/>
@endsection

