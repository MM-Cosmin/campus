@extends('backEnd.master')
@section('mainContent')

    @php  $setting = App\SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();  if(!empty($setting->currency_symbol)){ $currency = $setting->currency_symbol; }else{ $currency = '$'; }   @endphp
    <?php


    $module_links = [];
    $permissions = App\SmRolePermission::where('role_id', Auth::user()->role_id)->get();
    $module_links = [];
    $modules = [];
    foreach ($permissions as $permission) {
        $module_links[] = $permission->module_link_id;
        $modules[] = $permission->moduleLink->module_id;
    }
    $modules = array_unique($modules);
    if (Auth::user()->role_id == 3) {
        $childrens = App\SmParent::myChildrens();
    }

    $active_style = App\SmStyle::where("school_id", Auth::user()->school_id)->where('is_active', 1)->first();


    ?>

    <section class="mb-40 up_dashboard">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="main-title">
                        <h3 class="mb-20">@lang('common.welcome')</h3>
                        {{-- {{Session::get('LoginData')->school_name}}--}}

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

                @if(in_array(131, $module_links) || Auth::user()->role_id == 1)

                    <div class="col-lg-3 col-6">
                        <a href="#" class="d-block">
                            <div class="white-box single-summery">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>@lang('saas::saas.students')</h3>
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

                @endif


                @if(in_array(132, $module_links) || Auth::user()->role_id == 1)
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

                @endif


                @if(in_array(133, $module_links) || Auth::user()->role_id == 1)

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

                @endif


                @if(in_array(134, $module_links) || Auth::user()->role_id == 1)

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
        @endif


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
                @endif
            </div>
        </div>
    </section>


    @if(in_array(137, $module_links) || Auth::user()->role_id == 1)
        <section class="mt-50">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">@lang('communicate.notice_board')</h3>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <table class="school-table-style w-100">
                            <thead>
                            <tr>
                                <th>@lang('common.date')</th>
                                <th>@lang('saas::saas.title')</th>
                                <th>@lang('common.actions')</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php $role_id = Auth()->user()->role_id; ?>
                            <?php if (isset($notices)) {
                            foreach ($notices as $notice) {
                            $inform_to = explode(',', $notice->inform_to);
                            if (in_array($role_id, $inform_to)) {
                            ?>
                            <tr>
                                <td>{{date('jS M, Y', strtotime($notice->publish_on))}}</td>
                                <td>{{$notice->notice_title}}</td>
                                <td>
                                    <a href="{{route('view-notice',$notice->id)}}" title="View notice"
                                       class="primary-btn small tr-bg modalLink"
                                       data-modal-size="modal-lg">@lang('common.view')</a>
                                </td>
                            </tr>
                            <?php
                            }
                            }
                            }

                            if(Auth::user()->role_id == 5){
                            foreach ($administrator_notices as $notice) {
                            $inform_to = explode(',', $notice->inform_to);
                            if (in_array($school_id, $inform_to)) {
                            ?>
                            <tr>
                                <td>{{date('jS M, Y', strtotime($notice->publish_on))}}</td>
                                <td>{{$notice->notice_title}}</td>
                                <td>
                                    <a href="{{route('view-admin-notice',$notice->id)}}" title="View notice"
                                       class="primary-btn small tr-bg modalLink"
                                       data-modal-size="modal-lg">@lang('common.view')</a>
                                </td>
                            </tr>
                            <?php
                            }
                            }
                        }


                            ?>
                            
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    @endif


    <section class="mt-50">
        <div class="container-fluid p-0">

            @if(in_array(138, $module_links) || Auth::user()->role_id == 1)
                <div class="row">
                    <div class="col-lg-9 col-xl-9">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="main-title">
                                    <h3 class="mb-30">@lang('saas::saas.calendar')</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="white-box">
                                    <div class='common-calendar'>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-xl-3 mt-50-md">
                        <div class="row align-items-center up_toList">
                            <div class="col-lg-6 col-md-6">
                                <div class="main-title">
                                    <h3 class="mb-30">@lang('saas::saas.to_do_list')</h3>
                                </div>
                            </div>
                            <div class="col-lg-6 text-right col-12">

                                <a href="#" data-toggle="modal" class="primary-btn small fix-gr-bg"
                                   data-target="#add_to_do" title="Add To Do" data-modal-size="modal-md">
                                    <span class="ti-plus pr-2"></span>
                                    @lang('common.add')
                                </a>
                            </div>
                        </div>


                        <div class="modal fade admin-query" id="add_to_do">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Add To Do</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'saveToDoData',
                                            'method' => 'POST', 'enctype' => 'multipart/form-data', 'onsubmit' => 'return validateToDoForm()']) }}

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="row mt-25">
                                                        <div class="col-lg-12" id="sibling_class_div">
                                                            <div class="primary_input">
                                                                <input class="primary_input_field" type="text"
                                                                       name="todo_title" id="todo_title">
                                                                <label>@lang('saas::saas.to_do_title') <span></span> </label>
                                                                <span class="focus-border"></span>
                                                                <span class="modal_input_validation red_alert"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-30">
                                                        <div class="col-lg-12" id="">
                                                            <div class="no-gutters input-right-icon">
                                                                <div class="col">
                                                                    <div class="primary_input">
                                                                        <input class="read-only-input primary_input_field date form-control{{ $errors->has('date') ? ' is-invalid' : '' }}"
                                                                               id="startDate" type="text"
                                                                               autocomplete="off" readonly="true"
                                                                               name="date" value="{{date('m/d/Y')}}">
                                                                        <label>@lang('common.date') <span></span> </label>
                                                                        @if ($errors->has('date'))
                                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('date') }}</strong>
                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-auto">
                                                                    <button class="" type="button">
                                                                        <i class="ti-calendar" id="start-date-icon"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12 text-center">
                                                        <div class="mt-40 d-flex justify-content-between">
                                                            <button type="button" class="primary-btn tr-bg"
                                                                    data-dismiss="modal">@lang('common.cancel')</button>
                                                            <input class="primary-btn fix-gr-bg" type="submit"
                                                                   value="save">
                                                        </div>
                                                    </div>
                                                </div>
                                                {{ Form::close() }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array(139, $module_links) || Auth::user()->role_id == 1)
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="white-box school-table">
                                        <div class="to-do-list up_buttom mb-20">
                                            <button class="primary-btn small fix-gr-bg"
                                                    id="toDoList">@lang('saas::saas.incomplete')</button>
                                            <button class="primary-btn small tr-bg"
                                                    id="toDoListsCompleted">@lang('saas::saas.completed')</button>
                                        </div>

                                        <input type="hidden" id="url" value="{{url('/')}}">


                                        <div class="toDoList">
                                            @if(count($toDoLists)>0)

                                                @foreach($toDoLists as $toDoList)
                                                    <div class="single-to-do d-flex justify-content-between toDoList"
                                                         id="to_do_list_div{{$toDoList->id}}">
                                                        <div>
                                                            <input type="checkbox" id="midterm{{$toDoList->id}}"
                                                                   class="common-checkbox complete_task"
                                                                   name="complete_task" value="{{$toDoList->id}}">

                                                            <label for="midterm{{$toDoList->id}}">

                                                                <input type="hidden" id="id" value="{{$toDoList->id}}">
                                                                <input type="hidden" id="url" value="{{url('/')}}">
                                                                <h5 class="d-inline">{{$toDoList->todo_title}}</h5>
                                                                <p class="ml-35">{{ date('jS M, Y', strtotime($toDoList->date)) }}</p>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="single-to-do d-flex justify-content-between">
                                                    @lang('saas::saas.no_do_lists_assigned_yet')
                                                </div>

                                            @endif
                                        </div>


                                        <div class="toDoListsCompleted">
                                            @if(count($toDoListsCompleteds)>0)

                                                @foreach($toDoListsCompleteds as $toDoListsCompleted)

                                                    <div class="single-to-do d-flex justify-content-between"
                                                         id="to_do_list_div{{$toDoListsCompleted->id}}">
                                                        <div>
                                                            <h5 class="d-inline">{{$toDoListsCompleted->todo_title}}</h5>
                                                            <p class="">{{ date('jS M, Y', strtotime($toDoListsCompleted->date)) }}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="single-to-do d-flex justify-content-between">
                                                    @lang('saas::saas.no_do_lists_assigned_yet')
                                                </div>

                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
        </div>
    </section>





    @php
        $chart_data = "";

        for($i = 1; $i <= date('d'); $i++){

        $i = $i < 10? '0'.$i:$i;
            $income = App\SmAddIncome::monthlyIncome($i, $school_id);
            $expense = App\SmAddIncome::monthlyExpense($i, $school_id);

            $chart_data .= "{ day: '" . $i . "', income: " . $income . ", expense:" . $expense . " },";
        }

    @endphp


    @php
        $chart_data_yearly = "";

        for($i = 1; $i <= date('m'); $i++){

        $i = $i < 10? '0'.$i:$i;

            $yearlyIncome = App\SmAddIncome::yearlyIncome($i, $school_id);

            $yearlyExpense = App\SmAddIncome::yearlyExpense($i, $school_id);

            $chart_data_yearly .= "{ y: '" . $i . "', income: " . $yearlyIncome . ", expense:" . $yearlyExpense . " },";
        }

    @endphp


@endsection

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
                labels: ['Series A'],
                hideHover: 'auto',
                lineColors: ['{{activeStyle()->areachartlinecolor1}}', '{{activeStyle()->areachartlinecolor2}}'],
            });
        }

    </script>
    <?php
    $events = array();
    foreach ($holidays as $k => $holiday) {
        $events[$k]['title'] = $holiday->holiday_title;
        $events[$k]['start'] = date('D M Y', strtotime($holiday->from_date));
        $events[$k]['end'] = date('D M Y', strtotime($holiday->to_date));
    }
    ?>




    <script type="text/javascript">
        /*-------------------------------------------------------------------------------
           Full Calendar Js
        -------------------------------------------------------------------------------*/
        if ($('.common-calendar').length) {
            $('.common-calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                height: 650,
                events: <?php echo json_encode($events);?>,
            });
        }


    </script>

@endsection

