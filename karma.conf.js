// Karma configuration
// Generated on Fri Sep 29 2017 10:58:07 GMT-0400 (EDT)
const argv = require('yargs').argv;
const Mix = require('laravel-mix').config;

module.exports = function(config) {
  config.set({

    // base path that will be used to resolve all patterns (eg. files, exclude)
    basePath: './',


    // frameworks to use
    // available frameworks: https://npmjs.org/browse/keyword/karma-adapter
    frameworks: ['mocha', 'sinon-chai'],


    // list of files / patterns to load in the browser
    files: [
        {
        pattern: './tests/Frontend/setup.js',
        watched: false,
        served: true,
        included: true,
      },
    ],


    // list of files to exclude
    exclude: [
    ],

    // preprocess matching files before serving them to the browser
    // available preprocessors: https://npmjs.org/browse/keyword/karma-preprocessor
    preprocessors: {
      './tests/Frontend/setup.js': ['webpack', 'sourcemap'],
    },

    plugins: [
      'karma-jsdom-launcher',
      'karma-mocha',
      'karma-sinon-chai',
      'karma-webpack',
      'karma-sourcemap-loader',
      'karma-spec-reporter'
    ],

    // test results reporter to use
    // possible values: 'dots', 'progress'
    // available reporters: https://npmjs.org/browse/keyword/karma-reporter
    reporters: ['spec'],

    // web server port
    port: 9876,


    // enable / disable colors in the output (reporters and logs)
    colors: true,


    // level of logging
    // possible values: config.LOG_DISABLE || config.LOG_ERROR || config.LOG_WARN || config.LOG_INFO || config.LOG_DEBUG
    logLevel: config.LOG_INFO,


    // enable / disable watching file and executing tests whenever any file changes
    autoWatch: argv.watch,


    // start these browsers
    // available browser launchers: https://npmjs.org/browse/keyword/karma-launcher
    browsers: ['jsdom'],

    webpack: {
      resolve: {
        alias: {
            moment$: 'moment/moment.js'
        },
      },
      module: {
        loaders: [
          {
            test: /\.js?$/,
            exclude: /(node_modules|bower_components)/,
            loader: 'babel-loader',
            include: [
              path.join(__dirname, 'tests/Frontend')
            ],
          },
          {
            test: /\.vue$/,
            loader: 'vue-loader',
          }
        ]
      }
    },
    webpackMiddleware: {
      noInfo: true,
    },


    // Continuous Integration mode
    // if true, Karma captures browsers, runs the tests and exits
    singleRun: !argv.watch,

    // Concurrency level
    // how many browser should be started simultaneous
    concurrency: Infinity
  })
}
