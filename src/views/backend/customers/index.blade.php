@extends('chuckcms::backend.layouts.base')

@section('title')
	Customers
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
	$('body').on('click', '.customer_delete', function (event) {
		event.preventDefault();

		let customerId = $(this).data('id');
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
				url: "{{ route('dashboard.module.booker.customers.delete') }}",
				data: { 
					service_id: customerId, 
					_token: token
				}
			}).done(function(data){
				if(data == 'success'){
					$("tr[data-id='"+customerId+"']").first().remove();
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
					<li class="breadcrumb-item active" aria-current="Services">Klanten</li>
				</ol>
			</nav>
		</div>
	</div>
	<div class="row bg-light shadow-sm rounded p-3 mb-3 mx-1">
		<div class="col-sm-12 text-right">
			<a href="#" data-target="#createCustomerModal" data-toggle="modal" class="btn btn-sm btn-outline-success">Voeg Klant Toe</a>
		</div>
		<div class="col-sm-12 my-3">
			<div class="table-responsive">
				<table class="table" data-datatable style="width:100%">
					<thead>
						<th scope="col">ID</th>
						<th scope="col">Naam</th>
						<th scope="col">Prijs</th>
						<th scope="col">Voorschot</th>
						<th scope="col">Acties</th>
					</thead>
					<tbody>
						@foreach ($customers as $customer)
							<tr data-id="{{$customer->id}}">
								<td>{{$customer->id}}</td>
								<td class="semi-bold">{{ $customer->name }}</td>
								<td class="semi-bold"></td>
								<td class="semi-bold"></td>
								<td>
									<a href="{{ route('dashboard.module.booker.customers.edit', ['customer' => $customer->id]) }}" class="btn btn-sm btn-outline-secondary rounded d-inline-block">
						    			<i class="fa fa-edit"></i> edit
						    		</a>
									<a href="#" class="btn btn-danger btn-sm btn-rounded m-r-20 customer_delete" data-id="{{ $customer->id }}">
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
@include('chuckcms-module-booker::backend.customers._create_modal')
@endsection