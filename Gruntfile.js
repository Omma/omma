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
            'web/assets/components/jquery/dist/jquery.min.js',
            'web/assets/components/lodash/dist/lodash.min.js',
            'web/assets/components/bootstrap/dist/js/bootstrap.min.js',
            'web/assets/components/angular/angular.min.js',
            'web/assets/components/restangular/dist/restangular.min.js',
            'web/assets/components/angular-loading-bar/build/loading-bar.min.js',
            'web/assets/components/angular-ui-tree/dist/angular-ui-tree.min.js',
            'web/assets/components/moment/min/moment.min.js',
            'web/assets/components/moment/locale/de.js',
            'web/assets/components/glDatePicker/glDatePicker.js',
            'web/assets/components/angular-bootstrap/ui-bootstrap.min.js',
            'web/assets/components/angular-bootstrap/ui-bootstrap-tpls.min.js',
            'web/assets/components/angular-bootstrap-calendar/dist/js/angular-bootstrap-calendar.js',
            'web/assets/components/angular-bootstrap-calendar/dist/js/angular-bootstrap-calendar-tpls.js',
            'web/assets/components/typehead.js/dist/typeahead.bundle.min.js',

            'web/assets/components/textAngular/dist/textAngular-sanitize.min.js',
            'web/assets/components/textAngular/dist/textAngular.min.js',

            'web/assets/components/bootstrap-daterangepicker/daterangepicker.js',
            'web/assets/components/angular-daterangepicker/js/angular-daterangepicker.min.js',

            'web/assets/components/angular-xeditable/dist/js/xeditable.min.js',
            'web/assets/components/angular-ui-select/dist/select.min.js'
        ],
        js: [
            'web/assets/js/MainModule.js',
            'web/assets/js/**/*.js'
        ],
        css: [
            'web/assets/components/angular-bootstrap-calendar/dist/css/angular-bootstrap-calendar.css',
            'web/assets/components/angular-ui-tree/dist/angular-ui-tree.min.css',
            'web/assets/components/angular-xeditable/dist/css/xeditable.css',

            'web/assets/components/bootstrap-daterangepicker/daterangepicker-bs3.css',
            'web/assets/components/select2/select2.css',
            'web/assets/components/select2-bootstrap-css/select2-bootstrap.css',
            'web/assets/components/angular-ui-select/dist/select.min.css'
        ],
        less: [
            'web/assets/less/basic.less'
        ],
        templates: [
            'assets/templates/*.html'
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
        concat: {
            build: {
                src: files.jsLibs.concat([
                    'web/assets/build/main.min.js',
                    'web/assets/build/templates.js'
                ]),
                dest: 'web/assets/build/build.js'
            },
            libs: {
                src: files.jsLibs,
                dest: 'web/assets/build/libs.js',
                options: {
                    sourceMap: true
                }
            },
            main: {
                src: files.js.concat(['web/assets/build/templates.js']),
                dest: 'web/assets/build/main.js',
                options: {
                    sourceMap: true
                }
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
                    'web/assets/build/less.css': files.less
                }
            },
            dev: {
                options: {
                    paths: ['css'],
                    sourceMap: true
                },
                files: {
                    'web/assets/build/less.css': files.less
                }
            }
        },
        cssmin: {
            prod: {
                files: {
                    'web/assets/build/style.css': files.css.concat(['web/assets/build/less.css'])
                }
            },
            dev: {
                options: {
                    rebase: true,
                    target: 'web/assets/build',
                    root: 'web'
                },
                files: {
                    'web/assets/build/style.css': files.css.concat(['web/assets/build/less.css'])
                }
            }
        },
        ngtemplates: {
            app: {
                src: files.templates,
                dest: 'web/assets/build/templates.js',
                options: {
                    module: 'ommaApp'
                },
                cwd: 'web'
            }
        },
        watch: {
            // Watch less files for linting
            less: {
                files: [
                    'web/assets/less/*.less',
                ],
                tasks: [
                    'less:dev',
                    'cssmin:dev'
                ]
            },
            js: {
                files: files.js,
                tasks: [
                    'newer:jshint',
                    'concat:main'
                ]
            },
            ngtemplates: {
                files: files.templates,
                options: {
                    cwd: 'web'
                },
                tasks: [
                    'ngtemplates:app'
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
        'ngtemplates',
        'concat:libs',
        'concat:main',
        'less:dev',
        'cssmin:dev',
        'serve'
    ]);

    grunt.registerTask('build', [
        'newer:jshint',
        'ngtemplates',
        'less:prod',
        'cssmin:prod',
        'uglify:prod',
        'concat:build'
    ]);
};
