@extends('backEnd.master')
@section('title')
@if(!isset($school))
                            @lang('common.add_school')
                            @else
                            @lang('common.edit_school')
                            @endif
                           
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
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
                    <a href="#">@lang('system_settings.system_settings')</a>
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

            <div class="row">
                <div class="col-lg-12">
                    @include('backEnd.partials.alertMessage')
                    <div class="white-box">
                        <div class="">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            <div class="row mb-40">
                                <div class="col-lg-4">
                                    <div class="primary_input">
                                        <input
                                            class="primary_input_field{{ $errors->has('school_name') ? ' is-invalid' : '' }}"
                                            type="text" name="school_name" autocomplete="off" value="{{ isset($school)? $school->school_name:'' }}">
                                        <label>@lang('common.school_name') <span>*</span> </label>
                                        <span class="focus-border"></span>
                                        @if ($errors->has('school_name'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('school_name') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>



                                <div class="col-lg-4">
                                    <div class="primary_input">
                                        <input
                                            class="primary_input_field{{ $errors->has('school_code') ? ' is-invalid' : '' }}"
                                            type="text" name="school_code" autocomplete="off" value="{{ isset($school)? $school->school_code:'' }}">
                                        <label>@lang('common.school_code') <span></span></label>
                                        <span class="focus-border"></span>
                                        @if ($errors->has('school_code'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('school_code') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class='col-md-4'>

                                <div class="row no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field date" id="startDate" type="text" name="opening_date"
                                                       value="{{  isset($school)? date('m/d/Y', strtotime($school->starting_date)) : date('m/d/Y')}}">
                                                <label>@lang('common.opening_date') <span></span></label>
                                                <span class="focus-border"></span>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button class="" type="button">
                                                <i class="ti-calendar" id="start-date-icon"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-40">
                                <div class="col-lg-4">
                                    <div class="primary_input">
                                        <input
                                            class="primary_input_field{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                            type="text" name="email" autocomplete="off" value="{{ isset($school)? $school->email:'' }}">
                                        <label>@lang('common.email') <span>*</span> </label>
                                        <span class="focus-border"></span>
                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                               
                                    <div class="col-lg-4">
                                        <div class="primary_input">
                                           <input
                                               class="primary_input_field{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                               type="text" name="phone" autocomplete="off" value="{{ isset($school)? $school->phone:'' }}">
                                           <label>@lang('common.phone') <span>*</span> </label>
                                           <span class="focus-border"></span>
                                           @if ($errors->has('phone'))
                                               <span class="invalid-feedback" role="alert">
                                           <strong>{{ $errors->first('phone') }}</strong>
                                       </span>
                                           @endif
                                       </div>
                                    </div>
                                
                            </div>
                            

                            <div class="row md-20 mt-20">
                                <div class="col-lg-12">
                                    <div class="primary_input">
                                        <textarea class="primary_input_field" cols="0" rows="4" name="address"
                                                  id="details">{{ isset($school)? $school->address:'' }}</textarea>
                                        <label>@lang('common.address') <span></span> </label>
                                        

                                    </div>
                                </div>
                            </div>
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
