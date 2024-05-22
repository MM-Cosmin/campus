@if (in_array(857, App\GlobalVariable::GlobarModuleLinks()) || Auth::user()->role_id == 1 || Auth::user()->role_id == 4)
    <div class="col-lg-3">

        @if (isset($editdata))
            <form class="form-horizontal" action="{{ route('bbb.meetings.update', $editdata->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
            @else
                <form class="form-horizontal" action="{{ route('bbb.meetings.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">

                    <div class="main-title">
                        <h3 class="mb-15">
                            @if (isset($editdata))
                                @lang('common.edit_metting')
                            @else
                                @lang('common.add_meeting')
                            @endif
            
                        </h3>
                    </div>
                    <div class="row mb-15">
                        <div class="col-lg-12 ">
                            <label class="primary_input_label" for="">
                                {{ __('common.class') }}
                                <span class="text-danger"> *</span>
                            </label>
                            <select class="primary_select  user_type" name="member_type">
                                <option data-display=" @lang('common.member_type') *" value="">@lang('common.member_type') *</option>
                                @foreach ($roles as $value)
                                    @if (isset($editdata))
                                        <option value="{{ $value->id }}"
                                            {{ $value->id == $user_type ? 'selected' : '' }}>{{ $value->name }}
                                        </option>
                                    @else
                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @if ($errors->has('member_type'))
                                <span class="text-danger">
                                    {{ $errors->first('member_type') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-15">
                        <div class="col-lg-12" id="selectTeacherDiv">
                            <label for="checkbox" class="mb-2">@lang('common.member') <span class="text-danger">
                                    *</span></label>
                            <select multiple id="selectMultiUsers" class="multypol_check_select active position-relative" name="participate_ids[]"
                                style="width:300px">
                                @if (isset($editdata))
                                    @foreach ($userList as $value)
                                        <option value="{{ $value->id }}" selected>{{ $value->full_name }}</option>
                                     
                                    @endforeach
                                @endif
                            </select>
                            @if ($errors->has('participate_ids'))
                                <span class="text-danger" style="display:block">
                                    {{ $errors->first('participate_ids') }}
                                </span>
                            @endif
                        </div>
                    </div>


                    <div class="row mt-15">
                        <div class="col-lg-12">
                            <div class="primary_input">
                                <label class="primary_input_label" for="">@lang('common.topic') <span
                                        class="text-danger"> *</span></label>
                                <input class="primary_input_field" type="text" name="topic" autocomplete="off"
                                    value="{{ isset($editdata) ? old('topic', $editdata->topic) : old('topic') }}">


                                @if ($errors->has('topic'))
                                    <span class="text-danger">
                                        {{ $errors->first('topic') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mt-15">
                        <div class="col-lg-12">
                            <div class="primary_input">
                                <label class="primary_input_label" for="">@lang('common.description')</label>
                                <textarea class="primary_input_field form-control" cols="0" rows="4" name="description" id="address">{{ isset($editdata) ? old('description', $editdata->description) : old('description') }}</textarea>


                                @if ($errors->has('description'))
                                    <span class="text-danger">
                                        {{ $errors->first('description') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>


                    <div class="row mt-15" style="display:none;">
                        <div class="col-lg-12">
                            <div class="primary_input">
                                <label class="primary_input_label" for="">@lang('common.password') <span
                                        class="text-danger"> *</span></label>
                                <input class="primary_input_field" type="text" name="attendee_password"
                                    autocomplete="off"
                                    value="123456"
                                    value="{{ isset($editdata) ? old('topic', $editdata->attendee_password) : old('attendee_password') }}">


                                @if ($errors->has('attendee_password'))
                                    <span class="text-danger">
                                        {{ $errors->first('attendee_password') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mt-15">
                        <div class="col-lg-12">
                            <div class="primary_input">
                                <input class="primary_input_field" type="hidden" name="moderator_password"
                                    autocomplete="off" value="001122">

                                @if ($errors->has('moderator_password'))
                                    <span class="text-danger">
                                        {{ $errors->first('moderator_password') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mt-15">
                        <div class="col-lg-12">
                            <div class="primary_input">
                                <label class="primary_input_label" for="">@lang('common.date_of_meeting')<span
                                        class="text-danger"> *</span></label>
                                <div class="primary_datepicker_input">
                                    <div class="no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="">
                                                <input
                                                    class="primary_input_field  primary_input_field date form-control form-control"
                                                    id="startDate" type="text" name="date" readonly="true"
                                                    value="{{ isset($editdata) ? old('date', Carbon\Carbon::parse($editdata->date_of_meeting)->format('m/d/Y')) : old('date', Carbon\Carbon::now()->format('m/d/Y')) }}"
                                                    required>

                                            </div>
                                        </div>
                                        <button class="btn-date" data-id="#startDate" type="button">
                                            <label class="m-0 p-0" for="startDate">
                                                <i class="ti-calendar" id="start-date-icon"></i>
                                            </label>
                                        </button>
                                    </div>
                                </div>
                                <span class="text-danger">{{ $errors->first('date') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-15">
                        <div class="col-lg-12">
                            <div class="primary_input">
                                <label class="primary_input_label" for="">@lang('common.meeting_time')<span
                                        class="text-danger"> *</span></label>
                                <div class="primary_datepicker_input">
                                    <div class="no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="">
                                                <input
                                                    class="primary_input_field primary_input_field time form-control{{ @$errors->has('time') ? ' is-invalid' : '' }}"
                                                    type="text" name="time" id="meetTime"
                                                    value="{{ isset($editdata) ? old('time', $editdata->time) : old('time') }}">

                                                @if ($errors->has('time'))
                                                    <span class="text-danger d-block">
                                                        {{ $errors->first('time') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <button class="" type="button">
                                            <label class="m-0 p-0" for="meetTime">
                                                <i class="ti-alarm-clock " id="admission-date-icon"></i>
                                            </label>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-15" style="display:none;">
                        <div class="col-lg-12">
                            <div class="primary_input">
                                <label class="primary_input_label" for="">@lang('common.meeting_duration')<span
                                        class="text-danger"> *</span></label>
                                <input oninput="numberCheckWithDot(this)" class="primary_input_field" type="text"
                                    name="duration" autocomplete="off"
                                    value="240"
                                    value="{{ isset($editdata) ? old('duration', $editdata->duration) : old('duration') }}">


                                @if ($errors->has('duration'))
                                    <span class="text-danger">
                                        {{ $errors->first('duration') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mt-15" style="display:none;">
                        <div class="col-lg-12">
                            <div class="primary_input">
                                <label class="primary_input_label" for="">@lang('common.meeting_start_before')</label>
                                <input oninput="numberCheckWithDot(this)" class="primary_input_field" type="text"
                                    name="time_start_before" autocomplete="off"
                                    value="{{ isset($editdata) ? old('time_start_before', $editdata->time_start_before) : 10 }}">


                                @if ($errors->has('time_start_before'))
                                    <span class="text-danger">
                                        {{ $errors->first('time_start_before') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mt-15">
                        
                        <div class="col-lg-12 mt-15">
                            <div class="primary_input">
                                <div class="primary_file_uploader">
                                <input
                                    class="primary_input_field form-control {{ $errors->has('attached_file') ? ' is-invalid' : '' }}"
                                    readonly="true" type="text"
                                    placeholder="{{ isset($editdata->logo) && @$editdata->logo != '' ? getFilePath3(@$editdata->logo) : trans('common.attach_file') }}"
                                    id="placeholderInput">
                                    <button class="" type="button">
                                        <label class="primary-btn small fix-gr-bg" for="browseFile">{{ __('common.browse') }}</label>
                                        <input type="file" class="d-none" name="attached_file" id="browseFile">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Start setting  --}}
                    <div class="row mt-15" style="display:none;">
                        <div class="col-lg-12 d-flex flex-wrap column-gap-20 align-items-center">
                            <p class="fw-500 mb-10">@lang('bbb::bbb.change_default_settings')</p>
                            <div class="d-flex radio-btn-flex ml-3">
                                <div class="mr-30 row">
                                    <input type="radio" name="chnage-default-settings" id="change_default_settings"
                                        value="1" @if (isset($editdata)) checked @endif
                                        class="common-radio chnage-default-settings relationButton">
                                    <label for="change_default_settings">@lang('common.yes')</label>
                                </div>
                                <div class="mr-30 row">
                                    <input type="radio" name="chnage-default-settings"
                                        id="change_default_settings2" value="0"
                                        @if (!isset($editdata)) checked @endif
                                        class="common-radio chnage-default-settings relationButton">
                                    <label for="change_default_settings2">@lang('common.no')</label>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-40  default-settings">


                        <div class="col-lg-12 mt-10">
                            <div class="row">
                                <div class="col-lg-12 d-flex">
                                    <p class="fw-500 mb-10">@lang('bbb::bbb.welcome_message') </p>
                                </div>
                                <div class="col-lg-12">
                                    <div class="radio-btn-flex ml-20">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="">
                                                    <input type="text" name="welcome_message"
                                                        value="@if (!empty($setting)) {{ old('welcome_message', $setting->welcome_message) }} @endif"
                                                        class="form-control">

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-10">
                            <div class="row">
                                <div class="col-lg-12 d-flex">
                                    <p class="fw-500 mb-10">@lang('bbb::bbb.dial_number') </p>
                                </div>
                                <div class="col-lg-12">
                                    <div class="radio-btn-flex ml-20">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="">
                                                    <input type="text" name="dial_number"
                                                        value="@if (!empty($setting)) {{ old('dial_number', $setting->dial_number) }} @endif"
                                                        class="form-control">

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-10">
                            <div class="row">
                                <div class="col-lg-12 d-flex">
                                    <p class="fw-500 mb-10">@lang('bbb::bbb.max_participants')
                                        <small class="text-secondary">0= @lang('bbb::bbb.unlimited')</small>
                                    </p>
                                </div>
                                <div class="col-lg-12">
                                    <div class="radio-btn-flex ml-20">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="">
                                                    <input oninput="numberMinCheck(this)" type="text"
                                                        name="max_participants"
                                                        value="@if (!empty($setting)) {{ old('max_participants', $setting->max_participants) }} @endif"
                                                        class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-10">
                            <div class="row">
                                <div class="col-lg-12 d-flex">
                                    <p class="fw-500 mb-10">@lang('bbb::bbb.logout_url') </p>
                                </div>
                                <div class="col-lg-12">
                                    <div class="radio-btn-flex ml-20">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="">
                                                    <input type="text" name="logout_url"
                                                        value="@if (!empty($setting)) {{ old('logout_url', $setting->logout_url) }} @endif"
                                                        class="form-control">

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-10">
                            <div class="row">
                                <div class="col-lg-12 d-flex">
                                    <p class="fw-500 mb-10">@lang('bbb::bbb.record') </p>
                                </div>
                                <div class="col-lg-12">
                                    <div class="radio-btn-flex ml-20">
                                        <div class="row">
                                            <div class="radio-btn-flex ml-20">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="nowrap">
                                                            <input type="radio" name="record" id="record_on"
                                                                value="1" class="common-radio relationButton"
                                                                @if (!empty($setting)) {{ old('record', $setting->record) == 1 ? 'checked' : '' }} @endif>
                                                            <label for="record_on">@lang('common.enable')</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="">
                                                            <input type="radio" name="record" id="record"
                                                                value="0"
                                                                class="common-radio relationButton"@if (!empty($setting)) {{ old('record', $setting->record) == 0 ? 'checked' : '' }} @endif>
                                                            <label for="record">@lang('common.disable')</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-lg-12 mt-10">
                            <div class="row">
                                <div class="col-lg-12 d-flex">
                                    <p class="fw-500 mb-10">@lang('bbb::bbb.is_breakout') </p>
                                </div>
                                <div class="col-lg-12">
                                    <div class="radio-btn-flex ml-20">
                                        <div class="row">
                                            <div class="radio-btn-flex ml-20">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="nowrap">
                                                            <input type="radio" name="is_breakout"
                                                                id="is_breakout_on" value="1"
                                                                class="common-radio relationButton"
                                                                @if (!empty($setting)) {{ old('is_breakout', $setting->is_breakout) == 1 ? 'checked' : '' }} @endif>
                                                            <label for="is_breakout_on">@lang('common.enable')</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="">
                                                            <input type="radio" name="is_breakout" id="is_breakout"
                                                                value="0"
                                                                class="common-radio relationButton"@if (!empty($setting)) {{ old('is_breakout', $setting->is_breakout) == 0 ? 'checked' : '' }} @endif>
                                                            <label for="is_breakout">@lang('common.disable')</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-10">
                            <div class="row">
                                <div class="col-lg-12 d-flex">
                                    <p class="fw-500 mb-10">@lang('bbb::bbb.moderator_only_message') </p>
                                </div>
                                <div class="col-lg-12">
                                    <div class="radio-btn-flex ml-20">
                                        <div class="row">
                                            <div class="radio-btn-flex ml-20">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="">
                                                            <input type="text" name="moderator_only_message"
                                                                value="@if (!empty($setting)) {{ old('moderator_only_message', $setting->moderator_only_message) }} @endif"
                                                                class="form-control">

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-10">
                            <div class="row">
                                <div class="col-lg-12 d-flex">
                                    <p class="fw-500 mb-10">@lang('bbb::bbb.auto_start_recording') </p>
                                </div>
                                <div class="col-lg-12">
                                    <div class="radio-btn-flex ml-20">
                                        <div class="row">
                                            <div class="radio-btn-flex ml-20">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="nowrap">
                                                            <input type="radio" name="auto_start_recording"
                                                                id="auto_start_recording_on" value="1"
                                                                class="common-radio relationButton"
                                                                @if (!empty($setting)) {{ old('auto_start_recording', $setting->auto_start_recording) == 1 ? 'checked' : '' }} @endif>
                                                            <label
                                                                for="auto_start_recording_on">@lang('common.enable')</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="">
                                                            <input type="radio" name="auto_start_recording"
                                                                id="auto_start_recording" value="0"
                                                                class="common-radio relationButton"@if (!empty($setting)) {{ old('auto_start_recording', $setting->auto_start_recording) == 0 ? 'checked' : '' }} @endif>
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

                        <div class="col-lg-12 mt-10">
                            <div class="row">
                                <div class="col-lg-12 d-flex">
                                    <p class="fw-500 mb-10">@lang('bbb::bbb.allow_start_stop_recording') </p>
                                </div>
                                <div class="col-lg-12">
                                    <div class="radio-btn-flex ml-20">
                                        <div class="row">
                                            <div class="radio-btn-flex ml-20">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="nowrap">
                                                            <input type="radio" name="allow_start_stop_recording"
                                                                id="allow_start_stop_recording_on" value="1"
                                                                class="common-radio relationButton"
                                                                @if (!empty($setting)) {{ old('allow_start_stop_recording', $setting->allow_start_stop_recording) == 1 ? 'checked' : '' }} @endif>
                                                            <label
                                                                for="allow_start_stop_recording_on">@lang('common.enable')</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="">
                                                            <input type="radio" name="allow_start_stop_recording"
                                                                id="allow_start_stop_recording" value="0"
                                                                class="common-radio relationButton"@if (!empty($setting)) {{ old('allow_start_stop_recording', $setting->allow_start_stop_recording) == 0 ? 'checked' : '' }} @endif>
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


                        <div class="col-lg-12 mt-10">
                            <div class="row">
                                <div class="col-lg-12 d-flex">
                                    <p class="fw-500 mb-10">@lang('bbb::bbb.webcams_only_for_moderator') </p>
                                </div>
                                <div class="col-lg-12">
                                    <div class="radio-btn-flex ml-20">
                                        <div class="row">
                                            <div class="radio-btn-flex ml-20">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="nowrap">
                                                            <input type="radio" name="webcams_only_for_moderator"
                                                                id="webcams_only_for_moderator_on" value="1"
                                                                class="common-radio relationButton"
                                                                @if (!empty($setting)) {{ old('webcams_only_for_moderator', $setting->webcams_only_for_moderator) == 1 ? 'checked' : '' }} @endif>
                                                            <label
                                                                for="webcams_only_for_moderator_on">@lang('common.enable')</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="">
                                                            <input type="radio" name="webcams_only_for_moderator"
                                                                id="webcams_only_for_moderator" value="0"
                                                                class="common-radio relationButton"@if (!empty($setting)) {{ old('webcams_only_for_moderator', $setting->webcams_only_for_moderator) == 0 ? 'checked' : '' }} @endif>
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

                        {{-- <div class="col-lg-12 mt-10">
                                        <div class="row">
                                            
                                            <div class="col-lg-12">
                                                <div class="radio-btn-flex ml-20">
                                                    <div class="row">
                                                        <div class="radio-btn-flex ml-20">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    
                                                                    <label class="primary-btn small fix-gr-bg"
                                                                           for="upload_content_file">@lang('common.browse')</label>
                                                                    <input type="file"
                                                                           class="d-none form-control"
                                                                           name="attached_file"
                                                                           id="upload_content_file">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}

                        <div class="col-lg-12 mt-10">
                            <div class="row">
                                <div class="col-lg-12 d-flex">
                                    <p class="fw-500 mb-10">@lang('bbb::bbb.copyright') </p>
                                </div>
                                <div class="col-lg-12">
                                    <div class="radio-btn-flex ml-20">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <input type="text" name="copyright"
                                                    value="@if (!empty($setting)) {{ old('copyright', $setting->copyright) }} @endif"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-10">
                            <div class="row">
                                <div class="col-lg-12 d-flex">
                                    <p class="fw-500 mb-10">@lang('bbb::bbb.mute_on_start') </p>
                                </div>
                                <div class="col-lg-12">
                                    <div class="radio-btn-flex ml-20">
                                        <div class="row">
                                            <div class="radio-btn-flex ml-20">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="nowrap">
                                                            <input type="radio" name="mute_on_start"
                                                                id="mute_on_start_on" value="1"
                                                                class="common-radio relationButton"
                                                                @if (!empty($setting)) {{ old('mute_on_start', $setting->mute_on_start) == 1 ? 'checked' : '' }} @endif>
                                                            <label for="mute_on_start_on">@lang('common.enable')</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="">
                                                            <input type="radio" name="mute_on_start"
                                                                id="mute_on_start" value="0"
                                                                class="common-radio relationButton"@if (!empty($setting)) {{ old('mute_on_start', $setting->mute_on_start) == 0 ? 'checked' : '' }} @endif>
                                                            <label for="mute_on_start">@lang('common.disable')</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-10">
                            <div class="row">
                                <div class="col-lg-12 d-flex">
                                    <p class="fw-500 mb-10">@lang('bbb::bbb.lock_settings_disable_mic') </p>
                                </div>
                                <div class="col-lg-12">
                                    <div class="radio-btn-flex ml-20">
                                        <div class="row">
                                            <div class="radio-btn-flex ml-20">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="nowrap">
                                                            <input type="radio" name="lock_settings_disable_mic"
                                                                id="lock_settings_disable_mic_on" value="1"
                                                                class="common-radio relationButton"
                                                                @if (!empty($setting)) {{ old('lock_settings_disable_mic', $setting->lock_settings_disable_mic) == 1 ? 'checked' : '' }} @endif>
                                                            <label
                                                                for="lock_settings_disable_mic_on">@lang('common.enable')</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="">
                                                            <input type="radio" name="lock_settings_disable_mic"
                                                                id="lock_settings_disable_mic" value="0"
                                                                class="common-radio relationButton"@if (!empty($setting)) {{ old('lock_settings_disable_mic', $setting->lock_settings_disable_mic) == 0 ? 'checked' : '' }} @endif>
                                                            <label
                                                                for="lock_settings_disable_mic">@lang('common.disable')</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-10">
                            <div class="row">
                                <div class="col-lg-12 d-flex">
                                    <p class="fw-500 mb-10">@lang('bbb::bbb.lock_settings_disable_private_chat') </p>
                                </div>
                                <div class="col-lg-12">
                                    <div class="radio-btn-flex ml-20">
                                        <div class="row">
                                            <div class="radio-btn-flex ml-20">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="nowrap">
                                                            <input type="radio"
                                                                name="lock_settings_disable_private_chat"
                                                                id="lock_settings_disable_private_chat_on"
                                                                value="1" class="common-radio relationButton"
                                                                @if (!empty($setting)) {{ old('lock_settings_disable_private_chat', $setting->lock_settings_disable_private_chat) == 1 ? 'checked' : '' }} @endif>
                                                            <label
                                                                for="lock_settings_disable_private_chat_on">@lang('common.enable')</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="">
                                                            <input type="radio"
                                                                name="lock_settings_disable_private_chat"
                                                                id="lock_settings_disable_private_chat" value="0"
                                                                class="common-radio relationButton"@if (!empty($setting)) {{ old('lock_settings_disable_private_chat', $setting->lock_settings_disable_private_chat) == 0 ? 'checked' : '' }} @endif>
                                                            <label
                                                                for="lock_settings_disable_private_chat">@lang('common.disable')</label>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-10">
                            <div class="row">
                                <div class="col-lg-12 d-flex">
                                    <p class="fw-500 mb-10">@lang('bbb::bbb.lock_settings_disable_public_chat') </p>
                                </div>
                                <div class="col-lg-12">
                                    <div class="radio-btn-flex ml-20">
                                        <div class="row">
                                            <div class="radio-btn-flex ml-20">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="nowrap">
                                                            <input type="radio"
                                                                name="lock_settings_disable_public_chat"
                                                                id="lock_settings_disable_public_chat_on"
                                                                value="1" class="common-radio relationButton"
                                                                @if (!empty($setting)) {{ old('lock_settings_disable_public_chat', $setting->lock_settings_disable_public_chat) == 1 ? 'checked' : '' }} @endif>
                                                            <label
                                                                for="lock_settings_disable_public_chat_on">@lang('common.enable')</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="">
                                                            <input type="radio"
                                                                name="lock_settings_disable_public_chat"
                                                                id="lock_settings_disable_public_chat" value="0"
                                                                class="common-radio relationButton"@if (!empty($setting)) {{ old('lock_settings_disable_public_chat', $setting->lock_settings_disable_public_chat) == 0 ? 'checked' : '' }} @endif>
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

                        <div class="col-lg-12 mt-10">
                            <div class="row">
                                <div class="col-lg-12 d-flex">
                                    <p class="fw-500 mb-10">@lang('bbb::bbb.lock_settings_disable_note') </p>
                                </div>
                                <div class="col-lg-12">
                                    <div class="radio-btn-flex ml-20">
                                        <div class="row">
                                            <div class="radio-btn-flex ml-20">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="nowrap">
                                                            <input type="radio" name="lock_settings_disable_note"
                                                                id="lock_settings_disable_note_on" value="1"
                                                                class="common-radio relationButton"
                                                                @if (!empty($setting)) {{ old('lock_settings_disable_note', $setting->lock_settings_disable_note) == 1 ? 'checked' : '' }} @endif>
                                                            <label
                                                                for="lock_settings_disable_note_on">@lang('common.enable')</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="">
                                                            <input type="radio" name="lock_settings_disable_note"
                                                                id="lock_settings_disable_note" value="0"
                                                                class="common-radio relationButton"@if (!empty($setting)) {{ old('lock_settings_disable_note', $setting->lock_settings_disable_note) == 0 ? 'checked' : '' }} @endif>
                                                            <label
                                                                for="lock_settings_disable_note">@lang('common.disable')</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-10">
                            <div class="row">
                                <div class="col-lg-12 d-flex">
                                    <p class="fw-500 mb-10">@lang('bbb::bbb.lock_settings_locked_layout') </p>
                                </div>
                                <div class="col-lg-12">
                                    <div class="radio-btn-flex ml-20">
                                        <div class="row">
                                            <div class="radio-btn-flex ml-20">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="nowrap">
                                                            <input type="radio" name="lock_settings_locked_layout"
                                                                id="lock_settings_locked_layout_on" value="1"
                                                                class="common-radio relationButton"
                                                                @if (!empty($setting)) {{ old('lock_settings_locked_layout', $setting->lock_settings_locked_layout) == 1 ? 'checked' : '' }} @endif>
                                                            <label
                                                                for="lock_settings_locked_layout_on">@lang('common.enable')</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="">
                                                            <input type="radio" name="lock_settings_locked_layout"
                                                                id="lock_settings_locked_layout" value="0"
                                                                class="common-radio relationButton"@if (!empty($setting)) {{ old('lock_settings_locked_layout', $setting->lock_settings_locked_layout) == 0 ? 'checked' : '' }} @endif>
                                                            <label
                                                                for="lock_settings_locked_layout">@lang('common.disable')</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-10">
                            <div class="row">
                                <div class="col-lg-12 d-flex">
                                    <p class="fw-500 mb-10">@lang('bbb::bbb.lock_settings_lock_on_join') </p>
                                </div>
                                <div class="col-lg-12">
                                    <div class="radio-btn-flex ml-20">
                                        <div class="row">
                                            <div class="radio-btn-flex ml-20">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="nowrap">
                                                            <input type="radio" name="lock_settings_lock_on_join"
                                                                id="lock_settings_lock_on_join_on" value="1"
                                                                class="common-radio relationButton"
                                                                @if (!empty($setting)) {{ old('lock_settings_lock_on_join', $setting->lock_settings_lock_on_join) == 1 ? 'checked' : '' }} @endif>
                                                            <label
                                                                for="lock_settings_lock_on_join_on">@lang('common.enable')</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="">
                                                            <input type="radio" name="lock_settings_lock_on_join"
                                                                id="lock_settings_lock_on_join" value="0"
                                                                class="common-radio relationButton"@if (!empty($setting)) {{ old('lock_settings_lock_on_join', $setting->lock_settings_lock_on_join) == 0 ? 'checked' : '' }} @endif>
                                                            <label
                                                                for="lock_settings_lock_on_join">@lang('common.disable')</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-10">
                            <div class="row">
                                <div class="col-lg-12 d-flex">
                                    <p class="fw-500 mb-10">@lang('bbb::bbb.lock_settings_lock_on_join_configurable') </p>
                                </div>
                                <div class="col-lg-12">
                                    <div class="radio-btn-flex ml-20">
                                        <div class="row">
                                            <div class="radio-btn-flex ml-20">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="nowrap">
                                                            <input type="radio"
                                                                name="lock_settings_lock_on_join_configurable"
                                                                id="lock_settings_lock_on_join_configurable_on"
                                                                value="1" class="common-radio relationButton"
                                                                @if (!empty($setting)) {{ old('lock_settings_lock_on_join_configurable', $setting->lock_settings_lock_on_join_configurable) == 1 ? 'checked' : '' }} @endif>
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
                                                                class="common-radio relationButton"@if (!empty($setting)) {{ old('lock_settings_lock_on_join_configurable', $setting->lock_settings_lock_on_join_configurable) == 0 ? 'checked' : '' }} @endif>
                                                            <label
                                                                for="lock_settings_lock_on_join_configurable">@lang('common.disable')</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-10">
                            <div class="row">
                                <div class="col-lg-12 d-flex">
                                    <p class="fw-500 mb-10">@lang('bbb::bbb.guest_policy') </p>
                                </div>
                                <div class="col-lg-12">
                                    <div class="radio-btn-flex ml-20">
                                        <div class="row">
                                            <div class="radio-btn-flex ml-20">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="">
                                                            <select name="guest_policy" id="guest_policy"
                                                                class="primary_select">
                                                                <option value="ALWAYS_ACCEPT"
                                                                    @if (!empty($setting)) @if ($setting->guest_policy == 'ALWAYS_ACCEPT') selected @endif
                                                                    @endif>
                                                                    @lang('bbb::bbb.always_accept')
                                                                </option>
                                                                <option value="ALWAYS_DENY"
                                                                    @if (!empty($setting)) @if ($setting->guest_policy == 'ALWAYS_DENY') selected @endif
                                                                    @endif>
                                                                    @lang('bbb::bbb.always_deny')
                                                                </option>
                                                                <option value="ASK_MODERATOR"
                                                                    @if (!empty($setting)) @if ($setting->guest_policy == 'ASK_MODERATOR') selected @endif
                                                                    @endif>
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

                        <div class="col-lg-12 mt-10">
                            <div class="row">
                                <div class="col-lg-12 d-flex">
                                    <p class="fw-500 mb-10">@lang('bbb::bbb.redirect') </p>
                                </div>
                                <div class="col-lg-12">
                                    <div class="radio-btn-flex ml-20">
                                        <div class="row">
                                            <div class="radio-btn-flex ml-20">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="nowrap">
                                                            <input type="radio" name="redirect" id="redirect_on"
                                                                value="1" class="common-radio relationButton"
                                                                @if (!empty($setting)) {{ old('redirect', $setting->redirect) == 1 ? 'checked' : '' }} @endif>
                                                            <label for="redirect_on">@lang('common.enable')</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="">
                                                            <input type="radio" name="redirect" id="redirect"
                                                                value="0"
                                                                class="common-radio relationButton"@if (!empty($setting)) {{ old('redirect', $setting->redirect) == 0 ? 'checked' : '' }} @endif>
                                                            <label for="redirect">@lang('common.disable')</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-10">
                            <div class="row">
                                <div class="col-lg-12 d-flex">
                                    <p class="fw-500 mb-10">@lang('bbb::bbb.join_via_html5') </p>
                                </div>
                                <div class="col-lg-12">
                                    <div class="radio-btn-flex ml-20">
                                        <div class="row">
                                            <div class="radio-btn-flex ml-20">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="nowrap">
                                                            <input type="radio" name="join_via_html5"
                                                                id="join_via_html5_on" value="1"
                                                                class="common-radio relationButton"
                                                                @if (!empty($setting)) {{ old('join_via_html5', $setting->join_via_html5) == 1 ? 'checked' : '' }} @endif>
                                                            <label for="join_via_html5_on">@lang('common.enable')</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="">
                                                            <input type="radio" name="join_via_html5"
                                                                id="join_via_html5" value="0"
                                                                class="common-radio relationButton"@if (!empty($setting)) {{ old('join_via_html5', $setting->join_via_html5) == 0 ? 'checked' : '' }} @endif>
                                                            <label for="join_via_html5">@lang('common.disable')</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-10">
                            <div class="row">
                                <div class="col-lg-12 d-flex">
                                    <p class="fw-500 mb-10">@lang('bbb::bbb.state') </p>
                                </div>
                                <div class="col-lg-12">
                                    <div class="radio-btn-flex ml-20">
                                        <div class="row">
                                            <div class="radio-btn-flex ml-20">
                                                <div class="row">
                                                    <div class="col-lg-12">

                                                        <select name="state" id="state" class="primary_select">
                                                            <option value="any"
                                                                @if (!empty($setting)) @if ($setting->state == 'any') selected @endif
                                                                @endif>
                                                                @lang('bbb::bbb.any')
                                                            </option>
                                                            <option value="published"
                                                                @if (!empty($setting)) @if ($setting->state == 'published') selected @endif
                                                                @endif>
                                                                @lang('bbb::bbb.published')
                                                            </option>
                                                            <option value="unpublished"
                                                                @if (!empty($setting)) @if ($setting->state == 'unpublished') selected @endif
                                                                @endif>
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
                    </div>

                    {{-- Start setting  --}}

                    <div class="row mt-15">
                        <div class="col-lg-12 text-center">
                            @if (empty($env['security_salt']) || empty($env['server_base_url']))
                                <small class="text-danger">*
                                    @lang('bbb::bbb.please_make_sure_bbb_api_key_setup_successfully'). @lang('bbb::bbb.without_bbb_api_setup_you_can_not_create_class')
                                </small>
                            @else
                                <button class="primary-btn fix-gr-bg">
                                    <span class="ti-check"></span>
                                    @if (isset($editdata))
                                        @lang('common.update')
                                    @else
                                        @lang('common.save')
                                    @endif

                                </button>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
        </form>
    </div>

@endif

@include('backEnd.partials.multi_select_js')
@include('backEnd.partials.date_picker_css_js')
