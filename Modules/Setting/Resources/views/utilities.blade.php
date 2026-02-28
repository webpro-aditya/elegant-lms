@extends('backend.master')
@section('mainContent')
    <style>
        .white-box.single-summery {
            padding: 21px 0px;
        }

        .white-box.single-summery h1 {
            font-size: 20px;
        }
    </style>

    {!! generateBreadcrumb() !!}

    <div class="row justify-content-center">

        <div class="col-lg-12">
            <div class="row row-gap-4">
                <div class="col-md-4 col-lg-3 col-sm-6">
                    <a class="white-box single-summery d-block btn-ajax"
                       href="{{ route('setting.utilities', ['utilities' => 'optimize_clear']) }}">
                        <div class="d-block mt-10 text-center ">
                            <h3><i class="ti-cloud font_30"></i></h3>
                            <h1 class="gradient-color2 total_purchase">{{ __('setting.Clear Cache') }}</h1>
                        </div>
                    </a>
                </div>

                <div class="col-md-4 col-lg-3 col-sm-6">
                    <a class="white-box single-summery d-block btn-ajax"
                       href="{{ route('setting.utilities', ['utilities' => 'clear_log']) }}">
                        <div class="d-block mt-10 text-center ">
                            <h3><i class="ti-receipt font_30"></i></h3>
                            <h1 class="gradient-color2 total_purchase">{{ __('setting.Clear Log') }}</h1>
                        </div>
                    </a>
                </div>

                <div class="col-md-4 col-lg-3 col-sm-6">
                    <a class="white-box single-summery d-block btn-ajax"
                       href="{{ route('setting.utilities', ['utilities' => 'change_debug']) }}">
                        <div class="d-block mt-10 text-center ">
                            <h3><i class="ti-blackboard font_30"></i></h3>
                            <h1 class="gradient-color2 total_purchase"> {{ __((env('APP_DEBUG') ? trans('common.Disable') : trans("common.Enable") ).' '.trans('common.App Debug')) }}</h1>
                        </div>
                    </a>
                </div>


                <div class="col-md-4 col-lg-3 col-sm-6">
                    <a class="white-box single-summery d-block btn-ajax"
                       href="{{ route('setting.utilities', ['utilities' => 'force_https']) }}">
                        <div class="d-block mt-10 text-center ">
                            <h3><i class="ti-lock font_30"></i></h3>
                            <h1 class="gradient-color2 total_purchase"> {{ __((env('FORCE_HTTPS') ? trans('common.Disable') : trans("common.Enable") ).' '.trans('common.Force HTTPS')) }}</h1>
                        </div>
                    </a>
                </div>

                <div class="col-md-4 col-lg-3 col-sm-6">
                    <a class="white-box single-summery d-block btn-ajax" id="import_database_card" href="#"
                       data-bs-toggle="modal" data-bs-target="#ImportDatabaseModal">
                        <div class="d-block mt-10 text-center ">
                            <h3><i class="fas fa-database font_30"></i></h3>
                            <h1 class="gradient-color2 total_purchase"> {{ __('setting.Import Demo Database') }}</h1>
                        </div>
                    </a>
                </div>


                <div class="col-md-4 col-lg-3 col-sm-6">
                    <a class="white-box single-summery d-block" id="reset_database_card" href="#"
                       data-bs-toggle="modal" data-bs-target="#resetModal"
                    >
                        <div class="d-block mt-10 text-center ">
                            <h3><i class="fas fa-database font_30"></i></h3>
                            <h1 class="gradient-color2 total_purchase"> {{ __('setting.Reset Database') }}</h1>
                        </div>
                    </a>
                </div>
                @if(!$hasPassportInstall)
                    <div class="col-md-4 col-lg-3 col-sm-6">
                        <a class="white-box single-summery d-block btn-ajax"
                           href="{{ route('setting.utilities', ['utilities' => 'passport']) }}">
                            <div class="d-block mt-10 text-center ">
                                <h3><i class="ti-rocket font_30"></i></h3>
                                <h1 class="gradient-color2 total_purchase"> {{ __('setting.Passport install') }}</h1>
                            </div>
                        </a>
                    </div>
                @endif
                @if(config('app.demo_mode'))
                    <div class="col-md-4 col-lg-3 col-sm-6">
                        <a class="white-box single-summery d-block"
                           href="{{ route('setting.utilities', ['utilities' => 'reset_demo']) }}">
                            <div class="d-block mt-10 text-center ">
                                <h3><i class="ti-reload font_30"></i></h3>
                                <h1 class="gradient-color2 total_purchase"> {{ __('setting.Reset demo') }}</h1>
                            </div>
                        </a>
                    </div>
                @endif
                <div class="col-lg-12 d-none">
                    <div class="alert alert-warning mt-30 text-center">
                        {{__('This operation may take some time to complete. Please wait until it finishes.')}}
                    </div>
                </div>
            </div>
        </div>
    </div>



    {{-- Reset Modal --}}
    <div class="modal fade admin-query" id="resetModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('setting.Reset Database')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="ti-close"></i></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <strong>{{__('setting.reset_database_note')}}</strong>
                        <h4>@lang('setting.Are you sure to reset database')?</h4>
                    </div>
                    <div class="mt-40 justify-content-between">
                        <form id="activate_form" action="{{route('utilities.resetDatabase')}}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="primary_input mb-25">
                                        <label class="primary_input_label"
                                               for="title">{{__('common.Enter Password')}} <span
                                                class="text-danger">*</span></label>
                                        <input required type="password" id="password"
                                               class="primary_input_field" name="password"
                                               autocomplete="new-password"
                                               value="" placeholder="{{__('common.Enter Password')}} ">

                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="primary_input">
                                    <button type="submit" class="primary-btn fix-gr-bg"
                                            id="save_button_parent">{{ __('setting.Reset Database') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- import database modal --}}
    <div class="modal fade admin-query" id="ImportDatabaseModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('setting.import_demo_database')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="ti-close"></i></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <strong>{{__('setting.import_demo_note')}}</strong>
                        <h4>@lang('setting.are_you_sure_to_import_demo_database')</h4>
                    </div>

                    <div class="mt-40 justify-content-between">
                        <form id="activate_form" action="{{route('utilities.importDemoDatabase')}}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="primary_input mb-25">
                                        <label class="primary_input_label"
                                               for="title">{{__('common.Enter Password')}} <span
                                                class="text-danger">*</span></label>
                                        <input required type="password" id="password"
                                               class="primary_input_field" name="password" autocomplete="new-password"
                                               value="" placeholder="{{__('common.Enter Password')}} ">

                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="primary_input">
                                    <button type="submit" class="primary-btn fix-gr-bg"
                                            id="save_button_parent">{{ __('setting.Import Database') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Handle utility action clicks
            $(document).on('click', '.btn-ajax', function (e) {
                e.preventDefault();
                let url = $(this).attr('href');

                if (!url || url === '#') {
                    return;
                }

                $('.preloader').show();
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            toastr.success(response.message, '{{ __("common.Success") }}', {
                                timeOut: 3000,
                                progressBar: true,
                                closeButton: true
                            });
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        } else {
                            toastr.error(response.message, '{{ __("common.Error") }}', {
                                timeOut: 5000,
                                progressBar: true,
                                closeButton: true
                            });
                        }
                        $('.preloader').hide();

                    },
                    error: function (xhr) {
                        let message = '{{ __("common.Operation Failed") }}';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        toastr.error(message, '{{ __("common.Error") }}', {
                            timeOut: 5000,
                            progressBar: true,
                            closeButton: true
                        });
                        $('.preloader').hide();

                    }
                });
            });

            // Handle Reset Database Form
            $('#resetModal form').on('submit', function (e) {
                e.preventDefault();
                let $form = $(this);
                let url = $form.attr('action');

                $('.preloader').show();


                $.ajax({
                    url: url,
                    type: 'POST',
                    data: $form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            $('#resetModal').modal('hide');
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open').css('padding-right', '');

                            toastr.success(response.message, '{{ __("common.Success") }}', {
                                timeOut: 3000,
                                progressBar: true,
                                closeButton: true
                            });
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        } else {
                            toastr.error(response.message, '{{ __("common.Error") }}', {
                                timeOut: 5000,
                                progressBar: true,
                                closeButton: true
                            });
                        }
                        $('.preloader').hide();


                    },
                    error: function (xhr) {
                        let message = '{{ __("common.Operation Failed") }}';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        toastr.error(message, '{{ __("common.Error") }}', {
                            timeOut: 5000,
                            progressBar: true,
                            closeButton: true
                        });
                        $('.preloader').hide();

                    }
                });
            });

            // Handle Import Demo Database Form
            $('#ImportDatabaseModal form').on('submit', function (e) {
                e.preventDefault();
                let $form = $(this);
                let url = $form.attr('action');

                $('.preloader').removeClass('d-none');


                $.ajax({
                    url: url,
                    type: 'POST',
                    data: $form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            $('#ImportDatabaseModal').modal('hide');
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open').css('padding-right', '');

                            toastr.success(response.message, '{{ __("common.Success") }}', {
                                timeOut: 3000,
                                progressBar: true,
                                closeButton: true
                            });
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        } else {
                            toastr.error(response.message, '{{ __("common.Error") }}', {
                                timeOut: 5000,
                                progressBar: true,
                                closeButton: true
                            });
                        }
                        $('.preloader').addClass('d-none');

                    },
                    error: function (xhr) {
                        let message = '{{ __("common.Operation Failed") }}';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        toastr.error(message, '{{ __("common.Error") }}', {
                            timeOut: 5000,
                            progressBar: true,
                            closeButton: true
                        });
                        $('.preloader').addClass('d-none');
                    }
                });
            });

            // Clean up modal backdrop on modal hidden
            $('#resetModal, #ImportDatabaseModal').on('hidden.bs.modal', function () {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css('padding-right', '');
                $(this).find('form')[0].reset();
            });
        });
    </script>
@endpush




