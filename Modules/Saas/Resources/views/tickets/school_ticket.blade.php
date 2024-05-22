@extends('backEnd.master')
@section('title')
@lang('saas::saas.ticket_list')
@endsection 
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('saas::saas.ticket_list')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
            <a href="{{ route('admin.ticket_list')}}">@lang('saas::saas.ticket_system')</a>
                <a href="#">@lang('saas::saas.ticket_list')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row justify-content-between p-3">
            <div class="bc-pages">
             
            </div>
            <div class="bc-pages">
                    <a href="{{ route('admin.add_ticket') }}" class="primary-btn small fix-gr-bg">
                            <span class="ti-plus pr-2"></span>
                            @lang('common.add')
                        </a>
            </div>
    </div>
            <div class="row">
                <div class="col-lg-12">
                        
                    <div class="">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => ['admin.ticket_search'], 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_studentA']) }}
                            <div class="row white-box">
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                <div class="col-lg-4 mt-30-md">
                                    <select class="primary_select form-control{{ $errors->has('category') ? ' is-invalid' : '' }}" id="select_class" name="category">
                                        <option data-display="@lang('saas::saas.ticket_category') *" value="">@lang('saas::saas.ticket_category_select') *</option>
                                        @foreach($category as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                     @if ($errors->has('category'))
                                    <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('category') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-4 mt-30-md">
                                    <select class="primary_select form-control{{ $errors->has('priority') ? ' is-invalid' : '' }}" id="select_class" name="priority">
                                        <option data-display="@lang('saas::saas.ticket_priority') *" value="">@lang('saas::saas.ticket_priority_select') *</option>
                                        @foreach($priority as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                     @if ($errors->has('priority'))
                                    <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('priority') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-4 mt-30-md">
                                    <select class="primary_select form-control{{ $errors->has('active_status') ? ' is-invalid' : '' }}" id="select_class" name="active_status">
                                        <option data-display="@lang('common.status') *" value="">@lang('saas::saas.select_status') *</option>
                                        <option value="0">Pending</option>
                                        <option value="1">Complete</option>
                                    </select>
                                     @if ($errors->has('active_status'))
                                    <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('active_status') }}</strong>
                                    </span>
                                    @endif
                                </div>
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

            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0">@lang('saas::saas.ticket_list')</h3>
                            </div>
                        </div>
                    </div>
                    

                        
                    <!-- </div> -->
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                                <thead>
                                     @if(session()->has('message-success') != "" ||
                                    session()->get('message-danger') != "")
                                    <tr>
                                        <td colspan="7">
                                            @if(session()->has('message-success'))
                                            <div class="alert alert-success">
                                                {{ session()->get('message-success') }}
                                            </div>
                                            @elseif(session()->has('message-danger'))
                                            <div class="alert alert-danger">
                                                {{ session()->get('message-danger') }}
                                            </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th width="25%">@lang('common.subject')</th>
                                        <th width="15%">@lang('student.category')</th>
                                        <th width="10%">@lang('saas::saas.user_name')</th>
                                        <th width="15%">@lang('saas::saas.ticket_priority')</th>
                                        <th width="10%">@lang('saas::saas.user_agent')</th>
                                        <th width="10%">@lang('common.status')</th>
                                        <th width="15%">@lang('common.action')</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($ticket as $value)
                                    @if (Auth::user()->role_id == 1)
                                    <tr>
                                            <td>{{str_limit($value->subject,35)}}</td>
                                            <td>{{@$value->category->name}}</td>
                                            <td>{{$value->user->full_name}}</td>
                                            <td>{{@$value->priority->name}}</td>
                                            <td>{{@$value->agent_user?$value->agent_user->full_name:'Not assign yet !'}}</td>
                                            @if ($value->active_status == 0)
                                            <td>Pending</td>
                                            @endif
                                            @if ($value->active_status == 1)
                                            <td>Ongoing</td>
                                            @endif
                                            @if ($value->active_status == 2)
                                            <td>Complete</td>
                                            @endif
                                            @if ($value->active_status == 3)
                                            <td>Close</td>
                                            @endif
                                            <td>
                                                    <div class="row">
                                                    <div class="col-sm-6">
                    
                                                    <div class="dropdown">
                                                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                            @lang('common.select')
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right">
                     
                                                    
                    
                                                            <a class="dropdown-item" href="{{ route('admin.ticket_view',$value->id)}}"> @lang('common.view')</a>
                                                            <a class="dropdown-item" href="{{ route('admin.ticket_edit',$value->id)}}"> @lang('common.edit')</a>
                                                            <a class="deleteUrl dropdown-item" data-modal-size="modal-md" title="Delete Ticket" href="{{ route('admin.ticket_delete_view',$value->id)}}"> @lang('common.delete')</a>
                    
                                                            
                    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                </td>
                                    </tr>  
                                    @else
                                    @if (isset($value->assign_user) && @Auth::user()->id == $value->assign_user)
                                     
                                    <tr>
                                            <td>{{str_limit($value->subject,35)}}</td>
                                            <td>{{@$value->category->name}}</td>
                                            <td>{{$value->user->username}}</td>
                                            <td>{{@$value->priority->name}}</td>
                                            <td>{{@$value->agent_user?$value->agent_user->username:'Not assign yet !'}}</td>
                                            @if ($value->active_status == 0)
                                            <td>Pending</td>
                                            @endif
                                            @if ($value->active_status == 1)
                                            <td>Ongoing</td>
                                            @endif
                                            @if ($value->active_status == 2)
                                            <td>Complete</td>
                                            @endif
                                            @if ($value->active_status == 3)
                                            <td>Close</td>
                                            @endif
                                            <td>
                                                    <div class="row">
                                                    <div class="col-sm-6">
                    
                                                    <div class="dropdown">
                                                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                            @lang('common.select')
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right">
                     
                                                    
                    
                                                            <a class="dropdown-item" href="{{ route('admin.ticket_view',$value->id)}}"> @lang('common.view')</a>
                                                            <a class="dropdown-item" href="{{ route('admin.ticket_edit',$value->id)}}"> @lang('common.edit')</a>
                                                            {{-- <a class="deleteUrl dropdown-item" data-modal-size="modal-md" title="Delete Ticket" href="{{ route('admin.ticket_delete_view',$value->id)}}"> @lang('common.delete')</a> --}}
                    
                                                            
                    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                </td>
                                    </tr>   
                                    @endif
                                    @endif
                                    @endforeach
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