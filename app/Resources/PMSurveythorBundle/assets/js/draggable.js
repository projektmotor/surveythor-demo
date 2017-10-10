require('jquery-ui/ui/widgets/draggable/');

module.exports = {
    bindDraggable: function (selector) {
        $(selector).draggable({
            cursor: 'crosshair',
            helper: 'clone',
            connectToSortable: '.draggable-connect',
            revert: false
        });
    }
};
