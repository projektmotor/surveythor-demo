var webpack = require('webpack');
var node_dir = __dirname + '/node_modules';
var assets_dir = __dirname + '/app/Resources/PMSurveythorBundle/assets';

module.exports = {
    entry   : {
        survey: assets_dir + '/js/pages/survey.js',
    },
    plugins: [
        new webpack.ProvidePlugin({
            $: "jquery",
            jquery: "jQuery",
            "window.jQuery": "jquery"
        })
    ],
    resolve: {
        alias: {
            'jquery': node_dir + '/jquery/'
        }
    },
    output: {
        filename: "[name].bundle.js",
        path : __dirname + 'web/js/',
        publicPath : '/js/',
    },
};
