@extends('backEnd.master')
@section('title') 
    @lang('zoom::zoom.manage_zoom_settings')
@endsection
@section('mainContent')

<section class="sms-breadcrumb mb-20 up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('zoom::zoom.manage_zoom_setting')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('common.virtual_class')</a>
                <a href="#">@lang('zoom::zoom.settings')</a>
            </div>
        </div>
    </div>
</section>
@if(@$setting->api_use_for==0 || auth()->user()->role_id ==1)
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">
                    <form action="{{ route('zoom.settings.update') }}" method="POST">
                        @csrf
                        <div class="white-box">
                                <div class="row p-0">
                                    <div class="col-lg-12">
                                        <h3 class="text-center">@lang('zoom::zoom.zoom_setting')</h3>
                                        <hr>


                                        <div class="row mt-15">
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-5 d-flex">
                                                        <p class="text-uppercase fw-500 mb-10">@lang('zoom::zoom.meeting_approval')</p>
                                                    </div>
                                                    <div class="col-lg-7">
                                                        <select class="primary_select form-control {{ @$errors->has('approval_type') ? ' is-invalid' : '' }}" name="approval_type">
                                                            <option data-display="@lang('common.select') *" value="">@lang('common.select') *</option>
                                                            <option value="0" {{ old('approval_type',@$setting->approval_type) == 0? 'selected' : ''}} >@lang('zoom::zoom.automatically') </option>
                                                            <option value="1" {{ old('approval_type',@$setting->approval_type) == 1? 'selected' : ''}} >@lang('zoom::zoom.manually_approve')</option>
                                                            <option value="2" {{ old('approval_type',@$setting->approval_type) == 2? 'selected' : ''}} >@lang('zoom::zoom.no_registration_required')</option>
                                                        </select>
                                                        @if ($errors->has('approval_type'))
                                                            <span class="text-danger invalid-select" role="alert">
                                                                <strong>{{ @$errors->first('approval_type') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-5 d-flex">
                                                        <p class="text-uppercase fw-500 mb-10">@lang('zoom::zoom.host_video') </p>
                                                    </div>
                                                    <div class="col-lg-7">
                                                            <div class="radio-btn-flex ml-20">
                                                                <div class="row">
                                                                <div class="col-lg-6">
                                                                    <div class="">
                                                                        <input type="radio" name="host_video" id="host_video_on" value="1" class="common-radio relationButton" {{ old('host_video',@$setting->host_video) == 1 ? 'checked': ''}}>
                                                                        <label for="host_video_on">@lang('common.enable')</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="">
                                                                        <input type="radio" name="host_video" id="host_video" value="0" class="common-radio relationButton" {{ old('host_video',@$setting->host_video) == '0' ? 'checked': ''}}>
                                                                        <label for="host_video">@lang('common.disable')</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row mt-15">

                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-5 d-flex">
                                                        <p class="text-uppercase fw-500 mb-10"> @lang('zoom::zoom.auto_recording_for_paid_package')</p>
                                                    </div>
                                                    <div class="col-lg-7">
                                                        <select class="primary_select form-control {{ @$errors->has('auto_recording') ? ' is-invalid' : '' }}" name="auto_recording">
                                                            <option data-display="@lang('common.select') *" value="">@lang('common.select') *</option>
                                                            <option value="none" {{ old('auto_recording',@$setting->auto_recording) == 'none'? 'selected' : ''}} >@lang('zoom::zoom.none')</option>
                                                            <option value="local" {{ old('auto_recording',@$setting->auto_recording) == 'local'? 'selected' : ''}} >@lang('zoom::zoom.local')</option>
                                                            <option value="cloud" {{ old('auto_recording',@$setting->auto_recording) == 'cloud'? 'selected' : ''}} >@lang('zoom::zoom.cloud')</option>
                                                        </select>
                                                        @if ($errors->has('auto_recording'))
                                                        <span class="text-danger invalid-select" role="alert">
                                                            <strong>{{ @$errors->first('auto_recording') }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-5 d-flex">
                                                        <p class="text-uppercase fw-500 mb-10">@lang('zoom::zoom.participant_video') </p>
                                                    </div>
                                                    <div class="col-lg-7">
                                                            <div class="radio-btn-flex ml-20">
                                                                <div class="row">
                                                                <div class="col-lg-6">
                                                                    <div class="">
                                                                        <input type="radio" name="participant_video" id="participant_video_on" value="1" class="common-radio relationButton" {{ old('participant_video',@$setting->participant_video) == 1? 'checked': ''}}>
                                                                        <label for="participant_video_on">@lang('common.enable')</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="">
                                                                        <input type="radio" name="participant_video" id="participant_video" value="0" class="common-radio relationButton" {{ old('participant_video',@$setting->participant_video) == 0? 'checked': ''}}>
                                                                        <label for="participant_video">@lang('common.disable')</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row mt-15">

                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-5 d-flex">
                                                        <p class="text-uppercase fw-500 mb-10">@lang('zoom::zoom.audio_options')</p>
                                                    </div>
                                                    <div class="col-lg-7">
                                                        <select class="primary_select form-control {{ @$errors->has('audio') ? ' is-invalid' : '' }}" name="audio">
                                                            <option data-display="@lang('common.select') *" value="">@lang('common.select') *</option>
                                                            <option value="both" {{ old('audio',@$setting->audio) == 'both' ? 'selected' : ''}} >@lang('zoom::zoom.both')</option>
                                                            <option value="telephony"  {{ old('audio',@$setting->audio) == 'telephony'? 'selected' : ''}}>@lang('zoom::zoom.telephony')</option>
                                                            <option value="voip"  {{ old('audio',@$setting->audio) == 'voip'? 'selected' : ''}} >@lang('zoom::zoom.voip')</option>

                                                        </select>
                                                        @if ($errors->has('audio'))
                                                        <span class="text-danger invalid-select" role="alert">
                                                            <strong>{{ @$errors->first('audio') }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-5 d-flex">
                                                        <p class="text-uppercase fw-500 mb-10">@lang('zoom::zoom.join_before_host') </p>
                                                    </div>
                                                    <div class="col-lg-7">
                                                            <div class=" radio-btn-flex ml-20">
                                                                <div class="row">
                                                                <div class="col-lg-6">
                                                                    <div class="">
                                                                        <input type="radio" name="join_before_host" id="join_before_host_on" value="1" class="common-radio relationButton"  {{  old('join_before_host',@$setting->join_before_host) == 1? 'checked': '' }}>
                                                                        <label for="join_before_host_on">@lang('common.enable')</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="">
                                                                        <input type="radio" name="join_before_host" id="join_before_host" value="0" class="common-radio relationButton"  {{ old('join_before_host',@$setting->join_before_host) == 0? 'checked': '' }}>
                                                                        <label for="join_before_host">@lang('common.disable')</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-15">
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-5 d-flex">
                                                        <p class="text-uppercase fw-500 mb-10">@lang('zoom::zoom.pakage')</p>
                                                    </div>
                                                    <div class="col-lg-7">
                                                        <select class="primary_select form-control {{ @$errors->has('package_id') ? ' is-invalid' : '' }}" name="package_id">
                                                            <option data-display="@lang('common.select') *" value="">@lang('common.select') *</option>
                                                            <option value="1" {{ old('package_id',@$setting->package_id) == 1 ? 'selected' : ''}} >@lang('zoom::zoom.basic_free')</option>
                                                            <option value="2" {{ old('package_id',@$setting->package_id) == 2 ? 'selected' : ''}} >@lang('zoom::zoom.pro')</option>
                                                            <option value="3" {{ old('package_id',@$setting->package_id) == 3 ? 'selected' : ''}} >@lang('zoom::zoom.business')</option>
                                                            <option value="4" {{ old('package_id',@$setting->package_id) == 4 ? 'selected' : ''}} >@lang('zoom::zoom.enterprise')</option>
                                                        </select>
                                                        @if ($errors->has('package_id'))
                                                        <span class="text-danger invalid-select" role="alert">
                                                            <strong>{{ @$errors->first('package_id') }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-5 d-flex">
                                                        <p class="text-uppercase fw-500 mb-10">@lang('zoom::zoom.waiting_room')</p>
                                                    </div>
                                                    <div class="col-lg-7">
                                                            <div class=" radio-btn-flex ml-20">
                                                                <div class="row">
                                                                <div class="col-lg-6">
                                                                    <div class="">
                                                                        <input type="radio" name="waiting_room" id="waiting_room_on" value="1" class="common-radio relationButton"  {{ old('waiting_room',@$setting->waiting_room) == 1? 'checked': '' }}>
                                                                        <label for="waiting_room_on">@lang('common.enable')</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="">
                                                                        <input type="radio" name="waiting_room" id="waiting_room" value="0" class="common-radio relationButton"  {{ old('waiting_room',@$setting->waiting_room) == 0? 'checked': '' }}>
                                                                        <label for="waiting_room">@lang('common.disable')</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>

                                        <div class="row mt-15">
                                           
                                            <div class="col-lg-6">
                                                <div class="primary_input ">
                                                    <label class="primary_input_label" for="">@lang('zoom::zoom.account_id')<span class="text-danger"> *</span></label>
                                                    <input class="primary_input_field form-control{{ $errors->has('secret_key') ? ' is-invalid' : '' }}" type="text" name="account_id" value="{{ old('account_id',@$setting->account_id) }}">
                                                  
                                                    
                                                    @if ($errors->has('account_id'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                        {{ $errors->first('account_id') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-5 d-flex">
                                                        <p class="text-uppercase fw-500 mb-10"> @lang('zoom::zoom.mute_upon_entry') </p>
                                                    </div>
                                                    <div class="col-lg-7">

                                                            <div class="radio-btn-flex ml-20">
                                                                <div class="row">
                                                                <div class="col-lg-6">
                                                                    <div class="">
                                                                        <input type="radio" name="mute_upon_entry" id="mute_upon_entr_on" value="1" class="common-radio relationButton" {{ old('mute_upon_entry',@$setting->mute_upon_entry) == 1? 'checked': ''}}>
                                                                        <label for="mute_upon_entr_on">@lang('common.enable')</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="">
                                                                        <input type="radio" name="mute_upon_entry" id="mute_upon_entry" value="0" class="common-radio relationButton"  {{ old('mute_upon_entry',@$setting->mute_upon_entry) == 0? 'checked': ''}}>
                                                                        <label for="mute_upon_entry">@lang('common.disable')</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>

                                        <div class="row mt-15">                                            
                                            <div class="col-lg-6">
                                                <div class="primary_input ">
                                                    <label class="primary_input_label" for="">@lang('zoom::zoom.api_key')<span class="text-danger"> *</span> </label>
                                                    <input class="primary_input_field form-control{{ $errors->has('api_key') ? ' is-invalid' : '' }}" type="text" name="api_key" value="{{ old('api_key',@$setting->api_key) }}">
                                                    
                                                    
                                                    @if ($errors->has('api_key'))
                                                    <span class="text-danger" >
                                                        {{ $errors->first('api_key') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-15">
                                            <div class="col-lg-6">
                                                <div class="primary_input ">
                                                    <label class="primary_input_label" for="">@lang('zoom::zoom.serect_key')<span class="text-danger"> *</span></label>
                                                    <input class="primary_input_field form-control{{ $errors->has('secret_key') ? ' is-invalid' : '' }}" type="text" name="secret_key" value="{{ old('secret_key',@$setting->secret_key) }}">
                                                  
                                                    
                                                    @if ($errors->has('secret_key'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                        {{ $errors->first('secret_key') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                {{-- <div class="row">
                                                    <div class="col-lg-5 d-flex">
                                                        
                                                    </div>
                                                    <div class="col-lg-7">
                                                          <p class="slider_zoom">@lang('zoom::zoom.admin_teacher')</p>
                                                            <div class=" radio-btn-flex ml-20">
                                                              
                                                                 <label class="switch_toggle">
                                                                    <input type="checkbox" name="api_use_for"
                                                                            class="weekend_switch_btn" {{@$setting->api_use_for == 0? '':'checked'}}>
                                                                        <span class="slider round" style="background-color: #b336e2"></span>
                                                                    </label>
                                                                <div class="row">
                                                               

                                                            
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                <div class="row">
                                                    <div class="col-lg-5 d-flex">
                                                        <p class="text-uppercase fw-500 mb-10">@lang('zoom::zoom.api_use_for')</p>
                                                    </div>
                                                    <div class="col-lg-7">
                                                            <div class="radio-btn-flex ml-20">
                                                                <div class="row">
                                                                <div class="col-lg-6">
                                                                    <div class="">
                                                                        <input type="radio" name="api_use_for" id="api_use_for_admin" value="0" class="common-radio relationButton" {{ old('api_use_for',@$setting->api_use_for) == 0? 'checked': ''}}>
                                                                        <label for="api_use_for_admin">@lang('zoom::zoom.admin')</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="">
                                                                        <input type="radio" name="api_use_for" id="api_use_for_teacher" value="1" class="common-radio relationButton" {{ old('api_use_for',@$setting->api_use_for) == 1? 'checked': ''}}>
                                                                        <label for="api_use_for_teacher">@lang('common.teacher')</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if(userPermission('zoom.settings'))
                                            <div class="row mt-40">
                                                <div class="col-lg-12 text-center">
                                                <button class="primary-btn fix-gr-bg" id="_submit_btn_admission">
                                                        <span class="ti-check"></span>
                                                        @lang('common.update')
                                                    </button>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@elseif(@$setting->api_use_for==1 && auth()->user()->role_id !=1)  
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <form action="{{ route('zoom.ind.settings.update') }}" method="POST">
                    @csrf
                    <div class="white-box">
                            <div class="row p-0">
                                <div class="col-lg-12">
                                    <h3 class="text-center">@lang('zoom::zoom.zoom_setting')</h3>
                                    <hr>
                                    <div class="row mt-15">
                                        <div class="col-lg-6">
                                            <div class="primary_input ">
                                                <label class="primary_input_label" for="">@lang('zoom::zoom.account_id')<span class="text-danger"> *</span> </label>
                                                <input class="primary_input_field form-control{{ $errors->has('account_id') ? ' is-invalid' : '' }}" type="text" name="account_id" value="{{auth()->user()->zoom_account_id}}">
                                             
                                                
                                                @if ($errors->has('account_id'))
                                                <span class="text-danger" >
                                                    {{ $errors->first('account_id') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                       
                                    </div>
                                    <div class="row mt-15">
                                        <div class="col-lg-6">
                                            <div class="primary_input ">
                                                <label class="primary_input_label" for="">@lang('zoom::zoom.api_key')<span class="text-danger"> *</span> </label>
                                                <input class="primary_input_field form-control{{ $errors->has('api_key') ? ' is-invalid' : '' }}" type="text" name="api_key" value="{{auth()->user()->zoom_api_key_of_user}}">
                                               
                                                
                                                @if ($errors->has('api_key'))
                                                <span class="text-danger" >
                                                    {{ $errors->first('api_key') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="primary_input ">
                                                <label class="primary_input_label" for="">@lang('zoom::zoom.serect_key')<span class="text-danger"> *</span></label>
                                                <input class="primary_input_field form-control{{ $errors->has('secret_key') ? ' is-invalid' : '' }}" type="text" name="secret_key" value="{{auth()->user()->zoom_api_serect_of_user}}">
                                                
                                                @if ($errors->has('secret_key'))
                                                <span class="text-danger invalid-select" role="alert">
                                                    {{ $errors->first('secret_key') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                  

                                 
                                        <div class="row mt-40">
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
@endif
@endsection
