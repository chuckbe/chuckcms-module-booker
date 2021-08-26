@extends('chuckcms::backend.layouts.base')

@section('title')
	Booking Module
@endsection

@section('breadcrumbs')
	<ol class="breadcrumb">
		<li class="breadcrumb-item active"><a href="{{ route('dashboard.module.booker.appointments') }}">Appointments</a></li>
	</ol>
@endsection

@section('content')
<div class="container p3 min-height">
    <div class="row mb-3">
        <div class="col-sm-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-3">
                    <li class="breadcrumb-item active" aria-current="">Afspraak voor {{$appointment->client->name}}</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row bg-light shadow-sm rounded p-3 mb-3 mx-1">
        <div class="col-sm-12 my-3">
            <h3>Client Details</h3>
            <span class="lead">
                Name :  {{$appointment->client->name}} <br>
                Phone :  {{$appointment->client->tel}} <br>
                Email :  {{$appointment->client->email}} <br>
            </span>
            <h3 class="mt-3">Appointment details</h3>
            <span class="lead">
               Location : {{$appointment->location->name}} <br>
               Date : {{date( "d M Y", strtotime($appointment->date))}} <br>
               Booking : {{date("h:i a", strtotime($appointment->time))}} - {{date("h:i a", strtotime("+".$appointment->duration." minutes", strtotime($appointment->time)) )}}<br>
               Price : € {{number_format($appointment->price, 2, ',', '.')}}<br>
               Status : {{$appointment->status}}<br>
               is_cancelled : {{$appointment->is_cancelled}}
            </span>
            <h3 class="mt-3">Services</h3>
            <span class="lead">
                @foreach ($appointment->services as $service)
                    @if($loop->last){{$service->name}}
                    @else{{$service->name.", "}}
                    @endif
                @endforeach
            </span>
        </div>
    </div>
</div>
@endsection


@section('css')

@endsection

@section('scripts')
@endsection