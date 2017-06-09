/*global $:false, jQuery:false */
/*jslint node: true */
/*jshint esversion: 6 */
/*jshint sub:true*/
/*jshint browser: true */
/*global CKEDITOR: false */
"use strict";

var projektmotor = projektmotor || {};

projektmotor.Result = function (resultParams) {
    var
        params = {},
        result
    ;

    result = {
        init: function () {
            result.delegateRadios();
        },
        delegateRadios: function () {
            $('body').delegate('.multiple-choice-answers input', 'change', function() {
                var form = $(this).closest('form');
                $.ajax({
                    url: form.attr('action'),
                    method: 'post',
                    data: form.serialize(),
                    success: function(response) {
                        $('#result').html($(response).find('#result').html());
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
