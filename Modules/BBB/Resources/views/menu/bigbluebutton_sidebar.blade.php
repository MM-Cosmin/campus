
@if(userPermission('bbb') && menuStatus(850))
    <li data-position="{{menuPosition(850)}}" class="sortable_li">

    <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
        <div class="nav_icon_small">
            <span class="flaticon-reading"></span>
        </div>
        <div class="nav_title">
            <span>@lang('bbb::bbb.bbb')</span>
            @if (config('app.app_sync'))
                <span class="demo_addons">Addon</span>
            @endif
        </div>
    </a>
    <ul class="list-unstyled">
        @if(userPermission('bbb.virtual-class') && menuStatus(851))
            <li data-position="{{menuPosition(851)}}">
                <a href="{{ route('bbb.virtual-class')}}">@lang('common.virtual_class')</a>
            </li>
        @endif
        @if(userPermission('bbb.meetings') && menuStatus(856))
            <li data-position="{{menuPosition(856)}}">
                <a href="{{ route('bbb.meetings') }}">@lang('bbb::bbb.virtual_meeting')</a>
            </li>
        @endif
        @if(userPermission('bbb.virtual.class.reports.show') && menuStatus(862))
            <li data-position="{{menuPosition(862)}}">
                <a href="{{ route('bbb.virtual.class.reports.show') }}">@lang('bbb::bbb.class_reports')</a>
            </li>
        @endif

        @if(userPermission('bbb.class.recording.list') && menuStatus(868))
        <li data-position="{{menuPosition(868)}}">
            <a href="{{ route('bbb.class.recording.list') }}"> @lang('bbb::bbb.class_record_list')</a>
        </li>
         @endif
     
         @if(userPermission('bbb.meeting.recording.list') && menuStatus(869))
            <li data-position="{{menuPosition(869)}}">
                <a href="{{ route('bbb.meeting.recording.list') }}"> @lang('bbb::bbb.meeting_record_list')</a>
            </li>
        @endif

        @if(userPermission('bbb.meeting.reports.show') && menuStatus(866))
            <li data-position="{{menuPosition(866)}}">
                <a href="{{ route('bbb.meeting.reports.show') }}">@lang('bbb::bbb.meeting_reports')</a>
            </li>
        @endif
        
        @if(userPermission('bbb.settings') && menuStatus(866))
            <li data-position="{{menuPosition(866)}}">
                <a href="{{ route('bbb.settings') }}">@lang('bbb::bbb.settings')</a>
            </li>
        @endif
    </ul>
</li>
<!-- bbb Menu  -->
@endif
