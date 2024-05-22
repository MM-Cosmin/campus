@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('communicate.email_sms_log_list') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('communicate.communicate')</a>
                <a href="#">@lang('communicate.email_sms_log')</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
<div class="container-fluid p-0">
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-4 no-gutters">
                <a href="{{url('administrator/send-email-sms-view')}}" class="primary-btn small fix-gr-bg">
                    <span class="ti-plus pr-2"></span>
                    @lang('communicate.payment_id')
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">

                <table id="table_id" class="display school-table" cellspacing="0" width="100%">

                    <thead>
                       
                        <tr>
                            <th> @lang('common.title')</th>
                            <th> @lang('common.description')</th>
                            <th> @lang('common.date')</th>
                            <th> @lang('common.type')</th>
                            <th> @lang('common.group')</th>
                            <th> @lang('communicate.individual')</th>
                            <th> @lang('common.class')</th>
                           
                        </tr>
                    </thead>

                    <tbody>
                        @if(isset($emailSmsLogs))
                        @foreach($emailSmsLogs as $value)
                        <tr>

                            <td>{{$value->title}}</td>
                            <td>{{$value->description}}</td>
                            <td data-sort="{{strtotime($value->send_date)}}">
                                        {{ $value->send_date != ""? dateConvert($value->send_date):''}}</td>
                            <td>@if($value->send_through == 'E')
                            <button class="primary-btn small bg-warning text-white border-0"> @lang('common.email')</button>
                            @else
                            <button class="primary-btn small bg-success text-white border-0"> @lang('communicate.sms')</button>
                            @endif
                            </td>
                            <td>
                            @if($value->send_to == 'G')
                            <input type="checkbox" id="asdasd" class="" value="1" name="send_to" checked>
                            @endif
                            </td>
                            <td>
                            @if($value->send_to == 'I')
                            <input type="checkbox" id=""  value="" checked>
                            @endif
                            </td>
                            <td>
                            @if($value->send_to != 'G' && $value->send_to != 'I')
                            <input type="checkbox" id=""  value="" checked>
                            @endif
                            </td>
                        </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
@endsection
@include('backEnd.partials.data_table_js')