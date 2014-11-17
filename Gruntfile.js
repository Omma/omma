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
              src: ['assets/js/ctrl/*.js', 'assets/js/model/*.js'],
			      dest: 'assets/js/view_controller.js'
            }
	    },
			  
        /*'ftp-deploy': {
        	  build: {
        	    auth: {
        	      host: 'sv11.net-housting.de',
        	      port: 21,
        	      authKey: 'key1'
        	    },
        	    src: 'path/to/source/folder',
        	    dest: '/path/to/destination/folder',
        	    exclusions: ['path/to/source/folder.DS_Store', 'path/to/source/folderThumbs.db', 'path/to/dist/tmp']
        	  }
        	},*/
	    /*uglify: {
	      my_target: {
	        files: {
	          'assets/js/script.min.js': ['assets/js/script.js']
	        }
	      }
	    },
	    cssmin: {
	      combine: {
	        files: {
	          'assets/css/style.min.css': ['assets/css/style.css']
	        }
	      }
	    },*/
        less : {
            dev: {
                options: {
                    paths: ["css"]
                },
                files: {
                    "assets/css/style.css": "assets/less/*.less"
                }
            }
        },
        watch : {
            // Watch less files for linting
            less : {
                files : [
                    "assets/less/*.less"
                ],
                tasks: [
                    'less:dev'
                ]
            },
            concat : {
                files : [
                    [
                    	'assets/js/ctrl/*.js', 
                    	'assets/js/model/*.js', 
                    	'assets/js/script.js'
                    ]
                ],
                tasks : [
                    'concat'
                ]
            },
            /*uglify : {
                files : [
                    'assets/js/script.js'
                ],
                tasks : [
                    'uglify'
                ]
            },
            cssmin : {
                files : [
                    'assets/css/style.css'
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
                    '**/*.html' 
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
