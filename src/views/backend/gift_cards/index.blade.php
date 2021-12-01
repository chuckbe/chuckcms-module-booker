@extends('chuckcms::backend.layouts.base')

@section('title')
Gift Cards
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
					<li class="breadcrumb-item active" aria-current="Services">Cadeaubonnen</li>
				</ol>
			</nav>
		</div>
	</div>
	<div class="row bg-light shadow-sm rounded p-3 mb-3 mx-1">
		<div class="col-sm-12">
			<i class="fa fa-info-circle mr-r"></i> <b>Dit is momenteel nog een experimentele feature!</b>
		</div>
	</div>
	<div class="row bg-light shadow-sm rounded p-3 mb-3 mx-1">
		<div class="col-sm-12 text-right">
			<a href="#" data-target="#createGiftCardModal" data-toggle="modal" class="btn btn-sm btn-outline-success">Voeg Cadeaubon Toe</a>
		</div>
		<div class="col-sm-12 my-3">
			<div class="table-responsive">
				<table class="table" data-datatable style="width:100%">
					<thead>
						<th scope="col">ID</th>
						<th scope="col">Code</th>
						<th scope="col">Geclaimd?</th>
						<th scope="col">Klant</th>
						<th scope="col">Prijs</th>
						<th scope="col">Gewicht</th>
						<th scope="col" style="min-width:140px">Acties</th>
					</thead>
					<tbody>
						@foreach ($giftCards as $giftCard)
							<tr data-id="{{$giftCard->id}}">
								<td>{{$giftCard->id}}</td>
								<td class="semi-bold">
									{{ $giftCard->code }}
								</td>
								<td class="semi-bold">
									<span class="badge badge-{{ $giftCard->is_claimed ? 'success' : 'danger' }}">
										{!! $giftCard->is_claimed ? '✓' : '✕'!!}
									</span>
								</td>
								<td class="semi-bold">{{ $giftCard->customer->first_name.' '.$giftCard->customer->last_name }} <br> <small>{{ $giftCard->customer->email }}</small> <br> <small>{{ $giftCard->customer->tel }}</small></td>
								<td class="semi-bold">{{ $giftCard->formatted_price }}</td>
								<td class="semi-bold">{{ $giftCard->weight }}</td>
								<td>
									{{-- <a href="{{ route('dashboard.module.booker.services.edit', ['service' => $giftCard->id]) }}" class="btn btn-sm btn-outline-secondary rounded d-inline-block">
						    			<i class="fa fa-edit"></i> 
						    		</a> --}}
						    		@if($giftCard->has_invoice)
						    		<a href="{{ route('dashboard.module.booker.gift_cards.invoice', ['giftCard' => $giftCard->id]) }}" class="btn btn-sm btn-outline-secondary rounded d-inline-block" alt="Invoice">
						    			<i class="fa fa-file-pdf-o"></i> 
						    		</a>
						    		@endif
						    		@if($giftCard->has_credit_note)
						    		<a href="{{ route('dashboard.module.booker.gift_cards.credit_note', ['giftCard' => $giftCard->id]) }}" class="btn btn-sm btn-outline-secondary rounded d-inline-block" alt="Credit Note">
						    			<i class="fa fa-file"></i> 
						    		</a>
						    		@endif
						    		@if($giftCard->is_active)
									<a href="#" class="btn btn-danger btn-sm btn-rounded m-r-20 subscription_cancel" data-id="{{ $giftCard->id }}" data-price="{{ $giftCard->price }}">
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
@include('chuckcms-module-booker::backend.gift_cards._create_modal')
@endsection