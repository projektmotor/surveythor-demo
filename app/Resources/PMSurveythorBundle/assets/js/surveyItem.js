const dialog = require('./dialog');
const helper = require('./helper');
const sortable = require('./sortable');

const options = {
    backendTitleLength: 90
};

module.exports = {
    init: function () {
        let that = this;
        // keep value before input gets the focus, to save the value only if it has changed
        $('body').delegate(
            '#survey-elements input, #survey-elements textarea',
            'focus',
            function () {
                $(this).attr('data-value-on-focus', $(this).val());
            }
        );
        // save inputs at blur
        $('body').delegate(
            '#survey-elements input, #survey-elements textarea',
            'blur',
            function () {
                that.save(this);
            }
        );

        // open/close panels
        $('body').delegate('a.item-title, a.item-prefs', 'click', function (e) {
            e.preventDefault();
            that.collapse(this);
        });

        // bind delete button
        $('body').delegate('a.item-delete', 'click', function (e) {
            e.preventDefault();
            that.removeDialog(this);
        });
    },
    save: function (elem) {
        if ($(elem).val() !== $(elem).attr('data-value-on-focus')) {
            var form = $(elem).parents('form').first();
            var url = $(form).attr('action');
            $.ajax({
                url: url,
                method: 'post',
                data: form.serialize(),
                isLocal: true,
                success: function () {
                    var panel = $(elem).parents('.panel-default.survey-item').first();
                    var panelTitle = $(panel).children('.panel-heading').find('span.item-title-text');
                    var text = $(panel).find('.surveyitem-text').last().val();
                    var title = $(panel).find('.surveyitem-title').last().val();
                    title = title === '' ? text : title;

                    if (title.length > options.backendTitleLength) {
                        $(panelTitle).text(title.substring(0, options.backendTitleLength) + '...');
                    } else {
                        $(panelTitle).text(title);
                    }
                },
                error: function (response) {
                    console.log(response);
                }
            });
        }
    },
    collapse: function (a) {
        var panel = $(a).parents('.panel-heading').siblings('.panel-collapse');
        var isParent = $(panel).hasClass('parent-item');
        var formLoaded = $(panel).hasClass('loaded');

        if (isParent && !formLoaded) {
            var url = $(a).attr('href');
            $.ajax({
                url: url,
                method: 'GET',
                success: function (response) {
                    $(panel).html(response);
                    $(panel).addClass('loaded');
                    sortable.initSortable();
                }
            });
        }
        helper.collapse(panel);
    },
    removeDialog: function (link) {
        dialog.open('Wollen Sie dieses Element wirklich löschen?', dialog.buttonsYesNo(this.remove, link));
    },
    remove: function (link) {
        $.ajax({
            url: $(link).attr('href'),
            method: 'get',
            success: function (response) {
                if (response.status === 'OK') {
                    $('#item-' + response.item).parent('.survey-item').remove();
                } else if (response.status === 'FAIL') {
                    dialog.open(response.reason);
                }
            }
        });
    }
};
