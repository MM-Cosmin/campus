@extends('backEnd.master')
@section('title')
@lang('system_settings.general_settings_view')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('system_settings.update_general_settings')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="{{route('general-settings')}}">@lang('system_settings.general_settings_view')</a>
              </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-6">
                <div class="main-title">
                    <h3 class="mb-30">
                        @lang('common.add')
                   </h3>
                </div>
            </div>
        </div>
       
        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'administrator/add-general-settings-data', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    <div class="">
                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}"> 
                        <div class="row mb-40">
                            <div class="col-lg-3">
                                <div class="primary_input">
                                    <label>@lang('common.school_name') <span>*</span></label>
                                    <input class="primary_input_field{{ $errors->has('school_name') ? ' is-invalid' : '' }}"
                                    type="text" name="school_name" autocomplete="off" value="{{ old('school_name') }}">
                                    <span class="focus-border"></span>
                                    @if ($errors->has('school_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('school_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="primary_input">
                                    <label>@lang('system_settings.site_title') <span>*</span></label>
                                    <input class="primary_input_field{{ $errors->has('site_title') ? ' is-invalid' : '' }}"
                                    type="text" name="site_title" autocomplete="off" value="{{ old('site_title')}}">
                                    <span class="focus-border"></span>
                                    @if ($errors->has('site_title'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('site_title') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="primary_input">
                                    <label>@lang('system_settings.select_session') <span>*</span></label>
                                    <select class="primary_select form-control{{ $errors->has('session_id') ? ' is-invalid' : '' }}" name="session_id" id="session_id">
                                        <option data-display="@lang('system_settings.select_session') *" value="">@lang('common.select')</option>
                                        @foreach(academicYears() as $key=>$value)
                                        <option value="{{$value->id}}">{{$value->year}}</option>
                                        @endforeach
                                    </select>
                                    <span class="focus-border"></span>
                                    @if ($errors->has('session_id'))
                                    <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('session_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="primary_input">
                                    <label>@lang('system_settings.school_code') <span>*</span></label>
                                    <input class="primary_input_field{{ $errors->has('school_code') ? ' is-invalid' : '' }}"
                                    type="text" name="school_code" autocomplete="off" value="{{ old('school_code')}}">
                                    
                                    <span class="focus-border"></span>
                                    @if ($errors->has('school_code'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('school_code') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>


                        </div>

                        <div class="row mb-40">
                            <div class="col-lg-3">
                                <div class="primary_input">
                                    <label>@lang('common.phone') <span>*</span></label>
                                    <input oninput="phoneCheck(this)" class="primary_input_field{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                    type="text" name="phone" autocomplete="off" value="{{  old('phone')}}">
                                    
                                    <span class="focus-border"></span>
                                    @if ($errors->has('phone'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="primary_input">
                                    <label>@lang('common.email') <span>*</span></label>
                                    <input class="primary_input_field{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                    type="text" name="email" autocomplete="off" value="{{ old('email')}}">
                                    
                                    <span class="focus-border"></span>
                                    @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                           <div class="col-lg-3">
                                <div class="primary_input">
                                    <label>@lang('system_settings.language') <span>*</span></label>
                                    <select class="primary_select form-control{{ $errors->has('language_id') ? ' is-invalid' : '' }}" name="language_id" id="language_id">
                                        <option data-display="@lang('system_settings.language') *" value="">@lang('common.select') <span>*</span></option>
                                        @if(isset($languages))
                                        @foreach($languages as $value)
                                        <option value="{{$value->id}}">{{$value->language_name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <span class="focus-border"></span>
                                    @if ($errors->has('language_id'))
                                    <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('language_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="primary_input">
                                    <label>@lang('system_settings.select_date_format') <span>*</span></label>
                                    <select class="primary_select form-control{{ $errors->has('date_format_id') ? ' is-invalid' : '' }}" name="date_format_id" id="date_format_id">
                                        <option data-display="@lang('system_settings.select_date_format') *" value="">@lang('common.select') <span>*</span></option>
                                        @if(isset($dateFormats))
                                        @foreach($dateFormats as $value)
                                        <option value="{{$value->id}}">{{$value->normal_view}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <span class="focus-border"></span>
                                    @if ($errors->has('date_format_id'))
                                    <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('date_format_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <div class="row mb-40">

                            {{-- <div class="col-lg-3">
                                <div class="primary_input">
                                    <input class="primary_input_field{{ $errors->has('currency') ? ' is-invalid' : '' }}"
                                    type="text" name="currency" autocomplete="off" value="{{isset($editData)? $editData->currency : old('currency')}}">
                                    <label>Currency <span>*</span></label>
                                    <span class="focus-border"></span>
                                    @if ($errors->has('currency'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('currency') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div> --}}

                                <div class="col-lg-3">
                                    <div class="primary_input">
                                        <label>@lang('system_settings.select_currency')</label>
                                         <select name="currency" class="primary_select form-control {{ $errors->has('currency') ? ' is-invalid' : '' }}" id="currency">
                                            <option data-display="@lang('system_settings.select_currency')" value="">@lang('system_settings.select_currency')</option>
                                             @foreach($currencies as generalSetting()->currency_symbol)
                                                <option value="{{generalSetting()->currency_symbol->code}}" >{{generalSetting()->currency_symbol->name}} ({{generalSetting()->currency_symbol->code}})</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('currency'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('currency') }}</strong>
                                        </span>
                                        @endif
                                       
                                     </div>
                                </div>


                            


                            <div class="col-lg-3">
                                <div class="primary_input">
                                    <label>@lang('system_settings.currency_symbol') <span>*</span></label>
                                    <input class="primary_input_field{{ $errors->has('currency_symbol') ? ' is-invalid' : '' }}"
                                    type="text" name="currency_symbol" autocomplete="off" value="{{ old('currency_symbol')}}" id="currency_symbol" readonly="">
                                    
                                    <span class="focus-border"></span>
                                    @if ($errors->has('currency_symbol'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('currency_symbol') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                        </div>
                        <div class="row md-30">
                            <div class="col-lg-12">
                                <div class="primary_input">
                                    <label>@lang('system_settings.school_address') <span></span> </label>
                                    <textarea class="primary_input_field" cols="0" rows="4" name="address" id="address">{{ old('address')}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row md-30 mt-40">
                            <div class="col-lg-12">
                                <div class="primary_input">
                                    <label>@lang('system_settings.copyright_text') <span></span> </label>
                                    <textarea class="primary_input_field" cols="0" rows="4" name="copyright_text" id="copyright_text">{{ old('copyright_text')}}</textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row mt-40">
                        <div class="col-lg-12 text-center">
                            <button type="submit" class="primary-btn fix-gr-bg">
                                <span class="ti-check"></span>
                                @lang('common.add')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
    
</div>
</section>
@endsection
