(function(){

	'use strict';

	angular
	.module('csluniC', ['ngRoute','ui.bootstrap'])
    .controller('navBarCtrl', ['$scope', '$location',  function ($scope, $location) {
      $scope.navbarCollapsed = true;
      $scope.tab = 0;

      $scope.setTab = function (tabId) {
          $scope.tab = tabId;
          switch(tabId){
            case 1: document.title = 'Home'; break;
            case 2: document.title = 'Cursos'; break;
            case 3: document.title = 'Eventos'; break;
            case 4: document.title = 'Videos'; break;
            case 5: document.title = 'Contacto'; break;
            case 6: document.title = 'About'; break;
          }
      };

      $scope.isSet = function (tabId) {
          return $scope.tab === tabId;
      };

      if( $location.path() == "/" ) $scope.setTab(1);
      if( $location.path().indexOf("/cursos")   > -1 ) $scope.setTab(2);
      if( $location.path().indexOf("/events")   > -1 ) $scope.setTab(3);
      if( $location.path().indexOf("/videos")   > -1 ) $scope.setTab(4);
      if( $location.path().indexOf("/contacto") > -1 ) $scope.setTab(5);
      if( $location.path().indexOf("/about")    > -1 ) $scope.setTab(6);

    }])
  	.controller('HomeCtrl', ['$scope', 	function ($scope) {
  		console.log('HomeCtrl');
  	}])
    .controller('EventsCtrl', ['$scope',   function ($scope) {
      console.log('EventsCtrl');
    }])
    .controller('CursosCtrl', ['$scope',  function ($scope) {
      console.log('CursosCtrl');
    }])
    .controller('VideosCtrl', ['$scope',  function ($scope) {
      console.log('VideosCtrl');
    }])
    .controller('ContactoCtrl', ['$scope',  function ($scope) {
      console.log('ContactoCtrl');
    }])
    .controller('RegistroCtrl', ['$scope', '$http', function ($scope, $http) {
      $scope.registrar = function(user){
        console.log(user);
        var req = {method: 'POST', url: 'api/registrar.php', data: user};
        $http(req).success(function(data, status, headers, config) {
          if( data.success ){
            $scope.confirmacion = data.success;
          }
          window.r = data;
        }).error(function(data, status, headers, config) { });
      };
    }])
    .controller('MatriculaCtrl', ['$scope', '$upload', function ($scope, $upload) {
      console.log('RegistroCtrl');
      $scope.upload = function(files) {
        $scope.formUpload = false;
        if (files != null) {
          for (var i = 0; i < files.length; i++) {
            (function(file) {
              file.upload = $upload.upload({
                url: 'api/upload.php',
                method: 'POST',
                data: {"user": $scope.user},
                file: file
              });

              file.upload.then(function(response){
                file.isSuccess = true;
                $scope.my.files.push(response.data)
              }, function(response) {
                if (response.status > 0) {  }
              });

              file.upload.progress(function(evt) {
                file.progress = Math.min(100, parseInt(100.0 * evt.loaded / evt.total));
              });
            })(files[i]);
          }
        }
      };

    }])


    .controller('mapaCtrl', ['$scope',   function ($scope) {
      console.log('mapaCtrl');
    }])

  	.controller('AboutCtrl', ['$scope', 	function ($scope) {
  		console.log('AboutCtrl');
  	}])
    .controller('manualesCtrl', ['$scope',   function ($scope) {
      console.log('manualesCtrl');
    }])
    .controller('hpdCtrl', ['$scope',   function ($scope) {
      console.log('hpdCtrl');
    }])
    .controller('autocapaCtrl', ['$scope',   function ($scope) {
      console.log('autocapaCtrl');
    }])
    .controller('capaCtrl', ['$scope',   function ($scope) {
      console.log('capaCtrl');
    }])
    .controller('sesionesCtrl', ['$scope',   function ($scope) {
      console.log('sesionesCtrl');
    }])
    .controller('proyectosCtrl', ['$scope',   function ($scope) {
      console.log('proyectosCtrl');
    }])
   
    .controller('herramientasCtrl', ['$scope',   function ($scope) {
      console.log('herramientasCtrl');
    }]);
})();