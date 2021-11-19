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
					<li class="breadcrumb-item active" aria-current="Services">Abonnementen</li>
				</ol>
			</nav>
		</div>
	</div>
	<div class="row bg-light shadow-sm rounded p-3 mb-3 mx-1">
		<div class="col-sm-12 text-right">
			<a href="#" data-target="#createSubscriptionModal" data-toggle="modal" class="btn btn-sm btn-outline-success">Voeg Abonnement Toe</a>
		</div>
		<div class="col-sm-12 my-3">
			<div class="table-responsive">
				<table class="table" data-datatable style="width:100%">
					<thead>
						<th scope="col">ID</th>
						<th scope="col">Naam</th>
						<th scope="col">Vervalt</th>
						<th scope="col">Hernieuwen?</th>
						<th scope="col">Klant</th>
						<th scope="col">Prijs</th>
						<th scope="col">Actief?</th>
						<th scope="col">Resterend?</th>
						<th scope="col" style="min-width:140px">Acties</th>
					</thead>
					<tbody>
						@foreach ($subscriptions as $subscription)
							<tr data-id="{{$subscription->id}}">
								<td>{{$subscription->id}}</td>
								<td class="semi-bold">
									{{ $subscription->subscription_plan->name }}
									@if($subscription->type !== 'one-off')
									<br>
									@if($subscription->type == 'weekly')
									<small>Wekelijks</small>
									@elseif($subscription->type == 'monthly')
									<small>Maandelijks</small>
									@elseif($subscription->type == 'quarterly')
									<small>Driemaandelijks</small>
									@elseif($subscription->type == 'yearly')
									<small>Jaarlijks</small>
									@endif
									@endif
								</td>
								<td class="semi-bold">
									{{ $subscription->expires_at->format('d/m/Y H:i')}}
								</td>
								<td class="semi-bold">
									<span class="badge badge-{{ $subscription->will_renew ? 'success' : 'danger' }}">
										{!! $subscription->will_renew ? '✓' : '✕'!!}
									</span>
								</td>
								<td class="semi-bold">{{ $subscription->customer->first_name.' '.$subscription->customer->last_name }} <br> <small>{{ $subscription->customer->email }}</small> <br> <small>{{ $subscription->customer->tel }}</small></td>
								<td class="semi-bold">{{ $subscription->formatted_price }}</td>
								<td class="semi-bold">
									<span class="badge badge-{{ $subscription->is_active ? 'success' : 'danger' }}">
										{!! $subscription->is_active ? '✓' : '✕'!!}
									</span>
								</td>
								<td class="semi-bold">{{ $subscription->available_weight }}</td>
								<td>
									{{-- <a href="{{ route('dashboard.module.booker.services.edit', ['service' => $subscription->id]) }}" class="btn btn-sm btn-outline-secondary rounded d-inline-block">
						    			<i class="fa fa-edit"></i> 
						    		</a> --}}
						    		@if($subscription->has_invoice)
						    		<a href="{{ route('dashboard.module.booker.subscriptions.invoice', ['subscription' => $subscription->id]) }}" class="btn btn-sm btn-outline-secondary rounded d-inline-block">
						    			<i class="fa fa-file-pdf-o"></i> 
						    		</a>
						    		@endif
									{{-- <a href="#" class="btn btn-danger btn-sm btn-rounded m-r-20 service_delete" data-id="{{ $subscription->id }}">
						    			<i class="fa fa-trash"></i> 
						    		</a> --}}
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@include('chuckcms-module-booker::backend.subscriptions._create_modal')
@endsection