<span class="lead text-black font-weight-bold">Waar wil je de afspraak boeken?</span>
<select name="cmb_location" id="" class="form-control mt-2">
	@foreach($locations as $location)
	<option value="{{ $location->id }}">{{ $location->name }}</option>
	@endforeach
</select>