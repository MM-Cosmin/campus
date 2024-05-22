@extends('backEnd.master')
@section('title')
    {{ __('aicontent::aicontent.settings') }}
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>{{ __('aicontent::aicontent.settings') }}</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">{{ __('aicontent::aicontent.ai_content') }}</a>
                    <a href="{{ route('ai-content.settings') }}">{{ __('aicontent::aicontent.settings') }}</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="white-box">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="box_header">
                        <div class="main-title d-flex">
                            <h3 class="mb-0 mr-30">{{ __('aicontent::aicontent.settings') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="tab-content " id="myTabContent">
                                <div class="tab-pane fade show active" id="Activation" role="tabpanel"
                                    aria-labelledby="Activation-tab">
                                    <div class="main-title mb-25">
                                        <div class="main-title mb-25">
                                            <h3 class="mb-0">{{ __('aicontent::aicontent.open_ai_setup') }}</h3>
                                        </div>
                                        @if (userPermission('ai-content.update_settings'))
                                            <form action="{{ route('ai-content.settings-update') }}" method="POST"
                                                enctype="multipart/form-data">
                                                <input type="hidden" value="{{ $data['settings']->id }}" name="id">
                                        @endif
                                        @csrf
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="primary_input mb-25">
                                                    <label class="primary_input_label"
                                                        for="">{{ __('aicontent::aicontent.open_ai_model') }}</label>
                                                    <select class="primary_select mb-25" name="ai_default_model"
                                                        id="ai_default_model">
                                                        @foreach ($data['ai_models'] as $key => $ai_model)
                                                            <option value="{{ $key }}"
                                                                {{ $data['settings']->ai_default_model == $key ? 'selected' : '' }}>
                                                                {{ $ai_model }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="primary_input mb-25">
                                                    <label class="primary_input_label"
                                                        for="">{{ __('aicontent::aicontent.ai_default_language') }}</label>
                                                    <select class="primary_select mb-25"
                                                        name="ai_default_language"
                                                        id="ai_default_language">
                                                        @foreach ($data['languages'] as $key => $language)
                                                            <option value="{{ $key }}"
                                                                {{ $data['settings']->ai_default_language == $key ? 'selected' : '' }}>
                                                                {{ $language }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="primary_input mb-25">
                                                    <label class="primary_input_label"
                                                        for="">{{ __('aicontent::aicontent.ai_tones') }}</label>
                                                    <select class="primary_select mb-25" name="ai_default_tone"
                                                        id="ai_default_tone">
                                                        @foreach ($data['ai_tones'] as $key => $ai_model)
                                                            <option value="{{ $key }}"
                                                                {{ $data['settings']->ai_default_tone == $key ? 'selected' : '' }}>
                                                                {{ $ai_model }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="primary_input mb-25">
                                                    <label class="primary_input_label"
                                                        for="">{{ __('aicontent::aicontent.max_result_length') }}</label>
                                                    <input class="primary_input_field" placeholder="200"
                                                        type="number"
                                                        id="ai_max_result_length" name="ai_max_result_length"
                                                        value="{{ $data['settings']->ai_max_result_length ?? 200 }}">
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="primary_input mb-25">
                                                    <label class="primary_input_label"
                                                        for="">{{ __('aicontent::aicontent.ai_creativity') }}</label>
                                                    <select class="primary_select mb-25"
                                                        name="ai_default_creativity"
                                                        id="ai_default_creativity">
                                                        @foreach ($data['ai_creativity'] as $key => $ai_creativity)
                                                            <option value="{{ $key }}"
                                                                {{ $data['settings']->ai_default_creativity == $key ? 'selected' : '' }}>
                                                                {{ $ai_creativity }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-12">
                                                <div class="primary_input mb-25">
                                                    <label class="primary_input_label" for="">
                                                        {{ __('aicontent::aicontent.open_ai_secrete_key') }}
                                                        (<a href="https://platform.openai.com/account/api-keys" target="_blank">
                                                            {{ __('aicontent::aicontent.click_here') }}
                                                        </a>
                                                        <span>
                                                            {{ __('aicontent::aicontent.for_your_api_key') }}
                                                        </span>)
                                                    </label>
                                                    <input class="primary_input_field" placeholder="sk-"
                                                        type="text"
                                                        id="open_ai_secrete_key" name="open_ai_secrete_key"
                                                        value="{{ $data['settings']->open_ai_secret_key }}">
                                                </div>
                                            </div>
                                        </div>
                                        @php
                                            $tooltip = '';
                                            if (userPermission('settings.general_setting_update')) {
                                                $tooltip = '';
                                            } else {
                                                $tooltip = 'You have no permission to add';
                                            }
                                        @endphp
                                        <div class="submit_btn text-center mt-4">
                                            <button class="primary_btn_large" type="submit"
                                                data-toggle="tooltip"
                                                title="{{ $tooltip }}"><i class="ti-check"></i>
                                                {{ __('aicontent::aicontent.save') }}</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </section>
@endsection
