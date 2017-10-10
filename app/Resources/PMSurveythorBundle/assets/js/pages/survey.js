require('bootstrap-sass');
require('jquery-ui');
require('jquery-ui/ui/widgets/draggable/');
require('jquery-ui/ui/widgets/sortable/');
require('jquery-ui/ui/widgets/dialog/');
require('bootstrap-sass/assets/javascripts/bootstrap/affix.js');
require('jquery-ui/themes/base/dialog.css');


var projektmotor = projektmotor || {};

projektmotor.Survey = function () {
    "use strict";

    var
        survey,
        surveyItem,
        draggable,
        sortable,
        toolbox,
        dialog,
        choice,
        helpers
    ;

    survey = {
        init: function () {
            // begin disable form on website loading
            $(document).on({
                ajaxSend: function(e, jqXHR, options) {
                    if (options.isLocal) {
                        $('#action-visualizer').css('display', 'block');
                    } else {
                        $('body').addClass("loading");
                    }
                },
                ajaxStop: function() {
                    $('body').removeClass("loading");
                    $('#action-visualizer').css('display', 'none');
                }
            });
            // end disable form on website loading

            // begin save title on edit
            $('.survey-title-field').change(function() {
                var form = $('form[name=survey_title]');
                $.ajax({
                    url: form.attr('action'),
                    method: 'post',
                    data: form.serialize(),
                    isLocal: true,
                    success: function () {} // mal sehen
                });
            });
            // end save title on edit
        }
    },

    surveyItem = {
        options : {
            backendTitleLength: 90
        },
        init: function () {

            // keep value before input gets the focus, to save the value only if it has changed
            $('body').delegate(
                '#survey-elements input, #survey-elements textarea',
                'focus',
                function() {
                    $(this).attr('data-value-on-focus', $(this).val());
                }
            );
            // save inputs at blur
            $('body').delegate(
                '#survey-elements input, #survey-elements textarea',
                'blur',
                function() {
                    surveyItem.save(this);
                }
            );

            // open/close panels
            $('body').delegate('a.item-title, a.item-prefs', 'click', function(e) {
                e.preventDefault();
                surveyItem.collapse(this);
            });

            // bind delete button
            $('body').delegate('a.item-delete', 'click', function(e) {
                e.preventDefault();
                surveyItem.removeDialog(this);
            });
        },
        save: function (elem) {
            if ($(elem).val() !== $(elem).attr('data-value-on-focus')) {
                var form = $(elem).parents('form').first();
                var url = $(form).attr('action');
                $.ajax({
                    url: url,
                    method: 'post',
                    data: form.serialize(),
                    isLocal: true,
                    success: function () {
                        var panel = $(elem).parents('.panel-default.survey-item').first();
                        var panelTitle = $(panel).children('.panel-heading').find('span.item-title-text');
                        var text = $(panel).find('.surveyitem-text').last().val();
                        var title = $(panel).find('.surveyitem-title').last().val();
                        title = title === '' ? text : title;

                        if (title.length > surveyItem.options.backendTitleLength) {
                            $(panelTitle).text(title.substring(0, surveyItem.options.backendTitleLength) + '...');
                        } else {
                            $(panelTitle).text(title);
                        }
                    }
                });
            }
        },
        collapse: function (a) {
            var panel = $(a).parents('.panel-heading').siblings('.panel-collapse');
            var isParent = $(panel).hasClass('parent-item');
            var formLoaded = $(panel).hasClass('loaded');

            if (isParent && !formLoaded) {
                var url = $(a).attr('href');
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        $(panel).html(response);
                        $(panel).addClass('loaded');
                        sortable.initSortable();
                    }
                });
            }
            helpers.collapse(panel);
        },
        removeDialog: function (link) {
            dialog.open('Wollen Sie dieses Element wirklich löschen?', dialog.buttonsYesNo(surveyItem.remove, link));
        },
        remove: function (link) {
            $.ajax({
                url: $(link).attr('href'),
                method: 'get',
                success: function (response) {
                    response = JSON.parse(response);
                    if (response.status === 'OK') {
                        $('#item-' + response.item).parent('.survey-item').remove();
                    } else if(response.status === 'FAIL') {
                        dialog.open(response.reason);
                    }
                }
            });
        }
    },

        // bind draggable
    draggable = {
        init: function () {
            $('#new-items > div').draggable(draggable.draggableOptions);
        },
        draggableOptions: {
            cursor: 'crosshair',
            helper: 'clone',
            connectToSortable: '.draggable-connect',
            revert: false
        }
    },

    toolbox = {
        init: function() {
            // lock toolbox on page
            $('#survey-tools').affix({});
        }
    },

    sortable = {
        init: function () {
            sortable.initSortable();
        },

        initSortable: function() {
            $('.sortable').sortable({
                axis: 'y',
                cursor: 'move',
                items: '.survey-item',
                containment: "parent",
                update: function(event, ui) {
                    sortable.helpers.updateSortOrders(ui.item, event.target);
                },
                stop: function(event, ui) {
                    if ($(ui.item).hasClass('new-item')) {
                        var url = ui.item.attr('data-url');
                        $.ajax({
                            url: url,
                            method: 'GET',
                            success: function (response) {
                                response = JSON.parse(response);
                                $(ui.item).before(response.html);

                                sortable.helpers.openPanels(response.open);

                                $(ui.item).remove('.new-item');
                                sortable.helpers.sort(event.target);
                                sortable.initSortable();
                            }
                        });
                    }
                }
            });
            $('.sortable-itemgroup').sortable({
                axis: 'y',
                cursor: 'move',
                items: '.survey-item',
                containment: "parent",
                update: function(event, ui) {
                    sortable.helpers.updateSortOrders(ui.item, event.target);
                },
                stop: function(event, ui) {
                    if ($(ui.item).hasClass('new-item')) {
                        var draggableConnect = event.target;
                        var url = ui.item.attr('data-itemgroup-add-item-url');
                        var parentGroup = $(draggableConnect).parents('.panel-collapse').first().attr('id').substring(5);
                        var form = $(ui.item).closest('form[name=surveyitem]');
                        var sortOrder = $(ui.item).index();
                        $.ajax({
                            url: url + '?parent=' + parentGroup + '&sortorder=' + sortOrder,
                            method: 'POST',
                            data: form.serialize(),
                            success: function (response) {
                                response = JSON.parse(response);
                                $('#item-' + response.root).html(response.html);
                                $('#item-' + response.root).removeClass('in');
                                $(draggableConnect).remove();
                                sortable.helpers.openPanels(response.open);
                                sortable.initSortable();
                            }
                        });
                    }
                }
            });
        },

        helpers : {
            sort: function (sortableList) {
                for (var i = 0; i < sortableList.children.length; i++ ) {
                    var child = sortableList.children[i];
                    $.ajax({
                        isLocal: true,
                        url: $(child).attr('data-sortorder-url') + '?sortorder=' + i,
                        method: 'GET'
                    });
                }
            },
            openPanels: function (items) {
                for (var i = items.length - 1; i >= 0; i--) {
                    var panel = $('#item-' + items[i]);
                    helpers.collapse(panel);
                }

            },
            updateSortOrders: function (item, sortableList) {
                if (!$(item).hasClass('new-item')) {
                    sortable.helpers.sort(sortableList);
                }

            }
        }
    },

    dialog = {
        open: function (message, buttons = null) {
            $('#dialog').html(message);
            if (!buttons) {
                buttons = { 'Gut': function () {
                    $(this).dialog('close');
                }};
            }
            $("#dialog").dialog({
                resizable: false,
                modal: true,
                title: "SurveyThor",
                height: 'auto',
                width: 400,
                close: function() { $('#dialog').html(null); },
                buttons: buttons
            });
        },

        buttonsYesNo: function (callback, link) {
            return {
                "Ja": function () {
                    $(this).dialog('close');
                    callback(link);
                },
                "Nein": function () {
                    $(this).dialog('close');
                }
            };
        }
    },

    choice = {
        init: function () {
            // collapse title
            $('body').delegate('a.choice-title', 'click', function(e) {
                e.preventDefault();
                var panel = $(this).parents('.panel-heading').siblings('.panel-collapse');
                helpers.collapse(panel);
            });
            // add new choice
            $('body').delegate('.add-new-choice', 'click', function (e) {
                e.preventDefault();
                choice.add($(this).siblings('.question-answer-prototype').first());
            });
            // remove choice
            $('body').delegate('a.remove-choice', 'click', function (e) {
                e.preventDefault();
                choice.removeDialog(this);
            });
        },
        add: function (collectionHolder) {
            helpers.addFormFromPrototype(collectionHolder, '__choice__');
            helpers.collapse($(collectionHolder).children().last().children('.panel-collapse'));
        },
        removeDialog: function (a) {
            dialog.open('Wollen Sie diese Antwort wirklich löschen?', dialog.buttonsYesNo(choice.remove, a));
        },
        remove: function (a) {
            var panel = $(a).parents('.panel.choice');

            if (typeof $(a).attr('data-url') !== 'undefined') {
                var url = $(a).attr('data-url');

                $.ajax({
                    url: url,
                    method: 'post',
                    isLocal: true,
                    success: function (response) {
                        response = JSON.parse(response);
                        if (response.status === 'OK') {
                            $(panel).remove();
                        } else if(response.status === 'FAIL') {
                            dialog.open(response.reason);
                        }
                    }
                });

                return;
            }

            $(panel).remove();
        }
    },

    helpers = {
        collapse: function (panel) {
            var indicator = $(panel).siblings('.panel-heading')
                .find('.panel-title .panel-indicator');

            if ($(panel).hasClass('in')) {
                $(panel).removeClass('in');
                $(panel).css('display', 'none');
                $(indicator).find('.panel-indicator-bottom').css('display', 'block');
                $(indicator).find('.panel-indicator-top').css('display', 'none');
            } else {
                $(panel).addClass('in');
                $(panel).css('display', 'block');
                $(indicator).find('.panel-indicator-bottom').css('display', 'none');
                $(indicator).find('.panel-indicator-top').css('display', 'block');
            }
        },
        addFormFromPrototype: function (collectionHolder, prototypeName = '__choice__') {
            var prototype = collectionHolder.data('prototype');
            var index = collectionHolder.children('.panel-default').length;
            var re = new RegExp(prototypeName, 'g');
            var newForm = prototype.replace(re, index);
            collectionHolder.append(newForm);
            helpers.collapse($(newForm).find('.panel-collapse'));
        }

    },

    (function () {
        survey.init();
        surveyItem.init();
        sortable.init();
        draggable.init();
        toolbox.init();
        choice.init();
    }());
};
//        panel,
//        question,
//        resultRange,
//        condition,
//        helpers
//    ;
//
//   $.extend(params, {//{{{
//        surveyForm: 'form[name=survey]',
//        questionCollectionHolder: '#survey_questions',
//        addQuestionLink: 'a#add-question',
//        addResultRangeLink: 'a#add-resultrange',
//        resultRangeCollectionHolder: '#survey_resultRanges'
//    }, surveyParams);//}}}
//
//    panel = {
//        init: function () {
//            panel.bindRemove();
//            panel.initSortable();
//        },
//        bindRemove: function () {
//            $('body').delegate('a.remove-panel', 'click', function (e) {
//                e.preventDefault();
//                $(this).closest('.panel').remove();
//            });
//        },
//        initSortable: function() {
//            $('.sortable').sortable({
//                axis: 'y',
//                cancel: 'a.add-answer',
//                cursor: 'move',
//                handle: 'a.move-panel',
//                items: 'div.panel',
//                update: function(event) {
//                    for (var i = 0; i < event.target.children.length; i++ ) {
//                        var child = event.target.children[i];
//                        $(child).find('input.sortorder').val(i);
//                    }
//
//                    var form = $(this).closest('form');
//                    var url = $('div#survey').data('saveurl');
//
//                    $.ajax({
//                        data: form.serialize(),
//                        url: url,
//                        method: 'post',
//                        success: function (response) {
//                            console.log(response);
//                        }
//                    });
//                }
//            });
//        }
//    };
//
//    question = {//{{{
//        init: function () {
//            question.bindAdd();
//            question.addChoice();
//            question.bindAddChildQuestion();
//            question.bindTitleFields();
//            question.bindTypeSelect();
//        },
//        bindAdd: function () {
//            var newLink = $(params.addQuestionLink);
//            var questionCollectionHolder = $(params.questionCollectionHolder);
//
//            newLink.click(function (e) {
//                e.preventDefault();
//                helpers.addFormFromPrototype(questionCollectionHolder);
//            });
//        },
//        bindAddChildQuestion: function () {
//            $('body').delegate('.add-child-question', 'click', function(e) {
//                e.preventDefault();
//                var parentCollectionHolder = $(this).parents('.question-answer-prototype');
//                var panel = $(this).parents('.panel').first();
//                var index = parentCollectionHolder.children('.panel').index(panel);
//                var collectionHolderId = parentCollectionHolder.attr('id') + '_' + index + '_childQuestions';
//                var collectionHolder = $('#' + collectionHolderId);
//
//                collectionHolder.closest('.panel-collapse').addClass('in');
//                helpers.addFormFromPrototype(collectionHolder, '__question__');
//            });
//        },
//        bindTitleFields: function () {
//            $('body').delegate('.title-field', 'keyup', function() {
//                var panelTitle = $(this).closest('.panel').children('.panel-heading').find('h4 a');
//                panelTitle.text($(this).val());
//            });
//        },
//
//        bindTypeSelect: function () {
//            $('body').delegate('.question-type-select', 'change', function () {
//                var form = $(this).closest('form');
//                var url = form.attr('action');
//                var containerId = $(this).attr('id').substring(0, $(this).attr('id').length - 5);
//
//                $.ajax({
//                    data: form.serialize(),
//                    url: url,
//                    method: 'post',
//                    success: function (response) {
//                        $('#' + containerId).html($(response).find('#' + containerId).html());
//                    }
//                });
//            });
//        },
//        addChoice: function () {
//            $('body').delegate('a.add-answer', 'click',  function(e) {
//                e.preventDefault();
//                var collectionHolder = $(this).closest('.question-answer-prototype');
//                var link = collectionHolder.find('a.add-answer');
//
//                helpers.addFormFromPrototype(collectionHolder, '__choice__');
//                $(collectionHolder).find('.add-child-question').css('display', 'inline-block');
//                link.detach();
//                collectionHolder.append(link);
//            });
//        }
//
//    };//}}}
//
//    resultRange = {
//        init: function () {
//            resultRange.bindAdd();
//        },
//        bindAdd: function () {
//            var newLink = $(params.addResultRangeLink);
//            var collectionHolder = $(params.resultRangeCollectionHolder);
//
//            newLink.click(function (e) {
//                e.preventDefault();
//                helpers.addFormFromPrototype(collectionHolder, '__resultRange__');
//            });
//        },
//    };
//
//    condition = {
//        init: function () {
//            condition.bindAdd();
//            condition.bindQuestionSelect();
//        },
//        bindAdd: function () {
//            $('body').delegate('.condition-add', 'click', function(e) {
//                e.preventDefault();
//                var collectionHolder = $(this).parent('div');
//                //helpers.addFormFromPrototype(collectionHolder, '__condition__');
//                condition.addForm(collectionHolder);
//            });
//        },
//        bindQuestionSelect: function () {
//            $('body').delegate('.condition-question', 'change', function () {
//                var form = $(this).closest('form');
//                var url = form.attr('action');
//                var containerId = $(this).attr('id').substring(0, $(this).attr('id').length - 11);
//
//                $.ajax({
//                    data: form.serialize(),
//                    url: url,
//                    method: 'post',
//                    success: function (response) {
//                        $('#' + containerId).html($(response).find('#' + containerId).html());
//                    }
//                });
//            });
//        },
//        addForm: function (collectionHolder) {
//            var index = collectionHolder.data('index');
//
//            if (typeof index === 'undefined') {
//                //index = collectionHolder.children('.panel').length;
//                index = 0;
//                collectionHolder.prop('data-index', index);
//            }
//
//            var re = new RegExp('__condition__', 'g');
//            var prototype = collectionHolder.data('prototype');
//            var newForm = prototype.replace(re, index);
//
//            collectionHolder.append(newForm);
//            collectionHolder.data('index', index + 1);
//
//        }
//    };
//
//    helpers = {//{{{
//        addFormFromPrototype: function (collectionHolder, prototypeName = '__question__') {
//            var prototype = collectionHolder.data('prototype');
//            var index = collectionHolder.data('index');
//            var re = new RegExp(prototypeName, 'g');
//            var newFormDiv = $($('#default-panel').html());
//
//            if (typeof index === 'undefined') {
//                index = collectionHolder.children('.panel').length;
//                collectionHolder.prop('data-index', index);
//            }
//
//            var id = collectionHolder.attr('id') + '_' + index + '_panel';
//            var newForm = prototype.replace(re, index);
//
//            $(newFormDiv).attr('id', 'panel-' + id);
//            $(newFormDiv).find('a.collapsed').attr('href', '#' + id);
//            $(newFormDiv).find('a.collapsed').attr('aria-controls', id);
//            $(newFormDiv).find('div.panel-collapse').attr('id', id);
//
//            if (-1 !== prototypeName.indexOf('__choice__')) {
//                $(newFormDiv).find('.choice-toolbox').css('display', 'inline-block');
//            }
//
//            $(newFormDiv).find('.panel-body').append(newForm);
//            collectionHolder.append(newFormDiv);
//            collectionHolder.data('index', index + 1);
//        }
//    };//}}}
//
//    (function () {
//        panel.init();
//        question.init();
//        resultRange.init();
//        condition.init();
//    }());
//};

$(document).ready(function() {
    new projektmotor.Survey();
});
