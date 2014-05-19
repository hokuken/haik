/**
 *   Haik
 *   -------------------------------------------
 *   assets/js/haik.js
 *   
 *   Copyright (c) 2014 hokuken
 *   http://hokuken.com/
 *   
 *   created  : 14/05/16
 *   modified : 
 */
$("body").attr("ng-controller", "AdminCtrl");
$("body").append('<nav haik-admin-nav-include class="navbar navbar-fixed-top haik-navbar"></nav>');
$("body").append('<div haik-admin-slide-include id="haik_admin_slide"></div>');

angular.element(document).ready(function() {
  angular.bootstrap(document, ['haikApp']);
});

$(function(){
});
