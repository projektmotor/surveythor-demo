var projektmotor = projektmotor || {};

projektmotor.Result = function () {
    "use strict";
    var result;

    result = {
        init: function () {
            result.delegateNext();
            result.getNext();
        },
        delegateNext: function () {
            $('body').delegate('#survey-next', 'click', function () {
                result.getNext();
            });
        },
        getNext: function () {
            var form = $('form[name=answer]');
            var url = $('#survey-next').data('next-url');

            $.ajax({
                url: url,
                method: 'post',
                data: form.serialize(),
                success: function (response) {
                    $('#result').html(response);
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
