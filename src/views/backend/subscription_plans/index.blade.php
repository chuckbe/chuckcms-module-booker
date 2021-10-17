@extends('chuckcms::backend.layouts.base')

@section('title')
Subcriptions
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
<style>
.bg-white{
	background: #fff;
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.chuck.be/assets/plugins/sweetalert2.all.js"></script>
<script>
$(function() {
	$('select').select2({
	    theme: 'bootstrap4',
	    minimumResultsForSearch: Infinity
	});

	$('body').on('click', '.service_delete', function (event) {
		event.preventDefault();

		let serviceId = $(this).data('id');
		let token = '{{ Session::token() }}';

		swal({
			title: 'Are you sure?',
			text: "This will delete this service. You won't be able to revert this!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then((result)=>{
			$.ajax({
				method: 'POST',
				url: "{{ route('dashboard.module.booker.services.delete') }}",
				data: { 
					service_id: serviceId, 
					_token: token
				}
			}).done(function(data){
				if(data == 'success'){
					$("tr[data-id='"+serviceId+"']").first().remove();
					swal(
						'Deleted!',
						'The service has been deleted.',
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
		});
	});
});
</script>
@endsection

@section('content')
<div class="container min-height p-3">
    <div class="row">
		<div class="col-sm-12">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mt-3">
					<li class="breadcrumb-item active" aria-current="Formules">Formules</li>
				</ol>
			</nav>
		</div>
	</div>
	<div class="row bg-light shadow-sm rounded p-3 mb-3 mx-1">
		<div class="col-sm-12 text-right">
			<a href="#" data-target="#createSubscriptionPlanModal" data-toggle="modal" class="btn btn-sm btn-outline-success">Voeg Formule Toe</a>
		</div>
		<div class="col-sm-12 my-3">
			<div class="table-responsive">
				<table class="table" data-datatable style="width:100%">
					<thead>
						<th scope="col">ID</th>
						<th scope="col">Naam</th>
						<th scope="col">Type</th>
						<th scope="col">Prijs</th>
						<th scope="col">Acties</th>
					</thead>
					<tbody>
						@foreach ($subscription_plans as $plan)
							<tr data-id="{{$plan->id}}">
								<td>{{$plan->id}}</td>
								<td class="semi-bold">{{ $plan->name }}</td>
								<td class="semi-bold">{{ $plan->type }}</td>
								<td class="semi-bold">{{ $plan->formatted_price }}</td>
								<td>
									<a href="{{ route('dashboard.module.booker.subscription_plans.edit', ['subscription_plan' => $plan->id]) }}" class="btn btn-sm btn-outline-secondary rounded d-inline-block">
						    			<i class="fa fa-edit"></i> edit
						    		</a>
									<a href="#" class="btn btn-danger btn-sm btn-rounded m-r-20 service_delete" data-id="{{ $plan->id }}">
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
@include('chuckcms-module-booker::backend.subscription_plans._create_modal')
@endsection