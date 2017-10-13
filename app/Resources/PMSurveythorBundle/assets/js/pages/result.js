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

            $.ajax({
                url: url,
                method: 'post',
                data: form.serialize(),
                success: function (response) {
                    if (response.status === 'OK') {
                        container.html(response.html);
                    }
                    if (response.status === 'finished') {
                        window.location = response.url;
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
                container.html(response.html);
            }
        });
    }

    $(function () {
        initialize();
    });
};
