var app = angular
.module('oadtz', ['oadtz-services', 'ngAnimate', 'ngGrid', 'ngResource', 'ui.bootstrap', 'ui.select2', 'ui.utils', 'chieffancypants.loadingBar'])
.config(['$httpProvider', 'cfpLoadingBarProvider', function ($httpProvider, cfpLoadingBarProvider) {
  	$httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
  	
    cfpLoadingBarProvider.includeSpinner = false;
}])
.run(['$rootScope', '$http', '$window', 'authService', function ($rootScope, $http, $window, authService) {

	$rootScope.init = function () {
		$rootScope.currentModule = 'home';
		$rootScope.getCurrentUser();
		$rootScope.socket = new Pusher($rootScope.getMeta('socket'));
		$rootScope.sparklineInterval = 1;
		$rootScope.getSETInfo();

		$rootScope.modules = [
			{ name: 'home', text: 'Dashboard', url: $rootScope.getUrl(''), icon: 'fa fa-dashboard' },
			{ name: 'favorites', text: 'Favorites', url: $rootScope.getUrl('favorites'), icon: 'fa fa-star' },
			{ name: 'screeners', text: 'Screeners', url: $rootScope.getUrl('screeners'), icon: 'fa fa-eye' }
		];

		$rootScope.screenerFilters = {
			price: [
				{'label': 'Latest Price', 'name': 'last_quote'},
				{'label': 'EPS', 'name': 'eps'},
				{'label': 'Change(%)', 'name': 'change_today'},
				{'label': 'Change 13 weeks(%)', 'name': 'change_13week'},
				{'label': 'Change 26 weeks(%)', 'name': 'change_26week'},
				{'label': 'Change 52 weeks(%)', 'name': 'change_52week'},
				{'label': 'High 52 weeks', 'name': 'high_52week'},
				{'label': 'Low 52 weeks', 'name': 'low_52week'},
				{'label': 'Avg 50 days', 'name': 'avg_50day'},
				{'label': 'Avg 150 days', 'name': 'avg_150day'},
				{'label': 'Avg 200 days', 'name': 'avg_200day'}
			],
			value: [
				{'label': 'Market Capital', 'name': 'market_cap'},
				{'label': 'P/E Ratio', 'name': 'pe'},
				{'label': 'P/E Ratio 1 year forward', 'name': 'pe_1year'}
			],
			volume: [
				{'label': 'Volume', 'name': 'volume'},
				{'label': 'Avg Volume', 'name': 'avg_volume'}
			],
			finance: [
				{'label': 'Book Value', 'name': 'bv'},
				{'label': 'P/BV Ratio', 'name': 'pbv'},
				{'label': 'Cash/share', 'name': 'cash_per_share_year'},
				{'label': 'Current ratio', 'name': 'current_assets_to_liabilities_ratio_year'},
				{'label': 'LT debt/assets (Recent yr)(%)', 'name': 'longterm_debt_to_assets_year'},
				{'label': 'LT debt/assets (Recent qtr)(%)', 'name': 'longterm_debt_to_assets_quarter'},
				{'label': 'Total debt/assets (Recent yr)(%)', 'name': 'total_debt_to_assets_year'},
				{'label': 'Total debt/assets (Recent qtr)(%)', 'name': 'total_debt_to_assets_quarter'},
				{'label': 'LT debt/equity (Recent yr)(%)', 'name': 'longterm_debt_to_equity_year'},
				{'label': 'LT debt/equity (Recent qtr)(%)', 'name': 'longterm_debt_to_equity_quarter'},
				{'label': 'Total debt/equity (Recent yr)(%)', 'name': 'total_debt_to_equity_year'},
				{'label': 'Total debt/equity (Recent qtr)(%)', 'name': 'total_debt_to_equity_quarter'}
			],
			dividend: [
				{'label': 'Div Yield (%)', 'name': 'yield'},
				{'label': 'Days to XD', 'name': 'days_to_xd'},
				{'label': 'Div Recent quarter', 'name': 'recent_quarter'},
				{'label': 'Div Next quarter', 'name': 'next_quarter'},
				{'label': 'Div Recent year', 'name': 'per_share'},
				{'label': 'Div Next year', 'name': 'next_year'}
			],
			growth: [
				{'label': 'Net income 5 years(%)', 'name': 'net_income_5year'},
				{'label': 'Revenue 5 years(%)', 'name': 'revenue_5year'},
				{'label': 'Revenue 10 years(%)', 'name': 'revenue_10year'},
				{'label': 'EPS 5 years(%)', 'name': 'eps_5year'},
				{'label': 'EPS 10 years(%)', 'name': 'eps_10year'}
			]
		};

		$rootScope.stockGridColumns = [
	        	{ displayName: 'Stock', field: 'symbol', pinned: true, pinnable: false, groupable: false, cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><a href ng-click="loadStockWindow(row.getProperty(\'symbol\'))">{{row.getProperty(col.field)}} <i class="fa fa-star-o" ng-show="isFavorite(row.getProperty(\'id\'))"></i></a></div>' },
				//{ displayName: '', field: 'ref_id', groupable: false, sortable: false, resizable: false, width: 62, cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><a ng-href="https://www.google.com/finance?q=BKK:{{row.getProperty(\'symbol\')}}" target="_blank"><img ng-src="https://www.google.com/finance/chart?cht=s&cid={{row.getProperty(\'ref_id\')}}&p={{sparklineInterval}}"></a></div>', headerCellTemplate: '<div class="text-center"><a href ng-click="setSparklineInterval(\'1\')">d</a> | <a href ng-click="setSparklineInterval(\'1M\')">m</a> | <a href ng-click="setSparklineInterval(\'1Y\')">y</a></div>'  },
				{ displayName: '1d', field: 'ref_id', groupable: false, sortable: false, resizable: false, width: 62, cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><a ng-href="https://www.google.com/finance?q=BKK:{{row.getProperty(\'symbol\')}}" target="_blank"><img ng-src="https://www.google.com/finance/chart?cht=s&cid={{row.getProperty(\'ref_id\')}}&p=1"></a></div>' },
				{ displayName: '1m', field: 'ref_id', groupable: false, sortable: false, resizable: false, width: 62, cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><a ng-href="https://www.google.com/finance?q=BKK:{{row.getProperty(\'symbol\')}}" target="_blank"><img ng-src="https://www.google.com/finance/chart?cht=s&cid={{row.getProperty(\'ref_id\')}}&p=1M"></a></div>' },
				{ displayName: '3m', field: 'ref_id', groupable: false, sortable: false, resizable: false, width: 62, cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><a ng-href="https://www.google.com/finance?q=BKK:{{row.getProperty(\'symbol\')}}" target="_blank"><img ng-src="https://www.google.com/finance/chart?cht=s&cid={{row.getProperty(\'ref_id\')}}&p=3M"></a></div>' },
				{ displayName: '1y', field: 'ref_id', groupable: false, sortable: false, resizable: false, width: 62, cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><a ng-href="https://www.google.com/finance?q=BKK:{{row.getProperty(\'symbol\')}}" target="_blank"><img ng-src="https://www.google.com/finance/chart?cht=s&cid={{row.getProperty(\'ref_id\')}}&p=1Y"></a></div>' },
				{ displayName: '5y', field: 'ref_id', groupable: false, sortable: false, resizable: false, width: 62, cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><a ng-href="https://www.google.com/finance?q=BKK:{{row.getProperty(\'symbol\')}}" target="_blank"><img ng-src="https://www.google.com/finance/chart?cht=s&cid={{row.getProperty(\'ref_id\')}}&p=5Y"></a></div>' },
				{ displayName: 'Score', field: 'score', visible: false, filter: 'number' },
				{ displayName: 'Industry', field: 'industry', visible: false },
				{ displayName: 'Sub Industry', field: 'sub_industry', visible: false },
				{ displayName: 'Latest Price', field: 'price.last_quote', groupable: false },
				{ displayName: 'EPS', field: 'price.eps', groupable: false, visible: false },
				{ displayName: 'Change(%)', field: 'price.change_today', groupable: false, cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text ng-class="{\'text-danger\': row.getProperty(col.field) < 0, \'text-success\': row.getProperty(col.field) > 0, \'text-warning\': row.getProperty(col.field) == 0}"><i class="fa" ng-class="{\'fa-arrow-circle-down\': row.getProperty(col.field) < 0, \'fa-arrow-circle-up\': row.getProperty(col.field) > 0, \'fa-arrow-circle-right\': row.getProperty(col.field) == 0}"></i> {{row.getProperty(col.field)}}</span></div>' },
				{ displayName: 'Change 13 weeks(%)', field: 'price.change_13week', groupable: false, visible: false, cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text ng-class="{\'text-danger\': row.getProperty(col.field) < 0, \'text-success\': row.getProperty(col.field) > 0, \'text-warning\': row.getProperty(col.field) == 0}"><i class="fa" ng-class="{\'fa-arrow-circle-down\': row.getProperty(col.field) < 0, \'fa-arrow-circle-up\': row.getProperty(col.field) > 0, \'fa-arrow-circle-right\': row.getProperty(col.field) == 0}"></i> {{row.getProperty(col.field)}}</span></div>' },
				{ displayName: 'Change 26 weeks(%)', field: 'price.change_26week', groupable: false, visible: false, cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text ng-class="{\'text-danger\': row.getProperty(col.field) < 0, \'text-success\': row.getProperty(col.field) > 0, \'text-warning\': row.getProperty(col.field) == 0}"><i class="fa" ng-class="{\'fa-arrow-circle-down\': row.getProperty(col.field) < 0, \'fa-arrow-circle-up\': row.getProperty(col.field) > 0, \'fa-arrow-circle-right\': row.getProperty(col.field) == 0}"></i> {{row.getProperty(col.field)}}</span></div>' },
				{ displayName: 'Change 52 weeks(%)', field: 'price.change_52week', groupable: false, visible: false, cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text ng-class="{\'text-danger\': row.getProperty(col.field) < 0, \'text-success\': row.getProperty(col.field) > 0, \'text-warning\': row.getProperty(col.field) == 0}"><i class="fa" ng-class="{\'fa-arrow-circle-down\': row.getProperty(col.field) < 0, \'fa-arrow-circle-up\': row.getProperty(col.field) > 0, \'fa-arrow-circle-right\': row.getProperty(col.field) == 0}"></i> {{row.getProperty(col.field)}}</span></div>' },
				{ displayName: 'High 52 weeks', field: 'price.high_52week', groupable: false, visible: false },
				{ displayName: 'Low 52 weeks', field: 'price.low_52week', groupable: false, visible: false },
				{ displayName: 'Avg 50 days', field: 'price.avg_50day', groupable: false, visible: false },
				{ displayName: 'Avg 150 days', field: 'price.avg_150day', groupable: false, visible: false },
				{ displayName: 'Avg 200 days', field: 'price.avg_200day', groupable: false, visible: false },
				{ displayName: 'Market Capital', field: 'value.market_cap', groupable: false, visible: false, cellFilter: 'number' },
				{ displayName: 'P/E Ratio', field: 'value.pe', groupable: false },
				{ displayName: 'P/E Ratio 1 year forward', field: 'value.pe_1year', groupable: false, visible: false },
				{ displayName: 'Book Value', field: 'finance.bv', groupable: false, visible: false },
				{ displayName: 'P/BV Ratio', field: 'finance.pbv', groupable: false },
				{ displayName: 'Cash/share', field: 'finance.cash_per_share_year', groupable: false, visible: false },
				{ displayName: 'Current ratio', field: 'finance.current_assets_to_liabilities_ratio_year', groupable: false, visible: false },
				{ displayName: 'LT debt/assets (Recent yr)(%)', field: 'finance.longterm_debt_to_assets_year', groupable: false, visible: false },
				{ displayName: 'LT debt/assets (Recent qtr)(%)', field: 'finance.longterm_debt_to_assets_quarter', groupable: false, visible: false },
				{ displayName: 'Total debt/assets (Recent yr)(%)', field: 'finance.total_debt_to_assets_year', groupable: false, visible: false },
				{ displayName: 'Total debt/assets (Recent qtr)(%)', field: 'finance.total_debt_to_assets_quarter', groupable: false, visible: false },
				{ displayName: 'LT debt/equity (Recent yr)(%)', field: 'finance.longterm_debt_to_equity_year', groupable: false, visible: false },
				{ displayName: 'LT debt/equity (Recent qtr)(%)', field: 'finance.longterm_debt_to_equity_quarter', groupable: false, visible: false },
				{ displayName: 'Total debt/equity (Recent yr)(%)', field: 'finance.total_debt_to_equity_year', groupable: false, visible: false },
				{ displayName: 'Total debt/equity (Recent qtr)(%)', field: 'finance.total_debt_to_equity_quarter', groupable: false, visible: false },
				{ displayName: 'Volume', field: 'volume.volume', groupable: false, cellFilter: 'number' },
				{ displayName: 'Avg Volume', field: 'volume.avg_volume', groupable: false, visible: false, cellFilter: 'number' },
				{ displayName: 'Div Yield(%)', field: 'dividend.yield', groupable: false },
				{ displayName: 'XD Date', field: 'dividend.xd_date', groupable: false, cellFilter: 'date' },
				{ displayName: 'Days to XD', field: 'dividend.days_to_xd', groupable: false },
				{ displayName: 'Div Recent quarter', field: 'rdividend.ecent_quarter', groupable: false, visible: false },
				{ displayName: 'Div Next quarter', field: 'dividend.next_quarter', groupable: false, visible: false },
				{ displayName: 'Div Recent year', field: 'dividend.per_share', groupable: false, visible: false },
				{ displayName: 'Div Next year', field: 'dividend.next_year', groupable: false, visible: false },
				{ displayName: 'Net income 5 years(%)', field: 'growth.net_income_5year', groupable: false, visible: false },
				{ displayName: 'Revenue 5 years(%)', field: 'growth.revenue_5year', groupable: false, visible: false },
				{ displayName: 'Revenue 10 years(%)', field: 'growth.revenue_10year', groupable: false, visible: false },
				{ displayName: 'EPS 5 years(%)', field: 'growth.eps_5year', groupable: false, visible: false },
				{ displayName: 'EPS 10 years(%)', field: 'growth.eps_10year', groupable: false, visible: false }
	        ];


		$('[data-toggle=offcanvas]').click(function() {
			$('.row-offcanvas').toggleClass('active');
		});

		if ($('.sidebar-right').length == 0)
			$('.content').css('margin-right', 0);

		$rootScope.listen('stock:updated', function (data) {
			$rootScope.getSETInfo();
		});

		$rootScope.listen('company:price_updated', function (company) {
			if ($.inArray(company.id, $rootScope.me.favorites) != -1) {
				if ($rootScope.me.alert && $rootScope.me.alert.ceiling > 0 && company.price.change_today >= $rootScope.me.alert.ceiling) {
					//(company.price.change_today >= $rootScope.me.alert.ceiling || ((company.price.last_quote - company.price.prev_quote)/company.price.prev_quote)*100 >= $rootScope.me.alert.ceiling)) {
					$.growl.notice({
						title: '<i class="fa fa-arrow-circle-up"></i> ' + company.symbol,
						message: '<h4>' + company.price.last_quote + '</h4> <h5>(' + company.price.change_today + '%)</h5>'
						//message: '<h4>' + company.price.last_quote + '</h4> <h5>(From Close: ' + company.price.change_today + '%)</h5> <h5>(From Last Quote: ' + (((company.price.last_quote - company.price.prev_quote)/company.price.prev_quote)*100).toFixed(2) + '%)</h5>'
					});
				} else if ($rootScope.me.alert && $rootScope.me.alert.floor > 0 && company.price.change_today*-1 >= $rootScope.me.alert.floor) {
					//(company.price.change_today*-1 >= $rootScope.me.alert.floor || (((company.price.last_quote - company.price.prev_quote)/company.price.prev_quote)*100)*-1 >= $rootScope.me.alert.floor)) {
					$.growl.error({
						title: '<i class="fa fa-arrow-circle-down"></i> ' + company.symbol,
						message: '<h4>' + company.price.last_quote + '</h4> <h5>(' + company.price.change_today + '%)</h5>'
						//message: '<h4>' + company.price.last_quote + '</h4> <h5>(From Close: ' + company.price.change_today + '%)</h5> <h5>(From Last Quote: ' + (((company.price.last_quote - company.price.prev_quote)/company.price.prev_quote)*100).toFixed(2) + '%)</h5>'
					});
				}
			}
		});
	}

	$rootScope.setSparklineInterval = function (i) {
		$rootScope.sparklineInterval = i;
	}

	$rootScope.getCurrentUser = function () {
   		authService.user(function (user) {
   			$rootScope.me = user;
   		});
	}

	$rootScope.getConfig = function (configName, success) {
		authService.getConfig({
			configname: configName
		}, function (config) {
			$rootScope[configName] = config;

			if (success)
				success;
		});
	}

	$rootScope.getScreenerFilterLabel = function (name) {
		var filter = name.split('.');

		if ($rootScope.screenerFilters[filter[0]]) {
			for(var i = 0; i < $rootScope.screenerFilters[filter[0]].length; i++) {
				if (filter[1] == $rootScope.screenerFilters[filter[0]][i].name) {
					return $rootScope.screenerFilters[filter[0]][i].label;
				}
			}
		}
	}

	$rootScope.listen = function (event, handler) {
	    var channel = $rootScope.socket.subscribe('default');
	    
	    channel.bind(event, handler);
	};

	$rootScope.listenForUser = function (userId, event, handler) {
	    var channel = $rootScope.socket.subscribe('user@' + userId);
	    
	    channel.bind(event, handler);
	};

	$rootScope.getUrl = function (path) {
		return $('base').attr('href') + '/' + path;
	}

	$rootScope.getMeta = function (meta) {
		return $('meta[name='+meta+']').attr('content');
	}

	$rootScope.performSignout = function () {
		authService.signout(function (result) {
			if (result)
				$window.location.href = $rootScope.getUrl('signin');
		});
	}

	$rootScope.isFavorite = function (id) {
		return $.inArray(id, $rootScope.me.favorites) != -1;
	}

	$rootScope.loadStockWindow = function (symbol) {
		var win = window.open('http://www.settrade.com/C04_01_stock_quote_p1.jsp?txtSymbol=' + symbol);
	}

	$rootScope.getSETInfo = function () {
		$http
			.get($rootScope.getUrl('api/index'))
			.then(function (response) {
				if (response.data) {
					$rootScope.SET = $.grep(response.data, function (i) {
						return i.symbol == 'SET';
					});

					if ($rootScope.SET.length > 0) {
						$rootScope.SET = $rootScope.SET[0];

						if ($rootScope.SET.price.change_today > 0)
							$rootScope.SET.alertType = 'success';
						else if ($rootScope.SET.price.change_today < 0)
							$rootScope.SET.alertType = 'danger';
						else
							$rootScope.SET.alertType = 'info';
					}
				}
			});
	}

	$rootScope.init ();

}]);