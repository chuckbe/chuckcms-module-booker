@extends('chuckcms::backend.layouts.base')

@section('title')
	Locations
@endsection

@section('breadcrumbs')
	<ol class="breadcrumb">
		{{-- <li class="breadcrumb-item active"><a href="{{ route('dashboard.module.booker.locations.index') }}">Locaties</a></li> --}}
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
				{{-- <a href="#" data-target="#newLocationModal" data-toggle="modal" class="btn btn-sm btn-outline-success">Voeg Locatie Toe</a> --}}
				<a href="/dashboard/booker/locations/create" class="btn btn-sm btn-outline-success">Voeg Locatie Toe</a>
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
										<a href="{{ route('dashboard.module.booker.locations.edit', ['location' => $location]) }}"
											class="btn btn-sm btn-outline-secondary rounded d-inline-block">
							    			<i class="fa fa-edit"></i> edit
							    		</a>
										<a href="{{ route('dashboard.module.booker.locations.detail', ['location' => $location]) }}"
											class="btn btn-sm btn-outline-secondary rounded d-inline-block">
							    			<i class="fa fa-info"></i> Detail
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
@endsection

@section('css')
	<style>
	.bg-white{
		background: #fff;
	}
	</style>
@endsection

@section('scripts')
<script src="https://cdn.chuck.be/assets/plugins/sweetalert2.all.js"></script>
<script>
	$(function() {
		$('body').on('click', '.location_delete', function(event){
			event.preventDefault();
			let locationId = $(this).data('id');
			let token = '{{ Session::token() }}';
			swal({
				title: 'Are you sure?',
				text: "This will delete this location. You won't be able to revert this!",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!'
			}).then((result)=>{
				$.ajax({
					method: 'POST',
					url: "{{ route('dashboard.module.booker.locations.delete') }}",
					data: { 
						location_id: locationId, 
						_token: token
					}
				}).done(function(data){
					if(data == 'success'){
						$("tr[data-id='"+locationId+"']").first().remove();
						swal(
							'Deleted!',
							'The location has been deleted.',
							'success'
						)
					}else{
						swal(
							'Oops!',
							'Something went wrong...',
							'danger'
						)
					}
				})
			})
		});
	});
</script>
@endsection