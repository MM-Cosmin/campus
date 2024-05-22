(function ($) {
    "use strict";
    $(document).on('click', '.apiUse', function () {
        let value = $(this).val();
        if (value == 1) {
            $('#apiDetailDiv').removeClass('d-none');
        } else {
            $('#apiDetailDiv').addClass('d-none');
        }
    })
    $(document).on('click', '.settingsUpdate', function (e) {
        let value = $("input[name='use_api']:checked").val();
        let api_key = $('#api_key').val();
        let api_secret_key = $('#api_secret_key').val();

        if (value == 1) {
            if (api_key == '' || api_secret_key == '') {
                toastr.error("Key and Secret Can not be Empty!", "Failed");
                e.preventDefault();
                return;
            }
        }
    })
    $(document).on('click', '.eventSubmit', function (e) {
        let is_recurring = $("input[name='is_recurring']:checked").val();
        ;
        let type = $('#recurring_type').val();
        let recurring_repeat_day = $('#recurring_repeat_day').val();
        let days = $('.weekDays').is(":checked") ? 1 : 0;
        let end_date = new Date($('#recurring_end_date').val());
        let event_date = new Date($('#startDate').val());
     
        if (is_recurring == 1) {
            var status = false;
            if (!type) {
                toastr.error("Recurring Type can't be empty");
                var status = true;

            }
            if (!recurring_repeat_day) {
                toastr.error("Recurring repeat day can't be empty");
                var status = true;
            }
            if (type == 2 && !days == 1) {
                toastr.error("Day can't be empty");
                var status = true;
            }
            if (!end_date) {
                toastr.error("Recurring End date can't be empty");
                var status = true;
            }
            if (end_date && (end_date < event_date)) {
                toastr.error("Recurring End date will be greater than Meeting");
                var status = true;
            }
            if (status == true) {
                e.preventDefault();
                return;
            }
        }
    });
})(jQuery)
