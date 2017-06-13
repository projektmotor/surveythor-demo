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
            question.bindAdd();
            question.bindAddAnswer();
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
            });
        },
        bindAddChildQuestion: function () {
            $('body').delegate('.add-child-question', 'click', function(e) {
                e.preventDefault();
                var parentCollectionHolder = $(this).parents('.question-answer-prototype');
                var index = $('#' + parentCollectionHolder.attr('id') + ' .add-child-question').index(this);
                var collectionHolderId = parentCollectionHolder.attr('id') + '_' + index + '_childQuestions';
                var collectionHolder = $('#' + collectionHolderId);

                collectionHolder.closest('.panel-collapse').addClass('in');
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
                    }
                });
            });
        },
        bindAddAnswer: function () {
            $('body').delegate('a.add-answer', 'click',  function(e) {
                e.preventDefault();
                var collectionHolder = $(this).closest('.question-answer-prototype');
                var link = collectionHolder.find('a.add-answer');

                helpers.addFormFromPrototype(collectionHolder, '__answer__');
                $(collectionHolder).find('.add-child-question').css('display', 'inline-block');
                link.detach();
                collectionHolder.append(link);
            });
        }

    };//}}}

    helpers = {//{{{
        addFormFromPrototype: function (collectionHolder, prototypeName = '__question__') {
            var prototype = collectionHolder.data('prototype');
            var index = collectionHolder.data('index');
            var re = new RegExp(prototypeName, 'g');
            var newFormDiv = $($('#default-panel').html());

            if (typeof index == 'undefined') {
                index = collectionHolder.children('.panel').length;
                collectionHolder.prop('data-index', index);
            }

            var id = collectionHolder.attr('id') + '_' + index + '_panel';
            var newForm = prototype.replace(re, index);

            $(newFormDiv).attr('id', 'panel-' + id);
            $(newFormDiv).find('a.collapsed').attr('href', '#' + id);
            $(newFormDiv).find('a.collapsed').attr('aria-controls', id);
            $(newFormDiv).find('div.panel-collapse').attr('id', id);

            if (-1 === prototypeName.indexOf('answer')) {
                $(newFormDiv).find('.add-child-question').remove();
            }

            $(newFormDiv).find('.panel-body').append(newForm);
            collectionHolder.append(newFormDiv);
            collectionHolder.data('index', index + 1);
        }
    };//}}}

    (function () {
        question.init();
    }());
};

$(document).ready(function() {
    new projektmotor.Survey();
});
