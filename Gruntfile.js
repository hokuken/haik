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
          },
          {
            src: [
              './haik-app/assets/stylesheets/haik.less'
            ],
            dest: './assets/css/haik.css'
          }
        ]
      }
  };

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    less: lessConfig,
    concat: {
      options: {
        separator: ';'
      },
      js_haik: {
        src: ['./haik-app/assets/app/**/*.js', './haik-app/assets/js/*.js'],
        dest: './assets/js/haik.js'
      }
    },
    copy: {
      ng: {
        files: [
          {
            expand: true,
            cwd: './haik-app/assets/app/views',
            src: ['./*.html'],
            dest: './assets/views'
          }
        ]
      }
      
    },
  	watch: {
      less: {
        files: [
          './haik-contents/themes/*/less/*.less',
          './haik-app/assets/stylesheets/*.less'
        ],
        tasks: ['less'],
        options: {
          liveoverload: true
        }
      },
      js_haik: {
        files: [
          './haik-app/assets/js/*.js',
          './haik-app/assets/app/**/*.js',
        ],
        tasks: ['concat:js_haik']
      },
      view: {
        files: [
          './haik-app/assets/app/views/*.html'
        ],
        tasks: ['copy:ng']
      }
    },
    exec: {
      server: {
        cmd: "php artisan serv"
      }
    }
  });

  grunt.registerTask('default', ['less', 'concat', 'copy', 'watch']);
  grunt.registerTask('publish', ['less', 'concat', 'copy']);
  grunt.registerTask('server', ['exec:server']);
}
