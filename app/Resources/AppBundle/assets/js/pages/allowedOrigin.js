require('bootstrap-sass');

const
    toggleBooleanValue = require('../toggleBooleanValue')
;

$(document).ready(function () {
    toggleBooleanValue.bindToggle();
});
