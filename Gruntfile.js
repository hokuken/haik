module.exports = function(grunt) {

  require('load-grunt-tasks')(grunt);
  var path = require('path');

  // for compile less files
  var lessConfig = {
      development: {
        options: {
          compress: false
        },
        files: [
          {
            expand: true,
            cwd: './haik-contents/themes/',
            src: [
              './*/less/theme.less'
            ],
            dest: './',
            rename: function(dest, src){
              return path.normalize(this.cwd + src.replace(/less/g, 'css'));
            }
          }
        ]
      }
  };

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    less: lessConfig,
  	watch: {
      less: {
        files: [
          './haik-contents/themes/*/less/*.less'
        ],
        tasks: ['less'],
        options: {
          liveoverload: true
        }
      },
    },
    exec: {
      server: {
        cmd: "php artisan serv"
      }
    }
  });

  grunt.registerTask('default', ['less', 'watch']);
  grunt.registerTask('publish', ['less']);
  grunt.registerTask('server', ['exec:server']);
}
