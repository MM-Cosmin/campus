@extends('backEnd.master')
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
                <h1>@lang('zoom::zoom.meeting_details')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('common.meeting')</a>
                    <a href="#">@lang('common.details') </a>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-md-10">
                    <h3 class="mb-30"> @lang('common.topic') : {{ @$results['topic'] }}</h3>
                </div>
                <div class="col-md-2 pull-right  text-right">
                    @if (userPermission('zoom.meetings.edit'))
                        <a href="{{ route('zoom.meetings.edit', $localMeetingData->id) }}"
                            class="primary-btn small fix-gr-bg "> <span class="ti-pencil-alt"></span> @lang('common.edit') </a>
                    @endif
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
                                    <td>{{ @$results['topic'] }}</td>
                                </tr>
                                @if ($localMeetingData->weekly_days != null)
                                    <tr>
                                        <td>{{ $sl++ }} </td>
                                        <td class="propertiesname">@lang('zoom::zoom.repeat_day')</td>
                                        <td>
                                            @foreach ($assign_day as $day)
                                                {{ $day->name }},
                                            @endforeach
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.participants')</td>
                                    <td> {{ $localMeetingData->participatesName }} </td>
                                </tr>
                                @if ($localMeetingData->attached_file)
                                    <tr>
                                        <td>{{ $sl++ }} </td>
                                        <td class="propertiesname"> @lang('common.attached_file') </td>
                                        <td> <a href="{{ asset($localMeetingData->attached_file) }}" download=""><i
                                                    class="fa fa-download mr-1"></i> Download</a> </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td> {{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.start_date_time')</td>
                                    <td>{{ $localMeetingData->MeetingDateTime }}</td>
                                </tr>
                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('zoom::zoom.video_link') </td>
                                    <td>
                                        {{ $localMeetingData->vedio_link }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname"> @lang('zoom::zoom.recorded_video') </td>
                                    <td>
                                        @if ($localMeetingData->local_video)
                                            <a href="{{ asset($localMeetingData->local_video) }}" download=""><i
                                                    class="fa fa-download mr-1"></i> Download</a>
                                        @else
                                            No File
                                        @endif
                                    </td>


                                    </td>
                                </tr>
                                <tr>
                                    <td> {{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.meeting_id')</td>
                                    <td>{{ @$results['id'] }}</td>
                                </tr>


                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.start_join')</td>
                                    <td>

                                        @if (@$results['currentStatus'] == 'started')
                                            <a class="primary-btn small bg-success text-white border-0"
                                                href="{{ route('zoom.meeting.join', $localMeetingData->meeting_id) }}"
                                                target="_blank">
                                                @if (Auth::user()->role_id == 1 || Auth::user()->id == $localMeetingData->created_by)
                                                    @lang('common.start')
                                                @else
                                                    @lang('common.join')
                                                @endif
                                            </a>
                                        @elseif(@$results['currentStatus'] == 'waiting')
                                            <a href="#Waiting"
                                                class="primary-btn small bg-warning text-white border-0">@lang('common.not_yet_start')</button>
                                            @else
                                                <a href="#Closed"
                                                    class="primary-btn small bg-warning text-white border-0">@lang('common.closed')</button>
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.host_id')</td>
                                    <td>{{ @$results['host_id'] }}</td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.description')</td>
                                    <td> {{ $localMeetingData->description }} </td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.status')</td>
                                    <td>{{ @$results['currentStatus'] }}</td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.timezone')</td>
                                    <td>{{ @$results['timezone'] }}</td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.created_at') </td>
                                    <td>{{ Carbon\Carbon::parse(@$results['created_at'])->format('m-d-Y') }}</td>
                                </tr>



                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">h323 @lang('common.password')</td>
                                    <td>{{ @$results['h323_password'] }}</td>
                                </tr>

                                <tr>
                                    <td>11</td>
                                    <td class="propertiesname">@lang('zoom::zoom.encrypted_password')</td>
                                    <td>{{ @$results['encrypted_password'] }}</td>
                                </tr>
                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('zoom::zoom.host_video')</td>
                                    <td>{{ @$results['host_video'] == false ? 'False' : 'True' }}</td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('zoom::zoom.participant_video')</td>
                                    <td>{{ @$results['participant_video'] == false ? 'False' : 'True' }}</td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('zoom::zoom.cn_mettings')</td>
                                    <td>{{ @$results['cn_mettings'] == false ? 'False' : 'True' }}</td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('zoom::zoom.in_mettings')</td>
                                    <td>{{ @$results['in_mettings'] == false ? 'False' : 'True' }}</td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('zoom::zoom.join_before_host')</td>
                                    <td>{{ @$results['join_before_host'] == false ? 'False' : 'True' }}</td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('zoom::zoom.mute_upon_entry')</td>
                                    <td>{{ @$results['mute_upon_entry'] == false ? 'False' : 'True' }}</td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('zoom::zoom.watermark')</td>
                                    <td>{{ @$results['watermark'] == false ? 'False' : 'True' }}</td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('zoom::zoom.use_pmi')</td>
                                    <td>{{ @$results['use_pmi'] == false ? 'False' : 'True' }}</td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('zoom::zoom.audio_options')</td>
                                    <td>{{ @$results['audio'] }}</td>
                                </tr>
                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('zoom::zoom.auto_recording')</td>
                                    <td>{{ @$results['auto_recording'] }}</td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('zoom::zoom.enforce_login')</td>
                                    <td>{{ @$results['enforce_login'] == false ? 'False' : 'True' }}</td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('zoom::zoom.enforce_login_domains') </td>
                                    <td>{{ @$results['enforce_login_domains'] == false ? 'False' : 'True' }}</td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('zoom::zoom.alternative_hosts')</td>
                                    <td>{{ @$results['alternative_hosts'] == false ? 'False' : 'True' }}</td>
                                </tr>
                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('zoom::zoom.waiting_room')</td>
                                    <td>{{ @$results['waiting_room'] == false ? 'False' : 'True' }}</td>
                                </tr>
                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('zoom::zoom.meeting_authentication')</td>
                                    <td>{{ @$results['meeting_authentication'] == false ? 'False' : 'True' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
