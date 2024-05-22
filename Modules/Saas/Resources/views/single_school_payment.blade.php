@extends('backEnd.master')
@section('title')
@lang('saas::saas.payment_history')
@endsection
@section('mainContent')
@php  $setting = generalSetting(); 
if(!empty(@$setting->currency_symbol)){ @$currency = @$setting->currency_symbol; }
else{ @$currency = '$'; } 
@endphp

<style type="text/css">
    table tfoot th{
        padding:20px 10px 20px 30px !important;
    }
</style>

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
                            <h3 class="mb-0">@lang('saas::saas.payment_list_of') {{$school->school_name}}</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                            <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>@lang('saas::saas.package_name')</th>
                                        <th>@lang('saas::saas.payment_date')</th>
                                        <th>@lang('saas::saas.payment_method')</th>
                                        <th>@lang('common.duration')</th>
                                        <th>@lang('saas::saas.price')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $grand_total = 0;
                                    @endphp
                                    @foreach($payments as $payment)
                                    @php
                                        $grand_total = $grand_total + $payment->amount;
                                    @endphp
                                    <tr>
                                        <td>{{@$payment->package->name}}</td>
                                        <td  data-sort="{{strtotime(@$payment->payment_date)}}" >{{ !empty(@$payment->payment_date)? dateConvert(@$payment->payment_date):''}}</td>
                                        <td>{{@$payment->payment_method}}</td>
                                        <td>{{@$payment->package->duration_days}}</td>
                                       
                                        <td>{{number_format(@$payment->amount, 2)}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th>@lang('accounts.grand_total'):</th>
                                        <th></th>
                                        <th>{{number_format($grand_total, 2)}}</th>
                                    </tr>
                                </tfoot>
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