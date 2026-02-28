@extends(theme('layouts.master'))
@section('title')
    {{Settings('site_title')  ? Settings('site_title')  : 'Infix LMS'}} | {{__('appointment.Appointment')}}
@endsection
@section('css')
    @if(isRtl())
        <link rel="stylesheet"
              href="{{assetPath('modules/appointment/frontend/css/appointment.rtl.css') }}{{assetVersion()}}"/>
    @else
        <link rel="stylesheet"
              href="{{assetPath('modules/appointment/frontend/css/appointment.css') }}{{assetVersion()}}"/>
    @endif
@endsection
@section('mainContent')
    <x-appointment :pages="$pages" :categories="$categories"/>
@endsection

