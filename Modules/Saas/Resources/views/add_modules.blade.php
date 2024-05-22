
<div class="container-fluid">
    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => ['subscription/add-module', $id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
        <div class="row">
            <div class="col-lg-12">
                <input type="hidden" name="school_id" value="{{$id}}">
                <div class="row mt-30">
                    @php
                        $package = getschoolModule($school) ?? activePackage($school);
                    @endphp
                    @foreach($permissions as $hook => $values)
                        @if($value_count = count($values))
                            <div class="col-12 mt-3">
                                <div class="d-flex justify-content-between">
                                    <h3>{{ __('saas::saas.'.$hook.'_permission') }}</h3>
                                    @php
                                        $checked_all = $package && $package->$hook && count($package->$hook) == $value_count;
                                    @endphp
                                    <div class="mt-2">
                                        <input type="checkbox" id="select_all_{{$hook}}" value="{{$hook}}" class="common-radio relationButton select_all" {{ $checked_all ? 'checked' : '' }} >
                                        <label for="select_all_{{$hook}}">@lang('common.select_all')</label>
                                    </div>

                                </div>
                            </div>
                        @endif
                        @foreach($values as $key => $value)
                            <div class="col-lg-6 mt-3">
                                <div class="">
                                    @php
                                        $checked = $package && $package->$hook && in_array($key, $package->$hook);
                                    @endphp
                                    <input type="checkbox" name="{{ $hook }}[]" id="{{$hook}}_{{$key}}" value="{{$key}}" class="common-radio relationButton {{ $hook.'_checkbox' }}" {{ $checked ? 'checked' : '' }} >
                                    <label for="{{$hook}}_{{$key}}">@lang($value)</label>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
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
