
		<form name="screenerForm" class="form-horizontal form-skin noborder" ng-submit="saveScreener()">
		    <div class="modal-header">
		    	<h3>Screener</h3>
		    	<div class="form-group" ng-class="{'has-error': errors.name}">
					<label class="col-lg-2 control-label">Name:</label>
					<div class="col-lg-10">
						<input type="text" ng-model="screener.name" ng-disabled="$waiting" name="name" class="form-control no-radius">
						<span class="help-block text-danger" ng-show="errors.name">@{{errors.name[0]}}</span></div>
				</div>
		    </div>
		    <div class="modal-body">
		    	<div class="form-group text-center" ng-class="{'has-error': errors.filters}">
		    		<label class="control-label">Filter</label>	
		    		<select name="filter" class="input-sm" ng-model="selectedFilter" ng-disabled="$waiting" ng-change="addFilter()">
		    			<optgroup label="Price">
		    				<option ng-repeat="filter in screenerFilters.price" value="price.@{{filter.name}}">@{{filter.label}}</option>
		    			</optgroup>
		    			<optgroup label="Valuation">
		    				<option ng-repeat="filter in screenerFilters.value" value="value.@{{filter.name}}">@{{filter.label}}</option>
		    			</optgroup>
		    			<optgroup label="Volume">
		    				<option ng-repeat="filter in screenerFilters.volume" value="volume.@{{filter.name}}">@{{filter.label}}</option>
		    			</optgroup>
		    			<optgroup label="Financial">
		    				<option ng-repeat="filter in screenerFilters.finance" value="finance.@{{filter.name}}">@{{filter.label}}</option>
		    			</optgroup>
		    			<optgroup label="Dividend">
		    				<option ng-repeat="filter in screenerFilters.dividend" value="dividend.@{{filter.name}}">@{{filter.label}}</option>
		    			</optgroup>
		    			<optgroup label="Growth">
		    				<option ng-repeat="filter in screenerFilters.growth" value="growth.@{{filter.name}}">@{{filter.label}}</option>
		    			</optgroup>
		    		</select>
		    		<button type="button" class="btn btn-default btn-xs" ng-click="addFilter()" ng-disabled="$waiting"><i class="fa fa-plus"></i> Add filter</button>
		    		<span class="help-block text-danger" ng-show="errors.filters">@{{errors.filters[0]}}</span></div>
		    	</div>
		    	<div class="form-group" ng-repeat="filter in screener.filters">
					<label class="col-lg-3 control-label">@{{getScreenerFilterLabel(filter.name)}}:</label>
					<div class="col-lg-9">
						<div class="input-group">
							<input type="text" ng-model="screener.filters[$index].min" ng-disabled="$waiting" name="min" class="col-lg-5 no-radius" placeholder="From">
							<input type="text" ng-model="screener.filters[$index].max" ng-disabled="$waiting" name="max" class="col-lg-5 no-radius" placeholder="To">
							<button type="button" class="btn btn-link" title="delete" ng-click="removeFilter($index)" ng-disabled="screenerForm.$waiting"><i class="fa fa-trash-o"></i></button>
						</div>
					</div>
		    	</div>
		    </div>
		    <div class="modal-footer">
		        <button class="btn btn-blue btn-flat no-radius" type="submit" ng-disabled="screenerForm.$waiting"><i class="fa fa-save"></i> Save</button>
		        <button class="btn btn-default btn-flat no-radius" type="button" ng-click="modal.dismiss('cancel')" ng-disabled="screenerForm.$waiting">Cancel</button>
		    </div>
		</form>