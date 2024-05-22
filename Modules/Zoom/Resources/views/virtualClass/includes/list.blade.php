
@if(Auth::user()->role_id == 1 )
    <div class="col-lg-9">
@elseif(userPermission('zoom.virtual-class') && userPermission('zoom.virtual-class.store'))
    <div class="col-lg-9">
@else
    <div class="col-lg-12">
        
@endif
        <div class="main-title">
            <h3 class="mb-0">
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
                            @if (Auth::user()->role_id != 2 || Auth::user()->role_id != 3)
                            @if(moduleStatusCheck('University'))
                            <th>@lang('university::un.semester_label')</th>
                            <th>@lang('university::un.department')</th>
                            <th>@lang('common.section')</th>
                            @else 
                                <th>@lang('common.class')</th>
                                <th>@lang('common.class_section')</th>
                            @endif 
                            @endif
                            <th>@lang('zoom::zoom.virtual_class_id')</th>
                            <th>@lang('common.password')</th>
                            <th>@lang('common.topic')</th>
                            <th>@lang('common.date')</th>
                            <th>@lang('common.time')</th>
                            <th>@lang('common.duration')</th>
                            <th>@lang('zoom::zoom.zoom_start_join_before')</th>
                            <th>@lang('common.start_join')</th>                       
                            <th>@lang('common.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        @foreach($meetings as $key => $meeting )
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            @if (Auth::user()->role_id != 2 || Auth::user()->role_id != 3 )
                            @if(moduleStatusCheck('University'))
                            <td>{{ @$meeting->semesterLabel->name }} -({{@$meeting->unAcademic->name}})</td>
                            <td>{{ @$meeting->unDepartment->name}}</td>
                            <td>{{ @$meeting->unSection->section_name}}</td>
                            @else 
                            <td>{{ $meeting->class->class_name }}</td>
                            <td>{{ $meeting->section_id !=null ?  $meeting->section->section_name :'All sections' }}</td>

                            @endif
                            @endif
                            <td>{{ $meeting->meeting_id }}</td>
                            <td>{{ $meeting->password }}</td>
                            <td>{{ $meeting->topic }}</td>
                            <td>{{ $meeting->date_of_meeting }}</td>
                            <td>{{ $meeting->time_of_meeting }}</td>
                            <td>{{ $meeting->meeting_duration }} @lang('common.min')</td>
                            <td>{{ $meeting->time_before_start }} Min </td>
                            <td>
                                @if($meeting->currentStatus == 'started')
                           
                                        <a class="primary-btn small bg-success text-white border-0" href="{{ route('zoom.virtual-class.join', $meeting->meeting_id) }}" target="_blank" >
                                            @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 4 || Auth::user()->id == $meeting->created_by )
                                                @lang('common.start')
                                            @else
                                                @lang('common.join')
                                            @endif
                                        </a>
            
                                @elseif( $meeting->currentStatus == 'waiting')
                                    <a href="#Closed" class="primary-btn small bg-info text-white border-0">@lang('common.waiting')</button>
                                @else
                                    <a href="#Closed" class="primary-btn small bg-warning text-white border-0">@lang('common.closed')</button>
                                @endif
                                
                            </td>
                            <td>
                                <x-drop-down>
                                        <a class="dropdown-item" target="_blank"  href="{{ route('zoom.virtual-class.show', $meeting->meeting_id) }}">@lang('common.view')</a>
                                         @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 4 || Auth::user()->id == $meeting->created_by )
                                         
                                         
                                           <a class="dropdown-item modalLink" data-modal-size="modal-md"   title="@lang('zoom::zoom.upload_recorded_video')"  
                                           href="{{route('zoom.virtual-upload-vedio-file', [$meeting->id])}}" >@lang('zoom::zoom.upload_recorded_video')</a>
                                        
                                        @endif
                                        @if(userPermission('zoom.virtual-class.edit'))
                                            <a class="dropdown-item" href="{{ route('zoom.virtual-class.edit',$meeting->id ) }}">@lang('common.edit')</a>
                                        @endif
                                        @if(userPermission('zoom.virtual-class.destroy') )
                                            <a class="dropdown-item" data-toggle="modal" data-target="#d{{$meeting->id}}" href="#">@lang('common.delete')</a>
                                        @endif
                                </x-drop-down>
                            </td>
                        </tr>
     
                   
                        @if(userPermission('zoom.virtual-class.destroy'))
                            <div class="modal fade admin-query" id="d{{$meeting->id}}" >
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">@lang('zoom::zoom.delete_virtual_class')</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="text-center">
                                                <h4>@lang('common.are_you_sure_delete')</h4>
                                            </div>
                                            <div class="mt-40 d-flex justify-content-between">
                                                <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                <form class="" action="{{ route('zoom.virtual-class.destroy',$meeting->id) }}" method="POST" >
                                                    @csrf
                                                    @method('delete')
                                                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @endforeach
                    </tbody>
                </table>
                </x-table>
            </div>
        </div>
    </div>

