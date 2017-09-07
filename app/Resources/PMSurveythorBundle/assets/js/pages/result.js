var projektmotor = projektmotor || {};

projektmotor.Result = function () {
    "use strict";
    var result;

    result = {
        init: function () {
            result.delegateNext();
        },
        delegateNext: function () {
            $('body').delegate('#survey-next', 'click', function (e) {
                e.preventDefault();
                result.getNext();
            });
        },
        getNext: function () {
            var form = $('#result').find('form').first();
            var url = $('#survey-next').data('next-url');

            $.ajax({
                url: url,
                method: 'post',
                data: form.serialize(),
                success: function (response) {
                    try {
                        var data = JSON.parse(response);
                        window.location = data.url;
                    } catch (e) {
                        $('#result').html(response);
                    }
                }
            });
        }
    };

    (function () {
        result.init();
    }());
};

$(document).ready(function() {
    new projektmotor.Result();
});
