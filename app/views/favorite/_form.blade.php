
		<form name="alertForm" class="form-horizontal form-skin noborder" ng-submit="saveAlert()">
		    <div class="modal-header">
		    	<h3>Alert Setting</h3>
		    </div>
		    <div class="modal-body">
		    	<div class="form-group">
					<label class="col-lg-5 control-label">Price up more than(%):</label>
					<div class="col-lg-5">
						<div class="input-group">
							<input type="text" ng-model="alert.ceiling" ng-disabled="$waiting" name="ceiling" class="col-lg-12 no-radius" placeholder="">
						</div>
					</div>
		    	</div>
		    	<div class="form-group">
					<label class="col-lg-5 control-label">Price down below than(%):</label>
					<div class="col-lg-5">
						<div class="input-group">
							<input type="text" ng-model="alert.floor" ng-disabled="$waiting" name="floor" class="col-lg-12 no-radius" placeholder="">
						</div>
					</div>
		    	</div>
		    </div>
		    <div class="modal-footer">
		        <button class="btn btn-blue btn-flat no-radius" type="submit" ng-disabled="favorite.$waiting"><i class="fa fa-save"></i> Save</button>
		        <button class="btn btn-default btn-flat no-radius" type="button" ng-click="modal.dismiss('cancel')" ng-disabled="favorite.$waiting">Cancel</button>
		    </div>
		</form>