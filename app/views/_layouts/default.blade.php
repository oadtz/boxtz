<!DOCTYPE html>
<html lang="en" ng-app="oadtz">
	<head>
		<meta charset="utf-8">
		<title>@yield('title')</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="Thanapat Pirmphol">
		@include('_layouts.styles')
	</head>

  	<body ng-cloak>
  		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  			<div class="container">
	  			<div class="navbar-header">

	  				<!--<button type="button" class="btn btn-flat btn-navbar-mobile pull-right hidden-desktop" data-toggle="collapse" data-target=".sidebar-left">-->
		            <button type="button" class="navbar-toggle pull-right show-xs show-sm show-md" data-toggle="offcanvas" data-target=".sidebar-left">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>

					<a class="navbar-brand" href="{{URL::to('/')}}"><img src="{{URL::asset('assets/images/logo_small.png')}}"></a>
	  			</div>


				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav navbar-right">
						<!--li><a href="#"><i class="fa fa-globe"></i> Notifications <span class="badge">5</span></a></li>
						<li><a href="#"><i class="fa fa-envelope"></i> Messages <span class="badge">1</span></a></li-->
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> @{{me.username}} <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<!--li><a href="#"><i class="fa fa-user"></i> Profile</a></li>
								<li class="divider"></li-->
								<li><a href ng-click="performSignout()"><i class="fa fa-sign-out"></i> Sign Out</a></li>
							</ul>
						</li>
					</ul>
				</div><!-- /.navbar-collapse -->

	  		</div>
  		</nav>

  		<div class="container">
  			<div class="row row-offcanvas row-offcanvas-left">
				 <aside class="sidebar sidebar-left sidebar-offcanvas">

					<!--form class="sidebar-search">
						<input type="text" placeholder="Search Symbol">
				   	</form-->
					<h3 class="sidebar-header light hidden-xs hidden-sm">Navigation</h3>
					<ul class="navigation-list list-unstyled">
						<li ng-class="{current: currentModule == module.name}" ng-repeat="module in modules">
						  <a ng-href="@{{module.url}}">
						  	<i ng-class="module.icon"></i>
							<span>@{{module.text}}</span>
						  </a>
						</li>
					</ul>
				</aside>
				<section class="content">
					<div class="pull-right" ng-show="SET">
						<alert type="SET.alertType">
							<strong>SET @{{SET.price.last_quote | number:2}}</strong>
							<i class="fa" ng-class="{'fa-arrow-circle-o-up': SET.price.change_today > 0, 'fa-arrow-circle-o-down': SET.price.change_today < 0, 'fa-arrow-circle-o-right': SET.price.change_today == 0}"></i>
							@{{SET.price.change_today * SET.price.last_quote / 100  | number:2}} 
							@{{SET.price.change_today | number:2}}%
						</alert>
					</div>
					@yield('content')
				</section>
				
				@yield('right-panel')
			</div><!-- /.row-offcanvas -->
		</div><!-- /.container -->

		@include('_layouts.scripts')
  	</body>
</html>