@extends('backEnd.master')
@section('title')
    @lang('common.meetings_reports')
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
                <h1>@lang('common.meetings_reports') </h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('bbb::bbb.bbb')</a>
                    <a href="#">@lang('common.meetings_reports')</a>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area">
        <div class="container-fluid p-0">

            <div class="white-box">
                <div class="row">
                    <div class="col-lg-10 main-title">
                        <h3 class="mb-15">
                            @lang('common.meetings_reports')
                        </h3>
                    </div>
                </div>
                <div class="row mb-20">
                    <div class="col-lg-12">
    
                        <div>
    
                            <form action="{{ route('bbb.meeting.reports.show') }}" method="GET">
    
                                <div class="row">
                                    <div class="col-lg-3 mt-30-md">
                                        <label class="primary_input_label" for="">@lang('common.member_type')<span></span></label>
                                        <select class="primary_select  user_type" name="member_type">
                                            <option data-display=" @lang('common.member_type') *" value="">@lang('common.member_type') *
                                            </option>
                                            @foreach ($roles as $value)
                                                @if (isset($member_type))
                                                    <option value="{{ $value->id }}"
                                                        {{ $value->id == $member_type ? 'selected' : '' }}>{{ $value->name }}
                                                    </option>
                                                @else
                                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 mt-30-md" id="select_user_div">
                                        <label class="primary_input_label" for="">@lang('common.user')<span></span></label>
                                        <select id="select_user"
                                            class="primary_select "
                                            name="teachser_ids">
                                            <option data-display="@lang('common.select_user')" value="">@lang('common.select_teacher')</option>
                                            @if (isset($editdata))
                                                @foreach ($userList as $teacher)
                                                    <option value="{{ $teacher->id }}"
                                                        {{ isset($editdata) == $teacher->id ? 'selected' : '' }}>
                                                        {{ $teacher->full_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
    
                                    <div class="col-lg-3 mt-30-md">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('common.from_date')<span></span></label>
                                            <div class="primary_datepicker_input">
                                                <div class="no-gutters input-right-icon">
                                                    <div class="col">
                                                        <div class="">
                                                            <input class="primary_input_field  primary_input_field date form-control"
                                                        id="startDate" type="text" name="from_time" autocomplete="off"
                                                        value="{{ isset($from_time) ? Carbon\Carbon::parse($from_time)->format('m/d/Y') : '' }}">
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
                                    <div class="col-lg-3 mt-30-md">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('common.to_date')</label>
                                            <div class="primary_datepicker_input">
                                                <div class="no-gutters input-right-icon">
                                                    <div class="col">
                                                        <div class="">
                                                            <input class="primary_input_field  primary_input_field date form-control"
                                                            id="endDate" type="text" name="to_time"
                                                            value="{{ isset($to_time) ? Carbon\Carbon::parse($to_time)->format('m/d/Y') : '' }}"
                                                            readonly>
                                                        </div>
                                                    </div>
                                                    <button class="btn-date" data-id="#endDate" type="button">
                                                        <label class="m-0 p-0" for="endDate">
                                                            <i class="ti-calendar" id="start-date-icon"></i>
                                                        </label>
                                                    </button>
                                                </div>
                                            </div>
                                            <span class="text-danger">{{ $errors->first('date') }}</span>
                                        </div>
                                    </div>
    
    
    
                                    <div class="col-lg-12 mt-20 text-right">
                                        <button type="submit" class="primary-btn small fix-gr-bg" data-toggle="tooltip"
                                            title="">
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
        </div>
    </section>

    <section class="admin-visitor-area" style="display:  {{ isset($meetings) ? 'block' : 'none' }} ">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box mt-40">
                        <div class="row">
                            <div class="col-lg-12">
                                <x-table>
                                    <table id="table_id" class="table" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>@lang('common.meeting_id')</th>
                                                <th>@lang('common.password')</th>
                                                <th>@lang('common.topic')</th>
                                                <th>@lang('common.participants')</th>
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
                                                        <td>{{ $meeting->meeting_id }}</td>
                                                        <td>{{ $meeting->attendee_password }}</td>
                                                        <td>{{ $meeting->topic }} </td>
                                                        <td>{{ $meeting->participatesName }}</td>
                                                        <td>{{ $meeting->date }}</td>
                                                        <td>{{ $meeting->time }}</td>
                                                        <td>
                                                            @if ($meeting->duration == 0)
                                                                Unlimited
                                                            @else
                                                                {{ $meeting->duration }}
                                                            @endif min
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </x-table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.date_picker_css_js')
@section('script')
    <script>
        $(document).ready(function() {
            $(document).on('change', '.user_type', function() {
                let userType = $(this).val();

                $.get('{{ route('bbb.user.list.user.type.wise') }}', {
                    user_type: userType
                }, function(res) {

                    $.each(res, function(i, item) {

                        $("#select_user").find("option").not(":first").remove();
                        $("#select_user_div ul").find("li").not(":first").remove();

                        $("#select_user").append(
                            $("<option>", {
                                value: "all",
                                text: "Select Member",
                            })
                        );
                        $.each(item, function(i, user) {
                            $("#select_user").append(
                                $("<option>", {
                                    value: user.id,
                                    text: user.full_name,
                                })
                            );

                            $("#select_user_div ul").append(
                                "<li data-value='" +
                                user.id +
                                "' class='option'>" +
                                user.full_name +
                                "</li>"
                            );
                        });

                    });


                    //
                })
            })
        })
    </script>
@stop
