@extends(theme('layouts.dashboard_master'))
@section('title')
    {{Settings('site_title')  ? Settings('site_title')  : 'Infix LMS'}} | Invoice
@endsection
@section('css')
    <link href="{{assetPath('frontend/infixlmstheme/css/my_invoice.css')}}{{assetVersion()}}" rel="stylesheet"/>
@endsection
@section('mainContent')

    <x-subscription-invoice-page-section :enroll="$enroll"/>

@endsection
@section('js')
    <script src="{{ assetPath('frontend/infixlmstheme') }}/js/html2pdf.bundle.js{{assetVersion()}}"></script>
    <script src="{{ assetPath('frontend/infixlmstheme/js/my_invoice.js') }}{{assetVersion()}}"></script>
@endsection
