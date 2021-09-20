@extends('chuckcms::backend.layouts.base')

@section('title')
Locaties
@endsection

@section('add_record')
@can('create redirects')
<a href="#" data-target="#createLocationModal" data-toggle="modal" class="btn btn-link text-primary m-l-20 hidden-md-down">Voeg Nieuwe Locatie Toe</a>
@endcan
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ URL::to('vendor/laravel-filemanager/js/lfm.js') }}"></script>
<script>
$( document ).ready(function() { 
	
	$('select').select2({
	    theme: 'bootstrap4',
	    minimumResultsForSearch: Infinity
	});

	$('body').on('change', '#create_location_disabled_weekdays', function (event) {
		$('.openingshoursRangeInputSection').removeClass('d-none');
		$('.openingshoursRangeInputSection')
								.find('input')
								.prop('required', true)
								.prop('disabled', false);

		let selectedValues = $(this).find(':selected');

		for (var i = selectedValues.length - 1; i >= 0; i--) {
			$('.openingshoursRangeInputSection[data-day="'+selectedValues[i].value+'"]').addClass('d-none');
			$('.openingshoursRangeInputSection[data-day="'+selectedValues[i].value+'"]')
								.find('input')
								.prop('required', false)
								.prop('disabled', true);
		};
	});

	$('body').on('click', '.addOpeningshoursRangeInputRowBtn', function (event) {
		event.preventDefault();

		let dayOfTheWeek = $(this).attr('data-day');
		
		firstRangeInputRow = $('.openingshoursRangeInputWrapper[data-day="'+dayOfTheWeek+'"]')
								.find('.openingshoursRangeInputRow')
								.first();

		firstRangeInputRow.clone().appendTo('.openingshoursRangeInputWrapper[data-day="'+dayOfTheWeek+'"]');

		$('.openingshoursRangeInputWrapper[data-day="'+dayOfTheWeek+'"]')
								.find('.removeOpeningshoursRangeInputRowBtn')
								.removeClass('d-none');
	});

	$('body').on('click', '.removeOpeningshoursRangeInputRowBtn', function (event) {
		event.preventDefault();

		rangeInputWrapper = $(this).parents('.openingshoursRangeInputWrapper');

		if (rangeInputWrapper.find('.openingshoursRangeInputRow').length == 1) return;

		$(this).parents('.openingshoursRangeInputRow').remove();

		if (rangeInputWrapper.find('.openingshoursRangeInputRow').length == 1) {
			rangeInputWrapper.find('.removeOpeningshoursRangeInputRowBtn')
								.addClass('d-none');
		}
	});


	init();
	function init () {
		//init media manager inputs 
		var domain = "{{ URL::to('dashboard/media')}}";
		$('.img_lfm_link').filemanager('image', {prefix: domain});
	}
});

function deleteModal(id, name){
	$('#delete_location_id').val(id);
	$('#delete_location_name').text(name);
	$('#deleteLocationModal').modal('show');
}
</script>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-3">
                    <li class="breadcrumb-item active" aria-current="page">Locaties</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row bg-light shadow-sm rounded py-3 mb-3 mx-1">
    	<div class="col-sm-12 text-right">
    		<a href="#" data-target="#createLocationModal" data-toggle="modal" class="btn btn-sm btn-outline-success">Nieuwe Locatie</a>
    	</div>
        <div class="col-sm-12 my-3">
        	<div class="table-responsive">
        		<table class="table" data-datatable style="width:100%">
        			<thead>
        				<tr>
        					<th scope="col">#</th>
							<th scope="col">Naam</th>
							<th scope="col">Gesloten op</th>
							<th scope="col">Diensten</th>
							<th scope="col">Volgorde</th>
							<th scope="col" style="min-width:190px">Actions</th>
        				</tr>
        			</thead>
        			<tbody>
        				@foreach($locations as $location)
						<tr>
							<td class="v-align-middle">{{ $location->id }}</td>
							<td class="v-align-middle">{{$location->name }}</td>
							<td class="v-align-middle">
								@if(is_array($location->disabled_weekdays))
								<ul>
								@foreach($location->disabled_weekdays as $disabled_weekday)
								<li>{{ config('chuckcms-module-booker.locations.weekdays.'.$disabled_weekday) }}</li>
								@endforeach
								</ul>
								@else
								/
								@endif
							</td>
							<td class="v-align-middle">
								@if(count($location->services) > 0)
								<ul>
								@foreach($location->services as $service)
								<li>{{ $service->name }}{{ $service->isFree() ? '' : ' ('.$service->formatted_price.')' }}</li>
								@endforeach
								</ul>
								@else
								/
								@endif
							</td>
							<td class="v-align-middle">{{$location->order }}</td>
							<td class="v-align-middle semi-bold">
								@can('edit redirects')
								<a href="{{ route('dashboard.module.booker.locations.edit', ['location' => $location->id]) }}" class="btn btn-outline-secondary btn-sm btn-rounded m-r-20">
									<i data-feather="edit-2"></i> edit
								</a>
								@endcan

								@can('delete redirects')
								<a href="#" onclick="deleteModal({{ $location->id }}, '{{ $location->name }}')" class="btn btn-danger btn-sm btn-rounded m-r-20">
									<i data-feather="trash"></i> delete
								</a>
								@endcan
							</td>
						</tr>
						@endforeach
        			</tbody>
        		</table>
        	</div>
        </div>
    </div>
</div>
@include('chuckcms-module-booker::backend.locations._create_modal')
@include('chuckcms-module-booker::backend.locations._delete_modal')
@endsection