<div class="modal fade stick-up disable-scroll" id="medicalDeclarationModal" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
	      	<div class="modal-header clearfix text-left test-start">
		        <h5 class="modal-title">Medische Verklaring</h5>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      	</div>
	      	<div class="modal-body">
		        <div class="row">
		        	<div class="col-sm-12">
		        		{!! ChuckModuleBooker::getSetting('general.medical_declaration') !!}
		        	</div>
		        </div>
		    </div>
		    <div class="modal-footer">
		    	<div class="row">
		    		<div class="col-sm-12">
		    			<button class="btn btn-sm btn-success cmb_medical_declaration_modal_btn"><i class="fas fa-check"></i> Ik ga akkoord </button>
		    		</div>
		    	</div>
		    </div>
	    </div>
    </div>
</div>