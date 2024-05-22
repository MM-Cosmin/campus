@extends('backEnd.master')
@section('title')
    {{ __('whatsappsupport::general.settings') }}
@endsection
@push('css')
    <style>
        .radio-btn-flex label::before,
        .radio-btn-flex label::after {
            top: 0% !important;
            transform: translateY(0%) scale(1) rotate(8deg) !important;
        }
    </style>
@endpush
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>{{ __('whatsappsupport::general.settings') }}</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">{{ __('whatsappsupport::general.whatsapp_support') }}</a>
                    <a href="{{ route('whatsapp-support.settings') }}">{{ __('whatsappsupport::general.settings') }}</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="white-box">
                    <div class="row">
                        <div class="col-12">
                            <div class="box_header common_table_header">
                                <div class="main-title d-flex justify-content-between" style="width:100%;">
                                    <h3 class="mb-0 mr-30">{{ __('whatsappsupport::general.settings') }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div>
                                <form action="{{ route('whatsapp-support.settings.update') }}" method="post">
                                    @csrf
                                    <input type="hidden" value="{{ $whatappSupportSettings->id }}" name="id">
                                    <div class="row" id="pusher">
                                        <div class="col-xl-12">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                    for="">{{ __('whatsappsupport::general.color') }}</label>
                                                <input class="primary_input_field" type="color" name="color"
                                                    value="{{ $whatappSupportSettings->color ?? '' }}">
                                            </div>
                                        </div>

                                        <div class="col-xl-12">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                    for="">{{ __('whatsappsupport::general.intro_text') }}</label>
                                                <input class="primary_input_field" name="intro_text" type="text"
                                                    value="{{ $whatappSupportSettings->intro_text ?? '' }}">
                                            </div>
                                        </div>

                                        <div class="col-xl-12">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                    for="">{{ __('whatsappsupport::general.welcome_message') }}</label>
                                                <input class="primary_input_field" name="welcome_message" type="text"
                                                    value="{{ $whatappSupportSettings->welcome_message ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <button class="primary-btn radius_30px  fix-gr-bg"><i
                                            class="ti-check"></i>{{ __('whatsappsupport::general.update') }}</button>
                                </form>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-4">
                            <div class="white_box_30px">
                                <div class="main-title mb-25">
                                    <h3 class="mb-3">{{ __('whatsappsupport::general.functional_settings') }}</h3>
                                </div>
                                <form action="{{ route('whatsapp-support.settings.update') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" value="{{ $whatappSupportSettings->id }}" name="id">
                                    <div class="row" id="pusher">
                                        <div class="col-xl-6">
                                            <p class="primary_input_label">
                                                {{ __('whatsappsupport::general.agent_type') }}</p>
                                            <div class="d-flex radio-btn-flex">
                                                <div class="mr-20">
                                                    <input type="radio" name="agent_type"
                                                        {{ $whatappSupportSettings->agent_type == 'multi' ? 'checked' : '' }}
                                                        id="relationFather3" value="multi"
                                                        class="common-radio relationButton">
                                                    <label
                                                        for="relationFather3">{{ __('whatsappsupport::general.multi_agent') }}</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="agent_type"
                                                        {{ $whatappSupportSettings->agent_type == 'single' ? 'checked' : '' }}
                                                        id="relationMother4" value="single"
                                                        class="common-radio relationButton">
                                                    <label
                                                        for="relationMother4">{{ __('whatsappsupport::general.single_agent') }}</label>
                                                </div>
                                                @error('agent_type')
                                                    <small class="text-danger font-italic">*{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-xl-6">
                                            <p class="primary_input_label">
                                                {{ __('whatsappsupport::general.availability') }}</p>
                                            <div class="d-flex radio-btn-flex">
                                                <div class="mr-20">
                                                    <input type="radio" name="availability"
                                                        {{ $whatappSupportSettings->availability == 'mobile' ? 'checked' : '' }}
                                                        id="relationFather33333" value="mobile"
                                                        class="common-radio relationButton" checked>
                                                    <label
                                                        for="relationFather33333">{{ __('whatsappsupport::general.only_mobile') }}</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="availability"
                                                        {{ $whatappSupportSettings->availability == 'desktop' ? 'checked' : '' }}
                                                        id="relationMother4433" value="desktop"
                                                        class="common-radio relationButton">
                                                    <label
                                                        for="relationMother4433">{{ __('whatsappsupport::general.only_desktop') }}</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="availability"
                                                        {{ $whatappSupportSettings->availability == 'both' ? 'checked' : '' }}
                                                        id="relationMother4222" value="both"
                                                        class="common-radio relationButton">
                                                    <label
                                                        for="relationMother4222">{{ __('whatsappsupport::general.both') }}</label>
                                                </div>
                                                @error('availability')
                                                    <small class="text-danger font-italic">*{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-xl-6 mt-4">
                                            <p class="primary_input_label">
                                                {{ __('whatsappsupport::general.showing_page') }}</p>
                                            <div class="d-flex radio-btn-flex">
                                                <div class="mr-20">
                                                    <input type="radio" name="showing_page"
                                                        {{ $whatappSupportSettings->showing_page == 'homepage' ? 'checked' : '' }}
                                                        id="relationFather311" value="homepage"
                                                        class="common-radio relationButton" checked>
                                                    <label
                                                        for="relationFather311">{{ __('whatsappsupport::general.only_homepage') }}</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="showing_page"
                                                        {{ $whatappSupportSettings->showing_page == 'all' ? 'checked' : '' }}
                                                        id="relationMother411" value="all"
                                                        class="common-radio relationButton">
                                                    <label
                                                        for="relationMother411">{{ __('whatsappsupport::general.all_page') }}</label>
                                                </div>
                                                @error('showing_page')
                                                    <small class="text-danger font-italic">*{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-xl-6 mt-4">
                                            <p class="primary_input_label">
                                                {{ __('whatsappsupport::general.popup_open_initially') }}</p>
                                            <div class="d-flex radio-btn-flex">
                                                <div class="mr-20">
                                                    <input type="radio" name="open_popup"
                                                        {{ $whatappSupportSettings->open_popup == 1 ? 'checked' : '' }}
                                                        id="relationFather3119" value="1"
                                                        class="common-radio relationButton" checked>
                                                    <label
                                                        for="relationFather3119">{{ __('whatsappsupport::general.yes') }}</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="open_popup"
                                                        {{ $whatappSupportSettings->open_popup == 0 ? 'checked' : '' }}
                                                        id="relationMother4119" value="0"
                                                        class="common-radio relationButton">
                                                    <label
                                                        for="relationMother4119">{{ __('whatsappsupport::general.no') }}</label>
                                                </div>
                                                @error('open_popup')
                                                    <small class="text-danger font-italic">*{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-xl-6 mt-4">
                                            <p class="primary_input_label">
                                                {{ __('whatsappsupport::general.show_unavailable_agent_in_popup') }}
                                            </p>
                                            <div class="d-flex radio-btn-flex">
                                                <div class="mr-20">
                                                    <input type="radio" name="show_unavailable_agent"
                                                        {{ $whatappSupportSettings->show_unavailable_agent == 1 ? 'checked' : '' }}
                                                        id="relationFather33333v" value="1"
                                                        class="common-radio relationButton" checked>
                                                    <label
                                                        for="relationFather33333v">{{ __('whatsappsupport::general.yes') }}</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="show_unavailable_agent"
                                                        {{ $whatappSupportSettings->show_unavailable_agent == 0 ? 'checked' : '' }}
                                                        id="relationMother4433v" value="0"
                                                        class="common-radio relationButton">
                                                    <label
                                                        for="relationMother4433v">{{ __('whatsappsupport::general.no') }}</label>
                                                </div>
                                                @error('show_unavailable_agent')
                                                    <small class="text-danger font-italic">*{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- <div class="col-xl-6 mt-4">
                                            <p class="primary_input_label">
                                                {{ __('whatsappsupport::general.disable_for_admin_panel') }}</p>
                                            <div class="d-flex radio-btn-flex">
                                                <div class="mr-20">
                                                    <input type="radio" name="disable_for_admin_panel"
                                                        {{ $whatappSupportSettings->disable_for_admin_panel == 1 ? 'checked' : '' }}
                                                        id="relationv" value="1"
                                                        class="common-radio relationButton" checked>
                                                    <label
                                                        for="relationv">{{ __('whatsappsupport::general.yes') }}</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="disable_for_admin_panel"
                                                        {{ $whatappSupportSettings->disable_for_admin_panel == 0 ? 'checked' : '' }}
                                                        id="relation3v" value="0"
                                                        class="common-radio relationButton">
                                                    <label
                                                        for="relation3v">{{ __('whatsappsupport::general.no') }}</label>
                                                </div>
                                                @error('disable_for_admin_panel')
                                                    <small class="text-danger font-italic">*{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div> --}}

                                        <div class="col-xl-6 mt-4">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                    for="">{{ __('whatsappsupport::general.homepage_url') }}</label>
                                                <input class="primary_input_field" name="homepage_url" type="text"
                                                    value="{{ $whatappSupportSettings->homepage_url ?? '' }}">
                                            </div>
                                        </div>

                                        <div class="col-xl-6 mt-4">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                    for="">{{ __('whatsappsupport::general.primary_number') }}
                                                    (<small
                                                        class="text-danger">{{ __('whatsappsupport::general.with_country_code') }}</small>)</label>
                                                <input class="primary_input_field" name="primary_number"
                                                    type="text"
                                                    value="{{ $whatappSupportSettings->primary_number ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-xl-6 mt-4">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">
                                                    {{ __('whatsappsupport::general.whatsapp bubble logo') }}
                                                </label>
                                                <div class="primary_file_uploader">
                                                    <input class="primary_input_field form-control" type="text"
                                                        id="placeholderInput" name="bubble_logo"
                                                        placeholder="{{ isset($whatappSupportSettings) ? (@$whatappSupportSettings->bubble_logo != '' ? getFilePath3(@$whatappSupportSettings->bubble_logo) : trans('common.image')) : trans('common.image') }}"
                                                        readonly>
                                                    <button class="" type="button">
                                                        <label class="primary-btn small fix-gr-bg" for="browseFile">
                                                            {{ __('whatsappsupport::general.Browse') }} </label>
                                                        <input type="file" class="d-none"
                                                            name="bubble_logo" id="browseFile">
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="primary-btn radius_30px fix-gr-bg mt-4"><i
                                            class="ti-check"></i>{{ __('whatsappsupport::general.update') }}</button>
                                </form>

                            </div>
                        </div>

                        <div class="col-lg-12 mt-4">
                            <div class="white_box_30px">
                                <div class="main-title mb-25">
                                    <h3 class="mb-0">{{ __('whatsappsupport::general.layout_settings') }}</h3>
                                </div>
                                <form action="{{ route('whatsapp-support.settings.update') }}" method="post">
                                    @csrf
                                    <input type="hidden" value="{{ $whatappSupportSettings->id }}" name="id">
                                    <div class="col-xl-12">
                                        <p class="primary_input_label mb-3">
                                            {{ __('whatsappsupport::general.choose_layout') }}</p>
                                        <div class="d-flex radio-btn-flex flex-wrap row-gap-24">
                                            <div class="mr-20">
                                                <input type="radio" name="layout"
                                                    {{ $whatappSupportSettings->layout == 1 ? 'checked' : '' }}
                                                    id="relationFather113" value="1"
                                                    class="common-radio relationButton">
                                                <label for="relationFather113"><img style="border-radius: 10px"
                                                        src="{{ asset('public\whatsapp-support\preview-1.png') }}"
                                                        alt=""></label>
                                            </div>
                                            <div class="mr-20">
                                                <input type="radio" name="layout"
                                                    {{ $whatappSupportSettings->layout == 2 ? 'checked' : '' }}
                                                    id="relationMother1114" value="2"
                                                    class="common-radio relationButton">
                                                <label for="relationMother1114"><img style="border-radius: 10px"
                                                        src="{{ asset('public\whatsapp-support\preview-2.png') }}"
                                                        alt=""></label>
                                            </div>
                                            @error('layout')
                                                <small class="text-danger font-italic">*{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <button class="primary-btn radius_30px mt-4 fix-gr-bg"><i
                                            class="ti-check"></i>{{ __('whatsappsupport::general.update') }}</button>
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
