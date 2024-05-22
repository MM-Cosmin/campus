@php
	$gs = generalSetting();
@endphp
<!DOCTYPE html>
@php
    App::setLocale(getUserLanguage());
@endphp
<html lang="{{ app()->getLocale() }}" @if(isset ($ttl_rtl ) && $ttl_rtl ==1) dir="rtl" class="rtl" @endif >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{asset(generalSetting()->favicon)}}" type="image/png"/>
    <title>@lang('common.login')</title>
    <meta name="_token" content="{!! csrf_token() !!}"/>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/bootstrap.css" />
	<link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/themify-icons.css" />

    <link rel="stylesheet" href="{{url('/')}}/public/backEnd/vendors/css/nice-select.css" />
    <link rel="stylesheet" href="{{url('/')}}/public/backEnd/vendors/js/select2/select2.css" />

    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/toastr.min.css"/>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/css/{{activeStyle()->path_main_style}}" />
</head>
<body class="login admin hight_100"  style=" {{$css}} ">
<style>
	@media (max-width: 991px){
		.login.admin.hight_100 .login-height .form-wrap {
			padding: 50px 8px;
		}
		.login-area .login-height {
			min-height: auto;
		}
	}
	body{
		height: 100%;
	}
	hr{
		background: linear-gradient(90deg, #7c32ff 0%, #c738d8 51%, #7c32ff 100%) !important;
    	height: 1px !important;
	}
	.invalid-select strong{
		font-size: 11px !important;
	}
</style>

    <!--================ Start Login Area =================-->
	<section class="login-area up_login">

		<div class="container">

			@if(Illuminate\Support\Facades\Config::get('app.app_sync'))

			<div class="row mt-40">

				@php
					$users1 = App\User::where('role_id',1)->where('school_id', 1)->first();
					$email = @$users1->email;
					$password="123456";
				@endphp
					@if($users1)
					
						<div class="col-md-3 mt-40">
							<h4 class="text-center text-white">@lang('saas::saas.system_admin')</h4>	
							<hr>
							<form method="POST" class="" action="{{route('login')}}">
								@csrf
								<input type="hidden" class=" small form-control mt-10" name="school_id" value="1" readonly="true" required>
								<input type="hidden" class="form-control mt-10" name="email" value="{{@$email}}">
								<input type="hidden" class="form-control mt-10" name="password" value="{{$password}}">
								<input type="submit" class="primary-btn fix-gr-bg  mt-10 pull-right text-center col-lg-12" name="login" value="Super Admin @lang('common.login')">
							</form>

						</div>
					@endif

					@php
						$users1 = App\User::where('role_id', 1)->where('school_id', 2)->first();
						$email = @$users1->email;
						$password="123456";
					@endphp
					@if($users1)
						<div class="col-md-3 mt-40">
							<h4 class="text-center text-white">@lang('common.school') 2</h4>
							<hr>
							<form method="POST" class="" action="{{route('login')}}">
								@csrf
								<input type="hidden" class=" small form-control mt-10" name="school_id" value="2" readonly="true" required>
								<input type="hidden" class=" small form-control mt-10" name="email" value="{{@$email}}" readonly="true" required>
								<input type="hidden" class="form-control mt-10" name="password" value="{{$password}}" readonly>
								<input type="submit" class="primary-btn fix-gr-bg  mt-10 pull-right text-center col-lg-12" name="login" value="@lang('common.login')">
							</form>
						</div>
					@endif




					@php
						$users1 = App\User::where('role_id', 1)->where('school_id', 3)->first();
						$email = @$users1->email;
						$password="123456";
					@endphp
					@if($users1)
						<div class="col-md-3 mt-40">
							<h4 class="text-center text-white">@lang('common.school') 3</h4>
							<hr>
							<form method="POST" class="" action="{{route('login')}}">
								@csrf
								<input type="hidden" class=" small form-control mt-10" name="school_id" value="3" readonly="true" required>
								<input type="hidden" class=" small form-control mt-10" name="email" value="{{@$email}}" readonly="true" required>
								<input type="hidden" class="form-control mt-10" name="password" value="{{$password}}" readonly>
								<input type="submit" class="primary-btn fix-gr-bg  mt-10 pull-right text-center col-lg-12" name="login" value="@lang('common.login')">
							</form>
						</div>

					@endif


					@php
						$users1 = App\User::where('role_id', 1)->where('school_id', 4)->first();
						$email = @$users1->email;
						$password="123456";
					@endphp
					@if($users1)
						<div class="col-md-3 mt-40">
							<h4 class="text-center text-white">@lang('common.school') 4</h4>
							<hr>

							<form method="POST" class="" action="{{route('login')}}">
								@csrf
								<input type="hidden" class=" small form-control mt-10" name="school_id" value="4" readonly="true" required>
								<input type="hidden" class=" small form-control mt-10" name="email" value="{{@$email}}" readonly="true" required>
								<input type="hidden" class="form-control mt-10" name="password" value="{{$password}}" readonly>
								<input type="submit" class="primary-btn fix-gr-bg  mt-10 pull-right text-center col-lg-12" name="login" value="@lang('common.login')">
							</form>

						</div>
					@endif
			</div>
			@endif


			<input type="hidden" id="url" value="{{url('/')}}">
			<div class="row login-height justify-content-center align-items-center">
				<div class="col-lg-5 col-md-8">
					<div class="form-wrap text-center">
						<div class="logo-container">
							<a href="{{url('/')}}">
								<img src="{{asset(@$setting->logo)}}" alt="" class="logoimage">
							</a>
						</div>

						<h5 class="text-uppercase">@lang('common.login_details')</h5>

						<?php if(session()->has('message-success') != ""): ?>
		                    <?php if(session()->has('message-success')): ?>
		                    <p class="text-success"><?php echo e(session()->get('message-success')); ?></p>
		                    <?php endif; ?>
		                <?php endif; ?>
		                <?php if(session()->has('message-danger') != ""): ?>
		                    <?php if(session()->has('message-danger')): ?>
		                    <p class="text-danger"><?php echo e(session()->get('message-danger')); ?></p>
		                    <?php endif; ?>
		                <?php endif; ?>
						<form method="POST" class="" action="<?php echo e(route('login')); ?>">
                        <?php echo csrf_field(); ?>

                        	<div class="form-group input-group mb-4 mx-3">
								<div class="primary_input color-for-green">
									
                                        <select class="primary_select form-control{{ $errors->has('school_id') ? ' is-invalid' : '' }}" name="school_id" id="select-school">
											<option data-display="@lang('common.select_school') *" value="">@lang('common.select_school')*</option>
											<option value="{{$sch->id}}"> {{$sch->school_name}} </option>
                                            @foreach($schools as $school)
                                            <option value="{{$school->id}}"> {{$school->school_name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($errors->has('school_id'))
                                    <span class="invalid-select text-left text-danger pl-3" role="alert">
                                        <strong>{{ $errors->first('school_id') }}</strong>
                                    </span>
                                @endif
							</div>
							<input type="hidden" name="username" id="username-hidden">



							<div class="form-group input-group mb-4 mx-3">
								<span class="input-group-addon">
									<i class="ti-email"></i>
								</span>
								<input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" type="text" name='email' id="email-address" placeholder="@lang('common.enter_email_address')"/>
								@if ($errors->has('email'))
                                    <span class="invalid-feedback text-left pl-3" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
							</div>

							<div class="form-group input-group mb-4 mx-3">
								<span class="input-group-addon">
									<i class="ti-key"></i>
								</span>
								<input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" name='password' id="password" placeholder="@lang('common.enter_password')"/>
								@if ($errors->has('password'))
                                    <span class="invalid-feedback text-left pl-3" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
							</div>

							<div class="d-flex justify-content-between pl-30">
								<div class="checkbox">
									<input class="form-check-input" type="checkbox" name="remember" id="rememberMe" {{ old('remember') ? 'checked' : '' }}>
									<label for="rememberMe">@lang('common.remember_me')</label>
								</div>
								<div>
									<a href="<?php echo e(route('recoveryPassord')); ?>">@lang('common.forget_password') ?</a>
								</div>
							</div>

							<div class="form-group mt-30 mb-30">
								<button type="submit" class="primary-btn fix-gr-bg">
									<span class="ti-lock mr-2"></span>
									@lang('common.login')
                                </button>
							</div>
						</form>
					</div>

				</div>
			</div>
		</div>
	</section>
	<!--================ Start End Login Area =================-->

	<!--================ Footer Area =================-->
	<footer class="footer_area mt-30">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-12 text-center">
					<p>{!! generalSetting()->copyright_text !!}</p>
				</div>
			</div>
		</div>
	</footer>
	<!--================ End Footer Area =================-->

    <script src="{{asset('public/backEnd/')}}/vendors/js/jquery-3.2.1.min.js"></script>
    <script src="{{asset('public/backEnd/')}}/vendors/js/popper.js"></script>
	<script src="{{asset('public/backEnd/')}}/vendors/js/bootstrap.min.js"></script>
	<script src="{{url('/')}}/public/backEnd/vendors/js/nice-select.min.js"></script>
	<script src="{{asset('public/backEnd/')}}/js/login.js"></script>
	<script type="text/javascript" src="{{asset('public/backEnd/')}}/vendors/js/toastr.min.js"></script>

	<script type="text/javascript">
		if ($('.niceSelect').length) {
            $('.niceSelect').niceSelect();
        }

        $(document).ready(function() {
	        $("#email-address").keyup(function(){

			  $("#username-hidden").val($(this).val());
			});
	    });


	</script>

	{!! Toastr::message() !!}


</body>
</html>
