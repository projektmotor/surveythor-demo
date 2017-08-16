var projektmotor = projektmotor || {};

projektmotor.Result = function () {
    "use strict";
    var result;

    result = {
        init: function () {
            result.delegateRadios();
        },
        delegateRadios: function () {
            $('body').delegate('.choice-answer input', 'change', function() {
                var form = $(this).closest('form');
                $.ajax({
                    url: form.attr('action'),
                    method: 'post',
                    data: form.serialize(),
                    success: function(response) {
                        $('#result').html($(response).find('#result').html());

                        $('.question-childanswer .result_answers-question_answer').each(function () {
                            var parentId = $(this).data('parent-id');
                            var parentInput = $('input[data-answer-id='+parentId+']');
                            var target = parentInput.parents('div').first();
                            var type = parentInput.attr('type');

                            //if (type === 'checkbox') {
                            //    $(this).detach();
                            //    target.append($(this));
                            //}
                        });
                    }
                });
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
