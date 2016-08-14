angular.module('oadtz-directives', [])
.directive('autoScroll', function () {
	return function (scope, element, attrs) {
    	// watch the expression, and update the UI on change.
    	scope.$watch(attrs.scroll, function(value) {
    		if (value) {   			
				$('html, body').animate({
					scrollTop: $(element).offset().top - 100
				}, 400);
    		}
    	});
	}
})
.directive('placeholder', function () {
	return {
		restrict: 'A',
		link: function (scope, element, attrs) {
			if (!Modernizr.input.placeholder)
				$(element).placeholder();
		}
	};
})
.directive('notificationBar', function () {
	return {
		restrict: 'A',
		template: '<div style="min-height: 20px">' +
			            '<div class="notification-bar" style="position: fixed; margin-left: -20px; margin-right: -20px; width: 100%">' +
			                '<div class="progress progress-striped active {{notification.type}}" style="overflow: visible; margin-bottom: 0px" ng-show="notification.show"  ng-animate="{show: \'fade-in\'}">' +
			                    '<div class="notification-text bar" style="width: 100%;">{{notification.text}}</div>' +
			                '</div>' +
			            '</div>' +
			        '</div>',
	};
})
.directive('popupAlert', function () {
	return {
		restrict: 'A',
		link: function (scope, element, attrs) {
			$(element).css('display', 'none');

			scope.$watch(attrs.show, function (newValue, oldValue) {
				if (newValue) {
					var options = {
						type: attrs.type,
						text: $(element).html()
					};

					$.noty.closeAll();

					if (attrs.container)
						$(attrs.container).noty (options);
					else
						noty(options);
				} else {
					$.noty.closeAll();
				}
			});
		}
	};
});