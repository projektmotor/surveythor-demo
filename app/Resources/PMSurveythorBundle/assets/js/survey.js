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
    bindSaveTitleOnEdit: function () {
        "use strict";
        $('.survey-title-field').change(function () {
            var form = $('form[name=survey_title]');
            $.ajax({
                url: form.attr('action'),
                method: 'post',
                data: form.serialize(),
                isLocal: true,
                success: function () {
                } // mal sehen
            });
        });
    }
};
