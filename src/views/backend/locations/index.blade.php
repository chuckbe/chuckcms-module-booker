@extends('chuckcms::backend.layouts.base')

@section('title')
	Locations
@endsection

@section('breadcrumbs')
	<ol class="breadcrumb">
		<li class="breadcrumb-item active"><a href="{{ route('dashboard.module.booker.locations') }}">Locaties</a></li>
	</ol>
@endsection

@section('content')
    <div class="container min-height p-3">
        <div class="row">
			<div class="col-sm-12">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb mt-3">
						<li class="breadcrumb-item active" aria-current="Locaties">Locaties</li>
					</ol>
				</nav>
			</div>
		</div>
		<div class="row bg-light shadow-sm rounded p-3 mb-3 mx-1">
			<div class="tools">
				<a class="collapse" href="javascript:;"></a>
				<a class="config" data-toggle="modal" href="#grid-config"></a>
				<a class="reload" href="javascript:;"></a>
				<a class="remove" href="javascript:;"></a>
			</div>
			<div class="col-sm-12 text-right">
				<a href="#" data-target="#newLocationModal" data-toggle="modal" class="btn btn-sm btn-outline-success">Voeg Locatie Toe</a>
			</div>
			<div class="col-sm-12 my-3">
				<div class="table-responsive">
					<table class="table" data-datatable style="width:100%">
						<thead>
							<th scope="col">ID</th>
							<th scope="col">Naam</th>
							<th scope="col">Acties</th>
						</thead>
						<tbody>
							@foreach ($locations as $location)
								<tr data-id="{{$location->id}}">
									<td>{{$location->id}}</td>
									<td class="semi-bold">{{ $location->name }}</td>
									<td>
										<a 
											{{-- href="{{ route('dashboard.module.booker.locations.edit', ['location' => $location->id]) }}"  --}}
											class="btn btn-sm btn-outline-secondary rounded d-inline-block">
							    			<i class="fa fa-edit"></i> edit
							    		</a>
										<a href="#" class="btn btn-danger btn-sm btn-rounded m-r-20 location_delete" data-id="{{ $location->id }}">
							    			<i class="fa fa-trash"></i> 
							    		</a>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
    </div>
	@include('chuckcms-module-booker::backend.locations._modal')
@endsection

@section('css')
	<style>
	.bg-white{
		background: #fff;
	}
	</style>
@endsection

@section('scripts')
{{--  <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">

var geocoder = new google.maps.Geocoder();
var address = 'Berlaarsestraat 10, 2500 Lier';

geocoder.geocode( { 'address': address}, function(results, status) {

if (status == google.maps.GeocoderStatus.OK) {
    var latitude = results[0].geometry.location.lat();
    var longitude = results[0].geometry.location.lng();
    	console.log(latitude+', '+longitude);
    } 
}); 
</script>  --}}
@endsection