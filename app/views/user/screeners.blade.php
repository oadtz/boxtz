@section('title')
{{'Screeners'}}
@stop

@section('content')
<div ng-controller="UserScreenersController">
	<header class="content-header">
		<h1 class="light no-margin" ng-hide="selectedScreener">Stock Screeners</h1>
		<h1 class="light no-margin dropdown" ng-show="selectedScreener">
			<a href class="dropdown-toggle" style="text-decoration: none"><i class="fa fa-caret-down"></i> @{{selectedScreener.name}}</a>
			<ul class="dropdown-menu">
				<li ng-repeat="s in screeners | orderBy : 'name'">
					<a href ng-click="selectScreener(s)">@{{s.name}}</a>
				</li>
			</ul>
		</h1>
	</header>
	<form name="screenerForm" class="form-skin noborder">
      	<div class="input-group-btn">
        	<button class="btn btn-flat btn-blue no-radius" type="button" ng-click="loadNewScreenerForm()"><i class="fa fa-plus"></i> Create new screener...</button>
      		<button class="btn btn-flat btn-default no-radius" type="button" ng-show="selectedScreener" ng-click="loadEditScreenerForm()"><i class="fa fa-pencil"></i> Edit</button>
      		<button class="btn btn-flat btn-default no-radius" type="button" ng-show="selectedScreener" ng-click="deleteScreener()"><i class="fa fa-trash-o"></i> Delete</button>
      	</div>
  	</form>
	<section class="bordered">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 text-left">
					<span class="input-group-btn">
						<button class="btn btn-flat btn-yellow no-radius" type="button" ng-show="selectedScreener" ng-click="addFavoriteCompanies()" ng-disabled="gridOptions.selectedItems.length == 0"><i class="fa fa-star-o"></i> Add selected to favorites</button>
					</span>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<div ng-show="screeners.length > 0">
				        <div class="datagrid screeners-grid" ng-grid="gridOptions">
				        </div>
					</div>
					<h3 class="text-muted text-center ng-cloak" ng-cloak ng-show="screeners.length <= 0">
						You don't have any screener yet.
					</h3>
				</div>
			</div>
		</div>
	</section>
	<script type="text/ng-template" id="screenerForm">
		@include('screener._form')
	</script>
</div>
@stop