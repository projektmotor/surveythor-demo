let popoverTool = require('./popoverTool');

module.exports = {
    bindDisableSurveyWhileAjaxLoading: function () {
        "use strict";
        $(document).on({
            ajaxSend: function (e, jqXHR, options) {
                if (options.isLocal) {
                    $('#action-visualizer').css('display', 'block');
                } else {
                    $('body').addClass("loading");
                }
            },
            ajaxStop: function () {
                $('body').removeClass("loading");
                $('#action-visualizer').css('display', 'none');
            }
        });
    },
    bindSaveSurveyAttributeOnEdit: function () {
        "use strict";
        let fields = $('.js-survey-attribute-form-field');
        $.each(fields, function (index, field) {
            field = $(field);
            field.change(function () {
                let form = $(field).closest('form');
                console.log(form);
                $.ajax({
                    url: form.attr('action'),
                    method: 'post',
                    data: form.serialize()
                })
                    .done(function () {
                        popoverTool.showAndDestroySavePopover(field);
                    });
            });
        });
    }
};
