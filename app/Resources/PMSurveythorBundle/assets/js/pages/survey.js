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
        saveQuestionLink: 'a#save-question'
    }, surveyParams);//}}}

    question = {//{{{
        init: function () {
            $(params.saveQuestionLink).css('display', 'none');
            $(params.questionCollectionHolder).data('index', 0);
            $('.question.panel').css('display', 'none');
            $('#default-panel').css('display', 'none');

            question.bindAdd();
            //question.bindSave();
            question.bindTitleFields();
        },
        bindAdd: function () {
            var newLink = $(params.addQuestionLink);
            var questionCollectionHolder = $(params.questionCollectionHolder);

            newLink.click(function (e) {
                e.preventDefault();

                helpers.addFormFromPrototype(questionCollectionHolder, newLink);

                var addAnswerLink = $('<a href="#" class="add-question">Add an answer</a>');
                var newAnswerLink = $('<span></span>').append(addAnswerLink);
                var questionIndex = $(params.questionCollectionHolder).data('index') - 1;
                var answerCollectionHolder = $('#survey_questions_'+ questionIndex +'_answers');
                //var saveQuestionLink = $(params.saveQuestionLink);

                if (typeof answerCollectionHolder.data('index') == 'undefined') {
                    answerCollectionHolder.data('index', 0);
                }

                //$(params.addQuestionLink).css('display', 'none');
                //$('#survey_questions_' + questionIndex).append(saveQuestionLink);
                //saveQuestionLink.css('display', 'inline-block');

                answerCollectionHolder.append(newAnswerLink);
                $('.question.panel').css('display', 'inline-block');

                addAnswerLink.click(function (e) {
                    e.preventDefault();
                    helpers.addFormFromPrototype(answerCollectionHolder, newAnswerLink, '__answer__');
                    newAnswerLink.detach();
                    answerCollectionHolder.append(newAnswerLink);
                });
            });
        },
        //bindSave: function () {
        //    var saveLink = $(params.saveQuestionLink);

        //    saveLink.click(function () {
        //        var form = $(this).closest('form');

        //        $.ajax({
        //            url: form.attr('action'),
        //            method: 'post',
        //            data: form.serialize(),
        //            success: function () {
        //                // close all accordeons
        //                $(params.surveyForm).find('.in').each(function () {
        //                    $(params.addQuestionLink).css('display', 'inline-block');
        //                    $(params.saveQuestionLink).css('display', 'none');
        //                    $(this).removeClass('in');
        //                });
        //            }
        //        });
        //    });
        //},
        bindTitleFields: function () {
            $('body').delegate('.title-field', 'keydown', function() {
                var panelTitle = $(this).closest('.panel').children('.panel-heading').find('h4 a');
                console.log(panelTitle);
                panelTitle.text($(this).val());
            });
        }
    };//}}}

    helpers = {//{{{
        addFormFromPrototype: function (collectionHolder, newLink, prototypeName = '__question__') {
            var prototype = collectionHolder.data('prototype');
            var index = collectionHolder.data('index');
            var re = new RegExp(prototypeName, 'g');
            var newForm = prototype.replace(re, index);
            var id = helpers.makeId();

            collectionHolder.data('index', index + 1);

            var newFormDiv = $($('#default-panel').html());
            $(newFormDiv).find('a.collapsed').attr('href', '#' + id);
            $(newFormDiv).find('a.collapsed').attr('aria-controls', id);
            $(newFormDiv).find('div.panel-collapse').attr('id', id);
            $(newFormDiv).find('div.panel').attr('id', 'panel-' + id);

            $(newFormDiv).find('.panel-body').append(newForm);
            collectionHolder.append(newFormDiv);
        },
        makeId: function () {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

            for( var i=0; i < 5; i++ )
                text += possible.charAt(Math.floor(Math.random() * possible.length));

            return text;
        }
    };//}}}

    (function () {
        question.init();
    }());
};

$(document).ready(function() {
    new projektmotor.Survey();
});
