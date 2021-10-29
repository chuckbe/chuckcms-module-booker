@extends('chuckcms::backend.layouts.base')

@section('title')
	Edit Service
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
						<li class="breadcrumb-item"><a href="{{ route('dashboard.module.booker.services.index') }}">Diensten</a></li>
                        <li class="breadcrumb-item active" aria-current="Locaties">Bewerk dienst "{{ $service->name }}"</li>
					</ol>
				</nav>
			</div>
		</div>

		<form role="form" method="POST" action="{{ route('dashboard.module.booker.services.update') }}">
        
            <div class="row bg-light shadow-sm rounded p-3 mb-3 mx-1">
                <div class="col-sm-12">
                    <div class="form-group-attached">
                    	<div class="row">
			              <div class="col-sm-12">
			                <div class="form-group form-group-default">
			                  <label>Actief?</label>
			                  <select name="is_active" id="edit_subscription_plan_is_active" class="custom-control">
			                    <option value="1" @if($subscription_plan->is_active) selected @endif>Ja</option>
			                    <option value="0" @if(!$subscription_plan->is_active) selected @endif>Nee</option>
			                  </select>
			                </div>
			              </div>
			            </div>


			            <div class="row">
			              <div class="col-md-12">
			                <div class="form-group form-group-default required">
			                  <label>Naam *</label>
			                  <input type="text" id="edit_subscription_plan_name" name="name" class="form-control" value="{{ old('name', $subscription_plan->name) }}" required>
			                </div>
			              </div>
			            </div>

			            @if($subscription_plan->type == 'one-off')
			            <div class="row">
			              <div class="col-md-12">
			                <div class="form-group form-group-default required">
			                  <label>Maanden geldig (enkel bij Type: 'eenmalig')</label>
			                  <input type="number" min="0" steps="1" max="9999" id="create_subscription_plan_months_valid" name="months_valid" class="form-control" value="1" required>
			                </div>
			              </div>
			            </div>

			            <div class="row">
			              <div class="col-md-12">
			                <div class="form-group form-group-default required">
			                  <label>Dagen geldig (enkel bij Type: 'eenmalig')</label>
			                  <input type="number" min="0" steps="1" max="9999" id="create_subscription_plan_days_valid" name="days_valid" class="form-control" value="0" required>
			                </div>
			              </div>
			            </div>
			            @endif

			            <div class="row">
			              <div class="col-md-12">
			                <div class="form-group form-group-default required">
			                  <label>Prijs *</label>
			                  <input type="number" min="0.00" step="0.01" id="edit_subscription_plan_price" name="price" class="form-control" value="{{ old('price', $service->price) }}" required>
			                </div>
			              </div>
			            </div>

			            <div class="row">
			              <div class="col-md-12">
			                <div class="form-group form-group-default required">
			                  <label>Voorschot *</label>
			                  <input type="number" min="0.00" steps="0.01" id="edit_subscription_plan_deposit" name="deposit" class="form-control" value="{{ old('deposit', $service->deposit) }}" required>
			                </div>
			              </div>
			            </div>

			            <div class="row">
			              <div class="col-md-12">
			                <div class="form-group form-group-default required">
			                  <label>Gewicht</label>
			                  <input type="number" min="0" steps="1" max="9999" id="edit_subscription_plan_weight" name="weight" class="form-control" value="{{ old('weight', $service->weight) }}" required>
			                </div>
			              </div>
			            </div>

			            <div class="row">
			              <div class="col-md-12">
			                <div class="form-group form-group-default required">
			                  <label>Volgorde</label>
			                  <input type="number" min="0" steps="1" max="9999" id="edit_subscription_plan_order" name="order" class="form-control" value="{{ old('order', $service->order) }}" required>
			                </div>
			              </div>
			            </div>

			            <div class="row">
			              <div class="col-md-12">
			                <div class="form-group form-group-default">
			                  <label>Beschrijving</label>
			                  <textarea id="edit_subscription_plan_description" name="description" class="form-control">{{ old('description', $service->description) }}</textarea>
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
                            <input type="hidden" name="id" value="{{ $service->id }}">
                            <input type="hidden" name="_token" value="{{ Session::token() }}">
                            <button type="submit" name="update" class="btn btn-success btn-cons pull-right m-1" value="1">Opslaan</button>
                            <a href="{{ route('dashboard.module.booker.services.index') }}" class="pull-right m-1"><button type="button" class="btn btn-info btn-cons">Annuleren</button></a>
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection