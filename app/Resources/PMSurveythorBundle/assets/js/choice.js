const helper = require('./helper');
const dialog = require('./dialog');

module.exports = {
    init: function () {
        let that = this;
        // collapse title
        $('body').delegate('a.choice-title', 'click', function (e) {
            e.preventDefault();
            var panel = $(this).parents('.panel-heading').siblings('.panel-collapse');
            helper.collapse(panel);
        });
        // add new choice
        $('body').delegate('.add-new-choice', 'click', function (e) {
            e.preventDefault();
            that.add($(this).siblings('.question-answer-prototype').first());
        });
        // remove choice
        $('body').delegate('a.remove-choice', 'click', function (e) {
            e.preventDefault();
            that.removeDialog(this);
        });
    },
    add: function (collectionHolder) {
        helper.addFormFromPrototype(collectionHolder, '__choice__');
        helper.collapse($(collectionHolder).children().last().children('.panel-collapse'));
    },
    removeDialog: function (a) {
        dialog.open('Wollen Sie diese Antwort wirklich löschen?', dialog.buttonsYesNo(this.remove, a));
    },
    remove: function (a) {
        var panel = $(a).parents('.panel.choice');

        if (typeof $(a).attr('data-url') !== 'undefined') {
            var url = $(a).attr('data-url');

            $.ajax({
                url: url,
                method: 'post',
                isLocal: true,
                done: function (response) {
                    response = JSON.parse(response);
                    if (response.status === 'OK') {
                        $(panel).remove();
                    } else if (response.status === 'FAIL') {
                        dialog.open(response.reason);
                    }
                },
                fail: function (response) {
                    console.log(response);
                }
            });

            return;
        }

        $(panel).remove();
    }
};
