@extends('backEnd.master')
@section('title')
@lang('system_settings.general_settings') 
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
                        @lang('common.update')
                   </h3>
                </div>
            </div>
        </div>
       @if (moduleStatusCheck('Saas')== TRUE  && Auth::user()->is_administrator == "yes")
            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'update-general-settings-data', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
       @else
            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'update-school-settings-data', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
       @endif
        
        
        {{-- @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}
      

        @php
            if(moduleStatusCheck('Saas')== TRUE  && Auth::user()->is_administrator != "yes" ){
                $show_status="disabled";
            }else{
                $show_status="";
            }
        @endphp

        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    <div class="">
                        <input type="hidden"  name="url" id="url" value="{{URL::to('/')}}"> 
                        <div class="row mb-40">
                            <div class="col-lg-3">
                                <div class="primary_input">
                                    <input class="primary_input_field{{ $errors->has('school_name') ? ' is-invalid' : '' }}"
                                    type="text" name="school_name" autocomplete="off" value="{{isset($editData)? $editData->school_name : old('school_name')}}">
                                    <label>@lang('common.school_name') <span>*</span></label>
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
                                    <input class="primary_input_field{{ $errors->has('site_title') ? ' is-invalid' : '' }}"
                                    type="text" name="site_title" autocomplete="off" value="{{isset($editData)? $editData->site_title : old('site_title')}}">
                                    <label>@lang('system_settings.site_title') <span>*</span></label>
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
                                    <select class="primary_select form-control{{ $errors->has('session_id') ? ' is-invalid' : '' }}" name="session_id" id="session_id">
                                        <option data-display="@lang('common.select_academic_year') *" value="">@lang('common.select_academic_year')</option>
                                        
                                        @foreach($session_ids as $key=>$value)
                                        <option value="{{$value->id}}"
                                        @if(isset($editData))
                                        @if($editData->session_id == $value->id)
                                        selected
                                        @endif
                                        @endif
                                        >{{$value->year}}</option>
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
                                    <input class="primary_input_field{{ $errors->has('school_code') ? ' is-invalid' : '' }}"
                                    type="text" name="school_code" autocomplete="off" value="{{isset($editData)? $editData->school_code: old('school_code')}}">
                                    <label>@lang('system_settings.school_code') <span>*</span></label>
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
                                    <input oninput="phoneCheck(this)" class="primary_input_field{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                    type="tel" name="phone" autocomplete="off" value="{{isset($editData)? $editData->phone: old('phone')}}">
                                    <label>@lang('common.phone') <span>*</span></label>
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
                                    <input class="primary_input_field{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                    type="email" name="email" autocomplete="off" value="{{isset($editData)? $editData->email: old('email')}}">
                                    <label>@lang('common.email') <span>*</span></label>
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
                                    <select {{$show_status}} class="primary_select form-control{{ $errors->has('language_id') ? ' is-invalid' : '' }}" name="language_id" id="language_id">
                                        <option data-display="@lang('system_settings.language') *" value="">@lang('common.select') <span>*</span></option>
                                        @if(isset($languages))
                                            @foreach($languages as $key=>$value)
                                            <option value="{{$value->id}}"
                                            @if(isset($editData))
                                            @if($system_settings->language_id == $value->id)
                                            selected
                                            @endif
                                            @endif
                                            >
                                                {{$value->language_name}}
                                            </option>
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
                                    <select {{$show_status}} class="primary_select form-control{{ $errors->has('date_format_id') ? ' is-invalid' : '' }}" name="date_format_id" id="date_format_id">
                                        <option data-display="@lang('system_settings.select_date_format') *" value="">@lang('common.select') <span>*</span></option>
                                        @if(isset($dateFormats))
                                        @foreach($dateFormats as $key=>$value)
                                        <option value="{{$value->id}}"
                                        @if(isset($editData))
                                        @if($system_settings->date_format_id == $value->id)
                                        selected
                                        @endif
                                        @endif
                                        >{{$value->normal_view}}</option>
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

                            <div class="col-lg-2">
                                    <div class="primary_input">
                                         <select {{$show_status}} name="time_zone" class="primary_select form-control {{ $errors->has('time_zone') ? ' is-invalid' : '' }}" id="time_zone">
                                            <option data-display="@lang('common.select_time_zone') *" value="">@lang('common.select_time_zone') *</option>

                                            @foreach($time_zones as $time_zone)
                                            <option value="{{$time_zone->id}}" {{$time_zone->id == $system_settings->time_zone_id? 'selected':''}}>{{$time_zone->time_zone}}</option>
                                            @endforeach
      

                                             
                                        </select>

                                        <span class="focus-border"></span>
                                            @if ($errors->has('time_zone'))
                                            <span class="invalid-feedback invalid-select" role="alert">
                                                <strong>{{ $errors->first('time_zone') }}</strong>
                                            </span>
                                            @endif
                                        
                                       
                                     </div>
                                </div>

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
                                <div class="col-lg-2">
                                    <div class="primary_input">
                                         <select {{$show_status}} name="currency" class="primary_select form-control {{ $errors->has('currency') ? ' is-invalid' : '' }}" id="currency">
                                            <option data-display="@lang('system_settings.select_currency')" value="">@lang('system_settings.select_currency')</option>
                                             @foreach($currencies as $currency)
                                                <option value="{{$currency->symbol}}" {{generalSetting()->currency_symbol == $currency->symbol ? 'selected' : ''}}>{{$currency->name}} ({{$currency->code}})</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('currency'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('currency') }}</strong>
                                        </span>
                                        @endif
                                       
                                     </div>
                                </div>


                            


                            <div class="col-lg-2">
                                <div class="primary_input">
                                    <input class="primary_input_field{{ $errors->has('currency_symbol') ? ' is-invalid' : '' }}"
                                    type="text" name="currency_symbol" autocomplete="off" value="{{isset($editData)? $editData->currency_symbol : old('currency_symbol')}}" id="currency_symbol" readonly="">
                                    <label>@lang('system_settings.currency_symbol') <span>*</span></label>
                                    <span class="focus-border"></span>
                                    @if ($errors->has('currency_symbol'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('currency_symbol') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 d-flex relation-button">
                                <p class="text-uppercase mb-0">@lang('system_settings.promossion_without_exam')</p>
                                <div class="d-flex radio-btn-flex ml-30 mt-1">
                                    <div class="mr-20">
                                        <input type="radio" name="promotionSetting" id="relationFather" value="1" class="common-radio relationButton" {{@$editData->promotionSetting == "1"? 'checked': ''}}>
                                        <label for="relationFather">@lang('system_settings.enable')</label>
                                    </div>
                                    <div class="mr-20">
                                        <input type="radio" name="promotionSetting" id="relationMother" value="0" class="common-radio relationButton" {{@$editData->promotionSetting == "0"? 'checked': ''}}>
                                        <label for="relationMother">@lang('common.disable')</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row md-30">
                            <div class="col-lg-12">
                                <div class="primary_input">
                                <textarea class="primary_input_field" cols="0" rows="4" name="address" id="address">{{isset($editData) ? $editData->address : old('address')}}</textarea>
                                    <label>@lang('system_settings.school_address') <span></span> </label>
                                    

                                </div>
                            </div>
                        </div>
                        <div class="row md-30 mt-40 d-none">
                            <div class="col-lg-12">
                                <div class="primary_input">
                                <textarea {{$show_status}} class="primary_input_field" cols="0" rows="4" name="copyright_text" id="copyright_text">{{isset($editData) ? $system_settings->copyright_text : old('copyright_text')}}</textarea>
                                    <label>@lang('system_settings.copyright_text') <span></span> </label>
                                    

                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row mt-40">
                        <div class="col-lg-12 text-center">
                            <button type="submit" class="primary-btn fix-gr-bg submit">
                                <span class="ti-check"></span>
                                @lang('common.update')
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
