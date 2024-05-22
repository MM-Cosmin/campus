@extends('backEnd.master')
@section('title')
    @lang('common.meetings_reports')
@endsection
@section('css')
<style>
    .propertiesname{
        text-transform: uppercase;
    }.
    .recurrence-section-hide {
       display: none!important
    }
    </style>
@endsection

@section('mainContent')
<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('common.meetings_reports') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
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
                    @lang('common.meetings_reports')
                </h3>
            </div>
        </div>
        <div class="row mb-20">
            <div class="col-lg-12">
                <div class="white-box">
                    @if(userPermission('zoom.meeting.reports.show') )
                        <form action="{{ route('zoom.meeting.reports.show') }}" method="GET">
                    @endif
                            <div class="row">
                                <div class="col-lg-4 mt-30-md">
                                    <label class="primary_input_label" for="">
                                        {{ __('common.member_type') }} *
                                            <span class="text-danger"> </span>
                                    </label>
                                    <select class="primary_select  user_type form-control" name="member_type">
                                        <option data-display=" @lang('common.member_type') *" value="">@lang('common.member_type') *</option>
                                        @foreach($roles as $value)
                                            @if(isset($member_type))
                                                <option value="{{$value->id}}" {{ $value->id == $member_type ? 'selected' : '' }}>{{ $value->name }}</option>
                                            @else
                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <span class="text-danger">{{ $errors->first('member_type') }}</span>
                                </div>
                                <div class="col-lg-4 mt-30-md" id="select_user_div">
                                   
                                    <label class="primary_input_label" for="">
                                        {{ __('common.user') }}
                                            <span class="text-danger"> </span>
                                    </label>
                                    <select id="select_user" class="primary_select {{ $errors->has('section_id') ? ' is-invalid' : '' }}" name="teachser_ids">
                                        <option data-display="@lang('common.select_user')" value="">@lang('common.select_user')</option>
                                        @if(isset($editdata))
                                            @foreach($userList as $teacher)
                                                <option value="{{$teacher->id }}" {{ isset($editdata) == $teacher->id? 'selected':'' }} >{{$teacher->full_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>

                                </div>
                                
                                <div class="col-lg-2 mt-30-md"> 
                                    <div class="primary_input">
                                        <label class="primary_input_label" for="">@lang('common.from_date')<span></span></label>
                                        <div class="primary_datepicker_input">
                                            <div class="no-gutters input-right-icon">
                                                <div class="col">
                                                    <div class="">
                                                        <input data-display="@lang('common.from_date')" placeholder="@lang('common.from_date')" class="primary_input_field  primary_input_field date form-control form-control" id="from_time" type="text" name="from_time" value="{{ isset($from_time) ? Carbon\Carbon::parse($from_time)->format('m/d/Y') : '' }}">
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
                                <div class="col-lg-2 mt-30-md">                                    
                                    
                                    <div class="primary_input">
                                        <label class="primary_input_label" for="">@lang('common.to_date')<span></span></label>
                                        <div class="primary_datepicker_input">
                                            <div class="no-gutters input-right-icon">
                                                <div class="col">
                                                    <div class="">
                                                        <input data-display="@lang('common.to_date')" placeholder="@lang('common.to_date')" class="primary_input_field  primary_input_field date form-control form-control" id="to_time" type="text" name="to_time" value="{{ isset($to_time) ? Carbon\Carbon::parse($to_time)->format('m/d/Y') : '' }}">
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
                                    $tooltip = "";
                                        if(userPermission('zoom.meeting.reports.show'))
                                        {
                                            $tooltip = "";
                                        }else{
                                            $tooltip = "You have no permission to search";
                                        }
                                @endphp

                                <div class="col-lg-12 mt-20 text-right">
                                    <button type="submit" class="primary-btn small fix-gr-bg" data-toggle="tooltip" title="{{$tooltip}}">
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

<section class="admin-visitor-area" style="display:  {{ isset($meetings) ? 'block' : 'none'  }} ">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                        <table id="default_table2" class="table" cellspacing="0" width="100%">
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
                                @foreach($meetings as $key => $meeting )
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $meeting->meeting_id }}</td>
                                    <td>{{ $meeting->password }}</td>
                                    <td>{{ $meeting->topic }}  </td>
                                    <td>{{ $meeting->participatesName }}</td>
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

        </div>
    </div>
</section>

@include('backEnd.partials.date_picker_css_js')

@endsection

@section('script')
    <script>
        $(document).ready(function(){
            $(document).on('change','.user_type',function(){
                let userType = $(this).val();
               
                $.get('{{ route('zoom.user.list.user.type.wise') }}',{ user_type: userType },function(res){
                   
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
