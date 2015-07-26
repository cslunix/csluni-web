(function(){
	angular
	.module('csluniD', [])
	.directive('navBar', function(){
		return {
			restrict: 'E',
			templateUrl: 'views/navBar.html',
			controller: 'navBarCtrl'
		};
	});
})();