
@if(userPermission('zoom') && menuStatus(554))
    <li data-position="{{menuPosition(554)}}" class="sortable_li">
        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
            <div class="nav_icon_small">
                <span class="flaticon-reading"></span>
            </div>
            <div class="nav_title">
                <span>@lang('common.virtual_class')</span>
                @if (config('app.app_sync'))
                    <span class="demo_addons">Addon</span>
                @endif
            </div>
        </a>
        <ul class="list-unstyled">
            @if(userPermission('zoom.virtual-class') && menuStatus(555))
                <li data-position="{{menuPosition(555)}}">
                    <a href="{{ route('zoom.virtual-class')}}">@lang('common.virtual_class')</a>
                </li>
            @endif
            @if(userPermission('zoom.meetings') && menuStatus(560))
                <li data-position="{{menuPosition(560)}}">
                    <a href="{{ route('zoom.meetings') }}">@lang('zoom::zoom.virtual_meeting')</a>
                </li>
            @endif
            @if(userPermission('zoom.virtual.class.reports.show') && menuStatus(565))
                <li data-position="{{menuPosition(565)}}">
                    <a href="{{ route('zoom.virtual.class.reports.show') }}">@lang('zoom::zoom.class_reports')</a>
                </li>
            @endif
            {{-- @if(userPermission(565) && menuStatus(565))
            <li data-position="{{menuPosition(565)}}">
                <a href="{{ route('zoom.virtual.class.reports.show') }}">@lang('zoom::zoom.Recorder_file')</a>
            </li>
            @endif --}}


            @if(userPermission('zoom.meeting.reports.show') && menuStatus(567))
                <li data-position="{{menuPosition(567)}}">
                    <a href="{{ route('zoom.meeting.reports.show') }}">@lang('zoom::zoom.meeting_reports')</a>
                </li>
            @endif
            @if(userPermission('zoom.settings') && menuStatus(569))
                <li data-position="{{menuPosition(569)}}">
                    <a href="{{ route('zoom.settings') }}">@lang('zoom::zoom.settings')</a>
                </li>
            @endif
        </ul>
    </li>
    <!-- Zoom Menu  -->
@endif
