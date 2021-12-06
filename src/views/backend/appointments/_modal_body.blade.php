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
    			@php
    			setLocale(LC_ALL, 'nl_NL');
                \Carbon\Carbon::setLocale('nl_NL');
    			@endphp
    			<span class="cmb_confirmation_date_text">
    				{{ $appointment->start->locale('nl_NL')->format('d F Y')}}
    			</span> om 
    			<span class="cmb_confirmation_time_text">
    				{{ $appointment->time}} 
    				<small>
    					<span class="badge badge-{{ ChuckModuleBooker::getSetting('appointment.statuses.'.$appointment->status.'.paid') ? 'success' : 'danger' }}">
    						{{ ChuckModuleBooker::getSetting('appointment.statuses.'.$appointment->status.'.short.'.config('app.locale')) }}
    					</span>
    				</small>
                    @if($appointment->status == 'confirmed' && !$appointment->has_invoice)
                    <small>
                        <span class="badge badge-danger">
                            Betaling nodig!
                        </span>
                    </small>
                    @endif
    			</span>
    		</span>
			<small class="mr-2">
				<i class="fa fa-clock"></i> <span class="cmb_confirmation_duration_text">{{ $appointment->duration }} minuten</span>
			</small>
			<small>
				@if($appointment->is_free_session)
				<i class="fa fa-wallet"></i> <span class="cmb_confirmation_price_text"><span style="text-decoration: line-through;">{{ $appointment->services->sum('price') }} EUR</span> (Gratis Sessie)</span>
				@else
				<i class="fa fa-wallet"></i> <span class="cmb_confirmation_price_text">{{ $appointment->price }} EUR</span>
				@endif
			</small>
    	</div>
    	<div class="col-sm-12 mt-2">
    		<small>
	    		<i class="fa fa-user"></i> <span><b>{{ $appointment->customer->first_name.' '.$appointment->customer->last_name}}</b> <small>{{ is_null($appointment->customer->user_id) ? '(geen account)' : ($appointment->customer->user->active ? '(heeft account — actief)' : '(heeft account — non-actief)')}}</small></span>
    		</small>
    		<div class="d-block w-100"></div>
    		<small class="mr-2">
	    		<i class="fa fa-envelope-open-text"></i> <span>{{ $appointment->customer->email }}</span>
    		</small>
    		<small>
	    		<i class="fa fa-phone-alt"></i> <span>{{ $appointment->customer->tel }}</span>
    		</small>
    	</div>
        @if($appointment->status == 'confirmed' && !$appointment->is_free_session && !$appointment->is_subscription_session && !$appointment->has_invoice)
        <hr>
        <div class="col-sm-12 mt-2">
            <span class="d-block lead text-black font-weight-bold fw-bold">Betaling</span>
        </div>
        <div class="editAppointmentModalPayment eAMP col-sm-12 mt-1">
            <label class="mt-0 mb-0" for="cmb_detail_is_free_session">
                <small>
                    <input type="checkbox" class="mr-2 me-2" name="is_free_session" id="cmb_detail_is_free_session"> Gratis sessie?
                </small>
            </label>
            <div class="w-100 d-block"></div>
            <label class="mt-0 mb-0" for="cmb_detail_paid">
                <small>
                    <input type="checkbox" class="mr-2 me-2" name="paid" id="cmb_detail_paid"> Afspraak is ter plaatse betaald.
                </small>
            </label>
            <div class="w-100 d-block"></div>
            <label class="mt-0 mb-0" for="cmb_detail_qr_code">
                <small>
                    <input type="checkbox" class="mr-2 me-2" name="qr_code" id="cmb_detail_qr_code"> Toon Bancontact / Payconiq QR code.
                </small>
            </label>
        </div>
        <div class="eAMP col-sm-12 mt-2">
            <div class="form-group">
                <p class="font-size-small text-danger font-weight-bold fw-bold cmb_payment_detail_error_msg"></p>
            </div>
            <input type="hidden" name="appointment_payment_id" value="{{ $appointment->id }}">
            <button class="btn btn-sm btn-secondary" id="editAppointmentModalPaymentBtn">Update betalingstatus</button>
        </div>
        <div class="cmb_detail_qr_code d-none">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <img src="" width="180" height="180" class="cmb_detail_qr_code_img mx-auto m-5">
                </div>
                <div class="col-sm-12 text-center">
                    <span class="badge badge-light cmb_detail_qr_code_status">
                        <i class="fas fa-spinner fa-pulse"></i> Status checken...
                    </span>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
<div class="modal-footer">
	<div class="row">
		<div class="col-sm-12">
			@if($appointment->status !== 'canceled' && $appointment->status !== 'error')
			<button class="btn btn-sm btn-danger" id="editAppointmentModalCancelAppBtn" data-event-id="{{ $appointment->id }}">Annuleer afspraak</button>
			@endif
		</div>
	</div>
</div>