@extends('backEnd.master')
@section('title')
@lang('system_settings.income_expense_report')
@endsection

@section('mainContent')
@push('css')
<style>
    table.dataTable thead .sorting_asc::after,
    table.dataTable thead .sorting::after,
    table.dataTable thead .sorting_desc::after {
    top: 10px !important;
    left: 0px !important;
}
</style>
@endpush

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('system_settings.income_expense_report')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('reports.reports')</a>
                <a href="#">@lang('system_settings.income_expense_report')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('common.select_criteria') </h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    @if(session()->has('message-success') != "")
                        @if(session()->has('message-success'))
                        <div class="alert alert-success">
                            {{ session()->get('message-success') }}
                        </div>
                        @endif
                    @endif
                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'administrator/income-expense', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_income_expense']) }}
                            <div class="row">
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                <div class="col-lg-4">
                                    <select class="primary_select form-control{{ $errors->has('institution') ? ' is-invalid' : '' }}" name="institution" id="institution">
                                        <option data-display="@lang('common.select_institution') *" value="">@lang('common.select_institution') *</option>
                                        @foreach($institutions as $institution)
                                        <option value="{{ $institution->id }}">{{ $institution->school_name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('institution'))
                                    <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('institution') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="col-lg-4 mt-30-md">
                                    
                                    <div class="primary_input">
                                        {{-- <label>@lang('accounts.date_from')</label> --}}
                                        <div class="primary_datepicker_input">
                                            <div class="no-gutters input-right-icon">
                                                <div class="col">
                                                    <div class="">
                                                        <input class="primary_input_field date form-control{{ $errors->has('date_from') ? ' is-invalid' : '' }}" id="startDate" type="text"
                                                        name="date_from" value="{{isset($from_date)? date('m/d/Y', strtotime($from_date)):date('m/d/Y')}}" >
                                                    </div>
                                                </div>
                                                <button class="btn-date" data-id="#startDate" type="button">
                                                    <label class="m-0 p-0" for="startDate">
                                                        <i class="ti-calendar" id="start-date-icon"></i>
                                                    </label>
                                                </button>
                                            </div>
                                        </div>
                                        <span class="text-danger invalid-select" role="alert">{{ $errors->first('date') }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-4 mt-30-md">
                                    
                                    <div class="primary_input">
                                        {{-- <label>@lang('accounts.date_to')</label> --}}
                                        <div class="primary_datepicker_input">
                                            <div class="no-gutters input-right-icon">
                                                <div class="col">
                                                    <div class="">
                                                        <input class="primary_input_field date form-control{{ $errors->has('date_to') ? ' is-invalid' : '' }}" id="endDate" type="text"
                                                     name="date_to" value="{{isset($to_date)? date('m/d/Y', strtotime($to_date)):date('m/d/Y')}}" >
                                                   
                                                    </div>
                                                </div>
                                                <button class="btn-date" data-id="#endDate" type="button">
                                                    <label class="m-0 p-0" for="endDate">
                                                        <i class="ti-calendar" id="start-date-icon"></i>
                                                    </label>
                                                </button>
                                            </div>
                                        </div>
                                        <span class="text-danger invalid-select" role="alert">{{ $errors->first('date_to') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-30">
                                <div class="col-lg-4">
                                    <select class="primary_select form-control{{ $errors->has('type') ? ' is-invalid' : '' }}" name="type" id="account-type">
                                        <option data-display="Select Type *" value="">@lang('common.search_type')*</option>
                                        <option value="In" {{isset($type_id)? ($type_id == "In"? 'selected':''):''}}>@lang('accounts.income')</option>
                                        <option value="Ex" {{isset($type_id)? ($type_id == "Ex"? 'selected':''):''}}>@lang('accounts.expense')</option>
                                    </select>
                                    @if ($errors->has('type'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('type') }}
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-4" id="filtering_div">
                                    <select class="primary_select form-control{{ $errors->has('filtering') ? ' is-invalid' : '' }}" name="filtering" id="filtering_section">
                                    </select>
                                    @if ($errors->has('type'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('type') }}
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-4" id="income_div">
                                    <select class="primary_select form-control{{ $errors->has('filtering') ? ' is-invalid' : '' }}" name="filtering_income" id="filtering_section">
                                        <option value="all">@lang('common.all')</option>
                                        <option value="sell">@lang('inventory.item_sell')</option>
                                        <option value="fees">@lang('fees.fees_collection')</option>
                                        <option value="dormitory">@lang('dormitory.dormitory')</option>
                                        <option value="transport">@lang('transport.transport')</option>
                                    </select>
                                    
                                </div>

                                <div class="col-lg-2" id="expense_div">
                                    <select class="primary_select form-control{{ $errors->has('filtering') ? ' is-invalid' : '' }}" name="filtering_expense" id="filtering_section">
                                        <option value="all">@lang('common.all')</option>
                                        <option value="receive">@lang('inventory.item_receive')</option>
                                        <option value="payroll">@lang('hr.payroll')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
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

            
@if(isset($add_incomes))


            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-6 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0">@lang('accounts.income_result')</h3>
                            </div>
                        </div>
                    </div>

                
                    <!-- </div> -->
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>@lang('common.name')</th>
                                        <th>@lang('accounts.payroll')</th>
                                        <th>@lang('accounts.amount')({{generalSetting()->currency_symbol}})</th>
                                    </tr>
                                </thead>
                                @php $total_income = 0;@endphp
                                <tbody>
                                    @foreach($add_incomes as $add_income)
                                    @php $total_income = $total_income + $add_income->amount; @endphp
                                    <tr>
                                        <td>{{$add_income->name}}</td>
                                        <td>{{$add_income->ACHead!=""?$add_income->ACHead->head:""}}</td>
                                        <td>{{number_format($add_income->amount, 2)}}</td>
                                    </tr>
                                    @endforeach 
                                    @if($fees_payments != "")
                                        @php $total_income = $total_income + $fees_payments; @endphp
                                        <tr>
                                            <td>@lang('fees.fees_collection')</td>
                                            <td>@lang('fees.fees')</td>
                                            <td>{{number_format($fees_payments, 2)}}</td>
                                        </tr>
                                    @endif
                                    @if($item_sells != "")
                                    @php $total_income = $total_income + $item_sells; @endphp
                                    <tr>
                                        <td>@lang('inventory.item_sell')</td>
                                        <td>@lang('accounts.sells')</td>
                                        <td>{{number_format($item_sells, 2)}}</td>
                                    </tr>
                                    @endif
                                    @if($dormitory != 0)
                                    @php $total_income = $total_income + $dormitory; @endphp
                                    <tr>
                                        <td>@lang('accounts.dormitory_fees')</td>
                                        <td>@lang('dormitory.dormitory')</td>
                                        <td>{{number_format($dormitory, 2)}}</td>
                                    </tr>
                                    @endif
                                    
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th>@lang('accounts.grand_total')</th>
                                        <th>{{number_format($total_income, 2)}}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

@endif

@if(isset($add_expenses))


            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-6 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0">@lang('accounts.expense_result')</h3>
                            </div>
                        </div>
                    </div>

                
                    <!-- </div> -->
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>@lang('common.name')</th>
                                        <th>@lang('accounts.expense_head')</th>
                                        <th>@lang('accounts.amount')({{generalSetting()->currency_symbol}})</th>
                                    </tr>
                                </thead>
                                @php $total_expense = 0;@endphp
                                <tbody>
                                    @foreach($add_expenses as $add_expense)
                                    @php $total_expense = $total_expense + $add_expense->amount; @endphp
                                    <tr>
                                        <td>{{$add_expense->name}}</td>
                                        <td>{{$add_expense->ACHead!=""?$add_expense->ACHead->head:""}}</td>
                                        <td>{{number_format($add_expense->amount, 2)}}</td>
                                    </tr>
                                    @endforeach
                                    @if($item_receives != 0)
                                    @php $total_expense = $total_expense + $item_receives; @endphp
                                    <tr>
                                        <td>@lang('accounts.item_purchase')</td>
                                        <td>@lang('accounts.purchase')</td>
                                        <td>{{number_format($item_receives, 2)}}</td>
                                    </tr>
                                    @endif
                                    @if($payroll_payments != 0)
                                    @php $total_expense = $total_expense + $payroll_payments; @endphp
                                    <tr>
                                        <td>@lang('fees.from_payroll')</td>
                                        <td>@lang('hr.payroll')</td>
                                        <td>{{number_format($payroll_payments, 2)}}</td>
                                    </tr>
                                    @endif  
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th>@lang('accounts.grand_total')</th>
                                        <th>{{number_format($total_expense, 2)}}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

@endif

    </div>
</section>


@endsection
@include('backEnd.partials.date_picker_css_js')
@include('backEnd.partials.data_table_js')
