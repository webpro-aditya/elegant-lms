{{-- intl-tel-input CSS (v24 - stable release) --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@24.6.0/build/css/intlTelInput.css">

<form class="form-area contact-form" id="myForm"
      action="{{route('contactMsgSubmit')}}"
      method="post">
    @csrf
    <div class="row">
        <div class="col-lg-12">

            <div class="row">

                <div class="col-md-6">
                    <div class="contact-form-element">
                        <input name="name"
                                onfocus="this.placeholder = ''"
                                class="primary_input" type="text" required
                               value="{{old('name')}}">
                        <label class="primary_label">{{__('frontend.Name')}}</label>
                        <span class="text-danger" role="alert">{{$errors->first('name')}}</span>
                    </div>
                </div>



                <div class="col-md-6">
                    <div class="contact-form-element">
                        <input name="email" required
                                pattern="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{1,63}$"
                               onfocus="this.placeholder = ''"
                                class="primary_input"
                               type="email" value="{{old('email')}}">
                        <label class="primary_label">{{__('frontend.Email Address')}}</label>
                        <span class="text-danger" role="alert">{{$errors->first('email')}}</span>
                    </div>
                </div>

                {{-- Phone Number with ISD Code --}}
                <div class="col-md-6">
                    <div class="contact-form-element contact-phone-wrapper has-content">
                        <input id="contactPhone" name="phone" type="tel"
                               class="primary_input"
                               placeholder="{{__('frontend.Phone Number')}}"
                               onfocus="this.placeholder = ''"
                               value="{{old('phone')}}">
                        {{-- Label removed because intl-tel-input breaks CSS sibling selectors for floating labels --}}
                        <input type="hidden" name="full_phone" id="contactFullPhone">
                        <span class="text-danger" role="alert">{{$errors->first('phone')}}</span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="contact-form-element">
                        <input name="subject" required
                               onfocus="this.placeholder = ''"
                               class="primary_input" type="text"
                               value="{{old('subject')}}">
                        <label class="primary_label">{{__('frontend.Subject')}}</label>
                        <span class="text-danger" role="alert">{{$errors->first('subject')}}</span>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="contact-form-element">
                    <textarea required class="primary_textarea mb-0" name="message"
                              onfocus="this.placeholder = ''"
                    >{{old('message')}}</textarea>
                        <label class="primary_label">{{__('frontend.Message')}}</label>
                        <span class="text-danger" role="alert">{{$errors->first('message')}}</span>
                    </div>
                </div>


            </div>


        </div>

        <div class="col-12 mt_10 mb_20">


            @if(saasEnv('NOCAPTCHA_FOR_CONTACT')=='true')
                <input type="hidden" name="hasCaptcha"
                       value="{{saasEnv('NOCAPTCHA_FOR_CONTACT')}}">
                @if(saasEnv('NOCAPTCHA_IS_INVISIBLE')=="true")
                    {!! NoCaptcha::display(["data-size"=>"invisible"]) !!}
                    {!! NoCaptcha::renderJs() !!}
                @else
                    {!! NoCaptcha::display() !!}
                    {!! NoCaptcha::renderJs() !!}
                @endif

                @if ($errors->has('g-recaptcha-response'))
                    <span class="text-danger"
                          role="alert">{{$errors->first('g-recaptcha-response')}}</span>
                @endif
            @endif


        </div>
        <div class="col-lg-12 text-start">

            <div class="alert-msg"></div>


            @if(saasEnv('NOCAPTCHA_FOR_CONTACT')=='true' && saasEnv('NOCAPTCHA_IS_INVISIBLE')=="true")
                <button type="button"
                        class="g-recaptcha theme_btn small_btn submit-btn w-100 text-center"
                        data-sitekey="{{saasEnv('NOCAPTCHA_SITEKEY')}}"
                        data-size="invisible"
                        data-callback="onSubmit"
                >
                    {{__('frontend.Send Message')}}
                </button>

                <script src="https://www.google.com/recaptcha/api.js"
                        async
                        defer></script>
                <script>
                    function onSubmit(token) {
                        document.getElementById("myForm").submit();
                    }
                </script>
            @else

                <button type="submit"
                        class="theme_btn small_btn submit-btn w-100 text-center">
                    {{__('frontend.Send Message')}}
                </button>
            @endif

        </div>
    </div>
</form>

{{-- intl-tel-input JS (v24 - stable release) --}}
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@24.6.0/build/js/intlTelInput.min.js"></script>

<style>
    /* intl-tel-input seamless integration */
    .contact-phone-wrapper .iti {
        width: 100%;
        display: block;
    }
    .contact-phone-wrapper .iti .primary_input {
        width: 100% !important;
        padding-left: 96px !important;
    }
    .contact-phone-wrapper .iti .primary_input::placeholder {
        color: #8e8e8e;
        font-weight: 400;
    }
    .contact-phone-wrapper .iti__country-container {
        z-index: 10;
    }
    .contact-phone-wrapper .iti__country-list {
        z-index: 999;
        max-height: 200px;
        border-radius: 4px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.12);
    }
    .contact-phone-wrapper .iti__selected-country-primary {
        padding-left: 12px;
    }
    .contact-phone-wrapper .iti__selected-dial-code {
        font-size: 14px;
        color: #415094;
        font-weight: 500;
    }
</style>

<script>
    $(document).ready(function () {
        // Initialize intl-tel-input on the phone field
        var phoneInput = document.querySelector("#contactPhone");
        if (phoneInput && typeof window.intlTelInput === 'function') {
            var iti = window.intlTelInput(phoneInput, {
                initialCountry: "in",
                preferredCountries: ["in", "us", "gb", "ae", "sa", "om"],
                separateDialCode: true,
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@24.6.0/build/js/utils.js",
            });

            // Before form submit, set the full_phone hidden field with ISD + phone number
            var form = document.getElementById("myForm");
            if (form) {
                form.addEventListener("submit", function () {
                    var fullPhone = iti.getNumber();
                    document.getElementById("contactFullPhone").value = fullPhone;
                });
            }
        }

        // Existing label animation logic
        $(".contact-form input, .contact-form textarea").on("change", function () {
            let value = $(this).val();

            if (value.length > 0) {
                $(this).closest(".contact-form-element").addClass("has-content");
            } else {
                $(this).closest(".contact-form-element").removeClass("has-content");
            }
        });
    });
</script>
