require('jquery-ui/ui/widgets/sortable/');
const helper = require('./helper');

module.exports = {
    init: function () {
        this.initSortable();
    },

    initSortable: function () {
        let that = this;
        $('.sortable').sortable({
            axis: 'y',
            cursor: 'move',
            items: '.survey-item',
            containment: "parent",
            update: function (event, ui) {
                if (!$(ui.item).hasClass('new-item')) {
                    that.helpers.sort(event.target);
                }
            },
            stop: function (event, ui) {
                if ($(ui.item).hasClass('new-item')) {
                    var url = ui.item.attr('data-url');
                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function (response) {
                            $(ui.item).before(response.html);

                            that.helpers.openPanels(response.open);

                            $(ui.item).remove('.new-item');
                            that.helpers.sort(event.target);
                            that.initSortable();
                        }
                    });
                }
            }
        });
        $('.sortable-itemgroup').sortable({
            axis: 'y',
            cursor: 'move',
            items: '.survey-item',
            containment: "parent",
            update: function (event, ui) {
                if (!$(ui.item).hasClass('new-item')) {
                    that.helpers.sort(event.target);
                }
            },
            stop: function (event, ui) {
                if ($(ui.item).hasClass('new-item')) {
                    var draggableConnect = event.target;
                    var url = ui.item.attr('data-itemgroup-add-item-url');
                    var parentGroup = $(draggableConnect).parents('.panel-collapse').first().attr('id').substring(5);
                    var form = $(ui.item).closest('form[name=surveyitem]');
                    var sortOrder = $(ui.item).index();
                    $.ajax({
                        url: url + '?parent=' + parentGroup + '&sortorder=' + sortOrder,
                        method: 'POST',
                        data: form.serialize(),
                        success: function (response) {
                            $('#item-' + response.root).html(response.html);
                            $('#item-' + response.root).removeClass('in');
                            $(draggableConnect).remove();
                            that.helpers.openPanels(response.open);
                            that.initSortable();
                        }
                    });
                }
            }
        });
    },

    helpers: {
        sort: function (sortableList) {
            for (var i = 0; i < sortableList.children.length; i++) {
                var child = sortableList.children[i];
                $.ajax({
                    isLocal: true,
                    url: $(child).attr('data-sortorder-url') + '?sortorder=' + i,
                    method: 'GET'
                });
            }
        },
        openPanels: function (items) {
            for (var i = items.length - 1; i >= 0; i--) {
                var panel = $('#item-' + items[i]);
                helper.collapse(panel);
            }

        }
    }
};
