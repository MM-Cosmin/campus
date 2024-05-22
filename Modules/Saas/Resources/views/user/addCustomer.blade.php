@extends('backEnd.master')
@section('mainContent')

<style type="text/css">
    .form-control:disabled, .form-control[readonly] {
    background-color: #ffffff;
    opacity: 1;
}

</style>
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('common.edit') Profile</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('common.view_profile')</a>
                <a href="#"> @lang('common.edit') Profile</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                <div class="main-title">
                    <h3 class="mb-30">  User Details</h3>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                
                @if(isset($editData))
                    <a href="{{url('view-customer',$editData->id)}}" class="primary-btn small fix-gr-bg">  @lang('common.view') </a> 
                @endif   
            </div>
  
        </div>

        @if(isset($editData))
        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'ticket/profile-update', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
        <input type="hidden" value="{{$editData->id}}" name="staff_id">
        @endif

        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">  
        <input type="hidden" name="date_of_joining" id="date_of_joining" value="{{date('Y-m-d')}}">  
        <div class="row">
            <div class="col-lg-12"> 
              <div class="white-box">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h4>Basic Information</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-20">
                        <div class="col-lg-12">
                            <hr>
                        </div>
                    </div>

                    <div class="row mb-40">
                        <div class="col-lg-3">
                            <div class="primary_input">
                                <input class="primary_input_field {{$errors->has('first_name') ? 'is-invalid' : ' '}}" type="text"  name="first_name" value="{{isset($editData)?$editData->first_name:old('first_name')}}">
                                <span class="focus-border"></span>
                                <label>First Name <span>*</span> </label>
                                @if ($errors->has('first_name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('first_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="primary_input">
                                <input class="primary_input_field{{ $errors->has('last_name') ? ' is-invalid' : '' }}" type="text"  name="last_name" value="{{isset($editData)?$editData->last_name:old('last_name')}}">
                                <span class="focus-border"></span>
                                <label>Last Name  </label>
                                @if ($errors->has('last_name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                       <div class="col-lg-3">
                            <div class="primary_input">
                                <input class="primary_input_field{{ $errors->has('email') ? ' is-invalid' : '' }}" type="email"  name="email" value="{{isset($editData)?$editData->email:old('email')}}">
                                <span class="focus-border"></span>
                                <label>Email <span>*</span> </label>
                                @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                         <div class="col-lg-3">
                            <div class="primary_input">
                                <input class="primary_input_field{{ $errors->has('mobile') ? ' is-invalid' : '' }}" type="text"  name="mobile" value="{{isset($editData)?$editData->mobile:old('mobile')}}">
                                <span class="focus-border"></span>
                                <label>Mobile <span>*</span> </label>
                                @if ($errors->has('mobile'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('mobile') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div> 
                    </div>








                    <div class="row mt-40">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h4>Details</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-20">
                        <div class="col-lg-12">
                            <hr>
                        </div>
                    </div>





                    <div class="row mb-30">

                        <div class="col-lg-3">
                            <div class="primary_input">
                        
                                <input class="primary_input_field{{ $errors->has('company_name') ? ' is-invalid' : '' }}" type="text"  name="company_name" value="{{isset($editData)?$editData->company_name:old('company_name')}}">
                                <span class="focus-border"></span>
                                <label>Company Name </label>
                                @if ($errors->has('company_name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('company_name') }}</strong>
                                </span>
                                @endif


                            </div>
                        </div>

                         <div class="col-lg-3">
                            <div class="primary_input">
                                <select class="primary_select form-control{{ $errors->has('department_id') ? ' is-invalid' : '' }}" name="department_id" id="department_id">
                                    <option data-display="Select Department " value="">Select Department </option>
                                    @foreach($departments as $key=>$value)
                                    <option value="{{$value->id}}" {{ isset($editData)?$editData->department_id==$value->id? 'selected="selected"':'':''}} >{{$value->name}}</option>
                                    @endforeach
                                </select>
                                <span class="focus-border"></span>
                                @if ($errors->has('department_id'))
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('department_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        

                        <div class="col-lg-3">
                            <div class="primary_input">
                                <select class="primary_select form-control{{ $errors->has('designation_id') ? ' is-invalid' : '' }}" name="designation_id" id="designation_id">
                                    <option data-display="Select Designations " value="">Select Designations </option>
                                    @foreach($designations as $key=>$value)
                                    <option value="{{$value->id}}" {{ isset($editData)?$editData->designation_id==$value->id? 'selected="selected"':'':''}} >{{$value->title}}</option>
                                    @endforeach
                                </select>
                                <span class="focus-border"></span>
                                @if ($errors->has('designation_id'))
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('designation_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>



                        <div class="col-lg-3">
                            <div class="row no-gutters input-right-icon">
                                <div class="col">
                                    <div class="primary_input">

                                        <input class="primary_input_field {{ $errors->has('staff_photo') ? ' is-invalid' : '' }}" type="text" id="placeholderStaffsName" 
                                        placeholder="{{isset($editData->file) && $editData->file != '' ? getFilePath3($editData->file):'Photo '}}"
                                        disabled> 
                                        <span class="focus-border"></span>

                                        @if ($errors->has('staff_photo'))
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('staff_photo') }}</strong>
                                            </span>
                                        @endif

                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button class="primary-btn-small-input" type="button">
                                        <label class="primary-btn small fix-gr-bg" for="staff_photo">browse</label>
                                        <input type="file" class="d-none" name="staff_photo" id="staff_photo">
                                    </button>
                                </div>
                            </div>
                        </div>


                </div> 






                    <div class="row mt-40">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h4>Address</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-20">
                        <div class="col-lg-12">
                            <hr>
                        </div>
                    </div>


             
                

            <div class="row mb-40">
                <div class="col-lg-6">
                    <div class="primary_input">
                        <textarea class="primary_input_field {{ $errors->has('current_address') ? 'is-invalid' : ''}}" cols="0" rows="4" name="current_address" id="current_address">{{isset($editData)?$editData->current_address:old('current_address')}}</textarea>
                        <label> Billing Address <span>*</span> </label>
                        
                        @if ($errors->has('current_address'))
                         <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('current_address') }}</strong>
                        </span>
                        @endif 
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="primary_input">
                        <textarea class="primary_input_field {{ $errors->has('permanent_address') ? 'is-invalid' : ''}}" cols="0" rows="4" name="permanent_address" id="permanent_address">{{isset($editData)?$editData->permanent_address:old('permanent_address')}}</textarea>
                        <label> Shipping Address </label>
                        
                        @if ($errors->has('permanent_address'))
                         <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('permanent_address') }}</strong>
                        </span>
                        @endif 
                    </div>
                </div> 
            </div> 




            <!-- Bank Info Details -->
            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="main-title">
                        <h4>Bank Info Details</h4>
                    </div>
                </div>
            </div>
            <div class="row mb-30">
                <div class="col-lg-12">
                    <hr>
                </div>
            </div>
            <div class="row mb-40">
                <div class="col-lg-3">
                   <div class="primary_input">
                        <input class="primary_input_field{{ $errors->has('bank_account_name') ? ' is-invalid' : '' }}" type="text"  name="bank_account_name" value="{{isset($editData)?$editData->bank_account_name:old('bank_account_name')}}">
                        <label>Bank Account Name</label>
                        <span class="focus-border"></span>

                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="primary_input">
                        <input class="primary_input_field{{ $errors->has('bank_account_no') ? ' is-invalid' : '' }}" type="text"  name="bank_account_no" value="{{isset($editData)?$editData->bank_account_no:old('bank_account_no')}}">
                        <label>Account No</label>
                        <span class="focus-border"></span>

                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="primary_input">
                        <input class="primary_input_field{{ $errors->has('bank_name') ? ' is-invalid' : '' }}" type="text"  name="bank_name" value="{{isset($editData)?$editData->bank_name:old('bank_name')}}">
                        <label>Bank Name</label>
                        <span class="focus-border"></span>

                    </div>
                </div>

                <div class="col-lg-3">
                   <div class="primary_input">
                        <input class="primary_input_field{{ $errors->has('bank_brach') ? ' is-invalid' : '' }}" type="text"  name="bank_brach" value="{{isset($editData)?$editData->bank_brach:old('bank_brach')}}">
                        <label>Brach Name</label>
                        <span class="focus-border"></span>

                    </div>
                </div>  
            </div>
            <!-- end row -->

 

            <!-- Online Payment Info Details -->
            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="main-title">
                        <h4>Online Payment Info Details</h4>
                    </div>
                </div>
            </div>
            <div class="row mb-30">
                <div class="col-lg-12">
                    <hr>
                </div>
            </div>
            <div class="row mb-40">
                <div class="col-lg-2">
                   <div class="primary_input">
                        <input class="primary_input_field{{ $errors->has('paypal_account') ? ' is-invalid' : '' }}" type="text"  name="paypal_account" value="{{isset($editData)?$editData->paypal_account:old('paypal_account')}}">
                        <label>PayPal Account</label>
                        <span class="focus-border"></span>
                    </div>
                </div>

                <div class="col-lg-2">
                   <div class="primary_input">
                        <input class="primary_input_field{{ $errors->has('payoneer_account') ? ' is-invalid' : '' }}" type="text"  name="payoneer_account" value="{{isset($editData)?$editData->payoneer_account:old('payoneer_account')}}">
                        <label>Payoneer Account</label>
                        <span class="focus-border"></span>
                    </div>
                </div>

                <div class="col-lg-2">
                   <div class="primary_input">
                        <input class="primary_input_field{{ $errors->has('skrill_account') ? ' is-invalid' : '' }}" type="text"  name="skrill_account" value="{{old('skrill_account')}}">
                        <label>Skrill Account</label>
                        <span class="focus-border"></span>
                    </div>
                </div>
                <div class="col-lg-2">
                   <div class="primary_input">
                        <input class="primary_input_field{{ $errors->has('stripe_account') ? ' is-invalid' : '' }}" type="text"  name="stripe_account" value="{{isset($editData)?$editData->stripe_account:old('stripe_account')}}">
                        <label>Stripe Account</label>
                        <span class="focus-border"></span>
                    </div>
                </div>

                <div class="col-lg-2">
                   <div class="primary_input">
                        <input class="primary_input_field{{ $errors->has('wepay_account') ? ' is-invalid' : '' }}" type="text"  name="wepay_account" value="{{isset($editData)?$editData->wepay_account:old('wepay_account')}}">
                        <label>WePay Account</label>
                        <span class="focus-border"></span>
                    </div>
                </div>

                <div class="col-lg-2">
                   <div class="primary_input">
                        <input class="primary_input_field{{ $errors->has('amazon_account') ? ' is-invalid' : '' }}" type="text"  name="amazon_account" value="{{isset($editData)?$editData->amazon_account:old('amazon_account')}}">
                        <label>Amazon Account</label>
                        <span class="focus-border"></span>
                    </div>
                </div>
 
            </div>
            <!-- end row -->

            <!-- end Bank Info Details  -->


            
            <!-- end Bank Info Details  -->

            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="main-title">
                        <h4>Social Links Details</h4>
                    </div>
                </div>
            </div>
                <div class="row mb-30">
                    <div class="col-lg-12">
                        <hr>
                    </div>
                </div>
                <div class="row mb-20">
                    <div class="col-lg-3">
                       <div class="primary_input">
                        <input class="primary_input_field{{ $errors->has('facebook_url') ? ' is-invalid' : '' }}" type="text" name="facebook_url" 
                        value="{{isset($editData)?$editData->facebook_url:old('facebook_url')}}">
                        <label>Facebook Url</label>
                        <span class="focus-border"></span>

                    </div>
                </div>

                <div class="col-lg-3">
                   <div class="primary_input">
                    <input class="primary_input_field{{ $errors->has('twiteer_url') ? ' is-invalid' : '' }}" type="text"  name="twiteer_url" value="{{isset($editData)?$editData->twiteer_url:old('twiteer_url')}}">
                    <label>Twitter Url</label>
                    <span class="focus-border"></span>

                </div>
                </div>

                <div class="col-lg-3">
                   <div class="primary_input">
                    <input class="primary_input_field{{ $errors->has('linkedin_url') ? ' is-invalid' : '' }}" type="text"  name="linkedin_url" value="{{isset($editData)?$editData->twiteer_url:old('linkedin_url')}}">
                    <label>Linkedin Url</label>
                    <span class="focus-border"></span>

                </div>
                </div>

                <div class="col-lg-3">
                   <div class="primary_input">
                    <input class="primary_input_field{{ $errors->has('instragram_url') ? ' is-invalid' : '' }}" type="text"  name="instragram_url" value="{{isset($editData)?$editData->twiteer_url:old('instragram_url')}}">
                    <label>Instragram Url</label>
                    <span class="focus-border"></span>
                </div>
                </div>

            </div>


            <div class="row mt-40">
                <div class="col-lg-12 text-center">
                    <button class="primary-btn fix-gr-bg">
                        <span class="ti-check"></span>
                        @if(isset($editData)) Update @else Add @endif
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
{{ Form::close() }}
</div>
</section>
@endsection
