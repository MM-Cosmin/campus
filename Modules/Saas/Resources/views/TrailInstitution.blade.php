@extends('backEnd.master')
@section('title')
@lang('saas::saas.trial_institutes')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('saas::saas.trial_institutes') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('saas::saas.subscription')</a>
                <a href="#">@lang('saas::saas.trial_institutes') </a>
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
                            <h3 class="mb-0">@lang('saas::saas.trial_institutes') </h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                        <table id="table_id" class="table Crm_table_active3" cellspacing="0" width="100%">

                            <thead> 
                                <tr>
                                    <th>@lang('common.school_name')</th>
                                    <th>@lang('saas::saas.package_name')</th>
                                    {{-- <th>@lang('saas::saas.payment_date')</th> --}}
                                    {{-- <th>@lang('saas::saas.payment_method')</th> --}}
                                    
                                    <th>@lang('common.duration')</th>
                                    <th>@lang('common.status')</th>
                                    <th>@lang('accounts.amount') ({{@generalSetting()->currency_symbol}})</th>
                                    <th>@lang('common.action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                <tr>
                                    <td>{{@$payment->school->school_name}}</td>
                                    <td>{{@$payment->package->name}}</td>
                                    {{-- <td  data-sort="{{strtotime(@$payment->payment_date)}}" >{{ !empty(@$payment->payment_date)? dateConvert(@$payment->payment_date):''}}</td> --}}
                                    {{-- <td>{{@$payment->payment_method}}</td> --}}
                                   
                                    <td>{{@$payment->package->duration_days}}</td>
                                     <td>{{@$payment->approve_status}}</td>
                                   
                                    <td>{{number_format(@$payment->amount,2)}}</td>

                                    <td valign="top">
                                      <x-drop-down>

                                                <a class="dropdown-item modalLink" data-modal-size="modal-lg" title="@lang('saas::saas.update_payment_status') " href="{{route('subscription/update-status', [@$payment->id])}}">@lang('saas::saas.update_status')</a>

                                                @if(@$payment->file != "")
                                                 <a class="dropdown-item" href="{{route('subscription/download-payment-document',showDocument(@$payment->file))}}">
                                                     @lang('common.download') <span class="pl ti-download"></span>
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
@include('backEnd.partials.data_table_js')