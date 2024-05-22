@extends('backEnd.master')
@section('title') 
 Saas {{@Auth::user()->roles->name}}  @lang('common.dashboard')
@endsection
@section('mainContent')

    <section class="mb-40 up_dashboard">
        <div class="container-fluid p-0">
            <div class="row mb-30">
                <div class="col-lg-12">
                    <div class="main-title">
                        <h3>@lang('common.welcome_to_administrator') </h3>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    @if(session()->has('message-success'))
                        <div class="alert alert-success">
                            {{ session()->get('message-success') }}
                        </div>
                    @elseif(session()->has('message-danger'))
                        <div class="alert alert-danger">
                            {{ session()->get('message-danger') }}
                        </div>
                    @endif
                </div>
            </div>
             <div class="row">

                @if(userPermission(681))
                    <div class="col-lg-3 col-6">
                        <a href="{{route('administrator/institution-list')}}" class="d-block">
                            <div class="white-box single-summery">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>@lang('saas::saas.institution')</h3>
                                        <p class="mb-0">@lang('saas::saas.total_institution')</p>
                                    </div>
                                    <h1 class="gradient-color2">
                                        @if(isset($total_inistitutions))
                                            {{$total_inistitutions}}
                                        @endif
                                    </h1>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif


                @if(userPermission(603))
                    <div class="col-lg-3 col-6">
                        <a href="#" class="d-block">
                            <div class="white-box single-summery">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3> @lang('saas::saas.active_students')</h3>
                                        <p class="mb-0">@lang('saas::saas.total_active_students')</p>
                                    </div>
                                    <h1 class="gradient-color2">
                                        @if(isset($students))
                                            {{$students}}
                                        @endif
                                    </h1>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif

                @if(userPermission(604) )
                    <div class="col-lg-3 col-6">
                        <a href="#" class="d-block">
                            <div class="white-box single-summery">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3> @lang('saas::saas.inactive_students')</h3>
                                        <p class="mb-0">@lang('saas::saas.total_students')</p>
                                    </div>
                                    <h1 class="gradient-color2">
                                        @if(isset($data['inactiveStu']))
                                            {{$data['inactiveStu']}}
                                        @endif
                                    </h1>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif

                @if(userPermission(682))
                    <div class="col-lg-3 col-6">
                        <a href="#" class="d-block">
                            <div class="white-box single-summery">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>@lang('saas::saas.staff')</h3>
                                        <p class="mb-0">@lang('saas::saas.total_staff')</p>
                                    </div>
                                    <h1 class="gradient-color2">
                                        @if(isset($data['staffs']))
                                            {{$data['staffs']}}
                                        @endif
                                    </h1>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
            </div>
            <div class="row">
                @if(userPermission(683))
                    <div class="col-lg-3 col-6">
                        <a href="#" class="d-block">
                            <div class="white-box single-summery">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>@lang('saas::saas.saas_staffs')</h3>
                                        <p class="mb-0">@lang('saas::saas.total_saas_staffs')</p>
                                    </div>
                                    <h1 class="gradient-color2">

                                        @if(isset($data['saasStaffs']))
                                            {{$data['saasStaffs']}}
                                        @endif
                                    </h1>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
                @if(userPermission(605))
                    <div class="col-lg-3 col-6">
                        <a href="#" class="d-block">
                            <div class="white-box single-summery">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>@lang('saas::saas.parents')</h3>
                                        <p class="mb-0">@lang('saas::saas.total_parents')</p>
                                    </div>
                                    <h1 class="gradient-color2">
                                        @if(isset($students))
                                            {{$students}}
                                        @endif
                                    </h1>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif

                @if(userPermission(680))
                    <div class="col-lg-3 col-6">
                        <a href="#" class="d-block">
                            <div class="white-box single-summery">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>@lang('common.teachers')</h3>
                                        <p class="mb-0">@lang('saas::saas.total_teachers')</p>
                                    </div>
                                    <h1 class="gradient-color2">
                                        @if(isset($data['teachers']))
                                            {{$data['teachers']}}
                                        @endif
                                    </h1>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif

                @if(userPermission(684))
                    <div class="col-lg-3 col-6">
                        <a href="#" class="d-block">
                            <div class="white-box single-summery">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>@lang('saas::saas.school_admin')</h3>
                                        <p class="mb-0">@lang('saas::saas.total_school_admin')</p>
                                    </div>
                                    <h1 class="gradient-color2">
                                        @if(isset($data['schoolAdmin']))
                                            {{$data['schoolAdmin']}}
                                        @endif
                                    </h1>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
            </div>
        {{-- </div> --}}
    </section>


    <section class="up_dashboard" id="incomeExpenseDiv">
        <div class="container-fluid p-0">
            <div class="row">
                @if(in_array(135, $module_links) || Auth::user()->role_id == 1)

                    <div class="col-lg-8 col-md-9">
                        <div class="main-title">
                            <h3 class="mb-30"> @lang('saas::saas.income_and_expenses_for') {{date('M Y')}}</h3>
                        </div>
                    </div>
                    <div class="offset-lg-2 col-lg-2 text-right col-md-3 up_ds_margin">
                        <button type="button" class="primary-btn small tr-bg icon-only" id="areaChartBtn">
                            <span class="pr ti-move"></span>
                        </button>

                        <button type="button" class="primary-btn small fix-gr-bg icon-only ml-10"
                                id="areaChartBtnRemovetn">
                            <span class="pr ti-close"></span>
                        </button>
                    </div>

                    @php  if(!empty(generalSetting()->currency_symbol)){ $currency = generalSetting()->currency_symbol; }else{ $currency = '$'; }   @endphp
                    <div class="col-lg-12">
                        <div class="white-box" id="areaChartDiv">
                            <div class="row">
                                <div class="col-lg-3 col-6">
                                    <div class="text-center">
                                        <h1>({{generalSetting()->currency_symbol}}) {{number_format($m_total_income)}}</h1>
                                        <p>@lang('saas::saas.total_income')</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <div class="text-center">
                                        <h1>({{generalSetting()->currency_symbol}}) {{number_format($m_total_expense)}}</h1>
                                        <p>@lang('saas::saas.total_expenses')</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <div class="text-center">
                                        <h1>({{generalSetting()->currency_symbol}}) {{number_format($m_total_income - $m_total_expense)}}</h1>
                                        <p>@lang('saas::saas.total_profit')</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <div class="text-center">
                                        <h1>({{generalSetting()->currency_symbol}}) {{number_format($m_total_income)}}</h1>
                                        <p>@lang('saas::saas.total_revenue')</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div id="commonBarChart" style="height: 350px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>


                @endif

            </div>
        </div>
    </section>

    <section class="mt-50" id="incomeExpenseSessionDiv">
        <div class="container-fluid p-0">
            <div class="row">

                @if(in_array(136, $module_links) || Auth::user()->role_id == 1)
                    <div class="col-lg-8 col-md-9">
                        <div class="main-title">
                            <h3 class="mb-30">@lang('saas::saas.income_and_expenses_for') {{date('Y')}}</h3>
                        </div>
                    </div>
                    <div class="offset-lg-2 col-lg-2 text-right col-md-3 up_ds_margin">
                        <button type="button" class="primary-btn small tr-bg icon-only" id="barChartBtn">
                            <span class="pr ti-move"></span>
                        </button>

                        <button type="button" class="primary-btn small fix-gr-bg icon-only ml-10"
                                id="barChartBtnRemovetn">
                            <span class="pr ti-close"></span>
                        </button>
                    </div>
                    <div class="col-lg-12">
                        <div class="white-box" id="barChartDiv">
                            <div class="row">
                                <div class="col-lg-3 col-6">
                                    <div class="text-center">
                                        <h1>({{generalSetting()->currency_symbol}}) {{number_format($y_total_income)}}</h1>
                                        <p>@lang('saas::saas.total_income')</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <div class="text-center">
                                        <h1>({{generalSetting()->currency_symbol}}) {{number_format($y_total_expense)}}</h1>
                                        <p>@lang('saas::saas.total_expenses')</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <div class="text-center">
                                        <h1>({{generalSetting()->currency_symbol}}) {{number_format($y_total_income - $y_total_expense)}}</h1>
                                        <p>@lang('saas::saas.total_profit')</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <div class="text-center">
                                        <h1>({{generalSetting()->currency_symbol}}) {{number_format($y_total_income)}}</h1>
                                        <p>@lang('saas::saas.total_revenue')</p>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div id="commonAreaChart" style="height: 350px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script type="text/javascript">
        function barChart(idName) {
            window.barChart = Morris.Bar({
                element: 'commonBarChart',
                data: [<?php echo $chart_data; ?>],
                xkey: 'day',
                ykeys: ['income', 'expense'],
                labels: ['Income', 'Expense'],
                barColors: ['{{activeStyle()->barchart1}}', '{{activeStyle()->barchart2}}'],
                resize: true,
                redraw: true,
                gridTextColor: '{{activeStyle()->barcharttextcolor}}',
                gridTextSize: 12,
                gridTextFamily: '"poppins", sans-serif',
                barGap: 4,
                barSizeRatio: 0.3
            });
        }


        const monthNames = ["", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        function areaChart() {
            window.areaChart = Morris.Area({
                element: 'commonAreaChart',
                data: [  <?php echo $chart_data_yearly; ?> ],
                xkey: 'y',
                parseTime: false,
                ykeys: ['income', 'expense'],
                labels: ['Income', 'Expense'],
                xLabelFormat: function (x) {
                    var index = parseInt(x.src.y);
                    return monthNames[index];
                },
                xLabels: "month",
                labels: ['Series A'],
                hideHover: 'auto',
                lineColors: ['{{activeStyle()->areachartlinecolor1}}', '{{activeStyle()->areachartlinecolor2}}'],
            });
        }

    </script>
@endpush

