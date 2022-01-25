@extends('chuckcms::backend.layouts.base')

@section('title')
	Edit Subscription
@endsection

@section('css')
<style>
.bg-white{
	background: #fff;
}
</style>
@endsection

@section('scripts')

@endsection

@section('content')
    <div class="container min-height p-3">
        <div class="row">
			<div class="col-sm-12">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb mt-3">
						<li class="breadcrumb-item"><a href="{{ route('dashboard.module.booker.subscriptions.index') }}">Abonnementen</a></li>
                        <li class="breadcrumb-item active" aria-current="Locaties">Bewerk abonnement</li>
					</ol>
				</nav>
			</div>
		</div>

		<form role="form" method="POST" action="{{ route('dashboard.module.booker.subscriptions.update') }}">
        
            <div class="row bg-light shadow-sm rounded p-3 mb-3 mx-1">
                <div class="col-sm-12">
                    <div class="form-group-attached">
			            <div class="row">
			              	<div class="col-md-12">
				                <div class="form-group form-group-default required">
				                  	<label>Vervaldatum</label>
				                  	<input type="datetime-local" min="{{ now()->format('Y-m-d').'T'.now()->format('h:i') }}" name="expires_at" class="form-control" value="{{ old('expires_at', $subscription->expires_at->format('Y-m-d').'T'.$subscription->expires_at->format('h:i')) }}" required>
				                </div>
			              	</div>
			              	<div class="col-md-12">
				                <div class="form-group form-group-default required">
				                  	<label>Gewicht (= resterende beurten)</label>
				                  	<input type="number" min="-1" steps="1" max="9999" name="weight" class="form-control" value="{{ old('weight', $subscription->weight) }}" required>
				                </div>
			              	</div>
			            </div>
			        </div>
                </div>
            </div>
            @if ($errors->any())
		      <div class="row bg-light shadow-sm rounded p-3 mb-3 mx-1">
		        <div class="col">
		          <div class="my-3">
		            <div class="alert alert-danger">
		              <ul>
		                @foreach ($errors->all() as $error)
		                  <li>{{ $error }}</li>
		                @endforeach
		              </ul>
		            </div>
		          </div>
		        </div>
		      </div>
		    @endif
            <div class="row">
                <div class="col-sm-12">
                    <div class="my-3">
                        <p class="pull-right">
                            <input type="hidden" name="id" value="{{ $subscription->id }}">
                            <input type="hidden" name="_token" value="{{ Session::token() }}">
                            <button type="submit" name="update" class="btn btn-success btn-cons pull-right m-1" value="1">Opslaan</button>
                            <a href="{{ route('dashboard.module.booker.subscriptions.index') }}" class="pull-right m-1"><button type="button" class="btn btn-info btn-cons">Annuleren</button></a>
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection