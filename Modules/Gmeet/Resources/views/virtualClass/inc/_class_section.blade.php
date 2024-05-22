<div class="row">
    <div class="col-lg-12">
        <label class="primary_input_label" for="">
            {{ __('common.class') }}
                <span class="text-danger"> *</span>
        </label>
        <select class="primary_select form-control {{ @$errors->has('class') ? ' is-invalid' : '' }}"
            id="select_class" name="class">
            <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class') *</option>
            @foreach ($classes as $class)
                <option value="{{ @$class->id }}" 
                    @if(isset($editdata)) {{ old('class', $class->id) == $editdata->class_id ? 'selected' : '' }} @else{{ old('class') ? 'selected' : '' }} @endif>{{ @$class->class_name }}
                </option>
            @endforeach
        </select>
        @if ($errors->has('class'))
            <span class="text-danger invalid-select" role="alert">
                {{ @$errors->first('class') }}
            </span>
        @endif
    </div>
</div>

<div class="row  mt-15">
    <div class="col-lg-12" id="select_section_div">
        <label class="primary_input_label" for="">
            {{ __('common.section') }}
                <span class="text-danger"> </span>
        </label>
        <select class="primary_select form-control {{ @$errors->has('section') ? ' is-invalid' : '' }}"
            id="select_section" name="section">
            <option data-display="@lang('common.select_section')" value="">@lang('common.select_section') </option>
            @if (isset($editdata))
                @foreach ($class_sections as $section)
                    <option value="{{ @$section->id }}"
                        {{ old('section', $section->id) == $editdata->section_id ? 'selected' : '' }}>
                        {{ @$section->section_name }} </option>
                @endforeach
            @endif
        </select>
        <div class="pull-right loader loader_style" id="select_section_loader">
            <img class="loader_img_style" src="{{ asset('public/backEnd/img/demo_wait.gif') }}" alt="loader">
        </div>
        @if ($errors->has('section'))
            <span class="text-danger invalid-select" role="alert">
                {{ @$errors->first('section') }}
            </span>
        @endif
    </div>
</div>
