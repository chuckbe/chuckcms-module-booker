<div class="cmb_confirmation_wrapper d-none">
	<div class="form-group mb-2">
		<span class="d-block lead text-black font-weight-bold fw-bold">
			<small class="d-inline-block font-size-small"><a href="#" class="cmb_back_to_datepicker_btn text-dark"><small><i class="fa fa-arrow-left"></i> Terug</small></a></small>
			Klantgegevens <span class="float-right" style="margin-top: -3px;"><small><div class="badge badge-secondary font-weight-normal" role="button" id="cmb_selectExistingCustomer">Klant selecteren</div></small></span>
		</span>
	</div>

	<div class="row d-none" id="cmb_selectExistingCustomerWrapper">
		<div class="form-group mb-2 col-12">
			<select name="customers" id="" class="select2 custom-select">
				<option value="0" selected>-- Nieuwe klant --</option>
				@foreach($customers as $customer)
				<option value="{{ $customer->id }}" 
					data-customer-id="{{ $customer->id }}"
					data-first-name="{{ $customer->first_name }}"
					data-last-name="{{ $customer->last_name }}"
					data-email="{{ $customer->email }}"
					data-tel="{{ $customer->tel }}"
					data-available-weight="{{ $customer->getAvailableWeight()}}"
					data-available-weight-not-on-days="{{ $customer->getDatesWhenAvailableWeightNotAvailable()}}" 
					data-has-free-session="{{ $customer->has_free_session }}">{{ $customer->first_name }} {{ $customer->last_name }} ({{ $customer->email }})</option>
				@endforeach
			</select>
		</div>
	</div>
	
	<div class="row">
		<div class="form-group mb-2 col-6 pr-1 pe-1">
			<label for="" class="sr-only">Voornaam</label>
			<input type="text" class="form-control form-control-sm" name="first_name" placeholder="Voornaam *" required>
		</div>
		<div class="form-group mb-2 col-6 pl-1 ps-1">
			<label for="" class="sr-only">Achternaam</label>
			<input type="text" class="form-control form-control-sm" name="last_name" placeholder="Achternaam *" required>
		</div>
	</div>
	<div class="row">
		<div class="form-group mb-2 col-12">
			<label for="" class="sr-only">E-mailadres</label>
			<input type="email" class="form-control form-control-sm" name="email" placeholder="E-mailadres *" required>
			<small class="cmb_email_suggestion text-muted float-right d-none">Bedoel je misschien <a href="#" class="cmb_email_suggestion_link text-dark"></a>?</small>
			<small class="cmb_email_correction text-muted float-right d-none">Verbeter je e-mailadres.</small>
		</div>
	</div>
	<div class="row">
		<div class="form-group mb-2 col-12">
			<label for="" class="sr-only">Telefoonnummer</label>
			<input type="tel" class="form-control form-control-sm" name="tel" placeholder="Telefoonnummer *" required>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-12 mb-1">
			<label class="mt-0 mb-0" for="cmb_general_conditions">
				<small>
					<input type="checkbox" class="mr-2 me-2" name="general_conditions" id="cmb_general_conditions"> Klant gaat akkoord met de <a href="" class="cmb_show_general_conditions_btn text-dark"><u>algemene voorwaarden</u></a>.
				</small>
			</label>
			<div class="w-100 d-block"></div>
			<label class="mt-0 mb-0" for="cmb_medical_declaration">
				<small>
					<input type="checkbox" class="mr-2 me-2" name="medical_declaration" id="cmb_medical_declaration"> Klant gaat akkoord met de <a href="" class="cmb_show_medical_declaration_btn text-dark"><u>medische verklaring</u></a>.
				</small>
			</label>
			<div class="w-100 d-block"></div>
			<label class="mt-0 mb-0" for="cmb_promo_check">
				<small>
					<input type="checkbox" class="mr-2 me-2" name="promo" id="cmb_promo_check"> De klant op de hoogte houden van de toekomstige acties of promoties.
				</small>
			</label>
			<div class="w-100 d-block"></div>
			<label class="mt-0 mb-0" for="cmb_create_customer">
				<small>
					<input type="checkbox" class="mr-2 me-2" name="create_customer" id="cmb_create_customer"> Klant wil een account aanmaken
				</small>
			</label>
		</div>
	</div>


	<div class="form-group mb-1">
		<span class="d-block lead text-black font-weight-bold fw-bold">Overzicht</span>
	</div>
	<div class="cmb_confirmation_overview_section py-2 px-3 border rounded row no-gutters g-0 mb-2">
		<div class="col-sm-12 mb-2">
			<span class="font-weight-bold fw-bold d-block"><span class="cmb_confirmation_date_text">10 oktober 2021</span> om <span class="cmb_confirmation_time_text">10:00</span></span>
			<small class="mr-2"><i class="fa fa-clock"></i> <span class="cmb_confirmation_duration_text">30 minuten</span></small>
			<small><i class="fa fa-wallet"></i> <span class="cmb_confirmation_price_text">29 EUR</span></small>
		</div>
		<div class="col-sm-12 cmb_confirmation_overview_services mb-2">
			<div class="py-2 px-3 border rounded row no-gutters g-0">
				<div class="form-check col-sm-11">
				  <label class="form-check-label mt-0 row">
				    <span class="col-md-12 cmb_confirmation_overview_services_name_text font-weight-bold fw-bold">Cryosessie <small>(30 min)</small></span>
				  </label>
				</div>
				<span class="col-sm-1 cmb_confirmation_overview_services_dd_btn text-right text-end collapsed" data-toggle="collapse" data-target="#service_description0" aria-expanded="false" aria-controls="service_description0"><i class="fa fa-chevron-down"></i></span>
			    <div class="collapse col-md-12 cmb_confirmation_overview_services_dd_text" id="service_description0">
			    	<p class="mb-0 pb-2 pt-2 description">Mollit tempor veniam ut fugiat amet deserunt do sunt labore sed tempor anim esse incididunt.</p>
				</div>
			</div>
		</div>
	</div>

	<div class="form-group mb-1">
		<span class="d-block lead text-black font-weight-bold fw-bold">Betaling</span>
	</div>
	<div class="row cmb_confirmation_payment_section">
		<div class="form-group col-12 mb-0">
			<label class="mt-0 mb-0" for="cmb_is_free_session">
				<small>
					<input type="checkbox" class="mr-2 me-2" name="is_free_session" id="cmb_is_free_session"> Gratis sessie?
				</small>
			</label>
			<div class="w-100 d-block"></div>
			<label class="mt-0 mb-0" for="cmb_paid">
				<small>
					<input type="checkbox" class="mr-2 me-2" name="paid" id="cmb_paid"> Afspraak is betaald.
				</small>
			</label>
			<div class="w-100 d-block"></div>
			<label class="mt-0 mb-0" for="cmb_needs_payment">
				<small>
					<input type="checkbox" class="mr-2 me-2" name="needs_payment" id="cmb_needs_payment"> Verstuur betalingsuitnodiging.
				</small>
			</label>
			<div class="w-100 d-block"></div>
			<label class="mt-0 mb-0" for="cmb_qr_code">
				<small>
					<input type="checkbox" class="mr-2 me-2" name="qr_code" id="cmb_qr_code"> Toon Bancontact / Payconiq QR code.
				</small>
			</label>
		</div>
	</div>

	<div class="form-group">
		<p class="font-size-small text-danger font-weight-bold fw-bold cmb_confirmation_error_msg"></p>
	</div>
	<div class="form-group">
		<input type="hidden" name="customer_id" value="">
		<button class="btn btn-dark btn-large btn-block" id="cmb_confirmation_booker_btn">Bevestigen</button>
	</div>
</div>