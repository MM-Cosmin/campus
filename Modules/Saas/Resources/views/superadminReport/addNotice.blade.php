@extends('backEnd.master')
@section('title')
@if(!isset($notice))
@lang('communicate.add_notice')
@else
@lang('communicate.edit_notice')
@endif
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>
                @if(!isset($notice))
                    @lang('communicate.add_notice')
                @else
                    @lang('communicate.edit_notice')
                @endif
            </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('communicate.communicate')</a>
                <a href="#">
                    @if(!isset($notice))
                        @lang('communicate.add_notice')
                    @else
                        @lang('common.edit_notice')
                    @endif
                </a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
     
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@if(!isset($notice))
                @lang('communicate.add_notice')
                @else
                @lang('common.edit_notice')
                @endif</h3>
                </div>
            </div>
            <div class="offset-lg-6 col-lg-2 text-right col-md-6">
                <a href="{{route('administrator/send-notice')}}" class="primary-btn small fix-gr-bg">
                    @lang('communicate.notice_board')
                </a>
            </div>
        </div> 
        @if(!isset($notice))
        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'administrator/save-notice', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
        @else 
        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'administrator/update-notice', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
        @endif
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
                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                    <input type="hidden" name="id" id="id" value="{{ isset($notice)? $notice->id:''}}">

                    <div class="row">
                        <div class="col-lg-7">
                            <div class="primary_input">
                                <label>@lang('common.title') <span>*</span> </label>
                                <input class="primary_input_field{{ $errors->has('notice_title') ? ' is-invalid' : '' }}"
                                type="text" name="notice_title" autocomplete="off" value="{{isset($notice)? $notice->notice_title:''}}">

                             
                               
                                @if ($errors->has('notice_title'))
                                <span class="text-danger" role="alert">
                                    {{ $errors->first('notice_title') }}
                                </span>
                                @endif
                            </div>
                            <div class="primary_input">
                                <label class="textarea-label">@lang('communicate.notice') <span></span> </label>
                                <textarea class="primary_input_field article-ckeditor" cols="0" rows="4" name="notice_message" id="article-ckeditor">{{isset($notice)? $notice->notice_message:''}}</textarea>
                            </div>
                        </div>


                        <div class="col-lg-5">
                         
                        <div class="primary_input">
                            <label>@lang('communicate.notice_date') <span>*</span> </label>
                            <div class="primary_datepicker_input">
                                <div class="no-gutters input-right-icon">
                                    <div class="col">
                                        <div class="">
                                            <input class="primary_input_field  date form-control{{ $errors->has('notice_date') ? ' is-invalid' : '' }}" id="notice_date" type="text" autocomplete="off" 
                                    name="notice_date" value="{{  isset($notice)? date('m/d/Y', strtotime($notice->notice_date)): date('m/d/Y')}}">
                                        </div>
                                    </div>
                                    <button class="btn-date" data-id="#startDate" type="button">
                                        <label class="m-0 p-0" for="notice_date">
                                            <i class="ti-calendar" id="start-date-icon"></i>
                                        </label>
                                    </button>
                                </div>
                            </div>
                            <span class="text-danger">{{ $errors->first('notice_date') }}</span>
                        </div>
                        
                        <div class="primary_input">
                            <label>@lang('communicate.publish_on') <span>*</span> </label>
                                   
                            <div class="primary_datepicker_input">
                                <div class="no-gutters input-right-icon">
                                    <div class="col">
                                        <div class="">
                                            <input class="primary_input_field date form-control{{ $errors->has('publish_on') ? ' is-invalid' : '' }}" id="publish_on" type="text" autocomplete="off" 

                                            name="publish_on" value="{{  isset($notice)? date('m/d/Y', strtotime($notice->publish_on)): date('m/d/Y')}}">
                                        </div>
                                    </div>
                                    <button class="btn-date" data-id="#startDate" type="button">
                                        <label class="m-0 p-0" for="publish_on">
                                            <i class="ti-calendar" id="start-date-icon"></i>
                                        </label>
                                    </button>
                                </div>
                            </div>
                            <span class="text-danger">{{ $errors->first('publish_on') }}</span>
                        </div>
                        <div class="col-lg-12 mt-15">
                            <label>@lang('communicate.message_to')*</label><br>
                                @foreach($institutions as $institution)
                                <div class=""> 
                                    <input type="checkbox" id="institution_{{$institution->id}}" class="common-checkbox form-control{{ $errors->has('institution') ? ' is-invalid' : '' }}" value="{{  $institution->id}}" name="institution[]" {{ isset($institutionArray)? (in_array($institution->id, $institutionArray)? 'checked':''):"" }}>

                                    <label for="institution_{{$institution->id}}">{{$institution->school_name}}</label>
                                            
                                </div>  
                                @endforeach
                                        @if($errors->has('institution'))
                                        <span class="text-danger validate-textarea-checkbox" role="alert">
                                            {{ $errors->first('institution') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-40">
                            <div class="col-lg-12 text-center">
                                <button class="primary-btn fix-gr-bg">
                                    <span class="ti-check"></span>
                                    @if(isset($notice))
                                    @lang('communicate.update_notice')
                                    @else
                                    @lang('communicate.add_notice')
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
@push('script')
<script src="{{ asset('public/backEnd/vendors/editor/ckeditor/ckeditor.js') }}"></script>
<script>
    CKEDITOR.replace( 'notice_message' );
</script>
@endpush
