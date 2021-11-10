<div class="cmb_location_wrapper d-none">
	@if(count($locations) > 1)
	<div class="form-group">
		<span class="lead text-black font-weight-bold fw-bold">Waar wil je de afspraak boeken?</span>
		<select name="cmb_location" id="" class="form-control mt-2">
			@foreach($locations as $location)
			<option value="{{ $location->id }}" data-max-weight="{{ $location->max_weight }}">{{ $location->name }}</option>
			@endforeach
		</select>
	</div>
	@elseif(count($locations) == 1)
	@foreach($locations as $location)
	<input type="hidden" name="cmb_location" value="{{ $location->id }}" data-max-weight="{{ $location->max_weight }}">
	@endforeach
	@endif
</div>