/**
 * Gruntfile.js
 *
 * Copyright (c) 2012 quickcue
 */

module.exports = function (grunt) {
    // Load dev dependencies
    require('load-grunt-tasks')(grunt);

    // Time how long tasks take for build time optimizations
    require('time-grunt')(grunt);

    // Configure the app path

    var files = {
        jsLibs: [
            './web/assets/components/jquery/jquery.min.js',
            './web/assets/components/lodash/dist/lodash.min.js',
            './web/assets/components/bootstrap/dist/js/bootstrap.min.js',
            './web/assets/components/angular/angular.min.js',
            './web/assets/components/restangular/dist/restangular.min.js',
            './web/assets/components/angular-loading-bar/build/loading-bar.min.js',
            './web/assets/components/angular-ui-tree/dist/angular-ui-tree.min.js',
            './web/assets/components/moment/min/moment.min.js',
            './web/assets/components/glDatePicker/glDatePicker.js',
            './web/assets/components/bootstrap-calendar/js/calendar.min.js',
            './web/assets/components/bootstrap-calendar/js/language/de-DE.js',
            './web/assets/components/typehead.js/dist/typeahead.bundle.min.js',
            './web/assets/components/textAngular/dist/textAngular-sanitize.min.js',
            './web/assets/components/textAngular/dist/textAngular.min.js',
            './web/assets/components/angular-daterangepicker/js/angular-daterangepicker.js',
            './web/assets/components/angular-bootstrap/ui-bootstrap.min.js',
            './web/assets/components/angular-bootstrap/ui-bootstrap-tpls.min.js'
        ],
        js: [
            './web/assets/js/MainModule.js',
            './web/assets/js/**/*.js'
        ]
    };

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        // The actual grunt server settings
        connect: {
            options: {
                port: 9000,
                livereload: 35729,
                // Change this to '0.0.0.0' to access the server from outside
                hostname: '127.0.0.1'
            },
            livereload: {
                options: {
                    open: true,
                    base: [
                        '.'
                    ]
                }
            }
        },
        jshint: {
            options : {
                jshintrc : '.jshintrc'
            },
            all: files.js.concat(['Gruntfile.js'])
        },
        /*jshint camelcase: false */
        concat_sourcemap: {
            libs: {
                files: {
                    'web/assets/build/libs.js': files.jsLibs
                }
            },
            main: {
                files: {
                    'web/assets/build/main.js': files.js
                }
            }
        },
        concat: {
            build: {
                src: files.jsLibs.concat([
                    'web/assets/build/main.min.js'
                ]),
                dest: 'web/assets/build/build.js'
            },
            dist: {
                src: ['web/assets/js/ctrl/*.js', 'web/assets/js/model/*.js'],
                dest: 'web/assets/js/view_controller.js'
            }
        },
        uglify: {
            options: {},
            prod: {
                options: {
                    mangle: false // @TODO: https://stackoverflow.com/questions/17238759/angular-module-minification-bug
                },
                files: {
                    'web/assets/build/main.min.js': files.js
                }
            }
        },
        less: {
            prod: {
                options: {
                    paths: ['css']
                },
                files: {
                    'web/assets/build/style.css': 'web/assets/less/basic.less'
                }
            },
            dev: {
                options: {
                    paths: ['css'],
                    sourceMap: true
                },
                files: {
                    'web/assets/build/style.css': 'web/assets/less/basic.less'
                }
            }
        },
        watch: {
            // Watch less files for linting
            less: {
                files: [
                    'web/assets/less/*.less'
                ],
                tasks: [
                    'less:dev'
                ]
            },
            js: {
                files: files.js,
                tasks: [
                    'newer:jshint',
                    'concat_sourcemap:main'
                ]
            },

            // Live reload
            reload: {
                options: {
                    livereload: '<%= connect.options.livereload %>'
                },
                files: [
                    '<%= watch.less.files %>',
                    '<%= watch.js.files %>',
                    /*'<%= watch.uglify.files %>',*/
                    /*'<%= watch.cssmin.files %>',*/
                    'web/**/*.html'
                ]
            }
        }
    });

    grunt.registerTask('serve', function () {
        grunt.task.run([
            'connect:livereload',
            'watch'
        ]);
    });

    grunt.registerTask('default', [
        'newer:jshint',
        'concat_sourcemap:libs',
        'concat_sourcemap:main',
        'less:dev',
        'serve'
    ]);

    grunt.registerTask('build', [
        'newer:jshint',
        'less:prod',
        'uglify:prod',
        'concat:build'
    ]);
};
