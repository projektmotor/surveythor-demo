require('bootstrap-sass/assets/javascripts/bootstrap/affix.js');

module.exports = {
    lockToolboxOnPage: function (toolboxSelector) {
        $(toolboxSelector).affix({});
    }
};
