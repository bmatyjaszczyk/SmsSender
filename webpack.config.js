var Encore = require('@symfony/webpack-encore');
var CopyWebpackPlugin = require('copy-webpack-plugin');

Encore
// directory where compiled assets will be stored
    //.configureRuntimeEnvironment('production')
    .configureRuntimeEnvironment('development')
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/js/app.js')
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning()


    .enableSassLoader()
    // .addPlugin(new CopyWebpackPlugin([
    //     { from: './assets/images', to: 'images' },
    // ]))
;

module.exports = Encore.getWebpackConfig();
