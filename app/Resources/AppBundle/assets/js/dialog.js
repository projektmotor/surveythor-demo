require('jquery-ui/ui/widgets/dialog/');
require('jquery-ui/themes/base/dialog.css');

module.exports = {
    open: function (message, buttons = null) {
        $('#dialog').html(message);
        if (!buttons) {
            buttons = {
                'Gut': function () {
                    $(this).dialog('close');
                }
            };
        }
        $("#dialog").dialog({
            resizable: false,
            modal: true,
            title: "SurveyThor",
            height: 'auto',
            width: 400,
            close: function () {
                $('#dialog').html(null);
            },
            buttons: buttons
        });
    },

    buttonsYesNo: function (callback, link) {
        return {
            "Ja": function () {
                $(this).dialog('close');
                callback(link);
            },
            "Nein": function () {
                $(this).dialog('close');
            }
        };
    }
};
