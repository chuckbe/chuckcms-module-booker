<div class="cmb_services_wrapper">
	<div class="row checkbox-type">
		<div class="col-12 mb-2">
			<span class="lead text-black font-weight-bold">Selecteer het abonnement dat je wil boeken</span>
		</div>
		@foreach($subscription_plans as $plan)
		<div class="col-md-12{{ !$loop->last ? ' mb-2' : '' }}">
			<div class="py-2 px-3 border rounded row no-gutters">
				<div class="form-check col-md-11">
				  <label class="form-check-label row" for="subscription_plan_check{{ $plan->id }}">
				    <input type="checkbox" name="cmb_subscription_plan" value="{{ $plan->id }}" id="subscription_plan_check{{ $plan->id }}" data-name="{{ $plan->name }}" data-type="{{ $plan->type }}" data-weight="{{ $plan->weight }}" data-price="{{ $plan->price }}" data-description="Lorem ipsum dolor sit amet, consectetur adipisicing elit.">
				    <span class="col-md-9 font-weight-bold">
				    	<span class="cmb_subscription_plan_name">{{ $plan->name }}</span>
				    </span>
				    <span class="font-weight-bold col-md-3 text-right">{{ '€ '.number_format($plan->price, 2, ',', '.') }}</span>
				  </label>
				</div>
				<span class="col-md-1 text-right collapsed" type="button" data-toggle="collapse" data-target="#service_description{{ $plan->id }}" aria-expanded="false" aria-controls="service_description{{ $plan->id }}"><i class="fa fa-chevron-down"></i></span>
			    <div class="collapse col-md-12" id="service_description{{ $plan->id }}">
			    	<p class="mb-0 pb-2 pt-2 description">Mollit tempor veniam ut fugiat amet deserunt do sunt labore sed tempor anim esse incididunt.</p>
				</div>
			</div>
		</div>
		@endforeach
		<div class="col-12 mb-2">
			<small class="cmb_services_loading_message form-text text-muted text-right d-none"><i class="fas fa-spinner fa-pulse"></i> Data ophalen...</small>
			<small class="cmb_services_loading_error_message form-text text-muted text-danger text-right d-none"><i class="fas fa-exclamation-circle"></i> Er is iets misgegaan, probeer het nog eens opnieuw...</small>
		</div>
	</div>
</div>