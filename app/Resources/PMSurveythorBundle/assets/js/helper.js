module.exports = {
    collapse: function (panel) {
        var indicator = $(panel).siblings('.panel-heading')
            .find('.panel-title .panel-indicator');

        if ($(panel).hasClass('in')) {
            $(panel).removeClass('in');
            $(panel).css('display', 'none');
            $(indicator).find('.panel-indicator-bottom').css('display', 'block');
            $(indicator).find('.panel-indicator-top').css('display', 'none');
        } else {
            $(panel).addClass('in');
            $(panel).css('display', 'block');
            $(indicator).find('.panel-indicator-bottom').css('display', 'none');
            $(indicator).find('.panel-indicator-top').css('display', 'block');
        }
    },
    addFormFromPrototype: function (collectionHolder, prototypeName = '__choice__') {
        var prototype = collectionHolder.data('prototype');
        var index = collectionHolder.children('.panel-default').length;
        var re = new RegExp(prototypeName, 'g');
        var newForm = prototype.replace(re, index);
        collectionHolder.append(newForm);
        this.collapse($(newForm).find('.panel-collapse'));
    }
};
