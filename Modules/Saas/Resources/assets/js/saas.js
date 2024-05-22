// institution approve
(function () {
    $(document).ready(function () {
        $(document).on('change', '.switch-input-institution-approve', function () {
            if ($(this).is(':checked')) {
                var status = 'on';
            } else {
                var status = 'off';
            }
            var formData = {
                id: $(this).parents('tr').attr("id"),
                status: status,
            };
            var url = $('#url').val();
            $.ajax({
                type: "GET",
                data: formData,
                dataType: 'json',
                url: url + '/' + 'institution-approve',
                success: function (data) {
                    toastr.success('Operation successful', 'Successful', {
                        timeOut: 5000
                    })
                },
                error: function (data) {
                    console.log('no');
                    toastr.error('Operation Failed', 'Failed', {
                        timeOut: 5000
                    })
                }
            });
        });
    });

})();

(function () {
    $(document).ready(function () {
        $('.switch-input-institution-enable').on('change', function () {
            if ($(this).is(':checked')) {
                var status = 'on';
            } else {
                var status = 'off';
            }
            var formData = {
                id: $(this).parents('tr').attr("id"),
                status: status,
            };
            var url = $('#url').val();
            $.ajax({
                type: "GET",
                data: formData,
                dataType: 'json',
                url: url + '/' + 'institution-enable',
                success: function (data) {
                    toastr.success('Operation successful', 'Successful', {
                        timeOut: 5000
                    })
                },
                error: function (data) {
                    console.log('no');
                    toastr.error('Operation Failed', 'Failed', {
                        timeOut: 5000
                    })
                }
            });
        });
    });
})();