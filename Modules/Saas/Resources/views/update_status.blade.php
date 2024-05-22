
<script src="{{asset('public/backEnd/')}}/js/main.js"></script>
<div class="container-fluid">
    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'subscription/update-status-store',
                        'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
        <div class="row">
            <div class="col-lg-12">
                <input type="hidden" name="id" value="{{$payment->id}}">
                <div class="row mt-25">
                    <div class="col-lg-12">
                        <select class="primary_select" name="status" id="discount_group">
                            <option data-display="@lang('common.select_status')" value="">@lang('common.select_status')
                                <option value="pending" {{$payment->approve_status == 'pending'? 'selected':''}}>@lang('common.pending') </option>
                                <option value="approved" {{$payment->approve_status == 'approved'? 'selected':''}}>@lang('common.approved') </option>
                                <option value="cancelled" {{$payment->approve_status == 'cancelled'? 'selected':''}}>@lang('common.cancelled') </option>
                            
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
