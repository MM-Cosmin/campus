@extends('backEnd.master')
@section('title')
    {{ __('aicontent::aicontent.content_list') }}
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/AiContent/Resources/assets/css/ai_content.css') }}">
    <link rel="stylesheet" href="{{ asset('public/backEnd/vendors/editor/summernote-bs4.css') }}">

    <style>
        .output-column{
            min-width: 300px;
        }
    </style>
@endpush
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>{{ __('aicontent::aicontent.generated_content_list') }}</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">{{ __('aicontent::aicontent.ai_content') }}</a>
                    <a href="{{ route('ai-content.content') }}">{{ __('aicontent::aicontent.generated_content_list') }}</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="white-box">
            <div class="row">
                <div class="col-lg-12">
                    <div class="box_header common_table_header">
                        <div class="main-title d-flex flex-wrap mb-0">
                            <h3 class="mb-0">{{ __('aicontent::aicontent.content_list') }}</h3>
                        </div>
                    </div>
                    <div class="">
                        <x-table>
                            <table id="table_id" class="table Crm_table_active3" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ __('aicontent::aicontent.sl') }}</th>
                                        <th scope="col">{{ __('aicontent::aicontent.template') }}</th>
                                        <th scope="col">{{ __('aicontent::aicontent.input') }}</th>
                                        <th scope="col">{{ __('aicontent::aicontent.output') }}</th>
                                        <th scope="col">{{ __('aicontent::aicontent.model') }}</th>
                                        <th scope="col">{{ __('aicontent::aicontent.tokens') }}</th>
                                        <th scope="col">{{ __('aicontent::aicontent.words') }}</th>
                                        <th scope="col">{{ __('aicontent::aicontent.temperature') }}</th>
                                        <th scope="col">{{ __('aicontent::aicontent.lang') }}</th>
                                        <th scope="col">{{ __('aicontent::aicontent.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($contents)
                                        @foreach ($contents as $key => $content)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $content->template->name }}</td>
                                                <td>{{ $content->input_text }}</td>
                                                
                                                <td class="output-column">{!! mb_strimwidth($content->output_text, 0, 200, "...") !!}</td>
                                                <td>
                                                    {{ $openAiModels[$content->model] }}
                                                </td>
                                                <td>{{ $content->tokens }}</td>
                                                <td>{{ $content->words }}</td>
                                                <td>
                                                    {{ $creativities[$content->temperature] }}
                                                </td>
                                                <td>{{ $content->lang }}</td>
                                                <td>
                                                    <div class="dropdown CRM_dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                                            id="dropdownMenu1{{ @$category->id }}"
                                                            data-toggle="dropdown"
                                                            aria-haspopup="true"
                                                            aria-expanded="false">
                                                            {{ __('aicontent::aicontent.select') }}
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right"
                                                            aria-labelledby="dropdownMenu1{{ @$content->id }}">
                                                            <a class="dropdown-item copy_output" href="#"
                                                                data-output="{{ $content->output_text }}">{{ __('aicontent::aicontent.copy') }}</a>
                                                            <a class="dropdown-item" data-toggle="modal"
                                                                data-target="#edit_output_modal{{ @$content->id }}"
                                                                href="#">@lang('aicontent::aicontent.edit')</a>
                                                            <a class="dropdown-item" data-toggle="modal"
                                                                data-target="#deleteContentModal{{ @$content->id }}"
                                                                href="#">@lang('aicontent::aicontent.delete')</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <div class="modal fade admin-query" id="edit_output_modal{{ @$content->id }}">
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">
                                                                {{ __('aicontent::aicontent.Update AI response') }}</h4>
                                                            <button type="button" class="close" data-dismiss="modal">
                                                                <i class="ti-close "></i>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('ai-content.update') }}" method="post">
                                                                @csrf
                                                                <input type="hidden" name="id" id="ai_output_id"
                                                                    value="">
                                                                <div class="row">
                                                                    <div class="col-xl-12">
                                                                        <div class="primary_input mb-35">
                                                                            <label class="primary_input_label"
                                                                                for="">{{ __('aicontent::aicontent.AI Output') }}
                                                                                <span
                                                                                    class="text-danger">*</span> </label>
                                                                            <textarea class="summer_note" name="output" id="ai_output" cols="30"
                                                                                rows="10">{!! @$content->output_text !!}</textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 text-center pt_15">
                                                                        <div class="d-flex justify-content-center">
                                                                            <button class="primary-btn semi_large2  fix-gr-bg"
                                                                                type="submit"><i
                                                                                    class="ti-check"></i>
                                                                                {{ __('aicontent::aicontent.Update') }}
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal fade admin-query"
                                                id="deleteContentModal{{ @$content->id }}">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">
                                                                @lang('downloadCenter.delete_content')
                                                            </h4>
                                                            <button type="button" class="close" data-dismiss="modal">
                                                                &times;
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="text-center">
                                                                <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                            </div>
                                                            <div class="mt-40 d-flex justify-content-between">
                                                                <button type="button" class="primary-btn tr-bg"
                                                                    data-dismiss="modal">
                                                                    @lang('common.cancel')
                                                                </button>
                                                                <a href="{{ route('ai-content.delete', [@$content->id]) }}"
                                                                    class="text-light">
                                                                    <button class="primary-btn fix-gr-bg" type="submit">
                                                                        @lang('common.delete')
                                                                    </button>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endisset
                                </tbody>
                            </table>
                        </x-table>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </section>
@endsection
@include('backEnd.partials.data_table_js')
@push('scripts')
    <script src="{{ asset('Modules/AiContent/Resources/assets/js/ai_content.js') }}"></script>
    <script src="{{ asset('public/backEnd/') }}/vendors/editor/summernote-bs4.js"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.copy_output', function(e) {
                e.preventDefault();
                var output = $(this).data('output');
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(output).select();
                document.execCommand("copy");
                $temp.remove();
                toastr.success('Copied');
            });
        });
    </script>
@endpush
