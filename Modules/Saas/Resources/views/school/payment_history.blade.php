@extends('backEnd.master')
@section('title')
@lang('saas::saas.subscription_payment_history')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('saas::saas.payment_history') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('saas::saas.subscription')</a>
                <a href="#">@lang('saas::saas.payment_history')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('saas::saas.payment_list')</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                            <table id="table_id" class="table Crm_table_active3" cellspacing="0" width="100%">
                                <thead>                               
                                    <tr>
                                        <th>@lang('saas::saas.package_name')</th>
                                        <th>@lang('saas::saas.payment_date')</th>
                                        <th>@lang('saas::saas.payment_method')</th>
                                        <th>@lang('common.status')</th>
                                        <th>@lang('common.duration')</th>
                                        <th>@lang('accounts.amount') ({{@generalSetting()->currency_symbol}})</th>
                                        <th>@lang('common.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                    <tr>
                                        <td>{{@$payment->package->name}}</td>
                                        <td  data-sort="{{strtotime(@$payment->payment_date)}}" >{{ !empty(@$payment->payment_date)? dateConvert(@$payment->payment_date):''}}</td>
                                        <td>{{@$payment->payment_method}}</td>
                                        <td>{{@$payment->approve_status}}</td>
                                        <td>{{@$payment->package->duration_days}}</td>
                                        <td>{{number_format(@$payment->amount, 2)}}</td>
                                        <td valign="top">
                                            <x-drop-down>
                                                    @if(@$payment->file != "")
                                                    <a class="dropdown-item" href="{{url(@$payment->file)}}">
                                                        @lang('common.download') <span class="pl ti-download"></span>
                                                    </a>
                                                    @endif
                                            </x-drop-down>
                                        </td>
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
