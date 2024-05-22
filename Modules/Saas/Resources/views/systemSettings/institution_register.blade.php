<?php
$setting = generalSetting();
if (isset($setting->copyright_text)) {
    $copyright_text = $setting->copyright_text;
} else {
    $copyright_text = 'Copyright © 2019 All rights reserved | This template is made with by Codethemes';
}
if (isset($setting->logo)) {
    $logo = $setting->logo;
} else {
    $logo = 'public/uploads/settings/logo.png';
}

if (isset($setting->favicon)) {
    $favicon = $setting->favicon;
} else {
    $favicon = 'public/backEnd/img/favicon.png';
}

$login_background = App\SmBackgroundSetting::where([['is_default', 1], ['title', 'Login Background']])->first();

if (empty($login_background)) {
    $css = "background: url(" . url('public/backEnd/img/login-bg.jpg') . ")  no-repeat center; background-size: cover; ";
} else {
    if (!empty($login_background->image)) {
        $css = "background: url('" . url($login_background->image) . "')  no-repeat center;  background-size: cover;";
    } else {
        $css = "background:" . $login_background->color;
    }
}


$active_style = App\SmStyle::where("school_id", 1)->where('is_active', 1)->first();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{asset(generalSetting()->favicon)}}" type="image/png" />
    <title>@lang('saas::saas.new_institution_register')</title>
    <meta name="_token" content="{!! csrf_token() !!}" />
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/bootstrap.css" />
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/themify-icons.css" />
    <link rel="stylesheet" href="{{asset('landing/css/toastr.css')}}">
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/nice-select.css" />
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/js/select2/select2.css" />
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/fastselect.min.css" />
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/css/{{activeStyle()->path_main_style}}" />
</head>

<body class="login admin hight_100" style=" @if(activeStyle()->id==1) {{$css}} @endif ">
    <style>
        @media (max-width: 991px) {
            .login.admin.hight_100 .login-height .form-wrap {
                padding: 50px 8px;
            }

            .login-area .login-height {
                min-height: auto;
            }
        }

        label.error {
            position: absolute;
            top: 100%;
            text-align: center;
            left: 3%;
            color: red;
        }
    </style>

    <!--================ Start Login Area =================-->
    <section class="login-area up_login">

        <div class="container">

            <input type="hidden" id="url" value="{{url('/')}}">
            <div class="row login-height justify-content-center align-items-center">
                <div class="col-lg-8 col-md-8">
                    <div class="form-wrap text-center">
                        <div class="logo-container">
                            <a href="{{url('/')}}">
                                <img src="{{asset($setting->logo)}}" alt="" class="logoimage">
                            </a>
                        </div>
                        <h5 class="text-uppercase">@lang('saas::saas.registration') </h5>

                        <?php if (session()->has('message-success') != "") : ?>
                            <?php if (session()->has('message-success')) : ?>
                                <p class="text-success"><?php echo e(session()->get('message-success')); ?></p>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if (session()->has('message-danger') != "") : ?>
                            <?php if (session()->has('message-danger')) : ?>
                                <p class="text-danger"><?php echo e(session()->get('message-danger')); ?></p>
                            <?php endif; ?>
                        <?php endif; ?>
                        <form method="POST" class="" action="{{route('administrator/institution-register')}}" id="login">
                            <?php echo csrf_field(); ?>

                            <div class="form-group input-group mb-4 mx-3">
                                <span class="input-group-addon">
                                    <i class="ti-home"></i>
                                </span>
                                <input class="form-control{{ $errors->has('school_name') ? ' is-invalid' : '' }}" type="text" name='school_name' id="school_name" placeholder="Enter Institution Name" />
                                @if ($errors->has('school_name'))
                                <span class="invalid-feedback text-left pl-3" role="alert">
                                    <strong>{{ $errors->first('school_name') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group input-group mb-4 mx-3">
                                <span class="input-group-addon">
                                    <i class="ti-email"></i>
                                </span>
                                <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" type="text" name='email' id="email" placeholder="Enter email" />
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
                                <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" name='password' id="password" placeholder="Enter Password" />
                                @if ($errors->has('password'))
                                <span class="invalid-feedback text-left pl-3" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group input-group mb-4 mx-3">
                                <span class="input-group-addon">
                                    <i class="ti-key"></i>
                                </span>
                                <input class="form-control{{ $errors->has('cpassword') ? ' is-invalid' : '' }}" type="password" name='cpassword' id="cpassword" placeholder="Confirm Password" />
                                @if ($errors->has('cpassword'))
                                <span class="invalid-feedback text-left pl-3" role="alert">
                                    <strong>{{ $errors->first('cpassword') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group input-group mb-4 mx-3">
                                        <span class="input-group-addon">
                                            <i class="ti-package"></i>
                                        </span>

                                        <select class="primary_select form-control{{ $errors->has('package') ? ' is-invalid' : '' }}" id="" name="package">
                                            <option data-display="@lang('saas::saas.package') *" value="">@lang('saas::saas.package') *
                                            </option>
                                            @foreach($packages as $row)
                                            <option value="{{$row->id}}"> {{$row->package_name}} </option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>


                                <div class="col-lg-6">
                                    <div class="form-group input-group mb-4 mx-3">
                                        <span class="input-group-addon">
                                            <i class="ti-package"></i>
                                        </span>

                                        <select class="primary_select form-control{{ $errors->has('plans') ? ' is-invalid' : '' }}" id="" name="plans">
                                            <option data-display="Plans *" value="">@lang('saas::saas.plans') * </option>
                                            <option value="monthly_price">@lang('saas::saas.monthly')</option>
                                            <option value="quarterly_price">@lang('saas::saas.quarterly')</option>
                                            <option value="yearly_price">@lang('saas::saas.yearly')</option>
                                            <option value="lifetime_price">@lang('saas::saas.lifetime')</option>

                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="d-flex justify-content-between pl-30 pull-right">

                                <div class="pull-right">
                                </div>
                                <div class="pull-right">
                                    @lang('saas::saas.already_registered_click') <a class="text-white" href="{{route('login')}}">@lang('saas::saas.here')</a> @lang('saas::saas.to')
                                    @lang('saas::saas.login').
                                </div>
                            </div>

                            <div class="form-group mt-30 mb-30">
                                <button type="submit" class="primary-btn fix-gr-bg">
                                    <span class="ti-lock mr-2"></span>
                                    Register
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
    <footer class="footer_area">
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
    <script src="{{asset('public/backEnd/')}}/js/login.js"></script>
    <script src="{{asset('public/backEnd/')}}/js/validate.js"></script>
    <script src="{{asset('public/backEnd/')}}/js/additional.js"></script>
    <script src="{{asset('public/backEnd/')}}/vendors/js/nice-select.min.js"></script>
    <script>
        $('.primary-btn').on('click', function(e) {
            // Remove any old one
            $('.ripple').remove();

            // Setup
            var primaryBtnPosX = $(this).offset().left,
                primaryBtnPosY = $(this).offset().top,
                primaryBtnWidth = $(this).width(),
                primaryBtnHeight = $(this).height();

            // Add the element
            $(this).prepend("<span class='ripple'></span>");

            // Make it round!
            if (primaryBtnWidth >= primaryBtnHeight) {
                primaryBtnHeight = primaryBtnWidth;
            } else {
                primaryBtnWidth = primaryBtnHeight;
            }

            // Get the center of the element
            var x = e.pageX - primaryBtnPosX - primaryBtnWidth / 2;
            var y = e.pageY - primaryBtnPosY - primaryBtnHeight / 2;

            // Add the ripples CSS and start the animation
            $('.ripple')
                .css({
                    width: primaryBtnWidth,
                    height: primaryBtnHeight,
                    top: y + 'px',
                    left: x + 'px'
                })
                .addClass('rippleEffect');
        });
    </script>
    <script src="{{asset('backend/js/toastr.js')}}"></script>
    {!! Toastr::message() !!}

    <script>
        jQuery.validator.setDefaults({
            debug: true,
            success: "valid"
        });
        $("#login").validate({

            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6,
                },
                cpassword: {
                    required: true,
                    minlength: 6,
                },
                school_name: {
                    required: true,
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    </script>


</body>

</html>
