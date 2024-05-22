@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('saa::saas.ticket_list')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="{{ route('user.ticket') }}">@lang('saa::saas.ticket_system')</a>
                <a href="#">@lang('saa::saas.ticket_list')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
            <div class="row justify-content-between p-3">
                    <div class="bc-pages">
                            <a href="{{ route('user.active_ticket') }}" id="active" class="primary-btn small fix-gr-bg">
                                    @lang('saa::saas.active_ticket_system')
                             </a>
                            <a href="{{ route('user.completed_ticket') }}" id="complete" class="primary-btn small fix-gr-bg">
                                    @lang('saa::saas.completed_ticket_system')
                             </a>
                            <a href="{{ route('admin.ticket_list') }}" id="all" class="primary-btn small fix-gr-bg" style="display:none">
                                    @lang('saa::saas.all_ticket_system')
                             </a>
                    </div>
                    <div class="bc-pages">
                            <a href="{{ route('user.add_ticket') }}" class="primary-btn small fix-gr-bg">
                                    <span class="ti-plus pr-2"></span>
                                    @lang('common.add')
                                </a>
                    </div>
            </div>
      
        <div class="row">

            <div class="col-lg-12">
                
          <div class="row">
            <div class="col-lg-4 no-gutters mt-2">
                <div class="main-title">
                    <h3 class="mb-0"> @lang('saa::saas.ticket_list')</h3>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-12">
                <table id="table_id" class="display school-table" cellspacing="0" width="100%">

                    <thead>                      
                        <tr >
                            <th width="16%">@lang('common.subject')</th>
                            <th width="16%">@lang('student.category')</th>
                            <th width="16%">@lang('saa::saas.user_name')</th>
                            <th width="16%">@lang('saa::saas.ticket_priority')</th>
                            <th width="16%">@lang('saa::saas.user_agent')</th>
                            <th width="10%">@lang('common.status')</th>
                            <th width="16%">@lang('common.actions')</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if(isset($ticket))
                        @foreach($ticket as $value)
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

                                        <a class="dropdown-item" href="{{ route('user.ticket_view',$value->id)}}"> @lang('common.view')</a>
                                        <a class="dropdown-item" href="{{ route('user.ticket_edit',$value->id)}}"> @lang('common.edit')</a>
                                        <a class="deleteUrl dropdown-item" data-modal-size="modal-md" title="Delete Ticket" href="{{ route('user.ticket_delete_view',$value->id)}}"> @lang('common.delete')</a>

                                        

                                    </div>
                                </div>
                            </div>
                        </div>
                            </td>
                        </tr>

                        @endforeach
                        @endif
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
@section('script')
 
@endsection
