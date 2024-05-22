@if(isset($custom_fields))
 
        @foreach ($custom_fields as $key=>$custom_field)
            @if ($custom_field->type=="textInput")
                <div class="{{$custom_field->width}} position-relative input-control">
                    <div class="form-group input-group">
                        @if (activeTheme() == "edulia")
                            <label class="input-control-label col-lg-12">{{$custom_field->label}} 
                                <span>{{($custom_field->required==1)? "*":""}}</span> 
                            </label>
                        @endif
                        <input class="form-control"  type="text" 
                        name="customF[{{$custom_field->label}}]" placeholder="{{$custom_field->label}}" value="{{(isset($student)) ? customFieldValue($student->id,$custom_field->label,$student->custom_field_form_name):""}}">
                        {{-- <label>{{$custom_field->label}} <span>{{($custom_field->required==1)? "*":""}}</span> </label> --}}
                        @if ($errors->has($custom_field->label))
                        <span class="text-danger" >
                            {{ $errors->first($custom_field->label) }}
                        </span>
                        @endif
                    </div>
                </div>
            @elseif($custom_field->type=="numericInput")
                @php
                    $min_max_value = json_decode($custom_field->min_max_value);
                @endphp
                <div class="{{$custom_field->width}} position-relative input-control">
                    <div class="form-group input-group">
                        @if (activeTheme() == "edulia")
                            <label class="input-control-label col-lg-12">
                                {{$custom_field->label}} <span>{{($custom_field->required==1)? "*":""}}</span> 
                            </label>
                        @endif
                        <input class="form-control"  type="number" min="{{$min_max_value[0]}}" max="{{$min_max_value[1]}}" 
                        name="customF[{{$custom_field->label}}]" value="{{(isset($student)) ?customFieldValue($student->id,$custom_field->label,$student->custom_field_form_name):''}}">
                        {{-- <label>{{$custom_field->label}} <span>{{($custom_field->required==1)? "*":""}}</span> </label> --}}
                        @if ($errors->has($custom_field->label))
                        <span class="text-danger" >
                            {{ $errors->first($custom_field->label) }}
                        </span>
                        @endif
                    </div>
                </div>
            @elseif($custom_field->type=="multilineInput")
                @php
                    $min_max_length = json_decode($custom_field->min_max_length);
                @endphp
                <div class="{{$custom_field->width}} position-relative input-control">
                    <div class="form-group input-group">
                        @if (activeTheme() == "edulia")
                            <label class="input-control-label col-lg-12">
                                {{$custom_field->label}} <span>{{($custom_field->required==1)? "*":""}}</span> 
                            </label>
                        @endif
                        <textarea class="primary_input_field form-control" placeholder="{{$custom_field->label}}" cols="0" rows="3" name="customF[{{$custom_field->label}}]">{{(isset($student)) ?customFieldValue($student->id,$custom_field->label,$student->custom_field_form_name):""}}</textarea>
                        {{-- <label> <span>{{($custom_field->required==1)? "*":""}}</span> </label> --}}
                        @if($errors->has($custom_field->label))
                            <span class="text-danger">
                                {{ $errors->first($custom_field->label)}}
                            </span>
                        @endif
                    </div>
                </div>
            @elseif($custom_field->type=="datepickerInput")
                @if (activeTheme() == "edulia")
                    <div class="{{$custom_field->width}}">
                        <div class="input-control">
                            <label for="datepicker4"
                                class="input-control-label">{{$custom_field->label}}
                                <span>{{($custom_field->required==1)? " *":""}}</span>
                            </label>
                            <input
                                class="input-control-input {{ $errors->has($custom_field->label) ? ' is-invalid' : '' }}"
                                placeholder='{{ date('m/d/Y') }}' id='datepicker4' type="text"
                                name="customF[{{$custom_field->label}}]"
                                value="{{(isset($student)) ? customFieldValue($student->id,$custom_field->label,$student->custom_field_form_name) : ""}}"
                                autocomplete="off">
                            @if ($errors->has($custom_field->label))
                                <span class="text-danger" >
                                    {{ $errors->first($custom_field->label) }}
                                </span>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="{{$custom_field->width}}">
                        <div class="no-gutters input-right-icon">
                            <div class="col">
                                <div class="primary_input ">
                                    <input
                                        class="primary_input_field date form-control{{ $errors->has($custom_field->label) ? ' is-invalid' : '' }}"
                                        placeholder="{{$custom_field->label}}" id="startDate" type="text"
                                        name="customF[{{$custom_field->label}}]"
                                        value="{{(isset($student)) ? customFieldValue($student->id,$custom_field->label,$student->custom_field_form_name) : ""}}"
                                        autocomplete="off">
                                        {{-- <label>{{$custom_field->label}} <span>{{($custom_field->required==1)? "*":""}}</span></label> --}}
                                    @if ($errors->has($custom_field->label))
                                        <span class="text-danger" >
                                            {{ $errors->first($custom_field->label) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-auto">
                                <button class="" type="button">
                                    <i class="ti-calendar" id="start-date-icon"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            @elseif($custom_field->type=="checkboxInput")
                @php
                    $checkbox_values = json_decode($custom_field->name_value);
                @endphp
                @if (activeTheme() == "edulia")
                    <div class="{{$custom_field->width}}">
                        <div class="input-control select-guardian d-flex">
                            <label class="input-control-label">
                                {{$custom_field->label}}{!! $custom_field->required==1 ? "<span>*</span>" : '' !!}:
                            </label>
                            @foreach ($checkbox_values as $key => $checkbox_value)
                                <label for="custom_types_{{$key}}_{{$custom_field->id}}" class="page_radio">
                                    <input class="common-checkbox exam-checkbox" type="checkbox"
                                        name='customF[{{$custom_field->label}}][]'
                                        id="custom_types_{{$key}}_{{$custom_field->id}}"
                                        @if (isset($student))
                                            @if(!is_null(customFieldValue($student->id,$custom_field->label,$student->custom_field_form_name)))
                                                @if(in_array($checkbox_value, customFieldValue($student->id,$custom_field->label,$student->custom_field_form_name)))
                                                    checked
                                                @endif
                                            @endif
                                        @endif
                                        value="{{$checkbox_value}}">
                                    <span>{{$checkbox_value}}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="{{$custom_field->width}} mt-30 d-flex align-items-center">
                        {{-- <label class="mr-5">{{$custom_field->label}} {{($custom_field->required==1)? "*":""}}</label> --}}
                        <p class="text-uppercase fw-500 mb-10 mr-5">{{$custom_field->label}} </p>
                        @foreach ($checkbox_values as $key=>$checkbox_value)
                            <div class="row no-gutters input-right-icon mr-3">
                                <div class="primary_input">
                                    <input type="checkbox" id="custom_types_{{$key}}_{{$custom_field->id}}" class="common-checkbox exam-checkbox" name="customF[{{$custom_field->label}}][]" 
                                    @if (isset($student))
                                        @if(!is_null(customFieldValue($student->id,$custom_field->label,$student->custom_field_form_name)))
                                            @if(in_array($checkbox_value, customFieldValue($student->id,$custom_field->label,$student->custom_field_form_name)))
                                                checked
                                            @endif
                                        @endif
                                    @endif
                                        value="{{$checkbox_value}}">
                                    <label for="custom_types_{{$key}}_{{$custom_field->id}}">{{$checkbox_value}}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @elseif($custom_field->type=="radioInput")
                @php
                    $name_values = json_decode($custom_field->name_value);
                @endphp
                @if (activeTheme() == "edulia")
                    <div class="{{$custom_field->width}}">
                        <div class="input-control select-guardian d-flex">
                            <label class="input-control-label">
                                {{$custom_field->label}}{!! $custom_field->required==1 ? "<span>*</span>" : '' !!}:
                            </label>
                            @foreach ($name_values as $key=>$name_value)
                                <label for="{{$key}}_custField_{{$custom_field->id}}" class="page_radio">
                                    <input type="radio" name="customF[{{$custom_field->label}}]"
                                        id="{{$key}}_custField_{{$custom_field->id}}"
                                        @if(isset($student) && ($name_value == customFieldValue($student->id,$custom_field->label,$student->custom_field_form_name)))
                                                checked
                                        @endif
                                        value="{{$name_value}}"
                                        class="relationButton">
                                    <span class="page_radio_title">{{$name_value}}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="{{$custom_field->width}} d-flex relation-button">
                        <p class="text-uppercase fw-500 mb-10">{{$custom_field->label}} </p>
                        <div class="d-flex radio-btn-flex ml-30 mt-1">
                            @foreach ($name_values as $key=>$name_value)
                                <div class="mr-30">
                                    <input type="radio" name="customF[{{$custom_field->label}}]"
                                        id="{{$key}}_custField_{{$custom_field->id}}"
                                        @if(isset($student) && ($name_value == customFieldValue($student->id,$custom_field->label,$student->custom_field_form_name)))
                                            checked
                                        @endif
                                        value="{{$name_value}}"
                                        class="common-radio relationButton">
                                    <label for="{{$key}}_custField_{{$custom_field->id}}">{{$name_value}}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @elseif($custom_field->type=="dropdownInput")
                @php
                    $dropdown_name_values = json_decode($custom_field->name_value);
                @endphp
                @if (activeTheme() == "edulia")
                    <div class="{{$custom_field->width}}">
                        <div class="input-control" {{-- id="academic-year-div" --}}>
                            <label class="input-control-label">
                                {{$custom_field->label}}{!! $custom_field->required==1 ? "<span>*</span>" : '' !!}:
                            </label>
                            <select class="input-control-input {{ $errors->has($custom_field->label) ? ' is-invalid' : '' }}"
                                name="customF[{{$custom_field->label}}]" id="select-academic-year">
                                <option data-display="{{$custom_field->label}} @lang('common.select') {{($custom_field->required==1)? "*":""}}" value="">
                                    {{$custom_field->label}} @lang('common.select') {{($custom_field->required==1)? "*":""}}
                                </option>
                                @foreach($dropdown_name_values as $dropdown_name_value)
                                    <option value="{{$dropdown_name_value}}" {{isset($student)? (customFieldValue($student->id,$custom_field->label,$student->custom_field_form_name)==$dropdown_name_value ?'selected':''):''}}>
                                        {{$dropdown_name_value}}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has($custom_field->label))
                                <span class="text-danger invalid-select" role="alert">
                                    {{ $errors->first($custom_field->label) }}
                                </span>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="{{$custom_field->width}}">
                        <div class="form-group input-group">
                            <select class="primary_select form-control{{ $errors->has($custom_field->label) ? ' is-invalid' : '' }}" 
                                name="customF[{{$custom_field->label}}]">
                                <option data-display="{{$custom_field->label}} @lang('common.select') {{($custom_field->required==1)? "*":""}}" value="">
                                    {{$custom_field->label}} @lang('common.select') {{($custom_field->required==1)? "*":""}}
                                </option>
                                @foreach($dropdown_name_values as $dropdown_name_value)
                                    <option value="{{$dropdown_name_value}}" 
                                    {{isset($student)? (customFieldValue($student->id,$custom_field->label,$student->custom_field_form_name)==$dropdown_name_value ?'selected':''):''}}>{{$dropdown_name_value}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has($custom_field->label))
                                <span class="text-danger invalid-select" role="alert">
                                    {{ $errors->first($custom_field->label) }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            @elseif($custom_field->type=="fileInput")
                @if (activeTheme() == "edulia")
                    <div class="{{$custom_field->width}}">
                        <div class="position-relative input-control file_uploader">
                            <label class="input-control-label" for="placeholderPhoto_{{$key}}">
                                {{$custom_field->label}}{!! $custom_field->required==1 ? "<span>*</span>" : '' !!}:
                            </label>
                            <input class="input-control-input" type="text" id="placeholderPhoto_{{$key}}"
                                placeholder="{{(isset($student)) ? ((!showFileName(customFieldValue($student->id,$custom_field->label,$student->custom_field_form_name)))? $custom_field->label: showFileName(customFieldValue($student->id,$custom_field->label,$student->custom_field_form_name)))  : $custom_field->label}} {{((isset($student))? "" : $custom_field->required==1)? "*" : ""}}"
                                readonly="">
                            <button class="" type="button">
                                <label class="primary-btn small fix-gr-bg" for="photo_{{$key}}">
                                    @lang('common.browse')
                                </label>
                                <input type="file" id="photo_{{$key}}" class="d-none"
                                    value="{{(isset($student)) ? customFieldValue($student->id,$custom_field->label,$student->custom_field_form_name):""}}"
                                    name="customF[{{$custom_field->label}}]"
                                    @if(isset($student))
                                        {{" "}}
                                    @else
                                        @if($custom_field->required==1)
                                            {{"required"}}
                                        @endif
                                    @endif>
                            </button>
                        </div>
                    </div>
                @else
                    <div class="{{$custom_field->width}}">
                        <div class="row no-gutters input-right-icon">
                            <div class="col">
                                <div class="primary_input ">
                                    <input class="primary_input_field" type="text" id="placeholderPhoto_{{$key}}"
                                        placeholder="{{(isset($student)) ? ((!showFileName(customFieldValue($student->id,$custom_field->label,$student->custom_field_form_name)))? $custom_field->label: showFileName(customFieldValue($student->id,$custom_field->label,$student->custom_field_form_name)))  : $custom_field->label}} {{((isset($student))? "" : $custom_field->required==1)? "*" : ""}}" readonly="">
                                    @if ($errors->has($custom_field->label))
                                        <span class="text-danger d-block" >
                                            {{@$errors->first($custom_field->label)}}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-auto">
                                <button class="primary-btn-small-input" type="button">
                                    <label class="primary-btn small fix-gr-bg" for="photo_{{$key}}">
                                        @lang('common.browse')
                                    </label>
                                    <input type="file" id="photo_{{$key}}" data-id="#placeholderPhoto_{{$key}}" class="d-none cutom-photo"
                                        value="{{(isset($student)) ? customFieldValue($student->id,$custom_field->label,$student->custom_field_form_name):""}}"
                                        name="customF[{{$custom_field->label}}]"
                                        @if(isset($student))
                                            {{" "}}
                                        @else
                                            @if($custom_field->required==1)
                                                {{"required"}}
                                            @endif
                                        @endif>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        @endforeach
    
@endif

@include('backEnd.partials.date_picker_css_js')