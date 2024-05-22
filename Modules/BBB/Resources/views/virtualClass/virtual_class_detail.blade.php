@extends('backEnd.master')
@section('title')
    @lang('common.class_details')
@endsection
@section('mainContent')
    <style>
        .propertiesname {
            text-transform: uppercase;
            font-weight: bold;
        }
    </style>
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('common.class_details')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('bbb::bbb.bbb')</a>
                    <a href="#">@lang('common.virtual_class')</a>
                    <a href="#">@lang('common.class_details')</a>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-md-10">
                    <h3 class="mb-30">@lang('common.topic')</h3>
                </div>
                <div class="col-md-2 pull-right  text-right">

                    

                </div>
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="" class="table school-table-style" cellspacing="0" width="100%">

                                <tr>
                                    <th>#</th>
                                    <th>@lang('common.name')</th>
                                    <th>@lang('common.status')</th>
                                </tr>
                                @php $sl = 1 @endphp
                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.topic')</td>
                                    <td>{{ $localMeetingData->topic }}</td>
                                </tr>
                            @if (Auth::user()->role_id != 2)                      
                        
                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.class')</td>
                                    <td>{{ $localMeetingData->class->class_name }}</td>
                                </tr>
                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.class_section')</td>
                                    <td>{{ $localMeetingData->section_id !=null ?  $localMeetingData->section->section_name :'All sections' }}</td>

                                </tr>
                            @endif
                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.teachers')</td>
                                     <td> {{ $localMeetingData->teachersName }}  </td>
                                 </tr>
                               <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.document')</td>
                                    <td>

                                        <a href="{{ asset($localMeetingData->logo) }}" download="" ><i class="fa fa-download mr-1"></i> @lang('common.download')</a>
                                    </td>
                                </tr>

                                <tr>
                                    <td> {{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.start_date_time')</td>
                                    <td>{{ $localMeetingData->date }} {{ $localMeetingData->time }}</td>
                                </tr>
                                <tr>
                                    <td> {{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.class_id')</td>
                                    <td>{{$localMeetingData->meeting_id}}</td>
                                </tr>
                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('bbb::bbb.attendee_password')</td>
                                    <td>{{$localMeetingData->attendee_password}}</td>
                                </tr>
                                {{-- <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">{{__('Moderator Password')}}</td>
                                    <td>{{$localMeetingData->moderator_password}}</td>
                                </tr> --}}

     
                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.start_join')</td>
                                    <td>@lang('common.meeting_start_join_before') {{ $localMeetingData->time_start_before==null ? '10' :  $localMeetingData->time_start_before}} @lang('common.min')</td>
                                </tr>
                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname"> @lang('common.join')  </td>
                                    <td>
                                        @if($localMeetingData->getCurrentStatusAttribute() == 'started')

                                 @if (Auth::user()->role_id == 1 || Auth::user()->id == $localMeetingData->created_by)
                                <a target="_blank" class="primary-btn small bg-success text-white border-0" href="{{ route('bbb.meeting.start', $localMeetingData->meeting_id) }}">


                                        @lang('common.start')
                                        </a>
                                    @else
                                      
                                       <a target="_blank" class="primary-btn small bg-success text-white border-0" href="{{ route('bbb.meeting.join', $localMeetingData->meeting_id) }}">  @lang('common.join') </a>
                                     
                                    @endif
                            @elseif( $localMeetingData->getCurrentStatusAttribute() == 'waiting')
                                <a href="#Closed" class="primary-btn small bg-info text-white border-0">@lang('common.waiting')</button>
                            @else
                                <a href="#Closed" class="primary-btn small bg-warning text-white border-0">@lang('common.closed')</button>
                            @endif
                                    </td>

                                </tr>
                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname"> @lang('bbb::bbb.attendee_join')  </td>
                                    <td>
                                       

                                    </td>
                                </tr>


                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('bbb::bbb.welcome_message')  </td>
                                    <td>{{@$localMeetingData->welcome_message}}</td>
                                </tr>


                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('bbb::bbb.dial_number')  </td>
                                    <td>{{@$localMeetingData->dial_number}}</td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('bbb::bbb.max_participants')  </td>
                                    <td>{{@$localMeetingData->max_participants}}</td>
                                </tr>
                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('bbb::bbb.logout_url')  </td>
                                    <td>{{@$localMeetingData->logout_url}}</td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.duration')  </td>
                                    <td>{{@$localMeetingData->duration}}</td>
                                </tr>


                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('bbb::bbb.record')  </td>
                                    <td>{{@$localMeetingData->record==false?'False':'True'}}</td>
                                </tr>


                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('bbb::bbb.is_breakout')  </td>
                                    <td>{{@$localMeetingData->is_breakout==false?'False':'True'}}</td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('bbb::bbb.moderator_only_message')  </td>
                                    <td>{{@$localMeetingData->moderator_only_message==false?'False':'True'}}</td>
                                </tr>


                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('bbb::bbb.auto_start_recording')  </td>
                                    <td>{{@$localMeetingData->auto_start_recording==false?'False':'True'}}</td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('bbb::bbb.allow_start_stop_recording')  </td>
                                    <td>{{@$localMeetingData->allow_start_stop_recording==false?'False':'True'}}</td>
                                </tr>

                                {{--                                <tr>--}}
                                {{--                                    <td>{{ $sl++ }} </td>--}}
                                {{--                                    <td class="propertiesname">{{__('Logo')}}  </td>--}}
                                {{--                                    <td>{{@$localMeetingData->logo}}</td>--}}
                                {{--                                </tr>--}}
                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('bbb::bbb.copyright') </td>
                                    <td>{{@$localMeetingData->copyright}}</td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('bbb::bbb.mute_on_start')  </td>
                                    <td>{{@$localMeetingData->mute_on_start==false?'False':'True'}}</td>
                                </tr>


                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('bbb::bbb.webcams_only_for_moderator')  </td>
                                    <td>{{@$localMeetingData->webcams_only_for_moderator==false?'False':'True'}}</td>
                                </tr>

                                {{-- <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">{{__('Lock Settings Disable Cam')}}   </td>
                                    <td>{{@$localMeetingData->lock_settings_disable_cam==false?'False':'True'}}</td>
                                </tr> --}}
                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('bbb::bbb.lock_settings_disable_mic')  </td>
                                    <td>{{@$localMeetingData->lock_settings_disable_mic==false?'False':'True'}}</td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('bbb::bbb.lock_settings_lock_on_join')  </td>
                                    <td>{{@$localMeetingData->lock_settings_lock_on_join==false?'False':'True'}}</td>
                                </tr>


                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('bbb::bbb.lock_settings_lock_on_join_configurable')  </td>
                                    <td>{{@$localMeetingData->lock_settings_lock_on_join_configurable==false?'False':'True'}}</td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('bbb::bbb.join_via_html5')  </td>
                                    <td>{{@$localMeetingData->join_via_html5==false?'False':'True'}}</td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('bbb::bbb.lock_settings_disable_private_chat')  </td>
                                    <td>{{@$localMeetingData->lock_settings_disable_private_chat==false?'False':'True'}}</td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('bbb::bbb.lock_settings_disable_public_chat')  </td>
                                    <td>{{@$localMeetingData->lock_settings_disable_public_chat==false?'False':'True'}}</td>
                                </tr>


                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('bbb::bbb.lock_settings_disable_note')  </td>
                                    <td>{{@$localMeetingData->lock_settings_disable_note==false?'False':'True'}}</td>
                                </tr>


                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('bbb::bbb.lock_settings_locked_layout')  </td>
                                    <td>{{@$localMeetingData->lock_settings_locked_layout==false?'False':'True'}}</td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('bbb::bbb.lock_settings_lock_on_join') </td>
                                    <td>{{@$localMeetingData->lock_settings_lock_on_oin==false?'False':'True'}}</td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('bbb::bbb.lock_settings_lock_on_join_configurable') </td>
                                    <td>{{@$localMeetingData->lock_settings_sock_on_join_configurable==false?'False':'True'}}</td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('bbb::bbb.guest_policy')  </td>
                                    <td>{{@$localMeetingData->guest_policy}}</td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('bbb::bbb.redirect')  </td>
                                    <td>{{@$localMeetingData->redirect==false?'False':'True'}}</td>
                                </tr>

                                
                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('bbb::bbb.vedio_uplaod')  </td>
                                    <td>{{@$localMeetingData->redirect==false?'False':'True'}}</td>
                                </tr>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
