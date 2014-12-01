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

    grunt.initConfig({
        pkg : grunt.file.readJSON('package.json'),
        // The actual grunt server settings
        connect : {
            options : {
                port : 9000,
                livereload : 35729,
                // Change this to '0.0.0.0' to access the server from outside
                hostname : '127.0.0.1'
            },
            livereload : {
                options : {
                    open : true,
                    base : [
                        '.'
                    ]
                }
            }
        },
        concat: {
        	dist: {
              src: ['web/assets/js/ctrl/*.js', 'web/assets/js/model/*.js'],
			      dest: 'web/assets/js/view_controller.js'
            }
	    },


	    /*uglify: {
	      my_target: {
	        files: {
	          'web/assets/js/script.min.js': ['web/assets/js/script.js']
	        }
	      }
	    },
	    cssmin: {
	      combine: {
	        files: {
	          'web/assets/css/style.min.css': ['web/assets/css/style.css']
	        }
	      }
	    },*/
        less : {
            dev: {
                options: {
                    paths: ["css"]
                },
                files: {
                    "web/assets/css/style.css": "web/assets/less/basic.less"
                }
            }
        },
        watch : {
            // Watch less files for linting
            less : {
                files : [
                    "web/assets/less/*.less"
                ],
                tasks: [
                    'less:dev'
                ]
            },
            concat : {
                files : [
                    [
                    	'web/assets/js/ctrl/*.js',
                    	'web/assets/js/model/*.js',
                    	'web/assets/js/script.js'
                    ]
                ],
                tasks : [
                    'concat'
                ]
            },
            /*uglify : {
                files : [
                    'web/assets/js/script.js'
                ],
                tasks : [
                    'uglify'
                ]
            },
            cssmin : {
                files : [
                    'web/assets/css/style.css'
                ],
                tasks : [
                    'cssmin'
                ]
            },*/

            // Live reload
            reload : {
                options : {
                    livereload : '<%= connect.options.livereload %>'
                },
                files : [
                    '<%= watch.less.files %>',
                    '<%= watch.concat.files %>',
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
        'less:dev',
        'serve'
    ]);
};
