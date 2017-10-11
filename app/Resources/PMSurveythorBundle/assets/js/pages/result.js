module.exports = function (params) {

    let config = {};

    $.extend(
        config,
        {
            containerSelector: '#js-st-surveythor-container',
            nextSelector: '.js-st-survey-next',
            surveythorUri: null
        },
        params
    );


    const container = $(config.containerSelector);

    function bindClickOnNext() {
        $('body').delegate(config.nextSelector, 'click', function (e) {
            e.preventDefault();

            let form = container.find('form').first();
            let url = container.find(config.nextSelector).data('next-url');

            $.ajax({
                url: url,
                method: 'post',
                data: form.serialize(),
                success: function (response) {
                    let data = JSON.parse(response);
                    if (data.status === 'OK') {
                        container.html(data.html);
                    }
                    if (data.status === 'finished') {
                        window.location = data.url;
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
                let data = JSON.parse(response);
                container.html(data.html);
            }
        });
    }

    $(function () {
        initialize();
    });
};
