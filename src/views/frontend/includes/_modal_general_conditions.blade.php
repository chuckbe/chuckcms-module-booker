<div class="modal fade stick-up disable-scroll" id="generalConditionsModal" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
	      	<div class="modal-header clearfix text-left">
		        <h5 class="modal-title">Algemene Voorwaarden</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          	<span aria-hidden="true">&times;</span>
		        </button>
	      	</div>
	      	<div class="modal-body">
		        <div class="row">
		        	<style>
		        	.cmb_general_conditions_wrapper h5 { font-size: 1rem; }
		        	</style>
		        	<div class="col-sm-12 cmb_general_conditions_wrapper" style="font-size: 80%">
		        		{!! ChuckModuleBooker::getSetting('general.conditions') !!}
		        	</div>
		        </div>
		    </div>
		    <div class="modal-footer">
		    	<div class="row">
		    		<div class="col-sm-12">
		    			<button class="btn btn-sm btn-success cmb_general_conditions_modal_btn"><i class="fas fa-check"></i> Ik ga akkoord </button>
		    		</div>
		    	</div>
		    </div>
	    </div>
    </div>
</div>