@extends('backEnd.master')
@section('title')
    @lang('system_settings.manage_currency')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('system_settings.currency')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('system_settings.currency')</a>
                    <a href="#">@lang('system_settings.manage_currency')</a>

                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            @if (isset($edit_languages))
                <div class="row">
                    <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                        <a href="{{ route('marks-grade') }}" class="primary-btn small fix-gr-bg">
                            <span class="ti-plus pr-2"></span>
                            @lang('common.add')
                        </a>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h3 class="mb-30">
                                    @if (isset($edit_languages))
                                        @lang('system_settings.edit_currency')
                                    @else
                                        @lang('system_settings.add_currency')
                                    @endif
                                </h3>
                            </div>
                            @if (isset($editData))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'administrator/currency-update', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                <input type="hidden" name="id" value="{{ isset($editData) ? @$editData->id : '' }}">
                            @else
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'currency-store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @endif
                            <div class="white-box">
                                <div class="add-visitor">
                                    


                                    <div class="row ">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label>@lang('common.name') <span>*</span></label>
                                                <input
                                                    class="primary_input_field{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                    type="text" name="name" autocomplete="off"
                                                    value="{{ isset($editData) ? @$editData->name : '' }}" maxlength="25"
                                                    required>
                                               
                                                
                                                @if ($errors->has('name'))
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $errors->first('name') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-15">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label>@lang('system_settings.code') <span>*</span></label>
                                                <input
                                                    class="primary_input_field{{ $errors->has('code') ? ' is-invalid' : '' }}"
                                                    type="text" name="code" autocomplete="off"
                                                    value="{{ isset($editData) ? @$editData->code : '' }}" maxlength="10"
                                                    required>
                                                
                                                
                                                @if ($errors->has('code'))
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $errors->first('code') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-15">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label>@lang('system_settings.symbol') <span>*</span></label>
                                                <input
                                                    class="primary_input_field{{ $errors->has('symbol') ? ' is-invalid' : '' }}"
                                                    type="text" name="symbol" autocomplete="off"
                                                    value="{{ isset($editData) ? @$editData->symbol : '' }}" maxlength="5"
                                                    required>
                                                
                                                
                                                @if ($errors->has('symbol'))
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $errors->first('symbol') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg submit">
                                                <span class="ti-check"></span>
                                                @if (isset($editData))
                                                    @lang('system_settings.update_currency')
                                                @else
                                                    @lang('system_settings.save_currency')
                                                @endif

                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-30">@lang('system_settings.currency_list')</h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                                <table id="table_id" class="table Crm_table_active3" cellspacing="0" width="100%">


                                    <thead>

                                        <tr>
                                            <th>@lang('common.sl')</th>
                                            <th>@lang('common.name')</th>
                                            <th>@lang('system_settings.code')</th>
                                            <th>@lang('system_settings.symbol')</th>
                                            <th>@lang('common.action')</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php $i=1;  @endphp

                                        @foreach ($currencies as $value)
                                            <tr>
                                                <td>{{ $i++ }}
                                                <td>{{ $value->name }}</td>
                                                <td>{{ $value->code }}</td>
                                                <td>{{ $value->symbol }}</td>
                                                <td>

                                                    <x-drop-down>
                                                        <a class="dropdown-item"
                                                            href="{{ route('currency_edit', [@$value->id]) }}">@lang('common.edit')</a>

                                                        <a class="dropdown-item" data-toggle="modal"
                                                            data-target="#deleteCurrency{{ @$value->id }}"
                                                            href="{{ route('currency_delete', [@$value->id]) }}">@lang('common.delete')</a>
                                                    </x-drop-down>
                                                </td>

                                                <div class="modal fade admin-query" id="deleteCurrency{{ @$value->id }}">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">@lang('system_settings.delete_currency')</h4>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="text-center">
                                                                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                                </div>
                                                                <div class="mt-15 d-flex justify-content-between">
                                                                    <button type="button" class="primary-btn tr-bg"
                                                                        data-dismiss="modal">@lang('common.cancel')</button>
                                                                    <a href="{{ url('manage-currency/delete', [@$value->id]) }}"
                                                                        class="text-light">
                                                                        <button class="primary-btn fix-gr-bg"
                                                                            type="submit">@lang('common.delete')</button>
                                                                    </a>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </tr>
                                        @endforeach
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