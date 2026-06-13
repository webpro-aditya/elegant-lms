@extends('backend.master')
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>Floating Icon Setup</h1>
                <div class="bc-pages">
                    <b-breadcrumb>
                        <b-breadcrumb-item href="{{route('dashboard')}}">{{__('dashboard.Dashboard')}}</b-breadcrumb-item>
                        <b-breadcrumb-item href="#">{{__('setting.Settings')}}</b-breadcrumb-item>
                        <b-breadcrumb-item active>Floating Icon Setup</b-breadcrumb-item>
                    </b-breadcrumb>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box">
                        <form action="{{route('setting.floating_icon_setup_update')}}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-4 mt-30">
                                    <div class="input-effect">
                                        <label>{{__('WhatsApp Number')}}</label>
                                        <input class="primary_input_field name{{ $errors->has('floating_whatsapp') ? ' is-invalid' : '' }}"
                                               type="text" name="floating_whatsapp"
                                               value="{{ Settings('floating_whatsapp') }}">
                                        <span class="focus-border"></span>
                                        @if ($errors->has('floating_whatsapp'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('floating_whatsapp') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-4 mt-30">
                                    <div class="input-effect">
                                        <label>{{__('Email Address')}}</label>
                                        <input class="primary_input_field name{{ $errors->has('floating_email') ? ' is-invalid' : '' }}"
                                               type="email" name="floating_email"
                                               value="{{ Settings('floating_email') }}">
                                        <span class="focus-border"></span>
                                        @if ($errors->has('floating_email'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('floating_email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-4 mt-30">
                                    <div class="input-effect">
                                        <label>{{__('Phone Number')}}</label>
                                        <input class="primary_input_field name{{ $errors->has('floating_phone') ? ' is-invalid' : '' }}"
                                               type="text" name="floating_phone"
                                               value="{{ Settings('floating_phone') }}">
                                        <span class="focus-border"></span>
                                        @if ($errors->has('floating_phone'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('floating_phone') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-40">
                                <div class="col-lg-12 text-center">
                                    @if(env('APP_SYNC')==true)
                                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Disabled For Demo ">
                                            <button class="primary-btn small fix-gr-bg" style="pointer-events: none;"
                                                    type="button">{{ __('common.Update') }}</button>
                                        </span>
                                    @else
                                        <button class="primary-btn fix-gr-bg" data-toggle="tooltip" title="">
                                            <span class="ti-check"></span>
                                            {{__('common.Update')}}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
