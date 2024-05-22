<div class="container-fluid">
    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'parentregistration/assign-section-store', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'name' => 'myForm']) }}
        <input type="hidden" name="id" value="{{@$student->id}}">
        <div class="row">
            <div class="col-lg-12">
                <select class="primary_select1 w-100 bb form-control {{ $errors->has('section') ? ' is-invalid' : '' }}" name="section" id="sectionSelectStudent" >
                    <option data-display="@lang('common.select_section') *" value="">@lang('common.select_section') *</option>
                        @foreach ($sections as $old_section)
                            <option value="{{ $old_section->id }}" {{ $student->section_id == $old_section->id ? 'selected' : '' }} >
                            {{ $old_section->section_name }}</option>
                        @endforeach
                </select>
            </div>
            <!-- <div class="col-lg-12 text-center mt-40">
                <button class="primary-btn fix-gr-bg" id="save_button_sibling" type="button">
                    <span class="ti-check"></span>
                    save information
                </button>
            </div> -->
            <div class="col-lg-12 text-center mt-40">
                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                    <button class="primary-btn fix-gr-bg submit" type="submit"> @lang('common.save_information')</button>
                </div>
            </div>
        </div>
    {{ Form::close() }}
</div>
<script>
    if ($(".niceSelect1").length) {
        $(".niceSelect1").niceSelect();
    }
</script>
