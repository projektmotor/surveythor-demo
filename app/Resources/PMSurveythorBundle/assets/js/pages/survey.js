require('bootstrap-sass');
require('jquery-ui');
require('jquery-ui/ui/widgets/sortable/');


var projektmotor = projektmotor || {};

projektmotor.Survey = function (surveyParams) {
    "use strict";

    var
        params = {},
        panel,
        question,
        resultRange,
        helpers
    ;

   $.extend(params, {//{{{
        surveyForm: 'form[name=survey]',
        questionCollectionHolder: '#survey_questions',
        addQuestionLink: 'a#add-question',
        addResultRangeLink: 'a#add-resultrange',
        resultRangeCollectionHolder: '#survey_resultRanges'
    }, surveyParams);//}}}

    panel = {
        init: function () {
            panel.bindRemove();
            panel.initSortable();
        },
        bindRemove: function () {
            $('body').delegate('a.remove-panel', 'click', function (e) {
                e.preventDefault();
                $(this).closest('.panel').remove();
            });
        },
        initSortable: function() {
            $('.sortable').sortable({
                update: function(event) {
                    for (var i = 0; i < event.target.children.length; i++ ) {
                        var child = event.target.children[i];
                        $(child).find('input.sortorder').val(i);
                    }

                    var form = $(this).closest('form');
                    var url = $('div#survey').data('saveurl');

                    $.ajax({
                        data: form.serialize(),
                        url: url,
                        method: 'post',
                        success: function (response) {
                            console.log(response);
                        }
                    });
                }
            });
        }
    };

    question = {//{{{
        init: function () {
            question.bindAdd();
            question.addChoice();
            question.bindAddChildQuestion();
            question.bindTitleFields();
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
                var panel = $(this).parents('.panel').first();
                var index = parentCollectionHolder.children('.panel').index(panel);
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
        addChoice: function () {
            $('body').delegate('a.add-answer', 'click',  function(e) {
                e.preventDefault();
                var collectionHolder = $(this).closest('.question-answer-prototype');
                var link = collectionHolder.find('a.add-answer');

                helpers.addFormFromPrototype(collectionHolder, '__choice__');
                $(collectionHolder).find('.add-child-question').css('display', 'inline-block');
                link.detach();
                collectionHolder.append(link);
            });
        }

    };//}}}

    resultRange = {
        init: function () {
            resultRange.bindAdd();
        },
        bindAdd: function () {
            var newLink = $(params.addResultRangeLink);
            var collectionHolder = $(params.resultRangeCollectionHolder);

            newLink.click(function (e) {
                e.preventDefault();
                helpers.addFormFromPrototype(collectionHolder, '__resultRange__');
            });
        },

    };

    helpers = {//{{{
        addFormFromPrototype: function (collectionHolder, prototypeName = '__question__') {
            var prototype = collectionHolder.data('prototype');
            var index = collectionHolder.data('index');
            var re = new RegExp(prototypeName, 'g');
            var newFormDiv = $($('#default-panel').html());

            if (typeof index === 'undefined') {
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
        panel.init();
        question.init();
        resultRange.init();
    }());
};

$(document).ready(function() {
    new projektmotor.Survey();
});
