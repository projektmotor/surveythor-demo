require('bootstrap-sass');

const
    survey = require('../survey'),
    surveyItem = require('../surveyItem'),
    draggable = require('../draggable'),
    sortable = require('../sortable'),
    toolbox = require('../toolbox'),
    choice = require('../choice')
;

$(document).ready(function () {
    survey.bindDisableSurveyWhileAjaxLoading();
    survey.bindSaveSurveyAttributeOnEdit();
    surveyItem.init();
    sortable.init();
    draggable.bindDraggable('#new-items > div');
    toolbox.lockToolboxOnPage('#survey-tools');
    choice.init();
});
