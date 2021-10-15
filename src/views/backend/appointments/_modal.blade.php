<div class="modal fade stick-up disable-scroll" id="appointmentDetailsModal" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog ">
        <div class="modal-content-wrapper">
            <div class="modal-content">
		      	<div class="modal-header clearfix text-left">
			        <h5 class="modal-title">Bekijk <span class="semi-bold">afspraak</span></h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          	<span aria-hidden="true">&times;</span>
			        </button>
		      	</div>
		      	<div class="modal-body">
			        <div>
			          	@if($errors->any())
			            @foreach ($errors->all() as $error)
		              	<p class="text-danger">{{ $error }}</p>
			            @endforeach
			          	@endif
			        </div>
			        <div class="row">
			        	<div class="col-sm-12">
			        		<span class="font-weight-bold d-block">
			        			<span class="cmb_confirmation_date_text">10 oktober 2021</span> om <span class="cmb_confirmation_time_text">10:00</span>
			        		</span>
							<small class="mr-2">
								<i class="fa fa-clock"></i> <span class="cmb_confirmation_duration_text">30 minuten</span>
							</small>
							<small>
								<i class="fa fa-wallet"></i> <span class="cmb_confirmation_price_text">29 EUR</span>
							</small>
			        	</div>
			        </div>
			    </div>
		    </div>
        </div>
    </div>
</div>