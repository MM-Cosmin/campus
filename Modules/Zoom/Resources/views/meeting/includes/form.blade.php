
    <div class="col-lg-3">

        <div class="main-title">
            <h3 class="mb-30">
                @if (isset($editData))
                    @lang('common.edit_meeting')
                @else
                    @lang('common.add_meeting')
                @endif

            </h3>
        </div>

        @if (isset($editData))
            <form class="form-horizontal" action="{{ route('zoom.meetings.update', $editData->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
            @else
                @if (userPermission('zoom.meetings.store'))
                    <form class="form-horizontal" action="{{ route('zoom.meetings.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                @endif
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    <div class="row">
                        <div class="col-lg-12 ">
                            <select
                                class="primary_select  user_type form-control{{ $errors->has('user_type') ? ' is-invalid' : '' }}"
                                name="member_type">
                                <option data-display=" @lang('common.member_type') *" value="">@lang('common.member_type') *</option>
                                @foreach ($roles as $value)
                                    @if (isset($editData))
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
                            <select multiple id="selectMultiUsers"
                                class="multypol_check_select active position-relative" name="participate_ids[]"
                                style="width:300px">
                                @if (isset($editData))
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
                                <label class="primary_input_label" for="">@lang('common.topic')<span
                                        class="text-danger"> *</span></label>
                                <input class="primary_input_field" type="text" name="topic" autocomplete="off"
                                    value="{{ isset($editData) ? old('topic', $editData->topic) : old('topic') }}">


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
                                <textarea class="primary_input_field form-control" cols="0" rows="4" name="description" id="address">{{ isset($editData) ? old('description', $editData->description) : old('description') }}</textarea>


                                @if ($errors->has('description'))
                                    <span class="text-danger">
                                        {{ $errors->first('description') }}
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
                                                    value="{{ isset($editData) ? old('date', Carbon\Carbon::parse($editData->date_of_meeting)->format('m/d/Y')) : old('date', Carbon\Carbon::now()->format('m/d/Y')) }}"
                                                    required>
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
                    </div>
                    <div class="row mt-15">
                        <div class="col-lg-12">
                            <div class="primary_input">
                                <label class="primary_input_label" for="">@lang('zoom::zoom.time_of_meeting')<span
                                        class="text-danger"> *</span></label>
                                <div class="primary_datepicker_input">
                                    <div class="no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="">
                                                <input class="primary_input_field primary_input_field time" type="text"
                                                    name="time" id="in_time"
                                                    value="{{ isset($editData) ? old('time', $editData->time_of_meeting) : old('time') }}">

                                                
                                            </div>
                                        </div>
                                        <button class="" type="button">
                                            <label class="m-0 p-0" for="in_time">
                                                <i class="ti-alarm-clock " id="admission-date-icon"></i>
                                            </label>
                                        </button>
                                    </div>
                                    @if ($errors->has('time'))
                                                    <span class="text-danger d-block">
                                                        {{ $errors->first('time') }}
                                                    </span>
                                                @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-15">
                        <div class="col-lg-12">
                            <div class="primary_input">
                                <label class="primary_input_label" for="">@lang('zoom::zoom.meeting_durration')<span
                                        class="text-danger"> *</span></label>
                                <input type="number" oninput="numberCheck(this)"
                                    class="primary_input_field form-control{{ $errors->has('duration') ? ' is-invalid' : '' }}"
                                    type="text" name="duration" autocomplete="off"
                                    value="{{ isset($editData) ? old('duration', $editData->meeting_duration) : old('duration') }}">


                                @if ($errors->has('duration'))
                                    <span class="text-danger">
                                        {{ $errors->first('duration') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mt-15">
                        <div class="col-lg-12">
                            <div class="primary_input">
                                <label class="primary_input_label" for="">@lang('common.meeting_start_before')</label>
                                <input type="number" oninput="numberCheck(this)"
                                    class="primary_input_field form-control{{ $errors->has('time_start_before') ? ' is-invalid' : '' }}"
                                    type="text" name="time_start_before" autocomplete="off"
                                    value="{{ isset($editData) ? old('time_start_before', $editData->time_before_start) : 10 }}">


                                @if ($errors->has('time_start_before'))
                                    <span class="text-danger">
                                        {{ $errors->first('time_start_before') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mt-15">
                        <div class="col-lg-12">
                            <div class="primary_input">
                                <label class="primary_input_label" for="">@lang('common.password')<span
                                        class="text-danger"> *</span></label>
                                <input
                                    class="primary_input_field form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                    type="text" name="password" autocomplete="off"
                                    value="{{ isset($editData) ? old('password', $editData->password) : old('password', 123456) }}">


                                @if ($errors->has('password'))
                                    <span class="text-danger">
                                        {{ $errors->first('password') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mt-30">
                        <div class="col-lg-12 d-flex">
                            <p class="text-uppercase fw-500 mb-10" style="width: 130px;">@lang('zoom::zoom.zoom_recurring')</p>
                            <div class="d-flex radio-btn-flex ml-40">
                                @if (isset($editData))
                                    <div class="mr-30">
                                        <input type="radio" name="is_recurring" id="recurring_options1"
                                            value="1" class="common-radio recurring-type"
                                            {{ old('is_recurring', $editData->is_recurring) == 1 ? 'checked' : '' }}>
                                        <label for="recurring_options1">@lang('common.yes')</label>
                                    </div>
                                    <div class="mr-30">
                                        <input type="radio" name="is_recurring" id="recurring_options2"
                                            value="0" class="common-radio recurring-type"
                                            {{ old('is_recurring', $editData->is_recurring) == 0 ? 'checked' : '' }}>
                                        <label for="recurring_options2">@lang('common.no')</label>
                                    </div>
                                @else
                                    <div class="mr-30">
                                        <input type="radio" name="is_recurring" id="recurring_options1"
                                            value="1" class="common-radio recurring-type"
                                            {{ old('is_recurring') == 1 ? 'checked' : '' }}>
                                        <label for="recurring_options1">@lang('common.yes')</label>
                                    </div>
                                    <div class="mr-30">
                                        <input type="radio" name="is_recurring" id="recurring_options2"
                                            value="0" class="common-radio recurring-type"
                                            {{ old('is_recurring') == 0 ? 'checked' : '' }}>
                                        <label for="recurring_options2">@lang('common.no')</label>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>

                    <div class="row mt-20 recurrence-section-hide">
                        <div class="col-lg-6">
                            {{-- <label>Recurrence Type <span class="text-danger"> *</span></label> --}}
                            <select
                                class="primary_select form-control {{ @$errors->has('recurring_type') ? ' is-invalid' : '' }}"
                                id="recurring_type" name="recurring_type">
                                <option data-display="@lang('zoom::zoom.type') *" value="">@lang('zoom::zoom.type') *
                                </option>
                                @if (isset($editData))
                                    <option value="1"
                                        {{ old('recurring_type', $editData->recurring_type) == 1 ? 'selected' : '' }}>
                                        @lang('zoom::zoom.zoom_recurring_daily')</option>
                                    <option value="2"
                                        {{ old('recurring_type', $editData->recurring_type) == 2 ? 'selected' : '' }}>
                                        @lang('zoom::zoom.zoom_recurring_weekly')</option>
                                    <option value="3"
                                        {{ old('recurring_type', $editData->recurring_type) == 3 ? 'selected' : '' }}>
                                        @lang('zoom::zoom.zoom_recurring_monthly') </option>
                                @else
                                    <option value="1" {{ old('recurring_type') == 1 ? 'selected' : '' }}>
                                        @lang('zoom::zoom.zoom_recurring_daily')</option>
                                    <option value="2" {{ old('recurring_type') == 2 ? 'selected' : '' }}>
                                        @lang('zoom::zoom.zoom_recurring_weekly')</option>
                                    <option value="3" {{ old('recurring_type') == 3 ? 'selected' : '' }}>
                                        @lang('zoom::zoom.zoom_recurring_monthly') </option>
                                @endif
                            </select>
                            @if ($errors->has('recurring_type'))
                                <span class="text-danger invalid-select" role="alert">
                                    {{ @$errors->first('recurring_type') }}
                                </span>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            {{-- <label>Repeat every <span class="text-danger"> *</span></label> --}}
                            <select
                                class="primary_select form-control {{ @$errors->has('recurring_repect_day') ? ' is-invalid' : '' }}"
                                id="recurring_repect_day" name="recurring_repect_day">
                                <option data-display=" @lang('common.select') *" value="">@lang('zoom::zoom.zoom_recurring_repect') *
                                </option>
                                @for ($i = 1; $i <= 15; $i++)
                                    @if (isset($editData))
                                        <option value="{{ $i }}"
                                            {{ old('recurring_repect_day', $editData->recurring_repect_day) == $i ? 'selected' : '' }}>
                                            {{ $i }}</option>
                                    @else
                                        <option value="{{ $i }}"
                                            {{ old('recurring_repect_day') == $i ? 'selected' : '' }}>
                                            {{ $i }}</option>
                                    @endif
                                @endfor
                            </select>
                            @if ($errors->has('recurring_repect_day'))
                                <span class="text-danger invalid-select" role="alert">
                                    {{ @$errors->first('recurring_repect_day') }}
                                </span>
                            @endif
                        </div>

                        <div class="row mt-30 day_hide" id="day_hide">
                            <div class="col-lg-12 ml-15">
                                <label class="primary_input_label" for="">@lang('zoom::zoom.occurs_on') <span
                                        class="text-danger"> *</span></label>
                                @foreach ($days as $day)
                                    <div class="row ml-15">
                                        <div class="">
                                            @if (isset($editData))
                                                <input type="checkbox" id="day{{ @$day->id }}"
                                                    class="common-checkbox form-control{{ @$errors->has('days') ? ' is-invalid' : '' }}"
                                                    name="days[]"
                                                    value="{{ @$day->zoom_order }}"{{ in_array($day->zoom_order, $assign_day ?? '') ? 'checked' : '' }}>
                                                <label for="day{{ @$day->id }}">{{ @$day->name }}</label>
                                            @else
                                                <input type="checkbox" id="day{{ @$day->id }}"
                                                    class="common-checkbox form-control{{ @$errors->has('days') ? ' is-invalid' : '' }}"
                                                    name="days[]" value="{{ @$day->zoom_order }}">
                                                <label for="day{{ @$day->id }}"> {{ @$day->name }}</label>
                                            @endif
                                        </div>

                                    </div>
                                @endforeach
                                @if ($errors->has('days'))
                                    <span class="text-danger" style="display:block">
                                        {{ $errors->first('days') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>

                    <div class="row mt-30 recurrence-section-hide">
                        <div class="col-lg-12">
                            <div class="primary_input">
                                <label class="primary_input_label" for="">@lang('zoom::zoom.zoom_recurring_end') <span
                                        class="text-danger"> *</span></label>
                                <div class="primary_datepicker_input">
                                    <div class="no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="">
                                                <input
                                                    class="primary_input_field  primary_input_field date form-control form-control"
                                                    sty id="recurring_end_date" type="text"
                                                    name="recurring_end_date" readonly="true"
                                                    value="{{ isset($editData) ? old('recurring_end_date', Carbon\Carbon::parse($editData->recurring_end_date)->format('m/d/Y')) : old('recurring_end_date') }}"
                                                    required>
                                            </div>
                                        </div>
                                        <button class="btn-date" data-id="#startDate" type="button">
                                            <i class="ti-calendar" id="start-date-icon"></i>
                                        </button>
                                    </div>
                                </div>
                                <span class="text-danger">{{ $errors->first('recurring_end_date') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters input-right-icon mt-30">

                        <div class="col-lg-12 mt-15">
                            <div class="primary_input">
                                <div class="primary_file_uploader">
                                    <input
                                        class="primary_input_field form-control {{ $errors->has('attached_file') ? ' is-invalid' : '' }}"
                                        readonly="true" type="text"
                                        placeholder="{{ isset($editData->attached_file) && @$editData->attached_file != '' ? getFilePath3(@$editData->attached_file) : 'Attach File ' }}"
                                        id="placeholderInput">

                                    <button class="" type="button">
                                        <label class="primary-btn small fix-gr-bg"
                                            for="browseFile">{{ __('common.browse') }}</label>
                                        <input type="file" class="d-none" name="attached_file" id="browseFile">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Start setting  --}}
                    <div class="row mt-15">
                        <div class="col-lg-12 d-flex">
                            <p class="text-uppercase fw-500 mb-10" style="width: 130px;">@lang('zoom::zoom.change_default_settings')</p>
                            <div class="d-flex radio-btn-flex ml-40">
                                <div class="mr-30 row">
                                    <input type="radio" name="chnage-default-settings" id="change_default_settings"
                                        value="1" @if (isset($editData)) checked @endif
                                        class="common-radio chnage-default-settings relationButton">
                                    <label for="change_default_settings">@lang('common.yes')</label>
                                </div>
                                <div class="mr-30 row">
                                    <input type="radio" name="chnage-default-settings"
                                        id="change_default_settings2" value="0"
                                        @if (!isset($editData)) checked @endif
                                        class="common-radio chnage-default-settings relationButton">
                                    <label for="change_default_settings2">@lang('common.no')</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-15 default-settings">
                        <div class="col-lg-12 d-flex">
                            <p class="text-uppercase fw-500 mb-10" style="width: 130px;">@lang('zoom::zoom.join_before_host')</p>
                            <div class="d-flex radio-btn-flex ml-40">
                                @if (isset($editData))
                                    <div class="mr-30 row">
                                        <input type="radio" name="join_before_host" id="metting_options1"
                                            value="1" class="common-radio relationButton"
                                            {{ old('join_before_host', $editData->join_before_host) == 1 ? 'checked' : '' }}>
                                        <label for="metting_options1">@lang('common.yes')</label>
                                    </div>
                                    <div class="mr-30 row">
                                        <input type="radio" name="join_before_host" id="metting_options2"
                                            value="0" class="common-radio relationButton"
                                            {{ old('join_before_host', $editData->join_before_host) == 0 ? 'checked' : '' }}>
                                        <label for="metting_options2">@lang('common.no')</label>
                                    </div>
                                @else
                                    <div class="mr-30 row">
                                        <input type="radio" name="join_before_host" id="metting_options1"
                                            value="1" class="common-radio relationButton"
                                            {{ old('join_before_host', $default_settings->join_before_host) == 1 ? 'checked' : '' }}>
                                        <label for="metting_options1">@lang('common.yes')</label>
                                    </div>
                                    <div class="mr-30 row">
                                        <input type="radio" name="join_before_host" id="metting_options2"
                                            value="0" class="common-radio relationButton"
                                            {{ old('join_before_host', $default_settings->join_before_host) == 0 ? 'checked' : '' }}>
                                        <label for="metting_options2">@lang('common.no')</label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mt-30 default-settings">
                        <div class="col-lg-12 d-flex">
                            <p class="text-uppercase fw-500 mb-10" style="width: 130px;">@lang('zoom::zoom.host_video')</p>
                            <div class="d-flex radio-btn-flex ml-40">
                                @if (isset($editData))
                                    <div class="mr-30 row">
                                        <input type="radio" name="host_video" id="host_video1" value="1"
                                            class="common-radio relationButton"
                                            {{ old('host_video', $editData->host_video) == 1 ? 'checked' : '' }}>
                                        <label for="host_video1">@lang('common.yes')</label>
                                    </div>
                                    <div class="mr-30 row">
                                        <input type="radio" name="host_video" id="host_video2" value="0"
                                            class="common-radio relationButton"
                                            {{ old('host_video', $editData->host_video) == 0 ? 'checked' : '' }}>
                                        <label for="host_video2">@lang('common.no')</label>
                                    </div>
                                @else
                                    <div class="mr-30 row">
                                        <input type="radio" name="host_video" id="host_video1" value="1"
                                            class="common-radio relationButton"
                                            {{ old('host_video', $default_settings->host_video) == 1 ? 'checked' : '' }}>
                                        <label for="host_video1">@lang('common.yes')</label>
                                    </div>
                                    <div class="mr-30 row">
                                        <input type="radio" name="host_video" id="host_video2" value="0"
                                            class="common-radio relationButton"
                                            {{ old('host_video', $default_settings->host_video) == 0 ? 'checked' : '' }}>
                                        <label for="host_video2">@lang('common.no')</label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mt-30 default-settings">
                        <div class="col-lg-12 d-flex">
                            <p class="text-uppercase fw-500 mb-10" style="width: 130px;">@lang('zoom::zoom.participant_video')</p>
                            <div class="d-flex radio-btn-flex ml-40">
                                @if (isset($editData))
                                    <div class="mr-30 row">
                                        <input type="radio" name="participant_video" id="host_video3"
                                            value="1" class="common-radio"
                                            {{ old('participant_video', $editData->participant_video) == 1 ? 'checked' : '' }}>
                                        <label for="host_video3">@lang('common.yes')</label>
                                    </div>
                                    <div class="mr-30 row">
                                        <input type="radio" name="participant_video" id="host_video4"
                                            value="0" class="common-radio"
                                            {{ old('participant_video', $editData->participant_video) == 0 ? 'checked' : '' }}>
                                        <label for="host_video4">@lang('common.no')</label>
                                    </div>
                                @else
                                    <div class="mr-30 row">
                                        <input type="radio" name="participant_video" id="host_video3"
                                            value="1" class="common-radio"
                                            {{ old('participant_video', $default_settings->participant_video) == 1 ? 'checked' : '' }}>
                                        <label for="host_video3">@lang('common.yes')</label>
                                    </div>
                                    <div class="mr-30 row">
                                        <input type="radio" name="participant_video" id="host_video4"
                                            value="0" class="common-radio"
                                            {{ old('participant_video', $default_settings->participant_video) == 0 ? 'checked' : '' }}>
                                        <label for="host_video4">@lang('common.no')</label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mt-30 default-settings">
                        <div class="col-lg-12 d-flex">
                            <p class="text-uppercase fw-500 mb-10" style="width: 130px;">@lang('zoom::zoom.mute_upon_entry') </p>
                            <div class="d-flex radio-btn-flex ml-40">
                                @if (isset($editData))
                                    <div class="mr-30 row">
                                        <input type="radio" name="mute_upon_entry" id="mute_upon_entry_on"
                                            value="1" class="common-radio"
                                            {{ old('mute_upon_entry', $editData->mute_upon_entry) == 1 ? 'checked' : '' }}>
                                        <label for="mute_upon_entry_on">@lang('common.yes')</label>
                                    </div>
                                    <div class="mr-30 row">
                                        <input type="radio" name="mute_upon_entry" id="mute_upon_entry"
                                            value="0" class="common-radio"
                                            {{ old('mute_upon_entry', $editData->mute_upon_entry) == 0 ? 'checked' : '' }}>
                                        <label for="mute_upon_entry">@lang('common.no')</label>
                                    </div>
                                @else
                                    <div class="mr-30 row">
                                        <input type="radio" name="mute_upon_entry" id="mute_upon_entry_on"
                                            value="1" class="common-radio"
                                            {{ old('mute_upon_entry', $default_settings->mute_upon_entry) == 1 ? 'checked' : '' }}>
                                        <label for="mute_upon_entry_on">@lang('common.yes')</label>
                                    </div>
                                    <div class="mr-30 row">
                                        <input type="radio" name="mute_upon_entry" id="mute_upon_entry"
                                            value="0" class="common-radio"
                                            {{ old('mute_upon_entry', $default_settings->mute_upon_entry) == 0 ? 'checked' : '' }}>
                                        <label for="mute_upon_entry">@lang('common.no')</label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mt-30 default-settings">
                        <div class="col-lg-12 d-flex">
                            <p class="text-uppercase fw-500 mb-10" style="width: 130px;">@lang('zoom::zoom.waiting_room')</p>
                            <div class="d-flex radio-btn-flex ml-40">
                                @if (isset($editData))
                                    <div class="mr-30 row">
                                        <input type="radio" name="waiting_room" id="waiting_room_on"
                                            value="1" class="common-radio"
                                            {{ old('waiting_room', $editData->waiting_room) == 1 ? 'checked' : '' }}>
                                        <label for="waiting_room_on">@lang('common.yes')</label>
                                    </div>
                                    <div class="mr-30 row">
                                        <input type="radio" name="waiting_room" id="waiting_room" value="0"
                                            class="common-radio"
                                            {{ old('waiting_room', $editData->waiting_room) == 0 ? 'checked' : '' }}>
                                        <label for="waiting_room">@lang('common.no')</label>
                                    </div>
                                @else
                                    <div class="mr-30 row">
                                        <input type="radio" name="waiting_room" id="waiting_room_on"
                                            value="1" class="common-radio"
                                            {{ old('waiting_room', $default_settings->waiting_room) == 1 ? 'checked' : '' }}>
                                        <label for="waiting_room_on">@lang('common.yes')</label>
                                    </div>
                                    <div class="mr-30 row">
                                        <input type="radio" name="waiting_room" id="waiting_room" value="0"
                                            class="common-radio"
                                            {{ old('waiting_room', $default_settings->waiting_room) == 0 ? 'checked' : '' }}>
                                        <label for="waiting_room">@lang('common.no')</label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if ($default_settings->package_id != 1)
                        <div class="row mt-30">
                            <div class="col-lg-12 row">
                                <p class="text-uppercase fw-500 mb-10 col-lg-6" style="width: 130px;">
                                    @lang('zoom::zoom.auto_recording')</p>
                                <div class="col-lg-6">
                                    <select
                                        class="primary_select form-control {{ @$errors->has('auto_recording') ? ' is-invalid' : '' }}"
                                        name="auto_recording">
                                        @if (isset($editData))
                                            <option value="none"
                                                {{ old('auto_recording', $editData->auto_recording) == 'none' ? 'selected' : '' }}>
                                                @lang('common.none')</option>
                                            <option value="local"
                                                {{ old('auto_recording', $editData->auto_recording) == 'local' ? 'selected' : '' }}>
                                                @lang('zoom::zoom.local')</option>
                                            <option value="cloud"
                                                {{ old('auto_recording', $editData->auto_recording) == 'cloud' ? 'selected' : '' }}>
                                                @lang('zoom::zoom.cloud')</option>
                                        @else
                                            <option value="none"
                                                {{ old('auto_recording', $default_settings->auto_recording) == 'none' ? 'selected' : '' }}>
                                                @lang('common.none')</option>
                                            <option value="local"
                                                {{ old('auto_recording', $default_settings->auto_recording) == 'local' ? 'selected' : '' }}>
                                                @lang('zoom::zoom.local')</option>
                                            <option value="cloud"
                                                {{ old('auto_recording', $default_settings->auto_recording) == 'cloud' ? 'selected' : '' }}>
                                                @lang('zoom::zoom.cloud')</option>
                                        @endif
                                    </select>
                                    @if ($errors->has('auto_recording'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ @$errors->first('auto_recording') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row mt-30 default-settings">
                        <div class="col-lg-12 row">
                            <p class="text-uppercase fw-500 mb-10 col-lg-6" style="width: 130px;">@lang('zoom::zoom.audio_options')
                            </p>
                            <div class="col-lg-6">
                                <select
                                    class="primary_select form-control {{ @$errors->has('audio') ? ' is-invalid' : '' }}"
                                    name="audio">
                                    <option data-display="@lang('zoom::zoom.select_package') *" value="">@lang('zoom::zoom.select_package') *
                                    </option>
                                    @if (isset($editData))
                                        <option value="both"
                                            {{ old('audio', $editData->audio) == 'both' ? 'selected' : '' }}>
                                            @lang('zoom::zoom.both')</option>
                                        <option value="telephony"
                                            {{ old('audio', $editData->audio) == 'telephony' ? 'selected' : '' }}>
                                            @lang('zoom::zoom.telephony')</option>
                                        <option value="voip"
                                            {{ old('audio', $editData->audio) == 'voip' ? 'selected' : '' }}>
                                            @lang('zoom::zoom.voip')</option>
                                    @else
                                        <option value="both"
                                            {{ old('audio', $default_settings->audio) == 'both' ? 'selected' : '' }}>
                                            @lang('zoom::zoom.both')</option>
                                        <option value="telephony"
                                            {{ old('audio', $default_settings->audio) == 'telephony' ? 'selected' : '' }}>
                                            @lang('zoom::zoom.telephony')</option>
                                        <option value="voip"
                                            {{ old('audio', $default_settings->audio) == 'voip' ? 'selected' : '' }}>
                                            @lang('zoom::zoom.voip')</option>
                                    @endif

                                </select>
                                @if ($errors->has('audio'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ @$errors->first('audio') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mt-30 default-settings">
                        <div class="col-lg-12 row">
                            <p class="text-uppercase fw-500 mb-10 col-lg-6" style="width: 130px;">@lang('zoom::zoom.meeting_approval')
                            </p>
                            <div class="col-lg-6">
                                <select
                                    class="primary_select form-control {{ @$errors->has('approval_type') ? ' is-invalid' : '' }}"
                                    name="approval_type">
                                    @if (isset($editData))
                                        <option data-display="@lang('zoom::zoom.select_package') *" value="">@lang('zoom::zoom.select_package')
                                            *</option>
                                        <option value="0"
                                            {{ old('approval_type', $editData->approval_type) == 0 ? 'selected' : '' }}>
                                            @lang('zoom::zoom.automatically')</option>
                                        <option value="1"
                                            {{ old('approval_type', $editData->approval_type) == 1 ? 'selected' : '' }}>
                                            @lang('zoom::zoom.manually_approve')</option>
                                        <option value="2"
                                            {{ old('approval_type', $editData->approval_type) == 2 ? 'selected' : '' }}>
                                            @lang('common.no_registration_required')</option>
                                    @else
                                        <option data-display="@lang('zoom::zoom.select_package') *" value="">@lang('zoom::zoom.select_package')
                                            *</option>
                                        <option value="0"
                                            {{ old('approval_type', $default_settings->approval_type) == 0 ? 'selected' : '' }}>
                                            @lang('zoom::zoom.automatically')</option>
                                        <option value="1"
                                            {{ old('approval_type', $default_settings->approval_type) == 1 ? 'selected' : '' }}>
                                            @lang('zoom::zoom.manually_approve')</option>
                                        <option value="2"
                                            {{ old('approval_type', $default_settings->approval_type) == 2 ? 'selected' : '' }}>
                                            @lang('common.no_registration_required')</option>
                                    @endif

                                </select>
                                @if ($errors->has('approval_type'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ @$errors->first('approval_type') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Start setting  --}}

                    @php
                        $tooltip = '';
                        if (userPermission('zoom.meetings.store') || userPermission('zoom.meetings.edit')) {
                            $tooltip = '';
                        } else {
                            $tooltip = 'You have no permission to add';
                        }
                    @endphp
                    <div class="row mt-15">
                        <div class="col-lg-12 text-center">
                            <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip"
                                title="{{ $tooltip }}">
                                <span class="ti-check"></span>
                                @if (isset($editData))
                                    @lang('zoom::zoom.update_meeting')
                                @else
                                    @lang('zoom::zoom.save_meeting')
                                @endif

                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        </form>
    </div>


@include('backEnd.partials.multi_select_js')
@include('backEnd.partials.date_picker_css_js')