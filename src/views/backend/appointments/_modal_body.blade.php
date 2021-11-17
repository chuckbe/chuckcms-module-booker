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
    </div>
</div>
<div class="modal-footer">
	<div class="row">
		<div class="col-sm-12">
			@if($appointment->status !== 'canceled' && $appointment->status !== 'error')
			<button class="btn btn-sm btn-danger">Annuleer afspraak</button>
			@endif
		</div>
	</div>
</div>