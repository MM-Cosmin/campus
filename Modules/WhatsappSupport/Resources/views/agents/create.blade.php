@extends('backEnd.master')
@section('title')
    {{ __('whatsappsupport::general.agents') }}
@endsection
@push('css')
    <style>
        .input-right-icon {
            z-index: inherit !important;
        }

        .col-md-3.dayTimeAlign {
            top: 17px;
        }
    </style>
@endpush
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>{{ __('whatsappsupport::general.agents') }}</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">{{ __('whatsappsupport::general.whatsapp_support') }}</a>
                    <a
                        href="{{ route('whatsapp-support.agents') }}">{{ __('whatsappsupport::general.agents') }}</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
                <div class="col-12">
                    @include('whatsappsupport::partials._flash')
                    <div class="">
                        <div class="row">
                            <div class="col-12">
                                <div class="box_header common_table_header">
                                    <div class="main-title d-flex justify-content-between" style="width:100%;">
                                        <h3 class="mb-0 mr-30">{{ __('whatsappsupport::general.create_agent') }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="white_box_30px">
                                    <form action="{{ route('whatsapp-support.agents.store') }}" method=POST
                                        enctype=multipart/form-data>
                                        @csrf
                                        <div class="row" id="pusher">

                                            <div class="col-xl-6">
                                                <div class="primary_input mb-25">
                                                    <label class="primary_input_label"
                                                        for="">{{ __('whatsappsupport::general.name') }} *</label>
                                                    <input class="primary_input_field" name="name" type="text"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="col-xl-6">
                                                <div class="primary_input mb-25">
                                                    <label class="primary_input_label"
                                                        for="">{{ __('whatsappsupport::general.designation') }}
                                                        *</label>
                                                    <input class="primary_input_field" name="designation" type="text"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="col-xl-6">
                                                <div class="primary_input mb-25">
                                                    <label class="primary_input_label"
                                                        for="">{{ __('whatsappsupport::general.number') }} *
                                                        (<small
                                                            class="text-danger">{{ __('whatsappsupport::general.with_country_code') }}</small>)</label>
                                                    <input class="primary_input_field" name="number" type="text"
                                                        required
                                                        placeholder="{{ __('whatsappsupport::general.with_country_code') }}..">
                                                </div>
                                            </div>

                                            <div class="col-xl-6">
                                                <div class="primary_input mb-25">
                                                    <label class="primary_input_label"
                                                        for="">{{ __('whatsappsupport::general.avatar') }} </label>
                                                    <div class="primary_file_uploader">
                                                        <input class="primary_input_field" type="text"
                                                            id="placeholderFileOneName" name="avatar"
                                                            placeholder="{{ __('whatsappsupport::general.browse_avatar') }}"
                                                            id="placeholderUploadContent" readonly>
                                                        <button class="" type="button">
                                                            <label class="primary-btn small fix-gr-bg"
                                                                for="document_file_1">{{ __('whatsappsupport::general.browse') }}</label>
                                                            <input name="avatar" type="file" class="d-none"
                                                                id="document_file_1">
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-6">
                                                <p class="primary_input_label">{{ __('whatsappsupport::general.status') }}
                                                    *</p>
                                                <div class="d-flex radio-btn-flex">
                                                    <div class="mr-20">
                                                        <input type="radio" checked name="status" required
                                                            id="relationFather3" value="1"
                                                            class="common-radio relationButton">
                                                        <label
                                                            for="relationFather3">{{ __('whatsappsupport::general.active') }}</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="status" id="relationMother4"
                                                            value="0" class="common-radio relationButton">
                                                        <label
                                                            for="relationMother4">{{ __('whatsappsupport::general.inactive') }}</label>
                                                    </div>
                                                    @error('status')
                                                        <small class="text-danger font-italic">*{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-xl-6">
                                                <p class="primary_input_label">
                                                    {{ __('whatsappsupport::general.always_available') }} *</p>
                                                <div class="d-flex radio-btn-flex">
                                                    <div class="mr-20">
                                                        <input type="radio" name="always_available" required
                                                            id="relationFather33333" value="1"
                                                            class="common-radio relationButton" checked>
                                                        <label
                                                            for="relationFather33333">{{ __('whatsappsupport::general.yes') }}</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="always_available"
                                                            id="relationMother4433" value="0"
                                                            class="common-radio relationButton">
                                                        <label
                                                            for="relationMother4433">{{ __('whatsappsupport::general.no') }}</label>
                                                    </div>
                                                    @error('always_available')
                                                        <small class="text-danger font-italic">*{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-xl-6 mt-4">
                                                <h4>{{ __('whatsappsupport::general.time') }}: </h4>
                                                @foreach (\Carbon\Carbon::getDays() as $index => $day)
                                                    <div
                                                        class="row no-gutters input-right-icon mt-25 d-flex align-items-center">
                                                        <div class="col-md-3 dayTimeAlign">
                                                            <div class="input-effect">
                                                                <input type="checkbox" id="isBreak{{ $index }}"
                                                                    class="common-checkbox read-only-input"
                                                                    value="{{ $day }}" name="day[]">
                                                                <label
                                                                    for="isBreak{{ $index }}">{{ $day }}</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="col">
                                                                <div class="primary_input">
                                                                    <label
                                                                        class="primary_input_label">{{ __('whatsappsupport::general.start_time') }}
                                                                        <span class="text-danger"> *</span></label>
                                                                    <div class="primary_datepicker_input">
                                                                        <div class="no-gutters input-right-icon">
                                                                            <div class="col">
                                                                                <div class="">
                                                                                    <input
                                                                                        class="primary_input_field primary_input_field time"
                                                                                        id="agent_start{{ $index }}"
                                                                                        type="text"
                                                                                        name="start[]"
                                                                                        value="{{ Carbon::create("10:00 am")->format('H:i') }}">
                                                                                </div>
                                                                            </div>
                                                                            <button class="" type="button">
                                                                                <label class="m-0 p-0"
                                                                                    for="agent_start{{ $index }}">
                                                                                    <i class="ti-timer"></i>
                                                                                </label>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="col">
                                                                <div class="primary_input">
                                                                    <label
                                                                        class="primary_input_label">{{ __('whatsappsupport::general.end_time') }}
                                                                        <span class="text-danger"> *</span></label>
                                                                    <div class="primary_datepicker_input">
                                                                        <div class="no-gutters input-right-icon">
                                                                            <div class="col">
                                                                                <div class="">
                                                                                    <input
                                                                                        class="primary_input_field primary_input_field time"
                                                                                        id="agent_end{{ $index }}"
                                                                                        type="text"
                                                                                        name="end[]"
                                                                                        value="{{ Carbon::create("5:00 pm")->format('H:i') }}">
                                                                                </div>
                                                                            </div>
                                                                            <button class="" type="button">
                                                                                <label class="m-0 p-0"
                                                                                    for="agent_end{{ $index }}">
                                                                                    <i class="ti-timer"></i>
                                                                                </label>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 dayTimeAlign">
                                                            @if ($index == 0)
                                                                <p onclick="setTimeToAll()"
                                                                    class="primary-btn radius_30px fix-gr-bg ml-3">
                                                                    {{ __('whatsappsupport::general.apply_all_days') }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <button class="primary-btn radius_30px fix-gr-bg mt-4">
                                            <i class="ti-check"></i>
                                            {{ __('whatsappsupport::general.save') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@include('backEnd.partials.date_picker_css_js')
