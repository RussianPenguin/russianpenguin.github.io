module.exports = function (grunt) {

    // 1. All configuration goes here 
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        concat: {
            js: {
                src: ['src/**/*.js'],
                dest: 'gapi.js'
            }
        },
        uglify: {
            js: {
                src: 'gapi.js',
                dest: 'gapi.min.js'
            }
        },
        watch: {
            js: {
                files: ['src/**/*.js'],
                tasks: ['concat:js', 'uglify:js']
            }
        }
    });

    // 2. Where we tell Grunt we plan to use this plug-in.
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');

    // 3. Where we tell Grunt what to do when we type "grunt" into the terminal.
    grunt.registerTask('default', ['concat:js', 'uglify:js']);

};
