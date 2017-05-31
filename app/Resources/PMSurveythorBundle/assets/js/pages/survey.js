/*global $:false, jQuery:false */
/*jslint node: true */
/*jshint esversion: 6 */
/*jshint sub:true*/
/*jshint browser: true */
/*global CKEDITOR: false */
"use strict";

var projektmotor = projektmotor || {};

projektmotor.Survey = function (surveyParams) {
    var
        params = {},
        question,
        helpers
    ;

   $.extend(params, {//{{{
        questionCollectionHolder: '#survey_questions'
    }, surveyParams);//}}}

    question = {//{{{
        init: function () {
            question.bindAdd();
            $(params.questionCollectionHolder).data('index', 0);
        },
        bindAdd: function () {
            var addQuestionLink = $('<a href="#" class="add-question">Add a question</a>');
            var newLink = $('<span></span>').append(addQuestionLink);
            var questionCollectionHolder = $(params.questionCollectionHolder);

            questionCollectionHolder.append(newLink);

            newLink.click(function (e) {
                e.preventDefault();

                helpers.addFormFromPrototype(questionCollectionHolder, newLink);

                var addAnswerLink = $('<a href="#" class="add-question">Add an answer</a>');
                var newAnswerLink = $('<span></span>').append(addAnswerLink);
                var questionIndex = $(params.questionCollectionHolder).data('index') - 1;
                var answerCollectionHolder = $('#survey_questions_'+ questionIndex +'_answers');

                if (typeof answerCollectionHolder.data('index') == 'undefined') {
                    answerCollectionHolder.data('index', 0);
                }

                answerCollectionHolder.append(newAnswerLink);

                addAnswerLink.click(function (e) {
                    e.preventDefault();
                    helpers.addFormFromPrototype(answerCollectionHolder, newAnswerLink, '__answer__');
                });
            });
        }
    };//}}}

    helpers = {//{{{
        addFormFromPrototype: function (collectionHolder, newLink, prototypeName = '__question__') {
            var prototype = collectionHolder.data('prototype');
            var index = collectionHolder.data('index');
            var re = new RegExp(prototypeName, 'g');
            var newForm = prototype.replace(re, index);

            collectionHolder.data('index', index + 1);

            var newFormDiv = $('<div></div>').append(newForm);
            newLink.before(newFormDiv);
        }
    };//}}}

    (function () {
        question.init();
    }());
};

$(document).ready(function() {
    new projektmotor.Survey();
});
