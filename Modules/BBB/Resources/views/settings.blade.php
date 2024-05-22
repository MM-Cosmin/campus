@extends('backEnd.master')
@section('title')
@lang('bbb::bbb.settings')
@endsection
@section('mainContent')

    <section class="sms-breadcrumb mb-20 up_breadcrumb">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('bbb::bbb.settings')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('bbb::bbb.bbb')</a>
                    <a href="#">@lang('bbb::bbb.settings')</a>
                </div>
            </div>
        </div>
    </section>
     <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">
                    <form action="{{ route('bbb.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="white-box">
                            <div class="row p-0">
                                <div class="col-lg-12">
                                    <h3 class="text-center"> @lang('bbb::bbb.bbb_setup')</h3>
                                    <hr>


                                    <div class="row mb-40 ">
                                        <div class="col-lg-6 mt-10">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-300 mb-10">@lang('bbb::bbb.password_length') </p>
                                                </div>
                                                <div class="col-lg-7">                                                   
                                                    <div class="primary_input">
                                                        <input oninput="numberMinMaxCheck(this)" type="text"
                                                                name="password_length" placeholder="@lang('common.password')"
                                                                id="host_video_on"
                                                                value="@if(!empty($setting)){{ old('password_length',$setting->password_length)}}@endif"
                                                                class="primary_input_field">
                                                                

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- welcome message --}}

                                        <div class="col-lg-6 mt-10">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-300 mb-10">@lang('bbb::bbb.allow_start_recording') </p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="radio-btn-flex ml-20">
                                                        <div class="row">
                                                            <div class="radio-btn-flex ml-20">
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="nowrap">
                                                                            <input type="radio"
                                                                                   name="allow_start_stop_recording"
                                                                                   id="allow_start_stop_recording_on"
                                                                                   value="1"
                                                                                   class="common-radio relationButton" @if(!empty($setting)) {{ old('allow_start_stop_recording',$setting->allow_start_stop_recording) == 1? 'checked': ''}}@endif>
                                                                            <label
                                                                                for="allow_start_stop_recording_on">@lang('common.enable')</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="">
                                                                            <input type="radio"
                                                                                   name="allow_start_stop_recording"
                                                                                   id="allow_start_stop_recording"
                                                                                   value="0"
                                                                                   class="common-radio relationButton"@if(!empty($setting)) {{ old('allow_start_stop_recording',$setting->allow_start_stop_recording) == 0? 'checked': ''}} @endif>
                                                                            <label
                                                                                for="allow_start_stop_recording">@lang('common.disable')</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      

                                        <div class="col-lg-6 mt-10">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-300 mb-10">@lang('bbb::bbb.dial_number') </p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="primary_input">
                                                        <input type="text" name="dial_number" 
                                                               value="@if(!empty($setting)){{old('dial_number',$setting->dial_number)}}@endif"
                                                               class="primary_input_field">
                                                               
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- MAX PARTICIPANTS --}}
                                       
                                        <div class="col-lg-6 mt-10">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-300 mb-10">@lang('bbb::bbb.lock_settings_disable_mic') </p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="radio-btn-flex ml-20">
                                                        <div class="row">
                                                            <div class="radio-btn-flex ml-20">
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="nowrap">
                                                                            <input type="radio"
                                                                                   name="lock_settings_disable_mic"
                                                                                   id="lock_settings_disable_mic_on"
                                                                                   value="1"
                                                                                   class="common-radio relationButton" @if(!empty($setting)) {{ old('lock_settings_disable_mic',$setting->lock_settings_disable_mic) == 1? 'checked': ''}}@endif>
                                                                            <label
                                                                                for="lock_settings_disable_mic_on">@lang('common.enable')</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="">
                                                                            <input type="radio"
                                                                                   name="lock_settings_disable_mic"
                                                                                   id="lock_settings_disable_mic"
                                                                                   value="0"
                                                                                   class="common-radio relationButton"@if(!empty($setting)) {{ old('lock_settings_disable_mic',$setting->lock_settings_disable_mic) == 0? 'checked': ''}} @endif>
                                                                            <label
                                                                                for="logo">@lang('common.disable')</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mt-10">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-300 mb-10">@lang('bbb::bbb.logout_url') </p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="primary_input">
                                                        <input type="text" name="logout_url"
                                                               value="@if(!empty($setting)){{ old('logout_url',$setting->logout_url)}}@endif"
                                                               class="primary_input_field form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 mt-10">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-300 mb-10">@lang('bbb::bbb.record') </p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="radio-btn-flex ml-20">
                                                        <div class="row">
                                                            <div class="radio-btn-flex ml-20">
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="nowrap">
                                                                            <input type="radio" name="record"
                                                                                   id="record_on" value="1"
                                                                                   class="common-radio relationButton" @if(!empty($setting)) {{ old('record',$setting->record) == 1? 'checked': ''}}@endif>
                                                                            <label
                                                                                for="record_on">@lang('common.enable')</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="">
                                                                            <input type="radio" name="record"
                                                                                   id="record" value="0"
                                                                                   class="common-radio relationButton"@if(!empty($setting)) {{ old('record',$setting->record) == 0? 'checked': ''}} @endif>
                                                                            <label
                                                                                for="record">@lang('common.disable')</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 mt-10">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-300 mb-10">@lang('common.duration') </p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="primary_input">
                                                        <input oninput="numberMinZeroCheck(this)" type="text" name="duration"
                                                               value="@if(!empty($setting)){{ old('duration',$setting->duration)}}@endif"
                                                               class="primary_input_field form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 mt-10">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-300 mb-10">@lang('bbb::bbb.is_breakout') </p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="radio-btn-flex ml-20">
                                                        <div class="row">
                                                            <div class="radio-btn-flex ml-20">
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="nowrap">
                                                                            <input type="radio" name="is_breakout"
                                                                                   id="is_breakout_on" value="1"
                                                                                   class="common-radio relationButton" @if(!empty($setting)) {{ old('is_breakout',$setting->is_breakout) == 1? 'checked': ''}}@endif>
                                                                            <label
                                                                                for="is_breakout_on">@lang('common.enable')</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="">
                                                                            <input type="radio" name="is_breakout"
                                                                                   id="is_breakout" value="0"
                                                                                   class="common-radio relationButton"@if(!empty($setting)) {{ old('is_breakout',$setting->is_breakout) == 0? 'checked': ''}} @endif>
                                                                            <label
                                                                                for="is_breakout">@lang('common.disable')</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- MODERATOR ONLY MESSAGE --}}
                                        <div class="col-lg-6 mt-10">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-300 mb-10">@lang('bbb::bbb.copyright') </p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="primary_input">
                                                        <input oninput="numberMinMaxCheck(this)" type="text"
                                                               name="copyright"
                                                               value="@if(!empty($setting)){{ old('copyright',$setting->copyright)}}@endif"
                                                               class="primary_input_field form-control">
                                                               
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        {{--  --}}

                                        <div class="col-lg-6 mt-10">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-300 mb-10">@lang('bbb::bbb.auto_start_recording') </p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="radio-btn-flex ml-20">
                                                        <div class="row">
                                                            <div class="radio-btn-flex ml-20">
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="nowrap">
                                                                            <input type="radio"
                                                                                   name="auto_start_recording"
                                                                                   id="auto_start_recording_on" value="1"
                                                                                   class="common-radio relationButton" @if(!empty($setting)) {{ old('auto_start_recording',$setting->auto_start_recording) == 1? 'checked': ''}}@endif>
                                                                            <label
                                                                                for="auto_start_recording_on">@lang('common.enable')</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="">
                                                                            <input type="radio"
                                                                                   name="auto_start_recording"
                                                                                   id="auto_start_recording" value="0"
                                                                                   class="common-radio relationButton"@if(!empty($setting)) {{ old('auto_start_recording',$setting->auto_start_recording) == 0? 'checked': ''}} @endif>
                                                                            <label
                                                                                for="auto_start_recording">@lang('common.disable')</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- ALLOW START STOP RECORDING --}}
                                       
                                        <div class="col-lg-6 mt-10">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-300 mb-10">@lang('bbb::bbb.welcome_message') </p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="primary_input">
                                                        <input type="text" name="welcome_message"
                                                               value="@if(!empty($setting)){{old('welcome_message',$setting->welcome_message)}}@endif"
                                                               class="primary_input_field form-control">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 mt-10">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-300 mb-10">@lang('bbb::bbb.webcams_only_for_moderator') </p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="radio-btn-flex ml-20">
                                                        <div class="row">
                                                            <div class="radio-btn-flex ml-20">
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="nowrap">
                                                                            <input type="radio"
                                                                                   name="webcams_only_for_moderator"
                                                                                   id="webcams_only_for_moderator_on"
                                                                                   value="1"
                                                                                   class="common-radio relationButton" @if(!empty($setting)) {{ old('webcams_only_for_moderator',$setting->webcams_only_for_moderator) == 1? 'checked': ''}}@endif>
                                                                            <label
                                                                                for="webcams_only_for_moderator_on">@lang('common.enable')</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="">
                                                                            <input type="radio"
                                                                                   name="webcams_only_for_moderator"
                                                                                   id="webcams_only_for_moderator"
                                                                                   value="0"
                                                                                   class="common-radio relationButton"@if(!empty($setting)) {{ old('webcams_only_for_moderator',$setting->webcams_only_for_moderator) == 0? 'checked': ''}} @endif>
                                                                            <label
                                                                                for="webcams_only_for_moderator">@lang('common.disable')</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                  {{--      <div class="col-lg-6 mt-10">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-300 mb-10">{{__('Logo')}} </p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="radio-btn-flex ml-20">
                                                        <div class="row">
                                                            <div class="radio-btn-flex ml-20">
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        --}}{{--<button class="primary-btn-small-input"
                                                                                type="button">

                                                                        </button>--}}{{--
                                                                        <label class="primary-btn small fix-gr-bg"
                                                                               for="upload_content_file">{{__('Browse')}}</label>
                                                                        <input type="file"
                                                                               class="d-none form-control"
                                                                               name="logo"
                                                                               id="upload_content_file">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>--}}

                                       {{--  --}}

                                        
                                       <div class="col-lg-6 mt-10">
                                        <div class="row">
                                            <div class="col-lg-5 d-flex">
                                                <p class="text-uppercase fw-300 mb-10">@lang('bbb::bbb.moderator_only_message') </p>
                                            </div>
                                            <div class="col-lg-7">
                                               
                                                <div class="primary_input">
                                                    <input type="text"
                                                           name="moderator_only_message"
                                                           value="@if(!empty($setting)) {{ old('moderator_only_message',$setting->moderator_only_message)}}@endif"
                                                           class="primary_input_field">
                                                           

                                                
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                        <div class="col-lg-6 mt-10">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-300 mb-10">@lang('bbb::bbb.mute_on_start') </p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="radio-btn-flex ml-20">
                                                        <div class="row">
                                                            <div class="radio-btn-flex ml-20">
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="nowrap">
                                                                            <input type="radio" name="mute_on_start"
                                                                                   id="mute_on_start_on" value="1"
                                                                                   class="common-radio relationButton" @if(!empty($setting)) {{ old('mute_on_start',$setting->mute_on_start) == 1? 'checked': ''}}@endif>
                                                                            <label
                                                                                for="mute_on_start_on">@lang('common.enable')</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="">
                                                                            <input type="radio" name="mute_on_start"
                                                                                   id="mute_on_start" value="0"
                                                                                   class="common-radio relationButton"@if(!empty($setting)) {{ old('mute_on_start',$setting->mute_on_start) == 0? 'checked': ''}} @endif>
                                                                            <label for="logo">@lang('common.disable')</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- LOCK SETTINGS DISABLE MIC --}}
                                        

                                       

                                        <div class="col-lg-6 mt-10">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-300 mb-10">@lang('bbb::bbb.lock_settings_disable_private_chat') </p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="radio-btn-flex ml-20">
                                                        <div class="row">
                                                            <div class="radio-btn-flex ml-20">
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="nowrap">
                                                                            <input type="radio"
                                                                                   name="lock_settings_disable_private_chat"
                                                                                   id="lock_settings_disable_private_chat_on"
                                                                                   value="1"
                                                                                   class="common-radio relationButton" @if(!empty($setting)) {{ old('lock_settings_disable_private_chat',$setting->lock_settings_disable_private_chat) == 1? 'checked': ''}}@endif>
                                                                            <label
                                                                                for="lock_settings_disable_private_chat_on">@lang('common.enable')</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="">
                                                                            <input type="radio"
                                                                                   name="lock_settings_disable_private_chat"
                                                                                   id="lock_settings_disable_private_chat"
                                                                                   value="0"
                                                                                   class="common-radio relationButton"@if(!empty($setting)) {{ old('lock_settings_disable_private_chat',$setting->lock_settings_disable_private_chat) == 0? 'checked': ''}} @endif>
                                                                            <label for="logo">@lang('common.disable')</label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 mt-10">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-300 mb-10">@lang('bbb::bbb.lock_settings_disable_public_chat') </p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="radio-btn-flex ml-20">
                                                        <div class="row">
                                                            <div class="radio-btn-flex ml-20">
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="nowrap">
                                                                            <input type="radio"
                                                                                   name="lock_settings_disable_public_chat"
                                                                                   id="lock_settings_disable_public_chat_on"
                                                                                   value="1"
                                                                                   class="common-radio relationButton" @if(!empty($setting)) {{ old('lock_settings_disable_public_chat',$setting->lock_settings_disable_public_chat) == 1? 'checked': ''}}@endif>
                                                                            <label
                                                                                for="lock_settings_disable_public_chat_on">@lang('common.enable')</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="">
                                                                            <input type="radio"
                                                                                   name="lock_settings_disable_public_chat"
                                                                                   id="lock_settings_disable_public_chat"
                                                                                   value="0"
                                                                                   class="common-radio relationButton"@if(!empty($setting)) {{ old('lock_settings_disable_public_chat',$setting->lock_settings_disable_public_chat) == 0? 'checked': ''}} @endif>
                                                                            <label
                                                                                for="lock_settings_disable_public_chat">@lang('common.disable')</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 mt-10">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-300 mb-10">@lang('bbb::bbb.lock_settings_disable_note') </p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="radio-btn-flex ml-20">
                                                        <div class="row">
                                                            <div class="radio-btn-flex ml-20">
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="nowrap">
                                                                            <input type="radio"
                                                                                   name="lock_settings_disable_note"
                                                                                   id="lock_settings_disable_note_on"
                                                                                   value="1"
                                                                                   class="common-radio relationButton" @if(!empty($setting)) {{ old('lock_settings_disable_note',$setting->lock_settings_disable_note) == 1? 'checked': ''}}@endif>
                                                                            <label
                                                                                for="lock_settings_disable_note_on">@lang('common.enable')</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="">
                                                                            <input type="radio"
                                                                                   name="lock_settings_disable_note"
                                                                                   id="lock_settings_disable_note"
                                                                                   value="0"
                                                                                   class="common-radio relationButton"@if(!empty($setting)) {{ old('lock_settings_disable_note',$setting->lock_settings_disable_note) == 0? 'checked': ''}} @endif>
                                                                            <label for="logo">@lang('common.disable')</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 mt-10">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-300 mb-10">@lang('bbb::bbb.lock_settings_locked_layout') </p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="radio-btn-flex ml-20">
                                                        <div class="row">
                                                            <div class="radio-btn-flex ml-20">
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="nowrap">
                                                                            <input type="radio"
                                                                                   name="lock_settings_locked_layout"
                                                                                   id="lock_settings_locked_layout_on"
                                                                                   value="1"
                                                                                   class="common-radio relationButton" @if(!empty($setting)) {{ old('lock_settings_locked_layout',$setting->lock_settings_locked_layout) == 1? 'checked': ''}}@endif>
                                                                            <label
                                                                                for="lock_settings_locked_layout_on">@lang('common.enable')</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="">
                                                                            <input type="radio"
                                                                                   name="lock_settings_locked_layout"
                                                                                   id="lock_settings_locked_layout"
                                                                                   value="0"
                                                                                   class="common-radio relationButton"@if(!empty($setting)) {{ old('lock_settings_locked_layout',$setting->lock_settings_locked_layout) == 0? 'checked': ''}} @endif>
                                                                            <label
                                                                                for="logo">@lang('common.disable')</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 mt-10">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-300 mb-10">@lang('bbb::bbb.lock_settings_lock_on_join') </p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="radio-btn-flex ml-20">
                                                        <div class="row">
                                                            <div class="radio-btn-flex ml-20">
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="nowrap">
                                                                            <input type="radio"
                                                                                   name="lock_settings_lock_on_join"
                                                                                   id="lock_settings_lock_on_join_on"
                                                                                   value="1"
                                                                                   class="common-radio relationButton" @if(!empty($setting)) {{ old('lock_settings_lock_on_join',$setting->lock_settings_lock_on_join) == 1? 'checked': ''}}@endif>
                                                                            <label
                                                                                for="lock_settings_lock_on_join_on">@lang('common.enable')</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="">
                                                                            <input type="radio"
                                                                                   name="lock_settings_lock_on_join"
                                                                                   id="lock_settings_lock_on_join"
                                                                                   value="0"
                                                                                   class="common-radio relationButton"@if(!empty($setting)) {{ old('lock_settings_lock_on_join',$setting->lock_settings_lock_on_join) == 0? 'checked': ''}} @endif>
                                                                            <label
                                                                                for="logo">@lang('common.disable')</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 mt-10">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-300 mb-10">@lang('bbb::bbb.lock_settings_lock_on_join_configurable') </p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="radio-btn-flex ml-20">
                                                        <div class="row">
                                                            <div class="radio-btn-flex ml-20">
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="nowrap">
                                                                            <input type="radio"
                                                                                   name="lock_settings_lock_on_join_configurable"
                                                                                   id="lock_settings_lock_on_join_configurable_on"
                                                                                   value="1"
                                                                                   class="common-radio relationButton" @if(!empty($setting)) {{ old('lock_settings_lock_on_join_configurable',$setting->lock_settings_lock_on_join_configurable) == 1? 'checked': ''}}@endif>
                                                                            <label
                                                                                for="lock_settings_lock_on_join_configurable_on">@lang('common.enable')</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="">
                                                                            <input type="radio"
                                                                                   name="lock_settings_lock_on_join_configurable"
                                                                                   id="lock_settings_lock_on_join_configurable"
                                                                                   value="0"
                                                                                   class="common-radio relationButton"@if(!empty($setting)) {{ old('lock_settings_lock_on_join_configurable',$setting->lock_settings_lock_on_join_configurable) == 0? 'checked': ''}} @endif>
                                                                            <label
                                                                                for="logo">@lang('common.disable')</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 mt-10">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-300 mb-10">@lang('bbb::bbb.guest_policy') </p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="radio-btn-flex ml-20">
                                                        <div class="row">
                                                            <div class="radio-btn-flex ml-20">
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <div class="">
                                                                            <select name="guest_policy"
                                                                                    id="guest_policy"
                                                                                    class="primary_select">
                                                                                <option value="ALWAYS_ACCEPT"
                                                                                        @if(!empty($setting)) @if($setting->guest_policy=="ALWAYS_ACCEPT") selected @endif @endif>
                                                                                        @lang('bbb::bbb.always_accept')
                                                                                </option>
                                                                                <option value="ALWAYS_DENY"
                                                                                        @if(!empty($setting)) @if($setting->guest_policy=="ALWAYS_DENY") selected @endif @endif>
                                                                                        @lang('bbb::bbb.always_deny')
                                                                                </option>
                                                                                <option value="ASK_MODERATOR"
                                                                                        @if(!empty($setting)) @if($setting->guest_policy=="ASK_MODERATOR") selected @endif @endif>
                                                                                        @lang('bbb::bbb.ask_moderator')
                                                                                </option>

                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        {{-- join 5 --}}
                                        <div class="col-lg-6 mt-10">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-300 mb-10">@lang('bbb::bbb.join_via_html5') </p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="radio-btn-flex ml-20">
                                                        <div class="row">
                                                            <div class="radio-btn-flex ml-20">
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="nowrap">
                                                                            <input type="radio" name="join_via_html5"
                                                                                   id="join_via_html5_on" value="1"
                                                                                   class="common-radio relationButton" @if(!empty($setting)) {{ old('join_via_html5',$setting->join_via_html5) == 1? 'checked': ''}}@endif>
                                                                            <label
                                                                                for="join_via_html5_on">@lang('common.enable')</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="">
                                                                            <input type="radio" name="join_via_html5"
                                                                                   id="join_via_html5" value="0"
                                                                                   class="common-radio relationButton"@if(!empty($setting)) {{ old('join_via_html5',$setting->join_via_html5) == 0? 'checked': ''}} @endif>
                                                                            <label
                                                                                for="join_via_html5">@lang('common.disable')</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-lg-6 mt-10">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-300 mb-10">@lang('common.status') </p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="radio-btn-flex ml-20">
                                                        <div class="row">
                                                            <div class="radio-btn-flex ml-20">
                                                                <div class="row">
                                                                    <div class="col-lg-12" >

                                                                        <select name="state" id="state"
                                                                                class="primary_select">
                                                                            <option value="any"
                                                                                    @if(!empty($setting)) @if($setting->state=="any") selected @endif @endif>
                                                                                    @lang('bbb::bbb.any')
                                                                            </option>
                                                                            <option value="published"
                                                                                    @if(!empty($setting)) @if($setting->state=="published") selected @endif @endif>
                                                                                    @lang('bbb::bbb.published')
                                                                            </option>
                                                                            <option value="unpublished"
                                                                                    @if(!empty($setting)) @if($setting->state=="unpublished") selected @endif @endif>
                                                                                    @lang('bbb::bbb.unpublished')
                                                                            </option>
                                                                        </select>


                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mt-10">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-300 mb-10">@lang('bbb::bbb.redirect')</p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="radio-btn-flex ml-20">
                                                        <div class="row">
                                                            <div class="radio-btn-flex ml-20">
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="nowrap">
                                                                            <input type="radio" name="redirect"
                                                                                   id="redirect_on" value="1"
                                                                                   class="common-radio relationButton" @if(!empty($setting)) {{ old('redirect',$setting->redirect) == 1? 'checked': ''}}@endif>
                                                                            <label
                                                                                for="redirect_on">@lang('common.enable')</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="">
                                                                            <input type="radio" name="redirect"
                                                                                   id="redirect" value="0"
                                                                                   class="common-radio relationButton"@if(!empty($setting)) {{ old('redirect',$setting->redirect) == 0? 'checked': ''}} @endif>
                                                                            <label
                                                                                for="logo">@lang('common.disable')</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                       

                                       
                                    </div>

                                    <div class="row mb-40 mt-40">
                                        <div class="col-lg-6">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('bbb::bbb.bbb_security_salt')<span class="text-danger"> *</span></label>
                                                <input
                                                    class="primary_input_field"
                                                    type="text" name="security_salt"
                                                    value="@if(!empty($setting)){{old('secret_key',$setting->security_salt)}}@endif">                                               
                                                
                                                @if ($errors->has('security_salt'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                                {{ $errors->first('security_salt') }}
                                                            </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('bbb::bbb.bbb_server_base_url')<span class="text-danger"> *</span></label>
                                                <input
                                                    class="primary_input_field "
                                                    type="text" name="server_base_url"
                                                    value="{{ isset($setting) ? $setting->server_base_url : '' }}">
                                                
                                                @if ($errors->has('server_base_url'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                                {{ $errors->first('server_base_url') }}
                                                            </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg" id="_submit_btn_admission">
                                                <span class="ti-check"></span>
                                                @lang('common.update')
                                            </button>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
