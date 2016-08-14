@section('title')
{{'Dashboard'}}
@stop

@section('content')
<div ng-controller="SiteIndexController">
	<header class="content-header">
		<h1 class="light no-margin">Dashboard</h1>
	</header>

	<section class="bordered bg-color light-grey">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<h3 class="light">Today's Market</h3>
					<div class="metro-area">
						<ul class="metro-shortcut clearfix list-unstyled row">
							<li class="item wide" ng-repeat="index in indexes" ng-animate="{enter: 'fade-in'}">
								<span class="media box bg-color" ng-class="{red: index.price.change_today < 0, 'light-green': index.price.change_today > 0, yellow: index.price.change_today == 0}">
							
								  <div class="media-body">
								  	<div class="col-lg-8">
									    <h2 class="media-heading">
									    	<!--i class="fa" style="font-size: 20px" ng-class="{'fa-frown-o': index.price.change_today < 0, 'fa-smile-o': index.price.change_today > 0, 'fa-meh-o': index.price.change_today == 0}"></i-->
									    	@{{index.symbol}}
									    </h2>
									    <h3>@{{index.price.last_quote | number:2}}</h3>
									    <div>
									    	<p class="pull-left">@{{index.price.change_today | number:2}}%</p>
									    	<p class="pull-right">@{{index.price.last_quote - index.price.last_close  | number:2}}</p>
									    </div>
								    </div>
								  	<div class="col-lg-4">
									  	<a ng-href="https://www.google.com/finance?q=INDEXBKK:@{{index.symbol}}" target="_blank">
									  		<p><img class="image" ng-src="https://www.google.com/finance/chart?cht=s&cid=@{{index.ref_id}}&p=1&rand=@{{rand}}"></p>
									  		<p><img class="image" ng-src="https://www.google.com/finance/chart?cht=s&cid=@{{index.ref_id}}&p=1M&rand=@{{rand}}"></p>
									  		<p><img class="image" ng-src="https://www.google.com/finance/chart?cht=s&cid=@{{index.ref_id}}&p=1Y&rand=@{{rand}}"></p>
									  	</a>
								  	</div>
								  </div>
								</span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>


	<section class="bordered bg-color dark-grey" ng-show="favorites.length > 0">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<h3 class="light">Favorites</h3>
					<div class="metro-area">
						<ul class="metro-shortcut clearfix list-unstyled row">
							<li class="item wide" ng-repeat="company in favorites" ng-animate="{enter: 'fade-in'}">
								<span class="media box bg-color" ng-class="{red: company.price.change_today < 0, green: company.price.change_today > 0, yellow: company.price.change_today == 0}">

								  
								  
								  <div class="media-body">
								  	<div class="col-lg-8">
								  		<h2 class="media-heading">
								  			<!--i class="pull-left fa" style="font-size: 20px" ng-class="{'fa-frown-o': company.price.change_today < 0, 'fa-smile-o': company.price.change_today > 0, 'fa-meh-o': company.price.change_today == 0}"></i-->
									    	<a href ng-click="loadStockWindow(company.symbol)">@{{company.symbol}}</a>
									    </h2>
									    <h3>@{{company.price.last_quote | number:2}}</h3>
									    <div>
									    	<p class="pull-left">@{{company.price.change_today | number:2}}%</p>
									    	<p class="pull-right">@{{company.price.last_quote - company.price.last_close  | number:2}}</p>
									    </div>
								    </div>
								  	<div class="col-lg-4">
									  	<a ng-href="https://www.google.com/finance?q=BKK:@{{company.symbol}}" target="_blank">
									  		<p><img class="image" ng-src="https://www.google.com/finance/chart?cht=s&cid=@{{company.ref_id}}&p=1&rand=@{{rand}}"></p>
									  		<p><img class="image" ng-src="https://www.google.com/finance/chart?cht=s&cid=@{{company.ref_id}}&p=1M&rand=@{{rand}}"></p>
									  		<p><img class="image" ng-src="https://www.google.com/finance/chart?cht=s&cid=@{{company.ref_id}}&p=1Y&rand=@{{rand}}"></p>
									  	</a>
								  	</div>
								  </div>
								</span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="bordered">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<h3 class="light">Screeners</h3>
					<h3 class="dropdown" ng-show="screeners.length > 0">
						<a href class="dropdown-toggle" style="text-decoration: none">@{{selectedScreener.name}} <i class="fa fa-caret-down"></i></a>
						<ul class="dropdown-menu">
							<li ng-repeat="s in screeners | orderBy : 'name'">
								<a href ng-click="selectScreener(s)">@{{s.name}}</a>
							</li>
						</ul>
					</h3>
					<div class="metro-area">
						<div ng-show="screeners.length > 0">
					        <div class="datagrid screeners-grid" ng-cloak ng-grid="gridOptions" style="height: 400px">
					        </div>
						</div>
						<h3 class="text-muted text-center ng-cloak" ng-cloak ng-show="screeners.length <= 0">
							You don't have any screener yet.
						</h3>
					</div>
				</div>
			</div>
		</div>
	</section>
	<br>
</div>
@stop

@section('right-panel')
<aside class="sidebar sidebar-right" ng-controller="SiteIndexSideBarController">
	<div class="widget">
		<header class="widget-header">
			<a href ng-click="$gainers_hide = !$gainers_hide">
				<i class="fa icon-metro" ng-class="{'fa-caret-right': $gainers_hide, 'fa-caret-down': !$gainers_hide }"></i>
				<h3>Top Gainers</h3>
			</a>
		</header>
		<article class="widget-content" ng-hide="$gainers_hide">
			<div>
		        <div class="datagrid screeners-grid" ng-grid="gainerGridOptions" style="height: 200px">
		        </div>
			</div>
		</article>
	</div>

	<div class="widget">
		<header class="widget-header">
			<a href ng-click="$volumes_hide = !$volumes_hide">
				<i class="fa icon-metro" ng-class="{'fa-caret-right': $volumes_hide, 'fa-caret-down': !$volumes_hide }"></i>
				<h3>Top Volumes</h3>
			</a>
		</header>
		<article class="widget-content" ng-hide="$volumes_hide">
			<div>
		        <div class="datagrid screeners-grid" ng-grid="volumeGridOptions" style="height: 200px">
		        </div>
			</div>
		</article>
	</div>


	<!--div class="widget">
		<header class="widget-header">
			<a href ng-click="$xd_hide = !$xd_hide">
				<i class="fa icon-metro" ng-class="{'fa-caret-right': $xd_hide, 'fa-caret-down': !$xd_hide }"></i>
				<h3>XD Soon</h3>
			</a>
		</header>
		<article class="widget-content" ng-hide="$xd_hide">
			<div>
		        <div class="datagrid screeners-grid" ng-grid="xdGridOptions" style="height: 200px">
		        </div>
			</div>
		</article>
	</div-->
</aside>	
@stop