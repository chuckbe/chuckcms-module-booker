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
						<th scope="col">Datum</th>
						<th scope="col">Bedrag</th>
						<th scope="col">Acties</th>
					</thead>
					<tbody>
						@foreach ($invoices as $invoice)
							<tr data-id="{{$invoice->id}}">
								<td>{{$invoice->id}}</td>
								<td class="semi-bold">{{ $invoice->name }}</td>
								<td class="semi-bold">{{ $invoice->formatted_price }}</td>
								<td class="semi-bold">{{ $invoice->formatted_deposit }}</td>
								<td>
									<a href="{{ route('dashboard.module.booker.services.edit', ['service' => $invoice->id]) }}" class="btn btn-sm btn-outline-secondary rounded d-inline-block">
						    			<i class="fa fa-edit"></i> edit
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