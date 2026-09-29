@extends(theme('layouts.master'))
@section('title'){{Settings('site_title')  ? Settings('site_title')  : 'Infix LMS'}} |    {{$course->title}} @endsection
@section('css')
    <!-- Content Protection for Quiz Answer Sheet -->
    <link rel="stylesheet" href="{{ assetPath('frontend/infixlmstheme/css/content-protection.css') }}">
@endsection
@section('js')
    <!-- Content Protection for Quiz Answer Sheet -->
    <script src="{{ assetPath('frontend/infixlmstheme/js/content-protection.js') }}"></script>
@endsection

@section('mainContent')
<div class="content-protected">

    <x-breadcrumb :banner="$frontendContent->quiz_page_banner" :title="$course->title"
                  :subTitle="trans('frontend.Result Preview')"/>



    <x-quiz-result-preview-page-section :quizTest="$quizTest" :user="$user" :course="$course"/>


</div>

@endsection


