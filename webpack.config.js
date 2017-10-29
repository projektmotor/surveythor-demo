let assets_dir = './app/Resources/AppBundle/assets';

let Encore = require('@symfony/webpack-encore');

Encore
// directory where all compiled assets will be stored
    .setOutputPath('web/build/')

    // what's the public path to this directory (relative to your project's document root dir)
    .setPublicPath(Encore.isProduction() ? '/build' : 'http://surveythor-demo:8080/build/')

    // .setOutputPath()

    // empty the outputPath dir before each build
    .cleanupOutputBeforeBuild()

    // will output as web/build/survey.js
    .addEntry('survey', assets_dir + '/js/pages/survey.js')
    .addEntry('result', assets_dir + '/js/pages/result.js')
    .addEntry('frontend', assets_dir + '/js/pages/frontend.js')

    // will output as web/build/global.css
    .addStyleEntry('global', assets_dir + '/css/global.scss')

    // allow sass/scss files to be processed
    .enableSassLoader(function () {
    }, {
        resolveUrlLoader: false
    })

    // allow legacy applications to use $/jQuery as a global variable
    .autoProvidejQuery()

    .enableSourceMaps(!Encore.isProduction())

    .setManifestKeyPrefix('build/')

    // create hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())
;

// export the final configuration
let config = Encore.getWebpackConfig();

if (config.devServer) {
    config.devServer.host = '0.0.0.0';
    config.devServer.port = '8080';
    config.devServer.public = "surveythor-demo:8080";
}

module.exports = config;
