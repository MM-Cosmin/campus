@extends('backEnd.master')
@section('title')
    @lang('saas::saas.subscription_settings')
@endsection
@section('mainContent')
    <style type="text/css">
        #selectStaffsDiv,
        .forStudentWrapper {
            display: none;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 55px;
            height: 26px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 24px;
            width: 24px;
            left: 3px;
            bottom: 2px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background: linear-gradient(90deg, #7c32ff 0%, #c738d8 51%, #7c32ff 100%);
        }

        input:focus+.slider {
            box-shadow: 0 0 1px linear-gradient(90deg, #7c32ff 0%, #c738d8 51%, #7c32ff 100%);
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .buttons_div_one {
            /* border: 4px solid #FFFFFF; */
            border-radius: 12px;

            padding-top: 0px;
            padding-right: 5px;
            padding-bottom: 0px;
            margin-bottom: 4px;
            padding-left: 0px;
        }

        .buttons_div {
            border: 4px solid #19A0FB;
            border-radius: 12px
        }
    </style>
    <section class="sms-breadcrumb mb-40 up_breadcrumb white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('saas::saas.settings')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('saas::saas.subscription')</a>
                    <a href="#">@lang('saas::saas.settings')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">

                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'subscription/settings', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}

                    <div class="white-box">
                        <div class="row">
                            <div class="col-lg-12">
                                <h3 class="text-center">@lang('saas::saas.settings')</h3>
                                <hr>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="primary_input">
                                            <label class="text-uppercase">@lang('saas::saas.tax') (%) <span></span></label>
                                            <input
                                                class="primary_input_field{{ @$errors->has('amount') ? ' is-invalid' : '' }}"
                                                type="text" name="amount" autocomplete="off"
                                                value="{{ $setting->amount }}">


                                            @if ($errors->has('amount'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ @$errors->first('amount') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>



                            </div>
                            <div class="col-lg-6 mt-4">
                                <div class="row">
                                    <div class="col-lg-6 text-right">
                                        <p class="text-uppercase fw-500 mb-10">@lang('saas::saas.is_auto_approve') ? </p>
                                    </div>
                                    <div class="col-lg-6">

                                        <div class="radio-btn-flex ml-20">
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <div class="">
                                                        <input type="radio" name="is_auto_approve"
                                                            id="registration_after_mailF" value="1"
                                                            class="common-radio relationButton"
                                                            {{ @$setting->is_auto_approve == 1 ? 'checked' : '' }}>
                                                        <label for="registration_after_mailF">@lang('saas::saas.yes')</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="">
                                                        <input type="radio" name="is_auto_approve"
                                                            id="registration_after_mailM" value="0"
                                                            class="common-radio relationButton"
                                                            {{ @$setting->is_auto_approve == 0 ? 'checked' : '' }}>
                                                        <label for="registration_after_mailM">@lang('saas::saas.no')</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row mt-40">
                            <div class="col-lg-12 text-center">
                                <button class="primary-btn fix-gr-bg submit" id="_submit_btn_admission">
                                    <span class="ti-check"></span>
                                    @lang('common.save')
                                </button>
                            </div>
                        </div>


                    </div>

                </div>
            </div>
            {{ Form::close() }}
        </div>
        </div>
        </div>
    </section>
@endsection
