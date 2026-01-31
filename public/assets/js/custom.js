




function max_min_val(ele, Max, Min) {
    ele.oninput = function () {
        let max = Max;
        let min = Min;
        if (parseInt(this.value) > max) {
            this.value = max;
        } else if (parseInt(this.value) < min) {
            this.value = min;
        }
    }
} // End - Function on-input "Max" & "Min" value.

function inputToFixed(ele) {
    ele.addEventListener("change", () => {
        ele.value = parseFloat(ele.value).toFixed(2).toString();
    })
}
function input_number(ele) {
    ele.addEventListener('keyup', () => {
        ele.value = ele.value.replace(/\D/g, "");
    });
}
function input_float(ele) {
    ele.addEventListener('keyup', () => {
        ele.value = ele.value.replace(/[^0-9.]/g,'');
    });
}
function notValidateArabicCharInput(ele) {
    ele.addEventListener('keyup', () => {
        ele.value = ele.value.replace(/[^a-zA-Z0-9]/g, '');
    });
}

function lazySelect2Ajax(ele, route, isMultiple = false, defaultValue = null, dataParams = {}) {
    var placeholder = ele.data('placeholder');
    ele.select2({
        allowClear: false,
        multiple: isMultiple,
        ajax: {
            url: route,
            dataType: 'json',
            delay: 100,
            data: function (params) {
                return {
                    search: params.term || '',
                    page: params.page || 1,
                    ...dataParams
                };
            },
            processResults: data => ({
                results: data.items,
                pagination: { more: data.more }
            }),
            cache: true
        },
        placeholder: placeholder
    });

    if (defaultValue !== null) {
        $.ajax({
            url: route,
            data: { id: defaultValue, ...dataParams },
            dataType: 'json',
            success: function (data) {
                if (data.items.length > 0) {
                    let item = data.items[0];
                    let option = new Option(item.text, item.id, true, true);
                    ele.append(option).trigger('change');
                }
            }
        });
    }
}

async function handleFormStoreSubmission(formId, submitBtnId, routes, messageTitles, print = false) {
    const $button = $("." + submitBtnId);
    const $form = $("#" + formId);
    const formData = new FormData($form[0]);

    const setLoading = (isLoading = true) => {
        $button.prop('disabled', isLoading);
        isLoading ? $button.attr('data-kt-indicator', 'on') : $button.removeAttr('data-kt-indicator');
    };

    const showValidationErrors = (errors) => {
        $(".invalid_message").remove();
        let errorMessage = '';

        $.each(errors, (key, value) => {
            const $input = $('#' + key);
            const errorHtml = `<span class="badge badge-light-danger mt-2 text-wrap text-start lh-lg invalid_message">${value}</span>`;
            const badgeHtml = `<span class="badge badge-light-danger mb-2">${value}</span><br>`;

            if ($input.is('select, .datepicker_date')) {
                const fieldName = $input.attr('name');
                $(`span[data-name='${fieldName}']`).after(errorHtml);
            } else {
                $input.after(errorHtml);
            }

            errorMessage += badgeHtml;
        });

        Swal.fire(messageTitles.error, errorMessage, "error");
    };

    setLoading(true);

    $.ajax({
        url: routes.form,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: async function (data) {
            if (data.success) {
                Swal.fire(messageTitles.success, data.success, 'success');

                if (routes.redirect) {
                    setTimeout(() => location.href = routes.redirect, 2000);
                }

                $form.trigger("reset").find(".form-select").val(null);
            } else if (data.error) {
                Swal.fire(messageTitles.error, data.error, "error");
                setLoading(false);
            } else if (data.test) {
                console.log(data.test);
                Swal.fire(messageTitles.info, "In Console Log", 'info');
                setLoading(false);
            } else {
                console.log(data);
                setLoading(false);
            }

        },
        error: function (xhr) {
            console.log(xhr.responseJSON)
            const errors = xhr.responseJSON?.errors;
            if (errors) {
                showValidationErrors(errors);
            } else {
                Swal.fire(messageTitles.error, 'Something went wrong, please try again later', "error");
            }
            setLoading(false);
        }
    });
}

function handleGetData(route, data, onSuccess, messageTitles) {

    $.ajax({
        url: route,
        method: 'GET',
        data: data,
        success: function (response) {
            if (typeof onSuccess === 'function') {
                onSuccess(response);
            }
        },
        error: function (xhr) {
            Swal.fire(messageTitles.error || 'Error', xhr.responseJSON?.error || 'Something is failed.', "error");
        }
    });
}
function handlePostData(route, data, onSuccess, messageTitles) {

    $.ajax({
        url: route,
        method: 'POST',
        data: data,
        success: function (response) {
            if (typeof onSuccess === 'function') {
                onSuccess(response);
            }
        },
        error: function (xhr) {
            Swal.fire(messageTitles.error || 'Error', xhr.responseJSON?.error || 'Something is failed.', "error");
        }
    });
}
