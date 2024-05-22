<?php

$setting = generalSetting();


if (isset($setting->copyright_text)) {
    $copyright_text = $setting->copyright_text;
} else {
    $copyright_text = 'Copyright © 2019 All rights reserved | This template is made with by Codethemes';
}
if (isset($setting->logo)) {
    $logo = $setting->logo;
} else {
    $logo = 'public/uploads/settings/logo.png';
}

if (isset($setting->favicon)) {
    $favicon = $setting->favicon;
} else {
    $favicon = 'public/backEnd/img/favicon.png';
}
$ttl_rtl = userRtlLtl();
$login_background = App\SmBackgroundSetting::where([['is_default', 1], ['title', 'Login Background']])->first();

if (empty($login_background)) {
    $css = 'background: url(' . url('public/backEnd/img/in_registration.png') . ')  no-repeat center; background-size: cover; ';
} else {
    if (!empty($login_background->image)) {
        $css = "background: url('" . url($login_background->image) . "')  no-repeat center;  background-size: cover;";
    } else {
        $css = 'background:' . $login_background->color;
    }
}

$active_style = App\SmStyle::where('school_id', 1)
    ->where('is_active', 1)
    ->first();
?>
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" @if(isset ($ttl_rtl ) && $ttl_rtl ==1) dir="rtl" class="rtl"
      @endif style="{{ \Session::has('success') ? 'height: 100vh' : '' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset($favicon) }}" type="image/png" />
    <title>@lang('saas::saas.new_institution_registration')</title>
    <x-root-css/>
    <meta name="_token" content="{!! csrf_token() !!}" />
    <link rel="stylesheet" href="{{ asset('public/backEnd/') }}/vendors/css/bootstrap.css" />
    <link rel="stylesheet" href="{{ asset('public/backEnd/') }}/vendors/css/themify-icons.css" />
    <link rel="stylesheet" href="{{ asset('landing/css/toastr.css') }}">
    <link rel="stylesheet" href="{{ asset('public/backEnd/') }}/vendors/css/nice-select.css" />
    <link rel="stylesheet" href="{{ asset('public/backEnd/') }}/vendors/js/select2/select2.css" />
    <link rel="stylesheet" href="{{ asset('public/backEnd/') }}/vendors/css/fastselect.min.css" />
    <link rel="stylesheet" href="{{ url('public/backEnd/') }}/vendors/css/toastr.min.css" />
    <link rel="stylesheet" href="{{ url('public/backEnd/') }}/vendors/css/bootstrap-datepicker.min.css" />
    <link rel="stylesheet" href="{{ url('public/backEnd/') }}/vendors/css/bootstrap-datetimepicker.min.css" />
    <link rel="stylesheet" href="{{ url('public/backEnd/') }}/assets/vendors/vendors_static_style.css" />
    <link rel="stylesheet" href="{{ asset('public/backEnd/assets/css/rtl/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/backEnd/assets/css/loade.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/backEnd/vendors/css/nice-select.css') }}" />
    @if(userRtlLtl() ==1)
        <style>
            html[dir="rtl"] .loader_style_parent_reg {
                padding-left: 25px;
                position: absolute;
                left: 10px;
                top: 5px;
            }

            html[dir="rtl"] .input-right-icon button {
                margin-left: 0;
                left: 0;
                margin-right: auto;
            }

            html[dir="rtl"] .input-right-icon button i {
                left: 22px;
                display: inline-block !important;
            }

            html[dir="rtl"] .input-right-icon button {
                margin-left: 0;
                left: 0;
                margin-right: auto;
                position: absolute;
                left: 0;
            }

            html[dir="rtl"] .mr-20 {
                margin-right: 0px;
                margin-left: 20px;
            }

            html[dir="rtl"] .ml-30 {
                margin-left: 0;
                margin-right: 30px;
            }

            html[dir="rtl"] .primary_input_field:focus ~ label, .primary_input_field.read-only-input ~ label, html[dir="rtl"] .has-content.primary_input_field ~ label {
                text-align: right !important;
            }

            html[dir="rtl"] .primary_input_field ~ label {
                left: auto;
                right: 0 !important;
                text-align: right;
            }
        </style>
        <link rel="stylesheet" href="{{asset('public/backEnd/')}}/css/rtl/style.css"/>
    @else

        <link rel="stylesheet" href="{{url('public/backEnd/')}}/css/style.css"/>
    @endif
    <link rel="stylesheet" href="{{ asset('public/backEnd/saas/') }}/css/{{ activeStyle()->path_main_style }}" />
    <link rel="stylesheet" href="{{ url('Modules/ParentRegistration/Resources/assets/css/style.css') }}">
</head>

<body class="reg_bg" style="{{ @$css }}">
    <style>
        @media (max-width: 991px) {
            .login.admin.hight_100 .login-height .form-wrap {
                padding: 50px 8px;
            }

            .login-area .login-height {
                min-height: auto;
            }
        }

        label.error {
            position: absolute;
            top: 100%;
            text-align: center;
            left: 3%;
            color: red;
        }

        .hide {
            display: none;
        }

        .loader_img_style {
            width: 25px;
            height: 25px;
        }

        .registration_area table th,
        .registration_area table td {
            color: #415094;
            font-size: 14px;
            text-transform: uppercase;
            font-weight: 500;
            margin-bottom: 41px;
            margin-top: 0;
            line-height: 22px;
            letter-spacing: 1px;
        }

        #payment-method-area p {
            color: #415094;
            font-size: 14px;
            text-transform: uppercase;
            font-weight: 500;
            margin-bottom: 0px;
            margin-top: 0;
            line-height: 22px;
            letter-spacing: 1px;
        }

        #payment-method-area label {
            color: #415094;
        }

        .bank-details p,
        .cheque-details p {
            margin: 0 !important;
        }

        .paystack-area p {
            color: #415094;
        }

        .school-table th.price {
            text-align: right !important;
        }

        .single_registration_area .nice-select.niceSelect {
            padding: 0 20px 13px;
            line-height: 31px;
            margin-bottom: 0 !important;
            */
        }

        .single_registration_area span span {
            font-size: 10px;
            font-weight: 600;
            display: block;
            padding-left: 20px;
        }

    </style>

    <!--================ Start Login Area =================-->
    <div class="reg_bg">

    </div>
    <section class="login-area  registration_area ">
        <div class="container">
            <input type="hidden" id="url" value="{{ url('/') }}">
            <div class="registration_area_logo">
                <a href="{{ url('/') }}"><img src="{{ asset($logo) }}" alt="" style="max-width: 150px" ></a>
            </div>

            <div class="row justify-content-center align-items-center">
                <div class="col-lg-12">

                    <div class="text-center white-box single_registration_area">
                        <div class="reg_tittle">
                            <h5>Registration & Available Packages</h5>
                        </div>
                        <?php if (session()->has('message-success') != "") : ?>
                        <?php if (session()->has('message-success')) : ?>
                        <p class="text-success"><?php echo e(session()->get('message-success')); ?></p>
                        <?php endif; ?>
                        <?php endif; ?>
                        <?php if (session()->has('message-danger') != "") : ?>
                        <?php if (session()->has('message-danger')) : ?>
                        <p class="text-danger"><?php echo e(session()->get('message-danger')); ?></p>
                        <?php endif; ?>
                        <?php endif; ?>

                        @if (isSubscriptionEnabled())
                            <form method="POST" class="___class_+?11___"
                                action="{{ route('administrator/institution-register-store') }}"
                                id="subscription-payment" data-cc-on-file="false"
                                data-stripe-publishable-key="{{ @$payment_setting->gateway_publisher_key }}"
                                enctype="multipart/form-data">
                            @else

                                <form method="POST" class="___class_+?12___"
                                    action="{{ route('administrator/institution-register-store') }}"
                                    id="subscription-payment" enctype="multipart/form-data">

                        @endif


                        @csrf
                        <input type="hidden" name="url" id="url" value="{{ url('/') }}">
                        <input type="hidden" name="amount_tax" id="amount_tax" value="">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group input-group">
                                    <input class="form-control{{ $errors->has('school_name') ? ' is-invalid' : '' }}"
                                        type="text" name='school_name' id="school_name"
                                        placeholder="Enter Institution Name *" value="{{ old('school_name') }}"
                                        required />
                                    @if ($errors->has('school_name'))
                                        <span class="invalid-feedback text-left pl-3" role="alert">
                                            <strong>{{ $errors->first('school_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group input-group">
                                    <input
                                        class="form-control{{ $errors->has('school_email') ? ' is-invalid' : '' }}"
                                        type="text" name='school_email' id="school_email" placeholder="Enter email *"
                                        value="{{ old('school_email') }}" required />
                                    @if ($errors->has('school_email'))
                                        <span class="invalid-feedback text-left pl-3" role="alert">
                                            <strong>{{ $errors->first('school_email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group input-group">
                                    <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                        type="password" name='password' id="password" placeholder="Enter Password *"
                                        required />
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback text-left pl-3" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group input-group">
                                    <input
                                        class="form-control{{ $errors->has('confirm_password') ? ' is-invalid' : '' }}"
                                        type="password" name='confirm_password' id="confirm_password"
                                        placeholder="Confirm Password *" required />
                                    @if ($errors->has('confirm_password'))
                                        <span class="invalid-feedback text-left pl-3" role="alert">
                                            <strong>{{ $errors->first('confirm_password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @if (isSubscriptionEnabled())

                                <div class="col-lg-6">
                                    <div class="form-group primary_input">
                                        <select class="primary_select form-control" name="package"
                                            id="package-plan">
                                            <option data-display="Select Plan *" value="">Select Plan *</option>
                                            @foreach ($packages as $package)
                                                <option value="{{ $package->id }}">{{ $package->name }}</option>
                                            @endforeach

                                        </select>
                                        @if ($errors->has('package'))
                                            <span class="text-danger text-left" role="alert">
                                                <span>{{ $errors->first('package') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if (isSubscriptionEnabled())
                                <div class="col-lg-6">
                                    <div class="form-group input-group" style="margin-top: 20px;">
                                        <div class="col-lg-12 d-flex">
                                            <p class="text-uppercase fw-500 mb-10">@lang('accounts.payment_type')</p>
                                            <div class="d-flex radio-btn-flex ml-40 mt-1">
                                                <div class="mr-30">
                                                    <input type="radio" name="payment_type" id="relationTypeT"
                                                           value="trial" class="common-radio payment_type" checked>
                                                    <label for="relationTypeT">@lang('accounts.trial')</label>
                                                </div>
                                                <div class="mr-30">
                                                    <input type="radio" name="payment_type" id="relationTypeP"
                                                           value="paid" class="common-radio payment_type">
                                                    <label for="relationTypeP">@lang('fees.paid')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>


            <div class="row mt-30">
                <div class="col-lg-12">
                    <div class="text-center white-box privacy_police single_registration_area">
                        <div class="single_privacy_police">
                            <h3>Choose Your Custom Web Address (subdomain)</h3>
                        </div>
                        <div class="single_privacy_police">
                            <p>Every school account has its own web address. For example, if you want your school site
                                to be at https://app.{{ config('app.short_url') }} you'd enter app in the field
                                below. Letters & numbers only. </p>
                        </div>

                        <div class="single_privacy_policy">
                            <div class="___class_+?49___">
                                <div class="form-group input-group text-lowercase">
                                    https://
                                    <input
                                        class="form-control{{ $errors->has('domain') ? ' is-invalid' : '' }} p-0 px-3 m-0"
                                        type="text" name='domain' id="domain" placeholder="Choose a domain *"
                                        value="{{ old('domain') }}" required />
                                    .{{ config('app.short_url') }}
                                </div>
                            </div>
                        </div>
                        <span class="text-danger text-left" role="alert" id="domain_error">
                            @if ($errors->has('domain'))
                                {{ $errors->first('domain') }}
                            @endif
                        </span>

                    </div>
                </div>
            </div>

            @if (isSubscriptionEnabled())

                <div class="row mt-30" id="payment-method-area">
                    <div class="col-lg-12">
                        <div class="white-box">
                            <div class="___class_+?56___">
                                <div class="row">
                                    <div class="col-lg-12 d-flex">
                                        <p class="text-uppercase fw-500 mb-10">@lang('accounts.payment_method')</p>
                                        <div class="d-flex radio-btn-flex ml-40">
                                            @if (in_array(1, $array_payment_methods))
                                                <div class="mr-30">
                                                    <input type="radio" name="relationButton" id="relationFather"
                                                        value="cash" class="common-radio relationButton" checked>
                                                    <label for="relationFather">@lang('accounts.cash')</label>
                                                </div>
                                            @endif
                                            @if (in_array(2, $array_payment_methods))

                                                <div class="mr-30">
                                                    <input type="radio" name="relationButton" id="relationMother"
                                                        value="cheque" class="common-radio relationButton">
                                                    <label for="relationMother">@lang('accounts.cheque')</label>
                                                </div>
                                            @endif
                                            @if (in_array(3, $array_payment_methods))

                                                <div class="mr-30">
                                                    <input type="radio" name="relationButton" id="relationOther"
                                                        value="bank" class="common-radio relationButton">
                                                    <label for="relationOther">@lang('accounts.bank')</label>
                                                </div>
                                            @endif
                                            @if (in_array(4, $array_payment_methods))

                                                <div class="mr-30">
                                                    <input type="radio" name="relationButton" id="relationStripe"
                                                        value="stripe" class="common-radio relationButton">
                                                    <label for="relationStripe">@lang('system_settings.stripe')</label>
                                                </div>
                                            @endif
                                            @if (in_array(5, $array_payment_methods))

                                                <div class="mr-30">
                                                    <input type="radio" name="relationButton" id="relationPaystack"
                                                        value="paystack" class="common-radio relationButton">
                                                    <label for="relationPaystack">@lang('system_settings.paystack')</label>
                                                </div>
                                            @endif
                                            @if (in_array(6, $array_payment_methods))
                                                <div class="mr-30">
                                                    <input type="radio" name="relationButton" id="relationPayPal"
                                                        value="paypal" class="common-radio relationButton">
                                                    <label for="relationPayPal">@lang('system_settings.paypal')</label>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- start Cheque slip upload -->
                                <div class="row mt-30" id="cheque-area">
                                    <div class="col-md-5 cheque-details  mt-10">
                                        <strong>{!! $account_detail['cheque'] !!}</strong>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="row">
                                            <div class="col-lg-12 mt-0">
                                                <div class="primary_input">
                                                    <input class="primary_input_field" type="text"
                                                        name="bank_name_cheque" autocomplete="off">
                                                    <label>@lang('accounts.bank_name') <span></span></label>
                                                    <span class="focus-border"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mt-30">
                                                <div class="primary_input">
                                                    <input class="primary_input_field" type="text"
                                                        name="account_holder_cheque" autocomplete="off">
                                                    <label>@lang('accounts.account_holder') <span></span></label>
                                                    <span class="focus-border"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-lg-12 mt-30">
                                                <div class="row no-gutters input-right-icon">
                                                    <div class="col">
                                                        <div class="primary_input sm2_mb_20 md_mb_20">
                                                            <input class="primary_input_field" type="text"
                                                                id="placeholderPhoto"
                                                                placeholder="@lang('coommon.reference_jpeg_png'))"
                                                                readonly="">
                                                            <span class="focus-border"></span>
                                                            @if ($errors->has('file'))
                                                                <span class="invalid-feedback d-block" role="alert">
                                                                    <strong>{{ @$errors->first('file') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <button class="primary-btn-small-input" type="button">
                                                            <label class="primary-btn small fix-gr-bg text-light"
                                                                for="photo">@lang('common.browse')</label>
                                                            <input type="file" class="d-none"
                                                                name="cheque_photo" id="photo">
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end Cheque slip upload -->
                                <!-- start Bank slip upload -->
                                <div class="row mt-30" id="bank-area">
                                    <div class="col-md-5 bank-details mt-10">
                                        <strong>{!! $account_detail['bank'] !!}</strong>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="row">
                                            <div class="col-lg-12 mt-0">
                                                <div class="primary_input">
                                                    <input class="primary_input_field" type="text"
                                                        name="bank_name_bank" autocomplete="off">
                                                    <label>@lang('accounts.bank_name') <span></span></label>
                                                    <span class="focus-border"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mt-30">
                                                <div class="primary_input">
                                                    <input class="primary_input_field" type="text"
                                                        name="account_holder_bank" autocomplete="off">
                                                    <label>@lang('accounts.account_holder') <span></span></label>
                                                    <span class="focus-border"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-lg-12 mt-30">
                                                <div class="row no-gutters input-right-icon">
                                                    <div class="col">
                                                        <div class="primary_input sm2_mb_20 md_mb_20">
                                                            <input class="primary_input_field" type="text"
                                                                id="placeholderMothersName"
                                                                placeholder="@lang('common.reference_jpeg_png')"
                                                                readonly="">
                                                            <span class="focus-border"></span>

                                                            @if ($errors->has('file'))
                                                                <span class="invalid-feedback d-block" role="alert">
                                                                    <strong>{{ @$errors->first('file') }}</strong>
                                                                </span>
                                                            @endif

                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <button class="primary-btn-small-input" type="button">
                                                            <label class="primary-btn small fix-gr-bg text-light"
                                                                for="mothers_photo">@lang('common.browse')</label>
                                                            <input type="file" class="d-none" name="bank_photo"
                                                                id="mothers_photo">
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end bank slip upload -->
                                <!-- start stripe slip upload -->
                                <div class="row mt-30" id="stripe-area">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6 mt-20">
                                                <div class="primary_input">
                                                    <input class="primary_input_field" type="text"
                                                        name="name_on_card" autocomplete="off">
                                                    <label>@lang('accounts.name_on_card') <span>*</span></label>
                                                    <span class="focus-border"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 mt-20">
                                                <div class="primary_input">
                                                    <input class="primary_input_field card-number" type="text"
                                                        name="card-number" autocomplete="off">
                                                    <label>@lang('accounts.card_number') <span>*</span></label>
                                                    <span class="focus-border"></span>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row mt-20">
                                            <div class="col-lg-4 mt-20">
                                                <div class="primary_input">
                                                    <input class="primary_input_field card-cvc" type="text"
                                                        name="card-cvc" autocomplete="off">
                                                    <label>@lang('accounts.cvc') <span>*</span></label>
                                                    <span class="focus-border"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 mt-20">
                                                <div class="primary_input">
                                                    <input class="primary_input_field card-expiry-month"
                                                        type="text" name="card-expiry-month" autocomplete="off">
                                                    <label>@lang('accounts.expiration_month') <span>*</span></label>
                                                    <span class="focus-border"></span>
                                                </div>

                                            </div>
                                            <div class="col-lg-4 mt-20">
                                                <div class="primary_input">
                                                    <input class="primary_input_field card-expiry-year"
                                                        type="text" name="card-expiry-year" autocomplete="off">
                                                    <label>@lang('accounts.expiration_year') <span>*</span></label>
                                                    <span class="focus-border"></span>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row mt-20">
                                            <div class='primary_input'>
                                                <div class='col-md-12 error form-group hide'>
                                                    <div class='alert-danger alert'>Please correct the errors and try
                                                        again.</div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!-- end stripe slip upload -->

                                <!-- start paustack slip upload -->
                                <div class="row" id="paystack-area">
                                    <div class="col-md-12 text-center mt-30">
                                        <p class="___class_+?148___">@lang('accounts.paystack_note')</p>
                                    </div>
                                    <input type="hidden" name="email" value="otemuyiwa@gmail.com"> {{-- required --}}
                                    <input type="hidden" name="orderID" value="345">

                                    <input type="hidden" name="amount" id="paystack_amount" value="">
                                    {{-- required in kobo --}}
                                    <input type="hidden" name="quantity" value="1">
                                    <input type="hidden" name="currency" value="ZAR">
                                    <input type="hidden" name="metadata"
                                        value="{{ json_encode($array = ['key_name' => 'value']) }}">
                                    <input type="hidden" name="reference" value="{{ Paystack::genTranxRef() }}">
                                    <!-- end paustack slip upload -->
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="for-package-details">

                </div>

            @endif


            <div class="row mt-30">
                <div class="col-lg-12 ">
                    <div class="text-center white-box privacy_police single_registration_area">

                        <div class="single_privacy_police">
                            <p> By clicking the checkout now button you agree to our <a
                                    href="{{ url('institution-terms-service') }}" target="_blank"><strong>Terms of
                                        Service</strong></a> and Cancellation Policy and acknowledge receipt of the <a
                                    href="{{ url('institution-privacy-policy') }}" target="_blank"><strong>Privacy
                                        Policy</strong></a> </p>
                        </div>
                        @if ($errors->has('privacy_policy'))
                            <span class="text-danger text-left" role="alert">
                                <span>{{ $errors->first('privacy_policy') }}</span>
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="login_button text-center">
                        <button type="submit" class="primary-btn fix-gr-bg">
                            Checkout Now!
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </section>
    <!--================ Start End Login Area =================-->

    <!--================ Footer Area =================-->
    <footer class="footer_area registration_footer">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 text-center">
                    <p>{!! $copyright_text !!} </p>
                </div>
            </div>
        </div>
    </footer>
    <!--================ End Footer Area =================-->


    <script src="{{ asset('public/backEnd/') }}/vendors/js/jquery-3.2.1.min.js"></script>
    <script src="{{ asset('public/backEnd/') }}/vendors/js/popper.js"></script>
    <script src="{{ asset('public/backEnd/') }}/vendors/js/bootstrap.min.js"></script>
    <script src="{{ asset('public/backEnd/') }}/vendors/js/nice-select.min.js"></script>
    <script src="{{ asset('public/backEnd/saas/') }}/js/login.js"></script>
    <script src="{{ asset('public/backEnd/saas/') }}/js/validate.js"></script>
    <script src="{{ asset('public/backEnd/saas/') }}/js/additional.js"></script>
    {{-- <script src="{{asset('public/backEnd/saas/')}}/js/main.js"></script> --}}
    <script src="{{ asset('public/backEnd/saas/') }}/js/custom.js"></script>
    <script src="{{ url('/') }}/public/backEnd/js/main.js"></script>
    <script src="{{ url('/public/js/registration_custom.js') }}"></script>
    {{-- <script src="{{asset('public/backEnd/')}}/js/main.js"></script> --}}
    <script>
        $('.primary-btn').on('click', function(e) {
            // Remove any old one
            $('.ripple').remove();

            // Setup
            var primaryBtnPosX = $(this).offset().left,
                primaryBtnPosY = $(this).offset().top,
                primaryBtnWidth = $(this).width(),
                primaryBtnHeight = $(this).height();

            // Add the element
            $(this).prepend("<span class='ripple'></span>");

            // Make it round!
            if (primaryBtnWidth >= primaryBtnHeight) {
                primaryBtnHeight = primaryBtnWidth;
            } else {
                primaryBtnWidth = primaryBtnHeight;
            }

            // Get the center of the element
            var x = e.pageX - primaryBtnPosX - primaryBtnWidth / 2;
            var y = e.pageY - primaryBtnPosY - primaryBtnHeight / 2;

            // Add the ripples CSS and start the animation
            $('.ripple')
                .css({
                    width: primaryBtnWidth,
                    height: primaryBtnHeight,
                    top: y + 'px',
                    left: x + 'px'
                })
                .addClass('rippleEffect');
        });
    </script>
    <script type="text/javascript" src="{{ asset('public/backEnd/') }}/vendors/js/toastr.min.js"></script>
    {!! Toastr::message() !!}

    <script>
        jQuery.validator.setDefaults({
            debug: true,
            success: "valid"
        });
        $("#login").validate({

            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6,
                },
                cpassword: {
                    required: true,
                    minlength: 6,
                },
                school_name: {
                    required: true,
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
        if ($('.niceSelect').length) {
            $('.niceSelect').niceSelect();
        }

        //dropdown visiable js
        $(".single_additional_services").on('click', function() {
            $(this).find(".single_additional_text").toggleClass("active_pack");

        });

        //dropdown visiable js
        function totalIt() {
            var input = document.getElementsByName("additional_service");
            var total = 0;
            for (var i = 0; i < input.length; i++) {
                if (input[i].checked) {
                    total = total + parseFloat(input[i].value);
                }
            }
            document.getElementsByName("total")[0].value = "$" + total.toFixed(2);
        }

        $(document).on('keyup', '#domain', function(e) {
            
            let domain = $(this).val();
            if (e.keyCode === '13') {
                e.preventDefault();
            } else {
                regex = /^[a-zA-Z0-9]+$/
                if (domain.length > 1) {
                    if (regex.exec(domain)) {
                        $('#domain_error').html(
                            '<img src="{{ asset('public/backEnd/img/demo_wait.gif') }}" class="loader_img_style"/>')
                        $.ajax({
                            url: "{{ route('saas.domain.validate') }}",
                            data: {
                                domain: domain
                            },
                            method: 'post',
                            dataType: 'json',
                            success: function(response) {
                                if (response) {
                                    $('#domain').removeClass('is-invalid');
                                    $('#domain_error').removeClass('text-danger').addClass(
                                        'text-success').html('Available');
                                }
                            },
                            error: function(error) {
                                $('#domain').addClass('is-invalid');
                                $('#domain_error').removeClass('text-success').addClass('text-danger')
                                    .html(error.responseJSON.errors.domain[0]);
                            }
                        })

                    } else {
                        $('#domain').removeClass('is-invalid');
                        $('#domain_error').removeClass('text-success').addClass('text-danger').html('invalid');
                    }
                } else {
                    $('#domain').removeClass('is-invalid');
                    $('#domain_error').removeClass('text-success').addClass('text-danger').html('');
                }
            }


        });
    </script>

    <script type="text/javascript" src="{{ asset('public/backEnd/') }}/vendors/js/toastr.min.js"></script>

    {!! Toastr::message() !!}

    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>


    @if (isSubscriptionEnabled())

        <script type="text/javascript">
            var fileInput = document.getElementById("photo");
            if (fileInput) {
                fileInput.addEventListener("change", showFileName);

                function showFileName(event) {
                    var fileInput = event.srcElement;
                    var fileName = fileInput.files[0].name;
                    document.getElementById("placeholderPhoto").placeholder = fileName;
                }
            }
            var fileInput = document.getElementById("mothers_photo");
            if (fileInput) {
                fileInput.addEventListener("change", showFileName);

                function showFileName(event) {
                    var fileInput = event.srcElement;
                    var fileName = fileInput.files[0].name;
                    document.getElementById("placeholderMothersName").placeholder = fileName;
                }
            }


            $(document).ready(function() {
                $('#cheque-area').hide();
                $('#stripe-area').hide();
                $('#bank-area').hide();
                $('#paystack-area').hide();
                $('#payment-method-area').hide();

                $(".payment_type").on("click", function() {

                    if ($(this).val() == 'trial') {
                        $('#payment-method-area').hide(1000);
                    } else {
                        $('#payment-method-area').show(1000);
                    }

                });


                $(".relationButton").on("click", function() {

                    if ($(this).val() == 'cash') {

                        $('#cheque-area').hide(1000);
                        $('#stripe-area').hide(1000);
                        $('#bank-area').hide(1000);
                        $('#paystack-area').hide(1000);
                        $('#paypal-area').hide(1000);

                    } else if ($(this).val() == 'cheque') {
                        $('#cheque-area').show(1000);
                        $('#stripe-area').hide(1000);
                        $('#bank-area').hide(1000);
                        $('#paystack-area').hide(1000);
                        $('#paypal-area').hide(1000);

                    } else if ($(this).val() == 'bank') {
                        $('#cheque-area').hide(1000);
                        $('#stripe-area').hide(1000);
                        $('#bank-area').show(1000);
                        $('#paystack-area').hide(1000);
                        $('#paypal-area').hide(1000);

                    } else if ($(this).val() == 'stripe') {
                        $('#cheque-area').hide(1000);
                        $('#stripe-area').show(1000);
                        $('#bank-area').hide(1000);
                        $('#paystack-area').hide(1000);
                        $('#paypal-area').hide(1000);

                    } else if ($(this).val() == 'paystack') {
                        $('#cheque-area').hide(1000);
                        $('#stripe-area').hide(1000);
                        $('#bank-area').hide(1000);
                        $('#paystack-area').show(1000);
                        $('#paypal-area').hide(1000);

                    } else if ($(this).val() == 'paypal') {

                        $('#cheque-area').hide(1000);
                        $('#stripe-area').hide(1000);
                        $('#bank-area').hide(1000);
                        $('#paystack-area').hide(1000);
                        $('#paypal-area').show(1000);

                    }

                });
            });
        </script>


        <script type="text/javascript">
            $(function() {

                var $form = $("form#subscription-payment");

                $('form#subscription-payment').on('submit', function(e) {

                    if ($("input:radio[name=relationButton]:checked").val() == 'stripe') {

                        if (!$form.data('cc-on-file')) {

                            e.preventDefault();

                            Stripe.setPublishableKey($form.data('stripe-publishable-key'));

                            Stripe.createToken({
                                number: $('.card-number').val(),
                                cvc: $('.card-cvc').val(),
                                exp_month: $('.card-expiry-month').val(),
                                exp_year: $('.card-expiry-year').val()
                            }, stripeResponseHandler);

                        }
                    }
                });

                function stripeResponseHandler(status, response) {

                    if (response.error) {
                        $('.error')
                            .removeClass('hide')
                            .find('.alert')
                            .text(response.error.message);
                    } else {
                        // token contains id, last4, and card type
                        var token = response['id'];
                        // insert the token into the form so it gets submitted to the server
                        $form.find('input[type=text]').empty();

                        $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                        $form.get(0).submit();
                    }
                }

            });


            $(document).ready(function() {
                $("#package-plan").on("change", function() {
                    var url = $("#url").val();

                    if ($(this).val() == "") {
                        $('.for-package-details').empty();
                        return false;
                    } else {
                        $('.for-package-details').empty();
                    }

                    var formData = {
                        id: $(this).val(),
                    };
                    // get section for student
                    $.ajax({
                        type: "GET",
                        data: formData,
                        dataType: "json",
                        url: url + "/" + "subscription/get-package-info",
                        success: function(data) {
                            console.log(data);

                            var package_row = '';

                            package_row += "<div class='row mt-30'>";
                            package_row += "<div class='col-lg-12'>";
                            package_row += "<div class='white-box'>";


                            package_row +=
                                "<table id='table_id' class='display school-table' cellspacing='0' width='100%'>";
                            package_row += "<tr>";
                            package_row += "<th>Package Name </th>";
                            package_row += "<th>Trial</th>";
                            package_row += "<th>Duration</th>";
                            package_row += "<th class='price' align='right'>Price (" + data[4] +
                                ")</th>";
                            package_row += "</tr>";
                            package_row += "<tr>";
                            package_row += "<th colspan='4'><hr></th>";
                            package_row += "</tr>";
                            package_row += "<tr>";
                            package_row += "<td>" + data[0]['name'] + " </td>";
                            package_row += "<td>" + data[0]['trial_days'] + " days</td>";
                            package_row += "<td>" + data[0]['duration_days'] + " days</td>";
                            package_row += "<td align='right'>" + data[5] + "</td>";
                            package_row += "</tr>";
                            package_row += "<tr>";
                            package_row += "<th colspan='4'><hr></th>";
                            package_row += "</tr>";
                            package_row += " <tr>";
                            package_row += "<td colspan='3' align='right'>  Tax : </td>";
                            package_row += "<td align='right'> " + data[2] + " </td>";
                            package_row += "</tr>";
                            package_row += "<tr>";
                            package_row += "<th colspan='4'><hr></th>";
                            package_row += "</tr>";
                            package_row += "<tr>";
                            package_row += "<td colspan='3' align='right'>";
                            package_row += "<strong> Total :</strong>";
                            package_row += "</td>";
                            package_row += "<td align='right'> " + data[3] + " </td>";
                            package_row += "</tr>";
                            package_row += "</table>";

                            package_row += "</div>";
                            package_row += " </div>";
                            package_row += "</div>";

                            paystack_amount
                            $('#paystack_amount').val(data[3] * 100);
                            $('#amount_tax').val(data[3]);


                            $('.for-package-details').append(package_row);

                        },
                        error: function(data) {
                            console.log('Error:', data);
                        },
                    });
                });
            });
        </script>
    @endif


</body>

</html>
