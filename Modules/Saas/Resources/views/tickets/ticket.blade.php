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
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row justify-content-between p-3">
            <div class="bc-pages"></div>
            <div class="bc-pages">
                @if (Auth::user()->is_administrator=='yes')
                    <a href="{{ route('admin.add_ticket') }}" class="primary-btn small fix-gr-bg">
                        <span class="ti-plus pr-2"></span>
                        @lang('common.add')
                    </a>
                @else
                    <a href="{{ route('school.add_ticket') }}" class="primary-btn small fix-gr-bg">
                        <span class="ti-plus pr-2"></span>
                        @lang('common.add')
                    </a>
                @endif
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
                                <span class="text-danger" role="alert">
                                    {{ $errors->first('category') }}
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
                                <span class="text-danger" role="alert">
                                    {{ $errors->first('priority') }}
                                </span>
                                @endif
                            </div>
                            <div class="col-lg-4 mt-30-md">
                                <select class="primary_select form-control{{ $errors->has('active_status') ? ' is-invalid' : '' }}" id="select_class" name="active_status">
                                    <option data-display="@lang('common.status') *" value="">@lang('saas::saas.select_status') *</option>
                                    <option value="0">@lang('common.pending')</option>
                                    <option value="1">@lang('saas::saas.ongoing')</option>
                                    <option value="2">@lang('saas::saas.complete')</option>
                                    @if(Auth::user()->role_id == 1)
                                        <option value="3">@lang('common.close')</option>
                                    @endif
                                </select>
                                @if ($errors->has('active_status'))
                                    <span class="text-danger" role="alert">
                                        {{ $errors->first('active_status') }}
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
                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                            <table id="table_id" class="table Crm_table_active3" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="25%">@lang('common.subject')</th>
                                    <th width="15%">@lang('student.category')</th>
                                    <th width="15%">@lang('saas::saas.ticket_priority')</th>
                                    <th width="10%">@lang('saas::saas.user_agent')</th>
                                    <th width="10%">@lang('common.school')</th>
                                    <th width="10%">@lang('common.status')</th>
                                    <th width="15%">@lang('common.action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ticket as $value)
                                    @if (Auth::user()->role_id != 2 && Auth::user()->role_id != 3 )
                                        <tr>
                                            <td>{{\Illuminate\Support\Str::limit($value->subject,35)}}</td>
                                            <td>{{@$value->category->name}}</td>
                                            <td>{{@$value->priority->name}}</td>
                                            <td>{{@$value->agent_user?$value->agent_user->full_name:'Not assign yet !'}}</td>
                                            <td>{{ @$value->school->school_name }}</td>
                                            <td>
                                                @if ($value->active_status == 0)
                                                    @lang('common.pending')
                                                @endif
                                                @if ($value->active_status == 1)
                                                    @lang('saas::saas.ongoing')
                                                @endif
                                                @if ($value->active_status == 2)
                                                    @lang('saas::saas.complete')
                                                @endif
                                                @if ($value->active_status == 3)
                                                    @lang('common.close')
                                                @endif
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <x-drop-down>
                                                               <a class="dropdown-item" href="{{ route('admin.ticket_view',$value->id)}}"> @lang('common.view')</a>
                                                                     @if ((Auth::user()->is_administrator == "yes") ||userPermission('admin.ticket_edit'))
                                                                    <a class="dropdown-item" href="{{ route('admin.ticket_edit',$value->id)}}"> @lang('common.edit')</a>
                                                                    @endif
                                                                    @if((Auth::user()->is_administrator == "yes") || userPermission('admin.ticket_delete_view'))
                                                                    <a class="deleteUrl dropdown-item" data-modal-size="modal-md" title="@lang('common.delete_ticket')" href="{{ route('admin.ticket_delete_view',$value->id)}}"> 
                                                                        @lang('common.delete')
                                                                    </a>
                                                                    @endif
                                                                    @if(userPermission('admin.assign_ticket'))
                                                                    <a class="assign_ticket_modal dropdown-item" data-modal-size="modal-md" data-ticket_id = "{{ $value->id }}" data-assign_user="{{ $value->assign_user }}"> 
                                                                        @lang('common.Assign')
                                                                    </a>
                                                                @endif
                                                        </x-drop-down>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @else
                                  
                                    @endif
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

<div class="modal fade admin-query" id="assign_ticket_modal">
    <div class="modal-dialog small-modal modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('saas::saas.Assign Ticket')</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="container-fluid">
                  
                        {!! Form::open(['route'=>'admin.assign_ticket', 'method'=>'POST']) !!}   
                        <input type="hidden" id="ticket_id" name="ticket_id">                  
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-12" id="staff_class_div">
                                        <select class="primary_select" name="assign_user" id="assign_user_id">
                                            <option data-display="@lang('hr.select_staff') *" value="">@lang('hr.select_staff') *</option>
                                            @foreach($staffs as $staff)
                                            <option value="{{$staff->user_id}}" >{{$staff->full_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 text-center mt-40">
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>

                                    <button class="primary-btn fix-gr-bg"  type="submit">@lang('common.Assign')</button>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@include('backEnd.partials.data_table_js')
@push('script')
    <script>
        $(document).on('click', '.assign_ticket_modal', function(){
            let ticket_id = $(this).data('ticket_id');
            let assign_user = $(this).data('assign_user')
            $('#ticket_id').val(ticket_id);
            if(assign_user) {
                $("#assign_user_id option[value="+assign_user+"]").attr('selected', 'selected');
                $('#assign_user_id').niceSelect('update');
            }
            $('#assign_ticket_modal').modal('toggle');
        })
    </script>
@endpush

