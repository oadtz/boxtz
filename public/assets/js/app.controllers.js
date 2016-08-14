app
// Signup Controllers
.controller('SignupController', ['$scope', '$rootScope', 'authService', function ($scope, $rootScope, authService) {

	$scope.init = function () {
		$rootScope.currentModule = 'signup';
		$scope.user = {};
	}

	$scope.performSignup = function () {
		$scope.signupForm.$waiting = true;

		authService.signup($scope.user,
						function (data) {
							$scope.signupForm.errors = null;
							//$scope.signupForm.$waiting = false;
							$scope.signupForm.$success = true;
						},
						function (data, status) {
							if (status == 400) {
								$scope.signupForm.errors = data;
							}
							$scope.signupForm.$waiting = false;
						});
	}

	$scope.init();

}])

// Signin Controller
.controller('SigninController', ['$scope', '$rootScope', '$window', 'authService', function ($scope, $rootScope, $window, authService) {

	$scope.init = function () {
		$rootScope.currentModule = 'signin';
		$scope.user = {
			remember_flag: false
		};
	}

	$scope.performSignin = function () {
		$scope.signinForm.$waiting = true;

		authService.signin($scope.user.login, $scope.user.password, $scope.user.remember_flag,
						function (data) {
							$scope.signinForm.errors = null;
							$scope.signinForm.$failed = false;
							$scope.signinForm.$success = true;

							$window.location.href = $rootScope.getUrl('');
						},
						function (data, status) {
							if (status == 400) {
								$scope.signinForm.errors = data;
							} else if (status == 403) {
								$scope.signinForm.errors = null;
								$scope.signinForm.$failed = true;
							}
							$scope.signinForm.$waiting = false;
						});
	}

	$scope.init();

}])

// User Controllers
.controller('UserScreenersController', ['$scope', '$rootScope', '$http', '$modal', 'queryString', 'Company', 'Screener', 'User', function ($scope, $rootScope, $http, $modal, queryString, Company, Screener, User) {
	
	$scope.init = function () {
		$rootScope.currentModule = 'screeners';
		$scope.getScreeners();
		$rootScope.selectedScreenerId = null;

		$scope.gridOptions = { 
	        data: 'stocks',
	        columnDefs: $rootScope.stockGridColumns,
	        //multiSelect: false,
	        selectWithCheckboxOnly: false,
	        showColumnMenu: true,
	        enableColumnResize: true,
	        enableColumnReordering: true,
	        //enablePinning: true,
	        maintainColumnRatios: false,
	        showSelectionCheckbox: true,
	        selectedItems: []
	    };
	}

	$scope.getScreeners = function () {
		$http
			.get($rootScope.getUrl('api/auth/screeners'))
			.then(function (response) {
				if (response.data.Rows) {
					$scope.screeners = response.data.Rows;

					if (!$rootScope.selectedScreenerId)
						$scope.selectedScreener = $scope.screeners[0];
					else {
						for (var i = 0; i < $scope.screeners.length; i++) {
							if ($scope.screeners[i].id == $rootScope.selectedScreenerId) {
								$scope.selectedScreener = $scope.screeners[i];
								break;
							}
						}
					}
				} else {
					$scope.screeners = [];
				}
			});
	}

	$scope.addFavoriteCompanies = function() {
		$scope.screenerForm.$waiting = true;

		if ($scope.gridOptions.selectedItems.length > 0) {
			var companySymbols = $.map($scope.gridOptions.selectedItems, function (item, i) {
				return item.symbol;
			});


			$http
				.post($rootScope.getUrl('api/user/' + $rootScope.me.username + '/favorite')
					, {
						symbol: companySymbols
					})
				.error(function (response) {
					$scope.screenerForm.$waiting = false;
				})
				.then(function (response) {
					if (response.data)
						$rootScope.me.favorites = response.data;

					$scope.screenerForm.$waiting = false;
				});
		} else {
			$scope.screenerForm.$waiting = false;
		}
	}

	$scope.getScreenerStocks = function () {
		if ($scope.selectedScreener)
			$scope.stocks = Screener.stocks({ screenerId: $scope.selectedScreener.id });
	}

	$scope.selectScreener = function (screener) {
		$scope.selectedScreener = screener;
	}

	$scope.loadNewScreenerForm = function () {
	    var modal = $modal.open({
	      templateUrl: 'screenerForm',
	      controller: 'UserScreenerNewController',
	      backdrop: 'static',
	      scope: $scope
	    });
	}

	$scope.loadEditScreenerForm = function () {

	    var modal = $modal.open({
	      templateUrl: 'screenerForm',
	      controller: 'UserScreenerEditController',
	      backdrop: 'static',
	      scope: $scope
	    });

	}

	$scope.deleteScreener = function () {
		if (confirm('Are you sure to delete this screener?')) {
			Screener.delete({ screenerId: $scope.selectedScreener.id },
							function (response) {
	                			$scope.getScreeners();

								$rootScope.selectedScreenerId = null;
							},
							function (response) {
	                			$.notify('Error deleting', 'error', { position: 'center' });
							});
		}
	}
	

	$scope.$watch('me.id', function (newVal, oldVal) {

		if (newVal !== oldVal) {
			$rootScope.listen('stock:updated', function (data) {
				$scope.getScreenerStocks();
			});
		}

	}, true);

	$scope.$watch('selectedScreener', function (newVal, oldVal) {
		if (newVal !== oldVal) {
			$scope.getScreenerStocks ();
		}

	    $(window).resize();
	});

	/*$scope.$watch('selectedScreenerId', function (newVal) {
		for (var s in $scope.screeners) {
			if (s.id == newVal) {
				$scope.selectScreener(s);
				break;
			}
		}
	}, true);*/

	$scope.init ();

}])
.controller('UserScreenerNewController', ['$scope', '$rootScope', '$http', '$modalInstance', 'Screener', function ($scope, $rootScope, $http, modal, Screener) {

	$scope.init = function () {
		$scope.modal = modal;

		$scope.screener = {
			name: '',
			filters: []
		};
	}

	$scope.addFilter = function () {
		var $selectedFilter = $('select[name=filter] option:selected');

		if ($.grep($scope.screener.filters, function (f) { return f.name == $selectedFilter.val(); }).length == 0)
			$scope.screener.filters.push ({
				name: $selectedFilter.val()
			});
	}

	$scope.removeFilter = function (i) {
		$scope.screener.filters.splice(i, 1);
	}

	$scope.saveScreener = function () {
		$scope.$waiting = true;

		$scope.screener = Screener.store($scope.screener,
						function (response) {
							$scope.errors = null;
							//$scope.screenerForm.$waiting = false;
							$scope.$success = true;

							$scope.getScreeners();

							$rootScope.selectedScreenerId = $scope.screener.id;

							$scope.modal.close ();
						},
						function (response) {
							if (response.status == 400) {
								$scope.errors = response.data;
							}

							$scope.$waiting = false;
						});
	}

	$scope.init ();

}])
.controller('UserScreenerEditController', ['$scope', '$rootScope', '$http', '$modalInstance', 'Screener', function ($scope, $rootScope, $http, modal, Screener) {

	$scope.init = function () {
		$scope.modal = modal;

		$scope.screener = angular.copy($scope.selectedScreener);
		//console.log ($scope.screener);
	}

	$scope.addFilter = function () {
		var $selectedFilter = $('select[name=filter] option:selected');

		if ($.grep($scope.screener.filters, function (f) { return f.name == $selectedFilter.val(); }).length == 0)
			$scope.screener.filters.push ({
				name: $selectedFilter.val()
			});
	}

	$scope.removeFilter = function (i) {
		$scope.screener.filters.splice(i, 1);
	}

	$scope.saveScreener = function () {
		$scope.$waiting = true;

		$scope.selectedScreener = Screener.save($scope.screener, function (response) {
							$scope.errors = null;
							$scope.$success = true;

							$scope.getScreeners();
							$scope.modal.close ();
						},
						function (response) {
							if (response.status == 400) {
								$scope.errors = response.data;
							}

							$scope.$waiting = false;
						});
	}

	$scope.$watch('$success', function (newVal) {
		if (newVal)
			$rootScope.selectedScreenerId = $scope.screener.id;
	}, true);

	$scope.init ();

}])
.controller('UserFavoritesController', ['$scope', '$rootScope', '$http', '$modal', 'queryString', 'Company', 'User', function ($scope, $rootScope, $http, $modal, queryString, Company, User) {

	$scope.init = function () {
		$rootScope.currentModule = 'favorites';
		$scope.getFavorites();
		$rootScope.stockGridColumns.push(
				{ displayName: 'Alert targets', groupable: false, cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text ng-show="row.entity.price.last_close"><span ng-show="me.alert.ceiling" class="text-success">{{row.entity.price.last_close+(row.entity.price.last_close * me.alert.ceiling/100) | number:2}}</span>  <span ng-show="me.alert.floor" class="text-danger">({{row.entity.price.last_close-(row.entity.price.last_close * me.alert.floor/100) | number:2}})</span></span></div>' }
		);
		$scope.gridOptions = { 
	        data: 'favorites',
	        columnDefs: $rootScope.stockGridColumns,
	        //multiSelect: false,
	        selectWithCheckboxOnly: false,
	        showColumnMenu: true,
	        enableColumnResize: true,
	        enableColumnReordering: true,
	        //enablePinning: true,
	        //maintainColumnRatios: false,
	        showSelectionCheckbox: true,
	        selectedItems: []
	    };
	}

	$scope.searchCompany = function (searchWord) {
		return $http
					.get($rootScope.getUrl('api/company'), 
						{
							params: $.extend(queryString.search({ symbol: searchWord }), queryString.order('symbol', 'asc'), queryString.paginate(1, 10)),
							ignoreLoadingBar: true
						})
					.then(function (response) {
						return response.data;
					});
	}

	$scope.listCompanies = function () {
		return $http
					.get($rootScope.getUrl('api/company'), 
						{
							params: queryString.order('symbol', 'asc'),
							ignoreLoadingBar: true
						}
					)
					.then(function (response) {
						return response.data;
					});
	}

	$scope.getFavorites = function () {
		$http
			.get($rootScope.getUrl('api/auth/favorites'))
			.then(function (response) {
				if (response.data.Rows)
					$scope.favorites = response.data.Rows;
				else
					$scope.favorites = [];

				$(window).resize();
			});
	}

	$scope.addFavoriteCompany = function() {
		$scope.favoriteForm.$waiting = true;

		if ($scope.companySymbol) {
			$http
				.post($rootScope.getUrl('api/user/' + $rootScope.me.username + '/favorite')
					, {
						symbol: $scope.companySymbol
					})
				.error(function (response) {
					$scope.favoriteForm.$waiting = false;
				})
				.then(function (response) {
					if (response.data)
						$rootScope.me.favorites = response.data;

					$scope.companySymbol = '';
					$scope.favoriteForm.$waiting = false;
				});
		} else {
			$scope.favoriteForm.$waiting = false;
		}
	}

	$scope.deleteFavoriteCompany = function (symbol) {
		$scope.favoriteForm.$waiting = true;

		if (symbol) {
			$http
				.post($rootScope.getUrl('api/user/' + $rootScope.me.username + '/delete-favorite')
					, {
						symbol: symbol
					})
				.error(function (response) {
					$scope.favoriteForm.$waiting = false;
				})
				.then(function (response) {
					if (response.data)
						$rootScope.me.favorites = response.data;

					$scope.favoriteForm.$waiting = false;
				});
		}
	}

	$scope.deleteFavoriteCompanies = function () {
		$scope.favoriteForm.$waiting = true;

		if ($scope.gridOptions.selectedItems.length > 0) {
			var symbols = $.map($scope.gridOptions.selectedItems, function (item, i) {
				return item.symbol;
			});

			$http
				.post($rootScope.getUrl('api/user/' + $rootScope.me.username + '/delete-favorite')
					, {
						symbol: symbols
					})
				.error(function (response) {
					$scope.favoriteForm.$waiting = false;
				})
				.then(function (response) {
					if (response.data)
						$rootScope.me.favorites = response.data;

					$scope.favoriteForm.$waiting = false;
				});
		}
	}

	$scope.loadAlertForm = function () {
	    var modal = $modal.open({
	      templateUrl: 'favoriteForm',
	      controller: 'UserFavoriteAlertController',
	      backdrop: 'static',
	      scope: $scope
	    });
	}

	$scope.updateFavorite = function (company) {
		if ($scope.favorites.length) {
			for (var i = 0; i < $scope.favorites.length; i++) {
				if (company.symbol == $scope.favorites[i].symbol) {
					$scope.favorites[i] = company;

					if (!$scope.$$phase) 
						$scope.$apply();

					break;
				}
			}
		}
	}

	$scope.$watch('me.favorites', function (newVal, oldVal) {
		if (newVal !== oldVal)
			$scope.getFavorites();

	    $(window).resize();
	}, true);

	$scope.$watch('me.id', function (newVal, oldVal) {

		if (newVal !== oldVal) {
			$rootScope.listen('stock:updated', function (data) {
				$scope.getFavorites();
			});
		}

	    $(window).resize();

	}, true);

	$scope.init ();

}])
.controller('UserFavoriteAlertController', ['$scope', '$rootScope', '$http', '$modalInstance', function ($scope, $rootScope, $http, modal) {

	$scope.init = function () {
		$scope.modal = modal;
		$scope.alert = angular.copy($rootScope.me.alert) || {};
		$scope.alertForm = {};
	}

	$scope.saveAlert = function () {
		$scope.alertForm.$waiting = true;

		$http
			.post($rootScope.getUrl('api/user/' + $rootScope.me.username + '/alert')
				, $scope.alert)
			.error(function (response) {
				$scope.alertForm.$waiting = false;
			})
			.then(function (response) {
				if (response.data)
					$rootScope.me.alert = response.data;

				$scope.modal.close();
				$scope.alertForm.$waiting = false;
			});
		$scope.alertForm.$waiting = false;
	}

	$scope.init ();

}])

// Home Controllers
.controller('SiteIndexController', ['$scope', '$rootScope', '$http', 'Screener', function ($scope, $rootScope, $http, Screener) {

	$scope.init = function () {
		$rootScope.currentModule = 'home';

		$scope.gridOptions = { 
	        data: 'stocks',
	        columnDefs: $rootScope.stockGridColumns,
	        //multiSelect: false,
	        selectWithCheckboxOnly: false,
	        showColumnMenu: true,
	        enableColumnResize: true,
	        enableColumnReordering: true,
	        showSelectionCheckbox: true,
	        selectedItems: []
	    };

		$scope.rand = (new Date).getTime();
		$scope.getScreeners();
		$scope.getIndexes();
	}

	$scope.getIndexes = function () {
		$http
			.get($rootScope.getUrl('api/index'))
			.then(function (response) {
				if (response.data)
					$scope.indexes = response.data;
				else
					$scope.indexes = [];
			});
	}

	$scope.getFavorites = function () {
		$http
			.get($rootScope.getUrl('api/auth/favorites'))
			.then(function (response) {
				if (response.data.Rows)
					$scope.favorites = response.data.Rows;
				else
					$scope.favorites = [];
			});
	}

	$scope.getScreeners = function () {
		$http
			.get($rootScope.getUrl('api/auth/screeners'))
			.then(function (response) {
				if (response.data.Rows) {
					$scope.screeners = response.data.Rows;

					$scope.selectedScreener = $scope.screeners[0];
				} else {
					$scope.screeners = [];
				}
			});
	}

	$scope.getScreenerStocks = function () {
		if ($scope.selectedScreener)
			$scope.stocks = Screener.stocks({ screenerId: $scope.selectedScreener.id });
	}

	$scope.selectScreener = function (screener) {
		$scope.selectedScreener = screener;
	}

	$scope.$watch('selectedScreener', function (newVal, oldVal) {
		if (newVal !== oldVal) {
			$scope.getScreenerStocks ();
		}

	    $(window).resize();
	});

	$scope.$watch('me.favorites', function (newVal, oldVal) {
		if (newVal !== oldVal)
			$scope.getFavorites();
	}, true);

	$scope.$watch('me.id', function (newVal, oldVal) {

		if (newVal !== oldVal) {
			/*$rootScope.listen('company:added', function (data) {
				$scope.newCompany.push(data);

				if (!$scope.$$phase) 
					$scope.$apply()
			});*/
			$rootScope.listen('stock:updated', function (data) {
				$scope.rand = (new Date).getTime();
				$scope.getIndexes();
				$scope.getFavorites();

				$scope.getScreenerStocks();

				if (!$scope.$$phase) 
					$scope.$apply()
			});
		}

	}, true);

	$scope.init();

}])

.controller('SiteIndexSideBarController', ['$scope', '$rootScope', '$http', 'Screener', 'queryString', function ($scope, $rootScope, $http, Screener, queryString) {
	$scope.init = function () {
		$scope.getStockStats();

		$scope.gainerGridOptions = { 
	        data: 'gainers',
	        columnDefs: [

	        	{ displayName: 'Stock', field: 'symbol', groupable: false, cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><a href ng-click="loadStockWindow(row.getProperty(\'symbol\'))">{{row.getProperty(col.field)}} <i class="fa fa-star-o" ng-show="isFavorite(row.getProperty(\'id\'))"></i></a></div>' },
				{ displayName: '', field: 'ref_id', groupable: false, sortable: false, resizable: false, width: 62, cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><a ng-href="https://www.google.com/finance?q=BKK:{{row.getProperty(\'symbol\')}}" target="_blank"><img ng-src="https://www.google.com/finance/chart?cht=s&cid={{row.getProperty(\'ref_id\')}}&p={{sparklineInterval}}"></a></div>', headerCellTemplate: '<div class="text-center"><a href ng-click="setSparklineInterval(\'1\')">d</a> | <a href ng-click="setSparklineInterval(\'1M\')">m</a> | <a href ng-click="setSparklineInterval(\'1Y\')">y</a></div>'  },
				{ displayName: 'Price', field: 'price.last_quote', groupable: false },
				{ displayName: 'Change(%)', field: 'price.change_today', groupable: false, cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text ng-class="{\'text-danger\': row.getProperty(col.field) < 0, \'text-success\': row.getProperty(col.field) > 0, \'text-warning\': row.getProperty(col.field) == 0}"><i class="fa" ng-class="{\'fa-arrow-circle-down\': row.getProperty(col.field) < 0, \'fa-arrow-circle-up\': row.getProperty(col.field) > 0, \'fa-arrow-circle-right\': row.getProperty(col.field) == 0}"></i> {{row.getProperty(col.field)}}</span></div>' }
				
	        ],
	        multiSelect: false,
	        //showColumnMenu: true,
	        enableColumnResize: true,
	        //enableColumnReordering: true,
	        selectedItems: []
	    };

	    $scope.volumeGridOptions = $.extend({}, $scope.gainerGridOptions, { data: 'volumes' });
	    $scope.xdGridOptions = $.extend({}, $scope.gainerGridOptions, { data: 'xd_soons' });
	}

	$scope.getStockStats = function () {
		$http
			.get($rootScope.getUrl('api/company'), 
				{
					params: $.extend(queryString.order('price.change_today', 'desc'), queryString.paginate(1, 24)),
					ignoreLoadingBar: true
				})
			.then(function (response) {
				$scope.gainers = response.data;
			});


		$http
			.get($rootScope.getUrl('api/company'), 
				{
					params: $.extend(queryString.order('volume.volume', 'desc'), queryString.paginate(1, 24)),
					ignoreLoadingBar: true
				})
			.then(function (response) {
				$scope.volumes = response.data;
			});

		/*$http
			.get($rootScope.getUrl('api/company'), 
				{
					params: $.extend(queryString.query({'dividend.days_to_xd': 2}), queryString.order('dividend.days_to_xd', 'asc'), queryString.paginate(1, 24)),
					ignoreLoadingBar: true
				})
			.then(function (response) {
				$scope.xd_soons = response.data;
			});*/
	}

	$scope.$watch('me.id', function (newVal, oldVal) {

		if (newVal !== oldVal) {
			$rootScope.listen('stock:updated', function (data) {
				$scope.getStockStats();
			});

	    	$(window).resize();
		}

	}, true);

	$scope.init ();
}])

// Error : 404 Controller
.controller('Error404Controller', ['$scope', '$rootScope', function ($scope, $rootScope) {

}])