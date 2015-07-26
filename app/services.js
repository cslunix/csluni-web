(function(){

    'use strict';

    /* Services */

    /*
     http://docs.angularjs.org/api/ngResource.$resource

     Default ngResources are defined as

     'get':    {method:'GET'},
     'save':   {method:'POST'},
     'query':  {method:'GET', isArray:true},
     'remove': {method:'DELETE'},
     'delete': {method:'DELETE'}
                                /GO/ngdemo/web/eissonHack
     */

    var services = angular.module('Services', [
        'ngResource'
        ]);

    services.factory('MiActividadDetailFactory', function ($resource) {
        return $resource(
                        '/GO/modules/api1.0/index.php/mis-actividades/:id',
                         {},
                         {
                            'get': { method: 'GET', params: {id: '@id'} , isArray: false }
                         }
                );
    });

    services.factory('sendNotifyFactory', function ($resource) {
        return $resource(
                        'php/sendNotifyNOC.php',
                         {},
                         {
                            'send': { method: 'GET', params: {workID: '@workID', fecha: '@fecha'} , isArray: false }
                         }
                );
    });

    services.factory('nocDetailFactory', function ($resource) {
        return $resource(
                        '/GO/modules/api1.0/index.php/n/:workID/:fecha',
                         {},
                         {
                            'listar': { method: 'GET', params: {workID: '@workID', fecha: '@fecha'} , isArray: false }
                         }
                );
    });

    services.factory('killFactory', function ($resource) {
        return $resource(
                        'php/kill.php',
                         {},
                         {
                            'killing': { method: 'GET', params: {id: '@id'} , isArray: false }
                         }
                );
    });

    services.factory('BusquedaListByUserFactory', function ($resource) {
        return $resource(
                        'php/buscar/solicitanteList.php',
                         {},
                         {
                            'listar': { method: 'GET', params: {id: '@id'} , isArray: true }
                         }
                );
    });


     services.factory('BusquedaDetailFactory', function ($resource) {
         return $resource(
                         '/GO/modules/API/index.php/tp/:id',
                          {},
                          {
                             'listar': { method: 'GET', params: {id: '@id'} , isArray: false }
                          }
                 );
     });

    services.factory('personalFactory', function ($resource) {
        return $resource(
                        'php/add/personal.php',
                         {},
                         {
                            'add': { method: 'GET', params: {data: '@data', option: '@option'} , isArray: false },
                            'delete': { method: 'GET', params: {data: '@data', option: '@option'} , isArray: false }
                         }
                );
    });

    services.factory('NEFactory', function ($resource) {
        return $resource(
                        'php/add/ne.php',
                         {},
                         {
                            'add': { method: 'GET', params: {data: '@data', option: '@option'} , isArray: false },
                            'del': { method: 'GET', params: {data: '@data', option: '@option'} , isArray: false }
                         }
                );
    });

    services.factory('fechaFactory', function ($resource) {
        return $resource(
                        'php/fecha.php',
                         {},
                         {
                            'delete': { method: 'GET', params: {data: '@data', option: '@option'} , isArray: false },
                            'add': { method: 'GET', params: {data: '@data', option: '@option'} , isArray: false },
                            'update': { method: 'GET', params: {data: '@data', option: '@option'} , isArray: false }
                         }
                );
    });


    services.factory('rpnFactory', function ($resource) {
        return $resource(
                        'php/update/rpn.php',
                         {},
                         {
                            'update': { method: 'GET', params: {data: '@data', option: '@option'} , isArray: false }
                         }
                );
    });

    services.factory('goDetailFactory', function ($resource) {
        return $resource(
                        'php/goDetail.php',
                         {},
                         {
                            'listar': { method: 'GET', params: {workID: '@workID'} , isArray: false }
                         }
                );
    });


    services.factory('BusquedaByNocFactory', function ($resource) {
        return $resource(
                        'php/buscar/noc.php',
                         {},
                         {
                            'listar': { method: 'GET', params: {data: '@data'} , isArray: true }
                         }
                );
    });
                        //'/GO/modules/API/index.php/tp/fecha/:fecha',
    services.factory('BusquedaByFechaFactory', function ($resource) {
        return $resource(
                        '/GO/modules/API/index.php/busqueda/fecha/:fecha',
                         {},
                         {
                            'listar': { method: 'GET', params: {fecha: '@fecha'} , isArray: true }
                         }
                );
    });

    services.factory('BusquedaByNameFactory', function ($resource) {
        return $resource(
                        'php/buscar/byName.php',
                         {},
                         {
                            'find': { method: 'GET', params: {q: '@q'} , isArray: true }
                         }
                );
    });

    services.factory('BusquedaByAllUsersFactory', function ($resource) {
        return $resource(
                        'php/buscar/solicitante.php',
                         {},
                         {
                            'listar': { method: 'GET', params: {} , isArray: true }
                         }
                );
    });

    services.factory('ValidacionFactory', function ($resource) {
        return $resource(
                        '/GO/modules/api1.0/index.php/validacion/v/:token',
                         {},
                         {
                            'Listar': { method: 'GET', params: {token: '@token'} , isArray: false }
                         }
                );
    });

    services.factory('RegulacionFactory', function ($resource) {
        return $resource(
                        '/GO/modules/api1.0/index.php/validacion/r/:token',
                         {},
                         {
                            'Listar': { method: 'GET', params: {token: '@token'} , isArray: false }
                         }
                );
    });

    services.factory('CommentsFactory', function ($resource) {
        return $resource(
                        'php/saveComment.php',
                         {},
                         {
                            'saveComment': { method: 'GET', params: {data: '@data', actividad: '@actividad', session:'@session', id:'@id',workName:'@workName'} , isArray: false }
                         }
                );
    });

    services.factory('MisActividadesFactory', function ($resource) {
        return $resource(
                        'php/misActividades.php',
                         {},
                         {
                            'get': { method: 'GET', params: {} , isArray: true }
                         }
                );
    });

    services.factory('TicketsFactory', function ($resource) {
        return $resource(
                        'php/saveTickets.php',
                         {},
                         {
                            'save': { method: 'GET', params: {data: '@data'} , isArray: false },
                            'tipo': { method: 'GET', params: {data: '@data'} , isArray: false }
                         }
                );
    });

    services.factory('UpdateFactory', function ($resource) {
        return $resource(
                        'php/update/updatev1.php',
                         {},
                         {
                            'update': { method: 'GET', params: {data: '@data'} , isArray: false }
                         }
                );
    });

    services.factory('deleteFileFactory', function ($resource) {
        return $resource(
                        'php/files/delete.php',
                         {},
                         {
                            'delete': { method: 'GET', params: {data: '@data'} , isArray: false }
                         }
                );
    });

    services.factory('AproveRegFactory', function ($resource) {
        return $resource(
                        'php/aproveReg.php',
                         {},
                         {
                            'aprove': { method: 'GET', params: {user: '@user', data: '@data'} , isArray: false }
                         }
                );
    });

    services.factory('AproveFactory', function ($resource) {
        return $resource(
                        'php/aprove.php',
                         {},
                         {
                            'aprove': { method: 'GET', params: {user: '@user', data: '@data'} , isArray: false }
                         }
                );
    });

    services.factory('UpdateStatusGOFactory', function ($resource) {
        return $resource(
                        'php/update_status.php',
                         {},
                         {
                            'update': { method: 'GET', params: {user: '@user', data: '@data'} , isArray: false }
                         }
                );
    });

    services.factory('RejectFactory', function ($resource) {
        return $resource(
                        'php/reject.php',
                         {},
                         {
                            'reject': { method: 'GET', params: {user: '@user', data: '@data', actividad: '@actividad'} , isArray: false }
                         }
                );
    });

    services.factory('RechazarRegFactory', function ($resource) {
        return $resource(
                        'php/rechazarReg.php',
                         {},
                         {
                            'rechazar': { method: 'GET', params: {data: '@data', tipo: '@tipo'} , isArray: false }
                         }
                );
    });


    services.factory('validacionesFactory', function ($resource) {
        return $resource(
                        'php/validaciones.php',
                         {},
                         {
                            'getPendientes': { method: 'GET', params: {} , isArray: true },
                            'moreWorks': { method: 'POST', params: {data: '@data', tipo: '@tipo'} , isArray: true }
                         }
                );
    });

    services.factory('regulacionesFactory', function ($resource) {
        return $resource(
                        'php/regulaciones.php',
                         {},
                         {
                            'getPendientes': { method: 'GET', params: {} , isArray: true },
                            'moreWorks': { method: 'POST', params: {data: '@data', tipo: '@tipo'} , isArray: true }
                         }
                );
    });

    services.factory('gosFactory', function ($resource) {
        return $resource(
                        'php/gos.php',
                         {},
                         {
                            'listar': { method: 'GET', params: {} , isArray: true },
                            'moreWorks': { method: 'POST', params: {data: '@data', tipo: '@tipo'} , isArray: true }
                         }
                );
    });

    services.factory('resultadosF', function ($resource) {
        return $resource(
                        'php/myWorks/resultados.php',
                         {},
                         {
                            'set': { method: 'POST', params: {data: '@data', tipo: '@tipo'} , isArray: false }
                         }
                );
    });

    services.factory('userFactory', function ($resource) {
        return $resource(
                        '/GO/modules/api1.0/index.php/user',
                        {},
                        {
                            'getUser': { method: 'GET', params: {}, isArray: false }
                        }
                );
    });

    //services.factory('reunionFactory', function ($resource) {
    //    return $resource(
    //                    'php/reunion.php',
    //                    {},
    //                    {
    //                        'listar': { method: 'GET', params: {}, isArray: true }
    //                    }
    //            );
    //});

    services.factory('reunionState', ['$http', function ($http) {
        return $http.get('php/reunion.php');
    }]);
    //services.factory('reunionState', function ($http) {
    //    return $resource(
    //                    'php/reunion.php',
    //                    {},
    //                    {
    //                        'listar': { method: 'GET', params: {}, isArray: true }
    //                    }
    //            );
    //});

    services.factory('nocListState', ['$http', function ($http) {
        return $http.get('php/nocList.php');
    }]);

    //services.factory('nocListFactory', function ($resource) {
    //    return $resource(
    //                    'php/nocList.php',
    //                    {},
    //                    {
    //                        'listar': { method: 'GET', params: {}, isArray: true }
    //                    }
    //            );
    //});

    //services.factory("sharedData", function(){
    //  return {sharedObject: {data: null } }
    //});

    // services.factory('userService', ['$scope', function($scope) {
    //   $scope.userData = {yearSetCount: 0};
    //   $scope.user = function() {            return $scope.userData;      };
    //   $scope.setEmail = function(email) {            $scope.userData.email = email;      };
    //   $scope.getEmail = function() {            return $scope.userData.email;      };
    //   $scope.setSetCount = function(setCount) {            $scope.userData.yearSetCount = setCount;      };
    //   $scope.getSetCount = function() {            return $scope.userData.yearSetCount;      };
    // }]);

})();