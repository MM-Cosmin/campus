@extends('backEnd.master')
@section('title')
{{@$school->school_name}} @lang('saas::saas.institution_details')
@endsection
@section('mainContent')

@php 
$active_style = App\SmStyle::where("school_id", Auth::user()->school_id)->where('is_active', 1)->first();

 $setting = App\SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();  if(!empty($setting->currency_symbol)){ $currency = $setting->currency_symbol; }else{ $currency = '$'; }   @endphp

  <section class="mb-40 up_dashboard">
        <div class="container-fluid p-0">
            <div class="row mb-30">
                <div class="col-lg-12">
                    <div class="main-title">
                        <h3>@lang('common.welcome_to') <small>{{$school->school_name}}</small></h3>
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

               

                    <div class="col-lg-3 col-6">
                        <a href="#" class="d-block">
                            <div class="white-box single-summery">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>@lang('saas::saas.student')</h3>
                                        <p class="mb-0">@lang('saas::saas.total_students')</p>
                                    </div>
                                    <h1 class="gradient-color2">
                                        @if(isset($totalStudents))
                                            {{count($totalStudents)}}
                                        @endif
                                    </h1>
                                </div>
                            </div>
                        </a>
                    </div>

               


                    <div class="col-lg-3 col-6">
                        <a href="#" class="d-block">
                            <div class="white-box single-summery">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>@lang('common.teachers')</h3>
                                        <p class="mb-0">@lang('saas::saas.total_teachers')</p>
                                    </div>
                                    <h1 class="gradient-color2">
                                        @if(isset($totalStudents))
                                            {{count($totalTeachers)}}
                                        @endif</h1>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-3 col-6">
                        <a href="#" class="d-block">
                            <div class="white-box single-summery">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>@lang('saas::saas.parents')</h3>
                                        <p class="mb-0">@lang('saas::saas.total_parents')</p>
                                    </div>
                                    <h1 class="gradient-color2">
                                        @if(isset($totalParents))
                                            {{count($totalParents)}}
                                        @endif</h1>
                                </div>
                            </div>
                        </a>
                    </div>


                    <div class="col-lg-3 col-6">
                        <a href="#" class="d-block">
                            <div class="white-box single-summery">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>@lang('saas::saas.staffs')</h3>
                                        <p class="mb-0">@lang('saas::saas.total_staffs')</p>
                                    </div>
                                    <h1 class="gradient-color2">
                                        @if(isset($totalStaffs))
                                            {{count($totalStaffs)}}
                                        @endif
                                    </h1>
                                </div>
                            </div>
                        </a>
                    </div>
            </div>
        </div>

    </section>


    <section class="up_dashboard" id="incomeExpenseDiv">
        <div class="container-fluid p-0">
            <div class="row">


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
                    <div class="col-lg-12">
                        <div class="white-box" id="barChartDiv">
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



            </div>
        </div>
    </section>

    <section class="mt-50" id="incomeExpenseSessionDiv">
        <div class="container-fluid p-0">
            <div class="row">

                    <div class="col-lg-8 col-md-9">
                        <div class="main-title">
                            <h3 class="mb-30">@lang('saas::saas.income_and_expenses_for') {{date('Y')}}</h3>
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
                    <div class="col-lg-12">
                        <div class="white-box" id="areaChartDiv">
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
            </div>
        </div>
    </section>


@endsection

    @php
        $chart_data = "";

        for($i = 1; $i <= date('d'); $i++){

            $i = $i < 10? '0'.$i:$i;
            $income = App\SmAddIncome::monthlyIncome($i, $school->id);
            $expense = App\SmAddIncome::monthlyExpense($i, $school->id);


            $chart_data .= "{ day: '" . $i . "', income: " . $income . ", expense:" . $expense . " },";
        }

    @endphp


    @php
        $chart_data_yearly = "";

        for($i = 1; $i <= date('m'); $i++){

            $i = $i < 10? '0'.$i:$i;

            $yearlyIncome = App\SmAddIncome::yearlyIncome($i, $school->id);

            $yearlyExpense = App\SmAddIncome::yearlyExpense($i, $school->id);

            $chart_data_yearly .= "{ y: '" . $i . "', income: " . $yearlyIncome . ", expense:" . $yearlyExpense . " },";
        }

    @endphp



@section('script')

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


        const monthNames = ["", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ];

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
                labels: ['Income', 'Expense'],
                hideHover: 'auto',
                lineColors: ['{{activeStyle()->areachartlinecolor1}}', '{{activeStyle()->areachartlinecolor2}}'],
            });
        }

    </script>



@endsection
