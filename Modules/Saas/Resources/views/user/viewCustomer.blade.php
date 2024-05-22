@extends('backEnd.master')
@section('mainContent')

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>User Profile</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">Dashboard</a>
              {{--   <a href="{{route('staff_directory')}}">Customer Details</a> --}}
            </div>
        </div>
    </div>
</section>


<section class="mb-40 student-details">
    @if(session()->has('message-success'))
    <div class="alert alert-success">
        {{ session()->get('message-success') }}
    </div>
    @elseif(session()->has('message-danger'))
    <div class="alert alert-danger">
        {{ session()->get('message-danger') }}
    </div>
    @endif
    <div class="container-fluid p-0">
        <div class="row">
         <div class="col-lg-3">
            <!-- Start Student Meta Information -->
            <div class="main-title">
                <h3 class="mb-20">User Details</h3>
            </div>
            <div class="student-meta-box">
                <div class="student-meta-top"></div>
                @if(!empty($staffDetails->staff_photo) && file_exists($staffDetails->staff_photo))
                <img class="student-meta-img img-100" src="{{asset($staffDetails->staff_photo)}}"  alt="">
                @else
                <img class="student-meta-img img-100" src="{{asset('public/uploads/staff/staff1.jpg')}}"  alt="">
                
                @endif
                <div class="white-box">

               @if(isset($staffDetails) && !empty($staffDetails->full_name))
                    <div class="single-meta mt-10">
                        <div class="d-flex justify-content-between">
                            <div class="name">
                                User Name
                            </div>
                            <div class="value">

                                @if(isset($staffDetails)){{$staffDetails->full_name}}@endif

                            </div>
                        </div>
                    </div>

                @endif
               @if(isset($staffDetails) && !empty($staffDetails->roles))
                    <div class="single-meta">
                        <div class="d-flex justify-content-between">
                            <div class="name">
                                Role 
                            </div>
                            <div class="value">
                               @if(isset($staffDetails)){{!empty($staffDetails->roles)?$staffDetails->roles->name:''}}@endif
                           </div>
                       </div>
                   </div>

                @endif
               @if(isset($staffDetails) && !empty($staffDetails->designations))

                   <div class="single-meta">
                    <div class="d-flex justify-content-between">
                        <div class="name">
                            Designation
                        </div>
                        <div class="value">
                           @if(isset($staffDetails)){{!empty($staffDetails->designations)?$staffDetails->designations->title:''}}@endif
                       </div>
                   </div>
               </div>
               @endif
               @if(isset($staffDetails) && !empty($staffDetails->departments))
               <div class="single-meta">
                    <div class="d-flex justify-content-between">
                        <div class="name">
                            Department
                        </div>
                        <div class="value">
                            @if(isset($staffDetails)){{!empty($staffDetails->departments)?$staffDetails->departments->name:''}}@endif

                        </div>
                    </div>
                </div>
                @endif
               @if(isset($staffDetails) && !empty($staffDetails->company_name))
                <div class="single-meta">
                    <div class="d-flex justify-content-between">
                        <div class="name">
                           Company Name
                        </div>
                        <div class="value">
                           @if(isset($staffDetails)){{$staffDetails->company_name}}@endif
                       </div>
                   </div>
               </div>
                @endif
               @if(isset($staffDetails) && !empty($staffDetails->date_of_joining))
          
                   <div class="single-meta">
                    <div class="d-flex justify-content-between">
                        <div class="name">
                            Date of Joining
                        </div>
                        <div class="value">
                            @if(isset($staffDetails))
                            {{date('jS M, Y', strtotime($staffDetails->date_of_joining))}}
                            @endif
                        </div>
                    </div>
                </div>

                @endif
</div>
</div>
<!-- End Student Meta Information -->

</div>

<!-- Start Student Details -->
<div class="col-lg-9 staff-details">
    
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" href="#studentProfile" role="tab" data-toggle="tab">profile</a>
        </li> 
        <li class="nav-item edit-button">
            <a href="{{ route('ticket.edit_profile',$staffDetails->id)}}" class="primary-btn small fix-gr-bg">@lang('common.edit')
            </a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <!-- Start Profile Tab -->
        <div role="tabpanel" class="tab-pane fade show active" id="studentProfile">
            <div class="white-box">
                <h4 class="stu-sub-head">Personal info</h4>


 
               @if(isset($staffDetails) && !empty($staffDetails->mobile))

                <div class="single-info">
                    <div class="row">
                        <div class="col-lg-5 col-md-5">
                            <div class="">
                                Mobile No
                            </div>
                        </div>

                        <div class="col-lg-7 col-md-6">
                            <div class="">
                                @if(isset($staffDetails)){{$staffDetails->mobile}}@endif
                            </div>
                        </div>
                    </div>
                </div>

              @endif
               @if(isset($staffDetails) && !empty($staffDetails->emergency_mobile))
                <div class="single-info">
                    <div class="row">
                        <div class="col-lg-5 col-md-6">
                            <div class="">
                               Emergency Mobile
                           </div>
                       </div>

                       <div class="col-lg-7 col-md-7">
                        <div class="">
                         @if(isset($staffDetails)){{$staffDetails->emergency_mobile}}@endif
                     </div>
                 </div>
             </div>
         </div>

              @endif
               @if(isset($staffDetails) && !empty($staffDetails->email))
         <div class="single-info">
            <div class="row">
                <div class="col-lg-5 col-md-6">
                    <div class="">
                        Email
                    </div>
                </div>

                <div class="col-lg-7 col-md-7">
                    <div class="">
                        @if(isset($staffDetails)){{$staffDetails->email}}@endif
                    </div>
                </div>
            </div>
        </div>
              @endif
               @if(isset($staffDetails) && !empty($staffDetails->gender))

        <div class="single-info">
            <div class="row">
                <div class="col-lg-5 col-md-6">
                    <div class="">
                        Gender
                    </div>
                </div>

                <div class="col-lg-7 col-md-7">
                    <div class="">
                        @if(isset($staffDetails)){{$staffDetails->gender}}@endif
                    </div>
                </div>
            </div>
        </div>
              @endif
               @if(isset($staffDetails) && !empty($staffDetails->date_of_birth))

        <div class="single-info">
            <div class="row">
                <div class="col-lg-5 col-md-6">
                    <div class="">
                        Date of Birth
                    </div>
                </div>

                <div class="col-lg-7 col-md-7">
                    <div class="">
                        @if(isset($staffDetails)){{date('jS M, Y', strtotime($staffDetails->date_of_birth))}}@endif
                    </div>
                </div>
            </div>
        </div>
              @endif
               @if(isset($staffDetails) && !empty($staffDetails->marital_status))
        <div class="single-info">
            <div class="row">
                <div class="col-lg-5 col-md-6">
                    <div class="">
                       Marital Status
                   </div>
               </div>

               <div class="col-lg-7 col-md-7">
                <div class="">
                    @if(isset($staffDetails)){{$staffDetails->marital_status}}@endif
                </div>
            </div>
        </div>
    </div>
              @endif
               @if(isset($staffDetails) && !empty($staffDetails->fathers_name))

    <div class="single-info">
        <div class="row">
            <div class="col-lg-5 col-md-6">
                <div class="">
                    Father Name
                </div>
            </div>

            <div class="col-lg-7 col-md-7">
                <div class="">
                    @if(isset($staffDetails)){{$staffDetails->fathers_name}}@endif
                </div>
            </div>
        </div>
    </div>
              @endif
               @if(isset($staffDetails) && !empty($staffDetails->mothers_name))

    <div class="single-info">
        <div class="row">
            <div class="col-lg-5 col-md-6">
                <div class="">
                    Mother Name
                </div>
            </div>

            <div class="col-lg-7 col-md-7">
                <div class="">
                    @if(isset($staffDetails)){{$staffDetails->mothers_name}}@endif
                </div>
            </div>
        </div>
    </div>
              @endif
               @if(isset($staffDetails) && !empty($staffDetails->qualification))

    <div class="single-info">
        <div class="row">
            <div class="col-lg-5 col-md-6">
                <div class="">
                    Qualification
                </div>
            </div>

            <div class="col-lg-7 col-md-7">
                <div class="">
                    @if(isset($staffDetails)){{$staffDetails->qualification}}@endif
                </div>
            </div>
        </div>
    </div>
              @endif
               @if(isset($staffDetails) && !empty($staffDetails->experience))

    <div class="single-info">
        <div class="row">
            <div class="col-lg-5 col-md-6">
                <div class="">
                   Work Experience
               </div>
           </div>

           <div class="col-lg-7 col-md-7">
            <div class="">
                @if(isset($staffDetails)){{$staffDetails->experience}}@endif
            </div>
        </div>
    </div>
</div>
              @endif 

<!-- Start Parent Part -->
<h4 class="stu-sub-head mt-40">Addresses</h4>

 
@if(isset($staffDetails) && !empty($staffDetails->current_address))

<div class="single-info">
    <div class="row">
        <div class="col-lg-5 col-md-5">
            <div class="">
                Billing Address
            </div>
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="">
                @if(isset($staffDetails)){{$staffDetails->current_address}}@endif
            </div>
        </div>
    </div>
</div>
@endif
@if(isset($staffDetails) && !empty($staffDetails->permanent_address))

<div class="single-info">
    <div class="row">
        <div class="col-lg-5 col-md-5">
            <div class="">
             Shipping Address
         </div>
     </div>

     <div class="col-lg-7 col-md-6">
        <div class="">
            @if(isset($staffDetails)){{$staffDetails->permanent_address}}@endif
        </div>
    </div>
</div>
</div>

@endif

<!-- End Parent Part -->

<!-- Start Transport Part -->
<h4 class="stu-sub-head mt-40">Bank Account Details</h4>

 
@if(isset($staffDetails) && !empty($staffDetails->bank_account_name))

<div class="single-info">
    <div class="row">
        <div class="col-lg-5 col-md-5">
            <div class="">
                Account Name
            </div>
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="">
                @if(isset($staffDetails)){{$staffDetails->bank_account_name}}@endif
            </div>
        </div>
    </div>
</div>

@endif
@if(isset($staffDetails) && !empty($staffDetails->bank_account_no))

<div class="single-info">
    <div class="row">
        <div class="col-lg-5 col-md-5">
            <div class="">
                Acount Number
            </div>
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="">
                @if(isset($staffDetails)){{$staffDetails->bank_account_no}}@endif
            </div>
        </div>
    </div>
</div>
@endif
@if(isset($staffDetails) && !empty($staffDetails->bank_account_no))

<div class="single-info">
    <div class="row">
        <div class="col-lg-5 col-md-5">
            <div class="">
                Bank Name
            </div>
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="">
                @if(isset($staffDetails)){{$staffDetails->bank_name}}@endif
            </div>
        </div>
    </div>
</div>

@endif
@if(isset($staffDetails) && !empty($staffDetails->bank_brach))
<div class="single-info">
    <div class="row">
        <div class="col-lg-5 col-md-5">
            <div class="">
               Bank branch Name
           </div>
       </div>

       <div class="col-lg-7 col-md-6">
            <div class="">
                @if(isset($staffDetails)){{$staffDetails->bank_brach}}@endif
            </div>
        </div>
    </div>
</div>
@endif


<!-- End Transport Part -->
 

<!-- Start Other Information Part -->
<h4 class="stu-sub-head mt-40">Online Payment Details</h4>

 
@if(!empty($staffDetails->paypal_account))
<div class="single-info">
    <div class="row">
        <div class="col-lg-5 col-md-5">
            <div class="">
                PayPal Account
            </div>
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="">
                @if(isset($staffDetails)){{$staffDetails->paypal_account}}@endif
            </div>
        </div>
    </div>
</div>
@endif





@if(!empty($staffDetails->payoneer_account))

<div class="single-info">
    <div class="row">
        <div class="col-lg-5 col-md-5">
            <div class="">
                Payoneer Account
            </div>
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="">
                @if(isset($staffDetails)){{$staffDetails->payoneer_account}}@endif
            </div>
        </div>
    </div>
</div>
@endif




@if(!empty($staffDetails->skrill_account))

<div class="single-info">
    <div class="row">
        <div class="col-lg-5 col-md-5">
            <div class="">
                Skrill Account
            </div>
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="">
                @if(isset($staffDetails)){{$staffDetails->skrill_account}}@endif
            </div>
        </div>
    </div>
</div>

@endif





@if(!empty($staffDetails->amazon_account))
<div class="single-info">
    <div class="row">
        <div class="col-lg-5 col-md-5">
            <div class="">
                Stripe Account
            </div>
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="">
                @if(isset($staffDetails)){{$staffDetails->stripe_account}}@endif
            </div>
        </div>
    </div>
</div>


@endif





@if(!empty($staffDetails->wepay_account))
<div class="single-info">
    <div class="row">
        <div class="col-lg-5 col-md-5">
            <div class="">
                WePay Account
            </div>
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="">
                @if(isset($staffDetails)){{$staffDetails->wepay_account}}@endif
            </div>
        </div>
    </div>
</div>
@endif



@if(!empty($staffDetails->amazon_account))
<div class="single-info">
    <div class="row">
        <div class="col-lg-5 col-md-5">
            <div class="">
                Amazon Account
            </div>
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="">
                @if(isset($staffDetails)){{$staffDetails->amazon_account}}@endif
            </div>
        </div>
    </div>
</div>
@endif


<!-- Start Other Information Part -->
<h4 class="stu-sub-head mt-40">Social Media Link</h4>


@if(!empty($staffDetails->facebook_url))
<div class="single-info">
    <div class="row">
        <div class="col-lg-5 col-md-5">
            <div class="">
                Facebook Url 
            </div>
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="">
                @if(isset($staffDetails)){{$staffDetails->facebook_url}}@endif
            </div>
        </div>
    </div>
</div>

@endif



@if(!empty($staffDetails->twiteer_url))
<div class="single-info">
    <div class="row">
        <div class="col-lg-5 col-md-5">
            <div class="">
                Twitter Url
            </div>
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="">
                @if(isset($staffDetails)){{$staffDetails->twiteer_url}}@endif
            </div>
        </div>
    </div>
</div>

@endif



@if(!empty($staffDetails->linkedin_url))
<div class="single-info">
    <div class="row">
        <div class="col-lg-5 col-md-5">
            <div class="">
                Linkedin Url
            </div>
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="">
                @if(isset($staffDetails)){{$staffDetails->linkedin_url}}@endif
            </div>
        </div>
    </div>
</div>

@endif



@if(!empty($staffDetails->instragram_url))
<div class="single-info">
    <div class="row">
        <div class="col-lg-5 col-md-5">
            <div class="">
                Instrgram Url
            </div>
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="">
                @if(isset($staffDetails)){{$staffDetails->instragram_url}}@endif
            </div>
        </div>
    </div>
</div>
@endif
<!-- End Other Information Part -->
</div>
</div>
 

  
           
    </div>
</div>
</section>
@endsection
