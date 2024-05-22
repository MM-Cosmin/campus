"use strict";
$('#generate_content').on('click', function (e) {
    e.preventDefault();
    var form_data = $('#text_generator_form').serialize();
    var url = $('#text_generator_form').attr('action');
    var form_data_array = $('#text_generator_form').serializeArray();
    var language_input = form_data_array[1]['value'];
    var template_input = form_data_array[2]['value'];
    var tone_input = form_data_array[3]['value'];


    if ($('#keyword').val() == '') {
        $('#keyword_error_message').html('Keyword is required');
        return false;
    } else {
        $('#keyword_error_message').html('');
    }
    if (language_input == '') {
        $('#language_error_message').html('Language is required');
        return false;
    } else {
        $('#language_error_message').html('');
    }
    if (template_input == '') {
        $('#template_error_message').html('Template is required');
        return false;
    } else {
        $('#template_error_message').html('');
    }
    if (tone_input == '') {
        $('#tone_error_message').html('Tone is required');
        return false;
    } else {
        $('#tone_error_message').html('');
    }

    $('#show_ai_icon').removeClass('fas fa-robot');
    $('#show_ai_icon').addClass('fas fa-spinner fa-spin');
    let generated_result = $('#generated_result');
    $.ajax({
        url: url,
        type: 'POST',
        data: form_data,
        dataType: 'json',
        success: function (data) {
            if (data.status == 'success') {
                generated_result.html('');

                data.results.map((item, index) => {
                    let html = `<label class="primary_input_label" for="">
                                    Generated Content
                                </label>
                                <div class="row">
                                    <div class="offset-lg-10 col-lg-2">
                                        <button class="float-right primary-btn radius_30px mb-10 fix-gr-bg extraBtn copy_generated_content"
                                            data-clipboard-target="#generated_content" data-btn_id="${index}" onclick="copyResult(${index})" id="copy_generated_content_${index}">
                                            <i class="fas fa-copy  copy_icon" id="copy_icon_${index}"></i>
                                            <span class="" id="copied_tooltip_${index}" data-id="${index}" style="display: none">
                                                Copied!
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                <textarea class="lms_summernote" name="" id="generated_content_${index}" cols="30" rows="10">
                                </textarea>`;
                    generated_result.append(html);
                    $('#generated_content_' + index).summernote("code", item);
                });
                $('#error_message').html('');
            } else {
                $('#error_message').html(data.message);
            }
            $('#generation_result').show();
        },
        error: function (data) {
            console.log('error');
        },
        complete: function (data) {
            $('#show_ai_icon').removeClass('fas fa-spinner fa-spin');
            $('#show_ai_icon').addClass('fas fa-robot');
        }

    });

});

function copyResult(id) {
    var btn_id = id;
    var $temp = $("<input>");
    $("body").append($temp);
    var text = $('#generated_content_' + btn_id).val();
    text = text.trim();
    $temp.val(text).select();
    document.execCommand("copy");
    $(".generated-text").val(text);
    $temp.remove();

    $('#copied_tooltip_' + btn_id).show();
    $('#copy_icon_' + btn_id).hide();
    setTimeout(function () {
        $('#copied_tooltip_' + btn_id).hide();
        $('#copy_icon_' + btn_id).show();
    }, 1000);

}

$('#ai_text_generation_modal').on('hidden.bs.modal', function (e) {
    $('#generated_content').summernote("code", '');
    $('#generated_content').val('');
})

$('#ai_advance_section_collapse').on('click', function () {
    $('#ai_advance_section').toggle();
    if ($('#ai_advance_icon').hasClass('fas fa-angle-down')) {
        $('#ai_advance_icon').removeClass('fas fa-angle-down');
        $('#ai_advance_icon').addClass('fas fa-angle-up');
    } else {
        $('#ai_advance_icon').removeClass('fas fa-angle-up');
        $('#ai_advance_icon').addClass('fas fa-angle-down');
    }
})


