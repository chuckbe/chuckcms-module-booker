@extends('chuckcms::backend.layouts.base')

@section('title')
	Customers
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('module-booker/css/intlTelInput.min.css') }}" />
<style>
.bg-white{
	background: #fff;
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.chuck.be/assets/plugins/sweetalert2.all.js"></script>
<script src="{{ asset('module-booker/scripts/intlTelInput-jquery.min.js') }}"></script>
<script>
$(function() {
	$('body').on('click', '.customer_delete', function (event) {
		event.preventDefault();

		let customerId = $(this).data('id');
		let token = '{{ Session::token() }}';

		swal({
			title: 'Are you sure?',
			text: "This will delete this client. You won't be able to revert this!",
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
					customer_id: customerId, 
					_token: token
				}
			}).done(function(data){
				if(data.status == 'success'){
					$("tr[data-id='"+customerId+"']").first().remove();
					swal(
						'Deleted!',
						'The client has been deleted.',
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

	$('body').on('click', '.customer_refresh', function (event) {
		event.preventDefault();

		let customerId = $(this).data('id');
		let token = '{{ Session::token() }}';

		swal({
			title: 'Ben je zeker?',
			text: "Dit zal het huidige wachtwoord van de gebruiken resetten en zal opnieuw de account bevestigingsemail verzenden.",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ja, resetten en verzenden!'
		}).then((result)=>{
			$.ajax({
				method: 'POST',
				url: "{{ route('dashboard.module.booker.customers.reactivate') }}",
				data: { 
					customer_id: customerId, 
					_token: token
				}
			}).done(function(data){
				if(data.status == 'success'){
					swal(
						'Gelukt!',
						'Het wachtwoord van de klant werd gereset en er werd een nieuwe bevestigingsemail verzonden.',
						'success'
					)
				}else{
					swal(
						'Oops!',
						'Er is iets misgegaan...',
						'danger'
					)
				}
			})
		});
	});

});
$('input[type="tel"]').intlTelInput({
	initialCountry: "be",
	onlyCountries: ["be", "lu", "nl"],
	utilsScript: "{{ asset('module-booker/scripts/intlTelInput-utils.js') }}"
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
						<th scope="col">Account?</th>
						<th scope="col">Actief?</th>
						<th scope="col">B2B?</th>
						<th scope="col">Acties</th>
					</thead>
					<tbody>
						@foreach ($customers as $customer)
							<tr data-id="{{$customer->id}}">
								<td>{{$customer->id}}</td>
								<td class="semi-bold">
									{{ $customer->first_name . ' ' . $customer->last_name }} <br> 
									<small>{{ $customer->email }} <br>{{ $customer->tel }}</small>
								</td>
								<td class="semi-bold">
									<span class="badge badge-{{ !is_null($customer->user_id) ? 'success' : 'danger' }}">
										{!! !is_null($customer->user_id) ? '✓' : '✕'!!}
									</span>
								</td>
								<td class="semi-bold">
									<span class="badge badge-{{ !is_null($customer->user_id) && $customer->user->active ? 'success' : 'danger' }}">
										{!! !is_null($customer->user_id) && $customer->user->active ? '✓' : '✕'!!}
									</span>
								</td>
								<td class="semi-bold">
									<span class="badge badge-{{ $customer->hasCompany() ? 'success' : 'danger' }}">
										{!! $customer->hasCompany() ? '✓' : '✕'!!}
									</span>
								</td>
								<td>
									<a href="{{ route('dashboard.module.booker.customers.detail', ['customer' => $customer->id]) }}" class="btn btn-sm btn-outline-secondary rounded d-inline-block">
						    			<i class="fa fa-search"></i> 
						    		</a>
						    		@if(!is_null($customer->user_id))
									<a href="#" class="btn btn-secondary btn-sm btn-rounded m-r-20 customer_refresh" data-id="{{ $customer->id }}">
						    			<i class="fa fa-refresh"></i> 
						    		</a>
						    		@endif
						    		
						    		@if($customer->is_deletable)
									<a href="#" class="btn btn-danger btn-sm btn-rounded m-r-20 customer_delete" data-id="{{ $customer->id }}">
						    			<i class="fa fa-trash"></i> 
						    		</a>
						    		@endif
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