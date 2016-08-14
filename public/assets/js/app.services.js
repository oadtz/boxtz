angular
.module('oadtz-services', [])
.factory('siteInterceptor', ['$q', function ($q) {
	return function (promise) {
		return promise.then(function (response) {
			return response;
		}, function (response) {
			return $q.reject(response);
		});
	}
}])
.factory('queryString', [function () {
	return {
		search: function (search) {
			var s = {};

			for(var i in search) {
				s['s['+i+']'] = search[i]
			}

			return s;
		},
		query: function (query) {
			var q = {};

			for(var i in query) {
				q['q['+i+']'] = query[i]
			}

			return q;
		},
		order: function (order, dir) {
			var o = {
				'o[order_by]': order,
				'o[order_dir]': dir 
			};

			return o;
		},
		paginate: function (page, limit) {
			var p = {
				'p[page]': page,
				'p[limit]': limit
			};

			return p;
		}
	};
}])
.factory('resource', ['$resource', function ($resource) {
    return function( url, params, methods ) {
        var defaults = {
            save: { method: 'PUT', isArray: false },
            store: { method: 'POST', isArray: false }
        };

        methods = angular.extend( defaults, methods );

        return $resource( url, params, methods );
    };
}])
.factory('authService', ['$http', '$rootScope', function ($http, $rootScope) {
	return {
		/*user: function (success, error) {
			return $http.get('/api/auth/user').success(success).error(error);
		},		logout: function (success, error) {
			return $http.get('/api/auth/logout').success(success).error(error);
		},
		verify: function (username, token, success, error) {
			return $http.post('/api/auth/verify', {
				username: username,
				token: token
			}).success(success).error(error);
		}*/
		signin: function (login, password, rememberFlag, success, error) {
			return $http
						.post($rootScope.getUrl('api/auth/signin'), {
							login: login,
							password: password,
							remember_flag: rememberFlag
						})
						.success(success)
						.error(error);
		},

		signup: function (user, success, error) {
			return $http
						.post($rootScope.getUrl('api/auth/signup'), user)
						.success(success)
						.error(error);
		},

		signout: function (success, error) {
			return $http
						.get($rootScope.getUrl('api/auth/signout'))
						.success(success)
						.error(error);
		}, 

		user: function (success, error) {
			return $http
						.get($rootScope.getUrl('api/auth/user'))
						.success(success)
						.error(error);
		}
	};
}])
.factory('validateService', ['$http', function ($http) {
	return {
		checkRequired: function ($value) {
			return $.trim($value) != '';
		}
	};
}])
.factory('Company', ['$rootScope', 'resource', function ($rootScope, $resource) {
	return $resource($rootScope.getUrl('api/company/:symbol/:action'), {
				symbol: '@symbol'
			});
}])
.factory('Screener', ['$rootScope', 'resource', function ($rootScope, $resource) {
	return $resource($rootScope.getUrl('api/screener/:screenerId/:action'), {
				screenerId: '@id'
			},
			{
				stocks: { method: 'GET', params: { action: 'stocks' }, isArray: true }
			});
}])
.factory('User', ['$rootScope', 'resource', function ($rootScope, $resource) {
	return $resource('/api/user/:username/:action', {
				username: '@username'
			}, 
			{
				items: { method: 'GET', params: { action: 'item' }, isArray: true },
				collections: { method: 'GET', params: { action: 'collection'}, isArray: true }
			});
}]);