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
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard.module.booker.customers.index') }}">Klanten</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="Klantenfiche">
                        Fiche: {{ $customer->first_name . ' ' . $customer->last_name }}
                    </li>
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
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="c_subscriptions-tab" href="#" data-toggle="tab" data-target="#c_subscriptions">Abonnementen</a>
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

                    @if(session('status_reset_link'))
                        <div class="alert alert-primary" role="alert">
                            Kopier de volgende link en stuur deze naar de klant: <code>{{ session('reset_link') }}</code>
                        </div>
                    @endif

                    <span><b>Naam</b>: {{ $customer->first_name . ' ' . $customer->last_name }}</span>
                    <div class="w-100 d-block"></div>
                    <span><b>E-mail</b>: {{ $customer->email }} <button class="btn btn-sm btn-outline-secondary" data-target="#editCustomerEmailModal" data-toggle="modal">edit</button></span>
                    <div class="w-100 d-block mb-3"></div>
                    <span class="my-3">
                        <b>Wachtwoord</b>: 
                        @if(!is_null($customer->user_id))
                        <button class="btn btn-sm btn-outline-secondary btn-rounded" data-target="#resetCustomerPasswordModal" data-toggle="modal">
                            activeren en resetlink laten zien
                        </button>
                        @endif
                    </span>
                    @if(!is_null($customer->tel)) 
                    <div class="w-100 d-block mt-3"></div>
                    <span><b>Tel</b>: {{ $customer->tel }} </span>
                    @endif

                    <br>

                    <form action="{{ route('dashboard.module.booker.customers.update_address') }}" method="post">
                        <div class="row">
                            <div class="col-sm-12">          
                                <p class="lead mb-0 mt-2">Bedrijfsgegevens</p>
                            </div>
                            <div class="col-md-7 mb-3">
                                <label for="address">Straat *</label>
                                <input type="text" class="form-control" id="address" name="customer_street" placeholder="Straatnaam" value="{{ old('customer_street', $customer->billing_address_street) }}" required>
                                <div class="invalid-feedback">Adres is een verplicht veld.</div>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label for="housenumber">Huisnummer *</label>
                                <input type="text" class="form-control" id="housenumber" name="customer_housenumber" placeholder="123" value="{{ old('customer_housenumber', $customer->billing_address_housenumber) }}" required>
                                <div class="invalid-feedback">Huisnummer is een verplicht veld.</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="postalcode">Postcode *</label>
                                <input type="text" class="form-control" id="postalcode" name="customer_postalcode" placeholder="" value="{{ old('customer_postalcode', $customer->billing_address_postalcode) }}" required>
                                <div class="invalid-feedback">Postcode is een verplicht veld.</div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="city">Gemeente *</label>
                                <input type="text" class="form-control" id="city" name="customer_city" placeholder="" value="{{ old('customer_city', $customer->billing_address_city) }}" required>
                                <div class="invalid-feedback">Gemeente is een verplicht veld.</div>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label for="country">Land *</label>
                                <select class="custom-select d-block w-100" id="country" name="customer_country" required>
                                    <option selected disabled>Kies...</option>
                                    @foreach(config('chuckcms-module-booker.countries') as $countryKey => $country)
                                    <option value="{{ $countryKey }}" {{ old('customer_country', $customer->billing_address_country) == $countryKey ? 'selected' : '' }} >{{ config('chuckcms-module-booker.countries')[$countryKey] }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Gelieve uw land te selecteren.</div>
                            </div>
                        </div>

                        <hr class="mb-3">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="company">Bedrijf</label>
                                <input type="text" class="form-control" id="company" name="customer_company_name" placeholder="" value="{{ old('customer_company_name', $customer->company_name) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="companyVat">BTW-nummer</label>
                                <input type="text" class="form-control" id="companyVat" name="customer_company_vat" placeholder="" value="{{ old('customer_company_vat', $customer->company_vat) }}">
                                <div class="invalid-feedback">BTW-nummer is een verplicht veld.</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                <input type="hidden" name="_token" value="{{ Session::token() }}">
                                <button class="btn btn-outline-success btn-sm">Opslaan</button>
                            </div>
                        </div>
                    </form>
			    </div>
            </div>
        </div>

        <div class="col-sm-12 tab-pane fade" id="c_appointments" role="tabpanel" aria-labelledby="c_appointments-tab">
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
    	                                <td>
                                      <span class="badge badge-{{ $appointment->has_invoice ? 'success' : 'danger' }} badge-pill">{{ $appointment->has_invoice ? '✓' : '✕' }}</span>
                                      @if($appointment->has_invoice)
                                      <a href="{{ route('dashboard.module.booker.appointments.invoice', ['appointment' => $appointment->id]) }}" class="ml-2 d-inline-block">
                                        <small>download</small>
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
        </div>

        <div class="col-sm-12 tab-pane fade" id="c_subscriptions" role="tabpanel" aria-labelledby="c_subscriptions-tab">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group form-group-default required ">
                        <div class="table-responsive">
                            <table class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Naam</th>
                                        <th scope="col">Vervalt</th>
                                        <th scope="col">Hernieuwen?</th>
                                        <th scope="col">Klant</th>
                                        <th scope="col">Prijs</th>
                                        <th scope="col">Actief?</th>
                                        <th scope="col">Resterend?</th>
                                        <th scope="col" style="min-width:140px">Acties</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customer->subscriptions as $subscription)
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
                                            @if($subscription->has_invoice)
                                            <a href="{{ route('dashboard.module.booker.subscriptions.invoice', ['subscription' => $subscription->id]) }}" class="btn btn-sm btn-outline-secondary rounded d-inline-block">
                                                <i class="fa fa-file-pdf-o"></i> 
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
        </div>
    </div>
</div>
@include('chuckcms-module-booker::backend.customers._edit_modal')
@include('chuckcms-module-booker::backend.customers._reset_modal')
@endsection