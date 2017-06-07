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
            $('#default-panel').css('display', 'none');

            $('.question-answer-prototype').each(function () {
                helpers.addAnswerLink($(this).parent('div').parent('div').attr('id'));
            });

            question.bindAdd();
            question.bindAddChildQuestion();
            question.bindTitleFields();
            question.bindRemovePanel();
            question.bindTypeSelect();
        },
        bindAdd: function () {
            var newLink = $(params.addQuestionLink);
            var questionCollectionHolder = $(params.questionCollectionHolder);

            newLink.click(function (e) {
                e.preventDefault();
                helpers.addFormFromPrototype(questionCollectionHolder);
                $('.question.panel').css('display', 'block');
            });
        },
        bindAddChildQuestion: function () {
            $('body').delegate('.add-child-question', 'click', function(e) {
                e.preventDefault();
                var collectionHolder = $(this).closest('.question-answer-prototype');
                var index = collectionHolder.data('index') - 1;
                var collectionHolderId = $(this).parents('.question-answer-prototype').attr('id') + '_' + index + '_childQuestions';

                collectionHolder = $('#' + collectionHolderId);
                helpers.addFormFromPrototype(collectionHolder, '__question__');
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
                $(this).closest('.panel').remove();
            });
        },
        bindTypeSelect: function () {
            $('body').delegate('.question-type-select', 'change', function () {
                var form = $(this).closest('form');
                var url = form.attr('action');
                var containerId = $(this).attr('id').substring(0, $(this).attr('id').length - 5);

                $.ajax({
                    data: form.serialize(),
                    url: url,
                    method: 'post',
                    success: function (response) {
                        $('#' + containerId).html($(response).find('#' + containerId).html());
                        helpers.addAnswerLink(containerId);
                    }
                });
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
                index = collectionHolder.children('.panel').length;
                collectionHolder.prop('data-index', index);
            }
            var newForm = prototype.replace(re, index);

            $(newFormDiv).attr('id', 'panel-' + id);
            $(newFormDiv).find('a.collapsed').attr('href', '#' + id);
            $(newFormDiv).find('a.collapsed').attr('aria-controls', id);
            $(newFormDiv).find('div.panel-collapse').attr('id', id);

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
        addAnswerLink: function (containerId) {
            var addAnswerLink = $('<a href="#" class="add-question">Add an answer</a>');
            var newAnswerLink = $('<div></div>').append(addAnswerLink);
            var answerCollectionHolder, isChildQuestion;
            console.log(containerId);

            answerCollectionHolder = $('#' + containerId + '_answers');
            answerCollectionHolder.append(newAnswerLink);

            addAnswerLink.click(function (e) {
                e.preventDefault();

                var answerIndex = typeof answerCollectionHolder.data('index') == 'undefined'
                    ? answerCollectionHolder.children('.panel').length
                    : answerCollectionHolder.data('index')
                ;
                var addChildQuestionLink = $('<a href="#" class="add-child-question">Add a child question</a>');

                helpers.addFormFromPrototype(answerCollectionHolder, '__answer__');

                $(answerCollectionHolder)
                    .find('.question-toolbox:eq('+ (answerIndex) +')')
                    .append(addChildQuestionLink);

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
