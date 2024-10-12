@extends('backEnd.master')
@section('title')
    @lang('bbb::bbb.meeting_record_list')
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
                <h1>@lang('bbb::bbb.meeting_record_list') </h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('bbb::bbb.bbb')</a>
                    <a href="#"> @lang('bbb::bbb.meeting_record_list') </a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box">
                        <div class="row mt-40">
                            <div class="col-lg-12">
                                <x-table>
                                <table id="table_id" class="table" cellspacing="0" width="100%">
                                    <thead>
    
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('common.meeting_id')</th>
                                            <th>@lang('common.topic')</th>
                                            <th>@lang('bbb::bbb.date_|_time')</th>
                                            <th>@lang('bbb::bbb.total_participants')</th>
                                            <th>@lang('common.url')</th>
                                        </tr>
    
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                        @endphp
                                        @foreach ($recorList as $meeting)
                                            @php
                                                $classSection = Modules\BBB\Entities\BbbMeeting::classSection($meeting['meetingID']);
                                            @endphp
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $meeting['meetingID'] }}</td>
                                                </td>
                                                <td>{{ $meeting['name'] }}</td>
                                                <td>{{ $classSection->date }} | {{ $classSection->time }}</td>
                                                <td class="text-center">{{ $meeting['participants'] }}</td>
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
        </div>
    </section>
@endsection

@include('backEnd.partials.data_table_js')

{{--  --}}
