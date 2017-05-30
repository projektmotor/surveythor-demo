var webpack = require('webpack');
var node_dir = __dirname + '/node_modules';

module.exports = {
    entry   : "./web/js/surveythor.js",
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
        filename: "bundle.js",
        path : __dirname + 'web/js/',
        publicPath : '/js/',
    },
};
