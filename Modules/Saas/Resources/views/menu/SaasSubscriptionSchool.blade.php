<li>
    <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">

    <div class="nav_icon_small">
            <span class="flaticon-analytics"></span>
        </div>
        <div class="nav_title">
            @lang('saas::saas.subscription')
        </div>
    </a>
    <ul class="list-unstyled" >
        <li>
            <a href="{{url('subscription/package-list')}}">@lang('saas::saas.packages')</a>
        </li>
        <li>
            <a href="{{url('subscription/history')}}">@lang('saas::saas.payment_history')</a>
        </li>
    </ul>
</li>
