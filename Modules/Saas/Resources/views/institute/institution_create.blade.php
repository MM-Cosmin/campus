@extends('backEnd.master')
@section('title') 
@if(!isset($school))
    @lang('common.add_school') @else @lang('common.edit_school') @endif
   
@endsection

@section('mainContent')
    <section class="sms-breadcrumb mb-15 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@if(!isset($school))
                        
                        @lang('common.add_school') 

                        @else

                        @lang('common.edit_school') 
                        
                        @endif
                       </h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">

                        @if(!isset($school))
                        
                        @lang('common.add_school') 

                        @else

                        @lang('common.edit_school') 
                        
                        
                        @endif
                       </a>
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
                            @if(!isset($school))
                            @lang('common.add_school')
                            @else
                            @lang('common.edit_school')
                            @endif
                            </h3>
                    </div>
                </div>
            </div>
            @if(!isset($school))
                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'administrator/institution-store',
                'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
            @else

            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'administrator/institution-update',
                'method' => 'POST', 'enctype' => 'multipart/form-data']) }}

                <input type="hidden" name="id" value="{{ $school->id }}">

            @endif

            <div class="row ">
                <div class="col-lg-12">
                    @include('backEnd.partials.alertMessage')
                    <div class="white-box">
                        <div class="">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            <div class="row mb-15">
                                <div class="col-lg-3">
                                    <div class="primary_input">
                                        <label>@lang('common.school_name') <span>*</span> </label>
                                        <input
                                            class="primary_input_field form-control{{ $errors->has('school_name') ? ' is-invalid' : '' }}"
                                            type="text" name="school_name" autocomplete="off" value="{{ isset($school)? $school->school_name:'' }}">
                                        
                                      
                                        @if ($errors->has('school_name'))
                                            <span class="text-danger" role="alert">
                                                {{ $errors->first('school_name') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="primary_input">
                                        <label>@lang('common.school_code') <span></span></label>
                                        <input
                                            class="primary_input_field form-control{{ $errors->has('school_code') ? ' is-invalid' : '' }}"
                                            type="text" name="school_code" autocomplete="off" value="{{ isset($school)? $school->school_code:'' }}">
                                       
                                      
                                        @if ($errors->has('school_code'))
                                            <span class="text-danger" role="alert">
                                                {{ $errors->first('school_code') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class='col-lg-3'>                               
                                    <div class="primary_input">
                                        <label>@lang('common.opening_date') <span></span></label>
                                        <div class="primary_datepicker_input">
                                            <div class="no-gutters input-right-icon">
                                                <div class="col">
                                                    <div class="">
                                                        <input class="primary_input_field date" id="startDate" type="text" name="opening_date"
                                                        value="{{  isset($school)? date('m/d/Y', strtotime($school->starting_date)) : date('m/d/Y')}}">
                                                    </div>
                                                </div>
                                                <button class="btn-date" data-id="#startDate" type="button">
                                                    <i class="ti-calendar" id="start-date-icon"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <span class="text-danger">{{ $errors->first('date') }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="primary_input">
                                        <label>@lang('common.school_domain') <span>*</span> </label>
                                        <input
                                            class="primary_input_field form-control{{ $errors->has('domain') ? ' is-invalid' : '' }}"
                                            type="text" name="domain" autocomplete="off" value="{{ isset($school)? $school->domain:'' }}">
                                      
                                      
                                        @if ($errors->has('domain'))
                                            <span class="text-danger" role="alert">
                                                {{ $errors->first('domain') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            

                            <div class="row mb-15 mt-20">
                                <div class="col-lg-3">
                                    <div class="primary_input">
                                        <label>@lang('common.email') <span>*</span> </label>
                                        <input
                                            class="primary_input_field form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                            type="text" name="email" autocomplete="off" value="{{ isset($school)? $school->email:'' }}">
                                       
                                      
                                        @if ($errors->has('email'))
                                            <span class="text-danger" role="alert">
                                                {{ $errors->first('email') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="primary_input">
                                        <label>@lang('common.phone') <span>*</span> </label>
                                        <input
                                            class="primary_input_field form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                            type="text" name="phone" autocomplete="off" value="{{ isset($school)? $school->phone:'' }}">
                                        
                                        
                                        @if ($errors->has('phone'))
                                            <span class="text-danger" role="alert">
                                                {{ $errors->first('phone') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="primary_input">
                                        <label>@lang('common.password') <span>*</span> </label>
                                        <input
                                            class="primary_input_field form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                            type="password" name="password" autocomplete="off" value="{{ isset($school)? $school->password:'' }}">
                                        
                                      
                                        @if ($errors->has('password'))
                                            <span class="text-danger" role="alert">
                                                {{ $errors->first('password') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="primary_input">
                                        <label>@lang('auth.confirm_password') <span>*</span> </label>
                                        <input
                                            class="primary_input_field form-control{{ $errors->has('confirm_password') ? ' is-invalid' : '' }}"
                                            type="password" name="confirm_password" autocomplete="off" value="{{ isset($school)? $school->confirm_password:'' }}">
                                        
                                      
                                        @if ($errors->has('confirm_password'))
                                            <span class="text-danger" role="alert">
                                                {{ $errors->first('confirm_password') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                            </div>
                            

                            <div class="row md-20 mt-20">
                                <div class="col-lg-12">
                                    <div class="primary_input">
                                        <label>@lang('common.address') <span></span> </label>
                                        <textarea class="primary_input_field form-control" cols="0" rows="4" name="address" id="details">{{ isset($school)? $school->address:'' }}</textarea>
                                    </div>
                                </div>
                            </div>
                            @if(isSubscriptionEnabled())
                            @if(!isset($school))
                            <div class="row md-20 mt-20">
                                <div class="col-lg-6">
                                    
                                    <div class="form-group primary_input">
                                        <select class="primary_select form-control{{ $errors->has('package') ? ' is-invalid' : '' }}" name="package" id="package-plan">
                                            <option data-display="@lang('common.select_package') *" value="">@lang('common.select_package') *</option>
                                            @foreach($packages as $package)
                                            <option value="{{$package->id}}">{{$package->name}}</option>
                                            @endforeach

                                        </select>
                                        @if ($errors->has('package'))
                                            <span class="text-danger" role="alert">
                                                {{ $errors->first('package') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endif
                        </div>
                        <div class="row mt-40">
                            <div class="col-lg-12 text-center">
                                <button class="primary-btn fix-gr-bg submit">
                                    <span class="ti-check"></span>
                                   
                                        @if(!isset($school))
                        
                                        @lang('common.save_school') 

                                        @else

                                        @lang('common.update_school') 
                                        
                                        
                                        @endif
                              

                                  
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
@include('backEnd.partials.date_picker_css_js')
