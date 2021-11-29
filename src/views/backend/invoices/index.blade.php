@extends('chuckcms::backend.layouts.base')

@section('title')
Invoices
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
@endsection

@section('content')
<div class="container min-height p-3">
    <div class="row">
		<div class="col-sm-12">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mt-3">
					<li class="breadcrumb-item active" aria-current="Invoices">Facturen</li>
				</ol>
			</nav>
		</div>
	</div>
	<div class="row bg-light shadow-sm rounded p-3 mb-3 mx-1">
		<div class="col-sm-12 my-3">
			<div class="table-responsive">
				<table class="table" data-datatable style="width:100%">
					<thead>
						<th scope="col">ID</th>
						<th scope="col">Naam</th>
						<th scope="col">Type</th>
						<th scope="col">Datum</th>
						<th scope="col">Bedrag</th>
						<th scope="col">Acties</th>
					</thead>
					<tbody>
						@foreach ($invoices as $invoice)
							<tr data-id="{{$invoice->id}}">
								<td>{{$invoice->number}}</td>
								<td class="semi-bold">{{ $invoice->name }}</td>
								<td class="semi-bold">{{ $invoice->type == 'appointment' ? 'Afspraak' : ($invoice->type == 'subscription' ? 'Abonnement' : 'Cadeaubon') }}</td>
								<td class="semi-bold">{{ $invoice->object->created_at->format('d/m/Y') }}</td>
								<td class="semi-bold">{{ $invoice->object->price }}</td>
								<td>
									@if($invoice->type == 'appointment')
									<a href="{{ route('dashboard.module.booker.appointments.invoice', ['appointment' => $invoice->object->id]) }}" class="btn btn-sm btn-outline-secondary rounded d-inline-block" alt="Invoice">
						    			<i class="fa fa-file-pdf-o"></i> 
						    		</a>
									@else
									<a href="{{ route('dashboard.module.booker.subscriptions.invoice', ['subscription' => $invoice->object->id]) }}" class="btn btn-sm btn-outline-secondary rounded d-inline-block" alt="Invoice">
						    			<i class="fa fa-file-pdf-o"></i> 
						    		</a>
									@endif

									@if($invoice->object->has_credit_note)
									@if($invoice->type == 'subscription')
									<a href="{{ route('dashboard.module.booker.subscriptions.credit_note', ['subscription' => $invoice->object->id]) }}" class="btn btn-sm btn-outline-secondary rounded d-inline-block" alt="Credit Note">
						    			<i class="fa fa-file"></i> 
						    		</a>
						    		@endif
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
@endsection