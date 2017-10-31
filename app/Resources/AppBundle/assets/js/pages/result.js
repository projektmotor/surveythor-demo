require('jquery.scrollto');

module.exports = function (params) {

    let config = {};

    $.extend(
        config,
        {
            containerSelector: '#js-st-surveythor-container',
            buttonSelector: '.js-st-survey-button',
            surveyId: null,
            surveythorHost: null,
            surveythorUri: '/result/first/'
        },
        params
    );


    const container = $(config.containerSelector);

    config.surveythorUri = config.surveythorHost + config.surveythorUri + config.surveyId;

    function bindClickOnButton() {
        $(container).on('click', config.buttonSelector, function (e) {
            e.preventDefault();

            let form = container.find('form').first();
            let url = $(e.target).data('url');
            let data = form.serialize();

            if (data === '') {
                // we have to send at least this to trigger form submit
                data = {'result_item': ''};
            }

            $.ajax({
                url: url,
                method: 'post',
                data: data,
                success: function (response) {
                    if (response.url !== undefined) {
                        $.ajax({
                            url: response.url,
                            method: 'post',
                            success: function (response) {
                                container.html(response);
                            }
                        });
                    } else {
                        container.html(response);
                    }
                    $(window).scrollTo(container);
                }
            });
        });
    }

    function initialize() {
        bindClickOnButton();

        $.ajax({
            url: config.surveythorUri,
            method: 'post',
            success: function (response) {
                container.html(response);
            }
        });
    }

    $(function () {
        initialize();
    });
};
