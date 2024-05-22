@extends('backEnd.master')
@section('title')
    @lang('common.virtual_class_reports')
@endsection

@section('css')
    <style>
        .propertiesname {
            text-transform: uppercase;
        }

        . .recurrence-section-hide {
            display: none !important
        }
    </style>
@endsection

@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('common.virtual_class_reports') </h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('common.virtual_class')</a>
                    <a href="#"> @lang('reports.reports') </a>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-10">
                    <h3 class="mb-30">
                        @lang('common.virtual_class_reports')
                    </h3>
                </div>
            </div>
            <div class="row mb-20">
                <div class="col-lg-12">
                    <div class="white-box">
                        <form action="{{ route('zoom.virtual.class.reports.show') }}" method="GET">
                            <div class="row">
                                <div class="col-lg-2 mt-30-md">
                                    <label class="primary_input_label" for="">
                                        {{ __('common.class') }}
                                        <span class="text-danger"> *</span>
                                    </label>
                                    <select class="primary_select  {{ $errors->has('class_id') ? ' is-invalid' : '' }}"
                                        id="select_class" name="class_id">
                                        <option data-display="@lang('common.select_class')" value="">@lang('common.select_class')</option>
                                        @foreach ($classes as $class)
                                            @if (isset($class_id))
                                                <option value="{{ $class->id }}"
                                                    {{ $class_id == $class->id ? 'selected' : '' }}>{{ $class->class_name }}
                                                </option>
                                            @else
                                                <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2 mt-30-md" id="select_section_div">
                                    <label class="primary_input_label" for="">
                                        {{ __('common.section') }}
                                        <span class="text-danger"> </span>
                                    </label>
                                    <select class="primary_select {{ $errors->has('section_id') ? ' is-invalid' : '' }}"
                                        id="select_section" name="section_id">
                                        <option data-display="@lang('common.select_section')" value="">@lang('common.select_section')</option>
                                    </select>
                                    <div class="pull-right loader loader_style" id="select_section_loader">
                                        <img class="loader_img_style" src="{{ asset('public/backEnd/img/demo_wait.gif') }}"
                                            alt="loader">
                                    </div>
                                </div>
                                @if (Auth::user()->role_id == 1)
                                    <div class="col-lg-2 mt-30-md">
                                        <label class="primary_input_label" for="">
                                            {{ __('common.teachers') }}
                                            <span class="text-danger"> </span>
                                        </label>
                                        <select
                                            class="primary_select {{ $errors->has('section_id') ? ' is-invalid' : '' }}"
                                            name="teachser_ids">
                                            <option data-display="@lang('common.select_teacher')" value="">@lang('common.select_teacher')
                                            </option>
                                            @foreach ($teachers as $teacher)
                                                <option value="{{ $teacher->id }}"
                                                    {{ isset($teachser_ids) == $teacher->id ? 'selected' : '' }}>
                                                    {{ $teacher->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="col-lg-3 mt-30-md">
                                    <div class="primary_input">
                                        <label class="primary_input_label"
                                            for="">@lang('common.from_date')<span></span></label>
                                        <div class="primary_datepicker_input">
                                            <div class="no-gutters input-right-icon">
                                                <div class="col">
                                                    <div class="">
                                                        <input data-display="@lang('common.from_date')"
                                                            placeholder="@lang('common.from_date')"
                                                            class="primary_input_field  primary_input_field date form-control form-control"
                                                            id="from_time" type="text" name="from_time"
                                                            value="{{ isset($from_time) ? Carbon\Carbon::parse($from_time)->format('m/d/Y') : '' }}">
                                                    </div>
                                                </div>
                                                <button class="btn-date" data-id="#from_time" type="button">
                                                    <label class="m-0 p-0" for="from_time">
                                                        <i class="ti-calendar" id="start-date-icon"></i>
                                                    </label>
                                                </button>
                                            </div>
                                        </div>
                                        <span class="text-danger">{{ $errors->first('from_time') }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-3 mt-30-md">
                                    <div class="primary_input">
                                        <label class="primary_input_label"
                                            for="">@lang('common.to_date')<span></span></label>
                                        <div class="primary_datepicker_input">
                                            <div class="no-gutters input-right-icon">
                                                <div class="col">
                                                    <div class="">
                                                        <input data-display="@lang('common.to_date')"
                                                            placeholder="@lang('common.to_date')"
                                                            class="primary_input_field  primary_input_field date form-control"
                                                            id="to_time" type="text" name="to_time"
                                                            value="{{ isset($to_time) ? Carbon\Carbon::parse($to_time)->format('m/d/Y') : '' }}">
                                                    </div>
                                                </div>
                                                <button class="btn-date" data-id="#to_time" type="button">
                                                    <label class="m-0 p-0" for="to_time">
                                                        <i class="ti-calendar" id="start-date-icon"></i>
                                                    </label>
                                                </button>
                                            </div>
                                        </div>
                                        <span class="text-danger">{{ $errors->first('to_time') }}</span>
                                    </div>
                                </div>
                                @php
                                    $tooltip = '';
                                    if (userPermission('zoom.virtual.class.reports.show')) {
                                        $tooltip = '';
                                    } else {
                                        $tooltip = 'You have no permission to search';
                                    }
                                @endphp
                                <div class="col-lg-12 mt-20 text-right">
                                    <button type="submit" class="primary-btn small fix-gr-bg" data-toggle="tooltip"
                                        title="{{ $tooltip }}">
                                        <span class="ti-search pr-2"></span>
                                        @lang('common.search')
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area" style="display:  {{ isset($meetings) ? 'block' : 'none' }} ">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">                
                    <x-table>
                        <table id="default_table2" class="table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 4)
                                        <th>@lang('common.class')</th>
                                        <th>@lang('common.class_section')</th>
                                    @endif
                                    <th>@lang('common.meeting_id')</th>
                                    <th>@lang('common.password')</th>
                                    <th>@lang('common.topic')</th>
                                    @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 4)
                                        <th>@lang('common.teachers')</th>
                                    @endif
                                    <th>@lang('common.date')</th>
                                    <th>@lang('common.time')</th>
                                    <th>@lang('common.duration')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if (isset($meetings))
                                    @foreach ($meetings as $key => $meeting)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 4)
                                                <td>{{ $meeting->class->class_name }}</td>
                                                <td>{{ $meeting->section->section_name }}</td>
                                            @endif
                                            <td>{{ $meeting->meeting_id }}</td>
                                            <td>{{ $meeting->password }}</td>
                                            <td>{{ $meeting->topic }} </td>
                                            @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 4)
                                                <td>{{ $meeting->teachersName }}</td>
                                            @endif
                                            <td>{{ $meeting->date_of_meeting }}</td>
                                            <td>{{ $meeting->time_of_meeting }}</td>
                                            <td>{{ $meeting->meeting_duration }} Min</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </x-table>
                </div>
            </div>
        </div>
    </section>
@endsection
@include('backEnd.partials.date_picker_css_js')
