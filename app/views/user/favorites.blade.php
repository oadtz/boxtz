@section('title')
{{'Favorites'}}
@stop

@section('content')
<script type="text/ng-template" id="stockSymbolTemplate">
  <a class="typeahead">
      <h4 bind-html-unsafe="match.model.symbol | typeaheadHighlight:query" class="hilight"></h4>
      <span bind-html-unsafe="match.model.name" class="hilight"></span>
  </a>
</script>
<div ng-controller="UserFavoritesController">
	<header class="content-header">
		<h1 class="light no-margin">Favorite Stocks</h1>
	</header>
	<form name="favoriteForm" class="form-skin noborder" ng-submit="addFavoriteCompany()">
		<div class="input-group">
	      	<input type="text" class="form-control" placeholder="Enter Stock Symbol" 
	      		ng-model="companySymbol" 
	      		typeahead="c.symbol as c.symbol for c in searchCompany($viewValue)" 
	      		typeahead-template-url="stockSymbolTemplate"
	      		typeahead-on-select="addFavoriteCompany()"
	      		ng-disabled="favoriteForm.$waiting">
	      	<span class="input-group-btn">
	        	<button class="btn btn-flat btn-blue no-radius" type="submit" ng-disabled="favoriteForm.$waiting"><i class="fa fa-plus"></i> Add to favorites</button>
	      	</span>
		</div>
	</form>
	<section class="bordered">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-left">
					<span class="input-group-btn" ng-show="favorites.length > 0">
						<button class="btn btn-flat btn-yellow no-radius" type="button" ng-click="deleteFavoriteCompanies()" ng-disabled="gridOptions.selectedItems.length == 0"><i class="fa fa-trash-o"></i> Remove selected from favorites</button>
						<button class="btn btn-flat btn-default no-radius" type="button" ng-click="loadAlertForm()"><i class="fa fa-cog"></i> Alert setting...</button>
					</span>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<div ng-show="favorites.length > 0">
				    	<div class="datagrid screeners-grid" ng-grid="gridOptions">
				    	</div>
				    </div>
					<h3 class="text-muted text-center ng-cloak" ng-cloak ng-show="favorites.length <= 0">
						You don't have any favorite stock yet.
					</h3>
				</div>
			</div>
		</div>
	</section>
	<script type="text/ng-template" id="favoriteForm">
		@include('favorite._form')
	</script>
</div>
@stop