@extends('backEnd.master')
@section('title')
@lang('system_settings.email_settings')
@endsection
@section('mainContent')
<style type="text/css">
    .smtp_wrapper{
        display: none;
    }
    .smtp_wrapper_block{
        display: block;
    }
</style>

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('system_settings.email_settings') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('system_settings.system_settings')</a>
                <a href="#">@lang('system_settings.email_settings') </a>
            </div>
        </div>
    </div>
</section>


<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30"> @lang('common.select_email_settings')</h3>
                </div>
            </div>
        </div>
        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'method' => 'POST', 'route' => 'school-update-email-settings-data', 'id' => 'email_settings1', 'enctype' => 'multipart/form-data']) }}
        <div class="row">
            <div class="col-lg-12">
                @if(session()->has('message-success'))
                <div class="alert alert-success">
                  {{ session()->get('message-success') }}
              </div>
              @elseif(session()->has('message-danger'))
              <div class="alert alert-danger">
                  {{ session()->get('message-danger') }}
              </div>
              @endif
              <div class="white-box">
                <div class="">
                     <input type="hidden" name="email_settings_url" id="email_settings_url" value="update-email-settings-data">
                     <input type="hidden" name="url" id="url" value="{{URL::to('/')}}"> 
                     <input type="hidden" name="engine_type" id="engine_type" value="0">
                    
                    <div class="row justify-content-center mb-30">
                        <div class="col-lg-6">
                            <div class="primary_input">
                                <input class="primary_input_field{{ $errors->has('from_name') ? ' is-invalid' : '' }}"
                                type="text" name="from_name" id="from_name" autocomplete="off" value="{{isset($editData)? $editData->from_name : ''}}">
                                <label>@lang('system_settings.from_name')<span>*</span> </label>
                                <span class="focus-border"></span>
                                @if ($errors->has('from_name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('from_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="primary_input">
                                <input class="primary_input_field{{ $errors->has('from_email') ? ' is-invalid' : '' }}"
                                type="text" name="from_email" id="from_email" autocomplete="off" value="{{isset($editData)? $editData->from_email : ''}}">
                                <label>@lang('system_settings.from_mail')<span>*</span> </label>
                                <span class="focus-border"></span>
                                 @if ($errors->has('from_email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('from_email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
              
                    <div class="row justify-content-center mb-30">
                        <div class="col-lg-6">
                            <div class="primary_input">
                                <input class="primary_input_field{{ $errors->has('mail_driver') ? ' is-invalid' : '' }}"
                                type="text" name="mail_driver" id="mail_driver" autocomplete="off" value="{{isset($editData)? $editData->mail_driver : ''}}">
                                <label>@lang('system_settings.mail_driver') <span>*</span> </label>
                                <span class="focus-border"></span>
                                <span class="modal_input_validation red_alert"></span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="primary_input">
                                <input class="primary_input_field{{ $errors->has('mail_host') ? ' is-invalid' : '' }}"
                                type="text" name="mail_host" id="mail_host" autocomplete="off" value="{{isset($editData)? $editData->mail_host : ''}}">
                                <label>@lang('system_settings.mail_host') <span>*</span> </label>
                                <span class="focus-border"></span>
                                <span class="modal_input_validation red_alert"></span>
                            </div>
                        </div>
                      </div>

                    <div class="row justify-content-center mb-30">
                        <div class="col-lg-6">
                            <div class="primary_input">
                                <input class="primary_input_field{{ $errors->has('mail_port') ? ' is-invalid' : '' }}"
                                type="text" name="mail_port" id="mail_port" autocomplete="off" value="{{isset($editData)? $editData->mail_port : ''}}">
                                <label>@lang('system_settings.mail_port') <span>*</span> </label>
                                <span class="focus-border"></span>
                                <span class="modal_input_validation red_alert"></span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="primary_input">
                                <input class="primary_input_field{{ $errors->has('mail_username') ? ' is-invalid' : '' }}"
                                type="text" name="mail_username" id="mail_username" autocomplete="off" value="{{isset($editData)? $editData->mail_username : ''}}">
                                <label>@lang('system_settings.mail_username') <span>*</span> </label>
                                <span class="focus-border"></span>
                                <span class="modal_input_validation red_alert"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center mb-30">
                        <div class="col-lg-6">
                            <div class="primary_input">
                                <input class="primary_input_field{{ $errors->has('mail_password') ? ' is-invalid' : '' }}"
                                type="password" name="mail_password" id="mail_password" autocomplete="off" value="{{isset($editData)? $editData->mail_password : ''}}">
                                <label>@lang('system_settings.mail_password') <span>*</span> </label>
                                <span class="focus-border"></span>
                                <span class="modal_input_validation red_alert"></span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="primary_input">
                                <input class="primary_input_field{{ $errors->has('mail_encryption') ? ' is-invalid' : '' }}"
                                type="text" name="mail_encryption" id="mail_encryption" autocomplete="off" value="{{isset($editData)? $editData->mail_encryption : ''}}">
                                <label>@lang('system_settings.mail_encryption') <span>*</span> </label>
                                <span class="focus-border"></span>
                                <span class="modal_input_validation red_alert"></span>
                            </div>
                        </div>
                      </div>
                <div class="row mt-40">
                    <div class="col-lg-12 text-center">
                        <button class="primary-btn fix-gr-bg">
                            <span class="ti-check"></span>
                            @lang('common.update')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{ Form::close() }}
</div>
</section>
@endsection
