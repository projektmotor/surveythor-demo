module.exports = function (params) {

    let config = {};

    $.extend(
        config,
        {
            containerSelector: '#js-st-surveythor-container',
            nextSelector: '.js-st-survey-next',
            surveyId: null,
            surveythorHost: null,
            surveythorUri: '/result/first/'
        },
        params
    );

    config.surveythorUri = config.surveythorHost + config.surveythorUri + config.surveyId;

    const container = $(config.containerSelector);

    function bindClickOnNext() {
        container.on('click', config.nextSelector, function (e) {
            e.preventDefault();

            let form = container.find('form').first();
            let url = container.find(config.nextSelector).data('next-url');

            $.ajax({
                url: url,
                method: 'post',
                data: form.serialize(),
                success: function (response) {
                    try {
                        let data = JSON.parse(response);
                        window.location = data.url;
                    } catch (e) {
                        $('#result').html(response);
                    }
                }
            });
        });
    }

    function initialize() {
        bindClickOnNext();

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
