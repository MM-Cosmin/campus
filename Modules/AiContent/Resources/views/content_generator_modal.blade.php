<link rel="stylesheet" href="{{ asset('public\backEnd\vendors\editor\summernote-bs4.css') }}">
<link rel="stylesheet" href="{{ asset('Modules\AiContent\Resources\assets\css\ai_content.css') }}">
@php
    $data['title'] = 'AI Content Settings';
    $data['ai_models'] = Modules\AiContent\Entities\AiModels::OPEN_AI_MODELS;
    $data['ai_tones'] = Modules\AiContent\Entities\AiModels::AI_TONES;
    $data['ai_creativity'] = Modules\AiContent\Entities\AiModels::AI_CREATIVITY;
    $data['languages'] = Modules\AiContent\Entities\AiModels::SUPPORTED_LANGUAGES;
    $data['settings'] = Modules\AiContent\Entities\AiContentSetting::first();
    $data['templates'] = Modules\AiContent\Entities\AiTemplate::get();
@endphp
<div class="modal fade admin-query" id="ai_text_generation_modal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ __('aicontent::aicontent.ai_text_generation') }}</h4>
                <button type="button" class="close " data-dismiss="modal">
                    <i class="ti-close "></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('ai-content.generate_text') }}" method="POST" id="text_generator_form"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="primary_input mb-35">
                                <label class="primary_input_label"
                                    for="">{{ __('aicontent::aicontent.language') }}
                                    <strong class="text-danger">*</strong>
                                </label>
                                <select class="primary_select" name="language" id="language">
                                    <option
                                        data-display="{{ __('aicontent::aicontent.select') }} {{ __('aicontent::aicontent.language') }}"
                                        value="">{{ __('aicontent::aicontent.select') }}
                                        {{ __('aicontent::aicontent.language') }} </option>
                                    @foreach ($data['languages'] as $key => $language)
                                        <option value="{{ $key }}"
                                            {{ $data['settings']->ai_default_language == $key ? 'selected' : '' }}>
                                            {{ $language }}</option>
                                    @endforeach
                                </select>
                                <small class="text-danger" id="language_error_message"></small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="primary_input mb-35">
                                <label class="primary_input_label"
                                    for="">{{ __('aicontent::aicontent.template') }}
                                    <strong class="text-danger">*</strong>
                                </label>
                                <select class="primary_select" name="template_id" id="ai_template">
                                    <option
                                        data-display="{{ __('aicontent::aicontent.select') }} {{ __('aicontent::aicontent.template') }}"
                                        value="">{{ __('aicontent::aicontent.select') }}
                                        {{ __('aicontent::aicontent.template') }}
                                    </option>
                                    @foreach ($data['templates'] as $key => $template)
                                        <option value="{{ $template->id }}">{{ $template->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-danger" id="template_error_message"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row collapse mt-20" id="AdvanceOption">
                        <div class="col-lg-6">
                            <div class="primary_input mb-35">
                                <label class="primary_input_label"
                                    for="">{{ __('aicontent::aicontent.tone') }}<strong
                                        class="text-danger">*</strong></label>
                                <select class="primary_select" name="tone" id="tone">
                                    <option
                                        data-display="{{ __('aicontent::aicontent.select') }} {{ __('aicontent::aicontent.tone') }}"
                                        value="">{{ __('aicontent::aicontent.select') }}
                                        {{ __('aicontent::aicontent.tone') }}
                                    </option>
                                    @foreach ($data['ai_tones'] as $key => $ai_model)
                                        <option value="{{ $key }}"
                                            {{ $data['settings']->ai_default_tone == $key ? 'selected' : '' }}>
                                            {{ $ai_model }}</option>
                                    @endforeach
                                </select>
                                <small class="text-danger" id="tone_error_message"></small>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label"
                                    for="">{{ __('aicontent::aicontent.ai_creativity') }}</label>
                                <select class="primary_select mb-25" name="creativity" id="creativity">
                                    <option
                                        data-display="{{ __('aicontent::aicontent.select') }} {{ __('aicontent::aicontent.creativity') }}"
                                        value="">{{ __('aicontent::aicontent.select') }}
                                        {{ __('aicontent::aicontent.creativity') }}
                                    </option>
                                    @foreach ($data['ai_creativity'] as $key => $ai_creativity)
                                        <option value="{{ $key }}"
                                            {{ $data['settings']->ai_default_creativity == $key ? 'selected' : '' }}>
                                            {{ $ai_creativity }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label"
                                    for="">{{ __('aicontent::aicontent.number_of_results') }}</label>
                                <select class="primary_select mb-25" name="number_of_result" id="number_of_result">
                                    @for ($i = 1; $i < 11; $i++)
                                        <option>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label"
                                    for="">{{ __('aicontent::aicontent.max_result_length') }}
                                    <strong class="text-danger">*</strong></label>
                                <input class="primary_input_field" name="max_result_length" id="max_result_length"
                                    placeholder="{{ __('aicontent::aicontent.max_result') }}"
                                    value="{{ $data['settings']->ai_max_result_length }}" min="10"
                                    max="{{ $data['settings']->ai_max_result_length }}" type="number">
                                <small class="text-danger" id="max_result_error_message"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label"
                                    for="">{{ __('aicontent::aicontent.keyword') }}
                                    <strong class="text-danger">*</strong></label>
                                <input class="primary_input_field" name="keyword" id="keyword"
                                    placeholder="{{ __('aicontent::aicontent.keyword') }}" value=""
                                    type="text">
                                <small>{{ __('aicontent::aicontent.you_can_add_multiple_keywords') }}</small>
                                <small class="text-danger" id="keyword_error_message"></small>
                            </div>
                        </div>
                        <div class="col-lg-12 d-none" id="titleDiv">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label"
                                    for="">{{ __('aicontent::aicontent.title') }}
                                    <strong class="text-danger">*</strong></label>
                                <input class="primary_input_field" name="title" id="title"
                                    placeholder="{{ __('aicontent::aicontent.title') }}" value=""
                                    type="text">
                                <small class="text-danger" id="title_error_message"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 d-flex justify-content-end">
                            {{ __('aicontent::aicontent.show_advanced_options') }}
                            <button class="primary-btn radius_30px ml-10 fix-gr-bg extraBtn"
                                id="ai_advance_section_collapse" type="button" data-toggle="collapse"
                                data-target="#AdvanceOption" aria-expanded="false" aria-controls="AdvanceOption">
                                <i class="fas fa-angle-down" id="ai_advance_icon"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-12 text-center pt_15">
                        <div class="d-flex justify-content-center">
                            <button class="primary-btn semi_large2  fix-gr-bg" id="generate_content" type="button">
                                <i class="fas fa-robot" id="show_ai_icon"></i>
                                {{ __('aicontent::aicontent.generate') }}
                            </button>
                        </div>
                    </div>
                </form>
                <div class="row align-center">
                    <div class="col-lg-12">
                        <span class="text-danger" id="error_message">
                        </span>
                    </div>
                    <div class="col-lg-12" id="generation_result" style="display: block">
                        <div class="primary_input mb-25">
                            <div class="" id="generated_result">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('public/backEnd/vendors/editor/summernote-bs4.js') }}"></script>
    <script src="{{ asset('Modules/AiContent/Resources/assets/js/ai_content.js') }}"></script>
    <script>
        $(document).on('click', '#show_ai_text_generator', function() {
            var selected_template = $(this).data('selected_template');
            var ai_template = $('#ai_template');
            if (selected_template) {
                ai_template.val(selected_template);
                $('#ai_template').niceSelect('update');
            }
            $("#ai_text_generation_modal").modal('show');
        });

        $(document).on('change', '#ai_template', function(e) {
            console.log($(this).val());
            let templateId = $(this).val();
            if (templateId == 1 || templateId == 11) {
                $('#titleDiv').addClass('d-none');
            } else {
                $('#titleDiv').removeClass('d-none');
            }
        });
    </script>
@endpush
