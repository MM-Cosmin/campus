
 @if(Auth::user()->role_id == 1 )
    <div class="col-lg-9">
 @elseif(userPermission('zoom.meetings.store') && userPermission('zoom.meetings'))
    <div class="col-lg-9">
 @else
    <div class="col-lg-12">
 @endif

    <div class="main-title">
        <h3 class="mb-0">
            @lang('common.meeting_list')
        </h3>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <x-table>
                <table id="table_id" class="table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('common.meeting_id')</th>
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
                            <td>{{ $meeting->meeting_id }}</td>
                            <td>{{ $meeting->password }}</td>
                            <td>{{ $meeting->topic }}</td>
                            <td>{{ $meeting->date_of_meeting }}</td>
                            <td>{{ $meeting->time_of_meeting }}</td>
                            <td>{{ $meeting->meeting_duration }} Min </td>                    
                            <td>{{ $meeting->time_before_start }} Min </td>
                            <td>
                                @if($meeting->currentStatus == 'started')
                                    @if(userPermission('zoom.virtual-meeting.join'))
                                        <a class="primary-btn small bg-success text-white border-0" href="{{ route('zoom.meeting.join', $meeting->meeting_id) }}" target="_blank" >
                                            @if (Auth::user()->role_id == 1 || Auth::user()->id == $meeting->created_by)
                                                @lang('common.start')
                                            @else
                                                @lang('common.join')
                                            @endif
                                        </a>
                                    @else
                                        <button href="#notpermitted" class="primary-btn small bg-warning text-white border-0">Not Permitted</button>
                                    @endif

                                @elseif( $meeting->currentStatus == 'waiting')
                                    <a href="#Closed" class="primary-btn small bg-info text-white border-0">Waiting</button>
                                @else
                                    <a href="#Closed" class="primary-btn small bg-warning text-white border-0">Closed</button>
                                @endif
                            </td>
                            <td>
                                    <x-drop-down>
                                                <a class="dropdown-item" href="{{ route('zoom.meetings.show', $meeting->meeting_id) }}">@lang('common.view')</a>
                                            @if(userPermission('zoom.meetings.edit'))
                                                <a class="dropdown-item" href="{{ route('zoom.meetings.edit',$meeting->id ) }}">@lang('common.edit')</a>
                                            @endif
                                            @if(Auth::user()->id == $meeting->created_by)
                                            
                                            <a class="dropdown-item modalLink" data-modal-size="modal-md" title="@lang('zoom::zoom.upload_recorded_video')"  
                                                href="{{route('zoom.meeting-upload-vedio-file', [$meeting->id])}}" >@lang('zoom::zoom.upload_recorded_video')</a>
                                        
                                            @endif
                                            @if(userPermission('zoom.meetings.destroy'))
                                                <a class="dropdown-item" data-toggle="modal" data-target="#d{{$meeting->id}}" href="#">@lang('common.delete')</a>
                                            @endif
                                    </x-drop-down>
                            </td>
                        </tr>
                        <div class="modal fade admin-query" id="uploadmeeting{{$meeting->id}}">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title"> @lang('zoom::zoom.upload_recorded_file')</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'zoom.upload_document',
                                                                'method' => 'POST', 'enctype' => 'multipart/form-data', 'name' => 'document_upload']) }}
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <input type="hidden" name="meeting_id"
                                                        value="{{$meeting->id}}">
                                                    <div class="row mt-25">
                                                        <div class="col-lg-12">
                                                            <div class="primary_input">
                                                                <input type="hidden" name="meetingupload" value="meetingUpload">
                                                                <input class="primary_input_field form-control" type="text"
                                                                    name="title" value="{{$meeting->vedio_link}}" id="link">
                                                                <label> @lang('zoom::zoom.link')</label>
                                                                

                                                                <span class=" text-danger" role="alert"
                                                                    id="amount_error">
                                                                    
                                                                </span>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 mt-30">
                                                    <div class="row no-gutters input-right-icon">
                                                        <div class="col">
                                                            <div class="primary_input">
                                                                <input class="primary_input_field" type="text"
                                                                    id="placeholderPhoto" placeholder="{{isset($meeting->local_video) && @$meeting->local_video != ""? getFilePath3(@$meeting->local_video) : 'Attach File'}}"
                                                                    disabled>
                                                                
                                                            </div>
                                                        </div>
                                                        <div class="col-auto">
                                                            <button class="primary-btn-small-input" type="button">
                                                                <label class="primary-btn small fix-gr-bg" for="photo"> @lang('common.browse')</label>
                                                                <input type="file" class="d-none" name="vedio"
                                                                    id="photo">
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-lg-12 text-center mt-40">
                                                    <div class="mt-40 d-flex justify-content-between">
                                                        <button type="button" class="primary-btn tr-bg"
                                                                data-dismiss="modal">@lang('common.cancel')
                                                        </button>

                                                        <button class="primary-btn fix-gr-bg submit" type="submit">@lang('common.save')
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            {{ Form::close() }}
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        @if(userPermission('zoom.meetings.destroy'))
                            <div class="modal fade admin-query" id="d{{$meeting->id}}" >
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">@lang('zoom::zoom.delete_meetings')</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="text-center">
                                                <h4>@lang('zoom::zoom.are_you_sure_delete')</h4>
                                            </div>

                                            <div class="mt-40 d-flex justify-content-between">
                                                <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                <form class="" action="{{ route('zoom.meetings.destroy',$meeting->id) }}" method="POST" >
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
@include('backEnd.partials.data_table_js')
