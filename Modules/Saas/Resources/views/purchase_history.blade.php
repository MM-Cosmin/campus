@extends('backEnd.master')
@section('title')
{{@$package->name}} @lang('saas::saas.package_purchase_history')
@endsection
@section('mainContent')

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>{{@$package->name}} @lang('saas::saas.package') - @lang('saas::saas.purchase_history') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">{{@$package->name}}</a>
                <a href="#">@lang('saas::saas.purchase_history')</a>
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
                            <h3 class="mb-0">@lang('saas::saas.purchase_history')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                            <table id="table_id" class="display school-table" cellspacing="0" width="100%">

                                <thead>
                                   @if(session()->has('message-success-delete') != "" ||
                                    session()->get('message-danger-delete') != "")
                                    <tr>
                                        <td colspan="3">
                                             @if(session()->has('message-success-delete'))
                                              <div class="alert alert-success">
                                                  {{ session()->get('message-success-delete') }}
                                              </div>
                                            @elseif(session()->has('message-danger-delete'))
                                              <div class="alert alert-danger">
                                                  {{ session()->get('message-danger-delete') }}
                                              </div>
                                            @endif
                                        </td>
                                    </tr>
                                     @endif
                                    <tr>
                                        <th>@lang('common.school_name')</th>
                                    
                                        <th>@lang('saas::saas.payment_date')</th>
                                        <th>@lang('saas::saas.payment_type')</th>
                                        <th>@lang('saas::saas.payment_method')</th>
                                        
                                        <th>@lang('common.duration') (days)</th>
                                        <th>@lang('common.status')</th>
                                        <th>@lang('accounts.amount') ({{@generalSetting()->currency_symbol}})</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                        $grand_total = 0;
                                    @endphp
    
                                    @foreach($purchase_history as $payment)
                                    @php
                                    if($payment->payment_type =="paid"){
                                        $grand_total = $grand_total + $payment->amount;
                                    }
                                        
                                    @endphp
                                    <tr>
                                        <td><a href="{{route('subscription/single-school-payment', [$payment->school_id])}}"> {{@$payment->school->school_name}} </a> </td>
                                        
                                        <td  data-sort="{{strtotime(@$payment->payment_date)}}" >{{ !empty(@$payment->payment_date)? dateConvert(@$payment->payment_date):''}}</td>
                                        <td>{{@$payment->payment_type}}</td>
                                        <td>{{@$payment->payment_method}}</td>
                                        <td>{{@$payment->package->duration_days}}</td>
                                         <td>{{@$payment->approve_status}}</td>
                                       
                                        <td>
                                            @if( $payment->payment_type =="paid")
                                                {{number_format(@$payment->amount,2)}}
                                            @else 
                                            {{number_format(0,2)}}
                                            @endif
                                        </td>
    
                                    
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th>@lang('accounts.grand_total'):</th>
                                        <th></th>
                                        <th></th>
                                        <th> {{@generalSetting()->currency_symbol}} {{number_format($grand_total, 2)}}</th>
                                        
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