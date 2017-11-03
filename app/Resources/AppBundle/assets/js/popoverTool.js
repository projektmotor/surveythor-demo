module.exports = {
    showAndDestroySavePopover: function (field) {
        "use strict";
        field.popover({
                'placement': 'top',
                'content': 'gespeichert'
            }
        ).popover('show');
        window.setTimeout(function () {
            field.popover('destroy');
        }, 1000);
    }
};
