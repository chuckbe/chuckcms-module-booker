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
	    minimumResultsForSearch: 3
	});

	$('body').on('change', '#cmb_paid', function (event) {
        event.preventDefault();

        if ($(this).is(':checked')) {
            $('input[name="needs_payment"]').prop('checked', false).prop('disabled', true);
        } else {
            $('input[name="needs_payment"]').prop('checked', false).prop('disabled', false);
        }
    });

    $('body').on('change', '#cmb_needs_payment', function (event) {
        event.preventDefault();

        if ($(this).is(':checked')) {
            $('input[name="paid"]').prop('checked', false).prop('disabled', true);
        } else {
            $('input[name="paid"]').prop('checked', false).prop('disabled', false);
        }
    });

    $('body').on('change', '#create_subscription_subscription_plan', function (event) {
    	event.preventDefault();

    	if ($(this).find('option:selected:not(:disabled)').length) {
    		let option = $(this).find('option:selected:not(:disabled)').first();
    		let price = option.data('price');
    		let weight = option.data('weight');

    		$('input[name="price"]').val(price);
    		$('input[name="weight"]').val(weight);
    		$('input[name="usage"]').val(0);
    	}
    });

	$('body').on('click', '.subscription_cancel', function (event) {
		event.preventDefault();

		let subscriptionId = $(this).data('id');
		let subscriptionPrice = $(this).data('price');
		$('input[name="subscription_id"]').val(subscriptionId);
		$('input[name="credit"]').val(subscriptionPrice);

		$('#cancelSubscriptionModal').modal('show');

		return;
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
									<a href="{{ route('dashboard.module.booker.subscriptions.edit', ['subscription' => $subscription->id]) }}" class="btn btn-sm btn-outline-secondary rounded d-inline-block">
						    			<i class="fa fa-edit"></i> 
						    		</a>
						    		@if($subscription->has_invoice)
						    		<a href="{{ route('dashboard.module.booker.subscriptions.invoice', ['subscription' => $subscription->id]) }}" class="btn btn-sm btn-outline-secondary rounded d-inline-block" alt="Invoice">
						    			<i class="fa fa-file-pdf-o"></i> 
						    		</a>
						    		@endif
						    		@if($subscription->has_credit_note)
						    		<a href="{{ route('dashboard.module.booker.subscriptions.credit_note', ['subscription' => $subscription->id]) }}" class="btn btn-sm btn-outline-secondary rounded d-inline-block" alt="Credit Note">
						    			<i class="fa fa-file"></i> 
						    		</a>
						    		@endif
						    		@if($subscription->is_active)
									<a href="#" class="btn btn-danger btn-sm btn-rounded m-r-20 subscription_cancel" data-id="{{ $subscription->id }}" data-price="{{ $subscription->price }}">
						    			<i class="fa fa-times"></i> 
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
@include('chuckcms-module-booker::backend.subscriptions._create_modal')
@include('chuckcms-module-booker::backend.subscriptions._cancel_modal')
@endsection