@extends('chuckcms::backend.layouts.base')

@section('title')
Klant: {{ $customer->first_name . ' ' . $customer->last_name }}
@endsection

@section('breadcrumbs')
	<ol class="breadcrumb">
		<li class="breadcrumb-item active"><a href="{{ route('dashboard.module.booker.customers.detail', ['customer' => $customer->id]) }}">Klant: {{ $customer->first_name . ' ' . $customer->last_name }}</a></li>
	</ol>
@endsection

@section('css')

@endsection

@section('scripts')
@if (session('notification'))
@include('chuckcms::backend.includes.notification')
@endif
@endsection

@section('content')
<div class="container min-height p-3">
    <div class="row">
      <div class="col-sm-12">
        <nav aria-label="breadcumb mt-3">
          <ol class="breadcrumb mt-3">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.module.booker.customers.index') }}">Klanten</a></li>
            <li class="breadcrumb-item active" aria-current="Klantenfiche">Fiche: {{ $customer->first_name . ' ' . $customer->last_name }}</li>
          </ol>
        </nav>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
          @if ($errors->any())
          <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
          @endif
      </div>
      <div class="col-sm-12">
        <div class="my-3">
          <ul class="nav nav-tabs justify-content-start" id="pageTab" role="tablist">
              <li class="nav-item" role="presentation">
                <a class="nav-link active" id="c_general-tab" href="#" data-toggle="tab" data-target="#c_general">Algemeen</a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" id="c_appointments-tab" href="#" data-toggle="tab" data-target="#c_appointments">Afspraken</a>
              </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="row tab-content bg-light shadow-sm rounded p-3 mb-3 mx-1" id="customerTabContent">
      	<div class="col-sm-12 tab-pane fade show active" id="c_general" role="tabpanel" aria-labelledby="c_general-tab">
        	<div class="row">
				<div class="col-sm-12">
					<p class="lead">Details</p>

                    <b>Naam</b>: {{ $customer->first_name . ' ' . $customer->last_name }} <br>
                    <b>E-mail</b>: {{ $customer->email }} 
                    @if(!is_null($customer->tel)) 
                    <br>
                    <b>Tel</b>: {{ $customer->tel }} 
                    @endif
                    <br>
                    @if(!is_null($customer->company))
                    <b>Bedrijfsnaam</b>: {{ $customer->json['company']['name'] }} <br>
                    <b>BTW-nummer</b>: {{ $customer->json['company']['vat'] }} <br>
                    @endif

                    @if(!is_null($customer->address))
                    <b>Adres</b>: <br> {{ $customer->json['address']['billing']['street'] . ' ' . $customer->json['address']['billing']['housenumber'] }}, <br> {{ $order->json['address']['billing']['postalcode'] . ' ' . $customer->json['address']['billing']['city'] .', '. config('chuckcms-module-order-form.countries_data.'.$customer->json['address']['billing']['country'].'.native') }} <br>
                    @if(!$customer->json['address']['shipping_equal_to_billing'])
                    <br>
                    <b>Verzend adres: </b><br> 
                    {{ $customer->json['address']['shipping']['street'] . ' ' . $customer->json['address']['shipping']['housenumber'] }}, <br> {{ $customer->json['address']['shipping']['postalcode'] . ' ' . $customer->json['address']['shipping']['city'] .', '. config('chuckcms-module-order-form.countries_data.'.$customer->json['address']['shipping']['country'].'.native') }} <br>
                    @endif
                    @endif
				</div>
			</div>
      	</div>
      	<div class="col-sm-12 tab-pane fade" id="c_appointments" role="tabpanel" aria-labelledby="s_appointments-tab">
        	<div class="row">
			    <div class="col-lg-12">
			        <div class="form-group form-group-default required ">
			            <div class="table-responsive">
			                <table class="table" style="width:100%">
			                    <thead>
			                        <tr>
			                            <th scope="col">#</th>
			                            <th scope="col">Datum</th>
			                            <th scope="col">Uur</th>
			                            <th scope="col">Status</th>
			                            <th scope="col">Factuur?</th>
			                        </tr>
			                    </thead>
			                    <tbody>
			                        @foreach($customer->appointments as $appointment)
		                            <tr class="appointment_line" data-id="{{ $appointment->id }}">
		                                <td>{{ $appointment->id }}</td>
		                                <td>{{ $appointment->date->format('d/m/Y') }}</td>
		                                <td>{{ $appointment->time }}</td>
		                                <td>{{ $appointment->status_short_label }}</td>
		                                <td><span class="badge badge-{{ $appointment->has_invoice ? 'success' : 'danger' }} badge-pill">{{ $appointment->has_invoice ? '✓' : '✕' }}</span></td>
		                            </tr>
			                        @endforeach
			                    </tbody>
			                </table>
			            </div>
			        </div>
			    </div>
			</div>
      	</div>
    </div>
    
</div>



@endsection
