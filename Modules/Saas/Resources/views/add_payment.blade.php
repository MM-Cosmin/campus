
<script src="{{asset('public/backEnd/')}}/js/main.js"></script>
<div class="container-fluid">
    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'subscription/store-payment',
                        'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
        <div class="row">
            <div class="col-lg-12">
                <input type="hidden" name="school_id" value="{{$id}}">
                <div class="row mt-25">
                    <div class="col-lg-12">
                        <select class="primary_select" name="package" id="discount_group">
                            <option data-display="@lang('common.select_package')" value="">@lang('common.select_status')
                                @foreach($packages as $package)
                                <option value="{{$package->id}}">{{$package->name}} </option>
                                @endforeach
                            
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 text-center mt-40">
                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>

                    <button class="primary-btn fix-gr-bg submit" type="submit">@lang('common.save_information')</button>
                </div>
            </div>
        </div>
    {{ Form::close() }}
</div>
