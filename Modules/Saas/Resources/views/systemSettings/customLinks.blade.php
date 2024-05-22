@extends('backEnd.master')
@section('title')
Custom Links List
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>Custom Links Page</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">Front Settings</a>
                    <a href="#">Custom Links</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h3 class="mb-30">  Custom Links List </h3>
                            </div> 
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'administrator/custom-links-update', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }} 
                            <div class="white-box">

                                <div class="row">
                                    <div class="col-lg-12">
                                        @if(session()->has('message-success'))
                                            <div class="alert alert-success">
                                                @lang('saas::saas.operation_success_message')
                                            </div> 
                                        @endif
                                    </div>
                                </div>



                                 <div class="row">  
 
                                                <div class="col-lg-3 mt-40"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="title1" autocomplete="off" value="{{isset($links)?$links->title1:''}}">
                                                        <label>Title 01 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div> 
                                                <div class="col-lg-3 mt-40"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="title2" autocomplete="off"  value="{{isset($links)?$links->title2:''}}" >
                                                        <label>Title 02 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div> 
                                                <div class="col-lg-3 mt-40"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="title3" autocomplete="off"  value="{{isset($links)?$links->title3:''}}" >
                                                        <label>Title 03 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div> 
                                                <div class="col-lg-3 mt-40"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="title4" autocomplete="off"  value="{{isset($links)?$links->title4:''}}" >
                                                        <label>Title 04 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div> 


  
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_label1" autocomplete="off"   value="{{isset($links)?$links->link_label1:''}}"  >
                                                        <label>link Label 01 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_label2" autocomplete="off"   value="{{isset($links)?$links->link_label2:''}}" >
                                                        <label>link Label 02 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_label3" autocomplete="off"   value="{{isset($links)?$links->link_label3:''}}"  >
                                                        <label>link Label 03 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_label4" autocomplete="off"   value="{{isset($links)?$links->link_label4:''}}"  >
                                                        <label>link Label 04 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  

 
                                                <div class="col-lg-3 mt-20"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_href1" autocomplete="off"   value="{{isset($links)?$links->link_href1:''}}"  >
                                                        <label>link URL 01 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  
 
                                                <div class="col-lg-3 mt-20"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_href2" autocomplete="off"   value="{{isset($links)?$links->link_href2:''}}"  >
                                                        <label>link URL 02 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-20"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_href3" autocomplete="off"   value="{{isset($links)?$links->link_href3:''}}"  >
                                                        <label>link URL 03 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-20"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_href4" autocomplete="off"   value="{{isset($links)?$links->link_href4:''}}"  >
                                                        <label>link URL 04 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  


                                                <!-- ****************** ****************** ****************** ****************** -->



 
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_label5" autocomplete="off"    value="{{isset($links)?$links->link_label5:''}}"  >
                                                        <label>link Label 05 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_label6" autocomplete="off"    value="{{isset($links)?$links->link_label6:''}}"  >
                                                        <label>link Label 06 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_label7" autocomplete="off"    value="{{isset($links)?$links->link_label7:''}}"  >
                                                        <label>link Label 07 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_label8" autocomplete="off"   value="{{isset($links)?$links->link_label8:''}}"   >
                                                        <label>link Label 08 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  
 
                                                <div class="col-lg-3 mt-20"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_href5" autocomplete="off"   value="{{isset($links)?$links->link_href5:''}}"  >
                                                        <label>link URL 05 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  
 
                                                <div class="col-lg-3 mt-20"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_href6" autocomplete="off"   value="{{isset($links)?$links->link_href6:''}}"  >
                                                        <label>link URL 06 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-20"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_href7" autocomplete="off"   value="{{isset($links)?$links->link_href7:''}}"  >
                                                        <label>link URL 07 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-20"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_href8" autocomplete="off"  value="{{isset($links)?$links->link_href8:''}}"   >
                                                        <label>link URL 08 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  


                                                <!-- ****************** ****************** ****************** ****************** -->


 
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_label9" autocomplete="off"  value="{{isset($links)?$links->link_label9:''}}" >
                                                        <label>link Label 09 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_label10" autocomplete="off"   value="{{isset($links)?$links->link_label10:''}}">
                                                        <label>link Label 10 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_label11" autocomplete="off"  value="{{isset($links)?$links->link_label11:''}}">
                                                        <label>link Label 11 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_label12" autocomplete="off"  value="{{isset($links)?$links->link_label12:''}}">
                                                        <label>link Label 12 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  


 
                                                <div class="col-lg-3 mt-20"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_href9" autocomplete="off"   value="{{isset($links)?$links->link_href9:''}}"  >
                                                        <label>link URL 09 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  
 
                                                <div class="col-lg-3 mt-20"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_href10" autocomplete="off"   value="{{isset($links)?$links->link_href10:''}}"  >
                                                        <label>link URL 10 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-20"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_href11" autocomplete="off"   value="{{isset($links)?$links->link_href11:''}}"  >
                                                        <label>link URL 11 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-20"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_href12" autocomplete="off"   value="{{isset($links)?$links->link_href12:''}}"  >
                                                        <label>link URL 12 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  


                                                <!-- ****************** ****************** ****************** ****************** -->













 
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_label13" autocomplete="off"   value="{{isset($links)?$links->link_label13:''}}"  >
                                                        <label>link Label 13 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_label14" autocomplete="off"   value="{{isset($links)?$links->link_label14:''}}"  >
                                                        <label>link Label 14 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_label15" autocomplete="off"   value="{{isset($links)?$links->link_label15:''}}"  >
                                                        <label>link Label 15 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_label16" autocomplete="off"   value="{{isset($links)?$links->link_label16:''}}"  >
                                                        <label>link Label 16 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  

 
                                                <div class="col-lg-3 mt-20"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_href13" autocomplete="off"   value="{{isset($links)?$links->link_href13:''}}"  >
                                                        <label>link URL 13 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  
 
                                                <div class="col-lg-3 mt-20"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_href14" autocomplete="off"   value="{{isset($links)?$links->link_href14:''}}"  >
                                                        <label>link URL 14 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-20"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_href15" autocomplete="off"   value="{{isset($links)?$links->link_href15:''}}"  >
                                                        <label>link URL 15 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-20"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="link_href16" autocomplete="off"   value="{{isset($links)?$links->link_href16:''}}"  >
                                                        <label>link URL 16 </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  


                                                <!-- ****************** ****************** Social ****************** ****************** -->


                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="facebook_url" autocomplete="off"   value="{{isset($links)?$links->facebook_url:''}}"  >
                                                        <label>Facebook URL </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  
 
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="twitter_url" autocomplete="off"   value="{{isset($links)?$links->twitter_url:''}}"  >
                                                        <label>Twitter URL </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="dribble_url" autocomplete="off"   value="{{isset($links)?$links->dribble_url:''}}"  >
                                                        <label>Dribbble URL </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <input class="primary_input_field" type="text" name="behance_url" autocomplete="off"   value="{{isset($links)?$links->behance_url:''}}"  >
                                                        <label>Behance URL </label>
                                                        <span class="focus-border"></span>
                                                    </div> 
                                                </div>  


                                                <!-- ****************** ****************** end social ****************** ****************** -->




 
                                    </div>

                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg submit">
                                                <span class="ti-check"></span>
                                                @if(isset($update))
                                                    @lang('common.update')
                                                @else
                                                    @lang('common.save')
                                                @endif
                                            </button>
                                        </div>
                                    </div>


                            </div>
                            {{ Form::close() }}
                        </div> 
                </div>
 
            </div>
        </div>
    </section>

 
@endsection

