@extends(theme('layouts.master'))
@section('title')
    {{Settings('site_title')  ? Settings('site_title')  : 'Infix LMS'}} | {{__('appointment.Instructors')}}
@endsection
@section('css')
    <link rel="stylesheet"
          href="{{assetPath('modules/appointment/frontend/plugins/price-range/ion.rangeslider.min.css')}}{{assetVersion()}}"/>
    @if (isRtl())
        <link rel="stylesheet"
              href="{{assetPath('modules/appointment/frontend/css/appointment.rtl.css')}}{{assetVersion()}}"/>
    @else
        <link rel="stylesheet"
              href="{{assetPath('modules/appointment/frontend/css/appointment.css')}}{{assetVersion()}}"/>
    @endif
@endsection

@section('js')
    <script
        src="{{assetPath('modules/appointment/frontend/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
    <script
        src="{{assetPath('modules/appointment/frontend/plugins/jquery-ui/jquery.ui.touch-punch.min.js')}}"></script>
    <script
        src="{{assetPath('modules/appointment/frontend/plugins/price-range/ion.rangeslider.min.js')}}"></script>

    <script src="{{assetPath('modules/appointment/frontend/js/main.js')}}"></script>
    <script src="{{assetPath('modules/appointment/js/filter.js')}}{{assetVersion()}}"></script>

    <script>
        $(document).ready(function () {

            $("#price_range").ionRangeSlider({
                min: 20,
                max: 1000,
            });
        })

    </script>
@endsection
@section('mainContent')

    <x-appointment-breadcrumb :request="$request"/>

    <x-appointment-instructor-page-section :request="$request" :categories="$categories"
                                           :levels="$levels"/>
@endsection

