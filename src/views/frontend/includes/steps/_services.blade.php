<div class="cmb_services_wrapper">
	<div class="row checkbox-type">
		<div class="col-12 mb-2">
			<span class="lead text-black font-weight-bold fw-bold">Waarvoor wil je een afspraak boeken?</span>
		</div>
		@foreach($services as $service)
		<div class="col-md-12{{ !$loop->last ? ' mb-2' : '' }}">
			<div class="py-2 px-3 border rounded row no-gutters g-0">
				<div class="form-check col-md-11">
				  <label class="form-check-label row" for="service_check{{ $service->id }}">
				    <input type="checkbox" name="cmb_services" value="{{ $service->id }}" id="service_check{{ $service->id }}" data-name="{{ $service->name }}" data-duration="{{ $service->duration }}" data-weight="{{ $service->weight }}" data-price="{{ $service->price }}" data-description="Lorem ipsum dolor sit amet, consectetur adipisicing elit.">
				    <span class="col-md-9 font-weight-bold fw-bold"><span class="cmb_services_name">{{ $service->name }}</span> <small class="cmb_services_duration">({{ $service->duration }} min)</small></span>
				    <span class="font-weight-bold fw-bold col-md-3 text-right text-end">{{ '€ '.number_format($service->price, 2, ',', '.') }}</span>
				  </label>
				</div>
				<span class="col-md-1 text-right text-end collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#service_description{{ $service->id }}" aria-expanded="false" aria-controls="service_description{{ $service->id }}"><i class="fa fa-chevron-down"></i></span>
			    <div class="collapse col-md-12" id="service_description{{ $service->id }}">
			    	<p class="mb-0 pb-2 pt-2 description">Mollit tempor veniam ut fugiat amet deserunt do sunt labore sed tempor anim esse incididunt.</p>
				</div>
			</div>
		</div>
		@endforeach
		<div class="col-12 mb-2">
			<small class="cmb_services_loading_message form-text text-muted text-right text-end d-none"><i class="fas fa-spinner fa-pulse"></i> Data ophalen...</small>
			<small class="cmb_services_loading_error_message form-text text-muted text-danger text-right text-end d-none"><i class="fas fa-exclamation-circle"></i> Er is iets misgegaan, probeer het nog eens opnieuw...</small>
		</div>
	</div>
</div>