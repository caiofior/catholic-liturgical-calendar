module.exports = function (grunt) {

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        uglify: {
            my_target: {
                files: {
                    'theme/technext/small-apps/js/login.min.js': ['theme/technext/small-apps/js/login.js'],
                    'theme/technext/small-apps/js/calendar.add.min.js': ['theme/technext/small-apps/js/calendar.add.js'],
                    'theme/technext/small-apps/js/calendar.list.min.js': ['theme/technext/small-apps/js/calendar.list.js'],
                    'theme/technext/small-apps/js/prey.add.min.js': ['theme/technext/small-apps/js/prey.add.js'],
                }
            }
        }
    });

    // Load the plugin that provides the "uglify" task.
    grunt.loadNpmTasks('grunt-contrib-uglify');

    // Default task(s).
    grunt.registerTask('default', ['uglify']);

};