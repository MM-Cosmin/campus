@if(userPermission(542) && menuStatus(542) )
    <li data-position="{{menuPosition(542)}}" class="sortable_li">
        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
            <div class="nav_icon_small">
                <span class="flaticon-reading"></span>
            </div>
            <div class="nav_title">
                <span>@lang('student.registration')</span>
                @if (config('app.app_sync'))
                    <span class="demo_addons">Addon</span>
                @endif
            </div>
        </a>
        <ul class="list-unstyled">
            @if(userPermission('parentregistration.student-list') && menuStatus(543))
                <li data-position="{{menuPosition(543)}}">
                    <a href="{{url('parentregistration/student-list')}}"> @lang('common.student_list')</a>
                </li>
            @endif
       
            @if(userPermission('parentregistration/settings') && menuStatus(547))
                <li data-position="{{menuPosition(547)}}">
                    <a href="{{url('parentregistration/settings')}}"> @lang('student.settings')</a>
                </li>
            @endif
         
        </ul>
    </li>
@endif
