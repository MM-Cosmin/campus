@extends('backEnd.master')
@section('title')
@lang('saas::saas.subscription_payment')
@endsection
@section('mainContent')
<style type="text/css">
    .button-inherit{
        width: inherit;
    }
    .hide{
        display: none;
    }
    .bank-details p, .cheque-details p{
        margin:0 !important;
    }
    .school-table th.price{
        text-align: right !important;
    }
</style>
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('saas::saas.payment') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('saas::saas.subscription')</a>
                <a href="#">@lang('saas::saas.payment')</a>
            </div>
        </div>
    </div>
</section>

<section class="mb-40">
    <div class="container-fluid">
        <div class="row justify-content-between">

            
            <table id="" class="display school-table school-table-style" cellspacing="0" width="100%">
                <tr>
                    <th>@lang('saas::saas.package_name') </th>
                    <th>@lang('common.duration')</th>
                    <th>@lang('saas::saas.price')</th>
                </tr>
                <tr>
                    <td>{{$package->name}} </td>
                    <td>{{$package->duration_days}} days</td>
                    <td>{{number_format($package->price, 2)}}</td>
                </tr>
                <tr>
                    <td colspan="2"  align="right">
                        @lang('saas::saas.tax') :
                    </td>
                    <td>
                        {{number_format($tax, 2)}}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="right">
                       <strong> @lang('exam.result') :</strong>
                    </td>
                    <td>
                        {{number_format($package->price + $tax, 2)}}
                    </td>
                </tr>
            </table>
            
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <!-- <div class="col-lg-6 col-sm-6">
                <div class="main-title mb-20">
                    <h3 class="mb-0">@lang('common.add_student')</h3>
                </div>
            </div> -->
              
        </div>

        <form action="{{route('subscription/make-payment')}}" method="post" id="subscription-payment" data-cc-on-file="false" data-stripe-publishable-key="{{ $payment_setting->gateway_publisher_key }}" enctype="multipart/form-data">

        @csrf

        <input type="hidden" name="package_id" value="{{$package->id}}">
        <input type="hidden" name="amount_tax" value="{{$package->price + $tax}}">
        <input type="hidden" name="buy_type" value="{{$slug}}">

        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    <div class="">
                        <div class="row">
                            <div class="col-lg-12 d-flex">
                                    <p class="text-uppercase fw-500 mb-10">@lang('saas::saas.payment_method')</p>
                                    <div class="d-flex radio-btn-flex ml-40">
                                         @if(in_array(1, $array_payment_methods))
                                        <div class="mr-30">
                                            <input type="radio" name="relationButton" id="relationFather" value="cash" class="common-radio relationButton" checked>
                                            <label for="relationFather">@lang('saas::saas.cash')</label>
                                        </div>
                                        @endif
                                        @if(in_array(2, $array_payment_methods))
                                        <div class="mr-30">
                                            <input type="radio" name="relationButton" id="relationMother" value="cheque" class="common-radio relationButton">
                                            <label for="relationMother">@lang('saas::saas.cheque')</label>
                                        </div>
                                        @endif
                                        @if(in_array(3, $array_payment_methods))
                                        <div class="mr-30">
                                            <input type="radio" name="relationButton" id="relationOther" value="bank" class="common-radio relationButton">
                                            <label for="relationOther">@lang('saas::saas.bank')</label>
                                        </div>
                                        @endif
                                        @if(in_array(4, $array_payment_methods))

                                        <div class="mr-30">
                                            <input type="radio" name="relationButton" id="relationStripe" value="stripe" class="common-radio relationButton">
                                            <label for="relationStripe">@lang('saas::saas.stripe')</label>
                                        </div>
                                        @endif
                                        @if(in_array(5, $array_payment_methods))
                                        <div class="mr-30">
                                            <input type="radio" name="relationButton" id="relationPaystack" value="paystack" class="common-radio relationButton">
                                            <label for="relationPaystack">@lang('saas::saas.paystack')</label>
                                        </div>
                                        @endif
                                        @if(in_array(6, $array_payment_methods))
                                        <div class="mr-30">
                                            <input type="radio" name="relationButton" id="relationPayPal" value="paypal" class="common-radio relationButton">
                                            <label for="relationPayPal">@lang('saas::saas.paypal')</label>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                        </div>
                        <!-- start Cheque slip upload -->
                        <div class="row" id="cheque-area">
                            <div class="col-md-5 cheque-details  mt-10">
                                {!!$account_detail['cheque']!!}
                            </div>
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-lg-12 mt-20">
                                        <div class="primary_input">
                                            <input class="primary_input_field"
                                                type="text" name="bank_name_cheque" autocomplete="off">
                                            <label>@lang('saas::saas.bank_name')  <span></span></label>
                                            <span class="focus-border"></span> 
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mt-30">
                                        <div class="primary_input">
                                            <input class="primary_input_field"
                                                type="text" name="account_holder_cheque" autocomplete="off">
                                            <label>@lang('saas::saas.account_holder')  <span></span></label>
                                            <span class="focus-border"></span> 
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-lg-12 mt-30">
                                        <div class="row no-gutters input-right-icon">
                                            <div class="col">
                                                <div class="primary_input sm2_mb_20 md_mb_20">
                                                    <input class="primary_input_field" type="text" id="placeholderPhoto" placeholder="@lang('saas::saas.reference')(@lang('saas::saas.jpeg')/@lang('saas::saas.png'))"
                                                        readonly="">
                                                    <span class="focus-border"></span>

                                                    @if ($errors->has('file'))
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ @$errors->first('file') }}</strong>
                                                        </span>
                                                    @endif

                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button class="primary-btn-small-input" type="button">
                                                    <label class="primary-btn small fix-gr-bg" for="photo">@lang('common.browse')</label>
                                                    <input type="file" class="d-none" name="cheque_photo" id="photo">
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end Cheque slip upload -->
                        <!-- start Bank slip upload -->
                        <div class="row" id="bank-area">
                            <div class="col-md-5 bank-details mt-10">
                                {!!$account_detail['bank']!!}
                            </div>
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-lg-12 mt-20">
                                        <div class="primary_input">
                                            <input class="primary_input_field"
                                                type="text" name="bank_name_bank" autocomplete="off">
                                            <label>@lang('saas::saas.bank_name')  <span></span></label>
                                            <span class="focus-border"></span> 
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mt-20">
                                        <div class="primary_input">
                                            <input class="primary_input_field"
                                                type="text" name="account_holder_bank" autocomplete="off">
                                            <label>@lang('saas::saas.account_holder')  <span></span></label>
                                            <span class="focus-border"></span> 
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-lg-12 mt-20">
                                        <div class="row no-gutters input-right-icon">
                                            <div class="col">
                                                <div class="primary_input sm2_mb_20 md_mb_20">
                                                    <input class="primary_input_field" type="text" id="placeholderMothersName" placeholder="@lang('saas::saas.reference')(@lang('saas::saas.jpeg')/@lang('saas::saas.png'))"
                                                        readonly="">
                                                    <span class="focus-border"></span>

                                                    @if ($errors->has('file'))
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ @$errors->first('file') }}</strong>
                                                        </span>
                                                    @endif

                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button class="primary-btn-small-input" type="button">
                                                    <label class="primary-btn small fix-gr-bg" for="mothers_photo">@lang('common.browse')</label>
                                                    <input type="file" class="d-none" name="bank_photo" id="mothers_photo">
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end bank slip upload -->
                        <!-- start stripe slip upload -->
                        <div class="row" id="stripe-area">
                            <div class="col-lg-12"> 
                                <div class="row">                                              
                                    <div class="col-lg-6 mt-20">
                                        <div class="primary_input">
                                            <input class="primary_input_field"
                                                type="text" name="name_on_card" autocomplete="off">
                                            <label>@lang('saas::saas.name_on_card')  <span>*</span></label>
                                            <span class="focus-border"></span> 
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mt-20">
                                        <div class="primary_input">
                                            <input class="primary_input_field card-number"
                                                type="text" name="card-number" autocomplete="off">
                                            <label>@lang('saas::saas.card_number')  <span>*</span></label>
                                            <span class="focus-border"></span> 
                                        </div>
                            
                                    </div>
                                </div>
                                <div class="row mt-20">                                              
                                    <div class="col-lg-4 mt-20">
                                        <div class="primary_input">
                                            <input class="primary_input_field card-cvc"
                                                type="text" name="card-cvc" autocomplete="off">
                                            <label>@lang('saas::saas.cvc')  <span>*</span></label>
                                            <span class="focus-border"></span> 
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mt-20">
                                        <div class="primary_input">
                                            <input class="primary_input_field card-expiry-month"
                                                type="text" name="card-expiry-month" autocomplete="off">
                                            <label>@lang('saas::saas.expiration_month')  <span>*</span></label>
                                            <span class="focus-border"></span> 
                                        </div>
                            
                                    </div>
                                    <div class="col-lg-4 mt-20">
                                        <div class="primary_input">
                                            <input class="primary_input_field card-expiry-year"
                                                type="text" name="card-expiry-year" autocomplete="off">
                                            <label>@lang('saas::saas.expiration_year')  <span>*</span></label>
                                            <span class="focus-border"></span> 
                                        </div>                            
                                    </div>
                                     
                                </div>
                                <div class="row mt-20"> 
                                    <div class='primary_input'>
                                            <div class='col-md-12 error form-group hide'>
                                                <div class='alert-danger alert'>Please correct the errors and try
                                                    again.</div>
                                            </div>
                                        </div>

                                </div>
                            </div>
                        </div>
                        <!-- end stripe slip upload -->
                        <!-- start paustack slip upload -->
                        <div class="row" id="paystack-area">
                            <div class="col-md-12 text-center mt-30">
                                <p class="">@lang('saas::saas.paystack_note')</p>
                            </div>

                            <input type="hidden" name="email" value="{{Auth::user()->email}}"> {{-- required --}}
                            {{-- <input type="hidden" name="orderID" value="345"> --}}
                            @php $amount = $package->price + $tax; @endphp
                            <input type="hidden" name="amount" value="{{$amount * 100}}"> {{-- required in kobo --}}
                            <input type="hidden" name="quantity" value="1">
                            <input type="hidden" name="currency" value="ZAR">
                            <input type="hidden" name="metadata" value="{{ json_encode($array = ['key_name' => 'value',]) }}" > 
                            <input type="hidden" name="reference" value="{{ Paystack::genTranxRef() }}">
                        </div>

                        <!-- end paustack slip upload -->


                        <!-- paypal area start here  -->

                        <div class="row" id="paypal-area">
                            <div class="col-md-12 text-center mt-30">
                                <p class="">@lang('saas::saas.paypal_note')</p>
                            </div>

                        </div>


                        <div class="row mt-50"> 
                            <div class="col-lg-12 text-center">
                                <button class="primary-btn fix-gr-bg" type="submit">
                                    <span class="ti-check"></span>
                                    @lang('saas::saas.pay_now')
                                </button>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            
            </form>
        </div>
    </div>
</section>
@endsection
@section('script')

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>


<script type="text/javascript">
    $(document).ready(function() {
        $('#cheque-area').hide();
                $('#stripe-area').hide();
                $('#bank-area').hide();
                $('#paystack-area').hide();
                $('#paypal-area').hide(1000);

        $(".relationButton").on("click", function() {

            if($(this).val() == 'cash'){

                $('#cheque-area').hide(1000);
                $('#stripe-area').hide(1000);
                $('#bank-area').hide(1000);
                $('#paystack-area').hide(1000);
                $('#paypal-area').hide(1000);


            }else if($(this).val() == 'cheque'){
                $('#cheque-area').show(1000);
                $('#stripe-area').hide(1000);
                $('#bank-area').hide(1000);
                $('#paystack-area').hide(1000);
                $('#paypal-area').hide(1000);

            }else if($(this).val() == 'bank'){
                $('#cheque-area').hide(1000);
                $('#stripe-area').hide(1000);
                $('#bank-area').show(1000);
                $('#paystack-area').hide(1000);
                $('#paypal-area').hide(1000);

            }else if($(this).val() == 'stripe'){
                $('#cheque-area').hide(1000);
                $('#stripe-area').show(1000);
                $('#bank-area').hide(1000);
                $('#paystack-area').hide(1000);
                $('#paypal-area').hide(1000);

            }else if($(this).val() == 'paystack'){
     
                $('#cheque-area').hide(1000);
                $('#stripe-area').hide(1000);
                $('#bank-area').hide(1000);
                $('#paystack-area').show(1000);
                $('#paypal-area').hide(1000);

            }else if($(this).val() == 'paypal'){
     
                $('#cheque-area').hide(1000);
                $('#stripe-area').hide(1000);
                $('#bank-area').hide(1000);
                $('#paystack-area').hide(1000);
                $('#paypal-area').show(1000);

        }

        });
    });

</script>


<script type="text/javascript">


$(function() {


    var $form = $("form#subscription-payment");


    $('form#subscription-payment').on('submit', function(e) {



         if($("input:radio[name=relationButton]:checked").val() == 'stripe'){

            


            if (!$form.data('cc-on-file')) {

             e.preventDefault();





            Stripe.setPublishableKey($form.data('stripe-publishable-key'));


            Stripe.createToken({
                number: $('.card-number').val(),
                cvc: $('.card-cvc').val(),
                exp_month: $('.card-expiry-month').val(),
                exp_year: $('.card-expiry-year').val()
            }, stripeResponseHandler);



          }
         }
      });


      function stripeResponseHandler(status, response) {
  
            if (response.error) {
                $('.error')
                    .removeClass('hide')
                    .find('.alert')
                    .text(response.error.message);
            } else {
                // token contains id, last4, and card type
                var token = response['id'];
                // insert the token into the form so it gets submitted to the server
                $form.find('input[type=text]').empty();

                $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                $form.get(0).submit();
            }
        }
      
    });

</script>

@endsection


