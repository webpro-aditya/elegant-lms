<link rel="stylesheet" href="{{assetPath('backend/css/jquery-ui.css')}}{{assetVersion()}}"/>

<link rel="stylesheet" href="{{assetPath('backend/vendors/font_awesome/css/all.min.css')}}{{assetVersion()}}"/>
<link rel="stylesheet" href="{{assetPath('backend/css/themify-icons.css')}}{{assetVersion()}}"/>


<link rel="stylesheet" href="{{assetPath('chat/css/style.css')}}{{assetVersion()}}">
<link rel="stylesheet" href="{{assetPath('css/preloader.css')}}{{assetVersion()}}"/>
@if(isModuleActive("WhatsappSupport"))
    <link rel="stylesheet" href="{{assetPath('whatsapp-support/style.css')}}{{assetVersion()}}"/>
@endif

<link rel="stylesheet" href="{{assetPath('backend/css/fullcalendar.min.css')}}{{assetVersion()}}">

<link rel="stylesheet" href="{{assetPath('backend/css/app.css')}}{{assetVersion()}}">



@if(isRtl())
    <link rel="stylesheet" href="{{assetPath('backend/css/backend_style_rtl.css')}}{{assetVersion()}}"/>
@else
    <link rel="stylesheet" href="{{assetPath('backend/css/backend_style.css')}}{{assetVersion()}}"/>
@endif

<!-- uppy css -->
<link rel="stylesheet" href="{{assetPath('vendor/uppy/uppy.min.css')}}">
<link rel="stylesheet" href="{{assetPath('vendor/uppy/media.css')}}">

@stack('styles')




