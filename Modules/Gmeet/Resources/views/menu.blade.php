@if(userPermission('gmeet') && menuStatus(1250))
<li data-position="{{menuPosition(554)}}" class="sortable_li">
    <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
        <div class="nav_icon_small">
            <span class="flaticon-reading"></span>
        </div>
        <div class="nav_title">
            <span>@lang('gmeet::gmeet.gmeet')</span>
            @if (config('app.app_sync'))
                <span class="demo_addons">Addon</span>
            @endif
        </div>
    </a>
    <ul class="list-unstyled">
        @if(userPermission('g-meet.virtual-class.index') && menuStatus(1251))
            <li data-position="{{menuPosition(1251)}}">
                <a href="{{ route('g-meet.virtual-class.index')}}">@lang('common.virtual_class')</a>
            </li>
        @endif
        @if(userPermission('g-meet.virtual-meeting.index') && menuStatus(1256))
            <li data-position="{{menuPosition(1256)}}">
                <a href="{{ route('g-meet.virtual-meeting.index') }}">@lang('common.virtual_meeting')</a>
            </li>
        @endif
        
      
        @if(userPermission('g-meet.virtual.class.reports.show') && menuStatus(1262))
            <li data-position="{{menuPosition(1262)}}">
                <a href="{{ route('g-meet.virtual.class.reports.show') }}">@lang('gmeet::gmeet.class_reports')</a>
            </li>
        @endif

         @if(userPermission('g-meet.virtual.meeting.reports.show') && menuStatus(1264))
            <li data-position="{{menuPosition(1264)}}">
                <a href="{{ route('g-meet.virtual.meeting.reports.show') }}">@lang('gmeet::gmeet.meeting_reports')</a>
            </li>
        @endif 
        @if(userPermission('g-meet.settings.index') && menuStatus(1266))
            <li data-position="{{menuPosition(1266)}}">
                <a href="{{ route('g-meet.settings.index') }}">@lang('common.settings')</a>
            </li>
        @endif
    </ul>
</li>
<!-- Zoom Menu  -->
@endif
