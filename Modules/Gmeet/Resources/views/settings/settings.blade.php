@extends('gmeet::layouts.master')
@section('title')
    @lang('gmeet::gmeet.gmeet')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 up_breadcrumb white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('common.settings')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('gmeet::gmeet.gmeet')</a>
                    <a href="#">@lang('common.settings')</a>
                </div>
            </div>
        </div>
    </section>


    <section class="admin-visitor-area up_st_admin_visitor" id="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
                <div class="col-12">
                    @if(in_array(auth()->user()->role_id, [1]))
                    <div class="white_box_30px">
                        <div class="main-title">
                            <h3 class="mb-0">
                                @lang('gmeet::gmeet.manage_gmeet_setting')
                            </h3>
                        </div>
                        {!! Form::open([
                            'route' => ['g-meet.settings.update', $settings->id],
                            'method' => 'PUT',
                            'class' => 'bg-white p-4 rounded',
                        ]) !!}
                        <input type="hidden" name="type" value="api_use">
                        <div class="row mt-15">
                            <div class="col-lg-6 d-flex relation-button justify-content-between">
                                <p class="text-uppercase mb-0">{{ __('gmeet::gmeet.Use Google Calender Api') }}</p>
                                <div class="d-flex radio-btn-flex ml-30 mt-1 mt-3">
                                    <div class="mr-20">
                                        <input type="radio" name="use_api" id="apiEnable" value="1"
                                            {{ $settings->use_api == 1 ? 'checked' : '' }}
                                            class="common-radio relationButton apiUse">
                                        <label for="apiEnable">@lang('common.enable')</label>
                                    </div>
                                    <div class="mr-20">
                                        <input type="radio" name="use_api" id="apiDisable" value="0"
                                            {{ $settings->use_api == 0 ? 'checked' : '' }}
                                            class="common-radio relationButton apiUse">
                                        <label for="apiDisable">@lang('common.disable')</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-15">
                            <div class="col-12">
                                <button class="primary-btn small fix-gr-bg"><i
                                        class="ti-check"></i>@lang('common.update')</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    @endif
                    <div class=" {{ gMainSettings()->use_api == 0 ? 'd-none' : '' }} "id="apiDetailDiv">

                        <div class="white_box_30px mt-5">
                            <div class="main-title">
                                <h3 class="mb-0">
                                    @lang('gmeet::gmeet.Manage Google Api')
                                </h3>

                            </div>
                            {!! Form::open([
                                'route' => ['g-meet.settings.update', $settings->id],
                                'method' => 'PUT',
                                'class' => 'bg-white p-4 rounded',
                            ]) !!}
                            <input type="hidden" name="type" value="api">
                            <div class="row mt-15">
                                <div class="col-lg-6">
                                    <div class="primary_input ">
                                        <label class="primary_input_label" for="">@lang('gmeet::gmeet.api_key')<span class="text-danger"> *</span> </label>
                                        <input
                                            class="primary_input_field form-control{{ $errors->has('api_key') ? ' is-invalid' : '' }}"
                                            type="text" name="api_key" id="api_key" value="{{ $settings->api_key }}">
                                        
                                        
                                        @if ($errors->has('api_key'))
                                            <span class="text-danger" >
                                                {{ $errors->first('api_key') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="primary_input ">
                                        <label class="primary_input_label" for="">@lang('gmeet::gmeet.api_secret_key')<span class="text-danger"> *</span></label>
                                        <input
                                            class="primary_input_field form-control{{ $errors->has('api_secret_key') ? ' is-invalid' : '' }}"
                                            type="text" name="api_secret_key" id="api_secret_key"
                                            value="{{ $settings->api_secret }}">
                                       
                                        
                                        @if ($errors->has('api_secret_key'))
                                            <span class="text-danger" >
                                                {{ $errors->first('api_secret_key') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6 mt-15">
                                    <code>
                                        <a target="_blank"
                                            href="https://console.developers.google.com">{{ __('gmeet::gmeet.Click Here to Get Keys') }}</a></code>
                                </div>
                                <div class="col-6 mt-15">
                                    {{ __('gmeet::gmeet.Redirect URL') }}:
                                    <code>                                      
                                        {{ url('/') . '/gmeet/google/oauth' }}
                                    </code>
                                </div>
                            </div>
                            <div class="row mt-15">
                                <div class="col-12">
                                    <button class="primary-btn small fix-gr-bg"><i
                                            class="ti-check"></i>@lang('common.update')</button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        <div class="white_box_30px mt-5">
                            <div class="main-title">
                                <h3 class="mb-0">
                                    @lang('gmeet::gmeet.Reminder Setup')
                                </h3>
                            </div>
                            {!! Form::open([
                                'route' => ['g-meet.settings.update', $settings->id],
                                'method' => 'PUT',
                                'class' => 'bg-white p-4 rounded',
                            ]) !!}
                            <input type="hidden" name="type" value="reminder">

                            <div class="row mt-15">

                                <div class="col-lg-6">
                                    <div class="primary_input ">
                                        <label class="primary_input_label" for="">@lang('gmeet::gmeet.Email Notification Before(Minutes)')<span class="text-danger"> *</span></label>
                                        <input
                                            class="primary_input_field form-control{{ $errors->has('email_notification') ? ' is-invalid' : '' }}"
                                            type="text" name="email_notification" id="email_notification"
                                            value="{{ $settings->email_notification }}">
                                       
                                        
                                        @if ($errors->has('email_notification'))
                                            <span class="text-danger" >
                                                {{ $errors->first('email_notification') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="primary_input ">
                                        <label class="primary_input_label" for="">@lang('gmeet::gmeet.Pop-up Notification Before(Minutes)')<span class="text-danger"> *</span></label>
                                        <input
                                            class="primary_input_field form-control{{ $errors->has('popup_notification') ? ' is-invalid' : '' }}"
                                            type="text" name="popup_notification" id="popup_notification"
                                            value="{{ $settings->popup_notification }}">
                                       
                                        
                                        @if ($errors->has('popup_notification'))
                                            <span class="text-danger" >
                                                {{ $errors->first('popup_notification') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-15">
                                <div class="col-12">
                                    <button class="primary-btn small fix-gr-bg"><i
                                            class="ti-check"></i>@lang('common.update')</button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        @if(in_array(auth()->user()->role_id, [1]))
                            <div class="white_box_30px mt-5">
                                <div class="main-title">
                                    <h3 class="mb-0">
                                        @lang('gmeet::gmeet.permission_settings')
                                    </h3>
                                </div>
                                {!! Form::open([
                                    'route' => ['g-meet.settings.update', $settings->id],
                                    'method' => 'PUT',
                                    'class' => 'bg-white p-4 rounded',
                                ]) !!}
                                <input type="hidden" name="type" value="permission">

                                <div class="row mt-15">
                                    <div class="col-lg-6 d-flex relation-button justify-content-between">
                                        <p class="text-uppercase mb-0">
                                            {{ __('gmeet::gmeet.login Individual Gmail For Create Google Meet') }}</p>
                                        <div class="d-flex radio-btn-flex ml-30 mt-1 mt-3">
                                            <div class="mr-20">
                                                <input type="radio" name="individual_login" id="individualLoginEnable"
                                                    value="1" {{ $settings->individual_login == 1 ? 'checked' : '' }}
                                                    class="common-radio relationButton individual_login">
                                                <label for="individualLoginEnable">@lang('common.enable')</label>
                                            </div>
                                            <div class="mr-20">
                                                <input type="radio" name="individual_login" id="individualLoginDisable"
                                                    value="0" {{ $settings->individual_login == 0 ? 'checked' : '' }}
                                                    class="common-radio relationButton individual_login">
                                                <label for="individualLoginDisable">@lang('common.disable')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-15">
                                    <div class="col-12">
                                        <button class="primary-btn small fix-gr-bg"><i
                                                class="ti-check"></i>@lang('common.update')</button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
        (function ($) {
            "use strict";
            @if (count($errors) > 0)
                @foreach ($errors->all() as $error)
                    toastr.error("{{ $error }}");
                @endforeach
            @endif
        })(jQuery)
    </script>
@endsection
