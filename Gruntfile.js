module.exports = function(grunt) {

    require('load-grunt-tasks')(grunt);

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        exec: {
            server: {
                cmd: "php artisan serv"
            }
        }
    });

    grunt.registerTask('server', ['exec:server'])
}
