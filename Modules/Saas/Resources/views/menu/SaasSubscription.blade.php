<li>
    <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
        <div class="nav_icon_small">
            <span class="flaticon-analytics"></span>
        </div>
        <div class="nav_title">
            @lang('saas::saas.subscription')
        </div>
    </a>
    <ul class="list-unstyled">
        <li>
            <a href="{{url('subscription/packages')}}">@lang('saas::saas.package')</a>
        </li>

        <li>
            <a href="{{url('subscription/payment-method-setting')}}">@lang('saas::saas.payment_method_settings')</a>
        </li>
        <li>
            <a href="{{url('subscription/settings')}}"> @lang('saas::saas.settings')</a>
        </li>

        <li>
            <a href="{{url('subscription/trial-institutions')}}"> @lang('saas::saas.trial_institutes')</a>
        </li>
        <li>
            <a href="{{url('subscription/school-payments')}}">  @lang('saas::saas.all_payments')</a>
        </li>

        <li>
            <a href="{{url('subscription/payment-history')}}">  @lang('saas::saas.payment_history')</a>
        </li>
    </ul>
</li>
