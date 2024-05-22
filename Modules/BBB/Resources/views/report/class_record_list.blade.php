@extends('backEnd.master')
@section('title')
    @lang('bbb::bbb.class_record_list')
@endsection
@section('css')
    <style>
        .propertiesname {
            text-transform: uppercase;
        }

        . .recurrence-section-hide {
            display: none !important
        }
    </style>
@endsection

@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('bbb::bbb.virtual_class_record_list') </h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('bbb::bbb.bbb')</a>
                    <a href="#">@lang('bbb::bbb.virtual_class_record_list') </a>
                </div>
            </div>
        </div>
    </section>



    @if (auth()->user()->role_id == 2 || auth()->user()->role_id == 3)
        <section class="admin-visitor-area">
            <div class="container-fluid p-0">
                <div class="row">

                    <div class="col-lg-12 student-details up_admin_visitor">
                        <ul class="nav nav-tabs tabs_scroll_nav ml-0" role="tablist">

                            @foreach ($records as $key => $record)
                                <li class="nav-item">
                                    <a class="nav-link @if ($key == 0) active @endif "
                                        href="#tab{{ $key }}" role="tab"
                                        data-toggle="tab">{{ $record->class->class_name }}
                                        ({{ $record->section->section_name }})
                                    </a>
                                </li>
                            @endforeach

                        </ul>


                        <!-- Tab panes -->
                        <div class="tab-content mt-40">
                            <!-- Start Fees Tab -->
                            @foreach ($records as $key => $record)
                                <div role="tabpanel"
                                    class="tab-pane fade  @if ($key == 0) active show @endif"
                                    id="tab{{ $key }}">

                                    <div class="container-fluid p-0">
                                        <div class="white-box">
                                            <div class="row">
                                                <div class="col-lg-12 mt-40">
                                                    <x-table>
                                                        <table id="table_id" class="table" cellspacing="0" width="100%">
                                                            <thead>
     
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>@lang('common.meeting_id')</th>
                                                                    <th>@lang('common.class_section')</th>
                                                                    <th>@lang('common.topic')</th>
                                                                    <th>@lang('bbb::bbb.date_|_time')</th>
                                                                    @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 5 || Auth::user()->role_id == 4)
                                                                        <th>@lang('bbb::bbb.total_participants')</th>
                                                                    @endif
                                                                    <th>@lang('common.url')</th>
                                                                </tr>
    
                                                            </thead>
                                                            <tbody>
                                                                @php
                                                                    $i = 1;
                                                                @endphp
                                                                @foreach ($record->StudentBbbVirtualClassRecord as $meeting)
                                                                    @php
                                                                        $classSection = Modules\BBB\Entities\BbbVirtualClass::classSection($meeting['meetingID']);
                                                                    @endphp
                                                                    <tr>
                                                                        <td>{{ $i++ }}</td>
                                                                        <td>{{ $meeting['meetingID'] }}</td>
                                                                        <td>{{ $classSection->class->class_name }}
                                                                            ({{ $classSection->section_id != null ? $classSection->section->section_name : 'All sections' }})
                                                                        </td>
                                                                        <td>{{ $meeting['name'] }}</td>
                                                                        <td>{{ $classSection->date }} |
                                                                            {{ $classSection->time }}</td>
                                                                        @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 5 || Auth::user()->role_id == 4)
                                                                            <td class="text-center">
                                                                                {{ $meeting['participants'] }}</td>
                                                                        @endif
                                                                        <td>
                                                                            @if(@$meeting['playback']['format']['url'])
                                                                            <a href="{{ url($meeting['playback']['format']['url']) }}"
                                                                                target="_blank">@lang('bbb::bbb.vedio_link') </a>
                                                                            @endif
    
                                                                        </td>
    
    
    
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </x-table>
                                                </div>
    
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="admin-visitor-area">
            <div class="container-fluid p-0">
                <div class="white-box">
                    <div class="row">
                        <div class="col-lg-12 mt-40">
                                <x-table>
                                <table id="table_id" class="table" cellspacing="0" width="100%">
                                    <thead>
    
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('common.meeting_id')</th>
                                            <th>@lang('common.class_section')</th>
                                            <th>@lang('common.topic')</th>
                                            <th>@lang('bbb::bbb.date_|_time')</th>
                                            @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 5 || Auth::user()->role_id == 4)
                                                <th>@lang('bbb::bbb.total_participants')</th>
                                            @endif
                                            <th>@lang('common.url')</th>
                                        </tr>
    
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                        @endphp
                                        @foreach ($recorList as $meeting)
                                            @php
                                                $classSection = Modules\BBB\Entities\BbbVirtualClass::classSection($meeting['meetingID']);
                                            @endphp
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $meeting['meetingID'] }}</td>
                                                <td>{{ $classSection->class->class_name }}
                                                    ({{ $classSection->section_id != null ? $classSection->section->section_name : 'All sections' }})
                                                </td>
                                                <td>{{ $meeting['name'] }}</td>
                                                <td>{{ $classSection->date }} | {{ $classSection->time }}</td>
                                                @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 5 || Auth::user()->role_id == 4)
                                                    <td class="text-center">{{ $meeting['participants'] }}</td>
                                                @endif
                                                <td>
                                                    @if(@$meeting['playback']['format']['url'])
                                                    <a href="{{ url($meeting['playback']['format']['url']) }}"
                                                        target="_blank">@lang('bbb::bbb.vedio_link') </a>
                                                    @endif
                                                </td>
    
    
    
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                </x-table>
                        </div>
    
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection

@include('backEnd.partials.data_table_js')

{{--  --}}
