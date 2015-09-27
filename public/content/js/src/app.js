var Swal = angular.module('Swal', []);
Swal.factory('swal', function($window) {
    return $window.swal;
});

var APP = angular.module('APP', ['ui.router', 'Swal', 'angucomplete-alt']);

APP.config(['$stateProvider', '$urlRouterProvider', '$locationProvider',
    function($stateProvider, $urlRouterProvider, $locationProvider) {

        $urlRouterProvider.otherwise("/main");

        // $locationProvider.html5Mode({
        //     enabled: true,
        //     requireBase: false
        // });

        $stateProvider
            .state('main', {
                url: "/main",
                templateUrl: "/content/js/src/controllers/MainCtrl/MainCtrl.html",
                controller: 'MainCtrl'
            })
        ;
    }
]);

