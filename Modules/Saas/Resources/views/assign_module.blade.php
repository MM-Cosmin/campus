@extends('backEnd.master')
@section('title')
    @lang('saas::saas.assign_module_and_menu')
@endsection

@section('mainContent')
    <section class="sms-breadcrumb mb-40 up_breadcrumb white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('saas::saas.assign_module_and_menu') </h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('saas::saas.subscription')</a>
                    <a href="#">@lang('saas::saas.assign_module_and_menu')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('common.select_institution')</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'subscription/assign-module', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            <div class="col-lg-12">
                                <select class="primary_select form-control{{ $errors->has('institution') ? ' is-invalid' : '' }}"
                                        name="institution" id="select-institution">
                                    <option data-display="@lang('common.select_institution')"
                                            value="">@lang('common.select_institution') *
                                    </option>
                                    @php
                                        $s_id = isset($school) ? $school->id : old('institution');
                                        @endphp
                                    @foreach($schools as $s)
                                        <option value="{{ $s->id }}"
                                                @if($s_id == $s->id) selected @endif >{{ $s->school_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('institution'))
                                    <span class="text-danger" role="alert">
                                        {{ $errors->first('institution') }}
                                    </span>
                                @endif
                            </div>

                        </div>
                        <div class="col-lg-12 mt-20 text-right">
                            <button type="submit" class="primary-btn small fix-gr-bg">
                                <span class="ti-search pr-2"></span>
                                @lang('common.search')
                            </button>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>

        @if(isset($school))
            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="white-box">
                        <div class="row">
                            <div class="col-lg-6 no-gutters">
                                <div class="main-title">
                                    <h3 class="mb-0">@lang('saas::saas.assign_module_and_menu_for') {{ $school->school_name }}</h3>
                                </div>
                            </div>
                        </div>

                        <!-- </div> -->
                        <div class="row">
                            <div class="col-lg-12 ">
                                <div class="container-fluid">
                                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => ['subscription/add-module', $school->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <input type="hidden" name="school_id" value="{{$school->id}}">
                                            <div class="row mt-30">
                                                @php
                                                    $package = getschoolModule($school) ?? activePackage($school);
                                                @endphp
                                                @foreach($permissions as $hook => $values)
                                                    @if($value_count = count($values))
                                                        <div class="col-12 mt-3">
                                                            <div class="d-flex justify-content-between">
                                                                <h3>{{ __('saas::saas.'.$hook.'_permission') }}</h3>
                                                                @php
                                                                    $checked_all = $package && $package->$hook && count($package->$hook) == $value_count;
                                                                @endphp
                                                                <div class="mt-2">
                                                                    <input type="checkbox" id="select_all_{{$hook}}"
                                                                           value="{{$hook}}"
                                                                           class="common-checkbox common-radio relationButton select_all" {{ $checked_all ? 'checked' : '' }} >
                                                                    <label for="select_all_{{$hook}}">@lang('common.select_all')</label>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    @endif
                                                    @foreach($values as $key => $value)
                                                        <div class="col-lg-6 mt-3">
                                                            <div class="">
                                                                @php
                                                                    $checked = $package && $package->$hook && in_array($key, $package->$hook);
                                                                @endphp
                                                                <input type="checkbox" name="{{ $hook }}[]"
                                                                       id="{{$hook}}_{{$key}}" value="{{$key}}"
                                                                       class="common-checkbox common-radio relationButton {{ $hook.'_checkbox' }}" {{ $checked ? 'checked' : '' }} >
                                                                <label for="{{$hook}}_{{$key}}">@lang($value)</label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-lg-12 text-center mt-40">
                                            <div class="mt-40 d-flex justify-content-center">
                                                <button class="primary-btn fix-gr-bg submit"
                                                        type="submit">@lang('common.save_information')</button>
                                            </div>
                                        </div>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </section>

@endsection

@push('script')
    <script>
        $(document).on('change', '.select_all', function(){
            let hook = $(this).val();
            $('.'+hook+'_checkbox').prop('checked', $(this).is(':checked'));
        });

        $(document).on('change', '.menus_checkbox', function(){
            let total = $('.menus_checkbox').length;
            let checked = $('.menus_checkbox:checked').length;

            console.log('changed');

            $("input[value='menus']").prop('checked', total === checked);
        });

        $(document).on('change', '.modules_checkbox', function(){
            let total = $('.modules_checkbox').length;
            let checked = $('.modules_checkbox:checked').length;

            $("input[value='modules']").prop('checked', total === checked);
        });

        $(document).ready(function(){
            let total = $('.modules_checkbox').length;
            let checked = $('.modules_checkbox:checked').length;

            $("input[value='modules']").prop('checked', total === checked);

            total = $('.menus_checkbox').length;
            checked = $('.menus_checkbox:checked').length;
            $("input[value='menus']").prop('checked', total === checked);
        })
    </script>
@endpush
