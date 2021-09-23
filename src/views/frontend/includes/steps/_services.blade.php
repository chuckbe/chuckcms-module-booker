<div class="cmb_services_wrapper">
	<div class="form-group">
		<span class="lead text-black font-weight-bold">Waarvoor wil je een afspraak boeken?</span>
		<select name="cmb_services" id="" class="form-control">
			<option value="" disabled selected>Maak een keuze</option>
			@foreach($services as $service)
			<option value="{{ $service->id }}">{{ $service->name }}</option>
			@endforeach
		</select>
		<small class="cmb_services_loading_message form-text text-muted text-right d-none"><i class="fas fa-spinner fa-pulse"></i> Data ophalen...</small>
	</div>
</div>