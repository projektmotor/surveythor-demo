require('bootstrap-sass');
require('jquery-ui');
require('jquery-ui/ui/widgets/draggable/');
//require('jquery-ui/ui/widgets/droppable/');
require('jquery-ui/ui/widgets/sortable/');
require('bootstrap-sass/assets/javascripts/bootstrap/affix.js');


var projektmotor = projektmotor || {};

projektmotor.Survey = function () {
    "use strict";

    var
        surveyItem,
        draggable,
        sortable,
        toolbox
    ;

    surveyItem = {
        init: function () {
            $('body').delegate(
                '#survey-elements input, #survey-elements textarea',
                'blur',
                function() {
                    surveyItem.save(this);
                }
            );

            $('body').delegate('#survey-elements .panel-heading', 'click', function() {
                surveyItem.collapse(this);
            });
        },
        save: function (elem) {
            var form = $(elem).parents('form').first();
            var url = $(form).attr('action');
            $.ajax({
                url: url,
                method: 'post',
                data: form.serialize()
                //success: function (response) {
                //}
            });
        },
        collapse: function (item) {
            var panelbody = $(item).next();
            var isParent = $(item).parent().hasClass('parent');
            var formLoaded = $(panelbody).hasClass('loaded');

            if (isParent && !formLoaded) {
                var url = $(item).attr('data-itemform-url');
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        $(panelbody).html(response);
                        $(panelbody).addClass('loaded');
                        sortable.initSortable();
                    }
                });
            }

            if ($(panelbody).hasClass('in')) {
                $(panelbody).removeClass('in');
                $(panelbody).css('display', 'none');
            } else {
                $(panelbody).addClass('in');
                $(panelbody).css('display', 'block');
            }
        }
    },

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
                    $(event.target).parent().unbind('click');
                    if ($(ui.item).hasClass('new-item')) {
                        var url = ui.item.attr('data-url');
                        $.ajax({
                            url: url,
                            method: 'GET',
                            success: function (response) {
                                response = JSON.parse(response);
                                $(ui.item).before(response.html);

                                sortable.helpers.collapse(response.open);

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
                    $(event.target).parent().unbind('click');
                    if ($(ui.item).hasClass('new-item')) {
                        var draggableConnect = event.target;
                        var parentGroup = $(draggableConnect).parents('.panel-collapse').first().attr('id').substring(5);
                        var rootContainer = $(draggableConnect).parents('.parent-item').first();
                        var rootGroup = $(rootContainer).attr('id').substring(5);
                        var url = ui.item.attr('data-itemgroup-add-item-url');
                        var form = $(ui.item).closest('form[name=surveyitem]');
                        var sortOrder = $(ui.item).index();
                        $.ajax({
                            url: url + '?parent=' + parentGroup + '&sortorder=' + sortOrder + '&root=' + rootGroup,
                            method: 'POST',
                            data: form.serialize(),
                            success: function (response) {
                                response = JSON.parse(response);
                                $(rootContainer).html(response.html);
                                $(draggableConnect).remove();
                                sortable.helpers.collapse(response.open);
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
                        url: $(child).attr('data-sortorder-url') + '/' + i,
                        method: 'GET'
                    });
                }
            },
            collapse: function (items) {
                for (var i = items.length - 1; i >= 0; i--) {
                    $('#item-' + items[i]).addClass('in');
                }

            },
            updateSortOrders: function (item, sortableList) {
                if (!$(item).hasClass('new-item')) {
                    sortable.helpers.sort(sortableList);
                }

            }
        }
    };

    (function () {
        surveyItem.init();
        sortable.init();
        draggable.init();
        toolbox.init();
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
