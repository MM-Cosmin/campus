@extends('backEnd.master')
@section('title')
@lang('saas::saas.payment_history')
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
                                    <th>@lang('common.school_name')</th>
                                    
                                    <th>@lang('saas::saas.total_amount') ({{@generalSetting()->currency_symbol}})</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                                @foreach($payments as $payment)
                                <tr>
                                    <td><a href="{{route('subscription/single-school-payment', [$payment->school_id])}}"> {{@$payment->school->school_name}}</a></td>
                                    <td>{{number_format(@$payment->amount,2)}}</td>
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