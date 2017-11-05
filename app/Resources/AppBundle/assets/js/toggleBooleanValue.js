module.exports = {
    bindToggle: function () {
        "use strict";
        const parentElement = $('.js-toggle-boolean-value');

        const changeContent = function (newParentElement, currentTarget) {
            $.ajax(currentTarget.attr('href'))
                .done(function (response) {
                    newParentElement.replaceWith(response);
                });
        };

        const bindClick = function (parentElement) {
            parentElement.on('click', 'a', function (event) {
                event.preventDefault();
                const currentTarget = $(event.currentTarget);
                const newParentElement = $(this);
                currentTarget.addClass('disabled');
                changeContent(newParentElement, currentTarget);
            });
        };

        bindClick(parentElement);
    }
};
