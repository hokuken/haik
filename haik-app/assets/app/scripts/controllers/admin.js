angular.module('haikApp', [])
  .controller('AdminCtrl', ['$scope', '$location', '$window', function($scope, $location, $window) {

    var $body = angular.element("body");
    var $userNavbar = angular.element(".navbar").not(".haik-navbar");
    var $haikNavbar = angular.element(".haik-navbar:visible");

    var navbarTop = $haikNavbar.height() + parseInt($body.css("padding-top").replace(/px/, ''), 10);

    $body.css({paddingTop: navbarTop});
    if ($userNavbar.css("position") === 'fixed') {
      $userNavbar.css({top: $haikNavbar});
    }

    var pathArr = $location.path().split('/');
    var page = pathArr.pop();

    $scope.edit = function() {
      $window.location.href = '/cmd/edit/' + page;
    }

    angular.extend($scope, {
      "adminSidr": function(open) {
        if (open) {

          angular.element('[data-admin-menu]').sidr({
            name: 'haik_admin_slide',
            side: 'right'
          });

        } else {
          $.sidr('close', 'haik_admin_slide', function(){
              $body.css({paddingTop: navbarTop});
              if ($userNavbar.css("position") === 'fixed') {
                $userNavbar.css({top: $haikNavbar});
              }
          });
        }
      }
    });

  }])
  .config(["$locationProvider", function($locationProvider) {
    $locationProvider.html5Mode(true).hashPrefix('!');
  }])
  .directive('haikAdminNavInclude', function() {
    return {
      templateUrl: 'assets/views/admin_nav.html'
    };
  })
  .directive('haikAdminSlideInclude', function() {
    return {
      templateUrl: 'assets/views/admin_slide.html'
    };
  });

