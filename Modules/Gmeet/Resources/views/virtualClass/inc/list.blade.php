@php
    if (auth()->user()->role_id == 1 || userPermission('g-meet.virtual-class.store')) {
        $div = 'col-lg-9';
    } else {
        $div = 'col-lg-12';
    }
@endphp

<div class="{{ $div }}">

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
                            @if (!in_array(auth()->user()->role_id, [2, 3]))
                                @if (moduleStatusCheck('University'))
                                    <th>@lang('university::un.semester_label')</th>
                                    <th>@lang('university::un.department')</th>
                                    <th>@lang('common.section')</th>
                                @else
                                    <th>@lang('common.class_section')</th>
                                @endif
                            @endif
                            <th>@lang('common.topic')</th>
                            <th>@lang('common.date')</th>
                            <th>@lang('common.time')</th>
                            <th>@lang('common.duration')</th>
                            <th>@lang('gmeet::gmeet.start_join_before')</th>
                            <th>@lang('common.start_join')</th>
                            <th>@lang('common.actions')</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($meetings as $key => $meeting)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                @if (!in_array(auth()->user()->role_id, [2, 3]))
                                    @if (moduleStatusCheck('University'))
                                        <td>{{ @$meeting->semesterLabel->name }} -({{ @$meeting->unAcademic->name }})
                                        </td>
                                        <td>{{ @$meeting->unDepartment->name }}</td>
                                        <td>{{ @$meeting->unSection->section_name }}</td>
                                    @else
                                        <td>{{ $meeting->class->class_name . '(' . $meeting->section->section_name . ')' }}
                                        </td>
                                    @endif
                                @endif
                                <td>{{ $meeting->topic }}</td>
                                <td>{{ $meeting->date_of_meeting }}</td>
                                <td>{{ $meeting->time_of_meeting }}</td>
                                <td>{{ $meeting->meeting_duration }} @lang('common.min')</td>
                                <td>{{ $meeting->time_before_start }} @lang('common.min') </td>
                                <td>
                                    @if ($meeting->CurrentStatus == 'started')
                                        <a class="primary-btn small bg-success text-white border-0"
                                            href="{{ $meeting->gmeet_url }}" target="_blank"
                                            rel="noopener noreferrer">{{ auth()->user()->id == $meeting->created_by ? __('common.start') : __('common.join') }}</a>
                                    @elseif($meeting->CurrentStatus == 'waiting')
                                        <a class="primary-btn small bg-info text-white border-0"
                                            href="{{ $meeting->gmeet_url }}" target="_blank"
                                            rel="noopener noreferrer">{{ __('common.waiting') }}</a>
                                    @elseif($meeting->CurrentStatus == 'closed')
                                        <a class="primary-btn small bg-warning text-white border-0"
                                            rel="noopener noreferrer">{{ __('common.closed') }}</a>
                                    @else
                                    @endif

                                </td>

                                <td>
                                    <x-drop-down>

                                        <a class="dropdown-item" target="_blank"
                                            href="{{ route('g-meet.virtual-class.show', $meeting->id) }}">@lang('common.view')</a>

                                        @if (userPermission('g-meet.virtual-class.edit'))
                                            <a class="dropdown-item"
                                                href="{{ route('g-meet.virtual-class.edit', $meeting->id) }}">@lang('common.edit')</a>
                                        @endif
                                        @if (userPermission("g-meet.upload-document"))
                                            <a class="dropdown-item modalLink" data-modal-size="modal-md"
                                                title="@lang('gmeet::gmeet.upload_recorded_video')"
                                                href="{{ route('g-meet.upload-document-modal', [$meeting->id, 'type' => 'class']) }}">@lang('gmeet::gmeet.upload_recorded_video')</a>
                                        @endif
                                        @if (userPermission("g-meet.virtual-class.destroy"))
                                            <a class="dropdown-item" data-toggle="modal"
                                                data-target="#d{{ $meeting->id }}"
                                                href="#">@lang('common.delete')</a>
                                        @endif
                                    </x-drop-down>
                                </td>
                            </tr>


                            @if (userPermission("g-meet.virtual-class.destroy"))
                                <div class="modal fade admin-query" id="d{{ $meeting->id }}">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">@lang('gmeet::gmeet.delete_virtual_class')</h4>
                                                <button type="button" class="close"
                                                    data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <h4>@lang('common.are_you_sure_delete') ? </h4>
                                                </div>
                                                <div class="mt-40 d-flex justify-content-between">
                                                    <button type="button" class="primary-btn tr-bg"
                                                        data-dismiss="modal">@lang('common.cancel')</button>

                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['g-meet.virtual-class.destroy', $meeting->id]]) !!}
                                                    <button class="primary-btn fix-gr-bg"
                                                        type="submit">@lang('common.delete')</button>
                                                    {!! Form::close() !!}
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
