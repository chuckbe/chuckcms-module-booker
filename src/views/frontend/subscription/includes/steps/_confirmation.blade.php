<div class="cmb_confirmation_wrapper d-none">
	<div class="form-group mb-2">
		@guest
		<small class="d-block text-center text-muted"><a href="#" class="cmb_open_login_modal text-muted">Klik om aan te melden</a></small>
		@endguest
		<span class="d-block lead text-black font-weight-bold">Gegevens </span>
	</div>
	

	@guest
	<div class="row">
		<div class="form-group mb-2 col-6 pr-1">
			<label for="" class="sr-only">Voornaam</label>
			<input type="text" class="form-control form-control-sm" name="first_name" placeholder="Voornaam *" required>
		</div>
		<div class="form-group mb-2 col-6 pl-1">
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
		<div class="form-group col-12">
			<label class="mb-0" for="cmb_general_conditions">
				<small>
					<input type="checkbox" class="mr-2" name="general_conditions" id="cmb_general_conditions"> Ik ga akkoord met de <a href="" class="cmb_show_general_conditions_btn text-dark"><u>algemene voorwaarden</u></a>.
				</small>
			</label>
			<div class="w-100 d-block"></div>
			<label class="mb-0" for="cmb_medical_declaration">
				<small>
					<input type="checkbox" class="mr-2" name="medical_declaration" id="cmb_medical_declaration"> Ik ga akkoord met de <a href="" class="cmb_show_medical_declaration_btn text-dark"><u>medische verklaring</u></a>.
				</small>
			</label>
			<div class="w-100 d-block"></div>
			<label class="mb-0" for="cmb_create_customer">
				<small>
					<input type="checkbox" class="mr-2" name="create_customer" id="cmb_create_customer" checked disabled> Ik wil een account aanmaken
				</small>
			</label>
		</div>
	</div>
	@endguest
	

	@auth
	@role('customer')
	@php
	$customer = \Chuckbe\ChuckcmsModuleBooker\Models\Customer::where('user_id', Auth::user()->id)->first();
	@endphp
	<div class="row">
		<div class="form-group mb-2 col-6">
			<label for="" class="sr-only">Voornaam</label>
			<input type="text" class="form-control form-control-sm" name="first_name" placeholder="Voornaam *" value="{{ $customer->first_name }}" disabled required>
		</div>
		<div class="form-group mb-2 col-6">
			<label for="" class="sr-only">Achternaam</label>
			<input type="text" class="form-control form-control-sm" name="last_name" placeholder="Achternaam *" value="{{ $customer->last_name }}" disabled required>
		</div>
	</div>
	<div class="row">
		<div class="form-group mb-2 col-12">
			<label for="" class="sr-only">E-mailadres</label>
			<input type="email" class="form-control form-control-sm" name="email" placeholder="E-mailadres *" value="{{ $customer->email }}" disabled required>
		</div>
	</div>
	<div class="row">
		<div class="form-group mb-2 col-12">
			<label for="" class="sr-only">Telefoonnummer</label>
			<input type="tel" class="form-control form-control-sm" name="tel" placeholder="Telefoonnummer *" value="{{ $customer->tel }}" required>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-12">
			<label class="mb-0" for="cmb_general_conditions">
				<small>
					<input type="checkbox" class="mr-2" name="general_conditions" id="cmb_general_conditions" checked disabled> Ik ga akkoord met de algemene voorwaarden
				</small>
			</label>
			<div class="w-100 d-block"></div>
			<label class="mb-0" for="cmb_medical_declaration">
				<small>
					<input type="checkbox" class="mr-2" name="medical_declaration" id="cmb_medical_declaration" checked disabled> Ik ga akkoord met de medische verklaring
				</small>
			</label>
		</div>
	</div>
	@endrole
	@endauth


	<div class="form-group mb-2">
		<span class="d-block lead text-black font-weight-bold">Overzicht</span>
	</div>
	<div class="cmb_confirmation_overview_section py-2 px-3 border rounded row no-gutters mb-2">
		<div class="col-sm-12 mb-2">
			<span class="font-weight-bold d-block"><span class="cmb_confirmation_sub_plan_text">Subscription Plan</span></span>
			<small class="mr-2"><i class="fa fa-calendar"></i> <span class="cmb_confirmation_type_text">Maandelijks</span></small>
			<small><i class="fa fa-wallet"></i> <span class="cmb_confirmation_price_text">29 EUR</span></small>
			<span class="d-block cmb_confirmation_recurring_text d-none">
				<small>Het bedrag zal de eerstvolgende vernieuwing automatisch gedomiciliëerd worden. Je kan dit abonnement ten alle tijden stopzetten in uw account onder 'Mijn abonnementen'.</small>
			</span>
		</div>
	</div>
	<div class="form-group">
		<p class="font-size-small text-danger font-weight-bold cmb_confirmation_error_msg"></p>
	</div>
	<div class="form-group">
		@auth
		@role('customer')
		<input type="hidden" name="customer_id" value="{{ $customer->id }}">
		@endrole
		@endauth

		@guest
		<input type="hidden" name="customer_id" value="">
		@endguest

		<button class="btn btn-dark btn-large btn-block" id="cmb_confirmation_booker_btn">Betalen & Bevestigen</button>
	</div>
</div>