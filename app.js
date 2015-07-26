(function(){

  var app = angular.module('csluni', ['ngRoute', 'ngAnimate', 'angular-loading-bar', 'ui.bootstrap','csluniC', 'csluniD']);

  app.config(['$routeProvider', 'cfpLoadingBarProvider', function($routeProvider, cfpLoadingBarProvider){
    cfpLoadingBarProvider.includeSpinner = false;
    cfpLoadingBarProvider.latencyThreshold = 100;
    //cfpLoadingBarProvider.includeBar = false;
    $routeProvider.
      when('/', {
        templateUrl: 'views/home.html',
        controller: 'HomeCtrl'
      }).
      when('/cursos', {
        templateUrl: 'views/cursos.html',
        controller: 'CursosCtrl'
      }).
      when('/cursos/linux', {
        templateUrl: 'views/cursos/linux.html',
        controller: 'CursosCtrl'
      }).
      when('/cursos/php', {
        templateUrl: 'views/cursos/php.html',
        controller: 'CursosCtrl'
      }).
      when('/cursos/hacking', {
        templateUrl: 'views/cursos/hacking.html',
        controller: 'CursosCtrl'
      }).
      when('/cursos/arduino', {
        templateUrl: 'views/cursos/arduino.html',
        controller: 'CursosCtrl'
      }).
      when('/cursos/raspberry', {
        templateUrl: 'views/cursos/raspberry.html',
        controller: 'CursosCtrl'
      }).
      when('/events', {
        templateUrl: 'views/events.html',
        controller: 'EventsCtrl'
      }).
      when('/registro', {
        templateUrl: 'views/registro.html',
        controller: 'RegistroCtrl'
      }).
      when('/videos', {
        templateUrl: 'views/videos.html',
        controller: 'VideosCtrl'
      }).
      when('/contacto', {
        templateUrl: 'views/contacto.html',
        controller: 'ContactoCtrl'
      }).
      when('/about', {
        templateUrl: 'views/about.html',
        controller: 'AboutCtrl'
      }).
       when('/mapa', {
        templateUrl: 'views/mapa.html',
        controller: 'mapaCtrl'
      }).
       when('/manuales', {
        templateUrl: 'views/manuales.html',
        controller: 'manualesCtrl'
      }).
       when('/herramientas', {
        templateUrl: 'views/herramientas.html',
        controller: 'herramientasCtrl'
      }).
       when('/hpd', {
        templateUrl: 'views/hpd.html',
        controller: 'hpdCtrl'
      }).
       when('/autocapa', {
        templateUrl: 'views/autocapa.html',
        controller: 'autocapaCtrl'
      }).
       when('/capa', {
        templateUrl: 'views/capa.html',
        controller: 'capaCtrl'
      }).
       when('/sesiones', {
        templateUrl: 'views/sesiones.html',
        controller: 'sesionesCtrl'
      }).
       when('/proyectos', {
        templateUrl: 'views/proyectos.html',
        controller: 'proyectosCtrl'
      }).
      otherwise({
        redirectTo: '/'
      });
  }]);

})();