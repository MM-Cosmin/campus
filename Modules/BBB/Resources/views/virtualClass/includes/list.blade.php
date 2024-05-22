@if (Auth::user()->role_id == 1)
    <div class="col-lg-9">
    @elseif(userPermission('bbb.virtual-class') && userPermission('bbb.virtual-class.store'))
        <div class="col-lg-9">
        @else
            <div class="col-lg-12">
@endif
<div class="white-box">
    <div class="main-title">
        <h3 class="mb-15">
            @lang('common.virtual_class_list')
        </h3>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <x-table>
                <table id="table_id" class="table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            
                            <!-- <th>@lang('common.password')</th> -->
                            <th>@lang('common.topic')</th>
                            <th>@lang('bbb::bbb.date_|_time')</th>
                            <!-- <th>@lang('common.duration')</th> -->
                            <!-- <th>@lang('common.start_join_before')</th> -->
                            <th>@lang('common.start_join')</th>
                            @if (Auth::user()->role_id != 2)
                                <th>@lang('common.meeting_id')</th>
                                <th>@lang('common.class')</th>
                                <th>@lang('common.class_section')</th>
                            @endif
                            <th>@lang('common.actions')</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if(isset($meetings))
                            @foreach ($meetings as $key => $meeting)
                                @php 
                                    $created_id = $meeting->created_by;
                                    $user = App\User::find($created_id);
                                @endphp

                                @if($user)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <!-- <td>{{ $meeting->attendee_password }}</td> -->
                                        <td>{{ $meeting->topic }}</td>
                                        <td>{{ $meeting->date }} | {{ $meeting->time }}</td>
                                        <!-- <td>{{ $meeting->duration == 0 ? 'Unlimited' : $meeting->duration}}</td> -->
                                        <!-- <td>{{ $meeting->time_start_before == null ? 10 : $meeting->time_start_before }} Min</td> -->
                                        <td>
                                            @php
                                                $created_id = $meeting->created_by;
                                                $user = App\User::find($created_id);
                                                if ($user && $user->role_id == 1) {
                                                    $teahcer_id = DB::table('bbb_virtual_class_teachers')
                                                        ->where('meeting_id', $meeting->id)
                                                        ->first(['id', 'user_id']);
                                                    $teahcer_id = $teahcer_id->user_id;
                                                } else {
                                                    $teahcer_id = 0;
                                                }
                                            @endphp
                                            @if ($meeting->getCurrentStatusAttribute() == 'started')
                                                @if (Auth::user()->role_id == 1 || Auth::user()->id == $meeting->created_by || $teahcer_id == Auth::user()->id)
                                                    <a target="_blank" class="primary-btn small bg-success text-white border-0"
                                                        href="{{ route('bbb.meeting.start', $meeting->meeting_id) }}">
                                                        @lang('common.start')
                                                    </a>
                                                @else
                                                    <a target="_blank" class="primary-btn small bg-success text-white border-0"
                                                        href="{{ route('bbb.meeting.join', $meeting->meeting_id) }}">
                                                        @lang('common.join')
                                                    </a>
                                                @endif
                                            @elseif($meeting->getCurrentStatusAttribute() == 'waiting')
                                                <a href="#Closed"
                                                    class="primary-btn small bg-info text-white border-0">@lang('common.waiting')</button>
                                                @else
                                                    <a href="#Closed"
                                                        class="primary-btn small bg-warning text-white border-0">@lang('common.closed')</button>
                                            @endif
                                        </td>
                                        @if (Auth::user()->role_id != 2)
                                            <td>{{ $meeting->meeting_id }}</td>
                                            <td>{{ $meeting->class->class_name }}</td> 
                                            <td>{{ $meeting->section_id != null ? $meeting->section->section_name : 'All sections' }}
                                            </td>
                                        @endif
                                        <td>
                                            <x-drop-down>
                                                @if (Auth::user()->role_id == 1 || $user->role_id == 5)
                                                    <a class="dropdown-item"
                                                        href="{{ route('bbb.virtual-class.edit', $meeting->id) }}">@lang('common.edit')</a>
                
                                                    <a class="dropdown-item" data-toggle="modal" data-target="#d{{ $meeting->id }}"
                                                        href="#">@lang('common.delete')</a>
                                                @elseif($user->role_id != 1 || $user->role_id != 5)
                                                    @if (userPermission('bbb.virtual-class.edit'))
                                                        <a class="dropdown-item"
                                                            href="{{ route('bbb.virtual-class.edit', $meeting->id) }}">@lang('common.edit')</a>
                                                    @endif
                                                    @if (userPermission('bbb.virtual_class.destroy'))
                                                        <a class="dropdown-item" data-toggle="modal"
                                                            data-target="#d{{ $meeting->id }}" href="#">
                                                            @lang('common.delete')
                                                        </a>
                                                    @endif
                                                @else
                                                    <a class="dropdown-item"
                                                        href="{{ route('bbb.virtual-class.show', $meeting->id) }}">@lang('common.view')</a>
                                                @endif
                                            </x-drop-down>
                                        </td>
                                    </tr>
            
                                    <div class="modal fade admin-query" id="d{{ $meeting->id }}">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">@lang('common.delete')</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                
                                                <div class="modal-body">
                                                    <div class="text-center">
                                                        <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                    </div>
                
                                                    <div class="mt-40 d-flex justify-content-between">
                                                        <button type="button" class="primary-btn tr-bg"
                                                            data-dismiss="modal">@lang('common.cancel')</button>
                                                        <form class=""
                                                            action="{{ route('bbb.virtual_class.destroy', $meeting->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('delete')
                                                            <button class="primary-btn fix-gr-bg"
                                                                type="submit">@lang('common.delete')</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif 
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </x-table> 
        </div>
    </div>
</div>
</div>

@include('backEnd.partials.data_table_js')
