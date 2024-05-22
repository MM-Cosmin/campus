@php
    use App\SmGeneralSettings;
    $school_settings= SmGeneralSettings::where('school_id',Auth::user()->school_id)->first();
    if (Auth::user() == "") { header('location:' . url('/login')); exit(); }
    Session::put('permission', App\GlobalVariable::GlobarModuleLinks());
@endphp
<input type="hidden" name="url" id="url" value="{{url('/')}}">
<nav id="sidebar" class="sidebar">
    <div class="sidebar-header update_sidebar">
        <a href="{{url('/')}}">
          <img  src="{{ file_exists(@$school_settings->logo) ? asset($school_settings->logo) : asset('public/uploads/settings/logo.png') }}" alt="logo">
        </a>
        <a id="close_sidebar" class="d-lg-none">
            <i class="ti-close"></i>
        </a>
    </div>

    {{-- {{ Auth::user()->role_id }} --}}
    <ul id="sidebar_menu" class="list-unstyled components metismenu">
		<li>
			<a href="javascript:void(0)" aria-expanded="false" class="has-arrow">
				<div class="nav_icon_small">
					<span class="flaticon-analytics"></span>
				</div>
				<div class="nav_title">
					@lang('saas::saas.subscription')
				</div>
		    </a>
		    <ul class="list-unstyled">
		        <li>
		            <a href="{{route('subscription/package-list')}}">@lang('saas::saas.packages')</a>
		        </li>
		        <li>
		            <a href="{{route('subscription/history')}}">@lang('saas::saas.payment_history')</a>
		        </li>
		    </ul>
		</li>
	</ul>
</nav>
