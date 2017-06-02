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
        surveyForm: 'form[name=survey]',
        questionCollectionHolder: '#survey_questions',
        addQuestionLink: 'a#add-question',
    }, surveyParams);//}}}

    question = {//{{{
        init: function () {
            $(params.questionCollectionHolder).data('index', 0);
            $('.question.panel').css('display', 'none');
            $('#default-panel').css('display', 'none');

            question.bindAdd();
            question.bindAddChildQuestion();
            question.bindTitleFields();
            question.bindRemovePanel();
        },
        bindAdd: function () {
            var newLink = $(params.addQuestionLink);
            var questionCollectionHolder = $(params.questionCollectionHolder);

            newLink.click(function (e) {
                e.preventDefault();

                helpers.addFormFromPrototype(questionCollectionHolder);
                helpers.addAnswerLink($(params.questionCollectionHolder));

                $('.question.panel').css('display', 'block');
            });
        },
        bindAddChildQuestion: function () {
            $('body').delegate('.add-child-question', 'click', function(e) {
                e.preventDefault();
                var collectionHolder = $(this).parents('div').prev().find('.child-question-prototype');
                console.log(collectionHolder);

                helpers.addFormFromPrototype(collectionHolder, '__child_question__');
                helpers.addAnswerLink($(collectionHolder), true);
            });
        },
        bindTitleFields: function () {
            $('body').delegate('.title-field', 'keyup', function() {
                var panelTitle = $(this).closest('.panel').children('.panel-heading').find('h4 a');
                panelTitle.text($(this).val());
            });
        },
        bindRemovePanel: function () {
            $('body').delegate('a.remove-panel', 'click', function (e) {
                e.preventDefault();

                var id = $(this).attr('id').substr(7);
                $('#panel-' + id).remove();
            });
        }
    };//}}}

    helpers = {//{{{
        addFormFromPrototype: function (collectionHolder, prototypeName = '__question__') {
            var prototype = collectionHolder.data('prototype');
            var index = collectionHolder.data('index');
            var re = new RegExp(prototypeName, 'g');
            var id = helpers.makeId();
            var newFormDiv = $($('#default-panel').html());

            if (typeof index == 'undefined') {
                index = 0;
                collectionHolder.prop('data-index', index);
            }
            var newForm = prototype.replace(re, index);

            $(newFormDiv).attr('id', 'panel-' + id);
            $(newFormDiv).find('a.collapsed').attr('href', '#' + id);
            $(newFormDiv).find('a.collapsed').attr('aria-controls', id);
            $(newFormDiv).find('div.panel-collapse').attr('id', id);
            $(newFormDiv).find('a.remove-panel').attr('id', 'remove-' + id);

            $(newFormDiv).find('.panel-body').append(newForm);
            collectionHolder.append(newFormDiv);
            collectionHolder.data('index', index + 1);
        },
        makeId: function () {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

            for( var i=0; i < 5; i++ )
                text += possible.charAt(Math.floor(Math.random() * possible.length));

            return text;
        },
        addAnswerLink: function (collectionHolder, isChildQuestion = false) {
            var addAnswerLink = $('<a href="#" class="add-question">Add an answer</a>');
            var newAnswerLink = $('<div></div>').append(addAnswerLink);
            var prototypeName = '__answer__';
            var selector = '.question-answer-prototype';
            var index, answerCollectionHolder;

            if (isChildQuestion) {
                selector = '.child-question-answer-prototype';
                prototypeName = '__child_question_answer__';
            }
            answerCollectionHolder = $('body').find(selector);
            index = collectionHolder.find(selector).length - 1;
            if (index > 0) {
                answerCollectionHolder = $(selector + ':eq('+index+')');
            }

            if (typeof collectionHolder.data('index') == 'undefined') {
                collectionHolder.data('index', 0);
            }

            if (typeof answerCollectionHolder.data('index') == 'undefined') {
                answerCollectionHolder.data('index', 0);
            }

            answerCollectionHolder.append(newAnswerLink);

            addAnswerLink.click(function (e) {
                e.preventDefault();

                var answerIndex = answerCollectionHolder.data('index');
                var addChildQuestionLink = $('<a href="#" class="add-child-question">Add a child question</a>');
                var newChildQuestionLink = $('<div></div>').append(addChildQuestionLink);

                helpers.addFormFromPrototype(answerCollectionHolder, prototypeName);

                if (!isChildQuestion) {
                    $(answerCollectionHolder).append(newChildQuestionLink);
                }

                newAnswerLink.detach();
                answerCollectionHolder.append(newAnswerLink);
            });

        }
    };//}}}

    (function () {
        question.init();
    }());
};

$(document).ready(function() {
    new projektmotor.Survey();
});
