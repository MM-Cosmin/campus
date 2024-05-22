@extends('backEnd.master')
@section('title')
@lang('common.virtual_class_details')
@endsection
@section('mainContent')
<style>
    .propertiesname{
        text-transform: uppercase;
        font-weight:bold;
    }
    </style>
<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('common.virtual_class_details')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">{{__('dashboard.Dashboard')}}</a>
                <a href="#">@lang('common.virtual_class')</a>
                <a href="#">@lang('common.details')</a>
            </div>
        </div>
    </div>
</section>


<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-md-10">
                <h3 class="mb-30"> @lang('common.topic') : {{@$results['topic']}}</h3>
            </div>
            <div class="col-md-2 pull-right  text-right">
                @if(userPermission('bbb.meetings.edit'))
                    <a href="{{ route('bbb.meetings.edit', $localMeetingData->id) }}" class="primary-btn small fix-gr-bg "> <span class="ti-pencil-alt"></span> @lang('common.edit') </a>
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
                            {{--  <tr>
                                <td colspan="3">General Informations</td>
                            </tr>  --}}
                            @php $sl = 1 @endphp
                            <tr>
                                <td>{{ $sl++ }} </td> <td class="propertiesname">@lang('common.topic')</td> <td>{{@$results['topic']}}</td>
                            </tr>
                            <tr>
                                <td>{{ $sl++ }} </td> <td class="propertiesname">@lang('common.participants')</td> <td> {{ $localMeetingData->participatesName }}  </td>
                            </tr>
                            @if($localMeetingData->attached_file)
                                <tr>
                                    <td>{{ $sl++ }} </td> <td class="propertiesname"> @lang('common.attached_file') </td> <td> <a href="{{ asset($localMeetingData->attached_file) }}" download="" ><i class="fa fa-download mr-1"></i> Download</a>  </td>
                                </tr>
                            @endif
                            <tr>
                                <td> {{ $sl++ }} </td> <td class="propertiesname">@lang('common.start_date_time')</td> <td>{{ $localMeetingData->MeetingDateTime }}</td>
                            </tr>
                             <tr>
                                <td>{{ $sl++ }} </td>
                                <td class="propertiesname">@lang('common.start_join')</td>
                                <td>@lang('meeting_start_before') {{ $localMeetingData->time_start_before==null ? '10' :  $localMeetingData->time_start_before}} @lang('common.min')</td>
                            </tr>
                            <tr>
                                <td> {{ $sl++ }} </td> <td class="propertiesname">@lang('common.meeting_id')</td> <td>{{ @$results['id'] }}</td>
                            </tr>
                            <tr>
                                <td>{{ $sl++ }} </td> <td class="propertiesname">@lang('common.password')</td> <td>{{@$results['password']}}</td>
                            </tr>
                         
                                <tr>
                                    <td>{{ $sl++ }} </td> <td class="propertiesname">@lang('common.start_join')</td> <td>
                                        @if($localMeetingData->getCurrentStatusAttribute() == 'started')

                                 @if (Auth::user()->role_id == 1 || Auth::user()->id == $localMeetingData->created_by)
                                <a target="_blank" class="primary-btn small bg-success text-white border-0" href="{{ route('bbb.meeting.start', $localMeetingData->meeting_id) }}">


                                        @lang('common.start')
                                        </a>
                                    @else
                                      
                                       <a target="_blank" class="primary-btn small bg-success text-white border-0" href="{{ route('bbb.meeting.join', $localMeetingData->meeting_id) }}">  @lang('common.join') </a>
                                    
                                    @endif
                            @elseif( $localMeetingData->getCurrentStatusAttribute() == 'waiting')
                                <a href="#Closed" class="primary-btn small bg-info text-white border-0">Waiting</button>
                            @else
                                <a href="#Closed" class="primary-btn small bg-warning text-white border-0">Closed</button>
                            @endif
                                    </td>
                                </tr>
                            <tr>
                                <td>{{ $sl++ }} </td> <td class="propertiesname">@lang('common.host_id')</td> <td>{{@$results['host_id']}}</td>
                            </tr>

                            <tr>
                                <td>{{ $sl++ }} </td> <td class="propertiesname">@lang('common.description')</td> <td> {{ $localMeetingData->description }}  </td>
                            </tr>

                            <tr>
                                <td>{{ $sl++ }} </td> <td class="propertiesname">@lang('common.status')</td> <td>{{@$results['status']}}</td>
                            </tr>

                            <tr>
                                <td>{{ $sl++ }} </td> <td class="propertiesname">@lang('common.timezone')</td> <td>{{@$results['timezone']}}</td>
                            </tr>

                            <tr>
                                <td>{{ $sl++ }} </td> <td class="propertiesname">@lang('common.created_at') </td> <td>{{Carbon\Carbon::parse(@$results['created_at'])->format('m-d-Y')}}</td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
