const fgLoadCSS = require('fg-loadcss');

import '../css/frontend.scss';

$.ajax(window.location.origin + '/build/manifest.json').done(function (data) {
    fgLoadCSS.loadCSS(data['build/frontend.css']);
});
