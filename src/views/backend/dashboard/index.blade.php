@extends('chuckcms::backend.layouts.base')

@section('title', 'Dashboard')

@section('css')
<meta name="csrf-token" content="{{ Session::token() }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous">
@endsection

@section('content')
<div class="container min-height">
    <div class="row bg-light shadow-sm rounded p-3 mt-5 mb-5 mx-1">
        <div class="col-sm-12 my-3">
			<h4><b>Wat wil je doen?</b></h4>
        </div>
        <div class="col-sm-4">
        	<div class="card rounded p-3">
        		<a href="#" class="text-dark"><i class="fas fa-user"></i> Een klant maken</a>
        	</div>
        </div>
        <div class="col-sm-4">
        	<div class="card rounded p-3">
        		<a href="#" class="text-dark"><i class="fas fa-calendar"></i> Een afspraak inplannen</a>
        	</div>
        </div>
        <div class="col-sm-4 mb-5">
        	<div class="card rounded p-3">
        		<a href="#" class="text-dark"><i class="fas fa-wallet"></i> Een abonnement toevoegen</a>
        	</div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

@endsection